@extends('layouts.personnel')

@section('content')

@php
    $personnel = Auth::user()->personnelProfile;
@endphp

@if(!$personnel)
    <div class="alert alert-danger">Profil Personel tidak ditemukan untuk akun ini. Hubungi Admin.</div>
@else
    {{-- Profil & Jobdesk Ringkas --}}
    <div class="card-personnel p-3 mb-4 animate-fade-up">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-arh-gold d-flex justify-content-center align-items-center fw-bold fs-3" style="width: 60px; height: 60px;">
                <i class="bi bi-person-fill"></i>
            </div>
            <div>
                <h5 class="mb-1 text-white fw-bold">{{ $personnel->stage_name ?? Auth::user()->name }}</h5>
                <span class="badge badge-role mt-1 px-2 py-1"><i class="bi bi-music-note-list me-1"></i>{{ $personnel->primary_skill ?? 'Kru' }}</span>
            </div>
        </div>
        <div class="mt-3 pt-3 border-top border-secondary text-secondary small">
            <div class="d-flex justify-content-between mb-1">
                <span>Total Fee Pending</span>
                <span class="text-white fw-bold">Rp {{ number_format($personnel->events->where('pivot.status', 'scheduled')->sum('pivot.fee'), 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Event Mendatang</span>
                <span class="text-white fw-bold">{{ $personnel->events->where('event_date', '>=', now()->toDateString())->count() }}</span>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3 arh-gold"><i class="bi bi-calendar-check me-2"></i>Tugas & Jadwal</h5>

    <div class="d-flex flex-column gap-3 mb-5 pb-4">
        @forelse($personnel->events()->where('event_date', '>=', now()->toDateString())->orderBy('event_date', 'asc')->get() as $event)
            <div class="card-personnel p-3 position-relative">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="badge bg-secondary mb-1">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</span>
                        <div class="fw-bold text-white fs-5">{{ $event->booking->client_name ?? 'Event Sanggar' }}</div>
                    </div>
                </div>
                
                <div class="small text-secondary mb-2">
                    <i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($event->event_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->event_end)->format('H:i') }}<br>
                    <i class="bi bi-geo-alt me-1"></i> {{ $event->venue }}
                </div>
                
                <div class="p-2 rounded mt-2 mb-3" style="background: rgba(197, 160, 89, 0.1); border-left: 3px solid var(--arh-gold);">
                    <small class="d-block arh-gold fw-bold mb-1">Peran & Instruksi ("Jobdesk"):</small>
                    <div class="text-white text-capitalize small">
                        Job: <strong>{{ str_replace('_', ' ', $event->pivot->role_in_event ?? '-') }}</strong>
                    </div>
                    @if(str_contains(strtolower($event->pivot->role_in_event), 'gendang') || str_contains(strtolower($event->pivot->role_in_event), 'kendang'))
                        <small class="text-secondary mt-1 d-block"><i class="bi bi-box-seam me-1"></i>Harap bawa Kendang Induk dari Gudang B</small>
                    @endif
                </div>

                {{-- Status Kehadiran / Geolocation Button --}}
                @if($event->pivot->checked_in_at)
                    <div class="alert alert-success py-2 px-3 mb-0 text-center border-0 border-start border-4 border-success rounded-0">
                        <i class="bi bi-check-circle-fill me-1"></i> Hadir ({{ \Carbon\Carbon::parse($event->pivot->checked_in_at)->format('H:i') }})
                    </div>
                @else
                    {{-- Tombol Absen GPS --}}
                    <button type="button" class="btn btn-arh-gold w-100" data-event-id="{{ $event->id }}" onclick="performCheckIn(this.getAttribute('data-event-id'))">
                        <i class="bi bi-geo-fill me-1"></i> Saya Sudah di Lokasi
                    </button>
                    <form id="checkin-form-{{ $event->id }}" action="{{ route('personnel.attendance.check_in', $event->id) }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="latitude" id="lat-{{ $event->id }}">
                        <input type="hidden" name="longitude" id="lng-{{ $event->id }}">
                    </form>
                @endif
            </div>
        @empty
            <div class="text-center py-5 text-secondary">
                <i class="bi bi-flag fs-1 d-block mb-2"></i>
                <p>Belum ada jadwal event mendatang untuk Anda.</p>
            </div>
        @endforelse
    </div>
@endif

@endsection

@push('scripts')
<script>
    function performCheckIn(eventId) {
        if (!navigator.geolocation) {
            alert("Geolocation tidak didukung oleh browser Anda.");
            return;
        }

        const btn = event.target.closest('button');
        const defaultText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Mencari Lokasi...';
        btn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                // Fallback Offline: jika tidak ada internet, simpan di localStorage
                if (!navigator.onLine) {
                    btn.innerHTML = defaultText;
                    btn.disabled = false;
                    
                    let pending = JSON.parse(localStorage.getItem('pendingCheckins') || '[]');
                    pending.push({
                        eventId: eventId,
                        latitude: lat,
                        longitude: lng,
                        timestamp: new Date().toISOString()
                    });
                    localStorage.setItem('pendingCheckins', JSON.stringify(pending));
                    alert("Koneksi Offline. Absensi telah direkam di HP dan akan disinkronkan saat terhubung kembali!");
                    btn.innerHTML = '<i class="bi bi-clock-history me-1"></i> Menunggu Sinyal';
                } else {
                    // Online: submit normal
                    document.getElementById('lat-' + eventId).value = lat;
                    document.getElementById('lng-' + eventId).value = lng;
                    document.getElementById('checkin-form-' + eventId).submit();
                }
            },
            function(error) {
                btn.innerHTML = defaultText;
                btn.disabled = false;
                alert("Gagal mendeteksi lokasi. Pastikan GPS aktif dan Anda mengizinkan browser mengakses lokasi.");
                console.error("GPS Error:", error);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }
</script>
@endpush
