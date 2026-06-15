-- MYSQL_DELTA_SCOUT81_PROSPECTS.sql

SET NAMES utf8mb4;

-- DATABASE PROSPECT SCOUT81+
CREATE TABLE IF NOT EXISTS scout81_prospects (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ragione_sociale VARCHAR(255) NOT NULL,
  ateco         VARCHAR(20) NULL,
  macrosettore  VARCHAR(100) NULL,
  rischio       ENUM('BASSO','MEDIO','ALTO') NOT NULL DEFAULT 'MEDIO',
  haccp_applicabile TINYINT(1) NOT NULL DEFAULT 0,
  edilizia      TINYINT(1) NOT NULL DEFAULT 0,
  dipendenti_stimati SMALLINT UNSIGNED NULL,
  indirizzo     VARCHAR(255) NULL,
  comune        VARCHAR(100) NULL,
  provincia     CHAR(2) NULL,
  regione       VARCHAR(50) NULL,
  cap           CHAR(5) NULL,
  telefono      VARCHAR(30) NULL,
  email_azienda VARCHAR(255) NULL,
  sito_web      VARCHAR(255) NULL,
  lead_storico  TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'cliente SICURISSIMO81+ passato',
  score         TINYINT UNSIGNED NOT NULL DEFAULT 50,
  priorita      ENUM('ALTA','MEDIA','BASSA') NOT NULL DEFAULT 'MEDIA',
  provider_origine VARCHAR(50) NULL COMMENT 'outscraper, interno, ecc.',
  data_importazione DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ultimo_aggiornamento DATETIME NULL,
  INDEX idx_ateco (ateco),
  INDEX idx_provincia (provincia),
  INDEX idx_regione (regione),
  INDEX idx_rischio (rischio),
  INDEX idx_score (score),
  INDEX idx_lead_storico (lead_storico)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ASSEGNAZIONI PROSPECT A NETWORKER
CREATE TABLE IF NOT EXISTS scout81_assignments (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  prospect_id   BIGINT UNSIGNED NOT NULL,
  networker_id  BIGINT UNSIGNED NOT NULL,
  plp_order_id  BIGINT UNSIGNED NULL,
  status        ENUM('NUOVO','IN_LAVORAZIONE','FOLLOW_UP','AUDIT_FATTO','PREVENTIVO',
                     'CONVERTITO','PERSO','DA_RIATTIVARE') NOT NULL DEFAULT 'NUOVO',
  note          TEXT NULL,
  script_usato  VARCHAR(100) NULL,
  prossimo_followup DATE NULL,
  assegnato_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (prospect_id) REFERENCES scout81_prospects(id),
  FOREIGN KEY (networker_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_networker_status (networker_id, status),
  INDEX idx_prospect (prospect_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- FILTRI SALVATI SCOUT81+
CREATE TABLE IF NOT EXISTS scout81_saved_filters (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NOT NULL,
  nome          VARCHAR(100) NOT NULL,
  filtri        JSON NOT NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PLP81+ PACK DISPONIBILI
CREATE TABLE IF NOT EXISTS plp_packs_catalog (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codice        VARCHAR(50) NOT NULL UNIQUE,
  nome          VARCHAR(100) NOT NULL,
  descrizione   TEXT NULL,
  prospect_count SMALLINT UNSIGNED NOT NULL,
  prezzo_pv     DECIMAL(18,4) NOT NULL,
  pvplus_bonus  DECIMAL(18,4) NOT NULL DEFAULT 0,
  composizione  JSON NULL COMMENT 'percentuali settori',
  filtri_preset JSON NULL,
  attivo        TINYINT(1) NOT NULL DEFAULT 1,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Catalogo pack base
INSERT IGNORE INTO plp_packs_catalog (codice, nome, prospect_count, prezzo_pv, pvplus_bonus) VALUES
('PLP_START','PLP Start',100,29,100),
('PLP_PRO','PLP Pro',250,49,250),
('PLP_MAX','PLP Max',500,99,500),
('PLP_EDILIZIA','PLP Edilizia',100,39,100),
('PLP_HACCP','PLP HACCP',100,39,100),
('PLP_PROFESSIONISTI','PLP Professionisti',100,35,100),
('PLP_ARTIGIANI','PLP Artigiani',100,35,100),
('PLP_TERRITORIO','PLP Territorio',200,59,200),
('PLP_GENESYS_SPECIAL','PLP GENESYS Special',250,0,500),
('PLP_FOLLOWUP_STORICI','PLP Follow-up Lead Storici',200,49,200);

-- ORDINI PLP
CREATE TABLE IF NOT EXISTS plp_orders (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NOT NULL,
  pack_id       BIGINT UNSIGNED NOT NULL,
  pv_scalati    DECIMAL(18,4) NOT NULL,
  pvplus_erogati DECIMAL(18,4) NOT NULL DEFAULT 0,
  prospect_count SMALLINT UNSIGNED NOT NULL,
  status        ENUM('PENDING','ASSEGNATO','COMPLETATO','FALLITO') NOT NULL DEFAULT 'PENDING',
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (pack_id) REFERENCES plp_packs_catalog(id),
  INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PROVIDER API SCOUT81+
CREATE TABLE IF NOT EXISTS scout81_providers (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome          VARCHAR(100) NOT NULL,
  codice        VARCHAR(50) NOT NULL UNIQUE,
  attivo        TINYINT(1) NOT NULL DEFAULT 0,
  endpoint_url  VARCHAR(255) NULL COMMENT 'configurato in admin, key in .env',
  quota_mensile INT UNSIGNED NULL,
  quota_usata   INT UNSIGNED NOT NULL DEFAULT 0,
  note          TEXT NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO scout81_providers (nome, codice, attivo) VALUES
('Outscraper','outscraper',0),
('Database Interno SICURISSIMO81+','interno',1),
('Provider Futuro 1','futuro1',0);
