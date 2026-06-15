<?php
require_once __DIR__.'/../src/db.php';
header('Content-Type: application/json; charset=utf-8');
$action=$_GET['action']??'';
if($action==='lista'){
  $j=json_decode(@file_get_contents(__DIR__.'/../data/ateco_settori.json'),true);
  if(!$j){ echo json_encode(['ok'=>false]); exit; }
  $out=['ok'=>true,'default'=>$j['default'],'settori'=>array_map(function($s){return ['id'=>$s['id'],'label'=>$s['label'],'rischio'=>$s['rischio'],'haccp'=>$s['haccp'],'match'=>$s['match']];},$j['settori'])];
  echo json_encode($out,JSON_UNESCAPED_UNICODE); exit;
}
echo json_encode(['ok'=>false,'err'=>'azione sconosciuta']);
