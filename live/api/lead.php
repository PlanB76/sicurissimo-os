<?php
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/enroll.php';
$in=body(); if(!rateOk('lead:'.clientIp(),30,3600)) j(['ok'=>false,'err'=>'troppe richieste, riprova più tardi'],429);
$email=trim(strtolower($in['email']??'')); if(!filter_var($email,FILTER_VALIDATE_EMAIL)) j(['ok'=>false,'err'=>'email non valida'],422);
$src=preg_replace('/[^a-z_]/','',strtolower($in['source']??'newsletter'));
$esito=enrollUniversale($email,$in['nome']??'',$src,$in['owner_sic']??null);
j(['ok'=>true,'esito'=>$esito]);
