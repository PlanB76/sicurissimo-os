<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
auth_require();
$user       = auth_user();
$membership = $_SESSION['membership'] ?? 'NONE';
$is_networker = in_array($user['ruolo'], ['NETWORKER81','ELITE81','ADMIN81'], true);
$page_title = 'NETWORK81+ — La tua rete commerciale — 81plus.net';
$page_id    = 'network81';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-network81 layout-dashboard">
<div class="container">
  <h1>NETWORK81+</h1>
  <p class="page-intro">La tua rete commerciale per la sicurezza aziendale. SCOUT81+ trova il mercato. PLP81+ consegna prospect profilati. NETWORK81+ lavora il mercato.</p>

  <?php if (!$is_networker): ?>
  <!-- UPGRADE GATE -->
  <div class="upgrade-gate card81">
    <div class="gate-icon">🕸️</div>
    <h2>NETWORK81+ disponibile da NETWORKER81+</h2>
    <p>Attiva PRO+ o ELITE+ per accedere al modulo commerciale completo: pipeline 3D, territory map, piano compensi.</p>
    <a href="<?= BASE_URL ?>/membership.php?piano=PRO%2B" class="btn-primary">Attiva PRO+ e diventa NETWORKER81+</a>
    <a href="<?= BASE_URL ?>/genesys81.php" class="btn-secondary btn-gold">Candidati a GENESYS81+</a>
  </div>

  <!-- PLP PREVIEW LOCKED -->
  <section class="plp-preview-locked" aria-label="PLP81+ preview">
    <h2>PLP81+ — Pack Prospect Profilati (Preview)</h2>
    <p>I PLP Pack consegnano prospect profilati per il tuo settore e zona. Il risultato dipende dalla tua attivita commerciale.</p>
    <div class="plp-grid-preview">
      <?php
      $packs_preview = [
        ['PLP Start', '100 prospect', '29 PV'],
        ['PLP Pro',   '250 prospect', '49 PV'],
        ['PLP Max',   '500 prospect', '99 PV'],
        ['PLP HACCP', '200 prospect', '39 PV'],
        ['PLP Edilizia', '200 prospect', '39 PV'],
      ];
      foreach ($packs_preview as $p):
      ?>
      <div class="plp-card card81 plp-locked">
        <h3><?= e($p[0]) ?></h3>
        <div class="plp-count"><?= e($p[1]) ?></div>
        <div class="plp-price"><?= e($p[2]) ?></div>
        <div class="lock-overlay">🔒 Richiede NETWORKER81+</div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <?php else: ?>
  <!-- NETWORK OPERATIVO -->
  <div class="network-panels grid-2">
    <div class="card81">
      <h3>Pipeline3D81+</h3>
      <p>Visualizza la tua rete commerciale in 3D. Traccia ogni prospect dalla prima visita alla chiusura.</p>
      <a href="<?= BASE_URL ?>/ecosistema3d.php" class="btn-primary">Apri Pipeline3D</a>
    </div>
    <div class="card81">
      <h3>TerritoryMap81+</h3>
      <p>La tua zona territoriale. Prospect attivi, prospect assegnati, stato avanzamento.</p>
      <a href="<?= BASE_URL ?>/scout81.php#territory" class="btn-secondary">Vedi mappa</a>
    </div>
    <div class="card81">
      <h3>Piano Compensi81+</h3>
      <p>Riepilogo compensi maturati, piano carriera e stato equilibrio economico.</p>
      <a href="<?= BASE_URL ?>/cervello3d.php" class="btn-secondary">Compensi3D</a>
    </div>
    <div class="card81">
      <h3>Piano Marketing81+</h3>
      <p>Materiali, script, template per la tua attivita commerciale nel network.</p>
      <a href="#marketing" class="btn-secondary">Materiali</a>
    </div>
  </div>

  <!-- PLP CATALOGO OPERATIVO -->
  <section class="plp-catalog" id="plp" aria-label="PLP81+ catalogo completo">
    <h2>PLP81+ — Acquista prospect profilati</h2>
    <p class="plp-disclaimer">I PLP Pack consegnano prospect profilati per il tuo settore. Non promettono conversioni. Il risultato dipende dalla tua attivita commerciale etica e professionale.</p>
    <div id="plpCatalogFull" class="plp-grid">
      <div class="loading81">Caricamento catalogo...</div>
    </div>
  </section>

  <!-- LEAD ASSEGNATI -->
  <section class="leads-assegnati" aria-label="I tuoi lead">
    <h2>I tuoi lead</h2>
    <div id="leadsContainer" class="leads-list" aria-live="polite">
      <div class="loading81">Caricamento lead...</div>
    </div>
  </section>
  <?php endif; ?>

</div>
</main>

<?php if ($is_networker): ?>
<script>
var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
var headers = { 'X-CSRF-Token': csrfToken, 'X-Requested-With': 'XMLHttpRequest' };

// Carica catalogo PLP
fetch('<?= BASE_URL ?>/api/plp-catalog.php', { headers: headers })
  .then(function(r){return r.json();})
  .then(function(d){
    if(!d.ok||!d.packs) return;
    var grid = document.getElementById('plpCatalogFull');
    grid.innerHTML = '';
    d.packs.forEach(function(p){
      var card = document.createElement('div');
      card.className = 'plp-card card81';
      card.innerHTML = '<h3>' + p.nome + '</h3>'
        + '<div class="plp-count">' + p.num_prospect + ' prospect</div>'
        + '<div class="plp-price">' + p.prezzo_pv + ' PV</div>'
        + '<p>' + p.descrizione + '</p>'
        + '<button class="btn-primary btn-sm buy-plp" data-id="' + p.id + '" data-pv="' + p.prezzo_pv + '">Acquista</button>';
      grid.appendChild(card);
    });
    // Buy PLP handler
    document.querySelectorAll('.buy-plp').forEach(function(btn){
      btn.addEventListener('click', async function(){
        var id = this.dataset.id;
        var pv = this.dataset.pv;
        if (!confirm('Acquistare questo pack per ' + pv + ' PV?')) return;
        this.disabled = true;
        var fd = new FormData(); fd.append('csrf_token', csrfToken); fd.append('pack_id', id);
        var res = await fetch('<?= BASE_URL ?>/api/plp-buy-pack.php', { method:'POST', headers:{'X-CSRF-Token':csrfToken}, body:fd });
        var data = await res.json();
        alert(data.ok ? 'Pack acquistato! ' + data.msg : (data.error||'Errore'));
        if (data.ok) location.reload();
        else this.disabled = false;
      });
    });
  });

// Carica lead
fetch('<?= BASE_URL ?>/api/networker-leads.php', { headers: headers })
  .then(function(r){return r.json();})
  .then(function(d){
    var c = document.getElementById('leadsContainer');
    if(!d.ok||!d.leads||!d.leads.length) { c.innerHTML='<p>Nessun lead assegnato al momento.</p>'; return; }
    c.innerHTML = d.leads.map(function(l){
      return '<div class="lead-card card81"><strong>'+l.ragione_sociale+'</strong><br>'+l.comune+' ('+l.provincia+') &mdash; Score: '+l.score+'</div>';
    }).join('');
  });
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
