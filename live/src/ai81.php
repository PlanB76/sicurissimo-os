<?php
// 81+ MOTORE AUTOMAZIONI AI. Cinque automazioni vere che girano da sole.
// Uno, il coach quotidiano di ogni membro. Due, il commento AI all'audit.
// Tre, il radar dormienti che riaggancia chi sparisce. Quattro, il punteggio
// caldo freddo dei membri per la plancia. Cinque, il rapporto settimanale
// dell'Ammiraglio scritto dall'AI dai numeri veri.
// Senza GROQ_API_KEY ogni pezzo degrada con grazia su testi deterministici, mai errori.

require_once __DIR__.'/db.php';

function aiChat($sistema,$utente,$maxTok=500,$temp=0.5){
  $key=getenv('GROQ_API_KEY')?:''; if($key===''||!function_exists('curl_init')) return null;
  $body=json_encode(['model'=>'llama-3.3-70b-versatile','temperature'=>$temp,'max_tokens'=>$maxTok,
    'messages'=>[['role'=>'system','content'=>$sistema],['role'=>'user','content'=>$utente]]]);
  $ch=curl_init('https://api.groq.com/openai/v1/chat/completions');
  curl_setopt_array($ch,[CURLOPT_POST=>1,CURLOPT_POSTFIELDS=>$body,CURLOPT_HTTPHEADER=>['Content-Type: application/json','Authorization: Bearer '.$key],CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>22]);
  $r=curl_exec($ch); curl_close($ch);
  $d=json_decode($r,true); $out=trim($d['choices'][0]['message']['content']??'');
  return $out!==''?$out:null;
}

// ===== 1. COACH QUOTIDIANO, il piano del giorno di ogni membro =====
function aiCoach($pdo,$acc){
  $oggi=(new DateTime('now',new DateTimeZone('Europe/Rome')))->format('Y-m-d');
  $dir=__DIR__.'/../data/coach'; if(!is_dir($dir)) @mkdir($dir,0755,true);
  $f=$dir.'/'.$acc['id'].'.json';
  $cache=json_decode(@file_get_contents($f),true);
  if($cache && ($cache['giorno']??'')===$oggi) return $cache;
  // contesto vero del membro
  $manca=[];
  if(empty($acc['nome'])||empty($acc['tel'])||empty($acc['indirizzo'])) $manca[]='completare il profilo, vale 50 PV e accende la mappa Italia';
  if(empty($acc['telegram_id'])) $manca[]='collegare Telegram dalla dashboard, 50 PV subito e 10 PV a quiz';
  try{ $q=$pdo->prepare("SELECT 1 FROM events WHERE account_id=? AND type LIKE 'audit%' LIMIT 1"); $q->execute([$acc['id']]); $auditFatto=(bool)$q->fetch(); }catch(Exception $e){ $auditFatto=false; }
  if(!$auditFatto) $manca[]="fare l'audit gratuito di 2 minuti, la fotografia dei tuoi rischi";
  try{ $q=$pdo->prepare("SELECT 1 FROM pv_ledger WHERE account_id=? AND reason LIKE 'invito_registrato_%' LIMIT 1"); $q->execute([$acc['id']]); $haInvitato=(bool)$q->fetch(); }catch(Exception $e){ $haInvitato=false; }
  if(!$haInvitato) $manca[]='invitare il primo collega col tuo link referral, 25 PV a registrazione';
  $pv=0; try{ $q=$pdo->prepare('SELECT COALESCE(SUM(delta),0) s FROM pv_ledger WHERE account_id=?'); $q->execute([$acc['id']]); $pv=(int)$q->fetch()['s']; }catch(Exception $e){}
  $manca=array_slice($manca,0,3);
  if(!$manca) $manca=['tutte le missioni base sono complete, oggi punta sui quiz Telegram delle 13 e su un invito in più'];
  // testo deterministico sempre pronto
  $piano="Il tuo piano di oggi.\n".implode("\n",array_map(fn($m)=>'• '.ucfirst($m),$manca));
  // riscrittura AI se la chiave c'è, coi paletti
  $ai=aiChat('Sei il coach 81+ Sicurissimo. Trasforma la lista di azioni in un piano del giorno motivante per un imprenditore italiano. Regole assolute. Mantieni ESATTAMENTE le stesse azioni e gli stessi numeri di PV, non aggiungerne, non inventare norme o promesse di guadagno. Dai del tu, frasi brevi, voce attiva, solo virgole e punti, massimo 70 parole, formato, una riga di carica più le azioni in elenco con il punto elenco •.',
    'Membro con '.$pv.' PV. Azioni di oggi: '.implode('; ',$manca));
  if($ai && substr_count($ai,'•')>=1) $piano=$ai;
  $out=['giorno'=>$oggi,'pv'=>$pv,'piano'=>$piano,'ai'=>(bool)$ai];
  @file_put_contents($f,json_encode($out,JSON_UNESCAPED_UNICODE));
  return $out;
}

