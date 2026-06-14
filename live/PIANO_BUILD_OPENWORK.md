# ARCHITETTURA CARTELLE E PIANO BUILD
## Open Work: ogni sito è un modulo indipendente e integrabile
## 13 giugno 2026

---

## PRINCIPIO OPEN WORK

Ogni sub-progetto vive nella sua cartella. Puoi tornare a modificare qualsiasi sito precedente senza rifare nulla. La struttura condivisa (API, DB, CSS, JS) è al root. Ogni sub-progetto importa i componenti condivisi.

Su Hostinger ogni sub-dominio (81plus.academy, 81plus.zone, ecc.) punta alla sua cartella dentro public_html.

---

## STRUTTURA MASTER (public_html su Hostinger = root)

```
public_html/                    ← 81plus.net (HUB1, sito principale)
├── index.html                  ← Home 81plus.net
├── audit.html                  ← Audit 30 normative
├── signup.html                 ← Registrazione SIC-ID
├── login.html                  ← Login
├── dashboard-utente.html       ← Dashboard Genesi
├── [altre pagine HUB1...]
├── api/                        ← API condivise (tutte le API PHP)
│   ├── auth.php
│   ├── pv_acquisto.php
│   ├── saf_acquisto.php
│   ├── shop.php
│   └── [78 API totali]
├── src/                        ← Moduli core PHP
│   ├── config.php
│   ├── db.php
│   ├── auth_lib.php
│   ├── enroll.php
│   └── [26 moduli]
├── sql/                        ← Schema e patch DB
│   ├── schema.sql
│   └── [25 patch]
├── data/                       ← JSON config, listini, cataloghi
├── assets/                     ← CSS, immagini condivise
├── uploads/                    ← Upload utente, loghi PIX
│   └── pix/                    ← 22 loghi SVG sub-progetti
├── .env                        ← Credenziali (mai nel repo)
├── .htaccess                   ← Routing Apache
│
├── 81plus.it/                  ← Sub: MEMBER81+ (symlink o alias)
│   └── index.html
├── 81plus.academy/             ← Sub: FORMAZIONE
│   └── index.html
├── 81plus.network/             ← Sub: NETWORKER81+
│   └── index.html
├── 81plus.club/                ← Sub: CLUB ELITE
│   └── index.html
├── 81plus.shop/                ← Sub: SHOP FISICO
│   └── index.html
├── 81plus.zone/                ← Sub: FRANCHISING
│   └── index.html
├── 81plus.digital/             ← Sub: DAPP WEB3
│   └── index.html
├── 81plus.exchange/            ← Sub: DEX
│   └── index.html
├── 81plus.world/               ← Sub: METAVERSO
│   └── index.html
├── 81plus.org/                 ← Sub: DAO
│   └── index.html
├── 81plus.store/               ← Sub: MARKETPLACE
│   └── index.html
├── 81plus.credit/              ← Sub: BANKING DUBAI
│   └── index.html
├── 81plus.bond/                ← Sub: ASSICURAZIONI
│   └── index.html
├── 81plus.place/               ← Sub: SEDE VIRTUALE
│   └── index.html
├── 81plus.space/               ← Sub: PIX81+ (alias di pix81.html)
│   └── index.html
├── 81plus.cloud/               ← Sub: IPFS
│   └── index.html
├── 81plus.cards/               ← Sub: CARD DIGITALI
│   └── index.html
├── 81plus.christmas/           ← Sub: XMAS81
│   └── index.html
└── _redirects/                 ← Htaccess per domini in redirect
```

---

## PIANO BUILD SEQUENZIALE

### STEP 1: HUB1 · 81plus.net (QUESTO STEP)
Stato: 95% costruito. Manca: FAQ, proofreading, contatore live, test end-to-end.

### STEP 2: 81plus.it · MEMBER81+
Stato: landing in hub2.html. Da completare con pagine dedicate.

### STEP 3: 81plus.network · NETWORKER81+
Stato: network81.html + dashboard-networker. Da completare.

### STEP 4: 81plus.academy · FORMAZIONE
Stato: academy.html base. Da completare con catalogo corsi.

### STEP 5: 81plus.zone · FRANCHISING
Stato: zone.html + 3 sotto-pagine. Da completare.

### STEP 6: 81plus.club · CLUB ELITE
Stato: club81.html base. Da completare.

### STEP 7: 81plus.shop · SHOP
Stato: shop.html + api/shop.php. Da completare con catalogo reale.

### STEP 8-19: HUB3 sub-progetti (uno alla volta)
81plus.digital, exchange, world, org, store, credit, bond, place, space (PIX81 già fatto), cloud, cards, christmas.

Ogni step produce un sito completo e deployabile.
