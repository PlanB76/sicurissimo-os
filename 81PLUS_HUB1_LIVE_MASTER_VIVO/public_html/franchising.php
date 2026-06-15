<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Franchising 81+ — Diventa Partner — 81plus.net';
$page_id    = 'franchising';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-franchising container">
  <h1>Franchising 81+</h1>
  <p class="page-intro">Porta il sistema 81plus nella tua zona. Diventa un partner territoriale certificato.</p>
  <div class="franchising-info grid-2">
    <div class="card81">
      <h2>Cosa offriamo</h2>
      <ul>
        <li>Territorio in esclusiva</li>
        <li>Formazione completa sul sistema</li>
        <li>Materiali marketing e brand guidelines</li>
        <li>Accesso al Command Center 81+</li>
        <li>Supporto tecnico e commerciale</li>
      </ul>
    </div>
    <div class="card81">
      <h2>Chi cerchiamo</h2>
      <ul>
        <li>Consulenti sicurezza (RSPP, coordinatori CSP/CSE)</li>
        <li>Studi di consulenza del lavoro</li>
        <li>Imprenditori del settore formazione</li>
        <li>Professionisti con rete nel territorio</li>
      </ul>
    </div>
  </div>
  <div class="franchising-cta card81">
    <h2>Candidati come partner territoriale</h2>
    <form method="POST" action="<?= BASE_URL ?>/api/franchising-apply.php">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <div class="form-group"><label>Nome e Cognome *</label><input type="text" name="nome" required></div>
      <div class="form-group"><label>Email *</label><input type="email" name="email" required></div>
      <div class="form-group"><label>Zona di riferimento</label><input type="text" name="zona" placeholder="Es: Milano Nord, Veneto, Sicilia..."></div>
      <div class="form-group"><label>Esperienza nel settore</label><textarea name="esperienza" rows="3"></textarea></div>
      <button type="submit" class="btn-primary">Invia candidatura</button>
    </form>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>