<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Audit Gratuito 81/08 e HACCP — 81plus.net';
$page_id    = 'audit';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-audit container-narrow">
  <h1>Audit Gratuito</h1>
  <p class="page-intro">Scopri in 5 minuti il tuo livello di esposizione al rischio D.Lgs 81/08 e HACCP. Zero costi. Risultato immediato.</p>
  <div class="card81">
    <form id="auditForm" method="POST" action="<?= BASE_URL ?>/api/audit-submit.php">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <div class="form-group">
        <label for="settore">Settore *</label>
        <select id="settore" name="settore" required>
          <option value="">Seleziona</option>
          <option value="manifattura">Manifattura</option>
          <option value="food">Food &amp; Ristorazione</option>
          <option value="cantiere">Cantiere / Edilizia</option>
          <option value="ufficio">Ufficio</option>
          <option value="retail">Retail</option>
        </select>
      </div>
      <div class="form-group">
        <label for="dipendenti">Numero dipendenti *</label>
        <select id="dipendenti" name="dipendenti" required>
          <option value="1-5">1-5</option>
          <option value="6-15">6-15</option>
          <option value="16-50">16-50</option>
          <option value="50+">Oltre 50</option>
        </select>
      </div>
      <div class="form-group">
        <label>Hai un DVR aggiornato? *</label>
        <div class="radio-group">
          <label><input type="radio" name="dvr" value="si" required> Si, aggiornato</label>
          <label><input type="radio" name="dvr" value="vecchio"> Si, ma non aggiornato</label>
          <label><input type="radio" name="dvr" value="no"> No</label>
        </div>
      </div>
      <div class="form-group">
        <label>Ultima formazione dipendenti?</label>
        <div class="radio-group">
          <label><input type="radio" name="formazione" value="12m"> Ultimi 12 mesi</label>
          <label><input type="radio" name="formazione" value="24m"> 1-2 anni fa</label>
          <label><input type="radio" name="formazione" value="mai"> Mai / Non ricordo</label>
        </div>
      </div>
      <div class="form-group">
        <label for="email_audit">Email per ricevere il report</label>
        <input type="email" id="email_audit" name="email" placeholder="mario@azienda.it" <?php if (is_logged()): ?>value="<?= e($user['email'] ?? '') ?>"<?php endif; ?>>
      </div>
      <button type="submit" class="btn-primary btn-full">Calcola il mio rischio</button>
    </form>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>