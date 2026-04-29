@extends('layouts.admin')
@section('title', 'Detail Monitoring – ' . $event->booking->client_name)
@section('page_title', 'Detail Operasional')
@section('page_subtitle', $event->event_code . ' · ' . \Carbon\Carbon::parse($event->event_date)->format('d M Y'))

@section('content')
@php
    $booking   = $event->booking;
    $finance   = $event->financialRecord;
    $eventDate = \Carbon\Carbon::parse($event->event_date);
    $daysUntil = now()->startOfDay()->diffInDays($eventDate->startOfDay(), false);
    $isPriority = ($daysUntil >= 0 && $daysUntil <= 3);
    $statusColors = [
        'pending'   => '#fbbf24', 'dp_paid' => '#f97316',
        'confirmed' => '#60a5fa', 'paid_full'=> '#4ade80',
        'completed' => '#86efac', 'cancelled'=> '#888',
    ];
    $statusLabels = [
        'pending'   => 'Negotiation', 'dp_paid' => '🔒 Locked',
        'confirmed' => 'DP 50%',      'paid_full'=> '✓ PAID Lunas',
        'completed' => '✓✓ Completed','cancelled'=> 'Cancelled',
    ];
    $bStatus = $booking->status ?? 'pending';
    $badgeBg    = $bStatus === 'dp_paid' ? 'rgba(249,115,22,0.15)' : 'rgba(139,26,42,0.15)';
    $badgeColor = $statusColors[$bStatus] ?? '#8B1A2A';
    $badgeBorder = $badgeColor . '55';
    $badgeStyle  = "background:{$badgeBg}; color:{$badgeColor}; border:1px solid {$badgeBorder};";
@endphp

