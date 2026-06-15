# SQL — Ordine di esecuzione

Esegui i file nell'ordine indicato su MySQL 8.0+ (database: u173050672_81plusglobal, PHP 8.1+).

## Sequenza installazione base

1. `MYSQL1_INSTALL_81PLUS_HUB1.sql` — Schema principale + seed data (eseguire PRIMA di tutti)
2. `MYSQL_DELTA_DASHBOARD_ACADEMY_DOC_SCADENZIARIO.sql`
3. `MYSQL_DELTA_PIPELINE_TERRITORY_ADMIN_3D.sql`
4. `MYSQL_DELTA_NETWORKER_SCOUT_PLP_PIANI.sql`
5. `MYSQL_DELTA_SCOUT81_PROSPECTS.sql`
6. `MYSQL_DELTA_PVPLUS_BOOSTER.sql`

## Note critiche

- Esegui su un database VUOTO o in staging prima del deploy in produzione
- Il file 1 crea il database da zero — NON eseguire su un db con dati esistenti senza backup
- I PIX81+ slot (1000) vengono pre-inseriti dal file 1
- Le missioni PV+ (25) vengono pre-inserite dal file 6
- I pack PLP (10) vengono pre-inseriti dal file 5

## Credenziali (da variabili d'ambiente, NON nel codice)

```
DB_HOST=127.0.0.1
DB_NAME=u173050672_81plusglobal
DB_USER=...
DB_PASS=...
```

## Rotazione obbligatoria prima del go-live

- Groq API key
- PayPal Plan ID
- BSC wallet privato
- Hostinger API key
- SIC_SECRET (per HMAC-SHA256 SIC-ID)
