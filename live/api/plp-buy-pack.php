<?php
// api/plp-buy-pack.php — acquisto pack PLP81+
// POST: {pack_id}

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/plp_pack_service.php';

header('Content-Type: application/json; charset=utf-8');

$user = auth_guard(['NETWORKER81', 'ELITE81', 'ADMIN81']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$body   = json_decode(file_get_contents('php://input'), true) ?? [];
$pack_id = (int)($body['pack_id'] ?? 0);

if (!$pack_id) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'pack_id obbligatorio']);
    exit;
}

try {
    $order = PlpPackService::buyPack($user['id'], $pack_id);
    echo json_encode(['ok' => true, 'order_id' => $order['id'], 'prospect_count' => $order['prospect_count']]);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
