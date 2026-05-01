<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;family=Noto+Serif:ital,wght@0,400;0,600;0,700;1,400&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "on-surface-variant": "#504442",
                    "on-secondary": "#ffffff",
                    "inverse-surface": "#2f312f",
                    "on-surface": "#1a1c1a",
                    "on-primary": "#ffffff",
                    "surface-container-high": "#e9e8e5",
                    "primary-fixed-dim": "#e5beb5",
                    "error": "#ba1a1a",
                    "tertiary-fixed": "#ffdbce",
                    "outline-variant": "#d4c3bf",
                    "primary-fixed": "#ffdad2",
                    "surface-variant": "#e3e2e0",
                    "on-primary-fixed-variant": "#5c403a",
                    "on-error-container": "#93000a",
                    "on-primary-container": "#c19c94",
                    "surface-container-low": "#f4f3f1",
                    "on-secondary-container": "#6e5c00",
                    "tertiary-container": "#4e352c",
                    "secondary-fixed-dim": "#e9c400",
                    "on-error": "#ffffff",
                    "on-primary-fixed": "#2b1611",
                    "tertiary": "#352017",
                    "primary": "#361f1a",
                    "on-secondary-fixed-variant": "#544600",
                    "secondary-fixed": "#ffe16d",
                    "secondary-container": "#fcd400",
                    "surface-container-lowest": "#ffffff",
                    "surface-container-highest": "#e3e2e0",
                    "on-tertiary": "#ffffff",
                    "on-secondary-fixed": "#221b00",
                    "inverse-on-surface": "#f2f1ee",
                    "on-tertiary-fixed": "#2b160f",
                    "inverse-primary": "#e5beb5",
                    "surface": "#faf9f6",
                    "on-background": "#1a1c1a",
                    "outline": "#827471",
                    "surface-dim": "#dbdad7",
                    "primary-container": "#4e342e",
                    "on-tertiary-container": "#c09d91",
                    "tertiary-fixed-dim": "#e4beb2",
                    "background": "#faf9f6",
                    "error-container": "#ffdad6",
                    "on-tertiary-fixed-variant": "#5b4137",
                    "surface-tint": "#755750",
                    "secondary": "#705d00",
                    "surface-container": "#efeeeb",
                    "surface-bright": "#faf9f6"
            },
            "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
            },
            "fontFamily": {
                    "headline": ["Noto Serif"],
                    "body": ["Manrope"],
                    "label": ["Manrope"]
            }
          },
        },
      }
    </script>
