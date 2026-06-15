<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'FAQ — Domande frequenti — 81plus.net';
$page_id    = 'faq';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main class="page-faq container">
  <h1>Domande frequenti</h1>
  <div class="faq-list">
    <details class="faq-item">
      <summary>Cos'e 81plus.net?</summary>
      <p>81plus.net e un sistema operativo digitale per la sicurezza aziendale. Offre strumenti per la compliance D.Lgs 81/08, HACCP e certificazioni ISO. Non e uno studio di consulenza.</p>
    </details>
    <details class="faq-item">
      <summary>Cosa sono i PV?</summary>
      <p>I PV (PointValue) sono crediti interni al sistema, con valore convenzionale 1:1 euro. Non sono denaro elettronico. Non sono rimborsabili, salvo condizioni contrattuali specifiche esplicitamente previste.</p>
    </details>
    <details class="faq-item">
      <summary>Cosa sono i PV+?</summary>
      <p>I PV+ sono punti di gamification. Non rappresentano rendimento economico. Non sono convertibili in denaro. Servono per sbloccare livelli, missioni e accessi aggiuntivi nel sistema 81plus.</p>
    </details>
    <details class="faq-item">
      <summary>I documenti generati dal DOC81+ Builder sono validi legalmente?</summary>
      <p>No, non direttamente. Ogni documento e una bozza operativa. Prima dell'uso ufficiale richiede validazione da parte di un professionista abilitato (RSPP, consulente del lavoro, medico competente).</p>
    </details>
    <details class="faq-item">
      <summary>Cos'e il SIC-ID?</summary>
      <p>Il SIC-ID e il tuo codice identificativo universale nel sistema 81plus. Viene generato automaticamente al momento della registrazione. Non e un documento fiscale.</p>
    </details>
    <details class="faq-item">
      <summary>Cos'e GENESYS81+?</summary>
      <p>GENESYS81+ e il programma per early adopter e fondatori del sistema. Offre accesso anticipato alle funzionalita, vantaggi esclusivi e la possibilita di costruire una rete come NETWORKER81+. Non garantisce risultati economici.</p>
    </details>
    <details class="faq-item">
      <summary>Come funziona la foresta Green81+?</summary>
      <p>Green81+ e il programma di riforestazione sostenibile di 81plus. Ogni albero piantato e tracciato su registro pubblico. Non e un programma di carbon credits commerciabili.</p>
    </details>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>