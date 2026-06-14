<?php
// Gamification MEMBER81+. Livelli VIP su PV, missioni del giorno, premi azione con dedup.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
$pdo=db(); $in=body(); $action=$_GET['action']??'stato';

function vipLevels(){ return [
 ['lv'=>1,'pv'=>2000,'nome'=>'VIP 1','sblocca'=>'Dashboard completa e Academy'],
 ['lv'=>2,'pv'=>5000,'nome'=>'VIP 2','sblocca'=>'Bonus Academy e sconti dedicati'],
 ['lv'=>3,'pv'=>10000,'nome'=>'VIP 3','sblocca'=>'Anteprima Club 81+'],
 ['lv'=>4,'pv'=>25000,'nome'=>'VIP 4','sblocca'=>'Eventi e benefit riservati'],
 ['lv'=>5,'pv'=>50000,'nome'=>'VIP 5','sblocca'=>'Anteprima Elite ed esperienze premium']]; }
function vipDi($pv){ $cur=0;$next=null;
 foreach(vipLevels() as $l){ if($pv>=$l['pv'])$cur=$l['lv']; elseif($next===null)$next=$l; }
 return [$cur,$next]; }

// azioni premiabili con dedup: chiave => [pv, dedup per giorno?]
function azioni(){ return [
 'login_giorno'=>[1,true],
 'audit_fatto'=>[10,true],
 'corso_aperto'=>[1,true],
 'invito_inviato'=>[1,true],
 'mondo_esplorato'=>[1,true],
 'profilo_visitato'=>[1,true]
]; }


