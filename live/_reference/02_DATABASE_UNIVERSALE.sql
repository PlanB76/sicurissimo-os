-- ============================================================
-- DATABASE UNIVERSALE SICURISSIMO — Schema MySQL
-- Il primo database globale. Tutti i pianeti scrivono qui.
-- Da importare su Hostinger via phpMyAdmin.
-- ============================================================

-- Tutti gli utenti dell'ecosistema. Un solo account per tutto.
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150),
  email VARCHAR(190) UNIQUE NOT NULL,
  telefono VARCHAR(30),
  tipo ENUM('azienda','lavoratore','developer','admin') DEFAULT 'azienda',
  pianeta_ingresso VARCHAR(60),
  password_hash VARCHAR(255),
  creato_il DATETIME DEFAULT CURRENT_TIMESTAMP,
  stato ENUM('attivo','trial','sospeso','disattivo') DEFAULT 'trial',
  INDEX(email), INDEX(tipo)
);

-- Aziende con dati di conformità
CREATE TABLE companies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  ragione_sociale VARCHAR(200),
  piva VARCHAR(20),
  settore VARCHAR(60),
  rischio ENUM('basso','medio','alto'),
  dipendenti INT DEFAULT 0,
  citta VARCHAR(100),
  FOREIGN KEY(user_id) REFERENCES users(id)
);

-- Membership attive
CREATE TABLE subscriptions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  piano ENUM('basic','pro','elite'),
  prezzo_mese DECIMAL(8,2),
  stato ENUM('attiva','sospesa','annullata') DEFAULT 'attiva',
  inizio DATE, rinnovo DATE,
  FOREIGN KEY(user_id) REFERENCES users(id)
);

-- Documenti (DVR, POS, attestati) collegati al Cloud
CREATE TABLE documents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT,
  tipo VARCHAR(60),
  titolo VARCHAR(200),
  file_url VARCHAR(300),
  scadenza DATE,
  stato ENUM('valido','in_scadenza','scaduto','bozza') DEFAULT 'bozza',
  validato_da VARCHAR(150),
  creato_il DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(company_id) REFERENCES companies(id),
  INDEX(scadenza), INDEX(stato)
);

-- Scadenze monitorate dal Monitor
CREATE TABLE deadlines (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT,
  descrizione VARCHAR(200),
  data_scadenza DATE,
  alert_inviato ENUM('no','90','60','30') DEFAULT 'no',
  FOREIGN KEY(company_id) REFERENCES companies(id),
  INDEX(data_scadenza)
);

-- Lead di tutti i pianeti con punteggio
CREATE TABLE leads (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150), email VARCHAR(190), telefono VARCHAR(30),
  pianeta VARCHAR(60),
  fonte VARCHAR(80),
  punteggio INT DEFAULT 0,
  stato ENUM('freddo','tiepido','caldo','chiuso','perso') DEFAULT 'freddo',
  creato_il DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX(stato), INDEX(pianeta)
);

-- Tutte le conversazioni chat (Graziano e chat pianeti)
CREATE TABLE chats (
  id INT AUTO_INCREMENT PRIMARY KEY,
  session_id VARCHAR(80),
  pianeta VARCHAR(60) DEFAULT 'hub',
  ruolo ENUM('user','bot','lead','system'),
  testo TEXT,
  lead_nome VARCHAR(150), lead_email VARCHAR(190), lead_telefono VARCHAR(30),
  creato_il DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX(session_id), INDEX(pianeta)
);

-- Vendite e transazioni
CREATE TABLE sales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  prodotto VARCHAR(150),
  pianeta VARCHAR(60),
  importo DECIMAL(10,2),
  agente VARCHAR(80),
  data_vendita DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(user_id) REFERENCES users(id),
  INDEX(data_vendita)
);

-- Rete developer, ranghi, compensi
CREATE TABLE network (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  sponsor_id INT,
  rango VARCHAR(40),
  aziende_seguite INT DEFAULT 0,
  compenso_mese DECIMAL(10,2) DEFAULT 0,
  FOREIGN KEY(user_id) REFERENCES users(id)
);

-- Asset, Sigilli, certificati, NFT di valore
CREATE TABLE assets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  tipo VARCHAR(60),
  nome VARCHAR(150),
  qr_code VARCHAR(300),
  nft_hash VARCHAR(200),
  stato VARCHAR(40),
  creato_il DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(user_id) REFERENCES users(id)
);

-- Log di cosa fa ogni Agente AI (tracciamento)
CREATE TABLE agents_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  agente VARCHAR(80),
  pianeta VARCHAR(60),
  evento VARCHAR(120),
  esito ENUM('ok','errore','passato_umano') DEFAULT 'ok',
  dettaglio TEXT,
  creato_il DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX(agente), INDEX(creato_il)
);

-- Tutti gli eventi che accendono gli agenti
CREATE TABLE events (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tipo ENUM('persona','tempo','sistema'),
  nome_evento VARCHAR(120),
  agente_responsabile VARCHAR(80),
  payload TEXT,
  gestito ENUM('no','si') DEFAULT 'no',
  creato_il DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX(gestito), INDEX(tipo)
);

-- Prodotti e servizi del catalogo
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150),
  pianeta VARCHAR(60),
  prezzo DECIMAL(10,2),
  ricorrente ENUM('si','no') DEFAULT 'no',
  attivo ENUM('si','no') DEFAULT 'si'
);

-- Professionisti che validano (RSPP, medici, formatori)
CREATE TABLE professionals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150),
  ruolo VARCHAR(60),
  zona VARCHAR(80),
  validazioni_mese INT DEFAULT 0,
  stato ENUM('attivo','carico_alto','non_disponibile') DEFAULT 'attivo'
);
