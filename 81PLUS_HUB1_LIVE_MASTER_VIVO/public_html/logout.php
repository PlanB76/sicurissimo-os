<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

// Svuota tutte le variabili di sessione
$_SESSION = [];

// Rimuove il cookie di sessione
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(
        session_name(), '', time() - 42000,
        $p['path'], $p['domain'], $p['secure'], $p['httponly']
    );
}

session_destroy();
redirect('/login.php?logout=1');
