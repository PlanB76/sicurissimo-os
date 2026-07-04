#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
TELEGRAM CONTENT GENERATOR 81+ — 24 azioni/ora, ciclo 30 giorni, mai ripetuto
=============================================================================
Motore REALE (non placeholder): legge il ciclo del giorno dal DB unico, genera
contenuto originale per ognuna delle 11 chat con le API AI configurate, scrive
la coda in 04_SCHEDULED/<chat>.csv in stato BOZZA (mai pubblicato senza
approvazione umana - PUBLISH resta HUMAN_APPROVAL per regola FASE ZERO81+).

Motore AI a cascata (usa il primo disponibile, salta al successivo se fallisce):
  1. OpenAI (gpt-4o-mini)      - primario, verificato attivo
  2. Anthropic (claude-3-5-haiku) - richiede credito (oggi insufficiente)
  3. Gemini (gemini-2.0-flash)    - richiede fatturazione attiva (oggi quota 429)
  4. Groq (llama-3.1-8b-instant)  - va testato da GitHub Actions (bloccato in sandbox locale)

Credenziali SOLO da variabili d'ambiente (GitHub Secrets):
  OPENAI_API_KEY, ANTHROPIC_API_KEY, GEMINI_API_KEY, GROQ_API_KEY

Uso:
    python3 generate_telegram_content.py [--giorno-ciclo N] [--dry-run]
