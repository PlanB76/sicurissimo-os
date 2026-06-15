<?php
require_once __DIR__.'/../src/db.php';
require_once __DIR__.'/../src/auth_lib.php';
require_once __DIR__.'/../src/fleet.php';
header('Content-Type: application/json; charset=utf-8');
$a=function_exists('currentAccount')?currentAccount():null;
$isAdmin=$a && (($a['sic']??'')==='SIC-0000001' || ($a['ruolo']??'')==='admin');
if(!$isAdmin){ http_response_code(403); echo json_encode(['ok'=>false,'err'=>'solo admin']); exit; }
$flotta=fleetCarica();
$conta=['HUB1'=>0,'HUB2'=>0,'HUB3'=>0,'COMANDO'=>0];
foreach($flotta as $f){ $h=$f['hub']??''; if(isset($conta[$h])) $conta[$h]++; }
$log=[];
try{ $pdo=db(); foreach($pdo->query("SELECT ts,agente,hub,domanda FROM fleet_log ORDER BY id DESC LIMIT 20") as $r){ $log[]=$r; } }catch(Exception $e){}
echo json_encode(['ok'=>true,'totale'=>count($flotta),'conta'=>$conta,'agenti'=>$flotta,'ultime'=>$log],JSON_UNESCAPED_UNICODE);
