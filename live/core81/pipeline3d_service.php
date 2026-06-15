<?php
// core81/pipeline3d_service.php — Pipeline3D81+ grafo rete
declare(strict_types=1);
require_once __DIR__ . '/db.php';

class Pipeline3DService {
    public static function getGraph(array $user, array $filtri): array {
        $db    = DB::get();
        $uid   = $user['id'];
        $ruolo = $user['ruolo'];

        // Admin vede tutto, altrimenti solo propria rete
        if ($ruolo === 'ADMIN81') {
            $stmt = $db->prepare('SELECT u.id, u.nome, u.cognome, u.sic_id, u.ruolo, u.status,
                                         u.genesys_status, r.upline_id
                                  FROM users u
                                  LEFT JOIN pipeline_relations r ON u.id = r.downline_id
                                  LIMIT 500');
            $stmt->execute();
        } else {
            // Carica solo discendenti diretti/indiretti
            $stmt = $db->prepare('SELECT u.id, u.nome, u.cognome, u.sic_id, u.ruolo, u.status,
                                         u.genesys_status, r.upline_id
                                  FROM pipeline_relations r
                                  JOIN users u ON r.downline_id = u.id
                                  WHERE r.upline_id = ?
                                  LIMIT 200');
            $stmt->execute([$uid]);
        }

        $rows  = $stmt->fetchAll();
        $nodes = [];
        $edges = [];

        foreach ($rows as $row) {
            $nodes[] = [
                'id'             => $row['id'],
                'label'          => $row['nome'] . ' ' . $row['cognome'],
                'sic_id'         => $row['sic_id'],
                'ruolo'          => $row['ruolo'],
                'status'         => $row['status'],
                'genesys_status' => $row['genesys_status'],
                'color'          => self::nodeColor($row['ruolo'], $row['status'], $row['genesys_status']),
            ];
            if ($row['upline_id']) {
                $edges[] = ['from' => $row['upline_id'], 'to' => $row['id']];
            }
        }

        return ['nodes' => $nodes, 'edges' => $edges];
    }

    public static function getUserCard(array $user, int $target_id): ?array {
        $db = DB::get();

        // Verifica accesso (Networker vede solo propria rete)
        if ($user['ruolo'] !== 'ADMIN81') {
            $check = $db->prepare('SELECT id FROM pipeline_relations WHERE downline_id = ? AND upline_id = ?');
            $check->execute([$target_id, $user['id']]);
            if (!$check->fetch()) return null;
        }

        $stmt = $db->prepare('SELECT u.id, u.nome, u.cognome, u.sic_id, u.email, u.ruolo,
                                      u.status, u.genesys_status, m.piano as membership
                              FROM users u
                              LEFT JOIN memberships m ON u.id = m.user_id AND m.status = "ACTIVE"
                              WHERE u.id = ?');
        $stmt->execute([$target_id]);
        return $stmt->fetch() ?: null;
    }

    private static function nodeColor(string $ruolo, string $status, string $genesys): string {
        if ($status === 'SUSPENDED') return '#4A0000';
        if ($status === 'INACTIVE')  return '#444';
        if (str_starts_with($genesys, 'GENESYS')) return '#00D9FF';
        return match($ruolo) {
            'ELITE81'     => '#FFD24A',
            'NETWORKER81' => '#E8501A',
            'ADMIN81'     => '#8A4AE8',
            default       => '#F4F4F7',
        };
    }
}
