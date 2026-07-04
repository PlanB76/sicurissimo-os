# ATTIVAZIONE WORKFLOW GITHUB — SOCIAL GROWTH81+

Il token attuale non puo scrivere in .github/workflows (manca lo scope "workflow").
Per attivare l'automazione, UNA di queste due strade:

## Strada A — Copia manuale via web (5 minuti, sono 3 file ora)
1. Vai su github.com/PlanB76/sicurissimo-os → branch claude/create-claude-md-docs-JWjKP
2. Add file → Create new file → nome: .github/workflows/socialgrowth81-daily.yml
3. Incolla il contenuto di socialgrowth81-daily.yml (questa cartella) → Commit
4. Ripeti con socialgrowth81-publish-telegram.yml
5. Ripeti con socialgrowth81-telegram-daily.yml (NUOVO — genera i contenuti Telegram con AI reale)

## Strada B — Nuovo token
Genera un PAT con scope "repo" + "workflow" e passalo a Claude in chat.

## Secrets da impostare
Settings → Secrets and variables → Actions → New repository secret:

**Per la pubblicazione Telegram:**
- TELEGRAM_BOT_TOKEN  (di @sicurissimo81_bot)
- TELEGRAM_CHAT_ID    (canale madre)

**Per la generazione contenuti AI (NUOVO — workflow socialgrowth81-telegram-daily):**
- OPENAI_API_KEY      (verificata attiva e funzionante — motore primario)
- ANTHROPIC_API_KEY   (chiave valida ma credito insufficiente — aggiungi credito su console.anthropic.com)
- GEMINI_API_KEY      (chiave valida ma serve attivare fatturazione su ai.dev/projects per usarla)
- GROQ_API_KEY        (chiave valida, da verificare — potrebbe funzionare gia da GitHub Actions anche se bloccata in sandbox locale)

Il motore prova le chiavi in cascata (OpenAI -> Anthropic -> Gemini -> Groq) e usa la prima
disponibile: con solo OPENAI_API_KEY impostata il sistema funziona gia al 100% oggi.

## Come funziona dopo l'attivazione
- socialgrowth81-telegram-daily: ogni mattina 03:30 UTC genera contenuto REALE (AI) per le 11 chat,
  24 azioni/ora dove previsto, fill-rate calibrato per chat (Club/VIP restano a bassa frequenza),
  Semantic Guard automatico che blocca parole vietate (investimento/rendimento/ecc.) prima della coda
- socialgrowth81-daily: genera anche lo skeleton generico multi-social (48 slot, 6 piattaforme)
- Tu revisioni le code in 04_SCHEDULED/telegram_*.csv e cambi stato in APPROVATO per i post che vuoi pubblicare
- socialgrowth81-publish-telegram: SOLO manuale — il tuo click e la HUMAN_APPROVAL
