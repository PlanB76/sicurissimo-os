<?php
// api/pvplus-claim.php — claim PV+ per missione completata (idempotente, server-side)
// POST: {codice_missione, riferimento?}

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/pvplus_booster_service.php';

header('Content-Type: application/json; charset=utf-8');

$user = auth_guard();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$codice    = trim($body['codice_missione'] ?? '');
$riferimento = trim($body['riferimento'] ?? '') ?: null;

if (!$codice) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'codice_missione obbligatorio']);
    exit;
}

try {
    // Idempotente: se già claimato ritorna ok senza rierogare
    $result = PVPlusBoosterService::claimMission($user['id'], $codice, $riferimento);
    echo json_encode(['ok' => true, ...$result]);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
