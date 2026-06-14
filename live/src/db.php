<?php
// mb_substr polyfill, se l estensione mbstring non è attiva sul server
if(!function_exists('mb_substr')){ function mb_substr($s,$start,$length=null,$enc=null){ return $length===null?substr((string)$s,$start):substr((string)$s,$start,$length); } }
if(!function_exists('mb_strlen')){ function mb_strlen($s,$enc=null){ return strlen((string)$s); } }
if(!function_exists('mb_strtolower')){ function mb_strtolower($s,$enc=null){ return strtolower((string)$s); } }
if(!function_exists('mb_strtoupper')){ function mb_strtoupper($s,$enc=null){ return strtoupper((string)$s); } }
function db(){
  static $pdo=null; if($pdo) return $pdo;
  $c=require __DIR__.'/config.php';
  try{
    if($c['db_driver']==='mysql'){
      $dsn="mysql:host={$c['db_host']};port={$c['db_port']};dbname={$c['db_name']};charset=utf8mb4";
      $pdo=new PDO($dsn,$c['db_user'],$c['db_pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
    } else { throw new Exception('use sqlite'); }
  }catch(Throwable $e){
    @mkdir(dirname($c['sqlite']),0775,true);
    $pdo=new PDO('sqlite:'.$c['sqlite'],null,null,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
    $pdo->exec('PRAGMA journal_mode=WAL;');
  }
  return $pdo;
}
function drv(){ return db()->getAttribute(PDO::ATTR_DRIVER_NAME); }
function cfg(){ static $c=null; if(!$c)$c=require __DIR__.'/config.php'; return $c; }
function j($data,$code=200){ http_response_code($code); header('Content-Type: application/json'); echo json_encode($data,JSON_UNESCAPED_UNICODE); exit; }
function body(){ $r=json_decode(file_get_contents('php://input'),true); return is_array($r)?$r:$_POST; }
function ev($type,$accountId=null,$data=[]){ db()->prepare('INSERT INTO events(account_id,type,data,created_at) VALUES(?,?,?,?)')->execute([$accountId,$type,json_encode($data,JSON_UNESCAPED_UNICODE),gmdate('c')]); }
function clientIp(){ return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'; }
// rate limit semplice basato su DB. Ritorna true se entro i limiti.
function rateOk($key,$max,$windowSec){
  $pdo=db(); $now=time(); $k=substr($key,0,120);
  $pdo->prepare('DELETE FROM rate_limits WHERE ts < ?')->execute([$now-86400]);
  $st=$pdo->prepare('SELECT COUNT(*) c FROM rate_limits WHERE k=? AND ts > ?'); $st->execute([$k,$now-$windowSec]);
  if((int)$st->fetch()['c'] >= $max) return false;
  $pdo->prepare('INSERT INTO rate_limits(k,ts) VALUES(?,?)')->execute([$k,$now]); return true;
}
// CSRF stateless, legato al token di sessione e al segreto applicativo
function csrfFor($sessionToken){ return hash_hmac('sha256',$sessionToken, cfg()['app_secret']); }
function csrfCheck(){
  $tok=$_COOKIE['sic_session']??''; if(!$tok) return false;
  $sent=$_SERVER['HTTP_X_CSRF'] ?? (body()['_csrf'] ?? '');
  return is_string($sent) && hash_equals(csrfFor($tok),$sent);
}
// invio welcome reale, best effort. Richiede SPF DKIM DMARC sul dominio per arrivare in inbox.
function sendWelcome($email,$sic){
  $c=cfg(); $from=$c['welcome_from'];
  $sub='Benvenuto in 81+ , il tuo codice '.$sic;
  $body="Ciao,\nil tuo accesso a 81+ è attivo.\nIl tuo codice e ".$sic.".\nHai ricevuto 100 PV di benvenuto.\nLa direzione 81+";
  $headers='From: '.$from."\r\nReply-To: ".$c['info_from']."\r\nContent-Type: text/plain; charset=utf-8";
  return @mail($email,$sub,$body,$headers,'-f '.$from);
}
function mailGeneric($email,$sub,$body){
  $c=cfg(); $from=$c['welcome_from'];
  $headers='From: '.$from."\r\nReply-To: ".$c['info_from']."\r\nContent-Type: text/plain; charset=utf-8";
  return @mail($email,$sub,$body,$headers,'-f '.$from);
}
// upsert portabile mysql e sqlite, su una sola chiave unica
function upsert($table,$uniqueCol,$uniqueVal,$cols){
  $pdo=db();
  $st=$pdo->prepare("SELECT id FROM $table WHERE $uniqueCol=?"); $st->execute([$uniqueVal]); $row=$st->fetch();
  if($row){ $set=implode(',',array_map(fn($k)=>"$k=?",array_keys($cols)));
    $pdo->prepare("UPDATE $table SET $set WHERE $uniqueCol=?")->execute([...array_values($cols),$uniqueVal]);
  } else { $all=array_merge([$uniqueCol=>$uniqueVal],$cols);
    $ph=implode(',',array_fill(0,count($all),'?'));
    $pdo->prepare("INSERT INTO $table (".implode(',',array_keys($all)).") VALUES($ph)")->execute(array_values($all));
  }
}
