<?php
// 81+ MOTORE TELEGRAM. Il social principale dell'ecosistema, automatico al cento per cento.
// Il token vive SOLO nella variabile d'ambiente TELEGRAM_BOT_TOKEN, mai nel codice.
// Con TELEGRAM_FAKE=1 il motore scrive sul log invece di chiamare Telegram, per i collaudi.
//
// Cosa fa da solo. Si registra nelle chat dove aggiungi il bot. Pubblica il palinsesto
// editoriale ogni giorno con l'AI. Lancia il quiz a premi e accredita PV veri a chi
// risponde giusto. Dà il benvenuto ai nuovi. Risponde alle domande con l'AI. Consegna
// il gruppo privato del rank a chi attiva la membership, con link monouso mai pubblici.
// Pubblica gli articoli nuovi del blog. Aggancia il SIC-ID all'account Telegram.

require_once __DIR__.'/db.php';
require_once __DIR__.'/auth_lib.php';

function tgToken(){ return getenv('TELEGRAM_BOT_TOKEN')?:''; }
function tgBotUser(){ return getenv('TELEGRAM_BOT_USERNAME')?:'ottantuno_bot'; }
function tgFake(){ return getenv('TELEGRAM_FAKE')==='1' || tgToken()===''; }
function tgRoot(){ return realpath(__DIR__.'/..'); }

function tgLog($riga){
  $f=tgRoot().'/data/telegram_log.json';
  $l=json_decode(@file_get_contents($f),true)?:[];
  $l[]=['t'=>gmdate('c'),'r'=>$riga];
  if(count($l)>300)$l=array_slice($l,-300);
  @file_put_contents($f,json_encode($l,JSON_UNESCAPED_UNICODE));
}

function tgApi($metodo,$params=[]){
  if(tgFake()){ tgLog(['FAKE',$metodo,$params]); return ['ok'=>true,'fake'=>true,'result'=>['message_id'=>random_int(1,99999),'invite_link'=>'https://t.me/+FAKE'.substr(md5(json_encode($params)),0,8),'poll'=>['id'=>'fakepoll'.random_int(1000,9999)]]]; }
  $ch=curl_init('https://api.telegram.org/bot'.tgToken().'/'.$metodo);
  curl_setopt_array($ch,[CURLOPT_POST=>1,CURLOPT_POSTFIELDS=>json_encode($params),CURLOPT_HTTPHEADER=>['Content-Type: application/json'],CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>12]);
  $r=curl_exec($ch); curl_close($ch);
  $d=json_decode($r,true)?:['ok'=>false,'raw'=>$r];
  if(empty($d['ok'])) tgLog(['ERR',$metodo,$d['description']??'']);
  return $d;
}

// ===== REGISTRO CHAT, si riempie da solo dal webhook =====
function tgChats(){ return json_decode(@file_get_contents(tgRoot().'/data/telegram_chats.json'),true)?:['chats'=>[]]; }
function tgSalvaChats($d){ @file_put_contents(tgRoot().'/data/telegram_chats.json',json_encode($d,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)); }
function tgRuoloDaTitolo($titolo,$tipo){
  $t=strtolower($titolo??'');
  if(strpos($t,'elite')!==false) return 'elite';
  if(strpos($t,'pro')!==false) return 'pro';
  if(strpos($t,'basic')!==false) return 'basic';
  if($tipo==='channel') return 'canale';
  return 'community';
}
function tgRegistraChat($chat,$stato='member'){
  $d=tgChats(); $id=(string)$chat['id'];
  foreach($d['chats'] as &$c){ if((string)$c['id']===$id){ $c['titolo']=$chat['title']??$c['titolo']; $c['stato']=$stato; tgSalvaChats($d); return; } }
  $d['chats'][]=['id'=>$chat['id'],'titolo'=>$chat['title']??($chat['username']??'privata'),'tipo'=>$chat['type']??'group','ruolo'=>tgRuoloDaTitolo($chat['title']??'',$chat['type']??''),'stato'=>$stato,'registrata'=>gmdate('c')];
  tgSalvaChats($d); tgLog(['CHAT REGISTRATA',$chat['title']??$chat['id']]);
}
function tgChatPerRuolo($ruolo){
  foreach(tgChats()['chats'] as $c) if(($c['ruolo']??'')===$ruolo && ($c['stato']??'')!=='left') return $c;
  return null;
}
function tgChatsPubbliche(){
  $out=[]; foreach(tgChats()['chats'] as $c) if(in_array($c['ruolo']??'',['canale','community']) && ($c['stato']??'')!=='left') $out[]=$c;
  return $out;
}

