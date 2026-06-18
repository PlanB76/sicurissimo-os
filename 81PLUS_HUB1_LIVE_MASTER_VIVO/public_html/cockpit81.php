<?php
// cockpit81.php — Cockpit IMPARA: Missioni, Ruota della Vita, Piramide Maslow
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/core81/wallet_service.php';
auth_require();

$user   = auth_user();
$wallet = WalletService::get_all($user['id']);
$db     = DB::get();
$uid    = (int)$user['id'];
$today  = date('Y-m-d');

// ─── Maslow: calcola livello da pvplus_career_total ──────────────────────────
$career_total = (float)($wallet['pvplus_career_total'] ?? 0);
$maslow_map = [
    1 => ['nome' => 'CONFORMITÀ',  'soglia' => 0,     'colore' => '#6B7280', 'hex' => '6B7280', 'desc' => 'Base legale coperta'],
    2 => ['nome' => 'PROTEZIONE',  'soglia' => 1000,  'colore' => '#CD7F32', 'hex' => 'CD7F32', 'desc' => 'Protezione operativa attiva'],
    3 => ['nome' => 'COMMUNITY',   'soglia' => 5000,  'colore' => '#B9BCC2', 'hex' => 'B9BCC2', 'desc' => 'Parte della rete 81+'],
    4 => ['nome' => 'REPUTAZIONE', 'soglia' => 15000, 'colore' => '#FFD24A', 'hex' => 'FFD24A', 'desc' => 'Esperto riconosciuto'],
    5 => ['nome' => 'LEADERSHIP',  'soglia' => 50000, 'colore' => '#00D9FF', 'hex' => '00D9FF', 'desc' => 'Guida altri imprenditori'],
];
$maslow_level = 1;
foreach ($maslow_map as $lvl => $info) {
    if ($career_total >= $info['soglia']) $maslow_level = $lvl;
}
$ml_current  = $maslow_map[$maslow_level];
$ml_next     = $maslow_map[$maslow_level + 1] ?? null;
$ml_progress = 0;
if ($ml_next) {
    $range       = $ml_next['soglia'] - $ml_current['soglia'];
    $earned      = $career_total - $ml_current['soglia'];
    $ml_progress = $range > 0 ? min(100, (int)round($earned / $range * 100)) : 100;
}

// ─── Missioni di oggi ────────────────────────────────────────────────────────
$stmt = $db->prepare('SELECT * FROM daily_missions WHERE user_id = ? AND data_assegnazione = ? ORDER BY id ASC');
$stmt->execute([$uid, $today]);
$missions_today = $stmt->fetchAll();

if (empty($missions_today)) {
    // Assegna missioni per oggi
    $to_assign = [
        ['codice' => 'DAILY_COCKPIT', 'nome' => 'Missione del giorno completata', 'reward' => 25],
    ];

    $claimed = $db->prepare('SELECT DISTINCT codice_missione FROM pvplus_claims WHERE user_id = ?');
    $claimed->execute([$uid]);
    $claimed_codes = $claimed->fetchAll(\PDO::FETCH_COLUMN) ?: [];

    if (empty($claimed_codes)) {
        $pool = $db->query(
            'SELECT codice, nome, pvplus_base FROM pvplus_missions
             WHERE tipo = "ONETIME" AND attiva = 1
             ORDER BY FIELD(categoria,"ONBOARDING","AUDIT","CRESCITA","MASLOW","REFERRAL","STREAK") ASC
             LIMIT 2'
        );
    } else {
        $ph   = implode(',', array_fill(0, count($claimed_codes), '?'));
        $pool = $db->prepare(
            "SELECT codice, nome, pvplus_base FROM pvplus_missions
             WHERE tipo = 'ONETIME' AND attiva = 1 AND codice NOT IN ($ph)
             ORDER BY FIELD(categoria,'ONBOARDING','AUDIT','CRESCITA','MASLOW','REFERRAL','STREAK') ASC
             LIMIT 2"
        );
        $pool->execute($claimed_codes);
    }
    foreach ($pool->fetchAll() as $row) {
        $to_assign[] = ['codice' => $row['codice'], 'nome' => $row['nome'], 'reward' => $row['pvplus_base']];
    }

    $ins = $db->prepare(
        'INSERT INTO daily_missions (user_id, data_assegnazione, missione_codice, missione_nome, pvplus_reward)
         VALUES (?,?,?,?,?)'
    );
    foreach ($to_assign as $m) {
        $ins->execute([$uid, $today, $m['codice'], $m['nome'], $m['reward']]);
    }
    $stmt->execute([$uid, $today]);
    $missions_today = $stmt->fetchAll();
}

