const CACHE='sic81-v1';
const CORE=['/','/index.html','/dashboard.html','/manifest.json','/favicon.svg','/icon-192.png','/icon-512.png','/assets/chat81.js','/hub2.html','/hub3.html','/manifest-hub2.json','/manifest-hub3.json'];
self.addEventListener('install',e=>{self.skipWaiting();e.waitUntil(caches.open(CACHE).then(c=>c.addAll(CORE).catch(()=>{})));});
self.addEventListener('activate',e=>{e.waitUntil(caches.keys().then(k=>Promise.all(k.filter(x=>x!==CACHE).map(x=>caches.delete(x)))));self.clients.claim();});
self.addEventListener('fetch',e=>{
  const u=new URL(e.request.url);
  if(e.request.method!=='GET'||u.pathname.startsWith('/api/')){return;} // mai cache su API o POST
  e.respondWith(
    fetch(e.request).then(r=>{const cp=r.clone();caches.open(CACHE).then(c=>c.put(e.request,cp).catch(()=>{}));return r;})
    .catch(()=>caches.match(e.request).then(m=>m||caches.match('/index.html')))
  );
});
