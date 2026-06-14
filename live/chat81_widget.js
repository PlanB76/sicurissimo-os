/* 81+ CHAT WIDGET universale. Un assistente AI dedicato per ogni sito e sub-sito.
   Uso su qualunque pagina:
   <script src="https://81plus.net/chat81_widget.js" data-tema="sicurezza"></script>
   Temi: ecosistema, sicurezza, haccp, privacy, academy, shop, pix, token, network, zone, club, world, exchange.
   Parametri: data-tema (default ecosistema), data-base (default https://81plus.net, vuoto = stesso dominio). */
(function(){
  var me=document.currentScript||(function(){var s=document.getElementsByTagName('script');return s[s.length-1];})();
  var tema=(me.getAttribute('data-tema')||'ecosistema').toLowerCase();
  var base=me.hasAttribute('data-base')?me.getAttribute('data-base').replace(/\/$/,''):'';
  var P={ecosistema:['Nicolas','Ciao, sono Nicolas. Ti guido nell ecosistema 81+, chiedimi di SIC-ID, PV, membership e PIX81+.'],
    sicurezza:['Noemi','Ciao, sono Noemi. Dimmi quanti dipendenti hai e che attività fai, ti dico subito cosa ti serve per essere in regola.'],
    haccp:['Marta','Ciao, sono Marta. Bar, ristorante o produzione, dimmi la tua attività e ti dico cosa serve per l HACCP.'],
    privacy:['Paolo','Ciao, sono Paolo. Ti aiuto con GDPR, informative e registri. Da dove partiamo.'],
    academy:['Sofia','Ciao, sono Sofia. Ti aiuto a scegliere il corso giusto e a capire come funzionano gli attestati.'],
    shop:['Luca','Ciao, sono Luca. Cerchi DPI o prodotti per la sicurezza, dimmi cosa ti serve.'],
    pix:['Iris','Ciao, sono Iris. Le posizioni PIX81+ sono solo 1000, per sempre. Chiedimi come funziona la mappa.'],
    token:['Graziano','Ciao, sono Graziano. Ti spiego il token di utilità 81X, il mining di attività e le fasi dichiarate. Non è un investimento.'],
    digital:['Dea','Ciao, sono Dea. Ti racconto la trasformazione digitale sicura con 81+ DIGITAL.'],
    network:['Elena','Ciao, sono Elena. Ti spiego come funziona la rete 81+, commissioni solo da vendite reali.'],
    zone:['Marco','Ciao, sono Marco. Ti racconto il franchising 81+ ZONE e i tre formati.'],
    club:['Vittoria','Ciao, sono Vittoria. Ti racconto il Club 81+ e i suoi livelli.'],
    world:['Atlas','Ciao, sono Atlas. Benvenuto nel Metaverso 81+, chiedimi dei mondi 3D.'],
    exchange:['Dario','Ciao, sono Dario. Ti spiego il DEX 81+ e come si usa un wallet in sicurezza. Niente consigli finanziari.']};
  var p=P[tema]||P.ecosistema;
  var css=document.createElement('style');
  css.textContent='.c81b{position:fixed;bottom:18px;right:18px;z-index:99990;width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#E8501A,#FB6B00);border:0;cursor:pointer;box-shadow:0 8px 26px rgba(232,80,26,.45);display:flex;align-items:center;justify-content:center;color:#fff;font:800 19px Anton,Arial,sans-serif;animation:c81p 2.4s infinite}@keyframes c81p{0%{box-shadow:0 0 0 0 rgba(232,80,26,.5)}70%{box-shadow:0 0 0 13px rgba(232,80,26,0)}100%{box-shadow:0 0 0 0 rgba(232,80,26,0)}}.c81w{position:fixed;bottom:84px;right:18px;z-index:99991;width:min(360px,calc(100vw - 36px));height:min(480px,70vh);background:#0d0d13;border:1px solid #26262b;border-radius:16px;display:none;flex-direction:column;overflow:hidden;box-shadow:0 22px 70px rgba(0,0,0,.6);font-family:Sora,Arial,sans-serif}.c81w.on{display:flex}.c81h{background:linear-gradient(135deg,#E8501A,#FB6B00);color:#fff;padding:13px 16px;display:flex;align-items:center;gap:10px}.c81h .av{width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font:800 15px Anton,Arial}.c81h b{font-size:14px}.c81h small{display:block;font-size:10px;opacity:.85}.c81h .x{margin-left:auto;background:none;border:0;color:#fff;font-size:18px;cursor:pointer}.c81m{flex:1;overflow-y:auto;padding:14px;display:flex;flex-direction:column;gap:9px}.c81m .b{max-width:85%;padding:9px 12px;border-radius:12px;font-size:13px;line-height:1.55;color:#e2e2ea;white-space:pre-wrap}.c81m .ai{background:#17171f;border:1px solid #23232b;align-self:flex-start;border-bottom-left-radius:4px}.c81m .tu{background:#2a1610;border:1px solid #E8501A;align-self:flex-end;border-bottom-right-radius:4px}.c81m .dots span{display:inline-block;width:6px;height:6px;border-radius:50%;background:#FB6B00;margin-right:3px;animation:c81d 1s infinite}.c81m .dots span:nth-child(2){animation-delay:.18s}.c81m .dots span:nth-child(3){animation-delay:.36s}@keyframes c81d{0%,100%{opacity:.25}50%{opacity:1}}.c81f{display:flex;gap:8px;padding:11px;border-top:1px solid #1c1c22}.c81f input{flex:1;background:#15151c;border:1px solid #26262b;border-radius:10px;color:#fff;padding:10px 12px;font-size:13px;font-family:Sora,Arial;outline:none}.c81f input:focus{border-color:#E8501A}.c81f button{background:linear-gradient(135deg,#E8501A,#FB6B00);border:0;color:#fff;border-radius:10px;width:42px;cursor:pointer;font-size:16px}.c81n{font-size:10px;color:#6a6a72;text-align:center;padding:0 10px 9px}';
  document.head.appendChild(css);
  var btn=document.createElement('button');btn.className='c81b';btn.setAttribute('aria-label','Apri la chat');btn.textContent=p[0][0];
  var w=document.createElement('div');w.className='c81w';
  w.innerHTML='<div class="c81h"><span class="av">'+p[0][0]+'</span><div><b>'+p[0]+'</b><small>Assistente 81+ · risponde in pochi secondi</small></div><button class="x" aria-label="chiudi">✕</button></div>'
    +'<div class="c81m"></div>'
    +'<div class="c81f"><input type="text" maxlength="600" placeholder="Scrivi qui..."><button aria-label="invia">➤</button></div>'
    +'<div class="c81n">Assistente AI, può sbagliare. Per le decisioni importanti scrivi a info@81plus.net.</div>';
  document.body.appendChild(btn);document.body.appendChild(w);
  var m=w.querySelector('.c81m'),inp=w.querySelector('input'),send=w.querySelector('.c81f button');
  var storia=[];
  function bolla(t,cls){var d=document.createElement('div');d.className='b '+cls;d.textContent=t;m.appendChild(d);m.scrollTop=m.scrollHeight;return d;}
  function apri(){w.classList.add('on');if(!m.children.length)bolla(p[1],'ai');inp.focus();}
  btn.onclick=function(){w.classList.contains('on')?w.classList.remove('on'):apri();};
  w.querySelector('.x').onclick=function(){w.classList.remove('on');};
  function invia(){
    var t=inp.value.trim();if(!t)return;inp.value='';
    bolla(t,'tu');storia.push({role:'user',content:t});
    var dots=document.createElement('div');dots.className='b ai dots';dots.innerHTML='<span></span><span></span><span></span>';m.appendChild(dots);m.scrollTop=m.scrollHeight;
    fetch(base+'/api/chat.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({messaggio:t,tema:tema,storia:storia.slice(-6)})})
    .then(function(r){return r.json();})
    .then(function(d){dots.remove();var r=(d&&d.risposta)||'Non riesco a rispondere ora, riprova.';bolla(r,'ai');storia.push({role:'assistant',content:r});})
    .catch(function(){dots.remove();bolla('Connessione non riuscita, riprova tra poco.','ai');});
  }
  send.onclick=invia;
  inp.addEventListener('keydown',function(e){if(e.key==='Enter')invia();});
})();
