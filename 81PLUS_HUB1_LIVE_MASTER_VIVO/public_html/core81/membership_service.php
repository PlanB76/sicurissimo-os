<?php
// core81/membership_service.php — Attivazione e rinnovo membership
declare(strict_types=1);

class MembershipService {

    public static function activate(int $user_id, string $piano, bool $is_prima = false): array {
        $plans = MEMBERSHIP_PLANS;
        if (!isset($plans[$piano])) {
            throw new RuntimeException('Piano non valido: ' . $piano);
        }
        $plan   = $plans[$piano];
        $pv_req = (float)$plan['pv'];
        $db     = DB::get();

        // Controlla saldo
        $saldo_pv = WalletService::get_pv($user_id);
        if ($saldo_pv < $pv_req) {
            return ['ok' => false, 'error' => 'Saldo PV insufficiente. Necessari: ' . $pv_req . ' PV, hai: ' . number_format($saldo_pv, 2)];
        }

        $db->beginTransaction();
        try {
            // Scala PV
            WalletService::debit_pv($user_id, $pv_req, 'CONSUMO', 'Membership ' . $piano, 'membership_' . strtolower(str_replace('+', 'plus', $piano)));

            // Crea/aggiorna membership
            $ora  = date('Y-m-d');
            $fine = date('Y-m-d', strtotime('+1 month'));
            $pvplus_bonus = $is_prima ? (float)$plan['pvplus_prima'] : (float)$plan['pvplus_rinnovo'];

            $stmt = $db->prepare(
                'INSERT INTO memberships (user_id, piano, status, pv_mensile, pvplus_prima, pvplus_rinnovo, prima_attivazione, attiva_dal, scade_il)
                 VALUES (?, ?, "ACTIVE", ?, ?, ?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE status="ACTIVE", attiva_dal=?, scade_il=?, updated_at=NOW()'
            );
            $stmt->execute([$user_id, $piano, $pv_req, (float)$plan['pvplus_prima'], (float)$plan['pvplus_rinnovo'], $is_prima ? 1 : 0, $ora, $fine, $ora, $fine]);

            // Aggiorna ruolo utente se necessario
            $new_ruolo = match($piano) {
                'PRO+', 'ELITE+' => 'NETWORKER81',
                default           => null,
            };
            if ($new_ruolo) {
                $db->prepare('UPDATE users SET ruolo = ?, membership_tier = ?, networker_status = 1 WHERE id = ? AND ruolo NOT IN ("ELITE81","ADMIN81")')
                   ->execute([$new_ruolo, $piano, $user_id]);
            } else {
                $db->prepare('UPDATE users SET membership_tier = ? WHERE id = ?')->execute([$piano, $user_id]);
            }

            // Crea ordine paygate
            $order_ref = 'MBR-' . strtoupper(substr(str_replace(['+','-','0'], ['P','',''], $piano), 0, 6)) . '-' . time();
            $db->prepare(
                'INSERT INTO paygate_orders (user_id, tipo, importo_euro, pv_erogati, pvplus_erogati, metodo_pagamento, status, riferimento_ext)
                 VALUES (?, "MEMBERSHIP", ?, ?, ?, "INTERNO", "COMPLETED", ?)'
            )->execute([$user_id, $pv_req, $pv_req, $pvplus_bonus, $order_ref]);

            $db->commit();
        } catch (Throwable $e) {
            $db->rollBack();
            return ['ok' => false, 'error' => $e->getMessage()];
        }

        // Accredita PV+ bonus (idempotente)
        $rif = 'membership_' . $piano . '_' . ($is_prima ? 'prima' : 'rinnovo') . '_' . date('Ym');
        WalletService::credit_pvplus($user_id, $pvplus_bonus, 'MEMBERSHIP_ACTIVATION', $rif);

        // Aggiorna sessione
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id) {
            $_SESSION['membership'] = $piano;
        }

        return ['ok' => true, 'piano' => $piano, 'pvplus_bonus' => $pvplus_bonus];
    }

    public static function get_active(int $user_id): ?array {
        $stmt = DB::get()->prepare(
            'SELECT * FROM memberships WHERE user_id = ? AND status = "ACTIVE" ORDER BY created_at DESC LIMIT 1'
        );
        $stmt->execute([$user_id]);
        return $stmt->fetch() ?: null;
    }

    public static function is_prima_attivazione(int $user_id, string $piano): bool {
        $stmt = DB::get()->prepare(
            'SELECT COUNT(*) FROM memberships WHERE user_id = ? AND piano = ? AND prima_attivazione = 1 LIMIT 1'
        );
        $stmt->execute([$user_id, $piano]);
        return ((int)$stmt->fetchColumn()) === 0;
    }
}
