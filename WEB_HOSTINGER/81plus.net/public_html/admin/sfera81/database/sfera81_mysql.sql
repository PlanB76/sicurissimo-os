-- SFERA81+ V5 — Schema MySQL completo
-- 81+ GLOBAL — HUB 06 — 2026-06-19
-- Import: phpMyAdmin → seleziona database → Import → sfera81_mysql.sql

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
CREATE TABLE `sfera_user_profile` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `sic_id` varchar(30) NOT NULL DEFAULT '',
  `status` enum('MEMBER81+','NETWORKERS81+','ELITE81+','FRANCHISER81+','CLUB81+') NOT NULL DEFAULT 'MEMBER81+',
  `user_type` enum('azienda','persona','networker') NOT NULL DEFAULT 'azienda',
  `ateco_code` varchar(10) DEFAULT NULL,
  `macro_sector` varchar(50) DEFAULT NULL,
  `micro_sector` varchar(80) DEFAULT NULL,
  `risk_level` enum('basso','medio','alto') NOT NULL DEFAULT 'medio',
  `haccp_applicable` tinyint(1) NOT NULL DEFAULT 0,
  `privacy_applicable` tinyint(1) NOT NULL DEFAULT 1,
  `safety_applicable` tinyint(1) NOT NULL DEFAULT 1,
  `company_size` int DEFAULT 1,
  `current_escalation_level` int NOT NULL DEFAULT 1,
  `daily_streak` int NOT NULL DEFAULT 0,
  `last_access_date` date DEFAULT NULL,
  `pvplus_balance` int NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `sic_id` (`sic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE `sfera_daily_access` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `sic_id` varchar(30) NOT NULL,
  `access_date` date NOT NULL,
  `reward_type` varchar(30) NOT NULL DEFAULT 'DAILY_ACCESS',
  `reward_amount` int NOT NULL DEFAULT 1,
  `promo_id` int DEFAULT NULL,
  `booster_active` tinyint(1) NOT NULL DEFAULT 0,
  `anti_duplicate_key` varchar(100) NOT NULL,
  `ip_hash` varchar(64) DEFAULT NULL,
  `user_agent_hash` varchar(64) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `anti_duplicate_key` (`anti_duplicate_key`),
  KEY `user_id_date` (`user_id`,`access_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE `sfera_promos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `promo_code` varchar(50) NOT NULL,
  `promo_name` varchar(100) NOT NULL,
  `status` enum('active','inactive','expired') NOT NULL DEFAULT 'inactive',
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `reward_type` varchar(30) NOT NULL DEFAULT 'BOOSTER81+',
  `reward_amount` int NOT NULL DEFAULT 50,
  `monthly_slot` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int NOT NULL DEFAULT 1,
  `approved_by` int DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `promo_code` (`promo_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE `sfera_promo_redemptions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `promo_id` int NOT NULL,
  `user_id` int NOT NULL,
  `sic_id` varchar(30) NOT NULL,
  `access_date` date NOT NULL,
  `reward_amount` int NOT NULL DEFAULT 50,
  `anti_duplicate_key` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `anti_duplicate_key` (`anti_duplicate_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE `sfera_missions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mission_code` varchar(50) NOT NULL,
  `mission_name` varchar(120) NOT NULL,
  `mission_desc` text,
  `game_code` varchar(30) DEFAULT NULL,
  `hub` int NOT NULL DEFAULT 6,
  `status_min` enum('MEMBER81+','NETWORKERS81+','ELITE81+','FRANCHISER81+','CLUB81+') NOT NULL DEFAULT 'MEMBER81+',
  `haccp_required` tinyint(1) NOT NULL DEFAULT 0,
  `privacy_required` tinyint(1) NOT NULL DEFAULT 0,
  `safety_required` tinyint(1) NOT NULL DEFAULT 0,
  `frequency` enum('once','daily','weekly','monthly') NOT NULL DEFAULT 'once',
  `pvplus_reward` int NOT NULL DEFAULT 5,
  `daily_cap_group` enum('MEMBER81+','NETWORKERS81+','ELITE81+','FRANCHISER81+','CLUB81+') NOT NULL DEFAULT 'MEMBER81+',
  `required_once` tinyint(1) NOT NULL DEFAULT 1,
  `repeatable` tinyint(1) NOT NULL DEFAULT 0,
  `escalation_level` int NOT NULL DEFAULT 1,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mission_code` (`mission_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE `sfera_user_missions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `mission_id` int NOT NULL,
  `status` enum('open','in_progress','completed') NOT NULL DEFAULT 'open',
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `pvplus_awarded` int NOT NULL DEFAULT 0,
  `completion_note` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_mission` (`user_id`,`mission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE `sfera_escalation_levels` (
  `id` int NOT NULL AUTO_INCREMENT,
  `level_number` int NOT NULL,
  `maslow_level` varchar(60) NOT NULL,
  `level_code` varchar(30) NOT NULL,
  `level_name` varchar(60) NOT NULL,
  `objective` varchar(120) NOT NULL,
  `color` varchar(10) NOT NULL,
  `pvplus_reward` int NOT NULL DEFAULT 100,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `level_number` (`level_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE `sfera_lifewheel_areas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `life_area_name` varchar(60) NOT NULL,
  `sfera_area_code` varchar(30) NOT NULL,
  `sfera_area_name` varchar(60) NOT NULL,
  `haccp_sensitive` tinyint(1) NOT NULL DEFAULT 0,
  `privacy_sensitive` tinyint(1) NOT NULL DEFAULT 0,
  `safety_sensitive` tinyint(1) NOT NULL DEFAULT 0,
  `color` varchar(10) NOT NULL DEFAULT '#FFFFFF',
  `sort_order` int NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE `sfera_lifewheel_progress` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `area_id` int NOT NULL,
  `score` int NOT NULL DEFAULT 0,
  `status_cap` int NOT NULL DEFAULT 6,
  `last_completed_at` datetime DEFAULT NULL,
  `radar_priority` int NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_area` (`user_id`,`area_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE `sfera_event_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `sic_id` varchar(30) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `source_module` varchar(50) DEFAULT NULL,
  `source_id` int DEFAULT NULL,
  `ateco_code` varchar(10) DEFAULT NULL,
  `risk_level` varchar(10) DEFAULT NULL,
  `pvplus_delta` int NOT NULL DEFAULT 0,
  `metadata_json` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `event_type` (`event_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE `sfera_reward_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `sic_id` varchar(30) NOT NULL,
  `reward_type` varchar(30) NOT NULL,
  `reward_amount` int NOT NULL DEFAULT 0,
  `reason` varchar(120) DEFAULT NULL,
  `source_module` varchar(50) DEFAULT NULL,
  `promo_id` int DEFAULT NULL,
  `anti_duplicate_key` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `anti_duplicate_key` (`anti_duplicate_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE `leadgen_flows` (
  `id` int NOT NULL AUTO_INCREMENT,
  `flow_code` varchar(50) NOT NULL,
  `flow_name` varchar(100) NOT NULL,
  `trigger_event` varchar(50) NOT NULL,
  `target_segment` varchar(80) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `flow_code` (`flow_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE `leadgen_events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `sic_id` varchar(30) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `source` varchar(50) DEFAULT NULL,
  `segment` varchar(80) DEFAULT NULL,
  `metadata_json` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `segment` (`segment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================================
-- SEED DATA
-- ========================================================

INSERT INTO `sfera_escalation_levels` (`level_number`,`maslow_level`,`level_code`,`level_name`,`objective`,`color`,`pvplus_reward`) VALUES
(1,'Bisogni Fisiologici','IDENTITA81','IDENTITÀ81+','La tua base 81+ è attiva.','#3FBF6B',100),
(2,'Bisogni di Sicurezza','ATECO81','ATECO81+','Ora sai da dove partire.','#00D9FF',200),
(3,'Bisogni di Appartenenza','OBBLIGHI81','OBBLIGHI81+','Ora hai una mappa chiara degli obblighi.','#E8501A',350),
(4,'Bisogni di Stima','AZIONE81','AZIONE81+','Stai trasformando controllo in azione.','#8A4AE8',600),
(5,'Bisogni di Autorealizzazione','PRESIDIO81','PRESIDIO81+','Stai presidiando il tuo sistema.','#FFD24A',1000);

INSERT INTO `sfera_lifewheel_areas` (`life_area_name`,`sfera_area_code`,`sfera_area_name`,`safety_sensitive`,`haccp_sensitive`,`privacy_sensitive`,`color`,`sort_order`) VALUES
('Salute e Benessere Fisico','SICUREZZA_LAVORO','sicurezza lavoro',1,0,0,'#00E676',1),
('Carriera e Lavoro','CRM_CRESCITA','CRM e crescita',0,0,0,'#00D9FF',2),
('Finanze e Denaro','WALLET_PV','wallet e PV+',0,0,0,'#FFD24A',3),
('Crescita Personale','FORMAZIONE','formazione',0,0,0,'#9B6BFF',4),
('Ambiente e Spazio Fisico','DOCUMENTI','documenti',1,1,1,'#FF6B35',5),
('Relazioni e Amore','NETWORK_RELAZIONI','network e relazioni',0,0,0,'#FF4081',6),
('Famiglia e Amici','IDENTITA_PROFILO','identità e profilo',0,0,0,'#69F0AE',7),
('Svago e Divertimento','VISIONE_CONTINUITA','visione e continuità',0,0,0,'#40C4FF',8);

INSERT INTO `sfera_missions` (`mission_code`,`mission_name`,`mission_desc`,`game_code`,`pvplus_reward`,`frequency`,`required_once`,`repeatable`,`escalation_level`,`safety_required`) VALUES
('DAILY_ACCESS','Accesso giornaliero','Accedi ogni giorno per ricevere 1 PV+.','DAILY_SPARK81',1,'daily',0,1,1,0),
('COMPLETE_PROFILE','Completa il profilo','Inserisci tutti i dati del tuo profilo.','ESCALATION81',5,'once',1,0,1,0),
('VERIFY_EMAIL','Verifica email','Verifica il tuo indirizzo email.','ESCALATION81',3,'once',1,0,1,0),
('VERIFY_PHONE','Verifica telefono','Verifica il tuo numero di telefono.','ESCALATION81',3,'once',1,0,1,0),
('ACTIVATE_SIC_ID','Attiva SIC-ID','Attiva il tuo identificativo SIC-ID 81+.','ESCALATION81',10,'once',1,0,1,0),
('ACCEPT_RULES','Accetta regole e privacy','Leggi e accetta regole e informativa privacy.','ESCALATION81',2,'once',1,0,1,0),
('INSERT_ATECO','Inserisci ATECO','Inserisci il codice ATECO della tua attività.','ESCALATION81',8,'once',1,0,2,0),
('INSERT_LAVORATORI','Indica numero lavoratori','Indica quante persone lavorano nella tua azienda.','ESCALATION81',5,'once',1,0,2,0),
('INDICA_CANTIERE','Indica se lavori in cantiere','Specifica se la tua attività prevede lavori in cantiere.','ESCALATION81',3,'once',1,0,2,0),
('INDICA_ALIMENTI','Indica se tratti alimenti','Specifica se la tua attività prevede trattamento alimenti.','ESCALATION81',3,'once',1,0,2,0),
('GENERA_PROFILO_RISCHIO','Genera profilo rischio','Completa il wizard per generare il tuo profilo ATECO/RISCHIO.','ESCALATION81',15,'once',1,0,2,0),
('CHECK_DVR','Check DVR','Verifica lo stato del Documento di Valutazione dei Rischi.','SHIELD81',10,'once',1,0,3,1),
('CHECK_FORMAZIONE','Check formazione lavoratori','Verifica lo stato della formazione dei lavoratori.','SHIELD81',10,'once',1,0,3,1),
('CHECK_PRIVACY_POLICY','Check privacy policy','Verifica che la privacy policy sia presente e aggiornata.','PRIVACY_LOCK81',8,'once',1,0,3,1),
('CHECK_HACCP','Check manuale HACCP','Verifica lo stato del manuale HACCP.','HACCP_KITCHEN81',10,'once',1,0,3,1),
('COMPLETE_AUDIT','Completa audit SICURISSIMO','Esegui un audit completo con SICURISSIMO.','SHIELD81',50,'once',1,0,4,1),
('COMPLETE_MICROLEARNING','Completa microlearning sicurezza','Completa un modulo di formazione sulla sicurezza.','ACADEMY_QUEST81',10,'monthly',0,1,4,1),
('STREAK_7','Streak 7 giorni','Accedi per 7 giorni consecutivi.','DAILY_SPARK81',100,'once',0,1,4,0),
('STREAK_30','Streak 30 giorni','Accedi per 30 giorni consecutivi.','DAILY_SPARK81',500,'once',0,1,4,0),
('STREAK_81','Streak 81 giorni','Accedi per 81 giorni consecutivi.','DAILY_SPARK81',1500,'once',0,1,5,0),
('PIANO_30_GIORNI','Completa piano 30 giorni','Completa tutte le missioni del piano 30 giorni.','ESCALATION81',200,'once',1,0,5,0),
('PIANO_81_GIORNI','Completa piano 81 giorni','Completa tutte le missioni del piano 81 giorni.','ESCALATION81',500,'once',1,0,5,0);

INSERT INTO `leadgen_flows` (`flow_code`,`flow_name`,`trigger_event`,`target_segment`,`status`) VALUES
('WELCOME','Benvenuto 81+','registration','non_profilato','active'),
('ACTIVATE_SIC','Attivazione SIC-ID','sic_activation','non_profilato','active'),
('FIRST_MISSION','Prima missione completata','mission_complete','profilo_completo','active'),
('AUDIT_SUGGEST','Suggerimento audit','ateco_profiled','azienda','active'),
('REACTIVATION','Riattivazione lead freddo','inactivity_30d','lead_freddo','active');

-- Demo user per test
INSERT INTO `sfera_user_profile` (`user_id`,`sic_id`,`status`,`user_type`,`ateco_code`,`macro_sector`,`micro_sector`,`risk_level`,`haccp_applicable`,`privacy_applicable`,`safety_applicable`,`company_size`,`current_escalation_level`,`pvplus_balance`) VALUES
(1,'SIC-DEMO-81','MEMBER81+','azienda','41.20','Costruzioni','Lavori di costruzione di edifici residenziali e non residenziali','alto',0,1,1,5,1,0);

SET FOREIGN_KEY_CHECKS = 1;
