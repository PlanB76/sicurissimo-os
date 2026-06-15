<?php
// 81+ WEBHOOK TELEGRAM. Riceve tutto quello che succede nelle chat e risponde da solo.
// Protetto dal secret token che Telegram rimanda a ogni chiamata.
require_once __DIR__.'/../src/db.php';
require_once __DIR__.'/../src/telegram81.php';

$secretAtteso=getenv('TELEGRAM_WEBHOOK_SECRET')?:hash_hmac('sha256','tg81',cfg()['app_secret']);
$secretRicevuto=$_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN']??'';
if(!hash_equals($secretAtteso,$secretRicevuto)){ http_response_code(403); exit('no'); }

$up=json_decode(file_get_contents('php://input'),true)?:[];
$pdo=db();
http_response_code(200); // a Telegram si risponde subito, sempre

// ===== il bot viene aggiunto o tolto da una chat: registro automatico =====
if(isset($up['my_chat_member'])){
  $m=$up['my_chat_member'];
  $stato=$m['new_chat_member']['status']??'member';
  tgRegistraChat($m['chat'],in_array($stato,['left','kicked'])?'left':'member');
  echo 'ok'; exit;
}

// ===== risposta a un quiz: PV a chi indovina =====
if(isset($up['poll_answer'])){ tgRispostaQuiz($pdo,$up['poll_answer']); echo 'ok'; exit; }

$msg=$up['message']??($up['channel_post']??null);
if(!$msg){ echo 'ok'; exit; }
$chat=$msg['chat']??[]; $chatId=$chat['id']??0; $tipo=$chat['type']??'';
$testo=trim($msg['text']??'');
$da=$msg['from']??[]; $nome=trim($da['first_name']??'amico');

// registro anche da qui, così nessuna chat sfugge
if(in_array($tipo,['group','supergroup','channel'])) tgRegistraChat($chat,'member');

// ===== benvenuto ai nuovi membri =====
if(!empty($msg['new_chat_members'])){
  $cont=tgContenuti(); $reg=null;
  foreach(tgChats()['chats'] as $c){ if((string)$c['id']===(string)$chatId){ $reg=$c; break; } }
  $ruolo=$reg['ruolo']??'community';
  $benv=$cont['benvenuti'][$ruolo]??$cont['benvenuti']['community'];
  foreach($msg['new_chat_members'] as $nu){
    if(!empty($nu['is_bot'])) continue;
    $chi=trim($nu['first_name']??'');
    tgInvia($chatId,"Ciao <b>".htmlspecialchars($chi)."</b>. ".$benv,[[tgBtn("Fai l'audit gratuito di 2 minuti",'https://81plus.net/audit.html')]]);
  }
  echo 'ok'; exit;
}

