<?php
// SFERA81+ V5 — Configurazione
// Copia questo file in config/config.php e inserisci i dati reali

define('DB_HOST',        'localhost');
define('DB_NAME',        'il_tuo_database_81plus');
define('DB_USER',        'il_tuo_utente_db');
define('DB_PASS',        'la_tua_password_db');
define('DB_CHARSET',     'utf8mb4');

// Secret per endpoint admin (cambia obbligatoriamente)
define('ADMIN_SECRET',   'cambia_questo_secret_admin_81plus_2026');

define('SFERA_VERSION',  '5.0');
define('BASE_URL',       'https://81plus.net/admin/sfera81');

// Cap giornaliero per status
define('CAP_MEMBER',       100);
define('CAP_NETWORKERS',   250);
define('CAP_ELITE',        400);
define('CAP_FRANCHISER',   600);
define('CAP_CLUB',        1000);
