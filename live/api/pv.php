<?php
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$action=$_GET['action']??''; $a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401); $pdo=db(); $in=body();
if($action==='saldo'){ j(['ok'=>true,'pv'=>pvBalance($a['id']),'nota'=>'PV crediti fedelta, senza valore in euro dichiarato']); }
if($action==='trasferisci'){
  if(!cfg()['pv_transfer']) j(['ok'=>false,'err'=>'trasferimento PV non attivo'],403);   // interruttore legale
  if(!csrfCheck()) j(['ok'=>false,'err'=>'token csrf mancante o non valido'],403);
  if(!rateOk('pvtx:'.$a['id'],30,3600)) j(['ok'=>false,'err'=>'troppi trasferimenti, riprova più tardi'],429);
  $toSic=trim($in['to_sic']??''); $amt=(int)($in['amount']??0);
  if($amt<=0) j(['ok'=>false,'err'=>'importo non valido'],422);
  $q=$pdo->prepare('SELECT id FROM accounts WHERE sic=?'); $q->execute([$toSic]); $dest=$q->fetch();
  if(!$dest) j(['ok'=>false,'err'=>'SIC destinatario non trovato'],404);
  if((int)$dest['id']===(int)$a['id']) j(['ok'=>false,'err'=>'non puoi trasferire a te stesso'],422);
  $pdo->beginTransaction();
  try{
    if(drv()==='mysql'){ $pdo->prepare('SELECT COALESCE(SUM(delta),0) b FROM pv_ledger WHERE account_id=? FOR UPDATE')->execute([$a['id']]); }
    if($amt>pvBalance($a['id'])){ $pdo->rollBack(); j(['ok'=>false,'err'=>'PV insufficienti'],422); }
    pvAdd($a['id'],-$amt,'trasferimento_out',$toSic); pvAdd($dest['id'],$amt,'trasferimento_in',$a['sic']);
    $pdo->prepare('INSERT INTO pv_transfers(from_account,to_sic,amount,created_at) VALUES(?,?,?,?)')->execute([$a['id'],$toSic,$amt,gmdate('c')]);
    $pdo->commit();
  }catch(Throwable $e){ $pdo->rollBack(); j(['ok'=>false,'err'=>'trasferimento non riuscito'],500); }
  ev('pv_transfer',$a['id'],['to'=>$toSic,'amt'=>$amt]); j(['ok'=>true,'pv'=>pvBalance($a['id'])]);
}
if($action==='premia_click'){
  if(!csrfCheck()) j(['ok'=>false,'err'=>'token csrf mancante o non valido'],403);
  if(!rateOk('pvclick:'.$a['id'],120,3600)) j(['ok'=>false,'err'=>'troppi clic'],429);
  $slug=preg_replace('/[^a-z0-9_]/','',strtolower($in['slug']??'')); if($slug==='') j(['ok'=>false,'err'=>'slug mancante'],422);
  $reason='click_'.$slug;
  $q=$pdo->prepare('SELECT 1 FROM pv_ledger WHERE account_id=? AND reason=?'); $q->execute([$a['id'],$reason]);
  if($q->fetch()) j(['ok'=>true,'pv'=>pvBalance($a['id']),'già'=>true]);
  pvAdd($a['id'],1,$reason,$slug); ev('pv_click',$a['id'],['slug'=>$slug]);
  j(['ok'=>true,'pv'=>pvBalance($a['id']),'gift'=>1]);
}
if($action==='benefit_catalog'){ j(['ok'=>true,'benefit'=>[['k'=>'sconto_membership','nome'=>'Sconto su membership','costo'=>490],['k'=>'corso_extra','nome'=>'Corso extra in Academy','costo'=>990],['k'=>'sigillo_bronze','nome'=>'Sigillo Bronze','costo'=>9900]]]); }
if($action==='spend'){
  if(!csrfCheck()) j(['ok'=>false,'err'=>'token csrf mancante o non valido'],403);
  $cat=['sconto_membership'=>490,'corso_extra'=>990,'sigillo_bronze'=>9900]; $k=$in['benefit']??'';
  if(!isset($cat[$k])) j(['ok'=>false,'err'=>'benefit non valido'],422); $cost=$cat[$k];
  $pdo->beginTransaction();
  try{ if(drv()==='mysql')$pdo->prepare('SELECT COALESCE(SUM(delta),0) b FROM pv_ledger WHERE account_id=? FOR UPDATE')->execute([$a['id']]);
    if($cost>pvBalance($a['id'])){ $pdo->rollBack(); j(['ok'=>false,'err'=>'PV insufficienti'],422); }
    pvAdd($a['id'],-$cost,'benefit_'.$k); $pdo->commit();
  }catch(Throwable $e){ $pdo->rollBack(); j(['ok'=>false,'err'=>'spesa non riuscita'],500); }
  ev('pv_spend',$a['id'],['benefit'=>$k]); j(['ok'=>true,'pv'=>pvBalance($a['id'])]);
}
if($action==='movimenti'){ $st=$pdo->prepare('SELECT delta,reason,ref,created_at FROM pv_ledger WHERE account_id=? ORDER BY id DESC LIMIT 50'); $st->execute([$a['id']]); j(['ok'=>true,'movimenti'=>$st->fetchAll()]); }
j(['ok'=>false,'err'=>'azione sconosciuta'],404);
