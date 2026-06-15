<?php
// api/audit-submit.php — Calcola punteggio audit 81/08 e HACCP
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_err('Metodo non consentito', 405);
csrf_check();

$settore    = trim($_POST['settore']    ?? '');
$dipendenti = trim($_POST['dipendenti'] ?? '');
$dvr        = trim($_POST['dvr']        ?? 'no');
$formazione = trim($_POST['formazione'] ?? 'mai');
$email      = strtolower(trim($_POST['email'] ?? ''));

if (!$settore) json_err('Seleziona il settore');

// Calcolo score rischio (0-100, 100 = rischio massimo)
$score = 50;
if ($dvr === 'si')      $score -= 20;
elseif ($dvr === 'vecchio') $score += 10;
else                    $score += 30;

if ($formazione === '12m')  $score -= 15;
elseif ($formazione === '24m') $score += 5;
else                    $score += 20;

if (in_array($settore, ['cantiere','manifattura','food'], true)) $score += 10;

$score = max(0, min(100, $score));
$livello = match(true) {
    $score >= 70 => 'ALTO',
    $score >= 40 => 'MEDIO',
    default      => 'BASSO',
};

$raccomandazioni = [];
if ($dvr !== 'si')              $raccomandazioni[] = 'Aggiorna il Documento di Valutazione dei Rischi (DVR) secondo D.Lgs 81/08';
if ($formazione !== '12m')      $raccomandazioni[] = 'Pianifica la formazione obbligatoria dei dipendenti';
if ($settore === 'food')        $raccomandazioni[] = 'Verifica il piano HACCP e i registri di controllo temperatura';
if ($settore === 'cantiere')    $raccomandazioni[] = 'Controlla PSC/POS e la formazione specifica per cantieri';
$raccomandazioni[] = 'Accedi a 81plus.net per gestire scadenze e generare documenti operativi';

// Salva lead se email fornita
if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    try {
        $db = DB::get();
        $db->prepare('INSERT IGNORE INTO preventivi (tipo, settore, email_richiedente, contenuto, created_at) VALUES ("AUDIT", ?, ?, ?, NOW())')
           ->execute([$settore, $email, json_encode(['score'=>$score,'livello'=>$livello])]);
    } catch (Throwable) {}
}

json_ok([
    'score'           => $score,
    'livello'         => $livello,
    'raccomandazioni' => $raccomandazioni,
    'msg'             => 'Rischio ' . $livello . ' (' . $score . '/100). ' . count($raccomandazioni) . ' azioni raccomandate.',
]);
