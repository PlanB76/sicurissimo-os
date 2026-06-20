<?php
// 81PLUS Global OS — LeadGen81 Email Queue Mark-Sent
// FILE: api/leadgen81/email-queue/mark-sent.php
// METHOD: POST (admin only)
// Called by the external mailer after each email is dispatched (or failed).
// Updates the queue row status to SENT or FAILED.

require_once __DIR__ . '/../../../_bootstrap.php';

g81_require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    g81_error('Method not allowed', 405);
}

// ─── 1. PARSE INPUT ──────────────────────────────────────────────────────────

$body = file_get_contents('php://input');
$data = json_decode($body, true);
if (!is_array($data) || empty($data)) {
    $data = $_POST;
}
if (!is_array($data)) {
    $data = [];
}

// ─── 2. VALIDATE REQUIRED FIELDS ─────────────────────────────────────────────

if (!isset($data['queue_id']) || $data['queue_id'] === '') {
    g81_error('Il campo queue_id e obbligatorio.', 400);
}
$queue_id = (int) $data['queue_id'];
if ($queue_id < 1) {
    g81_error('queue_id non valido.', 400);
}

if (!isset($data['status']) || trim($data['status']) === '') {
    g81_error('Il campo status e obbligatorio.', 400);
}
$status = strtoupper(trim($data['status']));

if (!in_array($status, ['SENT', 'FAILED'], true)) {
    g81_error('Il campo status deve essere SENT o FAILED.', 400);
}

$error_msg = isset($data['error_msg']) ? substr(trim((string) $data['error_msg']), 0, 1000) : null;

// ─── 3. VERIFY QUEUE RECORD EXISTS ───────────────────────────────────────────

try {
    $pdo  = g81_pdo();
    $chk  = $pdo->prepare('SELECT id FROM leadgen81_email_queue WHERE id = ? LIMIT 1');
    $chk->execute([$queue_id]);
    if ($chk->fetch() === false) {
        g81_error('queue_id non trovato.', 404);
    }
} catch (Exception $e) {
    g81_error('Errore DB durante la verifica del record: ' . $e->getMessage(), 500);
}

// ─── 4. UPDATE STATUS ────────────────────────────────────────────────────────

try {
    $pdo = g81_pdo();

    if ($status === 'SENT') {
        $stmt = $pdo->prepare(
            'UPDATE leadgen81_email_queue SET status = \'SENT\', sent_at = NOW() WHERE id = ?'
        );
        $stmt->execute([$queue_id]);
    } else {
        // status === 'FAILED'
        $stmt = $pdo->prepare(
            'UPDATE leadgen81_email_queue SET status = \'FAILED\', error_msg = ? WHERE id = ?'
        );
        $stmt->execute([$error_msg, $queue_id]);
    }
} catch (Exception $e) {
    g81_error('Errore DB durante l\'aggiornamento dello stato: ' . $e->getMessage(), 500);
}

// ─── 5. RESPOND ──────────────────────────────────────────────────────────────

g81_response([
    'success'  => true,
    'queue_id' => $queue_id,
    'status'   => $status,
]);
