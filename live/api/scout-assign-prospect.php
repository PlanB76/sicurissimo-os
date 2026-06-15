<?php
// api/scout-assign-prospect.php — assegna prospect a networker
// POST: {prospect_id, networker_id?} — solo admin può specificare networker_id diverso

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

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$prospect_id   = (int)($body['prospect_id'] ?? 0);
$networker_id  = (int)($body['networker_id'] ?? $user['id']);

// Solo admin può assegnare ad altri
if ($networker_id !== $user['id'] && $user['ruolo'] !== 'ADMIN81') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Non autorizzato']);
    exit;
}

if (!$prospect_id) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'prospect_id obbligatorio']);
    exit;
}

try {
    $assignment_id = Scout81Service::assignProspect($prospect_id, $networker_id);
    echo json_encode(['ok' => true, 'assignment_id' => $assignment_id]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Errore assegnazione prospect']);
}
