<?php
// SIC-ID, identita unica dell ecosistema 81+.
// Capienza: alfabeto di 30 caratteri senza ambigui, 8 posizioni = 30^8, oltre 656 miliardi di combinazioni.
// Regge 21 milioni di utenti con densita di riempimento sotto lo 0,004 per cento, collisioni rarissime.
// La difesa vera resta UNIQUE su sic nel database piu retry sull insert, questo previene a monte.
define('SIC_ALFABETO','ABCDEFGHJKMNPQRSTUVWXYZ23456789'); // niente I O 0 1, leggibilita
define('SIC_LUNGHEZZA',8);

function nuovoSic(){
  $alf=SIC_ALFABETO; $n=strlen($alf); $s='';
  for($i=0;$i<SIC_LUNGHEZZA;$i++){ $s.=$alf[random_int(0,$n-1)]; }
  return 'SIC-'.$s;
}

// genera con verifica leggera, ma la difesa primaria e l UNIQUE su sic con retry all insert
function generaSic(PDO $pdo){
  for($t=0;$t<60;$t++){
    $sic=nuovoSic();
    $q=$pdo->prepare('SELECT 1 FROM accounts WHERE sic=?'); $q->execute([$sic]);
    if(!$q->fetch()) return $sic;
  }
  throw new Exception('SIC pool saturo, evento statisticamente impossibile, controllare il database');
}

// codici univoci dei quattro wallet interni, legati al SIC
function creaWallets(PDO $pdo,$accountId,$sic){
  $suff=substr($sic,4); // parte univoca del SIC
  foreach(['PV','EUR','SAF','CRY'] as $t){
    $code='81W-'.$t.'-'.$suff.'-'.strtoupper(bin2hex(random_bytes(2)));
    try{ $pdo->prepare('INSERT INTO wallets(account_id,tipo,wallet_code,created_at) VALUES(?,?,?,?)')->execute([$accountId,$t,$code,gmdate('c')]); }
    catch(PDOException $e){ /* gia esistente */ }
  }
}
