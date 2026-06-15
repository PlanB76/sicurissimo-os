-- ============================================================
-- MYSQL_DELTA_BLOCCO2_HUB1_CORE.sql
-- Delta BLOCCO 2 · Estensioni schema HUB1 core live
-- Eseguire DOPO MYSQL1_INSTALL_81PLUS_HUB1.sql
-- ============================================================

SET NAMES utf8mb4;
SET foreign_key_checks = 0;

-- ─── ESTENSIONI TABELLA users ─────────────────────────────────────────────────
ALTER TABLE users
  ADD COLUMN IF NOT EXISTS referral_public_url VARCHAR(255) GENERATED ALWAYS AS (
    CONCAT('https://81plus.net/signup.php?ref=', sic_id)
  ) VIRTUAL COMMENT 'URL referral pubblico derivato da SIC-ID',
  ADD COLUMN IF NOT EXISTS membership_tier ENUM('NONE','BASIC+','PRO+','ELITE+') NOT NULL DEFAULT 'NONE' AFTER genesys_status,
  ADD COLUMN IF NOT EXISTS is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER status,
  ADD COLUMN IF NOT EXISTS suspension_reason VARCHAR(255) NULL AFTER is_active,
  ADD COLUMN IF NOT EXISTS club_status TINYINT(1) NOT NULL DEFAULT 0 AFTER membership_tier,
  ADD COLUMN IF NOT EXISTS franchise_status TINYINT(1) NOT NULL DEFAULT 0 AFTER club_status,
  ADD COLUMN IF NOT EXISTS networker_status TINYINT(1) NOT NULL DEFAULT 0 AFTER franchise_status,
  ADD COLUMN IF NOT EXISTS settore VARCHAR(50) NULL AFTER cognome,
  ADD INDEX IF NOT EXISTS idx_membership_tier (membership_tier);

