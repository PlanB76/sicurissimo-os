<?php
// Plancia DIO. Numeri veri dell’ecosistema. Protetta da ADMIN_KEY nelle variabili ambiente.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/ai81.php';
$pdo=db();
$key=getenv('ADMIN_KEY')?:'';
if(!$key || (($_GET['key']??'')!==$key)){ http_response_code(403); j(['ok'=>false,'err'=>'chiave non valida']); }
function q($pdo,$sql,$p=[]){ $st=$pdo->prepare($sql); $st->execute($p); return $st->fetchAll(); }
function n($pdo,$sql,$p=[]){ $r=q($pdo,$sql,$p); return (int)($r[0]['c']??0); }

$oggi=gmdate('Y-m-d');
$d=[
 'utenti_totali'=>n($pdo,'SELECT COUNT(*) c FROM accounts'),
 'utenti_attivi'=>n($pdo,"SELECT COUNT(*) c FROM accounts WHERE status='attivo'"),
 'da_attivare'=>n($pdo,"SELECT COUNT(*) c FROM accounts WHERE status='da_attivare'"),
 'registrati_oggi'=>n($pdo,"SELECT COUNT(*) c FROM accounts WHERE created_at LIKE ?",[$oggi.'%']),
 'profili_completi'=>n($pdo,"SELECT COUNT(DISTINCT account_id) c FROM pv_ledger WHERE reason='profilo_completo_bonus'"),
 'pv_totali'=>(int)($pdo->query('SELECT COALESCE(SUM(delta),0) s FROM pv_ledger WHERE delta>0')->fetch()['s']),
 'lead_totali'=>n($pdo,'SELECT COUNT(*) c FROM leads'),
 'newsletter_attivi'=>n($pdo,"SELECT COUNT(*) c FROM newsletter WHERE stato='ATTIVO'"),
 'welcome_in_coda'=>n($pdo,"SELECT COUNT(*) c FROM welcome_queue WHERE stato='ATTIVO'"),
 'welcome_completate'=>n($pdo,"SELECT COUNT(*) c FROM welcome_queue WHERE stato='COMPLETATO'"),
 'click_anfos'=>n($pdo,"SELECT COUNT(*) c FROM events WHERE type='partner_click' AND data LIKE '%\"to\":\"anfos\"%'"),
 'click_lezione'=>n($pdo,"SELECT COUNT(*) c FROM events WHERE type='partner_click' AND data LIKE '%\"to\":\"lezione\"%'"),
 'click_anfos_oggi'=>n($pdo,"SELECT COUNT(*) c FROM events WHERE type='partner_click' AND data LIKE '%\"to\":\"anfos\"%' AND created_at LIKE ?",[$oggi.'%']),
 'click_lezione_oggi'=>n($pdo,"SELECT COUNT(*) c FROM events WHERE type='partner_click' AND data LIKE '%\"to\":\"lezione\"%' AND created_at LIKE ?",[$oggi.'%']),
 'click_youtube'=>n($pdo,"SELECT COUNT(*) c FROM events WHERE type='partner_click' AND data LIKE '%\"to\":\"youtube%'"),
 'click_telegram'=>n($pdo,"SELECT COUNT(*) c FROM events WHERE type='partner_click' AND data LIKE '%\"to\":\"tg_%'"),
 'email_comportamentali'=>n($pdo,"SELECT COUNT(*) c FROM trigger_sent"),
 'seo'=>json_decode(@file_get_contents(__DIR__.'/../data/seo_stato.json'),true)?:null,
 'membri_caldi'=>aiCaloreMembri($pdo,12),
 'report_settimana'=>json_decode(@file_get_contents(__DIR__.'/../data/report_settimana.json'),true)?:null,
 'telegram_collegati'=>n($pdo,"SELECT COUNT(*) c FROM accounts WHERE telegram_id IS NOT NULL"),
 'telegram_stato'=>json_decode(@file_get_contents(__DIR__.'/../data/telegram_stato.json'),true)?:null,
 'telegram_chats'=>count((json_decode(@file_get_contents(__DIR__.'/../data/telegram_chats.json'),true)?:['chats'=>[]])['chats']),
 'bonus_promo_settimana'=>n($pdo,"SELECT COUNT(*) c FROM pv_ledger WHERE reason LIKE 'promo_week_%'"),
 'audit_fatti'=>n($pdo,"SELECT COUNT(*) c FROM events WHERE type='audit_run'") + n($pdo,"SELECT COUNT(*) c FROM audit_runs"),
 'kyc_livello1'=>n($pdo,'SELECT COUNT(*) c FROM accounts WHERE kyc_level>=1'),
 'badge_assegnati'=>n($pdo,'SELECT COUNT(*) c FROM badges'),
 'documenti_sbloccati'=>n($pdo,'SELECT COUNT(*) c FROM doc_unlocks'),
 'ultimi_eventi'=>q($pdo,'SELECT type,account_id,created_at FROM events ORDER BY id DESC LIMIT 20'),
 'ultimi_iscritti'=>q($pdo,"SELECT sic,email,status,created_at FROM accounts ORDER BY id DESC LIMIT 12"),
 'prossimi_invii'=>q($pdo,"SELECT email,serie,step,next_at FROM welcome_queue WHERE stato='ATTIVO' ORDER BY next_at ASC LIMIT 12")
];
j(['ok'=>true,'dati'=>$d,'generato'=>gmdate('c')]);
