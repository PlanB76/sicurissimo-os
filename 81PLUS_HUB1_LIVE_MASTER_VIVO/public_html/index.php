<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = '81plus.net — Il Sistema Operativo per la Sicurezza Aziendale';
$page_desc  = 'D.Lgs 81/08, HACCP e ISO 45001 trasformati in vantaggio competitivo.';
$page_id    = 'home';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-home">
  <section class="hero81-dual" aria-label="Scegli il tuo percorso">
    <div class="hero81-intro container">
      <h1 class="display-hero">La sicurezza che ti rende<br><span class="text-orange">inattaccabile</span></h1>
      <p class="hero-sub">D.Lgs 81/08 &middot; HACCP &middot; ISO 45001/9001/14001<br>Non un consulente. Un sistema operativo che agisce mentre tu lavori.</p>
    </div>
    <div class="dual-door container">
      <div class="door-card door-user">
        <span class="door-icon" aria-hidden="true">&#128737;</span>
        <h2>Sono un Imprenditore</h2>
        <p>Voglio proteggere la mia azienda, ridurre il rischio ispezioni e trasformare la compliance in un asset.</p>
        <a href="<?= BASE_URL ?>/signup.php" class="btn-primary">Registrati gratuitamente</a>
        <a href="<?= BASE_URL ?>/audit.php" class="btn-secondary">Fai l'audit gratuito</a>
      </div>
      <div class="door-card door-networker">
        <span class="door-icon" aria-hidden="true">&#128640;</span>
        <h2>Voglio entrare in GENESYS81+</h2>
        <p>Sono un professionista della sicurezza o un early adopter. Voglio costruire il mio network con 81plus.</p>
        <a href="<?= BASE_URL ?>/genesys81.php" class="btn-primary btn-gold">Candidati a GENESYS81+</a>
        <a href="<?= BASE_URL ?>/chi-siamo.php" class="btn-secondary">Scopri il progetto</a>
      </div>
    </div>
  </section>

  <section class="what81 container">
    <h2 class="section-title">Un sistema che lavora per te</h2>
    <div class="grid-3">
      <div class="card81"><h3>Audit Gratuito</h3><p>Analisi istantanea della tua esposizione 81/08 e HACCP. Zero burocrazia, risultati immediati.</p></div>
      <div class="card81"><h3>DOC81+ Builder</h3><p>Genera procedure, DVR bozze e checklist operative. Ogni documento richiede validazione professionale prima dell'uso ufficiale.</p></div>
      <div class="card81"><h3>Academy 81+</h3><p>Corsi con partner certificati, attestati validi, formazione che conta davvero per la tua azienda.</p></div>
    </div>
  </section>

  <section class="membership-grid container">
    <h2 class="section-title">Scegli il tuo piano</h2>
    <div class="grid-3">
      <div class="card81 card-membership">
        <div class="plan-badge">BASIC+</div>
        <div class="plan-price">29,90 PV<span>/mese</span></div>
        <ul class="plan-features"><li>Audit gratuito</li><li>DOC81+ Builder</li><li>Academy base</li><li>100 PV+ al primo attivo</li></ul>
        <a href="<?= BASE_URL ?>/membership.php?piano=basic" class="btn-primary">Inizia con BASIC+</a>
      </div>
      <div class="card81 card-membership card-featured">
        <div class="plan-badge badge-orange">PRO+</div>
        <div class="plan-price">59,90 PV<span>/mese</span></div>
        <ul class="plan-features"><li>Tutto BASIC+</li><li>SCOUT81+ accesso</li><li>PLP Pack incluso</li><li>250 PV+ al primo attivo</li></ul>
        <a href="<?= BASE_URL ?>/membership.php?piano=pro" class="btn-primary">Scegli PRO+</a>
      </div>
      <div class="card81 card-membership">
        <div class="plan-badge badge-gold">ELITE+</div>
        <div class="plan-price">89,90 PV<span>/mese</span></div>
        <ul class="plan-features"><li>Tutto PRO+</li><li>NETWORK81+ pieno</li><li>Pipeline3D</li><li>500 PV+ al primo attivo</li></ul>
        <a href="<?= BASE_URL ?>/membership.php?piano=elite" class="btn-primary">Diventa ELITE+</a>
      </div>
    </div>
    <p class="plan-note">I PV sono crediti interni 1:1 euro. Non sono denaro elettronico. Non sono rimborsabili salvo condizioni contrattuali specifiche.</p>
  </section>

  <section class="cta-finale container">
    <h2>Inizia oggi. Gratis.</h2>
    <p>Il tuo SIC-ID viene generato automaticamente alla registrazione. Accesso immediato all'area personale.</p>
    <a href="<?= BASE_URL ?>/signup.php" class="btn-primary btn-lg">Registrati gratuitamente</a>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>