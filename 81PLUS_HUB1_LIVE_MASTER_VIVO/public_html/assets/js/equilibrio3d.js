// equilibrio3d.js — Equilibrio3D81+ radar sfera
(function () {
  'use strict';

  const DIMENSIONI = [
    'Lavoro','Energia','Tempo','Focus','Formazione',
    'Follow-up','Webinar','Famiglia','Salute','SCOUT81+','PLP81+','Pipeline'
  ];

  function initEquilibrio3D(containerId) {
    const el = document.getElementById(containerId);
    if (!el) return;

    // TODO: implementare sfera 3D con Three.js + radar chart
    // Placeholder visivo
    el.innerHTML = '<div class="equilibrio3d-radar">' +
      DIMENSIONI.map(d => `<div class="dim-item"><span>${d}</span><div class="dim-bar"></div></div>`).join('') +
      '</div>';

    console.log('Equilibrio3D81+ pronto. Collegare a Three.js radar.');
  }

  window.Equilibrio3D = { init: initEquilibrio3D };
})();
