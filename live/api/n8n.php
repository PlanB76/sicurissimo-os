<?php
// 81+ PONTE N8N. Architettura a eventi verso e dalle automazioni esterne.
// In uscita, n8n interroga gli eventi nuovi. In entrata, n8n può registrare eventi e accrediti PV mappati.
// Tutto firmato con N8N_SECRET nelle variabili d'ambiente, senza chiave il ponte è spento.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$sec=getenv('N8N_SECRET')?:'';
if($sec===''){ j(['ok'=>false,'err'=>'ponte spento, imposta N8N_SECRET'],503); }
$firma=$_SERVER['HTTP_X_81PLUS_SIGNATURE']??($_GET['s']??'');
if(!hash_equals(hash_hmac('sha256','n8n81',$sec),$firma)) j(['ok'=>false,'err'=>'firma non valida'],403);
$pdo=db(); $az=$_GET['az']??'eventi';
if($az==='eventi'){
  $da=(int)($_GET['da']??0);
  $q=$pdo->prepare('SELECT e.id,e.type,e.created_at,a.sic FROM events e LEFT JOIN accounts a ON a.id=e.account_id WHERE e.id>? ORDER BY e.id ASC LIMIT 200');
  $q->execute([$da]); $rows=$q->fetchAll();
  j(['ok'=>true,'eventi'=>$rows,'ultimo'=>$rows?(int)end($rows)['id']:$da]);
}
if($az==='evento'){
  $in=json_decode(file_get_contents('php://input'),true)?:[];
  $tipo=preg_replace('/[^a-z0-9_]/','',strtolower($in['tipo']??'')); if($tipo==='') j(['ok'=>false,'err'=>'manca il tipo'],400);
  $sic=strtoupper(trim($in['sic']??'')); $acc=null;
  if($sic!==''){ $q=$pdo->prepare('SELECT * FROM accounts WHERE sic=?'); $q->execute([$sic]); $acc=$q->fetch(); }
  if(function_exists('ev')) @ev('n8n_'.$tipo,$acc['id']??null,$in['dati']??[]);
  // PV solo da mappa fissa, mai importi liberi dall'esterno
  $mappa=['mission_completed'=>20,'webinar_presente'=>30,'video_completato'=>10];
  if($acc && isset($mappa[$tipo])){
    $reason='n8n_'.$tipo.'_'.substr(preg_replace('/[^a-z0-9]/','',strtolower($in['rif']??date('Ymd'))),0,30);
    try{ $c=$pdo->prepare('SELECT 1 FROM pv_ledger WHERE account_id=? AND reason=?'); $c->execute([$acc['id'],$reason]);
         if(!$c->fetch() && function_exists('pvAdd')){ pvAdd($acc['id'],$mappa[$tipo],$reason); j(['ok'=>true,'pv'=>$mappa[$tipo]]); } }catch(Exception $e){}
  }
  j(['ok'=>true,'pv'=>0]);
}
if($az==='lead_nuovo'){
  $in=json_decode(file_get_contents('php://input'),true)?:[];
  $email=filter_var(trim($in['email']??''),FILTER_VALIDATE_EMAIL); if(!$email) j(['ok'=>false,'err'=>'email non valida'],400);
  $nome=substr(trim($in['nome']??''),0,80); $fonte=preg_replace('/[^a-z0-9_]/','',strtolower($in['fonte']??'webinar'));
  $owner=strtoupper(trim($in['owner_sic']??''));
  try{ $q=$pdo->prepare('INSERT INTO leads(nome,email,source,owner_sic,stage,created_at) VALUES(?,?,?,?,?,?)');
       $q->execute([$nome,$email,$fonte,$owner,'nuovo',gmdate('c')]); }catch(Exception $e){}
  j(['ok'=>true,'lead'=>$email]);
}
if($az==='pv_premia'){
  // PV solo da mappa fissa, mai importi liberi. Stessa regola dell evento.
  $in=json_decode(file_get_contents('php://input'),true)?:[];
  $sic=strtoupper(trim($in['sic']??'')); $motivo=preg_replace('/[^a-z0-9_]/','',strtolower($in['motivo']??''));
  $mappa=['login'=>1,'corso'=>50,'referral'=>100,'profilo'=>0,'webinar'=>30,'missione'=>20];
  if($sic===''||!isset($mappa[$motivo])) j(['ok'=>false,'err'=>'motivo non in mappa'],400);
  $q=$pdo->prepare('SELECT * FROM accounts WHERE sic=?'); $q->execute([$sic]); $a=$q->fetch();
  if(!$a) j(['ok'=>false,'err'=>'sic non trovato'],404);
  $reason='n8n_'.$motivo.'_'.date('Ymd');
  try{ $c=$pdo->prepare('SELECT 1 FROM pv_ledger WHERE account_id=? AND reason=?'); $c->execute([$a['id'],$reason]);
       if($c->fetch()) j(['ok'=>true,'pv'=>0,'nota'=>'gia premiato oggi']);
       if($mappa[$motivo]>0 && function_exists('pvAdd')) pvAdd($a['id'],$mappa[$motivo],$reason); }catch(Exception $e){}
  j(['ok'=>true,'pv'=>$mappa[$motivo]]);
}
if($az==='scadenze_vicine'){
  $g=(int)($_GET['giorni']??30); $out=[]; $oggi=time(); $limite=$oggi+$g*86400;
  try{ $q=$pdo->query("SELECT s.*,a.sic FROM scadenze_cicliche s JOIN accounts a ON a.id=s.account_id WHERE s.attivo=1");
       foreach($q as $r){
         // calcolo la prossima occorrenza dal primo termine sommando la ricorrenza finche supera oggi
         $t=strtotime($r['primo_termine']); if(!$t) continue;
         $passo=max(1,(int)$r['ricorrenza_mesi']);
         $guard=0; while($t<$oggi && $guard<600){ $t=strtotime('+'.$passo.' months',$t); $guard++; }
         if($t>=$oggi && $t<=$limite){ $gg=(int)(($t-$oggi)/86400); $out[]=['sic'=>$r['sic'],'documento'=>$r['titolo'],'giorni'=>$gg]; }
       }
  }catch(Exception $e){}
  j(['ok'=>true,'scadenze'=>$out]);
}
if($az==='mail_scadenza' || $az==='offerta_pack'){
  // questi non toccano denaro ne PV, solo notifica. log evento e ok.
  $in=json_decode(file_get_contents('php://input'),true)?:[];
  if(function_exists('ev')) @ev('n8n_'.$az,null,$in);
  j(['ok'=>true,'notificato'=>true]);
}

j(['ok'=>false,'err'=>'azione sconosciuta'],400);
