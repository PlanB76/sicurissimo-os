#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
SOCIAL GROWTH81+ — Pubblicazione Telegram (GitHub Actions, manuale = HUMAN_APPROVAL)
====================================================================================
Legge 04_SCHEDULED/telegram.csv e pubblica sul canale SOLO le righe con
stato = APPROVATO e testo non vuoto. Scrive il log in 05_PUBLISHED/.

Credenziali SOLO da variabili d'ambiente (GitHub Secrets, mai committate):
  TELEGRAM_BOT_TOKEN  = token del bot (da @BotFather)
  TELEGRAM_CHAT_ID    = id del canale/gruppo (es. @sicurissimo o -100...)

Lancio: workflow socialgrowth81-publish-telegram (workflow_dispatch, manuale).
Regola FASE ZERO81+: PUBLISH = HUMAN_APPROVAL — il lancio manuale E l'approvazione.
"""
import csv
import json
import os
import sys
import urllib.parse
import urllib.request
from datetime import datetime, timezone

HERE = os.path.dirname(os.path.abspath(__file__))
BASE = os.path.dirname(HERE)
SCHED = os.path.join(BASE, "04_SCHEDULED", "telegram.csv")
PUBDIR = os.path.join(BASE, "05_PUBLISHED")

TOKEN = os.environ.get("TELEGRAM_BOT_TOKEN", "")
CHAT = os.environ.get("TELEGRAM_CHAT_ID", "")


def send(text):
    url = "https://api.telegram.org/bot%s/sendMessage" % TOKEN
    data = urllib.parse.urlencode({
        "chat_id": CHAT,
        "text": text,
        "disable_web_page_preview": "false",
    }).encode()
    req = urllib.request.Request(url, data=data)
    with urllib.request.urlopen(req, timeout=30) as r:
        return json.loads(r.read().decode())


def main():
    if not TOKEN or not CHAT:
        print("ERRORE: TELEGRAM_BOT_TOKEN / TELEGRAM_CHAT_ID non impostati nei GitHub Secrets.")
        return 1
    if not os.path.exists(SCHED):
        print("Nessuna coda: %s non esiste." % SCHED)
        return 0

    with open(SCHED, encoding="utf-8", newline="") as fh:
        rows = list(csv.DictReader(fh, delimiter=";"))

    os.makedirs(PUBDIR, exist_ok=True)
    now = datetime.now(timezone.utc).strftime("%Y-%m-%d")
    log_path = os.path.join(PUBDIR, "%s_log.csv" % now)

    sent, skipped, failed = 0, 0, 0
    log_rows = []
    for r in rows:
        stato = (r.get("stato") or "").strip().upper()
        testo = (r.get("testo") or "").strip()
        if stato != "APPROVATO" or not testo:
            skipped += 1
            continue
        try:
            resp = send(testo)
            ok = bool(resp.get("ok"))
            sent += 1 if ok else 0
            failed += 0 if ok else 1
            r["stato"] = "PUBBLICATO" if ok else "ERRORE"
            log_rows.append([r.get("data"), r.get("ora"), r.get("slot"),
                             r.get("tema"), "OK" if ok else "FAIL", testo[:80]])
        except Exception as e:
            failed += 1
            r["stato"] = "ERRORE"
            log_rows.append([r.get("data"), r.get("ora"), r.get("slot"),
                             r.get("tema"), "FAIL:%s" % str(e)[:40], testo[:80]])

    # aggiorna stati nella coda
    if rows:
        with open(SCHED, "w", encoding="utf-8", newline="") as fh:
            w = csv.DictWriter(fh, fieldnames=list(rows[0].keys()), delimiter=";")
            w.writeheader()
            w.writerows(rows)

    # log pubblicazione
    header_needed = not os.path.exists(log_path)
    with open(log_path, "a", encoding="utf-8", newline="") as fh:
        w = csv.writer(fh, delimiter=";")
        if header_needed:
            w.writerow(["data", "ora", "slot", "tema", "esito", "anteprima"])
        w.writerows(log_rows)

    print("Pubblicati: %d | Saltati (non approvati/vuoti): %d | Errori: %d" % (sent, skipped, failed))
    print("Log: %s" % log_path)
    return 0 if failed == 0 else 1


if __name__ == "__main__":
    sys.exit(main())
