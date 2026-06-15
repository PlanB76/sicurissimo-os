<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Blog — Sicurezza Aziendale — 81plus.net';
$page_id    = 'blog';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-blog container">
  <h1>Blog 81+</h1>
  <p class="page-intro">Articoli, guide e aggiornamenti normativi per imprenditori italiani. D.Lgs 81/08, HACCP, ISO, sicurezza cantiere.</p>
  <div id="blogList" class="blog-list" aria-live="polite">
    <div class="loading81">Caricamento articoli...</div>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>