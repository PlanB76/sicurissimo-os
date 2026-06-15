<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/core81/wallet_service.php';
auth_require();
$user        = auth_user();
$wallet      = WalletService::get_all($user['id']);
$page_title  = 'Dashboard — 81plus.net';
$page_id     = 'dashboard';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<div class="layout-dashboard">

<!-- ══ SIDEBAR ══════════════════════════════════════════════════════════════ -->
<aside class="sidebar81" role="navigation" aria-label="Menu dashboard">
  <div class="sidebar-user">
    <div class="sidebar-avatar" aria-hidden="true">&#128100;</div>
    <div class="sidebar-info">
      <strong><?= e($user['nome']) ?></strong>
      <code class="sic-id"><?= e($user['sic_id']) ?></code>
      <span class="ruolo-badge"><?= ruolo_label($user['ruolo']) ?></span>
      <?php if ($user['genesys_status'] !== 'NONE'): ?>
      <span class="genesys-badge badge-gold"><?= genesys_label($user['genesys_status']) ?></span>
      <?php endif; ?>
      <?php if ($_SESSION['membership'] !== 'NONE'): ?>
      <span class="membership-badge"><?= e($_SESSION['membership']) ?></span>
      <?php endif; ?>
    </div>
  </div>
  <nav class="sidebar-nav">
    <ul role="list">
      <li><a href="<?= BASE_URL ?>/dashboard.php" class="active" aria-current="page">Dashboard</a></li>
      <li><a href="<?= BASE_URL ?>/profilo.php">Profilo</a></li>
      <li><a href="<?= BASE_URL ?>/paygate81.php">Ricarica PV</a></li>
      <li><a href="<?= BASE_URL ?>/membership.php">Membership</a></li>
      <li><a href="<?= BASE_URL ?>/audit.php">Audit 81/08</a></li>
      <li><a href="<?= BASE_URL ?>/preventivo.php">DOC81+ Builder</a></li>
      <li><a href="<?= BASE_URL ?>/academy81.php">Academy</a></li>
      <?php if (in_array($user['ruolo'], ['NETWORKER81','ELITE81','ADMIN81'], true)): ?>
      <li class="sidebar-divider">Network</li>
      <li><a href="<?= BASE_URL ?>/scout81.php">SCOUT81+</a></li>
      <li><a href="<?= BASE_URL ?>/network81.php">NETWORK81+</a></li>
      <li><a href="<?= BASE_URL ?>/cervello3d.php">Compensi3D</a></li>
      <?php endif; ?>
      <?php if (in_array($user['ruolo'], ['ELITE81','ADMIN81'], true)): ?>
      <li class="sidebar-divider">Elite</li>
      <li><a href="<?= BASE_URL ?>/club81.php">Club81+</a></li>
      <li><a href="<?= BASE_URL ?>/franchising.php">Franchising</a></li>
      <?php endif; ?>
      <?php if ($user['ruolo'] === 'ADMIN81'): ?>
      <li class="sidebar-divider">Admin</li>
      <li><a href="<?= BASE_URL ?>/admin.php">Command Center</a></li>
      <?php endif; ?>
      <li class="sidebar-divider"></li>
      <li><a href="<?= BASE_URL ?>/kyc.php">Verifica identita (KYC)</a></li>
      <li><a href="<?= BASE_URL ?>/logout.php">Esci</a></li>
    </ul>
  </nav>
</aside>

