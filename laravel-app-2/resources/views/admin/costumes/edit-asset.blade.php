@extends('layouts.admin')
@section('title', 'Edit Aset Kostum – ART-HUB')
@section('page_title', 'Edit Aset Sanggar')
@section('page_subtitle', 'Perbarui detail data inventaris aset sanggar.')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-6 md:p-8">

        <div class="flex items-center gap-3 mb-8 pb-4 border-b border-outline-variant/20">
            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                <i class="bi bi-pencil-square text-2xl"></i>
            </div>
            <div>
                <h2 class="font-headline text-xl text-primary font-bold">Edit Informasi Aset</h2>
                <p class="font-body text-xs text-on-surface-variant">Sesuaikan jumlah, kategori, atau kondisi saat ini dari aset sanggar.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.costumes.update-asset', $costume->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                {{-- Nama Kostum --}}
                <div class="md:col-span-2">
                    <label class="font-label text-sm font-bold text-on-surface block mb-1.5 ml-1">Nama Kostum / Alat</label>
                    <input type="text" name="name" placeholder="Contoh: Kostum Tari Merak Gold"
                           class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                           value="{{ old('name', $costume->name) }}" required>
                    @error('name') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="font-label text-sm font-bold text-on-surface block mb-1.5 ml-1">Kategori</label>
                    <select name="category" class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors appearance-none" required>
                        <option value="" disabled>Pilih Kategori</option>
                        <option value="Tradisional" {{ old('category', $costume->category) == 'Tradisional' ? 'selected' : '' }}>Tradisional</option>
                        <option value="Modern" {{ old('category', $costume->category) == 'Modern' ? 'selected' : '' }}>Modern</option>
                        <option value="Aksesoris" {{ old('category', $costume->category) == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                        <option value="Musik" {{ old('category', $costume->category) == 'Musik' ? 'selected' : '' }}>Alat Musik</option>
                    </select>
                </div>

                {{-- Jumlah --}}
                <div>
                    <label class="font-label text-sm font-bold text-on-surface block mb-1.5 ml-1">Jumlah (Qty)</label>
                    <input type="number" name="quantity" min="1" placeholder="0"
                           class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                           value="{{ old('quantity', $costume->quantity) }}" required>
                </div>

                {{-- Kondisi --}}
                <div class="md:col-span-2">
                    <label class="font-label text-sm font-bold text-on-surface block mb-1.5 ml-1">Kondisi Saat Ini</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="relative flex flex-col p-4 rounded-xl border border-outline-variant/50 cursor-pointer hover:bg-surface-container transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                            <input type="radio" name="condition" value="good" class="absolute top-3 right-3 w-4 h-4 text-primary" {{ old('condition', $costume->condition) == 'good' ? 'checked' : '' }}>
                            <span class="font-label text-xs font-bold text-green-600 mb-1 uppercase tracking-tighter">Bagus</span>
                            <span class="text-[10px] text-on-surface-variant">Siap digunakan</span>
                        </label>
                        <label class="relative flex flex-col p-4 rounded-xl border border-outline-variant/50 cursor-pointer hover:bg-surface-container transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                            <input type="radio" name="condition" value="damaged" class="absolute top-3 right-3 w-4 h-4 text-primary" {{ old('condition', $costume->condition) == 'damaged' ? 'checked' : '' }}>
                            <span class="font-label text-xs font-bold text-orange-600 mb-1 uppercase tracking-tighter">Rusak</span>
                            <span class="text-[10px] text-on-surface-variant">Perlu perbaikan</span>
                        </label>
                        <label class="relative flex flex-col p-4 rounded-xl border border-outline-variant/50 cursor-pointer hover:bg-surface-container transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                            <input type="radio" name="condition" value="maintenance" class="absolute top-3 right-3 w-4 h-4 text-primary" {{ old('condition', $costume->condition) == 'maintenance' ? 'checked' : '' }}>
                            <span class="font-label text-xs font-bold text-blue-600 mb-1 uppercase tracking-tighter">Rawat</span>
                            <span class="text-[10px] text-on-surface-variant">Sedang dicuci/servis</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-6 border-t border-outline-variant/20 flex items-center justify-between">
                <div>
                    {{-- Form Hapus (Delete) dengan konfirmasi --}}
                    <button type="button" onclick="confirmDelete()" class="px-5 py-2.5 rounded-xl border border-red-200 text-red-600 hover:bg-red-50 font-label text-xs font-bold uppercase tracking-widest transition-colors flex items-center gap-1.5">
                        <i class="bi bi-trash"></i> Hapus Aset
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.costumes.index') }}" class="px-5 py-2.5 rounded-xl border border-outline-variant/50 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-md flex items-center gap-2">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>

        {{-- Form Hapus Tersembunyi --}}
        <form id="delete-form" method="POST" action="{{ route('admin.costumes.destroy-asset', $costume->id) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus aset "{{ $costume->name }}" dari inventaris? Tindakan ini tidak dapat dibatalkan.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection
