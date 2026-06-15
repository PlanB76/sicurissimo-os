-- MYSQL_DELTA_DASHBOARD_ACADEMY_DOC_SCADENZIARIO.sql

SET NAMES utf8mb4;

-- ACADEMY ACCESS
CREATE TABLE IF NOT EXISTS academy_access (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NOT NULL,
  livello       ENUM('CORE','PRO_SKILLS','ELITE_MASTERY') NOT NULL DEFAULT 'CORE',
  sbloccato_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  motivo        VARCHAR(255) NULL,
  UNIQUE KEY uk_user_livello (user_id, livello),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CORSI ACADEMY (solo ID interni, nomi partner non visibili lato utente)
CREATE TABLE IF NOT EXISTS academy_corsi (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  livello       ENUM('CORE','PRO_SKILLS','ELITE_MASTERY') NOT NULL,
  titolo        VARCHAR(200) NOT NULL,
  descrizione   TEXT NULL,
  partner_interno VARCHAR(100) NULL COMMENT 'nome partner, mai mostrato lato utente',
  link_partner  VARCHAR(255) NULL COMMENT 'URL piattaforma partner, redirect server-side',
  pvplus_completamento DECIMAL(18,4) NOT NULL DEFAULT 0,
  durata_ore    DECIMAL(5,2) NULL,
  attivo        TINYINT(1) NOT NULL DEFAULT 1,
  ordine        SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS academy_completamenti (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NOT NULL,
  corso_id      BIGINT UNSIGNED NOT NULL,
  completato_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  pvplus_erogati DECIMAL(18,4) NOT NULL DEFAULT 0,
  UNIQUE KEY uk_user_corso (user_id, corso_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- DOC81+ DOCUMENTI GENERATI
CREATE TABLE IF NOT EXISTS doc81_documents (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NOT NULL,
  tipo          VARCHAR(100) NOT NULL COMMENT 'DVR, DUVRI, POS, HACCP, Privacy, ecc.',
  titolo        VARCHAR(255) NOT NULL,
  dati_input    JSON NULL COMMENT 'dati azienda e specifici del documento',
  pdf_path      VARCHAR(255) NULL,
  status        ENUM('BOZZA','GENERATO','SCARICATO') NOT NULL DEFAULT 'BOZZA',
  disclaimer_accettato TINYINT(1) NOT NULL DEFAULT 0,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_tipo (user_id, tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SCADENZIARIO
CREATE TABLE IF NOT EXISTS scadenziario (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NOT NULL,
  categoria     VARCHAR(100) NOT NULL,
  nome          VARCHAR(255) NOT NULL,
  data_scadenza DATE NOT NULL,
  note          TEXT NULL,
  completato    TINYINT(1) NOT NULL DEFAULT 0,
  completato_at DATETIME NULL,
  notifica_90g  TINYINT(1) NOT NULL DEFAULT 0,
  notifica_30g  TINYINT(1) NOT NULL DEFAULT 0,
  notifica_7g   TINYINT(1) NOT NULL DEFAULT 0,
  notifica_0g   TINYINT(1) NOT NULL DEFAULT 0,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_data (user_id, data_scadenza),
  INDEX idx_completato (completato)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
