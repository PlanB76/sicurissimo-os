-- SFERA81+ V6 Addendum — Badge engine + missioni espanse
-- Da eseguire DOPO sfera81_mysql.sql su installazioni V5 esistenti
-- Su installazioni nuove, usare sfera81_mysql.sql (aggiornato con queste tabelle)

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sfera_badges` (
  `id` int NOT NULL AUTO_INCREMENT,
  `badge_code` varchar(50) NOT NULL,
  `badge_name` varchar(80) NOT NULL,
  `badge_desc` varchar(200) DEFAULT NULL,
  `badge_icon` varchar(20) DEFAULT '🏅',
  `game_code` varchar(30) DEFAULT NULL,
  `pvplus_bonus` int NOT NULL DEFAULT 0,
  `trigger_event` varchar(50) DEFAULT NULL,
  `trigger_value` int NOT NULL DEFAULT 1,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `badge_code` (`badge_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sfera_user_badges` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `sic_id` varchar(30) NOT NULL,
  `badge_id` int NOT NULL,
  `badge_code` varchar(50) NOT NULL,
  `pvplus_awarded` int NOT NULL DEFAULT 0,
  `earned_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_badge` (`user_id`,`badge_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- ========================================================
-- SEED BADGES
-- ========================================================
INSERT IGNORE INTO `sfera_badges` (`badge_code`,`badge_name`,`badge_desc`,`badge_icon`,`game_code`,`pvplus_bonus`,`trigger_event`) VALUES
('PRIMO_ACCESSO','Primo Accesso','Hai completato il tuo primo accesso giornaliero.','⚡','DAILY_SPARK81',5,'daily_access'),
('SIC_ID_ATTIVO','SIC-ID Attivo','Il tuo SIC-ID è stato attivato con successo.','🆔','ESCALATION81',10,'sic_id_activated'),
('PROFILO_COMPLETO','Profilo Completo','Hai completato tutti i dati del profilo.','✅','ESCALATION81',15,'profile_complete'),
('ATECO_PROFILO','Profilo ATECO','Hai inserito il tuo codice ATECO e il profilo rischio.','🏭','ESCALATION81',20,'ateco_profiled'),
('DVR_CHECK','DVR Verificato','Hai verificato il Documento di Valutazione dei Rischi.','📋','SHIELD81',25,'dvr_checked'),
('AUDIT_COMPLETATO','Audit Completato','Hai completato il tuo primo audit SICURISSIMO.','🔍','SHIELD81',50,'audit_complete'),
('STREAK_7','Streak 7 Giorni','7 accessi consecutivi. Stai costruendo un\'abitudine.','🔥','DAILY_SPARK81',100,'streak_7'),
('STREAK_30','Streak 30 Giorni','30 accessi consecutivi. Sei costante.','🌟','DAILY_SPARK81',500,'streak_30'),
('STREAK_81','Streak 81 Giorni','81 accessi consecutivi. Sei un presidio.','🏆','DAILY_SPARK81',1500,'streak_81'),
('LIFEWHEEL_CAP','Spicchio al Cap','Hai portato un area LIFEWHEEL81+ al cap del tuo status.','🎯','LIFEWHEEL81',150,'lifewheel_cap'),
('IDENTITA81_COMPLETE','IDENTITÀ81+ Completato','Hai completato il livello 1 del percorso ESCALATION81+.','🟢','ESCALATION81',100,'level_1_complete'),
('ATECO81_COMPLETE','ATECO81+ Completato','Hai completato il livello 2 del percorso ESCALATION81+.','🔵','ESCALATION81',200,'level_2_complete'),
('OBBLIGHI81_COMPLETE','OBBLIGHI81+ Completato','Hai completato il livello 3 del percorso ESCALATION81+.','🔴','ESCALATION81',350,'level_3_complete'),
('AZIONE81_COMPLETE','AZIONE81+ Completato','Hai completato il livello 4 del percorso ESCALATION81+.','🟣','ESCALATION81',600,'level_4_complete'),
('PRESIDIO81_COMPLETE','PRESIDIO81+ Completato','Hai completato il percorso ESCALATION81+ al massimo livello.','🌕','ESCALATION81',1000,'level_5_complete'),
('BOOSTER_ATTIVATO','BOOSTER81+ Attivato','Hai ricevuto il tuo primo reward BOOSTER81+.','⚡','BOOSTER81',0,'booster_used'),
('NETWORKER','Networker 81+','Hai attivato il tuo primo referral network.','🌐','NETWORK_MAP81',25,'referral_activated'),
('HACCP_OK','HACCP Presidiato','Manuale HACCP verificato e in regola.','🍽️','HACCP_KITCHEN81',30,'haccp_checked'),
('PRIVACY_OK','Privacy Lock','Privacy policy e registro trattamenti verificati.','🔒','PRIVACY_LOCK81',20,'privacy_checked'),
('ACADEMY_START','Academy 81+','Hai completato il tuo primo modulo Academy.','📚','ACADEMY_QUEST81',20,'academy_module');

