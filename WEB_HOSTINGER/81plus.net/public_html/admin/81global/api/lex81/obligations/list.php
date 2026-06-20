<?php
// 81PLUS Global OS — LEX81 Obligations List
// GET: norm_code, ateco_code, deadline_type (all optional filters)
require_once __DIR__ . '/../../../_bootstrap.php';

$pdo = g81_pdo();

$sql    = 'SELECT ob.* FROM lex81_obligations ob';
$joins  = [];
$wheres = [];
$params = [];

// Filter by direct norm_code
$norm_code = trim($_GET['norm_code'] ?? '');
if ($norm_code !== '') {
    $wheres[] = 'ob.norm_code = ?';
    $params[] = $norm_code;
}

// Filter by ateco_code via lex81_ateco_map → lex81_norms
$ateco_code = trim($_GET['ateco_code'] ?? '');
if ($ateco_code !== '') {
    if (!preg_match('/^[A-Za-z0-9.\-]{1,10}$/', $ateco_code)) {
        g81_error('ateco_code non valido');
    }
    $joins[]  = 'INNER JOIN lex81_norms n ON n.code = ob.norm_code';
    $joins[]  = 'INNER JOIN lex81_ateco_map am ON am.norm_code = n.code';
    $wheres[] = 'am.ateco_prefix = ?';
    $params[] = substr($ateco_code, 0, 2);
}

// Filter by deadline_type enum value
$deadline_type = trim($_GET['deadline_type'] ?? '');
if ($deadline_type !== '') {
    $wheres[] = 'ob.deadline_type = ?';
    $params[] = $deadline_type;
}

// Build final query
if (!empty($joins)) {
    $sql .= ' ' . implode(' ', $joins);
}
if (!empty($wheres)) {
    $sql .= ' WHERE ' . implode(' AND ', $wheres);
}
$sql .= ' ORDER BY ob.norm_code, ob.id';

$st = $pdo->prepare($sql);
$st->execute($params);
$obligations = $st->fetchAll();

g81_response([
    'success'     => true,
    'obligations' => $obligations,
    'total'       => count($obligations),
]);
