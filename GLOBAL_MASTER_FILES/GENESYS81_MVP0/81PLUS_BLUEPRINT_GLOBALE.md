# 81+ SICURISSIMO GLOBALE · BLUEPRINT GLOBALE UNICO
Aggiornato 2026-06-20. Documento-radice che riassume TUTTO: visione, architettura, moduli, cosa e
costruito, automazione, regole, stato e roadmap. Base canonica = ORGANIGRAMMA OPERATIVO DOMINI v3
(03_HUB1_81PLUS_NET/2026-06-20_..._v3_validazione.md). In caso di conflitto, vince l'organigramma.

## 1. COS'E 81+ (in una riga)
Un sistema operativo digitale per la sicurezza/compliance delle imprese (sicurezza 81.08, privacy GDPR,
HACCP), che acquisisce imprese reali, le ingaggia con gamification a missioni reali, le converte in
member/clienti/networker, su un'economia interna a PV/PV+ (utility, mai denaro). Casa madre: 81plus.net.

## 2. ARCHITETTURA: BRIDGE + 3 HUB
BRIDGE  81plus.online — ponte narrativo e community tra gli HUB.
HUB1 CORE/ORCHESTRAZIONE — 81plus.net (identita, login, SIC-ID, wallet, dashboard, AI, CRM, MySQL centrale),
        81plus.cloud (storage), 81plus.cards (card/QR/SIC-ID/badge). Satelliti: ai, app, global, systems, id, pass, badge...
HUB2 WEB2/COMPLIANCE — sicurissimo.online (motore soldi reali: audit, HACCP, privacy, preventivatore, corsi),
        81plus.it (istituzionale), 81plus.network (rete vendita), 81plus.academy (formazione),
        81plus.club (premium), 81plus.shop, 81plus.zone (franchising/SICURISSIMO POINT81+), 81plus.christmas.
HUB3 WEB3/ESPANSIONE — sicurissimo.io (gateway), 81plus.digital (DApp/SAF/wallet utility), 81plus.exchange,
        81plus.world (metaverso), 81plus.org (DAO), 81plus.place (CASA di PIX81+), 81plus.store (NFT/RWA).
        Finanziari (credit/bond/finance/defi/exchange) = PARCHEGGIO finche non validati legale/fiscale.

## 3. MODULI TRASVERSALI (dentro 81plus.net, NON domini)
- LEX81+      pilastro normativo: fonte unica sicurezza/HACCP/privacy; alimenta audit, risk radar, AI, preventivatore, missioni.
- SFERA81+    motore gamification/retention: profilo ATECO/RISCHIO -> missioni, ESCALATION81+, LIFEWHEEL81+, PV+.
- LEADGEN81+  flussi: prospect -> member/cliente/networker/elite/franchiser/club (opt-in + doppio opt-in).
- BOOSTER81+  promo admin: Daily Spark 1 -> 50 PV+, 1 volta/giorno, anti-duplicato.
- LOCK81+     blocco volontario PV/PV+ per benefit/accessi/badge. NON staking, NON rendimento.
- PAYGATE81+  pagamenti, ricariche PV, membership, ordini, ricevute/fatture, log, wallet.

## 4. COSA E GIA COSTRUITO

### 4A. LIVELLO WEB + DATABASE (Hostinger, 81plus.net/public_html/admin) — DB u173050672_81plusglobal
- SCOUT81+  /scout81/  prospect e lead. File: collector.php (scraping OSM per regione+macro ATECO, IT nazionale, ALL),
    enrich.php (territorio+ATECO), webenrich.php (email/PEC), piva_api.php (VIES P.IVA->nome/indirizzo),
    hunter.php (open data CKAN Italia: preset+discover+ingest), ai_score.php (conversione A/B/C, Groq+regole),
    auto_scrape.php (rotazione + battito), tools_api.php (score/funnel/setstage), prospect_api.php (stats/search/geo/full_since/add),
    plp_api.php (listino/pack/buy/gift), export.php, report.php, groq_chat.php, install_scout.php, index.html (dashboard gamificata mobile).
- SFERA81+  /sfera81/  motore gamificato server + GIOCHI81+. API: daily, missions, rewards, escalation, lifewheel, badge, events.
    8 GIOCHI 3D in /giochi/: piramide(Maslow), ruota-vita(12), ruota-esistenza(bhava 6), ikigai(Venn 4), purpose(golden circle 3),
    leadership(5), learning(albero 8), empower(nucleo 5) + classifiche.html, engine.js (azione/missione-driven, PV+->wallet+assi, tetti per status).
- LEX81+ e LEADGEN81+  /lex81/  api lex81.php (risk_map/obligations/audit, valori DA_VERIFICARE), leadgen.php (create_contact/trigger/start_flow),
    confirm.php (doppio opt-in), import_from_scout.php, seed_flows.php (81 flussi + FLOW_DOI + FLOW_XMAS_AVVENTO_365 + segmenti),
    install_lexleadgen.php, schema SQL, docs fonti ufficiali.
- GEM81+  /gem81/  scanner gemme crypto via proxy (geckoterminal/goplus/honeypot/dexscreener). L'utente firma sempre.
- Altri: control81, idea81, mappa81.html (indice link), area utente (login/SIC-ID/dashboard/wallet/paygate/audit/compliance/pix/recensioni).

