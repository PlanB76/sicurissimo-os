<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'News Normative — 81plus.net';
$page_id    = 'news';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-news container">
  <h1>News Normative</h1>
  <p class="page-intro">Aggiornamenti su circolari INAIL, decreti ministeriali e novita legislative sulla sicurezza sul lavoro.</p>
  <div id="newsList" class="news-list" aria-live="polite">
    <div class="loading81">Caricamento news...</div>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>