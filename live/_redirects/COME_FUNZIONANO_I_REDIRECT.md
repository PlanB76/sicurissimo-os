# Redirect domini verso le home dei sub-progetti

Ogni dominio del network punta alla sua home dentro 81plus.net.
Due modi per farlo su Hostinger.

## Modo 1, consigliato, parcheggio con redirect
Su hPanel, per ogni dominio aggiuntivo, imposta un redirect 301 verso la sottocartella.
Esempi:
- 81plus.shop  -> https://81plus.net/81plus.shop/
- 81plus.club  -> https://81plus.net/81plus.club/
- 81plus.zone  -> https://81plus.net/81plus.zone/
- 81plus.world -> https://81plus.net/81plus.world/

## Modo 2, dominio puntato sulla cartella
Se vuoi che il dominio mostri la cartella senza cambiare URL,
in hPanel imposta la document root del dominio sulla sottocartella corrispondente.
Esempio, document root di 81plus.shop -> public_html/81plus.shop

## Sub-progetti con app completa
Per i pianeti che diventano app vere (shop WooCommerce, club, ecc.)
il dominio va puntato sulla cartella dedicata, non in redirect.
