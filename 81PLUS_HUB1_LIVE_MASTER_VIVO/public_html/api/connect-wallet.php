<?php
// api/connect-wallet.php — Collega wallet BSC/BEP20
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/auth_guard.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_err('Metodo non consentito', 405);
$user = auth_guard();
csrf_check();

$wallet_address = trim($_POST['wallet_address'] ?? '');
$signature      = trim($_POST['signature']      ?? '');

// Validazione formato indirizzo ETH/BSC
if (!preg_match('/^0x[0-9a-fA-F]{40}$/', $wallet_address)) {
    json_err('Indirizzo wallet non valido. Usa un indirizzo BSC/BEP-20 valido.');
}

if (!$signature) json_err('Firma richiesta per verificare la proprieta del wallet');

// In produzione: verificare la firma crittografica del messaggio
// Per ora salva l'indirizzo come predisposizione per BLOCCO 3
$db = DB::get();
$db->prepare('UPDATE users SET wallet_address = ? WHERE id = ?')
   ->execute([$wallet_address, $user['id']]);

json_ok(['msg' => 'Wallet collegato. La verifica crittografica sara abilitata nella prossima release.', 'wallet' => $wallet_address]);
