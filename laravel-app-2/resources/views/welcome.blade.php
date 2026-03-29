<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ART-HUB | Sanggar Cahaya Gumilang</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        /* === PENGATURAN DASAR & TEMA WARNA === */
        :root {
            --primary: #D4AF37;       /* Emas ART-HUB */
            --primary-hover: #b8962c;
            --dark: #050505;          /* Base Paling Gelap */
            --light: #0a0b0d;         /* Background Gelap Utama */
            --gray: #272a30;          /* Batas dan Komponen Halus */
            --text-main: #f3f4f6;     /* Teks Utama Terang */
            --text-light: #9ca3af;    /* Teks Pudar / Muted */
            --card-bg: #15171a;       /* Latar Belakang Kotak (Card) */
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            color: var(--text-main);
            background-color: var(--light);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* === HEADER === */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            background-color: rgba(21, 23, 26, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--gray);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .logo {
            font-weight: 700;
            font-size: 24px;
            color: var(--text-main);
            letter-spacing: 1px;
        }

        .logo span {
            color: var(--primary);
        }

        /* Tombol Umum */
        .btn {
            display: inline-block;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            border: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--dark);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-outline {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
        }

        .btn-outline:hover {
            background-color: var(--primary);
            color: var(--dark);
        }

        /* === HERO SECTION === */
        .hero {
            display: flex;
            gap: 40px;
            padding: 60px 0;
            align-items: center;
        }

        .video-placeholder {
            flex: 1;
            background: linear-gradient(135deg, var(--card-bg) 0%, var(--light) 100%);
            border: 1px solid var(--gray);
            border-radius: 16px;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        /* Ikon Play bohongan untuk visualisasi video */
        .video-placeholder::after {
            content: "▶";
            font-size: 50px;
            color: var(--primary);
            background: rgba(0, 0, 0, 0.5);
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 2px solid var(--primary);
        }

        .hero-content {
            flex: 1;
        }

        .hero-content h1 {
            font-size: 42px;
            color: var(--text-main);
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .hero-content p {
            font-size: 16px;
            color: var(--text-light);
            margin-bottom: 30px;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
        }

        /* === BAGIAN UMUM (SECTION) === */
        section {
            padding: 60px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 32px;
            color: var(--text-main);
            font-weight: 700;
            position: relative;
            display: inline-block;
            margin-bottom: 10px;
        }

        /* Garis bawah estetis di judul section */
        .section-title::after {
            content: '';
            position: absolute;
            width: 50%;
            height: 4px;
            background-color: var(--primary);
            bottom: -10px;
            left: 25%;
            border-radius: 2px;
        }

        /* === STATISTIK SECTION === */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--gray);
            border-radius: 12px;
            text-align: center;
            padding: 30px 20px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
        }

        .stat-card h3 {
            font-size: 36px;
            color: var(--primary);
            margin-bottom: 5px;
        }

        /* === LAYANAN KAMI === */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .service-card {
            background: var(--card-bg);
            border: 1px solid var(--gray);
            border-radius: 12px;
            padding: 30px;
            border-top: 4px solid var(--primary);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .service-card:hover {
            border-color: var(--primary);
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.1);
        }

        .service-card h4 {
            font-size: 20px;
            color: var(--text-main);
            margin-bottom: 15px;
        }

        /* === WARISAN PENDIRI === */
        .founder-content {
            display: flex;
            gap: 40px;
            background: var(--card-bg);
            border: 1px solid var(--gray);
            padding: 40px;
            border-radius: 16px;
            align-items: center;
        }

        .photo-placeholder {
            flex: 1;
            background-color: var(--dark);
            border: 1px solid var(--gray);
            border-radius: 12px;
            min-height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-weight: 600;
        }

        .founder-details {
            flex: 1.5;
        }

        .founder-name {
            font-size: 28px;
            color: var(--text-main);
            font-weight: 700;
        }

        .year-badge {
            background: var(--primary);
            color: var(--dark);
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            margin: 10px 0 20px 0;
            font-size: 14px;
            font-weight: 600;
        }

        .founder-desc {
            font-size: 16px;
            font-style: italic;
            border-left: 4px solid var(--primary);
            padding-left: 15px;
            color: var(--text-light);
            margin-bottom: 25px;
        }

        .founder-achievements {
            background: var(--dark);
            border: 1px solid var(--gray);
            padding: 20px;
            border-radius: 8px;
            font-size: 14px;
        }

        /* === PORTOFOLIO PENARI === */
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .portfolio-item {
            background-color: var(--card-bg);
            border: 1px solid var(--gray);
            border-radius: 12px;
            min-height: 250px;
            display: flex;
            align-items: flex-end;
            padding: 20px;
            position: relative;
            overflow: hidden;
            transition: border-color 0.3s ease;
        }

        .portfolio-item:hover {
            border-color: var(--primary);
        }

        .portfolio-item span {
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid var(--primary);
            color: var(--primary);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        /* === TESTIMONI KLIEN === */
        .testi-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        .testi-card {
            background: var(--card-bg);
            border: 1px solid var(--gray);
            padding: 30px;
            border-radius: 12px;
            position: relative;
        }

        .stars-testi {
            color: var(--primary);
            font-size: 24px;
            margin-bottom: 15px;
        }

        /* === FOOTER === */
        footer {
            background-color: var(--dark);
            border-top: 1px solid var(--gray);
            color: var(--text-main);
            padding: 60px 20px 20px;
            text-align: center;
        }

        .footer-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--primary);
        }

        .footer-subtitle {
            color: var(--text-light);
            margin-bottom: 40px;
        }

        .footer-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 50px;
        }

        .footer-blocks {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .footer-block {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--gray);
            padding: 20px 40px;
            border-radius: 8px;
            min-width: 250px;
        }

        .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            color: var(--text-light);
            font-size: 14px;
        }

        /* Responsif */
        @media (max-width: 992px) {

            .hero,
            .founder-content {
                flex-direction: column;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .portfolio-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .testi-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .services-grid {
                grid-template-columns: 1fr;
            }

            .portfolio-grid {
                grid-template-columns: 1fr;
            }

            .footer-blocks {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <header>
        <div class="container header-content">
            <div class="logo">ART<span>HUB</span></div>
            <a href="/login" class="btn btn-primary">Login</a>
        </div>
    </header>

    <main>
        <section class="container hero">
            <div class="video-placeholder"></div>
            <div class="hero-content">
                <h1>Harmoni Gerak, <br>Melestarikan Budaya</h1>
                <p>Sanggar Cahaya Gumilang adalah tempat pelestarian seni tari tradisional Indonesia dengan dedikasi penuh. Kami menghadirkan pertunjukan kelas dunia dengan seniman berpengalaman.</p>
                <div class="hero-buttons">
                    <a href="#" class="btn btn-primary">Hubungi Kami</a>
                    <a href="#" class="btn btn-outline">Lihat Portofolio</a>
                </div>
            </div>
        </section>

        <section class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>25+</h3>
                    <p>Tahun Berdiri</p>
                </div>
                <div class="stat-card">
                    <h3>12</h3>
                    <p>Penari Profesional</p>
                </div>
                <div class="stat-card">
                    <h3>100+</h3>
                    <p>Event/Tahun</p>
                </div>
                <div class="stat-card">
                    <h3>5.0</h3>
                    <p>Rating Klien</p>
                </div>
            </div>
        </section>

        <section class="container">
            <div class="section-header">
                <h2 class="section-title">Layanan Kami</h2>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <h4>Upacara Pernikahan</h4>
                    <p>Tarian pembuka, selingan, dan penutup memukau untuk menyempurnakan hari bahagia Anda.</p>
                </div>
                <div class="service-card">
                    <h4>Festival & Event</h4>
                    <p>Penampilan spektakuler di festival budaya, pameran, dan acara korporat berskala besar.</p>
                </div>
                <div class="service-card">
                    <h4>WorkShop & Kelas</h4>
                    <p>Pelatihan tari tradisional interaktif yang dirancang khusus untuk pemula hingga mahir.</p>
                </div>
            </div>
        </section>

        <section class="container">
            <div class="section-header">
                <h2 class="section-title">Warisan Pendiri</h2>
            </div>
            <div class="founder-content">
                <div class="photo-placeholder">
                    [Foto Almarhum]
                </div>
                <div class="founder-details">
                    <h3 class="founder-name">Alm. Bpk. Kusmana</h3>
                    <div class="year-badge">1945 - 2020</div>
                    <p class="founder-desc">"Seni daerah adalah identitas kita. Membawanya ke panggung internasional adalah tugas kita bersama."</p>
                    <p style="margin-bottom: 20px;">Pendiri Sanggar Cahaya Gumilang yang telah mendedikasikan seluruh hidupnya untuk melestarikan seni tari tradisional Indonesia. Visinya terus kami jaga dan kembangkan hingga hari ini.</p>
                    <div class="founder-achievements">
                        <strong>Pencapaian Utama:</strong><br>
                        Juara Festival Tari Nasional 1998 • Duta Budaya Jawa Barat • Mentor 100+ Penari Profesional
                    </div>
                </div>
            </div>
        </section>

        <section class="container">
            <div class="section-header">
                <h2 class="section-title">Portofolio Penari</h2>
            </div>
            <div class="portfolio-grid">
                <div class="portfolio-item"><span>Penari 1</span></div>
                <div class="portfolio-item"><span>Penari 2</span></div>
                <div class="portfolio-item"><span>Penari 3</span></div>
                <div class="portfolio-item"><span>Penari 4</span></div>
                <div class="portfolio-item"><span>Penari 5</span></div>
                <div class="portfolio-item"><span>Penari 6</span></div>
                <div class="portfolio-item"><span>Penari 7</span></div>
                <div class="portfolio-item"><span>Penari 8</span></div>
            </div>
            <div style="text-align: center;">
                <a href="#" class="btn btn-outline">Lihat Detail Semua Penari</a>
            </div>
        </section>

        <section class="container">
            <div class="section-header">
                <h2 class="section-title">Apa Kata Mereka</h2>
            </div>
            <div class="testi-grid">
                <div class="testi-card">
                    <div class="stars-testi">★★★★★</div>
                    <p>"Penampilan yang luar biasa! Sangat profesional dan membuat acara pernikahan kami terasa sangat magis dan berbudaya."</p>
                    <br><strong>- Keluarga Bpk. Andi</strong>
                </div>
                <div class="testi-card">
                    <div class="stars-testi">★★★★★</div>
                    <p>"Tim penari sangat terorganisir dengan baik. Kostumnya indah dan gerakannya sangat memukau tamu dari luar negeri."</p>
                    <br><strong>- PT. Event Organizer</strong>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-title">Sanggar Cahaya Gumilang</div>
            <div class="footer-subtitle">Melestarikan budaya melalui keindahan harmoni dan gerak tari tradisional.</div>

            <div class="footer-buttons">
                <button class="btn btn-primary">Hubungi Kami</button>
                <button class="btn btn-outline" style="border-color: #fff; color: #fff;">WhatsApp</button>
                <button class="btn btn-outline" style="border-color: #fff; color: #fff;">Email</button>
            </div>

            <div class="footer-blocks">
                <div class="footer-block">
                    <h4>Lokasi Kami</h4>
                    <p style="font-size: 14px; margin-top: 10px; color: #9ca3af;">Jl. Seni Budaya No. 123<br>Jawa Barat, Indonesia</p>
                </div>
                <div class="footer-block">
                    <h4>Jam Operasional</h4>
                    <p style="font-size: 14px; margin-top: 10px; color: #9ca3af;">Senin - Jumat: 08.00 - 17.00<br>Sabtu: 09.00 - 15.00</p>
                </div>
            </div>

            <div class="copyright">
                &copy; 2026 Sanggar Cahaya Gumilang. All rights reserved.
            </div>
        </div>
    </footer>

</body>

</html>