<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Persetujuan – ART-HUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark:      #8B1A2A;
            --bg-card:      #FFFFFF;
            --text-main:    #1A1817;
            --text-muted:   #847B78;
            --gold-primary: #C5A028;
            --gold-dark:    #8B1A2A;
            --border-color: rgba(197,160,40,0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #FAF9F6;
            background-image:
                radial-gradient(ellipse at 10% 20%, rgba(139,26,42,0.05) 0%, transparent 55%),
                radial-gradient(ellipse at 90% 80%, rgba(197,160,40,0.03) 0%, transparent 50%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pending-container {
            background: var(--bg-card);
            padding: 50px 40px;
            border-radius: 24px;
            max-width: 500px;
            text-align: center;
            border: 1px solid var(--border-color);
            box-shadow: 0 8px 30px rgba(139, 26, 42, 0.05);
            animation: fadeIn 0.8s ease;
        }

        @media (max-width: 600px) {
            .pending-container {
                margin: 20px;
                padding: 40px 24px;
            }
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
            color: var(--gold-primary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 26, 42, 0.25);
        }
    </style>
</head>
<body>

    <div class="pending-container">
        @php
            $personnel = \App\Models\Personnel::where('user_id', Auth::id())->first();
            $status = $personnel ? $personnel->status : 'pending_verification';
        @endphp

        @if($status === 'deactivated')
            <div class="icon">⏸️</div>
            <h2>Akun Dinonaktifkan Sementara</h2>
            <p>Halo, <strong>{{ Auth::user()->name }}</strong>. <br><br>
            Akun Anda sebagai Personel saat ini <b>sedang dinonaktifkan sementara</b> oleh Admin. Anda tidak akan muncul dalam daftar plotting pementasan.</p>
            <p>Silakan hubungi Pimpinan Sanggar untuk informasi lebih lanjut dan pengaktifan kembali.</p>
        @else
            <div class="icon">⌛</div>
            <h2>Menunggu Konfirmasi Admin</h2>
            <p>Halo, <strong>{{ Auth::user()->name }}</strong>. <br><br>
            Akun Anda sebagai Personel telah berhasil diregistrasi, namun <b>membutuhkan persetujuan dari Admin / Pimpinan Sanggar</b> sebelum dapat mengakses Dashboard Personel.</p>
            <p>Silakan hubungi staf sanggar atau tunggu beberapa saat.</p>
        @endif
        
        <div class="btn-wrapper">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn" style="cursor: pointer;">Keluar (Logout)</button>
            </form>
        </div>
    </div>

</body>
</html>
