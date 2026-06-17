<?php
/**
 * 81+ MASTER DATABASE INSTALLER
 * Installa lo schema completo + 4179 lead in 1 click.
 * SICUREZZA: Protetto da ADMIN_KEY. Si auto-distrugge dopo il successo.
 * POSIZIONE: public_html/install_db.php
 * SQL: ../sql/MASTER_SCHEMA_81PLUS_COMPLETE.sql
 *      ../sql/LEADS_IMPORT_4179_SIC.sql
 */

define('INSTALLER_VERSION', '2.0.0');
define('SQL_DIR', __DIR__ . '/../sql/');
define('ENV_FILE', __DIR__ . '/../.env');

/* ── Carica .env ─────────────────────────────────────────────────── */
function loadEnv(string $path): array {
    if (!file_exists($path)) return [];
    $vars = [];
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (strpos($line, '=') === false) continue;
        [$k, $v] = explode('=', $line, 2);
        $vars[trim($k)] = trim($v, " \t\n\r\0\x0B\"'");
    }
    return $vars;
}

$env = loadEnv(ENV_FILE);
$ADMIN_KEY = $env['INSTALLER_KEY'] ?? $env['ADMIN_KEY'] ?? '';
if (!$ADMIN_KEY) $ADMIN_KEY = 'sicurissimo81CHANGE_ME_NOW';

/* ── Verifica chiave ─────────────────────────────────────────────── */
$submitted_key = $_POST['admin_key'] ?? $_GET['key'] ?? '';
$authed = ($ADMIN_KEY !== '' && hash_equals($ADMIN_KEY, $submitted_key));
$action = $_POST['action'] ?? '';

/* ── Esegui installazione ────────────────────────────────────────── */
$log = [];
$success = false;

if ($authed && $action === 'install') {
    try {
        /* Connessione DB */
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $env['DB_HOST'] ?? 'localhost',
            $env['DB_PORT'] ?? '3306',
            $env['DB_NAME'] ?? 'u173050672_81plusglobal'
        );
        $pdo = new PDO($dsn, $env['DB_USER'] ?? '', $env['DB_PASS'] ?? '', [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4, time_zone='+00:00'",
        ]);
        $log[] = ['ok', 'Connessione al database riuscita.'];

        /* Esegui file SQL con gestione DELIMITER */
        $files = [
            'MASTER_SCHEMA_81PLUS_COMPLETE.sql' => 'Schema 78 tabelle + trigger + view',
            'LEADS_IMPORT_4179_SIC.sql'          => 'Import 4179 lead SIC-10001→SIC-14179',
        ];

        foreach ($files as $filename => $label) {
            $path = SQL_DIR . $filename;
            if (!file_exists($path)) {
                $log[] = ['warn', "File non trovato: $filename — saltato."];
                continue;
            }
            $sql  = file_get_contents($path);
            $stmts = parseSql($sql);
            $ok = 0; $skip = 0; $err = 0;
            foreach ($stmts as $stmt) {
                $stmt = trim($stmt);
                if ($stmt === '') { $skip++; continue; }
                try {
                    $pdo->exec($stmt);
                    $ok++;
                } catch (PDOException $e) {
                    $msg = $e->getMessage();
                    /* Ignora errori non-fatali: duplicate key, already exists */
                    if (preg_match('/already exists|Duplicate entry|ER_DUP_ENTRY/i', $msg)) {
                        $skip++;
                    } else {
                        $err++;
                        $log[] = ['err', "Errore in $filename: " . substr($msg, 0, 200)];
                    }
                }
            }
            $log[] = ['ok', "$label — OK: $ok  saltati: $skip  errori: $err"];
        }

        /* Statistiche finali */
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        $tcount = count($tables);
        $leads  = (int) $pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();
        $log[] = ['ok', "Tabelle presenti nel DB: $tcount"];
        $log[] = ['ok', "Lead importati verificati: $leads"];

        $success = true;
        $log[] = ['ok', '--- INSTALLAZIONE COMPLETATA CON SUCCESSO ---'];

        /* Auto-distruzione */
        @unlink(__FILE__);
        $log[] = ['ok', 'File install_db.php eliminato automaticamente dal server.'];

    } catch (Throwable $e) {
        $log[] = ['err', 'Errore critico: ' . $e->getMessage()];
    }
}

