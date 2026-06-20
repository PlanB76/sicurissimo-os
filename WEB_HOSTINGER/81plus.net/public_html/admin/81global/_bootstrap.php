<?php
/**
 * _bootstrap.php — 81PLUS Global OS
 *
 * Shared bootstrap loaded by every API endpoint under /admin/81global/api/.
 * Provides the PDO singleton, response helpers, auth guards, contact lookups,
 * segment calculation, event tracking and email queuing.
 *
 * Do NOT include this file more than once per request; use require_once.
 */

require_once __DIR__ . '/config/config.php';

// ---------------------------------------------------------------------------
// Error handling
// ---------------------------------------------------------------------------

if (defined('DEBUG_MODE') && DEBUG_MODE) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

header('Content-Type: application/json; charset=utf-8');

// ---------------------------------------------------------------------------
// 1. PDO singleton
// ---------------------------------------------------------------------------

function g81_pdo(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_NAME,
            DB_CHARSET
        );

        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }

    return $pdo;
}

// ---------------------------------------------------------------------------
// 2. JSON response helpers
// ---------------------------------------------------------------------------

function g81_response(array $data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit();
}

function g81_error(string $message, int $code = 400, array $extra = []): void
{
    g81_response(['success' => false, 'error' => $message] + $extra, $code);
}

// ---------------------------------------------------------------------------
// 3. Request guards
// ---------------------------------------------------------------------------

function g81_require_post(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        g81_error('Method not allowed', 405);
    }
}

function g81_require_admin(): void
{
    $provided = $_SERVER['HTTP_X_ADMIN_SECRET'] ?? $_GET['admin_secret'] ?? '';

    if (!hash_equals(ADMIN_SECRET, $provided)) {
        g81_error('Unauthorized', 401);
    }
}

// ---------------------------------------------------------------------------
// 4. Contact lookups
// ---------------------------------------------------------------------------

function g81_get_contact(int $id): ?array
{
    $stmt = g81_pdo()->prepare(
        'SELECT * FROM leadgen81_contacts WHERE id = ? LIMIT 1'
    );
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    return $row !== false ? $row : null;
}

function g81_get_contact_by_email(string $email): ?array
{
    $stmt = g81_pdo()->prepare(
        'SELECT * FROM leadgen81_contacts WHERE email = ? LIMIT 1'
    );
    $stmt->execute([$email]);
    $row = $stmt->fetch();

    return $row !== false ? $row : null;
}

// ---------------------------------------------------------------------------
// 5. Segment calculation
// ---------------------------------------------------------------------------

function g81_calculate_segment(array $contact): string
{
    $score = (int) ($contact['heat_score'] ?? 0);

    if ($score >= 91) {
        return 'CLIENTE';
    }

    if ($score >= 61) {
        return 'HOT';
    }

    if ($score >= 26) {
        return 'WARM';
    }

    return 'COLD';
}

// ---------------------------------------------------------------------------
// 6. Event tracking + heat score update
// ---------------------------------------------------------------------------

function g81_track_event(int $contact_id, string $event_type, array $event_data = []): bool
{
    $pdo = g81_pdo();

    // Insert event row
    $stmt = $pdo->prepare(
        'INSERT INTO leadgen81_events
             (contact_id, event_type, event_data, ip_address, user_agent)
         VALUES (?, ?, ?, ?, ?)'
    );

    $inserted = $stmt->execute([
        $contact_id,
        $event_type,
        json_encode($event_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        $_SERVER['HTTP_USER_AGENT'] ?? '',
    ]);

    if (!$inserted) {
        return false;
    }

    // Heat-score increment by event type
    $increments = [
        'OPEN'            => 2,
        'CLICK'           => 5,
        'DOWNLOAD'        => 8,
        'REGISTER'        => 10,
        'WEBINAR_JOIN'    => 15,
        'CONSULT_REQUEST' => 20,
        'PURCHASE'        => 30,
    ];

    if (isset($increments[$event_type])) {
        $delta = $increments[$event_type];

        $upd = $pdo->prepare(
            'UPDATE leadgen81_contacts
             SET heat_score = LEAST(heat_score + ?, 100)
             WHERE id = ?'
        );
        $upd->execute([$delta, $contact_id]);
    }

    return true;
}

// ---------------------------------------------------------------------------
// 7. Tracking ID generator
// ---------------------------------------------------------------------------

function g81_generate_tracking_id(): string
{
    return bin2hex(random_bytes(32)); // 64-char hex string
}

// ---------------------------------------------------------------------------
// 8. Email queue
// ---------------------------------------------------------------------------

function g81_queue_email(
    int    $contact_id,
    string $flow_code,
    int    $step_order,
    array  $step,
    string $tracking_id = null
)/* returns int queue_id or false */ {
    if ($tracking_id === null) {
        $tracking_id = g81_generate_tracking_id();
    }

    $pdo = g81_pdo();

    // Fetch contact data for personalisation
    $cStmt = $pdo->prepare(
        'SELECT email, nome, cognome FROM leadgen81_contacts WHERE id = ? LIMIT 1'
    );
    $cStmt->execute([$contact_id]);
    $contact = $cStmt->fetch();

    if ($contact === false) {
        return false;
    }

    $placeholders = [
        '[NOME]'    => $contact['nome']    ?? '',
        '[COGNOME]' => $contact['cognome'] ?? '',
        '[EMAIL]'   => $contact['email']   ?? '',
    ];

    $subject   = strtr($step['subject']   ?? '', $placeholders);
    $body_text = strtr($step['body_text'] ?? '', $placeholders);

    // Calculate scheduled_at = NOW + delay_hours
    $delay_hours  = (int) ($step['delay_hours'] ?? 0);
    $scheduled_at = (new DateTime())
        ->add(new DateInterval("PT{$delay_hours}H"))
        ->format('Y-m-d H:i:s');

    $stmt = $pdo->prepare(
        'INSERT INTO leadgen81_email_queue
             (contact_id, flow_code, step_order, subject, body_text,
              tracking_id, scheduled_at, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );

    $ok = $stmt->execute([
        $contact_id,
        $flow_code,
        $step_order,
        $subject,
        $body_text,
        $tracking_id,
        $scheduled_at,
        'PENDING',
    ]);

    if (!$ok) {
        return false;
    }

    return (int) $pdo->lastInsertId();
}
