<?php
require_once __DIR__ . '/../../../_bootstrap.php';
$user_id = (int)($_GET['user_id'] ?? 0);
$limit   = min((int)($_GET['limit'] ?? 20), 100);
if (!$user_id) sfera_error('user_id obbligatorio');
$st = sfera_pdo()->prepare('SELECT reward_type,reward_amount,reason,source_module,created_at FROM sfera_reward_log WHERE user_id=? ORDER BY created_at DESC LIMIT ?');
$st->execute([$user_id, $limit]);
$user = sfera_get_user($user_id);
sfera_response(['ok'=>true,'balance'=>$user ? (int)$user['pvplus_balance'] : 0,'log'=>$st->fetchAll()]);
