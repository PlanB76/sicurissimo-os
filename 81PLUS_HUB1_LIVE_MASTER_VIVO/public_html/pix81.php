<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'PIX81+ — I 1000 Slot del Sistema — 81plus.net';
$page_id    = 'pix81';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-pix81 container">
  <h1>PIX81+</h1>
  <p class="page-intro">1000 slot totali nel sistema 81plus. 200 riservati ai GENESYS FOUNDER per 90 giorni. Non e un prodotto di investimento.</p>
  <div id="pixGrid" class="pix-grid" aria-label="Griglia 1000 slot PIX81+">
    <div class="loading81">Caricamento griglia PIX81+...</div>
  </div>
  <div class="pix-legend">
    <span class="pix-legend-item pix-free">Disponibile</span>
    <span class="pix-legend-item pix-founder">Riservato GENESYS FOUNDER</span>
    <span class="pix-legend-item pix-taken">Occupato</span>
  </div>
  <div class="pix-disclaimer card81">
    <p>PIX81+ non e un prodotto di investimento. L'acquisto di uno slot non garantisce rendimento economico. Il valore dello slot e determinato esclusivamente dall'utilizzo nel sistema 81plus.</p>
  </div>
</main>
<script src="<?= BASE_URL ?>/assets/js/pix81map.js" defer></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>