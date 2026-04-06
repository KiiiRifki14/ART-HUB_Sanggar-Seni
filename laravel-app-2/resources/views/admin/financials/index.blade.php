@extends('layouts.admin')

@section('title', 'Financial Report – ART-HUB')
@section('page_title', 'Financial Report')
@section('page_subtitle', 'Ringkasan laba, anggaran, dan audit keuangan sanggar.')

@section('content')
@php
    $totalProfit = $records->sum('fixed_profit');
    $totalBuffer = $records->sum('safety_buffer_amt');
    $totalRevenue = $records->sum('total_revenue');
    $totalOps = $records->sum('actual_operational_cost');
@endphp

@can('view-financials')
{{-- ── STAT CARDS ── --}}
<div class="row g-3 mb-5 animate-fade-up">
    <div class="col-6 col-xl-3">
        <div class="arh-card-gold p-4 text-center h-100">
            <i class="bi bi-safe2-fill arh-gold fs-1 mb-2 d-inline-block"></i>
            <h3 class="fw-bold fs-4 arh-gold mb-1">Rp {{ number_format($totalProfit, 0, ',', '.') }}</h3>
            <small class="text-secondary fw-semibold">Fixed Profit Total</small>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="arh-card p-4 text-center h-100" style="border-color: rgba(25,135,84,0.3);">
            <i class="bi bi-shield-check text-success fs-1 mb-2 d-inline-block"></i>
            <h3 class="fw-bold fs-4 text-success mb-1">Rp {{ number_format($totalBuffer, 0, ',', '.') }}</h3>
            <small class="text-secondary fw-semibold">Safety Buffer Area</small>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="arh-card p-4 text-center h-100">
            <i class="bi bi-graph-up-arrow text-white fs-1 mb-2 d-inline-block"></i>
            <h3 class="fw-bold fs-4 text-white mb-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <small class="text-secondary fw-semibold">Total Nilai Kontrak</small>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="arh-card p-4 text-center h-100" style="border-color: rgba(255,193,7,0.3);">
            <i class="bi bi-cash-stack text-warning fs-1 mb-2 d-inline-block"></i>
            <h3 class="fw-bold fs-4 text-warning mb-1">Rp {{ number_format($totalOps, 0, ',', '.') }}</h3>
            <small class="text-secondary fw-semibold">Realisasi Operasional</small>
        </div>
    </div>
</div>

{{-- ── DETAIL PER EVENT ── --}}
<div class="arh-card p-4 animate-fade-up">
    <h5 class="fw-bold mb-4 d-flex align-items-center gap-2 arh-gold">
        <i class="bi bi-activity"></i> Laporan Keuangan per Event
    </h5>
    
    <div class="table-responsive">
        <table class="table arh-table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Revenue</th>
                    <th>Laba Pimpinan (Fixed)</th>
                    <th>Honor Kru</th>
                    <th>Budget Ops</th>
                    <th>Realisasi Ops</th>
                    <th>Selisih Ops</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $r)
                @php $selisih = $r->operational_budget - $r->actual_operational_cost; @endphp
                <tr>
                    <td>
                        <span class="badge arh-badge-gold d-block mb-1">{{ $r->event->event_code ?? '-' }}</span>
                        <small class="text-secondary">{{ $r->event->booking->event_type ?? '' }}</small>
                    </td>
                    <td class="fw-semibold">Rp {{ number_format($r->total_revenue, 0, ',', '.') }}</td>
                    <td>
                        <div class="fw-bold arh-gold">Rp {{ number_format($r->fixed_profit, 0, ',', '.') }}</div>
                        <small class="text-secondary">{{ $r->fixed_profit_pct }}% dr Kontrak</small>
                    </td>
                    <td>Rp {{ number_format($r->total_personnel_honor, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($r->operational_budget, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($r->actual_operational_cost, 0, ',', '.') }}</td>
                    <td>
                        @if($selisih >= 0) 
                            <span class="text-success fw-bold">+Rp {{ number_format($selisih, 0, ',', '.') }}</span>
                        @else 
                            <span class="text-danger fw-bold">-Rp {{ number_format(abs($selisih), 0, ',', '.') }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.financials.post_event', $r->event->id ?? 0) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-eye"></i> Post-Event
                        </a>
                    </td>
                </tr>
                @endforeach
                @if($records->isEmpty())
                <tr>
                    <td colspan="8" class="text-center py-4 text-secondary">Belum ada data keuangan yang terbentuk.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@else
{{-- JIKA BUKAN VVIP --}}
<div class="p-5 text-center rounded-3 bg-danger bg-opacity-10 border border-danger mb-4 animate-fade-up">
    <i class="bi bi-shield-lock-fill text-danger" style="font-size: 4rem;"></i>
    <h3 class="text-danger fw-bold mt-3 mb-2">Akses Ditolak (Requires Pimpinan)</h3>
    <p class="text-secondary max-w-50 mx-auto">
        Laporan finansial, fixed profit, dan buffer budget adalah area khusus Pimpinan Sanggar. 
        Akun Anda tidak memiliki otoritas untuk melihat data sensitif ini.
    </p>
</div>
@endcan

@endsection
