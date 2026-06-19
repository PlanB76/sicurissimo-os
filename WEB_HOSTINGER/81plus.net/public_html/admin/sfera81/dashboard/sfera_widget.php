<?php
/**
 * SFERA81+ Dashboard Widget
 * Include in any dashboard page after requiring _bootstrap.php
 * Usage: require_once __DIR__ . '/../sfera_widget.php';
 *        sfera81_widget($user_id);
 */

function sfera81_widget(int $user_id, string $base_url = '/admin/sfera81'): void {
    if (!$user_id) return;

    $pdo = sfera_pdo();

    // Fetch profile
    $user = sfera_get_user($user_id);
    if (!$user) return;

    $status    = $user['status'] ?? 'MEMBER';
    $balance   = (int)($user['pvplus_balance'] ?? 0);
    $streak    = (int)($user['streak_days'] ?? 0);
    $sic_id    = htmlspecialchars($user['sic_id'] ?? '—');

    // Cap del giorno
    $cap = sfera_get_status_cap($status);
    $today = date('Y-m-d');

    // PV+ già guadagnati oggi (esclude DAILY_ACCESS e BOOSTER81+)
    $stDay = $pdo->prepare(
        'SELECT COALESCE(SUM(reward_amount),0) FROM sfera_reward_log
         WHERE user_id=? AND DATE(created_at)=? AND reward_type NOT IN ("DAILY_ACCESS","BOOSTER81+")'
    );
    $stDay->execute([$user_id, $today]);
    $earned_today = (int)$stDay->fetchColumn();

    // Livello escalation attivo
    $stEsc = $pdo->prepare(
        'SELECT e.level_number, e.level_name, e.level_code
         FROM sfera_escalation_levels e
         LEFT JOIN sfera_user_missions um ON um.user_id=? AND um.status="completed"
         LEFT JOIN sfera_missions m ON m.id=um.mission_id AND m.escalation_level=e.level_number
         GROUP BY e.id
         ORDER BY e.level_number DESC LIMIT 1'
    );
    $stEsc->execute([$user_id]);
    $esc = $stEsc->fetch(PDO::FETCH_ASSOC) ?: ['level_number'=>1,'level_name'=>'IDENTITÀ81+','level_code'=>'L1'];

    // Prossima missione aperta
    $stMission = $pdo->prepare(
        'SELECT m.mission_name, m.pvplus_reward
         FROM sfera_missions m
         LEFT JOIN sfera_user_missions um ON um.mission_id=m.id AND um.user_id=?
         WHERE (um.id IS NULL OR um.status="open")
           AND m.escalation_level <= ?
         ORDER BY m.pvplus_reward DESC LIMIT 1'
    );
    $stMission->execute([$user_id, (int)$esc['level_number'] + 1]);
    $next = $stMission->fetch(PDO::FETCH_ASSOC);

    // Promo attiva?
    $stPromo = $pdo->prepare(
        'SELECT promo_name, reward_type FROM sfera_promos WHERE status="active" AND NOW() BETWEEN start_at AND end_at LIMIT 1'
    );
    $stPromo->execute();
    $promo = $stPromo->fetch(PDO::FETCH_ASSOC);

    // Lifewheel cap
    $lw_cap = sfera_get_lifewheel_cap($status);

    $cap_pct = $cap > 0 ? min(100, round($earned_today / $cap * 100)) : 0;
    ?>
    <div class="sfera81-widget" style="
        background: linear-gradient(135deg, #0A1428 0%, #070D1A 100%);
        border: 1px solid rgba(0,217,255,0.2);
        border-radius: 16px;
        padding: 20px;
        font-family: 'Segoe UI', system-ui, sans-serif;
        color: #E8EDF5;
        max-width: 480px;
        box-shadow: 0 4px 24px rgba(0,217,255,0.06);
    ">
        <!-- Header -->
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
            <div>
                <div style="font-size:0.7rem; letter-spacing:0.12em; color:#00D9FF; text-transform:uppercase; margin-bottom:2px;">SFERA81+ OS</div>
                <div style="font-size:0.8rem; color:#8090A8;"><?= $sic_id ?></div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:1.6rem; font-weight:700; color:#FFD24A; line-height:1;"><?= number_format($balance) ?></div>
                <div style="font-size:0.65rem; color:#8090A8; letter-spacing:0.08em;">PV+</div>
            </div>
        </div>

        <!-- Status + Streak -->
        <div style="display:flex; gap:10px; margin-bottom:16px;">
            <div style="flex:1; background:rgba(0,217,255,0.06); border:1px solid rgba(0,217,255,0.15); border-radius:10px; padding:10px; text-align:center;">
                <div style="font-size:0.65rem; color:#8090A8; margin-bottom:2px;">STATUS</div>
                <div style="font-size:0.85rem; font-weight:700; color:#00D9FF;"><?= htmlspecialchars($status) ?></div>
            </div>
            <div style="flex:1; background:rgba(255,210,74,0.06); border:1px solid rgba(255,210,74,0.15); border-radius:10px; padding:10px; text-align:center;">
                <div style="font-size:0.65rem; color:#8090A8; margin-bottom:2px;">STREAK</div>
                <div style="font-size:0.85rem; font-weight:700; color:#FFD24A;"><?= $streak ?> gg</div>
            </div>
            <div style="flex:1; background:rgba(155,107,255,0.06); border:1px solid rgba(155,107,255,0.15); border-radius:10px; padding:10px; text-align:center;">
                <div style="font-size:0.65rem; color:#8090A8; margin-bottom:2px;">LW CAP</div>
                <div style="font-size:0.85rem; font-weight:700; color:#9B6BFF;"><?= $lw_cap ?>/10</div>
            </div>
        </div>

        <!-- Cap giornaliero -->
        <div style="margin-bottom:14px;">
            <div style="display:flex; justify-content:space-between; font-size:0.72rem; color:#8090A8; margin-bottom:6px;">
                <span>PV+ oggi</span>
                <span><?= $earned_today ?> / <?= $cap ?></span>
            </div>
            <div style="background:rgba(255,255,255,0.06); border-radius:20px; height:8px; overflow:hidden;">
                <div style="height:100%; width:<?= $cap_pct ?>%; background:linear-gradient(90deg,#00E676,#00D9FF); border-radius:20px; transition:width 0.5s;"></div>
            </div>
        </div>

        <!-- Escalation level -->
        <div style="background:rgba(255,255,255,0.03); border-radius:10px; padding:10px 12px; margin-bottom:12px; display:flex; align-items:center; gap:10px;">
            <div style="font-size:1.1rem;">📊</div>
            <div>
                <div style="font-size:0.65rem; color:#8090A8;">ESCALATION81+ — livello attivo</div>
                <div style="font-size:0.82rem; font-weight:600; color:#E8EDF5;"><?= htmlspecialchars($esc['level_code']) ?> — <?= htmlspecialchars($esc['level_name']) ?></div>
            </div>
        </div>

        <!-- Prossima missione -->
        <?php if ($next): ?>
        <div style="background:rgba(0,230,118,0.04); border:1px solid rgba(0,230,118,0.15); border-radius:10px; padding:10px 12px; margin-bottom:12px; display:flex; align-items:center; gap:10px;">
            <div style="font-size:1.1rem;">🎯</div>
            <div style="flex:1;">
                <div style="font-size:0.65rem; color:#8090A8;">PROSSIMA MISSIONE</div>
                <div style="font-size:0.8rem; color:#E8EDF5;"><?= htmlspecialchars($next['mission_name']) ?></div>
            </div>
            <div style="font-size:0.75rem; font-weight:700; color:#00E676;">+<?= (int)$next['pvplus_reward'] ?> PV+</div>
        </div>
        <?php endif; ?>

        <!-- Promo attiva -->
        <?php if ($promo): ?>
        <div style="background:rgba(255,210,74,0.06); border:1px solid rgba(255,210,74,0.3); border-radius:10px; padding:10px 12px; margin-bottom:12px; display:flex; align-items:center; gap:10px;">
            <div style="font-size:1.1rem;">⚡</div>
            <div>
                <div style="font-size:0.65rem; color:#8090A8;">PROMO ATTIVA</div>
                <div style="font-size:0.8rem; color:#FFD24A; font-weight:600;"><?= htmlspecialchars($promo['promo_name']) ?></div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Link iframe -->
        <div style="display:flex; gap:8px; margin-top:4px;">
            <a href="<?= htmlspecialchars($base_url) ?>/iframe/escalation81/index.html?level=<?= (int)$esc['level_number'] ?>"
               target="_blank"
               style="flex:1; text-align:center; background:rgba(0,217,255,0.08); border:1px solid rgba(0,217,255,0.2); color:#00D9FF; text-decoration:none; padding:8px; border-radius:8px; font-size:0.72rem; letter-spacing:0.05em;">
                ESCALATION81+
            </a>
            <a href="<?= htmlspecialchars($base_url) ?>/iframe/lifewheel81/index.html?status=<?= urlencode($status) ?>"
               target="_blank"
               style="flex:1; text-align:center; background:rgba(155,107,255,0.08); border:1px solid rgba(155,107,255,0.2); color:#9B6BFF; text-decoration:none; padding:8px; border-radius:8px; font-size:0.72rem; letter-spacing:0.05em;">
                LIFEWHEEL81+
            </a>
        </div>
    </div>
    <?php
}
