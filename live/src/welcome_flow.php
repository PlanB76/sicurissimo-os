<?php
require_once __DIR__.'/db.php';
function welcomeSteps(){ return [0,2,3,5,9,14,20]; }
function welcomeSerie($nome,$unsubUrl,$base='https://81plus.net'){
  $s=($nome?'Ciao '.explode(' ',trim($nome))[0]:'Ciao');
  $wa='https://wa.me/393388771737';
  $libro=$base.'/download/sicurissimo-addio-alla-burocrazia.pdf';
  $audit=$base.'/audit.html'; $profilo=$base.'/profilo.html';
  $corsiObb=$base.'/api/track.php?to=ac1&src=email'; $corsiPro=$base.'/api/track.php?to=ac2&src=email';
  $firma="\n\nLa direzione Sicurissimo 81+\nScrivici su WhatsApp ".$wa;
  $piede="\n\nSe non vuoi più ricevere queste email, annulla qui: ".$unsubUrl;
  return [
   ['Benvenuto in 81+. Ecco il tuo regalo',
    $s.",\n\nbenvenuto nella community 81+. Hai già 1000 punti PV di benvenuto nel tuo wallet. Cosa sono i PV. Sono i punti valore dell’ecosistema 81+. Ti danno sconti sui servizi, sbloccano vantaggi e benefit e fanno salire il tuo livello VIP. La spiegazione completa è nella tua dashboard.\n\nPrima cosa, il tuo regalo. Scarica la guida SICURISSIMO, addio alla burocrazia, e scopri come mettere in regola la tua azienda senza perderti tra le scartoffie: ".$libro."\n\nE c’è un modo per raddoppiare subito. Due minuti, completi il profilo, e il wallet sale a 2000 PV. In più si attiva il tuo pacchetto SIC completo, codice personale, link di invito e QR, e ricevi, più un mese di membership Basic 81+ in regalo. Lo fai qui: ".$profilo.$firma.$piede],
   ['Hai aperto la guida. Parti da qui',
    $s.",\n\nhai tra le mani la guida giusta. Il capitolo più importante e quello sulle scadenze, perché quasi sempre il problema non è mancare i documenti, e averli scaduti senza accorgersene.\n\nVuoi sapere subito a che punto sei. Fai l’audit gratuito, due minuti: ".$audit."\n\nSe non l’hai ancora scaricata, la guida è qui: ".$libro.$firma.$piede],
   ['I corsi che mettono in regola te e il tuo team',
    $s.",\n\nformazione obbligatoria con attestato valido, sicurezza, HACCP e privacy, su piattaforma accreditata. Scegli il corso, lo fai da casa, l’attestato arriva a tuo nome. Parti da qui: ".$corsiObb."\n\nE per crescere oltre l obbligo, informatica, lingue, business, c’è la formazione professionale su piattaforma accreditata MIM: ".$corsiPro.$firma.$piede],
   ['Sblocca 1000 PV e un mese di Basic',
    $s.",\n\nun passo veloce che ti conviene. Completa il tuo profilo con i tuoi dati e ricevi subito altri 1000 PV, più un mese di membership Basic 81+ in regalo.\n\nCon la Basic hai documenti aggiornati, scadenze monitorate e un riferimento sempre raggiungibile. Completa qui: ".$profilo.$firma.$piede],
   ['Il primo errore che costa caro',
    $s.",\n\nlo vediamo ogni settimana. Aziende che scoprono di avere documenti scaduti solo quando arriva il controllo. Le sanzioni del decreto 81 partono da migliaia di euro e possono fermare l’attività.\n\nLa soluzione e il controllo preventivo. Fai l’audit gratuito e vedi cosa manca: ".$audit.$firma.$piede],
   ['Formazione e patente a crediti, sei in regola',
    $s.",\n\nogni lavoratore deve avere formazione generale e specifica del settore giusto, e chi sta in cantiere ha bisogno della patente a crediti. Un attestato del comparto sbagliato vale zero davanti all’ispettore.\n\nVuoi sapere quali corsi servono alla tua azienda. Li trovi già pronti sulla piattaforma accreditata, scegli e parti subito: ".$corsiObb."\n\nPreferisci che te lo diciamo noi. Scrivici su WhatsApp e in cinque minuti hai la risposta: ".$wa.$firma.$piede],
   ['Ultima cosa, poi ti lasciamo lavorare',
    $s.",\n\nin questi giorni ti abbiamo dato la guida, l’audit e gli strumenti. Ora tocca a te. Continuare a rimandare, o sistemare tutto con un partner che ci mette la faccia.\n\nCon la membership hai tutto sotto controllo. Scrivici ora su WhatsApp per una consulenza gratuita di quindici minuti, senza impegno: ".$wa.$firma.$piede]
  ];
}

