<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
auth_require();
$user = auth_user();
$page_title = 'SCOUT81+ — Trova i tuoi prospect — 81plus.net';
$page_id    = 'scout81';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-scout81 layout-dashboard">
  <div class="container">
    <h1>SCOUT81+</h1>
    <p class="page-intro">SCOUT81+ e il motore di ricerca prospect del sistema 81plus. Identifica le aziende nella tua zona. Non garantisce clienti ne conversioni.</p>
    <?php if (!in_array($user['ruolo'], ['NETWORKER81','ELITE81','ADMIN81'], true) && ($user['membership'] ?? '') !== 'PRO+'): ?>
    <div class="upgrade-prompt card81">
      <h2>SCOUT81+ disponibile da PRO+</h2>
      <a href="<?= BASE_URL ?>/membership.php?piano=pro" class="btn-primary">Aggiorna a PRO+</a>
    </div>
    <?php else: ?>
    <div class="scout-filters card81">
      <h2>Filtri di ricerca</h2>
      <div class="filter-row">
        <select id="filterSettore"><option value="">Tutti i settori</option><option>Manifattura</option><option>Food</option><option>Cantiere</option><option>Ufficio</option></select>
        <select id="filterRegione"><option value="">Tutte le regioni</option></select>
        <select id="filterScore"><option value="">Qualsiasi score</option><option value="hot">Hot (70+)</option><option value="warm">Warm (40-69)</option><option value="cold">Cold (0-39)</option></select>
        <button id="filterApply" class="btn-primary">Cerca</button>
      </div>
    </div>
    <div id="scoutMap" class="scout-map" style="height:420px;" aria-label="Mappa SCOUT81+ prospect Italia"></div>
    <div id="scoutList" class="scout-list" aria-live="polite"></div>
    <?php endif; ?>
  </div>
</main>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
<script src="<?= BASE_URL ?>/assets/js/scout81map.js" defer></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>