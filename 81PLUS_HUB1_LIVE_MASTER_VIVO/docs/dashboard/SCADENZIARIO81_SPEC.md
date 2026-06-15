# SCADENZIARIO81+ SPEC

## CATEGORIE

**Formazione:**
corsi lavoratori, aggiornamenti formazione, primo soccorso, antincendio.

**Medicina:**
visite mediche.

**Attrezzature e DPI:**
DPI, estintori, attrezzature, mezzi, revisioni mezzi.

**Assicurazioni e contratti:**
assicurazioni, polizze, fatture, tasse, contratti, manutenzioni.

**HACCP:**
HACCP prodotti, scadenze alimenti, temperature, pulizie, sanificazioni, legionella.

**Privacy:**
documenti identità, PEC, dominio, hosting, software.

**Altro:**
qualsiasi scadenza personalizzata.

## OUTPUT EXPORT

- PDF riepilogativo
- CSV per import
- ICS per Google Calendar
- ICS per Apple Calendar
- Sync Google Calendar (API OAuth)

## NOTIFICHE

- 90 giorni prima: alert bassa priorità
- 30 giorni prima: alert media priorità
- 7 giorni prima: alert alta priorità
- Giorno scadenza: alert critico
- Scaduto: alert rosso + azione richiesta

## API

POST /api/create-deadline.php {categoria, nome, data, note, user_id}
GET /api/export-ics.php?user_id=&periodo=
