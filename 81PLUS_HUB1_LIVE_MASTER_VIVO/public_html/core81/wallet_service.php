<?php
// core81/wallet_service.php — Operazioni wallet 81+
declare(strict_types=1);

class WalletService {

    // Accredita PV (non PV+)
    public static function credit_pv(int $user_id, float $amount, string $tipo, string $nota = '', string $rif = ''): void {
        if ($amount <= 0) return;
        $db = DB::get();
        $db->beginTransaction();
        try {
            $db->prepare('UPDATE wallets SET pv_balance = pv_balance + ? WHERE user_id = ?')
               ->execute([$amount, $user_id]);
            $saldo = self::get_pv($user_id);
            $db->prepare(
                'INSERT INTO pv_transactions (user_id, tipo, importo, saldo_dopo, valuta, riferimento, nota)
                 VALUES (?, ?, ?, ?, "PV", ?, ?)'
            )->execute([$user_id, $tipo, $amount, $saldo, $rif, $nota]);
            $db->commit();
        } catch (Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    // Scala PV (controlla saldo)
    public static function debit_pv(int $user_id, float $amount, string $tipo, string $nota = '', string $rif = ''): void {
        if ($amount <= 0) return;
        $db = DB::get();
        $db->beginTransaction();
        try {
            $rows = $db->prepare('UPDATE wallets SET pv_balance = pv_balance - ? WHERE user_id = ? AND pv_balance >= ?')
                       ->execute([$amount, $user_id, $amount]);
            if ($db->query("SELECT ROW_COUNT()")->fetchColumn() < 1) {
                $db->rollBack();
                throw new RuntimeException('Saldo PV insufficiente');
            }
            $saldo = self::get_pv($user_id);
            $db->prepare(
                'INSERT INTO pv_transactions (user_id, tipo, importo, saldo_dopo, valuta, riferimento, nota)
                 VALUES (?, ?, ?, ?, "PV", ?, ?)'
            )->execute([$user_id, $tipo, -$amount, $saldo, $rif, $nota]);
            $db->commit();
        } catch (Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    // Accredita PV+ (idempotente via rif unico)
    public static function credit_pvplus(int $user_id, float $amount, string $missione, string $rif): bool {
        if ($amount <= 0) return false;
        $db = DB::get();
        // Controlla idempotenza
        $existing = $db->prepare(
            'SELECT id FROM pvplus_claims WHERE user_id = ? AND codice_missione = ? AND riferimento = ? LIMIT 1'
        );
        $existing->execute([$user_id, $missione, $rif]);
        if ($existing->fetch()) return false; // gia accreditato

        $db->beginTransaction();
        try {
            $db->prepare('UPDATE wallets SET pvplus_balance = pvplus_balance + ?, pvplus_career_total = pvplus_career_total + ? WHERE user_id = ?')
               ->execute([$amount, $amount, $user_id]);
            $saldo = self::get_pvplus($user_id);
            $db->prepare(
                'INSERT INTO pv_transactions (user_id, tipo, importo, saldo_dopo, valuta, riferimento, nota)
                 VALUES (?, "BONUS", ?, ?, "PV+", ?, ?)'
            )->execute([$user_id, $amount, $saldo, $rif, $missione]);
            $db->prepare(
                'INSERT INTO pvplus_claims (user_id, codice_missione, riferimento, pvplus_assegnati)
                 VALUES (?, ?, ?, ?)'
            )->execute([$user_id, $missione, $rif, $amount]);
            $db->commit();
            return true;
        } catch (Throwable $e) {
            $db->rollBack();
            return false;
        }
    }

    public static function get_pv(int $user_id): float {
        $row = DB::get()->prepare('SELECT pv_balance FROM wallets WHERE user_id = ?');
        $row->execute([$user_id]);
        return (float)($row->fetchColumn() ?? 0);
    }

    public static function get_pvplus(int $user_id): float {
        $row = DB::get()->prepare('SELECT pvplus_balance FROM wallets WHERE user_id = ?');
        $row->execute([$user_id]);
        return (float)($row->fetchColumn() ?? 0);
    }

    public static function get_all(int $user_id): array {
        $stmt = DB::get()->prepare('SELECT * FROM wallets WHERE user_id = ?');
        $stmt->execute([$user_id]);
        return $stmt->fetch() ?: ['pv_balance'=>0,'pvplus_balance'=>0,'saf_balance'=>0,'x81_balance'=>0,'usdt_balance'=>0];
    }
}
