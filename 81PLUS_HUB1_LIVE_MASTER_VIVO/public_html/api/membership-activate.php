<?php
// api/membership-activate.php — Attiva membership con PV
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/auth_guard.php';
require_once dirname(__DIR__) . '/core81/wallet_service.php';
require_once dirname(__DIR__) . '/core81/membership_service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_err('Metodo non consentito', 405);
$user = auth_guard(['MEMBER81','NETWORKER81','ELITE81','ADMIN81']);
csrf_check();

$piano = strtoupper(trim($_POST['piano'] ?? ''));
if (!isset(MEMBERSHIP_PLANS[$piano])) json_err('Piano non valido. Scegli: BASIC+, PRO+, ELITE+');

$is_prima = MembershipService::is_prima_attivazione($user['id'], $piano);
$result   = MembershipService::activate($user['id'], $piano, $is_prima);

if (!$result['ok']) json_err($result['error'] ?? 'Errore attivazione');

// Aggiorna sessione
$_SESSION['membership'] = $piano;
if ($piano !== 'BASIC+') {
    $_SESSION['ruolo'] = 'NETWORKER81';
}

json_ok([
    'piano'        => $piano,
    'pvplus_bonus' => $result['pvplus_bonus'],
    'prima'        => $is_prima,
    'msg'          => 'Membership ' . $piano . ' attivata. Hai ricevuto ' . $result['pvplus_bonus'] . ' PV+.',
]);
