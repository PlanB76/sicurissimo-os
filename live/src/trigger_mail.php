<?php
// 81+ EMAIL COMPORTAMENTALI. Il motore guarda le azioni delle ultime 48 ore
// e manda l'email giusta alla persona giusta, una volta sola per trigger.
// Gira dentro il cron orario. Dedupe sulla coppia email piu trigger.
// Le email parlano al lettore con il tu, una sola azione per email,
// e chiudono sempre con il motivo dell'invio e il link per gestire le preferenze.

require_once __DIR__.'/db.php';

function tmTabella($pdo){
  try{ $pdo->query('SELECT 1 FROM trigger_sent LIMIT 1'); }
  catch(Exception $e){
    if(strpos(strtolower($pdo->getAttribute(PDO::ATTR_DRIVER_NAME)),'sqlite')!==false){
      $pdo->exec('CREATE TABLE IF NOT EXISTS trigger_sent (id INTEGER PRIMARY KEY AUTOINCREMENT, email TEXT NOT NULL, trig TEXT NOT NULL, created_at TEXT, UNIQUE(email,trig))');
    } else {
      $pdo->exec('CREATE TABLE IF NOT EXISTS trigger_sent (id INT AUTO_INCREMENT PRIMARY KEY, email VARCHAR(190) NOT NULL, trig VARCHAR(60) NOT NULL, created_at VARCHAR(40), UNIQUE KEY uq_email_trig (email,trig)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
    }
  }
}

function tmGiaInviata($pdo,$email,$trig){
  $q=$pdo->prepare('SELECT 1 FROM trigger_sent WHERE email=? AND trig=?'); $q->execute([$email,$trig]);
  return (bool)$q->fetch();
}
function tmSegna($pdo,$email,$trig){
  try{ $pdo->prepare('INSERT INTO trigger_sent(email,trig,created_at) VALUES(?,?,?)')->execute([$email,$trig,gmdate('c')]); return true; }
  catch(Exception $e){ return false; }
}
function tmEmailValida($email){
  if(!$email) return false;
  if(stripos($email,'@wallet.81plus')!==false) return false;
  return filter_var($email,FILTER_VALIDATE_EMAIL)!==false;
}
function tmFirma(){
  return "\n\nLa direzione 81+\nhttps://81plus.net\n\nRicevi questa email perché hai un account 81+ e questa riguarda una tua azione recente. Gestisci le preferenze dalla tua dashboard, sezione profilo, oppure rispondi a questa email per non ricevere piu questi avvisi.";
}

// Le regole. Ogni regola dice: quale azione la accende, e che email parte.
function tmRegole(){
  return [
    [
      'trig'=>'t_audit',
      'sql'=>"SELECT DISTINCT a.email, a.nome FROM events e JOIN accounts a ON a.id=e.account_id WHERE e.type IN ('audit_run','audit','audit_fatto') AND e.created_at>=? AND a.status='attivo'",
      'sub'=>'81+ , il tuo audit parla chiaro, ecco le tre mosse',
      'body'=>function($r){ $n=$r['nome']?:'imprenditore';
        return "Ciao ".$n.",\nhai fatto l'audit, e questo ti mette già davanti a 7 aziende su 10, quelle che ai controlli risultano irregolari secondo i dati INL.\n\nOra le tre mosse, in ordine.\nUno, apri la dashboard e guarda il semaforo del tuo settore, ti dice cosa manca.\nDue, scarica i modelli che ti servono, li trovi pronti in Word e PDF.\nTre, se ti manca la formazione obbligatoria, parti dai corsi su piattaforme accreditate e riconosciute, MIM e Regione Lazio, attestato a tuo nome.\n\nLa tua dashboard è qui\nhttps://81plus.net/login.html".tmFirma(); }
    ],
    [
      'trig'=>'t_profilo',
      'sql'=>"SELECT DISTINCT a.email, a.nome FROM pv_ledger l JOIN accounts a ON a.id=l.account_id WHERE l.reason='profilo_completo_bonus' AND l.created_at>=? AND a.status='attivo'",
      'sub'=>'81+ , il tuo regalo è nella dashboard',
      'body'=>function($r){ $n=$r['nome']?:'membro 81+';
        return "Ciao ".$n.",\nprofilo completato, doppio premio accreditato.\n\nNella tua dashboard ora trovi il libro Addio Burocrazia in regalo, la tua SIC Card olografica con il QR personale, e il bonus PV già nel wallet.\n\nProssimo passo che ti consiglio, apri la SIC Card e condividi il tuo link di invito. Ogni amico che entra ti porta PV veri, 25 alla registrazione e 100 quando diventa attivo.\n\nEntra qui\nhttps://81plus.net/login.html".tmFirma(); }
    ],
    [
      'trig'=>'t_invito',
      'sql'=>"SELECT DISTINCT a.email, a.nome FROM events e JOIN accounts a ON a.id=e.account_id WHERE e.type='invito_registrato' AND e.created_at>=? AND a.status='attivo'",
      'sub'=>'81+ , la tua rete è partita',
      'body'=>function($r){ $n=$r['nome']?:'membro 81+';
        return "Ciao ".$n.",\nuna persona si è registrata con il tuo invito. I primi 25 PV sono già tuoi, e quando il tuo invitato completa il profilo ne arrivano altri 100.\n\nChi invita cinque persone attive sblocca il badge Evangelista, 500 PV in un colpo.\n\nIl tuo link e il tuo QR sono sulla SIC Card, nella dashboard.\nhttps://81plus.net/login.html".tmFirma(); }
    ],
    [
      'trig'=>'t_vip1',
      'sql'=>"SELECT DISTINCT a.email, a.nome FROM pv_ledger l JOIN accounts a ON a.id=l.account_id WHERE l.reason='vip_bonus_1' AND l.created_at>=? AND a.status='attivo'",
      'sub'=>'81+ , sei VIP 1, guarda cosa hai sbloccato',
      'body'=>function($r){ $n=$r['nome']?:'membro 81+';
        return "Ciao ".$n.",\nhai raggiunto il grado VIP 1 e il bonus di livello è già nel tuo wallet.\n\nCosa hai sbloccato adesso. La checklist controlli pronta in dashboard, la guida rapida dell'AI Coach, e la tua SIC Card che cambia materiale, ora è bronzo.\n\nIl prossimo grado è VIP 2, e li si apre il buono Academy. Vedi quanto ti manca nella card dei livelli.\nhttps://81plus.net/login.html".tmFirma(); }
    ],
    [
      'trig'=>'t_corsi',
      'sql'=>"SELECT DISTINCT a.email, a.nome FROM events e JOIN accounts a ON a.id=e.account_id WHERE e.type='partner_click' AND e.created_at>=? AND a.status='attivo'",
      'sub'=>'81+ , hai guardato i corsi, ti aiuto a scegliere',
      'body'=>function($r){ $n=$r['nome']?:'membro 81+';
        return "Ciao ".$n.",\nhai dato un'occhiata ai corsi e ti capisco, il catalogo è grande. Ti semplifico la scelta.\n\nSe ti serve la formazione obbligatoria, sicurezza, HACCP o privacy, scegli il corso del tuo ruolo sulla piattaforma accreditata, gli attestati sono validi e a tuo nome.\nSe vuoi crescere, lingue, digitale, gestione, c'è la piattaforma riconosciuta dal MIM, Ministero dell'Istruzione e del Merito.\n\nEntrambe le porte sono nella tua dashboard, sezione formazione certificata. Le fai da casa, quando vuoi.\nhttps://81plus.net/login.html\n\nSe hai un dubbio sul corso giusto per il tuo caso, scrivici su WhatsApp e la direzione ti risponde.\nhttps://wa.me/393388771737".tmFirma(); }
    ],
    [
      'trig'=>'t_doc',
      'sql'=>"SELECT DISTINCT a.email, a.nome FROM events e JOIN accounts a ON a.id=e.account_id WHERE e.type IN ('doc_unlock','doc_download') AND e.created_at>=? AND a.status='attivo'",
      'sub'=>'81+ , il documento è tuo, ora completalo bene',
      'body'=>function($r){ $n=$r['nome']?:'membro 81+';
        return "Ciao ".$n.",\nhai scaricato un modello dalla tua area. Bene, è il primo passo. Ora il consiglio che vale oro.\n\nCompila ogni campo tra parentesi quadre con i dati veri della tua attività, stampa, firma e conserva nel tuo fascicolo. Un documento compilato a metà, per un ispettore, è come non averlo.\n\nSe il documento prevede la validazione di un professionista, come il DVR, usa la base per arrivare preparato e falla validare.\n\nNella dashboard trovi anche la versione PDF di ogni modello.\nhttps://81plus.net/login.html".tmFirma(); }
    ]
  ];
}

// Il giro. Lo chiama il cron. Limite per non stressare il server di posta.
function triggerMailSweep($pdo,$limite=60){
  tmTabella($pdo);
  $da=gmdate('c',time()-48*3600);
  $inviate=0;
  foreach(tmRegole() as $regola){
    if($inviate>=$limite) break;
    try{ $st=$pdo->prepare($regola['sql']); $st->execute([$da]); $rows=$st->fetchAll(); }
    catch(Exception $e){ continue; }
    foreach($rows as $r){
      if($inviate>=$limite) break;
      $email=strtolower(trim($r['email']??''));
      if(!tmEmailValida($email)) continue;
      if(tmGiaInviata($pdo,$email,$regola['trig'])) continue;
      if(!tmSegna($pdo,$email,$regola['trig'])) continue;
      $body=is_callable($regola['body'])?$regola['body']($r):$regola['body'];
      if(function_exists('mailGeneric')) @mailGeneric($email,$regola['sub'],$body);
      try{ $pdo->prepare('INSERT INTO events(account_id,type,meta,created_at) VALUES(0,?,?,?)')
        ->execute(['trigger_mail',json_encode(['trig'=>$regola['trig'],'to'=>substr(md5($email),0,8)]),gmdate('c')]); }catch(Exception $e){}
      $inviate++;
    }
  }
  return $inviate;
}
