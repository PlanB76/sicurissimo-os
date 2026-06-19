<?php
// SFERA81+ — Stato LIFEWHEEL81+
// GET: user_id
require_once __DIR__ . '/../../../_bootstrap.php';

$user_id = (int)($_GET['user_id'] ?? 0);
if (!$user_id) sfera_error('user_id obbligatorio');

$user = sfera_get_user($user_id);
if (!$user) sfera_error('Utente non trovato', 404);

$cap  = sfera_get_lifewheel_cap($user['status']);
$pdo  = sfera_pdo();
$areas = $pdo->query('SELECT * FROM sfera_lifewheel_areas WHERE active=1 ORDER BY sort_order')->fetchAll();

$result = [];
$total_score = 0;
$weakest = null;
$weakest_score = 999;

foreach ($areas as $a) {
    // Salta HACCP se non applicabile
    $applicable = true;
    if ($a['haccp_sensitive'] && !$user['haccp_applicable']) $applicable = false;

    $st = $pdo->prepare('SELECT score, last_completed_at FROM sfera_lifewheel_progress WHERE user_id=? AND area_id=?');
    $st->execute([$user_id, $a['id']]);
    $prog = $st->fetch();
    $score = $prog ? min((int)$prog['score'], $cap) : 0;

    if ($applicable && $score < $weakest_score) {
        $weakest_score = $score;
        $weakest       = $a['sfera_area_name'];
    }
    if ($applicable) $total_score += $score;

    $result[] = [
        'id'             => (int)$a['id'],
        'life_area'      => $a['life_area_name'],
        'sfera_area'     => $a['sfera_area_name'],
        'code'           => $a['sfera_area_code'],
        'color'          => $a['color'],
        'score'          => $score,
        'cap'            => $cap,
        'applicable'     => $applicable,
        'last_completed' => $prog['last_completed_at'] ?? null,
        'at_cap'         => $score >= $cap,
    ];
}

$applicable_count = count(array_filter($result, fn($r) => $r['applicable']));
$avg_score = $applicable_count > 0 ? round($total_score / $applicable_count, 1) : 0;

sfera_response([
    'ok'           => true,
    'areas'        => $result,
    'overall_score'=> $avg_score,
    'status_cap'   => $cap,
    'status'       => $user['status'],
    'weakest_area' => $weakest,
]);
