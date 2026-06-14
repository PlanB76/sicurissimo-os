<?php
// 81+ CRESCITA. Calcola PV per area della ruota della vita e livello Maslow.
// Storico mensile dai movimenti pv_ledger, per vedere progressi e regressi nel tempo.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
$pdo=db();

// mappa reason -> area ruota della vita
function areaDi($reason){
  $r=strtolower($reason);
  if(strpos($r,'corso')!==false||strpos($r,'academy')!==false||strpos($r,'video')!==false) return 'formazione';
  if(strpos($r,'doc')!==false||strpos($r,'scad')!==false||strpos($r,'haccp')!==false||strpos($r,'privacy')!==false||strpos($r,'preventiv')!==false) return 'compliance';
  if(strpos($r,'invit')!==false||strpos($r,'network')!==false||strpos($r,'ref')!==false) return 'network';
  if(strpos($r,'welcome')!==false||strpos($r,'wallet')!==false||strpos($r,'acquist')!==false||strpos($r,'shop')!==false||strpos($r,'yearly')!==false||strpos($r,'early')!==false) return 'finanza';
  if(strpos($r,'webinar')!==false||strpos($r,'club')!==false||strpos($r,'evento')!==false) return 'community';
  if(strpos($r,'nft')!==false||strpos($r,'token')!==false||strpos($r,'digital')!==false||strpos($r,'pixel')!==false) return 'digitale';
  if(strpos($r,'missio')!==false||strpos($r,'rank')!==false||strpos($r,'rango')!==false||strpos($r,'premia')!==false||strpos($r,'premiato')!==false) return 'leadership';
  if(strpos($r,'profilo')!==false||strpos($r,'dvr')!==false||strpos($r,'formazione_gen')!==false) return 'sicurezza';
  return 'sicurezza';
}

$rows=$pdo->prepare('SELECT delta, reason, created_at FROM pv_ledger WHERE account_id=? ORDER BY created_at ASC');
$rows->execute([$a['id']]);
$mov=$rows->fetchAll(PDO::FETCH_ASSOC);

// PV per area (cumulativo positivo per la ruota)
$aree=['sicurezza'=>0,'formazione'=>0,'compliance'=>0,'network'=>0,'finanza'=>0,'digitale'=>0,'community'=>0,'leadership'=>0];
$totale=0;
// storico mensile del saldo totale
$mesi=[];
foreach($mov as $m){
  $d=(int)$m['delta']; $totale+=$d;
  $ar=areaDi($m['reason']);
  if($d>0) $aree[$ar]+=$d; // la ruota cresce con i PV guadagnati per area
  $mese=substr($m['created_at'],0,7); // YYYY-MM
  $mesi[$mese]=($mesi[$mese]??0)+$d;
}
// trasformo storico in saldo cumulativo per mese
$storico=[]; $cum=0;
ksort($mesi);
foreach($mesi as $k=>$v){ $cum+=$v; $storico[]=['mese'=>$k,'saldo'=>$cum,'delta'=>$v]; }

// livello Maslow dal totale
$soglie=[['liv'=>1,'pv'=>0],['liv'=>2,'pv'=>300],['liv'=>3,'pv'=>800],['liv'=>4,'pv'=>2000],['liv'=>5,'pv'=>5000]];
$livello=1; $prossima=300;
foreach($soglie as $i=>$s){
  if($totale>=$s['pv']){ $livello=$s['liv']; $prossima=$soglie[$i+1]['pv']??null; }
}

j(['ok'=>true,'aree'=>$aree,'totale'=>$totale,'maslow_livello'=>$livello,'prossima_soglia'=>$prossima,'storico'=>$storico]);
