<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Newsletter 81+ — Aggiornamenti Normativi';
$page_id    = 'newsletter';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-newsletter container-narrow">
  <div class="card81">
    <h1>Iscriviti alla newsletter</h1>
    <p>Ricevi ogni settimana aggiornamenti normativi, novita del sistema e casi pratici per la tua azienda. Zero spam.</p>
    <form method="POST" action="<?= BASE_URL ?>/api/newsletter-subscribe.php">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <div class="form-group"><label>Email *</label><input type="email" name="email" required placeholder="mario@azienda.it"></div>
      <div class="form-group"><label>Nome</label><input type="text" name="nome" placeholder="Mario"></div>
      <div class="form-group">
        <label class="checkbox-label">
          <input type="checkbox" name="privacy" required value="1">
          Accetto il trattamento dei dati per l'invio della newsletter. <a href="<?= BASE_URL ?>/privacy.php">Privacy Policy</a>
        </label>
      </div>
      <button type="submit" class="btn-primary btn-full">Iscriviti</button>
    </form>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>