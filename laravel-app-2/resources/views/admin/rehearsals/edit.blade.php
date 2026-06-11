@extends('layouts.admin')

@section('title', 'Edit Jadwal Latihan – ART-HUB')
@section('page_title', 'Edit Jadwal Latihan')
@section('page_subtitle', 'Perbarui detail sesi latihan yang sudah dijadwalkan.')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<style>
    .leaflet-container img { max-width: none !important; max-height: none !important; width: auto !important; }
    .leaflet-container img.leaflet-tile { width: 256px !important; height: 256px !important; }
    .leaflet-container * { box-sizing: content-box !important; }
    .leaflet-container { box-sizing: border-box !important; border-radius: 0.75rem; font-size: 12px; }
    #mapContainer { width: 100%; height: 250px; position: relative; display: block; border-radius: 0.75rem; overflow: hidden; z-index: 1; }
    .map-search-wrapper { position: relative; z-index: 10; }
    .autocomplete-dropdown { position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid rgba(106,90,84,0.2); border-top: none; border-radius: 0 0 0.75rem 0.75rem; max-height: 200px; overflow-y: auto; box-shadow: 0 8px 24px rgba(0,0,0,0.1); display: none; z-index: 20; }
    .autocomplete-dropdown.active { display: block; }
    .autocomplete-item { padding: 0.65rem 1rem; cursor: pointer; border-bottom: 1px solid rgba(106,90,84,0.08); font-size: 0.8rem; color: #1a1a1a; }
    .autocomplete-item:hover { background-color: rgba(54,31,26,0.04); }
    .autocomplete-item:last-child { border-bottom: none; }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let map = null, marker = null;
    const API_URL = '{{ url("/api/geocoding") }}';
    const addressSearch   = document.getElementById('addressSearch');
    const dropdown        = document.getElementById('autocompleteDropdown');
    const venueInput      = document.getElementById('venueInput');
    const latitudeInput   = document.getElementById('latitudeInput');
    const longitudeInput  = document.getElementById('longitudeInput');
    const latitudeDisplay = document.getElementById('latitudeDisplay');
    const longitudeDisplay= document.getElementById('longitudeDisplay');

    const initialLat = {{ $rehearsal->latitude ?? -6.9175 }};
    const initialLng = {{ $rehearsal->longitude ?? 107.6062 }};

    function initMap(lat, lng, zoom = 15) {
        if (map) map.remove();
        map = L.map('mapContainer').setView([lat, lng], zoom);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors', maxZoom: 19, noWrap: true
        }).addTo(map);
        map.on('click', function(e) { updateMarker(e.latlng.lat, e.latlng.lng); reverseGeocode(e.latlng.lat, e.latlng.lng); });
        const resizeObserver = new ResizeObserver(() => { if (map) map.invalidateSize(true); });
        resizeObserver.observe(document.getElementById('mapContainer'));
    }

    function updateMarker(lat, lng) {
        if (marker) { marker.setLatLng([lat, lng]); }
        else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', function() {
                const ll = marker.getLatLng();
                updateCoordinates(ll.lat, ll.lng); reverseGeocode(ll.lat, ll.lng);
            });
        }
        map.panTo([lat, lng]); updateCoordinates(lat, lng);
    }

    function updateCoordinates(lat, lng) {
        latitudeInput.value = lat.toFixed(8); longitudeInput.value = lng.toFixed(8);
        latitudeDisplay.value = lat.toFixed(8); longitudeDisplay.value = lng.toFixed(8);
    }

    let debounceTimer;
    addressSearch.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(async () => {
            const query = this.value.trim();
            if (query.length < 3) { dropdown.classList.remove('active'); return; }
            try {
                const response = await fetch(`${API_URL}/autocomplete?q=${encodeURIComponent(query)}`);
                const results = await response.json();
                dropdown.innerHTML = '';
                if (results.length === 0) {
                    dropdown.innerHTML = '<div class="autocomplete-item text-gray-400 italic">Tidak ada hasil ditemukan</div>';
                } else {
                    results.forEach(r => {
                        const item = document.createElement('div');
                        item.className = 'autocomplete-item';
                        item.textContent = r.label;
                        item.addEventListener('click', () => selectLocation(r.value));
                        dropdown.appendChild(item);
                    });
                }
                dropdown.classList.add('active');
            } catch (e) { console.error(e); }
        }, 300);
    });

    document.addEventListener('click', e => { if (!e.target.closest('.map-search-wrapper')) dropdown.classList.remove('active'); });

    function selectLocation(result) {
        addressSearch.value = result.display_name; venueInput.value = result.display_name;
        dropdown.classList.remove('active'); updateMarker(result.lat, result.lon);
    }

    async function reverseGeocode(lat, lng) {
        try {
            const response = await fetch(`${API_URL}/reverse?latitude=${lat}&longitude=${lng}`);
            const data = await response.json();
            if (data.success && data.result) { venueInput.value = data.result.display_name; addressSearch.value = data.result.display_name; }
        } catch(e) {}
    }

    initMap(initialLat, initialLng);
    updateMarker(initialLat, initialLng);
});
</script>
@endpush

