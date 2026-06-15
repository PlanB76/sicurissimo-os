<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Membership 81+ — Scegli il tuo piano';
$page_id    = 'membership';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-membership container">
  <h1>Scegli il tuo piano</h1>
  <p class="page-intro">Attiva la tua membership con PV. Scegli il piano piu adatto alla tua azienda e al tuo obiettivo.</p>
  <div class="membership-grid grid-3">
    <div class="card81 card-membership <?php echo (($_GET['piano']??'')=='basic')?'card-selected':'' ?>">
      <div class="plan-badge">BASIC+</div>
      <div class="plan-price">29,90 PV<span>/mese</span></div>
      <ul class="plan-features">
        <li>Audit gratuito illimitato</li>
        <li>DOC81+ Builder base</li>
        <li>Academy (corsi base)</li>
        <li>Scadenziario 81+</li>
        <li><strong>100 PV+</strong> al primo attivo</li>
        <li><strong>30 PV+</strong> al rinnovo mensile</li>
      </ul>
      <a href="<?= BASE_URL ?>/paygate81.php?membership=basic" class="btn-primary">Attiva BASIC+</a>
    </div>
    <div class="card81 card-membership card-featured <?php echo (($_GET['piano']??'')=='pro')?'card-selected':'' ?>">
      <div class="plan-badge badge-orange">PRO+ <span class="badge-pop">Piu scelto</span></div>
      <div class="plan-price">59,90 PV<span>/mese</span></div>
      <ul class="plan-features">
        <li>Tutto BASIC+</li>
        <li>SCOUT81+ (ricerca prospect)</li>
        <li>PLP Pack incluso</li>
        <li>DOC81+ Builder completo</li>
        <li><strong>250 PV+</strong> al primo attivo</li>
        <li><strong>60 PV+</strong> al rinnovo mensile</li>
      </ul>
      <a href="<?= BASE_URL ?>/paygate81.php?membership=pro" class="btn-primary">Scegli PRO+</a>
    </div>
    <div class="card81 card-membership <?php echo (($_GET['piano']??'')=='elite')?'card-selected':'' ?>">
      <div class="plan-badge badge-gold">ELITE+</div>
      <div class="plan-price">89,90 PV<span>/mese</span></div>
      <ul class="plan-features">
        <li>Tutto PRO+</li>
        <li>NETWORK81+ accesso pieno</li>
        <li>Pipeline3D commerciale</li>
        <li>Territory Map 81+</li>
        <li><strong>500 PV+</strong> al primo attivo</li>
        <li><strong>90 PV+</strong> al rinnovo mensile</li>
      </ul>
      <a href="<?= BASE_URL ?>/paygate81.php?membership=elite" class="btn-primary">Diventa ELITE+</a>
    </div>
  </div>
  <div class="membership-note card81">
    <p><strong>Nota:</strong> I PV (PointValue) sono crediti interni 1:1 euro. Non sono denaro elettronico ai sensi della Direttiva 2009/110/CE. I PV+ sono punti gamification, non rappresentano rendimento economico.</p>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>