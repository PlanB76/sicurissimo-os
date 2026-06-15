<?php
// 81+ RECUPERO PAGAMENTI. Registra un pagamento fallito, avvia il flusso di solleciti,
// avvisa la direzione, e gestisce la chiusura quando il pagamento rientra.
require_once __DIR__.'/db.php';
require_once __DIR__.'/avvisi81.php';

// chiamata dal webhook PayPal quando un addebito ricorrente fallisce
function pagamentoFallito($accountId,$sic,$prodotto,$piano,$importo,$motivo,$subId=null){
  $pdo=db();
  // se esiste gia un caso aperto per questo abbonamento, incremento i tentativi
  $q=$pdo->prepare("SELECT id,tentativi FROM pagamenti_falliti WHERE account_id=? AND piano=? AND stato='aperto' ORDER BY id DESC LIMIT 1");
  $q->execute([$accountId,$piano]); $ex=$q->fetch();
  if($ex){
    $pdo->prepare('UPDATE pagamenti_falliti SET tentativi=tentativi+1,motivo=? WHERE id=?')->execute([$motivo,$ex['id']]);
    $id=$ex['id'];
  } else {
    $pdo->prepare('INSERT INTO pagamenti_falliti(account_id,sic,prodotto,piano,importo_euro,motivo,paypal_sub_id,tentativi,stato,created_at) VALUES(?,?,?,?,?,?,?,1,?,?)')
        ->execute([$accountId,$sic,$prodotto,$piano,$importo,$motivo,$subId,'aperto',gmdate('c')]);
    $id=$pdo->lastInsertId();
  }
  // metto in pausa l abbonamento se collegato
  try{ $pdo->prepare("UPDATE abbonamenti SET stato='in_recupero' WHERE account_id=? AND piano=? AND stato='attivo'")->execute([$accountId,$piano]); }catch(Throwable $e){}
  // primo sollecito subito
  recuperoSollecito($id,0);
  // avviso direzione
  avvisoDirezione('pagamento_fallito','Pagamento fallito, '.$piano,
    'Addebito ricorrente non riuscito per '.$sic.'. Motivo, '.$motivo.'. Importo '.$importo.' euro. Flusso di recupero avviato.',
    $accountId,$sic,'alta');
  return $id;
}

// invia un sollecito all utente, scaglionato (giorno 0, 3, 7)
function recuperoSollecito($id,$step){
  $pdo=db();
  $q=$pdo->prepare('SELECT pf.*, a.email, a.nome FROM pagamenti_falliti pf JOIN accounts a ON a.id=pf.account_id WHERE pf.id=?');
  $q->execute([$id]); $r=$q->fetch(PDO::FETCH_ASSOC);
  if(!$r || $r['stato']!=='aperto') return false;
  $nome=$r['nome']?:'ciao';
  $piano=ucfirst(str_replace(['membership_','club_','rdp_'],['Membership ','Club 81+ ','RDP+ '],$r['piano']));
  $testi=[
    0=>["Il rinnovo di $piano non è andato a buon fine","Ciao $nome,\n\nl ultimo rinnovo del tuo abbonamento $piano non è andato a buon fine. Spesso è solo una carta scaduta o un addebito momentaneamente rifiutato.\n\nPuoi sistemare in un minuto da qui, senza perdere nulla del tuo percorso.\nhttps://81plus.net/abbonamenti.html\n\nLa direzione 81+"],
    1=>["Promemoria, il tuo $piano è in pausa","Ciao $nome,\n\nil tuo abbonamento $piano è ancora in pausa per un problema di pagamento. I tuoi dati e i tuoi PV sono al sicuro.\n\nAggiorna il metodo di pagamento quando vuoi, bastano pochi secondi.\nhttps://81plus.net/abbonamenti.html\n\nLa direzione 81+"],
    2=>["Ultimo promemoria su $piano","Ciao $nome,\n\nquesto è l ultimo promemoria. Se non riprendi $piano nei prossimi giorni, l abbonamento verrà chiuso. Nessun problema, potrai riattivarlo in futuro.\n\nSe vuoi mantenerlo attivo, sistema qui.\nhttps://81plus.net/abbonamenti.html\n\nLa direzione 81+"]
  ];
  $t=$testi[$step]??$testi[0];
  if(function_exists('mailGeneric')) @mailGeneric($r['email'],$t[0],$t[1]);
  $pdo->prepare('UPDATE pagamenti_falliti SET ultimo_sollecito=? WHERE id=?')->execute([gmdate('c'),$id]);
  if(function_exists('ev')) @ev('recupero_sollecito',$r['account_id'],['step'=>$step]);
  return true;
}

// il pagamento e rientrato, chiudo il caso e riattivo
function pagamentoRecuperato($accountId,$piano){
  $pdo=db();
  $pdo->prepare("UPDATE pagamenti_falliti SET stato='recuperato',risolto_il=? WHERE account_id=? AND piano=? AND stato='aperto'")->execute([gmdate('c'),$accountId,$piano]);
  try{ $pdo->prepare("UPDATE abbonamenti SET stato='attivo',prossimo_rinnovo=? WHERE account_id=? AND piano=? AND stato='in_recupero'")->execute([gmdate('c',time()+30*86400),$accountId,$piano]); }catch(Throwable $e){}
  if(function_exists('ev')) @ev('pagamento_recuperato',$accountId,['piano'=>$piano]);
}

// chiamata dal cron: manda i solleciti dovuti e chiude i casi troppo vecchi
function recuperoCiclo(){
  $pdo=db(); $ora=time(); $fatti=0;
  $q=$pdo->query("SELECT id,created_at,ultimo_sollecito,tentativi FROM pagamenti_falliti WHERE stato='aperto'");
  foreach($q->fetchAll(PDO::FETCH_ASSOC) as $r){
    $eta=$ora-strtotime($r['created_at']);
    $giorni=floor($eta/86400);
    $ultimo=$r['ultimo_sollecito']?strtotime($r['ultimo_sollecito']):0;
    $oreUltimo=($ora-$ultimo)/3600;
    // step 1 a 3 giorni, step 2 a 7 giorni, chiusura a 12 giorni
    if($giorni>=12){
      $pdo->prepare("UPDATE pagamenti_falliti SET stato='chiuso',risolto_il=? WHERE id=?")->execute([gmdate('c'),$r['id']]);
      // chiudo anche l abbonamento
      $ab=$pdo->prepare('SELECT account_id,piano FROM pagamenti_falliti WHERE id=?'); $ab->execute([$r['id']]); $abr=$ab->fetch();
      if($abr){ $pdo->prepare("UPDATE abbonamenti SET stato='disdetto',motivo_fine='pagamento non recuperato' WHERE account_id=? AND piano=? AND stato='in_recupero'")->execute([$abr['account_id'],$abr['piano']]); }
      continue;
    }
    if($giorni>=7 && $oreUltimo>=48){ recuperoSollecito($r['id'],2); $fatti++; }
    elseif($giorni>=3 && $oreUltimo>=48){ recuperoSollecito($r['id'],1); $fatti++; }
  }
  return $fatti;
}
