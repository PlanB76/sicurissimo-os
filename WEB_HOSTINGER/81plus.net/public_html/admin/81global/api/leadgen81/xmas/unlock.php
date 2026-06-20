<?php
/**
 * 81PLUS Global OS — LeadGen81: Xmas Avvento Unlock
 * Skill 40 — PNL & Neuromarketing (calendario avvento 81 giorni)
 *
 * GET /api/leadgen81/xmas/unlock?contact_id=<int>&day=<int 1-81>
 *
 * Sblocca il giorno dell'avvento per il contatto, restituisce il contenuto
 * del giorno e traccia un evento DOWNLOAD.
 */

require_once __DIR__ . '/../../../_bootstrap.php';

// ---------------------------------------------------------------------------
// 1. Metodo e input
// ---------------------------------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    g81_error('Metodo non supportato', 405);
}

$contact_id = (int) ($_GET['contact_id'] ?? 0);
$day        = (int) ($_GET['day']        ?? 0);

// ---------------------------------------------------------------------------
// 2. Validazione day
// ---------------------------------------------------------------------------

if ($day < 1 || $day > 81) {
    g81_error('Il parametro day deve essere un intero tra 1 e 81');
}

if ($contact_id <= 0) {
    g81_error('contact_id obbligatorio e deve essere un intero positivo');
}

// ---------------------------------------------------------------------------
// 3. Verifica contatto e iscrizione
// ---------------------------------------------------------------------------

$contact = g81_get_contact($contact_id);
if ($contact === null || (int) ($contact['is_subscribed'] ?? 0) === 0) {
    g81_error('Accesso non consentito', 403);
}

// ---------------------------------------------------------------------------
// 4. Controlla stato attuale del giorno
// ---------------------------------------------------------------------------

$pdo = g81_pdo();

try {
    $st = $pdo->prepare(
        "SELECT * FROM leadgen81_xmas_avvento
         WHERE contact_id = ? AND day_number = ?
         LIMIT 1"
    );
    $st->execute([$contact_id, $day]);
    $existing_row = $st->fetch();
} catch (PDOException $e) {
    g81_error('Errore lettura avvento: ' . $e->getMessage(), 500);
}

$already_unlocked = $existing_row !== false && (int) $existing_row['unlocked'] === 1;

// ---------------------------------------------------------------------------
// 5. Business rule: sblocco sequenziale (giorno N richiede N-1 sbloccato)
// ---------------------------------------------------------------------------

if (!$already_unlocked && $day > 1) {
    try {
        $st = $pdo->prepare(
            "SELECT unlocked FROM leadgen81_xmas_avvento
             WHERE contact_id = ? AND day_number = ?
             LIMIT 1"
        );
        $st->execute([$contact_id, $day - 1]);
        $prev_row = $st->fetch();
    } catch (PDOException $e) {
        g81_error('Errore verifica giorno precedente: ' . $e->getMessage(), 500);
    }

    if ($prev_row === false || (int) $prev_row['unlocked'] !== 1) {
        g81_error('Sblocca prima il giorno precedente', 403);
    }
}

// ---------------------------------------------------------------------------
// 6. Sblocca il giorno (INSERT ... ON DUPLICATE KEY UPDATE)
// ---------------------------------------------------------------------------

if (!$already_unlocked) {
    try {
        $st = $pdo->prepare(
            "INSERT INTO leadgen81_xmas_avvento
                 (contact_id, day_number, unlocked, unlocked_at)
             VALUES (?, ?, 1, NOW())
             ON DUPLICATE KEY UPDATE
                 unlocked     = 1,
                 unlocked_at  = NOW()"
        );
        $st->execute([$contact_id, $day]);
    } catch (PDOException $e) {
        g81_error('Errore sblocco giorno: ' . $e->getMessage(), 500);
    }
}

// ---------------------------------------------------------------------------
// 7. Recupera contenuto del giorno
// ---------------------------------------------------------------------------

$content_type = _xmas_content_type($day);
$content      = _xmas_fetch_content($pdo, $content_type, $day);

// ---------------------------------------------------------------------------
// 8. Traccia evento DOWNLOAD
// ---------------------------------------------------------------------------

g81_track_event($contact_id, 'DOWNLOAD', [
    'source'       => 'XMAS_AVVENTO',
    'day'          => $day,
    'content_type' => $content_type,
]);

// ---------------------------------------------------------------------------
// 9. Calcola progresso
// ---------------------------------------------------------------------------