// ===== 2. COMMENTO AI ALL'AUDIT, dal cron, a blocchi =====
function aiTabellaAudit($pdo){
  try{ $pdo->query('SELECT ai_note FROM audit_runs LIMIT 1'); }
  catch(Exception $e){ try{ $pdo->exec('ALTER TABLE audit_runs ADD COLUMN ai_note TEXT NULL'); }catch(Exception $x){} }
}
function aiCommentiAudit($pdo){
  if(getenv('GROQ_API_KEY')==='') return 0;
  aiTabellaAudit($pdo);
  $rows=$pdo->query("SELECT id,score,gaps FROM audit_runs WHERE ai_note IS NULL ORDER BY id DESC LIMIT 5")->fetchAll();
  $n=0;
  foreach($rows as $r){
    $gaps=json_decode($r['gaps']??'[]',true); if(!is_array($gaps)) $gaps=[];
    $ai=aiChat('Sei il consulente 81+ Sicurissimo. Scrivi un commento personalizzato al risultato di un audit di conformità su sicurezza sul lavoro, HACCP e privacy. Regole assolute. Mai inventare articoli di legge, sanzioni o cifre. Mai promettere esiti di ispezioni. Parla delle lacune indicate e di nessun altra. Dai del tu, frasi brevi, solo virgole e punti, massimo 110 parole, struttura, dove sei forte, dove sei scoperto, la prima cosa da sistemare. Chiudi con, indicazione generale, non consulenza legale.',
      'Punteggio '.$r['score'].' su 100. Aree scoperte: '.($gaps?implode('; ',array_slice(array_map('strval',$gaps),0,8)):'nessuna lacuna grave rilevata'));
    if(!$ai) break;
    $pdo->prepare('UPDATE audit_runs SET ai_note=? WHERE id=?')->execute([$ai,$r['id']]);
    $n++;
  }
  return $n;
}

