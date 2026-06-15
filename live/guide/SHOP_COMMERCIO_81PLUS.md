# Shop e commercio 81+, come lo monti su Hostinger

## La strategia in breve
Lo shop dentro 81plus.net che hai gia (shop.html) e la vetrina veloce, brandizzata, con carrello e pagamento in euro o PV.
Per il commercio vero, con magazzino, ordini e spedizioni automatiche, usi WooCommerce su WordPress.
WooCommerce diventa il motore unico, i tre fornitori si collegano dentro.

## Perche WooCommerce come motore unico
Un solo carrello, un solo checkout, un solo pannello ordini.
I tre fornitori fanno cose diverse e si integrano tutti in WooCommerce.

## I tre fornitori, chi fa cosa
- BigBuy, DPI e prodotti di sicurezza fisici. Stock in Europa, pacco neutro o brandizzato, dropshipping vero. Plugin ufficiale BigBuy per WooCommerce.
- Printify, abbigliamento e gadget brandizzati 81+, print on demand. App Printify per WooCommerce.
- Gelato, stampa locale globale, ottimo per poster, taccuini, stampe. Plugin Gelato per WooCommerce.

## Montaggio passo per passo su Hostinger
1. Su hPanel installa WordPress in un sottodominio o cartella, ad esempio shop.81plus.net oppure 81plus.net/store
2. Installa il plugin WooCommerce
3. Imposta valuta euro, paese Italia, aliquota IVA
4. Collega PayPal in WooCommerce, lo stesso conto che usi per le membership
5. Installa e collega i tre plugin fornitore
   - BigBuy, inserisci la tua API key BigBuy, scegli le categorie DPI da importare
   - Printify, collega l account, crea i prodotti con il logo 81+, pubblica su WooCommerce
   - Gelato, collega l account, carica i design, pubblica i prodotti
6. Imposta le spedizioni, ogni fornitore spedisce dal suo magazzino
7. Brand, tema scuro, arancione 81+, logo, stesso stile del sito

## Il legame con l ecosistema 81+
- Lo shop.html dentro 81plus.net resta la vetrina rapida e il punto PV
- Il bottone Shop nei menu porta qui
- Quando un cliente compra, gli accrediti PV con la regola gia pronta, acquisto shop vale 60 PV
- Per pagare in PV usi il flusso interno, api/shop.php az checkout_pv, gia pronto e sicuro

## Regole ferme, non si toccano
- I DPI devono avere marcatura CE reale e conformita REACH, mai DPI senza CE
- Amazon resta solo affiliazione trasparente, mai white label, vietato dal contratto Associates
- Printify e Gelato si possono brandizzare 81+, sono print on demand tuoi
- BigBuy in pacco neutro o brandizzato, come da loro condizioni

## Cosa e gia pronto adesso
- shop.html, vetrina con 14 prodotti di esempio dai tre fornitori, carrello, euro e PV
- data/shop_catalogo.json, il catalogo, lo sostituisci o lo alimenti via API
- api/shop.php, checkout euro verso PayPal e checkout PV che scala dal saldo
- Accredito PV automatico su acquisto, regola gia nel motore

## Prossimo passo consigliato
Parti con Printify per il brandizzato, e veloce e senza rischi.
Aggiungi BigBuy quando vuoi i DPI fisici col magazzino europeo.
Gelato per le stampe locali, quando servono poster e materiali.
