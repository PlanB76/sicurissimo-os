<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
auth_require();
$user = auth_user();
$page_title = 'Ecosistema 3D — Pipeline e Rete — 81plus.net';
$page_id    = 'ecosistema3d';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-ecosistema3d">
  <div class="d3-controls container">
    <h1>Pipeline3D — La tua rete</h1>
    <div class="d3-toolbar">
      <button id="resetView" class="btn-secondary btn-sm">Reset vista</button>
      <select id="filterDepth"><option value="3">3 livelli</option><option value="5">5 livelli</option><option value="all">Tutti</option></select>
    </div>
  </div>
  <div id="pipeline3dContainer" class="d3-container" aria-label="Visualizzazione 3D della tua rete commerciale" style="width:100%;height:600px;"></div>
</main>
<script src="https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.min.js" defer></script>
<script src="<?= BASE_URL ?>/assets/js/pipeline3d.js" defer></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>