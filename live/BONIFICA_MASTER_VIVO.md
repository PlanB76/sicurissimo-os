# BONIFICA MASTER VIVO — Report Tecnico
## Branch: bonifica-master-vivo-wave1
## Data: 14 giugno 2026
## Cartella: live/ (81plus_LIVE ZIP — HUB1 81plus.net)
## Revisore: Claude (Agente di Esecuzione)

---

## 1. RIEPILOGO ESECUTIVO

| Categoria | Trovate | Corrette Automaticamente | Da Confermare |
|-----------|---------|--------------------------|---------------|
| Credenziali esposte | 0 | — | — |
| File pericolosi in produzione | 2 | 0 | 2 (vedi §3) |
| Nomi vietati nel copy pubblico | 8 | 7 | 1 (rinomina file) |
| Parole vietate problematiche | 1 | 1 | 0 |
| Email errate | 2 | 2 | 0 |
| Parole vietate in disclaimer | 74 | 0 | 0 (conformi) |

---

## 2. CREDENZIALI E SEGRETI — PRIORITÀ ASSOLUTA

**RISULTATO: NESSUNA CREDENZIALE HARDCODED TROVATA NEL CODICE.**

Tutti i file PHP che usano credenziali le leggono da variabili d'ambiente:
- `src/ai81.php` → `getenv('GROQ_API_KEY')` ✓
- `src/brevo81.php` → `getenv('BREVO_API_KEY')` ✓
- `src/telegram81.php` → `getenv('TELEGRAM_BOT_TOKEN')` ✓
- `src/config.php` → `env('DB_PASS')` ✓
- `.env.example` → contiene solo placeholder (`changeme`, `metti_qui_*`) ✓

**NOTA PER MIRCO:** La chiave Groq presente nel file `MEMORIA_CLAUDE_GENESYS.md` (caricato come upload nella sessione Claude) NON è nel codice del repository. Ruota comunque quella chiave su console.groq.com prima del go-live.

---

## 3. FILE PERICOLOSI IN PRODUZIONE

### 3.1 installer.php — CANCELLARE DOPO IL PRIMO DEPLOY

**File:** `live/installer.php`
**Gravità:** 🔴 ALTA
**Motivo:** Script che crea tutte le 42 tabelle, account admin (SIC-0000001) e account demo. Se lasciato in produzione è accessibile da chiunque conosca l'URL.
**Azione:** Cancellare manualmente dopo che il database è installato e verificato. Le istruzioni sono già nel file stesso alla riga 84.
**Alternativa sicura:** Aggiungere al `.gitignore` e al `.htaccess` produzione.

### 3.2 api/backup.php — PROTEGGERE CON BACKUP_SECRET FORTE

**File:** `live/api/backup.php`
**Gravità:** 🟠 MEDIA
**Motivo:** Esporta l'intero database in ZIP/TAR.GZ. Protetto da `BACKUP_SECRET` in env.
**Azione:** Verificare che `BACKUP_SECRET` sia una stringa di almeno 40 caratteri casuali. Non esporre mai l'endpoint pubblicamente senza autenticazione HMAC.

---

## 4. NOMI VIETATI — Risultati

### 4.1 GreenGrove / Groove81 → Green81+
**Stato: CORRETTI AUTOMATICAMENTE ✓**

File corretti:
- `live/greengrove.html` — titolo, h1, descrizioni sostituite con "Green81+"
- `live/zone_modello.html` — riga 97
- `live/chi-siamo.html` — riga 65
- `live/christmas.html` — righe 89, 92
- `live/flowchart3d.html` — riga 152
- `live/LOGICA_FUNZIONALE_81PLUS.md` — riga 261
- `live/FOTOGRAFIA_81PLUS_13_GIUGNO_2026.md` — riga 415

**Da confermare (§8):** rinomina file `api/greengrove.php` → `api/green81.php`

### 4.2 SICONET → NETWORK 81+
**Stato: CONFORME — nessuna correzione necessaria ✓**

Le occorrenze di "SICONET" sono SOLO in:
- `.htaccess` righe 28-30, 86-88: redirect legacy `siconet.online` → `81plus.network` (corretto)
- `data/kb/listino.txt`: "NETWORK 81+ ex SICONET" (contesto storico ammesso)
- `sito81.js` riga 122: regex routing legacy (non copy pubblico)

Nessuna occorrenza nel copy pubblico che citi SICONET come nome attivo.

### 4.3 SAFE 5.0 → 81plus.digital
**Stato: CONFORME — nessuna correzione necessaria ✓**

Occorrenze solo in `.htaccess` come redirect legacy (`safe5.xyz`, `safe5.it` → `81plus.digital`). Corretto e necessario.

---

## 5. PAROLE VIETATE — Risultati

### 5.1 rendita
**Stato: CORRETTA AUTOMATICAMENTE ✓**
- `network81.html` riga 111: "La tua rendita." → "La tua commissione ricorrente."

### 5.2 investimento (47 occorrenze)
**Stato: TUTTE CONFORMI — nessuna correzione ✓**
Tutte le occorrenze sono in disclaimer e negazioni esplicite:
- "81X non è uno strumento finanziario... non costituisce sollecitazione all'investimento"
- "Non è un investimento e non promette rendimenti"
- "La licenza non è un investimento finanziario, è un diritto d'uso operativo"

