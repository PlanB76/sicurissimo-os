<?php
// 81+ MURO DEI 1000 PIX81+.
// Prezzo 1000 a PIX. Max 200 PV di sconto, il resto si paga con carta via PayPal.
// Max 20 PIX per utente. Upload logo con compressione. Link univoco legato al SIC ref.
require_once __DIR__.'/../src/db.php';
$az=$_GET['az']??'';
$in=json_decode(file_get_contents('php://input'),true)?:[];
$pdo=db();
define('PIX_PREZZO',1000);      // 1000 euro o 1000 PV a PIX
define('PIX_MAX_UTENTE',20);
define('PIX_TOTALI',1000);
define('PIX_MAX_SCONTO_PV',200); // massimo 200 PV di sconto per acquisto
define('PIX_IMG_MAX_MB',5);

function uniLink($sic,$pos){ return 'PIX-'.preg_replace('/[^0-9]/','',$sic).'-'.$pos; }

// MURO pubblico

// PROMO SETTIMANALE AUTOMATICA. Vera, calcolata e applicata solo dal server.
// Attiva nelle settimane ISO dispari, sconto reale, massimo 20 PIX a settimana.
function promoInfo($pdo){
  $sett=(int)gmdate('W'); $attiva=($sett%2)===1;
  $lun=strtotime('monday this week UTC'); if(gmdate('N')==='1' && gmdate('H')==='00') $lun=strtotime('today UTC');
  $inizio=gmdate('c',$lun); $fine=gmdate('c',$lun+7*86400);
  $usati=0;
  try{ $q=$pdo->prepare("SELECT COALESCE(SUM(n_pix),0) s FROM pixel_ordini WHERE stato='pagato' AND promo=1 AND created_at>=?"); $q->execute([$inizio]); $usati=(int)$q->fetch()['s']; }catch(Throwable $e){}
  return ['attiva'=>$attiva,'prezzo_promo'=>800,'sconto_pct'=>20,'slot_totali'=>20,'slot_usati'=>$usati,
          'slot_rimasti'=>max(0,20-$usati),'fine'=>$fine,'prossima'=>gmdate('c',$lun+($attiva?14:7)*86400 - ($attiva?7*86400:0))];
}

if($az==='muro'){
  $q=$pdo->query('SELECT pos,sic,azienda,url,colore,logo,img_path,link_univoco FROM pixel_muro WHERE account_id IS NOT NULL ORDER BY pos ASC');
  $occ=$q->fetchAll(PDO::FETCH_ASSOC);
  j(['ok'=>true,'totali'=>PIX_TOTALI,'prezzo'=>PIX_PREZZO,'max_utente'=>PIX_MAX_UTENTE,'max_sconto_pv'=>PIX_MAX_SCONTO_PV,'occupati'=>$occ,'venduti'=>count($occ),'promo'=>promoInfo($pdo)]);
}

// PUBBLICA, LIVING MAP. Ritorna lo stato dichiarato di ogni posizione 1..1000.
// Tipi reali e trasparenti, nessuna attivita finta. founder, riservato, utente, libero.
if($az==='mappa'){
  $rows=[];
  try{ $rows=$pdo->query('SELECT pos,tipo,account_id,azienda,descrizione,url,colore,logo,img_path,visite,click FROM pixel_muro ORDER BY pos')->fetchAll(PDO::FETCH_ASSOC); }catch(Throwable $e){
    // se la colonna tipo non c e ancora, fallback senza tipo
    $rows=$pdo->query('SELECT pos,account_id,azienda,descrizione,url,colore,logo,img_path,visite,click FROM pixel_muro ORDER BY pos')->fetchAll(PDO::FETCH_ASSOC);
  }
  // mappa per posizione
  $byPos=[]; foreach($rows as $r){ $byPos[(int)$r['pos']]=$r; }
  $celle=[]; $conte=['casa'=>0,'founder'=>0,'riservato'=>0,'utente'=>0,'libero'=>0,'esempio'=>0];
  for($p=1;$p<=PIX_TOTALI;$p++){
    $r=$byPos[$p]??null;
    if($r){
      $tipo=$r['tipo']??null;
      if(!$tipo){ $tipo=$r['account_id']?'utente':'libero'; }
      // se ha un account ed e segnato libero, e comunque un utente
      if($r['account_id'] && $tipo==='libero') $tipo='utente';
    } else { $tipo='libero'; }
    $conte[$tipo]=($conte[$tipo]??0)+1;
    $celle[]=['pos'=>$p,'tipo'=>$tipo,'azienda'=>$r['azienda']??null,'descrizione'=>$r['descrizione']??null,'url'=>$r['url']??null,'colore'=>$r['colore']??null,'logo'=>$r['logo']??null,'img'=>$r['img_path']??null,'visite'=>(int)($r['visite']??0),'click'=>(int)($r['click']??0)];
  }
  j(['ok'=>true,'totali'=>PIX_TOTALI,'celle'=>$celle,'conteggi'=>$conte,
     'legenda'=>['casa'=>'Nodi ufficiali dell ecosistema 81+','founder'=>'Regioni founder, riservate al franchising','riservato'=>'Whitelist token 81X','utente'=>'Presi da membri reali','esempio'=>'PIX dimostrativi, non clienti reali','libero'=>'Ancora disponibili']]);
}

