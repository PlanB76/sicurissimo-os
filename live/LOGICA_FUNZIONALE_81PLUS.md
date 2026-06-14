# LOGICA FUNZIONALE GLOBALE ECOSISTEMA 81+
## Versione 1.0, 13 giugno 2026
## Documento riservato alla direzione

---

## ARCHITETTURA A TRE HUB

L'ecosistema 81+ opera su tre hub distinti con separazione netta.

HUB1 (81plus.net) è il punto di ingresso universale, genera il SIC-ID, gestisce identità e SSO, ospita audit e dashboard. Non vende nulla, converte visitatori in utenti registrati.

HUB2 (sicurissimo.online e 81plus.it) è l'economia reale Web2. Qui si vendono servizi, corsi, membership, franchising. Tutto genera PV. I PV sono crediti fedeltà interni, 1 PV = 1 euro di sconto, non prelevabili, non convertibili in denaro.

HUB3 (81plus.online) è la compliance digitale Web3, con sede operativa a Dubai. Qui vivono token SAF (interno), token 81X (BSC BEP-20 on-chain), NFT, metaverso, DAO. L'unico ponte è: PV si scambiano 1:1 con SAF, SAF si converte in 81X on-chain.

Regola ferrea: commercio fisico e servizi tradizionali solo in HUB2, blockchain e token solo in HUB3. I PV esistono in entrambi i hub come ponte.

---

## IDENTITÀ UNIVERSALE

Ogni utente dell'ecosistema ha un codice SIC-XXXXXXX generato alla registrazione su qualsiasi punto di ingresso (HUB1, HUB2 o HUB3). Il SIC-ID è l'unica chiave di accesso a tutto. SSO cross-domain con token HMAC firmato, validità 120 secondi.

Alla registrazione l'utente riceve: SIC-ID, 100 PV di benvenuto, 4 wallet (PV crediti, Tesoretto interno, SAF Web3, Crypto USDT BEP-20), accesso alla dashboard, email di benvenuto automatica (5 email in sequenza via Brevo).

---

## SISTEMA MONETARIO

PV (Punti Valore): crediti fedeltà interni, 1 PV = 1 euro come sconto. Non prelevabili, non convertibili in denaro. Bonus 100 alla registrazione. Si accumulano con ogni acquisto, ogni attività, ogni referral. Leciti senza licenza perché sono sconti fedeltà, non moneta elettronica.

SAF (token interno): utility token Web3, solo uso interno all'ecosistema. Swap PV verso SAF al tasso 1:1, irreversibile. Il SAF serve per accedere ai servizi Web3 (staking di utilità, governance DAO, accesso a contenuti premium HUB3). Non ha valore di scambio esterno.

81X (token BSC BEP-20): il token dell'ecosistema con uso interno ed esterno, ma solo on-chain. Supply totale 21 milioni, di cui 5 milioni genesi per airdrop e offerte private a 0,10 USDT. Burn 0,5% per trasferimento, halving ogni 4 anni. Solo DEX, nessun CEX. L'81X si compra e vende solo sui pool on-chain (PancakeSwap o pool proprietario 81plus.exchange).

Flusso monetario: euro (PayPal) entra nell'ecosistema, compra servizi HUB2 che generano PV, i PV si convertono in SAF, il SAF si converte in 81X on-chain. Il percorso è a senso unico per il tratto PV/SAF: chi converte PV in SAF non torna indietro. Chi ha 81X può scambiarlo su DEX con USDT.

---

## HUB1: 81plus.net (IDENTITÀ E INGRESSO)

### Funzione
Punto di primo contatto. Ogni visitatore entra qui, fa l'audit gratuito delle 30 normative, riceve il risultato e si registra. Il SIC-ID nasce qui.

### Flusso utente
1. Il visitatore atterra dalla SEO, dalle ads, da un referral o dal passaparola.
2. Vede la home con il termometro normativo, le recensioni vere (294 recensioni, media 4,81 stelle, 35 settori), la proposta di valore.
3. Compila l'audit gratuito (nome, email, 30 domande binarie). Riceve il report con le non conformità.
4. Si registra, riceve SIC-ID e 100 PV.
5. Viene portato alla dashboard Genesi.
6. Da qui il SSO lo porta a HUB2 (servizi) o HUB3 (Web3) in base al suo interesse.