// ===== 3. RADAR DORMIENTI, riaggancio onesto di chi sparisce =====
function aiDormienti($pdo){
  $lim=gmdate('c',time()-14*86400);
  try{
    $rows=$pdo->query("SELECT a.id,a.email,a.nome,a.sic FROM accounts a WHERE a.status='attivo' AND a.email NOT LIKE '%@wallet.81plus%' AND a.created_at<'".$lim."' AND NOT EXISTS (SELECT 1 FROM events e WHERE e.account_id=a.id AND e.created_at>='".$lim."') AND NOT EXISTS (SELECT 1 FROM sessions s WHERE s.account_id=a.id AND s.created_at>='".$lim."') ORDER BY a.id ASC LIMIT 30")->fetchAll();
  }catch(Exception $e){ return 0; }
  $n=0;
  foreach($rows as $a){
    $trig='t_dormiente_'.gmdate('Ym'); // al massimo una al mese a testa
    try{ $c=$pdo->prepare('SELECT 1 FROM trigger_sent WHERE email=? AND trig=?'); $c->execute([$a['email'],$trig]); if($c->fetch()) continue; }catch(Exception $e){ continue; }
    $nome=$a['nome']?:'imprenditore';
    $corpo="Ciao ".$nome.",\n\nè un po' che non ci vediamo nell'ecosistema 81+ e nel frattempo le scadenze non si sono fermate, loro non dormono mai.\n\nIn due minuti dalla tua dashboard puoi, vedere il tuo piano del giorno preparato dal coach, controllare le missioni e i PV accumulati, e se non l'hai ancora fatto misurare la tua posizione con l'audit gratuito di 30 controlli.\n\nEntra qui, https://81plus.net/login.html\n\nIl tuo SIC ID resta ".$a['sic'].", i tuoi PV restano tuoi.\n\nLa direzione 81+\n\nRicevi questa email perché hai un account 81+ e non accedi da un po'. Gestisci le preferenze dal tuo profilo.";
    if(function_exists('mailGeneric')) @mailGeneric($a['email'],'I tuoi PV ti aspettano, le scadenze no',$corpo);
    try{ $pdo->prepare('INSERT INTO trigger_sent(email,trig,created_at) VALUES(?,?,?)')->execute([$a['email'],$trig,gmdate('c')]); }catch(Exception $e){}
    $n++; if($n>=15) break; // mai più di 15 per giro
  }
  return $n;
}

// ===== 4. PUNTEGGIO CALDO FREDDO, deterministico e spiegabile =====
function aiCaloreMembri($pdo,$limite=12){
  $out=[];
  try{ $rows=$pdo->query("SELECT id,sic,nome,cognome,email,telegram_id,created_at FROM accounts WHERE email NOT LIKE '%@wallet.81plus%' ORDER BY id DESC LIMIT 400")->fetchAll(); }catch(Exception $e){ return $out; }
  $sett=gmdate('c',time()-7*86400);
  foreach($rows as $a){
    $s=0; $perche=[];
    try{ $q=$pdo->prepare("SELECT COUNT(*) c FROM events WHERE account_id=? AND created_at>=?"); $q->execute([$a['id'],$sett]); $ev7=(int)$q->fetch()['c']; }catch(Exception $e){ $ev7=0; }
    if($ev7){ $s+=min($ev7,10)*4; $perche[]=$ev7.' azioni in 7 giorni'; }
    if(!empty($a['telegram_id'])){ $s+=15; $perche[]='Telegram collegato'; }
    try{ $q=$pdo->prepare("SELECT 1 FROM events WHERE account_id=? AND type LIKE 'audit%' LIMIT 1"); $q->execute([$a['id']]); if($q->fetch()){ $s+=20; $perche[]='audit fatto'; } }catch(Exception $e){}
    try{ $q=$pdo->prepare("SELECT 1 FROM events WHERE account_id=? AND type='partner_click' LIMIT 1"); $q->execute([$a['id']]); if($q->fetch()){ $s+=25; $perche[]='ha aperto i corsi'; } }catch(Exception $e){}
    try{ $q=$pdo->prepare("SELECT COUNT(*) c FROM accounts WHERE ref_by=?"); $q->execute([$a['sic']]); $fig=(int)$q->fetch()['c']; if($fig){ $s+=min($fig,5)*6; $perche[]=$fig.' invitati'; } }catch(Exception $e){}
    if($s<=0) continue;
    $out[]=['sic'=>$a['sic'],'nome'=>trim(($a['nome']??'').' '.($a['cognome']??''))?:'membro','email'=>$a['email'],'punti'=>$s,'fascia'=>$s>=60?'caldo':($s>=30?'tiepido':'freddo'),'perche'=>implode(', ',$perche)];
  }
  usort($out,fn($x,$y)=>$y['punti']<=>$x['punti']);
  return array_slice($out,0,$limite);
}

