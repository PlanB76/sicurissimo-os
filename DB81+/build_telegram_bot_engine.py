#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
BOT AUTOMATION ENGINE 81+ — Motore parametrico 24 azioni/giorno
Crea: telegram_action_taxonomy (24 categorie), telegram_chat_profile (fill-rate per chat),
telegram_value_ladder (scala valore da LISTINO81+ ufficiale), telegram_gating_rules,
telegram_accessi_log (schema vuoto, si popola runtime).
"""
import os
import sqlite3

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB = os.path.join(ROOT, "81PLUS_GLOBAL_MASTER", "81plus.net", "0-81PLUS.NET",
                  "81PLUS_GLOBAL_UNIVERSAL.db")

TAXONOMY = [
    (1,  "NEWS_HOOK",         "Attenzione",              "-"),
    (2,  "CHECKLIST_FREEBIE", "Lead magnet",             "FREEBIE"),
    (3,  "QUIZ_ENGAGEMENT",   "Interazione",             "-"),
    (4,  "SONDAGGIO",         "Interazione/segmentazione","-"),
    (5,  "CASO_REALE",        "Riprova sociale",         "-"),
    (6,  "TESTIMONIAL",       "Riprova sociale",         "-"),
    (7,  "VIDEO_SHORT",       "Awareness",               "-"),
    (8,  "VIDEO_LUNGO",       "Autorita",                "-"),
    (9,  "CTA_SOFT",          "Nurturing",               "-"),
    (10, "CTA_HARD",          "Conversione",             "-"),
    (11, "MITO_FATTO",        "Educazione",              "-"),
    (12, "DOMANDA_APERTA",    "Community",               "-"),
    (13, "BADGE_CELEBRATION", "Gamification",            "-"),
    (14, "RANK_PROGRESS",     "Gamification/Funnel",     "COMMUNITY_EVOLUTA"),
    (15, "MISSIONE_ANNOUNCE", "Gamification",            "-"),
    (16, "LEADERBOARD",       "Gamification",            "-"),
    (17, "RENEWAL_REMINDER",  "Retention",               "-"),
    (18, "UPSELL_NUDGE",      "Funnel",                  "-"),
    (19, "CROSS_PROMO",       "Funnel trasversale",      "-"),
    (20, "EVENT_REMINDER",    "Retention",               "-"),
    (21, "AI_QA_SLOT",        "Servizio",                "-"),
    (22, "ENTERTAINMENT",     "Retention",               "-"),
    (23, "RECAP",             "Chiusura ciclo",          "-"),
    (24, "DAO_UPDATE",        "Governance",              "-"),
]

CHAT_PROFILE = [
    ("Sicurissimo.Online",       20, 24, "NEWS_HOOK,CHECKLIST_FREEBIE,VIDEO_SHORT,CTA_SOFT,QUIZ_ENGAGEMENT"),
    ("81+ Sicurezza sul Lavoro",  4,  6, "NEWS_HOOK"),
    ("81+ ECOSYSTEM",            16, 20, "DOMANDA_APERTA,QUIZ_ENGAGEMENT,SONDAGGIO,CASO_REALE,AI_QA_SLOT"),
    ("81+ BASIC",                 8, 12, "RENEWAL_REMINDER,UPSELL_NUDGE,VIDEO_SHORT,MISSIONE_ANNOUNCE"),
    ("81+PRO",                    8, 12, "RENEWAL_REMINDER,UPSELL_NUDGE,VIDEO_SHORT,MISSIONE_ANNOUNCE,RANK_PROGRESS"),
    ("81+ ELITE",                 8, 12, "RENEWAL_REMINDER,VIDEO_LUNGO,MISSIONE_ANNOUNCE,TESTIMONIAL"),
    ("81+ NETWORK",              10, 14, "MISSIONE_ANNOUNCE,LEADERBOARD,RANK_PROGRESS,CASO_REALE,CTA_HARD"),
    ("81+ ELITE GROUP",           5,  8, "CASO_REALE,CROSS_PROMO,VIDEO_LUNGO,UPSELL_NUDGE"),
    ("81+ VIP",                   2,  3, "ENTERTAINMENT,EVENT_REMINDER"),
    ("81+ FRANCHISING",           5,  7, "LEADERBOARD,RENEWAL_REMINDER,MISSIONE_ANNOUNCE"),
    ("81+ CLUB",                  2,  4, "EVENT_REMINDER,RECAP,DAO_UPDATE"),
]

VALUE_LADDER = [
    (1,  "FREEBIE",              "Audit Express / checklist / quiz ATECO",     0,     "FOLLOWER"),
    (2,  "TRIPWIRE",              "Membership Basic+",                         49,    "MEMBER81+"),
    (3,  "ENTRY",                 "Pacchetto Compliance Start",                990,   "MEMBER81+"),
    (4,  "CORE_MEMBERSHIP",       "Membership Pro+",                           99,    "MEMBER81+"),
    (5,  "CORE_MEMBERSHIP_ALTO",  "Membership Elite+",                         149,   "MEMBER81+"),
    (6,  "CORE_PACCHETTO",        "Pacchetto Compliance Business",             1900,  "MEMBER81+"),
    (7,  "SALITA_STATUS",         "Sigillo NFT Bronze",                        990,   "MEMBER81+"),
    (8,  "SALITA_STATUS",         "Sigillo NFT Silver",                        1490,  "MEMBER81+"),
    (9,  "SALITA_STATUS",         "Sigillo NFT Gold",                          1990,  "MEMBER81+"),
    (10, "COMMUNITY_EVOLUTA",     "Network81+ SDK (una tantum)",               199,   "NETWORKER81+"),
    (11, "COMMUNITY_EVOLUTA",     "SDP Pass IGNITE/mese",                      49,    "NETWORKER81+"),
    (12, "COMMUNITY_EVOLUTA",     "SDP Pass CROWN/mese",                       999,   "NETWORKER81+"),
    (13, "ALTO_VALORE_RICORRENTE","Club Palladium/mese",                       1990,  "CLUB81+"),
    (14, "ALTO_VALORE_RICORRENTE","Club Rhodium/mese",                         9990,  "CLUB81+"),
    (15, "ALTO_TICKET",           "Franchising Light (ingresso)",              4900,  "FRANCHISEE81+"),
    (16, "ALTO_TICKET",           "Franchising Flagship (ingresso)",           19900, "FRANCHISEE81+"),
    (17, "UTILITY_WEB3",          "PIX81+ Special Pack (750 PV, non euro)",    0,     "TUTTI"),
    (18, "VERTICE",               "Percorso Socio Holding (valutazione diretta)",0,   "PRESIDENT81+"),
]

GATING_RULES = [
    ("PUBBLICO",      "Nessuna verifica", "Sempre aperto"),
    ("MEMBERSHIP",    "user81.membership_tier + membership_scadenza", "Tier attivo AND scadenza > oggi"),
    ("STATUS_RETE",   "user81.network_rank + sdk_attivo + sdp_scadenza + membership_scadenza", "SDK acquistato AND SDP Pass rank attivo AND Membership attiva"),
]


def main():
    con = sqlite3.connect(DB)
    cur = con.cursor()

    cur.execute("DROP TABLE IF EXISTS telegram_action_taxonomy")
    cur.execute("""CREATE TABLE telegram_action_taxonomy (
        id INTEGER PRIMARY KEY, categoria VARCHAR(30) NOT NULL,
        obiettivo_funnel VARCHAR(40) NOT NULL, gradino_scala_valore VARCHAR(30) NOT NULL)""")
    cur.executemany("INSERT INTO telegram_action_taxonomy VALUES (?,?,?,?)", TAXONOMY)

    cur.execute("DROP TABLE IF EXISTS telegram_chat_profile")
    cur.execute("""CREATE TABLE telegram_chat_profile (
        nome_chat VARCHAR(40) PRIMARY KEY, fill_rate_min INTEGER NOT NULL,
        fill_rate_max INTEGER NOT NULL, categorie_enfatizzate VARCHAR(200) NOT NULL)""")
    cur.executemany("INSERT INTO telegram_chat_profile VALUES (?,?,?,?)", CHAT_PROFILE)

    cur.execute("DROP TABLE IF EXISTS telegram_value_ladder")
    cur.execute("""CREATE TABLE telegram_value_ladder (
        ordine INTEGER PRIMARY KEY, gradino VARCHAR(30) NOT NULL,
        prodotto VARCHAR(60) NOT NULL, prezzo_eur INTEGER NOT NULL,
        stadio_riferimento VARCHAR(30) NOT NULL)""")
    cur.executemany("INSERT INTO telegram_value_ladder VALUES (?,?,?,?,?)", VALUE_LADDER)

    cur.execute("DROP TABLE IF EXISTS telegram_gating_rules")
    cur.execute("""CREATE TABLE telegram_gating_rules (
        asse VARCHAR(20) PRIMARY KEY, campo_verifica VARCHAR(200) NOT NULL,
        condizione VARCHAR(200) NOT NULL)""")
    cur.executemany("INSERT INTO telegram_gating_rules VALUES (?,?,?)", GATING_RULES)

    cur.execute("DROP TABLE IF EXISTS telegram_accessi_log")
    cur.execute("""CREATE TABLE telegram_accessi_log (
        id INTEGER PRIMARY KEY AUTOINCREMENT, sic_id VARCHAR(24), chat_richiesta VARCHAR(40),
        esito VARCHAR(10), motivo VARCHAR(200), created_at DATETIME DEFAULT CURRENT_TIMESTAMP)""")

    con.commit()
    for t in ("telegram_action_taxonomy", "telegram_chat_profile", "telegram_value_ladder",
              "telegram_gating_rules", "telegram_accessi_log"):
        print(f"  {t}: {cur.execute('SELECT COUNT(*) FROM ' + t).fetchone()[0]} righe")
    print("  integrity:", cur.execute("PRAGMA integrity_check").fetchone()[0])
    con.close()


if __name__ == "__main__":
    main()
