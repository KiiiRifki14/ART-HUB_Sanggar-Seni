
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $contents["sanggar_name"] ?? "Cahaya Gumilang" }} – Sanggar Seni</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Noto+Serif:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
tailwind.config = { theme: { extend: {
  colors: {
    primary:"#361f1a", "primary-container":"#4e342e",
    secondary:"#705d00", "secondary-container":"#fcd400", "on-secondary-container":"#3a2e00",
    surface:"#faf9f6", "surface-container-low":"#f4f3f1", "surface-container":"#efeeeb",
    "surface-container-high":"#e9e8e5", "surface-container-lowest":"#ffffff",
    "on-surface":"#1a1c1a", "on-surface-variant":"#504442",
    outline:"#827471", "outline-variant":"#d4c3bf",
  },
  fontFamily: { headline:["Noto Serif","serif"], body:["Manrope","sans-serif"], label:["Manrope","sans-serif"] }
}}}
</script>
<style>
* { box-sizing: border-box; }
html { scroll-behavior: smooth; }
.nav-link { font-family:"Manrope",sans-serif; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.12em; transition:color .2s; }
.section-label { font-family:"Manrope",sans-serif; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.18em; color:#705d00; }
.btn-primary { background:linear-gradient(135deg,#4E342E,#361F1A); color:#fff; font-family:"Manrope",sans-serif; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.12em; padding:.85rem 2rem; border-radius:8px; transition:opacity .2s; display:inline-block; text-decoration:none; }
.btn-primary:hover { opacity:.88; }
.btn-ghost { background:rgba(255,255,255,.1); backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,.25); color:#fff; font-family:"Manrope",sans-serif; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.12em; padding:.85rem 2rem; border-radius:8px; transition:background .2s; display:inline-block; text-decoration:none; }
.btn-ghost:hover { background:rgba(255,255,255,.2); }
.catalog-card { transition:transform .3s ease, box-shadow .3s ease; }
.catalog-card:hover { transform:translateY(-6px); box-shadow:0 20px 48px rgba(54,31,26,.12); }
.catalog-card img { transition:transform .5s ease; }
.catalog-card:hover img { transform:scale(1.06); }
.star-fill { color:#fcd400; }
/* mobile bottom nav padding */
@media(max-width:768px){ main { padding-bottom: 80px; } }
</style>
</head>
<body class="bg-surface font-body text-on-surface" x-data="{ showModal:false, svcTitle:'', svcDesc:'', svcPrice:'', mobileMenu:false }">

{{-- ═══════ NAVBAR ═══════ --}}
<header class="fixed top-0 left-0 right-0 z-50 bg-surface/90 backdrop-blur-md border-b border-outline-variant/20 shadow-sm">
  <div class="max-w-7xl mx-auto px-5 h-16 flex items-center justify-between">
    <a href="#" class="font-headline font-bold text-xl text-primary tracking-tight flex items-center gap-3">
      @if(!empty($contents['sanggar_logo']))
        <img src="{{ asset('storage/'.$contents['sanggar_logo']) }}" alt="Logo" class="h-9 w-auto object-contain">
      @endif
      <span class="text-secondary">{{ $contents["sanggar_name"] ?? "Cahaya Gumilang" }}</span>
    </a>
    <nav class="hidden md:flex items-center gap-7">
      <a href="#profil" class="nav-link text-on-surface-variant hover:text-secondary">Profil</a>
      <a href="#sejarah" class="nav-link text-on-surface-variant hover:text-secondary">Sejarah</a>
      <a href="#galeri" class="nav-link text-on-surface-variant hover:text-secondary">Seniman</a>
      <a href="#katalog" class="nav-link text-on-surface-variant hover:text-secondary">Katalog</a>
    </nav>
    <div class="flex items-center gap-3">
      @auth
        <a href="{{ url("/dashboard") }}" class="btn-primary py-2 px-4 text-xs">Dashboard</a>
      @else
        <a href="{{ route("login") }}" class="nav-link text-on-surface-variant hover:text-secondary hidden sm:block">Masuk</a>
        <a href="{{ route("register") }}" class="btn-primary py-2 px-4 text-xs">Daftar</a>
      @endauth
      <button class="md:hidden p-2 text-primary" @click="mobileMenu=!mobileMenu">
        <i class="bi bi-list text-2xl" x-show="!mobileMenu"></i>
        <i class="bi bi-x text-2xl" x-show="mobileMenu" style="display:none"></i>
      </button>
    </div>
  </div>
  {{-- Mobile dropdown --}}
  <div x-show="mobileMenu" style="display:none" class="md:hidden border-t border-outline-variant/20 bg-surface px-5 py-4 space-y-3">
    <a href="#profil" @click="mobileMenu=false" class="nav-link block text-on-surface-variant py-2">Profil</a>
    <a href="#sejarah" @click="mobileMenu=false" class="nav-link block text-on-surface-variant py-2">Sejarah</a>
    <a href="#galeri" @click="mobileMenu=false" class="nav-link block text-on-surface-variant py-2">Seniman</a>
    <a href="#katalog" @click="mobileMenu=false" class="nav-link block text-on-surface-variant py-2">Katalog</a>
  </div>
</header>

{{-- ═══════ HERO ═══════ --}}
<section id="profil" class="relative min-h-screen flex items-center overflow-hidden">
  <div class="absolute inset-0 z-0">
    @if(!empty($contents["hero_image"]))
      <img class="w-full h-full object-cover" src="{{ asset("storage/".$contents["hero_image"]) }}" alt="Hero">
    @else
      <div class="w-full h-full bg-gradient-to-br from-primary to-primary-container"></div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-r from-primary/85 via-primary/50 to-transparent"></div>
  </div>
  <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 pt-24 pb-16">
    <span class="inline-block mb-6 px-4 py-1.5 border border-secondary/40 rounded-full text-secondary text-xs font-label tracking-widest uppercase">
      <i class="bi bi-stars me-1"></i> Warisan Budaya Nusantara
    </span>
    <h1 class="font-headline text-4xl sm:text-5xl md:text-6xl lg:text-7xl text-white font-semibold leading-tight mb-6 max-w-3xl">
      {{ $contents["sanggar_name"] ?? "Cahaya Gumilang" }}<br>
      <em class="text-secondary-container/90">{{ $contents["hero_tagline"] ?? "Melestarikan Warisan Melalui Seni." }}</em>
    </h1>
    <p class="text-white/75 text-base md:text-lg font-body leading-relaxed mb-10 max-w-xl">
      {{ $contents["hero_description"] ?? "Menghadirkan keindahan abadi seni tradisi Indonesia melalui dedikasi lintas generasi." }}
    </p>
    <div class="flex flex-wrap gap-4">
      <a href="{{ route("register") }}" class="btn-primary">Booking Sekarang</a>
      <a href="#katalog" class="btn-ghost">Lihat Katalog</a>
    </div>
  </div>
</section>

{{-- ═══════ SEJARAH / PENDIRI ═══════ --}}
<section id="sejarah" class="py-20 md:py-28 px-6 lg:px-10 bg-surface">
  <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
    <div class="relative">
      <div class="aspect-[4/5] rounded-2xl overflow-hidden shadow-2xl">
        @if(!empty($contents["founder_photo"]) && ($contents["founder_photo_active"] ?? "1") === "1")
          <img class="w-full h-full object-cover" src="{{ asset("storage/".$contents["founder_photo"]) }}" alt="Pendiri">
        @else
          <div class="w-full h-full bg-gradient-to-br from-primary-container to-primary flex items-center justify-center">
            <i class="bi bi-person-circle text-9xl text-white/30"></i>
          </div>
        @endif
      </div>
      <div class="absolute -bottom-5 -right-5 bg-secondary-container rounded-xl p-5 shadow-xl hidden md:block">
        <p class="font-headline font-bold text-2xl text-primary">25+</p>
        <p class="text-xs font-label text-on-secondary-container uppercase tracking-widest">Tahun Berdiri</p>
      </div>
    </div>
    <div>
      <p class="section-label mb-4">Pendiri &amp; Jiwa Sanggar</p>
      <h2 class="font-headline text-3xl md:text-4xl text-primary font-semibold mb-6 leading-snug">
        <em>{{ $contents["history_founder_name"] ?? "Bapa A. Kusmana" }}</em>,<br>Cahaya yang Tak Pernah Padam.
      </h2>
      <blockquote class="border-l-4 border-secondary pl-5 mb-7">
        <p class="font-headline text-lg text-primary italic leading-relaxed mb-2">
          "{{ $contents["history_quote"] ?? "Seni bukan sekadar hiburan — ia adalah napas peradaban." }}"
        </p>
        <footer class="text-xs font-label font-bold text-secondary uppercase tracking-widest">— {{ $contents["history_founder_name"] ?? "A. Kusmana" }}</footer>
      </blockquote>
      <p class="text-on-surface-variant leading-relaxed text-sm md:text-base mb-10">
        {{ $contents["history_paragraph"] ?? "Atas visi dan dedikasi sang pendiri, sanggar ini lahir sebagai ruang kebudayaan yang membentuk karakter dan jiwa para seniman muda Indonesia." }}
      </p>
      <div class="flex gap-10">
        <div><p class="font-headline text-3xl font-bold text-primary">150+</p><p class="text-xs font-label text-on-surface-variant uppercase tracking-widest mt-1">Koleksi Alat Musik</p></div>
        <div><p class="font-headline text-3xl font-bold text-primary">500+</p><p class="text-xs font-label text-on-surface-variant uppercase tracking-widest mt-1">Seniman Dididik</p></div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════ KATALOG JASA ═══════ --}}
<section id="katalog" class="py-20 md:py-28 px-6 lg:px-10 bg-surface-container-low">
  <div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
      <div>
        <p class="section-label mb-2">Pilih Pengalaman Anda</p>
        <h2 class="font-headline text-3xl md:text-4xl text-primary font-semibold">Katalog Jasa</h2>
      </div>
      <a href="#katalog" class="text-sm font-label font-bold text-secondary uppercase tracking-widest hover:underline">Semua Paket &rarr;</a>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-7">
      @forelse($catalogs as $svc)
      <div class="catalog-card bg-surface-container-lowest rounded-2xl overflow-hidden border border-outline-variant/20 flex flex-col shadow-sm">
        <div class="relative h-52 overflow-hidden bg-surface-container">
          @if($svc->image)
            <img class="w-full h-full object-cover" src="{{ asset("storage/".$svc->image) }}" alt="{{ $svc->name }}">
          @else
            <div class="w-full h-full flex items-center justify-center"><i class="bi bi-image text-5xl text-outline/30"></i></div>
          @endif
          @if($svc->badge)
            <span class="absolute top-3 right-3 bg-secondary-container text-on-secondary-container text-[0.6rem] font-bold font-label uppercase tracking-widest px-3 py-1 rounded-full">{{ $svc->badge }}</span>
          @endif
        </div>
        <div class="p-6 flex flex-col flex-grow">
          <h3 class="font-headline text-xl text-primary font-semibold mb-2">{{ $svc->name }}</h3>
          <p class="text-sm text-on-surface-variant leading-relaxed mb-5 flex-grow">{{ $svc->description }}</p>
          <div class="border-t border-outline-variant/20 pt-4 flex items-center justify-between">
            <div>
              <p class="text-[0.6rem] font-label uppercase tracking-widest text-outline mb-0.5">Mulai Dari</p>
              <p class="font-headline font-bold text-lg text-primary">{{ $svc->price_formatted }}</p>
            </div>
            <button @click="showModal=true; svcTitle=`{{ addslashes($svc->name) }}`; svcDesc=`{{ addslashes($svc->detail ?? $svc->description) }}`; svcPrice=`{{ $svc->price_formatted }}`"
              class="w-10 h-10 rounded-full bg-surface-container hover:bg-primary hover:text-white text-on-surface transition-all flex items-center justify-center">
              <i class="bi bi-arrow-right"></i>
            </button>
          </div>
        </div>
      </div>
      @empty
      <div class="col-span-3 py-16 text-center text-on-surface-variant">
        <i class="bi bi-collection text-5xl block mb-3 opacity-30"></i>
        <p class="text-sm">Belum ada katalog jasa.</p>
      </div>
      @endforelse
    </div>
  </div>
</section>

{{-- ═══════ GALERI SENIMAN ═══════ --}}
<section id="galeri" class="py-20 md:py-28 px-6 lg:px-10 bg-surface">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-12">
      <p class="section-label mb-2">Mengenal Talenta Kami</p>
      <h2 class="font-headline text-3xl md:text-4xl text-primary font-semibold italic">Para Seniman Cahaya Gumilang</h2>
    </div>
    <div x-data="{ showAll: false }">
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($personnels as $index => $person)
        <div class="group text-center" x-show="showAll || {{ $index }} < 8" @if($index >= 8) style="display:none;" x-transition @endif>
          <div class="aspect-square mb-3 overflow-hidden rounded-xl bg-surface-container-high">
            @if($person->photo)
              <img class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500" src="{{ asset("storage/".$person->photo) }}" alt="{{ $person->user->name }}">
            @else
              <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-container to-primary">
                <i class="bi bi-person-fill text-4xl text-white/50"></i>
              </div>
            @endif
          </div>
          <h3 class="font-headline text-base text-primary font-bold leading-snug">{{ $person->stage_name ?? $person->user->name }}</h3>
          <p class="text-[0.65rem] font-label uppercase tracking-widest text-on-surface-variant mt-0.5">{{ ucwords(str_replace("_"," ",$person->specialty)) }}</p>
        </div>
        @endforeach
      </div>
      @if(count($personnels) > 8)
      <div class="mt-10 text-center" x-show="!showAll">
        <button @click="showAll=true" class="px-8 py-3 rounded-xl border border-outline-variant text-on-surface-variant font-label text-xs font-bold uppercase tracking-widest hover:border-secondary hover:text-secondary transition-colors">
          Lihat Semua Seniman ({{ count($personnels) }})
        </button>
      </div>
      <div class="mt-10 text-center" x-show="showAll" style="display:none">
        <button @click="showAll=false" class="px-8 py-3 rounded-xl border border-outline-variant text-on-surface-variant font-label text-xs font-bold uppercase tracking-widest hover:border-secondary hover:text-secondary transition-colors">Sembunyikan</button>
      </div>
      @endif
    </div>
  </div>
</section>

{{-- ═══════ TESTIMONI ═══════ --}}
<section class="py-20 px-6 lg:px-10 bg-surface-container-low">
  <div class="max-w-4xl mx-auto">
    <div class="text-center mb-12">
      <p class="section-label mb-2">Suara Klien Kami</p>
      <h2 class="font-headline text-3xl md:text-4xl text-primary font-semibold">Kesaksian Budaya</h2>
    </div>
    <div class="grid md:grid-cols-2 gap-6">
      <div class="bg-surface-container-lowest rounded-2xl p-7 border border-outline-variant/20 shadow-sm">
        <div class="flex gap-1 mb-4 text-secondary-container"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
        <p class="font-headline text-base text-primary italic mb-5 leading-relaxed">"Pertunjukan yang benar-benar memukau. Cahaya Gumilang berhasil menghadirkan jiwa Nusantara ke dalam acara pernikahan kami dengan sangat elegan."</p>
        <div><p class="font-bold text-sm text-primary">Anindya Putri</p><p class="text-xs text-on-surface-variant uppercase tracking-widest mt-0.5">Event Organizer</p></div>
      </div>
      <div class="bg-surface-container-lowest rounded-2xl p-7 border border-outline-variant/20 shadow-sm">
        <div class="flex gap-1 mb-4 text-secondary-container"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i></div>
        <p class="font-headline text-base text-primary italic mb-5 leading-relaxed">"Kualitas musik gamelannya sangat otentik. Para musisi menunjukkan disiplin dan dedikasi yang luar biasa pada seni tradisi."</p>
        <div><p class="font-bold text-sm text-primary">Robert Wagner</p><p class="text-xs text-on-surface-variant uppercase tracking-widest mt-0.5">Cultural Collector</p></div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════ CTA ═══════ --}}
