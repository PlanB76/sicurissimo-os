<?php
/**
 * dashboard.php · Dashboard Data Endpoint
 * 81+ Ecosystem · HUB1 · v1.0
 * GET /api/dashboard.php
 * Authorization: Bearer <sic_token>
 * Response: { ok, data: { compliance_score, docs_month, agents_active,
 *             next_deadline_days, dvr_ok, haccp_ok, form_ok, med_ok, activity[] }, err, ts }
 */

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

/* ── CORS (whitelist only) ── */
$allowed_origins = [
    'https://81plus.net', 'https://www.81plus.net',
    'https://sicurissimo.online', 'https://www.sicurissimo.online',
];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed_origins, true)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Vary: Origin');
}
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'GET')     out(false, null, 'Method not allowed', 405);

/* ── AUTH ── */
require_once __DIR__ . '/auth.php';   // shares JWT_SECRET, verify_jwt, get_db

$bearer = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$raw    = strncasecmp($bearer, 'bearer ', 7) === 0 ? substr($bearer, 7) : '';
if (!$raw) out(false, null, 'Token mancante', 401);

$payload = verify_jwt($raw);
if (!$payload) out(false, null, 'Token non valido o scaduto', 401);

$user_id = (int) ($payload['sub'] ?? 0);
if (!$user_id) out(false, null, 'Token corrotto', 401);

/* ── DATA ── */
$db = get_db();

/* Compliance items */
$st = $db->prepare(
    'SELECT dvr_ok, haccp_ok, form_ok, med_ok, compliance_score
     FROM user_compliance WHERE user_id = ? LIMIT 1'
);
$st->execute([$user_id]);
$comp = $st->fetch(PDO::FETCH_ASSOC) ?: [];

$dvr_ok   = (bool) ($comp['dvr_ok']   ?? false);
$haccp_ok = (bool) ($comp['haccp_ok'] ?? false);
$form_ok  = (bool) ($comp['form_ok']  ?? false);
$med_ok   = (bool) ($comp['med_ok']   ?? false);

/* Auto-calculate score if not stored */
$score = isset($comp['compliance_score']) ? (int) $comp['compliance_score'] : null;
if ($score === null) {
    $score = (int) round(((int)$dvr_ok + (int)$haccp_ok + (int)$form_ok + (int)$med_ok) / 4 * 100);
}

/* Docs this month */
$st = $db->prepare(
    'SELECT COUNT(*) FROM documents WHERE user_id = ? AND created_at >= DATE_FORMAT(NOW(), "%Y-%m-01")'
);
$st->execute([$user_id]);
$docs_month = (int) $st->fetchColumn();

/* Active skills (system-wide count — same for all users) */
$agents_active = 5; // Nicolas(34), DailyMonitor(35), LeadScoring(38), SocialAutomator(30), Command1000

/* Next deadline */
$st = $db->prepare(
    'SELECT DATEDIFF(due_date, CURDATE()) as days_left, title
     FROM deadlines WHERE user_id = ? AND due_date >= CURDATE()
     ORDER BY due_date ASC LIMIT 1'
);
$st->execute([$user_id]);
$dl = $st->fetch(PDO::FETCH_ASSOC);
$next_deadline_days  = $dl ? max(0, (int) $dl['days_left']) : null;
$next_deadline_title = $dl['title'] ?? null;

/* Recent activity */
$st = $db->prepare(
    'SELECT event, detail, created_at,
            UNIX_TIMESTAMP(created_at) as ts
     FROM activity_log WHERE user_id = ?
     ORDER BY created_at DESC LIMIT 10'
);
$st->execute([$user_id]);
$raw_activity = $st->fetchAll(PDO::FETCH_ASSOC);

$activity = array_map(function (array $row): array {
    $map = [
        'register'          => ['label' => 'Account creato',          'type' => 'login'],
        'login'             => ['label' => 'Accesso effettuato',       'type' => 'login'],
        'audit_start'       => ['label' => 'Audit avviato',            'type' => 'audit'],
        'audit_complete'    => ['label' => 'Audit completato',         'type' => 'audit'],
        'document_create'   => ['label' => 'Documento generato',       'type' => 'document'],
        'deadline_add'      => ['label' => 'Scadenza aggiunta',        'type' => 'deadline'],
        'agent_interaction' => ['label' => 'Interazione con agente',   'type' => 'agent'],
    ];
    $info = $map[$row['event']] ?? ['label' => ucfirst(str_replace('_',' ',$row['event'])), 'type' => 'generic'];
    return [
        'label'  => $info['label'],
        'type'   => $info['type'],
        'detail' => $row['detail'] ?? '',
        'ts'     => (int) $row['ts'],
    ];
}, $raw_activity);

out(true, [
    'compliance_score'     => $score,
    'dvr_ok'               => $dvr_ok,
    'haccp_ok'             => $haccp_ok,
    'form_ok'              => $form_ok,
    'med_ok'               => $med_ok,
    'docs_month'           => $docs_month,
    'agents_active'        => $agents_active,
    'next_deadline_days'   => $next_deadline_days,
    'next_deadline_title'  => $next_deadline_title,
    'activity'             => $activity,
]);
