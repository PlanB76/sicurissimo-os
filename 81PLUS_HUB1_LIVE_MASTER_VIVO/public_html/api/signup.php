<?php
// api/signup.php — Registrazione utente 81plus.net
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/welcome_email.php';
require_once dirname(__DIR__) . '/core81/wallet_service.php';
require_once dirname(__DIR__) . '/core81/referral_service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_err('Metodo non consentito', 405);
csrf_check();

$nome    = trim($_POST['nome']    ?? '');
$email   = strtolower(trim($_POST['email'] ?? ''));
$pass    = $_POST['password']     ?? '';
$settore = trim($_POST['settore'] ?? '');
$ref_sic = trim($_POST['ref']     ?? $_GET['ref'] ?? '');

if (!$nome)  json_err('Nome obbligatorio');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) json_err('Email non valida');
if (strlen($pass) < 8) json_err('Password troppo corta (minimo 8 caratteri)');
if (empty($_POST['privacy'])) json_err('Devi accettare la Privacy Policy');

$db = DB::get();

$stmt = $db->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
if ($stmt->fetch()) json_err('Email gia registrata. Usa Accedi o recupera la password.');

$hash = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);

// SIC-ID placeholder
$tmp_sic = 'SIC' . date('ymd') . strtoupper(bin2hex(random_bytes(4)));

$db->beginTransaction();
try {
    // Registra utente
    $stmt = $db->prepare(
        'INSERT INTO users (sic_id, email, password_hash, nome, settore, ruolo, genesys_status, membership_tier, is_active, created_at)
         VALUES (?, ?, ?, ?, ?, "MEMBER81", "NONE", "NONE", 1, NOW())'
    );
    $stmt->execute([$tmp_sic, $email, $hash, $nome, $settore]);
    $user_id = (int)$db->lastInsertId();

    // SIC-ID definitivo: SIC + yymmdd + user_id base36
    $sic_id = 'SIC' . date('ymd') . strtoupper(base_convert($user_id, 10, 36)) . strtoupper(substr(md5($email . $_ENV['SIC_SECRET'] ?? 'x'), 0, 4));
    $db->prepare('UPDATE users SET sic_id = ? WHERE id = ?')->execute([$sic_id, $user_id]);

    // Profilo base
    $db->prepare('INSERT INTO user_profiles (user_id) VALUES (?)')->execute([$user_id]);

    // Wallet
    $db->prepare('INSERT INTO wallets (user_id) VALUES (?)')->execute([$user_id]);

    $db->commit();
} catch (Throwable $e) {
    $db->rollBack();
    error_log('[SIGNUP ERROR] ' . $e->getMessage());
    json_err('Errore durante la registrazione. Riprova tra qualche momento.', 500);
}

// Welcome PV+ 100 standard (idempotente)
WalletService::credit_pvplus($user_id, WELCOME_PVPLUS_STANDARD, 'WELCOME_BONUS', 'welcome_' . $user_id);

// Referral handling
if ($ref_sic) {
    ReferralService::on_signup($user_id, $ref_sic);
}

// GENESYS promo: se arriva da ref GENESYS o da URL genesys
$is_genesys_ref = !empty($_POST['genesys_ref']) || !empty($_GET['genesys_ref']);
if (GENESYS_PROMO_ACTIVE && $is_genesys_ref) {
    $promo_expires = date('Y-m-d', strtotime('+' . GENESYS_PROMO_DAYS . ' days'));
    $db->prepare('UPDATE users SET genesys_status="GENESYS_MEMBER", genesys_promo_active=1, genesys_promo_expires=? WHERE id=?')
       ->execute([$promo_expires, $user_id]);
    WalletService::credit_pvplus($user_id, GENESYS_PROMO_PVPLUS, 'GENESYS_SIGNUP_PROMO', 'genesys_promo_' . $user_id);
}

// Sessione
$_SESSION['user_id']         = $user_id;
$_SESSION['sic_id']          = $sic_id;
$_SESSION['nome']            = $nome;
$_SESSION['email']           = $email;
$_SESSION['ruolo']           = 'MEMBER81';
$_SESSION['genesys_status']  = $is_genesys_ref && GENESYS_PROMO_ACTIVE ? 'GENESYS_MEMBER' : 'NONE';
$_SESSION['membership']      = 'NONE';
$_SESSION['pv_balance']      = 0;

// Email di benvenuto da welcome@81plus.net
try { send_welcome_email($email, $nome, $sic_id); } catch (Throwable) {}

json_ok(['sic_id' => $sic_id, 'redirect' => BASE_URL . '/dashboard.php?benvenuto=1']);
