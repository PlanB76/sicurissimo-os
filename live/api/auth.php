<?php
function twofaTabella($pdo){
  try{ $pdo->query('SELECT 1 FROM twofa_codes LIMIT 1'); }
  catch(Exception $e){
    $drv=$pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    if(stripos($drv,'sqlite')!==false) $pdo->exec('CREATE TABLE IF NOT EXISTS twofa_codes (id INTEGER PRIMARY KEY AUTOINCREMENT, account_id INTEGER, code_hash TEXT, scopo TEXT, expires_at TEXT, used INTEGER DEFAULT 0, created_at TEXT)');
    else $pdo->exec('CREATE TABLE IF NOT EXISTS twofa_codes (id INT AUTO_INCREMENT PRIMARY KEY, account_id INT NOT NULL, code_hash VARCHAR(190) NOT NULL, scopo VARCHAR(20), expires_at VARCHAR(40), used TINYINT DEFAULT 0, created_at VARCHAR(40), KEY idx_acc(account_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
  }
  try{ $pdo->query('SELECT twofa FROM accounts LIMIT 1'); }
  catch(Exception $e){ try{ $pdo->exec("ALTER TABLE accounts ADD COLUMN twofa VARCHAR(10) DEFAULT 'off'"); }catch(Exception $x){} }
}
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/sic.php'; require_once __DIR__.'/../src/auth_lib.php'; require_once __DIR__.'/../src/enroll.php'; require_once __DIR__.'/../src/keccak.php'; require_once __DIR__.'/../src/eth_verify.php';
$action=$_GET['action']??''; $pdo=db(); $in=body();


function campiProfilo($a){
  $tipo=$a['tipo']??'persona';
  if($tipo==='azienda'){ $req=['ragione_sociale','piva','tel','pec','ateco']; }
  else { $req=['nome','cognome','codice_fiscale','tel','data_nascita','indirizzo']; }
  $fatti=0; foreach($req as $k){ if(trim((string)($a[$k]??''))!=='') $fatti++; }
  return [$req,$fatti];
}
function profiloCompleto($a){ list($req,$fatti)=campiProfilo($a); return $fatti===count($req); }
function profiloPerc($a){ list($req,$fatti)=campiProfilo($a); return intval(100*$fatti/max(1,count($req))); }

function settoreDaAteco($ateco){
  $cfgp=__DIR__.'/../data/ateco_settori.json'; $j=json_decode(@file_get_contents($cfgp),true);
  if(!$j) return null;
  $a=trim((string)$ateco); $best=null;
  if($a!==''){
    foreach($j['settori'] as $st){ foreach($st['match'] as $m){ if(strpos($a,$m)===0){ if($best===null||strlen($m)>$best['_len']){ $best=$st; $best['_len']=strlen($m); } } } }
  }
  $out = $best ?: $j['default'];
  unset($out['_len']); unset($out['match']);
  return $out;
}
function pwdScaduta($a){
  $t=$a['pwd_set_at']??null; if(!$t) return false;
  return (time()-strtotime($t)) > 183*86400;
}


function creaAccount($pdo,$in){
  for($t=0;$t<5;$t++){ $sic=generaSic($pdo);
    try{
      $pdo->prepare('INSERT INTO accounts(sic,email,pass_hash,tipo,nome,cognome,ragione_sociale,tel,ref_by,codice_fiscale,kyc_level,status,email_verified,created_at) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)')
        ->execute([$sic,$in['email'],$in['pass_hash'],$in['tipo']??'persona',$in['nome']??null,$in['cognome']??null,$in['ragione_sociale']??null,$in['tel']??null,$in['ref_by']??null,$in['codice_fiscale']??null,0,'attivo',0,gmdate('c')]);
      return [$pdo->lastInsertId(),$sic];
    }catch(PDOException $e){ if(strpos($e->getMessage(),'sic')!==false || $e->getCode()=='23000'){ continue; } throw $e; }
  }
  throw new Exception('creazione account fallita');
}

// Sonda leggera, dice solo se la sessione e attiva, per popup e widget
if($action==='chi'){
  $a=currentAccount();
  if($a) j(['ok'=>true,'nome'=>$a['nome']??'','sic'=>$a['sic']??'']);
  j(['ok'=>false],401);
}

if($action==='registra'){
  if(!rateOk('reg:'.clientIp(),10,3600)) j(['ok'=>false,'err'=>'troppi tentativi, riprova più tardi'],429);
  $email=trim(strtolower($in['email']??'')); $pass=$in['password']??'';
  $cf=strtoupper(trim($in['codice_fiscale']??'')); if($cf!=='' && !preg_match('/^[A-Z0-9]{11,16}$/',$cf)) j(['ok'=>false,'err'=>'codice fiscale non valido'],422);
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($pass)<8) j(['ok'=>false,'err'=>'email valida e password di almeno 8 caratteri'],422);
  $ex=$pdo->prepare('SELECT 1 FROM accounts WHERE email=?'); $ex->execute([$email]); if($ex->fetch()) j(['ok'=>false,'err'=>'email già registrata'],409);
  list($id,$sic)=creaAccount($pdo,['email'=>$email,'pass_hash'=>password_hash($pass,PASSWORD_DEFAULT),'tipo'=>$in['tipo']??'persona','nome'=>$in['nome']??null,'cognome'=>$in['cognome']??null,'ragione_sociale'=>$in['ragione_sociale']??null,'tel'=>$in['tel']??null,'ref_by'=>$in['ref_by']??null,'codice_fiscale'=>$cf?:null]);
  $pdo->prepare('UPDATE accounts SET pwd_set_at=? WHERE id=?')->execute([gmdate('c'),$id]);
  pvAdd($id,100,'welcome_registrazione'); creaWallets($pdo,$id,$sic);
  $pdo->prepare('INSERT INTO network_nodes(account_id,parent_sic,rank,qualified,vol_personal,vol_group,updated_at) VALUES(?,?,?,?,?,?,?)')->execute([$id,$in['ref_by']??null,0,0,0,0,gmdate('c')]);
  ev('signup',$id,['sic'=>$sic]);
  if(!empty($in['ref_by'])){ $rf=$pdo->prepare('SELECT id FROM accounts WHERE sic=?'); $rf->execute([$in['ref_by']]); $padr=$rf->fetch();
    if($padr){ $rr='invito_registrato_'.$id; pvAdd($padr['id'],25,$rr); ev('invito_registrato',$padr['id'],['figlio'=>$id]); } }
  if(!empty($in['px'])){ try{ $pdo->prepare('UPDATE pixel_muro SET iscritti=iscritti+1 WHERE link_univoco=?')->execute([preg_replace('/[^a-zA-Z0-9_-]/','',$in['px'])]); }catch(Throwable $e){} }
  enrollUniversale($email,trim(($in['nome']??'').' '.($in['cognome']??'')),'signup',$in['ref_by']??null); sendWelcome($email,$sic); require_once __DIR__.'/../src/brevo81.php'; @brevoUpsert($email,['SIC'=>$sic,'ORIGINE'=>'hub1']); $tok=startSession($id);
  j(['ok'=>true,'sic'=>$sic,'pv'=>100,'csrf'=>csrfFor($tok)]);
}
if($action==='login'){
  $emailIn=strtolower(trim($in['email']??''));
  // doppio scudo: limite per IP e limite per account, contro gli attacchi distribuiti su un solo utente
  if(!rateOk('login:'.clientIp(),12,900)) j(['ok'=>false,'err'=>'troppi tentativi, riprova tra qualche minuto'],429);
  if($emailIn && !rateOk('login_acc:'.$emailIn,8,900)) j(['ok'=>false,'err'=>'troppi tentativi su questo account, riprova tra qualche minuto'],429);
  $st=$pdo->prepare('SELECT * FROM accounts WHERE email=?'); $st->execute([$emailIn]); $a=$st->fetch();
  if(!$a||!password_verify($in['password']??'',$a['pass_hash'])){ ev('login_fallito',$a['id']??0,['ip'=>substr(md5(clientIp()),0,8)]); j(['ok'=>false,'err'=>'credenziali non valide'],401); }
  if(empty($a['pwd_set_at'])){ $pdo->prepare('UPDATE accounts SET pwd_set_at=? WHERE id=?')->execute([gmdate('c'),$a['id']]); $a['pwd_set_at']=gmdate('c'); }
  // se la 2FA è accesa, niente sessione adesso: parte il codice via email e si chiude al secondo passo
  if(($a['twofa']??'off')==='email'){
    twofaTabella($pdo);
    $code=str_pad((string)random_int(0,999999),6,'0',STR_PAD_LEFT);
    $pdo->prepare('INSERT INTO twofa_codes(account_id,code_hash,scopo,expires_at,used,created_at) VALUES(?,?,?,?,0,?)')
       ->execute([$a['id'],password_hash($code,PASSWORD_DEFAULT),'login',gmdate('c',time()+600),gmdate('c')]);
    @mailGeneric($a['email'],'81+ , il tuo codice di accesso',"Il tuo codice di accesso 81+ è\n\n".$code."\n\nVale dieci minuti e una volta sola. Se non hai chiesto tu questo accesso, cambia subito la password.\n\nLa direzione 81+");
    $pre=hash_hmac('sha256',$a['id'].'|'.gmdate('YmdH'),cfg()['app_secret']);
    ev('login_2fa_richiesto',$a['id']);
    j(['ok'=>true,'twofa'=>true,'pre'=>$pre,'acc'=>$a['id'],'msg'=>'codice inviato alla tua email']);
  }
  $tok=startSession($a['id']); ev('login',$a['id']); j(['ok'=>true,'sic'=>$a['sic'],'csrf'=>csrfFor($tok),'pwd_scaduta'=>pwdScaduta($a)]);
}

