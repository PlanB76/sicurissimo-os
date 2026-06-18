<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

$cfg_file = __DIR__ . '/config.json';
$cfg = file_exists($cfg_file) ? json_decode(file_get_contents($cfg_file), true) : [];

function api_fetch(string $url, string $method = 'GET', array $body = []): array {
    $ctx = [
        'http' => [
            'method'  => $method,
            'timeout' => 8,
            'ignore_errors' => true,
            'header' => "Content-Type: application/json\r\nUser-Agent: DEX-GEM-RADAR/1.0\r\n",
        ],
    ];
    if ($method === 'POST' && $body) {
        $ctx['http']['content'] = json_encode($body);
    }
    $raw = @file_get_contents($url, false, stream_context_create($ctx));
    if ($raw === false) return ['error' => 'fetch_failed', 'url' => $url];
    $d = json_decode($raw, true);
    return $d ?? ['error' => 'json_parse_failed'];
}

function append_csv(string $file, array $row): void {
    $line = implode(',', array_map(fn($v) => '"' . str_replace('"', '""', (string)$v) . '"', $row)) . "\n";
    file_put_contents(__DIR__ . '/' . $file, $line, FILE_APPEND | LOCK_EX);
}

$action = $_GET['action'] ?? 'ping';

switch ($action) {

    case 'ping':
        echo json_encode(['ok' => true, 'mode' => 'php', 'version' => '1.0']);
        break;

    case 'dexscreener':
        $chain  = $_GET['chain'] ?? 'bsc';
        $q      = trim($_GET['q'] ?? '');
        $isAddr = ($_GET['isAddr'] ?? '0') === '1';
        if (!$q) { echo json_encode(['pairs' => []]); break; }

        $url = $isAddr
            ? "https://api.dexscreener.com/latest/dex/tokens/" . urlencode($q)
            : "https://api.dexscreener.com/latest/dex/search?q=" . urlencode($q);

        $d = api_fetch($url);
        $pairs = $d['pairs'] ?? [];

        // filter by chain
        if ($chain !== 'all') {
            $pairs = array_values(array_filter($pairs, fn($p) => ($p['chainId'] ?? '') === $chain));
        }

        echo json_encode(['pairs' => array_slice($pairs, 0, 20)]);
        break;

    case 'honeypot':
        $addr    = $_GET['address'] ?? '';
        $chainId = (int)($_GET['chainId'] ?? 1);
        if (!$addr) { echo json_encode(['error' => 'missing address']); break; }

        $d = api_fetch("https://api.honeypot.is/v2/IsHoneypot?address={$addr}&chainID={$chainId}");
        echo json_encode($d);
        break;

    case 'goplus':
        $addr    = strtolower($_GET['address'] ?? '');
        $chainId = (int)($_GET['chainId'] ?? 1);
        if (!$addr) { echo json_encode(['error' => 'missing address']); break; }

        $d   = api_fetch("https://api.gopluslabs.io/api/v1/token_security/{$chainId}?contract_addresses={$addr}");
        $res = $d['result'] ?? [];
        $info = $res[$addr] ?? $res[array_key_first($res)] ?? null;
        echo json_encode(['result' => $info]);
        break;

    case 'etherscan':
        $addr    = $_GET['address'] ?? '';
        $chain   = $_GET['chain'] ?? 'ethereum';
        if (!$addr) { echo json_encode(['error' => 'missing address']); break; }

        $endpoints = [
            'ethereum' => 'https://api.etherscan.io/api',
            'bsc'      => 'https://api.bscscan.com/api',
            'polygon'  => 'https://api.polygonscan.com/api',
            'arbitrum' => 'https://api.arbiscan.io/api',
            'base'     => 'https://api.basescan.org/api',
        ];
        $base = $endpoints[$chain] ?? $endpoints['ethereum'];

        $key_map = [
            'ethereum' => 'etherscan_api_key',
            'bsc'      => 'bscscan_api_key',
            'polygon'  => 'polygonscan_api_key',
            'arbitrum' => 'arbiscan_api_key',
            'base'     => 'basescan_api_key',
        ];
        $key = $cfg[$key_map[$chain] ?? 'etherscan_api_key'] ?? '';
        $url = "{$base}?module=contract&action=getsourcecode&address={$addr}&apikey={$key}";
        $d   = api_fetch($url);

        $result = $d['result'][0] ?? null;
        echo json_encode([
            'verified'    => !empty($result['SourceCode']),
            'source_code' => !empty($result['SourceCode']),
            'name'        => $result['ContractName'] ?? '',
            'compiler'    => $result['CompilerVersion'] ?? '',
            'proxy'       => ($result['Proxy'] ?? '0') === '1',
            'impl'        => $result['Implementation'] ?? '',
        ]);
        break;

    case 'save_watchlist':
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $row  = [
            $body['date']     ?? date('Y-m-d H:i'),
            $body['name']     ?? '',
            $body['symbol']   ?? '',
            $body['address']  ?? '',
            $body['chain']    ?? '',
            $body['price']    ?? '',
            $body['liq']      ?? '',
            $body['vol24']    ?? '',
            $body['score']    ?? '',
            $body['state']    ?? '',
            $body['honeypot'] ?? '',
            $body['notes']    ?? '',
            $body['dex_url']  ?? '',
        ];
        append_csv('watchlist.csv', $row);
        echo json_encode(['ok' => true]);
        break;

    case 'save_log':
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $row  = [
            $body['date']     ?? date('Y-m-d H:i'),
            $body['name']     ?? '',
            $body['symbol']   ?? '',
            $body['address']  ?? '',
            $body['chain']    ?? '',
            $body['price']    ?? '',
            $body['liq']      ?? '',
            $body['vol24']    ?? '',
            $body['score']    ?? '',
            $body['state']    ?? '',
            $body['honeypot'] ?? '',
            $body['notes']    ?? '',
            $body['dex_url']  ?? '',
        ];
        append_csv('risk_log.csv', $row);
        echo json_encode(['ok' => true]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'unknown action']);
}
