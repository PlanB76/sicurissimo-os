<?php
// SKILL 41 lato server. Ogni iscrizione, da qualsiasi form o pagina, fa tre cose:
// 1 lead deduplicato, 2 newsletter con consenso tracciato, 3 ingresso nel flusso welcome.
require_once __DIR__.'/db.php';
function enrollUniversale($email,$nome='',$fonte='sito',$ownerSic=null){
  $email=strtolower(trim($email)); if(!filter_var($email,FILTER_VALIDATE_EMAIL)) return 'email_non_valida';
  $pdo=db(); $now=gmdate('c'); $nuovo=false;
  // 1. LEADS, dedup per email. Se esiste integra senza sovrascrivere.
  $st=$pdo->prepare('SELECT id,nome FROM leads WHERE email=? ORDER BY id ASC LIMIT 1'); $st->execute([$email]); $l=$st->fetch();
  if(!$l){ $pdo->prepare('INSERT INTO leads(email,nome,source,owner_sic,stage,created_at) VALUES(?,?,?,?,?,?)')->execute([$email,$nome?:null,$fonte,$ownerSic,'nuovo',$now]); $nuovo=true; }
  elseif($nome && !$l['nome']){ $pdo->prepare('UPDATE leads SET nome=? WHERE id=?')->execute([$nome,$l['id']]); }
  // 2. NEWSLETTER, consenso tracciato, dedup.
  $st=$pdo->prepare('SELECT 1 FROM newsletter WHERE email=?'); $st->execute([$email]);
  if(!$st->fetch()){ $pdo->prepare('INSERT INTO newsletter(email,nome,fonte,consenso,stato,created_at) VALUES(?,?,?,?,?,?)')
    ->execute([$email,$nome?:null,$fonte,'SI, iscrizione volontaria da '.$fonte.' il '.$now,'ATTIVO',$now]); }
  // 3. FLUSSO WELCOME, ingresso solo la prima volta.
  $st=$pdo->prepare('SELECT 1 FROM welcome_queue WHERE email=?'); $st->execute([$email]);
  if(!$st->fetch()){ $pdo->prepare('INSERT INTO welcome_queue(email,nome,step,next_at,stato,unsub_token,created_at) VALUES(?,?,?,?,?,?,?)')
    ->execute([$email,$nome?:null,0,$now,'ATTIVO',bin2hex(random_bytes(20)),$now]); }
  ev('iscrizione_universale',null,['email_hash'=>substr(hash('sha256',$email),0,12),'fonte'=>$fonte]);
  return $nuovo?'nuovo_iscritto':'aggiornato';
}
