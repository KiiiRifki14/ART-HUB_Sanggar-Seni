@extends('layouts.admin')

@section('title', 'New Booking – ART-HUB')
@section('page_title', 'New Booking Entry')
@section('page_subtitle', 'Input booking manual dari klien offline / WhatsApp.')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<style>
    #mapContainer {
        width: 100%;
        height: 400px;
        border-radius: 0.5rem;
        border: 1px solid rgba(106, 90, 84, 0.2);
        z-index: 1;
    }
    .leaflet-container {
        border-radius: 0.5rem;
    }
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
        border-radius: 0 0 0.5rem 0.5rem;
        max-height: 200px;
        overflow-y: auto;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: none;
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
    }
    .autocomplete-item:hover {
        background-color: rgba(101, 75, 192, 0.05);
    }
    .autocomplete-item:last-child {
        border-bottom: none;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
@endpush

@section('content')
<div class="flex justify-center">
    <div class="w-full lg:w-8/12">
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] p-6 sm:p-8">
            <h2 class="font-headline text-xl text-primary font-semibold mb-6 flex items-center gap-2 border-b border-outline-variant/30 pb-4">
                <i class="bi bi-file-earmark-plus-fill text-secondary"></i> Form Booking Manual
            </h2>

            <form method="POST" action="{{ route('admin.bookings.manual.store') }}" class="space-y-8">
                @csrf

                {{-- Data Klien --}}
                <div class="space-y-4">
                    <h3 class="font-label text-xs uppercase tracking-widest text-secondary font-bold flex items-center gap-2">
                        <i class="bi bi-person-circle"></i> Data Klien
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Nama Klien <span class="text-red-500">*</span></label>
                            <input type="text" name="client_name" value="{{ old('client_name') }}" required
                                   class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('client_name') border-red-500 @enderror"
                                   placeholder="Bpk./Ibu Siapa">
                            @error('client_name') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">No. Telepon / WA <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <span class="inline-flex items-center px-4 rounded-l-lg border border-r-0 border-outline-variant/50 bg-surface-container-highest text-on-surface-variant font-body text-sm font-semibold">
                                    +62
                                </span>
                                <input type="text" name="client_phone" value="{{ old('client_phone') }}" required
                                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-r-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('client_phone') border-red-500 @enderror"
                                       placeholder="81xxxxxxxxx">
                            </div>
                            @error('client_phone') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Detail Event --}}
                <div class="space-y-4">
                    <h3 class="font-label text-xs uppercase tracking-widest text-secondary font-bold flex items-center gap-2">
                        <i class="bi bi-calendar-event"></i> Detail Event
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Jenis Event <span class="text-red-500">*</span></label>
                            <select name="event_type" required
                                    class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all appearance-none @error('event_type') border-red-500 @enderror">
                                <option value="">— Pilih Jenis —</option>
                                @foreach(['jaipong'=>'Jaipong','degung'=>'Degung','rampak_gendang'=>'Rampak Gendang','wayang_golek'=>'Wayang Golek','campuran'=>'Campuran'] as $k => $v)
                                    <option value="{{ $k }}" {{ old('event_type') === $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                            @error('event_type') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                            <input type="date" name="event_date" value="{{ old('event_date') }}" required
                                   class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('event_date') border-red-500 @enderror">
                            @error('event_date') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Jam Mulai <span class="text-red-500">*</span></label>
                            <input type="time" name="event_start" value="{{ old('event_start') }}" required
                                   class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('event_start') border-red-500 @enderror">
                            @error('event_start') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Jam Selesai <span class="text-red-500">*</span></label>
                            <input type="time" name="event_end" value="{{ old('event_end') }}" required
                                   class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('event_end') border-red-500 @enderror">
                            @error('event_end') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Venue / Lokasi Acara <span class="text-red-500">*</span></label>
                            <div class="map-search-wrapper">
                                <input type="text" id="addressSearch" 
                                       placeholder="Ketik alamat atau nama tempat (min. 3 karakter)..."
                                       autocomplete="off"
                                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                                <div class="autocomplete-dropdown" id="autocompleteDropdown"></div>
                            </div>
                            
                            <!-- Hidden fields untuk menyimpan venue, lat, lon -->
                            <input type="hidden" name="venue" id="venueInput" value="{{ old('venue') }}">
                            <input type="hidden" name="latitude" id="latitudeInput" value="{{ old('latitude') }}">
                            <input type="hidden" name="longitude" id="longitudeInput" value="{{ old('longitude') }}">
                            
                            <!-- Map Container -->
                            <div id="mapContainer" class="mt-4"></div>
                            
                            <!-- Coordinate Display -->
                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1">Latitude</label>
                                    <input type="text" id="latitudeDisplay" readonly
                                           class="w-full bg-surface-container-highest border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface-variant outline-none"
                                           placeholder="Auto-filled">
                                </div>
                                <div>
                                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1">Longitude</label>
                                    <input type="text" id="longitudeDisplay" readonly
                                           class="w-full bg-surface-container-highest border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface-variant outline-none"
                                           placeholder="Auto-filled">
                                </div>
                            </div>
                            
                            <p class="text-on-surface-variant text-xs mt-2 font-body">💡 Tip: Cari alamat di atas, pilih dari hasil, dan klik pada peta untuk menyesuaikan lokasi</p>
                            
                            @error('venue') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Nilai Kontrak --}}
                <div class="space-y-4">
                    <h3 class="font-label text-xs uppercase tracking-widest text-secondary font-bold flex items-center gap-2">
                        <i class="bi bi-wallet2"></i> Nilai Kontrak
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Total Harga Deal (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="total_price" value="{{ old('total_price') }}" required
                                   class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface font-semibold focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('total_price') border-red-500 @enderror"
                                   placeholder="Contoh: 15000000">
                            @error('total_price') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Jumlah DP Masuk (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="dp_amount" value="{{ old('dp_amount') }}" required
                                   class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-green-600 font-semibold focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('dp_amount') border-red-500 @enderror"
                                   placeholder="Contoh: 7500000">
                            @error('dp_amount') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-outline-variant/30 mt-8">
                    <a href="{{ route('admin.bookings.index') }}"
                       class="px-5 py-2.5 rounded-lg border border-outline-variant/50 text-on-surface-variant hover:bg-surface-container-low transition-colors font-label text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                    <button type="submit"
                            class="bg-gradient-to-br from-primary-container to-primary text-white px-6 py-2.5 rounded-lg font-label text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-md flex items-center gap-2">
                        <i class="bi bi-save"></i> Simpan Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let map = null;
    let marker = null;
    const API_URL = '{{ url("/api/geocoding") }}';
    
    const addressSearch = document.getElementById('addressSearch');
    const dropdown = document.getElementById('autocompleteDropdown');
    const venueInput = document.getElementById('venueInput');
    const latitudeInput = document.getElementById('latitudeInput');
    const longitudeInput = document.getElementById('longitudeInput');
    const latitudeDisplay = document.getElementById('latitudeDisplay');
    const longitudeDisplay = document.getElementById('longitudeDisplay');
    
    // Initialize map with default center (Indonesia center - Bandung)
    function initMap(lat = -6.9175, lng = 107.6062, zoom = 13) {
        if (map) {
            map.remove();
        }
        
        map = L.map('mapContainer').setView([lat, lng], zoom);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Allow clicking on map to set marker
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            updateMarker(lat, lng);
            reverseGeocode(lat, lng);
        });
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
    addressSearch.addEventListener('input', async function() {
        const query = this.value.trim();
        
        if (query.length < 3) {
            dropdown.classList.remove('active');
            dropdown.innerHTML = '';
            return;
        }
        
        try {
            const response = await fetch(`${API_URL}/autocomplete?q=${encodeURIComponent(query)}`);
            const results = await response.json();
            
            dropdown.innerHTML = '';
            
            if (results.length === 0) {
                dropdown.innerHTML = '<div class="autocomplete-item text-gray-500">Tidak ada hasil ditemukan</div>';
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
            dropdown.innerHTML = '<div class="autocomplete-item text-red-500">Error memuat hasil</div>';
        }
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
        venueInput.value = result.display_name;
        dropdown.classList.remove('active');
        updateMarker(result.lat, result.lon);
    }
    
    // Reverse geocode coordinates to get address
    async function reverseGeocode(lat, lng) {
        try {
            const response = await fetch(`${API_URL}/reverse?latitude=${lat}&longitude=${lng}`);
            const data = await response.json();
            
            if (data.success && data.result) {
                venueInput.value = data.result.display_name;
                addressSearch.value = data.result.display_name;
            }
        } catch (error) {
            console.error('Reverse geocode error:', error);
        }
    }
    
    // Initialize map on page load
    initMap();
    
    // If old data exists, restore it
    if (venueInput.value) {
        addressSearch.value = venueInput.value;
    }
    if (latitudeInput.value && longitudeInput.value) {
        const lat = parseFloat(latitudeInput.value);
        const lng = parseFloat(longitudeInput.value);
        updateMarker(lat, lng);
    }
});
</script>
@endpush
