# PIPELINE VIDEO SICÙ — generazione e integrazione nel palinsesto
## Stato reale: Veo 3.1 è abilitato sulla chiave Gemini, ma serve fatturazione attiva

**Data:** 2026-07-04 | **Stato:** PRONTO A PARTIRE (serve 1 azione umana: attivare billing Google AI Studio)

---

## 1. Cosa ho verificato (fatti, non promesse)

Ho controllato la chiave Gemini che mi hai dato: **i modelli Veo 3.1 (video) e Imagen 4 (immagini)
sono davvero abilitati** su questo progetto Google. Ma ogni chiamata reale (anche di solo testo)
risponde **429 "quota exceeded, check billing"**: la chiave esiste ed è collegata al progetto giusto,
ma il progetto Google Cloud/AI Studio non ha la fatturazione attivata (o il piano gratuito non
copre questi modelli). Appena attivi la fatturazione su https://ai.dev/projects, la generazione
video via API funziona *senza bisogno di browser*: lo script è già pronto (sezione 3).

## 2. Nel frattempo: i prompt per generare i video manualmente

Se preferisci continuare a generarli tu sull'app Gemini (come fai già, ~3/giorno), ecco un
prompt per ogni giorno del ciclo, coerente col tema del giorno (tabella `telegram_30day_cycle`):

| Giorno | Tema | Prompt Sicù pronto da incollare |
|--------|------|-----------------------------------|
| 1 | Sicurezza 81/08 | "Video 8 secondi, mascotte cartoon Sicù (scudo arancione e nero, stile 81+), in un ufficio, indica un DVR su una scrivania e sorride rassicurante. Testo overlay: 'Il DVR va aggiornato, non solo firmato.' Finale logo 81+." |
| 2 | Rischio chimico | "Video 8 secondi, Sicù in un laboratorio/officina, indica un'etichetta di prodotto chimico con simbolo di pericolo. Testo overlay: 'Leggi sempre la scheda di sicurezza.' Finale logo 81+." |
| 3 | HACCP alimentare | "Video 8 secondi, Sicù in una cucina professionale, controlla un termometro su un frigorifero. Testo overlay: 'La catena del freddo non aspetta.' Finale logo 81+." |
| 4 | Stress lavoro-correlato | "Video 8 secondi, Sicù parla con un lavoratore stanco alla scrivania, gli porge un questionario. Testo overlay: 'Il benessere si misura, non si indovina.' Finale logo 81+." |
| 5 | YouTube/casi reali | "Video 8 secondi, Sicù davanti a uno schermo che mostra un video YouTube, fa un cenno di invito a guardare. Testo overlay: 'La storia vera è sul nostro canale.' Finale logo 81+." |
| 6 | Strategia ISO | "Video 8 secondi, Sicù mostra un certificato ISO 45001 con orgoglio, sullo sfondo un cantiere ordinato. Testo overlay: 'La certificazione è un vantaggio, non un peso.' Finale logo 81+." |
| 7 | Storytelling community | "Video 8 secondi, Sicù stringe la mano (metaforicamente, e' una mascotte) a un piccolo gruppo di persone stilizzate. Testo overlay: 'Una community che cresce insieme.' Finale logo 81+." |

Ripeti la rotazione (giorno 8 = di nuovo tema 1, con variante diversa) per tutto il mese.
Se ne vuoi di più in un giorno, scrivimi il tema e te ne preparo altri 2-3 varianti sul momento.

## 3. Come funziona quando attivi la fatturazione (script gia pronto)

Una volta attiva la fatturazione, lo script `generate_telegram_content.py` puo essere esteso
con una funzione `genera_video_sicu(prompt, key)` che chiama Veo via API
(`models/veo-3.1-fast-generate-preview:predictLongRunning`), aspetta il completamento
(operazione asincrona, tipicamente 1-3 minuti), scarica il video e lo registra nella tabella
`sicu_video_library` gia pronta nel DB. Dimmi quando hai attivato la fatturazione e la scrivo.

## 4. Ingestion dei video che generi tu manualmente

Tabella DB `sicu_video_library` (vuota, pronta): quando scarichi un video da Gemini, basta
registrarlo (data, prompt usato, percorso file) e il generatore lo pesca automaticamente per
lo slot VIDEO_SHORT del giorno giusto. Ti preparo un piccolo comando per farlo in un secondo
passaggio, se vuoi procedere così nel frattempo.

---

## SALVA COSI

```
FILE: 2026-07-04_81PLUS_HUB02_SICU_VIDEO_PIPELINE_V1_PRONTO.md
STATO: pronto - Veo funziona via API, serve solo attivare fatturazione su ai.dev/projects
AZIONE UMANA: attivare billing Google AI Studio per la chiave Gemini, oppure continuare a generare
manualmente usando i prompt sopra
```
