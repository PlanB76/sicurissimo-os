<?php
// api/create-deadline.php — Scadenziario81+
// POST: {categoria, nome, data_scadenza, note}

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/scadenziario.php';

header('Content-Type: application/json; charset=utf-8');

$user = auth_guard();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$categoria = trim($body['categoria'] ?? '');
$nome      = trim($body['nome'] ?? '');
$data      = trim($body['data_scadenza'] ?? '');
$note      = trim($body['note'] ?? '');

if (!$categoria || !$nome || !$data) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'categoria, nome e data obbligatori']);
    exit;
}

try {
    $id = Scadenziario81::create($user['id'], $categoria, $nome, $data, $note);
    echo json_encode(['ok' => true, 'id' => $id]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Errore salvataggio scadenza']);
}
