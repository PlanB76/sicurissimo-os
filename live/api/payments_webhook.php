<?php
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$raw=file_get_contents('php://input'); $pdo=db(); $c=cfg();
// Verifica ufficiale PayPal, attiva quando PAYPAL_WEBHOOK_ID e credenziali sono configurate.
function paypalOfficialVerify($raw){
  $id=getenv('PAYPAL_WEBHOOK_ID'); $cid=getenv('PAYPAL_CLIENT_ID'); $sec=getenv('PAYPAL_CLIENT_SECRET');
  if(!$id||!$cid||!$sec) return null; // non configurata, si applica solo HMAC
  $env=getenv('PAYPAL_ENV')==='sandbox'?'api-m.sandbox.paypal.com':'api-m.paypal.com';
  $tk=curl_init("https://$env/v1/oauth2/token"); curl_setopt_array($tk,[CURLOPT_RETURNTRANSFER=>1,CURLOPT_POST=>1,CURLOPT_USERPWD=>$cid.':'.$sec,CURLOPT_POSTFIELDS=>'grant_type=client_credentials']);
  $at=json_decode(curl_exec($tk),true)['access_token']??null; curl_close($tk); if(!$at) return false;
  $body=json_encode(['transmission_id'=>$_SERVER['HTTP_PAYPAL_TRANSMISSION_ID']??'','transmission_time'=>$_SERVER['HTTP_PAYPAL_TRANSMISSION_TIME']??'','cert_url'=>$_SERVER['HTTP_PAYPAL_CERT_URL']??'','auth_algo'=>$_SERVER['HTTP_PAYPAL_AUTH_ALGO']??'','transmission_sig'=>$_SERVER['HTTP_PAYPAL_TRANSMISSION_SIG']??'','webhook_id'=>$id,'webhook_event'=>json_decode($raw,true)]);
  $vr=curl_init("https://$env/v1/notifications/verify-webhook-signature"); curl_setopt_array($vr,[CURLOPT_RETURNTRANSFER=>1,CURLOPT_POST=>1,CURLOPT_HTTPHEADER=>['Content-Type: application/json','Authorization: Bearer '.$at],CURLOPT_POSTFIELDS=>$body]);
  $res=json_decode(curl_exec($vr),true); curl_close($vr);
  return ($res['verification_status']??'')==='SUCCESS';
}

