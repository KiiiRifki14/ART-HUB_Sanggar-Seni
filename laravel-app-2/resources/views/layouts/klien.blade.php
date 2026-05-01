<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Klien Portal – ART-HUB')</title>
    
    <!-- Tailwind CSS (CDN for development) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&family=Noto+Serif:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
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
                    <div class="hidden sm:flex flex-col text-right">
                        <span class="font-label text-xs uppercase tracking-widest text-secondary font-bold">{{ Auth::user()->name }}</span>
                        <span class="font-label text-[0.65rem] text-white/60 uppercase tracking-widest">Client Portal</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors" title="Logout">
                            <i class="bi bi-box-arrow-right text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="flex-grow max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
        
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
                &copy; {{ date('Y') }} Sanggar Seni Cahaya Gumilang. All rights reserved.
            </p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
