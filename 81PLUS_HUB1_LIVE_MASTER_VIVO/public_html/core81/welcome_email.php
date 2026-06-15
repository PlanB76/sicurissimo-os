<?php
// core81/welcome_email.php — Invio email di benvenuto da welcome@81plus.net
declare(strict_types=1);

function send_welcome_email(string $email, string $nome, string $sic_id): bool {
    $from    = 'welcome@81plus.net';
    $subject = 'Benvenuto in 81plus.net — Il tuo SIC-ID';
    $body    = build_welcome_body($nome, $sic_id);

    $headers = [
        'From'         => $from,
        'Reply-To'     => $from,
        'MIME-Version' => '1.0',
        'Content-Type' => 'text/html; charset=UTF-8',
        'X-Mailer'     => '81plus.net Mailer v1',
    ];

    $header_str = implode("\r\n", array_map(
        fn($k, $v) => "$k: $v",
        array_keys($headers),
        $headers
    ));

    return mail($email, $subject, $body, $header_str);
}

function build_welcome_body(string $nome, string $sic_id): string {
    $base_url = $_ENV['BASE_URL'] ?? 'https://81plus.net';
    $nome_esc = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');
    $sic_esc  = htmlspecialchars($sic_id, ENT_QUOTES, 'UTF-8');
    return <<<HTML
<!DOCTYPE html>
<html lang="it">
<head><meta charset="UTF-8"><title>Benvenuto in 81plus.net</title></head>
<body style="background:#05050A;color:#F4F4F7;font-family:'DM Sans',Arial,sans-serif;max-width:600px;margin:0 auto;padding:2rem;">
  <div style="text-align:center;margin-bottom:2rem;">
    <img src="{$base_url}/assets/img/logo-81plus.svg" alt="81plus.net" width="120">
  </div>
  <h1 style="color:#E8501A;font-size:1.8rem;">Benvenuto, {$nome_esc}.</h1>
  <p>Il tuo account 81plus.net e attivo. Ecco il tuo SIC-ID univoco:</p>
  <div style="background:#0F0F18;border:1px solid rgba(255,255,255,0.1);border-radius:8px;padding:1.5rem;text-align:center;margin:1.5rem 0;">
    <code style="font-size:1.4rem;color:#FFD24A;letter-spacing:0.1em;">{$sic_esc}</code>
  </div>
  <p>Il SIC-ID e il tuo identificatore universale nel sistema 81plus. Conservalo.</p>
  <div style="margin:2rem 0;text-align:center;">
    <a href="{$base_url}/dashboard.php" style="background:#E8501A;color:#fff;padding:0.8rem 2rem;border-radius:6px;text-decoration:none;font-weight:600;">Accedi alla tua area personale</a>
  </div>
  <p style="font-size:0.85rem;color:#B9BCC2;">Questo messaggio e stato inviato automaticamente da welcome@81plus.net. Non rispondere a questa email.</p>
</body>
</html>
HTML;
}
