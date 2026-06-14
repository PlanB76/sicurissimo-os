<?php
require_once __DIR__.'/db.php';
function currentAccount(){
  $tok=$_COOKIE['sic_session']??''; if(!$tok) return null;
  $st=db()->prepare('SELECT a.* FROM sessions s JOIN accounts a ON a.id=s.account_id WHERE s.token=? AND s.expires_at>?');
  $st->execute([$tok,gmdate('c')]); return $st->fetch()?:null;
}
function startSession($accountId){
  $c=cfg(); $tok=bin2hex(random_bytes(32)); $exp=gmdate('c', time()+$c['session_ttl']*86400);
  db()->prepare('INSERT INTO sessions(account_id,token,created_at,expires_at) VALUES(?,?,?,?)')->execute([$accountId,$tok,gmdate('c'),$exp]);
  setcookie('sic_session',$tok,['expires'=>time()+$c['session_ttl']*86400,'path'=>'/','httponly'=>true,'samesite'=>'Lax','secure'=>($c['env']==='production')]);
  return $tok;
}
function pvBalance($id){ $st=db()->prepare('SELECT COALESCE(SUM(delta),0) b FROM pv_ledger WHERE account_id=?'); $st->execute([$id]); return (int)$st->fetch()['b']; }
function pvAdd($id,$d,$reason,$ref=null){ db()->prepare('INSERT INTO pv_ledger(account_id,delta,reason,ref,created_at) VALUES(?,?,?,?,?)')->execute([$id,$d,$reason,$ref,gmdate('c')]); }
