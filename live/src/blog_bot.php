<?php
// BLOG BOT 81+. Legge le notizie vere dai feed, le riscrive in stile Sicurissimo con l’AI,
// cita sempre la fonte, pubblica da solo. Regole dure dentro il prompt, niente numeri inventati,
// niente norme inventate, riscrittura breve e trasformativa con link all originale.
require_once __DIR__.'/db.php';

function bbStato(){ $f=__DIR__.'/../data/blog_bot_state.json'; return is_file($f)?(json_decode(file_get_contents($f),true)?:[]):[]; }
function bbSalvaStato($s){ @file_put_contents(__DIR__.'/../data/blog_bot_state.json', json_encode($s,JSON_UNESCAPED_UNICODE)); }

function bbHttp($url,$timeout=12){
  $ch=curl_init($url);
  curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>1,CURLOPT_FOLLOWLOCATION=>1,CURLOPT_MAXREDIRS=>3,
    CURLOPT_TIMEOUT=>$timeout,CURLOPT_USERAGENT=>'Mozilla/5.0 81plus-blogbot',CURLOPT_SSL_VERIFYPEER=>true]);
  $out=curl_exec($ch); $code=curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
  return ($out!==false && $code>=200 && $code<400)?$out:null;
}

// estrae voci da RSS 2.0 o Atom, in modo tollerante
function bbLeggiFeed($xmlTesto,$nomeFonte,$categoria){
  $voci=[];
  libxml_use_internal_errors(true);
  $x=simplexml_load_string($xmlTesto);
  if(!$x) return $voci;
  if(isset($x->channel->item)){ // RSS
    foreach($x->channel->item as $it){
      $voci[]=['titolo'=>trim((string)$it->title),'link'=>trim((string)$it->link),
        'sommario'=>trim(strip_tags((string)($it->description??''))),
        'data'=>trim((string)($it->pubDate??'')),'fonte'=>$nomeFonte,'categoria'=>$categoria];
    }
  } elseif(isset($x->entry)){ // Atom
    foreach($x->entry as $e){
      $link=''; foreach($e->link as $l){ $a=$l->attributes(); if(!isset($a['rel'])||(string)$a['rel']==='alternate'){ $link=(string)$a['href']; break; } }
      $voci[]=['titolo'=>trim((string)$e->title),'link'=>$link,
        'sommario'=>trim(strip_tags((string)($e->summary??$e->content??''))),
        'data'=>trim((string)($e->updated??$e->published??'')),'fonte'=>$nomeFonte,'categoria'=>$categoria];
    }
  }
  return $voci;
}

// riscrittura in stile Sicurissimo via Groq. Ritorna [titolo,estratto,corpo] oppure null.
// la voce parla alla buyer persona del giorno se disponibile.
function bbRiscrivi($titolo,$sommario,$fonteNome,$link){
  $key=getenv('GROQ_API_KEY')?:''; if($key==='') return null;
  $regole="Sei il redattore del blog 81+ Sicurissimo, sicurezza sul lavoro, HACCP e privacy per imprenditori italiani. Riscrivi la notizia in stile Sicurissimo. Regole assolute. Voce attiva, frasi brevi, dai del tu al lettore. Usa solo virgole e punti, mai trattini, mai punti e virgola, mai due punti, mai elenchi puntati, mai markdown, mai emoji, mai hashtag. Accenti italiani corretti. Non inventare mai numeri, date, sanzioni o norme, usa solo quelli presenti nella notizia, se mancano non metterne. Tra 120 e 220 parole. Spiega cosa cambia per una piccola impresa italiana. Chiudi con una sola frase che invita a fare l’audit gratuito su 81plus.net oppure a entrare nell’area 81+, scegli tu quale. Rispondi solo con un oggetto JSON con i campi titolo, massimo 70 caratteri, estratto, massimo 160 caratteri, corpo. Nessun testo fuori dal JSON.";
  $payload=json_encode([
    'model'=>'llama-3.3-70b-versatile',
    'temperature'=>0.4,
    'max_tokens'=>700,
    'messages'=>[
      ['role'=>'system','content'=>$regole],
      ['role'=>'user','content'=>"Notizia dalla fonte ".$fonteNome.".\nTitolo originale: ".$titolo."\nSommario: ".mb_substr($sommario,0,900)]
    ]
  ],JSON_UNESCAPED_UNICODE);
  $ch=curl_init('https://api.groq.com/openai/v1/chat/completions');
  curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>40,CURLOPT_POST=>1,CURLOPT_POSTFIELDS=>$payload,
    CURLOPT_HTTPHEADER=>['Content-Type: application/json','Authorization: Bearer '.$key]]);
  $out=curl_exec($ch); curl_close($ch);
  if(!$out) return null;
  $j=json_decode($out,true); $txt=$j['choices'][0]['message']['content']??'';
  $txt=trim(preg_replace('/^```(json)?|```$/m','',$txt));
  $r=json_decode($txt,true);
  if(!is_array($r)||empty($r['titolo'])||empty($r['corpo'])) return null;
  // pulizia dura: niente tag, niente caratteri vietati dallo stile
  $pulisci=function($s){ $s=strip_tags($s); $s=str_replace(['—','–',';',':','*','#','•'],[',',',','.','.','','',''],$s); return trim($s); };
  return ['titolo'=>mb_substr($pulisci($r['titolo']),0,90),
          'estratto'=>mb_substr($pulisci($r['estratto']??''),0,200),
          'corpo'=>$pulisci($r['corpo'])];
}