try {
    $st = $pdo->prepare(
        "SELECT COUNT(*) FROM leadgen81_xmas_avvento
         WHERE contact_id = ? AND unlocked = 1"
    );
    $st->execute([$contact_id]);
    $unlocked_days = (int) $st->fetchColumn();
} catch (PDOException $e) {
    $unlocked_days = $already_unlocked ? 0 : 1;
}

$percentage = round(($unlocked_days / 81) * 100, 1);

// ---------------------------------------------------------------------------
// 10. Risposta
// ---------------------------------------------------------------------------

g81_response([
    'success'          => true,
    'day'              => $day,
    'contact_id'       => $contact_id,
    'already_unlocked' => $already_unlocked,
    'content_type'     => $content_type,
    'content'          => $content,
    'progress'         => [
        'unlocked_days' => $unlocked_days,
        'total_days'    => 81,
        'percentage'    => $percentage,
    ],
    'xmas_url'         => 'https://81plus.christmas',
]);

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

/**
 * Determina il tipo di contenuto in base al numero del giorno.
 * Il ciclo di 7 tipi si ripete ogni 7 giorni.
 * I giorni rimanenti (non coperti dai pattern principali) usano RISORSA.
 */
function _xmas_content_type(int $day): string
{
    // Pattern fissi indicati nella specifica
    $norma_days   = [1, 8, 15, 22, 29, 36, 43, 50, 57, 64, 71];
    $obbligo_days = [2, 9, 16, 23, 30, 37, 44, 51, 58, 65, 72];
    $sanzione_days= [3, 10, 17, 24, 31, 38, 45, 52, 59, 66, 73];
    $corso_days   = [4, 11, 18, 25, 32, 39, 46, 53, 60, 67, 74, 78];
    $documento_days=[5, 12, 19, 26, 33, 40, 47, 54, 61, 68, 75];
    $servizio_days= [6, 13, 20, 27, 34, 41, 48, 55, 62, 69, 76];

    if (in_array($day, $norma_days, true))    return 'NORMA';
    if (in_array($day, $obbligo_days, true))  return 'OBBLIGO';
    if (in_array($day, $sanzione_days, true)) return 'SANZIONE';
    if (in_array($day, $corso_days, true))    return 'CORSO';
    if (in_array($day, $documento_days, true))return 'DOCUMENTO';
    if (in_array($day, $servizio_days, true)) return 'SERVIZIO';

    return 'RISORSA';
}

/**
 * Recupera il contenuto dal DB in base al tipo e al numero del giorno.
 * Per RISORSA restituisce un tip educativo statico.
 */
function _xmas_fetch_content(PDO $pdo, string $content_type, int $day): array
{
    // Offset usato per recuperare righe diverse nei tipi ciclici
    // Si basa sulla posizione del giorno nella propria serie (0-based)
    $offset = (int) floor(($day - 1) / 7);

    try {
        switch ($content_type) {
            case 'NORMA':
                $st = $pdo->prepare(
                    "SELECT * FROM lex81_norms
                     ORDER BY id ASC
                     LIMIT 1 OFFSET ?"
                );
                $st->execute([$offset]);
                $row = $st->fetch();
                return $row !== false ? $row : [];

            case 'OBBLIGO':
                $st = $pdo->prepare(
                    "SELECT * FROM lex81_obligations
                     ORDER BY id ASC
                     LIMIT 1 OFFSET ?"
                );
                $st->execute([$offset]);
                $row = $st->fetch();
                return $row !== false ? $row : [];

            case 'SANZIONE':
                $st = $pdo->prepare(
                    "SELECT * FROM lex81_sanctions
                     ORDER BY id ASC
                     LIMIT 1 OFFSET ?"
                );
                $st->execute([$offset]);
                $row = $st->fetch();
                return $row !== false ? $row : [];

            case 'CORSO':
                $st = $pdo->prepare(
                    "SELECT * FROM lex81_courses
                     ORDER BY id ASC
                     LIMIT 1 OFFSET ?"
                );
                $st->execute([$offset]);
                $row = $st->fetch();
                return $row !== false ? $row : [];

            case 'DOCUMENTO':
                $st = $pdo->prepare(
                    "SELECT * FROM lex81_documents
                     ORDER BY id ASC
                     LIMIT 1 OFFSET ?"
                );
                $st->execute([$offset]);
                $row = $st->fetch();
                return $row !== false ? $row : [];

            case 'SERVIZIO':
                $st = $pdo->prepare(
                    "SELECT * FROM lex81_services
                     WHERE active = 1
                     ORDER BY id ASC
                     LIMIT 1 OFFSET ?"
                );
                $st->execute([$offset]);
                $row = $st->fetch();
                return $row !== false ? $row : [];

            default:
                // RISORSA: tip educativo statico ciclico
                return _xmas_static_tip($day);
        }
    } catch (PDOException $e) {
        // In caso di errore DB restituisce il tip statico come fallback
        return _xmas_static_tip($day);
    }
}

