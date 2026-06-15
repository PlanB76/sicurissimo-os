<?php
// api/scout-prospects.php — lista prospect con filtri
// GET: ?filtri=...&page=&per_page=

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/scout81_service.php';

header('Content-Type: application/json; charset=utf-8');

$user = auth_guard(['NETWORKER81', 'ELITE81', 'ADMIN81']);

$filtri = $_GET;
$page   = max(1, (int)($_GET['page'] ?? 1));
$per    = min(50, max(10, (int)($_GET['per_page'] ?? 20)));

try {
    $result = Scout81Service::getProspects($user, $filtri, $page, $per);
    echo json_encode(['ok' => true, ...$result]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Errore prospect SCOUT81+']);
}
