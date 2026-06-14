<?php
// 81+ SHOP. Catalogo prodotti, carrello, checkout con PayPal e sconto PV.
// Prodotti fisici: POD (Printful) e dropshipping (BigBuy).
// L utente paga in euro con carta via PayPal. Sconto PV massimo 20% del carrello.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$pdo=db(); $az=$_GET['az']??'';

// Catalogo prodotti (hardcoded qui, futuro: tabella shop_prodotti)
function catalogo(){
  return [
    // WEAR MEMBER
    ['id'=>'W001','nome'=>'T-shirt Member 81+','cat'=>'wear_member','prezzo'=>29,'desc'=>'T-shirt cotone organico con logo 81+ ricamato.','img'=>'uploads/shop/tshirt_member.jpg','tipo'=>'pod','fornitore'=>'printful'],
    ['id'=>'W002','nome'=>'Felpa 81+','cat'=>'wear_member','prezzo'=>59,'desc'=>'Felpa con cappuccio, logo 81+ sul petto.','img'=>'uploads/shop/felpa.jpg','tipo'=>'pod','fornitore'=>'printful'],
    ['id'=>'W003','nome'=>'Cappello 81+','cat'=>'wear_member','prezzo'=>25,'desc'=>'Cappello snapback con patch 81+.','img'=>'uploads/shop/cappello.jpg','tipo'=>'pod','fornitore'=>'printful'],
    ['id'=>'W004','nome'=>'Polo 81+','cat'=>'wear_member','prezzo'=>39,'desc'=>'Polo tecnica con ricamo 81+.','img'=>'uploads/shop/polo.jpg','tipo'=>'pod','fornitore'=>'printful'],
    // WEAR NETWORKER
    ['id'=>'W010','nome'=>'T-shirt Networker 81+','cat'=>'wear_networker','prezzo'=>35,'desc'=>'T-shirt esclusiva per i networker, logo speciale.','img'=>'uploads/shop/tshirt_net.jpg','tipo'=>'pod','fornitore'=>'printful'],
    // WEAR ELITE
    ['id'=>'W020','nome'=>'T-shirt Elite 81+','cat'=>'wear_elite','prezzo'=>45,'desc'=>'T-shirt premium per i membri Elite.','img'=>'uploads/shop/tshirt_elite.jpg','tipo'=>'pod','fornitore'=>'printful'],
    // GADGET
    ['id'=>'G001','nome'=>'Tazza 81+','cat'=>'gadget','prezzo'=>19,'desc'=>'Tazza ceramica 350ml con logo 81+.','img'=>'uploads/shop/tazza.jpg','tipo'=>'pod','fornitore'=>'printful'],
    ['id'=>'G002','nome'=>'Borraccia 81+','cat'=>'gadget','prezzo'=>29,'desc'=>'Borraccia acciaio 500ml, logo inciso.','img'=>'uploads/shop/borraccia.jpg','tipo'=>'pod','fornitore'=>'printful'],
    ['id'=>'G003','nome'=>'Notebook 81+','cat'=>'gadget','prezzo'=>15,'desc'=>'Quaderno A5 80 pagine, copertina brandizzata.','img'=>'uploads/shop/notebook.jpg','tipo'=>'pod','fornitore'=>'printful'],
    ['id'=>'G004','nome'=>'Zaino 81+','cat'=>'gadget','prezzo'=>49,'desc'=>'Zaino tecnico con compartimento laptop.','img'=>'uploads/shop/zaino.jpg','tipo'=>'pod','fornitore'=>'printful'],
    ['id'=>'G005','nome'=>'Adesivi Pack 81+','cat'=>'gadget','prezzo'=>5,'desc'=>'Pack 5 adesivi logo 81+ e sub-progetti.','img'=>'uploads/shop/adesivi.jpg','tipo'=>'pod','fornitore'=>'printful'],
    // WORKWEAR DPI
    ['id'=>'D001','nome'=>'Gilet Alta Visibilità 81+','cat'=>'workwear','prezzo'=>19,'desc'=>'Gilet catarifrangente EN ISO 20471.','img'=>'uploads/shop/gilet.jpg','tipo'=>'dropship','fornitore'=>'bigbuy'],
    ['id'=>'D002','nome'=>'Scarpe Antinfortunistiche','cat'=>'workwear','prezzo'=>49,'desc'=>'Scarpe S3 puntale acciaio, suola antiperforazione.','img'=>'uploads/shop/scarpe.jpg','tipo'=>'dropship','fornitore'=>'bigbuy'],
    ['id'=>'D003','nome'=>'Kit DPI Base','cat'=>'workwear','prezzo'=>39,'desc'=>'Guanti, occhiali e casco. Tutto certificato CE.','img'=>'uploads/shop/kit_dpi.jpg','tipo'=>'dropship','fornitore'=>'bigbuy'],
    ['id'=>'D004','nome'=>'Kit HACCP','cat'=>'workwear','prezzo'=>29,'desc'=>'Camice, cuffia e guanti monouso. 50 pezzi.','img'=>'uploads/shop/kit_haccp.jpg','tipo'=>'dropship','fornitore'=>'bigbuy'],
    // KIT SPECIALI
    ['id'=>'K001','nome'=>'Kit Onboarding Azienda','cat'=>'kit','prezzo'=>69,'desc'=>'Box fisico: manuale, gadget, QR SIC-ID, USB corsi.','img'=>'uploads/shop/kit_onboarding.jpg','tipo'=>'custom','fornitore'=>'81plus'],
    ['id'=>'K002','nome'=>'Kit Networker Starter','cat'=>'kit','prezzo'=>89,'desc'=>'T-shirt + cappello + notebook + penna + adesivi.','img'=>'uploads/shop/kit_net.jpg','tipo'=>'pod','fornitore'=>'printful'],
    ['id'=>'K003','nome'=>'Kit Elite Premium','cat'=>'kit','prezzo'=>149,'desc'=>'Felpa + borraccia + zaino + notebook.','img'=>'uploads/shop/kit_elite.jpg','tipo'=>'pod','fornitore'=>'printful'],
  ];
}

