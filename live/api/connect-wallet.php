<?php
// api/connect-wallet.php — collega wallet BSC/BEP20 opzionale
// POST: {wallet_address, signature, message}

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/wallet_service.php';

header('Content-Type: application/json; charset=utf-8');

$user = auth_guard();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$address   = trim($body['wallet_address'] ?? '');
$signature = trim($body['signature'] ?? '');
$message   = trim($body['message'] ?? '');

if (!$address || !$signature || !$message) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'wallet_address, signature e message obbligatori']);
    exit;
}

// Verifica firma lato server (non via JS client-side)
try {
    $ok = WalletService::verifyAndConnect($user['id'], $address, $signature, $message);
    echo json_encode(['ok' => $ok]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Errore connessione wallet']);
}