<section class="py-20 px-6 bg-primary text-white text-center relative overflow-hidden">
  <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image:repeating-linear-gradient(90deg,#fff 0,#fff 1px,transparent 0,transparent 60px),repeating-linear-gradient(0deg,#fff 0,#fff 1px,transparent 0,transparent 60px)"></div>
  <div class="relative max-w-2xl mx-auto">
    <h2 class="font-headline text-3xl md:text-4xl font-semibold mb-4">Mulai Perjalanan Budaya Anda</h2>
    <p class="text-white/70 mb-8 font-body text-sm leading-relaxed">Jadilah bagian dari penjaga warisan nusantara. Daftarkan acara Anda atau hubungi kami untuk konsultasi konsep seni yang unik.</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="{{ route("register") }}" class="bg-secondary-container text-on-secondary-container font-bold text-xs font-label uppercase tracking-widest px-8 py-3.5 rounded-xl hover:opacity-90 transition-opacity">Registrasi Akun</a>
      <a href="mailto:{{ $contents["footer_email"] ?? "halo@cahayagumilang.id" }}" class="border border-white/30 text-white font-bold text-xs font-label uppercase tracking-widest px-8 py-3.5 rounded-xl hover:bg-white/10 transition-all">Hubungi Kami</a>
    </div>
  </div>
</section>

{{-- ═══════ FOOTER ═══════ --}}
<footer class="bg-surface py-16 px-6 lg:px-10 border-t border-outline-variant/20">
  <div class="max-w-7xl mx-auto">
    <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-10 pb-12 border-b border-outline-variant/20">
        <div class="md:col-span-2">
          <div class="flex items-center gap-3 mb-3">
            @if(!empty($contents['sanggar_logo']))
              <img src="{{ asset('storage/'.$contents['sanggar_logo']) }}" alt="Logo" class="h-10 w-auto object-contain">
            @endif
            <h3 class="font-headline text-2xl text-primary font-bold">{{ $contents["sanggar_name"] ?? "Cahaya Gumilang" }}</h3>
          </div>
          <p class="text-on-surface-variant text-sm leading-relaxed max-w-xs">{{ $contents["footer_tagline"] ?? "Pusat pelestarian dan pengembangan seni budaya tradisional Indonesia." }}</p>
        </div>
      <div>
        <h4 class="font-label text-xs font-bold uppercase tracking-widest text-primary mb-5">Navigasi</h4>
        <ul class="space-y-3 text-sm text-on-surface-variant">
          <li><a href="#profil" class="hover:text-secondary transition-colors">Profil Sanggar</a></li>
          <li><a href="#sejarah" class="hover:text-secondary transition-colors">Sejarah</a></li>
          <li><a href="#galeri" class="hover:text-secondary transition-colors">Galeri Seniman</a></li>
          <li><a href="#katalog" class="hover:text-secondary transition-colors">Katalog Jasa</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-label text-xs font-bold uppercase tracking-widest text-primary mb-5">Hubungi Kami</h4>
        <ul class="space-y-3 text-sm text-on-surface-variant">
          <li class="flex items-start gap-2"><i class="bi bi-geo-alt-fill text-secondary flex-shrink-0 mt-0.5"></i> {{ $contents["footer_address"] ?? "Jakarta, Indonesia" }}</li>
          <li class="flex items-start gap-2"><i class="bi bi-envelope-fill text-secondary flex-shrink-0 mt-0.5"></i> {{ $contents["footer_email"] ?? "halo@cahayagumilang.id" }}</li>
        </ul>
      </div>
    </div>
    <div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-3 text-[0.65rem] font-label uppercase tracking-widest text-on-surface-variant/50">
      <p>{{ $contents["footer_copyright"] ?? "© ".date("Y")." Cahaya Gumilang. Hak Cipta Dilindungi." }}</p>
      <p>Heritage Modernist Design</p>
    </div>
  </div>
</footer>

{{-- ═══════ MOBILE BOTTOM NAV ═══════ --}}
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-surface/95 backdrop-blur-xl border-t border-outline-variant/20 flex justify-around items-center px-2 pt-2 pb-3 shadow-lg">
  <a href="#profil" class="flex flex-col items-center gap-1 text-secondary px-3">
    <i class="bi bi-house-fill text-xl"></i>
    <span class="text-[0.55rem] font-label font-bold uppercase tracking-widest">Beranda</span>
  </a>
  <a href="#katalog" class="flex flex-col items-center gap-1 text-on-surface-variant/50 hover:text-secondary px-3">
    <i class="bi bi-collection text-xl"></i>
    <span class="text-[0.55rem] font-label font-bold uppercase tracking-widest">Katalog</span>
  </a>
  <a href="#galeri" class="flex flex-col items-center gap-1 text-on-surface-variant/50 hover:text-secondary px-3">
    <i class="bi bi-people-fill text-xl"></i>
    <span class="text-[0.55rem] font-label font-bold uppercase tracking-widest">Seniman</span>
  </a>
  @auth
    <a href="{{ url("/dashboard") }}" class="flex flex-col items-center gap-1 text-on-surface-variant/50 hover:text-secondary px-3">
      <i class="bi bi-speedometer2 text-xl"></i>
      <span class="text-[0.55rem] font-label font-bold uppercase tracking-widest">Dashboard</span>
    </a>
  @else
    <a href="{{ route("register") }}" class="flex flex-col items-center gap-1 text-on-surface-variant/50 hover:text-secondary px-3">
      <i class="bi bi-person-plus-fill text-xl"></i>
      <span class="text-[0.55rem] font-label font-bold uppercase tracking-widest">Daftar</span>
    </a>
  @endauth
</nav>

{{-- ═══════ SERVICE DETAIL MODAL ═══════ --}}
<div x-show="showModal" style="display:none" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
  <div class="fixed inset-0 bg-primary/50 backdrop-blur-sm" @click="showModal=false"></div>
  <div x-show="showModal"
       x-transition:enter="transition ease-out duration-250"
       x-transition:enter-start="opacity-0 scale-95"
       x-transition:enter-end="opacity-100 scale-100"
       class="relative bg-surface-container-lowest w-full max-w-md rounded-2xl shadow-2xl border border-outline-variant/20 overflow-hidden z-10">
    <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-low flex items-center justify-between">
      <h5 class="font-headline font-bold text-lg text-primary" x-text="svcTitle"></h5>
      <button @click="showModal=false" class="text-on-surface-variant hover:text-primary w-8 h-8 flex items-center justify-center rounded-lg hover:bg-surface-container transition-all">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
    <div class="p-6 space-y-4">
      <p class="font-body text-sm text-on-surface-variant leading-relaxed" x-text="svcDesc"></p>
      <div class="bg-secondary-container/10 border border-secondary-container/30 rounded-xl p-4 flex items-center justify-between">
        <span class="font-label text-xs font-bold uppercase tracking-widest text-secondary">Estimasi Mulai</span>
        <span class="font-headline font-bold text-xl text-primary" x-text="svcPrice"></span>
      </div>
    </div>
    <div class="px-6 py-4 border-t border-outline-variant/20 bg-surface-container-low flex justify-end gap-3">
      <button @click="showModal=false" class="px-5 py-2.5 rounded-xl border border-outline-variant text-on-surface-variant font-label text-xs font-bold uppercase tracking-widest hover:bg-surface-container transition-colors">Tutup</button>
      <a href="{{ route("register") }}" class="btn-primary py-2.5 px-5 text-xs">Booking Sekarang</a>
    </div>
  </div>
</div>

</body>
</html>
