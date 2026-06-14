<?php
// 81+ RETE 3D. Albero della downline coi dati dei membri.
// Modo utente, vede SOLO la propria rete, dati essenziali. Modo globale, solo con ADMIN_KEY, tutto.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$pdo=db();
$adminKey=getenv('ADMIN_KEY')?:'';
$globale=$adminKey!=='' && (($_GET['k']??'')===$adminKey);
$a=$globale?null:currentAccount();
if(!$globale && !$a) j(['ok'=>false,'err'=>'non autenticato'],401);

function pvDi($pdo,$id){ try{ $q=$pdo->prepare('SELECT COALESCE(SUM(delta),0) s FROM pv_ledger WHERE account_id=?'); $q->execute([$id]); return (int)$q->fetch()['s']; }catch(Exception $e){ return 0; } }
function rankDi($pdo,$id){ try{ $q=$pdo->prepare('SELECT rank FROM network_nodes WHERE account_id=?'); $q->execute([$id]); $r=$q->fetch(); return $r?(int)$r['rank']:0; }catch(Exception $e){ return 0; } }

$nodi=[]; $archi=[]; $visti=[];
$limite=$globale?1500:800; $maxProf=$globale?9:7;

function aggiungi($pdo,$acc,$prof,$globale){
  global $nodi,$visti,$limite;
  if(isset($visti[$acc['sic']])||count($nodi)>=$limite) return false;
  $visti[$acc['sic']]=true;
  $n=['sic'=>$acc['sic'],'nome'=>trim(($acc['nome']??'').' '.($acc['cognome']??''))?:($acc['ragione_sociale']??'membro'),
      'tipo'=>$acc['tipo'],'rank'=>rankDi($pdo,$acc['id']),'pv'=>pvDi($pdo,$acc['id']),
      'dal'=>substr($acc['created_at']??'',0,10),'tg'=>!empty($acc['telegram_id']),'prof'=>$prof];
  if($globale){ $n['email']=$acc['email']; $n['stato']=$acc['status']; $n['kyc']=(int)$acc['kyc_level']; }
  $nodi[]=$n;
  return true;
}

$st=$pdo->prepare('SELECT * FROM accounts WHERE ref_by=? ORDER BY id ASC LIMIT 200');
if($globale){
  $radici=$pdo->query("SELECT * FROM accounts WHERE (ref_by IS NULL OR ref_by='') AND email NOT LIKE '%@wallet.81plus%' ORDER BY id ASC LIMIT 120")->fetchAll();
}else{
  $radici=[$a];
}
$coda=[];
foreach($radici as $r){ if(aggiungi($pdo,$r,0,$globale)) $coda[]=[$r,0]; }
while($coda && count($nodi)<$limite){
  [$padre,$prof]=array_shift($coda);
  if($prof>=$maxProf) continue;
  $st->execute([$padre['sic']]);
  foreach($st->fetchAll() as $fig){
    if(aggiungi($pdo,$fig,$prof+1,$globale)){ $archi[]=[$padre['sic'],$fig['sic']]; $coda[]=[$fig,$prof+1]; }
  }
}
// conteggio diretti per ogni nodo
$dir=[]; foreach($archi as $e) $dir[$e[0]]=($dir[$e[0]]??0)+1;
foreach($nodi as &$n) $n['diretti']=$dir[$n['sic']]??0;
j(['ok'=>true,'globale'=>$globale,'nodi'=>$nodi,'archi'=>$archi,'totale'=>count($nodi)]);