// ===== INVIO =====
function tgInvia($chatId,$testo,$bottoni=null){
  $p=['chat_id'=>$chatId,'text'=>$testo,'parse_mode'=>'HTML','disable_web_page_preview'=>false];
  if($bottoni) $p['reply_markup']=['inline_keyboard'=>$bottoni];
  return tgApi('sendMessage',$p);
}
function tgBtn($testo,$url){ return ['text'=>$testo,'url'=>$url]; }

// ===== CONTENUTI E STATO =====
function tgContenuti(){ return json_decode(@file_get_contents(tgRoot().'/data/telegram_contenuti.json'),true)?:[]; }
function tgStato(){ return json_decode(@file_get_contents(tgRoot().'/data/telegram_stato.json'),true)?:[]; }
function tgSalvaStato($s){ @file_put_contents(tgRoot().'/data/telegram_stato.json',json_encode($s,JSON_UNESCAPED_UNICODE)); }

// riscrittura AI del post del giorno, con paletti rigidi. Se l'AI non c'è, parte il testo curato.
function tgAiRiscrivi($testo){
  $key=getenv('GROQ_API_KEY')?:''; if($key===''||getenv('TELEGRAM_AI')==='0') return $testo;
  $body=json_encode(['model'=>'llama-3.3-70b-versatile','temperature'=>0.7,'max_tokens'=>400,'messages'=>[
    ['role'=>'system','content'=>'Riscrivi il post per un canale Telegram italiano di sicurezza sul lavoro, HACCP e privacy. Regole assolute. Mantieni intatti tutti i numeri, le norme citate e i link, non aggiungerne di nuovi e non inventare dati o articoli di legge. Dai del tu, frasi brevi, voce attiva, tono diretto da imprenditore a imprenditore. Solo virgole e punti. Massimo 120 parole. Mantieni la chiamata all azione finale identica. Rispondi solo col testo del post.'],
    ['role'=>'user','content'=>$testo]]]);
  $ch=curl_init('https://api.groq.com/openai/v1/chat/completions');
  curl_setopt_array($ch,[CURLOPT_POST=>1,CURLOPT_POSTFIELDS=>$body,CURLOPT_HTTPHEADER=>['Content-Type: application/json','Authorization: Bearer '.$key],CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>20]);
  $r=curl_exec($ch); curl_close($ch);
  $d=json_decode($r,true); $out=trim($d['choices'][0]['message']['content']??'');
  // paranoia: se l'AI ha toccato i link o svuotato il testo, si usa l'originale
  if($out==='' || substr_count($out,'http')!==substr_count($testo,'http')) return $testo;
  return $out;
}

// ===== PALINSESTO EDITORIALE, il ritmo della settimana =====
function tgPostDelGiorno(){
  $tz=new DateTimeZone('Europe/Rome'); $ora=new DateTime('now',$tz);
  $dow=(int)$ora->format('N'); $oggi=$ora->format('Y-m-d');
  $st=tgStato(); if(($st['post_giorno']??'')===$oggi) return 0;
  $cont=tgContenuti(); $banco=$cont['palinsesto'][(string)$dow]??null; if(!$banco) return 0;
  $idx=((int)$ora->format('z'))%count($banco['varianti']);
  $testo=tgAiRiscrivi($banco['varianti'][$idx]);
  $bottoni=[[tgBtn($banco['cta_testo'],$banco['cta_url'])]];
  $n=0;
  foreach(tgChatsPubbliche() as $c){ tgInvia($c['id'],$testo,$bottoni); $n++; }
  $st['post_giorno']=$oggi; $st['post_totali']=($st['post_totali']??0)+$n; tgSalvaStato($st);
  return $n;
}

