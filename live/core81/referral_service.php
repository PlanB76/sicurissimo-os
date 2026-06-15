<?php
// core81/referral_service.php — gestione referral link
declare(strict_types=1);
require_once __DIR__ . '/db.php';

class ReferralService {
    public static function getOrCreate(int $uid, string $sic_id): array {
        $db   = DB::get();
        $stmt = $db->prepare('SELECT * FROM referral_links WHERE user_id = ?');
        $stmt->execute([$uid]);
        $row  = $stmt->fetch();

        if (!$row) {
            $db->prepare('INSERT IGNORE INTO referral_links (user_id, sic_id_ref) VALUES (?,?)')
               ->execute([$uid, $sic_id]);
            $stmt->execute([$uid]);
            $row = $stmt->fetch();
        }

        return [
            'link'     => 'https://81plus.net/signup.php?ref=' . $row['sic_id_ref'],
            'sic_ref'  => $row['sic_id_ref'],
            'clicks'   => $row['click_count'],
            'signups'  => $row['signup_count'],
            'conversions' => $row['conversion_count'],
            'pvplus_earned' => $row['pvplus_earned'],
        ];
    }

    public static function trackClick(string $sic_ref): void {
        DB::get()->prepare('UPDATE referral_links SET click_count = click_count + 1 WHERE sic_id_ref = ?')
                 ->execute([$sic_ref]);
    }

    public static function trackConversion(string $sic_ref, int $referred_id, string $tipo): void {
        $db   = DB::get();
        $stmt = $db->prepare('SELECT user_id FROM referral_links WHERE sic_id_ref = ?');
        $stmt->execute([$sic_ref]);
        $row  = $stmt->fetch();
        if (!$row) return;

        $referrer_id = $row['user_id'];

        $db->prepare('UPDATE referral_links SET conversion_count = conversion_count + 1 WHERE sic_id_ref = ?')
           ->execute([$sic_ref]);

        $db->prepare('INSERT IGNORE INTO referral_conversions (referrer_id, referred_id, tipo) VALUES (?,?,?)')
           ->execute([$referrer_id, $referred_id, $tipo]);
    }
}
