// cookie.js — Cookie consent 81+
(function () {
  'use strict';

  const BANNER_ID = 'cookie81-banner';
  const KEY       = 'cookie81_consent';

  function init() {
    if (localStorage.getItem(KEY)) return;
    showBanner();
  }

  function showBanner() {
    const banner = document.createElement('div');
    banner.id    = BANNER_ID;
    banner.className = 'cookie81-banner';
    banner.innerHTML =
      '<p>Usiamo cookie tecnici e, con il tuo consenso, cookie analitici per migliorare il servizio. ' +
      '<a href="/cookie.php">Dettagli</a></p>' +
      '<div class="cookie81-actions">' +
      '<button onclick="Cookie81.accept()">Accetta tutti</button>' +
      '<button onclick="Cookie81.onlyTech()">Solo tecnici</button>' +
      '</div>';
    document.body.appendChild(banner);
  }

  function accept() {
    save({ analytics: true, marketing: false, tecnici: true });
  }

  function onlyTech() {
    save({ analytics: false, marketing: false, tecnici: true });
  }

  function save(prefs) {
    localStorage.setItem(KEY, JSON.stringify(prefs));
    document.getElementById(BANNER_ID)?.remove();
    // Server-side log
    fetch('/api/cookie-consent.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(prefs),
    }).catch(() => {});
  }

  window.Cookie81 = { init, accept, onlyTech };
  document.addEventListener('DOMContentLoaded', init);
})();
