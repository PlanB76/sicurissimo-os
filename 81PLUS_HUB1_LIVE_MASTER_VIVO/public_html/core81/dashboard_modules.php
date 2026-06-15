<?php
// core81/dashboard_modules.php — Moduli dashboard per ruolo
declare(strict_types=1);

class DashboardModules {

    private static array $MODULES = [
        // Tutti i ruoli
        'sic_id'         => ['label'=>'SIC-ID',            'icon'=>'🆔', 'url'=>null,                    'ruoli'=>'all',         'preview'=>false, 'req'=>null],
        'referral'       => ['label'=>'ReferralLink81+',   'icon'=>'🔗', 'url'=>'#referral',             'ruoli'=>'all',         'preview'=>false, 'req'=>null],
        'wallet'         => ['label'=>'Wallet81+',         'icon'=>'💼', 'url'=>'#wallet',               'ruoli'=>'all',         'preview'=>false, 'req'=>null],
        'membership'     => ['label'=>'Membership',        'icon'=>'🏆', 'url'=>'/membership.php',        'ruoli'=>'all',         'preview'=>false, 'req'=>null],
        'paygate'        => ['label'=>'PayGate81+',        'icon'=>'💳', 'url'=>'/paygate81.php',         'ruoli'=>'all',         'preview'=>false, 'req'=>null],
        'audit'          => ['label'=>'Audit 81/08',       'icon'=>'🔍', 'url'=>'/audit.php',             'ruoli'=>'all',         'preview'=>false, 'req'=>null],
        'pvplus_booster' => ['label'=>'PV+ Booster81+',   'icon'=>'⚡', 'url'=>'/gamification.php',      'ruoli'=>'all',         'preview'=>false, 'req'=>null],
        'preventivo'     => ['label'=>'DOC81+ Builder',   'icon'=>'📄', 'url'=>'/preventivo.php',        'ruoli'=>'all',         'preview'=>true,  'req'=>'BASIC+'],
        'academy'        => ['label'=>'Academy 81+',      'icon'=>'🎓', 'url'=>'/academy81.php',          'ruoli'=>'all',         'preview'=>true,  'req'=>'BASIC+'],
        'scadenziario'   => ['label'=>'Scadenziario81+',  'icon'=>'📅', 'url'=>'/scadenze.php',           'ruoli'=>'all',         'preview'=>true,  'req'=>'BASIC+'],
        'pix81'          => ['label'=>'PIX81+',           'icon'=>'🗺️', 'url'=>'/pix81.php',             'ruoli'=>'all',         'preview'=>true,  'req'=>null],
        'green81'        => ['label'=>'Green81+',         'icon'=>'🌳', 'url'=>'/green81.php',            'ruoli'=>'all',         'preview'=>true,  'req'=>null],
        // Networker+
        'scout81'        => ['label'=>'SCOUT81+',         'icon'=>'🎯', 'url'=>'/scout81.php',            'ruoli'=>'networker+',  'preview'=>true,  'req'=>'PRO+'],
        'plp81'          => ['label'=>'PLP81+ Pack',      'icon'=>'📦', 'url'=>'/network81.php#plp',      'ruoli'=>'networker+',  'preview'=>true,  'req'=>'PRO+'],
        'network81'      => ['label'=>'NETWORK81+',       'icon'=>'🕸️', 'url'=>'/network81.php',          'ruoli'=>'networker+',  'preview'=>true,  'req'=>'PRO+'],
        'pipeline3d'     => ['label'=>'Pipeline3D81+',   'icon'=>'📊', 'url'=>'/ecosistema3d.php',        'ruoli'=>'networker+',  'preview'=>true,  'req'=>'PRO+'],
        'compensi'       => ['label'=>'Piano Compensi81+','icon'=>'💹', 'url'=>'/cervello3d.php',          'ruoli'=>'networker+',  'preview'=>true,  'req'=>'PRO+'],
        'marketing81'    => ['label'=>'Piano Marketing81+','icon'=>'📣', 'url'=>'/network81.php#marketing','ruoli'=>'networker+', 'preview'=>true,  'req'=>'PRO+'],
        // Elite+
        'club81'         => ['label'=>'Club81+',          'icon'=>'👑', 'url'=>'/club81.php',             'ruoli'=>'elite+',      'preview'=>true,  'req'=>'ELITE+'],
        'franchising'    => ['label'=>'Franchising81+',   'icon'=>'🏢', 'url'=>'/franchising.php',        'ruoli'=>'elite+',      'preview'=>true,  'req'=>'ELITE+'],
        'territory'      => ['label'=>'TerritoryMap81+',  'icon'=>'🗺️', 'url'=>'/scout81.php#territory',  'ruoli'=>'elite+',      'preview'=>true,  'req'=>'ELITE+'],
        'point81'        => ['label'=>'POINT81+',         'icon'=>'⭐', 'url'=>'/network81.php#point81',  'ruoli'=>'elite+',      'preview'=>true,  'req'=>'ELITE+'],
        // Admin
        'admin_center'   => ['label'=>'Admin Command',    'icon'=>'⚙️', 'url'=>'/admin.php',             'ruoli'=>'admin',        'preview'=>false, 'req'=>null],
    ];

