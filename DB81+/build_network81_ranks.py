#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
PIPELINE DEFINITIVA 81+ — Rank ufficiali nel DB unico
Fonte: SDP+ Recurrency Developer Pass (Brand System, validato visivamente 2026-07-04)
+ pipeline definitiva dichiarata da Mirco (USER -> MEMBER -> NETWORKER 8 rank ->
ELITE 5 rank -> VIP 5 rank -> FRANCHISEE -> CLUB -> PRESIDENT/SOCIO HOLDING).
Crea: network81_ranks (8 rank con soglie PV), pipeline81_stadi (10 stadi definitivi).
"""
import os
import sqlite3

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB = os.path.join(ROOT, "81PLUS_GLOBAL_MASTER", "81plus.net", "0-81PLUS.NET",
                  "81PLUS_GLOBAL_UNIVERSAL.db")

# Dal visual SDP+ (pass fisici): rank, titolo carriera, soglia PV, materiale pass
NETWORK_RANKS = [
    (1, "IGNITE",  "Start",     0,      "Bronzo"),
    (2, "RISE",    "Builder",   1000,   "Argento olografico"),
    (3, "DRIVE",   "Performer", 5000,   "Oro circuito"),
    (4, "SCALE",   "Leader",    15000,  "Acciaio inciso"),
    (5, "PEAK",    "Manager",   40000,  "Blu diamante"),
    (6, "SUMMIT",  "Director",  75000,  "Verde smeraldo"),
    (7, "LEGACY",  "Executive", 150000, "Rosso rubino"),
    (8, "CROWN",   "Elite",     300000, "Diamanti + 3 corone"),
]

# Pipeline definitiva (dichiarazione Mirco 2026-07-04 + scudi Brand System)
PIPELINE = [
    (0,  "SCONOSCIUTO",    "Traffico social",                                          "-",                    "-"),
    (1,  "FOLLOWER",       "Segue canale pubblico",                                    "-",                    "-"),
    (2,  "USER81+",        "Registrazione HUB1: SIC-ID, wallet, foto, nome, email",    "Tessera SIC-ID",       "Gratis"),
    (3,  "MEMBER81+",      "Membership attiva: Basic+ / Pro+ / Elite+",                "Scudo Member (bronzo)","Membership ricorrente"),
    (4,  "NETWORKER81+",   "SDK+ + membership attiva + SDP+ pack attivo. 8 rank IGNITE->CROWN", "Scudo Networker (acciaio)", "SDK 199 PV + Pass"),
    (5,  "ELITE81+",       "Dal rank 6 Network (SUMMIT) + SDK Royal + membership + SDP Royal. 5 rank", "Scudo Elite (oro)", "SDK Royal + Pass Royal"),
    (6,  "VIP81+",         "Dal rank 3 di Elite + SDK Diamond + membership + SDP Diamond. 5 rank", "Scudo VIP (da creare)", "SDK Diamond + Pass Diamond"),
    (7,  "FRANCHISEE81+",  "POINT81+ territoriale (requisiti da regolamento)",         "Badge POINT81+",       "Contratto franchising"),
    (8,  "CLUB81+",        "Accesso club su requisiti o invito",                       "Scudo Club (platino/diamanti)", "SDK Royal Club"),
    (9,  "PRESIDENT81+",   "Vertice community, anticamera societaria",                 "Scudo President (nero/corona)", "Invito direzione"),
    (10, "SOCIO_HOLDING",  "Partnership societaria PLANB.CASH HOLDING LTD",            "Contratto",            "Solo contratti legali"),
]


def main():
    con = sqlite3.connect(DB)
    cur = con.cursor()

    cur.execute("DROP TABLE IF EXISTS network81_ranks")
    cur.execute("""CREATE TABLE network81_ranks (
        rank INTEGER PRIMARY KEY, nome VARCHAR(10) NOT NULL,
        titolo VARCHAR(15) NOT NULL, soglia_pv INTEGER NOT NULL,
        pass_visual VARCHAR(30) NOT NULL)""")
    cur.executemany("INSERT INTO network81_ranks VALUES (?,?,?,?,?)", NETWORK_RANKS)

    cur.execute("DROP TABLE IF EXISTS pipeline81_stadi")
    cur.execute("""CREATE TABLE pipeline81_stadi (
        stadio INTEGER PRIMARY KEY, status VARCHAR(20) NOT NULL,
        requisiti VARCHAR(120) NOT NULL, badge_visual VARCHAR(40) NOT NULL,
        prodotto_ingresso VARCHAR(40) NOT NULL)""")
    cur.executemany("INSERT INTO pipeline81_stadi VALUES (?,?,?,?,?)", PIPELINE)

    con.commit()
    for t in ("network81_ranks", "pipeline81_stadi"):
        print(f"  {t}: {cur.execute('SELECT COUNT(*) FROM ' + t).fetchone()[0]} righe")
    print("  integrity:", cur.execute("PRAGMA integrity_check").fetchone()[0])
    con.close()


if __name__ == "__main__":
    main()
