# OUTPUT UNICO TEAM AI 81+
## 2026-06-20 — HUB01 — FASE ZERO81+ MVP0 — V1 — PRONTO

---

## COMUNICAZIONE UFFICIALE A TUTTO IL TEAM AI 81+ HOLDING

**Data:** 2026-06-20
**Emessa da:** Claude (HUB01 Builder)
**Destinatari:** Claude, ChatGPT, Gemini, Groq, Gemma/gpt-oss, n8n, MASTERBLASTER
**Priorita:** MASSIMA — FASE ZERO81+ FISSATA

---

## DECISIONE

FASE ZERO81+ e attiva. MVP0 go-live dichiarato da Mirco in data 2026-06-20.

Questa e la pietra fondativa di tutto l'ecosistema 81+. Ogni agente AI, ogni modulo, ogni sviluppo futuro parte da qui.
Non si torna indietro. Non si ricostruisce da zero. Si costruisce sopra questa base.

**Repository attivo:** PlanB76/sicurissimo-os
**Branch attivo:** claude/create-claude-md-docs-JWjKP
**DB fonte di verita:** u173050672_81plusglobal (Hostinger MySQL)
**Sheet cervello condiviso:** SICURISSIMO MASTER V1-2026 (Google Sheets)

---

## SOLUZIONE

### Architettura MVP0 fissata

```
BRIDGE: 81plus.online
  |
  |-- HUB1: 81plus.net         [Core — login, SIC-ID, dashboard, wallet, DB MySQL]
  |-- HUB2: sicurissimo.online [Web2 — compliance, LEX81+, HACCP, audit, corsi]
  |-- HUB3: sicurissimo.io     [Web3 — utility, LOCK81+, GEM81 — PARCHEGGIO]
```

### Moduli trasversali attivi in MVP0

| Modulo | Stato | Priorita |
|--------|-------|----------|
| LEX81+ | ATTIVO (PHP API + DB) | P1 |
| LEADGEN81+ | ATTIVO (PHP API + DB + 25 flow + 8 step email) | P1 |
| SFERA81+ | ATTIVO (GS: 40_SFERA_GIOCHI81 + 50_GAMIFICATION81) | P1 |
| MASTERBLASTER | ATTIVO (11 file .gs pronti per install) | P1 |
| PAYGATE81+ | SKELETON (90_PVCORE_PAYGATE81.gs) | P2 |
| BOOSTER81+ | DA COSTRUIRE | P3 |
| LOCK81+ | MODULO INTERNO WALLET — DA COSTRUIRE | P3 |
| GEM81 | PARCHEGGIO — zero trade execution senza firma utente | P4 |

### Stack tecnico confermato

- PHP 8+ con PDO prepared statements
- MySQL 8 (Hostinger, DB: u173050672_81plusglobal)
- Google Apps Script (11 file MASTERBLASTER)
- Google Sheets (cervello condiviso AI)
- JavaScript vanilla (admin panel)
- CORS: solo 81plus.net + 81plus.christmas

---

## COSA FARE

### Ordine di priorita MVP0

1. **Installare MASTERBLASTER su Google Apps Script**
   - Seguire SCRIPT/00_LEGGIMI_ORDINE_E_INSTALL.md
   - Incollare i file nell'ordine numerico (00, 10, 20, 30, 40, 50, 60, 70, 80, 90, 95)
   - Impostare Script Properties: X81_SECRET, MB81_WEBTOKEN
   - Eseguire MB_install() una volta
   - Autorizzare tutti i permessi richiesti

2. **Verificare la connessione DB**
   - PHP API gia pronte in WEB_HOSTINGER/81plus.net/public_html/admin/81global/
   - config.php da creare partendo da config.example.php (MAI committare config.php)
   - Test panel: admin/test_api.html

3. **Attivare il NODO CENTRALE**
   - MASTERBLASTER ↔ PHP API ↔ MySQL (sincronizzazione ogni 10 min)
   - Snapshot KPI ogni ora
   - Trigger automatici: 04:00 (Daily Monitor), 09:00 (Lead Scoring), 21:00 (Post Generation)

4. **Verificare LEX81+ e LEADGEN81+**
   - LEX81+: categorie, norme, ATECO, obblighi, sanzioni, documenti, corsi, servizi
   - LEADGEN81+: contatti, consensi, flow WELCOME, segmentazione, coda email
   - Double opt-in obbligatorio prima di qualsiasi invio email

