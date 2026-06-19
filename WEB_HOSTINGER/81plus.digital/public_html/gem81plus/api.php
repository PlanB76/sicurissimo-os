<?php
/* ============================================================
   GEM81 — Proxy PHP
   Risolve i problemi CORS su hosting (Hostinger ecc.).
   Il frontend chiama:  api.php?u=<url-encoded>
   Solo gli host in whitelist sono ammessi.
   ============================================================ */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

/* health check usato dal frontend per attivare la modalita proxy */
if (($_GET['action'] ?? '') === 'ping') {
    echo json_encode(['ok' => true, 'mode' => 'php', 'version' => '1.0']);
    exit;
}

$ALLOWED = [
    'api.geckoterminal.com',
    'api.gopluslabs.io',
    'api.honeypot.is',
    'api.dexscreener.com',
    'apiv5.paraswap.io',
];

$u = $_GET['u'] ?? '';
if (!$u) { http_response_code(400); echo json_encode(['error' => 'missing u']); exit; }

$parts = parse_url($u);
$host  = $parts['host'] ?? '';
if (($parts['scheme'] ?? '') !== 'https' || !in_array($host, $ALLOWED, true)) {
    http_response_code(403);
    echo json_encode(['error' => 'host non consentito', 'host' => $host]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] === 'POST' ? 'POST' : 'GET';
$body   = $method === 'POST' ? file_get_contents('php://input') : null;

$opts = [
    'http' => [
        'method'        => $method,
        'timeout'       => 12,
        'ignore_errors' => true,
        'header'        => "Accept: application/json\r\nContent-Type: application/json\r\nUser-Agent: GEM81/1.0\r\n",
    ],
];
if ($body !== null) $opts['http']['content'] = $body;

$raw = @file_get_contents($u, false, stream_context_create($opts));
if ($raw === false) { http_response_code(502); echo json_encode(['error' => 'upstream non raggiungibile']); exit; }

/* rilancia il body cosi com'e (e gia JSON) */
echo $raw;
