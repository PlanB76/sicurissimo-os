<?php
// 81+ SEED SUB-PROGETTI. Solo direzione. Marca i PIX delle case 81+ come founder,
// con logo, descrizione e link reali. Idempotente, si puo rilanciare.
// Sono posizioni della direzione, dichiarate founder nella mappa, nessun utente finto.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount();
$isAdmin=$a && (($a['sic']??'')==='SIC-0000001' || ($a['ruolo']??'')==='admin');
$cli=(php_sapi_name()==='cli');
if(!$isAdmin && !$cli) j(['ok'=>false,'err'=>'solo la direzione'],403);
$pdo=db();

$SUB=[
  ['pos'=>126,'slug'=>'hub1','nome'=>'81plus.net','desc'=>'La porta dell ecosistema dal 2003. Identità SIC-ID, audit e dashboard.','url'=>'https://81plus.net','col'=>'#E8501A'],
  ['pos'=>234,'slug'=>'sicurissimo','nome'=>'Sicurissimo','desc'=>'La compliance legale. Sicurezza, HACCP e privacy dal 2003.','url'=>'https://sicurissimo.online','col'=>'#FB6B00'],
  ['pos'=>323,'slug'=>'hub3','nome'=>'81plus.online','desc'=>'La compliance digitale completa. Il mondo Web3.','url'=>'https://81plus.online','col'=>'#00D9FF'],
  ['pos'=>175,'slug'=>'academy','nome'=>'81+ ACADEMY','desc'=>'Formazione certificata, attestati validi a norma di legge.','url'=>'https://81plus.academy','col'=>'#FB6B00'],
  ['pos'=>437,'slug'=>'bond','nome'=>'BOND 81+','desc'=>'I titoli di accesso e utilità interni dell ecosistema.','url'=>'https://81plus.bond','col'=>'#FFD24A'],
  ['pos'=>268,'slug'=>'cards','nome'=>'CARDS 81+','desc'=>'Le card digitali dell ecosistema.','url'=>'https://81plus.cards','col'=>'#E8501A'],
  ['pos'=>489,'slug'=>'christmas','nome'=>'XMAS81','desc'=>'Dove è Natale tutto l anno, regali e celebrazioni per ogni occasione.','url'=>'https://81plus.christmas','col'=>'#3FBF6B'],
  ['pos'=>592,'slug'=>'cloud','nome'=>'81+ CLOUD','desc'=>'L infrastruttura che regge tutto l ecosistema.','url'=>'https://81plus.cloud','col'=>'#4AE8C0'],
  ['pos'=>645,'slug'=>'club','nome'=>'CLUB 81+','desc'=>'Il club riservato, eventi e vantaggi.','url'=>'https://81plus.club','col'=>'#FFD24A'],
  ['pos'=>748,'slug'=>'credit','nome'=>'CREDIT 81+','desc'=>'I crediti e il wallet dell ecosistema.','url'=>'https://81plus.credit','col'=>'#00D9FF'],
  ['pos'=>813,'slug'=>'digital','nome'=>'81+ DIGITAL','desc'=>'La trasformazione digitale sicura delle aziende.','url'=>'https://81plus.digital','col'=>'#4AE8C0'],
  ['pos'=>103,'slug'=>'exchange','nome'=>'81+ EXCHANGE','desc'=>'Il DEX dell ecosistema, solo on-chain.','url'=>'https://81plus.exchange','col'=>'#00D9FF'],
  ['pos'=>616,'slug'=>'network','nome'=>'NETWORK81+','desc'=>'La rete commerciale, L.173 del 2005, commissioni solo da vendite reali.','url'=>'https://81plus.network','col'=>'#FFD24A'],
  ['pos'=>874,'slug'=>'org','nome'=>'81+ ORG','desc'=>'La futura governance DAO.','url'=>'https://81plus.org','col'=>'#00D9FF'],
  ['pos'=>372,'slug'=>'place','nome'=>'81+ PLACE','desc'=>'I luoghi fisici della rete.','url'=>'https://81plus.place','col'=>'#3FBF6B'],
  ['pos'=>781,'slug'=>'shop','nome'=>'81+ SHOP','desc'=>'Il merchandising ufficiale dell ecosistema.','url'=>'https://81plus.shop','col'=>'#E8501A'],
  ['pos'=>288,'slug'=>'space','nome'=>'81+ SPACE','desc'=>'Gli spazi riservati Elite.','url'=>'https://81plus.space','col'=>'#8A4AE8'],
  ['pos'=>559,'slug'=>'store','nome'=>'81+ STORE','desc'=>'Il marketplace del Web3.','url'=>'https://81plus.store','col'=>'#8A4AE8'],
  ['pos'=>887,'slug'=>'world','nome'=>'81+ WORLD','desc'=>'Il Metaverso 81+, mondi e spazi 3D.','url'=>'https://81plus.world','col'=>'#8A4AE8'],
  ['pos'=>715,'slug'=>'zone','nome'=>'81+ ZONE','desc'=>'Il franchising fisico sul territorio, L.129 del 2004.','url'=>'https://81plus.zone','col'=>'#3FBF6B'],
];
// 20 regioni founder a forma d Italia, riservate al franchising
$ITALIA=[300,301,339,340,341,342,380,381,421,461,462,502,541,542,543,581,458,498,620,621];
// 20 PIX whitelist token 81X
$WL=[50,111,163,222,253,316,389,407,473,483,571,630,658,683,757,769,825,857,911,932];
// 3 PIX dimostrativi, dichiarati esempio
$ESEMPI=[
  [305,'La tua officina','PIX dimostrativo. Così apparirebbe una officina meccanica con logo, contatti e link al sito.','Via dell Esempio 1, 45014 Porto Viro RO','info@esempio.it'],
  [612,'Il tuo ristorante','PIX dimostrativo. Così apparirebbe un ristorante con la sua pagina HACCP in regola.','Piazza Demo 8, 20100 Milano MI','prenota@esempio.it'],
  [858,'Il tuo studio','PIX dimostrativo. Così apparirebbe uno studio professionale visibile a tutta la community.','Corso Prova 21, 00100 Roma RM','studio@esempio.it'],
];

