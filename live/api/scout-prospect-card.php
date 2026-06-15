<?php
// api/scout-prospect-card.php — scheda singolo prospect
// GET: ?id=

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/scout81_service.php';

header('Content-Type: application/json; charset=utf-8');

$user = auth_guard(['NETWORKER81', 'ELITE81', 'ADMIN81']);

$prospect_id = (int)($_GET['id'] ?? 0);
if (!$prospect_id) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'id obbligatorio']);
    exit;
}

try {
    $card = Scout81Service::getProspectCard($user, $prospect_id);
    if (!$card) {
        http_response_code(404);
        echo json_encode(['ok' => false, 'error' => 'Prospect non trovato']);
        exit;
    }
    echo json_encode(['ok' => true, 'data' => $card]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Errore scheda prospect']);
}
