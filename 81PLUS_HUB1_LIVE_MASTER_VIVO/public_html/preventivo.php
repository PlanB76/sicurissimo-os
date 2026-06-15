<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
auth_require();
$user = auth_user();
$page_title = 'DOC81+ Builder — 81plus.net';
$page_id    = 'preventivo';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-preventivo container">
  <h1>DOC81+ Builder</h1>
  <p class="page-intro">Genera procedure operative, checklist e bozze documentali. <strong>Nota:</strong> ogni documento generato e una bozza che richiede validazione professionale prima dell'uso ufficiale.</p>
  <div class="card81">
    <form id="docForm" method="POST" action="<?= BASE_URL ?>/api/create-document.php">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <div class="form-group">
        <label for="doc_type">Tipo documento *</label>
        <select id="doc_type" name="doc_type" required>
          <option value="">Seleziona tipo</option>
          <option value="dvr_bozza">DVR Bozza (81/08)</option>
          <option value="haccp_piano">Piano HACCP</option>
          <option value="procedura_emergenza">Procedura di Emergenza</option>
          <option value="checklist_dpi">Checklist DPI</option>
          <option value="registro_formazione">Registro Formazione</option>
          <option value="piano_manutenzione">Piano Manutenzione Attrezzature</option>
        </select>
      </div>
      <div class="form-group">
        <label for="azienda_nome">Ragione sociale</label>
        <input type="text" id="azienda_nome" name="azienda_nome" placeholder="Mario Rossi S.r.l.">
      </div>
      <div class="form-group">
        <label for="settore_azienda">Settore</label>
        <select id="settore_azienda" name="settore">
          <option value="manifattura">Manifattura</option>
          <option value="food">Food &amp; Ristorazione</option>
          <option value="cantiere">Cantiere</option>
          <option value="ufficio">Ufficio</option>
        </select>
      </div>
      <div class="form-group">
        <label for="note">Note specifiche</label>
        <textarea id="note" name="note" rows="3" placeholder="Aggiungi contesto specifico per il tuo settore..."></textarea>
      </div>
      <button type="submit" class="btn-primary">Genera documento bozza</button>
    </form>
  </div>
  <div id="docResult" class="doc-result" style="display:none"></div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>