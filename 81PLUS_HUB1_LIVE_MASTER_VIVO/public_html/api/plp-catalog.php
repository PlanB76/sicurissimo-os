<?php
// api/plp-catalog.php — Catalogo pack PLP81+
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/auth_guard.php';
$user = auth_guard(['NETWORKER81','ELITE81','ADMIN81']);
$db   = DB::get();
$stmt = $db->prepare('SELECT * FROM plp_packs_catalog WHERE is_active = 1 ORDER BY sort_order, prezzo_pv');
$stmt->execute();
$packs = $stmt->fetchAll();
json_ok(['packs' => $packs]);
