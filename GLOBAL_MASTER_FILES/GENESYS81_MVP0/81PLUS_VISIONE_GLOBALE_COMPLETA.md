# 81+ GLOBAL OS · VISIONE GLOBALE COMPLETA
Aggiornato 2026-06-20. Lettura unica di tutto: i due livelli, i moduli, i flussi, cosa c'e e cosa manca,
e il ruolo del foglio MASTERBLASTER come nodo centrale condiviso tra le AI.

## A. I DUE LIVELLI (capire questo spiega tutto)
L'ecosistema vive su due piani che devono parlarsi:

LIVELLO 1 — WEB + DATABASE (Hostinger, 81plus.net, DB u173050672_81plusglobal)
  E' il prodotto reale: pagine, API PHP, dati veri, utenti, prospect, giochi 3D.
  Fonte di verita dei dati.

LIVELLO 2 — SHEET + APPS SCRIPT (Google MASTERBLASTER)
  E' il cervello operativo e il pannello di comando: registri, KPI, code, missioni,
  automazioni a tempo, e la bacheca condivisa tra Claude, gemma, gpt-oss.
  Nodo centrale di orchestrazione.

Il ponte tra i due livelli sono gli script .gs che leggono/scrivono le API PHP (es. SCOUT81 prospect engine
gia sincronizza DB -> Sheet). Questo e il collante che stiamo completando.

## B. INVENTARIO MODULI

### B1. Livello WEB+DB (su 81plus.net/public_html)
- SCOUT81+   /admin/scout81/    prospect e lead: scraping OSM, HUNTER open data CKAN, VIES P.IVA, AI conversione A/B/C, export.
- SFERA81+   /admin/sfera81/     motore gamificato server: daily, missioni, rewards, escalation, lifewheel, badge, eventi.
- GIOCHI81+  /admin/sfera81/giochi/   8 giochi 3D (piramide, ruota vita, bhava, ikigai, purpose, leadership, learning, empower) + classifiche.
- GEM81+     /admin/gem81/       scanner gemme crypto via proxy (geckoterminal, goplus, honeypot, dexscreener). L'utente firma sempre.
- LEX81+     /admin/lex81/       database normativo + audit ATECO (valori DA_VERIFICARE finche non validati).
- LEADGEN81+ /admin/lex81/       acquisizione e nurturing etico: 81 flussi + Avvento 365 + doppio opt-in. Legato a SCOUT81.
- MAPPA81+   /admin/mappa81.html  indice di tutti i link.
- Area utente: home, login, SIC-ID, dashboard, wallet PV+, PayGate, audit, compliance, PIX81, recensioni, GENESYS81.

### B2. Livello SHEET+APPS SCRIPT (MASTERBLASTER)
Moduli "OperativeSection" gia tuoi (creano sezioni dentro il foglio):
- TEAM AI ENGINE V4        cervello comandi/agenti AI (installONECLICK_MASTERBLASTER81).
- SCOUT81 PROSPECT ENGINE  sync prospect DB -> Sheet + trigger (scoutInstallAll).
- CASHCOW81+               YouTube Sicurissimo, archivio video, Shorts, palinsesto, KPI.
- GAMIFICATION81+          status, missioni, badge, PV+, streak, leaderboard, ruota, piramide, pass/kit.
- CASHBACK81+              cashback/benefit interni, voucher, referral, ledger PV/PV+, ordini, audit compliance.
- SFERA81+ (sheet)         ruota vita, piramide Maslow, abitudini, diario, sfide 90 giorni, priorita, anti-procrastinazione.
Nuovo: NODO CENTRALE 81+ (in /96_APPSCRIPT_81PLUS) = CORE condiviso + un file ponte per ogni strumento,
       che collega le OperativeSection al DB reale via le API PHP. In costruzione.

## C. FILO CONDUTTORE: COME GIRA TUTTO
1. SCOUT81 trova e arricchisce prospect (DB).  ->  sync su Sheet (tab prospect).
2. LEADGEN81 importa prospect come contatti (consenso 0), parte il doppio opt-in. Nessun invio prima della conferma.
3. LEX81 dice, per ATECO, quali temi/obblighi valgono (sicurezza sempre, privacy sempre, HACCP se alimentare).
4. L'utente entra (SIC-ID), gioca i giochi 3D, completa MISSIONI reali su quei temi.
5. Ogni missione da PV+: vanno nel WALLET (buoni sconto) e riempiono gli assi dei giochi per tema.
6. CASHBACK81 registra benefit/voucher da acquisti e servizi reali (PV/PV+ interni, mai denaro).
7. GAMIFICATION81 tiene status (MEMBER..CLUB), badge, leaderboard, con tetti per status.
8. CASHCOW81 alimenta contenuti (YouTube Sicurissimo) che portano nuovi utenti, CTA unica 81plus.net.
9. MASTERBLASTER fa da nodo: KPI, code, log, report, e bacheca per le AI.

