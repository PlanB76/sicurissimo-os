# SCOUT81+ PROVIDER API SPEC

## PROVIDER SUPPORTATI

### Outscraper (principale)
- Endpoint: configurabile in admin
- Dati: ragione sociale, ATECO, indirizzo, dipendenti stimati
- Autorizzazione: API key in .env (mai in codice client)
- Rate limit: gestito da scout81_service.php

### Provider interno (database SICURISSIMO81+ dal 2003)
- Fonte: storico clienti anonimizzato
- Priorità: massima
- Flag: lead_storico = true

### Provider futuri
- Configurabili da Admin Command Center
- Attivazione richiede: accordo legale, DPA, test, approvazione direzione

## CONFIGURAZIONE ADMIN

Admin può:
- Abilitare/disabilitare provider
- Impostare API key (salvata in .env, mai in DB)
- Impostare quota mensile prospect per provider
- Vedere log chiamate API
- Vedere errori provider
- Modificare regole scoring

## SICUREZZA

- API key mai nel codice client o nel DB
- Solo in .env o secrets manager
- Log ogni chiamata esterna
- Quota massima per evitare costi imprevisti
- Fallback su database interno se provider offline
