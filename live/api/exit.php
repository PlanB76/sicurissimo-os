<?php
// Pannello EXIT, riservato all admin DIO. Legge e salva gli input del piano, calcola le tre vie.
// Nessun numero inventato, parte da quello che c e nel database o da zero. Non e una promessa.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$pdo=db();
$a=function_exists('currentAccount')?currentAccount():null;
// solo l Ammiraglio, SIC-0000001 o ruolo admin
$isAdmin = $a && (($a['sic']??'')==='SIC-0000001' || ($a['ruolo']??'')==='admin');
if(!$isAdmin) j(['ok'=>false,'err'=>'solo admin'],403);

$action=$_GET['action']??'leggi';

// chiave-valore semplice in tabella settings, niente schema nuovo pesante
function exGet($pdo,$k,$def){ try{ $q=$pdo->prepare("SELECT v FROM app_settings WHERE k=?"); $q->execute([$k]); $r=$q->fetch(); return $r?$r['v']:$def; }catch(Exception $e){ return $def; } }
function exSet($pdo,$k,$v){ try{ $pdo->prepare("INSERT INTO app_settings(k,v) VALUES(?,?) ON DUPLICATE KEY UPDATE v=VALUES(v)")->execute([$k,$v]); }catch(Exception $e){
  try{ $pdo->prepare("INSERT OR REPLACE INTO app_settings(k,v) VALUES(?,?)")->execute([$k,$v]); }catch(Exception $e2){} } }

$campi=['mrr','onetime','costi','clienti','nuovi_mese','churn','meta','anni','cresc_prudente','cresc_medio','cresc_ambizioso','mult_prudente','mult_medio','mult_ambizioso','nodi_medio','val_nodo','quota_div'];

if($action==='salva'){
  $in=json_decode(file_get_contents('php://input'),true)?:[];
  foreach($campi as $c){ if(isset($in[$c])) exSet($pdo,'exit_'.$c,(string)(0+$in[$c])); }
  j(['ok'=>true,'salvato'=>true]);
}

// leggi e calcola
$v=[]; foreach($campi as $c){ $v[$c]=(float)exGet($pdo,'exit_'.$c,0); }
// default prudenti solo sui tassi, non sui tuoi numeri di business
if($v['anni']<=0) $v['anni']=5;
if($v['cresc_prudente']<=0) $v['cresc_prudente']=0.20;
if($v['cresc_medio']<=0) $v['cresc_medio']=0.50;
if($v['cresc_ambizioso']<=0) $v['cresc_ambizioso']=1.00;
if($v['mult_prudente']<=0) $v['mult_prudente']=2.0;
if($v['mult_medio']<=0) $v['mult_medio']=3.0;
if($v['mult_ambizioso']<=0) $v['mult_ambizioso']=4.0;
if($v['quota_div']<=0) $v['quota_div']=0.5;
if($v['churn']<=0) $v['churn']=0.03;

$arrOggi=$v['mrr']*12;
$margineMese=$v['mrr']+$v['onetime']-$v['costi'];
$margineAnno=$margineMese*12;
$anni=max(0,(int)round($v['anni']));
function proj($arr,$g,$anni){ $x=$arr; for($i=0;$i<$anni;$i++){ $x=$x*(1+$g); } return $x; }
$arrUscita=[
  'prudente'=>proj($arrOggi,$v['cresc_prudente'],$anni),
  'medio'=>proj($arrOggi,$v['cresc_medio'],$anni),
  'ambizioso'=>proj($arrOggi,$v['cresc_ambizioso'],$anni),
];
$via1=[ // vendita su multiplo ARR
  'prudente'=>$arrUscita['prudente']*$v['mult_prudente'],
  'medio'=>$arrUscita['medio']*$v['mult_medio'],
  'ambizioso'=>$arrUscita['ambizioso']*$v['mult_ambizioso'],
];
$via2=$v['nodi_medio']*$v['val_nodo']; // vendita rete
$via3=$margineAnno*$v['quota_div'];    // dividendo annuo
$miglioreMedia=max($via1['medio'],$via2);

j(['ok'=>true,
   'input'=>$v,
   'arr_oggi'=>$arrOggi,'margine_mese'=>$margineMese,'margine_anno'=>$margineAnno,
   'anni'=>$anni,'arr_uscita'=>$arrUscita,
   'via1_vendita'=>$via1,'via2_rete'=>$via2,'via3_dividendo_annuo'=>$via3,
   'meta'=>$v['meta'],'migliore_via_media'=>$miglioreMedia,'scarto_meta'=>$miglioreMedia-$v['meta'],
   'nota'=>'Stime sui tuoi numeri, non promesse. Il prezzo vero lo fa chi compra.']);
