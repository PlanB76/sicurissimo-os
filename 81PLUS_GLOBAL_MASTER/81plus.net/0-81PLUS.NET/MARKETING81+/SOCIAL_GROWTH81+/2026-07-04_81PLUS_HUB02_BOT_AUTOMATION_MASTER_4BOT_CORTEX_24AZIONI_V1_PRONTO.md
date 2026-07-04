# BOT AUTOMATION MASTER 81+ — 4 BOT, CORTEX81+ AI, GATING, 24 AZIONI/GIORNO
## Automazione 100% bot + AI + GitHub. Zero intervento manuale salvo HUMAN_APPROVAL.

**Data:** 2026-07-04 | **Stato:** PROGETTATO, PRONTO ALL'IMPLEMENTAZIONE
**Copre:** tutte le 11 chat reali dell'ecosistema (2 canali pubblici, 1 gruppo pubblico, 3 gruppi membership, 5 gruppi status di rete)

---

# 1. I 4 BOT — RUOLI DISTINTI, ZERO SOVRAPPOSIZIONE

Ogni bot ha UNA responsabilità primaria. Non si accavallano mai sulla stessa funzione,
cosi non c'e mai ambiguita su "chi risponde".

| Bot | Ruolo primario | Cosa fa | Cosa NON fa |
|-----|-----------------|---------|--------------|
| **@sicurissimo81_bot** | ORCHESTRATORE | Gating status/membership/SDP in tempo reale, inviti monouso, welcome, pubblicazione palinsesto approvato, comandi `/status /pv /rank`, trigger funnel (upsell/rinnovo) | Non modera contenuti, non risponde a domande aperte, non genera gamification |
| **@SicurissimoAI_bot** | CORTEX81+ AI ENGINE | Motore conversazionale (Claude/GPT via API), risposte a domande libere, FAQ, assistenza, problem solving, spiegazioni normative on-demand | Non gestisce gating, non modera, non assegna PV+ |
| **@sicurissimonewbot** | MODERAZIONE | Anti-spam, filtro Semantic Guard, controllo regole, escalation a Mirco per casi delicati, gestione conflitti pubblici | Non genera contenuti, non fa gating, non risponde a FAQ |
| **@ottantuno_bot** | GAMIFICATION/ENGAGEMENT | Missioni, quiz, sondaggi, badge, leaderboard, streak, intrattenimento segmentato per status | Non gestisce accessi, non modera, non vende direttamente |

### Perche 4 bot e non 1

Un solo bot che fa tutto diventa lento, confuso agli occhi dell'utente (troppi comandi diversi)
e rischioso (se cade, cade tutto). 4 bot specializzati permettono:
- Manutenzione indipendente (aggiorno la gamification senza toccare il gating)
- Percezione piu naturale per l'utente (parla con "l'assistente AI" per domande, con "il bot ufficiale" per lo status)
- Scalabilita: ogni bot puo girare su un webhook PHP separato sullo stesso hosting

---

# 2. GATING ENGINE — CONTROLLO STATUS/MEMBERSHIP/SDP IN TEMPO REALE

Il cuore tecnico di @sicurissimo81_bot. Ogni richiesta di accesso o azione passa da qui.

## 2.1 Query di verifica per asse (dal DB unico)

| Asse | Campo DB da verificare | Tabella | Condizione di accesso |
|------|--------------------------|---------|-------------------------|
| PUBBLICO | Nessuna | — | Sempre aperto |
| MEMBERSHIP | `membership_tier` + `membership_scadenza` | `user81` (da estendere) | Tier = Basic/Pro/Elite AND scadenza > oggi |
| STATUS_RETE Networker | `network_rank` + `sdk_attivo` + `sdp_scadenza` + `membership_scadenza` | `user81` + `network81_ranks` | SDK acquistato AND SDP Pass del rank corrente attivo AND Membership attiva |
| STATUS_RETE Elite | `network_rank >= 6` (SUMMIT) + `sdk_royal_attivo` + `sdp_royal_scadenza` | `user81` + `career81_ranks` | Come sopra, soglia rank 6 |
| STATUS_RETE VIP | `elite_rank_interno >= 3` + `sdk_diamond_attivo` + `sdp_diamond_scadenza` | `user81` | Rank 3 interno Elite raggiunto |
| STATUS_RETE Franchisee | `point81_contratto_attivo` + `canone_scadenza` | `user81` | Contratto Light/Standard/Flagship attivo |
| STATUS_RETE Club | `club_tier` (Palladium/Iridium/Rhodium) + `canone_scadenza` | `user81` | Canone mensile attivo |
| PRESIDENT81+ | Nessun campo automatico | — | Solo flag manuale impostato da Mirco, MAI automatico |

