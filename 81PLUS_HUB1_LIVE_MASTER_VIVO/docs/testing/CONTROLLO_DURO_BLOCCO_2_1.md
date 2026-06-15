# CONTROLLO DURO BLOCCO 2.1 — HUB1 CORE VALIDATION
## 81plus.net — Stato Validazione Tecnica e Semantica

**Data esecuzione:** 2026-06-15  
**Branch:** claude/create-claude-md-docs-JWjKP  
**Commit base:** 723438b  
**Validato da:** Nicolas (CONTROLLO DURO automatico + fix applicati)

---

## STATO GLOBALE: DA VALIDARE (test manuali in attesa)

| Categoria | Stato |
|-----------|-------|
| Registrazione | CORRETTO (fix applicato) |
| Login | CORRETTO (fix applicato) |
| SIC-ID | PASS |
| Bind Wallet Web3 | CORRETTO (fix applicato) |
| Dashboard | DA VALIDARE |
| PV / PV+ | PASS (idempotenza verificata) |
| BASIC+ | DA VALIDARE |
| GENESYS81+ | CORRETTO (fix applicato) |
| Semantic Guard | PASS (parole vietate non trovate in output) |

---

## CHECKLIST 25 PUNTI — PASS / FAIL / CORRETTO

### A. REGISTRAZIONE

| # | Test | Stato | Note |
|---|------|-------|------|
| 1 | Campo `privacy` validato server-side (non solo client) | PASS | `if (empty($_POST['privacy'])) json_err(...)` in `api/signup.php` |
| 2 | Password minimo 8 caratteri validata server-side | PASS | `if (strlen($pass) < 8) json_err(...)` |
| 3 | Email validata con `FILTER_VALIDATE_EMAIL` | PASS | Presente in `api/signup.php` |
| 4 | Rate limiting anti-abuse per IP | CORRETTO | Aggiunto: `signup_attempts` table + check 5/ora |
| 5 | Email di benvenuto da `welcome@81plus.net` | PASS | `send_welcome_email()` in try/catch non bloccante |

### B. LOGIN

| # | Test | Stato | Note |
|---|------|-------|------|
| 6 | Login supporta email O username | CORRETTO | Query `WHERE (u.email = ? OR u.username = ?)` |
| 7 | Colonne wallet corrette (`pv_balance`, `pvplus_balance`) | CORRETTO | Fix alias `AS pv` / `AS pvplus` |
| 8 | Check `is_active = 0` blocca accesso | CORRETTO | `if (!(int)$user['is_active']) json_err(...)` |
| 9 | Check `status != ACTIVE` blocca accesso | CORRETTO | `if ($user['status'] !== 'ACTIVE') json_err(...)` |

### C. SIC-ID

| # | Test | Stato | Note |
|---|------|-------|------|
| 10 | Formato `SIC + yymmdd + base36(user_id) + hmac_suffix` | PASS | Implementato in `api/signup.php` |
| 11 | SIC-ID immutabile dopo creazione | PASS | Nessun endpoint di modifica SIC-ID |
| 12 | SIC-ID NON contiene dati personali | PASS | Deriva da user_id + hash email |
| 13 | SIC-ID MAI visualizzato come CTA cliccabile | PASS | Solo display in `<code>` tag |

### D. BIND WALLET WEB3

| # | Test | Stato | Note |
|---|------|-------|------|
| 14 | Nonce generato server-side (`/api/nonce-wallet.php`) | CORRETTO | Nuovo endpoint, nonce in sessione 5 min |
| 15 | Nonce usato una sola volta (cancellato dopo uso) | CORRETTO | `unset($_SESSION['wallet_nonce'])` dopo check |
| 16 | Nonce scade dopo 300 secondi | CORRETTO | Check `(time() - $session_nonce_ts) > 300` |
| 17 | Wallet address UNIQUE (un wallet = un account) | CORRETTO | `uidx_wallet_address` in delta SQL + check pre-UPDATE |
| 18 | Bind loggato in `wallet_bind_log` | CORRETTO | Log SUCCESS e DUPLICATE |
| 19 | Copia pagina: no promesse economiche legate al wallet | CORRETTO | "Funzioni Web3 utility del sistema 81+" |

### E. PV / PV+

| # | Test | Stato | Note |
|---|------|-------|------|
| 20 | `credit_pvplus` idempotente via `pvplus_claims` UNIQUE KEY | PASS | Implementato in `core81/wallet_service.php` |
| 21 | `debit_pv` atomico con check `pv_balance >= amount` | PASS | `UPDATE WHERE pv_balance >= ?` + ROW_COUNT check |
| 22 | PV+ MAI accreditati via JS client-side | PASS | Tutte le operazioni in PHP server-side |

### F. GENESYS81+

| # | Test | Stato | Note |
|---|------|-------|------|
| 23 | Form include campi: telefono, telegram, social, followers, community | CORRETTO | Aggiunti a `genesys81.php` e `api/genesys-apply.php` |
| 24 | Status ENUM allineato: usa `RIFIUTATA` non `REJECTED` | CORRETTO | Fix in `api/genesys-apply.php` |
| 25 | Promo 1000 PV+ idempotente via `promo_pvplus_sent` flag | PASS | Check presente in `api/genesys-apply.php` |

