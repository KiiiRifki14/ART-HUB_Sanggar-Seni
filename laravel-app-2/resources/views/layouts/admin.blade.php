<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ART-HUB Sanggar Cahaya Gumilang')</title>
    
    <!-- Link Vanilla CSS Core -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Phosphor Icons (Aesthetic Minimalist Icons) -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>

    <!-- Overlay Desktop & Mobile Loader -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleSidebar()"></div>
    <div class="page-loader" id="pageLoader">
        <div class="spinner-gold"></div>
        <p style="margin-top: 1.5rem; color: var(--gold-primary); font-size: 0.9rem; letter-spacing: 2px; text-transform: uppercase;">Menyinkronisasi Basis Data 2...</p>
    </div>

    <div class="wrapper">
        <!-- ════════ SIDEBAR ════════ -->
        <aside class="sidebar glass-panel animate-fade-in" id="clientSidebar">
            <div class="sidebar-header" style="text-align: center; border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem;">
                <h2 class="title-gold" style="font-size: 1.5rem; margin-bottom: 0.5rem;">ART-HUB</h2>
                <span class="badge badge-gold">Control Panel</span>
            </div>

            <nav class="sidebar-nav" style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 1rem; flex: 1; overflow-y: auto; padding-right: 5px;">
                <a href="/dashboard" class="btn btn-outline" style="justify-content: flex-start; border: 1px solid var(--gold-primary); padding: 0.8rem 1rem; border-radius: 8px; background: var(--gold-primary); color: #000; font-weight: bold;">
                    <i class="ph ph-squares-four" style="font-size: 1.2rem;"></i> Dashboard
                </a>
                <a href="#" class="btn btn-outline" style="justify-content: flex-start; border: 1px solid var(--border-color); padding: 0.8rem 1rem; background: var(--glass-bg);">
                    <i class="ph ph-calendar-check" style="color: var(--gold-primary); font-size: 1.2rem;"></i> Event Management
                </a>
                <a href="#" class="btn btn-outline" style="justify-content: flex-start; border: 1px solid var(--border-color); padding: 0.8rem 1rem; background: var(--glass-bg);">
                    <i class="ph ph-users" style="color: var(--gold-primary); font-size: 1.2rem;"></i> Personnel Management
                </a>
                <a href="#" class="btn btn-outline" style="justify-content: flex-start; border: 1px solid var(--border-color); padding: 0.8rem 1rem; background: var(--glass-bg);">
                    <i class="ph ph-wallet" style="color: var(--gold-primary); font-size: 1.2rem;"></i> Payment Tracking
                </a>
                <a href="#" class="btn btn-outline" style="justify-content: flex-start; border: 1px solid var(--border-color); padding: 0.8rem 1rem; background: var(--glass-bg);">
                    <i class="ph ph-t-shirt" style="color: var(--gold-primary); font-size: 1.2rem;"></i> Costume Rental
                </a>
                <a href="#" class="btn btn-outline" style="justify-content: flex-start; border: 1px solid var(--border-color); padding: 0.8rem 1rem; background: var(--glass-bg);">
                    <i class="ph ph-chart-line-up" style="color: var(--gold-primary); font-size: 1.2rem;"></i> Financial Report
                </a>
                <a href="#" class="btn btn-outline" style="justify-content: flex-start; border: 1px solid var(--border-color); padding: 0.8rem 1rem; background: var(--glass-bg);">
                    <i class="ph ph-receipt" style="color: var(--gold-primary); font-size: 1.2rem;"></i> DP Verification
                </a>
                <a href="#" class="btn btn-outline" style="justify-content: flex-start; border: 1px solid var(--border-color); padding: 0.8rem 1rem; background: var(--glass-bg);">
                    <i class="ph ph-plus-circle" style="color: var(--gold-primary); font-size: 1.2rem;"></i> New Booking Entry
                </a>
                <a href="#" class="btn btn-outline" style="justify-content: flex-start; border: 1px solid var(--border-color); padding: 0.8rem 1rem; background: var(--glass-bg);">
                    <i class="ph ph-clipboard-text" style="color: var(--gold-primary); font-size: 1.2rem;"></i> Post-Event Update
                </a>
                <a href="#" class="btn btn-outline" style="justify-content: flex-start; border: 1px solid var(--border-color); padding: 0.8rem 1rem; background: var(--glass-bg);">
                    <i class="ph ph-activity" style="color: var(--gold-primary); font-size: 1.2rem;"></i> Event Monitoring
                </a>
                <a href="#" class="btn btn-outline" style="justify-content: flex-start; border: 1px solid var(--border-color); padding: 0.8rem 1rem; background: var(--glass-bg);">
                    <i class="ph ph-shield-warning" style="color: var(--gold-primary); font-size: 1.2rem;"></i> Cancellation Handler
                </a>
            </nav>

            <div style="margin-top: auto;">
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="width: 100%; border: none; justify-content: flex-start;">
                        <i class="ph ph-sign-out"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- ════════ MAIN CONTENT ════════ -->
        <main class="main-content">
            <!-- Header Notification / Welcome -->
            <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;" class="animate-fade-up">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button class="hamburger" onclick="toggleSidebar()"><i class="ph ph-list"></i></button>
                    <div>
                        <h1 style="margin-bottom: 0.2rem;">@yield('page_title')</h1>
                        <p class="text-muted">@yield('page_subtitle')</p>
                    </div>
                </div>
                <div class="user-profile" style="display: flex; align-items: center; gap: 1rem; background: var(--glass-bg); padding: 0.6rem 1.2rem; border-radius: 50px; border: 1px solid var(--glass-border);">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--gold-dark); display: flex; align-items: center; justify-content: center; font-weight: bold; color: #fff;">
                        Y
                    </div>
                    <div>
                        <div style="font-weight: 600; line-height: 1;">Pak Yat</div>
                        <small class="text-gold" style="color: var(--gold-primary);">Pimpinan Sanggar</small>
                    </div>
                </div>
            </header>

            <!-- Alerts (Success / Warning / Error) -->
            @if(session('success'))
                <div class="glass-panel" style="border-color: var(--success); background: var(--success-glow); margin-bottom: 2rem; padding: 1rem 1.5rem; display: flex; align-items: center; gap: 1rem;">
                    <i class="ph-fill ph-check-circle" style="color: var(--success); font-size: 1.5rem;"></i>
                    <span style="color: var(--text-main); font-weight: 500;">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('warning'))
                <div class="glass-panel" style="border-color: var(--gold-primary); background: rgba(212, 175, 55, 0.1); margin-bottom: 2rem; padding: 1rem 1.5rem; display: flex; align-items: center; gap: 1rem;">
                    <i class="ph-fill ph-warning" style="color: var(--gold-primary); font-size: 1.5rem;"></i>
                    <span style="color: var(--gold-light); font-weight: 500;">{{ session('warning') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="glass-panel" style="border-color: var(--danger); background: var(--danger-glow); margin-bottom: 2rem; padding: 1rem 1.5rem; display: flex; align-items: center; gap: 1rem;">
                    <i class="ph-fill ph-warning-octagon" style="color: var(--danger); font-size: 1.5rem;"></i>
                    <span style="color: #fff; font-weight: 500;">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Inject View Content -->
            @yield('content')
            
        </main>
    </div>

    <!-- JavaScript Interaktif -->
    <script>
        // Toggle Sidebar Mobile
        function toggleSidebar() {
            document.getElementById('clientSidebar').classList.toggle('show');
            document.getElementById('mobileOverlay').classList.toggle('show');
        }

        // Global Page Loader Logic
        function showLoader() {
            document.getElementById('pageLoader').classList.add('is-loading');
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Animasi cascade saat scroll
            const panels = document.querySelectorAll('.glass-panel');
            panels.forEach((panel, index) => {
                panel.style.animationDelay = `${index * 0.1}s`;
            });

            // Memicu Spinner ketika form disubmit ATAU tombol dengan kelas trigger-loader diklik
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', () => {
                    // Cek agar tidak muncul error jika validasi browser gagal
                    if(form.checkValidity()) showLoader();
                });
            });

            document.querySelectorAll('.trigger-loader').forEach(btn => {
                btn.addEventListener('click', showLoader);
            });
        });
    </script>
</body>
</html>
