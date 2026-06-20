<?php
/**
 * 81PLUS Global OS — LeadGen81: Flow Trigger
 * Skill 39 — CRM Bridge
 *
 * POST /api/leadgen81/flow/trigger
 * Body (JSON): {
 *   "contact_id": <int>  // OR
 *   "email":      <string>,
 *   "flow_code":  <string>  // required
 * }
 *
 * Avvia un flow email per un contatto. Mette in coda tutti gli step attivi.
 */

require_once __DIR__ . '/../../../_bootstrap.php';

// ---------------------------------------------------------------------------
// 1. Metodo e input
// ---------------------------------------------------------------------------

g81_require_post();

$body       = (array) json_decode(file_get_contents('php://input'), true);
$contact_id = isset($body['contact_id']) ? (int) $body['contact_id'] : 0;
$email      = trim((string) ($body['email'] ?? ''));
$flow_code  = trim((string) ($body['flow_code'] ?? ''));

if (empty($flow_code)) {
    g81_error('flow_code obbligatorio');
}

if ($contact_id <= 0 && $email === '') {
    g81_error('contact_id oppure email obbligatori');
}

// ---------------------------------------------------------------------------
// 2. Risolve contatto
// ---------------------------------------------------------------------------

if ($contact_id > 0) {
    $contact = g81_get_contact($contact_id);
} else {
    $contact = g81_get_contact_by_email($email);
}

if ($contact === null) {
    g81_error('Contatto non trovato', 404);
}

$contact_id = (int) $contact['id'];

// ---------------------------------------------------------------------------
// 3. Valida flow
// ---------------------------------------------------------------------------

$pdo = g81_pdo();

try {
    $st = $pdo->prepare(
        "SELECT id FROM leadgen81_flows
         WHERE flow_code = ? AND active = 1
         LIMIT 1"
    );
    $st->execute([$flow_code]);
    $flow_row = $st->fetch();
} catch (PDOException $e) {
    g81_error('Errore verifica flow: ' . $e->getMessage(), 500);
}

if ($flow_row === false) {
    g81_error('Flow non trovato o inattivo', 404);
}

// ---------------------------------------------------------------------------
// 4. Recupera step del flow
// ---------------------------------------------------------------------------

try {
    $st = $pdo->prepare(
        "SELECT *
         FROM leadgen81_flow_steps
         WHERE flow_code = ? AND active = 1
         ORDER BY step_order ASC"
    );
    $st->execute([$flow_code]);
    $steps = $st->fetchAll();
} catch (PDOException $e) {
    g81_error('Errore recupero step flow: ' . $e->getMessage(), 500);
}

// ---------------------------------------------------------------------------
// 5. Metti in coda ogni step
// ---------------------------------------------------------------------------

$emails_queued  = 0;
$first_send_at  = null;
$min_delay_hours = PHP_INT_MAX;

foreach ($steps as $step) {
    $queued = g81_queue_email(
        $contact_id,
        $flow_code,
        (int) $step['step_order'],
        $step
    );

    if ($queued !== false) {
        $emails_queued++;

        $delay_hours = (int) ($step['delay_hours'] ?? 0);
        if ($delay_hours < $min_delay_hours) {
            $min_delay_hours = $delay_hours;
        }
    }
}

// Calcola first_send_at come NOW + delay minimo
if ($emails_queued > 0 && $min_delay_hours < PHP_INT_MAX) {
    try {
        $dt = new DateTime();
        $dt->add(new DateInterval('PT' . $min_delay_hours . 'H'));
        $first_send_at = $dt->format('Y-m-d H:i:s');
    } catch (Exception $e) {
        $first_send_at = null;
    }
}

// ---------------------------------------------------------------------------
// 6. Risposta
// ---------------------------------------------------------------------------

g81_response([
    'success'       => true,
    'flow_code'     => $flow_code,
    'contact_id'    => $contact_id,
    'emails_queued' => $emails_queued,
    'first_send_at' => $first_send_at,
]);
