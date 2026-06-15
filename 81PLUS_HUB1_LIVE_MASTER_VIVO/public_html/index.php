<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = '81plus.net — La Sicurezza che ti rende Inattaccabile';
$page_desc  = 'D.Lgs 81/08, HACCP e ISO 45001 trasformati in vantaggio competitivo. Registrati gratis. Il tuo SIC-ID viene generato automaticamente.';
$page_id    = 'home';
$logged     = is_logged();
$user_home  = $logged ? auth_user() : [];
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-home">

<!-- ══ HERO — DOPPIA PORTA ═════════════════════════════════════════════════ -->
<section class="hero81-dual section-dark" aria-labelledby="hero-title">
  <div class="container hero-inner">
    <p class="hero-pre">Il Sistema Operativo per la Sicurezza Aziendale</p>
    <h1 id="hero-title" class="display-hero">La sicurezza che ti rende<br><span class="text-orange">inattaccabile</span></h1>
    <p class="hero-sub">D.Lgs 81/08 &middot; HACCP &middot; ISO 45001/9001/14001<br>Non un consulente che risponde. Un sistema che agisce mentre tu lavori.</p>
  </div>
  <div class="dual-door container">
    <div class="door-card door-user">
      <div class="door-tag">Per l'Imprenditore</div>
      <h2>Proteggi la tua azienda</h2>
      <p>Audit gratuito. DOC81+ Builder. Academy certificata. Scadenziario automatico. Tutto in un'area personale con il tuo SIC-ID.</p>
      <div class="door-actions">
        <a href="<?= BASE_URL ?>/signup.php" class="btn-primary">Registrati gratuitamente</a>
        <a href="<?= BASE_URL ?>/audit.php" class="btn-secondary">Fai l'audit gratuito</a>
      </div>
    </div>
    <div class="door-divider" aria-hidden="true">oppure</div>
    <div class="door-card door-networker">
      <div class="door-tag door-tag-gold">Per il Networker</div>
      <h2>Entra in GENESYS81+</h2>
      <p>Costruisci la tua rete con SCOUT81+, PLP81+ e NETWORK81+. <?php if (GENESYS_PROMO_ACTIVE): ?><strong>Promo attiva: +<?= GENESYS_PROMO_PVPLUS ?> PV+ alla candidatura.</strong><?php endif; ?></p>
      <div class="door-actions">
        <a href="<?= BASE_URL ?>/genesys81.php" class="btn-primary btn-gold">Candidati a GENESYS81+</a>
        <a href="<?= BASE_URL ?>/chi-siamo.php" class="btn-secondary">Scopri il progetto</a>
      </div>
    </div>
  </div>
</section>

<!-- ══ COME FUNZIONA ═══════════════════════════════════════════════════════ -->
<section class="come-funziona section-dark container" aria-label="Come funziona 81plus">
  <h2 class="section-title">Un sistema operativo, non un consulente</h2>
  <div class="grid-3">
    <div class="card81">
      <span class="card-step">01</span>
      <h3>Registrati. Gratis.</h3>
      <p>Crei l'account in 2 minuti. Ricevi il tuo <strong>SIC-ID</strong> univoco. Accedi all'area personale.</p>
    </div>
    <div class="card81">
      <span class="card-step">02</span>
      <h3>Fai l'audit gratuito.</h3>
      <p>Il sistema analizza la tua esposizione 81/08 e HACCP. Ricevi un report di rischio immediato.</p>
    </div>
    <div class="card81">
      <span class="card-step">03</span>
      <h3>Attiva e usa.</h3>
      <p>Attiva la membership con PV. Genera documenti, accedi ai corsi, presidia le scadenze.</p>
    </div>
  </div>
</section>

