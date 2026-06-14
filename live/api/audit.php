<?php
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/sic.php'; require_once __DIR__.'/../src/auth_lib.php'; require_once __DIR__.'/../src/enroll.php';
$pdo=db(); $in=body();
if(!rateOk('audit:'.clientIp(),20,3600)) j(['ok'=>false,'err'=>'troppe richieste, riprova più tardi'],429);
$email=trim(strtolower($in['email']??'')); $nome=trim($in['nome']??'');
if(!filter_var($email,FILTER_VALIDATE_EMAIL)||$nome==='') j(['ok'=>false,'err'=>'nome ed email richiesti'],422);
// captcha opzionale: se configurato e mancante, blocca
if(cfg()['captcha_secret'] && empty($in['captcha'])) j(['ok'=>false,'err'=>'verifica anti spam richiesta'],422);
$risposte=$in['risposte']??[]; $tot=30; $ok=0; foreach($risposte as $r){ if($r===true||$r==='si'||$r==='1') $ok++; }
$score=(int)round($ok/$tot*100); $gaps=max(0,$tot-$ok);
enrollUniversale($email,$nome,'audit'); $leadId=null;
$q=$pdo->prepare('SELECT id,sic FROM accounts WHERE email=?'); $q->execute([$email]); $acc=$q->fetch();
if(!$acc){
  for($t=0;$t<5;$t++){ $sic=generaSic($pdo); $setTok=bin2hex(random_bytes(20));
    try{ $pdo->prepare('INSERT INTO accounts(sic,email,pass_hash,tipo,nome,kyc_level,status,email_verified,set_token,created_at) VALUES(?,?,?,?,?,?,?,?,?,?)')
        ->execute([$sic,$email,'',('persona'),$nome,0,'da_attivare',0,$setTok,gmdate('c')]); break;
    }catch(PDOException $e){ if($t==4) throw $e; } }
  $accId=$pdo->lastInsertId(); pvAdd($accId,100,'welcome_audit'); sendWelcome($email,$sic);
  // doppio opt in, il link per impostare la password va inviato via email: /set_password.html?token=$setTok
} else { $accId=$acc['id']; $sic=$acc['sic']; }
$pdo->prepare('INSERT INTO audit_runs(account_id,lead_id,score,gaps,payload,created_at) VALUES(?,?,?,?,?,?)')->execute([$accId,$leadId,$score,json_encode(['gaps'=>$gaps]),json_encode($risposte,JSON_UNESCAPED_UNICODE),gmdate('c')]);
ev('audit_done',$accId,['score'=>$score,'gaps'=>$gaps]); j(['ok'=>true,'sic'=>$sic,'score'=>$score,'lacune'=>$gaps,'pv'=>100,'nota'=>'Ti abbiamo inviato una email per impostare la password e confermare l’indirizzo']);
