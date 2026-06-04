<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Klien Portal – ART-HUB')</title>
    
    <!-- Tailwind CSS (CDN for development) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&family=Noto+Serif:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Page-specific styles (pushed from child views, e.g. Leaflet CSS) -->
    @stack('styles')
    
    <!-- Tailwind Config for Heritage Modernist -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#361F1A',
                            container: '#5B3730'
                        },
                        secondary: {
                            DEFAULT: '#FCD400',
                            container: '#FDF0B2'
                        },
                        surface: {
                            container: {
                                lowest: '#FFFFFF',
                                low: '#FAF9F6',
                                DEFAULT: '#F4F2EE',
                                high: '#EBE7DF',
                                highest: '#E2DCD1'
                            }
                        },
                        on: {
                            surface: {
                                DEFAULT: '#1A1817',
                                variant: '#4D4946'
                            },
                            primary: {
                                DEFAULT: '#FFFFFF',
                                container: '#FFDBCF'
                            },
                            secondary: {
                                container: '#423700'
                            }
                        },
                        outline: {
                            DEFAULT: '#847B78',
                            variant: '#D7C4BF'
                        }
                    },
                    fontFamily: {
                        headline: ['"Noto Serif"', 'serif'],
                        body: ['"Manrope"', 'sans-serif'],
                        label: ['"Manrope"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Mobile bottom nav safe area */
        @media (max-width: 640px) {
            .klien-main { padding-bottom: 80px !important; }
        }
        .klien-bottom-nav {
            display: none;
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 50;
            background: #361F1A;
            border-top: 1px solid rgba(252,212,0,0.15);
            padding: 8px 0 max(10px, env(safe-area-inset-bottom));
        }
        @media (max-width: 640px) { .klien-bottom-nav { display: flex; justify-content: space-around; align-items: center; } }
        .klien-nav-item {
            display: flex; flex-direction: column; align-items: center; gap: 3px;
            text-decoration: none; color: rgba(255,255,255,0.45);
            font-size: 0.55rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;
            padding: 4px 16px; border-radius: 10px; transition: all 0.2s;
        }
        .klien-nav-item i { font-size: 1.2rem; line-height: 1; }
        .klien-nav-item.active { color: #FCD400; background: rgba(252,212,0,0.08); }
        .klien-nav-item:not(.active):hover { color: rgba(255,255,255,0.7); }
        .klien-nav-item.danger { color: rgba(239,68,68,0.5); }
        .klien-nav-item.danger:hover { color: rgba(239,68,68,0.85); }
    </style>
</head>
<body class="bg-surface-container-low text-on-surface font-body min-h-screen flex flex-col selection:bg-secondary/30 selection:text-primary">

    {{-- Top Navbar --}}
    <nav class="bg-primary text-white border-b border-outline-variant/20 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                {{-- Logo & Links --}}
                <div class="flex items-center gap-8">
                    <a href="{{ route('klien.dashboard') }}" class="font-headline font-bold text-2xl tracking-tight text-white flex items-center gap-2">
                        <span class="text-secondary">ART</span>-HUB
                    </a>
                    
                    <div class="hidden md:flex space-x-6">
                        <a href="{{ route('klien.dashboard') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors {{ request()->routeIs('klien.dashboard') ? 'border-secondary text-secondary' : 'border-transparent text-white/80 hover:text-white hover:border-white/50' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('klien.bookings.create') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors {{ request()->routeIs('klien.bookings.create') ? 'border-secondary text-secondary' : 'border-transparent text-white/80 hover:text-white hover:border-white/50' }}">
                            Pesan Baru
                        </a>
                    </div>
                </div>

                {{-- User Menu --}}
                <div class="flex items-center gap-4">
                    {{-- Notification Bell --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="relative p-2 text-white/80 hover:text-white transition-colors">
                            <i class="bi bi-bell-fill text-xl"></i>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1 right-1 flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                </span>
                            @endif
                        </button>

                        <div x-show="open" style="display: none;"
                             class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg py-2 border border-outline-variant/20 z-50">
                            <div class="px-4 py-2 border-b border-outline-variant/20 flex justify-between items-center">
                                <h3 class="font-headline font-bold text-primary text-sm">Notifikasi</h3>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <form action="{{ route('notifications.read_all') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-label font-bold uppercase tracking-widest text-secondary hover:text-secondary-container">Tandai Dibaca</button>
                                    </form>
                                @endif
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                @forelse(Auth::user()->notifications as $notification)
                                    <div class="px-4 py-3 border-b border-outline-variant/10 hover:bg-surface-container-low transition-colors {{ $notification->read_at ? 'opacity-60' : 'bg-secondary/5' }}">
                                        <p class="font-bold text-xs text-primary mb-1">{{ $notification->data['title'] ?? 'Pemberitahuan' }}</p>
                                        <p class="text-[11px] text-on-surface-variant leading-relaxed">{{ $notification->data['message'] ?? '' }}</p>
                                        <p class="text-[9px] text-outline font-label uppercase tracking-widest mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                @empty
                                    <div class="px-4 py-6 text-center text-on-surface-variant">
                                        <i class="bi bi-bell-slash text-2xl mb-2 block opacity-50"></i>
                                        <p class="text-xs">Belum ada notifikasi.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="hidden sm:flex flex-col text-right">
                        <span class="font-label text-xs uppercase tracking-widest text-secondary font-bold">{{ Auth::user()->name }}</span>
                        <span class="font-label text-[0.65rem] text-white/60 uppercase tracking-widest">Portal Klien</span>
                    </div>
                    <a href="{{ route('klien.profile.edit') }}" class="p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors" title="Profil">
                        <i class="bi bi-person-circle text-lg"></i>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors" title="Keluar">
                            <i class="bi bi-box-arrow-right text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="flex-grow max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 klien-main">
        
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3">
                <i class="bi bi-check-circle-fill text-green-600 text-lg"></i>
                <p class="font-body text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3">
                <i class="bi bi-exclamation-triangle-fill text-red-600 text-lg"></i>
                <p class="font-body text-sm font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @yield('content')
        
    </main>

    {{-- Footer --}}
    <footer class="bg-surface-container py-6 border-t border-outline-variant/30 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="font-label text-xs text-on-surface-variant uppercase tracking-widest font-bold">
                &copy; {{ date('Y') }} Sanggar Seni Cahaya Gumilang. Hak Cipta Dilindungi.
            </p>
        </div>
    </footer>

    @stack('scripts')

    {{-- Mobile Bottom Navigation (hanya tampil di layar < 640px) --}}
    <nav class="klien-bottom-nav">
        <a href="{{ route('klien.dashboard') }}"
           class="klien-nav-item {{ request()->routeIs('klien.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-fill"></i>Beranda
        </a>
        <a href="{{ route('klien.bookings.create') }}"
           class="klien-nav-item {{ request()->routeIs('klien.bookings.create') ? 'active' : '' }}">
            <i class="bi bi-calendar-plus-fill"></i>Pesan
        </a>
        <a href="{{ route('klien.profile.edit') }}"
           class="klien-nav-item {{ request()->routeIs('klien.profile.edit') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>Profil
        </a>
        <a href="#" onclick="document.getElementById('klien-logout-form').submit(); return false;"
           class="klien-nav-item danger">
            <i class="bi bi-box-arrow-right"></i>Keluar
        </a>
    </nav>
    <form id="klien-logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>

</body>
</html>
