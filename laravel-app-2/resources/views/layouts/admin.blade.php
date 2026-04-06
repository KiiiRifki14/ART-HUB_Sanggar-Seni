<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ART-HUB | Sanggar Cahaya Gumilang')</title>

    {{-- Bootstrap 5.3 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- Custom ART-HUB Overrides --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @yield('styles')
</head>
<body>

{{-- ════════ SIDEBAR ════════ --}}
<div class="d-flex" id="wrapper">

    <nav id="sidebar" class="arh-sidebar d-flex flex-column">
        {{-- Brand --}}
        <div class="arh-sidebar-brand text-center py-4 mb-2">
            <h4 class="arh-gold fw-bold mb-0 ls-2">ART-HUB</h4>
            <small class="badge arh-badge-gold mt-1">Control Panel</small>
            @auth
            <div class="mt-2 pt-2 border-top border-secondary">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <div class="arh-avatar-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    <div class="text-start">
                        <div class="fw-semibold small text-white">{{ Auth::user()->name }}</div>
                        <div class="arh-gold" style="font-size: 0.7rem;">Pimpinan Sanggar</div>
                    </div>
                </div>
            </div>
            @endauth
        </div>

        {{-- Navigation --}}
        @php
            $r = request();
            $menus = [
                ['Dashboard',        'bi-grid-1x2-fill',    'admin.dashboard',          $r->routeIs('admin.dashboard')],
                ['Event Management', 'bi-calendar-check-fill','admin.events.index',      $r->routeIs('admin.events.*') && !$r->routeIs('admin.events.plotting')],
                ['Personnel',        'bi-people-fill',       'admin.personnel.index',   $r->routeIs('admin.personnel.*')],
                ['Payment & DP',     'bi-receipt-cutoff',    'admin.bookings.index',    $r->routeIs('admin.bookings.*') && !$r->routeIs('admin.bookings.create')],
                ['New Booking',      'bi-plus-circle-fill',  'admin.bookings.create',   $r->routeIs('admin.bookings.create')],
                ['Costume & Logistik','bi-bag-fill',         'admin.costumes.index',    $r->routeIs('admin.costumes.*')],
                ['Financial Report', 'bi-graph-up-arrow',    'admin.financials.index',  $r->routeIs('admin.financials.*')],
                ['Cancellation',     'bi-shield-exclamation','admin.cancellations.index',$r->routeIs('admin.cancellations.*')],
            ];
        @endphp

        <ul class="nav flex-column px-3 flex-grow-1 overflow-auto">
            @foreach($menus as [$label, $icon, $routeName, $isActive])
            <li class="nav-item mb-1">
                <a href="{{ route($routeName) }}"
                   class="nav-link arh-nav-link {{ $isActive ? 'arh-nav-link--active' : '' }}">
                    <i class="bi {{ $icon }} me-2"></i>
                    <span>{{ $label }}</span>
                </a>
            </li>
            @endforeach
        </ul>

        {{-- Logout --}}
        <div class="px-3 pb-4 mt-2 border-top border-secondary pt-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center gap-2">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    {{-- ════════ MAIN CONTENT ════════ --}}
    <div id="page-content" class="flex-grow-1 overflow-auto">

        {{-- Topbar --}}
        <nav class="navbar arh-topbar px-4 border-bottom border-secondary sticky-top">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary" id="sidebarToggle">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <h5 class="mb-0 fw-bold">@yield('page_title', 'Admin Panel')</h5>
                    <small class="text-secondary">@yield('page_subtitle')</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge arh-badge-gold">
                    <i class="bi bi-shield-lock-fill me-1"></i>Admin
                </span>
                <small class="text-secondary d-none d-md-block">{{ now()->format('d M Y') }}</small>
            </div>
        </nav>

        {{-- Content Area --}}
        <main class="p-4">

            {{-- Flash: Success --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible border-0 d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- Flash: Warning --}}
            @if(session('warning'))
            <div class="alert alert-warning alert-dismissible border-0 d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <span>{{ session('warning') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- Flash: Error --}}
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible border-0 d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-x-octagon-fill fs-5"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')

        </main>
    </div>
</div>

{{-- Bootstrap 5 JS Bundle --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar       = document.getElementById('sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('arh-sidebar--collapsed');
            document.getElementById('page-content').classList.toggle('arh-content--expanded');
        });
    }

    // Auto-dismiss alerts after 5 seconds
    document.querySelectorAll('.alert').forEach(el => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });

    // Page loader on form submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function () {
            if (this.checkValidity()) {
                document.querySelectorAll('button[type="submit"]').forEach(btn => {
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
