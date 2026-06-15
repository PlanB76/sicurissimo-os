<?php
// api/genesys-apply.php — Candidatura GENESYS81+
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/wallet_service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_err('Metodo non consentito', 405);
csrf_check();

$nome    = trim($_POST['nome']    ?? '');
$email   = strtolower(trim($_POST['email'] ?? ''));
$settore = trim($_POST['settore'] ?? '');
$nota    = trim($_POST['nota']    ?? '');
$ruolo_richiesto = trim($_POST['ruolo_richiesto'] ?? 'GENESYS_MEMBER');

if (!$nome || !filter_var($email, FILTER_VALIDATE_EMAIL)) json_err('Nome e email obbligatori');

$allowed_ruoli = ['GENESYS_MEMBER','GENESYS_NETWORKER','GENESYS_LEADER','GENESYS_FOUNDER'];
if (!in_array($ruolo_richiesto, $allowed_ruoli, true)) $ruolo_richiesto = 'GENESYS_MEMBER';

$db = DB::get();

// Cerca utente esistente
$stmt = $db->prepare('SELECT id, genesys_status FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$existing = $stmt->fetch();

if ($existing && $existing['genesys_status'] !== 'NONE') {
    json_err('Candidatura gia presente per questa email. Accedi alla tua area personale.');
}

// Verifica candidatura duplicata
$check = $db->prepare('SELECT id FROM genesys_applications WHERE email = ? AND status NOT IN ("REJECTED") LIMIT 1');
$check->execute([$email]);
if ($check->fetch()) json_err('Candidatura gia ricevuta. Ti contatteremo presto.');

$stmt = $db->prepare(
    'INSERT INTO genesys_applications (nome, email, settore, ruolo_richiesto, nota_candidato, status)
     VALUES (?, ?, ?, ?, ?, "PENDING")'
);
$stmt->execute([$nome, $email, $settore, $ruolo_richiesto, $nota]);

// Se utente loggato, aggiorna status GENESYS e accredita promo PV+
$user_id = (int)($_SESSION['user_id'] ?? 0);
if ($user_id && GENESYS_PROMO_ACTIVE) {
    $promo_expires = date('Y-m-d', strtotime('+' . GENESYS_PROMO_DAYS . ' days'));
    // Controlla se promo gia inviata
    $sent = $db->prepare('SELECT promo_pvplus_sent FROM genesys_applications WHERE email = ? ORDER BY created_at DESC LIMIT 1');
    $sent->execute([$email]);
    $row = $sent->fetch();
    if ($row && !$row['promo_pvplus_sent']) {
        $db->prepare('UPDATE users SET genesys_status = ?, genesys_promo_active = 1, genesys_promo_expires = ? WHERE id = ?')
           ->execute([$ruolo_richiesto, $promo_expires, $user_id]);
        WalletService::credit_pvplus($user_id, GENESYS_PROMO_PVPLUS, 'GENESYS_APPLICATION_PROMO', 'genesys_apply_' . $user_id);
        $db->prepare('UPDATE genesys_applications SET promo_pvplus_sent = 1 WHERE email = ?')->execute([$email]);
        $_SESSION['genesys_status'] = $ruolo_richiesto;
    }
}

json_ok([
    'msg'   => 'Candidatura ricevuta. Ti contatteremo via email entro 48 ore lavorative.',
    'promo' => GENESYS_PROMO_ACTIVE ? '+ ' . GENESYS_PROMO_PVPLUS . ' PV+ accreditati sulla tua area personale.' : null,
]);
