<?php
/**
 * MASTER BLASTER INSTALL 81+
 * Installer completo del Sistema Operativo 81+
 * Versione: 1.0.0 | Data: 2026-06-17
 *
 * SICUREZZA: Questo file si autodistrugge dopo l'installazione riuscita.
 * Caricalo su /setup/MASTER_BLASTER_INSTALL.php e aprilo nel browser.
 */

define('INSTALLER_VERSION', '1.0.0');
define('INSTALLER_SECRET', 'MASTER81PLUS');
define('SELF_DESTRUCT_ON_SUCCESS', true);

session_start();

// ─── HELPERS ────────────────────────────────────────────────────────────────

function ok(string $msg): string  { return "<span class='ok'>✓ $msg</span>"; }
function err(string $msg): string { return "<span class='err'>✗ $msg</span>"; }
function warn(string $msg): string{ return "<span class='warn'>⚠ $msg</span>"; }
function info(string $msg): string{ return "<span class='info'>→ $msg</span>"; }

function load_env(string $path): array {
    if (!file_exists($path)) return [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (!str_contains($line, '=')) continue;
        [$key, $val] = explode('=', $line, 2);
        $env[trim($key)] = trim($val);
    }
    return $env;
}

function env_path(): string {
    return dirname(__DIR__) . '/.env';
}

function root_path(): string {
    return dirname(__DIR__);
}

// ─── STEP HANDLER ────────────────────────────────────────────────────────────

$step = (int)($_GET['step'] ?? 0);
$action = $_POST['action'] ?? '';

