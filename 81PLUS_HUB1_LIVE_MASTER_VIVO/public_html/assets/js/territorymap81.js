// territorymap81.js — TerritoryMap81+ con Leaflet
(function () {
  'use strict';

  let map = null;

  function initTerritoryMap(containerId) {
    if (!window.L) { console.error('Leaflet non caricato'); return; }

    map = L.map(containerId).setView([41.9, 12.5], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap'
    }).addTo(map);

    loadAreas({});
  }

  function loadAreas(filtri) {
    const params = new URLSearchParams(filtri);
    fetch('/api/territory-map-data.php?' + params, { credentials: 'include' })
      .then(r => r.json())
      .then(data => {
        if (!data.ok) return;
        renderAreas(data.data);
      });
  }

  function renderAreas(areas) {
    areas.forEach(a => {
      const color = areaColor(a.status, a.point81_attivo);
      // In produzione: usare GeoJSON province italiane
      const marker = L.circleMarker([41.9, 12.5], {
        radius: 10, fillColor: color, color: '#fff', weight: 1, fillOpacity: 0.7
      });
      marker.bindPopup(
        `<strong>${a.nome}</strong><br>` +
        `Tipo: ${a.tipo}<br>` +
        `Status: ${a.status}<br>` +
        `POINT81+: ${a.point81_attivo ? 'Attivo' : 'Non attivo'}`
      );
      marker.addTo(map);
    });
  }

  function areaColor(status, point81) {
    if (status === 'SOSPESA') return '#4A0000';
    if (status === 'ASSEGNATA' && point81) return '#3FBF6B';
    if (status === 'ASSEGNATA') return '#FFD24A';
    if (status === 'RISERVATA') return '#8A4AE8';
    return '#B9BCC2';
  }

  window.TerritoryMap81 = { init: initTerritoryMap, reload: loadAreas };
})();
