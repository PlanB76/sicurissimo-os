# 02 · SPECIFICHE WEBHOOK PAYPAL · PAYGATE81+

## Funzione

Questo documento definisce la specifica tecnica per collegare PayPal a PayGate81+.

PayPal viene usato per:
ricariche PV.
acquisto PIX81+.
acquisto SDK81+.
acquisto SDK81+ ELITE.
acquisto SDK81+ ROYAL.
eventuali pass mensili.

## Endpoint interno

Produzione:

https://81plus.net/api/paygate81/paypal_webhook.php

Sandbox:

https://81plus.net/staging/api/paygate81/paypal_webhook.php

Metodo:

POST

Content-Type:

application/json

## Eventi PayPal accettati

Per WAVE 1 accettare solo eventi collegati a pagamento completato o cattura completata.

Eventi principali:

PAYMENT.CAPTURE.COMPLETED
CHECKOUT.ORDER.APPROVED
CHECKOUT.ORDER.COMPLETED
BILLING.SUBSCRIPTION.ACTIVATED
BILLING.SUBSCRIPTION.CANCELLED
BILLING.SUBSCRIPTION.SUSPENDED
PAYMENT.CAPTURE.REFUNDED
PAYMENT.CAPTURE.DENIED

Regola:
per ricariche PV e acquisti una tantum processare solo eventi conclusivi con stato completato.
per subscription aggiornare solo membership o pass, mai accreditare PV se non previsto.

## Verifica firma

Il webhook non va mai processato solo perché arriva sul server.

Prima si verifica la firma usando i dati ricevuti negli header PayPal e il webhook ID salvato nel file .env.

## Header PayPal attesi

PAYPAL-AUTH-ALGO
PAYPAL-CERT-URL
PAYPAL-TRANSMISSION-ID
PAYPAL-TRANSMISSION-SIG
PAYPAL-TRANSMISSION-TIME

## Variabili ambiente

PAYPAL_MODE=sandbox oppure live
PAYPAL_CLIENT_ID=
PAYPAL_CLIENT_SECRET=
PAYPAL_WEBHOOK_ID=
PAYPAL_API_BASE=https://api-m.sandbox.paypal.com oppure https://api-m.paypal.com

Mai scrivere queste variabili nel codice client.
Mai committarle su GitHub.
Mai caricarle in Drive pubblico.

## Payload JSON atteso

Esempio logico PayGate81+:

{
  "id": "WH-8D1234567890",
  "event_version": "1.0",
  "create_time": "2026-06-14T12:00:00Z",
  "resource_type": "capture",
  "event_type": "PAYMENT.CAPTURE.COMPLETED",
  "summary": "Payment completed",
  "resource": {
    "id": "PAYPAL_CAPTURE_ID",
    "status": "COMPLETED",
    "amount": {
      "currency_code": "EUR",
      "value": "100.00"
    },
    "custom_id": "PG81-20260614-000001",
    "invoice_id": "PG81-20260614-000001",
    "supplementary_data": {
      "related_ids": {
        "order_id": "PAYPAL_ORDER_ID"
      }
    },
    "payer": {
      "email_address": "utente@example.com"
    }
  }
}

## Campi obbligatori interni

PayGate81+ deve trovare questi dati:

event_id = id webhook PayPal.
event_type = tipo evento.
provider_payment_id = resource.id.
provider_order_id = resource.supplementary_data.related_ids.order_id oppure resource.id.
order_code = resource.custom_id oppure resource.invoice_id.
amount = resource.amount.value.
currency = resource.amount.currency_code.
status = resource.status.
payer_email = resource.payer.email_address, se presente.

## Regola order_code

Ogni ordine creato da 81+ prima del checkout deve avere un codice unico.

Formato:

PG81-YYYYMMDD-000001

Questo codice va passato a PayPal come:

custom_id
invoice_id

Il webhook deve usare questo codice per collegare il pagamento all’ordine interno.

## Stati ordine interni

Tabella consigliata:

paygate81_orders

Stati:

pending.
awaiting_provider.
webhook_received.
signature_verified.
verified.
manual_review.
crediting.
credited.
failed.
rejected.
refunded.
cancelled.

## Regola idempotenza

PayPal può inviare lo stesso webhook più volte.
Il database deve impedire doppi accrediti.

Campi unici:

paypal_event_id UNIQUE.
provider_payment_id UNIQUE.
order_code UNIQUE.

Se paypal_event_id esiste già:
rispondere HTTP 200.
non accreditare nulla.
scrivere log duplicato ignorato.

Se order_code è già credited:
rispondere HTTP 200.
non accreditare nulla.
scrivere log ordine già processato.

## Flusso tecnico webhook

1. Ricevi payload raw.
2. Salva raw payload in paygate81_logs.
3. Verifica header PayPal.
4. Chiama verifica firma PayPal.
5. Se verifica fallisce, stato failed.
6. Se verifica riesce, cerca order_code.
7. Apri transazione SQL.
8. Blocca ordine con SELECT FOR UPDATE.
9. Controlla che non sia già credited.
10. Controlla importo e valuta.
11. Controlla pacchetto e PV previsti.
12. Aggiorna ordine a crediting.
13. Accredita PV nel wallet.
14. Inserisci movimento in pv_transactions.
15. Aggiorna ordine a credited.
16. Commit.
17. Invia email da info@81plus.net.
18. Rispondi HTTP 200.

## Risposte HTTP

Webhook valido e processato:
200.

Webhook duplicato già ricevuto:
200.

Firma non valida:
400.

Ordine inesistente:
202 manual_review.

Importo non corrispondente:
202 manual_review.

Errore temporaneo DB:
500.

Errore permanente:
200 con log failed.

## Regola importo

Il sistema accredita PV solo se:

currency = EUR.
amount = expected_amount.
status = COMPLETED.
signature = SUCCESS.
order_status non è credited.

## Regola finale

Nessun PV viene accreditato dal frontend.
Nessun PV viene accreditato senza verifica firma.
Nessun PV viene accreditato fuori transazione.
Nessun PV viene accreditato due volte.
