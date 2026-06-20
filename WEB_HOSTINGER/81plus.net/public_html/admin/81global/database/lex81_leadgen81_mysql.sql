-- ============================================================
-- 81+ GLOBAL OS -- Schema MySQL Completo
-- LEX81+ & LEADGEN81+
-- Versione: 1.0.0 | Data: 2026-06-20
-- ============================================================
-- ISTRUZIONI:
-- 1. Crea database 81global_db con collation utf8mb4_unicode_ci
-- 2. Esegui questo file via phpMyAdmin oppure CLI:
--    mysql -u user -p 81global_db < lex81_leadgen81_mysql.sql
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

START TRANSACTION;

-- ============================================================
-- LEX81+ TABLES
-- ============================================================

CREATE TABLE IF NOT EXISTS `lex81_categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(64) NOT NULL,
    `name` VARCHAR(128) NOT NULL,
    `description` TEXT,
    `icon` VARCHAR(64),
    `active` TINYINT DEFAULT 1,
    UNIQUE KEY `uq_category_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lex81_norms` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `norm_code` VARCHAR(64) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `source_law` VARCHAR(255),
    `applies_workers_min` INT DEFAULT 0,
    `applies_workers_max` INT DEFAULT 9999,
    `applies_haccp` TINYINT DEFAULT 0,
    `applies_privacy` TINYINT DEFAULT 0,
    `applies_edilizia` TINYINT DEFAULT 0,
    `applies_chimico` TINYINT DEFAULT 0,
    `priority` ENUM('critica','alta','media','bassa') DEFAULT 'media',
    `active` TINYINT DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_norm_code` (`norm_code`),
    CONSTRAINT `fk_norm_category` FOREIGN KEY (`category_id`) REFERENCES `lex81_categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lex81_ateco_map` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ateco_prefix` VARCHAR(10) NOT NULL,
    `norm_id` INT NOT NULL,
    `applicability` ENUM('obbligatoria','consigliata','opzionale') DEFAULT 'obbligatoria',
    `note` TEXT,
    CONSTRAINT `fk_ateco_norm` FOREIGN KEY (`norm_id`) REFERENCES `lex81_norms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lex81_obligations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `norm_id` INT NOT NULL,
    `obligation_code` VARCHAR(64) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `deadline_type` ENUM('immediata','annuale','triennale','quinquennale','custom','evento') NOT NULL,
    `deadline_days` INT NULL,
    `document_codes` VARCHAR(255),
    `course_codes` VARCHAR(255),
    `mission_code` VARCHAR(64),
    `active` TINYINT DEFAULT 1,
    UNIQUE KEY `uq_obligation_code` (`obligation_code`),
    CONSTRAINT `fk_obligation_norm` FOREIGN KEY (`norm_id`) REFERENCES `lex81_norms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lex81_sanctions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `norm_id` INT NOT NULL,
    `violation_code` VARCHAR(64) NOT NULL,
    `violation_title` VARCHAR(255) NOT NULL,
    `sanction_type` ENUM('ammenda','arresto','entrambi','sospensione') NOT NULL,
    `amount_min` DECIMAL(12,2) NULL,
    `amount_max` DECIMAL(12,2) NULL,
    `penal_months_min` INT NULL,
    `penal_months_max` INT NULL,
    `source_validated` TINYINT DEFAULT 0,
    `source_ref` VARCHAR(255),
    `note` TEXT,
    UNIQUE KEY `uq_violation_code` (`violation_code`),
    CONSTRAINT `fk_sanction_norm` FOREIGN KEY (`norm_id`) REFERENCES `lex81_norms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lex81_documents` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `doc_code` VARCHAR(64) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `template_available` TINYINT DEFAULT 0,
    `price_min` DECIMAL(10,2) NULL,
    `price_max` DECIMAL(10,2) NULL,
    `service_code` VARCHAR(64),
    UNIQUE KEY `uq_doc_code` (`doc_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lex81_courses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `course_code` VARCHAR(64) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `duration_hours` INT NOT NULL,
    `frequency_years` INT NULL,
    `target_roles` VARCHAR(255),
    `price_min` DECIMAL(10,2) NULL,
    `price_max` DECIMAL(10,2) NULL,
    UNIQUE KEY `uq_course_code` (`course_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lex81_services` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `service_code` VARCHAR(64) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `price_min` DECIMAL(10,2) NULL,
    `price_max` DECIMAL(10,2) NULL,
    `cta_url` VARCHAR(512),
    `active` TINYINT DEFAULT 1,
    UNIQUE KEY `uq_service_code` (`service_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- LEADGEN81+ TABLES
-- ============================================================

CREATE TABLE IF NOT EXISTS `leadgen81_contacts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `sic_id` VARCHAR(16) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(32),
    `nome` VARCHAR(128),
    `cognome` VARCHAR(128),
    `azienda` VARCHAR(255),
    `ateco_code` VARCHAR(10),
    `macro_sector` VARCHAR(64),
    `workers_count` INT DEFAULT 0,
    `city` VARCHAR(128),
    `province` VARCHAR(4),
    `segment` ENUM('non_profilato','azienda','profilo_completo','lead_caldo','lead_freddo') DEFAULT 'non_profilato',
    `heat` ENUM('freddo','tiepido','caldo') DEFAULT 'freddo',
    `temperature_score` INT DEFAULT 0,
    `status` ENUM('MEMBER','NETWORKERS','ELITE','FRANCHISER','CLUB') DEFAULT 'MEMBER',
    `source` VARCHAR(128),
    `utm_campaign` VARCHAR(128),
    `utm_medium` VARCHAR(64),
    `utm_source` VARCHAR(64),
    `consent_email` TINYINT DEFAULT 0,
    `consent_sms` TINYINT DEFAULT 0,
    `consent_whatsapp` TINYINT DEFAULT 0,
    `consent_profiling` TINYINT DEFAULT 0,
    `consent_date` DATETIME NULL,
    `unsubscribed` TINYINT DEFAULT 0,
    `unsubscribed_at` DATETIME NULL,
    `last_activity_at` DATETIME NULL,
    `haccp_applicable` TINYINT DEFAULT 0,
    `privacy_applicable` TINYINT DEFAULT 1,
    `edilizia_applicable` TINYINT DEFAULT 0,
    `risk_level` ENUM('basso','medio','alto','critico') DEFAULT 'medio',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_contact_email` (`email`),
    UNIQUE KEY `uq_contact_sic_id` (`sic_id`),
    INDEX `idx_contact_segment` (`segment`),
    INDEX `idx_contact_heat` (`heat`),
    INDEX `idx_contact_ateco` (`ateco_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `leadgen81_consents` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `contact_id` INT NOT NULL,
    `consent_type` ENUM('email','sms','whatsapp','profiling','terms') NOT NULL,
    `granted` TINYINT DEFAULT 0,
    `ip_address` VARCHAR(45),
    `user_agent` TEXT,
    `page_url` VARCHAR(512),
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_consent_contact` FOREIGN KEY (`contact_id`) REFERENCES `leadgen81_contacts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `leadgen81_unsubscribes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `contact_id` INT,
    `email` VARCHAR(255),
    `reason` TEXT,
    `unsubscribe_type` ENUM('email','sms','whatsapp','all') DEFAULT 'all',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `leadgen81_segments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `segment_code` VARCHAR(64) NOT NULL,
    `name` VARCHAR(128) NOT NULL,
    `description` TEXT,
    `heat_level` ENUM('freddo','tiepido','caldo') NOT NULL,
    `criteria_json` TEXT,
    `active` TINYINT DEFAULT 1,
    UNIQUE KEY `uq_segment_code` (`segment_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `leadgen81_flows` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `flow_code` VARCHAR(64) NOT NULL,
    `name` VARCHAR(128) NOT NULL,
    `description` TEXT,
    `trigger_event` VARCHAR(64),
    `segment_code` VARCHAR(64),
    `channel` ENUM('email','whatsapp','sms','tutti') DEFAULT 'email',
    `annual_week` INT NULL,
    `special_flow` VARCHAR(64) NULL,
    `active` TINYINT DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_flow_code` (`flow_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `leadgen81_flow_steps` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `flow_id` INT NOT NULL,
    `step_number` INT NOT NULL,
    `step_type` ENUM('email','whatsapp','sms','tag','segment_change','wait','webhook') NOT NULL,
    `delay_hours` INT DEFAULT 0,
    `subject` VARCHAR(255),
    `body_html` MEDIUMTEXT,
    `body_text` TEXT,
    `cta_label` VARCHAR(128),
    `cta_url` VARCHAR(512),
    `from_name` VARCHAR(128) DEFAULT 'Nicolas | Sicurissimo OS',
    `from_email` VARCHAR(128) DEFAULT 'nicolas@sicurissimo.online',
    `active` TINYINT DEFAULT 1,
    UNIQUE KEY `uq_flow_step` (`flow_id`, `step_number`),
    CONSTRAINT `fk_step_flow` FOREIGN KEY (`flow_id`) REFERENCES `leadgen81_flows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `leadgen81_events` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `contact_id` INT,
    `email` VARCHAR(255),
    `event_type` VARCHAR(64) NOT NULL,
    `source` VARCHAR(128),
    `metadata_json` TEXT,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_event_contact` (`contact_id`),
    INDEX `idx_event_type` (`event_type`),
    INDEX `idx_event_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `leadgen81_email_queue` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `contact_id` INT NOT NULL,
    `flow_id` INT NOT NULL,
    `step_id` INT NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `body_html` MEDIUMTEXT,
    `scheduled_at` DATETIME NOT NULL,
    `sent_at` DATETIME NULL,
    `status` ENUM('pending','sent','failed','cancelled','bounced') DEFAULT 'pending',
    `opens` INT DEFAULT 0,
    `clicks` INT DEFAULT 0,
    `tracking_id` VARCHAR(64),
    `error_msg` TEXT,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_tracking_id` (`tracking_id`),
    INDEX `idx_queue_status_scheduled` (`status`, `scheduled_at`),
    INDEX `idx_queue_contact` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `leadgen81_xmas_avvento` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `contact_id` INT NOT NULL,
    `day_number` INT NOT NULL,
    `content_type` ENUM('tip_normativo','bonus_pv','documento_free','corso_free','sconto','quiz') NOT NULL,
    `content_json` TEXT,
    `unlocked_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `opened_at` DATETIME NULL,
    `url` VARCHAR(512) DEFAULT 'https://81plus.christmas',
    UNIQUE KEY `uq_contact_day` (`contact_id`, `day_number`),
    CONSTRAINT `fk_xmas_contact` FOREIGN KEY (`contact_id`) REFERENCES `leadgen81_contacts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED: LEX81+ CATEGORIES (10 categorie)
-- ============================================================

INSERT IGNORE INTO `lex81_categories` (`code`, `name`, `description`, `icon`, `active`) VALUES
('SICUREZZA_LAVORO','Sicurezza sul Lavoro','Normativa D.Lgs 81/08 e successive modifiche','shield',1),
('HACCP','Igiene Alimentare HACCP','Reg. CE 852/2004 - igiene produzione e somministrazione alimenti','utensils',1),
('PRIVACY','Privacy e GDPR','Regolamento UE 2016/679 e D.Lgs 196/2003 aggiornato','lock',1),
('ANTINCENDIO','Prevenzione Incendi','D.M. 03/08/2015 e normativa antincendio','flame',1),
('SORVEGLIANZA_SANITARIA','Sorveglianza Sanitaria','Protocolli sanitari e visite mediche preventive','stethoscope',1),
('FORMAZIONE','Formazione Obbligatoria','Accordi Stato-Regioni e obblighi formativi D.Lgs 81/08','graduation-cap',1),
('CANTIERE','Sicurezza nei Cantieri','D.Lgs 81/08 Titolo IV - gestione cantieri temporanei e mobili','hard-hat',1),
('AMBIENTE','Ambiente e Sostenibilita','D.Lgs 152/2006 e normativa ambientale ISO 14001','leaf',1),
('ISO','Sistemi di Gestione ISO','ISO 45001:2018, ISO 9001:2015, ISO 14001:2015','certificate',1),
('D231','D.Lgs 231/2001','Modello Organizzativo per responsabilita amministrativa enti','building',1);

-- ============================================================
-- SEED: LEX81+ NORMS (24 norme reali italiane)
-- ============================================================

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'DVR_BASE','Documento di Valutazione dei Rischi','Obbligo di redazione e aggiornamento del DVR per tutti i datori di lavoro con almeno un dipendente.','D.Lgs 81/08 Art. 17 comma 1 lett. a)',1,9999,0,0,0,0,'critica',1 FROM lex81_categories WHERE code='SICUREZZA_LAVORO';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'NOMINE_OBBLIGATORIE','Nomine Obbligatorie per la Sicurezza','Nomina obbligatoria del RSPP, del Medico Competente e del RLS.','D.Lgs 81/08 Art. 17 e Art. 18',1,9999,0,0,0,0,'critica',1 FROM lex81_categories WHERE code='SICUREZZA_LAVORO';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'FORMAZIONE_BASE','Formazione Obbligatoria dei Lavoratori','Formazione adeguata entro 60 giorni assunzione. Aggiornamento quinquennale.','D.Lgs 81/08 Art. 37 + Accordo SR 21/12/2011',1,9999,0,0,0,0,'critica',1 FROM lex81_categories WHERE code='FORMAZIONE';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'FORMAZIONE_PREPOSTO','Formazione e Aggiornamento dei Preposti','Corso specifico 8 ore e aggiornamento biennale 6 ore. Obbligo dal D.L. 146/2021.','D.Lgs 81/08 Art. 37 comma 7 mod D.L. 146/2021',1,9999,0,0,0,0,'alta',1 FROM lex81_categories WHERE code='FORMAZIONE';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'FORMAZIONE_DL','Formazione del Datore di Lavoro che svolge RSPP','Il DL che svolge RSPP deve frequentare corsi specifici per la propria classe di rischio.','D.Lgs 81/08 Art. 34',1,9999,0,0,0,0,'alta',1 FROM lex81_categories WHERE code='FORMAZIONE';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'DPI','Dispositivi di Protezione Individuale','Fornitura DPI idonei ai rischi. Manutenzione e formazione sull uso obbligatorie.','D.Lgs 81/08 Art. 18 + Titolo III Capo II',1,9999,0,0,0,0,'alta',1 FROM lex81_categories WHERE code='SICUREZZA_LAVORO';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'SORVEGLIANZA_SANITARIA','Sorveglianza Sanitaria dei Lavoratori','Visite mediche preventive e periodiche per lavoratori esposti a rischi specifici.','D.Lgs 81/08 Art. 18 comma 1 lett. a) e Art. 41',1,9999,0,0,0,0,'alta',1 FROM lex81_categories WHERE code='SORVEGLIANZA_SANITARIA';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'RLS','Rappresentante dei Lavoratori per la Sicurezza','Obbligo di elezione RLS. Sotto soglia puo essere territoriale (RLST).','D.Lgs 81/08 Art. 47',1,9999,0,0,0,0,'media',1 FROM lex81_categories WHERE code='SICUREZZA_LAVORO';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'EMERGENZA_ANTINCENDIO','Piano di Emergenza e Prevenzione Incendi','Piano emergenza, nomina addetti antincendio e aggiornamento periodico.','D.Lgs 81/08 Art. 18 + D.M. 03/08/2015 + D.M. 02/09/2021',1,9999,0,0,0,0,'alta',1 FROM lex81_categories WHERE code='ANTINCENDIO';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'PRIMO_SOCCORSO','Primo Soccorso Aziendale','Designazione e formazione addetti PS per gruppo A, B o C. Aggiornamento ogni 3 anni.','D.Lgs 81/08 Art. 18 comma 1 lett. b) + D.M. 388/2003',1,9999,0,0,0,0,'alta',1 FROM lex81_categories WHERE code='SICUREZZA_LAVORO';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'STRESS_LAVORO','Valutazione Stress Lavoro-Correlato','Obbligo di valutazione e gestione del rischio stress lavoro-correlato nel DVR.','D.Lgs 81/08 Art. 28 comma 1',1,9999,0,0,0,0,'media',1 FROM lex81_categories WHERE code='SICUREZZA_LAVORO';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'ATTREZZATURE','Sicurezza delle Attrezzature di Lavoro','Attrezzature idonee, mantenute efficienti. Formazione operatori obbligatoria.','D.Lgs 81/08 Titolo III Capo I + Accordo SR 22/02/2012',1,9999,0,0,0,0,'media',1 FROM lex81_categories WHERE code='SICUREZZA_LAVORO';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'MANUALE_HACCP','Piano HACCP e Procedure di Autocontrollo','Procedure basate sui principi HACCP obbligatorie per imprese alimentari.','Reg. CE 852/2004 Art. 5',0,9999,1,0,0,0,'critica',1 FROM lex81_categories WHERE code='HACCP';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'FORMAZIONE_HACCP','Formazione degli Operatori del Settore Alimentare','Formazione igiene alimentare documentata per tutto il personale a contatto con alimenti.','Reg. CE 852/2004 Art. 7 + normative regionali',0,9999,1,0,0,0,'critica',1 FROM lex81_categories WHERE code='HACCP';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'REGISTRO_TEMPERATURE','Registro Controllo Temperature','Monitoraggio e registrazione temperature catena del freddo. Registri da conservare 2 anni.','Reg. CE 852/2004 + Reg. CE 853/2004',0,9999,1,0,0,0,'alta',1 FROM lex81_categories WHERE code='HACCP';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'ALLERGENI','Gestione e Comunicazione degli Allergeni','Obbligo informare sui 14 allergeni principali. In ristorazione al momento dell ordine.','Reg. UE 1169/2011 Art. 21 + D.Lgs 231/2017',0,9999,1,0,0,0,'alta',1 FROM lex81_categories WHERE code='HACCP';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'TRACCIABILITA','Tracciabilita degli Alimenti','Rintracciabilita alimenti in tutte le fasi. Documentazione approvvigionamenti e lotti.','Reg. CE 178/2002 Art. 18',0,9999,1,0,0,0,'alta',1 FROM lex81_categories WHERE code='HACCP';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'REGISTRO_TRATTAMENTI','Registro delle Attivita di Trattamento','Registro scritto obbligatorio per aziende con piu di 250 dipendenti o dati sensibili.','GDPR Art. 30 - Reg. UE 2016/679',0,9999,0,1,0,0,'alta',1 FROM lex81_categories WHERE code='PRIVACY';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'INFORMATIVA_DIPENDENTI','Informativa Privacy per i Dipendenti','Informativa chiara ai dipendenti prima o al momento dell assunzione.','GDPR Art. 13 - Reg. UE 2016/679',1,9999,0,1,0,0,'alta',1 FROM lex81_categories WHERE code='PRIVACY';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'INFORMATIVA_CLIENTI','Informativa Privacy per i Clienti','Informare i clienti sul trattamento dati prima della raccolta.','GDPR Art. 13 - Reg. UE 2016/679',0,9999,0,1,0,0,'alta',1 FROM lex81_categories WHERE code='PRIVACY';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'COOKIE_POLICY','Cookie Policy e Banner Consenso','Informare sugli utenti sull uso dei cookie e raccogliere consenso per cookie non tecnici.','Provvedimento Garante Privacy 10/06/2021',0,9999,0,1,0,0,'media',1 FROM lex81_categories WHERE code='PRIVACY';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'PSC_POS','Piano di Sicurezza e Coordinamento e Piano Operativo','PSC redatto dal CSP e POS da ogni impresa esecutrice obbligatori in cantiere.','D.Lgs 81/08 Titolo IV Art. 100 e segg.',0,9999,0,0,1,0,'critica',1 FROM lex81_categories WHERE code='CANTIERE';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'ISO45001','Sistema di Gestione per la Sicurezza ISO 45001','La certificazione ISO 45001:2018 diventa asset competitivo nelle gare d appalto.','UNI ISO 45001:2018',10,9999,0,0,0,0,'media',1 FROM lex81_categories WHERE code='ISO';

INSERT IGNORE INTO `lex81_norms` (`category_id`,`norm_code`,`title`,`description`,`source_law`,`applies_workers_min`,`applies_workers_max`,`applies_haccp`,`applies_privacy`,`applies_edilizia`,`applies_chimico`,`priority`,`active`)
SELECT id,'MOG231','Modello Organizzativo e di Gestione D.Lgs 231/2001','Il MOG 231 tutela l azienda dalla responsabilita amministrativa in caso di reati da dipendenti.','D.Lgs 231/2001 Art. 6',10,9999,0,0,0,0,'media',1 FROM lex81_categories WHERE code='D231';

-- ============================================================
-- SEED: SANCTIONS (con valori reali validati)
-- ============================================================

INSERT IGNORE INTO `lex81_sanctions` (`norm_id`,`violation_code`,`violation_title`,`sanction_type`,`amount_min`,`amount_max`,`penal_months_min`,`penal_months_max`,`source_validated`,`source_ref`,`note`)
SELECT id,'DVR_ASSENTE','DVR non redatto o non aggiornato','entrambi',2457.00,4914.00,3,6,1,'D.Lgs 81/08 Art. 55 comma 1 lett. a)','Arresto da 3 a 6 mesi o ammenda alternativa.' FROM lex81_norms WHERE norm_code='DVR_BASE';

INSERT IGNORE INTO `lex81_sanctions` (`norm_id`,`violation_code`,`violation_title`,`sanction_type`,`amount_min`,`amount_max`,`penal_months_min`,`penal_months_max`,`source_validated`,`source_ref`,`note`)
SELECT id,'RSPP_NON_NOMINATO','RSPP non nominato o privo dei requisiti','entrambi',2457.00,4914.00,3,6,1,'D.Lgs 81/08 Art. 55 comma 1 lett. b)','Obbligo non delegabile del datore di lavoro.' FROM lex81_norms WHERE norm_code='NOMINE_OBBLIGATORIE';

INSERT IGNORE INTO `lex81_sanctions` (`norm_id`,`violation_code`,`violation_title`,`sanction_type`,`amount_min`,`amount_max`,`penal_months_min`,`penal_months_max`,`source_validated`,`source_ref`,`note`)
SELECT id,'FORMAZIONE_OMESSA','Formazione obbligatoria omessa o insufficiente','entrambi',1474.21,2948.43,2,4,1,'D.Lgs 81/08 Art. 55 comma 5 lett. c)','Sanzione applicabile per ogni lavoratore non formato. Cumulabile.' FROM lex81_norms WHERE norm_code='FORMAZIONE_BASE';

INSERT IGNORE INTO `lex81_sanctions` (`norm_id`,`violation_code`,`violation_title`,`sanction_type`,`amount_min`,`amount_max`,`penal_months_min`,`penal_months_max`,`source_validated`,`source_ref`,`note`)
SELECT id,'SORVEGLIANZA_OMESSA','Sorveglianza sanitaria non effettuata','entrambi',2457.00,4914.00,3,6,1,'D.Lgs 81/08 Art. 55 comma 1 lett. e)','Grave per lavoratori esposti ad agenti chimici, rumore, vibrazioni.' FROM lex81_norms WHERE norm_code='SORVEGLIANZA_SANITARIA';

INSERT IGNORE INTO `lex81_sanctions` (`norm_id`,`violation_code`,`violation_title`,`sanction_type`,`amount_min`,`amount_max`,`penal_months_min`,`penal_months_max`,`source_validated`,`source_ref`,`note`)
SELECT id,'HACCP_ASSENTE','Piano HACCP assente o non aggiornato','ammenda',500.00,6000.00,NULL,NULL,1,'D.Lgs 193/2007 Art. 6 + Reg. CE 882/2004','Puo essere accompagnata da sospensione attivita in caso di rischio grave.' FROM lex81_norms WHERE norm_code='MANUALE_HACCP';

INSERT IGNORE INTO `lex81_sanctions` (`norm_id`,`violation_code`,`violation_title`,`sanction_type`,`amount_min`,`amount_max`,`penal_months_min`,`penal_months_max`,`source_validated`,`source_ref`,`note`)
SELECT id,'ALLERGENI_NON_COMUNICATI','Allergeni non comunicati o etichettatura errata','ammenda',1000.00,8000.00,NULL,NULL,1,'D.Lgs 231/2017 Art. 18 - attuazione Reg. UE 1169/2011','In caso di danno al consumatore, applicabile anche responsabilita penale.' FROM lex81_norms WHERE norm_code='ALLERGENI';

INSERT IGNORE INTO `lex81_sanctions` (`norm_id`,`violation_code`,`violation_title`,`sanction_type`,`amount_min`,`amount_max`,`penal_months_min`,`penal_months_max`,`source_validated`,`source_ref`,`note`)
SELECT id,'REGISTRO_TRATTAMENTI_ASSENTE','Registro trattamenti GDPR assente','ammenda',0.00,10000000.00,NULL,NULL,1,'GDPR Art. 83 comma 4 - Reg. UE 2016/679','Valore massimo teorico. Per PMI il Garante applica criteri proporzionali. Importo puo essere 2% fatturato globale annuo.' FROM lex81_norms WHERE norm_code='REGISTRO_TRATTAMENTI';

INSERT IGNORE INTO `lex81_sanctions` (`norm_id`,`violation_code`,`violation_title`,`sanction_type`,`amount_min`,`amount_max`,`penal_months_min`,`penal_months_max`,`source_validated`,`source_ref`,`note`)
SELECT id,'POS_ASSENTE','Piano Operativo di Sicurezza assente nel cantiere','ammenda',2457.00,4914.00,NULL,NULL,1,'D.Lgs 81/08 Art. 157','Responsabilita datore lavoro impresa esecutrice. Cantiere soggetto a sospensione immediata.' FROM lex81_norms WHERE norm_code='PSC_POS';

INSERT IGNORE INTO `lex81_sanctions` (`norm_id`,`violation_code`,`violation_title`,`sanction_type`,`amount_min`,`amount_max`,`penal_months_min`,`penal_months_max`,`source_validated`,`source_ref`,`note`)
SELECT id,'FORMAZIONE_HACCP_ASSENTE','Formazione HACCP del personale assente o non documentata','ammenda',300.00,3000.00,NULL,NULL,0,'Normative regionali variabili - rif. Reg. CE 852/2004 Art. 7','DA_VALIDARE: importi variano per regione. Verificare normativa locale specifica.' FROM lex81_norms WHERE norm_code='FORMAZIONE_HACCP';

-- ============================================================
-- SEED: ATECO MAP
-- ============================================================

-- Edilizia
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '41',id,'obbligatoria','Costruzione edifici - rischio PSC' FROM lex81_norms WHERE norm_code='PSC_POS';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '41',id,'obbligatoria',NULL FROM lex81_norms WHERE norm_code='DVR_BASE';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '41',id,'obbligatoria','Rischio alto - formazione 12 ore' FROM lex81_norms WHERE norm_code='FORMAZIONE_BASE';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '42',id,'obbligatoria','Ingegneria civile' FROM lex81_norms WHERE norm_code='PSC_POS';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '42',id,'obbligatoria',NULL FROM lex81_norms WHERE norm_code='DVR_BASE';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '42',id,'obbligatoria','Rischio alto' FROM lex81_norms WHERE norm_code='FORMAZIONE_BASE';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '43',id,'obbligatoria','Lavori specializzati - include quota' FROM lex81_norms WHERE norm_code='PSC_POS';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '43',id,'obbligatoria',NULL FROM lex81_norms WHERE norm_code='DVR_BASE';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '43',id,'obbligatoria','Rischio alto' FROM lex81_norms WHERE norm_code='FORMAZIONE_BASE';
-- Alimentare
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '10',id,'obbligatoria','Industria alimentare - piano HACCP completo' FROM lex81_norms WHERE norm_code='MANUALE_HACCP';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '10',id,'obbligatoria','Etichettatura e schede allergeni' FROM lex81_norms WHERE norm_code='ALLERGENI';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '10',id,'obbligatoria','Tracciabilita dalla materia prima al prodotto finito' FROM lex81_norms WHERE norm_code='TRACCIABILITA';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '10',id,'obbligatoria','Tutto il personale a contatto con alimenti' FROM lex81_norms WHERE norm_code='FORMAZIONE_HACCP';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '11',id,'obbligatoria','Produzione bevande' FROM lex81_norms WHERE norm_code='MANUALE_HACCP';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '11',id,'obbligatoria',NULL FROM lex81_norms WHERE norm_code='ALLERGENI';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '11',id,'obbligatoria',NULL FROM lex81_norms WHERE norm_code='TRACCIABILITA';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '11',id,'obbligatoria',NULL FROM lex81_norms WHERE norm_code='FORMAZIONE_HACCP';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '12',id,'obbligatoria','Produzione tabacco' FROM lex81_norms WHERE norm_code='MANUALE_HACCP';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '12',id,'consigliata',NULL FROM lex81_norms WHERE norm_code='FORMAZIONE_HACCP';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '56',id,'obbligatoria','Ristorazione - area piu comune ispezione NAS' FROM lex81_norms WHERE norm_code='MANUALE_HACCP';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '56',id,'obbligatoria','Obbligo comunicazione al momento dell ordine' FROM lex81_norms WHERE norm_code='ALLERGENI';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '56',id,'obbligatoria','Registro fornitori e lotti obbligatorio' FROM lex81_norms WHERE norm_code='TRACCIABILITA';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '56',id,'obbligatoria','Incluso personale di sala e cucina' FROM lex81_norms WHERE norm_code='FORMAZIONE_HACCP';
-- Universale
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '*',id,'obbligatoria','Applicabile a tutti i datori con dipendenti' FROM lex81_norms WHERE norm_code='DVR_BASE';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '*',id,'obbligatoria','Entro 60 giorni dall assunzione' FROM lex81_norms WHERE norm_code='FORMAZIONE_BASE';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '*',id,'obbligatoria','Piano emergenza obbligatorio in tutte le aziende' FROM lex81_norms WHERE norm_code='EMERGENZA_ANTINCENDIO';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '*',id,'obbligatoria','Almeno un addetto PS per ogni turno' FROM lex81_norms WHERE norm_code='PRIMO_SOCCORSO';
INSERT IGNORE INTO `lex81_ateco_map` (`ateco_prefix`,`norm_id`,`applicability`,`note`) SELECT '*',id,'obbligatoria','Ove i rischi non eliminabili alla fonte' FROM lex81_norms WHERE norm_code='DPI';

-- ============================================================
-- SEED: OBLIGATIONS (12 obblighi)
-- ============================================================

INSERT IGNORE INTO `lex81_obligations` (`norm_id`,`obligation_code`,`title`,`description`,`deadline_type`,`deadline_days`,`document_codes`,`course_codes`,`mission_code`,`active`) SELECT id,'OBL_DVR_REDAZIONE','Redazione iniziale del DVR','Il DVR deve essere redatto prima dell avvio dell attivita. Firma DL, RSPP, Medico Competente e RLS.','immediata',NULL,'DOC_DVR',NULL,'MISSION_DVR_PRIMO',1 FROM lex81_norms WHERE norm_code='DVR_BASE';

INSERT IGNORE INTO `lex81_obligations` (`norm_id`,`obligation_code`,`title`,`description`,`deadline_type`,`deadline_days`,`document_codes`,`course_codes`,`mission_code`,`active`) SELECT id,'OBL_DVR_AGGIORNAMENTO','Aggiornamento periodico del DVR','Aggiornamento in occasione di modifiche del processo produttivo o dopo infortuni significativi.','evento',NULL,'DOC_DVR',NULL,'MISSION_DVR_UPDATE',1 FROM lex81_norms WHERE norm_code='DVR_BASE';

INSERT IGNORE INTO `lex81_obligations` (`norm_id`,`obligation_code`,`title`,`description`,`deadline_type`,`deadline_days`,`document_codes`,`course_codes`,`mission_code`,`active`) SELECT id,'OBL_RSPP_NOMINA','Nomina del Responsabile del Servizio di Prevenzione e Protezione','Il RSPP deve essere nominato prima dell avvio dell attivita.','immediata',NULL,'DOC_NOMINA_RSPP','CORSO_RSPP','MISSION_RSPP_NOMINA',1 FROM lex81_norms WHERE norm_code='NOMINE_OBBLIGATORIE';

INSERT IGNORE INTO `lex81_obligations` (`norm_id`,`obligation_code`,`title`,`description`,`deadline_type`,`deadline_days`,`document_codes`,`course_codes`,`mission_code`,`active`) SELECT id,'OBL_FORMAZIONE_ENTRO60','Formazione dei lavoratori entro 60 giorni dall assunzione','Ogni nuovo lavoratore deve completare formazione generale e specifica entro 60 giorni.','custom',60,'DOC_REGISTRO_FORMAZIONE','CORSO_LAVORATORI_BASE,CORSO_LAVORATORI_MEDIO,CORSO_LAVORATORI_ALTO','MISSION_FORMAZIONE_BASE',1 FROM lex81_norms WHERE norm_code='FORMAZIONE_BASE';

INSERT IGNORE INTO `lex81_obligations` (`norm_id`,`obligation_code`,`title`,`description`,`deadline_type`,`deadline_days`,`document_codes`,`course_codes`,`mission_code`,`active`) SELECT id,'OBL_PREPOSTO_AGGIORNAMENTO','Aggiornamento biennale dei preposti','I preposti devono seguire aggiornamento ogni 2 anni di almeno 6 ore.','triennale',NULL,NULL,'CORSO_PREPOSTO_UPDATE','MISSION_PREPOSTO_UPD',1 FROM lex81_norms WHERE norm_code='FORMAZIONE_PREPOSTO';

INSERT IGNORE INTO `lex81_obligations` (`norm_id`,`obligation_code`,`title`,`description`,`deadline_type`,`deadline_days`,`document_codes`,`course_codes`,`mission_code`,`active`) SELECT id,'OBL_ANTINCENDIO_PIANO','Redazione e aggiornamento del Piano di Emergenza','Il piano di emergenza include procedure evacuazione, numeri emergenza e ruoli addetti.','immediata',NULL,'DOC_PIANO_EMERGENZA','CORSO_ANTINCENDIO_BASSO,CORSO_ANTINCENDIO_MEDIO,CORSO_ANTINCENDIO_ALTO','MISSION_EMERGENZA',1 FROM lex81_norms WHERE norm_code='EMERGENZA_ANTINCENDIO';

INSERT IGNORE INTO `lex81_obligations` (`norm_id`,`obligation_code`,`title`,`description`,`deadline_type`,`deadline_days`,`document_codes`,`course_codes`,`mission_code`,`active`) SELECT id,'OBL_PS_FORMAZIONE','Formazione degli addetti al primo soccorso','Corso per il gruppo A, B o C. Aggiornamento ogni 3 anni. Minimo un addetto per turno.','triennale',NULL,NULL,'CORSO_PRIMO_SOCCORSO_A,CORSO_PRIMO_SOCCORSO_B','MISSION_PS_FORM',1 FROM lex81_norms WHERE norm_code='PRIMO_SOCCORSO';

INSERT IGNORE INTO `lex81_obligations` (`norm_id`,`obligation_code`,`title`,`description`,`deadline_type`,`deadline_days`,`document_codes`,`course_codes`,`mission_code`,`active`) SELECT id,'OBL_HACCP_PIANO','Redazione e mantenimento del Piano HACCP','Il piano HACCP identifica rischi, CCP, limiti critici, monitoraggio e azioni correttive. Revisione annuale.','annuale',NULL,'DOC_MANUALE_HACCP','CORSO_HACCP_BASE,CORSO_HACCP_RESPONSABILE','MISSION_HACCP_PIANO',1 FROM lex81_norms WHERE norm_code='MANUALE_HACCP';

INSERT IGNORE INTO `lex81_obligations` (`norm_id`,`obligation_code`,`title`,`description`,`deadline_type`,`deadline_days`,`document_codes`,`course_codes`,`mission_code`,`active`) SELECT id,'OBL_ALLERGENI_SCHEDE','Redazione e aggiornamento schede allergeni','Schede allergeni disponibili per ogni piatto. In ristorazione accessibili al cliente su richiesta.','evento',NULL,'DOC_SCHEDE_ALLERGENI',NULL,'MISSION_ALLERGENI',1 FROM lex81_norms WHERE norm_code='ALLERGENI';

INSERT IGNORE INTO `lex81_obligations` (`norm_id`,`obligation_code`,`title`,`description`,`deadline_type`,`deadline_days`,`document_codes`,`course_codes`,`mission_code`,`active`) SELECT id,'OBL_PRIVACY_REGISTRO','Compilazione e aggiornamento Registro Trattamenti GDPR','Il registro contiene dati del titolare, finalita del trattamento e misure di sicurezza.','annuale',NULL,'DOC_REGISTRO_TRATTAMENTI','CORSO_PRIVACY','MISSION_GDPR_REGISTRO',1 FROM lex81_norms WHERE norm_code='REGISTRO_TRATTAMENTI';

INSERT IGNORE INTO `lex81_obligations` (`norm_id`,`obligation_code`,`title`,`description`,`deadline_type`,`deadline_days`,`document_codes`,`course_codes`,`mission_code`,`active`) SELECT id,'OBL_INFO_DIPENDENTI','Consegna informativa privacy ai dipendenti','Informativa consegnata prima o al momento dell assunzione.','immediata',NULL,'DOC_INFORMATIVA_DIPENDENTI',NULL,'MISSION_PRIVACY_DIP',1 FROM lex81_norms WHERE norm_code='INFORMATIVA_DIPENDENTI';

INSERT IGNORE INTO `lex81_obligations` (`norm_id`,`obligation_code`,`title`,`description`,`deadline_type`,`deadline_days`,`document_codes`,`course_codes`,`mission_code`,`active`) SELECT id,'OBL_INFO_CLIENTI','Disponibilita informativa privacy per i clienti','Informativa clienti disponibile prima della raccolta dati, chiara e comprensibile.','immediata',NULL,'DOC_INFORMATIVA_CLIENTI',NULL,'MISSION_PRIVACY_CLI',1 FROM lex81_norms WHERE norm_code='INFORMATIVA_CLIENTI';

-- ============================================================
-- SEED: DOCUMENTS (12 documenti)
-- ============================================================

INSERT IGNORE INTO `lex81_documents` (`doc_code`,`title`,`description`,`template_available`,`price_min`,`price_max`,`service_code`) VALUES
('DOC_DVR','Documento di Valutazione dei Rischi','DVR completo con analisi rischi, misure di prevenzione e piano di miglioramento',1,500.00,3000.00,'SVC_DVR_REDAZIONE'),
('DOC_NOMINA_RSPP','Lettera di Nomina RSPP','Documento formale di nomina del Responsabile Servizio Prevenzione e Protezione',1,50.00,200.00,'SVC_DVR_REDAZIONE'),
('DOC_REGISTRO_FORMAZIONE','Registro della Formazione','Registro con firme di presenza e attestati rilasciati',1,30.00,150.00,NULL),
('DOC_PIANO_EMERGENZA','Piano di Emergenza ed Evacuazione','Piano completo con procedure, planimetrie di evacuazione e assegnazione ruoli',1,300.00,1500.00,'SVC_EMERGENZA'),
('DOC_MANUALE_HACCP','Manuale HACCP Completo','Piano HACCP personalizzato con analisi CCP, schede di controllo e procedure igieniche',1,400.00,2500.00,'SVC_HACCP_REDAZIONE'),
('DOC_SCHEDE_ALLERGENI','Schede Informative Allergeni','Schede allergeni per ogni prodotto con i 14 allergeni principali',1,100.00,800.00,'SVC_HACCP_REDAZIONE'),
('DOC_REGISTRO_TEMPERATURE','Registro Controllo Temperature','Registro con checklist giornaliera temperature frigo, congelatori e cottura',1,20.00,80.00,NULL),
('DOC_REGISTRO_TRATTAMENTI','Registro Attivita di Trattamento GDPR','Registro completo ex Art. 30 GDPR con mappatura trattamenti e misure di sicurezza',1,200.00,1200.00,'SVC_PRIVACY'),
('DOC_INFORMATIVA_DIPENDENTI','Informativa Privacy Dipendenti','Informativa ex Art. 13 GDPR per i lavoratori dipendenti',1,80.00,300.00,'SVC_PRIVACY'),
('DOC_INFORMATIVA_CLIENTI','Informativa Privacy Clienti','Informativa ex Art. 13 GDPR per clienti e utenti del sito web',1,80.00,300.00,'SVC_PRIVACY'),
('DOC_NOMINA_PREPOSTO','Lettera di Nomina Preposto','Documento di nomina del preposto con poteri e responsabilita',1,30.00,150.00,NULL),
('DOC_DUVRI','Documento Unico di Valutazione Rischi Interferenziali','DUVRI per appalti con attivita di terzi in azienda',1,300.00,1800.00,'SVC_DVR_REDAZIONE');

-- ============================================================
-- SEED: COURSES (18 corsi)
-- ============================================================

INSERT IGNORE INTO `lex81_courses` (`course_code`,`title`,`description`,`duration_hours`,`frequency_years`,`target_roles`,`price_min`,`price_max`) VALUES
('CORSO_LAVORATORI_BASE','Formazione Lavoratori Rischio Basso (4h)','Formazione generale 4 ore per settori a rischio basso.',4,5,'lavoratori',60.00,120.00),
('CORSO_LAVORATORI_MEDIO','Formazione Lavoratori Rischio Medio (8h)','Formazione generale 4h + specifica 4h per rischio medio.',8,5,'lavoratori',100.00,180.00),
('CORSO_LAVORATORI_ALTO','Formazione Lavoratori Rischio Alto (12h)','Formazione generale 4h + specifica 8h per cantieri e produzione.',12,5,'lavoratori',140.00,250.00),
('CORSO_PREPOSTO_BASE','Formazione Preposti Corso Base (8h)','Corso obbligatorio 8 ore. Obblighi, responsabilita e gestione rischi.',8,2,'preposti,capireparto,capiturno',150.00,280.00),
('CORSO_PREPOSTO_UPDATE','Aggiornamento Biennale Preposti (6h)','Aggiornamento obbligatorio ogni 2 anni.',6,2,'preposti',100.00,200.00),
('CORSO_DIRIGENTI','Formazione Dirigenti (16h)','Corso obbligatorio per dirigenti con responsabilita in sicurezza.',16,5,'dirigenti',250.00,450.00),
('CORSO_RSPP','Formazione RSPP Modulo A+B+C','Percorso completo RSPP per datori di lavoro o professionisti.',16,5,'datoredilavoro,rspp',350.00,800.00),
('CORSO_ANTINCENDIO_BASSO','Addetti Antincendio Rischio Basso (2h)','Corso teorico-pratico per addetti antincendio a rischio basso.',2,3,'addetti_antincendio',80.00,150.00),
('CORSO_ANTINCENDIO_MEDIO','Addetti Antincendio Rischio Medio (8h)','Corso teorico-pratico per addetti antincendio a rischio medio.',8,3,'addetti_antincendio',180.00,320.00),
('CORSO_ANTINCENDIO_ALTO','Addetti Antincendio Rischio Alto (16h)','Corso completo con prove pratiche per aziende a rischio alto.',16,3,'addetti_antincendio',300.00,550.00),
('CORSO_PRIMO_SOCCORSO_A','Primo Soccorso Gruppo A (16h)','Corso 16 ore per aziende gruppo A (costruzioni, piu di 5 dipendenti).',16,3,'addetti_ps',250.00,400.00),
('CORSO_PRIMO_SOCCORSO_B','Primo Soccorso Gruppi B e C (12h)','Corso 12 ore per aziende gruppo B e C.',12,3,'addetti_ps',180.00,320.00),
('CORSO_HACCP_BASE','Formazione HACCP Base (6h)','Corso igiene alimentare per operatori di cucina, sala e magazzino.',6,3,'operatori_alimentari',80.00,160.00),
('CORSO_HACCP_RESPONSABILE','Formazione HACCP Responsabile (8h)','Corso per il responsabile HACCP. Include analisi CCP e documentazione.',8,3,'responsabile_haccp,titolare',120.00,220.00),
('CORSO_PRIVACY','Formazione Privacy e GDPR (2h)','Corso base protezione dati per dipendenti che trattano dati personali.',2,2,'dipendenti,amministrazione',60.00,120.00),
('CORSO_SALDATURA','Sicurezza nella Saldatura (4h)','Corso specifico per addetti alla saldatura. Rischi chimici, fumi e DPI.',4,5,'saldatori,officina',100.00,200.00),
('CORSO_QUOTA','Lavori in Quota (8h)','Corso per lavoratori che operano in quota. Ponteggi, scale e DPI anticaduta.',8,5,'edilizia,manutenzione',160.00,300.00),
('CORSO_CSE_CSP','Coordinatori Sicurezza Cantiere CSE/CSP (120h)','Percorso completo per Coordinatori Sicurezza in fase Esecuzione e Progettazione.',120,5,'coordinatori_cantiere,geometri,ingegneri',800.00,2000.00);

-- ============================================================
-- SEED: SERVICES (10 servizi)
-- ============================================================

INSERT IGNORE INTO `lex81_services` (`service_code`,`title`,`description`,`price_min`,`price_max`,`cta_url`,`active`) VALUES
('SVC_DVR_REDAZIONE','Redazione DVR e Valutazione Rischi','Sopralluogo, analisi rischi e redazione completa DVR personalizzato. Include nomina RSPP.',600.00,3500.00,'https://www.sicurissimo.online/servizi',1),
('SVC_EMERGENZA','Piano di Emergenza e Gestione Antincendio','Redazione piano emergenza, planimetrie evacuazione e formazione addetti.',400.00,2000.00,'https://www.sicurissimo.online/servizi',1),
('SVC_HACCP_REDAZIONE','Redazione Piano HACCP e Schede Allergeni','Piano HACCP personalizzato, schede allergeni, registro temperature e formazione team.',500.00,2800.00,'https://www.sicurissimo.online/servizi',1),
('SVC_SORVEGLIANZA_SANITARIA','Sorveglianza Sanitaria con Medico Competente','Medico competente esterno. Visite mediche preventive e periodiche.',800.00,4000.00,'https://www.sicurissimo.online/servizi',1),
('SVC_PRIVACY','Consulenza Privacy e GDPR','Mappatura trattamenti, registro Art. 30, informative e DPA.',400.00,2500.00,'https://www.sicurissimo.online/servizi',1),
('SVC_AUDIT_SICURISSIMO','Audit 81+ Sicurissimo Analisi di Conformita','Verifica completa conformita aziendale. Report dettagliato con piano azione prioritario.',300.00,1200.00,'https://www.sicurissimo.online/servizi',1),
('SVC_ISO45001','Percorso Certificazione ISO 45001:2018','Supporto completo per certificazione ISO 45001.',2000.00,8000.00,'https://www.sicurissimo.online/servizi',1),
('SVC_ISO9001','Percorso Certificazione ISO 9001:2015','Implementazione sistema gestione qualita ISO 9001.',1800.00,7000.00,'https://www.sicurissimo.online/servizi',1),
('SVC_231_MOG','Modello Organizzativo D.Lgs 231/2001','Redazione MOG 231 personalizzato. Analisi rischi reato, codice etico e Organismo Vigilanza.',3000.00,12000.00,'https://www.sicurissimo.online/servizi',1),
('SVC_FORMAZIONE_PACCHETTO','Pacchetto Formazione Completo in Azienda','Tutti i corsi obbligatori in un unico pacchetto formativo presso la tua sede.',500.00,3000.00,'https://www.sicurissimo.online/servizi',1);

-- ============================================================
-- SEED: LEADGEN81+ SEGMENTS (10 segmenti)
-- ============================================================

INSERT IGNORE INTO `leadgen81_segments` (`segment_code`,`name`,`description`,`heat_level`,`criteria_json`,`active`) VALUES
('non_profilato','Non Profilato','Contatto in ingresso senza informazioni aziendali. Priorita: profilazione.','freddo','{"ateco_code":null,"workers_count":null}',1),
('azienda','Azienda Identificata','Ha fornito nome azienda o ATECO ma manca profilazione completa.','freddo','{"azienda_presente":true,"profilo_completo":false}',1),
('profilo_completo','Profilo Completo','Ha completato ATECO, settore, numero dipendenti. Pronto per risk assessment.','tiepido','{"ateco_code_presente":true,"workers_count_gt":0}',1),
('lead_caldo','Lead Caldo','Ha chiesto informazioni, cliccato CTA o interagito negli ultimi 7 giorni.','caldo','{"last_activity_days_lt":7,"events_count_gt":2}',1),
('lead_freddo','Lead Freddo','Nessuna attivita da oltre 30 giorni. Necessita reingaggio.','freddo','{"last_activity_days_gt":30}',1),
('horeca','HO.RE.CA. Ristorazione','Ristoranti, bar, catering. Priorita HACCP, allergeni e sorveglianza sanitaria.','tiepido','{"ateco_prefix":["56","55","10","11"]}',1),
('edilizia','Edilizia e Cantieri','Imprese costruzioni e artigiani edili. Priorita PSC, POS, lavori in quota.','tiepido','{"ateco_prefix":["41","42","43"]}',1),
('rischio_alto','Azienda a Rischio Alto','Profilo ATECO critico o workers_count oltre 15. Priorita massima.','caldo','{"risk_level":["alto","critico"],"workers_count_gte":15}',1),
('vip_club','CLUB Status Elite','Clienti con status ELITE o CLUB. Servizio prioritario e accesso anticipato.','caldo','{"status":["ELITE","FRANCHISER","CLUB"]}',1),
('xmas_avvento','Avvento 81+ Campagna Speciale','Contatti iscritti alla campagna Avvento 81+. Flusso dedicato stagionale.','tiepido','{"special_flow":"FLOW_XMAS_AVVENTO_365"}',1);

-- ============================================================
-- SEED: FLOWS (25 flussi)
-- ============================================================

INSERT IGNORE INTO `leadgen81_flows` (`flow_code`,`name`,`description`,`trigger_event`,`segment_code`,`channel`,`annual_week`,`special_flow`,`active`) VALUES
('WELCOME','Benvenuto nel Sistema','Flusso di benvenuto per ogni nuovo contatto registrato','registration','non_profilato','email',NULL,NULL,1),
('SIC_ACTIVATED','Attivazione Sicurissimo OS','Flusso per chi attiva il proprio account sulla piattaforma','account_activated','azienda','email',NULL,NULL,1),
('FIRST_MISSION','Prima Missione Completata','Celebrazione e guida dopo la prima missione','mission_completed','profilo_completo','email',NULL,NULL,1),
('ATECO_DONE','Profilazione ATECO Completata','Flusso post-profilazione con contenuti mirati per settore','ateco_profiled','profilo_completo','email',NULL,NULL,1),
('HACCP_TRIGGER','Trigger HACCP Settore Alimentare','Flusso specifico per aziende con obbligo HACCP','haccp_detected','horeca','email',NULL,NULL,1),
('EDILIZIA_TRIGGER','Trigger Edilizia Cantieri','Flusso specifico per imprese edili e cantieri','edilizia_detected','edilizia','email',NULL,NULL,1),
('DVR_REMINDER','Promemoria DVR','Promemoria redazione o aggiornamento DVR','dvr_overdue','profilo_completo','email',NULL,NULL,1),
('FORMAZIONE_SCADENZA','Scadenza Formazione in Arrivo','Avviso scadenza corsi obbligatori entro 30 giorni','training_expiring','profilo_completo','email',NULL,NULL,1),
('AUDIT_DONE','Audit Completato','Follow-up dopo la presentazione del report di audit','audit_completed','profilo_completo','email',NULL,NULL,1),
('PREVENTIVO_REQUEST','Richiesta Preventivo','Flusso post-richiesta di preventivo. Qualifica e chiusura.','quote_requested','lead_caldo','email',NULL,NULL,1),
('ACADEMY_START','Accesso alla Academy','Onboarding nella piattaforma corsi online','academy_enrolled','profilo_completo','email',NULL,NULL,1),
('STREAK_7','Streak 7 Giorni','Celebrazione 7 giorni consecutivi di attivita','streak_7',NULL,'email',NULL,NULL,1),
('STREAK_30','Streak 30 Giorni','Celebrazione 30 giorni consecutivi. Proposta upgrade status.','streak_30',NULL,'email',NULL,NULL,1),
('STREAK_81','Streak 81 Giorni','Traguardo 81 giorni. Massima celebrazione. Status ELITE.','streak_81',NULL,'email',NULL,NULL,1),
('INACTIVITY_7D','Reingaggio 7 Giorni di Inattivita','Flusso di riattivazione per contatti inattivi da 7 giorni','inactivity_7d','lead_freddo','email',NULL,NULL,1),
('INACTIVITY_30D','Reingaggio 30 Giorni di Inattivita','Ultimo tentativo di riattivazione prima della pulizia database','inactivity_30d','lead_freddo','email',NULL,NULL,1),
('LIFEWHEEL_CAP','LifeWheel Completato','Flusso post-completamento ruota della vita aziendale','lifewheel_completed','profilo_completo','email',NULL,NULL,1),
('STATUS_UPGRADE','Upgrade di Status','Notifica e celebrazione avanzamento status','status_upgraded',NULL,'email',NULL,NULL,1),
('BOOSTER_PROMO','Booster Promozionale','Offerta temporanea per accelerare la conversione','booster_triggered','lead_caldo','email',NULL,NULL,1),
('NETWORKER_ATTIVATO','Networker Attivato','Flusso per chi entra nel programma NETWORKERS','networker_enrolled','vip_club','email',NULL,NULL,1),
('ISPEZIONI_PRIMAVERA','Alert Ispezioni di Primavera','Campagna stagionale: aumento ispezioni ASL e Ispettorato nel periodo marzo-maggio','annual_campaign',NULL,'email',10,NULL,1),
('FERRAGOSTO_PREP','Preparazione Chiusura Estiva','Checklist pre-chiusura estiva: documenti, scadenze e riapertura sicura','annual_campaign',NULL,'email',30,NULL,1),
('SETTEMBRE_RIPARTENZA','Ripartenza di Settembre','Campagna di settembre: aggiornamento normativo e nuovi corsi','annual_campaign',NULL,'email',36,NULL,1),
('FINE_ANNO_CHECKLIST','Checklist Fine Anno','Verifica adempimenti annuali, scadenze formazione e rinnovi dicembre','annual_campaign',NULL,'email',50,NULL,1),
('FLOW_XMAS_AVVENTO_365','Avvento 81+ 81 Porte Normative','Campagna speciale avvento: 81 giorni, 81 contenuti normativi.','xmas_campaign','xmas_avvento','email',48,'FLOW_XMAS_AVVENTO_365',1);

-- ============================================================
-- SEED: FLOW STEPS (8 step principali)
-- ============================================================

INSERT IGNORE INTO `leadgen81_flow_steps` (`flow_id`,`step_number`,`step_type`,`delay_hours`,`subject`,`body_html`,`body_text`,`cta_label`,`cta_url`,`from_name`,`from_email`,`active`)
SELECT f.id,1,'email',0,'Benvenuto in Sicurissimo OS, il tuo sistema di sicurezza aziendale',
'<p>Ciao [NOME],</p><p>Sei entrato nel sistema.</p><p>Sicurissimo OS e il primo agente agentico italiano per la sicurezza aziendale.</p><p>Mentre tu lavori, il sistema monitora le tue scadenze, aggiorna i tuoi documenti e ti avvisa prima che arrivi un ispettore.</p><p>Hai un dipendente? Hai obblighi di legge. Ignorarli costa fino a 4.914 euro di ammenda o 6 mesi di arresto.</p><p>Il primo passo e conoscere la tua situazione reale. Completa il profilo aziendale. Ci vogliono 3 minuti.</p><p>Nicolas<br>Co-Fondatore | Sicurissimo OS</p>',
'Ciao [NOME],

Sei entrato nel sistema.

Sicurissimo OS e il primo agente agentico italiano per la sicurezza aziendale.

Mentre tu lavori, il sistema monitora le tue scadenze, aggiorna i tuoi documenti e ti avvisa prima che arrivi un ispettore.

Hai un dipendente? Hai obblighi di legge. Ignorarli costa fino a 4.914 euro di ammenda o 6 mesi di arresto.

Il primo passo e conoscere la tua situazione reale. Completa il profilo aziendale. Ci vogliono 3 minuti.

Nicolas
Co-Fondatore | Sicurissimo OS',
'Completa il tuo profilo aziendale','https://www.sicurissimo.online/risorse-gratuite',
'Nicolas | Sicurissimo OS','nicolas@sicurissimo.online',1
FROM leadgen81_flows f WHERE f.flow_code='WELCOME';

INSERT IGNORE INTO `leadgen81_flow_steps` (`flow_id`,`step_number`,`step_type`,`delay_hours`,`subject`,`body_html`,`body_text`,`cta_label`,`cta_url`,`from_name`,`from_email`,`active`)
SELECT f.id,2,'email',24,'Una cosa che la maggior parte degli imprenditori non sa',
'<p>Ciao [NOME],</p><p>Ieri hai fatto il primo passo.</p><p>Il 78% delle sanzioni in sicurezza colpisce aziende che pensavano di essere in regola.</p><p>Non e ignoranza della legge. E la distanza tra quello che si pensa di avere e quello che si ha davvero.</p><p>Il DVR vecchio di 3 anni. Il corso di formazione scaduto. Il piano di emergenza mai aggiornato.</p><p>Sicurissimo OS analizza la tua situazione in meno di 2 minuti e ti dice dove sei esposto.</p><p>Parti da qui. E gratuito.</p><p>Nicolas<br>Co-Fondatore | Sicurissimo OS</p>',
'Ciao [NOME],

Ieri hai fatto il primo passo.

Il 78% delle sanzioni in sicurezza colpisce aziende che pensavano di essere in regola.

Non e ignoranza della legge. E la distanza tra quello che si pensa di avere e quello che si ha davvero.

Il DVR vecchio di 3 anni. Il corso di formazione scaduto. Il piano di emergenza mai aggiornato.

Sicurissimo OS analizza la tua situazione in meno di 2 minuti e ti dice dove sei esposto.

Parti da qui. E gratuito.

Nicolas
Co-Fondatore | Sicurissimo OS',
'Scopri dove sei esposto - gratis','https://www.sicurissimo.online/risorse-gratuite',
'Nicolas | Sicurissimo OS','nicolas@sicurissimo.online',1
FROM leadgen81_flows f WHERE f.flow_code='WELCOME';

INSERT IGNORE INTO `leadgen81_flow_steps` (`flow_id`,`step_number`,`step_type`,`delay_hours`,`subject`,`body_html`,`body_text`,`cta_label`,`cta_url`,`from_name`,`from_email`,`active`)
SELECT f.id,3,'email',72,'Qual e il tuo settore? Ti costruisco il profilo di rischio',
'<p>Ciao [NOME],</p><p>Tre giorni fa sei entrato in Sicurissimo OS.</p><p>Voglio costruire il tuo profilo di rischio personalizzato.</p><p>Ho bisogno di due informazioni: il tuo settore ATECO e il numero di dipendenti.</p><p>Con questi dati identifico le norme che si applicano alla tua azienda, le sanzioni a cui sei esposto e le azioni da fare subito.</p><p>Clicca qui. Rispondi a 3 domande. Ricevi il tuo report in 60 secondi.</p><p>Nicolas<br>Co-Fondatore | Sicurissimo OS</p>',
'Ciao [NOME],

Tre giorni fa sei entrato in Sicurissimo OS.

Voglio costruire il tuo profilo di rischio personalizzato.

Ho bisogno di due informazioni: il tuo settore ATECO e il numero di dipendenti.

Con questi dati identifico le norme che si applicano alla tua azienda, le sanzioni a cui sei esposto e le azioni da fare subito.

Clicca qui. Rispondi a 3 domande. Ricevi il tuo report in 60 secondi.

Nicolas
Co-Fondatore | Sicurissimo OS',
'Crea il mio profilo di rischio','https://www.sicurissimo.online/risorse-gratuite',
'Nicolas | Sicurissimo OS','nicolas@sicurissimo.online',1
FROM leadgen81_flows f WHERE f.flow_code='WELCOME';

INSERT IGNORE INTO `leadgen81_flow_steps` (`flow_id`,`step_number`,`step_type`,`delay_hours`,`subject`,`body_html`,`body_text`,`cta_label`,`cta_url`,`from_name`,`from_email`,`active`)
SELECT f.id,1,'email',0,'Sei nel settore alimentare. Hai il piano HACCP aggiornato?',
'<p>Ciao [NOME],</p><p>Ho visto che operi nel settore alimentare.</p><p>Questo vuol dire che hai obblighi HACCP da rispettare ogni giorno.</p><p>Piano HACCP, formazione del personale, schede allergeni e registro temperature.</p><p>Una visita dei NAS senza questi documenti significa chiusura immediata e ammenda fino a 6.000 euro.</p><p>Il sistema ha preparato per te la checklist HACCP completa. E gratuita.</p><p>Scaricala adesso. Scopri in 5 minuti se sei in regola.</p><p>Nicolas<br>Co-Fondatore | Sicurissimo OS</p>',
'Ciao [NOME],

Ho visto che operi nel settore alimentare.

Questo vuol dire che hai obblighi HACCP da rispettare ogni giorno.

Piano HACCP, formazione del personale, schede allergeni e registro temperature.

Una visita dei NAS senza questi documenti significa chiusura immediata e ammenda fino a 6.000 euro.

Il sistema ha preparato per te la checklist HACCP completa. E gratuita.

Scaricala adesso. Scopri in 5 minuti se sei in regola.

Nicolas
Co-Fondatore | Sicurissimo OS',
'Scarica la checklist HACCP gratuita','https://www.sicurissimo.online/risorse-gratuite',
'Nicolas | Sicurissimo OS','nicolas@sicurissimo.online',1
FROM leadgen81_flows f WHERE f.flow_code='HACCP_TRIGGER';

INSERT IGNORE INTO `leadgen81_flow_steps` (`flow_id`,`step_number`,`step_type`,`delay_hours`,`subject`,`body_html`,`body_text`,`cta_label`,`cta_url`,`from_name`,`from_email`,`active`)
SELECT f.id,2,'email',48,'Il caso del ristorante chiuso in 20 minuti',
'<p>Ciao [NOME],</p><p>Ti racconto una storia vera.</p><p>Un ristorante di Milano. 12 dipendenti. 8 anni di attivita.</p><p>I NAS entrano alle 11:30. Chiedono il piano HACCP, le schede allergeni e il registro temperature.</p><p>Il piano HACCP era scaduto da 14 mesi. Le schede allergeni non erano aggiornate. Il registro temperature era vuoto.</p><p>Alle 11:50 il ristorante e chiuso. Sanzione: 5.500 euro. Riqualificazione: 3 settimane.</p><p>Puoi evitarlo con il piano HACCP corretto. Parla con me adesso.</p><p>Nicolas<br>Co-Fondatore | Sicurissimo OS</p>',
'Ciao [NOME],

Ti racconto una storia vera.

Un ristorante di Milano. 12 dipendenti. 8 anni di attivita.

I NAS entrano alle 11:30. Chiedono il piano HACCP, le schede allergeni e il registro temperature.

Il piano HACCP era scaduto da 14 mesi. Le schede allergeni non erano aggiornate. Il registro temperature era vuoto.

Alle 11:50 il ristorante e chiuso. Sanzione: 5.500 euro. Riqualificazione: 3 settimane.

Puoi evitarlo con il piano HACCP corretto. Parla con me adesso.

Nicolas
Co-Fondatore | Sicurissimo OS',
'Parla con Nicolas su WhatsApp','https://wa.me/3388771737',
'Nicolas | Sicurissimo OS','nicolas@sicurissimo.online',1
FROM leadgen81_flows f WHERE f.flow_code='HACCP_TRIGGER';

INSERT IGNORE INTO `leadgen81_flow_steps` (`flow_id`,`step_number`,`step_type`,`delay_hours`,`subject`,`body_html`,`body_text`,`cta_label`,`cta_url`,`from_name`,`from_email`,`active`)
SELECT f.id,1,'email',0,'[NOME], sono passati 7 giorni - sei ancora qui?',
'<p>Ciao [NOME],</p><p>Sono Nicolas.</p><p>Sono passati 7 giorni dal tuo ultimo accesso a Sicurissimo OS.</p><p>Capisco. Gestire una azienda assorbe tutto il tempo e l energia che hai.</p><p>Ma la normativa non si ferma mentre sei occupato.</p><p>Ci vogliono 5 minuti per sapere se sei in regola oggi. Non domani.</p><p>Torna, completa il tuo profilo e ricevi il report di rischio gratuito.</p><p>Ti aspetto.</p><p>Nicolas<br>Co-Fondatore | Sicurissimo OS</p>',
'Ciao [NOME],

Sono Nicolas.

Sono passati 7 giorni dal tuo ultimo accesso a Sicurissimo OS.

Capisco. Gestire una azienda assorbe tutto il tempo e l energia che hai.

Ma la normativa non si ferma mentre sei occupato.

Ci vogliono 5 minuti per sapere se sei in regola oggi. Non domani.

Torna, completa il tuo profilo e ricevi il report di rischio gratuito.

Ti aspetto.

Nicolas
Co-Fondatore | Sicurissimo OS',
'Riprendi il tuo percorso','https://www.sicurissimo.online/risorse-gratuite',
'Nicolas | Sicurissimo OS','nicolas@sicurissimo.online',1
FROM leadgen81_flows f WHERE f.flow_code='INACTIVITY_7D';

INSERT IGNORE INTO `leadgen81_flow_steps` (`flow_id`,`step_number`,`step_type`,`delay_hours`,`subject`,`body_html`,`body_text`,`cta_label`,`cta_url`,`from_name`,`from_email`,`active`)
SELECT f.id,1,'email',0,'Apri la Prima Porta - 81 giorni, 81 segreti normativi',
'<p>Ciao [NOME],</p><p>Benvenuto nell Avvento 81+.</p><p>Per 81 giorni ti aspetta una porta da aprire.</p><p>Ogni porta contiene un contenuto che ti aiuta a proteggere la tua azienda, risparmiare tempo e dormire piu sereno.</p><p>Oggi: la prima porta e aperta. Dentro trovi il tuo tip normativo di benvenuto.</p><p>Apri adesso. Il contenuto e riservato solo a te.</p><p>Nicolas<br>Co-Fondatore | Sicurissimo OS</p>',
'Ciao [NOME],

Benvenuto nell Avvento 81+.

Per 81 giorni ti aspetta una porta da aprire.

Ogni porta contiene un contenuto che ti aiuta a proteggere la tua azienda, risparmiare tempo e dormire piu sereno.

Oggi: la prima porta e aperta. Dentro trovi il tuo tip normativo di benvenuto.

Apri adesso. Il contenuto e riservato solo a te.

Nicolas
Co-Fondatore | Sicurissimo OS',
'Apri la Prima Porta','https://81plus.christmas',
'Nicolas | Sicurissimo OS','nicolas@sicurissimo.online',1
FROM leadgen81_flows f WHERE f.flow_code='FLOW_XMAS_AVVENTO_365';

COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

-- Fine Schema 81+ Global OS v1.0.0
-- Tabelle: 17 (8 LEX81+ + 9 LEADGEN81+)
-- Seed: 10 categorie, 24 norme, 9 sanzioni, 10 segmenti, 25 flussi, 8 step email
-- Supporto: WhatsApp 3388771737 | https://www.sicurissimo.online
