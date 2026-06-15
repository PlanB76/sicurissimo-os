<?php
// 81+ MOTORE KPI. Il punteggio vita 0-1000 di ogni membro, calcolato dai dati veri,
// più i punteggi parziali, conformità, attività, fiducia, rete. Compatibile con le decisioni chiuse.
require_once __DIR__.'/db.php';
function kpiDi($pdo,$a){
  $id=$a['id']; $mese=gmdate('c',time()-30*86400);
  $n=function($sql,$p) use($pdo){ try{ $q=$pdo->prepare($sql); $q->execute($p); return (int)$q->fetch()['c']; }catch(Exception $e){ return 0; } };
  // CONFORMITA 0-250, audit fatto e punteggio, documenti generati, corsi aperti
  $auditScore=0; try{ $q=$pdo->prepare("SELECT score FROM audit_runs WHERE account_id=? ORDER BY id DESC LIMIT 1"); $q->execute([$id]); $r=$q->fetch(); $auditScore=$r?(int)$r['score']:0; }catch(Exception $e){}
  $docs=$n("SELECT COUNT(*) c FROM events WHERE account_id=? AND type='doc_creato'",[$id]);
  $corsi=$n("SELECT COUNT(*) c FROM events WHERE account_id=? AND type='partner_click'",[$id]);
  $conf=min(250,(int)($auditScore*1.5)+min($docs,5)*10+min($corsi,3)*10);
  // ATTIVITA 0-250, azioni ultimi 30 giorni
  $att=min(250,$n("SELECT COUNT(*) c FROM events WHERE account_id=? AND created_at>=?",[$id,$mese])*8);
  // FIDUCIA 0-250, profilo, email verificata, telegram, kyc, 2fa
  $fid=0;
  if(!empty($a['nome'])&&!empty($a['tel'])&&!empty($a['indirizzo']))$fid+=70;
  if(!empty($a['email_verified']))$fid+=40;
  if(!empty($a['telegram_id']))$fid+=50;
  $fid+=min(2,(int)($a['kyc_level']??0))*30;
  if(!empty($a['twofa']))$fid+=60;
  $fid=min(250,$fid);
  // RETE 0-250, diretti e profondità
  $dir=$n("SELECT COUNT(*) c FROM accounts WHERE ref_by=?",[$a['sic']]);
  $rete=min(250,$dir*25);
  $vita=$conf+$att+$fid+$rete;
  return ['conformita'=>$conf,'attivita'=>$att,'fiducia'=>$fid,'rete'=>$rete,'vita'=>$vita,
          'soglie'=>[['Recluta',0],['Operativo',250],['Sergente',1000],['Capitano',2500],['Comandante',5000],['Ammiraglio',10000]]];
}