// ===== QUIZ A PREMI, gamification vera con PV =====
function tgQuizDelGiorno($pdo){
  $tz=new DateTimeZone('Europe/Rome'); $ora=new DateTime('now',$tz);
  $oggi=$ora->format('Y-m-d');
  $st=tgStato(); if(($st['quiz_giorno']??'')===$oggi) return 0;
  $cont=tgContenuti(); $banco=$cont['quiz']??[]; if(!$banco) return 0;
  $q=$banco[((int)$ora->format('z'))%count($banco)];
  $st['quiz_giorno']=$oggi; $n=0;
  foreach(tgChatsPubbliche() as $c){
    // nei gruppi il quiz non è anonimo, così chi risponde giusto prende i PV
    $anon=($c['tipo']==='channel');
    $r=tgApi('sendPoll',['chat_id'=>$c['id'],'question'=>'QUIZ 81+ · '.$q['domanda'],'options'=>$q['opzioni'],'type'=>'quiz','correct_option_id'=>$q['giusta'],'is_anonymous'=>$anon,'explanation'=>$q['spiega'].' Rispondi giusto nei gruppi e guadagni 10 PV, collega il tuo SIC ID con /start.']);
    if(!empty($r['ok'])&&!$anon){ $st['quiz_aperti'][(string)($r['result']['poll']['id']??'')]=['giusta'=>$q['giusta'],'data'=>$oggi]; }
    $n++;
  }
  tgSalvaStato($st);
  return $n;
}
function tgRispostaQuiz($pdo,$pollAnswer){
  $st=tgStato(); $pid=(string)($pollAnswer['poll_id']??'');
  $info=$st['quiz_aperti'][$pid]??null; if(!$info) return;
  $scelte=$pollAnswer['option_ids']??[];
  if(!in_array((int)$info['giusta'],$scelte,true)) return;
  $uid=(int)($pollAnswer['user']['id']??0); if(!$uid) return;
  $acc=tgAccountDaTelegram($pdo,$uid); if(!$acc) return;
  $reason='g_quiz_'.str_replace('-','',$info['data']);
  $c=$pdo->prepare('SELECT 1 FROM pv_ledger WHERE account_id=? AND reason=?'); $c->execute([$acc['id'],$reason]);
  if(!$c->fetch() && function_exists('pvAdd')){ pvAdd($acc['id'],10,$reason); tgLog(['QUIZ PV',$acc['sic']??$acc['id']]); }
}

// ===== CLASSIFICA SETTIMANALE, la domenica =====
function tgClassificaSettimana($pdo){
  $tz=new DateTimeZone('Europe/Rome'); $ora=new DateTime('now',$tz);
  if((int)$ora->format('N')!==7) return 0;
  $sett=$ora->format('o\WW'); $st=tgStato(); if(($st['classifica']??'')===$sett) return 0;
  $da=gmdate('c',time()-7*86400);
  $q=$pdo->prepare("SELECT a.sic, SUM(l.delta) p FROM pv_ledger l JOIN accounts a ON a.id=l.account_id WHERE l.reason LIKE 'g_quiz_%' AND l.created_at>=? GROUP BY a.sic ORDER BY p DESC LIMIT 5");
  try{ $q->execute([$da]); $top=$q->fetchAll(); }catch(Exception $e){ $top=[]; }
  if($top){
    $righe=[]; $pos=1; foreach($top as $r){ $righe[]=$pos.'. '.substr($r['sic'],0,8).'··· con '.$r['p'].' PV'; $pos++; }
    $testo="<b>CLASSIFICA QUIZ DELLA SETTIMANA</b>\n\n".implode("\n",$righe)."\n\nOgni risposta giusta vale 10 PV veri nel tuo wallet. Collega il tuo SIC ID scrivendo /start al bot e gioca da protagonista la prossima settimana.";
    foreach(tgChatsPubbliche() as $c) tgInvia($c['id'],$testo);
  }
  $st['classifica']=$sett; tgSalvaStato($st);
  return count($top);
}

// ===== BLOG SUL CANALE, automatico =====
function tgPubblicaBlogNuovi(){
  $st=tgStato(); $gia=$st['blog_pubblicati']??[];
  $blog=json_decode(@file_get_contents(tgRoot().'/data/blog.json'),true)?:['posts'=>[]];
  $n=0;
  foreach(array_slice($blog['posts'],0,3) as $p){
    $slug=$p['slug']??''; if(!$slug||in_array($slug,$gia,true)) continue;
    $testo="<b>".htmlspecialchars($p['titolo'])."</b>\n\n".htmlspecialchars($p['estratto'])."\n\nLeggi tutto sul blog 81+.";
    $bottoni=[[tgBtn('Apri l\'articolo','https://81plus.net/blog/'.$slug.'.html')]];
    foreach(tgChatsPubbliche() as $c){ tgInvia($c['id'],$testo,$bottoni); }
    $gia[]=$slug; $n++;
  }
  $st['blog_pubblicati']=array_slice($gia,-100); tgSalvaStato($st);
  return $n;
}

