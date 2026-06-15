<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Academy 81+ — Formazione Certificata';
$page_id    = 'academy81';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-academy81 container">
  <h1>Academy 81+</h1>
  <p class="page-intro">Corsi di formazione certificati per la sicurezza aziendale. I partner Academy non sono visibili pubblicamente. I corsi si seguono sulla piattaforma del partner.</p>
  <div class="academy-categories">
    <div class="card81">
      <h2>D.Lgs 81/08</h2>
      <p>Formazione obbligatoria lavoratori, preposti, dirigenti. Attestati validi.</p>
      <a href="<?= BASE_URL ?>/academy81.php?cat=8108" class="btn-secondary">Vedi corsi</a>
    </div>
    <div class="card81">
      <h2>HACCP</h2>
      <p>Corsi igiene alimentare per operatori e responsabili del settore food.</p>
      <a href="<?= BASE_URL ?>/academy81.php?cat=haccp" class="btn-secondary">Vedi corsi</a>
    </div>
    <div class="card81">
      <h2>ISO 45001/9001/14001</h2>
      <p>Percorsi per auditor, lead auditor e responsabili sistema qualita.</p>
      <a href="<?= BASE_URL ?>/academy81.php?cat=iso" class="btn-secondary">Vedi corsi</a>
    </div>
  </div>
  <div id="corsiList" class="corsi-list" aria-live="polite"></div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>