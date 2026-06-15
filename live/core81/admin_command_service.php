<?php
// core81/admin_command_service.php — Admin Command Center81+
declare(strict_types=1);
require_once __DIR__ . '/db.php';

class AdminCommandService {
    private const AZIONI_CONSENTITE = [
        'attivare_utente','sospendere_utente','disattivare_utente',
        'cambiare_ruolo','cambiare_genesys_level',
        'attivare_club','disattivare_club',
        'attivare_franchising','disattivare_franchising',
        'bloccare_wallet','bloccare_referral','bloccare_plp',
        'bloccare_scout81','bloccare_dashboard',
        'nota_interna','ripristinare_utente',
        'modificare_regole_scout81',
        'abilitare_provider','disabilitare_provider',
        'abilitare_pack_plp','disabilitare_pack_plp',
        'riassegnare_lead','revocare_lead',
        'auditare_networker',
    ];

    public static function execute(int $admin_id, string $azione, ?int $target_id, array $params, string $note, ?string $ip, ?string $ua): array {
        if (!in_array($azione, self::AZIONI_CONSENTITE, true)) {
            throw new \InvalidArgumentException("Azione non consentita: $azione");
        }

        $db = DB::get();

        // Log obbligatorio PRIMA dell'azione
        $log = $db->prepare('INSERT INTO admin_action_log (admin_id, target_user_id, azione, parametri, note, ip_address, user_agent)
                              VALUES (?,?,?,?,?,?,?)');
        $log->execute([$admin_id, $target_id, $azione, json_encode($params), $note, $ip, $ua]);

        // Esegui azione
        $result = match($azione) {
            'attivare_utente'   => self::setStatus($target_id, 'ACTIVE'),
            'sospendere_utente' => self::setStatus($target_id, 'SUSPENDED'),
            'disattivare_utente'=> self::setStatus($target_id, 'INACTIVE'),
            'cambiare_ruolo'    => self::setRuolo($target_id, $params['ruolo'] ?? ''),
            'cambiare_genesys_level' => self::setGenesys($target_id, $params['genesys_status'] ?? ''),
            'nota_interna'      => self::setNota($target_id, $note),
            default             => ['done' => true],
        };

        return $result;
    }

    private static function setStatus(?int $uid, string $status): array {
        if (!$uid) throw new \InvalidArgumentException('target_user_id obbligatorio');
        DB::get()->prepare('UPDATE users SET status = ? WHERE id = ?')->execute([$status, $uid]);
        return ['status' => $status];
    }

    private static function setRuolo(?int $uid, string $ruolo): array {
        $ok = ['MEMBER81','NETWORKER81','ELITE81','ADMIN81'];
        if (!in_array($ruolo, $ok, true)) throw new \InvalidArgumentException('Ruolo non valido');
        DB::get()->prepare('UPDATE users SET ruolo = ? WHERE id = ?')->execute([$ruolo, $uid]);
        return ['ruolo' => $ruolo];
    }

    private static function setGenesys(?int $uid, string $gs): array {
        $ok = ['NONE','GENESYS_MEMBER','GENESYS_NETWORKER','GENESYS_LEADER','GENESYS_FOUNDER'];
        if (!in_array($gs, $ok, true)) throw new \InvalidArgumentException('Genesys status non valido');
        DB::get()->prepare('UPDATE users SET genesys_status = ? WHERE id = ?')->execute([$gs, $uid]);
        return ['genesys_status' => $gs];
    }

    private static function setNota(?int $uid, string $nota): array {
        if (!$uid) throw new \InvalidArgumentException('target_user_id obbligatorio');
        DB::get()->prepare('UPDATE users SET note_interne = ? WHERE id = ?')->execute([$nota, $uid]);
        return ['nota' => 'salvata'];
    }
}