5. **Eseguire TABULA_RASA selettiva** (solo se necessario)
   - Seguire SCRIPT/TABULA_RASA_RESTART_MVP0.md
   - Tenere DB, moduli, CLAUDE.md, script
   - Pulire solo dati test e trigger duplicati

---

## SCRIPT / CODICE

### File MASTERBLASTER (11 .gs — da installare in ordine)

```
00_MOTHERBOARD81_CORE.gs      → MB core, MB_install, MB_runAll, Web App entry
10_SCOUT81.gs                 → Acquisizione lead, profilazione ATECO
20_LEADGEN81.gs               → Nurturing, flow email, segmentazione
30_LEX81.gs                   → Knowledge base normativa (81/08, HACCP, privacy)
40_SFERA_GIOCHI81.gs          → Missioni, LIFEWHEEL81+, ESCALATION81+
50_GAMIFICATION81.gs          → PV/PV+, badge, progressi, retention
60_CASHBACK81.gs              → Cashback utility (NON denaro, NON rendimento)
70_CASHCOW81.gs               → Revenue engine, upsell, conversion tracking
80_GEM81.gs                   → Analisi mercato (solo info — zero trade execution)
90_PVCORE_PAYGATE81.gs        → Wallet PV/PV+, pagamenti, ricariche
95_AI_ROUTER81.gs             → Routing task per AI: Claude/ChatGPT/Gemini/Groq
```

### Routing AI (da 95_AI_ROUTER81.gs)

```javascript
var AI_MAP = {
  CODICE:    ['ANTHROPIC'],          // Claude = builder, codice, docs
  STRATEGIA: ['OPENAI','ANTHROPIC'], // ChatGPT = strategia, copy
  VISUAL:    ['GEMINI'],             // Gemini = visual, presentazioni
  SCORING:   ['GROQ'],               // Groq = lead scoring veloce
  CLASSIFICA:['OLLAMA','GROQ'],      // Gemma/gpt-oss = classificazione locale
  NURTURE:   ['ANTHROPIC','GROQ'],   // Claude + Groq = nurturing email
  DEFAULT:   ['GROQ','ANTHROPIC']
};
```

### NODO CENTRALE (handshake base)

```javascript
// Eseguire da MB_syncAll_() ogni 10 minuti
function MB_syncPhpApi_(endpoint, payload) {
  var url = MB.BASE.LEX + endpoint;
  var options = {
    method: 'post',
    contentType: 'application/json',
    headers: { 'X-81-Secret': PropertiesService.getScriptProperties().getProperty('X81_SECRET') },
    payload: JSON.stringify(payload),
    muteHttpExceptions: true
  };
  return JSON.parse(UrlFetchApp.fetch(url, options).getContentText());
}
```

---

## TASK AI — ASSEGNAZIONE RUOLI MVP0

| AI | Ruolo in MVP0 | Task immediati |
|----|--------------|----------------|
| **Claude** | Builder / HUB01 | PHP API, DB schema, CLAUDE.md, documentazione tecnica, test panel |
| **ChatGPT** | Strategista / Copy | Post magnetici, script Nicolas, funnel copy, strategie di lancio |
| **Gemini** | Visual / Architettura | Dashboard UI, organigramma visuale, presentazioni Mirco, blueprint PDF |
| **Groq** | Scoring / Velocita | Lead scoring real-time, classificazione ATECO, alert rapidi |
| **Gemma/gpt-oss** | Classificazione locale | Analisi semantica locale, filtro Semantic Guard, NLP italiano |
| **n8n** | Orchestrazione | Trigger automatici, webhook, bridge tra sistemi, cron jobs |
| **MASTERBLASTER** | Cervello condiviso | Coordina tutto via Google Sheet — fonte di verita per gli agenti |

### Regola di collaborazione

Nessun agente agisce su un HUMAN_APPROVAL task senza conferma di Mirco:
`SEND_EMAIL, PUBLISH, PAYOUT, SETTLEMENT, CONTRACT, LEGAL_CLAIM, MEMBERSHIP_CHANGE, ROLE_CHANGE`

---

## FILE CONSEGNATI IN QUESTA FASE

### Repo: sicurissimo-os / branch: claude/create-claude-md-docs-JWjKP

