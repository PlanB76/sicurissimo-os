/* 81+ COMPONENTE UNICO header e footer, mobile first.
   Identico su ogni sito e sub-sito dell ecosistema, concept 81plus.it.
   Header minimo: logo 81+ bianco su quadrato arancione, voci essenziali,
   PIX81+ evidenziato, switch IT EN reale, login e signup arancioni pulsanti.
   Footer legale completo e identico ovunque. */
(function(){
  var css=document.createElement('style');
  css.textContent=`
  :root{--o81:#E8501A;--o81b:#FB6B00}
  @keyframes puls81{0%{box-shadow:0 0 0 0 rgba(232,80,26,.55)}70%{box-shadow:0 0 0 11px rgba(232,80,26,0)}100%{box-shadow:0 0 0 0 rgba(232,80,26,0)}}
  .nav81{position:sticky;top:0;z-index:9999;background:rgba(8,8,8,.96);backdrop-filter:blur(8px);border-bottom:1px solid #1c1c1c;display:flex;align-items:center;justify-content:space-between;padding:10px 20px;font-family:'Sora',Arial,sans-serif}
  .nav81 .lg{display:flex;align-items:center;gap:9px;text-decoration:none}
  .nav81 .lgq{display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:9px;background:linear-gradient(135deg,#FB6B00,#E8501A);color:#fff;font-family:'Anton','Bebas Neue',sans-serif;font-size:18px;letter-spacing:.5px;box-shadow:0 4px 14px rgba(232,80,26,.35)}
  .nav81 .lgt{color:#fff;font-family:'Anton',sans-serif;font-size:15px;letter-spacing:1px;display:none}
  @media(min-width:560px){.nav81 .lgt{display:inline}}
  .nav81 .lk{display:flex;align-items:center;gap:18px}
  .nav81 .lk a{color:#cfcfcf;text-decoration:none;font-size:14px;white-space:nowrap}
  .nav81 .lk a:hover{color:#fff}
  .nav81 .lk a.pix{color:#fff;background:linear-gradient(135deg,rgba(232,80,26,.25),rgba(0,217,255,.12));border:1px solid var(--o81);padding:7px 14px;border-radius:9px;font-weight:800}
  .nav81 .lk a.pix:hover{background:linear-gradient(135deg,rgba(232,80,26,.4),rgba(0,217,255,.2))}
  .nav81 .auth{display:flex;align-items:center;gap:9px;margin-left:6px}
  .nav81 .blogin{color:var(--o81b);border:1px solid var(--o81);background:transparent;padding:7px 15px;border-radius:9px;font-weight:700;font-size:13px;text-decoration:none;transition:.2s;animation:puls81 2s infinite;animation-delay:1s}
  .nav81 .blogin:hover{background:rgba(232,80,26,.12)}
  .nav81 .bsignup{color:#fff;background:var(--o81);padding:8px 17px;border-radius:9px;font-weight:800;font-size:13px;text-decoration:none;box-shadow:0 0 0 0 rgba(232,80,26,.55);animation:puls81 2s infinite}
  .nav81 .lsw{display:flex;border:1px solid #2a2a30;border-radius:8px;overflow:hidden;margin-left:4px}
  .nav81 .lsw button{background:transparent;border:0;color:#9a9aa2;font:700 11px 'JetBrains Mono',monospace;padding:7px 9px;cursor:pointer}
  .nav81 .lsw button.on{background:var(--o81);color:#fff}
  .nav81 .tog{display:none;background:none;border:none;color:#fff;font-size:24px;cursor:pointer}
  @media(max-width:820px){
    .nav81 .tog{display:block}
    .nav81 .lk{position:fixed;top:58px;right:0;left:0;flex-direction:column;background:#0c0c0e;padding:16px;gap:14px;border-bottom:1px solid #1c1c1c;display:none}
    .nav81 .lk.open{display:flex}
    .nav81 .auth{margin-left:0}
  }
  /* Stelle recensioni, arancioni ovunque, regola globale ecosistema */
  .stars,.hr-stars,.stelle,[class*="-stars"],[class*="stars-"]{color:var(--o81b)!important}
  .foot81{background:#080808;border-top:1px solid #1c1c1c;color:#8a8a92;font-family:'Sora',Arial,sans-serif;padding:34px 20px 24px;margin-top:40px}
  .foot81 .in{max-width:1100px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:24px}
  .foot81 h4{color:#fff;font-size:14px;margin:0 0 10px;font-family:'Anton',sans-serif;letter-spacing:.5px}
  .foot81 a{color:#9a9aa2;text-decoration:none;font-size:13px;display:block;margin:5px 0}
  .foot81 a:hover{color:var(--o81b)}
  .foot81 .legal{max-width:1100px;margin:22px auto 0;padding-top:18px;border-top:1px solid #1a1a1a;font-size:12px;line-height:1.7;color:#6a6a72;text-align:center}
  .foot81 .legal b{color:#8a8a92}
  `;
  document.head.appendChild(css);

  // Voci essenziali. PIX81+ evidenziato, il cavallo di battaglia.
  var links=[
    ['index.html','Ecosistema'],
    ['recensioni.html','Recensioni'],
    ['audit.html','Audit Gratuito'],
    ['https://81plus.place','PIX81+','pix']
  ];

  function lkHtml(){
    return links.map(function(l){return '<a href="'+l[0]+'"'+(l[2]?' class="'+l[2]+'"':'')+'>'+l[1]+'</a>';}).join('');
  }
  function lswHtml(){
    var cur='it'; try{cur=localStorage.getItem('lang81')||'it';}catch(e){}
    return '<span class="lsw"><button data-l="it" class="'+(cur==='it'?'on':'')+'">IT</button><button data-l="en" class="'+(cur==='en'?'on':'')+'">EN</button></span>';
  }

  // Header identico ovunque. Le vecchie navbar di pagina vengono sostituite.
  // Le dashboard con sidebar sono app e restano come sono.
  var eApp=!!document.querySelector('.side');
  document.querySelectorAll('.s81bar').forEach(function(n){if(!eApp)n.remove();});
  var haNav=document.querySelector('.nav81')||eApp;
  if(!haNav){
    var nav=document.createElement('div');
    nav.className='nav81';
    nav.innerHTML=
      '<a class="lg" href="index.html"><span class="lgq">81+</span><span class="lgt">ECOSISTEMA</span></a>'+
      '<button class="tog" aria-label="menu" onclick="var l=this.parentNode.querySelector(\'.lk\');l.classList.toggle(\'open\')">&#9776;</button>'+
      '<div class="lk">'+lkHtml()+
        '<div class="auth"><a class="blogin" href="login.html">Accedi</a><a class="bsignup" href="signup.html">Registrati</a>'+lswHtml()+'</div>'+
      '</div>';
    document.body.insertBefore(nav,document.body.firstChild);
  }

  // Switch lingua reale, usa lang81Set di lang81.js
  document.addEventListener('click',function(e){
    var b=e.target.closest&&e.target.closest('.lsw button'); if(!b)return;
    document.querySelectorAll('.lsw button').forEach(function(x){x.classList.toggle('on',x===b);});
    if(window.lang81Set)window.lang81Set(b.getAttribute('data-l'));
    else{try{localStorage.setItem('lang81',b.getAttribute('data-l'));}catch(err){} location.reload();}
  });

  // Footer legale identico ovunque
  // Footer identico ovunque. I vecchi footer di pagina vengono sostituiti.
  if(!eApp){document.querySelectorAll('footer, .s81foot').forEach(function(n){n.remove();});}
  if(!document.querySelector('.foot81') && !eApp){
  var foot=document.createElement('div');
  foot.className='foot81';
  foot.innerHTML=
    '<div class="in">'+
      '<div><h4>Ecosistema</h4>'+
        '<a href="index.html">Home</a><a href="recensioni.html">Recensioni</a><a href="academy.html">Academy</a><a href="https://81plus.place">PIX81+</a><a href="costruzione.html">Diario di costruzione</a></div>'+
      '<div><h4>Strumenti</h4>'+
        '<a href="audit.html">Audit gratuito 30 normative</a><a href="preventivo.html">Preventivo</a><a href="strumenti.html">Tutti gli strumenti</a><a href="scadenze.html">Scadenziario</a></div>'+
      '<div><h4>Conto</h4>'+
        '<a href="login.html">Accedi</a><a href="signup.html">Registrati</a><a href="dashboard-utente.html">Area riservata</a><a href="ricarica_pv.html">Punti PV</a></div>'+
      '<div><h4>Legale</h4>'+
        '<a href="privacy.html">Privacy</a><a href="cookie.html">Cookie</a><a href="condizioni-vendita.html">Condizioni generali di vendita</a><a href="disclaimer.html">Disclaimer</a><a href="termini.html">Termini di servizio</a></div>'+
    '</div>'+
    '<div class="legal">'+
      '<b>Labo Tecnic Studio</b> &nbsp;·&nbsp; P.IVA IT01504180298 &nbsp;·&nbsp; Via Mantovana 78, 45014 Porto Viro (RO) &nbsp;·&nbsp; info@81plus.net<br>'+
      'I PV sono crediti fedelta interni non convertibili in denaro. 81X e un token di utilita e accesso, non un investimento. '+
      'Gli attestati di formazione obbligatoria sono emessi da enti accreditati indicati sull attestato e sono validi a norma di legge. '+
      'La rete opera secondo la L.173 del 2005, le commissioni derivano solo da vendite reali. Il franchising segue la L.129 del 2004.'+
    '</div>';
  document.body.appendChild(foot);
  }

  // Chat AI di competenza, tema scelto in base alla pagina
  if(!document.querySelector('.c81b') && !document.body.classList.contains('embed')){
    var pg=(location.pathname.split('/').pop()||'index.html').toLowerCase();
    var tema='ecosistema';
    if(/token81x|whitepaper/.test(pg))tema='token';
    else if(/pix|pixel|lancio/.test(pg))tema='pix';
    else if(/academy/.test(pg))tema='academy';
    else if(/shop/.test(pg))tema='shop';
    else if(/siconet|network|invita/.test(pg))tema='network';
    else if(/audit|strumenti|scadenz|preventivo|termometro/.test(pg))tema='sicurezza';
    else if(/haccp/.test(pg))tema='haccp';
    else if(/privacy_check|gdpr/.test(pg))tema='privacy';
    if(!/dashboard-admin|plancia|command/.test(pg)){
      var sc=document.createElement('script');
      sc.src='chat81_widget.js';
      sc.setAttribute('data-tema',tema);
      sc.setAttribute('data-base','');
      document.body.appendChild(sc);
    }
  }
})();