## 2.2 Flusso di verifica (pseudo-codice del webhook)

```
1. Utente scrive /accesso al bot (o clicca link da un post)
2. Bot legge SIC-ID collegato al chat_id Telegram (tabella user81.telegram_chat_id)
3. Bot esegue query di gating per l'asse richiesto (tabella sopra)
4. Se OK:
   - Genera link invito Telegram monouso (API createChatInviteLink, member_limit=1, expire_date=+24h)
   - Invia il link + messaggio di benvenuto specifico del gruppo
   - Logga l'evento in telegram_accessi_log
5. Se NO:
   - Calcola cosa manca (es. "ti manca il rinnovo SDP Pass, scaduto il [data]")
   - Invia messaggio con CTA specifica per sbloccare (link LISTINO81+ o pagamento)
   - NON invia alcun link di invito
6. Verifica periodica (cron ogni notte): scandisce tutti i membri dei gruppi status,
   ricontrolla la condizione di accesso, se decaduta rimuove dal gruppo con messaggio
   cortese di downgrade (mai kick silenzioso)
```

## 2.3 Downgrade automatico (pass/membership scaduti)

```
Messaggio tipo di downgrade (tono cortese, mai punitivo):

"Ciao [Nome]. Il tuo Pass [SDP/Membership] è scaduto il [data].
Il tuo status [rank] resta registrato, ma l'accesso a questo gruppo
si sospende fino al rinnovo. Rinnova qui: [link pagamento].
Se hai domande scrivi a @SicurissimoAI_bot."
```

---

# 3. CORTEX81+ — IL MOTORE AI CONVERSAZIONALE

@SicurissimoAI_bot e alimentato da CORTEX81+, il motore AI dell'ecosistema (gia previsto nel
CTA Bank come CTA22 "Chiedi a CORTEX81+"). Architettura:

```
Utente scrive domanda libera al bot
        |
        v
Webhook PHP riceve il messaggio
        |
        v
Query al DB: chi e l'utente (status, membership, storico) -> CONTESTO
        |
        v
Chiamata API Claude (Anthropic) con:
  - Contesto utente (status, prodotti gia posseduti, rank)
  - Knowledge base normativa (kb/sicurezza.txt, haccp.txt, privacy.txt, iso.txt, ateco.txt)
  - Regole di risposta (Semantic Guard, mai promesse finanziarie, sempre disclaimer se pertinente)
  - Catalogo LISTINO81+ per suggerire il prodotto giusto se la domanda lo richiede
        |
        v
Risposta generata, filtrata da un secondo passaggio Semantic Guard
(regex di sicurezza: blocca "investimento/rendimento/ROI/guadagno garantito" anche se
sfuggiti al prompt) prima di essere inviata
        |
        v
Log della conversazione (per training futuro e controllo qualita, mai per profilazione invasiva)
```

## 3.1 Casi d'uso CORTEX81+

| Tipo domanda | Esempio | Comportamento |
|---------------|---------|-----------------|
| Normativa | "Quante ore di formazione servono per rischio alto?" | Risponde con dato preciso da kb/sicurezza.txt (4+12 ore), cita fonte |
| Prodotto | "Quanto costa il Pacchetto Business?" | Risponde con prezzo esatto da listino (1900€), propone CTA |
| Status personale | "A che rank sono?" | Query diretta al DB (non serve AI generativa, risposta strutturata) |
| Obiezione | "E' troppo caro" | Risposta guidata da script già scritti nei programmi editoriali (gestione obiezioni), mai inventata da zero |
| Fuori tema/rischiosa | "Quanto posso guadagnare col Network?" | Risposta con disclaimer obbligatorio: compensi solo su vendite reali, nessuna garanzia, mai cifra promessa |
| Escalation | Utente arrabbiato, minaccia di andarsene | Bot riconosce sentiment negativo -> passa a @sicurissimonewbot per moderazione umana |

---

# 4. LA LOGICA DELLE 24 AZIONI/GIORNO — MOTORE GENERATIVO

**Perche non e una lista scritta a mano:** 24 azioni x 11 chat x 365 giorni = 96.360 azioni/anno.
Nessuna lista statica puo restare "sempre diversa" a questa scala. La soluzione e un
**motore a slot + rotazione + generazione AI**, non un calendario pre-scritto.

## 4.1 Tassonomia delle azioni (24 categorie funzionali)

