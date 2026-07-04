# CAMPAGNA DI CONSENSO (OPT-IN) — LISTA1 (1.852 contatti email+WA)
## NON è un invito diretto al gruppo: è la richiesta di consenso, passo obbligatorio prima

**Data:** 2026-07-04 | **Stato:** PRONTA, NON INVIATA (serve canale + consenso)

---

## Perché non posso mandare un invito diretto

Verificato nel DB: **0 contatti su 4.312 hanno `consenso_email=1`**. Sono contatti raccolti/importati,
non contatti che hanno mai confermato di voler ricevere comunicazioni da 81+. Mandare un invito
diretto al gruppo Telegram violerebbe la regola double opt-in (GDPR + regola FASE ZERO81+ n.4:
"nessuna email inviata senza double opt-in"). Il passo corretto è chiedere il consenso, non presumerlo.

## Il messaggio di richiesta consenso (pronto)

**Oggetto email:** Sei nella nostra lista contatti 81+ — confermi di voler restare?

```
Ciao,

sei nella lista contatti di 81+ (sicurezza sul lavoro, HACCP, privacy per aziende italiane).

Se vuoi restare aggiornato su normative, scadenze e novità dell'ecosistema 81+,
rispondi "SI" a questa email o clicca qui: [link conferma opt-in].

Se preferisci non ricevere più nulla, rispondi "NO" o ignora questo messaggio:
non ti scriveremo di nuovo.

Solo chi conferma riceverà l'invito alla community Telegram e i prossimi aggiornamenti.

81+ / Sicurissimo
```

**Messaggio WhatsApp equivalente (per chi ha anche il numero, LISTA1):**
```
Ciao. Sei nella lista contatti 81+ (sicurezza, HACCP, privacy per aziende).
Vuoi restare aggiornato e ricevere l'invito alla nostra community Telegram?
Rispondi SI per confermare, oppure ignora per non ricevere altro.
```

## Cosa succede dopo la conferma

1. Chi risponde "SI" → `consenso_email=1` (o `consenso_wa=1`) nel DB → entra nel flusso normale
2. Riceve il link al canale pubblico Sicurissimo.Online + al gruppo 81+ ECOSYSTEM (la baseline, sempre aperta)
3. Da lì segue il funnel naturale verso USER81+ (SIC-ID) e oltre

## Cosa manca per inviare davvero (azione umana)

- **Canale di invio**: SMTP (Hostinger, gia configurato altrove nel progetto: `info@81plus.net`) o
  WhatsApp Business API — nessuno dei due è collegato a questo script/flusso oggi
- **Un piccolo script di invio batch** (mai spam: throttling, un invio alla volta, rispetto dei limiti
  del provider) — non ancora scritto, va costruito quando il canale è pronto
- **Gestione delle risposte** (SI/NO) per aggiornare `consenso_email`/`consenso_wa` nel DB

## SALVA COSI

```
FILE: 2026-07-04_81PLUS_HUB02_CAMPAGNA_OPTIN_LISTA1_V1_PRONTO.md
STATO: messaggio pronto, NON inviato - serve canale SMTP/WhatsApp collegato
AZIONE UMANA: decidere il canale (SMTP Hostinger gia esiste per il progetto email, va solo collegato)
```
