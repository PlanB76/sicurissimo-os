<?php
// SFERA81+ — Daily Access Engine
// POST: user_id, sic_id
// Regola: max 1 PV+ giorno normale | max 50 PV+ con BOOSTER81+ attivo
require_once __DIR__ . '/../../../_bootstrap.php';

$p       = sfera_require_post();
$user_id = (int)($p['user_id'] ?? 0);
$sic_id  = trim($p['sic_id'] ?? '');

if (!$user_id) sfera_error('user_id obbligatorio');

$pdo  = sfera_pdo();
$date = date('Y-m-d');
$key  = 'DAILY_ACCESS:' . $user_id . ':' . $date;

// Anti-duplicato
$st = $pdo->prepare('SELECT id, booster_active, reward_amount FROM sfera_daily_access WHERE anti_duplicate_key=?');
$st->execute([$key]);
$existing = $st->fetch();

if ($existing) {
    $user = sfera_get_user($user_id);
    sfera_response([
        'ok'            => false,
        'already_done'  => true,
        'booster'       => (bool)$existing['booster_active'],
        'reward_today'  => (int)$existing['reward_amount'],
        'pvplus_balance'=> $user ? (int)$user['pvplus_balance'] : 0,
        'streak'        => $user ? (int)$user['daily_streak'] : 0,
        'msg'           => 'Hai già ricevuto il tuo reward di oggi. Completa una missione per continuare a crescere.',
    ]);
}

// Controlla BOOSTER81+ attivo
$st = $pdo->prepare(
    "SELECT * FROM sfera_promos
     WHERE status='active' AND reward_type='BOOSTER81+'
     AND (start_at IS NULL OR start_at <= NOW())
     AND (end_at IS NULL OR end_at >= NOW())
     LIMIT 1"
);
$st->execute();
$promo = $st->fetch();

$booster      = !empty($promo);
$reward       = $booster ? 50 : 1;
$reward_type  = $booster ? 'BOOSTER81+' : 'DAILY_ACCESS';
$promo_id     = $booster ? (int)$promo['id'] : null;

// Verifica slot promo mensile anti-abuso (1 per utente per promo)
if ($booster) {
    $bkey = 'BOOSTER:' . $user_id . ':' . $promo_id . ':' . $date;
    $st   = $pdo->prepare('SELECT id FROM sfera_promo_redemptions WHERE anti_duplicate_key=?');
    $st->execute([$bkey]);
    if ($st->fetch()) {
        // Ha già usato il booster oggi — degrada a normale
        $booster     = false;
        $reward      = 1;
        $reward_type = 'DAILY_ACCESS';
        $promo_id    = null;
    }
}

// Aggiorna streak
$user = sfera_get_user($user_id);
$new_streak = 1;
if ($user) {
    $last = $user['last_access_date'];
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    if ($last === $yesterday) {
        $new_streak = (int)$user['daily_streak'] + 1;
    } elseif ($last === $date) {
        $new_streak = (int)$user['daily_streak'];
    }
}

// Inserisci accesso
$pdo->prepare(
    'INSERT INTO sfera_daily_access (user_id,sic_id,access_date,reward_type,reward_amount,promo_id,booster_active,anti_duplicate_key,ip_hash,user_agent_hash)
     VALUES (?,?,?,?,?,?,?,?,?,?)'
)->execute([
    $user_id, $sic_id, $date, $reward_type, $reward, $promo_id, $booster ? 1 : 0, $key,
    hash('sha256', $_SERVER['REMOTE_ADDR'] ?? ''),
    hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? ''),
]);

// Inserisci promo redemption se booster
if ($booster && $promo_id) {
    $bkey = 'BOOSTER:' . $user_id . ':' . $promo_id . ':' . $date;
    try {
        $pdo->prepare(
            'INSERT INTO sfera_promo_redemptions (promo_id,user_id,sic_id,access_date,reward_amount,anti_duplicate_key) VALUES (?,?,?,?,?,?)'
        )->execute([$promo_id, $user_id, $sic_id, $date, $reward, $bkey]);
    } catch (Exception $e) {}
}

// Accredita PV+
sfera_award_pvplus($user_id, $reward, 'Accesso giornaliero' . ($booster ? ' BOOSTER81+' : ''), $reward_type, $promo_id);

// Aggiorna streak e last_access
$pdo->prepare(
    'UPDATE sfera_user_profile SET daily_streak=?, last_access_date=? WHERE user_id=?'
)->execute([$new_streak, $date, $user_id]);

// Trigger LEADGEN81+
$pdo->prepare(
    'INSERT INTO leadgen_events (user_id,sic_id,event_type,source,segment) VALUES (?,?,?,?,?)'
)->execute([$user_id, $sic_id, 'daily_access', 'DAILY_SPARK81', $booster ? 'booster_user' : 'regular_user']);

$user = sfera_get_user($user_id);

$msg = $booster
    ? 'BOOSTER81+ attivo. Hai ricevuto 50 PV+ interni. Completa la tua prima missione.'
    : '+1 PV+ accreditato. Continua ogni giorno per la tua streak.';

sfera_response([
    'ok'            => true,
    'reward'        => $reward,
    'booster'       => $booster,
    'reward_type'   => $reward_type,
    'streak'        => $new_streak,
    'pvplus_balance'=> $user ? (int)$user['pvplus_balance'] : $reward,
    'msg'           => $msg,
    'booster_copy'  => $booster ? [
        'title'   => 'BOOSTER81+ attivo oggi.',
        'sub'     => 'Attiva il tuo SIC-ID, completa la prima missione e scopri il percorso adatto al tuo profilo ATECO/RISCHIO.',
        'cta'     => 'Attiva ora 50 PV+',
    ] : null,
]);