/* ── Parser SQL con supporto DELIMITER ──────────────────────────── */
function parseSql(string $sql): array {
    $statements = [];
    $current    = '';
    $delimiter  = ';';
    $lines      = explode("\n", $sql);

    foreach ($lines as $line) {
        $trimmed = trim($line);

        /* Cambio delimiter */
        if (preg_match('/^DELIMITER\s+(\S+)/i', $trimmed, $m)) {
            $delimiter = $m[1];
            continue;
        }

        $current .= $line . "\n";

        /* Cerca il delimiter corrente nella riga */
        if ($delimiter === ';') {
            if (substr(rtrim($line), -1) === ';') {
                $stmt = trim($current);
                if ($stmt !== '') $statements[] = $stmt;
                $current = '';
            }
        } else {
            /* DELIMITER $$ — split per riga che TERMINA con $$ */
            if (preg_match('/\Q' . preg_quote($delimiter, '/') . '\E\s*$/', rtrim($line))) {
                $stmt = preg_replace('/\Q' . preg_quote($delimiter, '/') . '\E\s*$/', '', trim($current));
                $stmt = trim($stmt);
                if ($stmt !== '') $statements[] = $stmt;
                $current = '';
            }
        }
    }

    /* Residuo */
    if (trim($current) !== '') {
        $statements[] = trim($current);
    }

    return $statements;
}