-- ─── PROFILI ESTESI ───────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS user_profiles (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NOT NULL UNIQUE,
  ragione_sociale VARCHAR(150) NULL,
  piva          VARCHAR(20) NULL,
  indirizzo     VARCHAR(255) NULL,
  comune        VARCHAR(100) NULL,
  provincia     CHAR(2) NULL,
  regione       VARCHAR(50) NULL,
  cap           CHAR(5) NULL,
  settore_ateco VARCHAR(20) NULL,
  num_dipendenti TINYINT UNSIGNED NULL,
  sito_web      VARCHAR(255) NULL,
  bio           TEXT NULL,
  avatar_url    VARCHAR(255) NULL,
  genesys_promo_accepted_at DATETIME NULL,
  profile_completed_at DATETIME NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── CATALOGO PIANI MEMBERSHIP ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS membership_plans (
  id            TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codice        VARCHAR(10) NOT NULL UNIQUE,
  nome          VARCHAR(30) NOT NULL,
  pv_mensile    DECIMAL(8,2) NOT NULL,
  pvplus_prima  DECIMAL(8,2) NOT NULL COMMENT 'PV+ prima attivazione',
  pvplus_rinnovo DECIMAL(8,2) NOT NULL COMMENT 'PV+ ogni rinnovo mensile',
  is_active     TINYINT(1) NOT NULL DEFAULT 1,
  sort_order    TINYINT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO membership_plans (codice, nome, pv_mensile, pvplus_prima, pvplus_rinnovo, sort_order) VALUES
  ('BASIC+',  'Basic Plus',  29.90, 100.00,  30.00, 1),
  ('PRO+',    'Pro Plus',    59.90, 250.00,  60.00, 2),
  ('ELITE+',  'Elite Plus',  89.90, 500.00,  90.00, 3);

-- ─── EVENTI REFERRAL ─────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS referral_events (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  referrer_user_id BIGINT UNSIGNED NOT NULL COMMENT 'chi ha invitato',
  referred_user_id BIGINT UNSIGNED NULL COMMENT 'chi si e registrato (NULL se solo click)',
  tipo          ENUM('CLICK','SIGNUP','PROFILE_COMPLETE','MEMBERSHIP_BASIC','MEMBERSHIP_PRO','MEMBERSHIP_ELITE') NOT NULL,
  ip_hash       VARCHAR(64) NULL COMMENT 'hash IP anonimizzato',
  user_agent_hash VARCHAR(64) NULL,
  pvplus_awarded DECIMAL(8,2) NOT NULL DEFAULT 0,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (referrer_user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (referred_user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_referrer (referrer_user_id),
  INDEX idx_tipo (tipo),
  INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── MODULI DASHBOARD PER RUOLO ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS dashboard_modules (
  id            SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codice        VARCHAR(40) NOT NULL UNIQUE,
  nome          VARCHAR(80) NOT NULL,
  descrizione   TEXT NULL,
  icona         VARCHAR(10) NULL,
  url_target    VARCHAR(255) NULL,
  ruoli         JSON NOT NULL COMMENT '["MEMBER81","NETWORKER81",...]',
  genesys_req   VARCHAR(30) NULL COMMENT 'NULL = nessun requisito',
  membership_req VARCHAR(10) NULL COMMENT 'NULL = libero, BASIC+ = solo con membership',
  sort_order    SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  is_preview    TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 = preview con lock',
  is_active     TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO dashboard_modules
  (codice, nome, icona, url_target, ruoli, membership_req, sort_order, is_preview) VALUES
  ('sic_id',        'SIC-ID',            '🆔', NULL,                           '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,    1,  0),
  ('referral',      'ReferralLink81+',   '🔗', '/dashboard.php#referral',      '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,    2,  0),
  ('wallet',        'Wallet81+',         '💼', '/dashboard.php#wallet',        '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,    3,  0),
  ('membership',    'Membership',        '🏆', '/membership.php',              '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,    4,  0),
  ('paygate',       'PayGate81+',        '💳', '/paygate81.php',               '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,    5,  0),
  ('audit',         'Audit 81/08',       '🔍', '/audit.php',                   '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,    6,  0),
  ('preventivo',    'DOC81+ Builder',    '📄', '/preventivo.php',              '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', 'BASIC+', 7,  1),
  ('academy',       'Academy 81+',       '🎓', '/academy81.php',               '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', 'BASIC+', 8,  1),
  ('scadenziario',  'Scadenziario81+',   '📅', '/scadenze.php',                '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', 'BASIC+', 9,  1),
  ('pvplus_booster','PV+ Booster81+',    '⚡', '/gamification.php',            '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,   10,  0),
  ('pix81',         'PIX81+',            '🗺️', '/pix81.php',                   '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,   11,  1),
  ('green81',       'Green81+',          '🌳', '/green81.php',                 '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,   12,  1),
  ('scout81',       'SCOUT81+',          '🎯', '/scout81.php',                 '["NETWORKER81","ELITE81","ADMIN81"]',            'PRO+', 20,  1),
  ('plp81',         'PLP81+ Pack',       '📦', '/network81.php#plp',           '["NETWORKER81","ELITE81","ADMIN81"]',            'PRO+', 21,  1),
  ('network81',     'NETWORK81+',        '🕸️', '/network81.php',               '["NETWORKER81","ELITE81","ADMIN81"]',            'PRO+', 22,  1),
  ('pipeline3d',    'Pipeline3D81+',     '📊', '/ecosistema3d.php',            '["NETWORKER81","ELITE81","ADMIN81"]',            'PRO+', 23,  1),
  ('compensi',      'Piano Compensi81+', '💹', '/cervello3d.php',              '["NETWORKER81","ELITE81","ADMIN81"]',            'PRO+', 24,  1),
  ('club81',        'Club81+',           '👑', '/club81.php',                  '["ELITE81","ADMIN81"]',                         'ELITE+',30, 1),
  ('franchising',   'Franchising81+',    '🏢', '/franchising.php',             '["ELITE81","ADMIN81"]',                         'ELITE+',31, 1),
  ('territory',     'TerritoryMap81+',   '🗺️', '/scout81.php#territory',       '["ELITE81","ADMIN81"]',                         'ELITE+',32, 1),
  ('admin_center',  'Admin Command',     '⚙️', '/admin.php',                   '["ADMIN81"]',                                    NULL,  40,  0);

-- ─── WALLET TRANSACTIONS (alias esteso di pv_transactions) ───────────────────
CREATE OR REPLACE VIEW wallet_transactions AS
  SELECT id, user_id, tipo, importo, saldo_dopo, valuta,
         riferimento, nota, admin_id, created_at
  FROM   pv_transactions;

-- ─── AGGIORNAMENTO genesys_applications ─────────────────────────────────────
ALTER TABLE genesys_applications
  ADD COLUMN IF NOT EXISTS promo_pvplus_sent TINYINT(1) NOT NULL DEFAULT 0
    COMMENT '1 se 1000 PV+ promo inviati',
  ADD COLUMN IF NOT EXISTS profile_pvplus_sent TINYINT(1) NOT NULL DEFAULT 0
    COMMENT '1 se 1000 PV+ profilo completo inviati';

SET foreign_key_checks = 1;
