<?php
// 81PLUS Global OS — LeadGen81 Contact Profile
// FILE: api/leadgen81/contact/profile.php
// METHOD: GET only
// Returns full contact profile: data, consents, recent events, pending email queue.

require_once __DIR__ . '/../../../_bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    g81_error('Metodo non consentito. Usa GET.', 405);
}

// ─── 1. PARSE INPUT ───────────────────────────────────────────────────────────
$contact_id_raw = isset($_GET['contact_id']) ? trim($_GET['contact_id']) : '';
$email_raw      = isset($_GET['email'])      ? trim($_GET['email'])      : '';

if ($contact_id_raw === '' && $email_raw === '') {
    g81_error('Fornisci almeno uno tra contact_id e email.', 400);
}

// ─── 2. LOAD CONTACT ─────────────────────────────────────────────────────────
$contact = null;

if ($contact_id_raw !== '') {
    $contact_id_int = filter_var($contact_id_raw, FILTER_VALIDATE_INT);
    if ($contact_id_int === false || $contact_id_int <= 0) {
        g81_error('contact_id non valido. Deve essere un intero positivo.', 400);
    }
    try {
        $contact = g81_get_contact((int)$contact_id_int);
    } catch (Exception $e) {
        g81_error('Errore DB durante il recupero del contatto: ' . $e->getMessage(), 500);
    }
} else {
    $email = filter_var($email_raw, FILTER_VALIDATE_EMAIL);
    if ($email === false) {
        g81_error('Formato email non valido.', 400);
    }
    try {
        $contact = g81_get_contact_by_email($email);
    } catch (Exception $e) {
        g81_error('Errore DB durante il recupero del contatto: ' . $e->getMessage(), 500);
    }
}

if (!$contact) {
    g81_error('Contatto non trovato.', 404);
}

$contact_id = (int)$contact['id'];

// ─── 3. GET CONSENTS ─────────────────────────────────────────────────────────
$consents = [];
try {
    $stmt = g81_pdo()->prepare('SELECT * FROM leadgen81_consents WHERE contact_id = ?');
    $stmt->execute([$contact_id]);
    $consents = $stmt->fetchAll();
} catch (Exception $e) {
    g81_error('Errore DB durante il recupero dei consensi: ' . $e->getMessage(), 500);
}

// ─── 4. GET RECENT EVENTS (last 20) ──────────────────────────────────────────
$recent_events = [];
try {
    $stmt = g81_pdo()->prepare('
        SELECT * FROM leadgen81_events
        WHERE contact_id = ?
        ORDER BY created_at DESC
        LIMIT 20
    ');
    $stmt->execute([$contact_id]);
    $recent_events = $stmt->fetchAll();
} catch (Exception $e) {
    g81_error('Errore DB durante il recupero degli eventi: ' . $e->getMessage(), 500);
}

// ─── 5. GET PENDING EMAIL QUEUE ──────────────────────────────────────────────
$pending_queue = [];
try {
    $stmt = g81_pdo()->prepare("
        SELECT * FROM leadgen81_email_queue
        WHERE contact_id = ?
          AND status = 'PENDING'
        ORDER BY scheduled_at ASC
    ");
    $stmt->execute([$contact_id]);
    $pending_queue = $stmt->fetchAll();
} catch (Exception $e) {
    g81_error('Errore DB durante il recupero della coda email: ' . $e->getMessage(), 500);
}

// ─── 6. RESPOND ──────────────────────────────────────────────────────────────
g81_response([
    'success'       => true,
    'contact'       => $contact,
    'consents'      => $consents,
    'recent_events' => $recent_events,
    'pending_queue' => $pending_queue,
]);
