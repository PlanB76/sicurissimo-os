<?php
// api/login.php — Autenticazione utente
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_err('Metodo non consentito', 405);

csrf_check();

$email = trim($_POST['email'] ?? '');
$pass  = $_POST['password']  ?? '';
$redir = $_POST['redir']     ?? '';

if (!$email || !$pass) json_err('Inserisci email e password');

$db = DB::get();
$stmt = $db->prepare('SELECT u.*, w.pv, w.pvplus FROM users u LEFT JOIN wallets w ON w.user_id = u.id WHERE u.email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($pass, $user['password_hash'])) {
    json_err('Credenziali non valide');
}

// Aggiorna last_login
$db->prepare('UPDATE users SET last_login = NOW() WHERE id = ?')->execute([$user['id']]);

// Carica sessione
$_SESSION['user_id']         = (int)$user['id'];
$_SESSION['sic_id']          = $user['sic_id'];
$_SESSION['nome']            = $user['nome'];
$_SESSION['email']           = $user['email'];
$_SESSION['ruolo']           = $user['ruolo'];
$_SESSION['genesys_status']  = $user['genesys_status'] ?? 'NONE';
$_SESSION['membership']      = $user['membership_tier'] ?? 'NONE';
$_SESSION['pv_balance']      = (float)($user['pv'] ?? 0);

// Determina redirect
$base_url  = BASE_URL;
$safe_redir = $redir && str_starts_with($redir, '/') ? $base_url . $redir : $base_url . '/dashboard.php';

json_ok(['redirect' => $safe_redir]);
