<?php
if(!function_exists('env')){ function env($k,$d=null){ $v=getenv($k); return ($v===false||$v==='')?$d:$v; } }
return [
  'env'=>env('APP_ENV','production'),'db_driver'=>env('DB_DRIVER','mysql'),
  'db_host'=>env('DB_HOST','127.0.0.1'),'db_port'=>(int)env('DB_PORT','3306'),
  'db_name'=>env('DB_NAME','u173050672_81plusglobal'),'db_user'=>env('DB_USER','root'),'db_pass'=>env('DB_PASS',''),
  'sqlite'=>env('SQLITE_PATH', __DIR__.'/../data/hub1.sqlite'),'session_ttl'=>(int)env('SESSION_TTL_DAYS','14'),
  'welcome_from'=>env('WELCOME_FROM','welcome@81plus.net'),'info_from'=>env('INFO_FROM','info@81plus.net'),
  'app_secret'=>env('APP_SECRET','CAMBIAMI_in_.env_con_stringa_lunga_casuale'),
  'webhook_secret'=>env('WEBHOOK_SECRET',''),
  'pv_transfer'=>env('PV_TRANSFER_ENABLED','1')==='1',     // decisione legale: si può spegnere senza toccare il codice
  'captcha_secret'=>env('CAPTCHA_SECRET',''),
  'company'=>[
    'rag'=>'Labo Tecnic Studio','piva'=>'IT01504180298','sede'=>'Porto Viro (RO)','email'=>'info@81plus.net','dominio'=>'81plus.net'
  ],
];
