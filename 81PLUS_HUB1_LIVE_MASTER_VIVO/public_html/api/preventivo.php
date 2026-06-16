<?php
// api/preventivo.php — Preventivatore dinamico ASR 2025
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/asr2025_compliance_service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_err('Metodo non consentito', 405);
csrf_check();

$a = [
    'ateco'            => strtoupper(trim($_POST['ateco']           ?? '')),
    'num_lav'          => max(0, (int)($_POST['num_lav']            ?? 0)),
    'ha_dl'            => !empty($_POST['ha_dl']),
    'dl_formato'       => !empty($_POST['dl_formato']),
    'dl_fa_rspp'       => !empty($_POST['dl_fa_rspp']),
    'ha_cantieri'      => !empty($_POST['ha_cantieri']),
    'formazione_lav'   => !empty($_POST['formazione_lav']),
    'has_antincendio'  => !empty($_POST['has_antincendio']),
    'has_ps'           => !empty($_POST['has_ps']),
    'has_attrezzature' => !empty($_POST['has_attrezzature']),
    'has_amb_conf'     => !empty($_POST['has_amb_conf']),
    'dvr_presente'     => !empty($_POST['dvr_presente']),
    'haccp_ok'         => !empty($_POST['haccp_ok']),
    'ragione_sociale'  => trim($_POST['ragione_sociale'] ?? ''),
];

if (!$a['ateco']) json_err('Codice ATECO obbligatorio per il preventivo');

$rischio   = ASR2025ComplianceService::calcola_rischio($a);
$a['rischio'] = $rischio['livello'];
$corsi     = ASR2025ComplianceService::corsi_richiesti($a);
$priorita  = ASR2025ComplianceService::priorita_corsi($corsi);
$macro     = ASR2025ComplianceService::macrosettore($a['ateco']);

// Riepilogo per il preventivo
$riepilogo = array_map(fn($c) => [
    'nome'   => $c['nome'],
    'ore'    => $c['ore'],
    'figura' => $c['figura'],
    'urgenza'=> $c['priorita_giorni'] <= 7 ? 'URGENTE' : ($c['priorita_giorni'] <= 30 ? 'PRIORITARIA' : 'PROGRAMMABILE'),
], $corsi);

json_ok([
    'macrosettore'    => $macro,
    'rischio'         => $rischio,
    'corsi'           => $riepilogo,
    'totale_corsi'    => count($corsi),
    'totale_ore'      => array_sum(array_column($corsi, 'ore')),
    'priorita'        => $priorita,
    'nota_economica'  => 'I corsi e i documenti erogati da partner autorizzati si acquistano direttamente sulle piattaforme esterne. I PV 81+ sono utilizzabili esclusivamente per membership e servizi interni.',
    'disclaimer'      => ASR2025ComplianceService::disclaimer(),
]);
