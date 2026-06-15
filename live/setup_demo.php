<?php
// CREDENZIALI DI PROVA. Script monouso, protetto da chiave nell url.
// Dopo il go live cambia le password dal profilo e CANCELLA QUESTO FILE dal server.
$CHIAVE='354bf7fb0033691383c9092f';
if(($_GET['k']??'')!==$CHIAVE){ http_response_code(403); exit('chiave mancante'); }
require_once __DIR__.'/src/db.php'; require_once __DIR__.'/src/sic.php'; require_once __DIR__.'/src/auth_lib.php';
$pdo=db(); header('Content-Type: text/plain; charset=utf-8');
function mkDemo($pdo,$email,$pw,$nome,$tipo,$sicVoluto=null){
  $q=$pdo->prepare('SELECT id,sic FROM accounts WHERE email=?'); $q->execute([$email]);
  if($r=$q->fetch()){ echo $email." esiste gia, SIC ".$r['sic']."\n"; return; }
  $sic=$sicVoluto;
  if($sic){ $c=$pdo->prepare('SELECT 1 FROM accounts WHERE sic=?'); $c->execute([$sic]); if($c->fetch()) $sic=null; }
  if(!$sic) $sic=generaSic($pdo);
  $pdo->prepare('INSERT INTO accounts(sic,email,pass_hash,tipo,nome,status,email_verified,pwd_set_at,created_at) VALUES(?,?,?,?,?,?,1,?,?)')
     ->execute([$sic,strtolower($email),password_hash($pw,PASSWORD_DEFAULT),$tipo,$nome,'attivo',gmdate('c'),gmdate('c')]);
  $id=$pdo->lastInsertId();
  if(function_exists('creaWallets')) @creaWallets($pdo,$id,$sic);
  if(function_exists('pvAdd')) pvAdd($id,1000,'welcome_registrazione');
  echo "creato ".$email."  SIC ".$sic."\n";
}
mkDemo($pdo,'ammiraglio@81plus.net','DIO-Fortezza-2026!','Ammiraglio','persona','SIC-0000001');
mkDemo($pdo,'demo@81plus.net','Demo-Prova-2026!','Mario Demo','persona');
echo "\nFatto. Ora CANCELLA questo file dal server e cambia le password dopo i test.\n";