### Pagine chiave
index.html (home con termometro), audit.html (form audit), signup.html (registrazione), login.html, dashboard-utente.html (dashboard Genesi), chi-siamo.html, recensioni.html (294 vere).

### Chat AI
Mirco AI, il chatbot della direzione. Risponde su sicurezza sul lavoro, HACCP, privacy e sull'ecosistema. Proxy server (api/chat.php), nessuna chiave esposta nel client.

### Metriche
Visitatori, audit completati, registrazioni, tasso di conversione audit-to-registrazione.

---

## HUB2: sicurissimo.online e 81plus.it (ECONOMIA REALE)

### 81plus.it: MEMBER81+ (5 livelli PRO)

Funzione: il cuore dei servizi di compliance. L'utente registrato diventa MEMBER81+ e accede ai servizi per mettere in regola la sua azienda.

Ranking PRO (5 livelli): i livelli si sbloccano con i PV accumulati da acquisti di servizi, corsi e membership.

Livello 1, Starter: registrazione gratuita, 100 PV, accesso all'audit, alla guida, al libro digitale.
Livello 2, Operativo: membership Basic (49 euro al mese), accesso ai documenti base, supporto email.
Livello 3, Avanzato: membership Pro (99 euro al mese), accesso ai corsi PRO, ai webinar, ai documenti avanzati.
Livello 4, Esperto: membership Elite (149 euro al mese), accesso completo a tutti i servizi, supporto prioritario, webinar riservati.
Livello 5, Master: Pack Business o Enterprise, accesso a tutto più consulenza dedicata della direzione.

Prodotti membership: Basic 49, Pro 99, Elite 149 euro al mese.
Pack una tantum: Start 990, Business 1.900, Enterprise (custom).
Sigilli: Bronze 990, Silver 1.490, Gold 1.990, Platinum (custom).

Servizi inclusi: documenti di sicurezza (DVR, POS, DUVRI), valutazione dei rischi, pratiche HACCP, privacy e GDPR, formazione obbligatoria (via Academy), consulenza normativa.

Tutto genera PV. Ogni euro speso in membership o servizi genera 1 PV.

### 81plus.network: NETWORKER81+ (8 livelli VIP)

Funzione: la rete commerciale dell'ecosistema. Chi vuole guadagnare vendendo i servizi 81+ entra qui.

Ingresso: SDK 199 euro (una tantum) + SDP ricorrente (da 49 a 999 euro al mese, 8 tiers).

SDP tiers: IGNITE 49, RISE 99, DRIVE 149, SCALE 249, PEAK 399, SUMMIT 599, LEGACY 799, CROWN 999.

Ranking VIP (8 livelli): i livelli si sbloccano con il volume di vendite personali e di rete. Commissioni solo da vendite reali di servizi e prodotti (L.173/2005, mai da reclutamento).

Piano compensi: commissioni dirette sulle vendite, bonus di volume, bonus di leadership, piano equilibrio (incentivi per bilanciare vita e lavoro). Il piano compensi deve essere validato dal legale prima di incassare i primi pagamenti.

Piano carriera: ogni livello VIP sblocca benefici crescenti (percentuali più alte, accesso a eventi, formazione avanzata, strumenti di vendita dedicati).

Ogni vendita genera PV sia per il venditore che per il cliente. La rete genera PV su più livelli secondo il piano compensi.

### 81plus.academy: FORMAZIONE

Funzione: la piattaforma di formazione dell'ecosistema, su tre livelli.

Corsi PRO (per MEMBER81+): sicurezza sul lavoro (D.Lgs 81/08), HACCP (Reg. CE 852/2004), privacy (GDPR). Erogati in white-label da ANFOS (commissione 20-50%, pagamento 60 giorni fine mese). Attestati validi per legge, rilasciati con certificato ANFOS originale dentro cartellina brandizzata 81+.

Corsi VIP (per NETWORKER81+): crescita personale, finanza personale, leadership, comunicazione, vendita. Erogati in white-label da Lezione-online (commissione 15%, pagamento 60 giorni fine mese). Certificati MIM.

Corsi ELITE (per CLUB81+): formazione riservata ai membri Elite. Masterclass esclusive, speaker internazionali, workshop dal vivo. Erogati direttamente dalla direzione o da formatori selezionati.

