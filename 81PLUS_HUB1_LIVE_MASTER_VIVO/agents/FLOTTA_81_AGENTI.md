# FLOTTA 150 AGENTI AI 81+ — Guida Operativa
# Sistema Operativo 81+ | Versione 1.0.0 | Data 2026-06-17

---

## LA FLOTTA IN 30 SECONDI

Hai 150 agenti AI specializzati, divisi in 5 livelli:

| # | Hub | Dominio | Agenti |
|---|-----|---------|--------|
| 1 | **HUB1** — 81plus.net | Identità, governance, Nicolas, lead | 18 |
| 2 | **HUB2** — SICURISSIMO | Compliance D.Lgs 81/08, HACCP, ISO | 50 |
| 3 | **HUB3** — 81plus.online | Web3, utility, tokenomics, marketing | 50 |
| 4 | **PC Operator Layer** | Operatori per azioni esterne confermate | 13 |
| 5 | **Finance & Daily Ops** | Finanza, fatturazione, operativo quotidiano | 19 |

**Totale: 150 agenti. 0 deploy. 0 azioni critiche senza conferma.**

---

## COME FUNZIONA L'ORCHESTRAZIONE

Ogni richiesta parte da **Orchestrator81** (H1-01).
Lui legge la `routing.matrix.yaml`, identifica l'agente giusto, dispatcha.
Se non sa cosa fare: chiede a Mirco.

```
Mirco scrive una richiesta
        ↓
Orchestrator81 legge routing.matrix
        ↓
SemanticGuard81 verifica linguaggio (sempre)
        ↓
Agente specializzato esegue
        ↓
SystemLogger81 registra tutto
        ↓
Output a Mirco (con proposta azione successiva)
```

---

## REGOLE FERREE — NON NEGOZIABILI

**3 FRASI DI CONFERMA:**
- `CONFERMO INVIO` → per email, WhatsApp broadcast
- `CONFERMO PUBBLICA` → per post social, form pubblici
- `CONFERMO AZIONE CRITICA` → per deploy, fatture, bonifici, PayGate

**MAI in automatico:**
- Deploy o rilascio in produzione
- Invio campagne massicce (>50/100 destinatari)
- Emissione fatture elettroniche
- Bonifici o pagamenti
- Modifica logica PV/PV+/provvigioni/PayGate
- Cancellazione di qualsiasi file o dato

**MAI nel codice o file pubblici:**
- Credenziali Aruba (solo .env)
- API Key Stripe (solo .env)
- Nome o telefono dell'Ammiraglio (usare "la direzione")

---

## HUB1 — 18 AGENTI IDENTITÀ E GOVERNANCE

| ID | Nome | Funzione principale |
|----|------|---------------------|
| H1-01 | Orchestrator81 | Master router di tutti gli agenti |
| H1-02 | SemanticGuard81 | Blocca linguaggio vietato su tutti gli output |
| H1-03 | SicIDGenerator81 | Genera SIC-ID immutabili per ogni membro |
| H1-04 | MembershipManager81 | Gestisce tier BASIC+/PRO+/ELITE+/GENESYS81+ |
| H1-05 | AuthGate81 | JWT, sessioni, CORS whitelist-only |
| H1-06 | NicolasCore81 | Receptionist agentico WhatsApp → Calendly |
| H1-07 | LeadScoring81 | Scoring lead con Hugging Face. Report 09:00 |
| H1-08 | CRMBridge81 | Sync lead con Brevo e WhatsApp gateway |
| H1-09 | DailyMonitor81 | Integrità fogli e nuovi asset. Cron 04:00 |
| H1-10 | AlertSystem81 | Notifiche urgenti Telegram su anomalie |
| H1-11 | DriveScanner81 | PDF Drive → 4 post in 60 secondi |
| H1-12 | PNLEngine81 | Inietta ganci persuasivi. Parole: Scudo, Asset, Certezza |
| H1-13 | SocialAutomator81 | 4 post/giorno su calendario fisso |
| H1-14 | KnowledgeBase81 | Memoria permanente progetto |
| H1-15 | DataAnalytics81 | KPI, report, performance giornaliera |
| H1-16 | CommandCenter81 | Bridge Gemini↔Claude via Google Sheet |
| H1-17 | PrivacyGuard81 | GDPR, consensi, data breach |
| H1-18 | SystemLogger81 | Log immutabile di tutte le azioni |

