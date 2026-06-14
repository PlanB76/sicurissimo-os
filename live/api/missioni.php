<?php
// 81+ MISSIONI E LIVELLI. La gamification che copre tutto il sito, calcolata dai dati veri.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
$pdo=db(); $id=$a['id'];
function ha($pdo,$sql,$par){ try{ $q=$pdo->prepare($sql); $q->execute($par); return (bool)$q->fetch(); }catch(Exception $e){ return false; } }
$pv=function_exists('pvBalance')?pvBalance($id):0;
$mis=[
 ['id'=>'registrazione','nome'=>'Entra nell\'ecosistema','pv'=>100,'fatta'=>true,'come'=>'SIC ID creato'],
 ['id'=>'profilo','nome'=>'Completa il profilo','pv'=>50,'fatta'=>!empty($a['nome'])&&!empty($a['tel'])&&!empty($a['indirizzo']),'come'=>'nome, telefono e indirizzo nel profilo'],
 ['id'=>'audit','nome'=>'Fai l\'audit gratuito','pv'=>25,'fatta'=>ha($pdo,"SELECT 1 FROM events WHERE account_id=? AND type LIKE 'audit%' LIMIT 1",[$id]),'come'=>'30 controlli in 2 minuti'],
 ['id'=>'telegram','nome'=>'Collega Telegram','pv'=>50,'fatta'=>!empty($a['telegram_id']),'come'=>'dalla card azzurra qui sotto'],
 ['id'=>'quiz','nome'=>'Vinci il primo quiz','pv'=>10,'fatta'=>ha($pdo,"SELECT 1 FROM pv_ledger WHERE account_id=? AND reason LIKE 'g_quiz_%' LIMIT 1",[$id]),'come'=>'ogni giorno alle 13 nei gruppi Telegram'],
 ['id'=>'invito','nome'=>'Invita il primo amico','pv'=>25,'fatta'=>ha($pdo,"SELECT 1 FROM pv_ledger WHERE account_id=? AND reason LIKE 'invito_registrato_%' LIMIT 1",[$id]),'come'=>'col tuo link referral o il QR'],
  ['id'=>'documento','nome'=>'Genera il primo documento','pv'=>15,'fatta'=>ha($pdo,"SELECT 1 FROM pv_ledger WHERE account_id=? AND reason='doc_generato' LIMIT 1",[$id]),'come'=>'dalla Fabbrica documenti'],
 ['id'=>'corso','nome'=>'Apri il catalogo corsi','pv'=>10,'fatta'=>ha($pdo,"SELECT 1 FROM events WHERE account_id=? AND type='partner_click' LIMIT 1",[$id]),'come'=>'formazione certificata, sezione corsi'],
];
$fatte=count(array_filter($mis,fn($m)=>$m['fatta']));
$livelli=[['Recluta',0],['Operativo',250],['Sergente',1000],['Capitano',2500],['Comandante',5000],['Ammiraglio',10000]];
$liv=$livelli[0]; $next=null;
foreach($livelli as $i=>$L){ if($pv>=$L[1]){ $liv=$L; $next=$livelli[$i+1]??null; } }
j(['ok'=>true,'pv'=>$pv,'missioni'=>$mis,'fatte'=>$fatte,'totale'=>count($mis),
   'livello'=>$liv[0],'livello_min'=>$liv[1],'prossimo'=>$next?$next[0]:null,'prossimo_min'=>$next?$next[1]:null]);
