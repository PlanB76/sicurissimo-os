<?php
// SSO centrale. HUB1 emette un token firmato, il dominio satellite lo consuma e apre la sessione.
// Flusso: satellite -> https://81plus.net/api/sso.php?action=token&redirect=https://81plus.it/sso_ritorno
//         HUB1 (utente loggato) -> redirect con ?sso=TOKEN -> satellite chiama consume.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$action=$_GET['action']??''; $pdo=db(); $c=cfg();
$ALLOWED=['81plus.net','81plus.it','81plus.network','81plus.club','81plus.zone','81plus.academy','81plus.online','sicurissimo.online'];
function ssoSign($payload){ return rtrim(strtr(base64_encode($payload),'+/','-_'),'=').'.'.hash_hmac('sha256',$payload,cfg()['app_secret']); }
function ssoOpen($tok){ $p=explode('.',$tok); if(count($p)!==2) return null; $payload=base64_decode(strtr($p[0],'-_','+/'));
  if(!hash_equals(hash_hmac('sha256',$payload,cfg()['app_secret']),$p[1])) return null; $d=json_decode($payload,true);
  if(!$d||($d['exp']??0)<time()) return null; return $d; }
if($action==='token'){
  $a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato su HUB1'],401);
  $redirect=$_GET['redirect']??''; $host=parse_url($redirect,PHP_URL_HOST)?:'';
  $okHost=false; foreach($ALLOWED as $d){ if($host===$d||substr($host,-strlen('.'.$d))==='.'.$d) $okHost=true; }
  if(!$okHost) j(['ok'=>false,'err'=>'dominio non autorizzato'],403);
  $tok=ssoSign(json_encode(['sic'=>$a['sic'],'exp'=>time()+120,'n'=>bin2hex(random_bytes(8))]));
  header('Location: '.$redirect.(strpos($redirect,'?')===false?'?':'&').'sso='.urlencode($tok)); exit;
}
if($action==='consume'){
  $d=ssoOpen($_GET['token']??''); if(!$d) j(['ok'=>false,'err'=>'token non valido o scaduto'],401);
  $st=$pdo->prepare('SELECT id FROM accounts WHERE sic=?'); $st->execute([$d['sic']]); $a=$st->fetch();
  if(!$a) j(['ok'=>false,'err'=>'account non trovato'],404);
  $t=startSession($a['id']); ev('sso_consume',$a['id']); j(['ok'=>true,'sic'=>$d['sic'],'csrf'=>csrfFor($t)]);
}
j(['ok'=>false,'err'=>'azione sconosciuta'],404);
