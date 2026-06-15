<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
auth_require();
$user       = auth_user();
$membership = $_SESSION['membership'] ?? 'NONE';
$has_access = in_array($membership, ['PRO+','ELITE+'], true) || in_array($user['ruolo'], ['NETWORKER81','ELITE81','ADMIN81'], true);
$page_title = 'SCOUT81+ — Trova il tuo mercato — 81plus.net';
$page_id    = 'scout81';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-scout81 layout-dashboard">
<div class="container">
  <div class="page-header">
    <h1>SCOUT81+</h1>
    <p class="page-tagline">SCOUT81+ trova il mercato. PLP81+ consegna prospect profilati. NETWORK81+ lavora il mercato.</p>
  </div>

  <div class="scout-disclaimer card81 card-bordered">
    <strong>Nota operativa:</strong> SCOUT81+ non vende clienti. Non promette clienti. Non promette conversioni. Mostra prospect e opportunita da lavorare secondo regolamento, privacy e vendita etica.
  </div>

  <?php if (!$has_access): ?>
  <!-- UPGRADE PROMPT -->
  <div class="upgrade-gate card81">
    <div class="gate-icon">🎯</div>
    <h2>SCOUT81+ disponibile da PRO+</h2>
    <p>Con PRO+ accedi alla mappa prospect Italia, ai filtri avanzati per settore e zona, e al catalogo PLP81+ pack.</p>
    <a href="<?= BASE_URL ?>/membership.php?piano=PRO%2B" class="btn-primary">Attiva PRO+ per accedere</a>
    <a href="<?= BASE_URL ?>/paygate81.php" class="btn-secondary">Ricarica PV prima</a>
  </div>

  <!-- PREVIEW LOCKED -->
  <div class="scout-preview-locked">
    <h2>Anteprima SCOUT81+</h2>
    <div class="scout-filters-preview card81 blur-locked" aria-hidden="true">
      <div class="filter-row">
        <select disabled><option>Tutte le regioni</option></select>
        <select disabled><option>Tutti i settori</option></select>
        <select disabled><option>ATECO</option></select>
        <select disabled><option>Livello rischio</option></select>
        <button class="btn-primary" disabled>Cerca</button>
      </div>
    </div>
    <div class="map-preview-locked card81" aria-label="Mappa SCOUT81+ — accesso con PRO+">
      <div class="map-placeholder-locked">
        <span aria-hidden="true">🗺️</span>
        <p>Mappa prospect Italia — disponibile con PRO+</p>
      </div>
    </div>
  </div>

  <?php else: ?>
  <!-- SCOUT OPERATIVO -->
  <div class="scout-filters card81">
    <h2>Filtri di ricerca</h2>
    <div class="filter-row" id="scoutFilters">
      <select id="fRegione"><option value="">Tutte le regioni</option>
        <?php foreach (['Abruzzo','Basilicata','Calabria','Campania','Emilia-Romagna','Friuli-VG','Lazio','Liguria','Lombardia','Marche','Molise','Piemonte','Puglia','Sardegna','Sicilia','Toscana','Trentino-AA','Umbria','Val d\'Aosta','Veneto'] as $r): ?>
        <option><?= e($r) ?></option><?php endforeach; ?>
      </select>
      <select id="fSettore"><option value="">Tutti i settori</option>
        <option value="manifattura">Manifattura</option>
        <option value="food">Food &amp; HACCP</option>
        <option value="cantiere">Cantiere / Edilizia</option>
        <option value="ufficio">Ufficio</option>
        <option value="retail">Retail</option>
        <option value="professioni">Professionisti</option>
        <option value="artigiani">Artigiani</option>
      </select>
      <select id="fScore">
        <option value="">Qualsiasi score</option>
        <option value="hot">Hot (70+)</option>
        <option value="warm">Warm (40-69)</option>
        <option value="cold">Cold (0-39)</option>
      </select>
      <button class="btn-primary" id="scoutSearchBtn">Cerca prospect</button>
      <button class="btn-secondary" id="scoutSaveFilter">Salva filtro</button>
    </div>
  </div>

  <div id="scoutMapContainer" class="scout-map-container">
    <div id="scoutMap" style="height:420px;border-radius:8px;" aria-label="Mappa SCOUT81+ prospect Italia"></div>
  </div>

  <div id="scoutList" class="scout-results" aria-live="polite">
    <div class="scout-placeholder">Usa i filtri per cercare prospect nella tua zona.</div>
  </div>

  <!-- PLP81+ PACK PREVIEW -->
  <section class="plp-preview" aria-label="PLP81+ Pack Catalog">
    <h2>PLP81+ — Prospect profilati pronti</h2>
    <p>I PLP Pack consegnano prospect profilati. Non promettono conversioni. Il risultato dipende dalla tua attivita commerciale.</p>
    <div id="plpCatalog" class="plp-grid">
      <div class="loading81">Caricamento catalogo...</div>
    </div>
  </section>
  <?php endif; ?>

</div>
</main>

<?php if ($has_access): ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="<?= BASE_URL ?>/assets/js/scout81map.js" defer></script>
<script>
var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Carica catalogo PLP
fetch('<?= BASE_URL ?>/api/plp-catalog.php', {
  headers: { 'X-CSRF-Token': csrfToken }
}).then(function(r){return r.json();}).then(function(d){
  if(!d.ok||!d.packs) return;
  var grid = document.getElementById('plpCatalog');
  grid.innerHTML = '';
  d.packs.forEach(function(p) {
    var card = document.createElement('div');
    card.className = 'plp-card card81';
    card.innerHTML = '<h3>' + p.nome + '</h3>'
      + '<div class="plp-count">' + p.num_prospect + ' prospect</div>'
      + '<div class="plp-price">' + p.prezzo_pv + ' PV</div>'
      + '<p>' + p.descrizione + '</p>'
      + '<button class="btn-primary btn-sm buy-plp" data-id="' + p.id + '">Acquista pack</button>';
    grid.appendChild(card);
  });
});

// Search
document.getElementById('scoutSearchBtn').addEventListener('click', function() {
  var params = new URLSearchParams({
    regione: document.getElementById('fRegione').value,
    settore: document.getElementById('fSettore').value,
    score:   document.getElementById('fScore').value,
  });
  var list = document.getElementById('scoutList');
  list.innerHTML = '<div class="loading81">Ricerca in corso...</div>';
  fetch('<?= BASE_URL ?>/api/scout-prospects.php?' + params, {
    headers: { 'X-CSRF-Token': csrfToken }
  }).then(function(r){return r.json();}).then(function(d){
    if(!d.ok) { list.innerHTML='<p class="alert-error">'+(d.error||'Errore')+'</p>'; return; }
    if(!d.prospects||!d.prospects.length) { list.innerHTML='<p>Nessun prospect trovato con questi filtri.</p>'; return; }
    list.innerHTML = d.prospects.map(function(p) {
      return '<div class="scout-card card81">'
        + '<div class="scout-score score-' + (p.score>=70?'hot':p.score>=40?'warm':'cold') + '">' + p.score + '</div>'
        + '<div class="scout-info"><strong>' + p.ragione_sociale + '</strong><br>'
        + p.comune + ' (' + p.provincia + ') &mdash; ' + p.settore + '</div>'
        + '<button class="btn-secondary btn-sm scout-detail" data-id="' + p.id + '">Dettaglio</button>'
        + '</div>';
    }).join('');
  });
});
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
