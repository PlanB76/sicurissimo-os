<?php
// api/life-wheel-save.php — Salva Ruota della Vita + credita PV+
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/auth_guard.php';
require_once dirname(__DIR__) . '/core81/pvplus_booster_service.php';

header('Content-Type: application/json');

$user = auth_guard(['MEMBER81', 'NETWORKER81', 'ELITE81', 'ADMIN81']);
$db   = DB::get();
$uid  = (int)$user['id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true) ?? [];

$dims = ['salute', 'famiglia', 'lavoro', 'denaro', 'crescita', 'community', 'divertimento', 'ambiente'];
$scores = [];
foreach ($dims as $d) {
    $v = (int)($data[$d] ?? 5);
    $scores[$d] = max(1, min(10, $v));
}

$today = date('Y-m-d');

try {
    $db->prepare(
        'INSERT INTO life_wheel_scores
           (user_id, data_rilevazione, salute, famiglia, lavoro, denaro, crescita, community, divertimento, ambiente)
         VALUES (?,?,?,?,?,?,?,?,?,?)
         ON DUPLICATE KEY UPDATE
           salute=VALUES(salute), famiglia=VALUES(famiglia), lavoro=VALUES(lavoro),
           denaro=VALUES(denaro), crescita=VALUES(crescita), community=VALUES(community),
           divertimento=VALUES(divertimento), ambiente=VALUES(ambiente)'
    )->execute([
        $uid, $today,
        $scores['salute'], $scores['famiglia'], $scores['lavoro'], $scores['denaro'],
        $scores['crescita'], $scores['community'], $scores['divertimento'], $scores['ambiente'],
    ]);

    // Controlla se è la prima volta in assoluto → LIFE_WHEEL_FIRST
    $count_stmt = $db->prepare('SELECT COUNT(*) FROM life_wheel_scores WHERE user_id = ?');
    $count_stmt->execute([$uid]);
    $is_first = (int)$count_stmt->fetchColumn() === 1;

    $claim_code = $is_first ? 'LIFE_WHEEL_FIRST' : 'LIFE_WHEEL_WEEKLY';
    // Per il weekly usa la settimana ISO come riferimento (idempotente)
    $riferimento = $is_first ? null : date('o-W'); // es. "2025-25"
    $claim = PVPlusBoosterService::claimMission($uid, $claim_code, $riferimento);

    $media = array_sum($scores) / count($scores);

    echo json_encode([
        'ok'      => true,
        'media'   => round($media, 2),
        'pvplus'  => $claim['pvplus'] ?? 0,
        'already' => $claim['already_claimed'] ?? false,
    ]);
} catch (\Throwable $e) {
    error_log('[life-wheel-save] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Errore interno']);
}
