#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
81+ GLOBAL — Costruttore DB UNICO SQLite (universale)
=====================================================
Converte tutti gli schemi MySQL sparsi nel progetto in un unico
database SQLite portabile, che gira su qualsiasi host senza server MySQL.

NOTA (2026-07-04): i 53 file .sql sorgente elencati in SOURCES sono stati
consolidati nel DB unico e RIMOSSI dal repo. Questo script resta come
documentazione della provenienza del database. Il risultato finale e gia
il file 81PLUS_GLOBAL_UNIVERSAL.db (schema + dati). Per rieseguirlo servono
i sorgenti originali, recuperabili dalla cronologia git.

Uso:
    python3 build_sqlite_universal.py

Output:
    DB81+/81PLUS_GLOBAL_UNIVERSAL.db          (database SQLite pronto)
    DB81+/81PLUS_GLOBAL_UNIVERSAL.schema.sql  (schema consolidato leggibile)
    DB81+/81PLUS_GLOBAL_UNIVERSAL.report.txt  (report build: tabelle, origini, skip)

Regole di merge:
  - Le tabelle sono deduplicate per nome: vince la PRIMA definizione
    secondo l'ordine di priorita dei file (SOURCES).
  - MySQL -> SQLite: AUTO_INCREMENT -> INTEGER PRIMARY KEY AUTOINCREMENT,
    ENUM/SET -> TEXT, UNSIGNED rimosso, ON UPDATE CURRENT_TIMESTAMP rimosso,
    KEY/INDEX/UNIQUE KEY -> CREATE INDEX separati, opzioni ENGINE/CHARSET
    rimosse, COMMENT rimossi, NOW() -> CURRENT_TIMESTAMP.
  - I TRIGGER MySQL (DELIMITER ...) vengono saltati (logica applicativa).
  - Le VIEW vengono convertite e caricate dopo le tabelle.
  - Gli INSERT di seed vengono caricati solo per tabelle esistenti.
