<?php
// 81+ CASSA RICARICHE PV. Pacchetti di crediti interni, pagati con PayPal e verificati
// LATO SERVER sull'ordine vero, mai sulla parola del browser. Canale cripto sui binari
// del fornitore autorizzato via webhook, qui non si custodisce denaro né si muovono chiavi.
// I PV sono crediti interni, valgono dentro l'ecosistema e non si convertono in contanti.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$pdo=db();
function ricTabella($pdo){
  $sq=stripos($pdo->getAttribute(PDO::ATTR_DRIVER_NAME),'sqlite')!==false;
  try{$pdo->query('SELECT 1 FROM pv_ricariche LIMIT 1');}catch(Exception $e){
    $pdo->exec($sq?'CREATE TABLE IF NOT EXISTS pv_ricariche(id INTEGER PRIMARY KEY AUTOINCREMENT,account_id INTEGER,pack TEXT,pv INTEGER,euro REAL,provider TEXT,provider_ref TEXT,stato TEXT,created_at TEXT)'
                  :"CREATE TABLE IF NOT EXISTS pv_ricariche(id BIGINT AUTO_INCREMENT PRIMARY KEY,account_id BIGINT,pack VARCHAR(30),pv INT,euro DECIMAL(10,2),provider VARCHAR(20),provider_ref VARCHAR(100),stato VARCHAR(20),created_at VARCHAR(40)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");}
}
ricTabella($pdo);
$packs=(json_decode(@file_get_contents(__DIR__.'/../data/prezzi_pv.json'),true)?:[])['packs']??[];
$az=$_GET['az']??'config';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
if($az==='config'){
  j(['ok'=>true,'packs'=>$packs,'saldo'=>function_exists('pvBalance')?pvBalance($a['id']):0,
     'paypal_client'=>getenv('PAYPAL_CLIENT_ID')?:'','paypal_env'=>getenv('PAYPAL_ENV')?:'sandbox',
     'crypto_url'=>getenv('CRYPTO_PAY_URL')?:'',
     'nota'=>'I PV sono crediti interni dell ecosistema, un PV vale un euro sui servizi 81+, non sono rimborsabili e non si convertono in denaro.']);
}
if($az==='conferma'){
  $in=json_decode(file_get_contents('php://input'),true)?:[];
  $ordine=preg_replace('/[^A-Za-z0-9_-]/','',$in['orderID']??''); $packId=$in['pack']??'';
  $pack=null; foreach($packs as $p) if($p['id']===$packId){ $pack=$p; break; }
  if(!$pack||$ordine==='') j(['ok'=>false,'err'=>'dati mancanti'],400);
  // dedupe assoluto sull ordine
  $c=$pdo->prepare("SELECT 1 FROM pv_ricariche WHERE provider_ref=? AND stato='completata'"); $c->execute(['pp_'.$ordine]);
  if($c->fetch()) j(['ok'=>false,'err'=>'ordine già accreditato'],409);
  $verificato=false; $dettaglio='';
  if(getenv('PAYPAL_FAKE')==='1'){ $verificato=true; $dettaglio='fake'; }
  elseif(function_exists('curl_init') && getenv('PAYPAL_CLIENT_ID') && getenv('PAYPAL_CLIENT_SECRET')){
    $base=(getenv('PAYPAL_ENV')==='live')?'https://api-m.paypal.com':'https://api-m.sandbox.paypal.com';
    $ch=curl_init($base.'/v1/oauth2/token');
    curl_setopt_array($ch,[CURLOPT_POST=>1,CURLOPT_POSTFIELDS=>'grant_type=client_credentials',CURLOPT_USERPWD=>getenv('PAYPAL_CLIENT_ID').':'.getenv('PAYPAL_CLIENT_SECRET'),CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>15]);
    $tok=json_decode(curl_exec($ch),true)['access_token']??''; curl_close($ch);
    if($tok){
      $ch=curl_init($base.'/v2/checkout/orders/'.$ordine);
      curl_setopt_array($ch,[CURLOPT_HTTPHEADER=>['Authorization: Bearer '.$tok],CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>15]);
      $o=json_decode(curl_exec($ch),true); curl_close($ch);
      $importo=(float)($o['purchase_units'][0]['amount']['value']??0);
      $valuta=$o['purchase_units'][0]['amount']['currency_code']??'';
      if(($o['status']??'')==='COMPLETED' && $valuta==='EUR' && abs($importo-(float)$pack['euro'])<0.01){ $verificato=true; $dettaglio='paypal'; }
      else $dettaglio='stato '.($o['status']??'?').' importo '.$importo.' '.$valuta;
    }
  } else $dettaglio='credenziali PayPal mancanti sul server';
  if(!$verificato){
    $pdo->prepare("INSERT INTO pv_ricariche(account_id,pack,pv,euro,provider,provider_ref,stato,created_at) VALUES(?,?,?,?,?,?,?,?)")
        ->execute([$a['id'],$pack['id'],0,$pack['euro'],'paypal','pp_'.$ordine,'respinta '.$dettaglio,gmdate('c')]);
    j(['ok'=>false,'err'=>'pagamento non verificato, nessun addebito doppio, scrivi alla direzione se hai pagato'],402);
  }
  $tot=(int)$pack['pv']+(int)($pack['bonus']??0);
  $pdo->prepare("INSERT INTO pv_ricariche(account_id,pack,pv,euro,provider,provider_ref,stato,created_at) VALUES(?,?,?,?,?,?,'completata',?)")
      ->execute([$a['id'],$pack['id'],$tot,$pack['euro'],'paypal','pp_'.$ordine,gmdate('c')]);
  if(function_exists('pvAdd')) pvAdd($a['id'],$tot,'ricarica_pp_'.$ordine);
  if(function_exists('ev')) @ev('pv_ricarica',$a['id'],['pack'=>$pack['id']]);
  j(['ok'=>true,'pv'=>$tot,'saldo'=>function_exists('pvBalance')?pvBalance($a['id']):0]);
}
j(['ok'=>false,'err'=>'azione sconosciuta'],400);
