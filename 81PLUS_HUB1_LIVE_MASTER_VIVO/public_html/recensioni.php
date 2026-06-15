<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Testimonianze Reali SICURISSIMO81+ — 81plus.net';
$page_id    = 'recensioni';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-recensioni container">
  <div class="page-header">
    <h1>Testimonianze reali SICURISSIMO81+ dal 2003</h1>
    <p class="page-intro">Esperienze documentate di imprenditori che hanno scelto di presidiare la sicurezza. Ogni testimonianza e verificata. Nessuna recensione simulata o generata.</p>
  </div>

  <div class="recensioni-filters">
    <select id="filterSettore" aria-label="Filtra per settore">
      <option value="">Tutti i settori</option>
      <option value="manifattura">Manifattura</option>
      <option value="food">Food &amp; Ristorazione</option>
      <option value="cantiere">Cantiere / Edilizia</option>
      <option value="ufficio">Ufficio</option>
    </select>
    <select id="filterRating" aria-label="Filtra per valutazione">
      <option value="">Qualsiasi valutazione</option>
      <option value="5">5 stelle</option>
      <option value="4">4 stelle</option>
    </select>
    <button class="btn-secondary btn-sm" id="filterApply">Filtra</button>
  </div>

  <div id="recensioniContainer" class="recensioni-grid" aria-live="polite">
    <div class="loading81">Caricamento testimonianze...</div>
  </div>

  <div class="recensioni-note card81">
    <p><strong>Trasparenza:</strong> Tutte le testimonianze presenti su questa pagina sono reali e documentate. Nessuna recensione e simulata, generata o da verificare. Le testimonianze sono raccolte con consenso esplicito del titolare e non violano la normativa GDPR.</p>
  </div>
</main>

<script>
function loadRecensioni(settore, rating) {
  var container = document.getElementById('recensioniContainer');
  container.innerHTML = '<div class="loading81">Caricamento...</div>';
  var params = new URLSearchParams();
  if (settore) params.set('settore', settore);
  if (rating)  params.set('rating', rating);
  fetch('<?= BASE_URL ?>/api/recensioni-list.php' + (params.toString() ? '?' + params : ''))
    .then(function(r){return r.json();})
    .then(function(d){
      if (!d.ok || !d.items || !d.items.length) {
        container.innerHTML = '<p>Nessuna testimonianza trovata con questi filtri.</p>';
        return;
      }
      container.innerHTML = '';
      d.items.forEach(function(r){
        var stars = '&#9733;'.repeat(r.rating||5);
        var card = document.createElement('div');
        card.className = 'recensione-card card81';
        card.innerHTML = '<div class="rec-stars" aria-label="' + (r.rating||5) + ' su 5">' + stars + '</div>'
          + '<blockquote>' + r.testo + '</blockquote>'
          + '<cite><strong>' + r.autore + '</strong><br>' + r.ruolo_azienda + ' &mdash; ' + r.settore + '</cite>'
          + (r.data_verifica ? '<small class="rec-verified">Verificata il ' + r.data_verifica + '</small>' : '');
        container.appendChild(card);
      });
    });
}

loadRecensioni('', '');
document.getElementById('filterApply').addEventListener('click', function() {
  loadRecensioni(document.getElementById('filterSettore').value, document.getElementById('filterRating').value);
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