// ===== 5. RAPPORTO SETTIMANALE DELL'AMMIRAGLIO, domenica sera =====
function aiReportSettimanale($pdo){
  $tz=new DateTimeZone('Europe/Rome'); $ora=new DateTime('now',$tz);
  if((int)$ora->format('N')!==7 || (int)$ora->format('G')<21) return 0;
  $sett=$ora->format('o\WW');
  $f=__DIR__.'/../data/report_settimana.json';
  $st=json_decode(@file_get_contents($f),true)?:[];
  if(($st['settimana']??'')===$sett) return 0;
  $da=gmdate('c',time()-7*86400);
  $k=function($sql,$p=[]) use($pdo){ try{ $q=$pdo->prepare($sql); $q->execute($p); return (int)$q->fetch()['c']; }catch(Exception $e){ return 0; } };
  $kpi=[
   'nuovi iscritti'=>$k("SELECT COUNT(*) c FROM accounts WHERE created_at>=?",[$da]),
   'audit completati'=>$k("SELECT COUNT(*) c FROM events WHERE type LIKE 'audit%' AND created_at>=?",[$da]),
   'telegram collegati'=>$k("SELECT COUNT(*) c FROM accounts WHERE telegram_id IS NOT NULL"),
   'PV distribuiti in settimana'=>$k("SELECT COALESCE(SUM(delta),0) c FROM pv_ledger WHERE created_at>=? AND delta>0",[$da]),
   'click verso i corsi'=>$k("SELECT COUNT(*) c FROM events WHERE type='partner_click' AND created_at>=?",[$da]),
   'inviti andati a segno'=>$k("SELECT COUNT(*) c FROM events WHERE type='invito_registrato' AND created_at>=?",[$da]),
  ];
  $righe=''; foreach($kpi as $n=>$v) $righe.=ucfirst($n).': '.$v."\n";
  $testo="RAPPORTO SETTIMANALE 81+\nSettimana ".$sett."\n\n".$righe;
  $ai=aiChat('Sei Graziella, architetto AI della holding 81+. Scrivi il rapporto settimanale per l Ammiraglio partendo SOLO dai numeri forniti. Regole assolute. Non inventare numeri, tendenze o cause non deducibili dai dati. Tono esecutivo, dai del tu, frasi brevi, solo virgole e punti, massimo 130 parole. Struttura, come è andata, il segnale più importante, la mossa consigliata per la settimana che entra.',$righe);
  if($ai) $testo.="\nLETTURA DI GRAZIELLA\n".$ai;
  $st=['settimana'=>$sett,'quando'=>gmdate('c'),'kpi'=>$kpi,'testo'=>$testo];
  @file_put_contents($f,json_encode($st,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
  if(function_exists('mailGeneric')) @mailGeneric(cfg()['info_from']??'info@81plus.net','81+ rapporto della settimana '.$sett,$testo);
  return 1;
}

// ===== IL GIRO, dal cron =====
function aiGiro($pdo){
  if(getenv('AI_AUTOMAZIONI_ON')==='0') return ['off'=>true];
  return ['audit_commentati'=>aiCommentiAudit($pdo),
          'dormienti_riagganciati'=>aiDormienti($pdo),
          'report_settimanale'=>aiReportSettimanale($pdo)];
}

// buyer persona del giorno, ruota sulle tre persone vere di data/buyer_personas.json
function personaDelGiorno(){
  $f=__DIR__.'/../data/buyer_personas.json';
  if(!is_file($f)) return '';
  $d=json_decode(file_get_contents($f),true); $p=$d['personas']??[];
  if(!$p) return '';
  $x=$p[intval(date('z'))%count($p)];
  return 'Parla a questa persona, '.$x['nome'].'. Emozioni, '.$x['emozioni'].'. Pensa, '.$x['pensieri'].'. Usa le sue parole, '.$x['parole'].'. Leva giusta, '.$x['leva'].'. Invito al gradino, '.$x['gradino_ingresso'].'.';
}