### 4B. LIVELLO SHEET + APPS SCRIPT (MASTERBLASTER) — nodo centrale
- TEAM AI ENGINE V4 (comandi/agenti AI), SCOUT81 PROSPECT ENGINE (sync DB->Sheet), CASHCOW81+ (YouTube Sicurissimo),
  GAMIFICATION81+ (status/missioni/badge/PV+/leaderboard), CASHBACK81+ (benefit/voucher/referral/ledger), SFERA81+ (ruota/piramide/abitudini/diario/sfide90).
- NODO CENTRALE 81+ (in 96_APPSCRIPT_81PLUS): CORE condiviso pronto (_00_CORE81.gs) + ponti per strumento da completare;
  sync due vie Sheet<->DB via API PHP, trigger a tempo, Web App doPost come punto unico per le AI, segreti in Script Properties.

### 4C. AUTOMAZIONE E DEPLOY (lato PC)
- CARICA.bat -> SCOUT81_AVVIA_TUTTO.ps1: upload FTPS, installa tabelle (SCOUT/SFERA/LEX/LEADGEN), semina flussi, scoring, autostart Windows, accende autopilota.
- HUNTER81_AI.py autopilota: scraping, download dataset, classificazione con gemma/gpt-oss via Ollama.
- 81plus_deploy_hostinger.ps1 (FTPS delta), INSTALLA_PYTHON, backup/scarica, watchdog AI locale.

### 4D. MASTER DOCUMENTALE (30+ HUB cartelle, gia esistenti)
00 master vivo/control · 01 holding · 02 legale/compliance · 03 HUB1 · 04 HUB2 · 05 HUB3 · 06 nodi · 07 NotebookLM/TEAM AI ·
08 AI OS 150 agenti · 09 marketing/leadgen · 10 SCOUT81 lead pack · 11 sales/CRM · 12 PV/PV+ career/equilibrium ·
13 gamification/retention · 14 pass/kit/membership · 15 SICURISSIMO POINT81 franchising · 16 welfare plan · 17 visual/brand ·
18 tech/claude code/github · 19 database/API · 20 economia/cashflow/tasse · 21 startup/bandi · 22 investitori/exit ·
23 DAO/web3 · 24 global expansion · 25 QA/bonifica · 26 security/privacy · 27 wave lanci · 28 case study · 29 template/prompt · 30 archivio.

## 5. ECONOMIA INTERNA (regole ferme)
- PV e PV+ = utility/loyalty interna, MAI denaro. Cashback solo da acquisti/servizi/campagne reali.
- I PV+ si guadagnano con AZIONI e MISSIONI reali (no click): vanno nel wallet (buoni sconto) e riempiono gli assi dei giochi per tema.
- Tetti per status: MEMBER 3/12/40, NETWORKER 5/25/90, ELITE 8/40/150, FRANCHISER 12/60/220, CLUB 20/100/360 (giorno/settimana/mese).
- Semantic guard: vietati investimento, rendimento, ROI, APY, staking, rischio zero, zero multe, guadagno garantito.

## 6. COMPLIANCE E TONO
- Temi cardine: Sicurezza (sempre), Privacy (sempre), HACCP (solo ATECO alimentare). Fonte = LEX81+.
- LEX: non inventare sanzioni; senza fonte ufficiale = DA_VERIFICARE.
- LEADGEN: nessuna email senza doppio opt-in confermato.
- GEM/Web3 finanziario: nessuna esecuzione trade/movimento denaro; finanziari parcheggiati finche non validati.
- CTA unica: 81plus.net. Segreti solo in Script Properties / api .env. DB = fonte di verita.

## 7. FILO OPERATIVO END-TO-END
SCOUT trova/arricchisce imprese -> LEADGEN le acquisisce (doppio opt-in) -> LEX dice i temi per ATECO ->
utente con SIC-ID gioca e completa missioni reali -> PV+ nel wallet + assi giochi -> CASHBACK registra benefit reali ->
GAMIFICATION tiene status/badge/leaderboard -> CASHCOW (YouTube) porta nuovi utenti -> MASTERBLASTER orchestra e fa da memoria AI condivisa.

## 8. STATO
FATTO: SCOUT81 pipeline completa; 8 giochi 3D azione-driven; LEX/LEADGEN installati + 81 flussi + Avvento + doppio opt-in;
GEM81; OperativeSection CASHCOW/GAMIFICATION/CASHBACK/SFERA; deploy 1-click + autopilota locale; organigramma v3 canonico fissato;
CORE del nodo centrale pronto; blueprint e riepiloghi prodotti.
DA FARE: completare i ponti .gs del nodo centrale; persistenza reale giochi multi-utente (game_state/leaderboard/pvplus_ledger);
invio email LEADGEN post doppio opt-in; popolare LEX con norme validate; collegare CASHCOW come motore acquisizione.

## 9. ROADMAP (ordine)
1. Chiudere il NODO CENTRALE (ponti .gs SCOUT/GIOCHI/LEX/LEADGEN/GEM su CORE) e collegarlo al MASTERBLASTER.
2. Tabelle DB persistenza giochi + PV+ veri multi-utente.
3. Invio email LEADGEN dopo doppio opt-in (SMTP/SendGrid).
4. Popolare LEX con norme validate (togliere i DA_VERIFICARE confermati).
5. CASHCOW -> imbuto contenuti -> SCOUT/LEADGEN. Verso 6M imprese nel tempo.

## 10. NOTE DI GOVERNANCE
- Base canonica unica: organigramma v3. Aggiornare il file canonico e CLAUDE.md a ogni evoluzione.
- Regola di ingaggio: fai tutto tu, lascia all'utente 1 sola azione (1 click).
