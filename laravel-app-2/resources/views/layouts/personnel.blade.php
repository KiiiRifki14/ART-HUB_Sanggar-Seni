<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>@yield('title', 'Portal Kru – ART-HUB')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@600;700&display=swap" rel="stylesheet">
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
        .glass { background: rgba(255,255,255,0.045); border: 1px solid rgba(255,255,255,0.09); border-radius: 20px; }
        .glass-hi { background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.13); border-radius: 20px; }
        .gold-text { color: #C5A028; }
        .gold-border { border-color: rgba(197,160,40,0.35); }

        /* Offline banner */
        #offlineBanner { display:none; position:fixed; top:0; inset-inline:0; z-index:999; background:#dc2626; color:#fff; text-align:center; font-size:0.75rem; font-weight:700; padding:6px; }

        /* Header */
        .site-header {
            position: sticky; top: 0; z-index: 50;
            background: rgba(12,8,6,0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(197,160,40,0.15);
            padding: 12px 20px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .header-logo { font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; font-weight: 700; }
        .header-logo span.g { color: #C5A028; }
        .header-logo span.w { color: #fff; }

        /* Bottom Nav */
        .bottom-nav {
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 50;
            background: rgba(12,8,6,0.92);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(197,160,40,0.12);
            display: flex; justify-content: space-around; align-items: center;
            padding: 10px 0 max(10px, env(safe-area-inset-bottom));
        }
        .nav-item { display:flex; flex-direction:column; align-items:center; gap:4px; text-decoration:none; font-size:0.62rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; transition: all 0.2s; color: rgba(255,255,255,0.35); }
        .nav-item i { font-size:1.3rem; }
        .nav-item.active { color: #C5A028; }
        .nav-item:hover:not(.active) { color: rgba(255,255,255,0.65); }
        .nav-item.danger { color: rgba(239,68,68,0.55); }
        .nav-item.danger:hover { color: rgba(239,68,68,0.9); }

        /* Animations */
        @keyframes fadeUp { from { opacity:0; transform:translateY(18px); } to { opacity:1; transform:translateY(0); } }
        .fu  { animation: fadeUp 0.45s ease both; }
        .fu1 { animation: fadeUp 0.45s 0.07s ease both; }
        .fu2 { animation: fadeUp 0.45s 0.14s ease both; }
        .fu3 { animation: fadeUp 0.45s 0.21s ease both; }
        .fu4 { animation: fadeUp 0.45s 0.28s ease both; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width:3px; }
        ::-webkit-scrollbar-thumb { background:rgba(197,160,40,0.25); border-radius:99px; }
    </style>
</head>
<body>

<div id="offlineBanner"><i class="bi bi-wifi-off me-1"></i> OFFLINE – Absensi tersimpan, akan disinkronkan otomatis</div>

<!-- HEADER -->
<header class="site-header">
    <div class="header-logo">
        <span class="g">ART</span><span class="w">-HUB</span>
        <span style="font-family:'Plus Jakarta Sans',sans-serif; font-size:0.65rem; font-weight:600; color:rgba(255,255,255,0.3); margin-left:8px; text-transform:uppercase; letter-spacing:0.1em;">Portal Kru</span>
    </div>
    <div style="display:flex; align-items:center; gap:10px;">
        <div style="text-align:right; display:none;" class="sm-show">
            <div style="font-size:0.82rem; font-weight:700; color:#fff;">{{ Auth::user()->name }}</div>
            <div style="font-size:0.6rem; text-transform:uppercase; letter-spacing:0.1em; color:#C5A028;">Personel Aktif</div>
        </div>
        <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#8B1A2A,#4a0000);border:1.5px solid rgba(197,160,40,0.4);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.85rem;color:#C5A028;">
            {{ strtoupper(substr(Auth::user()->name,0,2)) }}
        </div>
    </div>
</header>

<!-- MAIN -->
<main style="max-width:560px; margin:0 auto; padding:20px 16px 110px;">

    @if(session('success'))
    <div class="fu" style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:12px;border:1px solid rgba(34,197,94,0.2);background:rgba(34,197,94,0.08);color:#4ade80;font-size:0.85rem;margin-bottom:16px;">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="fu" style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:12px;border:1px solid rgba(239,68,68,0.2);background:rgba(239,68,68,0.08);color:#f87171;font-size:0.85rem;margin-bottom:16px;">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
    </div>
    @endif

    @yield('content')
</main>

<!-- BOTTOM NAV -->
<nav class="bottom-nav">
    <a href="{{ route('personnel.dashboard') }}" class="nav-item {{ request()->routeIs('personnel.dashboard') ? 'active' : '' }}">
        <i class="bi bi-calendar-event-fill"></i>Jadwal
    </a>
    <a href="#" onclick="alert('Fitur belum tersedia.'); return false;" class="nav-item">
        <i class="bi bi-wallet2"></i>Keuangan
    </a>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-item danger">
        <i class="bi bi-box-arrow-right"></i>Logout
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
