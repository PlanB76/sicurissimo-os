<?php
// api/paypal-capture.php — Return URL PayPal dopo pagamento
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/auth_guard.php';
require_once dirname(__DIR__) . '/core81/paygate_service.php';

$user = auth_guard(['MEMBER81', 'NETWORKER81', 'ELITE81', 'ADMIN81']);

$paypal_order_id = trim($_GET['token'] ?? '');  // PayPal chiama il parametro "token"
if (!$paypal_order_id) {
    header('Location: ' . BASE_URL . '/paygate81.php?error=parametri_mancanti');
    exit;
}

try {
    // Cerca ordine interno per paypal_order_id
    $db   = DB::get();
    $stmt = $db->prepare(
        'SELECT po.order_code, po.importo_euro, po.status
         FROM paygate_orders po
         JOIN pagamenti_paypal pp ON pp.id = po.metodo_ref_id
         WHERE pp.paypal_order_id = ? AND po.user_id = ?
         LIMIT 1'
    );
    $stmt->execute([$paypal_order_id, $user['id']]);
    $order = $stmt->fetch();

    if (!$order) {
        header('Location: ' . BASE_URL . '/paygate81.php?error=ordine_non_trovato');
        exit;
    }

    if ($order['status'] === 'COMPLETED') {
        header('Location: ' . BASE_URL . '/paygate81.php?success=1&order=' . urlencode($order['order_code']));
        exit;
    }

    // Cattura pagamento PayPal
    $capture = PaygateService::paypalCapture($paypal_order_id);

    if (($capture['status'] ?? '') !== 'COMPLETED') {
        header('Location: ' . BASE_URL . '/paygate81.php?error=pagamento_non_completato');
        exit;
    }

    // Aggiorna pagamenti_paypal
    $db->prepare(
        'UPDATE pagamenti_paypal
         SET status = "COMPLETED", paypal_payer_id = ?, completato_at = NOW(), webhook_data = ?
         WHERE paypal_order_id = ?'
    )->execute([
        $capture['payer']['payer_id'] ?? null,
        json_encode($capture),
        $paypal_order_id,
    ]);

    // Accredita PV
    $ok = PaygateService::completeOrder($order['order_code'], $paypal_order_id);

    if ($ok) {
        header('Location: ' . BASE_URL . '/paygate81.php?success=1&order=' . urlencode($order['order_code']));
    } else {
        header('Location: ' . BASE_URL . '/paygate81.php?error=accredito_fallito&order=' . urlencode($order['order_code']));
    }

} catch (Throwable $e) {
    error_log('[paypal-capture] ' . $e->getMessage());
    header('Location: ' . BASE_URL . '/paygate81.php?error=errore_tecnico');
}
exit;
