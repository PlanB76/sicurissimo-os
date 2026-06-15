<?php
// Esporta i dati dell’ecosistema per il foglio Google di controllo.
// Protetta da chiave: /api/export.php?key=EXPORT_KEY&what=all
// La lista unica vive QUI su MySQL. Il foglio è solo uno specchio di lettura.
require_once __DIR__.'/../src/db.php';
$key=getenv('EXPORT_KEY')?:''; if(!$key||($_GET['key']??'')!==$key){ http_response_code(403); exit('no'); }
$pdo=db(); $what=$_GET['what']??'all';
function rows($pdo,$sql,$par=[]){ try{ $st=$pdo->prepare($sql); $st->execute($par); return $st->fetchAll(); }catch(Throwable $e){ return []; } }
$D=[];
if($what==='all'||$what==='kpi'){
  $D['kpi']=[
    'account_totali'=>(int)(rows($pdo,'SELECT COUNT(*) c FROM accounts')[0]['c']??0),
    'account_attivi'=>(int)(rows($pdo,"SELECT COUNT(*) c FROM accounts WHERE status='attivo'")[0]['c']??0),
    'lead_totali'=>(int)(rows($pdo,'SELECT COUNT(*) c FROM leads')[0]['c']??0),
    'newsletter_attivi'=>(int)(rows($pdo,"SELECT COUNT(*) c FROM newsletter WHERE stato='ATTIVO'")[0]['c']??0),
    'welcome_in_corso'=>(int)(rows($pdo,"SELECT COUNT(*) c FROM welcome_queue WHERE stato='ATTIVO'")[0]['c']??0),
    'pv_in_circolazione'=>(int)(rows($pdo,'SELECT COALESCE(SUM(delta),0) s FROM pv_ledger')[0]['s']??0),
    'early_bird_ciclo'=>gmdate('Y-m'),
    'early_bird_presi'=>(int)(rows($pdo,'SELECT COUNT(*) c FROM promo_early_bird WHERE cycle=?',[gmdate('Y-m')])[0]['c']??0),
    'vendite_registrate'=>(int)(rows($pdo,'SELECT COUNT(*) c FROM sales')[0]['c']??0),
    'aggiornato'=>gmdate('c')
  ];
}
if($what==='all'||$what==='accounts') $D['accounts']=rows($pdo,'SELECT sic,email,tipo,nome,cognome,status,email_verified,ref_by,codice_fiscale,eth_address,avatar_url,created_at FROM accounts ORDER BY id DESC LIMIT 5000');
if($what==='all'||$what==='leads') $D['leads']=rows($pdo,'SELECT email,nome,source,owner_sic,stage,created_at FROM leads ORDER BY id DESC LIMIT 5000');
if($what==='all'||$what==='newsletter') $D['newsletter']=rows($pdo,'SELECT email,nome,fonte,consenso,stato,created_at FROM newsletter ORDER BY id DESC LIMIT 5000');
if($what==='all'||$what==='welcome') $D['welcome']=rows($pdo,'SELECT email,nome,step,next_at,stato,created_at FROM welcome_queue ORDER BY id DESC LIMIT 5000');
if($what==='all'||$what==='pv'){
  $D['pv_saldi']=rows($pdo,'SELECT a.sic,a.email,COALESCE(SUM(l.delta),0) pv FROM accounts a LEFT JOIN pv_ledger l ON l.account_id=a.id GROUP BY a.id ORDER BY pv DESC LIMIT 5000');
  $D['pv_movimenti']=rows($pdo,'SELECT a.sic,l.delta,l.reason,l.ref,l.created_at FROM pv_ledger l JOIN accounts a ON a.id=l.account_id ORDER BY l.id DESC LIMIT 2000');
}
if($what==='all'||$what==='promo') $D['promo']=rows($pdo,'SELECT p.cycle,a.sic,a.email,p.created_at FROM promo_early_bird p JOIN accounts a ON a.id=p.account_id ORDER BY p.id DESC LIMIT 2000');
if($what==='all'||$what==='rete') $D['rete']=rows($pdo,'SELECT a.sic,a.ref_by,n.rank,n.qualified,n.vol_personal,n.vol_group,n.updated_at FROM network_nodes n JOIN accounts a ON a.id=n.account_id ORDER BY n.vol_group DESC LIMIT 5000');
j(['ok'=>true,'dati'=>$D]);
