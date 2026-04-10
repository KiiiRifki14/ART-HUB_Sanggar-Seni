<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ART-HUB | Sanggar Cahaya Gumilang')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        :root {
            --arh-sidebar-full: 240px;
            --arh-sidebar-mini: 60px;
            --arh-topbar-h: 52px;
            --arh-gold: #c5a059;
            --arh-gold-dim: rgba(197,160,89,0.12);
            --arh-bg: #0f0f0f;
            --arh-sidebar-bg: #141414;
            --arh-border: #242424;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--arh-bg); margin: 0; }

        /* ━━━━━━━━━━━━━━━━ SIDEBAR ━━━━━━━━━━━━━━━━ */
        #sidebar {
            width: var(--arh-sidebar-full);
            min-height: 100vh;
            background: var(--arh-sidebar-bg);
            border-right: 1px solid var(--arh-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 1040;
            transition: width 0.28s cubic-bezier(0.4,0,0.2,1);
            overflow: hidden;  /* needed for smooth width animation */
        }
        #sidebar.mini { width: var(--arh-sidebar-mini); }

        /* ━━━━━━━━━━━━━━━━ BRAND AREA ━━━━━━━━━━━━━━━━ */
        .arh-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 12px;
            height: 56px;
            border-bottom: 1px solid var(--arh-border);
            flex-shrink: 0;
            overflow: visible; /* don't clip toggle */
        }
        .arh-brand-logo {
            width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
            background: linear-gradient(135deg, #c5a059, #8a6e30);
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 0.72rem; color: #000; letter-spacing: -0.5px;
            transition: opacity 0.2s;
        }
        .arh-brand-text {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            white-space: nowrap;
            transition: opacity 0.2s, max-width 0.28s;
            max-width: 200px;
        }
        .arh-brand-title { font-size: 0.95rem; font-weight: 700; color: var(--arh-gold); letter-spacing: 1.5px; }
        .arh-brand-sub { font-size: 0.58rem; color: #666; text-transform: uppercase; letter-spacing: 0.8px; }

        /* Hamburger toggle – always visible, always at same position */
        #sidebarToggle {
            width: 32px; height: 32px; border-radius: 7px; flex-shrink: 0;
            border: 1px solid #333; background: #1c1c1c; color: #888;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s; margin-left: auto;
        }
        #sidebarToggle:hover { background: var(--arh-gold-dim); color: var(--arh-gold); border-color: var(--arh-gold); }
        #sidebarToggle .bi { font-size: 0.95rem; pointer-events: none; }

        /* In MINI: hide brand text + logo, center the toggle */
        #sidebar.mini .arh-brand { justify-content: center; padding: 0; }
        #sidebar.mini .arh-brand-logo { display: none; }
        #sidebar.mini .arh-brand-text { display: none; }
        #sidebar.mini #sidebarToggle { margin-left: 0; }

        /* ━━━━━━━━━━━━━━━━ USER ROW ━━━━━━━━━━━━━━━━ */
        .arh-user {
            display: flex; align-items: center; gap: 10px;
            padding: 0 12px; height: 48px;
            border-bottom: 1px solid var(--arh-border);
            flex-shrink: 0; overflow: hidden;
        }
        .arh-avatar {
            width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, #c5a059, #8a6e30);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.7rem; color: #000;
        }
        .arh-user-info { overflow: hidden; white-space: nowrap; }
        .arh-user-name { font-size: 0.78rem; font-weight: 600; color: #eee; }
        .arh-user-role { font-size: 0.6rem; color: var(--arh-gold); }

        #sidebar.mini .arh-user { justify-content: center; padding: 0; }
        #sidebar.mini .arh-user-info { display: none; }

        /* ━━━━━━━━━━━━━━━━ NAV LINKS ━━━━━━━━━━━━━━━━ */
        .arh-nav { list-style: none; padding: 8px 8px 0; margin: 0; overflow-y: auto; flex: 1; }

        .arh-nav-section {
            font-size: 0.58rem; color: #444; text-transform: uppercase;
            letter-spacing: 1px; padding: 12px 4px 3px; font-weight: 600;
            white-space: nowrap;
        }
        /* Mini: section divider becomes a thin line */
        #sidebar.mini .arh-nav-section {
            font-size: 0; height: 1px;
            background: var(--arh-border);
            margin: 8px 8px 4px;
            padding: 0;
        }

        .arh-nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 8px;
            border-radius: 8px; margin-bottom: 2px;
            color: #999; text-decoration: none;
            font-size: 0.83rem; font-weight: 500;
            transition: background 0.15s, color 0.15s;
            white-space: nowrap; overflow: hidden;
            position: relative;
        }
        .arh-nav-link:hover { background: var(--arh-gold-dim); color: #e0c882; }
        .arh-nav-link.active {
            background: linear-gradient(90deg, rgba(197,160,89,0.22), rgba(197,160,89,0.05));
            color: var(--arh-gold);
            border-left: 3px solid var(--arh-gold);
            padding-left: 5px;
        }
        .arh-nav-icon { font-size: 1.05rem; width: 22px; text-align: center; flex-shrink: 0; }
        .arh-nav-label { min-width: 0; overflow: hidden; }

        /* Mini: center icon, hide label */
        #sidebar.mini .arh-nav { padding: 8px 4px 0; }
        #sidebar.mini .arh-nav-link {
            justify-content: center;
            padding: 10px 0;
            border-left: none !important; /* remove active left border */
        }
        #sidebar.mini .arh-nav-link.active {
            border-left: none;
            padding-left: 0;
            outline: 1px solid rgba(197,160,89,0.4);
        }
        #sidebar.mini .arh-nav-label { display: none; }

        /* Tooltip on hover in mini mode */
        #sidebar.mini .arh-nav-link::after {
            content: attr(data-tooltip);
            position: fixed;
            left: calc(var(--arh-sidebar-mini) + 10px);
            background: #222; color: #eee;
            font-size: 0.78rem; font-weight: 500;
            padding: 5px 12px; border-radius: 6px;
            border: 1px solid #333; white-space: nowrap;
            pointer-events: none; opacity: 0;
            transition: opacity 0.15s; z-index: 9999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        }
        #sidebar.mini .arh-nav-link:hover::after { opacity: 1; }

        /* ━━━━━━━━━━━━━━━━ LOGOUT ━━━━━━━━━━━━━━━━ */
        .arh-logout-wrap {
            padding: 8px 8px 16px;
            border-top: 1px solid var(--arh-border);
            flex-shrink: 0;
        }
        .arh-logout {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 8px; border-radius: 8px;
            color: #ef4444; font-size: 0.83rem; font-weight: 500;
            transition: background 0.15s; white-space: nowrap; overflow: hidden;
            background: none; border: none; width: 100%; cursor: pointer;
            position: relative;
        }
        .arh-logout:hover { background: rgba(239,68,68,0.1); color: #ff7a7a; }
        #sidebar.mini .arh-logout { justify-content: center; padding: 10px 0; }
        #sidebar.mini .arh-logout-label { display: none; }
        #sidebar.mini .arh-logout::after {
            content: 'Logout';
            position: fixed;
            left: calc(var(--arh-sidebar-mini) + 10px);
            background: #222; color: #ef4444;
            font-size: 0.78rem; font-weight: 500;
            padding: 5px 12px; border-radius: 6px;
            border: 1px solid #333; white-space: nowrap;
            pointer-events: none; opacity: 0;
            transition: opacity 0.15s; z-index: 9999;
        }
        #sidebar.mini .arh-logout:hover::after { opacity: 1; }

        /* ━━━━━━━━━━━━━━━━ TOPBAR ━━━━━━━━━━━━━━━━ */
        #topbar {
            height: var(--arh-topbar-h);
            background: #121212;
            border-bottom: 1px solid var(--arh-border);
            display: flex; align-items: center;
            padding: 0 20px;
            position: fixed;
            top: 0;
            left: var(--arh-sidebar-full);
            right: 0;
            z-index: 1030;
            transition: left 0.28s cubic-bezier(0.4,0,0.2,1);
            gap: 14px;
        }
        #topbar.mini { left: var(--arh-sidebar-mini); }

        /* ━━━━━━━━━━━━━━━━ CONTENT ━━━━━━━━━━━━━━━━ */
        #page-content {
            margin-left: var(--arh-sidebar-full);
            padding-top: var(--arh-topbar-h);
            min-height: 100vh;
            transition: margin-left 0.28s cubic-bezier(0.4,0,0.2,1);
        }
        #page-content.mini { margin-left: var(--arh-sidebar-mini); }

        /* ━━━━━━━━━━━━━━━━ MOBILE ━━━━━━━━━━━━━━━━ */
        #sidebarOverlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.6); z-index: 1039;
        }
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); width: var(--arh-sidebar-full) !important; transition: transform 0.28s ease; }
            #sidebar.mobile-open { transform: translateX(0) !important; }
            #topbar, #topbar.mini { left: 0 !important; }
            #page-content, #page-content.mini { margin-left: 0 !important; }
        }
    </style>

    {{-- ⚡ Apply mini state BEFORE render to prevent flash on page navigation --}}
    <script>
        (function () {
            if (window.innerWidth > 768 && localStorage.getItem('arh_sidebar_mini') === 'true') {
                document.documentElement.setAttribute('data-sidebar-mini', '1');
            }
        })();
    </script>
    <style>
        /* Pre-apply mini classes via data attribute to prevent flash */
        [data-sidebar-mini="1"] #sidebar { width: var(--arh-sidebar-mini) !important; }
        [data-sidebar-mini="1"] #topbar  { left: var(--arh-sidebar-mini) !important; }
        [data-sidebar-mini="1"] #page-content { margin-left: var(--arh-sidebar-mini) !important; }
    </style>

    @yield('styles')
