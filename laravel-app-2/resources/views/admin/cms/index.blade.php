@extends('layouts.admin')

@section('title', 'CMS Landing Page – ART-HUB')
@section('page_title', 'Editor Landing Page')
@section('page_subtitle', 'Atur tampilan website publik Sanggar Cahaya Gumilang secara real-time.')

@section('content')

{{-- ALERT --}}
@if(session('success'))
    <div class="p-4 mb-6 text-sm text-green-700 bg-green-500/10 border border-green-500/20 rounded-xl flex items-center gap-2.5 font-bold shadow-sm animate-fade-up">
        <div class="w-7 h-7 rounded-lg bg-green-500/20 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-check-circle-fill text-green-500 text-sm"></i>
        </div>
        {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="p-4 mb-6 text-sm text-red-700 bg-red-500/10 border border-red-500/20 rounded-xl flex items-center gap-2.5 font-bold shadow-sm animate-fade-up">
        <div class="w-7 h-7 rounded-lg bg-red-500/20 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-exclamation-triangle-fill text-red-500 text-sm"></i>
        </div>
        {{ $errors->first() }}
    </div>
@endif

<div x-data="{
    activeTab: 'identitas',
    sanggarName: {{ json_encode($contents['sanggar_name'] ?? 'Cahaya Gumilang') }},
    heroTagline: {{ json_encode($contents['hero_tagline'] ?? 'Melestarikan Warisan Melalui Seni.') }},
    heroDescription: {{ json_encode($contents['hero_description'] ?? '') }},
    founderName: {{ json_encode($contents['history_founder_name'] ?? 'Bapa A. Kusmana') }},
    founderQuote: {{ json_encode($contents['history_quote'] ?? 'Seni bukan sekadar hiburan — ia adalah napas peradaban.') }},
    founderParagraph: {{ json_encode($contents['history_paragraph'] ?? '') }},
    founderPhotoActive: {{ ($contents['founder_photo_active'] ?? '1') === '1' ? 'true' : 'false' }},
    footerTagline: {{ json_encode($contents['footer_tagline'] ?? 'Pusat pelestarian dan pengembangan seni budaya tradisional Indonesia.') }},
    footerAddress: {{ json_encode($contents['footer_address'] ?? 'Jakarta, Indonesia') }},
    footerEmail: {{ json_encode($contents['footer_email'] ?? 'halo@cahayagumilang.id') }},
    footerCopyright: {{ json_encode($contents['footer_copyright'] ?? '© 2024 Cahaya Gumilang. All Rights Reserved.') }},
    heroImageSrc: {{ json_encode(!empty($contents['hero_image']) ? asset('storage/' . $contents['hero_image']) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuAKLJeZoxR8JGephSpt-CQ1qwaU9sXDbR_yFfvj_6hYNaUcw7tKNLK_SAqoX2jJhGKyMlYSaEUkIp8pc_tG0KZQn9D5MmfA9zZkQCHoMdVt4ahPKs3UZaGMJvQEjafV20nGf0iOcRwVhK7QGQMG1tUBHMRw5R259gVLoNw4PzeXIZGKOdZRfDoXvuMp7MNBEnN7OzKBKEXaNgiwCU66Ev2gnfn5xy3labIA8gxkvL1aXUIsbTv8QxMbyP8ql3EtI6Boi3-jMKHG5Q') }},
    logoSrc: {{ json_encode(!empty($contents['sanggar_logo']) ? asset('storage/' . $contents['sanggar_logo']) : '') }},
    founderPhotoSrc: {{ json_encode(!empty($contents['founder_photo']) ? asset('storage/' . $contents['founder_photo']) : '') }},
    logoName: '',
    heroImageName: '',
    founderPhotoName: ''
}" class="relative">

    <form action="{{ route('admin.cms.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-12 gap-6 items-start">
            
            {{-- NAVIGATION SIDEBAR PANEL (LEFT) --}}
            <div class="col-span-12 lg:col-span-4 space-y-4 lg:sticky lg:top-6">
                <!-- Navigation Options -->
                <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm p-4 space-y-1">
                    <div class="px-3 py-2 mb-2">
                        <span class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Navigasi Bagian Web</span>
                    </div>
                    
                    <!-- Identitas -->
                    <button type="button" @click="activeTab = 'identitas'"
                            :class="activeTab === 'identitas' ? 'bg-primary/5 border-primary text-primary font-bold shadow-[inset_3px_0_0_0_#361f1a]' : 'border-transparent text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface'"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border-l-2 text-left transition-all duration-200 group">
                        <div :class="activeTab === 'identitas' ? 'bg-primary text-white shadow-sm shadow-primary/20' : 'bg-surface-container text-outline group-hover:text-primary group-hover:bg-primary/10'"
                             class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200">
                            <i class="bi bi-building text-sm"></i>
                        </div>
                        <div>
                            <div class="font-body text-xs font-semibold leading-none">Identitas Sanggar</div>
                            <div class="font-body text-[0.65rem] text-outline mt-0.5 leading-none">Nama & logo utama</div>
                        </div>
                    </button>

                    <!-- Hero Banner -->
                    <button type="button" @click="activeTab = 'hero'"
                            :class="activeTab === 'hero' ? 'bg-primary/5 border-primary text-primary font-bold shadow-[inset_3px_0_0_0_#361f1a]' : 'border-transparent text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface'"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border-l-2 text-left transition-all duration-200 group">
                        <div :class="activeTab === 'hero' ? 'bg-primary text-white shadow-sm shadow-primary/20' : 'bg-surface-container text-outline group-hover:text-primary group-hover:bg-primary/10'"
                             class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200">
                            <i class="bi bi-image-fill text-sm"></i>
                        </div>
                        <div>
                            <div class="font-body text-xs font-semibold leading-none">Hero Banner</div>
                            <div class="font-body text-[0.65rem] text-outline mt-0.5 leading-none">Teks & gambar utama</div>
                        </div>
                    </button>

                    <!-- Pendiri -->
                    <button type="button" @click="activeTab = 'pendiri'"
                            :class="activeTab === 'pendiri' ? 'bg-primary/5 border-primary text-primary font-bold shadow-[inset_3px_0_0_0_#361f1a]' : 'border-transparent text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface'"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border-l-2 text-left transition-all duration-200 group">
                        <div :class="activeTab === 'pendiri' ? 'bg-primary text-white shadow-sm shadow-primary/20' : 'bg-surface-container text-outline group-hover:text-primary group-hover:bg-primary/10'"
                             class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200">
                            <i class="bi bi-person-badge-fill text-sm"></i>
                        </div>
                        <div>
                            <div class="font-body text-xs font-semibold leading-none">Profil Pendiri</div>
                            <div class="font-body text-[0.65rem] text-outline mt-0.5 leading-none">Sejarah & Kutipan</div>
                        </div>
                    </button>

                    <!-- Footer -->
                    <button type="button" @click="activeTab = 'footer'"
                            :class="activeTab === 'footer' ? 'bg-primary/5 border-primary text-primary font-bold shadow-[inset_3px_0_0_0_#361f1a]' : 'border-transparent text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface'"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border-l-2 text-left transition-all duration-200 group">
                        <div :class="activeTab === 'footer' ? 'bg-primary text-white shadow-sm shadow-primary/20' : 'bg-surface-container text-outline group-hover:text-primary group-hover:bg-primary/10'"
                             class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200">
                            <i class="bi bi-layout-text-window-reverse text-sm"></i>
                        </div>
                        <div>
                            <div class="font-body text-xs font-semibold leading-none">Footer & Kontak</div>
                            <div class="font-body text-[0.65rem] text-outline mt-0.5 leading-none">Alamat, email & info footer</div>
                        </div>
                    </button>
                </div>
                
                <!-- Live Preview Status -->
                <div class="bg-gradient-to-br from-secondary/15 to-secondary/5 border border-secondary/20 rounded-2xl shadow-sm p-4 flex gap-3">
                    <div class="w-8 h-8 rounded-xl bg-secondary/20 flex items-center justify-center flex-shrink-0 text-secondary">
                        <i class="bi bi-eye-fill"></i>
                    </div>
                    <div>
                        <h4 class="font-label text-[0.6rem] uppercase tracking-widest text-secondary font-bold">Preview Halaman</h4>
                        <p class="font-body text-xs text-on-surface-variant mt-1.5 leading-relaxed">Perubahan teks pada editor akan langsung diperbarui di bagian mockup di bawah secara real-time.</p>
                    </div>
                </div>
            </div>

            {{-- CONTENT EDITOR PANEL (RIGHT) --}}
            <div class="col-span-12 lg:col-span-8">
                <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm overflow-hidden relative min-h-[500px]">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-secondary"></div>
                    
                    {{-- ══════════ TAB CONTENT 1: IDENTITAS SANGGAR ══════════ --}}
                    <div x-show="activeTab === 'identitas'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="px-6 py-5 border-b border-outline-variant/20 bg-surface-container-low/50">
                            <h3 class="font-headline text-base font-bold text-primary">Identitas Sanggar</h3>
                            <p class="font-label text-[0.65rem] uppercase tracking-widest text-outline mt-0.5">Nama & logo sanggar yang tampil di navigasi web publik</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid md:grid-cols-2 gap-6 items-start">
                                <div>
                                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Nama Sanggar</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                            <i class="bi bi-shop"></i>
                                        </div>
                                        <input type="text" name="sanggar_name"
                                               x-model="sanggarName"
                                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl pl-10 pr-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all duration-200"
                                               placeholder="Cahaya Gumilang" required>
                                    </div>
                                    <p class="font-body text-[0.7rem] text-outline mt-1.5 leading-relaxed">Nama ini akan tampil di Navbar, Hero Section, dan Footer website utama.</p>
                                </div>
                                
                                <div>
                                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Logo Sanggar</label>
                                    <div class="flex items-center gap-4">
                                        {{-- Checkerboard background mockup --}}
                                        <div class="w-16 h-16 rounded-xl border border-outline-variant/30 flex flex-shrink-0 items-center justify-center overflow-hidden relative shadow-inner bg-surface-container-low"
                                             style="background-image: radial-gradient(#d4c3bf 1px, transparent 1px); background-size: 8px 8px;">
                                            <template x-if="logoSrc">
                                                <img :src="logoSrc" class="w-full h-full object-contain p-1.5" alt="Logo preview">
                                            </template>
                                            <template x-if="!logoSrc">
                                                <div class="font-headline font-bold text-xl text-primary/30">AH</div>
                                            </template>
                                        </div>
                                        
                                        <div class="flex-1">
                                            <label class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-outline-variant/50 hover:bg-surface-container hover:border-primary transition-colors cursor-pointer font-label text-[0.7rem] font-bold uppercase tracking-wider text-on-surface mb-1">
                                                <input type="file" name="sanggar_logo" class="hidden" accept="image/png,image/svg+xml,image/jpeg"
                                                       @change="
                                                            if ($event.target.files && $event.target.files[0]) {
                                                                const reader = new FileReader();
                                                                reader.onload = e => { logoSrc = e.target.result; };
                                                                reader.readAsDataURL($event.target.files[0]);
                                                                logoName = $event.target.files[0].name;
                                                            }
                                                       ">
                                                <i class="bi bi-upload"></i> Pilih Logo Baru
                                            </label>
                                            <p class="font-body text-[0.65rem] text-outline leading-tight">Format: PNG, SVG. Latar belakang transparan disarankan. Maks: 1MB.</p>
                                            <div x-show="logoName" class="font-body text-[0.65rem] text-secondary mt-1 flex items-center gap-1">
                                                <i class="bi bi-check-circle-fill"></i> <span x-text="logoName" class="truncate max-w-[150px]"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Preview Area --}}
                            <div class="border border-outline-variant/20 rounded-xl overflow-hidden mt-6">
                                <div class="bg-surface-container-low px-4 py-2 border-b border-outline-variant/20 flex justify-between items-center">
                                    <span class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold"><i class="bi bi-laptop me-1"></i> Preview Bagian Navbar</span>
                                    <span class="text-[0.6rem] bg-secondary/15 text-secondary font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Live Mockup</span>
                                </div>
                                <div class="bg-white px-6 py-4 flex items-center justify-between border-b border-outline-variant/10 shadow-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded bg-surface-container flex items-center justify-center border border-outline-variant/20">
                                            <template x-if="logoSrc">
                                                <img :src="logoSrc" class="w-full h-full object-contain p-1" alt="Navbar logo">
                                            </template>
                                            <template x-if="!logoSrc">
                                                <span class="text-[0.6rem] font-bold text-primary">AH</span>
                                            </template>
                                        </div>
                                        <span class="font-headline text-xs font-bold text-primary tracking-wide" x-text="sanggarName"></span>
                                    </div>
                                    <div class="flex gap-4 text-[0.65rem] font-bold text-outline uppercase tracking-wider">
                                        <span class="text-primary border-b border-primary">Home</span>
                                        <span>Katalog</span>
                                        <span>Tentang</span>
                                        <span>Kontak</span>
                                    </div>
                                    <span class="px-3 py-1 bg-primary text-white font-label text-[0.6rem] font-bold uppercase tracking-wider rounded-lg shadow-sm">Dashboard</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════ TAB CONTENT 2: HERO BANNER ══════════ --}}
                    <div x-show="activeTab === 'hero'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                        <div class="px-6 py-5 border-b border-outline-variant/20 bg-surface-container-low/50">
                            <h3 class="font-headline text-base font-bold text-primary">Hero & Banner Utama</h3>
                            <p class="font-label text-[0.65rem] uppercase tracking-widest text-outline mt-0.5">Atur gambar latar, tagline, dan deskripsi pembuka website</p>
                        </div>
                        <div class="p-6 space-y-6">
                            {{-- Image Banner Upload --}}
                            <div>
                                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2.5">Gambar Hero (Banner)</label>
                                <div class="grid md:grid-cols-12 gap-4 items-stretch">
                                    {{-- Banner Preview --}}
                                    <div class="md:col-span-7 rounded-xl overflow-hidden border border-outline-variant/30 bg-surface-container relative aspect-video shadow-sm">
                                        <img :src="heroImageSrc" class="w-full h-full object-cover" alt="Hero banner image preview">
                                        <div class="absolute inset-0 bg-gradient-to-r from-primary/60 to-transparent"></div>
                                        <div class="absolute top-2 right-2 bg-green-500 text-white text-[0.55rem] font-bold px-2 py-0.5 rounded uppercase tracking-wider">Aktif</div>
                                    </div>
                                    
                                    {{-- Upload area --}}
                                    <div class="md:col-span-5 flex flex-col justify-between">
                                        <label for="hero_image_input"
                                               class="flex flex-col items-center justify-center gap-3 border-2 border-dashed border-outline-variant/50 rounded-xl bg-surface-container hover:bg-surface-container-high hover:border-primary/40 transition-all cursor-pointer p-4 text-center h-full min-h-[120px] group">
                                            <div class="relative z-10 transition-all group-hover:scale-105 flex flex-col items-center gap-1.5">
                                                <i class="bi bi-cloud-arrow-up-fill text-2xl text-primary"></i>
                                                <span class="font-body text-xs font-bold text-on-surface leading-snug">Klik untuk ganti gambar</span>
                                                <span class="font-body text-[0.6rem] text-outline">JPG, PNG, WEBP. Maks 3MB</span>
                                            </div>
                                            <input type="file" name="hero_image" id="hero_image_input" class="hidden"
                                                   accept="image/jpg,image/jpeg,image/png,image/webp"
                                                   @change="
                                                        if ($event.target.files && $event.target.files[0]) {
                                                            const reader = new FileReader();
                                                            reader.onload = e => { heroImageSrc = e.target.result; };
                                                            reader.readAsDataURL($event.target.files[0]);
                                                            heroImageName = $event.target.files[0].name;
                                                        }
                                                   ">
                                        </label>
                                        <div x-show="heroImageName" class="font-body text-[0.65rem] text-secondary mt-1.5 flex items-center gap-1">
                                            <i class="bi bi-check-circle-fill"></i> <span x-text="heroImageName" class="truncate max-w-[180px]"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Tagline Hero</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-outline">
                                            <i class="bi bi-chat-quote-fill"></i>
                                        </div>
                                        <input type="text" name="hero_tagline"
                                               x-model="heroTagline"
                                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl pl-9 pr-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all duration-200"
                                               placeholder="Tagline besar di hero section">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Deskripsi Hero</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-outline">
                                            <i class="bi bi-text-paragraph"></i>
                                        </div>
                                        <input type="text" name="hero_description"
                                               x-model="heroDescription"
                                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl pl-9 pr-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all duration-200"
                                               placeholder="Kalimat pendek di bawah tagline">
                                    </div>
                                </div>
                            </div>

                            {{-- Preview Mockup --}}
                            <div class="border border-outline-variant/20 rounded-xl overflow-hidden mt-6">
                                <div class="bg-surface-container-low px-4 py-2 border-b border-outline-variant/20 flex justify-between items-center">
                                    <span class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold"><i class="bi bi-laptop me-1"></i> Preview Tampilan Hero</span>
                                    <span class="text-[0.6rem] bg-secondary/15 text-secondary font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Live Mockup</span>
                                </div>
                                <div class="relative bg-black h-48 flex items-center justify-center text-center overflow-hidden">
                                    <!-- Mockup Background Image -->
                                    <img :src="heroImageSrc" class="absolute inset-0 w-full h-full object-cover opacity-60" alt="Hero Mockup bg">
                                    <div class="absolute inset-0 bg-gradient-to-t from-primary/95 via-primary/50 to-primary/30"></div>
                                    
                                    <!-- Mockup Content -->
                                    <div class="relative px-6 py-4 z-10 text-white max-w-md">
                                        <h1 class="font-headline text-lg font-bold leading-tight" x-text="heroTagline || 'Melestarikan Warisan Melalui Seni.'"></h1>
                                        <p class="font-body text-[0.7rem] text-white/80 mt-2 line-clamp-2" x-text="heroDescription || 'Deskripsi singkat hero'"></p>
                                        <div class="mt-4 flex justify-center gap-2">
                                            <span class="px-3 py-1 bg-secondary text-secondary-container font-label text-[0.55rem] font-bold uppercase tracking-wider rounded-md">Booking Sekarang</span>
                                            <span class="px-3 py-1 border border-white/40 text-white font-label text-[0.55rem] font-bold uppercase tracking-wider rounded-md font-semibold">Pelajari Detail</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Indicator -->
                                    <div class="absolute bottom-2 right-2 flex items-center gap-1 bg-black/40 px-2 py-0.5 rounded text-[0.55rem] text-white/70 backdrop-blur-sm">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-ping"></span> Real-time
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════ TAB CONTENT 3: PROFIL PENDIRI ══════════ --}}
                    <div x-show="activeTab === 'pendiri'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                        <div class="px-6 py-5 border-b border-outline-variant/20 bg-surface-container-low/50">
                            <h3 class="font-headline text-base font-bold text-primary">Profil & Sejarah Pendiri</h3>
                            <p class="font-label text-[0.65rem] uppercase tracking-widest text-outline mt-0.5">Kelola foto, nama pendiri, kutipan penting, dan paragraf sejarah sanggar</p>
                        </div>
                        <div class="p-6 space-y-6">
                            {{-- Photo and Toggle Area --}}
                            <div>
                                <div class="flex items-center justify-between mb-3.5">
                                    <label class="font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold">Foto Pendiri (Bapa Kusmana)</label>
                                    
                                    {{-- Toggle --}}
                                    <div class="flex items-center gap-3">
                                        <span class="font-label text-[0.65rem] text-outline font-bold uppercase tracking-widest">Tampilkan Foto:</span>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="hidden" name="founder_photo_active" value="0">
                                            <input type="checkbox" name="founder_photo_active" value="1"
                                                   class="sr-only peer"
                                                   x-model="founderPhotoActive"
                                                   id="founderToggle">
                                            <div class="w-11 h-6 bg-surface-container-high peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-green-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                            <span class="ml-2 font-label text-[0.65rem] font-bold peer-checked:text-green-600 transition-colors"
                                                  :class="founderPhotoActive ? 'text-green-600' : 'text-outline'"
                                                  x-text="founderPhotoActive ? 'Aktif' : 'Nonaktif'">
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-12 gap-5 items-stretch">
                                    {{-- Image Preview Panel --}}
                                    <div class="md:col-span-4 rounded-xl overflow-hidden border border-outline-variant/30 bg-surface-container aspect-[3/4] relative flex items-center justify-center shadow-sm max-h-[180px]">
                                        <template x-if="founderPhotoSrc">
                                            <img :src="founderPhotoSrc" class="w-full h-full object-cover" alt="Founder image preview">
                                        </template>
                                        <template x-if="!founderPhotoSrc">
                                            <div class="flex flex-col items-center justify-center text-outline text-center p-4">
                                                <i class="bi bi-person-fill text-3xl mb-1"></i>
                                                <span class="text-[0.6rem] font-medium">Belum ada foto</span>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    {{-- Upload area & Warning --}}
                                    <div class="md:col-span-8 flex flex-col justify-between gap-3">
                                        <label for="founder_photo_input"
                                               class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-outline-variant/50 rounded-xl bg-surface-container hover:bg-surface-container-high hover:border-primary/40 transition-all cursor-pointer p-4 text-center h-full min-h-[90px] group">
                                            <div class="relative z-10 transition-all group-hover:scale-105 flex flex-col items-center gap-1">
                                                <i class="bi bi-person-bounding-box text-xl text-primary"></i>
                                                <span class="font-body text-xs font-bold text-on-surface">Pilih foto pendiri baru</span>
                                                <span class="font-body text-[0.6rem] text-outline">JPG, PNG, WEBP. Maks 2MB</span>
                                            </div>
                                            <input type="file" name="founder_photo" id="founder_photo_input" class="hidden"
                                                   accept="image/jpg,image/jpeg,image/png,image/webp"
                                                   @change="
                                                        if ($event.target.files && $event.target.files[0]) {
                                                            const reader = new FileReader();
                                                            reader.onload = e => { founderPhotoSrc = e.target.result; };
                                                            reader.readAsDataURL($event.target.files[0]);
                                                            founderPhotoName = $event.target.files[0].name;
                                                        }
                                                   ">
                                        </label>
                                        <div x-show="founderPhotoName" class="font-body text-[0.65rem] text-secondary flex items-center gap-1">
                                            <i class="bi bi-check-circle-fill"></i> <span x-text="founderPhotoName" class="truncate max-w-[200px]"></span>
                                        </div>
                                        <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-3.5 flex gap-2.5 items-start">
                                            <i class="bi bi-info-circle-fill text-amber-600 text-sm flex-shrink-0 mt-0.5"></i>
                                            <p class="font-body text-[0.7rem] text-amber-800 leading-normal">
                                                Nonaktifkan toggle "Tampilkan Foto" jika foto pendiri belum tersedia atau belum pas, agar tidak muncul di website publik.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Nama Pendiri</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-outline">
                                            <i class="bi bi-person-badge-fill"></i>
                                        </div>
                                        <input type="text" name="history_founder_name"
                                               x-model="founderName"
                                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl pl-9 pr-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all duration-200">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Kutipan (Quote) Pendiri</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 pt-2.5 flex items-start pointer-events-none text-outline">
                                            <i class="bi bi-chat-left-quote-fill"></i>
                                        </div>
                                        <textarea name="history_quote" rows="2"
                                                  x-model="founderQuote"
                                                  class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl pl-9 pr-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all duration-200 resize-none"
                                                  placeholder="Seni bukan sekadar hiburan — ia adalah napas peradaban."></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Paragraf Sejarah</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 pt-2.5 flex items-start pointer-events-none text-outline">
                                        <i class="bi bi-journal-text"></i>
                                    </div>
                                    <textarea name="history_paragraph" rows="4"
                                              x-model="founderParagraph"
                                              class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl pl-9 pr-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all duration-200 resize-none"
                                              placeholder="Ceritakan secara detail sejarah berdirinya sanggar..."></textarea>
                                </div>
                            </div>

                            {{-- Preview Mockup --}}
                            <div class="border border-outline-variant/20 rounded-xl overflow-hidden mt-6">
                                <div class="bg-surface-container-low px-4 py-2 border-b border-outline-variant/20 flex justify-between items-center">
                                    <span class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold"><i class="bi bi-laptop me-1"></i> Preview Tampilan Pendiri</span>
                                    <span class="text-[0.6rem] bg-secondary/15 text-secondary font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Live Mockup</span>
                                </div>
                                <div class="p-6 bg-surface-container-lowest">
                                    <div class="grid grid-cols-12 gap-4 items-center">
                                        <!-- Image Panel -->
                                        <div class="col-span-4" x-show="founderPhotoActive" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                                            <div class="aspect-[3/4] rounded-lg overflow-hidden bg-surface-container border border-outline-variant/30 shadow-sm relative">
                                                <template x-if="founderPhotoSrc">
                                                    <img :src="founderPhotoSrc" class="w-full h-full object-cover" alt="Founder mockup">
                                                </template>
                                                <template x-if="!founderPhotoSrc">
                                                    <div class="w-full h-full flex flex-col items-center justify-center text-outline">
                                                        <i class="bi bi-person text-xl"></i>
                                                        <span class="text-[0.5rem] font-bold">No Photo</span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        
                                        <!-- Text Content Panel -->
                                        <div :class="founderPhotoActive ? 'col-span-8' : 'col-span-12'" class="space-y-2">
                                            <div class="text-[0.55rem] font-bold text-secondary uppercase tracking-widest leading-none font-semibold">Sejarah & Pendiri</div>
                                            <h3 class="font-headline text-xs font-bold text-primary leading-tight" x-text="founderName || 'Bapa A. Kusmana'"></h3>
                                            
                                            <!-- Quote Block -->
                                            <div class="pl-2.5 border-l-2 border-secondary/50 py-0.5 italic">
                                                <p class="font-body text-[0.6rem] text-on-surface-variant leading-relaxed" x-text="founderQuote || 'Seni bukan sekadar hiburan...'"></p>
                                            </div>
                                            
                                            <!-- History Paragraph -->
                                            <p class="font-body text-[0.55rem] text-outline leading-relaxed line-clamp-3" x-text="founderParagraph || 'Belum ada deskripsi sejarah...'"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════ TAB CONTENT 4: FOOTER WEBSITE ══════════ --}}
                    <div x-show="activeTab === 'footer'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                        <div class="px-6 py-5 border-b border-outline-variant/20 bg-surface-container-low/50">
                            <h3 class="font-headline text-base font-bold text-primary">Footer Website & Kontak</h3>
                            <p class="font-label text-[0.65rem] uppercase tracking-widest text-outline mt-0.5">Sesuaikan tagline, alamat, email, dan teks hak cipta di bagian bawah halaman</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Tagline Footer</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 pt-2.5 flex items-start pointer-events-none text-outline">
                                            <i class="bi bi-chat-right-quote-fill"></i>
                                        </div>
                                        <textarea name="footer_tagline" rows="2"
                                                  x-model="footerTagline"
                                                  class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl pl-9 pr-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all duration-200 resize-none"></textarea>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Alamat</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-outline">
                                            <i class="bi bi-geo-alt-fill"></i>
                                        </div>
                                        <input type="text" name="footer_address"
                                               x-model="footerAddress"
                                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl pl-9 pr-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all duration-200">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Email Kontak</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-outline">
                                            <i class="bi bi-envelope-at-fill"></i>
                                        </div>
                                        <input type="email" name="footer_email"
                                               x-model="footerEmail"
                                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl pl-9 pr-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all duration-200">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Teks Copyright</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-outline">
                                            <i class="bi bi-c-circle-fill"></i>
                                        </div>
                                        <input type="text" name="footer_copyright"
                                               x-model="footerCopyright"
                                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl pl-9 pr-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all duration-200">
                                    </div>
                                </div>
                            </div>

                            {{-- Preview Mockup --}}
                            <div class="border border-outline-variant/20 rounded-xl overflow-hidden mt-6">
                                <div class="bg-surface-container-low px-4 py-2 border-b border-outline-variant/20 flex justify-between items-center">
                                    <span class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold"><i class="bi bi-laptop me-1"></i> Preview Tampilan Footer</span>
                                    <span class="text-[0.6rem] bg-secondary/15 text-secondary font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Live Mockup</span>
                                </div>
                                <div class="p-6 bg-primary text-white space-y-4">
                                    <div class="grid grid-cols-12 gap-4 pb-4 border-b border-white/10">
                                        <!-- Left: Logo & Tagline -->
                                        <div class="col-span-7 space-y-2">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded bg-white/10 flex items-center justify-center">
                                                    <template x-if="logoSrc">
                                                        <img :src="logoSrc" class="w-4 h-4 object-contain" alt="Logo mockup footer">
                                                    </template>
                                                    <template x-if="!logoSrc">
                                                        <span class="text-[0.5rem] font-bold text-white leading-none">AH</span>
                                                    </template>
                                                </div>
                                                <span class="font-headline text-xs font-bold tracking-wide" x-text="sanggarName"></span>
                                            </div>
                                            <p class="font-body text-[0.6rem] text-white/75 leading-relaxed" x-text="footerTagline"></p>
                                        </div>
                                        
                                        <!-- Contact details -->
                                        <div class="col-span-5 space-y-1.5">
                                            <div class="text-[0.5rem] uppercase tracking-widest text-white/50 font-bold">Hubungi Kami</div>
                                            <div class="flex items-center gap-1.5 text-[0.55rem] text-white/85">
                                                <i class="bi bi-geo-alt text-secondary"></i>
                                                <span x-text="footerAddress"></span>
                                            </div>
                                            <div class="flex items-center gap-1.5 text-[0.55rem] text-white/85">
                                                <i class="bi bi-envelope text-secondary"></i>
                                                <span class="truncate" x-text="footerEmail"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Bottom Copyright -->
                                    <div class="flex justify-between items-center text-[0.5rem] text-white/50">
                                        <span x-text="footerCopyright"></span>
                                        <span>Art-Hub Platform</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- STICKY FOOTER ACTIONS BAR --}}
                    <div class="sticky bottom-0 bg-surface-container-lowest/95 backdrop-blur border-t border-outline-variant/20 py-4 px-6 flex items-center justify-between gap-4 z-20">
                        <a href="{{ url('/') }}" target="_blank"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-outline-variant/50 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container hover:border-primary/30 transition-colors">
                            <i class="bi bi-box-arrow-up-right"></i> Lihat Landing Page
                        </a>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-lg">
                            <i class="bi bi-save2-fill"></i> Simpan Semua Perubahan
                        </button>
                    </div>

                </div>
            </div>

        </div>

    </form>

</div>

@endsection
