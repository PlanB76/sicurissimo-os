<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/core81/wallet_service.php';
auth_require();
$user       = auth_user();
$wallet     = WalletService::get_all($user['id']);
$pv_saldo   = (float)$wallet['pv_balance'];
$membership = $_SESSION['membership'] ?? 'NONE';
$page_title = 'PayGate81+ — Ricarica PV e Membership';
$page_id    = 'paygate81';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-paygate container">
  <div class="paygate-header">
    <h1>PayGate81+</h1>
    <div class="saldo-attuale card81">
      <span class="saldo-label">Saldo PV attuale</span>
      <span class="saldo-val"><?= number_format($pv_saldo, 2, ',', '.') ?> PV</span>
      <span class="saldo-sub">1 PV = 1 Euro (valore convenzionale interno)</span>
    </div>
  </div>

  <!-- SEZIONE 1: RICARICA PV -->
  <section class="paygate-section card81" aria-label="Ricarica PV">
    <h2>Ricarica PV</h2>
    <p>I PV sono crediti interni non rimborsabili (salvo condizioni contrattuali specifiche). Non sono denaro elettronico ai sensi della Direttiva 2009/110/CE.</p>
    <div class="pv-packs">
      <?php
      $packs = [
        ['amount'=>30,  'label'=>'30 PV',  'euro'=>'30,00 EUR', 'pop'=>false],
        ['amount'=>60,  'label'=>'60 PV',  'euro'=>'60,00 EUR', 'pop'=>true],
        ['amount'=>100, 'label'=>'100 PV', 'euro'=>'100,00 EUR','pop'=>false],
        ['amount'=>250, 'label'=>'250 PV', 'euro'=>'250,00 EUR','pop'=>false],
      ];
      foreach ($packs as $p):
      ?>
      <div class="pv-pack <?= $p['pop'] ? 'pv-pack-popular' : '' ?>" data-amount="<?= $p['amount'] ?>">
        <div class="pack-value"><?= $p['label'] ?></div>
        <div class="pack-price"><?= $p['euro'] ?></div>
        <?php if ($p['pop']): ?><div class="pack-badge">Piu scelto</div><?php endif; ?>
        <button class="<?= $p['pop'] ? 'btn-primary' : 'btn-secondary' ?> select-pack" data-amount="<?= $p['amount'] ?>">Seleziona</button>
      </div>
      <?php endforeach; ?>
    </div>

    <form id="paygateForm" method="POST" action="<?= BASE_URL ?>/api/paygate-create-order.php" style="display:none">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <input type="hidden" name="amount" id="payAmount" value="">
      <div class="provider-selector">
        <h3>Metodo di pagamento</h3>
        <label class="radio-label"><input type="radio" name="provider" value="paypal" checked> PayPal</label>
        <label class="radio-label"><input type="radio" name="provider" value="stripe"> Carta di credito (Stripe)</label>
        <label class="radio-label"><input type="radio" name="provider" value="bonifico"> Bonifico bancario</label>
      </div>
      <div id="paygateError" class="alert-error" style="display:none"></div>
      <button type="submit" class="btn-primary btn-full" id="paygateBtn">Procedi al pagamento</button>
    </form>
  </section>

  <!-- SEZIONE 2: ATTIVA MEMBERSHIP -->
  <section class="paygate-section card81" aria-label="Attiva Membership">
    <h2>Attiva Membership con PV</h2>
    <p>Usa i tuoi PV per attivare o rinnovare la membership. Il pagamento avviene direttamente dal tuo saldo PV.</p>
    <div class="grid-3">
      <?php foreach (MEMBERSHIP_PLANS as $piano => $plan):
        $abbastanza = $pv_saldo >= $plan['pv'];
        $is_active  = $membership === $piano;
        $is_prima   = !$is_active; // semplificato, poi usa MembershipService::is_prima_attivazione
        $bonus      = $is_prima ? $plan['pvplus_prima'] : $plan['pvplus_rinnovo'];
      ?>
      <div class="membership-activate-card card81 <?= $is_active ? 'card-active' : '' ?>">
        <?php if ($is_active): ?><div class="card-active-badge">ATTIVA</div><?php endif; ?>
        <div class="plan-badge <?= $piano==='PRO+' ? 'badge-orange' : ($piano==='ELITE+' ? 'badge-gold' : '') ?>"><?= e($piano) ?></div>
        <div class="plan-pv"><?= number_format($plan['pv'], 2, ',', '.') ?> PV/mese</div>
        <div class="plan-bonus">+<?= number_format($bonus, 0, ',', '.') ?> PV+ <?= $is_prima ? '(prima attivazione)' : '(rinnovo)' ?></div>
        <div class="plan-saldo <?= $abbastanza ? 'saldo-ok' : 'saldo-ko' ?>">
          <?= $abbastanza ? '✓ Saldo sufficiente' : '✗ Saldo insufficiente' ?>
        </div>
        <?php if ($abbastanza): ?>
        <button class="btn-primary activate-membership" data-piano="<?= e($piano) ?>"
          <?= $is_active ? 'disabled' : '' ?>>
          <?= $is_active ? 'Gia attiva' : 'Attiva ' . e($piano) ?>
        </button>
        <?php else: ?>
        <a href="#paygate-form" class="btn-secondary btn-sm" onclick="highlightRecharge()">Ricarica prima</a>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <p class="plan-note">I PV+ assegnati non sono rendimento economico. Non sono convertibili in euro.</p>
  </section>