| # | Categoria | Obiettivo funnel | Esempio |
|---|-----------|-------------------|---------|
| 1 | NEWS_HOOK | Attenzione | News normativa con hook emotivo |
| 2 | CHECKLIST_FREEBIE | Lead magnet | Checklist scaricabile gratuita |
| 3 | QUIZ_ENGAGEMENT | Interazione | Quiz a risposta multipla |
| 4 | SONDAGGIO | Interazione + segmentazione | Poll Telegram nativo |
| 5 | CASO_REALE | Riprova sociale | Storia anonimizzata di cliente |
| 6 | TESTIMONIAL | Riprova sociale | Recensione/screenshot risultato |
| 7 | VIDEO_SHORT | Awareness | Repurposing da YouTube |
| 8 | VIDEO_LUNGO | Autorita | Live/masterclass mensile |
| 9 | CTA_SOFT | Nurturing | "Scarica/guarda/rispondi" |
| 10 | CTA_HARD | Conversione | "Acquista/attiva/candidati" |
| 11 | MITO_FATTO | Educazione | Sfata un errore comune |
| 12 | DOMANDA_APERTA | Community | Stimola risposte/commenti |
| 13 | BADGE_CELEBRATION | Gamification | Annuncio pubblico traguardo utente |
| 14 | RANK_PROGRESS | Gamification/Funnel | "Ti mancano X PV per il prossimo rank" |
| 15 | MISSIONE_ANNOUNCE | Gamification | Nuova missione PV+ del giorno/settimana |
| 16 | LEADERBOARD | Gamification | Classifica periodica |
| 17 | RENEWAL_REMINDER | Retention | Promemoria rinnovo Pass/Membership |
| 18 | UPSELL_NUDGE | Funnel | Suggerimento upgrade tier successivo |
| 19 | CROSS_PROMO | Funnel trasversale | Rimando a un'altra chat dell'ecosistema |
| 20 | EVENT_REMINDER | Retention | Promemoria webinar/evento |
| 21 | AI_QA_SLOT | Servizio | "Chiedi a CORTEX81+" aperto |
| 22 | ENTERTAINMENT | Retention | Meme, curiosita, contenuto leggero |
| 23 | RECAP | Chiusura ciclo | Riepilogo giornata/settimana |
| 24 | DAO_UPDATE | Governance (solo assi alti) | Aggiornamento proposta/esito consultivo |

## 4.2 Motore di variabilita (come restano "diverse ogni giorno")

Ogni categoria non e un testo fisso: e uno **slot** che pesca da 3 dimensioni rotanti,
combinate per indice del giorno (mai a caso, sempre deterministico e riproducibile):

```
contenuto_del_giorno = f(categoria_slot, tema_del_giorno, prodotto_in_focus, hook_variante)

dove:
- tema_del_giorno       = rotazione settimanale gia definita in CLAUDE.md (Lun=81/08, Mar=chimico,
                          Mer=HACCP, Gio=stress, Ven=YouTube/casi, Sab=ISO, Dom=storytelling)
- prodotto_in_focus     = rotazione mensile sulla scala di valore (settimana 1: freebie/tripwire,
                          settimana 2: membership, settimana 3: pacchetti/sigilli, settimana 4: alto ticket)
- hook_variante         = indice (giorno_dell_anno mod N_varianti) su un banco di N hook per tema,
                          generato/arricchito da ChatGPT e revisionato da Claude prima della pubblicazione
```

Questo garantisce che lo stesso slot (es. "NEWS_HOOK delle 08:15") parli sempre di
attualita compliance, ma il tema, il prodotto abbinato e l'angolo emotivo cambino ogni giorno
per settimane prima di ripetersi, e anche quando si ripetono (dopo mesi) il fatto normativo
di base e comunque diverso (nuove news reali).

## 4.3 Fill-rate per chat (24 slot possibili, non tutti sempre attivi)

Coerente con i palinsesti gia scritti per ogni chat (che hanno gia calibrato la frequenza
giusta): il motore supporta fino a 24 slot/giorno ma **il fill-rate reale e specifico per asse**,
per non contraddire le logiche gia definite (es. Club/President hanno gia una frequenza
bassa per scelta strategica, non e un bug).

