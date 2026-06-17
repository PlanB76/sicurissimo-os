# HOSTINGER — ISTRUZIONI PRECISE PER ATTIVARE IL SISTEMA 81+
# Tempo stimato: 20-30 minuti

---

## PRIMA DI INIZIARE — COSA TI SERVE

Tienili aperti sul desktop:

- [ ] Login Hostinger (hPanel)
- [ ] Credenziali database MySQL che crei al passo 3
- [ ] Chiave API Claude: https://console.anthropic.com → API Keys
- [ ] Chiave API Gemini: https://aistudio.google.com → Get API Key
- [ ] Token Bot Telegram (da @BotFather su Telegram)
- [ ] Token WhatsApp Business API (da Meta Business Suite)
- [ ] Chiave API Brevo (da app.brevo.com → Settings → API Keys)

---

## PASSO 1 — CONTROLLA LA VERSIONE PHP

**hPanel → Siti web → il tuo dominio → PHP Configuration**

Seleziona: **PHP 8.1** o **PHP 8.2**

Assicurati che queste estensioni siano attive (di solito lo sono già su Hostinger):
- pdo_mysql
- curl
- mbstring
- openssl
- json
- zip

Clicca **Salva** se hai cambiato versione.

---

## PASSO 2 — CREA IL DATABASE MYSQL

**hPanel → Database → MySQL Database**

1. Clicca **Crea nuovo database**
2. Nome database: `u123456789_81plus` (Hostinger aggiunge il prefisso automaticamente)
3. Crea utente: `u123456789_admin` con password forte (usa il generatore)
4. Assegna l'utente al database con **TUTTI I PRIVILEGI**

**SALVA QUESTI DATI — servono subito:**
```
Host:     localhost
Database: u123456789_81plus    ← (il nome esatto che ti mostra Hostinger)
Utente:   u123456789_admin
Password: [quella che hai scelto]
```

---

## PASSO 3 — CARICA I FILE VIA FILE MANAGER

**hPanel → File Manager → public_html**

### Opzione A: Carica il progetto Git (consigliato)

Se hai SSH (Hostinger Business/VPS):
```bash
ssh u123456789@tuodominio.com
cd public_html
git clone https://[IL_TUO_PAT]@github.com/PlanB76/sicurissimo-os.git .
cp -r 81PLUS_HUB1_LIVE_MASTER_VIVO/* .
```

### Opzione B: Carica manualmente (hosting condiviso)

1. Vai in **File Manager → public_html**
2. Crea cartella `setup`
3. Carica il file `MASTER_BLASTER_INSTALL.php` nella cartella `setup`

**Struttura minima da caricare:**
```
public_html/
├── setup/
│   └── MASTER_BLASTER_INSTALL.php    ← CARICA QUESTO PRIMO
├── agents/
│   ├── registry.agents.json
│   ├── routing.matrix.yaml
│   ├── semantic_rules.yaml
│   ├── tool_bundles.yaml
│   └── memory/
│       └── memory_index.md
└── docs/
    └── master/
        └── WELLFARE_PROGRAM_81PLUS_V5_PRO.html
```

---

## PASSO 4 — ESEGUI L'INSTALLER

Apri nel browser:
```
https://tuodominio.com/setup/MASTER_BLASTER_INSTALL.php
```

L'installer ti guida in 7 step:

| Step | Cosa fa | Tempo |
|------|---------|-------|
| 0 | Benvenuto e info server | 5 sec |
| 1 | Controlla PHP e estensioni | 5 sec |
| 2 | Crea le 12 tabelle MySQL | 30 sec |
| 3 | Inserisci tutte le API key | 5 min |
| 4 | Crea cartelle + protezioni .htaccess | 10 sec |
| 5 | Testa tutte le connessioni API | 30 sec |
| 6 | Mostra i comandi cron da copiare | 2 min |
| 7 | Finalizza + si autodistrugge | 10 sec |

**Al passo 3 ti serve tutto quello che hai preparato al passo "PRIMA DI INIZIARE".**

---

## PASSO 5 — CONFIGURA I CRON JOBS

**hPanel → Avanzate → Cron Jobs → Crea nuovo cron job**

Per ogni riga qui sotto: seleziona **Personalizzato**, copia il comando, clicca Aggiungi.

> **Prima trova il tuo path reale:**
> File Manager → clicca sulla cartella public_html → guarda la barra in alto.
> Sarà qualcosa come `/home/u123456789/public_html`

```
# Sostituisci /home/u123456789 con il TUO path

# 04:00 — DailyMonitor81
0 4 * * *   php /home/u123456789/public_html/cron/daily_monitor.php

# 05:00 — Messaggi buongiorno WhatsApp
0 5 * * *   php /home/u123456789/public_html/cron/morning_whatsapp.php

# 08:15 — Post 1 Telegram
15 8 * * *  php /home/u123456789/public_html/cron/post_social.php?slot=1

# 09:00 — LeadScoring81
0 9 * * *   php /home/u123456789/public_html/cron/lead_scoring.php

# 12:30 — Post 2 Telegram
30 12 * * * php /home/u123456789/public_html/cron/post_social.php?slot=2

# 15:30 — Post 3 Telegram
30 15 * * * php /home/u123456789/public_html/cron/post_social.php?slot=3

# 19:20 — Post 4 Telegram
20 19 * * * php /home/u123456789/public_html/cron/post_social.php?slot=4

# 21:00 — Genera post giorno successivo
0 21 * * *  php /home/u123456789/public_html/cron/generate_posts.php
```

