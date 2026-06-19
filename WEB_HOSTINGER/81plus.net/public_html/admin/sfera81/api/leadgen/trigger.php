<?php
require_once __DIR__ . '/../../_bootstrap.php';
$p = sfera_require_post();
$user_id = (int)($p['user_id'] ?? 0);
$sic_id  = trim($p['sic_id'] ?? '');
$event   = trim($p['event_type'] ?? '');
$segment = trim($p['segment'] ?? 'non_profilato');
$meta    = isset($p['metadata']) ? json_encode($p['metadata']) : null;
if (!$user_id || !$event) sfera_error('user_id e event_type obbligatori');
sfera_pdo()->prepare(
    'INSERT INTO leadgen_events (user_id,sic_id,event_type,source,segment,metadata_json) VALUES (?,?,?,?,?,?)'
)->execute([$user_id, $sic_id, $event, 'LEADGEN81+', $segment, $meta]);
sfera_response(['ok'=>true,'msg'=>'Evento LEADGEN81+ registrato.']);
