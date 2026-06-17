<?php
// core81/paygate_service.php — Motore centrale PayGate81+
// PV accredito SERVER SIDE ONLY. Mai via JS client-side.
declare(strict_types=1);

class PaygateService {

    const PV_RATE     = 1.00;   // 1 PV = €1.00
    const MIN_IMPORTO = 5.00;
    const MAX_IMPORTO = 5000.00;

    // Pack predefiniti [pv, price, bonus_pvplus, label, popular]
    const PACKS = [
        'pack_30'  => ['pv' => 30,  'price' => 30.00,  'pvplus' => 3,  'label' => '30 PV',  'popular' => false],
        'pack_60'  => ['pv' => 60,  'price' => 60.00,  'pvplus' => 6,  'label' => '60 PV',  'popular' => true],
        'pack_100' => ['pv' => 100, 'price' => 100.00, 'pvplus' => 12, 'label' => '100 PV', 'popular' => false],
        'pack_250' => ['pv' => 250, 'price' => 250.00, 'pvplus' => 30, 'label' => '250 PV', 'popular' => false],
    ];

    const GATEWAYS = ['PAYPAL', 'REVOLUT', 'CRYPTO', 'BONIFICO'];

    /* ── Genera order code ──────────────────────────────────────── */
    public static function generateOrderCode(): string {
        return 'PV-' . strtoupper(bin2hex(random_bytes(5)));
    }

    /* ── Crea ordine pendente ────────────────────────────────────── */
    public static function createOrder(
        int $user_id,
        float $importo,
        float $pv_amount,
        float $pvplus_bonus,
        string $metodo,
        string $tipo = 'PV_PACK'
    ): array {
        $db         = DB::get();
        $order_code = self::generateOrderCode();

        // Crea wallet se trigger non è ancora scattato
        $db->prepare('INSERT IGNORE INTO wallets (user_id) VALUES (?)')->execute([$user_id]);

        $db->prepare(
            'INSERT INTO paygate_orders
             (user_id, order_code, tipo, importo_euro, pv_erogati, pvplus_erogati, metodo, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, "PENDING")'
        )->execute([$user_id, $order_code, $tipo, $importo, $pv_amount, $pvplus_bonus, $metodo]);

        return [
            'id'           => (int)$db->lastInsertId(),
            'order_code'   => $order_code,
            'importo'      => $importo,
            'pv_amount'    => $pv_amount,
            'pvplus_bonus' => $pvplus_bonus,
            'metodo'       => $metodo,
        ];
    }

    /* ── Completa ordine e accredita PV — IDEMPOTENTE ────────────── */
    public static function completeOrder(string $order_code, string $riferimento_ext = ''): bool {
        $db = DB::get();
        $db->beginTransaction();
        try {
            $stmt = $db->prepare(
                'SELECT id, user_id, pv_erogati, pvplus_erogati, status
                 FROM paygate_orders WHERE order_code = ? FOR UPDATE'
            );
            $stmt->execute([$order_code]);
            $order = $stmt->fetch();

            if (!$order) throw new RuntimeException('Ordine non trovato: ' . $order_code);

            // Idempotenza: già completato → ok silenzioso
            if ($order['status'] === 'COMPLETED') { $db->rollBack(); return true; }
            if ($order['status'] !== 'PENDING')
                throw new RuntimeException('Stato non valido: ' . $order['status']);

            $user_id = (int)$order['user_id'];
            $pv      = (float)$order['pv_erogati'];
            $pvplus  = (float)$order['pvplus_erogati'];

            // Accredita PV
            $db->prepare('UPDATE wallets SET pv_balance = pv_balance + ? WHERE user_id = ?')
               ->execute([$pv, $user_id]);

            $saldo = (float)$db->query(
                "SELECT pv_balance FROM wallets WHERE user_id = $user_id"
            )->fetchColumn();

            $db->prepare(
                'INSERT INTO pv_transactions
                 (user_id, tipo, importo, saldo_dopo, valuta, riferimento, nota)
                 VALUES (?, "ACQUISTO", ?, ?, "PV", ?, "PayGate81+ accredito automatico")'
            )->execute([$user_id, $pv, $saldo, $order_code]);

            // Accredita PV+ bonus (idempotente via pvplus_claims)
            if ($pvplus > 0) {
                $exists = $db->prepare(
                    'SELECT id FROM pvplus_claims
                     WHERE user_id = ? AND codice_missione = "PAYGATE_BONUS" AND riferimento = ?'
                );
                $exists->execute([$user_id, $order_code]);
                if (!$exists->fetch()) {
                    $db->prepare(
                        'UPDATE wallets
                         SET pvplus_balance = pvplus_balance + ?,
                             pvplus_career_total = pvplus_career_total + ?
                         WHERE user_id = ?'
                    )->execute([$pvplus, $pvplus, $user_id]);
                    $db->prepare(
                        'INSERT INTO pvplus_claims (user_id, codice_missione, riferimento, pvplus_assegnati)
                         VALUES (?, "PAYGATE_BONUS", ?, ?)'
                    )->execute([$user_id, $order_code, $pvplus]);
                }
            }

            // Aggiorna ordine → COMPLETED
            $db->prepare(
                'UPDATE paygate_orders
                 SET status = "COMPLETED", riferimento_ext = ?, updated_at = NOW()
                 WHERE order_code = ?'
            )->execute([$riferimento_ext ?: null, $order_code]);

            $db->commit();
            return true;

        } catch (Throwable $e) {
            $db->rollBack();
            error_log('[PaygateService.completeOrder] ' . $e->getMessage());
            return false;
        }
    }

