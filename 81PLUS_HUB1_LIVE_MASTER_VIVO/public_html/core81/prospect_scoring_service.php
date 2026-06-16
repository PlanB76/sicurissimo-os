<?php
// core81/prospect_scoring_service.php — Lead/Prospect Scoring ASR 2025
declare(strict_types=1);
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/asr2025_compliance_service.php';

class ProspectScoringService {
    // Score totale 0-100: 70+ = HOT, 40-69 = WARM, 0-39 = COLD

    public static function score_prospect(array $prospect): int {
        $score = 0;

        // Settore ad alto rischio normativo
        $macro = ASR2025ComplianceService::macrosettore($prospect['ateco_code'] ?? '');
        $score += match($macro) {
            'COSTRUZIONI', 'CHIMICO' => 25,
            'AGRICOLTURA', 'PESCA'   => 20,
            'RISTORAZIONE'           => 15,
            default                   => 5,
        };

        // Dimensione azienda (proxy per urgenza)
        $lav = (int)($prospect['num_dipendenti'] ?? 0);
        $score += match(true) {
            $lav >= 50  => 25,
            $lav >= 10  => 20,
            $lav >= 5   => 15,
            $lav >= 1   => 10,
            default     => 0,
        };

        // Compliance gap (più corsi mancanti = più urgenza)
        $score += min(25, (int)($prospect['corsi_mancanti'] ?? 0) * 5);

        // Segnali di engagement
        if (!empty($prospect['ha_richiesto_audit']))  $score += 10;
        if (!empty($prospect['ha_visitato_paygate'])) $score += 5;
        if (!empty($prospect['ha_referral']))         $score += 5;
        if (!empty($prospect['dvr_assente']))         $score += 10;

        return min(100, $score);
    }

    public static function classifica(int $score): string {
        return match(true) {
            $score >= 70 => 'HOT',
            $score >= 40 => 'WARM',
            default      => 'COLD',
        };
    }

    public static function aggiorna_score_db(int $prospect_id, int $score): void {
        try {
            $db = DB::get();
            $db->prepare(
                'UPDATE scout81_prospects SET score = ?, score_label = ?, updated_at = NOW() WHERE id = ?'
            )->execute([$score, self::classifica($score), $prospect_id]);
        } catch (Throwable $e) {
            error_log('[PROSPECT SCORING] ' . $e->getMessage());
        }
    }

    public static function batch_rescore(): int {
        $db = DB::get();
        $prospects = $db->query('SELECT * FROM scout81_prospects')->fetchAll();
        $n = 0;
        foreach ($prospects as $p) {
            $s = self::score_prospect($p);
            self::aggiorna_score_db((int)$p['id'], $s);
            $n++;
        }
        return $n;
    }
}
