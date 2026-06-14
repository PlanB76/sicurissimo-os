<?php
/**
 * auth.php · SIC-ID Authentication Endpoint
 * 81+ Ecosystem · HUB1 · v1.0
 * POST { action: 'register'|'login'|'refresh'|'logout' }
 * Response: { ok, data, err, ts }
 */

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');

/* ── CORS (whitelist only) ── */
$allowed_origins = [
    'https://81plus.net',
    'https://www.81plus.net',
    'https://sicurissimo.online',
    'https://www.sicurissimo.online',
];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed_origins, true)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Vary: Origin');
}
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    out(false, null, 'Method not allowed', 405);
}

/* ── CONFIG ── */
const JWT_SECRET   = '%%JWT_SECRET%%';   // rotate before go-live
const JWT_ALGO     = 'HS256';
const JWT_TTL      = 120;                // 120 seconds — SIC-ID spec
const REFRESH_TTL  = 86400 * 30;        // 30 days
const PV_WELCOME   = 100;               // PV gifted at registration
const DB_HOST      = 'localhost';
const DB_NAME      = 'u173050672_81plusglobal';
const DB_USER      = '%%DB_USER%%';     // rotate before go-live
const DB_PASS      = '%%DB_PASS%%';     // rotate before go-live
const DB_CHARSET   = 'utf8mb4';
const RATE_LIMIT   = 10;               // max attempts per IP per 15 min
const RATE_WINDOW  = 900;

/* ── REQUEST BODY ── */
$raw = file_get_contents('php://input');
$body = json_decode($raw ?: '', true);
if (!is_array($body)) {
    out(false, null, 'Invalid JSON body', 400);
}

$action = trim($body['action'] ?? '');

/* ── ROUTER ── */
switch ($action) {
    case 'register': handle_register($body); break;
    case 'login':    handle_login($body);    break;
    case 'refresh':  handle_refresh();       break;
    case 'logout':   handle_logout();        break;
    default:         out(false, null, 'Unknown action', 400);
}

/* ═══════════════════════════════════════════
   HANDLERS
═══════════════════════════════════════════ */

function handle_register(array $b): void {
    $nome    = sanitize($b['nome']    ?? '');
    $cognome = sanitize($b['cognome'] ?? '');
    $email   = strtolower(trim($b['email']    ?? ''));
    $pass    = $b['password'] ?? '';
    $settore = sanitize($b['settore'] ?? 'altro');
    $piva    = sanitize($b['piva']    ?? '');

    /* Validation */
    if (!$nome || !$cognome)            out(false, null, 'Nome e cognome obbligatori', 422);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) out(false, null, 'Email non valida', 422);
    if (strlen($pass) < 8)              out(false, null, 'Password minimo 8 caratteri', 422);
    $allowed_settori = ['manifatturiero','food','cantieri','uffici','commercio','altro'];
    if (!in_array($settore, $allowed_settori, true)) $settore = 'altro';

    rate_limit($email);

    $db = get_db();

    /* Check duplicate */
    $st = $db->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $st->execute([$email]);
    if ($st->fetch()) out(false, null, 'Email già registrata', 409);

    /* Insert */
    $hash = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);
    $sic_id = generate_sic_id();
    $ins = $db->prepare(
        'INSERT INTO users (sic_id, email, nome, cognome, settore, piva, password_hash, pv, created_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())'
    );
    $ins->execute([$sic_id, $email, $nome, $cognome, $settore, $piva, $hash, PV_WELCOME]);
    $user_id = (int) $db->lastInsertId();

    /* Log */
    log_event($db, $user_id, 'register', null);

    $token   = make_jwt($user_id, $sic_id, $nome, $email);
    $refresh = make_refresh($db, $user_id);

    out(true, [
        'token'       => $token,
        'refresh'     => $refresh,
        'sic_id'      => $sic_id,
        'nome'        => $nome,
        'pv'          => PV_WELCOME,
        'ttl'         => JWT_TTL,
    ]);
}

