<?php
// core81/auth_guard.php — verifica sessione e ruolo
declare(strict_types=1);

function auth_guard(array $ruoli_richiesti = []): array {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['user_id']) || empty($_SESSION['sic_id'])) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'error' => 'Non autenticato']);
        exit;
    }

    if (!empty($ruoli_richiesti) && !in_array($_SESSION['ruolo'] ?? '', $ruoli_richiesti, true)) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'error' => 'Non autorizzato']);
        exit;
    }

    return [
        'id'             => (int)$_SESSION['user_id'],
        'sic_id'         => $_SESSION['sic_id'],
        'ruolo'          => $_SESSION['ruolo'] ?? 'MEMBER81',
        'genesys_status' => $_SESSION['genesys_status'] ?? 'NONE',
        'email'          => $_SESSION['email'] ?? '',
    ];
}
