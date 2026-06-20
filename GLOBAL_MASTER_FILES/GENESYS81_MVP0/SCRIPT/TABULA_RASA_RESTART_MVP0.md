# 81+ · TABULA RASA + RESTART OPERATIVO MVP0
Procedura pulita per ripartire ordinati verso il go-live MVP0. Una sola azione umana per accendere il nodo.

## A. COSA SI TIENE (NON toccare)
- DB u173050672_81plusglobal (struttura e tabelle).
- 81plus.net e i moduli admin gia online (scout81, sfera81, lex81, gem81).
- Organigramma v3 canonico + CLAUDE.md (memoria operativa).
- Questo set di script Motherboard + il Google Sheet Master Blaster.
- I deliverable strategici (blueprint, motherboard PDF).

## B. COSA SI PULISCE (azzeramento controllato)
- Dati di test/demo nel DB (prospect finti, utenti di prova).
- Code vecchie nel foglio: COMMANDS, TASKS81, LOG81, SYNC_STATUS (svuotabili, riparte il CORE).
- Trigger Apps Script duplicati (il CORE li reinstalla puliti).
- File sparsi in 99_INBOX / 99_INBOX_DA_SMISTARE (smistare o archiviare).
- Prospect duplicati (SCOUT li deduplica al sync).

## C. RESTART, ORDINE ESATTO
1. APPS SCRIPT: incolla i file 00..95 nel progetto del foglio Master Blaster.
   Metti X81_SECRET e MB81_WEBTOKEN nelle Script Properties. Esegui MB_install e autorizza.
2. SERVER: lancia CARICA.bat (carica online, installa tabelle SCOUT/SFERA/LEX/LEADGEN,
   semina i flussi, accende l'autopilota locale gemma/gpt-oss).
3. VERIFICA: nel foglio, DASHBOARD mostra prospect e obblighi LEX che salgono; SYNC_STATUS tutto OK; LOG81 senza ERROR.
4. HUMAN APPROVAL: approva prezzi, claim normativi e regolamenti PV/PV+/CAREER prima di pubblicare.
5. WAVE 1 LIVE: landing "Audit in 15 minuti" + email 7 giorni + Scout Pack START.

## D. CHECKLIST GO-LIVE MVP0 (5 cose che il sistema deve fare benissimo)
[ ] 1. Acquisisce lead (SCOUT + landing + form)
[ ] 2. Diagnostica (audit 7 step -> score + PDF)
[ ] 3. Propone (preventivo 3 opzioni in PV)
[ ] 4. Attiva in dashboard (SIC-ID + wallet PV+ + missione)
[ ] 5. Genera follow-up automatico (email/nurture, solo con doppio opt-in)

## E. LA TUA UNICA AZIONE UMANA
Apri il foglio, incolla gli script, metti il secret, esegui MB_install. Poi lancia CARICA.bat. Il resto e' automatico.
