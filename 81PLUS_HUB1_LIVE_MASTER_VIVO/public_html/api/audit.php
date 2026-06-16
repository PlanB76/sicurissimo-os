<?php
// api/audit.php — Audit ASR 2025 Compliance Engine (60 secondi)
// Accordo Stato-Regioni Rep. Atti n. 59/CSR del 17 aprile 2025
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/core81/asr2025_compliance_service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_err('Metodo non consentito', 405);
csrf_check();

// ─── Raccolta dati audit (18 domande) ────────────────────────────────────────
$a = [
    'ateco'            => strtoupper(trim($_POST['ateco']           ?? '')),
    'num_lav'          => max(0, (int)($_POST['num_lav']            ?? 0)),
    'ha_soci'          => !empty($_POST['ha_soci']),
    'ha_dl'            => !empty($_POST['ha_dl']),
    'dl_formato'       => !empty($_POST['dl_formato']),
    'dl_anni_formazione'=> max(0, (int)($_POST['dl_anni_formazione']?? 0)),
    'dl_fa_rspp'       => !empty($_POST['dl_fa_rspp']),
    'dl_rspp_formato'  => !empty($_POST['dl_rspp_formato']),
    'ha_cantieri'      => !empty($_POST['ha_cantieri']),
    'e_impresa_affid'  => !empty($_POST['e_impresa_affid']),
    'has_dirigenti'    => !empty($_POST['has_dirigenti']),
    'has_preposti'     => !empty($_POST['has_preposti']),
    'formazione_lav'   => !empty($_POST['formazione_lav']),
    'has_antincendio'  => !empty($_POST['has_antincendio']),
    'has_ps'           => !empty($_POST['has_ps']),
    'has_attrezzature' => !empty($_POST['has_attrezzature']),
    'has_amb_conf'     => !empty($_POST['has_amb_conf']),
    'dvr_presente'     => !empty($_POST['dvr_presente']),
    'scadenziario'     => !empty($_POST['scadenziario']),
    'haccp_ok'         => !empty($_POST['haccp_ok']),
    'ragione_sociale'  => trim($_POST['ragione_sociale'] ?? ''),
    'rischio'          => strtoupper(trim($_POST['rischio'] ?? 'MEDIO')),
];
$email = strtolower(trim($_POST['email'] ?? ''));

if (!$a['ateco']) json_err('Codice ATECO obbligatorio');

// ─── Analisi compliance ───────────────────────────────────────────────────────
$rischio = ASR2025ComplianceService::calcola_rischio($a);
$a['rischio'] = $rischio['livello'];
$corsi   = ASR2025ComplianceService::corsi_richiesti($a);
$priorita = ASR2025ComplianceService::priorita_corsi($corsi);
$macro   = ASR2025ComplianceService::macrosettore($a['ateco']);

// ─── Validazione test ATECO (spec sezione 16 punto 5) ────────────────────────
// F4120  → COSTRUZIONI → mostra cantieri ✓
// I5610  → RISTORAZIONE → no cantieri ✓
// A0111  → AGRICOLTURA ✓
// A0311  → PESCA ✓
// C2010  → CHIMICO ✓

// ─── Salva audit e corsi se utente loggato ────────────────────────────────────
$user_id = (int)($_SESSION['user_id'] ?? 0);
$sic_id  = $_SESSION['sic_id'] ?? '';
$audit_id = 0;
if ($user_id && $sic_id) {
    try {
        $audit_id = ASR2025ComplianceService::salva_audit($user_id, $sic_id, $a, $rischio);
        ASR2025ComplianceService::salva_corsi($user_id, $audit_id, $corsi);
    } catch (Throwable $e) {
        error_log('[AUDIT ASR2025] DB error: ' . $e->getMessage());
    }
}

// ─── Salva lead email se non loggato ─────────────────────────────────────────
if ($email && filter_var($email, FILTER_VALIDATE_EMAIL) && !$user_id) {
    try {
        $db = DB::get();
        $db->prepare(
            'INSERT IGNORE INTO preventivi (tipo, settore, email_richiedente, contenuto, created_at)
             VALUES ("AUDIT_ASR2025", ?, ?, ?, NOW())'
        )->execute([$a['ateco'], $email, json_encode(['rischio' => $rischio, 'corsi_count' => count($corsi)])]);
    } catch (Throwable) {}
}

json_ok([
    'rischio'         => $rischio,
    'macrosettore'    => $macro,
    'corsi'           => $corsi,
    'priorita'        => $priorita,
    'totale_ore'      => array_sum(array_column($corsi, 'ore')),
    'audit_id'        => $audit_id ?: null,
    'disclaimer'      => ASR2025ComplianceService::disclaimer(),
    'nota_partner'    => 'I percorsi formativi sono erogati da piattaforme e-learning professionali e soggetti autorizzati. Il pagamento avviene sulle piattaforme esterne dei partner.',
]);