"""

import os
import re
import sqlite3
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

# Ordine di priorita: la prima definizione di ogni tabella vince.
SOURCES = [
    # HUB1 core — master superset (82 tabelle + 4179 lead)
    "81PLUS_HUB1_LIVE_MASTER_VIVO/sql/MASTER_81PLUS_GLOBAL.sql",
    # HUB2 moduli compliance
    "WEB_HOSTINGER/81plus.net/public_html/admin/81global/database/lex81_leadgen81_mysql.sql",
    "WEB_HOSTINGER/81plus.net/public_html/admin/sfera81/database/sfera81_mysql.sql",
    "WEB_HOSTINGER/81plus.net/public_html/admin/sfera81/database/sfera81_v6_addendum.sql",
    # Auth core HUB1
    "hub1/api/migrations/001_auth_core.sql",
    # Master vivo dashboard/academy/scadenziario + extra
    "live/sql/patch_v26_master_vivo.sql",
    "live/sql/schema.sql",
    # Delta HUB1 (tabelle aggiuntive non nel master)
    "81PLUS_HUB1_LIVE_MASTER_VIVO/sql/MYSQL_DELTA_BLOCCO2_HUB1_CORE.sql",
    "81PLUS_HUB1_LIVE_MASTER_VIVO/sql/MYSQL_DELTA_ARCHIVIO_INTELLIGENTE.sql",
    "81PLUS_HUB1_LIVE_MASTER_VIVO/sql/MYSQL_DELTA_SCOUT81_PROSPECTS.sql",
    "81PLUS_HUB1_LIVE_MASTER_VIVO/sql/MYSQL_DELTA_GAMIFICATION_WAVE1.sql",
    "81PLUS_HUB1_LIVE_MASTER_VIVO/sql/MYSQL_DELTA_PVPLUS_BOOSTER.sql",
    "81PLUS_HUB1_LIVE_MASTER_VIVO/sql/MYSQL_DELTA_NETWORKER_SCOUT_PLP_PIANI.sql",
    "81PLUS_HUB1_LIVE_MASTER_VIVO/sql/MYSQL_DELTA_DASHBOARD_ACADEMY_DOC_SCADENZIARIO.sql",
    "81PLUS_HUB1_LIVE_MASTER_VIVO/sql/MYSQL_DELTA_PIPELINE_TERRITORY_ADMIN_3D.sql",
    "81PLUS_HUB1_LIVE_MASTER_VIVO/sql/MYSQL_DELTA_ASR2025_HUB1.sql",
    # Patch incrementali con eventuali tabelle extra
    "live/sql/patch_v21_token81x.sql",
    "live/sql/patch_v19_pixel.sql",
    "live/sql/patch_v20_abbonamenti.sql",
    "live/sql/patch_v15_preventivi.sql",
    "live/sql/patch_v18_academy_economia.sql",
    "live/sql/patch_v25_monetario.sql",
    "live/sql/patch_v16_scadenze.sql",
    "live/sql/patch_v22_recupero.sql",
    "live/sql/patch_v11_orchestrator.sql",
    "live/sql/patch_v05_swap.sql",
    "live/sql/patch_v04_kyc_wallet.sql",
    "live/sql/patch_v03_identita.sql",
    "live/sql/patch_v07_onboarding.sql",
    "live/sql/patch_v08_modulistica.sql",
    "live/sql/patch_v09_gamification.sql",
    "live/sql/patch_v10_account_security.sql",
    "live/sql/patch_v12_trigger_mail.sql",
    "live/sql/patch_v13_sicurezza.sql",
    "live/sql/patch_v14_telegram.sql",
    "live/sql/patch_v17_fondatori.sql",
    "live/sql/patch_v23_pixel_tipi.sql",
    "live/sql/patch_v24_pix81.sql",
    # Riferimento universale + install
    "live/_reference/02_DATABASE_UNIVERSALE.sql",
    "81PLUS_HUB1_LIVE_MASTER_VIVO/sql/MYSQL1_INSTALL_81PLUS_HUB1.sql",
]

OUT_DB     = os.path.join(ROOT, "DB81+", "81PLUS_GLOBAL_UNIVERSAL.db")
OUT_SCHEMA = os.path.join(ROOT, "DB81+", "81PLUS_GLOBAL_UNIVERSAL.schema.sql")
OUT_REPORT = os.path.join(ROOT, "DB81+", "81PLUS_GLOBAL_UNIVERSAL.report.txt")


# ---------------------------------------------------------------------------
# 1. Lettura + pulizia commenti + split statement
# ---------------------------------------------------------------------------

def strip_block_comments(text):
    return re.sub(r"/\*.*?\*/", "", text, flags=re.DOTALL)


def read_statements(path):
    """Ritorna lista di statement SQL top-level, saltando i blocchi DELIMITER."""
    with open(path, "r", encoding="utf-8", errors="replace") as fh:
        raw = fh.read()
    raw = strip_block_comments(raw)

    lines = []
    in_delimiter = False
    for line in raw.split("\n"):
        s = line.strip()
        if s.upper().startswith("DELIMITER"):
            # entra/esce dal blocco trigger/procedura
            in_delimiter = not in_delimiter or not s.upper().endswith(";")
            # semplice toggle: "DELIMITER $$" apre, "DELIMITER ;" chiude
            in_delimiter = not s.upper().startswith("DELIMITER ;")
            continue
        if in_delimiter:
            continue
        if s.startswith("--") or s.startswith("#"):
            continue
        lines.append(line)
    body = "\n".join(lines)

    # split su ';' rispettando le stringhe single-quote ('' = escape)
    stmts = []
    buf = []
    in_str = False
    i = 0
    n = len(body)
    while i < n:
        c = body[i]
        buf.append(c)
        if c == "'":
            if in_str and i + 1 < n and body[i + 1] == "'":
                buf.append("'")
                i += 2
                continue
            in_str = not in_str
        elif c == ";" and not in_str:
            stmt = "".join(buf[:-1]).strip()
            if stmt:
                stmts.append(stmt)
            buf = []
        i += 1
    tail = "".join(buf).strip()
    if tail:
        stmts.append(tail)
    return stmts


# ---------------------------------------------------------------------------
# 2. Conversione CREATE TABLE MySQL -> SQLite
# ---------------------------------------------------------------------------

def split_top_level(body):
    """Divide il corpo della tabella in segmenti separati da virgole top-level."""
    segs = []
    buf = []
    depth = 0
    in_str = False
    i = 0
    n = len(body)
    while i < n:
        c = body[i]
        if c == "'":
            if in_str and i + 1 < n and body[i + 1] == "'":
                buf.append("''")
                i += 2
                continue
            in_str = not in_str
            buf.append(c)
        elif not in_str and c == "(":
            depth += 1
            buf.append(c)
        elif not in_str and c == ")":
            depth -= 1
            buf.append(c)
        elif not in_str and c == "," and depth == 0:
            segs.append("".join(buf).strip())
            buf = []
        else:
            buf.append(c)
        i += 1
    if "".join(buf).strip():
        segs.append("".join(buf).strip())
    return segs


COMMENT_RE = re.compile(r"\s+COMMENT\s+'(?:[^']|'')*'", re.IGNORECASE)
ENUMSET_RE = re.compile(r"\b(?:ENUM|SET)\s*\((?:[^()']|'(?:[^']|'')*')*\)", re.IGNORECASE)


def process_column(seg, table):
    seg = COMMENT_RE.sub("", seg)
    seg = ENUMSET_RE.sub("TEXT", seg)
    seg = re.sub(r"\bUNSIGNED\b", "", seg, flags=re.IGNORECASE)
    seg = re.sub(r"\bZEROFILL\b", "", seg, flags=re.IGNORECASE)
    seg = re.sub(r"\s+ON\s+UPDATE\s+CURRENT_TIMESTAMP", "", seg, flags=re.IGNORECASE)
    seg = re.sub(r"\bCHARACTER\s+SET\s+\w+", "", seg, flags=re.IGNORECASE)
    seg = re.sub(r"\bCOLLATE\s+\w+", "", seg, flags=re.IGNORECASE)
    seg = re.sub(r"[ \t]+", " ", seg).strip()
    return seg


def col_name(seg):
    m = re.match(r"\s*`([^`]+)`", seg)
    return m.group(1) if m else None


def convert_create_table(stmt, table):
    """Ritorna (create_sql, [index_sql...]) oppure (None, []) se non convertibile."""
    open_paren = stmt.find("(")
    close_paren = stmt.rfind(")")
    if open_paren < 0 or close_paren < 0 or close_paren < open_paren:
        return None, []
    body = stmt[open_paren + 1:close_paren]

    segs = split_top_level(body)

    # trova colonna AUTO_INCREMENT
    autoinc_col = None
    for seg in segs:
        if seg.lstrip().startswith("`") and re.search(r"\bAUTO_INCREMENT\b", seg, re.IGNORECASE):
            autoinc_col = col_name(seg)
            break

    columns = []
    indexes = []

    for seg in segs:
        st = seg.strip()
        low = st.lower()
        is_column = st.startswith("`")

        if is_column:
            name = col_name(st)
            if name and name == autoinc_col:
                columns.append("`%s` INTEGER PRIMARY KEY AUTOINCREMENT" % name)
            else:
                columns.append(process_column(st, table))
            continue

        # ---- vincoli / indici ----
        if low.startswith("primary key"):
            m = re.search(r"primary key\s*\((.*)\)", st, re.IGNORECASE | re.DOTALL)
            cols = m.group(1).strip() if m else ""
            single = re.sub(r"[`\s]", "", cols)
            if autoinc_col and single == autoinc_col:
                continue  # gia gestito nella colonna
            columns.append("PRIMARY KEY (%s)" % cols)
        elif low.startswith("unique key") or low.startswith("unique index") or low.startswith("unique"):
            m = re.search(r"unique(?:\s+key|\s+index)?\s+`?([\w]+)`?\s*\((.*)\)", st, re.IGNORECASE | re.DOTALL)
            if m:
                idx = "%s_%s" % (table, m.group(1))
                indexes.append('CREATE UNIQUE INDEX IF NOT EXISTS `%s` ON `%s` (%s);' % (idx, table, m.group(2).strip()))
            else:
                m2 = re.search(r"unique\s*\((.*)\)", st, re.IGNORECASE | re.DOTALL)
                if m2:
                    cols = m2.group(1).strip()
                    idx = "uq_%s_%s" % (table, re.sub(r"[^\w]", "_", cols))
                    indexes.append('CREATE UNIQUE INDEX IF NOT EXISTS `%s` ON `%s` (%s);' % (idx, table, cols))
        elif low.startswith("key") or low.startswith("index"):
            m = re.search(r"(?:key|index)\s+`?([\w]+)`?\s*\((.*)\)", st, re.IGNORECASE | re.DOTALL)
            if m:
                idx = "%s_%s" % (table, m.group(1))
                cols = re.sub(r"\(\d+\)", "", m.group(2).strip())  # rimuove prefix length col(191)
                indexes.append('CREATE INDEX IF NOT EXISTS `%s` ON `%s` (%s);' % (idx, table, cols))
        elif low.startswith("fulltext") or low.startswith("spatial"):
            continue  # non supportato
        elif low.startswith("constraint") or low.startswith("foreign key"):
            # mantieni FK inline (enforcement disattivato in build)
            seg_fk = COMMENT_RE.sub("", st)
            columns.append(seg_fk.strip())
        else:
            # segmento sconosciuto: mantienilo pulito
            columns.append(process_column(st, table))

    create = "CREATE TABLE IF NOT EXISTS `%s` (\n  %s\n);" % (table, ",\n  ".join(columns))
    return create, indexes


# ---------------------------------------------------------------------------
# 3. Normalizzazione INSERT / VIEW
# ---------------------------------------------------------------------------

def normalize_insert(stmt):
    stmt = re.sub(r"\bINSERT\s+IGNORE\s+INTO\b", "INSERT OR IGNORE INTO", stmt, flags=re.IGNORECASE)
    stmt = re.sub(r"\bINSERT\s+INTO\b", "INSERT OR IGNORE INTO", stmt, flags=re.IGNORECASE)
    stmt = re.sub(r"\bON\s+DUPLICATE\s+KEY\s+UPDATE\b.*$", "", stmt, flags=re.IGNORECASE | re.DOTALL)
    stmt = re.sub(r"\bNOW\s*\(\s*\)", "CURRENT_TIMESTAMP", stmt, flags=re.IGNORECASE)
    stmt = re.sub(r"\bUTC_TIMESTAMP\s*\(\s*\)", "CURRENT_TIMESTAMP", stmt, flags=re.IGNORECASE)
    stmt = re.sub(r"\bCURDATE\s*\(\s*\)", "CURRENT_DATE", stmt, flags=re.IGNORECASE)
    return stmt.strip()


def insert_target(stmt):
    m = re.search(r"INSERT\s+(?:OR\s+IGNORE\s+|IGNORE\s+)?INTO\s+`?([\w]+)`?", stmt, re.IGNORECASE)
    return m.group(1) if m else None


def normalize_view(stmt):
    stmt = re.sub(r"CREATE\s+OR\s+REPLACE\s+VIEW", "CREATE VIEW IF NOT EXISTS", stmt, flags=re.IGNORECASE)
    stmt = re.sub(r"CREATE\s+VIEW\s+(?!IF\s+NOT\s+EXISTS)", "CREATE VIEW IF NOT EXISTS ", stmt, flags=re.IGNORECASE)
    return stmt.strip()


# ---------------------------------------------------------------------------
# 4. Build
# ---------------------------------------------------------------------------

def main():
    tables = {}       # name -> (create_sql, source)
    indexes = []      # (sql, table)
    views = []        # (name, sql, source)
    inserts = []      # (table, sql, source)
    alters = []       # (sql, source)
    skipped = []      # (kind, source, snippet)

    for rel in SOURCES:
        path = os.path.join(ROOT, rel)
        if not os.path.exists(path):
            skipped.append(("MISSING_FILE", rel, ""))
            continue
        for stmt in read_statements(path):
            head = stmt.lstrip()[:40].upper()
            if head.startswith("CREATE TABLE"):
                m = re.search(r"CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?`?([\w]+)`?", stmt, re.IGNORECASE)
                if not m:
                    skipped.append(("BAD_CREATE", rel, stmt[:60]))
                    continue
                name = m.group(1)
                if name in tables:
                    continue  # gia definita da fonte a priorita maggiore
                create, idxs = convert_create_table(stmt, name)
                if create is None:
                    skipped.append(("UNCONVERTIBLE_TABLE:" + name, rel, stmt[:60]))
                    continue
                tables[name] = (create, rel)
                for ix in idxs:
                    indexes.append((ix, name))
            elif head.startswith("CREATE") and "VIEW" in head:
                vm = re.search(r"VIEW\s+(?:IF\s+NOT\s+EXISTS\s+)?`?([\w]+)`?", stmt, re.IGNORECASE)
                vname = vm.group(1) if vm else "view_%d" % len(views)
                views.append((vname, normalize_view(stmt), rel))
            elif head.startswith("INSERT"):
                tgt = insert_target(stmt)
                inserts.append((tgt, normalize_insert(stmt), rel))
            elif head.startswith("ALTER TABLE"):
                alters.append((stmt, rel))
            elif head.startswith(("SET ", "USE ", "START ", "COMMIT", "CREATE DATABASE",
                                   "CREATE TRIGGER", "DROP ", "LOCK ", "UNLOCK", "/*")):
                continue
            else:
                skipped.append(("SKIP_STMT", rel, stmt[:50].replace("\n", " ")))

    # --- costruzione DB ---
    if os.path.exists(OUT_DB):
        os.remove(OUT_DB)
    con = sqlite3.connect(OUT_DB)
    con.execute("PRAGMA foreign_keys = OFF;")
    cur = con.cursor()

    ok_tables, err_tables = [], []
    for name, (create, src) in tables.items():
        try:
            cur.execute(create)
            ok_tables.append(name)
        except Exception as e:
            err_tables.append((name, str(e), src))

    ok_idx = 0
    for ix, tbl in indexes:
        if tbl not in ok_tables:
            continue
        try:
            cur.execute(ix)
            ok_idx += 1
        except Exception as e:
            skipped.append(("INDEX_FAIL:" + tbl, "-", str(e)[:80]))

    # ALTER TABLE ADD COLUMN (best effort, una colonna per volta)
    ok_alter = 0
    for stmt, src in alters:
        for piece in re.split(r",\s*(?=ADD\b)", stmt, flags=re.IGNORECASE):
            am = re.match(r"(?:ALTER\s+TABLE\s+`?[\w]+`?\s+)?ADD\s+(?:COLUMN\s+)?`?([\w]+)`?\s+(.*)",
                          piece.strip(), re.IGNORECASE | re.DOTALL)
            tm = re.search(r"ALTER\s+TABLE\s+`?([\w]+)`?", stmt, re.IGNORECASE)
            if not am or not tm:
                continue
            tbl = tm.group(1)
            if tbl not in ok_tables:
                continue
            coldef = process_column("`%s` %s" % (am.group(1), am.group(2)), tbl)
            try:
                cur.execute("ALTER TABLE `%s` ADD COLUMN %s;" % (tbl, coldef.split("`", 2)[-1].strip() and coldef))
                ok_alter += 1
            except Exception:
                pass  # colonna gia esistente o non applicabile

    ok_ins, err_ins = 0, 0
    for tgt, stmt, src in inserts:
        if tgt not in ok_tables:
            err_ins += 1
            continue
        try:
            cur.execute(stmt)
            ok_ins += 1
        except Exception as e:
            err_ins += 1
            skipped.append(("INSERT_FAIL:" + str(tgt), "-", str(e)[:80]))

    ok_views = 0
    for vname, stmt, src in views:
        try:
            cur.execute(stmt)
            ok_views += 1
        except Exception as e:
            skipped.append(("VIEW_FAIL:" + vname, "-", str(e)[:80]))

    con.commit()

    # conteggio righe reali
    row_counts = {}
    for name in ok_tables:
        try:
            row_counts[name] = cur.execute("SELECT COUNT(*) FROM `%s`" % name).fetchone()[0]
        except Exception:
            row_counts[name] = -1
    con.close()

    # --- schema consolidato leggibile ---
    with open(OUT_SCHEMA, "w", encoding="utf-8") as fh:
        fh.write("-- 81+ GLOBAL UNIVERSAL — schema SQLite consolidato\n")
        fh.write("-- Generato da build_sqlite_universal.py\n")
        fh.write("-- Tabelle: %d | Indici: %d | View: %d\n\n" % (len(ok_tables), ok_idx, ok_views))
        fh.write("PRAGMA foreign_keys = OFF;\nBEGIN;\n\n")
        for name, (create, src) in tables.items():
            if name in ok_tables:
                fh.write("-- fonte: %s\n%s\n\n" % (src, create))
        for ix, tbl in indexes:
            if tbl in ok_tables:
                fh.write(ix + "\n")
        fh.write("\n")
        for vname, stmt, src in views:
            fh.write(stmt + ";\n")
        fh.write("\nCOMMIT;\n")

    # --- report ---
    with open(OUT_REPORT, "w", encoding="utf-8") as fh:
        fh.write("=" * 64 + "\n")
        fh.write("81+ GLOBAL UNIVERSAL — REPORT BUILD SQLite\n")
        fh.write("=" * 64 + "\n\n")
        fh.write("DB:      %s\n" % OUT_DB)
        fh.write("Tabelle: %d create, %d errori\n" % (len(ok_tables), len(err_tables)))
        fh.write("Indici:  %d\n" % ok_idx)
        fh.write("View:    %d\n" % ok_views)
        fh.write("ALTER:   %d applicati\n" % ok_alter)
        fh.write("INSERT:  %d ok, %d saltati/errore\n\n" % (ok_ins, err_ins))

        fh.write("--- TABELLE (righe seed) ---\n")
        for name in sorted(ok_tables):
            fh.write("  %-40s %8d righe   [%s]\n" % (name, row_counts.get(name, -1), tables[name][1].split('/')[-1]))
        if err_tables:
            fh.write("\n--- TABELLE IN ERRORE ---\n")
            for name, err, src in err_tables:
                fh.write("  %s: %s (%s)\n" % (name, err, src))
        if skipped:
            fh.write("\n--- STATEMENT SALTATI (%d) ---\n" % len(skipped))
            from collections import Counter
            kinds = Counter(k.split(":")[0] for k, _, _ in skipped)
            for k, c in kinds.most_common():
                fh.write("  %-24s %d\n" % (k, c))

    print("OK tabelle:", len(ok_tables), "| errori:", len(err_tables),
          "| view:", ok_views, "| insert:", ok_ins, "| indici:", ok_idx)
    print("Totale righe seed:", sum(v for v in row_counts.values() if v > 0))
    if err_tables:
        print("ERRORI TABELLE:")
        for name, err, src in err_tables[:20]:
            print("  -", name, "->", err)
    return 0 if not err_tables else 1


if __name__ == "__main__":
    sys.exit(main())