if($action==='login_2fa'){
  if(!rateOk('2fa:'.clientIp(),15,900)) j(['ok'=>false,'err'=>'troppi tentativi'],429);
  $accId=(int)($in['acc']??0); $pre=$in['pre']??''; $code=trim($in['code']??'');
  $atteso1=hash_hmac('sha256',$accId.'|'.gmdate('YmdH'),cfg()['app_secret']);
  $atteso2=hash_hmac('sha256',$accId.'|'.gmdate('YmdH',time()-3600),cfg()['app_secret']);
  if(!hash_equals($atteso1,$pre)&&!hash_equals($atteso2,$pre)) j(['ok'=>false,'err'=>'sessione di verifica scaduta, rifai l accesso'],403);
  twofaTabella($pdo);
  $q=$pdo->prepare("SELECT * FROM twofa_codes WHERE account_id=? AND scopo='login' AND used=0 ORDER BY id DESC LIMIT 3"); $q->execute([$accId]);
  $valido=false;
  foreach($q->fetchAll() as $r){
    if(strtotime($r['expires_at'])<time()) continue;
    if(password_verify($code,$r['code_hash'])){ $pdo->prepare('UPDATE twofa_codes SET used=1 WHERE id=?')->execute([$r['id']]); $valido=true; break; }
  }
  if(!$valido) j(['ok'=>false,'err'=>'codice non valido o scaduto'],401);
  $st=$pdo->prepare('SELECT * FROM accounts WHERE id=?'); $st->execute([$accId]); $a=$st->fetch();
  if(!$a) j(['ok'=>false,'err'=>'account non trovato'],404);
  $tok=startSession($a['id']); ev('login',$a['id'],['via'=>'2fa']); j(['ok'=>true,'sic'=>$a['sic'],'csrf'=>csrfFor($tok),'pwd_scaduta'=>pwdScaduta($a)]);
}