<!-- ══ PIANI MEMBERSHIP ════════════════════════════════════════════════════ -->
<section class="piani section-card container" aria-label="Piani membership 81+">
  <h2 class="section-title">Scegli il tuo piano</h2>
  <div class="grid-3 membership-grid">
    <div class="card81 card-membership">
      <div class="plan-badge">BASIC+</div>
      <div class="plan-price"><span class="price-num">29,90</span><span class="price-unit"> PV/mese</span></div>
      <ul class="plan-features">
        <li>Audit 81/08 e HACCP illimitato</li>
        <li>DOC81+ Builder base</li>
        <li>Academy 81+ (corsi base)</li>
        <li>Scadenziario automatico</li>
        <li><strong>100 PV+</strong> alla prima attivazione</li>
        <li>30 PV+ al rinnovo mensile</li>
      </ul>
      <a href="<?= BASE_URL ?>/membership.php?piano=BASIC%2B" class="btn-primary">Attiva BASIC+</a>
    </div>
    <div class="card81 card-membership card-featured">
      <div class="plan-badge badge-orange">PRO+ <span class="badge-pop">Piu scelto</span></div>
      <div class="plan-price"><span class="price-num">59,90</span><span class="price-unit"> PV/mese</span></div>
      <ul class="plan-features">
        <li>Tutto BASIC+</li>
        <li>SCOUT81+ prospect map</li>
        <li>PLP81+ pack incluso</li>
        <li>NETWORK81+ accesso base</li>
        <li><strong>250 PV+</strong> alla prima attivazione</li>
        <li>60 PV+ al rinnovo mensile</li>
      </ul>
      <a href="<?= BASE_URL ?>/membership.php?piano=PRO%2B" class="btn-primary">Scegli PRO+</a>
    </div>
    <div class="card81 card-membership">
      <div class="plan-badge badge-gold">ELITE+</div>
      <div class="plan-price"><span class="price-num">89,90</span><span class="price-unit"> PV/mese</span></div>
      <ul class="plan-features">
        <li>Tutto PRO+</li>
        <li>NETWORK81+ accesso pieno</li>
        <li>Pipeline3D81+ commerciale</li>
        <li>TerritoryMap81+</li>
        <li><strong>500 PV+</strong> alla prima attivazione</li>
        <li>90 PV+ al rinnovo mensile</li>
      </ul>
      <a href="<?= BASE_URL ?>/membership.php?piano=ELITE%2B" class="btn-primary">Diventa ELITE+</a>
    </div>
  </div>
  <p class="plan-note">I PV (PointValue) sono crediti interni con valore convenzionale 1:1 euro. Non sono denaro elettronico. Non sono rimborsabili salvo condizioni contrattuali specifiche. I PV+ sono punti di gamification, non rendimento economico.</p>
</section>

<!-- ══ GENESYS81+ PROMO ════════════════════════════════════════════════════ -->
<?php if (GENESYS_PROMO_ACTIVE): ?>
<section class="genesys-promo-banner section-card" aria-label="GENESYS81+ Promo">
  <div class="container promo-inner">
    <div class="promo-badge-gold">GENESYS81+ PROMO <?= GENESYS_PROMO_DAYS ?> GIORNI</div>
    <h2>I Fondatori del Sistema 81+</h2>
    <p>Entra ora come early adopter. Ricevi <strong><?= number_format(GENESYS_PROMO_PVPLUS, 0, ',', '.') ?> PV+</strong> alla candidatura + ulteriori <strong><?= number_format(GENESYS_PROFILE_PVPLUS, 0, ',', '.') ?> PV+</strong> al profilo completo. Accesso prioritario a PIX81+ Founder (200 slot riservati).</p>
    <a href="<?= BASE_URL ?>/genesys81.php" class="btn-primary btn-gold">Candidati a GENESYS81+</a>
    <p class="promo-note">PIX81+ non e un prodotto di investimento. L'accesso GENESYS81+ non garantisce risultati economici.</p>
  </div>
</section>
<?php endif; ?>

<!-- ══ SCOUT81+ PREVIEW ════════════════════════════════════════════════════ -->
<section class="scout-preview section-dark container" aria-label="SCOUT81+ preview">
  <h2 class="section-title">SCOUT81+ — Trova il tuo mercato</h2>
  <div class="grid-2 scout-grid">
    <div class="scout-text">
      <p><strong>SCOUT81+</strong> individua le aziende nella tua zona che hanno bisogno di presidiare la sicurezza. Filtra per settore, regione, ATECO e livello di rischio.</p>
      <p><strong>PLP81+</strong> consegna prospect profilati. <strong>NETWORK81+</strong> lavora il mercato.</p>
      <p class="scout-disclaimer">SCOUT81+ non vende clienti. Non promette conversioni. Mostra opportunita da lavorare secondo regolamento, privacy e vendita etica.</p>
      <a href="<?= BASE_URL ?>/scout81.php" class="btn-secondary">Scopri SCOUT81+</a>
    </div>
    <div class="scout-map-preview card81">
      <div class="map-placeholder" aria-label="Anteprima mappa SCOUT81+ Italia">
        <span class="map-icon" aria-hidden="true">🗺️</span>
        <p>Mappa prospect Italia disponibile con PRO+</p>
        <a href="<?= BASE_URL ?>/membership.php?piano=PRO%2B" class="btn-primary btn-sm">Attiva PRO+ per accedere</a>
      </div>
    </div>
  </div>
