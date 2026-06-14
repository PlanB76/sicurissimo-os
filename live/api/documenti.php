<?php
// 81+ FABBRICA DOCUMENTI. L'utente sceglie, il sistema precompila dal profilo,
// lui completa e scarica il documento personalizzato in Word o in stampa PDF.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php'; require_once __DIR__.'/../src/wallet81.php';
$a=currentAccount(); if(!$a){ if(($_GET['az']??'')==='catalogo') j(['ok'=>false,'err'=>'non autenticato'],401); http_response_code(401); exit('Accedi per usare la fabbrica documenti.'); }
$pdo=db();
$F=json_decode(file_get_contents(__DIR__.'/../data/fabbrica_documenti.json'),true);

function prefill($a,$p){
  switch($p){
    case 'ragione_sociale': return $a['ragione_sociale']?:trim(($a['nome']??'').' '.($a['cognome']??''));
    case 'piva': return $a['piva']??'';
    case 'indirizzo': return $a['indirizzo']??'';
    case 'ateco': return $a['ateco']??'';
    case 'dipendenti': return $a['dipendenti']??'';
    case 'nome_completo': return trim(($a['nome']??'').' '.($a['cognome']??''));
    case 'oggi': return (new DateTime('now',new DateTimeZone('Europe/Rome')))->format('d/m/Y');
  }
  return '';
}