function seedSvg($slug,$nome,$col){
  $dir=__DIR__.'/../uploads/pix'; @mkdir($dir,0775,true);
  $f=$dir.'/sub_'.$slug.'.svg';
  $iniz=strtoupper(substr(preg_replace('/[^A-Za-z0-9]/','',$nome),0,3));
  $svg='<svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512">'
    .'<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#101018"/><stop offset="1" stop-color="'.$col.'"/></linearGradient>'
    .'<radialGradient id="r" cx="0.3" cy="0.25" r="0.9"><stop offset="0" stop-color="#ffffff" stop-opacity="0.25"/><stop offset="0.5" stop-color="#ffffff" stop-opacity="0"/></radialGradient></defs>'
    .'<rect width="512" height="512" rx="46" fill="url(#g)"/><rect width="512" height="512" rx="46" fill="url(#r)"/>'
    .'<text x="256" y="250" text-anchor="middle" font-family="Arial Black,Arial" font-size="120" font-weight="900" fill="#ffffff">'.$iniz.'</text>'
    .'<text x="256" y="330" text-anchor="middle" font-family="Arial" font-size="34" font-weight="700" fill="#ffffff" opacity="0.85">'.htmlspecialchars($nome).'</text>'
    .'<text x="256" y="430" text-anchor="middle" font-family="Arial" font-size="26" font-weight="700" fill="'.$col.'">ECOSISTEMA 81+</text></svg>';
  file_put_contents($f,$svg);
  return 'uploads/pix/sub_'.$slug.'.svg';
}

