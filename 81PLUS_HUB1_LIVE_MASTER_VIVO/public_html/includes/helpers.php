<?php
// includes/helpers.php — Funzioni di utilità 81plus.net
declare(strict_types=1);

function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path, int $code = 302): never {
    http_response_code($code);
    header('Location: ' . BASE_URL . $path);
    exit;
}

function json_ok(array $data = [], int $code = 200): never {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode(['ok' => true, ...$data]);
    exit;
}

function json_err(string $msg, int $code = 400): never {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'error' => $msg]);
    exit;
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_check(): void {
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        json_err('Token CSRF non valido', 403);
    }
}

function format_pv(float $v): string {
    return number_format($v, 2, ',', '.') . ' PV';
}

function sic_id_generate(int $user_id, string $email): string {
    $payload = $user_id . '|' . $email . '|' . time();
    return 'SIC-' . strtoupper(substr(hash_hmac('sha256', $payload, $_ENV['SIC_SECRET'] ?? 'changeme'), 0, 12));
}

function ruolo_label(string $ruolo): string {
    return match($ruolo) {
        'MEMBER81'    => 'Member 81+',
        'NETWORKER81' => 'Networker 81+',
        'ELITE81'     => 'Elite 81+',
        'ADMIN81'     => 'Admin 81+',
        default       => $ruolo,
    };
}

function genesys_label(string $status): string {
    return match($status) {
        'GENESYS_MEMBER'    => 'Genesys Member',
        'GENESYS_NETWORKER' => 'Genesys Networker',
        'GENESYS_LEADER'    => 'Genesys Leader',
        'GENESYS_FOUNDER'   => 'Genesys Founder',
        default             => '',
    };
}
