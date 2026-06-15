<?php
// 81+ RECUPERO ADMIN. Solo direzione. Lista pagamenti falliti e in recupero.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount();
$isAdmin=$a && (($a['sic']??'')==='SIC-0000001' || ($a['ruolo']??'')==='admin');
if(!$isAdmin) j(['ok'=>false,'err'=>'solo la direzione'],403);
$pdo=db(); $az=$_GET['az']??'lista';

if($az==='lista'){
  $rows=$pdo->query("SELECT pf.id,pf.sic,pf.prodotto,pf.piano,pf.importo_euro,pf.motivo,pf.tentativi,pf.stato,pf.created_at,pf.ultimo_sollecito,a.email,a.nome FROM pagamenti_falliti pf LEFT JOIN accounts a ON a.id=pf.account_id ORDER BY pf.id DESC LIMIT 200")->fetchAll(PDO::FETCH_ASSOC);
  $aperti=(int)($pdo->query("SELECT COUNT(*) n FROM pagamenti_falliti WHERE stato='aperto'")->fetch()['n']??0);
  $valore=(float)($pdo->query("SELECT COALESCE(SUM(importo_euro),0) v FROM pagamenti_falliti WHERE stato='aperto'")->fetch()['v']??0);
  $recuperati=(int)($pdo->query("SELECT COUNT(*) n FROM pagamenti_falliti WHERE stato='recuperato'")->fetch()['n']??0);
  j(['ok'=>true,'casi'=>$rows,'aperti'=>$aperti,'valore_a_rischio'=>$valore,'recuperati'=>$recuperati]);
}
// forza un nuovo sollecito manuale
if($az==='sollecita'){
  $in=json_decode(file_get_contents('php://input'),true)?:[]; $id=(int)($in['id']??0);
  require_once __DIR__.'/../src/recupero81.php';
  recuperoSollecito($id,1);
  j(['ok'=>true,'nota'=>'Sollecito inviato']);
}
j(['ok'=>false,'err'=>'azione sconosciuta'],404);
