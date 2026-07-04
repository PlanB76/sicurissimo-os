#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
TELEGRAM INFRASTRUTTURA 81+ — Mappatura dell'infrastruttura reale gia esistente
Scoperta il 2026-07-04 tramite verifica pubblica dei link condivisi da Mirco.
Crea: telegram_infrastruttura (10 chat reali), telegram_bot_ruoli (4 bot).
NOTA: nessun invite link privato viene salvato qui (vivono solo in
telegram_config.local.json, gitignored). Solo metadati pubblici (nome, bio, iscritti).
"""
import os
import sqlite3

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB = os.path.join(ROOT, "81PLUS_GLOBAL_MASTER", "81plus.net", "0-81PLUS.NET",
                  "81PLUS_GLOBAL_UNIVERSAL.db")

INFRA = [
    ("Sicurissimo.Online", "CANALE", 188, "CANALE_MADRE",
     "La Sicurezza in 1 click per tutte le attivita", "ATTIVO",
     "Canale principale da usare come madre - piu iscritti"),
    ("81+ Sicurezza sul Lavoro", "CANALE", 41, "CANALE_SECONDARIO",
     "Aggiornamenti normativi, alert, scadenze, consigli pratici. HACCP e Privacy.", "ATTIVO",
     "Valutare merge/redirect verso Sicurissimo.Online per non disperdere audience"),
    ("81+ ECOSYSTEM", "GRUPPO_PUBBLICO", 170, "FOLLOWER_USER",
     "La Tua Sicurezza in 1 Click. Automatizzi ogni obbligo, elimini errori, blindi l'azienda.", "ATTIVO",
     "Gruppo pubblico di ingresso community - il piu popolato, centro del funnel"),
    ("81+ NETWORK", "GRUPPO_PRIVATO", 2, "NETWORKER81+",
     "Il 1 Network della Sicurezza. Costruisci la tua rete in 81+.", "DA_POPOLARE",
     "Attivare modalita Forum con 8 topic IGNITE->CROWN"),
    ("81+ BASIC", "GRUPPO_PRIVATO", 5, "MEMBER81+_BASIC",
     "Gruppo membership Basic+", "SEEDATO",
     "Solo 5 membri: da collegare al bot per popolamento automatico"),
    ("81+PRO", "GRUPPO_PRIVATO", 5, "MEMBER81+_PRO",
     "Gruppo membership Pro+", "SEEDATO", ""),
    ("81+ ELITE", "GRUPPO_PRIVATO", 5, "ELITE81+",
     "Badge oro, centro di controllo, accesso HUB operativi e automazione per scalare il business.", "SEEDATO",
     "Bio coerente con Scudo Elite oro del Brand System"),
    ("81+ VIP", "GRUPPO_PRIVATO", 5, "VIP81+",
     "Diamante nero e ossidiana. Il livello dei leader e visionari. Risorse esclusive.", "SEEDATO",
     "Bio coerente con SDK Diamond - VIP confermato status separato da Elite"),
    ("81+ CLUB", "GRUPPO_PRIVATO", 5, "CLUB81+",
     "L'olimpo di 81+. Esclusivita, Lifestyle, Networking di Prestigio.", "SEEDATO",
     "Aggiungere topic riservato PRESIDENT81+ per il vertice"),
    ("81+ FRANCHISING", "GRUPPO_PRIVATO", 5, "FRANCHISEE81+",
     "Vertice dell'architettura 81+. Protocolli automatizzati, controllo del mercato, comando assoluto.", "SEEDATO",
     ""),
]

BOT_RUOLI = [
    ("@sicurissimo81_bot", "Bot orchestratore principale: gating, welcome, gamification, comandi utente, pubblicazione palinsesto", "DA_ATTIVARE"),
    ("@SicurissimoAI_bot", "Bot AI/CORTEX81+: risposte automatiche, FAQ, chiedi-all-AI", "DA_VERIFICARE"),
    ("@ottantuno_bot", "Bot legacy - verificare se attivo, valutare merge nel principale", "DA_VERIFICARE"),
    ("@sicurissimonewbot", "Bot in test/sviluppo - verificare stato prima del lancio", "DA_VERIFICARE"),
]


def main():
    con = sqlite3.connect(DB)
    cur = con.cursor()

    cur.execute("DROP TABLE IF EXISTS telegram_infrastruttura")
    cur.execute("""CREATE TABLE telegram_infrastruttura (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome_reale VARCHAR(40) NOT NULL,
        tipo VARCHAR(20) NOT NULL,
        iscritti_rilevati INTEGER NOT NULL,
        stadio_pipeline VARCHAR(30) NOT NULL,
        bio_confermata VARCHAR(300) NOT NULL,
        stato VARCHAR(20) NOT NULL,
        note VARCHAR(200) DEFAULT ''
    )""")
    cur.executemany("""INSERT INTO telegram_infrastruttura
        (nome_reale, tipo, iscritti_rilevati, stadio_pipeline, bio_confermata, stato, note)
        VALUES (?,?,?,?,?,?,?)""", INFRA)

    cur.execute("DROP TABLE IF EXISTS telegram_bot_ruoli")
    cur.execute("""CREATE TABLE telegram_bot_ruoli (
        username VARCHAR(30) PRIMARY KEY,
        ruolo VARCHAR(200) NOT NULL,
        stato VARCHAR(20) NOT NULL
    )""")
    cur.executemany("INSERT INTO telegram_bot_ruoli VALUES (?,?,?)", BOT_RUOLI)

    con.commit()
    for t in ("telegram_infrastruttura", "telegram_bot_ruoli"):
        print(f"  {t}: {cur.execute('SELECT COUNT(*) FROM ' + t).fetchone()[0]} righe")
    print("  integrity:", cur.execute("PRAGMA integrity_check").fetchone()[0])
    con.close()


if __name__ == "__main__":
    main()
