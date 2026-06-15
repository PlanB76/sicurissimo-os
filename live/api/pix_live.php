<?php
// 81+ PIX LIVE. Numeri reali per la pagina del globo, mai gonfiati.
// online, conta le presenze reali degli ultimi 10 minuti.
// ultimi, gli acquisti reali con SIC mascherato. Niente eventi inventati.
require_once __DIR__.'/../src/db.php';
$pdo=db(); $az=$_GET['az']??'';

if($az==='ping'){
  $s=preg_replace('/[^a-zA-Z0-9]/','',$_GET['s']??''); if($s==='')$s=substr(md5(clientIp().($_SERVER['HTTP_USER_AGENT']??'')),0,12);
  if(rateOk('pixping_'.$s,4,60)) ev('pix_presenza',null,['s'=>substr($s,0,16)]);
  j(['ok'=>true]);
}

if($az==='live'){
  $da=gmdate('c',time()-600);
  $online=1;
  try{
    $q=$pdo->prepare("SELECT data FROM events WHERE type='pix_presenza' AND created_at>=?"); $q->execute([$da]);
    $set=[]; foreach($q->fetchAll(PDO::FETCH_COLUMN) as $d){ $j=json_decode($d,true); if(isset($j['s']))$set[$j['s']]=1; }
    $online=max(1,count($set));
  }catch(Throwable $e){}
  // ultimi acquisti reali, SIC mascherato per privacy
  $ultimi=[];
  try{
    $q=$pdo->query("SELECT pos,sic,azienda,created_at FROM pixel_muro WHERE account_id IS NOT NULL AND stato='attivo' ORDER BY created_at DESC LIMIT 6");
    foreach($q->fetchAll(PDO::FETCH_ASSOC) as $r){
      $sic=$r['sic']?substr($r['sic'],0,6).'***'.substr($r['sic'],-2):'';
      $ultimi[]=['pos'=>(int)$r['pos'],'sic'=>$sic,'azienda'=>$r['azienda']?:null,'quando'=>$r['created_at']];
    }
  }catch(Throwable $e){}
  // liberi reali
  $occ=0; try{ $occ=(int)$pdo->query("SELECT COUNT(*) c FROM pixel_muro WHERE account_id IS NOT NULL OR (tipo IS NOT NULL AND tipo!='libero')")->fetch()['c']; }catch(Throwable $e){}
  // promo reale condivisa con pixel.php
  require_once __DIR__.'/pixel_promo.php';
  j(['ok'=>true,'online'=>$online,'ultimi'=>$ultimi,'liberi'=>max(0,1000-$occ),'promo'=>promoLive($pdo)]);
}

j(['ok'=>false,'err'=>'azione sconosciuta'],404);