---

## HUB2 — 50 AGENTI COMPLIANCE (elenco rapido)

I 50 agenti HUB2 coprono ogni aspetto della compliance D.Lgs 81/08, HACCP e ISO.

**Agenti chiave:**
- **Safety81** (H2-01) — DVR, valutazione rischi, procedure
- **HACCP81** (H2-02) — Piani HACCP, CCP, catena freddo
- **ASR2025Agent81** (H2-03) — Corsi obbligatori 2025, mapping ATECO
- **AuditEngine81** (H2-12) — Audit interno, non conformità
- **PreventivoCompliance81** (H2-13) — Preventivi personalizzati
- **DocumentBuilder81** (H2-25) — DVR, POS, PSC, nomine
- **ScadenzarioManager81** (H2-26) — Tutto lo scadenziario compliance
- **InspectionSimulator81** (H2-27) — Simula ispezione ASL/INL prima della vera
- **SanzioniCalcolatore81** (H2-28) — Costo sanzioni vs costo compliance
- **NormativeMonitor81** (H2-49) — Monitora GU/INAIL/INL per nuove norme

**Altri rischi specifici:** Chimico, Rumore, Vibrazioni, Stress, DPI, Cantiere,
Sorveglianza Sanitaria, Incendio, Elettrico, Macchine, Ergonomia, Biologico,
Cancerogeno, Amianto, Altitudine, Gas Confinati, Termico, Notturno, Smart Working.

**ISO:** 45001, 9001, 14001 — ciascuno con gap analysis e angolo marketing.

---

## HUB3 — 50 AGENTI WEB3 E UTILITY (elenco rapido)

**Tokenomics e membership:**
- **GENESYS81Agent** (H3-01) — Max 81 membership premium. 1000 PV. NON investimento.
- **PIX81Agent** (H3-02) — Max 1000 spazi pubblicitari. Utility token.
- **LOCK81Agent** (H3-03) — Loyalty 180 giorni. NON staking. NON rendimento.
- **PVEngine81** (H3-04) — Credito interno 1:1 euro. NON moneta elettronica.
- **PVPlusEngine81** (H3-05) — Reward gamificato. Idempotente via UNIQUE KEY.
- **PayGate81** (H3-06) — Gateway pagamenti. CONFERMO AZIONE CRITICA per modifiche.
- **ProvvigioniEngine81** (H3-07) — Calcolo provvigioni partner. Idempotente.

**Marketing e crescita:**
- **WebinarEngine81** (H3-10) — Webinar: creazione, iscrizioni, follow-up
- **CourseMarketplace81** (H3-11) — Corsi gratuiti e a pagamento
- **CommunityManager81** (H3-12) — Telegram: moderazione e engagement
- **YouTubeAgent81** (H3-14) — SEO video @sicurissimo
- **EmailMarketing81** (H3-24) — Campagne Brevo con CONFERMO INVIO
- **GamificationEngine81** (H3-37) — Badge, livelli, classifiche

**Infrastruttura:**
- **WebhookManager81** (H3-27) — Validazione firma HMAC su tutti i webhook
- **APIGateway81** (H3-28) — Rate limiting, auth, CORS whitelist
- **BackupSystem81** (H3-29) — Backup automatico + test restore
- **MonitoringSystem81** (H3-30) — Uptime, errori, performance
- **FraudDetection81** (H3-41) — Anti-frode: doppi account, abuso referral
- **ComplianceScore81** (H3-49) — Score 0-100 per ogni cliente

---

## PC OPERATOR LAYER — 13 OPERATORI

Gli operatori PC gestiscono le azioni verso servizi esterni con sistema di permessi F0-F4.

