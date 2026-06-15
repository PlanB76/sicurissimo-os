# TERRITORYMAP81+ SPEC

## VISIBILITÀ

- ELITE81+ con franchise_status = eligible o active
- ADMIN81+ sempre

## CONTENUTO MAPPA

Italia 3D con:
- Regioni, province, comuni
- POINT81+ attivi (verde)
- POINT81+ da attivare (giallo)
- POINT81+ in attivazione (arancione)
- Aree libere (grigio chiaro)
- Aree riservate (viola)
- Aree sospese (rosso)
- Prospect SCOUT81+ sovrapposti
- PLP territorio disponibili
- Networker attivi/non attivi per area
- Lead storici da riattivare

## INTERAZIONE

- Zoom regioni/province/comuni
- Click area = dettaglio area
- Hover = stats rapide
- Ricerca comune/provincia
- Filtro stato area
- Filtro Networker

## SCHEDA AREA

- Nome area
- Status
- POINT81+ titolare (se presente)
- Networker attivi nell'area
- Prospect SCOUT81+ disponibili
- PV+ potenziali da territorio
- Storico attivazioni area

## API

GET /api/territory-map-data.php?regione=&provincia=
GET /api/point81-card.php?area_id=
