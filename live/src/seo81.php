<?php
// 81+ MOTORE SEO AUTOMATICO. Gira nel cron orario, zero lavoro manuale.
// Fa quattro cose. Uno, trasforma ogni articolo del blog in una pagina statica
// vera che i motori possono leggere. Due, rigenera la sitemap con tutte le
// pagine pubbliche e gli articoli. Tre, mantiene robots.txt. Quattro, quando
// nascono url nuove le segnala ai motori col protocollo IndexNow, Bing,
// Yandex, Seznam e gli altri aderenti. Per Google la sitemap viene letta
// da Search Console, il vecchio ping non esiste piu dal 2023.

require_once __DIR__.'/db.php';

function seoBase(){ return 'https://81plus.net'; }
function seoRoot(){ return realpath(__DIR__.'/..'); }

// pagine pubbliche da indicizzare, le riservate restano fuori
function seoPagine(){
  return [
    'index.html'=>['1.0','daily'],
    'audit.html'=>['0.9','weekly'],
    'signup.html'=>['0.9','monthly'],
    'recensioni.html'=>['0.8','weekly'],
    'blog.html'=>['0.8','daily'],
    'libri.html'=>['0.7','monthly'],
    'promo_settimana.html'=>['0.8','weekly'],
    'strumenti.html'=>['0.6','monthly'],
    'mondi.html'=>['0.6','monthly'],
    'newsletter.html'=>['0.5','monthly'],
    'login.html'=>['0.3','yearly'],
    'privacy.html'=>['0.3','yearly'],
    'cookie.html'=>['0.3','yearly'],
    'termini.html'=>['0.3','yearly'],
    'condizioni.html'=>['0.3','yearly'],
    'disclaimer.html'=>['0.3','yearly'],
  ];
}

function seoEsc($s){ return htmlspecialchars($s,ENT_QUOTES,'UTF-8'); }

// ===== 1. PAGINE STATICHE DEL BLOG =====
function seoBlogStatico(){
  $root=seoRoot(); $dir=$root.'/blog'; @mkdir($dir,0775,true);
  $dati=json_decode(@file_get_contents($root.'/data/blog.json'),true)?:['posts'=>[]];
  $posts=$dati['posts']??[]; $nuove=[];
  foreach($posts as $p){
    $slug=preg_replace('/[^a-z0-9\-]/','',strtolower($p['slug']??'')); if(!$slug) continue;
    $file=$dir.'/'.$slug.'.html';
    $tit=seoEsc($p['titolo']??''); $est=seoEsc($p['estratto']??'');
    $corpo=$p['corpo']??''; $data=seoEsc($p['data']??'');
    $fonte=isset($p['fonte'])?'<p style="font-size:12px;color:#6b7180">Fonte segnalata, '.seoEsc($p['fonte']).'</p>':'';
    // il corpo arriva dal nostro json, paragrafi su righe
    $corpoHtml='<p>'.implode('</p><p>',array_map('seoEsc',preg_split('/\n+/',trim($corpo)))).'</p>';
    $ld=json_encode(['@context'=>'https://schema.org','@type'=>'Article','headline'=>$p['titolo']??'','datePublished'=>$p['data']??'','author'=>['@type'=>'Organization','name'=>'81+ Sicurissimo Global'],'publisher'=>['@type'=>'Organization','name'=>'81+ Sicurissimo Global']],JSON_UNESCAPED_UNICODE);
    $html='<!DOCTYPE html><html lang="it"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
     .'<title>'.$tit.' · Blog 81+</title>'
     .'<meta name="description" content="'.$est.'">'
     .'<link rel="canonical" href="'.seoBase().'/blog/'.$slug.'.html">'
     .'<meta property="og:type" content="article"><meta property="og:title" content="'.$tit.'"><meta property="og:description" content="'.$est.'">'
     .'<meta property="og:url" content="'.seoBase().'/blog/'.$slug.'.html"><meta property="og:image" content="'.seoBase().'/og_cover.png"><meta property="og:locale" content="it_IT">'
     .'<meta name="twitter:card" content="summary_large_image">'
     .'<script type="application/ld+json">'.$ld.'</script>'
     .'<link rel="icon" type="image/svg+xml" href="../favicon.svg">'
     .'<link href="https://fonts.googleapis.com/css2?family=Anton&family=Sora:wght@400;600;700&display=swap" rel="stylesheet">'
     .'<style>body{background:#070710;color:#f3f3f8;font-family:Sora,sans-serif;margin:0}.w{max-width:720px;margin:0 auto;padding:30px 18px}h1{font-family:Anton;font-weight:400;text-transform:uppercase;font-size:30px;line-height:1.15}p{color:#cfcfdd;font-size:15px;line-height:1.7}a{color:#E8501A}.d{color:#9aa0b4;font-size:12px}.cta{display:inline-block;background:#E8501A;color:#fff;text-decoration:none;font-weight:700;padding:12px 20px;border-radius:10px;margin-top:18px}</style></head>'
     .'<body><div class="w"><a href="../blog.html" style="font-size:13px">← Tutti gli articoli</a>'
     .'<h1>'.$tit.'</h1><div class="d">'.$data.' · La direzione 81+</div>'
     .$corpoHtml.$fonte
     .'<a class="cta" href="../audit.html">Scopri in due minuti se sei in regola</a>'
     .'<p style="font-size:11px;color:#6b7180;margin-top:26px">81+ Sicurissimo Global, Labo Tecnic Studio, P.IVA IT01504180298, Porto Viro (RO)</p>'
     .'</div></body></html>';
    $prima=is_file($file)?md5_file($file):'';
    file_put_contents($file,$html);
    if(md5($html)!==$prima) $nuove[]=seoBase().'/blog/'.$slug.'.html';
  }
  return $nuove;
}

