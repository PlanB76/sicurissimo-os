<?php
// Negozio modulistica. Sblocco con PV o automatico per livello VIP. Download solo se sbloccato.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
$pdo=db(); $in=body(); $action=$_GET['action']??'stato';
$cat=json_decode(file_get_contents(__DIR__.'/../data/modulistica.json'),true);

function vipLevel($pv){ $s=[2000=>1,5000=>2,10000=>3,25000=>4,50000=>5]; $lv=0; foreach($s as $th=>$l){ if($pv>=$th)$lv=$l; } return $lv; }
// sblocchi automatici per VIP: pacchetti gratis
function packGratisPerVip($lv){
  $g=[];
  if($lv>=1){ $g[]='nomine'; $g[]='informative'; $g[]='kit_start'; }
  if($lv>=2){ $g[]='haccp_registri'; }     // VIP1: nomine + informative privacy
  // VIP3: DVR o manuale HACCP a scelta, gestito a parte
  if($lv>=5){ $g[]='__all__'; }                          // VIP5: tutto
  return $g;
}
function docIndex($cat){ $idx=[]; foreach($cat as $area=>$blk){ foreach($blk['items'] as $it){ $it['area']=$area; $idx[$it['id']]=$it; } } return $idx; }

if($action==='stato'){
  $pv=pvBalance($a['id']); $lv=vipLevel($pv); $packs=packGratisPerVip($lv);
  $u=$pdo->prepare('SELECT doc_id FROM doc_unlocks WHERE account_id=?'); $u->execute([$a['id']]);
  $sbloccati=array_map(fn($r)=>$r['doc_id'],$u->fetchAll());
  $vip3choice=null;
  if($lv>=3){ $c=$pdo->prepare("SELECT doc_id FROM doc_unlocks WHERE account_id=? AND via='vip3_scelta' LIMIT 1"); $c->execute([$a['id']]); $r=$c->fetch(); $vip3choice=$r?$r['doc_id']:null; }
  j(['ok'=>true,'pv'=>$pv,'vip'=>$lv,'packs_gratis'=>$packs,'sbloccati'=>$sbloccati,'vip3_scelta'=>$vip3choice,'catalogo'=>$cat]);
}
if($action==='sblocca'){
  if(!csrfCheck()) j(['ok'=>false,'err'=>'token csrf mancante'],403);
  $id=trim($in['doc_id']??''); $idx=docIndex($cat); if(!isset($idx[$id])) j(['ok'=>false,'err'=>'documento sconosciuto'],404);
  $doc=$idx[$id];
  // già sbloccato?
  $e=$pdo->prepare('SELECT 1 FROM doc_unlocks WHERE account_id=? AND doc_id=?'); $e->execute([$a['id'],$id]);
  if($e->fetch()) j(['ok'=>true,'già'=>true,'download'=>'api/modulistica.php?action=download&id='.$id]);
  $pv=pvBalance($a['id']); $lv=vipLevel($pv); $packs=packGratisPerVip($lv);
  $gratis = in_array('__all__',$packs) || (isset($doc['pack']) && in_array($doc['pack'],$packs));
  if($gratis){
    $pdo->prepare('INSERT INTO doc_unlocks(account_id,doc_id,via,created_at) VALUES(?,?,?,?)')->execute([$a['id'],$id,'vip',gmdate('c')]);
    ev('doc_sblocco',$a['id'],['id'=>$id,'via'=>'vip']);
    j(['ok'=>true,'via'=>'vip','download'=>'api/modulistica.php?action=download&id='.$id]);
  }
  // pagamento in PV con lock e ricontrollo saldo
  $costo=(int)$doc['pv'];
  $pdo->beginTransaction();
  try{
    $row=$pdo->query('SELECT COALESCE(SUM(delta),0) s FROM pv_ledger WHERE account_id='.(int)$a['id'].' FOR UPDATE')->fetch();
    if((int)$row['s']<$costo){ $pdo->rollBack(); j(['ok'=>false,'err'=>'PV insufficienti','pv'=>(int)$row['s'],'costo'=>$costo],402); }
    $pdo->prepare('INSERT INTO pv_ledger(account_id,delta,reason,ref,created_at) VALUES(?,?,?,?,?)')->execute([$a['id'],-$costo,'sblocco_doc',$id,gmdate('c')]);
    $pdo->prepare('INSERT INTO doc_unlocks(account_id,doc_id,via,created_at) VALUES(?,?,?,?)')->execute([$a['id'],$id,'pv',gmdate('c')]);
    $pdo->commit();
  }catch(Exception $ex){ $pdo->rollBack(); j(['ok'=>false,'err'=>'sblocco non riuscito'],500); }
  ev('doc_sblocco',$a['id'],['id'=>$id,'via'=>'pv','pv'=>$costo]);
  j(['ok'=>true,'via'=>'pv','pv'=>pvBalance($a['id']),'download'=>'api/modulistica.php?action=download&id='.$id]);
}
if($action==='vip3_scegli'){
  if(!csrfCheck()) j(['ok'=>false,'err'=>'token csrf mancante'],403);
  $pv=pvBalance($a['id']); if(vipLevel($pv)<3) j(['ok'=>false,'err'=>'serve il livello VIP 3'],403);
  $id=trim($in['doc_id']??''); if(!in_array($id,['SIC-10','HACCP-01'])) j(['ok'=>false,'err'=>'scelta non valida, DVR o Manuale HACCP'],422);
  $c=$pdo->prepare("SELECT 1 FROM doc_unlocks WHERE account_id=? AND via='vip3_scelta'"); $c->execute([$a['id']]);
  if($c->fetch()) j(['ok'=>false,'err'=>'scelta già effettuata'],409);
  $pdo->prepare('INSERT INTO doc_unlocks(account_id,doc_id,via,created_at) VALUES(?,?,?,?)')->execute([$a['id'],$id,'vip3_scelta',gmdate('c')]);
  ev('doc_sblocco',$a['id'],['id'=>$id,'via'=>'vip3']);
  j(['ok'=>true,'download'=>'api/modulistica.php?action=download&id='.$id]);
}
if($action==='download'){
  $id=trim($_GET['id']??''); $idx=docIndex($cat); if(!isset($idx[$id])){ http_response_code(404); exit('no'); }
  $e=$pdo->prepare('SELECT 1 FROM doc_unlocks WHERE account_id=? AND doc_id=?'); $e->execute([$a['id'],$id]);
  if(!$e->fetch()){ http_response_code(403); exit('documento non sbloccato'); }
  $nomeFile=$idx[$id]['f'];
  if(($_GET['fmt']??'')==='pdf'){
    $pdfNome=preg_replace('/\.docx$/i','.pdf',$nomeFile);
    if(is_file(__DIR__.'/../download/modulistica/pdf/'.$pdfNome)) $nomeFile='pdf/'.$pdfNome;
  }
  $file=__DIR__.'/../download/modulistica/'.$nomeFile;
  if(!is_file($file)){ http_response_code(404); exit('file mancante'); }
  ev('doc_download',$a['id'],['id'=>$id]);
  $ext=strtolower(pathinfo($file,PATHINFO_EXTENSION));
  $ct=$ext==='pdf'?'application/pdf':'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
  header('Content-Type: '.$ct);
  header('Content-Disposition: attachment; filename="81plus_'.basename($nomeFile).'"');
  header('Content-Length: '.filesize($file)); readfile($file); exit;
}
j(['ok'=>false,'err'=>'azione sconosciuta'],404);
