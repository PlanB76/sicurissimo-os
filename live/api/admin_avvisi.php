<?php
// 81+ AVVISI DIREZIONE. Solo admin. Lista, conteggio non letti, segna letto.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount();
$isAdmin=$a && (($a['sic']??'')==='SIC-0000001' || ($a['ruolo']??'')==='admin');
if(!$isAdmin) j(['ok'=>false,'err'=>'solo la direzione'],403);
$pdo=db(); $az=$_GET['az']??'lista'; $in=json_decode(file_get_contents('php://input'),true)?:[];

if($az==='lista'){
  $solo=$_GET['solo']??'';
  $sql='SELECT id,tipo,account_id,sic,titolo,dettaglio,gravita,letto,created_at FROM admin_avvisi';
  if($solo==='nonletti') $sql.=' WHERE letto=0';
  $sql.=' ORDER BY id DESC LIMIT 200';
  $rows=$pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  $nl=(int)($pdo->query('SELECT COUNT(*) n FROM admin_avvisi WHERE letto=0')->fetch()['n']??0);
  j(['ok'=>true,'avvisi'=>$rows,'non_letti'=>$nl]);
}
if($az==='conteggio'){
  $nl=(int)($pdo->query('SELECT COUNT(*) n FROM admin_avvisi WHERE letto=0')->fetch()['n']??0);
  j(['ok'=>true,'non_letti'=>$nl]);
}
if($az==='letto'){
  $id=(int)($in['id']??0);
  if($id) $pdo->prepare('UPDATE admin_avvisi SET letto=1 WHERE id=?')->execute([$id]);
  else $pdo->query('UPDATE admin_avvisi SET letto=1 WHERE letto=0'); // tutti
  j(['ok'=>true]);
}
j(['ok'=>false,'err'=>'azione sconosciuta'],404);
