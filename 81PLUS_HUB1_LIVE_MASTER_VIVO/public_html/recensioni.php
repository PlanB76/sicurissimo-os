<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Testimonianze Reali — 81plus.net';
$page_id    = 'recensioni';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-recensioni container">
  <h1>Testimonianze reali</h1>
  <p class="page-intro">Esperienze documentate di imprenditori che hanno scelto 81plus. Nessuna recensione simulata o generata.</p>
  <div id="recensioniContainer" class="recensioni-grid" aria-live="polite">
    <div class="loading81">Caricamento testimonianze...</div>
  </div>
</main>
<script>
fetch('<?= BASE_URL ?>/api/recensioni-list.php')
  .then(function(r){ return r.json(); })
  .then(function(d){
    if (!d.ok || !d.items) return;
    var c = document.getElementById('recensioniContainer');
    c.innerHTML = '';
    d.items.forEach(function(r) {
      var div = document.createElement('div');
      div.className = 'recensione-card card81';
      div.innerHTML = '<blockquote>' + r.testo + '</blockquote><cite>' + r.autore + ' &mdash; ' + r.settore + '</cite>';
      c.appendChild(div);
    });
  });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>