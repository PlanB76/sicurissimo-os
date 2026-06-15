# GENESYS81+ LAUNCH STRUCTURE

## FASI

### Fase 0 — Pre-lancio (adesso)
- Costruzione MASTER VIVO
- Build tecnico HUB1
- Test interni
- Preparazione materiali webinar

### Fase 1 — Webinar Privato Leader
- Target: leader selezionati
- Formato: call privata Zoom/Meet
- Obiettivo: onboarding primi 20-50 leader
- Output: iscrizioni GENESYS, candidature PIX Founder

### Fase 2 — Webinar Pubblico con Leader
- Target: community leader + pubblico
- Formato: live YouTube/Zoom
- Obiettivo: onboarding massa
- Output: registrazioni HUB1, candidature GENESYS

### Fase 3 — Wave 1 Live
- HUB1 pubblico
- Promo GENESYS attiva (90 giorni)
- 200 PIX Founder slot aperti
- Telegram privato aperto
- Call cadenzate

### Fase 4 — Espansione
- Wave 2: HUB2 81plus.it (MEMBER81+ 5 livelli PRO)
- Apertura POINT81+ primo territorio
- NFT81+ mint aperto
- 81plus.space preview

## STATUS GENESYS IN DB

genesys_status ENUM:
NONE, GENESYS_MEMBER, GENESYS_NETWORKER, GENESYS_LEADER, GENESYS_FOUNDER

genesys_promo_active: 0/1
genesys_promo_expires: DATE

## FORM CANDIDATURA GENESYS

- Nome cognome
- Email (pre-compilata se loggato)
- SIC-ID (pre-compilato)
- Settore di provenienza
- Community / pubblico (dimensione)
- Piattaforma principale (Telegram/YouTube/Instagram/altro)
- Motivazione (testo libero)
- Interesse per PIX Founder: sì/no
- Come hai conosciuto 81+

Output: candidatura salvata in genesys_applications,
notifica admin, autoresponder email.
