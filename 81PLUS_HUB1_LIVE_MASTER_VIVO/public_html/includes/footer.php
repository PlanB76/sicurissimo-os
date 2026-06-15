<?php
// includes/footer.php — Footer 81plus.net
declare(strict_types=1);
?>
<footer class="footer81" role="contentinfo">
  <div class="footer81-grid container">
    <div class="footer81-brand">
      <img src="<?= BASE_URL ?>/assets/img/logo-81plus.svg" alt="81plus.net" width="100" height="32">
      <p>Il Sistema Operativo per la Sicurezza Aziendale.<br>D.Lgs 81/08 · HACCP · ISO 45001/9001/14001.</p>
    </div>
    <div class="footer81-links">
      <h4>Prodotti</h4>
      <ul>
        <li><a href="<?= BASE_URL ?>/membership.php">Membership 81+</a></li>
        <li><a href="<?= BASE_URL ?>/academy81.php">Academy 81+</a></li>
        <li><a href="<?= BASE_URL ?>/scout81.php">SCOUT81+</a></li>
        <li><a href="<?= BASE_URL ?>/pix81.php">PIX81+</a></li>
      </ul>
    </div>
    <div class="footer81-links">
      <h4>Ecosistema</h4>
      <ul>
        <li><a href="<?= BASE_URL ?>/green81.php">Green81+</a></li>
        <li><a href="<?= BASE_URL ?>/network81.php">NETWORK81+</a></li>
        <li><a href="<?= BASE_URL ?>/genesys81.php">GENESYS81+</a></li>
        <li><a href="<?= BASE_URL ?>/shop81.php">Shop 81+</a></li>
      </ul>
    </div>
    <div class="footer81-links">
      <h4>Azienda</h4>
      <ul>
        <li><a href="<?= BASE_URL ?>/chi-siamo.php">Chi siamo</a></li>
        <li><a href="<?= BASE_URL ?>/blog.php">Blog</a></li>
        <li><a href="<?= BASE_URL ?>/eventi.php">Eventi</a></li>
        <li><a href="<?= BASE_URL ?>/faq.php">FAQ</a></li>
      </ul>
    </div>
    <div class="footer81-links">
      <h4>Legale</h4>
      <ul>
        <li><a href="<?= BASE_URL ?>/privacy.php">Privacy Policy</a></li>
        <li><a href="<?= BASE_URL ?>/cookie.php">Cookie Policy</a></li>
        <li><a href="<?= BASE_URL ?>/condizioni-generali-vendita.php">Condizioni di Vendita</a></li>
        <li><a href="<?= BASE_URL ?>/termini.php">Termini di Utilizzo</a></li>
        <li><a href="<?= BASE_URL ?>/disclaimer.php">Disclaimer</a></li>
      </ul>
    </div>
  </div>
  <div class="footer81-bottom container">
    <p>&copy; <?= date('Y') ?> 81plus.net — Tutti i diritti riservati.</p>
    <p>P.IVA: [inserire] — I PV non sono denaro elettronico. I PV+ non sono rendimento. Il sistema non garantisce risultati economici.</p>
  </div>
</footer>

<!-- Cookie Banner -->
<div class="cookie81-banner" id="cookie81Banner" role="dialog" aria-label="Informativa cookie" style="display:none">
  <p>Questo sito usa cookie tecnici essenziali. <a href="<?= BASE_URL ?>/cookie.php">Informativa completa</a></p>
  <button class="btn-primary btn-sm" id="cookie81Accept">Accetta</button>
</div>

<!-- JS Base -->
<script src="<?= BASE_URL ?>/assets/js/cookie.js" defer></script>
</body>
</html>
