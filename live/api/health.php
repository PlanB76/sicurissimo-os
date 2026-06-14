<?php
// Battito del sistema, per il monitoraggio esterno. Nessun dato sensibile.
require_once __DIR__.'/../src/db.php';
$db=false; try{ db()->query('SELECT 1'); $db=true; }catch(Exception $e){}
header('Content-Type: application/json');
http_response_code($db?200:503);
echo json_encode(['ok'=>$db,'db'=>$db,'ora'=>gmdate('c'),'versione'=>'3.3']);
