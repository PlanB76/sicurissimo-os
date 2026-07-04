#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
BOT AUTOMATION ENGINE 81+ — Motore parametrico 24 azioni/giorno
Crea: telegram_action_taxonomy (24 categorie), telegram_chat_profile (fill-rate per chat),
telegram_value_ladder (scala valore da LISTINO81+ ufficiale), telegram_bot_ruoli (4 bot
verificati via getMe 2026-07-04), telegram_gating_rules (regola precisa per livello,
confermata da Mirco 2026-07-04: baseline pubblica mai persa, ogni gruppo status richiede
un AND indipendente di requisiti, la decadenza di uno rimuove solo dal gruppo corrispondente),
telegram_accessi_log (schema vuoto, si popola runtime).
NOTA: nessun token/invite link privato vive qui. Solo in telegram_config.local.json (gitignored).
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

BOT_RUOLI = [
    ("@sicurissimo81_bot", "sicurissimo81+",
     "Orchestratore principale: gating status/membership/SDP, welcome, palinsesto, funnel/conversione", "VERIFICATO_LIVE"),
    ("@SicurissimoAI_bot", "SicurissimoAI_bot",
     "CORTEX81+ AI conversazionale: FAQ, Q&A libere, spiegazioni normative on-demand", "VERIFICATO_LIVE"),
    ("@ottantuno_bot", "81+ Assistenza",
     "Assistenza/Problem solving: support desk, escalation umana, gestione dubbi operativi", "VERIFICATO_LIVE"),
    ("@sicurissimonewbot", "sicurissimoonline",
     "Moderazione + Gamification: anti-spam, Semantic Guard, missioni, quiz, badge, leaderboard", "VERIFICATO_LIVE"),
]

# Regola confermata da Mirco 2026-07-04: la baseline (canali pubblici + 81+ ECOSYSTEM) non si
# perde MAI. Ogni gruppo status ha un proprio AND di requisiti indipendente dagli altri:
# la decadenza di un requisito rimuove SOLO dal gruppo corrispondente, mai dagli altri gia attivi.
GATING_RULES = [
    ("USER81+ (baseline)", "Nessuno - solo canali pubblici + 81+ ECOSYSTEM",
     "Nessuno - SIC-ID sufficiente", "N/A - baseline sempre garantita, mai revocata"),
    ("MEMBER81+ Basic+", "81+ BASIC",
     "Membership Basic+ attiva (ricorrente)",
     "Rimosso da 81+ BASIC, torna a baseline (canali pubblici + Ecosystem)"),
    ("MEMBER81+ Pro+", "81+PRO",
     "Membership Pro+ attiva (ricorrente)",
     "Rimosso da 81+PRO, torna a baseline"),
    ("MEMBER81+ Elite+", "81+ ELITE",
     "Membership Elite+ attiva (ricorrente)",
     "Rimosso da 81+ ELITE, torna a baseline"),
    ("NETWORKER81+", "81+ NETWORK",
     "Membership attiva (Basic/Pro/Elite, qualunque tier) AND SDK acquistato AND SDP Pass del rank corrente attivo",
     "Rimosso da 81+ NETWORK. Se la membership resta attiva, mantiene comunque il gruppo membership corrispondente"),
    ("ELITE81+ (status rete)", "81+ ELITE GROUP",
     "Membership attiva AND rank 6 SUMMIT raggiunto AND SDP Royal Pass attivo",
     "Rimosso da 81+ ELITE GROUP. Mantiene 81+ NETWORK e gruppo membership se ancora attivi"),
    ("VIP81+", "81+ VIP",
     "Membership attiva AND rank 3 interno Elite raggiunto AND SDP Diamond Pass attivo",
     "Rimosso da 81+ VIP. Mantiene i gruppi di livello inferiore se ancora attivi"),
    ("FRANCHISEE81+", "81+ FRANCHISING",
     "Membership attiva AND contratto POINT81+ attivo (canone pagato)",
     "Rimosso da 81+ FRANCHISING. Mantiene gli altri gruppi se ancora attivi"),
    ("CLUB81+", "81+ CLUB",
     "Membership attiva AND canone Club attivo (Palladium/Iridium/Rhodium)",
     "Rimosso da 81+ CLUB. Mantiene gli altri gruppi se ancora attivi"),
    ("PRESIDENT81+", "Topic dentro 81+ CLUB",
     "Invito diretto e manuale di Mirco - MAI automatico",
     "Solo Mirco puo rimuovere l'accesso, mai automatico"),
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

    cur.execute("DROP TABLE IF EXISTS telegram_bot_ruoli")
    cur.execute("""CREATE TABLE telegram_bot_ruoli (
        username VARCHAR(30) PRIMARY KEY, nome_verificato VARCHAR(60) NOT NULL,
        ruolo VARCHAR(300) NOT NULL, stato VARCHAR(20) NOT NULL)""")
    cur.executemany("INSERT INTO telegram_bot_ruoli VALUES (?,?,?,?)", BOT_RUOLI)

    cur.execute("DROP TABLE IF EXISTS telegram_gating_rules")
    cur.execute("""CREATE TABLE telegram_gating_rules (
        id INTEGER PRIMARY KEY AUTOINCREMENT, livello VARCHAR(30) NOT NULL,
        gruppo_dedicato VARCHAR(30) NOT NULL, requisiti_and VARCHAR(250) NOT NULL,
        su_decadenza VARCHAR(250) NOT NULL)""")
    cur.executemany("INSERT INTO telegram_gating_rules (livello, gruppo_dedicato, requisiti_and, su_decadenza) VALUES (?,?,?,?)", GATING_RULES)

    cur.execute("DROP TABLE IF EXISTS telegram_accessi_log")
    cur.execute("""CREATE TABLE telegram_accessi_log (
        id INTEGER PRIMARY KEY AUTOINCREMENT, sic_id VARCHAR(24), chat_richiesta VARCHAR(40),
        esito VARCHAR(10), motivo VARCHAR(200), created_at DATETIME DEFAULT CURRENT_TIMESTAMP)""")

    con.commit()
    for t in ("telegram_action_taxonomy", "telegram_chat_profile", "telegram_value_ladder",
              "telegram_bot_ruoli", "telegram_gating_rules", "telegram_accessi_log"):
        print(f"  {t}: {cur.execute('SELECT COUNT(*) FROM ' + t).fetchone()[0]} righe")
    print("  integrity:", cur.execute("PRAGMA integrity_check").fetchone()[0])
    con.close()


if __name__ == "__main__":
    main()
