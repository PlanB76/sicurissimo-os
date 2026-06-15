<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
auth_require();
$user = auth_user();
$page_title = 'Profilo — 81plus.net';
$page_id    = 'profilo';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-profilo container">
  <h1>Il tuo profilo</h1>
  <div class="card81 profilo-card">
    <div class="profilo-header">
      <div class="profilo-avatar" aria-hidden="true">&#128100;</div>
      <div>
        <h2><?= e($user['nome']) ?></h2>
        <p class="sic-id-display">SIC-ID: <code><?= e($user['sic_id']) ?></code></p>
        <p><?= ruolo_label($user['ruolo']) ?>
          <?php if ($user['genesys_status'] !== 'NONE'): ?>
          &middot; <?= genesys_label($user['genesys_status']) ?>
          <?php endif; ?>
        </p>
      </div>
    </div>
    <form id="profiloForm" method="POST" action="<?= BASE_URL ?>/api/update-profile.php">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <div class="form-group">
        <label for="nome">Nome e Cognome</label>
        <input type="text" id="nome" name="nome" value="<?= e($user['nome']) ?>">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" value="<?= e($user['email']) ?>" disabled>
        <small>L'email non e modificabile. Contatta il supporto per cambiarla.</small>
      </div>
      <button type="submit" class="btn-primary">Salva modifiche</button>
    </form>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>