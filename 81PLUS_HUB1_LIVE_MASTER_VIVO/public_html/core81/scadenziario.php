<?php
// core81/scadenziario.php — Scadenziario81+ service
declare(strict_types=1);
require_once __DIR__ . '/db.php';

class Scadenziario81 {
    public static function create(int $uid, string $cat, string $nome, string $data, string $note): int {
        $db = DB::get();
        $db->prepare('INSERT INTO scadenziario (user_id, categoria, nome, data_scadenza, note)
                      VALUES (?,?,?,?,?)')
           ->execute([$uid, $cat, $nome, $data, $note]);
        return (int)$db->lastInsertId();
    }

    public static function exportICS(int $uid, string $periodo): string {
        $db   = DB::get();
        $stmt = $db->prepare('SELECT * FROM scadenziario WHERE user_id = ? AND completato = 0 ORDER BY data_scadenza');
        $stmt->execute([$uid]);
        $items = $stmt->fetchAll();

        $ics = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//81plus.net//Scadenziario81+//IT
";
        foreach ($items as $item) {
            $dt  = str_replace('-', '', $item['data_scadenza']);
            $uid_ev = 'scad-' . $item['id'] . '@81plus.net';
            $ics .= "BEGIN:VEVENT
"
                  . "UID:$uid_ev
"
                  . "DTSTART;VALUE=DATE:$dt
"
                  . "SUMMARY:" . addcslashes($item['nome'], ',;\') . "
"
                  . "DESCRIPTION:Categoria: " . $item['categoria'] . "
"
                  . "END:VEVENT
";
        }
        $ics .= "END:VCALENDAR
";
        return $ics;
    }
}
