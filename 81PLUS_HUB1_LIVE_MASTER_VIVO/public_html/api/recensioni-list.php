<?php
// api/recensioni-list.php — Lista testimonianze pubbliche
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

$settore = trim($_GET['settore'] ?? '');
$rating  = (int)($_GET['rating'] ?? 0);
$limit   = min((int)($_GET['limit'] ?? 20), 50);

$db     = DB::get();
$where  = ['r.status = "APPROVED"'];
$params = [];

if ($settore) { $where[] = 'r.settore = ?'; $params[] = $settore; }
if ($rating)  { $where[] = 'r.rating = ?';  $params[] = $rating; }

$sql  = 'SELECT r.autore_nome AS autore, r.ruolo_azienda, r.settore, r.testo, r.rating, DATE_FORMAT(r.verificata_il, "%d/%m/%Y") AS data_verifica
         FROM recensioni r WHERE ' . implode(' AND ', $where) . ' ORDER BY r.verificata_il DESC LIMIT ?';
$params[] = $limit;

$stmt = $db->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();

json_ok(['items' => $items, 'count' => count($items)]);