**Come verificare che funzionino:**
hPanel → Cron Jobs → vai su "Log esecuzioni" dopo 24h. Dovresti vedere righe verdi.

---

## PASSO 6 — CONFIGURA GOOGLE SERVICE ACCOUNT

Per collegare Google Sheets, Drive e Calendar al sistema:

1. Vai su: https://console.cloud.google.com
2. Crea un progetto: `81plus-os`
3. Abilita queste API:
   - Google Sheets API
   - Google Drive API
   - Google Calendar API
4. Vai su **IAM → Service Accounts → Crea account di servizio**
5. Nome: `81plus-agent`
6. Crea chiave JSON → scarica il file
7. Apri il file JSON, copialo tutto
8. In hPanel File Manager → modifica `.env` → aggiungi:
   ```
   GOOGLE_SERVICE_ACCOUNT_JSON={"type":"service_account","project_id":"..."}
   ```
   (tutto su una riga)

**Poi condividi il foglio Google:**
- Apri il foglio SICURISSIMO MASTER
- Condividi con l'email del service account (tipo: `81plus-agent@81plus-os.iam.gserviceaccount.com`)
- Permessi: **Editor**

---

## PASSO 7 — PROTEGGI IL FILE .ENV

Su Hostinger l'installer crea già il .htaccess.
Verifica che esista questo file: `public_html/.htaccess`
Deve contenere:
```apache
# Protect .env
<Files .env>
  Order allow,deny
  Deny from all
</Files>
```

Se non c'è, aggiungilo manualmente via File Manager.

**VERIFICA:** Prova ad aprire nel browser `https://tuodominio.com/.env`
Deve rispondere **403 Forbidden**. Se mostra il contenuto, il file non è protetto.

---

## PASSO 8 — TEST FINALE

Apri questi URL in sequenza per verificare che tutto funzioni:

```
1. https://tuodominio.com/api/health
   → deve rispondere: {"status":"ok","agents":150,"db":"connected"}

2. https://tuodominio.com/api/agent/orchestrator81/ping
   → deve rispondere: {"agent":"orchestrator81","status":"active"}
```

Se vedi errori: controlla `storage/logs/system.log` via File Manager.

---

## STRUTTURA FINALE SU HOSTINGER

```
public_html/                    ← tutto qui
├── .env                        ← PROTETTO (non accessibile da web)
├── .htaccess                   ← rewrite rules + protezioni
├── bootstrap.php               ← core del sistema
├── index.php                   ← pagina principale 81plus.net
├── agents/
│   ├── registry.agents.json    ← 150 agenti
│   ├── routing.matrix.yaml
│   ├── semantic_rules.yaml
│   ├── tool_bundles.yaml
│   └── memory/
├── api/                        ← endpoint REST
│   ├── health.php
│   └── agent/
├── cron/                       ← script cron (protetti da .htaccess)
│   ├── .htaccess               ← deny from all, allow from localhost
│   ├── daily_monitor.php
│   ├── morning_whatsapp.php
│   ├── post_social.php
│   ├── lead_scoring.php
│   ├── generate_posts.php
│   └── kb_update.php
├── storage/                    ← protetto da .htaccess
│   ├── logs/
│   ├── pdfs/
│   ├── uploads/
│   └── backups/
├── templates/
│   ├── documents/
│   ├── emails/
│   └── whatsapp/
└── docs/
    └── master/
        └── WELLFARE_PROGRAM_81PLUS_V5_PRO.html
```

---

## CREDENZIALI CHE NON DEVE MANCARE NEL .ENV

Aggiungi quelle Aruba manualmente dopo l'installer:

```bash
# FATTURAZIONE ELETTRONICA ARUBA (aggiungi a mano — mai tramite form)
ARUBA_USERNAME=tuousername@aruba.it
ARUBA_PASSWORD=tuapassword
ARUBA_API_KEY=tuaapikey
ARUBA_CF=TUOCODICEFISCALE
ARUBA_P_IVA=TUAPARTITAIVA
```

**Regola Aruba:** Se appare OTP o 2FA durante il test → STOP. Intervento umano.

---

## PROBLEMI COMUNI

| Problema | Causa | Soluzione |
|----------|-------|-----------|
| 500 Internal Server Error | PHP < 8.0 | hPanel → PHP Configuration → 8.1 |
| DB connection failed | Credenziali sbagliate | Verifica nome db completo (con prefisso) |
| curl_exec timeout | Firewall Hostinger | Contatta supporto: enable outbound HTTPS |
| Cron non esegue | Path sbagliato | Verifica path con: `echo $HOME` via SSH |
| .env visibile da web | .htaccess mancante | Aggiungi protezione .htaccess manualmente |
| API Claude 401 | API key scaduta | Rigenera su console.anthropic.com |

---

## SUPPORTO

Se blocchi su un passo specifico, scrivi a Mirco:
- WhatsApp: 3388771737
- Il sistema gira su 81plus.net — qualsiasi errore compare in `storage/logs/`

---

*Istruzioni generate: 2026-06-17 | Sistema 81+ OS v1.0.0*
