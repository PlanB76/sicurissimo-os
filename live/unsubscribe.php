<?php
// 81+ DISISCRIZIONE. Obbligatoria per legge su ogni email di marketing.
// Registra l opt-out e conferma. Nessun login richiesto.
require_once __DIR__.'/src/db.php';
$pdo=db();
try{ $pdo->query('SELECT 1 FROM email_optout LIMIT 1'); }catch(Throwable $e){
  $sq=stripos($pdo->getAttribute(PDO::ATTR_DRIVER_NAME),'sqlite')!==false;
  $pdo->exec($sq?'CREATE TABLE IF NOT EXISTS email_optout(id INTEGER PRIMARY KEY AUTOINCREMENT,email TEXT UNIQUE,created_at TEXT)'
    :'CREATE TABLE IF NOT EXISTS email_optout(id BIGINT AUTO_INCREMENT PRIMARY KEY,email VARCHAR(191) UNIQUE,created_at VARCHAR(40)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
}
$email=strtolower(trim(filter_var($_GET['e']??'',FILTER_SANITIZE_EMAIL)));
$fatto=false;
if($email && filter_var($email,FILTER_VALIDATE_EMAIL)){
  try{ $pdo->prepare('INSERT INTO email_optout(email,created_at) VALUES(?,?)')->execute([$email,gmdate('c')]); }catch(Throwable $e){}
  if(function_exists('ev')) @ev('email_optout',null,['email'=>substr(md5($email),0,10)]);
  $fatto=true;
}
?>
<!DOCTYPE html>
<html lang="it"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>81+ · Disiscrizione</title><link rel="icon" href="favicon.svg">
<style>body{background:#0A0A0B;color:#c4c4cc;font-family:Arial,sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;text-align:center;padding:20px}
.box{max-width:440px;background:#141417;border:1px solid #26262b;border-radius:16px;padding:34px 28px}
h1{color:#fff;font-size:22px;margin:0 0 10px}p{font-size:14px;line-height:1.6}a{color:#FB6B00}</style></head>
<body><div class="box">
<?php if($fatto): ?>
<h1>Fatto, sei fuori dalla lista.</h1>
<p>Non riceverai più le nostre email di aggiornamento. Se cambi idea, ti basta registrarti di nuovo su <a href="https://81plus.net">81plus.net</a>. Grazie per questi anni insieme.</p>
<?php else: ?>
<h1>Indirizzo non valido.</h1>
<p>Il link non contiene un indirizzo email valido. Scrivi a info@81plus.net e ti togliamo dalla lista a mano, subito.</p>
<?php endif; ?>
</div></body></html>