| Chat | Asse | Fill-rate 24 slot | Slot enfatizzati |
|------|------|---------------------|----------------------|
| Sicurissimo.Online | PUBBLICO | 20-24/giorno (quasi pieno) | NEWS, CHECKLIST, VIDEO, CTA, QUIZ |
| 81+ Sicurezza sul Lavoro | PUBBLICO | 4-6/giorno (solo se news reale) | NEWS_HOOK, RENEWAL non pertinente |
| 81+ ECOSYSTEM | PUBBLICO (gruppo) | 16-20/giorno | DOMANDA_APERTA, QUIZ, SONDAGGIO, CASO_REALE, AI_QA |
| 81+ BASIC / PRO / ELITE (membership) | MEMBERSHIP | 8-12/giorno | RENEWAL, UPSELL_NUDGE, VIDEO, MISSIONE, RANK_PROGRESS |
| 81+ NETWORK (8 topic rank) | STATUS_RETE | 10-14/giorno totali sui topic | MISSIONE, LEADERBOARD, RANK_PROGRESS, CASO_REALE, CTA_HARD |
| 81+ ELITE GROUP | STATUS_RETE | 5-8/giorno | CASO_REALE alto livello, CROSS_PROMO, VIDEO_LUNGO, UPSELL verso VIP |
| 81+ VIP | STATUS_RETE | 2-3/settimana (non giornaliero) | ENTERTAINMENT sobrio, CTA_SOFT rarissime, EVENT |
| 81+ FRANCHISING | STATUS_RETE | 5-7/giorno | LEADERBOARD territoriale, RENEWAL canone, MISSIONE espansione |
| 81+ CLUB (+President) | STATUS_RETE | 2-4/settimana | EVENT, RECAP, DAO_UPDATE (solo su contenuto reale) |

**Regola guida:** il motore non forza 24 azioni ovunque. Rispetta la fisiologia già
progettata di ogni gruppo (piu in alto nella piramide, meno ma più dense le azioni).

---

# 5. FUNNEL, CONVERSIONE E ATTIVAZIONE RICORRENZE — VALUE LADDER ENGINE

## 5.1 Trigger automatici di conversione (letti dal DB, eseguiti dal bot)

| Trigger | Condizione | Azione automatica |
|---------|------------|----------------------|
| Nuovo USER81+ | SIC-ID appena creato | Invito al gruppo 81+ ECOSYSTEM + sequenza nurturing 7 giorni (gia definita nei programmi canali pubblici) |
| Uso ripetuto freebie | 3+ checklist scaricate in 14 giorni | UPSELL_NUDGE verso Membership Basic |
| Membership Basic da 60 giorni | membership_tier=Basic AND durata>=60gg | UPSELL_NUDGE verso Pro (mostra benefit aggiuntivi) |
| Vicino a rank successivo | PV attuali >= 80% soglia prossimo rank | RANK_PROGRESS con conto alla rovescia preciso |
| Pass in scadenza tra 7 giorni | sdp_scadenza - oggi <= 7 | RENEWAL_REMINDER, poi a 3 giorni, poi il giorno stesso |
| Pass scaduto | sdp_scadenza < oggi | Downgrade automatico (sezione 2.3) |
| Rank 6 SUMMIT raggiunto | network_rank=6 | Sblocco automatico invito a 81+ ELITE GROUP |
| Rank 3 interno Elite raggiunto | elite_rank_interno=3 | Sblocco automatico invito a 81+ VIP |
| Inattivita 30 giorni (gruppi pubblici) | Nessuna interazione | Sequenza di riattivazione soft (nuovo freebie/sondaggio) |
| Inattivita 60 giorni (membership/status) | Nessuna interazione | Alert a @sicurissimonewbot per contatto umano (mai solo bot su clienti paganti fermi) |

## 5.2 Scala di valore completa (fonte: LISTINO81+ ufficiale)

```
FREEBIE (0€)
  Audit Express, checklist, quiz, strumenti gratuiti per ATECO
        |
        v
TRIPWIRE (49€/mese)
  Membership Basic
        |
        v
ENTRY (990€ una tantum)
  Pacchetto Compliance Start
        |
        v
CORE (99-149€/mese oppure 1900€ una tantum)
  Membership Pro/Elite, Pacchetto Compliance Business
        |
        v
SALITA STATUS (990-1990€+)
  Sigilli NFT Bronze/Silver/Gold/Platinum
        |
        v
COMMUNITY EVOLUTA (199€ + 49-999€/mese)
  Network81+ SDK + SDP Pass per rank (8 rank)
        |
        v
ALTO VALORE RICORRENTE (1990-9990€/mese)
  Club 81+ VIP: Palladium/Iridium/Rhodium
        |
        v
ALTO TICKET UNA TANTUM (4900-19900€ + canone)
  Franchising 81+ Zone: Light/Standard/Flagship
        |
        v
UTILITY WEB3 (750 PV)
  PIX81+ Special Pack (mai investimento)
        |
        v
VERTICE (su invito/valutazione diretta)
  PRESIDENT81+ -> Socio Holding PLANB.CASH HOLDING LTD
```