if($az==='catalogo'){
  $cat=$_GET['cat']??'';
  $prodotti=catalogo();
  if($cat) $prodotti=array_values(array_filter($prodotti,fn($p)=>$p['cat']===$cat));
  j(['ok'=>true,'prodotti'=>$prodotti,'categorie'=>['wear_member','wear_networker','wear_elite','gadget','workwear','kit']]);
}

if($az==='ordina'){
  $a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'accedi per ordinare'],401);
  $in=json_decode(file_get_contents('php://input'),true)?:[];
  $items=$in['items']??[]; // [{id:'W001',qty:1}, ...]
  $pv_sconto=(int)($in['pv_sconto']??0);
  if(!$items) j(['ok'=>false,'err'=>'carrello vuoto'],422);
  $cat=catalogo(); $byId=[]; foreach($cat as $p) $byId[$p['id']]=$p;
  $totale=0; $righe=[];
  foreach($items as $it){
    $pid=$it['id']??''; $qty=max(1,min(10,(int)($it['qty']??1)));
    if(!isset($byId[$pid])) j(['ok'=>false,'err'=>'prodotto '.$pid.' non trovato'],422);
    $pr=$byId[$pid]; $sub=$pr['prezzo']*$qty; $totale+=$sub;
    $righe[]=['id'=>$pid,'nome'=>$pr['nome'],'prezzo'=>$pr['prezzo'],'qty'=>$qty,'subtotale'=>$sub];
  }
  // sconto PV: max 20% del totale
  $max_sconto=floor($totale*0.20);
  $saldo=pvBalance($a['id']);
  $sconto=min($pv_sconto,$max_sconto,$saldo);
  $euro=max(0,$totale-$sconto);
  // salva ordine
  $pdo->prepare('INSERT INTO pixel_ordini(account_id,sic,posizioni,n_pix,pv_sconto,euro_da_pagare,stato,created_at) VALUES(?,?,?,?,?,?,?,?)')
      ->execute([$a['id'],$a['sic'],json_encode($righe),count($righe),$sconto,$euro,'shop_attesa',gmdate('c')]);
  $ordineId=$pdo->lastInsertId();
  j(['ok'=>true,'ordine'=>$ordineId,'totale_prodotti'=>$totale,'sconto_pv'=>$sconto,'euro_da_pagare'=>$euro,'paypal_amount'=>number_format($euro,2,'.',''),'righe'=>$righe]);
}

j(['ok'=>false,'err'=>'az: catalogo, ordina'],400);
