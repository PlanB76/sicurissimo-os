<?php
// SFERA81+ — Lista missioni disponibili per utente
// GET: user_id
require_once __DIR__ . '/../../../_bootstrap.php';

$user_id = (int)($_GET['user_id'] ?? 0);
if (!$user_id) sfera_error('user_id obbligatorio');

$user = sfera_get_user($user_id);
if (!$user) sfera_error('Utente non trovato', 404);

$status  = $user['status'];
$haccp   = (int)$user['haccp_applicable'];
$level   = (int)$user['current_escalation_level'];
$pdo     = sfera_pdo();

$status_order = ['MEMBER81+'=>1,'NETWORKERS81+'=>2,'ELITE81+'=>3,'FRANCHISER81+'=>4,'CLUB81+'=>5];
$user_rank    = $status_order[$status] ?? 1;

// Missioni disponibili per questo profilo
$st = $pdo->prepare(
    "SELECT m.*, um.status as user_status, um.completed_at, um.pvplus_awarded
     FROM sfera_missions m
     LEFT JOIN sfera_user_missions um ON um.mission_id=m.id AND um.user_id=?
     WHERE m.active=1
     AND (m.haccp_required=0 OR (m.haccp_required=1 AND ?=1))
     AND m.escalation_level <= ?
     ORDER BY m.escalation_level ASC, m.pvplus_reward DESC"
);
$st->execute([$user_id, $haccp, $level + 1]);
$missions = $st->fetchAll();

$result = [];
foreach ($missions as $m) {
    $status_rank = $status_order[$m['status_min']] ?? 1;
    if ($status_rank > $user_rank) continue;
    $result[] = [
        'id'           => (int)$m['id'],
        'code'         => $m['mission_code'],
        'name'         => $m['mission_name'],
        'desc'         => $m['mission_desc'],
        'game'         => $m['game_code'],
        'pvplus'       => (int)$m['pvplus_reward'],
        'frequency'    => $m['frequency'],
        'level'        => (int)$m['escalation_level'],
        'status'       => $m['user_status'] ?? 'open',
        'completed_at' => $m['completed_at'],
        'repeatable'   => (bool)$m['repeatable'],
        'required_once'=> (bool)$m['required_once'],
    ];
}

sfera_response(['ok'=>true,'missions'=>$result,'count'=>count($result),'user_status'=>$status,'level'=>$level]);
