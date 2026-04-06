@extends('layouts.admin')

@section('title', 'Jadwal Latihan – ART-HUB')
@section('page_title', 'Jadwal Latihan (Rehearsals)')
@section('page_subtitle', 'Kalender & plotting sesi latihan sanggar.')

@section('content')

{{-- STAT CARDS --}}
@php
    $total    = $rehearsals->count();
    $upcoming = $rehearsals->where('rehearsal_date', '>=', now()->toDateString())->count();
    $past     = $total - $upcoming;
@endphp
<div class="row g-3 mb-4 animate-fade-up">
    <div class="col-12 col-md-4">
        <div class="arh-card-gold p-4 text-center h-100">
            <i class="bi bi-music-note-list arh-gold fs-1 d-inline-block mb-2"></i>
            <h3 class="fw-bold mb-0 arh-gold fs-3">{{ $upcoming }} Sesi</h3>
            <small class="fw-semibold text-secondary">Mendatang</small>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="arh-card p-4 text-center h-100">
            <i class="bi bi-clock-history text-secondary fs-1 d-inline-block mb-2"></i>
            <h3 class="fw-bold mb-0 text-white fs-3">{{ $past }} Sesi</h3>
            <small class="fw-semibold text-secondary">Selesai / Berlalu</small>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="arh-card p-4 text-center h-100" style="border-color: rgba(25,135,84,0.3);">
            <i class="bi bi-check-circle-fill text-success fs-1 d-inline-block mb-2"></i>
            <h3 class="fw-bold mb-0 text-success fs-3">{{ $total }}</h3>
            <small class="fw-semibold text-secondary">Total Terjadwal</small>
        </div>
    </div>
</div>

{{-- LIST LATIHAN --}}
<div class="arh-card p-4 animate-fade-up">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2 arh-gold">
            <i class="bi bi-calendar3"></i> Daftar Jadwal Latihan
        </h5>
    </div>

    <div class="table-responsive">
        <table class="table arh-table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Untuk Event / Klien</th>
                    <th>Tipe Latihan</th>
                    <th>Tanggal</th>
                    <th>Waktu (WIB)</th>
                    <th>Lokasi</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rehearsals as $r)
                @php
                    $isPast = \Carbon\Carbon::parse($r->rehearsal_date)->isPast() && \Carbon\Carbon::parse($r->rehearsal_date)->toDateString() !== now()->toDateString();
                @endphp
                <tr class="{{ $isPast ? 'opacity-50' : '' }}">
                    <td>
                        <span class="badge arh-badge-gold mb-1">{{ $r->event->event_code ?? '-' }}</span>
                        <div class="fw-semibold">{{ $r->event->booking->client_name ?? ($r->event->booking->client->name ?? '-') }}</div>
                    </td>
                    <td><span class="badge bg-secondary text-capitalize">{{ $r->type }}</span></td>
                    <td class="fw-semibold">{{ \Carbon\Carbon::parse($r->rehearsal_date)->format('d M Y') }}</td>
                    <td>
                        <i class="bi bi-clock me-1 text-secondary"></i>
                        {{ \Carbon\Carbon::parse($r->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($r->end_time)->format('H:i') }}
                    </td>
                    <td><small>{{ $r->location }}</small></td>
                    <td style="max-width: 15rem;">
                        <small class="text-secondary text-truncate d-inline-block w-100" title="{{ $r->notes }}">
                            {{ $r->notes ?? '-' }}
                        </small>
                    </td>
                    <td>
                        <a href="{{ route('admin.events.show', $r->event_id) }}" class="btn btn-outline-secondary btn-sm" title="Lihat Event Terkait">
                            <i class="bi bi-eye"></i> Detail Event
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-secondary">
                        <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                        Belum ada jadwal latihan.<br>
                        Gunakan tombol Plotting di halaman Event untuk menjadwalkan latihan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
