<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
auth_require();
$user = auth_user();
$page_title = 'Dashboard — 81plus.net';
$page_id    = 'dashboard';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-dashboard layout-dashboard">
  <aside class="sidebar81" role="navigation" aria-label="Menu dashboard">
    <div class="sidebar-user">
      <div class="sidebar-avatar" aria-hidden="true">&#128737;</div>
      <div class="sidebar-info">
        <strong><?= e($user['nome']) ?></strong>
        <span class="sic-id"><?= e($user['sic_id']) ?></span>
        <span class="ruolo-badge"><?= ruolo_label($user['ruolo']) ?></span>
        <?php if ($user['genesys_status'] !== 'NONE'): ?>
        <span class="genesys-badge"><?= genesys_label($user['genesys_status']) ?></span>
        <?php endif; ?>
      </div>
    </div>
    <nav class="sidebar-nav">
      <ul role="list">
        <li><a href="<?= BASE_URL ?>/dashboard.php" class="active">Dashboard</a></li>
        <li><a href="<?= BASE_URL ?>/profilo.php">Profilo</a></li>
        <li><a href="<?= BASE_URL ?>/membership.php">Membership</a></li>
        <li><a href="<?= BASE_URL ?>/audit.php">Audit 81/08</a></li>
        <li><a href="<?= BASE_URL ?>/preventivo.php">DOC81+ Builder</a></li>
        <li><a href="<?= BASE_URL ?>/academy81.php">Academy</a></li>
        <?php if (in_array($user['ruolo'], ['NETWORKER81','ELITE81','ADMIN81'], true)): ?>
        <li class="sidebar-divider">Network</li>
        <li><a href="<?= BASE_URL ?>/scout81.php">SCOUT81+</a></li>
        <li><a href="<?= BASE_URL ?>/network81.php">NETWORK81+</a></li>
        <?php endif; ?>
        <?php if ($user['ruolo'] === 'ADMIN81'): ?>
        <li class="sidebar-divider">Admin</li>
        <li><a href="<?= BASE_URL ?>/admin.php">Command Center</a></li>
        <?php endif; ?>
        <li class="sidebar-divider"></li>
        <li><a href="<?= BASE_URL ?>/logout.php">Esci</a></li>
      </ul>
    </nav>
  </aside>
  <div class="dashboard-main">
    <?php if (!empty($_GET['benvenuto'])): ?>
    <div class="alert-success">Benvenuto in 81plus.net! Il tuo SIC-ID e: <strong><?= e($user['sic_id']) ?></strong></div>
    <?php endif; ?>
    <!-- Wallet Card -->
    <div class="wallet-card card81" id="walletCard" aria-label="Portafoglio 81+">
      <div class="wallet-layers">
        <div class="wallet-layer layer-pv"><span class="wl-label">PV</span><span class="wl-val" id="wPV">--</span></div>
        <div class="wallet-layer layer-pvplus"><span class="wl-label">PV+</span><span class="wl-val" id="wPVP">--</span></div>
        <div class="wallet-layer layer-saf"><span class="wl-label">SAF</span><span class="wl-val" id="wSAF">--</span></div>
        <div class="wallet-layer layer-81x"><span class="wl-label">81X</span><span class="wl-val" id="w81X">--</span></div>
        <div class="wallet-layer layer-usdt"><span class="wl-label">USDT</span><span class="wl-val" id="wUSDT">--</span></div>
      </div>
    </div>
    <!-- Moduli dinamici per ruolo -->
    <div id="dashModules" class="dashboard-modules" aria-live="polite">
      <div class="loading81">Caricamento moduli...</div>
    </div>
  </div>
</main>
<script>
(function() {
  var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
  fetch('<?= BASE_URL ?>/api/dashboard-data.php', {
    headers: { 'X-CSRF-Token': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(function(r) { return r.json(); })
  .then(function(d) {
    if (!d.ok) return;
    if (d.wallet) {
      document.getElementById('wPV').textContent   = d.wallet.pv   || '0';
      document.getElementById('wPVP').textContent  = d.wallet.pvplus || '0';
      document.getElementById('wSAF').textContent  = d.wallet.saf  || '0';
      document.getElementById('w81X').textContent  = d.wallet['81x'] || '0';
      document.getElementById('wUSDT').textContent = d.wallet.usdt || '0';
    }
    if (d.html) {
      document.getElementById('dashModules').innerHTML = d.html;
    }
  });
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>