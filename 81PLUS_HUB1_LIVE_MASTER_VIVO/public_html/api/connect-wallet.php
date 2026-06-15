<?php
// api/connect-wallet.php — Collega wallet BSC/BEP20 con nonce + firma
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/auth_guard.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_err('Metodo non consentito', 405);
$user = auth_guard();
csrf_check();

$wallet_address = strtolower(trim($_POST['wallet_address'] ?? ''));
$signature      = trim($_POST['signature']      ?? '');
$nonce          = trim($_POST['nonce']          ?? '');

if (!preg_match('/^0x[0-9a-f]{40}$/', $wallet_address)) {
    json_err('Indirizzo wallet non valido. Usa un indirizzo BSC/BEP-20 valido.');
}
if (!$signature) {
    json_err('Firma crittografica richiesta per verificare la proprieta del wallet.');
}

// Verifica nonce sessione (max 5 minuti)
$session_nonce    = $_SESSION['wallet_nonce']    ?? '';
$session_nonce_ts = (int)($_SESSION['wallet_nonce_ts'] ?? 0);

if (!$nonce || $nonce !== $session_nonce || (time() - $session_nonce_ts) > 300) {
    unset($_SESSION['wallet_nonce'], $_SESSION['wallet_nonce_ts']);
    json_err('Nonce non valido o scaduto. Richiedi un nuovo nonce e riprova.');
}
unset($_SESSION['wallet_nonce'], $_SESSION['wallet_nonce_ts']);

// Verifica formato firma (0x + 130 hex chars = 65 bytes)
// BLOCCO 3: sostituire con ecrecover via ethereum-php per verifica crittografica completa
$signature_format_ok = (strlen($signature) === 132 && str_starts_with($signature, '0x'));
if (!$signature_format_ok) {
    error_log('[WALLET BIND] Firma formato errato user_id=' . $user['id'] . ' wallet=' . $wallet_address);
    json_err('Formato firma non valido. Usa MetaMask per firmare il messaggio.');
}

$db = DB::get();

// Verifica che wallet non sia gia collegato a un altro account
$check = $db->prepare('SELECT id FROM users WHERE wallet_address = ? AND id != ? LIMIT 1');
$check->execute([$wallet_address, $user['id']]);
if ($check->fetch()) {
    $ip_h = hash('sha256', $_SERVER['REMOTE_ADDR'] ?? '');
    $db->prepare(
        'INSERT INTO wallet_bind_log (user_id, wallet_address, ip_hash, user_agent, status, created_at)
         VALUES (?, ?, ?, ?, "DUPLICATE", NOW())'
    )->execute([$user['id'], $wallet_address, $ip_h, substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 200)]);
    json_err('Questo indirizzo wallet e gia collegato a un altro account.');
}

$db->prepare('UPDATE users SET wallet_address = ? WHERE id = ?')
   ->execute([$wallet_address, $user['id']]);

// Log evento di collegamento wallet
$ip_h = hash('sha256', $_SERVER['REMOTE_ADDR'] ?? '');
$db->prepare(
    'INSERT INTO wallet_bind_log (user_id, wallet_address, ip_hash, user_agent, status, created_at)
     VALUES (?, ?, ?, ?, "SUCCESS", NOW())'
)->execute([$user['id'], $wallet_address, $ip_h, substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 200)]);

json_ok([
    'msg'    => 'Wallet collegato. Funzioni Web3 utility del sistema 81+ ora disponibili.',
    'wallet' => $wallet_address,
]);
