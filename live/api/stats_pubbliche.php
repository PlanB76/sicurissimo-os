<?php
// Prova sociale onesta. Numeri veri dal database, con cache di dieci minuti.
require_once __DIR__.'/../src/db.php';
$cacheF=__DIR__.'/../data/stats_cache.json';
if(is_file($cacheF) && (time()-filemtime($cacheF))<600){ header('Content-Type: application/json'); echo file_get_contents($cacheF); exit; }
$pdo=db();
$utenti=(int)$pdo->query('SELECT COUNT(*) c FROM accounts')->fetch()['c'];
$setti=gmdate('c',time()-7*86400);
$audit=0;
try{ $st=$pdo->prepare("SELECT COUNT(*) c FROM events WHERE type IN ('audit_run','audit','audit_fatto') AND created_at>=?"); $st->execute([$setti]); $audit=(int)$st->fetch()['c']; }catch(Exception $e){}
$out=json_encode(['ok'=>true,'utenti'=>$utenti,'audit_settimana'=>$audit,'aggiornato'=>gmdate('c')]);
@file_put_contents($cacheF,$out);
header('Content-Type: application/json'); echo $out;
