-- ================================================================
-- MASTER SCHEMA 81+ — DATABASE GLOBALE ECOSISTEMA COMPLETO
-- Versione: 2.0.0 | Data: 2026-06-17
-- Database: u173050672_81plusglobal
-- Tabelle: 78 | Trigger: 4 | View: 3
-- Copre: Web2 + Web3 + MLM + Pagamenti + Agenti AI + Lead
-- ================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET time_zone = '+00:00';

-- ================================================================
-- SEZIONE 1: SISTEMA E CONFIGURAZIONE
-- ================================================================

CREATE TABLE IF NOT EXISTS `system_config` (
  `chiave`     VARCHAR(80)  NOT NULL,
  `valore`     LONGTEXT     NOT NULL,
  `categoria`  VARCHAR(40)  NOT NULL DEFAULT 'general',
  `note`       VARCHAR(255) DEFAULT NULL,
  `updated_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`chiave`),
  KEY `idx_categoria` (`categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `system_config` (`chiave`,`valore`,`categoria`,`note`) VALUES
('app_name',        '81+ OS',                   'app',     'Nome sistema'),
('app_url',         'https://81plus.net',        'app',     'URL principale'),
('app_env',         'production',                'app',     'Ambiente'),
('pv_ratio_euro',   '1.00',                      'finance', 'PV = 1 euro (convenzione interna, NON moneta)'),
('genesys_max',     '81',                        'genesys', 'Max slot GENESYS81+'),
('pix81_max',       '1000',                      'pix81',   'Max slot PIX81+'),
('lock81_days',     '180',                       'lock81',  'Durata LOCK81+ in giorni - NON staking'),
('welcome_from',    'welcome@81plus.net',        'email',   'Mittente email benvenuto'),
('sic_id_start',    '10001',                     'sicid',   'Primo SIC-ID sequenziale'),
('mlm_max_depth',   '8',                         'mlm',     'Profondità massima rete MLM'),
('commission_l1',   '0.20',                      'mlm',     'Commissione diretta livello 1 (20%)'),
('commission_l2',   '0.10',                      'mlm',     'Commissione override livello 2 (10%)'),
('commission_l3',   '0.05',                      'mlm',     'Commissione livello 3 (5%)'),
('revolut_mode',    'sandbox',                   'payments','Modalità Revolut: sandbox/production'),
('crypto_network',  'BSC',                       'crypto',  'Rete crypto principale: BEP-20'),
('x81_supply',      '21000000',                  'web3',    'Supply totale 81X'),
('x81_burn_pct',    '0.5',                       'web3',    'Burn % per trasferimento 81X'),
('semantic_guard',  '1',                         'ai',      '1=attivo, 0=disattivo');

CREATE TABLE IF NOT EXISTS `sessioni` (
  `session_id`  VARCHAR(128) NOT NULL,
  `user_id`     BIGINT UNSIGNED DEFAULT NULL,
  `ip_address`  VARCHAR(45)  DEFAULT NULL,
  `user_agent`  VARCHAR(300) DEFAULT NULL,
  `payload`     LONGTEXT     NOT NULL,
  `ultimo_uso`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`session_id`),
  KEY `idx_user`    (`user_id`),
  KEY `idx_uso`     (`ultimo_uso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `signup_attempts` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_hash`    VARCHAR(64)  NOT NULL,
  `email_hash` VARCHAR(64)  DEFAULT NULL,
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_ip_time` (`ip_hash`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cookie_consents` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    BIGINT UNSIGNED DEFAULT NULL,
  `session_id` VARCHAR(100) DEFAULT NULL,
  `ip_address` VARCHAR(45)  DEFAULT NULL,
  `analytics`  TINYINT(1)   NOT NULL DEFAULT 0,
  `marketing`  TINYINT(1)   NOT NULL DEFAULT 0,
  `tecnici`    TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user`    (`user_id`),
  KEY `idx_session` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- SEZIONE 2: UTENTI — TABELLA MASTER ECOSISTEMA
-- ================================================================

CREATE TABLE IF NOT EXISTS `users` (
  `id`                   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  -- Identità
  `sic_id`               VARCHAR(20)  NOT NULL                    COMMENT 'SIC-ID immutabile univoco',
  `email`                VARCHAR(255) NOT NULL,
  `username`             VARCHAR(80)  DEFAULT NULL,
  `password_hash`        VARCHAR(255) NOT NULL                    DEFAULT '',
  `nome`                 VARCHAR(100) DEFAULT NULL,
  `cognome`              VARCHAR(100) DEFAULT NULL,
  `telefono`             VARCHAR(30)  DEFAULT NULL,
  `telegram_user`        VARCHAR(80)  DEFAULT NULL,
  `whatsapp`             VARCHAR(30)  DEFAULT NULL,
  `avatar_url`           TEXT         DEFAULT NULL,
  -- Dati aziendali / fiscali
  `codice_fiscale`       VARCHAR(20)  DEFAULT NULL,
  `partita_iva`          VARCHAR(20)  DEFAULT NULL,
  `ragione_sociale`      VARCHAR(200) DEFAULT NULL,
  `codice_ateco`         VARCHAR(20)  DEFAULT NULL,
  `macrosettore`         VARCHAR(60)  DEFAULT NULL,
  `num_dipendenti`       SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `pec`                  VARCHAR(191) DEFAULT NULL,
  `codice_sdi`           VARCHAR(10)  DEFAULT NULL,
  `settore`              VARCHAR(80)  DEFAULT NULL,
  `indirizzo`            VARCHAR(255) DEFAULT NULL,
  `comune`               VARCHAR(100) DEFAULT NULL,
  `provincia`            CHAR(2)      DEFAULT NULL,
  `regione`              VARCHAR(60)  DEFAULT NULL,
  `cap`                  CHAR(5)      DEFAULT NULL,
  -- Ruolo e status
  `ruolo`                ENUM('MEMBER81','NETWORKER81','ELITE81','ADMIN81') NOT NULL DEFAULT 'MEMBER81',
  `status`               ENUM('ACTIVE','SUSPENDED','INACTIVE','BLOCKED','PENDING') NOT NULL DEFAULT 'PENDING',
  `is_active`            TINYINT(1)   NOT NULL DEFAULT 1,
  `suspension_reason`    VARCHAR(255) DEFAULT NULL,
  -- Membership
  `membership_tier`      ENUM('NONE','BASIC+','PRO+','ELITE+') NOT NULL DEFAULT 'NONE',
  `membership_scade_il`  DATE         DEFAULT NULL,
  -- GENESYS81+
  `genesys_status`       ENUM('NONE','GENESYS_MEMBER','GENESYS_NETWORKER','GENESYS_LEADER','GENESYS_FOUNDER') NOT NULL DEFAULT 'NONE',
  `genesys_promo_active` TINYINT(1)   NOT NULL DEFAULT 0,
  `genesys_promo_expires` DATE        DEFAULT NULL,
  -- Career
  `career_level`         TINYINT UNSIGNED NOT NULL DEFAULT 0     COMMENT 'L0-L8',
  `equilibrium_status`   ENUM('STARTER','ACTIVE','FOCUS','MASTER','ELITE','SPECIAL') NOT NULL DEFAULT 'STARTER',
  -- Club / Franchising
  `club_status`          TINYINT(1)   NOT NULL DEFAULT 0,
  `franchise_status`     TINYINT(1)   NOT NULL DEFAULT 0,
  `networker_status`     TINYINT(1)   NOT NULL DEFAULT 0,
  -- Referral
  `referral_ref`         VARCHAR(20)  DEFAULT NULL               COMMENT 'SIC-ID sponsor',
  -- KYC / Verifica
  `kyc_status`           ENUM('NONE','PENDING','VERIFIED','REJECTED') NOT NULL DEFAULT 'NONE',
  `email_verified_at`    DATETIME     DEFAULT NULL,
  `profilo_completo`     TINYINT(1)   NOT NULL DEFAULT 0,
  -- Web3
  `wallet_address`       VARCHAR(100) DEFAULT NULL               COMMENT 'BSC/BEP20',
  -- Timestamp
  `last_login`           DATETIME     DEFAULT NULL,
  `note_interne`         TEXT         DEFAULT NULL,
  `created_at`           DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`           DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_sic_id`       (`sic_id`),
  UNIQUE KEY `uk_email`        (`email`),
  UNIQUE KEY `uk_username`     (`username`),
  UNIQUE KEY `uidx_wallet`     (`wallet_address`),
  KEY `idx_ruolo`              (`ruolo`),
  KEY `idx_status`             (`status`),
  KEY `idx_membership_tier`    (`membership_tier`),
  KEY `idx_genesys`            (`genesys_status`),
  KEY `idx_referral`           (`referral_ref`),
  KEY `idx_piva`               (`partita_iva`),
  KEY `idx_ateco`              (`codice_ateco`),
  KEY `idx_provincia`          (`provincia`),
  KEY `idx_created`            (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_profiles` (
  `id`                      BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`                 BIGINT UNSIGNED NOT NULL,
  `ragione_sociale`         VARCHAR(200) DEFAULT NULL,
  `piva`                    VARCHAR(20)  DEFAULT NULL,
  `settore_ateco`           VARCHAR(20)  DEFAULT NULL,
  `num_dipendenti`          TINYINT UNSIGNED DEFAULT NULL,
  `sito_web`                VARCHAR(255) DEFAULT NULL,
  `bio`                     TEXT         DEFAULT NULL,
  `indirizzo`               VARCHAR(255) DEFAULT NULL,
  `comune`                  VARCHAR(100) DEFAULT NULL,
  `provincia`               CHAR(2)      DEFAULT NULL,
  `regione`                 VARCHAR(60)  DEFAULT NULL,
  `cap`                     CHAR(5)      DEFAULT NULL,
  `genesys_promo_accepted_at` DATETIME   DEFAULT NULL,
  `profile_completed_at`    DATETIME     DEFAULT NULL,
  `created_at`              DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`              DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user` (`user_id`),
  CONSTRAINT `fk_up_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `wallet_bind_log` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`        BIGINT UNSIGNED NOT NULL,
  `wallet_address` VARCHAR(100) NOT NULL,
  `ip_hash`        VARCHAR(64)  NOT NULL,
  `user_agent`     VARCHAR(300) DEFAULT NULL,
  `status`         ENUM('SUCCESS','FAILED','DUPLICATE') NOT NULL DEFAULT 'SUCCESS',
  `created_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user`   (`user_id`),
  KEY `idx_wallet` (`wallet_address`),
  CONSTRAINT `fk_wbl_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- SEZIONE 3: WALLET 5 STRATI
-- ================================================================

CREATE TABLE IF NOT EXISTS `wallets` (
  `id`                   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`              BIGINT UNSIGNED NOT NULL,
  `pv_balance`           DECIMAL(18,4) NOT NULL DEFAULT 0.0000   COMMENT 'PV credito interno 1:1 euro — NON moneta',
  `pvplus_balance`       DECIMAL(18,4) NOT NULL DEFAULT 0.0000   COMMENT 'PV+ reward gamificato — NON denaro',
  `saf_balance`          DECIMAL(18,8) NOT NULL DEFAULT 0.00000000 COMMENT 'SAF utility token',
  `x81_balance`          DECIMAL(18,8) NOT NULL DEFAULT 0.00000000 COMMENT '81X token BEP-20',
  `usdt_balance`         DECIMAL(18,6) NOT NULL DEFAULT 0.000000  COMMENT 'USDT custodito',
  `pvplus_career_total`  DECIMAL(18,4) NOT NULL DEFAULT 0.0000   COMMENT 'PV+ lifetime per career level',
  `pv_booster_rate`      DECIMAL(5,2)  NOT NULL DEFAULT 1.00     COMMENT 'moltiplicatore PV+ attivo',
  `updated_at`           DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user` (`user_id`),
  CONSTRAINT `fk_w_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pv_transactions` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     BIGINT UNSIGNED NOT NULL,
  `tipo`        ENUM('ACQUISTO','CONSUMO','BONUS','RIMBORSO','CORREZIONE_ADMIN','REFERRAL','COMMISSIONE','MLM_OVERRIDE','LOCK81_BENEFIT') NOT NULL,
  `importo`     DECIMAL(18,4) NOT NULL,
  `saldo_dopo`  DECIMAL(18,4) NOT NULL,
  `valuta`      ENUM('PV','PV+','SAF','81X','USDT') NOT NULL DEFAULT 'PV',
  `riferimento` VARCHAR(100)  DEFAULT NULL,
  `nota`        VARCHAR(255)  DEFAULT NULL,
  `admin_id`    BIGINT UNSIGNED DEFAULT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_tipo`  (`user_id`, `tipo`),
  KEY `idx_valuta`     (`valuta`),
  KEY `idx_created`    (`created_at`),
  CONSTRAINT `fk_pvt_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- SEZIONE 4: PAGAMENTI — REVOLUT, PAYPAL, CRYPTO, STRIPE
-- ================================================================

CREATE TABLE IF NOT EXISTS `pagamenti_revolut` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`        BIGINT UNSIGNED NOT NULL,
  `revolut_order_id` VARCHAR(100) DEFAULT NULL COMMENT 'ID ordine Revolut',
  `revolut_public_id` VARCHAR(100) DEFAULT NULL,
  `importo_euro`   DECIMAL(12,2) NOT NULL,
  `valuta`         CHAR(3) NOT NULL DEFAULT 'EUR',
  `tipo`           ENUM('PV_PACK','MEMBERSHIP','PIX81','PLP_PACK','ABBONAMENTO','ALTRO') NOT NULL,
  `pv_da_erogare`  DECIMAL(18,4) NOT NULL DEFAULT 0,
  `pvplus_da_erogare` DECIMAL(18,4) NOT NULL DEFAULT 0,
  `status`         ENUM('PENDING','COMPLETED','FAILED','REFUNDED','CANCELLED','AUTHORISED') NOT NULL DEFAULT 'PENDING',
  `webhook_data`   JSON DEFAULT NULL,
  `completato_at`  DATETIME DEFAULT NULL,
  `created_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_revolut_order` (`revolut_order_id`),
  KEY `idx_user`   (`user_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_rev_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pagamenti_paypal` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`        BIGINT UNSIGNED NOT NULL,
  `paypal_order_id` VARCHAR(100) DEFAULT NULL,
  `paypal_payer_id` VARCHAR(100) DEFAULT NULL,
  `importo_euro`   DECIMAL(12,2) NOT NULL,
  `tipo`           ENUM('PV_PACK','MEMBERSHIP','PIX81','PLP_PACK','ABBONAMENTO','ALTRO') NOT NULL,
  `pv_da_erogare`  DECIMAL(18,4) NOT NULL DEFAULT 0,
  `pvplus_da_erogare` DECIMAL(18,4) NOT NULL DEFAULT 0,
  `status`         ENUM('PENDING','COMPLETED','FAILED','REFUNDED','CANCELLED') NOT NULL DEFAULT 'PENDING',
  `webhook_data`   JSON DEFAULT NULL,
  `completato_at`  DATETIME DEFAULT NULL,
  `created_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_paypal_order` (`paypal_order_id`),
  KEY `idx_user`   (`user_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_ppl_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pagamenti_stripe` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`          BIGINT UNSIGNED NOT NULL,
  `stripe_payment_id` VARCHAR(100) DEFAULT NULL,
  `stripe_session_id` VARCHAR(100) DEFAULT NULL,
  `stripe_customer_id` VARCHAR(100) DEFAULT NULL,
  `importo_euro`     DECIMAL(12,2) NOT NULL,
  `tipo`             ENUM('PV_PACK','MEMBERSHIP','PIX81','PLP_PACK','ABBONAMENTO','ALTRO') NOT NULL,
  `pv_da_erogare`    DECIMAL(18,4) NOT NULL DEFAULT 0,
  `pvplus_da_erogare` DECIMAL(18,4) NOT NULL DEFAULT 0,
  `status`           ENUM('PENDING','COMPLETED','FAILED','REFUNDED','CANCELLED') NOT NULL DEFAULT 'PENDING',
  `webhook_data`     JSON DEFAULT NULL,
  `completato_at`    DATETIME DEFAULT NULL,
  `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_stripe_payment` (`stripe_payment_id`),
  KEY `idx_user`     (`user_id`),
  KEY `idx_status`   (`status`),
  CONSTRAINT `fk_str_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pagamenti_crypto` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`         BIGINT UNSIGNED NOT NULL,
  `rete`            ENUM('BSC','ETH','TRX','SOL','MATIC','BTC') NOT NULL DEFAULT 'BSC',
  `token`           VARCHAR(20)  NOT NULL DEFAULT 'USDT'          COMMENT 'USDT, BNB, 81X, ecc.',
  `wallet_mittente` VARCHAR(100) DEFAULT NULL,
  `wallet_ricevente` VARCHAR(100) NOT NULL,
  `importo_token`   DECIMAL(18,8) NOT NULL,
  `importo_euro_equiv` DECIMAL(12,2) DEFAULT NULL,
  `tx_hash`         VARCHAR(100) DEFAULT NULL                     COMMENT 'Hash transazione blockchain',
  `blocchi_confermati` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `tipo`            ENUM('PV_PACK','MEMBERSHIP','PIX81','PLP_PACK','X81_PURCHASE','AIRDROP','ALTRO') NOT NULL,
  `pv_da_erogare`   DECIMAL(18,4) NOT NULL DEFAULT 0,
  `pvplus_da_erogare` DECIMAL(18,4) NOT NULL DEFAULT 0,
  `status`          ENUM('PENDING','CONFIRMING','COMPLETED','FAILED','EXPIRED') NOT NULL DEFAULT 'PENDING',
  `completato_at`   DATETIME DEFAULT NULL,
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_tx_hash` (`tx_hash`),
  KEY `idx_user`    (`user_id`),
  KEY `idx_status`  (`status`),
  KEY `idx_rete`    (`rete`),
  CONSTRAINT `fk_cry_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pagamenti_bonifico` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`       BIGINT UNSIGNED NOT NULL,
  `causale`       VARCHAR(200) NOT NULL,
  `importo_euro`  DECIMAL(12,2) NOT NULL,
  `tipo`          ENUM('PV_PACK','MEMBERSHIP','PIX81','PLP_PACK','ABBONAMENTO','ALTRO') NOT NULL,
  `pv_da_erogare` DECIMAL(18,4) NOT NULL DEFAULT 0,
  `iban_mittente` VARCHAR(34)  DEFAULT NULL,
  `data_valuta`   DATE         DEFAULT NULL,
  `prova_url`     VARCHAR(255) DEFAULT NULL                       COMMENT 'Screenshot ricevuta',
  `status`        ENUM('ATTESA_VERIFICA','VERIFICATO','RIFIUTATO') NOT NULL DEFAULT 'ATTESA_VERIFICA',
  `verificato_da` BIGINT UNSIGNED DEFAULT NULL                   COMMENT 'admin_id',
  `note`          TEXT DEFAULT NULL,
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user`   (`user_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_bon_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `paygate_orders` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`          BIGINT UNSIGNED NOT NULL,
  `order_code`       VARCHAR(40)  NOT NULL,
  `tipo`             ENUM('PV_PACK','MEMBERSHIP','PIX81','PLP_PACK','SPECIAL_PACK','ABBONAMENTO','ALTRO') NOT NULL,
  `importo_euro`     DECIMAL(18,2) NOT NULL,
  `pv_erogati`       DECIMAL(18,4) NOT NULL DEFAULT 0,
  `pvplus_erogati`   DECIMAL(18,4) NOT NULL DEFAULT 0,
  `metodo`           ENUM('REVOLUT','PAYPAL','STRIPE','BONIFICO','CRYPTO','INTERNO') NOT NULL,
  `metodo_ref_id`    BIGINT UNSIGNED DEFAULT NULL               COMMENT 'FK a pagamenti_revolut/paypal/stripe/crypto/bonifico',
  `status`           ENUM('PENDING','COMPLETED','FAILED','REFUNDED','CANCELLED') NOT NULL DEFAULT 'PENDING',
  `riferimento_ext`  VARCHAR(255) DEFAULT NULL,
  `note`             TEXT DEFAULT NULL,
  `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_order_code` (`order_code`),
  KEY `idx_user`     (`user_id`),
  KEY `idx_status`   (`status`),
  CONSTRAINT `fk_pgo_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `shop_ordini` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`        BIGINT UNSIGNED DEFAULT NULL,
  `order_ref`      VARCHAR(32)  NOT NULL,
  `fornitore`      ENUM('bigbuy','gelato','printify','interno','altro') NOT NULL,
  `totale_euro`    DECIMAL(10,2) NOT NULL DEFAULT 0,
  `stato`          ENUM('pagato','in_lavorazione','spedito','consegnato','annullato') NOT NULL DEFAULT 'pagato',
  `tracking`       VARCHAR(100) DEFAULT NULL,
  `indirizzo_json` JSON DEFAULT NULL,
  `items_json`     JSON DEFAULT NULL,
  `created_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_order_ref` (`order_ref`),
  KEY `idx_user`   (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `abbonamenti` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`        BIGINT UNSIGNED NOT NULL,
  `piano`          VARCHAR(20)  NOT NULL,
  `metodo_pagamento` ENUM('REVOLUT','PAYPAL','STRIPE','CRYPTO','INTERNO') NOT NULL DEFAULT 'STRIPE',
  `metodo_subscription_id` VARCHAR(100) DEFAULT NULL           COMMENT 'ID sottoscrizione Stripe/PayPal/Revolut',
  `importo_euro`   DECIMAL(10,2) NOT NULL,
  `valuta`         CHAR(3) NOT NULL DEFAULT 'EUR',
  `status`         ENUM('ACTIVE','PAUSED','CANCELLED','EXPIRED','TRIAL') NOT NULL DEFAULT 'ACTIVE',
  `iniziato_il`    DATE NOT NULL,
  `prossimo_rinnovo` DATE DEFAULT NULL,
  `cancellato_il`  DATETIME DEFAULT NULL,
  `rinnovo_auto`   TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user`   (`user_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_abb_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- SEZIONE 5: PV+ GAMIFICATION
-- ================================================================

CREATE TABLE IF NOT EXISTS `pvplus_missions` (
  `id`                 BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codice`             VARCHAR(100) NOT NULL,
  `nome`               VARCHAR(200) NOT NULL,
  `descrizione`        TEXT DEFAULT NULL,
  `pvplus_base`        DECIMAL(18,4) NOT NULL,
  `tipo`               ENUM('ONETIME','RICORRENTE_GIORNALIERO','RICORRENTE_SETTIMANALE','RICORRENTE_MENSILE','PROGRESSIVA') NOT NULL DEFAULT 'ONETIME',
  `ruolo_minimo`       ENUM('MEMBER81','NETWORKER81','ELITE81','ADMIN81') NOT NULL DEFAULT 'MEMBER81',
  `genesys_richiesto`  TINYINT(1) NOT NULL DEFAULT 0,
  `categoria`          VARCHAR(60) DEFAULT NULL,
  `attiva`             TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_codice` (`codice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `pvplus_missions` (`codice`,`nome`,`pvplus_base`,`tipo`,`categoria`) VALUES
('WELCOME',                 'Benvenuto 81+',                           100,  'ONETIME',             'ONBOARDING'),
('PROFILE_COMPLETE',        'Profilo completato',                      200,  'ONETIME',             'ONBOARDING'),
('KYC_COMPLETE',            'KYC verificato',                          1000, 'ONETIME',             'ONBOARDING'),
('EMAIL_VERIFIED',          'Email verificata',                        50,   'ONETIME',             'ONBOARDING'),
('GENESYS_SIGNUP',          'Registrazione GENESYS promo',             1000, 'ONETIME',             'ONBOARDING'),
('GENESYS_PROFILE',         'Profilo completo promo GENESYS',          1000, 'ONETIME',             'ONBOARDING'),
('FIRST_AUDIT',             'Primo audit completato',                  300,  'ONETIME',             'AUDIT'),
('FIRST_BASIC',             'Prima attivazione BASIC+',                100,  'ONETIME',             'MEMBERSHIP'),
('FIRST_PRO',               'Prima attivazione PRO+',                  250,  'ONETIME',             'MEMBERSHIP'),
('FIRST_ELITE',             'Prima attivazione ELITE+',                500,  'ONETIME',             'MEMBERSHIP'),
('REFERRAL_SIGNUP',         'Referral registrato',                     100,  'RICORRENTE_MENSILE',  'REFERRAL'),
('REFERRAL_BASIC',          'Referral attiva BASIC+',                  500,  'RICORRENTE_MENSILE',  'REFERRAL'),
('SCOUT_FIRST_ACCESS',      'Primo accesso SCOUT81+',                  500,  'ONETIME',             'SCOUT81'),
('SCOUT_FILTER_SAVED',      'Primo filtro ATECO salvato',              100,  'ONETIME',             'SCOUT81'),
('SCOUT_MAP_SAVED',         'Prima mappa territorio salvata',          200,  'ONETIME',             'SCOUT81'),
('SCOUT_FIRST_PROSPECT',    'Primo prospect lavorato',                 300,  'ONETIME',             'SCOUT81'),
('PLP_FIRST_PACK',          'Primo pack PLP attivato',                 500,  'ONETIME',             'PLP81'),
('FOLLOWUP_10',             '10 follow-up lead completati',            1000, 'ONETIME',             'SCOUT81'),
('AUDIT_FROM_PROSPECT',     'Primo audit da prospect',                 500,  'ONETIME',             'AUDIT'),
('SALE_BASIC_FROM_PROSPECT','Primo BASIC+ da vendita reale',           2000, 'ONETIME',             'SCOUT81'),
('PIPELINE_7DAYS',          'Pipeline aggiornata 7 giorni consecutivi',1000, 'ONETIME',             'SCOUT81'),
('MARKETING_PLAN',          'Piano marketing completato',              2000, 'ONETIME',             'EQUILIBRIO'),
('EQUILIBRIO_PLAN',         'Piano equilibrio completato',             1000, 'ONETIME',             'EQUILIBRIO'),
('ACADEMY_CORE_COMPLETE',   'Academy Core completata',                 1000, 'ONETIME',             'ACADEMY'),
('DOC81_FIRST',             'Primo documento generato',                200,  'ONETIME',             'DOC81'),
('SCADENZIARIO_FIRST',      'Prima scadenza inserita',                 100,  'ONETIME',             'SCADENZIARIO'),
('PAYMENT_REVOLUT',         'Primo pagamento con Revolut',             200,  'ONETIME',             'PAYMENT'),
('PAYMENT_CRYPTO',          'Primo pagamento crypto',                  500,  'ONETIME',             'PAYMENT'),
('WALLET_BOUND',            'Wallet BSC collegato',                    300,  'ONETIME',             'WEB3'),
('PIX81_ACQUIRED',          'PIX81+ slot acquisito',                   1000, 'ONETIME',             'PIX81'),
('LOCK81_ACTIVATED',        'LOCK81+ attivato',                        500,  'ONETIME',             'LOCK81');

CREATE TABLE IF NOT EXISTS `pvplus_claims` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`         BIGINT UNSIGNED NOT NULL,
  `missione_id`     BIGINT UNSIGNED DEFAULT NULL,
  `codice_missione` VARCHAR(100)  DEFAULT NULL,
  `pvplus_base`     DECIMAL(18,4) NOT NULL,
  `moltiplicatore`  DECIMAL(5,2)  NOT NULL DEFAULT 1.00,
  `pvplus_finali`   DECIMAL(18,4) NOT NULL,
  `cap_applicato`   DECIMAL(5,2)  DEFAULT NULL,
  `riferimento`     VARCHAR(255)  DEFAULT NULL             COMMENT 'ID oggetto trigger missione',
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_idempotent` (`user_id`, `codice_missione`, `riferimento`),
  KEY `idx_user`    (`user_id`),
  KEY `idx_missione`(`codice_missione`),
  CONSTRAINT `fk_pvc_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pvplus_boosters` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`       BIGINT UNSIGNED NOT NULL,
  `tipo`          ENUM('MEMBERSHIP','CAREER','EQUILIBRIUM','GENESYS','ADMIN','PROMO') NOT NULL,
  `nome`          VARCHAR(100) NOT NULL,
  `moltiplicatore` DECIMAL(5,2) NOT NULL,
  `attivo`        TINYINT(1) NOT NULL DEFAULT 1,
  `valido_dal`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `valido_fino`   DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_attivo` (`user_id`, `attivo`),
  CONSTRAINT `fk_pvb_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- SEZIONE 6: MEMBERSHIP E PIANI
-- ================================================================

CREATE TABLE IF NOT EXISTS `membership_plans` (
  `id`              TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codice`          VARCHAR(10) NOT NULL,
  `nome`            VARCHAR(50) NOT NULL,
  `pv_mensile`      DECIMAL(8,2) NOT NULL,
  `pvplus_prima`    DECIMAL(8,2) NOT NULL COMMENT 'PV+ prima attivazione',
  `pvplus_rinnovo`  DECIMAL(8,2) NOT NULL COMMENT 'PV+ ogni rinnovo mensile',
  `features_json`   JSON DEFAULT NULL,
  `is_active`       TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order`      TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_codice` (`codice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `membership_plans` (`codice`,`nome`,`pv_mensile`,`pvplus_prima`,`pvplus_rinnovo`,`sort_order`) VALUES
('BASIC+',  'Basic Plus',  29.90,  100.00,  30.00, 1),
('PRO+',    'Pro Plus',    59.90,  250.00,  60.00, 2),
('ELITE+',  'Elite Plus',  89.90,  500.00,  90.00, 3);

CREATE TABLE IF NOT EXISTS `memberships` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`      BIGINT UNSIGNED NOT NULL,
  `piano_codice` VARCHAR(10) NOT NULL,
  `status`       ENUM('ACTIVE','EXPIRED','CANCELLED','PAUSED') NOT NULL DEFAULT 'ACTIVE',
  `attivato_il`  DATE NOT NULL,
  `scade_il`     DATE DEFAULT NULL,
  `rinnovo_auto` TINYINT(1) NOT NULL DEFAULT 1,
  `order_id`     BIGINT UNSIGNED DEFAULT NULL,
  `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_status` (`user_id`, `status`),
  KEY `idx_scade`       (`scade_il`),
  CONSTRAINT `fk_mem_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- SEZIONE 7: GENESYS81+, PIX81+, LOCK81+
-- ================================================================

CREATE TABLE IF NOT EXISTS `genesys_applications` (
  `id`                   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`              BIGINT UNSIGNED DEFAULT NULL,
  `sic_id`               VARCHAR(20) DEFAULT NULL,
  `nome`                 VARCHAR(100) NOT NULL,
  `cognome`              VARCHAR(100) NOT NULL,
  `email`                VARCHAR(255) NOT NULL,
  `telefono`             VARCHAR(30) DEFAULT NULL,
  `settore`              VARCHAR(100) DEFAULT NULL,
  `community_size`       INT UNSIGNED DEFAULT NULL,
  `piattaforma`          VARCHAR(80) DEFAULT NULL,
  `telegram_username`    VARCHAR(80) DEFAULT NULL,
  `social_url`           VARCHAR(255) DEFAULT NULL,
  `followers_range`      VARCHAR(30) DEFAULT NULL,
  `community_type`       VARCHAR(80) DEFAULT NULL,
  `interest`             VARCHAR(80) DEFAULT NULL,
  `motivazione`          TEXT DEFAULT NULL,
  `vuole_pix`            TINYINT(1) NOT NULL DEFAULT 0,
  `come_ha_conosciuto`   VARCHAR(200) DEFAULT NULL,
  `ruolo_richiesto`      VARCHAR(30) DEFAULT NULL,
  `status`               ENUM('PENDING','IN_VALUTAZIONE','APPROVATA','RIFIUTATA') NOT NULL DEFAULT 'PENDING',
  `promo_pvplus_sent`    TINYINT(1) NOT NULL DEFAULT 0,
  `profile_pvplus_sent`  TINYINT(1) NOT NULL DEFAULT 0,
  `note_admin`           TEXT DEFAULT NULL,
  `created_at`           DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email`        (`email`),
  KEY `idx_status`       (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pix81_slots` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `numero`        SMALLINT UNSIGNED NOT NULL COMMENT '1-1000 MAX',
  `tipo`          ENUM('FOUNDER','PUBBLICO','SPONSOR','COMMUNITY','RISERVATO') NOT NULL DEFAULT 'PUBBLICO',
  `user_id`       BIGINT UNSIGNED DEFAULT NULL,
  `prezzo_pv`     DECIMAL(18,4) NOT NULL DEFAULT 750,
  `pvplus_bonus`  DECIMAL(18,4) NOT NULL DEFAULT 1000,
  `special_pack`  TINYINT(1) NOT NULL DEFAULT 0,
  `status`        ENUM('LIBERO','RISERVATO','VENDUTO','SCADUTO') NOT NULL DEFAULT 'LIBERO',
  `assegnato_il`  DATETIME DEFAULT NULL,
  `scade_riserva` DATETIME DEFAULT NULL,
  `ad_titolo`     VARCHAR(200) DEFAULT NULL,
  `ad_url`        VARCHAR(500) DEFAULT NULL,
  `ad_immagine`   VARCHAR(255) DEFAULT NULL,
  `note`          VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_numero` (`numero`),
  KEY `idx_status` (`status`),
  KEY `idx_tipo`   (`tipo`),
  CONSTRAINT `fk_pix_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `pix81_slots` (`numero`, `tipo`)
SELECT n, CASE WHEN n <= 200 THEN 'FOUNDER' ELSE 'PUBBLICO' END
FROM (
  SELECT a.N + b.N*10 + c.N*100 + 1 AS n
  FROM (SELECT 0 N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
        UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
       (SELECT 0 N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
        UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) b,
       (SELECT 0 N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
        UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) c
  HAVING n BETWEEN 1 AND 1000
) nums;

CREATE TABLE IF NOT EXISTS `lock81_contracts` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`      BIGINT UNSIGNED NOT NULL,
  `pv_amount`    DECIMAL(18,4) NOT NULL,
  `started_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ends_at`      DATETIME NOT NULL                             COMMENT '180 gg da started_at — generato da applicazione',
  `status`       ENUM('ACTIVE','COMPLETED','EARLY_EXIT') NOT NULL DEFAULT 'ACTIVE',
  `benefit_tier` VARCHAR(20) DEFAULT NULL,
  `benefits_json` JSON DEFAULT NULL,
  `note`         VARCHAR(255) DEFAULT NULL                     COMMENT 'NON è staking. NON è rendimento. Fedeltà interna 180gg.',
  `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user`   (`user_id`),
  KEY `idx_status` (`status`),
  KEY `idx_ends`   (`ends_at`),
  CONSTRAINT `fk_lck_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- SEZIONE 8: LEADS — DATABASE COMPLETO 4179+ CONTATTI
-- ================================================================

CREATE TABLE IF NOT EXISTS `leads` (
  `id`                    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sic_id`                VARCHAR(20) NOT NULL                   COMMENT 'SIC-ID assegnato al lead',
  `nome`                  VARCHAR(200) DEFAULT NULL,
  `email`                 VARCHAR(255) NOT NULL,
  `telefono`              VARCHAR(40)  DEFAULT NULL,
  `whatsapp`              VARCHAR(40)  DEFAULT NULL,
  `settore`               VARCHAR(80)  DEFAULT NULL,
  `ateco`                 VARCHAR(20)  DEFAULT NULL,
  `macrosettore`          VARCHAR(60)  DEFAULT NULL,
  `dipendenti`            SMALLINT UNSIGNED DEFAULT NULL,
  `comune`                VARCHAR(100) DEFAULT NULL,
  `provincia`             CHAR(2)      DEFAULT NULL,
  `regione`               VARCHAR(60)  DEFAULT NULL,
  `rischio`               ENUM('BASSO','MEDIO','ALTO') DEFAULT NULL,
  `stato`                 ENUM('freddo','tiepido','caldo','prospect','cliente','perso') NOT NULL DEFAULT 'freddo',
  `score`                 TINYINT UNSIGNED NOT NULL DEFAULT 30,
  `fonte`                 VARCHAR(80)  DEFAULT NULL,
  `utm_source`            VARCHAR(80)  DEFAULT NULL,
  `utm_medium`            VARCHAR(80)  DEFAULT NULL,
  `utm_campaign`          VARCHAR(80)  DEFAULT NULL,
  `note`                  TEXT DEFAULT NULL,
  `convertito_user_id`    BIGINT UNSIGNED DEFAULT NULL           COMMENT 'FK users se si registra',
  `email_benvenuto_inviata` TINYINT(1) NOT NULL DEFAULT 0,
  `whatsapp_inviato`      TINYINT(1) NOT NULL DEFAULT 0,
  `audit_fatto`           TINYINT(1) NOT NULL DEFAULT 0,
  `last_contact`          DATETIME DEFAULT NULL,
  `next_followup`         DATE DEFAULT NULL,
  `agent_assigned`        VARCHAR(50)  DEFAULT NULL              COMMENT 'slug agente AI responsabile',
  `created_at`            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_email`  (`email`),
  UNIQUE KEY `uk_sic_id` (`sic_id`),
  KEY `idx_stato`         (`stato`),
  KEY `idx_score`         (`score`),
  KEY `idx_fonte`         (`fonte`),
  KEY `idx_provincia`     (`provincia`),
  KEY `idx_next_followup` (`next_followup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lead_interactions` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lead_id`    BIGINT UNSIGNED NOT NULL,
  `tipo`       ENUM('EMAIL','WHATSAPP','CHIAMATA','AUDIT','PREVENTIVO','RIUNIONE','NOTE','AI_ACTION') NOT NULL,
  `direzione`  ENUM('INBOUND','OUTBOUND') NOT NULL DEFAULT 'OUTBOUND',
  `contenuto`  TEXT DEFAULT NULL,
  `agente`     VARCHAR(50) DEFAULT NULL                         COMMENT 'slug agente AI o nome operatore',
  `esito`      ENUM('RISPOSTO','NON_RISPOSTO','INTERESSATO','NON_INTERESSATO','RIMANDATO') DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_lead` (`lead_id`),
  KEY `idx_tipo` (`tipo`),
  CONSTRAINT `fk_li_lead` FOREIGN KEY (`lead_id`) REFERENCES `leads`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- SEZIONE 9: REFERRAL E MLM NETWORK
-- ================================================================

CREATE TABLE IF NOT EXISTS `referral_links` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`          BIGINT UNSIGNED NOT NULL,
  `sic_id_ref`       VARCHAR(20) NOT NULL,
  `click_count`      INT UNSIGNED NOT NULL DEFAULT 0,
  `signup_count`     INT UNSIGNED NOT NULL DEFAULT 0,
  `conversion_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `pvplus_earned`    DECIMAL(18,4) NOT NULL DEFAULT 0,
  `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user`    (`user_id`),
  UNIQUE KEY `uk_sic_ref` (`sic_id_ref`),
  CONSTRAINT `fk_rl_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `referral_conversions` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `referrer_id`     BIGINT UNSIGNED NOT NULL,
  `referred_id`     BIGINT UNSIGNED NOT NULL,
  `tipo`            ENUM('SIGNUP','BASIC+','PRO+','ELITE+','PIX81','PLP','GENESYS','ALTRO') NOT NULL,
  `pvplus_assegnati` DECIMAL(18,4) NOT NULL DEFAULT 0,
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_referrer` (`referrer_id`),
  CONSTRAINT `fk_rc_ref` FOREIGN KEY (`referrer_id`) REFERENCES `users`(`id`),
  CONSTRAINT `fk_rc_red` FOREIGN KEY (`referred_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `mlm_tree` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`      BIGINT UNSIGNED NOT NULL                       COMMENT 'nodo figlio',
  `sponsor_id`   BIGINT UNSIGNED NOT NULL                       COMMENT 'nodo padre diretto',
  `livello`      TINYINT UNSIGNED NOT NULL DEFAULT 1            COMMENT '1=diretto, 2=override, max 8',
  `path`         VARCHAR(500) DEFAULT NULL                      COMMENT 'percorso ancestors ID separati da /',
  `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user` (`user_id`),
  KEY `idx_sponsor` (`sponsor_id`),
  KEY `idx_livello` (`livello`),
  CONSTRAINT `fk_mlm_user`    FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_mlm_sponsor` FOREIGN KEY (`sponsor_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `commissioni_mlm` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `beneficiario_id`  BIGINT UNSIGNED NOT NULL                   COMMENT 'chi riceve la commissione',
  `trigger_user_id`  BIGINT UNSIGNED NOT NULL                   COMMENT 'chi ha generato la vendita',
  `livello`          TINYINT UNSIGNED NOT NULL,
  `tipo`             ENUM('DIRETTA','OVERRIDE','CLUB_BONUS','FRANCHISE_BONUS','GENESYS_BONUS') NOT NULL,
  `importo_pv`       DECIMAL(18,4) NOT NULL,
  `importo_euro`     DECIMAL(12,2) DEFAULT NULL,
  `percentuale`      DECIMAL(5,2) NOT NULL,
  `vendita_ref`      BIGINT UNSIGNED DEFAULT NULL               COMMENT 'ID paygate_orders',
  `mese`             CHAR(7) NOT NULL                           COMMENT 'YYYY-MM',
  `status`           ENUM('MATURATO','VALIDATO','PAGATO','BLOCCATO') NOT NULL DEFAULT 'MATURATO',
  `giorno20_ok`      TINYINT(1) NOT NULL DEFAULT 0,
  `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_beneficiario` (`beneficiario_id`),
  KEY `idx_mese`         (`mese`),
  KEY `idx_status`       (`status`),
  CONSTRAINT `fk_cm_ben`  FOREIGN KEY (`beneficiario_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cm_trig` FOREIGN KEY (`trigger_user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `network_passes` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    BIGINT UNSIGNED NOT NULL,
  `tipo`       ENUM('SDK+','SDP+','SDK_ROYAL','SDP_ROYAL') NOT NULL,
  `livello`    VARCHAR(30) NOT NULL,
  `prezzo_pv`  DECIMAL(18,4) NOT NULL,
  `status`     ENUM('ACTIVE','EXPIRED','CANCELLED') NOT NULL DEFAULT 'ACTIVE',
  `attivo_dal` DATE NOT NULL,
  `scade_il`   DATE DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_tipo` (`user_id`, `tipo`),
  CONSTRAINT `fk_np_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `compensi_maturati` (
  `id`                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`             BIGINT UNSIGNED NOT NULL,
  `mese`                CHAR(7) NOT NULL,
  `tipo`                ENUM('PROVVIGIONE_DIRETTA','OVERRIDE','CLUB_BONUS','FRANCHISE_BONUS','ALTRO') NOT NULL,
  `importo`             DECIMAL(18,4) NOT NULL,
  `valuta`              ENUM('PV','EURO') NOT NULL DEFAULT 'PV',
  `commissione_mlm_id`  BIGINT UNSIGNED DEFAULT NULL,
  `status`              ENUM('MATURATO','VALIDATO','PAGATO','BLOCCATO') NOT NULL DEFAULT 'MATURATO',
  `note`                VARCHAR(255) DEFAULT NULL,
  `created_at`          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_mese` (`user_id`, `mese`),
  CONSTRAINT `fk_comp_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `regola_giorno20_check` (
  `id`                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`             BIGINT UNSIGNED NOT NULL,
  `mese`                CHAR(7) NOT NULL,
  `membership_attiva`   TINYINT(1) NOT NULL DEFAULT 0,
  `pass_attivo`         TINYINT(1) NOT NULL DEFAULT 0,
  `no_blocco_admin`     TINYINT(1) NOT NULL DEFAULT 1,
  `kyc_ok`              TINYINT(1) NOT NULL DEFAULT 0,
  `iban_wallet_ok`      TINYINT(1) NOT NULL DEFAULT 0,
  `attivo_giorno20`     TINYINT(1) NOT NULL DEFAULT 0,
  `compenso_erogato`    TINYINT(1) NOT NULL DEFAULT 0,
  `compenso_tesoreria`  TINYINT(1) NOT NULL DEFAULT 0,
  `checked_at`          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_mese` (`user_id`, `mese`),
  CONSTRAINT `fk_g20_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `piano_compensi_config` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `versione`      VARCHAR(20) NOT NULL,
  `dati`          JSON NOT NULL,
  `attivo`        TINYINT(1) NOT NULL DEFAULT 1,
  `pubblicato_il` DATE NOT NULL,
  `note`          TEXT DEFAULT NULL,
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- SEZIONE 10: PRODOTTI E SERVIZI
-- ================================================================

CREATE TABLE IF NOT EXISTS `catalogo_prodotti` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sku`           VARCHAR(60) NOT NULL,
  `nome`          VARCHAR(200) NOT NULL,
  `descrizione`   TEXT DEFAULT NULL,
  `categoria`     VARCHAR(80) DEFAULT NULL,
  `tipo`          ENUM('FISICO','DIGITALE','KIT_DPI','CORSO','DOCUMENTO','SERVIZIO','PACK','TOKEN') NOT NULL,
  `prezzo_euro`   DECIMAL(12,2) NOT NULL DEFAULT 0,
  `prezzo_pv`     DECIMAL(18,4) NOT NULL DEFAULT 0,
  `pvplus_bonus`  DECIMAL(18,4) NOT NULL DEFAULT 0,
  `stock`         INT NOT NULL DEFAULT -1                        COMMENT '-1 = illimitato',
  `immagine_url`  VARCHAR(255) DEFAULT NULL,
  `fornitore`     VARCHAR(80) DEFAULT NULL,
  `attivo`        TINYINT(1) NOT NULL DEFAULT 1,
  `membership_req` VARCHAR(10) DEFAULT NULL,
  `metadata_json` JSON DEFAULT NULL,
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_sku` (`sku`),
  KEY `idx_categoria` (`categoria`),
  KEY `idx_tipo`      (`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `catalogo_servizi` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codice`         VARCHAR(60) NOT NULL,
  `nome`           VARCHAR(200) NOT NULL,
  `descrizione`    TEXT DEFAULT NULL,
  `categoria`      ENUM('SICUREZZA_81','HACCP','ISO','PRIVACY','FORMAZIONE','CONSULENZA','AUDIT','PERIZIA','ALTRO') NOT NULL,
  `prezzo_pv`      DECIMAL(12,2) NOT NULL DEFAULT 0,
  `prezzo_euro`    DECIMAL(12,2) NOT NULL DEFAULT 0,
  `durata_ore`     DECIMAL(5,1) DEFAULT NULL,
  `modalita`       ENUM('ONLINE','PRESENZIALE','IBRIDO','DIGITALE') NOT NULL DEFAULT 'ONLINE',
  `attestato`      TINYINT(1) NOT NULL DEFAULT 0,
  `membership_req` VARCHAR(10) DEFAULT NULL,
  `attivo`         TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order`     SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_codice` (`codice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `ordini` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`        BIGINT UNSIGNED DEFAULT NULL,
  `lead_id`        BIGINT UNSIGNED DEFAULT NULL,
  `order_code`     VARCHAR(40) NOT NULL,
  `tipo_item`      ENUM('PRODOTTO','SERVIZIO','MEMBERSHIP','PV_PACK','PIX81','PLP','LOCK81','ALTRO') NOT NULL,
  `item_id`        BIGINT UNSIGNED DEFAULT NULL,
  `item_codice`    VARCHAR(80) DEFAULT NULL,
  `quantita`       SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `importo_euro`   DECIMAL(12,2) NOT NULL,
  `importo_pv`     DECIMAL(18,4) NOT NULL DEFAULT 0,
  `metodo`         ENUM('REVOLUT','PAYPAL','STRIPE','BONIFICO','CRYPTO','PV','INTERNO') NOT NULL,
  `paygate_order_id` BIGINT UNSIGNED DEFAULT NULL,
  `status`         ENUM('PENDING','PAGATO','IN_LAVORAZIONE','COMPLETATO','ANNULLATO','RIMBORSATO') NOT NULL DEFAULT 'PENDING',
  `note`           TEXT DEFAULT NULL,
  `created_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_order_code` (`order_code`),
  KEY `idx_user`   (`user_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- SEZIONE 11: COMPLIANCE E AUDIT
-- ================================================================

CREATE TABLE IF NOT EXISTS `audit_sessions` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`          BIGINT UNSIGNED DEFAULT NULL,
  `lead_id`          BIGINT UNSIGNED DEFAULT NULL,
  `session_token`    VARCHAR(100) NOT NULL,
  `settore`          VARCHAR(100) DEFAULT NULL,
  `ateco`            VARCHAR(20) DEFAULT NULL,
  `dipendenti`       TINYINT UNSIGNED DEFAULT NULL,
  `haccp`            TINYINT(1) DEFAULT NULL,
  `edilizia`         TINYINT(1) DEFAULT NULL,
  `rischi`           JSON DEFAULT NULL,
  `score_sicurezza`  TINYINT UNSIGNED DEFAULT NULL,
  `score_haccp`      TINYINT UNSIGNED DEFAULT NULL,
  `score_privacy`    TINYINT UNSIGNED DEFAULT NULL,
  `score_totale`     TINYINT UNSIGNED DEFAULT NULL,
  `rischio_livello`  ENUM('BASSO','MEDIO','ALTO') DEFAULT NULL,
  `priorita`         JSON DEFAULT NULL,
  `azioni`           JSON DEFAULT NULL,
  `completato`       TINYINT(1) NOT NULL DEFAULT 0,
  `email_inviata`    TINYINT(1) NOT NULL DEFAULT 0,
  `preventivo_id`    BIGINT UNSIGNED DEFAULT NULL,
  `ip_address`       VARCHAR(45) DEFAULT NULL,
  `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_token`  (`session_token`),
  KEY `idx_user`         (`user_id`),
  KEY `idx_lead`         (`lead_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `company_compliance_asr2025` (
  `id`                    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`               BIGINT UNSIGNED NOT NULL,
  `sic_id`                VARCHAR(20) NOT NULL,
  `ragione_sociale`       VARCHAR(200) DEFAULT NULL,
  `ateco_code`            VARCHAR(10) NOT NULL,
  `macrosettore`          VARCHAR(30) NOT NULL,
  `num_lavoratori`        TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `has_soci`              TINYINT(1) NOT NULL DEFAULT 0,
  `has_datore_lavoro`     TINYINT(1) NOT NULL DEFAULT 1,
  `dl_formato`            TINYINT(1) NOT NULL DEFAULT 0,
  `dl_anni_formazione`    TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `dl_fa_rspp`            TINYINT(1) NOT NULL DEFAULT 0,
  `dl_rspp_formato`       TINYINT(1) NOT NULL DEFAULT 0,
  `ha_cantieri`           TINYINT(1) NOT NULL DEFAULT 0,
  `e_impresa_affid`       TINYINT(1) NOT NULL DEFAULT 0,
  `has_dirigenti`         TINYINT(1) NOT NULL DEFAULT 0,
  `has_preposti`          TINYINT(1) NOT NULL DEFAULT 0,
  `formazione_lav`        TINYINT(1) NOT NULL DEFAULT 0,
  `has_antincendio`       TINYINT(1) NOT NULL DEFAULT 0,
  `has_primo_soccorso`    TINYINT(1) NOT NULL DEFAULT 0,
  `has_attrezzature`      TINYINT(1) NOT NULL DEFAULT 0,
  `has_ambienti_conf`     TINYINT(1) NOT NULL DEFAULT 0,
  `dvr_presente`          TINYINT(1) NOT NULL DEFAULT 0,
  `scadenziario_presente` TINYINT(1) NOT NULL DEFAULT 0,
  `haccp_ok`              TINYINT(1) NOT NULL DEFAULT 0,
  `rischio_livello`       ENUM('BASSO','MEDIO','ALTO') NOT NULL DEFAULT 'MEDIO',
  `score`                 TINYINT UNSIGNED NOT NULL DEFAULT 50,
  `audit_completed_at`    DATETIME DEFAULT NULL,
  `created_at`            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user`          (`user_id`),
  KEY `idx_ateco`         (`ateco_code`),
  KEY `idx_macrosettore`  (`macrosettore`),
  CONSTRAINT `fk_cca_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `course_requirements_ledger` (
  `id`                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`             BIGINT UNSIGNED NOT NULL,
  `audit_id`            BIGINT UNSIGNED NOT NULL,
  `codice_corso`        VARCHAR(50) NOT NULL,
  `nome_corso`          VARCHAR(200) NOT NULL,
  `ore_totali`          TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `figura_destinataria` VARCHAR(80) NOT NULL,
  `motivo_requisito`    TEXT DEFAULT NULL,
  `stato`               ENUM('MANCANTE','IN_SCADENZA','AGGIORNAMENTO','COMPLETATO') NOT NULL DEFAULT 'MANCANTE',
  `priorita_giorni`     SMALLINT UNSIGNED DEFAULT NULL,
  `attestato_url`       VARCHAR(255) DEFAULT NULL,
  `data_completamento`  DATE DEFAULT NULL,
  `note`                TEXT DEFAULT NULL,
  `created_at`          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user`   (`user_id`),
  KEY `idx_audit`  (`audit_id`),
  KEY `idx_stato`  (`stato`),
  CONSTRAINT `fk_crl_user`  FOREIGN KEY (`user_id`)  REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_crl_audit` FOREIGN KEY (`audit_id`) REFERENCES `company_compliance_asr2025`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `preventivi` (
  `id`                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`             BIGINT UNSIGNED DEFAULT NULL,
  `lead_id`             BIGINT UNSIGNED DEFAULT NULL,
  `audit_session_id`    BIGINT UNSIGNED DEFAULT NULL,
  `ragione_sociale`     VARCHAR(200) DEFAULT NULL,
  `settore`             VARCHAR(100) DEFAULT NULL,
  `dipendenti`          TINYINT UNSIGNED DEFAULT NULL,
  `servizi_selezionati` JSON DEFAULT NULL,
  `totale_pv`           DECIMAL(18,4) DEFAULT NULL,
  `totale_euro`         DECIMAL(12,2) DEFAULT NULL,
  `pdf_path`            VARCHAR(255) DEFAULT NULL,
  `status`              ENUM('BOZZA','INVIATO','ACCETTATO','RIFIUTATO','SCADUTO') NOT NULL DEFAULT 'BOZZA',
  `valid_until`         DATE DEFAULT NULL,
  `created_at`          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user`   (`user_id`),
  KEY `idx_lead`   (`lead_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `doc81_documents` (
  `id`                   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`              BIGINT UNSIGNED NOT NULL,
  `tipo`                 VARCHAR(100) NOT NULL,
  `titolo`               VARCHAR(255) NOT NULL,
  `dati_input`           JSON DEFAULT NULL,
  `pdf_path`             VARCHAR(255) DEFAULT NULL,
  `status`               ENUM('BOZZA','GENERATO','SCARICATO','ARCHIVIATO') NOT NULL DEFAULT 'BOZZA',
  `disclaimer_accettato` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at`           DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`           DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_tipo` (`user_id`, `tipo`),
  CONSTRAINT `fk_doc_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `scadenziario` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`       BIGINT UNSIGNED NOT NULL,
  `categoria`     VARCHAR(100) NOT NULL,
  `nome`          VARCHAR(255) NOT NULL,
  `data_scadenza` DATE NOT NULL,
  `note`          TEXT DEFAULT NULL,
  `completato`    TINYINT(1) NOT NULL DEFAULT 0,
  `completato_at` DATETIME DEFAULT NULL,
  `notifica_90g`  TINYINT(1) NOT NULL DEFAULT 0,
  `notifica_30g`  TINYINT(1) NOT NULL DEFAULT 0,
  `notifica_7g`   TINYINT(1) NOT NULL DEFAULT 0,
  `notifica_0g`   TINYINT(1) NOT NULL DEFAULT 0,
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_data`  (`user_id`, `data_scadenza`),
  KEY `idx_completato` (`completato`),
  CONSTRAINT `fk_sca_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- SEZIONE 12: ACADEMY E FORMAZIONE
-- ================================================================

CREATE TABLE IF NOT EXISTS `academy_access` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     BIGINT UNSIGNED NOT NULL,
  `livello`     ENUM('CORE','PRO_SKILLS','ELITE_MASTERY') NOT NULL DEFAULT 'CORE',
  `sbloccato_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `motivo`      VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_livello` (`user_id`, `livello`),
  CONSTRAINT `fk_aa_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `academy_corsi` (
  `id`                    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `livello`               ENUM('CORE','PRO_SKILLS','ELITE_MASTERY') NOT NULL,
  `titolo`                VARCHAR(200) NOT NULL,
  `descrizione`           TEXT DEFAULT NULL,
  `partner_interno`       VARCHAR(100) DEFAULT NULL               COMMENT 'MAI mostrare lato utente',
  `link_partner`          VARCHAR(255) DEFAULT NULL               COMMENT 'redirect server-side — MAI esporre',
  `pvplus_completamento`  DECIMAL(18,4) NOT NULL DEFAULT 0,
  `durata_ore`            DECIMAL(5,2) DEFAULT NULL,
  `attestato_incluso`     TINYINT(1) NOT NULL DEFAULT 0,
  `attivo`                TINYINT(1) NOT NULL DEFAULT 1,
  `ordine`                SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at`            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `academy_completamenti` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`        BIGINT UNSIGNED NOT NULL,
  `corso_id`       BIGINT UNSIGNED NOT NULL,
  `completato_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pvplus_erogati` DECIMAL(18,4) NOT NULL DEFAULT 0,
  `attestato_url`  VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_corso` (`user_id`, `corso_id`),
  CONSTRAINT `fk_ac_user`  FOREIGN KEY (`user_id`)  REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ac_corso` FOREIGN KEY (`corso_id`) REFERENCES `academy_corsi`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `academy_iscrizioni` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`       BIGINT UNSIGNED NOT NULL,
  `corso_id`      VARCHAR(60) NOT NULL,
  `fornitore`     ENUM('anfos','lezione-online','other') NOT NULL DEFAULT 'anfos',
  `titolo`        VARCHAR(191) NOT NULL,
  `prezzo_euro`   DECIMAL(8,2) NOT NULL DEFAULT 0,
  `commissione`   DECIMAL(6,2) NOT NULL DEFAULT 0                COMMENT '% commissione su prezzo',
  `stato`         ENUM('in_corso','completato','certificato') NOT NULL DEFAULT 'in_corso',
  `attestato_url` TEXT DEFAULT NULL,
  `completato_il` DATE DEFAULT NULL,
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user`      (`user_id`),
  KEY `idx_fornitore` (`fornitore`),
  CONSTRAINT `fk_ai_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- SEZIONE 13: SCOUT81+ E PLP
-- ================================================================

CREATE TABLE IF NOT EXISTS `scout81_prospects` (
  `id`                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ragione_sociale`     VARCHAR(255) NOT NULL,
  `ateco`               VARCHAR(20) DEFAULT NULL,
  `macrosettore`        VARCHAR(100) DEFAULT NULL,
  `rischio`             ENUM('BASSO','MEDIO','ALTO') NOT NULL DEFAULT 'MEDIO',
  `haccp_applicabile`   TINYINT(1) NOT NULL DEFAULT 0,
  `edilizia`            TINYINT(1) NOT NULL DEFAULT 0,
  `dipendenti_stimati`  SMALLINT UNSIGNED DEFAULT NULL,
  `indirizzo`           VARCHAR(255) DEFAULT NULL,
  `comune`              VARCHAR(100) DEFAULT NULL,
  `provincia`           CHAR(2) DEFAULT NULL,
  `regione`             VARCHAR(60) DEFAULT NULL,
  `cap`                 CHAR(5) DEFAULT NULL,
  `telefono`            VARCHAR(30) DEFAULT NULL,
  `email_azienda`       VARCHAR(255) DEFAULT NULL,
  `sito_web`            VARCHAR(255) DEFAULT NULL,
  `lead_storico`        TINYINT(1) NOT NULL DEFAULT 0,
  `score`               TINYINT UNSIGNED NOT NULL DEFAULT 50,
  `priorita`            ENUM('ALTA','MEDIA','BASSA') NOT NULL DEFAULT 'MEDIA',
  `provider_origine`    VARCHAR(50) DEFAULT NULL,
  `data_importazione`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ultimo_aggiornamento` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ateco`       (`ateco`),
  KEY `idx_provincia`   (`provincia`),
  KEY `idx_regione`     (`regione`),
  KEY `idx_rischio`     (`rischio`),
  KEY `idx_score`       (`score`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `scout81_assignments` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `prospect_id`     BIGINT UNSIGNED NOT NULL,
  `networker_id`    BIGINT UNSIGNED NOT NULL,
  `plp_order_id`    BIGINT UNSIGNED DEFAULT NULL,
  `status`          ENUM('NUOVO','IN_LAVORAZIONE','FOLLOW_UP','AUDIT_FATTO','PREVENTIVO','CONVERTITO','PERSO','DA_RIATTIVARE') NOT NULL DEFAULT 'NUOVO',
  `note`            TEXT DEFAULT NULL,
  `script_usato`    VARCHAR(100) DEFAULT NULL,
  `prossimo_followup` DATE DEFAULT NULL,
  `assegnato_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_networker` (`networker_id`, `status`),
  CONSTRAINT `fk_sa_prospect`  FOREIGN KEY (`prospect_id`)  REFERENCES `scout81_prospects`(`id`),
  CONSTRAINT `fk_sa_networker` FOREIGN KEY (`networker_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `scout81_saved_filters` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    BIGINT UNSIGNED NOT NULL,
  `nome`       VARCHAR(100) NOT NULL,
  `filtri`     JSON NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ssf_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `scout81_providers` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome`           VARCHAR(100) NOT NULL,
  `codice`         VARCHAR(50) NOT NULL,
  `attivo`         TINYINT(1) NOT NULL DEFAULT 0,
  `endpoint_url`   VARCHAR(255) DEFAULT NULL                     COMMENT 'key in .env, MAI qui',
  `quota_mensile`  INT UNSIGNED DEFAULT NULL,
  `quota_usata`    INT UNSIGNED NOT NULL DEFAULT 0,
  `note`           TEXT DEFAULT NULL,
  `created_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_codice` (`codice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `scout81_providers` (`nome`,`codice`,`attivo`) VALUES
('Outscraper','outscraper',0),
('Database Interno SICURISSIMO81+','interno',1),
('Provider Futuro 1','futuro1',0);

CREATE TABLE IF NOT EXISTS `plp_packs_catalog` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codice`         VARCHAR(50) NOT NULL,
  `nome`           VARCHAR(100) NOT NULL,
  `descrizione`    TEXT DEFAULT NULL,
  `prospect_count` SMALLINT UNSIGNED NOT NULL,
  `prezzo_pv`      DECIMAL(18,4) NOT NULL,
  `pvplus_bonus`   DECIMAL(18,4) NOT NULL DEFAULT 0,
  `composizione`   JSON DEFAULT NULL,
  `filtri_preset`  JSON DEFAULT NULL,
  `attivo`         TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_codice` (`codice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `plp_packs_catalog` (`codice`,`nome`,`prospect_count`,`prezzo_pv`,`pvplus_bonus`) VALUES
('PLP_START',           'PLP Start',           100, 29,  100),
('PLP_PRO',             'PLP Pro',             250, 49,  250),
('PLP_MAX',             'PLP Max',             500, 99,  500),
('PLP_EDILIZIA',        'PLP Edilizia',        100, 39,  100),
('PLP_HACCP',           'PLP HACCP',           100, 39,  100),
('PLP_PROFESSIONISTI',  'PLP Professionisti',  100, 35,  100),
('PLP_ARTIGIANI',       'PLP Artigiani',       100, 35,  100),
('PLP_TERRITORIO',      'PLP Territorio',      200, 59,  200),
('PLP_GENESYS_SPECIAL', 'PLP GENESYS Special', 250, 0,   500),
('PLP_FOLLOWUP_STORICI','PLP Follow-up Storici',200, 49, 200);

CREATE TABLE IF NOT EXISTS `plp_orders` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`         BIGINT UNSIGNED NOT NULL,
  `pack_id`         BIGINT UNSIGNED NOT NULL,
  `pv_scalati`      DECIMAL(18,4) NOT NULL,
  `pvplus_erogati`  DECIMAL(18,4) NOT NULL DEFAULT 0,
  `prospect_count`  SMALLINT UNSIGNED NOT NULL,
  `status`          ENUM('PENDING','ASSEGNATO','COMPLETATO','FALLITO') NOT NULL DEFAULT 'PENDING',
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  CONSTRAINT `fk_plpo_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_plpo_pack` FOREIGN KEY (`pack_id`) REFERENCES `plp_packs_catalog`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `materiali_networker` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tipo`           ENUM('SOCIAL','WEBINAR','SCRIPT','PDF','VIDEO','ALTRO') NOT NULL,
  `titolo`         VARCHAR(255) NOT NULL,
  `descrizione`    TEXT DEFAULT NULL,
  `file_path`      VARCHAR(255) DEFAULT NULL,
  `link_esterno`   VARCHAR(255) DEFAULT NULL,
  `livello_minimo` ENUM('NETWORKER81','ELITE81','ADMIN81') NOT NULL DEFAULT 'NETWORKER81',
  `attivo`         TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- SEZIONE 14: TERRITORY, CLUB, FRANCHISING
-- ================================================================

CREATE TABLE IF NOT EXISTS `pipeline_relations` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `upline_id`    BIGINT UNSIGNED NOT NULL,
  `downline_id`  BIGINT UNSIGNED NOT NULL,
  `profondita`   TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_downline` (`downline_id`),
  KEY `idx_upline` (`upline_id`),
  CONSTRAINT `fk_pr_up`   FOREIGN KEY (`upline_id`)   REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pr_down` FOREIGN KEY (`downline_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `territory_areas` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tipo`         ENUM('REGIONE','PROVINCIA','COMUNE') NOT NULL,
  `nome`         VARCHAR(100) NOT NULL,
  `codice`       VARCHAR(20) NOT NULL,
  `regione_ref`  VARCHAR(60) DEFAULT NULL,
  `provincia_ref` VARCHAR(60) DEFAULT NULL,
  `status`       ENUM('LIBERA','RISERVATA','ASSEGNATA','SOSPESA') NOT NULL DEFAULT 'LIBERA',
  `titolare_id`  BIGINT UNSIGNED DEFAULT NULL,
  `point81_attivo` TINYINT(1) NOT NULL DEFAULT 0,
  `note`         TEXT DEFAULT NULL,
  `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_codice` (`codice`),
  KEY `idx_tipo_status` (`tipo`, `status`),
  CONSTRAINT `fk_ta_titolare` FOREIGN KEY (`titolare_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `club81_membership` (
  `id`                    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`               BIGINT UNSIGNED NOT NULL,
  `livello`               ENUM('PALLADIUM','IRIDIUM','RHODIUM','OSMIUM','RHENIUM') NOT NULL,
  `status`                ENUM('ACTIVE','INACTIVE','PENDING') NOT NULL DEFAULT 'PENDING',
  `pv_mensili_richiesti`  DECIMAL(18,4) NOT NULL,
  `attivo_dal`            DATE DEFAULT NULL,
  `scade_il`              DATE DEFAULT NULL,
  `created_at`            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user` (`user_id`),
  CONSTRAINT `fk_club_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `franchising_applications` (
  `id`                   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`              BIGINT UNSIGNED NOT NULL,
  `tipo`                 ENUM('SATELLITE81+','STATION81+','COMMAND81+','SEDE_PROVINCIALE','SEDE_REGIONALE') NOT NULL,
  `area_id`              BIGINT UNSIGNED DEFAULT NULL,
  `status`               ENUM('PENDING','APPROVATA','RIFIUTATA','ATTIVA','SOSPESA') NOT NULL DEFAULT 'PENDING',
  `prezzo_euro`          DECIMAL(18,2) DEFAULT NULL,
  `canone_mensile_euro`  DECIMAL(18,2) DEFAULT NULL,
  `pv_bonus_start`       DECIMAL(18,4) DEFAULT NULL,
  `pv_bonus_mensile`     DECIMAL(18,4) DEFAULT NULL,
  `note_admin`           TEXT DEFAULT NULL,
  `created_at`           DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_fa_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fa_area` FOREIGN KEY (`area_id`)  REFERENCES `territory_areas`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- SEZIONE 15: WEB3, TOKEN 81X, AIRDROP, GREEN
-- ================================================================

CREATE TABLE IF NOT EXISTS `x81_config` (
  `chiave`  VARCHAR(60) NOT NULL,
  `valore`  TEXT NOT NULL,
  `note`    VARCHAR(191) DEFAULT NULL,
  PRIMARY KEY (`chiave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `x81_config` (`chiave`,`valore`,`note`) VALUES
('supply_totale',  '21000000', 'Supply totale 81X — fisso e immutabile'),
('genesi_tranche', '5000000',  'Tranche genesi per airdrop e promo private'),
('prezzo_genesi',  '0.10',     'USDT per token in fase genesi'),
('burn_pct',       '0.5',      'Burn percentuale per trasferimento 81X'),
('halving_anni',   '4',        'Anni tra un halving e il successivo'),
('rete_principale','BSC',      'Rete blockchain principale BEP-20');

CREATE TABLE IF NOT EXISTS `x81_airdrop` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`         BIGINT UNSIGNED NOT NULL,
  `sic_id`          VARCHAR(20) NOT NULL,
  `step`            TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `token_maturati`  DECIMAL(18,8) NOT NULL DEFAULT 0,
  `kyc_ok`          TINYINT(1) NOT NULL DEFAULT 0,
  `erogato`         TINYINT(1) NOT NULL DEFAULT 0,
  `tx_hash`         VARCHAR(66) DEFAULT NULL,
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_sic_step` (`sic_id`, `step`),
  KEY `idx_erogato` (`erogato`),
  CONSTRAINT `fk_air_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `green81_alberi` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`         BIGINT UNSIGNED NOT NULL,
  `tipo_origine`    ENUM('PV_ACQUISTO','PIX81','MISSIONE','LOCK81','ADMIN') NOT NULL,
  `riferimento`     VARCHAR(100) DEFAULT NULL,
  `specie`          VARCHAR(100) DEFAULT 'Quercia',
  `localita`        VARCHAR(200) DEFAULT NULL,
  `piantato_il`     DATE DEFAULT NULL,
  `certificato_url` VARCHAR(255) DEFAULT NULL,
  `dao_approvato`   TINYINT(1) NOT NULL DEFAULT 0,
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  CONSTRAINT `fk_ga_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `nft_assets` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     BIGINT UNSIGNED NOT NULL,
  `token_id`    VARCHAR(100) DEFAULT NULL                       COMMENT 'NFT token ID on chain',
  `contratto`   VARCHAR(100) DEFAULT NULL                       COMMENT 'Smart contract address',
  `rete`        ENUM('BSC','ETH','POLYGON') NOT NULL DEFAULT 'BSC',
  `tipo`        VARCHAR(60) NOT NULL                            COMMENT 'PIX81, GENESYS, ATTESTATO, SIGILLO',
  `metadata_uri` VARCHAR(255) DEFAULT NULL,
  `metadata_json` JSON DEFAULT NULL,
  `tx_mint`     VARCHAR(100) DEFAULT NULL,
  `stato`       ENUM('PENDING','MINTED','BURNED','TRANSFERRED') NOT NULL DEFAULT 'PENDING',
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user`  (`user_id`),
  KEY `idx_tipo`  (`tipo`),
  CONSTRAINT `fk_nft_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `blockchain_transactions` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     BIGINT UNSIGNED DEFAULT NULL,
  `rete`        ENUM('BSC','ETH','TRX','SOL','MATIC','BTC') NOT NULL DEFAULT 'BSC',
  `token`       VARCHAR(20) NOT NULL DEFAULT '81X',
  `tx_hash`     VARCHAR(100) DEFAULT NULL,
  `from_address` VARCHAR(100) DEFAULT NULL,
  `to_address`   VARCHAR(100) DEFAULT NULL,
  `importo`     DECIMAL(18,8) NOT NULL,
  `tipo`        ENUM('ACQUISTO','AIRDROP','BURN','TRANSFER','MINT','STAKING_REWARD') NOT NULL,
  `confermato`  TINYINT(1) NOT NULL DEFAULT 0,
  `blocchi`     SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `raw_data`    JSON DEFAULT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_tx_hash` (`tx_hash`),
  KEY `idx_user`   (`user_id`),
  KEY `idx_rete`   (`rete`),
  KEY `idx_tipo`   (`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- SEZIONE 16: COMUNICAZIONE, CHAT, NEWSLETTER
-- ================================================================

CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email`      VARCHAR(190) NOT NULL,
  `nome`       VARCHAR(120) DEFAULT NULL,
  `user_id`    BIGINT UNSIGNED DEFAULT NULL,
  `source`     VARCHAR(120) DEFAULT NULL,
  `liste`      JSON DEFAULT NULL                                 COMMENT 'array ID liste Brevo',
  `active`     TINYINT(1) NOT NULL DEFAULT 1,
  `unsubscribed_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `eventi` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    BIGINT UNSIGNED DEFAULT NULL,
  `lead_id`    BIGINT UNSIGNED DEFAULT NULL,
  `tipo`       VARCHAR(80) NOT NULL                              COMMENT 'login|signup|acquisto|audit|click|...',
  `payload`    JSON DEFAULT NULL,
  `ip`         VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(300) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user`    (`user_id`),
  KEY `idx_tipo`    (`tipo`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `chats` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id`     VARCHAR(80) NOT NULL,
  `user_id`        BIGINT UNSIGNED DEFAULT NULL,
  `lead_id`        BIGINT UNSIGNED DEFAULT NULL,
  `pianeta`        VARCHAR(60) NOT NULL DEFAULT 'hub',
  `ruolo`          ENUM('user','bot','lead','system') NOT NULL,
  `agente`         VARCHAR(50) DEFAULT NULL                      COMMENT 'slug agente AI',
  `testo`          TEXT NOT NULL,
  `lead_nome`      VARCHAR(150) DEFAULT NULL,
  `lead_email`     VARCHAR(190) DEFAULT NULL,
  `lead_telefono`  VARCHAR(30) DEFAULT NULL,
  `created_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_session`  (`session_id`),
  KEY `idx_pianeta`  (`pianeta`),
  KEY `idx_agente`   (`agente`),
  KEY `idx_created`  (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `notifiche` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     BIGINT UNSIGNED DEFAULT NULL,
  `canale`      ENUM('EMAIL','WHATSAPP','TELEGRAM','PUSH','SMS') NOT NULL,
  `tipo`        VARCHAR(60) NOT NULL,
  `titolo`      VARCHAR(200) DEFAULT NULL,
  `corpo`       TEXT NOT NULL,
  `status`      ENUM('PENDING','SENT','FAILED','SCHEDULED') NOT NULL DEFAULT 'PENDING',
  `schedulato_il` DATETIME DEFAULT NULL,
  `inviato_il`  DATETIME DEFAULT NULL,
  `errore`      TEXT DEFAULT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user`      (`user_id`),
  KEY `idx_status`    (`status`),
  KEY `idx_schedulato`(`schedulato_il`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `recensioni` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome`             VARCHAR(100) NOT NULL,
  `iniziale_cognome` CHAR(1) NOT NULL,
  `azienda`          VARCHAR(200) DEFAULT NULL,
  `comune`           VARCHAR(100) DEFAULT NULL,
  `provincia`        CHAR(2) DEFAULT NULL,
  `regione`          VARCHAR(60) DEFAULT NULL,
  `settore`          VARCHAR(100) DEFAULT NULL,
  `ateco`            VARCHAR(20) DEFAULT NULL,
  `rischio`          ENUM('BASSO','MEDIO','ALTO') DEFAULT NULL,
  `stelle`           TINYINT UNSIGNED NOT NULL,
  `testo`            TEXT NOT NULL,
  `servizio`         VARCHAR(100) DEFAULT NULL,
  `data_recensione`  DATE NOT NULL,
  `fonte`            ENUM('TRUSTPILOT','DIRETTA','SICURISSIMO81') NOT NULL DEFAULT 'SICURISSIMO81',
  `verificata`       TINYINT(1) NOT NULL DEFAULT 1,
  `attiva`           TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_stelle`  (`stelle`),
  KEY `idx_regione` (`regione`),
  KEY `idx_ateco`   (`ateco`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- SEZIONE 17: DASHBOARD E ADMIN
-- ================================================================

CREATE TABLE IF NOT EXISTS `dashboard_modules` (
  `id`             SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codice`         VARCHAR(40) NOT NULL,
  `nome`           VARCHAR(80) NOT NULL,
  `descrizione`    TEXT DEFAULT NULL,
  `icona`          VARCHAR(10) DEFAULT NULL,
  `url_target`     VARCHAR(255) DEFAULT NULL,
  `ruoli`          JSON NOT NULL,
  `genesys_req`    VARCHAR(30) DEFAULT NULL,
  `membership_req` VARCHAR(10) DEFAULT NULL,
  `sort_order`     SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `is_preview`     TINYINT(1) NOT NULL DEFAULT 0,
  `is_active`      TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_codice` (`codice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `dashboard_modules`
  (`codice`,`nome`,`icona`,`url_target`,`ruoli`,`membership_req`,`sort_order`,`is_preview`) VALUES
('sic_id',       'SIC-ID',            '🆔', NULL,                    '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,    1,  0),
('referral',     'ReferralLink81+',   '🔗', '/dashboard.php#referral','["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,    2,  0),
('wallet',       'Wallet81+',         '💼', '/dashboard.php#wallet', '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,    3,  0),
('membership',   'Membership',        '🏆', '/membership.php',       '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,    4,  0),
('paygate',      'PayGate81+',        '💳', '/paygate81.php',        '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,    5,  0),
('audit',        'Audit 81/08',       '🔍', '/audit.php',            '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,    6,  0),
('preventivo',   'DOC81+ Builder',    '📄', '/preventivo.php',       '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', 'BASIC+', 7, 1),
('academy',      'Academy 81+',       '🎓', '/academy81.php',        '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', 'BASIC+', 8, 1),
('scadenziario', 'Scadenziario81+',   '📅', '/scadenze.php',         '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', 'BASIC+', 9, 1),
('pvplus_boost', 'PV+ Booster81+',    '⚡', '/gamification.php',     '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,    10, 0),
('pix81',        'PIX81+',            '🗺️', '/pix81.php',            '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,    11, 1),
('green81',      'Green81+',          '🌳', '/green81.php',          '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,    12, 1),
('scout81',      'SCOUT81+',          '🎯', '/scout81.php',          '["NETWORKER81","ELITE81","ADMIN81"]',            'PRO+',  20, 1),
('plp81',        'PLP81+ Pack',       '📦', '/network81.php#plp',    '["NETWORKER81","ELITE81","ADMIN81"]',            'PRO+',  21, 1),
('network81',    'NETWORK81+',        '🕸️', '/network81.php',        '["NETWORKER81","ELITE81","ADMIN81"]',            'PRO+',  22, 1),
('pipeline3d',   'Pipeline3D81+',     '📊', '/ecosistema3d.php',     '["NETWORKER81","ELITE81","ADMIN81"]',            'PRO+',  23, 1),
('compensi',     'Piano Compensi81+', '💹', '/cervello3d.php',       '["NETWORKER81","ELITE81","ADMIN81"]',            'PRO+',  24, 1),
('club81',       'Club81+',           '👑', '/club81.php',           '["ELITE81","ADMIN81"]',                          'ELITE+',30, 1),
('franchising',  'Franchising81+',    '🏢', '/franchising.php',      '["ELITE81","ADMIN81"]',                          'ELITE+',31, 1),
('territory',    'TerritoryMap81+',   '🗺️', '/scout81.php#territory','["ELITE81","ADMIN81"]',                          'ELITE+',32, 1),
('web3',         'Web3 81+',          '🔗', '/web3.php',             '["MEMBER81","NETWORKER81","ELITE81","ADMIN81"]', NULL,    13, 1),
('admin_center', 'Admin Command',     '⚙️', '/admin.php',            '["ADMIN81"]',                                    NULL,   40, 0);

CREATE TABLE IF NOT EXISTS `admin_action_log` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id`        BIGINT UNSIGNED NOT NULL,
  `target_user_id`  BIGINT UNSIGNED DEFAULT NULL,
  `azione`          VARCHAR(100) NOT NULL,
  `parametri`       JSON DEFAULT NULL,
  `note`            TEXT DEFAULT NULL,
  `ip_address`      VARCHAR(45) DEFAULT NULL,
  `user_agent`      VARCHAR(300) DEFAULT NULL,
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_admin`   (`admin_id`),
  KEY `idx_target`  (`target_user_id`),
  KEY `idx_azione`  (`azione`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `system_logs` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `livello`    ENUM('DEBUG','INFO','WARNING','ERROR','CRITICAL') NOT NULL DEFAULT 'INFO',
  `contesto`   VARCHAR(80) DEFAULT NULL,
  `messaggio`  TEXT NOT NULL,
  `data_json`  JSON DEFAULT NULL,
  `user_id`    BIGINT UNSIGNED DEFAULT NULL,
  `ip`         VARCHAR(45) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_livello`  (`livello`),
  KEY `idx_contesto` (`contesto`),
  KEY `idx_created`  (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- SEZIONE 18: DEVELOPER API
-- ================================================================

CREATE TABLE IF NOT EXISTS `developer_api` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     BIGINT UNSIGNED NOT NULL,
  `api_key`     VARCHAR(64) NOT NULL,
  `tier`        ENUM('small','medium','large','enterprise') NOT NULL DEFAULT 'small',
  `calls_oggi`  INT UNSIGNED NOT NULL DEFAULT 0,
  `calls_mese`  INT UNSIGNED NOT NULL DEFAULT 0,
  `quota_giorno` INT UNSIGNED NOT NULL DEFAULT 1000,
  `quota_mese`  INT UNSIGNED NOT NULL DEFAULT 10000,
  `ip_whitelist` JSON DEFAULT NULL,
  `attivo`      TINYINT(1) NOT NULL DEFAULT 1,
  `scade_il`    DATE DEFAULT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_api_key` (`api_key`),
  KEY `idx_user` (`user_id`),
  CONSTRAINT `fk_dev_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- SEZIONE 19: AI AGENTS — LOG E MEMORIA
-- ================================================================

CREATE TABLE IF NOT EXISTS `agent_actions` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `agent_slug`      VARCHAR(60) NOT NULL,
  `agent_hub`       VARCHAR(10) DEFAULT NULL                    COMMENT 'HUB1|HUB2|HUB3|PCO|FIN',
  `trigger_type`    VARCHAR(80) DEFAULT NULL,
  `user_id`         BIGINT UNSIGNED DEFAULT NULL,
  `lead_id`         BIGINT UNSIGNED DEFAULT NULL,
  `payload_hash`    CHAR(64) DEFAULT NULL,
  `result_status`   ENUM('success','fail','pending_confirm','blocked','skipped') NOT NULL,
  `result_summary`  VARCHAR(500) DEFAULT NULL,
  `approval_phrase` VARCHAR(60) DEFAULT NULL                   COMMENT 'CONFERMO INVIO / CONFERMO PUBBLICA / CONFERMO AZIONE CRITICA',
  `duration_ms`     INT UNSIGNED DEFAULT NULL,
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_agent`    (`agent_slug`),
  KEY `idx_hub`      (`agent_hub`),
  KEY `idx_status`   (`result_status`),
  KEY `idx_created`  (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `semantic_guard_log` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     BIGINT UNSIGNED DEFAULT NULL,
  `agent_slug`  VARCHAR(60) DEFAULT NULL,
  `contesto`    VARCHAR(100) DEFAULT NULL,
  `parole`      TEXT NOT NULL,
  `azione`      ENUM('BLOCK','WARN','REPLACE') NOT NULL DEFAULT 'BLOCK',
  `ip_hash`     VARCHAR(64) DEFAULT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_created` (`created_at`),
  KEY `idx_azione`  (`azione`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `knowledge_base` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category`    VARCHAR(60) NOT NULL,
  `key_name`    VARCHAR(120) NOT NULL,
  `value`       LONGTEXT DEFAULT NULL,
  `source`      VARCHAR(100) DEFAULT NULL,
  `version`     SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cat_key` (`category`, `key_name`),
  KEY `idx_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `knowledge_base` (`category`,`key_name`,`value`,`source`) VALUES
('brand',    'nome_sistema',    '81+ OS',                       'CLAUDE.md'),
('brand',    'hub1',            '81plus.net — identità, SIC-ID, membership', 'CLAUDE.md'),
('brand',    'hub2',            'SICURISSIMO — compliance Web2', 'CLAUDE.md'),
('brand',    'hub3',            '81plus.online — Web3/utility', 'CLAUDE.md'),
('normativa','asr_2025',        'Accordo Stato-Regioni Rep. Atti n. 59/CSR del 17 aprile 2025', 'ASR_2025_ENGINE'),
('sicurezza','pv_definizione',  'Credito interno 1:1 euro per acquisti nel sistema. NON moneta elettronica. NON riscattabile.', 'CLAUDE.md'),
('sicurezza','lock81_definizione','Programma fedeltà interno 180gg. NON è staking. NON è rendimento. NON è APY.', 'CLAUDE.md'),
('sicurezza','genesys_max',     '81 slot massimi. Max 5 per utente. Primi 20 max 10. NON investimento.', 'CLAUDE.md');

-- ================================================================
-- SEZIONE 20: PROFESSIONISTI E ASSET FISICI
-- ================================================================

CREATE TABLE IF NOT EXISTS `professionals` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome`             VARCHAR(150) NOT NULL,
  `ruolo`            ENUM('RSPP','ASPP','MEDICO_COMPETENTE','FORMATORE','CONSULENTE','RLS','CSP','CSE') NOT NULL,
  `zona`             VARCHAR(100) DEFAULT NULL,
  `province_servite` JSON DEFAULT NULL,
  `specializzazioni` JSON DEFAULT NULL,
  `email`            VARCHAR(190) DEFAULT NULL,
  `telefono`         VARCHAR(30) DEFAULT NULL,
  `piva`             VARCHAR(20) DEFAULT NULL,
  `user_id`          BIGINT UNSIGNED DEFAULT NULL                COMMENT 'se registrato nel sistema',
  `validazioni_mese` INT NOT NULL DEFAULT 0,
  `stato`            ENUM('attivo','carico_alto','non_disponibile') NOT NULL DEFAULT 'attivo',
  `note`             TEXT DEFAULT NULL,
  `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_ruolo` (`ruolo`),
  KEY `idx_zona`  (`zona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `assets` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     BIGINT UNSIGNED NOT NULL,
  `tipo`        VARCHAR(60) NOT NULL                            COMMENT 'QR_SIGILLO|CERTIFICATO|NFT|KIT_DPI|DOCUMENTO',
  `nome`        VARCHAR(200) NOT NULL,
  `qr_code`     VARCHAR(300) DEFAULT NULL,
  `nft_hash`    VARCHAR(200) DEFAULT NULL,
  `file_url`    VARCHAR(255) DEFAULT NULL,
  `stato`       ENUM('attivo','scaduto','revocato','trasferito') NOT NULL DEFAULT 'attivo',
  `metadata`    JSON DEFAULT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_tipo` (`tipo`),
  CONSTRAINT `fk_ass_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- TRIGGERS — AUTOMAZIONI POST-REGISTRAZIONE
-- ================================================================

DELIMITER $$

DROP TRIGGER IF EXISTS `trg_create_wallet`$$
CREATE TRIGGER `trg_create_wallet`
AFTER INSERT ON `users`
FOR EACH ROW
BEGIN
  INSERT IGNORE INTO `wallets` (`user_id`) VALUES (NEW.id);
END$$

DROP TRIGGER IF EXISTS `trg_create_referral_link`$$
CREATE TRIGGER `trg_create_referral_link`
AFTER INSERT ON `users`
FOR EACH ROW
BEGIN
  INSERT IGNORE INTO `referral_links` (`user_id`, `sic_id_ref`)
  VALUES (NEW.id, NEW.sic_id);
END$$

DROP TRIGGER IF EXISTS `trg_welcome_pvplus`$$
CREATE TRIGGER `trg_welcome_pvplus`
AFTER INSERT ON `users`
FOR EACH ROW
BEGIN
  INSERT IGNORE INTO `pvplus_claims`
    (`user_id`, `codice_missione`, `pvplus_base`, `moltiplicatore`, `pvplus_finali`, `riferimento`)
  VALUES
    (NEW.id, 'WELCOME', 100.00, 1.00, 100.00, NEW.sic_id);
  UPDATE `wallets` SET `pvplus_balance` = `pvplus_balance` + 100.00 WHERE `user_id` = NEW.id;
END$$

DROP TRIGGER IF EXISTS `trg_log_payment_event`$$
CREATE TRIGGER `trg_log_payment_event`
AFTER UPDATE ON `paygate_orders`
FOR EACH ROW
BEGIN
  IF NEW.status = 'COMPLETED' AND OLD.status != 'COMPLETED' THEN
    INSERT INTO `eventi` (`user_id`, `tipo`, `payload`)
    VALUES (NEW.user_id, 'acquisto_completato',
      JSON_OBJECT('order_id', NEW.id, 'tipo', NEW.tipo, 'importo_euro', NEW.importo_euro, 'metodo', NEW.metodo));
  END IF;
END$$

DELIMITER ;

-- ================================================================
-- VIEWS
-- ================================================================

CREATE OR REPLACE VIEW `v_users_full` AS
  SELECT
    u.id, u.sic_id, u.email, u.nome, u.cognome, u.telefono,
    u.ruolo, u.status, u.membership_tier, u.genesys_status,
    u.career_level, u.kyc_status, u.codice_ateco, u.provincia,
    up.ragione_sociale, up.piva, up.sito_web,
    w.pv_balance, w.pvplus_balance, w.saf_balance, w.x81_balance,
    u.created_at
  FROM `users` u
  LEFT JOIN `user_profiles` up ON u.id = up.user_id
  LEFT JOIN `wallets` w ON u.id = w.user_id;

CREATE OR REPLACE VIEW `v_leads_dashboard` AS
  SELECT
    l.id, l.sic_id, l.nome, l.email, l.telefono,
    l.stato, l.score, l.fonte, l.provincia,
    l.email_benvenuto_inviata, l.whatsapp_inviato, l.audit_fatto,
    l.next_followup, l.created_at,
    COUNT(li.id) AS interazioni_totali
  FROM `leads` l
  LEFT JOIN `lead_interactions` li ON l.id = li.lead_id
  GROUP BY l.id;

CREATE OR REPLACE VIEW `v_payments_summary` AS
  SELECT user_id, 'REVOLUT' AS metodo, importo_euro, status, created_at FROM pagamenti_revolut
  UNION ALL
  SELECT user_id, 'PAYPAL',  importo_euro, status, created_at FROM pagamenti_paypal
  UNION ALL
  SELECT user_id, 'STRIPE',  importo_euro, status, created_at FROM pagamenti_stripe
  UNION ALL
  SELECT user_id, 'BONIFICO',importo_euro, status, created_at FROM pagamenti_bonifico
  UNION ALL
  SELECT user_id, 'CRYPTO',  importo_euro_equiv, status, created_at FROM pagamenti_crypto;

SET FOREIGN_KEY_CHECKS = 1;

-- ================================================================
-- FINE MASTER SCHEMA 81+ — 78 TABELLE | 4 TRIGGER | 3 VIEW
-- ================================================================
