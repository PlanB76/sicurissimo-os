#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
TELEGRAM CONTENT ENGINE 81+ — 24 azioni/ora, ciclo 30 giorni mai ripetuto
Crea: telegram_hourly_slots (24 slot fissi, 1 per ora), telegram_creative_formats
(24 formati "mai visti" ideati per l'ecosistema), telegram_30day_cycle (30 giorni,
combina tema normativo settimanale + formato creativo + focus prodotto scala valore,
si ripete ciclicamente ogni mese cambiando pero sempre i contenuti concreti generati
dall'AI), sicu_video_library (ingestion dei video Sicu generati su Gemini da Mirco).
"""
import os
import sqlite3

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB = os.path.join(ROOT, "81PLUS_GLOBAL_MASTER", "81plus.net", "0-81PLUS.NET",
                  "81PLUS_GLOBAL_UNIVERSAL.db")

HOURLY_SLOTS = [
    (0,  "RECAP",             "Buonanotte community - recap soft, mai commerciale"),
    (1,  "ENTERTAINMENT",     "Contenuto leggero notturno per fusi orari/insonni"),
    (2,  "CROSS_PROMO",       "Silenzio o cross-promo automatico minimo"),
    (3,  "CROSS_PROMO",       "Silenzio o cross-promo automatico minimo"),
    (4,  "DAO_UPDATE",        "Slot di servizio, solo se contenuto reale"),
    (5,  "NEWS_HOOK",         "Prima news del giorno, prep mattina"),
    (6,  "CHECKLIST_FREEBIE", "Sveglia operativa: checklist/freebie del giorno"),
    (7,  "NEWS_HOOK",         "News principale con hook, picco mattutino"),
    (8,  "MITO_FATTO",        "Educazione leggera, cafe break"),
    (9,  "MISSIONE_ANNOUNCE", "Missione/gioco del giorno si apre"),
    (10, "VIDEO_SHORT",       "Video Sicu o repurposing YouTube"),
    (11, "CASO_REALE",        "Storia/caso reale prima di pranzo"),
    (12, "SONDAGGIO",         "Sondaggio pausa pranzo, alto engagement"),
    (13, "QUIZ_ENGAGEMENT",   "Quiz pomeridiano, picco interazione"),
    (14, "TESTIMONIAL",       "Riprova sociale primo pomeriggio"),
    (15, "DOMANDA_APERTA",    "Community: domanda aperta, stimola commenti"),
    (16, "CTA_SOFT",          "Nudge morbido verso un prodotto scala valore"),
    (17, "BADGE_CELEBRATION", "Celebrazione pubblica traguardi utenti del giorno"),
    (18, "VIDEO_LUNGO",       "Video piu lungo/live, picco serale inizio"),
    (19, "RANK_PROGRESS",     "Aggiornamento rank/progressi (solo gruppi status)"),
    (20, "CTA_HARD",          "CTA diretta di conversione, picco serale"),
    (21, "LEADERBOARD",       "Classifica del giorno/settimana"),
    (22, "EVENT_REMINDER",    "Promemoria eventi/webinar/live prossimi"),
    (23, "UPSELL_NUDGE",      "Ultimo slot: upsell soft prima di chiudere la giornata"),
]

CREATIVE_FORMATS = [
    (1,  "Caccia al Cavillo",       "QUIZ_ENGAGEMENT", "Foto di uno scenario reale (cucina/cantiere/ufficio): trova l'errore di compliance nascosto. Prima risposta esatta nei commenti vince PV+.", "TUTTI"),
    (2,  "Il Minuto della Verita",  "MISSIONE_ANNOUNCE", "Sfida a timer: 60 secondi per rispondere a 5 domande di autovalutazione sulla propria azienda. Risultato = punteggio rischio personale.", "PUBBLICO"),
    (3,  "Duello 81+",              "SONDAGGIO", "Due scenari aziendali anonimizzati messi a confronto: la community vota quale e piu in regola. Rivelazione e spiegazione il giorno dopo.", "TUTTI"),
    (4,  "L'Ispettore Invisibile",  "QUIZ_ENGAGEMENT", "Quiz a bivi in stile 'scegli la tua avventura': sei l'ispettore, cosa controlli per primo? Ogni scelta porta a un esito diverso.", "PUBBLICO"),
    (5,  "Sicu Risponde",           "AI_QA_SLOT", "AMA con la mascotte Sicu (video Gemini): risponde alle 3 domande piu votate della settimana in un video breve.", "TUTTI"),
    (6,  "Il Cavillo della Settimana","MITO_FATTO", "Approfondimento leggero su una norma oscura/dimenticata, raccontata come una curiosita, non come uno sbadiglio normativo.", "PUBBLICO"),
    (7,  "Torneo dei Territori",    "LEADERBOARD", "Campionato mensile a punti tra territori Franchisee: KPI reali (attivazioni, rinnovi) trasformati in classifica sportiva con podio.", "FRANCHISEE81+"),
    (8,  "La Ruota della Sicurezza","MISSIONE_ANNOUNCE", "GIF di una ruota che gira (Wheel of Fortune style): premio in PV+ o sconto a sorpresa, generata e postata ogni venerdi.", "TUTTI"),
    (9,  "Trova le 5 Differenze",  "QUIZ_ENGAGEMENT", "Due immagini quasi identiche di un ambiente di lavoro: trova le 5 differenze di sicurezza tra le due.", "PUBBLICO"),
    (10, "Racconta la tua Salvezza","TESTIMONIAL", "Storie utente (anonime se richiesto) di infortuni/problemi evitati grazie a una procedura fatta bene. Voto community, premio al racconto del mese.", "TUTTI"),
    (11, "81+ Trivia Night",        "EVENT_REMINDER", "Evento live mensile in stile quiz show: domande a raffica, classifica in tempo reale, premio finale in PV+.", "MEMBERSHIP"),
    (12, "Il Barometro della Community","SONDAGGIO", "Sondaggio settimanale sull'umore/priorita della community, risultato mostrato come un termometro/barometro grafico.", "PUBBLICO"),
    (13, "La Cassaforte 81+",       "MISSIONE_ANNOUNCE", "Meccanica a sblocco collettivo: ogni tot commenti/reazioni si sblocca un pezzo di codice sconto, fino alla combinazione finale.", "PUBBLICO"),
    (14, "Rewind 81+",              "RECAP", "Recap mensile personalizzato in stile 'Spotify Wrapped': i tuoi PV+ del mese, il tuo rank, la tua streak, il tuo traguardo piu grande.", "TUTTI"),
    (15, "La Sfida dei 7 Giorni",   "MISSIONE_ANNOUNCE", "Serie settimanale a tappe (giorno 1 check DVR, giorno 2 check HACCP...): badge speciale a chi completa tutti e 7 i giorni.", "PUBBLICO"),
    (16, "Chiedi a CORTEX81+ Live", "AI_QA_SLOT", "Slot fisso dove l'AI CORTEX81+ risponde in diretta (testo) alle domande piu votate della giornata, in pubblico nel gruppo.", "TUTTI"),
    (17, "L'Angolo del Territorio", "CASO_REALE", "Spotlight settimanale su un territorio Franchisee diverso: storia, numeri, un consiglio del titolare locale.", "FRANCHISEE81+"),
    (18, "Meme Monday Compliance",  "ENTERTAINMENT", "Meme a tema compliance/lavoro (mai offensivo), generato o proposto dalla community ogni lunedi.", "PUBBLICO"),
    (19, "Il Podio del Rank",       "BADGE_CELEBRATION", "Celebrazione visiva (immagine con scudo/pass) di chi e salito di rank Network/Elite/VIP nella settimana.", "STATUS_RETE"),
    (20, "Combo del Giorno",        "CTA_HARD", "Reveal mystery-box: 'scopri quale prodotto ha lo sconto oggi' con un breve countdown/teaser prima della rivelazione.", "MEMBERSHIP"),
    (21, "Chi Vuol Essere Auditor", "QUIZ_ENGAGEMENT", "Quiz progressivo in stile 'Chi vuol essere milionario': 10 domande a difficolta crescente su normativa, con 'aiuti' (chiedi alla community, 50:50).", "PUBBLICO"),
    (22, "Video Sicu del Giorno",   "VIDEO_SHORT", "Video generato con Gemini della mascotte Sicu (max 3/giorno da Mirco, prompt extra su richiesta), tema coerente col giorno del ciclo.", "TUTTI"),
    (23, "Recap Annuale 81+",       "RECAP", "Il 31 dicembre: recap dell'anno intero dell'ecosistema, numeri aggregati community, anticipazione anno nuovo.", "PUBBLICO"),
    (24, "Ambassador Spotlight",    "TESTIMONIAL", "Intervista breve mensile a un membro/networker distintosi, in formato Q&A scritto o video.", "MEMBERSHIP"),
]

TEMI = ["Sicurezza 81/08", "Rischio chimico", "HACCP alimentare", "Stress lavoro-correlato",
        "YouTube/casi reali", "Strategia ISO", "Storytelling community"]
FORMAT_ORDER = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,1,2,3,4,5,6]
PRODOTTO_SETTIMANA = {1: "FREEBIE_TRIPWIRE", 2: "MEMBERSHIP_UPSELL", 3: "PACCHETTI_SIGILLI",
                      4: "NETWORK_STATUS_ALTO_TICKET", 5: "MIX_RECAP"}


def main():
    con = sqlite3.connect(DB)
    cur = con.cursor()

    cur.execute("DROP TABLE IF EXISTS telegram_hourly_slots")
    cur.execute("""CREATE TABLE telegram_hourly_slots (
        ora INTEGER PRIMARY KEY, categoria_primaria VARCHAR(30) NOT NULL,
        ruolo_slot VARCHAR(60) NOT NULL)""")
    cur.executemany("INSERT INTO telegram_hourly_slots VALUES (?,?,?)", HOURLY_SLOTS)

    cur.execute("DROP TABLE IF EXISTS telegram_creative_formats")
    cur.execute("""CREATE TABLE telegram_creative_formats (
        id INTEGER PRIMARY KEY, nome VARCHAR(40) NOT NULL,
        categoria_taxonomy VARCHAR(30) NOT NULL, descrizione VARCHAR(300) NOT NULL,
        target_chat VARCHAR(30) NOT NULL)""")
    cur.executemany("INSERT INTO telegram_creative_formats VALUES (?,?,?,?,?)", CREATIVE_FORMATS)

    cur.execute("DROP TABLE IF EXISTS telegram_30day_cycle")
    cur.execute("""CREATE TABLE telegram_30day_cycle (
        giorno_ciclo INTEGER PRIMARY KEY, tema_normativo VARCHAR(30) NOT NULL,
        formato_creativo_id INTEGER NOT NULL, prodotto_focus VARCHAR(30) NOT NULL,
        FOREIGN KEY(formato_creativo_id) REFERENCES telegram_creative_formats(id))""")
    rows = []
    for day in range(1, 31):
        tema = TEMI[(day - 1) % 7]
        fmt = FORMAT_ORDER[day - 1]
        settimana = min(5, (day - 1) // 7 + 1)
        rows.append((day, tema, fmt, PRODOTTO_SETTIMANA[settimana]))
    cur.executemany("INSERT INTO telegram_30day_cycle VALUES (?,?,?,?)", rows)

    cur.execute("DROP TABLE IF EXISTS sicu_video_library")
    cur.execute("""CREATE TABLE sicu_video_library (
        id INTEGER PRIMARY KEY AUTOINCREMENT, data_generazione DATE,
        prompt_usato TEXT NOT NULL, file_path VARCHAR(300) DEFAULT '',
        stato VARCHAR(20) DEFAULT 'DA_SCARICARE', usato_in_chat VARCHAR(40) DEFAULT '',
        usato_il DATE)""")

    con.commit()
    for t in ("telegram_hourly_slots", "telegram_creative_formats",
              "telegram_30day_cycle", "sicu_video_library"):
        print(f"  {t}: {cur.execute('SELECT COUNT(*) FROM ' + t).fetchone()[0]} righe")
    print("  integrity:", cur.execute("PRAGMA integrity_check").fetchone()[0])
    con.close()


if __name__ == "__main__":
    main()
