<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ART-HUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #d97706;
            --primary-hover: #b45309;
            --dark: #1f2937;
            --light: #f9fafb;
            --gray: #e5e7eb;
            --text-main: #374151;
            --text-light: #6b7280;
            --error: #ef4444;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            display: flex;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 1000px;
        }

        .login-visual {
            flex: 1;
            /* GANTI URL INI DENGAN GAMBAR PENARIMU */
            background-image: url('https://images.unsplash.com/photo-1543160350-c75c889f0ea8?q=80&w=800&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .login-visual::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
        }

        .login-form-panel {
            flex: 1;
            padding: 60px 50px;
        }

        .logo {
            font-weight: 700;
            font-size: 32px;
            color: var(--dark);
            text-align: center;
            margin-bottom: 10px;
        }

        .logo span {
            color: var(--primary);
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header p {
            color: var(--text-light);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--gray);
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
        }

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
            accent-color: var(--primary);
        }

        .btn-primary {
            display: block;
            width: 100%;
            padding: 14px;
            border-radius: 8px;
            border: none;
            background-color: var(--primary);
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            text-align: center;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .link:hover {
            color: var(--primary-hover);
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .login-visual {
                min-height: 250px;
            }

            .login-form-panel {
                padding: 40px 20px;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-visual"></div>
        <div class="login-form-panel">
            <div class="logo">ART<span>HUB</span></div>
            <div class="form-header">
                <p>Login ke sistem booking tari profesional</p>
            </div>

            @if (session('status'))
            <div style="color: green; margin-bottom: 15px; text-align: center; font-size: 14px;">
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
                    <input type="password" id="password" name="password" class="form-input" required autocomplete="current-password" placeholder="Masukkan password Anda">
                    @error('password')
                    <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group flex-between">
                    <div class="checkbox-group" style="margin-bottom: 0;">
                        <input id="remember_me" type="checkbox" class="checkbox-input" name="remember">
                        <label for="remember_me" style="font-size: 14px; color: var(--text-light); cursor: pointer;">Remember me</label>
                    </div>

                    @if (Route::has('password.request'))
                    <a class="link" href="{{ route('password.request') }}">Forgot Password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-primary">LOGIN</button>

                <div style="text-align: center; margin-top: 25px; font-size: 14px; color: var(--text-light);">
                    Belum punya akun? <a href="{{ route('register') }}" class="link">Register di sini</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>