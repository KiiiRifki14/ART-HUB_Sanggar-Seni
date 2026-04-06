@extends('layouts.admin')

@section('title', 'Detail Event – ART-HUB')
@section('page_title', $event->event_code)
@section('page_subtitle', 'Detail & Monitoring Event Pementasan.')

@section('content')

{{-- ── ROW ATAS: PANEL INFORMASI ── --}}
<div class="row g-4 animate-fade-up">

    {{-- Info Event --}}
    <div class="col-12 col-md-4">
        <div class="arh-card-gold h-100 p-4">
            <h5 class="fw-bold mb-1 arh-gold"><i class="bi bi-info-circle me-1"></i> Info Event</h5>
            <small class="text-secondary d-block mb-3 text-capitalize">{{ $event->booking->event_type ?? '-' }}</small>
            
            <div class="mb-3 border-bottom border-secondary pb-2">
                <small class="text-secondary d-block mb-1">Tanggal</small>
                <div class="fw-semibold">{{ $event->event_date->format('d M Y') }}</div>
            </div>
            <div class="mb-3 border-bottom border-secondary pb-2">
                <small class="text-secondary d-block mb-1">Waktu</small>
                <div class="fw-semibold">
                    {{ \Carbon\Carbon::parse($event->event_start)->format('H:i') }} – 
                    {{ \Carbon\Carbon::parse($event->event_end)->format('H:i') }}
                </div>
            </div>
            <div>
                <small class="text-secondary d-block mb-1">Venue</small>
                <div class="fw-semibold">{{ $event->venue }}</div>
            </div>
        </div>
    </div>

    {{-- Status & Estimasi Honor --}}
    <div class="col-12 col-md-4">
        <div class="arh-card h-100 p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-activity me-1 text-info"></i> Status</h5>
            
            @php $sc = ['planning'=>'warning','rehearsal'=>'warning','ready'=>'success','ongoing'=>'warning','completed'=>'success','cancelled'=>'danger']; @endphp
            <div class="mb-3 pb-3 border-bottom border-secondary">
                <span class="badge bg-{{ $sc[$event->status] ?? 'secondary' }} fs-6">
                    {{ strtoupper($event->status) }}
                </span>
            </div>
            
            <div class="mb-3">
                <small class="text-secondary d-block mb-1">Personel Diplot</small>
                <div class="fw-bold fs-3">
                    {{ $event->personnel->count() }}<span class="text-secondary fs-5">/{{ $event->personnel_count }}</span>
                </div>
            </div>
            <div>
                <small class="text-secondary d-block mb-1">Estimasi Honor Total</small>
                <div class="fw-bold fs-5 text-warning">Rp {{ number_format($event->estimated_total_honor, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Keuangan --}}
    <div class="col-12 col-md-4">
        <div class="arh-card h-100 p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-wallet2 me-1 text-success"></i> Keuangan</h5>
            
            @if($event->financialRecord)
                <div class="mb-3 border-bottom border-secondary pb-2">
                    <small class="text-secondary d-block mb-1">Total Revenue</small>
                    <div class="fw-semibold">Rp {{ number_format($event->financialRecord->total_revenue, 0, ',', '.') }}</div>
                </div>
                <div class="mb-3 border-bottom border-warning border-opacity-50 pb-2">
                    <small class="text-secondary d-block mb-1">Fixed Profit (Laba)</small>
                    <div class="fw-bold arh-gold">Rp {{ number_format($event->financialRecord->fixed_profit, 0, ',', '.') }}</div>
                </div>
                <div>
                    <small class="text-secondary d-block mb-1">Budget Operasional Default</small>
                    <div>Rp {{ number_format($event->financialRecord->operational_budget, 0, ',', '.') }}</div>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-safe2 text-secondary fs-1 d-block mb-2"></i>
                    <p class="text-secondary small mb-0">Belum ada data keuangan. Selesaikan DP Verification terlebih dahulu.</p>
                </div>
            @endif
        </div>
    </div>

</div>

{{-- ── ROW BAWAH: TABEL PERSONEL ── --}}
<div class="arh-card mt-4 p-4 animate-fade-up">
    <h5 class="fw-bold mb-4 d-flex align-items-center gap-2 arh-gold">
        <i class="bi bi-people-fill"></i> Personel & Check-in Monitor
    </h5>

    <div class="table-responsive">
        <table class="table arh-table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>Fee (Honor)</th>
                    <th>Check-in</th>
                    <th>Status Absen</th>
                </tr>
            </thead>
            <tbody>
                @forelse($event->personnel as $p)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="arh-avatar-sm">{{ strtoupper(substr($p->user->name, 0, 2)) }}</div>
                            <div>
                                <div class="fw-semibold">{{ $p->user->name }}</div>
                                <small class="text-secondary">{{ $p->specialty }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-capitalize">{{ str_replace('_', ' ', $p->pivot->role_in_event) }}</td>
                    <td class="fw-semibold">Rp {{ number_format($p->pivot->fee, 0, ',', '.') }}</td>
                    <td>
                        @if($p->pivot->checked_in_at)
                            <span class="text-success fw-bold"><i class="bi bi-clock-history me-1"></i>{{ \Carbon\Carbon::parse($p->pivot->checked_in_at)->format('H:i') }}</span>
                        @else
                            <span class="text-secondary">-</span>
                        @endif
                    </td>
                    <td>
                        @php $as = $p->pivot->attendance_status; @endphp
                        @if($as === 'on_time') <span class="badge bg-success">ON TIME</span>
                        @elseif($as === 'late') <span class="badge bg-danger">TELAT {{ $p->pivot->late_minutes }}m</span>
                        @else <span class="badge bg-secondary">BELUM</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-secondary">Belum ada personel yang diplot ke event ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── TOMBOL AKSI BAWAH ── --}}
<div class="mt-4 d-flex gap-2 flex-wrap animate-fade-up">
    <a href="{{ route('admin.events.plotting', $event->id) }}" class="btn btn-arh-gold">
        <i class="bi bi-diagram-3 me-1"></i> Kelola Plotting
    </a>
    @if($event->financialRecord)
    <a href="{{ route('admin.financials.post_event', $event->id) }}" class="btn btn-outline-info">
        <i class="bi bi-calculator me-1"></i> Kalkulasi Post-Event (Biaya Riil)
    </a>
    @endif
    <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>
@endsection
