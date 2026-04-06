<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
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
            --klien-gold: #D4AF37;
            --klien-dark: #0f1014;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
        }

        body {
            background-color: var(--klien-dark);
            color: #f1f1f1;
            font-family: 'Outfit', sans-serif;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(212, 175, 55, 0.08), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(255, 255, 255, 0.02), transparent 25%);
            background-attachment: fixed;
        }

        .klien-gold { color: var(--klien-gold); }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, background 0.3s ease;
        }
        
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-5px);
        }

        .navbar-klien {
            background: rgba(15, 16, 20, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--glass-border);
        }

        .btn-klien-gold {
            background: linear-gradient(135deg, #E6C25A, #B48B25);
            color: #000;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
            transition: all 0.3s ease;
        }

        .btn-klien-gold:hover {
            background: linear-gradient(135deg, #f7d165, #c59929);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
            color: #000;
        }

        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            color: #fff;
            border-radius: 8px;
            padding: 12px 15px;
        }

        .form-control:focus, .form-select:focus {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: var(--klien-gold);
            box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.25);
            color: #fff;
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
                    <span class="fw-semibold">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger border-0">Logout</button>
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
