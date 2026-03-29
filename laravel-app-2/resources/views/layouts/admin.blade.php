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

    <div class="wrapper">
        <!-- ════════ SIDEBAR ════════ -->
        <aside class="sidebar glass-panel animate-fade-in">
            <div class="sidebar-header" style="text-align: center; border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem;">
                <h2 class="title-gold" style="font-size: 1.5rem; margin-bottom: 0.5rem;">ART-HUB</h2>
                <span class="badge badge-gold">Control Panel</span>
            </div>

            <nav class="sidebar-nav" style="display: flex; flex-direction: column; gap: 0.8rem; margin-top: 1rem;">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline" style="justify-content: flex-start; border: none; padding: 1rem; border-radius: 12px; background: rgba(255,255,255,0.03);">
                    <i class="ph ph-squares-four" style="font-size: 1.2rem; color: var(--gold-primary);"></i> Dashboard
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline" style="justify-content: flex-start; border: none; padding: 1rem;">
                    <i class="ph ph-calendar-check" style="color: var(--gold-primary);"></i> Bookings
                </a>
                <a href="#" class="btn btn-outline" style="justify-content: flex-start; border: none; padding: 1rem;">
                    <i class="ph ph-users-three" style="color: var(--gold-primary);"></i> Smart Plotting
                </a>
                <a href="#" class="btn btn-outline" style="justify-content: flex-start; border: none; padding: 1rem;">
                    <i class="ph ph-t-shirt" style="color: var(--gold-primary);"></i> Inventory & Costumes
                </a>
                <a href="#" class="btn btn-outline" style="justify-content: flex-start; border: none; padding: 1rem;">
                    <i class="ph ph-chart-line-up" style="color: var(--gold-primary);"></i> Financial Audit
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
                <div>
                    <h1 style="margin-bottom: 0.2rem;">@yield('page_title')</h1>
                    <p class="text-muted">@yield('page_subtitle')</p>
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
        // Animasi cascade saat scroll
        document.addEventListener('DOMContentLoaded', () => {
            const panels = document.querySelectorAll('.glass-panel');
            panels.forEach((panel, index) => {
                panel.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>
