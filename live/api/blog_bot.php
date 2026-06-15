<?php
// Comandi del blog autonomo. Protetto da ADMIN_KEY.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/blog_bot.php';
$pdo=db(); $in=body();
$key=getenv('ADMIN_KEY')?:'';
if(!$key || (($in['key']??($_GET['key']??''))!==$key)){ http_response_code(403); j(['ok'=>false,'err'=>'chiave non valida']); }
$action=$_GET['action']??'giro';

if($action==='giro'){ j(bbGiro(isset($_GET['force']))); }

if($action==='stato'){
  $st=bbStato();
  $ult=$pdo->query("SELECT slug,titolo,categoria,fonte_nome,data,stato FROM blog_posts ORDER BY id DESC LIMIT 15")->fetchAll();
  j(['ok'=>true,'stato'=>$st,'ultimi_articoli'=>$ult]);
}

if($action==='elimina'){
  $slug=trim($in['slug']??($_GET['slug']??'')); if($slug==='') j(['ok'=>false,'err'=>'slug mancante'],422);
  $pdo->prepare('DELETE FROM blog_posts WHERE slug=?')->execute([$slug]);
  bbRigeneraJson($pdo);
  j(['ok'=>true,'eliminato'=>$slug]);
}

if($action==='pubblica'){
  $slug=trim($in['slug']??($_GET['slug']??'')); if($slug==='') j(['ok'=>false,'err'=>'slug mancante'],422);
  $pdo->prepare("UPDATE blog_posts SET stato='pubblicato' WHERE slug=?")->execute([$slug]);
  bbRigeneraJson($pdo);
  j(['ok'=>true,'pubblicato'=>$slug]);
}
j(['ok'=>false,'err'=>'azione sconosciuta'],404);
