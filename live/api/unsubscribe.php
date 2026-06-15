<?php
require_once __DIR__.'/../src/db.php';
$t=preg_replace('/[^a-f0-9]/','',$_GET['token']??''); $pdo=db();
if($t){ $st=$pdo->prepare('SELECT email FROM welcome_queue WHERE unsub_token=?'); $st->execute([$t]); $r=$st->fetch();
 if($r){ $pdo->prepare("UPDATE welcome_queue SET stato='ANNULLATO' WHERE unsub_token=?")->execute([$t]);
  $pdo->prepare("UPDATE newsletter SET stato='DISATTIVO' WHERE email=?")->execute([$r['email']]);
  ev('unsubscribe',null,[]); header('Content-Type: text/html; charset=utf-8');
  echo '<body style="background:#070710;color:#f3f3f8;font-family:sans-serif;text-align:center;padding-top:80px"><h2>Disiscrizione completata</h2><p>Non riceverai più le nostre email. Puoi tornare quando vuoi.</p></body>'; exit; } }
http_response_code(404); echo 'token non valido';
