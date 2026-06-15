<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$logged     = is_logged();
$user_g     = $logged ? auth_user() : [];
$page_title = 'GENESYS81+ — Programma Fondatori — 81plus.net';
$page_id    = 'genesys81';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-genesys">

<!-- HERO GENESYS -->
<section class="genesys-hero section-dark" aria-labelledby="genesys-title">
  <div class="container">
    <div class="genesys-badge-hero">GENESYS81+</div>
    <h1 id="genesys-title">I Fondatori del Sistema</h1>
    <p class="hero-sub">Non un referral. Una missione. I GENESYS81+ costruiscono il sistema dall'interno. Accesso anticipato. Vantaggi esclusivi. Nessuna promessa economica.</p>
    <?php if (GENESYS_PROMO_ACTIVE): ?>
    <div class="promo-banner-genesys">
      <strong>PROMO ATTIVA · <?= GENESYS_PROMO_DAYS ?> GIORNI</strong><br>
      +<?= number_format(GENESYS_PROMO_PVPLUS, 0, ',', '.') ?> PV+ alla candidatura · +<?= number_format(GENESYS_PROFILE_PVPLUS, 0, ',', '.') ?> PV+ al profilo completo
    </div>
    <?php endif; ?>
    <?php if ($logged && $user_g['genesys_status'] !== 'NONE'): ?>
    <div class="alert-success">Sei gia GENESYS81+ — <?= genesys_label($user_g['genesys_status']) ?>. SIC-ID: <code><?= e($user_g['sic_id']) ?></code></div>
    <?php else: ?>
    <a href="#genesys-form" class="btn-primary btn-gold btn-lg">Candidati a GENESYS81+</a>
    <?php endif; ?>
  </div>
</section>

<!-- LIVELLI GENESYS -->
<section class="genesys-levels container section-card" aria-label="Livelli GENESYS81+">
  <h2>I livelli del programma</h2>
  <div class="grid-4">
    <div class="card81 genesys-level-card level-member">
      <div class="level-num">1</div>
      <h3>GENESYS MEMBER</h3>
      <p>Accesso anticipato. Missioni speciali PV+. Badge esclusivo. Priorita nelle novita.</p>
    </div>
    <div class="card81 genesys-level-card level-networker">
      <div class="level-num">2</div>
      <h3>GENESYS NETWORKER</h3>
      <p>Costruisce la rete. SCOUT81+ priority. Pipeline3D avanzata. Materiali commerciali dedicati.</p>
    </div>
    <div class="card81 genesys-level-card level-leader">
      <div class="level-num">3</div>
      <h3>GENESYS LEADER</h3>
      <p>Guida zone territoriali. Accesso ai materiali di lancio. Visibilita dashboard NETWORK81+.</p>
    </div>
    <div class="card81 genesys-level-card level-founder">
      <div class="level-num">4</div>
      <h3>GENESYS FOUNDER</h3>
      <p>I pionieri del sistema. 200 slot PIX81+ Founder riservati 90 giorni. Massima priorita.</p>
    </div>
  </div>
</section>

<!-- SPECIAL PACK PREVIEW -->
<section class="genesys-special-pack container" aria-label="Special Pack GENESYS Founder">
  <div class="card81 special-pack-card">
    <div class="pack-tag-gold">GENESYS FOUNDER SPECIAL PACK · PREVIEW</div>
    <h2>PIX81+ Special Pack</h2>
    <p class="pack-price-preview">750 PV</p>
    <ul class="pack-contents">
      <li>1 slot PIX81+ Founder (da 200 disponibili, 90 giorni riservati)</li>
      <li>+1.000 PV+ bonus</li>
      <li>1 albero Green81+ dedicato</li>
      <li>1 NFT81+ utility (whitelist)</li>
      <li>Whitelist 81X token</li>
      <li>Accesso early 81plus.space</li>
    </ul>
    <p class="pack-disclaimer">PIX81+ non e un prodotto di investimento. E uno spazio digitale con utility, visibilita e accesso nel sistema 81plus. Non garantisce rendimento economico.</p>
    <p class="pack-coming">Disponibile dopo attivazione status GENESYS FOUNDER.</p>
  </div>
