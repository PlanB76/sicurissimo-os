# PV+ BOOSTER SYSTEM

## DEFINIZIONE

PV+ non sono soldi. Non sono rendimento. Non sono provvigioni.
PV+ sono reward interni per missioni, accessi, sblocchi, status,
Career Program ed Equilibrium Program.

## FORMULA

PV+ finali = PV+ base missione × moltiplicatore valido

Moltiplicatore valido = min(cap ruolo, booster attivo più alto applicabile)
Non sommare booster senza limite.

## CAP PER RUOLO

| Ruolo | Cap max |
|-------|---------|
| MEMBER81+ | x2 |
| NETWORKER81+ | x3 |
| ELITE81+ | x4 |
| GENESYS promo | x5 |

## EARNING RATE

| Condizione | Rate |
|------------|------|
| MEMBER81+ base | x1 |
| BASIC+ attivo | x1.2 |
| PRO+ attivo | x1.5 |
| ELITE+ attivo | x2 |
| NETWORKER candidato | x1.5 |
| NETWORKER attivo (Membership+SDK++SDP+) | x2 |
| NETWORKER con Piano Marketing completato | x2.5 |
| NETWORKER avanzato Career Program | x3 |
| ELITE candidato | x2.5 |
| ELITE con SDP+ Royal | x3 |
| ELITE con Club81+ | x3.5 |
| ELITE con Franchising/POINT81+ | x4 |
| GENESYS_MEMBER | x1.5 |
| GENESYS_NETWORKER | x2 |
| GENESYS_LEADER | x3 |
| GENESYS_FOUNDER | x4 |
| GENESYS promo speciale | x5 |

## CAREER PROGRAM

| Livello | Nome | Rate |
|---------|------|------|
| L0 | Explorer81+ | x1 |
| L1 | Starter81+ | x1.2 |
| L2 | Operator81+ | x1.5 |
| L3 | Builder81+ | x2 |
| L4 | Developer81+ | x2.5 |
| L5 | Leader81+ | x3 |
| L6 | Area81+ | x3.5 |
| L7 | Regional81+ | x4 |
| L8 | Genesis/National81+ | x5 (solo promo approvate) |

## MISSIONI SCOUT81+/PLP INTEGRATE

- Primo accesso SCOUT81+: +500 PV+
- Primo filtro ATECO salvato: +100 PV+
- Prima mappa territorio salvata: +200 PV+
- Primo prospect lavorato: +300 PV+
- Primo pack PLP attivato: +500 PV+
- 10 follow-up lead: +1000 PV+
- Primo audit da prospect: +500 PV+
- Primo BASIC+ da vendita reale: +2000 PV+
- Pipeline aggiornata 7 giorni consecutivi: +1000 PV+
- Territorio aggiornato: +500 PV+
- Piano marketing completato: +2000 PV+
- Piano equilibrio completato: +1000 PV+

## IDEMPOTENZA

Ogni accredito PV+ deve essere idempotente.
Gestito lato server PHP.
Mai via JS client-side.
Log ogni accredito con missione_id e user_id.
