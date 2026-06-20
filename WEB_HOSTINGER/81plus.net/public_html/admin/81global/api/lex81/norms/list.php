<?php
// 81PLUS Global OS — LEX81 Norms List
// GET: ateco_code, category_code, priority, workers_count (all optional filters)
require_once __DIR__ . '/../../../_bootstrap.php';

$pdo = g81_pdo();

$sql    = 'SELECT n.* FROM lex81_norms n';
$joins  = [];
$wheres = [];
$params = [];

// Join ateco_map only when filtering by ateco_code
$ateco_code = trim($_GET['ateco_code'] ?? '');
if ($ateco_code !== '') {
    if (!preg_match('/^[A-Za-z0-9.\-]{1,10}$/', $ateco_code)) {
        g81_error('ateco_code non valido');
    }
    $joins[]  = 'INNER JOIN lex81_ateco_map am ON am.norm_code = n.code';
    $wheres[] = 'am.ateco_prefix = ?';
    $params[] = substr($ateco_code, 0, 2);
}

// Filter by category
$category_code = trim($_GET['category_code'] ?? '');
if ($category_code !== '') {
    $wheres[] = 'n.category_code = ?';
    $params[] = $category_code;
}

// Filter by priority (whitelist accepted values)
$priority = strtoupper(trim($_GET['priority'] ?? ''));
if ($priority !== '') {
    $allowed_priorities = ['CRITICA', 'ALTA', 'MEDIA', 'BASSA'];
    if (!in_array($priority, $allowed_priorities, true)) {
        g81_error('priority non valido (CRITICA|ALTA|MEDIA|BASSA)');
    }
    $wheres[] = 'n.priority = ?';
    $params[] = $priority;
}

// Filter by workers threshold
$workers_count = isset($_GET['workers_count']) && $_GET['workers_count'] !== '' ? (int)$_GET['workers_count'] : null;
if ($workers_count !== null) {
    if ($workers_count < 1 || $workers_count > 9999) {
        g81_error('workers_count non valido (1-9999)');
    }
    $wheres[] = 'n.min_workers <= ?';
    $params[] = $workers_count;
}

// Build final query
if (!empty($joins)) {
    $sql .= ' ' . implode(' ', $joins);
}
if (!empty($wheres)) {
    $sql .= ' WHERE ' . implode(' AND ', $wheres);
}
$sql .= ' ORDER BY n.priority, n.code';

$st = $pdo->prepare($sql);
$st->execute($params);
$norms = $st->fetchAll();

g81_response([
    'success' => true,
    'norms'   => $norms,
    'total'   => count($norms),
]);
