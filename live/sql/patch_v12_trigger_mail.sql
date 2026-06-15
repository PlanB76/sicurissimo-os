-- Email comportamentali. Una riga per ogni invio, la coppia email piu trigger e unica,
-- cosi ogni persona riceve ogni email comportamentale una volta sola.
CREATE TABLE IF NOT EXISTS trigger_sent (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL,
  trig VARCHAR(60) NOT NULL,
  created_at VARCHAR(40),
  UNIQUE KEY uq_email_trig (email, trig)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
