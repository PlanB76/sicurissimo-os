<?php
// core81/asr2025_compliance_service.php — ASR 2025 Compliance Engine
// Accordo Stato-Regioni Rep. Atti n. 59/CSR del 17 aprile 2025
declare(strict_types=1);
require_once __DIR__ . '/db.php';

class ASR2025ComplianceService {

    // ─── ATECO macrosettore ────────────────────────────────────────────────────
    public static function macrosettore(string $ateco): string {
        $ateco = strtoupper(trim($ateco));
        if (!preg_match('/^([A-U])(\d{2})/', $ateco, $m)) return 'GENERICO';
        $letter = $m[1];
        $div    = (int)$m[2];
        return match(true) {
            $letter === 'A' && in_array($div, [1, 2], true) => 'AGRICOLTURA',
            $letter === 'A' && $div === 3                    => 'PESCA',
            $letter === 'C' && in_array($div, [19, 20], true)=> 'CHIMICO',
            $letter === 'F'                                   => 'COSTRUZIONI',
            $letter === 'I'                                   => 'RISTORAZIONE',
            default                                           => 'GENERICO',
        };
    }

    // ─── Corsi richiesti da audit ──────────────────────────────────────────────
    public static function corsi_richiesti(array $a): array {
        $macro = self::macrosettore($a['ateco'] ?? '');
        $corsi = [];

        // 1. Datore di Lavoro 16 ore — obbligatorio se DL presente e non formato
        if (!empty($a['ha_dl']) && empty($a['dl_formato'])) {
            $corsi[] = [
                'codice'          => 'DL_16H',
                'nome'            => 'Corso Datore di Lavoro — 16 ore',
                'ore'             => 16,
                'figura'          => 'Datore di Lavoro',
                'motivo'          => 'ASR 2025 — obbligatorio per tutti i Datori di Lavoro',
                'priorita_giorni' => 30,
                'stato'           => 'MANCANTE',
            ];
        }

        // 2. Modulo Cantieri 6 ore — solo se ATECO F o attività reale in cantiere
        if (!empty($a['ha_dl']) && ($macro === 'COSTRUZIONI' || !empty($a['ha_cantieri']))) {
            $corsi[] = [
                'codice'          => 'DL_CANTIERI_6H',
                'nome'            => 'Modulo Datore di Lavoro Cantieri — 6 ore',
                'ore'             => 6,
                'figura'          => 'Datore di Lavoro',
                'motivo'          => 'ASR 2025 — obbligatorio per DL con attività in cantieri temporanei o mobili',
                'priorita_giorni' => 30,
                'stato'           => 'MANCANTE',
            ];
        }

        // 3. DL-RSPP modulo comune 8 ore — solo se DL svolge ruolo RSPP
        if (!empty($a['ha_dl']) && !empty($a['dl_fa_rspp'])) {
            $corsi[] = [
                'codice'          => 'DL_RSPP_8H',
                'nome'            => 'Modulo Comune Datore di Lavoro RSPP — 8 ore',
                'ore'             => 8,
                'figura'          => 'Datore di Lavoro RSPP',
                'motivo'          => 'ASR 2025 — obbligatorio se il DL svolge direttamente i compiti SPP',
                'priorita_giorni' => 30,
                'stato'           => 'MANCANTE',
            ];
            // 4. Modulo integrativo ATECO (solo se DL-RSPP)
            $mod = self::modulo_integrativo_ateco($macro);
            if ($mod) $corsi[] = $mod;
        }

        // 5. Aggiornamento quinquennale DL (se formato ma scaduto >= 5 anni)
        $anni = (int)($a['dl_anni_formazione'] ?? 0);
        if (!empty($a['ha_dl']) && !empty($a['dl_formato']) && $anni >= 5) {
            $corsi[] = [
                'codice'          => 'DL_AGG_6H',
                'nome'            => 'Aggiornamento Datore di Lavoro — 6 ore (quinquennale)',
                'ore'             => 6,
                'figura'          => 'Datore di Lavoro',
                'motivo'          => 'ASR 2025 — aggiornamento obbligatorio ogni 5 anni',
                'priorita_giorni' => 7,
                'stato'           => 'IN_SCADENZA',
            ];
        }

        // 6. Aggiornamento DL-RSPP quinquennale
        if (!empty($a['dl_fa_rspp']) && !empty($a['dl_rspp_formato']) && $anni >= 5) {
            $corsi[] = [
                'codice'          => 'DL_RSPP_AGG_8H',
                'nome'            => 'Aggiornamento DL-RSPP — 8 ore (quinquennale)',
                'ore'             => 8,
                'figura'          => 'Datore di Lavoro RSPP',
                'motivo'          => 'ASR 2025 — aggiornamento quinquennale dal modulo comune',
                'priorita_giorni' => 7,
                'stato'           => 'IN_SCADENZA',
            ];
        }

        // 7. Formazione lavoratori (se mancante)
        if (empty($a['formazione_lav']) && (int)($a['num_lav'] ?? 0) > 0) {
            $ore_lav = self::ore_lavoratori($a['rischio'] ?? 'MEDIO');
            $corsi[] = [
                'codice'          => 'LAV_FORMAZIONE',
                'nome'            => 'Formazione Lavoratori — ' . $ore_lav . ' ore',
                'ore'             => $ore_lav,
                'figura'          => 'Lavoratori',
                'motivo'          => 'D.Lgs 81/08 art. 37 — formazione obbligatoria',
                'priorita_giorni' => 60,
                'stato'           => 'MANCANTE',
            ];
        }

        // 8. Antincendio
        if (empty($a['has_antincendio']) && (int)($a['num_lav'] ?? 0) > 0) {
            $corsi[] = [
                'codice'          => 'ANTINCENDIO',
                'nome'            => 'Formazione Addetti Antincendio',
                'ore'             => 4,
                'figura'          => 'Addetti Antincendio',
                'motivo'          => 'D.Lgs 81/08 — obbligo squadra di emergenza antincendio',
                'priorita_giorni' => 60,
                'stato'           => 'MANCANTE',
            ];
        }

        // 9. Primo Soccorso
        if (empty($a['has_ps']) && (int)($a['num_lav'] ?? 0) > 0) {
            $corsi[] = [
                'codice'          => 'PRIMO_SOCCORSO',
                'nome'            => 'Formazione Addetti Primo Soccorso',
                'ore'             => 12,
                'figura'          => 'Addetti Primo Soccorso',
                'motivo'          => 'D.Lgs 81/08 — obbligo squadra primo soccorso',
                'priorita_giorni' => 60,
                'stato'           => 'MANCANTE',
            ];
        }

        // 10. Attrezzature ex art. 73
        if (!empty($a['has_attrezzature'])) {
            $corsi[] = [
                'codice'          => 'ATTREZZATURE_ART73',
                'nome'            => 'Formazione Attrezzature ex art. 73 D.Lgs 81/08 — 4 ore',
                'ore'             => 4,
                'figura'          => 'Operatori attrezzature',
                'motivo'          => 'D.Lgs 81/08 art. 73 — formazione operatori attrezzature',
                'priorita_giorni' => 90,
                'stato'           => 'MANCANTE',
            ];
        }

        // 11. Ambienti confinati
        if (!empty($a['has_amb_conf'])) {
            $corsi[] = [
                'codice'          => 'AMBIENTI_CONFINATI',
                'nome'            => 'Formazione Lavori in Ambienti Confinati — 4 ore (pratica)',
                'ore'             => 4,
                'figura'          => 'Lavoratori ambienti confinati',
                'motivo'          => 'DPR 177/2011 — obbligo formazione ambienti confinati',
                'priorita_giorni' => 30,
                'stato'           => 'MANCANTE',
            ];
        }

        // 12. HACCP (solo ristorazione/food)
        if ($macro === 'RISTORAZIONE' && empty($a['haccp_ok'])) {
            $corsi[] = [
                'codice'          => 'HACCP',
                'nome'            => 'Formazione Igiene Alimentare e HACCP',
                'ore'             => 6,
                'figura'          => 'Manipolatori alimenti',
                'motivo'          => 'Reg. CE 852/2004 — formazione obbligatoria igiene alimentare',
                'priorita_giorni' => 30,
                'stato'           => 'MANCANTE',
            ];
        }

        return $corsi;
    }

