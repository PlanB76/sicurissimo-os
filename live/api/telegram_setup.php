<?php
// Attiva il webhook e i comandi del bot. Si lancia una volta, protetto da ADMIN_KEY.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/telegram81.php';
$key=getenv('ADMIN_KEY')?:''; if($key===''||(($_GET['k']??'')!==$key)){ http_response_code(403); exit('chiave mancante'); }
header('Content-Type: text/plain; charset=utf-8');
$az=$_GET['az']??'setup';
if($az==='setup'){
  $secret=getenv('TELEGRAM_WEBHOOK_SECRET')?:hash_hmac('sha256','tg81',cfg()['app_secret']);
  $me=tgApi('getMe');
  echo "Bot, ".($me['result']['username']??'sconosciuto')."\n";
  $r=tgApi('setWebhook',['url'=>'https://81plus.net/api/telegram_webhook.php','secret_token'=>$secret,'allowed_updates'=>['message','channel_post','my_chat_member','poll_answer']]);
  echo "Webhook, ".(!empty($r['ok'])?'attivo':'errore '.($r['description']??''))."\n";
  $c=tgApi('setMyCommands',['commands'=>[
   ['command'=>'start','description'=>'Benvenuto e collegamento SIC ID'],
   ['command'=>'audit','description'=>'Scopri se sei in regola, 2 minuti'],
   ['command'=>'corsi','description'=>'Formazione certificata online'],
   ['command'=>'membership','description'=>'I tre livelli 81+'],
   ['command'=>'quiz','description'=>'Il quiz a premi in PV'],
   ['command'=>'pv','description'=>'Il tuo saldo punti'],
   ['command'=>'aiuto','description'=>'Parla con una persona']]]);
  echo "Comandi, ".(!empty($c['ok'])?'impostati':'errore')."\n";
  echo "\nOra aggiungi il bot come amministratore nel canale e nei gruppi, si registrano da soli.\n";
}
if($az==='test'){ $can=tgChatPerRuolo('canale'); if(!$can){ echo "Nessun canale registrato ancora, aggiungi il bot come admin del canale.\n"; } else { tgInvia($can['id'],'Prova tecnica 81+, il motore è acceso.'); echo "Messaggio di prova inviato al canale ".$can['titolo']."\n"; } }
if($az==='post'){ $st=tgStato(); unset($st['post_giorno']); tgSalvaStato($st); echo "Inviati ".tgPostDelGiorno()." post adesso.\n"; }
if($az==='quiz'){ $st=tgStato(); unset($st['quiz_giorno']); tgSalvaStato($st); echo "Inviati ".tgQuizDelGiorno(db())." quiz adesso.\n"; }
if($az==='stato'){ echo json_encode(['chats'=>tgChats(),'stato'=>tgStato()],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)."\n"; }