// PUBBLICA, atterraggio da link univoco, mostra il PIX e l invitante
if($az==='link'){
  $lk=preg_replace('/[^A-Za-z0-9_-]/','',$in['link']??$_GET['link']??'');
  $q=$pdo->prepare('SELECT pos,sic,azienda,url,colore,logo,img_path FROM pixel_muro WHERE link_univoco=?'); $q->execute([$lk]);
  $px=$q->fetch(PDO::FETCH_ASSOC);
  if(!$px) j(['ok'=>false,'err'=>'link non trovato'],404);
  j(['ok'=>true,'pix'=>$px,'ref'=>$px['sic']]);
}

require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'accedi per usare i PIX81+'],401);

function pixMiei($pdo,$id){ $q=$pdo->prepare('SELECT COUNT(*) n FROM pixel_muro WHERE account_id=?'); $q->execute([$id]); return (int)($q->fetch()['n']??0); }

// I MIEI PIX
if($az==='miei'){
  $q=$pdo->prepare('SELECT pos,azienda,url,colore,logo,img_path,link_univoco,regalato_da FROM pixel_muro WHERE account_id=? ORDER BY pos'); $q->execute([$a['id']]);
  j(['ok'=>true,'pix'=>$q->fetchAll(PDO::FETCH_ASSOC),'quanti'=>pixMiei($pdo,$a['id']),'max'=>PIX_MAX_UTENTE]);
}

// PREVENTIVO acquisto, calcola euro da pagare con lo sconto PV scelto
if($az==='preventivo'){
  $posizioni=array_values(array_unique(array_map('intval',$in['posizioni']??[])));
  $pvSconto=max(0,min(PIX_MAX_SCONTO_PV,(int)($in['pv_sconto']??0)));
  $n=count($posizioni);
  if(!$n) j(['ok'=>false,'err'=>'scegli almeno un PIX'],422);
  if($n>PIX_MAX_UTENTE) j(['ok'=>false,'err'=>'massimo '.PIX_MAX_UTENTE.' PIX per volta'],422);
  $saldoPv=pvBalance($a['id']);
  if($pvSconto>$saldoPv) $pvSconto=$saldoPv;
  $costoTot=$n*PIX_PREZZO;
  $euro=max(0,$costoTot-$pvSconto);
  j(['ok'=>true,'n'=>$n,'costo'=>$costoTot,'pv_sconto'=>$pvSconto,'euro_da_pagare'=>$euro,'max_sconto'=>min(PIX_MAX_SCONTO_PV,$saldoPv)]);
}

