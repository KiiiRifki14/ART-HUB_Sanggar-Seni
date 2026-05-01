<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin | ART-HUB Sanggar Cahaya Gumilang')</title>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Noto+Serif:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary":                  "#361f1a",
                        "primary-container":        "#4e342e",
                        "on-primary":               "#ffffff",
                        "secondary":                "#705d00",
                        "secondary-container":      "#fcd400",
                        "on-secondary-container":   "#6e5c00",
                        "secondary-fixed-dim":      "#e9c400",
                        "surface":                  "#faf9f6",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low":    "#f4f3f1",
                        "surface-container":        "#efeeeb",
                        "surface-container-high":   "#e9e8e5",
                        "surface-container-highest":"#e3e2e0",
                        "on-surface":               "#1a1c1a",
                        "on-surface-variant":       "#504442",
                        "outline":                  "#827471",
                        "outline-variant":          "#d4c3bf",
                        "primary-fixed":            "#ffdad2",
                        "on-primary-fixed":         "#2b1611",
                    },
                    fontFamily: {
                        "headline": ["Noto Serif", "serif"],
                        "body":     ["Manrope", "sans-serif"],
                        "label":    ["Manrope", "sans-serif"],
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --sidebar-w: 240px;
            --sidebar-mini: 60px;
            --topbar-h: 56px;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Manrope', sans-serif; background: #faf9f6; color: #1a1c1a; margin: 0; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24; font-size: 1.1rem; }

        /* ── SIDEBAR ── */
        #sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: linear-gradient(180deg, #361f1a 0%, #2a1713 100%);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; z-index: 1040;
            transition: width 0.28s cubic-bezier(0.4,0,0.2,1);
            overflow: hidden;
        }
        #sidebar.mini { width: var(--sidebar-mini); }

        /* Brand */
        .arh-brand {
            display: flex; align-items: center; gap: 10px;
            padding: 0 14px; height: 60px; flex-shrink: 0;
            background: rgba(0,0,0,0.15);
        }
        .arh-brand-logo {
            width: 34px; height: 34px; border-radius: 8px; flex-shrink: 0;
            background: linear-gradient(135deg, #fcd400, #e9c400);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Noto Serif', serif; font-weight: 700; font-size: 0.78rem; color: #361f1a;
        }
        .arh-brand-text { flex: 1; min-width: 0; overflow: hidden; white-space: nowrap; transition: opacity 0.2s; }
        .arh-brand-title { font-family: 'Noto Serif', serif; font-size: 1rem; font-weight: 700; color: #fcd400; letter-spacing: 0.5px; }
        .arh-brand-sub { font-size: 0.55rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1px; }
        #sidebar.mini .arh-brand { justify-content: center; padding: 0; }
        #sidebar.mini .arh-brand-text { display: none; }

        /* Toggle */
        #sidebarToggle {
            width: 30px; height: 30px; border-radius: 6px; flex-shrink: 0; margin-left: auto;
            border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05);
            color: rgba(255,255,255,0.6); display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s;
        }
        #sidebarToggle:hover { background: rgba(252,212,0,0.15); color: #fcd400; border-color: rgba(252,212,0,0.3); }
        #sidebar.mini #sidebarToggle { margin-left: 0; }

        /* User row */
        .arh-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px; flex-shrink: 0;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .arh-avatar {
            width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, #fcd400, #e9c400);
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 0.72rem; color: #361f1a;
        }
        .arh-user-info { overflow: hidden; white-space: nowrap; }
        .arh-user-name { font-size: 0.78rem; font-weight: 600; color: #faf9f6; }
        .arh-user-role { font-size: 0.58rem; color: rgba(252,212,0,0.7); text-transform: uppercase; letter-spacing: 0.5px; }
        #sidebar.mini .arh-user { justify-content: center; padding: 10px 0; }
        #sidebar.mini .arh-user-info { display: none; }

        /* Nav */
        .arh-nav { list-style: none; padding: 8px 8px 0; margin: 0; overflow-y: auto; flex: 1; }
        .arh-nav-section {
            font-size: 0.58rem; color: rgba(252,212,0,0.5); text-transform: uppercase;
            letter-spacing: 1.2px; padding: 14px 6px 4px; font-weight: 700; white-space: nowrap;
        }
        #sidebar.mini .arh-nav-section { height: 1px; background: rgba(255,255,255,0.06); margin: 8px 8px 4px; padding: 0; font-size: 0; }

        .arh-nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 10px; border-radius: 8px; margin-bottom: 2px;
            color: rgba(255,255,255,0.65); text-decoration: none;
            font-size: 0.82rem; font-weight: 500;
            transition: all 0.18s; white-space: nowrap; overflow: hidden;
        }
        .arh-nav-link:hover { background: rgba(252,212,0,0.1); color: #fcd400; }
        .arh-nav-link.active {
            background: rgba(252,212,0,0.12);
            color: #fcd400; font-weight: 700;
            box-shadow: inset 3px 0 0 #fcd400;
        }
        .arh-nav-icon { width: 20px; text-align: center; flex-shrink: 0; font-size: 1rem; }
        #sidebar.mini .arh-nav { padding: 8px 4px 0; }
        #sidebar.mini .arh-nav-link { justify-content: center; padding: 10px 0; box-shadow: none !important; }
        #sidebar.mini .arh-nav-label { display: none; }

        /* Tooltip mini */
        #sidebar.mini .arh-nav-link::after {
            content: attr(data-tooltip); position: fixed;
            left: calc(var(--sidebar-mini) + 10px);
            background: #2a1713; color: #faf9f6; font-size: 0.78rem; font-weight: 500;
            padding: 5px 12px; border-radius: 6px; white-space: nowrap;
            pointer-events: none; opacity: 0; transition: opacity 0.15s; z-index: 9999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3); border: 1px solid rgba(252,212,0,0.2);
        }
        #sidebar.mini .arh-nav-link:hover::after { opacity: 1; }

        /* Logout */
        .arh-logout-wrap { padding: 8px 8px 16px; flex-shrink: 0; }
        .arh-logout {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 10px; border-radius: 8px; width: 100%;
            color: rgba(255,255,255,0.5); font-size: 0.82rem; font-weight: 500;
            background: none; border: none; cursor: pointer;
            transition: all 0.18s; white-space: nowrap; overflow: hidden;
        }
        .arh-logout:hover { background: rgba(186,26,26,0.15); color: #fca5a5; }
        #sidebar.mini .arh-logout { justify-content: center; padding: 10px 0; }
        #sidebar.mini .arh-logout-label { display: none; }
        #sidebar.mini .arh-logout::after {
            content: 'Logout'; position: fixed; left: calc(var(--sidebar-mini) + 10px);
            background: #2a1713; color: #fca5a5; font-size: 0.78rem; font-weight: 500;
            padding: 5px 12px; border-radius: 6px; white-space: nowrap;
            pointer-events: none; opacity: 0; transition: opacity 0.15s; z-index: 9999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        #sidebar.mini .arh-logout:hover::after { opacity: 1; }

        /* ── TOPBAR ── */
        #topbar {
            height: var(--topbar-h);
            background: #ffffff;
            box-shadow: 0 1px 0 #efeeeb, 0 4px 16px rgba(54,31,26,0.04);
            display: flex; align-items: center; padding: 0 24px;
            position: fixed; top: 0; left: var(--sidebar-w); right: 0;
            z-index: 1030; transition: left 0.28s cubic-bezier(0.4,0,0.2,1); gap: 14px;
        }
        #topbar.mini { left: var(--sidebar-mini); }

        /* ── CONTENT ── */
        #page-content {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
            transition: margin-left 0.28s cubic-bezier(0.4,0,0.2,1);
            background: #faf9f6;
        }
        #page-content.mini { margin-left: var(--sidebar-mini); }

        /* ── MOBILE ── */
        #sidebarOverlay {
            display: none; position: fixed; inset: 0;
            background: rgba(54,31,26,0.55); z-index: 1039; backdrop-filter: blur(2px);
        }
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); width: var(--sidebar-w) !important; transition: transform 0.28s ease; }
            #sidebar.mobile-open { transform: translateX(0) !important; }
            #topbar, #topbar.mini { left: 0 !important; }
            #page-content, #page-content.mini { margin-left: 0 !important; }
        }

        /* ── ALERT HERITAGE ── */
        .alert-heritage-success {
            background: #f0fdf4; border-left: 3px solid #16a34a;
            border-radius: 8px; padding: 12px 16px;
            display: flex; align-items: center; gap: 10px;
            font-family: 'Manrope', sans-serif; font-size: 0.85rem; color: #15803d;
        }
        .alert-heritage-warning {
            background: #fffbeb; border-left: 3px solid #d97706;
            border-radius: 8px; padding: 12px 16px;
            font-family: 'Manrope', sans-serif; font-size: 0.85rem; color: #b45309;
        }
        .alert-heritage-danger {
            background: #fef2f2; border-left: 3px solid #dc2626;
            border-radius: 8px; padding: 12px 16px;
            font-family: 'Manrope', sans-serif; font-size: 0.85rem; color: #b91c1c;
        }
    </style>

    {{-- Pre-apply mini state to prevent flash --}}
    <script>
        (function () {
            if (window.innerWidth > 768 && localStorage.getItem('arh_sidebar_mini') === 'true') {
                document.documentElement.setAttribute('data-sidebar-mini', '1');
            }
        })();
    </script>
    <style>
        [data-sidebar-mini="1"] #sidebar      { width: var(--sidebar-mini) !important; }
        [data-sidebar-mini="1"] #topbar       { left: var(--sidebar-mini) !important; }
        [data-sidebar-mini="1"] #page-content { margin-left: var(--sidebar-mini) !important; }
    </style>

    @yield('styles')
