@extends('layouts.admin')
@section('title', 'Tambah Aset Kostum – ART-HUB')
@section('page_title', 'Tambah Aset Baru')
@section('page_subtitle', 'Masukkan kostum atau perlengkapan baru ke dalam inventaris sanggar.')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-sm p-5 md:p-6">

        <div class="flex items-center gap-3 mb-5 pb-3 border-b border-outline-variant/20">
            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                <i class="bi bi-box-seam text-lg"></i>
            </div>
            <div>
                <h2 class="font-headline text-base text-primary font-bold">Informasi Aset</h2>
                <p class="font-body text-[0.7rem] text-on-surface-variant">Pastikan data jumlah dan kondisi sesuai dengan fisik barang.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.costumes.store-asset') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                {{-- Nama Kostum --}}
                <div class="md:col-span-2">
                    <label class="font-label text-xs font-bold text-on-surface block mb-1 ml-1">Nama Kostum / Alat</label>
                    <input type="text" name="name" placeholder="Contoh: Kostum Tari Merak Gold"
                           class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-3 py-2 font-body text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                           value="{{ old('name') }}" required>
                    @error('name') <p class="text-red-500 text-[0.68rem] mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="font-label text-xs font-bold text-on-surface block mb-1 ml-1">Kategori</label>
                    <select name="category" class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-3 py-2 font-body text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors appearance-none" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        <option value="atasan">Atasan</option>
                        <option value="bawahan">Bawahan</option>
                        <option value="aksesoris">Aksesoris</option>
                        <option value="alat_musik">Alat Musik</option>
                        <option value="properti">Properti</option>
                        <option value="tradisional">Tradisional</option>
                        <option value="modern">Modern</option>
                    </select>
                </div>

                {{-- Jumlah --}}
                <div>
                    <label class="font-label text-xs font-bold text-on-surface block mb-1 ml-1">Jumlah (Qty)</label>
                    <input type="number" name="quantity" min="1" placeholder="0"
                           class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-3 py-2 font-body text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                           value="{{ old('quantity', 1) }}" required>
                </div>

                {{-- Kondisi --}}
                <div class="md:col-span-2">
                    <label class="font-label text-xs font-bold text-on-surface block mb-1 ml-1">Kondisi Awal</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="relative flex flex-col p-3 rounded-lg border border-outline-variant/50 cursor-pointer hover:bg-surface-container transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                            <input type="radio" name="condition" value="good" class="absolute top-2 right-2 w-3.5 h-3.5 text-primary" checked>
                            <span class="font-label text-[0.65rem] font-bold text-green-600 mb-0.5 uppercase tracking-tighter">Bagus</span>
                            <span class="text-[9px] text-on-surface-variant leading-none">Siap digunakan</span>
                        </label>
                        <label class="relative flex flex-col p-3 rounded-lg border border-outline-variant/50 cursor-pointer hover:bg-surface-container transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                            <input type="radio" name="condition" value="damaged" class="absolute top-2 right-2 w-3.5 h-3.5 text-primary">
                            <span class="font-label text-[0.65rem] font-bold text-orange-600 mb-0.5 uppercase tracking-tighter">Rusak</span>
                            <span class="text-[9px] text-on-surface-variant leading-none">Perlu perbaikan</span>
                        </label>
                        <label class="relative flex flex-col p-3 rounded-lg border border-outline-variant/50 cursor-pointer hover:bg-surface-container transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                            <input type="radio" name="condition" value="maintenance" class="absolute top-2 right-2 w-3.5 h-3.5 text-primary">
                            <span class="font-label text-[0.65rem] font-bold text-blue-600 mb-0.5 uppercase tracking-tighter">Rawat</span>
                            <span class="text-[9px] text-on-surface-variant leading-none">Dicuci / servis</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-4.5 border-t border-outline-variant/20 flex items-center justify-end gap-2.5">
                <a href="{{ route('admin.costumes.index') }}" class="px-4 py-2 rounded-lg border border-outline-variant/50 font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-white font-label text-[0.65rem] font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-md flex items-center gap-1.5">
                    <i class="bi bi-cloud-arrow-up"></i> Simpan Aset
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
