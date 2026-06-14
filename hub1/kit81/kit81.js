/* ============================================================
   KIT81 · Shared JS Library · v1.0
   81+ Ecosystem · WEB STANDARDS 81+ V1.0 · 13 Giugno 2026
   © 2026 Labo Tecnic Studio · P.IVA IT01504180298
   ============================================================ */

(function () {
  'use strict';

  /* ─── I18N STRINGS ─── */
  const I18N = {
    it: {
      nav_audit: 'Audit', nav_agents: 'Agenti AI', nav_docs: 'Documenti',
      nav_ecosystem: 'Ecosistema', nav_scad: 'Scadenziario',
      nav_login: 'Accedi', nav_signup: 'SIC-ID Gratis',
      nav_logout: 'Esci', nav_profile: 'Profilo', nav_dashboard: 'Dashboard',
      footer_products: 'Prodotti', footer_legal: 'Legale', footer_community: 'Community',
      footer_mission: 'Il primo ecosistema agentico per la sicurezza aziendale. 32.000+ imprese seguite dal 2003.',
      footer_copy: '© 2026 Labo Tecnic Studio · P.IVA IT01504180298 · Porto Viro RO',
      cookie_msg: 'Usiamo cookie tecnici e, con il tuo consenso, di analisi per migliorare il sito.',
      cookie_ok: 'Accetto tutti', cookie_no: 'Solo tecnici',
      toast_copied: 'Copiato!', toast_err: 'Errore. Riprova.',
      skip_nav: 'Vai al contenuto principale',
    },
    en: {
      nav_audit: 'Audit', nav_agents: 'AI Agents', nav_docs: 'Documents',
      nav_ecosystem: 'Ecosystem', nav_scad: 'Deadlines',
      nav_login: 'Sign In', nav_signup: 'SIC-ID Free',
      nav_logout: 'Sign Out', nav_profile: 'Profile', nav_dashboard: 'Dashboard',
      footer_products: 'Products', footer_legal: 'Legal', footer_community: 'Community',
      footer_mission: 'The first agentic ecosystem for workplace safety. 32,000+ companies served since 2003.',
      footer_copy: '© 2026 Labo Tecnic Studio · VAT IT01504180298 · Porto Viro, Italy',
      cookie_msg: 'We use technical cookies and, with your consent, analytics to improve the site.',
      cookie_ok: 'Accept all', cookie_no: 'Technical only',
      toast_copied: 'Copied!', toast_err: 'Error. Try again.',
      skip_nav: 'Skip to main content',
    },
    es: {
      nav_audit: 'Auditoría', nav_agents: 'Agentes IA', nav_docs: 'Documentos',
      nav_ecosystem: 'Ecosistema', nav_scad: 'Vencimientos',
      nav_login: 'Entrar', nav_signup: 'SIC-ID Gratis',
      nav_logout: 'Salir', nav_profile: 'Perfil', nav_dashboard: 'Panel',
      footer_products: 'Productos', footer_legal: 'Legal', footer_community: 'Comunidad',
      footer_mission: 'El primer ecosistema agéntico para la seguridad laboral.',
      footer_copy: '© 2026 Labo Tecnic Studio · IVA IT01504180298 · Porto Viro, Italia',
      cookie_msg: 'Usamos cookies técnicas y, con tu consentimiento, de análisis.',
      cookie_ok: 'Aceptar todo', cookie_no: 'Solo técnicas',
      toast_copied: '¡Copiado!', toast_err: 'Error. Inténtalo de nuevo.',
      skip_nav: 'Ir al contenido principal',
    },
    fr: {
      nav_audit: 'Audit', nav_agents: 'Agents IA', nav_docs: 'Documents',
      nav_ecosystem: 'Écosystème', nav_scad: 'Échéances',
      nav_login: 'Connexion', nav_signup: 'SIC-ID Gratuit',
      nav_logout: 'Déconnexion', nav_profile: 'Profil', nav_dashboard: 'Tableau de bord',
      footer_products: 'Produits', footer_legal: 'Légal', footer_community: 'Communauté',
      footer_mission: "Le premier écosystème agentique pour la sécurité au travail.",
      footer_copy: '© 2026 Labo Tecnic Studio · TVA IT01504180298 · Porto Viro, Italie',
      cookie_msg: "Nous utilisons des cookies techniques et, avec votre consentement, d'analyse.",
      cookie_ok: 'Tout accepter', cookie_no: 'Techniques seulement',
      toast_copied: 'Copié!', toast_err: 'Erreur. Réessayez.',
      skip_nav: 'Aller au contenu principal',
    },
    de: {
      nav_audit: 'Audit', nav_agents: 'KI-Agenten', nav_docs: 'Dokumente',
      nav_ecosystem: 'Ökosystem', nav_scad: 'Fristen',
      nav_login: 'Anmelden', nav_signup: 'SIC-ID Kostenlos',
      nav_logout: 'Abmelden', nav_profile: 'Profil', nav_dashboard: 'Dashboard',
      footer_products: 'Produkte', footer_legal: 'Rechtliches', footer_community: 'Community',
      footer_mission: 'Das erste agentische Ökosystem für Arbeitssicherheit.',
      footer_copy: '© 2026 Labo Tecnic Studio · USt IT01504180298 · Porto Viro, Italien',
      cookie_msg: 'Wir verwenden technische Cookies und mit Ihrer Zustimmung Analyse-Cookies.',
      cookie_ok: 'Alle akzeptieren', cookie_no: 'Nur technische',
      toast_copied: 'Kopiert!', toast_err: 'Fehler. Bitte erneut versuchen.',
      skip_nav: 'Zum Hauptinhalt springen',
    },
    pt: {
      nav_audit: 'Auditoria', nav_agents: 'Agentes IA', nav_docs: 'Documentos',
      nav_ecosystem: 'Ecossistema', nav_scad: 'Prazos',
      nav_login: 'Entrar', nav_signup: 'SIC-ID Grátis',
      nav_logout: 'Sair', nav_profile: 'Perfil', nav_dashboard: 'Painel',
      footer_products: 'Produtos', footer_legal: 'Legal', footer_community: 'Comunidade',
      footer_mission: 'O primeiro ecossistema agêntico para segurança no trabalho.',
      footer_copy: '© 2026 Labo Tecnic Studio · NIF IT01504180298 · Porto Viro, Itália',
      cookie_msg: 'Usamos cookies técnicos e, com seu consentimento, de análise.',
      cookie_ok: 'Aceitar todos', cookie_no: 'Apenas técnicos',
      toast_copied: 'Copiado!', toast_err: 'Erro. Tente novamente.',
      skip_nav: 'Ir para o conteúdo principal',
    },
    zh: {
      nav_audit: '审计', nav_agents: 'AI代理', nav_docs: '文件',
      nav_ecosystem: '生态系统', nav_scad: '截止日期',
      nav_login: '登录', nav_signup: '免费SIC-ID',
      nav_logout: '退出', nav_profile: '个人资料', nav_dashboard: '控制台',
      footer_products: '产品', footer_legal: '法律', footer_community: '社区',
      footer_mission: '职场安全领域首个智能体生态系统。自2003年以来服务32,000+家企业。',
      footer_copy: '© 2026 Labo Tecnic Studio · 增值税 IT01504180298 · 意大利波尔托维罗',
      cookie_msg: '我们使用技术性Cookie，并在您同意的情况下使用分析性Cookie。',
      cookie_ok: '全部接受', cookie_no: '仅技术性',
      toast_copied: '已复制!', toast_err: '错误。请重试。',
      skip_nav: '跳至主要内容',
    },
    ja: {
      nav_audit: '監査', nav_agents: 'AIエージェント', nav_docs: 'ドキュメント',
      nav_ecosystem: 'エコシステム', nav_scad: '期限管理',
      nav_login: 'ログイン', nav_signup: '無料SIC-ID',
      nav_logout: 'ログアウト', nav_profile: 'プロフィール', nav_dashboard: 'ダッシュボード',
      footer_products: '製品', footer_legal: '法的情報', footer_community: 'コミュニティ',
      footer_mission: '労働安全のための最初のエージェンティックエコシステム。',
      footer_copy: '© 2026 Labo Tecnic Studio · 税番号 IT01504180298 · イタリア',
      cookie_msg: '技術的なCookieと、同意いただいた場合に分析用Cookieを使用します。',
      cookie_ok: 'すべて承認', cookie_no: '技術的のみ',
      toast_copied: 'コピーしました!', toast_err: 'エラー。もう一度お試しください。',
      skip_nav: 'メインコンテンツへスキップ',
    },
    ar: {
      nav_audit: 'تدقيق', nav_agents: 'وكلاء الذكاء الاصطناعي', nav_docs: 'الوثائق',
      nav_ecosystem: 'النظام البيئي', nav_scad: 'المواعيد النهائية',
      nav_login: 'تسجيل الدخول', nav_signup: 'SIC-ID مجاني',
      nav_logout: 'تسجيل الخروج', nav_profile: 'الملف الشخصي', nav_dashboard: 'لوحة التحكم',
      footer_products: 'المنتجات', footer_legal: 'قانوني', footer_community: 'المجتمع',
      footer_mission: 'أول نظام بيئي وكيل لسلامة مكان العمل.',
      footer_copy: '© 2026 Labo Tecnic Studio · ض.ق.م IT01504180298 · إيطاليا',
      cookie_msg: 'نستخدم ملفات تعريف الارتباط التقنية وملفات التحليل بموافقتك.',
      cookie_ok: 'قبول الكل', cookie_no: 'التقنية فقط',
      toast_copied: 'تم النسخ!', toast_err: 'خطأ. حاول مرة أخرى.',
      skip_nav: 'انتقل إلى المحتوى الرئيسي',
    },
    ru: {
      nav_audit: 'Аудит', nav_agents: 'ИИ-агенты', nav_docs: 'Документы',
      nav_ecosystem: 'Экосистема', nav_scad: 'Сроки',
      nav_login: 'Войти', nav_signup: 'SIC-ID Бесплатно',
      nav_logout: 'Выйти', nav_profile: 'Профиль', nav_dashboard: 'Панель',
      footer_products: 'Продукты', footer_legal: 'Правовая информация', footer_community: 'Сообщество',
      footer_mission: 'Первая агентская экосистема для охраны труда.',
      footer_copy: '© 2026 Labo Tecnic Studio · НДС IT01504180298 · Порто-Виро, Италия',
      cookie_msg: 'Мы используем технические файлы cookie и аналитические с вашего согласия.',
      cookie_ok: 'Принять все', cookie_no: 'Только технические',
      toast_copied: 'Скопировано!', toast_err: 'Ошибка. Попробуйте снова.',
      skip_nav: 'Перейти к основному содержимому',
    },
    ko: {
      nav_audit: '감사', nav_agents: 'AI 에이전트', nav_docs: '문서',
      nav_ecosystem: '에코시스템', nav_scad: '마감일',
      nav_login: '로그인', nav_signup: '무료 SIC-ID',
      nav_logout: '로그아웃', nav_profile: '프로필', nav_dashboard: '대시보드',
      footer_products: '제품', footer_legal: '법적 정보', footer_community: '커뮤니티',
      footer_mission: '직장 안전을 위한 최초의 에이전틱 에코시스템.',
      footer_copy: '© 2026 Labo Tecnic Studio · 사업자번호 IT01504180298 · 이탈리아',
      cookie_msg: '기술적 쿠키와 동의 시 분석 쿠키를 사용합니다.',
      cookie_ok: '모두 수락', cookie_no: '기술적만',
      toast_copied: '복사됨!', toast_err: '오류. 다시 시도하세요.',
      skip_nav: '본문으로 건너뛰기',
    },
    hi: {
      nav_audit: 'ऑडिट', nav_agents: 'AI एजेंट', nav_docs: 'दस्तावेज़',
      nav_ecosystem: 'इकोसिस्टम', nav_scad: 'समय-सीमा',
      nav_login: 'लॉग इन', nav_signup: 'मुफ़्त SIC-ID',
      nav_logout: 'लॉग आउट', nav_profile: 'प्रोफ़ाइल', nav_dashboard: 'डैशबोर्ड',
      footer_products: 'उत्पाद', footer_legal: 'कानूनी', footer_community: 'समुदाय',
      footer_mission: 'कार्यस्थल सुरक्षा के लिए पहला एजेंटिक इकोसिस्टम।',
      footer_copy: '© 2026 Labo Tecnic Studio · VAT IT01504180298 · इटली',
      cookie_msg: 'हम तकनीकी कुकीज़ और आपकी सहमति से विश्लेषण कुकीज़ का उपयोग करते हैं।',
      cookie_ok: 'सभी स्वीकार करें', cookie_no: 'केवल तकनीकी',
      toast_copied: 'कॉपी किया!', toast_err: 'त्रुटि। पुनः प्रयास करें।',
      skip_nav: 'मुख्य सामग्री पर जाएँ',
    },
  };

  /* ─── LANGUAGE LIST (60+) ─── */
  const LANGS = [
    { code:'it', flag:'🇮🇹', native:'Italiano' },
    { code:'en', flag:'🇬🇧', native:'English' },
    { code:'es', flag:'🇪🇸', native:'Español' },
    { code:'fr', flag:'🇫🇷', native:'Français' },
    { code:'de', flag:'🇩🇪', native:'Deutsch' },
    { code:'pt', flag:'🇵🇹', native:'Português' },
    { code:'zh', flag:'🇨🇳', native:'中文' },
    { code:'ja', flag:'🇯🇵', native:'日本語' },
    { code:'ko', flag:'🇰🇷', native:'한국어' },
    { code:'ar', flag:'🇸🇦', native:'العربية', rtl:true },
    { code:'hi', flag:'🇮🇳', native:'हिन्दी' },
    { code:'ru', flag:'🇷🇺', native:'Русский' },
    { code:'tr', flag:'🇹🇷', native:'Türkçe' },
    { code:'nl', flag:'🇳🇱', native:'Nederlands' },
    { code:'pl', flag:'🇵🇱', native:'Polski' },
    { code:'sv', flag:'🇸🇪', native:'Svenska' },
    { code:'da', flag:'🇩🇰', native:'Dansk' },
    { code:'no', flag:'🇳🇴', native:'Norsk' },
    { code:'fi', flag:'🇫🇮', native:'Suomi' },
    { code:'ro', flag:'🇷🇴', native:'Română' },
    { code:'uk', flag:'🇺🇦', native:'Українська' },
    { code:'cs', flag:'🇨🇿', native:'Čeština' },
    { code:'hu', flag:'🇭🇺', native:'Magyar' },
    { code:'el', flag:'🇬🇷', native:'Ελληνικά' },
    { code:'bg', flag:'🇧🇬', native:'Български' },
    { code:'hr', flag:'🇭🇷', native:'Hrvatski' },
    { code:'sk', flag:'🇸🇰', native:'Slovenčina' },
    { code:'sl', flag:'🇸🇮', native:'Slovenščina' },
    { code:'sr', flag:'🇷🇸', native:'Српски' },
    { code:'id', flag:'🇮🇩', native:'Bahasa Indonesia' },
    { code:'ms', flag:'🇲🇾', native:'Bahasa Melayu' },
    { code:'vi', flag:'🇻🇳', native:'Tiếng Việt' },
    { code:'th', flag:'🇹🇭', native:'ไทย' },
    { code:'he', flag:'🇮🇱', native:'עברית', rtl:true },
    { code:'fa', flag:'🇮🇷', native:'فارسی', rtl:true },
    { code:'ur', flag:'🇵🇰', native:'اردو', rtl:true },
    { code:'bn', flag:'🇧🇩', native:'বাংলা' },
    { code:'ta', flag:'🇮🇳', native:'தமிழ்' },
    { code:'te', flag:'🇮🇳', native:'తెలుగు' },
    { code:'ml', flag:'🇮🇳', native:'മലയാളം' },
    { code:'mr', flag:'🇮🇳', native:'मराठी' },
    { code:'sw', flag:'🇰🇪', native:'Kiswahili' },
    { code:'am', flag:'🇪🇹', native:'አማርኛ' },
    { code:'ha', flag:'🇳🇬', native:'Hausa' },
    { code:'yo', flag:'🇳🇬', native:'Yorùbá' },
    { code:'ig', flag:'🇳🇬', native:'Igbo' },
    { code:'af', flag:'🇿🇦', native:'Afrikaans' },
    { code:'az', flag:'🇦🇿', native:'Azərbaycan' },
    { code:'kk', flag:'🇰🇿', native:'Қазақша' },
    { code:'uz', flag:'🇺🇿', native:"O'zbek" },
    { code:'hy', flag:'🇦🇲', native:'Հայերեն' },
    { code:'ka', flag:'🇬🇪', native:'ქართული' },
    { code:'lt', flag:'🇱🇹', native:'Lietuvių' },
    { code:'lv', flag:'🇱🇻', native:'Latviešu' },
    { code:'et', flag:'🇪🇪', native:'Eesti' },
    { code:'mn', flag:'🇲🇳', native:'Монгол' },
    { code:'ne', flag:'🇳🇵', native:'नेपाली' },
    { code:'si', flag:'🇱🇰', native:'සිංහල' },
    { code:'my', flag:'🇲🇲', native:'မြန်မာ' },
    { code:'km', flag:'🇰🇭', native:'ខ្មែរ' },
    { code:'lo', flag:'🇱🇦', native:'ລາວ' },
    { code:'ca', flag:'🏳️', native:'Català' },
  ];

  const MAIN_LANGS = ['it','en','es','fr','de','pt','zh','ja','ko','ar','hi','ru'];

  /* ─── LANG STATE ─── */
  let _lang = 'it';

  function initLang() {
    const saved = localStorage.getItem('81_lang');
    const nav = (navigator.language || '').slice(0, 2).toLowerCase();
    const candidate = saved || nav || 'it';
    const valid = LANGS.map(l => l.code);
    _lang = valid.includes(candidate) ? candidate : 'it';
    window.__lang = _lang;
    const rtl = LANGS.find(l => l.code === _lang && l.rtl);
    if (rtl) document.documentElement.setAttribute('dir', 'rtl');
  }

  function T(key) {
    return I18N[_lang]?.[key] ?? I18N.en?.[key] ?? I18N.it?.[key] ?? key;
  }

  function applyI18N() {
    document.querySelectorAll('[data-i18n]').forEach(el => {
      const key = el.dataset.i18n;
      if (el.tagName === 'INPUT' && el.placeholder !== undefined) {
        el.placeholder = T(key);
      } else {
        el.textContent = T(key);
      }
    });
    document.querySelectorAll('[data-i18n-title]').forEach(el => {
      el.title = T(el.dataset.i18nTitle);
    });
  }

  function setLang(code) {
    const valid = LANGS.map(l => l.code);
    if (!valid.includes(code)) return;
    _lang = code;
    window.__lang = code;
    localStorage.setItem('81_lang', code);
    const langCodeEl = document.getElementById('k81NavLangCode');
    if (langCodeEl) langCodeEl.textContent = code.toUpperCase();
    const rtl = LANGS.find(l => l.code === code && l.rtl);
    document.documentElement.setAttribute('dir', rtl ? 'rtl' : 'ltr');
    applyI18N();
    document.querySelectorAll('.lang-item').forEach(el => {
      el.classList.toggle('active', el.dataset.lang === code);
    });
    closeLangModal();
  }

  /* ─── AUTH ─── */
  function getAuth() {
    try {
      const raw = localStorage.getItem('sic_token');
      if (!raw) return null;
      const parts = raw.split('.');
      if (parts.length < 2) return null;
      const payload = JSON.parse(atob(parts[1]));
      if (payload.exp && payload.exp < Math.floor(Date.now() / 1000)) {
        localStorage.removeItem('sic_token');
        return null;
      }
      return payload;
    } catch {
      return null;
    }
  }

  /* ─── LOADER ─── */
  function buildLoader() {
    if (document.getElementById('k81-loader')) return;
    const el = document.createElement('div');
    el.id = 'k81-loader';
    el.setAttribute('role', 'status');
    el.setAttribute('aria-live', 'polite');
    el.innerHTML = `<div class="k81-loader-logo" aria-hidden="true">81+</div>
<div class="k81-loader-bar" aria-hidden="true"><div class="k81-loader-fill"></div></div>`;
    document.body.insertBefore(el, document.body.firstChild);
  }

  function hideLoader() {
    const el = document.getElementById('k81-loader');
    if (!el) return;
    el.classList.add('k81-gone');
    setTimeout(() => el.remove(), 500);
  }

  /* ─── SKIP NAV ─── */
  function buildSkipNav() {
    if (document.querySelector('.skip-nav')) return;
    const a = document.createElement('a');
    a.href = '#main-content';
    a.className = 'skip-nav';
    a.setAttribute('data-i18n', 'skip_nav');
    a.textContent = T('skip_nav');
    document.body.insertBefore(a, document.body.firstChild);
  }

  /* ─── NAVBAR ─── */
  function buildNav() {
    if (document.getElementById('k81-nav')) return;
    const auth = getAuth();
    const hub = window.K81?.hub || 'NET';
    const customLinks = window.K81?.nav || [];

    let linksHTML = `
      <li><a href="/audit.html" data-i18n="nav_audit">Audit</a></li>
      <li><a href="/agenti.html" data-i18n="nav_agents">Agenti AI</a></li>
      <li><a href="/scadenziario.html" data-i18n="nav_scad">Scadenziario</a></li>
      ${auth ? `<li><a href="/documenti.html" data-i18n="nav_docs">Documenti</a></li>` : ''}
    `;

    customLinks.forEach(link => {
      if (!link.auth || auth) {
        linksHTML += `<li><a href="${link.href}">${link.label}</a></li>`;
      }
    });

    linksHTML += `
      <li class="nav-dropdown">
        <a href="#" aria-haspopup="true" data-i18n="nav_ecosystem">Ecosistema</a>
        <div class="dd" role="menu">
          <a href="https://81plus.net" role="menuitem">HUB1 · 81plus.net</a>
          <a href="https://81plus.it" role="menuitem">HUB2 · 81plus.it</a>
          <a href="https://sicurissimo.online" role="menuitem">Sicurissimo</a>
          <a href="https://81plus.online" role="menuitem">HUB3 · Web3</a>
        </div>
      </li>
    `;

    const pvHTML = auth ? `<span class="nav-pv" id="k81NavPv" aria-label="Saldo PV">0 PV</span>` : '';
    const authHTML = auth
      ? `<a class="btn btn-ghost btn-sm" href="/profile.html" data-i18n="nav_profile">Profilo</a>
         <a class="btn btn-ghost btn-sm" id="k81LogoutBtn" href="#" data-i18n="nav_logout">Esci</a>`
      : `<a class="btn btn-ghost btn-sm" href="/login.html" data-i18n="nav_login">Accedi</a>
         <a class="btn btn-primary btn-sm" href="/signup.html" data-i18n="nav_signup">SIC-ID Gratis</a>`;

    const nav = document.createElement('nav');
    nav.id = 'k81-nav';
    nav.setAttribute('role', 'navigation');
    nav.setAttribute('aria-label', 'Navigazione principale');
    nav.innerHTML = `
<div class="nav-inner">
  <a class="nav-logo" href="/" aria-label="81+ Home">
    <span class="logo-81">81</span><span class="logo-plus">+</span>
    <span class="nav-logo-hub">${hub}</span>
  </a>
  <ul class="nav-links" id="k81NavLinks" role="menubar" aria-label="Menu principale">
    ${linksHTML}
  </ul>
  <div class="nav-actions">
    <button class="nav-lang" id="k81LangBtn" aria-label="Cambia lingua" aria-expanded="false" aria-haspopup="dialog">
      🌐&nbsp;<span id="k81NavLangCode">${_lang.toUpperCase()}</span>
    </button>
    ${pvHTML}
    ${authHTML}
  </div>
  <button class="hamburger" id="k81Hamburger" aria-label="Apri menu" aria-expanded="false" aria-controls="k81NavLinks">
    <span aria-hidden="true"></span><span aria-hidden="true"></span><span aria-hidden="true"></span>
  </button>
</div>`;

    const loader = document.getElementById('k81-loader');
    if (loader) loader.insertAdjacentElement('afterend', nav);
    else document.body.insertBefore(nav, document.body.firstChild);

    /* Hamburger */
    const hamburger = document.getElementById('k81Hamburger');
    const navLinks = document.getElementById('k81NavLinks');
    hamburger?.addEventListener('click', () => {
      const open = navLinks.classList.toggle('open');
      hamburger.classList.toggle('open', open);
      hamburger.setAttribute('aria-expanded', String(open));
    });

    /* Close mobile nav on link click */
    navLinks?.querySelectorAll('a').forEach(a => {
      a.addEventListener('click', () => {
        navLinks.classList.remove('open');
        hamburger?.classList.remove('open');
        hamburger?.setAttribute('aria-expanded', 'false');
      });
    });

    /* Scroll class */
    window.addEventListener('scroll', () => {
      nav.classList.toggle('scrolled', window.scrollY > 20);
    }, { passive: true });

    /* Active link */
    const path = window.location.pathname;
    nav.querySelectorAll('.nav-links > li > a').forEach(a => {
      const href = a.getAttribute('href') || '';
      if (href !== '#' && (path === href || path.endsWith(href))) {
        a.classList.add('active');
        a.setAttribute('aria-current', 'page');
      }
    });

    /* Logout */
    document.getElementById('k81LogoutBtn')?.addEventListener('click', e => {
      e.preventDefault();
      localStorage.removeItem('sic_token');
      localStorage.removeItem('sic_pv');
      window.location.href = '/';
    });

    /* PV badge */
    if (auth) {
      const pvEl = document.getElementById('k81NavPv');
      if (pvEl) {
        const pv = localStorage.getItem('sic_pv') || '0';
        pvEl.textContent = `${pv} PV`;
      }
    }

    /* Lang button */
    document.getElementById('k81LangBtn')?.addEventListener('click', openLangModal);
  }

  /* ─── TICKER ─── */
  function buildTicker() {
    if (!window.K81?.ticker) return;
    if (document.getElementById('k81-ticker')) return;

    const tickerItems = window.K81.ticker || [
      { lbl: 'AZIENDE', val: '32.000+', cls: '' },
      { lbl: 'ATTESTATI', val: '225.000+', cls: '' },
      { lbl: 'RECENSIONI', val: '4,81 ★', cls: 'up' },
      { lbl: 'SETTORI', val: '35', cls: '' },
      { lbl: 'INFORTUNI INAIL 2023', val: '597.710', cls: 'dn' },
      { lbl: '81X', val: '21M MAX', cls: 'cyan' },
      { lbl: 'PV', val: '1 PV = 1€', cls: 'up' },
    ];

    const makeItem = ({ lbl, val, cls }) =>
      `<span class="ticker-item"><span class="ti-lbl">${lbl}</span><span class="ti-val ${cls || ''}">${val}</span></span><span class="ticker-sep">·</span>`;

    const items = tickerItems.map(makeItem).join('');

    const ticker = document.createElement('div');
    ticker.id = 'k81-ticker';
    ticker.setAttribute('aria-hidden', 'true');
    ticker.innerHTML = `<div class="ticker-track">${items}${items}</div>`;

    const nav = document.getElementById('k81-nav');
    if (nav) nav.insertAdjacentElement('afterend', ticker);
    else document.body.insertBefore(ticker, document.querySelector('main') || document.body.firstChild);
  }

  /* ─── FOOTER ─── */
  function buildFooter() {
    if (document.getElementById('k81-footer')) return;
    const footer = document.createElement('footer');
    footer.id = 'k81-footer';
    footer.setAttribute('role', 'contentinfo');
    footer.innerHTML = `
<div class="wrapper">
  <div class="footer-grid">
    <div>
      <a class="nav-logo" href="/" aria-label="81+ Home" style="margin-bottom:0">
        <span class="logo-81">81</span><span class="logo-plus">+</span>
      </a>
      <p class="footer-mission" data-i18n="footer_mission">Il primo ecosistema agentico per la sicurezza aziendale.</p>
    </div>
    <div>
      <h4 data-i18n="footer_products">Prodotti</h4>
      <ul>
        <li><a href="https://81plus.it">Membership 81+</a></li>
        <li><a href="/audit.html">Audit AI</a></li>
        <li><a href="/agenti.html">Agenti 18</a></li>
        <li><a href="/documenti.html">Fabbrica Documenti</a></li>
        <li><a href="https://81plus.online">HUB3 · Web3</a></li>
      </ul>
    </div>
    <div>
      <h4 data-i18n="footer_legal">Legale</h4>
      <ul>
        <li><a href="/privacy.html">Privacy Policy</a></li>
        <li><a href="/termini.html">Termini di Servizio</a></li>
        <li><a href="/cookie.html">Cookie Policy</a></li>
        <li><a href="/note-legali.html">Note Legali</a></li>
        <li><a href="/modulistica.html">Modulistica</a></li>
      </ul>
    </div>
    <div>
      <h4 data-i18n="footer_community">Community</h4>
      <ul>
        <li><a href="https://www.youtube.com/@sicurissimo" rel="noopener noreferrer" target="_blank">YouTube @sicurissimo</a></li>
        <li><a href="https://t.me/sicurissimo" rel="noopener noreferrer" target="_blank">Telegram</a></li>
        <li><a href="mailto:info@81plus.net">info@81plus.net</a></li>
        <li><a href="mailto:welcome@81plus.net">welcome@81plus.net</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p data-i18n="footer_copy">© 2026 Labo Tecnic Studio · P.IVA IT01504180298 · Porto Viro RO</p>
    <p><a href="/privacy.html">Privacy</a> · <a href="/termini.html">Termini</a> · <a href="/cookie.html">Cookie</a> · <a href="/note-legali.html">Note legali</a></p>
  </div>
</div>`;
    document.body.appendChild(footer);
  }

  /* ─── TOAST ─── */
  function buildToast() {
    if (document.getElementById('k81-toast')) return;
    const el = document.createElement('div');
    el.id = 'k81-toast';
    el.setAttribute('role', 'status');
    el.setAttribute('aria-live', 'polite');
    el.setAttribute('aria-atomic', 'true');
    document.body.appendChild(el);
  }

  function toast(msg, type = 'info', duration = 3500) {
    const el = document.getElementById('k81-toast');
    if (!el) return;
    el.textContent = msg;
    el.className = `on ${type}`;
    clearTimeout(el._tid);
    el._tid = setTimeout(() => { el.className = ''; }, duration);
  }

  /* ─── COOKIE BAR ─── */
  function buildCookieBar() {
    if (localStorage.getItem('81_cookie_ok')) return;
    if (document.getElementById('k81-cookie')) return;
    const bar = document.createElement('div');
    bar.id = 'k81-cookie';
    bar.setAttribute('role', 'dialog');
    bar.setAttribute('aria-label', 'Consenso Cookie');
    bar.setAttribute('aria-modal', 'false');
    bar.innerHTML = `
<p data-i18n="cookie_msg">Usiamo cookie tecnici e, con il tuo consenso, di analisi per migliorare il sito.</p>
<div class="cookie-actions">
  <button class="btn btn-ghost btn-sm" id="k81CookieNo" data-i18n="cookie_no">Solo tecnici</button>
  <button class="btn btn-primary btn-sm" id="k81CookieOk" data-i18n="cookie_ok">Accetto tutti</button>
</div>`;
    document.body.appendChild(bar);
    requestAnimationFrame(() => requestAnimationFrame(() => bar.classList.add('show')));

    const accept = all => {
      localStorage.setItem('81_cookie_ok', all ? 'all' : 'tech');
      bar.classList.remove('show');
      setTimeout(() => bar.remove(), 400);
    };
    document.getElementById('k81CookieOk')?.addEventListener('click', () => accept(true));
    document.getElementById('k81CookieNo')?.addEventListener('click', () => accept(false));
  }

  /* ─── LANG MODAL ─── */
  let _langModal = null;

  function buildLangModal() {
    if (document.getElementById('k81LangModal')) return;
    const main = LANGS.filter(l => MAIN_LANGS.includes(l.code));
    const other = LANGS.filter(l => !MAIN_LANGS.includes(l.code));

    const renderItems = arr => arr.map(l =>
      `<div class="lang-item${l.code === _lang ? ' active' : ''}" data-lang="${l.code}" tabindex="0" role="option" aria-selected="${l.code === _lang}">
        <span class="flag">${l.flag}</span><span>${l.native}</span>
      </div>`
    ).join('');

    const modal = document.createElement('div');
    modal.id = 'k81LangModal';
    modal.className = 'lang-modal';
    modal.setAttribute('role', 'dialog');
    modal.setAttribute('aria-modal', 'true');
    modal.setAttribute('aria-label', 'Seleziona lingua');
    modal.innerHTML = `
<div class="lang-box" role="listbox" aria-label="Lingue disponibili">
  <input class="lang-search" type="search" placeholder="Cerca lingua…" id="k81LangSearch" autocomplete="off" aria-label="Cerca lingua">
  <div class="lang-box-title">Principali</div>
  <div class="lang-grid" id="k81LangMain">${renderItems(main)}</div>
  <hr class="divider" style="margin:16px 0">
  <div class="lang-box-title">Altre lingue</div>
  <div class="lang-grid" id="k81LangOther">${renderItems(other)}</div>
</div>`;

    document.body.appendChild(modal);
    _langModal = modal;

    modal.addEventListener('click', e => { if (e.target === modal) closeLangModal(); });
    modal.addEventListener('keydown', e => { if (e.key === 'Escape') closeLangModal(); });

    modal.querySelectorAll('.lang-item').forEach(item => {
      const select = () => setLang(item.dataset.lang);
      item.addEventListener('click', select);
      item.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); select(); }
      });
    });

    document.getElementById('k81LangSearch')?.addEventListener('input', function () {
      const q = this.value.toLowerCase();
      modal.querySelectorAll('.lang-item').forEach(el => {
        el.style.display = el.textContent.toLowerCase().includes(q) ? '' : 'none';
      });
    });
  }

  function openLangModal() {
    buildLangModal();
    _langModal = document.getElementById('k81LangModal');
    _langModal.classList.add('open');
    document.getElementById('k81LangBtn')?.setAttribute('aria-expanded', 'true');
    document.getElementById('k81LangSearch')?.focus();
  }

  function closeLangModal() {
    document.getElementById('k81LangModal')?.classList.remove('open');
    document.getElementById('k81LangBtn')?.setAttribute('aria-expanded', 'false');
  }

  /* ─── COPY UTILITY ─── */
  function copyText(text) {
    navigator.clipboard?.writeText(text).then(() => toast(T('toast_copied'), 'ok'))
      .catch(() => toast(T('toast_err'), 'err'));
  }

  /* ─── SCHEMA JSON-LD ─── */
  function injectSchema() {
    if (document.querySelector('script[data-k81-schema]')) return;
    const s = document.createElement('script');
    s.type = 'application/ld+json';
    s.setAttribute('data-k81-schema', '');
    s.textContent = JSON.stringify({
      '@context': 'https://schema.org',
      '@type': 'Organization',
      name: '81+ · Labo Tecnic Studio',
      url: 'https://81plus.net',
      logo: 'https://81plus.net/assets/logo-81plus.svg',
      contactPoint: {
        '@type': 'ContactPoint',
        email: 'info@81plus.net',
        contactType: 'customer service',
        availableLanguage: ['Italian', 'English'],
      },
      address: {
        '@type': 'PostalAddress',
        addressLocality: 'Porto Viro',
        addressRegion: 'RO',
        addressCountry: 'IT',
      },
      vatID: 'IT01504180298',
      sameAs: ['https://81plus.it', 'https://sicurissimo.online'],
    });
    document.head.appendChild(s);
  }

  /* ─── INIT ─── */
  function init() {
    initLang();
    buildLoader();
    buildSkipNav();
    buildNav();
    buildTicker();
    buildToast();
    buildCookieBar();
    injectSchema();
    buildFooter();
    applyI18N();
    window.addEventListener('load', () => setTimeout(hideLoader, 150), { once: true });
  }

  /* ─── PUBLIC API ─── */
  const publicAPI = { toast, setLang, T, getAuth, openLangModal, copyText, hideLoader };
  if (window.K81 && typeof window.K81 === 'object') {
    Object.assign(window.K81, publicAPI);
  } else {
    window.K81 = publicAPI;
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
  } else {
    init();
  }

})();
