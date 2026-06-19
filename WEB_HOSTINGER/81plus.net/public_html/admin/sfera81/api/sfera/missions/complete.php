<?php
// SFERA81+ — Completa missione
// POST: user_id, mission_id
require_once __DIR__ . '/../../../_bootstrap.php';

$p          = sfera_require_post();
$user_id    = (int)($p['user_id'] ?? 0);
$mission_id = (int)($p['mission_id'] ?? 0);
if (!$user_id || !$mission_id) sfera_error('user_id e mission_id obbligatori');

$pdo  = sfera_pdo();
$user = sfera_get_user($user_id);
if (!$user) sfera_error('Utente non trovato', 404);

$st = $pdo->prepare('SELECT * FROM sfera_missions WHERE id=? AND active=1');
$st->execute([$mission_id]);
$mission = $st->fetch();
if (!$mission) sfera_error('Missione non trovata', 404);

// Verifica se già completata (required_once)
$st = $pdo->prepare('SELECT * FROM sfera_user_missions WHERE user_id=? AND mission_id=?');
$st->execute([$user_id, $mission_id]);
$um = $st->fetch();

if ($um && $mission['required_once'] && $um['status'] === 'completed') {
    sfera_error('Missione già completata.', 409);
}

// Verifica cap giornaliero
$remaining = sfera_check_daily_mission_cap($user_id, date('Y-m-d'), $user['status']);
if ($remaining <= 0) {
    sfera_error('Cap PV+ giornaliero raggiunto per il tuo livello ' . $user['status'] . '. Torna domani.', 429);
}

$reward = min((int)$mission['pvplus_reward'], $remaining);
$now    = date('Y-m-d H:i:s');

// Upsert user_missions
if ($um) {
    $pdo->prepare(
        "UPDATE sfera_user_missions SET status='completed',completed_at=?,pvplus_awarded=pvplus_awarded+?,updated_at=? WHERE id=?"
    )->execute([$now, $reward, $now, $um['id']]);
} else {
    $pdo->prepare(
        "INSERT INTO sfera_user_missions (user_id,mission_id,status,started_at,completed_at,pvplus_awarded) VALUES (?,?,'completed',?,?,?)"
    )->execute([$user_id, $mission_id, $now, $now, $reward]);
}

// Accredita PV+
sfera_award_pvplus($user_id, $reward, 'Missione: ' . $mission['mission_name'], $mission['game_code'] ?? 'QUEST');

// Log evento
$pdo->prepare(
    'INSERT INTO sfera_event_log (user_id,sic_id,event_type,source_module,source_id,pvplus_delta) VALUES (?,?,?,?,?,?)'
)->execute([$user_id, $user['sic_id'], 'mission_complete', $mission['game_code'], $mission_id, $reward]);

$user = sfera_get_user($user_id);

sfera_response([
    'ok'            => true,
    'reward'        => $reward,
    'mission'       => $mission['mission_name'],
    'pvplus_balance'=> (int)$user['pvplus_balance'],
    'msg'           => '+' . $reward . ' PV+ per "' . $mission['mission_name'] . '".',
]);