<!-- ══ MAIN DASHBOARD ════════════════════════════════════════════════════════ -->
<div class="dashboard-main">

  <?php if (!empty($_GET['benvenuto'])): ?>
  <div class="alert-success" role="status">
    Benvenuto in 81plus.net! Il tuo SIC-ID univoco &egrave;: <strong><?= e($user['sic_id']) ?></strong>
    <?php if ($user['genesys_status'] !== 'NONE'): ?><br>Hai ricevuto <strong><?= number_format(GENESYS_PROMO_PVPLUS, 0, ',', '.') ?> PV+</strong> come early adopter GENESYS81+.<?php endif; ?>
  </div>
  <?php endif; ?>

  <?php if (!empty($_GET['err'])): ?>
  <div class="alert-error" role="alert"><?= e($_GET['err']) ?></div>
  <?php endif; ?>

  <!-- WALLET CARD -->
  <div class="wallet-card card81" id="walletCard" aria-label="Portafoglio 81+">
    <h2 class="wallet-title">Wallet81+</h2>
    <div class="wallet-layers">
      <div class="wallet-layer layer-pv">
        <span class="wl-label">PV</span>
        <span class="wl-val" id="wPV"><?= number_format((float)$wallet['pv_balance'], 2, ',', '.') ?></span>
        <span class="wl-sub">Credito interno 1:1</span>
      </div>
      <div class="wallet-layer layer-pvplus">
        <span class="wl-label">PV+</span>
        <span class="wl-val" id="wPVP"><?= number_format((float)$wallet['pvplus_balance'], 2, ',', '.') ?></span>
        <span class="wl-sub">Reward gamificato</span>
      </div>
      <div class="wallet-layer layer-saf">
        <span class="wl-label">SAF</span>
        <span class="wl-val" id="wSAF"><?= number_format((float)$wallet['saf_balance'], 4, ',', '.') ?></span>
        <span class="wl-sub">Utility Web3</span>
      </div>
      <div class="wallet-layer layer-81x">
        <span class="wl-label">81X</span>
        <span class="wl-val" id="w81X"><?= number_format((float)$wallet['x81_balance'], 4, ',', '.') ?></span>
        <span class="wl-sub">Token BEP-20</span>
      </div>
      <div class="wallet-layer layer-usdt">
        <span class="wl-label">USDT</span>
        <span class="wl-val" id="wUSDT"><?= number_format((float)$wallet['usdt_balance'], 2, ',', '.') ?></span>
        <span class="wl-sub">Custodito BSC</span>
      </div>
    </div>
    <div class="wallet-actions">
      <a href="<?= BASE_URL ?>/paygate81.php" class="btn-primary btn-sm">Ricarica PV</a>
      <a href="<?= BASE_URL ?>/connect-wallet.php" class="btn-secondary btn-sm">Collega Wallet Web3</a>
    </div>
    <p class="wallet-note">I PV non sono denaro elettronico. I PV+ non sono rendimento economico. Non convertibili in euro.</p>
  </div>

  <!-- REFERRAL LINK -->
  <div class="referral-card card81" id="referralSection" aria-label="Il tuo link referral">
    <h2>ReferralLink81+</h2>
    <p>Condividi il tuo link. Per ogni registrazione tramite il tuo link puoi ricevere PV+ bonus.</p>
    <div class="referral-link-box">
      <code id="referralLinkText"><?= e(BASE_URL) ?>/signup.php?ref=<?= e($user['sic_id']) ?></code>
      <button class="btn-secondary btn-sm" id="copyReferralBtn" onclick="copyReferral()">Copia</button>
    </div>
    <div class="referral-stats" id="referralStats">
      <!-- Caricato via JS -->
    </div>
  </div>

  <!-- MODULI DASHBOARD per ruolo -->
  <div class="dash-modules-section">
    <h2>I tuoi strumenti</h2>
    <div id="dashModules" class="modules-grid" aria-live="polite">
      <div class="loading81">Caricamento...</div>
    </div>
  </div>

  <!-- GENESYS STATUS -->
  <?php if ($user['genesys_status'] === 'NONE'): ?>
  <div class="genesys-cta-card card81">
    <h3>Vuoi entrare in GENESYS81+?</h3>
    <p>Candidati ora come early adopter.<?php if (GENESYS_PROMO_ACTIVE): ?> <strong>Promo: +<?= GENESYS_PROMO_PVPLUS ?> PV+ alla candidatura.</strong><?php endif; ?></p>
    <a href="<?= BASE_URL ?>/genesys81.php" class="btn-primary btn-gold">Candidati a GENESYS81+</a>
  </div>
  <?php else: ?>
  <div class="genesys-status-card card81">
    <h3>GENESYS81+ — <?= genesys_label($user['genesys_status']) ?></h3>
    <p>Il tuo status GENESYS81+ e attivo. Hai accesso prioritario ai servizi del programma fondatori.</p>
  </div>
  <?php endif; ?>

</div>
</div>

<script>
var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Carica moduli dashboard
fetch('<?= BASE_URL ?>/api/dashboard-data.php', {
  headers: { 'X-CSRF-Token': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
}).then(function(r){return r.json();}).then(function(d){
  if (!d.ok) return;
  if (d.modules) {
    var grid = document.getElementById('dashModules');
    grid.innerHTML = '';
    d.modules.forEach(function(m) {
      var a = document.createElement('a');
      a.href = m.url || '#';
      a.className = 'module-card' + (m.locked ? ' module-locked' : '') + (m.preview ? ' module-preview' : '');
      a.setAttribute('aria-label', m.label);
      a.innerHTML = '<span class="module-icon" aria-hidden="true">' + m.icon + '</span>'
                  + '<span class="module-label">' + m.label + '</span>'
                  + (m.locked ? '<span class="lock-badge">🔒 ' + (m.req||'') + '</span>' : '');
      grid.appendChild(a);
    });
  }
  if (d.referral && d.referral.stats) {
    var s = d.referral.stats;
    var stats = document.getElementById('referralStats');
    stats.innerHTML = '<div class="ref-stat"><span>' + (s.signup||0) + '</span><small>Registrazioni</small></div>'
                    + '<div class="ref-stat"><span>' + (s.profili_completi||0) + '</span><small>Profili completi</small></div>'
                    + '<div class="ref-stat"><span>' + (s.pvplus_totali||0) + '</span><small>PV+ guadagnati</small></div>';
  }
});

function copyReferral() {
  var text = document.getElementById('referralLinkText').textContent;
  navigator.clipboard.writeText(text).then(function() {
    var btn = document.getElementById('copyReferralBtn');
    btn.textContent = 'Copiato!';
    setTimeout(function(){ btn.textContent = 'Copia'; }, 2000);
  });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