| ID | Operatore | Approval |
|----|-----------|---------|
| PCO-01 | BrevoCampaignOperator81 | CONFERMO INVIO |
| PCO-02 | EmailOperator81 | CONFERMO INVIO |
| PCO-03 | SocialPublisher81 | CONFERMO PUBBLICA |
| PCO-04 | CalendarOperator81 | Autonomo per routine |
| PCO-05 | DriveOperator81 | Autonomo (no delete) |
| PCO-06 | SheetsOperator81 | Autonomo (no struttura) |
| PCO-07 | TelegramOperator81 | CONFERMO INVIO broadcast |
| PCO-08 | WhatsAppOperator81 | CONFERMO INVIO broadcast |
| PCO-09 | PDFOperator81 | Autonomo per generazione |
| PCO-10 | FormOperator81 | CONFERMO PUBBLICA |
| PCO-11 | ScriptOperator81 | CONFERMO AZIONE CRITICA deploy |
| PCO-12 | APIOperator81 | Autonomo read, confirma write |
| PCO-13 | SecurityAuditOperator81 | Solo report, mai auto-patch |

---

## FINANCE & DAILY OPS — 19 AGENTI

| ID | Agente | Funzione |
|----|--------|---------|
| FIN-01 | FinancePilot81 | Pilota centrale finanza |
| FIN-02 | ArubaInvoiceOperator81 | FE su Aruba — CONF. CRITICA |
| FIN-03 | ReconciliationAgent81 | Match pagamenti-fatture |
| FIN-04 | RevenueTracker81 | MRR, ARR, churn revenue |
| FIN-05 | TaxCompliance81 | IVA, F24, scadenze fiscali |
| FIN-06 | CashFlowManager81 | Proiezioni liquidità 30/60/90gg |
| FIN-07 | CommissionPayout81 | Bonifici provvigioni — CONF. CRITICA |
| FIN-08 | DailyOpsPilot81 | Briefing mattutino + EOD summary |
| FIN-09 | BudgetManager81 | Budget vs actual, forecast |
| FIN-10 | InvoiceProcessor81 | OCR fatture fornitori |
| FIN-11 | ExpenseManager81 | Note spese team |
| FIN-12 | FinancialReporting81 | P&L, bilancio, rendiconto |
| FIN-13 | PaymentReminder81 | Solleciti pagamento progressivi |
| FIN-14 | SubscriptionBilling81 | Fatturazione abbonamenti ricorrenti |
| FIN-15 | TreasuryAgent81 | Tesoreria — CONF. CRITICA |
| FIN-16 | AccountingSync81 | Sync con software contabile |
| FIN-17 | KPIFinance81 | CAC, LTV, EBITDA, gross margin |
| FIN-18 | PricingOptimizer81 | Ottimizzazione prezzi su dati |
| FIN-19 | InvestorReporting81 | Deck per stakeholder — CONF. PUBBLICA |

---

## FILE DI RIFERIMENTO

```
agents/
├── registry.agents.json      ← Tutti i 150 agenti con dettaglio completo
├── routing.matrix.yaml       ← Keyword → agente. Usato da Orchestrator81
├── semantic_rules.yaml       ← Parole vietate, naming, protocollo scrittura
├── tool_bundles.yaml         ← Set strumenti per categoria agenti
├── FLOTTA_81_AGENTI.md       ← Questo file — guida operativa
└── memory/
    └── memory_index.md       ← Dove vive ogni informazione nel sistema
```

---

## COSA NON FARE (REMINDER RAPIDO)

Non fare deploy. Non pubblicare nulla. Non cambiare architettura senza conferma.
Non toccare logiche database, PayGate, wallet, PV, PV+, provvigioni o webhook senza conferma.
Non cancellare file. Non mettere credenziali nel codice. Non usare CORS wildcard.
Non scrivere recensioni false. Non promettere guadagni. Non inventare norme.
Non dire "staking", "rendimento", "APY", "investimento" per LOCK81+, PV o PIX81+.
Non usare il nome dell'Ammiraglio in pubblico. Scrivere sempre "la direzione".

---

*Flotta costruita: 2026-06-17 | Sistema 81+ v1.0.0*
