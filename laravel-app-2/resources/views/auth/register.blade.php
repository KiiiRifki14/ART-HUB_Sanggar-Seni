<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | ART-HUB</title>
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

        .register-container {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 550px;
            padding: 50px 40px;
        }

        .logo {
            font-weight: 700;
            font-size: 32px;
            color: var(--dark);
            text-align: center;
            margin-bottom: 5px;
        }

        .logo span {
            color: var(--primary);
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h2 {
            font-size: 24px;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .form-header p {
            color: var(--text-light);
            font-size: 14px;
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
            margin-top: 30px;
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

        @media (max-width: 480px) {
            .register-container {
                padding: 40px 20px;
            }
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

            <div style="text-align: center; margin-top: 25px; font-size: 14px; color: var(--text-light);">
                Sudah punya akun? <a href="{{ route('login') }}" class="link">Login di sini</a>
            </div>
        </form>
    </div>

</body>

</html>