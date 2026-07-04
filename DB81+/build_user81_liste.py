#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
81+ GLOBAL — Consolidamento 22k+ prospect in USER81+ (2 liste operative)
========================================================================
Legge tutte le fonti contatti del progetto (repo + upload Mirco), pulisce
i doppioni, applica la blacklist documentata, normalizza email e telefoni
e produce:

  LISTA 1 (USER81_LISTA1_EMAIL_WA): Nome/Azienda + email + telefono (SMS/WA)
  LISTA 2 (USER81_LISTA2_WA):       Nome/Azienda + telefono, senza email
  POOL    (PROSPECT81_POOL_EMAIL):  contatti con sola email (nurturing futuro)

Ogni USER81+ riceve un SIC-ID a 12 cifre: SIC-ID-XXXXXXXXXXXX
(sequenziale: prima LISTA 1, poi LISTA 2, poi POOL).

Tutto viene integrato nel DB unico DB81+/81PLUS_GLOBAL_UNIVERSAL.db
(tabella `user81` + view v_user81_lista1 / v_user81_lista2 / v_prospect81_pool)
e esportato in CSV pronti per campagne email/WA.
"""

import csv
import os
import re
import sqlite3
import sys
from collections import OrderedDict

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
UP = "/root/.claude/uploads/909670e6-2ef6-55f5-89cb-019d61883963/"
DB = os.path.join(ROOT, "DB81+", "81PLUS_GLOBAL_UNIVERSAL.db")
OUT_DIR = os.path.join(ROOT, "DB81+", "liste")

# ---------------------------------------------------------------------------
# Normalizzazione
# ---------------------------------------------------------------------------

EMAIL_RE = re.compile(r"^[A-Za-z0-9._%+\-']+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$")

def norm_email(v):
    if not v:
        return ""
    e = str(v).strip().lower().replace(" ", "")
    e = e.rstrip(".,;")
    return e if EMAIL_RE.match(e) else ""

def norm_phone(v):
    if not v:
        return ""
    s0 = str(v).strip()
    # numeri esportati come float da Excel: '390664001765.0' -> '390664001765'
    if re.match(r"^\+?\d+\.0+$", s0):
        s0 = s0.split(".")[0]
    s = re.sub(r"[^\d+]", "", s0)
    if s.startswith("00"):
        s = "+" + s[2:]
    if s.startswith("+"):
        digits = s[1:]
        return s if 9 <= len(digits) <= 15 and digits.isdigit() else ""
    if not s.isdigit():
        return ""
    if s.startswith("39") and 11 <= len(s) <= 13:
        return "+" + s
    if s.startswith("3") and 9 <= len(s) <= 10:
        return "+39" + s
    if s.startswith("0") and 8 <= len(s) <= 11:
        return "+39" + s
    return ""

def clean_txt(v, maxlen=200):
    if v is None:
        return ""
    s = re.sub(r"\s+", " ", str(v)).strip()
    return s[:maxlen]

# Blacklist documentata (foglio BLACKLIST RIMOSSI di LEAD_LIST_81PLUS)
BL_KEYWORDS = ["bisto", "desiderio", "liaci", "luminal", "zanellato", "peeter"]
BL_NAMES = ["perini alberto snc", "muratore bologna e provincia",
            "cme consorzio", "blm grandi cucine"]
def blacklisted(nome, azienda, email):
    t = (" ".join([nome or "", azienda or ""])).lower()
    e = (email or "").lower()
    for k in BL_KEYWORDS:
        if k in t or k in e:
            return True
    for n in BL_NAMES:
        if n in t:
            return True
    if "comune." in e.split("@")[-1] if "@" in e else False:
        return True
    if e.endswith("@unicredit.eu") or e.endswith("@cnaro.it") or e.startswith("ilaria.grandi@"):
        return True
    return False

# ---------------------------------------------------------------------------
# Parser fonti
# ---------------------------------------------------------------------------

def rec(nome="", cognome="", azienda="", email="", pec="", tel="", wa="",
        citta="", prov="", indirizzo="", piva="", fonte=""):
    return {
        "nome": clean_txt(nome), "cognome": clean_txt(cognome),
        "azienda": clean_txt(azienda),
        "email": norm_email(email), "pec": norm_email(pec),
        "telefono": norm_phone(tel), "whatsapp": norm_phone(wa),
        "citta": clean_txt(citta, 100), "provincia": clean_txt(prov, 4).upper(),
        "indirizzo": clean_txt(indirizzo, 200), "piva": clean_txt(piva, 20),
        "fonti": {fonte},
    }

def repair_leadlist_phone(v):
    """LEAD_LIST CSV ha telefoni corrotti da export float: valore reale x10
    (zero spurio in coda). Verificato contro lead_import_4000 (fonte pulita)."""
    s = re.sub(r"\D", "", str(v or ""))
    if s.startswith("39") and s.endswith("0") and 12 <= len(s) <= 13:
        return s[:-1]
    return v


def parse_all():
    records = []

    # ORDINE = priorita telefoni: prima le fonti con numeri puliti (+39 corretti),
    # poi LEAD_LIST (ricca di anagrafica ma con telefoni corrotti da riparare).

    import openpyxl

    # 1. live/data/lead_import_4000.csv (Nome;Email;Telefono — telefoni puliti)
    p = os.path.join(ROOT, "live", "data", "lead_import_4000.csv")
    with open(p, encoding="utf-8-sig", newline="") as fh:
        for row in csv.DictReader(fh, delimiter=";"):
            records.append(rec(nome=row.get("Nome", ""), email=row.get("Email", ""),
                               tel=row.get("Telefono", ""), fonte="import_giugno_2026"))
    def sheet_rows(fname):
        wb = openpyxl.load_workbook(UP + fname, read_only=True)
        rows = list(wb.active.iter_rows(values_only=True))
        wb.close()
        return rows

    # 2. Contatti_Mirco.xlsx (Nome, Email, Telefono — telefoni puliti)
    for r in sheet_rows("96d5d0ce-Contatti_Mirco.xlsx")[1:]:
        r = list(r) + [None] * 3
        records.append(rec(nome=r[0] or "", email=r[1] or "", tel=r[2] or "",
                           fonte="contatti_mirco"))

    # 3. LEAD_LIST_81PLUS_SIC_ID_COMPLETA.csv (repo — anagrafica ricca,
    #    telefoni riparati dallo zero spurio)
    p = os.path.join(ROOT, "LEAD_LIST_81PLUS_SIC_ID_COMPLETA.csv")
    with open(p, encoding="utf-8-sig", newline="") as fh:
        for row in csv.DictReader(fh):
            records.append(rec(
                nome=row.get("NOME", ""), cognome=row.get("COGNOME", ""),
                azienda=row.get("RAGIONE_SOCIALE", ""),
                email=row.get("EMAIL", ""), pec=row.get("PEC", ""),
                tel=repair_leadlist_phone(row.get("TELEFONO_MOBILE", "") or row.get("TELEFONO_FISSO", "")),
                wa=repair_leadlist_phone(row.get("WHATSAPP", "")),
                citta=row.get("CITTA_RESIDENZA", "") or row.get("SEDE_LEGALE_CITTA", ""),
                prov=row.get("PROVINCIA_RESIDENZA", "") or row.get("SEDE_LEGALE_PROV", ""),
                indirizzo=row.get("VIA_RESIDENZA", "") or row.get("SEDE_LEGALE_VIA", ""),
                piva=row.get("PARTITA_IVA", ""),
                fonte="LEAD_LIST_SICID",
            ))

    # 4. Contatti_email_Mirco.xlsx (Nome, email)
    for r in sheet_rows("e3f9d385-Contatti_email_Mirco.xlsx")[1:]:
        r = list(r) + [None] * 2
        records.append(rec(nome=r[0] or "", email=r[1] or "", fonte="email_mirco"))

    # 5. Temp_Contatti_labotecnic.xlsx (solo email) — labotecnic1 e identico
    for r in sheet_rows("03a4ba11-Temp_Contatti_labotecnic.xlsx"):
        if r and r[0]:
            records.append(rec(email=r[0], fonte="labotecnic"))

    # 6. Fatturazione elettronica completa (23 colonne)
    rows = sheet_rows("8939180c-Temp_Contatti_clienti_fatturazione_elettronica.xlsx")
    for r in rows[1:]:
        r = list(r) + [None] * 23
        records.append(rec(
            nome=(r[10] or ""), cognome=(r[11] or ""), azienda=(r[9] or ""),
            email=r[3] or "", pec=r[4] or "", tel=r[5] or "",
            citta=r[16] or "", prov=r[15] or "",
            indirizzo=" ".join(filter(None, [str(r[17] or ""), str(r[18] or "")])),
            piva=r[7] or "", fonte="fatturazione_elettronica",
        ))

    # 7. Fatturazione elettronica ridotta (email, denominazione, comune, via, civico)
    for r in sheet_rows("7113d55a-Contatti_clienti_fatturazione_elettronica.xlsx"):
        r = list(r) + [None] * 5
        if r[0]:
            records.append(rec(azienda=r[1] or "", email=r[0] or "",
                               citta=r[2] or "",
                               indirizzo=" ".join(filter(None, [str(r[3] or ""), str(r[4] or "")])),
                               fonte="fatturazione_elettronica"))

    return records

# ---------------------------------------------------------------------------
# Merge e dedup
# ---------------------------------------------------------------------------

def better(a, b):
    """riempi i campi vuoti di a con quelli di b"""
    for k in ("nome", "cognome", "azienda", "email", "pec", "telefono", "whatsapp",
              "citta", "provincia", "indirizzo", "piva"):
        if not a[k] and b[k]:
            a[k] = b[k]
        # preferisci il nome piu lungo (piu informativo)
        elif k in ("nome", "azienda") and b[k] and len(b[k]) > len(a[k]):
            a[k] = b[k]
    a["fonti"] |= b["fonti"]
    return a

def consolidate(records):
    by_email = OrderedDict()
    no_email = []
    bl_count = 0
    for r in records:
        if blacklisted(r["nome"], r["azienda"], r["email"]):
            bl_count += 1
            continue
        if r["email"]:
            k = r["email"]
            if k in by_email:
                better(by_email[k], r)
            else:
                by_email[k] = r
        elif r["telefono"] or r["whatsapp"] or r["nome"] or r["azienda"]:
            no_email.append(r)

    # indice telefono -> record email
    by_phone = {}
    for r in by_email.values():
        for ph in (r["telefono"], r["whatsapp"]):
            if ph:
                by_phone.setdefault(ph, r)

    only_phone = OrderedDict()
    dropped_no_channel = 0
    for r in no_email:
        ph = r["telefono"] or r["whatsapp"]
        if ph and ph in by_phone:
            better(by_phone[ph], r)           # doppione di un contatto email
        elif ph:
            if ph in only_phone:
                better(only_phone[ph], r)
            else:
                only_phone[ph] = r
        else:
            dropped_no_channel += 1           # ne email ne telefono: inutilizzabile

    return list(by_email.values()), list(only_phone.values()), bl_count, dropped_no_channel

# ---------------------------------------------------------------------------
# Classificazione + SIC-ID
# ---------------------------------------------------------------------------

def fix_nome_cognome(r):
    """Evita duplicazioni tipo nome='A Catalano' + cognome='Catalano'."""
    n, c = r["nome"], r["cognome"]
    if c and n.lower().endswith(" " + c.lower()):
        r["nome"] = n[: -len(c)].strip()
    elif c and n.lower() == c.lower():
        r["nome"] = ""
    return r


def classify(email_recs, phone_recs):
    for r in email_recs + phone_recs:
        fix_nome_cognome(r)
    lista1, pool = [], []
    for r in email_recs:
        has_name = bool(r["nome"] or r["cognome"] or r["azienda"])
        has_phone = bool(r["telefono"] or r["whatsapp"])
        if has_name and has_phone:
            lista1.append(r)
        else:
            pool.append(r)                    # email presente ma manca nome o telefono
    lista2 = [r for r in phone_recs if (r["nome"] or r["cognome"] or r["azienda"])]
    scarti2 = len(phone_recs) - len(lista2)   # telefono senza nome: non lavorabile
    key = lambda r: (r["azienda"] or r["nome"]).lower()
    lista1.sort(key=key)
    lista2.sort(key=key)
    pool.sort(key=key)
    return lista1, lista2, pool, scarti2

def assign_sicid(lista1, lista2, pool):
    n = 0
    for group in (lista1, lista2, pool):
        for r in group:
            n += 1
            r["sic_id"] = "SIC-ID-%012d" % n
    return n

# ---------------------------------------------------------------------------
# DB + CSV + report
# ---------------------------------------------------------------------------

def write_db(lista1, lista2, pool):
    con = sqlite3.connect(DB)
    cur = con.cursor()
    cur.execute("DROP TABLE IF EXISTS user81")
    cur.execute("""
        CREATE TABLE user81 (
          id         INTEGER PRIMARY KEY AUTOINCREMENT,
          sic_id     VARCHAR(24) NOT NULL UNIQUE,
          lista      VARCHAR(20) NOT NULL,   -- LISTA1_EMAIL_WA | LISTA2_WA | POOL_EMAIL
          nome       VARCHAR(200) DEFAULT '',
          cognome    VARCHAR(200) DEFAULT '',
          azienda    VARCHAR(200) DEFAULT '',
          email      VARCHAR(255) DEFAULT '',
          pec        VARCHAR(255) DEFAULT '',
          telefono   VARCHAR(20)  DEFAULT '',
          whatsapp   VARCHAR(20)  DEFAULT '',
          citta      VARCHAR(100) DEFAULT '',
          provincia  VARCHAR(4)   DEFAULT '',
          indirizzo  VARCHAR(200) DEFAULT '',
          piva       VARCHAR(20)  DEFAULT '',
          fonti      VARCHAR(300) DEFAULT '',
          segmento   VARCHAR(20)  DEFAULT 'FREDDO',
          consenso_email INTEGER  DEFAULT 0,  -- double opt-in: 0 finche non confermato
          consenso_wa    INTEGER  DEFAULT 0,
          created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )""")
    cur.execute("CREATE INDEX idx_user81_lista ON user81(lista)")
    cur.execute("CREATE INDEX idx_user81_email ON user81(email)")
    cur.execute("CREATE INDEX idx_user81_telefono ON user81(telefono)")
    cur.execute("CREATE INDEX idx_user81_provincia ON user81(provincia)")

    def ins(rows, lista):
        for r in rows:
            cur.execute("""INSERT INTO user81
                (sic_id, lista, nome, cognome, azienda, email, pec, telefono, whatsapp,
                 citta, provincia, indirizzo, piva, fonti)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)""",
                (r["sic_id"], lista, r["nome"], r["cognome"], r["azienda"], r["email"],
                 r["pec"], r["telefono"], r["whatsapp"], r["citta"], r["provincia"],
                 r["indirizzo"], r["piva"], "|".join(sorted(r["fonti"]))))
    ins(lista1, "LISTA1_EMAIL_WA")
    ins(lista2, "LISTA2_WA")
    ins(pool,   "POOL_EMAIL")

    for v in ("v_user81_lista1", "v_user81_lista2", "v_prospect81_pool"):
        cur.execute("DROP VIEW IF EXISTS " + v)
    cur.execute("""CREATE VIEW v_user81_lista1 AS
        SELECT sic_id, COALESCE(NULLIF(azienda,''), TRIM(nome||' '||cognome)) AS destinatario,
               nome, cognome, azienda, email, telefono, whatsapp, citta, provincia, fonti
        FROM user81 WHERE lista='LISTA1_EMAIL_WA'""")
    cur.execute("""CREATE VIEW v_user81_lista2 AS
        SELECT sic_id, COALESCE(NULLIF(azienda,''), TRIM(nome||' '||cognome)) AS destinatario,
               nome, cognome, azienda, telefono, whatsapp, citta, provincia, fonti
        FROM user81 WHERE lista='LISTA2_WA'""")
    cur.execute("""CREATE VIEW v_prospect81_pool AS
        SELECT sic_id, COALESCE(NULLIF(azienda,''), TRIM(nome||' '||cognome)) AS destinatario,
               nome, cognome, azienda, email, citta, provincia, fonti
        FROM user81 WHERE lista='POOL_EMAIL'""")
    con.commit()
    con.close()

def write_csv(lista1, lista2, pool):
    os.makedirs(OUT_DIR, exist_ok=True)
    def dump(path, rows, cols):
        with open(path, "w", encoding="utf-8-sig", newline="") as fh:
            w = csv.writer(fh)
            w.writerow(cols)
            for r in rows:
                w.writerow([r.get(c.lower(), "") if c != "SIC_ID" else r["sic_id"]
                            for c in cols])
    c1 = ["SIC_ID", "NOME", "COGNOME", "AZIENDA", "EMAIL", "TELEFONO", "WHATSAPP",
          "CITTA", "PROVINCIA", "PIVA", "FONTI"]
    for r in lista1 + lista2 + pool:
        r["fonti_str"] = "|".join(sorted(r["fonti"]))
    M = {"SIC_ID": "sic_id", "NOME": "nome", "COGNOME": "cognome", "AZIENDA": "azienda",
         "EMAIL": "email", "TELEFONO": "telefono", "WHATSAPP": "whatsapp",
         "CITTA": "citta", "PROVINCIA": "provincia", "PIVA": "piva",
         "FONTI": "fonti_str"}
    def dump2(path, rows, cols):
        with open(path, "w", encoding="utf-8-sig", newline="") as fh:
            w = csv.writer(fh)
            w.writerow(cols)
            for r in rows:
                w.writerow([r.get(M[c], "") for c in cols])
    dump2(os.path.join(OUT_DIR, "USER81_LISTA1_EMAIL_WA.csv"), lista1, c1)
    dump2(os.path.join(OUT_DIR, "USER81_LISTA2_WA.csv"), lista2,
          ["SIC_ID", "NOME", "COGNOME", "AZIENDA", "TELEFONO", "WHATSAPP", "CITTA", "PROVINCIA", "FONTI"])
    dump2(os.path.join(OUT_DIR, "PROSPECT81_POOL_EMAIL.csv"), pool,
          ["SIC_ID", "NOME", "COGNOME", "AZIENDA", "EMAIL", "CITTA", "PROVINCIA", "FONTI"])


def md_esc(s):
    return (s or "").replace("|", "/").replace("\n", " ")


def write_md(lista1, lista2, pool):
    """File MD master con tutti gli USER81+ e riferimento alla gestione SIC-ID nel DB."""
    p = os.path.join(OUT_DIR, "USER81_MASTER_LIST.md")
    L = []
    L.append("# USER81+ MASTER LIST — Ecosistema 81+")
    L.append("")
    L.append("Generata dal DB unico universale `DB81+/81PLUS_GLOBAL_UNIVERSAL.db`.")
    L.append("")
    L.append("## Gestione SIC-ID nel DB81+")
    L.append("")
    L.append("Ogni user ha un codice univoco con sintassi `SIC-ID-XXXXXXXXXXXX` (12 cifre sequenziali).")
    L.append("La sezione del DB che gestisce il SIC-ID di ogni user e la tabella **`user81`**:")
    L.append("")
    L.append("| Campo | Descrizione |")
    L.append("|-------|-------------|")
    L.append("| `sic_id` | Codice univoco SIC-ID-XXXXXXXXXXXX (UNIQUE, indicizzato) |")
    L.append("| `lista` | LISTA1_EMAIL_WA / LISTA2_WA / POOL_EMAIL |")
    L.append("| `nome`, `cognome`, `azienda` | Anagrafica |")
    L.append("| `email`, `pec` | Canale email (validato) |")
    L.append("| `telefono`, `whatsapp` | Canale SMS/WA (normalizzato +39) |")
    L.append("| `citta`, `provincia`, `indirizzo` | Localizzazione |")
    L.append("| `piva` | Partita IVA se nota |")
    L.append("| `fonti` | Origini del contatto (dedup tracciato) |")
    L.append("| `segmento` | FREDDO di default, aggiornato dal lead scoring (Skill 38) |")
    L.append("| `consenso_email`, `consenso_wa` | Double opt-in GDPR: 0 finche non confermato |")
    L.append("")
    L.append("View operative: `v_user81_lista1`, `v_user81_lista2`, `v_prospect81_pool`.")
    L.append("")
    L.append("## Numeri")
    L.append("")
    L.append("| Lista | User | Uso |")
    L.append("|-------|------|-----|")
    L.append("| LISTA 1 — Email + SMS/WA | %d | Campagne email + WhatsApp, nurturing completo |" % len(lista1))
    L.append("| LISTA 2 — Solo SMS/WA | %d | Campagne WhatsApp/SMS |" % len(lista2))
    L.append("| POOL — Solo email | %d | Nurturing email, da arricchire con telefono |" % len(pool))
    L.append("| **TOTALE USER81+** | **%d** | |" % (len(lista1) + len(lista2) + len(pool)))
    L.append("")

    def table(rows, cols, title):
        L.append("## %s (%d user)" % (title, len(rows)))
        L.append("")
        if not rows:
            L.append("_Nessun user in questa lista al momento. Struttura pronta: si popola")
            L.append("automaticamente quando arrivano contatti con telefono ma senza email._")
            L.append("")
            return
        hdr = {"sic_id": "SIC-ID", "nome": "Nome", "cognome": "Cognome",
               "azienda": "Azienda", "email": "Email", "telefono": "Telefono/SMS/WA",
               "citta": "Citta", "provincia": "Prov"}
        L.append("| " + " | ".join(hdr[c] for c in cols) + " |")
        L.append("|" + "---|" * len(cols))
        for r in rows:
            L.append("| " + " | ".join(md_esc(r.get(c, "")) for c in cols) + " |")
        L.append("")

    table(lista1, ["sic_id", "nome", "cognome", "azienda", "email", "telefono", "citta", "provincia"],
          "LISTA 1 — USER81+ Email + SMS/WA")
    table(lista2, ["sic_id", "nome", "cognome", "azienda", "telefono", "citta", "provincia"],
          "LISTA 2 — USER81+ Solo SMS/WA")
    table(pool, ["sic_id", "nome", "cognome", "azienda", "email", "citta", "provincia"],
          "POOL — Prospect solo email (nurturing)")

    L.append("---")
    L.append("Regola FASE ZERO81+ n.4: nessuna email inviata senza double opt-in.")
    L.append("Il DB MySQL di produzione resta la fonte di verita; questo file e generato dal mirror SQLite.")
    with open(p, "w", encoding="utf-8") as fh:
        fh.write("\n".join(L) + "\n")

def main():
    records = parse_all()
    raw = len(records)
    email_recs, phone_recs, bl, no_channel = consolidate(records)
    lista1, lista2, pool, scarti_tel = classify(email_recs, phone_recs)
    tot = assign_sicid(lista1, lista2, pool)
    write_db(lista1, lista2, pool)
    write_csv(lista1, lista2, pool)
    write_md(lista1, lista2, pool)

    rep = []
    rep.append("=" * 64)
    rep.append("USER81+ — REPORT CONSOLIDAMENTO PROSPECT")
    rep.append("=" * 64)
    rep.append("")
    rep.append("Righe grezze lette da 8 fonti:   %6d" % raw)
    rep.append("Rimossi da blacklist:            %6d" % bl)
    rep.append("Scartati senza canale contatto:  %6d" % (no_channel + scarti_tel))
    rep.append("Contatti unici dopo dedup:       %6d" % tot)
    rep.append("")
    rep.append("LISTA 1 (Nome+Email+Tel/WA):     %6d  -> USER81_LISTA1_EMAIL_WA.csv" % len(lista1))
    rep.append("LISTA 2 (Nome+Tel/WA, no email): %6d  -> USER81_LISTA2_WA.csv" % len(lista2))
    rep.append("POOL email-only (nurturing):     %6d  -> PROSPECT81_POOL_EMAIL.csv" % len(pool))
    rep.append("")
    rep.append("SIC-ID assegnati: SIC-ID-%012d -> SIC-ID-%012d" % (1, tot))
    rep.append("DB: tabella user81 + view v_user81_lista1 / v_user81_lista2 / v_prospect81_pool")
    rep.append("")
    rep.append("NOTA GDPR: consenso_email/consenso_wa = 0 per tutti.")
    rep.append("Nessun invio massivo senza double opt-in (regola FASE ZERO81+ n.4).")
    txt = "\n".join(rep)
    with open(os.path.join(OUT_DIR, "USER81_REPORT.txt"), "w", encoding="utf-8") as fh:
        fh.write(txt + "\n")
    print(txt)

if __name__ == "__main__":
    sys.exit(main())