$fatti=0;
foreach($SUB as $s){
  $img=seedSvg($s['slug'],$s['nome'],$s['col']);
  $ex=$pdo->prepare('SELECT id,account_id FROM pixel_muro WHERE pos=?'); $ex->execute([$s['pos']]); $row=$ex->fetch();
  if($row && $row['account_id']) continue; // mai sopra un PIX di un utente reale
  if($row){
    $pdo->prepare("UPDATE pixel_muro SET tipo='casa',azienda=?,descrizione=?,url=?,colore=?,img_path=?,stato='attivo' WHERE pos=?")
        ->execute([$s['nome'],$s['desc'],$s['url'],$s['col'],$img,$s['pos']]);
  } else {
    $pdo->prepare("INSERT INTO pixel_muro(pos,tipo,azienda,descrizione,url,colore,img_path,stato,created_at) VALUES(?,?,?,?,?,?,?,?,?)")
        ->execute([$s['pos'],'casa',$s['nome'],$s['desc'],$s['url'],$s['col'],$img,'attivo',gmdate('c')]);
  }
  $fatti++;
}
foreach($ITALIA as $p){
  $ex=$pdo->prepare('SELECT id,account_id FROM pixel_muro WHERE pos=?'); $ex->execute([$p]); $row=$ex->fetch();
  if($row && $row['account_id']) continue;
  $d='Riservato al punto fisico regionale del franchising 81+ ZONE.';
  if($row) $pdo->prepare("UPDATE pixel_muro SET tipo='founder',descrizione=?,colore='#FFD24A',stato='attivo' WHERE pos=?")->execute([$d,$p]);
  else $pdo->prepare("INSERT INTO pixel_muro(pos,tipo,descrizione,colore,stato,created_at) VALUES(?,?,?,?,?,?)")->execute([$p,'founder',$d,'#FFD24A','attivo',gmdate('c')]);
  $fatti++;
}
foreach($WL as $p){
  $ex=$pdo->prepare('SELECT id,account_id FROM pixel_muro WHERE pos=?'); $ex->execute([$p]); $row=$ex->fetch();
  if($row && $row['account_id']) continue;
  $d='Prenotato per la whitelist del token di utilità 81X. Airdrop fino a 50.000 81X secondo il whitepaper.';
  if($row) $pdo->prepare("UPDATE pixel_muro SET tipo='riservato',descrizione=?,colore='#00D9FF',stato='attivo' WHERE pos=?")->execute([$d,$p]);
  else $pdo->prepare("INSERT INTO pixel_muro(pos,tipo,descrizione,colore,stato,created_at) VALUES(?,?,?,?,?,?)")->execute([$p,'riservato',$d,'#00D9FF','attivo',gmdate('c')]);
  $fatti++;
}
foreach($ESEMPI as $e){
  [$p,$nome,$desc,$ind,$mail]=$e;
  $ex=$pdo->prepare('SELECT id,account_id FROM pixel_muro WHERE pos=?'); $ex->execute([$p]); $row=$ex->fetch();
  if($row && $row['account_id']) continue;
  if($row) $pdo->prepare("UPDATE pixel_muro SET tipo='esempio',azienda=?,descrizione=?,indirizzo=?,email=?,url='https://81plus.net',colore='#8A4AE8',stato='attivo' WHERE pos=?")->execute([$nome,$desc,$ind,$mail,$p]);
  else $pdo->prepare("INSERT INTO pixel_muro(pos,tipo,azienda,descrizione,indirizzo,email,url,colore,stato,created_at) VALUES(?,?,?,?,?,?,?,?,?,?)")->execute([$p,'esempio',$nome,$desc,$ind,$mail,'https://81plus.net','#8A4AE8','attivo',gmdate('c')]);
  $fatti++;
}
j(['ok'=>true,'seminati'=>$fatti,'nota'=>'20 nodi casa, 20 regioni founder, 20 whitelist, 3 esempi dichiarati. Nessun utente finto.']);
