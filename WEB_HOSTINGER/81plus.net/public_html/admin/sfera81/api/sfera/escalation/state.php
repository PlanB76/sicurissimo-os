<?php
// SFERA81+ — Stato ESCALATION81+
// GET: user_id
require_once __DIR__ . '/../../../_bootstrap.php';

$user_id = (int)($_GET['user_id'] ?? 0);
if (!$user_id) sfera_error('user_id obbligatorio');

$user = sfera_get_user($user_id);
if (!$user) sfera_error('Utente non trovato', 404);

$current = (int)$user['current_escalation_level'];
$pdo     = sfera_pdo();

$levels_def = $pdo->query('SELECT * FROM sfera_escalation_levels ORDER BY level_number')->fetchAll();

// Missioni completate per livello
$st = $pdo->prepare(
    "SELECT m.escalation_level, COUNT(*) as done
     FROM sfera_user_missions um
     JOIN sfera_missions m ON m.id=um.mission_id
     WHERE um.user_id=? AND um.status='completed'
     GROUP BY m.escalation_level"
);
$st->execute([$user_id]);
$done_map = [];
foreach ($st->fetchAll() as $r) $done_map[(int)$r['escalation_level']] = (int)$r['done'];

// Totale missioni per livello
$st = $pdo->prepare(
    "SELECT escalation_level, COUNT(*) as total FROM sfera_missions
     WHERE active=1 AND required_once=1 GROUP BY escalation_level"
);
$st->execute();
$total_map = [];
foreach ($st->fetchAll() as $r) $total_map[(int)$r['escalation_level']] = (int)$r['total'];

$levels = [];
foreach ($levels_def as $l) {
    $n     = (int)$l['level_number'];
    $done  = $done_map[$n] ?? 0;
    $total = $total_map[$n] ?? 1;
    if ($n < $current)      $status = 'completed';
    elseif ($n === $current) $status = 'active';
    else                     $status = 'locked';

    $levels[] = [
        'number'      => $n,
        'code'        => $l['level_code'],
        'name'        => $l['level_name'],
        'maslow'      => $l['maslow_level'],
        'objective'   => $l['objective'],
        'color'       => $l['color'],
        'pvplus_reward'=> (int)$l['pvplus_reward'],
        'status'      => $status,
        'missions_done'  => $done,
        'missions_total' => $total,
        'progress_pct'   => $total > 0 ? round($done / $total * 100) : 0,
    ];
}

sfera_response([
    'ok'            => true,
    'current_level' => $current,
    'levels'        => $levels,
    'pvplus_balance'=> (int)$user['pvplus_balance'],
]);
