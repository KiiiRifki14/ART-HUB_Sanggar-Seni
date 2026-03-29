<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Personel - ART-HUB</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        .auth-wrapper {
            display: flex; min-height: 100vh;
            align-items: center; justify-content: center;
            background: var(--bg-dark);
            position: relative; overflow: hidden;
            padding: 2rem;
        }
        .auth-box {
            width: 100%; max-width: 450px; padding: 2.5rem;
            z-index: 10; border-color: rgba(255, 255, 255, 0.1);
        }
        .form-group { margin-bottom: 1.2rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--text-muted); }
        .form-control { 
            width: 100%; padding: 0.8rem; border-radius: 8px; 
            border: 1px solid var(--border-color); background: rgba(0,0,0,0.5); 
            color: var(--text-main); font-family: 'Outfit', sans-serif; 
        }
        .form-control:focus { outline: none; border-color: var(--gold-primary); }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="glass-panel auth-box animate-fade-in">
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <h1 class="title-gold" style="font-size: 1.8rem; margin-bottom: 0.2rem;">Pendaftaran Kru</h1>
                <p class="text-muted" style="font-size: 0.85rem;">Bergabung ke sistem roster Cahaya Gumilang</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus autocomplete="name">
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email</label>
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autocomplete="username">
                </div>

                <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label" for="password">Kata Sandi</label>
                        <input id="password" type="password" name="password" class="form-control" required autocomplete="new-password">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Konfirmasi</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                    </div>
                </div>

                <button type="submit" class="btn btn-outline" style="width: 100%; padding: 1rem; border-color: var(--gold-primary); color: var(--gold-light);">
                    Daftar Sebagai Personel
                </button>

                <div style="text-align: center; margin-top: 1.5rem;">
                    <a href="{{ route('login') }}" class="text-muted" style="font-size: 0.85rem; text-decoration: none;">Sudah punya akun? Masuk</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
