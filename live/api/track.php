<?php
// Traccia i click verso i partner e reindirizza ai referral della direzione.
// Ogni click e un evento, con account se loggato. Il pannello DIO li conta.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$pdo=db();
$to=strtolower(trim($_GET['to']??''));
$cm=json_decode(@file_get_contents(__DIR__.'/../data/community.json'),true)?:[];
$dest=[
 'anfos'=>'https://corsi.elearningsicurezza.com/pid/2377',
 'ac1'=>'https://corsi.elearningsicurezza.com/pid/2377',
 'lezione'=>'https://www.lezione-online.it/?ref=6232899',
 'ac2'=>'https://www.lezione-online.it/?ref=6232899',
 'youtube'=>$cm['youtube']??'https://www.youtube.com/@sicurissimo',
 'youtube_live'=>$cm['youtube_live']??'https://www.youtube.com/@sicurissimo/streams'
];
foreach(($cm['telegram_gruppi']??[]) as $g){
  $l=trim($g['link']??'');
  $dest['tg_'.$g['id']]=$l!==''?$l:'https://wa.me/393388771737?text='.rawurlencode('Voglio entrare nel gruppo Telegram '.$g['nome']);
}
if(!isset($dest[$to])){ http_response_code(404); exit('destinazione sconosciuta'); }
$a=currentAccount();
ev('partner_click',$a?$a['id']:null,['to'=>$to,'src'=>substr($_GET['src']??'',0,40),'ip'=>substr(clientIp(),0,46)]);
// premio piccolo, una volta al giorno per partner, solo se loggato
if($a){
  $reason='g_partner_'.$to.'_'.gmdate('Ymd');
  $c=$pdo->prepare('SELECT 1 FROM pv_ledger WHERE account_id=? AND reason=? LIMIT 1'); $c->execute([$a['id'],$reason]);
  if(!$c->fetch()) pvAdd($a['id'],2,$reason);
}
header('Location: '.$dest[$to],true,302); exit;
