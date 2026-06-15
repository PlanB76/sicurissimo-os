<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Green81+ — Foresta e DAO — 81plus.net';
$page_id    = 'green81';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-green81 container">
  <section class="green81-hero">
    <h1>Green81+</h1>
    <p class="hero-sub">Ogni azienda che sceglie la sicurezza contribuisce alla riforestazione. Ogni albero e tracciato.</p>
  </section>
  <section class="green81-counter container">
    <div class="card81 green-stat">
      <div class="stat-num" id="alberiCount">--</div>
      <div class="stat-label">Alberi piantati</div>
    </div>
    <div class="card81 green-stat">
      <div class="stat-num" id="co2Count">--</div>
      <div class="stat-label">CO2 compensata (kg)</div>
    </div>
  </section>
  <section class="green81-dao container">
    <h2>DAO Green81+</h2>
    <p>La governance del fondo Green81+ e affidata alla community. I titolari di PV+ possono partecipare alle votazioni sulle iniziative ambientali.</p>
  </section>
</main>
<script>
fetch('<?= BASE_URL ?>/api/green81-stats.php')
  .then(function(r){ return r.json(); })
  .then(function(d){
    if (d.alberi) document.getElementById('alberiCount').textContent = d.alberi;
    if (d.co2) document.getElementById('co2Count').textContent = d.co2;
  });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>