-- ========================================================
-- MISSIONI AGGIUNTIVE V6
-- ========================================================
INSERT IGNORE INTO `sfera_missions` (`mission_code`,`mission_name`,`mission_desc`,`game_code`,`pvplus_reward`,`frequency`,`required_once`,`repeatable`,`escalation_level`,`safety_required`,`haccp_required`,`privacy_required`) VALUES
-- Livello 1 IDENTITÀ81+ (aggiuntive)
('CHOOSE_USER_TYPE','Scegli tipo utente','Indica se sei azienda, persona o networker.','ESCALATION81',3,'once',1,0,1,0,0,0),
('INSERT_CITY','Inserisci città e provincia','Inserisci la tua sede principale.','ESCALATION81',2,'once',1,0,1,0,0,0),
('UPLOAD_AVATAR','Carica avatar o logo','Personalizza il tuo profilo con un avatar o logo aziendale.','ESCALATION81',3,'once',1,0,1,0,0,0),
('WEEKLY_PROFILE_CHECK','Controllo profilo settimanale','Rivedi e aggiorna il tuo profilo ogni settimana.','ESCALATION81',5,'weekly',0,1,1,0,0,0),
-- Livello 2 ATECO81+ (aggiuntive)
('CHOOSE_MACRO','Scegli macrosettore','Seleziona il macrosettore della tua attività.','ESCALATION81',4,'once',1,0,2,0,0,0),
('CHOOSE_MICRO','Scegli microsettore','Seleziona il microsettore specifico.','ESCALATION81',3,'once',1,0,2,0,0,0),
('INDICA_DATI_PERSONALI','Indica se tratti dati personali','Specifica se la tua attività prevede trattamento di dati personali.','ESCALATION81',3,'once',1,0,2,0,0,0),
('INSERT_SEDE_LEGALE','Inserisci sede legale','Inserisci l\'indirizzo della sede legale.','ESCALATION81',3,'once',1,0,2,0,0,0),
('INSERT_SEDE_OPERATIVA','Inserisci sede operativa','Inserisci l\'indirizzo della sede operativa se diversa.','ESCALATION81',2,'once',1,0,2,0,0,0),
('INDICA_CORSI','Indica corsi necessari','Indica quali corsi di formazione sono necessari per la tua attività.','ESCALATION81',4,'once',1,0,2,0,0,0),
('INDICA_ATTREZZATURE','Indica attrezzature principali','Elenca le attrezzature principali utilizzate.','ESCALATION81',3,'once',1,0,2,0,0,0),
('INDICA_MANSIONI','Indica mansioni lavoratori','Indica le mansioni principali dei tuoi lavoratori.','ESCALATION81',4,'once',1,0,2,0,0,0),
('INDICA_SCADENZE','Indica scadenze critiche','Inserisci le prime scadenze obbligatorie.','ESCALATION81',5,'once',1,0,2,0,0,0),
-- Livello 3 OBBLIGHI81+ (aggiuntive)
('CHECK_NOMINA_DL','Nomina Datore di Lavoro','Verifica la nomina formale del Datore di Lavoro.','SHIELD81',8,'once',1,0,3,1,0,0),
('CHECK_PREPOSTO','Nomina Preposto','Verifica la nomina del Preposto.','SHIELD81',8,'once',1,0,3,1,0,0),
('CHECK_ANTINCENDIO','Formazione Antincendio','Verifica la formazione antincendio dei lavoratori.','SHIELD81',10,'once',1,0,3,1,0,0),
('CHECK_PRIMO_SOCCORSO','Formazione Primo Soccorso','Verifica la formazione primo soccorso.','SHIELD81',10,'once',1,0,3,1,0,0),
('CHECK_RLS','RLS/RLST','Verifica la nomina o consultazione del Rappresentante Lavoratori.','SHIELD81',8,'once',1,0,3,1,0,0),
('CHECK_SORVEGLIANZA','Sorveglianza Sanitaria','Verifica l\'attivazione della sorveglianza sanitaria se prevista.','SHIELD81',8,'once',1,0,3,1,0,0),
('CHECK_DPI','Check DPI','Verifica la fornitura e uso dei Dispositivi di Protezione Individuale.','SHIELD81',6,'once',1,0,3,1,0,0),
('CHECK_ATTREZZATURE','Check Attrezzature','Verifica la conformità delle attrezzature utilizzate.','SHIELD81',6,'once',1,0,3,1,0,0),
('CHECK_INFORMATIVE_PRIVACY','Informative Privacy','Verifica che tutte le informative privacy siano consegnate.','PRIVACY_LOCK81',8,'once',1,0,3,0,0,1),
('CHECK_REGISTRO_TRATTAMENTI','Registro Trattamenti','Verifica la tenuta del registro dei trattamenti dati.','PRIVACY_LOCK81',8,'once',1,0,3,0,0,1),
('CHECK_COOKIE','Cookie Policy','Verifica cookie policy e consenso se hai un sito web.','PRIVACY_LOCK81',6,'once',1,0,3,0,0,1),
('CHECK_ALLERGENI','Check Allergeni','Verifica l\'etichettatura e gestione allergeni.','HACCP_KITCHEN81',10,'once',1,0,3,0,1,0),
('CHECK_TRACCIABILITA','Check Tracciabilità','Verifica il sistema di tracciabilità alimentare.','HACCP_KITCHEN81',10,'once',1,0,3,0,1,0),
('CHECK_POS','Piano Operativo Sicurezza','Verifica il POS per lavori in cantiere.','SHIELD81',12,'once',1,0,3,1,0,0),
('CHECK_LAVORI_QUOTA','Lavori in Quota','Verifica formazione e attrezzature per lavori in quota.','SHIELD81',12,'once',1,0,3,1,0,0),
-- Livello 4 AZIONE81+ (aggiuntive)
('VIEW_REPORT','Visualizza report','Visualizza il report del tuo audit SICURISSIMO.','SHIELD81',8,'once',1,0,4,0,0,0),
('SAVE_PRIORITA','Salva 3 priorità','Salva le tue 3 priorità operative.','SHIELD81',10,'once',1,0,4,0,0,0),
('REQUEST_DOCUMENTO','Richiedi preventivo documento','Richiedi un preventivo per un documento obbligatorio.','DOC_FORGE81',15,'once',1,0,4,0,0,0),
('REQUEST_CORSO','Richiedi preventivo corso','Richiedi un preventivo per un corso obbligatorio.','ACADEMY_QUEST81',15,'once',1,0,4,0,0,0),
('ATTIVA_REMINDER','Attiva promemoria scadenze','Attiva i promemoria automatici per le scadenze.','SHIELD81',8,'once',1,0,4,0,0,0),
('UPDATE_DOCUMENTI','Aggiorna documenti','Aggiorna almeno un documento.','DOC_FORGE81',10,'monthly',0,1,4,0,0,0),
('UPDATE_CORSI','Aggiorna corsi','Aggiorna lo stato dei corsi formativi.','ACADEMY_QUEST81',10,'monthly',0,1,4,0,0,0),
('CRM_MISSION','Missione CRM','Completa una missione nel CRM 81+.','CRM_ARENA81',20,'once',1,0,4,0,0,0),
('FOLLOW_UP','Follow-up lead','Completa un follow-up su un lead qualificato.','CRM_ARENA81',15,'weekly',0,1,4,0,0,0),
('UPDATE_LIFEWHEEL','Aggiorna LIFEWHEEL81+','Aggiorna almeno uno spicchio LIFEWHEEL81+.','LIFEWHEEL81',12,'weekly',0,1,4,0,0,0),
('MIGLIORA_SPICCHIO','Migliora spicchio debole','Migliora lo spicchio più basso della tua LIFEWHEEL81+.','LIFEWHEEL81',20,'once',1,0,4,0,0,0),
('WALLET_CHECK','Consulta Wallet81+','Consulta il tuo Wallet PV+ e lo storico.','WALLET_QUEST81',5,'weekly',0,1,4,0,0,0),
('WEEKLY_REVIEW','Review settimanale','Completa la review settimanale del tuo piano.','ESCALATION81',15,'weekly',0,1,4,0,0,0),
-- Livello 5 PRESIDIO81+ (aggiuntive)
('SHIELD_SOGLIA','SHIELD81+ sopra soglia','Porta SHIELD81+ sopra la soglia minima del tuo profilo.','SHIELD81',100,'once',1,0,5,0,0,0),
('LIFEWHEEL_CAP_STATUS','LIFEWHEEL81+ al cap status','Porta LIFEWHEEL81+ al cap del tuo status su almeno 6 spicchi.','LIFEWHEEL81',150,'once',1,0,5,0,0,0),
('CHECK_3_SCADENZE','Presidia 3 scadenze','Registra e controlla almeno 3 scadenze critiche.','SHIELD81',50,'monthly',0,1,5,0,0,0),
('CHECK_3_FOLLOWUP','3 Follow-up completati','Completa 3 follow-up nel mese.','CRM_ARENA81',50,'monthly',0,1,5,0,0,0),
('CICLO_ACADEMY','Ciclo Academy completo','Completa un ciclo formativo completo Academy 81+.','ACADEMY_QUEST81',200,'once',1,0,5,0,0,0),
('CICLO_CRM','Ciclo CRM completo','Completa il ciclo CRM: prospect → cliente.','CRM_ARENA81',200,'once',1,0,5,0,0,0),
('REVIEW_MENSILE','Review mensile','Completa la review mensile completa del sistema.','ESCALATION81',100,'monthly',0,1,5,0,0,0),
('BADGE_PRESIDIO','Badge Presidio','Sblocca il badge PRESIDIO81+ completando il percorso.','ESCALATION81',200,'once',1,0,5,0,0,0),
('RETENTION_MISSION','Missione Retention','Mantieni un cliente attivo per 3 mesi consecutivi.','CRM_ARENA81',100,'once',1,0,5,0,0,0);

