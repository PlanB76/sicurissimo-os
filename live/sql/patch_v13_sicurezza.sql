-- Sicurezza account. Colonna per la 2FA via email e tabella dei codici a tempo.
ALTER TABLE accounts ADD COLUMN twofa VARCHAR(10) DEFAULT 'off';
CREATE TABLE IF NOT EXISTS twofa_codes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  account_id INT NOT NULL,
  code_hash VARCHAR(190) NOT NULL,
  scopo VARCHAR(20) DEFAULT 'login',
  expires_at VARCHAR(40),
  used TINYINT DEFAULT 0,
  created_at VARCHAR(40),
  KEY idx_acc (account_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
