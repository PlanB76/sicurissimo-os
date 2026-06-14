<?php
// Chat con un agente della flotta. Ogni sito e ogni dashboard chiama qui.
// Passi agent (id o nome) e messaggio. L agente risponde nel suo ruolo e tema.
// Ogni scambio viene registrato e un riassunto sale al direttivo e all Ammiraglio.
require_once __DIR__.'/../src/db.php';
require_once __DIR__.'/../src/ai81.php';
require_once __DIR__.'/../src/fleet.php';
if(!function_exists('mb_substr')){ function mb_substr($s,$st,$len=null){ return $len===null?substr($s,$st):substr($s,$st,$len); } }
header('Content-Type: application/json; charset=utf-8');

$in=json_decode(file_get_contents('php://input'),true)?:[];
$agId=trim($in['agente']??$_GET['agente']??'Mirco AI');
$msg=trim($in['messaggio']??'');
$sid=preg_replace('/[^a-zA-Z0-9_-]/','',$in['sessione']??'anon');
if($msg===''){ echo json_encode(['ok'=>false,'err'=>'messaggio vuoto']); exit; }

$a=fleetAgente($agId);
if(!$a){ http_response_code(404); echo json_encode(['ok'=>false,'err'=>'agente non trovato']); exit; }

$sistema=fleetPrompt($a);
$risposta=aiChat($sistema,$msg,600,0.4);

// degrado con grazia senza chiave: risposta deterministica utile, mai errore
if(!$risposta){
  $risposta="Sono ".$a['nome'].", mi occupo di ".$a['tema'].". ".
    "Il sistema AI e in fase di attivazione. Intanto ti dico la strada giusta. ".
    "Scrivimi la tua attivita e la tua domanda precisa, oppure passa dalla direzione su WhatsApp per una risposta immediata.";
  $aiOn=false;
} else { $aiOn=true; }

// registro lo scambio
try{
  $pdo=db();
  $pdo->exec("CREATE TABLE IF NOT EXISTS fleet_log(id INTEGER PRIMARY KEY AUTOINCREMENT, ts TEXT, agente TEXT, hub TEXT, sessione TEXT, domanda TEXT, risposta TEXT)");
  $pdo->prepare("INSERT INTO fleet_log(ts,agente,hub,sessione,domanda,risposta) VALUES(?,?,?,?,?,?)")
      ->execute([date('c'),$a['nome'],$a['hub'],$sid,mb_substr($msg,0,1000),mb_substr($risposta,0,2000)]);
}catch(Exception $e){ try{
  $pdo=db();
  $pdo->exec("CREATE TABLE IF NOT EXISTS fleet_log(id INT AUTO_INCREMENT PRIMARY KEY, ts VARCHAR(40), agente VARCHAR(80), hub VARCHAR(20), sessione VARCHAR(64), domanda TEXT, risposta TEXT) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
  $pdo->prepare("INSERT INTO fleet_log(ts,agente,hub,sessione,domanda,risposta) VALUES(?,?,?,?,?,?)")
      ->execute([date('c'),$a['nome'],$a['hub'],$sid,mb_substr($msg,0,1000),mb_substr($risposta,0,2000)]);
}catch(Exception $e2){} }

// inoltro al direttivo e all Ammiraglio via n8n, se il ponte e configurato
$n8n=getenv('N8N_FLOTTA_URL')?:'';
if($n8n!==''){
  $payload=json_encode(['evento'=>'chat_agente','agente'=>$a['nome'],'hub'=>$a['hub'],'grado'=>$a['grado'],'domanda'=>mb_substr($msg,0,500),'risposta'=>mb_substr($risposta,0,500)]);
  $ch=curl_init($n8n);
  curl_setopt_array($ch,[CURLOPT_POST=>1,CURLOPT_POSTFIELDS=>$payload,CURLOPT_HTTPHEADER=>['Content-Type: application/json'],CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>4]);
  @curl_exec($ch); @curl_close($ch);
}

echo json_encode(['ok'=>true,'agente'=>$a['nome'],'hub'=>$a['hub'],'grado'=>$a['grado'],'tema'=>$a['tema'],'risposta'=>$risposta,'ai'=>$aiOn],JSON_UNESCAPED_UNICODE);