<style>
      .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
      }
      .no-scrollbar::-webkit-scrollbar { display: none; }
      .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
      .fill-1 { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-background text-on-surface font-body selection:bg-secondary-container/30">
<!-- TopNavBar (Shared Component) -->
<nav class="fixed top-0 w-full z-50 flex justify-between items-center px-6 py-4 bg-[#faf9f6]/80 dark:bg-[#1a1c1a]/80 backdrop-blur-md shadow-[0_20px_40px_rgba(78,52,46,0.04)]">
<div class="flex items-center gap-8">
<span class="font-serif text-xl tracking-tight text-[#361f1a] dark:text-[#faf9f6] font-headline font-semibold">Cahaya Gumilang</span>
<div class="hidden md:flex items-center gap-6">
<a class="text-[#705d00] font-bold border-b-2 border-[#705d00] font-label text-xs tracking-widest uppercase py-1" href="#">Profil</a>
<a class="text-[#4e342e]/70 dark:text-[#efeeeb]/70 hover:text-[#705d00] transition-colors duration-300 font-label text-xs tracking-widest uppercase py-1" href="#">Sejarah</a>
<a class="text-[#4e342e]/70 dark:text-[#efeeeb]/70 hover:text-[#705d00] transition-colors duration-300 font-label text-xs tracking-widest uppercase py-1" href="#">Galeri</a>
<a class="text-[#4e342e]/70 dark:text-[#efeeeb]/70 hover:text-[#705d00] transition-colors duration-300 font-label text-xs tracking-widest uppercase py-1" href="#">Katalog Jasa</a>
</div>
</div>
<div class="flex items-center gap-3">
@auth
    <a href="{{ url('/dashboard') }}"
       class="bg-gradient-to-r from-[#4E342E] to-[#361F1A] text-white px-5 py-2 rounded-md font-body font-semibold text-xs hover:opacity-90 transition-all tracking-widest uppercase">
        Dashboard
    </a>
@else
    <a href="{{ route('login') }}"
       class="font-label text-xs tracking-widest uppercase text-[#4e342e]/70 hover:text-[#705d00] transition-colors duration-300 py-1 px-3">
        Masuk
    </a>
    <a href="{{ route('register') }}"
       class="bg-gradient-to-r from-[#4E342E] to-[#361F1A] text-white px-5 py-2 rounded-md font-body font-semibold text-xs hover:opacity-90 transition-all tracking-widest uppercase">
        Daftar
    </a>
@endauth
</div>
</nav>
<main class="pt-16">
<!-- Hero Section -->
<section class="relative h-[921px] min-h-[600px] flex items-center overflow-hidden px-6 lg:px-20">
<div class="absolute inset-0 z-0">
<img class="w-full h-full object-cover brightness-[0.7]" data-alt="Cinematic wide shot of Indonesian traditional dancers performing in a historic courtyard with warm golden hour lighting and dramatic shadows" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAKLJeZoxR8JGephSpt-CQ1qwaU9sXDbR_yFfvj_6hYNaUcw7tKNLK_SAqoX2jJhGKyMlYSaEUkIp8pc_tG0KZQn9D5MmfA9zZkQCHoMdVt4ahPKs3UZaGMJvQEjafV20nGf0iOcRwVhK7QGQMG1tUBHMRw5R259gVLoNw4PzeXIZGKOdZRfDoXvuMp7MNBEnN7OzKBKEXaNgiwCU66Ev2gnfn5xy3labIA8gxkvL1aXUIsbTv8QxMbyP8ql3EtI6Boi3-jMKHG5Q"/>
<div class="absolute inset-0 bg-gradient-to-r from-primary/80 to-transparent"></div>
</div>
<div class="relative z-10 max-w-4xl">
<span class="inline-block px-4 py-1 mb-6 border border-secondary-fixed-dim/30 rounded-full text-secondary-fixed-dim text-xs font-label tracking-[0.2em] uppercase">Edisi Warisan Budaya</span>
<h1 class="font-headline text-5xl md:text-7xl text-white font-semibold leading-tight mb-6">Cahaya Gumilang: Melestarikan Warisan Melalui Seni.</h1>
<p class="text-xl text-white/80 font-body mb-10 max-w-2xl leading-relaxed">Experience the timeless beauty of Indonesian traditional arts, curated through generations of excellence and passion.</p>
<div class="flex flex-wrap gap-4">
<a href="{{ route('register') }}"
   class="bg-gradient-to-r from-[#4E342E] to-[#361F1A] text-white px-8 py-4 rounded-md font-body font-semibold text-sm hover:opacity-90 transition-all shadow-xl">
    Booking Sekarang
</a>
<a href="#katalog"
   class="bg-white/10 backdrop-blur-md border border-white/20 text-white px-8 py-4 rounded-md font-body font-semibold text-sm hover:bg-white/20 transition-all">
    Jelajahi Galeri
</a>
</div>
</div>
</section>
<!-- Sejarah Warisan (Elegant Typography) -->
<section class="py-24 px-6 lg:px-20 bg-surface">
<div class="grid lg:grid-cols-12 gap-12 items-center">
<div class="lg:col-span-5 relative">
<div class="aspect-[4/5] bg-surface-container-high rounded-xl overflow-hidden shadow-2xl">
<img class="w-full h-full object-cover" data-alt="Extreme close-up of a hand-carved mahogany Gamelan instrument with intricate gold leaf details reflecting soft studio light" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAJPyCghH9erUQyj5Tq1PBTLJgrhSmN9WN-wh3FXJg7ev9AymEqBoW7J8iN0TzrhftCDPbg2J6o777zegiYDiTYfWwNpMsiEQifdL5xyu9jkYMI9DWO94KNlI40SiZl4UUpVMQ5sDl-_dnSERRSRykhT7vre3LCJQ3R1b325CRW-N4JXX3lwJayyNfm10MArim6Mol6b4zHyt2S09dGWLeGkOZaLQ_SRgWAlzuq6k8ZnGOOgM7t9dJFlaPOc9wdkcwkOxklauMVjQ"/>
</div>
<div class="absolute -bottom-10 -right-10 hidden md:block w-64 h-64 bg-surface-container-lowest p-6 rounded-xl shadow-lg">
<img class="w-full h-full object-cover rounded-lg mb-4" data-alt="Detailed macro shot of traditional Javanese wood carving patterns on a dark wood background with shallow depth of field" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCTox9iuu0JGUWEz25Dkf9WYWLSkLAg8M6xNFbQ_AQNm-4EXgE0ftmo3pQf4Cu06MW9cMdoQfhH8IXdkTp5LP63EQXmToQfda0fby8LTQGdL4GfDVOc0nxdf7Lccsw1yh3d6TJQiO5VWxHzfwCHDwxUUvsF-8JYMZ1y2aW7oTJsj5s0QYWESyxNIHByLiUVfRFtyn7hacrwWXWaI1JVnevhJKhWSl_oj69iRJxUn3dX26PNv2RqiDSZi7Si_2qT_IaCrOWTFqQymw"/>
<p class="text-[10px] font-label uppercase tracking-widest text-secondary font-bold">Estetika Klasik</p>
</div>
</div>
<div class="lg:col-span-6 lg:offset-1">
<span class="text-secondary font-label text-sm uppercase tracking-widest font-bold mb-4 block">Pendiri &amp; Jiwa Sanggar</span>
<h2 class="font-headline text-4xl md:text-5xl text-primary font-semibold mb-8 leading-tight italic">Bapa A. Kusmana,<br>Cahaya yang Tak Pernah Padam.</h2>

{{-- Quote penghormatan --}}
<div class="mb-8 pl-5 border-l-2 border-secondary">
    <p class="font-headline text-xl text-primary italic leading-relaxed mb-3">
        "Seni bukan sekadar hiburan — ia adalah napas peradaban, warisan yang wajib kita jaga dan wariskan kepada generasi mendatang."
    </p>
    <p class="font-label text-xs uppercase tracking-widest text-secondary font-bold">— A. Kusmana, Pendiri Sanggar Cahaya Gumilang</p>
</div>

<div class="space-y-5 text-on-surface-variant font-body leading-loose">
    <p>Atas visi dan dedikasi Bapa A. Kusmana, Sanggar Cahaya Gumilang lahir sebagai ruang kebudayaan yang tidak hanya melatih, tetapi juga membentuk karakter dan jiwa para seniman muda Indonesia.</p>
    <p>Setiap gerakan tari dan dentuman gamelan yang kami hadirkan adalah bentuk penghormatan nyata atas warisan beliau — sebuah estafet budaya yang terus kami emban dengan sepenuh hati.</p>
</div>

<div class="mt-10 flex gap-12">
    <div>
        <p class="text-3xl font-headline font-bold text-primary">25+</p>
        <p class="text-xs font-label uppercase tracking-tighter text-on-surface-variant">Tahun Pengabdian</p>
    </div>
    <div>
        <p class="text-3xl font-headline font-bold text-primary">150+</p>
        <p class="text-xs font-label uppercase tracking-tighter text-on-surface-variant">Koleksi Alat Musik</p>
    </div>
    <div>
        <p class="text-3xl font-headline font-bold text-primary">500+</p>
        <p class="text-xs font-label uppercase tracking-tighter text-on-surface-variant">Seniman Dididik</p>
    </div>
</div>
</div>
</div>
</section>
<!-- Katalog Jasa (Package Cards) -->
<section class="py-24 bg-surface-container-low px-6 lg:px-20 overflow-hidden">
<div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
<div>
<span class="text-secondary font-label text-sm uppercase tracking-widest font-bold mb-2 block">Pilih Pengalaman Anda</span>
<h2 class="font-headline text-4xl text-primary font-semibold">Katalog Jasa</h2>
</div>
<button class="group flex items-center gap-3 text-primary font-label text-sm tracking-widest uppercase">
                    Lihat Semua Katalog
                    <span class="w-10 h-[1px] bg-secondary-fixed-dim transition-all group-hover:w-16"></span>
</button>
</div>
<div class="grid md:grid-cols-3 gap-8">
<!-- Package 1: Tari -->
<div class="group bg-surface-container-lowest rounded-xl overflow-hidden transition-all hover:shadow-[0_20px_40px_rgba(78,52,46,0.06)] flex flex-col">
<div class="relative h-64 overflow-hidden">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" data-alt="Graceful dancer in vibrant traditional costume with gold headdress posing in a studio with atmospheric smoke and warm backlighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAF41nW0SEyv9O4aqM0b3R4kEgaL2Jyu2bCR7cp0LMScBbObqoS-YBORfi-yqZWisee8QnW7u2fLJcf_nql0YcGBTOeo0v6_NxugDIUkZys71Wt91M-oTt4dZAFDnEDCUQwxMJY5NlXBK9wskjMlMAadK07XEuZbXrDRlgTRXjp-SC4S5hoXgyu2mMDCmxYU7zKUMDLbhw1jXQv558pROaDVWXntuMcc-5WadhGwOUrqByv3bbfeWwR91tL-9EiAgbdPRjbHRyGgA"/>
<div class="absolute top-4 right-4 bg-secondary-container text-on-secondary-container px-3 py-1 rounded text-[10px] font-bold uppercase tracking-widest">Favorit</div>
</div>
<div class="p-8 flex flex-col flex-grow">
<h3 class="font-headline text-2xl text-primary mb-4">Pertunjukan Tari</h3>
<p class="text-on-surface-variant text-sm leading-relaxed mb-8">Pilihan tari tradisional mulai dari Keraton hingga tari rakyat yang enerjik untuk acara formal maupun perayaan.</p>
<div class="mt-auto pt-6 border-t border-surface-container-high flex justify-between items-center">
<div>
<p class="text-[10px] font-label uppercase tracking-widest text-on-surface-variant mb-1">Mulai Dari</p>
<p class="text-xl font-headline font-bold text-primary">Rp 2.500.000</p>
</div>
<button class="material-symbols-outlined bg-surface-container p-3 rounded-full hover:bg-primary hover:text-white transition-colors">arrow_forward</button>
</div>
</div>
</div>
<!-- Package 2: Musik -->
<div class="group bg-surface-container-lowest rounded-xl overflow-hidden transition-all hover:shadow-[0_20px_40px_rgba(78,52,46,0.06)] flex flex-col">
<div class="relative h-64 overflow-hidden">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" data-alt="Full Gamelan orchestra set in a pavilion with polished bronze instruments glowing under soft indoor lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD71OTocZUH9eFF4ePBkSLAaXrIEk7ylHj7oAMoze4pDrNZpsP7nyvYN8df3Of-q1uluCVCbjwiifRAPSFGRnx5YNG6W09EJXht0-0GagEFGMra7k21x1GBGNXZjyeUogInhx1hBQk4O8l2k9pnPscBDZIwcI9cE5lTY-jxbtPYUBFOCBWWHcTXoNpO3ya3mBF0npiG7PSbleLRQ-yk4ySxB35mE8lQTRrjRR0iPAIjghuxy4CT9JyYOyjdynCSiZ_4o5h-l6hqfw"/>
</div>
<div class="p-8 flex flex-col flex-grow">
<h3 class="font-headline text-2xl text-primary mb-4">Ensembel Musik</h3>
<p class="text-on-surface-variant text-sm leading-relaxed mb-8">Harmoni magis Gamelan, Angklung, atau Kecapi Suling untuk menciptakan suasana yang tenang dan bermartabat.</p>
<div class="mt-auto pt-6 border-t border-surface-container-high flex justify-between items-center">
<div>
<p class="text-[10px] font-label uppercase tracking-widest text-on-surface-variant mb-1">Mulai Dari</p>
<p class="text-xl font-headline font-bold text-primary">Rp 3.800.000</p>
</div>
<button class="material-symbols-outlined bg-surface-container p-3 rounded-full hover:bg-primary hover:text-white transition-colors">arrow_forward</button>
</div>
</div>
</div>
<!-- Package 3: Gabungan -->
<div class="group bg-surface-container-lowest rounded-xl overflow-hidden transition-all hover:shadow-[0_20px_40px_rgba(78,52,46,0.06)] flex flex-col">
<div class="relative h-64 overflow-hidden">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" data-alt="Grand stage performance featuring both dancers and full orchestra musicians in synchronized artistic expression" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDpA4-8mFYLi5Gl8SNfgM7ZJXVjd1ynL8_4TNfr5wmL661kKn1PajBCbF8MwPtytwLy_mQhev_0E-qXE_QcQQj__hu9Dc_2A0PEjKLab4MdT_L2ZsRcChi9a4nSRHBatx2lqQ8BhqpGT63EyEpMAib1ZiMsbaN9Gsb_b7DmuAZ54D6id8N483Qp26ckl1bwTIDixfqTENNyhaqE83DABN0jyIgy-3R7oOSDWeWjfz1wewKAaZ6-nv68RlDqEahSOikzkn-LFYxdQw"/>
</div>
<div class="p-8 flex flex-col flex-grow">
<h3 class="font-headline text-2xl text-primary mb-4">Paket Gabungan</h3>
<p class="text-on-surface-variant text-sm leading-relaxed mb-8">Kolaborasi megah musik dan tari dalam satu konsep pertunjukan teatrikal yang tak terlupakan.</p>
<div class="mt-auto pt-6 border-t border-surface-container-high flex justify-between items-center">
<div>
<p class="text-[10px] font-label uppercase tracking-widest text-on-surface-variant mb-1">Mulai Dari</p>
<p class="text-xl font-headline font-bold text-primary">Rp 5.500.000</p>
</div>
<button class="material-symbols-outlined bg-surface-container p-3 rounded-full hover:bg-primary hover:text-white transition-colors">arrow_forward</button>
</div>
</div>
</div>
</div>
</section><section class="py-24 bg-surface px-6 lg:px-20">
<div class="mb-16 text-center">
<span class="text-secondary font-label text-sm uppercase tracking-widest font-bold mb-2 block">Mengenal Talenta Kami</span>
<h2 class="font-headline text-4xl text-primary font-semibold italic">Para Seniman Cahaya Gumilang</h2>
</div>
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
<!-- Artist 1 -->
<div class="group text-center">
<div class="aspect-square mb-4 overflow-hidden rounded-xl bg-surface-container">
<img alt="Artist 1" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAF41nW0SEyv9O4aqM0b3R4kEgaL2Jyu2bCR7cp0LMScBbObqoS-YBORfi-yqZWisee8QnW7u2fLJcf_nql0YcGBTOeo0v6_NxugDIUkZys71Wt91M-oTt4dZAFDnEDCUQwxMJY5NlXBK9wskjMlMAadK07XEuZbXrDRlgTRXjp-SC4S5hoXgyu2mMDCmxYU7zKUMDLbhw1jXQv558pROaDVWXntuMcc-5WadhGwOUrqByv3bbfeWwR91tL-9EiAgbdPRjbHRyGgA"/>
</div>
<h3 class="font-headline text-lg text-primary font-bold">Ayu Larasati</h3>
<p class="text-xs font-label uppercase tracking-widest text-on-surface-variant">Lead Dancer</p>
</div>
<!-- Artist 2 -->
<div class="group text-center">
<div class="aspect-square mb-4 overflow-hidden rounded-xl bg-surface-container">
<img alt="Artist 2" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD71OTocZUH9eFF4ePBkSLAaXrIEk7ylHj7oAMoze4pDrNZpsP7nyvYN8df3Of-q1uluCVCbjwiifRAPSFGRnx5YNG6W09EJXht0-0GagEFGMra7k21x1GBGNXZjyeUogInhx1hBQk4O8l2k9pnPscBDZIwcI9cE5lTY-jxbtPYUBFOCBWWHcTXoNpO3ya3mBF0npiG7PSbleLRQ-yk4ySxB35mE8lQTRrjRR0iPAIjghuxy4CT9JyYOyjdynCSiZ_4o5h-l6hqfw"/>
</div>
<h3 class="font-headline text-lg text-primary font-bold">Budi Santoso</h3>
<p class="text-xs font-label uppercase tracking-widest text-on-surface-variant">Gamelan Lead</p>
</div>
<!-- Artist 3-12 (Repetitive structure for 12 members) -->
<!-- Artist 3 -->
<div class="group text-center">
<div class="aspect-square mb-4 overflow-hidden rounded-xl bg-surface-container">
<img alt="Artist 3" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAJPyCghH9erUQyj5Tq1PBTLJgrhSmN9WN-wh3FXJg7ev9AymEqBoW7J8iN0TzrhftCDPbg2J6o777zegiYDiTYfWwNpMsiEQifdL5xyu9jkYMI9DWO94KNlI40SiZl4UUpVMQ5sDl-_dnSERRSRykhT7vre3LCJQ3R1b325CRW-N4JXX3lwJayyNfm10MArim6Mol6b4zHyt2S09dGWLeGkOZaLQ_SRgWAlzuq6k8ZnGOOgM7t9dJFlaPOc9wdkcwkOxklauMVjQ"/>
</div>
<h3 class="font-headline text-lg text-primary font-bold">Dewi Sinta</h3>
<p class="text-xs font-label uppercase tracking-widest text-on-surface-variant">Traditional Dancer</p>
</div>
<div class="group text-center">
<div class="aspect-square mb-4 overflow-hidden rounded-xl bg-surface-container">
<img alt="Artist 4" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCTox9iuu0JGUWEz25Dkf9WYWLSkLAg8M6xNFbQ_AQNm-4EXgE0ftmo3pQf4Cu06MW9cMdoQfhH8IXdkTp5LP63EQXmToQfda0fby8LTQGdL4GfDVOc0nxdf7Lccsw1yh3d6TJQiO5VWxHzfwCHDwxUUvsF-8JYMZ1y2aW7oTJsj5s0QYWESyxNIHByLiUVfRFtyn7hacrwWXWaI1JVnevhJKhWSl_oj69iRJxUn3dX26PNv2RqiDSZi7Si_2qT_IaCrOWTFqQymw"/>
</div>
<h3 class="font-headline text-lg text-primary font-bold">Wayan Putra</h3>
<p class="text-xs font-label uppercase tracking-widest text-on-surface-variant">Kendang Specialist</p>
</div>
<!-- Adding remaining slots to fulfill 12 total -->
<div class="group text-center">
<div class="aspect-square mb-4 overflow-hidden rounded-xl bg-surface-container">
<img alt="Artist 5" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAKLJeZoxR8JGephSpt-CQ1qwaU9sXDbR_yFfvj_6hYNaUcw7tKNLK_SAqoX2jJhGKyMlYSaEUkIp8pc_tG0KZQn9D5MmfA9zZkQCHoMdVt4ahPKs3UZaGMJvQEjafV20nGf0iOcRwVhK7QGQMG1tUBHMRw5R259gVLoNw4PzeXIZGKOdZRfDoXvuMp7MNBEnN7OzKBKEXaNgiwCU66Ev2gnfn5xy3labIA8gxkvL1aXUIsbTv8QxMbyP8ql3EtI6Boi3-jMKHG5Q"/>
</div>
<h3 class="font-headline text-lg text-primary font-bold">Siti Aminah</h3>
<p class="text-xs font-label uppercase tracking-widest text-on-surface-variant">Sinden</p>
</div>
<div class="group text-center">
<div class="aspect-square mb-4 overflow-hidden rounded-xl bg-surface-container">
<img alt="Artist 6" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDpA4-8mFYLi5Gl8SNfgM7ZJXVjd1ynL8_4TNfr5wmL661kKn1PajBCbF8MwPtytwLy_mQhev_0E-qXE_QcQQj__hu9Dc_2A0PEjKLab4MdT_L2ZsRcChi9a4nSRHBatx2lqQ8BhqpGT63EyEpMAib1ZiMsbaN9Gsb_b7DmuAZ54D6id8N483Qp26ckl1bwTIDixfqTENNyhaqE83DABN0jyIgy-3R7oOSDWeWjfz1wewKAaZ6-nv68RlDqEahSOikzkn-LFYxdQw"/>
</div>
<h3 class="font-headline text-lg text-primary font-bold">Eko Prasetyo</h3>
<p class="text-xs font-label uppercase tracking-widest text-on-surface-variant">Flutist (Suling)</p>
</div>
<div class="group text-center">
<div class="aspect-square mb-4 overflow-hidden rounded-xl bg-surface-container">
<img alt="Artist 7" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAF41nW0SEyv9O4aqM0b3R4kEgaL2Jyu2bCR7cp0LMScBbObqoS-YBORfi-yqZWisee8QnW7u2fLJcf_nql0YcGBTOeo0v6_NxugDIUkZys71Wt91M-oTt4dZAFDnEDCUQwxMJY5NlXBK9wskjMlMAadK07XEuZbXrDRlgTRXjp-SC4S5hoXgyu2mMDCmxYU7zKUMDLbhw1jXQv558pROaDVWXntuMcc-5WadhGwOUrqByv3bbfeWwR91tL-9EiAgbdPRjbHRyGgA"/>
</div>
<h3 class="font-headline text-lg text-primary font-bold">Ratna Sari</h3>
<p class="text-xs font-label uppercase tracking-widest text-on-surface-variant">Mask Dancer</p>
</div>
<div class="group text-center">
<div class="aspect-square mb-4 overflow-hidden rounded-xl bg-surface-container">
<img alt="Artist 8" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD71OTocZUH9eFF4ePBkSLAaXrIEk7ylHj7oAMoze4pDrNZpsP7nyvYN8df3Of-q1uluCVCbjwiifRAPSFGRnx5YNG6W09EJXht0-0GagEFGMra7k21x1GBGNXZjyeUogInhx1hBQk4O8l2k9pnPscBDZIwcI9cE5lTY-jxbtPYUBFOCBWWHcTXoNpO3ya3mBF0npiG7PSbleLRQ-yk4ySxB35mE8lQTRrjRR0iPAIjghuxy4CT9JyYOyjdynCSiZ_4o5h-l6hqfw"/>
</div>
<h3 class="font-headline text-lg text-primary font-bold">Agung Wijaya</h3>
<p class="text-xs font-label uppercase tracking-widest text-on-surface-variant">Rebab Player</p>
</div>
<div class="group text-center">
<div class="aspect-square mb-4 overflow-hidden rounded-xl bg-surface-container">
<img alt="Artist 9" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAJPyCghH9erUQyj5Tq1PBTLJgrhSmN9WN-wh3FXJg7ev9AymEqBoW7J8iN0TzrhftCDPbg2J6o777zegiYDiTYfWwNpMsiEQifdL5xyu9jkYMI9DWO94KNlI40SiZl4UUpVMQ5sDl-_dnSERRSRykhT7vre3LCJQ3R1b325CRW-N4JXX3lwJayyNfm10MArim6Mol6b4zHyt2S09dGWLeGkOZaLQ_SRgWAlzuq6k8ZnGOOgM7t9dJFlaPOc9wdkcwkOxklauMVjQ"/>
</div>
<h3 class="font-headline text-lg text-primary font-bold">Lestari Wahyu</h3>
<p class="text-xs font-label uppercase tracking-widest text-on-surface-variant">Court Dancer</p>
</div>
<div class="group text-center">
<div class="aspect-square mb-4 overflow-hidden rounded-xl bg-surface-container">
<img alt="Artist 10" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCTox9iuu0JGUWEz25Dkf9WYWLSkLAg8M6xNFbQ_AQNm-4EXgE0ftmo3pQf4Cu06MW9cMdoQfhH8IXdkTp5LP63EQXmToQfda0fby8LTQGdL4GfDVOc0nxdf7Lccsw1yh3d6TJQiO5VWxHzfwCHDwxUUvsF-8JYMZ1y2aW7oTJsj5s0QYWESyxNIHByLiUVfRFtyn7hacrwWXWaI1JVnevhJKhWSl_oj69iRJxUn3dX26PNv2RqiDSZi7Si_2qT_IaCrOWTFqQymw"/>
</div>
<h3 class="font-headline text-lg text-primary font-bold">Dian Sastro</h3>
<p class="text-xs font-label uppercase tracking-widest text-on-surface-variant">Gong Specialist</p>
</div>
<div class="group text-center">
<div class="aspect-square mb-4 overflow-hidden rounded-xl bg-surface-container">
<img alt="Artist 11" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAKLJeZoxR8JGephSpt-CQ1qwaU9sXDbR_yFfvj_6hYNaUcw7tKNLK_SAqoX2jJhGKyMlYSaEUkIp8pc_tG0KZQn9D5MmfA9zZkQCHoMdVt4ahPKs3UZaGMJvQEjafV20nGf0iOcRwVhK7QGQMG1tUBHMRw5R259gVLoNw4PzeXIZGKOdZRfDoXvuMp7MNBEnN7OzKBKEXaNgiwCU66Ev2gnfn5xy3labIA8gxkvL1aXUIsbTv8QxMbyP8ql3EtI6Boi3-jMKHG5Q"/>
</div>
<h3 class="font-headline text-lg text-primary font-bold">Rizky Febian</h3>
<p class="text-xs font-label uppercase tracking-widest text-on-surface-variant">Gambus Player</p>
</div>
<div class="group text-center">
<div class="aspect-square mb-4 overflow-hidden rounded-xl bg-surface-container">
<img alt="Artist 12" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDpA4-8mFYLi5Gl8SNfgM7ZJXVjd1ynL8_4TNfr5wmL661kKn1PajBCbF8MwPtytwLy_mQhev_0E-qXE_QcQQj__hu9Dc_2A0PEjKLab4MdT_L2ZsRcChi9a4nSRHBatx2lqQ8BhqpGT63EyEpMAib1ZiMsbaN9Gsb_b7DmuAZ54D6id8N483Qp26ckl1bwTIDixfqTENNyhaqE83DABN0jyIgy-3R7oOSDWeWjfz1wewKAaZ6-nv68RlDqEahSOikzkn-LFYxdQw"/>
</div>
<h3 class="font-headline text-lg text-primary font-bold">Maya Hasan</h3>
<p class="text-xs font-label uppercase tracking-widest text-on-surface-variant">Contemporary Harpist</p>
</div>
</div>
</section>
<!-- CTA Section -->
<section class="py-24 bg-surface-container-low px-6 lg:px-20">
<div class="max-w-4xl mx-auto">
<div class="text-center mb-16">
<span class="text-secondary font-label text-sm uppercase tracking-widest font-bold mb-2 block">Suara Klien Kami</span>
<h2 class="font-headline text-4xl text-primary font-semibold">Kesaksian Budaya</h2>
</div>
<div class="grid md:grid-cols-2 gap-8">
<!-- Testimonial 1 -->
<div class="bg-white p-8 rounded-xl shadow-sm border border-surface-container-high">
<div class="flex text-secondary-container mb-4">
<span class="material-symbols-outlined fill-1">star</span>
<span class="material-symbols-outlined fill-1">star</span>
<span class="material-symbols-outlined fill-1">star</span>
<span class="material-symbols-outlined fill-1">star</span>
<span class="material-symbols-outlined fill-1">star</span>
</div>
<p class="font-headline text-lg text-primary mb-6 italic">"Pertunjukan yang benar-benar memukau. Cahaya Gumilang berhasil menghadirkan jiwa Nusantara ke dalam acara pernikahan kami dengan sangat elegan."</p>
<div>
<p class="font-bold text-sm text-primary">Anindya Putri</p>
<p class="text-xs text-on-surface-variant uppercase tracking-widest">Event Organizer</p>
</div>
</div>
<!-- Testimonial 2 -->
<div class="bg-white p-8 rounded-xl shadow-sm border border-surface-container-high">
<div class="flex text-secondary-container mb-4">
<span class="material-symbols-outlined fill-1">star</span>
<span class="material-symbols-outlined fill-1">star</span>
<span class="material-symbols-outlined fill-1">star</span>
<span class="material-symbols-outlined fill-1">star</span>
<span class="material-symbols-outlined">star</span>
</div>
<p class="font-headline text-lg text-primary mb-6 italic">"Kualitas musik gamelannya sangat otentik. Para musisi menunjukkan disiplin dan dedikasi yang luar biasa pada seni tradisi."</p>
<div>
<p class="font-bold text-sm text-primary">Robert Wagner</p>
<p class="text-xs text-on-surface-variant uppercase tracking-widest">Cultural Collector</p>
</div>
</div>
</div>
</div>
</section><section class="py-24 bg-primary text-white text-center relative overflow-hidden">
<div class="absolute inset-0 opacity-10 pointer-events-none">
<div class="grid grid-cols-6 h-full">
<div class="border-r border-white/20"></div>
<div class="border-r border-white/20"></div>
<div class="border-r border-white/20"></div>
<div class="border-r border-white/20"></div>
<div class="border-r border-white/20"></div>
</div>
</div>
<div class="relative z-10 max-w-2xl mx-auto px-6">
<h2 class="font-headline text-4xl mb-6">Mulai Perjalanan Budaya Anda</h2>
<p class="text-white/70 mb-10 font-body">Jadilah bagian dari penjaga warisan nusantara. Daftarkan acara Anda atau hubungi kami untuk konsultasi konsep seni yang unik.</p>
<div class="flex flex-col sm:flex-row gap-4 justify-center">
<a href="{{ route('register') }}"
   class="bg-secondary-container text-on-secondary-container px-10 py-4 rounded-md font-bold text-sm hover:opacity-90 transition-all uppercase tracking-widest text-center">
    Registrasi Akun
</a>
<a href="mailto:halo@cahayagumilang.id"
   class="bg-transparent border border-white/30 text-white px-10 py-4 rounded-md font-bold text-sm hover:bg-white/10 transition-all uppercase tracking-widest text-center">
    Hubungi Kami
</a>
</div>
</div>
</section>
</main>
<!-- Footer -->
<footer class="bg-surface py-20 px-6 lg:px-20">
<div class="grid md:grid-cols-4 gap-12 border-b border-surface-container-high pb-16">
<div class="col-span-2">
<h3 class="font-headline text-2xl text-primary mb-6">Cahaya Gumilang</h3>
<p class="text-on-surface-variant max-w-sm leading-relaxed text-sm">Pusat pelestarian dan pengembangan seni budaya tradisional Indonesia. Menghadirkan kualitas kurasi seni tingkat tinggi untuk dunia modern.</p>
</div>
<div>
<h4 class="font-label text-xs font-bold uppercase tracking-widest text-primary mb-6">Navigasi</h4>
<ul class="space-y-4 text-sm text-on-surface-variant">
<li><a class="hover:text-secondary transition-colors" href="#">Tentang Kami</a></li>
<li><a class="hover:text-secondary transition-colors" href="#">Katalog Layanan</a></li>
<li><a class="hover:text-secondary transition-colors" href="#">Galeri Dokumentasi</a></li>
<li><a class="hover:text-secondary transition-colors" href="#">Syarat &amp; Ketentuan</a></li>
</ul>
</div>
<div>
<h4 class="font-label text-xs font-bold uppercase tracking-widest text-primary mb-6">Hubungi Kami</h4>
<ul class="space-y-4 text-sm text-on-surface-variant">
<li class="flex items-center gap-3">
<span class="material-symbols-outlined text-sm">location_on</span>
                        Jakarta, Indonesia
                    </li>
<li class="flex items-center gap-3">
<span class="material-symbols-outlined text-sm">mail</span>
                        halo@cahayagumilang.id
                    </li>
<li class="flex gap-4 mt-6">
<button class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center hover:bg-secondary hover:text-white transition-all">
<span class="material-symbols-outlined text-sm">public</span>
</button>
<button class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center hover:bg-secondary hover:text-white transition-all">
<span class="material-symbols-outlined text-sm">share</span>
</button>
</li>
</ul>
</div>
</div>
<div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] font-label uppercase tracking-widest text-on-surface-variant/60">
<p>© 2024 Cahaya Gumilang. All Rights Reserved.</p>
<p>Designed with Heritage Modernist principles.</p>
</div>
</footer>
<!-- BottomNavBar (Mobile Only Shared Component) -->
<nav class="lg:hidden fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-4 pb-6 pt-3 bg-[#faf9f6]/90 dark:bg-[#1a1c1a]/90 backdrop-blur-xl shadow-[0_-10px_30px_rgba(78,52,46,0.08)] rounded-t-3xl">
<button class="flex flex-col items-center justify-center text-[#705d00] bg-[#fcd400]/10 rounded-2xl px-5 py-2">
<span class="material-symbols-outlined" data-icon="home">home</span>
<span class="font-bold text-[10px] tracking-wider font-label mt-1">Home</span>
</button>
<button class="flex flex-col items-center justify-center text-[#4e342e]/50 dark:text-[#efeeeb]/40">
<span class="material-symbols-outlined" data-icon="list_alt">list_alt</span>
<span class="font-bold text-[10px] tracking-wider font-label mt-1">Orders</span>
</button>
<button class="flex flex-col items-center justify-center text-[#4e342e]/50 dark:text-[#efeeeb]/40">
<span class="material-symbols-outlined" data-icon="event_note">event_note</span>
<span class="font-bold text-[10px] tracking-wider font-label mt-1">Schedule</span>
</button>
<button class="flex flex-col items-center justify-center text-[#4e342e]/50 dark:text-[#efeeeb]/40">
<span class="material-symbols-outlined" data-icon="account_balance_wallet">account_balance_wallet</span>
<span class="font-bold text-[10px] tracking-wider font-label mt-1">Finance</span>
</button>
<button class="flex flex-col items-center justify-center text-[#4e342e]/50 dark:text-[#efeeeb]/40">
<span class="material-symbols-outlined" data-icon="menu">menu</span>
<span class="font-bold text-[10px] tracking-wider font-label mt-1">Menu</span>
</button>
</nav>
</body></html>