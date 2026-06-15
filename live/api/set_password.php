<?php
require_once __DIR__.'/../src/db.php';
$in=body(); $pdo=db(); $tok=trim($in['token']??''); $pass=$in['password']??'';
if($tok===''||strlen($pass)<8) j(['ok'=>false,'err'=>'token e password di almeno 8 caratteri richiesti'],422);
$st=$pdo->prepare('SELECT id FROM accounts WHERE set_token=? AND status=?'); $st->execute([$tok,'da_attivare']); $a=$st->fetch();
if(!$a) j(['ok'=>false,'err'=>'token non valido o già usato'],404);
$pdo->prepare('UPDATE accounts SET pass_hash=?,status=?,email_verified=1,set_token=NULL WHERE id=?')->execute([password_hash($pass,PASSWORD_DEFAULT),'attivo',$a['id']]);
ev('password_set',$a['id']); j(['ok'=>true]);
