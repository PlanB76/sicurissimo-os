<?php
// 81+ PILLOLE VIDEO. La direzione incolla il link e la trascrizione di un video,
// l'AI la trasforma in micro lezione con azioni pratiche, pubblicata in dashboard. Premio lettura 5 PV.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php'; require_once __DIR__.'/../src/ai81.php';
$pdo=db(); $f=__DIR__.'/../data/pillole.json';
$az=$_GET['az']??'lista';
if($az==='lista'){
  $p=json_decode(@file_get_contents($f),true)?:['pillole'=>[]];
  j(['ok'=>true,'pillole'=>array_slice($p['pillole'],0,10)]);
}
if($az==='letta'){
  $a=currentAccount(); if(!$a) j(['ok'=>false],401);
  $in=json_decode(file_get_contents('php://input'),true)?:[];
  $pid=preg_replace('/[^a-z0-9_]/','',$in['id']??''); if($pid==='') j(['ok'=>false],400);
  $reason='pillola_'.$pid;
  try{ $c=$pdo->prepare('SELECT 1 FROM pv_ledger WHERE account_id=? AND reason=?'); $c->execute([$a['id'],$reason]);
       if(!$c->fetch() && function_exists('pvAdd')){ pvAdd($a['id'],5,$reason); j(['ok'=>true,'pv'=>5]); } }catch(Exception $e){}
  j(['ok'=>true,'pv'=>0]);
}
if($az==='crea'){
  $key=getenv('ADMIN_KEY')?:''; if($key===''||(($_GET['k']??'')!==$key)) j(['ok'=>false,'err'=>'chiave'],403);
  $in=json_decode(file_get_contents('php://input'),true)?:$_POST;
  $url=trim($in['url']??''); $tras=trim($in['trascrizione']??'');
  if($tras==='') j(['ok'=>false,'err'=>'manca la trascrizione'],400);
  $titolo=trim($in['titolo']??'Pillola 81+');
  $taglia=function($s,$n){ return function_exists('mb_substr')?mb_substr($s,0,$n):substr($s,0,$n); };
  $ai=aiChat('Trasforma la trascrizione in una micro lezione per imprenditori italiani su sicurezza, HACCP o privacy. Regole assolute. Usa SOLO informazioni presenti nella trascrizione, non aggiungere norme o numeri esterni. Dai del tu, solo virgole e punti. Formato esatto, una riga di sintesi, poi tre azioni pratiche introdotte da •, massimo 90 parole totali.',$taglia($tras,6000));
  if(!$ai) $ai='Sintesi non disponibile, AI spenta. '.$taglia($tras,200).'...';
  $p=json_decode(@file_get_contents($f),true)?:['pillole'=>[]];
  array_unshift($p['pillole'],['id'=>substr(md5($url.$titolo.time()),0,10),'titolo'=>$titolo,'url'=>$url,'lezione'=>$ai,'quando'=>gmdate('c')]);
  $p['pillole']=array_slice($p['pillole'],0,50);
  @file_put_contents($f,json_encode($p,JSON_UNESCAPED_UNICODE));
  j(['ok'=>true,'lezione'=>$ai]);
}
j(['ok'=>false,'err'=>'azione sconosciuta'],400);
