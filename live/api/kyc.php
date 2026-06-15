<?php
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'non autenticato'],401); $pdo=db(); $in=body(); $action=$_GET['action']??'stato';
function kycRow($pdo,$id){ $st=$pdo->prepare('SELECT * FROM kyc WHERE account_id=?'); $st->execute([$id]); return $st->fetch(); }
if($action==='stato'){ $k=kycRow($pdo,$a['id']);
  j(['ok'=>true,'livello'=>$k?(int)$k['livello']:0,'stato'=>$k?$k['stato']:'assente','provider'=>$k['provider']??null]); }
if($action==='invia'){
  if(!csrfCheck()) j(['ok'=>false,'err'=>'token csrf mancante'],403);
  if(!rateOk('kyc:'.$a['id'],10,3600)) j(['ok'=>false,'err'=>'troppe richieste'],429);
  $nome=trim($in['nome']??''); $cognome=trim($in['cognome']??''); $cf=strtoupper(trim($in['codice_fiscale']??''));
  $nasc=trim($in['data_nascita']??''); $dt=trim($in['doc_tipo']??''); $dn=trim($in['doc_numero']??'');
  if($nome===''||$cognome===''||$cf===''||$dn==='') j(['ok'=>false,'err'=>'compila nome, cognome, codice fiscale e numero documento'],422);
  if(!preg_match('/^[A-Z0-9]{11,16}$/',$cf)) j(['ok'=>false,'err'=>'codice fiscale non valido'],422);
  // livello 1, dati dichiarati. Stato in_verifica: la verifica forte va fatta da provider KYC o SPID.
  upsertKyc($pdo,$a['id'],['nome'=>$nome,'cognome'=>$cognome,'codice_fiscale'=>$cf,'data_nascita'=>$nasc,'doc_tipo'=>$dt,'doc_numero'=>$dn,'livello'=>1,'stato'=>'in_verifica','provider'=>'dichiarato','updated_at'=>gmdate('c')]);
  $pdo->prepare('UPDATE accounts SET codice_fiscale=COALESCE(codice_fiscale,?),kyc_level=1 WHERE id=?')->execute([$cf,$a['id']]);
  ev('kyc_inviato',$a['id'],['livello'=>1]); j(['ok'=>true,'livello'=>1,'stato'=>'in_verifica','nota'=>'Dati ricevuti. La verifica forte di identità avviene tramite provider KYC o SPID quando attivi.']);
}
function upsertKyc($pdo,$id,$c){
  $r=kycRow($pdo,$id);
  if($r){ $set=implode(',',array_map(fn($k)=>"$k=?",array_keys($c))); $pdo->prepare("UPDATE kyc SET $set WHERE account_id=?")->execute([...array_values($c),$id]); }
  else { $cols=array_merge(['account_id'=>$id],$c); $ph=implode(',',array_fill(0,count($cols),'?')); $pdo->prepare("INSERT INTO kyc(".implode(',',array_keys($cols)).") VALUES($ph)")->execute(array_values($cols)); }
}
j(['ok'=>false,'err'=>'azione sconosciuta'],404);
