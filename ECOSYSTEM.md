# ECOSISTEMA 81+ — RIEPILOGO LOGICO GLOBALE
### Versione 1.0 · Giugno 2026 · Labo Tecnic Studio

---

## 1. VISIONE DI SISTEMA

**Sicurissimo OS** è il motore operativo dell'ecosistema. Non è un sito, non è un chatbot. È un sistema automatizzato che trasforma la conformità normativa (D.Lgs 81/08, HACCP, ISO) da costo burocratico a leva di profitto per le PMI italiane.

Il sistema gira su tre livelli paralleli:

```
LIVELLO 1 — AUTOMAZIONE        Google Apps Script (40 Skill)
LIVELLO 2 — INTELLIGENZA       Claude API + Gemini API (Skill 1000)
LIVELLO 3 — DISTRIBUZIONE      Telegram · WhatsApp · Web (3 HUB)
```

Proprietario: Mirco  
Agente AI principale: **Nicolas** (Co-Fondatore Artificiale)  
Stack tecnico: PHP + MySQL su Hostinger · Google Workspace · BSC Blockchain

---

## 2. ARCHITETTURA WEB — I TRE HUB

| HUB | Dominio | Ruolo | Colore | Stato |
|---|---|---|---|---|
| HUB 1 | 81plus.net | Identità, SIC-ID, dashboard, agenti AI | #FB6B00 | Live |
| HUB 2 | 81plus.it · sicurissimo.online | Economia, membership, sigilli, academy | #FFD24A | Live |
| HUB 3 | 81plus.online | Web3, token 81X, NFT, DEX | #00D9FF | In costruzione |

I tre HUB condividono un'unica identità utente: il **SIC-ID** (SSO globale).

### HUB 1 · 81plus.net · Identità

| Componente | Funzione |
|---|---|
| SIC-ID | Identità digitale unica. JWT HMAC-signed, 120s validity. |
| Dashboard | Pannello personale post-login |
| Audit AI | Analisi rischi D.Lgs 81/08 e HACCP in 60 secondi |
| 18 Agenti AI | Nicolas + 17 specializzati, attivi 24/7 |
| Fabbrica Documenti | DVR, procedure, checklist, registri HACCP |
| Scadenziario | Tracciamento automatico scadenze normative |
| Wallet PV | 1 PV = 1€ sconto (max 20%) |
| KIT81 | Libreria CSS+JS condivisa da tutti gli HUB |

### HUB 2 · 81plus.it + sicurissimo.online · Economia

| Componente | Funzione |
|---|---|
| Membership | Piani abbonamento per PMI |
| Sigilli | Bronze / Silver / Gold / Platinum |
| Academy | Formazione accreditata 81/08 e HACCP |
| ZONE fisiche | Network sedi territoriali |
| Network commerciale | Partner e rivenditori |

### HUB 3 · 81plus.online · Web3

| Componente | Funzione |
|---|---|
| Token 81X | BEP-20 su BSC, supply max 21.000.000 |
| SAF | Utility token Web3 interno |
| NFT Sigilli | Certificazioni on-chain |
| DEX | Scambi on-chain autorizzati |

---

## 3. IDENTITÀ CONDIVISA — KIT81

Ogni pagina dei tre HUB segue questo pattern:

```
1. window.K81 = { hub: 'NET' | 'IT' | 'ONLINE', ticker: [...] }
2. <link rel="stylesheet" href="/kit81/kit81.css">
3. <script src="/kit81/kit81.js"></script>
→ kit81.js inietta: navbar · ticker · footer · cookie bar · i18n · toast · loader
```

### Palette colori (immutabile)

| Token | Hex | Uso |
|---|---|---|
| `--o` | `#FB6B00` | Arancio primario — CTA, accent, numeri chiave |
| `--oh` | `#E8501A` | Hover ONLY — mai come colore primario |
| `--gold` | `#FFD24A` | PV, rank, badge premium |
| `--cy` | `#00D9FF` | Web3, token, HUB3 |
| `--vs` | `#22C55E` | Verde successo |
| `--er` | `#EF4444` | Rosso errori |
| `--bg` | `#05050a` | Background (dark obbligatorio) |

### Tipografia (nessuna eccezione)

| Font | Token | Uso |
|---|---|---|
| Bebas Neue | `--fh` | Titoli, logo, numeri grandi |
| DM Sans | `--fb` | Corpo, paragrafi, bottoni |
| JetBrains Mono | `--fm` | Dati, codici, SIC-ID |

**Vietati su tutti i siti 81+:** Anton · Sora · Inter · Roboto

---

## 4. AUTENTICAZIONE — SIC-ID

