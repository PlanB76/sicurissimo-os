-- 81+ PIX81 pagina unica. Profilo completo sul PIX e promo settimanale reale.
ALTER TABLE pixel_muro ADD COLUMN indirizzo VARCHAR(255);
ALTER TABLE pixel_muro ADD COLUMN email VARCHAR(191);
ALTER TABLE pixel_ordini ADD COLUMN promo TINYINT DEFAULT 0;
ALTER TABLE pixel_ordini ADD COLUMN profilo TEXT;
