<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Klien Portal – ART-HUB')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --klien-maroon:       #800000;
            --klien-maroon-dark:  #800000;
            --klien-gold:         #D4AF37;
            --klien-gold-hover:   #F3CE5E;
            --klien-body-bg:      #FDFBF7;
            --klien-card-bg:      #FFFFFF;
            --klien-border:       #E8E3D9;
            --klien-text:         #1A1A1A;
            --klien-text-muted:   #7A7A7A;
        }

        body {
            background-color: var(--klien-body-bg);
            color: var(--klien-text);
            font-family: 'Outfit', sans-serif;
        }

        .klien-gold { color: var(--klien-gold); }

        .glass-card {
            background: var(--klien-card-bg);
            border: 1px solid var(--klien-gold); /* border gold tipis */
            border-radius: 16px;
            box-shadow: 0 2px 16px rgba(128,0,0,0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 24px rgba(128,0,0,0.1);
        }

        .navbar-klien {
            background: var(--klien-maroon-dark);
            border-bottom: 1px solid rgba(212,175,55,0.2);
        }
        .navbar-klien .navbar-brand,
        .navbar-klien .nav-link { color: #fff !important; }
        .navbar-klien .nav-link:hover { color: var(--klien-gold) !important; }
        .navbar-klien .nav-link.active { color: var(--klien-gold) !important; font-weight: 600; }

        .btn-klien-primary {
            background: var(--klien-maroon);
            color: #FFFFFF;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
            transition: all 0.3s ease;
        }
        .btn-klien-primary:hover {
            background: #600000;
            color: #FFFFFF;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(128,0,0,0.3);
        }

        .btn-klien-outline-gold {
            background: transparent;
            color: var(--klien-gold);
            border: 1px solid var(--klien-gold);
            font-weight: 600;
            border-radius: 8px;
            padding: 10px 24px;
            transition: all 0.3s ease;
        }
        .btn-klien-outline-gold:hover {
            background: var(--klien-gold);
            color: #1a0508;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(212,175,55,0.3);
        }

        .form-control, .form-select {
            background-color: #FFFFFF;
            border: 1px solid var(--klien-border);
            color: var(--klien-text);
            border-radius: 8px;
            padding: 12px 15px;
        }

        .form-control:focus, .form-select:focus {
            background-color: #FFFFFF;
            border-color: var(--klien-gold);
            box-shadow: 0 0 0 0.25rem rgba(212,175,55,0.2);
            color: var(--klien-text);
        }

        .form-control::placeholder { color: var(--klien-text-muted); }

        .klien-hero {
            background: linear-gradient(135deg, var(--klien-maroon), #4a0000);
            color: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(128,0,0,0.15);
            border-bottom: 3px solid var(--klien-gold);
        }

        .fp-card {
            background: rgba(212,175,55,0.1);
            border: 2px solid var(--klien-gold);
            border-radius: 12px;
            padding: 20px;
        }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-klien sticky-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="{{ route('klien.dashboard') }}">
                <span class="klien-gold">ART</span>-HUB
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#klienNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="klienNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('klien.dashboard') ? 'active klien-gold fw-semibold' : '' }}" href="{{ route('klien.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('klien.bookings.create') ? 'active klien-gold fw-semibold' : '' }}" href="{{ route('klien.bookings.create') }}">Pesan Baru</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <span class="fw-semibold text-white">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light border-0">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show rounded-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show rounded-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
