<?php
// includes/auth.php — Gestione sessione per le pagine HTML
declare(strict_types=1);

function auth_require(string $ruolo_minimo = ''): void {
    if (empty($_SESSION['user_id'])) {
        redirect('/login.php?redir=' . urlencode($_SERVER['REQUEST_URI'] ?? '/'));
    }
    $ruoli = ['MEMBER81', 'NETWORKER81', 'ELITE81', 'ADMIN81'];
    if ($ruolo_minimo && isset($_SESSION['ruolo'])) {
        $pos_utente = array_search($_SESSION['ruolo'], $ruoli, true);
        $pos_req    = array_search($ruolo_minimo, $ruoli, true);
        if ($pos_utente === false || $pos_req === false || $pos_utente < $pos_req) {
            redirect('/dashboard.php?err=accesso_negato');
        }
    }
}

function auth_user(): array {
    return [
        'id'             => (int)($_SESSION['user_id']  ?? 0),
        'sic_id'         => $_SESSION['sic_id']         ?? '',
        'nome'           => $_SESSION['nome']            ?? 'Utente',
        'email'          => $_SESSION['email']           ?? '',
        'ruolo'          => $_SESSION['ruolo']           ?? 'MEMBER81',
        'genesys_status' => $_SESSION['genesys_status'] ?? 'NONE',
        'membership'     => $_SESSION['membership']     ?? 'NONE',
        'pv_balance'     => (float)($_SESSION['pv_balance'] ?? 0),
    ];
}

function is_logged(): bool {
    return !empty($_SESSION['user_id']);
}

function is_admin(): bool {
    return ($_SESSION['ruolo'] ?? '') === 'ADMIN81';
}
