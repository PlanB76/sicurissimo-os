<?php
// SFERA81+ — Crea promo (admin only)
// POST + header X-Admin-Secret
require_once __DIR__ . '/../../../_bootstrap.php';
sfera_require_admin();

$p    = sfera_require_post();
$code = trim($p['promo_code'] ?? 'BOOSTER_' . date('Ym'));
$name = trim($p['promo_name'] ?? 'BOOSTER81+ ' . date('m/Y'));
$reward = (int)($p['reward_amount'] ?? 50);
$start  = $p['start_at'] ?? date('Y-m-d H:i:s');
$end    = $p['end_at']   ?? date('Y-m-d H:i:s', strtotime('+24 hours'));

// Verifica slot mensile: max 1 promo per mese
$month_start = date('Y-m-01 00:00:00');
$month_end   = date('Y-m-t 23:59:59');
$st = sfera_pdo()->prepare(
    "SELECT COUNT(*) FROM sfera_promos WHERE monthly_slot=1 AND created_at BETWEEN ? AND ?"
);
$st->execute([$month_start, $month_end]);
if ((int)$st->fetchColumn() >= 1) {
    sfera_error('Slot mensile già usato. BOOSTER81+ può essere attivato 1 volta al mese.', 409);
}

try {
    sfera_pdo()->prepare(
        'INSERT INTO sfera_promos (promo_code,promo_name,status,start_at,end_at,reward_type,reward_amount,created_by)
         VALUES (?,?,?,?,?,?,?,?)'
    )->execute([$code, $name, 'inactive', $start, $end, 'BOOSTER81+', $reward, 1]);
} catch (Exception $e) {
    sfera_error('Errore creazione promo: ' . $e->getMessage());
}

sfera_response(['ok'=>true,'msg'=>'Promo creata. Usa activate.php per attivarla.','code'=>$code]);
