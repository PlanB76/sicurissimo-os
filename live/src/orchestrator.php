<?php
// ORCHESTRATORE 81+. Motore a regole con moduli specializzati che votano.
// Deterministico e auditabile. Niente decisioni nascoste, niente premi arbitrari.
// Ogni modulo guarda lo stato reale e propone una azione con punteggio.
// Vince la proposta col punteggio più alto. Tutto viene registrato in ai_decisions.

function orchestratorStato($pdo,$a){
  $id=$a['id'];
  $aud=$pdo->prepare('SELECT score,created_at FROM audit_runs WHERE account_id=? ORDER BY id DESC LIMIT 1'); $aud->execute([$id]); $aud=$aud->fetch();
  $ult=$pdo->prepare("SELECT MAX(created_at) m FROM events WHERE account_id=? AND type='login'"); $ult->execute([$id]); $ultimo=$ult->fetch()['m']??null;
  $inv=$pdo->prepare("SELECT COUNT(*) c FROM pv_ledger WHERE account_id=? AND reason LIKE 'invito_attivo_%'"); $inv->execute([$id]); $invAtt=(int)$inv->fetch()['c'];
  $sc=$pdo->prepare("SELECT COUNT(*) c FROM scadenze WHERE account_id=? AND stato='aperta' AND scade_il<=?"); 
  try{ $sc->execute([$id,gmdate('Y-m-d',time()+30*86400)]); $scad30=(int)$sc->fetch()['c']; }catch(Exception $e){ $scad30=0; }
  return [
    'profilo_ok'=>profiloCompleto($a),
    'kyc'=>(int)($a['kyc_level']??0),
    'audit'=>$aud?:null,
    'giorni_inattivo'=>$ultimo?intval((time()-strtotime($ultimo))/86400):999,
    'inviti_attivi'=>$invAtt,
    'scadenze_30gg'=>$scad30,
    'pv'=>pvBalance($id),
    'haccp'=>(function() use ($a){ $s=function_exists('settoreDaAteco')?settoreDaAteco($a['ateco']??''):null; return $s?($s['haccp']??false):false; })()
  ];
}

// Ogni modulo: nome, voto -10..+10, proposta. Regole pubbliche, niente magia.
function orchestratorModuli($st){
  $p=[];
  // COMPLIANCE
  if(!$st['profilo_ok']) $p[]=['m'=>'compliance','score'=>9,'azione'=>'Completa il profilo','perché'=>'Senza profilo completo il quadro di conformità resta a meta','link'=>'profilo.html','pv'=>1000,'problema'=>'Profilo incompleto'];
  if(!$st['audit'])      $p[]=['m'=>'compliance','score'=>8,'azione'=>'Fai l’audit gratuito','perché'=>'In due minuti fotografi la tua posizione sulle 30 normative','link'=>'audit.html','pv'=>10,'problema'=>'Nessun audit eseguito'];
  elseif((int)$st['audit']['score']<70) $p[]=['m'=>'compliance','score'=>7,'azione'=>'Sistema i punti critici dell’audit','perché'=>'Punteggio audit '.$st['audit']['score'].', sotto la soglia di sicurezza','link'=>'https://wa.me/393388771737','pv'=>0,'problema'=>'Audit sotto 70'];
  // SCADENZE
  if($st['scadenze_30gg']>0) $p[]=['m'=>'scadenze','score'=>10,'azione'=>'Gestisci le scadenze in arrivo','perché'=>'Hai '.$st['scadenze_30gg'].' scadenze entro trenta giorni, il rischio sanzione si previene prima','link'=>'dashboard.html','pv'=>0,'problema'=>'Scadenze entro 30 giorni'];
  // TRUST
  if($st['kyc']<1) $p[]=['m'=>'trust','score'=>5,'azione'=>'Avvia la verifica KYC','perché'=>'Alza il Trust Score e prepara l operativita completa','link'=>'kyc.html','pv'=>0,'problema'=>'KYC non avviato'];
  // NETWORK
  if($st['inviti_attivi']<1) $p[]=['m'=>'network','score'=>4,'azione'=>'Invita la prima azienda','perché'=>'La rete parte da uno, il tuo link e nel profilo','link'=>'profilo.html','pv'=>0,'problema'=>'Rete ferma'];
  elseif($st['inviti_attivi']<5) $p[]=['m'=>'network','score'=>3,'azione'=>'Porta la rete a cinque inviti attivi','perché'=>'A cinque attivi scatta il badge Evangelista con 500 PV','link'=>'premi.html','pv'=>0,'problema'=>null];
  // RIATTIVAZIONE
  if($st['giorni_inattivo']>=15 && $st['giorni_inattivo']<999) $p[]=['m'=>'riattivazione','score'=>6,'azione'=>'Bentornato, riprendi da qui','perché'=>'Sei mancato '.$st['giorni_inattivo'].' giorni, ti aspetta un bonus di rientro','link'=>'dashboard.html','pv'=>50,'problema'=>'Inattivita prolungata'];
  // FORMAZIONE
  if($st['haccp']) $p[]=['m'=>'formazione','score'=>5,'azione'=>'Verifica la formazione HACCP del team','perché'=>'Il tuo settore richiede l autocontrollo alimentare, gli attestati vanno tenuti vivi','link'=>'academy.html','pv'=>0,'problema'=>null];
  // DEFAULT
  $p[]=['m'=>'crescita','score'=>1,'azione'=>'Esplora l’Academy','perché'=>'Scegli il prossimo corso utile alla tua azienda','link'=>'academy.html','pv'=>0,'problema'=>null];
  return $p;
}

