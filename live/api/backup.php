<?php
// Backup del progetto vivo. Impacchetta tutto il sito piu un dump del database
// e restituisce uno zip. Lo chiama il workflow n8n alle 01:00. Firmato con BACKUP_SECRET,
// senza chiave non fa niente. Mai esposto al pubblico.
$sec=getenv('BACKUP_SECRET')?:'';
if($sec===''){ http_response_code(503); echo 'backup spento, imposta BACKUP_SECRET'; exit; }
$s=$_GET['s']??($_SERVER['HTTP_X_81PLUS_BACKUP']??'');
if(!hash_equals($sec,$s)){ http_response_code(403); echo 'firma errata'; exit; }

@set_time_limit(300); @ini_set('memory_limit','512M');
$root=realpath(__DIR__.'/..');                 // radice del sito
$tmp=sys_get_temp_dir().'/backup81_'.date('Ymd_His').'.zip';

// dump del database in un file sql dentro lo zip
$sqlDump='';
try{
  require_once __DIR__.'/../src/db.php'; $pdo=db();
  $drv=$pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
  if($drv==='sqlite'){
    // copio il file sqlite cosi com e, e il backup piu fedele
    $sqlDump=null; // gestito sotto copiando il file
  } else {
    $tabs=$pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    foreach($tabs as $t){
      $sqlDump.="\n-- tabella $t\n";
      $rows=$pdo->query("SELECT * FROM `$t`")->fetchAll(PDO::FETCH_ASSOC);
      foreach($rows as $r){
        $cols=implode(',',array_map(fn($c)=>"`$c`",array_keys($r)));
        $vals=implode(',',array_map(fn($v)=>$v===null?'NULL':$pdo->quote($v),array_values($r)));
        $sqlDump.="INSERT INTO `$t` ($cols) VALUES ($vals);\n";
      }
    }
  }
}catch(Exception $e){ $sqlDump="-- dump db non riuscito: ".$e->getMessage(); }

$skip=array("/node_modules/","/.git/");
$leggimi="Backup 81+ del ".date("d/m/Y H:i")."\nContiene il sito completo e il database. Generato in automatico.";

if(class_exists("ZipArchive")){
  $zip=new ZipArchive();
  if($zip->open($tmp,ZipArchive::CREATE|ZipArchive::OVERWRITE)!==true){ http_response_code(500); echo "zip ko"; exit; }
  $it=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root,FilesystemIterator::SKIP_DOTS));
  foreach($it as $f){
    $p=$f->getPathname(); $rel=ltrim(str_replace($root,"",$p),"/\\");
    $salta=false; foreach($skip as $sk){ if(strpos($p,$sk)!==false){$salta=true;break;} }
    if($salta) continue;
    if($f->isFile() && $f->getSize()<25*1024*1024){ $zip->addFile($p,"sito/".$rel); }
  }
  if($sqlDump!==null && $sqlDump!==""){ $zip->addFromString("database/dump.sql",$sqlDump); }
  $zip->addFromString("LEGGIMI_BACKUP.txt",$leggimi);
  $zip->close();
  $finale=$tmp; $ctype="application/zip"; $ext="zip";
} else {
  // piano B, tar.gz con PharData
  $tar=str_replace(".zip",".tar",$tmp);
  @unlink($tar); @unlink($tar.".gz");
  $pd=new PharData($tar);
  $it=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root,FilesystemIterator::SKIP_DOTS));
  foreach($it as $f){
    $p=$f->getPathname(); $rel=ltrim(str_replace($root,"",$p),"/\\");
    $salta=false; foreach($skip as $sk){ if(strpos($p,$sk)!==false){$salta=true;break;} }
    if($salta) continue;
    if($f->isFile() && $f->getSize()<25*1024*1024){ $pd->addFile($p,"sito/".$rel); }
  }
  if($sqlDump!==null && $sqlDump!==""){ $pd->addFromString("database/dump.sql",$sqlDump); }
  $pd->addFromString("LEGGIMI_BACKUP.txt",$leggimi);
  $pd->compress(Phar::GZ);
  unset($pd); @unlink($tar);
  $finale=$tar.".gz"; $ctype="application/gzip"; $ext="tar.gz";
}

header("Content-Type: ".$ctype);
header("Content-Disposition: attachment; filename=\"BACKUP_81PLUS_".date("Ymd").".".$ext."\"");
header("Content-Length: ".filesize($finale));
readfile($finale);
@unlink($finale);
