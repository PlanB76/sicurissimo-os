<?php
// 81+ CASSA E FATTURE, solo direzione. Aruba senza API si lavora così, qui dentro vedi ogni
// incasso, scarichi il CSV con tutti i dati per emettere su Aruba in un minuto e stampi la
// proforma per il cliente. Quando avrai un gestionale con API, esempio Fatture in Cloud o
// Aruba con API attiva, questo stesso elenco diventa la sorgente del collegamento automatico.
require_once __DIR__.'/../src/db.php';
$key=getenv('ADMIN_KEY')?:''; if($key===''||(($_GET['k']??'')!==$key)){ http_response_code(403); exit('chiave'); }
$pdo=db(); $az=$_GET['az']??'lista';
function fatIncassi($pdo){
  $out=[];
  try{ foreach($pdo->query("SELECT r.*,a.sic,a.ragione_sociale,a.piva,a.indirizzo,a.email,a.nome,a.cognome FROM pv_ricariche r JOIN accounts a ON a.id=r.account_id WHERE r.stato='completata' ORDER BY r.id DESC LIMIT 500") as $r){
    $out[]=['tipo'=>'Ricarica PV '.$r['pack'],'euro'=>(float)$r['euro'],'quando'=>$r['created_at'],'rif'=>$r['provider_ref'],'provider'=>$r['provider'],
            'sic'=>$r['sic'],'cliente'=>$r['ragione_sociale']?:trim($r['nome'].' '.$r['cognome']),'piva'=>$r['piva'],'indirizzo'=>$r['indirizzo'],'email'=>$r['email']];
  }}catch(Exception $e){}
  try{ foreach($pdo->query("SELECT e.*,a.sic,a.ragione_sociale,a.piva,a.indirizzo,a.email,a.nome,a.cognome FROM entitlements e JOIN accounts a ON a.id=e.account_id WHERE e.status='active' ORDER BY e.id DESC LIMIT 500") as $r){
    $out[]=['tipo'=>'Abbonamento '.$r['product'],'euro'=>null,'quando'=>$r['valid_until'],'rif'=>$r['provider_ref'],'provider'=>$r['provider'],
            'sic'=>$r['sic'],'cliente'=>$r['ragione_sociale']?:trim($r['nome'].' '.$r['cognome']),'piva'=>$r['piva'],'indirizzo'=>$r['indirizzo'],'email'=>$r['email']];
  }}catch(Exception $e){}
  return $out;
}
if($az==='lista'){ j(['ok'=>true,'incassi'=>fatIncassi($pdo),'nota'=>'Importi lordi dal canale di pagamento. Per gli abbonamenti l importo lo trovi sul tuo PayPal, il prodotto e il cliente sono qui. Da verificare col commercialista per l emissione.']); }
if($az==='csv'){
  header('Content-Type: text/csv; charset=utf-8'); header('Content-Disposition: attachment; filename="incassi_81plus_'.date('Ymd').'.csv"');
  $f=fopen('php://output','w'); fputs($f,"\xEF\xBB\xBF");
  fputcsv($f,['data','tipo','importo_eur','cliente','piva_cf','indirizzo','email','sic','riferimento_pagamento','canale'],';');
  foreach(fatIncassi($pdo) as $r) fputcsv($f,[$r['quando'],$r['tipo'],$r['euro']!==null?number_format($r['euro'],2,',',''):'',$r['cliente'],$r['piva'],$r['indirizzo'],$r['email'],$r['sic'],$r['rif'],$r['provider']],';');
  exit;
}
if($az==='proforma'){
  $rif=$_GET['rif']??''; $riga=null;
  foreach(fatIncassi($pdo) as $r) if($r['rif']===$rif){ $riga=$r; break; }
  if(!$riga){ http_response_code(404); exit('movimento non trovato'); }
  $e=fn($x)=>htmlspecialchars((string)$x);
  header('Content-Type: text/html; charset=utf-8');
  echo '<!DOCTYPE html><html lang="it"><head><meta charset="utf-8"><title>Proforma</title><style>body{font-family:Arial;color:#111;margin:36px;font-size:13px;line-height:1.6}h1{font-size:20px;color:#E8501A;border-bottom:3px solid #E8501A;padding-bottom:8px}table{width:100%;border-collapse:collapse;margin:14px 0}td,th{border:1px solid #999;padding:7px;text-align:left;font-size:12px}.nota{margin-top:24px;font-size:10px;color:#666;border-top:1px solid #ccc;padding-top:8px}</style></head><body>'
   .'<h1>Avviso di pagamento ricevuto, proforma</h1>'
   .'<p><b>Labo Tecnic Studio</b>, P.IVA IT01504180298, Porto Viro RO</p>'
   .'<table><tr><th>Cliente</th><td>'.$e($riga['cliente']).'</td></tr><tr><th>P.IVA o CF</th><td>'.$e($riga['piva']).'</td></tr><tr><th>Indirizzo</th><td>'.$e($riga['indirizzo']).'</td></tr><tr><th>SIC</th><td>'.$e($riga['sic']).'</td></tr></table>'
   .'<table><tr><th>Descrizione</th><th>Importo</th><th>Data</th><th>Riferimento</th></tr><tr><td>'.$e($riga['tipo']).'</td><td>'.($riga['euro']!==null?number_format($riga['euro'],2,',','.').' € lordi':'vedi PayPal').'</td><td>'.$e($riga['quando']).'</td><td>'.$e($riga['rif']).'</td></tr></table>'
   .'<div class="nota">Documento non fiscale. La fattura elettronica viene emessa tramite il canale di fatturazione della direzione e recapitata via SDI. Da verificare col commercialista.</div>'
   .'<script>window.print()</script></body></html>';
  exit;
}
j(['ok'=>false,'err'=>'azione sconosciuta'],400);
