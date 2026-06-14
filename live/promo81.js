// 81+ PROMO ENGINE. Auto installante. Countdown vero della promo settimanale,
// prova sociale con numeri veri dal database, aggancio all'uscita una sola volta.
// Niente scarsità finta. La finestra chiude davvero e il bonus è erogato dal motore.
(function(){
if(window.__promo81)return;window.__promo81=1;
var PAGINA=(document.body&&document.body.dataset.pg)||location.pathname.split('/').pop().replace('.html','')||'index';
var IN_DASH=/dashboard|benvenuto/.test(location.pathname);
var IN_HOME=/index|^\/$|^$/.test(location.pathname.split('/').pop());

function quando(iso){var t=new Date(iso).getTime()-Date.now();if(t<0)t=0;
 var g=Math.floor(t/86400000),h=Math.floor(t%86400000/3600000),m=Math.floor(t%3600000/60000),s=Math.floor(t%60000/1000);
 return (g>0?g+'g ':'')+String(h).padStart(2,'0')+':'+String(m).padStart(2,'0')+':'+String(s).padStart(2,'0');}

// ===== COUNTDOWN PROMO =====
fetch('api/promo_week.php').then(function(r){return r.json();}).then(function(p){
 if(!p.ok||!p.attiva||!p.fine_finestra)return;
 try{ if(sessionStorage.getItem('promo81off')==='1'&&!IN_DASH)return; }catch(e){}
 if(IN_DASH){ cardDash(p); } else { striscia(p); }
 function tick(el){ el.textContent=quando(p.fine_finestra); if(new Date(p.fine_finestra)>new Date())setTimeout(function(){tick(el);},1000); else el.closest('[data-promo81]').remove(); }
 function striscia(p){
  var d=document.createElement('div'); d.setAttribute('data-promo81','1');
  d.style.cssText='position:fixed;top:0;left:0;right:0;z-index:99997;background:linear-gradient(90deg,#1a0d05,#241105);border-bottom:1px solid #E8501A;padding:9px 14px;font-family:Sora,Arial,sans-serif;display:flex;align-items:center;justify-content:center;gap:12px;flex-wrap:wrap';
  d.innerHTML='<span style="font-size:12.5px;color:#f3f3f8"><b style="color:#E8501A">'+p.titolo+'.</b> Bonus veri in PV su membership e Sigilli, e la formazione certificata per partire ora. Chiude tra</span>'
   +'<b style="font-family:JetBrains Mono,monospace;font-size:14px;color:#f3b016" id="p81t"></b>'
   +'<a href="promo_settimana.html" style="background:linear-gradient(180deg,#ff7a3d,#E8501A 55%,#c23f12);color:#fff;text-decoration:none;font-weight:700;padding:7px 14px;border-radius:8px;font-size:12px">Vedi la promo</a>'
   +'<span onclick="try{sessionStorage.setItem(\'promo81off\',\'1\')}catch(e){};this.parentNode.remove()" style="cursor:pointer;color:#6b7180;font-size:16px;padding:0 4px">&times;</span>';
  document.body.appendChild(d);
  document.body.style.paddingTop=(d.offsetHeight)+'px';
  tick(d.querySelector('#p81t'));
 }
 function cardDash(p){
  function ins(){
   var host=document.querySelector('.m81sec'); if(!host){setTimeout(ins,900);return;}
   var d=document.createElement('div'); d.setAttribute('data-promo81','1'); d.className='m81card';
   d.style.cssText='margin-bottom:14px;border-color:#f3b016!important;background:linear-gradient(160deg,#1c1206,#0e0a05)!important';
   d.innerHTML='<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">'
    +'<div><div style="font-family:Anton,Arial;font-size:18px;text-transform:uppercase;color:#f3b016">'+p.titolo+'</div>'
    +'<div style="font-size:12.5px;color:#bcbcc8;max-width:520px">'+p.sottotitolo+'</div></div>'
    +'<div style="text-align:center"><div style="font-family:JetBrains Mono,monospace;font-size:24px;color:#f3b016" id="p81td"></div>'
    +'<div style="font-size:10px;color:#9aa0b4;text-transform:uppercase;letter-spacing:1px">alla chiusura</div></div>'
    +'<a class="m81btn" href="promo_settimana.html">Vedi le tre offerte</a></div>';
   host.parentNode.insertBefore(d,host);
   tick(d.querySelector('#p81td'));
  }
  ins();
 }
}).catch(function(){});

// ===== PROVA SOCIALE, numeri veri =====
if(IN_HOME){
 fetch('api/stats_pubbliche.php').then(function(r){return r.json();}).then(function(s){
  if(!s.ok)return;
  var d=document.createElement('div');
  d.style.cssText='position:fixed;bottom:16px;right:16px;z-index:99996;background:#0e0e1a;border:1px solid #23232f;border-radius:12px;padding:11px 15px;font-family:Sora,Arial,sans-serif;font-size:12px;color:#bcbcc8;box-shadow:0 8px 24px rgba(0,0,0,.5);max-width:240px';
  d.innerHTML='<b style="color:#1db954">●</b> <b style="color:#fff">'+Number(s.utenti).toLocaleString('it-IT')+'</b> membri nell\u2019ecosistema'
   +(s.audit_settimana>0?(', <b style="color:#fff">'+s.audit_settimana+'</b> audit questa settimana'):'')
   +'<span onclick="this.parentNode.remove()" style="float:right;cursor:pointer;color:#6b7180;margin-left:8px">&times;</span>';
  setTimeout(function(){document.body.appendChild(d);},2500);
  setTimeout(function(){if(d.parentNode)d.remove();},14000);
 }).catch(function(){});
}

// ===== AGGANCIO ALL'USCITA, una volta sola per sessione, solo pagine pubbliche =====
if(IN_HOME){
 var fatto=false; try{fatto=sessionStorage.getItem('exit81')==='1';}catch(e){}
 if(!fatto){
  document.addEventListener('mouseout',function(e){
   if(fatto||e.clientY>8||e.relatedTarget)return; fatto=true;
   try{sessionStorage.setItem('exit81','1');}catch(x){}
   var o=document.createElement('div');
   o.style.cssText='position:fixed;inset:0;z-index:99999;background:rgba(5,5,12,.82);display:flex;align-items:center;justify-content:center;padding:18px';
   o.innerHTML='<div style="max-width:430px;background:#0e0e1a;border:1px solid #E8501A;border-radius:16px;padding:26px;text-align:center;font-family:Sora,Arial,sans-serif;box-shadow:0 30px 80px rgba(0,0,0,.7)">'
    +'<div style="font-family:Anton,Arial;font-size:22px;text-transform:uppercase;color:#fff;margin-bottom:8px">Prima di andare, prenditi il regalo</div>'
    +'<div style="font-size:13.5px;color:#bcbcc8;margin-bottom:16px">Il libro Addio Burocrazia e 1000 PV di benvenuto. Due minuti, nome ed email, e sono tuoi.</div>'
    +'<a href="signup.html" style="display:inline-block;background:linear-gradient(180deg,#ff7a3d,#E8501A 55%,#c23f12);color:#fff;text-decoration:none;font-weight:700;padding:13px 24px;border-radius:10px;font-size:14px">Prendo il regalo</a>'
    +'<div onclick="this.closest(\'div\').parentNode.remove()" style="margin-top:12px;font-size:12px;color:#6b7180;cursor:pointer">No grazie, esco</div></div>';
   o.onclick=function(e){if(e.target===o)o.remove();};
   document.body.appendChild(o);
  });
 }
}
})();
