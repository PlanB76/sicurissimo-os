-- ============================================================
-- 001_auth_core.sql · SIC-ID Core Tables
-- 81+ Ecosystem · DB: u173050672_81plusglobal
-- Run once on Hostinger MySQL 8.0+
-- ============================================================

SET NAMES utf8mb4;
SET foreign_key_checks = 0;

-- ── USERS ──
CREATE TABLE IF NOT EXISTS `users` (
  `id`            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `sic_id`        VARCHAR(20)     NOT NULL COMMENT 'SIC-XXXXXXXX-XXXXXX format',
  `email`         VARCHAR(180)    NOT NULL,
  `nome`          VARCHAR(80)     NOT NULL,
  `cognome`       VARCHAR(80)     NOT NULL,
  `settore`       ENUM('manifatturiero','food','cantieri','uffici','commercio','altro')
                                  NOT NULL DEFAULT 'altro',
  `piva`          VARCHAR(20)     NOT NULL DEFAULT '',
  `password_hash` VARCHAR(255)    NOT NULL,
  `pv`            MEDIUMINT       NOT NULL DEFAULT 0 COMMENT 'PV Web2 balance',
  `is_active`     TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`    DATETIME        NOT NULL,
  `updated_at`    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_email`  (`email`),
  UNIQUE KEY `uk_sic_id` (`sic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── REFRESH TOKENS ──
CREATE TABLE IF NOT EXISTS `refresh_tokens` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     INT UNSIGNED NOT NULL,
  `token_hash`  CHAR(64)     NOT NULL COMMENT 'SHA-256 of the raw token',
  `expires_at`  DATETIME     NOT NULL,
  `created_at`  DATETIME     NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_token_hash` (`token_hash`),
  KEY `idx_user_id` (`user_id`),
  CONSTRAINT `fk_rt_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── RATE LIMIT ──
CREATE TABLE IF NOT EXISTS `rate_limit` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key_hash`   CHAR(64)     NOT NULL COMMENT 'SHA-256 of IP|identifier',
  `created_at` DATETIME     NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_key_hash` (`key_hash`),
  KEY `idx_created`  (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── ACTIVITY LOG ──
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    INT UNSIGNED NOT NULL,
  `event`      VARCHAR(50)  NOT NULL,
  `detail`     VARCHAR(255)     NULL,
  `ip`         VARCHAR(45)      NULL COMMENT 'IPv4 or IPv6',
  `created_at` DATETIME     NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_event` (`user_id`, `created_at`),
  CONSTRAINT `fk_al_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── USER COMPLIANCE ──
CREATE TABLE IF NOT EXISTS `user_compliance` (
  `user_id`          INT UNSIGNED NOT NULL,
  `dvr_ok`           TINYINT(1)   NOT NULL DEFAULT 0 COMMENT 'DVR aggiornato',
  `haccp_ok`         TINYINT(1)   NOT NULL DEFAULT 0 COMMENT 'HACCP plan aggiornato',
  `form_ok`          TINYINT(1)   NOT NULL DEFAULT 0 COMMENT 'Formazione in regola',
  `med_ok`           TINYINT(1)   NOT NULL DEFAULT 0 COMMENT 'Visite mediche in regola',
  `compliance_score` TINYINT      NOT NULL DEFAULT 0 COMMENT '0-100',
  `updated_at`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_uc_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── DOCUMENTS ──
CREATE TABLE IF NOT EXISTS `documents` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `user_id`     INT UNSIGNED  NOT NULL,
  `type`        VARCHAR(50)   NOT NULL COMMENT 'dvr|haccp|pos|attestato|procedura',
  `title`       VARCHAR(200)  NOT NULL,
  `file_path`   VARCHAR(500)      NULL,
  `created_at`  DATETIME      NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_month` (`user_id`, `created_at`),
  CONSTRAINT `fk_doc_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── DEADLINES ──
CREATE TABLE IF NOT EXISTS `deadlines` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     INT UNSIGNED NOT NULL,
  `title`       VARCHAR(200) NOT NULL,
  `type`        VARCHAR(50)  NOT NULL DEFAULT 'altro' COMMENT 'medica|formazione|dvr|haccp|ispezione|altro',
  `due_date`    DATE         NOT NULL,
  `is_done`     TINYINT(1)   NOT NULL DEFAULT 0,
  `note`        TEXT             NULL,
  `created_at`  DATETIME     NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_due` (`user_id`, `due_date`),
  CONSTRAINT `fk_dl_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── CLEANUP JOB (event scheduler) ──
-- Remove expired rate_limit rows older than 1 hour
CREATE EVENT IF NOT EXISTS `cleanup_rate_limit`
  ON SCHEDULE EVERY 1 HOUR
  DO DELETE FROM `rate_limit` WHERE `created_at` < DATE_SUB(NOW(), INTERVAL 1 HOUR);

-- Remove expired refresh tokens
CREATE EVENT IF NOT EXISTS `cleanup_refresh_tokens`
  ON SCHEDULE EVERY 1 DAY
  DO DELETE FROM `refresh_tokens` WHERE `expires_at` < NOW();

SET foreign_key_checks = 1;