</head>
<body>

<div id="sidebarOverlay" onclick="closeSidebarMobile()"></div>

{{-- ════ SIDEBAR ════ --}}
<nav id="sidebar">

    {{-- Brand + Hamburger (always in brand row) --}}
    <div class="arh-brand">
        <div class="arh-brand-logo">AH</div>
        <div class="arh-brand-text">
            <div class="arh-brand-title">ART-HUB</div>
            <div class="arh-brand-sub">Sanggar Cahaya Gumilang</div>
        </div>
        <button id="sidebarToggle" title="Expand / Collapse">
            <i class="bi bi-layout-sidebar-reverse"></i>
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
                // ['Event Monitoring',  'bi-binoculars-fill',       'admin.events.monitoring',          $r->routeIs('admin.events.monitoring*')],
            ],
            'SDM & PRODUKSI' => [
                ['Personnel',         'bi-people-fill',           'admin.personnel.index',            $r->routeIs('admin.personnel.*')],
                // ['Costume & Logistik','bi-bag-fill',              'admin.costumes.index',             $r->routeIs('admin.costumes.*')],
            ],
            /*
            'KEUANGAN' => [
                ['New Booking',       'bi-plus-circle-fill',      'admin.bookings.create',            $r->routeIs('admin.bookings.create')],
                ['DP Verification',   'bi-patch-check-fill',      'admin.bookings.dp_verification',   $r->routeIs('admin.bookings.dp_verification')],
                ['Payment Tracking',  'bi-receipt-cutoff',        'admin.payments.index',             $r->routeIs('admin.payments.*')],
                ['Financial Report',  'bi-graph-up-arrow',        'admin.financials.index',           $r->routeIs('admin.financials.index')],
                ['Post-Event Update', 'bi-clipboard2-check-fill', 'admin.financials.post_event_list', $r->routeIs('admin.financials.post_event_list')],
            ],
            'MANAJEMEN' => [
                ['Cancellation',      'bi-shield-exclamation',    'admin.cancellations.index',        $r->routeIs('admin.cancellations.*')],
            ],
            */
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
    {{-- Mobile-only hamburger in topbar --}}
    <button class="d-flex d-md-none align-items-center justify-content-center"
            style="width:34px;height:34px;border-radius:7px;border:1px solid #333;background:#1c1c1c;color:#888;cursor:pointer;flex-shrink:0;"
            onclick="openSidebarMobile()">
        <i class="bi bi-list" style="font-size:1.1rem;"></i>
    </button>

    <div class="flex-grow-1">
        <h6 class="mb-0 fw-bold text-white" style="font-size:0.92rem;">@yield('page_title', 'Admin Panel')</h6>
        <div class="text-secondary" style="font-size:0.68rem;">@yield('page_subtitle')</div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="badge d-none d-sm-inline-flex"
              style="background:rgba(197,160,89,0.15); color:#c5a059; border:1px solid rgba(197,160,89,0.35); font-size:0.68rem;">
            <i class="bi bi-shield-lock-fill me-1"></i>Admin
        </span>
        <span class="text-secondary d-none d-lg-block" style="font-size:0.72rem;">{{ now()->format('d M Y') }}</span>
    </div>
