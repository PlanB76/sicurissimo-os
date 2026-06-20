<?php
// 81PLUS Global OS — LeadGen81 Contact Register
// FILE: api/leadgen81/contact/register.php
// METHOD: POST only
// Registers a new contact or updates an existing one, then queues WELCOME flow emails.

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

// ─── 2. VALIDATE REQUIRED FIELDS ─────────────────────────────────────────────
$raw_email = isset($data['email']) ? trim($data['email']) : '';
$raw_nome  = isset($data['nome'])  ? trim($data['nome'])  : '';

if ($raw_email === '') {
    g81_error('Il campo email e obbligatorio.', 400);
}
$email = filter_var($raw_email, FILTER_VALIDATE_EMAIL);
if ($email === false) {
    g81_error('Formato email non valido.', 400);
}

if ($raw_nome === '') {
    g81_error('Il campo nome e obbligatorio.', 400);
}
$nome = substr(strip_tags($raw_nome), 0, 100);

// ─── 3. SANITIZE OPTIONAL FIELDS ─────────────────────────────────────────────
$cognome       = isset($data['cognome'])       ? substr(strip_tags(trim($data['cognome'])), 0, 100)   : null;
$azienda       = isset($data['azienda'])       ? substr(strip_tags(trim($data['azienda'])), 0, 255)   : null;
$ateco_code    = isset($data['ateco_code'])    ? substr(strip_tags(trim($data['ateco_code'])), 0, 20) : null;
$workers_count = isset($data['workers_count']) ? (int)$data['workers_count']                           : null;
$phone         = isset($data['phone'])         ? substr(strip_tags(trim($data['phone'])), 0, 30)       : null;
$city          = isset($data['city'])          ? substr(strip_tags(trim($data['city'])), 0, 100)       : null;

$province = null;
if (!empty($data['province'])) {
    $prov = strtoupper(strip_tags(trim($data['province'])));
    if (strlen($prov) === 2 && ctype_alpha($prov)) {
        $province = $prov;
    }
}

$source = (!empty($data['source'])) ? substr(strip_tags(trim($data['source'])), 0, 50) : 'API';

$consents = [];
if (!empty($data['consents']) && is_array($data['consents'])) {
    foreach ($data['consents'] as $c) {
        $cleaned = substr(strip_tags(trim((string)$c)), 0, 50);
        if ($cleaned !== '') {
            $consents[] = $cleaned;
        }
    }
}

// ─── 4. CHECK IF CONTACT EXISTS ───────────────────────────────────────────────
try {
    $existing = g81_get_contact_by_email($email);
} catch (Exception $e) {
    g81_error('Errore DB durante la verifica del contatto: ' . $e->getMessage(), 500);
}

// ─── 5A. CONTACT EXISTS — UPDATE NON-NULL VALUES ─────────────────────────────
if ($existing) {
    $contact_id  = (int)$existing['id'];
    $tracking_id = $existing['tracking_id'];

    $set_parts = [];
    $params    = [];

    if ($nome !== '') {
        $set_parts[] = 'nome = ?';
        $params[]    = $nome;
    }
    if ($cognome !== null) {
        $set_parts[] = 'cognome = ?';
        $params[]    = $cognome;
    }
    if ($azienda !== null) {
        $set_parts[] = 'azienda = ?';
        $params[]    = $azienda;
    }
    if ($ateco_code !== null) {
        $set_parts[] = 'ateco_code = ?';
        $params[]    = $ateco_code;
    }
    if ($workers_count !== null) {
        $set_parts[] = 'workers_count = ?';
        $params[]    = $workers_count;
    }
    if ($phone !== null) {
        $set_parts[] = 'phone = ?';
        $params[]    = $phone;
    }
    if ($city !== null) {
        $set_parts[] = 'city = ?';
        $params[]    = $city;
    }
    if ($province !== null) {
        $set_parts[] = 'province = ?';
        $params[]    = $province;
    }
    if ($source !== 'API') {
        $set_parts[] = 'source = ?';
        $params[]    = $source;
    }

    if (!empty($set_parts)) {
        $set_parts[] = 'updated_at = NOW()';
        $params[]    = $contact_id;
        try {
            $sql = 'UPDATE leadgen81_contacts SET ' . implode(', ', $set_parts) . ' WHERE id = ?';
            g81_pdo()->prepare($sql)->execute($params);
        } catch (Exception $e) {
            g81_error('Errore DB durante l\'aggiornamento del contatto: ' . $e->getMessage(), 500);
        }
    }

    try {
        $segment = g81_calculate_segment($contact_id);
    } catch (Exception $e) {
        $segment = isset($existing['segment']) ? $existing['segment'] : 'COLD';
    }

    g81_response([
        'success'          => true,
        'contact_id'       => $contact_id,
        'tracking_id'      => $tracking_id,
        'segment'          => $segment,
        'existing_contact' => true,
        'flow_triggered'   => null,
        'emails_queued'    => 0,
    ]);
}

