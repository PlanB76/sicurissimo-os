<?php
// core81/dashboard_modules.php — carica moduli dashboard per ruolo
declare(strict_types=1);
require_once __DIR__ . '/db.php';

class DashboardModules {
    public static function loadForUser(array $user): array {
        $db     = DB::get();
        $uid    = $user['id'];
        $ruolo  = $user['ruolo'];
        $gen    = $user['genesys_status'];

        // Wallet
        $wallet = $db->prepare('SELECT * FROM wallets WHERE user_id = ?');
        $wallet->execute([$uid]);
        $w = $wallet->fetch() ?? [];

        // Membership attiva
        $memb = $db->prepare("SELECT * FROM memberships WHERE user_id = ? AND status = 'ACTIVE' LIMIT 1");
        $memb->execute([$uid]);
        $m = $memb->fetch() ?? null;

        $modules = [
            'sic_id'         => $user['sic_id'],
            'ruolo'          => $ruolo,
            'genesys_status' => $gen,
            'wallet'         => $w,
            'membership'     => $m,
        ];

        if (in_array($ruolo, ['NETWORKER81','ELITE81','ADMIN81'], true)) {
            // Conteggio lead assegnati
            $leads = $db->prepare("SELECT COUNT(*) as tot FROM scout81_assignments WHERE networker_id = ? AND status NOT IN ('CONVERTITO','PERSO')");
            $leads->execute([$uid]);
            $modules['leads_attivi'] = (int)($leads->fetch()['tot'] ?? 0);
        }

        if (in_array($ruolo, ['ELITE81','ADMIN81'], true)) {
            $modules['franchise_status'] = self::getFranchiseStatus($uid);
        }

        return $modules;
    }

    private static function getFranchiseStatus(int $uid): string {
        $db = DB::get();
        $st = $db->prepare("SELECT status FROM franchising_applications WHERE user_id = ? LIMIT 1");
        $st->execute([$uid]);
        return $st->fetchColumn() ?: 'none';
    }
}