"""
import csv
import json
import os
import re
import sys
import urllib.request
import urllib.error
import sqlite3
from datetime import date

# Semantic Guard: parole SEMPRE vietate, in qualunque contesto (regola FASE ZERO81+).
# Il system prompt le vieta gia, ma un LLM puo comunque sbagliare: questo e il secondo
# passaggio di controllo automatico, obbligatorio prima di mettere in coda un contenuto.
SEMANTIC_GUARD_VIETATE = [
    r"\binvest[a-z]*\b", r"\brendiment[oi]\b", r"\bguadagn[oi] garantit[oi]\b",
    r"\brischio zero\b", r"\bzero multe\b", r"\bAPY\b", r"\bROI\b", r"\bstaking\b",
    r"\bSafePoint\b", r"\bliquidit[aà] garantita\b", r"\bprofitto garantit[oi]\b",
]


def semantic_guard_check(testo):
    """Ritorna (ok: bool, parole_trovate: list). Se ok=False il contenuto va in REVISIONE, mai BOZZA pulita."""
    trovate = []
    for pattern in SEMANTIC_GUARD_VIETATE:
        if re.search(pattern, testo, re.IGNORECASE):
            trovate.append(pattern)
    return (len(trovate) == 0), trovate

HERE = os.path.dirname(os.path.abspath(__file__))
BASE = os.path.dirname(HERE)  # .../SOCIAL_GROWTH81+
MASTER = os.path.abspath(os.path.join(BASE, "..", ".."))  # .../0-81PLUS.NET
DB = os.path.join(MASTER, "81PLUS_GLOBAL_UNIVERSAL.db")
SCHED_DIR = os.path.join(BASE, "04_SCHEDULED")

CHATS = [
    "Sicurissimo.Online", "81+ Sicurezza sul Lavoro", "81+ ECOSYSTEM",
    "81+ BASIC", "81+PRO", "81+ ELITE", "81+ NETWORK",
    "81+ ELITE GROUP", "81+ VIP", "81+ FRANCHISING", "81+ CLUB",
]


def call_openai(prompt, key):
    body = json.dumps({
        "model": "gpt-4o-mini",
        "messages": [
            {"role": "system", "content": "Scrivi in italiano, voce attiva, frasi brevi, dai del tu, "
             "solo virgole e punti, niente hashtag, asterischi, trattini o emoji. Mai promesse di guadagno, "
             "rendimento, investimento o rischio zero. PV/PV+ sono sempre utility, mai denaro. "
             "Non citare mai codici tecnici interni (es. nomi in maiuscolo con underscore): usa solo nomi reali di prodotto."},
            {"role": "user", "content": prompt},
        ],
        "max_tokens": 220,
    }).encode()
    req = urllib.request.Request(
        "https://api.openai.com/v1/chat/completions", data=body,
        headers={"Authorization": f"Bearer {key}", "Content-Type": "application/json"}, method="POST")
    with urllib.request.urlopen(req, timeout=30) as r:
        return json.loads(r.read())["choices"][0]["message"]["content"].strip()


def call_anthropic(prompt, key):
    body = json.dumps({
        "model": "claude-3-5-haiku-20241022", "max_tokens": 220,
        "system": "Scrivi in italiano, voce attiva, frasi brevi, dai del tu, solo virgole e punti. "
                  "Mai promesse di guadagno o rendimento.",
        "messages": [{"role": "user", "content": prompt}],
    }).encode()
    req = urllib.request.Request(
        "https://api.anthropic.com/v1/messages", data=body,
        headers={"x-api-key": key, "anthropic-version": "2023-06-01",
                 "Content-Type": "application/json"}, method="POST")
    with urllib.request.urlopen(req, timeout=30) as r:
        return json.loads(r.read())["content"][0]["text"].strip()


def call_gemini(prompt, key):
    body = json.dumps({"contents": [{"parts": [{"text": prompt}]}]}).encode()
    url = f"https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={key}"
    req = urllib.request.Request(url, data=body, headers={"Content-Type": "application/json"}, method="POST")
    with urllib.request.urlopen(req, timeout=30) as r:
        return json.loads(r.read())["candidates"][0]["content"]["parts"][0]["text"].strip()


def call_groq(prompt, key):
    body = json.dumps({
        "model": "llama-3.1-8b-instant",
        "messages": [{"role": "user", "content": prompt}], "max_tokens": 220,
    }).encode()
    req = urllib.request.Request(
        "https://api.groq.com/openai/v1/chat/completions", data=body,
        headers={"Authorization": f"Bearer {key}", "Content-Type": "application/json"}, method="POST")
    with urllib.request.urlopen(req, timeout=30) as r:
        return json.loads(r.read())["choices"][0]["message"]["content"].strip()


ENGINES = [
    ("OPENAI_API_KEY", call_openai),
    ("ANTHROPIC_API_KEY", call_anthropic),
    ("GEMINI_API_KEY", call_gemini),
    ("GROQ_API_KEY", call_groq),
]


def generate(prompt):
    """Prova i motori in cascata, ritorna (testo, motore_usato) o (None, errori)."""
    errors = []
    for env_name, fn in ENGINES:
        key = os.environ.get(env_name, "")
        if not key:
            continue
        try:
            return fn(prompt, key), env_name.replace("_API_KEY", "")
        except Exception as e:
            errors.append(f"{env_name}: {e}")
    return None, " | ".join(errors) if errors else "nessuna API key configurata"


# Risolve il codice interno prodotto_focus in un prodotto/prezzo REALE da citare nel prompt
# (mai passare il codice grezzo all'AI: previene che lo ripeta come se fosse un nome vero)
PRODOTTO_FOCUS_REALE = {
    "FREEBIE_TRIPWIRE": "Audit Express gratuito, checklist ATECO, o Membership Basic+ (69 euro al mese)",
    "MEMBERSHIP_UPSELL": "upgrade di membership: da Basic+ (69 euro) a Pro+ (139 euro) o Elite+ (209 euro) al mese",
    "PACCHETTI_SIGILLI": "Pacchetto Compliance Business (1900 euro) o Sigillo NFT Gold+ (1990 euro)",
    "NETWORK_STATUS_ALTO_TICKET": "Network81+ SDK (199 euro una tantum) o Membership Vip+/Royal+ (349-559 euro al mese)",
    "MIX_RECAP": "il prodotto piu adatto allo stadio dell'utente, dal freebie al Pacchetto Enterprise",
}


def load_day_cycle(cur, giorno_ciclo):
    row = cur.execute(
        "SELECT tema_normativo, formato_creativo_id, prodotto_focus FROM telegram_30day_cycle WHERE giorno_ciclo=?",
        (giorno_ciclo,)).fetchone()
    fmt = cur.execute(
        "SELECT nome, descrizione, categoria_taxonomy FROM telegram_creative_formats WHERE id=?",
        (row[1],)).fetchone()
    return {"tema": row[0], "formato_nome": fmt[0], "formato_desc": fmt[1],
            "formato_categoria": fmt[2],
            "prodotto_focus": PRODOTTO_FOCUS_REALE.get(row[2], row[2])}


def build_prompt(chat, ora_info, day_info, tono_strategia):
    return (
        f"Scrivi UN contenuto Telegram breve (max 6 righe) per il gruppo/canale '{chat}' "
        f"dell'ecosistema 81+ (compliance aziendale: sicurezza D.Lgs 81/08, HACCP, privacy GDPR). "
        f"TONO E STRATEGIA SPECIFICI DI QUESTO GRUPPO (rispettali rigorosamente, sono diversi da "
        f"ogni altro gruppo dell'ecosistema): {tono_strategia} "
        f"Slot orario: {ora_info['ruolo_slot']} (categoria: {ora_info['categoria_primaria']}). "
        f"Tema del giorno: {day_info['tema']}. "
        f"Formato creativo di oggi (adattalo al tono del gruppo, salta o reinterpreta se non e coerente "
        f"col tono sopra): '{day_info['formato_nome']}' - {day_info['formato_desc']}. "
        f"Prodotto da menzionare con nome e prezzo reale SOLO se coerente col tono del gruppo: "
        f"{day_info['prodotto_focus']}. "
        f"Termina con una CTA coerente col tono di questo specifico gruppo."
    )


def main():
    giorno_ciclo = ((date.today().timetuple().tm_yday - 1) % 30) + 1
    if "--giorno-ciclo" in sys.argv:
        giorno_ciclo = int(sys.argv[sys.argv.index("--giorno-ciclo") + 1])
    dry_run = "--dry-run" in sys.argv

    con = sqlite3.connect(DB)
    cur = con.cursor()

    day_info = load_day_cycle(cur, giorno_ciclo)
    print(f"Giorno ciclo: {giorno_ciclo}/30 | Tema: {day_info['tema']} | "
          f"Formato: {day_info['formato_nome']} | Focus: {day_info['prodotto_focus']}")

    hourly = cur.execute(
        "SELECT ora, categoria_primaria, ruolo_slot FROM telegram_hourly_slots ORDER BY ora").fetchall()
    toni_by_name = dict(cur.execute(
        "SELECT nome_chat, tono_strategia FROM telegram_chat_tone_profile").fetchall())
    profili_by_name = {r[0]: {"fill_max": r[1], "categorie": set(r[2].split(","))}
                       for r in cur.execute(
        "SELECT nome_chat, fill_rate_max, categorie_enfatizzate FROM telegram_chat_profile").fetchall()}

    os.makedirs(SCHED_DIR, exist_ok=True)
    total_ok, total_fail, total_flagged = 0, 0, 0

    for chat in CHATS:
        chat_note = toni_by_name.get(chat, "")
        profilo = profili_by_name.get(chat, {"fill_max": 24, "categorie": set()})

        # Rispetta il fill-rate calibrato per chat (es. Club 2-4/giorno, VIP 2-3/settimana,
        # NON 24 su tutte indiscriminatamente): seleziona prima gli slot nelle categorie
        # enfatizzate di questa chat, poi riempie fino al fill_rate_max se serve.
        prioritari = [s for s in hourly if s[1] in profilo["categorie"]]
        resto = [s for s in hourly if s[1] not in profilo["categorie"]]
        slot_selezionati = (prioritari + resto)[:profilo["fill_max"]]
        slot_selezionati.sort(key=lambda s: s[0])
        # Il formato creativo del giorno si applica SOLO se coerente con le categorie
        # enfatizzate di questa chat; altrimenti la chat resta nel proprio tono senza il
        # gimmick generico pensato per il pubblico di massa (es. Club non fa "Caccia al Cavillo").
        formato_coerente = day_info["formato_categoria"] in profilo["categorie"] or not profilo["categorie"]
        day_info_chat = dict(day_info)
        if not formato_coerente:
            day_info_chat["formato_nome"] = "(nessuno - non pertinente per questo gruppo oggi)"
            day_info_chat["formato_desc"] = "Genera un contenuto nel tono e categoria di questo gruppo, ignora il formato generico del giorno."

        rows = []
        for ora, categoria, ruolo in slot_selezionati:
            ora_info = {"categoria_primaria": categoria, "ruolo_slot": ruolo}
            prompt = build_prompt(chat, ora_info, day_info_chat, chat_note)
            if dry_run:
                testo, motore = f"[DRY-RUN] {ruolo}", "nessuno"
            else:
                testo, motore = generate(prompt)

            stato = "BOZZA"
            if testo:
                total_ok += 1
                ok, trovate = semantic_guard_check(testo)
                if not ok:
                    stato = "REVISIONE_SEMANTIC_GUARD"
                    total_flagged += 1
                    print(f"  [ATTENZIONE] {chat} {ora:02d}:00 - parole vietate trovate: {trovate}")
            else:
                total_fail += 1
                testo = ""
                stato = "ERRORE_GENERAZIONE"
            rows.append([f"{ora:02d}:00", categoria, ruolo, testo or "", motore or "", stato])

        safe_name = chat.replace(" ", "_").replace("+", "PLUS").replace(".", "")
        path = os.path.join(SCHED_DIR, f"telegram_{safe_name}.csv")
        with open(path, "w", encoding="utf-8", newline="") as fh:
            w = csv.writer(fh, delimiter=";")
            w.writerow(["ora", "categoria", "ruolo_slot", "testo", "motore_ai", "stato"])
            w.writerows(rows)
        print(f"  {chat}: {len(rows)} slot -> {path}")

    con.close()
    print(f"\nTotale: {total_ok} generati, {total_fail} falliti, "
          f"{total_flagged} bloccati dal Semantic Guard (su {len(CHATS) * 24} slot possibili).")
    print("Stato: BOZZA in tutte le code (REVISIONE_SEMANTIC_GUARD per i contenuti flaggati).")
    print("Nessuna pubblicazione automatica: serve approvazione umana (PUBLISH = HUMAN_APPROVAL).")
    return 0 if total_fail == 0 else 1


if __name__ == "__main__":
    sys.exit(main())
