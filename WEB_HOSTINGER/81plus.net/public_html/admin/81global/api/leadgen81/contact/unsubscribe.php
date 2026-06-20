<?php
// 81PLUS Global OS — LeadGen81 Contact Unsubscribe
// FILE: api/leadgen81/contact/unsubscribe.php
// METHOD: POST only
// Unsubscribes a contact, revokes consents and cancels pending emails.
// Privacy-safe: always returns success even if email not found.

require_once __DIR__ . '/../../../_bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    g81_error('Metodo non consentito. Usa POST.', 405);
}

// ─── 1. PARSE INPUT ───────────────────────────────────────────────────────────
$body = file_get_contents('php://input');
$data = json_decode($body, true);
if (!is_array($data) || empty($data)) {
    $data = $_POST;
}
if (!is_array($data)) {
    $data = [];
}

// ─── 2. VALIDATE EMAIL ───────────────────────────────────────────────────────
$raw_email = isset($data['email']) ? trim($data['email']) : '';
if ($raw_email === '') {
    g81_error('Il campo email e obbligatorio.', 400);
}
$email = filter_var($raw_email, FILTER_VALIDATE_EMAIL);
if ($email === false) {
    g81_error('Formato email non valido.', 400);
}

// ─── 3. SANITIZE OPTIONAL FIELDS ─────────────────────────────────────────────
$reason           = isset($data['reason'])           ? substr(strip_tags(trim($data['reason'])), 0, 500) : null;
$unsubscribe_type = isset($data['unsubscribe_type']) ? strtoupper(strip_tags(trim($data['unsubscribe_type']))) : 'ALL';
if ($unsubscribe_type === '') {
    $unsubscribe_type = 'ALL';
}

// ─── 4. FIND CONTACT (privacy-safe: no error on missing) ─────────────────────
try {
    $contact = g81_get_contact_by_email($email);
} catch (Exception $e) {
    // Privacy-safe: do not reveal DB errors to caller
    g81_response([
        'success'         => true,
        'message'         => 'Disiscrizione registrata correttamente.',
        'emails_cancelled' => 0,
    ]);
}

// If contact not found, return success without revealing it
if (!$contact) {
    g81_response([
        'success'          => true,
        'message'          => 'Disiscrizione registrata correttamente.',
        'emails_cancelled' => 0,
    ]);
}

$contact_id     = (int)$contact['id'];
$emails_cancelled = 0;

// ─── 5A. MARK CONTACT AS UNSUBSCRIBED ────────────────────────────────────────
try {
    g81_pdo()->prepare("
        UPDATE leadgen81_contacts
        SET is_subscribed = 0,
            segment = 'INATTIVO',
            updated_at = NOW()
        WHERE id = ?
    ")->execute([$contact_id]);
} catch (Exception $e) {
    g81_error('Errore DB durante la disiscrizione del contatto: ' . $e->getMessage(), 500);
}

// ─── 5B. LOG UNSUBSCRIBE RECORD ──────────────────────────────────────────────
try {
    g81_pdo()->prepare('
        INSERT INTO leadgen81_unsubscribes
            (contact_id, email, unsubscribe_type, reason, created_at)
        VALUES
            (?, ?, ?, ?, NOW())
    ')->execute([$contact_id, $email, $unsubscribe_type, $reason]);
} catch (Exception $e) {
    g81_error('Errore DB durante il salvataggio della disiscrizione: ' . $e->getMessage(), 500);
}

// ─── 5C. REVOKE ALL CONSENTS (only if type = ALL) ────────────────────────────
if ($unsubscribe_type === 'ALL') {
    try {
        g81_pdo()->prepare("
            UPDATE leadgen81_consents
            SET granted = 0,
                revoked_at = NOW()
            WHERE contact_id = ?
        ")->execute([$contact_id]);
    } catch (Exception $e) {
        g81_error('Errore DB durante la revoca dei consensi: ' . $e->getMessage(), 500);
    }
}

// ─── 5D. CANCEL PENDING EMAILS ───────────────────────────────────────────────
try {
    $stmt = g81_pdo()->prepare("
        UPDATE leadgen81_email_queue
        SET status = 'SKIPPED',
            updated_at = NOW()
        WHERE contact_id = ?
          AND status = 'PENDING'
    ");
    $stmt->execute([$contact_id]);
    $emails_cancelled = (int)$stmt->rowCount();
} catch (Exception $e) {
    g81_error('Errore DB durante la cancellazione delle email in coda: ' . $e->getMessage(), 500);
}

// ─── 5E. LOG UNSUBSCRIBE EVENT ───────────────────────────────────────────────
try {
    g81_track_event($contact_id, 'CLICK', [
        'action' => 'UNSUBSCRIBE',
        'type'   => $unsubscribe_type,
    ]);
} catch (Exception $e) {
    // Non bloccante
}

// ─── 6. RESPOND ──────────────────────────────────────────────────────────────
g81_response([
    'success'          => true,
    'message'          => 'Disiscrizione registrata correttamente.',
    'emails_cancelled' => $emails_cancelled,
]);
