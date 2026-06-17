<?php
// webhooks/revolut.php — Revolut webhook listener
// Evento: ORDER_COMPLETED → accredita PV
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/paygate_service.php';

$body      = file_get_contents('php://input');
$sig_hdr   = $_SERVER['HTTP_REVOLUT_SIGNATURE'] ?? '';

// Risponde subito 200
http_response_code(200);
header('Content-Type: application/json');
echo '{"status":"received"}';
if (function_exists('fastcgi_finish_request')) fastcgi_finish_request();

// Verifica firma HMAC
if (!PaygateService::revolutVerifyWebhook($body, $sig_hdr)) {
    error_log('[revolut-webhook] Firma non valida');
    exit;
}

$event = json_decode($body, true);
$type  = $event['event'] ?? ($event['type'] ?? '');

// Revolut invia ORDER_COMPLETED quando il pagamento è confermato
if (!in_array($type, ['ORDER_COMPLETED', 'order_completed', 'ORDER.COMPLETED'], true)) exit;

$order_data       = $event['order_data'] ?? $event['data'] ?? $event;
$revolut_order_id = $order_data['id'] ?? null;
$ext_ref          = $order_data['merchant_order_ext_ref'] ?? null;  // = nostro order_code

if (!$ext_ref && !$revolut_order_id) {
    error_log('[revolut-webhook] Nessun riferimento ordine nel payload');
    exit;
}

try {
    $db = DB::get();

    // Cerca order_code tramite riferimento esterno (o revolut_order_id)
    $order_code = $ext_ref;
    if (!$order_code) {
        $stmt = $db->prepare(
            'SELECT po.order_code FROM paygate_orders po
             JOIN pagamenti_revolut pr ON pr.id = po.metodo_ref_id
             WHERE pr.revolut_order_id = ? LIMIT 1'
        );
        $stmt->execute([$revolut_order_id]);
        $order_code = $stmt->fetchColumn();
    }

    if (!$order_code) {
        error_log('[revolut-webhook] order_code non trovato per revolut_id: ' . $revolut_order_id);
        exit;
    }

    $db->prepare(
        'UPDATE pagamenti_revolut
         SET status = "COMPLETED", completato_at = NOW(), webhook_data = ?
         WHERE revolut_order_id = ?'
    )->execute([json_encode($event), $revolut_order_id ?? $ext_ref]);

    PaygateService::completeOrder($order_code, $revolut_order_id ?? '');

} catch (Throwable $e) {
    error_log('[revolut-webhook] ' . $e->getMessage());
}