### 5.3 rendimento (27 occorrenze)
**Stato: TUTTE CONFORMI ✓**
Tutte in contesto di negazione ("mai garantito", "non promette rendimento").

### 5.4 APY
**Stato: CONFORME ✓**
Appare SOLO nel vocabolario proibito interno (`LOGICA_FUNZIONALE_81PLUS.md`), mai nel copy pubblico.

### 5.5 CEX
**Stato: CONFORME ✓**
Appare solo come negazione: "Solo DEX, nessun CEX", "niente CEX per ora".

### 5.6 profitto garantito / guadagno garantito / staking garantito
**Stato: NON TROVATE ✓**

### 5.7 4000 video YouTube
**Stato: NON TROVATO ✓**
"4000" appare solo in contesto lead (4000 lead da importare), mai in riferimento a video YouTube.

---

## 6. EMAIL — Risultati

### 6.1 info@sicurissimo.io → info@81plus.net
**Stato: CORRETTO AUTOMATICAMENTE ✓**
- `live/index.html` riga 1741
- `live/recensioni.html` riga 925

### 6.2 welcome@81plus.net
**Stato: CONFORME ✓**
Usata SOLO in `src/config.php` (FROM address welcome email) e `.env.example` (placeholder). Mai in footer, pagine pubbliche o contatti.

### 6.3 sicurissimo.io come dominio (non email)
**Stato: DA VALUTARE — non corretto automaticamente**
`index.html` contiene riferimenti al dominio `sicurissimo.io` in:
- Ticker promo (riga 1102, 1108): "Corsi accreditati su sicurissimo.io"
- Eco-tag (riga 1386): "sicurissimo.io / .online"
- Link footer (righe 1731-1733): link a `https://sicurissimo.io/servizi.html`

Questi sono link a un sito reale, non un errore di email. Verificare se `sicurissimo.io` è ancora attivo e se i link devono puntare a `81plus.net` o restare.

---

## 7. CORREZIONI APPLICATE AUTOMATICAMENTE

| # | File | Modifica | Verificata |
|---|------|----------|-----------|
| 1 | `index.html` | `info@sicurissimo.io` → `info@81plus.net` | ✓ |
| 2 | `recensioni.html` | `info@sicurissimo.io` → `info@81plus.net` | ✓ |
| 3 | `greengrove.html` | `GreenGrove81` → `Green81+` in titolo, h1, copy | ✓ |
| 4 | `zone_modello.html` | `GreenGrove81` → `Green81+` | ✓ |
| 5 | `chi-siamo.html` | `GreenGrove81` → `Green81+` | ✓ |
| 6 | `christmas.html` | `GreenGrove81` → `Green81+` | ✓ |
| 7 | `flowchart3d.html` | `GREENGROVE81` → `GREEN81+` | ✓ |
| 8 | `LOGICA_FUNZIONALE_81PLUS.md` | `GreenGrove81` → `Green81+` | ✓ |
| 9 | `FOTOGRAFIA_81PLUS_13_GIUGNO_2026.md` | `GreenGrove81` → `Green81+` | ✓ |
| 10 | `network81.html` | "La tua rendita." → "La tua commissione ricorrente." | ✓ |

---

## 8. CORREZIONI DA CONFERMARE (non applicate)

| # | Tipo | File | Azione Proposta | Rischio |
|---|------|------|-----------------|---------|
| C1 | Rinomina file | `api/greengrove.php` | Rinominare in `api/green81.php` + aggiornare tutti i riferimenti (`fetch('/api/greengrove.php')`) | Medio — tocca URL API usate in JS |
| C2 | Colonne DB | `greengrove_alberi`, `greengrove_confermati` in `api/greengrove.php` | Lasciare invariate O aggiungere alias nelle query | Medio — tocca schema DB |
| C3 | Link footer index.html | Righe 1731-1733: link a `sicurissimo.io` | Confermare se il dominio è ancora attivo o va aggiornato | Basso |
| C4 | Cancellazione | `installer.php` | Cancellare DOPO il deploy in produzione (non ora) | Alto se lasciato in prod |

**Per confermare: rispondere "Confermo C1, C2, C3, C4" oppure indicare quali applicare.**

---

## 9. FILE DA NON USARE IN PRODUZIONE (tenere in repo, non deployare)

| File | Motivo |
|------|--------|
| `installer.php` | Da cancellare subito dopo il primo deploy |
| `.env.example` | Template, non deployare con valori reali |
| `BONIFICA_MASTER_VIVO.md` | Documento interno |
| `TODO_WAVE1_STAGING.md` | Documento interno |
| `README_DEPLOY_STAGING.md` | Documento interno |
| `LEGGIMI_DEPLOY.txt` | Documento interno |
| `LOGICA_FUNZIONALE_81PLUS.md` | Documento interno — proteggere con .htaccess |
| `FOTOGRAFIA_81PLUS_13_GIUGNO_2026.md` | Documento interno |
| `CATALOGO_PRODOTTI_81PLUS.md` | Documento interno |
| `PIANO_BUILD_OPENWORK.md` | Documento interno |

**Nota:** Il file `.htaccess` già protegge i file `.md`, `.sql` e `.json` negando accesso diretto.

---

*Report generato automaticamente — branch bonifica-master-vivo-wave1*
*Non fare deploy fino a conferma delle correzioni C1-C4.*