Ogni corso completato genera PV. I corsi obbligatori per legge (sicurezza, HACCP) generano anche il certificato ufficiale.

### 81plus.club: CLUB81+ (5 livelli ELITE)

Funzione: il club riservato per i membri di alto livello. Experience, eventi e lifestyle.

Ranking ELITE (5 livelli): PALLADIUM 1.990 euro al mese, IRIDIUM 4.990 euro al mese, RHODIUM 9.990 euro al mese, più 2 livelli superiori (custom, solo su invito).

Cosa include: eventi esclusivi dal vivo, viaggi organizzati, accesso a location premium (ville, yacht per eventi aziendali), formazione Elite con speaker selezionati, networking con imprenditori di alto livello, concierge dedicato.

Attenzione: ogni esperienza promessa deve avere un fornitore reale con contratto firmato. Non si promettono ville e yacht senza averli a contratto. Nella prima fase, il club parte con eventi dal vivo in location selezionate e formazione esclusiva. Le esperienze luxury si aggiungono quando hai almeno 20 membri PALLADIUM attivi.

### 81plus.shop: MERCHANDISING

Funzione: il negozio dell'ecosistema. Senza magazzino.

Categorie: wear81+ Member (abbigliamento brandizzato per i membri), wear81+ Networker (linea dedicata ai networker), wear81+ Elite (linea premium), Gadget (accessori e oggetti brandizzati), Workwear (DPI e abbigliamento da lavoro con marchio 81+).

