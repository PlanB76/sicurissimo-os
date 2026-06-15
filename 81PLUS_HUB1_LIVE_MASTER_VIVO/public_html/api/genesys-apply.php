<?php
// api/genesys-apply.php — candidatura GENESYS81+
// POST: {nome, cognome, email, settore, community_size, piattaforma, motivazione, vuole_pix, come_ha_conosciuto}

declare(strict_types=1);
require_once __DIR__ . '/../core81/db.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];

$nome    = trim($body['nome'] ?? '');
$cognome = trim($body['cognome'] ?? '');
$email   = trim($body['email'] ?? '');

if (!$nome || !$cognome || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'nome, cognome e email valida obbligatori']);
    exit;
}

// User loggato opzionale
session_start();
$user_id = $_SESSION['user_id'] ?? null;
$sic_id  = $_SESSION['sic_id'] ?? null;

try {
    $db = DB::get();
    $stmt = $db->prepare("INSERT INTO genesys_applications 
        (user_id, sic_id, nome, cognome, email, settore, community_size, 
         piattaforma, motivazione, vuole_pix, come_ha_conosciuto)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $user_id, $sic_id, $nome, $cognome, $email,
        $body['settore'] ?? null,
        (int)($body['community_size'] ?? 0) ?: null,
        $body['piattaforma'] ?? null,
        $body['motivazione'] ?? null,
        (int)($body['vuole_pix'] ?? 0),
        $body['come_ha_conosciuto'] ?? null,
    ]);
    // TODO: inviare notifica admin + autoresponder email
    echo json_encode(['ok' => true, 'message' => 'Candidatura ricevuta. Ti contatteremo presto.']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Errore salvataggio candidatura']);
}
