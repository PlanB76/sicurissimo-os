-- MYSQL_DELTA_PVPLUS_BOOSTER.sql

SET NAMES utf8mb4;

-- MISSIONI PV+
CREATE TABLE IF NOT EXISTS pvplus_missions (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codice        VARCHAR(100) NOT NULL UNIQUE,
  nome          VARCHAR(200) NOT NULL,
  descrizione   TEXT NULL,
  pvplus_base   DECIMAL(18,4) NOT NULL,
  tipo          ENUM('ONETIME','RICORRENTE_GIORNALIERO','RICORRENTE_SETTIMANALE',
                     'RICORRENTE_MENSILE','PROGRESSIVA') NOT NULL DEFAULT 'ONETIME',
  ruolo_minimo  ENUM('MEMBER81','NETWORKER81','ELITE81','ADMIN81') NOT NULL DEFAULT 'MEMBER81',
  genesys_richiesto TINYINT(1) NOT NULL DEFAULT 0,
  categoria     VARCHAR(50) NULL COMMENT 'SCOUT81,PLP81,ACADEMY,AUDIT,REFERRAL,ONBOARDING,EQUILIBRIO',
  attiva        TINYINT(1) NOT NULL DEFAULT 1,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Missioni base
INSERT IGNORE INTO pvplus_missions (codice, nome, pvplus_base, tipo, categoria) VALUES
('WELCOME','Benvenuto 81+',100,'ONETIME','ONBOARDING'),
('PROFILE_COMPLETE','Profilo completato',200,'ONETIME','ONBOARDING'),
('KYC_COMPLETE','KYC verificato',1000,'ONETIME','ONBOARDING'),
('GENESYS_SIGNUP','Registrazione GENESYS promo',1000,'ONETIME','ONBOARDING'),
('GENESYS_PROFILE','Profilo completo promo GENESYS',1000,'ONETIME','ONBOARDING'),
('FIRST_AUDIT','Primo audit completato',300,'ONETIME','AUDIT'),
('FIRST_BASIC','Prima attivazione BASIC+',100,'ONETIME','ONBOARDING'),
('FIRST_PRO','Prima attivazione PRO+',250,'ONETIME','ONBOARDING'),
('FIRST_ELITE','Prima attivazione ELITE+',500,'ONETIME','ONBOARDING'),
('REFERRAL_SIGNUP','Referral registrato',100,'RICORRENTE_MENSILE','REFERRAL'),
('REFERRAL_BASIC','Referral attiva BASIC+',500,'RICORRENTE_MENSILE','REFERRAL'),
('SCOUT_FIRST_ACCESS','Primo accesso SCOUT81+',500,'ONETIME','SCOUT81'),
('SCOUT_FILTER_SAVED','Primo filtro ATECO salvato',100,'ONETIME','SCOUT81'),
('SCOUT_MAP_SAVED','Prima mappa territorio salvata',200,'ONETIME','SCOUT81'),
('SCOUT_FIRST_PROSPECT','Primo prospect lavorato',300,'ONETIME','SCOUT81'),
('PLP_FIRST_PACK','Primo pack PLP attivato',500,'ONETIME','PLP81'),
('FOLLOWUP_10','10 follow-up lead completati',1000,'ONETIME','SCOUT81'),
('AUDIT_FROM_PROSPECT','Primo audit da prospect',500,'ONETIME','AUDIT'),
('SALE_BASIC_FROM_PROSPECT','Primo BASIC+ da vendita reale',2000,'ONETIME','SCOUT81'),
('PIPELINE_7DAYS','Pipeline aggiornata 7 giorni consecutivi',1000,'ONETIME','SCOUT81'),
('MARKETING_PLAN','Piano marketing completato',2000,'ONETIME','EQUILIBRIO'),
('EQUILIBRIO_PLAN','Piano equilibrio completato',1000,'ONETIME','EQUILIBRIO'),
('ACADEMY_CORE_COMPLETE','Academy Core completata',1000,'ONETIME','ACADEMY'),
('DOC81_FIRST','Primo documento generato',200,'ONETIME','DOC81'),
('SCADENZIARIO_FIRST','Prima scadenza inserita',100,'ONETIME','SCADENZIARIO');

-- CLAIM PV+ (log idempotente)
CREATE TABLE IF NOT EXISTS pvplus_claims (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NOT NULL,
  missione_id   BIGINT UNSIGNED NULL,
  codice_missione VARCHAR(100) NULL,
  pvplus_base   DECIMAL(18,4) NOT NULL,
  moltiplicatore DECIMAL(5,2) NOT NULL DEFAULT 1.00,
  pvplus_finali DECIMAL(18,4) NOT NULL,
  cap_applicato DECIMAL(5,2) NULL,
  riferimento   VARCHAR(255) NULL COMMENT 'ID oggetto che ha triggerato la missione',
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_user_missione_rif (user_id, codice_missione, riferimento),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user (user_id),
  INDEX idx_missione (codice_missione)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- BOOSTER ATTIVI
CREATE TABLE IF NOT EXISTS pvplus_boosters (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       BIGINT UNSIGNED NOT NULL,
  tipo          ENUM('MEMBERSHIP','CAREER','EQUILIBRIUM','GENESYS','ADMIN','PROMO') NOT NULL,
  nome          VARCHAR(100) NOT NULL,
  moltiplicatore DECIMAL(5,2) NOT NULL,
  attivo        TINYINT(1) NOT NULL DEFAULT 1,
  valido_dal    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  valido_fino   DATETIME NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_attivo (user_id, attivo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
