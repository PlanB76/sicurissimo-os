-- 81+ Patch v25: regole monetarie aggiornate.
-- PV acquistati vs PV gamification, SAF acquisto, sconto 20%

-- Tipo di PV nel ledger: acquisto_pv_blocco vs gamification
-- Già gestito dal campo reason in pv_ledger, non serve colonna nuova.

-- SAF ledger per il Web3
CREATE TABLE IF NOT EXISTS saf_ledger(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  amount DECIMAL(18,8) NOT NULL,
  reason VARCHAR(60) NOT NULL,
  ref_id BIGINT NULL,
  created_at VARCHAR(40) NOT NULL,
  INDEX(account_id),
  INDEX(reason)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SAF ricariche (acquisto con euro o USDT)
CREATE TABLE IF NOT EXISTS saf_ricariche(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  saf DECIMAL(18,8) NOT NULL,
  euro DECIMAL(12,2) NULL,
  usdt DECIMAL(18,8) NULL,
  metodo VARCHAR(20) NOT NULL DEFAULT 'paypal',
  stato VARCHAR(20) NOT NULL DEFAULT 'in_attesa',
  paypal_order_id VARCHAR(60),
  paid_at VARCHAR(40),
  created_at VARCHAR(40) NOT NULL,
  INDEX(account_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Config per tetti sconto
INSERT IGNORE INTO app_settings(chiave,valore) VALUES('pv_sconto_max_pct','20');
INSERT IGNORE INTO app_settings(chiave,valore) VALUES('pv_sconto_promo_pct','50');
INSERT IGNORE INTO app_settings(chiave,valore) VALUES('saf_to_81x_max_pct','20');
INSERT IGNORE INTO app_settings(chiave,valore) VALUES('pv_blocchi','100,300,500');
