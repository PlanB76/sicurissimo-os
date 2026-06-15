<?php
// includes/config.php — Configurazione ambiente 81plus.net HUB1
declare(strict_types=1);

if (file_exists(dirname(__DIR__, 2) . '/.env')) {
    foreach (file(dirname(__DIR__, 2) . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (str_contains($line, '=')) {
            [$k, $v] = explode('=', $line, 2);
            $_ENV[trim($k)] = trim($v);
        }
    }
}

define('APP_NAME',    '81plus.net');
define('APP_VERSION', '1.0.0-HUB1');
define('APP_ENV',     $_ENV['APP_ENV'] ?? 'production');
define('BASE_URL',    rtrim($_ENV['BASE_URL'] ?? 'https://81plus.net', '/'));

// ── GENESYS81+ Promo config ───────────────────────────────────────────────────
define('GENESYS_PROMO_ACTIVE',     (bool)($_ENV['GENESYS_PROMO_ACTIVE'] ?? true));
define('GENESYS_PROMO_DAYS',       (int)($_ENV['GENESYS_PROMO_DAYS'] ?? 90));
define('GENESYS_PROMO_PVPLUS',     1000);   // PV+ al signup GENESYS
define('GENESYS_PROFILE_PVPLUS',   1000);   // PV+ aggiuntivi profilo completo
define('WELCOME_PVPLUS_STANDARD',  100);    // PV+ welcome utente normale

// ── Membership config ─────────────────────────────────────────────────────────
define('MEMBERSHIP_PLANS', [
    'BASIC+' => ['pv' => 29.90, 'pvplus_prima' => 100.00,  'pvplus_rinnovo' => 30.00],
    'PRO+'   => ['pv' => 59.90, 'pvplus_prima' => 250.00,  'pvplus_rinnovo' => 60.00],
    'ELITE+' => ['pv' => 89.90, 'pvplus_prima' => 500.00,  'pvplus_rinnovo' => 90.00],
]);

// ── Sessione sicura ────────────────────────────────────────────────────────────
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

require_once __DIR__ . '/../core81/db.php';
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/semantic_guard.php';

date_default_timezone_set('Europe/Rome');
