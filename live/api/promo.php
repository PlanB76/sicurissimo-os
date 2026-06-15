<?php
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$action=$_GET['action']??'counter'; $pdo=db(); $cycle=gmdate('Y-m'); $LIMIT=100;
if($action==='counter'){
  $st=$pdo->prepare('SELECT COUNT(*) c FROM promo_early_bird WHERE cycle=?'); $st->execute([$cycle]); $claimed=(int)$st->fetch()['c'];
  j(['ok'=>true,'cycle'=>$cycle,'limit'=>$LIMIT,'claimed'=>$claimed,'remaining'=>max(0,$LIMIT-$claimed)]);
}
if($action==='claim'){
  $a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
  if(!csrfCheck()) j(['ok'=>false,'err'=>'token csrf mancante o non valido'],403);
  $q=$pdo->prepare('SELECT 1 FROM promo_early_bird WHERE cycle=? AND account_id=?'); $q->execute([$cycle,$a['id']]);
  if($q->fetch()) j(['ok'=>true,'già'=>true,'pv'=>pvBalance($a['id'])]);
  $pdo->beginTransaction();
  try{
    if(drv()==='mysql') $pdo->prepare('SELECT COUNT(*) c FROM promo_early_bird WHERE cycle=? FOR UPDATE')->execute([$cycle]);
    $st=$pdo->prepare('SELECT COUNT(*) c FROM promo_early_bird WHERE cycle=?'); $st->execute([$cycle]); $claimed=(int)$st->fetch()['c'];
    if($claimed>=$LIMIT){ $pdo->rollBack(); j(['ok'=>false,'esaurito'=>true,'err'=>'posti del ciclo terminati'],409); }
    $pdo->prepare('INSERT INTO promo_early_bird(cycle,account_id,created_at) VALUES(?,?,?)')->execute([$cycle,$a['id'],gmdate('c')]);
    pvAdd($a['id'],1000,'early_bird_'.$cycle);
    $pdo->commit();
  }catch(Throwable $e){ $pdo->rollBack(); j(['ok'=>false,'err'=>'claim non riuscito'],500); }
  ev('early_bird_claim',$a['id'],['cycle'=>$cycle]); j(['ok'=>true,'pv'=>pvBalance($a['id']),'gift'=>1000]);
}
j(['ok'=>false,'err'=>'azione sconosciuta'],404);