```
GLOBAL_MASTER_FILES/GENESYS81_MVP0/
├── 2026-06-20_81PLUS_HUB01_FASE_ZERO81_MVP0_V1_PRONTO.md   (questo file)
├── 2026-06-20_81PLUS_organigramma_operativo_domini_v3_validazione.md
├── 81PLUS_BLUEPRINT_GLOBALE.md
├── 81PLUS_MASTERBLASTER_MOTHERBOARD.pdf
├── 81PLUS_MASTER_BLASTER_BLUEPRINT.pdf
├── 81PLUS_VISIONE_GLOBALE_COMPLETA.md
└── SCRIPT/
    ├── 00_LEGGIMI_ORDINE_E_INSTALL.md
    ├── 00_MOTHERBOARD81_CORE.gs
    ├── 10_SCOUT81.gs
    ├── 20_LEADGEN81.gs
    ├── 30_LEX81.gs
    ├── 40_SFERA_GIOCHI81.gs
    ├── 50_GAMIFICATION81.gs
    ├── 60_CASHBACK81.gs
    ├── 70_CASHCOW81.gs
    ├── 80_GEM81.gs
    ├── 90_PVCORE_PAYGATE81.gs
    ├── 95_AI_ROUTER81.gs
    └── TABULA_RASA_RESTART_MVP0.md

WEB_HOSTINGER/81plus.net/public_html/admin/81global/
├── _bootstrap.php
├── config/config.example.php
├── api/lex81/          (5 endpoint PHP)
├── api/leadgen81/      (12 endpoint PHP)
├── database/lex81_leadgen81_mysql.sql
└── admin/test_api.html

CLAUDE.md (sezioni 1-18 aggiornate, FASE ZERO81+ dichiarata)
```

---

## RISCHI E GUARDRAIL ATTIVI

| Rischio | Guardrail |
|---------|-----------|
| Committare credenziali | config.php in .gitignore, mai committare token/chiavi |
| Semantic Guard violata | FORBIDDEN list in MB.FORBIDDEN — blocco automatico |
| PV/PV+ confuso con denaro | Semantic Guard attiva — mai usare: investimento, rendimento, ROI, APY, staking |
| Email senza consenso | Double opt-in obbligatorio — leadgen81_consents.granted = 1 required |
| Trade eseguiti da GEM81 | GEM81 e solo informativo — zero trade senza firma utente |
| Staking/rendimento promesso | LOCK81+ bloca PV per regole/benefit — MAI rendimento |
| Push su branch sbagliato | Branch attivo: claude/create-claude-md-docs-JWjKP — verificare sempre |
| Azioni HUMAN_APPROVAL auto | MB.HUMAN_APPROVAL list — richiede conferma Mirco |

---

## AZIONE UMANA RICHIESTA

**Mirco — azioni manuali necessarie per completare MVP0:**

1. **Google Apps Script:** Crea un nuovo progetto Apps Script collegato al MASTER Sheet. Incolla i file nell'ordine numerico da SCRIPT/. Esegui MB_install(). Autorizza tutti i permessi.

2. **Script Properties:** Imposta manualmente (non mettere in codice):
   - `X81_SECRET` = [tuo segreto sicuro]
   - `MB81_WEBTOKEN` = [token web app]
   - `ANTHROPIC_KEY`, `OPENAI_KEY`, `GEMINI_KEY`, `GROQ_KEY` (opzionali, solo se vuoi AI da Sheet)

3. **config.php su Hostinger:** Copia config.example.php in config.php sul server. Compila con credenziali DB reali. NON caricare su Git.

4. **Test panel:** Apri admin/test_api.html in browser. Verifica che ogni endpoint risponda 200.

5. **Google Drive:** Carica manualmente 81PLUS_MASTERBLASTER_MOTHERBOARD.pdf e 81PLUS_MASTER_BLASTER_BLUEPRINT.pdf nella cartella SFERA81+:
   - ID cartella: 1-6hUUcVoMcuOw1GWLL3WCgWeR8WziS3k

---

## SALVA COSI

```
File: 2026-06-20_81PLUS_HUB01_FASE_ZERO81_MVP0_V1_PRONTO.md
Cartella repo: GLOBAL_MASTER_FILES/GENESYS81_MVP0/
Branch: claude/create-claude-md-docs-JWjKP
Stato: PRONTO — COMMITTATO — PUSHATO
Fase: ZERO81+ (go-live MVP0 dichiarato da Mirco il 2026-06-20)
Prossimo step: Mirco installa MASTERBLASTER + imposta Script Properties
```

**FASE ZERO81+ FISSATA. IL SISTEMA E VIVO.**
