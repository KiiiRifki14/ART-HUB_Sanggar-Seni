<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ART-HUB Sanggar Cahaya Gumilang</title>
    
    <!-- Memanggil Vanilla CSS kita, BUKAN Tailwind Breeze -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        .auth-wrapper {
            display: flex; min-height: 100vh;
            align-items: center; justify-content: center;
            background: radial-gradient(circle at top, var(--bg-hover) 0%, var(--bg-dark) 100%);
            position: relative; overflow: hidden;
        }
        .auth-wrapper::before {
            content: ''; position: absolute; width: 40vw; height: 40vw; 
            background: var(--gold-glow); border-radius: 50%; 
            top: -20vw; right: -20vw; filter: blur(100px); opacity: 0.4;
        }
        .auth-box {
            width: 100%; max-width: 400px; padding: 3rem;
            z-index: 10; border-color: rgba(212, 175, 55, 0.4);
        }
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--text-muted); }
        .form-control { 
            width: 100%; padding: 0.8rem; border-radius: 8px; 
            border: 1px solid var(--border-color); background: rgba(0,0,0,0.5); 
            color: var(--text-main); font-family: 'Outfit', sans-serif; 
        }
        .form-control:focus { outline: none; border-color: var(--gold-primary); box-shadow: 0 0 10px var(--gold-glow); }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="glass-panel auth-box animate-fade-in">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h1 class="title-gold" style="font-size: 2rem; margin-bottom: 0.5rem;"><i class="ph-fill ph-mask-happy"></i> ART-HUB</h1>
                <p class="text-muted" style="font-size: 0.9rem;">Sistem Manajemen Eksekutif</p>
            </div>

            <!-- Session Status Breeze -->
            @if (session('status'))
                <div style="background: var(--success-glow); border: 1px solid var(--success); padding: 0.8rem; border-radius: 8px; margin-bottom: 1.5rem; color: var(--success); font-size: 0.85rem;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email</label>
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="admin@cahayagumilang.com">
                    @error('email') <span class="text-danger" style="font-size: 0.8rem; margin-top: 0.3rem; display: block;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Kata Sandi</label>
                    <input id="password" type="password" name="password" class="form-control" required placeholder="••••••••">
                    @error('password') <span class="text-danger" style="font-size: 0.8rem; margin-top: 0.3rem; display: block;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" style="display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: var(--text-muted);">
                        <input type="checkbox" name="remember" style="accent-color: var(--gold-primary);"> Ingat Saya
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="color: var(--gold-primary); text-decoration: none;">Lupa Sandi?</a>
                    @endif
                </div>

                <button type="submit" class="btn btn-gold" style="width: 100%; border: none; padding: 1rem;">
                    MASUK SISTEM <i class="ph ph-arrow-right"></i>
                </button>
                
                <div style="text-align: center; margin-top: 1.5rem;">
                    <a href="{{ route('register') }}" style="color: var(--text-muted); font-size: 0.85rem; text-decoration: none;">Belum punya akses? Daftar Roster</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
