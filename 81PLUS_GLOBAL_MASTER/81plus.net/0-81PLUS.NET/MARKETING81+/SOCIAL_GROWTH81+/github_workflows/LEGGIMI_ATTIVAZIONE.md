# ATTIVAZIONE WORKFLOW GITHUB — SOCIAL GROWTH81+

Il token attuale non puo scrivere in .github/workflows (manca lo scope "workflow").
Per attivare l'automazione, UNA di queste due strade:

## Strada A — Copia manuale via web (2 minuti)
1. Vai su github.com/PlanB76/sicurissimo-os → branch claude/create-claude-md-docs-JWjKP
2. Add file → Create new file → nome: .github/workflows/socialgrowth81-daily.yml
3. Incolla il contenuto di socialgrowth81-daily.yml (questa cartella) → Commit
4. Ripeti con socialgrowth81-publish-telegram.yml

## Strada B — Nuovo token
Genera un PAT con scope "repo" + "workflow" e passalo a Claude in chat.

## Secrets da impostare (obbligatori per la pubblicazione Telegram)
Settings → Secrets and variables → Actions → New repository secret:
- TELEGRAM_BOT_TOKEN  (da @BotFather)
- TELEGRAM_CHAT_ID    (es. @sicurissimo oppure -100xxxxxxxxxx)

## Come funziona dopo l'attivazione
- socialgrowth81-daily: ogni mattina 04:30 UTC genera il pacchetto del giorno (BOZZA) e committa
- Tu (o Claude) compilate i testi in 04_SCHEDULED/telegram.csv e mettete stato=APPROVATO
- socialgrowth81-publish-telegram: SOLO manuale — il tuo click e la HUMAN_APPROVAL
