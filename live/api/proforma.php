<?php
// 81+ PROFORMA API, genera bozza preventivo
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
$in=json_decode(file_get_contents('php://input'),true)?:[];
$voci=$in['voci']??[]; $tot=0;
foreach($voci as $v){ $tot+=(float)($v['prezzo']??0)*(int)($v['qta']??1); }
$iva=round($tot*0.22,2);
j(['ok'=>true,'imponibile'=>$tot,'iva'=>$iva,'totale'=>$tot+$iva,'nota'=>'Proforma indicativo, non fiscale']);
