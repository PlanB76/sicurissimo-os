# TODO WAVE 1 — Staging & Go-Live Checklist
## Branch: bonifica-master-vivo-wave1
## Data: 14 giugno 2026
## HUB1: 81plus.net

Questa lista copre tutto ciò che deve essere completato prima che HUB1 vada live.
Spunta ogni voce solo dopo verifica reale, non teorica.

---

## BLOCCO 0 — Prerequisiti (fare prima di tutto)

- [ ] **Rotazione credenziali:** Ruotare chiave Groq (`gsk_...`) su console.groq.com
- [ ] **Variabili ambiente Hostinger:** Configurare in hPanel → Avanzate → Variabili ambiente:
  - `ADMIN_KEY` — stringa segreta (min 32 caratteri casuali)
  - `APP_SECRET` — stringa lunga casuale (min 40 caratteri)
  - `DB_PASS` — password database MySQL
  - `GROQ_API_KEY` — nuova chiave Groq dopo rotazione
  - `BREVO_API_KEY` — chiave Brevo per email transazionali
  - `PAYPAL_CLIENT_ID` e `PAYPAL_CLIENT_SECRET` — credenziali PayPal
  - `PAYPAL_WEBHOOK_ID` — webhook ID PayPal
  - `TELEGRAM_BOT_TOKEN` — token bot Telegram
  - `N8N_SECRET` — segreto n8n
  - `BACKUP_SECRET` — stringa min 40 caratteri per protezione backup
  - `WELCOME_FROM` — `welcome@81plus.net`
  - `INFO_FROM` — `info@81plus.net`
- [ ] **Database MySQL creato** su Hostinger con nome `u173050672_81plusglobal`
- [ ] **Dominio `81plus.net` punta al server Hostinger** (DNS propagato)

---

## BLOCCO 1 — Deploy iniziale

- [ ] Caricare la cartella `live/` in `public_html` di `81plus.net` su Hostinger (mantenendo sottocartelle)
- [ ] Verificare che `.htaccess` sia caricato correttamente (test: accedere a un URL non esistente → deve dare 404.html brandizzato)
- [ ] Aprire `https://81plus.net/installer.php?k=LA_TUA_ADMIN_KEY` — installa tutte le 42 tabelle
- [ ] Verificare che l'account admin SIC-0000001 esista nel DB
- [ ] **CANCELLARE `installer.php` immediatamente dopo** (non lasciarlo in produzione)
- [ ] Testare accesso a `https://81plus.net/api/health.php` → deve rispondere `{"ok":true}`

---

## BLOCCO 2 — Bonifica MASTER VIVO (da questo branch)

- [ ] Approvare e applicare correzione C1: rinomina `api/greengrove.php` → `api/green81.php`
- [ ] Approvare e applicare correzione C2: aggiornare riferimenti interni alle colonne DB
- [ ] Approvare e applicare correzione C3: verificare status dominio `sicurissimo.io` e aggiornare link footer
- [ ] Verificare che nessun GreenGrove81 rimanga nel copy pubblico dopo le correzioni
- [ ] Verificare che tutti i link di navigazione funzionino (404 check)

---

## BLOCCO 3 — Test funzionali core

### Autenticazione SIC-ID
- [ ] Registrazione nuovo utente → riceve SIC-ID univoco
- [ ] Login con email + password → JWT valido 120 secondi
- [ ] Logout → token invalidato
- [ ] Cambio password → funziona
- [ ] Welcome email ricevuta su `welcome@81plus.net`

### PayGate81+
- [ ] Acquisto PV via PayPal sandbox → PV accreditati correttamente
- [ ] Webhook PayPal ricevuto e processato
- [ ] Acquisto Revolut → stato "in attesa verifica admin"
- [ ] Admin può confermare pagamento Revolut → PV accreditati

### Gamification
- [ ] Welcome bonus 100 PV+ accreditato alla registrazione
- [ ] Activity mining: 1 PV+ ogni 5 minuti di attività valida
- [ ] Missioni completabili e badge assegnabili
- [ ] Piano Equilibrio: 1 PV+ = 0,20 PV nel calcolo

### PIX81+
- [ ] Acquisto PIX81+ (1000 PV) → PIX assegnato
- [ ] Contatore PIX non supera 1000 totali
- [ ] 1 PIX = 1 albero Green81+ registrato

---

## BLOCCO 4 — Test legal & compliance

- [ ] Nessuna pagina pubblica usa: investimento (affermativo), rendimento garantito, APY, staking garantito, guadagno garantito
- [ ] Exchange81+ mai chiamato CEX in nessuna pagina pubblica
- [ ] Disclaimer DEX presente su tutte le pagine Web3
- [ ] PV: copy chiarisce che non sono denaro elettronico, non rimborsabili, non convertibili in euro
- [ ] Privacy policy aggiornata e linking corretto
- [ ] Cookie banner funzionante con consenso reale
- [ ] Condizioni di vendita accessibili prima del pagamento
- [ ] Nome e telefono dell'Ammiraglio non compaiono nelle pagine pubbliche (usa "la direzione")

---

## BLOCCO 5 — Performance & SEO

- [ ] Lighthouse mobile score > 90 su index.html
- [ ] Immagini con lazy load
- [ ] Font con `display=swap`
- [ ] `.htaccess` ha compressione gzip e cache headers
- [ ] Sitemap.xml generata e accessibile
- [ ] robots.txt corretto (noindex su /api/, /admin_*, /plancia_*)
- [ ] Schema.org JSON-LD presente su index.html

---

## BLOCCO 6 — Automazioni

- [ ] Cron orario configurato su hPanel: `https://81plus.net/api/cron.php?k=ADMIN_KEY`
- [ ] n8n: 5 flussi importati da cartella `live/n8n/`
- [ ] N8N_SECRET configurato nelle variabili ambiente
- [ ] Test: welcome flow si attiva alla registrazione
- [ ] Test: blog bot genera post automatici
- [ ] Test: scadenze inviano notifiche

---

## BLOCCO 7 — Post-deploy

- [ ] Monitorare `SYSTEM_LOGS` per errori nelle prime 48 ore
- [ ] Verificare che `api/backup.php` esegua correttamente il backup notturno
- [ ] Testare accesso dashboard admin (`plancia_dio.html`) con account SIC-0000001
- [ ] Comunicare go-live alla community (Wave 2 → email + Telegram)

---

## STATO ATTUALE (14 giugno 2026)

| Blocco | Stato |
|--------|-------|
| Blocco 0 — Prerequisiti | 🟡 In corso |
| Blocco 1 — Deploy iniziale | ⬜ Non iniziato |
| Blocco 2 — Bonifica MASTER VIVO | 🟡 Parziale (10 correzioni automatiche, 4 da confermare) |
| Blocco 3 — Test funzionali | ⬜ Non iniziato |
| Blocco 4 — Legal & compliance | 🟡 Scansione completata, no blockers critici |
| Blocco 5 — Performance | ⬜ Non iniziato |
| Blocco 6 — Automazioni | ⬜ Non iniziato |
| Blocco 7 — Post-deploy | ⬜ Non iniziato |

**Prossimo step immediato:** Confermare C1-C4 da BONIFICA_MASTER_VIVO.md, poi procedere con Blocco 0.