if($action==='twofa_stato'){
  $a=currentAccount(); if(!$a) j(['ok'=>false],401);
  j(['ok'=>true,'twofa'=>$a['twofa']??'off']);
}
if($action==='twofa_richiedi'){
  $a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
  if(!csrfCheck()) j(['ok'=>false,'err'=>'token csrf mancante'],403);
  if(!rateOk('2fareq:'.$a['id'],4,3600)) j(['ok'=>false,'err'=>'troppe richieste, riprova tra un ora'],429);
  twofaTabella($pdo);
  $code=str_pad((string)random_int(0,999999),6,'0',STR_PAD_LEFT);
  $pdo->prepare('INSERT INTO twofa_codes(account_id,code_hash,scopo,expires_at,used,created_at) VALUES(?,?,?,?,0,?)')
     ->execute([$a['id'],password_hash($code,PASSWORD_DEFAULT),'attiva',gmdate('c',time()+600),gmdate('c')]);
  @mailGeneric($a['email'],'81+ , codice per attivare la doppia verifica',"Il codice per attivare la doppia verifica sul tuo account è\n\n".$code."\n\nVale dieci minuti. Da quando la attivi, a ogni accesso ti chiederemo password piu codice email. Piu sicuro, sempre.\n\nLa direzione 81+");
  j(['ok'=>true,'msg'=>'codice inviato alla tua email']);
}
if($action==='twofa_attiva'){
  $a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
  if(!csrfCheck()) j(['ok'=>false,'err'=>'token csrf mancante'],403);
  twofaTabella($pdo);
  $code=trim($in['code']??''); $valido=false;
  $q=$pdo->prepare("SELECT * FROM twofa_codes WHERE account_id=? AND scopo='attiva' AND used=0 ORDER BY id DESC LIMIT 3"); $q->execute([$a['id']]);
  foreach($q->fetchAll() as $r){ if(strtotime($r['expires_at'])>=time() && password_verify($code,$r['code_hash'])){ $pdo->prepare('UPDATE twofa_codes SET used=1 WHERE id=?')->execute([$r['id']]); $valido=true; break; } }
  if(!$valido) j(['ok'=>false,'err'=>'codice non valido o scaduto'],401);
  $pdo->prepare("UPDATE accounts SET twofa='email' WHERE id=?")->execute([$a['id']]);
  @mailGeneric($a['email'],'81+ , doppia verifica attiva',"Fatto. Da adesso il tuo account 81+ chiede password piu codice email a ogni accesso. Se un giorno vuoi spegnerla, lo fai dalla dashboard con la tua password.\n\nLa direzione 81+");
  ev('twofa_attivata',$a['id']); j(['ok'=>true]);
}
if($action==='twofa_spegni'){
  $a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
  if(!csrfCheck()) j(['ok'=>false,'err'=>'token csrf mancante'],403);
  if(!password_verify($in['password']??'',$a['pass_hash'])) j(['ok'=>false,'err'=>'password non corretta'],403);
  $pdo->prepare("UPDATE accounts SET twofa='off' WHERE id=?")->execute([$a['id']]);
  @mailGeneric($a['email'],'81+ , doppia verifica spenta',"La doppia verifica sul tuo account è stata spenta adesso. Se non sei stato tu, cambia subito la password e riattivala.\n\nLa direzione 81+");
  ev('twofa_spenta',$a['id']); j(['ok'=>true]);
}

