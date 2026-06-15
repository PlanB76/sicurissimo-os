<?php
// api/paygate-create-order.php — Crea ordine acquisto PV
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/auth_guard.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_err('Metodo non consentito', 405);
$user = auth_guard(['MEMBER81','NETWORKER81','ELITE81','ADMIN81']);
csrf_check();

$amount   = (float)($_POST['amount'] ?? 0);
$provider = trim($_POST['provider'] ?? 'paypal');

if ($amount <= 0 || $amount > 10000) json_err('Importo non valido');
if (!in_array($provider, ['paypal','stripe','bonifico'], true)) json_err('Provider non supportato');

$db = DB::get();
$order_ref = 'PV-' . strtoupper(substr(md5($user['id'] . time()), 0, 8));

$stmt = $db->prepare(
    'INSERT INTO paygate_orders (user_id, tipo, importo_euro, pv_erogati, metodo_pagamento, status, riferimento_ext)
     VALUES (?, "PV_PACK", ?, ?, ?, "PENDING", ?)'
);
$provider_enum = match($provider) {
    'paypal'   => 'PAYPAL',
    'stripe'   => 'STRIPE',
    'bonifico' => 'BONIFICO',
    default    => 'PAYPAL',
};
$stmt->execute([$user['id'], $amount, $amount, $provider_enum, $order_ref]);
$order_id = (int)$db->lastInsertId();

// In produzione: qui si chiama PayPal/Stripe API per generare il redirect URL
// Per ora restituiamo dati dell'ordine per debug/staging
$redirect_url = match($provider) {
    'paypal'   => BASE_URL . '/paygate81.php?order=' . $order_ref . '&provider=paypal&step=checkout',
    'stripe'   => BASE_URL . '/paygate81.php?order=' . $order_ref . '&provider=stripe&step=checkout',
    'bonifico' => BASE_URL . '/paygate81.php?order=' . $order_ref . '&provider=bonifico&step=istruzioni',
    default    => BASE_URL . '/paygate81.php',
};

json_ok([
    'order_id'    => $order_id,
    'order_ref'   => $order_ref,
    'amount'      => $amount,
    'provider'    => $provider,
    'redirect'    => $redirect_url,
]);
