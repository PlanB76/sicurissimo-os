<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Registrazione — 81plus.net';
$page_desc  = 'Crea il tuo account. Il tuo SIC-ID viene generato automaticamente.';
$page_id    = 'signup';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-signup container-narrow">
  <div class="auth-card card81">
    <h1>Crea il tuo account</h1>
    <p class="auth-sub">Il tuo <strong>SIC-ID</strong> viene generato automaticamente alla conferma dell'email.</p>
    <form id="signupForm" method="POST" action="<?= BASE_URL ?>/api/signup.php" novalidate>
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <div class="form-group">
        <label for="nome">Nome e Cognome *</label>
        <input type="text" id="nome" name="nome" required autocomplete="name" placeholder="Mario Rossi">
      </div>
      <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" required autocomplete="email" placeholder="mario@azienda.it">
      </div>
      <div class="form-group">
        <label for="password">Password *</label>
        <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password" placeholder="Minimo 8 caratteri">
      </div>
      <div class="form-group">
        <label for="settore">Settore aziendale</label>
        <select id="settore" name="settore">
          <option value="">Seleziona il tuo settore</option>
          <option value="manifattura">Manifattura</option>
          <option value="food">Food &amp; Ristorazione (HACCP)</option>
          <option value="cantiere">Cantiere / Edilizia</option>
          <option value="ufficio">Ufficio / Servizi</option>
          <option value="retail">Retail / Commercio</option>
          <option value="altro">Altro</option>
        </select>
      </div>
      <div class="form-group">
        <label class="checkbox-label">
          <input type="checkbox" name="privacy" required value="1">
          Accetto la <a href="<?= BASE_URL ?>/privacy.php" target="_blank" rel="noopener">Privacy Policy</a> e i <a href="<?= BASE_URL ?>/termini.php" target="_blank" rel="noopener">Termini di Utilizzo</a>
        </label>
      </div>
      <div id="signupError" class="alert-error" role="alert" style="display:none"></div>
      <button type="submit" class="btn-primary btn-full" id="signupBtn">Crea il mio account</button>
    </form>
    <p class="auth-switch">Hai gia un account? <a href="<?= BASE_URL ?>/login.php">Accedi</a></p>
  </div>
</main>
<script>
(function() {
  var form = document.getElementById('signupForm');
  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    var btn = document.getElementById('signupBtn');
    var err = document.getElementById('signupError');
    btn.disabled = true; btn.textContent = 'Registrazione in corso...';
    err.style.display = 'none';
    try {
      var res = await fetch(form.action, { method: 'POST', body: new FormData(form) });
      var data = await res.json();
      if (data.ok) { window.location.href = '<?= BASE_URL ?>/dashboard.php?benvenuto=1'; }
      else { err.textContent = data.error || 'Errore. Riprova.'; err.style.display = 'block'; btn.disabled = false; btn.textContent = 'Crea il mio account'; }
    } catch(ex) { err.textContent = 'Errore di rete. Riprova.'; err.style.display = 'block'; btn.disabled = false; btn.textContent = 'Crea il mio account'; }
  });
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>