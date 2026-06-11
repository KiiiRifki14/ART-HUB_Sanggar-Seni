@extends('layouts.klien')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<style>
    /* ============================================================
       FIX LEAFLET + TAILWIND CONFLICT
       Tailwind resets: img { max-width: 100% } dan box-sizing: border-box
       keduanya MERUSAK rendering tile Leaflet → tile berhamburan
    ============================================================ */
    .leaflet-container img {
        max-width: none !important;
        max-height: none !important;
        width: auto !important;
    }
    .leaflet-container img.leaflet-tile {
        width: 256px !important;
        height: 256px !important;
    }
    .leaflet-container * {
        box-sizing: content-box !important;
    }
    .leaflet-container {
        box-sizing: border-box !important;
        border-radius: 0.75rem;
        font-size: 12px;
    }

    /* Map container: ukuran tetap, tidak bisa meluber */
    #mapContainer {
        width: 100%;
        height: 300px;
        position: relative;
        display: block;
        border-radius: 0.75rem;
        border: 1px solid rgba(106, 90, 84, 0.2);
        overflow: hidden;
        z-index: 1;
    }

    /* Autocomplete */
    .map-search-wrapper {
        position: relative;
        z-index: 10;
    }
    .autocomplete-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid rgba(106, 90, 84, 0.2);
        border-top: none;
        border-radius: 0 0 0.75rem 0.75rem;
        max-height: 200px;
        overflow-y: auto;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
        display: none;
        z-index: 9999;
    }
    .autocomplete-dropdown.active {
        display: block;
    }
    .autocomplete-item {
        padding: 0.75rem 1rem;
        cursor: pointer;
        border-bottom: 1px solid rgba(106, 90, 84, 0.1);
        font-size: 0.875rem;
        color: #1a1a1a;
        transition: background-color 0.2s ease;
    }
    .autocomplete-item:hover {
        background-color: rgba(106, 90, 84, 0.05);
    }
    .autocomplete-item:last-child {
        border-bottom: none;
    }
</style>
@endpush

@section('title', 'Ajukan Pesanan Pementasan – ART-HUB Sanggar Cahaya Gumilang')

@section('content')

{{-- BREADCRUMB --}}
<div class="flex items-center gap-2 mb-6 font-label text-xs uppercase tracking-widest font-bold">
    <a href="{{ route('klien.dashboard') }}" class="text-on-surface-variant hover:text-secondary transition-colors flex items-center gap-1.5">
        <i class="bi bi-arrow-left"></i> Dashboard
    </a>
    <span class="text-outline-variant">/</span>
    <span class="text-outline">Pesan Pementasan Baru</span>
</div>

{{-- HEADER --}}
<div class="mb-8">
    <h1 class="font-headline text-3xl sm:text-4xl font-bold text-primary mb-2">Ajukan <span class="text-secondary">Pementasan Baru</span></h1>
    <p class="font-body text-sm text-on-surface-variant max-w-2xl">Isi formulir di bawah ini. Tim kami akan menghubungi Anda untuk konfirmasi harga final sebelum Anda mentransfer DP.</p>
</div>

