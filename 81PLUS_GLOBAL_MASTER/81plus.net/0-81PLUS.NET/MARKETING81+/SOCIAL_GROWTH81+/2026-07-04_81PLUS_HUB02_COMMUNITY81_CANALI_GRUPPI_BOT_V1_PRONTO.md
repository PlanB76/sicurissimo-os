# COMMUNITY81+ — CANALI, GRUPPI TELEGRAM E BOT MANAGER
## Architettura definitiva della community a status (validata da Mirco 2026-07-04)

**Data:** 2026-07-04 | **Stato:** ATTIVO | **HUB:** HUB2 / SOCIAL_GROWTH81+
**Fonti interne:** master-vivo/03_RUOLI_STATUS_GATING, 07_GAMIFICATION_MASTER, 09_NETWORK_CLUB_ZONE, 10_PIANI_COMPENSI

---

## 1. GLI 8 CHAT DA COSTRUIRE (struttura definitiva)

| # | Tipo | Nome | Chi entra | Obiettivo |
|---|------|------|-----------|-----------|
| 1 | Canale PUBBLICO | SICURISSIMO TG 81+ | Tutti | Canale madre: news, palinsesto, quiz, autorevolezza, ingresso funnel |
| 2 | Gruppo PRIVATO | NETWORKER81+ | Networker con SDK81+ + Pass PRO+ (rank 1-8) | Network marketing etico: script, lead, formazione rete, rank |
| 3 | Gruppo PRIVATO | MEMBER81+ BASIC | Membership Basic+ attiva | Scadenze, corsi base, documenti, primo metodo |
| 4 | Gruppo PRIVATO | MEMBER81+ PRO | Membership Pro+ attiva | Audit, strumenti avanzati, priorita operativa |
| 5 | Gruppo PRIVATO | MEMBER81+ ELITE | Membership Elite+ attiva | Advisory, casi studio, accesso prioritario |
| 6 | Gruppo PRIVATO | MEMBER81+ VIP | Clienti VIP / multi-sede | Regia unica, servizi premium, eventi riservati |
| 7 | Gruppo PRIVATO | FRANCHISEE81+ | Titolari POINT81+ / franchising attivo | Territorio, modello operativo, presidio locale |
| 8 | Gruppo PRIVATO | CLUB81+ | SDK ROYAL + Pass ROYAL+ / invito diretto | Vertice: visione, partnership, ecosistema |

La scala di crescita (piramide Maslow + ruota della vita):

```
SCONOSCIUTO/FOLLOWER → canale SICURISSIMO TG 81+ (pubblico)
        ↓ registrazione SIC-ID su 81plus.net (HUB1)
MEMBER BASIC → MEMBER PRO → MEMBER ELITE → MEMBER VIP
        ↓ (in parallelo, chi sviluppa rete)
NETWORKER81+ (rank 1-8) → FRANCHISEE81+ (rank 6+, POINT81+) → CLUB81+
```

Ruoli cumulativi: chi sale mantiene l'accesso ai gruppi inferiori.
Pass scaduto: status storico mantenuto, accesso operativo limitato, niente provvigioni.
Il bot sposta le persone di gruppo quando cambia lo status nel DB (tabella user81).

### Descrizioni pronte (bio da incollare)

- **SICURISSIMO TG 81+**: "Sicurezza, HACCP, privacy e appalti senza fuffa. Ogni giorno: cosa controllare, cosa scade, cosa fare prima. Dal 2003. Parti da 81plus.net."
- **NETWORKER81+**: "Segnala valore, non fumo. Script, materiali, formazione e rank per costruire la tua rete in modo etico (L.173/2005)."
- **MEMBER81+ BASIC**: "Area riservata Member Basic+. Scadenze, corsi, documenti e primo metodo 81+."
- **MEMBER81+ PRO**: "Area riservata Member Pro+. Audit, strumenti avanzati e priorita operativa."
- **MEMBER81+ ELITE**: "Area riservata Member Elite+. Advisory, casi studio e accesso prioritario."
- **MEMBER81+ VIP**: "Area VIP. Regia unica per aziende strutturate e multi-sede. Eventi riservati."
- **FRANCHISEE81+**: "Il territorio non ha bisogno di volantini. Ha bisogno di presidi. Area riservata titolari POINT81+."
- **CLUB81+**: "Il vertice della community 81+. Solo su requisiti o invito diretto."

