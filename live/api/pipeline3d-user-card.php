<?php
// api/pipeline3d-user-card.php — scheda utente dalla pipeline
// GET: ?target_id=

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/pipeline3d_service.php';

header('Content-Type: application/json; charset=utf-8');

$user = auth_guard(['NETWORKER81', 'ELITE81', 'ADMIN81']);

$target_id = (int)($_GET['target_id'] ?? 0);
if (!$target_id) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'target_id obbligatorio']);
    exit;
}

try {
    $card = Pipeline3DService::getUserCard($user, $target_id);
    if (!$card) {
        http_response_code(404);
        echo json_encode(['ok' => false, 'error' => 'Utente non trovato nella pipeline']);
        exit;
    }
    echo json_encode(['ok' => true, 'data' => $card]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Errore scheda utente pipeline']);
}