// ─── Ruota della Vita ────────────────────────────────────────────────────────
$lw_stmt = $db->prepare('SELECT * FROM life_wheel_scores WHERE user_id = ? ORDER BY data_rilevazione DESC LIMIT 1');
$lw_stmt->execute([$uid]);
$lw = $lw_stmt->fetch() ?: [
    'salute'      => 5, 'famiglia'     => 5, 'lavoro'       => 5, 'denaro'  => 5,
    'crescita'    => 5, 'community'    => 5, 'divertimento' => 5, 'ambiente' => 5,
    'data_rilevazione' => null, 'media' => 5.00,
];

// ─── Streak ──────────────────────────────────────────────────────────────────
$sc = $db->prepare('SELECT streak_days, last_active_date FROM user_scores WHERE user_id = ?');
$sc->execute([$uid]);
$score_row = $sc->fetch() ?: ['streak_days' => 0, 'last_active_date' => null];
$streak    = (int)$score_row['streak_days'];

$last_active = $score_row['last_active_date'];
if ($last_active !== $today) {
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $streak    = ($last_active === $yesterday) ? $streak + 1 : 1;
    $db->prepare(
        'INSERT INTO user_scores (user_id, streak_days, last_active_date, engagement_score)
         VALUES (?,?,?,LEAST(?,100))
         ON DUPLICATE KEY UPDATE streak_days=VALUES(streak_days), last_active_date=VALUES(last_active_date),
           engagement_score=LEAST(engagement_score+1,100)'
    )->execute([$uid, $streak, $today, $streak]);
}

// Dati per la ruota (JSON per Chart.js)
$lw_labels  = ['Salute','Famiglia','Lavoro','Denaro','Crescita','Community','Divertimento','Ambiente'];
$lw_values  = [(int)$lw['salute'],(int)$lw['famiglia'],(int)$lw['lavoro'],(int)$lw['denaro'],
               (int)$lw['crescita'],(int)$lw['community'],(int)$lw['divertimento'],(int)$lw['ambiente']];