// ===== 2. SITEMAP =====
function seoSitemap(){
  $root=seoRoot(); $righe=[];
  foreach(seoPagine() as $f=>$pc){
    $path=$root.'/'.$f; if(!is_file($path)) continue;
    $righe[]='<url><loc>'.seoBase().'/'.$f.'</loc><lastmod>'.gmdate('Y-m-d',filemtime($path)).'</lastmod><changefreq>'.$pc[1].'</changefreq><priority>'.$pc[0].'</priority></url>';
  }
  foreach(glob($root.'/blog/*.html') as $b){
    $righe[]='<url><loc>'.seoBase().'/blog/'.basename($b).'</loc><lastmod>'.gmdate('Y-m-d',filemtime($b)).'</lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url>';
  }
  $xml='<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n".implode("\n",$righe)."\n".'</urlset>';
  file_put_contents($root.'/sitemap.xml',$xml);
  return count($righe);
}

// ===== 3. ROBOTS =====
function seoRobots(){
  $root=seoRoot();
  $r="User-agent: *\n"
   ."Disallow: /dashboard.html\nDisallow: /profilo.html\nDisallow: /benvenuto.html\nDisallow: /plancia_dio.html\nDisallow: /import_auto.html\nDisallow: /sistema_12_passi.html\nDisallow: /sic_card.html\nDisallow: /premi.html\nDisallow: /api/\nDisallow: /src/\nDisallow: /sql/\nDisallow: /data/\n\n"
   ."Sitemap: ".seoBase()."/sitemap.xml\n";
  file_put_contents($root.'/robots.txt',$r);
}

// ===== 4. INDEXNOW, il ping moderno ai motori =====
function seoIndexNowKey(){
  $root=seoRoot(); $kf=$root.'/data/indexnow_key.txt';
  if(!is_file($kf)){ $k=bin2hex(random_bytes(16)); file_put_contents($kf,$k); file_put_contents($root.'/'.$k.'.txt',$k); }
  $k=trim(file_get_contents($kf));
  if(!is_file($root.'/'.$k.'.txt')) file_put_contents($root.'/'.$k.'.txt',$k);
  return $k;
}
function seoIndexNowPing($urls){
  if(!$urls) return 0;
  $k=seoIndexNowKey();
  $body=json_encode(['host'=>'81plus.net','key'=>$k,'keyLocation'=>seoBase().'/'.$k.'.txt','urlList'=>array_values(array_slice($urls,0,500))]);
  $ch=curl_init('https://api.indexnow.org/indexnow');
  curl_setopt_array($ch,[CURLOPT_POST=>1,CURLOPT_POSTFIELDS=>$body,CURLOPT_HTTPHEADER=>['Content-Type: application/json; charset=utf-8'],CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>8]);
  @curl_exec($ch); $code=curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
  return $code;
}

// ===== IL GIRO COMPLETO, chiamato dal cron =====
function seoRigenera(){
  $nuove=seoBlogStatico();
  $tot=seoSitemap();
  seoRobots();
  seoIndexNowKey();
  $ping=0;
  if($nuove && getenv('INDEXNOW_ON')!=='0') $ping=seoIndexNowPing($nuove);
  // memoria del giro per la plancia
  @file_put_contents(seoRoot().'/data/seo_stato.json',json_encode(['ultimo_giro'=>gmdate('c'),'url_in_sitemap'=>$tot,'nuove_segnalate'=>count($nuove),'ping_http'=>$ping],JSON_UNESCAPED_UNICODE));
  return ['urls'=>$tot,'nuove'=>count($nuove)];
}
