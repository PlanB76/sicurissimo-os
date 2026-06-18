<?php
// api/daily-mission-today.php — Missioni giornaliere: lettura e completamento
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/auth_guard.php';
require_once dirname(__DIR__) . '/core81/pvplus_booster_service.php';

header('Content-Type: application/json');

$user  = auth_guard(['MEMBER81', 'NETWORKER81', 'ELITE81', 'ADMIN81']);
$db    = DB::get();
$today = date('Y-m-d');
$uid   = (int)$user['id'];

// ─── POST: completa missione ────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data       = json_decode(file_get_contents('php://input'), true) ?? [];
    $mission_id = (int)($data['mission_id'] ?? 0);

    if (!$mission_id) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'mission_id mancante']);
        exit;
    }

    $stmt = $db->prepare(
        'SELECT * FROM daily_missions
         WHERE id = ? AND user_id = ? AND data_assegnazione = ? AND status = "PENDING"'
    );
    $stmt->execute([$mission_id, $uid, $today]);
    $m = $stmt->fetch();

    if (!$m) {
        echo json_encode(['ok' => false, 'error' => 'Missione non trovabile o già completata']);
        exit;
    }

    try {
        // Credita PV+ usando il servizio esistente (idempotente)
        $riferimento = $today; // per RICORRENTE_GIORNALIERO: data = riferimento unico
        $claim = PVPlusBoosterService::claimMission($uid, $m['missione_codice'], $riferimento);

        // Segna completata nel log giornaliero
        $db->prepare(
            'UPDATE daily_missions SET status = "COMPLETED", completato_at = NOW() WHERE id = ?'
        )->execute([$mission_id]);

        echo json_encode([
            'ok'          => true,
            'pvplus'      => $claim['pvplus'] ?? 0,
            'already'     => $claim['already_claimed'] ?? false,
            'missione'    => $m['missione_nome'],
        ]);
    } catch (\Throwable $e) {
        error_log('[daily-mission] ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Errore interno']);
    }
    exit;
}

// ─── GET: carica/assegna missioni di oggi ────────────────────────────────────
$existing = $db->prepare(
    'SELECT * FROM daily_missions WHERE user_id = ? AND data_assegnazione = ? ORDER BY id ASC'
);
$existing->execute([$uid, $today]);
$missions = $existing->fetchAll();

if (empty($missions)) {
    $to_assign = [];

    // 1. Missione ricorrente giornaliera sempre presente
    $to_assign[] = [
        'codice'  => 'DAILY_COCKPIT',
        'nome'    => 'Missione del giorno completata',
        'reward'  => 25,
    ];

    // 2. Fino a 2 missioni ONETIME non ancora reclamate, priorità: ONBOARDING > AUDIT > CRESCITA
    $claimed = $db->prepare(
        'SELECT DISTINCT codice_missione FROM pvplus_claims WHERE user_id = ?'
    );
    $claimed->execute([$uid]);
    $claimed_codes = $claimed->fetchAll(\PDO::FETCH_COLUMN) ?: [];

    if (empty($claimed_codes)) {
        $pool = $db->prepare(
            'SELECT codice, nome, pvplus_base FROM pvplus_missions
             WHERE tipo = "ONETIME" AND attiva = 1
             ORDER BY FIELD(categoria,"ONBOARDING","AUDIT","CRESCITA","MASLOW","REFERRAL","STREAK") ASC
             LIMIT 2'
        );
        $pool->execute();
    } else {
        $placeholders = implode(',', array_fill(0, count($claimed_codes), '?'));
        $pool = $db->prepare(
            "SELECT codice, nome, pvplus_base FROM pvplus_missions
             WHERE tipo = 'ONETIME' AND attiva = 1
               AND codice NOT IN ($placeholders)
             ORDER BY FIELD(categoria,'ONBOARDING','AUDIT','CRESCITA','MASLOW','REFERRAL','STREAK') ASC
             LIMIT 2"
        );
        $pool->execute($claimed_codes);
    }
    foreach ($pool->fetchAll() as $row) {
        $to_assign[] = ['codice' => $row['codice'], 'nome' => $row['nome'], 'reward' => $row['pvplus_base']];
    }

    $ins = $db->prepare(
        'INSERT INTO daily_missions (user_id, data_assegnazione, missione_codice, missione_nome, pvplus_reward)
         VALUES (?,?,?,?,?)'
    );
    foreach ($to_assign as $m) {
        $ins->execute([$uid, $today, $m['codice'], $m['nome'], $m['reward']]);
    }

    $existing->execute([$uid, $today]);
    $missions = $existing->fetchAll();
}

echo json_encode(['ok' => true, 'date' => $today, 'missions' => $missions]);
