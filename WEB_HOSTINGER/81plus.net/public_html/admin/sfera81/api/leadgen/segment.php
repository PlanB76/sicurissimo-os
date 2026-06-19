<?php
// SFERA81+ — Segmentazione lead
// POST: user_id — calcola e restituisce segmento leadgen
require_once __DIR__ . '/../../_bootstrap.php';

$p       = sfera_require_post();
$user_id = (int)($p['user_id'] ?? 0);
if (!$user_id) sfera_error('user_id obbligatorio');

$user = sfera_get_user($user_id);
if (!$user) sfera_error('Utente non trovato', 404);

$pdo = sfera_pdo();

$macro  = strtolower($user['macro_sector'] ?? '');
$ateco  = $user['ateco_code'] ?? '';
$risk   = $user['risk_level'] ?? 'medio';
$haccp  = (bool)$user['haccp_applicable'];
$priv   = (bool)$user['privacy_applicable'];
$safety = (bool)$user['safety_applicable'];
$level  = (int)$user['current_escalation_level'];
$streak = (int)$user['daily_streak'];

// Conta missioni completate
$stM = $pdo->prepare("SELECT COUNT(*) FROM sfera_user_missions um WHERE um.user_id=? AND um.status='completed'");
$stM->execute([$user_id]);
$missions_done = (int)$stM->fetchColumn();

// Ultimo accesso
$last = $user['last_access_date'];
$days_inactive = $last ? (int)floor((time() - strtotime($last)) / 86400) : 999;

// Calcolo segmento primario
$segment = 'non_profilato';
$heat    = 'freddo';
$tags    = [];

if (!$ateco) {
    $segment = 'non_profilato';
    $heat    = 'freddo';
} elseif ($missions_done >= 10 && $streak >= 7) {
    $segment = 'lead_caldo';
    $heat    = 'caldo';
} elseif ($missions_done >= 3) {
    $segment = 'profilo_completo';
    $heat    = 'tiepido';
} else {
    $segment = 'azienda';
    $heat    = 'tiepido';
}

// Tags settore
if (str_contains($macro, 'costruzion') || str_contains($macro, 'edilizia') || str_starts_with($ateco, '41') || str_starts_with($ateco, '42') || str_starts_with($ateco, '43')) {
    $tags[] = 'edilizia';
}
if ($haccp || str_contains($macro, 'alimentar') || str_contains($macro, 'ristoraz') || str_contains($macro, 'horeca')) {
    $tags[] = 'horeca';
}
if ($risk === 'alto') $tags[] = 'rischio_alto';
if ($risk === 'medio') $tags[] = 'rischio_medio';
if ($priv) $tags[] = 'privacy_prioritaria';
if ($safety) $tags[] = 'sicurezza_prioritaria';
if ($haccp) $tags[] = 'haccp_applicabile';
if ($days_inactive >= 30) $tags[] = 'lead_freddo';
if ($days_inactive >= 7 && $days_inactive < 30) $tags[] = 'riattivazione_7d';
if ($streak >= 81) $tags[] = 'presidio81';
if ($streak >= 30) $tags[] = 'streak_attiva';
if ($level >= 4) $tags[] = 'top_level';

// Flusso consigliato
$flow = 'WELCOME';
if (in_array('lead_freddo', $tags)) $flow = 'INACTIVITY_30D';
elseif (in_array('riattivazione_7d', $tags)) $flow = 'INACTIVITY_7D';
elseif (in_array('horeca', $tags) && !in_array('haccp_applicabile', $tags)) $flow = 'HACCP_TRIGGER';
elseif (in_array('edilizia', $tags)) $flow = 'EDILIZIA_TRIGGER';
elseif ($missions_done >= 1) $flow = 'FIRST_MISSION_DONE';
elseif ($ateco) $flow = 'ATECO_DONE';

sfera_response([
    'ok'            => true,
    'segment'       => $segment,
    'heat'          => $heat,
    'tags'          => $tags,
    'recommended_flow' => $flow,
    'missions_done' => $missions_done,
    'days_inactive' => $days_inactive,
    'streak'        => $streak,
    'escalation_level' => $level,
]);
