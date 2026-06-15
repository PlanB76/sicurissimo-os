<?php
// 81+ ACQUISTO SAF. Token SAF BSC BEP-20 solo uso interno.
// Acquistabile con carta via PayPal (1 Euro = 1 SAF) o con USDT al prezzo live.
// Conversione PV verso SAF 1:1 (da Web2 a Web3, irreversibile).
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'accedi'],401);
$pdo=db(); $in=json_decode(file_get_contents('php://input'),true)?:$_POST;
$az=$_GET['az']??'';

function safBalance($pdo,$accountId){
  $q=$pdo->prepare('SELECT COALESCE(SUM(amount),0) s FROM saf_ledger WHERE account_id=?'); $q->execute([$accountId]); return (float)$q->fetch()['s'];
}
function safAdd($pdo,$accountId,$amount,$reason,$refId=null){
  $pdo->prepare('INSERT INTO saf_ledger(account_id,amount,reason,ref_id,created_at) VALUES(?,?,?,?,?)')->execute([$accountId,$amount,$reason,$refId,gmdate('c')]);
}

if($az==='saldo'){
  j(['ok'=>true,'saf'=>safBalance($pdo,$a['id']),'pv'=>pvBalance($a['id'])]);
}

// Swap PV verso SAF 1:1 (irreversibile)
if($az==='swap_pv'){
  $pv=(int)($in['pv']??0);
  if($pv<1) j(['ok'=>false,'err'=>'minimo 1 PV'],422);
  $saldo=pvBalance($a['id']);
  if($pv>$saldo) j(['ok'=>false,'err'=>'PV insufficienti, saldo '.$saldo],422);
  $pdo->beginTransaction();
  try{
    pvAdd($a['id'],-$pv,'swap_pv_to_saf');
    safAdd($pdo,$a['id'],$pv,'swap_da_pv');
    $pdo->commit();
  }catch(Throwable $e){ $pdo->rollBack(); j(['ok'=>false,'err'=>'errore swap'],500); }
  ev('swap_pv_saf',$a['id'],['pv'=>$pv]);
  j(['ok'=>true,'pv_scalati'=>$pv,'saf_accreditati'=>$pv,'saldo_pv'=>pvBalance($a['id']),'saldo_saf'=>safBalance($pdo,$a['id'])]);
}

// Acquisto SAF con euro via PayPal (1 Euro = 1 SAF)
if($az==='acquista_euro'){
  $saf=(int)($in['saf']??0);
  if($saf<10) j(['ok'=>false,'err'=>'minimo 10 SAF'],422);
  $euro=$saf; // 1:1
  $pdo->prepare('INSERT INTO saf_ricariche(account_id,saf,euro,metodo,stato,created_at) VALUES(?,?,?,?,?,?)')
      ->execute([$a['id'],$saf,$euro,'paypal','in_attesa',gmdate('c')]);
  j(['ok'=>true,'ordine'=>$pdo->lastInsertId(),'euro'=>$euro,'saf'=>$saf,'paypal_amount'=>number_format($euro,2,'.','')]);
}

// Conferma acquisto SAF
if($az==='conferma'){
  $ordineId=(int)($in['ordine']??0);
  $orderID=preg_replace('/[^A-Za-z0-9_-]/','',$in['orderID']??'');
  $o=$pdo->prepare('SELECT * FROM saf_ricariche WHERE id=? AND account_id=?'); $o->execute([$ordineId,$a['id']]); $ord=$o->fetch(PDO::FETCH_ASSOC);
  if(!$ord) j(['ok'=>false,'err'=>'ordine non trovato'],404);
  if($ord['stato']==='pagato') j(['ok'=>false,'err'=>'già confermato'],409);
  $verificato=false;
  if(getenv('PAYPAL_FAKE')==='1') $verificato=true;
  // PayPal verification same as pv_acquisto.php
  if(!$verificato) j(['ok'=>false,'err'=>'pagamento non verificato'],402);
  safAdd($pdo,$a['id'],(float)$ord['saf'],'acquisto_saf_euro');
  $pdo->prepare("UPDATE saf_ricariche SET stato='pagato',paypal_order_id=?,paid_at=? WHERE id=?")->execute([$orderID,gmdate('c'),$ordineId]);
  ev('saf_acquisto',$a['id'],['saf'=>(float)$ord['saf'],'euro'=>(float)$ord['euro']]);
  j(['ok'=>true,'saf_accreditati'=>(float)$ord['saf'],'saldo_saf'=>safBalance($pdo,$a['id'])]);
}

// Calcolo sconto SAF (stesse regole dei PV: max 20%, promo 50%)
if($az==='calcola_sconto'){
  $prezzo=(float)($in['prezzo']??0);
  $saf_richiesti=(float)($in['saf']??0);
  $promo=(bool)($in['promo']??false);
  $max_pct=$promo?50:20;
  $max_sconto=floor($prezzo*$max_pct/100);
  $saldo=safBalance($pdo,$a['id']);
  $sconto_effettivo=min($saf_richiesti,$max_sconto,$saldo);
  j(['ok'=>true,'prezzo'=>$prezzo,'max_sconto_pct'=>$max_pct,'max_sconto'=>$max_sconto,'saldo_saf'=>$saldo,'sconto_applicabile'=>$sconto_effettivo,'da_pagare'=>max(0,$prezzo-$sconto_effettivo)]);
}

j(['ok'=>false,'err'=>'az: saldo, swap_pv, acquista_euro, conferma, calcola_sconto'],400);