<div class="flex flex-col lg:flex-row gap-6 items-start">

    {{-- KOLOM KIRI: FORM --}}
    <div class="flex-grow w-full">
        <form action="{{ route('klien.bookings.store') }}" method="POST" id="bookingForm" class="space-y-6">
            @csrf
            <input type="hidden" name="total_price" id="inputTotalPrice" value="0">

            {{-- PILIH PAKET --}}
            <div class="bg-surface-container-lowest rounded-xl p-6 sm:p-8 border border-outline-variant/30 shadow-[0_4px_12px_rgba(54,31,26,0.02)]">
                <div class="font-label text-xs uppercase tracking-widest text-secondary font-bold flex items-center gap-2 mb-6 pb-4 border-b border-outline-variant/30">
                    <i class="bi bi-grid-3x2-gap"></i> Pilih Paket Pementasan
                </div>

                @if($errors->has('service_catalog_id'))
                    <div class="text-red-500 text-xs mb-3 font-body">{{ $errors->first('service_catalog_id') }}</div>
                @endif

                @if($catalogs->isEmpty())
                    <div class="text-center py-10 text-on-surface-variant font-body text-sm">
                        <i class="bi bi-collection text-3xl block mb-2 text-outline"></i>
                        Belum ada paket jasa tersedia. Hubungi admin sanggar.
                    </div>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($catalogs as $catalog)
                    <div class="relative group">
                        <input type="radio" class="peer absolute opacity-0 w-0 h-0 k-pkg-radio"
                               name="service_catalog_id"
                               id="pkg_{{ $catalog->id }}"
                               value="{{ $catalog->id }}"
                               data-price="{{ $catalog->price }}"
                               data-name="{{ $catalog->name }}"
                               data-max="{{ $catalog->max_personnel }}"
                               data-specialty="{{ $catalog->specialty_label }}"
                               {{ old('service_catalog_id') == $catalog->id ? 'checked' : '' }}
                               required>
                        <label for="pkg_{{ $catalog->id }}" class="block p-5 rounded-xl border-2 border-surface-container-high bg-surface-container-low cursor-pointer transition-all peer-checked:border-secondary peer-checked:bg-secondary/5 hover:border-outline-variant relative">
                            @if($catalog->badge)
                                <span class="absolute top-3 right-3 px-2 py-0.5 rounded text-[0.6rem] font-bold uppercase tracking-wider bg-secondary-container text-on-secondary-container">{{ $catalog->badge }}</span>
                            @endif
                            <div class="font-headline font-bold text-lg text-primary mb-1 pr-16">{{ $catalog->name }}</div>
                            <div class="font-body font-bold text-secondary text-base mb-2">{{ $catalog->price_formatted }}</div>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-1 text-[0.6rem] font-label font-bold uppercase tracking-wider text-on-surface-variant bg-surface-container rounded px-2 py-1">
                                    <i class="bi bi-people-fill"></i>
                                    {{ $catalog->max_personnel > 0 ? $catalog->max_personnel . ' Personel' : 'Personel Bebas' }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-[0.6rem] font-label font-bold uppercase tracking-wider text-on-surface-variant bg-surface-container rounded px-2 py-1">
                                    <i class="bi bi-music-note-beamed"></i>
                                    {{ $catalog->specialty_label }}
                                </span>
                            </div>
                            <i class="bi bi-check-circle-fill absolute bottom-4 right-4 text-secondary opacity-0 scale-50 transition-all peer-checked:opacity-100 peer-checked:scale-100 text-xl"></i>
                        </label>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- WAKTU & TANGGAL --}}
            <div class="bg-surface-container-lowest rounded-xl p-6 sm:p-8 border border-outline-variant/30 shadow-[0_4px_12px_rgba(54,31,26,0.02)]">
                <div class="font-label text-xs uppercase tracking-widest text-secondary font-bold flex items-center gap-2 mb-6 pb-4 border-b border-outline-variant/30">
                    <i class="bi bi-calendar3"></i> Waktu Pelaksanaan
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Tanggal Acara <span class="text-red-500">*</span></label>
                        <input type="date" name="event_date"
                                class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('event_date') border-red-500 @enderror"
                               min="{{ now()->addDays(30)->toDateString() }}" required>
                        <div class="font-label text-[0.65rem] text-outline mt-1.5 flex items-center gap-1"><i class="bi bi-info-circle"></i> Min. H+30 dari hari ini</div>

                        @error('event_date')<div class="text-red-500 text-xs mt-1 font-body">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Jam Mulai <span class="text-red-500">*</span></label>
                        <input type="time" name="event_start"
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('event_start') border-red-500 @enderror" required>
                        @error('event_start')<div class="text-red-500 text-xs mt-1 font-body">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Jam Selesai <span class="text-red-500">*</span></label>
                        <input type="time" name="event_end"
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('event_end') border-red-500 @enderror" required>
                        @error('event_end')<div class="text-red-500 text-xs mt-1 font-body">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- LOKASI --}}
            <div class="bg-surface-container-lowest rounded-xl p-6 sm:p-8 border border-outline-variant/30 shadow-[0_4px_12px_rgba(54,31,26,0.02)]">
                <div class="font-label text-xs uppercase tracking-widest text-secondary font-bold flex items-center gap-2 mb-6 pb-4 border-b border-outline-variant/30">
                    <i class="bi bi-geo-alt"></i> Lokasi Pementasan
                </div>
                <div class="space-y-5">
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Nama Gedung / Venue <span class="text-red-500">*</span></label>
                        <input type="text" name="venue" id="venueInput" value="{{ old('venue') }}"
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('venue') border-red-500 @enderror"
                               placeholder="Contoh: Gedung Sate, Bandung" required>
                        @error('venue')<div class="text-red-500 text-xs mt-1 font-body">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Cari Alamat di Peta</label>
                        <div class="map-search-wrapper">
                            <input type="text" id="addressSearch" 
                                   placeholder="Ketik alamat atau nama tempat untuk mencari (min. 3 karakter)..."
                                   autocomplete="off"
                                   class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            <div class="autocomplete-dropdown" id="autocompleteDropdown"></div>
                        </div>
                    </div>

                    <!-- Map Container -->
                    <div id="mapContainer"></div>

                    <!-- Hidden fields for lat, lon -->
                    <input type="hidden" name="latitude" id="latitudeInput" value="{{ old('latitude') }}">
                    <input type="hidden" name="longitude" id="longitudeInput" value="{{ old('longitude') }}">

                    <!-- Coordinate Display -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1">Latitude</label>
                            <input type="text" id="latitudeDisplay" readonly
                                   class="w-full bg-surface-container-highest border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface-variant outline-none"
                                   placeholder="Otomatis terisi">
                        </div>
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1">Longitude</label>
                            <input type="text" id="longitudeDisplay" readonly
                                   class="w-full bg-surface-container-highest border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface-variant outline-none"
                                   placeholder="Otomatis terisi">
                        </div>
                    </div>

                    <p class="text-on-surface-variant text-xs mt-1.5 font-body">💡 Tip: Anda bisa mencari alamat di atas, memilih hasil pencarian, atau langsung mengklik dan menggeser marker di peta untuk menaruh lokasi presisi.</p>

                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea id="venueAddressInput" name="venue_address" class="w-full bg-surface-container-low border {{ $errors->has('venue_address') ? 'border-red-500' : 'border-outline-variant/50' }} rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" rows="3"
                            placeholder="Contoh: Jl. Diponegoro No. 22, Kel. Citarum, Kec. Bandung Wetan, Kota Bandung" required>{{ old('venue_address') }}</textarea>
                        <div class="font-label text-[0.65rem] text-outline mt-1.5 flex items-center gap-1"><i class="bi bi-info-circle"></i> Wajib diisi agar kru dapat menemukan lokasi acara dengan tepat</div>
                        @error('venue_address')<div class="text-red-500 text-xs mt-1 font-body">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- KONTAK --}}
            <div class="bg-surface-container-lowest rounded-xl p-6 sm:p-8 border border-outline-variant/30 shadow-[0_4px_12px_rgba(54,31,26,0.02)]">
                <div class="font-label text-xs uppercase tracking-widest text-secondary font-bold flex items-center gap-2 mb-6 pb-4 border-b border-outline-variant/30">
                    <i class="bi bi-phone"></i> Kontak WhatsApp
                </div>
                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Nomor WA Aktif <span class="text-red-500">*</span></label>
                    <div class="flex">
                        <span class="inline-flex items-center px-4 rounded-l-lg border border-r-0 border-outline-variant/50 bg-surface-container-highest text-on-surface-variant font-body text-sm font-semibold">
                            +62
                        </span>
                        @php
                            $userPhone = auth()->user()->phone ?? '';
                            // Bersihkan karakter non-digit untuk mempermudah deteksi kode negara
                            $userPhone = preg_replace('/[^0-9+]/', '', $userPhone);
                            if (str_starts_with($userPhone, '+62')) {
                                $userPhone = substr($userPhone, 3);
                            } elseif (str_starts_with($userPhone, '62')) {
                                $userPhone = substr($userPhone, 2);
                            } elseif (str_starts_with($userPhone, '0')) {
                                $userPhone = substr($userPhone, 1);
                            }
                        @endphp
                        <input type="text" name="client_phone"
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-r-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('client_phone') border-red-500 @enderror"
                               placeholder="81xxxxxxxxx" value="{{ old('client_phone', $userPhone) }}" required>
                    </div>
                    @error('client_phone')<div class="text-red-500 text-xs mt-1 font-body">{{ $message }}</div>@enderror
                </div>
            </div>

            <button type="submit" class="hidden lg:flex w-full items-center justify-center gap-2 bg-gradient-to-br from-primary-container to-primary text-white px-6 py-4 rounded-xl font-label text-sm font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-xl shadow-primary/20">
                <i class="bi bi-send-fill"></i> Ajukan Pesanan Pementasan
            </button>
        </form>
    </div>

    {{-- KOLOM KANAN: ESTIMASI STICKY --}}
    <div class="w-full lg:w-80 flex-shrink-0">
        <div class="bg-primary text-white rounded-xl shadow-xl overflow-hidden sticky top-24 border border-primary-container">
            <div class="bg-primary-container px-5 py-4 font-label text-xs uppercase tracking-widest font-bold flex items-center gap-2 border-b border-white/10">
                <i class="bi bi-calculator"></i> Estimasi Biaya
            </div>
            <div class="p-5">
                <div class="flex justify-between font-body text-sm text-white/70 mb-3">
                    <span>Paket dipilih</span>
                    <span id="previewPkgName" class="font-semibold text-secondary text-right max-w-[140px] leading-tight">—</span>
                </div>
                <div class="flex justify-between font-body text-sm text-white/70 mb-4">
                    <span>Harga Dasar</span>
                    <span id="previewBasePrice" class="font-semibold">Rp 0</span>
                </div>
                
                <hr class="border-white/10 my-4">
                
                <div class="text-center">
                    <div class="font-label text-[0.65rem] uppercase tracking-widest text-white/50 mb-2">Perkiraan Total Kontrak</div>
                    <div id="previewTotalPrice" class="font-headline text-3xl font-bold text-secondary mb-5">Rp 0</div>
                </div>
                
                <div class="bg-white/5 rounded-lg p-3 font-body text-xs text-white/60 flex items-start gap-2 leading-relaxed">
                    <i class="bi bi-info-circle text-secondary mt-0.5"></i>
                    Harga dapat bernegosiasi. Admin sanggar akan mengonfirmasi harga final via WhatsApp.
                </div>
            </div>

            {{-- Mobile Submit --}}
            <div class="p-5 bg-black/20 border-t border-white/5 lg:hidden">
                <button type="submit" form="bookingForm" class="w-full flex items-center justify-center gap-2 bg-secondary text-primary px-4 py-3 rounded-lg font-label text-xs font-bold uppercase tracking-widest hover:bg-secondary-container transition-all">
                    <i class="bi bi-send-fill"></i> Ajukan Pesanan
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
document.querySelectorAll('.k-pkg-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
        var price    = parseInt(this.getAttribute('data-price'));
        var name     = this.getAttribute('data-name') || this.value;
        var maxPers  = this.getAttribute('data-max');
        var specialty = this.getAttribute('data-specialty');

        document.getElementById('previewPkgName').innerText    = name;
        document.getElementById('previewBasePrice').innerText  = 'Rp ' + price.toLocaleString('id-ID');
        document.getElementById('previewTotalPrice').innerText = 'Rp ' + price.toLocaleString('id-ID');
        document.getElementById('inputTotalPrice').value       = price;

        var infoEl = document.getElementById('previewPkgInfo');
        if (infoEl) {
            var pers = maxPers > 0 ? maxPers + ' Personel' : 'Bebas';
            infoEl.innerText = specialty + ' · ' + pers;
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    let map = null;
    let marker = null;
    const API_URL = '{{ url("/api/geocoding") }}';
    
    const addressSearch = document.getElementById('addressSearch');
    const dropdown = document.getElementById('autocompleteDropdown');
    const venueInput = document.getElementById('venueInput');
    const venueAddressInput = document.getElementById('venueAddressInput');
    const latitudeInput = document.getElementById('latitudeInput');
    const longitudeInput = document.getElementById('longitudeInput');
    const latitudeDisplay = document.getElementById('latitudeDisplay');
    const longitudeDisplay = document.getElementById('longitudeDisplay');
    
    // Initialize map with default center (Indonesia center - Bandung/Tangerang area)
    function initMap(lat = -6.9175, lng = 107.6062, zoom = 13) {
        if (map) {
            map.remove();
        }
        
        map = L.map('mapContainer').setView([lat, lng], zoom);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19,
            noWrap: true
        }).addTo(map);
        
        // Allow clicking on map to set marker
        map.on('click', function(e) {
            const clickLat = e.latlng.lat;
            const clickLng = e.latlng.lng;
            updateMarker(clickLat, clickLng);
            reverseGeocode(clickLat, clickLng);
        });

        // Force Leaflet to recalculate container bounds after rendering starts
        setTimeout(() => {
            map.invalidateSize(true);
        }, 400);
    }
    
    // Update marker on map
    function updateMarker(lat, lng) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], {
                draggable: true
            }).addTo(map);
            
            marker.on('dragend', function() {
                const latLng = marker.getLatLng();
                updateCoordinates(latLng.lat, latLng.lng);
                reverseGeocode(latLng.lat, latLng.lng);
            });
        }
        
        map.panTo([lat, lng]);
        updateCoordinates(lat, lng);
    }
    
    // Update coordinate display and hidden fields
    function updateCoordinates(lat, lng) {
        latitudeInput.value = lat.toFixed(8);
        longitudeInput.value = lng.toFixed(8);
        latitudeDisplay.value = lat.toFixed(8);
        longitudeDisplay.value = lng.toFixed(8);
    }
    
    // Search addresses with autocomplete
    let debounceTimer;
    addressSearch.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value.trim();
        
        if (query.length < 3) {
            dropdown.classList.remove('active');
            dropdown.innerHTML = '';
            return;
        }
        
        debounceTimer = setTimeout(async () => {
            try {
                const response = await fetch(`${API_URL}/autocomplete?q=${encodeURIComponent(query)}`);
                const results = await response.json();
                
                dropdown.innerHTML = '';
                
                if (results.length === 0) {
                    dropdown.innerHTML = '<div class="autocomplete-item text-gray-500 text-center py-2">Tidak ada hasil ditemukan</div>';
                } else {
                    results.forEach(result => {
                        const item = document.createElement('div');
                        item.className = 'autocomplete-item';
                        item.textContent = result.label;
                        item.addEventListener('click', function() {
                            selectLocation(result.value);
                        });
                        dropdown.appendChild(item);
                    });
                }
                
                dropdown.classList.add('active');
            } catch (error) {
                console.error('Autocomplete error:', error);
                dropdown.innerHTML = '<div class="autocomplete-item text-red-500 text-center py-2">Error memuat hasil</div>';
            }
        }, 300);
    });
    
    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.map-search-wrapper')) {
            dropdown.classList.remove('active');
        }
    });
    
    // Select location from autocomplete
    function selectLocation(result) {
        addressSearch.value = result.display_name;
        venueAddressInput.value = result.display_name;
        dropdown.classList.remove('active');
        updateMarker(result.lat, result.lon);
    }
    
    // Reverse geocode coordinates to get address
    async function reverseGeocode(lat, lng) {
        try {
            const response = await fetch(`${API_URL}/reverse?latitude=${lat}&longitude=${lng}`);
            const data = await response.json();
            
            if (data.success && data.result) {
                venueAddressInput.value = data.result.display_name;
                addressSearch.value = data.result.display_name;
            }
        } catch (error) {
            console.error('Reverse geocode error:', error);
        }
    }
    
    // Initialize map after DOM is fully painted so Leaflet can measure container
    requestAnimationFrame(() => {
        setTimeout(() => {
            initMap();

            // If old data exists, restore it
            if (venueAddressInput.value) {
                addressSearch.value = venueAddressInput.value;
            }
            if (latitudeInput.value && longitudeInput.value) {
                const lat = parseFloat(latitudeInput.value);
                const lng = parseFloat(longitudeInput.value);
                updateMarker(lat, lng);
            }
        }, 100);
    });
});
</script>
@endpush

@endsection
