<?php
// 81+ FOUNDER. Determina lo status fondatore dal numero progressivo del SIC-ID.
require_once __DIR__.'/../src/db.php';
$az=$_GET['az']??'stato';

// estrae il numero dal SIC tipo SIC-0000042 -> 42
function sicNum($sic){ if(preg_match('/(\d+)/',$sic,$m)) return (int)$m[1]; return 0; }
function tierDa($n){
  if($n>=1 && $n<=100) return 'genesi';
  if($n<=500) return 'pionieri';
  if($n<=1000) return 'early';
  return null;
}

// quanti posti restano in ogni tier (pubblico)
if($az==='posti'){
  $pdo=db();
  try{
    $tot=(int)($pdo->query('SELECT COUNT(*) n FROM accounts')->fetch()['n']??0);
  }catch(Throwable $e){ $tot=0; }
  j(['ok'=>true,'registrati'=>$tot,
     'genesi_liberi'=>max(0,100-min(100,$tot)),
     'pionieri_liberi'=>max(0,500-min(500,$tot)),
     'early_liberi'=>max(0,1000-min(1000,$tot))]);
}

// stato del founder loggato
require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
$n=sicNum($a['sic']);
$tier=tierDa($n);
$molt=['genesi'=>2.0,'pionieri'=>1.5,'early'=>1.25];
j(['ok'=>true,'sic'=>$a['sic'],'numero'=>$n,'tier'=>$tier,'is_founder'=>$tier!==null,'moltiplicatore'=>$tier?($molt[$tier]??1):1]);