function streakGiorni($pdo,$id){
  $st=$pdo->prepare("SELECT reason FROM pv_ledger WHERE account_id=? AND reason LIKE 'g_login_giorno_%'"); $st->execute([$id]);
  $giorni=[]; foreach($st->fetchAll() as $r){ $giorni[substr($r['reason'],-8)]=1; }
  $n=0; $t=time();
  while(isset($giorni[gmdate('Ymd',$t)])){ $n++; $t-=86400; }
  return $n;
}
function premiaUnaVolta($pdo,$id,$pv,$reason){
  $c=$pdo->prepare('SELECT 1 FROM pv_ledger WHERE account_id=? AND reason=? LIMIT 1'); $c->execute([$id,$reason]);
  if($c->fetch()) return false; pvAdd($id,$pv,$reason); return true;
}
function badgeDai($pdo,$id,$bid,$pv){
  $c=$pdo->prepare('SELECT 1 FROM badges WHERE account_id=? AND badge_id=?'); $c->execute([$id,$bid]);
  if($c->fetch()) return false;
  $pdo->prepare('INSERT INTO badges(account_id,badge_id,created_at) VALUES(?,?,?)')->execute([$id,$bid,gmdate('c')]);
  if($pv>0) premiaUnaVolta($pdo,$id,$pv,'badge_'.$bid);
  ev('badge',$id,['badge'=>$bid,'pv'=>$pv]); return true;
}
if($action==='progress'){
  $id=$a['id']; $novità=[];
  // STREAK: premi a 3, 7 e 30 giorni consecutivi
  $streak=streakGiorni($pdo,$id);
  if($streak>=3 && premiaUnaVolta($pdo,$id,5,'streak3_'.gmdate('Ymd',time()-($streak-3)*86400))) $novità[]='Fuoco della Compliance, 3 giorni, +5 PV';
  if($streak>=7 && premiaUnaVolta($pdo,$id,15,'streak7_'.gmdate('Ymd',time()-($streak-7)*86400))) $novità[]='Settimana Sicura, +15 PV';
  if($streak>=30 && premiaUnaVolta($pdo,$id,100,'streak30_'.gmdate('Ymd',time()-($streak-30)*86400))) $novità[]='Mese della Sicurezza, +100 PV e badge Azienda Virtuosa';
  if($streak>=30) badgeDai($pdo,$id,'azienda_virtuosa',0);
  // BADGE evangelista: 5 inviti attivi
  $iv=$pdo->prepare("SELECT COUNT(*) c FROM pv_ledger WHERE account_id=? AND reason LIKE 'invito_attivo_%'"); $iv->execute([$id]); $att=(int)$iv->fetch()['c'];
  if($att>=5 && badgeDai($pdo,$id,'evangelista',500)) $novità[]='Badge Evangelista 81+, +500 PV';
  // BADGE zero sanzioni: DVR + Manuale HACCP + le quattro nomine sbloccati
  $du=$pdo->prepare('SELECT doc_id FROM doc_unlocks WHERE account_id=?'); $du->execute([$id]);
  $docs=array_map(fn($r)=>$r['doc_id'],$du->fetchAll());
  $nomine=count(array_intersect(['SIC-01','SIC-02','SIC-03','SIC-04'],$docs))>=4;
  if(in_array('SIC-10',$docs)&&in_array('HACCP-01',$docs)&&$nomine && badgeDai($pdo,$id,'zero_sanzioni',200)) $novità[]='Badge Zero Sanzioni, +200 PV';
  // BONUS VIP EXTRA al passaggio di livello, con possibile cascata voluta
  $soglie=[1=>[2000,250],2=>[5000,500],3=>[10000,1200],4=>[25000,3500],5=>[50000,8000]];
  foreach($soglie as $lv=>$cf){ if(pvBalance($id)>=$cf[0] && premiaUnaVolta($pdo,$id,$cf[1],'vip_bonus_'.$lv)) $novità[]='Bonus VIP '.$lv.', +'.$cf[1].' PV EXTRA'; }
  // VOUCHER VIP2: sconto 20 per cento Academy, diritto registrato una volta
  if(pvBalance($id)>=5000){
    $ek='voucher20_'.$id; $e=$pdo->prepare('SELECT 1 FROM entitlements WHERE ent_key=?'); $e->execute([$ek]);
    if(!$e->fetch()){ $pdo->prepare('INSERT INTO entitlements(ent_key,account_id,product,status,provider,valid_until) VALUES(?,?,?,?,?,?)')
      ->execute([$ek,$id,'voucher_academy_20','active','vip2',gmdate('c',time()+365*86400)]); $novità[]='Voucher Academy 20 per cento attivato'; }
  }
  // badge in possesso
  $b=$pdo->prepare('SELECT badge_id,created_at FROM badges WHERE account_id=?'); $b->execute([$id]);
  // traguardo community: PV positivi totali di tutto l’ecosistema
  $tot=(int)$pdo->query('SELECT COALESCE(SUM(delta),0) s FROM pv_ledger WHERE delta>0')->fetch()['s'];
  $vou=$pdo->prepare("SELECT 1 FROM entitlements WHERE account_id=? AND product='voucher_academy_20' AND status='active'"); $vou->execute([$id]);
  j(['ok'=>true,'streak'=>$streak,'badges'=>$b->fetchAll(),'novità'=>$novità,'pv'=>pvBalance($id),
     'community_pv'=>$tot,'community_obiettivo'=>1000000,
     'voucher20'=>$vou->fetch()?('V20-'.substr($a['sic'],4)):null]);
}
if($action==='web3_esplorato'){
  if(!csrfCheck()) j(['ok'=>false,'err'=>'token csrf mancante'],403);
  $dato=badgeDai($pdo,$a['id'],'pioniere_web3',50);
  j(['ok'=>true,'badge'=>$dato,'pv'=>pvBalance($a['id']),'nota'=>$dato?'Badge Pioniere Web3, +50 PV':'già ottenuto']);
}
if($action==='stato'){
  $pv=pvBalance($a['id']); list($cur,$next)=vipDi($pv);
  j(['ok'=>true,'pv'=>$pv,'vip'=>$cur,'vip_nome'=>$cur?('VIP '.$cur):'Member','prossimo'=>$next,
     'mancanti'=>$next?max(0,$next['pv']-$pv):0,'livelli'=>vipLevels()]);
}
if($action==='premia'){
  if(!csrfCheck()) j(['ok'=>false,'err'=>'token csrf mancante'],403);
  $az=trim($in['azione']??''); $map=azioni();
  if(!isset($map[$az])) j(['ok'=>false,'err'=>'azione non premiabile'],422);
  list($pvAmt,$daily)=$map[$az];
  $reason='g_'.$az.($daily?('_'.gmdate('Ymd')):'');
  $chk=$pdo->prepare('SELECT 1 FROM pv_ledger WHERE account_id=? AND reason=? LIMIT 1'); $chk->execute([$a['id'],$reason]);
  if($chk->fetch()) j(['ok'=>true,'premiato'=>0,'pv'=>pvBalance($a['id']),'nota'=>'già premiato']);
  pvAdd($a['id'],$pvAmt,$reason); ev('gamify',$a['id'],['az'=>$az,'pv'=>$pvAmt]);
  j(['ok'=>true,'premiato'=>$pvAmt,'pv'=>pvBalance($a['id'])]);
}
if($action==='corso_manuale'){
  if(!csrfCheck()) j(['ok'=>false,'err'=>'token csrf mancante'],403);
  $tot=$pdo->prepare("SELECT COUNT(*) c FROM pv_ledger WHERE account_id=? AND reason LIKE 'g_corso_manuale_%'"); $tot->execute([$a['id']]); $n=(int)$tot->fetch()['c'];
  if($n>=20) j(['ok'=>true,'premiato'=>0,'corsi'=>$n,'pv'=>pvBalance($a['id']),'nota'=>'Hai raggiunto il tetto di 20 corsi, 20 PV totali.']);
  $titolo=trim($in['titolo']??''); if($titolo==='') j(['ok'=>false,'err'=>'inserisci il titolo del corso'],422);
  pvAdd($a['id'],1,'g_corso_manuale_'.($n+1)); ev('corso_manuale',$a['id'],['n'=>$n+1,'titolo'=>$titolo]);
  j(['ok'=>true,'premiato'=>1,'corsi'=>$n+1,'pv'=>pvBalance($a['id'])]);
}
if($action==='corsi_manuali'){
  $tot=$pdo->prepare("SELECT COUNT(*) c FROM pv_ledger WHERE account_id=? AND reason LIKE 'g_corso_manuale_%'"); $tot->execute([$a['id']]); $n=(int)$tot->fetch()['c'];
  j(['ok'=>true,'corsi'=>$n,'max'=>20]);
}
if($action==='missioni'){
  $oggi=gmdate('Ymd'); $pv=pvBalance($a['id']);
  $fatto=function($r) use($pdo,$a){ $s=$pdo->prepare('SELECT 1 FROM pv_ledger WHERE account_id=? AND reason=? LIMIT 1'); $s->execute([$a['id'],$r]); return (bool)$s->fetch(); };
  $profiloOk=$fatto('profilo_completo_bonus');
  $m=[
   ['k'=>'login_giorno','t'=>'Entra oggi nella dashboard','pv'=>1,'fatta'=>$fatto('g_login_giorno_'.$oggi)],
   ['k'=>'profilo','t'=>'Completa il profilo','pv'=>1000,'fatta'=>$profiloOk,'link'=>'profilo.html'],
   ['k'=>'audit_fatto','t'=>'Fai l’audit gratuito delle 30 normative','pv'=>10,'fatta'=>$fatto('g_audit_fatto_'.$oggi),'link'=>'audit.html'],
   ['k'=>'corso_aperto','t'=>'Apri l’Academy e scegli un corso','pv'=>1,'fatta'=>$fatto('g_corso_aperto_'.$oggi),'link'=>'academy.html'],
   ['k'=>'invito_inviato','t'=>'Condividi il tuo link di invito','pv'=>1,'fatta'=>$fatto('g_invito_inviato_'.$oggi),'link'=>'profilo.html'],
   ['k'=>'mondo_esplorato','t'=>'Esplora un mondo 3D dell’ecosistema','pv'=>1,'fatta'=>$fatto('g_mondo_esplorato_'.$oggi),'link'=>'mondi.html']
  ];
  j(['ok'=>true,'missioni'=>$m,'pv'=>$pv]);
}
j(['ok'=>false,'err'=>'azione sconosciuta'],404);
