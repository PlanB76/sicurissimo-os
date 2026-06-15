<?php
// 81+ GREENGROVE. Foresta reale Treedom. Zero alberi finti.
// Un albero ogni 5.000 PV community, confermati a zero finché Mirco non conferma Treedom.
require_once __DIR__.'/../src/db.php';
$pdo=db(); $az=$_GET['azione']??'';
define('PV_PER_ALBERO',5000);

if($az==='stato'){
  // PV totali reali: somma dei soli accrediti positivi dal pv_ledger
  $pvTot=0;
  try{ $pvTot=(int)$pdo->query("SELECT COALESCE(SUM(amount),0) s FROM pv_ledger WHERE amount>0")->fetch()['s']; }catch(Throwable $e){}
  // alberi confermati: per ora 0, sarà la colonna greengrove_confermati nella tabella config o da API Treedom
  $alberi=0;
  try{
    $q=$pdo->query("SELECT value FROM config WHERE key='greengrove_alberi'"); $r=$q->fetch();
    if($r) $alberi=(int)$r['value'];
  }catch(Throwable $e){}
  $co2Stima=$alberi*200; // stima media dichiarata 200kg CO2 per albero
  // utenti attivi ultimi 30 giorni
  $attivi=0;
  try{ $attivi=(int)$pdo->query("SELECT COUNT(DISTINCT account_id) c FROM events WHERE created_at>=datetime('now','-30 days')")->fetch()['c']; }catch(Throwable $e){}
  // classifica top PV
  $classifica=[];
  try{
    $q=$pdo->query("SELECT a.nome,a.cognome,COALESCE(SUM(p.amount),0) pv FROM pv_ledger p JOIN accounts a ON a.id=p.account_id WHERE p.amount>0 GROUP BY p.account_id ORDER BY pv DESC LIMIT 10");
    foreach($q->fetchAll(PDO::FETCH_ASSOC) as $r){
      $nome=trim(($r['nome']??'').($r['cognome']?' '.substr($r['cognome'],0,1).'.':''));
      if(!$nome)$nome='Membro';
      $classifica[]=['nome'=>$nome,'pv'=>(int)$r['pv']];
    }
  }catch(Throwable $e){}
  $verso=$pvTot%PV_PER_ALBERO; $pct=$pvTot>0?round($verso/PV_PER_ALBERO*100,1):0;
  j(['ok'=>true,'alberi_confermati'=>$alberi,'co2_stima_kg'=>$co2Stima,'utenti_attivi_30gg'=>$attivi,'pv_totali'=>$pvTot,
     'pv_per_albero'=>PV_PER_ALBERO,'verso_prossimo_albero'=>$verso,'percentuale_prossimo'=>$pct,'classifica'=>$classifica,
     'nota'=>'Alberi confermati a zero finché la direzione non conferma la piantumazione Treedom. Numeri onesti.']);
}
j(['ok'=>false,'err'=>'azione sconosciuta'],404);
