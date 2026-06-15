# SCOUT81+ MAP SPEC

## TECNOLOGIA

Mappa Italia 3D con Three.js o Leaflet.
Heatmap opportunità per territorio.

## LIVELLI MAPPA

- Italia completa
- Regione
- Provincia
- Comune

## VISUALIZZAZIONI

- Pin aziende (colore = stato prospect)
- Cluster per territorio
- Heatmap opportunità
- Score prospect per area
- Categoria ATECO per area
- Rischio basso/medio/alto

## FILTRI

regione, provincia, comune, ATECO, macrosettore, rischio,
dimensione azienda, HACCP applicabile, edilizia, privacy,
professionisti, artigiani, stato prospect
(freddi/tiepidi/assegnati/lavorati/da richiamare/con audit/
convertiti/persi/da riattivare)

## VISTE DISPONIBILI

- Vista mappa (default)
- Vista lista
- Vista pipeline
- Vista territorio
- Vista ATECO
- Vista follow-up
- Vista opportunità calde
- Vista lead storici
- Vista prospect acquistabili tramite PLP81+

## API ENDPOINT

GET /api/scout-map-data.php?regione=&provincia=&comune=&ateco=&rischio=&status=
GET /api/scout-prospects.php?filtri=...
GET /api/scout-prospect-card.php?id=
POST /api/scout-save-filter.php
POST /api/scout-assign-prospect.php