</main>

<div id="membershipModal" class="modal81" style="display:none" role="dialog" aria-modal="true">
  <div class="modal81-inner card81">
    <h3 id="modalTitle">Attivazione Membership</h3>
    <div id="modalBody"></div>
    <button class="btn-secondary" onclick="document.getElementById('membershipModal').style.display='none'">Chiudi</button>
  </div>
</div>

<script>
var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

document.querySelectorAll('.select-pack').forEach(function(btn) {
  btn.addEventListener('click', function() {
    document.getElementById('payAmount').value = this.dataset.amount;
    document.getElementById('paygateForm').style.display = 'block';
    document.querySelectorAll('.pv-pack').forEach(function(p){ p.classList.remove('selected'); });
    this.closest('.pv-pack').classList.add('selected');
    document.getElementById('paygateForm').scrollIntoView({behavior:'smooth'});
  });
});

document.getElementById('paygateForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  var btn = document.getElementById('paygateBtn');
  var err = document.getElementById('paygateError');
  btn.disabled = true; btn.textContent = 'Creazione ordine...';
  err.style.display = 'none';
  try {
    var res = await fetch(this.action, { method:'POST', body: new FormData(this) });
    var data = await res.json();
    if (data.ok) { window.location.href = data.redirect; }
    else { err.textContent = data.error||'Errore. Riprova.'; err.style.display='block'; btn.disabled=false; btn.textContent='Procedi al pagamento'; }
  } catch(ex) { err.textContent='Errore di rete.'; err.style.display='block'; btn.disabled=false; btn.textContent='Procedi al pagamento'; }
});

document.querySelectorAll('.activate-membership').forEach(function(btn) {
  btn.addEventListener('click', async function() {
    var piano = this.dataset.piano;
    if (!confirm('Attivare ' + piano + '? Verranno scalati i PV necessari dal tuo saldo.')) return;
    this.disabled = true; this.textContent = 'Attivazione...';
    var fd = new FormData();
    fd.append('csrf_token', csrfToken);
    fd.append('piano', piano);
    try {
      var res = await fetch('<?= BASE_URL ?>/api/membership-activate.php', { method:'POST', body:fd });
      var data = await res.json();
      var modal = document.getElementById('membershipModal');
      document.getElementById('modalBody').innerHTML = data.ok
        ? '<p class="alert-success">' + data.msg + '</p><p>Ricarica la pagina per aggiornare il tuo saldo.</p>'
        : '<p class="alert-error">' + (data.error||'Errore') + '</p>';
      modal.style.display = 'flex';
      if (data.ok) setTimeout(function(){ location.reload(); }, 2500);
    } catch(ex) { this.disabled=false; this.textContent='Attiva '+piano; alert('Errore di rete. Riprova.'); }
  });
});

function highlightRecharge() {
  document.querySelector('.pv-packs').scrollIntoView({behavior:'smooth'});
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
