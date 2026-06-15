<?php
// 81+ AVVISI ALLA DIREZIONE. Coda di notifiche per la dashboard admin + email.
// Usato da abbonamenti (disdetta, sospensione, recesso), pagamenti, eventi critici.
require_once __DIR__.'/db.php';

function avvisoTronca($s,$n){ return function_exists('mb_substr')?mb_substr($s,0,$n):substr($s,0,$n); }
function avvisoDirezione($tipo,$titolo,$dettaglio='',$accountId=null,$sic=null,$gravita='media'){
  $pdo=db();
  try{
    $pdo->prepare('INSERT INTO admin_avvisi(tipo,account_id,sic,titolo,dettaglio,gravita,letto,created_at) VALUES(?,?,?,?,?,?,0,?)')
        ->execute([$tipo,$accountId,$sic,avvisoTronca($titolo,191),avvisoTronca($dettaglio,500),$gravita,gmdate('c')]);
  }catch(Throwable $e){}
  // email alla direzione, best effort
  if(function_exists('mailGeneric')){
    $dest=getenv('ADMIN_EMAIL')?:'info@81plus.net';
    @mailGeneric($dest,'81+ avviso, '.$titolo, $titolo."\n\n".$dettaglio."\n\nSIC ".($sic?:'-')."\nGravità ".$gravita."\nLa direzione lo trova anche nella dashboard admin.");
  }
  if(function_exists('ev')) @ev('admin_avviso',$accountId,['tipo'=>$tipo,'titolo'=>$titolo]);
}
