<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | ART-HUB Sanggar Cahaya Gumilang</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #0a0b0d;
            --bg-card: #15171a;
            --bg-hover: #1e2126;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --border-color: #272a30;
            --gold-primary: #D4AF37;
            --gold-light: #F3E5AB;
            --gold-dark: #AA8C2C;
            --gold-glow: rgba(212, 175, 55, 0.3);
            --glass-border: rgba(255, 255, 255, 0.05);
            --error: #ef4444;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 350px; height: 350px;
            background: var(--gold-glow);
            border-radius: 50%;
            filter: blur(120px);
            top: -80px; right: -80px;
            animation: floatGlow 8s ease-in-out infinite alternate;
        }
        body::after {
            content: '';
            position: absolute;
            width: 250px; height: 250px;
            background: rgba(212, 175, 55, 0.12);
            border-radius: 50%;
            filter: blur(100px);
            bottom: -60px; left: -60px;
            animation: floatGlow 10s ease-in-out infinite alternate-reverse;
        }
        @keyframes floatGlow {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(20px, 15px) scale(1.1); }
        }

        .register-container {
            background: var(--bg-card);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            border: 1px solid var(--glass-border);
            width: 100%;
            max-width: 550px;
            padding: 45px 40px;
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo {
            font-weight: 800;
            font-size: 28px;
            color: var(--text-main);
            text-align: center;
            margin-bottom: 5px;
            letter-spacing: 2px;
        }
        .logo span { color: var(--gold-primary); }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-header h2 {
            font-size: 22px;
            color: var(--text-main);
            margin-bottom: 5px;
        }
        .form-header p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--gold-light);
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: rgba(0,0,0,0.3);
            color: var(--text-main);
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 3px var(--gold-glow);
        }
        .form-input::placeholder { color: var(--text-muted); }

        .error-msg {
            color: var(--error);
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .btn-primary {
            display: block;
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-dark));
            color: var(--bg-dark);
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            margin-top: 28px;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 0.95rem;
            box-shadow: 0 4px 15px var(--gold-glow);
        }
        .btn-primary:hover {
            transform: translateY(-2px) scale(1.01);
            box-shadow: 0 6px 25px rgba(212, 175, 55, 0.5);
            filter: brightness(1.1);
        }

        .link {
            color: var(--gold-primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: 0.2s;
        }
        .link:hover { color: var(--gold-light); }

        @media (max-width: 480px) {
            .register-container { padding: 35px 22px; }
        }
    </style>
</head>

<body>

    <div class="register-container">
        <div class="logo">ART<span>HUB</span></div>
        <div class="form-header">
            <h2>Buat Akun Baru</h2>
            <p>Bergabunglah dengan komunitas seni kami</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan nama Anda">
                @error('name')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required autocomplete="username" placeholder="Masukkan alamat email aktif">
                @error('email')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-input" required autocomplete="new-password" placeholder="Buat password yang kuat">
                @error('password')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required autocomplete="new-password" placeholder="Ulangi password Anda">
                @error('password_confirmation')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-primary">REGISTER</button>

            <div style="text-align: center; margin-top: 25px; font-size: 14px; color: var(--text-muted);">
                Sudah punya akun? <a href="{{ route('login') }}" class="link">Login di sini</a>
            </div>
        </form>
    </div>

</body>

</html>