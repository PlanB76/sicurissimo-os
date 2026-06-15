<?php
// api/dashboard-data.php — Dati dashboard per ruolo
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/auth_guard.php';
require_once dirname(__DIR__) . '/core81/wallet_service.php';
require_once dirname(__DIR__) . '/core81/dashboard_modules.php';
require_once dirname(__DIR__) . '/core81/referral_service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') json_err('Metodo non consentito', 405);
$user = auth_guard();

$wallet  = WalletService::get_all($user['id']);
$modules = DashboardModules::for_user($user['id'], $user['ruolo'], $user['genesys_status'], $_SESSION['membership'] ?? 'NONE');
$ref_stats = ReferralService::stats($user['id']);
$ref_link  = ReferralService::link($user['sic_id']);

json_ok([
    'wallet' => [
        'pv'    => number_format((float)$wallet['pv_balance'], 2, '.', ''),
        'pvplus'=> number_format((float)$wallet['pvplus_balance'], 2, '.', ''),
        'saf'   => number_format((float)$wallet['saf_balance'], 8, '.', ''),
        '81x'   => number_format((float)$wallet['x81_balance'], 8, '.', ''),
        'usdt'  => number_format((float)$wallet['usdt_balance'], 2, '.', ''),
    ],
    'modules'   => $modules,
    'referral'  => [
        'link'  => $ref_link,
        'stats' => $ref_stats,
    ],
    'user' => [
        'nome'           => $user['nome'],
        'sic_id'         => $user['sic_id'],
        'ruolo'          => $user['ruolo'],
        'genesys_status' => $user['genesys_status'],
        'membership'     => $_SESSION['membership'] ?? 'NONE',
    ],
]);
