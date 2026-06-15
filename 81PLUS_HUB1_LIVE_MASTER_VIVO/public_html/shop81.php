<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Shop 81+ — Prodotti e Abbonamenti';
$page_id    = 'shop81';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-shop81 container">
  <h1>Shop 81+</h1>
  <p class="page-intro">Prodotti digitali, abbonamenti e risorse premium per la sicurezza aziendale.</p>
  <div id="shopGrid" class="shop-grid" aria-live="polite">
    <div class="loading81">Caricamento prodotti...</div>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>