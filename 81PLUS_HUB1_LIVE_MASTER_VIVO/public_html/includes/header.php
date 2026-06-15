<?php
// includes/header.php — HTML head 81plus.net
declare(strict_types=1);
$page_title ??= '81plus.net — Il Sistema Operativo per la Sicurezza Aziendale';
$page_desc  ??= 'Protezione totale D.Lgs 81/08 e HACCP. Certificazioni ISO 45001, 9001, 14001. Ecosistema digitale per imprenditori italiani.';
$page_id    ??= 'generic';
?>
<!DOCTYPE html>
<html lang="it" data-page="<?= e($page_id) ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= e($page_desc) ?>">
  <meta name="robots" content="index, follow">
  <meta property="og:title"       content="<?= e($page_title) ?>">
  <meta property="og:description" content="<?= e($page_desc) ?>">
  <meta property="og:image"       content="<?= BASE_URL ?>/assets/img/og-image.jpg">
  <meta property="og:url"         content="<?= BASE_URL . e($_SERVER['REQUEST_URI'] ?? '/') ?>">
  <meta property="og:type"        content="website">
  <title><?= e($page_title) ?></title>
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
  <!-- CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/home.css">
  <?php if (in_array($page_id, ['dashboard', 'scout81', 'network81', 'admin'], true)): ?>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
  <?php endif; ?>
  <?php if (in_array($page_id, ['ecosistema3d', 'cervello3d', 'scout81-map'], true)): ?>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard3d.css">
  <?php endif; ?>
  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/assets/img/favicon.svg">
  <link rel="apple-touch-icon" href="<?= BASE_URL ?>/assets/img/apple-touch-icon.png">
  <!-- CSRF -->
  <meta name="csrf-token" content="<?= csrf_token() ?>">
</head>
<body class="page-<?= e($page_id) ?>">
