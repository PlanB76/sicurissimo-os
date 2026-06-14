<?php
// PREDISPOSIZIONE SPID. Attenzione, SPID non si accende da codice.
// Per accettare SPID un soggetto privato deve accreditarsi presso AgID come
// fornitore di servizi, direttamente o tramite un soggetto aggregatore,
// con metadata SAML2, certificati e convenzione. Stesso discorso per CIE id.
// Questo endpoint e la cucitura: quando l accreditamento c e, qui si monta
// il flusso SAML del fornitore scelto e il login SPID apre la sessione SIC.
require_once __DIR__.'/../src/db.php';
j(['ok'=>false,'stato'=>'predisposto','err'=>'SPID non ancora attivo. Richiede accreditamento AgID come service provider, di norma tramite soggetto aggregatore.'],501);
