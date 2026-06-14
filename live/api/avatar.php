<?php
// 81+ AVATAR API, salva url avatar Ready Player Me
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
$pdo=db(); $in=json_decode(file_get_contents('php://input'),true)?:[];
if(($_GET['az']??'')==='salva'){
  $url=trim($in['avatar_url']??'');
  if(!preg_match('#^https://#',$url)) j(['ok'=>false,'err'=>'url non valido'],422);
  $pdo->prepare('UPDATE accounts SET avatar_url=? WHERE id=?')->execute([$url,$a['id']]);
  j(['ok'=>true,'avatar_url'=>$url]);
}
j(['ok'=>true,'avatar_url'=>$a['avatar_url']??'']);
