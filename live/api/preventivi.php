<?php
// 81+ PREVENTIVI E CONTRATTI. Ogni ATECO carica il suo listino e genera
// preventivi, contratti, subappalti e dossier gara brandizzati coi suoi dati.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php'; require_once __DIR__.'/../src/wallet81.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
$pdo=db();
function pvTabelle($pdo){
  $sq=stripos($pdo->getAttribute(PDO::ATTR_DRIVER_NAME),'sqlite')!==false;
  $idc=$sq?'INTEGER PRIMARY KEY AUTOINCREMENT':'BIGINT AUTO_INCREMENT PRIMARY KEY'; $eng=$sq?'':' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
  try{$pdo->query('SELECT 1 FROM docs_salvati LIMIT 1');}catch(Exception $e){$pdo->exec("CREATE TABLE IF NOT EXISTS docs_salvati(id $idc,account_id BIGINT NOT NULL,doc_id VARCHAR(40),titolo VARCHAR(191),campi TEXT,updated_at VARCHAR(40))$eng");}
  try{$pdo->query('SELECT 1 FROM listino_voci LIMIT 1');}catch(Exception $e){$pdo->exec("CREATE TABLE IF NOT EXISTS listino_voci(id $idc,account_id BIGINT NOT NULL,voce VARCHAR(191),descrizione TEXT,unita VARCHAR(30),prezzo DECIMAL(12,2) DEFAULT 0,iva INT DEFAULT 22,updated_at VARCHAR(40))$eng");}
  try{$pdo->query('SELECT 1 FROM preventivi LIMIT 1');}catch(Exception $e){$pdo->exec("CREATE TABLE IF NOT EXISTS preventivi(id $idc,account_id BIGINT NOT NULL,tipo VARCHAR(20) DEFAULT 'preventivo',numero VARCHAR(30),cliente TEXT,righe TEXT,condizioni TEXT,extra TEXT,totale DECIMAL(12,2) DEFAULT 0,stato VARCHAR(20) DEFAULT 'bozza',updated_at VARCHAR(40))$eng");}
  foreach(['brand_logo VARCHAR(255)','brand_colore VARCHAR(10)','brand_contatti VARCHAR(255)'] as $c){ try{$pdo->exec("ALTER TABLE accounts ADD COLUMN $c NULL");}catch(Exception $e){} }
}
pvTabelle($pdo);
$az=$_GET['az']??''; $in=json_decode(file_get_contents('php://input'),true)?:$_POST;

// ===== BRAND =====
if($az==='brand_salva'){
  $logo=trim($in['brand_logo']??''); $col=trim($in['brand_colore']??''); $cont=trim($in['brand_contatti']??'');
  if($logo!=='' && !preg_match('#^https://[^\s"]+$#',$logo)) j(['ok'=>false,'err'=>'il logo deve essere un indirizzo https']);
  if($col!=='' && !preg_match('/^#[0-9A-Fa-f]{6}$/',$col)) $col='#E8501A';
  $pdo->prepare('UPDATE accounts SET brand_logo=?,brand_colore=?,brand_contatti=? WHERE id=?')->execute([$logo?:null,$col?:null,substr($cont,0,250)?:null,$a['id']]);
  j(['ok'=>true]);
}

