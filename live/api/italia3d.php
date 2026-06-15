<?php
// 81+ ITALIA 3D. Conta i membri per regione partendo dal CAP nell'indirizzo.
// Modo utente, solo la propria downline. Modo globale con ADMIN_KEY, tutta la rete coi membri per regione.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$pdo=db();
$adminKey=getenv('ADMIN_KEY')?:'';
$globale=$adminKey!=='' && (($_GET['k']??'')===$adminKey);
$a=$globale?null:currentAccount();
if(!$globale && !$a) j(['ok'=>false,'err'=>'non autenticato'],401);

function capRegione($indirizzo){
  if(!preg_match('/\b(\d{5})\b/',(string)$indirizzo,$m)) return 'ND';
  $p=(int)substr($m[1],0,2);
  $mappa=[ 'Lazio'=>[0,1,2,3,4],'Umbria'=>[5,6],'Sardegna'=>[7,8,9],
   'Valle d\'Aosta'=>[11],'Piemonte'=>[10,12,13,14,15,28],'Liguria'=>[16,17,18,19],
   'Lombardia'=>[20,21,22,23,24,25,26,27,46],'Veneto'=>[30,31,32,35,36,37,45],
   'Friuli'=>[33,34],'Trentino'=>[38,39],'Emilia-Romagna'=>[29,40,41,42,43,44,47,48],
   'Toscana'=>[50,51,52,53,54,55,56,57,58,59],'Marche'=>[60,61,62,63],
   'Abruzzo'=>[64,65,66,67],'Puglia'=>[70,71,72,73,74,76],'Basilicata'=>[75,85],
   'Campania'=>[80,81,82,83,84],'Molise'=>[86],'Calabria'=>[87,88,89],'Sicilia'=>[90,91,92,93,94,95,96,97,98]];
  foreach($mappa as $reg=>$pp) if(in_array($p,$pp,true)) return $reg;
  return 'ND';
}
// raccolta sic della rete in vista
$sics=[];
if($globale){
  $rows=$pdo->query("SELECT sic,nome,cognome,ragione_sociale,indirizzo,created_at FROM accounts WHERE email NOT LIKE '%@wallet.81plus%' LIMIT 8000")->fetchAll();
}else{
  $rows=[]; $coda=[$a['sic']]; $visti=[]; $st=$pdo->prepare('SELECT sic,nome,cognome,ragione_sociale,indirizzo,created_at,ref_by FROM accounts WHERE ref_by=? LIMIT 200');
  $me=$pdo->prepare('SELECT sic,nome,cognome,ragione_sociale,indirizzo,created_at FROM accounts WHERE id=?'); $me->execute([$a['id']]); $rows[]=$me->fetch();
  while($coda && count($rows)<2000){ $s=array_shift($coda); if(isset($visti[$s]))continue; $visti[$s]=1;
    $st->execute([$s]); foreach($st->fetchAll() as $r){ $rows[]=$r; $coda[]=$r['sic']; } }
}
$reg=[];
foreach($rows as $r){
  if(!$r) continue;
  $g=capRegione($r['indirizzo']??'');
  if(!isset($reg[$g])) $reg[$g]=['n'=>0,'membri'=>[]];
  $reg[$g]['n']++;
  if(count($reg[$g]['membri'])<40) $reg[$g]['membri'][]=['sic'=>$r['sic'],'nome'=>trim(($r['nome']??'').' '.($r['cognome']??''))?:($r['ragione_sociale']??'membro'),'dal'=>substr($r['created_at']??'',0,10)];
}
j(['ok'=>true,'globale'=>$globale,'regioni'=>$reg,'totale'=>count($rows)]);
