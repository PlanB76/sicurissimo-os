<?php
require_once __DIR__.'/../src/db.php';
require_once __DIR__.'/../src/nft_licenza.php';
require_once __DIR__.'/../src/auth_lib.php';
header('Content-Type: image/svg+xml');
$a=function_exists('currentAccount')?currentAccount():null;
$liv='REGIONAL'; $reg='—'; $sic='SIC-XXXXXXXX'; $ser='00'; $grado='FONDATORE';
if($a){
  $sic=$a['sic']??$sic;
  $reg=$a['regione']??'—';
  if(($a['ruolo']??'')==='fondatore'){
    // national se gestisce livello nazionale, regional altrimenti, dato reale dal db
    $liv=(!empty($a['nodo_livello']) && $a['nodo_livello']==='national')?'NATIONAL':'REGIONAL';
  }
}
// override demo via query solo per anteprima, non tocca dati
if(isset($_GET['demo'])){ $liv=strtoupper(preg_replace('/[^a-z]/i','',$_GET['liv']??'REGIONAL')); $reg=substr(preg_replace('/[^a-zA-Z àèéìòù]/','',$_GET['reg']??'Veneto'),0,20); $sic=$_GET['sic']??'SIC-DEMO0001'; $ser=preg_replace('/[^0-9]/','',$_GET['n']??'07'); }
echo nftLicenzaSvg($liv,$reg,$sic,$ser,$grado);
