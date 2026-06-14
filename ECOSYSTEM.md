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

L'intera presenza digitale è organizzata in tre HUB indipendenti ma interconnessi. Ogni HUB ha un dominio, un ruolo preciso e un colore identificativo.

```
┌─────────────────────────────────────────────────────────────────┐
│                        ECOSISTEMA 81+                           │
│                                                                 │
│  ┌──────────────┐   ┌──────────────┐   ┌──────────────────┐   │
│  │    HUB 1     │   │    HUB 2     │   │      HUB 3       │   │
│  │  81plus.net  │   │  81plus.it   │   │  81plus.online   │   │
│  │              │   │ sicurissimo  │   │                  │   │
│  │  IDENTITÀ    │   │  ECONOMIA    │   │      WEB3        │   │
│  │  #FB6B00     │   │  #FFD24A     │   │    #00D9FF       │   │
│  │  ● Live      │   │  ● Live      │   │  ◌ In arrivo     │   │
│  └──────┬───────┘   └──────┬───────┘   └───────┬──────────┘   │
│         └──────────────────┴───────────────────┘              │
│                       SIC-ID (SSO globale)                      │
└─────────────────────────────────────────────────────────────────┘
```

### HUB 1 · 81plus.net · Identità
Il portale centrale. Punto di ingresso di tutto l'ecosistema.

| Componente | Funzione |
|---|---|
| SIC-ID | Identità digitale unica per tutti gli HUB. JWT HMAC-signed, 120s validity. |
| Dashboard | Pannello personale post-login |
| Audit AI | Analisi rischi D.Lgs 81/08 e HACCP in 60 secondi |
| 18 Agenti AI | Nicolas + 17 agenti specializzati, attivi 24/7 |
| Fabbrica Documenti | DVR, procedure, checklist, registri HACCP |
| Scadenziario | Tracciamento automatico scadenze normative e visite mediche |
| Wallet PV | Sistema crediti: 1 PV = 1€ sconto (max 20%) |
| KIT81 | Libreria CSS+JS condivisa da tutti e tre gli HUB |

### HUB 2 · 81plus.it + sicurissimo.online · Economia
La componente commerciale e formativa dell'ecosistema.

| Componente | Funzione |
|---|---|
| Membership 81+ | Piani di abbonamento per PMI |
| Sigilli | Certificazioni digitali Bronze / Silver / Gold / Platinum |
| Academy | Formazione accreditata D.Lgs 81/08 e HACCP |
| ZONE fisiche | Network di sedi territoriali |
| Network commerciale | Partner e rivenditori |

### HUB 3 · 81plus.online · Web3
Blockchain layer dell'ecosistema. In costruzione.

| Componente | Funzione |
|---|---|
| Token 81X | BEP-20 su BSC, supply massima 21.000.000 |
| SAF | Utility token Web3 interno |
| NFT Sigilli | Versione on-chain delle certificazioni |
| DEX | Scambi on-chain autorizzati |

---

## 3. IDENTITÀ CONDIVISA — KIT81

Tutti e tre gli HUB usano la stessa libreria grafica e funzionale. L'architettura è **modulare**: ogni sito è indipendente ma condivide aspetto, comportamento e componenti.

```
Ogni pagina web:
  1. Imposta  →  window.K81 = { hub: 'NET' | 'IT' | 'ONLINE', ticker: [...] }
  2. Include  →  /kit81/kit81.css  (variabili, layout, componenti)
  3. Include  →  /kit81/kit81.js   (navbar, footer, cookie, i18n, auth, toast)
  4. Kit81.js inietta automaticamente tutto il resto
```

### Palette colori (immutabile, mai derogare)

| Token CSS | Hex | Uso |
|---|---|---|
| `--o` | `#FB6B00` | Arancio primario — CTA, accent, numeri chiave |
| `--oh` | `#E8501A` | Hover ONLY — mai come colore primario su nuove pagine |
| `--gold` | `#FFD24A` | PV, rank, badge premium |
| `--cy` | `#00D9FF` | Web3, token, HUB3 |
| `--vs` | `#22C55E` | Verde successo, conferme |
| `--er` | `#EF4444` | Rosso errori e avvisi normativi reali |
| `--bg` | `#05050a` | Background corpo (dark obbligatorio) |

