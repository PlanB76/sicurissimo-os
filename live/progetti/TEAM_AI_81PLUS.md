# TEAM AI 81+ · Claude a capo, ChatGPT e Gemini al tavolo

## La soluzione in una riga
Un workflow n8n dove Claude è il capo, riceve il problema, lo scompone, manda i pezzi a ChatGPT e a Gemini in parallelo, riceve i pareri, decide e ti consegna la soluzione finale su Telegram. Tu scrivi la domanda, il team lavora da solo.

## Come gira
1. Tu mandi la domanda al webhook, da Telegram, da un form o dal sito.
2. Claude, Graziella, scompone il problema in due domande mirate.
3. ChatGPT e Gemini rispondono in parallelo, ognuno sul suo pezzo.
4. Claude legge i due pareri, li pesa, decide e scrive la soluzione finale con i passi numerati.
5. La risposta arriva sul tuo Telegram admin.

## Cosa serve, una volta sola
- n8n. Su Hostinger gira sul piano VPS col template n8n a un click. Sul hosting condiviso non gira, serve il VPS oppure n8n cloud.
- Tre chiavi nelle variabili n8n. ANTHROPIC_API_KEY da console.anthropic.com, OPENAI_API_KEY da platform.openai.com, GEMINI_API_KEY da aistudio.google.com. Più TELEGRAM_BOT_TOKEN e TELEGRAM_ADMIN_CHAT che hai già.
- Importa download/n8n_team_ai_workflow.json, attiva, fine.

## Notion serve?
No, non è necessario. Il cervello dei dati è già il tuo MySQL più i JSON del sito, e n8n parla direttamente con api/n8n.php firmato. Notion può entrare dopo come lavagna di knowledge se vorrai, è un nodo in più nel workflow, non una fondamenta.

## Costi onesti
Ogni giro del team costa centesimi di API, tre chiamate AI per domanda. Il VPS n8n di Hostinger parte da pochi euro al mese. Niente abbonamenti nascosti.

## Estensioni già pronte
- Il nodo Leggi eventi 81+ del workflow gemello interroga api/n8n.php e può far partire il TEAM AI in automatico sugli eventi, esempio, lead caldo nuovo, il team prepara la strategia di contatto.
- La stessa uscita può scrivere su Brevo, sul canale Telegram o su email invece che in chat admin.