    // ─── Calcolo rischio ──────────────────────────────────────────────────────
    public static function calcola_rischio(array $a): array {
        $macro = self::macrosettore($a['ateco'] ?? '');
        $score = 50;

        // Fattori che aumentano il rischio
        if (empty($a['dvr_presente']))   $score += 25;
        if (empty($a['formazione_lav'])) $score += 15;
        if (empty($a['has_antincendio'])) $score += 10;
        if (empty($a['has_ps']))         $score += 10;
        if (!empty($a['ha_cantieri']))   $score += 15;
        if (!empty($a['has_amb_conf']))  $score += 20;
        if (in_array($macro, ['COSTRUZIONI', 'CHIMICO', 'AGRICOLTURA'], true)) $score += 10;

        // Fattori che riducono il rischio
        if (!empty($a['dvr_presente']))   $score -= 20;
        if (!empty($a['formazione_lav'])) $score -= 10;
        if (!empty($a['scadenziario']))   $score -= 5;

        $score  = max(0, min(100, $score));
        $livello = match(true) {
            $score >= 70 => 'ALTO',
            $score >= 40 => 'MEDIO',
            default      => 'BASSO',
        };

        return ['score' => $score, 'livello' => $livello, 'macrosettore' => $macro];
    }

    // ─── Priorità corsi (7/30/90 giorni) ──────────────────────────────────────
    public static function priorita_corsi(array $corsi): array {
        $p7 = $p30 = $p90 = [];
        foreach ($corsi as $c) {
            $g = (int)($c['priorita_giorni'] ?? 90);
            if ($g <= 7)       $p7[]  = $c;
            elseif ($g <= 30)  $p30[] = $c;
            else               $p90[] = $c;
        }
        return ['urgenti_7gg' => $p7, 'priorita_30gg' => $p30, 'programmabili_90gg' => $p90];
    }

