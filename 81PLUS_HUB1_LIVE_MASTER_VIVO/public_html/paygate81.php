<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/core81/wallet_service.php';
require_once __DIR__ . '/core81/paygate_service.php';
auth_require();

$user        = auth_user();
$wallet      = WalletService::get_all($user['id']);
$pv_saldo    = (float)$wallet['pv_balance'];
$membership  = $_SESSION['membership'] ?? 'NONE';
$page_title  = 'PayGate81+ — Ricarica PV';
$page_id     = 'paygate81';

// Passaggi post-pagamento
$step       = trim($_GET['step'] ?? '');
$order_code = trim($_GET['order'] ?? '');
$success    = isset($_GET['success']);
$error_key  = trim($_GET['error'] ?? '');

// Dati step crypto / bonifico
$order_data   = null;
$crypto_addrs = [];
$bonifico_data= [];

if (in_array($step, ['crypto', 'bonifico'], true) && $order_code) {
    $order_data = PaygateService::getOrder($order_code, (int)$user['id']);
    if ($step === 'crypto')   $crypto_addrs  = PaygateService::cryptoAddresses();
    if ($step === 'bonifico') $bonifico_data = PaygateService::bonificoData($order_code, (float)($order_data['importo_euro'] ?? 0));
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-paygate container">

  <div class="paygate-header">
    <h1>PayGate81+</h1>
    <div class="saldo-attuale card81">
      <span class="saldo-label">Saldo PV attuale</span>
      <span class="saldo-val"><?= number_format($pv_saldo, 2, ',', '.') ?> PV</span>
      <span class="saldo-sub">1 PV = 1 Euro (credito interno non rimborsabile)</span>
    </div>
  </div>

<?php if ($success && $order_code): ?>
  <!-- SUCCESSO -->
  <div class="paygate-result card81 result-ok">
    <div class="result-icon">✓</div>
    <h2>Pagamento ricevuto.</h2>
    <p>I tuoi PV sono stati accreditati. Il saldo si aggiorna entro pochi secondi.</p>
    <p class="order-ref">Riferimento ordine: <code><?= htmlspecialchars($order_code) ?></code></p>
    <a href="<?= BASE_URL ?>/dashboard.php" class="btn-primary">Vai alla dashboard</a>
  </div>

<?php elseif ($error_key): ?>
  <!-- ERRORE -->
  <?php $errors = [
    'annullato'            => 'Pagamento annullato.',
    'ordine_non_trovato'   => 'Ordine non trovato. Contatta il supporto.',
    'pagamento_non_completato' => 'Il pagamento non risulta completato su PayPal.',
    'accredito_fallito'    => 'Pagamento ricevuto ma accredito in corso. Ricontrollare tra 5 minuti.',
    'errore_tecnico'       => 'Errore tecnico. Scrivi a supporto@81plus.net con il riferimento ordine.',
    'parametri_mancanti'   => 'Dati mancanti. Riprova.',
  ]; ?>
  <div class="paygate-result card81 result-err">
    <div class="result-icon">!</div>
    <h2>Attenzione</h2>
    <p><?= htmlspecialchars($errors[$error_key] ?? 'Errore sconosciuto.') ?></p>
    <a href="<?= BASE_URL ?>/paygate81.php" class="btn-secondary">Riprova</a>
  </div>

<?php elseif ($step === 'crypto' && $order_data): ?>
  <!-- STEP CRYPTO: mostra indirizzi USDT -->
  <div class="paygate-step card81" id="crypto-step">
    <h2>Paga con USDT</h2>
    <p class="step-intro">Invia esattamente <strong><?= number_format((float)$order_data['importo_euro'], 2, ',', '.') ?> USDT</strong> a uno degli indirizzi qui sotto.<br>
    Includi la causale nella nota/memo dove disponibile.</p>
    <div class="crypto-ref">
      Riferimento ordine (includi come memo): <code><?= htmlspecialchars($order_code) ?></code>
    </div>
    <div class="crypto-networks">
      <?php foreach ($crypto_addrs as $chain => $info): if (!$info['address']) continue; ?>
      <div class="crypto-card card81">
        <div class="crypto-chain"><?= htmlspecialchars($chain) ?> <span class="crypto-network"><?= htmlspecialchars($info['network']) ?></span></div>
        <div class="crypto-label"><?= htmlspecialchars($info['label']) ?></div>
        <div class="crypto-address">
          <code id="addr-<?= $chain ?>"><?= htmlspecialchars($info['address']) ?></code>
          <button class="btn-copy" onclick="copyAddr('<?= $chain ?>')">Copia</button>
        </div>
        <div class="crypto-confirm"><?= (int)$info['min_confirmations'] ?> conferme necessarie</div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="crypto-note">
      <strong>Importante:</strong> invia SOLO USDT (non altri token). Dopo l'invio, il tuo ordine viene verificato manualmente entro 24 ore lavorative.<br>
      Hai dubbi? Scrivi a supporto@81plus.net con il tx hash.
    </div>
    <a href="<?= BASE_URL ?>/paygate81.php" class="btn-secondary">Torna al pagamento</a>
  </div>

<?php elseif ($step === 'bonifico' && $order_data): ?>
  <!-- STEP BONIFICO: mostra IBAN -->
  <div class="paygate-step card81" id="bonifico-step">
    <h2>Paga tramite Bonifico</h2>
    <p class="step-intro">Effettua un bonifico con i dati qui sotto. Indica la causale esatta.</p>
    <table class="bonifico-table">
      <tr><th>Intestato a</th><td><?= htmlspecialchars($bonifico_data['intestato_a']) ?></td></tr>
      <tr><th>IBAN</th><td><code><?= htmlspecialchars($bonifico_data['iban']) ?></code></td></tr>
      <?php if ($bonifico_data['bic']): ?><tr><th>BIC/SWIFT</th><td><code><?= htmlspecialchars($bonifico_data['bic']) ?></code></td></tr><?php endif; ?>
      <?php if ($bonifico_data['banca']): ?><tr><th>Banca</th><td><?= htmlspecialchars($bonifico_data['banca']) ?></td></tr><?php endif; ?>
      <tr><th>Importo</th><td><strong><?= number_format($bonifico_data['importo'], 2, ',', '.') ?> EUR</strong></td></tr>
      <tr><th>Causale obbligatoria</th><td><strong><?= htmlspecialchars($bonifico_data['causale']) ?></strong> <button class="btn-copy" onclick="copyText('<?= htmlspecialchars($bonifico_data['causale'], ENT_QUOTES) ?>')">Copia</button></td></tr>
    </table>
    <div class="bonifico-note">
      I PV vengono accreditati entro 1-2 giorni lavorativi dalla ricezione del bonifico.<br>
      Conserva la ricevuta. Per info: supporto@81plus.net
    </div>
    <a href="<?= BASE_URL ?>/paygate81.php" class="btn-secondary">Torna al pagamento</a>
  </div>

<?php else: ?>
  <!-- FORM PRINCIPALE -->
  <section class="paygate-section card81" id="paygate-form" aria-label="Ricarica PV">
    <h2>Ricarica PV</h2>
    <p class="paygate-legal">I PV sono crediti interni non rimborsabili. Non sono denaro elettronico ai sensi della Dir. 2009/110/CE. Valore convenzionale interno 1 PV = 1 Euro.</p>

    <!-- Pack predefiniti -->
    <div class="pv-packs">
      <?php foreach (PaygateService::PACKS as $pack_id => $p): ?>
      <div class="pv-pack <?= $p['popular'] ? 'pv-pack-popular' : '' ?>" data-pack="<?= $pack_id ?>" data-importo="<?= $p['price'] ?>">
        <?php if ($p['popular']): ?><div class="pack-badge">Piu scelto</div><?php endif; ?>
        <div class="pack-value"><?= htmlspecialchars($p['label']) ?></div>
        <div class="pack-price"><?= number_format($p['price'], 2, ',', '.') ?> EUR</div>
        <?php if ($p['pvplus'] > 0): ?>
        <div class="pack-bonus">+<?= (int)$p['pvplus'] ?> PV+ bonus</div>
        <?php endif; ?>
        <button class="btn-pack <?= $p['popular'] ? 'btn-primary' : 'btn-secondary' ?>" type="button">Seleziona</button>
      </div>
      <?php endforeach; ?>

      <!-- Custom -->
      <div class="pv-pack pv-pack-custom" data-pack="custom" data-importo="0">
        <div class="pack-value">Importo libero</div>
        <div class="pack-price-custom">
          <span class="euro-sign">€</span>
          <input type="number" id="custom-amount" min="<?= PaygateService::MIN_IMPORTO ?>" max="<?= PaygateService::MAX_IMPORTO ?>" step="1" placeholder="Min 5 — Max 5000">
        </div>
        <div class="pack-bonus">&nbsp;</div>
        <button class="btn-pack btn-secondary" type="button">Seleziona</button>
      </div>
    </div>

    <!-- Form pagamento (nascosto fino a selezione pack) -->
    <form id="paygateForm" method="POST" action="<?= BASE_URL ?>/api/paygate-create-order.php" style="display:none">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <input type="hidden" name="importo" id="payImporto" value="">
      <input type="hidden" name="pack_id" id="payPackId" value="">

      <div class="gateway-selector">
        <h3>Come vuoi pagare?</h3>
        <div class="gateway-grid">

          <label class="gateway-card" data-gateway="PAYPAL">
            <input type="radio" name="provider" value="PAYPAL" checked>
            <div class="gw-icon">🟦</div>
            <div class="gw-name">PayPal / Carta</div>
            <div class="gw-sub">Visa, Mastercard, PayPal</div>
          </label>

          <label class="gateway-card" data-gateway="REVOLUT">
            <input type="radio" name="provider" value="REVOLUT">
            <div class="gw-icon">🔵</div>
            <div class="gw-name">Revolut</div>
            <div class="gw-sub">Checkout Revolut</div>
          </label>

          <label class="gateway-card" data-gateway="CRYPTO">
            <input type="radio" name="provider" value="CRYPTO">
            <div class="gw-icon">🟡</div>
            <div class="gw-name">Cripto USDT</div>
            <div class="gw-sub">ETH / BSC / TRX</div>
          </label>

          <label class="gateway-card" data-gateway="BONIFICO">
            <input type="radio" name="provider" value="BONIFICO">
            <div class="gw-icon">🏦</div>
            <div class="gw-name">Bonifico IBAN</div>
            <div class="gw-sub">1-2 giorni lavorativi</div>
          </label>

        </div>

        <!-- Selettore rete crypto (mostra solo se CRYPTO) -->
        <div id="chain-selector" style="display:none;margin-top:12px">
          <label>Rete USDT:
            <select name="chain">
              <option value="BSC">BNB Smart Chain (BEP-20) — consigliata</option>
              <option value="ETH">Ethereum (ERC-20)</option>
              <option value="TRX">Tron (TRC-20)</option>
            </select>
          </label>
        </div>
      </div>

      <div class="paygate-summary" id="paygateSummary" style="display:none">
        <span id="summaryLabel"></span>
        <span id="summaryImporto"></span>
      </div>

      <div id="paygateError" class="alert-error" style="display:none"></div>
      <button type="submit" class="btn-primary btn-full" id="paygateBtn">
        Procedi al pagamento
      </button>
    </form>
  </section>

  <!-- SEZIONE MEMBERSHIP -->
  <section class="paygate-section card81" aria-label="Attiva Membership">
    <h2>Attiva Membership con PV</h2>
    <p>Usa i tuoi PV per attivare o rinnovare la membership. Il pagamento avviene direttamente dal tuo saldo.</p>
    <div class="grid-3">
      <?php foreach (MEMBERSHIP_PLANS as $piano => $plan):
        $abbastanza = $pv_saldo >= $plan['pv'];
        $is_active  = $membership === $piano;
        $is_prima   = !$is_active;
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
        <button class="btn-primary activate-membership" data-piano="<?= e($piano) ?>" <?= $is_active ? 'disabled' : '' ?>>
          <?= $is_active ? 'Gia attiva' : 'Attiva ' . e($piano) ?>
        </button>
        <?php else: ?>
        <a href="#paygate-form" class="btn-secondary btn-sm">Ricarica prima</a>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <p class="plan-note">I PV+ non sono rendimento economico. Non sono convertibili in euro.</p>
  </section>

<?php endif; ?>
</main>

<!-- Modal membership -->
<div id="membershipModal" class="modal81" style="display:none" role="dialog" aria-modal="true">
  <div class="modal81-inner card81">
    <h3 id="modalTitle">Attivazione Membership</h3>
    <div id="modalBody"></div>
    <button class="btn-secondary" onclick="document.getElementById('membershipModal').style.display='none'">Chiudi</button>
  </div>
</div>

<script>
var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

/* ── Selezione pack ────────────────────────────────────────── */
document.querySelectorAll('.pv-pack').forEach(function(card) {
  card.querySelector('.btn-pack').addEventListener('click', function() {
    var importo = parseFloat(card.dataset.importo) || 0;
    var packId  = card.dataset.pack;

    if (packId === 'custom') {
      var val = parseFloat(document.getElementById('custom-amount').value) || 0;
      if (val < <?= PaygateService::MIN_IMPORTO ?> || val > <?= PaygateService::MAX_IMPORTO ?>) {
        alert('Importo non valido. Min €<?= PaygateService::MIN_IMPORTO ?> — Max €<?= PaygateService::MAX_IMPORTO ?>');
        return;
      }
      importo = val;
    }

    document.getElementById('payImporto').value = importo.toFixed(2);
    document.getElementById('payPackId').value  = packId === 'custom' ? '' : packId;

    document.querySelectorAll('.pv-pack').forEach(function(p){ p.classList.remove('selected'); });
    card.classList.add('selected');

    document.getElementById('paygateForm').style.display = 'block';
    document.getElementById('summaryLabel').textContent  = packId === 'custom'
      ? 'Importo libero'
      : card.querySelector('.pack-value').textContent;
    document.getElementById('summaryImporto').textContent = '€' + importo.toFixed(2).replace('.', ',');
    document.getElementById('paygateSummary').style.display = 'flex';

    document.getElementById('paygateForm').scrollIntoView({behavior:'smooth'});
  });
});

/* ── Aggiorna custom amount in tempo reale ─────────────────── */
var customInput = document.getElementById('custom-amount');
if (customInput) {
  customInput.addEventListener('input', function() {
    var card = document.querySelector('.pv-pack-custom');
    if (card.classList.contains('selected')) {
      document.getElementById('payImporto').value = (parseFloat(this.value) || 0).toFixed(2);
      document.getElementById('summaryImporto').textContent = '€' + (parseFloat(this.value) || 0).toFixed(2).replace('.', ',');
    }
  });
}

/* ── Mostra/nascondi selettore rete crypto ─────────────────── */
document.querySelectorAll('input[name="provider"]').forEach(function(r) {
  r.addEventListener('change', function() {
    document.getElementById('chain-selector').style.display =
      this.value === 'CRYPTO' ? 'block' : 'none';
  });
});

/* ── Stile gateway card selezionata ────────────────────────── */
document.querySelectorAll('.gateway-card').forEach(function(card) {
  card.addEventListener('click', function() {
    document.querySelectorAll('.gateway-card').forEach(function(c){ c.classList.remove('gw-selected'); });
    this.classList.add('gw-selected');
  });
});

/* ── Submit form ───────────────────────────────────────────── */
document.getElementById('paygateForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  var btn = document.getElementById('paygateBtn');
  var err = document.getElementById('paygateError');
  btn.disabled = true;
  btn.textContent = 'Creazione ordine...';
  err.style.display = 'none';

  try {
    var res  = await fetch(this.action, { method: 'POST', body: new FormData(this) });
    var data = await res.json();
    if (data.ok) {
      window.location.href = data.redirect;
    } else {
      err.textContent    = data.error || 'Errore. Riprova.';
      err.style.display  = 'block';
      btn.disabled       = false;
      btn.textContent    = 'Procedi al pagamento';
    }
  } catch (ex) {
    err.textContent   = 'Errore di rete. Riprova.';
    err.style.display = 'block';
    btn.disabled      = false;
    btn.textContent   = 'Procedi al pagamento';
  }
});

