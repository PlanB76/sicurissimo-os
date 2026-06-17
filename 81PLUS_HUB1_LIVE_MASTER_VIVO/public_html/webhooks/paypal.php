<?php
// webhooks/paypal.php — PayPal webhook listener
// Riceve PAYMENT.CAPTURE.COMPLETED e accredita PV in background
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/paygate_service.php';

$body    = file_get_contents('php://input');
$headers = [];
foreach (getallheaders() as $k => $v) {
    $headers[strtoupper($k)] = $v;
}

// Risponde subito 200 a PayPal (best practice: non tenere la connessione aperta)
http_response_code(200);
header('Content-Type: application/json');
echo '{"status":"received"}';
if (function_exists('fastcgi_finish_request')) fastcgi_finish_request();

// Verifica firma
if (!PaygateService::paypalVerifyWebhook($headers, $body)) {
    error_log('[paypal-webhook] Firma non valida');
    exit;
}

$event = json_decode($body, true);
$event_type = $event['event_type'] ?? '';

if ($event_type !== 'PAYMENT.CAPTURE.COMPLETED') exit;

$resource         = $event['resource'] ?? [];
$custom_id        = $resource['custom_id']
    ?? $resource['purchase_units'][0]['payments']['captures'][0]['custom_id']
    ?? null;
$paypal_capture_id = $resource['id'] ?? null;

if (!$custom_id) {
    error_log('[paypal-webhook] custom_id (order_code) mancante nel payload');
    exit;
}

try {
    $db = DB::get();

    // Aggiorna pagamenti_paypal
    $db->prepare(
        'UPDATE pagamenti_paypal
         SET status = "COMPLETED", completato_at = NOW(), webhook_data = ?
         WHERE paypal_order_id = (
             SELECT riferimento_ext FROM paygate_orders WHERE order_code = ? LIMIT 1
         )'
    )->execute([json_encode($event), $custom_id]);

    PaygateService::completeOrder($custom_id, $paypal_capture_id ?? '');

} catch (Throwable $e) {
    error_log('[paypal-webhook] ' . $e->getMessage());
}