    // ─── Salva audit nel DB ────────────────────────────────────────────────────
    public static function salva_audit(int $user_id, string $sic_id, array $a, array $r): int {
        $db = DB::get();
        $macro = self::macrosettore($a['ateco'] ?? '');
        $db->prepare(
            'INSERT INTO company_compliance_asr2025
               (user_id, sic_id, ragione_sociale, ateco_code, macrosettore, num_lavoratori,
                has_soci, has_datore_lavoro, dl_formato, dl_fa_rspp, ha_cantieri,
                e_impresa_affid, has_dirigenti, has_preposti, formazione_lav,
                has_antincendio, has_primo_soccorso, has_attrezzature, has_ambienti_conf,
                dvr_presente, scadenziario_presente, rischio_livello, score, audit_completed_at)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())'
        )->execute([
            $user_id, $sic_id, $a['ragione_sociale'] ?? null,
            strtoupper($a['ateco'] ?? ''), $macro,
            (int)($a['num_lav'] ?? 1),
            (int)(!empty($a['ha_soci'])), (int)(!empty($a['ha_dl'])),
            (int)(!empty($a['dl_formato'])), (int)(!empty($a['dl_fa_rspp'])),
            (int)(!empty($a['ha_cantieri'])), (int)(!empty($a['e_impresa_affid'])),
            (int)(!empty($a['has_dirigenti'])), (int)(!empty($a['has_preposti'])),
            (int)(!empty($a['formazione_lav'])), (int)(!empty($a['has_antincendio'])),
            (int)(!empty($a['has_ps'])), (int)(!empty($a['has_attrezzature'])),
            (int)(!empty($a['has_amb_conf'])), (int)(!empty($a['dvr_presente'])),
            (int)(!empty($a['scadenziario'])), $r['livello'], $r['score'],
        ]);
        return (int)$db->lastInsertId();
    }

    // ─── Salva corsi nel ledger ────────────────────────────────────────────────
    public static function salva_corsi(int $user_id, int $audit_id, array $corsi): void {
        if (empty($corsi)) return;
        $db = DB::get();
        $stmt = $db->prepare(
            'INSERT INTO course_requirements_ledger
               (user_id, audit_id, codice_corso, nome_corso, ore_totali,
                figura_destinataria, motivo_requisito, stato, priorita_giorni)
             VALUES (?,?,?,?,?,?,?,?,?)'
        );
        foreach ($corsi as $c) {
            $stmt->execute([
                $user_id, $audit_id,
                $c['codice'], $c['nome'], $c['ore'],
                $c['figura'], $c['motivo'] ?? null,
                $c['stato'] ?? 'MANCANTE', $c['priorita_giorni'] ?? 90,
            ]);
        }
    }

    // ─── Stato compliance utente ───────────────────────────────────────────────
    public static function stato_compliance(int $user_id): array {
        $db   = DB::get();
        $last = $db->prepare(
            'SELECT * FROM company_compliance_asr2025 WHERE user_id = ? ORDER BY created_at DESC LIMIT 1'
        );
        $last->execute([$user_id]);
        $audit = $last->fetch();
        if (!$audit) return ['audit_presente' => false];

        $corsi = $db->prepare(
            'SELECT * FROM course_requirements_ledger WHERE user_id = ? AND audit_id = ? ORDER BY priorita_giorni ASC'
        );
        $corsi->execute([$user_id, $audit['id']]);
        $lista_corsi = $corsi->fetchAll();

        $mancanti   = array_filter($lista_corsi, fn($c) => $c['stato'] === 'MANCANTE');
        $in_scadenza = array_filter($lista_corsi, fn($c) => $c['stato'] === 'IN_SCADENZA');
        $completati = array_filter($lista_corsi, fn($c) => $c['stato'] === 'COMPLETATO');

        return [
            'audit_presente'  => true,
            'audit'           => $audit,
            'corsi'           => array_values($lista_corsi),
            'mancanti'        => count($mancanti),
            'in_scadenza'     => count($in_scadenza),
            'completati'      => count($completati),
            'totale'          => count($lista_corsi),
        ];
    }

    // ─── Helper: ore lavoratori per livello rischio ───────────────────────────
    private static function ore_lavoratori(string $rischio): int {
        return match(strtoupper($rischio)) {
            'ALTO'  => 16,
            'MEDIO' => 12,
            default => 8,
        };
    }

    // ─── Helper: modulo integrativo ATECO per DL-RSPP ────────────────────────
    private static function modulo_integrativo_ateco(string $macro): ?array {
        return match($macro) {
            'AGRICOLTURA' => [
                'codice' => 'DL_RSPP_INTEG_A0102', 'figura' => 'Datore di Lavoro RSPP',
                'nome'   => 'Modulo Integrativo DL-RSPP Agricoltura/Silvicoltura/Zootecnia — 16 ore',
                'ore'    => 16,
                'motivo' => 'ASR 2025 — modulo integrativo ATECO A 01-02: attrezzature agricole, rischio chimico/biologico',
                'priorita_giorni' => 60, 'stato' => 'MANCANTE',
            ],
            'PESCA' => [
                'codice' => 'DL_RSPP_INTEG_A03', 'figura' => 'Datore di Lavoro RSPP',
                'nome'   => 'Modulo Integrativo DL-RSPP Pesca — 12 ore',
                'ore'    => 12,
                'motivo' => 'ASR 2025 — modulo integrativo ATECO A 03: rischi a bordo, emergenze, attrezzature speciali',
                'priorita_giorni' => 60, 'stato' => 'MANCANTE',
            ],
            'COSTRUZIONI' => [
                'codice' => 'DL_RSPP_INTEG_F', 'figura' => 'Datore di Lavoro RSPP',
                'nome'   => 'Modulo Integrativo DL-RSPP Costruzioni/Edilizia — 16 ore',
                'ore'    => 16,
                'motivo' => 'ASR 2025 — modulo integrativo ATECO F: cantieri, POS, PSC, DPI III cat., lavori in quota',
                'priorita_giorni' => 60, 'stato' => 'MANCANTE',
            ],
            'CHIMICO' => [
                'codice' => 'DL_RSPP_INTEG_C1920', 'figura' => 'Datore di Lavoro RSPP',
                'nome'   => 'Modulo Integrativo DL-RSPP Chimico/Petrolchimico — 16 ore',
                'ore'    => 16,
                'motivo' => 'ASR 2025 — modulo integrativo ATECO C 19-20: rischio chimico, ATEX, DPI, sorveglianza sanitaria',
                'priorita_giorni' => 60, 'stato' => 'MANCANTE',
            ],
            default => null,
        };
    }

    // ─── Disclaimer obbligatorio ──────────────────────────────────────────────
    public static function disclaimer(): string {
        return 'I dati, i report, le checklist e i documenti generati dall\'Audit 81+ e dal DOC81+ Builder sono bozze operative basate sulle informazioni inserite dall\'utente e sui requisiti normativi censiti dal sistema. Devono essere verificati e validati da soggetti competenti prima dell\'uso ufficiale.';
    }
}
