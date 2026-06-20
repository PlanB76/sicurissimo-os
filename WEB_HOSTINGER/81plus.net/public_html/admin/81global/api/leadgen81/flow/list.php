<?php
/**
 * 81PLUS Global OS — LeadGen81: Flow List
 * Skill 31 — Data Analytics (admin view)
 *
 * GET /api/leadgen81/flow/list
 *
 * Restituisce tutti i flow con conteggio step e email PENDING in coda.
 * Endpoint admin-only.
 */

require_once __DIR__ . '/../../../_bootstrap.php';

// ---------------------------------------------------------------------------
// 1. Admin guard + metodo
// ---------------------------------------------------------------------------

g81_require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    g81_error('Metodo non supportato', 405);
}

// ---------------------------------------------------------------------------
// 2. Recupera tutti i flow
// ---------------------------------------------------------------------------

$pdo = g81_pdo();

try {
    $st = $pdo->prepare(
        "SELECT flow_code, name, description, active, created_at, updated_at
         FROM leadgen81_flows
         ORDER BY flow_code ASC"
    );
    $st->execute();
    $flows = $st->fetchAll();
} catch (PDOException $e) {
    g81_error('Errore recupero flow: ' . $e->getMessage(), 500);
}

// ---------------------------------------------------------------------------
// 3. Arricchisce ogni flow con conteggi
// ---------------------------------------------------------------------------

$result = [];

foreach ($flows as $flow) {
    $code = $flow['flow_code'];

    // Numero di step attivi
    try {
        $st_steps = $pdo->prepare(
            "SELECT COUNT(*) FROM leadgen81_flow_steps
             WHERE flow_code = ?"
        );
        $st_steps->execute([$code]);
        $step_count = (int) $st_steps->fetchColumn();
    } catch (PDOException $e) {
        $step_count = 0;
    }

    // Numero di email PENDING in coda
    try {
        $st_queue = $pdo->prepare(
            "SELECT COUNT(*) FROM leadgen81_email_queue
             WHERE flow_code = ? AND status = 'PENDING'"
        );
        $st_queue->execute([$code]);
        $pending_count = (int) $st_queue->fetchColumn();
    } catch (PDOException $e) {
        $pending_count = 0;
    }

    $result[] = [
        'flow_code'           => $code,
        'name'                => $flow['name']        ?? '',
        'description'         => $flow['description'] ?? null,
        'active'              => (int) $flow['active'],
        'step_count'          => $step_count,
        'pending_queue_count' => $pending_count,
        'created_at'          => $flow['created_at']  ?? null,
        'updated_at'          => $flow['updated_at']  ?? null,
    ];
}

// ---------------------------------------------------------------------------
// 4. Risposta
// ---------------------------------------------------------------------------

g81_response([
    'success' => true,
    'flows'   => $result,
    'total'   => count($result),
]);
