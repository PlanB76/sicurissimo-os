<?php
// 81PLUS Global OS — LEX81 Risk Profile
// POST: ateco_code, workers_count, haccp_applicable, edilizia_applicable
// Returns applicable norms, obligations, services and risk level for the company
require_once __DIR__ . '/../../_bootstrap.php';

$p = g81_require_post();

// --- Input validation ---
$ateco_code = trim($p['ateco_code'] ?? '');
if ($ateco_code === '' || !preg_match('/^[A-Za-z0-9.\-]{1,10}$/', $ateco_code)) {
    g81_error('ateco_code non valido (alfanumerico, punti e trattini, max 10 caratteri)');
}

$workers_count = isset($p['workers_count']) ? (int)$p['workers_count'] : 0;
if ($workers_count < 1 || $workers_count > 9999) {
    g81_error('workers_count non valido (1-9999)');
}

$haccp_applicable    = (int)(bool)($p['haccp_applicable']    ?? 0);
$edilizia_applicable = (int)(bool)($p['edilizia_applicable'] ?? 0);

$pdo = g81_pdo();

// ATECO prefix: first 2 chars
$ateco_prefix = substr($ateco_code, 0, 2);

// --- 1. Norms matched via ateco_map JOIN ---
$st = $pdo->prepare(
    'SELECT n.* FROM lex81_norms n
     INNER JOIN lex81_ateco_map am ON am.norm_code = n.code
     WHERE am.ateco_prefix = ?
       AND n.min_workers <= ?
       AND (n.max_workers IS NULL OR n.max_workers >= ?)
     ORDER BY n.priority, n.code'
);
$st->execute([$ateco_prefix, $workers_count, $workers_count]);
$ateco_norms = $st->fetchAll();

// --- 2. Universal norms (applicable to all companies) ---
$st2 = $pdo->prepare(
    "SELECT * FROM lex81_norms
     WHERE code IN ('DL81_2008','DL81_ART_37_FORMAZIONE')
       AND min_workers <= ?
     ORDER BY priority"
);
$st2->execute([$workers_count]);
$universal_norms = $st2->fetchAll();

// --- 3. HACCP-only norms when applicable ---
$haccp_norms = [];
if ($haccp_applicable) {
    $st3 = $pdo->prepare(
        'SELECT * FROM lex81_norms
         WHERE haccp_only = 1
           AND min_workers <= ?
         ORDER BY priority, code'
    );
    $st3->execute([$workers_count]);
    $haccp_norms = $st3->fetchAll();
}

// --- 4. Edilizia-only norms when applicable ---
$edilizia_norms = [];
if ($edilizia_applicable) {
    $st4 = $pdo->prepare(
        'SELECT * FROM lex81_norms
         WHERE edilizia_only = 1
           AND min_workers <= ?
         ORDER BY priority, code'
    );
    $st4->execute([$workers_count]);
    $edilizia_norms = $st4->fetchAll();
}

// --- 5. Merge all norms, deduplicate by code ---
$all_norms = [];
foreach (array_merge($ateco_norms, $universal_norms, $haccp_norms, $edilizia_norms) as $norm) {
    $all_norms[$norm['code']] = $norm;
}
$all_norms = array_values($all_norms);

// --- 6. Obligations for the applicable norms ---
$obligations = [];
if (!empty($all_norms)) {
    $norm_codes   = array_column($all_norms, 'code');
    $placeholders = implode(',', array_fill(0, count($norm_codes), '?'));
    $params       = array_merge($norm_codes, [$workers_count]);
    $stOb = $pdo->prepare(
        "SELECT * FROM lex81_obligations
         WHERE norm_code IN ($placeholders)
           AND min_workers <= ?
         ORDER BY norm_code, id"
    );
    $stOb->execute($params);
    $obligations = $stOb->fetchAll();
}

// --- 7. Available services (active only, cheapest first) ---
$stSv = $pdo->prepare('SELECT * FROM lex81_services WHERE active = 1 ORDER BY price_from');
$stSv->execute();
$services = $stSv->fetchAll();

// --- 8. Risk level based on highest priority norm found ---
$has_critica = false;
$has_alta    = false;
foreach ($all_norms as $norm) {
    if ($norm['priority'] === 'CRITICA') {
        $has_critica = true;
    } elseif ($norm['priority'] === 'ALTA') {
        $has_alta = true;
    }
}

if ($has_critica && $workers_count >= 10) {
    $risk_level = 'CRITICO';
} elseif ($has_critica) {
    $risk_level = 'ALTO';
} elseif ($has_alta) {
    $risk_level = 'MEDIO';
} else {
    $risk_level = 'BASSO';
}

// --- 9. Summary counts ---
$critical_count = 0;
foreach ($all_norms as $norm) {
    if ($norm['priority'] === 'CRITICA') {
        $critical_count++;
    }
}

g81_response([
    'success'          => true,
    'risk_level'       => $risk_level,
    'applicable_norms' => $all_norms,
    'obligations'      => $obligations,
    'services'         => $services,
    'summary'          => [
        'norm_count'       => count($all_norms),
        'critical_count'   => $critical_count,
        'obligation_count' => count($obligations),
    ],
]);
