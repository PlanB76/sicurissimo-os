<?php
// api/nonce-wallet.php — Genera nonce per firma wallet BSC/BEP-20
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/auth_guard.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') json_err('Metodo non consentito', 405);
$user = auth_guard();

$nonce   = bin2hex(random_bytes(16));
$sic_id  = $user['sic_id'];
$message = "Collega wallet 81plus.net\nNonce: {$nonce}\nSIC-ID: {$sic_id}";

$_SESSION['wallet_nonce']    = $nonce;
$_SESSION['wallet_nonce_ts'] = time();

json_ok([
    'nonce'   => $nonce,
    'message' => $message,
    'expires' => 300,
]);
