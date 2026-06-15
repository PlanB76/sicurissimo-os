<?php
// 81+ CARDS. Card digitale SIC-ID con QR e badge ranking.
// Genera SVG dinamico o PNG per condivisione.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$pdo=db(); $az=$_GET['az']??'';

// Card pubblica per SIC-ID (chiunque può vederla)
if($az==='view'){
  $sic=preg_replace('/[^A-Z0-9-]/','',$_GET['sic']??'');
  if(!$sic) j(['ok'=>false,'err'=>'sic mancante'],400);
  $q=$pdo->prepare('SELECT sic,nome,cognome,avatar,ruolo,created_at FROM accounts WHERE sic=?'); $q->execute([$sic]); $u=$q->fetch(PDO::FETCH_ASSOC);
  if(!$u) j(['ok'=>false,'err'=>'SIC non trovato'],404);
  // ranking
  $pv=0; try{$pv=(int)$pdo->prepare("SELECT COALESCE(SUM(amount),0) s FROM pv_ledger WHERE account_id=(SELECT id FROM accounts WHERE sic=?) AND amount>0")->execute([$sic])?:0; $pv=(int)$pdo->query("SELECT COALESCE(SUM(amount),0) s FROM pv_ledger WHERE account_id=(SELECT id FROM accounts WHERE sic='".$sic."') AND amount>0")->fetch()['s'];}catch(Throwable $e){}
  $livelli=[['da'=>0,'nome'=>'Starter','icona'=>'⭐','colore'=>'#8a8a96'],['da'=>300,'nome'=>'Operativo','icona'=>'🛡️','colore'=>'#CD7F32'],['da'=>800,'nome'=>'Veterano','icona'=>'⚡','colore'=>'#C0C0C0'],['da'=>2000,'nome'=>'Esperto','icona'=>'🔥','colore'=>'#FFD700'],['da'=>5000,'nome'=>'Master','icona'=>'💎','colore'=>'#E8501A']];
  $liv=$livelli[0]; foreach($livelli as $L){ if($pv>=$L['da']) $liv=$L; }
  // PIX
  $npix=0; try{$npix=(int)$pdo->query("SELECT COUNT(*) c FROM pixel_muro WHERE sic='".$sic."' AND account_id IS NOT NULL")->fetch()['c'];}catch(Throwable $e){}
  $nome=trim(($u['nome']??'').($u['cognome']?' '.substr($u['cognome'],0,1).'.':''));
  if(!$nome) $nome='Membro 81+';
  j(['ok'=>true,'sic'=>$sic,'nome'=>$nome,'avatar'=>$u['avatar']??null,'livello'=>$liv,'pv'=>$pv,'pix'=>$npix,
     'membro_dal'=>substr($u['created_at']??'',0,10),'qr_url'=>'https://81plus.net/cards.html?sic='.$sic]);
}

// Card SVG dinamica
if($az==='svg'){
  $sic=preg_replace('/[^A-Z0-9-]/','',$_GET['sic']??'');
  if(!$sic){ header('Content-Type: text/plain'); echo 'SIC mancante'; exit; }
  // fetch data via self
  $data=json_decode(file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/api/cards.php?az=view&sic='.$sic),true);
  if(!$data||!$data['ok']){ header('Content-Type: text/plain'); echo 'Non trovato'; exit; }
  $d=$data; $col=$d['livello']['colore']??'#E8501A';
  header('Content-Type: image/svg+xml');
  echo '<?xml version="1.0" encoding="UTF-8"?>';
  echo '<svg xmlns="http://www.w3.org/2000/svg" width="600" height="340" viewBox="0 0 600 340">';
  echo '<defs><linearGradient id="bg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#141417"/><stop offset="1" stop-color="#0A0A0F"/></linearGradient>';
  echo '<linearGradient id="ac" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="'.$col.'"/><stop offset="1" stop-color="#FB6B00"/></linearGradient></defs>';
  echo '<rect width="600" height="340" rx="24" fill="url(#bg)"/>';
  echo '<rect x="1" y="1" width="598" height="338" rx="23" fill="none" stroke="url(#ac)" stroke-width="2"/>';
  // logo
  echo '<rect x="28" y="28" width="38" height="38" rx="10" fill="#E8501A"/>';
  echo '<text x="47" y="55" font-family="Arial Black" font-size="18" fill="#fff" text-anchor="middle">81+</text>';
  echo '<text x="76" y="52" font-family="Arial" font-size="14" font-weight="700" fill="#fff">ECOSISTEMA</text>';
  // nome
  echo '<text x="28" y="110" font-family="Arial Black" font-size="28" fill="#fff">'.htmlspecialchars($d['nome']).'</text>';
  // SIC-ID
  echo '<text x="28" y="140" font-family="monospace" font-size="16" fill="'.$col.'">'.$d['sic'].'</text>';
  // livello badge
  echo '<rect x="28" y="158" width="'.max(80,strlen($d['livello']['nome'])*12+40).'" height="30" rx="15" fill="'.$col.'22" stroke="'.$col.'" stroke-width="1"/>';
  echo '<text x="48" y="179" font-family="Arial" font-size="13" font-weight="700" fill="'.$col.'">'.$d['livello']['icona'].' '.$d['livello']['nome'].'</text>';
  // stats
  echo '<text x="28" y="225" font-family="monospace" font-size="13" fill="#8a8a96">PV: <tspan fill="#fff">'.$d['pv'].'</tspan>  PIX: <tspan fill="#fff">'.$d['pix'].'</tspan>  Dal: <tspan fill="#fff">'.$d['membro_dal'].'</tspan></text>';
  // QR placeholder
  echo '<rect x="440" y="100" width="130" height="130" rx="12" fill="#1a1a22" stroke="#262630" stroke-width="1"/>';
  echo '<text x="505" y="170" font-family="monospace" font-size="10" fill="#8a8a96" text-anchor="middle">SCANSIONA</text>';
  echo '<text x="505" y="185" font-family="monospace" font-size="10" fill="#8a8a96" text-anchor="middle">PER IL PROFILO</text>';
  // footer
  echo '<text x="28" y="310" font-family="Arial" font-size="11" fill="#5a5a66">81plus.net · La compliance dal 2003 · Ecosistema 81+</text>';
  echo '<text x="572" y="310" font-family="monospace" font-size="10" fill="#5a5a66" text-anchor="end">CARD v1.0</text>';
  echo '</svg>';
  exit;
}

j(['ok'=>false,'err'=>'az mancante: view o svg'],400);