</section>

<!-- FORM CANDIDATURA -->
<?php if (!$logged || $user_g['genesys_status'] === 'NONE'): ?>
<section class="genesys-form container" id="genesys-form" aria-label="Form candidatura GENESYS81+">
  <div class="card81">
    <h2>Invia la tua candidatura</h2>
    <?php if (!$logged): ?>
    <p>Hai gia un account? <a href="<?= BASE_URL ?>/login.php?redir=/genesys81.php">Accedi prima</a> per collegare la candidatura al tuo SIC-ID.</p>
    <?php endif; ?>
    <form id="genesysForm" method="POST" action="<?= BASE_URL ?>/api/genesys-apply.php">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <?php if ($logged): ?>
      <input type="hidden" name="user_id" value="<?= $user_g['id'] ?>">
      <div class="form-group">
        <label>Il tuo SIC-ID</label>
        <input type="text" value="<?= e($user_g['sic_id']) ?>" disabled>
      </div>
      <?php endif; ?>
      <div class="form-group">
        <label for="g_nome">Nome e Cognome *</label>
        <input type="text" id="g_nome" name="nome" required value="<?= $logged ? e($user_g['nome']) : '' ?>">
      </div>
      <div class="form-group">
        <label for="g_email">Email *</label>
        <input type="email" id="g_email" name="email" required value="<?= $logged ? e($user_g['email']) : '' ?>" <?= $logged ? 'readonly' : '' ?>>
      </div>
      <div class="form-group">
        <label for="g_settore">Settore / Ruolo attuale</label>
        <select id="g_settore" name="settore">
          <option value="">Seleziona</option>
          <option value="consulente_sicurezza">Consulente Sicurezza / RSPP</option>
          <option value="imprenditore">Imprenditore</option>
          <option value="commerciale">Commerciale / Agente</option>
          <option value="formatore">Formatore</option>
          <option value="altro">Altro</option>
        </select>
      </div>
      <div class="form-group">
        <label for="g_ruolo">Livello GENESYS richiesto</label>
        <select id="g_ruolo" name="ruolo_richiesto">
          <option value="GENESYS_MEMBER">GENESYS MEMBER</option>
          <option value="GENESYS_NETWORKER">GENESYS NETWORKER</option>
          <option value="GENESYS_LEADER">GENESYS LEADER</option>
          <option value="GENESYS_FOUNDER">GENESYS FOUNDER</option>
        </select>
      </div>
      <div class="form-group">
        <label for="g_nota">Raccontaci brevemente la tua esperienza nel settore</label>
        <textarea id="g_nota" name="nota" rows="3" placeholder="Perche vuoi entrare in GENESYS81+?"></textarea>
      </div>
      <div id="genesysError" class="alert-error" style="display:none"></div>
      <div id="genesysSuccess" class="alert-success" style="display:none"></div>
      <button type="submit" class="btn-primary btn-full btn-gold" id="genesysBtn">Invia candidatura GENESYS81+</button>
    </form>
  </div>
</section>
<?php endif; ?>

</main>
<script>
(function() {
  var form = document.getElementById('genesysForm');
  if (!form) return;
  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    var btn = document.getElementById('genesysBtn');
    var err = document.getElementById('genesysError');
    var ok  = document.getElementById('genesysSuccess');
    btn.disabled = true; btn.textContent = 'Invio in corso...';
    err.style.display = 'none'; ok.style.display = 'none';
    try {
      var res = await fetch(form.action, { method:'POST', body: new FormData(form) });
      var data = await res.json();
      if (data.ok) {
        ok.innerHTML = data.msg + (data.promo ? '<br><strong>' + data.promo + '</strong>' : '');
        ok.style.display = 'block';
        form.style.display = 'none';
      } else { err.textContent = data.error||'Errore. Riprova.'; err.style.display='block'; btn.disabled=false; btn.textContent='Invia candidatura GENESYS81+'; }
    } catch(ex) { err.textContent='Errore di rete. Riprova.'; err.style.display='block'; btn.disabled=false; btn.textContent='Invia candidatura GENESYS81+'; }
  });
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