/**
 * Restituisce un tip educativo statico basato sul numero del giorno.
 * I tip si ripetono ciclicamente ogni 10 elementi.
 */
function _xmas_static_tip(int $day): array
{
    $tips = [
        [
            'tipo'      => 'RISORSA',
            'titolo'    => 'DVR — Documento di Valutazione dei Rischi',
            'contenuto' => 'Il DVR e la spina dorsale della sicurezza in azienda. Ogni datore di lavoro con almeno un dipendente deve averlo aggiornato. Senza DVR: sanzione penale fino a 6.000 euro.',
        ],
        [
            'tipo'      => 'RISORSA',
            'titolo'    => 'Formazione obbligatoria (art. 37 D.Lgs 81/08)',
            'contenuto' => 'La formazione dei lavoratori non e facoltativa. Va erogata entro 60 giorni dall\'assunzione e rinnovata ogni 5 anni. Mancata formazione: sanzione da 1.315 a 5.699 euro.',
        ],
        [
            'tipo'      => 'RISORSA',
            'titolo'    => 'Nomina del RSPP',
            'contenuto' => 'Il Responsabile del Servizio di Prevenzione e Protezione deve essere nominato per iscritto. Il datore di lavoro puo ricoprire questo ruolo nelle aziende fino a 30 addetti (settori a rischio basso).',
        ],
        [
            'tipo'      => 'RISORSA',
            'titolo'    => 'Sorveglianza sanitaria',
            'contenuto' => 'Il medico competente e obbligatorio quando i lavoratori sono esposti a rischi specifici: videoterminali, rumore, sostanze chimiche, movimentazione carichi. La visita va fatta prima dell\'assunzione.',
        ],
        [
            'tipo'      => 'RISORSA',
            'titolo'    => 'Piano di evacuazione e gestione emergenze',
            'contenuto' => 'Ogni azienda deve avere un piano di emergenza scritto, almeno un addetto al primo soccorso e uno antincendio formati. Le prove di evacuazione devono essere documentate.',
        ],
        [
            'tipo'      => 'RISORSA',
            'titolo'    => 'HACCP — Analisi dei pericoli nei punti critici',
            'contenuto' => 'Per il settore alimentare il piano HACCP identifica i punti critici di controllo (CCP) nella filiera produttiva. Va aggiornato ogni volta che cambia il processo o i prodotti trattati.',
        ],
        [
            'tipo'      => 'RISORSA',
            'titolo'    => 'Segnaletica di sicurezza',
            'contenuto' => 'I cartelli di sicurezza devono essere conformi alla norma UNI EN ISO 7010. Vietato l\'accesso, uscita di emergenza, estintore, pericolo chimico: ogni segnale ha forma e colore codificati.',
        ],
        [
            'tipo'      => 'RISORSA',
            'titolo'    => 'DPI — Dispositivi di Protezione Individuale',
            'contenuto' => 'Il datore di lavoro e tenuto a fornire i DPI gratuitamente. Guanti, maschere, cuffie, occhiali: la scelta dipende dai rischi identificati nel DVR. Vanno consegnati con registro di distribuzione.',
        ],
        [
            'tipo'      => 'RISORSA',
            'titolo'    => 'Registro infortuni',
            'contenuto' => 'Dal 2017 il registro infortuni non e piu obbligatorio in formato cartaceo. Gli infortuni superiori a 3 giorni devono essere comunicati all\'INAIL tramite il portale online entro 48 ore.',
        ],
        [
            'tipo'      => 'RISORSA',
            'titolo'    => 'ISO 45001 — Certificazione sicurezza',
            'contenuto' => 'La certificazione ISO 45001 trasforma la sicurezza da costo a vantaggio competitivo. Le aziende certificate accedono a bandi pubblici e gare d\'appalto con punteggi premianti.',
        ],
    ];

    $index = ($day - 1) % count($tips);
    return $tips[$index];
}
