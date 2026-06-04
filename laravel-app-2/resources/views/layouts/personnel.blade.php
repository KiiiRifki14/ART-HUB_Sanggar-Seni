<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <title>@yield('title', 'Portal Kru – ART-HUB')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Page-specific styles pushed from child views --}}
    @stack('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
    tailwind.config = {
        theme: { extend: {
            fontFamily: {
                'head': ['"Cormorant Garamond"', 'serif'],
                'sans': ['"Plus Jakarta Sans"', 'sans-serif'],
            },
            colors: {
                maroon: { DEFAULT:'#8B1A2A', dark:'#5C0E19', light:'#B5263B' },
                gold:   { DEFAULT:'#C5A028', light:'#E8C84A', dim:'rgba(197,160,40,0.15)' },
            }
        }}
    }
    </script>
    <style>
        * { box-sizing: border-box; }
        body {
            background: #FAF9F6;
            background-image:
                radial-gradient(ellipse at 10% 20%, rgba(139,26,42,0.05) 0%, transparent 55%),
                radial-gradient(ellipse at 90% 80%, rgba(197,160,40,0.03) 0%, transparent 50%);
            min-height: 100vh;
            min-height: 100dvh; /* dynamic viewport height untuk mobile */
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1A1817;
            overflow-x: hidden;
        }
        /* Offline banner */
        #offlineBanner { display:none; position:fixed; top:0; inset-inline:0; z-index:999; background:#dc2626; color:#fff; text-align:center; font-size:0.75rem; font-weight:700; padding:6px; }

        /* Header */
        .site-header {
            position: sticky; top: 0; z-index: 50;
            background: linear-gradient(135deg, #8B1A2A, #5C0E19);
            border-bottom: 1px solid rgba(197,160,40,0.3);
            padding: 12px 20px;
        }
        .header-container {
            max-width: 1024px;
            margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            width: 100%;
        }

        /* Desktop Nav */
        .desktop-nav {
            display: none;
            gap: 8px;
        }
        @media (min-width: 768px) {
            .desktop-nav { display: flex; align-items: center; }
        }
        .desktop-nav-item {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 6px 16px;
            border-radius: 12px;
            transition: all 0.2s;
        }
        .desktop-nav-item:hover {
            color: #fff;
            background: rgba(255,255,255,0.08);
        }
        .desktop-nav-item.active {
            color: #C5A028;
            background: rgba(197,160,40,0.15);
            border: 1px solid rgba(197,160,40,0.25);
        }
        .desktop-nav-item.danger {
            color: rgba(254,226,226,0.8);
        }
        .desktop-nav-item.danger:hover {
            color: #fff;
            background: rgba(239,68,68,0.2);
        }

        /* Bottom Nav */
        .bottom-nav {
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 50;
            background: linear-gradient(135deg, #8B1A2A, #5C0E19);
            border-top: 1px solid rgba(197,160,40,0.3);
            display: flex; justify-content: space-around; align-items: center;
            padding: 8px 0 max(10px, env(safe-area-inset-bottom));
        }
        @media (min-width: 768px) {
            .bottom-nav { display: none; }
        }
        .nav-item { display:flex; flex-direction:column; align-items:center; gap:3px; text-decoration:none; font-size:0.58rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; transition: all 0.2s; color: rgba(255,255,255,0.45); padding: 4px 12px; border-radius: 12px; }
        .nav-item i { font-size:1.25rem; line-height:1; }
        .nav-item.active { color: #C5A028; background: rgba(197,160,40,0.15); }
        .nav-item:hover:not(.active) { color: rgba(255,255,255,0.75); }
        .nav-item.danger { color: rgba(239,68,68,0.7); }
        .nav-item.danger:hover { color: rgba(239,68,68,1); }

        /* Main Container */
        .main-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px 16px 110px;
            overflow-x: hidden;
        }
        @media (min-width: 768px) {
            .main-container {
                max-width: 1024px;
                padding: 30px 24px 40px;
            }
        }

        /* Animations */
        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .fu  { animation: fadeUp 0.4s ease both; }
        .fu1 { animation: fadeUp 0.4s 0.06s ease both; }
        .fu2 { animation: fadeUp 0.4s 0.12s ease both; }
        .fu3 { animation: fadeUp 0.4s 0.18s ease both; }
        .fu4 { animation: fadeUp 0.4s 0.24s ease both; }
        .fu5 { animation: fadeUp 0.4s 0.30s ease both; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width:3px; }
        ::-webkit-scrollbar-thumb { background:rgba(139,26,42,0.2); border-radius:99px; }

        /* Card hover */
        .event-card { transition: all 0.2s ease; }
        .event-card:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(54,31,26,0.08); }
    </style>
</head>
<body>

<div id="offlineBanner"><i class="bi bi-wifi-off"></i> OFFLINE – Absensi tersimpan, akan disinkronkan otomatis</div>

<!-- HEADER -->
<header class="site-header">
    <div class="header-container">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="font-family:'Cormorant Garamond',serif;font-size:1.35rem;font-weight:700;">
                <span style="color:#C5A028;">ART</span><span style="color:#fff;">-HUB</span>
            </div>
            <span style="width:1px;height:18px;background:rgba(255,255,255,0.15);display:inline-block;"></span>
            <span style="font-size:0.6rem;font-weight:600;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.1em;">Portal Kru</span>
        </div>

        <!-- Desktop Navigation Menu -->
        <nav class="desktop-nav">
            <a href="{{ route('personnel.dashboard') }}" class="desktop-nav-item {{ request()->routeIs('personnel.dashboard') ? 'active' : '' }}">
                Jadwal
            </a>
            <a href="{{ route('personnel.keuangan') }}" class="desktop-nav-item {{ request()->routeIs('personnel.keuangan') ? 'active' : '' }}">
                Keuangan
            </a>
            <a href="{{ route('personnel.profile.edit') }}" class="desktop-nav-item {{ request()->routeIs('personnel.profile.edit') ? 'active' : '' }}">
                Profil
            </a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="desktop-nav-item danger">
                Keluar
            </a>
        </nav>

        <div style="display:flex;align-items:center;gap:15px;">
            
            {{-- Notification Bell --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false" class="relative text-white/80 hover:text-white transition-colors">
                    <i class="bi bi-bell-fill" style="font-size: 1.1rem; color: #C5A028;"></i>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="absolute top-0 right-0 flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                        </span>
                    @endif
                </button>

                <div x-show="open" style="display: none;"
                     class="absolute right-0 mt-3 w-72 md:w-80 rounded-2xl shadow-lg border border-gold/25 z-50 overflow-hidden bg-white">
                    <div class="px-4 py-3 flex justify-between items-center bg-gold/5 border-b border-gold/10">
                        <h3 style="font-family:'Cormorant Garamond',serif; font-weight:700; color:#8B1A2A; font-size:1rem;">Notifikasi</h3>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <form action="{{ route('notifications.read_all') }}" method="POST" class="m-0 inline">
                                @csrf
                                <button type="submit" style="font-size:0.65rem; font-weight:700; color:#8B1A2A; text-transform:uppercase; letter-spacing:0.05em;">Tandai Dibaca</button>
                            </form>
                        @endif
                    </div>
                    <div class="max-h-64 overflow-y-auto" style="scrollbar-width: thin;">
                        @forelse(Auth::user()->notifications as $notification)
                            <div class="px-4 py-3 transition-colors {{ $notification->read_at ? 'opacity-60' : '' }}" style="border-bottom:1px solid rgba(0,0,0,0.05); {{ $notification->read_at ? '' : 'background:rgba(139,26,42,0.03)' }}">
                                <p style="font-weight:700; font-size:0.75rem; color:#1A1817; margin-bottom:2px;">{{ $notification->data['title'] ?? 'Pemberitahuan' }}</p>
                                <p style="font-size:0.65rem; color:#4D4946; line-height:1.4;">{{ $notification->data['message'] ?? '' }}</p>
                                <p style="font-size:0.55rem; color:#8B1A2A; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; margin-top:6px;">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <div class="px-4 py-6 text-center" style="color:#847B78;">
                                <i class="bi bi-bell-slash text-xl mb-2 block opacity-50"></i>
                                <p style="font-size:0.7rem;">Belum ada notifikasi.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div style="text-align:right;">
                <div style="font-size:0.78rem;font-weight:700;color:#fff;line-height:1.2;">{{ Auth::user()->name }}</div>
                <div style="font-size:0.55rem;text-transform:uppercase;letter-spacing:0.1em;color:#C5A028;">Kru Aktif</div>
            </div>
            @php $p = Auth::user()->personnelProfile; $pPhoto = $p?->photo; @endphp
            <a href="{{ route('personnel.profile.edit') }}" style="display:block;width:36px;height:36px;border-radius:50%;border:1.5px solid rgba(197,160,40,0.4);overflow:hidden;flex-shrink:0;">
                @if($pPhoto)
                    <img src="{{ asset('storage/'.$pPhoto) }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <div style="width:100%;height:100%;background:linear-gradient(135deg,#8B1A2A,#4a0000);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.85rem;color:#C5A028;">
                        {{ strtoupper(substr(Auth::user()->name,0,2)) }}
                    </div>
                @endif
            </a>
        </div>
    </div>
</header>

<!-- MAIN -->
<main class="main-container">
    @yield('content')
</main>

<!-- BOTTOM NAV (MOBILE) -->
<nav class="bottom-nav">
    <a href="{{ route('personnel.dashboard') }}" class="nav-item {{ request()->routeIs('personnel.dashboard') ? 'active' : '' }}">
        <i class="bi bi-calendar-event-fill"></i>Jadwal
    </a>
    <a href="{{ route('personnel.keuangan') }}" class="nav-item {{ request()->routeIs('personnel.keuangan') ? 'active' : '' }}">
        <i class="bi bi-wallet2"></i>Keuangan
    </a>
    <a href="{{ route('personnel.profile.edit') }}" class="nav-item {{ request()->routeIs('personnel.profile.edit') ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i>Profil
    </a>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-item danger">
        <i class="bi bi-box-arrow-right"></i>Keluar
    </a>
</nav>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>

<script>
    window.addEventListener('load', () => {
        const banner = document.getElementById('offlineBanner');
        const update = () => { banner.style.display = navigator.onLine ? 'none' : 'block'; };
        window.addEventListener('online', update); window.addEventListener('offline', update); update();
    });
</script>
@stack('scripts')
</body>
</html>