</section>

<!-- ══ RECENSIONI PUBBLICHE PREVIEW ═══════════════════════════════════════ -->
<section class="recensioni-preview section-card container" aria-label="Testimonianze reali">
  <h2 class="section-title">Testimonianze reali SICURISSIMO81+ dal 2003</h2>
  <div id="homeRecensioni" class="recensioni-ticker" aria-live="polite">
    <!-- Caricate via JS da api/recensioni-list.php -->
    <div class="loading81">Caricamento testimonianze...</div>
  </div>
  <a href="<?= BASE_URL ?>/recensioni.php" class="btn-secondary">Leggi tutte le testimonianze</a>
</section>

<!-- ══ FAQ ESSENZIALI ══════════════════════════════════════════════════════ -->
<section class="faq-home section-dark container" aria-label="Domande frequenti">
  <h2 class="section-title">Domande frequenti</h2>
  <div class="faq-grid">
    <details class="faq-item"><summary>Cosa sono i PV?</summary><p>I PV (PointValue) sono crediti interni al sistema con valore convenzionale 1:1 euro. Non sono denaro elettronico. Non sono rimborsabili salvo condizioni contrattuali specifiche.</p></details>
    <details class="faq-item"><summary>Cosa sono i PV+?</summary><p>I PV+ sono punti del sistema di gamification 81plus. Non rappresentano rendimento economico. Non sono convertibili in denaro.</p></details>
    <details class="faq-item"><summary>Il SIC-ID e obbligatorio?</summary><p>No. Viene generato automaticamente alla registrazione gratuita. E il tuo identificatore univoco nel sistema 81plus.</p></details>
    <details class="faq-item"><summary>I documenti DOC81+ sono validi legalmente?</summary><p>No, non direttamente. Sono bozze operative che richiedono validazione da un professionista abilitato prima dell'uso ufficiale.</p></details>
    <details class="faq-item"><summary>GENESYS81+ garantisce guadagni?</summary><p>No. GENESYS81+ e un programma per early adopter con vantaggi di accesso anticipato. Non garantisce alcun risultato economico.</p></details>
    <details class="faq-item"><summary>PIX81+ e un investimento?</summary><p>No. PIX81+ e uno spazio digitale con utility, visibilita e accesso. Non e un prodotto finanziario.</p></details>
  </div>
  <a href="<?= BASE_URL ?>/faq.php" class="btn-secondary">Tutte le FAQ</a>
</section>

<!-- ══ CTA FINALE ══════════════════════════════════════════════════════════ -->
<section class="cta-finale section-card container" aria-label="Registrati ora">
  <h2>Inizia oggi. Gratis.</h2>
  <p>Il tuo SIC-ID viene generato automaticamente. Accesso immediato all'area personale.</p>
  <a href="<?= BASE_URL ?>/signup.php" class="btn-primary btn-lg">Registrati gratuitamente</a>
</section>

</main>

<script>
// Carica recensioni preview
fetch('<?= BASE_URL ?>/api/recensioni-list.php?limit=3')
  .then(function(r){return r.json();})
  .then(function(d){
    if(!d.ok||!d.items) return;
    var c = document.getElementById('homeRecensioni');
    c.innerHTML = '';
    d.items.forEach(function(r){
      var el = document.createElement('div');
      el.className = 'recensione-card card81';
      el.innerHTML = '<blockquote>' + r.testo + '</blockquote><cite>' + r.autore + ' &mdash; ' + r.settore + '</cite>';
      c.appendChild(el);
    });
  });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
