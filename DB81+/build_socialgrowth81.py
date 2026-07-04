#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
SOCIAL GROWTH81+ — Integrazione nel DB unico universale
Crea e popola: socialgrowth81_palinsesto (48 slot), socialgrowth81_cta_bank (24 CTA),
socialgrowth81_status_levels (10 livelli), socialgrowth81_gruppi (8 gruppi Telegram).
"""
import os
import sqlite3

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB = os.path.join(ROOT, "81PLUS_GLOBAL_MASTER", "81plus.net", "0-81PLUS.NET",
                  "81PLUS_GLOBAL_UNIVERSAL.db")

PALINSESTO = [
    (1,  "06:00", "Sabato check",     "Fotografia81+"),
    (2,  "06:30", "Scadenze",         "Check Scadenze81+"),
    (3,  "07:00", "HACCP allergeni",  "Check HACCP81+"),
    (4,  "07:30", "Privacy accessi",  "Check Privacy81+"),
    (5,  "08:00", "Corsi",            "SCHOOL81+"),
    (6,  "08:30", "Cantieri caldo",   "Check Cantiere81+"),
    (7,  "09:00", "Appalti CIG",      "APPALTI81+"),
    (8,  "09:30", "BOOK81+",          "BOOK81+"),
    (9,  "10:00", "Membership",       "Membership81+"),
    (10, "10:30", "Network",          "Network81+"),
    (11, "11:00", "Franchising",      "Franchising81+"),
    (12, "11:30", "Audit",            "Audit Express"),
    (13, "12:00", "Pausa community",  "Segmentazione"),
    (14, "12:30", "HACCP registri",   "Kit HACCP"),
    (15, "13:00", "YouTube push",     "YT -> 81plus.net"),
    (16, "13:30", "Privacy social",   "Kit Privacy"),
    (17, "14:00", "Preposti",         "Corso Preposto"),
    (18, "14:30", "Primo soccorso",   "Corso Primo Soccorso"),
    (19, "15:00", "Antincendio",      "Corso Antincendio"),
    (20, "15:30", "DPI",              "Check DPI"),
    (21, "16:00", "Dashboard",        "Membership81+"),
    (22, "16:30", "Ore perse",        "Calcolatore ore"),
    (23, "17:00", "MASTER81+",        "MASTER81+"),
    (24, "17:30", "ACADEMY81+",       "ACADEMY81+"),
    (25, "18:00", "USER",             "Gruppo USER81+"),
    (26, "18:30", "MEMBER",           "Membership81+"),
    (27, "19:00", "ELITE",            "ELITE81+"),
    (28, "19:30", "VIP",              "VIP81+"),
    (29, "20:00", "GENESYS",          "GENESYS81+"),
    (30, "20:30", "Recap",            "LISTINO81+"),
    (31, "21:00", "Libro",            "Amazon / BOOK81+"),
    (32, "21:30", "FAQ",              "WhatsApp opt-in"),
    (33, "22:00", "Mito/fatto",       "Check gratuito"),
    (34, "22:30", "Sondaggio",        "Segmentazione"),
    (35, "23:00", "Evergreen",        "Video YT"),
    (36, "23:30", "Night value",      "81plus.net"),
    (37, "00:00", "CORTEX81+",        "CORTEX81+"),
    (38, "00:30", "Checklist",        "Magnet81+"),
    (39, "01:00", "Appalti",          "Checklist gara"),
    (40, "01:30", "HACCP",            "Kit HACCP"),
    (41, "02:00", "Privacy",          "Kit Privacy"),
    (42, "02:30", "Formazione",       "Corsi online"),
    (43, "03:00", "Franchising",      "Franchising81+"),
    (44, "03:30", "Network",          "Network81+"),
    (45, "04:00", "Welfare",          "Welfare81+"),
    (46, "04:30", "Shop",             "81plus.shop"),
    (47, "05:00", "PV+ utility",      "PV+ benefit"),
    (48, "05:30", "Agenda",           "Agenda81+"),
]

CTA_BANK = [
    ("CTA01", "Fai Audit Express gratuito",                  "Lead freddo"),
    ("CTA02", "Sblocca Audit completo 30 normative",         "Lead caldo"),
    ("CTA03", "Scarica strumenti gratuiti per il tuo ATECO", "Magneti81+"),
    ("CTA04", "Fai check formazione",                        "UNIVERSITY81+"),
    ("CTA05", "Entra in SCHOOL81+",                          "Sicurezza, HACCP, Privacy"),
    ("CTA06", "Entra in MASTER81+",                          "Business, IT, management"),
    ("CTA07", "Entra in ACADEMY81+",                         "Leadership, HR"),
    ("CTA08", "Acquista corso o documento",                  "Conversione diretta"),
    ("CTA09", "Scegli Pack81+ Bronze",                       "Primo ordine"),
    ("CTA10", "Scegli Pack81+ Silver",                       "Azienda strutturata"),
    ("CTA11", "Valuta Pack81+ Gold",                         "Multi-ruolo"),
    ("CTA12", "Richiedi Pack81+ Platinum",                   "Multi-sede"),
    ("CTA13", "Attiva Membership81+",                        "Ricorrente"),
    ("CTA14", "Scrivi su WhatsApp per scegliere il piano",   "Conversazione"),
    ("CTA15", "Entra nel gruppo USER81+",                    "Community base"),
    ("CTA16", "Candidati NETWORKER81+",                      "Referral"),
    ("CTA17", "Candidati ELITE81+",                          "Consulenti/RSPP"),
    ("CTA18", "Candidati VIP81+",                            "Premium"),
    ("CTA19", "Candidati FRANCHISER81+",                     "Territorio"),
    ("CTA20", "Entra in GENESYS81+",                         "Vertice"),
    ("CTA21", "Cerca libri BOOK81+ su Amazon",               "BOOK81+"),
    ("CTA22", "Chiedi a CORTEX81+",                          "AI engagement"),
    ("CTA23", "Resta connesso agli aggiornamenti 81+",       "Newsletter"),
    ("CTA24", "Prenota fotografia iniziale 81+",             "Assessment"),
]

STATUS_LEVELS = [
    (0, "SCONOSCIUTO",  "Contenuto virale, news, quiz, short, reel"),
    (1, "FOLLOWER",     "Checklist, sondaggi, strumenti gratuiti, Audit Express"),
    (2, "USER",         "Gruppo privato, corso base, documento, registro BOOK81+"),
    (3, "MEMBER",       "Membership81+, Pack Bronze/Silver, corsi, scadenziario"),
    (4, "NETWORKER",    "Referral etico, segnalazione, community, materiali"),
    (5, "ELITE",        "Consulenti, formatori, RSPP, Pack Gold, strumenti avanzati"),
    (6, "VIP",          "Advisory, servizi prioritari, Pack Platinum, casi studio"),
    (7, "FRANCHISER",   "Punto territoriale 81+, presidio locale, modello scalabile"),
    (8, "GENESYS",      "Club esclusivo, accesso avanzato, visione ecosistema"),
    (9, "CLUB_SOCIO",   "Socio SICURISSIMO TG, vertice assoluto"),
]

GRUPPI = [
    (0, "SICURISSIMO TG 81+",  "CANALE PUBBLICO — tutti",                        "News, palinsesto, quiz, ingresso funnel"),
    (1, "NETWORKER81+",        "SDK81+ + Pass PRO+ (rank 1-8)",                  "Network marketing etico: script, lead, rank"),
    (2, "MEMBER81+ BASIC",     "Membership Basic+ attiva",                       "Scadenze, corsi base, documenti, metodo"),
    (3, "MEMBER81+ PRO",       "Membership Pro+ attiva",                         "Audit, strumenti avanzati, priorita"),
    (4, "MEMBER81+ ELITE",     "Membership Elite+ attiva",                       "Advisory, casi studio, accesso prioritario"),
    (5, "MEMBER81+ VIP",       "Clienti VIP / multi-sede",                       "Regia unica, servizi premium, eventi"),
    (6, "FRANCHISEE81+",       "Titolari POINT81+ / franchising attivo",         "Territorio, modello operativo, presidio"),
    (7, "CLUB81+",             "SDK ROYAL + Pass ROYAL+ / invito diretto",       "Vertice: visione, partnership, ecosistema"),
]


def main():
    con = sqlite3.connect(DB)
    cur = con.cursor()

    cur.execute("DROP TABLE IF EXISTS socialgrowth81_palinsesto")
    cur.execute("""CREATE TABLE socialgrowth81_palinsesto (
        slot INTEGER PRIMARY KEY, ora VARCHAR(5) NOT NULL,
        tema VARCHAR(60) NOT NULL, cta_funnel VARCHAR(60) NOT NULL)""")
    cur.executemany("INSERT INTO socialgrowth81_palinsesto VALUES (?,?,?,?)", PALINSESTO)

    cur.execute("DROP TABLE IF EXISTS socialgrowth81_cta_bank")
    cur.execute("""CREATE TABLE socialgrowth81_cta_bank (
        codice VARCHAR(6) PRIMARY KEY, cta VARCHAR(80) NOT NULL,
        funnel VARCHAR(60) NOT NULL)""")
    cur.executemany("INSERT INTO socialgrowth81_cta_bank VALUES (?,?,?)", CTA_BANK)

    cur.execute("DROP TABLE IF EXISTS socialgrowth81_status_levels")
    cur.execute("""CREATE TABLE socialgrowth81_status_levels (
        livello INTEGER PRIMARY KEY, status VARCHAR(20) NOT NULL,
        contenuto_funnel VARCHAR(120) NOT NULL)""")
    cur.executemany("INSERT INTO socialgrowth81_status_levels VALUES (?,?,?)", STATUS_LEVELS)

    cur.execute("DROP TABLE IF EXISTS socialgrowth81_gruppi")
    cur.execute("""CREATE TABLE socialgrowth81_gruppi (
        livello INTEGER PRIMARY KEY, gruppo VARCHAR(50) NOT NULL,
        pubblico VARCHAR(80) NOT NULL, obiettivo VARCHAR(120) NOT NULL)""")
    cur.executemany("INSERT INTO socialgrowth81_gruppi VALUES (?,?,?,?)", GRUPPI)

    con.commit()
    for t in ("socialgrowth81_palinsesto", "socialgrowth81_cta_bank",
              "socialgrowth81_status_levels", "socialgrowth81_gruppi"):
        n = cur.execute("SELECT COUNT(*) FROM " + t).fetchone()[0]
        print(f"  {t}: {n} righe")
    print("  integrity:", cur.execute("PRAGMA integrity_check").fetchone()[0])
    con.close()


if __name__ == "__main__":
    main()