// ─── 5B. NEW CONTACT — INSERT ─────────────────────────────────────────────────
try {
    $tracking_id = g81_generate_tracking_id();
} catch (Exception $e) {
    g81_error('Errore generazione tracking_id: ' . $e->getMessage(), 500);
}

try {
    $pdo = g81_pdo();

    $stmt = $pdo->prepare('
        INSERT INTO leadgen81_contacts
            (email, nome, cognome, azienda, ateco_code, workers_count, phone, city, province,
             source, tracking_id, is_subscribed, segment, created_at, updated_at)
        VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, \'COLD\', NOW(), NOW())
    ');
    $stmt->execute([
        $email,
        $nome,
        $cognome,
        $azienda,
        $ateco_code,
        $workers_count,
        $phone,
        $city,
        $province,
        $source,
        $tracking_id,
    ]);
    $contact_id = (int)$pdo->lastInsertId();
} catch (Exception $e) {
    g81_error('Errore DB durante la creazione del contatto: ' . $e->getMessage(), 500);
}

// ─── 6. INSERT CONSENTS ───────────────────────────────────────────────────────
if (!empty($consents)) {
    try {
        $pdo         = g81_pdo();
        $consent_sql = '
            INSERT INTO leadgen81_consents (contact_id, consent_type, granted, granted_at, created_at)
            VALUES (?, ?, 1, NOW(), NOW())
            ON DUPLICATE KEY UPDATE granted = 1, granted_at = NOW()
        ';
        $consent_stmt = $pdo->prepare($consent_sql);
        foreach ($consents as $consent_type) {
            $consent_stmt->execute([$contact_id, $consent_type]);
        }
    } catch (Exception $e) {
        g81_error('Errore DB durante il salvataggio dei consensi: ' . $e->getMessage(), 500);
    }
}

// ─── 7. TRACK REGISTER EVENT ─────────────────────────────────────────────────
try {
    g81_track_event($contact_id, 'REGISTER', [
        'source'   => $source,
        'email'    => $email,
        'consents' => $consents,
    ]);
} catch (Exception $e) {
    // Non bloccante
}

// ─── 8. CALCULATE SEGMENT ────────────────────────────────────────────────────
try {
    $segment = g81_calculate_segment($contact_id);
} catch (Exception $e) {
    $segment = 'COLD';
}

// ─── 9. TRIGGER WELCOME FLOW ─────────────────────────────────────────────────
$emails_queued  = 0;
$flow_triggered = null;

try {
    $pdo   = g81_pdo();
    $steps = $pdo->prepare("
        SELECT * FROM leadgen81_flow_steps
        WHERE flow_code = 'WELCOME'
        ORDER BY step_order ASC
    ");
    $steps->execute();
    $flow_steps = $steps->fetchAll();

    if (!empty($flow_steps)) {
        $flow_triggered = 'WELCOME';
        foreach ($flow_steps as $step) {
            g81_queue_email($contact_id, $step);
            $emails_queued++;
        }
    }
} catch (Exception $e) {
    // Non bloccante — il contatto e gia registrato
}

// ─── 10. UPDATE SEGMENT ON CONTACT ───────────────────────────────────────────
try {
    g81_pdo()->prepare('UPDATE leadgen81_contacts SET segment = ? WHERE id = ?')
             ->execute([$segment, $contact_id]);
} catch (Exception $e) {
    // Non bloccante
}

// ─── 11. RESPOND ─────────────────────────────────────────────────────────────
g81_response([
    'success'          => true,
    'contact_id'       => $contact_id,
    'tracking_id'      => $tracking_id,
    'segment'          => $segment,
    'existing_contact' => false,
    'flow_triggered'   => $flow_triggered,
    'emails_queued'    => $emails_queued,
]);
