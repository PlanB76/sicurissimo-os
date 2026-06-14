# 81PLUS — WORKFLOW N8N

I workflow importabili che reggono la revenue machine. Pronti da caricare in n8n.

## Come importare

1. Apri n8n self hosted sul VPS.
2. Menu, Import from File.
3. Carica il file JSON.
4. Configura le credenziali, HubSpot e la variabile d'ambiente BREVO_API_KEY.
5. Attiva il workflow.

## I tre workflow core, pronti

### 1 Lead Flow, 1_lead_flow.json
Il form del sito invia un webhook. Il flusso crea il SIC ID e la persona, fa il lead scoring con Groq, sincronizza HubSpot, manda l'email di benvenuto con Brevo e la notifica WhatsApp.
Webhook, /webhook/lead-in

### 2 Client Flow, 2_client_flow.json
PayPal conferma il pagamento, il flusso attiva la membership, aggiorna HubSpot, prepara la checklist documenti con l'AI Router e manda l'email di onboarding.
Webhook, /webhook/paypal-ok

### 3 Retention Flow, 3_retention_flow.json
Ogni mattina alle 8 legge le scadenze. Per quelle sotto i 30 giorni genera un alert con Groq, manda l'email e attiva l'upsell dei Sigilli Smart.
Trigger, cron giornaliero

## I quattro workflow restanti, da costruire

Dal documento 360, completano i sette.

4 Upsell. Cliente attivo da oltre 7 giorni, offerta HACCP avanzato, GDPR, ISO, formazione.
5 Referral loop. Invito, verifica, premio, nuovo account. Il referral è il SIC ID stesso.
6 RWA minting. Documento validato, hash SHA-256, chiamata a Identity81 mintRWA, notifica.
7 KPI consolidamento. Export dati, calcolo LTV CAC churn con Gemini, report al human alle 9.

## Nota

Tutti i workflow passano dall'AI Router, /api/ai_router.php, che sceglie il motore giusto e applica le soglie di governance. I documenti legali e le operazioni Web3 di valore alto vengono marcati per la validazione umana prima di partire.
