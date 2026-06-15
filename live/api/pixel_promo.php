<?php
// Promo settimanale 81+ condivisa. Una sola fonte di verità per il calcolo.
function promoLive($pdo){
  $sett=(int)gmdate('W'); $attiva=($sett%2)===1;
  $lun=strtotime('monday this week UTC');
  $inizio=gmdate('c',$lun); $fine=gmdate('c',$lun+7*86400);
  $usati=0;
  try{ $q=$pdo->prepare("SELECT COALESCE(SUM(n_pix),0) s FROM pixel_ordini WHERE stato='pagato' AND promo=1 AND created_at>=?"); $q->execute([$inizio]); $usati=(int)$q->fetch()['s']; }catch(Throwable $e){}
  return ['attiva'=>$attiva,'prezzo_promo'=>800,'sconto_pct'=>20,'slot_totali'=>20,'slot_usati'=>$usati,
          'slot_rimasti'=>max(0,20-$usati),'fine'=>$fine,'prossima'=>gmdate('c',$lun+7*86400)];
}
