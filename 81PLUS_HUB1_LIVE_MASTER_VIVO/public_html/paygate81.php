<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
auth_require();
$user = auth_user();
$page_title = 'PayGate81+ — Ricarica PV — 81plus.net';
$page_id    = 'paygate81';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-paygate container-narrow">
  <h1>Ricarica PV</h1>
  <p class="page-intro">Acquista PV (PointValue) per attivare o rinnovare la tua membership e accedere ai servizi 81plus.</p>
  <div class="card81">
    <div class="paygate-info">
      <p>1 PV = 1 Euro. I PV sono crediti interni non rimborsabili (salvo condizioni contrattuali specifiche). Non sono denaro elettronico.</p>
    </div>
    <div class="pv-packs">
      <div class="pv-pack" data-amount="30">
        <div class="pack-value">30 PV</div>
        <div class="pack-price">30,00 EUR</div>
        <button class="btn-secondary select-pack" data-amount="30">Seleziona</button>
      </div>
      <div class="pv-pack pv-pack-popular" data-amount="60">
        <div class="pack-value">60 PV</div>
        <div class="pack-price">60,00 EUR</div>
        <button class="btn-primary select-pack" data-amount="60">Piu popolare</button>
      </div>
      <div class="pv-pack" data-amount="100">
        <div class="pack-value">100 PV</div>
        <div class="pack-price">100,00 EUR</div>
        <button class="btn-secondary select-pack" data-amount="100">Seleziona</button>
      </div>
    </div>
    <form id="paygateForm" method="POST" action="<?= BASE_URL ?>/api/paygate-init.php" style="display:none">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <input type="hidden" name="amount" id="payAmount" value="">
      <div id="paygateProvider" class="provider-selector">
        <h3>Metodo di pagamento</h3>
        <label><input type="radio" name="provider" value="paypal" checked> PayPal</label>
        <label><input type="radio" name="provider" value="stripe"> Carta di credito</label>
        <label><input type="radio" name="provider" value="bonifico"> Bonifico bancario</label>
      </div>
      <button type="submit" class="btn-primary btn-full">Procedi al pagamento</button>
    </form>
  </div>
</main>
<script>
document.querySelectorAll('.select-pack').forEach(function(btn) {
  btn.addEventListener('click', function() {
    document.getElementById('payAmount').value = this.dataset.amount;
    document.getElementById('paygateForm').style.display = 'block';
    this.closest('.pv-pack').classList.add('selected');
  });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>