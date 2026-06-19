<?php
// SFERA81+ — Badge utente
// GET: user_id — restituisce badge guadagnati e disponibili
require_once __DIR__ . '/../../../_bootstrap.php';

$user_id = (int)($_GET['user_id'] ?? 0);
if (!$user_id) sfera_error('user_id obbligatorio');

$user = sfera_get_user($user_id);
if (!$user) sfera_error('Utente non trovato', 404);

$pdo = sfera_pdo();

// Badge guadagnati
$st = $pdo->prepare(
    'SELECT ub.badge_code, ub.pvplus_awarded, ub.earned_at,
            b.badge_name, b.badge_desc, b.badge_icon, b.game_code
     FROM sfera_user_badges ub
     JOIN sfera_badges b ON b.id=ub.badge_id
     WHERE ub.user_id=?
     ORDER BY ub.earned_at DESC'
);
$st->execute([$user_id]);
$earned = $st->fetchAll();

// Tutti i badge attivi (per mostrare quelli non ancora guadagnati)
$st2 = $pdo->prepare(
    'SELECT b.badge_code, b.badge_name, b.badge_desc, b.badge_icon,
            b.game_code, b.pvplus_bonus,
            (SELECT ub2.earned_at FROM sfera_user_badges ub2 WHERE ub2.user_id=? AND ub2.badge_id=b.id) as earned_at
     FROM sfera_badges b WHERE b.active=1
     ORDER BY b.id ASC'
);
$st2->execute([$user_id]);
$all = $st2->fetchAll();

$result = array_map(fn($b) => [
    'code'        => $b['badge_code'],
    'name'        => $b['badge_name'],
    'desc'        => $b['badge_desc'],
    'icon'        => $b['badge_icon'],
    'game'        => $b['game_code'],
    'pvplus_bonus'=> (int)$b['pvplus_bonus'],
    'earned'      => !empty($b['earned_at']),
    'earned_at'   => $b['earned_at'] ?? null,
], $all);

sfera_response([
    'ok'           => true,
    'total'        => count($all),
    'earned_count' => count($earned),
    'badges'       => $result,
]);