// ===== comandi =====
if($testo!=='' && $testo[0]==='/'){
  $parti=explode(' ',$testo,2); $cmd=strtolower(explode('@',$parti[0])[0]); $arg=trim($parti[1]??'');
  if($cmd==='/start'){
    if(stripos($arg,'BIND-')===0){
      $acc=tgAggancia($pdo,strtoupper($arg),$da);
      if($acc){
        tgInvia($chatId,"<b>Collegamento riuscito.</b>\n\nIl tuo Telegram è ora agganciato al SIC ID <b>".htmlspecialchars($acc['sic'])."</b> in modo univoco, e 50 PV sono appena entrati nel tuo wallet.\n\nDa adesso. Rispondi giusto ai quiz nei gruppi e guadagni 10 PV a risposta. Quando attivi una membership, il gruppo privato del tuo livello te lo consegno io, qui, con un link personale.",[[tgBtn('Apri la tua dashboard','https://81plus.net/login.html')]]);
      } else {
        tgInvia($chatId,"Il codice non è valido o è scaduto. Apri la dashboard, sezione Telegram, e genera un codice nuovo, vale trenta minuti.",[[tgBtn('Vai alla dashboard','https://81plus.net/login.html')]]);
      }
    } else {
      tgInvia($chatId,"Ciao ".htmlspecialchars($nome).", sono l'assistente 81+ di Sicurissimo.\n\nCosa posso fare per te.\n/audit per scoprire in due minuti se sei in regola\n/corsi per la formazione certificata\n/membership per i tre livelli\n/quiz per come funziona il quiz a premi\n/pv per il tuo saldo punti\n/aiuto per parlare con una persona vera\n\nE se hai un account 81+, collegalo dalla dashboard, vale 50 PV.",[[tgBtn("Fai l'audit gratuito",'https://81plus.net/audit.html')]]);
    }
    echo 'ok'; exit;
  }
  if($cmd==='/audit'){ tgInvia($chatId,"Trenta controlli su sicurezza, HACCP e privacy. Due minuti, gratis, risultato immediato.",[[tgBtn("Fai l'audit adesso",'https://81plus.net/audit.html')]]); echo 'ok'; exit; }
  if($cmd==='/corsi'){ tgInvia($chatId,"Formazione certificata online, attestati validi a tuo nome, su piattaforme accreditate e riconosciute, MIM e Regione Lazio. Li fai da casa, quando vuoi.",[[tgBtn('Scegli il corso','https://81plus.net/api/track.php?to=anfos&src=tg'),tgBtn('Corsi di crescita','https://81plus.net/api/track.php?to=lezione&src=tg')]]); echo 'ok'; exit; }
  if($cmd==='/membership'){ tgInvia($chatId,"Tre livelli, un percorso. Basic 49, Pro 99, Elite 149 euro al mese. Dentro, il sistema che tiene d'occhio le tue scadenze, i documenti e la community del tuo livello.",[[tgBtn('Scopri i tre livelli','https://81plus.net/index.html#pricing')]]); echo 'ok'; exit; }
  if($cmd==='/quiz'){ tgInvia($chatId,"Ogni giorno alle 13 esce il quiz 81+ nei gruppi. Rispondi giusto e guadagni 10 PV veri nel tuo wallet, la domenica esce la classifica della settimana.\n\nPer raccogliere i punti devi avere il SIC ID collegato, si fa dalla dashboard in dieci secondi e il collegamento da solo vale 50 PV."); echo 'ok'; exit; }
  if($cmd==='/pv'){
    $acc=tgAccountDaTelegram($pdo,$da['id']??0);
    if($acc&&function_exists('pvBalance')) tgInvia($chatId,"SIC <b>".htmlspecialchars($acc['sic'])."</b>\nSaldo, <b>".pvBalance($acc['id'])." PV</b>.\n\nUn PV vale un euro di sconto sui servizi dell'ecosistema.");
    else tgInvia($chatId,"Non vedo ancora un SIC ID collegato a questo Telegram. Si fa dalla dashboard, sezione Telegram, e il collegamento vale 50 PV.",[[tgBtn('Collega il tuo account','https://81plus.net/login.html')]]);
    echo 'ok'; exit;
  }
  if($cmd==='/aiuto'){ tgInvia($chatId,"Siamo qui. Scrivici su WhatsApp e risponde una persona vera del mestiere.",[[tgBtn('Apri WhatsApp','https://wa.me/393388771737')]]); echo 'ok'; exit; }
  echo 'ok'; exit;
}

// ===== risposta AI: sempre in privato, nei gruppi quando il bot è chiamato in causa =====
$menzionato=stripos($testo,'@'.tgBotUser())!==false;
$rispostaABot=isset($msg['reply_to_message']['from']['username']) && strcasecmp($msg['reply_to_message']['from']['username'],tgBotUser())===0;
if($testo!=='' && ($tipo==='private' || $menzionato || $rispostaABot)){
  if(!function_exists('rateOk') || rateOk('tgai:'.$chatId,8,3600)){
    $dom=trim(str_ireplace('@'.tgBotUser(),'',$testo));
    $r=tgRispostaAI($dom,$nome);
    tgInvia($chatId,$r."\n\n<i>Indicazione generale, non consulenza legale.</i>");
  }
}
echo 'ok';
