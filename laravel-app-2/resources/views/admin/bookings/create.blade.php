@extends('layouts.admin')

@section('title', 'New Booking – ART-HUB')
@section('page_title', 'New Booking Entry')
@section('page_subtitle', 'Input booking manual dari klien offline / WhatsApp.')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<style>
    .leaflet-container img { max-width: none !important; max-height: none !important; width: auto !important; }
    .leaflet-container img.leaflet-tile { width: 256px !important; height: 256px !important; }
    .leaflet-container * { box-sizing: content-box !important; }
    .leaflet-container { box-sizing: border-box !important; border-radius: 0.75rem; font-size: 12px; }
    #mapContainer { width: 100%; height: 300px; position: relative; display: block; border-radius: 0.75rem; overflow: hidden; z-index: 1; }
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
@endpush

@section('content')

{{-- Progress Steps --}}
<div class="flex items-center justify-center gap-0 mb-8">
    @foreach([['lucide-user','Data Klien'],['lucide-calendar','Detail Event'],['lucide-wallet','Kontrak']] as $i => [$icon, $label])
    <div class="flex items-center {{ $i > 0 ? '' : '' }}">
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 rounded-full {{ $i === 0 ? 'bg-gradient-to-br from-[#fcd400] to-[#C5A028] text-[#1A1817] shadow-lg' : 'bg-[#fff] border border-[#d4af37] text-[#C5A028] shadow-sm' }} flex items-center justify-center font-bold transition-all">
                <i data-lucide="{{ str_replace('lucide-', '', $icon) }}" class="w-5 h-5"></i>
            </div>
            <span class="font-label text-[0.65rem] uppercase tracking-widest mt-2 {{ $i === 0 ? 'text-[#8B1A2A] font-bold' : 'text-[#847B78]' }}">{{ $label }}</span>
        </div>
        @if($i < 2)
        <div class="w-24 h-px mx-2 mb-6" style="background:linear-gradient(to right, rgba(197,160,40,0.5), transparent);"></div>
        @endif
    </div>
    @endforeach
</div>