-- ========================================================
-- SEED LEADGEN FLOWS V6
-- ========================================================
INSERT IGNORE INTO `leadgen_flows` (`flow_code`,`flow_name`,`trigger_event`,`target_segment`,`status`) VALUES
('SIC_ACTIVATED','Attivazione SIC-ID','sic_id_activated','non_profilato','active'),
('FIRST_MISSION_DONE','Prima missione completata','mission_complete','non_profilato','active'),
('ATECO_DONE','Profilazione ATECO completa','ateco_profiled','azienda','active'),
('AUDIT_DONE','Audit completato','audit_complete','azienda','active'),
('ACADEMY_START_FLOW','Inizio percorso Academy','academy_started','profilo_completo','active'),
('HACCP_TRIGGER','Trigger settore alimentare','ateco_profiled','horeca','active'),
('EDILIZIA_TRIGGER','Trigger settore edilizia','ateco_profiled','edilizia','active'),
('INACTIVITY_7D','Riattivazione 7 giorni','inactivity_7d','lead_freddo','active'),
('INACTIVITY_30D','Riattivazione 30 giorni','inactivity_30d','lead_freddo','active'),
('BOOSTER_SEEN','Visto BOOSTER81+','booster_impression','non_profilato','active'),
('STREAK_MILESTONE','Milestone streak','streak_milestone','profilo_completo','active'),
('LIFEWHEEL_CAP_FLOW','Spicchio LIFEWHEEL al cap','lifewheel_cap','profilo_completo','active');
