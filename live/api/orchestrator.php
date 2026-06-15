<?php
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
// riuso helper profilo e settore definiti in auth.php senza eseguirne il routing
function campiProfilo($a){ $tipo=$a['tipo']??'persona';
  if($tipo==='azienda'){ $req=['ragione_sociale','piva','tel','pec','ateco']; }
  else { $req=['nome','cognome','codice_fiscale','tel','data_nascita','indirizzo']; }
  $fatti=0; foreach($req as $k){ if(trim((string)($a[$k]??''))!=='') $fatti++; } return [$req,$fatti]; }
function profiloCompleto($a){ list($req,$fatti)=campiProfilo($a); return $fatti===count($req); }
function settoreDaAteco($ateco){
  $j=json_decode(@file_get_contents(__DIR__.'/../data/ateco_settori.json'),true); if(!$j) return null;
  $a=trim((string)$ateco); $best=null;
  if($a!==''){ foreach($j['settori'] as $st){ foreach($st['match'] as $m){ if(strpos($a,$m)===0){ if($best===null||strlen($m)>$best['_len']){ $best=$st; $best['_len']=strlen($m); } } } } }
  $out=$best?:$j['default']; unset($out['_len']); unset($out['match']); return $out; }
require_once __DIR__.'/../src/orchestrator.php';
$pdo=db(); $action=$_GET['action']??'';

if($action==='decidi'){
  $a=currentAccount(); if(!$a) j(['ok'=>false],401);
  $r=orchestratorDecidi($pdo,$a);
  j(['ok'=>true]+$r);
}
if($action==='feed'){
  $a=currentAccount(); if(!$a) j(['ok'=>false],401);
  $st=$pdo->prepare('SELECT evento,problema,azione,reward_pv,link,score,created_at FROM ai_decisions WHERE account_id=? ORDER BY id DESC LIMIT 8'); $st->execute([$a['id']]);
  $mi=$pdo->prepare("SELECT tipo,titolo,reward_pv,stato,scade_il FROM missions WHERE account_id=? AND stato='attiva' ORDER BY id DESC LIMIT 5"); $mi->execute([$a['id']]);
  j(['ok'=>true,'feed'=>$st->fetchAll(),'missioni'=>$mi->fetchAll()]);
}
j(['ok'=>false,'err'=>'azione sconosciuta'],404);
