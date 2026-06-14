<?php
// 81+ TOKEN 81X ADMIN. Stato economico per la direzione. Genera anche l hash di trasparenza.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount();
$isAdmin=$a && (($a['sic']??'')==='SIC-0000001' || ($a['ruolo']??'')==='admin');
if(!$isAdmin) j(['ok'=>false,'err'=>'solo la direzione'],403);
$pdo=db(); $az=$_GET['az']??'stato';
define('X81_BASE',5000000);
define('X81_MAX',21000000);

if($az==='stato'){
  $emesso=(float)($pdo->query('SELECT COALESCE(SUM(delta),0) b FROM x81_ledger WHERE delta>0')->fetch()['b']??0);
  $bruciato=(float)($pdo->query("SELECT COALESCE(SUM(ABS(delta)),0) b FROM x81_ledger WHERE delta<0 AND reason LIKE '%burn%'")->fetch()['b']??0);
  $circolante=$emesso-$bruciato;
  $holders=(int)($pdo->query('SELECT COUNT(DISTINCT account_id) n FROM x81_ledger')->fetch()['n']??0);
  $oggi=gmdate('Y-m-d');
  $attiviOggi=(int)($pdo->query("SELECT COUNT(DISTINCT account_id) n FROM x81_mining WHERE giorno='".$oggi."'")->fetch()['n']??0);
  $minatoOggi=(float)($pdo->query("SELECT COALESCE(SUM(reward),0) b FROM x81_mining WHERE giorno='".$oggi."'")->fetch()['b']??0);
  $whitelist=(int)($pdo->query('SELECT COUNT(*) n FROM x81_whitelist')->fetch()['n']??0);
  j(['ok'=>true,'emesso'=>$emesso,'base'=>X81_BASE,'max'=>X81_MAX,'circolante'=>$circolante,'bruciato'=>$bruciato,
     'holders'=>$holders,'attivi_oggi'=>$attiviOggi,'minato_oggi'=>$minatoOggi,'whitelist'=>$whitelist,
     'base_residua'=>max(0,X81_BASE-$emesso)]);
}

// genera l hash di trasparenza del giorno (proof of emission)
if($az==='snapshot'){
  $oggi=gmdate('Y-m-d');
  $emesso=(float)($pdo->query('SELECT COALESCE(SUM(delta),0) b FROM x81_ledger WHERE delta>0')->fetch()['b']??0);
  $circolante=$emesso; // semplificato
  $attivi=(int)($pdo->query("SELECT COUNT(DISTINCT account_id) n FROM x81_mining WHERE giorno='".$oggi."'")->fetch()['n']??0);
  $payload=json_encode(['giorno'=>$oggi,'emesso'=>$emesso,'circolante'=>$circolante,'attivi'=>$attivi]);
  $hash=hash('sha256',$payload);
  // upsert giorno
  $ex=$pdo->prepare('SELECT id FROM x81_trasparenza WHERE giorno=?'); $ex->execute([$oggi]);
  if($ex->fetch()){
    $pdo->prepare('UPDATE x81_trasparenza SET totale_emesso=?,circolante=?,utenti_attivi=?,hash_ledger=? WHERE giorno=?')->execute([$emesso,$circolante,$attivi,$hash,$oggi]);
  } else {
    $pdo->prepare('INSERT INTO x81_trasparenza(giorno,totale_emesso,circolante,utenti_attivi,hash_ledger,created_at) VALUES(?,?,?,?,?,?)')->execute([$oggi,$emesso,$circolante,$attivi,$hash,gmdate('c')]);
  }
  ev('x81_snapshot',$a['id'],['giorno'=>$oggi,'hash'=>$hash]);
  j(['ok'=>true,'giorno'=>$oggi,'hash'=>$hash,'emesso'=>$emesso,'nota'=>'Hash di trasparenza generato. Può essere ancorato on-chain per prova pubblica.']);
}

j(['ok'=>false,'err'=>'azione sconosciuta'],404);
