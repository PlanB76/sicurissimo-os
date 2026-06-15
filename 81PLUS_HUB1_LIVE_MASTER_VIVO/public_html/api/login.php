<?php
// api/login.php — Autenticazione utente (email o username)
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_err('Metodo non consentito', 405);

csrf_check();

$login = trim($_POST['login'] ?? $_POST['email'] ?? '');
$pass  = $_POST['password'] ?? '';
$redir = $_POST['redir']    ?? '';

if (!$login || !$pass) json_err('Inserisci le credenziali di accesso');

$db = DB::get();
$stmt = $db->prepare(
    'SELECT u.*, w.pv_balance AS pv, w.pvplus_balance AS pvplus
     FROM users u
     LEFT JOIN wallets w ON w.user_id = u.id
     WHERE (u.email = ? OR u.username = ?)
     LIMIT 1'
);
$stmt->execute([$login, $login]);
$user = $stmt->fetch();

if (!$user || !password_verify($pass, $user['password_hash'])) {
    json_err('Credenziali non valide');
}

// Verifica account attivo
if (isset($user['is_active']) && !(int)$user['is_active']) {
    json_err('Account sospeso o disattivato. Contatta la direzione per assistenza.');
}

// Verifica status (colonna presente in tabella base)
if (!empty($user['status']) && $user['status'] !== 'ACTIVE') {
    json_err('Account non disponibile. Contatta la direzione per assistenza.');
}

$db->prepare('UPDATE users SET last_login = NOW() WHERE id = ?')->execute([$user['id']]);

$_SESSION['user_id']         = (int)$user['id'];
$_SESSION['sic_id']          = $user['sic_id'];
$_SESSION['nome']            = $user['nome'];
$_SESSION['email']           = $user['email'];
$_SESSION['ruolo']           = $user['ruolo'];
$_SESSION['genesys_status']  = $user['genesys_status'] ?? 'NONE';
$_SESSION['membership']      = $user['membership_tier'] ?? 'NONE';
$_SESSION['pv_balance']      = (float)($user['pv'] ?? 0);

$safe_redir = $redir && str_starts_with($redir, '/') ? BASE_URL . $redir : BASE_URL . '/dashboard.php';

json_ok(['redirect' => $safe_redir]);
