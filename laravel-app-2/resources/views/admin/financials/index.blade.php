@extends('layouts.admin')

@section('title', 'Financial Report - ART-HUB')
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
<div class="grid grid-4 animate-fade-up" style="margin-bottom: 2rem;">
    <div class="glass-panel card-gold" style="text-align: center;">
        <i class="ph-fill ph-vault" style="color: var(--gold-primary); font-size: 2rem;"></i>
        <h3 class="title-gold" style="font-size: 1.5rem; margin: 0.5rem 0 0 0;">Rp {{ number_format($totalProfit, 0, ',', '.') }}</h3>
        <small class="text-muted">Fixed Profit Total</small>
    </div>
    <div class="glass-panel" style="text-align: center;">
        <i class="ph-fill ph-shield-check" style="color: var(--success); font-size: 2rem;"></i>
        <h3 style="font-size: 1.5rem; margin: 0.5rem 0 0 0;">Rp {{ number_format($totalBuffer, 0, ',', '.') }}</h3>
        <small class="text-muted">Safety Buffer</small>
    </div>
    <div class="glass-panel" style="text-align: center;">
        <i class="ph-fill ph-chart-line-up" style="color: var(--text-main); font-size: 2rem;"></i>
        <h3 style="font-size: 1.5rem; margin: 0.5rem 0 0 0;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        <small class="text-muted">Total Revenue</small>
    </div>
    <div class="glass-panel" style="text-align: center;">
        <i class="ph-fill ph-money" style="color: var(--warning); font-size: 2rem;"></i>
        <h3 style="font-size: 1.5rem; margin: 0.5rem 0 0 0;">Rp {{ number_format($totalOps, 0, ',', '.') }}</h3>
        <small class="text-muted">Realisasi Operasional</small>
    </div>
</div>

<div class="glass-panel animate-fade-up stagger-1">
    <h2 style="margin-bottom: 2rem;"><i class="ph ph-chart-line-up" style="color: var(--gold-primary);"></i> Detail Per Event</h2>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Event</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Revenue</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Fixed Profit</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Honor Kru</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Budget Ops</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Realisasi</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Selisih</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $r)
                @php $selisih = $r->operational_budget - $r->actual_operational_cost; @endphp
                <tr style="border-bottom: 1px solid var(--border-color);" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1rem;">
                        <span class="badge badge-gold">{{ $r->event->event_code ?? '-' }}</span>
                        <div><small class="text-muted">{{ $r->event->booking->event_type ?? '' }}</small></div>
                    </td>
                    <td style="padding: 1rem; font-weight: 600;">Rp {{ number_format($r->total_revenue, 0, ',', '.') }}</td>
                    <td style="padding: 1rem;"><span class="title-gold">Rp {{ number_format($r->fixed_profit, 0, ',', '.') }}</span><br><small class="text-muted">{{ $r->fixed_profit_pct }}%</small></td>
                    <td style="padding: 1rem;">Rp {{ number_format($r->total_personnel_honor, 0, ',', '.') }}</td>
                    <td style="padding: 1rem;">Rp {{ number_format($r->operational_budget, 0, ',', '.') }}</td>
                    <td style="padding: 1rem;">Rp {{ number_format($r->actual_operational_cost, 0, ',', '.') }}</td>
                    <td style="padding: 1rem;">
                        @if($selisih >= 0) <span style="color: var(--success); font-weight: 700;">+Rp {{ number_format($selisih, 0, ',', '.') }}</span>
                        @else <span style="color: var(--danger); font-weight: 700;">-Rp {{ number_format(abs($selisih), 0, ',', '.') }}</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <a href="{{ route('admin.financials.post_event', $r->event->id) }}" class="btn btn-outline" style="padding: 0.4rem 1rem; font-size: 0.8rem;">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="glass-panel" style="text-align: center; border-color: var(--danger); background: var(--danger-glow); padding: 3rem;">
    <i class="ph-fill ph-warning-octagon" style="color: var(--danger); font-size: 4rem; margin-bottom: 1rem;"></i>
    <h3 style="color: #fff; margin-bottom: 1rem;">Akses Ditolak (Level VVIP)</h3>
    <p class="text-muted" style="max-width: 500px; margin: 0 auto;">Laporan finansial, fixed profit, dan buffer budget adalah area khusus Pimpinan Sanggar. Akun Anda tidak memiliki otoritas untuk melihat halaman ini.</p>
</div>
@endcan
@endsection
