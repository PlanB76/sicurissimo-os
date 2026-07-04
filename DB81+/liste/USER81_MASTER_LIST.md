# USER81+ MASTER LIST — Ecosistema 81+

Generata dal DB unico universale `DB81+/81PLUS_GLOBAL_UNIVERSAL.db`.

## Gestione SIC-ID nel DB81+

Ogni user ha un codice univoco con sintassi `SIC-ID-XXXXXXXXXXXX` (12 cifre sequenziali).
La sezione del DB che gestisce il SIC-ID di ogni user e la tabella **`user81`**:

| Campo | Descrizione |
|-------|-------------|
| `sic_id` | Codice univoco SIC-ID-XXXXXXXXXXXX (UNIQUE, indicizzato) |
| `lista` | LISTA1_EMAIL_WA / LISTA2_WA / POOL_EMAIL |
| `nome`, `cognome`, `azienda` | Anagrafica |
| `email`, `pec` | Canale email (validato) |
| `telefono`, `whatsapp` | Canale SMS/WA (normalizzato +39) |
| `citta`, `provincia`, `indirizzo` | Localizzazione |
| `piva` | Partita IVA se nota |
| `fonti` | Origini del contatto (dedup tracciato) |
| `segmento` | FREDDO di default, aggiornato dal lead scoring (Skill 38) |
| `consenso_email`, `consenso_wa` | Double opt-in GDPR: 0 finche non confermato |

View operative: `v_user81_lista1`, `v_user81_lista2`, `v_prospect81_pool`.

## Numeri

| Lista | User | Uso |
|-------|------|-----|
| LISTA 1 — Email + SMS/WA | 1852 | Campagne email + WhatsApp, nurturing completo |
| LISTA 2 — Solo SMS/WA | 0 | Campagne WhatsApp/SMS |
| POOL — Solo email | 2460 | Nurturing email, da arricchire con telefono |
| **TOTALE USER81+** | **4312** | |

## LISTA 1 — USER81+ Email + SMS/WA (1852 user)

| SIC-ID | Nome | Cognome | Azienda | Email | Telefono/SMS/WA | Citta | Prov |
|---|---|---|---|---|---|---|---|
| SIC-ID-000000000001 | 2S Group srls |  | 2S Group srls | info@2sgroup.net | +390664001765 |  |  |
| SIC-ID-000000000002 | 3c Costruzioni 2012 S.R.L. |  | 3c Costruzioni 2012 S.R.L. | info@politect.it | +390661520357 |  |  |
| SIC-ID-000000000003 | Gianluca Prescianotto |  | 3G s.n.c. di Prescianotto Gianluca & C. | 3.g@tiscali.it | +393483844651 | Porto Viro | RO |
| SIC-ID-000000000004 | 3l Costruzioni |  | 3l Costruzioni | costruzioni.3l@libero.it | +39041640108 |  |  |
| SIC-ID-000000000005 | A | Catalano |  | a.catalano@cestarorossi.com | +393283564959 |  |  |
| SIC-ID-000000000006 | A.C.P. | Ponteggi |  | info@acpponteggi.it | +390457930749 |  |  |
| SIC-ID-000000000007 | A.F.M. Ascensori Srl |  | A.F.M. Ascensori Srl | commerciale@afmascensori.it | +390666019120 |  |  |
| SIC-ID-000000000008 | Abitare Magazzino Edile |  | Abitare Magazzino Edile | privacy.idrocentro@legalmail.it | +390171409001 |  |  |
| SIC-ID-000000000009 | ABM | Hossain |  | abm.moakkher@gmail.com | +393283352697 |  |  |
| SIC-ID-000000000010 | Accardi Pitturazioni |  | Accardi Pitturazioni | contatto@esempio.it | +393273107097 |  |  |
| SIC-ID-000000000011 | Acquadolce |  |  | info@acquadolce.info | +390583779534 |  |  |
| SIC-ID-000000000012 | Acrobatica | Roma Trionfale |  | m.baldassarre@ediliziacrobatica.com | +39800826969 |  |  |
| SIC-ID-000000000013 | Adicem Edilizia S.r.l. |  | Adicem Edilizia S.r.l. | o@adicem.it | +390957892790 |  |  |
| SIC-ID-000000000014 | Adrianozamana |  |  | adrianozamana@gmail.com | +393383233672 |  |  |
| SIC-ID-000000000015 | AG Dimensione Edilizia Srls |  | AG Dimensione Edilizia Srls | alloisig@gmail.com | +393664386271 |  |  |
| SIC-ID-000000000016 | Agati & carianni - impresa edile |  | Agati & carianni - impresa edile | info@agaticarianni.it | +393335782407 |  |  |
| SIC-ID-000000000017 | Agenziagiusy |  | Agenziagiusy | agenziagiusy@gmail.com | +393284660984 |  |  |
| SIC-ID-000000000018 | Agr Legno S.r.l. Sardegna |  | Agr Legno S.r.l. Sardegna | info@agrlegno.com | +390704594781 |  |  |
| SIC-ID-000000000019 | Agricolmeccanica Di Milazzo Matteo |  | Agricolmeccanica Di Milazzo Matteo | agricolmeccanica@gmail.com | +390923981061 |  |  |
| SIC-ID-000000000020 | Aleandri Project & Consulting Srl |  | Aleandri Project & Consulting Srl | umberto@aleandri.net | +39065818999 |  |  |
| SIC-ID-000000000021 | Alessandra | Lyo |  | alemorandin29@gmail.com | +393921843007 |  |  |
| SIC-ID-000000000022 | Alessandro | Itinerando |  | alebasso.ab@gmail.com | +393495564808 |  |  |
| SIC-ID-000000000023 | Alessandro | Menini |  | alessandro.menini@uteco.com | +393392420336 |  |  |
| SIC-ID-000000000024 | Alessandro | Obinu |  | marco.orsini13@libero.it | +393392420336 |  |  |
| SIC-ID-000000000025 | Alessandro | PA CARD Travaini |  | ale.trava.seller4you@gmail.com | +393282192897 |  |  |
| SIC-ID-000000000026 | Alessandro | ROBBINS |  | alexander.maganuco@gmail.com | +393496921925 |  |  |
| SIC-ID-000000000027 | Alessandro | Sala |  | centrosicurezza2020@gmail.com | +393341640492 |  |  |
| SIC-ID-000000000028 | Alessandro | Zaninello |  | zani79@gmail.com | +393339755718 |  |  |
| SIC-ID-000000000029 | Alessandro | Zennaro |  | alessandrozennaro@hotmail.it | +393484421068 |  |  |
| SIC-ID-000000000030 | Alessandro | wind |  | aleascalone@gmail.com | +393201745111 |  |  |
| SIC-ID-000000000031 | Alessandro | Bortoloni |  | 94ale.b@gmail.com | +393335030436 |  |  |
| SIC-ID-000000000032 | Alessandro Cianci / Termografia edile Roma |  | Alessandro Cianci / Termografia edile Roma | ale.cianci2@gmail.com | +393346750484 |  |  |
| SIC-ID-000000000033 | Alessandro nardi / ingegnere & consulente immobiliare |  | Alessandro nardi / ingegnere & consulente immobiliare | info@alessandronardi.info | +393514373460 |  |  |
| SIC-ID-000000000034 | Alessandrogrossi |  |  | alessandrogrossi@live.it | +393494692065 |  |  |
| SIC-ID-000000000035 | Alessandrotecchiati |  |  | alessandrotecchiati@tiscali.it | +393389404230 |  |  |
| SIC-ID-000000000036 | Alessia | Beduschi |  | alessia@serecom.it | +393280707946 |  |  |
| SIC-ID-000000000037 | Alessia | Beduschi |  | alessia.beduschi@simtree.it | +393284648947 |  |  |
| SIC-ID-000000000038 | Alessia | Milani |  | alessiamilani2@gmail.com | +393477322076 |  |  |
| SIC-ID-000000000039 | Alessia | Natta |  | alessia.natta@gmail.com | +393408744824 |  |  |
| SIC-ID-000000000040 | Alessiascapocchin85 |  |  | alessiascapocchin85@gmail.com | +3934961935080 |  |  |
| SIC-ID-000000000041 | Alessio |  |  | alessio@fb-fantasy.it | +393387828022 |  |  |
| SIC-ID-000000000042 | Alessio | Aumar |  | alessio75v@gmail.com | +393288174041 |  |  |
| SIC-ID-000000000043 | Alessio | RICARICA |  | alessio.forzan@deledsrl.it | +393319956833 |  |  |
| SIC-ID-000000000044 | Alessio Tesconi formatore urbanistica ed edilizia |  | Alessio Tesconi formatore urbanistica ed edilizia | info@camurbanstudio.com | +393474305332 |  |  |
| SIC-ID-000000000045 | Alessio0408 | Guantini |  | alessio0408@gmail.com | +393319956833 |  |  |
| SIC-ID-000000000046 | Alessio_panaro | Panaro |  | alessio_panaro@libero.it | +393483384253 |  |  |
| SIC-ID-000000000047 | ALEX | CHIARION |  | alex.chiarion@gmail.com | +393351308732 |  |  |
| SIC-ID-000000000048 | Alice | Finardi |  | alice.finardi@ciacarr.it | +393490568987 |  |  |
| SIC-ID-000000000049 | Alice | Vendemiati |  | alice.vendemiati@gmail.com | +3933341521060 |  |  |
| SIC-ID-000000000050 | Backup_sicurissimo Engine v1_2026-02-24_03-38 estr |  | Alimentari Favaron S.n.c. di Favaron Luigi & C. | alim.favaron@gmail.com | +3934055512070 | Loreo | RO |
| SIC-ID-000000000051 | Spinello Natalino |  | Alimentari Spinello Natalino | alimentari.spinello@libero.it | +390426660269 | Taglio di Po | RO |
| SIC-ID-000000000052 | Alpiturist |  |  | info@alpiturist.com | +390424692435 |  |  |
| SIC-ID-000000000053 | Altaquota Srls lavori edili |  | Altaquota Srls lavori edili | lavoriinquota@hotmail.com | +393938765632 |  |  |
| SIC-ID-000000000054 | Alteda Costruzioni |  | Alteda Costruzioni | info@altedacostruzioni.it | +390432760714 |  |  |
| SIC-ID-000000000055 | AM | LIVING |  | info@amliving.it | +393407879325 |  |  |
| SIC-ID-000000000056 | Amato Costruzioni srl |  | Amato Costruzioni srl | costruzioniamat@gmail.com | +39092421645 |  |  |
| SIC-ID-000000000057 | Ambiente Lavori Infinite Soluzioni S.r.l. |  | Ambiente Lavori Infinite Soluzioni S.r.l. | info@ambientelavorinfinitesoluzioni.it | +390689671725 |  |  |
| SIC-ID-000000000058 | Ambra | CDNE |  | a.romagnolo@cdne.it | +393462458241 |  |  |
| SIC-ID-000000000059 | Amfroldi Maria |  | Amfroldi Maria | amfroldi@gmail.com | +393355249394 |  |  |
| SIC-ID-000000000060 | Amiternum Edilizia |  | Amiternum Edilizia | amiternumedilizia@gmail.com | +393462271833 |  |  |
| SIC-ID-000000000061 | ANAS S.p.A. - Direzione Generale |  | ANAS S.p.A. - Direzione Generale | fflagello@ana.net | +39390644461 |  |  |
| SIC-ID-000000000062 | Ance | Lazio-Urcel |  | ancelazio@ancelazio.it | +39063221128 |  |  |
| SIC-ID-000000000063 | ANCE National Association of Builders |  | ANCE National Association of Builders | nuscab@ance.it | +393906845671 |  |  |
| SIC-ID-000000000064 | Andrea | Bragagnolo Automatismi |  | andrea.bragagnolo@bmautomazioni.com | +393383068885 |  |  |
| SIC-ID-000000000065 | Andrea | Depaolipd |  | andrea.depaolipd@libero.it | +393486067770 |  |  |
| SIC-ID-000000000066 | Andrea | FINOTTO |  | andrea.finotto1986@gmail.com | +393477289252 |  |  |
| SIC-ID-000000000067 | Andrea | Lyo |  | andreagazzola1979@gmail.com | +393482489312 |  |  |
| SIC-ID-000000000068 | Andrea | Lyo |  | a.bezze75@gmail.com | +393483130155 |  |  |
| SIC-ID-000000000069 | Andrea | Lyo |  | andrea_zorzi@outlook.it | +393496288555 |  |  |
| SIC-ID-000000000070 | Andrea | Lyo |  | fattoretto76@libero.it | +393478478680 |  |  |
| SIC-ID-000000000071 | Andrea | Roccato |  | a.roccato@studioroccato.com | +393486067770 |  |  |
| SIC-ID-000000000072 | Andrea | Subito |  | utente-mftgpv5qi@messaggi.subito.it | +393920888934 |  |  |
| SIC-ID-000000000073 | Andrea Di Rosa |  | Andrea Di Rosa | andreaascoli604@gmail.com | +393206464511 |  |  |
| SIC-ID-000000000074 | Aneltec S.R.L. Societa' A Socio Unico |  | Aneltec S.R.L. Societa' A Socio Unico | info@aneltec.it | +39024223343 |  |  |
| SIC-ID-000000000075 | Annaac |  |  | anna86ac@hotmail.it | +393396615361 |  |  |
| SIC-ID-000000000076 | Annacorbetta | Corbetta |  | annacorbetta@virgilio.it | +393355249394 |  |  |
| SIC-ID-000000000077 | Trattoria Sara |  | Antica Trattoria di Pozzato Sara | trattoriavenerinodasara@gmail.com | +390426990035 | Papozze | RO |
| SIC-ID-000000000078 | Antonello | Avona |  | antonello.avona@gmail.com | +393357063210 |  |  |
| SIC-ID-000000000079 | Antonello | Lyoness |  | antonellocappellato@icloud.com | +393357063210 |  |  |
| SIC-ID-000000000080 | Antonello | Lyoness |  | antonellocappellato@gmail.com | +393357063210 |  |  |
| SIC-ID-000000000081 | Antonello | Lyoness |  | a.cappellattoforceup@gmail.com | +393357063210 |  |  |
| SIC-ID-000000000082 | Antonia |  |  | antonia@gamoservizi.it | +393403886458 |  |  |
| SIC-ID-000000000083 | Antonio | Lazzaro |  | alazzaro2606@gmail.com | +393384297972 |  |  |
| SIC-ID-000000000084 | Antonio | Lazzaro |  | lazzaro@quboimpianti.com | +393356819568 |  |  |
| SIC-ID-000000000085 | Antonio | Lyo |  | info@nonsolocharter.com | +393450400400 |  |  |
| SIC-ID-000000000086 | Antonio | Sicurezza Farina |  | farinaimpianti@libero.it | +393356630538 |  |  |
| SIC-ID-000000000087 | Antonio | Tizzani |  | antonio.tizzani@libero.it | +393280553220 |  |  |
| SIC-ID-000000000088 | Appartamenti Complesso Residenziale Pini di Roma |  | Appartamenti Complesso Residenziale Pini di Roma | info@romasaxarubra.it | +390633630219 |  |  |
| SIC-ID-000000000089 | Arcadia Calcestruzzi S.P.A. |  | Arcadia Calcestruzzi S.P.A. | info@arcadiacalcestruzzi.it | +390573536262 |  |  |
| SIC-ID-000000000090 | Arch | Tamburin |  | archtamburin@libero.it | +3904261900617 |  |  |
| SIC-ID-000000000091 | Arch.edil S.r.l. |  | Arch.edil S.r.l. | info@morosinicostruzioni.it | +39035294111 |  |  |
| SIC-ID-000000000092 | Archfedericoantonio | Federico |  | archfedericoantonio@libero.it | +393484020288 |  |  |
| SIC-ID-000000000093 | AREA - Azienda Regionale per l'Edilizia Abitativa |  | AREA - Azienda Regionale per l'Edilizia Abitativa | trasparenza@area.sardegna.it | +393907020071 |  |  |
| SIC-ID-000000000094 | Area Gestione Edilizia della Sapienza, IV Piano Edificio di Ortopedia |  | Area Gestione Edilizia della Sapienza, IV Piano Edificio di Ortopedia | electi.world@gmail.com | +390649694151 |  |  |
| SIC-ID-000000000095 | Area Sosta Camper Al Plan |  | Area Sosta Camper Al Plan | info@areadisostavaldirabbi.it | +393396556740 |  |  |
| SIC-ID-000000000096 | Arianna | Granfo Capelli |  | granfoari@libero.it | +393403456638 |  |  |
| SIC-ID-000000000097 | Aries - Ferramenta Colori Cartongesso |  | Aries - Ferramenta Colori Cartongesso | barando@libero.it | +390923560222 |  |  |
| SIC-ID-000000000098 | Arpe, Associazione Romana della Proprietá Edilizia |  | Arpe, Associazione Romana della Proprietá Edilizia | segreteria@arpe.roma.it | +393906485611 |  |  |
| SIC-ID-000000000099 | ARREDAMENTI | CAMPAGNER |  | info@arredamenticampagner.it | +390421705069 |  |  |
| SIC-ID-000000000100 | Arrichiello Ciro s.r.l. |  | Arrichiello Ciro s.r.l. | info@arrichiello.it | +390817590171 |  |  |
| SIC-ID-000000000101 | Arte | Nautica Mattana |  | artenautica@libero.it | +393494692065 |  |  |
| SIC-ID-000000000102 | Matteo Sarto |  | Arte del Colore Srls | teodor.1981@gmail.com | +393408741799 | Adria | RO |
| SIC-ID-000000000103 | Artedil | e Avesani |  | info@artedileavesani.com | +39045917922 |  |  |
| SIC-ID-000000000104 | Artigiana Edile Snc |  | ARTIGIANA EDILE SNC di Fabris Giocondo & C. | info@artigianaedile.it | +390415541822 | Chioggia | VE |
| SIC-ID-000000000105 | Artigiana Srl - impresa edile |  | Artigiana Srl - impresa edile | info@artigianaimpresaedile.it | +390495227270 |  |  |
| SIC-ID-000000000106 | Artigiano |  |  | restauri@tiscalinet.it | +3933891633500 |  |  |
| SIC-ID-000000000107 | Asoli 3.0 edilizia e ceramiche |  | Asoli 3.0 edilizia e ceramiche | motoridiricerca@netservice.biz | +393907169043 |  |  |
| SIC-ID-000000000108 | Assistenza caldaie Roma e provincia |  |  | marco.delia+assistenzacaldaieroma24.com@gmail.com | +390697620597 |  |  |
| SIC-ID-000000000109 | Backup_sicurissimo Engine v1_2026-02-24_03-38 estr |  | ASSOCIAZIONE MAPPE FELICI | anna@studentefelice.it | +393381641532 | Brione | BS |
| SIC-ID-000000000110 | Lucadoati Rescue guardian |  | Associazione Rescue Guardian | lucadoati@hotmail.it | +393289647841 | Rosolina | RO |
| SIC-ID-000000000111 | Astel S.r.l. |  | Astel S.r.l. | info@astelsrl.com | +390432487070 |  |  |
| SIC-ID-000000000112 | Atelier | tends iDaminelli |  | info@idaminelli.it | +390350171206 |  |  |
| SIC-ID-000000000113 | ATIS | Ceramiche |  | info@atisceramiche.it | +3908119910034 |  |  |
| SIC-ID-000000000114 | ATIZETA S.R.L.S |  | ATIZETA S.R.L.S | info@bollettino.sardegna.it | +390782804042 |  |  |
| SIC-ID-000000000115 | Attivita' Edilizie Bergamasche S.r.l. |  | Attivita' Edilizie Bergamasche S.r.l. | info@attivitaediliziebergamasche.it | +39035595574 |  |  |
| SIC-ID-000000000116 | Augusto | Avanzo |  | augusto.avanzo@gmail.com | +393473087942 |  |  |
| SIC-ID-000000000117 | Aurora Color Srl sede di Signa - Zetacolor Vernici e Colori dal 1963 |  | Aurora Color Srl sede di Signa - Zetacolor Vernici e Colori dal 1963 | info@auroracolor.it | +390558797081 |  |  |
| SIC-ID-000000000118 | Aurora Costruzioni di aia francesco s.r.l. |  | Aurora Costruzioni di aia francesco s.r.l. | auroracostruzioni.sas.fe@gmail.com | +390532350550 |  |  |
| SIC-ID-000000000119 | Autocarrozzeria | Limpido |  | autocarrozzerialimpidosm@gmail.com | +39093124689 |  |  |
| SIC-ID-000000000120 | AutoClinic - salute e bellezza dell'auto |  | AutoClinic - salute e bellezza dell'auto | salvatore.pili@carglass.it | +390931490721 |  |  |
| SIC-ID-000000000121 | Civierosnc Civiero gabriele & figli snc |  | AUTOTRASPORTI CIVIERO GABRIELE & FIGLI SNC | civierosnc@gmail.com | +3934732236140 | Loreo | RO |
| SIC-ID-000000000122 | AVC |  |  | mod231@avcsrl.it | +390659606392 |  |  |
| SIC-ID-000000000123 | Azienda Agricola Giorgio |  | Azienda Agricola Giorgio | enricopizzolato3@gmail.com | +390533790063 |  |  |
| SIC-ID-000000000124 | Azzurra Costruzioni s.r.l.u. |  | Azzurra Costruzioni s.r.l.u. | webmaster@ca2solution.it | +39800629520 |  |  |
| SIC-ID-000000000125 | B.Edil |  |  | marcohasrama@hotmail.it | +393285537295 |  |  |
| SIC-ID-000000000126 | B.m. Srl - costruzioni edili e ristrutturazioni |  | B.m. Srl - costruzioni edili e ristrutturazioni | info@bmedile.com | +390516814119 |  |  |
| SIC-ID-000000000127 | B.M.T. Di Battistessa Ivan & C. snc |  | B.M.T. Di Battistessa Ivan & C. snc | ivan.battistessa@tiscali.it | +39034342364 |  |  |
| SIC-ID-000000000128 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessandro.crepaldi.55@gmail.com | +393402974429 |  |  |
| SIC-ID-000000000129 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | august0.buccomin0@gmail.com | +393382543361 |  |  |
| SIC-ID-000000000130 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andrea.pisarra@alice.it | +393402156963 |  |  |
| SIC-ID-000000000131 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andrea.galdiolo@gmail.com | +393331331385 |  |  |
| SIC-ID-000000000132 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andrea.ali8011@gmail.com | +393484421068 |  |  |
| SIC-ID-000000000133 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andrea-antonioli@libero.it | +393406030419 |  |  |
| SIC-ID-000000000134 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | amm@eshirt.it | +393343412897 |  |  |
| SIC-ID-000000000135 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessia.ebano@artigianatopadovano.it | +3933570632100 |  |  |
| SIC-ID-000000000136 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alicezanoni-66@live.it | +393899697457 |  |  |
| SIC-ID-000000000137 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alice.gazzignato@gmail.com | +393319325595 |  |  |
| SIC-ID-000000000138 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | attivalavoro@attivamenteonlus.it | +393358129051 |  |  |
| SIC-ID-000000000139 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessia.garbo@infun.es | +393484020288 |  |  |
| SIC-ID-000000000140 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andrea.cerreti@alice.it | +393402304639 |  |  |
| SIC-ID-000000000141 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessandroscacco@gmail.com | +393806997577 |  |  |
| SIC-ID-000000000142 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessandropanet@gmail.com | +393404727334 |  |  |
| SIC-ID-000000000143 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antonioprovasi@alice.it | +393479248503 |  |  |
| SIC-ID-000000000144 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andreabrognara@libero.it | +393357488230 |  |  |
| SIC-ID-000000000145 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antonella.iglesias@gmail.com | +393356089443 |  |  |
| SIC-ID-000000000146 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antonellaemery60@gmail.com | +393470609509 |  |  |
| SIC-ID-000000000147 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antonellamusic2009@libero.it | +393292507095 |  |  |
| SIC-ID-000000000148 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antonino.vena@gmail.com | +393478501233 |  |  |
| SIC-ID-000000000149 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antonio85nania@gmail.com | +3933810187160 |  |  |
| SIC-ID-000000000150 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antonio@artio.it | +393773181652 |  |  |
| SIC-ID-000000000151 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antoniocastellano64@alice.it | +393479294377 |  |  |
| SIC-ID-000000000152 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | archfedericoantonio@gmail.com | +393356978565 |  |  |
| SIC-ID-000000000153 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | asceotaanna61@gmail.com | +393459804152 |  |  |
| SIC-ID-000000000154 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | argentifabrizio@libero.it | +3933963142180 |  |  |
| SIC-ID-000000000155 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andrea.reali84@gmail.com | +393346132534 |  |  |
| SIC-ID-000000000156 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | armando.duo@tin.it | +393483141745 |  |  |
| SIC-ID-000000000157 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antonella.loi@me.com | +393478587793 |  |  |
| SIC-ID-000000000158 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | agnese@agnesetricot.it | +393200198260 |  |  |
| SIC-ID-000000000159 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andrea@birikina.it | +393392178302 |  |  |
| SIC-ID-000000000160 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | achidan@libero.it | +393296632281 |  |  |
| SIC-ID-000000000161 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | argentinaangelo86@gmail.com | +393801012122 |  |  |
| SIC-ID-000000000162 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andrea.scheggi@virgilio.it | +393495763881 |  |  |
| SIC-ID-000000000163 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andrea.vernizzi@crveneto.it | +3933964154270 |  |  |
| SIC-ID-000000000164 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | annugupta9847@gmail.com | +393219981940 |  |  |
| SIC-ID-000000000165 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessandro.basso@padovafiere.it | +3933833669720 |  |  |
| SIC-ID-000000000166 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessandra@rigato.net | +393290311802 |  |  |
| SIC-ID-000000000167 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessandra.menegon@vpsolar.com | +393334268866 |  |  |
| SIC-ID-000000000168 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alcamo.handicraft@gmail.com | +393477633710 |  |  |
| SIC-ID-000000000169 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | agostino.chiaretto@wind.it | +3932839191970 |  |  |
| SIC-ID-000000000170 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessia.manzo@hideea.com | +393279754151 |  |  |
| SIC-ID-000000000171 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | agnesleshrac@gmail.com | +393346900948 |  |  |
| SIC-ID-000000000172 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | assistenza@voxmail.it | +393385853854 |  |  |
| SIC-ID-000000000173 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | arretrati@genertel.it | +393335745706 |  |  |
| SIC-ID-000000000174 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andrpasini@libero.it | +393483844057 |  |  |
| SIC-ID-000000000175 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | angela.faccioli@alice.it | +3934932900850 |  |  |
| SIC-ID-000000000176 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | angela.ravera@gmail.com | +393471469860 |  |  |
| SIC-ID-000000000177 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | angelo.ballarin@alice.it | +393358413925 |  |  |
| SIC-ID-000000000178 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | angelo@prosvirom.com | +3934956092990 |  |  |
| SIC-ID-000000000179 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | annalisa.seren@gmail.com | +3934789422900 |  |  |
| SIC-ID-000000000180 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | annalisavinci1@gmail.com | +393489246207 |  |  |
| SIC-ID-000000000181 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | annapaola.angius@virgilio.it | +393486269626 |  |  |
| SIC-ID-000000000182 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessandro.pigaiani@alice.it | +393483897702 |  |  |
| SIC-ID-000000000183 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antonella.pomarico@consfor.com | +393319325595 |  |  |
| SIC-ID-000000000184 | Bagni | Moro |  | bagnidalmoro@gmail.com | +39042668211 |  |  |
| SIC-ID-000000000185 | Bagni | Shock |  | info@bagnishock.com | +393396943602 |  |  |
| SIC-ID-000000000186 | Bagni | dal Moro Antonio |  | info@bagnidalmoro.it | +39042668046 |  |  |
| SIC-ID-000000000187 | Balbo | Stefano |  | balbo.stefano@alice.it | +393381626922 |  |  |
| SIC-ID-000000000188 | Baldodaniele8 |  |  | baldodaniele8@yahoo.it | +393924571299 |  |  |
| SIC-ID-000000000189 | Banin_matteo |  |  | banin_matteo@libero.it | +393409353713 |  |  |
| SIC-ID-000000000190 | Fabio Ferrari Bar AI RIVÁ |  | BAR AI SCALINI S.R.L.S. | mauriziaferro1992@icloud.com | +393456910521 | Ariano nel Polesine | RO |
| SIC-ID-000000000191 | Barhrosolinamare Roccato |  | BAR H di Fabio Porzionato | barhrosolinamare@gmail.com | +393281214929 | Rosolina | RO |
| SIC-ID-000000000192 | Barbara | Lyo |  | cirelli.barbara@yahoo.it | +393409041533 |  |  |
| SIC-ID-000000000193 | Barbara | Veronese |  | barbara.veronese@enaip.veneto.it | +393440482173 |  |  |
| SIC-ID-000000000194 | Bartolomeo Giacalone s.r.l. |  | Bartolomeo Giacalone s.r.l. | info@bartolomeogiacalonesrl.it | +390923836919 |  |  |
| SIC-ID-000000000195 | Basanadamiano |  |  | basanadamiano@libero.it | +393351433447 |  |  |
| SIC-ID-000000000196 | Basile | Vetri |  | info@basilevetri.com | +39095870224 |  |  |
| SIC-ID-000000000197 | Basile S.r.l. |  | Basile S.r.l. | supporto@ceramichebasile.it | +39092321259 |  |  |
| SIC-ID-000000000198 | Bassetto Costruzioni |  | Bassetto Costruzioni | info@bassettocostruzioni.it | +390414765281 |  |  |
| SIC-ID-000000000199 | Batt89 | Ciordo |  | batt89@libero.it | +393406784902 |  |  |
| SIC-ID-000000000200 | bauexpert AG / SpA - Mattarello |  | bauexpert AG / SpA - Mattarello | info@bauexpert.it | +390461923050 |  |  |
| SIC-ID-000000000201 | Beatrice | Arillotta |  | architetto.arillotta@gmail.com | +393351433447 |  |  |
| SIC-ID-000000000202 | Beatriceviviani |  |  | beatriceviviani@tosettiviviani.com | +393425725476 |  |  |
| SIC-ID-000000000203 | Beblattonerie | Bar grillara |  | beblattonerie@gmail.com | +393405167268 |  |  |
| SIC-ID-000000000204 | Bedetti | Massimo |  | bedetti.massimo@libero.it | +393472120843 |  |  |
| SIC-ID-000000000205 | Bellini | Geom massimo |  | bellini.geom.massimo@gmail.com | +393482467582 |  |  |
| SIC-ID-000000000206 | Benedetti | Arredamenti |  | showroom@benedettiarredamenti.eu | +39065746610 |  |  |
| SIC-ID-000000000207 | Benito | Zio |  | info@astolfipelletterie.com | +393280485879 |  |  |
| SIC-ID-000000000208 | Bergamo Ceramiche Srl |  | Bergamo Ceramiche Srl | italianfloordesign@legalmail.it | +390354243837 |  |  |
| SIC-ID-000000000209 | Bernardi Asolo |  | Bernardi Asolo | info@bernardidasolo.it | +390434759185 |  |  |
| SIC-ID-000000000210 | Bernhard | Kramer |  | vonkraemer@aol.com | +393398660360 |  |  |
| SIC-ID-000000000211 | Bertoni | Silvia |  | bertoni.silvia@libero.it | +393486541700 |  |  |
| SIC-ID-000000000212 | Beta Costruzioni S.r.l. |  | Beta Costruzioni S.r.l. | betacostruzionivenezia@gmail.com | +39041961476 |  |  |
| SIC-ID-000000000213 | Bettelle Andrea |  | Bettelle Andrea | andrea.bettelle@email.it | +393357542030 | Chioggia | VE |
| SIC-ID-000000000214 | Bettydianini |  |  | bettydianini@gmail.com | +393281282824 |  |  |
| SIC-ID-000000000215 | Bi.Ci.Emme Costruzioni |  | Bi.Ci.Emme Costruzioni | info@biciemmecostruzioni.it | +390697166068 |  |  |
| SIC-ID-000000000216 | Bianconi Costruzioni Srl |  | Bianconi Costruzioni Srl | info@bianconicostruzioni.it | +390635508083 |  |  |
| SIC-ID-000000000217 | BigMat | Casarreda |  | info@casarreda.it | +39058377130 |  |  |
| SIC-ID-000000000218 | BigMat | De Filippi Vincenzo |  | defilippi@bigmat.it | +39092324982 |  |  |
| SIC-ID-000000000219 | BigMat | Edilcommercio |  | info@bigmat.it | +39035711057 |  |  |
| SIC-ID-000000000220 | BigMat | F.lli Crusco |  | expo@cruscoceramiche.com | +390985801231 |  |  |
| SIC-ID-000000000221 | BigMat | MEDIEDIL |  | mediedil@bigmat.it | +390923951769 |  |  |
| SIC-ID-000000000222 | BigMat | Soluzioni Edili |  | info@soluzioniedili.eu | +390457200022 |  |  |
| SIC-ID-000000000223 | BigMat D'Ambrosio Edilizia |  | BigMat D'Ambrosio Edilizia | dambrosio@bigmat.it | +390803743592 |  |  |
| SIC-ID-000000000224 | BigMat Dov.Edil - Magazzino Hone |  | BigMat Dov.Edil - Magazzino Hone | dovedil@bigmat.it | +390125807443 |  |  |
| SIC-ID-000000000225 | BigMat Edilaosta S.r.l. |  | BigMat Edilaosta S.r.l. | privacy@bigmat.it | +3901651853500 |  |  |
| SIC-ID-000000000226 | BigMat Edilizia Colombini Srl |  | BigMat Edilizia Colombini Srl | ediliziacolombini@bigmat.it | +390341941123 |  |  |
| SIC-ID-000000000227 | BigMat Edilizia Commerciale - Via Nepal 40 |  | BigMat Edilizia Commerciale - Via Nepal 40 | info@ediliziacommerciale.it | +390564453346 |  |  |
| SIC-ID-000000000228 | Bigmat ediliziare |  | Bigmat ediliziare | ediliziare@bigmat.it | +390734628783 |  |  |
| SIC-ID-000000000229 | BigMat Moser Guido Edilizia S.r.l. |  | BigMat Moser Guido Edilizia S.r.l. | moserguido@bigmat.it | +390461531046 |  |  |
| SIC-ID-000000000230 | Bigmat nuova comes s.r.l. |  | Bigmat nuova comes s.r.l. | info@nuovacomes.it | +39071660464 |  |  |
| SIC-ID-000000000231 | Binettigiulio61 | Binetti |  | binettigiulio61@gmail.com | +393483845017 |  |  |
| SIC-ID-000000000232 | Bio Tek srl impresa edile |  | Bio Tek srl impresa edile | info@bio-tek.it | +390519981389 |  |  |
| SIC-ID-000000000233 | Biocasa Costruzioni |  | Biocasa Costruzioni | biocasa1982@gmail.com | +393453282729 |  |  |
| SIC-ID-000000000234 | Bioedilizia srl |  | Bioedilizia srl | bioedilizia.srl@libero.it | +3932011851620 |  |  |
| SIC-ID-000000000235 | BioEdilizia Toscana |  | BioEdilizia Toscana | info@bioediliziatoscana.it | +393487017094 |  |  |
| SIC-ID-000000000236 | Bioedilizia2.0 Srls |  | Bioedilizia2.0 Srls | info@bienebe.com | +393339976971 |  |  |
| SIC-ID-000000000237 | Giulycost Moro |  | BLUE CAFE' S.n.c. di Bacci Cristina & Costanzo Giulia | giulycost95@gmail.com | +3934008382940 | Chioggia |  |
| SIC-ID-000000000238 | Bnf Costruzioni s.r.l. |  | Bnf Costruzioni s.r.l. | bnfcostruzioni@gmail.com | +393804352753 |  |  |
| SIC-ID-000000000239 | Boatomichele |  |  | boatomichele@libero.it | +393290227862 |  |  |
| SIC-ID-000000000240 | Bof | Crivellaro |  | bof@dolomyte.eu | +393283096600 |  |  |
| SIC-ID-000000000241 | Bon | Lavi |  | bon.lavi@libero.it | +393494571273 |  |  |
| SIC-ID-000000000242 | Bondesan | Ilaria KM5 |  | ilyb1982@libero.it | +393479354294 |  |  |
| SIC-ID-000000000243 | Bondi Gianni |  | Bondi Gianni | bondi.gianni@libero.it | +393200314139 |  |  |
| SIC-ID-000000000244 | Fiorentini Barbara Market |  | BONDI' MARKET di Fiorentini Barbara | barbyfiore@gmail.com | +393281351055 | Loreo | RO |
| SIC-ID-000000000245 | Bonifiche Belliche Ediltecnica Srl |  | Bonifiche Belliche Ediltecnica Srl | amministrazione@bonifichebelliche.eu | +3905851710021 |  |  |
| SIC-ID-000000000246 | Borelli Impresa edile srl |  | Borelli Impresa edile srl | impresaborelli@gmail.com | +39037585350 |  |  |
| SIC-ID-000000000247 | Botti | Sperandio Materiali Edili |  | info@bottisperandio.it | +39030881754 |  |  |
| SIC-ID-000000000248 | Bovolenta | Fabio |  | bovolenta.fabio@libero.it | +393476488692 |  |  |
| SIC-ID-000000000249 | Bovolenta | Marino |  | az.bovolenta.marino@gmail.com | +393396192850 |  |  |
| SIC-ID-000000000250 | Bovolenta | Nicoló |  | bovolentanicolo@live.it | +393493290085 |  |  |
| SIC-ID-000000000251 | Bovolenta Gabriele |  | BOVOLENTA GABRIELE | bovolenta.gabriele@libero.it | +3933888339890 | Porto Viro | RO |
| SIC-ID-000000000252 | Decodecorazionibovolenta Cognome email telefono id cliente backup_sicu |  | BOVOLENTA STEFANO | decodecorazionibovolenta@gmail.com | +393401804545 | Porto Viro | RO |
| SIC-ID-000000000253 | Bracelli S.r.l. - Materiali Edili - Centro Trasformazione Acciao per C.A. |  | Bracelli S.r.l. - Materiali Edili - Centro Trasformazione Acciao per C.A. | socialmedia@plesk.com | +390342635329 |  |  |
| SIC-ID-000000000254 | Brenzan | Angelo |  | info@villadadige.it | +393932199126 |  |  |
| SIC-ID-000000000255 | Bricocenter Casoria |  | Bricocenter Casoria | paolo.negri@bricocenter.it | +390817577048 |  |  |
| SIC-ID-000000000256 | Bricofer | senigallia |  | faidate.senigallia@bricofer.it | +39071791831 |  |  |
| SIC-ID-000000000257 | Brugioni Srl |  | Brugioni Srl | paolo@brugionitir.it | +390585857590 |  |  |
| SIC-ID-000000000258 | Brunelli | Giovanni |  | brunelli.giovanni@libero.it | +393342259604 |  |  |
| SIC-ID-000000000259 | BSM Edilizia Snc |  | BSM Edilizia Snc | bsmlavoriedili@gmail.com | +393382653345 |  |  |
| SIC-ID-000000000260 | Building | Materials Renato Lupetti |  | renato.lupetti@gmail.com | +39050571234 |  |  |
| SIC-ID-000000000261 | BuildUp srl |  | BuildUp srl | info@buildupappalti.it | +3937739020540 |  |  |
| SIC-ID-000000000262 | Buononato | Giuseppe01 |  | buononato.giuseppe01@libero.it | +393406880726 |  |  |
| SIC-ID-000000000263 | Bruno BUTTINI |  | BUTTINI BRUNO | brunobuttini@gmail.com | +393358085588 | Mesola | FE |
| SIC-ID-000000000264 | C.A.S.A EDILIZIA S.R.L.S. |  | C.A.S.A EDILIZIA S.R.L.S. | info@casaedilizia.it | +393401619192 |  |  |
| SIC-ID-000000000265 | C.d.f. Costruzioni, ristrutturazioni, restauri |  | C.d.f. Costruzioni, ristrutturazioni, restauri | info@cdf-srl.it | +390415322863 |  |  |
| SIC-ID-000000000266 | C.E.P. Costruzioni Edili Presta S.N.C. |  | C.E.P. Costruzioni Edili Presta S.N.C. | info@costruzioniedilipresta.it | +39066143930 |  |  |
| SIC-ID-000000000267 | C.L. Conglomerati Lucchesi Srl |  | C.L. Conglomerati Lucchesi Srl | clconglomerati@legalmail.it | +390583299894 |  |  |
| SIC-ID-000000000268 | C.M Colorificio Mediterraneo Di Genna Fabio |  | C.M Colorificio Mediterraneo Di Genna Fabio | info@colorificiomediterraneo.it | +390923723074 |  |  |
| SIC-ID-000000000269 | C.M.EDIL s.r.l. |  | C.M.EDIL s.r.l. | info@cmedil.it | +390583464180 |  |  |
| SIC-ID-000000000270 | Ca.gi.ma. S.r.l. |  | Ca.gi.ma. S.r.l. | cagimasrl@gmail.com | +39070781658 |  |  |
| SIC-ID-000000000271 | Ca.Ri. Costruzioni S.R.L. |  | Ca.Ri. Costruzioni S.R.L. | info@caricostruzioni.it | +390630366830 |  |  |
| SIC-ID-000000000272 | Caborgh | Piwi |  | caborgh@libero.it | +393408569858 |  |  |
| SIC-ID-000000000273 | Cabras Mariano S.R.L. |  | Cabras Mariano S.R.L. | marianocabras@legalmail.it | +39070765672 |  |  |
| SIC-ID-000000000274 | Cacioppo Group - costruzioni e turismo |  | Cacioppo Group - costruzioni e turismo | srledilcasa@gmail.com | +39092428468 |  |  |
| SIC-ID-000000000275 | Lorena Malgari |  | Caffe’ Malgari’ Piadina Romagnola di Mazzucato Michele | lorena.scremin@alice.it | +393491966857 | Anguillara Veneta | PD |
| SIC-ID-000000000276 | Paralovo Mattia |  | Caffè Commercio di Paralovo Mattia. | paralovo.mattia@yahoo.it | +393498402960 | Loreo | RO |
| SIC-ID-000000000277 | Cagnoni | brico giardinaggio ferramenta |  | info@cagnoni.it | +39071289981 |  |  |
| SIC-ID-000000000278 | Calandrino Group srl |  | Calandrino Group srl | info@calandrinogroup.it | +393488938934 |  |  |
| SIC-ID-000000000279 | Calcestruzzi | Nicolò errante |  | calcestruzzierrante@alice.it | +3909251955885 |  |  |
| SIC-ID-000000000280 | Calcestruzzi | senigallia |  | calcestruzzi.senigallia@virgilio.it | +393469848577 |  |  |
| SIC-ID-000000000281 | Calcio | Veneto UISP |  | calcio.veneto@uisp.it | +39324061460 |  |  |
| SIC-ID-000000000282 | Callegari_andrea |  |  | callegari_andrea@libero.it | +393290974057 |  |  |
| SIC-ID-000000000283 | Callegaro Costruzioni |  | Callegaro Costruzioni | info@callegarocostruzioni.com | +39049646345 |  |  |
| SIC-ID-000000000284 | Irene Camacci |  | CAMA CAFFE ' DI CAMACCI IRENE | irecama86@libero.it | +393475525348 | LOREO | RO |
| SIC-ID-000000000285 | Caminetti | CIK |  | info@cik.it | +39035541257 |  |  |
| SIC-ID-000000000286 | Camisotti | geom |  | alessandrocamisotti@yahoo.it | +39042671729 |  |  |
| SIC-ID-000000000287 | Campagnaro Costruzioni impresa edile |  | Campagnaro Costruzioni impresa edile | info@campagnarocostruzioni.it | +393335858863 |  |  |
| SIC-ID-000000000288 | Campo Costruzioni - impresa edile |  | Campo Costruzioni - impresa edile | info@campocostruzioni.it | +393926673138 |  |  |
| SIC-ID-000000000289 | Campochiaro | Piero |  | info@ceramichecampochiaro.com | +390585843412 |  |  |
| SIC-ID-000000000290 | Canova1963 |  |  | canova1963@gmail.com | +393483819759 |  |  |
| SIC-ID-000000000291 | Capellini | Piu |  | a.piu@3technology.it | +393282211829 |  |  |
| SIC-ID-000000000292 | Capitanjackpeenses | Vivaldi |  | capitanjackpeenses@gmail.com | +393710128880 |  |  |
| SIC-ID-000000000293 | Cappellato | Chiara |  | cappellato.chiara@libero.it | +393483141745 |  |  |
| SIC-ID-000000000294 | Carbone | Francesco |  | francesco90carbone@gmail.com | +393421688889 |  |  |
| SIC-ID-000000000295 | Carboni Casa - Showroom Modena |  | Carboni Casa - Showroom Modena | servizioclientimostra@carboni.com | +390522633311 |  |  |
| SIC-ID-000000000296 | Carlo | Bergamaschi |  | carlo.bergamaschi@vodafone.it | +3932945031420 |  |  |
| SIC-ID-000000000297 | Carlo | Bottos |  | carlo.bottos@icloud.com | +3933931462120 |  |  |
| SIC-ID-000000000298 | Carlo | Cester |  | carlo@ideadesignfactory.com | +393451716322 |  |  |
| SIC-ID-000000000299 | Carlodalterio79 |  |  | carlodalterio79@gmail.com | +393392854006 |  |  |
| SIC-ID-000000000300 | Carloregazzo Ferro | Regazzo |  | carloregazzo@tin.it | +393475620590 | Mogliano Veneto | TV |
| SIC-ID-000000000301 | Carolina_ffigueiredo |  |  | carolina_ffigueiredo@hotmail.com | +393471737338 |  |  |
| SIC-ID-000000000302 | Carta | Delta |  | cartadelta@libero.it | +393402974429 |  |  |
| SIC-ID-000000000303 | Casa Lab 38 by Stefanedil Trionfale |  | Casa Lab 38 by Stefanedil Trionfale | info@casalab38.it | +390645554874 |  |  |
| SIC-ID-000000000304 | Casartelli | Michea |  | casartelli.michea@gmail.com | +393713538202 |  |  |
| SIC-ID-000000000305 | Enrico Chiozzotto |  | CASE DI ASIAGO SRLS | enrico.chiozzotto@gmail.com | +393314940595 | Asiago | VI |
| SIC-ID-000000000306 | Casellato | Francesco Rosolina |  | info@camaimport.com | +390426664937 |  |  |
| SIC-ID-000000000307 | Casellato | Francesco Rosolina |  | camasrl@gmail.com | +390426664937 |  |  |
| SIC-ID-000000000308 | Casmirri | Freeart |  | casmirri.freeart@gmail.com | +393482237037 |  |  |
| SIC-ID-000000000309 | Catalano Michele srl |  | Catalano Michele srl | info@catalanocostruzioni.it | +390923718148 |  |  |
| SIC-ID-000000000310 | Caterina | Gallori |  | caterina.gallori@libero.it | +393405179080 |  |  |
| SIC-ID-000000000311 | Catialazzari Catia lazzari rif tacchetto |  | Catialazzari Catia lazzari rif tacchetto | catialazzari@yahoo.it | +390415321662 |  |  |
| SIC-ID-000000000312 | Cecilia |  |  | cecilia@fancygrafica.com | +3932866684660 |  |  |
| SIC-ID-000000000313 | Cecilia | Sicurezza |  | chichij@hotmail.it | +393478942290 |  |  |
| SIC-ID-000000000314 | Cei |  |  | info@edilnoleggiosicilia.it | +390923907987 |  |  |
| SIC-ID-000000000315 | Celda | Ro |  | celda.ro@libero.it | +393288213641 |  |  |
| SIC-ID-000000000316 | Celi Energia e costruzioni spa |  | Celi Energia e costruzioni spa | info@celienergia.it | +39092460943 |  |  |
| SIC-ID-000000000317 | Cella Costruzioni S.R.L. |  | Cella Costruzioni S.R.L. | impresa@cellacostruzioni.com | +390432869443 |  |  |
| SIC-ID-000000000318 | Centro | Pietra Living |  | roberto.gabrielli@centropietraliving.it | +3904611560083 |  |  |
| SIC-ID-000000000319 | Centro Chiavi Gianicolense - Serrature, duplicazione chiavi, radiocomandi auto, pronto intervento, cilindri a Roma Monteverde |  | Centro Chiavi Gianicolense - Serrature, duplicazione chiavi, radiocomandi auto, pronto intervento, cilindri a Roma Monteverde | info@centrochiavigianicolense.it | +393487541872 |  |  |
| SIC-ID-000000000320 | Centrodibellezzabellessere Services plants |  | CENTRO DI BELLEZZA BELLESSERE di Felici Samanta | centrodibellezzabellessere@gmail.com | +393343069007 | Porto Tolle | RO |
| SIC-ID-000000000321 | Centro Edile Fontana |  | Centro Edile Fontana | servizioclienti@centroedilefontana.it | +3909621924555 |  |  |
| SIC-ID-000000000322 | Centro Edile Lucca srl |  | Centro Edile Lucca srl | info@centroedilelucca.com | +390583962526 |  |  |
| SIC-ID-000000000323 | Centro Edilizia |  | Centro Edilizia | info@centroedilizia.com | +39035774145 |  |  |
| SIC-ID-000000000324 | Centroedile Milano - ARESE |  | Centroedile Milano - ARESE | progettazione@centroedilemilano.com | +390293588193 |  |  |
| SIC-ID-000000000325 | Ceragioli Costruzioni |  | Ceragioli Costruzioni | info@ceragiolicostruzioni.it | +390584951549 |  |  |
| SIC-ID-000000000326 | Ceramiche In S.r.l. |  | Ceramiche In S.r.l. | ceramicheinsrl@gmail.com | +39092325207 |  |  |
| SIC-ID-000000000327 | Ceramiche Lucarda di Lucarda Giuseppe & C. S.n.c. |  | Ceramiche Lucarda di Lucarda Giuseppe & C. S.n.c. | info@ceramichelucarda.it | +39041429248 |  |  |
| SIC-ID-000000000328 | Cesarato Costruzioni srl |  | Cesarato Costruzioni srl | cesaratocostruzioni@libero.it | +390415385503 |  |  |
| SIC-ID-000000000329 | Cf Costruzioni impresa edile restauri ristrutturazioni |  | Cf Costruzioni impresa edile restauri ristrutturazioni | info@cf-costruzioni.it | +393408798729 |  |  |
| SIC-ID-000000000330 | Chiara | Azzolini84 |  | chiara.azzolini84@gmail.com | +393478693951 |  |  |
| SIC-ID-000000000331 | Chiara | Bonora |  | bonorachiara@yahoo.it | +393773181652 |  |  |
| SIC-ID-000000000332 | Chiara | Pannella |  | chiara.pannella@hotmail.it | +393440482173 |  |  |
| SIC-ID-000000000333 | Chiara | Spinello |  | chiara.spinello@alice.it | +393476852831 |  |  |
| SIC-ID-000000000334 | Chiarabravin |  |  | chiarabravin@libero.it | +393271686148 |  |  |
| SIC-ID-000000000335 | Chiaramarangoni | Arianna |  | chiaramarangoni@alice.it | +393481649026 |  |  |
| SIC-ID-000000000336 | Chievo Costruzioni S.R.L. |  | Chievo Costruzioni S.R.L. | info@chievocostruzioni.it | +390458348112 |  |  |
| SIC-ID-000000000337 | Re Bertaggia |  | CHIOGGIA DIPINTURA S.r.l.s. | re.bertaggia@gmail.com | +393483641299 | Chioggia | VE |
| SIC-ID-000000000338 | Consulenzacester Service di nordio lorella |  | CHIOGGIA SERVICE DI NORDIO LORELLA | consulenzacester@gmail.com | +3934753576170 | Chioggia | VE |
| SIC-ID-000000000339 | Cime S.R.L. Commercio Industria Materiali Edili |  | Cime S.R.L. Commercio Industria Materiali Edili | info@cimesrl.com | +390815841917 |  |  |
| SIC-ID-000000000340 | Cimmino Calce s.r.l |  | Cimmino Calce s.r.l | info@cimminocalce.com | +390817593256 |  |  |
| SIC-ID-000000000341 | Cisaf | montemarciano |  | francesco.rocila@refitcompany.com | +390719158229 |  |  |
| SIC-ID-000000000342 | Clapspietro | Petrella jetboard |  | clapspietro@gmail.com | +393791863833 |  |  |
| SIC-ID-000000000343 | claudia | acevedo |  | clapatri.v@gmail.com | +393295484710 |  |  |
| SIC-ID-000000000344 | Claudio | Carella |  | claudio.carella@allianzbankfa.it | +393287039619 |  |  |
| SIC-ID-000000000345 | Claudio | Ferrarese |  | claudio.ferrarese@libero.it | +393806912748 |  |  |
| SIC-ID-000000000346 | Claudio | Gibin |  | claudio.gibin@gmail.com | +393346469722 |  |  |
| SIC-ID-000000000347 | Claudio | Parrino |  | claudio.parrino@gmail.com | +393393146212 |  |  |
| SIC-ID-000000000348 | Claudio | Unimetal |  | claudio.unimetal@alice.it | +393286668466 |  |  |
| SIC-ID-000000000349 | Claudio | Comaron |  | claudio.comaron@unipd.it | +393287445792 |  |  |
| SIC-ID-000000000350 | Claudio_design |  |  | claudio_design@libero.it | +393287513461 |  |  |
| SIC-ID-000000000351 | Claudionoce65 |  |  | claudionoce65@gmail.com | +393393146212 |  |  |
| SIC-ID-000000000352 | Claudiopigato |  |  | claudiopigato@tin.it | +393332973125 |  |  |
| SIC-ID-000000000353 | Claudiosalsilli | Hyper toscana miki |  | claudiosalsilli@gmail.com | +3933192038490 |  |  |
| SIC-ID-000000000354 | Clementechimbote |  |  | clementechimbote@hotmail.com | +393313646474 |  |  |
| SIC-ID-000000000355 | Cnt Costruzioni di contartese leo - impresa edile e ristrutturazioni |  | Cnt Costruzioni di contartese leo - impresa edile e ristrutturazioni | cntcostruzioni@gmail.com | +393402151509 |  |  |
| SIC-ID-000000000356 | Co.Ve.Ri. |  |  | privacy@coveri.it | +39041429466 |  |  |
| SIC-ID-000000000357 | COEFIN S.R.L. |  | COEFIN S.R.L. | info@coefinsrl.com | +390698182401 |  |  |
| SIC-ID-000000000358 | Color Lab srl Pistoia |  | Color Lab srl Pistoia | info@colorlab-srl.it | +390573518113 |  |  |
| SIC-ID-000000000359 | Colorificio | Casaplast |  | info@casaplast.it | +39035690404 |  |  |
| SIC-ID-000000000360 | Com-Edil Di Delbono Luciano & C Snc |  | Com-Edil Di Delbono Luciano & C Snc | com-edil@libero.it | +390306850645 |  |  |
| SIC-ID-000000000361 | Comedil |  |  | info@comedilshop.com | +39073453884 |  |  |
| SIC-ID-000000000362 | Commercial Isonzo (S. R. L.) |  | Commercial Isonzo (S. R. L.) | isonzo@gmail.com | +39067014403 |  |  |
| SIC-ID-000000000363 | Commerciale Ferramenta Primo |  | Commerciale Ferramenta Primo | comfer@commercialeferramenta.it | +390426320330 |  |  |
| SIC-ID-000000000364 | Commerciale Giannetti (S.R.L.) |  | Commerciale Giannetti (S.R.L.) | info@giannetti.it | +390586661294 |  |  |
| SIC-ID-000000000365 | Compagnia | del Sale |  | info@compagniadelsale.it | +390645582460 |  |  |
| SIC-ID-000000000366 | Complementi | Climatici |  | servizioclienti@complementiclimatici.it | +393464703610 |  |  |
| SIC-ID-000000000367 | Concetta | Voltlina |  | kosseimc@hotmail.com | +393494291138 |  |  |
| SIC-ID-000000000368 | Concettalauda | Energy |  | concettalauda@gmail.com | +393288366255 |  |  |
| SIC-ID-000000000369 | Condor S.r.l. |  | Condor S.r.l. | condorsrl@gmail.com | +390918981593 |  |  |
| SIC-ID-000000000370 | Consorzi Agrari d' Italia SpA - Ag. di MONTEPESCALI |  | Consorzi Agrari d' Italia SpA - Ag. di MONTEPESCALI | arcidosso@consorziagrariditalia.it | +390564329015 |  |  |
| SIC-ID-000000000371 | Conte Francesco costruzioni e restauri s.r.l. |  | Conte Francesco costruzioni e restauri s.r.l. | info@conteedile.it | +390415203612 |  |  |
| SIC-ID-000000000372 | Cooperativa Edilizia |  | Cooperativa Edilizia | iniziative@cooperativaedilizia.it | +390454647620 |  |  |
| SIC-ID-000000000373 | Corazza Costruzioni edili |  | Corazza Costruzioni edili | web@corazzacostruzioni.com | +390532436811 |  |  |
| SIC-ID-000000000374 | Cornolti |  |  | falcor67@libero.it | +39035571561 |  |  |
| SIC-ID-000000000375 | Coronetta Costruzioni |  | Coronetta Costruzioni | info@coronettacostruzioni.com | +390692935682 |  |  |
| SIC-ID-000000000376 | Corrado Casa - impresa edile |  | Corrado Casa - impresa edile | info@corradocasa.com | +393487489457 |  |  |
| SIC-ID-000000000377 | Corsi. | Assistenza |  | support@corsi.it | +393348374549 |  |  |
| SIC-ID-000000000378 | Corvucci |  |  | corvucci@corofar.it | +393886584684 |  |  |
| SIC-ID-000000000379 | Cosit |  |  | cosit@manufatticosit.com | +390909384422 |  |  |
| SIC-ID-000000000380 | Costruzioni & servizi rinnovabili s.r.l. |  | Costruzioni & servizi rinnovabili s.r.l. | adv@rinnovabili.it | +390532681207 |  |  |
| SIC-ID-000000000381 | Costruzioni C.e.c.i. s.r.l. |  | Costruzioni C.e.c.i. s.r.l. | costruzioniceci@gmail.com | +390516841221 |  |  |
| SIC-ID-000000000382 | Costruzioni E ristrutturazioni edili di gobbo bruno (s.r.l.) |  | Costruzioni E ristrutturazioni edili di gobbo bruno (s.r.l.) | info@gobbobruno.com | +39042297635 |  |  |
| SIC-ID-000000000383 | Costruzioni e Ristrutturazioni Edili Roma - Albert Costruzioni |  | Costruzioni e Ristrutturazioni Edili Roma - Albert Costruzioni | privacy@localweb.it | +393441663555 |  |  |
| SIC-ID-000000000384 | Costruzioni Edili pavanello s.r.l. |  | Costruzioni Edili pavanello s.r.l. | contattaci@costruzionipavanello.it | +39041640740 |  |  |
| SIC-ID-000000000385 | Costruzioni Edili romagnoli srl / impresa edile per nuove costruzioni / castel san pietro terme / bologna |  | Costruzioni Edili romagnoli srl / impresa edile per nuove costruzioni / castel san pietro terme / bologna | info@costruzioniromagnoli.it | +39051946557 |  |  |
| SIC-ID-000000000386 | Costruzioni Edili sartorato |  | Costruzioni Edili sartorato | info@sartoratocostruzioni.com | +390422788005 |  |  |
| SIC-ID-000000000387 | Costruzioni Edili zucchini spa |  | Costruzioni Edili zucchini spa | info@costruzioniedilizucchini.it | +39051226964 |  |  |
| SIC-ID-000000000388 | Costruzioni Generali Due Srl - Azienda Edile |  | Costruzioni Generali Due Srl - Azienda Edile | segreteria@cg2.it | +39059512495 |  |  |
| SIC-ID-000000000389 | Costruzioni Geom. Vettorini Pietro Srl |  | Costruzioni Geom. Vettorini Pietro Srl | info@emsol.it | +390585790577 |  |  |
| SIC-ID-000000000390 | Costruzioni Giacobazzi Spa |  | Costruzioni Giacobazzi Spa | info@costruzionigiacobazzi.com | +39051860850 |  |  |
| SIC-ID-000000000391 | Costruzioni Gruppo Santarelli |  | Costruzioni Gruppo Santarelli | segreteriagruppoimmobiliare@gmail.com | +390687137390 |  |  |
| SIC-ID-000000000392 | Costruzioni stano & c. s.r.l. |  | Costruzioni stano & c. s.r.l. | info@costruzionistano.it | +390803038681 |  |  |
| SIC-ID-000000000393 | Costruzioni Venezia pattarello sas |  | Costruzioni Venezia pattarello sas | info@costruzionipattarello.it | +39330999191 |  |  |
| SIC-ID-000000000394 | Cremona | Michele |  | cremona.michele@gmail.com | +393468330913 |  |  |
| SIC-ID-000000000395 | Crezza S.r.l. - Sede amministrativa e produttiva di Gordona |  | Crezza S.r.l. - Sede amministrativa e produttiva di Gordona | info@crezza.com | +39034343144 |  |  |
| SIC-ID-000000000396 | Crhis | Dj |  | modenese.c@gmail.com | +393478279558 |  |  |
| SIC-ID-000000000397 | Crifill Srl |  | Crifill Srl | export@crifill.it | +390498839539 |  |  |
| SIC-ID-000000000398 | Crifill Srl |  | Crifill Srl | info@crifill.it | +390498839539 |  |  |
| SIC-ID-000000000399 | Cristian | Veronese |  | cristianveronese@hotmail.it | +393479956624 |  |  |
| SIC-ID-000000000400 | cristian | barbieri |  | cristian.barbieri73@gmail.com | +393471009888 |  |  |
| SIC-ID-000000000401 | Cristiano | Ceresatto |  | cristiano.ceresatto@gmail.com | +393408310242 |  |  |
| SIC-ID-000000000402 | Cristiano | Nani |  | cristiano.nani@tin.it | +393337339940 |  |  |
| SIC-ID-000000000403 | Crlrpl255 |  |  | crlrpl255@gmail.com | +393468513386 |  |  |
| SIC-ID-000000000404 | Croccoalessandra |  |  | croccoalessandra@virgilio.it | +393491237168 |  |  |
| SIC-ID-000000000405 | Cryptonly2003 | Frizziero |  | cryptonly2003@gmail.com | +393474100541 |  |  |
| SIC-ID-000000000406 | Cs | Davide |  | cs.davide@hotmail.it | +393408072600 |  |  |
| SIC-ID-000000000407 | Cscmaurizio |  |  | cscmaurizio@virgilio.it | +393346935515 |  |  |
| SIC-ID-000000000408 | CSE - Cristiano Sbordoni Edilizia s.r.l. - Gruppo Sbordoni |  | CSE - Cristiano Sbordoni Edilizia s.r.l. - Gruppo Sbordoni | cse@grupposbordoni.com | +390639724646 |  |  |
| SIC-ID-000000000409 | Cugini Spa - Premiscelati per l'edilizia |  | Cugini Spa - Premiscelati per l'edilizia | cugini@cugini.it | +39035520780 |  |  |
| SIC-ID-000000000410 | D.s.c Edilizia s.a.s di rizzo domenico |  | D.s.c Edilizia s.a.s di rizzo domenico | jennysong72@gmail.com | +393295875288 |  |  |
| SIC-ID-000000000411 | Da Tecnofer S.R.L. |  | Da Tecnofer S.R.L. | info@tecnofersrl.eu | +39034342630 |  |  |
| SIC-ID-000000000412 | Dal Castello romano impresa edile |  | Dal Castello romano impresa edile | info@romanodalcastello.it | +390445532232 |  |  |
| SIC-ID-000000000413 | Damiano | Lyo |  | lurkindmi@libero.it | +393297029104 |  |  |
| SIC-ID-000000000414 | Damiga Srl |  | Damiga Srl | info@damigasrl.com | +39092425488 |  |  |
| SIC-ID-000000000415 | DANI RIFINITURE S.R.L. |  | DANI RIFINITURE S.R.L. | info@danirifiniture.it | +3904641982214 |  |  |
| SIC-ID-000000000416 | Danicre |  |  | danicre@katamail.com | +393477678548 |  |  |
| SIC-ID-000000000417 | Daniel | Conselvan |  | conselvandaniel@gmail.com | +393468550001 |  |  |
| SIC-ID-000000000418 | Daniela | Granzieri |  | daniela.granzieri@alice.it | +393280340311 |  |  |
| SIC-ID-000000000419 | Daniela | Menegaldo22 |  | daniela.menegaldo22@gmail.com | +393476772283 |  |  |
| SIC-ID-000000000420 | Daniele |  |  | daniele@centrodicalcolo.it | +393492918322 |  |  |
| SIC-ID-000000000421 | Daniele |  |  | daniele@dibenedetti.com | +393333826138 |  |  |
| SIC-ID-000000000422 | Daniele | Brazzoni |  | daniele.brazzoni@telecomitalia.it | +393351441078 |  |  |
| SIC-ID-000000000423 | Daniele | Colombo243 |  | daniele.colombo243@gmail.com | +393478535400 |  |  |
| SIC-ID-000000000424 | Daniele | Grottolo ENEL |  | daniele.grottolo@enel.com | +393201982679 |  |  |
| SIC-ID-000000000425 | Daniele | LYO |  | daniele.sileo@gmail.com | +393472626953 |  |  |
| SIC-ID-000000000426 | Daniele | Mocco |  | daniele.mocco@libero.it | +393294239455 |  |  |
| SIC-ID-000000000427 | Daniele | Peruzzo |  | daniele.peruzzo@agenziarolando.it | +393351433447 |  |  |
| SIC-ID-000000000428 | Daniele | Tiziano |  | daniele.tiziano@alice.it | +393337254755 |  |  |
| SIC-ID-000000000429 | Danielepes | Pes |  | danielepes@hotmail.com | +393288451486 |  |  |
| SIC-ID-000000000430 | Dario | Codemo |  | dario.codemo@gmail.com | +393899697457 |  |  |
| SIC-ID-000000000431 | Dario | Gabetti |  | dariogabetti@libero.it | +393939198786 |  |  |
| SIC-ID-000000000432 | Dav | Con |  | dav.con@libero.it | +393498126938 |  |  |
| SIC-ID-000000000433 | Davaj |  |  | davaj@tiscali.it | +393343412897 |  |  |
| SIC-ID-000000000434 | Davide |  |  | davide@fusaroimpianti.it | +393289295055 |  |  |
| SIC-ID-000000000435 | Davide | Bardella |  | semmy15569@gmail.com | +393476410961 |  |  |
| SIC-ID-000000000436 | Davide | Lyo |  | davide.bedin1966@yahoo.it | +393480990462 |  |  |
| SIC-ID-000000000437 | Davide | Tiozzo1 |  | davide.tiozzo1@libero.it | +393468550001 |  |  |
| SIC-ID-000000000438 | Davide_zennaro | Sala onde delta |  | davide_zennaro@libero.it | +393400566206 |  |  |
| SIC-ID-000000000439 | Davidepomponio |  |  | davidepomponio@libero.it | +393939356578 |  |  |
| SIC-ID-000000000440 | Davider84 |  |  | davider84@hotmail.it | +393202866190 |  |  |
| SIC-ID-000000000441 | Davidricciardi |  |  | davidricciardi@tiscali.it | +393335745706 |  |  |
| SIC-ID-000000000442 | De | Agostini Renato |  | info@deagostinirenatosnc.com | +39034342434 |  |  |
| SIC-ID-000000000443 | De | Boni Elisabetta |  | debonielisa@libero.it | +393355312661 |  |  |
| SIC-ID-000000000444 | De Angelis Costruzioni Srl |  | De Angelis Costruzioni Srl | ufficiotecnico@deangeliscostruzioni.com | +390669922612 |  |  |
| SIC-ID-000000000445 | Luigi |  | De Boni Luigi | deboniluigi@libero.it | +3933396253510 | Chioggia | VE |
| SIC-ID-000000000446 | De Giuli costruzioni s.r.l. |  | De Giuli costruzioni s.r.l. | degiulicostruzioni@gmail.com | +393356049376 |  |  |
| SIC-ID-000000000447 | De Palo Group S.r.l. |  | De Palo Group S.r.l. | depalogroupsrl@libero.it | +390803735912 |  |  |
| SIC-ID-000000000448 | De Sanctis Costruzioni Spa |  | De Sanctis Costruzioni Spa | info@gruppodesanctis.com | +39064620131 |  |  |
| SIC-ID-000000000449 | De Santis Clima Srl / Assistenza autorizzata Baxi, Radiant, Fujitsu |  | De Santis Clima Srl / Assistenza autorizzata Baxi, Radiant, Fujitsu | desantisassistenza@gmail.com | +39063011024 |  |  |
| SIC-ID-000000000450 | De Vellis Servizi Globali Srl (sede legale) |  | De Vellis Servizi Globali Srl (sede legale) | qualita@devellis.it | +39077589881 |  |  |
| SIC-ID-000000000451 | Debora | Cafè Rum Cazzador |  | enrico.cazzador@fincantieri.it | +393475521586 |  |  |
| SIC-ID-000000000452 | Debora | Petrina |  | debora.petrina@gmail.com | +393351441078 |  |  |
| SIC-ID-000000000453 | Debora | Terzi |  | debora.terzi@gmail.com | +3933868960860 |  |  |
| SIC-ID-000000000454 | Decaromelissa04 | Ruzza |  | decaromelissa04@gmail.com | +393392775383 |  |  |
| SIC-ID-000000000455 | Decoraziobovolenta Giua onean porto rotondo |  | Decoraziobovolenta Giua onean porto rotondo | decoraziobovolenta@gmail.com | +393497152882 |  |  |
| SIC-ID-000000000456 | Del | Debbio |  | m.spadoni@deldebbio.it | +39058395851 |  |  |
| SIC-ID-000000000457 | Del Dosso Diego S.r.l. - Sede di Piateda |  | Del Dosso Diego S.r.l. - Sede di Piateda | stefano@deldosso.com | +390342370760 |  |  |
| SIC-ID-000000000458 | Delmare Vernici Srl |  | Delmare Vernici Srl | info@delmarepaint.it | +390410992531 |  |  |
| SIC-ID-000000000459 | Dero | Geomnicola |  | dero.geomnicola@virgilio.it | +393389254029 |  |  |
| SIC-ID-000000000460 | Design&Co. - Studio Interior Design |  | Design&Co. - Studio Interior Design | info@akreodesign.it | +393204445352 |  |  |
| SIC-ID-000000000461 | Devare |  |  | devare@libero.it | +393345648483 |  |  |
| SIC-ID-000000000462 | Di Leonardo group |  | Di Leonardo group | info@studioscioglilingue.it | +393914228787 |  |  |
| SIC-ID-000000000463 | Di.Co. Edilizia Srl |  | Di.Co. Edilizia Srl | dicoedilizia@libero.it | +39070850767 |  |  |
| SIC-ID-000000000464 | Didattica |  |  | didattica@hideea.com | +3934667867430 |  |  |
| SIC-ID-000000000465 | Diego | Alba |  | sceriffo80@virgilio.it | +393881681332 |  |  |
| SIC-ID-000000000466 | Diego | Canton |  | diego.canton@gsbconsulenze.it | +393404859354 |  |  |
| SIC-ID-000000000467 | Diego | Canton cts |  | diego.canton.cts@gmail.com | +393282015239 |  |  |
| SIC-ID-000000000468 | Diego | Valentini |  | diego.valentini@libero.it | +393357739942 |  |  |
| SIC-ID-000000000469 | Diego Arch. |  | Diego Arch. | studio.arks@gmail.com | +393426468167 |  |  |
| SIC-ID-000000000470 | Diego Termoidraulica |  | Diego Termoidraulica | idraulicafonsato@gmail.com | +393488591228 |  |  |
| SIC-ID-000000000471 | Diegogiarrizzo |  |  | diegogiarrizzo@libero.it | +393397182558 |  |  |
| SIC-ID-000000000472 | Diegpetto | Andrea |  | diegpetto@gmail.com | +393357410044 |  |  |
| SIC-ID-000000000473 | Diesis | Spettacoli |  | macondospettacoli@libero.it | +393472764081 |  |  |
| SIC-ID-000000000474 | Dilan | Ferro |  | dilanferro@gmail.com | +393463048419 |  |  |
| SIC-ID-000000000475 | Dimaurodaniela76 |  |  | dimaurodaniela76@gmail.com | +393289295055 |  |  |
| SIC-ID-000000000476 | Dimensione20 | Gelato cioccolate teas |  | dimensione20@gmail.com | +393482227831 |  |  |
| SIC-ID-000000000477 | Ditalia | Maurizio |  | ditalia.maurizio@gmail.com | +393288174041 |  |  |
| SIC-ID-000000000478 | Ditta Edile andrea adragna |  | Ditta Edile andrea adragna | parts.italy@wartsila.com | +393314835120 |  |  |
| SIC-ID-000000000479 | Djcarlodevilla |  |  | djcarlodevilla@gmail.com | +393487413322 |  |  |
| SIC-ID-000000000480 | Documenti | Renzo |  | documenti@genertel.it | +3934880686500 |  |  |
| SIC-ID-000000000481 | Domenico | Dona |  | domenico.dona@libero.it | +393207874767 |  |  |
| SIC-ID-000000000482 | Domenico | Franco24 |  | domenico.franco24@gmail.com | +3933862198100 |  |  |
| SIC-ID-000000000483 | Domenico | Lyo |  | mimmiposi@gmail.com | +393891140980 |  |  |
| SIC-ID-000000000484 | Domidea Ristrutturazioni Roma - Studio Prati |  | Domidea Ristrutturazioni Roma - Studio Prati | project@domidea.it | +39800800747 |  |  |
| SIC-ID-000000000485 | Domino Costruzioni s.r.l. |  | Domino Costruzioni s.r.l. | info@dominohaus.it | +390532685530 |  |  |
| SIC-ID-000000000486 | Domus | Tende |  | info@domustende.it | +39066689456 |  |  |
| SIC-ID-000000000487 | Donatoaliprandi |  |  | donatoaliprandi@libero.it | +393351439523 |  |  |
| SIC-ID-000000000488 | Doneda Edilizia |  | Doneda Edilizia | doe-j@donedaedilizia.com | +390354874051 |  |  |
| SIC-ID-000000000489 | Doro Fratelli impresa edile costruzioni |  | Doro Fratelli impresa edile costruzioni | info@fratellidoro.it | +393358059257 |  |  |
| SIC-ID-000000000490 | Dott | Lucio |  | businaro.lucio@libero.it | +393663944222 |  |  |
| SIC-ID-000000000491 | Dott. V. Pezzangora MC Mestre |  | Dott. V. Pezzangora MC Mestre | v.pezzangora@alice.it | +393498371754 |  |  |
| SIC-ID-000000000492 | Dott. Vianelli Veterinario |  | Dott. Vianelli Veterinario | massimo.vianelli@gmail.com | +39368978652 |  |  |
| SIC-ID-000000000493 | Dret | System |  | info@dretsystem.com | +393884332006 |  |  |
| SIC-ID-000000000494 | Dridialejos |  |  | dridialejos@yahoo.it | +393491591252 |  |  |
| SIC-ID-000000000495 | Ds Constructions srl |  | Ds Constructions srl | dsimpresa@libero.it | +393688038184 |  |  |
| SIC-ID-000000000496 | Duemme347 | Mescalchin |  | duemme347@gmail.com | +393385203882 |  |  |
| SIC-ID-000000000497 | E | Irsap rovigo |  | e.carboni@mail.sittam.it | +393483150181 |  |  |
| SIC-ID-000000000498 | E | Pizzo |  | e.nobili@kattedra.com | +393286550676 |  |  |
| SIC-ID-000000000499 | E Monica di giulio |  | E Monica di giulio | e.pavino@hiperformance.it | +393339027451 |  |  |
| SIC-ID-000000000500 | Ecoedilizia |  | Ecoedilizia | contabilita@eco-edilizia.it | +393484165344 |  |  |
| SIC-ID-000000000501 | ECOTEC-Ideal Montaggi srl |  | ECOTEC-Ideal Montaggi srl | info@ecotec.bio | +39058375008 |  |  |
| SIC-ID-000000000502 | EDIL | FR.AN 1967 |  | reclami@edilfran.it | +390966717206 |  |  |
| SIC-ID-000000000503 | EDIL | SIMONI |  | info@edilsimoni.com | +393471299627 |  |  |
| SIC-ID-000000000504 | Edil | 2N |  | info@edil2n.it | +39035810504 |  |  |
| SIC-ID-000000000505 | Edil | B.M. Baraiolo |  | info@edilbmbaraiolo.it | +390342652276 |  |  |
| SIC-ID-000000000506 | Edil | Bezzi |  | umberto.bezzi78@gmail.com | +390463751118 |  |  |
| SIC-ID-000000000507 | Edil | Cabras |  | dittacabras@yahoo.it | +39078275859 |  |  |
| SIC-ID-000000000508 | Edil | Corer |  | info@corersrl.it | +393396914896 |  |  |
| SIC-ID-000000000509 | Edil | Dervishi gentjan |  | dervishigentjan@yahoo.it | +393205503107 |  |  |
| SIC-ID-000000000510 | Edil | Petrozzi |  | info@edilpetrozzi.it | +39066583854 |  |  |
| SIC-ID-000000000511 | Edil | Roncelli |  | infos@kompass.com | +39035542719 |  |  |
| SIC-ID-000000000512 | Edil | Venezia |  | info@edilvenezia.it | +393485163720 |  |  |
| SIC-ID-000000000513 | Edil | Virruto |  | info@edilvirruto.it | +393484939449 |  |  |
| SIC-ID-000000000514 | Edil 2001 costruzioni srl |  | Edil 2001 costruzioni srl | info@edil2001costruzionisrl.it | +390532847923 |  |  |
| SIC-ID-000000000515 | Edil A.GV. srl |  | Edil A.GV. srl | info@edilagv.com | +39016545716 |  |  |
| SIC-ID-000000000516 | Edil Bardello Srl |  | Edil Bardello Srl | roberta.ziviani@edilbardello.it | +390332746798 |  |  |
| SIC-ID-000000000517 | Edil Bolzonella s.r.l. |  | Edil Bolzonella s.r.l. | info@edilbolzonella.it | +39337494578 |  |  |
| SIC-ID-000000000518 | Edil Claps srl / ditta di costruzioni e ristrutturazioni / bologna / sant’agata bolognese |  | Edil Claps srl / ditta di costruzioni e ristrutturazioni / bologna / sant’agata bolognese | info@edilclaps.com | +390510471911 |  |  |
| SIC-ID-000000000519 | Edil Contractor srl |  | Edil Contractor srl | info@edilcontractor.com | +390425758134 |  |  |
| SIC-ID-000000000520 | Edil G.l. S.r.l. |  | Edil G.l. S.r.l. | info@edilgl.it | +39035905105 |  |  |
| SIC-ID-000000000521 | Edil Gc - general costruzioni |  | Edil Gc - general costruzioni | amministrazione@edilgcostruzionisrl.it | +390515872004 |  |  |
| SIC-ID-000000000522 | Edil Gronde Di Piras Giulio Impermeabilizzazione in Poliuretano |  | Edil Gronde Di Piras Giulio Impermeabilizzazione in Poliuretano | luigi.piras@hotmail.it | +393403780142 |  |  |
| SIC-ID-000000000523 | Edil H.B.A. Impianti Soc. Coop. |  | Edil H.B.A. Impianti Soc. Coop. | amministrazione@edilhba.it | +390332443830 |  |  |
| SIC-ID-000000000524 | Edil Laurenzi S.r.l. |  | Edil Laurenzi S.r.l. | edillaurenzisrl@gmail.com | +393319510745 |  |  |
| SIC-ID-000000000525 | Edil Lavori s.r.l. |  | Edil Lavori s.r.l. | info@edillavori.it | +390421322402 |  |  |
| SIC-ID-000000000526 | Edil Leonardo srl - Edilizia Sostenibile Roma e Latina |  | Edil Leonardo srl - Edilizia Sostenibile Roma e Latina | info@edilleonardo.it | +393455389463 |  |  |
| SIC-ID-000000000527 | Edil Maggian s.r.l unipersonale |  | Edil Maggian s.r.l unipersonale | info@edilmaggiansrl.it | +390415226720 |  |  |
| SIC-ID-000000000528 | Edil Morzillo s.r.l.s. |  | Edil Morzillo s.r.l.s. | info@edilmoro.com | +393398753762 |  |  |
| SIC-ID-000000000529 | Edil Multiservice snc di cazacu ivan |  | Edil Multiservice snc di cazacu ivan | commerciale@techtower.it | +390532472160 |  |  |
| SIC-ID-000000000530 | Edil Pinca srl |  | Edil Pinca srl | info@edilpinca.it | +39053599333 |  |  |
| SIC-ID-000000000531 | Edil Porro srl - impresa edile |  | Edil Porro srl - impresa edile | info@edilporro.it | +393496073078 |  |  |
| SIC-ID-000000000532 | Edil Project -realizzazione ville e piscine |  | Edil Project -realizzazione ville e piscine | edilprojectsrl87@libero.it | +393926799531 |  |  |
| SIC-ID-000000000533 | Edil Project s.r.l. |  | Edil Project s.r.l. | info@edil-project-srl.it | +390923568654 |  |  |
| SIC-ID-000000000534 | Edil Rapid SRL |  | Edil Rapid SRL | info@edil-rapid.it | +393200296979 |  |  |
| SIC-ID-000000000535 | Edil S. A. M. Orazio |  | EDIL S.A.M. di Gasparetto Orazio | edil.sam.1974@gmail.com | +393405551207 | Taglio di Po | RO |
| SIC-ID-000000000536 | Edil Sangaletti S.R.L. |  | Edil Sangaletti S.R.L. | info@edilsangalettisrl.it | +39035672743 |  |  |
| SIC-ID-000000000537 | edil sas di mascanzoni luca & c. |  | edil sas di mascanzoni luca & c. | luca@edilsnc.it | +390458345524 |  |  |
| SIC-ID-000000000538 | Edil Service barone s.r.l. |  | Edil Service barone s.r.l. | edilservicebarone@facileimpresa.it | +393881748645 |  |  |
| SIC-ID-000000000539 | Edil Service di roberto comunale |  | Edil Service di roberto comunale | emacomunale@libero.it | +393246242909 |  |  |
| SIC-ID-000000000540 | Edil Spa Products |  | Edil Spa Products | info@edilprodottispa.eu | +390813306094 |  |  |
| SIC-ID-000000000541 | Edil Vi.mas. di vitulli domenico & c. |  | Edil Vi.mas. di vitulli domenico & c. | edilvimas.snc@libero.it | +390532845253 |  |  |
| SIC-ID-000000000542 | Edil-For |  |  | info@edilfor.it | +39035905010 |  |  |
| SIC-ID-000000000543 | Edil.dome |  |  | info@edildome.it | +393275774420 |  |  |
| SIC-ID-000000000544 | Edilalba Srl |  | Edilalba Srl | info@edilalba.it | +390532470428 |  |  |
| SIC-ID-000000000545 | Edilbernardibau |  |  | info@edilbernardibau.it | +390471383284 |  |  |
| SIC-ID-000000000546 | Edilbi Srl |  | Edilbi Srl | edilbifrau@gmail.com | +390785854322 |  |  |
| SIC-ID-000000000547 | Edilbonacina |  |  | nfo@edilbonacina.it | +390354940289 |  |  |
| SIC-ID-000000000548 | EdilButtu Costruzioni |  | EdilButtu Costruzioni | tizianobuttu@yahoo.it | +393299732477 |  |  |
| SIC-ID-000000000549 | Edilcassa | Del Lazio |  | info@edilcassadellazio.it | +39065880773 |  |  |
| SIC-ID-000000000550 | Edilcentralino Srl |  | Edilcentralino Srl | info@edilcentralino.it | +39035791282 |  |  |
| SIC-ID-000000000551 | Edilcentro Srl |  | Edilcentro Srl | info@edilcentroitalia.it | +390862717382 |  |  |
| SIC-ID-000000000552 | EdilCorradi S.n.c |  | EdilCorradi S.n.c | info@edilcorradi.com | +39059386058 |  |  |
| SIC-ID-000000000553 | Edilcostruzioni Nardo giocondo |  | Edilcostruzioni Nardo giocondo | info@mg-edilcostruzioni.it | +390415150538 |  |  |
| SIC-ID-000000000554 | Edildomus srl |  | Edildomus srl | info@edildomusdapra.it | +390463901004 |  |  |
| SIC-ID-000000000555 | Edildream Srl |  | Edildream Srl | info@edildream.net | +390514681105 |  |  |
| SIC-ID-000000000556 | Edile A.G.R. snc |  | Edile A.G.R. snc | info@edileagr.it | +390463974757 |  |  |
| SIC-ID-000000000557 | Edile Express Mazzotta / Materiali per l'Edilizia - Arredo Casa |  | Edile Express Mazzotta / Materiali per l'Edilizia - Arredo Casa | expressmazzotta@libero.it | +393343129293 |  |  |
| SIC-ID-000000000558 | Edile Pasquali srl |  | Edile Pasquali srl | info@edilepasquali.it | +390516056784 |  |  |
| SIC-ID-000000000559 | Edileco Società Cooperativa |  | Edileco Società Cooperativa | bclaudia@edileco.org | +390165767621 |  |  |
| SIC-ID-000000000560 | Edilerica Appalti e Costruzioni |  | Edilerica Appalti e Costruzioni | jobs@edilerica.it | +39063729849 |  |  |
| SIC-ID-000000000561 | EDILERRE SRL |  | EDILERRE SRL | info@edilerresrl.it | +390342861081 |  |  |
| SIC-ID-000000000562 | Edilevolution / edilizia acrobatica misiliscemi |  | Edilevolution / edilizia acrobatica misiliscemi | edilevolutionsufune@gmail.com | +393286339035 |  |  |
| SIC-ID-000000000563 | Edilflora di Grosso Lavalle Mario E Cairo Ornella“VIVAIO, EVENT PLANNER IMPIANTI VILLE PARCHI” |  | Edilflora di Grosso Lavalle Mario E Cairo Ornella“VIVAIO, EVENT PLANNER IMPIANTI VILLE PARCHI” | edilflorabelvedere@libero.it | +390985849854 |  |  |
| SIC-ID-000000000564 | Edilforniture Locatelli Sas |  | Edilforniture Locatelli Sas | info@edilforniture1974.com | +39035791488 |  |  |
| SIC-ID-000000000565 | Edilfuni S.r.l. - Edilizia su fune Roma |  | Edilfuni S.r.l. - Edilizia su fune Roma | wilmer2@qodeinteractive.com | +39800300878 |  |  |
| SIC-ID-000000000566 | Edilgianluca snc |  | Edilgianluca snc | info@edilgianluca.it | +393474123438 |  |  |
| SIC-ID-000000000567 | Edilin S.r.l. |  | Edilin S.r.l. | edilin@edilin.it | +390650513818 |  |  |
| SIC-ID-000000000568 | Edilizia 95 roma |  | Edilizia 95 roma | ristrutturazioniedilizia95@gmail.com | +393920121544 |  |  |
| SIC-ID-000000000569 | Edilizia Abruzzo |  | Edilizia Abruzzo | edilizia.abruzzo20@gmail.com | +393791047817 |  |  |
| SIC-ID-000000000570 | Edilizia Alternativa |  | Edilizia Alternativa | info@edilizialternativa.it | +39800035181 |  |  |
| SIC-ID-000000000571 | Edilizia Anacleto snc |  | Edilizia Anacleto snc | info@edilizianacleto.it | +390376595068 |  |  |
| SIC-ID-000000000572 | Edilizia Bertuccio |  | Edilizia Bertuccio | mostra.bertuccio@libero.it | +390963331928 |  |  |
| SIC-ID-000000000573 | Edilizia Biancani Benito Srl |  | Edilizia Biancani Benito Srl | info@biancanigroup.com | +390586680016 |  |  |
| SIC-ID-000000000574 | Edilizia C.D. Srl |  | Edilizia C.D. Srl | info@edilizia-cd.it | +390666182061 |  |  |
| SIC-ID-000000000575 | Edilizia Colombini S.R.L. |  | Edilizia Colombini S.R.L. | lucia@ediliziacolombini.it | +390342687757 |  |  |
| SIC-ID-000000000576 | Edilizia Commerciale Srl |  | Edilizia Commerciale Srl | ediliziacommerciale@bigmat.it | +39056435189 |  |  |
| SIC-ID-000000000577 | Edilizia D.F. S.r.l.s. |  | Edilizia D.F. S.r.l.s. | ediliziadf@outlook.it | +393906260783 |  |  |
| SIC-ID-000000000578 | Edilizia Daniela Sas |  | Edilizia Daniela Sas | info@ediliziadaniela.com | +390761799388 |  |  |
| SIC-ID-000000000579 | EDILIZIA DEL DUEMILA SAS |  | EDILIZIA DEL DUEMILA SAS | info@ediliziadelduemila.it | +390804037634 |  |  |
| SIC-ID-000000000580 | Edilizia Design Roma - La tua casa con stile |  | Edilizia Design Roma - La tua casa con stile | informazioni@ediliziadesign.it | +39065591938 |  |  |
| SIC-ID-000000000581 | Edilizia E falegnameria ferrari sandro srl |  | Edilizia E falegnameria ferrari sandro srl | imp.ferrari@libero.it | +393358394707 |  |  |
| SIC-ID-000000000582 | Edilizia E soluzioni di cammarata mirko |  | Edilizia E soluzioni di cammarata mirko | ediliziasoluzioni@gmail.com | +393276312068 |  |  |
| SIC-ID-000000000583 | Edilizia E Trasporti F.lli Riccio Srl |  | Edilizia E Trasporti F.lli Riccio Srl | preventivi@ediliziaetrasporti.it | +390817386176 |  |  |
| SIC-ID-000000000584 | Edilizia Lori impresa edile |  | Edilizia Lori impresa edile | edilizialori@gmail.com | +393920580536 |  |  |
| SIC-ID-000000000585 | Edilizia MB |  | Edilizia MB | ediliziambsrl@gmail.com | +39066281858 |  |  |
| SIC-ID-000000000586 | Edilizia Orobica Roncalli |  | Edilizia Orobica Roncalli | vittoria@edilizia-orobica.com | +390356321011 |  |  |
| SIC-ID-000000000587 | Edilizia Raschellà Vincenzo Vendita Materiali Edili |  | Edilizia Raschellà Vincenzo Vendita Materiali Edili | raschellaedilizia@gmail.com | +39066240997 |  |  |
| SIC-ID-000000000588 | Edilizia Ristrutturazioni Impresa Edile |  | Edilizia Ristrutturazioni Impresa Edile | studwld@aol.com | +393296017322 |  |  |
| SIC-ID-000000000589 | Edilizia Roma Nord S.R.L. |  | Edilizia Roma Nord S.R.L. | info@ediliziaromanord.it | +390689413612 |  |  |
| SIC-ID-000000000590 | Edilizia Sabotino |  | Edilizia Sabotino | commerciale@ediliziasabotino.it | +390773648320 |  |  |
| SIC-ID-000000000591 | Edilizia Vezzanese srls |  | Edilizia Vezzanese srls | ediliziavezzanese.rg@libero.it | +393516293657 |  |  |
| SIC-ID-000000000592 | EDILIZIA-SC SRLS |  | EDILIZIA-SC SRLS | info@edilizia-sc.it | +393803533342 |  |  |
| SIC-ID-000000000593 | Edilmaf | S.a.s. |  | info@edilmaf.it | +39024562103 |  |  |
| SIC-ID-000000000594 | Edilmaino | Enterprise |  | milvo.ufficiotecnico@edilmaino.com | +39034334677 |  |  |
| SIC-ID-000000000595 | Edilmar (s.r.l.) |  | Edilmar (s.r.l.) | edilmar@edilmar.it | +39041460704 |  |  |
| SIC-ID-000000000596 | Edilmartignacco S.r.l. |  | Edilmartignacco S.r.l. | info@edilmartignacco.it | +390432400509 |  |  |
| SIC-ID-000000000597 | Edilmaso Costruzioni s.r.l. |  | Edilmaso Costruzioni s.r.l. | info@edilmaso.it | +39041410724 |  |  |
| SIC-ID-000000000598 | Edilmiri Group srl |  | Edilmiri Group srl | segreteria@edilmirigroupsrl.it | +390418226336 |  |  |
| SIC-ID-000000000599 | Edilmodac 1 srls |  | Edilmodac 1 srls | info@edilmodac.it | +390418660118 |  |  |
| SIC-ID-000000000600 | EdilMurino Srl |  | EdilMurino Srl | info@anticacasadelcarrubo.it | +393284764764 |  |  |
| SIC-ID-000000000601 | Edilnord | Bergamasca |  | edilnordbergamasca@yahoo.it | +393347264721 |  |  |
| SIC-ID-000000000602 | Edilnova |  |  | info@impresacostruzioniedilnova.it | +3904993669310 |  |  |
| SIC-ID-000000000603 | Edilnova Dell'Irno S.R.L. |  | Edilnova Dell'Irno S.R.L. | edilnovadellirno@edilnovadellirno.com | +390815844522 |  |  |
| SIC-ID-000000000604 | Edilpiu' Srl |  | Edilpiu' Srl | noleggio@capsrl.com | +390583833059 |  |  |
| SIC-ID-000000000605 | Edilravanelli Srl |  | Edilravanelli Srl | ravanelli@legalmail.it | +3904611636932 |  |  |
| SIC-ID-000000000606 | Edilriviera S.r.l. |  | Edilriviera S.r.l. | info@edilrivieraservice.it | +390412376632 |  |  |
| SIC-ID-000000000607 | Edilsand srl |  | Edilsand srl | mailtoinfo@edilsand.it | +393467988937 |  |  |
| SIC-ID-000000000608 | Edilstrada - Risanamenti e Impermeabilizzazioni |  | Edilstrada - Risanamenti e Impermeabilizzazioni | info@risanamentiedilstrada.it | +39336523643 |  |  |
| SIC-ID-000000000609 | Edilterracciano S.r.l.s. |  | Edilterracciano S.r.l.s. | edilterracciano@gmail.com | +393356361484 |  |  |
| SIC-ID-000000000610 | Ediltimmy Srl |  | Ediltimmy Srl | mail@ediltimmy.it | +393756820861 |  |  |
| SIC-ID-000000000611 | Ediltutto SRL |  | Ediltutto SRL | info@ediltuttosrl.it | +390172743253 |  |  |
| SIC-ID-000000000612 | Ediltutto Srl |  | Ediltutto Srl | commerciale.ediltutto@gmail.com | +39092428353 |  |  |
| SIC-ID-000000000613 | Edra costruzioni soc. coop. |  | Edra costruzioni soc. coop. | info@edracostruzioni.it | +390716608195 |  |  |
| SIC-ID-000000000614 | Education | Smile cremona |  | education.smile.cremona@gmail.com | +393472792942 |  |  |
| SIC-ID-000000000615 | EffediliziaDue - Ristrutturazione Roma |  | EffediliziaDue - Ristrutturazione Roma | effe.ediliziadue@gmail.com | +393711087700 |  |  |
| SIC-ID-000000000616 | Ele8327 |  |  | ele8327@libero.it | +393492321855 |  |  |
| SIC-ID-000000000617 | Elena | Antonioli |  | elena.antonioli@libero.it | +3932884514860 |  |  |
| SIC-ID-000000000618 | Elena | Casella onean |  | elena.chionni@libero.it | +393483605164 |  |  |
| SIC-ID-000000000619 | Elena | Cavallaro |  | elena.cavallaro@tntitaly.it | +393357459877 |  |  |
| SIC-ID-000000000620 | Elenabeltrami2 |  |  | elenabeltrami2@gmail.com | +393339755718 |  |  |
| SIC-ID-000000000621 | Eleongas |  |  | 1eleongas@libero.it | +393494904892 |  |  |
| SIC-ID-000000000622 | Eleonora | Capitanio |  | eleonora.capitanio@hotmail.it | +3934856676280 |  |  |
| SIC-ID-000000000623 | Eleonora | Jonny l |  | eleonora@dreika.it | +3933338261380 |  |  |
| SIC-ID-000000000624 | Elettromeccanica | Burini Bergamo |  | elmecburini@hotmail.it | +39035543893 |  |  |
| SIC-ID-000000000625 | Elisa | Fanton |  | elisa.fanton@proseccoardenghi.it | +393491690272 |  |  |
| SIC-ID-000000000626 | Elisa | Mora |  | elisa.mora@bunge.com | +393202866190 |  |  |
| SIC-ID-000000000627 | Elisa_gatto |  |  | elisa_gatto@libero.it | +393290246828 |  |  |
| SIC-ID-000000000628 | Elisabetta | Adamo |  | elisabetta.adamo@eldatasas.com | +393408072600 |  |  |
| SIC-ID-000000000629 | Elisabettadoretti |  |  | elisabettadoretti@gmail.com | +393357818190 |  |  |
| SIC-ID-000000000630 | Elisapavan |  |  | elisapavan@live.it | +393495552947 |  |  |
| SIC-ID-000000000631 | Elleuno S.r.l. Costruzioni Metalliche |  | Elleuno S.r.l. Costruzioni Metalliche | elleuno@live.it | +390630819607 |  |  |
| SIC-ID-000000000632 | Emanuel | Lyo |  | fly_emanuel@hotmail.it | +393494374729 |  |  |
| SIC-ID-000000000633 | Emanuela | Barbiero |  | emanuela.barbiero@artigianatopadovano.it | +393206484738 |  |  |
| SIC-ID-000000000634 | Emanueladigoscio |  |  | emanueladigoscio@yahoo.it | +393792416261 |  |  |
| SIC-ID-000000000635 | Emanuele | Secci66 |  | emanuele.secci66@gmail.com | +3934808665020 |  |  |
| SIC-ID-000000000636 | Emiliosiriakki | Sirianni |  | emiliosiriakki@gmail.com | +393356650323 |  |  |
| SIC-ID-000000000637 | Emily | Sun |  | emily@yeehaw3d.com | +393450912954 |  |  |
| SIC-ID-000000000638 | Emmepi di Pediconi Massimiliano |  | Emmepi di Pediconi Massimiliano | info@m-pi.it | +390697275238 |  |  |
| SIC-ID-000000000639 | Energy Service la rosa nicolò maurizio |  | Energy Service la rosa nicolò maurizio | info@larosaenergy.com | +393495306292 |  |  |
| SIC-ID-000000000640 | Eni Plenitude Flagship Store |  | Eni Plenitude Flagship Store | sandonatomilanese.energystore@acdenergy.it | +390695060477 |  |  |
| SIC-ID-000000000641 | Enrico | Bonvento |  | enrico.bonvento@iqtconsulting.it | +393355312661 |  |  |
| SIC-ID-000000000642 | Enrico | Bortolami |  | ebortolami@tize.it | +393477910017 |  |  |
| SIC-ID-000000000643 | Enrico | Bortolami |  | info@michelangelost.com | +393477910017 |  |  |
| SIC-ID-000000000644 | Enrico | Gallo |  | enrico.digallo@gmail.com | +393356675694 |  |  |
| SIC-ID-000000000645 | Enrico | Grillo |  | enrico.grillo@venezze.it | +393492255048 |  |  |
| SIC-ID-000000000646 | Enrico | Mantovan |  | enrico.mantovan@gmail.com | +393454589954 |  |  |
| SIC-ID-000000000647 | Enrico | Piovan |  | enrico.piovan@gv3.it | +393282015239 |  |  |
| SIC-ID-000000000648 | Enricobimbatti |  |  | enricobimbatti@libero.it | +393356675694 |  |  |
| SIC-ID-000000000649 | Enricogas64 |  |  | enricogas64@gmail.com | +393204850699 |  |  |
| SIC-ID-000000000650 | Erbastoadele |  |  | erbastoadele@gmail.com | +393288366255 |  |  |
| SIC-ID-000000000651 | Erica | Kristalli d zukkero |  | erica@boschiero.it | +3934048593540 |  |  |
| SIC-ID-000000000652 | Ermannofagherazzi |  |  | ermannofagherazzi@ermannofagherazzi.com | +393358140876 |  |  |
| SIC-ID-000000000653 | Ermesdinatale |  |  | ermesdinatale@gmail.com | +393933786823 |  |  |
| SIC-ID-000000000654 | Ersiliabonucci Box srls |  | Ersiliabonucci Box srls | ersiliabonucci@gmail.com | +3933832336720 |  |  |
| SIC-ID-000000000655 | Etenim srl |  | Etenim srl | info@etenim.it | +390645473460 |  |  |
| SIC-ID-000000000656 | ETERNOO |  |  | segreteria@eternoo.it | +390457513227 |  |  |
| SIC-ID-000000000657 | Eugeniolucchesini61 |  |  | eugeniolucchesini61@gmail.com | +3934942334650 |  |  |
| SIC-ID-000000000658 | Euro Edilizia 2000 |  | Euro Edilizia 2000 | euroedilizia2000srl@gmail.com | +39065576824 |  |  |
| SIC-ID-000000000659 | Euroedil Srl |  | Euroedil Srl | info@euroedilsoluzioni.it | +390429670215 |  |  |
| SIC-ID-000000000660 | Evolsystem S.r.l |  | Evolsystem S.r.l | info@elettrosystemonline.com | +393899171164 |  |  |
| SIC-ID-000000000661 | F.c. Costruzioni s.r.l. |  | F.c. Costruzioni s.r.l. | info@figeocostruzioni.it | +390923533860 |  |  |
| SIC-ID-000000000662 | F.C. Fasolino Costruzioni Srl |  | F.C. Fasolino Costruzioni Srl | info@fasolinocostruzioni.it | +39063050582 |  |  |
| SIC-ID-000000000663 | F.lli | Fontana |  | fratellifontana@yahoo.it | +390424490136 |  |  |
| SIC-ID-000000000664 | F.lli Baschiera s.r.l. costruzioni edili |  | F.lli Baschiera s.r.l. costruzioni edili | tecnico.baschiera@gmail.com | +390415161423 |  |  |
| SIC-ID-000000000665 | F.lli Martino snc |  | F.lli Martino snc | f.llimartinosnc@gmail.com | +393337000361 |  |  |
| SIC-ID-000000000666 | F.p. Impresa edile |  | F.p. Impresa edile | fpimpresaedile@gmail.com | +390516131351 |  |  |
| SIC-ID-000000000667 | F.T. COSTRUZIONI INOX di Taborre Gianpaolo |  | F.T. COSTRUZIONI INOX di Taborre Gianpaolo | taborreg@libero.it | +393283325640 |  |  |
| SIC-ID-000000000668 | Fabiana | Antico1976 |  | fabiana.antico1976@gmail.com | +393405769958 |  |  |
| SIC-ID-000000000669 | Fabianobeltrami |  |  | fabianobeltrami@alice.it | +393397385012 |  |  |
| SIC-ID-000000000670 | Fabianopigaiani |  |  | fabianopigaiani@libero.it | +393332377831 |  |  |
| SIC-ID-000000000671 | Fabio |  |  | fabio@centrodicalcolo.it | +393463048419 |  |  |
| SIC-ID-000000000672 | Fabio | Chignoli |  | fabio.chignoli@cesi.it | +3933971825580 |  |  |
| SIC-ID-000000000673 | Fabio | Dalconi |  | fabio.dalconi@libero.it | +393356463793 |  |  |
| SIC-ID-000000000674 | Fabio | Feggi29 |  | fabio.feggi29@gmail.com | +393488101416 |  |  |
| SIC-ID-000000000675 | Fabio | Finot |  | finotti.fabio@sodea.it | +393357089839 |  |  |
| SIC-ID-000000000676 | Fabio | Montefiori |  | fabio.montefiori@makeitdifferent.it | +393356627747 |  |  |
| SIC-ID-000000000677 | Fabio | Turcatel |  | fabio.turcatel@tecnolines.it | +393494354699 |  |  |
| SIC-ID-000000000678 | Fabio Mantovan Costruzioni |  | Fabio Mantovan Costruzioni | effepicostruzioni@email.it | +393356627747 |  |  |
| SIC-ID-000000000679 | Fabio Pasqualato Impianti |  | Fabio Pasqualato Impianti | fabiopasqualato@gmail.com | +393247478788 |  |  |
| SIC-ID-000000000680 | Fabio92_3 |  |  | fabio92_3@libero.it | +393476495083 |  |  |
| SIC-ID-000000000681 | Fabiobernardinello457 |  |  | fabiobernardinello457@live.it | +393497209455 |  |  |
| SIC-ID-000000000682 | Fabmele |  |  | fab06mele@gmail.com | +393501634405 |  |  |
| SIC-ID-000000000683 | Fabrilemassimo Snc |  | Fabrilemassimo Snc | fabrilemassimo.snc@gmail.com | +3937134641310 |  |  |
| SIC-ID-000000000684 | Fabrizio | Andreotti |  | fabrizio.andreotti@gmail.com | +393480702571 |  |  |
| SIC-ID-000000000685 | Fabrizio Costruzioni |  | Fabrizio Costruzioni | fabrizio@autoaccessoriopolesano.it | +393357035951 |  |  |
| SIC-ID-000000000686 | Facente Giuseppe impresa edile |  | Facente Giuseppe impresa edile | hello@mygroovydomain.com | +393485524929 |  |  |
| SIC-ID-000000000687 | Falegnameria | Giovanni |  | giovanni.sanguin@icloud.com | +393487560280 |  |  |
| SIC-ID-000000000688 | Falk | Edil |  | falkedil@libero.it | +390815025472 |  |  |
| SIC-ID-000000000689 | Falserbau |  |  | info@falserbau.it | +390471353460 |  |  |
| SIC-ID-000000000690 | Famar s.r.l. - falconara m.ma |  | Famar s.r.l. - falconara m.ma | info@famaredilizia.it | +390719160096 |  |  |
| SIC-ID-000000000691 | Tiziana Boscolo |  | FANTASYLANDIA di Boscolo Tiziana | fantasylandiachioggia@gmail.com | +393494233465 | Chioggia | VE |
| SIC-ID-000000000692 | Farinazzoandrea | Euro hygiene |  | farinazzoandrea@gmail.com | +393403675711 |  |  |
| SIC-ID-000000000693 | faro | valentina |  | farosalotti.valentina@gmail.com | +393295981059 |  |  |
| SIC-ID-000000000694 | Farosalotti | Granfo |  | farosalotti@gmail.com | +3934864103900 |  |  |
| SIC-ID-000000000695 | FASE4 srl Materiali Edili |  | FASE4 srl Materiali Edili | fase4srl@outlook.it | +390332723177 |  |  |
| SIC-ID-000000000696 | Fassari | ColorHome |  | info@fassari.com | +39095495238 |  |  |
| SIC-ID-000000000697 | Fastcolor sas |  | Fastcolor sas | info@fastcolor.it | +393471484864 |  |  |
| SIC-ID-000000000698 | Fatpalma | Cicciona |  | fatpalma@gmail.com | +393496409428 |  |  |
| SIC-ID-000000000699 | Fatture | Tipog |  | fatture@credimi.com | +3934796372120 |  |  |
| SIC-ID-000000000700 | Fausto | Peratello |  | gustame.sottomarina@gmail.com | +393453609168 |  |  |
| SIC-ID-000000000701 | Fausto Marco Peratello | Pizza |  | fausto.pizza@yahoo.it | +393489010893 |  |  |
| SIC-ID-000000000702 | Favaro Friggitoria | Favaro |  | favaro.nicola@yahoo.it | +393203327015 | San Martino di Venezze | RO |
| SIC-ID-000000000703 | Favro_barbara |  |  | favro_barbara@hotmail.it | +3933357470250 |  |  |
| SIC-ID-000000000704 | FD | Rent Event |  | event@fdrentservice.com | +3933332994090 |  |  |
| SIC-ID-000000000705 | FD | Rent Folini |  | maurizio.folini@fdrentservice.com | +3934007080690 |  |  |
| SIC-ID-000000000706 | FD | Rent Victor |  | victor.damiani@fdrentservice.com | +393381151262 |  |  |
| SIC-ID-000000000707 | FEA SRL |  | FEA SRL | direzione@feasrl.eu | +390597230987 |  |  |
| SIC-ID-000000000708 | Fecchio | Federico |  | fecchio.impiantielettrici@gmail.com | +393393897953 |  |  |
| SIC-ID-000000000709 | Fedeemi | Verona |  | fedeemi@libero.it | +393282174556 |  |  |
| SIC-ID-000000000710 | Fedenardi8 | Nardini |  | fedenardi8@yahoo.it | +393455075098 |  |  |
| SIC-ID-000000000711 | Federicco | Adria shopping |  | federicco@live.it | +3934797373120 |  |  |
| SIC-ID-000000000712 | Federico |  |  | federico@carbonera.net | +3938995558470 |  |  |
| SIC-ID-000000000713 | Federico | Caselli |  | federico.caselli@tryeco.com | +3932078747670 |  |  |
| SIC-ID-000000000714 | Federico | Lyo |  | morefed@alice.it | +393935437121 |  |  |
| SIC-ID-000000000715 | Federico | Sabbadin |  | federico.sabbadin@alice.it | +393714227283 |  |  |
| SIC-ID-000000000716 | Federico Costruzioni |  | Federico Costruzioni | federico@saldoteck.com | +393491690272 |  |  |
| SIC-ID-000000000717 | Federico_milano | Fonso pittori |  | federico_milano@hotmail.com | +3934957638810 |  |  |
| SIC-ID-000000000718 | Federico_spinadin |  |  | federico_spinadin@hotmail.it | +393484125170 |  |  |
| SIC-ID-000000000719 | Felice | Marangoni |  | felice.marangoni@virgilio.it | +3932902468280 |  |  |
| SIC-ID-000000000720 | Fernando | Lyo |  | silurolap@hotmail.it | +393492868488 |  |  |
| SIC-ID-000000000721 | Ferramenta Fercom - Casarza Ligure |  | Ferramenta Fercom - Casarza Ligure | info@fercom.eu | +390185469089 |  |  |
| SIC-ID-000000000722 | Ferramenta Scomazzon Libero sas |  | Ferramenta Scomazzon Libero sas | marketing@scomazzon.it | +39041698800 |  |  |
| SIC-ID-000000000723 | Ferrari | Cavarzere |  | ferrari.cavarzere@gmail.com | +393357083572 |  |  |
| SIC-ID-000000000724 | Ferroedilizia |  | Ferroedilizia | commerciale@ferroedilizia.it | +390564456133 |  |  |
| SIC-ID-000000000725 | Ferroni | Bellato |  | ferroni.bellato@libero.it | +3935304443980 |  |  |
| SIC-ID-000000000726 | Ferrua | Nadia |  | ferrua.nadia@gmail.com | +393667100971 |  |  |
| SIC-ID-000000000727 | FGM | 1957 |  | info@fgm1957.com | +3905831798043 |  |  |
| SIC-ID-000000000728 | Ficara | Giuseppe |  | daniele.marceca@libero.it | +390923552752 |  |  |
| SIC-ID-000000000729 | Filippo |  |  | filippo@dottormartone.com | +393292803380 |  |  |
| SIC-ID-000000000730 | Filippo | Ong |  | filippo.ong@gmail.com | +3937924162610 |  |  |
| SIC-ID-000000000731 | Filippofiore316 | Fiore |  | filippofiore316@gmail.com | +393296605139 |  |  |
| SIC-ID-000000000732 | Filippomarangoni | Carazzato ultimo |  | filippomarangoni@patio.it | +3934013471620 |  |  |
| SIC-ID-000000000733 | FILLEA CGIL - il sindacato dei lavoratori delle costruzioni |  | FILLEA CGIL - il sindacato dei lavoratori delle costruzioni | segreteria@filleacgil.it | +393906441141 |  |  |
| SIC-ID-000000000734 | Fior Impresa edile |  | Fior Impresa edile | info@fiorimpresa.it | +390495952542 |  |  |
| SIC-ID-000000000735 | Fiorillo Srl impresa edile |  | Fiorillo Srl impresa edile | info@fiorillosrl.it | +390761309075 |  |  |
| SIC-ID-000000000736 | Flicorno |  |  | flicorno@gmail.com | +3938580633350 |  |  |
| SIC-ID-000000000737 | FloraDesign |  |  | info@floradesign.it | +393938892343 |  |  |
| SIC-ID-000000000738 | Fonsoandrea2015 Andrea impresa individuale |  | FONSO ANDREA IMPRESA INDIVIDUALE | fonsoandrea2015@gmail.com | +393803017700 | Porto Viro | RO |
| SIC-ID-000000000739 | Fonsoandrea |  |  | fonsoandrea@gmail.com | +393338491437 |  |  |
| SIC-ID-000000000740 | Formazione | GEORO |  | formazione@collegio.geometri.ro.it | +393803966493 |  |  |
| SIC-ID-000000000741 | Formazione | Lavoro |  | formazione@lonigosoccorso.it | +3932945031470 |  |  |
| SIC-ID-000000000742 | Fornoni | F.lli |  | info@fornoniflli.it | +39035905427 |  |  |
| SIC-ID-000000000743 | Forte di Bard |  | Forte di Bard | info@littlewild-gallery.com | +390125833811 |  |  |
| SIC-ID-000000000744 | Fra | Ska ssl |  | fra.ska.ssl@gmail.com | +393298876534 |  |  |
| SIC-ID-000000000745 | Fraccaroli Leonello E Figli (S.N.C.) |  | Fraccaroli Leonello E Figli (S.N.C.) | info@impresafraccaroli.com | +390458301092 |  |  |
| SIC-ID-000000000746 | Franca_caccia |  |  | franca_caccia@libero.it | +3933859653080 |  |  |
| SIC-ID-000000000747 | Francesca | Ballan |  | francesca.ballan@forlegnonovem.it | +393928632740 |  |  |
| SIC-ID-000000000748 | Francesca | vettorello |  | francyvettorello@libero.it | +393289574125 |  |  |
| SIC-ID-000000000749 | Francescaceliberto | Celiberto |  | francescaceliberto@gmail.com | +393717745138 |  |  |
| SIC-ID-000000000750 | Francescaliccardi |  |  | francescaliccardi@hotmail.com | +393285498899 |  |  |
| SIC-ID-000000000751 | Francesco | Arnone POS |  | francesco@solo.sh | +393480866502 |  |  |
| SIC-ID-000000000752 | Francesco | Arnone POS |  | operationmanager@solo.sh | +393480866502 |  |  |
| SIC-ID-000000000753 | Francesco | Brami |  | francesco.brami@changecapital.it | +393319006308 |  |  |
| SIC-ID-000000000754 | Francesco | Dalpiaz |  | francesco.dalpiaz@gmail.com | +393474483215 |  |  |
| SIC-ID-000000000755 | Francesco | Lyo |  | fravelli58@hotmail.it | +393355253084 |  |  |
| SIC-ID-000000000756 | Francesco | Passoni |  | francesco.passoni@unionextrusion.it | +393389168273 |  |  |
| SIC-ID-000000000757 | Francesco | Schioppa |  | francesco.schioppa@gmail.com | +393483635918 |  |  |
| SIC-ID-000000000758 | Francesco | Soncini |  | soncini.francesco@alice.it | +393283919197 |  |  |
| SIC-ID-000000000759 | Francesco | Zanirato |  | francesco.zanirato@yahoo.it | +393348374549 |  |  |
| SIC-ID-000000000760 | Francesco | Zantedeschi |  | francesco.zantedeschi@isolabio.eu | +393208858567 |  |  |
| SIC-ID-000000000761 | Francescogeomverzola |  |  | francescogeomverzola@gmail.com | +393491990407 |  |  |
| SIC-ID-000000000762 | Franco | Feola4 |  | franco.feola4@gmail.com | +39386474470 |  |  |
| SIC-ID-000000000763 | Franco | Pasqua59 |  | franco.pasqua59@gmail.com | +3932807079460 |  |  |
| SIC-ID-000000000764 | Franco | Rossi |  | franco.rossi@ater.rovigo.it | +393358140876 |  |  |
| SIC-ID-000000000765 | Franco Costruzioni srl |  | Franco Costruzioni srl | info@gruppofranco.it | +39049629368 |  |  |
| SIC-ID-000000000766 | FrancoNeri-BG |  |  | franconeri51@gmail.com | +393358055687 |  |  |
| SIC-ID-000000000767 | Francy | Fumi |  | francy.fumi@tiscali.it | +393388077946 |  |  |
| SIC-ID-000000000768 | Franz | Lanaro |  | franz.lanaro@gmail.com | +393282161977 |  |  |
| SIC-ID-000000000769 | Franzonstefano86 |  |  | franzonstefano86@gmail.com | +393396407783 |  |  |
| SIC-ID-000000000770 | Fratelli | Marrazzo |  | info@fratellimarrazzoedilizia.it | +390961074025 |  |  |
| SIC-ID-000000000771 | Fratelli | Sindoni |  | info@fratellisindoni.com | +390931463353 |  |  |
| SIC-ID-000000000772 | Fratelli | simonetti |  | info@fratellisimonetti.com | +390714600540 |  |  |
| SIC-ID-000000000773 | Fratelli Capra impresa edile a lugo |  | Fratelli Capra impresa edile a lugo | giampaolo_capra@libero.it | +393386750022 |  |  |
| SIC-ID-000000000774 | Fratelli Nardi Magazzino Edile |  | Fratelli Nardi Magazzino Edile | flli.nardi@libero.it | +39058823015 |  |  |
| SIC-ID-000000000775 | Fratelli Sposetti SRL - SPOSETTI PAVIMENTAZIONI |  | Fratelli Sposetti SRL - SPOSETTI PAVIMENTAZIONI | info@sposettipavimentazioni.com | +39034342341 |  |  |
| SIC-ID-000000000776 | Frezza | Andrea |  | frezza.andrea@libero.it | +393331492007 |  |  |
| SIC-ID-000000000777 | Frigo Tetti Di Riccardo Frigo |  | Frigo Tetti Di Riccardo Frigo | info@frigotetti.com | +390424692050 |  |  |
| SIC-ID-000000000778 | Futura S.r.l. |  | Futura S.r.l. | info@futura.srl | +393276754136 |  |  |
| SIC-ID-000000000779 | G | Fiorenzato |  | g.fiorenzato@ispettorato.gov.it | +393487937411 |  |  |
| SIC-ID-000000000780 | G | Landini |  | g.landini@stablecomp.com | +393355858184 |  |  |
| SIC-ID-000000000781 | G. & V. - Impresa Edile Pistoia |  | G. & V. - Impresa Edile Pistoia | info@annalu.it | +393454589830 |  |  |
| SIC-ID-000000000782 | G. Edilizia Technology srl |  | G. Edilizia Technology srl | parafarmaciarattazzi@gmail.com | +393371061111 |  |  |
| SIC-ID-000000000783 | G.B.A. Costruzioni Srl |  | G.B.A. Costruzioni Srl | info@gbacostruzioni.it | +39058353406 |  |  |
| SIC-ID-000000000784 | G.B.F. Srl Lattoneria – Rivenditore VELUX |  | G.B.F. Srl Lattoneria – Rivenditore VELUX | gbfinduno@gbfsrl.it | +390332200069 |  |  |
| SIC-ID-000000000785 | G.g.s. Lavori edili di geloso salvatore |  | G.g.s. Lavori edili di geloso salvatore | assistenza@webador.it | +393343325479 |  |  |
| SIC-ID-000000000786 | Gabriele | Rossi |  | gabry88.rossi@gmail.com | +393282422301 |  |  |
| SIC-ID-000000000787 | Gabriele | Senno |  | gabriele.senno@amesvenezia.it | +3934915912520 |  |  |
| SIC-ID-000000000788 | Gabriele | Zanirato |  | gabriele.zanirato@hotmail.it | +393397385012 |  |  |
| SIC-ID-000000000789 | Gabrio | 45 |  | info@ristorantemolo45.it | +393293619477 |  |  |
| SIC-ID-000000000790 | Gaetano | Soardo |  | gaetano.soardo@gmail.com | +393482258004 |  |  |
| SIC-ID-000000000791 | Gaetanosicurello |  |  | gaetanosicurello@gmail.com | +393497227068 |  |  |
| SIC-ID-000000000792 | Galliera Costruzioni s.r.l. |  | Galliera Costruzioni s.r.l. | gallieram@libero.it | +390532897718 |  |  |
| SIC-ID-000000000793 | Galluppi | Simona |  | galluppi.simona@libero.it | +393775459877 |  |  |
| SIC-ID-000000000794 | Gam | Opere edili |  | info@gamelettroimpianti.it | +393204134295 |  |  |
| SIC-ID-000000000795 | Gardenale | estintori |  | gardenaleestintori@libero.it | +390425757906 |  |  |
| SIC-ID-000000000796 | Gasparettoandrea2000 |  | Gasparettoandrea2000 | gasparettoandrea2000@outlook.it | +393283919197 |  |  |
| SIC-ID-000000000797 | GBR Solution - Coperture / Edilizia / Impermeabilizzazioni |  | GBR Solution - Coperture / Edilizia / Impermeabilizzazioni | info@gbrsolution.it | +390222226791 |  |  |
| SIC-ID-000000000798 | Gcab5197 |  |  | gcab5197@gmail.com | +393477385364 |  |  |
| SIC-ID-000000000799 | Gefco | Italia |  | camille.baudin@gefco.co.uk | +393902932701 |  |  |
| SIC-ID-000000000800 | Gem Or service |  | Gem Or service | gem@godaddy.com | +3934714717140 |  |  |
| SIC-ID-000000000801 | Gemma S.r.l. |  | Gemma S.r.l. | michela.taddia@gemmasrl.net | +390532846537 |  |  |
| SIC-ID-000000000802 | GENESYS | ARCHITETTI |  | info@genesystudio.it | +393343402538 |  |  |
| SIC-ID-000000000803 | Gennari Matteo SOLUZIONE SNC |  | Gennari Matteo SOLUZIONE SNC | soluzione_legno@libero.it | +393428264120 |  |  |
| SIC-ID-000000000804 | geo stefano arch. Tamburin |  | geo stefano arch. Tamburin | geostefano.za@gmail.com | +3933416901510 |  |  |
| SIC-ID-000000000805 | Geoale1962 Marciano' surf expo roma |  | Geoale1962 Marciano' surf expo roma | geoale1962@libero.it | +393387003044 |  |  |
| SIC-ID-000000000806 | Geom | Giovanninimauro |  | geom.giovanninimauro@alice.it | +393487236264 |  |  |
| SIC-ID-000000000807 | Geom | Sante |  | studio-ceciliato@libero.it | +393394213478 |  |  |
| SIC-ID-000000000808 | Geometri | Fiziamenti |  | geometri.finanziamenti@popso.it | +393389709623 |  |  |
| SIC-ID-000000000809 | Geopla | Cutuli |  | geopla@libero.it | +393357035951 |  |  |
| SIC-ID-000000000810 | GES.CO. EDILICIA SRL |  | GES.CO. EDILICIA SRL | legalerappresentante@gescoedilicia.com | +393426332896 |  |  |
| SIC-ID-000000000811 | Gestionedocumenticlientimerciaida |  |  | gestionedocumenticlientimerciaida@posteitaliane.it | +3934758857950 |  |  |
| SIC-ID-000000000812 | Gesu2406 |  |  | gesu2406@gmail.com | +393494339860 |  |  |
| SIC-ID-000000000813 | GF Carval - Edilizia |  | GF Carval - Edilizia | shop@gfcarval.it | +39064512027 |  |  |
| SIC-ID-000000000814 | Gheco Costruzioni Generali S.r.l. |  | Gheco Costruzioni Generali S.r.l. | info@gheco.it | +3906622860300 |  |  |
| SIC-ID-000000000815 | Giacomo | Franzoso |  | giacomo.franzoso@gmail.com | +393463336525 |  |  |
| SIC-ID-000000000816 | Giacomo. | DOMA Doná |  | giacomo@agentidoma.it | +393938118156 |  |  |
| SIC-ID-000000000817 | Giacomotrabalzini | Trabalzini |  | giacomotrabalzini@hotmail.com | +393518290377 |  |  |
| SIC-ID-000000000818 | Giametta |  |  | info@giammetta.it | +390923941470 |  |  |
| SIC-ID-000000000819 | Giampaolo | Lyo |  | diego.ddm747@gmail.com | +393921253679 |  |  |
| SIC-ID-000000000820 | Giampaolobaccaglini |  |  | giampaolobaccaglini@virgilio.it | +393452150198 |  |  |
| SIC-ID-000000000821 | Giancarlo | Gennari |  | gennari.giancarlo@libero.it | +393339932757 |  |  |
| SIC-ID-000000000822 | Gianfrancacris |  |  | gianfrancacris@gmail.com | +393289574125 |  |  |
| SIC-ID-000000000823 | Gianfranco | It |  | gianfranco.it@gmail.com | +3933816269220 |  |  |
| SIC-ID-000000000824 | Gianfranco | Lo Cascio |  | gianfranco@bbq4all.it | +393479133699 |  |  |
| SIC-ID-000000000825 | Gianfrancogriggio |  |  | gianfrancogriggio@tin.it | +393357627572 |  |  |
| SIC-ID-000000000826 | Gianluca | Follini |  | gianluca.follini@gmail.com | +393883537782 |  |  |
| SIC-ID-000000000827 | Gianluca | Galetto |  | gianluca.galetto@gmail.com | +393476488692 |  |  |
| SIC-ID-000000000828 | Gianluca | Sacco |  | gianluca.sacco@libero.it | +393472376788 |  |  |
| SIC-ID-000000000829 | Gianluca | Schio |  | gianluca.schio@live.it | +393334597799 |  |  |
| SIC-ID-000000000830 | Gianlucacarraro |  |  | gianlucacarraro@iol.it | +393206294116 |  |  |
| SIC-ID-000000000831 | Gianni | Bertelle |  | gianni.bertelle@gmail.com | +393283352697 |  |  |
| SIC-ID-000000000832 | Gianni | Bragante |  | gianni.bragante@email.it | +393476495083 |  |  |
| SIC-ID-000000000833 | Gianni | Cantelli |  | gianni.cantelli@alice.it | +393482403080 |  |  |
| SIC-ID-000000000834 | Gianni | Schiavon |  | janisero@gmail.com | +393478526000 |  |  |
| SIC-ID-000000000835 | Giannibuldo |  |  | giannibuldo@yahoo.it | +393356924648 |  |  |
| SIC-ID-000000000836 | Giattiluciano |  |  | giattiluciano@gmail.com | +393477048924 |  |  |
| SIC-ID-000000000837 | Ginardi Arredamenti Srl |  | Ginardi Arredamenti Srl | info@sititematici.it | +39065895027 |  |  |
| SIC-ID-000000000838 | Ginko Srl |  | Ginko Srl | tappezzeriasalese@gmail.com | +393486618168 |  |  |
| SIC-ID-000000000839 | Gino |  |  | gino@strovigo.com | +3934536091680 |  |  |
| SIC-ID-000000000840 | Giordanocostruzioni Giordano |  | Giordanocostruzioni Giordano | giordanocostruzioni@gmail.com | +393357542030 |  |  |
| SIC-ID-000000000841 | Giorgetti Renzo / Magazzino Edile Lucca, Pavimenti, Arredo Bagno, Caminetti e Terrecotte |  | Giorgetti Renzo / Magazzino Edile Lucca, Pavimenti, Arredo Bagno, Caminetti e Terrecotte | info@giorgettirenzo.it | +390583928103 |  |  |
| SIC-ID-000000000842 | Giorgio | Dagostini |  | giorgio.dagostini@elitestate.it | +393490781729 |  |  |
| SIC-ID-000000000843 | Giorgio | Decastello |  | giorgio.decastello@haritalia.com | +393488214093 |  |  |
| SIC-ID-000000000844 | Giorgio | Grimani |  | giorgio@grimani.eu | +393492543709 |  |  |
| SIC-ID-000000000845 | Giorgio | Marchioro |  | giorgio.marchioro@libero.it | +393466455746 |  |  |
| SIC-ID-000000000846 | Giorgio.ferraro | Enel |  | giorgio.ferraro@enel.com | +393292409684 |  |  |
| SIC-ID-000000000847 | Giorgio_pizzolato |  |  | giorgio_pizzolato@libero.it | +393484850361 |  |  |
| SIC-ID-000000000848 | Giorusso | Russo |  | gio79russo@libero.it | +393384327131 |  |  |
| SIC-ID-000000000849 | Giosga | Tiengo |  | giosga77@yahoo.it | +393474843509 |  |  |
| SIC-ID-000000000850 | Giovanna | Gentilomi |  | giovanna.gentilomi@unibo.it | +393396551537 |  |  |
| SIC-ID-000000000851 | Giovannaguazzelli1961 |  |  | giovannaguazzelli1961@gmail.com | +393427417949 |  |  |
| SIC-ID-000000000852 | Giovannalubjan |  |  | giovannalubjan@gmail.com | +393498588010 |  |  |
| SIC-ID-000000000853 | Giovanni |  |  | giovanni@giovannibillo.it | +393479637212 |  |  |
| SIC-ID-000000000854 | Giovanni |  |  | edil.future@gmail.com | +393388543587 |  |  |
| SIC-ID-000000000855 | Giovanni | Carloni |  | giovanni.carloni@ebaf.ch | +393388543587 |  |  |
| SIC-ID-000000000856 | Giovanni | D'Andrea |  | dandreagiovanni1989@virgilio.it | +393403873567 |  |  |
| SIC-ID-000000000857 | Giovanni | Lora7 |  | giovanni.lora7@gimail.com | +393483384253 |  |  |
| SIC-ID-000000000858 | Giovanni | Zampirolo |  | giovanni.zampirolo@itstecnologie.it | +393666568531 |  |  |
| SIC-ID-000000000859 | Giovanni_casazza |  |  | giovanni_casazza@virgilio.it | +393450587109 |  |  |
| SIC-ID-000000000860 | Giovannicannella |  |  | giovannicannella@tiscali.it | +393351288446 |  |  |
| SIC-ID-000000000861 | Giovannilaterza |  |  | giovannilaterza@libero.it | +393299267400 |  |  |
| SIC-ID-000000000862 | Giovannirossato | Ro |  | giovannirossato.ro@libero.it | +393917528554 |  |  |
| SIC-ID-000000000863 | Giovannyangelo | Lanzetta |  | giovannyangelo@outlook.com | +393490704524 |  |  |
| SIC-ID-000000000864 | Girardi Diego |  | Girardi Diego | girardi.d@libero.it | +393289647841 |  |  |
| SIC-ID-000000000865 | Giu | Sep78 |  | giu.sep78@alice.it | +393339625351 |  |  |
| SIC-ID-000000000866 | Giulia | FTV |  | giulia.bergamin92@gmail.com | +393477334148 |  |  |
| SIC-ID-000000000867 | Giulia09 |  |  | giulia09@ymail.com | +393475357617 |  |  |
| SIC-ID-000000000868 | Giuliana | AttivaMente |  | giuliana.gradara@attivamenteonlus.it | +393283339015 |  |  |
| SIC-ID-000000000869 | Giuliana | Bttn |  | giuliana.bttn@gmail.com | +393296605139 |  |  |
| SIC-ID-000000000870 | Giuliano | Ponzilacqua |  | giuliano.ponzilacqua@uparchitettiassociati.it | +393487937411 |  |  |
| SIC-ID-000000000871 | Giulianoberto |  |  | giuliano69berto@gmail.com | +393489751611 |  |  |
| SIC-ID-000000000872 | Giulio | Massetti |  | luca.biadolla@gmail.com | +393483845017 |  |  |
| SIC-ID-000000000873 | Giusemice | Micello |  | giusemice@gmail.com | +393483022554 |  |  |
| SIC-ID-000000000874 | Giuseppe | Brancaleon |  | verdescaligera@gmail.com | +393292259549 |  |  |
| SIC-ID-000000000875 | Giuseppe | Fossati |  | giuseppe.fossati@carisbo.it | +393289574125 |  |  |
| SIC-ID-000000000876 | Giuseppe Blunda costruzioni |  | Giuseppe Blunda costruzioni | example@domain.com | +390924076431 |  |  |
| SIC-ID-000000000877 | Giuseppe_07 Sicchiero acque venete spa |  | Giuseppe_07 Sicchiero acque venete spa | giuseppe_07@alice.it | +393387828022 |  |  |
| SIC-ID-000000000878 | Giuseppinadevitooo |  |  | giuseppinadevitooo@gmail.com | +393343509912 |  |  |
| SIC-ID-000000000879 | Global Edilizia |  | Global Edilizia | info@globaledilizia.it | +393335637851 |  |  |
| SIC-ID-000000000880 | Globaltech1987 Posa 3000 |  | Globaltech1987 Posa 3000 | globaltech1987@gmail.com | +393478566813 |  |  |
| SIC-ID-000000000881 | Gm Impresa edile di guidolin massimo |  | Gm Impresa edile di guidolin massimo | info@gmimpresaedile.it | +393490976671 |  |  |
| SIC-ID-000000000882 | GMSC Serio Costruzioni |  | GMSC Serio Costruzioni | gmscseriocostruzioni@gmail.com | +393476132720 |  |  |
| SIC-ID-000000000883 | Gobbato | S. l |  | f.lligobbato@libero.it | +393480836896 |  |  |
| SIC-ID-000000000884 | Golin | RCM |  | mat82it@yahoo.it | +393408718063 |  |  |
| SIC-ID-000000000885 | Govoni Costruzioni snc |  | Govoni Costruzioni snc | info@govonicostruzioni.it | +390532870868 |  |  |
| SIC-ID-000000000886 | Gp Impresa edile bologna |  | Gp Impresa edile bologna | edil.pinto.tommaso@gmail.com | +393358033747 |  |  |
| SIC-ID-000000000887 | Gradarafederico Milan contatori acque venete |  | Gradarafederico Milan contatori acque venete | gradarafederico@gmail.com | +393336184558 |  |  |
| SIC-ID-000000000888 | Granellacostruzionisrl |  | Granellacostruzionisrl | granellacostruzionisrl@gmail.com | +393771889290 |  |  |
| SIC-ID-000000000889 | Graziano | Paolo |  | graziano@bbsgroup.it | +393485115931 |  |  |
| SIC-ID-000000000890 | Green House srl - impresa edile |  | Green House srl - impresa edile | greenhouse.costr@gmail.com | +390744432516 |  |  |
| SIC-ID-000000000891 | Grivetti Restauri s.rl impresa edile - ristrutturazioni; costruzioni; appaltatori |  | Grivetti Restauri s.rl impresa edile - ristrutturazioni; costruzioni; appaltatori | info@grivettirestauri.it | +390598677935 |  |  |
| SIC-ID-000000000892 | Grosseto Edilizia |  | Grosseto Edilizia | info@grossetoedilizia.com | +3905641931990 |  |  |
| SIC-ID-000000000893 | Group E - Edilflaminio |  | Group E - Edilflaminio | info@gruppoe.com | +390652996300 |  |  |
| SIC-ID-000000000894 | Gruppo | Castaldi |  | privacy@gruppocastaldi.it | +390692936099 |  |  |
| SIC-ID-000000000895 | Gruppo Di stefano srl |  | Gruppo Di stefano srl | gruppodistefano@libero.it | +390924922193 |  |  |
| SIC-ID-000000000896 | Gruppo Michielan srl - impresa edile |  | Gruppo Michielan srl - impresa edile | info@gruppomichielan.it | +39041447547 |  |  |
| SIC-ID-000000000897 | Guidom48 |  |  | guidom48@virgilio.it | +393355366712 |  |  |
| SIC-ID-000000000898 | Gullo Costruzioni |  | Gullo Costruzioni | info@026969.it | +393287366969 |  |  |
| SIC-ID-000000000899 | Gumina | Gianluca |  | gumina.gianluca@gmail.com | +393486928673 |  |  |
| SIC-ID-000000000900 | HABIMAT F.lli Crusco S.R.L. |  | HABIMAT F.lli Crusco S.R.L. | info@fioriniedilizia.it | +390985801852 |  |  |
| SIC-ID-000000000901 | Habitat |  |  | habitatdiamante@gmail.com | +393356526067 |  |  |
| SIC-ID-000000000902 | HABITUS / Led Edilizia |  | HABITUS / Led Edilizia | commerce@lededilizia.it | +390690212142 |  |  |
| SIC-ID-000000000903 | Handyman |  |  | thehandyman.it@gmail.com | +393495755056 |  |  |
| SIC-ID-000000000904 | HDC - Hub Design Coronari |  | HDC - Hub Design Coronari | info@epmroma.it | +390668804608 |  |  |
| SIC-ID-000000000905 | Ranzato Andrea |  | I.D.E.A. RESTAURI SNC DI RANZATO ANDREA E PERINI DAVIDE | ranzatoandrea1976@libero.it | +3932856221690 | Chioggia | VE |
| SIC-ID-000000000906 | I.r.e. impresa ripristini edili |  | I.r.e. impresa ripristini edili | info@impresaire.com | +393907135791 |  |  |
| SIC-ID-000000000907 | Ianniello | Alessio |  | ianniello.alessio@gmail.com | +393402304639 |  |  |
| SIC-ID-000000000908 | ICEA S.R.L / Impresa di ristrutturazioni edilizie, costruzioni, impianti fotovoltaici, pannelli solari |  | ICEA S.R.L / Impresa di ristrutturazioni edilizie, costruzioni, impianti fotovoltaici, pannelli solari | info@iceaimpianti.com | +39070789306 |  |  |
| SIC-ID-000000000909 | IDE Danico edilizia srl |  | IDE Danico edilizia srl | danico@danico.it | +390695461072 |  |  |
| SIC-ID-000000000910 | Idea Colore s.r.l. |  | Idea Colore s.r.l. | info@ideacolore.it | +393905043075 |  |  |
| SIC-ID-000000000911 | Ideal Impianti s.r.l |  | Ideal Impianti s.r.l | info.idealimpiantisrl@gmail.com | +39092487046 |  |  |
| SIC-ID-000000000912 | Idraulica Gazometro |  | Idraulica Gazometro | info@idraulicagazometro.com | +39065780761 |  |  |
| SIC-ID-000000000913 | Ignazio | Puccio |  | ignaziopuccio@libero.it | +393923387776 |  |  |
| SIC-ID-000000000914 | Il | Tetto |  | iltettocasesnc@gmail.com | +390463973230 |  |  |
| SIC-ID-000000000915 | Marika Gelateria TDP |  | Il Capriccio S.n.c. di Spinello Alessandro e Mantovani Marika | spinello.alessandro@libero.it | +393403197383 | Taglio di Po | RO |
| SIC-ID-000000000916 | Ilaria | Ilaria |  | gottardo.ilaria@sangaetano.org | +3938049691310 |  |  |
| SIC-ID-000000000917 | Ilaria | Livolsi |  | ilaria.livolsi@attivamenteonlus.it | +393717745138 |  |  |
| SIC-ID-000000000918 | Ilenia | Francescon |  | ilenia.francescon@libero.it | +393473223614 |  |  |
| SIC-ID-000000000919 | IMI | Busatto |  | imisrl2015@libero.it | +393285358695 |  |  |
| SIC-ID-000000000920 | Immobil Service Srl |  | Immobil Service Srl | info@immobilservicesrl.it | +390697841609 |  |  |
| SIC-ID-000000000921 | Immobiliare Rebecca Sas di Tosatto Paolo |  | Immobiliare Rebecca Sas di Tosatto Paolo | rebecca.tosatto@odcecvenezia.legalmail.it | +390415660011 |  |  |
| SIC-ID-000000000922 | Immobiliare Roana |  | Immobiliare Roana | info@immobiliareroana.it | +39042466124 |  |  |
| SIC-ID-000000000923 | Imperiale Costruzioni srl |  | Imperiale Costruzioni srl | info@imperialecostruzioni.com | +393887331274 |  |  |
| SIC-ID-000000000924 | Impermeo - Impermeabilizzazioni Roma e Regione Lazio |  | Impermeo - Impermeabilizzazioni Roma e Regione Lazio | tudinihousing@legalmail.it | +393517872231 |  |  |
| SIC-ID-000000000925 | Impianti FTV CDNE |  | Impianti FTV CDNE | impiantifv@cdne.it | +393286010934 |  |  |
| SIC-ID-000000000926 | Impiantistica Edilizia 2020 |  | Impiantistica Edilizia 2020 | impiantisticaedilizia2020@gmail.com | +393931773491 |  |  |
| SIC-ID-000000000927 | Impresa |  | Impresa | impresacastaldi@gmail.com | +393791573415 |  |  |
| SIC-ID-000000000928 | Impresa Bianconi |  | Impresa Bianconi | info@bianconigroup.com | +393451558475 |  |  |
| SIC-ID-000000000929 | Impresa Bioedilizia / edilverde costruzioni s.r.l. |  | Impresa Bioedilizia / edilverde costruzioni s.r.l. | info@costruzioniedilverde.com | +390532093269 |  |  |
| SIC-ID-000000000930 | Impresa Costruzioni grossi walter s.r.l. - impresa edile a parma |  | Impresa Costruzioni grossi walter s.r.l. - impresa edile a parma | info@impresagrossi.com | +390521281212 |  |  |
| SIC-ID-000000000931 | Impresa Di Costruzioni Ing. Raffaello Pellegrini S.R.L. |  | Impresa Di Costruzioni Ing. Raffaello Pellegrini S.R.L. | odv@impresapellegrini.it | +39070265271 |  |  |
| SIC-ID-000000000932 | Impresa Di costruzioni la rinascente - sabbiatura edile |  | Impresa Di costruzioni la rinascente - sabbiatura edile | info@impresaedilelarinascente.it | +393347570784 |  |  |
| SIC-ID-000000000933 | Impresa Edile |  | Impresa Edile | cacciatoregioacchino@gmail.com | +390521816665 |  |  |
| SIC-ID-000000000934 | Impresa Edile - lattoneria mazzotti daniele |  | Impresa Edile - lattoneria mazzotti daniele | lattoneriamazzotti@alice.it | +393358233581 |  |  |
| SIC-ID-000000000935 | Impresa Edile 2g |  | Impresa Edile 2g | info@rsgroupitalia.it | +393393332441 |  |  |
| SIC-ID-000000000936 | Impresa Edile 3d giuseppe d'antuono |  | Impresa Edile 3d giuseppe d'antuono | contatto@contattogenova.it | +393488187573 |  |  |
| SIC-ID-000000000937 | Impresa Edile 4d coperture san felice sul panaro modena |  | Impresa Edile 4d coperture san felice sul panaro modena | info@4dcoperture.com | +393402638778 |  |  |
| SIC-ID-000000000938 | Impresa Edile a modena - c.c.e.a. costruzioni |  | Impresa Edile a modena - c.c.e.a. costruzioni | coop.ccea@gmail.com | +390522557781 |  |  |
| SIC-ID-000000000939 | Impresa Edile accardo giuseppe |  | Impresa Edile accardo giuseppe | info@restone.it | +393478756767 |  |  |
| SIC-ID-000000000940 | Impresa Edile aia mario costruzioni |  | Impresa Edile aia mario costruzioni | info@ananiamariocostruzioni.it | +393474674105 |  |  |
| SIC-ID-000000000941 | Impresa Edile alex service |  | Impresa Edile alex service | alexservicesrls@libero.it | +393206388988 |  |  |
| SIC-ID-000000000942 | Impresa Edile ammirata rosario |  | Impresa Edile ammirata rosario | info@hotel-boccaccio.it | +390545994551 |  |  |
| SIC-ID-000000000943 | Impresa Edile antonello s.r.l. |  | Impresa Edile antonello s.r.l. | antonellosrl@hotmail.it | +393319062180 |  |  |
| SIC-ID-000000000944 | Impresa Edile aralla |  | Impresa Edile aralla | arallanik92@gmail.com | +393312257099 |  |  |
| SIC-ID-000000000945 | Impresa Edile arena nicola |  | Impresa Edile arena nicola | info@impresaedilearena.com | +393382898521 |  |  |
| SIC-ID-000000000946 | Impresa Edile artigiana barolo graziano |  | Impresa Edile artigiana barolo graziano | d.barolo@studioarchitetti.com | +393460232526 |  |  |
| SIC-ID-000000000947 | Impresa Edile baldo paolo & c. snc |  | Impresa Edile baldo paolo & c. snc | info@botter.it | +390421205180 |  |  |
| SIC-ID-000000000948 | Impresa Edile baraldo |  | Impresa Edile baraldo | info@impresabaraldo.it | +390445364845 |  |  |
| SIC-ID-000000000949 | Impresa Edile bartucca srl |  | Impresa Edile bartucca srl | impresaedilebartucca@gmail.com | +393452235121 |  |  |
| SIC-ID-000000000950 | Impresa Edile baruffaldi luigi |  | Impresa Edile baruffaldi luigi | info.mtc@baruffaldi.it | +390516849247 |  |  |
| SIC-ID-000000000951 | Impresa Edile barzon stefano |  | Impresa Edile barzon stefano | info@impresabarzon.it | +393487492931 |  |  |
| SIC-ID-000000000952 | Impresa Edile bianchi pietro |  | Impresa Edile bianchi pietro | info@novelloec.com | +393487450933 |  |  |
| SIC-ID-000000000953 | Impresa Edile bologna pinto tommaso |  | Impresa Edile bologna pinto tommaso | tommaso@gmail.com | +39337861230 |  |  |
| SIC-ID-000000000954 | impresa edile bologna pinto tommaso |  | impresa edile bologna pinto tommaso | .tommaso@gmail.com | +39337861230 |  |  |
| SIC-ID-000000000955 | Impresa Edile Bonalumi Giuliano e Figli S.r.l. |  | Impresa Edile Bonalumi Giuliano e Figli S.r.l. | impresabonalumisrl@gmail.com | +39035542487 |  |  |
| SIC-ID-000000000956 | Impresa Edile boscain silvano e figlio |  | Impresa Edile boscain silvano e figlio | info@impresaedileboscain.it | +393472960860 |  |  |
| SIC-ID-000000000957 | Impresa Edile bucaj |  | Impresa Edile bucaj | edil@vucaj.net | +390412439466 |  |  |
| SIC-ID-000000000958 | Impresa Edile bullado antonio |  | Impresa Edile bullado antonio | info@bulladoantonio.com | +393408614252 |  |  |
| SIC-ID-000000000959 | Impresa Edile c.e.bu. s.r.l.s. |  | Impresa Edile c.e.bu. s.r.l.s. | info@ce-bu.it | +393927570025 |  |  |
| SIC-ID-000000000960 | Impresa Edile camillo mardegan |  | Impresa Edile camillo mardegan | social@mardegan.it | +393356486533 |  |  |
| SIC-ID-000000000961 | Impresa Edile Cappai Vincenzo - Sinnai |  | Impresa Edile Cappai Vincenzo - Sinnai | disservizi@amicacard.it | +393339577421 |  |  |
| SIC-ID-000000000962 | Impresa Edile catalano nicolo' |  | Impresa Edile catalano nicolo' | info@impresacatalano.it | +393394023224 |  |  |
| SIC-ID-000000000963 | Impresa Edile cdm srl |  | Impresa Edile cdm srl | segreteria.cdmsrl@gmail.com | +390522343492 |  |  |
| SIC-ID-000000000964 | Impresa Edile cestaro di cestaro claudia - lavori pubblici privati costruzioni appartamenti |  | Impresa Edile cestaro di cestaro claudia - lavori pubblici privati costruzioni appartamenti | arrigo.cestaro@libero.it | +390425701248 |  |  |
| SIC-ID-000000000965 | Impresa Edile ciaravolo luigi |  | Impresa Edile ciaravolo luigi | info@impresaedileciaravolo.it | +393408405923 |  |  |
| SIC-ID-000000000966 | Impresa Edile ciprì paolo – ristrutturazioni e lavori edili |  | Impresa Edile ciprì paolo – ristrutturazioni e lavori edili | cipripaolo2015@gmail.com | +393287545263 |  |  |
| SIC-ID-000000000967 | Impresa Edile colombani giacomo |  | Impresa Edile colombani giacomo | colombanigiacomo@alice.it | +393387218639 |  |  |
| SIC-ID-000000000968 | Impresa Edile coppola giuseppe |  | Impresa Edile coppola giuseppe | www.ariabona.it@gmail.com | +393388851285 |  |  |
| SIC-ID-000000000969 | Impresa Edile corradi |  | Impresa Edile corradi | edilcorradi@gmail.com | +393406642025 |  |  |
| SIC-ID-000000000970 | Impresa Edile costel srl di pasquale stellato |  | Impresa Edile costel srl di pasquale stellato | privacy@astrazeneca.com | +393452413595 |  |  |
| SIC-ID-000000000971 | Impresa Edile cristian |  | Impresa Edile cristian | impresaedilecristian@gmail.com | +393278663414 |  |  |
| SIC-ID-000000000972 | Impresa Edile de vincenti |  | Impresa Edile de vincenti | devincentiemanuele@yahoo.it | +393454572294 |  |  |
| SIC-ID-000000000973 | Impresa Edile di bevilacqua walter |  | Impresa Edile di bevilacqua walter | segreteria@athenagroup.eu | +390415600396 |  |  |
| SIC-ID-000000000974 | Impresa Edile di cesaretti filippo - ferrara |  | Impresa Edile di cesaretti filippo - ferrara | info@studiocesaretti.it | +393355252853 |  |  |
| SIC-ID-000000000975 | Impresa Edile di costruzioni di giovanni |  | Impresa Edile di costruzioni di giovanni | infoimpresaediledigiovanni@gmail.com | +393714193490 |  |  |
| SIC-ID-000000000976 | Impresa Edile DMBP SRL Dhamo Martin |  | Impresa Edile DMBP SRL Dhamo Martin | info@impresaediledm.it | +393404870499 |  |  |
| SIC-ID-000000000977 | Impresa Edile domi dritan |  | Impresa Edile domi dritan | domidritan2@gmail.com | +393331206032 |  |  |
| SIC-ID-000000000978 | Impresa Edile duilio gazzetta s.r.l. |  | Impresa Edile duilio gazzetta s.r.l. | info@duiliogazzettasrl.com | +39041721911 |  |  |
| SIC-ID-000000000979 | Impresa Edile ebi |  | Impresa Edile ebi | ristrutturazioneeb.srl@gmail.com | +393924658204 |  |  |
| SIC-ID-000000000980 | Impresa Edile edilangeli |  | Impresa Edile edilangeli | edilangeli@yahoo.it | +393497797571 |  |  |
| SIC-ID-000000000981 | Impresa Edile Emili Evelyn |  | Impresa Edile Emili Evelyn | noshifabjan@gmail.com | +393288769350 |  |  |
| SIC-ID-000000000982 | Impresa Edile eurocasa srl |  | Impresa Edile eurocasa srl | info@giorgiobormac.com | +390593971695 |  |  |
| SIC-ID-000000000983 | Impresa Edile euroedil di laca fred |  | Impresa Edile euroedil di laca fred | lacafred@hotmail.com | +393299378046 |  |  |
| SIC-ID-000000000984 | Impresa Edile f.lli bezzegato & c. s.n.c. |  | Impresa Edile f.lli bezzegato & c. s.n.c. | info@impresaedilebezzegato.com | +393358123514 |  |  |
| SIC-ID-000000000985 | Impresa Edile f.lli lecce |  | Impresa Edile f.lli lecce | info@impresalecce.it | +390386390025 |  |  |
| SIC-ID-000000000986 | Impresa Edile f.lli moro s.r.l. |  | Impresa Edile f.lli moro s.r.l. | media.relations@delonghigroup.com | +390422841475 |  |  |
| SIC-ID-000000000987 | Impresa Edile ferrari |  | Impresa Edile ferrari | ing.ferrari1954@libero.it | +39059340999 |  |  |
| SIC-ID-000000000988 | Claudio Ferro |  | IMPRESA EDILE FERRO CLAUDIO | morosini2003@libero.it | +39335249743 | Rosolina | RO |
| SIC-ID-000000000989 | Impresa Edile fontana |  | Impresa Edile fontana | info@impresaedilefontana.com | +393312230334 |  |  |
| SIC-ID-000000000990 | Impresa Edile francesco palmeri |  | Impresa Edile francesco palmeri | francopalmericostruzioni@gmail.com | +3902358130819 |  |  |
| SIC-ID-000000000991 | Impresa Edile fratelli facchin snc |  | Impresa Edile fratelli facchin snc | fratelli.facchin@libero.it | +39360500990 |  |  |
| SIC-ID-000000000992 | Impresa Edile fratelli giuliani |  | Impresa Edile fratelli giuliani | info@giulianibau.com | +390532892407 |  |  |
| SIC-ID-000000000993 | Impresa Edile fratelli turra di turra luigi, valerio snc |  | Impresa Edile fratelli turra di turra luigi, valerio snc | info@impresaturra.it | +390532870488 |  |  |
| SIC-ID-000000000994 | Impresa Edile gatto di gatto vittorio |  | Impresa Edile gatto di gatto vittorio | info@gattocostruzioni.com | +390422633065 |  |  |
| SIC-ID-000000000995 | Impresa Edile general costruzioni |  | Impresa Edile general costruzioni | info@gccostruzioni.net | +390422849246 |  |  |
| SIC-ID-000000000996 | Impresa Edile gentilin christian |  | Impresa Edile gentilin christian | info@gentile-costruzioni.com | +393393687302 |  |  |
| SIC-ID-000000000997 | Impresa Edile giorgio domenicangelo |  | Impresa Edile giorgio domenicangelo | aregoladarte_bo@alice.it | +39051704627 |  |  |
| SIC-ID-000000000998 | Impresa Edile grosso candido |  | Impresa Edile grosso candido | info@grossocandido.it | +393386278343 |  |  |
| SIC-ID-000000000999 | Impresa edile Leghi Luigi srl |  | Impresa edile Leghi Luigi srl | leghiluigi@gmail.com | +390350781940 |  |  |
| SIC-ID-000000001000 | Impresa Edile linguerri srl |  | Impresa Edile linguerri srl | edile.linguerri@tiscali.it | +39051851718 |  |  |
| SIC-ID-000000001001 | Impresa Edile loverre |  | Impresa Edile loverre | loverre.salvatore@gmail.com | +393393596768 |  |  |
| SIC-ID-000000001002 | Impresa Edile lugli giuseppe |  | Impresa Edile lugli giuseppe | info@casa39.com | +393384140401 |  |  |
| SIC-ID-000000001003 | Impresa Edile maccan |  | Impresa Edile maccan | impresamaccan@libero.it | +390434626550 |  |  |
| SIC-ID-000000001004 | Impresa Edile masiero cristian |  | Impresa Edile masiero cristian | info@bpriduttori.com | +39041486088 |  |  |
| SIC-ID-000000001005 | Impresa Edile masiero s.r.l. |  | Impresa Edile masiero s.r.l. | atrex@atrex.it | +390498626166 |  |  |
| SIC-ID-000000001006 | Impresa Edile masiero s.r.l. |  | Impresa Edile masiero s.r.l. | info@masierogiovanni.it | +390498626166 |  |  |
| SIC-ID-000000001007 | Impresa Edile mattiuzzo g. & g. s.n.c. |  | Impresa Edile mattiuzzo g. & g. s.n.c. | info@impresaedilemattiuzzo.it | +390421221343 |  |  |
| SIC-ID-000000001008 | Impresa Edile matulli roberto |  | Impresa Edile matulli roberto | info@pucci.it | +39054551586 |  |  |
| SIC-ID-000000001009 | Impresa Edile meli |  | Impresa Edile meli | edilemelisrl@gmail.com | +390532715361 |  |  |
| SIC-ID-000000001010 | Impresa Edile michi house |  | Impresa Edile michi house | michitumminello67@gmail.com | +393356814880 |  |  |
| SIC-ID-000000001011 | Impresa Edile michielan |  | Impresa Edile michielan | alaricomichielan@libero.it | +393491343597 |  |  |
| SIC-ID-000000001012 | IMPRESA EDILE MILAZZO del Geom. Alex Milazzo |  | IMPRESA EDILE MILAZZO del Geom. Alex Milazzo | .alex@gmail.com | +393485114815 |  |  |
| SIC-ID-000000001013 | Impresa Edile milazzo del geom. alex milazzo |  | Impresa Edile milazzo del geom. alex milazzo | alex@gmail.com | +393485114815 |  |  |
| SIC-ID-000000001014 | Impresa Edile mirco badalotti |  | Impresa Edile mirco badalotti | info@mircobadalottimpresaedile.it | +390375899032 |  |  |
| SIC-ID-000000001015 | Impresa Edile mondo costruzioni |  | Impresa Edile mondo costruzioni | info@nomesito.it | +393391538933 |  |  |
| SIC-ID-000000001016 | Impresa Edile nico costruzioni |  | Impresa Edile nico costruzioni | info@nicocostruzioni.it | +390516871487 |  |  |
| SIC-ID-000000001017 | Impresa Edile nicoli giovanni & luigino s.n.c. |  | Impresa Edile nicoli giovanni & luigino s.n.c. | info@impresaedilenicoli.it | +393318251839 |  |  |
| SIC-ID-000000001018 | Impresa Edile nova domus snc |  | Impresa Edile nova domus snc | ufficio@novadomus-snc.it | +393356892844 |  |  |
| SIC-ID-000000001019 | Impresa Edile noventa |  | Impresa Edile noventa | info@costruzioniedilinoventa.it | +393291544593 |  |  |
| SIC-ID-000000001020 | Impresa Edile oddo paolo |  | Impresa Edile oddo paolo | info@impresaedileoddopaolo.it | +393335285207 |  |  |
| SIC-ID-000000001021 | Impresa Edile omb s.r.l. |  | Impresa Edile omb s.r.l. | amministrazioneombsrl@gmail.com | +393465118880 |  |  |
| SIC-ID-000000001022 | Impresa Edile palamin mauro srl |  | Impresa Edile palamin mauro srl | info@impresaedilepalaminmaurosrl.it | +3904211880637 |  |  |
| SIC-ID-000000001023 | Impresa Edile parazza luca |  |  | info@bo.cna.it | +39051370324 |  |  |
| SIC-ID-000000001024 | Impresa Edile patrizi s.r.l. |  | Impresa Edile patrizi s.r.l. | info@impresaedilepatrizi.it | +390761751828 |  |  |
| SIC-ID-000000001025 | Impresa Edile pavan di pavan silvano e roberto s.n.c. |  | Impresa Edile pavan di pavan silvano e roberto s.n.c. | info@costruzionipavan.com | +390422807156 |  |  |
| SIC-ID-000000001026 | Impresa Edile pedocchi srl |  | Impresa Edile pedocchi srl | info@pedocchi.it | +390425756864 |  |  |
| SIC-ID-000000001027 | Impresa Edile peruzzo mirco e poletto ilario s.n.c. |  | Impresa Edile peruzzo mirco e poletto ilario s.n.c. | shoponline@peserico.it | +390445605677 |  |  |
| SIC-ID-000000001028 | Impresa Edile piazza sergio di piazza geom. nicola |  | Impresa Edile piazza sergio di piazza geom. nicola | nicola@ediliziapiazza.it | +393387002091 |  |  |
| SIC-ID-000000001029 | Impresa Edile pisconti leonardo - costruzioni e ristrutturazioni chiavi in mano |  | Impresa Edile pisconti leonardo - costruzioni e ristrutturazioni chiavi in mano | piscontileonardo@libero.it | +393456444981 |  |  |
| SIC-ID-000000001030 | Impresa Edile pivato srl |  | Impresa Edile pivato srl | impresapivato@libero.it | +390423484188 |  |  |
| SIC-ID-000000001031 | Impresa Edile progetto ristrutturare geom. cipriano tufano |  | Impresa Edile progetto ristrutturare geom. cipriano tufano | tufanocipriano@gmail.com | +393275381067 |  |  |
| SIC-ID-000000001032 | Impresa Edile ragona rag. adriano |  | Impresa Edile ragona rag. adriano | impresaedile.ragona@gmail.com | +393402182772 |  |  |
| SIC-ID-000000001033 | Impresa Edile rampazzo aurelio s.r.l. |  | Impresa Edile rampazzo aurelio s.r.l. | info@impresarampazzo.it | +390415489457 |  |  |
| SIC-ID-000000001034 | Impresa Edile reggio emilia c.c.e.a. costruzioni |  | Impresa Edile reggio emilia c.c.e.a. costruzioni | info@cceacostruzioni.it | +390522557781 |  |  |
| SIC-ID-000000001035 | Impresa Edile Roma - Gruppo EB |  | Impresa Edile Roma - Gruppo EB | info@gruppoeb.it | +390656548418 |  |  |
| SIC-ID-000000001036 | Impresa Edile Roma - S.E.A. 89 |  | Impresa Edile Roma - S.E.A. 89 | info@sea89.it | +39066532632 |  |  |
| SIC-ID-000000001037 | Impresa Edile rossi giuseppe |  | Impresa Edile rossi giuseppe | info@impresarossi.it | +39042623013 |  |  |
| SIC-ID-000000001038 | Impresa Edile Rossi Michele |  | Impresa Edile Rossi Michele | rossiedil@libero.it | +39360340111 |  |  |
| SIC-ID-000000001039 | Impresa Edile rovigo |  | Impresa Edile rovigo | info@impresaboaretto.it | +393293406892 |  |  |
| SIC-ID-000000001040 | Impresa Edile rukolli |  | Impresa Edile rukolli | ramadanrukolli6@gmail.com | +393396627702 |  |  |
| SIC-ID-000000001041 | Impresa Edile russo gabriele |  | Impresa Edile russo gabriele | geom.russogabriele@gmail.com | +393383767187 |  |  |
| SIC-ID-000000001042 | Impresa Edile sabbion |  | Impresa Edile sabbion | info@impresaedilesabbion.it | +39049719285 |  |  |
| SIC-ID-000000001043 | Impresa Edile scarato |  | Impresa Edile scarato | impresascarato@gmail.com | +393484904503 |  |  |
| SIC-ID-000000001044 | Impresa Edile Schirru Oscar |  | Impresa Edile Schirru Oscar | oscar.schirru@gmail.com | +393931946256 |  |  |
| SIC-ID-000000001045 | Impresa Edile segatto s.r.l |  | Impresa Edile segatto s.r.l | info@impresaedilesegatto.it | +390422746046 |  |  |
| SIC-ID-000000001046 | Impresa Edile selmani |  | Impresa Edile selmani | impresaselmani@gmail.com | +393922490990 |  |  |
| SIC-ID-000000001047 | Impresa Edile sinigaglia daniele |  | Impresa Edile sinigaglia daniele | info@edilsinigaglia.com | +393386871500 |  |  |
| SIC-ID-000000001048 | Impresa Edile spricigo adamo e figli sas |  | Impresa Edile spricigo adamo e figli sas | impresa@edilespricigo.it | +390422396164 |  |  |
| SIC-ID-000000001049 | Impresa Edile stangherlin camillo e adriano s.r.l. |  | Impresa Edile stangherlin camillo e adriano s.r.l. | info@stangherlin.it | +390423468250 |  |  |
| SIC-ID-000000001050 | Impresa Edile stradale e movimento terra sciacca francesco |  | Impresa Edile stradale e movimento terra sciacca francesco | info@impresasciaccafrancesco.it | +393284003935 |  |  |
| SIC-ID-000000001051 | Impresa Edile stradale ghedin di ghedin umberto & figlio |  | Impresa Edile stradale ghedin di ghedin umberto & figlio | investor.relations@delonghigroup.com | +39042297285 |  |  |
| SIC-ID-000000001052 | Impresa Edile tasselli |  | Impresa Edile tasselli | info@impresatasselli.it | +39054525942 |  |  |
| SIC-ID-000000001053 | Impresa edile Tecnicaedilizia di Cherubini Michele |  | Impresa edile Tecnicaedilizia di Cherubini Michele | tecnicaedilizia@tecnicaedilizia.it | +390458621996 |  |  |
| SIC-ID-000000001054 | Impresa Edile tecnocostruzioni |  | Impresa Edile tecnocostruzioni | tecnocostruzionivozza@gmail.com | +393382781333 |  |  |
| SIC-ID-000000001055 | Impresa Edile tessaro remo |  | Impresa Edile tessaro remo | info@impresatessaro.com | +39049631031 |  |  |
| SIC-ID-000000001056 | Impresa Edile tomanin |  | Impresa Edile tomanin | giuliotomanin975@gmail.com | +393286891119 |  |  |
| SIC-ID-000000001057 | Impresa Edile Tommasini Di Tommasini Enio & C. Snc |  | Impresa Edile Tommasini Di Tommasini Enio & C. Snc | impresatommasini@gmail.com | +390457156286 |  |  |
| SIC-ID-000000001058 | Impresa Edile tosatto di verghi tosatto |  | Impresa Edile tosatto di verghi tosatto | immobiliare.rebecca@tosatto.it | +390415780128 |  |  |
| SIC-ID-000000001059 | Impresa Edile tosatto paolo |  | Impresa Edile tosatto paolo | webmaster@tosatto.it | +393395273843 |  |  |
| SIC-ID-000000001060 | Impresa Edile tre n.p. |  | Impresa Edile tre n.p. | impresaediletrenp@gmail.com | +393489289591 |  |  |
| SIC-ID-000000001061 | Impresa Edile viterbo - gruppo eb |  | Impresa Edile viterbo - gruppo eb | info@bruppoeb.it | +3907611576845 |  |  |
| SIC-ID-000000001062 | Impresa Edile zemolini srl |  | Impresa Edile zemolini srl | info@impresaedilezemolini.com | +390532478339 |  |  |
| SIC-ID-000000001063 | Impresa Edile Zimer - Coperture e Bonifica Amianto |  | Impresa Edile Zimer - Coperture e Bonifica Amianto | tecnico@impresazimer.com | +390432934928 |  |  |
| SIC-ID-000000001064 | Impresa Edile zorzin |  | Impresa Edile zorzin | info@impresaedilezorzin.it | +393491417165 |  |  |
| SIC-ID-000000001065 | Impresa Giorgio Fontana |  | Impresa Giorgio Fontana | info@costruzioniedilifontana.it | +390332334012 |  |  |
| SIC-ID-000000001066 | Impresa lavori marittimi ancona - i.l.m.a. |  | Impresa lavori marittimi ancona - i.l.m.a. | info@ilmaoffshore.it | +39071201908 |  |  |
| SIC-ID-000000001067 | Impresa Moretti srl |  | Impresa Moretti srl | info@impresamoretti.it | +390532720150 |  |  |
| SIC-ID-000000001068 | Impresa Naso |  | Impresa Naso | vito@impresanaso.it | +393334733623 |  |  |
| SIC-ID-000000001069 | Impresa Pasqual Zemiro Srl |  | Impresa Pasqual Zemiro Srl | info@pasqualzemirosrl.it | +390415470017 |  |  |
| SIC-ID-000000001070 | Imprese Edili baricella impresa edile barletta angelo antonio |  | Imprese Edili baricella impresa edile barletta angelo antonio | redazione@pritalia.it | +39051879183 |  |  |
| SIC-ID-000000001071 | In Quota Srls |  | In Quota Srls | quotasrls@gmail.com | +39800033995 |  |  |
| SIC-ID-000000001072 | Info | Medwork |  | info@medworksas.com | +390498072345 |  |  |
| SIC-ID-000000001073 | Ing | BERTI |  | fabio.berti@poliedron.com | +393384439416 |  |  |
| SIC-ID-000000001074 | Ing | Concetti |  | ing.concetti@gmail.com | +393487838473 |  |  |
| SIC-ID-000000001075 | Ing | Gallerani Enel |  | angelo.gallerani@enel.com | +390418215776 |  |  |
| SIC-ID-000000001076 | Ing | Pregnolato |  | studiopregnolato@gmail.com | +393299262150 |  |  |
| SIC-ID-000000001077 | Ing. Antonino campo & c. costruzioni e impianti snc |  | Ing. Antonino campo & c. costruzioni e impianti snc | prenotazioni@villamargherita.it | +390923921501 |  |  |
| SIC-ID-000000001078 | Ing.G. Lombardi E C. Costruzioni Edilizie Srl |  | Ing.G. Lombardi E C. Costruzioni Edilizie Srl | ufficiotecnico@inglombardi.com | +390815848576 |  |  |
| SIC-ID-000000001079 | Inguscio | Solsonica |  | noisanmichele@gmail.com | +393487919434 |  |  |
| SIC-ID-000000001080 | Innamorati Edilizia SpA |  | Innamorati Edilizia SpA | webserver@staff.aruba.it | +39086202891 |  |  |
| SIC-ID-000000001081 | INNOVAZIONE(IN)EDILIZIA srls |  | INNOVAZIONE(IN)EDILIZIA srls | info@innovazioneinedilizia.com | +390683086915 |  |  |
| SIC-ID-000000001082 | int&ext Ristrutturazioni Costruzioni Restyling |  | int&ext Ristrutturazioni Costruzioni Restyling | intext@tiscali.it | +39800587969 |  |  |
| SIC-ID-000000001083 | Intersonda S.r.l. |  | Intersonda S.r.l. | ufficiocommerciale@intersonda.it | +390583644646 |  |  |
| SIC-ID-000000001084 | Intesa Verde s.r.l. |  | Intesa Verde s.r.l. | info@verdeintesa.it | +393313016546 |  |  |
| SIC-ID-000000001085 | Irene | Bend |  | irene.bend@live.it | +393475525348 |  |  |
| SIC-ID-000000001086 | Isabella | Bellan |  | isabellabella.ib@gmail.com | +393493425240 |  |  |
| SIC-ID-000000001087 | Isabellatammaro | Tammaro |  | isabellatammaro@gmail.com | +393470144114 |  |  |
| SIC-ID-000000001088 | Isof Srl Impresa Costruzioni |  | Isof Srl Impresa Costruzioni | impresaisof@gmail.com | +390704638087 |  |  |
| SIC-ID-000000001089 | ISOLA COSTRUZIONI Srl |  | ISOLA COSTRUZIONI Srl | info@isolaugo.it | +393477721291 |  |  |
| SIC-ID-000000001090 | Italia Costruzioni Impianti srls |  | Italia Costruzioni Impianti srls | italiacostruzionimpiantisrls@gmail.com | +393276162819 |  |  |
| SIC-ID-000000001091 | Ivan | Padovan |  | ivanpadovan78@gmail.com | +393282219280 |  |  |
| SIC-ID-000000001092 | Ivan | Veronese |  | ivan.veronese@alice.it | +3934882394480 |  |  |
| SIC-ID-000000001093 | Ivanb61 |  |  | ivanb61@libero.it | +393293439966 |  |  |
| SIC-ID-000000001094 | Ivanmaso65 |  |  | ivanmaso65@gmail.com | +393356460375 |  |  |
| SIC-ID-000000001095 | Ivano | M56 |  | ivano.m56@libero.it | +393354609730 |  |  |
| SIC-ID-000000001096 | Jacopo | Lyo |  | djjacy@hotmail.it | +393201571385 |  |  |
| SIC-ID-000000001097 | Joia669 |  |  | joia669@hotmail.it | +393482467581 |  |  |
| SIC-ID-000000001098 | Jon | Uriarte |  | preorder@onean.com | +34634555778 |  |  |
| SIC-ID-000000001099 | Jon | Uriarte |  | j.uriarte@aquilasurf.com | +34634555778 |  |  |
| SIC-ID-000000001100 | Jon | Uriarte |  | j.uriarte@onean.com | +34634555778 |  |  |
| SIC-ID-000000001101 | Jonny | Cavallarin |  | jonnycavallarin@hotmail.it | +393277349044 |  |  |
| SIC-ID-000000001102 | Juan_giovanni_1124 |  |  | juan_giovanni_1124@hotmail.com | +393398182865 |  |  |
| SIC-ID-000000001103 | Juxhin | Moretti |  | juxhin@ipervox.com | +393386254956 |  |  |
| SIC-ID-000000001104 | Karrozzeria Vergone S.r.l. |  | Karrozzeria Vergone S.r.l. | info@carrozzeriavergone.com | +390931750611 |  |  |
| SIC-ID-000000001105 | Katiuscia | Vertua |  | katiuscia.vertua@simtree.it | +3934907045240 |  |  |
| SIC-ID-000000001106 | Kevin | Rossante |  | kevinrossante@yahoo.it | +393297795272 |  |  |
| SIC-ID-000000001107 | Khiangtesawma8 |  |  | khiangtesawma8@gmail.com | +3933397802710 |  |  |
| SIC-ID-000000001108 | Monica Ferrari Panetteria |  | L' Essenziale S.a.s. di Ferrari Monica & C. | michela.pozzato@yahoo.it | +393406664199 | Rosolina | RO |
| SIC-ID-000000001109 | L'arte | Del mestiere |  | info@impresafavara.it | +393408237803 |  |  |
| SIC-ID-000000001110 | L'Arte di Abitare - Agenzia di Oriago di Mira |  | L'Arte di Abitare - Agenzia di Oriago di Mira | spinea@artediabitare.it | +39041472423 |  |  |
| SIC-ID-000000001111 | L'Immobile Costruzioni Srl |  | L'Immobile Costruzioni Srl | info@limmobilecostruzioni.it | +390291447880 |  |  |
| SIC-ID-000000001112 | L-angolodelpane |  |  | l-angolodelpane@hotmail.com | +393933370085 |  |  |
| SIC-ID-000000001113 | L.r.a. Ristrutturazioni e costruzioni edili |  | L.r.a. Ristrutturazioni e costruzioni edili | impresaedilelra2014@gmail.com | +393792449733 |  |  |
| SIC-ID-000000001114 | La | Gió |  | soncini.gio@alice.it | +393487761434 |  |  |
| SIC-ID-000000001115 | La | Nuova Gronda |  | privacy@lanuovagronda.com | +39041420914 |  |  |
| SIC-ID-000000001116 | La | Piastrella Italia |  | info@lapiastrellaitalia.it | +390985849125 |  |  |
| SIC-ID-000000001117 | La | RosticceRA |  | enricapezzolato@libero.it | +390426025048 |  |  |
| SIC-ID-000000001118 | LABO+ Studio |  | LABO+ Studio | labomobile.lm@gmail.com | +393389689338 |  |  |
| SIC-ID-000000001119 | Ladoridori |  |  | ladoridori@gmail.com | +393319203849 |  |  |
| SIC-ID-000000001120 | Lago | Federica |  | lago.federica@gmail.com | +393396331241 |  |  |
| SIC-ID-000000001121 | Lamelacoop Rimini |  | Lamelacoop Rimini | lamelacoop@icloud.com | +3937146638710 |  |  |
| SIC-ID-000000001122 | Lamuc | 89 |  | info@lamuc.it | +390639750142 |  |  |
| SIC-ID-000000001123 | Lanari | Maria cristina |  | lanari.maria.cristina@gmail.com | +393407231036 |  |  |
| SIC-ID-000000001124 | Lanza | Ristrutturazioni |  | lanzaristrutturazioni@gmail.com | +393497535347 |  |  |
| SIC-ID-000000001125 | Lara | Mediolanum |  | lara.beltrami@bancamediolanum.it | +393493111902 |  |  |
| SIC-ID-000000001126 | Larasantin | Santin lara |  | larasantin@libero.it | +393391947081 |  |  |
| SIC-ID-000000001127 | Latini Group Italia Srl |  | Latini Group Italia Srl | orjon.nallbati@onlawoffice.com | +390686356060 |  |  |
| SIC-ID-000000001128 | Lattoneria Favaretto Di Favaretto Mattia |  | Lattoneria Favaretto Di Favaretto Mattia | lattoneriafavaretto@gmail.com | +393355323498 |  |  |
| SIC-ID-000000001129 | Lau641 |  |  | lau641@hotmail.it | +393929173084 |  |  |
| SIC-ID-000000001130 | Laura | Danda60 |  | laura.danda60@gmail.com | +393701330772 |  |  |
| SIC-ID-000000001131 | Laura11081968 | Fusetti |  | laura11081968@gmail.com | +393466028580 |  |  |
| SIC-ID-000000001132 | Laurabruni31 |  |  | laurabruni31@gmail.com | +393408125618 |  |  |
| SIC-ID-000000001133 | Lauralbb |  |  | lauralbb@hotmail.it | +393496043079 |  |  |
| SIC-ID-000000001134 | Lauramarcucci | Rogi cucine |  | lauramarcucci@tiscali.it | +393484940968 |  |  |
| SIC-ID-000000001135 | Lauravascellari |  |  | lauravascellari@virgilio.it | +393494294790 |  |  |
| SIC-ID-000000001136 | Lavori E costruzioni s.r.l. |  | Lavori E costruzioni s.r.l. | info@lavoriecostruzioni.it | +39092426699 |  |  |
| SIC-ID-000000001137 | Lavori Edili a bologna / gs ristrutturazioni |  | Lavori Edili a bologna / gs ristrutturazioni | info@fashion-web.it | +393482723774 |  |  |
| SIC-ID-000000001138 | Pullara Filippo |  | LAVORI EDILI SAS DI PULLARA FILIPPO & C. | pullara.filippo@libero.it | +393475357617 | Porto Viro | RO |
| SIC-ID-000000001139 | Paolo_lazzarin Falegname |  | LAZZARIN COSTRUZIONI S.R.L. | paolo_lazzarin@libero.it | +3934773216460 | ROSOLINA | RO |
| SIC-ID-000000001140 | Lci Isolanti Termici - Coibentazioni |  | Lci Isolanti Termici - Coibentazioni | info@lci.it | +390415630858 |  |  |
| SIC-ID-000000001141 | Lcipriani1071 |  |  | lcipriani1071@gmail.com | +393463871785 |  |  |
| SIC-ID-000000001142 | Le | Decorazioni - Prati |  | monicaborghese@ledecorazioni.com | +390660668395 |  |  |
| SIC-ID-000000001143 | Le.il. Costruzioni s.r.l. |  | Le.il. Costruzioni s.r.l. | info@leilgroup.it | +390918781438 |  |  |
| SIC-ID-000000001144 | Lemmi & C. Sede di Pistoia |  | Lemmi & C. Sede di Pistoia | info@dittalemmi.com | +3905731794724 |  |  |
| SIC-ID-000000001145 | Lenkam |  |  | lenkam@tin.it | +3938597465860 |  |  |
| SIC-ID-000000001146 | Leofer di Leonardi Salvatore - Lavorazioni in ferro / Paesi Etnei / Catania |  | Leofer di Leonardi Salvatore - Lavorazioni in ferro / Paesi Etnei / Catania | info.leofer@gmail.com | +393407245580 |  |  |
| SIC-ID-000000001147 | Leonardo | Bonato |  | leonardo.bonato@tin.it | +3934919904070 |  |  |
| SIC-ID-000000001148 | Leonardo | Fibbiani |  | leonardo.fibbiani@gmail.com | +393485204990 |  |  |
| SIC-ID-000000001149 | Leonardo | Lo Cicero |  | artisti.leonardo@studiopozzato.it | +393314390562 |  |  |
| SIC-ID-000000001150 | Leone |  |  | leone@zannovello.it | +393421688889 |  |  |
| SIC-ID-000000001151 | Leroy | Merlin |  | silvia.cencelli@leroymerlin.it | +390410992630 |  |  |
| SIC-ID-000000001152 | Lessinia Costruzioni Srl |  | Lessinia Costruzioni Srl | lessiniacostruzioni@gmail.com | +390456500321 |  |  |
| SIC-ID-000000001153 | Letizia | RICARICA |  | graficarima@gmail.com | +393924879543 |  |  |
| SIC-ID-000000001154 | Lg edilizia srl |  | Lg edilizia srl | lgripasrl@gmail.com | +39073597230 |  |  |
| SIC-ID-000000001155 | Liliana | Lattuada |  | liliana.lattuada@fastwebnet.it | +3934964838670 |  |  |
| SIC-ID-000000001156 | Linda | Forenza |  | linda.forenza@gmail.com | +393886380383 |  |  |
| SIC-ID-000000001157 | Lmc | Infrastrutture Stradali |  | info@lmc-infrastrutturestradali.it | +390957040688 |  |  |
| SIC-ID-000000001158 | Lombardi Franco Srl |  | Lombardi Franco Srl | info@segheria-lombardi.it | +390465621159 |  |  |
| SIC-ID-000000001159 | Lomonacostruzioni Srl |  | Lomonacostruzioni Srl | info@lomonacostruzioni.com | +393392149317 |  |  |
| SIC-ID-000000001160 | Longhin Costruzioni edili s.r.l. |  | Longhin Costruzioni edili s.r.l. | info@longhincostruzioniedili.it | +393357034596 |  |  |
| SIC-ID-000000001161 | Loredana | Elite |  | loredana@molinofavero.com | +3934744832150 |  |  |
| SIC-ID-000000001162 | Lorenza | Bego |  | lorenza.bego@libero.it | +393204643357 |  |  |
| SIC-ID-000000001163 | Lorenzo | Lyo |  | motomar@motomar.it | +39456400888 |  |  |
| SIC-ID-000000001164 | Lorenzolivestage |  |  | lorenzolivestage@iol.it | +393289354511 |  |  |
| SIC-ID-000000001165 | Lorenzoni | Giuliano |  | timoty79@libero.it | +393356742283 |  |  |
| SIC-ID-000000001166 | Lovato Giuseppe Srl |  | Lovato Giuseppe Srl | preventivi@lovatolegnami.it | +390458347977 |  |  |
| SIC-ID-000000001167 | Luca | CERVED |  | luca.nencioni@cerved.com | +390587298454 |  |  |
| SIC-ID-000000001168 | Luca | Ferraro DAN Costiera |  | luca.ferraro1978@gmail.com | +393290091910 |  |  |
| SIC-ID-000000001169 | Luca Ravaioli impresa edile ravenna |  | Luca Ravaioli impresa edile ravenna | nome@email.it | +393280878572 |  |  |
| SIC-ID-000000001170 | Luccasfalti - sede e magazzino |  | Luccasfalti - sede e magazzino | info@luccasfalti.it | +393407437539 |  |  |
| SIC-ID-000000001171 | Luciafavilli |  |  | luciafavilli@tiscali.it | +393388833989 |  |  |
| SIC-ID-000000001172 | Luciafune2807 |  |  | luciafune2807@gmail.com | +393281214929 |  |  |
| SIC-ID-000000001173 | Lucialocatelli01 | President |  | lucialocatelli01@gmail.com | +393487944934 |  |  |
| SIC-ID-000000001174 | Lucianoangelini64 |  |  | lucianoangelini64@alice.it | +393474219379 |  |  |
| SIC-ID-000000001175 | Lucio | Licata |  | lucio.licata@live.it | +393287374019 |  |  |
| SIC-ID-000000001176 | Lucio Tessarin SERVICE |  | Lucio Tessarin SERVICE | info@sldservice.it | +393287374019 |  |  |
| SIC-ID-000000001177 | Lucrezio | Giovanni |  | lucrezio.giovanni@agecreditsrl.it | +393479699253 |  |  |
| SIC-ID-000000001178 | Luigi | Ferraiuolo2004 |  | luigi.ferraiuolo2004@libero.it | +393459100635 |  |  |
| SIC-ID-000000001179 | Luigi | Salvatore |  | luigi.salvatore@gtssrl.eu | +393384389864 |  |  |
| SIC-ID-000000001180 | Luigi | Sicurezza Salzano |  | luigi.salzano.76@gmail.com | +393385965308 |  |  |
| SIC-ID-000000001181 | Luigiesposito_1976 |  |  | luigiesposito_1976@libero.it | +393471217898 |  |  |
| SIC-ID-000000001182 | Luisas21 |  | Luisas21 | luisas21@hotmail.it | +393894360041 |  |  |
| SIC-ID-000000001183 | Lunadei | Ristrutturazioni |  | info@lunadeiristrutturazioni.it | +39065416284 |  |  |
| SIC-ID-000000001184 | Luxury | Windows Italia |  | gibus@luxurywindowsitalia.com | +39065411815 |  |  |
| SIC-ID-000000001185 | M.D. Costruzioni S.R.L. |  | M.D. Costruzioni S.R.L. | m.d.costruzionisrls@legalmail.it | +393920044415 |  |  |
| SIC-ID-000000001186 | MA.C. S.r.l |  | MA.C. S.r.l | privacy@mac-edilizia.it | +390755280928 |  |  |
| SIC-ID-000000001187 | MA.GHI Impresa Edile di Geom. Matteo Ghirardi - Ristrutturazione Appartamenti a Bergamo e Milano |  | MA.GHI Impresa Edile di Geom. Matteo Ghirardi - Ristrutturazione Appartamenti a Bergamo e Milano | info@impresamaghi.it | +393429487019 |  |  |
| SIC-ID-000000001188 | Macelleriadacarlogenova |  |  | macelleriadacarlogenova@gmail.com | +39395837530 |  |  |
| SIC-ID-000000001189 | Mae.S. |  |  | maes.laiena@libero.it | +390817597328 |  |  |
| SIC-ID-000000001190 | Magazzini Edili Tontine Srl |  | Magazzini Edili Tontine Srl | tontine.edile@gmail.com | +390172413791 |  |  |
| SIC-ID-000000001191 | Maggio | Roberto |  | maggio.roberto@hotmail.it | +393467327965 |  |  |
| SIC-ID-000000001192 | Magnani Edilizia SRL Varese |  | Magnani Edilizia SRL Varese | magnaniedilizia@cert.postecert.it | +390332462418 |  |  |
| SIC-ID-000000001193 | Maldini | Gabriele Enel Ferrara |  | gabriele.maldini@enel.it | +393292406126 |  |  |
| SIC-ID-000000001194 | Maltese S.r.l. |  | Maltese S.r.l. | info@maltesesrl.it | +39092426433 |  |  |
| SIC-ID-000000001195 | Mamigiro |  |  | mamigiro@gmail.com | +393664365547 |  |  |
| SIC-ID-000000001196 | Mandolesi |  |  | mand72@libero.it | +390734628117 |  |  |
| SIC-ID-000000001197 | Manfrin | Manuele |  | manfrin.m@libero.it | +3933827746050 |  |  |
| SIC-ID-000000001198 | Mantovan | Cristian |  | chrismaitai1981@gmail.com | +393408310242 |  |  |
| SIC-ID-000000001199 | Manuela | Moscardi |  | manuela.moscardi@mm-forgings.com | +3933880779460 |  |  |
| SIC-ID-000000001200 | Manuela08111976 |  |  | manuela08111976@gmail.com | +393457811660 |  |  |
| SIC-ID-000000001201 | Marangoninicoletta | Roberto |  | marangoninicoletta@gmail.com | +393357107570 |  |  |
| SIC-ID-000000001202 | Marangonmelissa |  |  | marangonmelissa@gmail.com | +393801050159 |  |  |
| SIC-ID-000000001203 | Marco | Lanza |  | lanzapesca@libero.it | +393472448654 |  |  |
| SIC-ID-000000001204 | Marco | Ferro |  | m.ferro@eco-engineering.it | +393292999142 |  |  |
| SIC-ID-000000001205 | Maremmana Edilizia srl |  | Maremmana Edilizia srl | maremmanaedilizia@libero.it | +393483709391 |  |  |
| SIC-ID-000000001206 | Marghy | Cinque |  | marghy.cinque@tiscali.it | +393479571941 |  |  |
| SIC-ID-000000001207 | Maria | Faccin |  | m.faccin@progettorisparmioenergetico.it | +393389808126 |  |  |
| SIC-ID-000000001208 | Mariagrazia15 |  |  | mariagrazia15@live.it | +3934817578000 |  |  |
| SIC-ID-000000001209 | Mariah70 |  |  | mariah70@alice.it | +393284222852 |  |  |
| SIC-ID-000000001210 | Mariangela | Spolaor |  | mariangela.spolaor@confve.it | +3934633365250 |  |  |
| SIC-ID-000000001211 | Mariangela | Voltarel |  | mariangela.voltarel@alice.it | +3933575258210 |  |  |
| SIC-ID-000000001212 | Marianna | Bellini |  | marianna.bellini@fazland.com | +393518290377 |  |  |
| SIC-ID-000000001213 | Mariella Srl |  | Mariella Srl | mariella@maxilia.it | +393406322038 |  |  |
| SIC-ID-000000001214 | Marina | Bonati64 |  | marina.bonati64@gmail.com | +393464360158 |  |  |
| SIC-ID-000000001215 | Marina | Venegoni |  | marina.venegoni@libero.it | +3934738299810 |  |  |
| SIC-ID-000000001216 | Marinelli paolo & c. s.r.l. - materiali edili - ferramenta |  | Marinelli paolo & c. s.r.l. - materiali edili - ferramenta | info@toolmaster.it | +390717570366 |  |  |
| SIC-ID-000000001217 | Marinobaldassarri |  |  | marinobaldassarri@hotmail.com | +393314940595 |  |  |
| SIC-ID-000000001218 | Mario | Bragagnolo |  | mario.bragagnolo@bmautomazioni.com | +393487809877 |  |  |
| SIC-ID-000000001219 | Mario | Carroccio80 |  | mario.carroccio80@gmail.com | +393477321646 |  |  |
| SIC-ID-000000001220 | Mariolinag62 |  |  | mariolinag62@gmail.com | +3932017451110 |  |  |
| SIC-ID-000000001221 | Marssignals | Sergio |  | marssignals@gmail.com | +393292746457 |  |  |
| SIC-ID-000000001222 | Martina | Penzo |  | martina.penzo@euroports.it | +3933553895410 |  |  |
| SIC-ID-000000001223 | Martina | Speri |  | martina.speri@sacrocuore.it | +393345851704 |  |  |
| SIC-ID-000000001224 | Martina_tempesta |  |  | martina_tempesta@libero.it | +393463214955 |  |  |
| SIC-ID-000000001225 | Martinello | Dani22 |  | martinello.dani22@gmail.com | +393933320092 |  |  |
| SIC-ID-000000001226 | Martini Pio mario sas impresa edile di martini pio mario & c. |  | Martini Pio mario sas impresa edile di martini pio mario & c. | info@alifax.com | +39049604545 |  |  |
| SIC-ID-000000001227 | Martino | Gerardo |  | martino.gerardo@myefm.it | +393486067770 |  |  |
| SIC-ID-000000001228 | Marzia | Cappello |  | marzia.cappello@gmail.com | +3934063220380 |  |  |
| SIC-ID-000000001229 | Marzio | Sisti |  | marzio.sisti@yahoo.it | +3933334888260 |  |  |
| SIC-ID-000000001230 | Mascia | Annam |  | mascia.annam@gmail.com | +3932928033800 |  |  |
| SIC-ID-000000001231 | Masi Ristrutturazioni s.r.l. |  | Masi Ristrutturazioni s.r.l. | info@masiristrutturazioni.com | +390515060592 |  |  |
| SIC-ID-000000001232 | Massimiliano | Michelotto |  | massimiliano.michelotto@stema.it | +393392775383 |  |  |
| SIC-ID-000000001233 | Massimilianolaurenti | Luca |  | massimilianolaurenti@tin.it | +3934091329060 |  |  |
| SIC-ID-000000001234 | Massimilianomunari |  |  | massimilianomunari@libero.it | +393791863833 |  |  |
| SIC-ID-000000001235 | Massimilianonocehd | Noce |  | massimilianonocehd@gmail.com | +393357583788 |  |  |
| SIC-ID-000000001236 | Massimo |  |  | massimo@bieffemontaggi.it | +393494758933 |  |  |
| SIC-ID-000000001237 | Massimo | Boscolo Vengest |  | massimo.boscolo@gruppovengest.it | +3933479100550 |  |  |
| SIC-ID-000000001238 | Massimo | Lyo |  | degattimassimo@gmail.com | +393888719533 |  |  |
| SIC-ID-000000001239 | Massimo | Lyoness |  | dealer64@gmail.com | +393494758933 |  |  |
| SIC-ID-000000001240 | Massimo | Nuvoletto |  | massimo@puntaadige.it | +3933576275720 |  |  |
| SIC-ID-000000001241 | Massimo cooperativa |  | Massimo cooperativa | massimo@vivoilmare.it | +393479970899 |  |  |
| SIC-ID-000000001242 | Massimo Srl |  | Massimo Srl | massimo.baccaglini@ronconiauto.it | +393289134522 |  |  |
| SIC-ID-000000001243 | Master Costruzioni srls |  | Master Costruzioni srls | info@costruzionimastergroup.it | +393491466989 |  |  |
| SIC-ID-000000001244 | Materesabertuccelli |  |  | materesabertuccelli@teletu.it | +3934029744290 |  |  |
| SIC-ID-000000001245 | Materiale per edilizia bergamo - Forniture Edili |  | Materiale per edilizia bergamo - Forniture Edili | info@fornitureedili.com | +39035784815 |  |  |
| SIC-ID-000000001246 | Materiali | edili c.i.m.m.e. |  | cimmesrl@libero.it | +390734937077 |  |  |
| SIC-ID-000000001247 | Materiali edili val sabbia - Castelnuovo Carletto |  | Materiali edili val sabbia - Castelnuovo Carletto | castelnuovocarletto@virgilio.it | +390365824204 |  |  |
| SIC-ID-000000001248 | Matis010475 | Automotive varagnolo |  | matis010475@gmail.com | +3934758232130 |  |  |
| SIC-ID-000000001249 | Mattbon55 |  |  | mattbon55@gmail.com | +393357372586 |  |  |
| SIC-ID-000000001250 | Matteo |  |  | matteo@easysub.it | +393406326453 |  |  |
| SIC-ID-000000001251 | Matteo | Aeffe |  | matteo.aeffe@gmail.com | +393478949312 |  |  |
| SIC-ID-000000001252 | Matteo | Com |  | matteo.com@live.com | +393472120843 |  |  |
| SIC-ID-000000001253 | Matteo | Debattisti |  | matteo.debattisti@gmail.com | +3934652476800 |  |  |
| SIC-ID-000000001254 | Matteo | Ferrari |  | matteo.ferrari@lalineaverde.it | +3933830688850 |  |  |
| SIC-ID-000000001255 | Matteo | Lyo |  | matteopurisiol@yahoo.it | +393485579585 |  |  |
| SIC-ID-000000001256 | Matteo | Melchiotti |  | matteo.melchiotti.1980@gmail.com | +3932797829380 |  |  |
| SIC-ID-000000001257 | Matteo | Moro |  | info@2mlighting.it | +393400838294 |  |  |
| SIC-ID-000000001258 | Matteo | ONEAN |  | mattebreschi@yahoo.it | +393497865638 |  |  |
| SIC-ID-000000001259 | Matteo | Pagan |  | pagan.matteo@virgilio.it | +3934755598370 |  |  |
| SIC-ID-000000001260 | Mattia | Arco |  | mattia.arco@libero.it | +393355316847 |  |  |
| SIC-ID-000000001261 | Mattia | Chiodoro |  | mattia.boscolo@hotmail.it | +393466919569 |  |  |
| SIC-ID-000000001262 | Mattia | Gardenghi |  | mattia.gardenghi@gmail.com | +393391956291 |  |  |
| SIC-ID-000000001263 | Mattia | Lyo |  | mattiafox89@gmail.com | +393295365912 |  |  |
| SIC-ID-000000001264 | Mauiscrizioni |  |  | mauiscrizioni@libero.it | +393482718252 |  |  |
| SIC-ID-000000001265 | Maurizio |  |  | maurizio@greentechservice.it | +3934824030800 |  |  |
| SIC-ID-000000001266 | Maurizio | Drago |  | maurizio.drago@artigianatopadovano.it | +3937718892900 |  |  |
| SIC-ID-000000001267 | Maurizio | Estintori |  | direzione@vm-antincendi.it | +393487446629 |  |  |
| SIC-ID-000000001268 | Maurizio Cognome email telefono id cliente backup_sicu |  | Maurizio Cognome email telefono id cliente backup_sicu | maurizio.ulano@artigianfidi.pd.it | +3934869286730 |  |  |
| SIC-ID-000000001269 | Mauro | Cecchinato |  | mauro.cecchinato@email.it | +3932003141390 |  |  |
| SIC-ID-000000001270 | Mauro GEOM. CTU |  | Mauro GEOM. CTU | mauroca@libero.it | +393388833989 |  |  |
| SIC-ID-000000001271 | Maurobeggio |  |  | maurobeggio@hotmail.com | +393929183275 |  |  |
| SIC-ID-000000001272 | Mauroboscolo1 |  |  | mauroboscolo1@gmail.com | +393402109776 |  |  |
| SIC-ID-000000001273 | Mauropicky |  |  | mauropicky@gmail.com | +393389867717 |  |  |
| SIC-ID-000000001274 | Maurovf58 | Benedetti |  | maurovf58@gmail.com | +393284765250 |  |  |
| SIC-ID-000000001275 | Maxam Costruzioni Srl |  | Maxam Costruzioni Srl | info@maxamcostruzioni.it | +39063216528 |  |  |
| SIC-ID-000000001276 | Mbadescu82 | Badescu goat |  | mbadescu82@gmail.com | +3938993507150 |  |  |
| SIC-ID-000000001277 | Mbonato |  |  | mbonato@me.com | +393405583664 |  |  |
| SIC-ID-000000001278 | Mc coperture di chiappetti manuel |  | Mc coperture di chiappetti manuel | info@mccoperture.it | +393347820166 |  |  |
| SIC-ID-000000001279 | MC Restauri S.r.l. |  |  | mcrestauri@cert.cna.it | +390639723846 |  |  |
| SIC-ID-000000001280 | McEdil S.r.l.s. Costruzioni e Impianti |  | McEdil S.r.l.s. Costruzioni e Impianti | info@mcedil.it | +393801032737 |  |  |
| SIC-ID-000000001281 | Md Serramenti |  | Md Serramenti | md.serramenti@libero.it | +390426322508 |  |  |
| SIC-ID-000000001282 | Mecstore - Edilfer |  | Mecstore - Edilfer | efb.mecstoreedilfer@gmail.com | +393311333738 |  |  |
| SIC-ID-000000001283 | Mega | Magazzini Edili |  | pier@megasrl.it | +39017286404 |  |  |
| SIC-ID-000000001284 | Mega | Restauri |  | info@megarestauri.it | +390622428363 |  |  |
| SIC-ID-000000001285 | Meganleva19 | Pf treviso |  | meganleva19@gmail.com | +393487476318 |  |  |
| SIC-ID-000000001286 | Melanywindm |  |  | melanywind83m@libero.it | +393356742283 |  |  |
| SIC-ID-000000001287 | Melissa_mesc |  |  | melissa_mesc@yahoo.com | +393475915630 |  |  |
| SIC-ID-000000001288 | Meneghini | Giovanni |  | info@meneghinigiovannisrl.it | +39045942264 |  |  |
| SIC-ID-000000001289 | Mentucci aldo srl |  | Mentucci aldo srl | info@mentuccialdo.it | +390717921151 |  |  |
| SIC-ID-000000001290 | Mesoraca Srl/ impresa edile per costruzione e ristrutturazioni di edifici residenziali e industriali/ bologna/ zola predosa |  | Mesoraca Srl/ impresa edile per costruzione e ristrutturazioni di edifici residenziali e industriali/ bologna/ zola predosa | info@mesoracasrl.com | +39051753611 |  |  |
| SIC-ID-000000001291 | MEV | Materiali Edili Varese |  | info@mevcasa.com | +390332820598 |  |  |
| SIC-ID-000000001292 | Mgm Costruzioni s.r.l. / impresa edile a bologna |  | Mgm Costruzioni s.r.l. / impresa edile a bologna | info@mgmcostruzioni.it | +393755646294 |  |  |
| SIC-ID-000000001293 | MGR Costruzioni Edili |  | MGR Costruzioni Edili | mgrcostruzioniedili@gmail.com | +393403469284 |  |  |
| SIC-ID-000000001294 | Micaela | Colleoni82 |  | micaela.colleoni82@gmail.com | +3934097140780 |  |  |
| SIC-ID-000000001295 | Michela_ferlin |  |  | michela_ferlin@yahoo.it | +393402439855 |  |  |
| SIC-ID-000000001296 | Michela_tassotti | Stoppa |  | michela_tassotti@yahoo.it | +3934795719410 |  |  |
| SIC-ID-000000001297 | Michele |  |  | michele@fivesuperiorstars.com | +393355372248 |  |  |
| SIC-ID-000000001298 | Michele | Catamero |  | michele.catamero@gmail.com | +3933314920070 |  |  |
| SIC-ID-000000001299 | Michele | Doris |  | michele.doris@antonianaemergenza.it | +393491352406 |  |  |
| SIC-ID-000000001300 | Michele | Gennari |  | zapjoldon@gmail.com | +393454631574 |  |  |
| SIC-ID-000000001301 | Michele | Pezzolato |  | michelepezzolato@gmail.com | +393485622861 |  |  |
| SIC-ID-000000001302 | Michele | Sandrin |  | sm85@libero.it | +393475501579 |  |  |
| SIC-ID-000000001303 | Michele | Siviero |  | michele.siviero@libero.it | +3934209841810 |  |  |
| SIC-ID-000000001304 | Michele | Tarroni |  | michele@cpmshop.it | +393331894558 |  |  |
| SIC-ID-000000001305 | michele | assistedil |  | cocchieri@assistedil.it | +393401804545 |  |  |
| SIC-ID-000000001306 | Michele | Lyo |  | 222giraffa@gmail.com | +393477887785 |  |  |
| SIC-ID-000000001307 | Michelegasparini46 |  | Michelegasparini46 | michelegasparini46@gmail.com | +393293096073 |  |  |
| SIC-ID-000000001308 | Micheletoffa | Ghezzo |  | micheletoffa@libero.it | +393462123847 |  |  |
| SIC-ID-000000001309 | Miki | Soncin |  | info@chioggia-immobiliare.it | +393286381230 |  |  |
| SIC-ID-000000001310 | Milan | davide |  | milan.geometra@libero.it | +393391556172 |  |  |
| SIC-ID-000000001311 | Milani | Alessia Café |  | milanialessia2@gmail.com | +393403961349 |  |  |
| SIC-ID-000000001312 | Milena | Mimmo |  | milena.mimmo@gmail.com | +393474918173 |  |  |
| SIC-ID-000000001313 | Milena | Saiani |  | milenasaiani@alice.it | +393491290681 |  |  |
| SIC-ID-000000001314 | Milena | Saletta |  | milena.saletta@gmail.com | +3933512954320 |  |  |
| SIC-ID-000000001315 | Millesaporisnc Hair |  | Millesaporisnc Hair | millesaporisnc@libero.it | +393883869351 |  |  |
| SIC-ID-000000001316 | Mirco |  |  | mirco@porzionatoassociati.it | +393496781084 |  |  |
| SIC-ID-000000001317 | Mirco | Lyo |  | mircofaggia@gmail.com | +393933324093 |  |  |
| SIC-ID-000000001318 | Mirco Gasparotto |  | Mirco Gasparotto | mirco.gasparotto@arroweld.com | +393482730070 |  |  |
| SIC-ID-000000001319 | Mirko | Cogo |  | mirko.cogo@virgilio.it | +393292409684 |  |  |
| SIC-ID-000000001320 | Mister | Trevi |  | mister.trevi@libero.it | +393338792862 |  |  |
| SIC-ID-000000001321 | Mmauro0267 |  |  | mmauro0267@gmail.com | +393371206615 |  |  |
| SIC-ID-000000001322 | Mobilia Costruzioni srl |  | Mobilia Costruzioni srl | mobiliacostruzionisrl@gmail.com | +393533199750 |  |  |
| SIC-ID-000000001323 | Moduspm Edile |  | Moduspm Edile | moduspm@gmail.com | +3933837163870 |  |  |
| SIC-ID-000000001324 | Molinella Costruzioni s.r.l. |  | Molinella Costruzioni s.r.l. | info@molinellacostruzioni.it | +39051881350 |  |  |
| SIC-ID-000000001325 | Molino | Pordenone |  | welcome@molinopn.com | +390434362421 |  |  |
| SIC-ID-000000001326 | Monaci | F.lli |  | info@monaciedilizia.it | +39034571407 |  |  |
| SIC-ID-000000001327 | Mondo Infissi / Installazione Infissi e Serramenti |  | Mondo Infissi / Installazione Infissi e Serramenti | info@mondo-infissi.it | +390187992101 |  |  |
| SIC-ID-000000001328 | Monica | Carlin80 |  | monica.carlin80@gmail.com | +393290963289 |  |  |
| SIC-ID-000000001329 | Monica | Duo82 |  | monica.duo82@icloud.com | +3938943600410 |  |  |
| SIC-ID-000000001330 | Monica | Pavan |  | monica.pavan@libero.it | +393458416346 |  |  |
| SIC-ID-000000001331 | Monicabaioni32 |  |  | monicabaioni32@gmail.com | +393406664199 |  |  |
| SIC-ID-000000001332 | Montepescali |  |  | v.bloise@trenitalia.it | +390595454760 |  |  |
| SIC-ID-000000001333 | Moregolaluciano |  |  | moregolaluciano@libero.it | +393494214877 |  |  |
| SIC-ID-000000001334 | Morello | Stefania |  | stefania.morello@venetica.org | +393929880922 |  |  |
| SIC-ID-000000001335 | Morena | Buzzoni |  | morena.buzzoni@virgilio.it | +393200854219 |  |  |
| SIC-ID-000000001336 | MORENO | MARANGON |  | moreno@myradiostore.it | +393939012962 |  |  |
| SIC-ID-000000001337 | Moreno | Menegazzo |  | moreno.menegazzo@gmail.com | +393393919397 |  |  |
| SIC-ID-000000001338 | Moretti Centro Edile |  | Moretti Centro Edile | moretticentroedile@gmail.com | +390803743768 |  |  |
| SIC-ID-000000001339 | Morigi Impresa edile |  | Morigi Impresa edile | info@impresaedilemorigi.it | +3905441823618 |  |  |
| SIC-ID-000000001340 | Mosnamariano | Radio fibra |  | mosnamariano@gmail.com | +393896903705 |  |  |
| SIC-ID-000000001341 | Mosè | Dr Destefani |  | m.destefani@bipiemme.it | +390425070622 |  |  |
| SIC-ID-000000001342 | Mottamarta69 |  |  | mottamarta69@gmail.com | +393338664500 |  |  |
| SIC-ID-000000001343 | Movimac S.r.l. |  | Movimac S.r.l. | info@movi-mac.it | +390817594403 |  |  |
| SIC-ID-000000001344 | MQ COSTRUZIONI GENERALI |  | MQ COSTRUZIONI GENERALI | utente@dominio.com | +393517280097 |  |  |
| SIC-ID-000000001345 | MSA s.r.l. |  | MSA s.r.l. | info@msappalti.it | +390817599023 |  |  |
| SIC-ID-000000001346 | Mtelettricsnc |  | Mtelettricsnc | mtelettricsnc@alice.it | +393400838294 |  |  |
| SIC-ID-000000001347 | Mulinari Costruzioni generali srl |  | Mulinari Costruzioni generali srl | info@mulinaricostruzioni.it | +39054561013 |  |  |
| SIC-ID-000000001348 | Multiservices Leone s.r.l.s. |  | Multiservices Leone s.r.l.s. | info@multiservicesleone.com | +393342740988 |  |  |
| SIC-ID-000000001349 | Murri - Edilizia |  | Murri - Edilizia | info@murri-cisam.it | +390630810709 |  |  |
| SIC-ID-000000001350 | Mussatigiacomo | Lyo |  | mussatigiacomo@gmail.com | +3934799708990 |  |  |
| SIC-ID-000000001351 | Mvr Artedile srl impresa edile e opere in cartongesso |  | Mvr Artedile srl impresa edile e opere in cartongesso | ufficio.mvrartedile.srl@gmail.com | +390522390311 |  |  |
| SIC-ID-000000001352 | N.T.C. Costruzioni Generali S.R.L. |  | N.T.C. Costruzioni Generali S.R.L. | info@ntccostruzionigenerali.com | +39070882419 |  |  |
| SIC-ID-000000001353 | Nadia | Coratti |  | nadia.coratti@libero.it | +393480996070 |  |  |
| SIC-ID-000000001354 | Nadia | Saccardin |  | nadiasacc@gmail.com | +3933514339960 |  |  |
| SIC-ID-000000001355 | Nalin | Gessica |  | nalin.gessica@libero.it | +393486067770 |  |  |
| SIC-ID-000000001356 | Nalon Srl |  | Nalon Srl | mira@nalon.it | +390415676540 |  |  |
| SIC-ID-000000001357 | Nautica | Antonello |  | info@nauticaantonello.com | +390423720355 |  |  |
| SIC-ID-000000001358 | Nautica | Antonello |  | info@antonellosport.com | +390423720355 |  |  |
| SIC-ID-000000001359 | Nd Edilizia e ristrutturazioni srls |  | Nd Edilizia e ristrutturazioni srls | info@ndediliziaeristrutturazioni.it | +393498334967 |  |  |
| SIC-ID-000000001360 | Negrello | Giancarlo Spisal Rovigo |  | negrello.giancarlo@azisanrovigo.it | +393497952739 |  |  |
| SIC-ID-000000001361 | New | Edil cupi |  | web@e21.it | +393206238643 |  |  |
| SIC-ID-000000001362 | New | Line Mantovan Stefano |  | jmj69nola@gmail.com | +393291558661 |  |  |
| SIC-ID-000000001363 | New Style Gomme Adria Ivano |  | NEW STYLE GOMME S.a.s. | newstylegommesas@gmail.com | +393406657691 | Adria | RO |
| SIC-ID-000000001364 | New Style Gomme Rovigo |  | NEW STYLE GOMME S.r.l. | newstylegomme@libero.it | +390425475487 | Rovigo | RO |
| SIC-ID-000000001365 | Nico | Pregnolato |  | nico.pregnolato@gmail.com | +3933941849110 |  |  |
| SIC-ID-000000001366 | Nico | Vettore |  | nico.vettore@hotmail.it | +3934907817290 |  |  |
| SIC-ID-000000001367 | Nicola |  |  | b-nicola@libero.it | +393346900946 |  |  |
| SIC-ID-000000001368 | Nicola |  |  | nicola@soladria.it | +3934848503610 |  |  |
| SIC-ID-000000001369 | Nicola | B |  | nicola.b@tisoalfredo.it | +393494445997 |  |  |
| SIC-ID-000000001370 | Nicola | Domini |  | level8@me.com | +393472222690 |  |  |
| SIC-ID-000000001371 | Nicola | Donà |  | nico.dona@gmail.com | +393355376755 |  |  |
| SIC-ID-000000001372 | Nicola | Fusetti |  | nicola.fusetti@email.it | +393295361604 |  |  |
| SIC-ID-000000001373 | Nicola | Paparella |  | servizioclienti@bipiemme.it | +393381018716 |  |  |
| SIC-ID-000000001374 | Nicola | Pessot |  | nicola.pessot@gmail.com | +393333844282 |  |  |
| SIC-ID-000000001375 | Nicola | Sanfelici |  | rentservice.energia@libero.it | +3934960872020 |  |  |
| SIC-ID-000000001376 | Nicola | Svaizer |  | nicola.svaizer@jlbbooks.it | +393394700867 |  |  |
| SIC-ID-000000001377 | Nicola | Zambon |  | nic@avvzambon.com | +393396116999 |  |  |
| SIC-ID-000000001378 | Nicole | Passarella |  | nicole.passarella@outlook.it | +393396215395 |  |  |
| SIC-ID-000000001379 | Nicolo | Bortolato |  | nicolo.bortolato@gmail.com | +3934076573280 |  |  |
| SIC-ID-000000001380 | Ninakate89 |  |  | ninakate89@gmail.com | +393492747757 |  |  |
| SIC-ID-000000001381 | Nino | Anto |  | nino.anto@live.it | +393933368070 |  |  |
| SIC-ID-000000001382 | Nino | San Filippo |  | nino.sanfilippo@gmail.com | +39335360120 |  |  |
| SIC-ID-000000001383 | NIO | Cocktails |  | shop@nio-cocktails.com | +393457681262 |  |  |
| SIC-ID-000000001384 | Nipab | - Showroom |  | info@nipab.it | +390290390034 |  |  |
| SIC-ID-000000001385 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | alessandra.dc78@gmail.com | +393334152106 |  |  |
| SIC-ID-000000001386 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | bv.archh@bortolasovantini.it | +393480717912 |  |  |
| SIC-ID-000000001387 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | clavomatic@gmail.com | +393286668466 |  |  |
| SIC-ID-000000001388 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | francalarosa@tiscali.it | +393937764394 |  |  |
| SIC-ID-000000001389 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | elenacaiazzo78@live.it | +393899555847 |  |  |
| SIC-ID-000000001390 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | 59antonioromano@gmail.com | +393346548933 |  |  |
| SIC-ID-000000001391 | Notaio | Arnone |  | l.arnone@libero.it | +390426634155 |  |  |
| SIC-ID-000000001392 | Notaio | Cocito |  | gcocito@notariato.it | +39042622282 |  |  |
| SIC-ID-000000001393 | Nova Costruzioni |  | Nova Costruzioni | info@nuovacostruzionieimpianti.com | +390421312428 |  |  |
| SIC-ID-000000001394 | Novamobili / Veneta Cucine - Fattorini Roma Centro |  | Novamobili / Veneta Cucine - Fattorini Roma Centro | info@fattorinidesign.com | +390668136615 |  |  |
| SIC-ID-000000001395 | Novello | Gabriele |  | info@novelloponteggi.com | +39041479111 |  |  |
| SIC-ID-000000001396 | Nuova Costruzioni Generali srl |  | Nuova Costruzioni Generali srl | studioaprea@newpowergreen.it | +39063700116 |  |  |
| SIC-ID-000000001397 | Nuova edilizia |  | Nuova edilizia | commerciale@nuovaediliziadue.it | +390734932958 |  |  |
| SIC-ID-000000001398 | Nuova Errepi Modena |  | Nuova Errepi S.n.c. | info@nuovaerrepi.it | +39059671371 | Novi di Modena | MO |
| SIC-ID-000000001399 | Nuova Logistica Lucianu Srl |  | Nuova Logistica Lucianu Srl | ordini.penisola@lucianu.it | +390187626927 |  |  |
| SIC-ID-000000001400 | Nuova Solai Edilpa Srl |  | Nuova Solai Edilpa Srl | info@nuovasolaiedilpa.it | +390583299472 |  |  |
| SIC-ID-000000001401 | Nutini Costruzioni Srl |  | Nutini Costruzioni Srl | nutini@nutini-costruzioni.com | +390583779379 |  |  |
| SIC-ID-000000001402 | OBI | Trento |  | fabio.fiani@obi-italia.it | +390461420294 |  |  |
| SIC-ID-000000001403 | ODAP - Officine di Architettura Pavese |  | ODAP - Officine di Architettura Pavese | info@odap.it | +393518866406 |  |  |
| SIC-ID-000000001404 | Anna Polacca marta |  | OFF STAGE SOCIETA' COOPERATIVA | anna.andrello@gmail.com | +393338664500 | VIADANA | MN |
| SIC-ID-000000001405 | Office | Gió |  | office@3technology.it | +393487761434 |  |  |
| SIC-ID-000000001406 | Official | Break |  | official@cam.tv | +393357790052 |  |  |
| SIC-ID-000000001407 | Officine | Nuove |  | info@officinenuove.it | +390432561586 |  |  |
| SIC-ID-000000001408 | OMCB Srl / Meccanica di Precisione / Sede Principale e Unità Produttiva |  | OMCB Srl / Meccanica di Precisione / Sede Principale e Unità Produttiva | info@omcb.it | +39030927278 |  |  |
| SIC-ID-000000001409 | Opera Edile |  | Opera Edile | info@operaedile.com | +390414764938 |  |  |
| SIC-ID-000000001410 | OPR di GALEONE ANGELO |  | OPR di GALEONE ANGELO | privacy@titrovo.it | +390187736483 |  |  |
| SIC-ID-000000001411 | Or | Mantovani |  | or.mantovani@gmail.com | +393313646515 |  |  |
| SIC-ID-000000001412 | Orazio | Paiola |  | o.paiola@alice.it | +393382900121 |  |  |
| SIC-ID-000000001413 | Oriana | Monaldo77 |  | oriana.monaldo77@gmail.com | +3934969219250 |  |  |
| SIC-ID-000000001414 | Ottavianelli Edilizia |  | Ottavianelli Edilizia | info@almaristrutturazioni.it | +393891354362 |  |  |
| SIC-ID-000000001415 | P.m.a. Di valisena / impresa edile - reggio emilia |  | P.m.a. Di valisena / impresa edile - reggio emilia | pmadivalisena@libero.it | +390522394013 |  |  |
| SIC-ID-000000001416 | Padana Pesca |  | PADANA PESCA S.R.L. UNIPERSONALE | padanapesca@libero.it | +390415506599 | Chioggia | VE |
| SIC-ID-000000001417 | Paesa80 | Rossi mc |  | paesa80@gmail.com | +3933513071710 |  |  |
| SIC-ID-000000001418 | Pagani Coperture - Civili e Industriali |  | Pagani Coperture - Civili e Industriali | info@paganicoperture.it | +39035342282 |  |  |
| SIC-ID-000000001419 | Pagano Costruzioni in Legno Srl |  | Pagano Costruzioni in Legno Srl | contact@pagano.it | +390650652480 |  |  |
| SIC-ID-000000001420 | Pallose |  |  | pallose@hotmail.it | +393492604244 |  |  |
| SIC-ID-000000001421 | Palumbo Costruzioni S.R.L. |  | Palumbo Costruzioni S.R.L. | info@palumbocostruzionisrl.com | +390583955030 |  |  |
| SIC-ID-000000001422 | pamela. | folini Rent |  | pamela.folini@fdrentservice.com | +393501542152 |  |  |
| SIC-ID-000000001423 | Panificio Girotti di girotti stefania ed elena s.n.c. |  | PANIFICIO GIROTTI di Girotti Stefania ed Elena s.n.c. | elenagirotti2014@libero.it | +393714227283 | Taglio di Po | RO |
| SIC-ID-000000001424 | Paola | Amodiolog |  | paola.amodiolog@gmail.com | +3934946920650 |  |  |
| SIC-ID-000000001425 | Paola | Camuffo |  | paola.camuffo@gmail.com | +393499048722 |  |  |
| SIC-ID-000000001426 | Paola | Caroti 54ot |  | paola.caroti.54ot@alice.it | +3934838977020 |  |  |
| SIC-ID-000000001427 | Paola | Giachino |  | p.giachino@greenenergyitalia.it | +393476759859 |  |  |
| SIC-ID-000000001428 | Paola | Saccoman |  | psaccoman@yahoo.it | +393206703339 |  |  |
| SIC-ID-000000001429 | Paola | Zambello69 |  | paola.zambello69@vodafone.it | +393384327131 |  |  |
| SIC-ID-000000001430 | Paolaeiredicuori |  |  | paolaeiredicuori@tiscali.it | +393488996434 |  |  |
| SIC-ID-000000001431 | Paolaperugini |  |  | paola27perugini@libero.it | +393471382315 |  |  |
| SIC-ID-000000001432 | Paolo |  |  | paolo@officine-parenti.it | +39336549297 |  |  |
| SIC-ID-000000001433 | Paolo | Andry |  | paolo.andry@gmail.com | +3933828476790 |  |  |
| SIC-ID-000000001434 | Paolo | Arch Ballarin |  | paolo_archballarin@hotmail.com | +393356369211 |  |  |
| SIC-ID-000000001435 | Paolo | BAR CENTRALE MORO |  | tstony@tiscali.it | +393385679212 |  |  |
| SIC-ID-000000001436 | Paolo | Casarin |  | paolo.casarin@artigianatopadovano.it | +3933136440440 |  |  |
| SIC-ID-000000001437 | Paolo | Colombo |  | paolo.colombo@la7.it | +393351231060 |  |  |
| SIC-ID-000000001438 | Paolo | Dronigi |  | paolo.dronigi@austria4you.com | +393488047482 |  |  |
| SIC-ID-000000001439 | Paolo | Facco |  | paolo.facco@cambiscena.it | +393482472219 |  |  |
| SIC-ID-000000001440 | Paolo | Franciosi |  | paolo.franciosi@tin.it | +393929908273 |  |  |
| SIC-ID-000000001441 | Paolo | Vianello |  | paolo.vianello@alice.it | +3934872362640 |  |  |
| SIC-ID-000000001442 | Paolodaw2013 |  |  | paolodaw2013@gmail.com | +393386476247 |  |  |
| SIC-ID-000000001443 | Paolomusic | Bergamin ftv |  | paolomusic@alice.it | +393455825013 |  |  |
| SIC-ID-000000001444 | Paolopav | Industriali |  | paolopav.industriali@gmail.com | +393473535000 |  |  |
| SIC-ID-000000001445 | Pareti Manovrabili Roma - Logicity Srl |  | Pareti Manovrabili Roma - Logicity Srl | info@logicity.it | +393906485731 |  |  |
| SIC-ID-000000001446 | Parilegno di Mattoccia Dario |  | Parilegno di Mattoccia Dario | info@parilegno.it | +390664400163 |  |  |
| SIC-ID-000000001447 | Parpajola | Gabriele |  | greenenergyitalia@gmail.com | +39335498923 |  |  |
| SIC-ID-000000001448 | Parrinello | G |  | parrinello.g@alice.it | +393334675162 |  |  |
| SIC-ID-000000001449 | Partner | Antonio 2 |  | partner@eshirt.it | +393487361979 |  |  |
| SIC-ID-000000001450 | Pasquale | Lillo |  | quattroeffestore@hotmail.it | +3934010723010 |  |  |
| SIC-ID-000000001451 | Pasqualini costruzioni |  | Pasqualini costruzioni | info@pasqualinicostruzioni.it | +390717819162 |  |  |
| SIC-ID-000000001452 | Pasticceria | Porto Viro |  | cristina.melato.89@gmail.com | +393891797670 |  |  |
| SIC-ID-000000001453 | Pastrello Costruzioni di pastrello michele & c. s.n.c. |  | Pastrello Costruzioni di pastrello michele & c. s.n.c. | info@pastrellocostruzioni.it | +393294516796 |  |  |
| SIC-ID-000000001454 | Pedrin | Adriano |  | pedrin.adriano@gmail.com | +393357818190 |  |  |
| SIC-ID-000000001455 | Pelizzo | Valerio |  | pelizzo.valerio@gmail.com | +393382543361 |  |  |
| SIC-ID-000000001456 | Pellecchiaalfredo | Milan |  | pellecchiaalfredo@gmail.com | +3933869056630 |  |  |
| SIC-ID-000000001457 | Pellegrino Giovanni costruzioni |  | Pellegrino Giovanni costruzioni | info@pellegrinocostruzioni.it | +393270078380 |  |  |
| SIC-ID-000000001458 | Pendy |  |  | giovannibillo90@gmail.com | +393491008038 |  |  |
| SIC-ID-000000001459 | Penny Lift Ascensori e Montascale |  | Penny Lift Ascensori e Montascale | info@pennylift.it | +39069103996 |  |  |
| SIC-ID-000000001460 | Perale Edilizia srl |  | Perale Edilizia srl | edilizia@gruppoperale.it | +39041612494 |  |  |
| SIC-ID-000000001461 | Perin Impresa edile |  | Perin Impresa edile | perinponteggi@perinponteggi.it | +393485417422 |  |  |
| SIC-ID-000000001462 | Pesce Costruzioni |  | Pesce Costruzioni | info@pescecostruzioni.it | +390415841026 |  |  |
| SIC-ID-000000001463 | Petra | Razum |  | razumpetra@gmail.com | +3933981828650 |  |  |
| SIC-ID-000000001464 | Pezzolatomatteo |  |  | pezzolatomatteo@gmail.com | +3934873457450 |  |  |
| SIC-ID-000000001465 | Pfa Srl |  | Pfa Srl | inferriate.milano@gmail.com | +3934828918140 |  |  |
| SIC-ID-000000001466 | Piatello | Beltramini acv |  | piatello@alice.it | +393486705641 |  |  |
| SIC-ID-000000001467 | Piattaforma | Sv |  | piattaforma.sv@gmail.com | +393351295432 |  |  |
| SIC-ID-000000001468 | Pidile |  |  | pidile@asl14chioggia.veneto.it | +393356319259 |  |  |
| SIC-ID-000000001469 | Pier | Lyo |  | pierbrunociocca@gmail.com | +393395288364 |  |  |
| SIC-ID-000000001470 | Pier | Russo |  | pierfedericorusso@gmail.com | +3933557572790 |  |  |
| SIC-ID-000000001471 | Piero | Chinello |  | piero.chinello@gmail.com | +393494140193 |  |  |
| SIC-ID-000000001472 | Piero | Gallimberti |  | pierogall@virgilio.it | +393480161460 |  |  |
| SIC-ID-000000001473 | Piero | Giuriola |  | piero.giuriola@yahoo.it | +3933987161010 |  |  |
| SIC-ID-000000001474 | Pieroni srl |  | Pieroni srl | matteo@pieroni.it | +390583838375 |  |  |
| SIC-ID-000000001475 | Pietro | Avanzi |  | pietro.avanzi@gmail.com | +393493629708 |  |  |
| SIC-ID-000000001476 | Pietro | Ran |  | pietro.ran@alice.it | +393355824202 |  |  |
| SIC-ID-000000001477 | Pietro | Rosin |  | pietro.rosin@bancamediolanum.it | +393471471714 |  |  |
| SIC-ID-000000001478 | Piga Costruzioni s.r.l. |  | Piga Costruzioni s.r.l. | info@schiavinagroup.com | +390532241613 |  |  |
| SIC-ID-000000001479 | Pizzardi Costruzioni |  | Pizzardi Costruzioni | g.pizzardi@alice.it | +393384397514 |  |  |
| SIC-ID-000000001480 | Paolo Pregnolato PL Pose SERVIZI |  | PL POSE E SERVIZI SRLS | paololapavimenti@alice.it | +393481757800 | Taglio di Po | RO |
| SIC-ID-000000001481 | Poggio-carlo | Sartor |  | poggio-carlo@libero.it | +393401448523 |  |  |
| SIC-ID-000000001482 | Poli | Silvia |  | silvia.p@luxia.it | +393356650323 |  |  |
| SIC-ID-000000001483 | Poli | Silvia |  | info@luxia.it | +393356650323 |  |  |
| SIC-ID-000000001484 | Poliambulatoriosangiovanni | Lanza |  | poliambulatoriosangiovanni@gmail.com | +393334432795 |  |  |
| SIC-ID-000000001485 | Policaro Srl |  | Policaro Srl | policarosrl@micso.net | +393929442440 |  |  |
| SIC-ID-000000001486 | Pomafer | Forniture-Forniture industriali |  | info@pomaferforniture.it | +393357210260 |  |  |
| SIC-ID-000000001487 | Ponteggi | Veloci / Noleggio-Montaggio-Smontaggio |  | ponteggiveloci@gmail.com | +390572508520 |  |  |
| SIC-ID-000000001488 | Power | System |  | powersystem.gm@gmail.com | +3934913524060 |  |  |
| SIC-ID-000000001489 | Pozzati Costruzioni srl |  | Pozzati Costruzioni srl | info@pozzaticostruzioni.it | +390532863057 |  |  |
| SIC-ID-000000001490 | Pra Ristrutturazioni - Ditta Ristrutturazioni Roma |  | Pra Ristrutturazioni - Ditta Ristrutturazioni Roma | commerciale.pra@gmail.com | +390669283839 |  |  |
| SIC-ID-000000001491 | PRAS Tecnica Edilizia Srl |  | PRAS Tecnica Edilizia Srl | pras@pras.it | +39066878374 |  |  |
| SIC-ID-000000001492 | Pressendo | Sonia |  | pressendo.sonia@gmail.com | +393490734270 |  |  |
| SIC-ID-000000001493 | Enzo De Biaggi Salara |  | PRO-LOCO DI SALARA | proloco.salara@alice.it | +393299685845 | Salara | RO |
| SIC-ID-000000001494 | Professoromec |  |  | professoromec@gmail.com | +393383076901 |  |  |
| SIC-ID-000000001495 | Profilgessi | Trapani |  | luminatenetworks@gmail.com | +39092322669 |  |  |
| SIC-ID-000000001496 | Progetto Creativo Srl |  | Progetto Creativo Srl | info@progettocreativosrl.it | +390623482606 |  |  |
| SIC-ID-000000001497 | PROGETTOB SRL |  | PROGETTOB SRL | info.progettob@gmail.com | +39058325483 |  |  |
| SIC-ID-000000001498 | Pucci Edilizia e Strade di Alessandro Pucci |  | Pucci Edilizia e Strade di Alessandro Pucci | pucci@pucciediliziaestrade.it | +39335394609 |  |  |
| SIC-ID-000000001499 | Puozzomassimo |  |  | puozzomassimo@libero.it | +393294503146 |  |  |
| SIC-ID-000000001500 | PV | Auto |  | pvpratiche@pv-centroservizi.it | +390495385729 |  |  |
| SIC-ID-000000001501 | Quadrilatero Marche-Umbria S.p.A. |  | Quadrilatero Marche-Umbria S.p.A. | responsabileprotezionedati@quadrilaterospa.it | +393906845601 |  |  |
| SIC-ID-000000001502 | R.g. Impresa edile |  | R.g. Impresa edile | rgsrl.info@gmail.com | +390544215658 |  |  |
| SIC-ID-000000001503 | Rabbiese | p.s.c.a.r.l. |  | info@rabbiese.it | +390463985488 |  |  |
| SIC-ID-000000001504 | Raffaele | Barcheri Colour |  | raffaele.barcheri@libero.it | +393288174112 |  |  |
| SIC-ID-000000001505 | Raffaele | Pagan |  | raffaelepagan88@gmail.com | +393487637670 |  |  |
| SIC-ID-000000001506 | Raffaelemarson |  |  | raffaelemarson@alice.it | +393467269714 |  |  |
| SIC-ID-000000001507 | Raffaella | Azenaib |  | raffaella@bulloalberto.191.it | +393339625351 |  |  |
| SIC-ID-000000001508 | Raffaella | Chioggiotta |  | raffa.unmondo@gmail.com | +393387300818 |  |  |
| SIC-ID-000000001509 | Raffaelladambra | D'ambra |  | raffaelladambra@live.it | +393387300818 |  |  |
| SIC-ID-000000001510 | Ranzatovariscoriccardo |  |  | ranzatovariscoriccardo@gmail.com | +393336767446 |  |  |
| SIC-ID-000000001511 | Rapid work di iurescia Massimiliano impresa edile e impianti idraulici |  | Rapid work di iurescia Massimiliano impresa edile e impianti idraulici | massimiliano5764@tiscali.it | +393714746831 |  |  |
| SIC-ID-000000001512 | Rastelli Edilizia |  | Rastelli Edilizia | davidrastelli@libero.it | +39067827545 |  |  |
| SIC-ID-000000001513 | Rce Costruzioni s.r.l. / impresa edile per nuove costruzioni e ristrutturazioni / modena / carpi |  | Rce Costruzioni s.r.l. / impresa edile per nuove costruzioni e ristrutturazioni / modena / carpi | info@rcecostruzioni.com | +390594725960 |  |  |
| SIC-ID-000000001514 | RE.I.M. S.r.l. |  | RE.I.M. S.r.l. | reimsrl@autiero.legalmail.it | +390815845843 |  |  |
| SIC-ID-000000001515 | Rebuilding | ancona |  | info@rebuildingancona.com | +390712072021 |  |  |
| SIC-ID-000000001516 | Reclasnc Cognome email telefono id cliente backup_sicu |  | Reclasnc Cognome email telefono id cliente backup_sicu | reclasnc@gmail.com | +393407231036 |  |  |
| SIC-ID-000000001517 | Red Edil costruzioni |  | Red Edil costruzioni | info@casagroupimmobiliare.it | +393382726955 |  |  |
| SIC-ID-000000001518 | Redemption | Elttric |  | redemption@studentcoin.org | +3933583906350 |  |  |
| SIC-ID-000000001519 | Redil Srl - Per l'edilizia |  | Redil Srl - Per l'edilizia | redil@redil.it | +390759291031 |  |  |
| SIC-ID-000000001520 | Reghellin Claudio impresa edile |  | Reghellin Claudio impresa edile | info@impresareghellin.it | +393355628653 |  |  |
| SIC-ID-000000001521 | Renata | Senatore |  | renata.senatore@live.it | +393401623436 |  |  |
| SIC-ID-000000001522 | Renato |  |  | renato@pescamar.it | +393384082881 |  |  |
| SIC-ID-000000001523 | Renda S.r.l. |  | Renda S.r.l. | impresarendasrl@gmail.com | +393664495193 |  |  |
| SIC-ID-000000001524 | Renova Red S.p.A. |  | Renova Red S.p.A. | ufficiosegreteria@renova.red | +390697848755 |  |  |
| SIC-ID-000000001525 | Renso67 | Cattlan |  | renso67@gmail.com | +3932836368670 |  |  |
| SIC-ID-000000001526 | Renzo | Bellonzi |  | renzo.bellonzi@libero.it | +393388694872 |  |  |
| SIC-ID-000000001527 | Renzo | Satti |  | renzo.satti@gmail.com | +393358191030 |  |  |
| SIC-ID-000000001528 | Repin S.r.l. |  | Repin S.r.l. | repin@repin.it | +390957110000 |  |  |
| SIC-ID-000000001529 | Residence | Capinera Scarpa |  | info@residencecapinera.com | +393392038798 |  |  |
| SIC-ID-000000001530 | Residence Bonetti - vacanze in Val di Rabbi Trentino alle porte del Parco Nazionale dello Stelvio CIN IT022150B4UPCH6CJG |  | Residence Bonetti - vacanze in Val di Rabbi Trentino alle porte del Parco Nazionale dello Stelvio CIN IT022150B4UPCH6CJG | info@residencebonetti.com | +390463901526 |  |  |
| SIC-ID-000000001531 | Restauracja | Bramarai |  | restauracja@szara.pl | +3934773853640 |  |  |
| SIC-ID-000000001532 | Restauroitalia |  |  | restauroitalia@gmail.com | +393463766542 |  |  |
| SIC-ID-000000001533 | Ribeirofilomena61 |  |  | ribeirofilomena61@gmail.com | +393358375900 |  |  |
| SIC-ID-000000001534 | Riccardo | Ferro |  | riccardo.ferro@hotmail.it | +393408958564 |  |  |
| SIC-ID-000000001535 | Riccardo | Franchi Shopping |  | riccardofranchi2011@hotmail.com | +393420576590 |  |  |
| SIC-ID-000000001536 | Riccardo | Frigato |  | rickyfriga@libero.it | +393482568584 |  |  |
| SIC-ID-000000001537 | Riccardo | Napolitano |  | riccardo.napolitano@tim.it | +3933864176630 |  |  |
| SIC-ID-000000001538 | Riccobono Costruzioni |  | Riccobono Costruzioni | info@riccobonocostruzioni.com | +393463978841 |  |  |
| SIC-ID-000000001539 | Rifugio | Ghebo |  | rifugioilghebo@libero.it | +393489157201 |  |  |
| SIC-ID-000000001540 | Rinaldi |  |  | info@piastrellerinaldi.it | +39017375335 |  |  |
| SIC-ID-000000001541 | Simone_rondina Scarsella |  | RISTORANTE PIZZERIA TIFFANY S.N.C. DI RONDINA SIMONE & C. | simone_rondina@yahoo.it | +393484190127 | Adria | RO |
| SIC-ID-000000001542 | Ristrutturazioni Bologna - impresa edile bologna - posapiù |  | Ristrutturazioni Bologna - impresa edile bologna - posapiù | dittaposapiu@yahoo.it | +390514129239 |  |  |
| SIC-ID-000000001543 | Ristrutturazioni Roma Nord - Building Technologies |  | Ristrutturazioni Roma Nord - Building Technologies | info@buildingtechnologies.it | +393279031101 |  |  |
| SIC-ID-000000001544 | Rita | Des57 |  | rita.des57@gmail.com | +39392880120 |  |  |
| SIC-ID-000000001545 | RM srl |  | RM srl | info@rmmarine.it | +390187415066 |  |  |
| SIC-ID-000000001546 | Road On s.r.l. |  | Road On s.r.l. | roadon.soluzionistradali@gmail.com | +393923378207 |  |  |
| SIC-ID-000000001547 | Robert |  |  | robert@broofa.com | +3937095516150 |  |  |
| SIC-ID-000000001548 | Roberto |  |  | roberto@housers.com | +393401256596 |  |  |
| SIC-ID-000000001549 | Roberto |  |  | borsettoroberto@gmail.com | +393400874124 |  |  |
| SIC-ID-000000001550 | Roberto | Carlesso |  | robertocarlesso@hotmail.it | +393500116346 |  |  |
| SIC-ID-000000001551 | Roberto | Carturan |  | roberto.carturan@alice.it | +3932967447960 |  |  |
| SIC-ID-000000001552 | Roberto | Frosolone |  | rfrosolone@yahoo.com | +393335747025 |  |  |
| SIC-ID-000000001553 | Roberto | Lyo |  | robecanci@gmail.com | +393334881086 |  |  |
| SIC-ID-000000001554 | Roberto | Paccagnella |  | roberto.paccagnella@antonveneta.it | +3932847763300 |  |  |
| SIC-ID-000000001555 | Roberto | Pettenello |  | roberto.pettenello@libero.it | +393483528346 |  |  |
| SIC-ID-000000001556 | Roberto | Porello |  | roberto.porello@lyoness.it | +3934830225540 |  |  |
| SIC-ID-000000001557 | Roberto | Rossi |  | roberto@harley-davidson-mantova.it | +393471503750 |  |  |
| SIC-ID-000000001558 | Roberto | Sicurezza Mengozzi |  | mengozziroberto1972@gmail.com | +393347377181 |  |  |
| SIC-ID-000000001559 | Roberto | Targa |  | roberto.targa@alice.it | +393335747025 |  |  |
| SIC-ID-000000001560 | Roberto | Zaccaro |  | roberto.zaccaro@gmail.com | +393482227092 |  |  |
| SIC-ID-000000001561 | Roberto | Zucchetto Polenta |  | info@zucchettonoleggi.com | +393357073553 |  |  |
| SIC-ID-000000001562 | Roberto68533 |  |  | roberto68533@gmail.com | +393346170884 |  |  |
| SIC-ID-000000001563 | Robertoravag |  |  | robertoravagnan@hotmail.it | +393202584607 |  |  |
| SIC-ID-000000001564 | Robipoltro57 | Videoclub |  | robipoltro57@gmail.com | +3938910601660 |  |  |
| SIC-ID-000000001565 | Raccatello Mauro |  | ROCCATELLO MAURO | roccatello.mauro@libero.it | +393282286024 | Cavarzere | VE |
| SIC-ID-000000001566 | Rocco | Cita |  | rocco@lodise.net | +393403618607 |  |  |
| SIC-ID-000000001567 | Roccoparrucchieri | Grondaie |  | roccoparrucchieri@gmail.com | +393478585394 |  |  |
| SIC-ID-000000001568 | ROMA EDILIZIA |  | ROMA EDILIZIA | info@edilroma.com | +39066385035 |  |  |
| SIC-ID-000000001569 | Roma Edilizia - Ristrutturazioni Roma |  | Roma Edilizia - Ristrutturazioni Roma | inforomaedilizia@gmail.com | +390693379894 |  |  |
| SIC-ID-000000001570 | Romaandrea |  |  | romaandrea@libero.it | +393386417663 |  |  |
| SIC-ID-000000001571 | Romani | remo |  | studio@romaniremo.it | +39071200696 |  |  |
| SIC-ID-000000001572 | Romapatrickx27 |  |  | romapatrickx27@gmail.com | +393394700867 |  |  |
| SIC-ID-000000001573 | Romea Asfalti Srl |  | Romea Asfalti Srl | info@romeasfalti.it | +39041698366 |  |  |
| SIC-ID-000000001574 | Roobnigro |  |  | roobnigro@hotmail.com | +393488095507 |  |  |
| SIC-ID-000000001575 | Ropier61 |  |  | ropier61@gmail.com | +393488147893 |  |  |
| SIC-ID-000000001576 | Rosatistefania4 |  |  | rosatistefania4@gmail.com | +393476427107 |  |  |
| SIC-ID-000000001577 | Rossella | C |  | 77rossella.c@gmail.com | +393387322286 |  |  |
| SIC-ID-000000001578 | Rossigraziano1974 | Rossi |  | rossigraziano1974@gmail.com | +393393779441 |  |  |
| SIC-ID-000000001579 | Roxxdown | Four roxx down |  | 4roxxdown@gmail.com | +393406693347 |  |  |
| SIC-ID-000000001580 | Ruatti Legnami S.r.l. |  | Ruatti Legnami S.r.l. | info@ruattilegnami.it | +390463901270 |  |  |
| SIC-ID-000000001581 | S | Fontanelli68 |  | s.fontanelli68@gmail.com | +393452329228 |  |  |
| SIC-ID-000000001582 | S | Presti76 |  | s.presti76@gmail.com | +393386440072 |  |  |
| SIC-ID-000000001583 | S | Simone81 |  | s.simone81@gmail.com | +393923769296 |  |  |
| SIC-ID-000000001584 | S. I. P. I. Nord Srl |  | S. I. P. I. Nord Srl | info@solitec.eu | +390636381299 |  |  |
| SIC-ID-000000001585 | S.i.c.e.s. Group s.r.l. |  | S.i.c.e.s. Group s.r.l. | sicesgroupsrl@libero.it | +393451193377 |  |  |
| SIC-ID-000000001586 | S.i.m.e. S.r.l. |  | S.i.m.e. S.r.l. | simemarsala@gmail.com | +390923969492 |  |  |
| SIC-ID-000000001587 | S.lj.co. Srl / impresa edile di costruzione, restauro e ristrutturazione / bologna |  | S.lj.co. Srl / impresa edile di costruzione, restauro e ristrutturazione / bologna | info@sljco.it | +39051474040 |  |  |
| SIC-ID-000000001588 | S.p. Costruzioni |  | S.p. Costruzioni | spcostruzioni84@gmail.com | +393895215720 |  |  |
| SIC-ID-000000001589 | S.p.s. Impresa edile |  | S.p.s. Impresa edile | info@irce.it | +390542670621 |  |  |
| SIC-ID-000000001590 | S.v.s. Costruzioni s.r.l. |  | S.v.s. Costruzioni s.r.l. | svscostruzionisrl@virgilio.it | +390923553848 |  |  |
| SIC-ID-000000001591 | Sabrina | Dalmaso |  | sabrina.dalmaso@gmail.com | +39335276769 |  |  |
| SIC-ID-000000001592 | Sabrina | Laterra |  | sabrina.laterra@libero.it | +393483544599 |  |  |
| SIC-ID-000000001593 | SAC • Società Appalti Costruzioni S.p.A. |  | SAC • Società Appalti Costruzioni S.p.A. | odv@sacspa.it | +39068084741 |  |  |
| SIC-ID-000000001594 | Saccoman | Enrico |  | saccoman.enrico@libero.it | +393407984884 |  |  |
| SIC-ID-000000001595 | Safer - impresa edile - ristrutturazioni - manutenzioni - recupero edifici |  | Safer - impresa edile - ristrutturazioni - manutenzioni - recupero edifici | info@saferlugo.it | +39054530493 |  |  |
| SIC-ID-000000001596 | Saln | Salnitri lyo |  | saln@libero.it | +393489022121 |  |  |
| SIC-ID-000000001597 | Salone | Bolle Blu |  | salone.bolleblu@virgilio.it | +393357576234 |  |  |
| SIC-ID-000000001598 | Salvatore Zingaro impresa edile |  | Salvatore Zingaro impresa edile | zngcostruzioni@virgilio.it | +390516841388 |  |  |
| SIC-ID-000000001599 | Samuele | Barbato |  | samuele.barbato@bmautomazioni.com | +3934713823150 |  |  |
| SIC-ID-000000001600 | Sandra_8 |  |  | sandra_8@hotmail.it | +393357356146 |  |  |
| SIC-ID-000000001601 | Sandraferri81 |  |  | sandraferri81@gmail.com | +393357604637 |  |  |
| SIC-ID-000000001602 | Sandro | Bevilacqua |  | sandro.bevilacqua@confcom.it | +3932846609840 |  |  |
| SIC-ID-000000001603 | Sandro | Tortello |  | sandrotortello@gmail.com | +393284829318 |  |  |
| SIC-ID-000000001604 | Sandromagi | Dona |  | sandromagnani@tiscali.it | +393487347694 |  |  |
| SIC-ID-000000001605 | Sara | Bellan |  | sarabellan81@gmail.com | +393408520549 |  |  |
| SIC-ID-000000001606 | Sara | Bellan |  | idea@labotecnic.com | +393408520549 |  |  |
| SIC-ID-000000001607 | Sara | Duchi |  | sara.duchi@alice.it | +393348156764 |  |  |
| SIC-ID-000000001608 | Sara | Renosto |  | sara.renosto@heslab.it | +393206053662 |  |  |
| SIC-ID-000000001609 | Sara | Tiozzo |  | sara.tiozzo@attivamenteonlus.it | +393519646302 |  |  |
| SIC-ID-000000001610 | Sara Costruzioni e servizi s.r.l |  | Sara Costruzioni e servizi s.r.l | aziendasarasrl@libero.it | +393332489210 |  |  |
| SIC-ID-000000001611 | SARDA HOUSE S.R.L. |  | SARDA HOUSE S.R.L. | info@sardahousesrl.com | +393281295128 |  |  |
| SIC-ID-000000001612 | Sarda Strade Srl |  | Sarda Strade Srl | sardastrade@gmail.com | +39070243372 |  |  |
| SIC-ID-000000001613 | Saritalia Srl |  | Saritalia Srl | info@saritalia.eu | +390664651252 |  |  |
| SIC-ID-000000001614 | Sarmuci Costruzioni srl di nino stellino |  | Sarmuci Costruzioni srl di nino stellino | info@sarmucicostruzioni.it | +393899218415 |  |  |
| SIC-ID-000000001615 | Scampeotto | Campeotto |  | scampeotto@libero.it | +3934779100170 |  |  |
| SIC-ID-000000001616 | Scavitel S.r.l. |  | Scavitel S.r.l. | g.parisi@scavitelsrl.it | +393929396607 |  |  |
| SIC-ID-000000001617 | Scegliere Infissi Lucca - Diemme Infissi |  | Scegliere Infissi Lucca - Diemme Infissi | info@diemmeinfissi.com | +390583990244 |  |  |
| SIC-ID-000000001618 | Schibapaolo |  |  | schibapaolo@alice.it | +393483968101 |  |  |
| SIC-ID-000000001619 | Sciacca Francesco & Figli S.a.s |  | Sciacca Francesco & Figli S.a.s | info@ceramichesciacca.it | +390923990603 |  |  |
| SIC-ID-000000001620 | Scuttari | Luciano |  | scuttari.luciano@libero.it | +393482712724 |  |  |
| SIC-ID-000000001621 | SE Impresa Edile |  | SE Impresa Edile | seimpresaedile@gmail.com | +393358485231 |  |  |
| SIC-ID-000000001622 | Se. Car. Srl |  | Se. Car. Srl | info@se-car.it | +390415631181 |  |  |
| SIC-ID-000000001623 | Sec S.R.L. |  | Sec S.R.L. | info@secsrl.it | +390456301979 |  |  |
| SIC-ID-000000001624 | Sedefa |  |  | info@sedefa.it | +39068804425 |  |  |
| SIC-ID-000000001625 | Serena | Coiffure |  | serena.coiffure@gmail.com | +393494236175 |  |  |
| SIC-ID-000000001626 | Sergio | Silecchia |  | sergio.silecchia@gmail.com | +393404839027 |  |  |
| SIC-ID-000000001627 | SERRAMENTI ALBARESTAURI |  | SERRAMENTI ALBARESTAURI | info@albarestauri.it | +393922186855 |  |  |
| SIC-ID-000000001628 | Service It |  | Service It | service.it@organic.plus | +393336184461 |  |  |
| SIC-ID-000000001629 | Service It |  | Service It | service.it@cashbackworld.com | +393881809475 |  |  |
| SIC-ID-000000001630 | Service It |  | Service It | service.it@cashback-solutions.com | +393386896289 |  |  |
| SIC-ID-000000001631 | Servizi Edili r.c. |  | Servizi Edili r.c. | rec_srl@virgilio.it | +393389347865 |  |  |
| SIC-ID-000000001632 | Sfsuperesse124 |  |  | sfsuperesse124@gmail.com | +39394990130 |  |  |
| SIC-ID-000000001633 | Sgsprogetti | Tv |  | sgsprogetti.tv@gmail.com | +393332873270 |  |  |
| SIC-ID-000000001634 | Shopwki | Fontana |  | shopwki@wki.it | +393477033437 |  |  |
| SIC-ID-000000001635 | Ezio Lyo |  | SI.T.A. DI MANTOVANI G. & C. S.N.C. | info@sitasnc.it | +393382893112 | Fiscaglia | FE |
| SIC-ID-000000001636 | Sicil Funi sicilia impresa edile |  | Sicil Funi sicilia impresa edile | info@edilfuni.it | +393505322461 |  |  |
| SIC-ID-000000001637 | Sicilia ponteggi srls |  | Sicilia ponteggi srls | siciliaponteggisrls2020@gmail.com | +393275359957 |  |  |
| SIC-ID-000000001638 | Sicilscavi di Spampinato Salvatore |  | Sicilscavi di Spampinato Salvatore | sicilscavi@live.it | +393515320364 |  |  |
| SIC-ID-000000001639 | Sicurissimo | Pregnolato privato |  | infosicurissimo@gmail.com | +393388771737 |  |  |
| SIC-ID-000000001640 | Sidermori Srl |  | Sidermori Srl | giorgio@sidermori.it | +390464918631 |  |  |
| SIC-ID-000000001641 | Signorinomariannatp |  |  | signorinomariannatp@gmail.com | +393357083572 |  |  |
| SIC-ID-000000001642 | Silvanos |  |  | silvano67s@gmail.com | +393357693125 |  |  |
| SIC-ID-000000001643 | Silvia | Beltrame |  | silvia.beltrame@itstecnologie.it | +393357818190 |  |  |
| SIC-ID-000000001644 | Silvia | Busson com |  | silvia@elettrocostruzioni.com | +393479474716 |  |  |
| SIC-ID-000000001645 | Silvia | Casson |  | silvia.casson@attivamenteonlus.it | +393356353336 |  |  |
| SIC-ID-000000001646 | Silvia | Crepaldi |  | silvia@centrodicalcolo.it | +393207872234 |  |  |
| SIC-ID-000000001647 | Silvia | Passarella |  | silvia.passarella@alleanza.it | +393400713397 |  |  |
| SIC-ID-000000001648 | Silvia_pozzati |  |  | silvia_pozzati@yahoo.it | +3934755253480 |  |  |
| SIC-ID-000000001649 | Silviavanin |  |  | silviavanin@live.it | +393497139156 |  |  |
| SIC-ID-000000001650 | Simona | Massimo |  | simona@evolutionforum.sm | +393287257621 |  |  |
| SIC-ID-000000001651 | Simona | Mazza |  | simona.mazza@hotmail.it | +393283295611 |  |  |
| SIC-ID-000000001652 | Simonacassioli | Pregnolato surf |  | simonacassioli@gmail.com | +393494520924 |  |  |
| SIC-ID-000000001653 | Simoncello | C |  | simoncello.c@alice.it | +393472313864 |  |  |
| SIC-ID-000000001654 | Simone | Avv. boscolo |  | boscolo.simone@gmail.com | +393478566813 |  |  |
| SIC-ID-000000001655 | Simone | Boscolo |  | simone.boscolo@gmail.com | +393201633858 |  |  |
| SIC-ID-000000001656 | Simone | Cestaro |  | simone.cestaro@ingetek.it | +393203257596 |  |  |
| SIC-ID-000000001657 | Simone | Cestaro |  | simone.cestaro@ondatek.it | +393346147157 |  |  |
| SIC-ID-000000001658 | Simone | Eynard |  | simone.eynard@gmail.com | +393515784810 |  |  |
| SIC-ID-000000001659 | Simone | Lyo |  | simone.cason@koine.ve.it | +393356824460 |  |  |
| SIC-ID-000000001660 | Simone | Zanella |  | simone.zanella@libero.it | +393402327568 |  |  |
| SIC-ID-000000001661 | Simone Cognome email telefono id cliente backup_sicu |  | Simone Cognome email telefono id cliente backup_sicu | gigosimone@gmail.com | +3933883893430 |  |  |
| SIC-ID-000000001662 | Simone Finardi italy |  | Simone Finardi italy | simone.finardi@gmail.com | +393428027700 |  |  |
| SIC-ID-000000001663 | Simonedelo | Onean |  | simonedelo@gmail.com | +393495044256 |  |  |
| SIC-ID-000000001664 | Simonemoro | Sm |  | simonemoro.sm@gmail.com | +393496362330 |  |  |
| SIC-ID-000000001665 | Simonepanfilio |  |  | simonepanfilio@libero.it | +393428027700 |  |  |
| SIC-ID-000000001666 | Simonezanellati | CICS |  | simonezanellati@gmail.com | +393402327568 |  |  |
| SIC-ID-000000001667 | Sip Srl - impresa edile |  | Sip Srl - impresa edile | info@sipimpresaedile.it | +393759080960 |  |  |
| SIC-ID-000000001668 | Sirco S.r.l. |  | Sirco S.r.l. | tecnico@sircosrl.it | +390518659436 |  |  |
| SIC-ID-000000001669 | SM Costruzioni di Mura srl |  | SM Costruzioni di Mura srl | sm-costruzioni@outlook.com | +393285615358 |  |  |
| SIC-ID-000000001670 | Sme-service Teknotherm |  | Sme-service Teknotherm | sme-service@lyoness.it | +393402306961 |  |  |
| SIC-ID-000000001671 | So.ge.se. srl |  | So.ge.se. srl | paolo.vaccai@sogeseitalia.it | +390516650647 |  |  |
| SIC-ID-000000001672 | Porzionato Diego |  | SOCIETA' COOPERATIVA FACCHINI CONTARINA | porzionatodiego@tiscali.it | +393483641299 | Porto Viro | RO |
| SIC-ID-000000001673 | Solida - impresa edile |  | Solida - impresa edile | info@solidaedilizia.it | +393891535786 |  |  |
| SIC-ID-000000001674 | Solida Costruzioni srl |  | Solida Costruzioni srl | info@solidacostruzioni.com | +393473639171 |  |  |
| SIC-ID-000000001675 | Soluzioni | Edili Bergamo |  | info@soluzioni-edili.it | +39035247176 |  |  |
| SIC-ID-000000001676 | Soluzioni e Costruzioni Soc. Coop. |  | Soluzioni e Costruzioni Soc. Coop. | info@soluzioniecostruzioni.it | +393490548584 |  |  |
| SIC-ID-000000001677 | Soncin | Mattia Ceramika |  | puntoceramika@gmail.com | +3932835649590 |  |  |
| SIC-ID-000000001678 | Sonepar - Napoli - Distributore di Materiale Elettrico |  | Sonepar - Napoli - Distributore di Materiale Elettrico | marco.fresco@sonepar.it | +390815503111 |  |  |
| SIC-ID-000000001679 | Sonia | Artusi |  | sonia.artusi@alice.it | +393498112131 |  |  |
| SIC-ID-000000001680 | Sonia | Bergantin |  | sonia.bergantin@gmail.com | +393933675436 |  |  |
| SIC-ID-000000001681 | Sonia | Minorini |  | sonia.minorini@alice.it | +393402860300 |  |  |
| SIC-ID-000000001682 | Sonia | Palmisano |  | sonia.palmisano@tiscali.it | +393351002415 |  |  |
| SIC-ID-000000001683 | Sorini |  |  | info@soriniedilizia.it | +390572635033 |  |  |
| SIC-ID-000000001684 | Sottomarinasup | Bezzi |  | sottomarinasup@geniuslociasd.com | +393295361604 |  |  |
| SIC-ID-000000001685 | Spadaro uncini materiali e soluzioni per l'edilizia |  | Spadaro uncini materiali e soluzioni per l'edilizia | info@spadarouncini.it | +39071908800 |  |  |
| SIC-ID-000000001686 | Spagnoli Spagnoli |  | Spagnoli Spagnoli | spagnoli@waveelectric.it | +393408624986 |  |  |
| SIC-ID-000000001687 | Stafftre S.r.l. |  | Stafftre S.r.l. | info@villaggiobiancocalce.it | +393357020546 |  |  |
| SIC-ID-000000001688 | Stefanedil - Il Mondo dell'Edilizia |  | Stefanedil - Il Mondo dell'Edilizia | trionfale@stefanedil.com | +390661283731 |  |  |
| SIC-ID-000000001689 | Stefania | Erdmann |  | stefyerdmann@gmail.com | +393454696690 |  |  |
| SIC-ID-000000001690 | Stefania | Padoan |  | stefania.padoan@attivamenteonlus.it | +393488435472 |  |  |
| SIC-ID-000000001691 | Stefania | Roncon |  | stefania.roncon@crveneto.it | +393488517708 |  |  |
| SIC-ID-000000001692 | Stefania Pastore |  | Stefania Pastore | stefania.pastore@attivamenteonlus.it | +393485204990 |  |  |
| SIC-ID-000000001693 | Stefania_br |  |  | stefania_br@libero.it | +393464923828 |  |  |
| SIC-ID-000000001694 | Stefaniabalestrino |  |  | stefaniabalestrino@hotmail.it | +393460279454 |  |  |
| SIC-ID-000000001695 | Stefaniazeta72 | Perin |  | stefaniazeta72@hotmail.com | +393279754151 |  |  |
| SIC-ID-000000001696 | Stefano | Antico |  | stefano.antico@vigilfuoco.it | +393491717851 |  |  |
| SIC-ID-000000001697 | Stefano | Boccato |  | stefanoboccato96@gmail.com | +393455895400 |  |  |
| SIC-ID-000000001698 | Stefano | Crescenzo |  | stefano.crescenzo@gmail.com | +393471735085 |  |  |
| SIC-ID-000000001699 | Stefano | Dallanora |  | stefano.dallanora@riwega.com | +393339344994 |  |  |
| SIC-ID-000000001700 | Stefano | Faita |  | stefano.faita@sicurcond.it | +393381686286 |  |  |
| SIC-ID-000000001701 | Stefano | Ferrarese |  | stefano.ferrarese@alice.it | +393482560580 |  |  |
| SIC-ID-000000001702 | Stefano | Malibu' |  | stefano@pivatostefano.com | +393939701780 |  |  |
| SIC-ID-000000001703 | Stefano | Pozzuolo |  | stefano.pozzuolo@hotmail.it | +393357176835 |  |  |
| SIC-ID-000000001704 | Stefano | Smeratdi |  | stefano.smeraldi@venetoformazione.it | +393396407783 |  |  |
| SIC-ID-000000001705 | Stefano | Video club ok |  | stefano@essetiservice.com | +393474219379 |  |  |
| SIC-ID-000000001706 | Stefano Di lisi |  | Stefano Di lisi | stefano.di.lisi@gmail.com | +393281214929 |  |  |
| SIC-ID-000000001707 | Stefanobaldo |  |  | stefanobaldo@hotmail.it | +393474100541 |  |  |
| SIC-ID-000000001708 | Steldo Srl |  | Steldo Srl | riva@steldo.it | +390464594300 |  |  |
| SIC-ID-000000001709 | Stenaglia | Tenaglia |  | stenaglia@remax.it | +393358156468 |  |  |
| SIC-ID-000000001710 | Stil casa costruzioni |  | Stil casa costruzioni | info@stilcasacostruzioni.it | +393470052496 |  |  |
| SIC-ID-000000001711 | Stile Costruzioni Edili Di Rebecchini Ing.Luigi & C. Spa |  | Stile Costruzioni Edili Di Rebecchini Ing.Luigi & C. Spa | vendite@stilespa.it | +39066791500 |  |  |
| SIC-ID-000000001712 | Stipo72 |  |  | stipo72@gmail.com | +393475075526 |  |  |
| SIC-ID-000000001713 | Storo Diesel S.r.l. |  | Storo Diesel S.r.l. | zocchi@storodiesel.it | +390465686411 |  |  |
| SIC-ID-000000001714 | Studio Bucatari - D. ssa Destro |  | Studio Bucatari - D. ssa Destro | contabilita@studiobucatari.it | +3904251573034 |  |  |
| SIC-ID-000000001715 | Studio design bastianoni |  | Studio design bastianoni | info@studiobastianoni.com | +390716609726 |  |  |
| SIC-ID-000000001716 | Studio Maila |  | Studio Maila | maila.contabilita@chioggia.it | +39301372630 |  |  |
| SIC-ID-000000001717 | Studio Micheletti e Crepaldi |  | Studio Micheletti e Crepaldi | debora@micheletticrepaldi.it | +393331571836 |  |  |
| SIC-ID-000000001718 | Studio Tecnico ed impresa edile |  | Studio Tecnico ed impresa edile | info@tecnoedi.com | +393299886408 |  |  |
| SIC-ID-000000001719 | Studio tecnico ing. callari |  | Studio tecnico ing. callari | info@studiocallari.it | +390712805068 |  |  |
| SIC-ID-000000001720 | Studio tecnico ing. luigi fagiani |  | Studio tecnico ing. luigi fagiani | info@studiofagiani.eu | +390717931046 |  |  |
| SIC-ID-000000001721 | Massimo Tonon |  | Studio Tecnico Tonon Geom. Massimo | tononmax@libero.it | +393291585784 | Piove di Sacco | PD |
| SIC-ID-000000001722 | Studio zoppi ingegneria e associati |  | Studio zoppi ingegneria e associati | info.studiozoppi@gmail.com | +390712076581 |  |  |
| SIC-ID-000000001723 | Studiocentonza |  | Studiocentonza | studiocentonza@libero.it | +393482739540 |  |  |
| SIC-ID-000000001724 | Studiod Progetto | DESIDERIO |  | studiod.progetto@hotmail.it | +393407885810 | Chioggia | VE |
| SIC-ID-000000001725 | Studiogiacon |  | Studiogiacon | studiogiacon@libero.it | +393400004537 |  |  |
| SIC-ID-000000001726 | studiorocas architects |  | studiorocas architects | r.casconi@awn.it | +390692918231 |  |  |
| SIC-ID-000000001727 | Studiosicurezzapadalino F150 |  | Studiosicurezzapadalino F150 | studiosicurezzapadalino@gmail.com | +3934658403610 |  |  |
| SIC-ID-000000001728 | Stufreg Rag. freguglia |  | Stufreg Rag. freguglia | stufreg@gmail.com | +3934643601580 |  |  |
| SIC-ID-000000001729 | Style | Maison Parquet Roma |  | info@stylemaison.com | +390684241534 |  |  |
| SIC-ID-000000001730 | Sugo | Dario |  | sugo.dario@libero.it | +393939198786 |  |  |
| SIC-ID-000000001731 | Susanna | Cavallarin |  | susanna.cavallarin@attivamenteonlus.it | +393494214877 |  |  |
| SIC-ID-000000001732 | susy | gesso |  | susydalgesso@gmail.com | +393363673620 |  |  |
| SIC-ID-000000001733 | SVR | Ristrutturazioni Roma |  | info@svrristrutturazioni.it | +393394972833 |  |  |
| SIC-ID-000000001734 | T Costruzioni SRLS |  | T Costruzioni SRLS | tcostruzionicontact@gmail.com | +393384952551 |  |  |
| SIC-ID-000000001735 | T.A.T. | Tecno Assistenza Trentina |  | info@tecnoassistenzatrentina.it | +390461822278 |  |  |
| SIC-ID-000000001736 | T.c.a. srl |  | T.c.a. srl | tcasrl@cisanaelio.it | +390290394254 |  |  |
| SIC-ID-000000001737 | T.L. Arte del Pulito SRL - Impresa di Pulizie Mira |  | T.L. Arte del Pulito SRL - Impresa di Pulizie Mira | lily_tudos@icloud.com | +393278712813 |  |  |
| SIC-ID-000000001738 | Tabusso | Carlo Building Materials |  | chieri.detommasi@bigmat.it | +3901721801162 |  |  |
| SIC-ID-000000001739 | Taglio cemento armato bergamo - Tecno Edilizia Srl |  | Taglio cemento armato bergamo - Tecno Edilizia Srl | info@tecnoedilizia.com | +39035720934 |  |  |
| SIC-ID-000000001740 | Talarico S.r.l. - Materiali per l' edilizia - marmi - arredobagno - idraulica - rivestimenti |  | Talarico S.r.l. - Materiali per l' edilizia - marmi - arredobagno - idraulica - rivestimenti | info@talaricosrl.com | +390961961155 |  |  |
| SIC-ID-000000001741 | Targa | Roberto |  | targa.roberto@alice.it | +393923850238 |  |  |
| SIC-ID-000000001742 | Tavella | Federico |  | tavella_federico@libero.it | +393408782809 |  |  |
| SIC-ID-000000001743 | Tavola | Materiali Edili |  | tavolamaterialiedili@gmail.com | +390395310122 |  |  |
| SIC-ID-000000001744 | Team | Edil green |  | bigiariniema@gmail.com | +390510014712 |  |  |
| SIC-ID-000000001745 | Team | Edilcoperture |  | team@meetlima.com | +393470736019 |  |  |
| SIC-ID-000000001746 | Tecnica Edilizia Srl |  | Tecnica Edilizia Srl | tecnicaediliziasrl@tiscali.it | +390862453012 |  |  |
| SIC-ID-000000001747 | Tecno | Appalti |  | tecnoappaltiroma@gmail.com | +390685856031 |  |  |
| SIC-ID-000000001748 | Tecno Impianti caruso |  | Tecno Impianti caruso | info@tecnoimpianticaruso.it | +393209708260 |  |  |
| SIC-ID-000000001749 | TECNOEDIL sas di Toffali M.W. e C. / Rappresentanze Edili |  | TECNOEDIL sas di Toffali M.W. e C. / Rappresentanze Edili | tecnoedil@tecnologieedili.it | +393488567724 |  |  |
| SIC-ID-000000001750 | Tecnogesso | Lavorazione Cartongesso Mira |  | tecnogesso@libero.it | +393282705918 |  |  |
| SIC-ID-000000001751 | Tecnomat |  |  | tecnomat@tecnomat.it | +390283905463 |  |  |
| SIC-ID-000000001752 | Teo Pizza Crocco |  | TEO PIZZA SAS DI CROCCO CINZIA & C. | cin.ele66@yahoo.it | +390414950018 | CORBOLA | RO |
| SIC-ID-000000001753 | Terme Di Rabbi |  | Terme Di Rabbi | info@termedirabbi.it | +390463983000 |  |  |
| SIC-ID-000000001754 | Termoidraulica Coico |  | Termoidraulica Coico | info@termoidraulicacoico.com | +390633253387 |  |  |
| SIC-ID-000000001755 | Termoidraulica Mei Srl |  | Termoidraulica Mei Srl | termoidraulicamei@gmail.com | +39065810310 |  |  |
| SIC-ID-000000001756 | Terranova Costruzioni srl |  | Terranova Costruzioni srl | distrettopesca@gmail.com | +390923947763 |  |  |
| SIC-ID-000000001757 | Terre dell'Etruria - Casino di Terra |  | Terre dell'Etruria - Casino di Terra | raggi@terretruria.it | +39058836043 |  |  |
| SIC-ID-000000001758 | Terreverdi Soc. Coop. Per Azioni |  | Terreverdi Soc. Coop. Per Azioni | commerciale@terreverdicoop.it | +390717958719 |  |  |
| SIC-ID-000000001759 | Tesonefra | Hyper capital |  | tesonefra@libero.it | +393938533284 |  |  |
| SIC-ID-000000001760 | Teving S.r.l. |  | Teving S.r.l. | info@teving.it | +390923551238 |  |  |
| SIC-ID-000000001761 | The trailed Roma Srl |  | The trailed Roma Srl | g.mancini1478@gmail.com | +39066530640 |  |  |
| SIC-ID-000000001762 | tiberio | bacci |  | tiberiobacci@libero.it | +393498680142 |  |  |
| SIC-ID-000000001763 | Tiemme Costruzioni edili spa |  | Tiemme Costruzioni edili spa | info@tiemmecostruzioni.it | +390495792022 |  |  |
| SIC-ID-000000001764 | Tink | Ristrutturazioni |  | info@tinkristrutturazioni.it | +393758720028 |  |  |
| SIC-ID-000000001765 | Tizianalio Cognome email telefono id cliente backup_sicu |  | Tizianalio Cognome email telefono id cliente backup_sicu | tizianalio@libero.it | +393284660984 |  |  |
| SIC-ID-000000001766 | Tiziano | Cetarini |  | tiziano.cetarini@gmail.com | +393487944934 |  |  |
| SIC-ID-000000001767 | Tiziano | Guidarini Distributori |  | tommcarburanti@virgilio.it | +393358375900 |  |  |
| SIC-ID-000000001768 | Top Color Srl - Forniture Professionali |  | Top Color Srl - Forniture Professionali | oriago@topcolorsrl.it | +390415630302 |  |  |
| SIC-ID-000000001769 | Toschi Costruzioni - impresa edile |  | Toschi Costruzioni - impresa edile | info@toschicostruzioni.it | +393385859398 |  |  |
| SIC-ID-000000001770 | Tosoj |  |  | tosoj@libero.it | +393408457028 |  |  |
| SIC-ID-000000001771 | Tre | C |  | impresaediletrec@libero.it | +393485267581 |  |  |
| SIC-ID-000000001772 | Trediemme Restauri / Restauro Opere Architettoniche |  | Trediemme Restauri / Restauro Opere Architettoniche | trediemmerestauri@mclink.it | +39065755983 |  |  |
| SIC-ID-000000001773 | Trentinabeton srl |  | Trentinabeton srl | mirtis@trentinabeton.com | +390461757329 |  |  |
| SIC-ID-000000001774 | Tuan | Nguyen |  | tuan.nguyen@chipcore.eu | +393396913435 |  |  |
| SIC-ID-000000001775 | Tucciaronepasquale | Cariparo |  | tucciaronepasquale@gmail.com | +393346935515 |  |  |
| SIC-ID-000000001776 | Tuttedile di Genna Leonarda & Francesco s.n.c. |  | Tuttedile di Genna Leonarda & Francesco s.n.c. | info@tuttedile.it | +390332470238 |  |  |
| SIC-ID-000000001777 | Tutto Per l'edilizia |  | Tutto Per l'edilizia | info@materialiperledilizia.com | +393291815548 |  |  |
| SIC-ID-000000001778 | Tutto Per l'edilizia di lillo curseri |  | Tutto Per l'edilizia di lillo curseri | lillocurseri@hotmail.it | +393401260443 |  |  |
| SIC-ID-000000001779 | Ulded61 |  |  | ulded61@gmail.com | +393487347694 |  |  |
| SIC-ID-000000001780 | ULMA Construction Casseforme e Ponteggi |  | ULMA Construction Casseforme e Ponteggi | press@ulmaconstruction.es | +390457237900 |  |  |
| SIC-ID-000000001781 | UNIONCASA REGIONE LAZIO - ROMA |  | UNIONCASA REGIONE LAZIO - ROMA | lazio@unioncasa.org | +390637501058 |  |  |
| SIC-ID-000000001782 | Urbano Costruzioni di urbano gennaro - impresa edile |  | Urbano Costruzioni di urbano gennaro - impresa edile | gennaro.urbano@gmail.com | +393394218408 |  |  |
| SIC-ID-000000001783 | USD | Calcio |  | frassinellecalcio@gmail.com | +393687538359 |  |  |
| SIC-ID-000000001784 | Utensilferramenta Pistoiese spa |  | Utensilferramenta Pistoiese spa | marco.f@ufptrade.it | +393905739386 |  |  |
| SIC-ID-000000001785 | Utente |  |  | mf7640@alice.it | +393356369211 |  |  |
| SIC-ID-000000001786 | Vaccari Srl |  | Vaccari Srl | vaccaricostruzioni@gmail.com | +39053266225 |  |  |
| SIC-ID-000000001787 | Vaillant | Assistenza Roma |  | climagroupnewsrl@gmail.com | +393331980947 |  |  |
| SIC-ID-000000001788 | Vale | Falconi |  | vale.falconi@libero.it | +393200605914 |  |  |
| SIC-ID-000000001789 | Valentina |  |  | valentina.dussin@gmail.com | +393402156963 |  |  |
| SIC-ID-000000001790 | Valentinarabitti |  |  | valentinarabitti@gmail.com | +393939583753 |  |  |
| SIC-ID-000000001791 | Valeria | Mb coperture |  | valeria@zambonin.it | +3934712178980 |  |  |
| SIC-ID-000000001792 | valeria | pluti |  | valeria.pluti@gmail.com | +393287037116 |  |  |
| SIC-ID-000000001793 | Valeria Srl |  | Valeria Srl | valeria.tiozzo@sambin.com | +3934784361210 |  |  |
| SIC-ID-000000001794 | Valeriafra |  |  | valeriafra@virgilio.it | +393489246207 |  |  |
| SIC-ID-000000001795 | Valeriovolpini5 | Volpini |  | valeriovolpini5@gmail.com | +393459804152 |  |  |
| SIC-ID-000000001796 | Validazioni | Pellegrin |  | validazioni@anfos.it | +393389591205 |  |  |
| SIC-ID-000000001797 | Valm Edil Srl |  | Valm Edil Srl | info@valmedil.it | +390342558555 |  |  |
| SIC-ID-000000001798 | Valteo Costruzioni |  | Valteo Costruzioni | info@valteosrl.it | +393488610925 |  |  |
| SIC-ID-000000001799 | Valter | Orogel |  | vzino@orogel.it | +393395781720 |  |  |
| SIC-ID-000000001800 | Vanin Impianti Srl |  | Vanin Impianti Srl | info@vanin.net | +39041429754 |  |  |
| SIC-ID-000000001801 | Varagnolosara94 | Sv |  | varagnolosara94.sv@gmail.com | +393395350744 |  |  |
| SIC-ID-000000001802 | Varese Costruzioni Srl |  | Varese Costruzioni Srl | varesecostruzioni@gmail.com | +390332821327 |  |  |
| SIC-ID-000000001803 | Vargiu Clelia Srl di Murgia |  | Vargiu Clelia Srl di Murgia | vargiucleliasrl@tiscali.it | +39070740675 |  |  |
| SIC-ID-000000001804 | Varia Costruzioni |  | Varia Costruzioni | info@variacostruzioni.it | +390583511888 |  |  |
| SIC-ID-000000001805 | Vavassori |  |  | info@vavassoriedilizia.it | +39035661042 |  |  |
| SIC-ID-000000001806 | Venetoponteggi |  |  | venetoponteggi@gmail.com | +393407919593 |  |  |
| SIC-ID-000000001807 | Ver. | Color Parati |  | vercolorsrl@gmail.com | +39067810150 |  |  |
| SIC-ID-000000001808 | Verdetec | Giardiniere Roma Nord |  | lucapisanello@inwind.it | +393381093906 |  |  |
| SIC-ID-000000001809 | Verifiche impianti di terra di bevilacqua fabrizio |  | Verifiche impianti di terra di bevilacqua fabrizio | info@fabriziobevilacqua.it | +393899968494 |  |  |
| SIC-ID-000000001810 | Verniciatura Fratelli Brina Di Cristiano E Davide Brina S.N.C. |  | Verniciatura Fratelli Brina Di Cristiano E Davide Brina S.N.C. | info@verniciaturabrina.it | +39035636430 |  |  |
| SIC-ID-000000001811 | Verona | Restauri |  | info@veronarestauri.it | +393470154425 |  |  |
| SIC-ID-000000001812 | Veronese | Raffaele |  | veronese.raffaele@tiscali.it | +393281263830 |  |  |
| SIC-ID-000000001813 | Veronicaguiarssi |  |  | veronicaguiarssi@gmail.com | +393408744824 |  |  |
| SIC-ID-000000001814 | Vfrau85 |  |  | vfrau85@yahoo.it | +393346132534 |  |  |
| SIC-ID-000000001815 | Virgin Water Srl |  | Virgin Water Srl | info@virginwater.it | +393513582668 |  |  |
| SIC-ID-000000001816 | Vitalegiuseppe76 |  |  | vitalegiuseppe76@gmail.com | +393292239781 |  |  |
| SIC-ID-000000001817 | Vitaliano13 | Gennari |  | vitaliano13@gmail.com | +393457037638 |  |  |
| SIC-ID-000000001818 | Vito60000 |  |  | vito60000@live.it | +393713538202 |  |  |
| SIC-ID-000000001819 | Viviani | Rosa |  | v.mariarosa@alice.it | +393381211909 |  |  |
| SIC-ID-000000001820 | Volpato Costruzioni Srl |  | Volpato Costruzioni Srl | info@volcer.com | +390415138237 |  |  |
| SIC-ID-000000001821 | Volpi Costruzioni - impresa edile costruzioni e ristrutturazioni a parma |  | Volpi Costruzioni - impresa edile costruzioni e ristrutturazioni a parma | info@volpicostruzioni.it | +393356342478 |  |  |
| SIC-ID-000000001822 | Michele Voltan |  | Voltan Michele | michelevoltan@libero.it | +393383404328 | Loreo | RO |
| SIC-ID-000000001823 | Vultaggio G., vario v. & c. s.n.c. |  | Vultaggio G., vario v. & c. s.n.c. | info@vultaggioevario.it | +39092421094 |  |  |
| SIC-ID-000000001824 | Vultaggio Srl |  | Vultaggio Srl | info@vultaggio.it | +390923833499 |  |  |
| SIC-ID-000000001825 | Wallet | Blumatica |  | wallet@rendimentoetico.it | +393289134522 |  |  |
| SIC-ID-000000001826 | Wassim | Geometri. |  | wassimmeftah700@gmail.com | +3932780130700 |  |  |
| SIC-ID-000000001827 | Weber G e D Ingrosso Materiali Edili |  | Weber G e D Ingrosso Materiali Edili | lauren.ae.bowers@gmail.com | +39095338014 |  |  |
| SIC-ID-000000001828 | Webinar | Nicolas |  | webinar@scalpingwithbull.com | +393471501914 |  |  |
| SIC-ID-000000001829 | William Srl Costruzioni in Ferro |  | William Srl Costruzioni in Ferro | williamsrl@legalmail.it | +390652373209 |  |  |
| SIC-ID-000000001830 | Work | Edil |  | workedilsrl2017@gmail.com | +390815402578 |  |  |
| SIC-ID-000000001831 | Work Di gioia |  | Work Di gioia | work@kervinchery.com | +3934728224240 |  |  |
| SIC-ID-000000001832 | Z.D.L. Costruzioni Srl |  | Z.D.L. Costruzioni Srl | info@zdlcostruzioni.com | +393469554367 |  |  |
| SIC-ID-000000001833 | Z2g Costruzioni |  | Z2g Costruzioni | email@mail.com | +393339174067 |  |  |
| SIC-ID-000000001834 | Zambon | Riccardo |  | archeo1@live.it | +393354243430 |  |  |
| SIC-ID-000000001835 | Zanchi & C. S.N.C. |  | Zanchi & C. S.N.C. | matteo@zanchiedil.it | +39034591619 |  |  |
| SIC-ID-000000001836 | Zanibellato Impresa edile |  | Zanibellato Impresa edile | zanibellatomariosas@libero.it | +393357073519 |  |  |
| SIC-ID-000000001837 | Zaninelloantonio |  |  | zaninelloantonio@gmail.com | +393291992963 |  |  |
| SIC-ID-000000001838 | Zanzi | Ivana |  | zanzi.ivana@gmail.com | +393484850361 |  |  |
| SIC-ID-000000001839 | ZapTech |  |  | info@zaptech.it | +393338444145 |  |  |
| SIC-ID-000000001840 | Zavalele | Zavattiero |  | zavalele@gmail.com | +393477906223 |  |  |
| SIC-ID-000000001841 | Zavan Supplies Srl |  | Zavan Supplies Srl | info@zavanforniture.it | +39041429377 |  |  |
| SIC-ID-000000001842 | Zennaro | Antonio Mazzon |  | beatricemazzon06@gmail.com | +393400921799 |  |  |
| SIC-ID-000000001843 | Zennaro Costruzioni srl |  | Zennaro Costruzioni srl | info@zennarocostruzioni.it | +390415630190 |  |  |
| SIC-ID-000000001844 | Zerbini | Cons Adige Euganeo |  | leonardo.zerbini@adigeuganeo.it | +393482888272 |  |  |
| SIC-ID-000000001845 | Zeta costruzioni srl |  | Zeta costruzioni srl | info@zetacostruzioniancona.it | +390719735603 |  |  |
| SIC-ID-000000001846 | Zeta Due - impresa edile zeta due srl |  | Zeta Due - impresa edile zeta due srl | info@zetadue.com | +390422757736 |  |  |
| SIC-ID-000000001847 | Zieromauro |  |  | zieromauro@hotmail.it | +393488594447 |  |  |
| SIC-ID-000000001848 | Zingafer |  |  | info@zingafer.it | +390957513375 |  |  |
| SIC-ID-000000001849 | ZM Termoidraulica |  | ZM Termoidraulica | zmtermoidro@gmail.com | +390421242563 |  |  |
| SIC-ID-000000001850 | Zuliani Impresa edile albinea |  | Zuliani Impresa edile albinea | impresa@zuliani.re.it | +390522347159 |  |  |
| SIC-ID-000000001851 | Zust | Ambrosetti |  | yu.aihua@zust.it | +39390252541 |  |  |
| SIC-ID-000000001852 | Zuzolo | Roberto71 |  | zuzolo.roberto71@gmail.com | +393891797670 |  |  |

## LISTA 2 — USER81+ Solo SMS/WA (0 user)

_Nessun user in questa lista al momento. Struttura pronta: si popola
automaticamente quando arrivano contatti con telefono ma senza email._

## POOL — Prospect solo email (nurturing) (2460 user)

| SIC-ID | Nome | Cognome | Azienda | Email | Citta | Prov |
|---|---|---|---|---|---|---|
| SIC-ID-000000001853 |  |  |  | info@pipitonealberto.it |  |  |
| SIC-ID-000000001854 |  |  |  | amministrazione.glenda@studipozzato.it |  |  |
| SIC-ID-000000001855 |  |  |  | amministrazione@baccaroshoes.it |  |  |
| SIC-ID-000000001856 |  |  |  | amministrazione@costruzionidegiuli.it |  |  |
| SIC-ID-000000001857 |  |  |  | amministrazione@elettrofor.it |  |  |
| SIC-ID-000000001858 |  |  |  | amministrazione@macmanagement.it |  |  |
| SIC-ID-000000001859 |  |  |  | amministrazione@nuovatipografia.it |  |  |
| SIC-ID-000000001860 |  |  |  | amministrazione@padovaclima.it |  |  |
| SIC-ID-000000001861 |  |  |  | amministrazione@studioaeditecne.it |  |  |
| SIC-ID-000000001862 |  |  |  | amministrazione@synthesis-srl.com |  |  |
| SIC-ID-000000001863 |  |  |  | e.romeo@calcestruzzi.it |  |  |
| SIC-ID-000000001864 |  |  |  | f.zanatta@calcestruzzi.it |  |  |
| SIC-ID-000000001865 |  |  |  | info@a08.it |  |  |
| SIC-ID-000000001866 |  |  |  | info@adriaticaimmobiliare.eu |  |  |
| SIC-ID-000000001867 |  |  |  | info@agentidoma.it |  |  |
| SIC-ID-000000001868 |  |  |  | info@agenziaorizzonti.it |  |  |
| SIC-ID-000000001869 |  |  |  | info@agriturismoilleccio.it |  |  |
| SIC-ID-000000001870 |  |  |  | info@aifos.it |  |  |
| SIC-ID-000000001871 |  |  |  | info@aistardigital.it |  |  |
| SIC-ID-000000001872 |  |  |  | info@allesfisch.it |  |  |
| SIC-ID-000000001873 |  |  |  | info@alpenboys.it |  |  |
| SIC-ID-000000001874 |  |  |  | info@altoadriatiko.it |  |  |
| SIC-ID-000000001875 |  |  |  | info@angeloruggeri.it |  |  |
| SIC-ID-000000001876 |  |  |  | info@aqua-consult.it |  |  |
| SIC-ID-000000001877 |  |  |  | info@arcleonardo.com |  |  |
| SIC-ID-000000001878 |  |  |  | info@artigianatopadovano.it |  |  |
| SIC-ID-000000001879 |  |  |  | info@assistedil.it |  |  |
| SIC-ID-000000001880 |  |  |  | info@begossistudio.com |  |  |
| SIC-ID-000000001881 |  |  |  | info@beltramebevande.it |  |  |
| SIC-ID-000000001882 |  |  |  | info@blue-tomato.com |  |  |
| SIC-ID-000000001883 |  |  |  | info@bountyrimini.it |  |  |
| SIC-ID-000000001884 |  |  |  | info@braviassociati.com |  |  |
| SIC-ID-000000001885 |  |  |  | info@bridgman.it |  |  |
| SIC-ID-000000001886 |  |  |  | info@brimar.it |  |  |
| SIC-ID-000000001887 |  |  |  | info@brixiamoto.it |  |  |
| SIC-ID-000000001888 |  |  |  | info@caa-srl.com |  |  |
| SIC-ID-000000001889 |  |  |  | info@campanellicostruzioni.it |  |  |
| SIC-ID-000000001890 |  |  |  | info@carloegiorgio.it |  |  |
| SIC-ID-000000001891 |  |  |  | info@carpenterieferrari.com |  |  |
| SIC-ID-000000001892 |  |  |  | info@carservicelusia.it |  |  |
| SIC-ID-000000001893 |  |  |  | info@cecchettiningegneria.it |  |  |
| SIC-ID-000000001894 |  |  |  | info@cerealdocks.it |  |  |
| SIC-ID-000000001895 |  |  |  | info@cgtmarchitetti.it |  |  |
| SIC-ID-000000001896 |  |  |  | info@chacarero.it |  |  |
| SIC-ID-000000001897 |  |  |  | info@chipcore.eu |  |  |
| SIC-ID-000000001898 |  |  |  | info@climoglass.com |  |  |
| SIC-ID-000000001899 |  |  |  | info@clodiaforniture.it |  |  |
| SIC-ID-000000001900 |  |  |  | info@cmitalia.it |  |  |
| SIC-ID-000000001901 |  |  |  | info@containergroup.it |  |  |
| SIC-ID-000000001902 |  |  |  | info@coop-life.it |  |  |
| SIC-ID-000000001903 |  |  |  | info@corona-ferrea.it |  |  |
| SIC-ID-000000001904 |  |  |  | info@corsicryptovalute.it |  |  |
| SIC-ID-000000001905 |  |  |  | info@costruzioniedilferro.com |  |  |
| SIC-ID-000000001906 |  |  |  | info@costruzioniedilferro.it |  |  |
| SIC-ID-000000001907 |  |  |  | info@covolo.it |  |  |
| SIC-ID-000000001908 |  |  |  | info@cpmshop.it |  |  |
| SIC-ID-000000001909 |  |  |  | info@ctstrasporti.it |  |  |
| SIC-ID-000000001910 |  |  |  | info@cuoreiberico.it |  |  |
| SIC-ID-000000001911 |  |  |  | info@danieliecurto.it |  |  |
| SIC-ID-000000001912 |  |  |  | info@dbarchitetto.com |  |  |
| SIC-ID-000000001913 |  |  |  | info@deltaconsulting.it |  |  |
| SIC-ID-000000001914 |  |  |  | info@diegoinmusica.it |  |  |
| SIC-ID-000000001915 |  |  |  | info@dima-ve.com |  |  |
| SIC-ID-000000001916 |  |  |  | info@dimensionemoto.net |  |  |
| SIC-ID-000000001917 |  |  |  | info@dipiustudio.com |  |  |
| SIC-ID-000000001918 |  |  |  | info@distilleriemantovani.it |  |  |
| SIC-ID-000000001919 |  |  |  | info@dittabrignoli.it |  |  |
| SIC-ID-000000001920 |  |  |  | info@donegacostruzioni.it |  |  |
| SIC-ID-000000001921 |  |  |  | info@ebvenetofvg.it |  |  |
| SIC-ID-000000001922 |  |  |  | info@ecovie.it |  |  |
| SIC-ID-000000001923 |  |  |  | info@edilfuturosnc.it |  |  |
| SIC-ID-000000001924 |  |  |  | info@egointernational.it |  |  |
| SIC-ID-000000001925 |  |  |  | info@ekoore.com |  |  |
| SIC-ID-000000001926 |  |  |  | info@elearningsicurezza.com |  |  |
| SIC-ID-000000001927 |  |  |  | info@electrolight.it |  |  |
| SIC-ID-000000001928 |  |  |  | info@elegantgift.it |  |  |
| SIC-ID-000000001929 |  |  |  | info@emanueleferrarese.it |  |  |
| SIC-ID-000000001930 |  |  |  | info@emmegirisarcimenti.com |  |  |
| SIC-ID-000000001931 |  |  |  | info@energeticosrl.it |  |  |
| SIC-ID-000000001932 |  |  |  | info@eredirossini.it |  |  |
| SIC-ID-000000001933 |  |  |  | info@eridania.191.it |  |  |
| SIC-ID-000000001934 |  |  |  | info@erreservices.it |  |  |
| SIC-ID-000000001935 |  |  |  | info@eshirt.it |  |  |
| SIC-ID-000000001936 |  |  |  | info@essecimultiservice.com |  |  |
| SIC-ID-000000001937 |  |  |  | info@esteticamente.eu |  |  |
| SIC-ID-000000001938 |  |  |  | info@esteticanadia.it |  |  |
| SIC-ID-000000001939 |  |  |  | info@euromusicart.com |  |  |
| SIC-ID-000000001940 |  |  |  | info@evolutionforum.sm |  |  |
| SIC-ID-000000001941 |  |  |  | info@evomatic.it |  |  |
| SIC-ID-000000001942 |  |  |  | info@faenaedilizia.it |  |  |
| SIC-ID-000000001943 |  |  |  | info@fanchinsrl.it |  |  |
| SIC-ID-000000001944 |  |  |  | info@fastweb.it |  |  |
| SIC-ID-000000001945 |  |  |  | info@fdastrutture.com |  |  |
| SIC-ID-000000001946 |  |  |  | info@federicodigiorgi.it |  |  |
| SIC-ID-000000001947 |  |  |  | info@fnaantincendio.it |  |  |
| SIC-ID-000000001948 |  |  |  | info@franceschetti-pulizie.it |  |  |
| SIC-ID-000000001949 |  |  |  | info@frankcadillacmagic.it |  |  |
| SIC-ID-000000001950 |  |  |  | info@fuenteflamenca.com |  |  |
| SIC-ID-000000001951 |  |  |  | info@gardacarpentieri.it |  |  |
| SIC-ID-000000001952 |  |  |  | info@geometrapuozzo.it |  |  |
| SIC-ID-000000001953 |  |  |  | info@geopalitalia.com |  |  |
| SIC-ID-000000001954 |  |  |  | info@girotto.it |  |  |
| SIC-ID-000000001955 |  |  |  | info@giustiniani.net |  |  |
| SIC-ID-000000001956 |  |  |  | info@grafi-cartsrl.com |  |  |
| SIC-ID-000000001957 |  |  |  | info@groupsgvcaminetti.it |  |  |
| SIC-ID-000000001958 |  |  |  | info@gruppomedis.com |  |  |
| SIC-ID-000000001959 |  |  |  | info@gtridello.it |  |  |
| SIC-ID-000000001960 |  |  |  | info@guardianservice.com |  |  |
| SIC-ID-000000001961 |  |  |  | info@guardianservizi.com |  |  |
| SIC-ID-000000001962 |  |  |  | info@gumpab.com |  |  |
| SIC-ID-000000001963 |  |  |  | info@ham-burger.it |  |  |
| SIC-ID-000000001964 |  |  |  | info@hastudio.it |  |  |
| SIC-ID-000000001965 |  |  |  | info@henriette-gioielli.com |  |  |
| SIC-ID-000000001966 |  |  |  | info@hiperformance.it |  |  |
| SIC-ID-000000001967 |  |  |  | info@homefenster.it |  |  |
| SIC-ID-000000001968 |  |  |  | info@hotelbaiaflaminia.com |  |  |
| SIC-ID-000000001969 |  |  |  | info@hotelesagono.com |  |  |
| SIC-ID-000000001970 |  |  |  | info@hoteleuropa.rn.it |  |  |
| SIC-ID-000000001971 |  |  |  | info@hotelgrisu.com |  |  |
| SIC-ID-000000001972 |  |  |  | info@hotelolympia.ro.it |  |  |
| SIC-ID-000000001973 |  |  |  | info@hotelumbertorosolina.com |  |  |
| SIC-ID-000000001974 |  |  |  | info@hotrodgrills.eu |  |  |
| SIC-ID-000000001975 |  |  |  | info@immaginails.it |  |  |
| SIC-ID-000000001976 |  |  |  | info@immobiliare-mediocasa.it |  |  |
| SIC-ID-000000001977 |  |  |  | info@immobiliarecasini.it |  |  |
| SIC-ID-000000001978 |  |  |  | info@immobiliareexcelsior.it |  |  |
| SIC-ID-000000001979 |  |  |  | info@ioparomatiche.it |  |  |
| SIC-ID-000000001980 |  |  |  | info@iptonline.it |  |  |
| SIC-ID-000000001981 |  |  |  | info@irseuropa.it |  |  |
| SIC-ID-000000001982 |  |  |  | info@islem.it |  |  |
| SIC-ID-000000001983 |  |  |  | info@ivoavidhold.com |  |  |
| SIC-ID-000000001984 |  |  |  | info@keepitsimple.it |  |  |
| SIC-ID-000000001985 |  |  |  | info@khe-sc.com |  |  |
| SIC-ID-000000001986 |  |  |  | info@labettoladelbuttero.com |  |  |
| SIC-ID-000000001987 |  |  |  | info@laboclodia.com |  |  |
| SIC-ID-000000001988 |  |  |  | info@labotecnic.com |  |  |
| SIC-ID-000000001989 |  |  |  | info@lacasadelviaggio.it |  |  |
| SIC-ID-000000001990 |  |  |  | info@lagentile.eu |  |  |
| SIC-ID-000000001991 |  |  |  | info@lakegardawind.com |  |  |
| SIC-ID-000000001992 |  |  |  | info@ledscreenstore.it |  |  |
| SIC-ID-000000001993 |  |  |  | info@leonefell.com |  |  |
| SIC-ID-000000001994 |  |  |  | info@lisandri.it |  |  |
| SIC-ID-000000001995 |  |  |  | info@lmalimentare.it |  |  |
| SIC-ID-000000001996 |  |  |  | info@lorcastyle.it |  |  |
| SIC-ID-000000001997 |  |  |  | info@lucamantovani.it |  |  |
| SIC-ID-000000001998 |  |  |  | info@lycloud.info |  |  |
| SIC-ID-000000001999 |  |  |  | info@maciprevenzione.it |  |  |
| SIC-ID-000000002000 |  |  |  | info@magdaconfezioni.com |  |  |
| SIC-ID-000000002001 |  |  |  | info@magoandrea.it |  |  |
| SIC-ID-000000002002 |  |  |  | info@marazambon.it |  |  |
| SIC-ID-000000002003 |  |  |  | info@masterbdesign.it |  |  |
| SIC-ID-000000002004 |  |  |  | info@mbtectum.it |  |  |
| SIC-ID-000000002005 |  |  |  | info@mentalitamilionariand.com |  |  |
| SIC-ID-000000002006 |  |  |  | info@mgrpavimenti.eu |  |  |
| SIC-ID-000000002007 |  |  |  | info@michelebonivento.com |  |  |
| SIC-ID-000000002008 |  |  |  | info@miguelteam.com |  |  |
| SIC-ID-000000002009 |  |  |  | info@misureacustiche.it |  |  |
| SIC-ID-000000002010 |  |  |  | info@modena-antonio.it |  |  |
| SIC-ID-000000002011 |  |  |  | info@motofactory.it |  |  |
| SIC-ID-000000002012 |  |  |  | info@motorfactory.it |  |  |
| SIC-ID-000000002013 |  |  |  | info@multimed.it |  |  |
| SIC-ID-000000002014 |  |  |  | info@murercommercialisti.it |  |  |
| SIC-ID-000000002015 |  |  |  | info@mycashbackbot.com |  |  |
| SIC-ID-000000002016 |  |  |  | info@navarriniarchitetti.it |  |  |
| SIC-ID-000000002017 |  |  |  | info@negriricevimenti.com |  |  |
| SIC-ID-000000002018 |  |  |  | info@newcalorsystem.it |  |  |
| SIC-ID-000000002019 |  |  |  | info@nuovacasadellosterzo.com |  |  |
| SIC-ID-000000002020 |  |  |  | info@nuovamarinasirenella.it |  |  |
| SIC-ID-000000002021 |  |  |  | info@obmsrl.com |  |  |
| SIC-ID-000000002022 |  |  |  | info@officeteamsrl.com |  |  |
| SIC-ID-000000002023 |  |  |  | info@officine-parenti.it |  |  |
| SIC-ID-000000002024 |  |  |  | info@olivatoassociati.it |  |  |
| SIC-ID-000000002025 |  |  |  | info@one-fiber.it |  |  |
| SIC-ID-000000002026 |  |  |  | info@or-service.biz |  |  |
| SIC-ID-000000002027 |  |  |  | info@pellicciasrl.it |  |  |
| SIC-ID-000000002028 |  |  |  | info@petraimpianti.it |  |  |
| SIC-ID-000000002029 |  |  |  | info@pirotecnicaarquatese.it |  |  |
| SIC-ID-000000002030 |  |  |  | info@playadelsol.eu |  |  |
| SIC-ID-000000002031 |  |  |  | info@promofiereverona.com |  |  |
| SIC-ID-000000002032 |  |  |  | info@pubblifest.com |  |  |
| SIC-ID-000000002033 |  |  |  | info@punto3arredamenti.it |  |  |
| SIC-ID-000000002034 |  |  |  | info@recostruzioni.it |  |  |
| SIC-ID-000000002035 |  |  |  | info@reef.it |  |  |
| SIC-ID-000000002036 |  |  |  | info@rendimentoetico.it |  |  |
| SIC-ID-000000002037 |  |  |  | info@ri-carica.com |  |  |
| SIC-ID-000000002038 |  |  |  | info@rigato.net |  |  |
| SIC-ID-000000002039 |  |  |  | info@romeagraf.it |  |  |
| SIC-ID-000000002040 |  |  |  | info@rossante.it |  |  |
| SIC-ID-000000002041 |  |  |  | info@rotoinfissioni.it |  |  |
| SIC-ID-000000002042 |  |  |  | info@sabbatinisrl.com |  |  |
| SIC-ID-000000002043 |  |  |  | info@safestudio.it |  |  |
| SIC-ID-000000002044 |  |  |  | info@salataglio-teamgreen.com |  |  |
| SIC-ID-000000002045 |  |  |  | info@sanadent.it |  |  |
| SIC-ID-000000002046 |  |  |  | info@sangiulianocompany.it |  |  |
| SIC-ID-000000002047 |  |  |  | info@schiesaricatering.it |  |  |
| SIC-ID-000000002048 |  |  |  | info@serramenticasaservice.it |  |  |
| SIC-ID-000000002049 |  |  |  | info@serramentitomasini.it |  |  |
| SIC-ID-000000002050 |  |  |  | info@sgrace.it |  |  |
| SIC-ID-000000002051 |  |  |  | info@sixte.it |  |  |
| SIC-ID-000000002052 |  |  |  | info@skaj.it |  |  |
| SIC-ID-000000002053 |  |  |  | info@sotecasrl.it |  |  |
| SIC-ID-000000002054 |  |  |  | info@spaziaperti.com |  |  |
| SIC-ID-000000002055 |  |  |  | info@stereocarpd.it |  |  |
| SIC-ID-000000002056 |  |  |  | info@strikesurfshop.it |  |  |
| SIC-ID-000000002057 |  |  |  | info@studio-facco.it |  |  |
| SIC-ID-000000002058 |  |  |  | info@studioa2.org |  |  |
| SIC-ID-000000002059 |  |  |  | info@studioagm.eu |  |  |
| SIC-ID-000000002060 |  |  |  | info@studioazzi.it |  |  |
| SIC-ID-000000002061 |  |  |  | info@studiobaruffaldi.com |  |  |
| SIC-ID-000000002062 |  |  |  | info@studiobertibizzotto.it |  |  |
| SIC-ID-000000002063 |  |  |  | info@studioborille.it |  |  |
| SIC-ID-000000002064 |  |  |  | info@studiocostanzo.com |  |  |
| SIC-ID-000000002065 |  |  |  | info@studiodallamutta.it |  |  |
| SIC-ID-000000002066 |  |  |  | info@studiofimi.it |  |  |
| SIC-ID-000000002067 |  |  |  | info@studiofiorinibarbara.it |  |  |
| SIC-ID-000000002068 |  |  |  | info@studiofurlandebora.it |  |  |
| SIC-ID-000000002069 |  |  |  | info@studiogigli.it |  |  |
| SIC-ID-000000002070 |  |  |  | info@studiolegalefois.it |  |  |
| SIC-ID-000000002071 |  |  |  | info@studiolippiassociato.com |  |  |
| SIC-ID-000000002072 |  |  |  | info@studiom6.it |  |  |
| SIC-ID-000000002073 |  |  |  | info@studiomassarotto.191.it |  |  |
| SIC-ID-000000002074 |  |  |  | info@studiomicucciebonello.it |  |  |
| SIC-ID-000000002075 |  |  |  | info@studiopadovanefranchini.it |  |  |
| SIC-ID-000000002076 |  |  |  | info@studiopram.ve.it |  |  |
| SIC-ID-000000002077 |  |  |  | info@studiotognin.it |  |  |
| SIC-ID-000000002078 |  |  |  | info@studiotomasello.com |  |  |
| SIC-ID-000000002079 |  |  |  | info@studiotonon.com |  |  |
| SIC-ID-000000002080 |  |  |  | info@surftolive.com |  |  |
| SIC-ID-000000002081 |  |  |  | info@svmedicalservice.it |  |  |
| SIC-ID-000000002082 |  |  |  | info@synthesis-srl.com |  |  |
| SIC-ID-000000002083 |  |  |  | info@techneprogetti.com |  |  |
| SIC-ID-000000002084 |  |  |  | info@tecnoclimap.com |  |  |
| SIC-ID-000000002085 |  |  |  | info@tecnocopy.eu |  |  |
| SIC-ID-000000002086 |  |  |  | info@tettolares.com |  |  |
| SIC-ID-000000002087 |  |  |  | info@tfeingegneria.it |  |  |
| SIC-ID-000000002088 |  |  |  | info@thehypergame.com |  |  |
| SIC-ID-000000002089 |  |  |  | info@tiepoloviaggi.com |  |  |
| SIC-ID-000000002090 |  |  |  | info@tommycar.it |  |  |
| SIC-ID-000000002091 |  |  |  | info@toscanocostruzioni.it |  |  |
| SIC-ID-000000002092 |  |  |  | info@trasecosrl.com |  |  |
| SIC-ID-000000002093 |  |  |  | info@tutto-vacanze.it |  |  |
| SIC-ID-000000002094 |  |  |  | info@venditaautomatica.com |  |  |
| SIC-ID-000000002095 |  |  |  | info@venetamassettisnc.it |  |  |
| SIC-ID-000000002096 |  |  |  | info@videofotochinaglia.it |  |  |
| SIC-ID-000000002097 |  |  |  | info@vienocnc.com |  |  |
| SIC-ID-000000002098 |  |  |  | info@waltercragnolin.it |  |  |
| SIC-ID-000000002099 |  |  |  | info@xharredocasa.it |  |  |
| SIC-ID-000000002100 |  |  |  | info@yeehaw3d.com |  |  |
| SIC-ID-000000002101 |  |  |  | info@zetaservice.net |  |  |
| SIC-ID-000000002102 |  |  |  | l.battan@avvecomm.it |  |  |
| SIC-ID-000000002103 |  |  |  | segreteria@agefis.it |  |  |
| SIC-ID-000000002104 |  |  |  | segreteria@consorziocics.com |  |  |
| SIC-ID-000000002105 |  |  |  | segreteria@istitutodocet.it |  |  |
| SIC-ID-000000002106 |  |  |  | segreteria@itineragroup.it |  |  |
| SIC-ID-000000002107 |  |  |  | segreteria@laboclodia.com |  |  |
| SIC-ID-000000002108 |  |  |  | segreteria@nservizi.it |  |  |
| SIC-ID-000000002109 |  |  |  | segreteria@reef.it |  |  |
| SIC-ID-000000002110 |  |  |  | servizioclienti@support.expedia.it |  |  |
| SIC-ID-000000002111 |  |  |  | supporto@anfos.it |  |  |
| SIC-ID-000000002112 |  |  |  | supporto@bbq4all.it |  |  |
| SIC-ID-000000002113 |  |  |  | supporto@omnia.video |  |  |
| SIC-ID-000000002114 |  |  |  | depenalizzazione.pref_rovigo@interno.it |  |  |
| SIC-ID-000000002115 |  |  |  | 3388389343m@gmail.com |  |  |
| SIC-ID-000000002116 |  |  |  | boscaratoedilizia_sas@legalmail.it |  |  |
| SIC-ID-000000002117 |  |  |  | coop.airone@legalmail.it |  |  |
| SIC-ID-000000002118 |  |  |  | crifillsrl@legalmail.it |  |  |
| SIC-ID-000000002119 |  |  |  | falconi.marco95@gmail.com |  |  |
| SIC-ID-000000002120 |  |  |  | llponzettosrl@legalmail.it |  |  |
| SIC-ID-000000002121 |  |  |  | marco.bizziato@stema.it |  |  |
| SIC-ID-000000002122 |  |  |  | marco.costa@euroansa.it |  |  |
| SIC-ID-000000002123 |  |  |  | marco.tiozzo@ferlintiozzo.com |  |  |
| SIC-ID-000000002124 |  |  |  | marcobenini@ymail.com |  |  |
| SIC-ID-000000002125 |  |  |  | marcocastellanowork@gmail.com |  |  |
| SIC-ID-000000002126 |  |  |  | marcoginapri71@gmail.com |  |  |
| SIC-ID-000000002127 |  |  |  | marcotanduo@gmail.com |  |  |
| SIC-ID-000000002128 |  |  |  | marcovservizi@gmail.com |  |  |
| SIC-ID-000000002129 |  |  |  | oriana.bianchini@legalmail.it |  |  |
| SIC-ID-000000002130 |  |  |  | piersimonimarco1@gmail.com |  |  |
| SIC-ID-000000002131 |  |  |  | schettinonadia@legalmail.it |  |  |
| SIC-ID-000000002132 |  |  |  | sottozerogelateria.pt@gmail.com |  |  |
| SIC-ID-000000002133 |  |  |  | 10820712+cq4ks@tickets.livechatinc.com |  |  |
| SIC-ID-000000002134 |  |  |  | 2394647009-fyqj.mv9z.rtjw.9drs@property.booking.com |  |  |
| SIC-ID-000000002135 |  |  |  | casinialberto77@gmail.com |  |  |
| SIC-ID-000000002136 |  |  |  | commissione.patenti@aulss5.veneto.it |  |  |
| SIC-ID-000000002137 |  |  |  | dipendenze.tagliodipo@aulss5.veneto.it |  |  |
| SIC-ID-000000002138 |  |  |  | lombar_mzwp4329qg@members.ebay.it |  |  |
| SIC-ID-000000002139 |  |  |  | marco.pizza333@gmail.com |  |  |
| SIC-ID-000000002140 |  |  |  | mfdq4xbmsvtny5s@marketplace.amazon.it |  |  |
| SIC-ID-000000002141 |  |  |  | mgnwkf18f57mkc9@marketplace.amazon.it |  |  |
| SIC-ID-000000002142 |  |  |  | nkj95mtkl4bwc8l@marketplace.amazon.it |  |  |
| SIC-ID-000000002143 |  |  |  | not-5967f403-9cbf-456f-8e79-cb86add5e9b7@expmessaging.tripadvisor.com |  |  |
| SIC-ID-000000002144 |  |  |  | reply-fe5d1272706400747d14-200_html-477827634-10901677-81141@e.viator.com |  |  |
| SIC-ID-000000002145 |  |  |  | rois00200a@istruzione.it |  |  |
| SIC-ID-000000002146 |  |  |  | rois011005@istruzione.it |  |  |
| SIC-ID-000000002147 |  |  |  | rois01300r@istruzione.it |  |  |
| SIC-ID-000000002148 |  |  |  | sunriselab24@gmail.com |  |  |
| SIC-ID-000000002149 |  |  |  | v4vpbckf2bwfdmd@marketplace.amazon.it |  |  |
| SIC-ID-000000002150 |  |  |  | vc0sxp2wds72cgz@marketplace.amazon.it |  |  |
| SIC-ID-000000002151 |  |  |  | info@grandimolini.it |  |  |
| SIC-ID-000000002152 |  |  |  | info@marcobizzotto.it |  |  |
| SIC-ID-000000002153 |  |  |  | info@marcocastelli.org |  |  |
| SIC-ID-000000002154 |  |  |  | info@marcoepippo.com |  |  |
| SIC-ID-000000002155 |  |  |  | info@marcogavioli.com |  |  |
| SIC-ID-000000002156 |  |  |  | info@marcospanio.it |  |  |
| SIC-ID-000000002157 |  |  |  | marcodaurelio1@gmail.com |  |  |
| SIC-ID-000000002158 |  |  |  | 0120001@mmfgshops.com |  |  |
| SIC-ID-000000002159 |  |  |  | amministrazione@studiotecnicoomega.it |  |  |
| SIC-ID-000000002160 |  |  |  | avvocati@marensi.it |  |  |
| SIC-ID-000000002161 |  |  |  | avv.giorgia.giannini@tiscali.it |  |  |
| SIC-ID-000000002162 |  |  |  | avv.marcopietropolli@gmail.com |  |  |
| SIC-ID-000000002163 |  |  |  | bernardinello.marco@libero.it |  |  |
| SIC-ID-000000002164 |  |  |  | bellan_marco@hotmail.it |  |  |
| SIC-ID-000000002165 |  |  |  | enrico.greghi@hotmail.it |  |  |
| SIC-ID-000000002166 |  |  |  | fabrismarcoimpianti@libero.it |  |  |
| SIC-ID-000000002167 |  |  |  | frrmarco@yahoo.it |  |  |
| SIC-ID-000000002168 |  |  |  | garbellini.marco@gmail.com |  |  |
| SIC-ID-000000002169 |  |  |  | geomarcoleoni@tiscali.it |  |  |
| SIC-ID-000000002170 |  |  |  | info@studiotecnico-elisaandreasi.it |  |  |
| SIC-ID-000000002171 |  |  |  | info@studiotecnicogpa.it |  |  |
| SIC-ID-000000002172 |  |  |  | lggrandi@gmail.com |  |  |
| SIC-ID-000000002173 |  |  |  | marco@studiopaghesrl.it |  |  |
| SIC-ID-000000002174 |  |  |  | marco.coletto@studioprofessionisti3lune.it |  |  |
| SIC-ID-000000002175 |  |  |  | marco.destro@anticimex.it |  |  |
| SIC-ID-000000002176 |  |  |  | marco.marconi.mailbox@gmail.com |  |  |
| SIC-ID-000000002177 |  |  |  | marco.pietro@libero.it |  |  |
| SIC-ID-000000002178 |  |  |  | marco.siciliani@attivamenteonlus.it |  |  |
| SIC-ID-000000002179 |  |  |  | marco3sa@libero.it |  |  |
| SIC-ID-000000002180 |  |  |  | plservicesrl@legalmail.it |  |  |
| SIC-ID-000000002181 |  |  |  | avvsilviadinapoli@gmail.com |  |  |
| SIC-ID-000000002182 |  |  |  | marco.anzini71@gmail.com |  |  |
| SIC-ID-000000002183 |  |  |  | marco.lavagetto@freelance-studio.it |  |  |
| SIC-ID-000000002184 |  |  |  | robertodegregorio69@gmail.com |  |  |
| SIC-ID-000000002185 |  |  |  | morenagrandi67@gmail.com |  |  |
| SIC-ID-000000002186 |  |  |  | emanuellimarco@libero.it |  |  |
| SIC-ID-000000002187 |  |  |  | galiottomarco@gmail.com |  |  |
| SIC-ID-000000002188 |  |  |  | cassageometri@geopec.it |  |  |
| SIC-ID-000000002189 |  |  |  | benignocostruzioni@unapec.it |  |  |
| SIC-ID-000000002190 |  |  |  | info@specialitamucciestaccioli.it |  |  |
| SIC-ID-000000002191 |  |  |  | marco@ingegneriamilani.it |  |  |
| SIC-ID-000000002192 |  |  |  | edilmoderna.primo@gmail.com |  |  |
| SIC-ID-000000002193 |  |  |  | zeroarchitettura@gmail.com |  |  |
| SIC-ID-000000002194 | 2 Emme impresa edile |  | 2 Emme impresa edile | mm2emme@yahoo.it |  |  |
| SIC-ID-000000002195 |  |  | 2T SCAVI S.R.L. | 2tscavi1@gmail.com | Taglio di Po | RO |
| SIC-ID-000000002196 | A | Dissette |  | a.dissette@archiworld.it |  |  |
| SIC-ID-000000002197 | A | Ferrari315 |  | a.ferrari315@gmail.com |  |  |
| SIC-ID-000000002198 | A | Ferro |  | a.ferro@costruzioniedilferro.com |  |  |
| SIC-ID-000000002199 | A | Fortin |  | a.fortin@csworks.it |  |  |
| SIC-ID-000000002200 | A | Frigato |  | a.frigato@inwind.it |  |  |
| SIC-ID-000000002201 | A | Marangon |  | a.marangon@costruzioniedilferro.com |  |  |
| SIC-ID-000000002202 | A | Mischiari |  | a.mischiari@gmail.com |  |  |
| SIC-ID-000000002203 | A | Sgualdo |  | a.sgualdo@inwind.it |  |  |
| SIC-ID-000000002204 | A. | Energieeffizienzlösungen |  | caraviello@eness.ch |  |  |
| SIC-ID-000000002205 | Aadf |  |  | aadf.info@gmail.com |  |  |
| SIC-ID-000000002206 | Aboumosaab26 |  |  | aboumosaab26@hotmail.it |  |  |
| SIC-ID-000000002207 | ACCA | Del Polito |  | luigi.delpolito@acca.it |  |  |
| SIC-ID-000000002208 | Acca Software spa |  | Acca Software spa | info@acca.it |  |  |
| SIC-ID-000000002209 | Adami | Beatrice |  | adamib@studiocavallari.info |  |  |
| SIC-ID-000000002210 | Adi-motta |  |  | adi-motta@hotmail.it |  |  |
| SIC-ID-000000002211 | Adriana | Semola |  | adriana.semola@cf.confart.tv |  |  |
| SIC-ID-000000002212 | AF | shop |  | af.interni@hotmail.it |  |  |
| SIC-ID-000000002213 | Afservicesrlu |  | Afservicesrlu | afservicesrlu@gmail.com |  |  |
| SIC-ID-000000002214 | Agenzia Allianz Pordenone centro |  | Agenzia Allianz Pordenone centro | agenzia@allianz.pn.it |  |  |
| SIC-ID-000000002215 | Agenzia Mare |  | Agenzia Mare | info@deltamare.com |  |  |
| SIC-ID-000000002216 | Agenzia Web |  | Agenzia Web | info@agenziacasaweb.it |  |  |
| SIC-ID-000000002217 | Agirmo |  |  | agirmo@infovacanze.it |  |  |
| SIC-ID-000000002218 | Agitatorec |  |  | agitatorec@inwind.it |  |  |
| SIC-ID-000000002219 | Agostinoviola |  |  | agostinoviola@inwind.it |  |  |
| SIC-ID-000000002220 | Agri Scavi |  | Agri Scavi | agri.scavi@libero.it |  |  |
| SIC-ID-000000002221 | AGRISCAVI di Bellan Luca |  | AGRISCAVI di Bellan Luca | info@agriscavi.it | Porto Viro | RO |
| SIC-ID-000000002222 | Nome Cognome email telefono id cliente backup_sicu |  | AGRYTEK SRL | agrytek@libero.it | Porto Viro | RO |
| SIC-ID-000000002223 | Aizitel07 |  |  | aizitel07@libero.it |  |  |
| SIC-ID-000000002224 | Akiram | 74 |  | akiram.74@virgilio.it |  |  |
| SIC-ID-000000002225 | Albe | Rocc |  | albe.rocc@yahoo.it |  |  |
| SIC-ID-000000002226 | Albertiluciano |  |  | albertiluciano@libero.it |  |  |
| SIC-ID-000000002227 | Alberto |  |  | morettoalberto@commercialeferramenta.it |  |  |
| SIC-ID-000000002228 | Alberto |  |  | albertobenetti1@virgilio.it |  |  |
| SIC-ID-000000002229 | Alberto |  |  | albertodeiacobis@gmail.com |  |  |
| SIC-ID-000000002230 | Alberto Ascomrovigo |  |  | alberto.ascomrovigo@gmail.com |  |  |
| SIC-ID-000000002231 | Alberto Casini |  |  | alberto@studioercolini.it |  |  |
| SIC-ID-000000002232 | Alberto Geom |  |  | alberto.76@libero.it |  |  |
| SIC-ID-000000002233 | Alberto Pagan |  |  | alberto.pagan@crveneto.it |  |  |
| SIC-ID-000000002234 | Alberto Sannini |  |  | alberto.sannini@email.it |  |  |
| SIC-ID-000000002235 | Alberto Vianello |  |  | alberto.vianello@vdv.it |  |  |
| SIC-ID-000000002236 | Alberto88siviero |  |  | alberto88siviero@alice.it |  |  |
| SIC-ID-000000002237 | Albertopreviato |  |  | albertopreviato@libero.it |  |  |
| SIC-ID-000000002238 | Albertovenezia |  |  | albertovenezia@mac.com |  |  |
| SIC-ID-000000002239 | Ale | Miozzi |  | ale.miozzi@tiscali.it |  |  |
| SIC-ID-000000002240 | Alessandra |  |  | alessandra@studioassociatobbc.it |  |  |
| SIC-ID-000000002241 | Alessandro | calamante |  | info@acgroupsrls.it |  |  |
| SIC-ID-000000002242 | Alessandromorelli |  |  | alessandromorelli@inwind.it |  |  |
| SIC-ID-000000002243 | Alessia_maselli |  |  | alessia_maselli@virgilio.it |  |  |
| SIC-ID-000000002244 | Alex Management |  | Alex Management | info@alexvanni.com |  |  |
| SIC-ID-000000002245 | Alexandra | Gamma |  | alexandra@studiogammaonline.it |  |  |
| SIC-ID-000000002246 | Alexs |  |  | alex1970s@libero.it |  |  |
| SIC-ID-000000002247 | Alfonso |  |  | alfonso.martello@aslnapoli2nord.it |  |  |
| SIC-ID-000000002248 | Alfonsogorirossi |  |  | alfonsogorirossi@virgilio.it |  |  |
| SIC-ID-000000002249 |  |  | ALLAM ABDELLATIF | allam.abdellatif@pec.it | BADIA POLESINE | RO |
| SIC-ID-000000002250 | Almainferro |  | ALMA S.n.c. di Pertegato Alessandro e Iodice Marco | almainferro@hotmail.com | Padova | PD |
| SIC-ID-000000002251 | Alxdc |  |  | alxdc@yahoo.it |  |  |
| SIC-ID-000000002252 | AM Impianti |  | AM Impianti | service@amimpianti.tech |  |  |
| SIC-ID-000000002253 | Amministrazione |  |  | amministrazione@ascomrovigo.it |  |  |
| SIC-ID-000000002254 | Amministrazione |  |  | amministrazione@promofiereverona.com |  |  |
| SIC-ID-000000002255 | Amministrazione | Tizè |  | amministrazione@tize.it |  |  |
| SIC-ID-000000002256 | Amoreamaro | Bar |  | amoreamaro.bar@gmail.com |  |  |
| SIC-ID-000000002257 | Ampeliobos |  |  | ampeliobos@alice.it |  |  |
| SIC-ID-000000002258 | Andrea - Guadagnare le case |  | Andrea - Guadagnare le case | assistenza@guadagnareconlecase.it |  |  |
| SIC-ID-000000002259 | Andreonecristina |  |  | andreonecristina@yahoo.it |  |  |
| SIC-ID-000000002260 | Angela |  |  | angelina.giacometti@istruzione.it |  |  |
| SIC-ID-000000002261 | Angela |  |  | angela@studiodieci.info |  |  |
| SIC-ID-000000002262 | Angelina | Soravia |  | angelina.soravia@gmail.com |  |  |
| SIC-ID-000000002263 | Anipa |  |  | anipa@anipa.it |  |  |
| SIC-ID-000000002264 | Anna |  |  | anna@annaritagelasio.it |  |  |
| SIC-ID-000000002265 | Anna | Antonelli1959 |  | anna.antonelli1959@gmail.com |  |  |
| SIC-ID-000000002266 | Anna | Aurelio1989 |  | anna.aurelio1989@gmail.com |  |  |
| SIC-ID-000000002267 | Anna | Delprete1980 |  | anna.delprete1980@gmail.com |  |  |
| SIC-ID-000000002268 | Anna | Didonato |  | anna.didonato@outlook.com |  |  |
| SIC-ID-000000002269 | Anna | Olimpia |  | a.olimpia@tiscali.it |  |  |
| SIC-ID-000000002270 | Anna | Vy |  | anna.vy@libero.it |  |  |
| SIC-ID-000000002271 | Anna | Zompa |  | anna.zompa@virgilio.it |  |  |
| SIC-ID-000000002272 | Anna_sanfilippo64 |  |  | anna_sanfilippo64@libero.it |  |  |
| SIC-ID-000000002273 | Annadauria | D'auria |  | annadauria@libero.it |  |  |
| SIC-ID-000000002274 | Annafranzese51 |  |  | annafranzese51@gmail.com |  |  |
| SIC-ID-000000002275 | Annalisa |  |  | 25annalisa@gmail.com |  |  |
| SIC-ID-000000002276 | Annalisa | Zampini |  | annalisa.zampini@tin.it |  |  |
| SIC-ID-000000002277 | Annamaria | Altieri |  | annamaria.altieri@gmail.com |  |  |
| SIC-ID-000000002278 | Annapisanelli |  |  | annapisanelli@gmail.com |  |  |
| SIC-ID-000000002279 | Annascarpato9 |  |  | annascarpato9@gmail.com |  |  |
| SIC-ID-000000002280 | Antonio | Oltramari |  | antonio.oltramari@emic.it |  |  |
| SIC-ID-000000002281 | Apesorridente |  |  | apesorridente@hotmail.com |  |  |
| SIC-ID-000000002282 | Aps Costruzioni |  | Aps Costruzioni | info@apcostruzioni.it |  |  |
| SIC-ID-000000002283 | Arben | Kola |  | arben.kola@gmail.com |  |  |
| SIC-ID-000000002284 | Arch | C cost |  | arch.c.cost@gmail.com |  |  |
| SIC-ID-000000002285 | Arch | Digennaroalina |  | arch.digennaroalina@gmail.com |  |  |
| SIC-ID-000000002286 | Arch | Fioravanti |  | arch.fioravanti@gmail.com |  |  |
| SIC-ID-000000002287 | Arch | Lauraboscoloforcola |  | arch.lauraboscoloforcola@gmail.com |  |  |
| SIC-ID-000000002288 | Arch | Marica paparella |  | arch.marica.paparella@gmail.com |  |  |
| SIC-ID-000000002289 | Arch | Nataleromeo |  | arch.nataleromeo@gmail.com |  |  |
| SIC-ID-000000002290 | Arch | Sargiacomo |  | arch.sargiacomo@gmail.com |  |  |
| SIC-ID-000000002291 | Arch | Tomassettipaolo |  | arch.tomassettipaolo@gmail.com |  |  |
| SIC-ID-000000002292 | Archiluigibarbato |  |  | archiluigibarbato@gmail.com |  |  |
| SIC-ID-000000002293 | Archistudio Lasfera |  | Archistudio Lasfera | archistudio.lasfera@tiscali.it |  |  |
| SIC-ID-000000002294 | Architettozanardi |  |  | architettozanardi@libero.it |  |  |
| SIC-ID-000000002295 | Arkimiki76 | D'andrea |  | arkimiki76@gmail.com |  |  |
| SIC-ID-000000002296 | Armyat |  |  | armyat@libero.it |  |  |
| SIC-ID-000000002297 | Arniani | Monica |  | monica@cpmshop.it |  |  |
| SIC-ID-000000002298 | Assistenza |  |  | assistenza@staff.aruba.it |  |  |
| SIC-ID-000000002299 | Assistenza | GSE |  | assistenzaportaleapplicativi@gse.it |  |  |
| SIC-ID-000000002300 | Atlanta76 |  |  | atlanta76@libero.it |  |  |
| SIC-ID-000000002301 | Attilio |  |  | attilio@mastudio.it |  |  |
| SIC-ID-000000002302 | Attrezzature | AiFOS |  | attrezzature@aifos.it |  |  |
| SIC-ID-000000002303 | Aulettadebora |  |  | aulettadebora@live.com |  |  |
| SIC-ID-000000002304 | Autospurghi | F.lli Consiglio |  | marcoconsiglio75@libero.it |  |  |
| SIC-ID-000000002305 | Averie | com |  | averie@cryptocom.intercom-mail.com |  |  |
| SIC-ID-000000002306 | Avv | Valentinavalenti |  | avv.valentinavalenti@barzazi.com |  |  |
| SIC-ID-000000002307 |  |  | AXAT S.R.L. | axatsrl@pec.it | ROVIGO | RO |
| SIC-ID-000000002308 |  |  | AZZURRA COSTRUZIONI S.R.L. | azzcossrl@pec.it | Adria | RO |
| SIC-ID-000000002309 | AZZURRA SERVICE S.R.L. |  | AZZURRA SERVICE S.R.L. | azzurraservice27@legalmail.it | Chioggia | VE |
| SIC-ID-000000002310 | B | Pregnolato |  | b.pregnolato@tiscali.it |  |  |
| SIC-ID-000000002311 | B&b S.n.c. |  | B&B S.n.c. | info@beblattonerie.it | Porto Viro | RO |
| SIC-ID-000000002312 | Baccanscavi |  | BACCAN SCAVI di Baccan Alessio | baccanscavi@libero.it | Pettorazza Grimani | RO |
| SIC-ID-000000002313 | Bacci | Tiziana |  | bacci.tiziana@gmail.com |  |  |
| SIC-ID-000000002314 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessandro.piva-86@hotmail.it |  |  |
| SIC-ID-000000002315 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antonella.pezzolato@gmail.com |  |  |
| SIC-ID-000000002316 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | ammboxsrls@gmail.com |  |  |
| SIC-ID-000000002317 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | avidholdevento@gmail.com |  |  |
| SIC-ID-000000002318 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | b.biagi@hiperformance.it |  |  |
| SIC-ID-000000002319 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | arch.destefani@tiscali.it |  |  |
| SIC-ID-000000002320 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | ambulatorio.vianelli@libero.it |  |  |
| SIC-ID-000000002321 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alialbakheet_2019@yahoo.com |  |  |
| SIC-ID-000000002322 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessandra.sguotti@enaip.veneto.it |  |  |
| SIC-ID-000000002323 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | ambrapol2005@yahoo.it |  |  |
| SIC-ID-000000002324 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessandra@studiocoletto.net |  |  |
| SIC-ID-000000002325 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | arch.anita.bergamini@gmail.com |  |  |
| SIC-ID-000000002326 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | aldodangeloprizzi@libero.it |  |  |
| SIC-ID-000000002327 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | arch.d.volpin@libero.it |  |  |
| SIC-ID-000000002328 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alovato@notabene.it |  |  |
| SIC-ID-000000002329 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | annaconti94@gmail.com |  |  |
| SIC-ID-000000002330 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | annaboscolomeo@libero.it |  |  |
| SIC-ID-000000002331 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | architetti@ambrosimarangoni.it |  |  |
| SIC-ID-000000002332 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | archimurano@libero.it |  |  |
| SIC-ID-000000002333 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | archicase@libero.it |  |  |
| SIC-ID-000000002334 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | archemiliotrame@tiscali.it |  |  |
| SIC-ID-000000002335 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessiadiana@yahoo.it |  |  |
| SIC-ID-000000002336 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessandro@studiocuman.it |  |  |
| SIC-ID-000000002337 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | annabella.castelli@tiscali.it |  |  |
| SIC-ID-000000002338 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alemilani9@libero.it |  |  |
| SIC-ID-000000002339 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | arch.roberto.pavan@gmail.com |  |  |
| SIC-ID-000000002340 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | arch.nikbarde@outlook.it |  |  |
| SIC-ID-000000002341 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | anna.fregugia@bancobpm.it |  |  |
| SIC-ID-000000002342 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessandro.mingozzi@libero.it |  |  |
| SIC-ID-000000002343 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | arch.moroncini@gmail.com |  |  |
| SIC-ID-000000002344 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | arch.matteoferro@hotmail.it |  |  |
| SIC-ID-000000002345 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessandra.zampaolo@gmail.com |  |  |
| SIC-ID-000000002346 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antonellocapellato@gmail.com |  |  |
| SIC-ID-000000002347 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | admin@nicoladomini.com |  |  |
| SIC-ID-000000002348 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | affiliazioni@anfos.it |  |  |
| SIC-ID-000000002349 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | amore.diluna66@gmail.com |  |  |
| SIC-ID-000000002350 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessandro.asingegneria@gmail.com |  |  |
| SIC-ID-000000002351 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | airdropmcc@protonmail.com |  |  |
| SIC-ID-000000002352 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | anna.sturaro@enaip.veneto.it |  |  |
| SIC-ID-000000002353 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alvin33@libero.it |  |  |
| SIC-ID-000000002354 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | aiellorosaria71@gmail.com |  |  |
| SIC-ID-000000002355 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andreaptmail@alice.it |  |  |
| SIC-ID-000000002356 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | ade@studiolaurenti.com |  |  |
| SIC-ID-000000002357 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | ada.sanarica@alice.it |  |  |
| SIC-ID-000000002358 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | activestudio.formazione@gmail.com |  |  |
| SIC-ID-000000002359 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | administration@isfmworldwide.com |  |  |
| SIC-ID-000000002360 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antonino.triscari@cassaragionieri.it |  |  |
| SIC-ID-000000002361 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | arch.cervini1@gmail.com |  |  |
| SIC-ID-000000002362 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antonio@studioferranteaporti.it |  |  |
| SIC-ID-000000002363 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | assunta.giannattasio@gmail.com |  |  |
| SIC-ID-000000002364 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | atanasiumarius68@gmail.com |  |  |
| SIC-ID-000000002365 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | adessololeggo@gmail.com |  |  |
| SIC-ID-000000002366 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | attilioderenzis1984@gmail.com |  |  |
| SIC-ID-000000002367 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | annalisamormile@hotmail.it |  |  |
| SIC-ID-000000002368 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | auricapartene@gmail.com |  |  |
| SIC-ID-000000002369 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | avianello@asmrovigo.it |  |  |
| SIC-ID-000000002370 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | axenievalentina@yahoo.com |  |  |
| SIC-ID-000000002371 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | azizf0443@gmail.com |  |  |
| SIC-ID-000000002372 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | studio@pozzati.net |  |  |
| SIC-ID-000000002373 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | support@carid.com |  |  |
| SIC-ID-000000002374 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | support@onean.com |  |  |
| SIC-ID-000000002375 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | ariannavianello@yahoo.it |  |  |
| SIC-ID-000000002376 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | august0buccomin0@gmail.com |  |  |
| SIC-ID-000000002377 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andreamigliorini76@gmail.com |  |  |
| SIC-ID-000000002378 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andreadebei@gmail.com |  |  |
| SIC-ID-000000002379 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antonina71@libero.it |  |  |
| SIC-ID-000000002380 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | anto.giorgi@email.it |  |  |
| SIC-ID-000000002381 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andmaz62@live.it |  |  |
| SIC-ID-000000002382 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | 2_emme_elettronica@libero.it |  |  |
| SIC-ID-000000002383 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | aladino.lorin@gmail.com |  |  |
| SIC-ID-000000002384 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alaskandelcolle@gmail.com |  |  |
| SIC-ID-000000002385 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alby.hoover@gmail.com |  |  |
| SIC-ID-000000002386 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | abortolato@libero.it |  |  |
| SIC-ID-000000002387 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | acannizzaro455@gmail.com |  |  |
| SIC-ID-000000002388 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | advice@2sconsulting.eu |  |  |
| SIC-ID-000000002389 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | acquisti@guardianlavori.it |  |  |
| SIC-ID-000000002390 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antonelladevincenzo@hotmail.it |  |  |
| SIC-ID-000000002391 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | agenzia55.capannori@gmail.com |  |  |
| SIC-ID-000000002392 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | agiovannig@gmail.com |  |  |
| SIC-ID-000000002393 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | adrycavaliere@hotmail.it |  |  |
| SIC-ID-000000002394 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | adrimusicservice@gmail.com |  |  |
| SIC-ID-000000002395 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antonellaalfonso@hotelsangiuan.it |  |  |
| SIC-ID-000000002396 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | adriatica.srl@virgilio.it |  |  |
| SIC-ID-000000002397 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andreaemusica@tin.it |  |  |
| SIC-ID-000000002398 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | acavagnini@libero.it |  |  |
| SIC-ID-000000002399 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | adria@acstudiolex.it |  |  |
| SIC-ID-000000002400 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andreamalacarne82@gmail.com |  |  |
| SIC-ID-000000002401 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | support@pixartprinting.com |  |  |
| SIC-ID-000000002402 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | arianna.studioruffato@gmail.com |  |  |
| SIC-ID-000000002403 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | aglio82@libero.it |  |  |
| SIC-ID-000000002404 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alex.melloni@gmail.com |  |  |
| SIC-ID-000000002405 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | armandosalerno1984@libero.it |  |  |
| SIC-ID-000000002406 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | ashraftiba8@gmail.com |  |  |
| SIC-ID-000000002407 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | annagasparro04@virgilio.it |  |  |
| SIC-ID-000000002408 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | architetto.bosi@gmail.com |  |  |
| SIC-ID-000000002409 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alessiodeluca.designer@gmail.com |  |  |
| SIC-ID-000000002410 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alemiaz@live.it |  |  |
| SIC-ID-000000002411 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alejandrosaorin@gmail.com |  |  |
| SIC-ID-000000002412 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | anna.campion@live.it |  |  |
| SIC-ID-000000002413 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | angy.ake@gmail.com |  |  |
| SIC-ID-000000002414 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | ali.homayoni93@gmail.com |  |  |
| SIC-ID-000000002415 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | arch.cappato@libero.it |  |  |
| SIC-ID-000000002416 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andreavettore@alice.it |  |  |
| SIC-ID-000000002417 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | arch.augusti@studioarchitetturaleonardo.it |  |  |
| SIC-ID-000000002418 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | aleing29@libero.it |  |  |
| SIC-ID-000000002419 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | arch.crepaldi@ambiterr.it |  |  |
| SIC-ID-000000002420 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | ale.pre81@gmail.com |  |  |
| SIC-ID-000000002421 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | ale.calca@yahoo.it |  |  |
| SIC-ID-000000002422 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | aldomassimo65@gmail.com |  |  |
| SIC-ID-000000002423 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | apveneziano@libero.it |  |  |
| SIC-ID-000000002424 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | anzolettimichele@gmail.com |  |  |
| SIC-ID-000000002425 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | arco_studio@tin.it |  |  |
| SIC-ID-000000002426 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | armigliato.andrea@libero.it |  |  |
| SIC-ID-000000002427 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | aromasrl@virgilio.it |  |  |
| SIC-ID-000000002428 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antwiisaac444@gmail.com |  |  |
| SIC-ID-000000002429 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | assistenza@sambin.com |  |  |
| SIC-ID-000000002430 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | archvaleriogibin@libero.it |  |  |
| SIC-ID-000000002431 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | architettocalzavara@libero.it |  |  |
| SIC-ID-000000002432 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | allamyassin8@gmail.com |  |  |
| SIC-ID-000000002433 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | assocfidi@libero.it |  |  |
| SIC-ID-000000002434 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | assistenza@sogert.it |  |  |
| SIC-ID-000000002435 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | annasucato@gmail.com |  |  |
| SIC-ID-000000002436 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | arseniorapeggia@libero.it |  |  |
| SIC-ID-000000002437 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | artigianato@ccsa-castelfranco.191.it |  |  |
| SIC-ID-000000002438 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | artimpianti.snc@libero.it |  |  |
| SIC-ID-000000002439 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | artisti.leonardo@studipozzato.it |  |  |
| SIC-ID-000000002440 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | armando.71cotugno@gmail.com |  |  |
| SIC-ID-000000002441 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | alexthebig@hotmail.com |  |  |
| SIC-ID-000000002442 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | artur.karenych1995@gmail.com |  |  |
| SIC-ID-000000002443 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | ascanioferrara1@virgilio.it |  |  |
| SIC-ID-000000002444 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | asd.marlinone@libero.it |  |  |
| SIC-ID-000000002445 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | architetto.dargenio@gmail.com |  |  |
| SIC-ID-000000002446 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | artlegno.giurgi@yahoo.it |  |  |
| SIC-ID-000000002447 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | antaro1964@libero.it |  |  |
| SIC-ID-000000002448 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | assisten.studiobanin@libero.it |  |  |
| SIC-ID-000000002449 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | assistenza@acca.it |  |  |
| SIC-ID-000000002450 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | assistenza@alfiobardolla.com |  |  |
| SIC-ID-000000002451 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | assistenza@prontopro.it |  |  |
| SIC-ID-000000002452 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | andres.benito@surfnatur.com |  |  |
| SIC-ID-000000002453 | Backup_sicurissimo | Engine v1_2026-02-24_03-38 estr |  | arkstudio.marzolla@libero.it |  |  |
| SIC-ID-000000002454 | Baglionisax |  |  | baglionisax@libero.it |  |  |
| SIC-ID-000000002455 | Bagnohappydays |  |  | bagnohappydays@gmail.com |  |  |
| SIC-ID-000000002456 | Bailo | Luca |  | bailo.luca@gmail.com |  |  |
| SIC-ID-000000002457 | Bakhitgahli |  |  | bakhitgahli@gmail.com |  |  |
| SIC-ID-000000002458 | Baldachini | Loris |  | baldachini.loris@libero.it |  |  |
| SIC-ID-000000002459 | Balgu2007 |  |  | balgu2007@alice.it |  |  |
| SIC-ID-000000002460 | Baninmanolo |  |  | baninmanolo@libero.it |  |  |
| SIC-ID-000000002461 | Barbarabellonivox |  |  | barbarabellonivox@gmail.com |  |  |
| SIC-ID-000000002462 | Barca | Lg |  | barca.lg@libero.it |  |  |
| SIC-ID-000000002463 | Bardellaprogetti |  |  | bardellaprogetti@libero.it |  |  |
| SIC-ID-000000002464 | Barfiori |  |  | barfiori@tin.it |  |  |
| SIC-ID-000000002465 | Barnaalina |  |  | barnaalina@libero.it |  |  |
| SIC-ID-000000002466 | Baronma |  |  | baronma@libero.it |  |  |
| SIC-ID-000000002467 | Barottiginasas |  | Barottiginasas | barottiginasas@gmail.com |  |  |
| SIC-ID-000000002468 | Bartolomeolarosa98 | Larosa |  | bartolomeolarosa98@gmail.com |  |  |
| SIC-ID-000000002469 | Giuseppebaruffaldi Giuseppe |  | Baruffaldi Giuseppe | giuseppebaruffaldi@gmail.com | Porto Viro | RO |
| SIC-ID-000000002470 | Baruffaldi Mariagrazia |  | Baruffaldi Mariagrazia | baruffaldi.mariagrazia@gmail.com |  |  |
| SIC-ID-000000002471 | Basstef72 |  |  | basstef72@gmail.com |  |  |
| SIC-ID-000000002472 | Battan |  |  | s_battan@hotmail.it |  |  |
| SIC-ID-000000002473 |  |  | BATTAN BENEDETTA | battanbenedetta@pec.it | Rosolina | RO |
| SIC-ID-000000002474 | Battistagallo |  |  | battistagallo@gmail.com |  |  |
| SIC-ID-000000002475 | Battistini | Dario |  | battistini.dario@libero.it |  |  |
| SIC-ID-000000002476 | Bdavid69 |  |  | bdavid69@tiscali.it |  |  |
| SIC-ID-000000002477 | Bdocmatteo |  |  | bdocmatteo@gmail.com |  |  |
| SIC-ID-000000002478 | Bebobestmusic |  |  | bebobestmusic@gmail.com |  |  |
| SIC-ID-000000002479 | Bedeiroberta |  |  | bedeiroberta@gmail.com |  |  |
| SIC-ID-000000002480 | Bedonml |  |  | bedonml@libero.it |  |  |
| SIC-ID-000000002481 | Bellaselbe |  |  | bellaselbe@gmail.com |  |  |
| SIC-ID-000000002482 | Bellato |  |  | abellato64@gmail.com |  |  |
| SIC-ID-000000002483 | Bellatoalberto |  |  | bellatoalberto@hotmail.com |  |  |
| SIC-ID-000000002484 | Bellemoeri |  |  | bellemoernani@libero.it |  |  |
| SIC-ID-000000002485 | Belliade |  |  | belliade@alice.it |  |  |
| SIC-ID-000000002486 | Beppe |  |  | beppe@givemotions.it |  |  |
| SIC-ID-000000002487 | Beppetv |  |  | beppe59tv@libero.it |  |  |
| SIC-ID-000000002488 | Berganton | Mauro |  | berganton.mauro@libero.it |  |  |
| SIC-ID-000000002489 | Bergantongiorgio |  |  | bergantongiorgio@gmail.com |  |  |
| SIC-ID-000000002490 | Bernardinello |  |  | c_bernardinello@tin.it |  |  |
| SIC-ID-000000002491 | Bertarellilucadjberta |  |  | bertarellilucadjberta@gmail.com |  |  |
| SIC-ID-000000002492 | Bertazza | Narciso |  | bertazza.narciso@libero.it |  |  |
| SIC-ID-000000002493 | Berti |  |  | berti@fregugliasrl.it |  |  |
| SIC-ID-000000002494 | Bertilla | P |  | bertilla.p@alice.it |  |  |
| SIC-ID-000000002495 | Bertolaso | Paolo |  | bertolaso.paolo@libero.it |  |  |
| SIC-ID-000000002496 | Berton | Luca |  | berton.luca@virgilio.it |  |  |
| SIC-ID-000000002497 | Best | Bologna |  | info@towerhotelbologna.com |  |  |
| SIC-ID-000000002498 | Bestwestern |  |  | bestwestern@express.medallia.com |  |  |
| SIC-ID-000000002499 | Better |  |  | better@excite.it |  |  |
| SIC-ID-000000002500 | Bettineschi | Federico |  | bettineschi.federico@atervenezia.it |  |  |
| SIC-ID-000000002501 | Bettonora |  |  | bettonora@live.it |  |  |
| SIC-ID-000000002502 | Bfferrari |  |  | bfferrari@libero.it |  |  |
| SIC-ID-000000002503 | BGY | IMPORT |  | bgyhubimport@dhl.com |  |  |
| SIC-ID-000000002504 | Bianchinistudio |  | Bianchinistudio | bianchinistudio@yahoo.it |  |  |
| SIC-ID-000000002505 | Bibiservice2003 |  | Bibiservice2003 | bibiservice2003@gmail.com |  |  |
| SIC-ID-000000002506 | Biemme |  |  | info.biemme@libero.it |  |  |
| SIC-ID-000000002507 | Billing |  |  | billing@zoom.us |  |  |
| SIC-ID-000000002508 | Binabo |  |  | binabo@alice.it |  |  |
| SIC-ID-000000002509 | Bioedil | Casa |  | info@gpcostruzionisrl.com |  |  |
| SIC-ID-000000002510 | Biomarket | Talamona |  | info@biomarketsrl.com |  |  |
| SIC-ID-000000002511 | Bisaglia | Ottorino |  | bisaglia.ottorino@libero.it |  |  |
| SIC-ID-000000002512 | Bivoln1 |  |  | bivoln1@gmail.com |  |  |
| SIC-ID-000000002513 | Bluetek |  |  | bluetek@libero.it |  |  |
| SIC-ID-000000002514 | Bmax76 |  |  | bmax76@alice.it |  |  |
| SIC-ID-000000002515 | Boato | Progetti |  | boato.progetti@libero.it |  |  |
| SIC-ID-000000002516 | Bobsammy463 |  |  | bobsammy463@gmail.com |  |  |
| SIC-ID-000000002517 | Bobymarkovic |  |  | bobymarkovic@gmail.com |  |  |
| SIC-ID-000000002518 | Bodea76 |  |  | bodea76@yahoo.it |  |  |
| SIC-ID-000000002519 | Boggiani | Franco |  | boggiani.franco@libero.it |  |  |
| SIC-ID-000000002520 | Bollati | Mirella |  | bollati.mirella@gmail.com |  |  |
| SIC-ID-000000002521 | Bonafincostruzioni |  | BONAFIN FRANCESCO | bonafincostruzioni@gmail.com | ARIANO NEL POLESINE | RO |
| SIC-ID-000000002522 | Bonaldomatteo |  |  | bonaldomatteo@tiscali.it |  |  |
| SIC-ID-000000002523 | Bonculescusorincostantin | Bonculescu |  | bonculescusorincostantin@gmail.com |  |  |
| SIC-ID-000000002524 | Bondi Alessandro |  | Bondi Alessandro | bondi.alessandro@gmail.com |  |  |
| SIC-ID-000000002525 | Booking |  |  | booking@lifeclass.net |  |  |
| SIC-ID-000000002526 | Booking | Roma |  | booking.roma@campusx.it |  |  |
| SIC-ID-000000002527 | Borghipneumatici |  |  | borghipneumatici@gmail.com |  |  |
| SIC-ID-000000002528 | Boscaratoedilizia Edilizia s.a.s di boscarato samuele & c. |  | Boscarato Edilizia S.a.s di Boscarato Samuele & C. | boscaratoedilizia@gmail.com | Chioggia | VE |
| SIC-ID-000000002529 | Boscolomanuel75 |  |  | boscolomanuel75@gmail.com |  |  |
| SIC-ID-000000002530 | Boscolopelo | Mauro |  | boscolopelo.mauro@tiscali.it |  |  |
| SIC-ID-000000002531 | Botti | Roberta66 |  | botti.roberta66@virgilio.it |  |  |
| SIC-ID-000000002532 | Bovolenta | L |  | bovolenta.l@gmail.com |  |  |
| SIC-ID-000000002533 | Braga | Gozzo |  | braga.gozzo@yahoo.it |  |  |
| SIC-ID-000000002534 | Bredalorenza |  |  | bredalorenza@gmail.com |  |  |
| SIC-ID-000000002535 | Bresciani | Bellan |  | susy@impresabrescianisrl.it |  |  |
| SIC-ID-000000002536 | Brina | Tatiana |  | brina.tatiana@gmail.com |  |  |
| SIC-ID-000000002537 | Brini60 |  |  | brini60@gmail.com |  |  |
| SIC-ID-000000002538 | Brunobuttini |  |  | brunobuttini@icloud.com |  |  |
| SIC-ID-000000002539 | Btstrasporti |  |  | btstrasporti@gmail.com |  |  |
| SIC-ID-000000002540 | Bulferetti |  |  | bulferetti@yahoo.it |  |  |
| SIC-ID-000000002541 | Bumarix |  |  | bumarix@tiscali.it |  |  |
| SIC-ID-000000002542 | Buoso-sasso |  | Buoso-sasso | buoso-sasso@libero.it |  |  |
| SIC-ID-000000002543 | Bury | Tattoo Marco Pregnolato |  | marco.buriolo@libero.it |  |  |
| SIC-ID-000000002544 | Busatto | Dario |  | dittabusatto@libero.it |  |  |
| SIC-ID-000000002545 | Businesssupport |  |  | businesssupport@soldo.com |  |  |
| SIC-ID-000000002546 | C | Bossi |  | c.bossi@italcementi.it |  |  |
| SIC-ID-000000002547 | C | Braghin |  | c.braghin@hotmail.it |  |  |
| SIC-ID-000000002548 | C | Cerchiari |  | c.cerchiari@tin.it |  |  |
| SIC-ID-000000002549 | C | Fioratto |  | c.fioratto@fiorattomascellani.it |  |  |
| SIC-ID-000000002550 | C | Garcia |  | c.garcia@onean.com |  |  |
| SIC-ID-000000002551 | C. | Viro |  | cssportoviro@aulss5.veneto.it |  |  |
| SIC-ID-000000002552 | Segreteria CICS - Studio Pozzato |  | C.A.M. - Cooperativa Artisti Musicali Soc. Coop. A r.l. | consorzio.cics@gmail.com | Taglio di Po | RO |
| SIC-ID-000000002553 |  |  | C.M. Costruzioni Manfrin di Manfrin Fabrizio | manfrin.fabrizio@pec.it | Chioggia | VE |
| SIC-ID-000000002554 | CRAME |  | C.R.A.M.E. S.c.a.r.l. | coopcrame@tin.it | Chioggia | VE |
| SIC-ID-000000002555 | Caa Srl78 |  | Caa Srl78 | caa.srl78@gmail.com |  |  |
| SIC-ID-000000002556 | Cafaq |  |  | caf001aq@gmail.com |  |  |
| SIC-ID-000000002557 | Caio |  |  | mo03@infopec.cassaedile.it |  |  |
| SIC-ID-000000002558 | Calcestruzzi | Contabilità |  | contabilita.fornitori@calcestruzzi.it |  |  |
| SIC-ID-000000002559 | Calcio | UISP |  | calcio.rovigo@uisp.it |  |  |
| SIC-ID-000000002560 | Calvaresi Servicelab |  | Calvaresi Servicelab | calvaresi.servicelab@libero.it |  |  |
| SIC-ID-000000002561 | Cam. | Care |  | support@cam.tv |  |  |
| SIC-ID-000000002562 | Camillafranci8 |  |  | camillafranci8@gmail.com |  |  |
| SIC-ID-000000002563 | Camisotti |  |  | camisotti@tiscalinet.it |  |  |
| SIC-ID-000000002564 | Camlauretta |  |  | camlauretta@libero.it |  |  |
| SIC-ID-000000002565 | Campacimagrini |  | Campacimagrini | campacimagrini@tin.it |  |  |
| SIC-ID-000000002566 | Campacisrl |  | Campacisrl | campacisrl@libero.it |  |  |
| SIC-ID-000000002567 | Campigotto | M |  | campigotto.m@gmail.com |  |  |
| SIC-ID-000000002568 | Campion | Mauro |  | campion.mauro@gmail.com |  |  |
| SIC-ID-000000002569 | Campion | Sara |  | campion.sara@gmail.com |  |  |
| SIC-ID-000000002570 | Capodaglio |  |  | capodaglio@gteing.com |  |  |
| SIC-ID-000000002571 | Cappellato | SME |  | antonello.cappellato@sme.illumia.it |  |  |
| SIC-ID-000000002572 | capsule | houses |  | usinfo@etonghouses.com |  |  |
| SIC-ID-000000002573 | capsule | houses |  | info@etonghouses.com |  |  |
| SIC-ID-000000002574 | Carbonera | Federico |  | carbonera.federico@gmail.com |  |  |
| SIC-ID-000000002575 | Carcolombo70 |  |  | carcolombo70@gmail.com |  |  |
| SIC-ID-000000002576 | Cardsupport |  |  | cardsupport@coinbase.com |  |  |
| SIC-ID-000000002577 | Carla | Siboni |  | carla.siboni@gmail.com |  |  |
| SIC-ID-000000002578 | Carlaa231113 | Alexandra abrantes coelho |  | carlaa231113@gmail.com |  |  |
| SIC-ID-000000002579 | Carlaintoppa72 |  |  | carlaintoppa72@gmail.com |  |  |
| SIC-ID-000000002580 | Carlapaparella |  |  | carlapaparella@libero.it |  |  |
| SIC-ID-000000002581 | Carlaservizioagenti |  | Carlaservizioagenti | carlaservizioagenti@gmail.com |  |  |
| SIC-ID-000000002582 | Carlatartari | Mo |  | carlatartari.mo@gmail.com |  |  |
| SIC-ID-000000002583 | CARLO | MURA |  | dellamura@ieol.net |  |  |
| SIC-ID-000000002584 | Carlo | Albertini |  | carlo.albertini@cert.odcvenezia.it |  |  |
| SIC-ID-000000002585 | Carlo | Pagan |  | carlo.pagan@hastudio.it |  |  |
| SIC-ID-000000002586 | Carlocallegaro |  |  | carlocallegaro@gmail.com |  |  |
| SIC-ID-000000002587 | Carloluviner |  |  | carloluviner@carloluviner.it |  |  |
| SIC-ID-000000002588 | Carlotta | Camisotti |  | carlotta.camisotti@gmail.com |  |  |
| SIC-ID-000000002589 | Carlottabellan |  |  | carlottabellan@libero.it |  |  |
| SIC-ID-000000002590 | Carmelo | Maimone |  | carmelo.maimone@virgilio.it |  |  |
| SIC-ID-000000002591 | Carmelopiacent |  |  | carmelopiacent@libero.it |  |  |
| SIC-ID-000000002592 | Carmelotudisco54 | Tudisco |  | carmelotudisco54@gmail.com |  |  |
| SIC-ID-000000002593 | Carmen | Barsan |  | carmen.barsan@hotmail.com |  |  |
| SIC-ID-000000002594 | Carrozzeriasette |  |  | carrozzeriasette@libero.it |  |  |
| SIC-ID-000000002595 | Casetta Alberto |  |  | casetta_alberto@libero.it |  |  |
| SIC-ID-000000002596 | Casfra75 |  |  | casfra75@hotmail.com |  |  |
| SIC-ID-000000002597 | Cashback | Italy |  | accounting.it@cashback-solutions.com |  |  |
| SIC-ID-000000002598 | Cashback | Partner |  | accounting.it@cashbackworld.com |  |  |
| SIC-ID-000000002599 | Cashback | Partner |  | onlinepartner.it@cashbackworld.com |  |  |
| SIC-ID-000000002600 | Cassa | del Veneto |  | novita@comunicazione.intesasanpaolo.com |  |  |
| SIC-ID-000000002601 | Catiacarloni |  |  | catiacarloni@hotmail.it |  |  |
| SIC-ID-000000002602 | Cavazzana |  |  | cavazzana@tin.it |  |  |
| SIC-ID-000000002603 | Cbotta61 |  |  | cbotta61@gmail.com |  |  |
| SIC-ID-000000002604 | Cbudri |  |  | cbudri@michelangelost.com |  |  |
| SIC-ID-000000002605 | Ccsa | Castelfranco |  | ccsa.castelfranco@alice.it |  |  |
| SIC-ID-000000002606 | Cecchetto | Mauro |  | cecchetto.mauro@email.it |  |  |
| SIC-ID-000000002607 | Cedacvolpato |  |  | cedacvolpato@virgilio.it |  |  |
| SIC-ID-000000002608 | Celsocaramori |  |  | celsocaramori@tiscali.it |  |  |
| SIC-ID-000000002609 | Cencing |  |  | cencing@libero.it |  |  |
| SIC-ID-000000002610 | Cenna |  |  | cenna@cenna.it |  |  |
| SIC-ID-000000002611 | Centro | STS |  | info@centrodiformazionests.it |  |  |
| SIC-ID-000000002612 | Centro Commerciale Valfreddana |  | Centro Commerciale Valfreddana | info@ccvf.it |  |  |
| SIC-ID-000000002613 | Centrostudi |  |  | centrostudi@agefis.it |  |  |
| SIC-ID-000000002614 | Cer_max |  |  | cer_max@virgilio.it |  |  |
| SIC-ID-000000002615 | Ceronicostruzionisrl |  | Ceronicostruzionisrl | ceronicostruzionisrl@gmail.com |  |  |
| SIC-ID-000000002616 | Cezaravram28 | Avram |  | cezaravram28@gmail.com |  |  |
| SIC-ID-000000002617 | Cfagni |  |  | cfagni@yahoo.com |  |  |
| SIC-ID-000000002618 | Cgc Srl |  | Cgc Srl | cgc.srl@libero.it |  |  |
| SIC-ID-000000002619 | Chef-86 |  |  | chef-86@live.it |  |  |
| SIC-ID-000000002620 | Cheikhniang563 |  |  | cheikhniang563@gmail.com |  |  |
| SIC-ID-000000002621 | Chiara |  |  | tecnico@seftrum.com |  |  |
| SIC-ID-000000002622 | Chiaralucci |  |  | chiaralucci@virgilio.it |  |  |
| SIC-ID-000000002623 | Chicca | Bertolini |  | chicca.bertolini@libero.it |  |  |
| SIC-ID-000000002624 | Chiclamino95 |  |  | chiclamino95@gmail.com |  |  |
| SIC-ID-000000002625 | Chicumarga2005 |  |  | chicumarga2005@yahoo.it |  |  |
| SIC-ID-000000002626 | Chieregato | D |  | chieregato.d@libero.it |  |  |
| SIC-ID-000000002627 | Chimenim | 32 |  | chimenim.32@gmail.com |  |  |
| SIC-ID-000000002628 | Chioccia75 |  |  | chioccia75@hotmail.it |  |  |
| SIC-ID-000000002629 | Chiodimichele |  |  | chiodimichele@libero.it |  |  |
| SIC-ID-000000002630 | Chioggia | Rominatiozzo |  | chioggia.rominatiozzo@gmail.com |  |  |
| SIC-ID-000000002631 | Chioggiaortomercatoveneto |  |  | chioggiaortomercatoveneto@yahoo.it |  |  |
| SIC-ID-000000002632 | Chris |  |  | chris@mellowboards.com |  |  |
| SIC-ID-000000002633 | Christabel_50 |  |  | christabel_50@yahoo.com |  |  |
| SIC-ID-000000002634 | Christian |  |  | christian@tuttogare.com |  |  |
| SIC-ID-000000002635 | Christian | Mussati |  | c.mussati@gmail.com |  |  |
| SIC-ID-000000002636 | Christiancarlini82 |  |  | christiancarlini82@gmail.com |  |  |
| SIC-ID-000000002637 | Cianfagna | Luigi |  | cianfagna.luigi@gmail.com |  |  |
| SIC-ID-000000002638 | Ciaoelyz88 |  |  | ciaoelyz88@gmail.com |  |  |
| SIC-ID-000000002639 |  |  | CICCHETTERIA DA NINO FISOLO DI DIEGO ARDIZZON | cicchetteriadaninofisolo@pec.it | Chioggia | VE |
| SIC-ID-000000002640 | Cielleperon |  |  | cielleperon@libero.it |  |  |
| SIC-ID-000000002641 | Cil | Angela |  | cil.angela@hotmail.it |  |  |
| SIC-ID-000000002642 | Cinzia |  |  | cinzia@autoaccessoriopolesano.it |  |  |
| SIC-ID-000000002643 | Cinzia | Geo |  | cinzia.geo@gmail.com |  |  |
| SIC-ID-000000002644 | Cinzia | Previgliano |  | cinzia.previgliano@icloud.com |  |  |
| SIC-ID-000000002645 | Cipriotianna60 |  |  | cipriotianna60@gmail.com |  |  |
| SIC-ID-000000002646 | Cirillo Costruzioni |  | Cirillo Costruzioni | cirillo.costruzioni@gmail.com |  |  |
| SIC-ID-000000002647 | Cl | Tassone |  | cl.tassone@gmail.com |  |  |
| SIC-ID-000000002648 | Claudiargentina |  |  | claudiargentina@hotmail.com |  |  |
| SIC-ID-000000002649 | Claudio_marangoni1 |  |  | claudio_marangoni1@libero.it |  |  |
| SIC-ID-000000002650 | Claudiozorzan |  |  | claudiozorzan@gmail.com |  |  |
| SIC-ID-000000002651 | Cleopatrasavino |  |  | cleopatrasavino@gmail.com |  |  |
| SIC-ID-000000002652 | Clienti |  |  | clienti@linear.it |  |  |
| SIC-ID-000000002653 | Clinicadelcomputer | Ro |  | clinicadelcomputer.ro@gmail.com |  |  |
| SIC-ID-000000002654 | Clinicaveterinariastazione |  |  | clinicaveterinariastazione@gmail.com |  |  |
| SIC-ID-000000002655 | COBEMAR S. R. L. Cristian |  | COBEMAR S. R. L. Cristian | cristian.cobemar@gmail.com |  |  |
| SIC-ID-000000002656 | Cogead Srl |  | Cogead Srl | cogead.srl@gmail.com |  |  |
| SIC-ID-000000002657 | Coinbasecard |  |  | coinbasecard@aptopayments.com |  |  |
| SIC-ID-000000002658 | Coledilaf |  |  | coledilaf@virgilio.it |  |  |
| SIC-ID-000000002659 | Coluc | 287 |  | coluc.287@gmail.com |  |  |
| SIC-ID-000000002660 | Comitatoelettoralepvp |  |  | comitatoelettoralepvp@gmail.com |  |  |
| SIC-ID-000000002661 | Comm | Edil |  | comm.edil@libero.it |  |  |
| SIC-ID-000000002662 | Commerciale |  | Commerciale | commerciale@zucchettonoleggi.com |  |  |
| SIC-ID-000000002663 | Commerciale |  | Commerciale | commerciale@transistor.it |  |  |
| SIC-ID-000000002664 | Commerciale |  | Commerciale | commerciale@sviluppoita.it |  |  |
| SIC-ID-000000002665 | Commerciale |  | Commerciale | commerciale@benazzo.com |  |  |
| SIC-ID-000000002666 | Commerciale ACCA |  | Commerciale ACCA | commerciale@acca.it |  |  |
| SIC-ID-000000002667 | Commercianti | Centro |  | commercianti.centro@libero.it |  |  |
| SIC-ID-000000002668 | Commercio | Crivellari |  | commercio.crivellari@virgilio.it |  |  |
| SIC-ID-000000002669 | Communication |  |  | communication@mail.pixartprinting.com |  |  |
| SIC-ID-000000002670 | Comunica |  |  | comunica@fotovoltaici.info |  |  |
| SIC-ID-000000002671 | Comunicazione |  |  | comunicazione@attivamenteonlus.it |  |  |
| SIC-ID-000000002672 | Comzaiaemanuele |  |  | za.comzaiaemanuele@libero.it |  |  |
| SIC-ID-000000002673 | Conession |  |  | conession@yahoo.it |  |  |
| SIC-ID-000000002674 | Confezioni | Samy |  | confezioni.samy@libero.it |  |  |
| SIC-ID-000000002675 | Consie83 |  |  | consie83@yahoo.it |  |  |
| SIC-ID-000000002676 | Consulenza Crepaldi |  | Consulenza Crepaldi | consulenza.crepaldi@libero.it |  |  |
| SIC-ID-000000002677 | Contabilita |  |  | contabilita@reef.it |  |  |
| SIC-ID-000000002678 | Contact |  |  | contact@systeme.io |  |  |
| SIC-ID-000000002679 | Contact |  |  | contact@sendinblue.com |  |  |
| SIC-ID-000000002680 | Contarino | Giuseppe |  | contarino.giuseppe@libero.it |  |  |
| SIC-ID-000000002681 | Contewally |  |  | contewally@gmail.com |  |  |
| SIC-ID-000000002682 | Cooperativaomnibus |  | Cooperativaomnibus | cooperativaomnibus@tiscali.it |  |  |
| SIC-ID-000000002683 | Corizzorocco2 |  |  | corizzorocco2@gmail.com |  |  |
| SIC-ID-000000002684 |  |  | Corpo Sano Snc di Molena Marta & C. | corposanosnc@legalmail.it | Chioggia | VE |
| SIC-ID-000000002685 | Corradomanoli |  |  | corradomanoli@alice.it |  |  |
| SIC-ID-000000002686 | Correggio | Circondaria |  | correggio.circondaria@gigroup.com |  |  |
| SIC-ID-000000002687 | Corsairscompany |  |  | corsairscompany@gmail.com |  |  |
| SIC-ID-000000002688 | Cortebenetti |  |  | cortebenetti@gmail.com |  |  |
| SIC-ID-000000002689 | Cortelmail |  |  | cortelmail@yahoo.it |  |  |
| SIC-ID-000000002690 | Costanza Flaminiasrl |  | Costanza Flaminiasrl | costanza.flaminiasrl@gmail.com |  |  |
| SIC-ID-000000002691 | Costruzioni Galvani |  | Costruzioni Galvani | costruzioni.galvani@gmail.com |  |  |
| SIC-ID-000000002692 | COSTRUZIONI GENERALI CHIOGGIA SRL |  | COSTRUZIONI GENERALI CHIOGGIA SRL | costruzionigeneralichioggia@legalmail.it | Chioggia | VE |
| SIC-ID-000000002693 | Costruzionifaraco |  | Costruzionifaraco | costruzionifaraco@libero.it |  |  |
| SIC-ID-000000002694 | Courtney | Smith |  | courtney.smith@keap.com |  |  |
| SIC-ID-000000002695 | Cova | Gianfranco |  | cova.gianfranco@libero.it |  |  |
| SIC-ID-000000002696 | Crepaldi |  |  | e.crepaldi@alpinaimmobiliare.it |  |  |
| SIC-ID-000000002697 | Crepaldiadolfo |  |  | crepaldiadolfo@libero.it |  |  |
| SIC-ID-000000002698 | Crialdue |  |  | crialdue@gmail.com |  |  |
| SIC-ID-000000002699 | Cricomi |  |  | cricomi@gmail.com |  |  |
| SIC-ID-000000002700 | Crifill |  |  | crifill@libero.it |  |  |
| SIC-ID-000000002701 | Cris | Durando |  | cris.durando@gmail.com |  |  |
| SIC-ID-000000002702 | Criscavallini |  |  | criscavallini@libero.it |  |  |
| SIC-ID-000000002703 | Cristian | Boscolo |  | sicoo.sicurezza@gmail.com |  |  |
| SIC-ID-000000002704 | CRISTIAN | BERGO |  | pav_2000@libero.it | Rosolina | RO |
| SIC-ID-000000002705 | Cristiana | Bonfanti |  | cristiana.bonfanti@gmail.com |  |  |
| SIC-ID-000000002706 | Cristianafantoni |  |  | cristianafantoni@hotmail.com |  |  |
| SIC-ID-000000002707 | Cristianbergo |  |  | cristianbergo@libero.it |  |  |
| SIC-ID-000000002708 | Cristiancrose |  |  | cristiancrose@libero.it |  |  |
| SIC-ID-000000002709 | Cristianopellegrin |  |  | cristianopellegrin@libero.it |  |  |
| SIC-ID-000000002710 | Cristianotrotta | Business |  | cristianotrotta.business@gmail.com |  |  |
| SIC-ID-000000002711 | Crivellari_ferro |  |  | crivellari_ferro@yahoo.it |  |  |
| SIC-ID-000000002712 | Crivellariolga |  |  | crivellariolga@gmail.com |  |  |
| SIC-ID-000000002713 | Crypto. | com |  | operator@cryptocom.intercom-mail.com |  |  |
| SIC-ID-000000002714 | Csc |  |  | csc@cscgruppopiu.it |  |  |
| SIC-ID-000000002715 | Csultan |  |  | csultan@fastwebnet.it |  |  |
| SIC-ID-000000002716 | Csweca |  |  | csweca@tin.it |  |  |
| SIC-ID-000000002717 | CTS | l. - Amministrazione |  | amministrazione@carpenteriacts.it |  |  |
| SIC-ID-000000002718 | Curitanapoli |  |  | curitanapoli@yahoo.com |  |  |
| SIC-ID-000000002719 | Curtisouma2017 |  |  | curtisouma2017@gmail.com |  |  |
| SIC-ID-000000002720 | Cusinnatalino |  |  | cusinnatalino@libero.it |  |  |
| SIC-ID-000000002721 | CUSTOMER | ADMINISTRATOR |  | milcsadm@dhl.com |  |  |
| SIC-ID-000000002722 | Customercareefficace |  |  | customercareefficace@gmail.com |  |  |
| SIC-ID-000000002723 | Customerservice |  | Customerservice | customerservice@libraccio.it |  |  |
| SIC-ID-000000002724 | D | Barlafante |  | d.barlafante@codditive.com |  |  |
| SIC-ID-000000002725 | D | Garuti |  | d.garuti@yahoo.it |  |  |
| SIC-ID-000000002726 | D | Maghella |  | d.maghella@tdkservice.com |  |  |
| SIC-ID-000000002727 | D | Mascellani |  | d.mascellani@fiorattomascellani.it |  |  |
| SIC-ID-000000002728 | D'ostuni | BiPiEmme |  | g.dostuni@bipiemme.it |  |  |
| SIC-ID-000000002729 | Dabbinajat |  |  | dabbinajat@gmail.com |  |  |
| SIC-ID-000000002730 | Dacri78 |  |  | dacri78@libero.it |  |  |
| SIC-ID-000000002731 | Daffeh2020 |  |  | daffeh2020@gmail.com |  |  |
| SIC-ID-000000002732 | Dainesevito |  |  | dainesevito@libero.it |  |  |
| SIC-ID-000000002733 | Daisyperezperez |  |  | daisyperezperez@hotmail.com |  |  |
| SIC-ID-000000002734 | Dal | al Successo |  | info@dalsognoalsuccesso.com |  |  |
| SIC-ID-000000002735 | Dalila | Tacchetto |  | dalila.tacchetto@tntitaly.it |  |  |
| SIC-ID-000000002736 | Dallara8410 |  |  | dallara8410@gmail.com |  |  |
| SIC-ID-000000002737 | Dallara_sas |  | Dallara_sas | dallara_sas@libero.it |  |  |
| SIC-ID-000000002738 | Damiano |  |  | damiano@sisasrl.org |  |  |
| SIC-ID-000000002739 | Danielaspano83 |  | Danielaspano83 | danielaspano83@libero.it |  |  |
| SIC-ID-000000002740 | Daniele | Albertin |  | daniele.albertin@yahoo.it |  |  |
| SIC-ID-000000002741 | Daniele | Vendramin |  | daniele.vendramin@gmail.com |  |  |
| SIC-ID-000000002742 | Danielebariga |  |  | danielebariga@libero.it |  |  |
| SIC-ID-000000002743 | Danielebragadin |  |  | danielebragadin@gmail.com |  |  |
| SIC-ID-000000002744 | Danielenalin1968 |  |  | danielenalin1968@gmail.com |  |  |
| SIC-ID-000000002745 | Danieli |  |  | danieli@solaristende.it |  |  |
| SIC-ID-000000002746 | Danielsound2001 |  |  | danielsound2001@yahoo.it |  |  |
| SIC-ID-000000002747 | Danilina | Irina |  | danilina.irina@outlook.it |  |  |
| SIC-ID-000000002748 | Danilobeninati86 |  |  | danilobeninati86@gmail.com |  |  |
| SIC-ID-000000002749 | Danilofranze | Franzè |  | danilofranze@libero.it |  |  |
| SIC-ID-000000002750 | Danivota |  |  | danivota@yahoo.it |  |  |
| SIC-ID-000000002751 | Dariaviviani |  |  | dariaviviani@gmail.com |  |  |
| SIC-ID-000000002752 | Darievalentin79 | Valentin |  | darievalentin79@gmail.com |  |  |
| SIC-ID-000000002753 | Dario | Passarella |  | dario.passarella@libero.it |  |  |
| SIC-ID-000000002754 | Daryy89 |  |  | daryy89@gmail.com |  |  |
| SIC-ID-000000002755 | Dav | Tone |  | dav.tone@alice.it |  |  |
| SIC-ID-000000002756 | Davidbozzato |  |  | davidbozzato@hotmail.com |  |  |
| SIC-ID-000000002757 | Davidebozzato |  |  | davidebozzato@outlook.com |  |  |
| SIC-ID-000000002758 | Davideferroarch |  |  | davideferroarch@gmail.com |  |  |
| SIC-ID-000000002759 | Davidmanzoli |  |  | davidmanzoli72@gmail.com |  |  |
| SIC-ID-000000002760 | Davidmanzoli |  |  | davidmanzoli@gmail.com |  |  |
| SIC-ID-000000002761 | Dbponteggi |  |  | dbponteggi@gmail.com |  |  |
| SIC-ID-000000002762 | Dbuoso |  |  | dbuoso@libero.it |  |  |
| SIC-ID-000000002763 | Deamservice |  | Deamservice | deamservice@yahoo.it |  |  |
| SIC-ID-000000002764 | Debby | Giulia2907 |  | debby.giulia2907@gmail.com |  |  |
| SIC-ID-000000002765 | Debernardiv329 |  |  | debernardiv329@gmail.com |  |  |
| SIC-ID-000000002766 | Dedonnomdm | De donno |  | dedonnomdm@gmail.com |  |  |
| SIC-ID-000000002767 | Defranceschi | Mirco |  | defranceschi.mirco@tiscali.it |  |  |
| SIC-ID-000000002768 | Deina | Taloni |  | deina.taloni@gmail.com |  |  |
| SIC-ID-000000002769 | Dellamura |  |  | dellamura@iol.it |  |  |
| SIC-ID-000000002770 | Delta srl |  | DELTA LAVORI S.R.L. | deltalavori@gmail.com | ROVIGO | RO |
| SIC-ID-000000002771 | Deltamchele59 |  |  | deltamchele59@gmail.it |  |  |
| SIC-ID-000000002772 | Deltamichele59 |  |  | deltamichele59@gmail.it |  |  |
| SIC-ID-000000002773 | Deltamichele79 |  |  | deltamichele79@gmail.it |  |  |
| SIC-ID-000000002774 | Deltast |  |  | deltast@shineline.it |  |  |
| SIC-ID-000000002775 | Delu6060 |  |  | delu6060@tiscali.it |  |  |
| SIC-ID-000000002776 | Dema Srl |  | Dema Srl | dema.srl@outlook.it |  |  |
| SIC-ID-000000002777 | Demfolk |  |  | demfolk@yahoo.it |  |  |
| SIC-ID-000000002778 | Denis | Boscolo |  | denis.boscolo@hotmail.it |  |  |
| SIC-ID-000000002779 | Denis | Maccapanipav |  | denis.maccapanipav@libero.it |  |  |
| SIC-ID-000000002780 | Denisfinotti |  |  | denisfinotti@yahoo.it |  |  |
| SIC-ID-000000002781 | Desiertoceleste |  |  | desiertoceleste@gmail.com |  |  |
| SIC-ID-000000002782 | Designs |  |  | designs@spreadshirt.net |  |  |
| SIC-ID-000000002783 | Devimpianti2009 |  | Devimpianti2009 | devimpianti2009@libero.it |  |  |
| SIC-ID-000000002784 | Di Leo Costruzioni S.R.L. |  | Di Leo Costruzioni S.R.L. | venditedileo@gmail.com |  |  |
| SIC-ID-000000002785 | Di Leo Costruzioni S.R.L. |  | Di Leo Costruzioni S.R.L. | dileocostruzionisrl@tiscali.it |  |  |
| SIC-ID-000000002786 | Irenebassani Un taglio di bassani irene |  | DIAMOCI UN TAGLIO di Bassani Irene | irenebassani@gmail.com | Loreo | RO |
| SIC-ID-000000002787 | Dianatosku |  |  | dianatosku@live.it |  |  |
| SIC-ID-000000002788 | Dianetruebloodu545 |  |  | dianetruebloodu545@gmail.com |  |  |
| SIC-ID-000000002789 | Diego | Furegato |  | diego.furegato@gmail.com |  |  |
| SIC-ID-000000002790 | Diessefood |  |  | diessefood@diessechem.com |  |  |
| SIC-ID-000000002791 | Difrescopatrizia |  |  | difrescopatrizia@virgilio.it |  |  |
| SIC-ID-000000002792 | Digitalvideo3 |  |  | digitalvideo3@virgilio.it |  |  |
| SIC-ID-000000002793 | Dimitri | Pr |  | dimitri.pr@hotmail.it |  |  |
| SIC-ID-000000002794 | Dimitrisapia |  |  | dimitrisapia@libero.it |  |  |
| SIC-ID-000000002795 | Dipiustudio |  | Dipiustudio | dipiustudio@gmail.com |  |  |
| SIC-ID-000000002796 | Direzione |  |  | direzione@confimprenditori.it |  |  |
| SIC-ID-000000002797 | Direzione |  |  | direzione@hotelexpoverona.it |  |  |
| SIC-ID-000000002798 | Direzione |  |  | direzione@giclasolar.com |  |  |
| SIC-ID-000000002799 | Direzione |  |  | direzione@centroimpresa.biz |  |  |
| SIC-ID-000000002800 | Dirk |  |  | dirk@autistici.org |  |  |
| SIC-ID-000000002801 | Dirosariocristina | Cristina |  | dirosariocristina@gmail.com |  |  |
| SIC-ID-000000002802 | Discernim |  |  | discernim@gmail.com |  |  |
| SIC-ID-000000002803 | Dittaborrillo |  | Dittaborrillo | dittaborrillo@gmail.com |  |  |
| SIC-ID-000000002804 | Dittazitiello |  | Dittazitiello | dittazitiello@gmail.com |  |  |
| SIC-ID-000000002805 | Divinafiorella |  |  | divinafiorella@gmail.com |  |  |
| SIC-ID-000000002806 | Dkrama |  |  | dkrama@tin.it |  |  |
| SIC-ID-000000002807 | Dmantovani |  |  | dmantovani@miswaco.slb.com |  |  |
| SIC-ID-000000002808 | Dmazzon |  |  | dmazzon@tiscali.it |  |  |
| SIC-ID-000000002809 | Doganafedex |  |  | doganafedex@fedex.com |  |  |
| SIC-ID-000000002810 | Dolores | Fin |  | dolores.fin@gmail.com |  |  |
| SIC-ID-000000002811 | Domeneghetti |  |  | domeneghetti@a08.it |  |  |
| SIC-ID-000000002812 | Domenico | Lobosco |  | domenico.lobosco@virgilio.it |  |  |
| SIC-ID-000000002813 | Guzzodrea Consulting s.r.l. |  | DOMINA CONSULTING S.r.l. | guzzonandrea@gmail.com | Cavarzere | VE |
| SIC-ID-000000002814 | Donatella | Ba |  | donatella.ba@gmail.com |  |  |
| SIC-ID-000000002815 | Dongting28 |  |  | dongting28@gmail.com |  |  |
| SIC-ID-000000002816 | Donicast |  |  | donicast@tiscali.it |  |  |
| SIC-ID-000000002817 | Dorafalzarano |  |  | dorafalzarano@libero.it |  |  |
| SIC-ID-000000002818 | Dorianascarlata |  |  | dorianascarlata@libero.it |  |  |
| SIC-ID-000000002819 | DOS S.R.L. |  | DOS S.R.L. | dos.srls@legalmail.it | Chioggia | VE |
| SIC-ID-000000002820 | Dott | Engine v1_2026-02-24_03-38 estr |  | dott.fioravanti@stargatenet.it |  |  |
| SIC-ID-000000002821 | Dott | Mauriziorossi |  | dott.mauriziorossi@libero.it |  |  |
| SIC-ID-000000002822 | Doumbiasounga17061994sido |  |  | doumbiasounga17061994sido@glail.com |  |  |
| SIC-ID-000000002823 | Doumbiasoungali17061994sido |  |  | doumbiasoungali17061994sido@gmail.com |  |  |
| SIC-ID-000000002824 | Doumbiasoungalo17061194sido |  |  | doumbiasoungalo17061194sido@gmail.com |  |  |
| SIC-ID-000000002825 | Doumbiasoungalo17061994sido |  |  | doumbiasoungalo17061994sido@gmail.com |  |  |
| SIC-ID-000000002826 | Doumbiasoungalo17061994sifo |  |  | doumbiasoungalo17061994sifo@gmail.com |  |  |
| SIC-ID-000000002827 | Doumbiasoungalo17061994soido |  |  | doumbiasoungalo17061994soido@gmail.com |  |  |
| SIC-ID-000000002828 | Doumbiasoungalo17061994sudo |  |  | doumbiasoungalo17061994sudo@gmail.com |  |  |
| SIC-ID-000000002829 | Doumbiasoungalo17061995sido |  |  | doumbiasoungalo17061995sido@gmail.com |  |  |
| SIC-ID-000000002830 | Doumbiasoungla17061994sido |  |  | doumbiasoungla17061994sido@gmail.com |  |  |
| SIC-ID-000000002831 | Doumbiasounglo17061994sido |  |  | doumbiasounglo17061994sido@gmail.com |  |  |
| SIC-ID-000000002832 | Drago | Omar |  | drago.omar@libero.it |  |  |
| SIC-ID-000000002833 | Dragoneluci |  |  | dragone5luci@libero.it |  |  |
| SIC-ID-000000002834 |  |  | DREAM BAR BOSCOLO SALE VALERIA | boscolosalevaleria@pec.it | Chioggia | VE |
| SIC-ID-000000002835 | Drex81 |  |  | drex81@libero.it |  |  |
| SIC-ID-000000002836 | Drive |  |  | drive@soldo.com |  |  |
| SIC-ID-000000002837 | Dukesgarage2011 |  |  | dukesgarage2011@gmail.com |  |  |
| SIC-ID-000000002838 | Duo | Engine v1_2026-02-24_03-38 estr |  | duo.serramenti@gmail.com |  |  |
| SIC-ID-000000002839 | Duo | Engine v1_2026-02-24_03-38 estr |  | duo.devid@gmail.com |  |  |
| SIC-ID-000000002840 | Duo Devid Serramenti |  | Duo Serramenti | info@duoserramenti.it |  |  |
| SIC-ID-000000002841 | Duopan |  |  | duopan@libero.it |  |  |
| SIC-ID-000000002842 | Duò Serramenti di duò devid |  | Duò Serramenti di Duò Devid | amministrazione@duoserramenti.com | Loreo | RO |
| SIC-ID-000000002843 | Dy | Grigolo |  | dy.grigolo@gmail.com |  |  |
| SIC-ID-000000002844 | E | Laiatici |  | e.laiatici@aironegroup.it |  |  |
| SIC-ID-000000002845 | E | Pavan |  | e.pavan@studio-geotech.it |  |  |
| SIC-ID-000000002846 | Easy-cracow |  |  | info.easy-cracow@viennahouse.com |  |  |
| SIC-ID-000000002847 | Ecomindsrlpd |  | Ecomindsrlpd | ecomindsrlpd@gmail.com |  |  |
| SIC-ID-000000002848 |  |  | ECOPET BP SRL | gobbatosrl@pec.it | Brugine | PD |
| SIC-ID-000000002849 | Ecosistemtec |  |  | ecosistemtec@libero.it |  |  |
| SIC-ID-000000002850 | Ecosmorzo - Materiali Edili Naturali |  | Ecosmorzo - Materiali Edili Naturali | info@ecosmorzo.it |  |  |
| SIC-ID-000000002851 | Eddytheor |  |  | eddytheor@gmail.com |  |  |
| SIC-ID-000000002852 | Edepompeis |  |  | edepompeis@gmail.com |  |  |
| SIC-ID-000000002853 | Edil | Carinella |  | edilcarinella@gmail.com |  |  |
| SIC-ID-000000002854 | Edil | Contract |  | edilcontract@yahoo.com |  |  |
| SIC-ID-000000002855 |  |  | EDIL AL.MA. COSTRUZIONI DI VILLANI ALESSANDRO | villani-alessandro@pec.it | Sant'Antimo | NA |
| SIC-ID-000000002856 | Edil Calabria di Piraino Mario |  | Edil Calabria di Piraino Mario | pirainomario57@gmail.com |  |  |
| SIC-ID-000000002857 |  |  | EDIL CESARATO SNC di Cesarato Marco & C. | marco.cesarato76@gmail.com | Piove di Sacco | PD |
| SIC-ID-000000002858 | Edil Tomarchio Srl |  | Edil Tomarchio Srl | info@ediltomarchio.it |  |  |
| SIC-ID-000000002859 | Edil-city |  |  | edil-city@libero.it |  |  |
| SIC-ID-000000002860 | Edilboscarato S.n.c. |  | EDILBOSCARATO S.N.C. | edilboscarato@tiscali.it | Chioggia | VE |
| SIC-ID-000000002861 | Edilcostruzioniduemila |  | Edilcostruzioniduemila | edilcostruzioniduemila@gmail.com |  |  |
| SIC-ID-000000002862 | Edile Chimenti - impresa edile san pietro in casale |  | Edile Chimenti - impresa edile san pietro in casale | info@chimenti1978.com |  |  |
| SIC-ID-000000002863 | Edile Mazzettopaolo |  | Edile Mazzettopaolo | edile.mazzettopaolo@libero.it |  |  |
| SIC-ID-000000002864 | Edilfer Di Zanardi S.r.l. |  | Edilfer Di Zanardi S.r.l. | info@edilferdizanardi.it |  |  |
| SIC-ID-000000002865 | Edilizia |  | Edilizia | edilizia@venezze.it |  |  |
| SIC-ID-000000002866 | Edilizia Speciale S.R.L. |  | Edilizia Speciale S.R.L. | infoweb@laterizispeciali.it |  |  |
| SIC-ID-000000002867 | Ediliziabaldo Baldo |  | Ediliziabaldo Baldo | ediliziabaldo@gmail.com |  |  |
| SIC-ID-000000002868 | Edilmarket | Ligure |  | informazioni@edilmarketligure.it |  |  |
| SIC-ID-000000002869 | Edyguerrini |  |  | edyguerrini@gmail.com |  |  |
| SIC-ID-000000002870 | Egeacoop76 Cara |  | Egeacoop76 Cara | egeacoop76@gmail.com |  |  |
| SIC-ID-000000002871 | Eidostudio Architetti |  | Eidostudio Architetti | eidostudio.architetti@gmail.com |  |  |
| SIC-ID-000000002872 | Elcon |  |  | elcon@polesineinnovazione.it |  |  |
| SIC-ID-000000002873 | Eldaghirardelli |  |  | eldaghirardelli@gmail.com |  |  |
| SIC-ID-000000002874 | Eleberna54 |  |  | eleberna54@libero.it |  |  |
| SIC-ID-000000002875 | Elena |  |  | amministrazione@lineealterne.com |  |  |
| SIC-ID-000000002876 | Elena | Bellesia |  | elena.bellesia@coldiretti.it |  |  |
| SIC-ID-000000002877 | Elena | Pirovano |  | elena.pirovano@tiscali.it |  |  |
| SIC-ID-000000002878 | Elena | Ponticello |  | elena.ponticello@bmautomazioni.com |  |  |
| SIC-ID-000000002879 | Elenaverlich |  |  | elenaverlich@libero.it |  |  |
| SIC-ID-000000002880 | Eleonora | Bortolussi |  | eleonora.bortolussi@mps.it |  |  |
| SIC-ID-000000002881 | Eli | Rondinini |  | eli.rondinini@hotmail.it |  |  |
| SIC-ID-000000002882 | Elierrante2 | Errante |  | elierrante2@gmail.com |  |  |
| SIC-ID-000000002883 | Elisa | Barrila |  | elisa.barrila@tumiatiimpianti.it |  |  |
| SIC-ID-000000002884 | Elisa Studiosd |  | Elisa Studiosd | elisa.studiosd@gmail.com |  |  |
| SIC-ID-000000002885 | Elisaandreasi |  |  | elisaandreasi@gmail.com |  |  |
| SIC-ID-000000002886 | Elisabettaonida |  |  | elisabettaonida@gmail.com |  |  |
| SIC-ID-000000002887 | Elletibisrl |  | Elletibisrl | elletibisrl@libero.it |  |  |
| SIC-ID-000000002888 | Elvicascio |  |  | elvicascio@libero.it |  |  |
| SIC-ID-000000002889 | Email |  |  | email@italopentimalli.com |  |  |
| SIC-ID-000000002890 | Emanuele Manzetti |  |  | info.emanuele.manzetti@gmail.com |  |  |
| SIC-ID-000000002891 | Emanuelesirtori |  |  | emanuelesirtori@gmail.com |  |  |
| SIC-ID-000000002892 | Emanuelpellegrini |  |  | emanuelpellegrini@libero.it |  |  |
| SIC-ID-000000002893 | Embyone |  |  | embyone@virgilio.it |  |  |
| SIC-ID-000000002894 | Emergyworld | It |  | emergyworld.it@myworld.com |  |  |
| SIC-ID-000000002895 | Emiliana | Fiumara |  | emiliana.fiumara@libero.it |  |  |
| SIC-ID-000000002896 | Emilypregnolato |  |  | emilypregnolato@hotmail.it |  |  |
| SIC-ID-000000002897 | Emmanuele | Dalloco |  | emmanuele.dalloco@gmail.com |  |  |
| SIC-ID-000000002898 | Emmedi Engine v1_2026-02-24_03-38 estr |  | EMMEDI COSTRUZIONI E FINITURE SRLS | emmedi.costruzioniefiniture@gmail.com | Porto Viro | RO |
| SIC-ID-000000002899 | Emozione05 |  |  | emozione05@gmail.com |  |  |
| SIC-ID-000000002900 | Emreina |  |  | emreina@hotmail.it |  |  |
| SIC-ID-000000002901 | Enea Costruzioni |  | Enea Costruzioni | eneacostruzionigenerali@gmail.com |  |  |
| SIC-ID-000000002902 | Eneazerbin |  |  | eneazerbin@virgilio.it |  |  |
| SIC-ID-000000002903 | Energia |  |  | infoenergia@regione.veneto.it |  |  |
| SIC-ID-000000002904 | Energyworld | It |  | energyworld.it@myworld.com |  |  |
| SIC-ID-000000002905 | Engineering |  |  | engineering@finsolar.it |  |  |
| SIC-ID-000000002906 | Ennio | Cazzaro |  | ennio.cazzaro@gmail.com |  |  |
| SIC-ID-000000002907 | Ennoemi |  |  | ennoemi@gmail.com |  |  |
| SIC-ID-000000002908 | Enockbodi |  |  | enockbodi@gmail.com |  |  |
| SIC-ID-000000002909 | Enrico | Bortolo |  | p.e.decorazioni@gmail.com |  |  |
| SIC-ID-000000002910 | Enrico | Longo |  | enrico.longo@geofor.info |  |  |
| SIC-ID-000000002911 | Enricobuoso |  |  | enricobuoso@tiscali.it |  |  |
| SIC-ID-000000002912 | Enricoenergia |  |  | enricoenergia@gmail.com |  |  |
| SIC-ID-000000002913 | Enrisas |  | Enrisas | enrisas@libero.it |  |  |
| SIC-ID-000000002914 | Enzlet |  |  | enzlet@hotmail.it |  |  |
| SIC-ID-000000002915 | Enzo | Vollono |  | enzo.vollono@gmail.com |  |  |
| SIC-ID-000000002916 | Enzostoppa |  |  | enzostoppa@libero.it |  |  |
| SIC-ID-000000002917 | Ep | Ponteggi |  | ep.ponteggi@gmail.com |  |  |
| SIC-ID-000000002918 | Eprosperi | Engine v1_2026-02-24_03-38 estr |  | eprosperi@notariato.it |  |  |
| SIC-ID-000000002919 | Eri | Poli41 |  | eri.poli41@gmail.com |  |  |
| SIC-ID-000000002920 | Erica | Bugari |  | erica.bugari@imq.it |  |  |
| SIC-ID-000000002921 | Erika | Solmi |  | erika.solmi@gv3.it |  |  |
| SIC-ID-000000002922 | Erika_362 |  |  | erika_362@hotmail.com |  |  |
| SIC-ID-000000002923 | Erikarossi | Zeus |  | erikarossi.zeus@gmail.com |  |  |
| SIC-ID-000000002924 | Erminiapuccini |  |  | erminiapuccini@gmail.com |  |  |
| SIC-ID-000000002925 | Erregisnc |  | Erregisnc | erregisnc@alice.it |  |  |
| SIC-ID-000000002926 | Erregisrlmail |  | Erregisrlmail | erregisrlmail@gmail.com |  |  |
| SIC-ID-000000002927 | Errys | Orsi |  | errys.orsi@libero.it |  |  |
| SIC-ID-000000002928 | Escamassi |  |  | escamassi@alice.it |  |  |
| SIC-ID-000000002929 | Essecisrl |  | Essecisrl | essecisrl@wmail.it |  |  |
| SIC-ID-000000002930 | Essegi | Federica |  | essegi.federica@gmail.com |  |  |
| SIC-ID-000000002931 | Monica Duò Caffè |  | Essenziale Caffè di Duò Monica | monikinaduo82@gmail.com | Adria | RO |
| SIC-ID-000000002932 | Essetiservice |  | Essetiservice | essetiservice@alice.it |  |  |
| SIC-ID-000000002933 | Este Edilizia |  | Este Edilizia | este.edilizia@outlook.it |  |  |
| SIC-ID-000000002934 | Estersaddam2 |  |  | estersaddam2@gmail.com |  |  |
| SIC-ID-000000002935 | Esterscarpa |  |  | esterscarpa@hotmail.com |  |  |
| SIC-ID-000000002936 | Estetica | Carlotta |  | estetica.carlotta@gmail.com |  |  |
| SIC-ID-000000002937 | Estetica | Venere miriam |  | estetica.venere.miriam@gmail.com |  |  |
| SIC-ID-000000002938 | Ethnosimmigrazione |  |  | ethnosimmigrazione@gmail.com |  |  |
| SIC-ID-000000002939 |  |  | ETRENCH SRL | etrenchsrl@pec.it | ROVIGO | RO |
| SIC-ID-000000002940 | Ettoreparise66 |  |  | ettoreparise66@gmail.com |  |  |
| SIC-ID-000000002941 | Eugenioelboy |  |  | eugenioelboy@hotmail.it |  |  |
| SIC-ID-000000002942 | Eurocolor2005 |  |  | eurocolor2005@libero.it |  |  |
| SIC-ID-000000002943 | Ezanca |  |  | ezanca@libero.it |  |  |
| SIC-ID-000000002944 | Eze116 |  |  | eze116@hotmail.com |  |  |
| SIC-ID-000000002945 | F | 2donatella |  | f.2donatella@libero.it |  |  |
| SIC-ID-000000002946 | F | Bertuola |  | f.bertuola@rfi.it |  |  |
| SIC-ID-000000002947 | F | Cicatiello |  | f.cicatiello@rfi.it |  |  |
| SIC-ID-000000002948 | F | G gordillo |  | f.g.gordillo@gmail.com |  |  |
| SIC-ID-000000002949 | F | Picelli |  | f.picelli@picellieassociati.com |  |  |
| SIC-ID-000000002950 | F | Scanu |  | f.scanu@fsbusitalia.it |  |  |
| SIC-ID-000000002951 | F | Vanzelli |  | f.vanzelli@synergysystem.it |  |  |
| SIC-ID-000000002952 | F | Vitale |  | f.vitale@sepsrl.com |  |  |
| SIC-ID-000000002953 | F | Zampini |  | f.zampini@libero.it |  |  |
| SIC-ID-000000002954 | Beltramem66 |  | F.B. SAS di Beltrame Marco & C. | beltramem66@gmail.com | Chioggia | VE |
| SIC-ID-000000002955 |  |  | F.LLI CHIEREGATO SNC di Chieregato Daniele e Gabriele | fratellichieregato@pec.it | Chioggia | VE |
| SIC-ID-000000002956 | Fa.ro. Srl |  | FA.RO. SRL | commerciale1.faro@gmail.com | Camposampiero | PD |
| SIC-ID-000000002957 | Faasema | Teryima |  | samabrahamsnigltd@yahoo.com |  |  |
| SIC-ID-000000002958 | Fabiob |  |  | fabio1976b@gmail.com |  |  |
| SIC-ID-000000002959 | Fabiofeggi29 | Feggi |  | fabiofeggi29@gmail.com | Porto Tolle | RO |
| SIC-ID-000000002960 | Fabiogazzini |  |  | fabiogazzini@me.com |  |  |
| SIC-ID-000000002961 | Fabioi3 |  |  | fabioi3@yahoo.it |  |  |
| SIC-ID-000000002962 | Fabiomagnini |  |  | fabiomagnini@ymail.com |  |  |
| SIC-ID-000000002963 | Fabiomoresco |  |  | fabiomoresco@mac.com |  |  |
| SIC-ID-000000002964 |  |  | Fabris Claudia | fabris.claudia@pec.it | Chioggia | VE |
| SIC-ID-000000002965 | Fabrizio | Astori |  | fabrizio.astori@gmail.com |  |  |
| SIC-ID-000000002966 | Facchin | Anna |  | facchin.anna@sangaetano.org |  |  |
| SIC-ID-000000002967 | Facebook |  |  | privacy+icrggvs.aea4677gokze4@support.facebook.com |  |  |
| SIC-ID-000000002968 | Facebook |  |  | info+icxc262.aeavadqb3e@support.facebook.com |  |  |
| SIC-ID-000000002969 | Falco341 |  |  | falco341@gmail.com |  |  |
| SIC-ID-000000002970 | Faldutoluca73 |  |  | faldutoluca73@gmail.com |  |  |
| SIC-ID-000000002971 | Familycapone |  |  | familycapone@libero.it |  |  |
| SIC-ID-000000002972 | Famitalia | Fe |  | famitalia.fe@gmail.com |  |  |
| SIC-ID-000000002973 | Fantoni |  |  | fantoni@eredifantoni.it |  |  |
| SIC-ID-000000002974 | Faram | Modena |  | faram.modena@pierluigivasini.it |  |  |
| SIC-ID-000000002975 | Farfalla77 |  |  | farfalla77@live.com |  |  |
| SIC-ID-000000002976 | Farm Pincara |  | Farm Pincara | farm.pincara@libero.it |  |  |
| SIC-ID-000000002977 | Farosnc |  | Farosnc | farosnc@libero.it |  |  |
| SIC-ID-000000002978 | Fasiol | Rodolfo |  | fasiol.rodolfo@azisanrovigo.it |  |  |
| SIC-ID-000000002979 | Fasulillomynames | Ferraro |  | fasulillomynames@virgilio.it |  |  |
| SIC-ID-000000002980 | Fatokeaugustine |  |  | fatokeaugustine@gmail.com |  |  |
| SIC-ID-000000002981 | Fattoretto | Luca |  | fattoretto.luca@alice.it |  |  |
| SIC-ID-000000002982 | Fatture |  |  | fatture@euroports.it |  |  |
| SIC-ID-000000002983 | Fausto | Fellin |  | fausto.fellin@studiofellin.it |  |  |
| SIC-ID-000000002984 | Faustofusetti65 |  |  | faustofusetti65@gmail.com |  |  |
| SIC-ID-000000002985 | Faxgratis |  |  | faxgratis@faxator.com |  |  |
| SIC-ID-000000002986 | FD | Rent info |  | info@fdrentservice.com |  |  |
| SIC-ID-000000002987 | Fdelon |  |  | fdelon@gmail.com |  |  |
| SIC-ID-000000002988 | Febalza |  |  | febalza@yahoo.com |  |  |
| SIC-ID-000000002989 | Fecchio79 |  |  | fecchio79@libero.it |  |  |
| SIC-ID-000000002990 | Fedeflorence |  |  | fedeflorence@hotmail.it |  |  |
| SIC-ID-000000002991 | Federico | Panza |  | federico.panza@damiani.com |  |  |
| SIC-ID-000000002992 | Federico |  |  | fecchio.impianti@virgilio.it |  |  |
| SIC-ID-000000002993 | Federicotivelli |  |  | federicotivelli@libero.it |  |  |
| SIC-ID-000000002994 | Federpini |  |  | federpini@gmail.com |  |  |
| SIC-ID-000000002995 | Fefe7330 |  |  | fefe7330@gmail.com |  |  |
| SIC-ID-000000002996 | Ferdi8306 |  |  | ferdi8306@gmail.com |  |  |
| SIC-ID-000000002997 | Ferdo | Ogliani |  | fernando.ogliani@libero.it |  |  |
| SIC-ID-000000002998 | Fermani | Fabrizio |  | fermani.fabrizio@gmail.com |  |  |
| SIC-ID-000000002999 | Ferrariscavi |  | Ferrariscavi | ferrariscavi@libero.it |  |  |
| SIC-ID-000000003000 | Ferreroa1984 |  |  | ferreroa1984@gmail.com |  |  |
| SIC-ID-000000003001 | Ferro-idroservice |  | Ferro-idroservice | ferro-idroservice@libero.it |  |  |
| SIC-ID-000000003002 | Ferrodavide |  |  | ferrodavide@hotmail.com |  |  |
| SIC-ID-000000003003 | Ferrotrasporti | Monia |  | ferrotrasporti.monia@alice.it |  |  |
| SIC-ID-000000003004 | Ferry2005 |  |  | ferry2005@email.it |  |  |
| SIC-ID-000000003005 | Ffrancescaromana |  |  | ffrancescaromana@gmail.com |  |  |
| SIC-ID-000000003006 | Fgcxvb | Sjb |  | fgcxvb4sjb@privaterelay.appleid.com |  |  |
| SIC-ID-000000003007 | Fibi |  |  | fibi@libero.it |  |  |
| SIC-ID-000000003008 | Finottieros |  |  | finottieros@live.it |  |  |
| SIC-ID-000000003009 | Finottipaoloragioneria |  |  | finottipaoloragioneria@pcert.postecert.it |  |  |
| SIC-ID-000000003010 | Finotto | Giulia |  | finotto.giulia@yahoo.it |  |  |
| SIC-ID-000000003011 | Fiorella | Civardi |  | fiorella.civardi@studiocivardi.com |  |  |
| SIC-ID-000000003012 | Fiorenzadegliesposti |  |  | fiorenzadegliesposti@tin.it |  |  |
| SIC-ID-000000003013 | Fioretto | Davide |  | fioretto.davide@libero.it |  |  |
| SIC-ID-000000003014 | Flaviano | Momoscale |  | info@momoscale.eu |  |  |
| SIC-ID-000000003015 | Flavio_veronese |  |  | flavio_veronese@alice.it |  |  |
| SIC-ID-000000003016 | Flaviocesa |  |  | flaviocesa@hotmail.it |  |  |
| SIC-ID-000000003017 | Fleediego69 |  |  | fleediego69@gmail.com |  |  |
| SIC-ID-000000003018 | Fm | Milano |  | fm.milano@gmail.com |  |  |
| SIC-ID-000000003019 | Fmasotti |  |  | fmasotti@tcravenna.it |  |  |
| SIC-ID-000000003020 | Fnardelli74 |  |  | fnardelli74@gmail.com |  |  |
| SIC-ID-000000003021 | Fochielisa08 |  |  | fochielisa08@gmail.com |  |  |
| SIC-ID-000000003022 | Fogliatopierandrea | Fogliato |  | fogliatopierandrea@gmail.com |  |  |
| SIC-ID-000000003023 | Fonsato |  |  | fonsato@libero.it |  |  |
| SIC-ID-000000003024 | Fonsatos |  |  | fonsatos@gmail.com |  |  |
| SIC-ID-000000003025 | Forazzo |  |  | forazzo@yahoo.com |  |  |
| SIC-ID-000000003026 | Forin |  |  | forin@pistorello.it |  |  |
| SIC-ID-000000003027 | Forlanfaria |  |  | forlanfaria@gmail.com |  |  |
| SIC-ID-000000003028 | Forma.service Srl |  | FORMA.SERVICE SRL | coordformatemp@formaservice.it | Milano | MI |
| SIC-ID-000000003029 | Fatture |  | FORMASICUREZZA SRL | fatture@elearningsicurezza.com | Anguillara Sabazia | RM |
| SIC-ID-000000003030 | Formicolatecla |  |  | formicolatecla@gmail.com |  |  |
| SIC-ID-000000003031 | Fotovoltaico |  |  | fotovoltaico@tasrl.it |  |  |
| SIC-ID-000000003032 | Fragonas |  |  | fragonas@tiscali.it |  |  |
| SIC-ID-000000003033 | Framar | Chioggia |  | framar.chioggia@gmail.com |  |  |
| SIC-ID-000000003034 | Frampazzo | Engine v1_2026-02-24_03-38 estr |  | frampazzo@notariato.it |  |  |
| SIC-ID-000000003035 | Franc | Rodella |  | franc.rodella@tiscali.it |  |  |
| SIC-ID-000000003036 | Francescabassi73 |  |  | francescabassi73@yahoo.it |  |  |
| SIC-ID-000000003037 | Francescagobesso |  |  | francescagobesso@hotmail.com |  |  |
| SIC-ID-000000003038 | Francesco_barengo |  |  | francesco_barengo@libero.it |  |  |
| SIC-ID-000000003039 | Francescosalviato3 |  |  | francescosalviato3@gmail.com |  |  |
| SIC-ID-000000003040 | Francescoutizisrl |  | Francescoutizisrl | francescoutizisrl@gmail.com |  |  |
| SIC-ID-000000003041 | Franciscaidris40 |  |  | franciscaidris40@gmail.com |  |  |
| SIC-ID-000000003042 | Franco | Chirco |  | franco.chirco@imci-group.com |  |  |
| SIC-ID-000000003043 | Francodetuglie |  |  | francodetuglie@gmail.com |  |  |
| SIC-ID-000000003044 | Francy | 68 |  | francy.68@live.it |  |  |
| SIC-ID-000000003045 | Francybresciani |  |  | francybresciani@me.com |  |  |
| SIC-ID-000000003046 | Frankiedj |  |  | frankiedj@alice.it |  |  |
| SIC-ID-000000003047 | Franz | Suono |  | franz.suono@gmail.com |  |  |
| SIC-ID-000000003048 | Frassonmaurizio | Frasson |  | frassonmaurizio@libero.it |  |  |
| SIC-ID-000000003049 |  |  | FREE COLOR SNC di Ballarin Angelo & Varisco Gimi | freecolorsnc@pec.it | Chioggia | VE |
| SIC-ID-000000003050 | Fregugliajuri |  |  | fregugliajuri@gmail.com |  |  |
| SIC-ID-000000003051 | Frkl |  |  | frkl@libero.it |  |  |
| SIC-ID-000000003052 | Front | Bologna |  | fom@towerhotelbologna.com |  |  |
| SIC-ID-000000003053 | Frstudio Fr |  | Frstudio Fr | frstudio.fr@gmail.com |  |  |
| SIC-ID-000000003054 | Fseformazione |  |  | fseformazione@ascomrovigo.it |  |  |
| SIC-ID-000000003055 | Fuad | Vand |  | fuad.vand@gmail.com |  |  |
| SIC-ID-000000003056 | Fulvio |  |  | fulvio@finottofulvio.com |  |  |
| SIC-ID-000000003057 | Fuoriclasse217 |  |  | fuoriclasse217@gmail.com |  |  |
| SIC-ID-000000003058 | Furlanisnc |  | Furlanisnc | furlanisnc@libero.it |  |  |
| SIC-ID-000000003059 | Fvallese1 |  |  | fvallese1@virgilio.it |  |  |
| SIC-ID-000000003060 | G | Bagatin |  | g.bagatin@gmail.com |  |  |
| SIC-ID-000000003061 | G | Braj |  | g.braj@rfi.it |  |  |
| SIC-ID-000000003062 | G | Carassini |  | g.carassini@gmail.com |  |  |
| SIC-ID-000000003063 | G | Costa |  | g.costa@tecnobitmail.com |  |  |
| SIC-ID-000000003064 | G | Gallocchio |  | g.gallocchio@libero.it |  |  |
| SIC-ID-000000003065 | G | Manfredi |  | g.manfredi@comas-srl.com |  |  |
| SIC-ID-000000003066 | G | Visentin |  | g.visentin@yahoo.it |  |  |
| SIC-ID-000000003067 | G | Zuolo |  | g.zuolo@politecnaeng.it |  |  |
| SIC-ID-000000003068 | Gaal |  |  | gaal@inwind.it |  |  |
| SIC-ID-000000003069 | Gabriela | G1 |  | gabriela.g1@alice.it |  |  |
| SIC-ID-000000003070 | Gabrielebovolenta |  |  | gabrielebovolenta@virgilio.it |  |  |
| SIC-ID-000000003071 | Gabrielegiatti |  |  | gabrielegiatti@alice.it |  |  |
| SIC-ID-000000003072 | Gabrielemazzaro |  |  | gabrielemazzaro@finproject.biz |  |  |
| SIC-ID-000000003073 | Gabrydematte |  |  | gabrydematte@gmail.com |  |  |
| SIC-ID-000000003074 | Gaddi Spa |  | Gaddi Spa | info@gaddispa.com |  |  |
| SIC-ID-000000003075 | Gadir | Cg |  | gadir.cg@gmail.com |  |  |
| SIC-ID-000000003076 | Gaeema | 4 |  | gaeema@tin.it |  |  |
| SIC-ID-000000003077 | Galbian72 | Galbiati |  | galbian72@gmail.com |  |  |
| SIC-ID-000000003078 | Gardaland Hotel Resort Reception |  | Gardaland Hotel Resort Reception | receptionhotel@gardaland.it |  |  |
| SIC-ID-000000003079 | Garofaloa68 |  |  | garofaloa68@gmail.com |  |  |
| SIC-ID-000000003080 | Gattaliu |  |  | gattaliu@tiscalinet.it |  |  |
| SIC-ID-000000003081 | Gattoelia8 |  |  | gattoelia8@gcom.it |  |  |
| SIC-ID-000000003082 | Gavioli |  |  | gavioli@ater.rovigo.it |  |  |
| SIC-ID-000000003083 | Gboem |  |  | gboem@equipefinance.it |  |  |
| SIC-ID-000000003084 | Gd | Verniciature |  | gd.verniciature@yahoo.it |  |  |
| SIC-ID-000000003085 | Gelateriamimosa |  |  | gelateriamimosa@virgilio.it |  |  |
| SIC-ID-000000003086 | Gelindo | Stoppa |  | gelindo.stoppa@alice.it |  |  |
| SIC-ID-000000003087 | Gen_costruzioni |  | Gen_costruzioni | gen_costruzioni@virgilio.it |  |  |
| SIC-ID-000000003088 | GENERTEL | TRIESTE |  | richiestainfo@genertel.it |  |  |
| SIC-ID-000000003089 | Gennarodimarzo1963 |  |  | gennarodimarzo1963@gmail.com |  |  |
| SIC-ID-000000003090 | Gennaroleone75 |  |  | gennaroleone75@gmail.com |  |  |
| SIC-ID-000000003091 | Genny | Pagliai |  | genny.pagliai@tiscali.it |  |  |
| SIC-ID-000000003092 | Geo | Michieletti |  | geo.michieletti@tiscali.it |  |  |
| SIC-ID-000000003093 | Geo | Sandro |  | geo.sandro@virgilio.it |  |  |
| SIC-ID-000000003094 | Geoberni |  |  | geoberni@hotmail.it |  |  |
| SIC-ID-000000003095 | Geologia |  |  | geologia@sigeo.info |  |  |
| SIC-ID-000000003096 | Geom | Albertocastagna |  | geom.albertocastagna@gmail.com |  |  |
| SIC-ID-000000003097 | Geom | Aldosegato |  | geom.aldosegato@stargatenet.it |  |  |
| SIC-ID-000000003098 | Geom | Beggiato |  | geom.beggiato@live.it |  |  |
| SIC-ID-000000003099 | Geom | Buora |  | geom.buora@libero.it |  |  |
| SIC-ID-000000003100 | Geom | Camisottilino |  | geom.camisottilino@tiscali.it |  |  |
| SIC-ID-000000003101 | Geom | Candellieri |  | geom.candellieri@virgilio.it |  |  |
| SIC-ID-000000003102 | Geom | Colombo |  | geom.colombo@alice.it |  |  |
| SIC-ID-000000003103 | Geom | Destro |  | geom.destro@alice.it |  |  |
| SIC-ID-000000003104 | Geom | Fogagnolo |  | geom.fogagnolo@gmail.com |  |  |
| SIC-ID-000000003105 | Geom | Franco grasso63 |  | geom.franco.grasso63@gmail.com |  |  |
| SIC-ID-000000003106 | Geom | Lucaformaglio |  | geom.lucaformaglio@virgilio.it |  |  |
| SIC-ID-000000003107 | Geom | Mariotrevisan |  | geom.mariotrevisan@gmail.com |  |  |
| SIC-ID-000000003108 | Geom | Matteocasarotto |  | geom.matteocasarotto@stargatenet.it |  |  |
| SIC-ID-000000003109 | Geom | Mauriziocassetta |  | geom.mauriziocassetta@gmail.com |  |  |
| SIC-ID-000000003110 | Geom | R sagredin |  | geom.r.sagredin@virgilio.it |  |  |
| SIC-ID-000000003111 | Geom | Rossinico |  | geom.rossinico@libero.it |  |  |
| SIC-ID-000000003112 | Geom | Vicentini |  | geom.vicentini@libero.it |  |  |
| SIC-ID-000000003113 | Geom | Patrizia |  | geom.patrizia@gmail.com |  |  |
| SIC-ID-000000003114 | geom. Capuzzo |  | geom. Capuzzo | padova@corsigeometri.it |  |  |
| SIC-ID-000000003115 | Geomax_mc |  |  | geomax_mc@libero.it |  |  |
| SIC-ID-000000003116 | Geomberto |  |  | geomberto@libero.it |  |  |
| SIC-ID-000000003117 | Geometra |  |  | geometra@federicogazzetta.it |  |  |
| SIC-ID-000000003118 | Geometra | Devivo |  | geometra.devivo@gmail.com |  |  |
| SIC-ID-000000003119 | Geometra | Neodo |  | geometra.neodo@gmail.com |  |  |
| SIC-ID-000000003120 | Geometragfm |  |  | geometragfm@virgilio.it |  |  |
| SIC-ID-000000003121 | Geommosca |  |  | geommosca@libero.it |  |  |
| SIC-ID-000000003122 | Geompavasini |  |  | geompavasini@gmail.com |  |  |
| SIC-ID-000000003123 | Geomsoncin |  |  | geomsoncin@tin.it |  |  |
| SIC-ID-000000003124 | Geomstefanocarboni |  |  | geomstefanocarboni@libero.it |  |  |
| SIC-ID-000000003125 | Geopalitalia |  |  | geopalitalia@gmail.com |  |  |
| SIC-ID-000000003126 | Georgsamuel |  |  | georgsamuel@gmail.com |  |  |
| SIC-ID-000000003127 | Georubi |  |  | georubi@libero.it |  |  |
| SIC-ID-000000003128 | Geostudio 08 |  | Geostudio 08 | geostudio.08@gmail.com |  |  |
| SIC-ID-000000003129 | Geotecnostudio |  | Geotecnostudio | geotecnostudio@libero.it |  |  |
| SIC-ID-000000003130 | Geoumbe |  |  | geoumbe@libero.it |  |  |
| SIC-ID-000000003131 | Geoweb | Roma |  | info@geoweb.it |  |  |
| SIC-ID-000000003132 | Gepo69 |  |  | gepo69@alice.it |  |  |
| SIC-ID-000000003133 | Geppino |  |  | info.cagepa@gmail.com |  |  |
| SIC-ID-000000003134 | Gerardina | Delvecchio |  | gerardina.delvecchio@gmail.com |  |  |
| SIC-ID-000000003135 | Gerardo | Stefan |  | gerardo.stefan@libero.it |  |  |
| SIC-ID-000000003136 | Germanmi |  |  | germanmi@tin.it |  |  |
| SIC-ID-000000003137 | Gessicaferro85 |  |  | gessicaferro85@gmail.com |  |  |
| SIC-ID-000000003138 | Gestore |  | Gestore | gestore@g-patrol.it |  |  |
| SIC-ID-000000003139 | Gherardinivittorio |  |  | gherardinivittorio@gmail.com |  |  |
| SIC-ID-000000003140 | Ghezzog |  |  | ghezzog@alice.it |  |  |
| SIC-ID-000000003141 | Ghiro | 14 |  | ghiro.14@libero.it |  |  |
| SIC-ID-000000003142 | Ghisellini | Giovanni |  | prolocofrass@libero.it |  |  |
| SIC-ID-000000003143 | Silviarondina86 Di rondina silvia |  | GI.EMME di Rondina Silvia | silviarondina86@gmail.com | Padova | PD |
| SIC-ID-000000003144 | Giacomelli | Cristina |  | giacomelli.cristina@tiscali.it |  |  |
| SIC-ID-000000003145 | Giacomo |  |  | giacomo@giacomofreddi.it |  |  |
| SIC-ID-000000003146 | Giacomo | Trombini |  | giacomo.trombini@virgilio.it |  |  |
| SIC-ID-000000003147 | Giacomotuzza |  |  | giacomotuzza@libero.it |  |  |
| SIC-ID-000000003148 | Giaconthomas |  |  | giaconthomas@libero.it |  |  |
| SIC-ID-000000003149 | Giampaoloantonio |  |  | giampaoloantonio@tiscali.it |  |  |
| SIC-ID-000000003150 | Giampietro |  |  | giampietro@studiosaccon.it |  |  |
| SIC-ID-000000003151 | Gianfrancolosi |  |  | gianfrancolosi@katamail.com |  |  |
| SIC-ID-000000003152 | Giankipoc |  |  | giankipoc@outlook.it |  |  |
| SIC-ID-000000003153 | Gianlu | Frez |  | gianlu.frez@libero.it |  |  |
| SIC-ID-000000003154 | Gianlucaloi69 |  |  | gianlucaloi69@gmail.com |  |  |
| SIC-ID-000000003155 | Gianlucapignotti |  |  | gianlucapignotti@tiscali.it |  |  |
| SIC-ID-000000003156 | gianmarco | germini |  | ggmarco@gmail.com |  |  |
| SIC-ID-000000003157 | Gianni |  |  | gianni@gagliardo.it |  |  |
| SIC-ID-000000003158 | Giannifederico Federico |  | GIANNI FEDERICO | giannifederico@gmail.com | Codevigo | PD |
| SIC-ID-000000003159 | Giannibeninca |  |  | giannibeninca@libero.it |  |  |
| SIC-ID-000000003160 | Gianniscanu |  |  | gianniscanu@alice.it |  |  |
| SIC-ID-000000003161 | Gianpietropea |  |  | gianpietropea@hotmail.it |  |  |
| SIC-ID-000000003162 | Gibinivan |  |  | gibinivan@libero.it |  |  |
| SIC-ID-000000003163 | Gigi | Turla |  | gigiturla@gmail.com |  |  |
| SIC-ID-000000003164 | Gigino93 | Candela |  | gigino93@gmail.com |  |  |
| SIC-ID-000000003165 | Gigisabia70 |  |  | gigisabia70@gmail.com |  |  |
| SIC-ID-000000003166 | Gimmilanza |  |  | gimmilanza@hotmail.it |  |  |
| SIC-ID-000000003167 | Ginosafranco1 |  |  | ginosafranco1@gmail.com |  |  |
| SIC-ID-000000003168 | Gio | Baroni65 |  | gio.baroni65@gmail.com |  |  |
| SIC-ID-000000003169 | Gio | Gallone |  | gio.gallone@gmail.com |  |  |
| SIC-ID-000000003170 | Gioehein | Dell'erba |  | gioehein@libero.it |  |  |
| SIC-ID-000000003171 | Giofavarotto |  |  | giofavarotto@tiscali.it |  |  |
| SIC-ID-000000003172 | Giorgia | Tasso |  | giorgia.tasso@alice.it |  |  |
| SIC-ID-000000003173 | Giorgian |  |  | giorgian@venditaautomatica.com |  |  |
| SIC-ID-000000003174 | Giorgiozamara |  |  | giorgiozamara@gmail.com |  |  |
| SIC-ID-000000003175 | Giovanni | Ippolito |  | giovanni.ippolito@venditoreadistanza.com |  |  |
| SIC-ID-000000003176 | Giovanniapanunzio |  |  | giovanniapanunzio@gmail.com |  |  |
| SIC-ID-000000003177 | Giovannibissacco | 91 |  | giovannibissacco.91@gmail.com |  |  |
| SIC-ID-000000003178 | Giovanniboscolo |  |  | giovanniboscolo@tiscali.it |  |  |
| SIC-ID-000000003179 | Giovanniciviero |  |  | giovanniciviero@libero.it |  |  |
| SIC-ID-000000003180 | Gippep |  |  | gippep@gmail.com |  |  |
| SIC-ID-000000003181 | Girotto | Elisa |  | girotto.elisa@libero.it |  |  |
| SIC-ID-000000003182 | Girottodiego |  |  | girottodiego@libero.it |  |  |
| SIC-ID-000000003183 | Gisele |  |  | gisele@hotmail.es |  |  |
| SIC-ID-000000003184 | Giubilatochiara |  |  | giubilatochiara@iisciprianicolombo.edu.it |  |  |
| SIC-ID-000000003185 | Giulia | Franzato |  | giulia.franzato@padovafiere.it |  |  |
| SIC-ID-000000003186 | Giulia | Pietro |  | info@giuliaepietro.it |  |  |
| SIC-ID-000000003187 | Giuliacezza |  |  | giuliacezza@friuladria.it |  |  |
| SIC-ID-000000003188 | Giuliana | AttivaMente |  | giuliana@attivamenteonlus.onmicrosoft.com |  |  |
| SIC-ID-000000003189 | Giuliano | Lanzetti |  | info@pienissimo.com |  |  |
| SIC-ID-000000003190 | Giuliatammiso |  |  | giuliatammiso@gmail.com |  |  |
| SIC-ID-000000003191 | Giulio | Tartuferi elettricista |  | amministrazione@caspita.biz |  |  |
| SIC-ID-000000003192 | Giuseppebergantin |  |  | giuseppebergantin@me.com |  |  |
| SIC-ID-000000003193 | Giuseppelobueaudiopro |  |  | giuseppelobueaudiopro@gmail.com |  |  |
| SIC-ID-000000003194 | Giuseppinasansone3 |  |  | giuseppinasansone3@gmail.com |  |  |
| SIC-ID-000000003195 | Giustina | Dsga |  | giustina.dsga@gmail.com |  |  |
| SIC-ID-000000003196 | Giusymontanino |  |  | giusymontanino@hotmail.it |  |  |
| SIC-ID-000000003197 | Givifer |  |  | givifer@libero.it |  |  |
| SIC-ID-000000003198 | Glen | White |  | glen.white@libero.it |  |  |
| SIC-ID-000000003199 | Globoasfalti |  |  | globoasfalti@libero.it |  |  |
| SIC-ID-000000003200 | Glocati |  |  | glocati@synthesis-srl.com |  |  |
| SIC-ID-000000003201 | Gloria |  |  | ferrogloria79@gmail.com |  |  |
| SIC-ID-000000003202 | Gloria | Naso |  | gloria.naso@libero.it |  |  |
| SIC-ID-000000003203 | Gloria | Zerbinati |  | gloria.zerbinati@libero.it |  |  |
| SIC-ID-000000003204 | Gloriataddei63 |  |  | gloriataddei63@gmail.com |  |  |
| SIC-ID-000000003205 | Gmarangoni |  |  | gmarangoni@bancadria.it |  |  |
| SIC-ID-000000003206 | Gmrizzieri |  |  | gmrizzieri@libero.it |  |  |
| SIC-ID-000000003207 | Gmtchiarel |  |  | gmtchiarel@libero.it |  |  |
| SIC-ID-000000003208 | Gobbato | Enrico |  | info.enrico72@gmail.com |  |  |
| SIC-ID-000000003209 | Gogoleva59 |  |  | gogoleva59@yahoo.it |  |  |
| SIC-ID-000000003210 | Gold_fine |  |  | gold_fine@hotmail.com |  |  |
| SIC-ID-000000003211 | Golinellil |  |  | golinellil@i-dea.it |  |  |
| SIC-ID-000000003212 | Golivieri357 |  |  | golivieri357@gmail.com |  |  |
| SIC-ID-000000003213 | Gongservice |  | Gongservice | gongservice@libero.it |  |  |
| SIC-ID-000000003214 | Goyita | Margarita |  | goyita@hotmail.it |  |  |
| SIC-ID-000000003215 | Gpadovan |  |  | gpadovan@hotmail.com |  |  |
| SIC-ID-000000003216 | Gpclienti |  |  | gpclienti@alice.it |  |  |
| SIC-ID-000000003217 | Gpozzato |  |  | gpozzato@gmail.it |  |  |
| SIC-ID-000000003218 | Grafica |  |  | grafica@trattostampa.it |  |  |
| SIC-ID-000000003219 | Graphic Solutions |  | Graphic Solutions | graphic.solutions@virgilio.it |  |  |
| SIC-ID-000000003220 | Graronzulli |  |  | graronzulli@gmail.com |  |  |
| SIC-ID-000000003221 | Grassi | Mancin |  | grassi.mancin@libero.it |  |  |
| SIC-ID-000000003222 | Gratton | Gabriele |  | gratton.gabriele@yahoo.it |  |  |
| SIC-ID-000000003223 | Graziusojacopo | Graziuso |  | graziusojacopo@gmail.com |  |  |
| SIC-ID-000000003224 | Grebra |  |  | grebra@alice.it |  |  |
| SIC-ID-000000003225 | Greentel Srlcr |  | Greentel Srlcr | greentel.srlcr@gmail.com |  |  |
| SIC-ID-000000003226 | Gretas |  |  | gretas@libero.it |  |  |
| SIC-ID-000000003227 | Grimeco_costruzioni |  | Grimeco_costruzioni | grimeco_costruzioni@yahoo.it |  |  |
| SIC-ID-000000003228 | grossatoivaldo@libero | it |  | grossatoivaldo@libero.it |  |  |
| SIC-ID-000000003229 | Groups |  | Groups | groups@ramadaplazamilano.it |  |  |
| SIC-ID-000000003230 | Groupsgvcaminetti |  | Groupsgvcaminetti | groupsgvcaminetti@virgilio.it |  |  |
| SIC-ID-000000003231 | Gruppoculturalepolentari |  |  | gruppoculturalepolentari@gmail.com |  |  |
| SIC-ID-000000003232 | Gscalone |  |  | gscalone@gmail.com |  |  |
| SIC-ID-000000003233 | Gteing |  |  | gteing@gteing.com |  |  |
| SIC-ID-000000003234 | Gteresa |  |  | gteresa@lattebusche.it |  |  |
| SIC-ID-000000003235 | Guadagnidirendita |  |  | guadagnidirendita@gmail.com |  |  |
| SIC-ID-000000003236 | Guerracapuzzogeom |  |  | guerracapuzzogeom@libero.it |  |  |
| SIC-ID-000000003237 | Guglielmocampajola |  |  | guglielmocampajola@gmail.com |  |  |
| SIC-ID-000000003238 | Guidotosarelli |  |  | guidotosarelli@gmail.com |  |  |
| SIC-ID-000000003239 | Guolo | Patrick |  | guolo.patrick@alice.it |  |  |
| SIC-ID-000000003240 | Guolo | Patrick |  | guolo.patrick@gmail.com |  |  |
| SIC-ID-000000003241 | Gvalentini | Geom |  | gvalentini.geom@alice.it |  |  |
| SIC-ID-000000003242 | Hajnic |  |  | hajnic@gmail.com |  |  |
| SIC-ID-000000003243 | Hank | com |  | hank@cryptocom.intercom-mail.com |  |  |
| SIC-ID-000000003244 | Harley-Davidson | Onlineshop Dresden |  | newsletter@shop-harley-dresden.com |  |  |
| SIC-ID-000000003245 | Help |  |  | help@produzionidalbasso.com |  |  |
| SIC-ID-000000003246 | Help |  |  | help@eshirt.it |  |  |
| SIC-ID-000000003247 | Honademolaadewuyi | Adewuyi |  | honademolaadewuyi@gmail.com |  |  |
| SIC-ID-000000003248 | Hotel |  | Hotel | hotel@pragserwildsee.com |  |  |
| SIC-ID-000000003249 | Hotel Relax Asiago |  | Hotel Asiago | info@relaxhotelasiago.it |  |  |
| SIC-ID-000000003250 | Hotel Bella Vista Terme |  | Hotel Bella Vista Terme | info@bellavistaterme.com |  |  |
| SIC-ID-000000003251 | Hotel Da Barba |  | Hotel Da Barba | info@dabarba.it |  |  |
| SIC-ID-000000003252 | Hotel Paradiso Asiago |  | Hotel Paradiso Asiago | info@hotelparadisoasiago.it |  |  |
| SIC-ID-000000003253 | Hotel Real |  | Hotel Real | hotel.real@libero.it |  |  |
| SIC-ID-000000003254 | Hotrodgrills |  |  | hotrodgrills@outlook.com |  |  |
| SIC-ID-000000003255 | Hypercommunityitaly |  |  | hypercommunityitaly@gmail.com |  |  |
| SIC-ID-000000003256 | Hyperfund | Team |  | compliance@thehyperfund.com |  |  |
| SIC-ID-000000003257 | I | Gordini |  | i.gordini@calcestruzzi.it |  |  |
| SIC-ID-000000003258 | I | Prevedello |  | i.prevedello@gmail.com |  |  |
| SIC-ID-000000003259 |  |  | I Sapori di Casa di Ferrari Egle | isaporidiegle@pec.it | Loreo | RO |
| SIC-ID-000000003260 |  |  | I.M.I. IMPRESA MONTAGGI INDUSTRIALI SRL | imi.montaggi@pec.it | Selvazzano Dentro | PD |
| SIC-ID-000000003261 | Iaco | Cla |  | iaco.cla@libero.it |  |  |
| SIC-ID-000000003262 | Ida | Marina olmina |  | ida.marina.olmina@hotmail.it |  |  |
| SIC-ID-000000003263 |  |  | IDRO-SERVICE DI FERRO GIAN PIETRO | ferrogianpietro@pec.it | Cavarzere | VE |
| SIC-ID-000000003264 | Idrotecno | Sf |  | idrotecno.sf@libero.it |  |  |
| SIC-ID-000000003265 | Igor | Grigolato |  | igor.grigolato@studiom6.it |  |  |
| SIC-ID-000000003266 | Il | Euroffice |  | supporto@euroffice.it |  |  |
| SIC-ID-000000003267 | Il Tempio del corpo di fogo veronica |  | IL TEMPIO DEL CORPO di Fogo Veronica | fogoveronica@gmail.com | Cavarzere | VE |
| SIC-ID-000000003268 |  |  | IL TEMPIO DELLA BELLEZZA DI BONANDINI SILVIA | info@pec.tempiobellezza.eu | PORTO VIRO | RO |
| SIC-ID-000000003269 | Ilber73 |  |  | ilber73@live.it |  |  |
| SIC-ID-000000003270 | Ilenia |  |  | ilenia.francescon@gmail.com |  |  |
| SIC-ID-000000003271 | Ileniatosetto86 |  |  | ileniatosetto86@libero.it |  |  |
| SIC-ID-000000003272 | Ilpesciolino |  |  | ilpesciolino@email.it |  |  |
| SIC-ID-000000003273 | Ilpuntobase |  |  | ilpuntobase@tin.it |  |  |
| SIC-ID-000000003274 | Immarca |  |  | immarca@libero.it |  |  |
| SIC-ID-000000003275 | Immob_progettocasa |  |  | immob_progettocasa@libero.it |  |  |
| SIC-ID-000000003276 | Immobiliare Di adria |  | Immobiliare Di adria | immobiliare.di.adria@gmail.com |  |  |
| SIC-ID-000000003277 | Impedovo | A |  | impedovo.a@gmail.com |  |  |
| SIC-ID-000000003278 | Imprenditore |  |  | servizioclienti@genertel.it |  |  |
| SIC-ID-000000003279 | Impresa E.d.i.l.e seven |  | Impresa E.d.i.l.e seven | seven@sevensrl.it |  |  |
| SIC-ID-000000003280 | Impresa Edile |  | Impresa Edile | s.p.e.costruzioni@gmail.com |  |  |
| SIC-ID-000000003281 | Impresa Edile Barizza S.A.S. |  | Impresa Edile Barizza S.A.S. | 605a7baede844d278b89dc95ae0a9123@sentry-next.wixpress.com |  |  |
| SIC-ID-000000003282 | Impresa Edile riberti roberto |  | Impresa Edile riberti roberto | faustinirobertoimpresa@gmail.com |  |  |
| SIC-ID-000000003283 | Impresa Ferratisrl |  | Impresa Ferratisrl | impresa.ferratisrl@gmail.com |  |  |
| SIC-ID-000000003284 | Impresacogipa |  | Impresacogipa | impresacogipa@gmail.com |  |  |
| SIC-ID-000000003285 | Impresaguerra |  | Impresaguerra | impresaguerra@virgilio.it |  |  |
| SIC-ID-000000003286 | Imprese |  |  | imprese@studioporzionato.191.it |  |  |
| SIC-ID-000000003287 | Inarch1 |  |  | inarch1@tin.it |  |  |
| SIC-ID-000000003288 | Incaricatiallevendite |  |  | incaricatiallevendite@lyconet.com |  |  |
| SIC-ID-000000003289 | Indragundidimax | Gunawan |  | indragundidimax@gmail.com |  |  |
| SIC-ID-000000003290 | Info | - Best Expo |  | info@hotelexpoverona.it |  |  |
| SIC-ID-000000003291 | INFO Centro Servizi S. Anna |  | INFO Centro Servizi Anna | info@cssanna.com |  |  |
| SIC-ID-000000003292 | Info Group |  | Info Group | info@tec-group.net |  |  |
| SIC-ID-000000003293 | Info064 |  |  | info064@brt.it |  |  |
| SIC-ID-000000003294 | Infoareaufficio |  |  | infoareaufficio@virgilio.it |  |  |
| SIC-ID-000000003295 | Infoboxsrls |  | Infoboxsrls | infoboxsrls@gmail.com |  |  |
| SIC-ID-000000003296 | Infogoticashopelettro |  |  | infogoticashopelettro@gmail.com |  |  |
| SIC-ID-000000003297 | Infomusikandsound |  |  | infomusikandsound@gmail.com |  |  |
| SIC-ID-000000003298 | Infosaluteebellezza |  |  | infosaluteebellezza@gmail.com |  |  |
| SIC-ID-000000003299 | Infostudio |  | Infostudio | infostudio.cb@gmail.com |  |  |
| SIC-ID-000000003300 | Ing | Aguiarigiuliano |  | ing.aguiarigiuliano@libero.it |  |  |
| SIC-ID-000000003301 | Ing | Bazzani |  | ing.bazzani@gmail.com |  |  |
| SIC-ID-000000003302 | Ing | Cmilan |  | ing.cmilan@libero.it |  |  |
| SIC-ID-000000003303 | Ing | Donatofiorillo |  | ing.donatofiorillo@libero.it |  |  |
| SIC-ID-000000003304 | Ing | Elenachiappa |  | ing.elenachiappa@gmail.com |  |  |
| SIC-ID-000000003305 | Ing | Giacomazzi |  | ing.giacomazzi@tiscali.it |  |  |
| SIC-ID-000000003306 | Ing | Giordanoantonio |  | ing.giordanoantonio@gmail.com |  |  |
| SIC-ID-000000003307 | Ing | Gromani |  | ing.gromani@gmail.com |  |  |
| SIC-ID-000000003308 | Ing | Guglielmo |  | ing.guglielmo@tiscali.it |  |  |
| SIC-ID-000000003309 | Ing | Paoloborin |  | ing.paoloborin@libero.it |  |  |
| SIC-ID-000000003310 | Ing | Polichetti |  | ing.polichetti@libero.it |  |  |
| SIC-ID-000000003311 | Ing | Romanfra |  | ing.romanfra@gmail.com |  |  |
| SIC-ID-000000003312 | Ing | Sacrato |  | ing.sacrato@libero.it |  |  |
| SIC-ID-000000003313 | Ing | Sandrosignoretto |  | ing.sandrosignoretto@gmail.com |  |  |
| SIC-ID-000000003314 | Ing | Sanna roberto |  | ing.sanna.roberto@gmail.com |  |  |
| SIC-ID-000000003315 | Ing. Rinaldi APE |  | Ing. Rinaldi APE | certienergia@gmail.com |  |  |
| SIC-ID-000000003316 | Ingbaiano | Baiano |  | ingbaiano@libero.it |  |  |
| SIC-ID-000000003317 | Ingguglielmo | Passarella |  | ingguglielmo.passarella@gmail.com |  |  |
| SIC-ID-000000003318 | Inglago |  |  | inglago@libero.it |  |  |
| SIC-ID-000000003319 | Ingmauriziobarboni |  |  | ingmauriziobarboni@gmail.com |  |  |
| SIC-ID-000000003320 | Ingridgiomartini |  |  | ingridgiomartini@yahoo.it |  |  |
| SIC-ID-000000003321 | Ingsena |  |  | ingsena@alice.it |  |  |
| SIC-ID-000000003322 | Iniziative |  |  | iniziative@uildmve.it |  |  |
| SIC-ID-000000003323 | Insieme | Perlapace |  | insieme.perlapace@hotmail.it |  |  |
| SIC-ID-000000003324 | Interazionieuropa |  |  | interazionieuropa@libero.it |  |  |
| SIC-ID-000000003325 | Intonaci | Fl |  | intonaci.fl@gmail.com |  |  |
| SIC-ID-000000003326 | Intonacimilan |  |  | intonacimilan@libero.it |  |  |
| SIC-ID-000000003327 | Inviodocumenti | It |  | inviodocumenti.it@ing.com |  |  |
| SIC-ID-000000003328 | Iocisono |  |  | iocisono@kartra.com |  |  |
| SIC-ID-000000003329 | Iovhei55 |  |  | iovhei55@gmail.com |  |  |
| SIC-ID-000000003330 | Iphone | Liliana |  | iphone.liliana@gmail.com |  |  |
| SIC-ID-000000003331 | Iragazzidelsole |  |  | iragazzidelsole@libero.it |  |  |
| SIC-ID-000000003332 | Iris59 |  |  | iris59@hotmail.it |  |  |
| SIC-ID-000000003333 | Irsi |  |  | irsi@irsi.it |  |  |
| SIC-ID-000000003334 | Isabellapicariello7 |  |  | isabellapicariello7@gmail.com |  |  |
| SIC-ID-000000003335 | Isaqsrl |  | Isaqsrl | isaqsrl@gmail.com |  |  |
| SIC-ID-000000003336 | Ishtiyaqaw |  |  | ishtiyaqaw@gmail.com |  |  |
| SIC-ID-000000003337 | Isis | Sanlucar |  | isis.sanlucar@gmail.com |  |  |
| SIC-ID-000000003338 | Issps |  |  | issps@outlook.it |  |  |
| SIC-ID-000000003339 | It | Support |  | it.support@lyconet.com |  |  |
| SIC-ID-000000003340 | Italia | Expo |  | info@italiasurfexpo.it |  |  |
| SIC-ID-000000003341 | Italiancoffee |  |  | italiancoffee@actimail.it |  |  |
| SIC-ID-000000003342 | Italy |  |  | italy@lyconet.com |  |  |
| SIC-ID-000000003343 | Italy |  |  | italy@lyconet.it |  |  |
| SIC-ID-000000003344 | Itiszubair343 |  |  | itiszubair343@gmail.com |  |  |
| SIC-ID-000000003345 | Ivana | Pietrolungo |  | ivana.pietrolungo@gmail.com |  |  |
| SIC-ID-000000003346 | Ivanapagliarulo |  |  | ivanapagliarulo@alice.it |  |  |
| SIC-ID-000000003347 | Ivmsp |  |  | iv3msp@libero.it |  |  |
| SIC-ID-000000003348 | Ivo | Magnabosco |  | ivo.magnabosco@gmail.com |  |  |
| SIC-ID-000000003349 | Ivo | Rinaldin |  | ivo.rinaldin@alice.it |  |  |
| SIC-ID-000000003350 | Ivoavidhold |  |  | ivoavidhold@gmail.com |  |  |
| SIC-ID-000000003351 | Izeta7 |  |  | izeta7@gmail.com |  |  |
| SIC-ID-000000003352 | Jacopoaneghini |  |  | jacopoaneghini@me.com |  |  |
| SIC-ID-000000003353 | Jarilavoro |  |  | jarilavoro@gmail.com |  |  |
| SIC-ID-000000003354 | Jarivianellolavoro |  |  | jarivianellolavoro@gmail.com |  |  |
| SIC-ID-000000003355 | Jariviianellolavoro |  |  | jariviianellolavoro@gmail.com |  |  |
| SIC-ID-000000003356 | Jeanpalezza |  |  | jeanpalezza@gmail.com |  |  |
| SIC-ID-000000003357 | Jetboarditaly |  |  | jetboarditaly@gmail.com |  |  |
| SIC-ID-000000003358 | Jidejoda4 |  |  | jidejoda4@live.com |  |  |
| SIC-ID-000000003359 | Jimmi | Bas |  | jimmi.bas@gmail.com |  |  |
| SIC-ID-000000003360 | Jimmybonato |  |  | jimmybonato@gmail.com |  |  |
| SIC-ID-000000003361 | Joao | Bahia |  | joao.bahia@tiscali.it |  |  |
| SIC-ID-000000003362 | Jonathan | Kennedy |  | pasqualijonathan@gmail.com |  |  |
| SIC-ID-000000003363 | Josephaglieririnella | Rinella |  | josephaglieririnella@gmail.com |  |  |
| SIC-ID-000000003364 | Josex78 |  |  | josex78@libero.it |  |  |
| SIC-ID-000000003365 | Joshef70 |  |  | joshef70@libero.it |  |  |
| SIC-ID-000000003366 | Jrxpaintingcontractors | Castillo sanabia |  | jrxpaintingcontractors@gmail.com |  |  |
| SIC-ID-000000003367 | Justchigozie |  |  | justchigozie@gmail.com |  |  |
| SIC-ID-000000003368 | Kamykatt |  |  | kamykatt@hotmail.it |  |  |
| SIC-ID-000000003369 | Kantemohamed980 | Mohamed |  | kantemohamed980@gmail.com |  |  |
| SIC-ID-000000003370 | Katiusciagabriele3 |  |  | katiusciagabriele3@gmail.com |  |  |
| SIC-ID-000000003371 | Kayros68 |  |  | kayros68@live.it |  |  |
| SIC-ID-000000003372 | Kilsyblake21 |  |  | kilsyblake21@gmail.com |  |  |
| SIC-ID-000000003373 | Kimberly | com |  | kimberly@cryptocom.intercom-mail.com |  |  |
| SIC-ID-000000003374 | Konatefatim001 |  |  | konatefatim001@gmail.com |  |  |
| SIC-ID-000000003375 | Krashdj |  |  | krashdj@tiscali.it |  |  |
| SIC-ID-000000003376 | Kri | Mottaran |  | kri.mottaran@gmail.com |  |  |
| SIC-ID-000000003377 | Kristianpiva |  | KRISTIAN PIVA | kristianpiva@libero.it | Porto Viro | RO |
| SIC-ID-000000003378 | Kyky73 |  |  | kyky73@gmail.com |  |  |
| SIC-ID-000000003379 | L | Belli |  | l.belli@calcestruzzi.it |  |  |
| SIC-ID-000000003380 | L | Bozzatomenin |  | l.bozzatomenin@studiomenin.it |  |  |
| SIC-ID-000000003381 | Silviamarzola Del gusto di marzola silvia |  | L'ANGOLO DEL GUSTO DI MARZOLA SILVIA | silviamarzola@libero.it | Chioggia | VE |
| SIC-ID-000000003382 | La | Clessidra |  | la.clessidra@live.it |  |  |
| SIC-ID-000000003383 | La Cooperativa |  | LA MELA Società Cooperativa | info@lamelacoop.it | Corbola | RO |
| SIC-ID-000000003384 | LABO+ Studio |  | LABO+ Studio | labotecnicstudio@outlook.it |  |  |
| SIC-ID-000000003385 | Labruna2 |  |  | labruna2@yahoo.it |  |  |
| SIC-ID-000000003386 | Lacasadivi | 10 |  | lacasadivi.10@libero.it |  |  |
| SIC-ID-000000003387 | Lacasasulfiumebosa |  | Lacasasulfiumebosa | lacasasulfiumebosa@gmail.com |  |  |
| SIC-ID-000000003388 | Ladyanna69 |  |  | ladyanna69@libero.it |  |  |
| SIC-ID-000000003389 | Laila | Bisio |  | laila.bisio@milanoexe.it |  |  |
| SIC-ID-000000003390 | Lalla | Lanzi |  | lalla.lanzi@gmail.com |  |  |
| SIC-ID-000000003391 | Lamagiadelfumo |  |  | lamagiadelfumo@gmail.com |  |  |
| SIC-ID-000000003392 | Lamagna |  |  | v.lamagna@libero.it |  |  |
| SIC-ID-000000003393 | Lapostadimiki |  |  | lapostadimiki@gmail.com |  |  |
| SIC-ID-000000003394 | Lara | Astolfi |  | lara.astolfi@libero.it |  |  |
| SIC-ID-000000003395 | Larabiolo |  |  | larabiolo@gmail.com |  |  |
| SIC-ID-000000003396 | Laracappelli |  |  | laracappelli@hotmail.it |  |  |
| SIC-ID-000000003397 | Latte | Busche |  | info@lattebusche.it |  |  |
| SIC-ID-000000003398 | Lattoneriaclodia |  |  | lattoneriaclodia@gmail.com |  |  |
| SIC-ID-000000003399 | Laura |  |  | info@lauraivan.com |  |  |
| SIC-ID-000000003400 | Laura_laura908 |  |  | laura_laura908@yahoo.com |  |  |
| SIC-ID-000000003401 | Laurarizzato85 |  |  | laurarizzato85@gmail.com |  |  |
| SIC-ID-000000003402 | Laurasolinas73 | Solinas |  | laurasolinas73@gmail.com |  |  |
| SIC-ID-000000003403 | Laurazida71 |  |  | laurazida71@gmail.com |  |  |
| SIC-ID-000000003404 | Laurimaria15 |  |  | laurimaria15@gmail.com |  |  |
| SIC-ID-000000003405 | Lauro | Gardinale |  | lauro.gardinale@alfuturosa.it |  |  |
| SIC-ID-000000003406 | Lautomobile | Pratiche |  | lautomobile.pratiche@gmail.com |  |  |
| SIC-ID-000000003407 | Lavoricontoterzi |  | Lavoricontoterzi | lavoricontoterzi@libero.it |  |  |
| SIC-ID-000000003408 | Law_enforcement |  |  | law_enforcement@bybit.com |  |  |
| SIC-ID-000000003409 | Lazzarin | Cristina |  | lazzarincri@libero.it |  |  |
| SIC-ID-000000003410 | Lazzarin | Massimo |  | lazzarin.massimo@ciapadova.it |  |  |
| SIC-ID-000000003411 | Le di Sammarco |  |  | ecommerce@levignedisammarco.it |  |  |
| SIC-ID-000000003412 | Lead | Rilevato |  | commerciale@compedata.com |  |  |
| SIC-ID-000000003413 | Leander | Vocaj |  | leander.vocaj@yahoo.it |  |  |
| SIC-ID-000000003414 | Ledeliziedinazzareno |  |  | ledeliziedinazzareno@libero.it |  |  |
| SIC-ID-000000003415 | Legal | It |  | legal.it@myworld.com |  |  |
| SIC-ID-000000003416 | Legale | Federicapozzato |  | legale.federicapozzato@studipozzato.it |  |  |
| SIC-ID-000000003417 | Lellabosco |  |  | lellabosco@libero.it |  |  |
| SIC-ID-000000003418 | Lenzi |  |  | lenzi@lucalenzi.it |  |  |
| SIC-ID-000000003419 | Leonecapoferri61 |  |  | leonecapoferri61@gmail.com |  |  |
| SIC-ID-000000003420 | Leonelorenzoni |  |  | leonelorenzoni@libero.it |  |  |
| SIC-ID-000000003421 | Leosil54 |  |  | leosil54@libero.it |  |  |
| SIC-ID-000000003422 | Leucianto22 |  |  | leucianto22@gmail.com |  |  |
| SIC-ID-000000003423 | Lgfservizi |  | Lgfservizi | lgfservizi@gmail.com |  |  |
| SIC-ID-000000003424 | Lgimmy |  |  | lgimmy@inwind.it |  |  |
| SIC-ID-000000003425 | Libri |  |  | libri@archimagazine.es |  |  |
| SIC-ID-000000003426 | Licia Gabriella | Consoli |  | liciaconsolid@gmail.com | Cesena | FC |
| SIC-ID-000000003427 | Lidia |  |  | lidia@negritrasporti.it |  |  |
| SIC-ID-000000003428 | Lidialaplaca |  |  | lidialaplaca@hotmail.it |  |  |
| SIC-ID-000000003429 | Lillimarino249 |  |  | lillimarino249@gmail.com |  |  |
| SIC-ID-000000003430 | Linareppuccia |  |  | linareppuccia@libero.it |  |  |
| SIC-ID-000000003431 | Lindabobbo |  |  | lindabobbo@virgilio.it |  |  |
| SIC-ID-000000003432 | Linkin560 | Barbuto |  | linkin560@yahoo.it |  |  |
| SIC-ID-000000003433 | Linogiuseppe | Zen |  | linogiuseppe.zen@icportoviro.edu.it |  |  |
| SIC-ID-000000003434 | Lis Srl |  | Lis Srl | info.lis.srl@gmail.com |  |  |
| SIC-ID-000000003435 | Lisanicoletto |  |  | lisanicoletto@hotmail.com |  |  |
| SIC-ID-000000003436 | Lisatugnolo |  |  | lisatugnolo@hotmail.com |  |  |
| SIC-ID-000000003437 | Livicsoluzioniedili |  |  | livicsoluzioniedili@libero.it |  |  |
| SIC-ID-000000003438 | Lodo | Claudia |  | lodo.claudia@gmail.com |  |  |
| SIC-ID-000000003439 | Longo | M geometra |  | longo.m.geometra@gmail.com |  |  |
| SIC-ID-000000003440 | Loredana | Ciotti |  | loredana.ciotti@gmail.com |  |  |
| SIC-ID-000000003441 | Loredana | Sardo |  | loredana.sardo@tiscali.it |  |  |
| SIC-ID-000000003442 | Lorella Studiogs |  | Lorella Studiogs | lorella.studiogs@gmail.com |  |  |
| SIC-ID-000000003443 | Lorellamontanari63 |  |  | lorellamontanari63@gmail.com |  |  |
| SIC-ID-000000003444 | Lorena | Dalpoz |  | lorena.dalpoz@regione.veneto.it |  |  |
| SIC-ID-000000003445 | Lorena | Luca60 |  | lorena.luca60@gmail.com |  |  |
| SIC-ID-000000003446 | Lorena Srl |  | Lorena Srl | amministrazione@benazzosrl.it |  |  |
| SIC-ID-000000003447 | Lorenzomoretto |  |  | lorenzomoretto@yahoo.it |  |  |
| SIC-ID-000000003448 | Loretta_ricci |  |  | loretta_ricci@libero.it |  |  |
| SIC-ID-000000003449 | Lorettazanardelli |  |  | lorettazanardelli@gmail.com |  |  |
| SIC-ID-000000003450 | Losapio | A |  | losapio.a@gmail.com |  |  |
| SIC-ID-000000003451 | Lothar | 80 |  | lothar.80@alice.it |  |  |
| SIC-ID-000000003452 | Lpieretto |  |  | lpieretto@libero.it |  |  |
| SIC-ID-000000003453 | Lpizzolato |  |  | lpizzolato@libero.it |  |  |
| SIC-ID-000000003454 | Lubrog |  |  | lubrog@tin.it |  |  |
| SIC-ID-000000003455 | Luc365 |  |  | luc365@hotmail.it |  |  |
| SIC-ID-000000003456 | Luca |  |  | luca@logiksrl.it |  |  |
| SIC-ID-000000003457 | Luca |  |  | luca@izzi.it |  |  |
| SIC-ID-000000003458 | Luca |  |  | luca.santinpilu@gmail.com |  |  |
| SIC-ID-000000003459 | Luca |  |  | chiodiluca@libero.it |  |  |
| SIC-ID-000000003460 | Luca | Dambrosio2 |  | luca.dambrosio2@libero.it |  |  |
| SIC-ID-000000003461 | Luca | Giordano73 |  | luca.giordano73@gmail.com |  |  |
| SIC-ID-000000003462 | Luca | Parnisari |  | luca.parnisari@gmail.com |  |  |
| SIC-ID-000000003463 | Lucamilani63 |  |  | lucamilani63@hotmail.it |  |  |
| SIC-ID-000000003464 | Lucasocciarelli |  |  | lucasocciarelli@yahoo.it |  |  |
| SIC-ID-000000003465 | Lucedesign |  |  | info.lucedesign@gmail.com |  |  |
| SIC-ID-000000003466 | Lucia |  |  | lucia@reef.it |  |  |
| SIC-ID-000000003467 | Lucia | Zampini 63 |  | lucia.zampini.63@gmail.com |  |  |
| SIC-ID-000000003468 | Luciaballiana68 |  |  | luciaballiana68@gmail.com |  |  |
| SIC-ID-000000003469 | Luciana | Marchioni |  | luciana.marchioni@libero.it |  |  |
| SIC-ID-000000003470 | Luciana | Masi |  | luciana.masi@yahoo.it |  |  |
| SIC-ID-000000003471 | Ludobriciola |  |  | ludobriciola@gmail.com |  |  |
| SIC-ID-000000003472 | Ludovico | M |  | ludovico.m@inwind.it |  |  |
| SIC-ID-000000003473 | Luigi |  |  | segreteria@luigicarlino.it |  |  |
| SIC-ID-000000003474 | Luigi |  |  | caporaliluigi@gmail.com |  |  |
| SIC-ID-000000003475 | Luigi | Bove |  | luigi_bove@hotmail.it |  |  |
| SIC-ID-000000003476 | Luigifreddo |  |  | luigifreddo@gmail.com |  |  |
| SIC-ID-000000003477 | Luigifreddo |  |  | luigifreddo@studiofreddo.191.it |  |  |
| SIC-ID-000000003478 | Luigisbano |  |  | luigisbano@alice.it |  |  |
| SIC-ID-000000003479 | Luisadaniela8482 |  |  | luisadaniela8482@gmail.com |  |  |
| SIC-ID-000000003480 | Luismor |  |  | luismor@libero.it |  |  |
| SIC-ID-000000003481 | Lulittam |  |  | lulittam@gmail.com |  |  |
| SIC-ID-000000003482 | Luminizza |  |  | luminizza@live.ru |  |  |
| SIC-ID-000000003483 | Lunardivittorio |  |  | lunardivittorio@virgilio.it |  |  |
| SIC-ID-000000003484 | Lungomichela |  |  | lungomichela@gmail.com |  |  |
| SIC-ID-000000003485 | Luzpierotto |  |  | luzpierotto@hotmail.com |  |  |
| SIC-ID-000000003486 | Lzagato |  |  | lzagato@libero.it |  |  |
| SIC-ID-000000003487 | M | Belotti |  | m.belotti@studioassociatobbc.it |  |  |
| SIC-ID-000000003488 | M | Debei |  | m.debei@intermediassicura.it |  |  |
| SIC-ID-000000003489 | M | Depietri |  | m.depietri@mos80.it |  |  |
| SIC-ID-000000003490 | M | Lago |  | m.lago@onean.com |  |  |
| SIC-ID-000000003491 | M | Leitempergher |  | m.leitempergher@transistor.it |  |  |
| SIC-ID-000000003492 | M | Monaco |  | m.monaco@blumatica.it |  |  |
| SIC-ID-000000003493 | M | Saccoman |  | m.saccoman@gmail.com |  |  |
| SIC-ID-000000003494 | M | Trombetti |  | m.trombetti@libero.it |  |  |
| SIC-ID-000000003495 | M.g. Costruzioni s.r.l. |  | M.g. Costruzioni s.r.l. | info@mggcostruzioni.com |  |  |
| SIC-ID-000000003496 | Mabate2003 |  |  | mabate2003@alice.it |  |  |
| SIC-ID-000000003497 | MABO Group |  | MABO Group | info@mabogroup.it |  |  |
| SIC-ID-000000003498 | Made |  |  | made@marcegaglia.com |  |  |
| SIC-ID-000000003499 | Madre-querida |  |  | madre-querida@hotmail.com |  |  |
| SIC-ID-000000003500 | Magda | Dellai |  | magda.dellai@hymson.eu |  |  |
| SIC-ID-000000003501 | Maidirebau |  |  | maidirebau@gmail.com |  |  |
| SIC-ID-000000003502 | Mail |  |  | mail@ilve.com |  |  |
| SIC-ID-000000003503 | Maila | Amministrazione |  | maila.amministrazione@studiocarloalbertini.it |  |  |
| SIC-ID-000000003504 | Maiorano | Ester |  | maiorano.ester@libero.it |  |  |
| SIC-ID-000000003505 | Maira | Comin75 |  | maira.comin75@gmail.com |  |  |
| SIC-ID-000000003506 | Maite | Onean |  | m.perez@onean.com |  |  |
| SIC-ID-000000003507 | Malo | 2010 |  | malo.2010@libero.it |  |  |
| SIC-ID-000000003508 | Malookkhan56 |  |  | malookkhan56@gmail.com |  |  |
| SIC-ID-000000003509 | Mamoserena | Anna |  | mamoserena@gmail.com |  |  |
| SIC-ID-000000003510 | Manager |  |  | manager@ultimomulino.it |  |  |
| SIC-ID-000000003511 | Manca | Patrizio |  | manca.patrizio@gmail.com |  |  |
| SIC-ID-000000003512 | Mancin | Dem |  | mancin.dem@gmail.com |  |  |
| SIC-ID-000000003513 | Mandingo | 79 |  | mandingo.79@live.it |  |  |
| SIC-ID-000000003514 | Manola | Iberico |  | manola@cuoreiberico.it |  |  |
| SIC-ID-000000003515 | Mantovan | Adelmo |  | mantovan.adelmo@gmail.com |  |  |
| SIC-ID-000000003516 | Mantovan Mattia |  | MANTOVAN MATTIA | decor.mm@virgilio.it | Porto Viro | RO |
| SIC-ID-000000003517 | Mantovani Leonzio & figli s.r.l. |  | MANTOVANI LEONZIO & FIGLI S.R.L. | info@mantovanitrasporti.it | PORTO VIRO | RO |
| SIC-ID-000000003518 | Mantovaniandrea7 |  |  | mantovaniandrea7@gmail.com |  |  |
| SIC-ID-000000003519 | Mantovaninico13 |  |  | mantovaninico13@gmail.com |  |  |
| SIC-ID-000000003520 | Manuel | Cavallin |  | manuel.cavallin@prodecopharma.com |  |  |
| SIC-ID-000000003521 | Manuel | Cipriotto |  | manuel.cipriotto@gmail.com |  |  |
| SIC-ID-000000003522 | Manuel_facen |  |  | manuel_facen@hotmail.it |  |  |
| SIC-ID-000000003523 | Manuelpaolini |  |  | manuelpaolini@engineer.com |  |  |
| SIC-ID-000000003524 | Manzolidavid72 |  |  | manzolidavid72@gmail.com |  |  |
| SIC-ID-000000003525 | Maqconsultsrl |  | Maqconsultsrl | maqconsultsrl@tiscali.it |  |  |
| SIC-ID-000000003526 | Mar | Milena |  | mar.milena@libero.it |  |  |
| SIC-ID-000000003527 | Marangon | Denis |  | marangon.denis@libero.it |  |  |
| SIC-ID-000000003528 | Marangon | Denis |  | marangon.denis@alice.it |  |  |
| SIC-ID-000000003529 | Marangonmelissa |  |  | marangonmelissa@gmai.com |  |  |
| SIC-ID-000000003530 | Marcatinaccio |  |  | marcatinaccio@yahoo.it |  |  |
| SIC-ID-000000003531 | Marcelloliguorivs |  |  | marcelloliguorivs@gmail.com |  |  |
| SIC-ID-000000003532 | Marcellosoncin |  |  | marcellosoncin@icloud.com |  |  |
| SIC-ID-000000003533 | Marco | Geom MOtta |  | adi-motta@hotmail.com |  |  |
| SIC-ID-000000003534 | Marco | Finotto |  | marco_finotto@alice.it |  |  |
| SIC-ID-000000003535 | Marco | Sicurezza Lusso |  | marco.lusso@bcs-ais.com |  |  |
| SIC-ID-000000003536 | Marco | Zeke 99 ONEAN |  | marco.zeke99@gmail.com |  |  |
| SIC-ID-000000003537 | marco | testacci |  | marco.testacci@bmautomazioni.com |  |  |
| SIC-ID-000000003538 | Marcorelli Srl |  | Marcorelli Srl | commerciale@marcorellisrl.it |  |  |
| SIC-ID-000000003539 | Mari Sport / Costruzione impianti sportivi - Campi da tennis e paddle |  | Mari Sport / Costruzione impianti sportivi - Campi da tennis e paddle | marisportsistem@gmail.com |  |  |
| SIC-ID-000000003540 | Maria | Faccin |  | m.faccin@greenenergyitalia.it |  |  |
| SIC-ID-000000003541 | Maria | Sardina |  | maria.sardina@libero.it |  |  |
| SIC-ID-000000003542 | Mariachiara | Crivellari |  | mariachiara.crivellari@gmail.com |  |  |
| SIC-ID-000000003543 | Mariachiarapizzo |  |  | mariachiarapizzo@libero.it |  |  |
| SIC-ID-000000003544 | Mariacristina | Mernone |  | mariacristina.mernone@gmail.com |  |  |
| SIC-ID-000000003545 | Mariafanaru |  |  | mariafanaru@gmail.com |  |  |
| SIC-ID-000000003546 | Mariag | Buono |  | mariag.buono@gmail.com |  |  |
| SIC-ID-000000003547 | Mariagrazia | Guzzon |  | mguzzon.2@notariato.it |  |  |
| SIC-ID-000000003548 | Mariagrazialopriore9 |  |  | mariagrazialopriore9@gmail.com |  |  |
| SIC-ID-000000003549 | Marialentini |  |  | marialentini@gmail.com |  |  |
| SIC-ID-000000003550 | Mariano | Seminara |  | mariano.seminara@yahoo.it |  |  |
| SIC-ID-000000003551 | Mariapia | Garbo |  | mariapia.garbo@alice.it |  |  |
| SIC-ID-000000003552 | Mariateresa | Florio |  | mariateresa.florio@tin.it |  |  |
| SIC-ID-000000003553 | Mariella | Gaeta |  | mariella.gaeta@yahoo.it |  |  |
| SIC-ID-000000003554 | Marilisascafati |  | Marilisascafati | marilisascafati@gmail.con |  |  |
| SIC-ID-000000003555 | Marilisascafati |  | Marilisascafati | marilisascafati@gmail.com |  |  |
| SIC-ID-000000003556 | Marimonaci |  |  | marimonaci@yahoo.it |  |  |
| SIC-ID-000000003557 | Marina | Bianconcini |  | marina.bianconcini@easymatic.it |  |  |
| SIC-ID-000000003558 | Marina | com |  | marina.bancheva@cryptocom.intercom-mail.com |  |  |
| SIC-ID-000000003559 | Mariocrespomartinez |  |  | mariocrespomartinez@libero.it |  |  |
| SIC-ID-000000003560 | Mariofrancescorusso | Francesco |  | mariofrancescorusso@gmail.com |  |  |
| SIC-ID-000000003561 | Mariofusco1979 |  |  | mariofusco1979@hotmail.it |  |  |
| SIC-ID-000000003562 | Mariomasala1 |  |  | mariomasala1@gmail.com |  |  |
| SIC-ID-000000003563 | Marketing |  |  | marketing@lyoness.it |  |  |
| SIC-ID-000000003564 | Marketing It |  |  | marketing.it@myworld.com |  |  |
| SIC-ID-000000003565 | Marola | M |  | marola.m@elettrosigma.com |  |  |
| SIC-ID-000000003566 | Marta | Gastaldin |  | marta.gastaldin@cf.confart.tv |  |  |
| SIC-ID-000000003567 | Martecu |  |  | martecu@gmail.com |  |  |
| SIC-ID-000000003568 | Martinaalesara |  |  | martinaalesara@libero.it |  |  |
| SIC-ID-000000003569 | Martinacarossa |  |  | martinacarossa@gmail.com |  |  |
| SIC-ID-000000003570 | Martinaddance |  |  | martinaddance@gmail.com |  |  |
| SIC-ID-000000003571 | Martinello | Dani |  | martinello.dani@gmail.com |  |  |
| SIC-ID-000000003572 | Martinrissi1962 |  |  | martinrissi1962@hotmail.com |  |  |
| SIC-ID-000000003573 | Marziaorsucci65 |  |  | marziaorsucci65@gmail.com |  |  |
| SIC-ID-000000003574 | Mascheriniloredana |  |  | mascheriniloredana@gmail.com |  |  |
| SIC-ID-000000003575 | Masciaverucchi |  |  | masciaverucchi@gmail.com |  |  |
| SIC-ID-000000003576 | Masierluca |  |  | masierluca@libero.it |  |  |
| SIC-ID-000000003577 | Masiero | Mattia |  | masierocostruzioni@gmail.com |  |  |
| SIC-ID-000000003578 | Masiero Mattia costruzioni s.r.l. |  | MASIERO MATTIA COSTRUZIONI S.r.l. | amministrazionemasierosrl@gmail.com | Chioggia | VE |
| SIC-ID-000000003579 | Massi | Bonistalli |  | massi.bonistalli@alice.it |  |  |
| SIC-ID-000000003580 | Massiedil2 |  |  | massiedil2@gmail.com |  |  |
| SIC-ID-000000003581 | Massifer64 |  |  | massifer64@gmail.com |  |  |
| SIC-ID-000000003582 | Massimo | Zanardo |  | massimo.zanardo@interprostudio.it |  |  |
| SIC-ID-000000003583 | Massimosottile3 |  |  | massimosottile3@gmail.com |  |  |
| SIC-ID-000000003584 | Matteo | 85 |  | assuntamatteo.85@gmail.com |  |  |
| SIC-ID-000000003585 | Matteo | Fabbri |  | matteo.fabbri@enaip.veneto.it |  |  |
| SIC-ID-000000003586 | Matteoalfonso |  |  | matteoalfonso@yahoo.com |  |  |
| SIC-ID-000000003587 | Matteoauto |  |  | matteoauto@hotmail.it |  |  |
| SIC-ID-000000003588 | Matteodiiasio60 |  |  | matteodiiasio60@gmail.com |  |  |
| SIC-ID-000000003589 | Mattia | Casellato |  | mattia.casellato@ictagliodipo.com |  |  |
| SIC-ID-000000003590 | Mattiacheula89 |  |  | mattiacheula89@gmail.com |  |  |
| SIC-ID-000000003591 | Mattiatognati |  |  | mattiatognati@alice.it |  |  |
| SIC-ID-000000003592 | Mattiaveronese |  |  | mattiaveronese@vodafone.it |  |  |
| SIC-ID-000000003593 | Mattiolimarica | Mattioli |  | mattiolimarica@gmail.com |  |  |
| SIC-ID-000000003594 | Mauramagnaghi |  |  | mauramagnaghi@gmail.com |  |  |
| SIC-ID-000000003595 | Mauri050988 |  |  | mauri050988@gmail.com |  |  |
| SIC-ID-000000003596 | Maurielloelvira |  |  | maurielloelvira@gmail.com |  |  |
| SIC-ID-000000003597 | Maurizio | Derosa |  | maurizio.derosa@outlook.it |  |  |
| SIC-ID-000000003598 | Mauriziobuzzone |  |  | mauriziobuzzone@gmail.com |  |  |
| SIC-ID-000000003599 | Mauriziodrago |  |  | mauriziodrago@gmail.com |  |  |
| SIC-ID-000000003600 | Mauriziomarippi |  |  | mauriziomarippi@gmail.com |  |  |
| SIC-ID-000000003601 | Mauriziosco1 |  |  | mauriziosco1@yahoo.com |  |  |
| SIC-ID-000000003602 | Mauro |  |  | mauro@studiomingotti.com |  |  |
| SIC-ID-000000003603 | Maurobottaro4 |  |  | maurobottaro4@gmail.com |  |  |
| SIC-ID-000000003604 | Mauroricetto |  |  | mauroricetto@yahoo.it |  |  |
| SIC-ID-000000003605 | Maverick76_12 |  |  | maverick76_12@libero.it |  |  |
| SIC-ID-000000003606 | Max | Lazzari |  | max.lazzari@hotmail.com |  |  |
| SIC-ID-000000003607 | Max | Morelli |  | max.morelli@me.com |  |  |
| SIC-ID-000000003608 | Maxgraci69 |  |  | maxgraci69@gmail.com |  |  |
| SIC-ID-000000003609 | Maxmarevivo |  |  | maxmarevivo@gmail.com |  |  |
| SIC-ID-000000003610 | Mazsandro |  |  | mazsandro@hotmail.com |  |  |
| SIC-ID-000000003611 | Mazza | Ugo |  | mazzaugo61@gmail.com |  |  |
| SIC-ID-000000003612 | Mazzetto |  |  | mazzetto@sermetranet.it |  |  |
| SIC-ID-000000003613 | Mazzuccoluca |  |  | mazzuccoluca@gmail.com |  |  |
| SIC-ID-000000003614 | Mb Srl |  | Mb Srl | mb.srl@alice.it |  |  |
| SIC-ID-000000003615 | Medilav2011 |  |  | medilav2011@tiscali.it |  |  |
| SIC-ID-000000003616 | Megajhp |  |  | megajhp@gmail.com |  |  |
| SIC-ID-000000003617 | Melina | Agnello |  | melina.agnello@libero.it |  |  |
| SIC-ID-000000003618 | Mendezramongu |  |  | mendez2018ramongu@gmail.com |  |  |
| SIC-ID-000000003619 | Meneghessoalessia |  |  | meneghessoalessia@gmail.com |  |  |
| SIC-ID-000000003620 | Meneghin | Roberto |  | meneghin.roberto@hotmail.it |  |  |
| SIC-ID-000000003621 | Merchant- | It |  | merchant-info.it@cashback-solutions.com |  |  |
| SIC-ID-000000003622 | Mery3000 |  |  | mery3000@libero.it |  |  |
| SIC-ID-000000003623 | Mestaghanmimed | Tra |  | mestaghanmimed.tra@gmail.com |  |  |
| SIC-ID-000000003624 | Mexas |  |  | mexas@libero.it |  |  |
| SIC-ID-000000003625 | Mf | Cabezas97 |  | mf.cabezas97@gmail.com |  |  |
| SIC-ID-000000003626 |  |  | MF IMPRESA EDILE SRLS | mfimpresaedile@pec.it | Rosolina | RO |
| SIC-ID-000000003627 | Mf Impresaedile |  | Mf Impresaedile | mf.impresaedile@gmail.com |  |  |
| SIC-ID-000000003628 | Mg | Zandarin |  | mg.zandarin@gmail.com |  |  |
| SIC-ID-000000003629 | Mgfiumelli | Grazia |  | mgfiumelli@gmail.com |  |  |
| SIC-ID-000000003630 | Mguidijob |  |  | mguidijob@gmail.com |  |  |
| SIC-ID-000000003631 | Mi | Heitzinger |  | mi.heitzinger@gmail.com |  |  |
| SIC-ID-000000003632 | Micangeli |  |  | micangeli@hotmail.it |  |  |
| SIC-ID-000000003633 | Michael |  |  | furlanmichael@ymail.com |  |  |
| SIC-ID-000000003634 | Michelangelo | Scardamaglia |  | michelangelo.scardamaglia@gmail.com |  |  |
| SIC-ID-000000003635 | Micheledelta59 |  |  | micheledelta59@gmail.it |  |  |
| SIC-ID-000000003636 | Micheleposta2 | Caputo |  | micheleposta2@gmail.com |  |  |
| SIC-ID-000000003637 | Michellecappelletto |  |  | michellecappelletto@gmail.com |  |  |
| SIC-ID-000000003638 | Simonmike84 |  | MICHELON SIMON | simonmike84@gmail.com | Vigodarzere | PD |
| SIC-ID-000000003639 | Michi | Deirossi |  | michi.deirossi@tin.it |  |  |
| SIC-ID-000000003640 | Mihaipoke | Palade |  | mihaipoke@yahoo.com |  |  |
| SIC-ID-000000003641 | Mikeiscaro |  |  | mikeiscaro@hotmail.com |  |  |
| SIC-ID-000000003642 | Mikelhelmi |  |  | mikelhelmi@libero.it |  |  |
| SIC-ID-000000003643 | Milagrosmenendez71 |  |  | milagrosmenendez71@gmail.com |  |  |
| SIC-ID-000000003644 | Milaka1979 |  |  | milaka1979@hotmail.com |  |  |
| SIC-ID-000000003645 | Milaniloeis1976 |  |  | milaniloeis1976@gmail.com |  |  |
| SIC-ID-000000003646 | Milaniloria1976 |  |  | milaniloria1976@gmail.com |  |  |
| SIC-ID-000000003647 | Milaniloris1976 |  |  | milaniloris1976@gmail.com |  |  |
| SIC-ID-000000003648 | Milaniloris2976 |  |  | milaniloris2976@gmail.com |  |  |
| SIC-ID-000000003649 | Milaniloris76 |  |  | milaniloris76@gmail.com |  |  |
| SIC-ID-000000003650 | Milaniloros1976 |  |  | milaniloros1976@gmail.com |  |  |
| SIC-ID-000000003651 |  |  | MILLESAPORI BANQUETING & RISTORAZIONE DI FINOTTI ENRICO & C. | millesapori.snc@arubapec.it | Porto Viro | RO |
| SIC-ID-000000003652 | Mingozzichiara |  |  | mingozzichiara@iisciprianicolombo.edu.it |  |  |
| SIC-ID-000000003653 | Mioriandmore |  |  | mioriandmore@gmail.com |  |  |
| SIC-ID-000000003654 | Miottomichele59 |  |  | miottomichele59@gmail.it |  |  |
| SIC-ID-000000003655 | Mirandolavasco |  |  | mirandolavasco@gmail.com |  |  |
| SIC-ID-000000003656 | Miranorma |  |  | miranorma@libero.it |  |  |
| SIC-ID-000000003657 | Mirapic |  |  | mirapic@virgilio.it |  |  |
| SIC-ID-000000003658 | Mirca |  |  | mirca@nglattonieri.com |  |  |
| SIC-ID-000000003659 | Mirco |  |  | daoplanb@gmail.com |  |  |
| SIC-ID-000000003660 | Mircobenedetti |  |  | mircobenedetti@gmail.com |  |  |
| SIC-ID-000000003661 | Mircogiupponi |  |  | mircogiupponi@libero.it |  |  |
| SIC-ID-000000003662 | Mirellad |  |  | mirellad@tiscali.it |  |  |
| SIC-ID-000000003663 | Miriamsanfilippo |  |  | miriamsanfilippo@gmail.com |  |  |
| SIC-ID-000000003664 | Mirna | Loi |  | mirna.loi@leonardocompany.com |  |  |
| SIC-ID-000000003665 | Mluisa | Spezzati |  | mluisa.spezzati@gmail.com |  |  |
| SIC-ID-000000003666 | Mmm-bop |  |  | mmm-bop@hotmail.it |  |  |
| SIC-ID-000000003667 | Mmta |  |  | mmt65a@gmail.com |  |  |
| SIC-ID-000000003668 | Mnegro80 |  |  | mnegro80@gmail.com |  |  |
| SIC-ID-000000003669 | Mo-88 |  |  | mo-88@live.it |  |  |
| SIC-ID-000000003670 | Modellimkt |  |  | modellimkt@gmail.com |  |  |
| SIC-ID-000000003671 |  |  | MODENA MICHELA LAVORAZIONE MATERIE PLASTICHE | michelamodena@pec.it | Piove di Sacco | PD |
| SIC-ID-000000003672 | Moira | Benvegnu |  | moira.benvegnu@gmail.com |  |  |
| SIC-ID-000000003673 | Molinobergamini |  |  | molinobergamini@libero.it |  |  |
| SIC-ID-000000003674 | Mon Apr 23 1973 00:00:00 gmt+0100 (central european standard time) |  | Mon Apr 23 1973 00:00:00 gmt+0100 (central european standard time) | 23aprile73@libero.it |  |  |
| SIC-ID-000000003675 | Monia |  |  | monia@zambonmarmi.it |  |  |
| SIC-ID-000000003676 | Moniam |  |  | moniam@libero.it |  |  |
| SIC-ID-000000003677 | Monica | Bissacco |  | monica.bissacco@studiomenin.it |  |  |
| SIC-ID-000000003678 | Monica | Marenghi67 |  | monica.marenghi67@gmail.com |  |  |
| SIC-ID-000000003679 | Monica | Riello |  | monica.riello@studiosd.it |  |  |
| SIC-ID-000000003680 | Monica | Spiga79 |  | monica.spiga79@gmail.com |  |  |
| SIC-ID-000000003681 | Monicafonsato |  |  | monicafonsato@gmail.com |  |  |
| SIC-ID-000000003682 | Montefortemario1 | Monteforte |  | montefortemario1@gmail.com |  |  |
| SIC-ID-000000003683 | Monteleoneantonietta |  |  | monteleoneantonietta@libero.it |  |  |
| SIC-ID-000000003684 | Moretti Sas |  | Moretti Sas | moretticostruzionisas@gmail.com |  |  |
| SIC-ID-000000003685 | Morini_alex |  |  | morini_alex@hotmail.com |  |  |
| SIC-ID-000000003686 | Motorman | Loreo |  | motorman.loreo@gmail.com |  |  |
| SIC-ID-000000003687 | Moutacharifo |  |  | moutacharifo@gmail.com |  |  |
| SIC-ID-000000003688 | Movida Design srl |  | Movida Design srl | movidadesign@gmail.com |  |  |
| SIC-ID-000000003689 | Mpizzi |  |  | mpizzi@lavoro.gov.it |  |  |
| SIC-ID-000000003690 | Mrrosabo |  |  | mrrosabo@libero.it |  |  |
| SIC-ID-000000003691 | Mt | Parente |  | mt.parente@yahoo.it |  |  |
| SIC-ID-000000003692 | Mtvsrl |  | Mtvsrl | mtvsrl@libero.it |  |  |
| SIC-ID-000000003693 | MultiGrafica | Printing |  | info@multigrafica.net |  |  |
| SIC-ID-000000003694 | Musikana |  |  | musikana@libero.it |  |  |
| SIC-ID-000000003695 | Mwrlife |  |  | mwrlife@globalewallet.com |  |  |
| SIC-ID-000000003696 | Nabilross |  |  | 9nabilross7@gmail.com |  |  |
| SIC-ID-000000003697 | Napoligaetano81 |  |  | napoligaetano81@gmail.com |  |  |
| SIC-ID-000000003698 | Napolitano_silvia |  |  | napolitano_silvia@libero.it |  |  |
| SIC-ID-000000003699 | Nardinge |  |  | nardinge@libero.it |  |  |
| SIC-ID-000000003700 | Natystani |  |  | natystani@icloud.com |  |  |
| SIC-ID-000000003701 | Nelloesimo |  |  | nelloesimo@libero.it |  |  |
| SIC-ID-000000003702 | Neridermo |  |  | neridermo@libero.lt |  |  |
| SIC-ID-000000003703 | Nespoli |  |  | nespoli@cerchioblu.org |  |  |
| SIC-ID-000000003704 | Newedil_t Srl |  | NEW-EDIL SRL | newedil_t@libero.it | Rosolina | RO |
| SIC-ID-000000003705 | Newfly76 |  |  | newfly76@hotmail.it |  |  |
| SIC-ID-000000003706 | Newlinepro | Bg |  | newlinepro.bg@gmail.com |  |  |
| SIC-ID-000000003707 | Newlinesm |  |  | newlinesm@libero.it |  |  |
| SIC-ID-000000003708 | News |  |  | news@portaleconsulenti.it |  |  |
| SIC-ID-000000003709 | News | Gruppomas |  | news.gruppomas@gmail.com |  |  |
| SIC-ID-000000003710 | Newsteteys |  |  | newsteteys@gmail.com |  |  |
| SIC-ID-000000003711 | Nextimpianti2016 |  | Nextimpianti2016 | nextimpianti2016@gmail.com |  |  |
| SIC-ID-000000003712 | Niassekhadim1501 |  |  | niassekhadim1501@gmail.com |  |  |
| SIC-ID-000000003713 | Niazig11 |  |  | niazig11@gmail.com |  |  |
| SIC-ID-000000003714 | Nicohd1340 |  |  | nicohd1340@libero.it |  |  |
| SIC-ID-000000003715 | Nicola | Passerini |  | nicola.passerini@studiolegalepasserini.it |  |  |
| SIC-ID-000000003716 | Nicola | Subito |  | utente-nvifgh2rj@messaggi.subito.it |  |  |
| SIC-ID-000000003717 | Nicolapasta |  |  | nicolapasta@alice.it |  |  |
| SIC-ID-000000003718 | Nicolapiccoli | Piccoli |  | nicolapiccoli.piccoli@gmail.com |  |  |
| SIC-ID-000000003719 | Nicolemondin90 |  |  | nicolemondin90@gmail.com |  |  |
| SIC-ID-000000003720 | Nikhilfayaz |  |  | nikhilfayaz@gmail.com |  |  |
| SIC-ID-000000003721 | Nikupalade |  |  | nikupalade@gmail.com |  |  |
| SIC-ID-000000003722 | Nildesava |  |  | nildesava@libero.it |  |  |
| SIC-ID-000000003723 | Nisigiuseppe |  |  | nisigiuseppe@yahoo.it |  |  |
| SIC-ID-000000003724 | Nives | Conf |  | nives.conf@libero.it |  |  |
| SIC-ID-000000003725 | Nlalanne |  |  | nlalanne@alice.it |  |  |
| SIC-ID-000000003726 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | biglucaoffice@gmail.com |  |  |
| SIC-ID-000000003727 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | alessiafulgheri79@gmail.com |  |  |
| SIC-ID-000000003728 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | ghelli.m@gmail.com |  |  |
| SIC-ID-000000003729 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | eddittica@tin.it |  |  |
| SIC-ID-000000003730 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | noemi.rita@venditoreadistanza.com |  |  |
| SIC-ID-000000003731 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | mari.asaro62@gmail.com |  |  |
| SIC-ID-000000003732 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | michi.lazza@gmail.com |  |  |
| SIC-ID-000000003733 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | alboino16@gmail.com |  |  |
| SIC-ID-000000003734 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | bitbuilding.online@gmail.com |  |  |
| SIC-ID-000000003735 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | bobisse@libero.it |  |  |
| SIC-ID-000000003736 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | gestione.partecipazioni@ivgspa.it |  |  |
| SIC-ID-000000003737 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | crivellari.f40@libero.it |  |  |
| SIC-ID-000000003738 | Nome Cognome email telefono id cliente backup_sicu |  | Nome Cognome email telefono id cliente backup_sicu | dario.renesto@libero.it |  |  |
| SIC-ID-000000003739 | Nonloso-1969 |  |  | nonloso-1969@libero.it |  |  |
| SIC-ID-000000003740 | Nordiofederico |  |  | nordiofederico@hotmail.com |  |  |
| SIC-ID-000000003741 | Nordiox |  |  | nordiox@libero.it |  |  |
| SIC-ID-000000003742 | Noylen |  |  | noylen@hotmail.com |  |  |
| SIC-ID-000000003743 | Nss_sistemasinistriunipol |  |  | nss_sistemasinistriunipol@unipol.it |  |  |
| SIC-ID-000000003744 | Numero | Italia |  | info@numeroverde.com |  |  |
| SIC-ID-000000003745 | Nuovabelpesca |  |  | info@nuovabelpesca.it |  |  |
| SIC-ID-000000003746 | Nuovaprined | Prined |  | nuovaprined@gmail.com |  |  |
| SIC-ID-000000003747 | Nuovatmsrl |  | Nuovatmsrl | nuovatmsrl@libero.it |  |  |
| SIC-ID-000000003748 | Nuovoabitare2 0 |  | NUOVO ABITARE VENETO S.R.L. | nuovoabitare2.0@gmail.com | Valdobbiadene | TV |
| SIC-ID-000000003749 | Nuvolettogianfranco |  |  | nuvolettogianfranco@commercialeferramenta.it |  |  |
| SIC-ID-000000003750 | Nvste80 |  |  | nvste80@gmail.com |  |  |
| SIC-ID-000000003751 | Odontoiatriacavarzere |  |  | odontoiatriacavarzere@gmail.com |  |  |
| SIC-ID-000000003752 | Ofbellicapelli |  |  | ofbellicapelli@gmail.com |  |  |
| SIC-ID-000000003753 | Ofrizzo |  |  | ofrizzo@libero.it |  |  |
| SIC-ID-000000003754 | Okoroafor | chisom |  | okoroaforanaelechisom@yahoo.com |  |  |
| SIC-ID-000000003755 | Olmes | Trapella |  | olmes.trapella@libero.it |  |  |
| SIC-ID-000000003756 | Omar | Ruggeri |  | omar.ruggeri@gmail.com |  |  |
| SIC-ID-000000003757 | Omar Costruzioni |  | Omar Costruzioni | omarcostruzioni@libero.it |  |  |
| SIC-ID-000000003758 | Omarbiker73 |  |  | omarbiker73@yahoo.it |  |  |
| SIC-ID-000000003759 | Omarcappelletto |  |  | omarcappelletto@gmail.com |  |  |
| SIC-ID-000000003760 | Omarmoutacharif78 |  |  | omarmoutacharif78@gmail.com |  |  |
| SIC-ID-000000003761 | Omarmoutscharif78 |  |  | omarmoutscharif78@gmail.com |  |  |
| SIC-ID-000000003762 | Ombre71 |  |  | ombre71@hotmail.com |  |  |
| SIC-ID-000000003763 | Omerican81 |  |  | omerican81@gmail.com |  |  |
| SIC-ID-000000003764 | Oneanboards |  |  | oneanboards@gmail.com |  |  |
| SIC-ID-000000003765 | Oneraul |  |  | oneraul@hotmail.com |  |  |
| SIC-ID-000000003766 | Onofrio | Salvemini |  | onofrio.salvemini@gmail.com |  |  |
| SIC-ID-000000003767 | Ooxtx |  |  | 999ooxtx@gmail.com |  |  |
| SIC-ID-000000003768 | Orbital_76 |  |  | orbital_76@libero.it |  |  |
| SIC-ID-000000003769 | Oriettapaga |  |  | oriettapaga@gmail.com |  |  |
| SIC-ID-000000003770 | Orlando |  |  | e.orlando@ogcostruzioni.it |  |  |
| SIC-ID-000000003771 | Orlandogrossato |  |  | orlandogrossato@gmail.com |  |  |
| SIC-ID-000000003772 | Orlaxxx |  |  | orlaxxx@libero.it |  |  |
| SIC-ID-000000003773 | Ortofrutticolapanzin |  |  | ortofrutticolapanzin@libero.it |  |  |
| SIC-ID-000000003774 | Oscarsemola |  |  | oscarsemola@libero.it |  |  |
| SIC-ID-000000003775 | Osso9687 |  |  | osso9687@gmail.com |  |  |
| SIC-ID-000000003776 | P | Bordoni |  | p.bordoni@pepatrasporti.it |  |  |
| SIC-ID-000000003777 | P | Pezzotta |  | p.pezzotta@soluzionetasse.com |  |  |
| SIC-ID-000000003778 | P | Teramo51 |  | p.teramo51@gmail.com |  |  |
| SIC-ID-000000003779 | P | Zamponi |  | p.zamponi@gmail.com |  |  |
| SIC-ID-000000003780 | Befedchioggia Srl |  | P&B Srl | befedchioggia@gmail.com | Chioggia | VE |
| SIC-ID-000000003781 | Pa | Guglielmini |  | pa.guglielmini@virgilio.it |  |  |
| SIC-ID-000000003782 | Padoan63 |  |  | padoan63@gmail.com |  |  |
| SIC-ID-000000003783 | Padrin | Adriano |  | padrin.adriano@gmail.com |  |  |
| SIC-ID-000000003784 | Padusallestimenti |  |  | padusallestimenti@gmail.com |  |  |
| SIC-ID-000000003785 | Paga |  |  | paga@icloud.com |  |  |
| SIC-ID-000000003786 | Paghe | Sauro |  | paghe.sauro@studipozzato.it |  |  |
| SIC-ID-000000003787 | Paki | Zennaro |  | paki.zennaro@gmail.com |  |  |
| SIC-ID-000000003788 | Palopoli1963 |  |  | palopoli1963@gmail.com |  |  |
| SIC-ID-000000003789 | Pam | Trentin |  | pam.trentin@live.it |  |  |
| SIC-ID-000000003790 | Pampanafrancesco |  |  | pampanafrancesco@tiscali.it |  |  |
| SIC-ID-000000003791 | Panemnostrum |  |  | panemnostrum@virgilio.it |  |  |
| SIC-ID-000000003792 | Panificiofinotti |  | PANIFICIO FINOTTI S.A.S. DI FINOTTI ANDREA & C. | panificiofinotti@gmail.com | PORTO VIRO | RO |
| SIC-ID-000000003793 | Panificio Piva |  | Panificio Piva S.n.c. di Piva Angela & C. | giova.nello@libero.it | Chioggia | VE |
| SIC-ID-000000003794 | Paola | Accordini72 |  | paola.accordini72@gmail.com |  |  |
| SIC-ID-000000003795 | Paola | M17 |  | paola.m17@libero.it |  |  |
| SIC-ID-000000003796 | Paola30 | Rossi |  | paola30.rossi@gmail.com |  |  |
| SIC-ID-000000003797 | Paolabenvenuti70 |  |  | paolabenvenuti70@gmail.com |  |  |
| SIC-ID-000000003798 | Paolafilomeno | Ing |  | paolafilomeno.ing@gmail.com |  |  |
| SIC-ID-000000003799 | Paolagabe80 |  |  | paolagabe80@gmail.com |  |  |
| SIC-ID-000000003800 | Paolapaolelli |  |  | paolapaolelli@gmail.com |  |  |
| SIC-ID-000000003801 | Paolina471 |  |  | paolina471@gmail.com |  |  |
| SIC-ID-000000003802 | Paolo |  |  | conventopaolo@yahoo.it |  |  |
| SIC-ID-000000003803 | Paolo |  |  | marchesinigpaolo@libero.it |  |  |
| SIC-ID-000000003804 | Paolo |  |  | arvedapaolo@fe2000.it |  |  |
| SIC-ID-000000003805 | Paolo |  |  | bernardipaolo@iisciprianicolombo.edu.it |  |  |
| SIC-ID-000000003806 | Paolobaglioni |  |  | info.paolobaglioni@gmail.com |  |  |
| SIC-ID-000000003807 | Paolofenza |  |  | paolofenza@katamail.com |  |  |
| SIC-ID-000000003808 | Paparazzosalvatore | Paparazzo |  | paparazzosalvatore@libero.it |  |  |
| SIC-ID-000000003809 | Paride | Marcato |  | paride.marcato@alice.it |  |  |
| SIC-ID-000000003810 | Parmstef |  |  | parmstef@libero.it |  |  |
| SIC-ID-000000003811 | Pasini | Annamaria63 |  | pasini.annamaria63@gmail.com |  |  |
| SIC-ID-000000003812 | Pasqualemocerino |  |  | pasqualemocerino@yahoo.it |  |  |
| SIC-ID-000000003813 | Pasqualiniccolo |  |  | pasqualiniccolo@libero.it |  |  |
| SIC-ID-000000003814 | Passarellasnc |  | Passarellasnc | passarellasnc@alice.it |  |  |
| SIC-ID-000000003815 | Passionegiardino | Luis |  | passionegiardino@libero.it |  |  |
| SIC-ID-000000003816 | Pasticceriailcantuccio |  |  | pasticceriailcantuccio@gmail.com |  |  |
| SIC-ID-000000003817 | Pasticceriamilani |  |  | pasticceriamilani@libero.it |  |  |
| SIC-ID-000000003818 | Patbocc |  |  | patbocc@icloud.com |  |  |
| SIC-ID-000000003819 | Patexal |  |  | patexal@libero.it |  |  |
| SIC-ID-000000003820 | Patrizia |  |  | patrizia@dinamicaonlus.it |  |  |
| SIC-ID-000000003821 | Patrizia | Fanciulli |  | patrizia.fanciulli@fanscomputer.it |  |  |
| SIC-ID-000000003822 | Patriziafer |  |  | patrizia67fer@gmail.com |  |  |
| SIC-ID-000000003823 | Patriziafregugia |  |  | patriziafregugia@gmail.com |  |  |
| SIC-ID-000000003824 | Patrolwatch |  |  | patrolwatch@yahoo.com |  |  |
| SIC-ID-000000003825 | Patty | Maestri63 |  | patty.maestri63@gmail.com |  |  |
| SIC-ID-000000003826 | Pattypoggianella |  |  | patty72poggianella@yahoo.it |  |  |
| SIC-ID-000000003827 | Payment | Shahin |  | payment.shahin@gmail.com |  |  |
| SIC-ID-000000003828 | Pepa | Trasporti |  | m.pepa@pepatrasporti.it |  |  |
| SIC-ID-000000003829 | Perito | Em |  | perito.em@libero.it |  |  |
| SIC-ID-000000003830 | Peritoedilepenzo |  | Peritoedilepenzo | peritoedilepenzo@hotmail.it |  |  |
| SIC-ID-000000003831 | Perlemurrine |  |  | perlemurrine@hotmail.it |  |  |
| SIC-ID-000000003832 | Perosanicola |  |  | perosanicola@gmail.com |  |  |
| SIC-ID-000000003833 | PERRONE GROUP SRL |  | PERRONE GROUP SRL | 98071preventivi@gruppoperrone.com |  |  |
| SIC-ID-000000003834 | Peruchglobal |  | Peruchglobal | peruchglobal@gmail.com |  |  |
| SIC-ID-000000003835 | Pfossato14 |  |  | pfossato14@gmail.com |  |  |
| SIC-ID-000000003836 | Pgbeppi |  |  | pgbeppi@libero.it |  |  |
| SIC-ID-000000003837 | Piadaservice |  | Piadaservice | piadaservice@gmail.com |  |  |
| SIC-ID-000000003838 |  |  | PIADINERIA DAL BAFFO DI FERRO RICCARDO | piadineriadalbaffo@pec.it | Porto Viro | RO |
| SIC-ID-000000003839 | Piano | Club 21 |  | piano.club.21@gmail.com |  |  |
| SIC-ID-000000003840 | Pianob | Club 21 |  | pianob.club.21@gmail.com |  |  |
| SIC-ID-000000003841 | Pibartolini |  |  | pibartolini@libero.it |  |  |
| SIC-ID-000000003842 | Piccoli | Giacomo |  | piccoli.giacomo@hotmail.it |  |  |
| SIC-ID-000000003843 | Piemmerestaurisnc |  | PIEMME RESTAURI SNC di Piron Ighlin e Munegato Marco | piemmerestaurisnc@gmail.com | Campolongo Maggiore | VE |
| SIC-ID-000000003844 | Pier | Veron |  | pier.veron@gmail.com |  |  |
| SIC-ID-000000003845 | Pierfrancescocaporali |  |  | pierfrancescocaporali@gmail.com |  |  |
| SIC-ID-000000003846 | Piero | Gallimberti |  | pierogal@virgilio.it |  |  |
| SIC-ID-000000003847 | Piero | Mapelli |  | info@edilmapuno.com |  |  |
| SIC-ID-000000003848 | Pilar2001 |  |  | pilar2001@hotmail.com |  |  |
| SIC-ID-000000003849 | Pinapatrizia71 |  |  | pinapatrizia71@gmail.com |  |  |
| SIC-ID-000000003850 | Pioppi797 |  |  | pioppi797@gmail.com |  |  |
| SIC-ID-000000003851 | Pirilma |  |  | pirilma@libero.it |  |  |
| SIC-ID-000000003852 | Pistaura |  |  | pistaura@libero.it |  |  |
| SIC-ID-000000003853 | Pitfava |  |  | pitfava@gmail.com |  |  |
| SIC-ID-000000003854 | pixart | printing |  | info.tecnica@pixartprinting.com |  |  |
| SIC-ID-000000003855 | Pizzeria Export TDP |  | Pizza Export Kennedy di Bertaglia Valerio | pizzeriakennedy@libero.it | Corbola | RO |
| SIC-ID-000000003856 | Pizzaideabertoncello |  |  | pizzaideabertoncello@alice.it |  |  |
| SIC-ID-000000003857 | Pizzogerardo78 |  |  | pizzogerardo78@gmail.com |  |  |
| SIC-ID-000000003858 | Planbdao |  |  | planbdao@gmail.com |  |  |
| SIC-ID-000000003859 | Pmf Srl |  | Pmf Srl | pmf.srl@gmail.com |  |  |
| SIC-ID-000000003860 | Polesine | Acque |  | info@polesineacque.it |  |  |
| SIC-ID-000000003861 | Pongetti | A |  | pongetti.a@tiscali.it |  |  |
| SIC-ID-000000003862 | Ponzettomartino |  |  | ponzettomartino@alice.it |  |  |
| SIC-ID-000000003863 | Portaleanalia |  |  | portaleanalia@hotmail.com |  |  |
| SIC-ID-000000003864 | Portoviro |  |  | portoviro@enaip.veneto.it |  |  |
| SIC-ID-000000003865 | Portoviro |  |  | portoviro@gabetti.it |  |  |
| SIC-ID-000000003866 | Portoviro |  |  | portoviro@sogert.it |  |  |
| SIC-ID-000000003867 | Posetilsrl |  | Posetilsrl | posetilsrl@yahoo.it |  |  |
| SIC-ID-000000003868 | Post | Vita |  | postvenditavita@alleanza.it |  |  |
| SIC-ID-000000003869 | Posta |  |  | posta@fderosa.com |  |  |
| SIC-ID-000000003870 | Posta |  |  | posta@evelinzubin.com |  |  |
| SIC-ID-000000003871 | Pregnolato | Simonetta |  | pregnolato.simonetta@virgilio.it |  |  |
| SIC-ID-000000003872 | Pregnolato75 |  |  | pregnolato75@gmail.com |  |  |
| SIC-ID-000000003873 | Prenotazioni |  |  | prenotazioni@traghettilines.it |  |  |
| SIC-ID-000000003874 | Presidente |  |  | presidente@artigianatopadovano.it |  |  |
| SIC-ID-000000003875 | Presidente |  |  | presidente@oasiservizi.com |  |  |
| SIC-ID-000000003876 | Presidente |  |  | presidente@soccorsopero.it |  |  |
| SIC-ID-000000003877 | Presidenza |  |  | presidenza@sorgentedeisogni.it |  |  |
| SIC-ID-000000003878 | Primacasa | Fiesso |  | primacasa.fiesso@libero.it |  |  |
| SIC-ID-000000003879 | Primaclassesnc |  | Primaclassesnc | primaclassesnc@gmail.com |  |  |
| SIC-ID-000000003880 | Priorepatrizia72 |  |  | priorepatrizia72@gmail.com |  |  |
| SIC-ID-000000003881 | Professioni |  |  | professioni@studioporzionato.191.it |  |  |
| SIC-ID-000000003882 | Profildelta Sas di sega mariano & c. |  | PROFILDELTA SAS DI SEGA MARIANO & C. | profildelta@libero.it | Porto Viro | RO |
| SIC-ID-000000003883 | Protezione | Bonus |  | protezione.bonus@genertel.it |  |  |
| SIC-ID-000000003884 | Pubbly | System |  | info@pubblysystem.it |  |  |
| SIC-ID-000000003885 | publiARTE | brusaferro |  | info@publiarte.it |  |  |
| SIC-ID-000000003886 | Puntoevirgolasnc |  | Puntoevirgolasnc | puntoevirgolasnc@virgilio.it |  |  |
| SIC-ID-000000003887 | Pvd |  |  | pvd@tiscali.it |  |  |
| SIC-ID-000000003888 | Qualificamarketingsrl |  | Qualificamarketingsrl | qualificamarketingsrl@gmail.com |  |  |
| SIC-ID-000000003889 |  |  | QUBO IMPIANTI DI LAZZARO ANTONIO | info@pec.quboimpianti.com | Abano Terme | PD |
| SIC-ID-000000003890 | R | Angelelli |  | r.angelelli@calcestruzzi.it |  |  |
| SIC-ID-000000003891 | R | Capricci |  | r.capricci@alice.it |  |  |
| SIC-ID-000000003892 | R | Cesari |  | r.cesari@sepsrl.com |  |  |
| SIC-ID-000000003893 | R.c.m. - rinascita cooperativa musicale soc. coop. a r.l. |  | R.C.M. - Rinascita Cooperativa Musicale Soc. Coop. a r.l. | info@cooprcm.it | Taglio di Po | RO |
| SIC-ID-000000003894 | Rachele |  |  | rachele@labadiaimmobiliare.it |  |  |
| SIC-ID-000000003895 | Rachele | Barletta19 |  | rachele.barletta19@libero.it |  |  |
| SIC-ID-000000003896 | Radice | Sonora |  | radice.sonora@libero.it |  |  |
| SIC-ID-000000003897 | Radiobruno | 1 |  | radiobruno.1@tin.it |  |  |
| SIC-ID-000000003898 | Raffaela | Portieri |  | raffaela.portieri@gmail.com |  |  |
| SIC-ID-000000003899 | Raffaella | Marcato |  | raffaella.marcato@virgilio.it |  |  |
| SIC-ID-000000003900 | Ragazzi | Sabrina68 |  | ragazzi.sabrina68@gmail.com |  |  |
| SIC-ID-000000003901 | Raimpianti77 |  | Raimpianti77 | raimpianti77@gmail.com |  |  |
| SIC-ID-000000003902 | Ralumini70 | Rauta |  | ralumini70@yahoo.it |  |  |
| SIC-ID-000000003903 | Rambaldiclaudia |  |  | rambaldiclaudia@libero.it |  |  |
| SIC-ID-000000003904 | Www Webposte |  | RANZATO SIMONE DITTA INDIVIDUALE - CHIOSCO DAI BOLLA | www.webposte@pcert.it | Chioggia | VE |
| SIC-ID-000000003905 | Rasgaz12 |  |  | rasgaz12@yahoo.fr |  |  |
| SIC-ID-000000003906 | Razum | Petra |  | razum.petra@gmail.com |  |  |
| SIC-ID-000000003907 | Re_boia64 |  |  | re_boia64@yahoo.it |  |  |
| SIC-ID-000000003908 | Ready Room Srl |  | Ready Room Srl | info@centom.it |  |  |
| SIC-ID-000000003909 | Ready Room Srl |  | Ready Room Srl | segreteria100m@gmail.com |  |  |
| SIC-ID-000000003910 | Ready Room Srl |  | Ready Room Srl | info@angelomarcoccia.it |  |  |
| SIC-ID-000000003911 | Realestate | It |  | realestate.it@housers.com |  |  |
| SIC-ID-000000003912 | Reception |  |  | reception@gardalandhotel.it |  |  |
| SIC-ID-000000003913 | Recordsrl |  | Recordsrl | info.recordsrl@gmail.com |  |  |
| SIC-ID-000000003914 | Recruiting |  |  | recruiting@sosrelazioni.it |  |  |
| SIC-ID-000000003915 | Redazione |  |  | redazione@4surf.it |  |  |
| SIC-ID-000000003916 | Refund | Token |  | refundhypertoken@secretary.net |  |  |
| SIC-ID-000000003917 | Remigio | Ruzzante |  | remigio.ruzzante@libero.it |  |  |
| SIC-ID-000000003918 | Renatopiccolo568 |  |  | renatopiccolo568@gmai.com |  |  |
| SIC-ID-000000003919 | Renzoflaviomoretto | Engine v1_2026-02-24_03-38 estr |  | renzoflaviomoretto@gmail.com |  |  |
| SIC-ID-000000003920 | Rescueguardian2015 |  |  | rescueguardian2015@gmail.com |  |  |
| SIC-ID-000000003921 | Reve |  |  | re2014ve@gmail.com |  |  |
| SIC-ID-000000003922 | Reverie1967 |  |  | reverie1967@gmail.com |  |  |
| SIC-ID-000000003923 | Rfabbri75 |  |  | rfabbri75@yahoo.it |  |  |
| SIC-ID-000000003924 | Rgiyoev |  |  | rgiyoev@bk.ru |  |  |
| SIC-ID-000000003925 | Riccardo |  |  | riccardo@samboeassociati.it |  |  |
| SIC-ID-000000003926 | Riccardoferioli95 | Ferioli |  | riccardoferioli95@gmail.com |  |  |
| SIC-ID-000000003927 | Ricerca |  |  | ricerca@agentidoma.it |  |  |
| SIC-ID-000000003928 | Richieste |  |  | richieste@acquevenete.it |  |  |
| SIC-ID-000000003929 | Richlawon |  |  | richlawon@yahoo.com |  |  |
| SIC-ID-000000003930 | Rickigilardoni |  |  | rickigilardoni@gmail.com |  |  |
| SIC-ID-000000003931 | Ricky |  |  | ricky@radiofiera.it |  |  |
| SIC-ID-000000003932 | Rikymarchetti |  |  | rikymarchetti@hotmail.it |  |  |
| SIC-ID-000000003933 | Rimbaglionita66 |  |  | rimbaglionita66@gmail.com |  |  |
| SIC-ID-000000003934 | Rinodj | Vendemiati |  | rinodj.vendemiati@gmail.com |  |  |
| SIC-ID-000000003935 | Ristoranteanticoguerriero |  | Ristoranteanticoguerriero | ristoranteanticoguerriero@gmail.com |  |  |
| SIC-ID-000000003936 | Ristorantepeppino |  | Ristorantepeppino | ristorantepeppino@gmail.com |  |  |
| SIC-ID-000000003937 | Ritacalia1967 |  |  | ritacalia1967@libero.it |  |  |
| SIC-ID-000000003938 | Ritadibella1968 |  |  | ritadibella1968@gmail.com |  |  |
| SIC-ID-000000003939 | Ritaloveno67 |  |  | ritaloveno67@gmail.com |  |  |
| SIC-ID-000000003940 | Ritaschiatti |  |  | ritaschiatti@gmail.com |  |  |
| SIC-ID-000000003941 | Rmpubblicita |  |  | rmpubblicita@live.it |  |  |
| SIC-ID-000000003942 | Robboetto94 |  |  | robboetto94@gmail.com |  |  |
| SIC-ID-000000003943 | Roberrodrago1983 |  |  | roberrodrago1983@gmail.com |  |  |
| SIC-ID-000000003944 | Roberta | Scagnolari |  | roberta.scagnolari@gmail.com |  |  |
| SIC-ID-000000003945 | Robertaiacurso |  |  | robertaiacurso@gmail.com |  |  |
| SIC-ID-000000003946 | Robertapezzolato |  |  | robertapezzolato@live.it |  |  |
| SIC-ID-000000003947 | Roberto |  |  | fiorimantiroberto@gmail.com |  |  |
| SIC-ID-000000003948 | Roberto | Tortello |  | tortello1tortelloroberto@gmail.com |  |  |
| SIC-ID-000000003949 | Roberto |  |  | roberto@drserviziufficio.it |  |  |
| SIC-ID-000000003950 | Robertodrago |  |  | robertodrago@mail.com |  |  |
| SIC-ID-000000003951 | Robertodrago1983 |  |  | robertodrago1983@gmail.com |  |  |
| SIC-ID-000000003952 | Robertodrago1984 |  |  | robertodrago1984@gmail.com |  |  |
| SIC-ID-000000003953 | Robertodrago198e |  |  | robertodrago198e@gmail.com |  |  |
| SIC-ID-000000003954 | Robertodrago1993 |  |  | robertodrago1993@gmail.com |  |  |
| SIC-ID-000000003955 | Robertogatto |  |  | robertogatto@blsd.info |  |  |
| SIC-ID-000000003956 | Robertograssi3 |  |  | robertograssi3@alice.it |  |  |
| SIC-ID-000000003957 | Robertolongu |  |  | robertolongu@gmail.com |  |  |
| SIC-ID-000000003958 | Robrtodrago1983 |  |  | robrtodrago1983@gmail.com |  |  |
| SIC-ID-000000003959 | Robylegnaro |  |  | robylegnaro@libero.it |  |  |
| SIC-ID-000000003960 | Roccato | Elisa |  | roccato.elisa@email.it |  |  |
| SIC-ID-000000003961 | Rocchipregnolatosnc |  | Rocchipregnolatosnc | rocchipregnolatosnc@virgilio.it |  |  |
| SIC-ID-000000003962 | Roccoannalisa16 |  |  | roccoannalisa16@gmail.com |  |  |
| SIC-ID-000000003963 | Roccomarinoconslavoro | Marino |  | roccomarinoconslavoro@gmail.com |  |  |
| SIC-ID-000000003964 | Rodeghergraphic |  |  | rodeghergraphic@tiscali.it |  |  |
| SIC-ID-000000003965 | Rominaruzzetti |  |  | rominaruzzetti@yahoo.it |  |  |
| SIC-ID-000000003966 | Ronertodrago1983 |  |  | ronertodrago1983@gmail.com |  |  |
| SIC-ID-000000003967 | Rosacolagiacomo |  |  | rosacolagiacomo@libero.it |  |  |
| SIC-ID-000000003968 | Rosanna | Buono61 |  | rosanna.buono61@gmail.com |  |  |
| SIC-ID-000000003969 | Rosariapanzuti |  |  | rosariapanzuti@gmail.com |  |  |
| SIC-ID-000000003970 | Rosario186 |  |  | rosario186@hotmail.it |  |  |
| SIC-ID-000000003971 | Rosazulema |  |  | rosazulema@hotmail.it |  |  |
| SIC-ID-000000003972 | Roscip80 |  |  | roscip80@gmail.com |  |  |
| SIC-ID-000000003973 | Www Capeto2008 |  | ROSMAR PARK SNC DI CASSETTA MIRIAM & C. | www.capeto2008@libero.it | Rosolina | RO |
| SIC-ID-000000003974 | Rospo1971 |  |  | rospo1971@libero.it |  |  |
| SIC-ID-000000003975 | Rosscar |  |  | rosscar@libero.it |  |  |
| SIC-ID-000000003976 | Rossi | Alessandra |  | rossi.alessandra@live.it |  |  |
| SIC-ID-000000003977 | Rossini_rondina |  |  | rossini_rondina@alice.it |  |  |
| SIC-ID-000000003978 | Rouge | Diamond |  | rouge.diamond@tiscali.it |  |  |
| SIC-ID-000000003979 | Route 443 di ferro gessica |  | ROUTE 443 di Ferro Gessica | jessicaferro85@gmail.com | Villadose | RO |
| SIC-ID-000000003980 | Rovai | Lucia |  | rovai.lucia@gmail.com |  |  |
| SIC-ID-000000003981 | Rovigo |  |  | rovigo@inail.it |  |  |
| SIC-ID-000000003982 | Rovigo |  |  | rovigo@indalo.it |  |  |
| SIC-ID-000000003983 | Rovigo | UISP |  | rovigo@uisp.it |  |  |
| SIC-ID-000000003984 | Rovigo-premi |  |  | rovigo-premi@inail.it |  |  |
| SIC-ID-000000003985 | Rovigoallestimenti |  |  | rovigoallestimenti@libero.it |  |  |
| SIC-ID-000000003986 | Roxana | Dias |  | roxana.dias@hotmail.it |  |  |
| SIC-ID-000000003987 | Roxdemar |  |  | roxdemar@gmail.com |  |  |
| SIC-ID-000000003988 | Rperazzolo |  |  | rperazzolo@asl14chioggia.veneto.it |  |  |
| SIC-ID-000000003989 | Rsantangelo |  |  | rsantangelo@asl.at.it |  |  |
| SIC-ID-000000003990 | Ruben_1983 |  |  | ruben_1983@libero.it |  |  |
| SIC-ID-000000003991 | Ruffo_angela | Ruffo |  | ruffo_angela@libero.it |  |  |
| SIC-ID-000000003992 | Russoleoluca |  |  | russoleoluca@gmail.com |  |  |
| SIC-ID-000000003993 | S | Marotta |  | s.marotta@marottahse.it |  |  |
| SIC-ID-000000003994 | S.c.s. Costruzioni Edili S.r.l. Societa Unipersonale |  | S.c.s. Costruzioni Edili S.r.l. Societa Unipersonale | info@scscostruzioniedilisrl.com |  |  |
| SIC-ID-000000003995 | Sabina |  |  | sabina@studioborgato.it |  |  |
| SIC-ID-000000003996 | Sabrina |  |  | sabrina@cssanna.com |  |  |
| SIC-ID-000000003997 | Sabrina | Dore |  | sabrina.dore@alice.it |  |  |
| SIC-ID-000000003998 | Sabrinaka80 |  |  | sabrinaka80@gmail.com |  |  |
| SIC-ID-000000003999 | Sabrinap3691 |  |  | sabrinap3691@gmail.com |  |  |
| SIC-ID-000000004000 | Saed | Ro |  | saed.ro@virgilio.it |  |  |
| SIC-ID-000000004001 | Salgaim Ecologic spa |  | SALGAIM ECOLOGIC SPA | amministrazione@salgaim.it | Padova | PD |
| SIC-ID-000000004002 | Salute & movimento a.s.d. |  | SALUTE & MOVIMENTO A.S.D. | lessenzadibellessere@gmail.com | Porto Tolle | RO |
| SIC-ID-000000004003 | Salvamento | informazioni |  | info@salvamentoacademy.com |  |  |
| SIC-ID-000000004004 | Salvatore | Masia1966 |  | salvatore.masia1966@gmail.com |  |  |
| SIC-ID-000000004005 | Salvatoresprio | Arch |  | salvatoresprio.arch@gmail.com |  |  |
| SIC-ID-000000004006 | Salvo | Belllo |  | salvo.belllo@hotmail.it |  |  |
| SIC-ID-000000004007 | Salvofu40 |  |  | salvofu40@gmail.com |  |  |
| SIC-ID-000000004008 | Samantamicheletti |  |  | samantamicheletti@gmail.com |  |  |
| SIC-ID-000000004009 | Samantha |  |  | samantha@autocarrozzerianico.it |  |  |
| SIC-ID-000000004010 | Samantha | To |  | samantha.to@alice.it |  |  |
| SIC-ID-000000004011 | Samehkhlefa30 |  |  | samehkhlefa30@gmail.com |  |  |
| SIC-ID-000000004012 | Sampeibettin |  |  | sampeibettin@libero.it |  |  |
| SIC-ID-000000004013 | Sanchez | Gemmac |  | sanchez.gemmac@gmail.com |  |  |
| SIC-ID-000000004014 | Sandra | Boscolo |  | sandra.boscolo@studiorosteghin.it |  |  |
| SIC-ID-000000004015 | Sandromarangoni83 |  |  | sandromarangoni83@gmail.com |  |  |
| SIC-ID-000000004016 | Sangiusto | Oratorio |  | sangiusto.oratorio@gmail.com |  |  |
| SIC-ID-000000004017 | Sanounoubaba |  |  | sanounoubaba@gmail.com |  |  |
| SIC-ID-000000004018 | Sante | Casini |  | sante.casini@marcegaglia.com |  |  |
| SIC-ID-000000004019 | Santoriello | Mara |  | santoriello.mara@gmail.com |  |  |
| SIC-ID-000000004020 | Sarabordina87 |  |  | sarabordina87@tiscali.it |  |  |
| SIC-ID-000000004021 | Sarotto Group |  | Sarotto Group | info@sarotto.it |  |  |
| SIC-ID-000000004022 | Sarotto Group |  | Sarotto Group | sarotto@sarotto.it |  |  |
| SIC-ID-000000004023 | Sarri |  |  | sarri@ebret.it |  |  |
| SIC-ID-000000004024 | Sarta | Carolina |  | sarta.carolina@gmail.com |  |  |
| SIC-ID-000000004025 | Sartoriamontaga |  |  | sartoriamontagnana@gmail.com |  |  |
| SIC-ID-000000004026 | Sauro Vivian |  | SAURO E THOMAS S.N.C. DI VIVIAN SAURO E C. | sauro.vivian@gmail.com | ROSOLINA | RO |
| SIC-ID-000000004027 | Sauroviviani |  |  | sauroviviani@gmail.com |  |  |
| SIC-ID-000000004028 | Sayadtoor8 |  |  | sayadtoor8@gmail.com |  |  |
| SIC-ID-000000004029 | Sbappa69 |  |  | sbappa69@gmail.com |  |  |
| SIC-ID-000000004030 | Sbiasioli |  |  | sbiasioli@bancadria.it |  |  |
| SIC-ID-000000004031 | Sbposta |  |  | sbposta@outlook.com |  |  |
| SIC-ID-000000004032 | Sbs | Professionisti |  | sbs.professionisti@gmail.com |  |  |
| SIC-ID-000000004033 | Scarabel |  |  | f.scarabel@iesbiogas.it |  |  |
| SIC-ID-000000004034 | SCIACCHITANO | Savio Giuseppe |  | sg.sciacchitano@calcestruzzi.it |  |  |
| SIC-ID-000000004035 | Scorze |  |  | scorze@cattolica.it |  |  |
| SIC-ID-000000004036 | Screamin |  |  | screamin_eagle@hotmail.it |  |  |
| SIC-ID-000000004037 | Scuolaguidasicura |  |  | scuolaguidasicura@gmail.com |  |  |
| SIC-ID-000000004038 | Scuttariservice |  | Scuttariservice | scuttariservice@gmail.com |  |  |
| SIC-ID-000000004039 | Sds | Ferro |  | sds.ferro@libero.it |  |  |
| SIC-ID-000000004040 | Sede | Rovigo |  | sede@collegio.geometri.ro.it |  |  |
| SIC-ID-000000004041 | Segalaenrica |  |  | segalaenrica@alice.it |  |  |
| SIC-ID-000000004042 | Segato |  |  | f.segato@ste-energy.com |  |  |
| SIC-ID-000000004043 | Segreteria |  |  | segreteria@ebav.it |  |  |
| SIC-ID-000000004044 | Segreteria |  |  | segreteria@ateneoimpresa.it |  |  |
| SIC-ID-000000004045 | Segreteria Srl |  | Segreteria Srl | segreteria@outsphera.it |  |  |
| SIC-ID-000000004046 | Segreteriaconsulenze |  |  | segreteriaconsulenze@gmail.com |  |  |
| SIC-ID-000000004047 | Segreterianazionale |  |  | segreterianazionale@anfos.it |  |  |
| SIC-ID-000000004048 | Senrico |  |  | senrico@lattebusche.it |  |  |
| SIC-ID-000000004049 | Serena |  |  | bucciserena72@gmail.com |  |  |
| SIC-ID-000000004050 | Serena-boin |  |  | serena-boin@alice.it |  |  |
| SIC-ID-000000004051 | Sergio | Pizzo 1992 |  | sergio.pizzo.1992@gmail.com |  |  |
| SIC-ID-000000004052 | Sergiobevilacqua |  |  | info.sergiobevilacqua@gmail.com |  |  |
| SIC-ID-000000004053 | Sergioricci | Cabaret |  | sergioricci.cabaret@alice.it |  |  |
| SIC-ID-000000004054 | Service |  | Service | service@hyperbc.com |  |  |
| SIC-ID-000000004055 | Service At |  | Service At | service.at@organic.plus |  |  |
| SIC-ID-000000004056 | Service Plants |  | Service Plants | serviceplants@yahoo.it |  |  |
| SIC-ID-000000004057 | Amministrazione - Service srl |  | SERVICE PLANTS S.r.l. | amministrazione@serviceplants.it | San Costanzo | PU |
| SIC-ID-000000004058 | Servizi Online |  | Servizi Online | servizi.online@agsm.it |  |  |
| SIC-ID-000000004059 | Servizicontabilidozzi |  | Servizicontabilidozzi | servizicontabilidozzi@yahoo.it |  |  |
| SIC-ID-000000004060 | Serviziimmobiliari Rovigo |  | Serviziimmobiliari Rovigo | serviziimmobiliari.rovigo@gmail.com |  |  |
| SIC-ID-000000004061 | Servizio |  | Servizio | servizio_clienti@genertel.it |  |  |
| SIC-ID-000000004062 | Servizio Adria |  | Servizio Adria | vaccinazioniadria@aulss5.veneto.it |  |  |
| SIC-ID-000000004063 | Servizio Ascotrade |  | Servizio Ascotrade | servizio.clienti@ascotrade.it |  |  |
| SIC-ID-000000004064 | Servizio Performance |  | Servizio Performance | servizioclienti@hiperformance.it |  |  |
| SIC-ID-000000004065 | Servizioalcliente |  | Servizioalcliente | servizioalcliente@sinfon.it |  |  |
| SIC-ID-000000004066 | Servizioclienti |  | Servizioclienti | servizioclienti@housers.com |  |  |
| SIC-ID-000000004067 | Servizisalvatempo |  | Servizisalvatempo | servizisalvatempo@gmail.com |  |  |
| SIC-ID-000000004068 | Sesillo |  |  | sesillo@alice.it |  |  |
| SIC-ID-000000004069 | Seunogunruku |  |  | seunogunruku@gmail.com |  |  |
| SIC-ID-000000004070 | Sgaiola |  |  | sgaiola@lavoro.gov.it |  |  |
| SIC-ID-000000004071 | Sgiovanelli |  |  | sgiovanelli@daneurope.org |  |  |
| SIC-ID-000000004072 | Sgsquarcina |  |  | sgsquarcina@virgilio.it |  |  |
| SIC-ID-000000004073 | Sicurezza |  |  | sicurezza@karrell.it |  |  |
| SIC-ID-000000004074 | Sicurezza |  |  | sicurezza@tecnocrane.it |  |  |
| SIC-ID-000000004075 | Sicurezza |  |  | sicurezza@simmerle-ecodrive.it |  |  |
| SIC-ID-000000004076 | Sicurezza |  |  | sicurezza@istitutosantandrea.org |  |  |
| SIC-ID-000000004077 | Sicurezza. | della sicurezza |  | info@sicurezza.pro |  |  |
| SIC-ID-000000004078 | Sicurissimo | Online |  | sicurissimo.online@gmail.com |  |  |
| SIC-ID-000000004079 | Sicuteria |  |  | sicuteria@libero.it |  |  |
| SIC-ID-000000004080 | Sigimar |  |  | sigimar@sigimar.it |  |  |
| SIC-ID-000000004081 | Signormatita |  |  | signormatita@gmail.com |  |  |
| SIC-ID-000000004082 | Silvia | Marzetti |  | marzettisilvia@gmail.com |  |  |
| SIC-ID-000000004083 | Silvia | Passarella |  | silvia.passarella@allenza.it |  |  |
| SIC-ID-000000004084 | Silvia |  |  | silvia@ccdegrandis.it |  |  |
| SIC-ID-000000004085 | Silviabonifazi9 | Bonifazi |  | silviabonifazi9@gmail.com |  |  |
| SIC-ID-000000004086 | Silvybruno17 |  |  | silvybruno17@gmail.com |  |  |
| SIC-ID-000000004087 | Simo | Iesir |  | simo.iesir@gmail.com |  |  |
| SIC-ID-000000004088 | Simo | Pesci |  | simo.pesci@virgilio.it |  |  |
| SIC-ID-000000004089 | Simober |  |  | simober@libero.it |  |  |
| SIC-ID-000000004090 | Simoci24 |  |  | simoci24@hotmail.it |  |  |
| SIC-ID-000000004091 | Simonadalmasso17 |  |  | simonadalmasso17@gmail.com |  |  |
| SIC-ID-000000004092 | Simonagozzi1 |  |  | simonagozzi1@gmail.com |  |  |
| SIC-ID-000000004093 | Simonavendemiati |  |  | simonavendemiati@gmail.com |  |  |
| SIC-ID-000000004094 | Simone |  |  | simone@studioatessarin.it |  |  |
| SIC-ID-000000004095 | Simonebenetazzo |  |  | simonebenetazzo@proevosrl.it |  |  |
| SIC-ID-000000004096 | Simonettagurrisi |  |  | simonettagurrisi@gmail.com |  |  |
| SIC-ID-000000004097 | Sinistri |  |  | sinistri@linear.it |  |  |
| SIC-ID-000000004098 | Sinistri |  |  | sinistri@lienar.it |  |  |
| SIC-ID-000000004099 | Sinistri | Genertel |  | sinistri@genertel.it |  |  |
| SIC-ID-000000004100 | Slvtrevisan |  |  | slvtrevisan@yahoo.it |  |  |
| SIC-ID-000000004101 | SMN Design / Interior Design & Brand Identity / Progettazione Boutique, Ristoranti, Bar, Hotel & Uffici |  | SMN Design / Interior Design & Brand Identity / Progettazione Boutique, Ristoranti, Bar, Hotel & Uffici | smn-design@outlook.com |  |  |
| SIC-ID-000000004102 | SMN Design / Interior Design & Brand Identity / Progettazione Boutique, Ristoranti, Bar, Hotel & Uffici |  | SMN Design / Interior Design & Brand Identity / Progettazione Boutique, Ristoranti, Bar, Hotel & Uffici | hello@smndesign.net |  |  |
| SIC-ID-000000004103 | Societa' Metano donada di sante panetto & c snc |  | SOCIETA' METANO DONADA DI SANTE PANETTO & C Snc | metanodonada@gmail.com | Porto Viro | RO |
| SIC-ID-000000004104 | Societaelimarsrl |  | Societaelimarsrl | societaelimarsrl@gmail.com |  |  |
| SIC-ID-000000004105 | Sonia_bea |  |  | sonia_bea@hotmail.it |  |  |
| SIC-ID-000000004106 | Sonny88 |  |  | sonny88@tin.it |  |  |
| SIC-ID-000000004107 | Sophia | 63 |  | sophia.63@libero.it |  |  |
| SIC-ID-000000004108 | Soragni |  |  | soragni@giroldisoragni.it |  |  |
| SIC-ID-000000004109 | Sottomarina |  |  | sottomarina@alleanza.it |  |  |
| SIC-ID-000000004110 | Spazioliberonews Libero news |  | Spazioliberonews Libero news | spazioliberonews@gmail.com |  |  |
| SIC-ID-000000004111 | Specialone | Sannino |  | specialone.sannino@gmail.com |  |  |
| SIC-ID-000000004112 | Spetras |  |  | spetras@tiscali.it |  |  |
| SIC-ID-000000004113 | Spice |  |  | spice@supereva.it |  |  |
| SIC-ID-000000004114 | Spigolon Katia & scarpa billy |  | SPIGOLON KATIA & SCARPA BILLY | katia.spigolon.1987@gmail.com | Chioggia | VE |
| SIC-ID-000000004115 | Srl Logik |  | Srl Logik | srl.logik@gmail.com |  |  |
| SIC-ID-000000004116 | Ssl | Gdea |  | ssl.gdea@gmail.com |  |  |
| SIC-ID-000000004117 | Stabe66 |  |  | stabe66@libero.it |  |  |
| SIC-ID-000000004118 | STABILIMENTO BAGNI LIDO DI PADOVA S.R.L. |  | STABILIMENTO BAGNI LIDO DI PADOVA S.R.L. | bagni_clodia@legalmail.it | Chioggia | VE |
| SIC-ID-000000004119 | Staffid |  |  | info.staffid@gmail.com |  |  |
| SIC-ID-000000004120 | Stbirbes |  |  | stbirbes@gmail.com |  |  |
| SIC-ID-000000004121 | Stcristiano |  |  | stcristiano@gmail.com |  |  |
| SIC-ID-000000004122 | Stefania | Mele75 |  | stefania.mele75@gmail.com |  |  |
| SIC-ID-000000004123 | Stefaniabarca |  |  | stefaniabarca@yahoo.it |  |  |
| SIC-ID-000000004124 | Stefano | Caproni |  | stefano.caproni@libero.it |  |  |
| SIC-ID-000000004125 | Stefano | Decina |  | info@fedelambiente.it |  |  |
| SIC-ID-000000004126 | Stefanopiano70 |  |  | stefanopiano70@gmail.com |  |  |
| SIC-ID-000000004127 | Stefanoturella73 |  |  | stefanoturella73@libero.it |  |  |
| SIC-ID-000000004128 | Stefy | Lazza |  | stefy.lazza@hotmail.com |  |  |
| SIC-ID-000000004129 | Stefy | Ponti |  | stefy.ponti@libero.it |  |  |
| SIC-ID-000000004130 | Stegarescu79 |  |  | stegarescu79@yahoo.com |  |  |
| SIC-ID-000000004131 | Strozzifiorella |  |  | strozzifiorella@tiscali.it |  |  |
| SIC-ID-000000004132 | Stsrltrasporti |  | Stsrltrasporti | stsrltrasporti@gmail.com |  |  |
| SIC-ID-000000004133 | Studio |  | Studio | studio@gamoservizi.it |  |  |
| SIC-ID-000000004134 | Studio |  | Studio | studio@brvarchitetti.it |  |  |
| SIC-ID-000000004135 | studio 3g |  | studio 3g | info@sta3g.it |  |  |
| SIC-ID-000000004136 | Studio Albiero |  | Studio Albiero | studio.albiero@tiscali.it |  |  |
| SIC-ID-000000004137 | Studio Bucatari |  | Studio Bucatari | bucatari@studiobucatari.it |  |  |
| SIC-ID-000000004138 | Studio Cuppoletti |  | Studio Cuppoletti | studio@cuppoletti.it |  |  |
| SIC-ID-000000004139 | Studio Donà / Viro |  | Studio Donà / Viro | portoviro@studiodona.net |  |  |
| SIC-ID-000000004140 | Studio Fenzi |  | Studio Fenzi | studio.fenzi@gmail.com |  |  |
| SIC-ID-000000004141 | Studio Leone-Fell & C. |  | Studio Leone-Fell & C. | inuripoctinaccpt0qbf-smtpreply@mg.msgsndr.us |  |  |
| SIC-ID-000000004142 | Studio Lim ch |  | Studio Lim ch | studio.lim.ch@gmail.com |  |  |
| SIC-ID-000000004143 | studio naturarch | Boscolo Bisto |  | studionaturarch@gmail.com | Chioggia | VE |
| SIC-ID-000000004144 | Studio Poggio |  | Studio Poggio | info@biscaropoggio.it |  |  |
| SIC-ID-000000004145 | Studio Renzotiozzo |  | Studio Renzotiozzo | studio.renzotiozzo@libero.it |  |  |
| SIC-ID-000000004146 | Studio Rossi |  | Studio Rossi | info@studio-rossi.net |  |  |
| SIC-ID-000000004147 | Studio Sixte |  | Studio Sixte | studio.sixte@gmail.com |  |  |
| SIC-ID-000000004148 | Studioedilb |  | STUDIO TECNICO EDIL/B di Bagno Geom. Pierluigi & Bezzi Geom. Gianni Carlo | studioedilb@libero.it | Porto Viro | RO |
| SIC-ID-000000004149 | Studio Vdv |  | Studio Vdv | studio@vdv.it |  |  |
| SIC-ID-000000004150 | Studiobanin |  | Studiobanin | info.studiobanin@libero.it |  |  |
| SIC-ID-000000004151 | Studioboschelli |  | Studioboschelli | studioboschelli@gmail.com |  |  |
| SIC-ID-000000004152 | Studiochiappini Dr. pietro |  | Studiochiappini Dr. pietro | studiochiappini@chimici.it |  |  |
| SIC-ID-000000004153 | Studiocolaiacomo |  | Studiocolaiacomo | studiocolaiacomo@tiscali.it |  |  |
| SIC-ID-000000004154 | Studiodesolei |  | Studiodesolei | studiodesolei@interfree.it |  |  |
| SIC-ID-000000004155 | Studiogaliotto |  | Studiogaliotto | studiogaliotto@galiotto.it |  |  |
| SIC-ID-000000004156 | Studioignaziog Romano |  | Studioignaziog Romano | studioignaziog.romano@gmail.com |  |  |
| SIC-ID-000000004157 | Studioioleterrentin |  | Studioioleterrentin | studioioleterrentin@libero.it |  |  |
| SIC-ID-000000004158 | Studiolanza Lanza |  | Studiolanza Lanza | studiolanza@yahoo.it |  |  |
| SIC-ID-000000004159 | Studiomoscariellodevivo |  | Studiomoscariellodevivo | studiomoscariellodevivo@gmail.com |  |  |
| SIC-ID-000000004160 | Studiopezzato |  | Studiopezzato | studiopezzato@virgilio.it |  |  |
| SIC-ID-000000004161 | Studioravag |  | Studioravag | studioravagnan@gmail.com |  |  |
| SIC-ID-000000004162 | Studiorondinelli |  | Studiorondinelli | studiorondinelli@yahoo.it |  |  |
| SIC-ID-000000004163 | Studiotecnicoaprea |  | Studiotecnicoaprea | studiotecnicoaprea@virgilio.it |  |  |
| SIC-ID-000000004164 | Studiotecnicocrivellari |  | Studiotecnicocrivellari | studiotecnicocrivellari@gmail.com |  |  |
| SIC-ID-000000004165 | Studiothiene |  | Studiothiene | studiothiene@gmail.com |  |  |
| SIC-ID-000000004166 | Sudhirkumarsingh80449 |  |  | sudhirkumarsingh80449@gmail.com |  |  |
| SIC-ID-000000004167 | Sukhwinderpalmadho123 |  |  | sukhwinderpalmadho123@gmail.com |  |  |
| SIC-ID-000000004168 |  |  | SUNRISE RENT SCAVI S.R.L. | sunriserent@libero.it | PORTO VIRO | RO |
| SIC-ID-000000004169 | Supersinger7 |  |  | supersinger7@hotmail.com |  |  |
| SIC-ID-000000004170 | Support |  |  | support@mwrlife.com |  |  |
| SIC-ID-000000004171 | Support+id | Engine v1_2026-02-24_03-38 estr |  | support+id34967@hyperpayhelp.zendesk.com |  |  |
| SIC-ID-000000004172 | Support+id | Engine v1_2026-02-24_03-38 estr |  | support+id45403@hyperpayhelp.zendesk.com |  |  |
| SIC-ID-000000004173 | Support+id | Engine v1_2026-02-24_03-38 estr |  | support+id52004@hyperpayhelp.zendesk.com |  |  |
| SIC-ID-000000004174 | Support+id178784 |  |  | support+id178784@dubaicollin.zendesk.com |  |  |
| SIC-ID-000000004175 | Support+id19613 |  |  | support+id19613@hyperpayhelp.zendesk.com |  |  |
| SIC-ID-000000004176 | Support+id671 |  |  | support+id671@daoversal.zendesk.com |  |  |
| SIC-ID-000000004177 | Support2 |  |  | support2@blackcatcard.com |  |  |
| SIC-ID-000000004178 | Supremerulez |  |  | supremerulez@hotmail.it |  |  |
| SIC-ID-000000004179 | Surfcamp |  |  | surfcamp@marineddabay.com |  |  |
| SIC-ID-000000004180 | Systeme. | support |  | support@systeme.io |  |  |
| SIC-ID-000000004181 | Tabaccheria | Roma88 |  | tabaccheria.roma88@gmail.com |  |  |
| SIC-ID-000000004182 | Taccolahome |  |  | taccolahome@gmail.com |  |  |
| SIC-ID-000000004183 | Tacu_v_nicoleta | Veronica |  | tacu_v_nicoleta@libero.it |  |  |
| SIC-ID-000000004184 | Taglieriadiva | Diva |  | taglieriadiva@libero.it |  |  |
| SIC-ID-000000004185 | Taisozaji | Albrigoni |  | taisozaji@libero.it |  |  |
| SIC-ID-000000004186 | Talibehballa |  |  | talibehballa@gmail.com |  |  |
| SIC-ID-000000004187 | Tamaracioin |  |  | tamaracioin@gmail.com |  |  |
| SIC-ID-000000004188 | Taniabertaggia |  |  | taniabertaggia@libero.it |  |  |
| SIC-ID-000000004189 | Te | Grilli |  | te.grilli@gmail.com |  |  |
| SIC-ID-000000004190 | Teatro Studios |  | Teatro Studios | info@teatrodellevoci.com |  |  |
| SIC-ID-000000004191 | Tecaflo |  |  | tecaflo@gmail.com |  |  |
| SIC-ID-000000004192 | Tecknosound |  |  | tecknosound@hotmail.com |  |  |
| SIC-ID-000000004193 | Tecnico |  |  | tecnico@3technology.it |  |  |
| SIC-ID-000000004194 | Tecnico - Freguglia S. l |  | Tecnico - Freguglia S. l | sanelli@fregugliasrl.it |  |  |
| SIC-ID-000000004195 | Teconf |  |  | teconf@tiscali.it |  |  |
| SIC-ID-000000004196 | Telasystem |  |  | telasystem@libero.it |  |  |
| SIC-ID-000000004197 | Tempiodelsole2 |  |  | tempiodelsole2@email.it |  |  |
| SIC-ID-000000004198 | Tequilasas Sas |  | TEQUILA S.a.s. di Cecchini Pietro & C. | tequilasas@yahoo.it | Chioggia | VE |
| SIC-ID-000000004199 | Termoidraulicamc Mc |  | Termoidraulicamc Mc | termoidraulicamc@tiscali.it |  |  |
| SIC-ID-000000004200 | Tigriferraro |  |  | tigriferraro@gmail.com |  |  |
| SIC-ID-000000004201 |  |  | TIOZZO GUGLIELMO Impresa individuale | guglielmo.tiozzo@pec.it | Chioggia | VE |
| SIC-ID-000000004202 | Tiozzomartina |  |  | tiozzomartina@libero.it |  |  |
| SIC-ID-000000004203 | Tito | Pavan |  | tito.pavan@gmail.com |  |  |
| SIC-ID-000000004204 | Tizi | Raso |  | tizi.raso@gmail.com |  |  |
| SIC-ID-000000004205 | Tizianobissacco |  |  | tizianobissacco@gmail.com |  |  |
| SIC-ID-000000004206 | Tizianohair |  |  | tizianohair@hotmail.it |  |  |
| SIC-ID-000000004207 | Toad1 | Ottobon |  | toad1@live.it |  |  |
| SIC-ID-000000004208 | Today |  |  | today@thebalance.com |  |  |
| SIC-ID-000000004209 | Tolomei | Al |  | tolomei.al@gmail.com |  |  |
| SIC-ID-000000004210 | Tolveguido |  |  | tolveguido@yahoo.it |  |  |
| SIC-ID-000000004211 | Tomaificiotarga |  |  | tomaificiotarga@alice.it |  |  |
| SIC-ID-000000004212 | Tommaso |  |  | tommaso@tuscanypeople.com |  |  |
| SIC-ID-000000004213 | Tommy |  |  | tommy.73stocco@gmail.com |  |  |
| SIC-ID-000000004214 | Tomtmawolo |  |  | tomtmawolo@gmail.com |  |  |
| SIC-ID-000000004215 | Tonia | 79 |  | tonia.79@libero.it |  |  |
| SIC-ID-000000004216 | Tonicastiello |  |  | tonicastiello@gmail.com |  |  |
| SIC-ID-000000004217 | Tonyhubbardth |  |  | tonyhubbard4th@gmail.com |  |  |
| SIC-ID-000000004218 | Topolina-69 |  |  | topolina-69@hotmail.it |  |  |
| SIC-ID-000000004219 | Torresingiuliano |  |  | torresingiuliano@libero.it |  |  |
| SIC-ID-000000004220 | Torresinmatteo1998 |  |  | torresinmatteo1998@gmail.com |  |  |
| SIC-ID-000000004221 | Tortorafortuna74 |  |  | tortorafortuna74@gmail.com |  |  |
| SIC-ID-000000004222 | Tosinimaria | A |  | tosinimaria.a@libero.it |  |  |
| SIC-ID-000000004223 | Touchwindows | Touchwindows |  | services@touchwindow.it |  |  |
| SIC-ID-000000004224 | Touchwindows | Touchwindows |  | info@touchwindow.it |  |  |
| SIC-ID-000000004225 | Touchwindows | Touchwindows |  | amministrazione@touchwindow.it |  |  |
| SIC-ID-000000004226 | Towerhotelbologna Bo |  | Towerhotelbologna Bo | towerhotelbologna.bo@bestwestern.it |  |  |
| SIC-ID-000000004227 | Transdeltasrl S.r.l. |  | Transdelta S.r.l. | transdeltasrl@alice.it | Porto Viro | RO |
| SIC-ID-000000004228 | Travaglia_impianti |  | Travaglia_impianti | travaglia_impianti@libero.it |  |  |
| SIC-ID-000000004229 | Tronconegvincenzo |  |  | tronconeg4vincenzo3@gmail.com |  |  |
| SIC-ID-000000004230 | Tronelli |  |  | tronelli@gmail.com |  |  |
| SIC-ID-000000004231 | Tstoni |  |  | tstoni@tiscali.it |  |  |
| SIC-ID-000000004232 | Tsvetanka | Gerova |  | tsvetanka.gerova@yahoo.com |  |  |
| SIC-ID-000000004233 | Tuber | Sabinum |  | tuber.sabinum@gmail.com |  |  |
| SIC-ID-000000004234 | Ufficio | Amministrativo |  | ufficio.amministrativo@tecnolimap.com |  |  |
| SIC-ID-000000004235 | UFFICIO snc |  | UFFICIO snc | info@ufficioservice.com |  |  |
| SIC-ID-000000004236 | Ufficiotecnico |  |  | ufficiotecnico@lclavoriincorso.it |  |  |
| SIC-ID-000000004237 | Ufficiotecnicogare |  |  | ufficiotecnicogare@pistorello.it |  |  |
| SIC-ID-000000004238 | Ulissecroda |  |  | ulissecroda@gmail.com |  |  |
| SIC-ID-000000004239 | Umberto | Asquini |  | umberto.asquini@live.it |  |  |
| SIC-ID-000000004240 | Universal |  |  | universal@francofiumana.it |  |  |
| SIC-ID-000000004241 | Updeucce |  |  | 223upde72ucce@hpeprint.com |  |  |
| SIC-ID-000000004242 | Utente |  |  | cs2@thehyperfund.com |  |  |
| SIC-ID-000000004243 | Utente |  |  | fy2672@gmail.com |  |  |
| SIC-ID-000000004244 | Utente |  |  | bm1977@alice.it |  |  |
| SIC-ID-000000004245 | Uvescci |  |  | uve4019scci@posteitaliane.it |  |  |
| SIC-ID-000000004246 | Valentina |  |  | valentina.sasso@biscaropoggio.it |  |  |
| SIC-ID-000000004247 | Valentina |  |  | info@fattoriedeldelta.it |  |  |
| SIC-ID-000000004248 | Valentinaze |  |  | valentinaze@gmail.com |  |  |
| SIC-ID-000000004249 | Valentino |  |  | valentino.sieve16@gmail.com |  |  |
| SIC-ID-000000004250 | Valentinosieve |  |  | valentinosieve@libero.it |  |  |
| SIC-ID-000000004251 | Valeria |  |  | valeria@marefq.it |  |  |
| SIC-ID-000000004252 | Valeria |  |  | valeria@banzatoarredamenti.it |  |  |
| SIC-ID-000000004253 | Valeria | Spoladori |  | valeria.spoladori@studioagm.eu |  |  |
| SIC-ID-000000004254 | Valerio | Marzolla |  | valerio.marzolla@k-adriatica.it |  |  |
| SIC-ID-000000004255 | Vallyeffe |  |  | vallyeffe@gmail.com |  |  |
| SIC-ID-000000004256 | Vania | Fabris |  | vania.fabris@libero.it |  |  |
| SIC-ID-000000004257 | Vanita06 |  |  | vanita06@libero.it |  |  |
| SIC-ID-000000004258 | Vanna | Napolitano |  | vanna.napolitano@libero.it |  |  |
| SIC-ID-000000004259 | Varimarboxa |  |  | varimarbox4a@gmail.com |  |  |
| SIC-ID-000000004260 | Ve | Rossidiana |  | ve.rossidiana@enel.com |  |  |
| SIC-ID-000000004261 | Veneta | Rulli |  | info@venetarulli.it |  |  |
| SIC-ID-000000004262 | Venturewhale2021 |  |  | venturewhale2021@yahoo.com |  |  |
| SIC-ID-000000004263 | Vernafiorella |  |  | vernafiorella@live.it |  |  |
| SIC-ID-000000004264 | Vetro | Gmail |  | vetroservicesrl@gmail.com |  |  |
| SIC-ID-000000004265 | Vfrancese |  |  | vfrancese@gmail.com |  |  |
| SIC-ID-000000004266 |  |  | VIALE PAOLO | viale.paolo@pec.it | Codevigo | PD |
| SIC-ID-000000004267 | Vicentinigeomluca |  |  | vicentinigeomluca@gmail.com |  |  |
| SIC-ID-000000004268 | Vicepresidente |  |  | vicepresidente@artigianatopadovano.it |  |  |
| SIC-ID-000000004269 | Viennalucas |  |  | viennalucas@protonmail.com |  |  |
| SIC-ID-000000004270 | Villaggiodelcuore |  |  | villaggiodelcuore@gmail.com |  |  |
| SIC-ID-000000004271 | Vincenzamonti73 |  |  | vincenzamonti73@gmail.com |  |  |
| SIC-ID-000000004272 | Vincenzo | Valenza |  | vincenzo.valenza@hotmail.it |  |  |
| SIC-ID-000000004273 | Virginiaapostoli |  |  | virginiaapostoli@gmail.com |  |  |
| SIC-ID-000000004274 | Virginiacipolla |  |  | virginiacipolla@tiscali.it |  |  |
| SIC-ID-000000004275 | Vittoria |  |  | vittoria.currao@abacospa.it |  |  |
| SIC-ID-000000004276 | Vittorio | Londoni |  | vittorio.londoni@londonigroup.com |  |  |
| SIC-ID-000000004277 | Vkcscuolakite |  |  | vkcscuolakite@libero.it |  |  |
| SIC-ID-000000004278 | Vrn_grl | Grl |  | vrn_grl@yahoo.it |  |  |
| SIC-ID-000000004279 | Vsaccucci |  |  | vsaccucci@libero.it |  |  |
| SIC-ID-000000004280 | Wainer | Bruni |  | wainer.bruni@alice.it |  |  |
| SIC-ID-000000004281 | Waterlandstudio |  | Waterlandstudio | waterlandstudio@gmail.com |  |  |
| SIC-ID-000000004282 | Way | 2 usd4 |  | way.2.usd4@gmail.com |  |  |
| SIC-ID-000000004283 | Webinar |  |  | webinar@aifesformazione.it |  |  |
| SIC-ID-000000004284 | White-c |  |  | white-c@libero.it |  |  |
| SIC-ID-000000004285 | Xaver8 |  |  | xaver8@libero.it |  |  |
| SIC-ID-000000004286 | Xblumi |  |  | xblumi@hotmail.it |  |  |
| SIC-ID-000000004287 | Y | Rossi |  | y.rossi@fornovogas.it |  |  |
| SIC-ID-000000004288 | Yahyamnasri8 | Mnasri |  | yahyamnasri8@gmail.com |  |  |
| SIC-ID-000000004289 | Yascira | Lucy |  | yascira.lucy@gmail.com |  |  |
| SIC-ID-000000004290 | Your22242 |  |  | your22242@gmail.com |  |  |
| SIC-ID-000000004291 | Yselvaggio84 |  |  | yselvaggio84@gmail.com |  |  |
| SIC-ID-000000004292 | Yuslenia | Barban |  | yuslenia.barban@libero.it |  |  |
| SIC-ID-000000004293 | Yvonne | 70sejas ysa61 |  | yvonne.70sejas.ysa61@gmail.com |  |  |
| SIC-ID-000000004294 | Zagostafforg |  |  | zagostafforg@libero.it |  |  |
| SIC-ID-000000004295 | Zaia Impianti |  | Zaia Impianti | info@zaiaimpianti.it |  |  |
| SIC-ID-000000004296 | Zaiaemanuele |  |  | zaiaemanuele@libero.it |  |  |
| SIC-ID-000000004297 | Zamboni | C78 |  | zamboni.c78@gmail.com |  |  |
| SIC-ID-000000004298 | Zamperlin | Lendinara |  | zamperlin.lendinara@libero.it |  |  |
| SIC-ID-000000004299 | Zampino Alberto |  |  | zampino.alberto@gmail.com |  |  |
| SIC-ID-000000004300 | Zanella76 |  |  | zanella76@gmail.com |  |  |
| SIC-ID-000000004301 | Zaniboni | Valeria |  | zaniboni.valeria@gmail.com |  |  |
| SIC-ID-000000004302 | Zanzibar & slot srl |  | Zanzibar & Slot Srl | silvia.trombin@virgilio.it | Taglio di Po | RO |
| SIC-ID-000000004303 | Zendesk-box |  |  | zendesk-box@sendinblue.com |  |  |
| SIC-ID-000000004304 | Zeta | 2000 |  | info@zeta2000.it |  |  |
| SIC-ID-000000004305 | Zeta 2000 2000 |  |  | amministrazione@zeta2000.it |  |  |
| SIC-ID-000000004306 |  |  | ZETA 2000 S.r.l. | zeta2000s.r.l@legalmail.it | Carlino | UD |
| SIC-ID-000000004307 | Zhanetalacaj |  |  | zhanetalacaj@gmail.com |  |  |
| SIC-ID-000000004308 | Zhenweizhu |  | ZHU ZHENWEI | zhenweizhu@yahoo.it | PORTO VIRO | RO |
| SIC-ID-000000004309 | Zimmaro | Luca |  | zimmaro.luca@gmail.com |  |  |
| SIC-ID-000000004310 | Zorzan | Elena |  | zorzan.elena@libero.it |  |  |
| SIC-ID-000000004311 | Zumella |  |  | zumella@libero.it |  |  |
| SIC-ID-000000004312 | Zvi | Benyamini |  | zvi@wholebraintrading.com |  |  |

---
Regola FASE ZERO81+ n.4: nessuna email inviata senza double opt-in.
Il DB MySQL di produzione resta la fonte di verita; questo file e generato dal mirror SQLite.
