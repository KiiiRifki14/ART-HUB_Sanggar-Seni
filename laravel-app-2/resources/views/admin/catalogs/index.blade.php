@extends('layouts.admin')

@section('title', 'Katalog Jasa – ART-HUB')
@section('page_title', 'Katalog Jasa')
@section('page_subtitle', 'Kelola paket jasa yang tampil di landing page website.')

@section('content')

{{-- ALERT --}}
@if(session('success'))
    <div class="p-4 mb-6 text-sm text-green-700 bg-green-500/10 border border-green-500/20 rounded-xl flex items-center gap-2 font-bold">
        <i class="bi bi-check-circle-fill text-green-500"></i> {{ session('success') }}
    </div>
@endif

{{-- HEADER BAR --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="font-body text-sm text-on-surface-variant">Total <span class="font-bold text-primary">{{ $catalogs->count() }}</span> paket jasa terdaftar.</p>
    </div>
    <a href="{{ route('admin.catalogs.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-md">
        <i class="bi bi-plus-lg"></i> Tambah Katalog
    </a>
</div>

{{-- TABLE (Desktop) --}}
<div class="hidden md:block bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm overflow-hidden">
    @if($catalogs->isEmpty())
        <div class="text-center py-20 text-on-surface-variant">
            <i class="bi bi-collection text-5xl mb-4 block text-outline/50"></i>
            <p class="font-headline text-lg font-semibold text-primary mb-2">Belum ada katalog jasa</p>
            <p class="font-body text-sm mb-6">Tambahkan paket jasa pertama untuk ditampilkan di landing page.</p>
            <a href="{{ route('admin.catalogs.create') }}"
               class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors">
                <i class="bi bi-plus-lg"></i> Tambah Sekarang
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-outline-variant/20 bg-surface-container-low/60">
                        <th class="text-left px-5 py-3.5 font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">#</th>
                        <th class="text-left px-5 py-3.5 font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Gambar</th>
                        <th class="text-left px-5 py-3.5 font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Nama Paket</th>
                        <th class="text-left px-5 py-3.5 font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Harga</th>
                        <th class="text-left px-5 py-3.5 font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Badge</th>
                        <th class="text-center px-5 py-3.5 font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Tampil</th>
                        <th class="text-center px-5 py-3.5 font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @foreach($catalogs as $catalog)
                    <tr class="hover:bg-surface-container/50 transition-colors" id="catalog-row-{{ $catalog->id }}">
                        <td class="px-5 py-4 font-label text-xs text-outline font-bold">{{ $catalog->sort_order ?: $loop->iteration }}</td>
                        <td class="px-5 py-4">
                            <div class="w-16 h-12 rounded-lg overflow-hidden bg-surface-container flex-shrink-0">
                                @if($catalog->image)
                                    <img src="{{ asset('storage/' . $catalog->image) }}"
                                         class="w-full h-full object-cover" alt="{{ $catalog->name }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-outline/40">
                                        <i class="bi bi-image text-xl"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-headline font-bold text-primary text-sm">{{ $catalog->name }}</p>
                            <p class="font-body text-xs text-on-surface-variant mt-0.5 max-w-xs truncate">{{ $catalog->description }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-headline font-bold text-sm text-primary">{{ $catalog->price_formatted }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @if($catalog->badge)
                                <span class="px-2.5 py-1 rounded-full bg-secondary-container/60 text-secondary font-label text-[0.6rem] font-bold uppercase tracking-widest">
                                    {{ $catalog->badge }}
                                </span>
                            @else
                                <span class="text-outline/40 font-body text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            {{-- Toggle Switch --}}
                            <button type="button"
                                    onclick="toggleCatalog(this)"
                                    data-url="{{ route('admin.catalogs.toggle', $catalog->id) }}"
                                    data-active="{{ $catalog->is_active ? '1' : '0' }}"
                                    data-id="{{ $catalog->id }}"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none
                                           {{ $catalog->is_active ? 'bg-green-500' : 'bg-surface-container-high' }}"
                                    title="{{ $catalog->is_active ? 'Klik untuk sembunyikan' : 'Klik untuk tampilkan' }}">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform
                                             {{ $catalog->is_active ? 'translate-x-6' : 'translate-x-1' }}"
                                      id="toggle-dot-{{ $catalog->id }}"></span>
                            </button>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.catalogs.edit', $catalog) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-outline-variant/50 font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container hover:border-primary/30 hover:text-primary transition-colors">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </a>
                                <form action="{{ route('admin.catalogs.destroy', $catalog) }}" method="POST"
                                      onsubmit="return confirm('Hapus katalog \'{{ addslashes($catalog->name) }}\'? Gambar juga akan ikut terhapus.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-red-200 font-label text-[0.65rem] font-bold uppercase tracking-widest text-red-500 hover:bg-red-50 hover:border-red-400 transition-colors">
                                        <i class="bi bi-trash3-fill"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- MOBILE CARDS (Mobile only) --}}
@if(!$catalogs->isEmpty())
<div class="md:hidden space-y-3 mb-4 mt-0">
    @foreach($catalogs as $catalog)
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-4 py-3 bg-surface-container-low border-b border-outline-variant/20">
            <div class="w-14 h-10 rounded-lg overflow-hidden bg-surface-container flex-shrink-0">
                @if($catalog->image)
                    <img src="{{ asset('storage/' . $catalog->image) }}" class="w-full h-full object-cover" alt="{{ $catalog->name }}">
                @else
                    <div class="w-full h-full flex items-center justify-center text-outline/40"><i class="bi bi-image text-lg"></i></div>
                @endif
            </div>
            <div class="flex-grow min-w-0">
                <p class="font-headline font-bold text-primary text-sm truncate">{{ $catalog->name }}</p>
                <p class="font-headline font-bold text-secondary text-sm">{{ $catalog->price_formatted }}</p>
            </div>
            <button type="button" onclick="toggleCatalog(this)"
                    data-url="{{ route('admin.catalogs.toggle', $catalog->id) }}"
                    data-active="{{ $catalog->is_active ? '1' : '0' }}"
                    data-id="{{ $catalog->id }}"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors flex-shrink-0 {{ $catalog->is_active ? 'bg-green-500' : 'bg-surface-container-high' }}">
                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform {{ $catalog->is_active ? 'translate-x-6' : 'translate-x-1' }}" id="toggle-dot-{{ $catalog->id }}"></span>
            </button>
        </div>
        <div class="px-4 py-2.5 flex items-center justify-between">
            <div class="flex items-center gap-1.5">
                @if($catalog->badge)
                <span class="px-2 py-0.5 rounded-full bg-secondary-container/60 text-secondary font-label text-[0.6rem] font-bold uppercase tracking-widest">{{ $catalog->badge }}</span>
                @endif
                <span class="font-label text-[0.6rem] text-outline uppercase tracking-widest">Sort: {{ $catalog->sort_order ?: $loop->iteration }}</span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.catalogs.edit', $catalog) }}" class="h-8 px-3 inline-flex items-center gap-1 rounded-lg border border-outline-variant/50 font-label text-[0.6rem] font-bold uppercase tracking-wider text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors"><i class="bi bi-pencil-fill"></i> Edit</a>
                <form action="{{ route('admin.catalogs.destroy', $catalog) }}" method="POST" onsubmit="return confirm('Hapus katalog \'{{ addslashes($catalog->name) }}\'?')">@csrf @method('DELETE')
                    <button type="submit" class="h-8 px-3 inline-flex items-center gap-1 rounded-lg border border-red-200 font-label text-[0.6rem] font-bold uppercase tracking-wider text-red-500 hover:bg-red-50 transition-colors"><i class="bi bi-trash3-fill"></i></button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

<div class="mt-4 flex items-start gap-2 text-xs text-on-surface-variant font-body">
    <i class="bi bi-info-circle-fill text-secondary flex-shrink-0 mt-0.5"></i>
    <p>Katalog yang ditampilkan di landing page hanya yang <strong>status tampilnya aktif (hijau)</strong>. Urutan tampil diatur via kolom <strong>#</strong> (sort order) di form edit.</p>
</div>

@push('scripts')
<script>
function toggleCatalog(btn) {
    const isActive = btn.dataset.active === '1';
    const url = btn.dataset.url;
    const id = btn.dataset.id;

    // Tambahkan efek loading sementara
    btn.style.opacity = '0.5';
    btn.style.pointerEvents = 'none';

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ _method: 'PATCH' }) // Gunakan method spoofing Laravel
    })
    .then(async r => {
        if (!r.ok) {
            const err = await r.text();
            throw new Error('Network response was not ok');
        }
        return r.json();
    })
    .then(data => {
        if (data.success) {
            const newActive = data.is_active;
            btn.dataset.active = newActive ? '1' : '0';
            btn.classList.toggle('bg-green-500', newActive);
            btn.classList.toggle('bg-surface-container-high', !newActive);
            const dot = document.getElementById(`toggle-dot-${id}`);
            dot.classList.toggle('translate-x-6', newActive);
            dot.classList.toggle('translate-x-1', !newActive);
            btn.title = newActive ? 'Klik untuk sembunyikan' : 'Klik untuk tampilkan';
        }
    })
    .catch((e) => {
        console.error(e);
        alert('Gagal memperbarui status. Pastikan koneksi stabil dan coba lagi.');
    })
    .finally(() => {
        btn.style.opacity = '1';
        btn.style.pointerEvents = 'auto';
    });
}
</script>
@endpush

@endsection
