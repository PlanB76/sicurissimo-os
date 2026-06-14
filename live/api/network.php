<?php
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php'; require_once __DIR__.'/../src/comp_plan.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401); $pdo=db(); $action=$_GET['action']??'tree';
function kids($pdo,$sic,$depth){ if($depth>6) return [];
  $st=$pdo->prepare('SELECT a.sic,a.nome,a.cognome,a.tipo,a.status,n.rank,n.qualified,n.vol_personal,n.vol_group FROM accounts a JOIN network_nodes n ON n.account_id=a.id WHERE a.ref_by=?');
  $st->execute([$sic]); $out=[]; foreach($st->fetchAll() as $r){ $r['kids']=kids($pdo,$r['sic'],$depth+1); $out[]=$r; } return $out; }
if($action==='tree'){ j(['ok'=>true,'sic'=>$a['sic'],'tree'=>kids($pdo,$a['sic'],0)]); }
if($action==='pipeline'){ $st=$pdo->prepare('SELECT stage,COUNT(*) c FROM leads WHERE owner_sic=? GROUP BY stage'); $st->execute([$a['sic']]);
  $by=['nuovo'=>0,'contatto'=>0,'qualificato'=>0,'cliente'=>0]; foreach($st->fetchAll() as $r){ $st2=$r['stage']?:'nuovo'; if(isset($by[$st2]))$by[$st2]=(int)$r['c']; } j(['ok'=>true,'pipeline'=>$by]); }
if($action==='rank'){ $st=$pdo->prepare('SELECT vol_personal,vol_group,rank FROM network_nodes WHERE account_id=?'); $st->execute([$a['id']]); $n=$st->fetch()?:['vol_personal'=>0,'vol_group'=>0,'rank'=>0];
  $rr=planRanks(); $q=rankQualificato((int)$n['vol_personal'],(int)$n['vol_group']);
  j(['ok'=>true,'vol_personal'=>(int)$n['vol_personal'],'vol_group'=>(int)$n['vol_group'],'rank_attuale'=>(int)$n['rank'],'rank_qualificato'=>$q,'rank_nome'=>$rr[$q]['nome'],'split'=>planSplit()]); }
j(['ok'=>false,'err'=>'azione sconosciuta'],404);
