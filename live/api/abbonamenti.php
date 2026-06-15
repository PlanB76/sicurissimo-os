<?php
// 81+ ABBONAMENTI RICORRENTI. In euro (PayPal) o in PV (rinnovo dal wallet).
// L utente puo disdire. Ogni disattivazione, sospensione o recesso avvisa la direzione.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php'; require_once __DIR__.'/../src/avvisi81.php';
$pdo=db();
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'accedi'],401);
$az=$_GET['az']??'miei';
$in=json_decode(file_get_contents('php://input'),true)?:[];

// catalogo piani abbonabili, prezzi dal listino canonico
$LISTINO=[
  'membership_basic'=>['nome'=>'Membership Basic','euro'=>49,'pv'=>49,'ciclo'=>'mensile'],
  'membership_pro'  =>['nome'=>'Membership Pro','euro'=>99,'pv'=>99,'ciclo'=>'mensile'],
  'membership_elite'=>['nome'=>'Membership Elite','euro'=>149,'pv'=>149,'ciclo'=>'mensile'],
  'club_palladium'  =>['nome'=>'Club 81+ Palladium','euro'=>1990,'pv'=>1990,'ciclo'=>'mensile'],
  'club_iridium'    =>['nome'=>'Club 81+ Iridium','euro'=>4990,'pv'=>4990,'ciclo'=>'mensile'],
  'club_rhodium'    =>['nome'=>'Club 81+ Rhodium','euro'=>9990,'pv'=>9990,'ciclo'=>'mensile'],
  'rdp_ignite'      =>['nome'=>'RDP+ Ignite','euro'=>49,'pv'=>49,'ciclo'=>'mensile'],
  'rdp_rise'        =>['nome'=>'RDP+ Rise','euro'=>99,'pv'=>99,'ciclo'=>'mensile'],
  'rdp_drive'       =>['nome'=>'RDP+ Drive','euro'=>149,'pv'=>149,'ciclo'=>'mensile'],
  'rdp_scale'       =>['nome'=>'RDP+ Scale','euro'=>249,'pv'=>249,'ciclo'=>'mensile'],
  'rdp_peak'        =>['nome'=>'RDP+ Peak','euro'=>399,'pv'=>399,'ciclo'=>'mensile'],
  'rdp_summit'      =>['nome'=>'RDP+ Summit','euro'=>599,'pv'=>599,'ciclo'=>'mensile'],
  'rdp_legacy'      =>['nome'=>'RDP+ Legacy','euro'=>799,'pv'=>799,'ciclo'=>'mensile'],
  'rdp_crown'       =>['nome'=>'RDP+ Crown','euro'=>999,'pv'=>999,'ciclo'=>'mensile'],
];

if($az==='catalogo'){ j(['ok'=>true,'piani'=>$LISTINO]); }

if($az==='miei'){
  $q=$pdo->prepare('SELECT id,prodotto,piano,prezzo_euro,prezzo_pv,metodo,ciclo,stato,prossimo_rinnovo,creato_il FROM abbonamenti WHERE account_id=? ORDER BY id DESC');
  $q->execute([$a['id']]);
  j(['ok'=>true,'abbonamenti'=>$q->fetchAll(PDO::FETCH_ASSOC)]);
}

// attiva un abbonamento, in PV (subito dal wallet) o in euro (segna PayPal sub id)
if($az==='attiva'){
  $piano=$in['piano']??''; $metodo=($in['metodo']??'euro')==='pv'?'pv':'euro';
  $p=$LISTINO[$piano]??null;
  if(!$p) j(['ok'=>false,'err'=>'piano sconosciuto'],404);
  // niente doppioni attivi sullo stesso piano
  $d=$pdo->prepare("SELECT 1 FROM abbonamenti WHERE account_id=? AND piano=? AND stato='attivo'"); $d->execute([$a['id'],$piano]);
  if($d->fetch()) j(['ok'=>false,'err'=>'hai già questo abbonamento attivo'],409);

  $prossimo=gmdate('c',time()+30*86400);
  if($metodo==='pv'){
    if(pvBalance($a['id'])<(int)$p['pv']) j(['ok'=>false,'err'=>'PV insufficienti','servono'=>(int)$p['pv'],'hai'=>pvBalance($a['id'])],402);
    pvAdd($a['id'],-(int)$p['pv'],'abbonamento_'.$piano);
  }
  $subId=$metodo==='euro'?preg_replace('/[^A-Za-z0-9_-]/','',$in['paypal_sub_id']??''):null;
  $pdo->prepare('INSERT INTO abbonamenti(account_id,sic,prodotto,piano,prezzo_euro,prezzo_pv,metodo,ciclo,stato,paypal_sub_id,prossimo_rinnovo,creato_il) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)')
      ->execute([$a['id'],$a['sic'],explode('_',$piano)[0],$piano,(float)$p['euro'],(int)$p['pv'],$metodo,$p['ciclo'],'attivo',$subId,$prossimo,gmdate('c')]);
  ev('abbonamento_attivato',$a['id'],['piano'=>$piano,'metodo'=>$metodo]);
  j(['ok'=>true,'piano'=>$p['nome'],'metodo'=>$metodo,'prossimo_rinnovo'=>$prossimo,'saldo'=>pvBalance($a['id'])]);
}

