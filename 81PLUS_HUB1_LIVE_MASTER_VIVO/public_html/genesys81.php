<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'GENESYS81+ — Programma Fondatori — 81plus.net';
$page_id    = 'genesys81';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-genesys container">
  <section class="genesys-hero">
    <h1>GENESYS81+</h1>
    <p class="hero-sub">Il programma per chi vuole essere parte della fondazione del sistema. Non un referral. Una missione.</p>
    <a href="<?= BASE_URL ?>/signup.php?ref=genesys" class="btn-primary btn-gold">Candidati a GENESYS81+</a>
  </section>
  <section class="genesys-levels container">
    <h2>I livelli GENESYS81+</h2>
    <div class="grid-4">
      <div class="card81 genesys-card level-member">
        <h3>GENESYS MEMBER</h3>
        <p>Accesso anticipato. Missioni speciali. Badge esclusivo.</p>
      </div>
      <div class="card81 genesys-card level-networker">
        <h3>GENESYS NETWORKER</h3>
        <p>Costruisce la rete. Accesso SCOUT81+ priority. Pipeline3D avanzata.</p>
      </div>
      <div class="card81 genesys-card level-leader">
        <h3>GENESYS LEADER</h3>
        <p>Guida zone territoriali. Accesso ai materiali di lancio. Visibilita nella dashboard NETWORK81+.</p>
      </div>
      <div class="card81 genesys-card level-founder">
        <h3>GENESYS FOUNDER</h3>
        <p>I pionieri del sistema. Slot PIX81+ riservati 90 giorni. Massima priorita su tutti i servizi.</p>
      </div>
    </div>
  </section>
  <section class="genesys-cta container">
    <p>200 slot PIX81+ Founder sono riservati ai GENESYS FOUNDER per i primi 90 giorni dal lancio. Posti limitati.</p>
    <a href="<?= BASE_URL ?>/signup.php?ref=genesys" class="btn-primary">Candidati ora</a>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>