Ogni azione del motore a 24 slot (sezione 4) e taggata con il gradino di questa scala:
il bot sa sempre "che prodotto sto spingendo oggi in questo slot" e non salta gradini
(non propone Franchising a un FOLLOWER appena arrivato).

---

# 6. INTEGRAZIONE GITHUB ACTIONS — PIPELINE COMPLETA

Estensione del sistema gia costruito (`socialgrowth81-daily`, `socialgrowth81-publish-telegram`).

```
04:30 UTC - socialgrowth81-daily (cron, gia esistente)
    |
    v
Legge telegram_chat_profile + telegram_action_taxonomy + tema_del_giorno
    |
    v
Genera bozza per ognuna delle 11 chat (fino a 24 slot ciascuna, secondo fill-rate)
    |
    v
[NUOVO] Chiamata a ChatGPT (motore creativo) per arricchire hook/varianti del giorno
    |
    v
Scrive coda in 04_SCHEDULED/telegram_<nome_chat>.csv, stato=BOZZA
    |
    v
Claude (io) revisiona: coerenza LISTINO81+, Semantic Guard, tono per asse, anti-ripetizione
    |
    v
Mirco approva (cambia stato in APPROVATO) - HUMAN_APPROVAL non negoziabile per PUBLISH
    |
    v
socialgrowth81-publish-telegram (manuale, il lancio = l'approvazione) pubblica via Bot API
su ognuna delle 11 chat, con throttling per non generare spam percepito
    |
    v
@sicurissimo81_bot esegue gating/inviti in tempo reale H24 (separato dal ciclo editoriale,
gira sempre, risponde a comandi utente istantaneamente)
    |
    v
@SicurissimoAI_bot (CORTEX81+) risponde H24 alle domande libere (separato, sempre attivo)
    |
    v
@sicurissimonewbot modera H24 (separato, sempre attivo)
    |
    v
@ottantuno_bot gestisce missioni/gamification H24 + pubblica quiz/sondaggi programmati
```

**Nota su "100% automatizzato":** il ciclo editoriale (cosa pubblicare) ha un solo punto umano
(l'approvazione di Mirco, che puo ridursi a un click). Tutto il resto — gating, risposte AI,
moderazione, gamification, calcolo rank, promemoria — gira **davvero H24 senza intervento**,
perche sono webhook Telegram che rispondono in tempo reale, non cicli GitHub Actions schedulati.

---

# 7. SCHEMA DATABASE (nuove tabelle, motore parametrico)

| Tabella | Contenuto |
|---------|-----------|
| `telegram_action_taxonomy` | Le 24 categorie di azione con obiettivo funnel e gradino scala valore |
| `telegram_chat_profile` | Fill-rate e categorie enfatizzate per ognuna delle 11 chat |
| `telegram_value_ladder` | Gradino, prodotto, prezzo, asse/stadio di riferimento, trigger di attivazione |
| `telegram_gating_rules` | Query di verifica per ogni asse (che campo controllare) |
| `telegram_accessi_log` | Log di ogni verifica di accesso (per audit e KPI) |

---

# 8. AZIONE UMANA RESIDUA (l'unica non automatizzabile per scelta, non per limite tecnico)

1. **PUBLISH** sui canali/gruppi: Mirco approva (un click su GitHub Actions o su un'interfaccia futura)
2. **Invito al topic PRESIDENT81+**: sempre e solo Mirco personalmente, mai automatico (per scelta strategica, sezione Club/President)
3. **Colloqui VIP/Club/President**: relazione umana diretta, il bot puo solo prenotare lo slot in calendario
4. **Creazione fisica delle chat mancanti** (topic PRESIDENT81+ dentro Club, eventuale gruppo Elite/Basic/Pro se non ancora Forum)
5. **Token bot + Secrets GitHub**: setup iniziale una tantum

Tutto il resto — 264 potenziali azioni/giorno, gating, AI, gamification, moderazione, rank,
rinnovi — gira in autonomia completa una volta acceso il sistema.

---

## SALVA COSI

```
HUB: HUB1-HUB2 / BOT AUTOMATION MASTER
FILE: 2026-07-04_81PLUS_HUB02_BOT_AUTOMATION_MASTER_4BOT_CORTEX_24AZIONI_V1_PRONTO.md
DB: telegram_action_taxonomy, telegram_chat_profile, telegram_value_ladder, telegram_gating_rules
STATO: PROGETTATO — pronto per sviluppo webhook PHP
AZIONE UMANA: assegnare i 4 bot esistenti ai ruoli sopra, sviluppo webhook (prossimo passo tecnico)
```