function bbSlug($t){
  $s=strtolower(trim($t)); $s=strtr($s,['à'=>'a','è'=>'e','é'=>'e','ì'=>'i','ò'=>'o','ù'=>'u']);
  $s=preg_replace('/[^a-z0-9]+/','-',$s); $s=trim($s,'-');
  return substr($s,0,80)?:('post-'.substr(md5($t),0,8));
}

// rigenera data/blog.json unendo articoli manuali e automatici pubblicati
function bbRigeneraJson($pdo){
  $f=__DIR__.'/../data/blog.json';
  $d=json_decode(@file_get_contents($f),true)?:['posts'=>[]];
  $manuali=array_values(array_filter($d['posts']??[],function($p){ return ($p['origine']??'manuale')==='manuale'; }));
  $st=$pdo->query("SELECT slug,titolo,estratto,corpo,data,categoria,fonte_nome,fonte_url FROM blog_posts WHERE stato='pubblicato' ORDER BY data DESC LIMIT 60");
  $auto=[];
  foreach($st->fetchAll() as $r){
    $auto[]=['slug'=>$r['slug'],'titolo'=>$r['titolo'],'estratto'=>$r['estratto'],'corpo'=>$r['corpo'],
      'data'=>$r['data'],'categoria'=>$r['categoria'],'origine'=>'auto',
      'fonte_nome'=>$r['fonte_nome'],'fonte_url'=>$r['fonte_url']];
  }
  $tutti=array_merge($auto,$manuali);
  usort($tutti,function($a,$b){ return strcmp($b['data']??'',$a['data']??''); });
  $d['posts']=$tutti;
  @file_put_contents($f,json_encode($d,JSON_UNESCAPED_UNICODE));
  return count($tutti);
}

// giro completo del bot. Ritorna il rapporto.
function bbGiro($force=false){
  if(getenv('BLOG_BOT_ON')==='0') return ['ok'=>false,'err'=>'bot spento da BLOG_BOT_ON'];
  $pdo=db();
  $stato=bbStato();
  if(!$force && !empty($stato['ultimo_giro']) && (time()-strtotime($stato['ultimo_giro']))<20*3600){
    return ['ok'=>true,'salto'=>true,'nota'=>'giro già fatto nelle ultime 20 ore'];
  }
  $cfg=json_decode(@file_get_contents(__DIR__.'/../data/blog_fonti.json'),true)?:['fonti'=>[]];
  $max=max(1,min(6,(int)($cfg['massimo_per_giro']??3)));
  $pubblica=!isset($cfg['pubblica_automatico'])||$cfg['pubblica_automatico'];
  $candidati=[]; $fontiOk=0; $fontiKo=0;
  foreach(($cfg['fonti']??[]) as $f){
    $xml=bbHttp($f['url']);
    if($xml===null){ $fontiKo++; continue; }
    $fontiOk++;
    foreach(array_slice(bbLeggiFeed($xml,$f['nome'],$f['categoria']??'Sicurezza'),0,6) as $v){
      if($v['titolo']!=='' && $v['link']!=='') $candidati[]=$v;
    }
  }
  $creati=0;$saltatiDup=0;$riscrittureKo=0;$rapportoVoci=[];
  foreach($candidati as $v){
    if($creati>=$max) break;
    $hash=hash('sha256',$v['link']);
    $c=$pdo->prepare('SELECT 1 FROM blog_posts WHERE fonte_hash=? LIMIT 1'); $c->execute([$hash]);
    if($c->fetch()){ $saltatiDup++; continue; }
    $r=bbRiscrivi($v['titolo'],$v['sommario'],$v['fonte'],$v['link']);
    if($r===null){ $riscrittureKo++; continue; }
    $slug=bbSlug($r['titolo']);
    $c2=$pdo->prepare('SELECT 1 FROM blog_posts WHERE slug=?'); $c2->execute([$slug]);
    if($c2->fetch()) $slug.='-'.substr(md5($v['link']),0,6);
    try{
      $pdo->prepare('INSERT INTO blog_posts(slug,titolo,estratto,corpo,data,categoria,fonte_nome,fonte_url,fonte_hash,origine,stato,created_at) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)')
        ->execute([$slug,$r['titolo'],$r['estratto'],$r['corpo'],gmdate('Y-m-d'),$v['categoria'],$v['fonte'],$v['link'],$hash,'auto',$pubblica?'pubblicato':'bozza',gmdate('c')]);
      $creati++; $rapportoVoci[]=$r['titolo'];
      ev('blog_auto',null,['slug'=>$slug,'fonte'=>$v['fonte']]);
    }catch(PDOException $e){ /* slug o hash duplicato in corsa, salto */ }
  }
  $totale=bbRigeneraJson($pdo);
  $stato['ultimo_giro']=gmdate('c');
  $stato['ultimo_rapporto']=['fonti_lette'=>$fontiOk,'fonti_fallite'=>$fontiKo,'creati'=>$creati,'doppioni_saltati'=>$saltatiDup,'riscritture_fallite'=>$riscrittureKo];
  bbSalvaStato($stato);
  return ['ok'=>true,'fonti_lette'=>$fontiOk,'fonti_fallite'=>$fontiKo,'creati'=>$creati,
    'doppioni_saltati'=>$saltatiDup,'riscritture_fallite'=>$riscrittureKo,
    'articoli_totali_sul_blog'=>$totale,'nuovi_titoli'=>$rapportoVoci,'pubblicazione'=>$pubblica?'automatica':'in bozza'];
}
