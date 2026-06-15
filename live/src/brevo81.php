<?php
// 81+ PONTE BREVO. Sincronizza contatti e attributi col marketing Brevo via API ufficiale.
// Chiave SOLO in variabile d'ambiente BREVO_API_KEY. Senza chiave il ponte resta spento, zero errori.
function brevoOn(){ $k=getenv('BREVO_API_KEY'); return $k!==false && $k!=='' && function_exists('curl_init'); }
function brevoApi($metodo,$path,$body=null){
  if(!brevoOn()) return null;
  $ch=curl_init('https://api.brevo.com/v3'.$path);
  $h=['api-key: '.getenv('BREVO_API_KEY'),'Content-Type: application/json','Accept: application/json'];
  curl_setopt_array($ch,[CURLOPT_CUSTOMREQUEST=>$metodo,CURLOPT_HTTPHEADER=>$h,CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>12]);
  if($body!==null) curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($body));
  $r=curl_exec($ch); $c=curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
  return ['code'=>$c,'body'=>json_decode($r,true)];
}
function brevoUpsert($email,$attrs=[]){
  if(!brevoOn()) return false;
  $lista=(int)(getenv('BREVO_LIST_ID')?:0);
  $p=['email'=>$email,'attributes'=>$attrs,'updateEnabled'=>true];
  if($lista) $p['listIds']=[$lista];
  $r=brevoApi('POST','/contacts',$p);
  return $r && in_array($r['code'],[200,201,204]);
}
// giro del cron, porta in Brevo gli account nuovi a blocchi, senza mai bloccare il resto
function brevoSyncNuovi($pdo){
  if(!brevoOn()) return 0;
  $f=__DIR__.'/../data/brevo_stato.json';
  $st=json_decode(@file_get_contents($f),true)?:['ultimo_id'=>0];
  $q=$pdo->prepare('SELECT id,sic,email,nome,cognome,tipo,ref_by,created_at FROM accounts WHERE id>? ORDER BY id ASC LIMIT 60');
  $q->execute([(int)$st['ultimo_id']]); $n=0;
  foreach($q->fetchAll() as $a){
    if(strpos($a['email'],'@wallet.81plus')===false){
      brevoUpsert($a['email'],['NOME'=>$a['nome']?:'','COGNOME'=>$a['cognome']?:'','SIC'=>$a['sic'],'TIPO'=>$a['tipo'],'SPONSOR'=>$a['ref_by']?:'','ORIGINE'=>'hub1']);
      $n++;
    }
    $st['ultimo_id']=$a['id'];
  }
  if($n) $st['sync_at']=gmdate('c');
  @file_put_contents($f,json_encode($st));
  return $n;
}
