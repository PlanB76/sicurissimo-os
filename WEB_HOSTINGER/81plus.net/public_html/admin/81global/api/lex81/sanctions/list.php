<?php
// 81PLUS Global OS — LEX81 Sanctions List
// GET: norm_code, validated_only, min_amount (all optional filters)
require_once __DIR__ . '/../../../_bootstrap.php';

$pdo = g81_pdo();

$sql    = 'SELECT * FROM lex81_sanctions';
$wheres = [];
$params = [];

// Filter by norm_code
$norm_code = trim($_GET['norm_code'] ?? '');
if ($norm_code !== '') {
    $wheres[] = 'norm_code = ?';
    $params[] = $norm_code;
}

// Return only validated sanctions
if (($_GET['validated_only'] ?? '') === '1') {
    $wheres[] = 'validated = 1';
}

// Filter by minimum sanction amount
$min_amount = isset($_GET['min_amount']) && $_GET['min_amount'] !== '' ? $_GET['min_amount'] : null;
if ($min_amount !== null) {
    if (!is_numeric($min_amount) || (float)$min_amount < 0) {
        g81_error('min_amount non valido');
    }
    $wheres[] = 'amount_min >= ?';
    $params[] = (float)$min_amount;
}

// Build final query
if (!empty($wheres)) {
    $sql .= ' WHERE ' . implode(' AND ', $wheres);
}
$sql .= ' ORDER BY norm_code, amount_min';

$st = $pdo->prepare($sql);
$st->execute($params);
$sanctions = $st->fetchAll();

g81_response([
    'success'   => true,
    'sanctions' => $sanctions,
    'total'     => count($sanctions),
]);
