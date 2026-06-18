-- MYSQL_DELTA_GAMIFICATION_WAVE1.sql
-- Wave1 Gamification: Life Wheel, Maslow, Daily Missions, User Scores
-- Blueprint Section 14 — tabelle: life_wheel_scores, maslow_status, daily_missions, user_scores
-- Eseguire dopo MASTER_81PLUS_GLOBAL.sql

SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- ─── 1. RUOTA DELLA VITA ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `life_wheel_scores` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`          BIGINT UNSIGNED NOT NULL,
  `data_rilevazione` DATE            NOT NULL,
  `salute`           TINYINT UNSIGNED NOT NULL DEFAULT 5 COMMENT '1-10',
  `famiglia`         TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `lavoro`           TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `denaro`           TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `crescita`         TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `community`        TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `divertimento`     TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `ambiente`         TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `media`            DECIMAL(4,2) GENERATED ALWAYS AS (
    (salute + famiglia + lavoro + denaro + crescita + community + divertimento + ambiente) / 8
  ) STORED,
  `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_data` (`user_id`, `data_rilevazione`),
  KEY `idx_user`     (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Ruota della Vita — 8 dimensioni, rilevazione periodica per utente';

-- ─── 2. PIRAMIDE DI MASLOW 81+ ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `maslow_status` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`       BIGINT UNSIGNED NOT NULL,
  `livello`       TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '1=CONFORMITA 2=PROTEZIONE 3=COMMUNITY 4=REPUTAZIONE 5=LEADERSHIP',
  `punti_livello` INT UNSIGNED     NOT NULL DEFAULT 0,
  `updated_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Piramide Maslow 81+ — livello corrente per utente';

-- ─── 3. MISSIONI GIORNALIERE ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `daily_missions` (
  `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`           BIGINT UNSIGNED NOT NULL,
  `data_assegnazione` DATE            NOT NULL,
  `missione_codice`   VARCHAR(100)    NOT NULL,
  `missione_nome`     VARCHAR(200)    NOT NULL,
  `pvplus_reward`     DECIMAL(18,4)   NOT NULL DEFAULT 0,
  `status`            ENUM('PENDING','COMPLETED','EXPIRED') NOT NULL DEFAULT 'PENDING',
  `completato_at`     DATETIME DEFAULT NULL,
  `created_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_date` (`user_id`, `data_assegnazione`),
  KEY `idx_status`    (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Missioni giornaliere assegnate per utente — log e stato';

-- ─── 4. USER SCORES ─────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `user_scores` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`          BIGINT UNSIGNED NOT NULL,
  `compliance_score` TINYINT UNSIGNED NOT NULL DEFAULT 0  COMMENT '0-100: audit + doc81',
  `engagement_score` TINYINT UNSIGNED NOT NULL DEFAULT 0  COMMENT '0-100: missioni + accessi',
  `network_score`    TINYINT UNSIGNED NOT NULL DEFAULT 0  COMMENT '0-100: referral + networker',
  `growth_score`     TINYINT UNSIGNED NOT NULL DEFAULT 0  COMMENT '0-100: corsi + ruota',
  `total_score`      SMALLINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0-400: somma dei 4 score',
  `streak_days`      SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `last_active_date` DATE DEFAULT NULL,
  `updated_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Score aggregati per gamification e leaderboard';

-- ─── 5. NUOVE MISSIONI PV+ ──────────────────────────────────────────────────
INSERT IGNORE INTO `pvplus_missions`
  (`codice`, `nome`, `descrizione`, `pvplus_base`, `tipo`, `categoria`) VALUES

-- Daily
('DAILY_LOGIN',       'Accesso giornaliero 81+',
 'Accedi alla piattaforma ogni giorno per guadagnare PV+',
 10,   'RICORRENTE_GIORNALIERO', 'DAILY'),

('DAILY_COCKPIT',     'Missione del giorno completata',
 'Apri il Cockpit e completa la tua missione quotidiana',
 25,   'RICORRENTE_GIORNALIERO', 'DAILY'),

-- Ruota della Vita
('LIFE_WHEEL_FIRST',  'Prima Ruota della Vita',
 'Compila per la prima volta la tua Ruota della Vita',
 300,  'ONETIME',                'CRESCITA'),

('LIFE_WHEEL_WEEKLY', 'Ruota della Vita settimanale',
 'Aggiorna i tuoi 8 indicatori ogni settimana',
 150,  'RICORRENTE_SETTIMANALE', 'CRESCITA'),

-- Maslow progressione
('MASLOW_L2',         'Livello Protezione sbloccato',
 'Hai attivato protezione operativa — livello Maslow 2',
 500,  'ONETIME',                'MASLOW'),

('MASLOW_L3',         'Livello Community sbloccato',
 'Sei parte attiva della rete 81+ — livello Maslow 3',
 1000, 'ONETIME',                'MASLOW'),

('MASLOW_L4',         'Livello Reputazione sbloccato',
 'Sei un punto di riferimento 81+ — livello Maslow 4',
 2000, 'ONETIME',                'MASLOW'),

('MASLOW_L5',         'Livello Leadership sbloccato',
 'Guidi altri imprenditori — livello Maslow 5',
 5000, 'ONETIME',                'MASLOW'),

-- Streak
('STREAK_7',          '7 giorni consecutivi',
 'Hai effettuato accesso per 7 giorni di fila',
 500,  'ONETIME',                'STREAK'),

('STREAK_30',         '30 giorni consecutivi',
 'Hai effettuato accesso per 30 giorni di fila',
 2000, 'ONETIME',                'STREAK');
