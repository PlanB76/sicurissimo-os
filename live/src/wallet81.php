<?php
// 81+ WALLET PV. Policy del credito, pacchetti documenti, coupon, autoricarica e avvisi.
require_once __DIR__.'/db.php';
function wPolicy(){ return json_decode(@file_get_contents(__DIR__.'/../data/pv_policy.json'),true)?:['minimo_pv'=>100,'soglia_autoricarica'=>250]; }
function wBundleAttivo($pdo,$accId,$categoria){
  // un pacchetto comprato vale 12 mesi dalla data dell'addebito nel libro mastro
  $LP=json_decode(@file_get_contents(__DIR__.'/../data/prezzi_pv.json'),true)?:[];
  foreach(($LP['bundle']??[]) as $bid=>$b){
    if(!in_array($categoria,$b['categorie']??[])) continue;
    try{
      $q=db()->prepare("SELECT created_at FROM pv_ledger WHERE account_id=? AND reason=? ORDER BY id DESC LIMIT 1");
      $q->execute([$accId,'bundle_'.$bid]); $r=$q->fetch();
      if($r && strtotime($r['created_at'])>time()-(int)($b['mesi']??12)*30*86400) return $bid;
    }catch(Exception $e){}
  }
  return null;
}
function wAutoSettings($pdo,$accId){
  $f=__DIR__.'/../data/autoricarica.json';
  $d=json_decode(@file_get_contents($f),true)?:['account'=>[]];
  return $d['account'][(string)$accId]??['attiva'=>false,'soglia'=>wPolicy()['soglia_autoricarica'],'pack'=>'pv50'];
}
function wAutoSalva($accId,$set){
  $f=__DIR__.'/../data/autoricarica.json';
  $d=json_decode(@file_get_contents($f),true)?:['account'=>[]];
  $d['account'][(string)$accId]=$set;
  @file_put_contents($f,json_encode($d,JSON_UNESCAPED_UNICODE));
}
// cron, avvisa per email chi è sotto soglia, una volta al mese per soglia
function wAvvisiSaldo($pdo){
  if(!function_exists('pvBalance')) require_once __DIR__.'/auth_lib.php';
  $pol=wPolicy(); $n=0;
  try{ $rows=$pdo->query("SELECT id,email,nome FROM accounts WHERE email NOT LIKE '%@wallet.81plus%' AND email_verified=1")->fetchAll(); }catch(Exception $e){ return 0; }
  foreach($rows as $r){
    $b=pvBalance($r['id']);
    if($b>=$pol['soglia_autoricarica']) continue;
    $liv=$b<$pol['minimo_pv']?'critico':'basso';
    $trig='t_saldo_'.$liv.'_'.gmdate('Ym');
    try{ $c=$pdo->prepare('SELECT 1 FROM trigger_sent WHERE email=? AND trig=?'); $c->execute([$r['email'],$trig]); if($c->fetch()) continue; }catch(Exception $e){ continue; }
    $corpo="Ciao ".($r['nome']?:'imprenditore').",\n\nil tuo saldo PV è a ".$b.($liv==='critico'?", sotto il minimo consigliato di ".$pol['minimo_pv'].". Senza credito i documenti restano in anteprima e non si stampano.":", sotto la soglia di ".$pol['soglia_autoricarica'].".")."\n\nRicarica in un minuto, https://81plus.net/ricarica_pv.html, gli scaglioni più alti regalano più PV bonus. Oppure attiva l'autoricarica dalla stessa pagina e non ci pensi più.\n\nLa direzione 81+";
    if(function_exists('mailGeneric')) @mailGeneric($r['email'],'Il tuo saldo PV è '.$liv.', '.$b.' PV',$corpo);
    try{ $pdo->prepare('INSERT INTO trigger_sent(email,trig,created_at) VALUES(?,?,?)')->execute([$r['email'],$trig,gmdate('c')]); $n++; }catch(Exception $e){}
  }
  return $n;
}
