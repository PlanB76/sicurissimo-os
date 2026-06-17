# GUIDA INSTALLAZIONE DATABASE 81+ SU HOSTINGER
## 1 click — Tempo stimato: 5 minuti

---

## COSA CONTIENE QUESTA CARTELLA

| File | Descrizione |
|------|-------------|
| `MYSQL1_INSTALL_81PLUS_HUB1_COMPLETE.sql` | Schema completo 78 tabelle + 4179 lead (file unico) — v4.0.0 |
| `install_db.php` | Installer web protetto — si auto-cancella dopo il successo |
| `.env.example` | Template configurazione credenziali |
| `ISTRUZIONI_INSTALLAZIONE_DB.md` | Questo file |

---

## MATRICE SIC-ID (NON CAMBIARE MAI)

| Tier | Formato | Range | Note |
|------|---------|-------|------|
| Owner-Founder | `SIC-ID-OWNER-00000001` | 1 solo | Mirco |
| GENESYS co-founder | `SIC-ID-GEN-00000002` → `SIC-ID-GEN-00000100` | 99 slot | Prime 99 |
| Utenti normali | `SIC-ID-81-00000101` → salire | illimitati | Tutti gli altri |
| Lead (non registrati) | `SIC-ID-X-00000001` → `SIC-ID-X-00004179` | 4179+ | CSV Giugno 2026 |

---

## STEP 1 — CREA IL DATABASE SU HOSTINGER

1. Vai su **hPanel** → **Database** → **Database MySQL**
2. Clicca **Crea nuovo database**
3. Nome database: `81plusglobal` (diventerà `u173050672_81plusglobal`)
4. Crea utente: `81plus` con password sicura
5. Assegna l'utente al database con **tutti i privilegi**
6. Copia i dati:
   - **Host:** `localhost`
   - **Nome DB:** (quello che Hostinger ha generato, es. `u173050672_81plusglobal`)
   - **Utente:** (es. `u173050672_81plus`)
   - **Password:** quella che hai scelto

---

## STEP 2 — CARICA I FILE SU HOSTINGER

Tramite **File Manager** di hPanel o FTP (FileZilla):

```
public_html/
    install_db.php          ← carica qui
    
(cartella una livello sopra public_html, di solito home/u173050672/)
    sql/
        MYSQL1_INSTALL_81PLUS_HUB1_COMPLETE.sql    ← carica qui
    .env                            ← crea/modifica qui
```

> **IMPORTANTE:** Il file `MYSQL1_INSTALL_81PLUS_HUB1_COMPLETE.sql` va nella cartella `sql/`
> che si trova **fuori** da `public_html/`, non dentro.
> Questo lo protegge da accesso web diretto.

---

## STEP 3 — CONFIGURA IL FILE .env

1. Nella root del progetto (una cartella sopra `public_html/`) crea il file `.env`
2. Copia il contenuto di `.env.example`
3. Compila **obbligatoriamente**:
   ```
   DB_HOST=localhost
   DB_NAME=u173050672_81plusglobal    ← nome esatto da Hostinger
   DB_USER=u173050672_81plus          ← utente esatto da Hostinger
   DB_PASS=la_tua_password
   INSTALLER_KEY=scegli_una_chiave_segreta_lunga
   ```
4. Salva come `.env` (senza .example)

---

## STEP 4 — ESEGUI L'INSTALLAZIONE (1 CLICK)

1. Apri nel browser:
   ```
   https://tuodominio.it/install_db.php
   ```
2. Inserisci la `INSTALLER_KEY` che hai scelto nel `.env`
3. Clicca **"Installa Database Globale 81+"**
4. Attendi il completamento (30-60 secondi)
5. Leggi il log — cerca la riga verde finale:
   ```
   ✓ --- INSTALLAZIONE COMPLETATA CON SUCCESSO ---
   ✓ File install_db.php eliminato automaticamente dal server.
   ```

**Il file si cancella da solo.** Non devi fare nulla.

---

## STEP 5 — VERIFICA

Vai su **hPanel → phpMyAdmin** e controlla:

