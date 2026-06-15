<?php
// includes/semantic_guard.php — Filtro parole vietate 81plus.net
declare(strict_types=1);

const PAROLE_VIETATE_81 = [
    // Token/Strumenti finanziari vietati
    'investimento', 'investire', 'rendita', 'rendimento',
    'profitto garantito', 'guadagno garantito', 'guadagno assicurato',
    'APY', 'staking garantito', 'CEX', 'passive income garantita',

    // Brand deprecated
    'SICONET', 'SAFE5.0', 'GreenGrove81', 'Groove81+',

    // Promesse false
    'zero multe', 'rischio zero', 'garantiamo al 100%', 'azienda al 100%',
    'soldi facili', 'diventa ricco', 'schema piramidale', 'schema Ponzi',

    // Recensioni non reali
    'recensioni demo', 'recensioni simulate', 'recensioni generate',
    'recensioni da verificare',

    // Web3 fuori contesto
    'BAYC', 'Bored Ape',
];

// Sostituzioni suggerite (chiave=vietata, valore=alternativa approvata)
const SOSTITUZIONI_81 = [
    'investimento'           => 'utility',
    'guadagno garantito'     => 'opportunità commerciale reale',
    'rendimento'             => 'reward variabile',
    'profitto'               => 'risultato variabile',
    'rischio zero'           => 'riduzione del rischio',
    'azienda al 100%'        => 'percorso guidato di presidio',
    'passive income'         => 'flusso commerciale da network',
    'soldi facili'           => 'opportunità di lavoro reale',
    'zero multe'             => 'riduzione del rischio sanzionatorio',
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
        if (APP_ENV === 'development') {
            throw new RuntimeException('Semantic guard: ' . implode(', ', $v));
        }
    }
}
