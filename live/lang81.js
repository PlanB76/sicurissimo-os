// 81+ LANG ENGINE. Vero switch italiano inglese su tutto il sito.
// Funziona in due modi insieme. Primo, gli elementi con data-i18n usano il dizionario per chiave.
// Secondo, una mappa di testo esatto traduce i nodi di testo di ogni pagina, senza toccare il codice.
// La scelta resta salvata e si applica da sola a ogni pagina.
(function(){
if(window.__lang81)return;window.__lang81=1;

var MAP={
// navigazione e azioni comuni
"Ecosistema":"Ecosystem","Recensioni":"Reviews","Strumenti":"Tools","Blog":"Blog","Libri":"Books","Newsletter":"Newsletter",
"Accedi":"Log in","Registrati":"Sign up","Iscriviti":"Subscribe","Entra":"Enter","Esci":"Log out",
"Registrati e ricevi 1000 PV":"Sign up and get 1000 PV","Registrati subito e prendi 1000 PV":"Sign up now and get 1000 PV",
"Fai l’audit gratuito":"Take the free audit","Audit gratuito":"Free audit","Fai ora":"Do it now",
"Torna alla home":"Back to home","Torna alla dashboard":"Back to dashboard","Torna al profilo":"Back to profile","Torna all’accesso":"Back to login",
"Dashboard":"Dashboard","Profilo":"Profile","Scarica":"Download","Apri":"Open","Salva":"Save","Invia":"Send","Conferma":"Confirm","Annulla":"Cancel",
"Stampa o salva in PDF":"Print or save as PDF","Word":"Word","PDF":"PDF",
// accesso e account
"Email":"Email","Password":"Password","Nome":"First name","Cognome":"Last name","Telefono":"Phone",
"Password dimenticata?":"Forgot your password?","Invia il link di recupero":"Send recovery link",
"Reimposta password":"Reset password","Nuova password":"New password","Ripeti la password":"Repeat password",
"Salva la nuova password":"Save new password","Password attuale":"Current password",
"Aggiorna password":"Update password","Aggiorna email":"Update email","Nuova email":"New email",
"Conferma con la password":"Confirm with your password","Non hai un accesso?":"No account yet?",
"Accedi con il wallet, Wallet Connect":"Log in with your wallet, Wallet Connect",
"Chi sei":"Who you are","Azienda o partita IVA":"Company or VAT holder","Dipendente o privato":"Employee or private individual",
"Codice ATECO della tua attività":"Your business ATECO code","Codice invito, se ne hai uno":"Invite code, if you have one",
"Codice fiscale, facoltativo ora, necessario per i wallet":"Tax code, optional now, required for wallets",
// dashboard, titoli delle sezioni
"I tuoi numeri":"Your numbers","Livelli VIP e missioni del giorno":"VIP levels and daily missions",
"Cosa sono i PV, spiegazione completa":"What PV are, full explanation","81plus Academy":"81plus Academy",
"Mettiti in regola adesso, formazione certificata":"Get compliant now, certified training",
"Scegli il corso obbligatorio":"Choose your mandatory course","Scegli il corso di crescita":"Choose your growth course",
"Diretta e community":"Live and community","Webinar live":"Live webinar","Vai alle dirette":"Go to live streams",
"Canale e iscrizione":"Channel and subscribe","Gruppi Telegram":"Telegram groups","Entra nella community 81+":"Join the 81+ community",
"Il tuo settore e i tuoi adempimenti":"Your sector and your obligations",
"Modelli universali da scaricare":"Universal templates to download",
"I libri della direzione":"Books by the management","Acquista su Amazon":"Buy on Amazon",
"Costanza, badge e traguardo community":"Consistency, badges and community goal",
"Catalogo premi VIP":"VIP rewards catalog","Centro Scadenze":"Deadline Center","Centro Opportunità":"Opportunity Center",
"Sicurezza, per tutti":"Safety, for everyone","Privacy, per tutti":"Privacy, for everyone",
"HACCP, obbligatorio per il tuo settore":"HACCP, mandatory for your sector",
"Entra nell’Academy":"Enter the Academy","Modulistica con i PV":"Templates with PV",
"La tua SIC Card":"Your SIC Card","Apri la SIC Card":"Open your SIC Card",
"SICUREZZA DELL’ACCESSO":"ACCOUNT SECURITY","Cambia password":"Change password","Cambia email":"Change email",
// sic card
"Muovi il mouse sulla card, o inclina il telefono, e guardala vivere. Il QR porta al tuo link di invito personale. La card si rinnova da sola a ogni passaggio di grado VIP, colori e materiale cambiano col tuo rango.":"Move your mouse over the card, or tilt your phone, and watch it come alive. The QR leads to your personal invite link. The card renews itself at every VIP rank upgrade, colors and material change with your rank.",
// premi
"Ogni livello accredita PV EXTRA una volta sola e sblocca premi digitali. Il tuo livello si calcola sui PV totali guadagnati.":"Each level credits EXTRA PV once and unlocks digital rewards. Your level is based on total PV earned.",
// cookie banner e footer
"Usiamo cookie tecnici per far funzionare il sito e, solo col tuo consenso, cookie di statistica e marketing. Scegli tu.":"We use technical cookies to run the site and, only with your consent, analytics and marketing cookies. You choose.",
"Dettagli":"Details","Solo tecnici":"Technical only","Accetta tutti":"Accept all",
"Privacy":"Privacy","Cookie":"Cookies","Condizioni di vendita":"Terms of sale","Termini":"Terms","Disclaimer":"Disclaimer",
// libri
"I libri della direzione, le pubblicazioni":"Books by the management","Le pubblicazioni":"Publications",
"Guide pratiche su sicurezza, igiene alimentare e crescita. Scritte da Sicurissimo, disponibili su Amazon. Chi completa il profilo riceve in regalo il libro Addio Burocrazia.":"Practical guides on safety, food hygiene and growth. Written by Sicurissimo, available on Amazon. Complete your profile and get the book Addio Burocrazia as a gift.",
// benvenuto
"Benvenuto Member 81+":"Welcome, 81+ Member","Completa il profilo":"Complete your profile",
"Sei in regola con la formazione obbligatoria":"Are you compliant with mandatory training",
"Vedi i corsi obbligatori":"See mandatory courses",
// blog
"Il blog 81+":"The 81+ blog","leggi":"read",
// generiche
"In attivazione":"Activation pending","accedi per vedere i tuoi KPI":"log in to see your KPIs",
"Pronto.":"Ready.","Errore, riprova.":"Error, try again.","Backend non raggiungibile.":"Backend not reachable."
};

function lingua(){ try{return localStorage.getItem('lang81')||'it';}catch(e){return 'it';} }
function salva(l){ try{localStorage.setItem('lang81',l);}catch(e){} }

// traduce i nodi di testo con la mappa esatta, avanti e indietro
var INV=null;
function inverti(){ if(INV)return INV; INV={}; for(var k in MAP)INV[MAP[k]]=k; return INV; }
function traduciTesto(daIt){
 var dict=daIt?MAP:inverti();
 var walker=document.createTreeWalker(document.body,NodeFilter.SHOW_TEXT,null,false);
 var nodi=[]; while(walker.nextNode())nodi.push(walker.currentNode);
 nodi.forEach(function(n){
  var t=n.nodeValue, tt=t.trim();
  if(tt && dict[tt]!==undefined){ n.nodeValue=t.replace(tt,dict[tt]); }
 });
 // anche i placeholder e i value dei bottoni
 document.querySelectorAll('input[placeholder],button,option,a').forEach(function(e){
  var p=e.getAttribute&&e.getAttribute('placeholder');
  if(p&&dict[p.trim()]!==undefined)e.setAttribute('placeholder',dict[p.trim()]);
 });
}

// dizionario per chiave, per le pagine che usano data-i18n
function traduciChiavi(l){
 fetch('data/i18n.json').then(function(r){return r.json();}).then(function(d){
  var t=d[l]||d.it||{};
  document.querySelectorAll('[data-i18n]').forEach(function(e){ if(t[e.dataset.i18n])e.textContent=t[e.dataset.i18n]; });
 }).catch(function(){});
}

window.lang81Set=function(l){
 var prima=lingua(); salva(l);
 if(l==='en'&&prima!=='en')traduciTesto(true);
 if(l==='it'&&prima!=='it')traduciTesto(false);
 traduciChiavi(l);
 document.documentElement.lang=l;
 var b=document.getElementById('lang81sw'); if(b)b.textContent=(l==='en'?'IT':'EN');
 // compatibile col selettore della home
 var sel=document.getElementById('lang'); if(sel&&sel.value!==l)sel.value=l;
};

function boot(){
 // interruttore fisso solo se l header non ha gia lo switch lsw
 if(!document.getElementById('lang81sw') && !document.querySelector('.nav81 .lsw, .lsw')){
  var b=document.createElement('button'); b.id='lang81sw';
  b.style.cssText='position:fixed;bottom:16px;left:16px;z-index:99998;background:#14141f;border:1px solid #E8501A;color:#fff;border-radius:9px;padding:8px 13px;font-family:Sora,Arial,sans-serif;font-size:12px;font-weight:700;cursor:pointer;box-shadow:0 6px 18px rgba(0,0,0,.5)';
  b.title='Cambia lingua, switch language';
  b.onclick=function(){ lang81Set(lingua()==='en'?'it':'en'); };
  document.body.appendChild(b);
 }
 var l=lingua();
 var sw=document.getElementById('lang81sw'); if(sw)sw.textContent=(l==='en'?'IT':'EN');
 if(l==='en'){ traduciTesto(true); traduciChiavi('en'); document.documentElement.lang='en'; }
 // aggancio il selettore della home se presente
 var sel=document.getElementById('lang'); if(sel){ sel.value=l; sel.onchange=function(){ lang81Set(this.value); }; }
 // i contenuti caricati dopo, traduco di nuovo con calma
 if(l==='en'){ setTimeout(function(){traduciTesto(true);},1400); setTimeout(function(){traduciTesto(true);},3200); }
}
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',boot);else boot();
})();
