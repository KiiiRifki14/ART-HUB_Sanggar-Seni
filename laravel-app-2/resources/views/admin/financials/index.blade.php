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
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-primary-container to-primary text-white rounded-xl p-6 border border-primary/20 shadow-[0_12px_24px_rgba(54,31,26,0.08)] text-center relative overflow-hidden">
        <div class="absolute -right-6 -bottom-6 text-white/5">
            <i class="bi bi-safe2-fill text-9xl"></i>
        </div>
        <div class="relative z-10">
            <i class="bi bi-safe2-fill text-3xl text-secondary-container mb-3 block"></i>
            <h3 class="font-headline text-3xl font-bold text-secondary-container mb-1">Rp {{ number_format($totalProfit, 0, ',', '.') }}</h3>
            <div class="font-label text-xs uppercase tracking-widest text-white/80 font-bold">Fixed Profit Total</div>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-green-500/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-shield-check text-3xl text-green-500 mb-3 block"></i>
        <h3 class="font-headline text-3xl font-bold text-green-600 mb-1">Rp {{ number_format($totalBuffer, 0, ',', '.') }}</h3>
        <div class="font-label text-xs uppercase tracking-widest text-outline font-bold">Safety Buffer Area</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-graph-up-arrow text-3xl text-on-surface-variant mb-3 block"></i>
        <h3 class="font-headline text-3xl font-bold text-on-surface mb-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        <div class="font-label text-xs uppercase tracking-widest text-outline font-bold">Total Nilai Kontrak</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-orange-500/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-cash-stack text-3xl text-orange-500 mb-3 block"></i>
        <h3 class="font-headline text-3xl font-bold text-orange-600 mb-1">Rp {{ number_format($totalOps, 0, ',', '.') }}</h3>
        <div class="font-label text-xs uppercase tracking-widest text-outline font-bold">Realisasi Operasional</div>
    </div>
</div>

{{-- Header --}}
<div class="flex items-center gap-2 mb-6">
    <h2 class="font-headline text-xl text-primary font-semibold">
        <i class="bi bi-activity text-secondary me-1"></i> Laporan Keuangan per Event
    </h2>
</div>

{{-- ── DETAIL PER EVENT ── --}}
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
    <table class="w-full">
        <thead class="bg-surface-container-low">
            <tr>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Event</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Revenue</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Laba Pimpinan (Fixed)</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Honor Kru</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Budget Ops</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Realisasi Ops</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Selisih Ops</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/20">
            @forelse($records as $r)
            @php 
                $selisih = $r->operational_budget - $r->actual_operational_cost; 
            @endphp
            <tr class="hover:bg-surface-container-low/50 transition-colors">
                <td class="px-6 py-4">
                    <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider mb-1">
                        {{ $r->event->event_code ?? '-' }}
                    </span>
                    <div class="font-label text-xs text-outline capitalize">{{ str_replace('_', ' ', $r->event->booking->event_type ?? '') }}</div>
                </td>
                <td class="px-6 py-4 text-right font-body font-semibold text-on-surface text-sm">
                    Rp {{ number_format($r->total_revenue, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="font-headline font-bold text-primary text-sm">Rp {{ number_format($r->fixed_profit, 0, ',', '.') }}</div>
                    <div class="font-label text-xs text-outline">{{ $r->fixed_profit_pct }}% dr Kontrak</div>
                </td>
                <td class="px-6 py-4 text-right font-body text-sm text-on-surface-variant">
                    Rp {{ number_format($r->total_personnel_honor, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 text-right font-body text-sm text-on-surface-variant">
                    Rp {{ number_format($r->operational_budget, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 text-right font-body text-sm text-on-surface-variant">
                    Rp {{ number_format($r->actual_operational_cost, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 text-right">
                    @if($selisih >= 0) 
                        <span class="font-body text-sm font-bold text-green-600">+Rp {{ number_format($selisih, 0, ',', '.') }}</span>
                    @else 
                        <span class="font-body text-sm font-bold text-red-600">-Rp {{ number_format(abs($selisih), 0, ',', '.') }}</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    <a href="{{ route('admin.financials.post_event', $r->event->id ?? 0) }}" 
                       class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-md border border-outline-variant/30 bg-surface-container-lowest font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant hover:bg-primary hover:text-white hover:border-primary transition-all">
                        <i class="bi bi-eye-fill"></i> Post-Event
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-20 text-center">
                    <i class="bi bi-cash-stack text-4xl text-outline mb-4 block"></i>
                    <p class="font-headline text-lg text-on-surface font-semibold mb-1">Belum ada data keuangan</p>
                    <p class="font-label text-xs uppercase tracking-widest text-outline">Data akan terbentuk setelah DP masuk</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@else
{{-- JIKA BUKAN VVIP --}}
<div class="px-6 py-20 text-center rounded-xl bg-red-500/5 border border-red-500/20 max-w-2xl mx-auto mt-10">
    <div class="w-24 h-24 rounded-full bg-red-500/10 flex items-center justify-center mx-auto mb-6">
        <i class="bi bi-shield-lock-fill text-5xl text-red-500"></i>
    </div>
    <h3 class="font-headline text-2xl text-red-700 font-bold mb-3">Akses Ditolak (Requires Pimpinan)</h3>
    <p class="font-body text-sm text-on-surface-variant leading-relaxed">
        Laporan finansial, fixed profit, dan buffer budget adalah area khusus Pimpinan Sanggar.<br> 
        Akun Anda tidak memiliki otoritas untuk melihat data sensitif ini.
    </p>
</div>
@endcan

@endsection