// ===== LISTINO =====
if($az==='listino'){ $q=$pdo->prepare('SELECT * FROM listino_voci WHERE account_id=? ORDER BY voce'); $q->execute([$a['id']]); j(['ok'=>true,'voci'=>$q->fetchAll(),'brand'=>['logo'=>$a['brand_logo']??'','colore'=>$a['brand_colore']??'#E8501A','contatti'=>$a['brand_contatti']??'']]); }
if($az==='listino_salva'){
  $voce=trim($in['voce']??''); if($voce==='') j(['ok'=>false,'err'=>'manca il nome della voce']);
  $pr=(float)($in['prezzo']??0); $iva=max(0,min(22,(int)($in['iva']??22)));
  if(!empty($in['id'])){
    $pdo->prepare('UPDATE listino_voci SET voce=?,descrizione=?,unita=?,prezzo=?,iva=?,updated_at=? WHERE id=? AND account_id=?')
        ->execute([$voce,trim($in['descrizione']??''),trim($in['unita']??''),$pr,$iva,gmdate('c'),(int)$in['id'],$a['id']]);
  } else {
    $pdo->prepare('INSERT INTO listino_voci(account_id,voce,descrizione,unita,prezzo,iva,updated_at) VALUES(?,?,?,?,?,?,?)')
        ->execute([$a['id'],$voce,trim($in['descrizione']??''),trim($in['unita']??''),$pr,$iva,gmdate('c')]);
  }
  j(['ok'=>true]);
}
if($az==='listino_cancella'){ $pdo->prepare('DELETE FROM listino_voci WHERE id=? AND account_id=?')->execute([(int)($in['id']??0),$a['id']]); j(['ok'=>true]); }
if($az==='listino_modello'){
  $m=$in['modello']??'';
  if($m==='auto'){
    $ateco=(string)($a['ateco']??''); $m='professionista';
    $mappa=['41'=>'edile','42'=>'edile','43'=>'edile','45'=>'officina','49'=>'trasporti','10'=>'alimentare','56'=>'catering','47.2'=>'alimentare','96'=>'benessere'];
    foreach($mappa as $pre=>$pk){ if(strpos($ateco,$pre)===0){ $m=$pk; break; } }
  }
  $mod=['catering'=>[['Menu servito 3 portate','Antipasto, primo, secondo con contorno, acqua e caffè inclusi','persona',38,10],['Buffet aperitivo','Finger food, 8 pezzi a persona, allestimento incluso','persona',18,10],['Servizio camerieri','Personale di sala in divisa','ora/persona',22,22],['Torta personalizzata','Su progetto del cliente','kg',28,10],['Noleggio attrezzatura','Tavoli, sedie, tovagliato','forfait',150,22]],
       'edile'=>[['Manodopera operaio specializzato','','ora',32,22],['Manodopera operaio comune','','ora',26,22],['Demolizione tramezzi','Inclusi calo in basso e trasporto a discarica','mq',28,22],['Muratura in laterizio','Spessore 8 cm, intonaco escluso','mq',45,22],['Intonaco civile','Premiscelato, due mani','mq',22,22],['Pavimentazione gres','Posa su massetto esistente, materiale escluso','mq',30,22],['Nolo ponteggio','Montaggio, smontaggio e nolo primo mese','mq',14,22]],
       'professionista'=>[['Consulenza in studio','','ora',80,22],['Consulenza presso il cliente','Trasferta entro 30 km inclusa','ora',100,22],['Redazione pratica ordinaria','','cadauna',250,22],['Canone assistenza mensile','Fino a 4 ore comprese','mese',300,22]],
       'officina'=>[['Manodopera meccanico','','ora',45,22],['Tagliando completo','Olio e filtri inclusi, ricambi originali a parte','cadauno',180,22],['Cambio gomme stagionale','Equilibratura inclusa','treno',60,22],['Diagnosi elettronica','','cadauna',50,22],['Revisione pre collaudo','Controllo completo prima della revisione ministeriale','cadauna',45,22],['Ricarica clima','Gas e controllo perdite','cadauna',80,22]],
       'trasporti'=>[['Trasporto dedicato furgone','Fino a 35 quintali','km',1.20,22],['Trasporto dedicato motrice','','km',1.80,22],['Fermo carico scarico','Oltre la prima ora','ora',35,22],['Facchinaggio','Per addetto','ora',28,22],['Trasloco appartamento','Squadra due persone più mezzo','giorno',650,22]],
       'alimentare'=>[['Pasto completo mensa','Primo, secondo, contorno, pane e acqua','pasto',9.50,4],['Cestino monoporzione','','cadauno',7,10],['Fornitura pane quotidiana','','kg',4.20,4],['Torta su ordinazione','','kg',26,10]],
       'benessere'=>[['Trattamento viso','60 minuti','seduta',55,22],['Massaggio rilassante','50 minuti','seduta',60,22],['Manicure completa','','seduta',25,22],['Percorso 5 sedute','Pacchetto con quinta seduta in omaggio','pacchetto',220,22]]];
  if(!isset($mod[$m])) j(['ok'=>false,'err'=>'modello sconosciuto']);
  $st=$pdo->prepare('INSERT INTO listino_voci(account_id,voce,descrizione,unita,prezzo,iva,updated_at) VALUES(?,?,?,?,?,?,?)');
  foreach($mod[$m] as $v) $st->execute([$a['id'],$v[0],$v[1],$v[2],$v[3],$v[4],gmdate('c')]);
  j(['ok'=>true,'aggiunte'=>count($mod[$m])]);
}

// ===== PREVENTIVI, CONTRATTI, SUBAPPALTI, GARE =====
if($az==='miei'){ $q=$pdo->prepare('SELECT id,tipo,numero,stato,totale,updated_at,cliente FROM preventivi WHERE account_id=? ORDER BY id DESC LIMIT 100'); $q->execute([$a['id']]);
  $r=array_map(function($x){ $c=json_decode($x['cliente']??'{}',true); $x['cliente_nome']=$c['nome']??''; unset($x['cliente']); return $x; },$q->fetchAll()); j(['ok'=>true,'documenti'=>$r]); }
if($az==='carica'){ $q=$pdo->prepare('SELECT * FROM preventivi WHERE id=? AND account_id=?'); $q->execute([(int)($_GET['id']??0),$a['id']]); $r=$q->fetch();
  if(!$r) j(['ok'=>false,'err'=>'non trovato'],404);
  $r['cliente']=json_decode($r['cliente']??'{}',true); $r['righe']=json_decode($r['righe']??'[]',true); $r['extra']=json_decode($r['extra']??'{}',true);
  j(['ok'=>true,'doc'=>$r]); }
if($az==='salva'){
  $tipo=in_array($in['tipo']??'',['preventivo','contratto','subappalto','gara'])?$in['tipo']:'preventivo';
  $righe=is_array($in['righe']??null)?$in['righe']:[];
  $tot=0; foreach($righe as $r){ $imp=(float)($r['qta']??0)*(float)($r['prezzo']??0); $imp-=$imp*((float)($r['sconto']??0)/100); $tot+=$imp*(1+((float)($r['iva']??0)/100)); }
  $stato=in_array($in['stato']??'',['bozza','inviato','accettato','rifiutato'])?$in['stato']:'bozza';
  if(!empty($in['id'])){
    $pdo->prepare('UPDATE preventivi SET tipo=?,cliente=?,righe=?,condizioni=?,extra=?,totale=?,stato=?,updated_at=? WHERE id=? AND account_id=?')
        ->execute([$tipo,json_encode($in['cliente']??[],JSON_UNESCAPED_UNICODE),json_encode($righe,JSON_UNESCAPED_UNICODE),trim($in['condizioni']??''),json_encode($in['extra']??[],JSON_UNESCAPED_UNICODE),round($tot,2),$stato,gmdate('c'),(int)$in['id'],$a['id']]);
    $id=(int)$in['id'];
  } else {
    if(!rateOk('prevcrea:'.$a['id'],60,3600)) j(['ok'=>false,'err'=>'troppe creazioni'],429);
    $anno=date('Y'); $c=$pdo->prepare("SELECT COUNT(*) c FROM preventivi WHERE account_id=? AND numero LIKE ?"); $c->execute([$a['id'],'%-'.$anno.'-%']);
    $num=strtoupper(substr($tipo,0,3)).'-'.$anno.'-'.str_pad(((int)$c->fetch()['c'])+1,3,'0',STR_PAD_LEFT);
    $pdo->prepare('INSERT INTO preventivi(account_id,tipo,numero,cliente,righe,condizioni,extra,totale,stato,updated_at) VALUES(?,?,?,?,?,?,?,?,?,?)')
        ->execute([$a['id'],$tipo,$num,json_encode($in['cliente']??[],JSON_UNESCAPED_UNICODE),json_encode($righe,JSON_UNESCAPED_UNICODE),trim($in['condizioni']??''),json_encode($in['extra']??[],JSON_UNESCAPED_UNICODE),round($tot,2),$stato,gmdate('c')]);
    $id=(int)$pdo->lastInsertId();
    try{ $c2=$pdo->prepare("SELECT 1 FROM pv_ledger WHERE account_id=? AND reason='preventivo_creato'"); $c2->execute([$a['id']]);
         if(!$c2->fetch() && function_exists('pvAdd')) pvAdd($a['id'],15,'preventivo_creato'); }catch(Exception $e){}
    if(function_exists('ev')) @ev('preventivo_creato',$a['id'],['tipo'=>$tipo]);
  }
  j(['ok'=>true,'id'=>$id]);
}
if($az==='cancella'){ $pdo->prepare('DELETE FROM preventivi WHERE id=? AND account_id=?')->execute([(int)($in['id']??0),$a['id']]); j(['ok'=>true]); }

