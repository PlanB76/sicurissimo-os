<?php
// Genera il codice di aggancio SIC ID <-> Telegram per l'utente loggato.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php'; require_once __DIR__.'/../src/telegram81.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
$pdo=db();
if(($_GET['action']??'')==='stato'){ j(['ok'=>true,'collegato'=>!empty($a['telegram_id'])]); }
if(!rateOk('tglink:'.$a['id'],6,3600)) j(['ok'=>false,'err'=>'troppe richieste'],429);
$code=tgCodiceAggancio($pdo,$a['id']);
j(['ok'=>true,'code'=>$code,'url'=>'https://t.me/'.tgBotUser().'?start='.$code,'collegato'=>!empty($a['telegram_id'])]);
