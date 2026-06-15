<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Condizioni Generali di Vendita — 81plus.net';
$page_id    = 'cgv';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-legal container">
  <h1>Condizioni Generali di Vendita</h1>
  <p class="legal-updated">Ultimo aggiornamento: <?= date('d/m/Y') ?></p>
  <div class="legal-body">
    <h2>1. Oggetto</h2>
    <p>Le presenti condizioni regolano l'acquisto di crediti PV (PointValue) e membership sul sito 81plus.net.</p>
    <h2>2. PV — PointValue</h2>
    <p>I PV sono crediti interni con valore convenzionale 1:1 euro. Non costituiscono denaro elettronico ai sensi della Direttiva 2009/110/CE. Non sono rimborsabili, salvo specifiche condizioni contrattuali esplicitamente previste e comunicate prima dell'acquisto.</p>
    <h2>3. PV+ — Punti Gamification</h2>
    <p>I PV+ sono punti del sistema di gamification 81plus. Non hanno valore monetario. Non sono convertibili in denaro. Non rappresentano rendimento economico di alcun tipo.</p>
    <h2>4. Membership</h2>
    <p>Le membership si rinnovano mensilmente previo addebito automatico dei PV necessari. La disdetta puo essere effettuata in qualsiasi momento dall'area personale.</p>
    <h2>5. Diritto di recesso</h2>
    <p>Il diritto di recesso si applica secondo la normativa vigente (D.Lgs 206/2005). Per i servizi digitali fruiti immediatamente, il recesso non comporta rimborso dei PV gia utilizzati.</p>
    <h2>6. Foro competente</h2>
    <p>Per qualsiasi controversia e competente il Foro del luogo di residenza del consumatore, salvo accordo diverso tra le parti.</p>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>