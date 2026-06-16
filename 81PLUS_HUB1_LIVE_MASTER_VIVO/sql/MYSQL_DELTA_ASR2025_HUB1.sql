-- ============================================================
-- MYSQL_DELTA_ASR2025_HUB1.sql
-- ASR 2025 Compliance Engine — Nuove tabelle HUB1
-- Accordo Stato-Regioni Rep. Atti n. 59/CSR del 17 aprile 2025
-- Eseguire DOPO MYSQL_DELTA_BLOCCO2_HUB1_CORE.sql
-- ============================================================

SET NAMES utf8mb4;
SET foreign_key_checks = 0;

-- ─── AUDIT COMPLIANCE ASR 2025 ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS company_compliance_asr2025 (
  id                    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id               BIGINT UNSIGNED NOT NULL,
  sic_id                VARCHAR(20) NOT NULL,
  ragione_sociale       VARCHAR(200) NULL,
  ateco_code            VARCHAR(10)  NOT NULL,
  macrosettore          VARCHAR(30)  NOT NULL,
  num_lavoratori        TINYINT UNSIGNED NOT NULL DEFAULT 1,
  has_soci              TINYINT(1) NOT NULL DEFAULT 0,
  has_datore_lavoro     TINYINT(1) NOT NULL DEFAULT 1,
  dl_formato            TINYINT(1) NOT NULL DEFAULT 0,
  dl_anni_formazione    TINYINT UNSIGNED NOT NULL DEFAULT 0,
  dl_fa_rspp            TINYINT(1) NOT NULL DEFAULT 0,
  dl_rspp_formato       TINYINT(1) NOT NULL DEFAULT 0,
  ha_cantieri           TINYINT(1) NOT NULL DEFAULT 0,
  e_impresa_affid       TINYINT(1) NOT NULL DEFAULT 0,
  has_dirigenti         TINYINT(1) NOT NULL DEFAULT 0,
  has_preposti          TINYINT(1) NOT NULL DEFAULT 0,
  formazione_lav        TINYINT(1) NOT NULL DEFAULT 0,
  has_antincendio       TINYINT(1) NOT NULL DEFAULT 0,
  has_primo_soccorso    TINYINT(1) NOT NULL DEFAULT 0,
  has_attrezzature      TINYINT(1) NOT NULL DEFAULT 0,
  has_ambienti_conf     TINYINT(1) NOT NULL DEFAULT 0,
  dvr_presente          TINYINT(1) NOT NULL DEFAULT 0,
  scadenziario_presente TINYINT(1) NOT NULL DEFAULT 0,
  haccp_ok              TINYINT(1) NOT NULL DEFAULT 0,
  rischio_livello       ENUM('BASSO','MEDIO','ALTO') NOT NULL DEFAULT 'MEDIO',
  score                 TINYINT UNSIGNED NOT NULL DEFAULT 50,
  audit_completed_at    DATETIME NULL,
  created_at            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user        (user_id),
  INDEX idx_ateco       (ateco_code),
  INDEX idx_macrosettore(macrosettore),
  INDEX idx_rischio     (rischio_livello),
  INDEX idx_sic         (sic_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── LEDGER CORSI RICHIESTI ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS course_requirements_ledger (
  id                   BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id              BIGINT UNSIGNED NOT NULL,
  audit_id             BIGINT UNSIGNED NOT NULL,
  codice_corso         VARCHAR(50)  NOT NULL,
  nome_corso           VARCHAR(200) NOT NULL,
  ore_totali           TINYINT UNSIGNED NOT NULL DEFAULT 0,
  figura_destinataria  VARCHAR(80) NOT NULL,
  motivo_requisito     TEXT NULL,
  stato                ENUM('MANCANTE','IN_SCADENZA','AGGIORNAMENTO','COMPLETATO') NOT NULL DEFAULT 'MANCANTE',
  priorita_giorni      SMALLINT UNSIGNED NULL COMMENT 'scadenza prevista in giorni da audit',
  attestato_url        VARCHAR(255) NULL,
  data_completamento   DATE NULL,
  note                 TEXT NULL,
  created_at           DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id)  REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (audit_id) REFERENCES company_compliance_asr2025(id) ON DELETE CASCADE,
  INDEX idx_user        (user_id),
  INDEX idx_audit       (audit_id),
  INDEX idx_stato       (stato),
  INDEX idx_priorita    (priorita_giorni)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── AGGIORNAMENTO SEMANTIC GUARD (log parole vietate) ───────────────────────
CREATE TABLE IF NOT EXISTS semantic_guard_log (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     BIGINT UNSIGNED NULL,
  contesto    VARCHAR(100) NULL,
  parole      TEXT NOT NULL,
  ip_hash     VARCHAR(64) NULL,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET foreign_key_checks = 1;
