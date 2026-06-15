<?php
// 81+ SEQUENZA DI CONVERSIONE AUTOMATICA. Tre tocchi dopo la registrazione per chi non ha
// ancora comprato, valore prima, scala poi, sorpresa alla fine. Dedupe su trigger_sent,
// stesso meccanismo delle altre mail. Si ferma da sola appena l account compra qualcosa.
require_once __DIR__.'/trigger_mail.php';
function seqHaComprato($pdo,$accId){
  try{ $q=$pdo->prepare("SELECT 1 FROM pv_ricariche WHERE account_id=? AND stato='completata' LIMIT 1"); $q->execute([$accId]); if($q->fetch()) return true; }catch(Exception $e){}
  try{ $q=$pdo->prepare("SELECT 1 FROM entitlements WHERE account_id=? AND status='active' LIMIT 1"); $q->execute([$accId]); if($q->fetch()) return true; }catch(Exception $e){}
  return false;
}
function seqTappe(){
  $base=getenv('SITE_URL')?:'https://81plus.net';
  return [
   ['giorni'=>2,'trig'=>'seq_d2','ogg'=>'Hai già fatto il tuo audit? Due minuti, zero impegni',
    'txt'=>"Ciao NOME,\n\nquando ti sei registrato hai ricevuto 100 PV in regalo, sono ancora lì.\n\nIl primo gradino è gratis e ci metti meno di un caffè, l audit ti dice esattamente dove sei messo con le 30 verifiche che contano.\n\nFallo adesso, $base/index.html#audit\n\nNessuno ti chiamerà, il risultato è tuo."],
   ['giorni'=>5,'trig'=>'seq_d5','ogg'=>'La scala del valore 81+, dal gratis al club, senza fretta',
    'txt'=>"Ciao NOME,\n\nti faccio vedere la mappa onesta di tutto quello che facciamo, si chiama scala del valore.\n\nParti gratis, sali solo quando un gradino ti serve davvero, ogni passo ti dà più tranquillità del precedente.\n\nGuardala qui, $base/scala_valore.html\n\nI tuoi 100 PV valgono già sul primo documento."],
   ['giorni'=>9,'trig'=>'seq_d9','ogg'=>'Una sorpresa per te, l anteprima è gratis',
    'txt'=>"Ciao NOME,\n\nlo sai che la fabbrica documenti ti fa vedere l anteprima gratis, con la filigrana, prima di spendere un solo PV?\n\nGeneri il tuo DVR o il manuale HACCP sui tuoi dati veri, lo leggi, e decidi solo dopo.\n\nProva qui, $base/documenti_fabbrica.html\n\nE se hai domande la direzione risponde su WhatsApp, https://wa.me/393388771737"],
  ];
}
function seqSweep($pdo,$limite=80){
  tmTabella($pdo); $inviate=0;
  try{ $rs=$pdo->query("SELECT id,email,nome,created_at FROM accounts WHERE email LIKE '%@%' ORDER BY id DESC LIMIT 800"); }catch(Exception $e){ return 0; }
  $ora=time();
  foreach($rs as $a){
    if($inviate>=$limite) break;
    if(!tmEmailValida($a['email'])) continue;
    $eta=($ora-strtotime($a['created_at']?:'now'))/86400;
    if(seqHaComprato($pdo,$a['id'])) continue;
    foreach(seqTappe() as $t){
      if($eta<$t['giorni']) continue;
      if(tmGiaInviata($pdo,$a['email'],$t['trig'])) continue;
      $nome=trim((string)$a['nome'])?:'amico di 81+';
      $txt=str_replace('NOME',$nome,$t['txt'])."\n\n".tmFirma();
      if(function_exists('mail')) @mail($a['email'],$t['ogg'],$txt,"From: 81+ <info@81plus.net>\r\nContent-Type: text/plain; charset=utf-8");
      tmSegna($pdo,$a['email'],$t['trig']); $inviate++;
      break; // una tappa per giro, mai due mail insieme alla stessa persona
    }
  }
  return $inviate;
}
