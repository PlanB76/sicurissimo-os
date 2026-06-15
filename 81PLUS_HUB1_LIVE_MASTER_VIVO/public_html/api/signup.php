<?php
// api/signup.php — Registrazione utente
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/welcome_email.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_err('Metodo non consentito', 405);
}

csrf_check();

$nome    = trim($_POST['nome']    ?? '');
$email   = trim($_POST['email']   ?? '');
$pass    = $_POST['password']     ?? '';
$settore = trim($_POST['settore'] ?? '');

if (!$nome || !$email || !$pass) json_err('Campi obbligatori mancanti');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) json_err('Email non valida');
if (strlen($pass) < 8) json_err('Password troppo corta (minimo 8 caratteri)');
if (empty($_POST['privacy'])) json_err('Devi accettare la Privacy Policy');

$db = DB::get();

// Verifica email univoca
$stmt = $db->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
if ($stmt->fetch()) json_err('Email gia registrata');

$hash   = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);
$sic_id = sic_id_generate(0, $email); // user_id 0, verra aggiornato dopo insert

$db->beginTransaction();
try {
    $stmt = $db->prepare('INSERT INTO users (nome, email, password_hash, settore, sic_id, ruolo, genesys_status, created_at) VALUES (?, ?, ?, ?, ?, "MEMBER81", "NONE", NOW())');
    $stmt->execute([$nome, $email, $hash, $settore, $sic_id]);
    $user_id = (int)$db->lastInsertId();

    // Aggiorna SIC-ID con user_id reale
    $sic_id = sic_id_generate($user_id, $email);
    $db->prepare('UPDATE users SET sic_id = ? WHERE id = ?')->execute([$sic_id, $user_id]);

    // Crea wallet
    $db->prepare('INSERT INTO wallets (user_id, pv, pvplus, saf, token81x, usdt) VALUES (?, 0, 0, 0, 0, 0)')->execute([$user_id]);

    $db->commit();
} catch (Throwable $e) {
    $db->rollBack();
    error_log('[SIGNUP ERROR] ' . $e->getMessage());
    json_err('Errore durante la registrazione. Riprova.', 500);
}

// Avvia sessione
$_SESSION['user_id']         = $user_id;
$_SESSION['sic_id']          = $sic_id;
$_SESSION['nome']            = $nome;
$_SESSION['email']           = $email;
$_SESSION['ruolo']           = 'MEMBER81';
$_SESSION['genesys_status']  = 'NONE';
$_SESSION['membership']      = 'NONE';
$_SESSION['pv_balance']      = 0;

// Invia email di benvenuto (non blocca la risposta se fallisce)
try {
    send_welcome_email($email, $nome, $sic_id);
} catch (Throwable) {}

json_ok(['sic_id' => $sic_id]);
