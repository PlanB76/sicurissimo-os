#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
SOCIAL GROWTH81+ — Generatore pacchetto giornaliero (GitHub Actions)
====================================================================
Gira ogni mattina via GitHub Actions (cron). Legge il palinsesto 48 slot
dal DB unico universale e produce:

  DAILY/YYYY-MM-DD_SOCIAL_GROWTH81_PACCHETTO_GIORNO_AUTO.md   (skeleton del giorno)
  04_SCHEDULED/telegram.csv                                    (coda Telegram da approvare)

Il contenuto testuale va rifinito da Claude/Mirco. La pubblicazione avviene
SOLO con approvazione umana (workflow publish-telegram, manuale).
Regola FASE ZERO81+: PUBLISH = HUMAN_APPROVAL.
"""
import csv
import os
import sqlite3
import sys
from datetime import date, datetime

HERE = os.path.dirname(os.path.abspath(__file__))
BASE = os.path.dirname(HERE)                       # .../0-81PLUS.NET/MARKETING81+/SOCIAL_GROWTH81+
MASTER = os.path.abspath(os.path.join(BASE, "..", ".."))  # .../0-81PLUS.NET (cartella master unica)
DB = os.path.join(MASTER, "81PLUS_GLOBAL_UNIVERSAL.db")

# Rotazione settimanale temi (0=lunedi ... 6=domenica)
TEMI_SETTIMANA = {
    0: ("Sicurezza lavoro e scadenze",      "Audit Express"),
    1: ("HACCP e settore food",             "Check HACCP81+"),
    2: ("Privacy e GDPR",                   "Check Privacy81+"),
    3: ("Appalti e cantieri",               "APPALTI81+"),
    4: ("UNIVERSITY81+ e membership",       "Membership81+"),
    5: ("BOOK81+ e registri operativi",     "BOOK81+"),
    6: ("Storytelling, community, status",  "Gruppo USER81+"),
}


def main():
    oggi = date.today()
    giorno = oggi.isoformat()
    tema, prodotto = TEMI_SETTIMANA[oggi.weekday()]

    con = sqlite3.connect(DB)
    cur = con.cursor()
    slots = cur.execute(
        "SELECT slot, ora, tema, cta_funnel FROM socialgrowth81_palinsesto ORDER BY slot"
    ).fetchall()
    ctas = cur.execute(
        "SELECT codice, cta, funnel FROM socialgrowth81_cta_bank ORDER BY codice"
    ).fetchall()
    con.close()

    # --- 1. Skeleton MD del giorno ---
    daily_dir = os.path.join(BASE, "DAILY")
    os.makedirs(daily_dir, exist_ok=True)
    md_path = os.path.join(daily_dir, "%s_SOCIAL_GROWTH81_PACCHETTO_GIORNO_AUTO.md" % giorno)

    L = []
    L.append("# SOCIAL GROWTH81+ — PACCHETTO AUTO DEL GIORNO")
    L.append("")
    L.append("Data: %s | Tema dominante: **%s** | Prodotto spinto: **%s**" % (giorno, tema, prodotto))
    L.append("Generato automaticamente da GitHub Actions. Da rifinire e APPROVARE prima della pubblicazione.")
    L.append("")
    L.append("## Palinsesto 48 slot (da DB unico)")
    L.append("")
    L.append("| # | Ora | Tema slot | CTA/Funnel | Testo (da compilare) | Stato |")
    L.append("|---|-----|-----------|------------|----------------------|-------|")
    for slot, ora, t, cta in slots:
        L.append("| %d | %s | %s | %s |  | BOZZA |" % (slot, ora, t, cta))
    L.append("")
    L.append("## CTA Bank disponibile")
    L.append("")
    for cod, cta, funnel in ctas:
        L.append("- **%s** — %s (%s)" % (cod, cta, funnel))
    L.append("")
    L.append("## Regole (vincolanti)")
    L.append("- Fonte CTA: LISTINO81+ | News solo da fonti ufficiali")
    L.append("- WhatsApp/email solo double opt-in | PV/PV+ solo utility")
    L.append("- Niente promesse assolute, zero multe, rischio zero, guadagni garantiti")
    L.append("- PUBLISH = HUMAN_APPROVAL: Mirco valida prima")
    with open(md_path, "w", encoding="utf-8") as fh:
        fh.write("\n".join(L) + "\n")

    # --- 2. Coda Telegram (solo skeleton, stato BOZZA) ---
    sched_dir = os.path.join(BASE, "04_SCHEDULED")
    os.makedirs(sched_dir, exist_ok=True)
    tg_path = os.path.join(sched_dir, "telegram.csv")
    with open(tg_path, "w", encoding="utf-8", newline="") as fh:
        w = csv.writer(fh, delimiter=";")
        w.writerow(["data", "ora", "slot", "tema", "cta_funnel", "testo", "stato"])
        for slot, ora, t, cta in slots:
            w.writerow([giorno, ora, slot, t, cta, "", "BOZZA"])

    print("Generato: %s" % md_path)
    print("Coda TG:  %s (48 righe, stato BOZZA)" % tg_path)
    print("Prossimo passo: compilare i testi, cambiare stato in APPROVATO,")
    print("poi lanciare il workflow socialgrowth81-publish-telegram (manuale).")
    return 0


if __name__ == "__main__":
    sys.exit(main())