// ===== AGGANCIO SIC-ID <-> TELEGRAM, univoco =====
function tgTabelle($pdo){
  try{ $pdo->query('SELECT telegram_id FROM accounts LIMIT 1'); }
  catch(Exception $e){ try{ $pdo->exec("ALTER TABLE accounts ADD COLUMN telegram_id VARCHAR(32) NULL"); }catch(Exception $x){} }
  try{ $pdo->query('SELECT 1 FROM tg_links LIMIT 1'); }
  catch(Exception $e){
    $drv=$pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    if(stripos($drv,'sqlite')!==false) $pdo->exec('CREATE TABLE IF NOT EXISTS tg_links (code TEXT PRIMARY KEY, account_id INTEGER, expires_at TEXT)');
    else $pdo->exec('CREATE TABLE IF NOT EXISTS tg_links (code VARCHAR(40) PRIMARY KEY, account_id BIGINT NOT NULL, expires_at VARCHAR(40)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
  }
}
function tgCodiceAggancio($pdo,$accountId){
  tgTabelle($pdo);
  $code='BIND-'.strtoupper(bin2hex(random_bytes(5)));
  $pdo->prepare('INSERT INTO tg_links(code,account_id,expires_at) VALUES(?,?,?)')->execute([$code,$accountId,gmdate('c',time()+1800)]);
  return $code;
}
function tgAggancia($pdo,$code,$tgUser){
  tgTabelle($pdo);
  $q=$pdo->prepare('SELECT * FROM tg_links WHERE code=?'); $q->execute([$code]); $r=$q->fetch();
  if(!$r||strtotime($r['expires_at'])<time()) return null;
  $pdo->prepare('DELETE FROM tg_links WHERE code=?')->execute([$code]);
  $uid=(string)($tgUser['id']??''); if(!$uid) return null;
  // univoco nei due sensi: un telegram per un account
  $pdo->prepare('UPDATE accounts SET telegram_id=NULL WHERE telegram_id=?')->execute([$uid]);
  $pdo->prepare('UPDATE accounts SET telegram_id=? WHERE id=?')->execute([$uid,$r['account_id']]);
  $a=$pdo->prepare('SELECT * FROM accounts WHERE id=?'); $a->execute([$r['account_id']]); $acc=$a->fetch();
  $reason='telegram_collegato';
  $c=$pdo->prepare('SELECT 1 FROM pv_ledger WHERE account_id=? AND reason=?'); $c->execute([$acc['id'],$reason]);
  if(!$c->fetch() && function_exists('pvAdd')) pvAdd($acc['id'],50,$reason);
  if(function_exists('ev')) ev('telegram_collegato',$acc['id']);
  return $acc;
}
function tgAccountDaTelegram($pdo,$tgId){
  tgTabelle($pdo);
  $q=$pdo->prepare('SELECT * FROM accounts WHERE telegram_id=?'); $q->execute([(string)$tgId]);
  return $q->fetch()?:null;
}

// ===== CONSEGNA DEL GRUPPO PRIVATO DEL RANK, link monouso mai pubblici =====
function tgConsegnaGruppo($pdo,$accountId,$product){
  $ruolo=null;
  $p=strtolower($product);
  if(strpos($p,'elite')!==false)$ruolo='elite'; elseif(strpos($p,'pro')!==false)$ruolo='pro'; elseif(strpos($p,'basic')!==false||strpos($p,'membership')!==false)$ruolo='basic';
  if(!$ruolo) return false;
  $chat=tgChatPerRuolo($ruolo); if(!$chat) { tgLog(['GRUPPO MANCANTE',$ruolo]); return false; }
  $a=$pdo->prepare('SELECT * FROM accounts WHERE id=?'); $a->execute([$accountId]); $acc=$a->fetch();
  if(!$acc) return false;
  $link=tgApi('createChatInviteLink',['chat_id'=>$chat['id'],'member_limit'=>1,'name'=>'81+ '.$acc['sic']]);
  $url=$link['result']['invite_link']??null; if(!$url) return false;
  $testo="<b>Benvenuto nel livello ".strtoupper($ruolo)."</b>\n\nLa tua membership è attiva e questo è il tuo ingresso personale al gruppo privato. Il link vale per una sola persona, te.\n\nDentro trovi la community del tuo livello, gli annunci riservati e i vantaggi del rank.";
  if(!empty($acc['telegram_id'])){
    tgInvia($acc['telegram_id'],$testo,[[tgBtn('Entra nel gruppo '.strtoupper($ruolo),$url)]]);
  }
  // in ogni caso parte anche l'email con il link, così nessuno resta fuori
  if(function_exists('mailGeneric')) @mailGeneric($acc['email'],'81+ , il tuo gruppo privato '.strtoupper($ruolo),"La tua membership è attiva.\n\nQuesto è il tuo ingresso personale al gruppo Telegram privato del tuo livello, vale per una sola persona:\n".$url."\n\nSe non hai ancora collegato Telegram al tuo SIC ID, fallo dalla dashboard, vale 50 PV.\n\nLa direzione 81+");
  tgLog(['GRUPPO CONSEGNATO',$ruolo,$acc['sic']??$accountId]);
  return true;
}

// ===== LINK DI INVITO AUTOMATICI per la dashboard, solo spazi pubblici =====
function tgSincronizzaLinkPubblici(){
  $f=tgRoot().'/data/community.json';
  $com=json_decode(@file_get_contents($f),true); if(!$com) return 0;
  $n=0;
  foreach(tgChats()['chats'] as $c){
    if(($c['ruolo']??'')!=='community'&&($c['ruolo']??'')!=='canale') continue;
    if(($c['stato']??'')==='left') continue;
    $r=tgApi('exportChatInviteLink',['chat_id'=>$c['id']]);
    $url=$r['result']??null; if(!is_string($url)) continue;
    foreach($com['telegram_gruppi'] as &$g){
      if(($g['link']??'')==='' && (stripos($c['titolo'],$g['nome'])!==false || stripos($g['nome'],'community')!==false && $c['ruolo']==='community')){ $g['link']=$url; $n++; break; }
    }
  }
  if($n) @file_put_contents($f,json_encode($com,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
  return $n;
}

// ===== RISPOSTA AI nelle chat =====
function tgRispostaAI($domanda,$nome){
  $key=getenv('GROQ_API_KEY')?:'';
  $fallback="Ciao ".$nome.", bella domanda. Per la risposta precisa sul tuo caso scrivi alla direzione su WhatsApp, https://wa.me/393388771737 , intanto puoi misurare la tua situazione con l'audit gratuito di due minuti, https://81plus.net/audit.html";
  if($key==='') return $fallback;
  $body=json_encode(['model'=>'llama-3.3-70b-versatile','temperature'=>0.4,'max_tokens'=>350,'messages'=>[
    ['role'=>'system','content'=>'Sei l assistente 81+ Sicurissimo nei gruppi Telegram. Rispondi solo su sicurezza sul lavoro Decreto 81/08, HACCP, privacy GDPR e sull ecosistema 81+. Regole assolute. Mai inventare articoli di legge, numeri o sanzioni, se non sei certo dici che serve una verifica puntuale e indirizzi a WhatsApp https://wa.me/393388771737. Massimo 90 parole, dai del tu, frasi brevi, solo virgole e punti. Non dare consulenza legale vincolante, le tue sono indicazioni generali. Chiudi quando ha senso con una sola azione, l audit gratuito https://81plus.net/audit.html. Se la domanda è fuori tema, riporta con gentilezza il discorso sui temi del gruppo.'],
    ['role'=>'user','content'=>$domanda]]]);
  $ch=curl_init('https://api.groq.com/openai/v1/chat/completions');
  curl_setopt_array($ch,[CURLOPT_POST=>1,CURLOPT_POSTFIELDS=>$body,CURLOPT_HTTPHEADER=>['Content-Type: application/json','Authorization: Bearer '.$key],CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>20]);
  $r=curl_exec($ch); curl_close($ch);
  $d=json_decode($r,true); $out=trim($d['choices'][0]['message']['content']??'');
  return $out!==''?$out:$fallback;
}

// ===== IL GIRO ORARIO, chiamato dal cron =====
function tgGiro($pdo){
  if(getenv('TELEGRAM_ON')==='0') return ['off'=>true];
  $tz=new DateTimeZone('Europe/Rome'); $ora=new DateTime('now',$tz); $h=(int)$ora->format('G');
  $fatto=['post'=>0,'quiz'=>0,'blog'=>0,'classifica'=>0,'link'=>0];
  if($h>=8)  $fatto['post']=tgPostDelGiorno();                 // post del giorno dalle 08
  if($h>=13) $fatto['quiz']=tgQuizDelGiorno($pdo);             // quiz dalle 13
  if($h>=20) $fatto['classifica']=tgClassificaSettimana($pdo); // domenica sera
  $fatto['blog']=tgPubblicaBlogNuovi();                        // articoli nuovi appena escono
  $st=tgStato();
  if(($st['sync_link']??'')!==$ora->format('Y-m-d')){ $fatto['link']=tgSincronizzaLinkPubblici(); $st['sync_link']=$ora->format('Y-m-d'); tgSalvaStato($st); }
  return $fatto;
}
