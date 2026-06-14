<?php
// 81+ SCADENZIARIO API. CRUD, preset per ATECO, feed calendario ICS con token personale.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php'; require_once __DIR__.'/../src/scadenze81.php';
$pdo=db(); scTabelle($pdo);
$az=$_GET['az']??'lista';

// feed calendario, niente sessione, token personale nell'indirizzo, per Google e Apple
if($az==='ics'){
  $t=preg_replace('/[^a-f0-9]/','',$_GET['t']??'');
  if(strlen($t)<24){ http_response_code(403); exit('token mancante'); }
  $q=$pdo->prepare('SELECT * FROM accounts WHERE cal_token=?'); $q->execute([$t]); $acc=$q->fetch();
  if(!$acc){ http_response_code(403); exit('token non valido'); }
  header('Content-Type: text/calendar; charset=utf-8');
  header('Content-Disposition: inline; filename="scadenze_81plus.ics"');
  echo scIcs($pdo,$acc); exit;
}

$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
$in=json_decode(file_get_contents('php://input'),true)?:[];

if($az==='lista'){
  if(empty($a['cal_token'])){ $t=bin2hex(random_bytes(16)); $pdo->prepare('UPDATE accounts SET cal_token=? WHERE id=?')->execute([$t,$a['id']]); $a['cal_token']=$t; }
  j(['ok'=>true,'occorrenze'=>scOccorrenze($pdo,$a['id'],80),
     'feed'=>'https://81plus.net/api/scadenze.php?az=ics&t='.$a['cal_token'],
     'ateco'=>$a['ateco']??'']);
}
if($az==='salva'){
  $tit=trim($in['titolo']??''); if($tit==='') j(['ok'=>false,'err'=>'manca il titolo']);
  $data=trim($in['primo_termine']??''); if(!preg_match('/^\d{4}-\d{2}-\d{2}$/',$data)) j(['ok'=>false,'err'=>'data nel formato anno-mese-giorno']);
  $mesi=max(1,min(120,(int)($in['ricorrenza_mesi']??12)));
  if(!empty($in['id'])){
    $pdo->prepare('UPDATE scadenze_cicliche SET titolo=?,categoria=?,primo_termine=?,ricorrenza_mesi=?,note=?,updated_at=? WHERE id=? AND account_id=?')
        ->execute([$tit,trim($in['categoria']??'altro'),$data,$mesi,substr(trim($in['note']??''),0,250),gmdate('c'),(int)$in['id'],$a['id']]);
  } else {
    if(!rateOk('scad:'.$a['id'],120,3600)) j(['ok'=>false,'err'=>'troppe scadenze in un colpo'],429);
    $pdo->prepare('INSERT INTO scadenze_cicliche(account_id,titolo,categoria,primo_termine,ricorrenza_mesi,note,attivo,updated_at) VALUES(?,?,?,?,?,?,1,?)')
        ->execute([$a['id'],$tit,trim($in['categoria']??'altro'),$data,$mesi,substr(trim($in['note']??''),0,250),gmdate('c')]);
    try{ $c=$pdo->prepare("SELECT 1 FROM pv_ledger WHERE account_id=? AND reason='scadenziario_avviato'"); $c->execute([$a['id']]);
         if(!$c->fetch() && function_exists('pvAdd')) pvAdd($a['id'],10,'scadenziario_avviato'); }catch(Exception $e){}
    if(function_exists('ev')) @ev('scadenza_creata',$a['id']);
  }
  j(['ok'=>true]);
}
if($az==='cancella'){ $pdo->prepare('DELETE FROM scadenze_cicliche WHERE id=? AND account_id=?')->execute([(int)($in['id']??0),$a['id']]); j(['ok'=>true]); }
if($az==='preset'){
  $P=json_decode(file_get_contents(__DIR__.'/../data/scadenze_ateco.json'),true);
  $pacchetto=$in['pacchetto']??'';
  if($pacchetto==='' || $pacchetto==='auto'){
    $ateco=(string)($a['ateco']??''); $pacchetto=$P['mappa_ateco']['default'];
    foreach($P['mappa_ateco'] as $pref=>$pk){ if($pref!=='default' && strpos($ateco,$pref)===0){ $pacchetto=$pk; break; } }
  }
  $voci=array_merge($P['pacchetti']['base']??[],$P['pacchetti'][$pacchetto]??[]);
  $st=$pdo->prepare('INSERT INTO scadenze_cicliche(account_id,titolo,categoria,primo_termine,ricorrenza_mesi,note,attivo,updated_at) VALUES(?,?,?,?,?,?,1,?)');
  $gia=$pdo->prepare('SELECT 1 FROM scadenze_cicliche WHERE account_id=? AND titolo=?');
  $n=0; $partenza=(new DateTime('today',new DateTimeZone('Europe/Rome')))->modify('+1 month')->format('Y-m-d');
  foreach($voci as $v){
    $gia->execute([$a['id'],$v['titolo']]); if($gia->fetch()) continue;
    $st->execute([$a['id'],$v['titolo'],$v['categoria'],$partenza,(int)$v['mesi'],$v['note'],gmdate('c')]); $n++;
  }
  j(['ok'=>true,'aggiunte'=>$n,'pacchetto'=>$pacchetto,'nota'=>'Le cadenze sono suggerimenti da adattare alla tua situazione, le date partono tra un mese, sistemale sulle tue vere.']);
}
j(['ok'=>false,'err'=>'azione sconosciuta'],400);