```
Registrazione:
  signup.html → POST /api/auth.php { action: 'register' }
  → MySQL u173050672_81plusglobal
  → JWT HMAC-signed (exp: 120s sliding)
  → localStorage: sic_token + sic_pv

Login:
  login.html → POST /api/auth.php { action: 'login' }
  → bcrypt verify → JWT → redirect /dashboard.html

Settori disponibili:
  manifatturiero | food | cantieri | uffici | commercio | altro
```

---

## 5. STACK TECNICO

| Layer | Tecnologia | Note |
|---|---|---|
| Frontend | HTML5 + KIT81 CSS/JS | WCAG 2.1 AA, dark theme obbligatorio |
| Backend | PHP + PDO prepared statements | Hostinger VPS |
| Database | MySQL `u173050672_81plusglobal` | No credenziali in client code |
| AI veloce | Groq API llama-3.3-70b | Risposta <2s |
| AI avanzata | Claude API + Gemini API | Skill 1000, orchestrazione |
| Automazione | Google Apps Script (40 Skill) | Google Sheet come cervello condiviso |
| Memoria | Google Sheets Master V1-2026 | Lead, log, knowledge base |
| File | Google Drive ECOSYSTEM-SICURISSIMO | PDF normativi, .gs, asset |
| CRM | Sheets + WhatsApp Gateway | Bulk e nurturing |
| Social | Telegram | Distribuzione contenuti automatica |
| Calendario | Google Calendar | Piano editoriale, scadenziario |
| Pagamenti | PayPal Plans | Abbonamenti membership |
| Blockchain | BSC BEP-20 | Token 81X, wallet SAF |
| Prenotazioni | Calendly | Consulenze → chiusura commerciale |
| Lead scoring | Hugging Face | Analisi semantica, Skill 38 |

**Regole di sicurezza non negoziabili:**
- No credenziali in codice client o file pubblici. Solo variabili d'ambiente.
- CORS whitelist domini 81+ soltanto. Mai `*` su endpoint autenticati.
- PDO prepared statements ovunque. Nessuna query concatenata.
- JWT HMAC firmato. No token in URL.

---

## 6. LE 40 SKILL — MOTORE DI AUTOMAZIONE

| Skill | Funzione |
|---|---|
| 01-10 | Sicurezza D.Lgs 81/08: rischi fisici, chimici, rumore, vibrazioni, DPI |
| 11-20 | HACCP: catena del freddo, manipolazione, registri, punti critici |
| 21-29 | ISO 45001 / 9001 / 14001 come leva di marketing e vantaggio competitivo |
| 30 | Social Automator: pubblica quiz, sondaggi e post su Telegram |
| 31-33 | Analytics: KPI, reportistica, performance |
| 34 | Nicolas Core: receptionist WhatsApp, qualifica lead, spinge a Calendly |
| 35 | Daily Monitor: integrità 40 fogli alle 04:00 |
| 36 | Alert System: notifiche Telegram per scadenze, circolari, anomalie |
| 37 | Drive Scanner: PDF → 4 post in 60 secondi |
| 38 | Lead Scoring: lista lead caldi ogni mattina alle 09:00 |
| 39 | CRM Bridge: sync lead con gateway WhatsApp e email massivo |
| 40 | PNL Engine: ganci persuasivi in ogni comunicazione |
| 1000 | Command Center: Claude API + Gemini API, coordina tutto |

### Calendario operativo fisso

| Orario | Azione | Skill |
|---|---|---|
| 04:00 | Monitoraggio integrità fogli + rilevamento nuovi asset | 35 |
| 05:00 | Buongiorno PNL personalizzato via WhatsApp a tutti i lead | 40+39 |
| 08:15 | Post 1 — Focus: 81/08 | 30 |
| 09:00 | Report interazioni notturne + lista lead caldi | 38 |
| 12:30 | Post 2 — Focus: HACCP | 30 |
| 15:30 | Post 3 — Focus: YouTube / storytelling | 30 |
| 19:20 | Post 4 — Focus: ISO / leadership | 30 |
| 21:00 | Genera 4 post per domani + eventi Calendar | 40+30 |

---

## 7. NICOLAS — L'AGENTE DI AZIONE

Nicolas non è un chatbot. È il Co-Fondatore Artificiale di 81+. Skill 34.

```
Flow di qualifica lead:
  1. Accoglienza con nome del contatto
  2. Domanda aperta sul settore
  3. Identificazione problema principale (sanzioni / ispezioni / formazione)
  4. Proposta risorsa gratuita mirata
  5. Invito a consulenza WhatsApp o webinar
```

| Temperatura lead | Azione |
|---|---|
| Freddo | Risorse gratuite. Non vendere. Costruisci fiducia. |
| Tiepido | Domanda specifica sul settore. Proponi corso pertinente. |
| Caldo | Spingi su WhatsApp con urgenza reale. |

---

## 8. FUNNEL E MONETIZZAZIONE

