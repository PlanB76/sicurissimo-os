<?php
// api/territory-map-data.php — dati TerritoryMap81+
// GET: ?regione=&provincia=

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/territory_service.php';

header('Content-Type: application/json; charset=utf-8');

$user = auth_guard(['ELITE81', 'ADMIN81']);

$filtri = [
    'regione'   => $_GET['regione'] ?? null,
    'provincia' => $_GET['provincia'] ?? null,
];

try {
    $data = TerritoryService::getMapData($user, $filtri);
    echo json_encode(['ok' => true, 'data' => $data]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Errore TerritoryMap81+']);
}
