<?php
/**
 * 81PLUS Global OS — LeadGen81: Segment Calculator
 * Skill 38 — Lead Scoring
 *
 * POST /api/leadgen81/segment/calculate
 * Body (JSON): { "contact_id": <int> }
 *
 * Calcola segmento, temperature_score e tags per il contatto.
 * Aggiorna leadgen81_contacts e, se il segmento e cambiato, avvia il flow
 * automatico associato al nuovo segmento.
 */

require_once __DIR__ . '/../../../_bootstrap.php';

// ---------------------------------------------------------------------------
// 1. Metodo e input
// ---------------------------------------------------------------------------

g81_require_post();

$body       = (array) json_decode(file_get_contents('php://input'), true);
$contact_id = (int) ($body['contact_id'] ?? 0);

if ($contact_id <= 0) {
    g81_error('contact_id obbligatorio e deve essere un intero positivo');
}

// ---------------------------------------------------------------------------
// 2. Recupera contatto
// ---------------------------------------------------------------------------

$contact = g81_get_contact($contact_id);
if ($contact === null) {
    g81_error('Contatto non trovato', 404);
}

$old_segment = $contact['segment'] ?? null;

// ---------------------------------------------------------------------------
// 3. Segmento
// ---------------------------------------------------------------------------

$new_segment = g81_calculate_segment($contact);

// ---------------------------------------------------------------------------
// 4. Temperature score
// ---------------------------------------------------------------------------

$heat_score = (float) ($contact['heat_score'] ?? 0);
$temp_score = $heat_score;

$pdo = g81_pdo();

try {
    // +5 per eventi OPEN negli ultimi 7 giorni
    $st = $pdo->prepare(
        "SELECT COUNT(*) FROM leadgen81_events
         WHERE contact_id = ?
           AND event_type = 'OPEN'
           AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
    );
    $st->execute([$contact_id]);
    if ((int) $st->fetchColumn() > 0) {
        $temp_score += 5;
    }

    // +8 per eventi CLICK negli ultimi 7 giorni
    $st = $pdo->prepare(
        "SELECT COUNT(*) FROM leadgen81_events
         WHERE contact_id = ?
           AND event_type = 'CLICK'
           AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
    );
    $st->execute([$contact_id]);
    if ((int) $st->fetchColumn() > 0) {
        $temp_score += 8;
    }

    // +10 per eventi DOWNLOAD o WEBINAR_JOIN negli ultimi 30 giorni
    $st = $pdo->prepare(
        "SELECT COUNT(*) FROM leadgen81_events
         WHERE contact_id = ?
           AND event_type IN ('DOWNLOAD', 'WEBINAR_JOIN')
           AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)"
    );
    $st->execute([$contact_id]);
    if ((int) $st->fetchColumn() > 0) {
        $temp_score += 10;
    }
} catch (PDOException $e) {
    g81_error('Errore lettura eventi: ' . $e->getMessage(), 500);
}

// Cap a 100.00
$temp_score = round(min(100.00, $temp_score), 2);

// ---------------------------------------------------------------------------
// 5. Tags
// ---------------------------------------------------------------------------

$tags          = [];
$ateco_code    = (string) ($contact['ateco_code']    ?? '');
$workers_count = (int)    ($contact['workers_count'] ?? 0);
$risk_level    = strtoupper((string) ($contact['risk_level'] ?? ''));

// Settore food & beverage
if (
    strncmp($ateco_code, '56', 2) === 0 ||
    strncmp($ateco_code, '10', 2) === 0 ||
    strncmp($ateco_code, '11', 2) === 0
) {
    $tags[] = 'FOOD_BEVERAGE';
}

// Dimensione azienda
if ($workers_count >= 50) {
    $tags[] = 'GRANDE_AZIENDA';
} elseif ($workers_count >= 10) {
    $tags[] = 'PMI';
} else {
    $tags[] = 'MICRO_IMPRESA';
}

// Calore lead
if ($heat_score >= 60) {
    $tags[] = 'LEAD_CALDO';
}

// Rischio normativo
if ($risk_level === 'CRITICO' || $risk_level === 'ALTO') {
    $tags[] = 'RISCHIO_ALTO';
}

$tags_json = json_encode($tags, JSON_UNESCAPED_UNICODE);

// ---------------------------------------------------------------------------
// 6. Aggiorna contatto
// ---------------------------------------------------------------------------

try {
    $st = $pdo->prepare(
        "UPDATE leadgen81_contacts
         SET segment          = ?,
             temperature_score = ?,
             tags              = ?,
             updated_at        = NOW()
         WHERE id = ?"
    );
    $st->execute([$new_segment, $temp_score, $tags_json, $contact_id]);
} catch (PDOException $e) {
    g81_error('Errore aggiornamento contatto: ' . $e->getMessage(), 500);
}

// ---------------------------------------------------------------------------
// 7. Auto-flow sul cambio di segmento
// ---------------------------------------------------------------------------

$auto_flow_triggered = null;

if ($new_segment !== $old_segment) {
    try {
        $st = $pdo->prepare(
            "SELECT auto_flow_code
             FROM leadgen81_segments
             WHERE segment_code = CONCAT('SEG_', ?)"
        );
        $st->execute([$new_segment]);
        $seg_row = $st->fetch();

        if ($seg_row !== false && !empty($seg_row['auto_flow_code'])) {
            $flow_code = $seg_row['auto_flow_code'];

            $st_steps = $pdo->prepare(
                "SELECT *
                 FROM leadgen81_flow_steps
                 WHERE flow_code = ? AND active = 1
                 ORDER BY step_order ASC"
            );
            $st_steps->execute([$flow_code]);
            $steps = $st_steps->fetchAll();

            foreach ($steps as $step) {
                g81_queue_email(
                    $contact_id,
                    $flow_code,
                    (int) $step['step_order'],
                    $step
                );
            }

            if (!empty($steps)) {
                $auto_flow_triggered = $flow_code;
            }
        }
    } catch (PDOException $e) {
        // Il contatto e gia aggiornato: non bloccare la risposta
        // L'errore del flow viene silenziosamente ignorato
    }
}

// ---------------------------------------------------------------------------
// 8. Risposta
// ---------------------------------------------------------------------------

g81_response([
    'success'             => true,
    'contact_id'          => $contact_id,
    'old_segment'         => $old_segment,
    'new_segment'         => $new_segment,
    'heat_score'          => $heat_score,
    'temperature_score'   => $temp_score,
    'tags'                => $tags,
    'auto_flow_triggered' => $auto_flow_triggered,
]);
