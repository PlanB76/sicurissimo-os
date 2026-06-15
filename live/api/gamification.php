<?php
// 81+ GAMIFICATION UNIFICATA. Stato completo del giocatore a tutti i livelli.
// XP, livello, missioni, badge, booster attivi, benefit, premi soglia. Tutto in PV.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'accedi'],401);
$pdo=db();
$M=json_decode(@file_get_contents(__DIR__.'/../data/gamification_81plus.json'),true)?:[];

function gHas($pdo,$sql,$par){ try{ $q=$pdo->prepare($sql); $q->execute($par); return (bool)$q->fetch(); }catch(Throwable $e){ return false; } }
function gCount($pdo,$sql,$par){ try{ $q=$pdo->prepare($sql); $q->execute($par); return (int)($q->fetch()['n']??0); }catch(Throwable $e){ return 0; } }
function sicNum($sic){ return preg_match('/(\d+)/',$sic,$m)?(int)$m[1]:0; }

$id=$a['id']; $sic=$a['sic'];
$pv=pvBalance($id);

// XP = somma PV positivi guadagnati (esperienza), separato dal saldo spendibile
$xp=(int)(gCount($pdo,"SELECT COALESCE(SUM(delta),0) n FROM pv_ledger WHERE account_id=? AND delta>0",[$id]));

// livello da XP
$liv=$M['livelli_pv'][0]; $next=null;
foreach($M['livelli_pv'] as $i=>$L){ if($xp>=$L['da']){ $liv=$L; $next=$M['livelli_pv'][$i+1]??null; } }

// stato missioni
$nInvitati=gCount($pdo,"SELECT COUNT(*) n FROM accounts WHERE ref_by=?",[$sic]);
$nPix=gCount($pdo,"SELECT COUNT(*) n FROM pixel_muro WHERE account_id=?",[$id]);
$nCorsi=gCount($pdo,"SELECT COUNT(*) n FROM events WHERE account_id=? AND type='partner_click'",[$id]);
$missioni=[];
foreach(($M['missioni']??[]) as $mi){
  $fatta=false;
  switch($mi['id']){
    case 'registrazione': $fatta=true; break;
    case 'profilo': $fatta=!empty($a['nome'])&&!empty($a['tel']); break;
    case 'audit': $fatta=gHas($pdo,"SELECT 1 FROM events WHERE account_id=? AND type LIKE 'audit%' LIMIT 1",[$id]); break;
    case 'primo_documento': $fatta=gHas($pdo,"SELECT 1 FROM pv_ledger WHERE account_id=? AND reason='doc_generato' LIMIT 1",[$id]); break;
    case 'primo_corso': $fatta=$nCorsi>0; break;
    case 'prima_membership': $fatta=gHas($pdo,"SELECT 1 FROM abbonamenti WHERE account_id=? AND prodotto='membership' LIMIT 1",[$id]); break;
    case 'primo_sigillo': $fatta=gHas($pdo,"SELECT 1 FROM pv_ledger WHERE account_id=? AND reason LIKE '%sigillo%' LIMIT 1",[$id]); break;
    case 'primo_invito': $fatta=$nInvitati>0; break;
    case 'dieci_invitati': $fatta=$nInvitati>=10; break;
    case 'primo_pix': $fatta=$nPix>0; break;
    case 'telegram': $fatta=!empty($a['telegram_id']); break;
    case 'quiz': $fatta=gHas($pdo,"SELECT 1 FROM pv_ledger WHERE account_id=? AND reason LIKE 'g_quiz_%' LIMIT 1",[$id]); break;
    case 'wallet_carico': $fatta=gHas($pdo,"SELECT 1 FROM pv_ledger WHERE account_id=? AND (reason LIKE 'swap_%' OR reason LIKE 'ricarica_%') LIMIT 1",[$id]); break;
    case 'web3': $fatta=gHas($pdo,"SELECT 1 FROM badges WHERE account_id=? AND badge_id='pioniere_web3' LIMIT 1",[$id]); break;
  }
  $missioni[]=['id'=>$mi['id'],'nome'=>$mi['nome'],'desc'=>$mi['desc'],'pv'=>$mi['pv'],'cat'=>$mi['cat'],'fatta'=>$fatta];
}
$fatte=count(array_filter($missioni,fn($m)=>$m['fatta']));

