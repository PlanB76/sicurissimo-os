-- 81+ ABBONAMENTI RICORRENTI e AVVISI ALLA DIREZIONE.
-- Abbonamenti pagabili in euro (PayPal ricorrente) o in PV (rinnovo periodico dal wallet).
CREATE TABLE IF NOT EXISTS abbonamenti(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  sic VARCHAR(191),
  prodotto VARCHAR(60) NOT NULL,
  piano VARCHAR(40) NOT NULL,
  prezzo_euro DECIMAL(10,2) DEFAULT 0,
  prezzo_pv INT DEFAULT 0,
  metodo VARCHAR(16) DEFAULT 'euro',
  ciclo VARCHAR(16) DEFAULT 'mensile',
  stato VARCHAR(20) DEFAULT 'attivo',
  paypal_sub_id VARCHAR(191),
  prossimo_rinnovo VARCHAR(40),
  creato_il VARCHAR(40),
  disdetto_il VARCHAR(40),
  motivo_fine VARCHAR(191),
  INDEX(account_id),
  INDEX(stato),
  INDEX(paypal_sub_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- coda avvisi per la direzione, mostrata nella dashboard admin
CREATE TABLE IF NOT EXISTS admin_avvisi(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  tipo VARCHAR(40) NOT NULL,
  account_id BIGINT,
  sic VARCHAR(191),
  titolo VARCHAR(191),
  dettaglio VARCHAR(500),
  gravita VARCHAR(16) DEFAULT 'media',
  letto INT DEFAULT 0,
  created_at VARCHAR(40),
  INDEX(tipo),
  INDEX(letto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
