<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk | ART-HUB Sanggar Cahaya Gumilang</title>

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Noto+Serif:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary":                  "#361f1a",
                        "primary-container":        "#4e342e",
                        "on-primary":               "#ffffff",
                        "primary-fixed-dim":        "#e5beb5",
                        "secondary":                "#705d00",
                        "secondary-container":      "#fcd400",
                        "secondary-fixed-dim":      "#e9c400",
                        "on-secondary-container":   "#6e5c00",
                        "surface":                  "#faf9f6",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low":    "#f4f3f1",
                        "surface-container":        "#efeeeb",
                        "surface-container-high":   "#e9e8e5",
                        "surface-container-highest":"#e3e2e0",
                        "on-surface":               "#1a1c1a",
                        "on-surface-variant":       "#504442",
                        "outline":                  "#827471",
                        "outline-variant":          "#d4c3bf",
                    },
                    fontFamily: {
                        "headline": ["Noto Serif", "serif"],
                        "display":  ["Noto Serif", "serif"],
                        "body":     ["Manrope", "sans-serif"],
                        "label":    ["Manrope", "sans-serif"],
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased selection:bg-secondary-container selection:text-on-secondary-container h-screen flex overflow-hidden">

    {{-- ══ LEFT PANEL: Heritage Image ══ --}}
    <div class="hidden lg:block lg:w-[45%] xl:w-1/2 relative bg-surface-container-low" data-aos="fade-right" data-aos-duration="1400">
        <img class="absolute inset-0 w-full h-full object-cover opacity-90 mix-blend-multiply"
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDITxC5SnLAR7YvQv5cYvrNUM7MIUDZw1KM9WhxSUbxC-gmlv-V5SSIbpOr2kOuTFKBizsg9GzPy34WzdpzoTkICW5xhFJE56kYO8eeYbMJP5-4TS8Vcr172Fio5L8YGWgUUmyDOAomnBfT1chCBUOdpSlrHCIGQNVH4zo0Hvq2RBE6TVyG37qguXlga-XD-n48X3BfGnUkNg-9l2WsuKowylYlk_ttpB78iZ9xptjW9UeNGAK4QkbWu_3nzBi5ef2AcOMYfR0YmA"
            alt="Gamelan instruments in warm cinematic light">
        {{-- Depth gradient --}}
        <div class="absolute inset-0 bg-gradient-to-tr from-primary/95 via-primary/60 to-transparent"></div>
        {{-- Welcome typography --}}
        <div class="absolute bottom-20 left-16 right-16 z-10 max-w-xl" data-aos="fade-up" data-aos-delay="400" data-aos-duration="1200">
            <span class="inline-block px-3 py-1 mb-6 rounded bg-surface-container-highest/20 backdrop-blur-md font-label text-xs uppercase tracking-widest text-on-primary border border-outline-variant/30">
                Sanggar Seni Cahaya Gumilang
            </span>
            <h2 class="font-display text-4xl xl:text-5xl text-on-primary leading-tight tracking-tight mb-4 drop-shadow-md">
                Preserving the rhythm of the past, orchestrating the future.
            </h2>
            <p class="font-body text-lg text-primary-fixed-dim/90 font-light">
                Secure access to the gallery's digital curation and management ecosystem.
            </p>
        </div>
    </div>

    {{-- ══ RIGHT PANEL: The Canvas Form ══ --}}
    <div class="w-full lg:w-[55%] xl:w-1/2 h-full bg-surface-container-lowest flex flex-col justify-center relative z-10 shadow-[-30px_0_60px_rgba(54,31,26,0.06)] overflow-y-auto"
         data-aos="fade-left" data-aos-duration="1000">
        <div class="w-full max-w-md mx-auto px-8 py-12">

            {{-- Brand Identity Header --}}
            <div class="mb-14" data-aos="fade-up" data-aos-delay="200">
                <h1 class="font-display text-5xl text-primary tracking-tight mb-3">Art-Hub</h1>
                <p class="font-label text-sm uppercase tracking-[0.2em] text-outline">The Digital Curator</p>
            </div>

            {{-- Validation Errors --}}
            @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 rounded-lg" data-aos="fade-up" data-aos-delay="250">
                @foreach ($errors->all() as $error)
                    <p class="text-red-700 text-sm font-body">• {{ $error }}</p>
                @endforeach
            </div>
            @endif

            {{-- Session Status --}}
            @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 rounded-lg" data-aos="fade-up">
                <p class="text-green-700 text-sm font-body">{{ session('status') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Input Fields --}}
                <div class="space-y-6 mb-8">

                    {{-- Email --}}
                    <div class="relative group" data-aos="fade-up" data-aos-delay="350">
                        <input class="peer w-full bg-surface-container-low text-on-surface font-body px-5 py-4 pt-6 rounded-lg border-none focus:ring-0 focus:bg-surface-container-lowest focus:shadow-[0_8px_24px_rgba(78,52,46,0.04)] transition-all placeholder-transparent"
                            id="email" name="email" placeholder="Email Address" type="email"
                            value="{{ old('email') }}" required autofocus>
                        <label class="absolute left-5 top-4 font-label text-xs uppercase tracking-widest text-outline transition-all
                            peer-placeholder-shown:text-sm peer-placeholder-shown:top-5 peer-placeholder-shown:normal-case peer-placeholder-shown:tracking-normal
                            peer-focus:text-xs peer-focus:uppercase peer-focus:tracking-widest peer-focus:top-2 peer-focus:text-secondary"
                            for="email">Email Address</label>
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-[2px] bg-secondary transition-all duration-300 peer-focus:w-full rounded-full"></span>
                    </div>

                    {{-- Password --}}
                    <div class="relative group" data-aos="fade-up" data-aos-delay="450">
                        <input class="peer w-full bg-surface-container-low text-on-surface font-body px-5 py-4 pt-6 rounded-lg border-none focus:ring-0 focus:bg-surface-container-lowest focus:shadow-[0_8px_24px_rgba(78,52,46,0.04)] transition-all placeholder-transparent pr-14"
                            id="password" name="password" placeholder="Password" type="password" required>
                        <label class="absolute left-5 top-4 font-label text-xs uppercase tracking-widest text-outline transition-all
                            peer-placeholder-shown:text-sm peer-placeholder-shown:top-5 peer-placeholder-shown:normal-case peer-placeholder-shown:tracking-normal
                            peer-focus:text-xs peer-focus:uppercase peer-focus:tracking-widest peer-focus:top-2 peer-focus:text-secondary"
                            for="password">Password</label>
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-[2px] bg-secondary transition-all duration-300 peer-focus:w-full rounded-full"></span>
                        <button type="button" onclick="togglePassword(this)"
                            class="absolute right-5 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-xl">visibility_off</span>
                        </button>
                    </div>
                </div>

                {{-- Utilities & Remember --}}
                <div class="flex items-center justify-between mb-10" data-aos="fade-up" data-aos-delay="520">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="w-5 h-5 rounded border border-outline-variant/50 bg-surface-container-lowest group-hover:border-primary flex items-center justify-center transition-colors relative">
                            <input type="checkbox" name="remember" id="remember" class="absolute opacity-0 w-full h-full cursor-pointer">
                            <span class="material-symbols-outlined text-sm text-primary opacity-0 peer-checked:opacity-100 transition-opacity" style="font-variation-settings:'FILL' 1;">check</span>
                        </div>
                        <span class="font-body text-sm text-on-surface-variant group-hover:text-primary transition-colors">Retain Session</span>
                    </label>
                    @if (Route::has('password.request'))
                    <a class="font-label text-xs uppercase tracking-widest text-primary font-semibold hover:text-secondary transition-colors underline decoration-secondary-fixed-dim decoration-2 underline-offset-4"
                        href="{{ route('password.request') }}">Recover Access</a>
                    @endif
                </div>

                {{-- Primary CTA --}}
                <button type="submit" data-aos="fade-up" data-aos-delay="600"
                    class="w-full relative overflow-hidden bg-gradient-to-br from-primary-container to-primary text-on-primary py-4 rounded-lg font-body text-base font-semibold shadow-[0_12px_24px_rgba(54,31,26,0.15)] hover:shadow-[0_16px_32px_rgba(54,31,26,0.2)] hover:-translate-y-0.5 transition-all group">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        Masuk
                        <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </span>
                    {{-- Hover flare --}}
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full duration-700 transition-transform"></div>
                </button>
            </form>

            {{-- Registration Footer --}}
            <div class="mt-16 pt-8 border-t border-outline-variant/20 text-center" data-aos="fade-up" data-aos-delay="700">
                <p class="font-body text-sm text-on-surface-variant">
                    Belum punya akun?
                    <a class="inline-block ml-2 font-label text-xs uppercase tracking-widest text-primary font-bold hover:text-secondary transition-colors"
                        href="{{ route('register') }}">Register Here</a>
                </p>
            </div>

        </div>
    </div>

</body>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 900, easing: 'ease-out-cubic', once: true, offset: 0 });

    function togglePassword(btn) {
        const input = btn.closest('.relative').querySelector('input');
        const icon  = btn.querySelector('span');
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility_off';
        }
    }
</script>
</html>