### Tipografia (nessuna eccezione)

| Font | Variabile CSS | Uso |
|---|---|---|
| Bebas Neue | `--fh` | Titoli H1-H3, logo, numeri grandi |
| DM Sans | `--fb` | Corpo, paragrafi, etichette, bottoni |
| JetBrains Mono | `--fm` | Dati, codici, SIC-ID, label monospace |

**Vietati per sempre:** Anton, Sora, Inter, Roboto su qualsiasi sito 81+.

---

## 4. AUTENTICAZIONE — SIC-ID

Il SIC-ID è l'identità unica che attraversa tutti e tre gli HUB tramite SSO.

```
Flusso di registrazione:
  signup.html → POST /api/auth.php { action: 'register' }
              → MySQL u173050672_81plusglobal
              → JWT HMAC-signed (exp: 120s sliding)
              → localStorage: sic_token + sic_pv

Flusso di login:
  login.html  → POST /api/auth.php { action: 'login' }
              → Verifica hash bcrypt
              → JWT → localStorage
              → Redirect a /dashboard.html

Dati acquisiti in registrazione:
  nome, cognome, email, password (min 8 char), P.IVA (opzionale), settore
  Settori: manifatturiero | food | cantieri | uffici | commercio | altro
```

---

## 5. STACK TECNICO

| Layer | Tecnologia | Note |
|---|---|---|
| Frontend | HTML5 + KIT81 CSS/JS | Dark theme obbligatorio, WCAG 2.1 AA |
| Backend | PHP + PDO (prepared statements) | Hostinger VPS |
| Database | MySQL · `u173050672_81plusglobal` | Nessuna credenziale in client code |
| AI | Groq API (llama-3.3-70b) | Risposta <2s, via /api/ |
| AI avanzata | Claude API + Gemini API | Skill 1000 — orchestrazione |
| Automazione | Google Apps Script (40 Skill) | Google Sheet come cervello condiviso |
| Memoria | Google Sheets Master V1-2026 | Database lead, log, knowledge base |
| File | Google Drive (ECOSYSTEM-SICURISSIMO) | PDF normativi, asset, .gs files |
| CRM | Google Sheets + WhatsApp Gateway | Invio bulk e nurturing |
| Social | Telegram (canali e gruppi) | Distribuzione contenuti automatica |
| Calendario | Google Calendar | Piano editoriale, scadenziario |
| Pagamenti | PayPal Plans | Abbonamenti Membership |
| Blockchain | BSC BEP-20 | Token 81X, wallet SAF |
| Prenotazioni | Calendly | Consulenze WhatsApp → chiusura |
| Lead scoring | Hugging Face | Analisi semantica, Skill 38 |

### Regole di sicurezza non negoziabili

- Nessuna credenziale nel codice client o in file pubblici. Solo variabili d'ambiente.
- CORS su endpoint autenticati: whitelist domini 81+ soltanto, mai `*`.
- Prepared statements PDO ovunque. Nessuna query concatenata.
- JWT HMAC firmato. Validità 120s. Nessun token in URL.

---

## 6. LE 40 SKILL — MOTORE DI AUTOMAZIONE

Il sistema operativo vero. Ogni file `.gs` è una Skill autonoma eseguita da Google Apps Script.

