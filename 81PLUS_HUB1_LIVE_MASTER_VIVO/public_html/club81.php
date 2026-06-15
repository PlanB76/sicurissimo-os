<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
auth_require();
$user = auth_user();
$page_title = 'Club81+ — Comunita Elite — 81plus.net';
$page_id    = 'club81';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-club81 container">
  <h1>Club81+</h1>
  <p class="page-intro">La comunita riservata ai membri ELITE81+ e ai GENESYS LEADER/FOUNDER. Accesso a contenuti premium, eventi esclusivi e rete di alto livello.</p>
  <?php if (!in_array($user['ruolo'], ['ELITE81','ADMIN81'], true) && !in_array($user['genesys_status'], ['GENESYS_LEADER','GENESYS_FOUNDER'], true)): ?>
  <div class="upgrade-prompt card81">
    <h2>Accesso riservato a ELITE81+ e GENESYS LEADER/FOUNDER</h2>
    <a href="<?= BASE_URL ?>/membership.php?piano=elite" class="btn-primary">Diventa ELITE+</a>
  </div>
  <?php else: ?>
  <div class="club-content" id="clubContent"><div class="loading81">Caricamento contenuti Club81+...</div></div>
  <?php endif; ?>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>