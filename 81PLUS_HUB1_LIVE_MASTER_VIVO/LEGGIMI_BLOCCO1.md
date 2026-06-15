# 81PLUS_HUB1_LIVE_MASTER_VIVO — BLOCCO 1
## Struttura tecnica HUB1 81plus.net — Pronta per revisione

Generato il: 15/06/2026

---

## Struttura cartella

```
81PLUS_HUB1_LIVE_MASTER_VIVO/
├── public_html/           ← ROOT del sito Hostinger
│   ├── index.php          ← Home (doppia porta: Imprenditore / GENESYS81+)
│   ├── signup.php         ← Registrazione
│   ├── login.php          ← Accesso
│   ├── logout.php         ← Disconnessione
│   ├── dashboard.php      ← Area personale (tutti i ruoli)
│   ├── profilo.php        ← Gestione profilo
│   ├── kyc.php            ← Verifica identita (+1000 PV+)
│   ├── audit.php          ← Audit gratuito 81/08 HACCP
│   ├── preventivo.php     ← DOC81+ Builder
│   ├── paygate81.php      ← Ricarica PV / PayGate81+
│   ├── membership.php     ← Scegli piano (BASIC/PRO/ELITE)
│   ├── recensioni.php     ← Testimonianze reali
│   ├── faq.php            ← FAQ
│   ├── genesys81.php      ← Programma GENESYS81+
│   ├── pix81.php          ← PIX81+ griglia 1000 slot
│   ├── green81.php        ← Green81+ foresta e DAO
│   ├── network81.php      ← NETWORK81+ area commerciale
│   ├── scout81.php        ← SCOUT81+ ricerca prospect
│   ├── libri.php          ← Libri e risorse
│   ├── eventi.php         ← Eventi e webinar
│   ├── chi-siamo.php      ← Chi siamo
│   ├── club81.php         ← Club81+ (ELITE+/GENESYS LEADER+)
│   ├── franchising.php    ← Franchising 81+
│   ├── shop81.php         ← Shop prodotti digitali
│   ├── academy81.php      ← Academy 81+
│   ├── blog.php           ← Blog normativo
│   ├── news.php           ← News normative
│   ├── newsletter.php     ← Iscrizione newsletter
│   ├── ecosistema3d.php   ← Pipeline3D visualizzazione rete
│   ├── cervello3d.php     ← Compensi3D e Equilibrio
│   ├── privacy.php        ← Privacy Policy
│   ├── cookie.php         ← Cookie Policy
│   ├── condizioni-generali-vendita.php
│   ├── termini.php        ← Termini di utilizzo
│   ├── disclaimer.php     ← Disclaimer documentale e normativo
│   ├── admin.php          ← Command Center (solo ADMIN81)
│   ├── includes/          ← File condivisi (NON accessibili via HTTP)
│   │   ├── config.php     ← Costanti, sessione, carica core81
│   │   ├── db.php         ← Solo referenza (il vero e in core81/)
│   │   ├── auth.php       ← auth_require(), auth_user(), is_logged()
│   │   ├── header.php     ← HTML head, meta, CSS imports
│   │   ├── nav.php        ← Navigazione principale
│   │   ├── footer.php     ← Footer, cookie banner, JS base
│   │   ├── helpers.php    ← e(), redirect(), json_ok/err(), csrf_, format_pv(), sic_id_generate()
│   │   └── semantic_guard.php ← Filtro parole vietate
│   ├── core81/            ← Servizi PHP (NON accessibili via HTTP)
│   │   ├── db.php         ← PDO singleton
│   │   ├── auth_guard.php ← auth_guard() per le API
│   │   ├── dashboard_modules.php
│   │   ├── scout81_service.php
│   │   ├── plp_pack_service.php
│   │   ├── pvplus_booster_service.php
│   │   ├── pipeline3d_service.php
│   │   ├── territory_service.php
│   │   ├── admin_command_service.php
│   │   ├── referral_service.php
│   │   ├── document_builder.php
│   │   ├── scadenziario.php
│   │   ├── academy_access.php
│   │   └── welcome_email.php  ← Email da welcome@81plus.net
│   ├── api/               ← Endpoint JSON (22 file)
│   │   ├── signup.php     ← POST registrazione
│   │   ├── login.php      ← POST login
│   │   └── [20 endpoint esistenti copiati da live/api/]
│   └── assets/
│       ├── css/           ← style.css, home.css, dashboard.css, dashboard3d.css
│       ├── js/            ← scout81map.js, pipeline3d.js, territorymap81.js, compensi3d.js, equilibrio3d.js, cookie.js
│       └── img/           ← Cartella per loghi, favicon, og-image (aggiungere manualmente)
├── sql/
│   ├── LEGGIMI_SQL.md     ← Ordine esecuzione
│   ├── MYSQL1_INSTALL_81PLUS_HUB1.sql          (1. SCHEMA BASE)
│   ├── MYSQL_DELTA_DASHBOARD_ACADEMY_DOC_SCADENZIARIO.sql
│   ├── MYSQL_DELTA_PIPELINE_TERRITORY_ADMIN_3D.sql
│   ├── MYSQL_DELTA_NETWORKER_SCOUT_PLP_PIANI.sql
│   ├── MYSQL_DELTA_SCOUT81_PROSPECTS.sql
│   └── MYSQL_DELTA_PVPLUS_BOOSTER.sql           (6. ULTIMO)
├── docs/
│   ├── master/            ← MASTER_VIVO e POLIZIA_SEMANTICA
│   ├── launch/            ← HUB1 launch specs, copy, email, WhatsApp, Telegram
│   ├── dashboard/         ← Dashboard spec, roles matrix, strumenti, scadenziario
│   ├── scout81/           ← Scout81+ master, map, scoring, provider, PLP
│   ├── network81/         ← Networker dashboard, referral, pipeline3D, compensi
│   ├── genesys81/         ← GENESYS81+ launch structure, PIX81+ special pack
│   ├── legal/             ← Admin command center spec
│   ├── visual/            ← 29 visual brief Gemini per asset UI
│   └── archivio_master/   ← Archivio ZIP (43 file DNA ecosistema)
├── .env.example           ← Template variabili d'ambiente (NON committare .env reale)
└── LEGGIMI_BLOCCO1.md     ← Questo file

```

