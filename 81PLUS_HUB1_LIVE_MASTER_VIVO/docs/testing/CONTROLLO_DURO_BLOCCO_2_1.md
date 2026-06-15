# CONTROLLO DURO BLOCCO 2.1 — HUB1 CORE VALIDATION
## Checklist Manuale 25 Punti — 81plus.net

**Data:** 2026-06-15  
**Branch:** claude/create-claude-md-docs-JWjKP  
**Commit:** cd82e8f → aggiornato in questo ciclo  
**Responsabile test:** Da eseguire su ambiente staging  

---

> REGOLA BLOCCANTE: Se anche un solo test critico risulta FAIL, STATO = BLOCCATO.
> Non procedere con BLOCCO 3 finche tutti i test non sono PASS.

---

## CHECKLIST PASS / FAIL

| # | Test | Stato | File / Tabella | Note operative |
|---|------|-------|----------------|----------------|
| 1 | **Registrazione standard** — email, nome, password, privacy checkbox | DA TESTARE | `api/signup.php`, `users`, `wallets` | Verifica: utente creato, wallet creato, ruolo=MEMBER81 |
| 2 | **Registrazione da referral** — `?ref=SIC...` in URL salva referrer_sic_id | DA TESTARE | `api/signup.php`, `referral_events` | Verifica: referral_ref nella tabella users, evento referral registrato |
| 3 | **Registrazione da GENESYS** — `?genesys_ref=1` assegna 1000 PV+ e status GENESYS_MEMBER | DA TESTARE | `api/signup.php`, `wallets`, `pvplus_claims` | Verifica: pvplus_balance = 1100 (100 welcome + 1000 GENESYS), idempotenza |
| 4 | **Login email** — accesso con email + password validi | DA TESTARE | `api/login.php`, sessione PHP | Verifica: redirect a dashboard, sessione con user_id/sic_id/ruolo |
| 5 | **Login username** — accesso con username + password validi | DA TESTARE | `api/login.php` | Query `WHERE (email = ? OR username = ?)` — testare con username impostato |
| 6 | **Logout** — distrugge sessione, rimuove cookie, redirect a /login.php | DA TESTARE | `logout.php` | Verifica: `$_SESSION = []`, cookie rimosso, redirect corretto |
| 7 | **Accesso dashboard non loggato** — redirect a login | DA TESTARE | `dashboard.php`, `includes/auth.php` | Verifica: HTTP 302 → /login.php?redir=/dashboard.php |
| 8 | **SIC-ID generato** — formato SIC+yymmdd+base36(id)+hmac_suffix, univoco | DA TESTARE | `api/signup.php`, colonna `users.sic_id` | Verifica: SIC260615... senza dati personali leggibili |
| 9 | **Referral link generato** — `https://81plus.net/signup.php?ref={SIC-ID}` visibile in dashboard | DA TESTARE | `dashboard.php`, `core81/referral_service.php` | Verifica: link visibile, bottone copia funzionante |
| 10 | **Wallet bind successo** — nonce generato, firma corretta, wallet salvato | DA TESTARE | `api/nonce-wallet.php`, `api/connect-wallet.php`, `wallet_bind_log` | Verifica: users.wallet_address aggiornato, log SUCCESS |
| 11 | **Wallet bind firma errata** — wallet NON salvato, errore chiaro | DA TESTARE | `api/connect-wallet.php` | Verifica: nessun UPDATE su users, errore "Formato firma non valido" |
| 12 | **Wallet gia collegato** — stesso indirizzo rifiutato per secondo account | DA TESTARE | `api/connect-wallet.php`, `uidx_wallet_address` | Verifica: errore "gia collegato a un altro account", log DUPLICATE |
| 13 | **PV+ welcome standard** — 100 PV+ accreditati alla prima registrazione | DA TESTARE | `wallets.pvplus_balance`, `pvplus_claims` | Verifica: saldo = 100 PV+, codice_missione = WELCOME_BONUS |
| 14 | **PV+ welcome GENESYS** — 1000 PV+ aggiuntivi accreditati da flusso GENESYS | DA TESTARE | `wallets.pvplus_balance`, `pvplus_claims` | Verifica: saldo = 1100 PV+ (100+1000), GENESYS_SIGNUP_PROMO |
| 15 | **Profilo completo GENESYS** — 1000 PV+ aggiuntivi quando profilo = 100% | DA TESTARE | `pvplus_claims`, `genesys_applications.profile_pvplus_sent` | Verifica: profile_pvplus_sent = 1 dopo completamento, idempotenza |
| 16 | **BASIC+ saldo sufficiente** — 29,90 PV scalati, membership attivata, 100 PV+ assegnati | DA TESTARE | `api/membership-activate.php`, `wallets`, `user_memberships` | Verifica: pv_balance -29.90, pvplus +100, membership_tier = BASIC+ |
| 17 | **BASIC+ saldo insufficiente** — blocco attivazione, CTA PayGate81+ mostrata | DA TESTARE | `api/membership-activate.php`, `paygate81.php` | Verifica: json_err con messaggio chiaro, redirect a paygate |
| 18 | **Doppio bonus impedito** — welcome PV+ e GENESYS PV+ non duplicati | DA TESTARE | `pvplus_claims` UNIQUE KEY | Verifica: seconda chiamata credit_pvplus con stesso codice = skip senza errore |
| 19 | **Dashboard MEMBER81** — vede solo moduli base (SIC-ID, Wallet, Referral, Audit, PayGate) | DA TESTARE | `dashboard.php`, `core81/dashboard_modules.php` | SCOUT81+, PLP, NETWORK, Pipeline devono essere preview/locked |
| 20 | **Dashboard NETWORKER81** — vede moduli base + SCOUT81+ preview + NETWORK81+ | DA TESTARE | `dashboard.php`, `api/dashboard-data.php` | Verifica: moduli NETWORKER visibili, ELITE moduli locked |
| 21 | **Dashboard ELITE81** — vede tutto NETWORKER + Club81+, Franchising, TerritoryMap | DA TESTARE | `dashboard.php`, `api/dashboard-data.php` | Verifica: tutti i moduli ELITE sbloccati |
| 22 | **Dashboard ADMIN81** — vede tutto + Admin Command Center | DA TESTARE | `dashboard.php` | Verifica: link /admin.php visibile solo per ADMIN81 |
| 23 | **Semantic guard** — input con parola vietata blocca la richiesta | DA TESTARE | `includes/semantic_guard.php` | Test: inviare "investimento garantito" in form GENESYS — verifica blocco |
| 24 | **Nessuna parola vietata nel codice** — scan completo file PHP | PASS | Tutti i PHP in `public_html/` | Scan eseguito 2026-06-15: rendimento solo in negazione, zero banned affirmative |
| 25 | **Nessun dato sensibile nel codice** — no password, no chiavi API, no token in file PHP | PASS | Tutti i file `.php` | Verificato: uso esclusivo di `$_ENV[...]`, nessuna credenziale hardcoded |

