<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ART-HUB Sanggar Cahaya Gumilang</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* ── NEW PALETTE: Deep Maroon + Clean White + Gold ── */
            --bg-dark:      #800000;        /* Body: Deep Maroon */
            --bg-card:      #FFFFFF;        /* Form Panel: Clean White */
            --bg-hover:     #FDFBF7;
            --text-main:    #1A1A1A;        /* Teks gelap di atas putih */
            --text-muted:   #7A7A7A;
            --border-color: #E8E3D9;
            --gold-primary: #D4AF37;        /* Accent Gold */
            --gold-light:   #F3CE5E;
            --gold-dark:    #b5952f;
            --gold-glow:    rgba(212,175,55,0.25);
            --maroon-btn:   #800000;
            --maroon-btn-hover: #4a0000;
            --glass-border: rgba(255,255,255,0.2);
            --error:        #FF0000;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--bg-dark), #4a0000);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Ambient glow */
        body::before {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: rgba(212,175,55,0.15); /* Gold glow */
            border-radius: 50%;
            filter: blur(120px);
            top: -100px; left: -100px;
            animation: floatGlow 8s ease-in-out infinite alternate;
        }
        body::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(212,175,55,0.2); /* Gold glow */
            border-radius: 50%;
            filter: blur(100px);
            bottom: -80px; right: -80px;
            animation: floatGlow 10s ease-in-out infinite alternate-reverse;
        }
        @keyframes floatGlow {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 20px) scale(1.1); }
        }


        .login-container {
            display: flex;
            background: var(--bg-card);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            border: 1px solid var(--glass-border);
            width: 100%;
            max-width: 1000px;
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-visual {
            flex: 1;
            background: linear-gradient(135deg, #1C0508, #4a0000);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
        }

        .login-visual-content {
            text-align: center;
            padding: 3rem;
            z-index: 1;
        }

        .login-visual-content h1 {
            font-size: 3rem;
            color: var(--gold-primary);
            font-weight: 800;
            text-shadow: 0 0 30px var(--gold-glow);
            margin-bottom: 0.5rem;
            letter-spacing: 3px;
        }

        .login-visual-content p {
            color: var(--text-muted);
            font-size: 1rem;
            line-height: 1.6;
        }

        .visual-badge {
            display: inline-block;
            padding: 0.4rem 1.2rem;
            background: var(--gold-glow);
            border: 1px solid var(--gold-primary);
            border-radius: 50px;
            color: var(--gold-primary);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 1.5rem;
        }

        .login-form-panel {
            flex: 1;
            padding: 50px 45px;
            background: var(--bg-card);
        }

        .logo {
            font-weight: 800;
            font-size: 28px;
            color: var(--text-main);
            text-align: center;
            margin-bottom: 8px;
            letter-spacing: 2px;
        }
        .logo span { color: var(--gold-primary); }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-header p { color: var(--text-muted); font-size: 0.9rem; }

        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-main);
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: #FFFFFF;
            color: var(--text-main);
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--bg-dark);
            box-shadow: 0 0 0 3px rgba(128,0,0,0.15);
            background: #FDFBF7;
        }
        .form-input::placeholder { color: var(--text-muted); }

        .error-msg {
            color: var(--error);
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        .checkbox-input {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: var(--gold-primary);
        }

        .btn-primary {
            display: block;
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            border: none;
            background: var(--maroon-btn);
            color: #FFFFFF;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: 0 4px 15px rgba(128,0,0,0.25);
        }
        .btn-primary:hover {
            background: var(--maroon-btn-hover);
            transform: translateY(-2px) scale(1.01);
            box-shadow: 0 6px 25px rgba(128,0,0,0.35);
        }

        .link {
            color: var(--gold-primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: 0.2s;
        }
        .link:hover { color: var(--gold-light); }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @media (max-width: 768px) {
            .login-container { flex-direction: column; }
            .login-visual { min-height: 200px; }
            .login-visual-content h1 { font-size: 2rem; }
            .login-form-panel { padding: 35px 25px; }
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-visual">
            <div class="login-visual-content">
                <h1>ART-HUB</h1>
                <p>Sanggar Cahaya Gumilang<br>Management & Financial Security System</p>
                <div class="visual-badge">Est. Subang — Sejak 1985</div>
            </div>
        </div>
        <div class="login-form-panel">
            <div class="logo">ART<span>HUB</span></div>
            <div class="form-header">
                <p>Login ke sistem booking tari profesional</p>
            </div>

            @if (session('status'))
            <div style="color: var(--gold-primary); margin-bottom: 15px; text-align: center; font-size: 14px; background: var(--gold-glow); padding: 0.8rem; border-radius: 8px;">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Masukkan email Anda">
                    @error('email')
                    <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" class="form-input" required autocomplete="current-password" placeholder="Masukkan password Anda" style="padding-right: 40px;">
                        <span onclick="togglePassword('password', this)" style="position: absolute; right: 12px; top: 12px; cursor: pointer; color: var(--text-muted);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                            </svg>
                        </span>
                    </div>
                    @error('password')
                    <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group flex-between">
                    <div class="checkbox-group" style="margin-bottom: 0;">
                        <input id="remember_me" type="checkbox" class="checkbox-input" name="remember">
                        <label for="remember_me" style="font-size: 13px; color: var(--text-muted); cursor: pointer;">Remember me</label>
                    </div>

                    @if (Route::has('password.request'))
                    <a class="link" href="{{ route('password.request') }}">Lupa Password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-primary">LOGIN</button>

                <div style="text-align: center; margin-top: 25px; font-size: 14px; color: var(--text-muted);">
                    Belum punya akun? <a href="{{ route('register') }}" class="link">Register di sini</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconEl) {
            var input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                iconEl.style.color = "var(--gold-primary)";
            } else {
                input.type = "password";
                iconEl.style.color = "var(--text-muted)";
            }
        }
    </script>
</body>

</html>