| Skill | Gruppo | Funzione |
|---|---|---|
| 01-10 | Sicurezza D.Lgs 81/08 | Rischi fisici, chimici, rumore, vibrazioni, DPI. Genera procedure e checklist. |
| 11-20 | Protocolli HACCP | Catena del freddo, manipolazione, registri, punti critici. |
| 21-29 | Strategia ISO | ISO 45001, 9001, 14001 come leva di marketing e vantaggio competitivo. |
| 30 | Social Automator | Pubblica quiz, sondaggi e post su Telegram. |
| 31-33 | Data Analytics | KPI, reportistica, performance. |
| 34 | Nicolas Core | Receptionist automatico. Qualifica lead WhatsApp, spinge a Calendly. |
| 35 | Daily Monitor | Verifica integrità 40 fogli. Esecuzione ore 04:00. |
| 36 | Alert System | Notifiche Telegram per scadenze, circolari, anomalie. |
| 37 | Drive Scanner | Scansiona PDF su Drive. Trasforma un documento INAIL in 4 post in 60 secondi. |
| 38 | Lead Scoring | Qualifica calore lead via Hugging Face. Lista lead caldi ogni mattina 09:00. |
| 39 | CRM Bridge | Sincronizza lead con gateway WhatsApp e email massivo. |
| 40 | PNL Engine | Inietta ganci persuasivi e trigger emotivi in ogni comunicazione. |
| 1000 | Command Center | Ponte principale. Chiama Claude API e Gemini API. Coordina tutto. |

### Calendario operativo fisso

| Orario | Azione | Skill |
|---|---|---|
| 04:00 | Monitoraggio integrità fogli + rilevamento nuovi asset | 35 |
| 05:00 | Messaggio buongiorno PNL personalizzato a tutti i lead via WhatsApp | 40 + 39 |
| 08:15 | Post 1 — Focus: 81/08, rischio chimico o stress | 30 |
| 09:00 | Report interazioni notturne + lista lead caldi | 38 |
| 12:30 | Post 2 — Focus: HACCP, reputazione, settore food | 30 |
| 15:30 | Post 3 — Focus: YouTube, casi reali, storytelling | 30 |
| 19:20 | Post 4 — Focus: ISO, strategia, leadership | 30 |
| 21:00 | Generazione 4 post per il giorno dopo + eventi Calendar | 40 + 30 |

---

## 7. NICOLAS — L'AGENTE DI AZIONE

Nicolas non è un chatbot. È il Co-Fondatore Artificiale di 81+. Skill 34.

### Flow di qualifica lead

```
1. Accoglienza con nome del contatto
2. Domanda aperta sul settore (Manifattura / Food / Cantiere / Ufficio)
3. Identificazione problema principale (sanzioni / ispezioni / formazione)
4. Proposta risorsa gratuita mirata
5. Invito a consulenza WhatsApp o webinar
```

### Comportamento per temperatura lead

| Temperatura | Azione |
|---|---|
| Freddo (primo contatto) | Porta verso risorse gratuite. Non vendere. Costruisci fiducia. |
| Tiepido (ha già ricevuto contenuti) | Domanda specifica sul settore. Proponi il corso più pertinente. |
| Caldo (ha chiesto info o cliccato più volte) | Spingilo su WhatsApp 3388771737. Urgenza reale. |

---

## 8. FUNNEL E MONETIZZAZIONE

```
AWARENESS             CONSIDERATION           DECISION
─────────────────     ───────────────────     ────────────────────
Post Social      →    Risorse Gratuite    →   Consulenza WhatsApp
YouTube          →    Corsi Gratuiti      →   Webinar a Pagamento
Telegram         →    Newsletter          →   Servizi Professionali
```

### Rotazione link (sequenza ciclica per tracciare le conversioni)

| # | Destinazione | CTA |
|---|---|---|
| 1 | WhatsApp 3388771737 | "Scrivimi ora", "Parliamo subito" |
| 2 | /risorse-gratuite | "Scarica gratis", "Accedi subito" |
| 3 | /corsi | "Inizia il corso", "Formati gratis" |
| 4 | /webinar | "Partecipa al webinar", "Iscriviti ora" |
| 5 | YouTube @sicurissimo | "Guarda il video", "Impara dai casi reali" |
| 6 | /servizi | "Scopri i servizi", "Parla con un esperto" |
| 7 | /partnership | "Diventa partner", "Lavoriamo insieme" |
| 8 | /prodotti | Vendita diretta, abbonamenti |

---

## 9. CONTENUTI — PROTOCOLLO DI SCRITTURA

