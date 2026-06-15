# PLP81+ PACK SPEC

## DEFINIZIONE

PLP81+ = Power Lead Prospect 81+

PLP81+ consegna pack prospect profilati.
Non promette clienti. Non promette vendite.

## PACK BASE

| Pack | Prospect | PV | PV+ Bonus |
|------|----------|-----|-----------|
| PLP Start | 100 | 29 | 100 |
| PLP Pro | 250 | 49 | 250 |
| PLP Max | 500 | 99 | 500 |

## PACK SPECIALI

| Pack | Segmento |
|------|----------|
| PLP Edilizia | Cantieri, costruzioni, subappalti |
| PLP HACCP | Ristorazione, bar, laboratori alimentari |
| PLP Professionisti | Studi, uffici, autonomi |
| PLP Artigiani | Artigiani, piccole botteghe |
| PLP Territorio | Provincia/regione specifica |
| PLP GENESYS Special | Lead storici caldi, early adopter |
| PLP Follow-up Lead Storici | Clienti SICURISSIMO81+ inattivi |

## COMPOSIZIONE STANDARD

- 50% generici ATECO misti
- 15% edilizia
- 15% HACCP
- 10% professionisti e uffici
- 10% artigiani e autonomi

## MECCANISMO

1. Networker acquista pack con PV dal wallet
2. Sistema assegna prospect al Networker
3. Networker vede prospect in SCOUT81+ / Pipeline
4. Networker lavora i prospect
5. Conversione genera provvigione su vendita reale
6. PV+ bonus accreditati se missione completata

## API

POST /api/plp-buy-pack.php {pack_id, user_id}
GET /api/networker-leads.php {user_id, status, page}
