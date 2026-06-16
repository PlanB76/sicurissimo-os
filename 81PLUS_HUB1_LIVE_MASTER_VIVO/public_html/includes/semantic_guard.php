<?php
// includes/semantic_guard.php — Filtro parole vietate 81plus.net
// Aggiornato con parole vietate ASR 2025 Compliance Engine
declare(strict_types=1);

const PAROLE_VIETATE_81 = [
    // Token/Strumenti finanziari vietati
    'investimento', 'investire', 'rendita', 'rendimento',
    'profitto garantito', 'guadagno garantito', 'guadagno assicurato',
    'APY', 'staking garantito', 'CEX', 'passive income garantita',
    'investimento garantito', 'rendita automatica', 'profitto sicuro',

    // Brand deprecated
    'SICONET', 'SAFE5.0', 'GreenGrove81', 'Groove81+',

    // Promesse false — originali
    'zero multe', 'rischio zero', 'garantiamo al 100%', 'azienda al 100%',
    'soldi facili', 'diventa ricco', 'schema piramidale', 'schema Ponzi',

    // Promesse false — ASR 2025 compliance specifiche
    'zero sanzioni', 'azzerare rischi legali', 'azzeramento del rischio',
    'compliance totale automatica', 'azienda in regola al 100%',
    'garantito al 100%', 'conformita garantita',

    // Recensioni non reali
    'recensioni demo', 'recensioni simulate', 'recensioni generate',
    'recensioni da verificare',

    // Web3 fuori contesto
    'BAYC', 'Bored Ape',
];

// Sostituzioni suggerite (chiave=vietata, valore=alternativa approvata)
const SOSTITUZIONI_81 = [
    'investimento'              => 'utility',
    'investimento garantito'    => 'percorso con opportunità reali',
    'guadagno garantito'        => 'opportunità commerciale reale',
    'rendimento'                => 'reward variabile',
    'rendita automatica'        => 'flusso commerciale da network',
    'profitto'                  => 'risultato variabile',
    'profitto sicuro'           => 'attività commerciale reale',
    'rischio zero'              => 'riduzione del rischio',
    'zero sanzioni'             => 'riduzione del rischio sanzionatorio',
    'azzerare rischi legali'    => 'presidiare i rischi legali',
    'azzeramento del rischio'   => 'mitigazione del rischio',
    'compliance totale automatica' => 'supporto alla gestione degli obblighi',
    'azienda al 100%'           => 'percorso guidato di presidio',
    'azienda in regola al 100%' => 'orientamento verso la conformità',
    'garantito al 100%'         => 'verificato con soggetti competenti',
    'conformita garantita'      => 'percorso di verifica assistita',
    'passive income'            => 'flusso commerciale da network',
    'soldi facili'              => 'opportunità di lavoro reale',
    'zero multe'                => 'riduzione del rischio sanzionatorio',
];

function semantic_check(string $testo): array {
    $trovate = [];
    $lower   = mb_strtolower($testo);
    foreach (PAROLE_VIETATE_81 as $p) {
        if (mb_strpos($lower, mb_strtolower($p)) !== false) {
            $trovate[] = $p;
        }
    }
    return $trovate;
}

function semantic_clean(string $testo): string {
    foreach (SOSTITUZIONI_81 as $vietata => $approvata) {
        $testo = preg_replace('/\b' . preg_quote($vietata, '/') . '\b/iu', $approvata, $testo);
    }
    return $testo;
}

function semantic_block(string $testo, string $contesto = ''): void {
    $v = semantic_check($testo);
    if (!empty($v)) {
        error_log('[SEMANTIC_GUARD] Parole vietate in "' . $contesto . '": ' . implode(', ', $v));
        // Log su DB in produzione (non bloccante)
        try {
            $db = DB::get();
            $db->prepare(
                'INSERT INTO semantic_guard_log (user_id, contesto, parole, ip_hash, created_at)
                 VALUES (?, ?, ?, ?, NOW())'
            )->execute([
                $_SESSION['user_id'] ?? null,
                $contesto,
                implode(', ', $v),
                hash('sha256', $_SERVER['REMOTE_ADDR'] ?? ''),
            ]);
        } catch (Throwable) {}
        if (defined('APP_ENV') && APP_ENV === 'development') {
            throw new RuntimeException('Semantic guard: ' . implode(', ', $v));
        }
    }
}

