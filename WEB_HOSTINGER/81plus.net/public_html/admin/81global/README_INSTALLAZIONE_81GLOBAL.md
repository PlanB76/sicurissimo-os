# 81PLUS Global OS — Guida Installazione Hostinger

Questa guida ti porta dall'upload all'endpoint funzionante in meno di 30 minuti.

---

## Requisiti

- PHP 7.4 o superiore (consigliato PHP 8.1+)
- MySQL 5.7 o MariaDB 10.3+
- Apache con mod_rewrite e mod_headers attivi
- Accesso FTP o File Manager Hostinger
- phpMyAdmin o client MySQL per l'import del database

---

## Struttura File

```
/public_html/admin/81global/
├── .htaccess
├── _bootstrap.php
├── README_INSTALLAZIONE_81GLOBAL.md
├── admin/
│   └── test_api.html
├── api/
│   ├── lex81/
│   │   ├── risk-profile.php
│   │   ├── norms/list.php
│   │   ├── obligations/list.php
│   │   └── sanctions/list.php
│   └── leadgen81/
│       ├── contact/register.php
│       ├── contact/profile.php
│       ├── contact/unsubscribe.php
│       ├── segment/calculate.php
│       ├── flow/trigger.php
│       ├── flow/list.php
│       ├── email-queue/process.php
│       ├── email-queue/mark-sent.php
│       ├── email-queue/track-open.php
│       ├── email-queue/track-click.php
│       └── xmas/unlock.php
├── config/
│   ├── config.example.php
│   └── config.php (da creare tu, non committare)
└── database/
    └── lex81_leadgen81_mysql.sql
```

---

## Installazione Passo per Passo

### Step 1 — Upload File

Carica la cartella `81global/` dentro `/public_html/admin/` tramite:
- Hostinger File Manager (drag and drop la cartella compressa)
- FTP con FileZilla o Cyberduck

Il percorso finale deve essere: `/public_html/admin/81global/`

### Step 2 — Importa il Database

Apri phpMyAdmin dal pannello Hostinger.

Crea un database nuovo oppure usa quello esistente.

Importa il file `database/lex81_leadgen81_mysql.sql`:
- Tab Importa
- Scegli il file
- Formato SQL
- Clicca Esegui

Il file crea tutte le tabelle e carica i dati di partenza in un unico blocco transazionale.

### Step 3 — Configura config.php

Entra nella cartella `config/` via File Manager.

Copia `config.example.php` e rinominalo `config.php`.

Apri `config.php` e compila questi valori:

```php
define('DB_HOST',   'localhost');
define('DB_NAME',   'il_tuo_database');
define('DB_USER',   'il_tuo_utente');
define('DB_PASS',   'la_tua_password');
define('ADMIN_SECRET', 'genera-una-stringa-random-di-64-caratteri');
define('BASE_URL',  'https://81plus.net/admin/81global');
define('FROM_EMAIL','nicolas@81plus.net');
```

Per generare ADMIN_SECRET usa questo comando (se hai accesso SSH):

```bash
openssl rand -hex 32
```

Oppure usa un generatore di password online e copia 64 caratteri casuali.

### Step 4 — Permessi File

Imposta i permessi tramite File Manager o FTP:

| File | Permessi |
|------|----------|
| config/config.php | 640 |
| _bootstrap.php | 640 |
| tutti i .php | 644 |
| cartelle | 755 |

La cartella `database/` e gia protetta via .htaccess.

### Step 5 — Verifica .htaccess

Assicurati che il tuo piano Hostinger abbia mod_rewrite e mod_headers attivi.

Per verificare, apri nel browser: `https://tuodominio.it/admin/81global/api/lex81/norms/list.php`

Se vedi una risposta JSON, il sistema funziona.

Se vedi un errore 500, controlla il log PHP in Hostinger panel sotto Avanzato > Log Errori.

### Step 6 — Test API

Apri nel browser: `https://tuodominio.it/admin/81global/admin/test_api.html`

Inserisci il tuo Base URL e l'Admin Secret che hai configurato.

Testa nell'ordine:
1. Lista Norme (nessun parametro) — verifica che il database sia carico
2. Registra Contatto — crea il primo lead e verifica il flow WELCOME
3. Processa Coda Email (Admin) — verifica che le email siano in coda

---

## Sicurezza

Cambia sempre ADMIN_SECRET prima di andare in produzione.

Non committare mai `config.php` su GitHub. Il file e gia in `.gitignore` se hai clonato il repo correttamente.

HTTPS e obbligatorio. Tutti i cookie e i link di tracking devono girare su HTTPS.

Le credenziali database non devono mai apparire nei log. Verifica che `display_errors = Off` in produzione.

---

## Troubleshooting

**Errore 403 su config.php o _bootstrap.php**
Questo e corretto. Questi file non sono accessibili dal browser per design.

**Errore 500 sulle API**
Apri Hostinger panel, vai su Avanzato, Log Errori PHP. L'errore reale e li.
Il caso piu comune e una costante non definita in config.php.

**Errore CORS su richieste fetch dal browser**
Verifica che il dominio da cui fai la richiesta sia in ALLOWED_ORIGINS dentro config.php.
Controlla che mod_headers sia attivo su Apache.

**Email in coda ma non inviate**
L'endpoint `email-queue/process.php` restituisce i testi pronti ma non invia autonomamente.
Devi collegare un servizio SMTP esterno (Brevo, SendGrid, AWS SES) che chiama `mark-sent.php` dopo ogni invio.

**Il tracking pixel non funziona**
Verifica che l'URL di track-open.php sia raggiungibile pubblicamente.
Molti client email bloccano i pixel di default. Il sistema conta solo le aperture con pixel abilitato.

---

## Supporto

Sito: https://www.sicurissimo.online

WhatsApp: 3388771737

Nicolas risponde entro 24 ore.
