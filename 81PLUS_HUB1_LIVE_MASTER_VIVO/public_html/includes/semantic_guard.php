<?php
// includes/semantic_guard.php — Filtro parole vietate 81plus.net
declare(strict_types=1);

const PAROLE_VIETATE_81 = [
    'investimento', 'rendita', 'rendimento', 'profitto garantito',
    'guadagno garantito', 'APY', 'staking garantito', 'CEX',
    'SICONET', 'SAFE5.0', 'GreenGrove81', 'Groove81',
    'zero multe', 'rischio zero', 'garantiamo al 100%',
    'recensioni demo', 'recensioni simulate', 'recensioni generate',
    'recensioni da verificare', 'BAYC', 'Bored Ape',
];

function semantic_check(string $testo): array {
    $trovate = [];
    foreach (PAROLE_VIETATE_81 as $p) {
        if (stripos($testo, $p) !== false) {
            $trovate[] = $p;
        }
    }
    return $trovate;
}

function semantic_block(string $testo, string $contesto = ''): void {
    $v = semantic_check($testo);
    if (!empty($v)) {
        error_log('[SEMANTIC_GUARD] Parole vietate rilevate in ' . $contesto . ': ' . implode(', ', $v));
        if (($_ENV['APP_ENV'] ?? 'production') === 'development') {
            throw new RuntimeException('Semantic guard: parole vietate rilevate: ' . implode(', ', $v));
        }
    }
}
