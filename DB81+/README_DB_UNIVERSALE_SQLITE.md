# 81+ GLOBAL UNIVERSAL — DB UNICO SQLite

**POSIZIONE ATTUALE DEL DB (dal 2026-07-04):**
`81PLUS_GLOBAL_MASTER/81plus.net/0-81PLUS.NET/81PLUS_GLOBAL_UNIVERSAL.db`
Percorso locale Mirco: `C:\81PLUS_GLOBAL_MASTER\81plus.net\0-81PLUS.NET\`
Insieme al DB vive la lista unica contatti USER81+ in formato MD.

Database unico portabile per tutto l'ecosistema 81+. Gira su qualsiasi host senza server MySQL.
Consolida in un solo file tutti gli schemi SQL sparsi nel progetto (HUB1 core, LEX81+, LEADGEN81+, SFERA81+, auth, dashboard, academy, gamification, pagamenti, web3).

## File

| File | Cosa e |
|------|--------|
| `81PLUS_GLOBAL_UNIVERSAL.db` | Il database SQLite pronto all'uso (209 tabelle, 4 view, 372 indici) |
| `81PLUS_GLOBAL_UNIVERSAL.schema.sql` | Schema consolidato leggibile (rigenerabile) |
| `81PLUS_GLOBAL_UNIVERSAL.report.txt` | Report build: tabelle, righe seed, origine, statement saltati |
| `build_sqlite_universal.py` | Script che rigenera il DB dai sorgenti MySQL |

## Contenuto verificato

- 209 tabelle, integrity_check = ok
- 4179 lead reali importati (tabella `leads`, SIC-ID-X-00000001 → 00004179)
- LEX81+ completo: 24 norme, 12 obblighi, 9 sanzioni, 10 categorie, 28 mappe ATECO, 18 corsi, 12 documenti, 10 servizi
- LEADGEN81+: 25 flow, 8 step email, 10 segmenti
- SFERA81+: 22 missioni, 8 aree LIFEWHEEL, 5 livelli ESCALATION
- system_config, knowledge_base, dashboard, x81, membership seed inclusi

## Uso

### Da riga di comando (se hai sqlite3)
```
sqlite3 81PLUS_GLOBAL_UNIVERSAL.db "SELECT sic_id, nome FROM leads LIMIT 5;"
```

### Da Python
```python
import sqlite3
con = sqlite3.connect("81PLUS_GLOBAL_UNIVERSAL.db")
for r in con.execute("SELECT sic_id, nome, stato FROM leads LIMIT 10"):
    print(r)
```

### Da PHP (host senza MySQL)
```php
$db = new PDO('sqlite:81PLUS_GLOBAL_UNIVERSAL.db');
$stmt = $db->query("SELECT nome FROM lex81_norms LIMIT 5");
```

## Fonte unica

Dal 2026-07-04 questo `.db` e la FONTE UNICA del database 81+ nel repo.
Tutti i 53 file `.sql` sparsi (schemi MySQL, patch, delta, install) sono stati consolidati qui dentro e rimossi.
Il file `.db` contiene sia lo schema sia i dati (4179 lead + tutti i seed). La cronologia dei sorgenti resta recuperabile da git.

`build_sqlite_universal.py` e conservato come documentazione della provenienza (mostra come e stato generato e da quali fonti). I percorsi in `SOURCES` puntano a file ora rimossi, quindi lo script non rigenera piu dai sorgenti: il `.db` e gia il risultato finale.

Per ispezionare o modificare lo schema, usa `81PLUS_GLOBAL_UNIVERSAL.schema.sql` (DDL leggibile) oppure opera direttamente sul `.db`.

## Note tecniche di conversione

- `AUTO_INCREMENT` → `INTEGER PRIMARY KEY AUTOINCREMENT`
- `ENUM(...)` / `SET(...)` → `TEXT`
- `UNSIGNED`, `ON UPDATE CURRENT_TIMESTAMP`, opzioni `ENGINE`/`CHARSET`/`COMMENT` rimossi
- `KEY` / `INDEX` / `UNIQUE KEY` inline → `CREATE INDEX` separati
- `NOW()` → `CURRENT_TIMESTAMP`
- I TRIGGER MySQL sono logica applicativa e vengono gestiti a livello codice, non nel DB portabile
- Le FOREIGN KEY sono mantenute ma l'enforcement e disattivato in build (PRAGMA foreign_keys=OFF)

## Relazione con la produzione

Il DB MySQL `u173050672_81plusglobal` su Hostinger resta la fonte di verita in produzione (FASE ZERO81+, regola 1).
Questo SQLite universale e il mirror portabile per sviluppo, test locale, backup a file singolo e host senza MySQL.