$page_title = 'Cockpit IMPARA — 81plus.net';
$page_id    = 'cockpit81';
$page_css   = 'dashboard'; // usa dashboard.css
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<!-- Chart.js per radar Ruota della Vita -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
/* ── Cockpit IMPARA ──────────────────────────────────────────────────────── */
.cockpit-hero {
  background: linear-gradient(135deg, #0F0F18 0%, #12101E 100%);
  border-bottom: 1px solid rgba(255,255,255,0.06);
  padding: 2rem;
}
.cockpit-hero-inner {
  max-width: 1200px; margin: 0 auto;
  display: flex; align-items: center; justify-content: space-between;
  gap: 1.5rem; flex-wrap: wrap;
}
.cockpit-user-info { display: flex; flex-direction: column; gap: 0.4rem; }
.cockpit-user-info h1 {
  font-size: 1.8rem; letter-spacing: 0.06em; color: var(--white);
  font-family: var(--font-display);
}
.cockpit-user-info .sic-tag {
  font-family: var(--font-mono); font-size: 0.78rem;
  color: var(--silver); opacity: 0.7;
}
.cockpit-stats {
  display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap;
}
.cockpit-stat {
  text-align: center;
  background: rgba(255,255,255,0.04);
  border: 1px solid rgba(255,255,255,0.07);
  border-radius: 10px; padding: 0.75rem 1.25rem;
  min-width: 90px;
}
.cockpit-stat .val {
  font-family: var(--font-display); font-size: 1.6rem;
  color: var(--orange);
}
.cockpit-stat .lbl {
  font-size: 0.7rem; color: var(--silver); text-transform: uppercase; letter-spacing: 0.08em;
}
.maslow-badge {
  display: inline-flex; align-items: center; gap: 0.4rem;
  border: 1px solid; border-radius: 20px;
  padding: 0.3rem 0.9rem; font-size: 0.78rem;
  font-family: var(--font-display); letter-spacing: 0.1em;
}

/* ── Layout principale ───────────────────────────────────────────────────── */
.cockpit-main {
  max-width: 1200px; margin: 2rem auto; padding: 0 1.5rem;
  display: grid;
  grid-template-columns: 1fr 1.1fr 0.9fr;
  gap: 1.5rem;
}
@media (max-width: 1024px) { .cockpit-main { grid-template-columns: 1fr 1fr; } }
@media (max-width: 640px)  { .cockpit-main { grid-template-columns: 1fr; } }

/* ── Missioni ─────────────────────────────────────────────────────────────── */
.cockpit-section-title {
  font-family: var(--font-display); font-size: 1.1rem;
  color: var(--silver); letter-spacing: 0.1em;
  margin-bottom: 1rem; text-transform: uppercase;
  display: flex; align-items: center; gap: 0.5rem;
}
.mission-card {
  background: var(--card);
  border: 1px solid rgba(255,255,255,0.06);
  border-radius: 10px; padding: 1rem 1.25rem;
  margin-bottom: 0.75rem;
  display: flex; flex-direction: column; gap: 0.6rem;
  transition: border-color 0.2s;
}
.mission-card.done {
  opacity: 0.5;
  border-color: var(--green81);
}
.mission-card:not(.done):hover { border-color: rgba(232,80,26,0.4); }
.mission-card .m-name {
  font-size: 0.95rem; font-weight: 600; color: var(--white);
}
.mission-card .m-reward {
  font-family: var(--font-mono); font-size: 0.8rem; color: var(--gold);
}
.mission-card .m-status {
  font-size: 0.75rem; color: var(--green81);
  display: none;
}
.mission-card.done .m-status { display: block; }
.mission-card .btn-complete {
  align-self: flex-start;
  background: var(--orange); color: var(--white);
  border: none; border-radius: 6px;
  padding: 0.4rem 1rem;
  font-family: var(--font-display); font-size: 0.9rem;
  cursor: pointer; transition: background 0.2s;
}
.mission-card .btn-complete:hover { background: #c2400f; }
.mission-card.done .btn-complete { display: none; }

/* PV+ toast */
.pvplus-toast {
  position: fixed; bottom: 2rem; right: 2rem;
  background: var(--card); border: 1px solid var(--gold);
  border-radius: 10px; padding: 1rem 1.5rem;
  display: none; flex-direction: column; gap: 0.25rem;
  animation: slideIn 0.3s ease; z-index: 1000;
}
.pvplus-toast.show { display: flex; }
@keyframes slideIn {
  from { transform: translateY(20px); opacity: 0; }
  to   { transform: translateY(0);    opacity: 1; }
}
.pvplus-toast .toast-title {
  font-family: var(--font-display); font-size: 1.1rem; color: var(--gold);
}
.pvplus-toast .toast-val {
  font-family: var(--font-mono); font-size: 1.4rem; color: var(--white);
}

/* ── Ruota della Vita ─────────────────────────────────────────────────────── */
.life-wheel-wrap {
  background: var(--card);
  border: 1px solid rgba(255,255,255,0.06);
  border-radius: 12px; padding: 1.5rem;
}
.chart-container {
  position: relative; width: 100%; max-width: 340px;
  margin: 0 auto; aspect-ratio: 1;
}
.lw-footer {
  margin-top: 1rem; display: flex; align-items: center; justify-content: space-between;
  flex-wrap: wrap; gap: 0.5rem;
}
.lw-media { font-family: var(--font-mono); font-size: 0.9rem; color: var(--silver); }
.lw-date  { font-size: 0.75rem; color: var(--silver); opacity: 0.6; }
.btn-edit-wheel {
  background: transparent; border: 1px solid var(--orange);
  color: var(--orange); border-radius: 6px;
  padding: 0.35rem 0.9rem;
  font-family: var(--font-display); font-size: 0.85rem;
  cursor: pointer; transition: all 0.2s;
}
.btn-edit-wheel:hover { background: var(--orange); color: var(--white); }

/* Sliders edit modal */
.wheel-edit-panel {
  display: none; margin-top: 1rem;
  border-top: 1px solid rgba(255,255,255,0.06);
  padding-top: 1rem;
}
.wheel-edit-panel.open { display: block; }
.dim-row {
  display: flex; align-items: center; gap: 0.75rem;
  margin-bottom: 0.6rem;
}
.dim-row label {
  font-size: 0.8rem; color: var(--silver);
  width: 110px; flex-shrink: 0;
}
.dim-row input[type=range] {
  flex: 1; accent-color: var(--orange);
}
.dim-row .dim-val {
  font-family: var(--font-mono); font-size: 0.85rem;
  color: var(--gold); width: 20px; text-align: right;
}
.btn-save-wheel {
  margin-top: 0.75rem; width: 100%;
  background: var(--orange); color: var(--white);
  border: none; border-radius: 6px; padding: 0.6rem 1rem;
  font-family: var(--font-display); font-size: 1rem; cursor: pointer;
  transition: background 0.2s;
}
.btn-save-wheel:hover { background: #c2400f; }

/* ── Piramide Maslow ──────────────────────────────────────────────────────── */
.maslow-wrap {
  background: var(--card);
  border: 1px solid rgba(255,255,255,0.06);
  border-radius: 12px; padding: 1.5rem;
}
.maslow-pyramid {
  display: flex; flex-direction: column-reverse;
  align-items: center; gap: 4px;
  margin: 1rem 0;
}
.maslow-level {
  width: 100%; border-radius: 4px;
  display: flex; align-items: center; justify-content: center;
  padding: 0.55rem 0.5rem;
  font-family: var(--font-display); font-size: 0.85rem;
  letter-spacing: 0.08em; cursor: default;
  transition: transform 0.15s;
  opacity: 0.4;
  position: relative;
}
.maslow-level.active { opacity: 1; transform: scale(1.03); }
.maslow-level-1 { max-width: 100%; background: rgba(107,114,128,0.3); border: 1px solid #6B7280; color: #B9BCC2; }
.maslow-level-2 { max-width: 85%;  background: rgba(205,127,50,0.25); border: 1px solid #CD7F32; color: #CD7F32; }
.maslow-level-3 { max-width: 70%;  background: rgba(185,188,194,0.2); border: 1px solid #B9BCC2; color: #B9BCC2; }
.maslow-level-4 { max-width: 55%;  background: rgba(255,210,74,0.2);  border: 1px solid #FFD24A; color: #FFD24A; }
.maslow-level-5 { max-width: 40%;  background: rgba(0,217,255,0.15);  border: 1px solid #00D9FF; color: #00D9FF; }
.maslow-level .lvl-num {
  position: absolute; left: 0.5rem;
  font-size: 0.65rem; opacity: 0.6;
}
.maslow-progress-row {
  margin-top: 1rem; border-top: 1px solid rgba(255,255,255,0.06);
  padding-top: 1rem;
}
.maslow-progress-label {
  display: flex; justify-content: space-between; align-items: center;
  margin-bottom: 0.5rem;
}
.maslow-progress-label .current-lv {
  font-family: var(--font-display); font-size: 0.9rem;
}
.maslow-progress-label .next-lv {
  font-size: 0.75rem; color: var(--silver);
}
.progress-bar-bg {
  background: rgba(255,255,255,0.07); border-radius: 10px; height: 8px;
  overflow: hidden;
}
.progress-bar-fill {
  height: 100%; border-radius: 10px;
  background: linear-gradient(90deg, var(--orange), var(--gold));
  transition: width 0.6s ease;
}
.maslow-pts {
  margin-top: 0.5rem;
  font-family: var(--font-mono); font-size: 0.75rem; color: var(--silver);
}

/* ── Score row ────────────────────────────────────────────────────────────── */
.score-row {
  max-width: 1200px; margin: 0 auto 2.5rem; padding: 0 1.5rem;
  display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;
}
@media (max-width: 640px) { .score-row { grid-template-columns: 1fr 1fr; } }
.score-card {
  background: var(--card);
  border: 1px solid rgba(255,255,255,0.06);
  border-radius: 10px; padding: 1rem;
  text-align: center;
}
.score-card .sc-label {
  font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em;
  color: var(--silver); margin-bottom: 0.4rem;
}
.score-card .sc-bar-bg {
  background: rgba(255,255,255,0.06); border-radius: 6px; height: 6px;
  margin-bottom: 0.4rem; overflow: hidden;
}
.score-card .sc-bar-fill { height: 100%; border-radius: 6px; }
.score-card .sc-val {
  font-family: var(--font-mono); font-size: 1rem; color: var(--white);
}

/* ── Manifesto ────────────────────────────────────────────────────────────── */
.cockpit-manifesto {
  text-align: center; padding: 2.5rem 1.5rem;
  border-top: 1px solid rgba(255,255,255,0.04);
  max-width: 700px; margin: 0 auto;
}
.cockpit-manifesto .delta-phrase {
  font-family: var(--font-display); font-size: 1.5rem;
  color: var(--silver); line-height: 1.4;
  opacity: 0.5;
}
</style>

<!-- ══ HERO COCKPIT ══════════════════════════════════════════════════════════ -->
<div class="cockpit-hero">
  <div class="cockpit-hero-inner">
    <div class="cockpit-user-info">
      <h1>COCKPIT IMPARA</h1>
      <div>
        <span class="maslow-badge" style="color:#<?= e($ml_current['hex']) ?>; border-color:#<?= e($ml_current['hex']) ?>;">
          &#9650; <?= e($ml_current['nome']) ?>
        </span>
      </div>
      <div class="sic-tag"><?= e($user['sic_id']) ?> &middot; <?= e($user['nome']) ?> <?= e($user['cognome']) ?></div>
    </div>

    <div class="cockpit-stats">
      <div class="cockpit-stat">
        <div class="val"><?= number_format((float)$wallet['pvplus_balance'], 0, ',', '.') ?></div>
        <div class="lbl">PV+</div>
      </div>
      <div class="cockpit-stat">
        <div class="val"><?= $streak ?></div>
        <div class="lbl">Streak</div>
      </div>
      <div class="cockpit-stat">
        <div class="val"><?= $maslow_level ?>/5</div>
        <div class="lbl">Livello</div>
      </div>
      <div class="cockpit-stat">
        <div class="val"><?= number_format((float)$lw['media'], 1) ?></div>
        <div class="lbl">Ruota</div>
      </div>
    </div>
  </div>
</div>

<!-- ══ MAIN 3-COLUMN ════════════════════════════════════════════════════════ -->
<div class="cockpit-main">

  <!-- COLONNA 1: MISSIONI DEL GIORNO -->
  <div>
    <div class="cockpit-section-title">&#127919; Missioni del Giorno</div>

    <?php foreach ($missions_today as $m): ?>
    <?php $done = $m['status'] === 'COMPLETED'; ?>
    <div class="mission-card <?= $done ? 'done' : '' ?>" id="mc-<?= (int)$m['id'] ?>">
      <div class="m-name"><?= e($m['missione_nome']) ?></div>
      <div class="m-reward">+<?= number_format((float)$m['pvplus_reward'], 0) ?> PV+</div>
      <div class="m-status">&#10003; Completata</div>
      <?php if (!$done): ?>
      <button class="btn-complete" onclick="completeMission(<?= (int)$m['id'] ?>)">
        Completa
      </button>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <?php if (empty($missions_today)): ?>
    <p style="color:var(--silver);font-size:0.85rem;">Nessuna missione disponibile oggi.</p>
    <?php endif; ?>

    <div style="margin-top:1.5rem;padding:1rem;background:rgba(255,255,255,0.03);border-radius:8px;border:1px solid rgba(255,255,255,0.06);">
      <div style="font-size:0.75rem;color:var(--silver);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">PV+ Career Total</div>
      <div style="font-family:var(--font-mono);font-size:1.1rem;color:var(--gold);">
        <?= number_format($career_total, 2, ',', '.') ?> PV+
      </div>
      <div style="font-size:0.72rem;color:var(--silver);margin-top:0.25rem;">
        Usati per il calcolo del tuo livello Maslow
      </div>
    </div>
  </div>

  <!-- COLONNA 2: RUOTA DELLA VITA -->
  <div>
    <div class="cockpit-section-title">&#9680; Ruota della Vita</div>
    <div class="life-wheel-wrap">
      <div class="chart-container">
        <canvas id="lifeWheelChart"></canvas>
      </div>
      <div class="lw-footer">
        <div>
          <div class="lw-media">Media: <?= number_format((float)$lw['media'], 1) ?>/10</div>
          <?php if ($lw['data_rilevazione']): ?>
          <div class="lw-date">Ultima rilevazione: <?= e($lw['data_rilevazione']) ?></div>
          <?php else: ?>
          <div class="lw-date" style="color:var(--orange);">Non ancora compilata &rarr; +300 PV+</div>
          <?php endif; ?>
        </div>
        <button class="btn-edit-wheel" onclick="toggleWheelEdit()">Aggiorna</button>
      </div>

      <!-- Pannello sliders edit -->
      <div class="wheel-edit-panel" id="wheelEditPanel">
        <?php
        $dims = [
            'salute'       => 'Salute &amp; Benessere',
            'famiglia'     => 'Famiglia',
            'lavoro'       => 'Lavoro &amp; Carriera',
            'denaro'       => 'Finanze',
            'crescita'     => 'Crescita Personale',
            'community'    => 'Community 81+',
            'divertimento' => 'Divertimento',
            'ambiente'     => 'Ambiente &amp; Casa',
        ];
        foreach ($dims as $key => $label): ?>
        <div class="dim-row">
          <label><?= $label ?></label>
          <input type="range" min="1" max="10" value="<?= (int)$lw[$key] ?>"
                 id="sl-<?= $key ?>"
                 oninput="document.getElementById('sv-<?= $key ?>').textContent=this.value;updateChart()">
          <span class="dim-val" id="sv-<?= $key ?>"><?= (int)$lw[$key] ?></span>
        </div>
        <?php endforeach; ?>
        <button class="btn-save-wheel" onclick="saveWheel()">Salva Ruota (+PV+)</button>
      </div>
    </div>
  </div>

  <!-- COLONNA 3: PIRAMIDE DI MASLOW 81+ -->
  <div>
    <div class="cockpit-section-title">&#9650; Piramide 81+</div>
    <div class="maslow-wrap">
      <div class="maslow-pyramid">
        <?php foreach ($maslow_map as $lvl => $info): ?>
        <div class="maslow-level maslow-level-<?= $lvl ?> <?= $lvl <= $maslow_level ? 'active' : '' ?>">
          <span class="lvl-num"><?= $lvl ?></span>
          <?= e($info['nome']) ?>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Descrizione livello corrente -->
      <div style="text-align:center;margin:0.5rem 0 1rem;">
        <span style="font-size:0.8rem;color:var(--silver);">
          <?= e($ml_current['desc']) ?>
        </span>
      </div>

      <!-- Progress verso prossimo livello -->
      <div class="maslow-progress-row">
        <div class="maslow-progress-label">
          <span class="current-lv" style="color:#<?= e($ml_current['hex']) ?>;">
            Livello <?= $maslow_level ?> — <?= e($ml_current['nome']) ?>
          </span>
          <?php if ($ml_next): ?>
          <span class="next-lv">→ <?= e($ml_next['nome']) ?></span>
          <?php endif; ?>
        </div>
        <div class="progress-bar-bg">
          <div class="progress-bar-fill" style="width:<?= $ml_progress ?>%"></div>
        </div>
        <div class="maslow-pts">
          <?php if ($ml_next): ?>
            <?= number_format($career_total, 0, ',', '.') ?> / <?= number_format((float)$ml_next['soglia'], 0, ',', '.') ?> PV+
          <?php else: ?>
            Livello massimo raggiunto
          <?php endif; ?>
        </div>
      </div>

      <!-- Mappa livelli -->
      <div style="margin-top:1rem;border-top:1px solid rgba(255,255,255,0.06);padding-top:1rem;">
        <?php foreach ($maslow_map as $lvl => $info): ?>
        <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:0.4rem;opacity:<?= $lvl <= $maslow_level ? '1' : '0.3' ?>">
          <div style="width:10px;height:10px;border-radius:50%;background:#<?= e($info['hex']) ?>;flex-shrink:0;"></div>
          <span style="font-size:0.75rem;color:var(--silver);">
            <strong style="color:#<?= e($info['hex']) ?>;"><?= $lvl ?>.</strong>
            <?= e($info['nome']) ?> — <?= e($info['desc']) ?>
          </span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</div><!-- /cockpit-main -->

<!-- ══ SCORE ROW ════════════════════════════════════════════════════════════ -->
<?php
$score_data = $db->prepare('SELECT * FROM user_scores WHERE user_id = ?');
$score_data->execute([$uid]);
$us = $score_data->fetch() ?: ['compliance_score'=>0,'engagement_score'=>0,'network_score'=>0,'growth_score'=>0,'total_score'=>0];
$score_items = [
    ['label'=>'Compliance', 'val'=>$us['compliance_score'], 'color'=>'#3FBF6B'],
    ['label'=>'Engagement', 'val'=>$us['engagement_score'], 'color'=>'#E8501A'],
    ['label'=>'Network',    'val'=>$us['network_score'],    'color'=>'#00D9FF'],
    ['label'=>'Crescita',   'val'=>$us['growth_score'],     'color'=>'#FFD24A'],
];
?>
<div class="score-row">
  <?php foreach ($score_items as $si): ?>
  <div class="score-card">
    <div class="sc-label"><?= e($si['label']) ?></div>
    <div class="sc-bar-bg">
      <div class="sc-bar-fill" style="width:<?= (int)$si['val'] ?>%;background:<?= e($si['color']) ?>;"></div>
    </div>
    <div class="sc-val"><?= (int)$si['val'] ?>/100</div>
  </div>
  <?php endforeach; ?>
</div>

<!-- ══ MANIFESTO ════════════════════════════════════════════════════════════ -->
<div class="cockpit-manifesto">
  <p class="delta-phrase">
    Il Delta non è dove il fiume finisce.<br>
    È dove impara a diventare mare.
  </p>
</div>

<!-- ══ PV+ TOAST ════════════════════════════════════════════════════════════ -->
<div class="pvplus-toast" id="pvplusToast">
  <div class="toast-title">&#10024; PV+ guadagnati</div>
  <div class="toast-val" id="toastVal">+0</div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
// ── Chart.js Radar ───────────────────────────────────────────────────────────
const lwLabels = <?= json_encode($lw_labels) ?>;
const lwValues = <?= json_encode($lw_values) ?>;

const radarData = {
  labels: lwLabels,
  datasets: [{
    data: lwValues,
    backgroundColor: 'rgba(232,80,26,0.15)',
    borderColor:     '#E8501A',
    pointBackgroundColor: '#FFD24A',
    pointRadius: 4,
    borderWidth: 2,
  }]
};
const radarOpts = {
  responsive: true, maintainAspectRatio: true,
  scales: {
    r: {
      min: 0, max: 10, ticks: { stepSize: 2, display: false },
      grid:       { color: 'rgba(255,255,255,0.07)' },
      angleLines: { color: 'rgba(255,255,255,0.07)' },
      pointLabels: {
        color: '#B9BCC2', font: { family: "'DM Sans', sans-serif", size: 11 }
      }
    }
  },
  plugins: { legend: { display: false } }
};

const ctx   = document.getElementById('lifeWheelChart').getContext('2d');
const chart = new Chart(ctx, { type: 'radar', data: radarData, options: radarOpts });

function updateChart() {
  const dims = ['salute','famiglia','lavoro','denaro','crescita','community','divertimento','ambiente'];
  chart.data.datasets[0].data = dims.map(d => parseInt(document.getElementById('sl-'+d).value));
  chart.update();
}

function toggleWheelEdit() {
  const panel = document.getElementById('wheelEditPanel');
  panel.classList.toggle('open');
}

// ── Completa missione ────────────────────────────────────────────────────────
function completeMission(missionId) {
  const csrf = document.querySelector('meta[name="csrf-token"]').content;
  fetch('<?= BASE_URL ?>/api/daily-mission-today.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrf },
    body: JSON.stringify({ mission_id: missionId })
  })
  .then(r => r.json())
  .then(data => {
    if (data.ok) {
      const card = document.getElementById('mc-' + missionId);
      card.classList.add('done');
      showToast('+' + parseFloat(data.pvplus).toFixed(0) + ' PV+');
    }
  })
  .catch(() => {});
}

// ── Salva Ruota ──────────────────────────────────────────────────────────────
function saveWheel() {
  const dims = ['salute','famiglia','lavoro','denaro','crescita','community','divertimento','ambiente'];
  const payload = {};
  dims.forEach(d => payload[d] = parseInt(document.getElementById('sl-'+d).value));

  const csrf = document.querySelector('meta[name="csrf-token"]').content;
  fetch('<?= BASE_URL ?>/api/life-wheel-save.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrf },
    body: JSON.stringify(payload)
  })
  .then(r => r.json())
  .then(data => {
    if (data.ok) {
      if (!data.already) showToast('+' + parseFloat(data.pvplus).toFixed(0) + ' PV+ (Ruota)');
      document.getElementById('wheelEditPanel').classList.remove('open');
    }
  })
  .catch(() => {});
}

// ── Toast ────────────────────────────────────────────────────────────────────
function showToast(msg) {
  const toast = document.getElementById('pvplusToast');
  document.getElementById('toastVal').textContent = msg;
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 3500);
}
</script>
