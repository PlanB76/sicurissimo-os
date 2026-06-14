# Installazione flussi n8n 81+ in 1 click

## Cosa serve
Una istanza n8n attiva. Puoi usare n8n cloud oppure installarla sulla tua VPS.

## Import dei flussi
1. Apri n8n
2. Menu, Import from File
3. Carica uno alla volta i file json di questa cartella:
   - 1_lead_flow.json, cattura e nurturing lead
   - 2_client_flow.json, onboarding cliente dopo acquisto
   - 3_retention_flow.json, retention e scadenze
   - n8n_workflow_81plus.json, flusso principale eventi
   - n8n_team_ai_workflow.json, orchestrazione flotta AI

## Collegamento al sito
Tutti i flussi parlano col sito tramite il ponte api/n8n.php.
1. Su Hostinger imposta la variabile N8N_SECRET con una stringa lunga casuale
2. In n8n, in ogni nodo HTTP che chiama 81plus.net, aggiungi il parametro di firma
   La firma e hash_hmac sha256 della stringa n8n81 con il tuo N8N_SECRET
3. Eventi in uscita, n8n legge da https://81plus.net/api/n8n.php?az=eventi&da=ID&s=FIRMA
4. Eventi in entrata, n8n scrive su https://81plus.net/api/n8n.php?az=evento

## PV automatici
Il ponte accredita PV solo da una mappa fissa e sicura:
- mission_completed, 20 PV
- webinar_presente, 30 PV
- video_completato, 10 PV
Nessun importo libero puo arrivare dall esterno. Sicurezza prima di tutto.
