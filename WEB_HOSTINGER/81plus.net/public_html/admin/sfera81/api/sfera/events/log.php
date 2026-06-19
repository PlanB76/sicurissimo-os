<?php
// SFERA81+ — Log evento sistema
// POST: user_id, sic_id, event_type, source_module, [ateco_code, risk_level, pvplus_delta, metadata]
require_once __DIR__ . '/../../../_bootstrap.php';

$p           = sfera_require_post();
$user_id     = (int)($p['user_id'] ?? 0);
$sic_id      = trim($p['sic_id'] ?? '');
$event_type  = trim($p['event_type'] ?? '');
$source      = trim($p['source_module'] ?? 'SFERA81');
$ateco       = trim($p['ateco_code'] ?? '');
$risk        = trim($p['risk_level'] ?? '');
$pvplus_delta= (int)($p['pvplus_delta'] ?? 0);
$meta        = isset($p['metadata']) ? json_encode($p['metadata']) : null;

if (!$user_id || !$event_type) sfera_error('user_id e event_type obbligatori');

$pdo = sfera_pdo();

// Se mancano ateco/risk, prendi dal profilo
if (!$ateco || !$risk) {
    $user = sfera_get_user($user_id);
    if ($user) {
        $ateco = $ateco ?: ($user['ateco_code'] ?? '');
        $risk  = $risk  ?: ($user['risk_level'] ?? 'medio');
        $sic_id = $sic_id ?: ($user['sic_id'] ?? '');
    }
}

$pdo->prepare(
    'INSERT INTO sfera_event_log (user_id,sic_id,event_type,source_module,ateco_code,risk_level,pvplus_delta,metadata_json)
     VALUES (?,?,?,?,?,?,?,?)'
)->execute([$user_id, $sic_id, $event_type, $source, $ateco, $risk, $pvplus_delta, $meta]);

// Controllo trigger badge
$badge_triggers = [
    'daily_access'    => 'PRIMO_ACCESSO',
    'sic_id_activated'=> 'SIC_ID_ATTIVO',
    'profile_complete'=> 'PROFILO_COMPLETO',
    'ateco_profiled'  => 'ATECO_PROFILO',
    'dvr_checked'     => 'DVR_CHECK',
    'audit_complete'  => 'AUDIT_COMPLETATO',
    'haccp_checked'   => 'HACCP_OK',
    'privacy_checked' => 'PRIVACY_OK',
    'booster_used'    => 'BOOSTER_ATTIVATO',
    'referral_activated'=> 'NETWORKER',
    'academy_module'  => 'ACADEMY_START',
];

if (isset($badge_triggers[$event_type]) && $user_id) {
    $badge_code = $badge_triggers[$event_type];
    $stBadge = $pdo->prepare('SELECT id, pvplus_bonus FROM sfera_badges WHERE badge_code=? AND active=1');
    $stBadge->execute([$badge_code]);
    $badge = $stBadge->fetch();
    if ($badge) {
        try {
            $pdo->prepare(
                'INSERT INTO sfera_user_badges (user_id,sic_id,badge_id,badge_code,pvplus_awarded) VALUES (?,?,?,?,?)'
            )->execute([$user_id, $sic_id, $badge['id'], $badge_code, $badge['pvplus_bonus']]);
            if ($badge['pvplus_bonus'] > 0) {
                sfera_award_pvplus($user_id, (int)$badge['pvplus_bonus'], 'Badge: '.$badge_code, 'BADGE_ENGINE');
            }
        } catch (Exception $e) {
            // Badge già assegnato — ok
        }
    }
}

sfera_response(['ok'=>true,'msg'=>'Evento registrato.','event'=>$event_type]);
