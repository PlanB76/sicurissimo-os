-- ============================================================
-- MYSQL1_INSTALL_81PLUS_HUB1.sql
-- Schema base HUB1 81plus.net · Wave 1
-- DB: u173050672_81plusglobal · PHP 8.1+ · MySQL 8+
-- ============================================================

SET NAMES utf8mb4;
SET foreign_key_checks = 0;

-- UTENTI
CREATE TABLE IF NOT EXISTS users (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sic_id        VARCHAR(20) NOT NULL UNIQUE COMMENT 'SIC-ID univoco generato al signup',
  email         VARCHAR(255) NOT NULL UNIQUE,
  username      VARCHAR(80) UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  nome          VARCHAR(100),
  cognome       VARCHAR(100),
  telefono      VARCHAR(30),
  telegram_user VARCHAR(80),
  whatsapp      VARCHAR(30),
  ruolo         ENUM('MEMBER81','NETWORKER81','ELITE81','ADMIN81') NOT NULL DEFAULT 'MEMBER81',
  status        ENUM('ACTIVE','SUSPENDED','INACTIVE','BLOCKED') NOT NULL DEFAULT 'ACTIVE',
  genesys_status ENUM('NONE','GENESYS_MEMBER','GENESYS_NETWORKER','GENESYS_LEADER','GENESYS_FOUNDER') NOT NULL DEFAULT 'NONE',
  genesys_promo_active TINYINT(1) NOT NULL DEFAULT 0,
  genesys_promo_expires DATE NULL,
  career_level  TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'L0-L8',
  equilibrium_status ENUM('STARTER','ACTIVE','FOCUS','MASTER','ELITE','SPECIAL') NOT NULL DEFAULT 'STARTER',
  wallet_id     BIGINT UNSIGNED NULL,
  referral_ref  VARCHAR(20) NULL COMMENT 'SIC-ID di chi ha invitato',
  kyc_status    ENUM('NONE','PENDING','VERIFIED','REJECTED') NOT NULL DEFAULT 'NONE',
  wallet_address VARCHAR(100) NULL COMMENT 'BSC/BEP20 wallet opzionale',
  email_verified_at DATETIME NULL,
  last_login    DATETIME NULL,
  note_interne  TEXT NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_email (email),
  INDEX idx_sic_id (sic_id),
  INDEX idx_ruolo (ruolo),
  INDEX idx_status (status),
  INDEX idx_genesys (genesys_status),
  INDEX idx_referral (referral_ref)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- WALLET 5 STRATI
CREATE TABLE IF NOT EXISTS wallets (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NOT NULL UNIQUE,
  pv_balance    DECIMAL(18,4) NOT NULL DEFAULT 0 COMMENT 'PV credito interno 1:1',
  pvplus_balance DECIMAL(18,4) NOT NULL DEFAULT 0 COMMENT 'PV+ reward gamificato',
  saf_balance   DECIMAL(18,8) NOT NULL DEFAULT 0 COMMENT 'SAF utility Web3',
  x81_balance   DECIMAL(18,8) NOT NULL DEFAULT 0 COMMENT '81X token BEP-20',
  usdt_balance  DECIMAL(18,6) NOT NULL DEFAULT 0 COMMENT 'USDT custodito',
  pvplus_career_total DECIMAL(18,4) NOT NULL DEFAULT 0 COMMENT 'PV+ totali lifetime per career level',
  pv_booster_rate DECIMAL(5,2) NOT NULL DEFAULT 1.00 COMMENT 'moltiplicatore PV+ attivo',
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- TRANSAZIONI PV
CREATE TABLE IF NOT EXISTS pv_transactions (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NOT NULL,
  tipo          ENUM('ACQUISTO','CONSUMO','BONUS','RIMBORSO','CORREZIONE_ADMIN','REFERRAL','COMMISSIONE') NOT NULL,
  importo       DECIMAL(18,4) NOT NULL,
  saldo_dopo    DECIMAL(18,4) NOT NULL,
  valuta        ENUM('PV','PV+','SAF','81X','USDT') NOT NULL DEFAULT 'PV',
  riferimento   VARCHAR(100) NULL COMMENT 'order_id, missione_id, ecc.',
  nota          VARCHAR(255) NULL,
  admin_id      BIGINT UNSIGNED NULL COMMENT 'se azione admin',
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_tipo (user_id, tipo),
  INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- MEMBERSHIP
CREATE TABLE IF NOT EXISTS memberships (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NOT NULL,
  piano         ENUM('BASIC+','PRO+','ELITE+') NOT NULL,
  status        ENUM('ACTIVE','EXPIRED','CANCELLED','PENDING') NOT NULL DEFAULT 'PENDING',
  pv_mensile    DECIMAL(18,4) NOT NULL COMMENT '29.90/59.90/89.90',
  pvplus_prima  DECIMAL(18,4) NOT NULL COMMENT '100/250/500 prima attivazione',
  pvplus_rinnovo DECIMAL(18,4) NOT NULL COMMENT '30/60/90 rinnovo',
  prima_attivazione TINYINT(1) NOT NULL DEFAULT 0,
  attiva_dal    DATE NOT NULL,
  scade_il      DATE NULL,
  rinnovo_auto  TINYINT(1) NOT NULL DEFAULT 1,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_status (user_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ORDINI PAYGATE81+
CREATE TABLE IF NOT EXISTS paygate_orders (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NOT NULL,
  tipo          ENUM('PV_PACK','MEMBERSHIP','PIX81','PLP_PACK','SPECIAL_PACK','ALTRO') NOT NULL,
  importo_euro  DECIMAL(18,2) NOT NULL,
  pv_erogati    DECIMAL(18,4) NOT NULL DEFAULT 0,
  pvplus_erogati DECIMAL(18,4) NOT NULL DEFAULT 0,
  metodo_pagamento ENUM('PAYPAL','STRIPE','BONIFICO','CRYPTO','INTERNO') NOT NULL,
  status        ENUM('PENDING','COMPLETED','FAILED','REFUNDED','CANCELLED') NOT NULL DEFAULT 'PENDING',
  riferimento_ext VARCHAR(255) NULL COMMENT 'ID transazione PayPal/Stripe',
  note          TEXT NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_status (user_id, status),
  INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- AUDIT SESSIONI
CREATE TABLE IF NOT EXISTS audit_sessions (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NULL COMMENT 'NULL se anonimo',
  session_token VARCHAR(100) NOT NULL UNIQUE,
  settore       VARCHAR(100) NULL,
  dipendenti    TINYINT UNSIGNED NULL,
  haccp         TINYINT(1) NULL,
  edilizia      TINYINT(1) NULL,
  rischi        JSON NULL COMMENT 'array rischi identificati',
  score_sicurezza TINYINT UNSIGNED NULL,
  score_haccp   TINYINT UNSIGNED NULL,
  score_privacy TINYINT UNSIGNED NULL,
  priorita      JSON NULL,
  azioni        JSON NULL,
  completato    TINYINT(1) NOT NULL DEFAULT 0,
  email_inviata TINYINT(1) NOT NULL DEFAULT 0,
  preventivo_id BIGINT UNSIGNED NULL,
  ip_address    VARCHAR(45) NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_user (user_id),
  INDEX idx_token (session_token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PREVENTIVI
CREATE TABLE IF NOT EXISTS preventivi (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NULL,
  audit_session_id BIGINT UNSIGNED NULL,
  ragione_sociale VARCHAR(200) NULL,
  settore       VARCHAR(100) NULL,
  dipendenti    TINYINT UNSIGNED NULL,
  servizi_selezionati JSON NULL,
  totale_pv     DECIMAL(18,4) NULL,
  totale_euro   DECIMAL(18,2) NULL,
  pdf_path      VARCHAR(255) NULL,
  status        ENUM('BOZZA','INVIATO','ACCETTATO','RIFIUTATO','SCADUTO') NOT NULL DEFAULT 'BOZZA',
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- RECENSIONI
CREATE TABLE IF NOT EXISTS recensioni (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome          VARCHAR(100) NOT NULL,
  iniziale_cognome CHAR(1) NOT NULL,
  azienda       VARCHAR(200) NULL,
  comune        VARCHAR(100) NULL,
  provincia     CHAR(2) NULL,
  regione       VARCHAR(50) NULL,
  settore       VARCHAR(100) NULL,
  ateco         VARCHAR(20) NULL,
  rischio       ENUM('BASSO','MEDIO','ALTO') NULL,
  stelle        TINYINT UNSIGNED NOT NULL CHECK (stelle >= 4 AND stelle <= 5),
  testo         TEXT NOT NULL,
  servizio      VARCHAR(100) NULL,
  data_recensione DATE NOT NULL,
  fonte         ENUM('TRUSTPILOT','DIRETTA','SICURISSIMO81') NOT NULL DEFAULT 'SICURISSIMO81',
  verificata    TINYINT(1) NOT NULL DEFAULT 1,
  attiva        TINYINT(1) NOT NULL DEFAULT 1,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_ateco (ateco),
  INDEX idx_regione (regione),
  INDEX idx_rischio (rischio),
  INDEX idx_stelle (stelle)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- GENESYS CANDIDATURE
CREATE TABLE IF NOT EXISTS genesys_applications (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NULL,
  sic_id        VARCHAR(20) NULL,
  nome          VARCHAR(100) NOT NULL,
  cognome       VARCHAR(100) NOT NULL,
  email         VARCHAR(255) NOT NULL,
  settore       VARCHAR(100) NULL,
  community_size INT UNSIGNED NULL,
  piattaforma   VARCHAR(50) NULL,
  motivazione   TEXT NULL,
  vuole_pix     TINYINT(1) NOT NULL DEFAULT 0,
  come_ha_conosciuto VARCHAR(200) NULL,
  status        ENUM('PENDING','APPROVATA','RIFIUTATA','IN_VALUTAZIONE') NOT NULL DEFAULT 'PENDING',
  note_admin    TEXT NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_email (email),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- REFERRAL
CREATE TABLE IF NOT EXISTS referral_links (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NOT NULL UNIQUE,
  sic_id_ref    VARCHAR(20) NOT NULL UNIQUE,
  click_count   INT UNSIGNED NOT NULL DEFAULT 0,
  signup_count  INT UNSIGNED NOT NULL DEFAULT 0,
  conversion_count INT UNSIGNED NOT NULL DEFAULT 0,
  pvplus_earned DECIMAL(18,4) NOT NULL DEFAULT 0,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS referral_conversions (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  referrer_id   BIGINT UNSIGNED NOT NULL,
  referred_id   BIGINT UNSIGNED NOT NULL,
  tipo          ENUM('SIGNUP','BASIC+','PRO+','ELITE+','PIX','PLP','ALTRO') NOT NULL,
  pvplus_assegnati DECIMAL(18,4) NOT NULL DEFAULT 0,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (referrer_id) REFERENCES users(id),
  FOREIGN KEY (referred_id) REFERENCES users(id),
  INDEX idx_referrer (referrer_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PIX81+
CREATE TABLE IF NOT EXISTS pix81_slots (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  numero        SMALLINT UNSIGNED NOT NULL UNIQUE COMMENT '1-1000',
  tipo          ENUM('FOUNDER','PUBBLICO','SPONSOR','COMMUNITY','RISERVATO') NOT NULL DEFAULT 'PUBBLICO',
  user_id       BIGINT UNSIGNED NULL,
  prezzo_pv     DECIMAL(18,4) NOT NULL DEFAULT 750,
  pvplus_bonus  DECIMAL(18,4) NOT NULL DEFAULT 1000,
  special_pack  TINYINT(1) NOT NULL DEFAULT 0,
  status        ENUM('LIBERO','RISERVATO','VENDUTO','SCADUTO') NOT NULL DEFAULT 'LIBERO',
  assegnato_il  DATETIME NULL,
  scade_riserva DATETIME NULL,
  note          VARCHAR(255) NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_status (status),
  INDEX idx_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserisci 200 slot FOUNDER e 800 PUBBLICO
INSERT IGNORE INTO pix81_slots (numero, tipo) 
SELECT n, CASE WHEN n <= 200 THEN 'FOUNDER' ELSE 'PUBBLICO' END
FROM (
  SELECT a.N + b.N * 10 + c.N * 100 + 1 AS n
  FROM (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
        UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
       (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
        UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) b,
       (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
        UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) c
  HAVING n BETWEEN 1 AND 1000
) nums;

-- GREEN81+
CREATE TABLE IF NOT EXISTS green81_alberi (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NOT NULL,
  tipo_origine  ENUM('PV_ACQUISTO','PIX81','MISSIONE','ADMIN') NOT NULL,
  riferimento   VARCHAR(100) NULL,
  specie        VARCHAR(100) NULL DEFAULT 'Quercia',
  localita      VARCHAR(200) NULL,
  piantato_il   DATE NULL,
  certificato_url VARCHAR(255) NULL,
  dao_approvato TINYINT(1) NOT NULL DEFAULT 0,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ADMIN ACTION LOG
CREATE TABLE IF NOT EXISTS admin_action_log (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  admin_id      BIGINT UNSIGNED NOT NULL,
  target_user_id BIGINT UNSIGNED NULL,
  azione        VARCHAR(100) NOT NULL,
  parametri     JSON NULL,
  note          TEXT NULL,
  ip_address    VARCHAR(45) NULL,
  user_agent    VARCHAR(255) NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_admin (admin_id),
  INDEX idx_target (target_user_id),
  INDEX idx_azione (azione),
  INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- COOKIE CONSENT
CREATE TABLE IF NOT EXISTS cookie_consents (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NULL,
  session_id    VARCHAR(100) NULL,
  ip_address    VARCHAR(45) NULL,
  analytics     TINYINT(1) NOT NULL DEFAULT 0,
  marketing     TINYINT(1) NOT NULL DEFAULT 0,
  tecnici       TINYINT(1) NOT NULL DEFAULT 1,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_user (user_id),
  INDEX idx_session (session_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET foreign_key_checks = 1;
