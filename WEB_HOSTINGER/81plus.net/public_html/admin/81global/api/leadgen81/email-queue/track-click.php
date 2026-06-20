<?php
// 81PLUS Global OS — LeadGen81 Email Click Tracker
// FILE: api/leadgen81/email-queue/track-click.php
// METHOD: GET (no auth — called when a contact clicks a tracked link in an email)
// Increments click counter, tracks event, then performs 302 redirect to target URL.
// IMPORTANT: do NOT call g81_response() here — output is a redirect, not JSON.

require_once __DIR__ . '/../../../_bootstrap.php';

// ─── Helper: perform a safe redirect and exit ─────────────────────────────────

function do_redirect(string $url): void
{
    header('Location: ' . $url, true, 302);
    exit();
}

// ─── Fallback URL (used when destination is invalid or not whitelisted) ───────

$fallback_url = defined('BASE_URL') ? rtrim(BASE_URL, '/') : 'https://81plus.net';

// ─── 1. VALIDATE TRACKING ID ─────────────────────────────────────────────────

$t = isset($_GET['t']) ? trim($_GET['t']) : '';

if (!preg_match('/^[a-f0-9]{64}$/', $t)) {
    do_redirect($fallback_url);
}

// ─── 2. VALIDATE AND DECODE DESTINATION URL ───────────────────────────────────

$u_raw = isset($_GET['u']) ? trim($_GET['u']) : '';

if ($u_raw === '') {
    do_redirect($fallback_url);
}

// Detect and decode base64 — base64 strings use [A-Za-z0-9+/=] only.
// A URL that starts with 'http' is almost certainly not base64.
if (!preg_match('#^https?://#i', $u_raw) && preg_match('#^[A-Za-z0-9+/]+=*$#', $u_raw)) {
    $decoded = base64_decode($u_raw, true);
    $u_decoded = ($decoded !== false) ? $decoded : $u_raw;
} else {
    $u_decoded = $u_raw;
}

// Basic URL sanity check
$parsed = parse_url($u_decoded);
if ($parsed === false || empty($parsed['host'])) {
    do_redirect($fallback_url);
}

$destination_domain = strtolower($parsed['host']);

// ─── 3. WHITELIST CHECK ───────────────────────────────────────────────────────

$safe_url = $fallback_url; // default to fallback if domain not whitelisted

if (defined('WHITELIST_REDIRECT_DOMAINS')) {
    $whitelist = json_decode(WHITELIST_REDIRECT_DOMAINS, true);
    if (is_array($whitelist)) {
        foreach ($whitelist as $allowed) {
            // Allow exact match or subdomain match (e.g. www.81plus.net)
            $allowed = strtolower(trim($allowed));
            if ($destination_domain === $allowed || substr($destination_domain, -(strlen($allowed) + 1)) === '.' . $allowed) {
                $safe_url = $u_decoded;
                break;
            }
        }
    }
}

// ─── 4. LOOK UP EMAIL QUEUE RECORD AND TRACK CLICK ───────────────────────────

try {
    $pdo  = g81_pdo();
    $stmt = $pdo->prepare(
        'SELECT eq.*, c.id AS cid
         FROM leadgen81_email_queue eq
         INNER JOIN leadgen81_contacts c ON c.id = eq.contact_id
         WHERE eq.tracking_id = ?
         LIMIT 1'
    );
    $stmt->execute([$t]);
    $row = $stmt->fetch();
} catch (Exception $e) {
    // DB error — redirect silently
    do_redirect($safe_url);
}

if ($row !== false) {
    $contact_id = (int) $row['cid'];

    try {
        $upd = $pdo->prepare(
            'UPDATE leadgen81_email_queue SET clicks = clicks + 1 WHERE tracking_id = ?'
        );
        $upd->execute([$t]);
    } catch (Exception $e) {
        // Non-blocking
    }

    try {
        g81_track_event($contact_id, 'CLICK', [
            'tracking_id' => $t,
            'url'         => $u_decoded,
        ]);
    } catch (Exception $e) {
        // Non-blocking
    }
}

// ─── 5. REDIRECT ─────────────────────────────────────────────────────────────

do_redirect($safe_url);