// SICUREZZA: senza segreto configurato o firma valida, NON si attiva nulla.
$secret=$c['webhook_secret'];
if(!$secret){ http_response_code(503); echo 'webhook secret non configurato'; exit; }
$sig=$_SERVER['HTTP_X_SIGNATURE'] ?? '';
$calc=hash_hmac('sha256',$raw,$secret);
if(!is_string($sig) || !hash_equals($calc,$sig)){ ev('webhook_firma_invalida',null,['ip'=>clientIp()]); http_response_code(401); echo 'firma non valida'; exit; }
$pp=paypalOfficialVerify($raw);
if($pp===false){ ev('webhook_paypal_invalido',null,[]); http_response_code(401); echo 'verifica paypal fallita'; exit; }
// $pp===null significa verifica ufficiale non configurata, resta valida la sola firma HMAC condivisa.
$evt=json_decode($raw,true)?:[];
$providerRef=$evt['id']??null; $email=$evt['payer_email']??($evt['email']??null);
$product=$evt['product']??'membership'; $status=$evt['status']??'completed';
$amount=(int)round(($evt['amount']??0)*100); $currency=$evt['currency']??'EUR';
if(!$providerRef||!$email){ http_response_code(400); echo 'bad payload'; exit; }
$q=$pdo->prepare('SELECT id FROM accounts WHERE email=?'); $q->execute([strtolower($email)]); $acc=$q->fetch();
if(!$acc){ http_response_code(404); echo 'account not found'; exit; }
require_once __DIR__.'/../src/recupero81.php';
// PAGAMENTO RICORRENTE FALLITO, avvio recupero
$etype=$evt['event_type']??'';
if(stripos($etype,'PAYMENT.FAILED')!==false || stripos($status,'fail')!==false || $status==='declined'){
  $accFull=$pdo->prepare('SELECT id,sic FROM accounts WHERE email=?'); $accFull->execute([strtolower($email)]); $af=$accFull->fetch();
  $motivo=$evt['reason']??($evt['note']??'addebito non riuscito');
  pagamentoFallito($acc['id'],$af['sic']??null,$product,$evt['piano']??$product,($evt['amount']??0),$motivo,$evt['subscription_id']??null);
  ev('payment_failed',$acc['id'],['product'=>$product]); http_response_code(200); echo 'recupero avviato'; exit;
}
// PAGAMENTO RIENTRATO dopo un fallimento
if(in_array($status,['completed','active','succeeded'],true)){
  pagamentoRecuperato($acc['id'],$evt['piano']??$product);
}
upsert('payments','provider_ref',$providerRef,['account_id'=>$acc['id'],'provider'=>'paypal','amount_cents'=>$amount,'currency'=>$currency,'status'=>$status,'raw'=>$raw,'created_at'=>gmdate('c')]);
$active=in_array($status,['completed','active','succeeded'],true)?'active':'inactive';
// entitlement unico per account+product: uso una chiave composta come stringa
$ek=$acc['id'].':'.$product;
upsert('entitlements','ent_key',$ek,['account_id'=>$acc['id'],'product'=>$product,'status'=>$active,'provider'=>'paypal','provider_ref'=>$providerRef,'valid_until'=>gmdate('c',time()+31*86400)]);
// gamification, acquisto completato premia 50 PV, dedup sulla referenza del pagamento
if($active==='active'){
  $rr='acquisto_'.substr(md5($providerRef),0,16);
  $cg=$pdo->prepare('SELECT 1 FROM pv_ledger WHERE account_id=? AND reason=?'); $cg->execute([$acc['id'],$rr]);
  if(!$cg->fetch()){ pvAdd($acc['id'],50,$rr); ev('gamify_acquisto',$acc['id'],['pv'=>50]); }
  // BONUS PROMO SETTIMANALE, reale, erogato solo se il pagamento avviene nella finestra
  $pc=json_decode(@file_get_contents(__DIR__.'/../data/promo_settimana.json'),true)?:[];
  if(!empty($pc['attiva'])){
    $tz=new DateTimeZone('Europe/Rome'); $ora=new DateTime('now',$tz); $dow=(int)$ora->format('N');
    if($dow>=(int)($pc['giorno_inizio']??1) && $dow<=(int)($pc['giorno_fine']??5)){
      require_once __DIR__.'/../src/promo81w.php'; $stp=promoStato(); $offNow=$stp['offerte']??($pc['offerte']??[]);
      $bonus=0; foreach($offNow as $of){ if(strpos($product,$of['id'])!==false){ $bonus=(int)($of['bonus_pv']??0); break; } }
      if($bonus<=0 && strpos($product,'membership')!==false) $bonus=500;
      if($bonus>0){
        $pw='promo_week_'.$ora->format('o\WW').'_'.$acc['id'];
        $cp=$pdo->prepare('SELECT 1 FROM pv_ledger WHERE account_id=? AND reason=?'); $cp->execute([$acc['id'],$pw]);
        if(!$cp->fetch()){ pvAdd($acc['id'],$bonus,$pw); ev('promo_week_bonus',$acc['id'],['pv'=>$bonus,'product'=>$product]); }
      }
    }
  }
}
if(stripos($product,'pv_pack_')===0){
  $LP=json_decode(@file_get_contents(__DIR__.'/../data/prezzi_pv.json'),true)?:[];
  foreach(($LP['packs']??[]) as $pk){ if('pv_pack_'.$pk['id']===$product){
    $reason='ricarica_wh_'.preg_replace('/[^A-Za-z0-9_-]/','',$providerRef);
    $c=$pdo->prepare('SELECT 1 FROM pv_ledger WHERE reason=?'); $c->execute([$reason]);
    if(!$c->fetch() && function_exists('pvAdd')) pvAdd($acc['id'],(int)$pk['pv']+(int)($pk['bonus']??0),$reason);
    break; } }
}
if($active==='active'){ require_once __DIR__.'/../src/telegram81.php'; @tgConsegnaGruppo($pdo,$acc['id'],$product); }
ev('payment_'.$active,$acc['id'],['product'=>$product,'ref'=>$providerRef]); http_response_code(200); echo 'ok';
