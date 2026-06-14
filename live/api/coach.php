<?php
// 81+ COACH DEL GIORNO. Il piano quotidiano del membro, con AI se accesa, sempre coi dati veri.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php'; require_once __DIR__.'/../src/ai81.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401);
$c=aiCoach(db(),$a);
// ultimo commento AI all'audit del membro, se esiste
$nota=null;
try{ $q=db()->prepare("SELECT ai_note,score FROM audit_runs WHERE account_id=? AND ai_note IS NOT NULL ORDER BY id DESC LIMIT 1"); $q->execute([$a['id']]); if($r=$q->fetch()) $nota=['testo'=>$r['ai_note'],'score'=>(int)$r['score']]; }catch(Exception $e){}
j(['ok'=>true,'coach'=>$c,'audit_ai'=>$nota]);
