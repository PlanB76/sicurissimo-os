# SFERA81+ V5 — Guida all'installazione su Hostinger

## Struttura cartelle

```
/public_html/admin/sfera81/
├── .htaccess                   ← sicurezza + CORS
├── README_INSTALLAZIONE_SFERA81.md
├── database/
│   └── sfera81_mysql.sql       ← schema + seed data
├── config/
│   └── config.example.php      ← copia in config.php e completa
├── api/
│   ├── _bootstrap.php          ← incluso da tutti gli endpoint
│   ├── sfera/
│   │   ├── daily/access.php
│   │   ├── promos/active.php, create.php, activate.php, deactivate.php
│   │   ├── missions/list.php, complete.php
│   │   ├── escalation/state.php
│   │   ├── lifewheel/state.php
│   │   ├── risk-radar/state.php
│   │   ├── profile/get.php, update.php
│   │   └── rewards/log.php
│   └── leadgen/
│       └── trigger.php
├── iframe/
│   ├── escalation81/index.html ← piramide 3D Canvas
│   └── lifewheel81/index.html  ← ruota 3D Canvas
├── dashboard/
│   └── sfera_widget.php        ← widget PHP da includere
└── admin/
    └── test_api.html           ← pannello test endpoint
```

---

## 1. Database

Accedi a phpMyAdmin su Hostinger.

1. Crea un database dedicato (es. `sfera81db`)
2. Crea un utente con tutti i permessi su quel database
3. Importa il file `database/sfera81_mysql.sql`

---

## 2. Configurazione PHP

Copia e rinomina:

```bash
cp config/config.example.php config/config.php
```

Edita `config/config.php` con i tuoi dati reali:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'sfera81db');       // il tuo DB
define('DB_USER', 'sfera81user');     // il tuo utente DB
define('DB_PASS', 'PASSWORD_FORTE');  // la tua password
define('ADMIN_SECRET', 'CHIAVE_ADMIN_LUNGA_E_CASUALE');
```

**Non committare mai config.php nel repository.**

---

## 3. Upload su Hostinger

Carica tutta la cartella `sfera81/` via FTP o File Manager in:

```
/public_html/admin/sfera81/
```

Verifica che `config.php` sia presente (non viene caricato dal repo).

---

## 4. Test installazione

Apri in browser:

```
https://81plus.net/admin/sfera81/admin/test_api.html
```

- Base URL: `/admin/sfera81/api`
- user_id: `1` (demo precaricato dal seed SQL)
- Clicca **GET profilo** — deve restituire i dati del demo user
- Clicca **Daily Access** — deve restituire `{ok:true, reward:1, ...}`

Se ricevi errori 500, verifica:
- `config.php` presente e dati DB corretti
- Estensione `pdo_mysql` abilitata (Hostinger: attiva di default)
- PHP 8.0+ (Hostinger: selezionabile dal pannello)

---

## 5. Iframe — parametri URL

### ESCALATION81+

```
/admin/sfera81/iframe/escalation81/index.html?level=2
```

Parametro `level` (1-5): livello corrente dell'utente.

### LIFEWHEEL81+

```
/admin/sfera81/iframe/lifewheel81/index.html?status=ELITE&scores=7,5,4,8,3,6,5,4&applicable=1,1,1,1,1,1,0,1
```

Parametri:
- `status`: MEMBER / NETWORKERS / ELITE / FRANCHISER / CLUB
- `scores`: 8 interi separati da virgola (1-10, ordine aree DB)
- `applicable`: 8 flag 0/1 (per HACCP e privacy non applicabili)

---

## 6. Widget dashboard

Nel tuo file dashboard PHP (dopo il require di _bootstrap.php):

```php
require_once __DIR__ . '/path/to/sfera81/dashboard/sfera_widget.php';
sfera81_widget($user_id, '/admin/sfera81');
```

---

## 7. Sicurezza

- **`admin/test_api.html`**: blocca l'accesso in produzione via `.htaccess` o rimuovilo
- **ADMIN_SECRET**: deve essere una stringa casuale lunga (min 32 caratteri)
- **`config.php`**: protetto da `.htaccess`, non esposto via web
- **PDO prepared statements**: tutti gli endpoint usano query parametrizzate
- **X-Admin-Secret header**: richiesto da create/activate/deactivate promo

---

## 8. Cap giornalieri per status

| Status     | Cap PV+/giorno | Cap LIFEWHEEL |
|------------|---------------|---------------|
| MEMBER     | 100           | 6/10          |
| NETWORKERS | 250           | 7/10          |
| ELITE      | 400           | 8/10          |
| FRANCHISER | 600           | 9/10          |
| CLUB       | 1000          | 10/10         |

---

## 9. BOOSTER81+

Il BOOSTER81+ è una promo mensile admin-only:
- Max 1 promo attiva per mese
- Sostituisce il reward giornaliero da 1 PV+ a 50 PV+
- Anti-duplicato: un solo accredito BOOSTER per utente per giorno
- Creazione via API (con X-Admin-Secret) o via test_api.html

---

## Note finali

- Semantic Guard: non usare mai "investimento", "rendimento", "ROI", "guadagno garantito"
- I PV+ (SICURISSIMO POINT81+) sono utility interne, non valuta
- Per supporto: contatta Mirco via WhatsApp 3388771737
