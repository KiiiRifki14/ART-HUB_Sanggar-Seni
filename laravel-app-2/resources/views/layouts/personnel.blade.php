<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>@yield('title', 'Portal Kru – ART-HUB')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
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
            background: #0C0806;
            background-image:
                radial-gradient(ellipse at 10% 20%, rgba(139,26,42,0.18) 0%, transparent 55%),
                radial-gradient(ellipse at 90% 80%, rgba(197,160,40,0.06) 0%, transparent 50%);
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #fff;
        }
        /* Offline banner */
        #offlineBanner { display:none; position:fixed; top:0; inset-inline:0; z-index:999; background:#dc2626; color:#fff; text-align:center; font-size:0.75rem; font-weight:700; padding:6px; }

        /* Header */
        .site-header {
            position: sticky; top: 0; z-index: 50;
            background: rgba(12,8,6,0.88);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(197,160,40,0.15);
            padding: 12px 20px;
            display: flex; align-items: center; justify-content: space-between;
        }

        /* Bottom Nav */
        .bottom-nav {
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 50;
            background: rgba(12,8,6,0.95);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-top: 1px solid rgba(197,160,40,0.12);
            display: flex; justify-content: space-around; align-items: center;
            padding: 8px 0 max(10px, env(safe-area-inset-bottom));
        }
        .nav-item { display:flex; flex-direction:column; align-items:center; gap:3px; text-decoration:none; font-size:0.58rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; transition: all 0.2s; color: rgba(255,255,255,0.35); padding: 4px 12px; border-radius: 12px; }
        .nav-item i { font-size:1.25rem; line-height:1; }
        .nav-item.active { color: #C5A028; background: rgba(197,160,40,0.08); }
        .nav-item:hover:not(.active) { color: rgba(255,255,255,0.65); }
        .nav-item.danger { color: rgba(239,68,68,0.55); }
        .nav-item.danger:hover { color: rgba(239,68,68,0.9); }

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
        ::-webkit-scrollbar-thumb { background:rgba(197,160,40,0.25); border-radius:99px; }

        /* Card hover */
        .event-card { transition: all 0.2s ease; }
        .event-card:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(0,0,0,0.3); }
    </style>
</head>
<body>

<div id="offlineBanner"><i class="bi bi-wifi-off"></i> OFFLINE – Absensi tersimpan, akan disinkronkan otomatis</div>

<!-- HEADER -->
<header class="site-header">
    <div style="display:flex;align-items:center;gap:10px;">
        <div style="font-family:'Cormorant Garamond',serif;font-size:1.35rem;font-weight:700;">
            <span style="color:#C5A028;">ART</span><span style="color:#fff;">-HUB</span>
        </div>
        <span style="width:1px;height:18px;background:rgba(255,255,255,0.15);display:inline-block;"></span>
        <span style="font-size:0.6rem;font-weight:600;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.1em;">Portal Kru</span>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
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
</header>

<!-- MAIN -->
<main style="max-width:600px; margin:0 auto; padding:20px 16px 110px;">
    @yield('content')
</main>

<!-- BOTTOM NAV -->
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
