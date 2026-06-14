-- 81+ PIX LIVING MAP. Tipi dichiarati e trasparenti per ogni PIX.
-- founder, posizioni della direzione. riservato, partner e whitelist futura.
-- utente, preso da un cliente reale. libero, ancora disponibile.
-- Nessuna attività simulata, nessun utente finto. Solo stati reali e dichiarati.
ALTER TABLE pixel_muro ADD COLUMN tipo VARCHAR(16) DEFAULT 'libero';