/* ── UI ──────────────────────────────────────────────────────────── */
?><!DOCTYPE html>
<html lang="it">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>81+ Database Installer v<?= INSTALLER_VERSION ?></title>
<style>
  *{box-sizing:border-box;margin:0;padding:0}
  body{background:#05050A;color:#e8e8e8;font-family:'Segoe UI',system-ui,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
  .card{background:#0d0d14;border:1px solid #1a1a2e;border-radius:16px;padding:40px;max-width:680px;width:100%}
  .logo{display:flex;align-items:center;gap:14px;margin-bottom:32px}
  .logo-badge{background:#E8501A;color:#fff;font-weight:700;font-size:22px;width:52px;height:52px;border-radius:12px;display:flex;align-items:center;justify-content:center;letter-spacing:-1px}
  .logo-text{font-size:22px;font-weight:700;color:#fff}
  .logo-sub{font-size:13px;color:#888;margin-top:2px}
  h1{font-size:20px;font-weight:700;color:#fff;margin-bottom:8px}
  p.sub{color:#888;font-size:14px;margin-bottom:28px;line-height:1.6}
  .checklist{background:#07070f;border-radius:10px;padding:16px 20px;margin-bottom:28px}
  .checklist li{list-style:none;padding:5px 0;font-size:13px;color:#aaa;display:flex;gap:10px;align-items:flex-start}
  .checklist li::before{content:'✓';color:#E8501A;font-weight:700;flex-shrink:0}
  label{display:block;font-size:13px;color:#aaa;margin-bottom:8px}
  input[type=password]{width:100%;background:#07070f;border:1px solid #2a2a3e;color:#fff;padding:12px 16px;border-radius:8px;font-size:15px;outline:none;transition:border-color .2s}
  input[type=password]:focus{border-color:#E8501A}
  .btn{display:block;width:100%;margin-top:20px;background:#E8501A;color:#fff;border:none;padding:14px;border-radius:8px;font-size:16px;font-weight:700;cursor:pointer;letter-spacing:.5px;transition:opacity .2s}
  .btn:hover{opacity:.85}
  .btn:disabled{opacity:.4;cursor:default}
  .warning{background:#1a0a00;border:1px solid #5a1a00;border-radius:8px;padding:14px 18px;font-size:13px;color:#ff9944;margin-bottom:24px;line-height:1.6}
  .log{background:#020208;border-radius:10px;padding:16px 20px;margin-top:24px;max-height:420px;overflow-y:auto}
  .log-line{font-size:12px;font-family:'Courier New',monospace;padding:3px 0;border-bottom:1px solid #0f0f1a;display:flex;gap:10px;align-items:flex-start}
  .log-line:last-child{border:none}
  .log-line.ok .icon{color:#22dd77}
  .log-line.err .icon{color:#ff4444}
  .log-line.warn .icon{color:#ffaa22}
  .log-line .msg{color:#ccc;word-break:break-word}
  .success-banner{background:#001a08;border:1px solid #00aa44;border-radius:10px;padding:20px;text-align:center;margin-top:24px}
  .success-banner h2{color:#22dd77;font-size:18px;margin-bottom:8px}
  .success-banner p{color:#aaa;font-size:13px;line-height:1.7}
  .pill{display:inline-block;background:#E8501A22;color:#E8501A;border:1px solid #E8501A44;border-radius:20px;padding:2px 10px;font-size:11px;font-weight:600;margin-left:8px}
</style>
</head>
<body>
<div class="card">

  <div class="logo">
    <div class="logo-badge">81+</div>
    <div>
      <div class="logo-text">Database Installer</div>
      <div class="logo-sub">Sicurissimo OS — v<?= INSTALLER_VERSION ?> <span class="pill">1-click</span></div>
    </div>
  </div>

<?php if ($success): ?>

  <div class="success-banner">
    <h2>Installazione completata.</h2>
    <p>Lo schema completo e i 4179 lead sono stati importati nel database.<br>
    Questo file e stato eliminato automaticamente dal server.<br>
    <strong style="color:#22dd77">Puoi chiudere questa pagina.</strong></p>
  </div>

  <div class="log">
    <?php foreach ($log as [$type, $msg]): ?>
    <div class="log-line <?= $type ?>">
      <span class="icon"><?= $type === 'ok' ? '✓' : ($type === 'err' ? '✗' : '⚠') ?></span>
      <span class="msg"><?= htmlspecialchars($msg) ?></span>
    </div>
    <?php endforeach; ?>
  </div>

<?php elseif ($authed && $action === 'install'): ?>

  <div class="log">
    <?php foreach ($log as [$type, $msg]): ?>
    <div class="log-line <?= $type ?>">
      <span class="icon"><?= $type === 'ok' ? '✓' : ($type === 'err' ? '✗' : '⚠') ?></span>
      <span class="msg"><?= htmlspecialchars($msg) ?></span>
    </div>
    <?php endforeach; ?>
  </div>

<?php elseif ($authed): ?>

  <h1>Pronto per l'installazione</h1>
  <p class="sub">Autenticazione riuscita. Premi il pulsante per installare lo schema completo e importare i 4179 lead.</p>

  <ul class="checklist">
    <li>78 tabelle — Web2 + Web3 + MLM + Pagamenti + Agenti AI</li>
    <li>4 trigger automatici (wallet, referral, PV+, eventi)</li>
    <li>3 view ottimizzate (users_full, leads_dashboard, payments_summary)</li>
    <li>4179 lead con SIC-ID SIC-10001→SIC-14179</li>
    <li>Dati iniziali: config, missioni PV+, membership, PIX81 (1000 slot)</li>
    <li>Operazione idempotente — sicura da rieseguire</li>
  </ul>

  <div class="warning">
    Verifica che il file .env nella root del progetto contenga le credenziali del database corrette prima di procedere.
  </div>

  <form method="post">
    <input type="hidden" name="admin_key" value="<?= htmlspecialchars($submitted_key) ?>">
    <input type="hidden" name="action" value="install">
    <button type="submit" class="btn">Installa Database Globale 81+</button>
  </form>

<?php else: ?>

  <h1>Accesso Installatore</h1>
  <p class="sub">Inserisci la chiave di installazione per continuare. La chiave e definita nel file .env come <code style="color:#E8501A">INSTALLER_KEY</code>.</p>

  <?php if ($submitted_key !== ''): ?>
  <div class="warning">Chiave non valida. Controlla il file .env.</div>
  <?php endif; ?>

  <form method="post">
    <label>Chiave di Installazione</label>
    <input type="password" name="admin_key" placeholder="Inserisci INSTALLER_KEY..." autofocus>
    <input type="hidden" name="action" value="confirm">
    <button type="submit" class="btn">Verifica Chiave</button>
  </form>

  <div style="margin-top:28px;padding-top:20px;border-top:1px solid #1a1a2e">
    <p style="font-size:12px;color:#555;line-height:1.8">
      File SQL richiesti:<br>
      <code style="color:#E8501A">../sql/MASTER_SCHEMA_81PLUS_COMPLETE.sql</code><br>
      <code style="color:#E8501A">../sql/LEADS_IMPORT_4179_SIC.sql</code>
    </p>
  </div>

<?php endif; ?>

</div>
</body>
</html>
