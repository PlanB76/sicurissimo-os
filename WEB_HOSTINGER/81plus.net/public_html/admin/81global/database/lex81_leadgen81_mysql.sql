-- =============================================================================
-- 81PLUS GLOBAL OS — Schema MySQL Completo
-- File:    lex81_leadgen81_mysql.sql
-- Versione: 1.0.0
-- Data:    2026-06-20
-- Autore:  Nicolas — Co-Fondatore Artificiale, Sicurissimo OS
-- =============================================================================
--
-- ISTRUZIONI:
-- 1. Crea il database MySQL e impostane il nome prima di eseguire questo file.
-- 2. Decommenta la riga CREATE DATABASE / USE qui sotto e sostituisci
--    "nome_del_tuo_database" con il nome reale del database.
-- 3. Esegui il file tramite riga di comando o phpMyAdmin.
--
-- CREATE DATABASE IF NOT EXISTS `nome_del_tuo_database`
--   CHARACTER SET utf8mb4
--   COLLATE utf8mb4_unicode_ci;
-- USE `nome_del_tuo_database`;
--
-- =============================================================================

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET collation_connection = utf8mb4_unicode_ci;

-- Disabilita controlli FK durante la creazione per evitare errori di ordine
SET FOREIGN_KEY_CHECKS = 0;

START TRANSACTION;

-- =============================================================================
-- SEZIONE 1 — LEX81+
-- Norme, categorie, obblighi, sanzioni, documenti, corsi e servizi
-- =============================================================================

