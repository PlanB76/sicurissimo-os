<?php
// 81+ SCADENZIARIO. Ricorrenze cicliche, prossime occorrenze, avvisi email, feed calendario.
require_once __DIR__.'/db.php';
function scTabelle($pdo){
  $sq=stripos($pdo->getAttribute(PDO::ATTR_DRIVER_NAME),'sqlite')!==false;
  try{$pdo->query('SELECT 1 FROM scadenze_cicliche LIMIT 1');}catch(Exception $e){
    $pdo->exec($sq?'CREATE TABLE IF NOT EXISTS scadenze_cicliche(id INTEGER PRIMARY KEY AUTOINCREMENT,account_id INTEGER,titolo TEXT,categoria TEXT,primo_termine TEXT,ricorrenza_mesi INTEGER DEFAULT 12,note TEXT,attivo INTEGER DEFAULT 1,updated_at TEXT)'
                  :"CREATE TABLE IF NOT EXISTS scadenze_cicliche(id BIGINT AUTO_INCREMENT PRIMARY KEY,account_id BIGINT,titolo VARCHAR(191),categoria VARCHAR(40),primo_termine VARCHAR(10),ricorrenza_mesi INT DEFAULT 12,note VARCHAR(255),attivo TINYINT DEFAULT 1,updated_at VARCHAR(40)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");}
  try{$pdo->exec('ALTER TABLE accounts ADD COLUMN cal_token VARCHAR(48) NULL');}catch(Exception $e){}
}
function scProssima($primo,$mesi,$da=null){
  $da=$da?:new DateTime('today',new DateTimeZone('Europe/Rome'));
  try{ $d=new DateTime($primo,new DateTimeZone('Europe/Rome')); }catch(Exception $e){ return null; }
  $mesi=max(1,(int)$mesi); $g=(int)$d->format('j');
  $salva=clone $d;
  while($d<$da){ $salva=clone $d; $d->modify('first day of +'.$mesi.' month'); $d->setDate((int)$d->format('Y'),(int)$d->format('n'),min($g,(int)$d->format('t'))); }
  return $d;
}
function scOccorrenze($pdo,$accId,$quante=60){
  $q=$pdo->prepare('SELECT * FROM scadenze_cicliche WHERE account_id=? AND attivo=1'); $q->execute([$accId]);
  $out=[]; $oggi=new DateTime('today',new DateTimeZone('Europe/Rome'));
  foreach($q->fetchAll() as $s){
    $n=scProssima($s['primo_termine'],$s['ricorrenza_mesi']); if(!$n) continue;
    $delta=(int)$oggi->diff($n)->format('%r%a');
    $out[]=['id'=>(int)$s['id'],'titolo'=>$s['titolo'],'categoria'=>$s['categoria'],'quando'=>$n->format('Y-m-d'),
            'giorni'=>$delta,'ricorrenza_mesi'=>(int)$s['ricorrenza_mesi'],'note'=>$s['note'],'primo_termine'=>$s['primo_termine']];
  }
  usort($out,fn($a,$b)=>strcmp($a['quando'],$b['quando']));
  return array_slice($out,0,$quante);
}
function scIcs($pdo,$acc){
  $righe=["BEGIN:VCALENDAR","VERSION:2.0","PRODID:-//81plus//Scadenziario//IT","X-WR-CALNAME:Scadenze 81+ ".$acc['sic'],"REFRESH-INTERVAL;VALUE=DURATION:P1D"];
  $q=$pdo->prepare('SELECT * FROM scadenze_cicliche WHERE account_id=? AND attivo=1'); $q->execute([$acc['id']]);
  foreach($q->fetchAll() as $s){
    $n=scProssima($s['primo_termine'],$s['ricorrenza_mesi']); if(!$n) continue;
    $uid='sc'.$s['id'].'@81plus.net'; $dt=$n->format('Ymd');
    $tit=preg_replace('/[\r\n,;]+/',' ',$s['titolo']); $note=preg_replace('/[\r\n,;]+/',' ',(string)$s['note']);
    $righe[]="BEGIN:VEVENT";
    $righe[]="UID:".$uid;
    $righe[]="DTSTAMP:".gmdate('Ymd\THis\Z');
    $righe[]="DTSTART;VALUE=DATE:".$dt;
    $righe[]="RRULE:FREQ=MONTHLY;INTERVAL=".max(1,(int)$s['ricorrenza_mesi']);
    $righe[]="SUMMARY:81+ ".$tit;
    if($note!=='')$righe[]="DESCRIPTION:".$note;
    $righe[]="BEGIN:VALARM"; $righe[]="TRIGGER:-P14D"; $righe[]="ACTION:DISPLAY"; $righe[]="DESCRIPTION:Scadenza tra 14 giorni"; $righe[]="END:VALARM";
    $righe[]="END:VEVENT";
  }
  $righe[]="END:VCALENDAR";
  return implode("\r\n",$righe)."\r\n";
}
// avvisi dal cron, una email per occorrenza a 30 e a 7 giorni
function scAvvisi($pdo){
  scTabelle($pdo);
  try{ $rows=$pdo->query("SELECT s.*,a.email,a.nome FROM scadenze_cicliche s JOIN accounts a ON a.id=s.account_id WHERE s.attivo=1 AND a.email NOT LIKE '%@wallet.81plus%'")->fetchAll(); }catch(Exception $e){ return 0; }
  $n=0;
  foreach($rows as $s){
    $next=scProssima($s['primo_termine'],$s['ricorrenza_mesi']); if(!$next) continue;
    $oggi=new DateTime('today',new DateTimeZone('Europe/Rome'));
    $delta=(int)$oggi->diff($next)->format('%r%a');
    foreach([30,7] as $soglia){
      if($delta!==$soglia) continue;
      $trig='t_scad_'.$s['id'].'_'.$next->format('Ymd').'_'.$soglia;
      try{ $c=$pdo->prepare('SELECT 1 FROM trigger_sent WHERE email=? AND trig=?'); $c->execute([$s['email'],$trig]); if($c->fetch()) continue; }catch(Exception $e){ continue; }
      $corpo="Ciao ".($s['nome']?:'imprenditore').",\n\ntra ".$soglia." giorni scade, ".$s['titolo'].", il ".$next->format('d/m/Y').".\n".($s['note']?'Nota, '.$s['note']."\n":'')."\nApri lo scadenziario dalla dashboard per gestirla, https://81plus.net/scadenze.html\n\nLa direzione 81+\n\nRicevi questo avviso perché hai inserito la scadenza nel tuo scadenziario 81+.";
      if(function_exists('mailGeneric')) @mailGeneric($s['email'],'Tra '.$soglia.' giorni scade, '.$s['titolo'],$corpo);
      try{ $pdo->prepare('INSERT INTO trigger_sent(email,trig,created_at) VALUES(?,?,?)')->execute([$s['email'],$trig,gmdate('c')]); $n++; }catch(Exception $e){}
    }
  }
  return $n;
}