    /* ── Calcola PV e PV+ da importo + pack opzionale ────────────── */
    public static function calcPV(float $importo, ?string $pack_id = null): array {
        $pvplus = 0.0;
        if ($pack_id && isset(self::PACKS[$pack_id])) {
            $pvplus = (float)self::PACKS[$pack_id]['pvplus'];
        }
        return ['pv' => $importo, 'pvplus' => $pvplus];
    }

    /* ── Leggi ordine per order_code (verificato per user_id) ───── */
    public static function getOrder(string $order_code, ?int $user_id = null): ?array {
        $db   = DB::get();
        $sql  = 'SELECT * FROM paygate_orders WHERE order_code = ?';
        $args = [$order_code];
        if ($user_id !== null) { $sql .= ' AND user_id = ?'; $args[] = $user_id; }
        $stmt = $db->prepare($sql);
        $stmt->execute($args);
        return $stmt->fetch() ?: null;
    }

    /* ── PayPal: ottieni access token ───────────────────────────── */
    public static function paypalGetToken(): string {
        $mode = $_ENV['PAYPAL_MODE'] ?? 'sandbox';
        $base = $mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $ch = curl_init($base . '/v1/oauth2/token');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => 'grant_type=client_credentials',
            CURLOPT_USERPWD        => ($_ENV['PAYPAL_CLIENT_ID'] ?? '') . ':' . ($_ENV['PAYPAL_CLIENT_SECRET'] ?? ''),
            CURLOPT_HTTPHEADER     => ['Accept: application/json', 'Accept-Language: it_IT'],
            CURLOPT_TIMEOUT        => 15,
        ]);
        $res  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code !== 200) throw new RuntimeException('PayPal token error HTTP ' . $code);
        $data = json_decode($res, true);
        return $data['access_token'] ?? throw new RuntimeException('PayPal: access_token mancante');
    }

    /* ── PayPal: crea ordine ────────────────────────────────────── */
    public static function paypalCreateOrder(float $importo, string $order_code, string $descrizione): array {
        $mode  = $_ENV['PAYPAL_MODE'] ?? 'sandbox';
        $base  = $mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
        $token = self::paypalGetToken();

        $payload = json_encode([
            'intent'              => 'CAPTURE',
            'purchase_units'      => [[
                'amount'      => ['currency_code' => 'EUR', 'value' => number_format($importo, 2, '.', '')],
                'description' => $descrizione,
                'custom_id'   => $order_code,
            ]],
            'application_context' => [
                'brand_name'  => '81+ OS',
                'locale'      => 'it-IT',
                'user_action' => 'PAY_NOW',
                'return_url'  => (BASE_URL . '/api/paypal-capture.php'),
                'cancel_url'  => (BASE_URL . '/paygate81.php?error=annullato'),
            ],
        ]);

        $ch = curl_init($base . '/v2/checkout/orders');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
                'PayPal-Request-Id: ' . $order_code,
            ],
            CURLOPT_TIMEOUT => 20,
        ]);
        $res  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code !== 201) throw new RuntimeException('PayPal createOrder HTTP ' . $code . ': ' . $res);
        $data = json_decode($res, true);

        $approve = '';
        foreach (($data['links'] ?? []) as $link) {
            if ($link['rel'] === 'approve') { $approve = $link['href']; break; }
        }
        if (!$approve) throw new RuntimeException('PayPal: approve link mancante');

        return ['paypal_order_id' => $data['id'], 'approve_url' => $approve];
    }

    /* ── PayPal: cattura pagamento ──────────────────────────────── */
    public static function paypalCapture(string $paypal_order_id): array {
        $mode  = $_ENV['PAYPAL_MODE'] ?? 'sandbox';
        $base  = $mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
        $token = self::paypalGetToken();

        $ch = curl_init("$base/v2/checkout/orders/$paypal_order_id/capture");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => '{}',
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ],
            CURLOPT_TIMEOUT => 20,
        ]);
        $res  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code !== 201) throw new RuntimeException('PayPal capture HTTP ' . $code . ': ' . $res);
        return json_decode($res, true);
    }

    /* ── Revolut: crea ordine ───────────────────────────────────── */
    public static function revolutCreateOrder(float $importo, string $order_code, string $descrizione): array {
        $mode = $_ENV['REVOLUT_MODE'] ?? 'sandbox';
        $base = $mode === 'live'
            ? 'https://merchant.revolut.com'
            : 'https://sandbox-merchant.revolut.com';

        $payload = json_encode([
            'amount'                  => (int)round($importo * 100), // centesimi
            'currency'                => 'EUR',
            'description'             => $descrizione,
            'merchant_order_ext_ref'  => $order_code,
        ]);

        $ch = curl_init($base . '/api/orders');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . ($_ENV['REVOLUT_API_KEY'] ?? ''),
                'Revolut-Api-Version: 2024-09-01',
            ],
            CURLOPT_TIMEOUT => 20,
        ]);
        $res  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code !== 201) throw new RuntimeException('Revolut createOrder HTTP ' . $code . ': ' . $res);
        $data = json_decode($res, true);

        $checkout_url = $data['checkout_url'] ?? ('https://checkout.revolut.com/pay/' . ($data['public_id'] ?? ''));
        return ['revolut_order_id' => $data['id'], 'checkout_url' => $checkout_url];
    }

    /* ── Revolut: verifica firma webhook ────────────────────────── */
    public static function revolutVerifyWebhook(string $body, string $signature_header): bool {
        // Header format: "t=1620000000,v1=hex_signature"
        $parts = [];
        foreach (explode(',', $signature_header) as $part) {
            [$k, $v] = array_pad(explode('=', $part, 2), 2, '');
            $parts[$k] = $v;
        }
        $timestamp = $parts['t'] ?? '';
        $sig       = $parts['v1'] ?? '';
        if (!$timestamp || !$sig) return false;

        $secret   = $_ENV['REVOLUT_WEBHOOK_SECRET'] ?? '';
        $expected = hash_hmac('sha256', "$timestamp.$body", $secret);
        return hash_equals($expected, $sig);
    }

    /* ── PayPal: verifica firma webhook ─────────────────────────── */
    public static function paypalVerifyWebhook(array $headers, string $body): bool {
        $webhook_id = $_ENV['PAYPAL_WEBHOOK_ID'] ?? '';
        if (!$webhook_id) return true; // skip se non configurato

        $mode  = $_ENV['PAYPAL_MODE'] ?? 'sandbox';
        $base  = $mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
        try {
            $token   = self::paypalGetToken();
            $payload = json_encode([
                'auth_algo'       => $headers['PAYPAL-AUTH-ALGO'] ?? '',
                'cert_url'        => $headers['PAYPAL-CERT-URL'] ?? '',
                'transmission_id' => $headers['PAYPAL-TRANSMISSION-ID'] ?? '',
                'transmission_sig'=> $headers['PAYPAL-TRANSMISSION-SIG'] ?? '',
                'transmission_time'=> $headers['PAYPAL-TRANSMISSION-TIME'] ?? '',
                'webhook_id'      => $webhook_id,
                'webhook_event'   => json_decode($body, true),
            ]);
            $ch = curl_init($base . '/v1/notifications/verify-webhook-signature');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $payload,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token,
                ],
                CURLOPT_TIMEOUT => 15,
            ]);
            $res  = curl_exec($ch);
            curl_close($ch);
            $data = json_decode($res, true);
            return ($data['verification_status'] ?? '') === 'SUCCESS';
        } catch (Throwable $e) {
            error_log('[PaygateService.paypalVerify] ' . $e->getMessage());
            return false;
        }
    }

    /* ── Indirizzi crypto da .env ────────────────────────────────── */
    public static function cryptoAddresses(): array {
        return [
            'ETH' => ['address' => $_ENV['CRYPTO_ETH_ADDRESS'] ?? '', 'network' => 'ERC-20', 'label' => 'Ethereum',         'min_confirmations' => 12],
            'BSC' => ['address' => $_ENV['CRYPTO_BSC_ADDRESS'] ?? '', 'network' => 'BEP-20', 'label' => 'BNB Smart Chain',  'min_confirmations' => 15],
            'TRX' => ['address' => $_ENV['CRYPTO_TRX_ADDRESS'] ?? '', 'network' => 'TRC-20', 'label' => 'Tron',             'min_confirmations' => 20],
        ];
    }

    /* ── Dati bonifico da .env ───────────────────────────────────── */
    public static function bonificoData(string $order_code, float $importo): array {
        return [
            'iban'        => $_ENV['BONIFICO_IBAN'] ?? '',
            'bic'         => $_ENV['BONIFICO_BIC']  ?? '',
            'intestato_a' => $_ENV['BONIFICO_INTESTATO'] ?? '81+ OS',
            'banca'       => $_ENV['BONIFICO_BANCA'] ?? '',
            'causale'     => '81+ PV ' . $order_code,
            'importo'     => $importo,
        ];
    }
}
