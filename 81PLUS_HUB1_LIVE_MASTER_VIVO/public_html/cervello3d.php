<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
auth_require();
$user = auth_user();
$page_title = 'Compensi3D e Equilibrio — 81plus.net';
$page_id    = 'cervello3d';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-cervello3d container">
  <h1>Compensi3D</h1>
  <p class="page-intro">Il tuo riepilogo compensi maturati, piano di carriera 81+ e stato di equilibrio economico.</p>
  <div class="grid-2">
    <div id="compensi3dCanvas" style="height:400px;"></div>
    <div id="equilibrioPanel" class="card81"></div>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.min.js" defer></script>
<script src="<?= BASE_URL ?>/assets/js/compensi3d.js" defer></script>
<script src="<?= BASE_URL ?>/assets/js/equilibrio3d.js" defer></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>