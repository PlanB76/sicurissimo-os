-- MYSQL_DELTA_ARCHIVIO_INTELLIGENTE.sql
-- Delta: Archivio Intelligente 81+ Global
-- Data: 2026-06-18
-- Versione: 1.0
-- Owner: Claude Code / Mirco Pregnolato
-- Stato: BOZZA — richiede validazione umana prima del deploy su Hostinger
--
-- Installa 4 tabelle:
--   1. archivio_master     — registro centrale di tutti i file del sistema
--   2. ai_outputs          — output prodotti da ogni AI (Claude, Gemini, ChatGPT, Gemma4)
--   3. notebook_status     — stato in tempo reale dei 32 NotebookLM
--   4. task_queue          — coda task tra AI, umani e Claude Code
--
-- Esegui DOPO tutti i delta precedenti.
-- Nessun DROP. Uso IF NOT EXISTS per sicurezza.
-- Charset: utf8mb4. Collation: utf8mb4_unicode_ci.

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

-- ============================================================
-- 1. ARCHIVIO_MASTER
-- Registro centrale di ogni file presente in C:\81PLUS_GLOBAL_MASTER
-- e mirroring Google Drive. Popolato da Gemma4 Watchdog via API.
-- ============================================================

CREATE TABLE IF NOT EXISTS `archivio_master` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `file_name`       VARCHAR(255) NOT NULL,
  `file_path`       VARCHAR(1000) NOT NULL,
  `file_hash_sha256` CHAR(64) NOT NULL,
  `file_size_bytes` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `file_ext`        VARCHAR(20) NOT NULL DEFAULT '',
  `area`            VARCHAR(100) NOT NULL DEFAULT 'NON_CLASSIFICATO',
  `status`          ENUM('APPROVED','REVIEW','REJECTED','PENDING') NOT NULL DEFAULT 'PENDING',
  `source`          ENUM('LOCAL','DRIVE','GITHUB','HOSTINGER','EXTERNAL') NOT NULL DEFAULT 'LOCAL',
  `uploaded_by`     VARCHAR(100) NOT NULL DEFAULT 'SYSTEM',
  `ai_analyzed_by`  VARCHAR(100) DEFAULT NULL COMMENT 'Nome AI che ha analizzato il file',
  `summary`         TEXT DEFAULT NULL,
  `risks`           TEXT DEFAULT NULL COMMENT 'JSON array dei rischi rilevati',
  `destination_hint` VARCHAR(500) DEFAULT NULL,
  `human_approved`  TINYINT(1) NOT NULL DEFAULT 0,
  `human_approved_by` VARCHAR(100) DEFAULT NULL,
  `human_approved_at` DATETIME DEFAULT NULL,
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_file_hash` (`file_hash_sha256`),
  KEY `idx_status` (`status`),
  KEY `idx_area` (`area`),
  KEY `idx_source` (`source`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Registro centrale di tutti i file del sistema 81+ Global';


-- ============================================================
-- 2. AI_OUTPUTS
-- Ogni output prodotto da un AI (testo, JSON, codice, visual brief)
-- viene registrato qui con riferimento al file sorgente se presente.
-- ============================================================

CREATE TABLE IF NOT EXISTS `ai_outputs` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ai_name`         ENUM('CLAUDE_CODE','CLAUDE_COWORK','CHATGPT','GEMINI','GEMMA4','SYSTEM') NOT NULL,
  `output_type`     ENUM('POST','SCRIPT','SQL','ANALYSIS','REPORT','LEGAL','VISUAL_BRIEF','EMAIL','FAQ','OTHER') NOT NULL DEFAULT 'OTHER',
  `title`           VARCHAR(500) NOT NULL,
  `content`         LONGTEXT NOT NULL,
  `version`         VARCHAR(20) NOT NULL DEFAULT '1.0',
  `status`          ENUM('BOZZA','IN_REVIEW','APPROVATO','RIFIUTATO','ARCHIVIATO') NOT NULL DEFAULT 'BOZZA',
  `area`            VARCHAR(100) NOT NULL DEFAULT 'GENERALE',
  `hub_number`      TINYINT UNSIGNED DEFAULT NULL COMMENT 'Numero HUB NotebookLM (1-32)',
  `archivio_ref`    INT UNSIGNED DEFAULT NULL COMMENT 'FK a archivio_master.id se output da file',
  `task_ref`        INT UNSIGNED DEFAULT NULL COMMENT 'FK a task_queue.id se output da task',
  `human_approval_required` TINYINT(1) NOT NULL DEFAULT 1,
  `human_approved`  TINYINT(1) NOT NULL DEFAULT 0,
  `human_approved_by` VARCHAR(100) DEFAULT NULL,
  `human_approved_at` DATETIME DEFAULT NULL,
  `destination_path` VARCHAR(1000) DEFAULT NULL COMMENT 'Cartella destinazione consigliata',
  `tags`            VARCHAR(500) DEFAULT NULL COMMENT 'Tag separati da virgola',
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_ai_name` (`ai_name`),
  KEY `idx_output_type` (`output_type`),
  KEY `idx_status` (`status`),
  KEY `idx_hub` (`hub_number`),
  KEY `idx_area` (`area`),
  KEY `idx_created` (`created_at`),
  CONSTRAINT `fk_ai_outputs_archivio` FOREIGN KEY (`archivio_ref`)
    REFERENCES `archivio_master` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Output prodotti da ogni AI del sistema 81+ Global';


-- ============================================================
-- 3. NOTEBOOK_STATUS
-- Stato in tempo reale dei 32 NotebookLM.
-- Aggiornato da Claude CoWork ogni settimana.
-- ============================================================

CREATE TABLE IF NOT EXISTS `notebook_status` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `hub_number`      TINYINT UNSIGNED NOT NULL COMMENT '1-32',
  `hub_name`        VARCHAR(100) NOT NULL,
  `status`          ENUM('ATTIVO','BLOCCATO','IN_ATTESA','ARCHIVIATO') NOT NULL DEFAULT 'IN_ATTESA',
  `week_date`       DATE NOT NULL COMMENT 'Lunedi della settimana di riferimento',
  `changes_this_week` TEXT DEFAULT NULL,
  `blockers`        TEXT DEFAULT NULL,
  `needs`           TEXT DEFAULT NULL,
  `task_claude_code` TEXT DEFAULT NULL,
  `task_ai_agent`   TEXT DEFAULT NULL,
  `task_human`      TEXT DEFAULT NULL,
  `kpi_json`        JSON DEFAULT NULL COMMENT 'KPI aggiornati in formato JSON',
  `next_action`     TEXT DEFAULT NULL,
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_hub_week` (`hub_number`, `week_date`),
  KEY `idx_status` (`status`),
  KEY `idx_hub_number` (`hub_number`),
  KEY `idx_week_date` (`week_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Stato settimanale dei 32 NotebookLM del sistema 81+ Global';


-- ============================================================
-- 4. TASK_QUEUE
-- Coda centralizzata per task tra AI, Claude Code e operatori umani.
-- Priorita: URGENTE > ALTA > NORMALE > BASSA.
-- ============================================================

CREATE TABLE IF NOT EXISTS `task_queue` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_code`       VARCHAR(50) NOT NULL COMMENT 'Es: TASK-2026-0001',
  `title`           VARCHAR(500) NOT NULL,
  `description`     TEXT DEFAULT NULL,
  `assigned_to`     ENUM('CLAUDE_CODE','CLAUDE_COWORK','CHATGPT','GEMINI','GEMMA4','HUMAN','SYSTEM') NOT NULL DEFAULT 'CLAUDE_CODE',
  `created_by`      ENUM('CLAUDE_CODE','CLAUDE_COWORK','CHATGPT','GEMINI','GEMMA4','HUMAN','SYSTEM') NOT NULL DEFAULT 'HUMAN',
  `priority`        ENUM('URGENTE','ALTA','NORMALE','BASSA') NOT NULL DEFAULT 'NORMALE',
  `status`          ENUM('APERTO','IN_CORSO','COMPLETATO','BLOCCATO','ANNULLATO') NOT NULL DEFAULT 'APERTO',
  `area`            VARCHAR(100) NOT NULL DEFAULT 'GENERALE',
  `hub_number`      TINYINT UNSIGNED DEFAULT NULL,
  `input_files`     TEXT DEFAULT NULL COMMENT 'Percorsi file di input, uno per riga',
  `output_files`    TEXT DEFAULT NULL COMMENT 'Percorsi file prodotti, uno per riga',
  `notes`           TEXT DEFAULT NULL,
  `deadline`        DATETIME DEFAULT NULL,
  `started_at`      DATETIME DEFAULT NULL,
  `completed_at`    DATETIME DEFAULT NULL,
  `human_required`  TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 se richiede validazione umana',
  `human_approved`  TINYINT(1) NOT NULL DEFAULT 0,
  `human_approved_by` VARCHAR(100) DEFAULT NULL,
  `human_approved_at` DATETIME DEFAULT NULL,
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_task_code` (`task_code`),
  KEY `idx_assigned_to` (`assigned_to`),
  KEY `idx_priority` (`priority`),
  KEY `idx_status` (`status`),
  KEY `idx_area` (`area`),
  KEY `idx_hub` (`hub_number`),
  KEY `idx_deadline` (`deadline`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Coda centralizzata task tra AI, Claude Code e operatori umani 81+ Global';


-- ============================================================
-- FK aggiuntiva: ai_outputs.task_ref -> task_queue.id
-- Aggiunta dopo la creazione delle due tabelle.
-- ============================================================

ALTER TABLE `ai_outputs`
  ADD CONSTRAINT `fk_ai_outputs_task`
    FOREIGN KEY (`task_ref`) REFERENCES `task_queue` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE;


-- ============================================================
-- DATI INIZIALI — 32 HUB notebook_status (prima settimana)
-- ============================================================

INSERT IGNORE INTO `notebook_status`
  (`hub_number`, `hub_name`, `status`, `week_date`, `next_action`)
VALUES
  (1,  'MASTER_VIVO',                      'ATTIVO',    '2026-06-15', 'Aggiorna STATO_PROGETTO.md con Wave1 completata'),
  (2,  'LEGALE_REGOLAMENTI',               'IN_ATTESA', '2026-06-15', 'Caricare bozza regolamento PV+ per revisione'),
  (3,  'HUB1',                             'ATTIVO',    '2026-06-15', 'Deploy cockpit81.php su Hostinger'),
  (4,  'HUB2',                             'IN_ATTESA', '2026-06-15', 'Definire architettura HUB2'),
  (5,  'HUB3',                             'IN_ATTESA', '2026-06-15', 'Definire architettura HUB3'),
  (6,  'MARKETING_CONTENUTI',              'ATTIVO',    '2026-06-15', 'Generare 4 post magnetici settimanali'),
  (7,  'GLOBAL',                           'ATTIVO',    '2026-06-15', 'Aggiornare mappa ecosistema 22 nodi'),
  (8,  'BONIFICA',                         'IN_ATTESA', '2026-06-15', 'Avviare bonifica archivio storico'),
  (9,  'HUB1_WAVE1',                       'ATTIVO',    '2026-06-15', 'Wave1 Giorni 8-14: SIC-ID e wallet frontend'),
  (10, 'VISUAL_MASTER',                    'IN_ATTESA', '2026-06-15', 'Creare brief visivo per Gemini'),
  (11, 'ECONOMIA_CASHFLOW',                'IN_ATTESA', '2026-06-15', 'Importare dati cashflow da fogli Google'),
  (12, 'COMMERCIALISTA_TASSE',             'IN_ATTESA', '2026-06-15', 'Preparare documentazione fiscale 2025'),
  (13, 'COMPLIANCE',                       'ATTIVO',    '2026-06-15', 'Aggiornare analisi MiCA e AI Act'),
  (14, 'DUE_DILIGENCE',                    'IN_ATTESA', '2026-06-15', 'Template due diligence pronto'),
  (15, 'STRATEGIE',                        'ATTIVO',    '2026-06-15', 'Piano go-to-market Q3 2026'),
  (16, 'INVESTITORI_ANGELS',               'IN_ATTESA', '2026-06-15', 'Preparare pitch deck v2'),
  (17, 'BANCHE_FONDI',                     'IN_ATTESA', '2026-06-15', 'Mappare fondi disponibili'),
  (18, 'GESTIONE_GLOBALE',                 'ATTIVO',    '2026-06-15', 'Dashboard KPI globale Wave1'),
  (19, 'EXIT',                             'IN_ATTESA', '2026-06-15', 'Definire strategia exit 3 anni'),
  (20, 'WAVE_OPERATIVE',                   'ATTIVO',    '2026-06-15', 'Pianificare Wave2 e Wave3'),
  (21, 'SCOUT81_LEAD_GENERATION',          'ATTIVO',    '2026-06-15', 'Attivare Scout Pack onboarding'),
  (22, 'SALES_CRM_NETWORKERS',             'ATTIVO',    '2026-06-15', 'Configurare CRM Bridge Skill 39'),
  (23, 'WELFARE_PLAN',                     'IN_ATTESA', '2026-06-15', 'Definire piano welfare PV+'),
  (24, 'STARTUP_INNOVATIVA_BANDI',         'IN_ATTESA', '2026-06-15', 'Ricerca bandi Mise e Simest'),
  (25, 'DAO_GOVERNANCE',                   'IN_ATTESA', '2026-06-15', 'Definire statuto DAO bozza'),
  (26, 'AI_AGENT_CONTROL_TOWER',           'ATTIVO',    '2026-06-15', 'Watchdog Gemma4 installato'),
  (27, 'DATA_ML_NEURAL_ENGINE',            'IN_ATTESA', '2026-06-15', 'Integrare Hugging Face Skill 38'),
  (28, 'REGOLAMENTI_PV_PVPLUS_CAREER',     'IN_ATTESA', '2026-06-15', 'Finalizzare regolamento PV e PV+'),
  (29, 'SICURISSIMO_POINT81_FRANCHISE',    'ATTIVO',    '2026-06-15', 'Preparare kit franchise SICURISSIMO POINT81+'),
  (30, 'CUSTOMER_SUCCESS_SUPPORT',         'IN_ATTESA', '2026-06-15', 'Configurare FAQ Nicolas Skill 34'),
  (31, 'QA_HUMAN_APPROVAL',               'ATTIVO',    '2026-06-15', 'Processo AI propone Human valida attivo'),
  (32, 'SECURITY_PRIVACY_CYBER_RISK',      'IN_ATTESA', '2026-06-15', 'Audit GDPR e analisi rischi cyber');


-- ============================================================
-- TASK INIZIALI — prime azioni Wave1 Giorni 8-14
-- ============================================================

INSERT IGNORE INTO `task_queue`
  (`task_code`, `title`, `assigned_to`, `created_by`, `priority`, `status`, `area`, `hub_number`, `description`, `deadline`, `human_required`)
VALUES
  ('TASK-2026-0001', 'Creazione flow SIC-ID per nuovi utenti',
   'CLAUDE_CODE', 'HUMAN', 'ALTA', 'APERTO', 'HUB1_TECH', 9,
   'Sviluppare pagina registrazione SIC-ID con validazione PHP, insert DB, email conferma.',
   '2026-06-25 23:59:00', 1),

  ('TASK-2026-0002', 'API dinamica punteggio utente (api/user-score.php)',
   'CLAUDE_CODE', 'HUMAN', 'ALTA', 'APERTO', 'HUB1_TECH', 9,
   'Endpoint REST che calcola score live da life_wheel_scores, maslow_status, daily_missions.',
   '2026-06-25 23:59:00', 0),

  ('TASK-2026-0003', 'Frontend wallet PV+ — pannello saldo e movimenti',
   'CLAUDE_CODE', 'HUMAN', 'ALTA', 'APERTO', 'HUB1_TECH', 9,
   'Pagina cockpit wallet con saldo PV+, storico transazioni, barra avanzamento Maslow.',
   '2026-06-27 23:59:00', 1),

  ('TASK-2026-0004', 'Export CSV admin per LEAD_DATABASE',
   'CLAUDE_CODE', 'HUMAN', 'NORMALE', 'APERTO', 'HUB1_TECH', 9,
   'Endpoint admin protetto che esporta tutti i lead con filtri per stato e data.',
   '2026-06-30 23:59:00', 1),

  ('TASK-2026-0005', 'Aggiornare install_db.php con riferimento MYSQL_DELTA_GAMIFICATION_WAVE1',
   'CLAUDE_CODE', 'HUMAN', 'NORMALE', 'APERTO', 'HUB1_TECH', 9,
   'Aggiungere il delta gamification nella lista dei file SQL da eseguire.',
   '2026-06-20 23:59:00', 0),

  ('TASK-2026-0006', 'Deploy MYSQL_DELTA_ARCHIVIO_INTELLIGENTE.sql su Hostinger',
   'HUMAN', 'CLAUDE_CODE', 'ALTA', 'APERTO', 'DATABASE', 1,
   'Validare e eseguire questo file SQL su Hostinger tramite phpMyAdmin o CLI.',
   '2026-06-22 23:59:00', 1),

  ('TASK-2026-0007', 'Configurare Gemma4 Watchdog su C:\\81PLUS_GLOBAL_MASTER',
   'HUMAN', 'CLAUDE_CODE', 'URGENTE', 'APERTO', 'AI_AGENT', 26,
   'Installare Ollama, scaricare modello gemma4, avviare 81PLUS_LOCAL_AI_WATCHDOG.ps1.',
   '2026-06-20 23:59:00', 1),

  ('TASK-2026-0008', 'Caricare 32 HUB_XX.txt nei rispettivi NotebookLM',
   'HUMAN', 'CLAUDE_CODE', 'ALTA', 'APERTO', 'NOTEBOOKLM', NULL,
   'Per ogni NotebookLM: caricare HUB_XX.txt come fonte primaria dal folder HUB_FILES.',
   '2026-06-25 23:59:00', 1),

  ('TASK-2026-0009', 'Brief visivo per Gemini — dashboard mockup HUB1',
   'GEMINI', 'CLAUDE_CODE', 'NORMALE', 'APERTO', 'VISUAL', 10,
   'Creare brief in OUTPUT_PER_GEMINI/: colori, font, mood, elementi obbligatori per mockup cockpit.',
   '2026-06-28 23:59:00', 1),

  ('TASK-2026-0010', 'Bozza regolamento PV+ per ChatGPT',
   'CHATGPT', 'HUMAN', 'ALTA', 'APERTO', 'LEGALE', 28,
   'Produrre bozza regolamento PV+ con sezioni: accesso, accumulo, conversione, scadenza, casi limite.',
   '2026-06-27 23:59:00', 1);

SET foreign_key_checks = 1;

-- Fine MYSQL_DELTA_ARCHIVIO_INTELLIGENTE.sql
-- Eseguito con successo: 4 tabelle create, 32 HUB inizializzati, 10 task aperti.
