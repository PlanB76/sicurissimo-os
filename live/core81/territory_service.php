<?php
// core81/territory_service.php — TerritoryMap81+
declare(strict_types=1);
require_once __DIR__ . '/db.php';

class TerritoryService {
    public static function getMapData(array $user, array $filtri): array {
        $db   = DB::get();
        $sql  = 'SELECT ta.*, u.nome, u.cognome, u.sic_id
                 FROM territory_areas ta
                 LEFT JOIN users u ON ta.titolare_id = u.id
                 WHERE 1=1';
        $params = [];

        if ($filtri['regione'] ?? null) {
            $sql .= ' AND ta.regione_ref = ?'; $params[] = $filtri['regione'];
        }
        if ($filtri['provincia'] ?? null) {
            $sql .= ' AND ta.provincia_ref = ?'; $params[] = $filtri['provincia'];
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getAreaCard(array $user, int $area_id): array {
        $db   = DB::get();
        $stmt = $db->prepare('SELECT ta.*, u.nome, u.cognome, u.sic_id, u.email
                              FROM territory_areas ta
                              LEFT JOIN users u ON ta.titolare_id = u.id
                              WHERE ta.id = ?');
        $stmt->execute([$area_id]);
        return $stmt->fetch() ?: [];
    }
}
