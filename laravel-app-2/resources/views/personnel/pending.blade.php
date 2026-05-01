<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Persetujuan – ART-HUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark:      #800000;
            --bg-card:      #FDFBF7;
            --text-main:    #1A1A1A;
            --text-muted:   #7A7A7A;
            --gold-primary: #D4AF37;
            --gold-dark:    #b5952f;
            --border-color: #E8E3D9;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--bg-dark), #4a0000);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pending-container {
            background: var(--bg-card);
            padding: 50px 40px;
            border-radius: 16px;
            max-width: 500px;
            text-align: center;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .icon {
            font-size: 60px;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0); }
        }

        h2 {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            margin-bottom: 15px;
            color: var(--gold-dark);
        }

        p {
            font-size: 15px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 30px;
        }

        p strong, p b {
            color: var(--text-main);
        }

        .btn-wrapper {
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            background: transparent;
            color: var(--bg-dark);
            border: 1px solid var(--bg-dark);
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }

        .btn:hover {
            background: var(--bg-dark);
            color: #FFFFFF;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(128, 0, 0, 0.25);
        }
    </style>
</head>
<body>

    <div class="pending-container">
        <div class="icon">⌛</div>
        <h2>Menunggu Konfirmasi Admin</h2>
        <p>Halo, <strong>{{ Auth::user()->name }}</strong>. <br><br>
        Akun Anda sebagai Personel telah berhasil diregistrasi, namun <b>membutuhkan persetujuan dari Admin / Pimpinan Sanggar</b> sebelum dapat mengakses Dashboard Personel.</p>
        
        <p>Silakan hubungi staf sanggar atau tunggu beberapa saat.</p>
        
        <div class="btn-wrapper">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn" style="cursor: pointer;">Keluar (Logout)</button>
            </form>
        </div>
    </div>

</body>
</html>
