-- Fabbrica V2. Archivio documenti con revisione, listino prezzi personale, preventivi e contratti brandizzati.
CREATE TABLE IF NOT EXISTS docs_salvati (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  doc_id VARCHAR(40) NOT NULL,
  titolo VARCHAR(191),
  campi TEXT,
  updated_at VARCHAR(40)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS listino_voci (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  voce VARCHAR(191) NOT NULL,
  descrizione TEXT,
  unita VARCHAR(30),
  prezzo DECIMAL(12,2) NOT NULL DEFAULT 0,
  iva INT NOT NULL DEFAULT 22,
  updated_at VARCHAR(40)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS preventivi (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  tipo VARCHAR(20) NOT NULL DEFAULT 'preventivo',
  numero VARCHAR(30),
  cliente TEXT,
  righe TEXT,
  condizioni TEXT,
  extra TEXT,
  totale DECIMAL(12,2) DEFAULT 0,
  stato VARCHAR(20) DEFAULT 'bozza',
  updated_at VARCHAR(40)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE accounts ADD COLUMN brand_logo VARCHAR(255) NULL;
ALTER TABLE accounts ADD COLUMN brand_colore VARCHAR(10) NULL;
ALTER TABLE accounts ADD COLUMN brand_contatti VARCHAR(255) NULL;