if($action==='wallet_nonce'){
  if(!rateOk('wn:'.clientIp(),30,3600)) j(['ok'=>false,'err'=>'troppe richieste'],429);
  $nonce=bin2hex(random_bytes(16));
  $pdo->prepare('INSERT INTO wallet_nonces(nonce,created_at) VALUES(?,?)')->execute([$nonce,gmdate('c')]);
  $msg="Accedo a 81plus.net col mio wallet.\nNonce ".$nonce."\nData ".gmdate('c');
  j(['ok'=>true,'nonce'=>$nonce,'message'=>$msg]);
}
if($action==='wallet_login'){
  if(defined('ETH_VERIFY_ON') && !ETH_VERIFY_ON) j(['ok'=>false,'err'=>'login wallet momentaneamente non disponibile su questo server'],503);
  if(!rateOk('wl:'.clientIp(),20,900)) j(['ok'=>false,'err'=>'troppi tentativi'],429);
  $addr=strtolower(trim($in['address']??'')); $sig=$in['signature']??''; $msg=$in['message']??''; $nonce=$in['nonce']??'';
  if(!preg_match('/^0x[a-f0-9]{40}$/',$addr)) j(['ok'=>false,'err'=>'indirizzo non valido'],422);
  $st=$pdo->prepare('SELECT id,used FROM wallet_nonces WHERE nonce=?'); $st->execute([$nonce]); $nr=$st->fetch();
  if(!$nr || (int)$nr['used']===1) j(['ok'=>false,'err'=>'nonce non valido o già usato'],401);
  if(strpos($msg,$nonce)===false) j(['ok'=>false,'err'=>'messaggio non coerente col nonce'],401);
  $rec=eth_recover($msg,$sig);
  if(!$rec || strtolower($rec)!==$addr){ ev('wallet_login_fallito',null,['addr'=>$addr]); j(['ok'=>false,'err'=>'firma non valida'],401); }
  $pdo->prepare('UPDATE wallet_nonces SET used=1,address=? WHERE nonce=?')->execute([$addr,$nonce]);
  // trova o crea account legato all’indirizzo
  $st=$pdo->prepare('SELECT * FROM accounts WHERE eth_address=?'); $st->execute([$addr]); $a=$st->fetch();
  if(!$a){
    list($id,$sic)=creaAccount($pdo,['email'=>$addr.'@wallet.81plus','pass_hash'=>password_hash(bin2hex(random_bytes(16)),PASSWORD_DEFAULT),'tipo'=>'persona']);
    $pdo->prepare('UPDATE accounts SET eth_address=? WHERE id=?')->execute([$addr,$id]);
    pvAdd($id,100,'welcome_wallet'); creaWallets($pdo,$id,$sic);
    $pdo->prepare('INSERT INTO network_nodes(account_id,parent_sic,rank,qualified,vol_personal,vol_group,updated_at) VALUES(?,?,?,?,?,?,?)')->execute([$id,null,0,0,0,0,gmdate('c')]);
    ev('signup_wallet',$id,['addr'=>$addr]); $t=startSession($id); j(['ok'=>true,'sic'=>$sic,'nuovo'=>true,'csrf'=>csrfFor($t)]);
  } else { $t=startSession($a['id']); ev('wallet_login',$a['id']); j(['ok'=>true,'sic'=>$a['sic'],'csrf'=>csrfFor($t)]); }
}
if($action==='aggiorna_profilo'){
  $a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
  if(!csrfCheck()) j(['ok'=>false,'err'=>'token csrf mancante'],403);
  $nome=trim($in['nome']??$a['nome']); $cognome=trim($in['cognome']??$a['cognome']);
  $tel=trim($in['tel']??($a['tel']??'')); $rs=trim($in['ragione_sociale']??($a['ragione_sociale']??''));
  $cf=strtoupper(trim($in['codice_fiscale']??($a['codice_fiscale']??'')));
  $dn=trim($in['data_nascita']??($a['data_nascita']??'')); $ind=trim($in['indirizzo']??($a['indirizzo']??''));
  $piva=strtoupper(trim($in['piva']??($a['piva']??''))); $pec=strtolower(trim($in['pec']??($a['pec']??'')));
  $sdi=strtoupper(trim($in['sdi']??($a['sdi']??''))); $ateco=trim($in['ateco']??($a['ateco']??''));
  $dip=($in['dipendenti']??($a['dipendenti']??null)); $dip=($dip===''||$dip===null)?null:(int)$dip;
  $tipo=trim($in['tipo']??($a['tipo']??'persona')); if(!in_array($tipo,['persona','azienda']))$tipo='persona';
  if($piva!=='' && !preg_match('/^[A-Z]{0,2}[0-9]{11}$/',$piva)) j(['ok'=>false,'err'=>'partita iva non valida'],422);
  if($pec!=='' && !filter_var($pec,FILTER_VALIDATE_EMAIL)) j(['ok'=>false,'err'=>'pec non valida'],422);
  if($cf!=='' && !preg_match('/^[A-Z0-9]{11,16}$/',$cf)) j(['ok'=>false,'err'=>'codice fiscale non valido'],422);
  $pdo->prepare('UPDATE accounts SET nome=?,cognome=?,tel=?,ragione_sociale=?,codice_fiscale=?,data_nascita=?,indirizzo=?,piva=?,pec=?,sdi=?,ateco=?,dipendenti=?,tipo=? WHERE id=?')
    ->execute([$nome?:null,$cognome?:null,$tel?:null,$rs?:null,$cf?:null,$dn?:null,$ind?:null,$piva?:null,$pec?:null,$sdi?:null,$ateco?:null,$dip,$tipo,$a['id']]);
  $a['nome']=$nome;$a['cognome']=$cognome;$a['tel']=$tel;$a['ragione_sociale']=$rs;$a['codice_fiscale']=$cf;$a['data_nascita']=$dn;$a['indirizzo']=$ind;$a['piva']=$piva;$a['pec']=$pec;$a['sdi']=$sdi;$a['ateco']=$ateco;$a['dipendenti']=$dip;$a['tipo']=$tipo;
  $premio=false;$pvBonus=0;$mese=false;
  if(profiloCompleto($a)){
    $g=$pdo->prepare("SELECT 1 FROM pv_ledger WHERE account_id=? AND reason='profilo_completo_bonus' LIMIT 1"); $g->execute([$a['id']]);
    if(!$g->fetch()){
      pvAdd($a['id'],100,'profilo_completo_bonus'); $pvBonus=100; $premio=true;
      // un mese di membership Basic in regalo, entitlement idempotente
      $ek='mb_basic_'.$a['id'];
      $e=$pdo->prepare('SELECT id FROM entitlements WHERE ent_key=?'); $e->execute([$ek]);
      if(!$e->fetch()){
        $pdo->prepare('INSERT INTO entitlements(ent_key,account_id,product,status,provider,valid_until) VALUES(?,?,?,?,?,?)')
          ->execute([$ek,$a['id'],'membership_basic','active','omaggio_profilo',gmdate('c',time()+30*86400)]); $mese=true;
      }
      ev('bonus_profilo',$a['id'],['pv'=>100,'mese_basic'=>$mese]);
      if(!empty($a['ref_by'])){ $rf=$pdo->prepare('SELECT id FROM accounts WHERE sic=?'); $rf->execute([$a['ref_by']]); $padr=$rf->fetch();
        if($padr){ $rr='invito_attivo_'.$a['id']; $c2=$pdo->prepare('SELECT 1 FROM pv_ledger WHERE account_id=? AND reason=?'); $c2->execute([$padr['id'],$rr]);
          if(!$c2->fetch()){ pvAdd($padr['id'],100,$rr); ev('invito_attivo',$padr['id'],['figlio'=>$a['id']]); } } }
    }
  }
  j(['ok'=>true,'profilo_completo'=>profiloCompleto($a),'premio'=>$premio,'pv_bonus'=>$pvBonus,'mese_basic'=>$mese,'pv'=>pvBalance($a['id']),'regalo'=>($premio?'download/sicurissimo-addio-alla-burocrazia.pdf':null),'regalo_titolo'=>'Sicurissimo. Addio Burocrazia']);
}
if($action==='me'){ $a=currentAccount(); if(!$a) j(['ok'=>false],401);
  $ws=db()->prepare('SELECT tipo,wallet_code FROM wallets WHERE account_id=?'); $ws->execute([$a['id']]);
  $bonus=db()->prepare("SELECT 1 FROM pv_ledger WHERE account_id=? AND reason='profilo_completo_bonus' LIMIT 1"); $bonus->execute([$a['id']]); $bonusPreso=(bool)$bonus->fetch();
  $mb=db()->prepare("SELECT product,status,valid_until FROM entitlements WHERE account_id=? AND product='membership_basic' ORDER BY id DESC LIMIT 1"); $mb->execute([$a['id']]); $mbr=$mb->fetch();
  $completo=profiloCompleto($a);
  j(['ok'=>true,'sic'=>$a['sic'],'email'=>$a['email'],'tipo'=>$a['tipo'],'nome'=>$a['nome'],'cognome'=>$a['cognome'],'regione'=>$a['regione']??null,'ruolo'=>$a['ruolo']??'member','nodo_livello'=>$a['nodo_livello']??null,
     'ragione_sociale'=>$a['ragione_sociale']??null,'tel'=>$a['tel']??null,
     'codice_fiscale'=>$a['codice_fiscale']??null,'eth_address'=>$a['eth_address']??null,'avatar_url'=>$a['avatar_url']??null,
     'wallets'=>$ws->fetchAll(),'ref_link'=>'https://81plus.net/signup.html?ref='.$a['sic'],
     'profilo_completo'=>$completo,'completamento'=>profiloPerc($a),'data_nascita'=>$a['data_nascita']??null,'indirizzo'=>$a['indirizzo']??null,'piva'=>$a['piva']??null,'pec'=>$a['pec']??null,'sdi'=>$a['sdi']??null,'ateco'=>$a['ateco']??null,'dipendenti'=>$a['dipendenti']??null,'bonus_profilo'=>$bonusPreso,'membership'=>$mbr?:null,
     'pv'=>pvBalance($a['id']),'settore'=>settoreDaAteco($a['ateco']??''),'pwd_scaduta'=>pwdScaduta($a),'csrf'=>csrfFor($_COOKIE['sic_session'])]); }

