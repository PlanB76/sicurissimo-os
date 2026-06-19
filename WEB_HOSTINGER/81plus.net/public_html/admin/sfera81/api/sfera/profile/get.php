<?php
require_once __DIR__ . '/../../../_bootstrap.php';
$user_id = (int)($_GET['user_id'] ?? 0);
if (!$user_id) sfera_error('user_id obbligatorio');
$user = sfera_get_user($user_id);
if (!$user) sfera_error('Utente non trovato', 404);
unset($user['id']);
sfera_response(['ok'=>true,'profile'=>$user]);
