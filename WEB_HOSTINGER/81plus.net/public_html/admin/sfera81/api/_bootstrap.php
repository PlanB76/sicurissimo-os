<?php
// SFERA81+ V5 — Bootstrap API
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Admin-Secret');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

$cfg = __DIR__ . '/../../config/config.php';
if (!file_exists($cfg)) { http_response_code(500); echo json_encode(['error'=>'config.php mancante']); exit; }
require_once $cfg;

function sfera_pdo(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;
    $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    return $pdo;
}

function sfera_response(array $data, int $code = 200): void {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function sfera_error(string $msg, int $code = 400): void {
    sfera_response(['ok' => false, 'error' => $msg], $code);
}

function sfera_get_user(int $user_id): ?array {
    $st = sfera_pdo()->prepare('SELECT * FROM sfera_user_profile WHERE user_id=?');
    $st->execute([$user_id]);
    return $st->fetch() ?: null;
}

function sfera_get_status_cap(string $status): int {
    $caps = [
        'MEMBER81+'      => CAP_MEMBER,
        'NETWORKERS81+'  => CAP_NETWORKERS,
        'ELITE81+'       => CAP_ELITE,
        'FRANCHISER81+'  => CAP_FRANCHISER,
        'CLUB81+'        => CAP_CLUB,
    ];
    return $caps[$status] ?? CAP_MEMBER;
}

function sfera_get_lifewheel_cap(string $status): int {
    $caps = ['MEMBER81+'=>6,'NETWORKERS81+'=>7,'ELITE81+'=>8,'FRANCHISER81+'=>9,'CLUB81+'=>10];
    return $caps[$status] ?? 6;
}

function sfera_award_pvplus(int $user_id, int $amount, string $reason, string $source, ?int $promo_id = null): void {
    $pdo = sfera_pdo();
    $pdo->prepare('UPDATE sfera_user_profile SET pvplus_balance=pvplus_balance+? WHERE user_id=?')
        ->execute([$amount, $user_id]);
    $u = sfera_get_user($user_id);
    $pdo->prepare('INSERT INTO sfera_reward_log (user_id,sic_id,reward_type,reward_amount,reason,source_module,promo_id) VALUES (?,?,?,?,?,?,?)')
        ->execute([$user_id, $u['sic_id']??'', $source, $amount, $reason, $source, $promo_id]);
}

function sfera_check_daily_mission_cap(int $user_id, string $date, string $status): int {
    $cap = sfera_get_status_cap($status);
    $st = sfera_pdo()->prepare(
        "SELECT COALESCE(SUM(reward_amount),0) FROM sfera_reward_log
         WHERE user_id=? AND source_module NOT IN ('DAILY_ACCESS','BOOSTER81+')
         AND DATE(created_at)=?"
    );
    $st->execute([$user_id, $date]);
    $used = (int)$st->fetchColumn();
    return max(0, $cap - $used);
}

function sfera_require_post(): array {
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    if (!$data) $data = $_POST;
    return $data ?: [];
}

function sfera_require_admin(): void {
    $secret = $_SERVER['HTTP_X_ADMIN_SECRET'] ?? ($_POST['admin_secret'] ?? ($_GET['admin_secret'] ?? ''));
    if ($secret !== ADMIN_SECRET) sfera_error('Accesso non autorizzato', 403);
}