// disdetta da parte dell utente, avvisa la direzione
if($az==='disdici'){
  $id=(int)($in['id']??0);
  $q=$pdo->prepare('SELECT * FROM abbonamenti WHERE id=? AND account_id=?'); $q->execute([$id,$a['id']]);
  $ab=$q->fetch(PDO::FETCH_ASSOC);
  if(!$ab) j(['ok'=>false,'err'=>'abbonamento non trovato'],404);
  if($ab['stato']!=='attivo') j(['ok'=>false,'err'=>'questo abbonamento non è attivo'],409);
  $motivo=mb_substr(trim($in['motivo']??''),0,191);
  $pdo->prepare("UPDATE abbonamenti SET stato='disdetto',disdetto_il=?,motivo_fine=? WHERE id=?")->execute([gmdate('c'),$motivo,$id]);
  // AVVISO ALLA DIREZIONE
  avvisoDirezione('abbonamento_disdetto','Abbonamento disdetto, '.$ab['piano'],
    'L utente '.$a['sic'].' ha disdetto '.$ab['piano'].'. Metodo '.$ab['metodo'].'. Motivo, '.($motivo?:'non indicato').'. Se in euro, ricorda che il pagamento ricorrente va fermato anche su PayPal.',
    $a['id'],$a['sic'],'media');
  ev('abbonamento_disdetto',$a['id'],['piano'=>$ab['piano']]);
  $nota=$ab['metodo']==='euro'
    ? 'Abbonamento disdetto. Il rinnovo in euro va fermato anche dal tuo PayPal, nelle impostazioni dei pagamenti automatici.'
    : 'Abbonamento disdetto. Non verranno più scalati PV ai prossimi cicli.';
  j(['ok'=>true,'nota'=>$nota]);
}

// sospensione temporanea
if($az==='sospendi'){
  $id=(int)($in['id']??0);
  $q=$pdo->prepare('SELECT * FROM abbonamenti WHERE id=? AND account_id=?'); $q->execute([$id,$a['id']]);
  $ab=$q->fetch(PDO::FETCH_ASSOC);
  if(!$ab||$ab['stato']!=='attivo') j(['ok'=>false,'err'=>'abbonamento non attivo'],409);
  $pdo->prepare("UPDATE abbonamenti SET stato='sospeso' WHERE id=?")->execute([$id]);
  avvisoDirezione('abbonamento_sospeso','Abbonamento sospeso, '.$ab['piano'],
    'L utente '.$a['sic'].' ha sospeso '.$ab['piano'].'.',$a['id'],$a['sic'],'bassa');
  ev('abbonamento_sospeso',$a['id'],['piano'=>$ab['piano']]);
  j(['ok'=>true,'nota'=>'Abbonamento sospeso. Lo puoi riattivare quando vuoi.']);
}

// recesso entro i termini di legge, avviso alto alla direzione
if($az==='recesso'){
  $id=(int)($in['id']??0);
  $q=$pdo->prepare('SELECT * FROM abbonamenti WHERE id=? AND account_id=?'); $q->execute([$id,$a['id']]);
  $ab=$q->fetch(PDO::FETCH_ASSOC);
  if(!$ab) j(['ok'=>false,'err'=>'abbonamento non trovato'],404);
  $pdo->prepare("UPDATE abbonamenti SET stato='recesso',disdetto_il=?,motivo_fine=? WHERE id=?")->execute([gmdate('c'),'recesso legale',$id]);
  avvisoDirezione('abbonamento_recesso','RECESSO richiesto, '.$ab['piano'],
    'L utente '.$a['sic'].' ha esercitato il recesso su '.$ab['piano'].'. Va gestito secondo i termini di legge, valutare eventuale rimborso e stop pagamento PayPal.',
    $a['id'],$a['sic'],'alta');
  ev('abbonamento_recesso',$a['id'],['piano'=>$ab['piano']]);
  j(['ok'=>true,'nota'=>'Richiesta di recesso registrata. La direzione la gestisce secondo i termini di legge e ti contatta.']);
}

j(['ok'=>false,'err'=>'azione sconosciuta'],404);