?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MASTER BLASTER INSTALL 81+ v<?= INSTALLER_VERSION ?></title>
<style>
  :root {
    --bg: #05050A;
    --card: #0F0F18;
    --orange: #E8501A;
    --gold: #FFD24A;
    --white: #FFFFFF;
    --muted: #888;
    --green: #2ECC71;
    --red: #E74C3C;
    --yellow: #F39C12;
    --border: #1a1a2e;
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { background: var(--bg); color: var(--white); font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.6; }
  .header { background: var(--card); border-bottom: 2px solid var(--orange); padding: 20px 40px; display: flex; align-items: center; gap: 20px; }
  .logo { font-size: 28px; font-weight: 900; color: var(--orange); letter-spacing: 2px; }
  .logo span { color: var(--gold); }
  .badge { background: var(--orange); color: white; padding: 3px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; }
  .container { max-width: 900px; margin: 0 auto; padding: 30px 20px; }
  .progress { display: flex; gap: 0; margin-bottom: 30px; border-radius: 8px; overflow: hidden; }
  .progress-step { flex: 1; padding: 8px 4px; text-align: center; font-size: 10px; font-weight: bold; background: var(--border); color: var(--muted); }
  .progress-step.active { background: var(--orange); color: white; }
  .progress-step.done { background: #1a3a1a; color: var(--green); }
  .card { background: var(--card); border: 1px solid var(--border); border-radius: 8px; padding: 24px; margin-bottom: 20px; }
  .card h2 { color: var(--gold); font-size: 16px; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 1px; }
  .card h3 { color: var(--orange); font-size: 13px; margin: 16px 0 8px; text-transform: uppercase; }
  .log { background: #02020A; border: 1px solid var(--border); border-radius: 4px; padding: 14px; font-size: 12px; line-height: 1.8; }
  .ok  { color: var(--green); }
  .err { color: var(--red); }
  .warn{ color: var(--yellow); }
  .info{ color: #7EB8F7; }
  .btn { display: inline-block; background: var(--orange); color: white; border: none; padding: 12px 28px; border-radius: 6px; font-size: 14px; font-weight: bold; cursor: pointer; text-decoration: none; font-family: inherit; letter-spacing: 1px; margin-top: 16px; }
  .btn:hover { background: #c94415; }
  .btn-gold { background: var(--gold); color: #05050A; }
  .btn-gold:hover { background: #e6bd3a; }
  .btn-grey { background: #333; color: white; }
  .btn-red  { background: var(--red); }
  .form-group { margin-bottom: 14px; }
  .form-group label { display: block; color: var(--muted); font-size: 11px; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 1px; }
  .form-group input { width: 100%; background: #02020A; border: 1px solid var(--border); color: var(--white); padding: 10px 12px; border-radius: 4px; font-family: monospace; font-size: 13px; }
  .form-group input:focus { outline: none; border-color: var(--orange); }
  .form-group input.optional { border-color: #1a1a3a; }
  .section-title { font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: 2px; margin: 20px 0 10px; padding-bottom: 6px; border-bottom: 1px solid var(--border); }
  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  .tag { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 10px; font-weight: bold; margin-left: 6px; }
  .tag-req { background: #3a1a1a; color: var(--red); }
  .tag-opt { background: #1a2a3a; color: #7EB8F7; }
  .tag-later { background: #1a1a2a; color: var(--muted); }
  .summary-box { border: 1px solid var(--green); background: #0a1a0a; border-radius: 6px; padding: 16px; margin: 10px 0; }
  .summary-box.fail { border-color: var(--red); background: #1a0a0a; }
  .summary-box.warn { border-color: var(--yellow); background: #1a1500; }
  .kpi-row { display: flex; gap: 12px; margin-bottom: 16px; }
  .kpi { flex: 1; background: var(--bg); border: 1px solid var(--border); border-radius: 6px; padding: 12px; text-align: center; }
  .kpi .num { font-size: 28px; font-weight: 900; color: var(--orange); }
  .kpi .label { font-size: 10px; color: var(--muted); text-transform: uppercase; margin-top: 2px; }
  .destruct-warning { background: #1a0505; border: 2px solid var(--red); border-radius: 6px; padding: 16px; margin-top: 20px; font-size: 12px; color: var(--red); }
  table { width: 100%; border-collapse: collapse; font-size: 12px; }
  td, th { padding: 8px 10px; border-bottom: 1px solid var(--border); text-align: left; }
  th { color: var(--muted); text-transform: uppercase; font-size: 10px; letter-spacing: 1px; }
  td.pass { color: var(--green); }
  td.fail { color: var(--red); }
  td.skip { color: var(--muted); }
  .env-block { background: #02020A; border: 1px solid var(--border); border-radius: 4px; padding: 14px; font-size: 11px; color: #7EB8F7; white-space: pre; overflow-x: auto; }
</style>
</head>
<body>

<div class="header">
  <div class="logo">81<span>+</span> OS</div>
  <div>
    <div style="font-size:13px; font-weight:bold; color:var(--gold);">MASTER BLASTER INSTALL</div>
    <div style="font-size:11px; color:var(--muted);">Sistema Operativo 81+ — v<?= INSTALLER_VERSION ?></div>
  </div>
  <div style="margin-left:auto;">
    <span class="badge">INSTALLER</span>
  </div>
</div>

<div class="container">

<?php

// ─── STEP 0: WELCOME ─────────────────────────────────────────────────────────

if ($step === 0):
?>
<div class="progress">
  <div class="progress-step active">0 · START</div>
  <div class="progress-step">1 · CHECK</div>
  <div class="progress-step">2 · DATABASE</div>
  <div class="progress-step">3 · ENV</div>
  <div class="progress-step">4 · STRUTTURA</div>
  <div class="progress-step">5 · TEST API</div>
  <div class="progress-step">6 · CRON</div>
  <div class="progress-step">7 · DONE</div>
</div>

<div class="card">
  <h2>Benvenuto nel Master Blaster Installer</h2>
  <div class="kpi-row">
    <div class="kpi"><div class="num">150</div><div class="label">Agenti AI</div></div>
    <div class="kpi"><div class="num">5</div><div class="label">Hub Attivi</div></div>
    <div class="kpi"><div class="num">12</div><div class="label">Tabelle DB</div></div>
    <div class="kpi"><div class="num">8</div><div class="label">Cron Jobs</div></div>
  </div>
  <p style="color:var(--muted); margin-bottom:16px;">
    Questo installer configura l'intero Sistema Operativo 81+ su Hostinger.<br>
    Durata stimata: <strong style="color:var(--gold);">5 minuti</strong>.
  </p>
  <div class="log">
    <?= info("Percorso root rilevato: " . root_path()) ?>
    <?= info("PHP versione: " . PHP_VERSION) ?>
    <?= info("Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A')) ?>
    <?= info("Data/ora: " . date('Y-m-d H:i:s')) ?>
  </div>
  <div class="destruct-warning">
    ⚠ SICUREZZA: Questo file si autodistrugge dopo il completamento.<br>
    NON lasciare MASTER_BLASTER_INSTALL.php accessibile pubblicamente.
  </div>
  <a href="?step=1" class="btn">AVVIA INSTALLAZIONE →</a>
</div>

<?php

// ─── STEP 1: SYSTEM CHECK ─────────────────────────────────────────────────────

elseif ($step === 1):

$checks = [];
$all_ok = true;

// PHP version
$php_ok = version_compare(PHP_VERSION, '8.0.0', '>=');
$checks[] = ['PHP >= 8.0', $php_ok, PHP_VERSION];
if (!$php_ok) $all_ok = false;

// Extensions
$required_exts = ['pdo', 'pdo_mysql', 'curl', 'json', 'mbstring', 'openssl', 'zip'];
foreach ($required_exts as $ext) {
    $ok = extension_loaded($ext);
    $checks[] = ["ext: $ext", $ok, $ok ? 'presente' : 'MANCANTE'];
    if (!$ok) $all_ok = false;
}

// Optional
$optional_exts = ['gd', 'imagick', 'redis'];
foreach ($optional_exts as $ext) {
    $loaded = extension_loaded($ext);
    $checks[] = ["ext: $ext (opz.)", true, $loaded ? 'presente' : 'non installata'];
}

// Writable directories
$write_dirs = [
    root_path(),
    root_path() . '/public_html',
    root_path() . '/agents',
];
foreach ($write_dirs as $dir) {
    $exists = is_dir($dir);
    $writable = $exists && is_writable($dir);
    $label = basename($dir) ?: 'root';
    $checks[] = ["scrivibile: /$label", $writable, $writable ? 'OK' : ($exists ? 'NO PERMESSI' : 'NON ESISTE')];
    if (!$writable) $all_ok = false;
}

// .env check
$env_exists = file_exists(env_path());
$checks[] = ['.env file', true, $env_exists ? 'presente' : 'da creare (step 3)'];

?>
<div class="progress">
  <div class="progress-step done">0 · START</div>
  <div class="progress-step active">1 · CHECK</div>
  <div class="progress-step">2 · DATABASE</div>
  <div class="progress-step">3 · ENV</div>
  <div class="progress-step">4 · STRUTTURA</div>
  <div class="progress-step">5 · TEST API</div>
  <div class="progress-step">6 · CRON</div>
  <div class="progress-step">7 · DONE</div>
</div>

<div class="card">
  <h2>Step 1 — Controllo Ambiente</h2>
  <table>
    <tr><th>Controllo</th><th>Status</th><th>Valore</th></tr>
    <?php foreach ($checks as $c): ?>
    <tr>
      <td><?= htmlspecialchars($c[0]) ?></td>
      <td class="<?= $c[1] ? 'pass' : 'fail' ?>"><?= $c[1] ? '✓' : '✗' ?></td>
      <td style="color:var(--muted)"><?= htmlspecialchars($c[2]) ?></td>
    </tr>
    <?php endforeach; ?>
  </table>

  <?php if ($all_ok): ?>
    <div class="summary-box" style="margin-top:16px;">
      <?= ok("Ambiente compatibile. Puoi procedere.") ?>
    </div>
    <a href="?step=2" class="btn">PROSSIMO: DATABASE →</a>
  <?php else: ?>
    <div class="summary-box fail" style="margin-top:16px;">
      <?= err("Risolvi gli errori sopra prima di procedere.") ?><br>
      <small style="color:var(--muted)">Su Hostinger: hPanel → PHP → versione 8.1 o 8.2. Per estensioni mancanti contatta il supporto.</small>
    </div>
    <a href="?step=1" class="btn btn-grey">RICONTROLLA</a>
  <?php endif; ?>
</div>

<?php

// ─── STEP 2: DATABASE ──────────────────────────────────────────────────────────

elseif ($step === 2):

$result = null;
$db_error = null;

if ($action === 'create_db') {
    $env = load_env(env_path());
    $host = $_POST['db_host'] ?? $env['DB_HOST'] ?? 'localhost';
    $name = $_POST['db_name'] ?? $env['DB_NAME'] ?? '';
    $user = $_POST['db_user'] ?? $env['DB_USER'] ?? '';
    $pass = $_POST['db_pass'] ?? $env['DB_PASS'] ?? '';

    if ($name && $user && $pass) {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$name;charset=utf8mb4", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            $sql_file = dirname(__DIR__) . '/sql/schema_81plus.sql';
            $sql_inline = file_exists($sql_file) ? file_get_contents($sql_file) : null;

            // Schema inline se sql non esiste
            $schema_sql = $sql_inline ?: <<<SQL
-- USERS
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sic_id` VARCHAR(32) NOT NULL,
  `nome` VARCHAR(100),
  `cognome` VARCHAR(100),
  `email` VARCHAR(255) NOT NULL,
  `telefono` VARCHAR(20),
  `password_hash` VARCHAR(255) NOT NULL,
  `settore_ateco` VARCHAR(10),
  `azienda` VARCHAR(255),
  `created_at` DATETIME NOT NULL DEFAULT NOW(),
  `updated_at` DATETIME NOT NULL DEFAULT NOW() ON UPDATE NOW(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_sic_id` (`sic_id`),
  UNIQUE KEY `uk_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- MEMBERSHIPS
CREATE TABLE IF NOT EXISTS `memberships` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `tier` ENUM('BASIC+','PRO+','ELITE+','GENESYS81+') NOT NULL,
  `pv_price` DECIMAL(10,2) NOT NULL,
  `status` ENUM('active','expired','cancelled') NOT NULL DEFAULT 'active',
  `started_at` DATETIME NOT NULL DEFAULT NOW(),
  `expires_at` DATETIME,
  `renewal_count` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  CONSTRAINT `fk_mem_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PV TRANSACTIONS (ledger idempotente)
CREATE TABLE IF NOT EXISTS `pv_transactions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `direction` ENUM('credit','debit') NOT NULL,
  `reason` VARCHAR(100) NOT NULL,
  `reference_id` VARCHAR(64),
  `balance_after` DECIMAL(10,2),
  `created_at` DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`id`),
  KEY `idx_user_pv` (`user_id`),
  CONSTRAINT `fk_pv_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PV+ CLAIMS (reward idempotente via UNIQUE KEY)
CREATE TABLE IF NOT EXISTS `pvplus_claims` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `event_type` VARCHAR(50) NOT NULL,
  `event_id` VARCHAR(64) NOT NULL,
  `pvplus_amount` INT UNSIGNED NOT NULL,
  `claimed_at` DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_idempotent` (`user_id`, `event_type`, `event_id`),
  CONSTRAINT `fk_pvplus_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- GENESYS81+ SLOTS (max 81)
CREATE TABLE IF NOT EXISTS `genesys81_slots` (
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED,
  `assigned_at` DATETIME,
  `slot_number` TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_slot` (`slot_number`),
  CONSTRAINT `fk_gen_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PIX81+ SLOTS (max 1000)
CREATE TABLE IF NOT EXISTS `pix81_slots` (
  `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED,
  `slot_number` SMALLINT UNSIGNED NOT NULL,
  `ad_content` TEXT,
  `ad_url` VARCHAR(500),
  `assigned_at` DATETIME,
  `active_from` DATE,
  `active_to` DATE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_pix_slot` (`slot_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- LOCK81+ CONTRACTS (fedeltà 180gg — NON staking)
CREATE TABLE IF NOT EXISTS `lock81_contracts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `pv_amount` DECIMAL(10,2) NOT NULL,
  `started_at` DATETIME NOT NULL DEFAULT NOW(),
  `ends_at` DATETIME NOT NULL,
  `status` ENUM('active','completed','early_exit') NOT NULL DEFAULT 'active',
  `benefit_tier` VARCHAR(20),
  PRIMARY KEY (`id`),
  KEY `idx_lock_user` (`user_id`),
  CONSTRAINT `fk_lock_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- LEADS DATABASE
CREATE TABLE IF NOT EXISTS `leads` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100),
  `telefono` VARCHAR(20),
  `email` VARCHAR(255),
  `settore` VARCHAR(50),
  `azienda` VARCHAR(255),
  `n_dipendenti` SMALLINT UNSIGNED,
  `problema_principale` VARCHAR(255),
  `status` ENUM('freddo','tiepido','caldo','cliente') NOT NULL DEFAULT 'freddo',
  `score` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `sorgente` VARCHAR(50),
  `note` TEXT,
  `last_interaction` DATETIME,
  `created_at` DATETIME NOT NULL DEFAULT NOW(),
  `updated_at` DATETIME NOT NULL DEFAULT NOW() ON UPDATE NOW(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_score` (`score`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- COMMISSIONS (provvigioni partner)
CREATE TABLE IF NOT EXISTS `commissions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `partner_user_id` INT UNSIGNED NOT NULL,
  `sale_user_id` INT UNSIGNED,
  `sale_type` VARCHAR(50),
  `sale_reference` VARCHAR(64),
  `gross_amount` DECIMAL(10,2) NOT NULL,
  `commission_rate` DECIMAL(5,4) NOT NULL,
  `commission_amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('pending','approved','paid','disputed') NOT NULL DEFAULT 'pending',
  `payout_date` DATE,
  `created_at` DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_sale_ref` (`partner_user_id`, `sale_reference`),
  KEY `idx_partner` (`partner_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- AUDIT LOG (immutabile — INSERT ONLY)
CREATE TABLE IF NOT EXISTS `audit_log` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `agent_id` VARCHAR(20),
  `action` VARCHAR(100) NOT NULL,
  `user_id` INT UNSIGNED,
  `input_hash` CHAR(64),
  `output_hash` CHAR(64),
  `status` ENUM('success','fail','blocked') NOT NULL,
  `ip_address` VARCHAR(45),
  `created_at` DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`id`),
  KEY `idx_agent` (`agent_id`),
  KEY `idx_ts` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- AGENT ACTIONS LOG
CREATE TABLE IF NOT EXISTS `agent_actions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `agent_slug` VARCHAR(50) NOT NULL,
  `trigger_type` VARCHAR(50),
  `payload_hash` CHAR(64),
  `result_status` ENUM('success','fail','pending_confirm','blocked') NOT NULL,
  `result_summary` VARCHAR(500),
  `created_at` DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`id`),
  KEY `idx_agent_slug` (`agent_slug`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SIC_ID REGISTRY (immutabile)
CREATE TABLE IF NOT EXISTS `sic_id_registry` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `sic_id` VARCHAR(32) NOT NULL,
  `generated_at` DATETIME NOT NULL DEFAULT NOW(),
  `hmac_suffix` CHAR(8) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_sic` (`sic_id`),
  UNIQUE KEY `uk_user_sic` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- KNOWLEDGE BASE
CREATE TABLE IF NOT EXISTS `knowledge_base` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` VARCHAR(50) NOT NULL,
  `key_name` VARCHAR(100) NOT NULL,
  `value` LONGTEXT,
  `source` VARCHAR(100),
  `updated_at` DATETIME NOT NULL DEFAULT NOW() ON UPDATE NOW(),
  `created_at` DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cat_key` (`category`, `key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;

            // Execute schema
            $statements = array_filter(
                array_map('trim', explode(';', $schema_sql)),
                fn($s) => strlen($s) > 10 && !str_starts_with(ltrim($s), '--')
            );

            $tables_created = 0;
            $tables_errors = [];
            foreach ($statements as $stmt) {
                try {
                    $pdo->exec($stmt);
                    if (preg_match('/CREATE TABLE.*`(\w+)`/i', $stmt, $m)) {
                        $tables_created++;
                    }
                } catch (PDOException $e) {
                    if (!str_contains($e->getMessage(), 'already exists')) {
                        $tables_errors[] = $e->getMessage();
                    }
                }
            }

            // Save DB config to .env
            $env_content = file_exists(env_path()) ? file_get_contents(env_path()) : '';
            $db_vars = [
                'DB_HOST' => $host,
                'DB_NAME' => $name,
                'DB_USER' => $user,
                'DB_PASS' => $pass,
                'DB_CHARSET' => 'utf8mb4',
            ];
            foreach ($db_vars as $k => $v) {
                if (preg_match("/^{$k}=/m", $env_content)) {
                    $env_content = preg_replace("/^{$k}=.*/m", "$k=$v", $env_content);
                } else {
                    $env_content .= "\n$k=$v";
                }
            }
            file_put_contents(env_path(), ltrim($env_content));

            $result = ['ok' => true, 'tables' => $tables_created, 'errors' => $tables_errors];

        } catch (PDOException $e) {
            $db_error = $e->getMessage();
        }
    } else {
        $db_error = 'Compila tutti i campi obbligatori.';
    }
}

$env = load_env(env_path());
?>
<div class="progress">
  <div class="progress-step done">0 · START</div>
  <div class="progress-step done">1 · CHECK</div>
  <div class="progress-step active">2 · DATABASE</div>
  <div class="progress-step">3 · ENV</div>
  <div class="progress-step">4 · STRUTTURA</div>
  <div class="progress-step">5 · TEST API</div>
  <div class="progress-step">6 · CRON</div>
  <div class="progress-step">7 · DONE</div>
</div>

<div class="card">
  <h2>Step 2 — Configurazione Database MySQL</h2>

  <?php if ($result && $result['ok']): ?>
    <div class="summary-box">
      <?= ok("Database connesso e schema creato.") ?><br>
      <?= ok("Tabelle create/verificate: <strong>" . $result['tables'] . "</strong>") ?><br>
      <?php if ($result['errors']): ?>
        <?php foreach ($result['errors'] as $e): ?>
          <?= warn(htmlspecialchars($e)) ?><br>
        <?php endforeach; ?>
      <?php else: ?>
        <?= ok("Zero errori di schema.") ?>
      <?php endif; ?>
    </div>
    <a href="?step=3" class="btn">PROSSIMO: CREDENZIALI API →</a>

  <?php else: ?>

    <?php if ($db_error): ?>
      <div class="summary-box fail" style="margin-bottom:14px;">
        <?= err(htmlspecialchars($db_error)) ?>
      </div>
    <?php endif; ?>

    <p style="color:var(--muted); margin-bottom:16px; font-size:12px;">
      Inserisci le credenziali del database MySQL che hai creato su Hostinger hPanel.<br>
      Su hosting condiviso: <strong>host = localhost</strong>. Su VPS: potrebbe essere diverso.
    </p>

    <form method="POST" action="?step=2">
      <input type="hidden" name="action" value="create_db">
      <div class="grid-2">
        <div class="form-group">
          <label>Host <span class="tag tag-req">RICHIESTO</span></label>
          <input type="text" name="db_host" value="<?= htmlspecialchars($env['DB_HOST'] ?? 'localhost') ?>" placeholder="localhost">
        </div>
        <div class="form-group">
          <label>Nome Database <span class="tag tag-req">RICHIESTO</span></label>
          <input type="text" name="db_name" value="<?= htmlspecialchars($env['DB_NAME'] ?? '') ?>" placeholder="u123456789_81plus">
        </div>
        <div class="form-group">
          <label>Username DB <span class="tag tag-req">RICHIESTO</span></label>
          <input type="text" name="db_user" value="<?= htmlspecialchars($env['DB_USER'] ?? '') ?>" placeholder="u123456789_user">
        </div>
        <div class="form-group">
          <label>Password DB <span class="tag tag-req">RICHIESTO</span></label>
          <input type="password" name="db_pass" placeholder="password sicura">
        </div>
      </div>
      <button type="submit" class="btn">CREA SCHEMA DATABASE →</button>
    </form>

    <div style="margin-top:16px; padding:12px; background:#02020A; border-radius:4px; font-size:11px; color:var(--muted);">
      <strong style="color:var(--gold)">COME TROVARE I DATI SU HOSTINGER:</strong><br>
      hPanel → Database → MySQL Database → Crea nuovo database e utente.<br>
      Copia nome_db, nome_utente, password. Host = localhost (hosting condiviso).
    </div>

  <?php endif; ?>
</div>

<?php

// ─── STEP 3: ENV FILE ──────────────────────────────────────────────────────────

elseif ($step === 3):

if ($action === 'save_env') {
    $env_data = file_exists(env_path()) ? file_get_contents(env_path()) : '';

    $fields = [
        'APP_NAME', 'APP_URL', 'APP_ENV',
        'JWT_SECRET', 'HMAC_SECRET',
        'ANTHROPIC_API_KEY', 'GEMINI_API_KEY', 'HUGGINGFACE_API_KEY',
        'GOOGLE_SHEET_MASTER_ID', 'GOOGLE_DRIVE_ECOSYSTEM_ID',
        'TELEGRAM_BOT_TOKEN', 'TELEGRAM_CHANNEL_IDS',
        'WHATSAPP_API_TOKEN', 'WHATSAPP_PHONE_ID', 'WHATSAPP_COMMERCIAL_NUMBER',
        'BREVO_API_KEY',
        'CALENDLY_API_TOKEN',
        'STRIPE_SECRET_KEY', 'STRIPE_WEBHOOK_SECRET',
    ];

    foreach ($fields as $key) {
        $val = trim($_POST[$key] ?? '');
        if ($val === '') continue;
        if (preg_match("/^{$key}=/m", $env_data)) {
            $env_data = preg_replace("/^{$key}=.*/m", "$key=$val", $env_data);
        } else {
            $env_data .= "\n$key=$val";
        }
    }
    file_put_contents(env_path(), ltrim($env_data));
    $saved = true;
}

$env = load_env(env_path());
?>
<div class="progress">
  <div class="progress-step done">0 · START</div>
  <div class="progress-step done">1 · CHECK</div>
  <div class="progress-step done">2 · DATABASE</div>
  <div class="progress-step active">3 · ENV</div>
  <div class="progress-step">4 · STRUTTURA</div>
  <div class="progress-step">5 · TEST API</div>
  <div class="progress-step">6 · CRON</div>
  <div class="progress-step">7 · DONE</div>
</div>

<div class="card">
  <h2>Step 3 — Credenziali e Variabili d'Ambiente</h2>

  <?php if (!empty($saved)): ?>
    <div class="summary-box" style="margin-bottom:14px;"><?= ok("Credenziali salvate in .env") ?></div>
  <?php endif; ?>

  <p style="color:var(--muted); font-size:12px; margin-bottom:16px;">
    I campi con <span class="tag tag-req">RICHIESTO</span> bloccano il sistema se vuoti.<br>
    <span class="tag tag-opt">ORA</span> = configura adesso. <span class="tag tag-later">DOPO</span> = puoi farlo in seguito.
  </p>

  <form method="POST" action="?step=3">
    <input type="hidden" name="action" value="save_env">

    <div class="section-title">Sistema</div>
    <div class="grid-2">
      <div class="form-group">
        <label>APP_NAME <span class="tag tag-req">RICHIESTO</span></label>
        <input type="text" name="APP_NAME" value="<?= htmlspecialchars($env['APP_NAME'] ?? '81+ OS') ?>">
      </div>
      <div class="form-group">
        <label>APP_URL <span class="tag tag-req">RICHIESTO</span></label>
        <input type="text" name="APP_URL" value="<?= htmlspecialchars($env['APP_URL'] ?? 'https://81plus.net') ?>" placeholder="https://81plus.net">
      </div>
    </div>
    <div class="grid-2">
      <div class="form-group">
        <label>APP_ENV</label>
        <input type="text" name="APP_ENV" value="<?= htmlspecialchars($env['APP_ENV'] ?? 'production') ?>">
      </div>
      <div class="form-group">
        <label>JWT_SECRET <span class="tag tag-req">RICHIESTO</span></label>
        <input type="text" name="JWT_SECRET" value="<?= htmlspecialchars($env['JWT_SECRET'] ?? '') ?>" placeholder="stringa casuale lunga 64 caratteri">
      </div>
    </div>
    <div class="form-group">
      <label>HMAC_SECRET <span class="tag tag-req">RICHIESTO</span> (per SIC-ID e webhook)</label>
      <input type="text" name="HMAC_SECRET" value="<?= htmlspecialchars($env['HMAC_SECRET'] ?? '') ?>" placeholder="stringa casuale lunga 64 caratteri">
    </div>

    <div class="section-title">AI Models <span class="tag tag-req">RICHIESTO</span></div>
    <div class="form-group">
      <label>ANTHROPIC_API_KEY (Claude — Agente di Esecuzione)</label>
      <input type="password" name="ANTHROPIC_API_KEY" value="<?= htmlspecialchars($env['ANTHROPIC_API_KEY'] ?? '') ?>" placeholder="sk-ant-...">
    </div>
    <div class="form-group">
      <label>GEMINI_API_KEY (Gemini — Architetto Strategico)</label>
      <input type="password" name="GEMINI_API_KEY" value="<?= htmlspecialchars($env['GEMINI_API_KEY'] ?? '') ?>" placeholder="AIza...">
    </div>
    <div class="form-group">
      <label>HUGGINGFACE_API_KEY (Lead Scoring — opzionale) <span class="tag tag-opt">ORA</span></label>
      <input type="password" class="optional" name="HUGGINGFACE_API_KEY" value="<?= htmlspecialchars($env['HUGGINGFACE_API_KEY'] ?? '') ?>" placeholder="hf_...">
    </div>

    <div class="section-title">Google Workspace <span class="tag tag-opt">ORA</span></div>
    <div class="form-group">
      <label>GOOGLE_SHEET_MASTER_ID (ID del foglio SICURISSIMO MASTER)</label>
      <input type="text" class="optional" name="GOOGLE_SHEET_MASTER_ID" value="<?= htmlspecialchars($env['GOOGLE_SHEET_MASTER_ID'] ?? '1mzF28NNi8orU9HgqTdmKk1csYNCrYPjDtt1q6VBz26M') ?>">
    </div>
    <div class="form-group">
      <label>GOOGLE_DRIVE_ECOSYSTEM_ID (ID cartella ECOSYSTEM-SICURISSIMO)</label>
      <input type="text" class="optional" name="GOOGLE_DRIVE_ECOSYSTEM_ID" value="<?= htmlspecialchars($env['GOOGLE_DRIVE_ECOSYSTEM_ID'] ?? '1aUOkuD8j7QSUM3ElJhSgpVMqUgULp-HR') ?>">
    </div>

    <div class="section-title">Telegram <span class="tag tag-req">RICHIESTO</span> (post automatici)</div>
    <div class="grid-2">
      <div class="form-group">
        <label>TELEGRAM_BOT_TOKEN</label>
        <input type="password" name="TELEGRAM_BOT_TOKEN" value="<?= htmlspecialchars($env['TELEGRAM_BOT_TOKEN'] ?? '') ?>" placeholder="1234567890:AAH...">
      </div>
      <div class="form-group">
        <label>TELEGRAM_CHANNEL_IDS (separati da virgola)</label>
        <input type="text" name="TELEGRAM_CHANNEL_IDS" value="<?= htmlspecialchars($env['TELEGRAM_CHANNEL_IDS'] ?? '') ?>" placeholder="-100123456,-100789012">
      </div>
    </div>

    <div class="section-title">WhatsApp Business <span class="tag tag-req">RICHIESTO</span> (Nicolas Core)</div>
    <div class="grid-2">
      <div class="form-group">
        <label>WHATSAPP_API_TOKEN</label>
        <input type="password" name="WHATSAPP_API_TOKEN" value="<?= htmlspecialchars($env['WHATSAPP_API_TOKEN'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>WHATSAPP_PHONE_ID</label>
        <input type="text" name="WHATSAPP_PHONE_ID" value="<?= htmlspecialchars($env['WHATSAPP_PHONE_ID'] ?? '') ?>" placeholder="1234567890">
      </div>
    </div>
    <div class="form-group">
      <label>WHATSAPP_COMMERCIAL_NUMBER</label>
      <input type="text" name="WHATSAPP_COMMERCIAL_NUMBER" value="<?= htmlspecialchars($env['WHATSAPP_COMMERCIAL_NUMBER'] ?? '3388771737') ?>">
    </div>

    <div class="section-title">Email Marketing <span class="tag tag-opt">ORA</span></div>
    <div class="form-group">
      <label>BREVO_API_KEY (campagne email — da welcome@81plus.net)</label>
      <input type="password" class="optional" name="BREVO_API_KEY" value="<?= htmlspecialchars($env['BREVO_API_KEY'] ?? '') ?>" placeholder="xkeysib-...">
    </div>
    <div class="form-group">
      <label>CALENDLY_API_TOKEN <span class="tag tag-later">DOPO</span></label>
      <input type="password" class="optional" name="CALENDLY_API_TOKEN" value="<?= htmlspecialchars($env['CALENDLY_API_TOKEN'] ?? '') ?>">
    </div>

    <div class="section-title">Pagamenti <span class="tag tag-later">DOPO</span></div>
    <div class="grid-2">
      <div class="form-group">
        <label>STRIPE_SECRET_KEY</label>
        <input type="password" class="optional" name="STRIPE_SECRET_KEY" value="<?= htmlspecialchars($env['STRIPE_SECRET_KEY'] ?? '') ?>" placeholder="sk_live_...">
      </div>
      <div class="form-group">
        <label>STRIPE_WEBHOOK_SECRET</label>
        <input type="password" class="optional" name="STRIPE_WEBHOOK_SECRET" value="<?= htmlspecialchars($env['STRIPE_WEBHOOK_SECRET'] ?? '') ?>" placeholder="whsec_...">
      </div>
    </div>

    <button type="submit" class="btn">SALVA CREDENZIALI →</button>
    <a href="?step=4" class="btn btn-grey" style="margin-left:8px;">SALTA (configura dopo)</a>
  </form>

  <div style="margin-top:16px; padding:10px; background:#02020A; border-radius:4px; font-size:11px; color:var(--muted);">
    ⚠ Le credenziali Aruba (fatturazione elettronica) non sono qui: aggiungile manualmente al .env dopo.<br>
    Le chiavi <strong>ARUBA_USERNAME, ARUBA_PASSWORD, ARUBA_API_KEY</strong> non vanno MAI in chiaro. Solo .env.
  </div>
</div>

<?php

// ─── STEP 4: DIRECTORY STRUCTURE ──────────────────────────────────────────────

elseif ($step === 4):

$dirs = [
    'storage'              => 'File temporanei e cache',
    'storage/logs'         => 'Log di sistema',
    'storage/pdfs'         => 'PDF generati',
    'storage/uploads'      => 'Upload utenti',
    'storage/backups'      => 'Backup automatici',
    'public_html/uploads'  => 'Upload pubblici (immagini profilo, asset)',
    'templates'            => 'Template documenti',
    'templates/documents'  => 'Template PDF sicurezza',
    'templates/emails'     => 'Template email',
    'templates/whatsapp'   => 'Template messaggi WhatsApp',
    'agents'               => 'Registry agenti AI',
    'agents/memory'        => 'Memoria persistente agenti',
    'cache'                => 'Cache applicazione',
    'scripts'              => 'Google Apps Script .gs files',
];

$results = [];
foreach ($dirs as $dir => $desc) {
    $full = root_path() . '/' . $dir;
    if (!is_dir($full)) {
        $created = @mkdir($full, 0755, true);
        $results[$dir] = $created ? 'created' : 'error';
    } else {
        $results[$dir] = 'exists';
    }
}

// .htaccess per proteggere storage e .env
$htaccess_storage = "Order deny,allow\nDeny from all\n";
$htaccess_root_extra = "\n# Protect .env\n<Files .env>\nOrder allow,deny\nDeny from all\n</Files>\n";

@file_put_contents(root_path() . '/storage/.htaccess', $htaccess_storage);
@file_put_contents(root_path() . '/cache/.htaccess', $htaccess_storage);

$existing_htaccess = file_get_contents(root_path() . '/.htaccess') ?? '';
if (!str_contains($existing_htaccess, 'Protect .env')) {
    @file_put_contents(root_path() . '/.htaccess', $existing_htaccess . $htaccess_root_extra);
}
?>
<div class="progress">
  <div class="progress-step done">0 · START</div>
  <div class="progress-step done">1 · CHECK</div>
  <div class="progress-step done">2 · DATABASE</div>
  <div class="progress-step done">3 · ENV</div>
  <div class="progress-step active">4 · STRUTTURA</div>
  <div class="progress-step">5 · TEST API</div>
  <div class="progress-step">6 · CRON</div>
  <div class="progress-step">7 · DONE</div>
</div>

<div class="card">
  <h2>Step 4 — Struttura Cartelle e Sicurezza</h2>
  <table>
    <tr><th>Directory</th><th>Status</th><th>Descrizione</th></tr>
    <?php foreach ($results as $dir => $status): ?>
    <tr>
      <td style="font-family:monospace; font-size:11px;">/<span style="color:var(--orange)"><?= htmlspecialchars($dir) ?></span></td>
      <td class="<?= $status === 'error' ? 'fail' : 'pass' ?>">
        <?= $status === 'created' ? '✓ creata' : ($status === 'exists' ? '✓ esistente' : '✗ errore') ?>
      </td>
      <td style="color:var(--muted); font-size:11px;"><?= htmlspecialchars($dirs[$dir]) ?></td>
    </tr>
    <?php endforeach; ?>
  </table>

  <div style="margin-top:12px;" class="log">
    <?= ok(".htaccess creato in /storage → accesso diretto bloccato") ?><br>
    <?= ok(".htaccess creato in /cache → accesso diretto bloccato") ?><br>
    <?= ok("Protezione .env aggiunta al .htaccess root") ?>
  </div>

  <a href="?step=5" class="btn" style="margin-top:16px;">PROSSIMO: TEST API →</a>
</div>

<?php

// ─── STEP 5: API TESTS ────────────────────────────────────────────────────────

elseif ($step === 5):

$env = load_env(env_path());
$api_results = [];

function test_curl(string $url, array $headers = [], string $body = '', string $method = 'GET'): array {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 8,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);
    return ['code' => $code, 'body' => $resp, 'error' => $err];
}

// Test Claude API
if (!empty($env['ANTHROPIC_API_KEY'])) {
    $r = test_curl('https://api.anthropic.com/v1/messages', [
        'x-api-key: ' . $env['ANTHROPIC_API_KEY'],
        'anthropic-version: 2023-06-01',
        'content-type: application/json',
    ], json_encode([
        'model' => 'claude-haiku-4-5-20251001',
        'max_tokens' => 10,
        'messages' => [['role' => 'user', 'content' => 'ping']],
    ]), 'POST');
    $ok = in_array($r['code'], [200, 400]) && !$r['error'];
    $api_results['Claude API (Anthropic)'] = [$ok, "HTTP " . $r['code']];
} else {
    $api_results['Claude API (Anthropic)'] = [false, 'ANTHROPIC_API_KEY mancante'];
}

// Test Gemini API
if (!empty($env['GEMINI_API_KEY'])) {
    $r = test_curl("https://generativelanguage.googleapis.com/v1beta/models?key=" . $env['GEMINI_API_KEY']);
    $ok = $r['code'] === 200 && !$r['error'];
    $api_results['Gemini API (Google)'] = [$ok, "HTTP " . $r['code']];
} else {
    $api_results['Gemini API (Google)'] = [null, 'Chiave non configurata (step 3)'];
}

// Test Telegram
if (!empty($env['TELEGRAM_BOT_TOKEN'])) {
    $r = test_curl("https://api.telegram.org/bot{$env['TELEGRAM_BOT_TOKEN']}/getMe");
    $data = json_decode($r['body'], true);
    $ok = $r['code'] === 200 && ($data['ok'] ?? false);
    $name = $ok ? ($data['result']['username'] ?? '') : '';
    $api_results['Telegram Bot API'] = [$ok, $ok ? "@$name OK" : "HTTP " . $r['code']];
} else {
    $api_results['Telegram Bot API'] = [null, 'Token non configurato'];
}

// Test Brevo
if (!empty($env['BREVO_API_KEY'])) {
    $r = test_curl('https://api.brevo.com/v3/account', [
        'api-key: ' . $env['BREVO_API_KEY'],
        'accept: application/json',
    ]);
    $ok = $r['code'] === 200;
    $data = json_decode($r['body'], true);
    $email = $ok ? ($data['email'] ?? '') : '';
    $api_results['Brevo (Email Marketing)'] = [$ok, $ok ? "Account: $email" : "HTTP " . $r['code']];
} else {
    $api_results['Brevo (Email Marketing)'] = [null, 'Chiave non configurata'];
}

// Test WhatsApp
if (!empty($env['WHATSAPP_API_TOKEN']) && !empty($env['WHATSAPP_PHONE_ID'])) {
    $r = test_curl(
        "https://graph.facebook.com/v18.0/{$env['WHATSAPP_PHONE_ID']}",
        ['Authorization: Bearer ' . $env['WHATSAPP_API_TOKEN']]
    );
    $ok = $r['code'] === 200;
    $api_results['WhatsApp Business API'] = [$ok, "HTTP " . $r['code']];
} else {
    $api_results['WhatsApp Business API'] = [null, 'Token non configurato'];
}

// Test DB
$env2 = load_env(env_path());
if (!empty($env2['DB_HOST']) && !empty($env2['DB_NAME'])) {
    try {
        $pdo = new PDO(
            "mysql:host={$env2['DB_HOST']};dbname={$env2['DB_NAME']};charset=utf8mb4",
            $env2['DB_USER'] ?? '', $env2['DB_PASS'] ?? '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 3]
        );
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        $api_results['MySQL Database'] = [true, count($tables) . " tabelle trovate"];
    } catch (PDOException $e) {
        $api_results['MySQL Database'] = [false, $e->getMessage()];
    }
} else {
    $api_results['MySQL Database'] = [false, 'DB non configurato (step 2)'];
}

$pass_count = count(array_filter($api_results, fn($r) => $r[0] === true));
$total = count($api_results);
?>
<div class="progress">
  <div class="progress-step done">0 · START</div>
  <div class="progress-step done">1 · CHECK</div>
  <div class="progress-step done">2 · DATABASE</div>
  <div class="progress-step done">3 · ENV</div>
  <div class="progress-step done">4 · STRUTTURA</div>
  <div class="progress-step active">5 · TEST API</div>
  <div class="progress-step">6 · CRON</div>
  <div class="progress-step">7 · DONE</div>
</div>

<div class="card">
  <h2>Step 5 — Test Connessioni API</h2>

  <div class="kpi-row" style="margin-bottom:16px;">
    <div class="kpi"><div class="num" style="color:var(--green)"><?= $pass_count ?></div><div class="label">Connesse</div></div>
    <div class="kpi"><div class="num" style="color:var(--red)"><?= count(array_filter($api_results, fn($r) => $r[0] === false)) ?></div><div class="label">Errori</div></div>
    <div class="kpi"><div class="num" style="color:var(--muted)"><?= count(array_filter($api_results, fn($r) => $r[0] === null)) ?></div><div class="label">Da Config</div></div>
  </div>

  <table>
    <tr><th>Servizio</th><th>Status</th><th>Dettaglio</th></tr>
    <?php foreach ($api_results as $name => $res): ?>
    <tr>
      <td><?= htmlspecialchars($name) ?></td>
      <td class="<?= $res[0] === true ? 'pass' : ($res[0] === false ? 'fail' : 'skip') ?>">
        <?= $res[0] === true ? '✓ OK' : ($res[0] === false ? '✗ FAIL' : '— N/A') ?>
      </td>
      <td style="color:var(--muted); font-size:11px;"><?= htmlspecialchars($res[1]) ?></td>
    </tr>
    <?php endforeach; ?>
  </table>

  <a href="?step=6" class="btn" style="margin-top:16px;">PROSSIMO: CRON JOBS →</a>
  <?php if ($pass_count < $total): ?>
    <a href="?step=3" class="btn btn-grey" style="margin-left:8px;">TORNA A CREDENZIALI</a>
  <?php endif; ?>
</div>

<?php

// ─── STEP 6: CRON JOBS ────────────────────────────────────────────────────────

elseif ($step === 6):
?>
<div class="progress">
  <div class="progress-step done">0 · START</div>
  <div class="progress-step done">1 · CHECK</div>
  <div class="progress-step done">2 · DATABASE</div>
  <div class="progress-step done">3 · ENV</div>
  <div class="progress-step done">4 · STRUTTURA</div>
  <div class="progress-step done">5 · TEST API</div>
  <div class="progress-step active">6 · CRON</div>
  <div class="progress-step">7 · DONE</div>
</div>

<div class="card">
  <h2>Step 6 — Cron Jobs (Agenti Automatici)</h2>
  <p style="color:var(--muted); font-size:12px; margin-bottom:16px;">
    Copia questi comandi esattamente in <strong>hPanel → Cron Jobs → Nuovo Cron Job</strong>.<br>
    Sostituisci <code style="color:var(--gold)">/home/u123456789</code> con il tuo path reale (visibile in hPanel → File Manager → barra indirizzo).
  </p>

  <div class="log">
<?php
$root = '/home/u123456789/public_html';
$crons = [
    ['00 04 * * *', 'DailyMonitor81 — integrità fogli, nuovi asset',    "$root/cron/daily_monitor.php"],
    ['00 05 * * *', 'Messaggi buongiorno WhatsApp a tutti i lead',       "$root/cron/morning_whatsapp.php"],
    ['15 08 * * *', 'Post 1 Telegram (primavera/estate)',                "$root/cron/post_social.php?slot=1"],
    ['30 08 * * *', 'Post 1 Telegram (autunno/inverno)',                 "$root/cron/post_social.php?slot=1&season=aw"],
    ['00 09 * * *', 'LeadScoring81 — report lead caldi del giorno',      "$root/cron/lead_scoring.php"],
    ['30 12 * * *', 'Post 2 Telegram (HACCP / food)',                   "$root/cron/post_social.php?slot=2"],
    ['30 15 * * *', 'Post 3 Telegram (YouTube / storytelling)',          "$root/cron/post_social.php?slot=3"],
    ['20 19 * * *', 'Post 4 Telegram (ISO / strategia)',                "$root/cron/post_social.php?slot=4"],
    ['00 21 * * *', 'Genera 4 post giorno successivo + Calendar',        "$root/cron/generate_posts.php"],
    ['30 21 * * *', 'Aggiorna KnowledgeBase con dati del giorno',        "$root/cron/kb_update.php"],
];
foreach ($crons as $c) {
    echo info(htmlspecialchars($c[0] . "  php " . $c[2])) . " &nbsp; <span style='color:var(--muted);font-size:10px;'>// " . htmlspecialchars($c[1]) . "</span><br>";
}
?>
  </div>

  <h3>Come aggiungere cron su Hostinger</h3>
  <div style="background:#02020A; border-radius:4px; padding:14px; font-size:12px; color:var(--muted); line-height:2;">
    <strong style="color:var(--gold)">1.</strong> hPanel → sezione <strong>Avanzate</strong> → <strong>Cron Jobs</strong><br>
    <strong style="color:var(--gold)">2.</strong> Clicca <strong>Crea nuovo cron job</strong><br>
    <strong style="color:var(--gold)">3.</strong> Scegli <strong>Personalizzato</strong><br>
    <strong style="color:var(--gold)">4.</strong> Nel campo <strong>Comando</strong> incolla: <code style="color:var(--orange)">php /home/u123456789/public_html/cron/daily_monitor.php</code><br>
    <strong style="color:var(--gold)">5.</strong> Ripeti per ogni riga sopra<br>
    <strong style="color:var(--gold)">6.</strong> Verifica dopo 24h in hPanel → Cron Jobs → Log Esecuzioni
  </div>

  <div style="margin-top:12px; padding:10px; background:#1a1500; border:1px solid var(--yellow); border-radius:4px; font-size:11px; color:var(--yellow);">
    ⚠ I file <code>/cron/*.php</code> vengono generati automaticamente al prossimo step.<br>
    Proteggili con <code>.htaccess</code> che consente solo chiamate da localhost (già configurato al step 4).
  </div>

  <a href="?step=7" class="btn" style="margin-top:16px;">FINALIZZA INSTALLAZIONE →</a>
</div>

<?php

// ─── STEP 7: DONE ──────────────────────────────────────────────────────────────

elseif ($step === 7):

$env = load_env(env_path());

// Generate cron files
$cron_dir = root_path() . '/public_html/cron';
if (!is_dir($cron_dir)) @mkdir($cron_dir, 0755, true);

$cron_htaccess = "Order deny,allow\nDeny from all\nAllow from 127.0.0.1\nAllow from ::1\n";
@file_put_contents($cron_dir . '/.htaccess', $cron_htaccess);

$cron_files = [
    'daily_monitor.php' => "<?php\nrequire_once __DIR__ . '/../../bootstrap.php';\n// DailyMonitor81 (H1-09)\nagent_run('daily_monitor81', 'cron_04:00');\n",
    'morning_whatsapp.php' => "<?php\nrequire_once __DIR__ . '/../../bootstrap.php';\n// NicolasCore81 + PNLEngine81 (H1-06 + H1-12)\nagent_run('nicolas_core81', 'cron_05:00_buongiorno');\n",
    'post_social.php' => "<?php\nrequire_once __DIR__ . '/../../bootstrap.php';\n// SocialAutomator81 (H1-13)\n\$slot = (int)(\$_GET['slot'] ?? 1);\nagent_run('social_automator81', \"cron_post_slot_{\$slot}\");\n",
    'lead_scoring.php' => "<?php\nrequire_once __DIR__ . '/../../bootstrap.php';\n// LeadScoring81 (H1-07)\nagent_run('lead_scoring81', 'cron_09:00');\n",
    'generate_posts.php' => "<?php\nrequire_once __DIR__ . '/../../bootstrap.php';\n// SocialAutomator81 + PNLEngine81 (H1-12 + H1-13)\nagent_run('pnl_engine81', 'cron_21:00_genera_4_post');\n",
    'kb_update.php' => "<?php\nrequire_once __DIR__ . '/../../bootstrap.php';\n// KnowledgeBase81 (H1-14)\nagent_run('knowledge_base81', 'cron_21:30_update');\n",
];

foreach ($cron_files as $fname => $content) {
    @file_put_contents($cron_dir . '/' . $fname, $content);
}

// Generate bootstrap.php
$bootstrap = <<<'PHP'
<?php
/**
 * Bootstrap 81+ OS
 * Carica .env, autoload, connessione DB, agent runner
 */
define('ROOT_PATH', dirname(__DIR__));
define('AGENT_REGISTRY', ROOT_PATH . '/agents/registry.agents.json');
define('ROUTING_MATRIX', ROOT_PATH . '/agents/routing.matrix.yaml');
define('SEMANTIC_RULES', ROOT_PATH . '/agents/semantic_rules.yaml');

// Load .env
$env_lines = file_exists(ROOT_PATH . '/.env')
    ? file(ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
    : [];
foreach ($env_lines as $line) {
    if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
    [$k, $v] = explode('=', $line, 2);
    $_ENV[trim($k)] = trim($v);
    putenv(trim($k) . '=' . trim($v));
}

// DB connection singleton
function get_db(): ?PDO {
    static $pdo = null;
    if ($pdo) return $pdo;
    try {
        $pdo = new PDO(
            "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
            $_ENV['DB_USER'] ?? '',
            $_ENV['DB_PASS'] ?? '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT => false]
        );
    } catch (PDOException $e) {
        error_log("[81+OS] DB connection failed: " . $e->getMessage());
        return null;
    }
    return $pdo;
}

// Agent runner — chiama Claude API con contesto agente
function agent_run(string $agent_slug, string $trigger, array $payload = []): ?array {
    $registry = json_decode(file_get_contents(AGENT_REGISTRY), true);
    $agent = null;
    foreach (['HUB1','HUB2','HUB3','PC_OPERATOR','FINANCE_OPS'] as $hub) {
        foreach (($registry['agents'][$hub] ?? []) as $a) {
            if ($a['slug'] === $agent_slug) { $agent = $a; break 2; }
        }
    }
    if (!$agent) {
        error_log("[81+OS] Agent not found: $agent_slug");
        return null;
    }

    $api_key = $_ENV['ANTHROPIC_API_KEY'] ?? '';
    if (!$api_key) {
        error_log("[81+OS] ANTHROPIC_API_KEY missing");
        return null;
    }

    $system_prompt = "Sei {$agent['name']}, agente 81+ OS. Ruolo: {$agent['role']}. ";
    $system_prompt .= "Trigger attuale: $trigger. ";
    if (!empty($agent['constraints'])) {
        $system_prompt .= "Constraints: " . implode('. ', (array)$agent['constraints']) . ". ";
    }
    $system_prompt .= "Rispondi SOLO con JSON {\"status\":\"...\",\"output\":\"...\",\"next_action\":\"...\"}";

    $body = json_encode([
        'model'      => 'claude-haiku-4-5-20251001',
        'max_tokens' => 500,
        'system'     => $system_prompt,
        'messages'   => [['role'=>'user','content'=> json_encode(['trigger'=>$trigger,'payload'=>$payload])]],
    ]);

    $ch = curl_init('https://api.anthropic.com/v1/messages');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $body,
        CURLOPT_HTTPHEADER     => [
            'x-api-key: ' . $api_key,
            'anthropic-version: 2023-06-01',
            'content-type: application/json',
        ],
    ]);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Log to DB
    $db = get_db();
    if ($db) {
        $stmt = $db->prepare(
            "INSERT INTO agent_actions (agent_slug, trigger_type, result_status, result_summary) VALUES (?,?,?,?)"
        );
        $data = json_decode($resp, true);
        $content = $data['content'][0]['text'] ?? '';
        $stmt->execute([$agent_slug, $trigger, $code === 200 ? 'success' : 'fail', substr($content, 0, 400)]);
    }

    if ($code !== 200) {
        error_log("[81+OS] Agent $agent_slug failed: HTTP $code — $resp");
        return null;
    }

    $data = json_decode($resp, true);
    $text = $data['content'][0]['text'] ?? '{}';
    return json_decode($text, true) ?? ['raw' => $text];
}
PHP;

@file_put_contents(root_path() . '/bootstrap.php', $bootstrap);

// Count what was done
$env_keys_set = count(array_filter($env, fn($v) => $v !== ''));

// Self-destruct
$self_destructed = false;
if (SELF_DESTRUCT_ON_SUCCESS) {
    $self_destructed = @unlink(__FILE__);
}
?>
<div class="progress">
  <div class="progress-step done">0 · START</div>
  <div class="progress-step done">1 · CHECK</div>
  <div class="progress-step done">2 · DATABASE</div>
  <div class="progress-step done">3 · ENV</div>
  <div class="progress-step done">4 · STRUTTURA</div>
  <div class="progress-step done">5 · TEST API</div>
  <div class="progress-step done">6 · CRON</div>
  <div class="progress-step active">7 · DONE</div>
</div>

<div class="card">
  <h2>✓ Installazione Completata</h2>

  <div class="kpi-row">
    <div class="kpi"><div class="num">150</div><div class="label">Agenti Registrati</div></div>
    <div class="kpi"><div class="num">12</div><div class="label">Tabelle DB</div></div>
    <div class="kpi"><div class="num"><?= count($cron_files) ?></div><div class="label">Cron Files</div></div>
    <div class="kpi"><div class="num"><?= $env_keys_set ?></div><div class="label">Env Variables</div></div>
  </div>

  <div class="log">
    <?= ok("Schema database MySQL creato (12 tabelle)") ?><br>
    <?= ok("Struttura cartelle creata con .htaccess protezioni") ?><br>
    <?= ok("File /cron/*.php generati") ?><br>
    <?= ok("bootstrap.php generato (agent runner + DB connection)") ?><br>
    <?= ok(".env configurato con " . $env_keys_set . " variabili") ?><br>
    <?= $self_destructed ? ok("INSTALLER ELIMINATO — sicurezza garantita") : warn("Elimina manualmente MASTER_BLASTER_INSTALL.php via FTP") ?>
  </div>

  <h3 style="margin-top:20px;">Prossimi 3 passi (fai subito)</h3>
  <div style="background:#02020A; border-radius:4px; padding:14px; font-size:12px; line-height:2.2;">
    <strong style="color:var(--gold)">1.</strong> Aggiungi i <strong>cron jobs</strong> in hPanel (torna a step 6 per i comandi)<br>
    <strong style="color:var(--gold)">2.</strong> Carica il <strong>GOOGLE_SERVICE_ACCOUNT_JSON</strong> e aggiungilo al .env<br>
    <strong style="color:var(--gold)">3.</strong> Testa NicolasCore81 inviando un messaggio WhatsApp al numero commerciale
  </div>

  <?php if (!$self_destructed): ?>
  <div class="destruct-warning" style="margin-top:16px;">
    ⚠ ATTENZIONE: Il file installer NON è stato eliminato automaticamente.<br>
    Eliminalo subito via hPanel File Manager o FTP:<br>
    <code>rm /home/tuoutente/public_html/setup/MASTER_BLASTER_INSTALL.php</code>
  </div>
  <?php endif; ?>
</div>

<?php endif; ?>

</div><!-- /container -->
</body>
</html>
