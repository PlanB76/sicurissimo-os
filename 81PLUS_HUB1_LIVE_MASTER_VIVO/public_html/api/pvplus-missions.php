<?php
// api/pvplus-missions.php — missioni PV+ disponibili per utente
// GET: ?categoria=

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/pvplus_booster_service.php';

header('Content-Type: application/json; charset=utf-8');

$user = auth_guard();
$categoria = $_GET['categoria'] ?? null;

try {
    $missions = PVPlusBoosterService::getMissionsForUser($user, $categoria);
    echo json_encode(['ok' => true, 'missions' => $missions]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Errore missioni PV+']);
}
