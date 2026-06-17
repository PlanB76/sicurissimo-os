<?php
// api/paygate-create-order.php — Crea ordine e restituisce URL gateway
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/auth_guard.php';
require_once dirname(__DIR__) . '/core81/paygate_service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_err('Metodo non consentito', 405);
$user = auth_guard(['MEMBER81', 'NETWORKER81', 'ELITE81', 'ADMIN81']);
csrf_check();

$importo  = round((float)($_POST['importo'] ?? 0), 2);
$pack_id  = trim($_POST['pack_id'] ?? '');
$provider = strtoupper(trim($_POST['provider'] ?? ''));

// Validazione importo
if ($importo < PaygateService::MIN_IMPORTO || $importo > PaygateService::MAX_IMPORTO) {
    json_err('Importo non valido (min €' . PaygateService::MIN_IMPORTO . ')');
}

// Validazione gateway
if (!in_array($provider, ['PAYPAL', 'REVOLUT', 'CRYPTO', 'BONIFICO'], true)) {
    json_err('Metodo di pagamento non supportato');
}

// Calcola PV e PV+ bonus
$calc   = PaygateService::calcPV($importo, $pack_id ?: null);
$pv     = $calc['pv'];
$pvplus = $calc['pvplus'];

// Descrizione ordine
$pack_label = isset(PaygateService::PACKS[$pack_id])
    ? PaygateService::PACKS[$pack_id]['label']
    : number_format($importo, 2, ',', '.') . ' PV liberi';
$descrizione = '81+ — ' . $pack_label;

try {
    $order = PaygateService::createOrder(
        (int)$user['id'], $importo, $pv, $pvplus, $provider
    );

    $order_code = $order['order_code'];
    $db         = DB::get();

    switch ($provider) {

        /* ── PayPal ──────────────────────────────────────────────── */
        case 'PAYPAL':
            $pp = PaygateService::paypalCreateOrder($importo, $order_code, $descrizione);

            $db->prepare(
                'INSERT INTO pagamenti_paypal
                 (user_id, paypal_order_id, importo_euro, tipo, pv_da_erogare, pvplus_da_erogare, status)
                 VALUES (?, ?, ?, "PV_PACK", ?, ?, "PENDING")'
            )->execute([$user['id'], $pp['paypal_order_id'], $importo, $pv, $pvplus]);

            // Collega ref id all'ordine
            $db->prepare(
                'UPDATE paygate_orders SET metodo_ref_id = LAST_INSERT_ID(), riferimento_ext = ?
                 WHERE order_code = ?'
            )->execute([$pp['paypal_order_id'], $order_code]);

            json_ok(['redirect' => $pp['approve_url'], 'order_code' => $order_code]);

        /* ── Revolut ─────────────────────────────────────────────── */
        case 'REVOLUT':
            $rv = PaygateService::revolutCreateOrder($importo, $order_code, $descrizione);

            $db->prepare(
                'INSERT INTO pagamenti_revolut
                 (user_id, revolut_order_id, importo_euro, tipo, pv_da_erogare, pvplus_da_erogare, status)
                 VALUES (?, ?, ?, "PV_PACK", ?, ?, "PENDING")'
            )->execute([$user['id'], $rv['revolut_order_id'], $importo, $pv, $pvplus]);

            $db->prepare(
                'UPDATE paygate_orders SET metodo_ref_id = LAST_INSERT_ID(), riferimento_ext = ?
                 WHERE order_code = ?'
            )->execute([$rv['revolut_order_id'], $order_code]);

            json_ok(['redirect' => $rv['checkout_url'], 'order_code' => $order_code]);

        /* ── Crypto USDT ─────────────────────────────────────────── */
        case 'CRYPTO':
            $chain   = strtoupper(trim($_POST['chain'] ?? 'BSC'));
            $addrs   = PaygateService::cryptoAddresses();
            if (!isset($addrs[$chain]) || !$addrs[$chain]['address']) {
                json_err('Rete cripto non disponibile');
            }

            $db->prepare(
                'INSERT INTO pagamenti_crypto
                 (user_id, rete, token, wallet_ricevente, importo_token, importo_euro_equiv,
                  tipo, pv_da_erogare, pvplus_da_erogare, status)
                 VALUES (?, ?, "USDT", ?, ?, ?, "PV_PACK", ?, ?, "PENDING")'
            )->execute([
                $user['id'], $chain, $addrs[$chain]['address'],
                $importo, $importo, $pv, $pvplus
            ]);

            $crypto_id = (int)$db->lastInsertId();
            $db->prepare(
                'UPDATE paygate_orders SET metodo_ref_id = ? WHERE order_code = ?'
            )->execute([$crypto_id, $order_code]);

            json_ok([
                'redirect'   => BASE_URL . '/paygate81.php?step=crypto&order=' . urlencode($order_code),
                'order_code' => $order_code,
            ]);

        /* ── Bonifico IBAN ───────────────────────────────────────── */
        case 'BONIFICO':
            $causale = '81+ PV ' . $order_code;
            $db->prepare(
                'INSERT INTO pagamenti_bonifico
                 (user_id, causale, importo_euro, tipo, pv_da_erogare)
                 VALUES (?, ?, ?, "PV_PACK", ?)'
            )->execute([$user['id'], $causale, $importo, $pv]);

            $bon_id = (int)$db->lastInsertId();
            $db->prepare(
                'UPDATE paygate_orders SET metodo_ref_id = ? WHERE order_code = ?'
            )->execute([$bon_id, $order_code]);

            json_ok([
                'redirect'   => BASE_URL . '/paygate81.php?step=bonifico&order=' . urlencode($order_code),
                'order_code' => $order_code,
            ]);
    }

} catch (Throwable $e) {
    error_log('[paygate-create-order] ' . $e->getMessage());
    json_err('Errore creazione ordine. Riprova tra qualche minuto.');
}
