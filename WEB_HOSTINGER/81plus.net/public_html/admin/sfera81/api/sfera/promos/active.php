<?php
// SFERA81+ — Promo attiva
// GET: restituisce la promo BOOSTER81+ attiva se esiste
require_once __DIR__ . '/../../../_bootstrap.php';

$st = sfera_pdo()->prepare(
    "SELECT * FROM sfera_promos
     WHERE status='active' AND (start_at IS NULL OR start_at<=NOW()) AND (end_at IS NULL OR end_at>=NOW())
     LIMIT 1"
);
$st->execute();
$promo = $st->fetch();

if (!$promo) {
    sfera_response(['ok'=>true,'active'=>false,'promo'=>null]);
}

sfera_response([
    'ok'     => true,
    'active' => true,
    'promo'  => [
        'id'           => (int)$promo['id'],
        'code'         => $promo['promo_code'],
        'name'         => $promo['promo_name'],
        'reward'       => (int)$promo['reward_amount'],
        'ends_at'      => $promo['end_at'],
        'copy'         => [
            'title'  => 'BOOSTER81+ attivo oggi.',
            'sub'    => 'Accedi e ricevi '.(int)$promo['reward_amount'].' PV+ interni per iniziare il tuo percorso 81+.',
            'cta'    => 'Attiva ora '.(int)$promo['reward_amount'].' PV+',
            'second' => 'Hai già ricevuto il reward BOOSTER81+ di oggi. Completa una missione per continuare a crescere.',
        ],
    ],
]);