function welcomeSerieAttivazione($nome,$base,$magicUrl,$unsubUrl){
  $s=($nome?'Ciao '.explode(' ',trim($nome))[0]:'Ciao');
  $wa='https://wa.me/393388771737';
  $corpo=$s.",\n\nda oggi sei dentro 81+. Ti abbiamo già caricato il tuo profilo con il tuo codice SIC’è un regalo di benvenuto di 1000 punti PV, spendibili come sconto sui nostri servizi.\n\nAttiva il tuo accesso e imposta la password qui: ".$magicUrl."\n\nDentro trovi l’audit gratuito, le tue scadenze e la dashboard. Per qualsiasi cosa scrivici su WhatsApp: ".$wa."\n\nLa direzione Sicurissimo 81+";
  $corpo.="\n\nRicevi questa email perché sei un nostro cliente. Se non vuoi più ricevere comunicazioni, annulla qui: ".$unsubUrl;
  return [['Il tuo accesso 81+ è pronto, 1000 PV in regalo',$corpo]];
}

function processaWelcome($limit=50){
  $pdo=db(); $now=gmdate('c'); $c=cfg(); $base='https://'.$c['company']['dominio'];
  $st=$pdo->prepare("SELECT * FROM welcome_queue WHERE stato='ATTIVO' AND next_at<=? ORDER BY next_at ASC LIMIT ".(int)$limit); $st->execute([$now]);
  $n=0;
  foreach($st->fetchAll() as $r){
    $unsub=$base.'/api/unsubscribe.php?token='.$r['unsub_token'];
    // recupera token di attivazione se l account e ancora da attivare
    $magic=null; $ast=$pdo->prepare('SELECT set_token,status FROM accounts WHERE email=?'); $ast->execute([$r['email']]); $acc=$ast->fetch();
    if($acc && $acc['status']==='da_attivare' && $acc['set_token']){ $magic=$base.'/set_password.html?token='.$acc['set_token']; }
    $tipoSerie=isset($r['serie'])?$r['serie']:'nurture';
    if($tipoSerie==='attivazione'){ $serie=welcomeSerieAttivazione($r['nome'],$base,$magic?:($base.'/login.html'),$unsub); }
    else {
      $serie=welcomeSerie($r['nome'],$unsub,$base);
      if($magic && isset($serie[0])){ $serie[0][1]="Attiva il tuo accesso e prendi i tuoi 1000 PV in regalo: ".$magic."\n\n".$serie[0][1]; }
    }
    $step=(int)$r['step'];
    if($step>=count($serie)){ $pdo->prepare("UPDATE welcome_queue SET stato='COMPLETATO' WHERE id=?")->execute([$r['id']]); continue; }
    $msg=$serie[$step];
    $headers='From: 81+ <'.$c['welcome_from'].">\r\nReply-To: ".$c['info_from']."\r\nContent-Type: text/plain; charset=utf-8\r\nMIME-Version: 1.0";
    $ok=@mail($r['email'],$msg[0],$msg[1],$headers,'-f'.$c['welcome_from']);
    if(!$ok){ ev('welcome_invio_fallito',null,['step'=>$step]); continue; }
    $steps=welcomeSteps(); $next=$step+1;
    if($next>=count($serie)){ $pdo->prepare("UPDATE welcome_queue SET step=?,stato='COMPLETATO' WHERE id=?")->execute([$next,$r['id']]); }
    else{ $giorni=$steps[$next]-$steps[$step]; $na=gmdate('c',time()+$giorni*86400);
      $pdo->prepare("UPDATE welcome_queue SET step=?,next_at=? WHERE id=?")->execute([$next,$na,$r['id']]); }
    ev('welcome_step',null,['step'=>$step+1]); $n++;
  }
  return $n;
}
