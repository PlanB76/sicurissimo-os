<?php
// core81/plp_pack_service.php — PLP81+ acquisto e gestione
declare(strict_types=1);
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/pvplus_booster_service.php';

class PlpPackService {
    public static function buyPack(int $uid, int $pack_id): array {
        $db = DB::get();

        $pack = $db->prepare('SELECT * FROM plp_packs_catalog WHERE id = ? AND attivo = 1');
        $pack->execute([$pack_id]);
        $p = $pack->fetch();
        if (!$p) throw new \RuntimeException('Pack non disponibile');

        $wallet = $db->prepare('SELECT pv_balance FROM wallets WHERE user_id = ?');
        $wallet->execute([$uid]);
        $w = $wallet->fetch();
        if (!$w || $w['pv_balance'] < $p['prezzo_pv']) {
            throw new \RuntimeException('PV insufficienti');
        }

        $db->beginTransaction();
        try {
            // Scala PV
            $db->prepare('UPDATE wallets SET pv_balance = pv_balance - ? WHERE user_id = ?')
               ->execute([$p['prezzo_pv'], $uid]);

            // Crea ordine
            $db->prepare('INSERT INTO plp_orders (user_id, pack_id, pv_scalati, pvplus_erogati, prospect_count)
                          VALUES (?,?,?,?,?)')
               ->execute([$uid, $pack_id, $p['prezzo_pv'], $p['pvplus_bonus'], $p['prospect_count']]);
            $order_id = (int)$db->lastInsertId();

            // TODO: assegna prospect reali dal pool
            $db->prepare("UPDATE plp_orders SET status = 'ASSEGNATO' WHERE id = ?")->execute([$order_id]);

            // PV+ bonus
            if ($p['pvplus_bonus'] > 0) {
                PVPlusBoosterService::claimMission($uid, 'PLP_FIRST_PACK', (string)$order_id);
            }

            $db->commit();
            return ['id' => $order_id, 'prospect_count' => $p['prospect_count']];
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function getNetworkerLeads(int $uid, ?string $status, int $page): array {
        $db     = DB::get();
        $offset = ($page - 1) * 20;
        $sql    = 'SELECT a.*, p.ragione_sociale, p.ateco, p.rischio, p.comune, p.provincia, p.score
                   FROM scout81_assignments a
                   JOIN scout81_prospects p ON a.prospect_id = p.id
                   WHERE a.networker_id = ?';
        $params = [$uid];
        if ($status) { $sql .= ' AND a.status = ?'; $params[] = $status; }
        $sql .= " ORDER BY a.updated_at DESC LIMIT 20 OFFSET $offset";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return ['items' => $stmt->fetchAll(), 'page' => $page];
    }
}