/* ── Attiva membership ─────────────────────────────────────── */
document.querySelectorAll('.activate-membership').forEach(function(btn) {
  btn.addEventListener('click', async function() {
    var piano = this.dataset.piano;
    if (!confirm('Attivare ' + piano + '? Verranno scalati i PV necessari dal tuo saldo.')) return;
    this.disabled    = true;
    this.textContent = 'Attivazione...';
    var fd = new FormData();
    fd.append('csrf_token', csrfToken);
    fd.append('piano', piano);
    try {
      var res  = await fetch('<?= BASE_URL ?>/api/membership-activate.php', { method: 'POST', body: fd });
      var data = await res.json();
      var modal = document.getElementById('membershipModal');
      document.getElementById('modalBody').innerHTML = data.ok
        ? '<p class="alert-success">' + data.msg + '</p><p>Ricarica la pagina per aggiornare il saldo.</p>'
        : '<p class="alert-error">' + (data.error || 'Errore') + '</p>';
      modal.style.display = 'flex';
      if (data.ok) setTimeout(function(){ location.reload(); }, 2500);
    } catch (ex) {
      this.disabled    = false;
      this.textContent = 'Attiva ' + piano;
      alert('Errore di rete.');
    }
  });
});

/* ── Copia testo negli appunti ─────────────────────────────── */
function copyAddr(chain) {
  var el = document.getElementById('addr-' + chain);
  if (el) copyText(el.textContent);
}
function copyText(text) {
  navigator.clipboard.writeText(text).then(function() {
    alert('Copiato: ' + text);
  }).catch(function() {
    prompt('Copia manualmente:', text);
  });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
