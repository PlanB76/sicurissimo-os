/* 81+ NAV · Header e footer condivisi · Inseriti automaticamente in ogni pagina */
(function(){
const NAV_HTML=`
<nav id="nav81" style="position:fixed;top:0;left:0;right:0;z-index:999;background:rgba(5,5,10,0.95);border-bottom:1px solid #1a1a22;padding:0 16px;display:flex;align-items:center;height:48px;backdrop-filter:blur(12px)">
  <a href="/" style="display:flex;align-items:center;gap:6px;text-decoration:none">
    <span style="background:#E8501A;color:#fff;font-weight:900;padding:4px 8px;border-radius:6px;font-size:14px">81+</span>
  </a>
  <div style="display:flex;gap:4px;margin-left:auto" id="navLinks">
    <a href="/come-funziona.html" style="color:#888;text-decoration:none;font-size:12px;padding:6px 10px;border-radius:6px">Come funziona</a>
    <a href="/audit.html" style="color:#E8501A;text-decoration:none;font-size:12px;padding:6px 10px;border-radius:6px;font-weight:700">Audit gratis</a>
    <a href="/faq.html" style="color:#888;text-decoration:none;font-size:12px;padding:6px 10px;border-radius:6px">FAQ</a>
    <a href="/contatti.html" style="color:#888;text-decoration:none;font-size:12px;padding:6px 10px;border-radius:6px">Contatti</a>
  </div>
  <div style="display:flex;gap:4px;margin-left:12px" id="navAuth">
    <a href="/login.html" style="color:#c8c8d2;text-decoration:none;font-size:12px;padding:6px 12px;border:1px solid #1a1a22;border-radius:8px">Accedi</a>
    <a href="/signup.html" style="color:#fff;text-decoration:none;font-size:12px;padding:6px 12px;background:#E8501A;border-radius:8px;font-weight:600">Registrati</a>
  </div>
</nav>`;

const FOOTER_HTML=`
<footer id="foot81" style="background:#0a0a0f;border-top:1px solid #1a1a22;padding:24px 20px;margin-top:40px">
  <div style="max-width:900px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:20px;font-size:11px;color:#555">
    <div>
      <div style="color:#E8501A;font-weight:700;font-size:13px;margin-bottom:8px">81+ Ecosistema</div>
      <a href="/come-funziona.html" style="color:#666;text-decoration:none;display:block;padding:2px 0">Come funziona</a>
      <a href="/chi-siamo.html" style="color:#666;text-decoration:none;display:block;padding:2px 0">Chi siamo</a>
      <a href="/recensioni.html" style="color:#666;text-decoration:none;display:block;padding:2px 0">Recensioni</a>
      <a href="/faq.html" style="color:#666;text-decoration:none;display:block;padding:2px 0">FAQ</a>
    </div>
    <div>
      <div style="color:#3FBF6B;font-weight:700;font-size:13px;margin-bottom:8px">Servizi</div>
      <a href="/audit.html" style="color:#666;text-decoration:none;display:block;padding:2px 0">Audit gratuito</a>
      <a href="/academy.html" style="color:#666;text-decoration:none;display:block;padding:2px 0">Academy</a>
      <a href="/shop.html" style="color:#666;text-decoration:none;display:block;padding:2px 0">Shop</a>
      <a href="/pix81.html" style="color:#666;text-decoration:none;display:block;padding:2px 0">PIX81+</a>
    </div>
    <div>
      <div style="color:#888;font-weight:700;font-size:13px;margin-bottom:8px">Legale</div>
      <a href="/privacy.html" style="color:#666;text-decoration:none;display:block;padding:2px 0">Privacy</a>
      <a href="/cookies.html" style="color:#666;text-decoration:none;display:block;padding:2px 0">Cookie</a>
      <a href="/condizioni.html" style="color:#666;text-decoration:none;display:block;padding:2px 0">Condizioni</a>
      <a href="/disclaimer.html" style="color:#666;text-decoration:none;display:block;padding:2px 0">Disclaimer</a>
    </div>
    <div>
      <div style="color:#888;font-weight:700;font-size:13px;margin-bottom:8px">Contatti</div>
      <a href="mailto:info@81plus.net" style="color:#666;text-decoration:none;display:block;padding:2px 0">info@81plus.net</a>
      <a href="/contatti.html" style="color:#666;text-decoration:none;display:block;padding:2px 0">Contattaci</a>
      <div style="margin-top:8px;color:#444">Labo Tecnic Studio<br>P.IVA IT01504180298<br>Porto Viro (RO)</div>
    </div>
  </div>
  <div style="text-align:center;margin-top:20px;color:#333;font-size:10px">© 2003-2026 Labo Tecnic Studio. Tutti i diritti riservati. 81+ è un marchio registrato.</div>
</footer>`;

// Insert nav at top of body
if(!document.getElementById('nav81')){
  document.body.insertAdjacentHTML('afterbegin',NAV_HTML);
  document.body.style.paddingTop='48px';
}

// Insert footer at end of body
if(!document.getElementById('foot81')){
  document.body.insertAdjacentHTML('beforeend',FOOTER_HTML);
}

// Check if logged in → change nav
fetch('/api/auth.php?action=chi').then(r=>r.json()).then(d=>{
  if(d&&d.ok&&d.sic){
    const auth=document.getElementById('navAuth');
    if(auth) auth.innerHTML=`<a href="/dashboard-utente.html" style="color:#fff;text-decoration:none;font-size:12px;padding:6px 12px;background:#E8501A;border-radius:8px;font-weight:600">${d.sic}</a>`;
  }
}).catch(()=>{});
})();
