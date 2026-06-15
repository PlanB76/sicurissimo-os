<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Accedi — 81plus.net';
$page_desc  = 'Accedi al tuo account 81plus.net.';
$page_id    = 'login';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-login container-narrow">
  <div class="auth-card card81">
    <h1>Accedi al tuo account</h1>
    <form id="loginForm" method="POST" action="<?= BASE_URL ?>/api/login.php" novalidate>
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <?php if (!empty($_GET['redir'])): ?>
      <input type="hidden" name="redir" value="<?= e($_GET['redir']) ?>">
      <?php endif; ?>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required autocomplete="email">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">
      </div>
      <div id="loginError" class="alert-error" role="alert" style="display:none"></div>
      <button type="submit" class="btn-primary btn-full" id="loginBtn">Accedi</button>
    </form>
    <p class="auth-switch">Non hai un account? <a href="<?= BASE_URL ?>/signup.php">Registrati gratis</a></p>
    <p class="auth-switch"><a href="<?= BASE_URL ?>/reset-password.php">Password dimenticata?</a></p>
  </div>
</main>
<script>
(function() {
  var form = document.getElementById('loginForm');
  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    var btn = document.getElementById('loginBtn');
    var err = document.getElementById('loginError');
    btn.disabled = true; btn.textContent = 'Accesso in corso...';
    err.style.display = 'none';
    try {
      var res = await fetch(form.action, { method: 'POST', body: new FormData(form) });
      var data = await res.json();
      if (data.ok) { window.location.href = data.redirect || '<?= BASE_URL ?>/dashboard.php'; }
      else { err.textContent = data.error || 'Credenziali non valide.'; err.style.display = 'block'; btn.disabled = false; btn.textContent = 'Accedi'; }
    } catch(ex) { err.textContent = 'Errore di rete. Riprova.'; err.style.display = 'block'; btn.disabled = false; btn.textContent = 'Accedi'; }
  });
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>