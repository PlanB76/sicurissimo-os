<?php
// Piano dichiarativo. Soglie di QUALIFICA del rango, in volume di vendite reali.
function planRanks(){ return [
 0=>['nome'=>'Start','vp'=>0,'vg'=>0],1=>['nome'=>'IGNITE','vp'=>500,'vg'=>0],2=>['nome'=>'RISE','vp'=>1000,'vg'=>3000],
 3=>['nome'=>'DRIVE','vp'=>1500,'vg'=>8000],4=>['nome'=>'SCALE','vp'=>2000,'vg'=>20000],5=>['nome'=>'PEAK','vp'=>2500,'vg'=>45000],
 6=>['nome'=>'SUMMIT','vp'=>3000,'vg'=>90000],7=>['nome'=>'LEGACY','vp'=>3500,'vg'=>180000],8=>['nome'=>'CROWN','vp'=>4000,'vg'=>350000]]; }
function rankQualificato($vp,$vg){ $r=0; foreach(planRanks() as $k=>$v){ if($vp>=$v['vp'] && $vg>=$v['vg']) $r=$k; } return $r; }
// Percentuali del piano, SOLO per visualizzazione e simulazione. Tetto 60 per cento. L.173/2005.
function planSplit(){ return ['diretta'=>33,'override'=>18,'pool'=>3,'equilibrio'=>6]; }
/* CUCITURA PROVVIGIONI, NON IMPLEMENTATA QUI PER SCELTA.
   Il calcolo e l accredito multilivello delle provvigioni, anche se pagate in PV,
   vanno eseguiti dal modulo di settlement validato dal legale, separato da questo codice.
   Qui esistono solo le soglie di qualifica del rango in sola lettura e le percentuali per display e simulazione. */
