<?php
// api/networker-leads.php — lead assegnati al networker
// GET: ?status=&page=

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/plp_pack_service.php';

header('Content-Type: application/json; charset=utf-8');

$user = auth_guard(['NETWORKER81', 'ELITE81', 'ADMIN81']);

$status  = $_GET['status'] ?? null;
$page    = max(1, (int)($_GET['page'] ?? 1));

try {
    $leads = PlpPackService::getNetworkerLeads($user['id'], $status, $page);
    echo json_encode(['ok' => true, ...$leads]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Errore caricamento lead']);
}
