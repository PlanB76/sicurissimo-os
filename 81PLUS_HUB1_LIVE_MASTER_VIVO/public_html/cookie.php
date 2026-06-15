<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Cookie Policy — 81plus.net';
$page_id    = 'cookie';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-legal container">
  <h1>Cookie Policy</h1>
  <p class="legal-updated">Ultimo aggiornamento: <?= date('d/m/Y') ?></p>
  <div class="legal-body">
    <h2>Cookie tecnici</h2>
    <p>Utilizziamo esclusivamente cookie tecnici essenziali per il funzionamento del sito: gestione sessione, preferenze lingua, sicurezza CSRF. Non richiedono consenso ai sensi del Provvedimento Garante 231/2014.</p>
    <h2>Cookie di analisi</h2>
    <p>Non utilizziamo cookie di analisi o profilazione di terze parti. Le metriche interne sono aggregate e anonime.</p>
    <h2>Gestione cookie</h2>
    <p>Puoi disabilitare i cookie tecnici dalle impostazioni del browser, ma alcune funzionalita del sito potrebbero non essere disponibili.</p>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>