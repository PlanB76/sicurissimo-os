#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
PIPELINE GLOBALE 81+ — Integrazione rank nel DB unico universale
Crea e popola: career81_ranks (L0-L8), genesys81_ranks (4 territoriali),
equilibrium81_status (6 status).
Fonti: CAREER_PROGRAM81_SPEC.md, genesys_rank.json, EQUILIBRIUM_PROGRAM81_SPEC.md
"""
import os
import sqlite3

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB = os.path.join(ROOT, "81PLUS_GLOBAL_MASTER", "81plus.net", "0-81PLUS.NET",
                  "81PLUS_GLOBAL_UNIVERSAL.db")

CAREER = [
    (0, "Explorer81+",         "Registrazione completata",              "x1"),
    (1, "Starter81+",          "BASIC+ attivo, audit completato",       "x1.2"),
    (2, "Operator81+",         "PRO+ attivo, 3 mesi attivita",          "x1.5"),
    (3, "Builder81+",          "ELITE+ attivo, 5 referral convertiti",  "x2"),
    (4, "Developer81+",        "SDK+ attivo, 10 lead lavorati",         "x2.5"),
    (5, "Leader81+",           "SDP+ attivo, 20 attivi in rete",        "x3"),
    (6, "Area81+",             "Franchising/POINT81+ eligible",         "x3.5"),
    (7, "Regional81+",         "Area attiva 6 mesi",                    "x4"),
    (8, "Genesis/National81+", "Su invito e approvazione direzione",    "x5 solo promo"),
]
# Regole: conferma fatturato 6 mesi (1 salto ok), retrocessione max 1 livello, L3 = pavimento.

GENESYS = [
    (1, "COMMUNAL",   "1 comune",    1, 2000,  3,   "",             500,   5,  50,  6),
    (2, "PROVINCIAL", "1 provincia", 1, 6000,  10,  "3 Communal",   1500,  10, 150, 6),
    (3, "REGIONAL",   "1 regione",   1, 15000, 30,  "3 Provincial", 5000,  15, 400, 6),
    (4, "NATIONAL",   "Italia",      1, 40000, 100, "5 Regional",   15000, 20, 1200, 6),
]
# Struttura solo per salire. Benefit = PV e opportunita, mai denaro verso l'alto.

EQUILIBRIUM = [
    (1, "Starter", "Registrazione",               "x1.2"),
    (2, "Active",  "30 giorni attivita",          "x1.5"),
    (3, "Focus",   "Piano equilibrio completato", "x2"),
    (4, "Master",  "3 mesi Focus",                "x3"),
    (5, "Elite",   "6 mesi Master",               "x4 permanente"),
    (6, "Special", "Promo approvata direzione",   "x5"),
]


def main():
    con = sqlite3.connect(DB)
    cur = con.cursor()

    cur.execute("DROP TABLE IF EXISTS career81_ranks")
    cur.execute("""CREATE TABLE career81_ranks (
        livello INTEGER PRIMARY KEY, nome VARCHAR(30) NOT NULL,
        requisiti VARCHAR(120) NOT NULL, booster_pvplus VARCHAR(20) NOT NULL)""")
    cur.executemany("INSERT INTO career81_ranks VALUES (?,?,?,?)", CAREER)

    cur.execute("DROP TABLE IF EXISTS genesys81_ranks")
    cur.execute("""CREATE TABLE genesys81_ranks (
        ordine INTEGER PRIMARY KEY, rank VARCHAR(20) NOT NULL,
        territorio VARCHAR(20) NOT NULL, posti INTEGER NOT NULL,
        volume_personale INTEGER NOT NULL, aziende_attive INTEGER NOT NULL,
        struttura_per_salire VARCHAR(30) NOT NULL,
        bonus_pv_ingresso INTEGER NOT NULL, sconto_servizi_pct INTEGER NOT NULL,
        bonus_pv_mensile INTEGER NOT NULL, mantenimento_mesi INTEGER NOT NULL)""")
    cur.executemany("INSERT INTO genesys81_ranks VALUES (?,?,?,?,?,?,?,?,?,?,?)", GENESYS)

    cur.execute("DROP TABLE IF EXISTS equilibrium81_status")
    cur.execute("""CREATE TABLE equilibrium81_status (
        ordine INTEGER PRIMARY KEY, status VARCHAR(20) NOT NULL,
        requisiti VARCHAR(60) NOT NULL, booster VARCHAR(20) NOT NULL)""")
    cur.executemany("INSERT INTO equilibrium81_status VALUES (?,?,?,?)", EQUILIBRIUM)

    con.commit()
    for t in ("career81_ranks", "genesys81_ranks", "equilibrium81_status"):
        n = cur.execute("SELECT COUNT(*) FROM " + t).fetchone()[0]
        print(f"  {t}: {n} righe")
    print("  integrity:", cur.execute("PRAGMA integrity_check").fetchone()[0])
    con.close()


if __name__ == "__main__":
    main()
