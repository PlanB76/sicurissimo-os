<?php
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount(); if(!$a) j(['ok'=>false],401); $pdo=db(); $plan=require __DIR__.'/../src/comp_plan.php';
$st=$pdo->prepare('SELECT n.account_id,a.sic,a.nome,a.cognome,n.rank,n.qualified,n.vol_personal,n.vol_group FROM network_nodes n JOIN accounts a ON a.id=n.account_id WHERE n.parent_sic=?');
$st->execute([$a['sic']]); $downline=$st->fetchAll();
$self=$pdo->prepare('SELECT vol_personal,vol_group,rank FROM network_nodes WHERE account_id=?'); $self->execute([$a['id']]); $me=$self->fetch()?:['vol_group'=>0,'rank'=>0];
$next=null; foreach($plan['ranks'] as $lvl=>$r){ if(($me['vol_group']??0)<$r['vol']){ $next=['rank'=>$lvl,'nome'=>$r['nome'],'vol'=>$r['vol']]; break; } }
j(['ok'=>true,'downline'=>$downline,'volumi'=>$me,'prossimo_rank'=>$next,'piano'=>$plan,'nota'=>'Proiezione, non guadagno garantito.']);
