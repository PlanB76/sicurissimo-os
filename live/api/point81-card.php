<?php
// api/point81-card.php — scheda area POINT81+
// GET: ?area_id=

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/territory_service.php';

header('Content-Type: application/json; charset=utf-8');

$user = auth_guard(['ELITE81', 'ADMIN81']);

$area_id = (int)($_GET['area_id'] ?? 0);
if (!$area_id) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'area_id obbligatorio']);
    exit;
}

try {
    $card = TerritoryService::getAreaCard($user, $area_id);
    echo json_encode(['ok' => true, 'data' => $card]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Errore scheda area']);
}
