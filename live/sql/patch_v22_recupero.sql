-- 81+ RECUPERO PAGAMENTI FALLITI. Carte scadute, addebiti rifiutati, problemi tecnici.
-- Una fetta dei pagamenti ricorrenti salta per questi motivi. Qui li recuperiamo.
CREATE TABLE IF NOT EXISTS pagamenti_falliti(
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT NOT NULL,
  sic VARCHAR(191),
  abbonamento_id BIGINT,
  prodotto VARCHAR(60),
  piano VARCHAR(40),
  importo_euro DECIMAL(10,2) DEFAULT 0,
  motivo VARCHAR(120),
  paypal_sub_id VARCHAR(191),
  tentativi INT DEFAULT 0,
  ultimo_sollecito VARCHAR(40),
  stato VARCHAR(20) DEFAULT 'aperto',
  created_at VARCHAR(40),
  risolto_il VARCHAR(40),
  INDEX(account_id),
  INDEX(stato)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
