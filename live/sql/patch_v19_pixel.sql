-- 81+ PIX81+ FOUNDING NODE. 1000 posizioni permanenti del Metaverso 81+.
-- Ogni PIX 1000 (euro o PV), max 200 PV di sconto, max 20 per utente, per sempre.
CREATE TABLE IF NOT EXISTS pixel_muro(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  pos INT NOT NULL UNIQUE,
  account_id BIGINT,
  sic VARCHAR(191),
  azienda VARCHAR(191),
  descrizione VARCHAR(500),
  url VARCHAR(191),
  social VARCHAR(255),
  colore VARCHAR(16) DEFAULT '#E8501A',
  logo VARCHAR(191),
  img_path VARCHAR(255),
  img_kb INT DEFAULT 0,
  link_univoco VARCHAR(191),
  regalato_da VARCHAR(191),
  visite INT DEFAULT 0,
  click INT DEFAULT 0,
  inviti INT DEFAULT 0,
  iscritti INT DEFAULT 0,
  stato VARCHAR(16) DEFAULT 'attivo',
  created_at VARCHAR(40),
  updated_at VARCHAR(40),
  INDEX(account_id),
  INDEX(sic),
  INDEX(link_univoco)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS pixel_ordini(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  sic VARCHAR(191),
  posizioni VARCHAR(500),
  n_pix INT NOT NULL,
  pv_sconto INT DEFAULT 0,
  euro_da_pagare DECIMAL(10,2) NOT NULL,
  paypal_order_id VARCHAR(191),
  stato VARCHAR(16) DEFAULT 'in_attesa',
  regalo_sic VARCHAR(191),
  created_at VARCHAR(40),
  paid_at VARCHAR(40),
  INDEX(account_id),
  INDEX(paypal_order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
