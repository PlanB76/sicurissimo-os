# README DEPLOY STAGING — HUB1 81plus.net
## Branch: bonifica-master-vivo-wave1
## Data: 14 giugno 2026

Guida tecnica per eseguire il sito in locale per test e staging prima del deploy in produzione.

---

## PREREQUISITI LOCALI

- PHP 8.1+
- MySQL 8.0+ (oppure SQLite per test rapido)
- Composer (opzionale, non richiesto)
- Un browser moderno

---

## TEST RAPIDO CON PHP BUILT-IN SERVER

Per testare in locale senza installare nulla:

```bash
# 1. Entra nella cartella live
cd /home/user/sicurissimo-os/live

# 2. Crea il file .env locale (copia il template)
cp .env.example .env

# 3. Modifica .env con i tuoi valori locali:
# DB_HOST=127.0.0.1
# DB_NAME=81plus_local
# DB_USER=root
# DB_PASS=tua_password_locale
# GROQ_API_KEY=la_tua_chiave_groq
# ADMIN_KEY=test_admin_key_locale
# APP_SECRET=una_stringa_casuale_lunga_per_test

# 4. Crea il database locale MySQL
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS 81plus_local CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 5. Avvia il server PHP built-in
php -S localhost:8080 -t .

# 6. Apri il browser su http://localhost:8080
```

---

## INSTALLAZIONE DATABASE

Con il server avviato e `.env` configurato:

```
http://localhost:8080/installer.php?k=test_admin_key_locale
```

Questo crea tutte le 42 tabelle, l'account admin SIC-0000001 e un account demo.

**IMPORTANTE:** Cancellare `installer.php` prima di ogni deploy in produzione.

---

## STRUTTURA CARTELLE

```
live/
├── api/              ← 78 endpoint PHP (auth, pagamenti, gamification, ecc.)
├── src/              ← 24 librerie condivise PHP
├── data/             ← JSON statici (kb, listini, configurazioni)
├── sql/              ← 18 patch SQL (tabelle aggiuntive oltre installer)
├── n8n/              ← 5 flussi automazione n8n
├── assets/           ← immagini, icone, font
├── uploads/          ← file caricati dagli utenti (vuoto in staging)
├── 81plus.academy/   ← landing sub-dominio
├── 81plus.bond/      ← landing sub-dominio
├── [altri sub-domini]/
├── _redirects/       ← .htaccess per ogni sub-dominio legacy
├── _reference/       ← Schema SQL di riferimento (non eseguire)
├── _flotta/          ← Configurazione flotta AI 118 agenti
├── .env.example      ← Template variabili ambiente
├── .env              ← LOCALE SOLO — mai committare
├── .htaccess         ← Rewrite rules, protezione file sensibili
├── installer.php     ← Setup DB — CANCELLARE DOPO USO
└── [107 pagine HTML]
```

---

## VARIABILI AMBIENTE RICHIESTE

Tutte le variabili vanno in `.env` per locale, in hPanel per produzione.

### Obbligatorie subito

| Variabile | Descrizione | Esempio |
|-----------|-------------|---------|
| `DB_HOST` | Host database | `localhost` |
| `DB_NAME` | Nome database | `u173050672_81plusglobal` |
| `DB_USER` | Utente MySQL | `u173050672_81plus` |
| `DB_PASS` | Password MySQL | `[ruotare prima del live]` |
| `APP_SECRET` | Segreto JWT/HMAC | stringa 40+ caratteri |
| `ADMIN_KEY` | Protezione endpoint admin | stringa 32+ caratteri |
| `GROQ_API_KEY` | AI chat (Graziella/Nicolas) | `gsk_...` |

### Obbligatorie per funzioni specifiche

| Variabile | Funzione |
|-----------|---------|
| `BREVO_API_KEY` | Email transazionali (welcome, scadenze) |
| `PAYPAL_CLIENT_ID` | PayGate81+ PayPal |
| `PAYPAL_CLIENT_SECRET` | PayGate81+ PayPal |
| `PAYPAL_WEBHOOK_ID` | Verifica webhook PayPal |
| `TELEGRAM_BOT_TOKEN` | Notifiche Telegram |
| `N8N_SECRET` | Firma webhook n8n |
| `BACKUP_SECRET` | Protezione endpoint backup |
| `WELCOME_FROM` | `welcome@81plus.net` |
| `INFO_FROM` | `info@81plus.net` |

---

## STAGING SU HOSTINGER (prima del go-live)

Per testare in un ambiente più vicino alla produzione senza andare live:

1. Crea un sottodominio `staging.81plus.net` su hPanel
2. Carica il contenuto di `live/` nella cartella del sottodominio
3. Crea un database MySQL separato (es. `81plus_staging`)
4. Configura le variabili ambiente per il sottodominio staging
5. Esegui `installer.php?k=ADMIN_KEY` sul sottodominio
6. Testa tutti i flussi da TODO_WAVE1_STAGING.md
7. Cancella `installer.php` dal sottodominio staging dopo l'installazione

**Nota:** Usa credenziali PayPal sandbox per staging, non quelle live.

---

## CRON JOB (staging e produzione)

Configurare su hPanel → Cron Jobs:

```
# Ogni ora (attiva welcome flow, blog bot, scadenze, AI)
0 * * * * curl -s "https://81plus.net/api/cron.php?k=LA_TUA_ADMIN_KEY" > /dev/null 2>&1
```

---

## PROTEZIONI .htaccess ATTIVE

Il file `.htaccess` già protegge:
- ❌ Accesso diretto ai file `.env`, `.sql`, `.md`, `.json`
- ❌ Accesso diretto a `src/` e `data/`
- ✅ Redirect da legacy domains (NETWORK81+, 81plus.digital) ai nuovi URL
- ✅ HTTPS forzato
- ✅ Gzip compression
- ✅ Cache headers per assets statici

---

## TROUBLESHOOTING

**502/503 su api/:**
→ Verificare che PHP 8.1+ sia attivo in hPanel → PHP Manager

**Errore JWT:**
→ Verificare `APP_SECRET` sia uguale in tutte le richieste SSO cross-domain

**PayPal non funziona:**
→ Verificare che il webhook PayPal punti a `https://81plus.net/api/payments_webhook.php`

**Email non arrivano:**
→ Verificare `BREVO_API_KEY` e che il dominio mittente sia verificato su Brevo

**Database errori:**
→ Eseguire le patch SQL da `live/sql/` in ordine numerico se mancano tabelle dopo installer.php

---

## CONTATTI TECNICI

Per problemi tecnici: info@81plus.net
Per emergenze deploy: WhatsApp wa.me/3388771737

---

*Documento generato — branch bonifica-master-vivo-wave1*
*Non commitare mai .env con valori reali nel repository.*
