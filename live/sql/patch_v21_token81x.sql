-- 81+ TOKEN 81X. Ledger interno off-chain, mining attività, whitelist, airdrop, trasparenza.
-- I saldi 81X sono crediti interni dell ecosistema fino alla migrazione Web3 dichiarata.
CREATE TABLE IF NOT EXISTS x81_ledger(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  sic VARCHAR(191),
  delta DECIMAL(18,4) NOT NULL,
  reason VARCHAR(80) NOT NULL,
  ref VARCHAR(191),
  created_at VARCHAR(40),
  INDEX(account_id),
  INDEX(reason)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- stato giornaliero del mining per ogni utente, per cap e anti farming
CREATE TABLE IF NOT EXISTS x81_mining(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  giorno VARCHAR(10) NOT NULL,
  attivita_score INT DEFAULT 0,
  reward DECIMAL(18,4) DEFAULT 0,
  risk_score INT DEFAULT 0,
  created_at VARCHAR(40),
  INDEX(account_id),
  INDEX(giorno)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- whitelist prevendita e airdrop, legate al PIX81+ e al SIC-ID
CREATE TABLE IF NOT EXISTS x81_whitelist(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL UNIQUE,
  sic VARCHAR(191),
  tipo VARCHAR(20) DEFAULT 'whitelist',
  motivo VARCHAR(120),
  kyc INT DEFAULT 0,
  stato VARCHAR(16) DEFAULT 'in_attesa',
  created_at VARCHAR(40),
  INDEX(stato)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- log di trasparenza, hash giornaliero del ledger (proof of emission)
CREATE TABLE IF NOT EXISTS x81_trasparenza(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  giorno VARCHAR(10) NOT NULL UNIQUE,
  totale_emesso DECIMAL(18,4) DEFAULT 0,
  circolante DECIMAL(18,4) DEFAULT 0,
  utenti_attivi INT DEFAULT 0,
  hash_ledger VARCHAR(80),
  anchor_tx VARCHAR(191),
  created_at VARCHAR(40)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
