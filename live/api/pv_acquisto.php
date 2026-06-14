<?php
// 81+ ACQUISTO PV. L utente compra blocchi di PV con carta via PayPal.
// Blocchi fissi: 100 PV (100 euro), 300 PV (300 euro), 500 PV (500 euro).
// 1 PV = 1 Euro. Swap automatico e invisibile.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'accedi'],401);
$pdo=db(); $in=json_decode(file_get_contents('php://input'),true)?:$_POST;
$az=$_GET['az']??'';

define('PV_BLOCCHI',[100=>100,300=>300,500=>500]);
define('PV_SCONTO_MAX_PCT',20); // tetto sconto PV gamification: 20% del prezzo
define('PV_SCONTO_PROMO_PCT',50); // tetto promo speciali: 50%

if($az==='blocchi'){
  j(['ok'=>true,'blocchi'=>array_map(fn($pv)=>['pv'=>$pv,'euro'=>$pv,'label'=>$pv.' PV per '.$pv.' euro'],array_keys(PV_BLOCCHI)),'sconto_max_pct'=>PV_SCONTO_MAX_PCT]);
}

if($az==='acquista'){
  $blocco=(int)($in['blocco']??0);
  if(!isset(PV_BLOCCHI[$blocco])) j(['ok'=>false,'err'=>'blocco non valido, scegli 100, 300 o 500'],422);
  $euro=PV_BLOCCHI[$blocco];
  // crea ordine PV, attende conferma PayPal
  $pdo->prepare('INSERT INTO pv_ricariche(account_id,pv,euro,stato,created_at) VALUES(?,?,?,?,?)')
      ->execute([$a['id'],$blocco,$euro,'in_attesa',gmdate('c')]);
  $ordineId=$pdo->lastInsertId();
  j(['ok'=>true,'ordine'=>$ordineId,'euro'=>$euro,'pv'=>$blocco,'paypal_amount'=>number_format($euro,2,'.','')]);
}

if($az==='conferma'){
  $ordineId=(int)($in['ordine']??0);
  $orderID=preg_replace('/[^A-Za-z0-9_-]/','',$in['orderID']??'');
  $o=$pdo->prepare('SELECT * FROM pv_ricariche WHERE id=? AND account_id=?'); $o->execute([$ordineId,$a['id']]); $ord=$o->fetch(PDO::FETCH_ASSOC);
  if(!$ord) j(['ok'=>false,'err'=>'ordine non trovato'],404);
  if($ord['stato']==='pagato') j(['ok'=>false,'err'=>'già confermato'],409);
  // verifica PayPal (stessa logica di pixel.php)
  $verificato=false;
  $euro=(float)$ord['euro'];
  if($euro<=0.001) $verificato=true;
  elseif(getenv('PAYPAL_FAKE')==='1') $verificato=true;
  elseif(function_exists('curl_init') && getenv('PAYPAL_CLIENT_ID') && getenv('PAYPAL_CLIENT_SECRET') && $orderID!==''){
    $base=(getenv('PAYPAL_ENV')==='live')?'https://api-m.paypal.com':'https://api-m.sandbox.paypal.com';
    $ch=curl_init($base.'/v1/oauth2/token');
    curl_setopt_array($ch,[CURLOPT_POST=>1,CURLOPT_POSTFIELDS=>'grant_type=client_credentials',CURLOPT_USERPWD=>getenv('PAYPAL_CLIENT_ID').':'.getenv('PAYPAL_CLIENT_SECRET'),CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>15]);
    $tok=json_decode(curl_exec($ch),true)['access_token']??''; curl_close($ch);
    if($tok){
      $ch=curl_init($base.'/v2/checkout/orders/'.$orderID);
      curl_setopt_array($ch,[CURLOPT_HTTPHEADER=>['Authorization: Bearer '.$tok],CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>15]);
      $r=json_decode(curl_exec($ch),true); curl_close($ch);
      if(($r['status']??'')==='COMPLETED' && ($r['purchase_units'][0]['amount']['currency_code']??'')==='EUR') $verificato=true;
    }
  }
  if(!$verificato) j(['ok'=>false,'err'=>'pagamento non verificato'],402);
  pvAdd($a['id'],(int)$ord['pv'],'acquisto_pv_blocco');
  $pdo->prepare("UPDATE pv_ricariche SET stato='pagato',paypal_order_id=?,paid_at=? WHERE id=?")->execute([$orderID,gmdate('c'),$ordineId]);
  ev('pv_acquisto',$a['id'],['pv'=>(int)$ord['pv'],'euro'=>$euro]);
  j(['ok'=>true,'pv_accreditati'=>(int)$ord['pv'],'saldo'=>pvBalance($a['id'])]);
}

// Calcolo sconto PV: quanto può scontare l utente su un prezzo
if($az==='calcola_sconto'){
  $prezzo=(float)($in['prezzo']??0);
  $pv_richiesti=(int)($in['pv']??0);
  $promo=(bool)($in['promo']??false);
  $max_pct=$promo?PV_SCONTO_PROMO_PCT:PV_SCONTO_MAX_PCT;
  $max_sconto=floor($prezzo*$max_pct/100);
  $saldo=pvBalance($a['id']);
  // PV gamification = tutti quelli con reason che non inizia per 'acquisto_pv'
  $pv_gam=0; try{$pv_gam=(int)$pdo->prepare("SELECT COALESCE(SUM(amount),0) s FROM pv_ledger WHERE account_id=? AND amount>0 AND reason NOT LIKE 'acquisto_pv%'")->execute([$a['id']])?:0;
    $q=$pdo->prepare("SELECT COALESCE(SUM(amount),0) s FROM pv_ledger WHERE account_id=? AND amount>0 AND reason NOT LIKE 'acquisto_pv%'"); $q->execute([$a['id']]); $pv_gam=(int)$q->fetch()['s'];
  }catch(Throwable $e){}
  $sconto_effettivo=min($pv_richiesti,$max_sconto,$saldo);
  j(['ok'=>true,'prezzo'=>$prezzo,'max_sconto_pct'=>$max_pct,'max_sconto_euro'=>$max_sconto,'pv_gamification'=>$pv_gam,'pv_saldo'=>$saldo,'sconto_applicabile'=>$sconto_effettivo,'da_pagare'=>max(0,$prezzo-$sconto_effettivo)]);
}

j(['ok'=>false,'err'=>'az: blocchi, acquista, conferma, calcola_sconto'],400);
