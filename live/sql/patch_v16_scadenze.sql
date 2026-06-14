-- Scadenziario ciclico, prezzi PV sui documenti, ricariche PV.
CREATE TABLE IF NOT EXISTS scadenze_cicliche (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  titolo VARCHAR(191) NOT NULL,
  categoria VARCHAR(40),
  primo_termine VARCHAR(10) NOT NULL,
  ricorrenza_mesi INT NOT NULL DEFAULT 12,
  note VARCHAR(255),
  attivo TINYINT NOT NULL DEFAULT 1,
  updated_at VARCHAR(40)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS pv_ricariche (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  pack VARCHAR(30),
  pv INT NOT NULL,
  euro DECIMAL(10,2) NOT NULL,
  provider VARCHAR(20),
  provider_ref VARCHAR(100),
  stato VARCHAR(20) DEFAULT 'in_attesa',
  created_at VARCHAR(40)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE accounts ADD COLUMN cal_token VARCHAR(48) NULL;
ALTER TABLE docs_salvati ADD COLUMN pagato TINYINT NOT NULL DEFAULT 0;