<div class="flex justify-center">
    <div class="w-full max-w-3xl">

        <form method="POST" action="{{ route('admin.bookings.manual.store') }}" class="space-y-5" id="bookingForm">
            @csrf

            {{-- ══ SECTION: Data Klien ══ --}}
            <div class="card-gold overflow-hidden">
                <div class="px-6 py-4 border-b flex items-center gap-3" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.02);">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(139,26,42,0.1);">
                        <i data-lucide="user" class="w-5 h-5" style="color:#8B1A2A;"></i>
                    </div>
                    <div>
                        <div class="title-gold" style="font-size:1.1rem;">Data Klien</div>
                        <p class="subtitle-gold" style="font-size:0.65rem;">Informasi identitas pemesan</p>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block subtitle-gold mb-2">Nama Klien <span class="text-red-500">*</span></label>
                            <input type="text" name="client_name" value="{{ old('client_name') }}" required
                                   class="input-gold @error('client_name') border-red-500 @enderror"
                                   placeholder="Bpk./Ibu Siapa">
                            @error('client_name') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block subtitle-gold mb-2">No. Telepon / WA <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 font-bold" style="background:rgba(197,160,40,0.1); border-color:rgba(197,160,40,0.4); color:#8B1A2A;">+62</span>
                                <input type="text" name="client_phone" value="{{ old('client_phone') }}" required
                                       class="w-full bg-white border rounded-r-xl px-4 py-3 focus:outline-none focus:border-yellow-600 focus:ring-1 focus:ring-yellow-600 transition-all font-body text-sm @error('client_phone') border-red-500 @enderror"
                                       style="border-color:rgba(197,160,40,0.4); color:#1A1817;"
                                       placeholder="81xxxxxxxxx">
                            </div>
                            @error('client_phone') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ SECTION: Detail Event ══ --}}
            <div class="card-gold overflow-hidden">
                <div class="px-6 py-4 border-b flex items-center gap-3" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.02);">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(197,160,40,0.1);">
                        <i data-lucide="calendar" class="w-5 h-5 text-yellow-600"></i>
                    </div>
                    <div>
                        <div class="title-gold" style="font-size:1.1rem;">Detail Event</div>
                        <p class="subtitle-gold" style="font-size:0.65rem;">Jenis, tanggal, waktu & lokasi pementasan</p>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block subtitle-gold mb-2">Jenis Event <span class="text-red-500">*</span></label>
                            <select name="event_type" required
                                    class="input-gold appearance-none @error('event_type') border-red-500 @enderror">
                                <option value="">— Pilih Jenis —</option>
                                @foreach(['jaipong'=>'Jaipong','degung'=>'Degung','rampak_gendang'=>'Rampak Gendang','wayang_golek'=>'Wayang Golek','campuran'=>'Campuran'] as $k => $v)
                                    <option value="{{ $k }}" {{ old('event_type') === $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                            @error('event_type') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block subtitle-gold mb-2">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                            <input type="date" name="event_date" value="{{ old('event_date') }}" required
                                   class="input-gold @error('event_date') border-red-500 @enderror">
                            @error('event_date') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block subtitle-gold mb-2">Jam Mulai <span class="text-red-500">*</span></label>
                            <input type="time" name="event_start" value="{{ old('event_start') }}" required
                                   class="input-gold @error('event_start') border-red-500 @enderror">
                            @error('event_start') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block subtitle-gold mb-2">Jam Selesai <span class="text-red-500">*</span></label>
                            <input type="time" name="event_end" value="{{ old('event_end') }}" required
                                   class="input-gold @error('event_end') border-red-500 @enderror">
                            @error('event_end') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Venue / Peta --}}
                    <div>
                        <label class="block subtitle-gold mb-2">Venue / Lokasi Acara <span class="text-red-500">*</span></label>
                        <div class="map-search-wrapper mb-3">
                            <div class="relative">
                                <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                                <input type="text" id="addressSearch"
                                       placeholder="Ketik alamat atau nama tempat (min. 3 karakter)..."
                                       autocomplete="off"
                                       class="input-gold" style="padding-left:40px;">
                            </div>
                            <div class="autocomplete-dropdown" id="autocompleteDropdown"></div>
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" name="venue" id="venueInput" value="{{ old('venue') }}">
                        <input type="hidden" name="latitude" id="latitudeInput" value="{{ old('latitude') }}">
                        <input type="hidden" name="longitude" id="longitudeInput" value="{{ old('longitude') }}">

                        <!-- Map Container -->
                        <div id="mapContainer" style="border:1px solid rgba(197,160,40,0.4);"></div>

                        <!-- Coordinates -->
                        <div class="mt-3 grid grid-cols-2 gap-3">
                            <div>
                                <label class="block subtitle-gold mb-1" style="font-size:0.6rem;">Latitude</label>
                                <input type="text" id="latitudeDisplay" readonly
                                       class="input-gold bg-gray-50" style="color:#847B78;"
                                       placeholder="Auto-filled">
                            </div>
                            <div>
                                <label class="block subtitle-gold mb-1" style="font-size:0.6rem;">Longitude</label>
                                <input type="text" id="longitudeDisplay" readonly
                                       class="input-gold bg-gray-50" style="color:#847B78;"
                                       placeholder="Auto-filled">
                            </div>
                        </div>

                        <div class="flex items-start gap-2 mt-2.5 p-3 rounded-xl" style="background:rgba(197,160,40,0.05); border:1px solid rgba(197,160,40,0.2);">
                            <i data-lucide="lightbulb" class="w-4 h-4 text-yellow-600 flex-shrink-0 mt-0.5"></i>
                            <p class="font-body text-xs" style="color:#504442;">Cari alamat di atas, pilih dari hasil, dan klik peta untuk menyesuaikan lokasi tepat.</p>
                        </div>

                        @error('venue') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- ══ SECTION: Nilai Kontrak ══ --}}
            <div class="card-gold overflow-hidden">
                <div class="px-6 py-4 border-b flex items-center gap-3" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.02);">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(22,163,74,0.1);">
                        <i data-lucide="wallet" class="w-5 h-5 text-green-600"></i>
                    </div>
                    <div>
                        <div class="title-gold" style="font-size:1.1rem;">Nilai Kontrak</div>
                        <p class="subtitle-gold" style="font-size:0.65rem;">Total harga deal & jumlah uang muka (DP)</p>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block subtitle-gold mb-2">Total Harga Deal (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center font-bold text-sm" style="color:#1A1817; font-family:'Inter',sans-serif;">Rp</span>
                                <input type="number" name="total_price" value="{{ old('total_price') }}" required
                                       class="input-gold" style="padding-left:40px; font-weight:700; color:#1A1817;"
                                       placeholder="15000000" id="totalPriceInput" oninput="calcSisa()">
                            </div>
                            @error('total_price') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block subtitle-gold mb-2">Jumlah DP Masuk (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center font-bold text-sm" style="color:#16a34a; font-family:'Inter',sans-serif;">Rp</span>
                                <input type="number" name="dp_amount" value="{{ old('dp_amount') }}" required
                                       class="input-gold" style="padding-left:40px; font-weight:700; color:#16a34a;"
                                       placeholder="7500000" id="dpAmountInput" oninput="calcSisa()">
                            </div>
                            @error('dp_amount') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    {{-- Sisa Pelunasan Preview --}}
                    <div id="sisaPreview" class="mt-4 p-4 rounded-xl hidden" style="background:rgba(139,26,42,0.03); border:1px solid rgba(139,26,42,0.1);">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="subtitle-gold" style="font-size:0.6rem;">Sisa Pelunasan</p>
                                <p id="sisaAmount" class="title-gold mt-1" style="font-size:1.4rem; color:#8B1A2A;">Rp 0</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background:rgba(139,26,42,0.1);">
                                <i data-lucide="coins" class="w-6 h-6" style="color:#8B1A2A;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ ACTIONS ══ --}}
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.bookings.index') }}" class="arh-btn-secondary px-6 py-2.5">
                    <i data-lucide="x" class="w-4 h-4 mr-2 inline-block"></i> Batal
                </a>
                <button type="submit" class="arh-btn-primary px-8 py-2.5" style="background:linear-gradient(135deg, #fcd400, #C5A028); color:#1A1817; border:none;">
                    <i data-lucide="save" class="w-4 h-4 mr-2 inline-block"></i> Simpan Booking
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Hitung sisa
function calcSisa() {
    const total = parseInt(document.getElementById('totalPriceInput').value) || 0;
    const dp    = parseInt(document.getElementById('dpAmountInput').value) || 0;
    const sisa  = total - dp;
    const preview = document.getElementById('sisaPreview');
    if (total > 0 && dp > 0) {
        preview.classList.remove('hidden');
        document.getElementById('sisaAmount').textContent = 'Rp ' + sisa.toLocaleString('id-ID');
    } else {
        preview.classList.add('hidden');
    }
}

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

    function initMap(lat = -6.9175, lng = 107.6062, zoom = 13) {
        if (map) map.remove();
        map = L.map('mapContainer').setView([lat, lng], zoom);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors', maxZoom: 19, noWrap: true
        }).addTo(map);
        map.on('click', function(e) { updateMarker(e.latlng.lat, e.latlng.lng); reverseGeocode(e.latlng.lat, e.latlng.lng); });
        
        // Reset size peta secara paksa agar terhindar dari bug rendering abu-abu setengah layar
        setTimeout(() => {
            map.invalidateSize(true);
        }, 300);
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

    initMap();
    if (venueInput.value) addressSearch.value = venueInput.value;
    if (latitudeInput.value && longitudeInput.value) updateMarker(parseFloat(latitudeInput.value), parseFloat(longitudeInput.value));
    calcSisa();
});
</script>
@endpush

@endsection
