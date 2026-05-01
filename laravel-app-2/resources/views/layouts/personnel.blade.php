<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>@yield('title', 'Kru Portal – ART-HUB')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --arh-maroon:      #800000;
            --arh-maroon-dark: #800000;
            --arh-gold:        #D4AF37;
            --arh-gold-hover:  #F3CE5E;
            --arh-body-bg:     #FDFBF7;
            --arh-card-bg:     #FFFFFF;
            --arh-border:      #E8E3D9;
            --arh-text:        #1A1A1A;
            --arh-text-muted:  #7A7A7A;
        }

        body {
            background-color: var(--arh-body-bg);
            color: var(--arh-text);
            font-family: 'Inter', -apple-system, sans-serif;
            padding-bottom: 80px; /* Safe area for bottom nav */
        }

        .arh-gold { color: var(--arh-gold); }
        .bg-arh-gold { background-color: var(--arh-gold); color: #1a0508; }
        
        .btn-arh-gold {
            background-color: var(--arh-gold);
            color: #1a0508;
            border: none;
            font-weight: 600;
        }
        .btn-arh-gold:hover {
            background-color: var(--arh-gold-hover);
            color: #1a0508;
        }

        .btn-checkin {
            background-color: var(--arh-gold);
            color: #1a0508;
            border: none;
            font-weight: 700;
            padding: 16px;
            border-radius: 12px;
            width: 100%;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(212,175,55,0.4);
        }
        .btn-checkin.success {
            background-color: var(--arh-maroon);
            color: #FFFFFF;
            box-shadow: 0 4px 15px rgba(128,0,0,0.4);
        }

        .offline-banner {
            display: none;
            background-color: #dc3545;
            color: white;
            text-align: center;
            padding: 8px;
            font-size: 0.85rem;
            font-weight: bold;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1050;
        }

        /* Bottom Mobile Navigation */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: var(--arh-card-bg);
            border-top: 1px solid var(--arh-border);
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            padding-bottom: env(safe-area-inset-bottom, 10px);
            z-index: 1040;
        }
        
        .mobile-bottom-nav a {
            color: var(--arh-text-muted);
            text-decoration: none;
            text-align: center;
            font-size: 0.75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }
        
        .mobile-bottom-nav a.active {
            color: var(--arh-gold);
        }
        
        .mobile-bottom-nav i {
            font-size: 1.35rem;
        }

        /* Header */
        .mobile-header {
            background-color: var(--arh-maroon-dark);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(197,160,40,0.2);
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        .mobile-header .fw-bold { color: #C5A028; }
        .mobile-header span.small { color: #f0e0e0; }

        .card-personnel {
            background-color: var(--arh-card-bg);
            border: 1px solid var(--arh-gold);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(128,0,0,0.05);
        }
        
        /* Slide-in transition for main content */
        main.container {
            animation: slideInRight 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            padding-left: 16px;
            padding-right: 16px;
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .badge-role {
            background-color: rgba(197,160,40,0.15);
            color: #C5A028;
            border: 1px solid rgba(197,160,40,0.4);
        }
    </style>
</head>
<body>

    <div id="offlineBanner" class="offline-banner">
        <i class="bi bi-wifi-off me-1"></i> Mode Offline Aktif - Absensi akan disinkronkan nanti
    </div>

    <header class="mobile-header mt-0" id="mainHeader">
        <div class="fw-bold fs-5" style="color: #D4AF37;">ART<span style="color: #fff;">-HUB</span></div>
        <div class="d-flex align-items-center gap-3">
            <span class="small fw-semibold" style="color: #FDFBF7;">{{ Auth::user()->name }}</span>
            <div class="rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width:35px;height:35px;font-size:0.9rem;background:linear-gradient(135deg,#4a0000,#2d0000);color:#fff;border:1px solid rgba(212,175,55,0.4);">
                {{ substr(Auth::user()->name, 0, 2) }}
            </div>
        </div>
    </header>

    <main class="container py-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @yield('content')
    </main>

    <nav class="mobile-bottom-nav">
        <a href="{{ route('personnel.dashboard') }}" class="{{ request()->routeIs('personnel.dashboard') ? 'active' : '' }}">
            <i class="bi bi-calendar-event"></i>
            <span>Jadwal Ku</span>
        </a>
        <a href="#" onclick="alert('Fitur Kasbon belum tesedia.'); return false;">
            <i class="bi bi-wallet2"></i>
            <span>Keuangan</span>
        </a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right text-danger"></i>
            <span class="text-danger">Logout</span>
        </a>
    </nav>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Offline / Online Status Detection Layer
        window.addEventListener('load', () => {
            const banner = document.getElementById('offlineBanner');
            const header = document.getElementById('mainHeader');
            
            function updateOnlineStatus() {
                if (navigator.onLine) {
                    banner.style.display = 'none';
                    header.style.marginTop = '0';
                    // Sync pending check-ins if connected
                    syncOfflineCheckins();
                } else {
                    banner.style.display = 'block';
                    header.style.marginTop = '35px';
                }
            }

            window.addEventListener('online', updateOnlineStatus);
            window.addEventListener('offline', updateOnlineStatus);
            updateOnlineStatus();
        });

        // Offline Fallback Synchronization Logic (Ghosting Guard)
        function syncOfflineCheckins() {
            let pendingCheckins = JSON.parse(localStorage.getItem('pendingCheckins') || '[]');
            if (pendingCheckins.length > 0) {
                console.log('🔄 SINKRONISASI OFFLINE: Menemukan ' + pendingCheckins.length + ' data absensi.');
                
                // For each pending checkin, we create a hidden form and submit it
                // In a real SPA we would use fetch(), but here we use a form post fallback
                const data = pendingCheckins.shift();
                localStorage.setItem('pendingCheckins', JSON.stringify(pendingCheckins));
                
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/personnel/events/${data.eventId}/check-in`;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                
                const latInput = document.createElement('input');
                latInput.type = 'hidden';
                latInput.name = 'latitude';
                latInput.value = data.latitude;
                
                const lngInput = document.createElement('input');
                lngInput.type = 'hidden';
                lngInput.name = 'longitude';
                lngInput.value = data.longitude;
                
                // Timestamp override (so the backend knows WHEN it actually happened offline)
                const timeInput = document.createElement('input');
                timeInput.type = 'hidden';
                timeInput.name = 'offline_timestamp';
                timeInput.value = data.timestamp;
                
                form.appendChild(csrfInput);
                form.appendChild(latInput);
                form.appendChild(lngInput);
                form.appendChild(timeInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
