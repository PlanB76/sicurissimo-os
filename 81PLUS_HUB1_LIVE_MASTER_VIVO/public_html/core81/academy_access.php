<?php
// core81/academy_access.php — sblocco livelli Academy81+
declare(strict_types=1);
require_once __DIR__ . '/db.php';

class AcademyAccess {
    public static function checkAndUnlock(int $uid): void {
        $db  = DB::get();
        $usr = $db->prepare('SELECT ruolo FROM users WHERE id = ?');
        $usr->execute([$uid]);
        $u   = $usr->fetch();
        if (!$u) return;

        $wlt = $db->prepare('SELECT pvplus_career_total FROM wallets WHERE user_id = ?');
        $wlt->execute([$uid]);
        $w   = $wlt->fetch();
        $pvplus_tot = (float)($w['pvplus_career_total'] ?? 0);

        // Core: tutti
        self::unlock($uid, 'CORE', 'Accesso base');

        // Pro Skills: NETWORKER con 50k PV+ lifetime
        if (in_array($u['ruolo'], ['NETWORKER81','ELITE81','ADMIN81'], true) && $pvplus_tot >= 50000) {
            self::unlock($uid, 'PRO_SKILLS', 'Requisiti soddisfatti');
        }

        // Elite Mastery: ELITE con 100k PV+ lifetime
        if (in_array($u['ruolo'], ['ELITE81','ADMIN81'], true) && $pvplus_tot >= 100000) {
            self::unlock($uid, 'ELITE_MASTERY', 'Requisiti soddisfatti');
        }
    }

    private static function unlock(int $uid, string $livello, string $motivo): void {
        DB::get()->prepare('INSERT IGNORE INTO academy_access (user_id, livello, motivo) VALUES (?,?,?)')
                 ->execute([$uid, $livello, $motivo]);
    }

    public static function getLevels(int $uid): array {
        $db   = DB::get();
        $stmt = $db->prepare('SELECT livello FROM academy_access WHERE user_id = ?');
        $stmt->execute([$uid]);
        return array_column($stmt->fetchAll(), 'livello');
    }
}