---

## Checklist pre-revisione

### Struttura

- [x] 34 pagine PHP create (incluso admin.php)
- [x] 8 includes (config, db, auth, header, nav, footer, helpers, semantic_guard)
- [x] 14 servizi core81 (13 copiati + welcome_email.php nuovo)
- [x] 22 endpoint API (20 copiati + signup.php + login.php nuovi)
- [x] 4 CSS + 6 JS da live/assets/
- [x] 6 SQL file copiati + LEGGIMI_SQL.md
- [x] 36 docs da WAVE1_MASTER + 29 visual brief + 43 archivio ZIP
- [x] .htaccess con sicurezza, HTTPS forzato, blocco /includes/ e /core81/
- [x] .env.example con tutti i placeholder

### Sicurezza implementata

- [x] CSRF token su tutti i form POST
- [x] Password hash bcrypt cost=12
- [x] SIC-ID generato server-side con HMAC-SHA256
- [x] PV+: accredito idempotente via UNIQUE KEY (mantenuto da pvplus_booster_service.php)
- [x] auth_require() su tutte le pagine protette
- [x] includes/ e core81/ bloccati da .htaccess
- [x] semantic_guard.php per filtrare parole vietate
- [x] Email di benvenuto da welcome@81plus.net
- [x] footer disclaimer: "I PV non sono denaro elettronico. I PV+ non sono rendimento."
- [x] Nessuna credenziale nel codice — solo $_ENV[]

### Da fare prima del deploy

- [ ] Aggiungere img/: logo-81plus.svg, favicon.svg, og-image.jpg
- [ ] Compilare .env con credenziali reali
- [ ] Ruotare: Groq API key, PayPal Plan ID, BSC private key, Hostinger API key, SIC_SECRET
- [ ] Caricare .env su Hostinger tramite SSH (NON via FTP/cPanel esposto)
- [ ] Eseguire SQL nell'ordine indicato in sql/LEGGIMI_SQL.md
- [ ] Verificare CORS whitelist per API (mai `*` su endpoint autenticati)
- [ ] Test signup → email di benvenuto → dashboard
- [ ] Test paygate81 → membership activation → PV+ credit idempotent

---

## Regole inviolabili (portate avanti da sessione precedente)

- SIC-ID generato automaticamente, MAI come CTA
- PV accreditati solo server-side, MAI via JS client
- Nessun nome/telefono del fondatore nelle pagine pubbliche
- I documenti DOC81+ sono SEMPRE bozze con disclaimer obbligatorio
- Partner Academy NON visibili sul lato utente
- CORS: MAI `*` su endpoint autenticati
- welcome@81plus.net per tutte le email di sistema
