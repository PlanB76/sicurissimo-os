<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
auth_require();
$user = auth_user();
$page_title = 'Verifica Identita (KYC) — 81plus.net';
$page_id    = 'kyc';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-kyc container-narrow">
  <div class="card81">
    <h1>Verifica la tua identita</h1>
    <p>La verifica KYC sblocca funzionalita avanzate e accredita <strong>+1.000 PV+</strong> sul tuo account.</p>
    <div class="kyc-steps">
      <div class="kyc-step">
        <span class="step-num">1</span>
        <div><h3>Documento d'identita</h3><p>Carica fronte e retro di un documento valido (CI, Passaporto).</p></div>
      </div>
      <div class="kyc-step">
        <span class="step-num">2</span>
        <div><h3>Selfie con documento</h3><p>Una foto con il documento in mano per confermare l'identita.</p></div>
      </div>
      <div class="kyc-step">
        <span class="step-num">3</span>
        <div><h3>Revisione</h3><p>Il team verifica entro 24/48 ore lavorative. Ricevi email di conferma.</p></div>
      </div>
    </div>
    <form method="POST" action="<?= BASE_URL ?>/api/kyc-submit.php" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <div class="form-group"><label>Documento fronte *</label><input type="file" name="doc_front" accept="image/*,application/pdf" required></div>
      <div class="form-group"><label>Documento retro *</label><input type="file" name="doc_back" accept="image/*,application/pdf" required></div>
      <div class="form-group"><label>Selfie con documento *</label><input type="file" name="doc_selfie" accept="image/*" required></div>
      <button type="submit" class="btn-primary btn-full">Invia per verifica</button>
    </form>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>