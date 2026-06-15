<?php
// 81+ PREVENTIVO SELF SERVICE. Checkout del preventivo creato dall utente.
require_once __DIR__.'/../src/db.php';
$az=$_GET['az']??'';
$in=json_decode(file_get_contents('php://input'),true)?:[];

if($az==='checkout'){
  $voci=$in['voci']??[]; $tot=(float)($in['totale']??0);
  if(!$voci||$tot<=0) j(['ok'=>false,'err'=>'preventivo vuoto'],422);
  $cid=getenv('PAYPAL_CLIENT_ID')?:'';
  // registro la richiesta di preventivo come lead caldo
  try{
    $pdo=db();
    $pdo->prepare('INSERT INTO events(account_id,type,data,created_at) VALUES(NULL,?,?,?)')
        ->execute(['preventivo_self', json_encode(['voci'=>count($voci),'tot'=>$tot]), gmdate('c')]);
  }catch(Throwable $e){}
  if($cid===''){
    j(['ok'=>false,'err'=>'paypal non configurato','tot'=>$tot]);
  }
  // in produzione si crea l ordine PayPal e si restituisce l url
  j(['ok'=>true,'url'=>'','tot'=>$tot,'nota'=>'collega PAYPAL_CLIENT_ID e SECRET per il pagamento']);
}
j(['ok'=>false,'err'=>'azione sconosciuta'],404);
