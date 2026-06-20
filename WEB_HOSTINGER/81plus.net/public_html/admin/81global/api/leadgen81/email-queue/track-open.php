<?php
// 81PLUS Global OS — LeadGen81 Email Open Tracker
// FILE: api/leadgen81/email-queue/track-open.php
// METHOD: GET (no auth — called by email clients loading the tracking pixel)
// Returns a 1x1 transparent GIF regardless of outcome.
// IMPORTANT: do NOT call g81_response() here — output is image/gif, not JSON.

require_once __DIR__ . '/../../../_bootstrap.php';

// ─── Override bootstrap's default JSON Content-Type immediately ──────────────
header('Content-Type: image/gif');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

// 1x1 transparent GIF (base64-encoded)
define('PIXEL_GIF', base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'));

// ─── 1. VALIDATE TRACKING ID ─────────────────────────────────────────────────

$t = isset($_GET['t']) ? trim($_GET['t']) : '';

if (!preg_match('/^[a-f0-9]{64}$/', $t)) {
    // Invalid or missing tracking ID — still serve the pixel silently
    echo PIXEL_GIF;
    exit();
}

// ─── 2. LOOK UP EMAIL QUEUE RECORD ───────────────────────────────────────────

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
    // DB error — serve pixel silently
    echo PIXEL_GIF;
    exit();
}

// ─── 3. INCREMENT OPENS AND TRACK EVENT (only if record found and status=SENT) ─

if ($row !== false && $row['status'] === 'SENT') {
    $contact_id = (int) $row['cid'];

    try {
        $upd = $pdo->prepare(
            'UPDATE leadgen81_email_queue SET opens = opens + 1 WHERE tracking_id = ?'
        );
        $upd->execute([$t]);
    } catch (Exception $e) {
        // Non-blocking
    }

    try {
        g81_track_event($contact_id, 'OPEN', ['tracking_id' => $t]);
    } catch (Exception $e) {
        // Non-blocking
    }
}

// ─── 4. SERVE PIXEL ──────────────────────────────────────────────────────────

echo PIXEL_GIF;
exit();
