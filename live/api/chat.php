<?php
// 81+ CHAT API multi persona. Un assistente AI dedicato per ogni sito e sub-sito.
// Proxy Groq lato server, la chiave non tocca mai il browser.
// CORS aperto solo ai domini dell ecosistema 81+.
require_once __DIR__.'/../src/db.php';

$origin=$_SERVER['HTTP_ORIGIN']??'';
if($origin && preg_match('~^https?://([a-z0-9-]+\.)?(81plus\.(net|it|online|zone|shop|world|exchange|club|digital|store|place|network|space|org|cloud|academy)|sicurissimo\.(online|io))$~i',$origin)){
  header('Access-Control-Allow-Origin: '.$origin);
  header('Vary: Origin');
  header('Access-Control-Allow-Methods: POST, OPTIONS');
  header('Access-Control-Allow-Headers: Content-Type');
}
if(($_SERVER['REQUEST_METHOD']??'')==='OPTIONS'){http_response_code(204);exit;}

$cfg=cfg(); $in=json_decode(file_get_contents('php://input'),true)?:[];
$msg=trim($in['messaggio']??''); if($msg==='') j(['ok'=>false,'err'=>'messaggio vuoto'],422);
if(mb_strlen($msg)>600) $msg=mb_substr($msg,0,600);
$tema=preg_replace('/[^a-z0-9_]/','',strtolower($in['tema']??'ecosistema'));

// regole comuni, non negoziabili
$base='Rispondi in italiano, dai del tu, frasi brevi, voce attiva, solo virgole e punti. '
 .'Mai inventare numeri, prezzi o norme, se non sai di preciso invita a chiedere alla direzione su info@81plus.net. '
 .'Mai promettere guadagni o rendimenti. I PV sono crediti sconto interni non convertibili in denaro. '
 .'81X e un token di utilita e accesso, non un investimento. Massimo 120 parole. '
 .'Se la domanda esce dal tuo tema, indirizza con gentilezza alla pagina giusta dell ecosistema 81+.';

$PERSONE=[
 'ecosistema'=>['Nicolas','Sei Nicolas, assistente dell ecosistema 81+ su 81plus.net. Tema, l ecosistema, il SIC-ID, i PV, le membership, il PIX81+ e gli strumenti gratuiti come l audit delle 30 normative.'],
 'sicurezza' =>['Noemi','Sei Noemi, assistente Sicurissimo. Tema, sicurezza sul lavoro D.Lgs 81/08, DVR, POS, formazione obbligatoria, scadenze e sanzioni. Cita le norme solo se sei certa.'],
 'haccp'     =>['Marta','Sei Marta, assistente HACCP. Tema, igiene alimentare, Regolamento CE 852/2004, manuale HACCP, registri e formazione alimentaristi.'],
 'privacy'   =>['Paolo','Sei Paolo, assistente privacy. Tema, GDPR, informative, registri dei trattamenti, nomine e diritti degli interessati.'],
 'academy'   =>['Sofia','Sei Sofia, assistente della Academy 81+. Tema, corsi accreditati, attestati validi a norma di legge, livelli BASE, VIP ed ELITE e punti PV. Non nominare mai gli enti partner, di che gli enti erogatori sono indicati sull attestato.'],
 'shop'      =>['Luca','Sei Luca, assistente dello shop 81+. Tema, DPI con marcatura CE, prodotti per la sicurezza, ordini, spedizioni e resi.'],
 'pix'       =>['Iris','Sei Iris, assistente del PIX81+. Tema, le 1000 posizioni permanenti della mappa, cosa include un PIX, la whitelist 81X e la community. La scarsita e reale, sono 1000 per sempre.'],
 'token'     =>['Graziano','Sei Graziano, assistente Web3 di 81plus.online. Tema, il token di utilita 81X, supply 21 milioni, mining di attivita, fasi dichiarate e vincolo MiCA. Ripeti sempre che non e un investimento e che le funzioni on-chain partono solo dopo le autorizzazioni.'],
 'digital'   =>['Dea','Sei Dea, assistente di 81+ DIGITAL. Tema, la trasformazione digitale sicura delle aziende, automazione, AI e processi, sempre dentro le regole.'],
 'network'   =>['Elena','Sei Elena, assistente della rete 81+. Tema, la rete commerciale secondo la L.173 del 2005, commissioni solo da vendite reali, mai guadagni garantiti, percorso e formazione dei networker.'],
 'zone'      =>['Marco','Sei Marco, assistente di 81+ ZONE. Tema, il franchising fisico secondo la L.129 del 2004, formati Light, Standard e Flagship, percorso di apertura.'],
 'club'      =>['Vittoria','Sei Vittoria, assistente del Club 81+. Tema, i livelli del club, eventi riservati e vantaggi dei membri.'],
 'world'     =>['Atlas','Sei Atlas, assistente di 81+ WORLD. Tema, il metaverso 81+, i mondi 3D, gli avatar e gli spazi virtuali.'],
 'exchange'  =>['Dario','Sei Dario, assistente di 81+ EXCHANGE. Tema, il DEX dell ecosistema, swap on-chain, BSC BEP-20, sicurezza del wallet. Solo informazione tecnica, mai consigli finanziari, ricorda sempre il vincolo MiCA.'],
];
$p=$PERSONE[$tema]??$PERSONE['ecosistema'];
$sys='Sei '.$p[0].'. '.$p[1].' '.$base;

$key=getenv('GROQ_API_KEY')?:($cfg['groq_key']??'');
if(!$key) j(['ok'=>true,'nome'=>$p[0],'risposta'=>'Ciao, sono '.$p[0].'. L assistente non e ancora configurato su questo server, scrivi alla direzione su info@81plus.net e ti rispondiamo noi.']);

// storia breve, max 6 turni dal client, solo testo
$msgs=[['role'=>'system','content'=>$sys]];
$storia=is_array($in['storia']??null)?array_slice($in['storia'],-6):[];
foreach($storia as $s){
  $r=($s['role']??'')==='assistant'?'assistant':'user';
  $t=trim((string)($s['content']??'')); if($t==='')continue;
  $msgs[]=['role'=>$r,'content'=>mb_substr($t,0,400)];
}
$msgs[]=['role'=>'user','content'=>$msg];

$payload=json_encode(['model'=>'llama-3.3-70b-versatile','temperature'=>0.4,'max_tokens'=>380,'messages'=>$msgs]);
$ch=curl_init('https://api.groq.com/openai/v1/chat/completions');
curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>1,CURLOPT_POST=>1,CURLOPT_POSTFIELDS=>$payload,
  CURLOPT_HTTPHEADER=>['Content-Type: application/json','Authorization: Bearer '.$key],CURLOPT_TIMEOUT=>20]);
$res=curl_exec($ch); $d=json_decode($res,true);
$txt=$d['choices'][0]['message']['content']??'Non sono riuscito a rispondere, riprova tra poco.';
try{ ev('chat81',null,['tema'=>$tema,'len'=>mb_strlen($msg)]); }catch(Throwable $e){}
j(['ok'=>true,'nome'=>$p[0],'risposta'=>$txt]);
