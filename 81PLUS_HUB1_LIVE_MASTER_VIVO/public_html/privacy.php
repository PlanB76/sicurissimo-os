<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Privacy Policy — 81plus.net';
$page_id    = 'privacy';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-legal container">
  <h1>Privacy Policy</h1>
  <p class="legal-updated">Ultimo aggiornamento: <?= date('d/m/Y') ?></p>
  <div class="legal-body">
    <h2>1. Titolare del trattamento</h2>
    <p>Il titolare del trattamento dei dati personali e la societa responsabile del sito 81plus.net, raggiungibile tramite i canali ufficiali indicati nel sito.</p>
    <h2>2. Dati raccolti</h2>
    <p>Raccogliamo dati di registrazione (nome, email), dati di utilizzo del servizio e dati tecnici (IP anonimizzato, cookie tecnici). Non raccogliamo dati sensibili senza consenso esplicito.</p>
    <h2>3. Finalita del trattamento</h2>
    <p>I dati sono trattati per: erogazione del servizio, comunicazioni di sistema, miglioramento del prodotto. Non vendiamo dati a terzi.</p>
    <h2>4. Base giuridica</h2>
    <p>Contratto (art. 6 lett. b GDPR), consenso (art. 6 lett. a GDPR), interesse legittimo (art. 6 lett. f GDPR).</p>
    <h2>5. Conservazione</h2>
    <p>I dati sono conservati per la durata del rapporto contrattuale e fino a 10 anni per gli obblighi fiscali/legali.</p>
    <h2>6. Diritti dell'interessato</h2>
    <p>Accesso, rettifica, cancellazione, portabilita, opposizione. Richieste tramite i canali ufficiali del sito.</p>
    <h2>7. Cookie</h2>
    <p>Utilizziamo solo cookie tecnici essenziali. Nessun cookie di profilazione di terze parti. Vedi la <a href="<?= BASE_URL ?>/cookie.php">Cookie Policy</a>.</p>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>