if($action==='cambia_password'){
  $a=currentAccount(); if(!$a) j(['ok'=>false],401);
  if(!csrfCheck()) j(['ok'=>false,'err'=>'token csrf mancante'],403);
  $vecchia=$in['vecchia']??''; $nuova=$in['nuova']??'';
  if(!password_verify($vecchia,$a['pass_hash'])) j(['ok'=>false,'err'=>'la password attuale non è corretta'],403);
  if(strlen($nuova)<8) j(['ok'=>false,'err'=>'la nuova password deve avere almeno 8 caratteri'],422);
  if(password_verify($nuova,$a['pass_hash'])) j(['ok'=>false,'err'=>'la nuova password deve essere diversa'],422);
  $pdo->prepare('UPDATE accounts SET pass_hash=?,pwd_set_at=? WHERE id=?')->execute([password_hash($nuova,PASSWORD_DEFAULT),gmdate('c'),$a['id']]);
  // azione conseguente: chiudo le altre sessioni, tengo solo questa
  $pdo->prepare('DELETE FROM sessions WHERE account_id=? AND token<>?')->execute([$a['id'],$_COOKIE['sic_session']??'']);
  ev('pwd_change',$a['id']); mailGeneric($a['email'],'81+ , password aggiornata',"Ciao,\nla password del tuo accesso 81+ è stata cambiata.\nSe non sei stato tu, scrivi subito a info@81plus.net.\nLa direzione 81+");
  j(['ok'=>true,'msg'=>'Password aggiornata. Le altre sessioni sono state chiuse.']);
}
if($action==='cambia_email'){
  $a=currentAccount(); if(!$a) j(['ok'=>false],401);
  if(!csrfCheck()) j(['ok'=>false,'err'=>'token csrf mancante'],403);
  $nuova=trim(strtolower($in['email']??'')); $pass=$in['password']??'';
  if(!filter_var($nuova,FILTER_VALIDATE_EMAIL)) j(['ok'=>false,'err'=>'email non valida'],422);
  if(!password_verify($pass,$a['pass_hash'])) j(['ok'=>false,'err'=>'conferma con la tua password'],403);
  $ex=$pdo->prepare('SELECT 1 FROM accounts WHERE email=? AND id<>?'); $ex->execute([$nuova,$a['id']]); if($ex->fetch()) j(['ok'=>false,'err'=>'email già in uso'],409);
  $vecchia=$a['email'];
  $pdo->prepare('UPDATE accounts SET email=?,email_verified=0 WHERE id=?')->execute([$nuova,$a['id']]);
  ev('email_change',$a['id'],['da'=>$vecchia,'a'=>$nuova]);
  // azione conseguente: avviso al vecchio e al nuovo indirizzo
  mailGeneric($vecchia,'81+ , richiesta di cambio email',"Ciao,\nabbiamo ricevuto la richiesta di cambiare l’email del tuo accesso 81+ in ".$nuova.".\nSe non sei stato tu, scrivi subito a info@81plus.net.\nLa direzione 81+");
  mailGeneric($nuova,'81+ , nuova email collegata',"Ciao,\nquesto indirizzo e ora collegato al tuo accesso 81+.\nLa direzione 81+");
  j(['ok'=>true,'msg'=>'Email aggiornata. Ti abbiamo avvisato sul vecchio e sul nuovo indirizzo.']);
}
if($action==='recupera_password'){
  if(!rateOk('recpw:'.clientIp(),8,3600)) j(['ok'=>false,'err'=>'troppi tentativi, riprova più tardi'],429);
  $email=trim(strtolower($in['email']??''));
  // risposta sempre uguale, non rivelo se l’email esiste
  $msg=['ok'=>true,'msg'=>'Se l’indirizzo e registrato, ti arriva una email con il link per reimpostare la password.'];
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)) j($msg);
  $u=$pdo->prepare('SELECT id FROM accounts WHERE email=?'); $u->execute([$email]); $acc=$u->fetch();
  if($acc){
    $tok=bin2hex(random_bytes(24)); $exp=gmdate('c',time()+3600);
    $pdo->prepare('INSERT INTO pwd_resets(account_id,token,expires_at,used,created_at) VALUES(?,?,?,0,?)')->execute([$acc['id'],$tok,$exp,gmdate('c')]);
    $link='https://81plus.net/reset_password.html?token='.$tok;
    mailGeneric($email,'81+ , reimposta la tua password',"Ciao,\nper reimpostare la password apri questo link entro un’ora.\n".$link."\nSe non hai richiesto tu il reset, ignora questa email.\nLa direzione 81+");
    ev('pwd_reset_request',$acc['id']);
  }
  j($msg);
}
if($action==='reset_password'){
  $tok=$in['token']??''; $nuova=$in['nuova']??'';
  if(strlen($nuova)<8) j(['ok'=>false,'err'=>'la nuova password deve avere almeno 8 caratteri'],422);
  $r=$pdo->prepare('SELECT * FROM pwd_resets WHERE token=? AND used=0'); $r->execute([$tok]); $row=$r->fetch();
  if(!$row || strtotime($row['expires_at'])<time()) j(['ok'=>false,'err'=>'link non valido o scaduto, richiedine uno nuovo'],400);
  $pdo->prepare('UPDATE accounts SET pass_hash=?,pwd_set_at=? WHERE id=?')->execute([password_hash($nuova,PASSWORD_DEFAULT),gmdate('c'),$row['account_id']]);
  $pdo->prepare('UPDATE pwd_resets SET used=1 WHERE id=?')->execute([$row['id']]);
  $pdo->prepare('DELETE FROM sessions WHERE account_id=?')->execute([$row['account_id']]);
  ev('pwd_reset_done',$row['account_id']);
  $em=$pdo->prepare('SELECT email FROM accounts WHERE id=?'); $em->execute([$row['account_id']]); $e=$em->fetch();
  if($e) mailGeneric($e['email'],'81+ , password reimpostata',"Ciao,\nla tua password è stata reimpostata.\nSe non sei stato tu, scrivi subito a info@81plus.net.\nLa direzione 81+");
  j(['ok'=>true,'msg'=>'Password reimpostata. Ora puoi accedere.']);
}
if($action==='logout'){ if(!empty($_COOKIE['sic_session'])){ $pdo->prepare('DELETE FROM sessions WHERE token=?')->execute([$_COOKIE['sic_session']]); } setcookie('sic_session','',time()-3600,'/'); header('Location: index.html'); exit; }
j(['ok'=>false,'err'=>'azione sconosciuta'],404);
