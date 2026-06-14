-- Patch per installazioni gia esistenti. Su installazione nuova basta schema.sql.
ALTER TABLE accounts ADD COLUMN codice_fiscale VARCHAR(16) NULL;
ALTER TABLE accounts ADD COLUMN eth_address VARCHAR(42) NULL;
ALTER TABLE accounts ADD COLUMN avatar_url VARCHAR(255) NULL;
CREATE TABLE IF NOT EXISTS wallets(id BIGINT AUTO_INCREMENT PRIMARY KEY,account_id BIGINT NOT NULL,tipo VARCHAR(12) NOT NULL,wallet_code VARCHAR(32) NOT NULL UNIQUE,created_at VARCHAR(40) NOT NULL,UNIQUE KEY uq_acc_tipo(account_id,tipo)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