---

## PAROLE VIETATE TROVATE NEI FILE PHP

Scan eseguito su `public_html/**/*.php` escludendo `semantic_guard.php`:

**Risultato:** NESSUNA parola vietata usata in contesto positivo/affermativo.

Le occorrenze di "rendimento" trovate sono tutte in negazione esplicita:
- "non rappresentano rendimento economico" — disclaimer legale corretto
- "non sono rendimento" — footer disclaimer corretto
- "Non garantisce rendimento economico" — `pix81.php`, `genesys81.php` — corretto

Nessuna occorrenza di: `investimento`, `investire`, `rendita`, `APY`, `staking garantito`,
`CEX`, `passive income garantita`, `SICONET`, `SAFE5.0`, `GreenGrove81`, `Groove81+`,
`zero multe`, `rischio zero`, `garantiamo al 100%`, `soldi facili`, `diventa ricco`,
`schema piramidale`, `schema Ponzi`, `BAYC`, `Bored Ape`.

---

## FILE MODIFICATI IN CONTROLLO DURO 2.1

| File | Tipo modifica |
|------|--------------|
| `public_html/api/login.php` | Fix: username support, wallet columns, status check |
| `public_html/api/connect-wallet.php` | Fix: nonce check, UNIQUE check, bind log |
| `public_html/api/nonce-wallet.php` | NUOVO: endpoint generazione nonce |
| `public_html/api/signup.php` | Fix: rate limiting IP-based |
| `public_html/api/genesys-apply.php` | Fix: campi mancanti, RIFIUTATA status |
| `public_html/genesys81.php` | Fix: form con campi aggiuntivi |
| `sql/MYSQL_DELTA_BLOCCO2_HUB1_CORE.sql` | Fix: wallet_bind_log, signup_attempts, uidx_wallet_address, colonne genesys |

## TABELLE MODIFICATE / AGGIUNTE

| Tabella | Modifica |
|---------|---------|
| `users` | + `UNIQUE INDEX uidx_wallet_address` |
| `genesys_applications` | + `ruolo_richiesto`, `telefono`, `telegram_username`, `social_url`, `followers_range`, `community_type`, `interest`, `promo_pvplus_sent`, `profile_pvplus_sent` |
| `wallet_bind_log` | NUOVA tabella |
| `signup_attempts` | NUOVA tabella |

---

## RISCHI RESIDUI (DA RISOLVERE IN BLOCCO 3)

| Rischio | Priorita | Stato |
|---------|----------|-------|
| Verifica crittografica firma wallet (ecrecover) | ALTA | TODO BLOCCO 3 — placeholder strutturato presente |
| KYC anti-money-laundering per PV sopra soglia | MEDIA | Non richiesto in BLOCCO 2 |
| 2FA per login | MEDIA | Non richiesto in BLOCCO 2 |
| Cleanup automatico `signup_attempts` > 24h | BASSA | Cron job in BLOCCO 3 |
| Email verification flow | MEDIA | Colonna `email_verified_at` presente, flow TODO |

---

## TEST MANUALI DA ESEGUIRE

1. **Registrazione**: Registra utente con email nuova. Verifica SIC-ID creato. Verifica 100 PV+ accreditati. Verifica welcome email da `welcome@81plus.net`.
2. **Rate limit**: Registra 6 volte dallo stesso IP in meno di 1 ora. Il 6° tentativo deve restituire HTTP 429.
3. **Login email**: Accedi con email + password. Verifica redirect a `/dashboard.php`.
4. **Login username**: Accedi con username (se impostato) + password. Verifica funzionamento.
5. **Login account sospeso**: Imposta `status = 'SUSPENDED'` su un utente. Verifica blocco accesso con messaggio corretto.
6. **Wallet nonce**: Chiama `GET /api/nonce-wallet.php`. Verifica risposta con `nonce` e `message`.
7. **Wallet bind**: Invia POST a `/api/connect-wallet.php` con nonce valido + firma formato corretto. Verifica UPDATE in `users.wallet_address`.
8. **Wallet bind duplicato**: Prova a collegare stesso wallet_address a secondo account. Verifica errore bloccante.
9. **Nonce scaduto**: Genera nonce, aspetta 6 minuti, tenta bind. Verifica errore "Nonce scaduto".
10. **GENESYS form**: Compila candidatura con tutti i campi nuovi. Verifica INSERT in `genesys_applications` con tutti i campi.
11. **GENESYS promo PV+**: Candidatura da utente loggato con GENESYS_PROMO_ACTIVE=true. Verifica 1000 PV+ accreditati una sola volta (idempotenza).
12. **Semantic guard**: Invia form con testo contenente "investimento" o "rendimento garantito". Verifica blocco da `semantic_guard.php`.

---

## PROSSIMA AZIONE

**STATO CORRENTE: DA VALIDARE — Test manuali in attesa di esecuzione su ambiente staging.**

Quando tutti i 12 test manuali passano:
- STATO → VALIDATO
- Procedere a BLOCCO 3: Pipeline3D81+, TerritoryMap81+, DOC81+ avanzato
- NON procedere a BLOCCO 3 finche anche un solo test critico fallisce