// CREA ORDINE, registra l intento e prepara il pagamento PayPal
if($az==='crea_ordine'){
  $posizioni=array_values(array_unique(array_map('intval',$in['posizioni']??[])));
  $pvSconto=max(0,min(PIX_MAX_SCONTO_PV,(int)($in['pv_sconto']??0)));
  $regaloSic=trim($in['regalo_sic']??'');
  $n=count($posizioni);
  if(!$n) j(['ok'=>false,'err'=>'scegli almeno un PIX'],422);
  if($n>PIX_MAX_UTENTE) j(['ok'=>false,'err'=>'massimo '.PIX_MAX_UTENTE.' PIX'],422);
  foreach($posizioni as $p){ if($p<0||$p>=PIX_TOTALI) j(['ok'=>false,'err'=>'posizione non valida'],422); }
  // destinatario
  $dest=$a;
  if($regaloSic!==''){
    $r=$pdo->prepare('SELECT id,sic FROM accounts WHERE sic=?'); $r->execute([$regaloSic]); $dest=$r->fetch(PDO::FETCH_ASSOC);
    if(!$dest) j(['ok'=>false,'err'=>'il SIC destinatario non risulta registrato'],422);
  }
  if(pixMiei($pdo,$dest['id'])+$n>PIX_MAX_UTENTE) j(['ok'=>false,'err'=>'il destinatario supererebbe i '.PIX_MAX_UTENTE.' PIX'],422);
  // posizioni libere
  foreach($posizioni as $p){ $c=$pdo->prepare('SELECT account_id FROM pixel_muro WHERE pos=?'); $c->execute([$p]); $row=$c->fetch(); if($row && $row['account_id']!==null) j(['ok'=>false,'err'=>'il PIX '.$p.' è già stato preso'],409); }
  $saldoPv=pvBalance($a['id']); if($pvSconto>$saldoPv) $pvSconto=$saldoPv;
  // promo reale lato server, prezzo scontato solo se tutto l ordine entra negli slot della settimana
  $promo=promoInfo($pdo); $prezzoEff=PIX_PREZZO; $promoFlag=0;
  if($promo['attiva'] && $n<=$promo['slot_rimasti']){ $prezzoEff=$promo['prezzo_promo']; $promoFlag=1; }
  // profilo pubblicitario del PIX, applicato dopo il pagamento
  $pr=$in['profilo']??[];
  $profilo=json_encode([
    'azienda'=>mb_substr(trim($pr['azienda']??''),0,40),
    'descrizione'=>mb_substr(trim($pr['descrizione']??''),0,300),
    'url'=>mb_substr(trim($pr['url']??''),0,120),
    'colore'=>preg_match('/^#[0-9a-fA-F]{6}$/',$pr['colore']??'')?$pr['colore']:'#E8501A',
    'logo'=>mb_substr(trim($pr['logo']??''),0,4),
    'indirizzo'=>mb_substr(trim($pr['indirizzo']??''),0,200),
    'email'=>filter_var(trim($pr['email']??''),FILTER_VALIDATE_EMAIL)?:'',
  ],JSON_UNESCAPED_UNICODE);
  $euro=max(0,$n*$prezzoEff-$pvSconto);
  $pdo->prepare('INSERT INTO pixel_ordini(account_id,sic,posizioni,n_pix,pv_sconto,euro_da_pagare,stato,regalo_sic,promo,profilo,created_at) VALUES(?,?,?,?,?,?,?,?,?,?,?)')
      ->execute([$a['id'],$a['sic'],implode(',',$posizioni),$n,$pvSconto,$euro,'in_attesa',$regaloSic,$promoFlag,$profilo,gmdate('c')]);
  $ordineId=$pdo->lastInsertId();
  j(['ok'=>true,'ordine'=>$ordineId,'euro_da_pagare'=>$euro,'pv_sconto'=>$pvSconto,'promo_applicata'=>$promoFlag===1,'prezzo_unitario'=>$prezzoEff,'paypal_amount'=>number_format($euro,2,'.','')]);
}