</head>
<body>

<div id="sidebarOverlay" onclick="closeSidebarMobile()"></div>

{{-- ════ SIDEBAR ════ --}}
<nav id="sidebar">

    {{-- Brand --}}
    <div class="arh-brand">
        <div class="arh-brand-logo">AH</div>
        <div class="arh-brand-text">
            <div class="arh-brand-title">ART-HUB</div>
            <div class="arh-brand-sub">Sanggar Cahaya Gumilang</div>
        </div>
        <button id="sidebarToggle" title="Collapse sidebar">
            <i class="bi bi-layout-sidebar-reverse" style="font-size:0.95rem;pointer-events:none;"></i>
        </button>
    </div>

    {{-- User --}}
    @auth
    <div class="arh-user">
        <div class="arh-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
        <div class="arh-user-info">
            <div class="arh-user-name">{{ Auth::user()->name }}</div>
            <div class="arh-user-role">Pimpinan Sanggar</div>
        </div>
    </div>
    @endauth

    {{-- Nav --}}
    @php
        $r = request();
        $menuGroups = [
            'UTAMA' => [
                ['Dashboard',         'bi-grid-1x2-fill',         'admin.dashboard',                  $r->routeIs('admin.dashboard')],
                ['Event Management',  'bi-calendar-check-fill',   'admin.events.index',               $r->routeIs('admin.events.*') && !$r->routeIs('admin.events.monitoring*')],
                ['Event Monitoring',  'bi-binoculars-fill',       'admin.events.monitoring',          $r->routeIs('admin.events.monitoring*')],
            ],
            'SDM & PRODUKSI' => [
                ['Personnel',         'bi-people-fill',           'admin.personnel.index',            $r->routeIs('admin.personnel.*')],
                ['Costume & Logistik','bi-bag-fill',              'admin.costumes.index',             $r->routeIs('admin.costumes.*')],
            ],
            'KEUANGAN' => [
                ['Daftar Booking',    'bi-journal-text',          'admin.bookings.index',             $r->routeIs('admin.bookings.index')],
                ['New Booking',       'bi-plus-circle-fill',      'admin.bookings.create',            $r->routeIs('admin.bookings.create')],
                ['DP Verification',   'bi-patch-check-fill',      'admin.bookings.dp_verification',   $r->routeIs('admin.bookings.dp_verification')],
                ['Payment Tracking',  'bi-receipt-cutoff',        'admin.payments.index',             $r->routeIs('admin.payments.*')],
                ['Financial Report',  'bi-graph-up-arrow',        'admin.financials.index',           $r->routeIs('admin.financials.index')],
                ['Post-Event Update', 'bi-clipboard2-check-fill', 'admin.financials.post_event_list', $r->routeIs('admin.financials.post_event_list')],
            ],
            'MANAJEMEN' => [
                ['Cancellation',      'bi-shield-exclamation',    'admin.cancellations.index',        $r->routeIs('admin.cancellations.*')],
            ],
        ];
    @endphp

    <ul class="arh-nav">
        @foreach($menuGroups as $section => $menus)
            <li><div class="arh-nav-section">{{ $section }}</div></li>
            @foreach($menus as [$label, $icon, $routeName, $isActive])
            <li>
                <a href="{{ route($routeName) }}"
                   class="arh-nav-link {{ $isActive ? 'active' : '' }}"
                   data-tooltip="{{ $label }}">
                    <i class="bi {{ $icon }} arh-nav-icon"></i>
                    <span class="arh-nav-label">{{ $label }}</span>
                </a>
            </li>
            @endforeach
        @endforeach
    </ul>

    {{-- Logout --}}
    <div class="arh-logout-wrap">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="arh-logout" data-tooltip="Logout">
                <i class="bi bi-box-arrow-left arh-nav-icon"></i>
                <span class="arh-logout-label">Logout</span>
            </button>
        </form>
    </div>
