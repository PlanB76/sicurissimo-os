<?php
// 81+ REFERRAL. Link e QR legati al SIC-ID, conteggio inviti, attivi e PV guadagnati.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
$pdo=db();
$base='https://81plus.net/signup.html?ref='.$a['sic'];
$q=$pdo->prepare('SELECT COUNT(*) n FROM accounts WHERE ref_by=?'); $q->execute([$a['sic']]);
$invitati=(int)($q->fetch()['n']??0);
// attivi: invitati con almeno una membership o profilo completo
$attivi=0;
try{
  $qa=$pdo->prepare("SELECT COUNT(*) n FROM accounts WHERE ref_by=? AND (membership IS NOT NULL AND membership<>'')");
  $qa->execute([$a['sic']]); $attivi=(int)($qa->fetch()['n']??0);
}catch(Throwable $e){}
// PV guadagnati da inviti
$pv=0;
try{
  $qp=$pdo->prepare("SELECT COALESCE(SUM(delta),0) s FROM pv_ledger WHERE account_id=? AND (reason LIKE '%invit%' OR reason LIKE '%referr%')");
  $qp->execute([$a['id']]); $pv=(int)($qp->fetch()['s']??0);
}catch(Throwable $e){}
j(['ok'=>true,'sic'=>$a['sic'],'link'=>$base,'invitati'=>$invitati,'attivi'=>$attivi,'pv'=>$pv]);
