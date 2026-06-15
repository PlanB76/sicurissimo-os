<?php
// includes/nav.php — Navigazione 81plus.net
declare(strict_types=1);
$logged = is_logged();
$user_nav = $logged ? auth_user() : [];
?>
<nav class="nav81" id="nav81" role="navigation" aria-label="Navigazione principale">
  <div class="nav81-brand">
    <a href="<?= BASE_URL ?>/" aria-label="81plus.net — Home">
      <img src="<?= BASE_URL ?>/assets/img/logo-81plus.svg" alt="81plus.net" width="120" height="40">
    </a>
  </div>

  <button class="nav81-toggle" id="navToggle" aria-expanded="false" aria-controls="navMenu" aria-label="Apri menu">
    <span></span><span></span><span></span>
  </button>

  <ul class="nav81-menu" id="navMenu" role="list">
    <li><a href="<?= BASE_URL ?>/chi-siamo.php">Chi siamo</a></li>
    <li><a href="<?= BASE_URL ?>/membership.php">Membership</a></li>
    <li><a href="<?= BASE_URL ?>/academy81.php">Academy</a></li>
    <li><a href="<?= BASE_URL ?>/scout81.php">Scout81+</a></li>
    <li class="nav81-has-sub">
      <a href="#" aria-haspopup="true">Ecosystem <span aria-hidden="true">▾</span></a>
      <ul class="nav81-sub" role="list">
        <li><a href="<?= BASE_URL ?>/pix81.php">PIX81+</a></li>
        <li><a href="<?= BASE_URL ?>/green81.php">Green81+</a></li>
        <li><a href="<?= BASE_URL ?>/network81.php">NETWORK81+</a></li>
        <li><a href="<?= BASE_URL ?>/genesys81.php">GENESYS81+</a></li>
      </ul>
    </li>
    <li><a href="<?= BASE_URL ?>/blog.php">Blog</a></li>
  </ul>

  <div class="nav81-cta">
    <?php if ($logged): ?>
      <a href="<?= BASE_URL ?>/dashboard.php" class="btn-secondary btn-sm">
        <?= e($user_nav['nome'] ?? 'Area Personale') ?>
      </a>
    <?php else: ?>
      <a href="<?= BASE_URL ?>/login.php" class="btn-secondary btn-sm">Accedi</a>
      <a href="<?= BASE_URL ?>/signup.php" class="btn-primary btn-sm">Registrati</a>
    <?php endif; ?>
  </div>
</nav>

<script>
document.getElementById('navToggle').addEventListener('click', function() {
  const menu = document.getElementById('navMenu');
  const open = this.getAttribute('aria-expanded') === 'true';
  this.setAttribute('aria-expanded', String(!open));
  menu.classList.toggle('open', !open);
});
</script>