</nav>

{{-- ════ TOPBAR ════ --}}
<nav id="topbar">
    {{-- Mobile hamburger --}}
    <button onclick="openSidebarMobile()"
        style="width:34px;height:34px;border-radius:8px;border:1px solid #efeeeb;background:#faf9f6;color:#361f1a;cursor:pointer;flex-shrink:0;display:none;"
        class="mobile-menu-btn" id="mobileMenuBtn">
        <i class="bi bi-list" style="font-size:1.1rem;"></i>
    </button>

    <div style="flex:1;">
        <div style="font-family:'Noto Serif',serif;font-size:1rem;font-weight:600;color:#361f1a;line-height:1.2;">
            @yield('page_title', 'Admin Panel')
        </div>
        <div style="font-size:0.68rem;color:#827471;font-family:'Manrope',sans-serif;text-transform:uppercase;letter-spacing:0.05em;">
            @yield('page_subtitle')
        </div>
    </div>

    <div style="display:flex;align-items:center;gap:12px;">
        <span style="background:rgba(252,212,0,0.12);color:#705d00;font-size:0.68rem;font-weight:700;padding:4px 10px;border-radius:99px;font-family:'Manrope',sans-serif;text-transform:uppercase;letter-spacing:0.08em;">
            Admin
        </span>
        <span style="font-size:0.72rem;color:#827471;font-family:'Manrope',sans-serif;">
            {{ now()->translatedFormat('d M Y') }}
        </span>
    </div>
