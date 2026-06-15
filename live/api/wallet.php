<?php
// 81+ WALLET API. Saldo, policy, coupon, pacchetti documenti, autoricarica.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php'; require_once __DIR__.'/../src/wallet81.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
$pdo=db(); $az=$_GET['az']??'stato'; $in=json_decode(file_get_contents('php://input'),true)?:[];
$LP=json_decode(@file_get_contents(__DIR__.'/../data/prezzi_pv.json'),true)?:[];
if($az==='stato'){
  $pol=wPolicy(); $b=function_exists('pvBalance')?pvBalance($a['id']):0;
  $bundles=[]; foreach(($LP['bundle']??[]) as $bid=>$bb){
    $q=$pdo->prepare("SELECT created_at FROM pv_ledger WHERE account_id=? AND reason=? ORDER BY id DESC LIMIT 1"); $q->execute([$a['id'],'bundle_'.$bid]); $r=$q->fetch();
    $att=$r && strtotime($r['created_at'])>time()-(int)($bb['mesi']??12)*30*86400;
    $bundles[]=['id'=>$bid,'nome'=>$bb['nome'],'pv'=>$bb['pv'],'cosa'=>$bb['cosa'],'attivo'=>$att,'fino'=>$att?gmdate('d/m/Y',strtotime($r['created_at'])+(int)($bb['mesi']??12)*30*86400):null];
  }
  j(['ok'=>true,'saldo'=>$b,'policy'=>$pol,'sotto_soglia'=>$b<$pol['soglia_autoricarica'],'critico'=>$b<$pol['minimo_pv'],
     'bundles'=>$bundles,'autoricarica'=>wAutoSettings($pdo,$a['id']),'packs'=>$LP['packs']??[]]);
}
if($az==='bundle_compra'){
  $bid=$in['bundle']??''; $b=($LP['bundle']??[])[$bid]??null;
  if(!$b) j(['ok'=>false,'err'=>'pacchetto sconosciuto'],404);
  if(wBundleAttivo($pdo,$a['id'],$b['categorie'][0])) j(['ok'=>false,'err'=>'hai già un pacchetto attivo su questa famiglia']);
  $saldo=pvBalance($a['id']);
  if($saldo<(int)$b['pv']) j(['ok'=>false,'err'=>'pv_insufficienti','costo'=>(int)$b['pv'],'saldo'=>$saldo],402);
  pvAdd($a['id'],-(int)$b['pv'],'bundle_'.$bid);
  if(function_exists('ev')) @ev('bundle_acquistato',$a['id'],['bundle'=>$bid]);
  j(['ok'=>true,'saldo'=>pvBalance($a['id']),'messaggio'=>'Pacchetto attivo per '.$b['mesi'].' mesi, da ora i documenti finali di queste famiglie sono compresi.']);
}
if($az==='coupon'){
  $cod=strtoupper(preg_replace('/[^A-Z0-9]/','',strtoupper($in['codice']??'')));
  if($cod==='') j(['ok'=>false,'err'=>'scrivi il codice']);
  if(!rateOk('coupon:'.$a['id'],10,3600)) j(['ok'=>false,'err'=>'troppi tentativi'],429);
  $C=json_decode(@file_get_contents(__DIR__.'/../data/coupon.json'),true)?:['coupon'=>[]];
  $cp=null; foreach($C['coupon'] as $x) if(strtoupper($x['codice'])===$cod){ $cp=$x; break; }
  if(!$cp) j(['ok'=>false,'err'=>'codice non valido']);
  if(!empty($cp['scadenza']) && strtotime($cp['scadenza'].' 23:59:59')<time()) j(['ok'=>false,'err'=>'codice scaduto']);
  $reason='coupon_'.$cod;
  $c=$pdo->prepare('SELECT 1 FROM pv_ledger WHERE account_id=? AND reason=?'); $c->execute([$a['id'],$reason]);
  if($c->fetch()) j(['ok'=>false,'err'=>'lo hai già usato']);
  if(!empty($cp['usi_max'])){ $u=$pdo->prepare('SELECT COUNT(*) c FROM pv_ledger WHERE reason=?'); $u->execute([$reason]); if((int)$u->fetch()['c']>=(int)$cp['usi_max']) j(['ok'=>false,'err'=>'codice esaurito']); }
  if(($cp['tipo']??'')==='pv_bonus'){ pvAdd($a['id'],(int)$cp['valore'],$reason); if(function_exists('ev')) @ev('coupon_usato',$a['id'],['c'=>$cod]); j(['ok'=>true,'pv'=>(int)$cp['valore'],'saldo'=>pvBalance($a['id'])]); }
  j(['ok'=>false,'err'=>'tipo coupon non gestito']);
}
if($az==='autoricarica'){
  $set=['attiva'=>!empty($in['attiva']),'soglia'=>max(100,min(1000,(int)($in['soglia']??wPolicy()['soglia_autoricarica']))),'pack'=>in_array($in['pack']??'',array_column($LP['packs']??[],'id'))?$in['pack']:'pv50'];
  wAutoSalva($a['id'],$set);
  j(['ok'=>true,'autoricarica'=>$set,'nota'=>'L addebito automatico corre sull abbonamento PayPal di autoricarica, lo gestisci e lo disdici dal tuo PayPal quando vuoi.']);
}
j(['ok'=>false,'err'=>'azione sconosciuta'],400);
