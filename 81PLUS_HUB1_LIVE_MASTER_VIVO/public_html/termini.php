<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Termini di Utilizzo — 81plus.net';
$page_id    = 'termini';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-legal container">
  <h1>Termini di Utilizzo</h1>
  <p class="legal-updated">Ultimo aggiornamento: <?= date('d/m/Y') ?></p>
  <div class="legal-body">
    <h2>1. Accettazione</h2>
    <p>L'utilizzo del sito 81plus.net implica l'accettazione dei presenti termini. Se non accetti, non puoi utilizzare il servizio.</p>
    <h2>2. Utilizzo consentito</h2>
    <p>Il servizio e destinato a persone fisiche e giuridiche per uso aziendale. E vietato l'uso fraudolento, la creazione di account falsi, la manipolazione del sistema di gamification.</p>
    <h2>3. Contenuti generati</h2>
    <p>I documenti generati dal DOC81+ Builder sono bozze operative. Non costituiscono parere legale ne documentazione ufficiale. Richiedono validazione professionale prima dell'uso.</p>
    <h2>4. Limitazione di responsabilita</h2>
    <p>81plus.net non garantisce la completezza o aggiornamento delle informazioni normative. L'utente rimane responsabile della conformita della propria azienda alle normative vigenti.</p>
    <h2>5. Modifiche al servizio</h2>
    <p>Ci riserviamo il diritto di modificare il servizio, i prezzi e i termini con preavviso di 30 giorni via email.</p>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>