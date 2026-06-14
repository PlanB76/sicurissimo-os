<?php
// 81+ PROMO SETTIMANALE. Finestra vera lunedì-venerdì, offerte a rotazione ciclica
// sul numero di settimana ISO. Mai scarsità finta, la scadenza è il calendario.
function promoStato(){
  $cfg=json_decode(@file_get_contents(__DIR__.'/../data/promo_settimana.json'),true)?:['attiva'=>false];
  if(empty($cfg['attiva'])) return ['ok'=>true,'attiva'=>false];
  $tz=new DateTimeZone('Europe/Rome'); $ora=new DateTime('now',$tz);
  $dow=(int)$ora->format('N'); $gi=(int)($cfg['giorno_inizio']??1); $gf=(int)($cfg['giorno_fine']??5);
  $in=($dow>=$gi && $dow<=$gf);
  $fine=clone $ora; $fine->modify(($gf-$dow).' day'); $fine->setTime(23,59,59);
  $pross=clone $ora; $delta=($gi-$dow+7)%7; if($delta===0)$delta=7; $pross->modify('+'.$delta.' day'); $pross->setTime(0,0,0);
  $nsett=(int)$ora->format('W');
  $offerte=$cfg['offerte']??[];
  $titolo=$cfg['titolo']??''; $sotto=$cfg['sottotitolo']??'';
  if(!empty($cfg['rotazione'])){
    $r=$cfg['rotazione'][$nsett % count($cfg['rotazione'])];
    $offerte=$r['offerte']??$offerte; $titolo=$r['titolo']??$titolo; $sotto=$r['sottotitolo']??$sotto;
  }
  return ['ok'=>true,'attiva'=>$in,'settimana'=>$ora->format('o\\WW'),
    'fine_finestra'=>$in?$fine->format('c'):null,'prossima_apertura'=>$in?null:$pross->format('c'),
    'titolo'=>$titolo,'sottotitolo'=>$sotto,'offerte'=>$offerte];
}
// annuncio sul canale Telegram, una volta per settimana, il primo giro utile della finestra
function promoTelegram($pdo){
  $st=promoStato(); if(empty($st['attiva'])) return 0;
  $trig='t_promo_tg_'.$st['settimana'];
  try{ $c=$pdo->prepare("SELECT 1 FROM trigger_sent WHERE email='telegram' AND trig=?"); $c->execute([$trig]); if($c->fetch()) return 0; }catch(Exception $e){ return 0; }
  require_once __DIR__.'/telegram81.php';
  $righe=["🟠 ".$st['titolo'],$st['sottotitolo'],""];
  foreach($st['offerte'] as $o) $righe[]="• ".$o['nome'].($o['bonus_pv']??0?", ".$o['bonus_pv']." PV in regalo":"");
  $fine=(new DateTime($st['fine_finestra']))->format('d/m');
  $righe[]=""; $righe[]="Vale fino a venerdì ".$fine.", poi la settimana gira e cambia offerta. Componi la tua combo, https://81plus.net/combo81.html";
  $canale=getenv('TELEGRAM_CHANNEL')?:'@ottantunoplus';
  if(!tgFake()) @tgApi('sendMessage',['chat_id'=>$canale,'text'=>implode("\n",$righe)]);
  try{ $pdo->prepare("INSERT INTO trigger_sent(email,trig,created_at) VALUES('telegram',?,?)")->execute([$trig,gmdate('c')]); }catch(Exception $e){}
  return 1;
}
