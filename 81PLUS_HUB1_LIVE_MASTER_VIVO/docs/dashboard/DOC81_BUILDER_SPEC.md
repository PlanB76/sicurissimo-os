# DOC81+ BUILDER SPEC

## DEFINIZIONE

Generatore di documenti personalizzati in PDF per la compliance aziendale.

## DOCUMENTI SUPPORTATI

### Sicurezza sul Lavoro (81/08)
DVR, DUVRI, POS, PSC, PIMUS, MMC, rischio chimico, rischio biologico,
rumore, vibrazioni, stress lavoro correlato, procedure sicurezza,
nomine, verbali, checklist.

### HACCP
Manuale HACCP, registri temperature, registri pulizie,
registri sanificazione, registri non conformità,
registri manutenzioni HACCP, legionella.

### Privacy (GDPR)
Privacy policy, registro trattamenti, nomine privacy,
informative privacy, lettere incarico autorizzati,
valutazioni privacy, data breach.

### Altro compliance
Altri documenti su richiesta.

## FLUSSO GENERAZIONE

1. Utente sceglie tipo documento
2. Compila wizard guidato (dati azienda, settore, rischi, ecc.)
3. Sistema genera bozza PDF
4. Utente scarica PDF
5. Disclaimer obbligatorio mostrato prima del download

## DISCLAIMER OBBLIGATORIO

I documenti generati sono bozze operative basate sui dati inseriti
e sui modelli disponibili.
Devono essere verificati e validati da soggetti competenti
prima dell'uso ufficiale.
81+ non garantisce la conformità automatica dei documenti generati.

## API

POST /api/create-document.php {tipo, dati_azienda, dati_specifici}
GET /api/create-document.php?doc_id= (download PDF)
