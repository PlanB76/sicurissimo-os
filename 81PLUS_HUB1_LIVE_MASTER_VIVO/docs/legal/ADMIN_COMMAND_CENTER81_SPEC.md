# ADMIN COMMAND CENTER81+ SPEC

## ACCESSO

Solo ADMIN81+.

## SEZIONI

### Utenti
- Lista utenti con filtri
- Scheda utente dettagliata
- Azioni: attivare, sospendere, disattivare, cambiare ruolo/GENESYS, bloccare

### Wallet e PV
- Saldo PV e PV+ per utente
- Storico transazioni
- Correzioni manuali (con log obbligatorio)

### Membership e Ordini
- Lista membership attive
- Ordini PayGate81+
- Rimborsi (se applicabile)

### SCOUT81+ Globale
- Database prospect globale
- Regole scoring (modificabili)
- Provider API (abilitazione/disabilitazione)
- Pack PLP generabili
- Riassegnazione lead

### Pipeline e Territory
- Pipeline3D81+ globale
- TerritoryMap81+ globale
- Assegnazione aree

### Contenuti
- Academy, DOC81+, Scadenziario
- News, Newsletter, Blog, Shop

### Log e Sicurezza
- Admin action log (ogni azione admin)
- Errori sistema
- Log accessi
- Anomalie wallet

## ADMIN ACTION LOG

Ogni azione admin registra:
- admin_id
- target_user_id (se applicabile)
- azione
- parametri
- note
- timestamp
- ip_address

## AZIONI ADMIN

attivare utente, sospendere utente, disattivare utente,
cambiare ruolo, cambiare genesys_level, attivare/disattivare Club,
attivare/disattivare Franchising, bloccare wallet operativo,
bloccare referral, bloccare PLP, bloccare SCOUT81+, bloccare dashboard,
scrivere nota interna, ripristinare utente,
modificare regole SCOUT81+, abilitare/disabilitare provider SCOUT81+,
abilitare/disabilitare pack PLP81+, riassegnare lead,
revocare lead, auditare attività Networker.