---

## 2. IL BOT MANAGER — @Sicurissimo81Bot

Un solo bot, admin in tutti gli 8 chat. Gestisce tutto via Bot API (niente browser).

### Funzioni core

| Area | Funzione |
|------|----------|
| Onboarding | /start → benvenuto, chiede email → match con user81 nel DB → conferma SIC-ID → link registrazione HUB1 (81plus.net) |
| Gating status | Legge membership/rank dal DB → link invito monouso al gruppo giusto → rimozione automatica se il pass scade (downgrade cortese) |
| Welcome | Messaggio automatico a ogni nuovo membro: regole, missione del giorno, CTA |
| Gamification | /pv (saldo PV+), /rank, /missioni, /badge. +PV+ per risposta utile premiata da admin. Streak e leaderboard settimanale |
| Anti-spam | No link esterni per non-admin, filtro parole Semantic Guard (investimento, rendimento, guadagno garantito...), warn → mute → kick |
| Funnel | Parole chiave (AUDIT, HACCP, PRIVACY, CORSO, PACK) → risposta con link LISTINO81+ corretto. DM SOLO con consenso |
| Double opt-in | Nessun DM promozionale senza consenso registrato (user81.consenso_wa = 1) |
| Palinsesto | Post programmati dai workflow GitHub sul canale 1, quiz e sondaggi automatici |
| Regia | Azioni sensibili loggate. PUBLISH e inviti massivi = HUMAN_APPROVAL di Mirco |

### Comandi utente

```
/start     benvenuto + collegamento SIC-ID
/pv        saldo PV+ e activity score
/rank      rank network e prossimo obiettivo
/missioni  missioni attive
/badge     badge conquistati
/status    il tuo livello e i gruppi a cui hai accesso
/aiuto     FAQ e contatto WhatsApp opt-in
```

### Missioni attive (da GAMIFICATION_MASTER)

Verifica email, completa profilo, entra in Telegram, fai audit, guarda 1 video ogni 2 giorni,
partecipa a webinar/evento, invita contatto, rispondi al sondaggio, completa checklist ATECO,
condividi video YouTube, completa mini corso, prenota call, partecipa Q&A, aiuta la community.

### Regole anti-rischio (vincolanti)

PV+ = activity score, MAI guadagno o rendimento. No gambling, no premi casuali a pagamento,
no promessa payout, no pressione, no falso scarcity. Network etico L.173/2005.

---

## 3. AUTOMAZIONE (tutto su GitHub, niente n8n, niente browser)

1. **GitHub Actions** `socialgrowth81-daily`: genera pacchetto e coda dal palinsesto DB (cron 04:30 UTC)
2. **Mirco/Claude** compilano testi e mettono stato=APPROVATO
3. **GitHub Actions** `socialgrowth81-publish-telegram` (manuale = HUMAN_APPROVAL): pubblica sul canale
4. **Bot webhook** (PHP su Hostinger, stesso stack di 81global): comandi, welcome, gating, anti-spam in tempo reale
5. **DB unico** (user81 + socialgrowth81_*): fonte di verita per status, inviti e gamification

### Cosa serve da Mirco per accendere tutto (20 minuti)

1. Creare gli 8 chat dalla tabella sopra (ordine 1→8), incollando le bio pronte
2. @BotFather → /newbot → nome: Sicurissimo81Bot → copiare il TOKEN
3. Aggiungere il bot come ADMIN in tutti gli 8 chat
4. Passare a Claude il token (in chat o nei GitHub Secrets) + inoltrare un messaggio da ogni chat (per i chat_id)
5. GitHub Secrets: TELEGRAM_BOT_TOKEN + TELEGRAM_CHAT_ID (canale madre)

Da quel momento Claude gestisce e automatizza tutto via API.

---

## SALVA COSI

```
HUB: HUB2 / SOCIAL_GROWTH81+ / COMMUNITY81+
FILE: 2026-07-04_81PLUS_HUB02_COMMUNITY81_CANALI_GRUPPI_BOT_V1_PRONTO.md
REPO: 81PLUS_GLOBAL_MASTER/81plus.net/0-81PLUS.NET/MARKETING81+/SOCIAL_GROWTH81+/
DB: socialgrowth81_gruppi aggiornata a 8 chat con requisiti di accesso
STATO: PRONTO — in attesa creazione chat + token bot da Mirco
```
