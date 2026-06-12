<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Kru – ART-HUB')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Page-specific styles pushed from child views --}}
    @stack('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
    tailwind.config = {
        theme: { extend: {
            fontFamily: {
                'serif':  ['"Cormorant Garamond"', 'serif'],
                'sans':   ['"Inter"', 'sans-serif'],
            },
            colors: {
                maroon: { 900:'#1e0d0a', 800:'#361f1a', 700:'#5C0E19', DEFAULT:'#8B1A2A' },
                gold:   { DEFAULT:'#C5A028', light:'#fcd400', dim:'rgba(197,160,40,0.15)' },
                surface: '#faf9f6',
            }
        }}
    }
    </script>
    <style>
        :root {
            --clr-maroon-900: #1e0d0a;
            --clr-maroon-800: #361f1a;
            --clr-maroon-700: #5C0E19;
            --clr-maroon-500: #8B1A2A;
            --clr-gold-500:   #C5A028;
            --clr-gold-300:   #fcd400;
            --clr-surface:    #faf9f6;
            --clr-card:       #ffffff;
            --sidebar-w:      260px;
            --topbar-h:       60px;
            --easing-spring:  cubic-bezier(0.16, 1, 0.3, 1);
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--clr-surface);
            color: #1A1817;
            min-height: 100vh;
            min-height: 100dvh;
            overflow-x: hidden;
        }

        /* ── OFFLINE BANNER ── */
        #offlineBanner {
            display: none; position: fixed; top: 0; inset-inline: 0; z-index: 9999;
            background: #dc2626; color: #fff; text-align: center;
            font-size: 0.72rem; font-weight: 700; padding: 5px 12px;
            letter-spacing: 0.05em; text-transform: uppercase;
        }

        /* ── SIDEBAR (desktop/tablet) ── */
        .prs-sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: linear-gradient(185deg, var(--clr-maroon-900) 0%, var(--clr-maroon-800) 100%);
            border-right: 1px solid rgba(255,255,255,0.04);
            display: none; /* hidden by default, shown on md+ */
            flex-direction: column;
            z-index: 200;
            box-shadow: 4px 0 24px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        @media (min-width: 768px) {
            .prs-sidebar { display: flex; }
        }

        /* Sidebar brand */
        .prs-brand {
            display: flex; align-items: center; gap: 10px;
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            flex-shrink: 0;
        }
        .prs-brand-logo {
            width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--clr-gold-300), var(--clr-gold-500));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 0.95rem;
            color: var(--clr-maroon-900); box-shadow: 0 4px 12px rgba(252,212,0,0.25);
        }
        .prs-brand-text { overflow: hidden; }
        .prs-brand-title {
            font-family: 'Cormorant Garamond', serif; font-size: 1.15rem;
            font-weight: 700; color: var(--clr-gold-300); letter-spacing: 0.5px; line-height: 1;
        }
        .prs-brand-sub {
            font-size: 0.55rem; color: rgba(255,255,255,0.35);
            text-transform: uppercase; letter-spacing: 2px; font-weight: 600; margin-top: 2px;
        }

        /* Sidebar user card */
        .prs-user {
            display: flex; align-items: center; gap: 10px;
            margin: 14px 14px 8px;
            padding: 10px 12px;
            border-radius: 12px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            transition: all 0.4s var(--easing-spring);
            text-decoration: none;
            flex-shrink: 0;
        }
        .prs-user:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(197,160,40,0.3);
            box-shadow: 0 4px 16px rgba(197,160,40,0.1);
        }
        .prs-avatar {
            width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0; overflow: hidden;
            border: 1.5px solid rgba(197,160,40,0.35);
            background: linear-gradient(135deg, var(--clr-gold-500), var(--clr-maroon-700));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 0.9rem; color: #fff;
        }
        .prs-user-name { font-size: 0.82rem; font-weight: 600; color: #fff; line-height: 1.2; }
        .prs-user-role { font-size: 0.55rem; color: var(--clr-gold-500); text-transform: uppercase; letter-spacing: 1.5px; }

        /* Sidebar nav */
        .prs-nav { flex: 1; overflow-y: auto; padding: 8px 12px 12px; min-height: 0; }
        .prs-nav::-webkit-scrollbar { width: 3px; }
        .prs-nav::-webkit-scrollbar-thumb { background: rgba(197,160,40,0.15); border-radius: 99px; }
        .prs-nav-section {
            font-size: 0.58rem; color: rgba(255,255,255,0.25);
            text-transform: uppercase; letter-spacing: 2px; font-weight: 700;
            padding: 18px 6px 8px; border-bottom: 1px solid rgba(255,255,255,0.04);
            margin-bottom: 6px;
        }
        .prs-nav-link {
            position: relative; display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px; margin-bottom: 4px;
            color: rgba(255,255,255,0.55); text-decoration: none;
            font-size: 0.88rem; font-weight: 500;
            transition: all 0.4s var(--easing-spring);
            border: 1px solid transparent; overflow: hidden;
            white-space: nowrap;
        }
        .prs-nav-link::before {
            content: ''; position: absolute; left: 0; top: 20%; height: 60%; width: 2.5px;
            background: var(--clr-gold-300); border-radius: 0 4px 4px 0;
            opacity: 0; transform: scaleY(0); transition: all 0.3s var(--easing-spring);
        }
        .prs-nav-link:hover { color: #fff; background: rgba(255,255,255,0.04); padding-left: 16px; }
        .prs-nav-link:hover::before { opacity: 0.5; transform: scaleY(1); }
        .prs-nav-link.active {
            background: linear-gradient(90deg, rgba(197,160,40,0.14), rgba(197,160,40,0.03));
            border-color: rgba(197,160,40,0.2);
            color: var(--clr-gold-300); font-weight: 600;
        }
        .prs-nav-link.active::before { opacity: 1; transform: scaleY(1.2); }
        .prs-nav-icon {
            width: 28px; height: 28px; flex-shrink: 0; display: flex; align-items: center;
            justify-content: center; border-radius: 8px; background: rgba(0,0,0,0.3);
            font-size: 0.95rem; transition: all 0.35s var(--easing-spring);
        }
        .prs-nav-link:hover .prs-nav-icon { background: rgba(197,160,40,0.15); color: var(--clr-gold-300); }
        .prs-nav-link.active .prs-nav-icon { background: var(--clr-gold-300); color: var(--clr-maroon-900); box-shadow: 0 0 12px rgba(252,212,0,0.3); }

        /* Sidebar logout */
        .prs-logout-wrap { padding: 12px; flex-shrink: 0; border-top: 1px solid rgba(255,255,255,0.04); }
        .prs-logout {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px; width: 100%;
            color: rgba(255,255,255,0.5); font-size: 0.88rem; font-weight: 500;
            background: none; border: 1px solid transparent; cursor: pointer;
            transition: all 0.4s var(--easing-spring); text-decoration: none;
        }
        .prs-logout:hover { color: #fca5a5; background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.2); }
        .prs-logout:hover .prs-nav-icon { background: rgba(239,68,68,0.2); color: #fca5a5; }

        /* ── TOPBAR (desktop: right of sidebar) ── */
        .prs-topbar {
            position: fixed; top: 0; right: 0; left: 0;
            height: var(--topbar-h);
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(197,160,40,0.12);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 16px;
            z-index: 100;
            transition: left 0.4s var(--easing-spring);
        }
        @media (min-width: 768px) {
            .prs-topbar { left: var(--sidebar-w); padding: 0 24px; }
        }
        .prs-topbar-brand {
            display: flex; align-items: center; gap: 8px;
            font-family: 'Cormorant Garamond', serif; font-weight: 700;
            font-size: 1.15rem; color: var(--clr-maroon-800);
        }
        @media (min-width: 768px) {
            .prs-topbar-brand { display: none; } /* hidden on desktop, shown in sidebar */
        }

        /* ── MAIN CONTENT ── */
        .prs-main {
            padding-top: var(--topbar-h);
            min-height: 100vh;
            overflow-x: hidden;
        }
        @media (min-width: 768px) {
            .prs-main { margin-left: var(--sidebar-w); }
        }
        .prs-content {
            max-width: 720px;
            margin: 0 auto;
            padding: 20px 16px 110px; /* bottom pad for mobile nav */
        }
        @media (min-width: 768px) {
            .prs-content { max-width: 960px; padding: 28px 28px 40px; }
        }

        /* ── BOTTOM NAV (mobile only) ── */
        .prs-bottom-nav {
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 100;
            background: rgba(30, 13, 10, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-top: 1px solid rgba(197,160,40,0.2);
            display: flex; justify-content: space-around; align-items: center;
            padding: 6px 0 max(8px, env(safe-area-inset-bottom));
            box-shadow: 0 -8px 32px rgba(30,13,10,0.2);
        }
        @media (min-width: 768px) {
            .prs-bottom-nav { display: none; }
        }
        .prs-bn-item {
            display: flex; flex-direction: column; align-items: center; gap: 2px;
            text-decoration: none; font-size: 0.55rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.08em;
            transition: all 0.35s var(--easing-spring);
            color: rgba(255,255,255,0.38);
            padding: 4px 14px; border-radius: 12px;
            position: relative;
        }
        .prs-bn-item i { font-size: 1.2rem; line-height: 1; transition: all 0.35s var(--easing-spring); }
        .prs-bn-item.active {
            color: var(--clr-gold-300);
            background: rgba(197,160,40,0.12);
        }
        .prs-bn-item.active i { transform: translateY(-1px); }
        .prs-bn-item:hover:not(.active) { color: rgba(255,255,255,0.65); }
        .prs-bn-item.danger { color: rgba(248,113,113,0.6); }
        .prs-bn-item.danger:hover { color: rgba(248,113,113,1); }

        /* ── PAGE ANIMATIONS ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fu  { animation: fadeUp 0.45s var(--easing-spring) both; }
        .fu1 { animation: fadeUp 0.45s 0.07s var(--easing-spring) both; }
        .fu2 { animation: fadeUp 0.45s 0.14s var(--easing-spring) both; }
        .fu3 { animation: fadeUp 0.45s 0.21s var(--easing-spring) both; }
        .fu4 { animation: fadeUp 0.45s 0.28s var(--easing-spring) both; }
        .fu5 { animation: fadeUp 0.45s 0.35s var(--easing-spring) both; }

        /* Card hover */
        .event-card { transition: all 0.35s var(--easing-spring); }
        .event-card:hover { transform: translateY(-2px); box-shadow: 0 14px 40px rgba(54,31,26,0.1) !important; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 3px; }
        ::-webkit-scrollbar-thumb { background: rgba(139,26,42,0.15); border-radius: 99px; }

        /* Notification dropdown */
        .notif-dropdown {
            position: absolute; right: 0; top: calc(100% + 10px);
            width: 300px; border-radius: 16px;
            background: #fff;
            border: 1px solid rgba(197,160,40,0.2);
            box-shadow: 0 16px 48px rgba(30,13,10,0.12);
            overflow: hidden; z-index: 999;
        }

        /* Input base */
        .prs-input {
            width: 100%; background: #F4F2EE; border: none;
            border-bottom: 2px solid var(--clr-gold-500);
            padding: 12px 14px 10px; border-radius: 8px 8px 0 0;
            font-family: 'Inter', sans-serif; font-size: 0.9rem; color: #1A1817;
            outline: none; transition: border-color 0.3s var(--easing-spring), background 0.3s var(--easing-spring);
        }
        .prs-input:focus { border-bottom-color: var(--clr-maroon-500); background: #FFFDF0; }
        .prs-input::placeholder { color: #B0A49F; }
    </style>
</head>
<body>

<div id="offlineBanner"><i class="bi bi-wifi-off"></i> OFFLINE – Data tersimpan lokal, sinkronisasi otomatis saat online</div>

{{-- ══ SIDEBAR (desktop only) ══ --}}
<aside class="prs-sidebar">
    {{-- Brand --}}
    <div class="prs-brand">
        <div class="prs-brand-logo">A</div>
        <div class="prs-brand-text">
            <div class="prs-brand-title">ART-HUB</div>
            <div class="prs-brand-sub">Portal Kru</div>
        </div>
    </div>

    {{-- User card --}}
    @php $p = Auth::user()->personnelProfile; $pPhoto = $p?->photo; @endphp
    <a href="{{ route('personnel.profile.edit') }}" class="prs-user">
        <div class="prs-avatar" style="width:34px;height:34px">
            @if($pPhoto)
                <img src="{{ asset('storage/'.$pPhoto) }}" style="width:100%;height:100%;object-fit:cover">
            @else
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            @endif
        </div>
        <div style="overflow:hidden">
            <div class="prs-user-name truncate">{{ Auth::user()->name }}</div>
            <div class="prs-user-role">Kru Aktif</div>
        </div>
        <i class="bi bi-pencil-square ms-auto" style="color:rgba(197,160,40,0.4);font-size:0.75rem;flex-shrink:0"></i>
    </a>

    {{-- Nav --}}
    <nav class="prs-nav">
        <div class="prs-nav-section">Menu Utama</div>

        <a href="{{ route('personnel.dashboard') }}"
           class="prs-nav-link {{ request()->routeIs('personnel.dashboard') ? 'active' : '' }}">
            <span class="prs-nav-icon"><i class="bi bi-calendar-event-fill"></i></span>
            <span>Jadwal & Dashboard</span>
        </a>
        <a href="{{ route('personnel.keuangan') }}"
           class="prs-nav-link {{ request()->routeIs('personnel.keuangan') ? 'active' : '' }}">
            <span class="prs-nav-icon"><i class="bi bi-wallet2"></i></span>
            <span>Keuangan Saya</span>
        </a>
        <a href="{{ route('personnel.profile.edit') }}"
           class="prs-nav-link {{ request()->routeIs('personnel.profile.*') ? 'active' : '' }}">
            <span class="prs-nav-icon"><i class="bi bi-person-badge-fill"></i></span>
            <span>Kartu Identitas</span>
        </a>
    </nav>

    {{-- Logout --}}
    <div class="prs-logout-wrap">
        <button onclick="document.getElementById('logout-form').submit()" class="prs-logout">
            <span class="prs-nav-icon"><i class="bi bi-box-arrow-right"></i></span>
            <span>Keluar</span>
        </button>
    </div>
</aside>

{{-- ══ TOPBAR ══ --}}
<header class="prs-topbar">
    {{-- Brand (mobile only) --}}
    <div class="prs-topbar-brand">
        <span style="color:var(--clr-gold-300)">ART</span><span style="color:var(--clr-maroon-800)">-HUB</span>
        <span style="font-family:'Inter',sans-serif;font-size:0.55rem;text-transform:uppercase;letter-spacing:2px;color:#827471;font-weight:600">Portal Kru</span>
    </div>

    <div style="display:flex;align-items:center;gap:14px;margin-left:auto">
        {{-- Notification Bell --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.outside="open = false"
                    class="relative" style="color:rgba(139,26,42,0.7);background:none;border:none;cursor:pointer;padding:4px;font-size:1.1rem;">
                <i class="bi bi-bell-fill" style="color:var(--clr-gold-500)"></i>
                @if(Auth::user()->unreadNotifications->count() > 0)
                    <span style="position:absolute;top:0;right:0;width:8px;height:8px;background:#ef4444;border-radius:50%;border:2px solid #fff"></span>
                @endif
            </button>
            <div x-show="open" style="display:none" class="notif-dropdown">
                <div style="padding:12px 16px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(197,160,40,0.1);background:rgba(197,160,40,0.04)">
                    <span style="font-family:'Cormorant Garamond',serif;font-weight:700;font-size:1rem;color:var(--clr-maroon-500)">Notifikasi</span>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.read_all') }}" method="POST" style="margin:0">
                        @csrf
                        <button type="submit" style="font-size:0.6rem;font-weight:700;color:var(--clr-maroon-500);text-transform:uppercase;letter-spacing:0.05em;background:none;border:none;cursor:pointer">Tandai Dibaca</button>
                    </form>
                    @endif
                </div>
                <div style="max-height:260px;overflow-y:auto">
                    @forelse(Auth::user()->notifications as $notification)
                    <div style="padding:12px 16px;border-bottom:1px solid rgba(0,0,0,0.04);{{ $notification->read_at ? 'opacity:0.6' : 'background:rgba(139,26,42,0.02)' }}">
                        <p style="font-weight:700;font-size:0.75rem;color:#1A1817;margin:0 0 2px">{{ $notification->data['title'] ?? 'Pemberitahuan' }}</p>
                        <p style="font-size:0.65rem;color:#4D4946;margin:0 0 4px;line-height:1.4">{{ $notification->data['message'] ?? '' }}</p>
                        <p style="font-size:0.55rem;color:var(--clr-maroon-500);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;margin:0">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @empty
                    <div style="padding:24px;text-align:center;color:#847B78">
                        <i class="bi bi-bell-slash" style="font-size:1.5rem;display:block;margin-bottom:8px;opacity:0.4"></i>
                        <p style="font-size:0.7rem;margin:0">Belum ada notifikasi.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- User avatar --}}
        <a href="{{ route('personnel.profile.edit') }}"
           style="width:34px;height:34px;border-radius:50%;overflow:hidden;border:1.5px solid rgba(197,160,40,0.35);display:block;flex-shrink:0;text-decoration:none">
            @if($pPhoto)
                <img src="{{ asset('storage/'.$pPhoto) }}" style="width:100%;height:100%;object-fit:cover">
            @else
                <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--clr-maroon-500),var(--clr-maroon-700));display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-weight:700;font-size:0.85rem;color:var(--clr-gold-300)">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
            @endif
        </a>
    </div>
</header>

{{-- ══ MAIN CONTENT ══ --}}
<main class="prs-main">
    <div class="prs-content">
        @yield('content')
    </div>
</main>

{{-- ══ BOTTOM NAV (mobile only) ══ --}}
<nav class="prs-bottom-nav">
    <a href="{{ route('personnel.dashboard') }}"
       class="prs-bn-item {{ request()->routeIs('personnel.dashboard') ? 'active' : '' }}">
        <i class="bi bi-calendar-event-fill"></i>Jadwal
    </a>
    <a href="{{ route('personnel.keuangan') }}"
       class="prs-bn-item {{ request()->routeIs('personnel.keuangan') ? 'active' : '' }}">
        <i class="bi bi-wallet2"></i>Keuangan
    </a>
    <a href="{{ route('personnel.profile.edit') }}"
       class="prs-bn-item {{ request()->routeIs('personnel.profile.*') ? 'active' : '' }}">
        <i class="bi bi-person-badge-fill"></i>Profil
    </a>
    <button onclick="document.getElementById('logout-form').submit()" class="prs-bn-item danger" style="background:none;border:none;cursor:pointer">
        <i class="bi bi-box-arrow-right"></i>Keluar
    </button>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>

<script>
    window.addEventListener('load', () => {
        const banner = document.getElementById('offlineBanner');
        const update = () => { banner.style.display = navigator.onLine ? 'none' : 'block'; };
        window.addEventListener('online', update);
        window.addEventListener('offline', update);
        update();
    });
</script>
@stack('scripts')
</body>
</html>
