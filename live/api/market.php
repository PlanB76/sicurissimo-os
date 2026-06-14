<?php
require_once __DIR__.'/../src/db.php';
// Quotazioni live BTC ETH in USDT, con cache 60s su tabella rate_limits riusata come kv leggera.
$cacheFile=sys_get_temp_dir().'/81plus_prices.json';
$fresh=false;
if(is_file($cacheFile) && (time()-filemtime($cacheFile))<60){ $data=json_decode(file_get_contents($cacheFile),true); $fresh=true; }
if(!$fresh){
  $url='https://api.coingecko.com/api/v3/simple/price?ids=bitcoin,ethereum,tether&vs_currencies=usd';
  $ctx=stream_context_create(['http'=>['timeout'=>5,'header'=>"User-Agent: 81plus\r\n"]]);
  $raw=@file_get_contents($url,false,$ctx); $j=$raw?json_decode($raw,true):null;
  if($j && isset($j['bitcoin']['usd'])){
    $data=['BTC'=>$j['bitcoin']['usd'],'ETH'=>$j['ethereum']['usd'],'USDT'=>$j['tether']['usd']??1,'PV'=>1,'ts'=>gmdate('c')];
    @file_put_contents($cacheFile,json_encode($data));
  } else { // fallback ultimo valore salvato, altrimenti zero con flag
    $data=is_file($cacheFile)?json_decode(file_get_contents($cacheFile),true):['BTC'=>0,'ETH'=>0,'USDT'=>1,'PV'=>1,'ts'=>null,'stale'=>true];
  }
}
j(['ok'=>true,'prezzi'=>$data,'nota'=>'Quotazioni di mercato indicative. PV e credito interno, 1 PV vale 1 unita come sconto, non è una valuta.']);
