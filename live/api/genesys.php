<?php
// 81+ GENESYS, stato delle regioni fondatori. Mostra solo dati veri dal database.
require_once __DIR__.'/../src/db.php';
$pdo=db(); $az=$_GET['az']??'regioni';
if($az==='regioni'){
  $prese=[];
  try{ foreach($pdo->query("SELECT DISTINCT regione FROM accounts WHERE ruolo='fondatore' AND regione IS NOT NULL AND regione<>''") as $r){ $prese[]=$r['regione']; } }catch(Exception $e){}
  j(['ok'=>true,'prese'=>$prese,'totale'=>20]);
}
j(['ok'=>false,'err'=>'azione sconosciuta'],400);
