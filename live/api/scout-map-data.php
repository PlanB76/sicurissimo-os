<?php
// api/scout-map-data.php — dati mappa SCOUT81+
// GET: ?regione=&provincia=&comune=&ateco=&rischio=&status=

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/scout81_service.php';

header('Content-Type: application/json; charset=utf-8');

$user = auth_guard(['NETWORKER81', 'ELITE81', 'ADMIN81']);

$filtri = [
    'regione'   => $_GET['regione'] ?? null,
    'provincia' => $_GET['provincia'] ?? null,
    'comune'    => $_GET['comune'] ?? null,
    'ateco'     => $_GET['ateco'] ?? null,
    'rischio'   => $_GET['rischio'] ?? null,
    'status'    => $_GET['status'] ?? null,
];

try {
    $data = Scout81Service::getMapData($user, $filtri);
    echo json_encode(['ok' => true, 'data' => $data]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Errore mappa SCOUT81+']);
}