- Tabelle presenti: **almeno 78**
- Tabella `leads`: **4179 righe**
- Tabella `pix81_slots`: **1000 righe**
- Tabella `system_config`: cerca `sic_id_owner` → deve esserci `SIC-ID-OWNER-00000001`

---

## COSA È INSTALLATO

### Struttura DB (78 tabelle)
- **Utenti:** users, user_profiles, wallet_bind_log, sessioni, signup_attempts
- **Wallet:** wallets, pv_transactions, pvplus_claims, pvplus_missions, pvplus_boosters
- **Pagamenti:** pagamenti_revolut, pagamenti_paypal, pagamenti_stripe, pagamenti_crypto, pagamenti_bonifico, paygate_orders, abbonamenti, shop_ordini
- **Membership:** membership_plans, memberships, genesys_applications, pix81_slots, lock81_contracts
- **Lead:** leads (4179 importati), lead_interactions
- **MLM:** mlm_tree, commissioni_mlm, referral_links, referral_conversions, network_passes, compensi_maturati, regola_giorno20_check
- **Prodotti:** catalogo_prodotti, catalogo_servizi, ordini
- **Compliance:** audit_sessions, company_compliance_asr2025, course_requirements_ledger, preventivi, doc81_documents, scadenziario
- **Academy:** academy_access, academy_corsi, academy_completamenti, academy_iscrizioni
- **Scout81+:** scout81_prospects, scout81_assignments, scout81_saved_filters, scout81_providers, plp_packs_catalog
- **Web3:** wallets (x81/SAF), blockchain_transactions, nft_assets, x81_config, x81_airdrop
- **Comunicazione:** notifiche, eventi, email_log, sms_log, knowledge_base
- **Agenti AI:** ai_agent_runs, ai_agent_memory, ai_conversation_log
- **Admin:** dashboard_modules, admin_actions, audit_log, developer_api, system_config

### Dati pre-installati
- `system_config`: 24 righe (inclusa matrice SIC-ID completa)
- `pvplus_missions`: 31 missioni PV+ operative
- `membership_plans`: BASIC+ / PRO+ / ELITE+
- `plp_packs_catalog`: 10 pacchetti PLP
- `scout81_providers`: Outscraper + DB Interno + Futuro
- `dashboard_modules`: 22 moduli dashboard
- `pix81_slots`: 1000 slot (200 FOUNDER + 800 PUBBLICO)
- `leads`: 4179 lead con SIC-ID-X-00000001→00004179

### 4 Trigger automatici (si attivano alla registrazione utente)
- `trg_create_wallet` → crea wallet al signup
- `trg_create_referral_link` → genera link referral personale
- `trg_welcome_pvplus` → accredita 100 PV+ di benvenuto (idempotente)
- `trg_log_payment_event` → logga ogni pagamento COMPLETED

---

## PROBLEMI COMUNI

| Problema | Causa | Soluzione |
|----------|-------|-----------|
| "File non trovato: MYSQL1_INSTALL_81PLUS_HUB1_COMPLETE.sql" | SQL non nella cartella giusta | Mettilo in `../sql/` rispetto a public_html |
| "Connessione al database fallita" | Credenziali .env errate | Verifica DB_NAME, DB_USER, DB_PASS in hPanel |
| "Chiave non valida" | INSTALLER_KEY diversa | Copia esatta da .env, senza spazi |
| Timeout dopo 30 secondi | Importa lead lenta | Normale su shared hosting — aspetta o aumenta max_execution_time |
| Installer non si cancella | Permessi file | Cancellalo manualmente da File Manager dopo il successo |

---

## SICUREZZA POST-INSTALLAZIONE

- [ ] Verificare che `install_db.php` non esista più in `public_html/`
- [ ] Aggiungere in `.htaccess` la protezione della cartella `sql/`:
  ```apache
  <IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteRule ^sql/ - [F,L]
  </IfModule>
  ```
- [ ] Verificare che `.env` non sia accessibile via web (Hostinger lo blocca di default)
- [ ] Aggiungere il resto delle API key nel `.env` una alla volta

---

*Generato da 81+ OS — Nicolas AI | 17 giugno 2026*