// CONFERMA pagamento PayPal e assegna i PIX
if($az==='conferma'){
  $ordineId=(int)($in['ordine']??0);
  $orderID=preg_replace('/[^A-Za-z0-9_-]/','',$in['orderID']??'');
  $o=$pdo->prepare('SELECT * FROM pixel_ordini WHERE id=? AND account_id=?'); $o->execute([$ordineId,$a['id']]); $ord=$o->fetch(PDO::FETCH_ASSOC);
  if(!$ord) j(['ok'=>false,'err'=>'ordine non trovato'],404);
  if($ord['stato']==='pagato') j(['ok'=>false,'err'=>'ordine già completato'],409);
  $euro=(float)$ord['euro_da_pagare'];

  // verifica PayPal reale, salvo importo zero (tutto sconto PV)
  $verificato=false;
  if($euro<=0.001){ $verificato=true; }
  elseif(getenv('PAYPAL_FAKE')==='1'){ $verificato=true; }
  elseif(function_exists('curl_init') && getenv('PAYPAL_CLIENT_ID') && getenv('PAYPAL_CLIENT_SECRET') && $orderID!==''){
    $base=(getenv('PAYPAL_ENV')==='live')?'https://api-m.paypal.com':'https://api-m.sandbox.paypal.com';
    $ch=curl_init($base.'/v1/oauth2/token');
    curl_setopt_array($ch,[CURLOPT_POST=>1,CURLOPT_POSTFIELDS=>'grant_type=client_credentials',CURLOPT_USERPWD=>getenv('PAYPAL_CLIENT_ID').':'.getenv('PAYPAL_CLIENT_SECRET'),CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>15]);
    $tok=json_decode(curl_exec($ch),true)['access_token']??''; curl_close($ch);
    if($tok){
      $ch=curl_init($base.'/v2/checkout/orders/'.$orderID);
      curl_setopt_array($ch,[CURLOPT_HTTPHEADER=>['Authorization: Bearer '.$tok],CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>15]);
      $r=json_decode(curl_exec($ch),true); curl_close($ch);
      $imp=(float)($r['purchase_units'][0]['amount']['value']??0); $val=$r['purchase_units'][0]['amount']['currency_code']??'';
      if(($r['status']??'')==='COMPLETED' && $val==='EUR' && abs($imp-$euro)<0.01) $verificato=true;
    }
  }
  if(!$verificato) j(['ok'=>false,'err'=>'pagamento non verificato, nessun addebito doppio'],402);

  // destinatario
  $dest=$a;
  if($ord['regalo_sic']){ $r=$pdo->prepare('SELECT id,sic FROM accounts WHERE sic=?'); $r->execute([$ord['regalo_sic']]); $dest=$r->fetch(PDO::FETCH_ASSOC); if(!$dest) $dest=$a; }
  $posizioni=array_filter(array_map('intval',explode(',',$ord['posizioni'])),fn($x)=>$x>=0);

  $pdo->beginTransaction();
  try{
    // riverifico posizioni libere
    foreach($posizioni as $p){ $c=$pdo->prepare('SELECT account_id FROM pixel_muro WHERE pos=?'); $c->execute([$p]); $row=$c->fetch(); if($row && $row['account_id']!==null){ $pdo->rollBack(); j(['ok'=>false,'err'=>'un PIX è stato preso nel frattempo'],409); } }
    // scalo i PV di sconto
    if((int)$ord['pv_sconto']>0){
      if(pvBalance($a['id'])<(int)$ord['pv_sconto']){ $pdo->rollBack(); j(['ok'=>false,'err'=>'PV insufficienti'],422); }
      pvAdd($a['id'],-(int)$ord['pv_sconto'],'pix_sconto_pv');
    }
    $now=gmdate('c');
    foreach($posizioni as $p){
      $lk=uniLink($dest['sic'],$p);
      $ex=$pdo->prepare('SELECT id FROM pixel_muro WHERE pos=?'); $ex->execute([$p]);
      if($ex->fetch()){
        $pdo->prepare('UPDATE pixel_muro SET account_id=?,sic=?,link_univoco=?,regalato_da=?,stato=?,created_at=?,updated_at=? WHERE pos=?')
            ->execute([$dest['id'],$dest['sic'],$lk,$ord['regalo_sic']?:null,'attivo',$now,$now,$p]);
      } else {
        $pdo->prepare('INSERT INTO pixel_muro(pos,account_id,sic,link_univoco,regalato_da,stato,created_at,updated_at) VALUES(?,?,?,?,?,?,?,?)')
            ->execute([$p,$dest['id'],$dest['sic'],$lk,$ord['regalo_sic']?:null,'attivo',$now,$now]);
      }
    }
    // applico il profilo pubblicitario, il logo sul globo appare subito
    $prof=json_decode($ord['profilo']??'',true)?:[];
    if(!empty($prof['azienda'])||!empty($prof['url'])||!empty($prof['email'])||!empty($prof['indirizzo'])){
      foreach($posizioni as $p){
        $pdo->prepare('UPDATE pixel_muro SET azienda=?,descrizione=?,url=?,colore=?,logo=?,indirizzo=?,email=?,tipo=? WHERE pos=? AND account_id=?')
            ->execute([$prof['azienda']?:null,$prof['descrizione']?:null,$prof['url']?:null,$prof['colore']??'#E8501A',$prof['logo']?:null,$prof['indirizzo']?:null,$prof['email']?:null,'utente',$p,$dest['id']]);
      }
    } else {
      foreach($posizioni as $p){ $pdo->prepare('UPDATE pixel_muro SET tipo=? WHERE pos=? AND account_id=?')->execute(['utente',$p,$dest['id']]); }
    }
    $pdo->prepare("UPDATE pixel_ordini SET stato='pagato',paypal_order_id=?,paid_at=? WHERE id=?")->execute([$orderID,$now,$ordineId]);
    $pdo->commit();
  }catch(Throwable $e){ $pdo->rollBack(); j(['ok'=>false,'err'=>'assegnazione non riuscita'],500); }
  ev('pix_acquisto',$a['id'],['n'=>count($posizioni),'euro'=>$euro,'pv_sconto'=>(int)$ord['pv_sconto'],'regalo'=>(bool)$ord['regalo_sic']]);
  j(['ok'=>true,'comprati'=>count($posizioni),'euro_pagati'=>$euro,'pv_saldo'=>pvBalance($a['id'])]);
}