function handle_login(array $b): void {
    $email = strtolower(trim($b['email']    ?? ''));
    $pass  = $b['password'] ?? '';

    if (!$email || !$pass) out(false, null, 'Credenziali mancanti', 422);

    rate_limit($email);

    $db = get_db();
    $st = $db->prepare('SELECT id, sic_id, nome, cognome, password_hash, pv, is_active FROM users WHERE email = ? LIMIT 1');
    $st->execute([$email]);
    $row = $st->fetch(PDO::FETCH_ASSOC);

    /* Timing-safe rejection — always verify even if row not found */
    $dummy = '$2y$12$invalidhashpaddingtomatchbcryptlength000000000000000000000';
    $hash  = $row ? $row['password_hash'] : $dummy;
    $valid = password_verify($pass, $hash);

    if (!$row || !$valid) out(false, null, 'Credenziali non valide', 401);
    if (!(int)$row['is_active']) out(false, null, 'Account sospeso. Contatta info@81plus.net', 403);

    $user_id = (int) $row['id'];

    /* Rehash if needed */
    if (password_needs_rehash($hash, PASSWORD_BCRYPT, ['cost' => 12])) {
        $new_hash = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);
        $db->prepare('UPDATE users SET password_hash = ? WHERE id = ?')->execute([$new_hash, $user_id]);
    }

    log_event($db, $user_id, 'login', null);

    $token   = make_jwt($user_id, $row['sic_id'], $row['nome'], $email);
    $refresh = make_refresh($db, $user_id);

    out(true, [
        'token'   => $token,
        'refresh' => $refresh,
        'sic_id'  => $row['sic_id'],
        'nome'    => $row['nome'],
        'pv'      => (int) $row['pv'],
        'ttl'     => JWT_TTL,
    ]);
}

function handle_refresh(): void {
    $auth   = get_bearer();
    $token  = $auth['raw'] ?? '';
    if (!$token) out(false, null, 'Token mancante', 401);

    $db = get_db();
    $st = $db->prepare(
        'SELECT rt.user_id, rt.expires_at, u.sic_id, u.nome, u.email, u.pv, u.is_active
         FROM refresh_tokens rt JOIN users u ON u.id = rt.user_id
         WHERE rt.token_hash = ? LIMIT 1'
    );
    $st->execute([hash('sha256', $token)]);
    $row = $st->fetch(PDO::FETCH_ASSOC);

    if (!$row)                              out(false, null, 'Token non valido', 401);
    if (strtotime($row['expires_at']) < time()) out(false, null, 'Token scaduto', 401);
    if (!(int)$row['is_active'])            out(false, null, 'Account sospeso', 403);

    $user_id = (int) $row['user_id'];

    /* Rotate refresh token */
    $db->prepare('DELETE FROM refresh_tokens WHERE token_hash = ?')->execute([hash('sha256', $token)]);
    $new_refresh = make_refresh($db, $user_id);
    $new_token   = make_jwt($user_id, $row['sic_id'], $row['nome'], $row['email']);

    out(true, [
        'token'   => $new_token,
        'refresh' => $new_refresh,
        'sic_id'  => $row['sic_id'],
        'nome'    => $row['nome'],
        'pv'      => (int) $row['pv'],
        'ttl'     => JWT_TTL,
    ]);
}

function handle_logout(): void {
    $auth  = get_bearer();
    $token = $auth['raw'] ?? '';
    if ($token) {
        $db = get_db();
        $db->prepare('DELETE FROM refresh_tokens WHERE token_hash = ?')->execute([hash('sha256', $token)]);
    }
    out(true, ['message' => 'Logout effettuato']);
}

/* ═══════════════════════════════════════════
   JWT (HMAC-SHA256, no library needed)
═══════════════════════════════════════════ */