@section('content')

{{-- ── BACK BREADCRUMB ── --}}
<div class="flex items-center gap-2 mb-6 text-sm text-on-surface-variant">
    <a href="{{ route('admin.rehearsals.index') }}"
       class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-outline hover:text-primary transition-colors">
        <i class="bi bi-arrow-left-circle-fill text-base"></i> Kembali ke Daftar
    </a>
    <i class="bi bi-chevron-right text-[0.6rem] text-outline/50"></i>
    <span class="text-xs font-bold uppercase tracking-widest text-primary">Edit Jadwal</span>
</div>

{{-- ── VALIDATION ERRORS ── --}}
@if ($errors->any())
<div class="mb-6 p-4 rounded-2xl border border-red-400/60 bg-red-50 shadow-sm">
    <p class="font-bold text-sm text-red-800 mb-2 flex items-center gap-2"><i class="bi bi-x-circle-fill"></i> Ada kesalahan input:</p>
    <ul class="list-disc list-inside space-y-0.5">
        @foreach($errors->all() as $err)
        <li class="text-xs text-red-700">{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Info Latihan yang sedang diedit --}}
<div class="mb-5 p-4 rounded-xl bg-secondary-container/20 border border-secondary/20 flex items-center gap-3">
    <i class="bi bi-pencil-square text-secondary text-xl"></i>
    <div>
        <p class="font-label text-[0.65rem] uppercase tracking-widest text-on-surface-variant font-bold">Sedang Mengedit</p>
        <p class="font-body text-sm font-semibold text-on-surface">
            Latihan #{{ $rehearsal->id }} —
            {{ \Carbon\Carbon::parse($rehearsal->rehearsal_date)->translatedFormat('d F Y') }}
            ({{ strtoupper($rehearsal->type) }})
        </p>
    </div>
</div>

