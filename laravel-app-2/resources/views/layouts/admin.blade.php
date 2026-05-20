<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin | ART-HUB Sanggar Cahaya Gumilang')</title>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Noto+Serif:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                    },
                    keyframes: {
                        fadeUp: {
                            '0%': { opacity: '0', transform: 'translateY(15px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        scaleUp: {
                            '0%': { opacity: '0', transform: 'scale(0.97)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        }
                    },
                    animation: {
                        'fade-up': 'fadeUp 0.5s cubic-bezier(0.2, 0.8, 0.2, 1) forwards',
                        'scale-up': 'scaleUp 0.4s cubic-bezier(0.2, 0.8, 0.2, 1) forwards',
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --sidebar-w: 260px;
            --sidebar-mini: 64px;
            --topbar-h: 60px;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Manrope', sans-serif; background: #faf9f6; color: #1a1c1a; margin: 0; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24; font-size: 1.1rem; }
        [x-cloak] { display: none !important; }

        /* ── SIDEBAR ── */
        #sidebar {
            width: var(--sidebar-w);
            height: 100vh;            /* fixed height, bukan min-height */
            background: linear-gradient(180deg, #2b1915 0%, #1f0f0c 100%);
            border-right: 1px solid rgba(255,255,255,0.04);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; z-index: 1040;
            transition: width 0.3s cubic-bezier(0.2, 0, 0, 1);
            overflow: hidden;         /* clip horizontal saja */
            box-shadow: 4px 0 24px rgba(0,0,0,0.1);
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

        /* Nav — scrollable, selalu menyisakan ruang untuk footer sidebar */
        .arh-nav { list-style: none; padding: 16px 16px 0; margin: 0; overflow-y: auto; flex: 1; min-height: 0; }
        
        /* Custom Scrollbar for Nav */
        .arh-nav::-webkit-scrollbar { width: 4px; }
        .arh-nav::-webkit-scrollbar-track { background: transparent; }
        .arh-nav::-webkit-scrollbar-thumb { background: rgba(252,212,0,0.15); border-radius: 10px; }
        .arh-nav::-webkit-scrollbar-thumb:hover { background: rgba(252,212,0,0.3); }

        .arh-nav-section {
            font-size: 0.62rem; color: rgba(255,255,255,0.35); text-transform: uppercase;
            letter-spacing: 1.5px; padding: 18px 8px 8px; font-weight: 700; white-space: nowrap;
        }
        #sidebar.mini .arh-nav-section { height: 1px; background: rgba(255,255,255,0.06); margin: 12px 8px 8px; padding: 0; font-size: 0; }

        .arh-nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px; border-radius: 10px; margin-bottom: 6px;
            color: rgba(255,255,255,0.6); text-decoration: none;
            font-size: 0.85rem; font-weight: 500;
            transition: all 0.2s ease; white-space: nowrap; overflow: hidden;
            border: 1px solid transparent;
        }
        .arh-nav-link:hover { background: rgba(255,255,255,0.04); color: #fff; border-color: rgba(255,255,255,0.08); }
        .arh-nav-link.active {
            background: rgba(252,212,0,0.1);
            border-color: rgba(252,212,0,0.2);
            color: #fcd400; font-weight: 600;
        }
        .arh-nav-icon { 
            width: 26px; height: 26px; text-align: center; flex-shrink: 0; font-size: 1.1rem;
            display: flex; align-items: center; justify-content: center;
            border-radius: 6px; background: rgba(0,0,0,0.2); transition: all 0.2s ease;
        }
        .arh-nav-link:hover .arh-nav-icon { background: rgba(255,255,255,0.1); color: #fff; }
        .arh-nav-link.active .arh-nav-icon { background: #fcd400; color: #2b1915; box-shadow: 0 4px 12px rgba(252,212,0,0.3); }

        #sidebar.mini .arh-nav { padding: 16px 8px 0; }
        #sidebar.mini .arh-nav-link { justify-content: center; padding: 12px 0; border-color: transparent; }
        #sidebar.mini .arh-nav-icon { background: transparent; width: 32px; height: 32px; font-size: 1.25rem; }
        #sidebar.mini .arh-nav-link.active .arh-nav-icon { background: #fcd400; color: #2b1915; }
        #sidebar.mini .arh-nav-label { display: none; }

        /* Tooltip mini */
        #sidebar.mini .arh-nav-link::after {
            content: attr(data-tooltip); position: fixed;
            left: calc(var(--sidebar-mini) + 12px);
            background: #21120f; color: #faf9f6; font-size: 0.8rem; font-weight: 500;
            padding: 6px 14px; border-radius: 8px; white-space: nowrap;
            pointer-events: none; opacity: 0; transition: opacity 0.2s; z-index: 9999;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4); border: 1px solid rgba(252,212,0,0.15);
        }
        #sidebar.mini .arh-nav-link:hover::after { opacity: 1; }

        /* Logout – selalu nempel di bawah sidebar, tidak ikut scroll */
        .arh-logout-wrap { padding: 16px; flex-shrink: 0; border-top: 1px solid rgba(255,255,255,0.04); margin-top: auto; }
        .arh-logout {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px; border-radius: 10px; width: 100%;
            color: rgba(255,255,255,0.5); font-size: 0.85rem; font-weight: 500;
            background: none; border: 1px solid transparent; cursor: pointer;
            transition: all 0.2s ease; white-space: nowrap; overflow: hidden;
        }
        .arh-logout:hover { background: rgba(239,68,68,0.1); color: #fca5a5; border-color: rgba(239,68,68,0.2); }
        .arh-logout .arh-nav-icon {
            width: 26px; height: 26px; text-align: center; flex-shrink: 0; font-size: 1.1rem;
            display: flex; align-items: center; justify-content: center;
            border-radius: 6px; background: rgba(0,0,0,0.2); transition: all 0.2s ease;
        }
        .arh-logout:hover .arh-nav-icon { background: rgba(239,68,68,0.2); color: #fca5a5; }

        #sidebar.mini .arh-logout { justify-content: center; padding: 12px 0; border-color: transparent; }
        #sidebar.mini .arh-logout .arh-nav-icon { background: transparent; width: 32px; height: 32px; font-size: 1.25rem; }
        #sidebar.mini .arh-logout-label { display: none; }
        #sidebar.mini .arh-logout::after {
            content: 'Keluar'; position: fixed; left: calc(var(--sidebar-mini) + 12px);
            background: #21120f; color: #fca5a5; font-size: 0.8rem; font-weight: 500;
            padding: 6px 14px; border-radius: 8px; white-space: nowrap;
            pointer-events: none; opacity: 0; transition: opacity 0.2s; z-index: 9999;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4); border: 1px solid rgba(239,68,68,0.2);
        }
        #sidebar.mini .arh-logout:hover::after { opacity: 1; }

        /* Landing Page Link */
        .arh-landing {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px; border-radius: 10px; width: 100%;
            color: rgba(255,255,255,0.7); font-size: 0.85rem; font-weight: 500;
            text-decoration: none; transition: all 0.2s ease; white-space: nowrap; overflow: hidden;
            border: 1px solid transparent;
        }
        .arh-landing:hover { background: rgba(255,255,255,0.04); color: #ffffff; border-color: rgba(255,255,255,0.08); }
        .arh-landing .arh-nav-icon {
            width: 26px; height: 26px; text-align: center; flex-shrink: 0; font-size: 1.1rem;
            display: flex; align-items: center; justify-content: center;
            border-radius: 6px; background: rgba(0,0,0,0.2); transition: all 0.2s ease;
        }
        .arh-landing:hover .arh-nav-icon { background: rgba(255,255,255,0.1); color: #fff; }

        #sidebar.mini .arh-landing { justify-content: center; padding: 12px 0; border-color: transparent; }
        #sidebar.mini .arh-landing .arh-nav-icon { background: transparent; width: 32px; height: 32px; font-size: 1.25rem; }
        #sidebar.mini .arh-landing-label { display: none; }
        #sidebar.mini .arh-landing::after {
            content: 'Landing Page'; position: fixed; left: calc(var(--sidebar-mini) + 12px);
            background: #21120f; color: #ffffff; font-size: 0.8rem; font-weight: 500;
            padding: 6px 14px; border-radius: 8px; white-space: nowrap;
            pointer-events: none; opacity: 0; transition: opacity 0.2s; z-index: 9999;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.15);
        }
        #sidebar.mini .arh-landing:hover::after { opacity: 1; }

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
            #page-content, #page-content.mini { margin-left: 0 !important; padding-bottom: 0; }
            #page-content main { padding: 16px !important; }
            .mobile-menu-btn { display: flex !important; }
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

        /* ── AUTO-STAGGER PAGE ANIMATIONS ── */
        @keyframes fadeUp {
            0% { opacity: 0; transform: translateY(15px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes scaleUp {
            0% { opacity: 0; transform: scale(0.97); }
            100% { opacity: 1; transform: scale(1); }
        }

        /* Menyihir seluruh elemen dalam main content untuk muncul berurutan (Cascade Fade Up) */
        #page-content main > * {
            opacity: 0;
            animation: fadeUp 0.5s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }
        #page-content main > *:nth-child(1) { animation-delay: 0.05s; }
        #page-content main > *:nth-child(2) { animation-delay: 0.10s; }
        #page-content main > *:nth-child(3) { animation-delay: 0.15s; }
        #page-content main > *:nth-child(4) { animation-delay: 0.20s; }
        #page-content main > *:nth-child(5) { animation-delay: 0.25s; }
        #page-content main > *:nth-child(6) { animation-delay: 0.30s; }
        #page-content main > *:nth-child(7) { animation-delay: 0.35s; }
        #page-content main > *:nth-child(8) { animation-delay: 0.40s; }
        #page-content main > *:nth-child(n+9) { animation-delay: 0.45s; }

        /* Pengecualian animasi membesar untuk Notifikasi */
        #page-content main .alert-heritage-success, 
        #page-content main .alert-heritage-warning, 
        #page-content main .alert-heritage-danger {
            animation-name: scaleUp;
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

    <style>
        @media (max-width: 1024px) {
            .mobile-card-table {
                display: block;
                width: 100%;
                min-width: unset !important;
            }
            .mobile-card-table thead {
                display: none;
            }
            .mobile-card-table tbody, .mobile-card-table tr {
                display: block;
                width: 100%;
            }
            .mobile-card-table tr {
                margin-bottom: 1rem;
                background-color: var(--surface-container-lowest, #fff);
                border: 1px solid rgba(0,0,0,0.1);
                border-radius: 1rem;
                padding: 0.5rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            }
            .mobile-card-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                padding: 0.75rem 0.5rem;
                border: none !important;
                border-bottom: 1px dashed rgba(0,0,0,0.1) !important;
                text-align: right !important;
            }
            .mobile-card-table td:last-child {
                border-bottom: none !important;
            }
            .mobile-card-table td::before {
                content: attr(data-label);
                font-weight: 800;
                font-family: 'Inter', sans-serif;
                font-size: 0.65rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #64748b;
                text-align: left;
                margin-right: 1rem;
                flex-shrink: 0;
            }
            .mobile-card-table td > * {
                text-align: right;
            }
            .mobile-card-table td > .flex {
                justify-content: flex-end;
            }
            /* Override whitespace/padding issues for cards */
            .mobile-card-table td.px-6, .mobile-card-table td.py-4 {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
                padding-top: 0.75rem !important;
                padding-bottom: 0.75rem !important;
            }
        }
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
        $pendingBadge = \App\Models\Personnel::where('is_active', false)->count();
        $menuGroups = [
            'UTAMA' => [
                ['Dashboard',         'bi-grid-1x2-fill',         'admin.dashboard',                  $r->routeIs('admin.dashboard'),                  0],
                ['Event Management',  'bi-calendar-check-fill',   'admin.events.index',               $r->routeIs('admin.events.*') && !$r->routeIs('admin.events.monitoring*'), 0],
                ['Event Monitoring',  'bi-binoculars-fill',       'admin.events.monitoring',          $r->routeIs('admin.events.monitoring*'),          0],
            ],
            'SDM & PRODUKSI' => [
                ['Personnel',         'bi-people-fill',           'admin.personnel.index',            $r->routeIs('admin.personnel.*'),                 $pendingBadge],
                ['Costume & Logistik','bi-bag-fill',              'admin.costumes.index',             $r->routeIs('admin.costumes.*'),                  0],
            ],
            'KEUANGAN' => [
                ['Daftar Booking',    'bi-journal-text',          'admin.bookings.index',             $r->routeIs('admin.bookings.index'),              0],
                ['DP Verification',   'bi-patch-check-fill',      'admin.bookings.dp_verification',   $r->routeIs('admin.bookings.dp_verification'),    0],
                ['Payment Tracking',  'bi-receipt-cutoff',        'admin.payments.index',             $r->routeIs('admin.payments.*'),                  0],
                ['Financial Report',  'bi-graph-up-arrow',        'admin.financials.index',           $r->routeIs('admin.financials.index'),            0],
                ['Post-Event Update', 'bi-clipboard2-check-fill', 'admin.financials.post_event_list', $r->routeIs('admin.financials.post_event_list'),  0],
            ],
            'MANAJEMEN' => [
                ['Cancellation',      'bi-shield-exclamation',    'admin.cancellations.index',        $r->routeIs('admin.cancellations.*'),             0],
                ['Katalog Jasa',      'bi-collection-fill',       'admin.catalogs.index',             $r->routeIs('admin.catalogs.*'),                  0],
                ['CMS Landing Page',  'bi-window-sidebar',        'admin.cms.index',                  $r->routeIs('admin.cms.*'),                       0],
            ],
        ];
    @endphp

    <ul class="arh-nav">
        @foreach($menuGroups as $section => $menus)
            <li><div class="arh-nav-section">{{ $section }}</div></li>
            @foreach($menus as [$label, $icon, $routeName, $isActive, $badge])
            <li>
                <a href="{{ route($routeName) }}"
                   class="arh-nav-link {{ $isActive ? 'active' : '' }}"
                   data-tooltip="{{ $label }}">
                    <i class="bi {{ $icon }} arh-nav-icon"></i>
                    <span class="arh-nav-label">{{ $label }}</span>
                    @if($badge > 0)
                    <span style="
                        margin-left:auto; flex-shrink:0;
                        min-width:18px; height:18px; padding: 0 5px;
                        border-radius:9px; background:#f97316;
                        color:#fff; font-size:0.6rem; font-weight:800;
                        display:flex; align-items:center; justify-content:center;
                        line-height:1; font-family:'Manrope',sans-serif;
                    ">{{ $badge }}</span>
                    @endif
                </a>
            </li>
            @endforeach
        @endforeach
    </ul>

    {{-- Footer Actions (Landing Page & Logout) --}}
    <div class="arh-logout-wrap" style="display:flex; flex-direction:column; gap:4px;">
        <a href="{{ url('/') }}" class="arh-landing" data-tooltip="Lihat Landing Page">
            <i class="bi bi-window-desktop arh-nav-icon"></i>
            <span class="arh-landing-label">Landing Page</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="arh-logout" data-tooltip="Keluar">
                <i class="bi bi-box-arrow-left arh-nav-icon"></i>
                <span class="arh-logout-label">Keluar</span>
            </button>
        </form>
    </div>
</nav>

{{-- ════ TOPBAR ════ --}}
<nav id="topbar">
    <div style="flex:1; min-width:0;">
        <div style="font-family:'Noto Serif',serif;font-size:1rem;font-weight:600;color:#361f1a;line-height:1.2; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
            @yield('page_title', 'Admin Panel')
        </div>
        <div style="font-size:0.68rem;color:#827471;font-family:'Manrope',sans-serif;text-transform:uppercase;letter-spacing:0.05em; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
            @yield('page_subtitle')
        </div>
    </div>

    <div style="display:flex;align-items:center;gap:12px;">
        <span class="hidden md:inline-flex" style="background:rgba(252,212,0,0.12);color:#705d00;font-size:0.68rem;font-weight:700;padding:4px 10px;border-radius:99px;font-family:'Manrope',sans-serif;text-transform:uppercase;letter-spacing:0.08em;align-items:center;">
            Admin
        </span>
        <span class="hidden md:inline-block" style="font-size:0.72rem;color:#827471;font-family:'Manrope',sans-serif;">
            {{ now()->translatedFormat('d M Y') }}
        </span>

        {{-- Mobile hamburger moved to right --}}
        <button onclick="openSidebarMobile()"
            style="width:36px;height:36px;border-radius:8px;border:1px solid #e9e8e5;background:#f4f3f1;color:#361f1a;cursor:pointer;flex-shrink:0;display:none;align-items:center;justify-content:center;"
            class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="bi bi-list" style="font-size:1.3rem;"></i>
        </button>
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

    // Close sidebar when resizing from mobile → desktop
    window.addEventListener('resize', () => {
        if (!isMobile()) {
            closeSidebarMobile();
            applyMiniState(localStorage.getItem('arh_sidebar_mini') === 'true');
        }
    });

    function openSidebarMobile() {
        sidebar.classList.add('mobile-open');
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden'; // prevent background scroll
    }
    function closeSidebarMobile() {
        sidebar.classList.remove('mobile-open');
        overlay.style.display = 'none';
        document.body.style.overflow = '';
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

    // Convert data tables to mobile cards (CSS media query akan mengatur kapan view card aktif)
    document.addEventListener('DOMContentLoaded', function() {
        const tables = document.querySelectorAll('#page-content main table');
        tables.forEach(table => {
            // Hanya target tabel yang memiliki thead (data table)
            if (table.querySelector('thead') && !table.classList.contains('no-mobile-card')) {
                table.classList.add('mobile-card-table');
                
                // Ambil teks dari th thead untuk dijadikan label
                const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.innerText.trim());
                
                // Terapkan attr data-label ke setiap td di tbody
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    cells.forEach((cell, index) => {
                        if (headers[index]) {
                            cell.setAttribute('data-label', headers[index]);
                        }
                    });
                });
            }
        });
    });
</script>
@yield('scripts')
@stack('scripts')
</body>
</html>
