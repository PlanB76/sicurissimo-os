// scout81map.js — SCOUT81+ Mappa Italia con Leaflet
// Dipende da: Leaflet.js (caricato dalla pagina)

(function () {
  'use strict';

  let map = null;
  let markersLayer = null;

  function initScout81Map(containerId) {
    if (!window.L) { console.error('Leaflet non caricato'); return; }

    map = L.map(containerId).setView([41.9, 12.5], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors',
      maxZoom: 18,
    }).addTo(map);

    markersLayer = L.layerGroup().addTo(map);
    loadProspects({});
  }

  function loadProspects(filtri) {
    const params = new URLSearchParams(filtri);
    fetch('/api/scout-map-data.php?' + params.toString(), { credentials: 'include' })
      .then(r => r.json())
      .then(data => {
        if (!data.ok) return;
        renderMarkers(data.data);
      })
      .catch(e => console.error('Errore mappa SCOUT81+:', e));
  }

  function renderMarkers(prospects) {
    markersLayer.clearLayers();
    prospects.forEach(p => {
      if (!p.comune) return;
      // Geocoding semplificato — in produzione usare coordinate da DB
      const color = riskColor(p.rischio);
      const icon = L.circleMarker([0, 0], { // placeholder coords
        radius: 6, fillColor: color, color: '#fff', weight: 1, fillOpacity: 0.85
      });
      icon.bindPopup(
        `<strong>${p.ragione_sociale}</strong><br>` +
        `ATECO: ${p.ateco || '—'}<br>` +
        `Rischio: ${p.rischio}<br>` +
        `Score: ${p.score}<br>` +
        `<a href="/api/scout-prospect-card.php?id=${p.id}" target="_blank">Scheda</a>`
      );
      markersLayer.addLayer(icon);
    });
  }

  function riskColor(rischio) {
    return { ALTO: '#E8501A', MEDIO: '#FFD24A', BASSO: '#3FBF6B' }[rischio] || '#B9BCC2';
  }

  window.Scout81Map = { init: initScout81Map, load: loadProspects };
})();