</nav>

{{-- ════ CONTENT ════ --}}
<div id="page-content">
    <main style="padding: 28px 28px;">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert-heritage-success" style="margin-bottom:16px;display:flex;align-items:center;gap:10px;">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;font-size:1rem;">×</button>
        </div>
        @endif
        @if(session('warning'))
        <div class="alert-heritage-warning" style="margin-bottom:16px;display:flex;align-items:center;gap:10px;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ session('warning') }}</span>
            <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;font-size:1rem;">×</button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert-heritage-danger" style="margin-bottom:16px;display:flex;align-items:center;gap:10px;">
            <i class="bi bi-x-octagon-fill"></i>
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;font-size:1rem;">×</button>
        </div>
        @endif

        @yield('content')
    </main>
</div>

<script>
    const sidebar   = document.getElementById('sidebar');
    const topbar    = document.getElementById('topbar');
    const content   = document.getElementById('page-content');
    const overlay   = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');
    const isMobile  = () => window.innerWidth <= 768;

    // Mobile menu btn visibility
    const mobileBtn = document.getElementById('mobileMenuBtn');
    if (mobileBtn) mobileBtn.style.display = isMobile() ? 'flex' : 'none';
    window.addEventListener('resize', () => {
        if (mobileBtn) mobileBtn.style.display = isMobile() ? 'flex' : 'none';
    });

    function applyMiniState(mini) {
        sidebar.classList.toggle('mini', mini);
        topbar.classList.toggle('mini', mini);
        content.classList.toggle('mini', mini);
        const icon = toggleBtn.querySelector('.bi');
        if (icon) icon.className = mini ? 'bi bi-layout-sidebar' : 'bi bi-layout-sidebar-reverse';
        document.documentElement.removeAttribute('data-sidebar-mini');
    }

    const isMini = localStorage.getItem('arh_sidebar_mini') === 'true';
    if (!isMobile()) applyMiniState(isMini);

    toggleBtn.addEventListener('click', () => {
        if (isMobile()) return;
        const nowMini = !sidebar.classList.contains('mini');
        applyMiniState(nowMini);
        localStorage.setItem('arh_sidebar_mini', String(nowMini));
    });

    function openSidebarMobile() {
        sidebar.classList.add('mobile-open');
        overlay.style.display = 'block';
    }
    function closeSidebarMobile() {
        sidebar.classList.remove('mobile-open');
        overlay.style.display = 'none';
    }

    // Auto-dismiss alerts after 5s
    document.querySelectorAll('[class^="alert-heritage"]').forEach(el => {
        setTimeout(() => el.remove(), 5000);
    });

    // Prevent double-submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            if (this.checkValidity()) {
                this.querySelectorAll('button[type="submit"]').forEach(btn => {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Memproses...';
                });
            }
        });
    });
</script>
@yield('scripts')
</body>
</html>
