<?php
// api/referral-stats.php — Statistiche referral utente
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/auth_guard.php';
require_once dirname(__DIR__) . '/core81/referral_service.php';

$user  = auth_guard();
$stats = ReferralService::stats($user['id']);
$link  = ReferralService::link($user['sic_id']);

json_ok([
    'link'  => $link,
    'stats' => $stats,
]);