{{-- ── FORM CARD ── --}}
<div class="max-w-2xl mx-auto">
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden">

        {{-- Accent top bar --}}
        <div class="h-1 w-full bg-gradient-to-r from-secondary via-primary to-secondary-container"></div>

        {{-- Header --}}
        <div class="px-7 py-5 border-b border-outline-variant/20 bg-surface-container-low/50 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-secondary/10 flex items-center justify-center shadow-inner">
                <i class="bi bi-pencil-fill text-xl text-secondary"></i>
            </div>
            <div>
                <h2 class="font-headline font-bold text-primary text-base">Perbarui Jadwal Latihan</h2>
                <p class="font-label text-[0.65rem] uppercase tracking-widest text-outline mt-0.5">Ubah tanggal, jam, tipe, lokasi, atau catatan</p>
            </div>
        </div>

        {{-- FORM --}}
        <form action="{{ route('admin.rehearsals.update', $rehearsal) }}" method="POST" class="p-7 space-y-6">
            @csrf
            @method('PUT')

            {{-- ─ SECTION: Tipe + Tanggal ─ --}}
            <div>
                <h3 class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-3 flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full bg-secondary/10 flex items-center justify-center text-secondary text-[0.55rem]">1</span>
                    Detail Sesi
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Tipe --}}
                    <div>
                        <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Tipe Latihan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none">
                                <i class="bi bi-filter-square-fill text-secondary"></i>
                            </span>
                            <select name="type" required
                                    class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-9 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all appearance-none">
                                <option value="gabungan" {{ old('type', $rehearsal->type) == 'gabungan' ? 'selected' : '' }}>🎭 Gabungan (Tari + Musik)</option>
                                <option value="tari"     {{ old('type', $rehearsal->type) == 'tari'     ? 'selected' : '' }}>💃 Khusus Tari</option>
                                <option value="musik"    {{ old('type', $rehearsal->type) == 'musik'    ? 'selected' : '' }}>🎵 Khusus Musik</option>
                            </select>
                            <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-xs">
                                <i class="bi bi-chevron-down"></i>
                            </span>
                        </div>
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Tanggal Latihan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none">
                                <i class="bi bi-calendar-date text-primary"></i>
                            </span>
                            <input type="date" name="rehearsal_date"
                                   value="{{ old('rehearsal_date', $rehearsal->rehearsal_date) }}" required
                                   class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-4 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─ SECTION: Jam ─ --}}
            <div>
                <h3 class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-3 flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-600 text-[0.55rem]">2</span>
                    Waktu Sesi
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    {{-- Jam Mulai --}}
                    <div>
                        <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Jam Mulai <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none">
                                <i class="bi bi-clock-fill text-blue-500"></i>
                            </span>
                            <input type="time" name="start_time"
                                   value="{{ old('start_time', \Carbon\Carbon::parse($rehearsal->start_time)->format('H:i')) }}" required
                                   class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-4 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        </div>
                    </div>

                    {{-- Jam Selesai --}}
                    <div>
                        <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Jam Selesai <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none">
                                <i class="bi bi-clock-history text-outline"></i>
                            </span>
                            <input type="time" name="end_time"
                                   value="{{ old('end_time', \Carbon\Carbon::parse($rehearsal->end_time)->format('H:i')) }}" required
                                   class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-4 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─ SECTION: Lokasi + Catatan ─ --}}
            <div>
                <h3 class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-3 flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full bg-green-500/10 flex items-center justify-center text-green-600 text-[0.55rem]">3</span>
                    Lokasi & Catatan
                </h3>
                <div class="space-y-4">
                    {{-- Lokasi --}}
                    <div>
                        <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Lokasi Latihan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="bi bi-geo-alt-fill absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none text-green-600"></i>
                            <input type="text" name="location" id="venueInput"
                                   value="{{ old('location', $rehearsal->location) }}" required
                                   placeholder="Contoh: Pendopo Utama Sanggar"
                                   class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-4 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        </div>
                    </div>

                    {{-- Cari Alamat --}}
                    <div>
                        <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Cari Lokasi di Peta</label>
                        <div class="map-search-wrapper relative z-10">
                            <input type="text" id="addressSearch"
                                   value="{{ $rehearsal->location }}"
                                   placeholder="Ketik alamat atau nama tempat untuk mencari..."
                                   autocomplete="off"
                                   class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm px-4 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                            <div class="autocomplete-dropdown" id="autocompleteDropdown"></div>
                        </div>
                    </div>

                    <!-- Map Container -->
                    <div id="mapContainer" class="border border-outline-variant/30"></div>

                    <!-- Hidden fields for lat, lon -->
                    <input type="hidden" name="latitude" id="latitudeInput" value="{{ old('latitude', $rehearsal->latitude) }}">
                    <input type="hidden" name="longitude" id="longitudeInput" value="{{ old('longitude', $rehearsal->longitude) }}">

                    <!-- Coordinate Display -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Latitude</label>
                            <input type="text" id="latitudeDisplay" readonly
                                   value="{{ $rehearsal->latitude }}"
                                   class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-highest text-sm px-4 py-2 text-on-surface-variant outline-none"
                                   placeholder="Auto-filled">
                        </div>
                        <div>
                            <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Longitude</label>
                            <input type="text" id="longitudeDisplay" readonly
                                   value="{{ $rehearsal->longitude }}"
                                   class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-highest text-sm px-4 py-2 text-on-surface-variant outline-none"
                                   placeholder="Auto-filled">
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Catatan <span class="text-on-surface-variant/50 font-normal normal-case tracking-normal">Opsional</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3.5 text-outline text-sm pointer-events-none">
                                <i class="bi bi-chat-left-text"></i>
                            </span>
                            <textarea name="notes" rows="3"
                                      placeholder="Contoh: Bawa properti selendang masing-masing, hadir 15 menit lebih awal..."
                                      class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-4 py-2.5 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-none">{{ old('notes', $rehearsal->notes) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─ ACTION BUTTONS ─ --}}
            <div class="pt-4 mt-2 flex flex-col sm:flex-row justify-end gap-3 border-t border-outline-variant/20">
                <a href="{{ route('admin.rehearsals.index') }}"
                   class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-xl border border-outline-variant text-sm font-bold uppercase tracking-wider text-on-surface-variant hover:bg-surface-container hover:border-primary/30 transition-all">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 h-11 px-8 rounded-xl bg-gradient-to-r from-secondary-container to-secondary text-white text-sm font-bold uppercase tracking-wider hover:opacity-90 transition-all shadow-md">
                    <i class="bi bi-check-circle-fill"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
