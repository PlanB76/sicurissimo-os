<?php
// 81PLUS Global OS — LeadGen81 Email Queue Processor
// FILE: api/leadgen81/email-queue/process.php
// METHOD: GET (admin only)
// Returns prepared, personalised emails that are due for sending.
// NOTE: This endpoint does NOT send emails directly. The external mailer
// (SMTP, AWS SES, SendGrid, etc.) must call mark-sent.php for each email
// after it has been dispatched.

require_once __DIR__ . '/../../../_bootstrap.php';

g81_require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    g81_error('Method not allowed', 405);
}

// ─── 1. PARSE PARAMS ─────────────────────────────────────────────────────────

$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 50;
if ($limit < 1) {
    $limit = 1;
}
if ($limit > 200) {
    $limit = 200;
}

// ─── 2. FETCH PENDING EMAILS DUE NOW ─────────────────────────────────────────

try {
    $pdo = g81_pdo();

    $stmt = $pdo->prepare(
        'SELECT eq.*, c.nome, c.cognome, c.email AS contact_email, c.is_subscribed
         FROM leadgen81_email_queue eq
         INNER JOIN leadgen81_contacts c ON c.id = eq.contact_id
         WHERE eq.status = \'PENDING\'
         AND eq.scheduled_at <= NOW()
         AND c.is_subscribed = 1
         ORDER BY eq.scheduled_at ASC
         LIMIT ?'
    );
    $stmt->execute([$limit]);
    $rows = $stmt->fetchAll();
} catch (Exception $e) {
    g81_error('Errore DB durante il recupero della coda email: ' . $e->getMessage(), 500);
}

// ─── 3. PERSONALISE EACH EMAIL ───────────────────────────────────────────────

$emails = [];

foreach ($rows as $row) {
    $nome          = $row['nome']          ?? '';
    $cognome       = $row['cognome']       ?? '';
    $contact_email = $row['contact_email'] ?? '';
    $tracking_id   = $row['tracking_id']  ?? '';

    $placeholders = [
        '[NOME]'        => $nome,
        '[COGNOME]'     => $cognome,
        '[EMAIL]'       => $contact_email,
        '[TRACKING_ID]' => $tracking_id,
    ];

    $subject   = strtr($row['subject']   ?? '', $placeholders);
    $body_text = strtr($row['body_text'] ?? '', $placeholders);

    // Append tracking pixel reference as plain text
    if ($tracking_id !== '') {
        $tracking_url = rtrim(BASE_URL, '/') . '/api/leadgen81/email-queue/track-open.php?t=' . $tracking_id;
        $body_text   .= "\n\n— " . $tracking_url;
    }

    // Append Italian unsubscribe footer (voce attiva, no markdown)
    $unsubscribe_url = rtrim(BASE_URL, '/') . '/api/leadgen81/contact/unsubscribe.php?email=' . rawurlencode($contact_email);
    $body_text      .= "\n\nNon vuoi piu ricevere queste email? Disiscrivi qui: " . $unsubscribe_url;

    $emails[] = [
        'queue_id'    => (int) $row['id'],
        'to_email'    => $contact_email,
        'to_name'     => trim($nome . ' ' . $cognome),
        'subject'     => $subject,
        'body_text'   => $body_text,
        'from_name'   => 'Nicolas - Sicurissimo OS',
        'from_email'  => 'nicolas@81plus.net',
        'tracking_id' => $tracking_id,
    ];
}

// ─── 4. RESPOND ──────────────────────────────────────────────────────────────

g81_response([
    'success'      => true,
    'emails'       => $emails,
    'total'        => count($emails),
    'processed_at' => (new DateTime())->format(DateTime::ATOM),
]);
