<?php
// core81/pvplus_booster_service.php — PV+ missioni e booster
declare(strict_types=1);
require_once __DIR__ . '/db.php';

class PVPlusBoosterService {
    // Caps per ruolo
    private const CAPS = [
        'MEMBER81'    => 2.0,
        'NETWORKER81' => 3.0,
        'ELITE81'     => 4.0,
        'ADMIN81'     => 4.0,
    ];

    public static function claimMission(int $uid, string $codice, ?string $riferimento = null): array {
        $db = DB::get();

        // Idempotenza: controlla se già claimato
        $check = $db->prepare('SELECT id, pvplus_finali FROM pvplus_claims 
                                WHERE user_id = ? AND codice_missione = ? AND riferimento <=> ?');
        $check->execute([$uid, $codice, $riferimento]);
        if ($existing = $check->fetch()) {
            return ['already_claimed' => true, 'pvplus' => (float)$existing['pvplus_finali']];
        }

        // Carica missione
        $mst = $db->prepare('SELECT * FROM pvplus_missions WHERE codice = ? AND attiva = 1');
        $mst->execute([$codice]);
        $mission = $mst->fetch();
        if (!$mission) return ['ok' => false, 'error' => 'Missione non trovata'];

        // Calcola moltiplicatore
        $user = $db->prepare('SELECT ruolo, genesys_status FROM users WHERE id = ?');
        $user->execute([$uid]);
        $u = $user->fetch();

        $booster = self::getActiveBooster($uid, $u['ruolo'], $u['genesys_status']);
        $cap     = self::CAPS[$u['ruolo']] ?? 2.0;
        $mult    = min($cap, $booster);
        $finale  = round((float)$mission['pvplus_base'] * $mult, 4);

        $db->beginTransaction();
        try {
            // Registra claim
            $db->prepare('INSERT INTO pvplus_claims 
                          (user_id, codice_missione, pvplus_base, moltiplicatore, pvplus_finali, cap_applicato, riferimento)
                          VALUES (?,?,?,?,?,?,?)')
               ->execute([$uid, $codice, $mission['pvplus_base'], $mult, $finale, $cap, $riferimento]);

            // Aggiorna wallet — idempotente lato DB (INSERT già verificato sopra)
            $db->prepare('UPDATE wallets SET pvplus_balance = pvplus_balance + ?,
                                             pvplus_career_total = pvplus_career_total + ?
                          WHERE user_id = ?')
               ->execute([$finale, $finale, $uid]);

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        return ['claimed' => true, 'pvplus' => $finale, 'moltiplicatore' => $mult];
    }

    private static function getActiveBooster(int $uid, string $ruolo, string $genesys): float {
        $db   = DB::get();
        $stmt = $db->prepare("SELECT MAX(moltiplicatore) as max_boost
                              FROM pvplus_boosters
                              WHERE user_id = ? AND attivo = 1 
                                AND (valido_fino IS NULL OR valido_fino > NOW())");
        $stmt->execute([$uid]);
        $boost = (float)($stmt->fetchColumn() ?? 1.0);
        return max(1.0, $boost);
    }

    public static function getMissionsForUser(array $user, ?string $categoria): array {
        $db  = DB::get();
        $sql = 'SELECT m.*, 
                       CASE WHEN c.id IS NOT NULL THEN 1 ELSE 0 END as completata
                FROM pvplus_missions m
                LEFT JOIN pvplus_claims c ON m.codice = c.codice_missione AND c.user_id = ?
                WHERE m.attiva = 1 AND m.ruolo_minimo <= ?';
        $params = [$user['id'], $user['ruolo']];
        if ($categoria) { $sql .= ' AND m.categoria = ?'; $params[] = $categoria; }
        $sql .= ' ORDER BY m.pvplus_base DESC';
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