-- -----------------------------------------------------------------------------
-- Tabella 1: lex81_categories — Categorie normative
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lex81_categories` (
    `id`          INT            NOT NULL AUTO_INCREMENT,
    `code`        VARCHAR(20)    NOT NULL,
    `name`        VARCHAR(100)   NOT NULL,
    `description` TEXT,
    `icon`        VARCHAR(50),
    `color`       VARCHAR(7),
    `sort_order`  INT            NOT NULL DEFAULT 0,
    `created_at`  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_lex81_categories_code` (`code`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Categorie normative del sistema 81PLUS';

-- -----------------------------------------------------------------------------
-- Tabella 2: lex81_norms — Norme di legge
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lex81_norms` (
    `id`                 INT              NOT NULL AUTO_INCREMENT,
    `code`               VARCHAR(30)      NOT NULL,
    `title`              VARCHAR(200)     NOT NULL,
    `full_title`         TEXT,
    `category_code`      VARCHAR(20)      NOT NULL,
    `decree_date`        DATE,
    `gazzetta_ufficiale` VARCHAR(50),
    `min_workers`        INT              NOT NULL DEFAULT 1,
    `max_workers`        INT              DEFAULT NULL COMMENT 'NULL = nessun limite massimo',
    `haccp_only`         TINYINT(1)       NOT NULL DEFAULT 0,
    `edilizia_only`      TINYINT(1)       NOT NULL DEFAULT 0,
    `priority`           ENUM('CRITICA','ALTA','MEDIA','BASSA') NOT NULL DEFAULT 'ALTA',
    `summary`            TEXT,
    `penalty_max`        DECIMAL(10,2),
    `created_at`         TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_lex81_norms_code` (`code`),
    CONSTRAINT `fk_lex81_norms_category`
        FOREIGN KEY (`category_code`) REFERENCES `lex81_categories` (`code`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Norme di legge mappate per area di rischio';

-- -----------------------------------------------------------------------------
-- Tabella 3: lex81_ateco_map — Mapping codici ATECO -> norme applicabili
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lex81_ateco_map` (
    `id`           INT         NOT NULL AUTO_INCREMENT,
    `ateco_prefix` VARCHAR(10) NOT NULL,
    `norm_code`    VARCHAR(30) NOT NULL,
    `mandatory`    TINYINT(1)  NOT NULL DEFAULT 1,
    `notes`        TEXT,
    PRIMARY KEY (`id`),
    KEY `idx_lex81_ateco_map_prefix` (`ateco_prefix`),
    UNIQUE KEY `uq_lex81_ateco_map_prefix_norm` (`ateco_prefix`, `norm_code`),
    CONSTRAINT `fk_lex81_ateco_map_norm`
        FOREIGN KEY (`norm_code`) REFERENCES `lex81_norms` (`code`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Relazione tra codici ATECO e norme obbligatorie applicabili';

-- -----------------------------------------------------------------------------
-- Tabella 4: lex81_obligations — Obblighi specifici per norma
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lex81_obligations` (
    `id`                INT         NOT NULL AUTO_INCREMENT,
    `norm_code`         VARCHAR(30) NOT NULL,
    `obligation_code`   VARCHAR(50) NOT NULL,
    `title`             VARCHAR(200) NOT NULL,
    `description`       TEXT,
    `deadline_type`     ENUM('IMMEDIATO','ANNUALE','BIENNALE','QUINQUENNALE','UNICO') NOT NULL DEFAULT 'UNICO',
    `responsible`       ENUM('DATORE_LAVORO','RSPP','MEDICO_COMPETENTE','RLS','PREPOSTO') NOT NULL DEFAULT 'DATORE_LAVORO',
    `min_workers`       INT         NOT NULL DEFAULT 1,
    `document_required` TINYINT(1)  NOT NULL DEFAULT 0,
    `created_at`        TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_lex81_obligations_code` (`obligation_code`),
    KEY `idx_lex81_obligations_norm` (`norm_code`),
    CONSTRAINT `fk_lex81_obligations_norm`
        FOREIGN KEY (`norm_code`) REFERENCES `lex81_norms` (`code`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Obblighi specifici associati a ciascuna norma';

-- -----------------------------------------------------------------------------
-- Tabella 5: lex81_sanctions — Sanzioni amministrative e penali
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lex81_sanctions` (
    `id`                    INT             NOT NULL AUTO_INCREMENT,
    `norm_code`             VARCHAR(30)     NOT NULL,
    `article`               VARCHAR(50),
    `violation_description` TEXT            NOT NULL,
    `sanction_type`         ENUM('AMMINISTRATIVA','PENALE','ARRESTO','SOSPENSIONE_ATTIVITA') NOT NULL,
    `amount_min`            DECIMAL(10,2),
    `amount_max`            DECIMAL(10,2),
    `arrest_months_min`     INT,
    `arrest_months_max`     INT,
    `validated`             TINYINT(1)      NOT NULL DEFAULT 1,
    `source_url`            VARCHAR(500),
    `created_at`            TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_lex81_sanctions_norm` (`norm_code`),
    CONSTRAINT `fk_lex81_sanctions_norm`
        FOREIGN KEY (`norm_code`) REFERENCES `lex81_norms` (`code`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Sanzioni amministrative e penali per violazione delle norme';

-- -----------------------------------------------------------------------------
-- Tabella 6: lex81_documents — Documenti obbligatori
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lex81_documents` (
    `id`                 INT          NOT NULL AUTO_INCREMENT,
    `norm_code`          VARCHAR(30)  NOT NULL,
    `document_code`      VARCHAR(50)  NOT NULL,
    `name`               VARCHAR(200) NOT NULL,
    `description`        TEXT,
    `template_available` TINYINT(1)   NOT NULL DEFAULT 0,
    `download_url`       VARCHAR(500),
    `renewal_months`     INT,
    `created_at`         TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_lex81_documents_code` (`document_code`),
    KEY `idx_lex81_documents_norm` (`norm_code`),
    CONSTRAINT `fk_lex81_documents_norm`
        FOREIGN KEY (`norm_code`) REFERENCES `lex81_norms` (`code`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Documenti obbligatori richiesti per ogni norma';

-- -----------------------------------------------------------------------------
-- Tabella 7: lex81_courses — Corsi di formazione obbligatoria
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lex81_courses` (
    `id`             INT          NOT NULL AUTO_INCREMENT,
    `norm_code`      VARCHAR(30)  NOT NULL,
    `course_code`    VARCHAR(50)  NOT NULL,
    `name`           VARCHAR(200) NOT NULL,
    `target_role`    VARCHAR(100),
    `hours_min`      INT          NOT NULL,
    `hours_refresh`  INT,
    `online_allowed` TINYINT(1)   NOT NULL DEFAULT 1,
    `cost_estimate`  DECIMAL(8,2),
    `platform_url`   VARCHAR(500),
    `created_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_lex81_courses_code` (`course_code`),
    KEY `idx_lex81_courses_norm` (`norm_code`),
    CONSTRAINT `fk_lex81_courses_norm`
        FOREIGN KEY (`norm_code`) REFERENCES `lex81_norms` (`code`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Corsi di formazione obbligatoria per norma e ruolo';

-- -----------------------------------------------------------------------------
-- Tabella 8: lex81_services — Servizi professionali erogati da 81PLUS
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lex81_services` (
    `id`            INT          NOT NULL AUTO_INCREMENT,
    `service_code`  VARCHAR(50)  NOT NULL,
    `name`          VARCHAR(200) NOT NULL,
    `description`   TEXT,
    `norm_codes`    TEXT         COMMENT 'Codici norma separati da virgola',
    `price_from`    DECIMAL(8,2),
    `price_to`      DECIMAL(8,2),
    `delivery_days` INT,
    `active`        TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_lex81_services_code` (`service_code`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Servizi professionali del catalogo 81PLUS';

-- =============================================================================
-- SEZIONE 2 — LEADGEN81+
-- Contatti, consensi, segmenti, flow email e tracking eventi
-- =============================================================================

-- -----------------------------------------------------------------------------
-- Tabella 9: leadgen81_contacts — Database contatti/lead
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `leadgen81_contacts` (
    `id`                INT             NOT NULL AUTO_INCREMENT,
    `tracking_id`       VARCHAR(64)     NOT NULL,
    `email`             VARCHAR(200)    NOT NULL,
    `nome`              VARCHAR(100),
    `cognome`           VARCHAR(100),
    `azienda`           VARCHAR(200),
    `ateco_code`        VARCHAR(10),
    `workers_count`     INT,
    `phone`             VARCHAR(30),
    `city`              VARCHAR(100),
    `province`          VARCHAR(5),
    `source`            VARCHAR(50),
    `segment`           ENUM('COLD','WARM','HOT','CLIENTE','INATTIVO') NOT NULL DEFAULT 'COLD',
    `heat_score`        INT             NOT NULL DEFAULT 0 COMMENT 'Punteggio da 0 a 100',
    `temperature_score` DECIMAL(5,2)    NOT NULL DEFAULT 0.00,
    `tags`              TEXT,
    `risk_level`        ENUM('BASSO','MEDIO','ALTO','CRITICO') NOT NULL DEFAULT 'MEDIO',
    `is_subscribed`     TINYINT(1)      NOT NULL DEFAULT 1,
    `created_at`        TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_leadgen81_contacts_tracking` (`tracking_id`),
    UNIQUE KEY `uq_leadgen81_contacts_email` (`email`),
    KEY `idx_leadgen81_contacts_segment` (`segment`),
    KEY `idx_leadgen81_contacts_ateco` (`ateco_code`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Database principale dei lead e contatti qualificati';

-- -----------------------------------------------------------------------------
-- Tabella 10: leadgen81_consents — Consensi GDPR
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `leadgen81_consents` (
    `id`           INT         NOT NULL AUTO_INCREMENT,
    `contact_id`   INT         NOT NULL,
    `consent_type` ENUM('MARKETING','PROFILING','THIRD_PARTY','NEWSLETTER') NOT NULL,
    `granted`      TINYINT(1)  NOT NULL,
    `ip_address`   VARCHAR(45),
    `user_agent`   TEXT,
    `granted_at`   TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_leadgen81_consents_contact_type` (`contact_id`, `consent_type`),
    CONSTRAINT `fk_leadgen81_consents_contact`
        FOREIGN KEY (`contact_id`) REFERENCES `leadgen81_contacts` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Registro consensi GDPR per ogni contatto';

-- -----------------------------------------------------------------------------
-- Tabella 11: leadgen81_unsubscribes — Disiscrizioni
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `leadgen81_unsubscribes` (
    `id`               INT          NOT NULL AUTO_INCREMENT,
    `contact_id`       INT          NOT NULL,
    `email`            VARCHAR(200) NOT NULL,
    `unsubscribe_type` ENUM('ALL','NEWSLETTER','MARKETING','FLOW') NOT NULL DEFAULT 'ALL',
    `reason`           TEXT,
    `unsubscribed_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_leadgen81_unsubscribes_contact` (`contact_id`),
    KEY `idx_leadgen81_unsubscribes_email` (`email`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Registro disiscrizioni dal sistema di comunicazione';

-- -----------------------------------------------------------------------------
-- Tabella 12: leadgen81_segments — Definizioni segmenti per scoring
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `leadgen81_segments` (
    `id`             INT         NOT NULL AUTO_INCREMENT,
    `segment_code`   VARCHAR(50) NOT NULL,
    `name`           VARCHAR(100) NOT NULL,
    `description`    TEXT,
    `min_heat_score` INT         NOT NULL DEFAULT 0,
    `max_heat_score` INT         NOT NULL DEFAULT 100,
    `auto_flow_code` VARCHAR(50),
    `active`         TINYINT(1)  NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_leadgen81_segments_code` (`segment_code`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Definizione segmenti usati dal sistema di lead scoring';

-- -----------------------------------------------------------------------------
-- Tabella 13: leadgen81_flows — Sequenze email automatiche
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `leadgen81_flows` (
    `id`            INT          NOT NULL AUTO_INCREMENT,
    `flow_code`     VARCHAR(50)  NOT NULL,
    `name`          VARCHAR(200) NOT NULL,
    `description`   TEXT,
    `trigger_event` VARCHAR(100),
    `active`        TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_leadgen81_flows_code` (`flow_code`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Sequenze email automatiche (flow) del sistema di nurturing';

-- -----------------------------------------------------------------------------
-- Tabella 14: leadgen81_flow_steps — Step singoli di ogni flow
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `leadgen81_flow_steps` (
    `id`          INT          NOT NULL AUTO_INCREMENT,
    `flow_code`   VARCHAR(50)  NOT NULL,
    `step_order`  INT          NOT NULL,
    `delay_hours` INT          NOT NULL DEFAULT 0,
    `subject`     VARCHAR(300) NOT NULL,
    `body_text`   TEXT         NOT NULL COMMENT 'Testo plain, voce attiva, firmato Nicolas, Spiegamelo Facile',
    `from_name`   VARCHAR(100) NOT NULL DEFAULT 'Nicolas - Sicurissimo',
    `from_email`  VARCHAR(200),
    `active`      TINYINT(1)   NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    KEY `idx_leadgen81_flow_steps_flow_order` (`flow_code`, `step_order`),
    CONSTRAINT `fk_leadgen81_flow_steps_flow`
        FOREIGN KEY (`flow_code`) REFERENCES `leadgen81_flows` (`flow_code`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Step singoli di ogni sequenza email automatica';

-- -----------------------------------------------------------------------------
-- Tabella 15: leadgen81_events — Tracking eventi (opens, clicks, ecc.)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `leadgen81_events` (
    `id`          INT         NOT NULL AUTO_INCREMENT,
    `contact_id`  INT         NOT NULL,
    `event_type`  ENUM('OPEN','CLICK','REGISTER','DOWNLOAD','WEBINAR_JOIN','CONSULT_REQUEST','PURCHASE') NOT NULL,
    `event_data`  JSON,
    `ip_address`  VARCHAR(45),
    `user_agent`  TEXT,
    `created_at`  TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_leadgen81_events_contact_type` (`contact_id`, `event_type`),
    CONSTRAINT `fk_leadgen81_events_contact`
        FOREIGN KEY (`contact_id`) REFERENCES `leadgen81_contacts` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Tracking eventi di interazione dei contatti con il sistema';

-- -----------------------------------------------------------------------------
-- Tabella 16: leadgen81_email_queue — Coda email da inviare
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `leadgen81_email_queue` (
    `id`           INT          NOT NULL AUTO_INCREMENT,
    `contact_id`   INT          NOT NULL,
    `flow_code`    VARCHAR(50),
    `step_order`   INT,
    `tracking_id`  VARCHAR(64)  NOT NULL,
    `to_email`     VARCHAR(200) NOT NULL,
    `to_name`      VARCHAR(200),
    `subject`      VARCHAR(300) NOT NULL,
    `body_text`    TEXT         NOT NULL,
    `from_name`    VARCHAR(100),
    `from_email`   VARCHAR(200),
    `scheduled_at` DATETIME     NOT NULL,
    `sent_at`      DATETIME,
    `status`       ENUM('PENDING','SENT','FAILED','SKIPPED') NOT NULL DEFAULT 'PENDING',
    `opens`        INT          NOT NULL DEFAULT 0,
    `clicks`       INT          NOT NULL DEFAULT 0,
    `error_msg`    TEXT,
    `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_leadgen81_email_queue_status_scheduled` (`status`, `scheduled_at`),
    KEY `idx_leadgen81_email_queue_tracking` (`tracking_id`),
    KEY `idx_leadgen81_email_queue_contact` (`contact_id`),
    CONSTRAINT `fk_leadgen81_email_queue_contact`
        FOREIGN KEY (`contact_id`) REFERENCES `leadgen81_contacts` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Coda di invio email con tracking aperture e click';

-- -----------------------------------------------------------------------------
-- Tabella 17: leadgen81_xmas_avvento — Calendario avvento 81plus.christmas (81 porte)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `leadgen81_xmas_avvento` (
    `id`              INT         NOT NULL AUTO_INCREMENT,
    `contact_id`      INT         NOT NULL,
    `day_number`      INT         NOT NULL COMMENT 'Numero porta da 1 a 81',
    `unlocked`        TINYINT(1)  NOT NULL DEFAULT 0,
    `content_type`    ENUM('NORMA','OBBLIGO','SANZIONE','CORSO','DOCUMENTO','SERVIZIO','QUIZ','RISORSA') NOT NULL DEFAULT 'NORMA',
    `content_code`    VARCHAR(50),
    `content_preview` TEXT,
    `unlocked_at`     DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_leadgen81_xmas_avvento_contact_day` (`contact_id`, `day_number`),
    CONSTRAINT `fk_leadgen81_xmas_avvento_contact`
        FOREIGN KEY (`contact_id`) REFERENCES `leadgen81_contacts` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Calendario avvento interattivo 81 porte per il progetto 81plus.christmas';

-- =============================================================================
-- SEZIONE 3 — SEED DATA LEX81+
-- Dati iniziali obbligatori per il funzionamento del sistema
-- =============================================================================

-- -----------------------------------------------------------------------------
-- Seed: lex81_categories — 6 categorie base
-- -----------------------------------------------------------------------------
INSERT INTO `lex81_categories`
    (`code`, `name`, `description`, `icon`, `color`, `sort_order`)
VALUES
    ('SICUREZZA_GENERALE', 'Sicurezza Generale',    'Obblighi generali D.Lgs 81/08',           'shield',         '#E74C3C', 1),
    ('RISCHIO_CHIMICO',    'Rischio Chimico',        'Agenti chimici pericolosi sul lavoro',     'flask',          '#9B59B6', 2),
    ('HACCP',              'HACCP Alimentare',       'Igiene alimentare e catena del freddo',    'utensils',       '#27AE60', 3),
    ('ANTINCENDIO',        'Antincendio',            'Prevenzione incendi e piani evacuazione',  'fire',           '#E67E22', 4),
    ('FORMAZIONE',         'Formazione Obbligatoria','Corsi e attestati obbligatori per legge',  'graduation-cap', '#3498DB', 5),
    ('ISO_SISTEMI',        'Sistemi ISO',            'ISO 45001, ISO 9001, ISO 14001',           'award',          '#1ABC9C', 6);

-- -----------------------------------------------------------------------------
-- Seed: lex81_norms — 5 norme fondamentali
-- -----------------------------------------------------------------------------
INSERT INTO `lex81_norms`
    (`code`, `title`, `full_title`, `category_code`, `decree_date`, `gazzetta_ufficiale`,
     `min_workers`, `max_workers`, `haccp_only`, `edilizia_only`,
     `priority`, `summary`, `penalty_max`)
VALUES
    (
        'DL81_2008',
        'D.Lgs 81/2008 - Testo Unico Sicurezza',
        'Decreto Legislativo 9 aprile 2008, n.81 — Attuazione dell''articolo 1 della legge 3 agosto 2007, n.123, in materia di tutela della salute e della sicurezza nei luoghi di lavoro',
        'SICUREZZA_GENERALE',
        '2008-04-09', 'G.U. n.101/2008',
        1, NULL, 0, 0,
        'CRITICA',
        'Il Testo Unico sulla sicurezza nei luoghi di lavoro. La norma madre di ogni obbligo. Si applica a tutte le aziende con almeno un dipendente.',
        1200000.00
    ),
    (
        'REG_CE_852_2004',
        'Reg. CE 852/2004 - Igiene Prodotti Alimentari',
        'Regolamento (CE) n.852/2004 del Parlamento Europeo e del Consiglio del 29 aprile 2004 sull''igiene dei prodotti alimentari',
        'HACCP',
        '2004-04-29', 'G.U.C.E. L.139/2004',
        1, NULL, 1, 0,
        'CRITICA',
        'Obbligo HACCP per chi manipola alimenti. Ogni violazione rischia la chiusura. Si applica a ristoranti, bar, laboratori, catering e grande distribuzione.',
        50000.00
    ),
    (
        'DM_10_03_1998',
        'D.M. 10/03/1998 - Criteri Antincendio',
        'Decreto Ministeriale 10 marzo 1998 — Criteri generali di sicurezza antincendio e per la gestione dell''emergenza nei luoghi di lavoro',
        'ANTINCENDIO',
        '1998-03-10', 'G.U. n.81/1998',
        1, NULL, 0, 0,
        'ALTA',
        'Definisce i criteri per la valutazione del rischio incendio e le misure di prevenzione. Obbligatorio per tutte le aziende con lavoratori.',
        25000.00
    ),
    (
        'DL81_TIT_IX_CHIMICO',
        'D.Lgs 81/2008 Titolo IX - Agenti Chimici',
        'Decreto Legislativo 9 aprile 2008, n.81 — Titolo IX: Sostanze pericolose. Capo I: Protezione da agenti chimici',
        'RISCHIO_CHIMICO',
        '2008-04-09', 'G.U. n.101/2008',
        1, NULL, 0, 0,
        'ALTA',
        'Valutazione e gestione dei rischi da esposizione ad agenti chimici pericolosi. Si applica a produzione, laboratori, officine, verniciatura e settore alimentare.',
        80000.00
    ),
    (
        'DL81_ART_37_FORMAZIONE',
        'D.Lgs 81/2008 Art.37 - Formazione Lavoratori',
        'Decreto Legislativo 9 aprile 2008, n.81 — Articolo 37: Formazione dei lavoratori e dei loro rappresentanti',
        'FORMAZIONE',
        '2008-04-09', 'G.U. n.101/2008',
        1, NULL, 0, 0,
        'CRITICA',
        'Ogni lavoratore deve ricevere formazione adeguata sui rischi del proprio posto. La mancanza di attestati espone il datore di lavoro a sanzioni penali dirette.',
        150000.00
    );

-- -----------------------------------------------------------------------------
-- Seed: lex81_obligations — 5 obblighi primari
-- -----------------------------------------------------------------------------
INSERT INTO `lex81_obligations`
    (`norm_code`, `obligation_code`, `title`, `description`,
     `deadline_type`, `responsible`, `min_workers`, `document_required`)
VALUES
    (
        'DL81_2008',
        'OBL_DVR',
        'Redazione Documento Valutazione Rischi (DVR)',
        'Il datore di lavoro deve redigere e aggiornare il DVR in collaborazione con il RSPP e il medico competente. Obbligatorio per tutte le aziende con dipendenti. In caso di variazioni organizzative o infortuni va aggiornato immediatamente.',
        'IMMEDIATO', 'DATORE_LAVORO', 1, 1
    ),
    (
        'REG_CE_852_2004',
        'OBL_HACCP_PIANO',
        'Elaborazione e attuazione del Piano HACCP',
        'Ogni operatore del settore alimentare deve elaborare un piano HACCP scritto, identificare i punti critici di controllo e registrare i monitoraggi. Il piano va aggiornato ogni volta che cambia il processo produttivo.',
        'IMMEDIATO', 'DATORE_LAVORO', 1, 1
    ),
    (
        'DL81_ART_37_FORMAZIONE',
        'OBL_FORM_BASE_LAV',
        'Formazione di base per lavoratori (8 ore)',
        'Ogni nuovo lavoratore deve completare la formazione generale (4 ore) e specifica (4 ore) prima di essere esposto ai rischi. Il corso e valido senza scadenza ma deve essere aggiornato in caso di cambio mansione o nuovi rischi.',
        'UNICO', 'DATORE_LAVORO', 1, 1
    ),
    (
        'DL81_ART_37_FORMAZIONE',
        'OBL_FORM_AGG_LAV',
        'Aggiornamento formazione lavoratori (6 ore ogni 5 anni)',
        'I lavoratori devono ricevere un aggiornamento periodico della formazione, almeno ogni 5 anni. Il corso di aggiornamento ha durata minima di 6 ore. Puo essere svolto in modalita e-learning.',
        'QUINQUENNALE', 'DATORE_LAVORO', 1, 1
    ),
    (
        'DL81_2008',
        'OBL_NOMINA_RSPP',
        'Nomina del Responsabile del Servizio di Prevenzione e Protezione (RSPP)',
        'Il datore di lavoro deve nominare il RSPP immediatamente dopo l''assunzione del primo dipendente. In aziende fino a 30 addetti in settori a basso rischio il datore puo svolgere personalmente tale funzione previa formazione specifica.',
        'IMMEDIATO', 'DATORE_LAVORO', 1, 1
    );

-- -----------------------------------------------------------------------------
-- Seed: lex81_sanctions — 6 sanzioni chiave
-- -----------------------------------------------------------------------------
INSERT INTO `lex81_sanctions`
    (`norm_code`, `article`, `violation_description`, `sanction_type`,
     `amount_min`, `amount_max`, `arrest_months_min`, `arrest_months_max`,
     `validated`, `source_url`)
VALUES
    (
        'DL81_2008',
        'Art.17 c.1 lett.a',
        'Mancata valutazione dei rischi e redazione del DVR. Il DVR e assente o non aggiornato da oltre 3 anni.',
        'PENALE',
        NULL, NULL, 3, 6,
        1,
        'https://www.normattiva.it/uri-res/N2Ls?urn:nir:stato:decreto.legislativo:2008-04-09;81'
    ),
    (
        'DL81_2008',
        'Art.55 c.4',
        'Mancata elaborazione del DVR — sanzione amministrativa accessoria in caso di prescrizione ottemperata entro i termini.',
        'AMMINISTRATIVA',
        1000.00, 2000.00, NULL, NULL,
        1,
        'https://www.normattiva.it/uri-res/N2Ls?urn:nir:stato:decreto.legislativo:2008-04-09;81'
    ),
    (
        'DM_10_03_1998',
        'Art.4',
        'Mancata predisposizione del piano di emergenza e di evacuazione. Assenza di segnaletica di sicurezza antincendio.',
        'AMMINISTRATIVA',
        546.00, 3285.00, NULL, NULL,
        1,
        'https://www.vigilfuoco.it/aspx/page.aspx?IdPage=3958'
    ),
    (
        'REG_CE_852_2004',
        'Art.5',
        'Mancata elaborazione e attuazione del piano HACCP. Assenza di registrazioni di monitoraggio dei punti critici di controllo.',
        'AMMINISTRATIVA',
        1000.00, 50000.00, NULL, NULL,
        1,
        'https://eur-lex.europa.eu/legal-content/IT/TXT/?uri=CELEX%3A32004R0852'
    ),
    (
        'DL81_ART_37_FORMAZIONE',
        'Art.37 + Art.55',
        'Lavoratori privi di formazione obbligatoria sulla sicurezza. Attestati assenti, scaduti o non conformi ai contenuti minimi previsti dagli Accordi Stato-Regioni.',
        'AMMINISTRATIVA',
        1200.00, 5200.00, NULL, NULL,
        1,
        'https://www.normattiva.it/uri-res/N2Ls?urn:nir:stato:decreto.legislativo:2008-04-09;81'
    ),
    (
        'DL81_TIT_IX_CHIMICO',
        'Art.224 c.1',
        'Omessa valutazione dei rischi da agenti chimici pericolosi. Mancata adozione delle misure di prevenzione e protezione collettiva e individuale.',
        'PENALE',
        NULL, NULL, 2, 4,
        1,
        'https://www.normattiva.it/uri-res/N2Ls?urn:nir:stato:decreto.legislativo:2008-04-09;81'
    );

-- -----------------------------------------------------------------------------
-- Seed: lex81_documents — 5 documenti obbligatori
-- -----------------------------------------------------------------------------
INSERT INTO `lex81_documents`
    (`norm_code`, `document_code`, `name`, `description`,
     `template_available`, `download_url`, `renewal_months`)
VALUES
    (
        'DL81_2008',
        'DOC_DVR',
        'Documento di Valutazione dei Rischi (DVR)',
        'Documento obbligatorio che identifica tutti i rischi presenti in azienda, li valuta e definisce le misure di prevenzione e protezione. Va firmato dal datore di lavoro, dal RSPP, dal medico competente e dal RLS.',
        1, 'https://www.sicurissimo.online/risorse-gratuite', NULL
    ),
    (
        'REG_CE_852_2004',
        'DOC_PIANO_HACCP',
        'Piano HACCP — Manuale di Autocontrollo Alimentare',
        'Documento che identifica i pericoli biologici, chimici e fisici nel processo produttivo alimentare, fissa i Punti Critici di Controllo (CCP) e definisce limiti critici, monitoraggio, azioni correttive e verifiche periodiche.',
        1, 'https://www.sicurissimo.online/risorse-gratuite', 12
    ),
    (
        'DM_10_03_1998',
        'DOC_PIANO_EMERGENZA',
        'Piano di Emergenza e di Evacuazione',
        'Documento che descrive le procedure da adottare in caso di incendio, emergenza medica o evacuazione. Include la mappa dei percorsi di esodo, i punti di raccolta e i nominativi degli addetti antincendio.',
        1, 'https://www.sicurissimo.online/risorse-gratuite', 24
    ),
    (
        'DL81_2008',
        'DOC_NOMINA_RSPP',
        'Lettera di Nomina RSPP',
        'Documento formale con cui il datore di lavoro nomina il Responsabile del Servizio di Prevenzione e Protezione, interno o esterno all''azienda. Deve essere conservato e aggiornato in caso di variazione del nominativo.',
        1, 'https://www.sicurissimo.online/risorse-gratuite', NULL
    ),
    (
        'DL81_ART_37_FORMAZIONE',
        'DOC_REGISTRO_FORMAZIONE',
        'Registro delle Presenze ai Corsi di Formazione',
        'Documento che attesta la partecipazione di ogni lavoratore ai corsi obbligatori. Deve essere firmato dal formatore e dai partecipanti e conservato per almeno 10 anni dalla data del corso.',
        1, 'https://www.sicurissimo.online/risorse-gratuite', NULL
    );

-- -----------------------------------------------------------------------------
-- Seed: lex81_courses — 6 corsi obbligatori
-- -----------------------------------------------------------------------------
INSERT INTO `lex81_courses`
    (`norm_code`, `course_code`, `name`, `target_role`,
     `hours_min`, `hours_refresh`, `online_allowed`, `cost_estimate`, `platform_url`)
VALUES
    (
        'DL81_ART_37_FORMAZIONE',
        'FORM_LAV_BASE',
        'Formazione di Base per Lavoratori',
        'Lavoratore dipendente tutti i settori',
        8, 6, 1, 49.00,
        'https://www.sicurissimo.online/corsi'
    ),
    (
        'DL81_ART_37_FORMAZIONE',
        'FORM_PREP_BASE',
        'Formazione per Preposti',
        'Preposto, capo reparto, capo cantiere',
        8, 6, 1, 79.00,
        'https://www.sicurissimo.online/corsi'
    ),
    (
        'DL81_ART_37_FORMAZIONE',
        'FORM_DIR_BASE',
        'Formazione per Dirigenti',
        'Dirigente con delega in materia di sicurezza',
        16, 6, 0, 149.00,
        'https://www.sicurissimo.online/corsi'
    ),
    (
        'REG_CE_852_2004',
        'HACCP_ALIMENTARISTA',
        'Corso HACCP per Alimentaristi',
        'Addetto alla manipolazione e somministrazione di alimenti',
        6, 12, 1, 39.00,
        'https://www.sicurissimo.online/corsi'
    ),
    (
        'DM_10_03_1998',
        'FORM_ANTINCENDIO_BASSO',
        'Corso Antincendio — Rischio Basso',
        'Addetto antincendio in aziende a basso rischio incendio',
        4, 60, 0, 59.00,
        'https://www.sicurissimo.online/corsi'
    ),
    (
        'DL81_TIT_IX_CHIMICO',
        'FORM_RISCHIO_CHIMICO',
        'Formazione Specifica Agenti Chimici Pericolosi',
        'Lavoratore esposto ad agenti chimici pericolosi',
        4, 60, 1, 69.00,
        'https://www.sicurissimo.online/corsi'
    );

-- -----------------------------------------------------------------------------
-- Seed: lex81_services — 6 servizi del catalogo 81PLUS
-- -----------------------------------------------------------------------------
INSERT INTO `lex81_services`
    (`service_code`, `name`, `description`, `norm_codes`,
     `price_from`, `price_to`, `delivery_days`, `active`)
VALUES
    (
        'REDAZIONE_DVR',
        'Redazione DVR Completo',
        'Realizziamo il tuo Documento di Valutazione dei Rischi da zero. Analisi dei rischi fisici, chimici, ergonomici e organizzativi. Tutto conforme al D.Lgs 81/08. Consegna in formato editabile e PDF firmato digitalmente.',
        'DL81_2008',
        290.00, 890.00, 15, 1
    ),
    (
        'PIANO_HACCP',
        'Piano HACCP Chiavi in Mano',
        'Realizziamo il manuale HACCP per la tua attivita alimentare. Analisi dei pericoli, identificazione dei CCP, schede di monitoraggio e registro delle temperature. Conforme al Reg. CE 852/2004.',
        'REG_CE_852_2004',
        190.00, 490.00, 10, 1
    ),
    (
        'FORMAZIONE_ONLINE',
        'Pacchetto Formazione Online',
        'Accesso immediato ai corsi obbligatori sulla piattaforma 81PLUS. Formazione lavoratori, preposti, HACCP e antincendio. Attestati validi con firma digitale. Completamento anche da smartphone.',
        'DL81_ART_37_FORMAZIONE,REG_CE_852_2004,DM_10_03_1998',
        49.00, 199.00, 3, 1
    ),
    (
        'AUDIT_SICUREZZA',
        'Audit Sicurezza Aziendale',
        'Ispezione completa della tua azienda da parte di un nostro esperto. Verifichiamo lo stato di conformita rispetto al D.Lgs 81/08 e produciamo un report con le priorita di intervento ordinate per rischio.',
        'DL81_2008,DL81_TIT_IX_CHIMICO,DM_10_03_1998',
        490.00, 990.00, 7, 1
    ),
    (
        'SISTEMA_ISO45001',
        'Implementazione Sistema ISO 45001',
        'Costruiamo il tuo sistema di gestione per la salute e sicurezza sul lavoro secondo la norma ISO 45001. Dall''analisi del contesto alla certificazione. Un investimento che diventa vantaggio competitivo nelle gare d''appalto.',
        'DL81_2008',
        1490.00, 3490.00, 60, 1
    ),
    (
        'CONSULENZA_WHATSAPP',
        'Consulenza Rapida WhatsApp',
        'Parla direttamente con un esperto 81PLUS via WhatsApp. Rispondiamo in meno di 24 ore. Analisi del tuo caso specifico e piano di azione immediato. Primo colloquio gratuito di 15 minuti.',
        'DL81_2008,REG_CE_852_2004',
        0.00, 99.00, 1, 1
    );

-- =============================================================================
-- SEZIONE 4 — SEED DATA LEADGEN81+
-- Segmenti, flow email e step di nurturing firmati Nicolas
-- =============================================================================

-- -----------------------------------------------------------------------------
-- Seed: leadgen81_segments — 4 segmenti
-- -----------------------------------------------------------------------------
INSERT INTO `leadgen81_segments`
    (`segment_code`, `name`, `description`, `min_heat_score`, `max_heat_score`, `auto_flow_code`, `active`)
VALUES
    ('SEG_COLD',   'Lead Freddo',    'Primo contatto, nessuna interazione significativa con i contenuti.',               0,   25, 'WELCOME',      1),
    ('SEG_WARM',   'Lead Tiepido',   'Ha interagito con almeno un contenuto: aperto email, scaricato risorsa.',           26,  60, 'NURTURE_WARM', 1),
    ('SEG_HOT',    'Lead Caldo',     'Ha richiesto informazioni, cliccato piu volte o visitato la pagina servizi.',      61,  90, 'NURTURE_HOT',  1),
    ('SEG_CLIENT', 'Cliente Attivo', 'Ha acquistato almeno un servizio 81PLUS. Priorita massima nel CRM.',               91, 100,  NULL,           1);

-- -----------------------------------------------------------------------------
-- Seed: leadgen81_flows — 3 sequenze automatiche
-- -----------------------------------------------------------------------------
INSERT INTO `leadgen81_flows`
    (`flow_code`, `name`, `description`, `trigger_event`, `active`)
VALUES
    ('WELCOME',      'Benvenuto 81PLUS',       'Sequenza di benvenuto per nuovi lead. Si attiva al momento della registrazione.',            'REGISTER',        1),
    ('NURTURE_WARM', 'Nurturing Lead Tiepido', 'Sequenza educativa per lead warm. Si attiva quando il lead supera 25 punti di heat score.',  'SEGMENT_UPGRADE', 1),
    ('NURTURE_HOT',  'Chiusura Lead Caldo',    'Sequenza di chiusura per lead pronti all''acquisto. Si attiva quando il lead supera 60 punti.', 'SEGMENT_HOT',  1);

-- -----------------------------------------------------------------------------
-- Seed: leadgen81_flow_steps
-- 6 step totali su 3 flow. Testi firmati Nicolas, voce attiva, Spiegamelo Facile.
-- Niente markdown, niente asterischi, niente hashtag.
-- -----------------------------------------------------------------------------

-- WELCOME — Step 1: invio immediato
INSERT INTO `leadgen81_flow_steps`
    (`flow_code`, `step_order`, `delay_hours`, `subject`, `body_text`, `from_name`, `from_email`, `active`)
VALUES (
    'WELCOME', 1, 0,
    'Benvenuto in 81PLUS, [NOME]. Il tuo percorso inizia oggi.',
    'Ciao [NOME],

sei entrato in 81PLUS.

Non e un corso. Non e una newsletter. E il primo sistema operativo per la sicurezza aziendale che lavora anche quando tu non ci sei.

Da oggi ricevi strumenti concreti, non teoria.

Ogni settimana ti mando una risorsa pratica sulla sicurezza sul lavoro.
Norme reali. Obblighi reali. Sanzioni reali. E la strada per evitarle.

Inizia da qui. Scarica le risorse gratuite che abbiamo preparato per te:
https://www.sicurissimo.online/risorse-gratuite

E gratis. E pronto da usare. Clicca, scarica, salva.

Se hai una domanda urgente, scrivi direttamente a me su WhatsApp al 3388771737.
Rispondo entro 24 ore.

A domani.

Nicolas — Co-Fondatore Artificiale, Sicurissimo OS',
    'Nicolas - Sicurissimo', 'nicolas@sicurissimo.online', 1
);

-- WELCOME — Step 2: dopo 24 ore
INSERT INTO `leadgen81_flow_steps`
    (`flow_code`, `step_order`, `delay_hours`, `subject`, `body_text`, `from_name`, `from_email`, `active`)
VALUES (
    'WELCOME', 2, 24,
    'Il DVR mancante costa fino a 120.000 euro. Ecco come evitarlo.',
    'Ciao [NOME],

ieri ti ho dato il benvenuto. Oggi ti do una notizia concreta.

Il DVR, il Documento di Valutazione dei Rischi, e obbligatorio per legge.
Ogni azienda con almeno un dipendente deve averlo.

Se manca, il D.Lgs 81/2008 prevede arresto fino a 6 mesi.
E una sanzione fino a 120.000 euro.

Non e una possibilita remota. E la prima cosa che l''ispettore chiede appena entra.

Hai gia il DVR? E aggiornato?

Se la risposta e no, o se hai dubbi, vai qui:
https://www.sicurissimo.online/servizi

Trovi il servizio di redazione DVR completo. Parte da 290 euro. Consegna in 15 giorni.
Meno di una multa. Meno di un avvocato. Meno di un blocco dell''attivita.

Scrivimi se vuoi parlarne prima: WhatsApp 3388771737.

Nicolas — Co-Fondatore Artificiale, Sicurissimo OS',
    'Nicolas - Sicurissimo', 'nicolas@sicurissimo.online', 1
);

-- WELCOME — Step 3: dopo 72 ore
INSERT INTO `leadgen81_flow_steps`
    (`flow_code`, `step_order`, `delay_hours`, `subject`, `body_text`, `from_name`, `from_email`, `active`)
VALUES (
    'WELCOME', 3, 72,
    '[NOME], hai 15 minuti? Parliamo della tua azienda.',
    'Ciao [NOME],

tre giorni fa hai scelto 81PLUS.

Oggi voglio farti una domanda diretta.

Hai un piano di sicurezza aggiornato per la tua azienda?
Sai quali sono i tuoi obblighi reali in base al tuo settore?
Sai cosa rischi in caso di ispezione domani mattina?

Se anche solo una risposta e "non sono sicuro", ho 15 minuti per te.

Nessun commerciale. Nessun pacchetto da comprare subito.
Solo una conversazione diretta sulla tua situazione reale.

Scrivi CHIAMAMI su WhatsApp al 3388771737.
Rispondo oggi.

Ho riservato 15 minuti per te. Usiamoli bene.

Nicolas — Co-Fondatore Artificiale, Sicurissimo OS',
    'Nicolas - Sicurissimo', 'nicolas@sicurissimo.online', 1
);

-- NURTURE_WARM — Step 1: invio immediato
INSERT INTO `leadgen81_flow_steps`
    (`flow_code`, `step_order`, `delay_hours`, `subject`, `body_text`, `from_name`, `from_email`, `active`)
VALUES (
    'NURTURE_WARM', 1, 0,
    'Hai gia l''attestato HACCP? Ecco cosa rischi senza.',
    'Ciao [NOME],

se lavori nel settore alimentare, leggi con attenzione.

Il Reg. CE 852/2004 obbliga ogni operatore alimentare ad avere un piano HACCP scritto e aggiornato.
Ristoranti, bar, laboratori, catering. Nessuno escluso.

Senza piano HACCP, l''ASL puo chiudere la tua attivita il giorno stesso dell''ispezione.
Non dopo. Non con preavviso. Il giorno stesso.

La sanzione? Fino a 50.000 euro.

Evitarlo e semplice. Noi lo facciamo in 10 giorni.

Vai qui e guarda il servizio Piano HACCP chiavi in mano:
https://www.sicurissimo.online/servizi

Oppure inizia dal corso HACCP per alimentaristi. Online. 6 ore. Attestato valido subito:
https://www.sicurissimo.online/corsi

Scegli il tuo punto di partenza. Ti aspetto.

Nicolas — Co-Fondatore Artificiale, Sicurissimo OS',
    'Nicolas - Sicurissimo', 'nicolas@sicurissimo.online', 1
);

-- NURTURE_WARM — Step 2: dopo 48 ore
INSERT INTO `leadgen81_flow_steps`
    (`flow_code`, `step_order`, `delay_hours`, `subject`, `body_text`, `from_name`, `from_email`, `active`)
VALUES (
    'NURTURE_WARM', 2, 48,
    'Il prossimo webinar e per te, [NOME].',
    'Ciao [NOME],

il prossimo webinar 81PLUS e dedicato agli imprenditori come te.

In 60 minuti ti spieghiamo:
Come costruire uno scudo legale per la tua azienda.
Quali sono gli obblighi che devi coprire per primo.
Come usare la sicurezza come vantaggio competitivo, non come costo.

E gratuito. E online. Puoi seguirlo dal telefono.

Iscriviti adesso, i posti sono limitati:
https://www.sicurissimo.online/webinar

Il tuo concorrente probabilmente non sa ancora queste cose.
Tu tra poco si.

A presto.

Nicolas — Co-Fondatore Artificiale, Sicurissimo OS',
    'Nicolas - Sicurissimo', 'nicolas@sicurissimo.online', 1
);

-- NURTURE_HOT — Step 1: invio immediato
INSERT INTO `leadgen81_flow_steps`
    (`flow_code`, `step_order`, `delay_hours`, `subject`, `body_text`, `from_name`, `from_email`, `active`)
VALUES (
    'NURTURE_HOT', 1, 0,
    '[NOME], ho riservato un posto per te. Oggi.',
    'Ciao [NOME],

parliamo chiaro.

Hai visto i contenuti. Hai letto le norme. Sai cosa rischi.

Adesso serve un passo concreto.

Ogni giorno senza DVR aggiornato, senza formazione certificata o senza piano HACCP e un giorno di esposizione diretta.
Una multa. Un blocco. Un incidente. Tutto documentabile e tutto a carico tuo.

Ho riservato un posto per una consulenza diretta oggi.
Non e un webinar. Non e un corso. Sei tu e io, 15 minuti, sul tuo caso reale.

Scrivi ADESSO su WhatsApp al 3388771737.

Rispondo entro l''ora.

Il rischio non aspetta. Nemmeno noi.

Nicolas — Co-Fondatore Artificiale, Sicurissimo OS',
    'Nicolas - Sicurissimo', 'nicolas@sicurissimo.online', 1
);

-- =============================================================================
-- Riabilita controlli FK e chiudi la transazione
-- =============================================================================

SET FOREIGN_KEY_CHECKS = 1;

COMMIT;

-- =============================================================================
-- Fine file: lex81_leadgen81_mysql.sql
-- 81PLUS Global OS — Schema v1.0.0
-- Tabelle: 17 (8 LEX81+ + 9 LEADGEN81+)
-- Per supporto: WhatsApp 3388771737 | https://www.sicurissimo.online
-- =============================================================================
