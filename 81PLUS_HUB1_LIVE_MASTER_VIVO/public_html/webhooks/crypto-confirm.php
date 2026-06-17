<?php
// webhooks/crypto-confirm.php — Conferma manuale pagamento crypto (solo ADMIN)
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/auth_guard.php';
require_once dirname(__DIR__) . '/core81/paygate_service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_err('Metodo non consentito', 405);
$user = auth_guard(['ADMIN81']);
csrf_check();

$order_code = trim($_POST['order_code'] ?? '');
$tx_hash    = trim($_POST['tx_hash'] ?? '');

if (!$order_code || !$tx_hash) json_err('order_code e tx_hash sono obbligatori');

try {
    $db    = DB::get();
    $order = PaygateService::getOrder($order_code);

    if (!$order) json_err('Ordine non trovato');
    if ($order['metodo'] !== 'CRYPTO') json_err('Ordine non è di tipo crypto');
    if ($order['status'] === 'COMPLETED') json_ok(['msg' => 'Già completato (idempotente)']);

    // Aggiorna pagamenti_crypto con tx_hash e COMPLETED
    $db->prepare(
        'UPDATE pagamenti_crypto
         SET status = "COMPLETED", tx_hash = ?, completato_at = NOW(), blocchi_confermati = 1
         WHERE id = ?'
    )->execute([$tx_hash, $order['metodo_ref_id']]);

    $ok = PaygateService::completeOrder($order_code, $tx_hash);

    if ($ok) {
        json_ok(['msg' => 'PV accreditati per ordine ' . $order_code]);
    } else {
        json_err('Accredito fallito — controlla i log');
    }

} catch (Throwable $e) {
    error_log('[crypto-confirm] ' . $e->getMessage());
    json_err('Errore tecnico: ' . $e->getMessage());
}
