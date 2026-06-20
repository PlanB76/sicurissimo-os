# 81+ MASTERBLASTER MOTHERBOARD · SET SCRIPT MVP0 · LEGGIMI

## COSA E'
Set di script Apps Script DIVISI (uno per singolarita) da incollare TUTTI nello stesso
progetto Apps Script BOUND al Google Sheet Master Blaster. Il CORE crea il foglio e governa tutto.

## ORDINE FILE (incollali tutti, ognuno come file separato nel progetto)
1. 00_MOTHERBOARD81_CORE.gs   -> crea foglio, menu, install, trigger, semantic guard, human approval, KPI, Web App
2. 10_SCOUT81.gs              -> prospect: sync, scrape, enrich, AI score
3. 20_LEADGEN81.gs           -> nurturing (solo con doppio opt-in)
4. 30_LEX81.gs               -> normativo: obblighi/audit per ATECO
5. 40_SFERA_GIOCHI81.gs      -> missioni, escalation, lifewheel
6. 50_GAMIFICATION81.gs      -> tetti per status, leaderboard
7. 60_CASHBACK81.gs          -> benefit/voucher da ordini reali
8. 70_CASHCOW81.gs           -> contenuti YouTube + KPI
9. 80_GEM81.gs               -> scanner crypto (informativo, utente firma)
10. 90_PVCORE_PAYGATE81.gs   -> wallet PV/PV+, award, check 60/40
11. 95_AI_ROUTER81.gs        -> sceglie l'LLM giusto per tipo task

Un solo onOpen e un solo doPost (nel CORE). Nessuna collisione: verificato con node --check.

## INSTALL (una sola azione umana)
1. Apri il Google Sheet Master Blaster > Estensioni > Apps Script.
2. Crea 11 file e incolla i contenuti nell'ordine sopra.
3. Impostazioni progetto > Proprieta script:
   - X81_SECRET = il tuo secret admin (oggi SCOUT81-OWNER-2026, da cambiare)
   - MB81_WEBTOKEN = un token a tua scelta (per la Web App)
   - (opzionali AI) OPENAI_API_KEY, ANTHROPIC_API_KEY, GEMINI_API_KEY, GROQ_API_KEY, OLLAMA_BRIDGE_URL
4. Salva. Esegui MB_install una volta e autorizza.
5. Menu "81+ MOTHERBOARD" > RUN ALL adesso. Da qui i trigger lavorano da soli.

## WEB APP (per far scrivere comandi a gemma/gpt-oss/n8n)
Distribuisci > App web > Esegui come Me > Accesso: chi ha il link.
POST JSON: { "token":"<MB81_WEBTOKEN>", "origine":"gemma", "titolo":"...", "payload":{...} }
Finisce in COMMANDS, passa dal semantic guard, diventa TASKS81.

## REGOLE
PV/PV+ utility mai denaro · semantic guard attivo · doppio opt-in obbligatorio ·
human approval su payout/contratti/claim normativi · DB = fonte di verita · CTA unica 81plus.net.
