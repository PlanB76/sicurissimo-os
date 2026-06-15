<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Eventi e Webinar — 81plus.net';
$page_id    = 'eventi';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-eventi container">
  <h1>Eventi e Webinar</h1>
  <p class="page-intro">Webinar strategici, incontri live e sessioni formative per imprenditori e NETWORKER81+.</p>
  <div id="eventiList" class="eventi-list" aria-live="polite">
    <div class="loading81">Caricamento eventi...</div>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>