// ===== GENERAZIONE DOCUMENTO BRANDIZZATO =====
if($az==='genera'){
  $q=$pdo->prepare('SELECT * FROM preventivi WHERE id=? AND account_id=?'); $q->execute([(int)($_GET['id']??($in['id']??0)),$a['id']]); $P=$q->fetch();
  if(!$P) j(['ok'=>false,'err'=>'non trovato'],404);
  $cli=json_decode($P['cliente']??'{}',true); $righe=json_decode($P['righe']??'[]',true); $ex=json_decode($P['extra']??'{}',true);
  $soloAnteprima=!empty($_GET['anteprima']);
  // un documento, un addebito. Le revisioni della stessa pratica restano comprese. L'anteprima è libera, con filigrana.
  if(!$soloAnteprima && empty($ex['pv_pagato'])){
    $LP=json_decode(@file_get_contents(__DIR__.'/../data/prezzi_pv.json'),true)?:[];
    $costo=(int)($LP['preventivi'][$P['tipo']]??5); if(function_exists('wBundleAttivo') && wBundleAttivo($pdo,$a['id'],'studio')) $costo=0;
    $saldo=function_exists('pvBalance')?pvBalance($a['id']):0;
    if($saldo<$costo) j(['ok'=>false,'err'=>'pv_insufficienti','costo'=>$costo,'saldo'=>$saldo],402);
    if($costo>0 && function_exists('pvAdd')) pvAdd($a['id'],-$costo,'doc_prev_'.$P['id']);
    $ex['pv_pagato']=1;
    $pdo->prepare('UPDATE preventivi SET extra=? WHERE id=?')->execute([json_encode($ex,JSON_UNESCAPED_UNICODE),$P['id']]);
  }
  $col=preg_match('/^#[0-9A-Fa-f]{6}$/',$a['brand_colore']??'')?$a['brand_colore']:'#E8501A';
  $rs=htmlspecialchars($a['ragione_sociale']?:trim(($a['nome']??'').' '.($a['cognome']??'')));
  $logo=preg_match('#^https://#',$a['brand_logo']??'')?'<img src="'.htmlspecialchars($a['brand_logo']).'" style="max-height:64px;max-width:200px" alt="logo">':'<div style="font-size:24px;font-weight:700;color:'.$col.'">'.$rs.'</div>';
  $e=fn($x)=>htmlspecialchars((string)$x); $eb=fn($x)=>nl2br(htmlspecialchars((string)$x));
  $oggi=(new DateTime('now',new DateTimeZone('Europe/Rome')))->format('d/m/Y');
  $imponibile=0;$ivaTot=0;$corpo='';
  foreach($righe as $r){
    $imp=(float)($r['qta']??0)*(float)($r['prezzo']??0); $sc=(float)($r['sconto']??0); $imp-=$imp*$sc/100;
    $ivp=(float)($r['iva']??0); $imponibile+=$imp; $ivaTot+=$imp*$ivp/100;
    $corpo.='<tr><td>'.$e($r['voce']??'').($r['descrizione']??''?'<div class="dsc">'.$eb($r['descrizione']).'</div>':'').'</td><td class="c">'.$e($r['qta']??'').' '.$e($r['unita']??'').'</td><td class="r">'.number_format((float)($r['prezzo']??0),2,',','.').'</td><td class="c">'.($sc?$e($sc).'%':'-').'</td><td class="c">'.$e($ivp).'%</td><td class="r">'.number_format($imp,2,',','.').'</td></tr>';
  }
  $tot=$imponibile+$ivaTot;
  $tipi=['preventivo'=>'Preventivo','contratto'=>'Contratto','subappalto'=>'Contratto di subappalto','gara'=>'Dossier gara'];
  $tit=$tipi[$P['tipo']]??'Documento';
  $testaCli='<table class="due"><tr><td><b>Da</b><br>'.$rs.'<br>'.$e($a['indirizzo']??'').'<br>P.IVA '.$e($a['piva']??'').'<br>'.$e($a['brand_contatti']??$a['email']).'</td><td><b>'.($P['tipo']==='subappalto'?'Subappaltatore':'Spett.le').'</b><br>'.$e($cli['nome']??'').'<br>'.$eb($cli['indirizzo']??'').'<br>'.($cli['piva']??''?'P.IVA '.$e($cli['piva']):'').'</td></tr></table>';
  $tabella='<table class="tb"><tr><th>Voce</th><th>Quantità</th><th>Prezzo €</th><th>Sconto</th><th>IVA</th><th>Importo €</th></tr>'.$corpo.'</table>
  <table class="tot"><tr><td>Imponibile</td><td class="r">'.number_format($imponibile,2,',','.').' €</td></tr><tr><td>IVA</td><td class="r">'.number_format($ivaTot,2,',','.').' €</td></tr><tr><td><b>Totale</b></td><td class="r"><b>'.number_format($tot,2,',','.').' €</b></td></tr></table>';
  $mezzo='';
  if($P['tipo']==='preventivo'){
    $mezzo=$testaCli.'<p>Con la presente siamo lieti di sottoporvi la nostra migliore offerta.</p>'.$tabella
      .'<h2>Condizioni</h2><p>'.$eb($P['condizioni']?:'Validità dell\'offerta 30 giorni. Pagamento come da accordi. Esclusioni e varianti saranno quotate a parte.').'</p>'
      .'<table class="due firme"><tr><td>'.$rs.'<br><br>_______________________</td><td>Per accettazione, il cliente<br><br>_______________________</td></tr></table>';
  }
  if($P['tipo']==='contratto'){
    $mezzo=$testaCli.'<h2>Oggetto del contratto</h2><p>'.$eb($ex['oggetto']??'Le prestazioni dettagliate nella tabella che segue.').'</p>'.$tabella
      .'<h2>Tempi di esecuzione</h2><p>'.$eb($ex['tempi']??'[DA COMPLETARE, inizio e durata delle prestazioni]').'</p>'
      .'<h2>Pagamenti</h2><p>'.$eb($ex['pagamenti']??'[DA COMPLETARE, acconto, stati di avanzamento, saldo]').'</p>'
      .'<h2>Condizioni generali</h2><p>'.$eb($P['condizioni']?:'Le parti si impegnano alla riservatezza. Per quanto non previsto si rinvia al Codice civile. Foro competente quello della sede del fornitore, salvo diversa norma inderogabile.').'</p>'
      .'<table class="due firme"><tr><td>Il fornitore<br>'.$rs.'<br><br>_______________________</td><td>Il committente<br>'.$e($cli['nome']??'').'<br><br>_______________________</td></tr></table>';
  }
  if($P['tipo']==='subappalto'){
    $mezzo=$testaCli.'<h2>Lavori affidati in subappalto</h2><p>'.$eb($ex['oggetto']??'I lavori dettagliati nella tabella che segue, riferiti al cantiere di '.($ex['cantiere']??'[cantiere]').'.').'</p>'.$tabella
      .'<h2>Sicurezza</h2><p>Il subappaltatore si obbliga a osservare il D.Lgs 81/08, a redigere il proprio POS e a coordinarsi con il PSC ove presente. La documentazione di idoneità tecnico professionale è allegata, art. 26 e Allegato XVII.</p>'
      .'<h2>Autorizzazione</h2><p>Per i contratti pubblici il subappalto è soggetto alla disciplina e all\'autorizzazione della stazione appaltante secondo il codice dei contratti vigente. [DA VERIFICARE con il contratto principale e il bando]</p>'
      .'<h2>Pagamenti e condizioni</h2><p>'.$eb($P['condizioni']?:'[DA COMPLETARE, termini di pagamento, ritenute di garanzia, oneri della sicurezza non soggetti a ribasso]').'</p>'
      .'<table class="due firme"><tr><td>L\'appaltatore<br>'.$rs.'<br><br>_______________________</td><td>Il subappaltatore<br>'.$e($cli['nome']??'').'<br><br>_______________________</td></tr></table>';
  }
  if($P['tipo']==='gara'){
    $mezzo='<table class="tb"><tr><td>Stazione appaltante</td><td>'.$e($cli['nome']??'').'</td></tr><tr><td>Oggetto della gara</td><td>'.$eb($ex['oggetto']??'').'</td></tr><tr><td>CIG</td><td>'.$e($ex['cig']??'[DA COMPLETARE]').'</td></tr><tr><td>Scadenza presentazione</td><td>'.$e($ex['scadenza']??'[DA COMPLETARE]').'</td></tr><tr><td>Importo a base di gara</td><td>'.$e($ex['base']??'[DA COMPLETARE]').'</td></tr></table>'
      .'<h2>Checklist documenti tipici</h2><table class="tb"><tr><th>Documento</th><th>Pronto</th><th>Note</th></tr>'
      .implode('',array_map(fn($d)=>'<tr><td>'.$d.'</td><td class="c">☐</td><td></td></tr>',['Domanda di partecipazione','DGUE, documento di gara unico europeo','Dichiarazioni sui requisiti generali','Requisiti di idoneità professionale, visura e iscrizioni','Capacità economico finanziaria richiesta dal bando','Capacità tecnica, lavori o servizi analoghi','Garanzia provvisoria','PASSOE e contributo ANAC ove dovuti','Offerta tecnica secondo i criteri del bando','Offerta economica con costi della manodopera e oneri sicurezza aziendali']))
      .'</table><p>[DA VERIFICARE sempre sul bando e sul disciplinare, che prevalgono su qualunque elenco. La nostra offerta economica è riportata di seguito.]</p>'.$tabella
      .'<h2>Note interne</h2><p>'.$eb($P['condizioni']?:'').'</p>';
  }
  $html='<!DOCTYPE html><html lang="it"><head><meta charset="utf-8"><title>'.$e($tit.' '.$P['numero']).'</title><style>
body{font-family:"DM Sans",Arial,Helvetica,sans-serif;color:#111;margin:34px;font-size:13px;line-height:1.55}
.testa{display:flex;justify-content:space-between;align-items:center;border-bottom:3px solid '.$col.';padding-bottom:10px;margin-bottom:6px}
.testa .num{text-align:right;font-size:12px;color:#444}
h1{font-size:20px;color:'.$col.';margin:10px 0 2px} h2{font-size:14.5px;color:'.$col.';margin:18px 0 6px}
table.due{width:100%;border-collapse:collapse;margin:12px 0} table.due td{width:50%;vertical-align:top;padding:8px;border:1px solid #ddd;font-size:12px}
table.tb{width:100%;border-collapse:collapse;margin:10px 0} table.tb td,table.tb th{border:1px solid #999;padding:6px 8px;font-size:12px;text-align:left;vertical-align:top} table.tb th{background:#f3f3f3}
table.tot{border-collapse:collapse;margin-left:auto;min-width:260px} table.tot td{border:1px solid #999;padding:6px 10px;font-size:12.5px}
.r{text-align:right}.c{text-align:center}.dsc{color:#555;font-size:11px}
.firme td{border:0!important;padding-top:30px;text-align:center}
.nota{margin-top:26px;border-top:1px solid #ccc;padding-top:8px;font-size:10px;color:#666}
body.anteprima::after{content:"ANTEPRIMA 81+ , NON VALIDA";position:fixed;top:42%;left:4%;right:4%;text-align:center;font-size:46px;color:rgba(232,80,26,0.16);transform:rotate(-18deg);font-weight:700;letter-spacing:3px;pointer-events:none}
</style></head><body class="'.($soloAnteprima?'anteprima':'').'">
<div class="testa"><div>'.$logo.'</div><div class="num"><b>'.$e($tit).' n. '.$e($P['numero']).'</b><br>Data, '.$oggi.'<br>Stato, '.$e($P['stato']).'</div></div>
<h1>'.$e($tit).'</h1>'.$mezzo.'
<div class="nota">Documento generato con l\'ecosistema 81+ da '.$rs.'. '.($P['tipo']==='gara'||$P['tipo']==='subappalto'?'Le parti tra parentesi quadre vanno completate e verificate sul bando o sul contratto principale, che prevalgono. ':'').'Documento non fiscale, la fattura segue a parte ove dovuta.</div>
</body></html>';
  if(($_GET['fmt']??'')==='html'){ header('Content-Type: text/html; charset=utf-8'); echo $html; exit; }
  j(['ok'=>true,'html'=>$html,'numero'=>$P['numero']]);
}
j(['ok'=>false,'err'=>'azione sconosciuta'],400);
