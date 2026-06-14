<?php
// Riceve dal workflow notturno la sintesi norme aggiornata e la scrive nella base
// di conoscenza degli agenti. Firmato con KB_SECRET, senza chiave non scrive niente.
header('Content-Type: application/json; charset=utf-8');
$sec=getenv('KB_SECRET')?:'';
if($sec===''){ echo json_encode(['ok'=>false,'err'=>'kb spento']); exit; }
$s=$_POST['s']??($_GET['s']??'');
if(!hash_equals($sec,$s)){ http_response_code(403); echo json_encode(['ok'=>false,'err'=>'firma errata']); exit; }
$tema=preg_replace('/[^a-z]/','',strtolower($_POST['tema']??''));
$testo=trim($_POST['testo']??'');
$ammessi=['sicurezza','haccp','privacy','iso','ateco','web3'];
if(!in_array($tema,$ammessi,true)||$testo===''){ echo json_encode(['ok'=>false,'err'=>'dati non validi']); exit; }
$dir=__DIR__.'/../data/kb'; if(!is_dir($dir)) @mkdir($dir,0755,true);
$intest="Aggiornato il ".date('d/m/Y H:i')."\n";
@file_put_contents($dir.'/'.$tema.'.txt',$intest.substr($testo,0,1800));
echo json_encode(['ok'=>true,'tema'=>$tema,'lunghezza'=>strlen($testo)]);
