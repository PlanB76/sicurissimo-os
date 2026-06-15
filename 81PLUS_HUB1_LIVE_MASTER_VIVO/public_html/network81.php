<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
auth_require();
$user = auth_user();
$page_title = 'NETWORK81+ — Costruisci la tua rete — 81plus.net';
$page_id    = 'network81';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-network81 layout-dashboard">
  <div class="container">
    <h1>NETWORK81+</h1>
    <p class="page-intro">La tua rete commerciale per la sicurezza aziendale. Prospect qualificati, pipeline 3D, territorio mappato.</p>
    <?php if (!in_array($user['ruolo'], ['NETWORKER81','ELITE81','ADMIN81'], true)): ?>
    <div class="upgrade-prompt card81">
      <h2>Funzione disponibile da NETWORKER81+</h2>
      <p>Aggiorna il tuo piano per accedere al NETWORK81+.</p>
      <a href="<?= BASE_URL ?>/membership.php" class="btn-primary">Vedi i piani</a>
    </div>
    <?php else: ?>
    <div class="network-modules grid-2">
      <div class="card81"><h3>Pipeline3D</h3><p>Visualizza la tua rete in 3D. Traccia il percorso di ogni prospect.</p><a href="<?= BASE_URL ?>/ecosistema3d.php" class="btn-secondary">Apri Pipeline3D</a></div>
      <div class="card81"><h3>Territory Map</h3><p>La tua zona territoriale. Prospect nella tua area. Stato di avanzamento.</p><a href="<?= BASE_URL ?>/scout81.php#map" class="btn-secondary">Vedi la mappa</a></div>
      <div class="card81"><h3>Lead Storici</h3><p>I tuoi prospect assegnati, stati di avanzamento, storico contatti.</p><a href="<?= BASE_URL ?>/api/networker-leads.php" class="btn-secondary">Gestisci lead</a></div>
      <div class="card81"><h3>Compensi3D</h3><p>Il tuo riepilogo compensi, piano di carriera, equilibrio economico.</p><a href="<?= BASE_URL ?>/cervello3d.php" class="btn-secondary">Vedi compensi</a></div>
    </div>
    <?php endif; ?>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>