```
AWARENESS           CONSIDERATION         DECISION
Post Social    →    Risorse Gratuite  →   Consulenza WhatsApp
YouTube        →    Corsi Gratuiti    →   Webinar a Pagamento
Telegram       →    Newsletter        →   Servizi Professionali
```

### Rotazione link (sequenza ciclica)

| # | Destinazione | CTA |
|---|---|---|
| 1 | WhatsApp commerciale | Scrivimi ora · Parliamo subito |
| 2 | /risorse-gratuite | Scarica gratis · Accedi subito |
| 3 | /corsi | Inizia il corso · Formati gratis |
| 4 | /webinar | Partecipa al webinar · Iscriviti ora |
| 5 | YouTube @sicurissimo | Guarda il video · Impara dai casi reali |
| 6 | /servizi | Scopri i servizi · Parla con un esperto |
| 7 | /partnership | Diventa partner · Lavoriamo insieme |
| 8 | /prodotti | Vendita diretta · Abbonamenti |

---

## 9. PROTOCOLLO DI SCRITTURA

**Struttura post magnetico:**
1. Gancio emotivo (prima riga che ferma lo scroll)
2. Sviluppo del problema o storia di vita
3. Aggancio alla norma senza tecnicismi
4. Beneficio trasformativo per l'imprenditore
5. CTA con link

**Regole:** voce attiva · frasi brevi · 7-8 righe · niente hashtag · niente markdown nei testi pubblicabili · niente frasi passive

**Parole PNL:** Scudo · Asset · Valore · Certezza · Inattaccabile · Libertà · Protezione · Profitto · Reputazione · Futuro · Fiducia · Prevenzione · Autonomia · Solidità

| Giorno | Tema | Norma | Emozione |
|---|---|---|---|
| Lunedì | Leadership e valore umano | 81/08 | Speranza |
| Martedì | Rischio chimico | 81/08 | Paura → azione |
| Mercoledì | Igiene alimentare | HACCP | Reputazione |
| Giovedì | Stress lavoro-correlato | 81/08 | Empatia |
| Venerdì | Casi reali YouTube | Tutti | Apprendimento |
| Sabato | Strategia ISO | ISO 45001/9001 | Ambizione |
| Domenica | Storia di vita salvata | Tutti | Urgenza |

---

## 10. MEMORIA DEL SISTEMA

| Foglio / File | Contenuto |
|---|---|
| SICURISSIMO MASTER V1-2026 | Google Sheet principale, tutti i fogli operativi |
| LEAD_DATABASE | Raccolta, qualifica e gestione automatica contatti |
| KNOWLEDGE_BASE | Brand, normative aggiornate, FAQ, risposte Nicolas |
| SYSTEM_LOGS | Registro azioni di ogni Skill |
| Google Drive ECOSYSTEM-SICURISSIMO | PDF normativi, .gs, asset visivi |

---

## 11. SINCRONIZZAZIONE GEMINI–CLAUDE

```
Gemini = Architetto Strategico
  → Crea post magnetici, analizza normative, scrive .gs, pianifica Skill

Claude = Agente di Esecuzione
  → Legge KNOWLEDGE_BASE, ottimizza codice, gestisce API, esegue task complessi

Handover: RECAP PER CLAUDE che Mirco incolla nella chat attiva
         → sincronizza i due sistemi senza duplicare lavoro
```

---

## 12. STATO DEL PROGETTO (Giugno 2026)

| Componente | Stato | Dettaglio |
|---|---|---|
| CLAUDE.md (DNA sistema) | ✓ | Committato sul branch attivo |
| KIT81 CSS | ✓ | 552 righe, variabili + layout + componenti |
| KIT81 JS | ✓ | 718 righe, i18n 12 lingue full + 60 lingue switcher |
| HUB1 index.html | ✓ | Hero · stats · HUB · prodotti · recensioni · CTA |
| HUB1 login.html | ✓ | Accesso SIC-ID con JWT |
| HUB1 signup.html | ✓ | Registrazione + settore selector + password strength |
| HUB1 404.html | ✓ | Pagina errore brandizzata |
| HUB1 logo-81plus.svg | ✓ | Logo vettoriale |
| HUB1 dashboard.html | ○ | Da costruire |
| HUB1 audit.html | ○ | Da costruire |
| HUB1 agenti.html | ○ | Da costruire |
| HUB1 scadenziario.html | ○ | Da costruire |
| HUB1 documenti.html | ○ | Da costruire |
| Backend /api/auth.php | ○ | Da costruire — Auth, JWT, MySQL |
| HUB2 migrazione KIT81 | ○ | In attesa |
| HUB3 Web3 layer | ○ | In costruzione |
| 40 Skill Google Apps Script | ✓ | Operativo in background |

---

*Claude · Sicurissimo OS · Labo Tecnic Studio · P.IVA IT01504180298*
