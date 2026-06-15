# DASHBOARD UNIFICATA SPEC

## FILE: dashboard.php

Una dashboard unica per tutti i ruoli.
I moduli visibili cambiano in base al ruolo e allo status.

## RUOLI PRINCIPALI

- MEMBER81+
- NETWORKER81+
- ELITE81+
- ADMIN81+

## GENESYS STATUS

- NONE
- GENESYS_MEMBER
- GENESYS_NETWORKER
- GENESYS_LEADER
- GENESYS_FOUNDER

## MEMBER81+ VEDE

SIC-ID, Wallet81+ (PV/PV+), Membership, PayGate81+, Preventivo,
Audit, Academy81+ Core, DOC81+ Builder, Scadenziario81+,
Strumenti81+, Gamification, PV+ Booster81+, ReferralLink81+,
PIX81+, Green81+, News operative, Newsletter, Eventi.

## NETWORKER81+ VEDE

Tutto MEMBER81+ più:
NETWORK81+, SCOUT81+, SCOUT81+ Map, SCOUT81+ Radar,
SCOUT81+ Prospect Finder, SCOUT81+ Territory Scan, PLP81+ Pack,
SDK+, SDP+, Referral link, QR personale, Pipeline3D81+,
Lead assegnati, Piano Compensi81+ PDF + simulatore,
Piano Marketing81+ PDF + strumento, Piano Equilibrio81+ PDF,
Equilibrio3D81+, Compensi3D81+, Materiali social, Materiali webinar,
Telegram Networker, Academy81+ Pro Skills.

## ELITE81+ VEDE

Tutto NETWORKER81+ più:
Franchising, POINT81+, Club81+, TerritoryMap81+, SDP+ Royal,
SDK+ Royal, Gestione territorio, Candidatura area, Eventi VIP,
Call Elite, Academy81+ Elite Mastery, PIX81+ Founder, GENESYS Founder status.

## ADMIN81+ VEDE

Tutto. Admin Command Center81+ globale.

## STRUTTURA TECNICA

```
dashboard.php
├── includes/auth_check.php
├── includes/role_loader.php
├── modules/
│   ├── mod_wallet.php
│   ├── mod_membership.php
│   ├── mod_audit.php
│   ├── mod_preventivo.php
│   ├── mod_paygate.php
│   ├── mod_academy.php
│   ├── mod_doc81.php
│   ├── mod_scadenziario.php
│   ├── mod_gamification.php
│   ├── mod_pvplus_booster.php
│   ├── mod_referral.php
│   ├── mod_pix81.php
│   ├── mod_green81.php
│   ├── mod_scout81.php (NETWORKER+)
│   ├── mod_plp81.php (NETWORKER+)
│   ├── mod_pipeline3d.php (NETWORKER+)
│   ├── mod_territory.php (ELITE+)
│   ├── mod_club81.php (ELITE+)
│   ├── mod_franchising.php (ELITE+)
│   └── mod_admin_center.php (ADMIN)
```
