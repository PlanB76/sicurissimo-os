<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Chi siamo — 81plus.net';
$page_id    = 'chi-siamo';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-chi-siamo container">
  <section class="about-hero">
    <h1>Il progetto 81plus</h1>
    <p class="hero-sub">Nati dalla convinzione che la sicurezza sul lavoro non debba essere un peso, ma un vantaggio competitivo per le imprese italiane.</p>
  </section>
  <section class="about-mission">
    <h2>La nostra missione</h2>
    <p>Democratizzare l'accesso alla compliance aziendale. Rendere la sicurezza un asset che lavora per l'imprenditore, non contro di lui.</p>
  </section>
  <section class="about-3hub">
    <h2>L'architettura a 3 HUB</h2>
    <div class="grid-3">
      <div class="card81"><h3>HUB1 — 81plus.net</h3><p>Identita, accesso, membership. Il gateway del sistema.</p></div>
      <div class="card81"><h3>HUB2 — 81plus.it</h3><p>L'economia Web2. Prodotti, servizi, marketplace.</p></div>
      <div class="card81"><h3>HUB3 — 81plus.online</h3><p>L'ecosistema Web3. NFT utility, SAF token, DAO governance.</p></div>
    </div>
  </section>
  <section class="about-legal">
    <p>La direzione e raggiungibile tramite i canali ufficiali del sito. Tutte le comunicazioni operative avvengono attraverso il sistema 81plus.</p>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>