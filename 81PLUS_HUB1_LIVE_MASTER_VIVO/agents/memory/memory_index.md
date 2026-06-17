# MEMORY INDEX 81+ — Mappa della Memoria Persistente del Sistema
# Data: 2026-06-17

Questo file mappa dove vive ogni tipo di informazione nel sistema 81+.
Prima di cercare qualcosa, consulta questo indice per trovare subito la sorgente corretta.

---

## 1. KNOWLEDGE BASE — Contesto Brand e Normativo

**Dove:** Google Sheet SICURISSIMO MASTER V1-2026 → scheda KNOWLEDGE_BASE
**Link:** https://docs.google.com/spreadsheets/d/1mzF28NNi8orU9HgqTdmKk1csYNCrYPjDtt1q6VBz26M
**Aggiornato da:** KnowledgeBase81 (H1-14), CommandCenter81 (H1-16)
**Contiene:**
- Contesto brand 81+, NETWORK81+, SICURISSIMO
- Normative aggiornate (D.Lgs 81/08, ASR 2025, HACCP, ISO)
- FAQ ottimizzate di Nicolas
- Regole linguistiche e naming
- Preferenze e decisioni di Mirco

---

## 2. LEAD DATABASE

**Dove:** Google Sheet → scheda LEAD_DATABASE
**Aggiornato da:** NicolasCore81 (H1-06), LeadScoring81 (H1-07), CRMBridge81 (H1-08)
**Colonne principali:**
- ID lead, nome, telefono, email
- Settore ATECO
- Data primo contatto
- Status (freddo/tiepido/caldo)
- Score (0-100)
- Storico interazioni
- Tag campagna sorgente

---

## 3. SYSTEM LOGS

**Dove:** Google Sheet → scheda SYSTEM_LOGS
**Aggiornato da:** SystemLogger81 (H1-18) — tutte le azioni sistema
**Struttura:** timestamp | agent_id | action | input_hash | output_hash | status
**Regola:** Append only. Mai cancellare righe.

---

## 4. NORMATIVE DATABASE

**Dove:** `/81PLUS_HUB1_LIVE_MASTER_VIVO/docs/master/ASR_2025_COMPLIANCE_ENGINE_MASTER_CLEAN.md`
**Contiene:**
- ASR 2025 completo (Rep. Atti n. 59/CSR 17/04/2025)
- Mapping ATECO → corsi obbligatori
- DL 16h, Cantieri 6h, DL-RSPP 8h + moduli ATECO
- D.Lgs 81/08 struttura principale
- Naming rules e semantic guard

---

## 5. AGENT REGISTRY

**Dove:** `/81PLUS_HUB1_LIVE_MASTER_VIVO/agents/registry.agents.json`
**Contiene:** Tutti i 150 agenti con ID, ruolo, tools, trigger, output, constraints
**Aggiornato da:** CommandCenter81 (H1-16) su decisione di Mirco

---

## 6. ROUTING MATRIX

**Dove:** `/81PLUS_HUB1_LIVE_MASTER_VIVO/agents/routing.matrix.yaml`
**Usato da:** Orchestrator81 (H1-01) per dispatch richieste
**Contiene:** keyword → agente, priority overrides, fallback chain

---

## 7. SEMANTIC RULES

**Dove:** `/81PLUS_HUB1_LIVE_MASTER_VIVO/agents/semantic_rules.yaml`
**Usato da:** SemanticGuard81 (H1-02) — attivo su tutti gli output
**Contiene:** parole vietate, naming rules, protocollo scrittura, permission levels

---

## 8. TOOL BUNDLES

**Dove:** `/81PLUS_HUB1_LIVE_MASTER_VIVO/agents/tool_bundles.yaml`
**Contiene:** Set riutilizzabili di strumenti per categoria agenti, credenziali .env required

---

## 9. CALENDARIO EDITORIALE

**Dove:** Google Calendar → calendari 81+
**Aggiornato da:** CalendarOperator81 (PCO-04), SocialAutomator81 (H1-13)
**Orari fissi:**
- 04:00 → DailyMonitor81
- 05:00 → Messaggi buongiorno WhatsApp
- 08:15/08:30 → Post 1 (stagionale)
- 09:00 → Report lead caldi
- 12:30 → Post 2
- 15:30/15:45 → Post 3 (stagionale)
- 19:00/19:20 → Post 4 (stagionale)
- 21:00 → Generazione post giorno successivo

---

## 10. ASSET DRIVE

**Dove:** Google Drive → ECOSYSTEM-SICURISSIMO
**Link:** https://drive.google.com/drive/folders/1aUOkuD8j7QSUM3ElJhSgpVMqUgULp-HR
**Contiene:**
- PDF normativi (INAIL, circolari, decreti)
- File .gs (Google Apps Script skills)
- Asset visivi (loghi, template, brand kit)
- Documenti master (WELLFARE PROGRAM, guide, manuali)

---

## 11. DATABASE PRINCIPALE (MySQL/PostgreSQL)

**Dove:** Server 81plus.net
**Tabelle critiche:**
- `users` — anagrafica membri con SIC-ID
- `memberships` — abbonamenti attivi con tier
- `pv_transactions` — ledger PV idempotente
- `pvplus_claims` — reward PV+ con UNIQUE KEY
- `genesys81_slots` — 81 posti max con assegnazioni
- `pix81_slots` — 1000 slot PIX81+ max
- `lock81_contracts` — contratti LOCK 180 giorni
- `leads` — database lead qualificati
- `commissions` — provvigioni partner
- `audit_log` — log immutabile azioni

---

## 12. DOCUMENTI MASTER (Repository Git)

**Branch attivo:** `claude/create-claude-md-docs-JWjKP`
**Percorso base:** `/81PLUS_HUB1_LIVE_MASTER_VIVO/`

| File | Descrizione | Ultimo commit |
|------|-------------|---------------|
| `docs/master/ASR_2025_COMPLIANCE_ENGINE_MASTER_CLEAN.md` | Knowledge base ASR 2025 completa | b7ff9f5 |
| `docs/master/WELLFARE_PROGRAM_81PLUS_V5_PRO.html` | Documento commerciale V5 PRO | 3b7af47 |
| `agents/registry.agents.json` | Registry 150 agenti AI | questo commit |
| `agents/routing.matrix.yaml` | Matrice routing agenti | questo commit |
| `agents/semantic_rules.yaml` | Regole semantiche e naming | questo commit |
| `agents/tool_bundles.yaml` | Bundle strumenti per agenti | questo commit |
| `agents/memory/memory_index.md` | Questo file | questo commit |
| `LEGGIMI_BLOCCO1.md` | Stato Blocco 1 | cd82e8f |
| `docs/testing/CONTROLLO_DURO_BLOCCO_2_1.md` | Checklist test 25 item | 074f717 |

---

## REGOLE DI ACCESSO MEMORIA

1. **Leggi sempre KNOWLEDGE_BASE prima di rispondere su temi normativi.**
2. **Non scrivere mai su pv_transactions o pvplus_claims da client-side JS.**
3. **Log SYSTEM_LOGS è append-only: mai cancellare, mai modificare righe passate.**
4. **Credenziali: mai in memoria persistente pubblica. Solo variabili d'ambiente.**
5. **SIC-ID immutabili: mai sovrascrivere un SIC-ID esistente.**
6. **KNOWLEDGE_BASE è la sorgente di verità: in caso di conflitto, vince KB.**
