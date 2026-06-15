<?php
// api/scout-save-filter.php — salva filtro SCOUT81+
// POST: {nome, filtri}

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/scout81_service.php';

header('Content-Type: application/json; charset=utf-8');

$user = auth_guard(['NETWORKER81', 'ELITE81', 'ADMIN81']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$body  = json_decode(file_get_contents('php://input'), true) ?? [];
$nome  = trim($body['nome'] ?? '');
$filtri = $body['filtri'] ?? [];

if (!$nome) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'nome obbligatorio']);
    exit;
}

try {
    $id = Scout81Service::saveFilter($user['id'], $nome, $filtri);
    echo json_encode(['ok' => true, 'id' => $id]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Errore salvataggio filtro']);
}
