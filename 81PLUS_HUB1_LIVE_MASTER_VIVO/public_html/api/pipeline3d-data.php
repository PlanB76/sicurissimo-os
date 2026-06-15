<?php
// api/pipeline3d-data.php — dati grafo Pipeline3D81+
// GET: ?filtri=...

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/pipeline3d_service.php';

header('Content-Type: application/json; charset=utf-8');

$user = auth_guard(['NETWORKER81', 'ELITE81', 'ADMIN81']);

$filtri = $_GET;

try {
    $graph = Pipeline3DService::getGraph($user, $filtri);
    echo json_encode(['ok' => true, 'nodes' => $graph['nodes'], 'edges' => $graph['edges']]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Errore Pipeline3D81+']);
}
