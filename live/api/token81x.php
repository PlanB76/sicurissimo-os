<?php
// 81+ TOKEN 81X. Ledger interno trasparente, mining attività, whitelist e airdrop.
// I saldi 81X sono crediti interni dell ecosistema. La fase on-chain è dichiarata e futura.
// Niente promesse di rendimento, 81X è utilità e accesso.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$pdo=db();
$TK=json_decode(@file_get_contents(__DIR__.'/../data/tokenomics_81x.json'),true)?:[];
$az=$_GET['az']??'stato';
$in=json_decode(file_get_contents('php://input'),true)?:[];

define('X81_BASE',5000000);
define('X81_GIORNI',1460);                 // 4 anni
define('X81_CAP_GIORNO_WALLET',50);        // cap reward giornaliero per wallet
define('X81_DAILY_GLOBAL', X81_BASE/X81_GIORNI); // ~3424/giorno

function x81Saldo($pdo,$id){ $q=$pdo->prepare('SELECT COALESCE(SUM(delta),0) b FROM x81_ledger WHERE account_id=?'); $q->execute([$id]); return (float)($q->fetch()['b']??0); }
function x81Emesso($pdo){ $q=$pdo->query('SELECT COALESCE(SUM(delta),0) b FROM x81_ledger WHERE delta>0'); return (float)($q->fetch()['b']??0); }

// PUBBLICO, tokenomics e stato emissione
if($az==='tokenomics'){
  $emesso=x81Emesso($pdo);
  j(['ok'=>true,'tokenomics'=>$TK,'emesso'=>$emesso,'base_residua'=>max(0,X81_BASE-$emesso),'base'=>X81_BASE]);
}

// PUBBLICO, log trasparenza
if($az==='trasparenza'){
  $rows=$pdo->query('SELECT giorno,totale_emesso,circolante,utenti_attivi,hash_ledger,anchor_tx FROM x81_trasparenza ORDER BY giorno DESC LIMIT 30')->fetchAll(PDO::FETCH_ASSOC);
  j(['ok'=>true,'log'=>$rows,'nota'=>'Hash giornaliero del ledger 81X, per verifica pubblica. Proof of emission.']);
}

$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'accedi'],401);
$id=$a['id']; $sic=$a['sic'];

// stato personale 81X
if($az==='stato'){
  $oggi=gmdate('Y-m-d');
  $m=$pdo->prepare('SELECT reward FROM x81_mining WHERE account_id=? AND giorno=?'); $m->execute([$id,$oggi]); $mr=$m->fetch();
  $rewardOggi=(float)($mr['reward']??0);
  // possiede PIX? -> bridge attivo, whitelist e airdrop
  $pq=$pdo->prepare('SELECT COUNT(*) n FROM pixel_muro WHERE account_id=?'); $pq->execute([$id]); $pix=(int)($pq->fetch()['n']??0);
  $wl=$pdo->prepare('SELECT tipo,stato FROM x81_whitelist WHERE account_id=?'); $wl->execute([$id]); $wlrow=$wl->fetch();
  j(['ok'=>true,'saldo'=>x81Saldo($pdo,$id),'reward_oggi'=>$rewardOggi,'cap_giorno'=>X81_CAP_GIORNO_WALLET,
     'pix_posseduti'=>$pix,'bridge_attivo'=>$pix>0,
     'whitelist'=>$wlrow?['tipo'=>$wlrow['tipo'],'stato'=>$wlrow['stato']]:null]);
}

// MINING, proof of engagement. Converte attività reali in 81X, con cap e anti farming.
if($az==='mina'){
  $oggi=gmdate('Y-m-d');
  // attività del giorno = eventi reali registrati (audit, doc, corso, invito, login)
  $score=(int)($pdo->query("SELECT COUNT(*) n FROM events WHERE account_id=".(int)$id." AND substr(created_at,1,10)='".$oggi."'")->fetch()['n']??0);
  // risk score SIC-ID base (anti bot), qui semplice, in futuro AI
  $risk=0;
  // se ha gia minato oggi, non doppia
  $ex=$pdo->prepare('SELECT id,reward FROM x81_mining WHERE account_id=? AND giorno=?'); $ex->execute([$id,$oggi]); $exr=$ex->fetch();
  // reward = score * fattore, ridotto dal rischio, limitato dal cap
  $base=min($score*1.0, X81_CAP_GIORNO_WALLET);
  $reward=round($base*(1-$risk/200),4);
  if($exr){
    // aggiorno solo se il nuovo reward è maggiore (più attività nel giorno)
    $delta=$reward-(float)$exr['reward'];
    if($delta>0){
      $pdo->prepare('UPDATE x81_mining SET attivita_score=?,reward=?,risk_score=? WHERE id=?')->execute([$score,$reward,$risk,$exr['id']]);
      $pdo->prepare('INSERT INTO x81_ledger(account_id,sic,delta,reason,ref,created_at) VALUES(?,?,?,?,?,?)')->execute([$id,$sic,$delta,'mining_'.$oggi,'incremento',gmdate('c')]);
    }
  } else {
    $pdo->prepare('INSERT INTO x81_mining(account_id,giorno,attivita_score,reward,risk_score,created_at) VALUES(?,?,?,?,?,?)')->execute([$id,$oggi,$score,$reward,$risk,gmdate('c')]);
    if($reward>0) $pdo->prepare('INSERT INTO x81_ledger(account_id,sic,delta,reason,ref,created_at) VALUES(?,?,?,?,?,?)')->execute([$id,$sic,$reward,'mining_'.$oggi,'giornaliero',gmdate('c')]);
  }
  ev('x81_mining',$id,['score'=>$score,'reward'=>$reward]);
  j(['ok'=>true,'score'=>$score,'reward_oggi'=>$reward,'cap'=>X81_CAP_GIORNO_WALLET,'saldo'=>x81Saldo($pdo,$id),
     'nota'=>'I tuoi 81X derivano dalla tua attività reale nell ecosistema. Sono crediti interni, la versione on-chain arriverà dopo le autorizzazioni.']);
}

// BRIDGE, attiva la whitelist se possiedi un PIX81+
if($az==='bridge'){
  $pix=(int)($pdo->query('SELECT COUNT(*) n FROM pixel_muro WHERE account_id='.(int)$id)->fetch()['n']??0);
  if($pix<1) j(['ok'=>false,'err'=>'serve almeno un PIX81+ per attivare il ponte verso il Web3'],403);
  $ex=$pdo->prepare('SELECT id FROM x81_whitelist WHERE account_id=?'); $ex->execute([$id]);
  if(!$ex->fetch()){
    $pdo->prepare('INSERT INTO x81_whitelist(account_id,sic,tipo,motivo,kyc,stato,created_at) VALUES(?,?,?,?,?,?,?)')
        ->execute([$id,$sic,'whitelist','possiede PIX81+',0,'in_attesa',gmdate('c')]);
    ev('x81_whitelist',$id,['motivo'=>'pix']);
  }
  j(['ok'=>true,'whitelist'=>true,'airdrop'=>true,'nota'=>'Sei in whitelist per la prevendita 81X e hai diritto all airdrop del primo rilascio. La fase on-chain parte dopo le autorizzazioni di legge, ti avviseremo.']);
}

j(['ok'=>false,'err'=>'azione sconosciuta'],404);