---

## RISULTATI CORRENTI

| Categoria | Test | PASS | FAIL | DA TESTARE |
|-----------|------|------|------|-----------|
| Registrazione | 1-3 | — | — | 3 |
| Login & Sessione | 4-7 | — | — | 4 |
| SIC-ID & Referral | 8-9 | — | — | 2 |
| Wallet Web3 | 10-12 | — | — | 3 |
| PV / PV+ | 13-15 | — | — | 3 |
| BASIC+ Membership | 16-18 | — | — | 3 |
| Dashboard Ruoli | 19-22 | — | — | 4 |
| Semantic Guard | 23 | — | — | 1 |
| Codice Sicuro | 24-25 | **2** | — | — |

**TOTALE: 2 PASS / 23 DA TESTARE / 0 FAIL**

---

## STATO GLOBALE: DA VALIDARE

Il codice e stato verificato staticamente e corretto in tutti i punti critici.
I 23 test manuali richiedono esecuzione su ambiente staging con database MySQL attivo.

---

## FIX APPLICATI PRIMA DEL TEST MANUALE

| # | Problema | Fix | File |
|---|---------|-----|------|
| A | Login solo email, non username | Campo `login` accetta email O username | `api/login.php`, `login.php` |
| B | `pv` / `pvplus` colonne errate | Fix alias `pv_balance AS pv` | `api/login.php` |
| C | Account sospeso poteva accedere | Check `is_active` e `status != ACTIVE` | `api/login.php` |
| D | Logout non puliva cookie | `$_SESSION = []` + setcookie expire | `logout.php` |
| E | Dashboard mostrava codice errore raw | Mappa codice → messaggio leggibile | `dashboard.php` |
| F | Connect wallet senza nonce | Endpoint nonce + verifica sessione 5min | `api/nonce-wallet.php`, `api/connect-wallet.php` |
| G | Wallet address duplicabile | UNIQUE INDEX + check pre-UPDATE | `sql/MYSQL_DELTA_BLOCCO2_HUB1_CORE.sql` |
| H | Wallet bind non loggato | Tabella `wallet_bind_log` | SQL delta |
| I | Signup senza rate limiting | `signup_attempts` table, max 5/ora/IP | `api/signup.php`, SQL delta |
| L | GENESYS form mancante di campi | telefono, telegram, social, followers, community, interest | `genesys81.php`, `api/genesys-apply.php` |
| M | Status REJECTED vs RIFIUTATA | Fix a RIFIUTATA (allineato al DB) | `api/genesys-apply.php` |
| N | Copia signup errata | "alla registrazione" non "alla conferma email" | `signup.php` |

---

## PROSSIMA AZIONE

1. Configurare ambiente staging (PHP 8.1+, MySQL, variabili `.env`)
2. Importare `sql/MYSQL1_INSTALL_81PLUS_HUB1.sql` poi `sql/MYSQL_DELTA_BLOCCO2_HUB1_CORE.sql`
3. Eseguire i 23 test manuali uno per uno
4. Per ogni test: segnare PASS o FAIL con nota
5. Se tutti PASS → STATO = VALIDATO → autorizzazione BLOCCO 3
6. Se anche un solo FAIL critico → STATO = BLOCCATO → correggere e ritestare

**NON dichiarare il sito pronto finche tutti i 25 test non sono PASS.**