function costoDoc($doc,$accId=null){
  if($accId && function_exists('wBundleAttivo') && wBundleAttivo(db(),$accId,$doc['categoria'])) return 0;
  $P=json_decode(@file_get_contents(__DIR__.'/../data/prezzi_pv.json'),true)?:['default'=>8];
  if(isset($P['per_doc'][$doc['id']])) return (int)$P['per_doc'][$doc['id']];
  if(isset($P['per_categoria'][$doc['categoria']])) return (int)$P['per_categoria'][$doc['categoria']];
  return (int)($P['default']??8);
}
function docHtml($titolo,$doc,$mappa,$corpo,$a,$finale=false){
  $html='<!DOCTYPE html><html lang="it"><head><meta charset="utf-8"><title>'.$titolo.'</title><style>
body{font-family:"DM Sans",Arial,Helvetica,sans-serif;color:#111;margin:34px;font-size:13px;line-height:1.55}
h1{font-size:21px;border-bottom:3px solid #E8501A;padding-bottom:8px;margin:0 0 4px}
.norma{color:#555;font-size:11.5px;margin-bottom:18px}
h2{font-size:15px;color:#E8501A;margin:20px 0 6px}
table.tb{width:100%;border-collapse:collapse;margin:8px 0}
table.tb td,table.tb th{border:1px solid #999;padding:6px 8px;font-size:12px;text-align:left;vertical-align:top}
table.tb th{background:#f3f3f3}
.c{text-align:center} .vuoto{color:#b35; letter-spacing:1px}
.firme{display:flex;gap:40px;flex-wrap:wrap;margin-top:26px}
.firma{min-width:200px;font-size:12px;text-align:center}
.linea{border-bottom:1px solid #111;height:34px;margin-bottom:5px}
.nota{margin-top:30px;border-top:1px solid #ccc;padding-top:8px;font-size:10px;color:#666}
@media print{.nostampa{display:none}}
body.anteprima::after{content:"ANTEPRIMA, NON VALIDA";position:fixed;top:42%;left:6%;right:6%;text-align:center;font-size:52px;color:rgba(232,80,26,0.16);transform:rotate(-18deg);font-weight:700;letter-spacing:4px;pointer-events:none}
</style></head><body class="'.(!empty($finale)?'':'anteprima').'">
<h1>'.$titolo.'</h1><div class="norma">Riferimenti normativi, '.htmlspecialchars($doc['norma']).' · '.htmlspecialchars($mappa['{{ragione_sociale}}']?strip_tags($mappa['{{ragione_sociale}}']):'').'</div>'
.$corpo.
'<div class="nota">Documento base generato dall\'ecosistema 81+ per '.htmlspecialchars(strip_tags($mappa['{{ragione_sociale}}'])).', SIC '.htmlspecialchars($a['sic']).', il '.htmlspecialchars(strip_tags($mappa['{{data}}'])).'. Le parti tra parentesi quadre vanno completate. Prima della firma e dell\'adozione il documento va verificato e integrato con i tecnici abilitati, RSPP, consulente HACCP o privacy secondo la materia. Non costituisce consulenza legale.</div>
</body></html>';
  return $html;
}
$az=$_GET['az']??'catalogo';
if($az==='catalogo'){
  $out=[];
  foreach($F['documenti'] as $d){
    $campi=[];
    foreach($d['campi'] as $c){ $c['val']=isset($c['p'])?prefill($a,$c['p']):''; $campi[]=$c; }
    $out[]=['id'=>$d['id'],'titolo'=>$d['titolo'],'categoria'=>$d['categoria'],'norma'=>$d['norma'],'costo_pv'=>costoDoc($d,$a['id']),'campi'=>$campi];
  }
  j(['ok'=>true,'categorie'=>$F['categorie'],'documenti'=>$out,'saldo_pv'=>function_exists('pvBalance')?pvBalance($a['id']):0]);
}

if($az==='genera'){
  $in=json_decode(file_get_contents('php://input'),true)?:$_POST;
  $id=$in['id']??''; $vals=$in['campi']??[];
  if(is_string($vals)) $vals=json_decode($vals,true)?:[];
  $doc=null; foreach($F['documenti'] as $d) if($d['id']===$id){ $doc=$d; break; }
  if(!$doc) j(['ok'=>false,'err'=>'documento sconosciuto'],404);
  if(!rateOk('fabbrica:'.$a['id'],40,3600)) j(['ok'=>false,'err'=>'troppe generazioni, riprova tra poco'],429);
  $mappa=[];
  foreach($doc['campi'] as $c){
    $v=trim((string)($vals[$c['k']]??''));
    if($v==='' && isset($c['p'])) $v=prefill($a,$c['p']);
    $mappa['{{'.$c['k'].'}}']=$v!==''?nl2br(htmlspecialchars($v)):'<span class="vuoto">[__________]</span>';
  }
  $corpo='';
  foreach($doc['sezioni'] as $s) $corpo.='<div class="sez">'.strtr($s,$mappa).'</div>';
  $titolo=htmlspecialchars($doc['titolo']);
  $html='<!DOCTYPE html><html lang="it"><head><meta charset="utf-8"><title>'.$titolo.'</title><style>
body{font-family:"DM Sans",Arial,Helvetica,sans-serif;color:#111;margin:34px;font-size:13px;line-height:1.55}
h1{font-size:21px;border-bottom:3px solid #E8501A;padding-bottom:8px;margin:0 0 4px}
.norma{color:#555;font-size:11.5px;margin-bottom:18px}
h2{font-size:15px;color:#E8501A;margin:20px 0 6px}
table.tb{width:100%;border-collapse:collapse;margin:8px 0}
table.tb td,table.tb th{border:1px solid #999;padding:6px 8px;font-size:12px;text-align:left;vertical-align:top}
table.tb th{background:#f3f3f3}
.c{text-align:center} .vuoto{color:#b35; letter-spacing:1px}
.firme{display:flex;gap:40px;flex-wrap:wrap;margin-top:26px}
.firma{min-width:200px;font-size:12px;text-align:center}
.linea{border-bottom:1px solid #111;height:34px;margin-bottom:5px}
.nota{margin-top:30px;border-top:1px solid #ccc;padding-top:8px;font-size:10px;color:#666}
@media print{.nostampa{display:none}}
body.anteprima::after{content:"ANTEPRIMA, NON VALIDA";position:fixed;top:42%;left:6%;right:6%;text-align:center;font-size:52px;color:rgba(232,80,26,0.16);transform:rotate(-18deg);font-weight:700;letter-spacing:4px;pointer-events:none}
</style></head><body class="'.(!empty($finale)?'':'anteprima').'">
<h1>'.$titolo.'</h1><div class="norma">Riferimenti normativi, '.htmlspecialchars($doc['norma']).' · '.htmlspecialchars($mappa['{{ragione_sociale}}']?strip_tags($mappa['{{ragione_sociale}}']):'').'</div>'
.$corpo.
'<div class="nota">Documento base generato dall\'ecosistema 81+ per '.htmlspecialchars(strip_tags($mappa['{{ragione_sociale}}'])).', SIC '.htmlspecialchars($a['sic']).', il '.htmlspecialchars(strip_tags($mappa['{{data}}'])).'. Le parti tra parentesi quadre vanno completate. Prima della firma e dell\'adozione il documento va verificato e integrato con i tecnici abilitati, RSPP, consulente HACCP o privacy secondo la materia. Non costituisce consulenza legale.</div>
</body></html>';
  // premio una tantum e tracciamento
  try{ $c=$pdo->prepare("SELECT 1 FROM pv_ledger WHERE account_id=? AND reason='doc_generato'"); $c->execute([$a['id']]);
       if(!$c->fetch() && function_exists('pvAdd')){ pvAdd($a['id'],15,'doc_generato'); } }catch(Exception $e){}
  if(function_exists('ev')) @ev('doc_creato',$a['id'],['doc'=>$id]);
  $fmt=$_GET['fmt']??($in['fmt']??'json');
  if($fmt==='html'){ header('Content-Type: text/html; charset=utf-8'); echo $html; exit; }
  j(['ok'=>true,'html'=>$html]);
}
if($az==='finale'){
  $in=json_decode(file_get_contents('php://input'),true)?:$_POST;
  if(isset($in['campi'])&&is_string($in['campi'])) $in['campi']=json_decode($in['campi'],true)?:[];
  $id=$in['id']??($in['doc']??''); $doc=null;
  foreach($F['documenti'] as $d) if($d['id']===$id){ $doc=$d; break; }
  if(!$doc) j(['ok'=>false,'err'=>'documento sconosciuto'],404);
  try{ $pdo->query('SELECT pagato FROM docs_salvati LIMIT 1'); }catch(Exception $e){ try{ $pdo->exec('ALTER TABLE docs_salvati ADD COLUMN pagato TINYINT NOT NULL DEFAULT 0'); }catch(Exception $x){} }
  $sid=(int)($in['salvato_id']??0); $pagato=false;
  if($sid){ $q=$pdo->prepare('SELECT pagato FROM docs_salvati WHERE id=? AND account_id=?'); $q->execute([$sid,$a['id']]); $r=$q->fetch(); if(!$r) $sid=0; else $pagato=!empty($r['pagato']); }
  $costo=costoDoc($doc,$a['id']);
  if(!$pagato){
    $saldo=function_exists('pvBalance')?pvBalance($a['id']):0;
    if($saldo<$costo) j(['ok'=>false,'err'=>'pv_insufficienti','costo'=>$costo,'saldo'=>$saldo],402);
  }
  // archivio della pratica, pagata
  if(!$sid){
    $pdo->prepare('INSERT INTO docs_salvati(account_id,doc_id,titolo,campi,pagato,updated_at) VALUES(?,?,?,?,1,?)')
        ->execute([$a['id'],$doc['id'],$doc['titolo'],json_encode($in['campi']??[],JSON_UNESCAPED_UNICODE),gmdate('c')]);
    $sid=(int)$pdo->lastInsertId();
  } else {
    $pdo->prepare('UPDATE docs_salvati SET campi=?,pagato=1,updated_at=? WHERE id=? AND account_id=?')->execute([json_encode($in['campi']??[],JSON_UNESCAPED_UNICODE),gmdate('c'),$sid,$a['id']]);
  }
  if(!$pagato && $costo>0 && function_exists('pvAdd')){ pvAdd($a['id'],-$costo,'doc_acquisto_'.$sid); }
  if(function_exists('ev')) @ev('doc_finale',$a['id'],['doc'=>$doc['id']]);
  $_GET['az']='genera'; $in['fmt']=$in['fmt']??'json';
  // rigenero il corpo come finale
  $finale=true;
  $mappa=[];
  foreach($doc['campi'] as $c){ $v=trim((string)(($in['campi'][$c['k']]??''))); if($v==='' && isset($c['p'])) $v=prefill($a,$c['p']);
    $mappa['{{'.$c['k'].'}}']=$v!==''?nl2br(htmlspecialchars($v)):'<span class="vuoto">[__________]</span>'; }
  $corpo=''; foreach($doc['sezioni'] as $s2) $corpo.='<div class="sez">'.strtr($s2,$mappa).'</div>';
  $titolo=htmlspecialchars($doc['titolo']);
  $html=docHtml($titolo,$doc,$mappa,$corpo,$a,true);
  $saldoDopo=function_exists('pvBalance')?pvBalance($a['id']):0;
  if(($_GET['fmt']??($in['fmt']??''))==='html'){ header('Content-Type: text/html; charset=utf-8'); echo $html; exit; }
  j(['ok'=>true,'html'=>$html,'salvato_id'=>$sid,'addebitati'=>$pagato?0:$costo,'saldo'=>$saldoDopo]);
}
if($az==='salva'){
  $in=json_decode(file_get_contents('php://input'),true)?:[];
  $id=$in['doc']??''; $campi=$in['campi']??[]; $tit=null;
  foreach($F['documenti'] as $d) if($d['id']===$id){ $tit=$d['titolo']; break; }
  if(!$tit) j(['ok'=>false,'err'=>'documento sconosciuto'],404);
  try{ $pdo->query('SELECT 1 FROM docs_salvati LIMIT 1'); }catch(Exception $e){
    $sq=stripos($pdo->getAttribute(PDO::ATTR_DRIVER_NAME),'sqlite')!==false;
    $pdo->exec($sq?'CREATE TABLE IF NOT EXISTS docs_salvati(id INTEGER PRIMARY KEY AUTOINCREMENT,account_id INTEGER,doc_id TEXT,titolo TEXT,campi TEXT,updated_at TEXT)':"CREATE TABLE IF NOT EXISTS docs_salvati(id BIGINT AUTO_INCREMENT PRIMARY KEY,account_id BIGINT,doc_id VARCHAR(40),titolo VARCHAR(191),campi TEXT,updated_at VARCHAR(40)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"); }
  if(!empty($in['salvato_id'])){
    $pdo->prepare('UPDATE docs_salvati SET campi=?,updated_at=? WHERE id=? AND account_id=?')->execute([json_encode($campi,JSON_UNESCAPED_UNICODE),gmdate('c'),(int)$in['salvato_id'],$a['id']]);
    j(['ok'=>true,'salvato_id'=>(int)$in['salvato_id'],'revisione'=>true]);
  }
  $pdo->prepare('INSERT INTO docs_salvati(account_id,doc_id,titolo,campi,updated_at) VALUES(?,?,?,?,?)')->execute([$a['id'],$id,$tit,json_encode($campi,JSON_UNESCAPED_UNICODE),gmdate('c')]);
  j(['ok'=>true,'salvato_id'=>(int)$pdo->lastInsertId()]);
}
if($az==='miei'){
  try{ $q=$pdo->prepare('SELECT id,doc_id,titolo,updated_at FROM docs_salvati WHERE account_id=? ORDER BY updated_at DESC LIMIT 100'); $q->execute([$a['id']]); j(['ok'=>true,'salvati'=>$q->fetchAll()]); }
  catch(Exception $e){ j(['ok'=>true,'salvati'=>[]]); }
}
if($az==='carica'){
  $q=$pdo->prepare('SELECT * FROM docs_salvati WHERE id=? AND account_id=?'); $q->execute([(int)($_GET['id']??0),$a['id']]); $r=$q->fetch();
  if(!$r) j(['ok'=>false,'err'=>'non trovato'],404);
  j(['ok'=>true,'salvato'=>['id'=>(int)$r['id'],'doc'=>$r['doc_id'],'campi'=>json_decode($r['campi']??'{}',true),'updated_at'=>$r['updated_at']]]);
}
if($az==='archivio_cancella'){
  $in=json_decode(file_get_contents('php://input'),true)?:[];
  $pdo->prepare('DELETE FROM docs_salvati WHERE id=? AND account_id=?')->execute([(int)($in['id']??0),$a['id']]);
  j(['ok'=>true]);
}
j(['ok'=>false,'err'=>'azione sconosciuta'],400);