<style>
    .det-card { background:#1a1a1a; border:1px solid #2a2a2a; border-radius:12px; padding:24px; }
    .kru-row { display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px solid #222; }
    .kru-row:last-child { border-bottom:none; }
    .absen-dot { width:10px; height:10px; border-radius:50%; display:inline-block; }
    .absen-dot.on-time { background:#4ade80; }
    .absen-dot.late    { background:#fbbf24; }
    .absen-dot.absent  { background:#ef4444; }
    .absen-dot.pending { background:#555; }
</style>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.events.monitoring') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    @if($isPriority)
    <span class="badge" style="background:rgba(251,191,36,0.2); color:#fbbf24; border:1px solid rgba(251,191,36,0.4); font-size:0.82rem;">
        ★ Upcoming Priority – H-{{ $daysUntil }}
    </span>
    @endif
    <span class="badge" @style([$badgeStyle])>
        {{ $statusLabels[$bStatus] ?? $bStatus }}
    </span>
</div>

<div class="row g-4">
    {{-- Kiri: Info Acara + Koordinat --}}
    <div class="col-12 col-lg-5">
        <div class="det-card mb-4">
            <h6 class="fw-bold mb-3" style="color:#8B1A2A;"><i class="bi bi-info-circle-fill me-2"></i>Info Acara</h6>
            <div class="row g-3 text-sm">
                <div class="col-6">
                    <div class="text-secondary" style="font-size:0.72rem;">KLIEN</div>
                    <div class=" fw-semibold">{{ $booking->client_name }}</div>
                </div>
                <div class="col-6">
                    <div class="text-secondary" style="font-size:0.72rem;">JENIS ACARA</div>
                    <div class=" fw-semibold text-capitalize">{{ str_replace('_',' ', $booking->event_type) }}</div>
                </div>
                <div class="col-6">
                    <div class="text-secondary" style="font-size:0.72rem;">TANGGAL</div>
                    <div class=" fw-semibold">{{ $eventDate->format('d M Y') }}</div>
                </div>
                <div class="col-6">
                    <div class="text-secondary" style="font-size:0.72rem;">WAKTU</div>
                    <div class=" fw-semibold">
                        {{ \Carbon\Carbon::parse($event->event_start)->format('H:i') }} –
                        {{ \Carbon\Carbon::parse($event->event_end)->format('H:i') }} WIB
                    </div>
                </div>
                <div class="col-12">
                    <div class="text-secondary" style="font-size:0.72rem;">VENUE</div>
                    <div class=" fw-semibold">{{ $event->venue }}</div>
                </div>
            </div>
        </div>

        <div class="det-card">
            <h6 class="fw-bold mb-3" style="color:#8B1A2A;"><i class="bi bi-geo-alt-fill me-2"></i>Koordinat GPS</h6>
            @if($event->latitude && $event->longitude)
                <div class="mb-3">
                    <span class="badge bg-success-subtle text-success border border-success mb-2"><i class="bi bi-check-circle me-1"></i>Koordinat Aktif</span>
                    <div class="d-flex gap-3 " style="font-size:0.85rem;">
                        <div><span class="text-secondary">Lat:</span> {{ $event->latitude }}</div>
                        <div><span class="text-secondary">Lng:</span> {{ $event->longitude }}</div>
                    </div>
                </div>
                <a href="https://maps.google.com/?q={{ $event->latitude }},{{ $event->longitude }}"
                   target="_blank" class="btn btn-sm btn-outline-success w-100">
                    <i class="bi bi-map me-1"></i>Buka di Google Maps
                </a>
            @else
                <div class="text-secondary small mb-3">
                    <i class="bi bi-exclamation-triangle me-1 text-warning"></i>
                    Koordinat GPS belum diset. Ghosting Guard tidak aktif.
                </div>
                <button class="btn btn-sm btn-outline-warning w-100" data-bs-toggle="modal" data-bs-target="#modalKoordinat">
                    <i class="bi bi-geo me-1"></i>Set Koordinat GPS
                </button>
            @endif
        </div>
    </div>

    {{-- Kanan: Daftar Kru & Absensi --}}
    <div class="col-12 col-lg-7">
        <div class="det-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="color:#8B1A2A;"><i class="bi bi-people-fill me-2"></i>Ghosting Guard – Status Kru</h6>
                <span class="badge bg-secondary">{{ $event->personnel->count() }} / {{ $event->personnel_count ?? '–' }}</span>
            </div>

            @if($event->personnel->isEmpty())
                <div class="text-center py-4 text-secondary">
                    <i class="bi bi-person-x fs-2 d-block mb-2"></i>
                    Belum ada kru yang di-plot.<br>
                    <a href="{{ route('admin.events.plotting', $event->id) }}" class="btn btn-sm btn-arh-gold mt-2">
                        <i class="bi bi-people me-1"></i>Go to Smart Plotting
                    </a>
                </div>
            @else
                @foreach($event->personnel as $p)
                @php
                    $pivot  = $p->pivot;
                    $aStatus = $pivot->attendance_status ?? 'not_arrived';
                    $dotClass = match($aStatus) {
                        'on_time' => 'on-time',
                        'late'    => 'late',
                        default   => ($pivot->checked_in_at ? 'on-time' : 'absent'),
                    };
                    $lateText = ($aStatus === 'late' && $pivot->late_minutes > 0)
                        ? ' (Telat ' . $pivot->late_minutes . ' mnt)'
                        : '';
                @endphp
                <div class="kru-row">
                    <div class="d-flex align-items-center gap-3">
                        <span class="absen-dot {{ $dotClass }}"></span>
                        <div>
                            <div class=" fw-semibold" style="font-size:0.88rem;">{{ $p->user->name ?? '–' }}</div>
                            <div class="text-secondary" style="font-size:0.75rem;">
                                {{ str_replace('_',' ', $pivot->role_in_event) }}
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        @if($pivot->checked_in_at)
                            <div class="text-success fw-semibold" style="font-size:0.8rem;">
                                <i class="bi bi-geo-fill me-1"></i>
                                {{ \Carbon\Carbon::parse($pivot->checked_in_at)->format('H:i') }}
                                {{ $lateText }}
                            </div>
                        @else
                            <span class="text-secondary" style="font-size:0.8rem;">Belum absen</span>
                        @endif
                        <div class="text-secondary" style="font-size:0.72rem;">
                            Rp {{ number_format($pivot->fee, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Legend --}}
                <div class="mt-3 d-flex gap-3" style="font-size:0.72rem; color:#888;">
                    <span><span class="absen-dot on-time me-1"></span>Hadir</span>
                    <span><span class="absen-dot late me-1"></span>Terlambat</span>
                    <span><span class="absen-dot absent me-1"></span>Belum Absen</span>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Set Koordinat --}}
<div class="modal fade" id="modalKoordinat" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary">
                <h6 class="modal-title "><i class="bi bi-geo-fill text-warning me-2"></i>Set Koordinat GPS</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.events.update_coordinates', $event->id) }}" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-light">Latitude</label>
                        <input type="number" name="latitude" step="any" class="form-control" value="{{ $event->latitude }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-light">Longitude</label>
                        <input type="number" name="longitude" step="any" class="form-control" value="{{ $event->longitude }}" required>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold">Simpan Koordinat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