## D. REGOLE FERME (compliance e tono)
- Base: ATECO / rischio 81.08. Temi cardine: SICUREZZA (sempre), PRIVACY (sempre), HACCP (solo alimentare).
- PV e PV+ sono utility interna, NON denaro. Cashback solo da acquisti/servizi/campagne reali.
- Semantic guard: vietati investimento, rendimento, ROI, APY, staking, rischio zero, zero multe, guadagno garantito.
- LEX: non inventare sanzioni. Senza fonte interna ufficiale = DA_VERIFICARE.
- LEADGEN: nessuna email senza doppio opt-in confermato.
- GEM: nessuna esecuzione di trade o movimento denaro. L'utente firma sempre.
- CTA unica ovunque: 81plus.net.
- Segreti e chiavi: solo Script Properties (Apps Script) o api/.env (server). MAI nel foglio, MAI nelle chat.

## E. IL NODO CENTRALE (perche e la mossa giusta)
Un solo progetto Apps Script BOUND al MASTERBLASTER con:
- CONTROL PLANE su tab: CONFIG, TASKS81 (coda AI), LOG81, KPI81, + tab per modulo.
- SYNC DUE VIE Sheet <-> DB via le API PHP (pull con full_since, push con award/complete/ingest).
- TRIGGER A TEMPO: sync 10 min, KPI ogni ora, snapshot+reset cap+hunter ogni giorno.
- WEB APP doPost: punto unico dove le AI scrivono task/eventi (token in Script Properties).
- ORCHESTRAZIONE AI: il foglio e la memoria condivisa di Claude, gemma, gpt-oss. Tutti leggono TASKS/CONFIG/LOG e scrivono esiti.
Vantaggio: un cervello unico, multi-AI, che vede tutti i moduli e li tiene allineati al DB, anche a PC spento.
Limiti da rispettare: quota Apps Script (6 min/esecuzione), job pesanti spezzati.

## F. STATO: FATTO vs DA FARE
FATTO
- SCOUT81 pipeline completa (scraping, open data, VIES, AI scoring, export) + sync Sheet.
- SFERA/GIOCHI 8 giochi 3D azione-driven con PV+, missioni a tema, tetti, classifiche (client + endpoint server).
- LEX81 e LEADGEN81 installati, 81 flussi + Avvento + doppio opt-in seminati.
- GEM81 scanner. CASHCOW/GAMIFICATION/CASHBACK/SFERA OperativeSection sul foglio.
- Deploy un click (CARICA.bat) + autopilota locale (gemma/gpt-oss) + blueprint registrato.
- Riepiloghi per Apps Script (gamification + nodo centrale).

DA FARE
- Completare il NODO CENTRALE: file ponte .gs per ogni strumento (CORE gia pronto).
- Persistenza reale giochi multi-utente su DB (game_state, game_leaderboard, pvplus_ledger).
- Invio email reale LEADGEN (SMTP/SendGrid) dopo doppio opt-in.
- Popolare LEX dalle fonti ufficiali (validare i DA_VERIFICARE).
- Unificare i menu onOpen se metti piu moduli nello stesso progetto (oggi 1 onOpen nel CORE).

## G. ROADMAP CONSIGLIATA (ordine)
1. Chiudere il NODO CENTRALE (ponti .gs SCOUT/GIOCHI/LEX/LEADGEN/GEM) e collegarlo al MASTERBLASTER.
2. Creare le tabelle DB di persistenza giochi e agganciare i PV+ veri.
3. Attivare invio email LEADGEN post doppio opt-in.
4. Riempire LEX con norme validate.
5. Collegare CASHCOW (YouTube) come motore di acquisizione contenuti -> SCOUT/LEADGEN.

## H. NOTA NODO E AI CONDIVISE
Il foglio MASTERBLASTER condiviso e l'idea giusta: diventa il nodo unico dove le AI collaborano,
con i dati reali nel DB e il controllo nel foglio. Tieni i segreti fuori dal foglio e il DB come verita.
