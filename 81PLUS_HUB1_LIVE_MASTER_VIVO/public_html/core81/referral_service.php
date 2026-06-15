<?php
// core81/referral_service.php — Gestione referral SIC-ID
declare(strict_types=1);

class ReferralService {

    // Registra il click su un referral link
    public static function click(string $referrer_sic_id, string $ip): void {
        $db = DB::get();
        $stmt = $db->prepare('SELECT id FROM users WHERE sic_id = ? AND is_active = 1 LIMIT 1');
        $stmt->execute([$referrer_sic_id]);
        $referrer = $stmt->fetch();
        if (!$referrer) return;

        $db->prepare(
            'INSERT INTO referral_events (referrer_user_id, tipo, ip_hash) VALUES (?, "CLICK", ?)'
        )->execute([$referrer['id'], hash('sha256', $ip . 'SALT81')]);
    }

    // Al signup, salva il referrer e assegna PV+ se provenienza valida
    public static function on_signup(int $new_user_id, ?string $ref_sic_id): void {
        if (!$ref_sic_id) return;
        $db = DB::get();

        $stmt = $db->prepare('SELECT id FROM users WHERE sic_id = ? AND is_active = 1 LIMIT 1');
        $stmt->execute([$ref_sic_id]);
        $referrer = $stmt->fetch();
        if (!$referrer) return;

        $referrer_id = (int)$referrer['id'];

        // Salva evento SIGNUP
        $db->prepare(
            'INSERT INTO referral_events (referrer_user_id, referred_user_id, tipo) VALUES (?, ?, "SIGNUP")'
        )->execute([$referrer_id, $new_user_id]);

        // Aggiorna referral_ref sul nuovo utente
        $db->prepare('UPDATE users SET referral_ref = ? WHERE id = ?')->execute([$ref_sic_id, $new_user_id]);
    }

    // Statistiche referral per dashboard
    public static function stats(int $user_id): array {
        $db   = DB::get();
        $stmt = $db->prepare("
            SELECT
              SUM(tipo = 'CLICK')              AS click,
              SUM(tipo = 'SIGNUP')             AS signup,
              SUM(tipo = 'PROFILE_COMPLETE')   AS profili_completi,
              SUM(tipo = 'MEMBERSHIP_BASIC')   AS attivazioni_basic,
              SUM(tipo = 'MEMBERSHIP_PRO')     AS attivazioni_pro,
              SUM(tipo = 'MEMBERSHIP_ELITE')   AS attivazioni_elite,
              SUM(pvplus_awarded)              AS pvplus_totali
            FROM referral_events
            WHERE referrer_user_id = ?
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetch() ?: [];
    }

    // Recupera il link referral pubblico
    public static function link(string $sic_id): string {
        return BASE_URL . '/signup.php?ref=' . urlencode($sic_id);
    }
}
