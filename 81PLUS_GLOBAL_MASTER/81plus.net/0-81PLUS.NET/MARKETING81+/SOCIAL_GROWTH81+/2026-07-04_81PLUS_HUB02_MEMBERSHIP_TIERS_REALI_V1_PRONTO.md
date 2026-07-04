# MEMBERSHIP81+ — I 6+1 TIER REALI (fonte: 81plus.net/membership.html)
## Sostituisce ogni prezzo membership usato finora (49/99/149€ erano superati)

**Data verifica:** 2026-07-04 | **Fonte:** pagina live https://81plus.net/membership.html (testo incollato da Mirco)
**Regola:** "Le membership danno accesso ai servizi 81+ (community, piattaforma corsi e documenti,
scadenziario, supporto, AUDIT81+). I corsi e i documenti acquistabili singolarmente restano su Academy81+."

---

## Tabella completa (cumulativa: ogni tier include tutto il precedente)

| # | Tier | Prezzo/mese | Posti tot. | Attivati | Disponibili | Badge NFT | Etichetta |
|---|------|-------------|-----------|----------|--------------|-----------|-----------|
| 1 | **Basic+** | 69€ | 100 | 78 | 22 | Bronze+ | — |
| 2 | **Pro+** | 139€ | 100 | 88 | 12 | Silver+ | Più scelto |
| 3 | **Elite+** | 209€ | 100 | 84 | 16 | Gold+ | Best seller |
| 4 | **Vip+** | 349€ | 100 | 71 | 29 | Platinum+ | — |
| 5 | **Royal+** | 559€ | 100 | 63 | 37 | Diamond+ | Livello massimo prima di GENESYS81+ |
| 6 | **GENESYS81+** | 1399€ | — | — | — | Special Rare+ | Non ancora disponibile |

Scarsita: **reale**, non da inventare per marketing — sono i numeri esatti del sito (posti/100, gia attivati).
Prezzi IVA inclusa, attivazione ricorrente automatica via carta/PayPal.

## Benefit chiave per tier (cumulativi)

**Basic+ (69€):** community, piattaforma corsi/documenti, aggiornamenti normativi automatici, alert scadenze, newsletter settimanale, NFT Bronze+, consulenze tecniche scontate, sconto 15% piattaforma, convenzioni, formazione specialistica, badge on-chain.

**Pro+ (139€):** tutto Basic+ + 2 consulenze tecniche incluse, scadenziario guidato, assistenza prioritaria chat/email, schede operative e template, NFT Silver+, 1 Live 81+ mensile, formazione extra, convenzioni Pro+.

**Elite+ (209€):** tutto Pro+ + supporto dedicato WhatsApp, 2 Live 81+ mensili, eventi VIP in presenza, 4 consulenze tecniche incluse, NFT Gold+, formazione extra Elite+, convenzioni Elite+.

**Vip+ (349€):** tutto Elite+ + audit periodici programmati, reportistica avanzata, onboarding assistito, SLA dedicato, revisione periodica scadenze, check documentale ricorrente, priorita aula virtuale e richieste tecniche, sessione strategica mensile, NFT Platinum+, badge on-chain.

**Royal+ (559€):** tutto Vip+ + account manager personale, interventi on-site, personalizzazioni su misura, priorita assoluta, supporto direzionale, roadmap operativa personalizzata, dashboard evoluta, report per direzione, accesso anticipato funzioni premium, NFT Diamond+.

**GENESYS81+ (1399€, non disponibile):** tutto Royal+ + regia strategica dedicata, accesso anticipato nuovi moduli, advisory riservata periodica, pilot dedicati, personalizzazioni evolute dashboard/flussi/automazioni, priorita assoluta sviluppo, sessioni private analisi operativa, roadmap 90gg personalizzata, whitelist interna, NFT Special Rare+.

---

## ⚠️ QUESTIONE APERTA — mappatura sui gruppi Telegram (da decidere con Mirco)

Oggi ho 3 soli gruppi membership mappati: 81+ BASIC, 81+PRO, 81+ ELITE (badge oro — coerente
con NFT Gold+ di Elite+, buon segnale). **Non esistono ancora gruppi dedicati per Vip+, Royal+,
GENESYS81+.**

Inoltre c'e una possibile incoerenza da verificare: il gruppo Telegram "81+ VIP" (status di rete,
bio "diamante nero e ossidiana") ha un'immagine (diamante nero) che corrisponde meglio al badge
**Diamond+ di Royal+** che al badge **Platinum+ di Vip+**. Possibilita:
1. Il gruppo "81+ VIP" e davvero per lo status di rete VIP81+ (SDK/SDP Diamond, rank 3 Elite) come
   gia mappato, e la coincidenza cromatica col Royal+ e casuale
2. Il gruppo "81+ VIP" e in realta per la membership Royal+ (Diamond+), e lo status di rete VIP81+
   non ha ancora un gruppo dedicato

Non decido da solo: serve conferma di Mirco. Nel frattempo la mappatura Telegram resta quella
gia verificata (WebFetch sulle bio reali), la tabella `membership_tiers81` nel DB e indipendente
e corretta a prescindere da come verra risolta la mappatura gruppi.

---

## SALVA COSI

```
HUB: HUB2 / MEMBERSHIP81+ REALE
FILE: 2026-07-04_81PLUS_HUB02_MEMBERSHIP_TIERS_REALI_V1_PRONTO.md
DB: tabella membership_tiers81 (6 righe) + telegram_value_ladder aggiornata (prezzi corretti)
STATO: PREZZI CONFERMATI DA MIRCO — mappatura gruppi Telegram per Vip+/Royal+/GENESYS81+ ancora aperta
```
