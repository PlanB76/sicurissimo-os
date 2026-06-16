<?php
// api/compliance-check.php — Verifica stato compliance ASR 2025 utente
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/auth_guard.php';
require_once dirname(__DIR__) . '/core81/asr2025_compliance_service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') json_err('Metodo non consentito', 405);
$user = auth_guard();

$stato = ASR2025ComplianceService::stato_compliance($user['id']);

if (!$stato['audit_presente']) {
    json_ok([
        'audit_presente' => false,
        'msg'            => 'Nessun audit completato. Fai l\'audit gratuito per conoscere le priorità formative della tua azienda.',
        'cta_audit'      => BASE_URL . '/audit.php',
    ]);
    return;
}

json_ok([
    'audit_presente'  => true,
    'macrosettore'    => $stato['audit']['macrosettore'],
    'rischio'         => $stato['audit']['rischio_livello'],
    'score'           => (int)$stato['audit']['score'],
    'corsi'           => $stato['corsi'],
    'mancanti'        => $stato['mancanti'],
    'in_scadenza'     => $stato['in_scadenza'],
    'completati'      => $stato['completati'],
    'totale'          => $stato['totale'],
    'audit_data'      => $stato['audit']['audit_completed_at'],
    'disclaimer'      => ASR2025ComplianceService::disclaimer(),
    'nota_partner'    => 'I percorsi formativi identificati sono erogati da piattaforme e soggetti autorizzati. Il completamento e la verifica restano a carico dell\'azienda.',
]);
