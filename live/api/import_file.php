<?php
// Importa i lead dal file CSV già caricato in /data, a blocchi, in modo ripetibile.
// Protetto da IMPORT_KEY. Legge data/lead_import_4000.csv, separatore ; oppure ,
// Crea SIC, 1000 PV, link di attivazione, mette in coda welcome a scaglioni.
// Si può richiamare più volte, riprende da dove era arrivato grazie all offset.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/sic.php'; require_once __DIR__.'/../src/auth_lib.php';
$pdo=db(); $in=body();
$key=getenv('IMPORT_KEY')?:'';
if(!$key || (($in['key']??($_GET['key']??''))!==$key)){ http_response_code(403); j(['ok'=>false,'err'=>'chiave import non valida']); }

$file=__DIR__.'/../data/'.basename($in['file']??($_GET['file']??'lead_import_4000.csv'));
if(!is_file($file)) j(['ok'=>false,'err'=>'file non trovato in data, caricalo prima'],404);

$offset=max(0,(int)($in['offset']??($_GET['offset']??0)));
$blocco=max(50,min(1000,(int)($in['blocco']??($_GET['blocco']??500))));
$perGiorno=max(10,min(500,(int)($in['per_giorno']??($_GET['per_giorno']??50))));
$ownerSic=trim($in['owner_sic']??($_GET['owner_sic']??''))?:null;
$serieDefault=in_array(strtolower(trim($in['serie']??($_GET['serie']??'servizio'))),['nurture','marketing'])?'nurture':'attivazione';
$now=time();

$fh=fopen($file,'r'); if(!$fh) j(['ok'=>false,'err'=>'lettura file fallita'],500);
// rileva separatore dalla prima riga
$first=fgets($fh); $sep=(substr_count($first,';')>=substr_count($first,','))?';':',';
rewind($fh);
$header=fgetcsv($fh,0,$sep);
$header=array_map(function($x){return strtolower(trim(str_replace("\xEF\xBB\xBF",'',$x)));},$header);
$col=array_flip($header);
function val($r,$col,$names){ foreach($names as $n){ if(isset($col[$n])&&isset($r[$col[$n]])) return trim($r[$col[$n]]); } return ''; }

$i=0;$creati=0;$saltati=0;$errori=0;$letti=0;
while(($r=fgetcsv($fh,0,$sep))!==false){
  if($i<$offset){ $i++; continue; }
  if($letti>=$blocco) break;
  $i++; $letti++;
  $email=strtolower(val($r,$col,['email','e-mail','mail']));
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)){ $errori++; continue; }
  $nomeRaw=val($r,$col,['nome','ragione sociale','azienda','nominativo']);
  $tel=val($r,$col,['telefono','tel','cellulare','phone']);
  $rs=$nomeRaw; // nel tuo file il nome e spesso la ragione sociale
  $tipo='persona'; // prudente, l’utente potra passare ad azienda e mettere ATECO
  $marketing=($serieDefault==='nurture');
  $serie=$serieDefault;

  $ex=$pdo->prepare('SELECT id FROM accounts WHERE email=?'); $ex->execute([$email]); $a=$ex->fetch();
  if($a){ $saltati++; garantisciLista($pdo,$email,$nomeRaw,$ownerSic,$marketing,$now,$i,$perGiorno,$serie); continue; }
  try{
    $setTok=bin2hex(random_bytes(24)); $sic=null;
    for($t=0;$t<5;$t++){ $sic=generaSic($pdo);
      try{
        $pdo->prepare('INSERT INTO accounts(sic,email,pass_hash,tipo,nome,ragione_sociale,tel,ref_by,kyc_level,status,email_verified,set_token,pwd_set_at,created_at) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)')
          ->execute([$sic,$email,password_hash(bin2hex(random_bytes(16)),PASSWORD_DEFAULT),$tipo,$nomeRaw?:null,null,$tel?:null,$ownerSic,0,'da_attivare',0,$setTok,null,gmdate('c')]);
        break;
      }catch(PDOException $e){ if($e->getCode()=='23000'){ if(strpos($e->getMessage(),'email')!==false) throw $e; continue; } throw $e; }
    }
    $id=$pdo->lastInsertId();
    pvAdd($id,1000,'welcome_import'); creaWallets($pdo,$id,$sic);
    $pdo->prepare('INSERT INTO network_nodes(account_id,parent_sic,rank,qualified,vol_personal,vol_group,updated_at) VALUES(?,?,?,?,?,?,?)')->execute([$id,$ownerSic,0,0,0,0,gmdate('c')]);
    garantisciLista($pdo,$email,$nomeRaw,$ownerSic,$marketing,$now,$i,$perGiorno,$serie);
    ev('import_account',$id,['sic'=>$sic,'serie'=>$serie]); $creati++;
  }catch(PDOException $e){ $errori++; }
}
$finito=feof($fh); fclose($fh);
$prossimo=$offset+$letti;
j(['ok'=>true,'letti'=>$letti,'creati'=>$creati,'gia_presenti'=>$saltati,'errori'=>$errori,
   'offset_iniziale'=>$offset,'prossimo_offset'=>$prossimo,'finito'=>$finito,
   'separatore'=>$sep,'colonne'=>$header,
   'nota'=>$finito?'Import completato. Le email partono a scaglioni di '.$perGiorno.' al giorno tramite il cron.':'Blocco importato. Richiama di nuovo con offset '.$prossimo.' per continuare.']);

function garantisciLista($pdo,$email,$nome,$ownerSic,$marketing,$now,$idx,$perGiorno,$serie){
  $consTxt=$marketing?'marketing':'servizio';
  $st=$pdo->prepare('SELECT id FROM leads WHERE email=? LIMIT 1'); $st->execute([$email]); $l=$st->fetch();
  if(!$l){ $pdo->prepare('INSERT INTO leads(email,nome,source,owner_sic,stage,consenso_mkt,created_at) VALUES(?,?,?,?,?,?,?)')->execute([$email,$nome?:null,'import_clienti',$ownerSic,'cliente',$consTxt,gmdate('c')]); }
  if($marketing){
    $st=$pdo->prepare('SELECT 1 FROM newsletter WHERE email=?'); $st->execute([$email]);
    if(!$st->fetch()){ $pdo->prepare('INSERT INTO newsletter(email,nome,fonte,consenso,stato,created_at) VALUES(?,?,?,?,?,?)')->execute([$email,$nome?:null,'import_clienti','cliente con base GDPR, import del '.gmdate('Y-m-d'),'ATTIVO',gmdate('c')]); }
  }
  $st=$pdo->prepare('SELECT id FROM welcome_queue WHERE email=?'); $st->execute([$email]);
  if(!$st->fetch()){
    $giornoOffset=intval($idx/$perGiorno);
    $nextAt=gmdate('c', $now + $giornoOffset*86400);
    $pdo->prepare('INSERT INTO welcome_queue(email,nome,step,next_at,stato,serie,unsub_token,created_at) VALUES(?,?,?,?,?,?,?,?)')
      ->execute([$email,$nome?:null,0,$nextAt,'ATTIVO',$serie,bin2hex(random_bytes(20)),gmdate('c')]);
  }
}