// PERSONALIZZA un PIX, azienda, url, colore, logo testo
if($az==='aggiorna'){
  $pos=(int)($in['pos']??-1);
  $q=$pdo->prepare('SELECT id FROM pixel_muro WHERE pos=? AND account_id=?'); $q->execute([$pos,$a['id']]);
  if(!$q->fetch()) j(['ok'=>false,'err'=>'questo PIX non è tuo'],403);
  $azienda=mb_substr(trim($in['azienda']??''),0,40);
  $url=mb_substr(trim($in['url']??''),0,120);
  $colore=preg_match('/^#[0-9a-fA-F]{6}$/',$in['colore']??'')?$in['colore']:'#E8501A';
  $logo=mb_substr(trim($in['logo']??'81+'),0,4);
  $pdo->prepare('UPDATE pixel_muro SET azienda=?,url=?,colore=?,logo=?,updated_at=? WHERE pos=? AND account_id=?')
      ->execute([$azienda,$url,$colore,$logo,gmdate('c'),$pos,$a['id']]);
  j(['ok'=>true,'aggiornato'=>$pos]);
}


// ===== FOUNDING NODE, titolo, badge, distretto, score =====
function pixTitolo($n){
  if($n>=25) return ['Master Builder','💎'];
  if($n>=10) return ['Legend','👑'];
  if($n>=5)  return ['Architect','📐'];
  if($n>=3)  return ['Founder','🏛️'];
  if($n>=1)  return ['Builder','🧱'];
  return ['Visitatore','👤'];
}
function pixBadge($pos){
  if($pos<100) return ['founding100','Founding 100','🏛️'];
  if($pos<500) return ['pioneer500','Pioneer 500','⚜️'];
  if($pos<1000) return ['original','Original Builder','🛡️'];
  return [null,'',''];
}
function pixDistretto($pos){
  if($pos<400) return 'Member District';
  if($pos<700) return 'Networker District';
  if($pos<900) return 'Elite District';
  return 'Club81+ District';
}
function pixScore($r){
  return (int)($r['visite']??0)*1 + (int)($r['click']??0)*3 + (int)($r['inviti']??0)*10 + (int)($r['iscritti']??0)*25;
}

