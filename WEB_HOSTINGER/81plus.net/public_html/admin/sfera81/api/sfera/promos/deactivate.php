<?php
require_once __DIR__ . '/../../../_bootstrap.php';
sfera_require_admin();
$p  = sfera_require_post();
$id = (int)($p['promo_id'] ?? 0);
if (!$id) sfera_error('promo_id obbligatorio');
sfera_pdo()->prepare("UPDATE sfera_promos SET status='inactive' WHERE id=?")->execute([$id]);
sfera_response(['ok'=>true,'msg'=>'BOOSTER81+ disattivato.']);
