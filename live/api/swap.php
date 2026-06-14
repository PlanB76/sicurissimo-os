<?php
// 81+ SWAP INVISIBILE. Carichi il wallet con carta via PayPal e ricevi PV uno a uno.
// Nessun pacchetto rigido, scegli l importo. I PV sono crediti interni non convertibili in denaro.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$pdo=db();
// riuso la tabella pv_ricariche se c e, altrimenti la creo
try{ $pdo->query('SELECT 1 FROM pv_ricariche LIMIT 1'); }catch(Throwable $e){
  $sq=stripos($pdo->getAttribute(PDO::ATTR_DRIVER_NAME),'sqlite')!==false;
  $pdo->exec($sq?'CREATE TABLE IF NOT EXISTS pv_ricariche(id INTEGER PRIMARY KEY AUTOINCREMENT,account_id INTEGER,pack TEXT,pv INTEGER,euro REAL,provider TEXT,provider_ref TEXT,stato TEXT,created_at TEXT)'
    :'CREATE TABLE IF NOT EXISTS pv_ricariche(id BIGINT AUTO_INCREMENT PRIMARY KEY,account_id BIGINT,pack VARCHAR(30),pv INT,euro DECIMAL(10,2),provider VARCHAR(20),provider_ref VARCHAR(100),stato VARCHAR(20),created_at VARCHAR(40)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
}
$az=$_GET['az']??'config';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'accedi per caricare il wallet'],401);
$in=json_decode(file_get_contents('php://input'),true)?:[];

define('SWAP_MIN',10);
define('SWAP_MAX',2000);

if($az==='config'){
  j(['ok'=>true,'saldo'=>pvBalance($a['id']),'min'=>SWAP_MIN,'max'=>SWAP_MAX,
     'paypal_client'=>getenv('PAYPAL_CLIENT_ID')?:'','paypal_env'=>getenv('PAYPAL_ENV')?:'sandbox',
     'nota'=>'Carichi il wallet e ricevi PV uno a uno. Un PV vale un euro come sconto sui servizi 81+. I PV non sono rimborsabili e non si convertono in contanti.']);
}

// crea ordine, qui solo validazione importo, PayPal crea l ordine lato client
if($az==='prepara'){
  $euro=round((float)($in['euro']??0),2);
  if($euro<SWAP_MIN||$euro>SWAP_MAX) j(['ok'=>false,'err'=>'importo tra '.SWAP_MIN.' e '.SWAP_MAX.' euro'],422);
  j(['ok'=>true,'euro'=>$euro,'pv'=>(int)round($euro),'amount'=>number_format($euro,2,'.','')]);
}

// conferma pagamento PayPal e accredita i PV uno a uno
if($az==='conferma'){
  $ordine=preg_replace('/[^A-Za-z0-9_-]/','',$in['orderID']??'');
  $euro=round((float)($in['euro']??0),2);
  if($euro<SWAP_MIN||$euro>SWAP_MAX||$ordine==='') j(['ok'=>false,'err'=>'dati mancanti'],400);
  // anti doppione
  $c=$pdo->prepare("SELECT 1 FROM pv_ricariche WHERE provider_ref=? AND stato='completata'"); $c->execute(['swap_'.$ordine]);
  if($c->fetch()) j(['ok'=>false,'err'=>'ordine già accreditato'],409);

  $verificato=false;
  if(getenv('PAYPAL_FAKE')==='1'){ $verificato=true; }
  elseif(function_exists('curl_init') && getenv('PAYPAL_CLIENT_ID') && getenv('PAYPAL_CLIENT_SECRET')){
    $base=(getenv('PAYPAL_ENV')==='live')?'https://api-m.paypal.com':'https://api-m.sandbox.paypal.com';
    $ch=curl_init($base.'/v1/oauth2/token');
    curl_setopt_array($ch,[CURLOPT_POST=>1,CURLOPT_POSTFIELDS=>'grant_type=client_credentials',CURLOPT_USERPWD=>getenv('PAYPAL_CLIENT_ID').':'.getenv('PAYPAL_CLIENT_SECRET'),CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>15]);
    $tok=json_decode(curl_exec($ch),true)['access_token']??''; curl_close($ch);
    if($tok){
      $ch=curl_init($base.'/v2/checkout/orders/'.$ordine);
      curl_setopt_array($ch,[CURLOPT_HTTPHEADER=>['Authorization: Bearer '.$tok],CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>15]);
      $o=json_decode(curl_exec($ch),true); curl_close($ch);
      $imp=(float)($o['purchase_units'][0]['amount']['value']??0); $val=$o['purchase_units'][0]['amount']['currency_code']??'';
      if(($o['status']??'')==='COMPLETED' && $val==='EUR' && abs($imp-$euro)<0.01) $verificato=true;
    }
  }
  if(!$verificato){
    $pdo->prepare("INSERT INTO pv_ricariche(account_id,pack,pv,euro,provider,provider_ref,stato,created_at) VALUES(?,?,?,?,?,?,?,?)")
        ->execute([$a['id'],'swap',0,$euro,'paypal','swap_'.$ordine,'respinta',gmdate('c')]);
    j(['ok'=>false,'err'=>'pagamento non verificato, nessun addebito doppio'],402);
  }
  $pv=(int)round($euro); // uno a uno
  $pdo->prepare("INSERT INTO pv_ricariche(account_id,pack,pv,euro,provider,provider_ref,stato,created_at) VALUES(?,?,?,?,?,?,'completata',?)")
      ->execute([$a['id'],'swap',$pv,$euro,'paypal','swap_'.$ordine,gmdate('c')]);
  pvAdd($a['id'],$pv,'swap_'.$ordine);
  ev('swap_pv',$a['id'],['euro'=>$euro,'pv'=>$pv]);
  j(['ok'=>true,'pv'=>$pv,'saldo'=>pvBalance($a['id'])]);
}

j(['ok'=>false,'err'=>'azione sconosciuta'],404);
