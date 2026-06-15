<?php
// Import in blocco di una lista clienti con base GDPR. Crea SIC completo, 1000 PV, accesso dashboard
// con link magico, mette in coda welcome a scaglioni. Protetto da IMPORT_KEY, mai pubblico.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/sic.php'; require_once __DIR__.'/../src/auth_lib.php';
$pdo=db(); $in=body();
$key=getenv('IMPORT_KEY')?:'';
if(!$key || (($in['key']??($_GET['key']??''))!==$key)){ http_response_code(403); j(['ok'=>false,'err'=>'chiave import non valida']); }

$contatti=$in['contatti']??[]; if(!is_array($contatti)||!count($contatti)) j(['ok'=>false,'err'=>'nessun contatto'],422);
$perGiorno=max(10,min(500,(int)($in['per_giorno']??50)));   // tetto giornaliero, riscaldamento dominio
$ownerSic=trim($in['owner_sic']??'')?:null;                  // di solito il SIC dell’Ammiraglio
$c=cfg(); $base='https://'.$c['company']['dominio'];
$now=time();

$creati=0;$saltati=0;$errori=0;$idx=0;$report=[];
foreach($contatti as $row){
  $email=strtolower(trim($row['email']??''));
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)){ $errori++; continue; }
  $nome=trim($row['nome']??''); $cognome=trim($row['cognome']??''); $rs=trim($row['ragione_sociale']??'');
  $tel=trim($row['tel']??''); $cf=strtoupper(trim($row['codice_fiscale']??''));
  // consenso: marketing = nurture completo, altrimenti solo email di servizio con attivazione
  $cons=strtolower(trim($row['consenso']??''));
  $marketing=in_array($cons,['marketing','si','sì','yes','1','true']);
  $serie=$marketing?'nurture':'attivazione';
  $nomeIntero=trim($nome.' '.$cognome).($rs?(' '.$rs):'');

  // dedup per email su accounts
  $ex=$pdo->prepare('SELECT id,sic FROM accounts WHERE email=?'); $ex->execute([$email]); $a=$ex->fetch();
  if($a){
    $saltati++;
    // assicura presenza in leads e newsletter, senza toccare account
    garantisciLista($pdo,$email,$nomeIntero,$ownerSic,$marketing,$now,$idx,$perGiorno,$serie);
    $idx++; continue;
  }
  try{
    $setTok=bin2hex(random_bytes(24));
    $sic=null;
    for($t=0;$t<5;$t++){ $sic=generaSic($pdo);
      try{
        $pdo->prepare('INSERT INTO accounts(sic,email,pass_hash,tipo,nome,cognome,ragione_sociale,tel,ref_by,codice_fiscale,kyc_level,status,email_verified,set_token,created_at) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)')
          ->execute([$sic,$email,password_hash(bin2hex(random_bytes(16)),PASSWORD_DEFAULT),$rs?'azienda':'persona',$nome?:null,$cognome?:null,$rs?:null,$tel?:null,$ownerSic,$cf?:null,0,'da_attivare',0,$setTok,gmdate('c')]);
        break;
      }catch(PDOException $e){ if($e->getCode()=='23000'){ if(strpos($e->getMessage(),'email')!==false){ throw $e; } continue; } throw $e; }
    }
    $id=$pdo->lastInsertId();
    pvAdd($id,1000,'welcome_import');
    creaWallets($pdo,$id,$sic);
    $pdo->prepare('INSERT INTO network_nodes(account_id,parent_sic,rank,qualified,vol_personal,vol_group,updated_at) VALUES(?,?,?,?,?,?,?)')->execute([$id,$ownerSic,0,0,0,0,gmdate('c')]);
    garantisciLista($pdo,$email,$nomeIntero,$ownerSic,$marketing,$now,$idx,$perGiorno,$serie);
    ev('import_account',$id,['sic'=>$sic,'serie'=>$serie]);
    $creati++;
  }catch(PDOException $e){ $errori++; }
  $idx++;
}
j(['ok'=>true,'creati'=>$creati,'gia_presenti'=>$saltati,'errori'=>$errori,
   'per_giorno'=>$perGiorno,'giorni_invio_stimati'=>(int)ceil(($creati+$saltati)/$perGiorno),
   'nota'=>'Account creati con SIC, 1000 PV e link di attivazione. Le email partono a scaglioni di '.$perGiorno.' al giorno per proteggere il dominio. I contatti senza consenso marketing ricevono solo l’email di servizio con l’attivazione.']);

// leads + newsletter + welcome a scaglioni
function garantisciLista($pdo,$email,$nome,$ownerSic,$marketing,$now,$idx,$perGiorno,$serie){
  $consTxt=$marketing?'marketing':'servizio';
  $st=$pdo->prepare('SELECT id FROM leads WHERE email=? LIMIT 1'); $st->execute([$email]); $l=$st->fetch();
  if(!$l){ $pdo->prepare('INSERT INTO leads(email,nome,source,owner_sic,stage,consenso_mkt,created_at) VALUES(?,?,?,?,?,?,?)')->execute([$email,$nome?:null,'import_clienti',$ownerSic,'cliente',$consTxt,gmdate('c')]); }
  if($marketing){
    $st=$pdo->prepare('SELECT 1 FROM newsletter WHERE email=?'); $st->execute([$email]);
    if(!$st->fetch()){ $pdo->prepare('INSERT INTO newsletter(email,nome,fonte,consenso,stato,created_at) VALUES(?,?,?,?,?,?)')
      ->execute([$email,$nome?:null,'import_clienti','SI, cliente con base GDPR, import del '.gmdate('Y-m-d'),'ATTIVO',gmdate('c')]); }
  }
  // welcome a scaglioni: il giorno parte in base alla posizione nella lista
  $st=$pdo->prepare('SELECT id FROM welcome_queue WHERE email=?'); $st->execute([$email]);
  if(!$st->fetch()){
    $giornoOffset=intval($idx/$perGiorno);
    $nextAt=gmdate('c', $now + $giornoOffset*86400);
    $pdo->prepare('INSERT INTO welcome_queue(email,nome,step,next_at,stato,serie,unsub_token,created_at) VALUES(?,?,?,?,?,?,?,?)')
      ->execute([$email,$nome?:null,0,$nextAt,'ATTIVO',$serie,bin2hex(random_bytes(20)),gmdate('c')]);
  }
}
