<?php
// 81+ PIX81+ UPLOAD LOGO. jpg, png, svg, pdf, quadrato, max 5MB.
// Conversione automatica in WebP, ridimensionamento 512px, output leggero 300-500 KB.
require_once __DIR__.'/../src/db.php'; require_once __DIR__.'/../src/auth_lib.php';
$a=currentAccount(); if(!$a) j(['ok'=>false,'err'=>'accedi per caricare'],401);
$pdo=db();

$pos=(int)($_POST['pos']??-1);
$q=$pdo->prepare('SELECT id FROM pixel_muro WHERE pos=? AND account_id=?'); $q->execute([$pos,$a['id']]);
if(!$q->fetch()) j(['ok'=>false,'err'=>'questo PIX non è tuo'],403);

if(empty($_FILES['logo'])||$_FILES['logo']['error']!==UPLOAD_ERR_OK) j(['ok'=>false,'err'=>'nessun file ricevuto'],422);
$f=$_FILES['logo'];
if($f['size']>5*1024*1024) j(['ok'=>false,'err'=>'file oltre i 5 MB'],422);

$tmp=$f['tmp_name'];
$mime=function_exists('mime_content_type')?mime_content_type($tmp):($f['type']??'');
$ammessi=['image/jpeg'=>'jpg','image/jpg'=>'jpg','image/png'=>'png','application/pdf'=>'pdf','image/svg+xml'=>'svg'];
$ext=$ammessi[$mime]??null;
if(!$ext){ $e=strtolower(pathinfo($f['name'],PATHINFO_EXTENSION)); if(in_array($e,['jpg','jpeg','png','pdf','svg'])) $ext=($e==='jpeg'?'jpg':$e); }
if(!$ext) j(['ok'=>false,'err'=>'formato non ammesso, usa jpg png svg o pdf'],422);

$dir=__DIR__.'/../uploads/pix'; @mkdir($dir,0775,true);
$base='pix_'.$a['id'].'_'.$pos.'_'.substr(md5(uniqid('',true)),0,8);
$kb=0; $rel='';

// raster jpg e png, ritaglio quadrato 512 e converto in WebP se possibile
if(($ext==='jpg'||$ext==='png') && function_exists('imagecreatefromstring')){
  $data=@file_get_contents($tmp); $img=@imagecreatefromstring($data);
  if($img){
    $w=imagesx($img); $h=imagesy($img); $lato=min($w,$h);
    $sq=imagecreatetruecolor(512,512);
    // sfondo bianco per le png trasparenti convertite in jpg di riserva
    $white=imagecolorallocate($sq,255,255,255); imagefilledrectangle($sq,0,0,512,512,$white);
    imagecopyresampled($sq,$img,0,0,(int)(($w-$lato)/2),(int)(($h-$lato)/2),512,512,$lato,$lato);
    if(function_exists('imagewebp')){
      $rel='uploads/pix/'.$base.'.webp'; $dest=__DIR__.'/../'.$rel;
      imagewebp($sq,$dest,80); // qualita 80, ottimo peso
    } else {
      $rel='uploads/pix/'.$base.'.jpg'; $dest=__DIR__.'/../'.$rel;
      imagejpeg($sq,$dest,82);
    }
    imagedestroy($img); imagedestroy($sq);
    $kb=(int)round(filesize($dest)/1024);
  }
}
// svg e pdf, gia vettoriali o leggeri, li salvo cosi
if($rel===''){
  $rel='uploads/pix/'.$base.'.'.$ext; $dest=__DIR__.'/../'.$rel;
  move_uploaded_file($tmp,$dest); $kb=(int)round(filesize($dest)/1024);
}

$pdo->prepare('UPDATE pixel_muro SET img_path=?,img_kb=?,updated_at=? WHERE pos=? AND account_id=?')
    ->execute([$rel,$kb,gmdate('c'),$pos,$a['id']]);
ev('pix_immagine',$a['id'],['pos'=>$pos,'kb'=>$kb,'ext'=>$ext]);
j(['ok'=>true,'img_path'=>$rel,'kb'=>$kb,'nota'=>'immagine ottimizzata, '.$kb.' KB']);
