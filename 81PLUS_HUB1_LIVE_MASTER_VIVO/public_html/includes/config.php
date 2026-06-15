<?php
// includes/config.php — Configurazione ambiente 81plus.net HUB1
declare(strict_types=1);

// Carica .env se presente (produzione: usare variabili server reali)
if (file_exists(dirname(__DIR__, 2) . '/.env')) {
    foreach (file(dirname(__DIR__, 2) . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (str_contains($line, '=')) {
            [$k, $v] = explode('=', $line, 2);
            $_ENV[trim($k)] = trim($v);
        }
    }
}

// Costanti di sistema
define('APP_NAME',    '81plus.net');
define('APP_VERSION', '1.0.0-HUB1');
define('APP_ENV',     $_ENV['APP_ENV'] ?? 'production');
define('BASE_URL',    $_ENV['BASE_URL'] ?? 'https://81plus.net');

// Sessione sicura
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => (APP_ENV === 'production'),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// Carica core81
require_once __DIR__ . '/../core81/db.php';
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/semantic_guard.php';

// Timezone
date_default_timezone_set('Europe/Rome');