function orchestratorDecidi($pdo,$a){
  $st=orchestratorStato($pdo,$a);
  $prop=orchestratorModuli($st);
  usort($prop,function($x,$y){ return $y['score']<=>$x['score']; });
  $top=$prop[0];
  $voti=array_map(function($x){ return $x['m'].':'.$x['score']; },array_slice($prop,0,5));
  // bonus rientro: una volta ogni 30 giorni, accreditato subito al ritorno, regola pubblica
  $premiato=0;
  if($top['m']==='riattivazione' && $top['pv']>0){
    $chiave='rientro_'.gmdate('Ym');
    $c=$pdo->prepare('SELECT 1 FROM pv_ledger WHERE account_id=? AND reason=?'); $c->execute([$a['id'],$chiave]);
    if(!$c->fetch()){ pvAdd($a['id'],$top['pv'],$chiave); $premiato=$top['pv']; }
  }
  $pdo->prepare('INSERT INTO ai_decisions(account_id,evento,situazione,problema,azione,reward_pv,link,moduli,score,created_at) VALUES(?,?,?,?,?,?,?,?,?,?)')
    ->execute([$a['id'],'dashboard_load',json_encode($st,JSON_UNESCAPED_UNICODE),$top['problema'],$top['azione'],$premiato,$top['link'],implode(', ',$voti),$top['score'],gmdate('c')]);
  return ['stato'=>$st,'decisione'=>$top,'voti'=>$voti,'premiato'=>$premiato];
}

// Sweep dal cron: inattivi 15+ giorni, crea missione di rientro e mette in coda una email, dedup mensile
function orchestratorSweepInattivi($pdo,$limite=40){
  $rows=$pdo->query("SELECT a.* FROM accounts a WHERE a.status='attivo'")->fetchAll();
  $n=0;
  foreach($rows as $a){
    if($n>=$limite) break;
    $u=$pdo->prepare("SELECT MAX(created_at) m FROM events WHERE account_id=? AND type='login'"); $u->execute([$a['id']]); $m=$u->fetch()['m']??null;
    if(!$m) continue;
    $gg=intval((time()-strtotime($m))/86400);
    if($gg<15||$gg>120) continue;
    $mk='riattiva_'.gmdate('Ym');
    $c=$pdo->prepare('SELECT 1 FROM missions WHERE account_id=? AND tipo=?'); $c->execute([$a['id'],$mk]);
    if($c->fetch()) continue;
    $pdo->prepare('INSERT INTO missions(account_id,tipo,titolo,reward_pv,stato,scade_il,created_at) VALUES(?,?,?,?,?,?,?)')
      ->execute([$a['id'],$mk,'Rientra in dashboard e riscuoti il bonus di rientro',50,'attiva',gmdate('Y-m-d',time()+30*86400),gmdate('c')]);
    if(function_exists('mailGeneric')) @mailGeneric($a['email'],'81+ , il tuo bonus di rientro ti aspetta',"Ciao,\nsono ".$gg." giorni che non passi dalla tua dashboard 81+.\nTi abbiamo riservato un bonus di rientro di 50 PV, ti basta accedere.\nhttps://81plus.net/login.html\nLa direzione 81+");
    ev('riattivazione_inviata',$a['id'],['giorni'=>$gg]); $n++;
  }
  return $n;
}
