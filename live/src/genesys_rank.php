<?php
// Motore dei rank territoriali GENESYS. Sola lettura sulle soglie, niente settlement provvigioni.
// I benefit sono PV da mappa fissa e accesso a opportunita, mai distribuzione di denaro verso l alto.

function gRanks(){
  static $r=null;
  if($r===null){ $j=@file_get_contents(__DIR__.'/../data/genesys_rank.json'); $d=$j?json_decode($j,true):[]; $r=$d['rank']??[]; }
  return $r;
}

// conta i sotto nodi di un certo rank ancorati a questo account, per la soglia struttura
function gSottoNodi(PDO $pdo,$sic,$rankId){
  try{ $q=$pdo->prepare("SELECT COUNT(*) c FROM accounts WHERE ref_by=? AND genesys_rank=?"); $q->execute([$sic,$rankId]); $row=$q->fetch(); return (int)($row['c']??0); }
  catch(Exception $e){ return 0; }
}

// rank territoriale qualificato. Volume e aziende reggono il livello, la struttura serve per salire oltre.
// Un fondatore genesi parte assegnato al suo livello, la struttura sotto la costruisce poi.
function gRankQualificato(PDO $pdo, array $acc, $perMantenimento=true){
  $vp=(int)($acc['genesys_vol_personale']??0);
  $az=(int)($acc['genesys_aziende_attive']??0);
  $sic=$acc['sic']??'';
  $migliore=null;
  foreach(gRanks() as $r){
    if($vp < ($r['soglia_volume_personale']??0)) continue;
    if($az < ($r['soglia_aziende_attive']??0)) continue;
    // la soglia struttura conta solo per SALIRE oltre il rank gia posseduto, non per mantenerlo
    if(!$perMantenimento){
      $ok=true;
      foreach(['communal'=>'soglia_communal_sotto','provincial'=>'soglia_provincial_sotto','regional'=>'soglia_regional_sotto'] as $liv=>$campo){
        if(isset($r[$campo]) && gSottoNodi($pdo,$sic,$liv) < $r[$campo]){ $ok=false; break; }
      }
      if(!$ok) continue;
    }
    $migliore=$r;
  }
  return $migliore;
}

// stato carriera, qualificato vs attuale, e finestra di mantenimento
function gStatoCarriera(PDO $pdo, array $acc){
  $ranks=gRanks();
  $attualeId=$acc['genesys_rank']??null;
  $dal=$acc['genesys_rank_dal']??null;
  $qual=gRankQualificato($pdo,$acc);
  $byId=[]; foreach($ranks as $r){ $byId[$r['id']]=$r; }
  $attuale=$attualeId?($byId[$attualeId]??null):null;
  // mantenimento, da quanti giorni e nel livello attuale, e mancano alla verifica dei 6 mesi
  $giorniNelRank = $dal? max(0,(int)((time()-strtotime($dal))/86400)) : 0;
  $mesiMant = $attuale['mantenimento_mesi']??6;
  $scadenzaMant = $dal? gmdate('Y-m-d', strtotime($dal)+$mesiMant*30*86400) : null;
  // a rischio retrocessione se nel rank attuale ma non piu qualificato per quel livello
  $aRischio = false;
  if($attuale && $qual){ $aRischio = ($qual['ordine'] < $attuale['ordine']); }
  elseif($attuale && !$qual){ $aRischio = true; }
  return [
    'rank_attuale'=>$attuale,
    'rank_qualificato'=>$qual,
    'giorni_nel_rank'=>$giorniNelRank,
    'scadenza_mantenimento'=>$scadenzaMant,
    'a_rischio_retrocessione'=>$aRischio,
    'vol_personale'=>(int)($acc['genesys_vol_personale']??0),
    'aziende_attive'=>(int)($acc['genesys_aziende_attive']??0),
  ];
}

// Cron, verifica il mantenimento a 6 mesi e retrocede chi non riconferma. Tocca solo il rank, mai denaro.
function gVerificaMantenimento(PDO $pdo, $limite=200){
  $retro=0; $conferme=0;
  $ranks=gRanks(); $byOrd=[]; foreach($ranks as $r){ $byOrd[$r['ordine']]=$r; }
  try{
    $q=$pdo->prepare("SELECT * FROM accounts WHERE genesys_rank IS NOT NULL AND genesys_rank<>'' LIMIT ?");
    $q->bindValue(1,(int)$limite,PDO::PARAM_INT); $q->execute();
    foreach($q->fetchAll() as $acc){
      $dal=$acc['genesys_rank_dal']??null; if(!$dal) continue;
      $stato=gStatoCarriera($pdo,$acc);
      $att=$stato['rank_attuale']; if(!$att) continue;
      $mesi=$att['mantenimento_mesi']??6;
      $scaduto = strtotime($dal) <= time()-$mesi*30*86400;
      if(!$scaduto) continue; // finestra non ancora chiusa
      // finestra chiusa, verifico se ancora qualificato per il livello attuale
      $qual=$stato['rank_qualificato'];
      if($qual && $qual['ordine']>=$att['ordine']){
        // riconferma, rinnovo la finestra
        $u=$pdo->prepare("UPDATE accounts SET genesys_rank_dal=? WHERE id=?"); $u->execute([gmdate('c'),$acc['id']]); $conferme++;
      } else {
        // retrocessione di un gradino
        $nuovoOrd=max(1,$att['ordine']-1); $nuovo=$byOrd[$nuovoOrd]['id']??$att['id'];
        if($nuovo!==$att['id']){
          $u=$pdo->prepare("UPDATE accounts SET genesys_rank=?, genesys_rank_dal=? WHERE id=?"); $u->execute([$nuovo,gmdate('c'),$acc['id']]);
          if(function_exists('ev')) @ev('genesys_retrocessione',$acc['id'],['da'=>$att['id'],'a'=>$nuovo]);
          $retro++;
        }
      }
    }
  }catch(Exception $e){}
  return ['retrocessioni'=>$retro,'riconferme'=>$conferme];
}
