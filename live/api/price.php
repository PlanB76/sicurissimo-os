<?php
// 81+ PREZZI API, listino pubblico
require_once __DIR__.'/../src/db.php';
$listino=@json_decode(@file_get_contents(__DIR__.'/../data/listino.json'),true)?:[];
if(!$listino){
  $listino=['membership'=>['basic'=>49,'pro'=>99,'elite'=>149],'pack'=>['start'=>990,'business'=>1900],'club'=>['palladium'=>1990,'iridium'=>4990,'rhodium'=>9990]];
}
j(['ok'=>true,'listino'=>$listino]);