    private static array $RUOLO_ORDER = ['MEMBER81' => 0, 'NETWORKER81' => 1, 'ELITE81' => 2, 'ADMIN81' => 3];
    private static array $MEMBERSHIP_ORDER = ['NONE' => 0, 'BASIC+' => 1, 'PRO+' => 2, 'ELITE+' => 3];

    public static function for_user(int $user_id, string $ruolo, string $genesys, string $membership): array {
        $r_level = self::$RUOLO_ORDER[$ruolo] ?? 0;
        $m_level = self::$MEMBERSHIP_ORDER[$membership] ?? 0;
        $result  = [];

        foreach (self::$MODULES as $codice => $m) {
            // Filtra per ruolo
            $visible = match($m['ruoli']) {
                'all'         => true,
                'networker+'  => $r_level >= 1,
                'elite+'      => $r_level >= 2,
                'admin'       => $ruolo === 'ADMIN81',
                default       => true,
            };
            if (!$visible) continue;

            // Calcola locked state
            $locked = false;
            if ($m['req']) {
                $req_level = self::$MEMBERSHIP_ORDER[$m['req']] ?? 0;
                $locked    = $m_level < $req_level;
            }

            $result[] = [
                'codice'  => $codice,
                'label'   => $m['label'],
                'icon'    => $m['icon'],
                'url'     => $m['url'] ? BASE_URL . $m['url'] : null,
                'preview' => $m['preview'],
                'locked'  => $locked,
                'req'     => $m['req'],
            ];
        }
        return $result;
    }

    public static function render_html(array $modules): string {
        $out = '<div class="modules-grid">';
        foreach ($modules as $m) {
            $locked_class = $m['locked'] ? ' module-locked' : '';
            $preview_class = $m['preview'] ? ' module-preview' : '';
            $href = $m['url'] ? 'href="' . htmlspecialchars($m['url'], ENT_QUOTES) . '"' : 'href="#"';
            $lock_badge = $m['locked'] ? '<span class="lock-badge" aria-label="Richiede ' . htmlspecialchars($m['req'] ?? '', ENT_QUOTES) . '">🔒 ' . htmlspecialchars($m['req'] ?? '', ENT_QUOTES) . '</span>' : '';
            $out .= '<a ' . $href . ' class="module-card' . $locked_class . $preview_class . '" aria-label="' . htmlspecialchars($m['label'], ENT_QUOTES) . '">';
            $out .= '<span class="module-icon" aria-hidden="true">' . $m['icon'] . '</span>';
            $out .= '<span class="module-label">' . htmlspecialchars($m['label'], ENT_QUOTES) . '</span>';
            $out .= $lock_badge;
            $out .= '</a>';
        }
        $out .= '</div>';
        return $out;
    }
}