// STATO del founder loggato, per il widget dashboard
if($az==='stato'){
  $a=currentAccount(); if(!$a) j(['ok'=>true,'loggato'=>false]);
  $q=$pdo->prepare('SELECT COUNT(*) n FROM pixel_muro WHERE account_id=?'); $q->execute([$a['id']]);
  $n=(int)($q->fetch()['n']??0);
  $tot=(int)($pdo->query('SELECT COUNT(*) n FROM pixel_muro WHERE account_id IS NOT NULL')->fetch()['n']??0);
  list($titolo,$ic)=pixTitolo($n);
  j(['ok'=>true,'loggato'=>true,'posseduti'=>$n,'disponibili'=>PIX_TOTALI-$tot,'totali'=>PIX_TOTALI,
     'elite_space'=>$n>=1,'titolo'=>$titolo,'titolo_icona'=>$ic]);
}

// FOMO, ultimi acquisti pubblici
if($az==='fomo'){
  $q=$pdo->query("SELECT pos,azienda,created_at FROM pixel_muro WHERE account_id IS NOT NULL ORDER BY id DESC LIMIT 8");
  $ultimi=$q->fetchAll(PDO::FETCH_ASSOC);
  $tot=(int)($pdo->query('SELECT COUNT(*) n FROM pixel_muro WHERE account_id IS NOT NULL')->fetch()['n']??0);
  j(['ok'=>true,'ultimi'=>$ultimi,'occupati'=>$tot,'disponibili'=>PIX_TOTALI-$tot,'totali'=>PIX_TOTALI]);
}

// HALL OF FAME, classifica per score
if($az==='hall'){
  $q=$pdo->query("SELECT pos,sic,azienda,url,colore,logo,img_path,visite,click,inviti,iscritti FROM pixel_muro WHERE account_id IS NOT NULL");
  $rows=$q->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as &$r){ $r['score']=pixScore($r); list($r['badge_id'],$r['badge'],$r['badge_ic'])=pixBadge((int)$r['pos']); }
  usort($rows,fn($a,$b)=>$b['score']-$a['score']);
  j(['ok'=>true,'classifica'=>array_slice($rows,0,20)]);
}

// VISITA, traccia una visita a un PIX da link univoco (pubblico)
if($az==='visita'){
  $lk=preg_replace('/[^A-Za-z0-9_-]/','',$in['link']??$_GET['link']??'');
  if($lk!==''){ $pdo->prepare('UPDATE pixel_muro SET visite=visite+1 WHERE link_univoco=?')->execute([$lk]); }
  j(['ok'=>true]);
}

// ADMIN, assegna un tipo dichiarato a una o piu posizioni. Solo direzione.
// Serve a marcare i founder node della direzione e i riservati a partner e whitelist.
if($az==='admin_tipo'){
  $isAdmin=(($a['sic']??'')==='SIC-0000001' || ($a['ruolo']??'')==='admin');
  if(!$isAdmin) j(['ok'=>false,'err'=>'solo la direzione'],403);
  $tipo=in_array($in['tipo']??'',['casa','founder','riservato','esempio','libero'])?$in['tipo']:'';
  $da=(int)($in['da']??0); $aP=(int)($in['a']??0);
  if(!$tipo||$da<1||$aP<$da||$aP>PIX_TOTALI) j(['ok'=>false,'err'=>'parametri non validi'],422);
  // garantisco che le righe esistano e setto il tipo, solo su posizioni senza account reale
  $n=0;
  for($p=$da;$p<=$aP;$p++){
    $ex=$pdo->prepare('SELECT id,account_id FROM pixel_muro WHERE pos=?'); $ex->execute([$p]); $row=$ex->fetch();
    if($row){
      if($row['account_id']) continue; // non tocco i PIX di utenti reali
      $pdo->prepare('UPDATE pixel_muro SET tipo=? WHERE pos=?')->execute([$tipo,$p]); $n++;
    } else {
      $pdo->prepare('INSERT INTO pixel_muro(pos,tipo,stato,created_at) VALUES(?,?,?,?)')->execute([$p,$tipo,'riservato',gmdate('c')]); $n++;
    }
  }
  ev('pix_admin_tipo',$a['id'],['tipo'=>$tipo,'da'=>$da,'a'=>$aP,'n'=>$n]);
  j(['ok'=>true,'aggiornati'=>$n,'tipo'=>$tipo]);
}


j(['ok'=>false,'err'=>'azione sconosciuta'],404);
