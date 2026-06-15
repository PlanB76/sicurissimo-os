// Widget chat 81+. Uso: <script src="assets/chat81.js" data-agente="Graziella" data-titolo="HUB2"></script>
// Crea una chat fluttuante arancio legata a un agente della flotta.
(function(){
  var s=document.currentScript;
  var agente=s.getAttribute('data-agente')||'Mirco AI';
  var titolo=s.getAttribute('data-titolo')||agente;
  var sid=localStorage.getItem('sic81_sid'); if(!sid){sid='s'+Math.random().toString(36).slice(2,11);localStorage.setItem('sic81_sid',sid);}
  var O='#E8501A';
  var css=document.createElement('style');
  css.textContent=''
    +'.c81b{position:fixed;right:18px;bottom:18px;width:58px;height:58px;border-radius:50%;background:'+O+';color:#fff;border:none;cursor:pointer;font-size:24px;box-shadow:0 6px 20px rgba(0,0,0,.4);z-index:99998}'
    +'.c81p{position:fixed;right:18px;bottom:86px;width:340px;max-width:92vw;height:460px;max-height:74vh;background:#0c0c12;border:1px solid #23232f;border-radius:16px;display:none;flex-direction:column;overflow:hidden;z-index:99999;box-shadow:0 10px 40px rgba(0,0,0,.6)}'
    +'.c81h{background:'+O+';color:#fff;padding:12px 14px;font-weight:bold;font-family:sans-serif;font-size:14px}'
    +'.c81h small{display:block;opacity:.85;font-weight:normal;font-size:11px}'
    +'.c81m{flex:1;overflow-y:auto;padding:12px;display:flex;flex-direction:column;gap:8px}'
    +'.c81r{max-width:84%;padding:8px 11px;border-radius:12px;font-size:13px;line-height:1.5;font-family:sans-serif;white-space:pre-wrap}'
    +'.c81u{align-self:flex-end;background:'+O+';color:#fff}'
    +'.c81a{align-self:flex-start;background:#17171f;color:#e8e8ee;border:1px solid #23232f}'
    +'.c81f{display:flex;border-top:1px solid #23232f}'
    +'.c81f input{flex:1;background:#0c0c12;border:none;color:#fff;padding:12px;font-size:13px;outline:none}'
    +'.c81f button{background:'+O+';color:#fff;border:none;padding:0 16px;cursor:pointer;font-weight:bold}';
  document.head.appendChild(css);
  var b=document.createElement('button'); b.className='c81b'; b.innerHTML='&#128172;'; b.setAttribute('aria-label','Apri chat');
  var p=document.createElement('div'); p.className='c81p';
  p.innerHTML='<div class="c81h">'+titolo+'<small>AI 81+, scrivi la tua domanda</small></div>'
    +'<div class="c81m" id="c81m"></div>'
    +'<div class="c81f"><input id="c81i" placeholder="Scrivi qui" autocomplete="off"><button id="c81s">Invia</button></div>';
  document.body.appendChild(b); document.body.appendChild(p);
  function add(t,who){var d=document.createElement('div');d.className='c81r '+(who==='u'?'c81u':'c81a');d.textContent=t;document.getElementById('c81m').appendChild(d);var m=document.getElementById('c81m');m.scrollTop=m.scrollHeight;return d;}
  var aperto=false;
  b.onclick=function(){aperto=!aperto;p.style.display=aperto?'flex':'none';if(aperto&&!p.dataset.intro){p.dataset.intro='1';add('Ciao, sono '+agente+'. Come ti posso aiutare sul tema di '+titolo+'?','a');}};
  function invia(){
    var i=document.getElementById('c81i');var t=i.value.trim();if(!t)return;i.value='';add(t,'u');
    var wait=add('...','a');
    fetch('api/agente.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({agente:agente,messaggio:t,sessione:sid})})
      .then(function(r){return r.json();}).then(function(d){wait.textContent=(d&&d.risposta)?d.risposta:'Riprova tra un momento.';})
      .catch(function(){wait.textContent='Connessione assente, riprova.';});
  }
  document.getElementById('c81s').onclick=invia;
  document.getElementById('c81i').addEventListener('keydown',function(e){if(e.key==='Enter')invia();});
})();
