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

    {{-- Page-specific styles (e.g. Leaflet CSS) pushed from child views --}}
    @stack('styles')

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    fontSize: {
                        'golden-h1': ['42px', { lineHeight: '55px' }],
                        'golden-h2': ['26px', { lineHeight: '42px' }],
                        'golden-body': ['16px', { lineHeight: '26px' }],
                        'golden-caption': ['10px', { lineHeight: '16px' }],
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
            --sidebar-w: 300px;
            --sidebar-mini: 72px;
            --topbar-h: 70px;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Manrope', sans-serif; background: #faf9f6; color: #1a1c1a; margin: 0; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24; font-size: 1.1rem; }
        [x-cloak] { display: none !important; }

        /* Golden Ratio CSS Classes */
        .text-golden-h1 { font-size: 42px !important; line-height: 55px !important; }
        .text-golden-h2 { font-size: 26px !important; line-height: 42px !important; }
        .text-golden-body { font-size: 16px !important; line-height: 26px !important; }
        .text-golden-caption { font-size: 10px !important; line-height: 16px !important; }

        /* ── SIDEBAR ── */
        #sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            height: 100dvh; /* fix iOS safari */
            background: linear-gradient(185deg, #23120f 0%, #150907 100%);
            border-right: 1px solid rgba(255,255,255,0.05);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; z-index: 1050; /* high z-index to hover above all */
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            box-shadow: 6px 0 30px rgba(0,0,0,0.25);
        }
        #sidebar.mini { width: var(--sidebar-mini); }

        /* Brand */
        .arh-brand {
            display: flex; align-items: center; gap: 12px;
            padding: 0 20px; height: var(--topbar-h); flex-shrink: 0;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.03);
            transition: padding 0.3s;
        }
        .arh-brand-logo {
            width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
            background: linear-gradient(135deg, #fcd400, #bfa000);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Noto Serif', serif; font-weight: 800; font-size: 0.9rem; color: #23120f;
            box-shadow: 0 4px 10px rgba(252,212,0,0.25);
        }
        .arh-brand-text { flex: 1; min-width: 0; overflow: hidden; white-space: nowrap; transition: opacity 0.2s; }
        .arh-brand-title { font-family: 'Noto Serif', serif; font-size: 1.15rem; font-weight: 700; color: #fcd400; letter-spacing: 0.5px; }
        .arh-brand-sub { font-size: 0.58rem; color: rgba(255,255,255,0.45); text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600; margin-top: 1px; }
        #sidebar.mini .arh-brand { justify-content: center; padding: 0; }
        #sidebar.mini .arh-brand-text { display: none; }

        /* User row (now an anchor link) */
        .arh-user {
            display: flex; align-items: center; gap: 12px;
            padding: 12px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            margin: 16px;
            flex-shrink: 0;
            backdrop-filter: blur(12px);
            transition: all 0.3s ease;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }
        .arh-user::after {
            content: '↗ Lihat Website';
            position: absolute; bottom: 0; left: 0; right: 0;
            background: linear-gradient(135deg, rgba(252,212,0,0.9), rgba(180,152,0,0.9));
            color: #23120f; font-size: 0.55rem; font-weight: 800;
            text-align: center; padding: 4px 0; letter-spacing: 0.12em; text-transform: uppercase;
            transform: translateY(100%); transition: transform 0.25s ease;
            font-family: 'Manrope', sans-serif;
        }
        .arh-user:hover::after { transform: translateY(0); }
        .arh-user:hover {
            border-color: rgba(252,212,0,0.4);
            box-shadow: 0 4px 20px rgba(252,212,0,0.15);
            background: rgba(255,255,255,0.08);
        }
        .arh-avatar {
            width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, #fcd400, #e9c400);
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 0.8rem; color: #23120f;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .arh-user-info { overflow: hidden; white-space: nowrap; }
        .arh-user-name { font-size: 0.85rem; font-weight: 700; color: #faf9f6; }
        .arh-user-role { font-size: 0.6rem; color: rgba(252,212,0,0.85); text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-top: 1px; }
        #sidebar.mini .arh-user { justify-content: center; padding: 8px; margin: 16px 8px; border-radius: 10px; }
        #sidebar.mini .arh-user-info { display: none; }
        #sidebar.mini .arh-user::after { display: none; }
        #sidebar.mini .arh-user[data-tooltip]::before {
            content: attr(data-tooltip); position: fixed;
            left: calc(var(--sidebar-mini) + 12px);
            background: #180907; color: #fcd400; font-size: 0.8rem; font-weight: 600;
            padding: 8px 16px; border-radius: 8px; white-space: nowrap;
            pointer-events: none; opacity: 0; transition: opacity 0.2s; z-index: 9999;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid rgba(252,212,0,0.2);
        }
        #sidebar.mini .arh-user:hover[data-tooltip]::before { opacity: 1; }

        /* Nav - scrollable */
        .arh-nav { list-style: none; padding: 0 16px 16px; margin: 0; overflow-y: auto; flex: 1; min-height: 0; }
        .arh-nav::-webkit-scrollbar { width: 4px; }
        .arh-nav::-webkit-scrollbar-track { background: transparent; }
        .arh-nav::-webkit-scrollbar-thumb { background: rgba(252,212,0,0.1); border-radius: 10px; }
        .arh-nav::-webkit-scrollbar-thumb:hover { background: rgba(252,212,0,0.25); }

        .arh-nav-section {
            font-size: 0.68rem; color: rgba(255,255,255,0.3); text-transform: uppercase;
            letter-spacing: 2px; padding: 22px 8px 10px; font-weight: 800; white-space: nowrap;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            margin-bottom: 8px;
        }
        #sidebar.mini .arh-nav-section { height: 1px; background: rgba(255,255,255,0.05); margin: 16px 8px 12px; padding: 0; font-size: 0; border: none; }

        .arh-nav-link {
            position: relative;
            display: flex; align-items: center; gap: 12px;
            padding: 12px 14px; border-radius: 10px; margin-bottom: 6px;
            color: rgba(255,255,255,0.65); text-decoration: none;
            font-size: 0.9rem; font-weight: 500;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); white-space: nowrap; overflow: hidden;
            border: 1px solid transparent;
        }
        .arh-nav-link::before {
            content: ''; position: absolute; left: 0; top: 25%; height: 50%; width: 3px;
            background: #fcd400; border-radius: 0 4px 4px 0; opacity: 0;
            transition: opacity 0.2s, transform 0.2s; transform: scaleY(0.3);
        }
        .arh-nav-link:hover::before { opacity: 0.5; transform: scaleY(1); }
        .arh-nav-link.active::before { opacity: 1; transform: scaleY(1.3); }

        .arh-nav-link:hover {
            background: rgba(255,255,255,0.03);
            color: #fff;
            padding-left: 18px;
        }
        #sidebar.mini .arh-nav-link:hover { padding-left: 14px; }

        .arh-nav-link.active {
            background: linear-gradient(90deg, rgba(252,212,0,0.12) 0%, rgba(252,212,0,0.02) 100%);
            border-color: rgba(252,212,0,0.2);
            color: #fcd400; font-weight: 700;
        }
        .arh-nav-icon { 
            width: 28px; height: 28px; text-align: center; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px; background: rgba(0,0,0,0.3); transition: all 0.25s ease;
        }
        .arh-nav-icon svg {
            width: 16px !important; height: 16px !important;
            transition: all 0.25s ease;
        }
        .arh-nav-link:hover .arh-nav-icon { background: rgba(252,212,0,0.15); color: #fcd400; transform: scale(1.05); }
        .arh-nav-link.active .arh-nav-icon { background: #fcd400; color: #23120f; box-shadow: 0 0 10px rgba(252,212,0,0.3); }

        #sidebar.mini .arh-nav { padding: 0 8px 16px; }
        #sidebar.mini .arh-nav-link { justify-content: center; padding: 12px 0; border-color: transparent; }
        #sidebar.mini .arh-nav-icon { background: transparent; width: 36px; height: 36px; }
        #sidebar.mini .arh-nav-icon svg { width: 20px !important; height: 20px !important; }
        #sidebar.mini .arh-nav-link.active .arh-nav-icon { background: #fcd400; color: #23120f; border-radius: 10px; }
        #sidebar.mini .arh-nav-label { display: none; }

        /* Tooltip mini */
        #sidebar.mini .arh-nav-link::after {
            content: attr(data-tooltip); position: fixed;
            left: calc(var(--sidebar-mini) + 12px);
            background: #180907; color: #faf9f6; font-size: 0.8rem; font-weight: 600;
            padding: 8px 16px; border-radius: 8px; white-space: nowrap;
            pointer-events: none; opacity: 0; transition: opacity 0.2s; z-index: 9999;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid rgba(252,212,0,0.2);
        }
        #sidebar.mini .arh-nav-link:hover::after { opacity: 1; }

        /* Bottom Action wrap */
        .arh-logout-wrap { padding: 16px; flex-shrink: 0; border-top: 1px solid rgba(255,255,255,0.04); margin-top: auto; background: rgba(0,0,0,0.15); }
        #sidebar.mini .arh-logout-wrap { padding: 12px 8px; }

        .arh-logout, .arh-landing {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 14px; border-radius: 10px; width: 100%;
            color: rgba(255,255,255,0.65); font-size: 0.88rem; font-weight: 600;
            background: none; border: 1px solid transparent; cursor: pointer;
            transition: all 0.25s ease; white-space: nowrap; overflow: hidden;
            text-decoration: none;
        }
        .arh-landing:hover { background: rgba(255,255,255,0.03); color: #fff; }
        .arh-logout:hover { background: rgba(239,68,68,0.1); color: #fca5a5; border-color: rgba(239,68,68,0.2); }
        
        .arh-landing .arh-nav-icon, .arh-logout .arh-nav-icon {
            width: 28px; height: 28px; text-align: center; flex-shrink: 0; font-size: 1.2rem;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px; background: rgba(0,0,0,0.3); transition: all 0.25s ease;
        }
        .arh-landing:hover .arh-nav-icon { background: rgba(252,212,0,0.15); color: #fcd400; }
        .arh-logout:hover .arh-nav-icon { background: rgba(239,68,68,0.2); color: #fca5a5; }

        #sidebar.mini .arh-landing, #sidebar.mini .arh-logout { justify-content: center; padding: 12px 0; border-color: transparent; }
        #sidebar.mini .arh-landing .arh-nav-icon, #sidebar.mini .arh-logout .arh-nav-icon { background: transparent; width: 36px; height: 36px; font-size: 1.35rem; }
        #sidebar.mini .arh-landing-label, #sidebar.mini .arh-logout-label { display: none; }
        
        #sidebar.mini .arh-landing::after {
            content: 'Landing Page'; position: fixed; left: calc(var(--sidebar-mini) + 12px);
            background: #180907; color: #ffffff; font-size: 0.8rem; font-weight: 600;
            padding: 8px 16px; border-radius: 8px; white-space: nowrap;
            pointer-events: none; opacity: 0; transition: opacity 0.2s; z-index: 9999;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid rgba(252,212,0,0.2);
        }
        #sidebar.mini .arh-landing:hover::after { opacity: 1; }

        #sidebar.mini .arh-logout::after {
            content: 'Keluar'; position: fixed; left: calc(var(--sidebar-mini) + 12px);
            background: #180907; color: #fca5a5; font-size: 0.8rem; font-weight: 600;
            padding: 8px 16px; border-radius: 8px; white-space: nowrap;
            pointer-events: none; opacity: 0; transition: opacity 0.2s; z-index: 9999;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid rgba(239,68,68,0.2);
        }
        #sidebar.mini .arh-logout:hover::after { opacity: 1; }

        /* ── TOPBAR ── */
        #topbar {
            height: var(--topbar-h);
            background: #ffffff;
            border-bottom: 1px solid #efeeeb;
            display: flex; align-items: center; padding: 0 28px;
            position: fixed; top: 0; left: var(--sidebar-w); right: 0;
            z-index: 1030; transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1); gap: 16px;
        }
        #topbar.mini { left: var(--sidebar-mini); }

        /* ── CONTENT ── */
        #page-content {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #faf9f6;
        }
        #page-content.mini { margin-left: var(--sidebar-mini); }

        /* ── MOBILE ── */
        #sidebarOverlay {
            display: none; position: fixed; inset: 0;
            background: rgba(35,18,15,0.6); z-index: 1049; backdrop-filter: blur(4px);
            transition: opacity 0.3s ease;
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .scrollbar-none::-webkit-scrollbar {
            display: none !important;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        .scrollbar-none {
            -ms-overflow-style: none !important;  /* IE and Edge */
            scrollbar-width: none !important;  /* Firefox */
        }
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); width: var(--sidebar-w) !important; transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
            #sidebar.mobile-open { transform: translateX(0) !important; }
            #topbar, #topbar.mini { left: 0 !important; padding: 0 16px !important; }
            #page-content, #page-content.mini { margin-left: 0 !important; }
            #page-content main { padding: 16px !important; }
            .mobile-menu-btn { display: flex !important; }
            
            /* Global Responsive overrides on mobile */
            .grid { gap: 12px !important; }
            .p-6 { padding: 16px !important; }
            .p-5 { padding: 12px !important; }
            .mb-8 { margin-bottom: 20px !important; }
            .mb-6 { margin-bottom: 16px !important; }
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

    @yield('styles')
</head>
<body>

<div id="sidebarOverlay" onclick="closeSidebarMobile()"></div>

{{-- ════ SIDEBAR ════ --}}
<nav id="sidebar">

    {{-- Brand --}}
    @php
        $siteContents = \Illuminate\Support\Facades\Cache::remember(
            'site_contents',
            3600,
            fn() => \App\Models\SiteContent::pluck('value', 'key')->toArray()
        );
        $sanggarName = $siteContents['sanggar_name'] ?? 'Cahaya Gumilang';
        $sanggarLogo = $siteContents['sanggar_logo'] ?? null;
    @endphp
    <div class="arh-brand">
        @if($sanggarLogo)
            <img src="{{ asset('storage/' . $sanggarLogo) }}" alt="Logo" class="arh-brand-logo" style="background: transparent; object-fit: contain; box-shadow: none;">
        @else
            <div class="arh-brand-logo">AH</div>
        @endif
        <div class="arh-brand-text">
            <div class="arh-brand-title">ART-HUB</div>
            <div class="arh-brand-sub">{{ $sanggarName }}</div>
        </div>
    </div>

    {{-- User: klik → landing page (tanpa logout) --}}
    @auth
    <a href="{{ url('/') }}" class="arh-user" title="Lihat Landing Page" data-tooltip="Lihat Landing Page" style="text-decoration:none;cursor:pointer;">
        <div class="arh-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
        <div class="arh-user-info">
            <div class="arh-user-name">{{ Auth::user()->name }}</div>
            <div class="arh-user-role" style="color:rgba(252,212,0,0.7);font-size:0.55rem;margin-top:2px;">✦ Klik → Lihat Website</div>
        </div>
    </a>
    @endauth

    {{-- Nav --}}
    @php
        $r = request();
        $pendingBadge = \App\Models\Personnel::where('status', 'pending_verification')->count();
        $pendingBookingBadge = \App\Models\Booking::where('status', 'pending')->count();
        $pendingDpVerificationBadge = \App\Models\Booking::where('status', 'pending')->whereNotNull('payment_proof')->count();
        $pendingPostEventBadge = \App\Models\Event::where(function ($q) {
                $q->where('event_date', '<', now()->toDateString())
                  ->orWhere('status', 'completed');
            })
            ->whereHas('financialRecord', function ($q) {
                $q->whereDoesntHave('operationalCosts');
            })
            ->count();
        $menuGroups = [
            'UTAMA' => [
                ['Dashboard',         'layout-dashboard',         'admin.dashboard',                  $r->routeIs('admin.dashboard'),                  0],
                ['Event Management',  'calendar-days',            'admin.events.index',               $r->routeIs('admin.events.*') && !$r->routeIs('admin.events.monitoring*'), 0],
                ['Event Monitoring',  'eye',                      'admin.events.monitoring',          $r->routeIs('admin.events.monitoring*'),          0],
            ],
            'SDM & PRODUKSI' => [
                ['Personnel',         'users',                    'admin.personnel.index',            $r->routeIs('admin.personnel.*'),                 $pendingBadge],
                ['Jadwal Latihan',    'music',                    'admin.rehearsals.index',           $r->routeIs('admin.rehearsals.*'),                0],
                ['Costume & Logistik','package',                  'admin.costumes.index',             $r->routeIs('admin.costumes.*'),                  0],
            ],
            'KEUANGAN' => [
                ['Daftar Booking',    'book-open',                'admin.bookings.index',             $r->routeIs('admin.bookings.index'),              $pendingBookingBadge],
                ['DP Verification',   'check-circle',             'admin.bookings.dp_verification',   $r->routeIs('admin.bookings.dp_verification'),    $pendingDpVerificationBadge],
                ['Payment Tracking',  'receipt',                  'admin.payments.index',             $r->routeIs('admin.payments.*'),                  0],
                ['Financial Report',  'trending-up',              'admin.financials.index',           $r->routeIs('admin.financials.index') || $r->routeIs('admin.financials.post_event_list'), $pendingPostEventBadge],
            ],
            'MANAJEMEN' => [
                ['Cancellation',      'shield-alert',             'admin.cancellations.index',        $r->routeIs('admin.cancellations.*'),             0],
                ['Katalog Jasa',      'folder-open',              'admin.catalogs.index',             $r->routeIs('admin.catalogs.*'),                  0],
                ['CMS Landing Page',  'layout',                   'admin.cms.index',                  $r->routeIs('admin.cms.*'),                       0],
                ['Pengaturan Profil', 'user-cog',                 'admin.profile.edit',               $r->routeIs('admin.profile.*'),                   0],
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
                    <span class="arh-nav-icon"><i data-lucide="{{ $icon }}"></i></span>
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

    {{-- Footer Actions (Logout) --}}
    <div class="arh-logout-wrap" style="display:flex; flex-direction:column; gap:4px;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="arh-logout" data-tooltip="Keluar">
                <span class="arh-nav-icon"><i data-lucide="log-out"></i></span>
                <span class="arh-logout-label">Keluar</span>
            </button>
        </form>
    </div>
</nav>

{{-- ════ TOPBAR ════ --}}
<nav id="topbar">
    <button id="sidebarToggle" type="button" class="hidden md:flex items-center justify-center mr-2 w-9 h-9 rounded-lg border border-outline-variant/30 bg-white text-primary hover:border-secondary hover:text-secondary hover:bg-secondary/5 transition-all shadow-sm flex-shrink-0" title="Toggle Sidebar">
        <i class="bi bi-layout-sidebar-reverse" style="font-size: 1.1rem;"></i>
    </button>
    
    <div style="flex:1; min-width:0;">
        <div style="font-family:'Noto Serif',serif;font-size:1.1rem;font-weight:700;color:#361f1a;line-height:1.2; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
            @yield('page_title', 'Admin Panel')
        </div>
        <div style="font-size:0.72rem;color:#827471;font-family:'Manrope',sans-serif;text-transform:uppercase;letter-spacing:0.05em; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top: 2px;">
            @yield('page_subtitle')
        </div>
    </div>

    <div style="display:flex;align-items:center;gap:12px;">
        
        {{-- Notification Dropdown --}}
        @php
            $unreadNotifications = Auth::user()->unreadNotifications;
            $unreadCount = $unreadNotifications->count();
        @endphp
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.away="open = false" class="relative p-2 text-on-surface-variant hover:text-primary transition-colors flex items-center justify-center rounded-full hover:bg-surface-container">
                <i class="bi bi-bell-fill" style="font-size: 1.1rem;"></i>
                @if($unreadCount > 0)
                    <span class="absolute top-1 right-1 flex items-center justify-center w-4 h-4 text-[0.6rem] font-bold text-white bg-red-500 border-2 border-white rounded-full">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </button>

            <div x-show="open" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-lg border border-outline-variant/30 overflow-hidden z-50">
                    <div class="px-4 py-3 border-b border-outline-variant/10 flex justify-between items-center bg-surface-container-low rounded-t-2xl">
                        <span class="font-bold text-primary text-sm">Notifikasi Baru</span>
                        @if($unreadCount > 0)
                        <form action="{{ route('notifications.read_all') }}" method="POST" class="m-0 inline">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-secondary hover:text-primary transition-colors">Tandai Semua Dibaca</button>
                        </form>
                        @endif
                    </div>
                <div class="max-h-80 overflow-y-auto">
                    @forelse($unreadNotifications as $notification)
                        <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 hover:bg-surface-container-low border-b border-outline-variant/10 transition-colors">
                            <p class="text-xs text-on-surface font-semibold mb-1">{{ $notification->data['message'] ?? 'Ada notifikasi baru' }}</p>
                            <span class="text-[0.65rem] text-outline flex items-center gap-1">
                                <i class="bi bi-clock"></i> {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </a>
                    @empty
                        <div class="px-4 py-6 text-center text-outline text-sm flex flex-col items-center">
                            <i class="bi bi-bell-slash text-2xl mb-2 opacity-50"></i>
                            Tidak ada notifikasi baru
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <span class="hidden md:inline-flex" style="background:rgba(252,212,0,0.12);color:#705d00;font-size:0.68rem;font-weight:700;padding:4px 10px;border-radius:99px;font-family:'Manrope',sans-serif;text-transform:uppercase;letter-spacing:0.08em;align-items:center;">
            Admin
        </span>
        <span class="hidden md:inline-block" style="font-size:0.72rem;color:#827471;font-family:'Manrope',sans-serif;font-weight:500;">
            {{ now()->translatedFormat('d M Y') }}
        </span>

        {{-- Mobile hamburger kustom --}}
        <button onclick="openSidebarMobile()"
            style="width:40px;height:40px;border-radius:8px;border:1px solid #e9e8e5;background:#ffffff;color:#361f1a;cursor:pointer;flex-shrink:0;display:none;align-items:center;justify-content:center;box-shadow: 0 2px 8px rgba(0,0,0,0.05);"
            class="mobile-menu-btn hover:border-secondary hover:text-secondary transition-all" id="mobileMenuBtn">
            <i class="bi bi-list" style="font-size:1.4rem;"></i>
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

    // Global Form Confirmation using SweetAlert2
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.tagName !== 'FORM') return;

        const confirmMsg = form.getAttribute('data-confirm');
        if (confirmMsg && !form._confirmed) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Tindakan',
                text: confirmMsg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#361f1a', // Maroon
                cancelButtonColor: '#827471',  // Outline grey
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-2xl border border-outline-variant/30 shadow-xl font-body',
                    title: 'font-headline text-lg font-bold text-primary',
                    htmlContainer: 'font-body text-sm text-on-surface-variant',
                    confirmButton: 'px-4 py-2.5 rounded-lg font-label font-bold text-xs uppercase tracking-wider',
                    cancelButton: 'px-4 py-2.5 rounded-lg font-label font-bold text-xs uppercase tracking-wider'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form._confirmed = true;
                    // Trigger modern submit flow to run validation and subsequent submit handlers
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                }
            });
        }
    });

    // Prevent double-submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (e.defaultPrevented) return;
            if (this.checkValidity()) {
                this.querySelectorAll('button[type="submit"]').forEach(btn => {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Memproses...';
                });
            }
        });
    });

    // Persist sidebar scroll position
    const sidebarNav = document.querySelector('.arh-nav');
    if (sidebarNav) {
        // Restore scroll position
        const savedScroll = sessionStorage.getItem('arh_sidebar_scroll');
        if (savedScroll) {
            sidebarNav.scrollTop = parseInt(savedScroll, 10);
        }

        // Save scroll position before navigating away
        window.addEventListener('beforeunload', () => {
            sessionStorage.setItem('arh_sidebar_scroll', sidebarNav.scrollTop);
        });
    }

    // Initialize Lucide Icons
    lucide.createIcons();


</script>
@yield('scripts')
@stack('scripts')
</body>
</html>
