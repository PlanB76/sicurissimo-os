<?php
// api/kyc-submit.php — Invio documenti KYC
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/auth_guard.php';
require_once dirname(__DIR__) . '/core81/wallet_service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_err('Metodo non consentito', 405);
$user = auth_guard();
csrf_check();

// Verifica stato KYC attuale
$db   = DB::get();
$stmt = $db->prepare('SELECT kyc_status FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$user['id']]);
$row = $stmt->fetch();
if (in_array($row['kyc_status'] ?? '', ['VERIFIED','PENDING'], true)) {
    json_err('KYC gia inviato o verificato.');
}

$allowed_types = ['image/jpeg','image/png','image/webp','application/pdf'];
$max_size      = 5 * 1024 * 1024; // 5MB

foreach (['doc_front','doc_back','doc_selfie'] as $field) {
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        json_err('File "' . $field . '" non valido o mancante');
    }
    if ($_FILES[$field]['size'] > $max_size) json_err('File troppo grande (max 5MB)');
    if (!in_array($_FILES[$field]['type'], $allowed_types, true)) json_err('Formato non supportato (JPG, PNG, PDF)');
}

// Salva in uploads/kyc/ (directory protetta)
$upload_dir = dirname(__DIR__) . '/uploads/kyc/' . $user['id'] . '/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0700, true);

$saved = [];
foreach (['doc_front','doc_back','doc_selfie'] as $field) {
    $ext      = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
    $filename = $field . '_' . time() . '.' . $ext;
    move_uploaded_file($_FILES[$field]['tmp_name'], $upload_dir . $filename);
    $saved[] = $filename;
}

$db->prepare('UPDATE users SET kyc_status = "PENDING" WHERE id = ?')->execute([$user['id']]);

json_ok(['msg' => 'Documenti KYC ricevuti. Verifica entro 24/48 ore lavorative. Riceverai email di conferma a ' . $user['email'] . '.']);
