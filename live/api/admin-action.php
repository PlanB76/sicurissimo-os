<?php
// api/admin-action.php — azioni Admin Command Center81+
// POST: {azione, target_user_id?, parametri, note}

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/admin_command_service.php';

header('Content-Type: application/json; charset=utf-8');

$user = auth_guard(['ADMIN81']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$azione = trim($body['azione'] ?? '');
$target = (int)($body['target_user_id'] ?? 0) ?: null;
$params = $body['parametri'] ?? [];
$note   = trim($body['note'] ?? '');

if (!$azione) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'azione obbligatoria']);
    exit;
}

try {
    $result = AdminCommandService::execute($user['id'], $azione, $target, $params, $note,
        $_SERVER['REMOTE_ADDR'] ?? null, $_SERVER['HTTP_USER_AGENT'] ?? null);
    echo json_encode(['ok' => true, 'result' => $result]);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
