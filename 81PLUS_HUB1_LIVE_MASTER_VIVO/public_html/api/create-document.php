<?php
// api/create-document.php — DOC81+ Builder
// POST: {tipo, titolo, dati_input}

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/document_builder.php';

header('Content-Type: application/json; charset=utf-8');

$user = auth_guard();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$tipo = trim($body['tipo'] ?? '');
$titolo = trim($body['titolo'] ?? '');
$dati = $body['dati_input'] ?? [];

if (!$tipo || !$titolo) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'tipo e titolo obbligatori']);
    exit;
}

try {
    $doc = DocumentBuilder::create($user['id'], $tipo, $titolo, $dati);
    echo json_encode(['ok' => true, 'doc_id' => $doc['id'], 'pdf_url' => $doc['pdf_url']]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Errore generazione documento']);
}
