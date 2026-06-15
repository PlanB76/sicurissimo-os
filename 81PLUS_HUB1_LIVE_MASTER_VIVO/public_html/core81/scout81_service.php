<?php
// core81/scout81_service.php — SCOUT81+ engine
declare(strict_types=1);
require_once __DIR__ . '/db.php';

class Scout81Service {
    public static function getMapData(array $user, array $filtri): array {
        $db   = DB::get();
        $sql  = 'SELECT id, ragione_sociale, ateco, rischio, comune, provincia,
                        regione, score, haccp_applicabile, edilizia
                 FROM scout81_prospects WHERE 1=1';
        $params = [];

        if ($filtri['regione'] ?? null) {
            $sql .= ' AND regione = ?'; $params[] = $filtri['regione'];
        }
        if ($filtri['provincia'] ?? null) {
            $sql .= ' AND provincia = ?'; $params[] = $filtri['provincia'];
        }
        if ($filtri['ateco'] ?? null) {
            $sql .= ' AND ateco LIKE ?'; $params[] = $filtri['ateco'] . '%';
        }
        if ($filtri['rischio'] ?? null) {
            $sql .= ' AND rischio = ?'; $params[] = strtoupper($filtri['rischio']);
        }

        $sql .= ' LIMIT 500';
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getProspects(array $user, array $filtri, int $page, int $per): array {
        $db     = DB::get();
        $offset = ($page - 1) * $per;
        $sql    = 'SELECT p.*, a.status as assignment_status, a.networker_id
                   FROM scout81_prospects p
                   LEFT JOIN scout81_assignments a ON p.id = a.prospect_id AND a.networker_id = ?
                   WHERE 1=1';
        $params = [$user['id']];

        if ($filtri['provincia'] ?? null) {
            $sql .= ' AND p.provincia = ?'; $params[] = $filtri['provincia'];
        }
        if ($filtri['rischio'] ?? null) {
            $sql .= ' AND p.rischio = ?'; $params[] = strtoupper($filtri['rischio']);
        }

        $countSql = str_replace('SELECT p.*, a.status as assignment_status, a.networker_id', 'SELECT COUNT(*)', $sql);
        $countStmt = $db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $sql .= " ORDER BY p.score DESC LIMIT $per OFFSET $offset";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return ['items' => $stmt->fetchAll(), 'total' => $total, 'page' => $page, 'per_page' => $per];
    }

    public static function getProspectCard(array $user, int $pid): ?array {
        $db   = DB::get();
        $stmt = $db->prepare('SELECT p.*, a.status as assignment_status, a.note, a.prossimo_followup
                              FROM scout81_prospects p
                              LEFT JOIN scout81_assignments a ON p.id = a.prospect_id AND a.networker_id = ?
                              WHERE p.id = ?');
        $stmt->execute([$user['id'], $pid]);
        return $stmt->fetch() ?: null;
    }

    public static function saveFilter(int $uid, string $nome, array $filtri): int {
        $db   = DB::get();
        $stmt = $db->prepare('INSERT INTO scout81_saved_filters (user_id, nome, filtri) VALUES (?,?,?)');
        $stmt->execute([$uid, $nome, json_encode($filtri)]);
        return (int)$db->lastInsertId();
    }

    public static function assignProspect(int $pid, int $nid): int {
        $db   = DB::get();
        $stmt = $db->prepare('INSERT IGNORE INTO scout81_assignments (prospect_id, networker_id) VALUES (?,?)');
        $stmt->execute([$pid, $nid]);
        return (int)$db->lastInsertId();
    }
}
