<?php
// SFERA81+ — RISK RADAR81+
// GET: user_id — Calcola priorità operativa del giorno
require_once __DIR__ . '/../../../_bootstrap.php';

$user_id = (int)($_GET['user_id'] ?? 0);
if (!$user_id) sfera_error('user_id obbligatorio');

$user = sfera_get_user($user_id);
if (!$user) sfera_error('Utente non trovato', 404);

$ateco  = $user['ateco_code'] ?? '';
$risk   = $user['risk_level'] ?? 'medio';
$haccp  = (bool)$user['haccp_applicable'];
$macro  = strtolower($user['macro_sector'] ?? '');

// Logica priorità basata su ATECO/RISCHIO
$priority = 'sicurezza lavoro';
$reason   = '';
$urgency  = 'media';

if (str_contains($macro, 'costruzion') || str_contains($macro, 'edilizia') || str_contains($ateco, '41') || str_contains($ateco, '42') || str_contains($ateco, '43')) {
    $priority = 'sicurezza lavoro';
    $reason   = 'ATECO edilizia, rischio alto. Formazione e documenti da verificare.';
    $urgency  = 'alta';
} elseif ($haccp || str_contains($macro, 'alimentar') || str_contains($macro, 'ristoraz') || str_contains($macro, 'horeca')) {
    $priority = 'HACCP';
    $reason   = 'Settore alimentare. Manuale HACCP e formazione addetti da verificare.';
    $urgency  = 'alta';
} elseif ($user['privacy_applicable'] && ($user['company_size'] ?? 1) <= 5) {
    $priority = 'privacy';
    $reason   = 'Tratti dati personali. Privacy policy e registro trattamenti da verificare.';
    $urgency  = 'media';
} elseif ($risk === 'alto') {
    $priority = 'sicurezza lavoro';
    $reason   = 'Profilo rischio alto. Verifica DVR e formazione lavoratori.';
    $urgency  = 'alta';
} elseif ($risk === 'medio') {
    $priority = 'formazione';
    $reason   = 'Rischio medio. Aggiorna la formazione dei lavoratori.';
    $urgency  = 'media';
} else {
    $priority = 'identità e profilo';
    $reason   = 'Completa il profilo per ricevere priorità operative personalizzate.';
    $urgency  = 'bassa';
}

// Missioni aperte per la priorità
$pdo = sfera_pdo();
$st  = $pdo->prepare(
    "SELECT m.mission_name, m.pvplus_reward FROM sfera_missions m
     LEFT JOIN sfera_user_missions um ON um.mission_id=m.id AND um.user_id=?
     WHERE m.active=1 AND (um.status IS NULL OR um.status != 'completed')
     ORDER BY m.escalation_level ASC, m.pvplus_reward DESC LIMIT 3"
);
$st->execute([$user_id]);
$open_missions = $st->fetchAll();

sfera_response([
    'ok'            => true,
    'priority_area' => $priority,
    'reason'        => $reason,
    'urgency'       => $urgency,
    'ateco'         => $ateco,
    'risk_level'    => $risk,
    'haccp'         => $haccp,
    'open_missions' => array_map(fn($m) => ['name'=>$m['mission_name'],'pvplus'=>(int)$m['pvplus_reward']], $open_missions),
    'message'       => 'La tua priorità oggi nasce dal tuo ATECO e dal tuo livello di rischio.',
]);
