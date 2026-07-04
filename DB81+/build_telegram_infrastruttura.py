#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
TELEGRAM INFRASTRUTTURA 81+ — Mappatura dell'infrastruttura reale gia esistente
Scoperta il 2026-07-04 tramite verifica pubblica dei link condivisi da Mirco.
Crea: telegram_infrastruttura (11 chat reali), telegram_bot_ruoli (4 bot).
NOTA: nessun invite link privato viene salvato qui (vivono solo in
telegram_config.local.json, gitignored). Solo metadati pubblici (nome, bio, iscritti).

CORREZIONE 2026-07-04 (seconda passata): esistono DUE gruppi "Elite" distinti,
confermato da Mirco con un nuovo link:
  - "81+ ELITE" (bio: badge oro, HUB operativi) = MEMBERSHIP Elite+ (asse MEMBERSHIP)
  - "81+ ELITE GROUP" (bio: Il Vertice Strategico) = status di rete ELITE81+ (asse STATUS_RETE)
"""
import os
import sqlite3

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB = os.path.join(ROOT, "81PLUS_GLOBAL_MASTER", "81plus.net", "0-81PLUS.NET",
                  "81PLUS_GLOBAL_UNIVERSAL.db")

# nome, tipo, iscritti, stadio_pipeline, asse, bio, stato, gating_requisito, note
INFRA = [
    ("Sicurissimo.Online", "CANALE", 188, "FOLLOWER", "PUBBLICO",
     "La Sicurezza in 1 click per tutte le attivita", "ATTIVO",
     "Nessuno - aperto a tutti",
     "Canale madre, piu iscritti"),
    ("81+ Sicurezza sul Lavoro", "CANALE", 41, "FOLLOWER", "PUBBLICO",
     "Aggiornamenti normativi, alert, scadenze, consigli pratici. HACCP e Privacy.", "ATTIVO",
     "Nessuno - aperto a tutti",
     "Secondario - valutare merge con canale madre"),
    ("81+ ECOSYSTEM", "GRUPPO", 170, "USER81+", "PUBBLICO",
     "La Tua Sicurezza in 1 Click. Automatizzi ogni obbligo, elimini errori, blindi l'azienda.", "ATTIVO",
     "Aperto oggi - SIC-ID consigliato per funnel completo",
     "USER81+ accede sia al canale pubblico che a questo gruppo"),
    ("81+ BASIC", "GRUPPO", 5, "MEMBER81+", "MEMBERSHIP",
     "Gruppo membership Basic+", "SEEDATO",
     "Membership Basic+ attiva (49 euro/mese)", ""),
    ("81+PRO", "GRUPPO", 5, "MEMBER81+", "MEMBERSHIP",
     "Gruppo membership Pro+", "SEEDATO",
     "Membership Pro+ attiva (99 euro/mese)", ""),
    ("81+ ELITE", "GRUPPO", 5, "MEMBER81+", "MEMBERSHIP",
     "Badge oro, centro di controllo, accesso HUB operativi e automazione per scalare il business.", "SEEDATO",
     "Membership Elite+ attiva (149 euro/mese)",
     "CORRETTO 2026-07-04: e la membership Elite+, NON lo status di rete ELITE81+"),
    ("81+ NETWORK", "GRUPPO", 2, "NETWORKER81+", "STATUS_RETE",
     "Il 1 Network della Sicurezza. Costruisci la tua rete in 81+.", "DA_POPOLARE",
     "SDK 199 euro + SDP Pass mensile (IGNITE 49 -> CROWN 999) + Membership attiva",
     "Modalita Forum con 8 topic rank IGNITE->CROWN"),
    ("81+ ELITE GROUP", "GRUPPO", 5, "ELITE81+", "STATUS_RETE",
     "Il Vertice Strategico. Accesso riservato all'eccellenza. Centro di comando dove la strategia diventa vantaggio competitivo. Analisi, automazione avanzata, networking di alto livello.", "SEEDATO",
     "Rank 6 SUMMIT Network81+ + SDK Royal + SDP Royal Pass + Membership attiva",
     "CORRETTO 2026-07-04: e lo status di rete ELITE81+ (5 rank), NON la membership. Link nuovo confermato da Mirco."),
    ("81+ VIP", "GRUPPO", 5, "VIP81+", "STATUS_RETE",
     "Diamante nero e ossidiana. Il livello dei leader e visionari. Risorse esclusive.", "SEEDATO",
     "Rank 3 interno ELITE81+ + SDK Diamond + SDP Diamond Pass + Membership attiva",
     "5 rank proposti: Ossidiana, Onice, Tormalina Nera, Spinello Nero, Diamante Nero"),
    ("81+ FRANCHISING", "GRUPPO", 5, "FRANCHISEE81+", "STATUS_RETE",
     "Vertice dell'architettura 81+. Protocolli automatizzati, controllo del mercato, comando assoluto.", "SEEDATO",
     "Contratto POINT81+ attivo (Light 4900+149/mese, Standard 9900+390/mese, Flagship 19900+590/mese)",
     ""),
    ("81+ CLUB", "GRUPPO", 5, "CLUB81+/PRESIDENT81+", "STATUS_RETE",
     "L'olimpo di 81+. Esclusivita, Lifestyle, Networking di Prestigio.", "SEEDATO",
     "Canone Club: Palladium 1990/mese, Iridium 4990/mese, Rhodium 9990/mese. Topic President solo su invito diretto Mirco.",
     "Topic riservato PRESIDENT81+ da creare dentro questo gruppo"),
]

BOT_RUOLI = [
    ("@sicurissimo81_bot", "Bot orchestratore principale: gating status/membership/SDP, welcome, gamification, comandi utente, pubblicazione palinsesto, funnel/conversione", "DA_ATTIVARE"),
    ("@SicurissimoAI_bot", "Bot AI/CORTEX81+: motore conversazionale, FAQ, assistenza, problem solving, Q&A in tempo reale", "DA_VERIFICARE"),
    ("@sicurissimonewbot", "Bot moderazione/community: anti-spam, controllo regole, escalation, gestione conflitti, ambassador", "DA_VERIFICARE"),
    ("@ottantuno_bot", "Bot gamification/engagement: missioni, quiz, sondaggi, badge, leaderboard, intrattenimento segmentato per status", "DA_VERIFICARE"),
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
        asse VARCHAR(20) NOT NULL,
        bio_confermata VARCHAR(300) NOT NULL,
        stato VARCHAR(20) NOT NULL,
        gating_requisito VARCHAR(200) NOT NULL,
        note VARCHAR(250) DEFAULT ''
    )""")
    cur.executemany("""INSERT INTO telegram_infrastruttura
        (nome_reale, tipo, iscritti_rilevati, stadio_pipeline, asse, bio_confermata, stato, gating_requisito, note)
        VALUES (?,?,?,?,?,?,?,?,?)""", INFRA)

    cur.execute("DROP TABLE IF EXISTS telegram_bot_ruoli")
    cur.execute("""CREATE TABLE telegram_bot_ruoli (
        username VARCHAR(30) PRIMARY KEY,
        ruolo VARCHAR(300) NOT NULL,
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
