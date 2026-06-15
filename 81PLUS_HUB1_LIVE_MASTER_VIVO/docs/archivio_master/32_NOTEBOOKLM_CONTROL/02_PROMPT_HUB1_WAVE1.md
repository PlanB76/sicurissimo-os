Analizza le fonti caricate per HUB1 WAVE 1.

Obiettivo:
preparare 81plus.net alla partenza reale.

Controlla se sono presenti, coerenti e completi questi moduli:

signup
login
SIC-ID
profilo persona
profilo azienda
dashboard utente
wallet PV
wallet PV+
PayGate81+
PayPal webhook
Revolut manual review
referral link
QR code
PIX81+
Green81+
welcome email da welcome@81plus.net
contatti generali da info@81plus.net
activity mining PV+
database MySQL
API PHP
log admin
sicurezza base

Per ogni modulo dimmi:

1. presente o mancante
2. file collegati
3. tabelle database necessarie
4. API necessarie
5. rischio tecnico
6. rischio sicurezza
7. cosa deve fare Claude
8. cosa deve fare Claude Code su GitHub
9. cosa devo controllare io prima del deploy

Regole tecniche:

PV e PV+ non devono mai essere accreditati da JavaScript client side.
PayPal deve usare webhook verificato.
Revolut deve essere semi automatico con verifica admin.
Ogni accredito deve essere idempotente.
Ogni pagamento deve avere log.
Ogni utente deve essere collegato a SIC-ID.
Nessuna credenziale deve stare nel codice.
Exchange81+ non riguarda WAVE 1, salvo link o preview.