</nav>

{{-- ════ CONTENT ════ --}}
<div id="page-content">
    <main class="p-4">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible border-0 d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i><span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible border-0 d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i><span>{{ session('warning') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible border-0 d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-x-octagon-fill fs-5"></i><span>{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar   = document.getElementById('sidebar');
    const topbar    = document.getElementById('topbar');
    const content   = document.getElementById('page-content');
    const overlay   = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');
    const isMobile  = () => window.innerWidth <= 768;

    // Toggle classes properly
    function applyMiniState(mini) {
        sidebar.classList.toggle('mini', mini);
        topbar.classList.toggle('mini', mini);
        content.classList.toggle('mini', mini);
        // Flip icon direction
        const icon = toggleBtn.querySelector('.bi');
        icon.className = mini ? 'bi bi-layout-sidebar' : 'bi bi-layout-sidebar-reverse';
        // Also remove the data-sidebar-mini attr (no longer needed once JS runs)
        document.documentElement.removeAttribute('data-sidebar-mini');
    }

    // Restore persisted state on load (no transition flash because CSS pre-applied it)
    const isMini = localStorage.getItem('arh_sidebar_mini') === 'true';
    if (!isMobile()) applyMiniState(isMini);

    // Desktop toggle click
    toggleBtn.addEventListener('click', () => {
        if (isMobile()) return;
        const nowMini = !sidebar.classList.contains('mini');
        applyMiniState(nowMini);
        localStorage.setItem('arh_sidebar_mini', String(nowMini));
    });

    // Mobile
    function openSidebarMobile() {
        sidebar.classList.add('mobile-open');
        overlay.style.display = 'block';
    }
    function closeSidebarMobile() {
        sidebar.classList.remove('mobile-open');
        overlay.style.display = 'none';
    }

    // Auto-dismiss alerts
    document.querySelectorAll('.alert').forEach(el => {
        setTimeout(() => { bootstrap.Alert.getOrCreateInstance(el)?.close(); }, 5000);
    });

    // Prevent double-submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            if (this.checkValidity()) {
                this.querySelectorAll('button[type="submit"]').forEach(btn => {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                });
            }
        });
    });
</script>
@yield('scripts')
</body>
</html>
