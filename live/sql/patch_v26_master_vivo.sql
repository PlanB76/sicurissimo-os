-- patch_v26_master_vivo.sql
-- Integrazione tabelle dal MASTER VIVO
-- 14 giugno 2026

-- PV+ ledger (valuta reward separata da PV)
CREATE TABLE IF NOT EXISTS pvplus_ledger(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  delta INT NOT NULL,
  reason VARCHAR(60) NOT NULL,
  ref VARCHAR(60) NULL,
  created_at VARCHAR(40) NOT NULL,
  INDEX(account_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SDK orders (Network 199, ELITE 499, ROYAL 999)
CREATE TABLE IF NOT EXISTS sdk_orders(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  tipo VARCHAR(16) NOT NULL,
  pv_costo INT NOT NULL,
  pvplus_bonus INT NOT NULL DEFAULT 0,
  stato VARCHAR(16) NOT NULL DEFAULT 'attivo',
  created_at VARCHAR(40) NOT NULL,
  INDEX(account_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Membership attive (tracking ricorrenza)
CREATE TABLE IF NOT EXISTS memberships(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  piano VARCHAR(24) NOT NULL,
  pv_mese INT NOT NULL,
  stato VARCHAR(16) NOT NULL DEFAULT 'attivo',
  rinnovo_il VARCHAR(20) NULL,
  created_at VARCHAR(40) NOT NULL,
  UNIQUE KEY uq_acc_piano(account_id,piano),
  INDEX(stato)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Ruoli cumulativi utente
CREATE TABLE IF NOT EXISTS user_roles(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  ruolo VARCHAR(24) NOT NULL,
  attivo TINYINT NOT NULL DEFAULT 1,
  created_at VARCHAR(40) NOT NULL,
  UNIQUE KEY uq_acc_ruolo(account_id,ruolo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Profili networker
CREATE TABLE IF NOT EXISTS networker_profiles(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL UNIQUE,
  rank_level INT NOT NULL DEFAULT 1,
  rank_nome VARCHAR(24) NOT NULL DEFAULT 'STARTER',
  vol_personale INT NOT NULL DEFAULT 0,
  vol_gruppo INT NOT NULL DEFAULT 0,
  mesi_conferma INT NOT NULL DEFAULT 0,
  mesi_crisi INT NOT NULL DEFAULT 0,
  attivo_giorno20 TINYINT NOT NULL DEFAULT 0,
  updated_at VARCHAR(40)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pass networker ricorrenti
CREATE TABLE IF NOT EXISTS networker_passes(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  tipo VARCHAR(24) NOT NULL,
  livello INT NOT NULL DEFAULT 1,
  pv_mese INT NOT NULL,
  stato VARCHAR(16) NOT NULL DEFAULT 'attivo',
  rinnovo_il VARCHAR(20) NULL,
  created_at VARCHAR(40) NOT NULL,
  UNIQUE KEY uq_acc_tipo(account_id,tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Profili elite/franchising
CREATE TABLE IF NOT EXISTS elite_profiles(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL UNIQUE,
  rank_level INT NOT NULL DEFAULT 1,
  rank_nome VARCHAR(24) NOT NULL DEFAULT 'PIONEER',
  tipo_point VARCHAR(16) NULL,
  comune VARCHAR(120) NULL,
  provincia VARCHAR(4) NULL,
  updated_at VARCHAR(40)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pass elite ricorrenti
CREATE TABLE IF NOT EXISTS elite_passes(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  livello INT NOT NULL DEFAULT 1,
  pv_mese INT NOT NULL,
  stato VARCHAR(16) NOT NULL DEFAULT 'attivo',
  rinnovo_il VARCHAR(20) NULL,
  created_at VARCHAR(40) NOT NULL,
  UNIQUE KEY uq_acc(account_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Profili club royal
CREATE TABLE IF NOT EXISTS club_profiles(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL UNIQUE,
  rank_level INT NOT NULL DEFAULT 1,
  rank_nome VARCHAR(24) NOT NULL DEFAULT 'PALLADIUM',
  updated_at VARCHAR(40)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pass club royal
CREATE TABLE IF NOT EXISTS club_passes(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL UNIQUE,
  livello INT NOT NULL DEFAULT 1,
  pv_mese INT NOT NULL,
  stato VARCHAR(16) NOT NULL DEFAULT 'attivo',
  rinnovo_il VARCHAR(20) NULL,
  created_at VARCHAR(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PayGate81+ log
CREATE TABLE IF NOT EXISTS paygate81_logs(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  tipo VARCHAR(24) NOT NULL,
  importo_eur DECIMAL(12,2) NOT NULL,
  pv_accreditati INT NOT NULL DEFAULT 0,
  pvplus_accreditati INT NOT NULL DEFAULT 0,
  metodo VARCHAR(16) NOT NULL,
  stato VARCHAR(16) NOT NULL DEFAULT 'pending',
  provider_ref VARCHAR(191) NULL,
  created_at VARCHAR(40) NOT NULL,
  INDEX(account_id),
  INDEX(stato)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Revolut review manuale
CREATE TABLE IF NOT EXISTS revolut_reviews(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  paygate_log_id BIGINT NOT NULL,
  account_id BIGINT NOT NULL,
  importo_eur DECIMAL(12,2) NOT NULL,
  stato VARCHAR(16) NOT NULL DEFAULT 'da_verificare',
  admin_note VARCHAR(255) NULL,
  verificato_da VARCHAR(40) NULL,
  created_at VARCHAR(40) NOT NULL,
  INDEX(stato)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Referral tracking
CREATE TABLE IF NOT EXISTS referrals(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  invitante_id BIGINT NOT NULL,
  invitato_id BIGINT NULL,
  codice VARCHAR(16) NOT NULL,
  canale VARCHAR(24) NULL,
  stato VARCHAR(16) NOT NULL DEFAULT 'pending',
  created_at VARCHAR(40) NOT NULL,
  convertito_il VARCHAR(40) NULL,
  INDEX(invitante_id),
  INDEX(codice)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- QR codes
CREATE TABLE IF NOT EXISTS qr_codes(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  tipo VARCHAR(24) NOT NULL,
  url VARCHAR(500) NOT NULL,
  created_at VARCHAR(40) NOT NULL,
  INDEX(account_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Green81+ alberi
CREATE TABLE IF NOT EXISTS green81_trees(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  motivo VARCHAR(60) NOT NULL,
  provider VARCHAR(40) NOT NULL DEFAULT 'treedom',
  provider_ref VARCHAR(120) NULL,
  stato VARCHAR(16) NOT NULL DEFAULT 'pending',
  created_at VARCHAR(40) NOT NULL,
  INDEX(account_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Streaks gamification
CREATE TABLE IF NOT EXISTS streaks(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  tipo VARCHAR(24) NOT NULL,
  giorni INT NOT NULL DEFAULT 0,
  max_giorni INT NOT NULL DEFAULT 0,
  ultimo_check VARCHAR(40) NULL,
  UNIQUE KEY uq_acc_tipo(account_id,tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Event passes
CREATE TABLE IF NOT EXISTS event_passes(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  event_id BIGINT NOT NULL,
  tipo_pass VARCHAR(16) NOT NULL DEFAULT 'free',
  stato VARCHAR(16) NOT NULL DEFAULT 'attivo',
  created_at VARCHAR(40) NOT NULL,
  UNIQUE KEY uq_acc_ev(account_id,event_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Partner tracking
CREATE TABLE IF NOT EXISTS partner_clicks(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NULL,
  partner VARCHAR(40) NOT NULL,
  url VARCHAR(500) NOT NULL,
  created_at VARCHAR(40) NOT NULL,
  INDEX(partner)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS partner_conversions(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  click_id BIGINT NULL,
  account_id BIGINT NOT NULL,
  partner VARCHAR(40) NOT NULL,
  prodotto VARCHAR(120) NOT NULL,
  importo_eur DECIMAL(12,2) NOT NULL,
  provvigione_eur DECIMAL(12,2) NOT NULL,
  stato VARCHAR(16) NOT NULL DEFAULT 'pending',
  created_at VARCHAR(40) NOT NULL,
  INDEX(account_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Network vendite e commissioni
CREATE TABLE IF NOT EXISTS network_sales(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  seller_id BIGINT NOT NULL,
  buyer_id BIGINT NOT NULL,
  prodotto VARCHAR(60) NOT NULL,
  pv_importo INT NOT NULL,
  mese VARCHAR(7) NOT NULL,
  created_at VARCHAR(40) NOT NULL,
  INDEX(seller_id),
  INDEX(mese)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS network_commissions(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  beneficiary_id BIGINT NOT NULL,
  sale_id BIGINT NOT NULL,
  livello INT NOT NULL,
  pv_commissione INT NOT NULL,
  eur_commissione DECIMAL(12,2) NOT NULL DEFAULT 0,
  mese VARCHAR(7) NOT NULL,
  stato VARCHAR(16) NOT NULL DEFAULT 'calcolato',
  pagato_il VARCHAR(40) NULL,
  created_at VARCHAR(40) NOT NULL,
  INDEX(beneficiary_id),
  INDEX(mese)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Rank history e conferme
CREATE TABLE IF NOT EXISTS rank_history(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  network VARCHAR(16) NOT NULL,
  rank_da INT NOT NULL,
  rank_a INT NOT NULL,
  motivo VARCHAR(60) NOT NULL,
  created_at VARCHAR(40) NOT NULL,
  INDEX(account_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS rank_confirmations(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  network VARCHAR(16) NOT NULL,
  rank_level INT NOT NULL,
  mese VARCHAR(7) NOT NULL,
  fatturato_pv INT NOT NULL DEFAULT 0,
  confermato TINYINT NOT NULL DEFAULT 0,
  created_at VARCHAR(40) NOT NULL,
  UNIQUE KEY uq_acc_net_mese(account_id,network,mese)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Piano Equilibrio punti PV+
CREATE TABLE IF NOT EXISTS equilibrium_points(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  mese VARCHAR(7) NOT NULL,
  pvplus_personali INT NOT NULL DEFAULT 0,
  pvplus_rete INT NOT NULL DEFAULT 0,
  pvplus_missioni INT NOT NULL DEFAULT 0,
  pvplus_qualita INT NOT NULL DEFAULT 0,
  pvplus_totale INT NOT NULL DEFAULT 0,
  pv_equivalente DECIMAL(12,2) NOT NULL DEFAULT 0,
  created_at VARCHAR(40) NOT NULL,
  UNIQUE KEY uq_acc_mese(account_id,mese)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tesoreria Nazionale Green81+
CREATE TABLE IF NOT EXISTS treasury_national(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  mese VARCHAR(7) NOT NULL,
  fonte VARCHAR(60) NOT NULL,
  importo_eur DECIMAL(12,2) NOT NULL,
  destinazione VARCHAR(60) NULL,
  stato VARCHAR(16) NOT NULL DEFAULT 'accantonato',
  created_at VARCHAR(40) NOT NULL,
  INDEX(mese)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- POINT81+ domande franchising
CREATE TABLE IF NOT EXISTS point81_applications(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  tipo VARCHAR(16) NOT NULL,
  comune VARCHAR(120) NOT NULL,
  provincia VARCHAR(4) NOT NULL,
  regione VARCHAR(40) NOT NULL,
  stato VARCHAR(16) NOT NULL DEFAULT 'ricevuta',
  note TEXT NULL,
  created_at VARCHAR(40) NOT NULL,
  INDEX(account_id),
  INDEX(stato)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- NFT passes
CREATE TABLE IF NOT EXISTS nft_passes(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  tipo VARCHAR(24) NOT NULL,
  token_id VARCHAR(40) NULL,
  contract_address VARCHAR(42) NULL,
  stato VARCHAR(16) NOT NULL DEFAULT 'attivo',
  created_at VARCHAR(40) NOT NULL,
  INDEX(account_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Email optins
CREATE TABLE IF NOT EXISTS email_optins(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  tipo VARCHAR(24) NOT NULL,
  attivo TINYINT NOT NULL DEFAULT 1,
  created_at VARCHAR(40) NOT NULL,
  UNIQUE KEY uq_acc_tipo(account_id,tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Telegram users
CREATE TABLE IF NOT EXISTS telegram_users(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL UNIQUE,
  tg_id BIGINT NULL,
  tg_username VARCHAR(40) NULL,
  stato VARCHAR(16) NOT NULL DEFAULT 'collegato',
  created_at VARCHAR(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- YouTube content index
CREATE TABLE IF NOT EXISTS youtube_content_index(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  video_id VARCHAR(16) NOT NULL UNIQUE,
  titolo VARCHAR(255) NOT NULL,
  categoria VARCHAR(40) NULL,
  durata_sec INT NULL,
  pubblicato_il VARCHAR(20) NULL,
  playlist VARCHAR(60) NULL,
  stato VARCHAR(16) NOT NULL DEFAULT 'attivo',
  created_at VARCHAR(40) NOT NULL,
  INDEX(categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin logs
CREATE TABLE IF NOT EXISTS admin_logs(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  admin_id BIGINT NOT NULL,
  azione VARCHAR(60) NOT NULL,
  target_type VARCHAR(24) NULL,
  target_id BIGINT NULL,
  dettaglio TEXT NULL,
  created_at VARCHAR(40) NOT NULL,
  INDEX(admin_id),
  INDEX(created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Automation logs
CREATE TABLE IF NOT EXISTS automation_logs(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  tipo VARCHAR(40) NOT NULL,
  stato VARCHAR(16) NOT NULL,
  dettaglio TEXT NULL,
  created_at VARCHAR(40) NOT NULL,
  INDEX(tipo),
  INDEX(created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Config: aggiornamento blocchi PV e SDK
INSERT IGNORE INTO app_settings(k,v) VALUES
  ('pv_blocchi','[50,100,300,500,1000,2500,5000]'),
  ('sdk_network_pv','199'),
  ('sdk_network_pvplus','499'),
  ('sdk_elite_pv','499'),
  ('sdk_elite_pvplus','999'),
  ('sdk_royal_pv','999'),
  ('sdk_royal_pvplus','1499'),
  ('pvplus_activity_rate','1'),
  ('pvplus_activity_min','5'),
  ('pvplus_equilibrio_rate','0.20'),
  ('giorno_compensi','20'),
  ('rank_conferma_mesi','6'),
  ('rank_crisi_mesi','2'),
  ('rank_pavimento','3'),
  ('green81_pv_soglia','5000');