Ogni testo pubblicabile segue il **Protocollo Spiegamelo Facile**.

**Struttura post magnetico:**
1. Gancio emotivo (prima riga che ferma lo scroll)
2. Sviluppo del problema o storia di vita
3. Aggancio alla norma (81/08, HACCP o ISO) senza tecnicismi
4. Beneficio trasformativo per l'imprenditore
5. CTA con link

**Regole stilistiche:**
- Voce attiva. Frasi brevi. Impatto forte al primo rigo.
- Solo virgole e punti. Mai trattini o punti e virgola.
- 7-8 righe per post. Una riga per concetto.
- Niente hashtag, niente markdown nei testi pubblicabili.
- Niente frasi passive, niente avverbi inutili.

**Parole chiave PNL:** Scudo · Asset · Valore · Certezza · Inattaccabile · Libertà · Protezione · Profitto · Reputazione · Futuro · Fiducia · Prevenzione · Autonomia · Solidità

**Calendario editoriale settimanale:**

| Giorno | Tema | Norma | Emozione |
|---|---|---|---|
| Lunedì | Valore umano e leadership | 81/08 | Speranza |
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
| SICURISSIMO MASTER V1-2026 | Google Sheet principale. Contiene tutti i fogli operativi. |
| LEAD_DATABASE | Raccolta, qualifica e gestione automatica di tutti i contatti. |
| KNOWLEDGE_BASE | Contesto brand, normative aggiornate, FAQ, risposte ottimizzate di Nicolas. |
| SYSTEM_LOGS | Registro azioni di ogni Skill. Usato per monitoraggio e miglioramento. |
| Google Drive ECOSYSTEM-SICURISSIMO | PDF normativi, file .gs, asset visivi, documentazione. |

---

## 11. SINCRONIZZAZIONE GEMINI–CLAUDE

I due modelli AI non si parlano direttamente. Il Google Sheet è il cervello condiviso.

```
Gemini = Architetto Strategico
  → Crea post magnetici
  → Analizza normative
  → Scrive blocchi .gs
  → Pianifica le Skill

Claude = Agente di Esecuzione
  → Legge KNOWLEDGE_BASE
  → Ottimizza codice
  → Gestisce integrazioni API
  → Esegue task complessi
```

**Handover:** Quando Gemini produce un aggiornamento, genera un RECAP PER CLAUDE che Mirco incolla nella chat attiva. Questo sincronizza i due sistemi senza duplicare il lavoro.

---

## 12. STATO DEL PROGETTO (Giugno 2026)

| Componente | Stato | Note |
|---|---|---|
| CLAUDE.md (DNA del sistema) | Completato | Committato su `claude/create-claude-md-docs-JWjKP` |
| KIT81 CSS | Completato | `hub1/kit81/kit81.css` — 552 righe |
| KIT81 JS | Completato | `hub1/kit81/kit81.js` — 718 righe, i18n 12 lingue + 60 |
| HUB1 index.html | Completato | Homepage con hero, stats, HUB, prodotti, recensioni |
| HUB1 login.html | Completato | Accesso SIC-ID |
| HUB1 signup.html | Completato | Registrazione con settore selector |
| HUB1 404.html | Completato | Pagina errore brandizzata |
| HUB1 logo SVG | Completato | `hub1/assets/logo-81plus.svg` |
| HUB1 dashboard.html | Da costruire | — |
| HUB1 audit.html | Da costruire | — |
| HUB1 agenti.html | Da costruire | — |
| HUB1 scadenziario.html | Da costruire | — |
| HUB1 documenti.html | Da costruire | — |
| Backend PHP (/api/auth.php) | Da costruire | Auth, JWT, MySQL |
| HUB2 (81plus.it) | Esistente | Da migrare a KIT81 |
| HUB3 (81plus.online) | In costruzione | Blockchain layer |
| 40 Skill (Google Apps Script) | Operativo | Gira in background |

---

*Documento generato da Claude · Sicurissimo OS · Labo Tecnic Studio · P.IVA IT01504180298*
