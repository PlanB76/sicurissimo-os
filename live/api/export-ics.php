<?php
// api/export-ics.php — export scadenziario in formato ICS
// GET: ?user_id=&periodo=

declare(strict_types=1);
require_once __DIR__ . '/../core81/auth_guard.php';
require_once __DIR__ . '/../core81/scadenziario.php';

$user = auth_guard();
$periodo = $_GET['periodo'] ?? '12m';

$ics = Scadenziario81::exportICS($user['id'], $periodo);

header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="scadenziario81.ics"');
echo $ics;
