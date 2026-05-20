@extends('layouts.admin')

@section('title', 'Tambah Katalog Jasa – ART-HUB')
@section('page_title', 'Tambah Katalog Jasa')
@section('page_subtitle', 'Tambahkan paket jasa baru untuk ditampilkan di landing page.')

@section('content')

@if($errors->any())
    <div class="p-4 mb-6 text-sm text-red-700 bg-red-500/10 border border-red-500/20 rounded-xl font-bold">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
    </div>
@endif

<form action="{{ route('admin.catalogs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div class="grid lg:grid-cols-3 gap-6">

        {{-- LEFT --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-low/50">
                    <div class="font-headline text-base text-primary font-bold flex items-center gap-2">
                        <i class="bi bi-info-circle-fill text-secondary"></i> Informasi Paket
                    </div>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Nama Paket <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    </div>
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-body text-sm font-bold text-on-surface-variant">Rp</span>
                            <input type="number" name="price" value="{{ old('price') }}" min="0" required
                                   class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl pl-10 pr-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Deskripsi Singkat <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="2" required
                                  class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors resize-none">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Detail Lengkap <span class="text-outline text-[0.6rem] font-normal normal-case">(popup modal)</span></label>
                        <textarea name="detail" rows="4"
                                  class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors resize-none">{{ old('detail') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Gambar --}}
            <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-low/50">
                    <div class="font-headline text-base text-primary font-bold flex items-center gap-2">
                        <i class="bi bi-image-fill text-secondary"></i> Gambar Katalog
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-4 items-start">
                        <div class="rounded-xl overflow-hidden border border-outline-variant/30 bg-surface-container aspect-video">
                            <div class="w-full h-full flex items-center justify-center text-outline/40" id="previewPlaceholder">
                                <div class="text-center">
                                    <i class="bi bi-image text-4xl block mb-2"></i>
                                    <p class="font-body text-xs">Belum ada gambar</p>
                                </div>
                            </div>
                            <img src="" class="w-full h-full object-cover hidden" id="previewImg" alt="Preview">
                        </div>
                        <label for="imageInput"
                               class="flex flex-col items-center justify-center gap-3 h-full min-h-[120px] border-2 border-dashed border-outline-variant/50 rounded-xl bg-surface-container hover:bg-surface-container-high hover:border-primary/40 transition-all cursor-pointer px-4 py-8 text-center">
                            <i class="bi bi-cloud-arrow-up-fill text-3xl text-outline"></i>
                            <div>
                                <p class="font-body text-sm font-bold text-on-surface">Unggah gambar</p>
                                <p class="font-body text-xs text-outline mt-0.5">JPG, PNG, WEBP — Maks. 2MB</p>
                            </div>
                            <input type="file" name="image" id="imageInput" class="hidden"
                                   accept="image/jpg,image/jpeg,image/png,image/webp"
                                   onchange="previewCatalogImage(this)">
                        </label>
                    </div>
                    <p id="imageName" class="font-body text-xs text-secondary mt-2 hidden"><i class="bi bi-check-circle-fill"></i> <span></span></p>
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="space-y-6">
            <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-low/50">
                    <div class="font-headline text-base text-primary font-bold flex items-center gap-2">
                        <i class="bi bi-sliders text-secondary"></i> Pengaturan
                    </div>
                </div>
                <div class="p-6 space-y-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold block mb-1">Tampilkan di Landing Page</label>
                            <p class="font-body text-xs text-outline">Nonaktifkan untuk menyembunyikan sementara.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer ml-4">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-surface-container-high peer-checked:bg-green-500 rounded-full peer after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                        </label>
                    </div>

                    {{-- BARU: Tipe Spesialisasi --}}
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Tipe Personel yang Dibutuhkan <span class="text-red-500">*</span></label>
                        <select name="specialty_type" required
                                class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                            <option value="penari"   {{ old('specialty_type') === 'penari'   ? 'selected' : '' }}>🎭 Penari saja</option>
                            <option value="pemusik"  {{ old('specialty_type') === 'pemusik'  ? 'selected' : '' }}>🎵 Pemusik saja</option>
                            <option value="gabungan" {{ old('specialty_type', 'gabungan') === 'gabungan' ? 'selected' : '' }}>🎭🎵 Gabungan (Penari + Pemusik)</option>
                        </select>
                        <p class="font-body text-xs text-outline mt-1">Dipakai sistem untuk menyaring personel saat plotting.</p>
                    </div>

                    {{-- BARU: Maks Personel --}}
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Maks. Personel <span class="text-outline text-[0.6rem] font-normal normal-case">(0 = tanpa batas)</span></label>
                        <input type="number" name="max_personnel" value="{{ old('max_personnel', 0) }}" min="0"
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                               placeholder="cth: 4">
                        <p class="font-body text-xs text-outline mt-1">Kuota maks personel yang bisa di-plot ke event ini. 0 = bebas.</p>
                    </div>

                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Label / Badge <span class="text-outline text-[0.6rem] font-normal normal-case">(opsional)</span></label>
                        <input type="text" name="badge" value="{{ old('badge') }}" maxlength="50"
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                               placeholder="cth: Favorit, Baru, Promo">
                    </div>
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Urutan Tampil</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        <p class="font-body text-xs text-outline mt-1">Angka lebih kecil tampil lebih dulu.</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-lg">
                    <i class="bi bi-save2-fill"></i> Tambah Katalog
                </button>
                <a href="{{ route('admin.catalogs.index') }}"
                   class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl border border-outline-variant/50 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-colors">
                    <i class="bi bi-arrow-left"></i> Batal
                </a>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function previewCatalogImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('previewImg');
            const ph  = document.getElementById('previewPlaceholder');
            img.src = e.target.result;
            img.classList.remove('hidden');
            if (ph) ph.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
        const nameEl = document.getElementById('imageName');
        nameEl.classList.remove('hidden');
        nameEl.querySelector('span').textContent = input.files[0].name;
    }
}
</script>
@endpush

@endsection
