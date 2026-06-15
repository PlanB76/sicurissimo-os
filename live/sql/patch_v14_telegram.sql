-- Telegram. Aggancio univoco del SIC ID all account Telegram e codici a tempo.
ALTER TABLE accounts ADD COLUMN telegram_id VARCHAR(32) NULL;
CREATE TABLE IF NOT EXISTS tg_links (
  code VARCHAR(40) PRIMARY KEY,
  account_id BIGINT NOT NULL,
  expires_at VARCHAR(40)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