// badge posseduti
$badgePosseduti=[];
try{ foreach($pdo->query("SELECT badge_id FROM badges WHERE account_id=".(int)$id) as $r) $badgePosseduti[]=$r['badge_id']; }catch(Throwable $e){}
// badge automatici da stato
if($nPix>0 && !in_array('founder_node',$badgePosseduti)) $badgePosseduti[]='founder_node';
$badge=[];
foreach(($M['badge']??[]) as $b){ $badge[]=['id'=>$b['id'],'nome'=>$b['nome'],'desc'=>$b['desc'],'icona'=>$b['icona'],'ottenuto'=>in_array($b['id'],$badgePosseduti)]; }

// booster attivi
$n=sicNum($sic);
$membership='';
try{ $mq=$pdo->prepare("SELECT piano FROM abbonamenti WHERE account_id=? AND prodotto='membership' AND stato='attivo' ORDER BY id DESC LIMIT 1"); $mq->execute([$id]); $mr=$mq->fetch(); if($mr){ $membership=strtolower(str_replace('membership_','',$mr['piano'])); } }catch(Throwable $e){}
$streak=gCount($pdo,"SELECT COUNT(DISTINCT substr(created_at,1,10)) n FROM pv_ledger WHERE account_id=? AND created_at>=?",[$id,gmdate('c',time()-7*86400)]);
$booster=[]; $moltTot=1.0;
foreach(($M['booster']??[]) as $bo){
  $attivo=false; $t=$bo['trigger'];
  if($t==='sic<=100') $attivo=$n>=1&&$n<=100;
  elseif($t==='sic<=500') $attivo=$n>=1&&$n<=500;
  elseif($t==='sic<=1000') $attivo=$n>=1&&$n<=1000;
  elseif($t==='streak>=7') $attivo=$streak>=7;
  elseif($t==='membership=elite') $attivo=$membership==='elite';
  // i booster founder non si sommano tra loro, vince il piu alto
  if($attivo){ $booster[]=['nome'=>$bo['nome'],'desc'=>$bo['desc'],'molt'=>$bo['molt']]; }
}
// moltiplicatore effettivo, il founder piu alto + eventuali extra non founder
$founderMolt=1.0; $extraMolt=1.0;
foreach($booster as $b){
  if(strpos($b['desc'],'SIC')!==false||strpos($b['nome'],'Genesi')!==false||strpos($b['nome'],'Pionieri')!==false||strpos($b['nome'],'Early')!==false){ $founderMolt=max($founderMolt,$b['molt']); }
  else { $extraMolt=max($extraMolt,$b['molt']); }
}
$moltTot=round($founderMolt*$extraMolt,2);

// benefit per livello
$benefit=[]; foreach(($M['benefit']??[]) as $bf){ $benefit[]=['nome'=>$bf['nome'],'desc'=>$bf['desc'],'attivo'=>$liv['liv']>=$bf['liv_min']]; }

// premi soglia
$premi=[]; foreach(($M['premi_soglia']??[]) as $pr){ $premi[]=['nome'=>$pr['nome'],'desc'=>$pr['desc'],'pv'=>$pr['pv'],'raggiunto'=>$xp>=$pr['pv']]; }

j(['ok'=>true,
  'pv'=>$xp,'saldo_pv'=>$pv,
  'livello'=>$liv,'prossimo'=>$next,
  'missioni'=>$missioni,'missioni_fatte'=>$fatte,'missioni_totali'=>count($missioni),
  'badge'=>$badge,
  'booster'=>$booster,'moltiplicatore'=>$moltTot,
  'benefit'=>$benefit,
  'premi'=>$premi,
  'rete'=>['invitati'=>$nInvitati,'pix'=>$nPix]
]);
