// pipeline3d.js — Pipeline3D81+ con Three.js
// Dipende da: three.min.js

(function () {
  'use strict';

  let scene, camera, renderer, nodes = {}, edges = [];

  function initPipeline3D(containerId) {
    if (!window.THREE) { console.error('Three.js non caricato'); return; }

    const el = document.getElementById(containerId);
    if (!el) return;

    scene    = new THREE.Scene();
    scene.background = new THREE.Color(0x05050A);

    camera   = new THREE.PerspectiveCamera(60, el.clientWidth / el.clientHeight, 0.1, 1000);
    camera.position.set(0, 0, 15);

    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(el.clientWidth, el.clientHeight);
    el.appendChild(renderer.domElement);

    loadGraph();
    animate();

    window.addEventListener('resize', () => {
      camera.aspect = el.clientWidth / el.clientHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(el.clientWidth, el.clientHeight);
    });
  }

  function loadGraph() {
    fetch('/api/pipeline3d-data.php', { credentials: 'include' })
      .then(r => r.json())
      .then(data => {
        if (!data.ok) return;
        renderGraph(data.nodes, data.edges);
      });
  }

  function renderGraph(nodeData, edgeData) {
    nodeData.forEach((n, i) => {
      const geo  = new THREE.SphereGeometry(0.3, 16, 16);
      const mat  = new THREE.MeshBasicMaterial({ color: parseInt(n.color.replace('#',''), 16) });
      const mesh = new THREE.Mesh(geo, mat);
      // Layout circolare semplice
      const angle = (i / nodeData.length) * Math.PI * 2;
      mesh.position.set(Math.cos(angle) * 8, Math.sin(angle) * 8, 0);
      mesh.userData = n;
      scene.add(mesh);
      nodes[n.id] = mesh;
    });

    edgeData.forEach(e => {
      if (!nodes[e.from] || !nodes[e.to]) return;
      const mat  = new THREE.LineBasicMaterial({ color: 0x333333 });
      const geo  = new THREE.BufferGeometry().setFromPoints([
        nodes[e.from].position, nodes[e.to].position
      ]);
      scene.add(new THREE.Line(geo, mat));
    });
  }

  function animate() {
    requestAnimationFrame(animate);
    scene.rotation.y += 0.001;
    renderer.render(scene, camera);
  }

  window.Pipeline3D = { init: initPipeline3D, reload: loadGraph };
})();