function make_jwt(int $uid, string $sic_id, string $nome, string $email): string {
    $header  = b64url(json_encode(['alg' => JWT_ALGO, 'typ' => 'JWT']));
    $payload = b64url(json_encode([
        'sub'    => $uid,
        'sic_id' => $sic_id,
        'nome'   => $nome,
        'email'  => $email,
        'iat'    => time(),
        'exp'    => time() + JWT_TTL,
    ]));
    $sig = b64url(hash_hmac('sha256', $header . '.' . $payload, JWT_SECRET, true));
    return $header . '.' . $payload . '.' . $sig;
}

function verify_jwt(string $token): ?array {
    $parts = explode('.', $token);
    if (count($parts) !== 3) return null;
    [$h, $p, $s] = $parts;
    $expected = b64url(hash_hmac('sha256', $h . '.' . $p, JWT_SECRET, true));
    if (!hash_equals($expected, $s)) return null;
    $payload = json_decode(base64_decode(strtr($p, '-_', '+/')), true);
    if (!$payload || ($payload['exp'] ?? 0) < time()) return null;
    return $payload;
}

function b64url(string $data): string {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

/* ═══════════════════════════════════════════
   REFRESH TOKEN
═══════════════════════════════════════════ */

function make_refresh(PDO $db, int $user_id): string {
    $token = bin2hex(random_bytes(32));
    $hash  = hash('sha256', $token);
    $exp   = date('Y-m-d H:i:s', time() + REFRESH_TTL);
    /* Purge old tokens for this user (keep max 5 devices) */
    $db->prepare(
        'DELETE FROM refresh_tokens WHERE user_id = ? AND id NOT IN
         (SELECT id FROM (SELECT id FROM refresh_tokens WHERE user_id = ? ORDER BY created_at DESC LIMIT 4) t)'
    )->execute([$user_id, $user_id]);
    $db->prepare(
        'INSERT INTO refresh_tokens (user_id, token_hash, expires_at, created_at) VALUES (?, ?, ?, NOW())'
    )->execute([$user_id, $hash, $exp]);
    return $token;
}

/* ═══════════════════════════════════════════
   RATE LIMITING (stored in DB)
═══════════════════════════════════════════ */

function rate_limit(string $identifier): void {
    $ip  = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $key = hash('sha256', $ip . '|' . $identifier);
    $db  = get_db();
    $st  = $db->prepare(
        'SELECT COUNT(*) FROM rate_limit WHERE key_hash = ? AND created_at > DATE_SUB(NOW(), INTERVAL ? SECOND)'
    );
    $st->execute([$key, RATE_WINDOW]);
    $count = (int) $st->fetchColumn();
    if ($count >= RATE_LIMIT) out(false, null, 'Troppi tentativi. Riprova tra 15 minuti.', 429);
    $db->prepare('INSERT INTO rate_limit (key_hash, created_at) VALUES (?, NOW())')->execute([$key]);
}

/* ═══════════════════════════════════════════
   HELPERS
═══════════════════════════════════════════ */

function get_db(): PDO {
    static $db = null;
    if ($db) return $db;
    try {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $db = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        out(false, null, 'Database non disponibile', 503);
    }
    return $db;
}

function get_bearer(): array {
    $h = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (strncasecmp($h, 'bearer ', 7) === 0) {
        return ['raw' => substr($h, 7)];
    }
    return [];
}

function generate_sic_id(): string {
    return 'SIC-' . strtoupper(bin2hex(random_bytes(4))) . '-' . strtoupper(bin2hex(random_bytes(3)));
}

function sanitize(string $val): string {
    return trim(htmlspecialchars($val, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
}

function log_event(PDO $db, int $user_id, string $event, ?string $detail): void {
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    try {
        $db->prepare(
            'INSERT INTO activity_log (user_id, event, detail, ip, created_at) VALUES (?, ?, ?, ?, NOW())'
        )->execute([$user_id, $event, $detail, $ip]);
    } catch (Throwable $e) {
        /* Non-critical: ignore log failures */
    }
}

function out(bool $ok, mixed $data, string $err = '', int $status = 200): never {
    http_response_code($status);
    echo json_encode([
        'ok'   => $ok,
        'data' => $data,
        'err'  => $err,
        'ts'   => time(),
    ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    exit;
}
