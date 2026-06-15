<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Libri e Risorse — 81plus.net';
$page_id    = 'libri';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-libri container">
  <h1>Libri e Risorse</h1>
  <p class="page-intro">Pubblicazioni, guide pratiche e risorse formative per la sicurezza aziendale. Materiali validati da esperti.</p>
  <div id="libroGrid" class="libri-grid" aria-live="polite">
    <div class="loading81">Caricamento risorse...</div>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>