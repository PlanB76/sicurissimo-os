<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
auth_require('ADMIN81');
$user = auth_user();
$page_title = 'Admin Command Center — 81plus.net';
$page_id    = 'admin';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-admin layout-dashboard">
  <div class="container">
    <h1>Admin Command Center 81+</h1>
    <p class="page-intro">Accesso riservato ADMIN81. Ogni azione viene registrata nell'audit log prima dell'esecuzione.</p>
    <div class="admin-panels grid-3">
      <div class="card81"><h3>Utenti</h3><p>Gestisci ruoli, verifica KYC, blocca/sblocca account.</p><button class="btn-secondary admin-action" data-action="list_users">Vedi utenti</button></div>
      <div class="card81"><h3>Wallet</h3><p>Monitora saldi PV, correggi anomalie, audit transazioni.</p><button class="btn-secondary admin-action" data-action="wallet_report">Report wallet</button></div>
      <div class="card81"><h3>GENESYS81+</h3><p>Approva candidature, assegna status, gestisci fondatori.</p><button class="btn-secondary admin-action" data-action="genesys_list">Candidature</button></div>
      <div class="card81"><h3>PIX81+ Slots</h3><p>Stato 1000 slot, slot Founder, assegnazioni.</p><button class="btn-secondary admin-action" data-action="pix_status">Stato PIX81+</button></div>
      <div class="card81"><h3>Scout81+ Prospect</h3><p>Gestisci il catalogo prospect, assegnazioni, punteggi.</p><button class="btn-secondary admin-action" data-action="scout_admin">Scout Admin</button></div>
      <div class="card81"><h3>Audit Log</h3><p>Log completo di tutte le azioni admin. Immutabile.</p><button class="btn-secondary admin-action" data-action="audit_log">Vedi log</button></div>
    </div>
    <div id="adminResult" class="admin-result" aria-live="polite"></div>
  </div>
</main>
<script>
var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
document.querySelectorAll('.admin-action').forEach(function(btn) {
  btn.addEventListener('click', function() {
    var action = this.dataset.action;
    var result = document.getElementById('adminResult');
    result.innerHTML = '<div class="loading81">Esecuzione...</div>';
    fetch('<?= BASE_URL ?>/api/admin-action.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken },
      body: JSON.stringify({ action: action })
    }).then(function(r){ return r.json(); }).then(function(d){
      result.innerHTML = d.ok ? (d.html || '<p>OK</p>') : '<p class="alert-error">' + (d.error || 'Errore') + '</p>';
    });
  });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>