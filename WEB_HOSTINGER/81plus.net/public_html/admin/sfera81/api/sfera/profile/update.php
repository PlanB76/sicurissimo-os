<?php
require_once __DIR__ . '/../../../_bootstrap.php';
$p       = sfera_require_post();
$user_id = (int)($p['user_id'] ?? 0);
if (!$user_id) sfera_error('user_id obbligatorio');

$allowed = ['sic_id','ateco_code','macro_sector','micro_sector','risk_level','haccp_applicable','privacy_applicable','safety_applicable','company_size','user_type'];
$sets = []; $vals = [];
foreach ($allowed as $f) {
    if (isset($p[$f])) { $sets[] = "$f=?"; $vals[] = $p[$f]; }
}
if (!$sets) sfera_error('Nessun campo da aggiornare');
$vals[] = $user_id;
sfera_pdo()->prepare('UPDATE sfera_user_profile SET ' . implode(',', $sets) . ' WHERE user_id=?')->execute($vals);
sfera_response(['ok'=>true,'msg'=>'Profilo aggiornato.']);
