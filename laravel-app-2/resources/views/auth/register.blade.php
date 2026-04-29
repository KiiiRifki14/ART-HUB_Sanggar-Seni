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

        /* Radio Buttons */
        .role-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .role-option {
            flex: 1;
            position: relative;
        }
        .role-option input[type="radio"] {
            display: none;
        }
        .role-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .role-option input[type="radio"]:checked + .role-label {
            border-color: var(--gold-primary);
            color: var(--gold-primary);
            background: rgba(212, 175, 55, 0.05);
        }
        .radio-circle {
            width: 14px;
            height: 14px;
            border: 1px solid var(--text-muted);
            border-radius: 50%;
            display: inline-block;
            position: relative;
        }
        .role-option input[type="radio"]:checked + .role-label .radio-circle {
            border-color: var(--gold-primary);
        }
        .role-option input[type="radio"]:checked + .role-label .radio-circle::after {
            content: '';
            width: 6px;
            height: 6px;
            background: var(--gold-primary);
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Personnel Info Box */
        .personnel-info-box {
            display: none;
            border: 1px solid var(--border-color);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            background: rgba(0,0,0,0.2);
            animation: slideDown 0.4s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .personnel-info-box h3 {
            font-size: 14px;
            color: var(--text-main);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }
        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 10px;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-muted);
            cursor: pointer;
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
                <label class="form-label mb-2">I am a:</label>
                <div class="role-selector">
                    <label class="role-option">
                        <input type="radio" name="role_type" value="client" checked onchange="togglePersonnelBox()">
                        <div class="role-label"><span class="radio-circle"></span> Client</div>
                    </label>
                    <label class="role-option">
                        <input type="radio" name="role_type" value="personnel" onchange="togglePersonnelBox()">
                        <div class="role-label"><span class="radio-circle"></span> Personnel</div>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Enter your full name">
                @error('name')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" id="phone" name="phone" class="form-input" value="{{ old('phone') }}" required placeholder="0812-3456-7890">
                @error('phone')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required autocomplete="username" placeholder="Enter your email">
                @error('email')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" class="form-input" required autocomplete="new-password" placeholder="Buat password yang kuat" style="padding-right: 40px;">
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

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div style="position: relative;">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required autocomplete="new-password" placeholder="Confirm your password" style="padding-right: 40px;">
                    <span onclick="togglePassword('password_confirmation', this)" style="position: absolute; right: 12px; top: 12px; cursor: pointer; color: var(--text-muted);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>
                    </span>
                </div>
                @error('password_confirmation')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div id="personnelBox" class="personnel-info-box">
                <h3>Personnel Information</h3>
                
                <div class="form-group">
                    <label class="form-label">Primary Job / Occupation</label>
                    <input type="text" name="day_job_name" class="form-input" placeholder="e.g., Office Worker, Freelancer, Student">
                </div>

                <div class="form-group">
                    <label class="form-label">Office Hours (If applicable)</label>
                    <div style="display:flex; gap:10px;">
                        <div style="flex:1;">
                            <span style="font-size:11px; color:var(--text-muted); display:block; margin-bottom:4px;">From</span>
                            <input type="time" name="day_job_start" class="form-input">
                        </div>
                        <div style="flex:1;">
                            <span style="font-size:11px; color:var(--text-muted); display:block; margin-bottom:4px;">To</span>
                            <input type="time" name="day_job_end" class="form-input">
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top:15px;">
                    <label class="form-label">Dance Specialties</label>
                    <div class="checkbox-group">
                        <label class="checkbox-item"><input type="checkbox" name="dance_specialties[]" value="jaipong"> Jaipong / Sunda</label>
                        <label class="checkbox-item"><input type="checkbox" name="dance_specialties[]" value="ramayana"> Ramayana / Jawa</label>
                        <label class="checkbox-item"><input type="checkbox" name="dance_specialties[]" value="legong"> Legong / Bali</label>
                        <label class="checkbox-item"><input type="checkbox" name="dance_specialties[]" value="other"> Other Traditional</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-primary">CREATE ACCOUNT</button>

            <div style="text-align: center; margin-top: 25px; font-size: 14px; color: var(--text-muted);">
                Already have an account? <a href="{{ route('login') }}" class="link">Login here</a>
            </div>
        </form>
    </div>

    <script>
        function togglePersonnelBox() {
            var isPersonnel = document.querySelector('input[name="role_type"]:checked').value === 'personnel';
            document.getElementById('personnelBox').style.display = isPersonnel ? 'block' : 'none';
        }

        function togglePassword(inputId, iconEl) {
            var input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                iconEl.style.color = "var(--gold-primary)"; // highlight icon
            } else {
                input.type = "password";
                iconEl.style.color = "var(--text-muted)";
            }
        }
        
        // Cek onload
        togglePersonnelBox();
    </script>
</body>

</html>