Fornitori: BigBuy per DPI e prodotti sicurezza (pacco neutro o brandizzato, nessun minimo d'ordine, stock EU). Printful, Printify o Gelato per il POD (print on demand) su abbigliamento e gadget. Amazon solo come affiliato trasparente (mai white-label, FTC disclosure).

Ogni acquisto genera PV.

### 81plus.zone: FRANCHISING (8 livelli SUPER-PRO)

Funzione: il franchising fisico sul territorio. I Point81+ (SICURISSIMO) sono punti fisici dove le aziende locali trovano un consulente di sicurezza sul lavoro, HACCP e privacy.

Ranking SUPER-PRO (8 livelli): i primi 3 sono nel listino attuale.
Light: 4.900 euro ingresso + 149 euro al mese.
Standard: 9.900 euro ingresso + 390 euro al mese.
Flagship: 19.900 euro ingresso + 590 euro al mese.
I livelli 4-8 si attivano solo quando hai almeno 10 affiliati nel primo livello.

Obblighi legali (L.129/2004): DIP (Documento Informazione Precontrattuale) depositato 30 giorni prima della firma, bilanci degli ultimi 3 anni, descrizione della rete, territorio di competenza, durata minima del contratto. Il DIP deve essere redatto dal legale prima di firmare il primo contratto di affiliazione.

Ogni affiliato genera PV continui dalla sua attività. Il ranking SUPER-PRO avanza col volume della rete di affiliati.

---

## HUB3: 81plus.online (COMPLIANCE DIGITALE, SEDE DUBAI)

Tutte le attività Web3 hanno sede operativa a Dubai con le licenze già acquisite. Gli utenti EU accedono ai servizi Web3 tramite il proprio SIC-ID e wallet non-custodial.

### 81plus.digital: DAPP MEMBERSHIP

Funzione: la porta di ingresso al mondo Web3 dell'ecosistema. L'utente con SIC-ID attiva la sua membership Web3 qui.

Servizi: accesso alla DAPP con dashboard Web3, swap PV verso SAF (1:1, irreversibile), blocco di utilità SAF (il SAF viene bloccato per un periodo e sblocca funzionalità premium), network ranking Web3 (8 livelli, separato dal ranking HUB2).

Il blocco di utilità NON è staking nel senso finanziario. Non promette rendimenti. Il SAF bloccato dà accesso a funzionalità (voto DAO, contenuti premium, priorità sugli airdrop), non interessi.

Vocabolario obbligatorio: mai dire "staking", "rendimento", "APY", "investimento", "profitto" nel copy pubblico. Dire "blocco di utilità", "attivazione", "accesso funzionale".

### 81plus.exchange: DEX

Funzione: il DEX (decentralized exchange) dell'ecosistema. Solo non-custodial, solo on-chain.

Servizi: swap (SAF, 81X, USDT e top 20, con routing sui pool e slippage dichiarato), pool di liquidità (contratti verificati e auditati, trasparenza totale), vault (depositi a lungo termine con meccanica di utilità, non rendimento), trading (order book o AMM, solo token dell'ecosistema e stablecoin).

Nessun CEX. L'utente mantiene sempre il controllo delle sue chiavi. MetaMask bind sul wallet crypto del SIC-ID. Nessuna custodia dei fondi da parte dell'ecosistema.

Contratti smart: da deployare su BSC mainnet via Hardhat e Remix IDE. Audit indipendente obbligatorio prima del lancio pubblico.

### 81plus.world: METAVERSO

Funzione: il metaverso 3D dell'ecosistema. Spazi virtuali, eventi, land NFT.

Collegamento PIX81: 1 PIX81 = 1 NFT LAND nel metaverso. Chi compra un pixel sul globo riceve un NFT ERC-721 su BSC che rappresenta quella posizione nel mondo virtuale. Il valore non è speculativo, è posizionale e pubblicitario.

Contenuti: mondi 3D (costruiti con Three.js o Unity WebGL), eventi virtuali, showroom, esposizioni. L'utente cammina nel metaverso col suo avatar Ready Player Me e vede i brand dei proprietari dei PIX sulle land.

Token: 81X per acquisti in-world, NFT per land e oggetti, RWA (Real World Asset) per la tokenizzazione di beni reali.

### 81plus.org: DAO

Funzione: la governance decentralizzata dell'ecosistema. I possessori di SAF bloccato votano sulle proposte.

Struttura: proposte della direzione o della community, votazione on-chain con peso proporzionale al SAF bloccato, esecuzione automatica delle proposte approvate (quando i contratti lo permettono).

Prima fase: la DAO è consultiva, non esecutiva. La direzione mantiene il potere decisionale finale. La DAO diventa esecutiva quando il framework legale (MiCA e Dubai) lo consente.

### 81plus.place: SEDE VIRTUALE

Funzione: i luoghi fisici e virtuali della rete. Sede virtuale con videoconferenze, NFT collection per arredare gli spazi.

Servizi: sede virtuale (stanza Jitsi brandizzata 81+ per meeting), collezione NFT per arredamenti e strumenti virtuali (tavoli, sedie, schermi, lavagne), PIX81 reward (chi ha un PIX riceve un NFT reward in place).

Prima fase: la videoconferenza brandizzata funziona subito (Jitsi self-hosted, open source). Gli arredamenti NFT partono quando 81plus.world ha il motore 3D attivo.

### 81plus.store: MARKETPLACE WEB3

Funzione: il marketplace dove si comprano e vendono NFT, RWA, land del metaverso, contenuti digitali.

Token: 81X come valuta del marketplace. Le transazioni avvengono on-chain, il marketplace è un'interfaccia che interagisce con i contratti smart.

Categorie: NFT (arte, collezioni, badge, certificati), RWA (tokenizzazione di beni reali, quote di proprietà), Land (le posizioni del metaverso 81plus.world), Contenuti (corsi premium, documenti, template).

### 81plus.credit: WEB3 BANKING (DUBAI)

Funzione: servizio bancario Web3 con sede a Dubai, licenze già ottenute, opera a livello globale.

Servizi: wallet custody e non-custody, swap token 81X verso USDT e viceversa, servizi finanziari Web3 (mutui, finanziamenti, linee di credito in crypto), on-ramp e off-ramp (conversione euro/crypto e viceversa).

Sede e licenze: Dubai, licenze VARA (Virtual Assets Regulatory Authority) o DFSA (Dubai Financial Services Authority) già acquisite come dichiarato dalla direzione.

NOTA CRITICA per utenti EU: anche con licenza Dubai, i residenti EU che accedono a servizi finanziari potrebbero far scattare obblighi regolamentari locali (MiFID II, PSD2). Verifica con il legale se serve il passporting MiCA o un'esenzione specifica per i servizi offerti tramite credit.

### 81plus.bond: ASSICURAZIONI E POLIZZE (DUBAI)

Funzione: marketplace assicurativo e obbligazionario Web3.

Servizi: polizze assicurative tokenizzate, bond di garanzia su servizi 81+, assicurazioni peer-to-peer su blockchain, swap bond 81X verso USDT.

Sede e licenze: Dubai, come 81plus.credit.

NOTA CRITICA: la distribuzione assicurativa in Italia richiede iscrizione IVASS o accordo con intermediario iscritto. I bond tokenizzati potrebbero essere classificati come strumenti finanziari sotto MiFID II. Validazione legale obbligatoria prima del lancio verso clienti EU.

### 81plus.space: PONTE WEB2-WEB3

Funzione: la pubblicità permanente nell'ecosistema e il ponte tra i due mondi.

PIX81: il globo dei 1000 pixel è il cuore di space. Ogni PIX è una posizione pubblicitaria permanente. Il proprietario ci mette logo, descrizione, indirizzo, email, link al sito. Il PIX è visibile da ogni pagina dell'ecosistema che embedda il globo.

Ponte PV: space è dove i PV del mondo Web2 incontrano il mondo Web3. Chi ha un PIX entra nella whitelist 81X. Chi accumula PV dai servizi li converte in SAF e poi in 81X.

Prezzo: 1000 euro a PIX, con promo settimanale automatica (800 euro, massimo 20 slot per settimana). Sconto PV fino a 200.

### 81plus.cloud: ARCHIVIAZIONE IPFS

Funzione: spazio dedicato per l'archiviazione sicura e immutabile dei documenti dell'ecosistema.

Servizi: archiviazione documenti di compliance su IPFS (attestati, certificati, DVR, documenti privacy), pinning via Pinata o nft.storage, CID (Content Identifier) univoco per ogni documento, prova di esistenza e integrità su blockchain.

Prima fase: i documenti restano su Hostinger (già funzionante). Il layer IPFS si attiva quando i contratti NFT sono deployati e i documenti devono essere immutabili (ad esempio, gli attestati di formazione come NFT verificabili).

### 81plus.cards: CARD DIGITALI

Funzione: il biglietto da visita digitale dell'ecosistema.

Servizi: card digitale con SIC-ID, QR code che porta al profilo pubblico, badge dei livelli ranking raggiunti (PRO, VIP, ELITE, SUPER-PRO), design brandizzato 81+.

Generazione: automatica alla registrazione. Si aggiorna in tempo reale quando l'utente sale di livello. Condivisibile via link, salvabile nel wallet Apple o Google.

Implementazione: SVG dinamico servito da api/cards.php con i dati del profilo e i badge. Export in PNG per i social.

### 81plus.christmas: XMAS81

Funzione: il brand natalizio dell'ecosistema, dove è Natale tutto l'anno.

Servizi: PIX in promozione speciale (edizione limitata XMAS), card augurali brandizzate (da inviare con SIC-ID del destinatario), donazione albero Treedom in nome del destinatario (collegato a Green81+), regali digitali (NFT regalo, PV regalo, PIX regalo), campagne stagionali ma il brand XMAS81 vive tutto l'anno come sezione "regali e celebrazioni".

Implementazione: landing page dedicata, flag christmas nel sistema promo, integrazione con Treedom per gli alberi regalo.

---

## SISTEMA RANKING UNIFICATO

L'ecosistema ha 5 ranking separati, ognuno con i suoi livelli. Un utente può avanzare in più ranking contemporaneamente. I PV accumulati contano per tutti i ranking in cui l'utente è attivo.

MEMBER PRO (HUB2, 81plus.it): 5 livelli. Avanza coi PV da acquisti di servizi e corsi. Sblocca servizi di compliance.

NETWORKER VIP (HUB2, 81plus.network): 8 livelli. Avanza col volume di vendite personali e di rete. Sblocca commissioni e bonus crescenti.

CLUB ELITE (HUB2, 81plus.club): 5 livelli. Avanza con la membership pagata e la partecipazione agli eventi. Sblocca esperienze esclusive.

ZONE SUPER-PRO (HUB2, 81plus.zone): 8 livelli. Avanza col volume della rete di affiliati. Sblocca territori e benefici crescenti.

DIGITAL WEB3 (HUB3, 81plus.digital): 8 livelli. Avanza col SAF bloccato e l'attività on-chain. Sblocca funzionalità Web3 premium.

Database: tabella ranking_config con campi (tipo, livello, nome, soglia_pv, soglia_volume, permessi, colore, icona). I ranking si leggono dalla config, non sono hardcoded.

---

## FLUSSO COMPLETO UTENTE

Fase 1, Scoperta: il visitatore trova 81plus.net via SEO o referral, fa l'audit gratuito, si registra, riceve SIC-ID e 100 PV.

Fase 2, Attivazione: l'utente compra la prima membership o il primo corso su 81plus.it, accumula PV, sale nel ranking PRO.

Fase 3, Espansione: l'utente scopre il network (81plus.network), diventa NETWORKER, inizia a vendere e guadagnare commissioni.

Fase 4, Ponte Web3: l'utente converte i PV in SAF su 81plus.digital, esplora il mondo Web3, compra un PIX su 81plus.space.

Fase 5, Ecosistema completo: l'utente opera su entrambi i hub, partecipa alla DAO (81plus.org), compra e vende sul marketplace (81plus.store), frequenta il metaverso (81plus.world).

Fase 6, Elite: l'utente entra nel Club (81plus.club) o apre un Point81+ in franchising (81plus.zone).

---

## CRITICITÀ IDENTIFICATE E SOLUZIONI

1. LICENZE DUBAI E UTENTI EU. Credit e bond operano da Dubai con licenze locali. Per gli utenti EU serve una verifica legale su MiCA passporting e compatibilità MiFID II. Soluzione: consulenza legale specializzata prima del lancio verso residenti EU. Fino ad allora, credit e bond servono solo utenti extra-EU.

2. PIANO COMPENSI NETWORK. Il piano compensi deve rispettare la L.173/2005 (commissioni solo da vendite reali, mai da reclutamento). Soluzione: validazione legale del piano compensi prima di incassare i primi pagamenti dai networker.

3. FRANCHISING DIP. La L.129/2004 richiede il DIP depositato 30 giorni prima della firma. Soluzione: il legale prepara il DIP con bilanci, descrizione rete e contratto tipo prima di firmare il primo affiliato.

4. VOCABOLARIO TOKEN. SAF e 81X sono token di utilità, non strumenti finanziari. Il copy pubblico non deve mai dire staking, rendimento, APY, investimento, profitto. Soluzione: revisione di ogni pagina pubblica con vocabolario approvato.

5. SMART CONTRACT AUDIT. I contratti SAF e 81X devono essere auditati da un revisore indipendente prima del deploy su mainnet. Soluzione: scrittura contratti, deploy su testnet, audit, poi mainnet.

6. CHIAVI E CREDENZIALI. La chiave Groq è esposta nel nicolas-widget.html sul Drive. Altre credenziali (PayPal Plan IDs, BSC deposit wallets, Hostinger API) devono essere ruotate. Soluzione: revocare tutte le chiavi esposte e rigenerarle prima del go-live.

7. PROMETTRE ESPERIENZE SENZA FORNITORI. Il Club promette ville, yacht, viaggi luxury. Soluzione: firmare contratti con almeno 3 fornitori di esperienze prima di promettere qualcosa nel copy. Nella prima fase, il club offre eventi dal vivo e formazione.

8. ARCHIVIAZIONE IPFS. Cloud promette IPFS ma non è ancora implementato. Soluzione: prima fase con archiviazione Hostinger (già funzionante), IPFS attivo dopo il deploy dei contratti NFT.

---

## COSA MANCA E PROSSIMI PASSI

1. Contratti smart SAF e 81X da scrivere, testare e auditare.
2. Tabella ranking_config nel DB per gestire i 5 ranking da plancia.
3. Landing XMAS81 e sistema regali digitali.
4. API cards.php per generazione card digitali con badge.
5. DIP franchising dal legale.
6. Piano compensi network validato dal legale.
7. Almeno 3 contratti fornitori esperienze per il Club.
8. Verifica legale MiCA passporting per credit e bond verso utenti EU.
9. Integrazione Jitsi per place (videoconferenze brandizzate).
10. Deploy testnet BSC, audit, poi mainnet.

---

## CONCLUSIONE

L'architettura funziona. I tre hub sono separati e coerenti. Il flusso PV verso SAF verso 81X è pulito e percorribile. I 20 sub-progetti coprono ogni aspetto dell'ecosistema senza sovrapposizioni. Le criticità sono tutte risolvibili, nessuna è bloccante per il lancio del Wave 1 (HUB1 e HUB2 base). Le criticità diventano bloccanti solo quando si attivano credit, bond e il token 81X su mainnet, e per quelle serve la validazione legale specifica.
