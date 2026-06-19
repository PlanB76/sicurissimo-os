<?php
// SFERA81+ — Coda email leadgen (admin only)
// GET: restituisce coda email da inviare basata su eventi e segmenti
// POST: segna email come inviata
require_once __DIR__ . '/../../_bootstrap.php';
sfera_require_admin();

$pdo = sfera_pdo();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $limit = min((int)($_GET['limit'] ?? 50), 200);
    $segment = trim($_GET['segment'] ?? '');

    // Utenti con eventi recenti non ancora convertiti
    $where = "WHERE le.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $params = [];
    if ($segment) {
        $where .= " AND le.segment = ?";
        $params[] = $segment;
    }

    $st = $pdo->prepare(
        "SELECT le.user_id, le.sic_id, le.event_type, le.segment,
                le.created_at as event_date,
                up.pvplus_balance, up.status, up.current_escalation_level,
                up.ateco_code, up.macro_sector, up.risk_level,
                up.daily_streak, up.last_access_date
         FROM leadgen_events le
         JOIN sfera_user_profile up ON up.user_id=le.user_id
         {$where}
         GROUP BY le.user_id
         ORDER BY le.created_at DESC
         LIMIT ?"
    );
    $params[] = $limit;
    $st->execute($params);
    $queue = $st->fetchAll();

    $result = array_map(fn($r) => [
        'user_id'    => (int)$r['user_id'],
        'sic_id'     => $r['sic_id'],
        'event'      => $r['event_type'],
        'segment'    => $r['segment'],
        'status'     => $r['status'],
        'ateco'      => $r['ateco_code'],
        'sector'     => $r['macro_sector'],
        'risk'       => $r['risk_level'],
        'pvplus'     => (int)$r['pvplus_balance'],
        'level'      => (int)$r['current_escalation_level'],
        'streak'     => (int)$r['daily_streak'],
        'last_access'=> $r['last_access_date'],
        'event_date' => $r['event_date'],
    ], $queue);

    sfera_response(['ok'=>true,'count'=>count($result),'queue'=>$result]);
}

// POST: registra invio email
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p       = sfera_require_post();
    $user_id = (int)($p['user_id'] ?? 0);
    $flow    = trim($p['flow_code'] ?? '');
    if (!$user_id || !$flow) sfera_error('user_id e flow_code obbligatori');

    $user = sfera_get_user($user_id);
    $pdo->prepare(
        'INSERT INTO leadgen_events (user_id,sic_id,event_type,source,segment,metadata_json)
         VALUES (?,?,?,?,?,?)'
    )->execute([
        $user_id,
        $user['sic_id'] ?? '',
        'email_sent',
        'EMAIL_QUEUE81',
        $p['segment'] ?? 'non_profilato',
        json_encode(['flow'=>$flow,'sent_at'=>date('Y-m-d H:i:s')])
    ]);
    sfera_response(['ok'=>true,'msg'=>'Email registrata come inviata.','flow'=>$flow]);
}

sfera_error('Metodo non supportato', 405);
