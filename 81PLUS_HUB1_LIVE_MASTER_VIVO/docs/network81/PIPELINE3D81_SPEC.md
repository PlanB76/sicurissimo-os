# PIPELINE3D81+ SPEC

## VISIBILITÀ

- NETWORKER81+ (solo propria rete)
- ELITE81+ (rete espansa)
- ADMIN81+ (globale)

## TECNOLOGIA

Three.js + WebGL
Grafo 3D con nodi e connessioni.
Rendering ottimizzato mobile.

## INTERAZIONE

- Zoom in/out (pinch/scroll)
- Rotazione (drag)
- Pan (drag con 2 dita)
- Click singolo = scheda rapida utente
- Doppio click = espandi sub-downline
- Ricerca nome o SIC-ID
- Tap su nodo = info popup

## FILTRI

ruolo, status, produzione, periodo, attivi/non attivi,
GENESYS, Club, Franchising, SCOUT81+ source, PLP81+ pack

## COLORI NODI

| Ruolo/Status | Colore |
|-------------|--------|
| MEMBER81+ | Bianco |
| NETWORKER81+ | Arancione |
| ELITE81+ | Oro |
| FRANCHISING/POINT81+ | Oro con pulsazione arancione |
| CLUB81+ | Platino con pulsazione diamante |
| GENESYS | Aura ciano/viola |
| SOSPESO | Rosso scuro |
| INATTIVO | Grigio |
| DA ATTIVARE | Giallo |

## SCHEDA UTENTE (click)

nome, cognome, SIC-ID, email, Telegram, WhatsApp, ruolo, status,
membership, GENESYS level, produzione personale, produzione rete,
invitati diretti, attivi, non attivi, ultime azioni,
prossimo follow-up, origine lead SCOUT81+, pack PLP associato.

## PRIVACY

Networker: vede solo propria rete e dati consentiti.
Admin: vede tutto.
Nessun dato sensibile in export non autorizzato.

## API

GET /api/pipeline3d-data.php?user_id=&filtri=
GET /api/pipeline3d-user-card.php?target_id=
