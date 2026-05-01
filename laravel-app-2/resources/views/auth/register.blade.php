<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun | ART-HUB Sanggar Cahaya Gumilang</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Noto+Serif:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
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
                        "secondary":                "#705d00",
                        "secondary-container":      "#fcd400",
                        "on-secondary-container":   "#6e5c00",
                        "secondary-fixed-dim":      "#e9c400",
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
                        "primary-fixed":            "#ffdad2",
                        "on-primary-fixed":         "#2b1611",
                    },
                    fontFamily: {
                        "headline": ["Noto Serif", "serif"],
                        "display":  ["Noto Serif", "serif"],
                        "body":     ["Manrope", "sans-serif"],
                        "label":    ["Manrope", "sans-serif"],
                    },
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        /* Floating label animation */
        .field-group input:not(:placeholder-shown) ~ label,
        .field-group input:focus ~ label {
            top: -1.2rem; font-size: 0.7rem; color: #705d00;
            text-transform: uppercase; letter-spacing: 0.05em;
        }
        /* Tab panels */
        #panel-personnel { display: block; }
        #panel-client    { display: none; }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased min-h-screen flex selection:bg-secondary-container selection:text-on-secondary-container">

<main class="w-full flex min-h-screen">

    {{-- ══ LEFT PANEL ══ --}}
    <section id="left-panel"
        class="hidden lg:flex lg:w-[45%] relative overflow-hidden bg-primary items-end pb-24 px-16"
        style="background-image:url('https://lh3.googleusercontent.com/aida/ADBb0ugxoJkoOrQB5qHrx3eygc-Cfj4PUh8ZrXcoAElbM9P6kxVgCI1QEzxyvXCaV7CEQKHEEVUPlztp97QrMuTXDeBS8-bBr4qtocWGi-aJRNl3dmg19LPWPCSU3dn7b_ZYwZD0Epaot3C1-HCYSYML-1aldfcEjaeQ1BB6-ohqxYZ5ZTWZiguPChO5fwv9ECOvS94Z_ZgudK0NxNZIbWoAFZtDxXU47DG3hNge5gX3ISZeVjByl9XeWVUf_g'); background-size:cover; background-position:center;"
        data-aos="fade-right" data-aos-duration="1200">
        <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/80 to-transparent"></div>
        <div class="relative z-10 w-full max-w-md" id="left-content-personnel">
            <div class="mb-6">
                <span class="font-display font-black text-white text-2xl tracking-tight">Sanggar Cahaya Gumilang</span>
            </div>
            <h1 class="font-headline text-5xl text-white leading-[1.1] tracking-[-0.02em] mb-5 drop-shadow-md">Join the<br>Tradition.</h1>
            <p class="font-body text-white/75 text-base leading-relaxed">Become a part of our curated ensemble. Elevate your craft within an institution dedicated to preserving and innovating Indonesian arts.</p>
        </div>
        <div class="relative z-10 w-full max-w-md hidden" id="left-content-client">
            <div class="mb-6">
                <span class="font-display font-black text-white text-2xl tracking-tight">Sanggar Cahaya Gumilang</span>
            </div>
            <h1 class="font-headline text-5xl text-white leading-[1.1] tracking-[-0.02em] mb-5 drop-shadow-md">Support<br>the Arts.</h1>
            <p class="font-body text-white/75 text-base leading-relaxed">Join our curated network of patrons and partners. Your engagement ensures the continuity of our cultural heritage and artistic excellence.</p>
        </div>
    </section>

    {{-- ══ RIGHT PANEL ══ --}}
    <section class="w-full lg:w-[55%] flex flex-col justify-center items-center py-14 px-6 sm:px-12 lg:px-20 bg-surface-container-lowest" data-aos="fade-left" data-aos-duration="1000">

        {{-- Mobile brand --}}
        <div class="lg:hidden w-full max-w-lg mb-10 text-center" data-aos="fade-down" data-aos-delay="100">
            <h2 class="font-headline text-3xl text-primary font-bold tracking-tight">Cahaya Gumilang</h2>
            <p class="font-body text-on-surface-variant mt-1 text-sm">Arts Management Portal</p>
        </div>

        <div class="w-full max-w-lg">

            {{-- Header --}}
            <div class="mb-10" data-aos="fade-up" data-aos-delay="200">
                <h2 class="font-headline text-4xl text-primary font-medium tracking-tight mb-1">Create Account</h2>
                <p class="font-body text-on-surface-variant text-sm" id="form-subtitle">Please complete the details below to register.</p>
            </div>

            {{-- Role Toggle --}}
            <div class="flex bg-surface-container-low p-1 rounded-full mb-10 border border-outline-variant/15" id="role-toggle" data-aos="fade-up" data-aos-delay="300">
                <button type="button" id="btn-personnel"
                    onclick="switchTab('personnel')"
                    class="flex-1 py-2.5 px-4 rounded-full bg-primary text-on-primary font-body text-sm font-semibold transition-all duration-200 shadow-[0_4px_12px_rgba(78,52,46,0.18)] flex justify-center items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">badge</span>Personnel
                </button>
                <button type="button" id="btn-client"
                    onclick="switchTab('client')"
                    class="flex-1 py-2.5 px-4 rounded-full text-on-surface-variant hover:text-primary font-body text-sm font-medium transition-all duration-200 flex justify-center items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">group</span>Client
                </button>
            </div>

            {{-- Validation Errors --}}
            @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                @foreach ($errors->all() as $error)
                    <p class="text-red-700 text-sm font-body">• {{ $error }}</p>
                @endforeach
            </div>
            @endif

            {{-- ═══ PERSONNEL FORM ═══ --}}
            <div id="panel-personnel">
                <form method="POST" action="{{ route('register') }}" class="space-y-7">
                    @csrf
                    <input type="hidden" name="role" value="personnel">

                    {{-- Full Name --}}
                    <div class="field-group relative" data-aos="fade-up" data-aos-delay="400">
                        <input type="text" name="name" id="p_name" placeholder=" " required value="{{ old('name') }}"
                            class="peer block w-full bg-surface-container-high border-0 border-b-2 border-outline-variant/40 py-3 px-4 text-on-surface focus:ring-0 focus:border-secondary focus:bg-surface-container-lowest rounded-t-lg transition-all duration-200">
                        <label for="p_name" class="absolute left-4 top-3 text-on-surface-variant font-label text-sm transition-all duration-200 pointer-events-none
                            peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm
                            peer-focus:-top-5 peer-focus:text-xs peer-focus:text-secondary peer-focus:uppercase peer-focus:tracking-widest">
                            Full Name
                        </label>
                    </div>

                    {{-- Phone + Occupation --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-7" data-aos="fade-up" data-aos-delay="450">
                        <div class="field-group relative">
                            <input type="tel" name="phone" id="p_phone" placeholder=" " value="{{ old('phone') }}" required
                                class="peer block w-full bg-surface-container-high border-0 border-b-2 border-outline-variant/40 py-3 px-4 text-on-surface focus:ring-0 focus:border-secondary focus:bg-surface-container-lowest rounded-t-lg transition-all duration-200">
                            <label for="p_phone" class="absolute left-4 top-3 text-on-surface-variant font-label text-sm transition-all duration-200 pointer-events-none
                                peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm
                                peer-focus:-top-5 peer-focus:text-xs peer-focus:text-secondary peer-focus:uppercase peer-focus:tracking-widest">
                                Phone Number
                            </label>
                        </div>
                        <div class="field-group relative">
                            <input type="text" name="primary_skill" id="p_skill" placeholder=" " value="{{ old('primary_skill') }}"
                                class="peer block w-full bg-surface-container-high border-0 border-b-2 border-outline-variant/40 py-3 px-4 text-on-surface focus:ring-0 focus:border-secondary focus:bg-surface-container-lowest rounded-t-lg transition-all duration-200">
                            <label for="p_skill" class="absolute left-4 top-3 text-on-surface-variant font-label text-sm transition-all duration-200 pointer-events-none
                                peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm
                                peer-focus:-top-5 peer-focus:text-xs peer-focus:text-secondary peer-focus:uppercase peer-focus:tracking-widest">
                                Primary Occupation
                            </label>
                        </div>
                    </div>

                    {{-- Specialties --}}
                    <div data-aos="fade-up" data-aos-delay="500">
                        <p class="font-headline text-base text-primary mb-4">Dance &amp; Music Specialties</p>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach([['jaipong','Tari Jaipong'],['topeng','Tari Topeng'],['gamelan','Gamelan'],['kendang','Kendang'],['sinden','Sinden'],['other','Other']] as [$val,$label])
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center justify-center flex-shrink-0">
                                    <input type="checkbox" name="specialties[]" value="{{ $val }}"
                                        class="peer appearance-none w-5 h-5 border-2 border-outline rounded bg-surface checked:bg-secondary checked:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all cursor-pointer">
                                    <span class="material-symbols-outlined absolute text-on-primary text-[14px] opacity-0 peer-checked:opacity-100 pointer-events-none" style="font-variation-settings:'FILL' 1;">check</span>
                                </div>
                                <span class="font-body text-sm text-on-surface group-hover:text-primary transition-colors">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="field-group relative" data-aos="fade-up" data-aos-delay="550">
                        <input type="email" name="email" id="p_email" placeholder=" " required value="{{ old('email') }}"
                            class="peer block w-full bg-surface-container-high border-0 border-b-2 border-outline-variant/40 py-3 px-4 text-on-surface focus:ring-0 focus:border-secondary focus:bg-surface-container-lowest rounded-t-lg transition-all duration-200">
                        <label for="p_email" class="absolute left-4 top-3 text-on-surface-variant font-label text-sm transition-all duration-200 pointer-events-none
                            peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm
                            peer-focus:-top-5 peer-focus:text-xs peer-focus:text-secondary peer-focus:uppercase peer-focus:tracking-widest">
                            Email Address
                        </label>
                    </div>

                    {{-- Password --}}
                    <div class="field-group relative" data-aos="fade-up" data-aos-delay="600">
                        <input type="password" name="password" id="p_password" placeholder=" " required
                            class="peer block w-full bg-surface-container-high border-0 border-b-2 border-outline-variant/40 py-3 px-4 text-on-surface focus:ring-0 focus:border-secondary focus:bg-surface-container-lowest rounded-t-lg transition-all duration-200">
                        <label for="p_password" class="absolute left-4 top-3 text-on-surface-variant font-label text-sm transition-all duration-200 pointer-events-none
                            peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm
                            peer-focus:-top-5 peer-focus:text-xs peer-focus:text-secondary peer-focus:uppercase peer-focus:tracking-widest">
                            Password
                        </label>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="field-group relative" data-aos="fade-up" data-aos-delay="650">
                        <input type="password" name="password_confirmation" id="p_confirm" placeholder=" " required
                            class="peer block w-full bg-surface-container-high border-0 border-b-2 border-outline-variant/40 py-3 px-4 text-on-surface focus:ring-0 focus:border-secondary focus:bg-surface-container-lowest rounded-t-lg transition-all duration-200">
                        <label for="p_confirm" class="absolute left-4 top-3 text-on-surface-variant font-label text-sm transition-all duration-200 pointer-events-none
                            peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm
                            peer-focus:-top-5 peer-focus:text-xs peer-focus:text-secondary peer-focus:uppercase peer-focus:tracking-widest">
                            Confirm Password
                        </label>
                    </div>

                    {{-- CTA --}}
                    <div class="pt-2 flex flex-col items-center gap-5" data-aos="fade-up" data-aos-delay="700">
                        <button type="submit"
                            class="w-full flex items-center justify-between gap-3 bg-gradient-to-r from-primary-container to-primary text-on-primary py-4 px-7 rounded-xl font-body font-semibold text-sm shadow-[0_8px_24px_rgba(54,31,26,0.2)] hover:shadow-[0_12px_30px_rgba(54,31,26,0.3)] hover:-translate-y-0.5 transition-all duration-300 group">
                            <span class="tracking-wide">Create Account</span>
                            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform duration-200">arrow_forward</span>
                        </button>
                        <p class="font-body text-sm text-on-surface-variant">
                            Already part of the ensemble?
                            <a href="{{ route('login') }}" class="text-primary font-semibold border-b-2 border-secondary-fixed-dim hover:border-primary transition-colors pb-0.5 ml-1">Log in here</a>
                        </p>
                    </div>
                </form>
            </div>

            {{-- ═══ CLIENT FORM ═══ --}}
            <div id="panel-client">
                <form method="POST" action="{{ route('register') }}" class="space-y-7">
                    @csrf
                    <input type="hidden" name="role" value="klien">

                    {{-- Full Name --}}
                    <div data-aos="fade-up" data-aos-delay="400">
                        <label class="block font-label text-xs uppercase tracking-[0.05em] text-on-surface-variant mb-2">Full Name</label>
                        <input type="text" name="name" required value="{{ old('name') }}" placeholder="Raden Saleh"
                            class="w-full bg-surface-container-high/60 border-0 border-b-2 border-outline-variant/40 text-on-surface font-body px-4 py-3 rounded-t-lg focus:ring-0 focus:border-secondary transition-all duration-200 placeholder:text-on-surface-variant/40">
                    </div>

                    {{-- Organization --}}
                    <div data-aos="fade-up" data-aos-delay="450">
                        <label class="font-label text-xs uppercase tracking-[0.05em] text-on-surface-variant mb-2 flex justify-between">
                            <span>Organization</span>
                            <span class="normal-case tracking-normal opacity-50">Optional</span>
                        </label>
                        <input type="text" name="organization" value="{{ old('organization') }}" placeholder="e.g. National Gallery"
                            class="w-full bg-surface-container-high/60 border-0 border-b-2 border-outline-variant/40 text-on-surface font-body px-4 py-3 rounded-t-lg focus:ring-0 focus:border-secondary transition-all duration-200 placeholder:text-on-surface-variant/40">
                    </div>

                    {{-- Phone + Email --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" data-aos="fade-up" data-aos-delay="500">
                        <div>
                            <label class="block font-label text-xs uppercase tracking-[0.05em] text-on-surface-variant mb-2">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+62 812 3456 7890" required
                                class="w-full bg-surface-container-high/60 border-0 border-b-2 border-outline-variant/40 text-on-surface font-body px-4 py-3 rounded-t-lg focus:ring-0 focus:border-secondary transition-all duration-200 placeholder:text-on-surface-variant/40">
                        </div>
                        <div>
                            <label class="block font-label text-xs uppercase tracking-[0.05em] text-on-surface-variant mb-2">Email Address</label>
                            <input type="email" name="email" required value="{{ old('email') }}" placeholder="name@domain.com"
                                class="w-full bg-surface-container-high/60 border-0 border-b-2 border-outline-variant/40 text-on-surface font-body px-4 py-3 rounded-t-lg focus:ring-0 focus:border-secondary transition-all duration-200 placeholder:text-on-surface-variant/40">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div data-aos="fade-up" data-aos-delay="550">
                        <label class="block font-label text-xs uppercase tracking-[0.05em] text-on-surface-variant mb-2">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="c_password" required placeholder="••••••••"
                                class="w-full bg-surface-container-high/60 border-0 border-b-2 border-outline-variant/40 text-on-surface font-body px-4 py-3 rounded-t-lg focus:ring-0 focus:border-secondary transition-all duration-200 pr-12">
                            <button type="button" onclick="togglePwd('c_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]">visibility_off</span>
                            </button>
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div data-aos="fade-up" data-aos-delay="600">
                        <label class="block font-label text-xs uppercase tracking-[0.05em] text-on-surface-variant mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••"
                            class="w-full bg-surface-container-high/60 border-0 border-b-2 border-outline-variant/40 text-on-surface font-body px-4 py-3 rounded-t-lg focus:ring-0 focus:border-secondary transition-all duration-200">
                    </div>

                    {{-- CTA --}}
                    <div class="pt-2 flex flex-col gap-5" data-aos="fade-up" data-aos-delay="650">
                        <button type="submit"
                            class="w-full bg-gradient-to-br from-primary-container to-primary text-on-primary font-body text-sm font-semibold rounded-xl py-4 px-6 flex justify-between items-center hover:opacity-90 hover:-translate-y-0.5 transition-all duration-300 shadow-[0_8px_24px_rgba(54,31,26,0.18)] group">
                            <span class="tracking-wide">Create Account</span>
                            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform duration-200">arrow_forward</span>
                        </button>
                        <div class="text-center">
                            <p class="font-body text-sm text-on-surface-variant">
                                Already have an account?
                                <a href="{{ route('login') }}" class="text-primary font-semibold border-b-2 border-secondary-fixed-dim hover:border-primary transition-colors pb-0.5 ml-1">Sign in here</a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </section>
</main>

<script>
function switchTab(tab) {
    const isPersonnel = (tab === 'personnel');

    // Panels
    document.getElementById('panel-personnel').style.display = isPersonnel ? 'block' : 'none';
    document.getElementById('panel-client').style.display    = isPersonnel ? 'none' : 'block';

    // Left panel content
    document.getElementById('left-content-personnel').classList.toggle('hidden', !isPersonnel);
    document.getElementById('left-content-client').classList.toggle('hidden', isPersonnel);

    // Subtitle
    document.getElementById('form-subtitle').textContent = isPersonnel
        ? 'Please complete the details below to register.'
        : 'Register to manage your gallery engagements and bookings.';

    // Toggle buttons style
    const btnP = document.getElementById('btn-personnel');
    const btnC = document.getElementById('btn-client');

    if (isPersonnel) {
        btnP.className = 'flex-1 py-2.5 px-4 rounded-full bg-primary text-white font-body text-sm font-semibold transition-all duration-200 shadow-[0_4px_12px_rgba(78,52,46,0.18)] flex justify-center items-center gap-2';
        btnC.className = 'flex-1 py-2.5 px-4 rounded-full text-on-surface-variant hover:text-primary font-body text-sm font-medium transition-all duration-200 flex justify-center items-center gap-2';
    } else {
        btnC.className = 'flex-1 py-2.5 px-4 rounded-full bg-primary text-white font-body text-sm font-semibold transition-all duration-200 shadow-[0_4px_12px_rgba(78,52,46,0.18)] flex justify-center items-center gap-2';
        btnP.className = 'flex-1 py-2.5 px-4 rounded-full text-on-surface-variant hover:text-primary font-body text-sm font-medium transition-all duration-200 flex justify-center items-center gap-2';
    }
}

function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('span');
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility_off';
    }
}

    document.addEventListener('DOMContentLoaded', function() {
        const oldRole = "{{ old('role') }}";
        if (oldRole === 'klien') {
            switchTab('client');
        }
    });
</script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 50,
    });
</script>

</body>
</html>