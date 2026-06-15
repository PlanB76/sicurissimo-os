<?php
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$action=$_GET['action']??'';
$pdo=db();

// Strati academy con soglie PV
$STRATI=[
  'base'=>['nome'=>'81+ Academy BASE','partner'=>'Academy BASE','pv'=>0,'url'=>'api/track.php?to=anfos&src=academy'],
  'vip'=>['nome'=>'81+ Academy VIP','partner'=>'Academy VIP','pv'=>500,'url'=>'api/track.php?to=lezione&src=academy'],
  'elite'=>['nome'=>'81+ Academy ELITE','partner'=>'Academy ELITE','pv'=>1500,'url'=>'https://differentacademy.it/?ref=labomobile']
];

// Catalogo pubblico degli strati, senza dati sensibili
if($action==='strati'){
  $out=[];
  foreach($STRATI as $id=>$s){ $out[]=['id'=>$id,'nome'=>$s['nome'],'partner'=>$s['partner'],'pv_richiesti'=>$s['pv']]; }
  j(['ok'=>true,'strati'=>$out]);
}

// Stato sblocco per l utente loggato
if($action==='stato'){
  $a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
  $pv=pvBalance($a['id']);
  $stato=[];
  foreach($STRATI as $id=>$s){
    $stato[]=['id'=>$id,'nome'=>$s['nome'],'pv_richiesti'=>$s['pv'],'sbloccato'=>($pv>=$s['pv']),'mancano'=>max(0,$s['pv']-$pv)];
  }
  j(['ok'=>true,'pv'=>$pv,'strati'=>$stato]);
}

// Accesso a uno strato, verifica PV server side prima di dare il link
if($action==='accedi'){
  $a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
  $id=preg_replace('/[^a-z]/','',strtolower($_GET['strato']??''));
  if(!isset($STRATI[$id])) j(['ok'=>false,'err'=>'strato non valido'],404);
  $pv=pvBalance($a['id']); $s=$STRATI[$id];
  if($pv<$s['pv']) j(['ok'=>false,'err'=>'PV insufficienti','mancano'=>$s['pv']-$pv],403);
  ev('academy_access',$a['id'],['strato'=>$id]);
  j(['ok'=>true,'url'=>$s['url'],'nome'=>$s['nome']]);
}

j(['ok'=>false,'err'=>'azione sconosciuta'],404);
