<?php
// api/dashboard-data.php — dati dashboard per ruolo
// Richiede sessione autenticata con SIC-ID valido

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/dashboard_modules.php';

header('Content-Type: application/json; charset=utf-8');

$user = auth_guard(); // lancia 401 se non autenticato

try {
    $data = DashboardModules::loadForUser($user);
    echo json_encode(['ok' => true, 'data' => $data]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Errore caricamento dashboard']);
}
