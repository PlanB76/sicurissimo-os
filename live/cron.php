<?php
// 81+ CRON. Da chiamare ogni ora o ogni giorno da Hostinger (cron job) o via URL con chiave.
// Esegue: solleciti di recupero pagamenti, avvisi saldo PV.
require_once __DIR__.'/src/db.php';
// protezione: chiave segreta in env, oppure esecuzione da CLI
$key=getenv('CRON_KEY')?:'';
$fromCli=(php_sapi_name()==='cli');
if(!$fromCli && (!$key || ($_GET['key']??'')!==$key)){ http_response_code(403); echo 'forbidden'; exit; }

$out=[];

// 1. RECUPERO PAGAMENTI FALLITI
try{
  require_once __DIR__.'/src/recupero81.php';
  $n=recuperoCiclo();
  $out['recupero_solleciti']=$n;
}catch(Throwable $e){ $out['recupero_errore']=$e->getMessage(); }

// 2. AVVISI SALDO PV (se la funzione esiste)
try{
  require_once __DIR__.'/src/wallet81.php';
  if(function_exists('wAvvisiSaldo')){ wAvvisiSaldo(db()); $out['avvisi_saldo']='ok'; }
}catch(Throwable $e){ $out['saldo_errore']=$e->getMessage(); }

// 3. SNAPSHOT TRASPARENZA 81X (hash giornaliero del ledger)
try{
  $pdo=db();
  $oggi=gmdate('Y-m-d');
  $ex=$pdo->query("SELECT 1 FROM x81_trasparenza WHERE giorno='".$oggi."'")->fetch();
  if(!$ex){
    $emesso=(float)($pdo->query('SELECT COALESCE(SUM(delta),0) b FROM x81_ledger WHERE delta>0')->fetch()['b']??0);
    $attivi=(int)($pdo->query("SELECT COUNT(DISTINCT account_id) n FROM x81_mining WHERE giorno='".$oggi."'")->fetch()['n']??0);
    $hash=hash('sha256',json_encode(['giorno'=>$oggi,'emesso'=>$emesso,'attivi'=>$attivi]));
    $pdo->prepare('INSERT INTO x81_trasparenza(giorno,totale_emesso,circolante,utenti_attivi,hash_ledger,created_at) VALUES(?,?,?,?,?,?)')
        ->execute([$oggi,$emesso,$emesso,$attivi,$hash,gmdate('c')]);
    $out['snapshot_81x']=$hash;
  } else $out['snapshot_81x']='già fatto oggi';
}catch(Throwable $e){ $out['snapshot_errore']=$e->getMessage(); }

if(function_exists('ev')) @ev('cron_run',null,$out);
header('Content-Type: application/json');
echo json_encode(['ok'=>true,'eseguito'=>gmdate('c'),'risultati'=>$out]);
