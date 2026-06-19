<?php
// SFERA81+ — Award PV+ (admin only)
// POST: user_id, amount, reason, source
// Header: X-Admin-Secret
require_once __DIR__ . '/../../../_bootstrap.php';
sfera_require_admin();

$p       = sfera_require_post();
$user_id = (int)($p['user_id'] ?? 0);
$amount  = (int)($p['amount'] ?? 0);
$reason  = trim($p['reason'] ?? 'award_manuale');
$source  = trim($p['source'] ?? 'ADMIN_AWARD');

if (!$user_id || $amount <= 0) sfera_error('user_id e amount > 0 obbligatori');
if ($amount > 10000) sfera_error('Amount troppo alto (max 10000)');

$user = sfera_get_user($user_id);
if (!$user) sfera_error('Utente non trovato', 404);

sfera_award_pvplus($user_id, $amount, $reason, $source);

$user = sfera_get_user($user_id);
sfera_response([
    'ok'            => true,
    'awarded'       => $amount,
    'reason'        => $reason,
    'pvplus_balance'=> (int)$user['pvplus_balance'],
    'msg'           => '+' . $amount . ' PV+ accreditati manualmente.',
]);
