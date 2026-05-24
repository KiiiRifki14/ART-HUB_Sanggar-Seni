@extends('layouts.admin')

@section('title', 'CMS Landing Page – ART-HUB')
@section('page_title', 'Editor Landing Page')
@section('page_subtitle', 'Atur tampilan website publik Sanggar Cahaya Gumilang secara real-time.')

@section('content')

{{-- ALERT --}}
@if(session('success'))
    <div class="p-4 mb-6 text-sm text-green-700 bg-green-500/10 border border-green-500/20 rounded-xl flex items-center gap-2 font-bold">
        <i class="bi bi-check-circle-fill text-green-500"></i> {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="p-4 mb-6 text-sm text-red-700 bg-red-500/10 border border-red-500/20 rounded-xl font-bold">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}
    </div>
@endif

<form action="{{ route('admin.cms.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    {{-- ══════════ SECTION 1: IDENTITAS SANGGAR ══════════ --}}
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-low/50 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                <i class="bi bi-building text-primary"></i>
            </div>
            <div>
                <div class="font-headline text-base text-primary font-bold">Identitas Sanggar</div>
                <p class="font-label text-[0.65rem] uppercase tracking-widest text-outline">Nama yang tampil di navbar, hero, dan footer</p>
            </div>
        </div>
        <div class="p-6">
            <div class="grid md:grid-cols-2 gap-6 items-start">
                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Nama Sanggar</label>
                    <input type="text" name="sanggar_name"
                           value="{{ $contents['sanggar_name'] ?? 'Cahaya Gumilang' }}"
                           class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 font-headline text-lg font-bold text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                           placeholder="Cahaya Gumilang" required>
                    <p class="font-body text-xs text-outline mt-1.5">Nama ini akan tampil di Navbar, Hero Section, dan Footer website.</p>
                </div>
                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Logo Sanggar</label>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-surface-container border border-outline-variant/30 flex flex-shrink-0 items-center justify-center overflow-hidden">
                            @if(!empty($contents['sanggar_logo']))
                                <img src="{{ asset('storage/' . $contents['sanggar_logo']) }}" id="logoPreview" class="w-full h-full object-contain" alt="Logo">
                            @else
                                <div class="font-headline font-bold text-xl text-primary" id="logoTextFallback">AH</div>
                                <img src="" id="logoPreview" class="w-full h-full object-contain hidden" alt="Logo">
                            @endif
                        </div>
                        <div class="flex-1">
                            <label class="inline-block px-4 py-2 rounded-lg border border-outline-variant/50 hover:bg-surface-container hover:border-primary transition-colors cursor-pointer text-xs font-semibold text-on-surface mb-1">
                                <input type="file" name="sanggar_logo" class="hidden" accept="image/png,image/svg+xml,image/jpeg" onchange="previewLogo(this)">
                                <i class="bi bi-upload me-1"></i> Pilih Logo Baru
                            </label>
                            <p class="font-body text-[0.65rem] text-outline">Format: PNG, SVG. Latar belakang transparan lebih disarankan. Maks: 1MB.</p>
                            <p id="logo_image_name" class="font-body text-[0.65rem] text-secondary mt-1 hidden"><i class="bi bi-check-circle-fill"></i> <span></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════ SECTION 2: HERO / BANNER UTAMA ══════════ --}}
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-low/50 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-secondary/10 flex items-center justify-center flex-shrink-0">
                <i class="bi bi-image-fill text-secondary"></i>
            </div>
            <div>
                <div class="font-headline text-base text-primary font-bold">Hero / Banner Utama</div>
                <p class="font-label text-[0.65rem] uppercase tracking-widest text-outline">Gambar & teks bagian paling atas landing page</p>
            </div>
        </div>
        <div class="p-6 space-y-5">
            {{-- Preview & Upload Hero Image --}}
            <div>
                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-3">Gambar Hero (Banner)</label>
                <div class="grid md:grid-cols-2 gap-4 items-start">
                    {{-- Preview --}}
                    <div class="rounded-xl overflow-hidden border border-outline-variant/30 bg-surface-container aspect-video relative">
                        @if(!empty($contents['hero_image']))
                            <img src="{{ asset('storage/' . $contents['hero_image']) }}"
                                 class="w-full h-full object-cover" alt="Hero Image" id="heroPreview">
                            <div class="absolute top-2 right-2 bg-green-500 text-white text-[0.6rem] font-bold px-2 py-0.5 rounded-md uppercase">Aktif</div>
                        @else
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAKLJeZoxR8JGephSpt-CQ1qwaU9sXDbR_yFfvj_6hYNaUcw7tKNLK_SAqoX2jJhGKyMlYSaEUkIp8pc_tG0KZQn9D5MmfA9zZkQCHoMdVt4ahPKs3UZaGMJvQEjafV20nGf0iOcRwVhK7QGQMG1tUBHMRw5R259gVLoNw4PzeXIZGKOdZRfDoXvuMp7MNBEnN7OzKBKEXaNgiwCU66Ev2gnfn5xy3labIA8gxkvL1aXUIsbTv8QxMbyP8ql3EtI6Boi3-jMKHG5Q"
                                 class="w-full h-full object-cover" alt="Default Hero" id="heroPreview">
                            <div class="absolute top-2 right-2 bg-outline text-white text-[0.6rem] font-bold px-2 py-0.5 rounded-md uppercase">Default</div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-r from-primary/60 to-transparent pointer-events-none"></div>
                    </div>
                    {{-- Upload Area --}}
                    <div>
                        <label for="hero_image_input"
                               class="flex flex-col items-center justify-center gap-3 h-full min-h-[140px] border-2 border-dashed border-outline-variant/50 rounded-xl bg-surface-container hover:bg-surface-container-high hover:border-primary/40 transition-all cursor-pointer px-4 py-8 text-center">
                            <i class="bi bi-cloud-arrow-up-fill text-3xl text-outline"></i>
                            <div>
                                <p class="font-body text-sm font-bold text-on-surface">Klik untuk ganti gambar hero</p>
                                <p class="font-body text-xs text-outline mt-0.5">JPG, PNG, WEBP — Maks. 3MB</p>
                            </div>
                            <input type="file" name="hero_image" id="hero_image_input" class="hidden"
                                   accept="image/jpg,image/jpeg,image/png,image/webp"
                                   onchange="previewImage(this, 'heroPreview')">
                        </label>
                        <p id="hero_image_name" class="font-body text-xs text-secondary mt-2 hidden"><i class="bi bi-check-circle-fill"></i> <span></span></p>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Tagline Hero</label>
                    <input type="text" name="hero_tagline"
                           value="{{ $contents['hero_tagline'] ?? 'Melestarikan Warisan Melalui Seni.' }}"
                           class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                           placeholder="Tagline besar di hero section">
                </div>
                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Deskripsi Hero</label>
                    <input type="text" name="hero_description"
                           value="{{ $contents['hero_description'] ?? '' }}"
                           class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                           placeholder="Kalimat pendek di bawah tagline">
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════ SECTION 3: PENDIRI SANGGAR ══════════ --}}
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-low/50 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                <i class="bi bi-person-badge-fill text-primary"></i>
            </div>
            <div>
                <div class="font-headline text-base text-primary font-bold">Profil Pendiri Sanggar</div>
                <p class="font-label text-[0.65rem] uppercase tracking-widest text-outline">Informasi di bagian "Sejarah & Pendiri"</p>
            </div>
        </div>
        <div class="p-6 space-y-5">
            {{-- Foto Pendiri --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold">Foto Pendiri (Bapa Kusmana)</label>
                    {{-- Toggle Aktif/Nonaktif --}}
                    <div class="flex items-center gap-3">
                        <span class="font-label text-xs text-on-surface-variant font-bold uppercase tracking-widest">Tampilkan Foto:</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="founder_photo_active" value="0">
                            <input type="checkbox" name="founder_photo_active" value="1"
                                   class="sr-only peer"
                                   {{ ($contents['founder_photo_active'] ?? '1') === '1' ? 'checked' : '' }}
                                   id="founderToggle">
                            <div class="w-11 h-6 bg-surface-container-high peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-green-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                            <span class="ml-2 font-label text-xs font-bold text-on-surface-variant peer-checked:text-green-600" id="toggleLabel">
                                {{ ($contents['founder_photo_active'] ?? '1') === '1' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </label>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4 items-start">
                    {{-- Preview --}}
                    <div class="rounded-xl overflow-hidden border border-outline-variant/30 bg-surface-container aspect-[4/5] relative max-h-64">
                        @if(!empty($contents['founder_photo']))
                            <img src="{{ asset('storage/' . $contents['founder_photo']) }}"
                                 class="w-full h-full object-cover" alt="Foto Pendiri" id="founderPreview">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-outline bg-surface-container" id="founderPreviewPlaceholder">
                                <i class="bi bi-person-fill text-5xl mb-2"></i>
                                <p class="font-body text-xs">Belum ada foto</p>
                            </div>
                            <img src="" class="w-full h-full object-cover hidden" alt="Foto Pendiri" id="founderPreview">
                        @endif
                    </div>
                    {{-- Upload --}}
                    <div class="space-y-3">
                        <label for="founder_photo_input"
                               class="flex flex-col items-center justify-center gap-3 border-2 border-dashed border-outline-variant/50 rounded-xl bg-surface-container hover:bg-surface-container-high hover:border-primary/40 transition-all cursor-pointer px-4 py-8 text-center">
                            <i class="bi bi-person-square text-3xl text-outline"></i>
                            <div>
                                <p class="font-body text-sm font-bold text-on-surface">Ganti foto pendiri</p>
                                <p class="font-body text-xs text-outline mt-0.5">JPG, PNG, WEBP — Maks. 2MB</p>
                            </div>
                            <input type="file" name="founder_photo" id="founder_photo_input" class="hidden"
                                   accept="image/jpg,image/jpeg,image/png,image/webp"
                                   onchange="previewFounder(this)">
                        </label>
                        <p id="founder_photo_name" class="font-body text-xs text-secondary hidden"><i class="bi bi-check-circle-fill"></i> <span></span></p>
                        <div class="bg-amber-500/10 border border-amber-500/20 rounded-lg p-3">
                            <p class="font-body text-xs text-amber-700"><i class="bi bi-info-circle-fill me-1"></i>
                                Nonaktifkan toggle di atas jika foto belum tersedia agar tidak tampil di website publik.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Nama Pendiri</label>
                    <input type="text" name="history_founder_name"
                           value="{{ $contents['history_founder_name'] ?? 'Bapa A. Kusmana' }}"
                           class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                </div>
                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Kutipan (Quote) Pendiri</label>
                    <textarea name="history_quote" rows="2"
                              class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors resize-none">{{ $contents['history_quote'] ?? 'Seni bukan sekadar hiburan — ia adalah napas peradaban.' }}</textarea>
                </div>
            </div>
            <div>
                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Paragraf Sejarah</label>
                <textarea name="history_paragraph" rows="4"
                          class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors resize-none">{{ $contents['history_paragraph'] ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- ══════════ SECTION 4: FOOTER ══════════ --}}
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-low/50 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-outline/10 flex items-center justify-center flex-shrink-0">
                <i class="bi bi-layout-text-window-reverse text-outline"></i>
            </div>
            <div>
                <div class="font-headline text-base text-primary font-bold">Footer Website</div>
                <p class="font-label text-[0.65rem] uppercase tracking-widest text-outline">Kontak dan keterangan di bagian bawah halaman</p>
            </div>
        </div>
        <div class="p-6 grid md:grid-cols-2 gap-5">
            <div>
                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Tagline Footer</label>
                <textarea name="footer_tagline" rows="2"
                          class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors resize-none">{{ $contents['footer_tagline'] ?? 'Pusat pelestarian dan pengembangan seni budaya tradisional Indonesia.' }}</textarea>
            </div>
            <div>
                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Alamat</label>
                <input type="text" name="footer_address"
                       value="{{ $contents['footer_address'] ?? 'Jakarta, Indonesia' }}"
                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
            </div>
            <div>
                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Email Kontak</label>
                <input type="email" name="footer_email"
                       value="{{ $contents['footer_email'] ?? 'halo@cahayagumilang.id' }}"
                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
            </div>
            <div>
                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Teks Copyright</label>
                <input type="text" name="footer_copyright"
                       value="{{ $contents['footer_copyright'] ?? '© 2024 Cahaya Gumilang. All Rights Reserved.' }}"
                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
            </div>
        </div>
    </div>

    {{-- ══════════ SECTION KONTAK & PEMBAYARAN ══════════ --}}
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-low/50 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-green-500/10 flex items-center justify-center flex-shrink-0">
                <i class="bi bi-telephone-fill text-green-600"></i>
            </div>
            <div>
                <div class="font-headline text-base text-primary font-bold">Informasi Kontak & Pembayaran</div>
                <p class="font-label text-[0.65rem] uppercase tracking-widest text-outline">Nomor WA Admin & Rekening Bank (ditampilkan di portal Klien)</p>
            </div>
        </div>
        <div class="p-6 grid md:grid-cols-2 gap-6">
            <div>
                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Nomor WhatsApp Admin</label>
                <input type="text" name="admin_whatsapp"
                       value="{{ $contents['admin_whatsapp'] ?? '' }}"
                       placeholder="628123456789 (tanpa tanda +)"
                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                <p class="font-body text-xs text-outline mt-1.5">Format: 628xxx (kode negara tanpa +). Digunakan untuk tombol "Negosiasi Harga" di portal Klien.</p>
            </div>
            <div>
                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Jenis Bank</label>
                <input type="text" name="bank_type"
                       value="{{ $contents['bank_type'] ?? 'BCA' }}"
                       placeholder="BCA / BRI / Mandiri / dll"
                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
            </div>
            <div>
                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Nomor Rekening</label>
                <input type="text" name="bank_account_number"
                       value="{{ $contents['bank_account_number'] ?? '' }}"
                       placeholder="1234 5678 90"
                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
            </div>
            <div>
                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Nama Pemilik Rekening (a/n)</label>
                <input type="text" name="bank_account_name"
                       value="{{ $contents['bank_account_name'] ?? '' }}"
                       placeholder="Nama sesuai buku tabungan"
                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                <p class="font-body text-xs text-outline mt-1.5">Ditampilkan di halaman pembayaran portal Klien.</p>
            </div>
        </div>
    </div>

    {{-- TOMBOL AKSI --}}
    <div class="flex items-center justify-between gap-4 py-2">
        <a href="{{ url('/') }}" target="_blank"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-outline-variant/50 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container hover:border-primary/30 transition-colors">
            <i class="bi bi-box-arrow-up-right"></i> Lihat Landing Page
        </a>
        <button type="submit"
                class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-lg">
            <i class="bi bi-save2-fill"></i> Simpan Semua Perubahan
        </button>
    </div>

</form>


@push('scripts')
<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('logoPreview');
            const fallback = document.getElementById('logoTextFallback');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (fallback) fallback.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
        const nameEl = document.getElementById('logo_image_name');
        nameEl.classList.remove('hidden');
        nameEl.querySelector('span').textContent = input.files[0].name;
    }
}

function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
        const nameEl = document.getElementById('hero_image_name');
        nameEl.classList.remove('hidden');
        nameEl.querySelector('span').textContent = input.files[0].name;
    }
}

function previewFounder(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('founderPreview');
            const placeholder = document.getElementById('founderPreviewPlaceholder');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
        const nameEl = document.getElementById('founder_photo_name');
        nameEl.classList.remove('hidden');
        nameEl.querySelector('span').textContent = input.files[0].name;
    }
}

// Toggle label update
document.getElementById('founderToggle').addEventListener('change', function() {
    document.getElementById('toggleLabel').textContent = this.checked ? 'Aktif' : 'Nonaktif';
});
</script>
@endpush

@endsection
