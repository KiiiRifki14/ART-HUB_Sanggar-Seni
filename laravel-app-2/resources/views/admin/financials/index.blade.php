@extends('layouts.admin')

@section('title', 'Laporan Keuangan – ART-HUB')
@section('page_title', 'Laporan Keuangan')
@section('page_subtitle', 'Ringkasan laba, anggaran, dan audit keuangan sanggar.')

@section('content')
@php
    $totalProfit = $totals['fixed_profit'];
    $totalBuffer = $totals['safety_buffer_amt'];
    $totalRevenue = $totals['total_revenue'];
    $totalOps = $totals['actual_operational_cost'];
    $totalHonor = $totals['total_personnel_honor'];
    $totalBudgetOps = $totals['operational_budget'];
@endphp

@can('view-financials')
{{-- ══ STAT CARDS ══ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-primary-container to-primary text-white rounded-xl p-4 sm:p-6 border border-primary/20 shadow-[0_12px_24px_rgba(54,31,26,0.08)] text-center relative overflow-hidden col-span-2 lg:col-span-1">
        <div class="absolute -right-6 -bottom-6 text-white/5">
            <i class="bi bi-safe2-fill text-9xl"></i>
        </div>
        <div class="relative z-10">
            <i class="bi bi-safe2-fill text-3xl text-secondary-container mb-3 block"></i>
            <h3 class="font-headline text-3xl font-bold text-secondary-container mb-1">Rp {{ number_format($totalProfit, 0, ',', '.') }}</h3>
            <div class="font-label text-xs uppercase tracking-widest text-white/80 font-bold">Total Laba Tetap</div>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-4 sm:p-6 border border-green-500/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-shield-check text-2xl sm:text-3xl text-green-500 mb-2 sm:mb-3 block"></i>
        <h3 class="font-headline text-2xl sm:text-3xl font-bold text-green-600 mb-1">Rp {{ number_format($totalBuffer, 0, ',', '.') }}</h3>
        <div class="font-label text-[0.65rem] sm:text-xs uppercase tracking-widest text-outline font-bold">Dana Cadangan</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-4 sm:p-6 border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-graph-up-arrow text-2xl sm:text-3xl text-on-surface-variant mb-2 sm:mb-3 block"></i>
        <h3 class="font-headline text-2xl sm:text-3xl font-bold text-on-surface mb-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        <div class="font-label text-[0.65rem] sm:text-xs uppercase tracking-widest text-outline font-bold">Total Nilai Kontrak</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-4 sm:p-6 border border-orange-500/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-cash-stack text-2xl sm:text-3xl text-orange-500 mb-2 sm:mb-3 block"></i>
        <h3 class="font-headline text-2xl sm:text-3xl font-bold text-orange-600 mb-1">Rp {{ number_format($totalOps, 0, ',', '.') }}</h3>
        <div class="font-label text-[0.65rem] sm:text-xs uppercase tracking-widest text-outline font-bold">Realisasi Operasional</div>
    </div>
</div>

{{-- ══ VISUALISASI ALLOKASI FIXED PROFIT FIRST ══ --}}
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] p-6 mb-8">
    <div class="flex items-center gap-2 mb-3">
        <div class="w-1 h-6 bg-secondary rounded-full"></div>
        <h3 class="font-headline text-base text-primary font-bold">Alokasi Anggaran: Fixed Profit First</h3>
    </div>
    <p class="font-body text-xs text-on-surface-variant mb-4 leading-relaxed">
        Sistem mengamankan <strong>Laba Tetap Sanggar</strong> terlebih dahulu di setiap pementasan yang disetujui, kemudian mengalokasikan sisanya untuk <strong>Honor Kru & Penari</strong> serta <strong>Biaya Operasional Lapangan</strong>.
    </p>
    
    @php
        $profitPct = $totalRevenue > 0 ? round(($totalProfit / $totalRevenue) * 100, 1) : 0;
        $honorPct = $totalRevenue > 0 ? round(($totalHonor / $totalRevenue) * 100, 1) : 0;
        $budgetPct = $totalRevenue > 0 ? round(($totalBudgetOps / $totalRevenue) * 100, 1) : 0;
        // Penyesuaian agar total persentase pas 100% jika ada desimal pembulatan
        $totalPct = $profitPct + $honorPct + $budgetPct;
        if ($totalPct > 100) $budgetPct = max(0, $budgetPct - ($totalPct - 100));
        if ($totalPct < 100 && $totalPct > 0) $budgetPct = $budgetPct + (100 - $totalPct);
    @endphp

    <div class="w-full bg-surface-container-high rounded-xl h-7 overflow-hidden flex mb-5 border border-outline-variant/20">
        @if($profitPct > 0)
        <div class="h-full bg-[#361f1a] text-[#fcd400] flex items-center justify-center font-label text-[0.65rem] font-extrabold transition-all" style="width: {{ $profitPct }}%" title="Laba Tetap: {{ $profitPct }}%">
            {{ $profitPct }}% Laba Tetap
        </div>
        @endif
        @if($honorPct > 0)
        <div class="h-full bg-[#fcd400] text-[#6e5c00] flex items-center justify-center font-label text-[0.65rem] font-extrabold transition-all" style="width: {{ $honorPct }}%" title="Honor Kru: {{ $honorPct }}%">
            {{ $honorPct }}% Honor Kru
        </div>
        @endif
        @if($budgetPct > 0)
        <div class="h-full bg-[#e9e8e5] text-[#504442] flex items-center justify-center font-label text-[0.65rem] font-extrabold transition-all" style="width: {{ $budgetPct }}%" title="Anggaran Ops: {{ $budgetPct }}%">
            {{ $budgetPct }}% Anggaran Ops
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
        <div class="flex items-start gap-3 p-3 rounded-lg bg-primary/5 border border-primary/10">
            <span class="w-3.5 h-3.5 rounded bg-[#361f1a] shrink-0 mt-0.5"></span>
            <div>
                <div class="font-bold text-primary">1. Laba Tetap Sanggar (Fixed Profit)</div>
                <div class="text-[0.7rem] text-outline mt-1 leading-relaxed">Dana aman sanggar yang langsung dipotong di awal sebelum pembagian honor lapangan.</div>
                <div class="font-headline font-bold text-sm text-[#361f1a] mt-2">Rp {{ number_format($totalProfit, 0, ',', '.') }}</div>
            </div>
        </div>
        
        <div class="flex items-start gap-3 p-3 rounded-lg bg-secondary/5 border border-secondary/15">
            <span class="w-3.5 h-3.5 rounded bg-[#fcd400] shrink-0 mt-0.5"></span>
            <div>
                <div class="font-bold text-on-secondary-container">2. Alokasi Honor Personel</div>
                <div class="text-[0.7rem] text-outline mt-1 leading-relaxed">Total hak bayaran yang dialokasikan untuk kru, penari, dan pemusik yang bertugas.</div>
                <div class="font-headline font-bold text-sm text-[#705d00] mt-2">Rp {{ number_format($totalHonor, 0, ',', '.') }}</div>
            </div>
        </div>
        
        <div class="flex items-start gap-3 p-3 rounded-lg bg-surface-container border border-outline-variant/30">
            <span class="w-3.5 h-3.5 rounded bg-[#e3e2e0] shrink-0 mt-0.5"></span>
            <div>
                <div class="font-bold text-on-surface-variant">3. Anggaran Operasional Lapangan</div>
                <div class="text-[0.7rem] text-outline mt-1 leading-relaxed">Plafon anggaran untuk biaya logistik, konsumsi, sewa kostum vendor luar, dsb.</div>
                <div class="font-headline font-bold text-sm text-on-surface mt-2">Rp {{ number_format($totalBudgetOps, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Header --}}
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
    <h2 class="font-headline text-xl text-primary font-semibold">
        <i class="bi bi-activity text-secondary me-1"></i> Laporan Keuangan per Event
    </h2>
    <a href="{{ route('admin.financials.export_pdf', request()->only(['search','date_from','date_to'])) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-widest hover:bg-red-700 transition-colors shadow-md whitespace-nowrap">
        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Unduh Laporan PDF
    </a>
</div>

{{-- Filter Form --}}
<form method="GET" action="{{ route('admin.financials.index') }}" class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl p-4 mb-6 shadow-sm">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
        <div>
            <label class="block font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1.5">Cari Kode / Jenis Acara</label>
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-outline text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full pl-8 pr-4 py-2.5 bg-surface-container-low border border-outline-variant/50 rounded-lg font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                       placeholder="Contoh: ARH-001 atau Jaipong">
            </div>
        </div>
        <div>
            <label class="block font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1.5">Tanggal Dari</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="w-full px-4 py-2.5 bg-surface-container-low border border-outline-variant/50 rounded-lg font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
        </div>
        <div>
            <label class="block font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1.5">Tanggal Sampai</label>
            <div class="flex gap-2">
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="flex-1 px-4 py-2.5 bg-surface-container-low border border-outline-variant/50 rounded-lg font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                <button type="submit" class="flex-shrink-0 px-4 py-2.5 bg-primary text-white rounded-lg font-label text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all">
                    <i class="bi bi-funnel-fill"></i>
                </button>
                @if(request()->hasAny(['search','date_from','date_to']))
                <a href="{{ route('admin.financials.index') }}" class="flex-shrink-0 flex items-center px-3 py-2.5 bg-surface-container border border-outline-variant/30 text-on-surface-variant rounded-lg font-label text-xs font-bold hover:bg-red-500 hover:text-white hover:border-red-500 transition-all" title="Reset Filter">
                    <i class="bi bi-x-lg"></i>
                </a>
                @endif
            </div>
        </div>
    </div>
    @if(request()->hasAny(['search','date_from','date_to']))
    <div class="mt-3 flex items-center gap-2 font-label text-xs text-secondary font-bold">
        <i class="bi bi-funnel-fill"></i> Filter aktif — menampilkan {{ $records->total() }} hasil
    </div>
    @endif
</form>

{{-- ══ TABLE (Desktop) ══ --}}
<div class="hidden md:block bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden overflow-x-auto w-full">
    <table class="w-full min-w-[900px]">
        <thead class="bg-surface-container-low">
            <tr>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Event</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Pendapatan</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Laba Pimpinan (Fixed)</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Honor Kru</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Budget Ops</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Realisasi Ops</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">
                    Efisiensi Ops 
                    <i class="bi bi-info-circle ml-1 text-on-surface-variant cursor-help" title="Selisih antara Budget Operasional dengan Biaya Riil (Realisasi Ops). Positif = Efisien/Sisa Budget. Negatif = Melebihi Anggaran."></i>
                </th>
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


{{-- ══ MOBILE CARDS (Mobile only) ══ --}}
<div class="md:hidden space-y-3">
    @forelse($records as $r)
    @php $selisih = $r->operational_budget - $r->actual_operational_cost; @endphp
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 bg-surface-container-low border-b border-outline-variant/20">
            <div>
                <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">{{ $r->event->event_code ?? '-' }}</span>
                <div class="font-label text-[0.6rem] text-outline mt-0.5 capitalize">{{ str_replace('_', ' ', $r->event->booking->event_type ?? '') }}</div>
            </div>
            <a href="{{ route('admin.financials.post_event', $r->event->id ?? 0) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary text-white font-label text-[0.6rem] font-bold uppercase tracking-wider hover:bg-primary-container transition-colors">
                <i class="bi bi-eye-fill"></i> Post-Event
            </a>
        </div>
        <div class="px-4 py-3 grid grid-cols-2 gap-2">
            <div class="bg-surface-container rounded-lg p-2.5">
                <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline mb-0.5">Pendapatan</div>
                <div class="font-headline font-bold text-xs text-on-surface">Rp {{ number_format($r->total_revenue, 0, ',', '.') }}</div>
            </div>
            <div class="bg-primary/5 border border-primary/10 rounded-lg p-2.5">
                <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline mb-0.5">Laba Tetap</div>
                <div class="font-headline font-bold text-xs text-primary">Rp {{ number_format($r->fixed_profit, 0, ',', '.') }}</div>
                <div class="font-label text-[0.5rem] text-outline">{{ $r->fixed_profit_pct }}% dr kontrak</div>
            </div>
            <div class="bg-surface-container rounded-lg p-2.5">
                <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline mb-0.5">Honor Kru</div>
                <div class="font-headline font-bold text-xs text-on-surface-variant">Rp {{ number_format($r->total_personnel_honor, 0, ',', '.') }}</div>
            </div>
            <div class="{{ $selisih >= 0 ? 'bg-green-500/5 border border-green-500/10' : 'bg-red-500/5 border border-red-500/10' }} rounded-lg p-2.5">
                <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline mb-0.5">Efisiensi Ops</div>
                <div class="font-headline font-bold text-xs {{ $selisih >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ $selisih >= 0 ? '+' : '-' }}Rp {{ number_format(abs($selisih), 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    @empty
    <div class="py-14 flex flex-col items-center justify-center bg-surface-container-lowest border border-dashed border-outline-variant/30 rounded-xl text-center">
        <i class="bi bi-cash-stack text-4xl text-outline mb-3"></i>
        <p class="font-headline text-base text-on-surface font-semibold">Belum ada data keuangan</p>
    </div>
    @endforelse
</div>

@if ($records->hasPages())
<div class="mt-4 px-1">
    {{ $records->links() }}
</div>
@endif

{{-- ══ SECTION POST-EVENT UPDATE (Digabung) ══ --}}
<div class="mt-10">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
        <div class="flex items-center gap-3">
            <h2 class="font-headline text-xl text-primary font-semibold flex items-center gap-2">
                <i class="bi bi-clipboard-check-fill text-secondary"></i> Input Biaya Pasca-Event
            </h2>
            @if($pendingCount > 0)
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-500 text-white font-label text-[0.65rem] font-bold animate-pulse">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ $pendingCount }} Belum Diisi
            </span>
            @else
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-500/10 text-green-600 border border-green-500/20 font-label text-[0.65rem] font-bold">
                <i class="bi bi-check-circle-fill"></i> Semua Terisi
            </span>
            @endif
        </div>
        <p class="font-label text-xs uppercase tracking-widest text-outline">Catat biaya riil lapangan pasca pementasan (bensin, makan, dll)</p>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
        <div class="divide-y divide-outline-variant/20">
            @forelse($postEvents as $event)
            @php
                $booking  = $event->booking;
                $finance  = $event->financialRecord;
                $costs    = $finance?->operationalCosts;
                $totalCost= $costs?->sum('actual_amount') ?? 0;
                $budget   = $finance?->operational_budget ?? 0;
                $pct      = $budget > 0 ? min(100, round($totalCost / $budget * 100)) : 0;
                $isDone      = $costs && $costs->count() > 0;
                $budgetColor = $pct >= 100 ? 'bg-red-500' : ($pct >= 80 ? 'bg-orange-500' : 'bg-green-500');
                $textColor   = $pct >= 100 ? 'text-red-600' : ($pct >= 80 ? 'text-orange-600' : 'text-green-600');
            @endphp
            <div class="flex flex-col sm:flex-row sm:items-center p-5 gap-5 hover:bg-surface-container-low/50 transition-colors">
                {{-- Event Info --}}
                <div class="flex-grow">
                    <div class="flex items-center gap-3 mb-1.5 flex-wrap">
                        <span class="font-headline font-semibold text-primary">{{ $booking->client_name ?? 'Event Sanggar' }}</span>
                        <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">
                            {{ $event->event_code }}
                        </span>
                        @if($isDone)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded border border-green-500/20 bg-green-500/10 font-label text-[0.6rem] font-bold uppercase tracking-wider text-green-600">
                                <i class="bi bi-check-circle-fill"></i> Biaya Terinput
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded border border-orange-500/20 bg-orange-500/10 font-label text-[0.6rem] font-bold uppercase tracking-wider text-orange-600">
                                <i class="bi bi-exclamation-circle-fill"></i> Belum Input
                            </span>
                        @endif
                    </div>
                    <div class="font-label text-xs text-on-surface-variant flex items-center gap-3">
                        <span class="flex items-center gap-1"><i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</span>
                        <span class="text-outline-variant">•</span>
                        <span class="flex items-center gap-1 truncate"><i class="bi bi-geo-alt"></i> {{ $event->venue }}</span>
                    </div>
                </div>

                {{-- Budget progress --}}
                @if($finance)
                <div class="min-w-[180px] w-full sm:w-auto">
                    <div class="flex justify-between font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline mb-1.5">
                        <span>Terpakai</span>
                        <span class="{{ $textColor }}">{{ $pct }}%</span>
                    </div>
                    @php $widthStyle = "width: {$pct}%"; @endphp
                    <div class="h-2 w-full bg-surface-container-highest rounded-full overflow-hidden mb-1.5">
                        <div class="h-full {{ $budgetColor }} rounded-full transition-all duration-500" style="{{ $widthStyle }}"></div>
                    </div>
                    <div class="flex justify-between font-body text-xs text-on-surface-variant">
                        <span>Rp {{ number_format($totalCost, 0, ',', '.') }}</span>
                        <span class="text-outline">/ Rp {{ number_format($budget, 0, ',', '.') }}</span>
                    </div>
                </div>
                @else
                <div class="min-w-[180px] text-center w-full sm:w-auto">
                    <span class="font-label text-xs uppercase tracking-widest text-outline">Finansial belum terbentuk</span>
                </div>
                @endif

                {{-- CTA --}}
                <div class="flex-shrink-0 w-full sm:w-auto">
                    @if($finance)
                        <a href="{{ route('admin.financials.post_event', $event->id) }}"
                           class="w-full sm:w-auto inline-flex justify-center items-center gap-2 {{ $isDone ? 'border border-green-500/30 text-green-600 hover:bg-green-500/10' : 'bg-gradient-to-br from-primary-container to-primary text-white shadow-md hover:opacity-90' }} px-5 py-2 rounded-lg transition-all font-label text-xs font-bold uppercase tracking-widest">
                            <i class="bi bi-{{ $isDone ? 'pencil-fill' : 'clipboard-plus-fill' }}"></i>
                            {{ $isDone ? 'Edit Biaya' : 'Input Biaya' }}
                        </a>
                    @else
                    <span class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2 rounded-lg bg-surface-container text-outline font-label text-xs font-bold uppercase tracking-widest cursor-not-allowed">
                        Konfirmasi DP Dulu
                    </span>
                    @endif
                </div>
            </div>
            @empty
            <div class="px-6 py-14 text-center">
                <i class="bi bi-calendar-check text-4xl text-outline mb-4 block"></i>
                <p class="font-headline text-lg text-on-surface font-semibold mb-1">Belum ada pementasan selesai</p>
                <p class="font-label text-xs uppercase tracking-widest text-outline">Pementasan yang sudah selesai akan muncul di sini</p>
            </div>
            @endforelse
        </div>

        @if($postEvents->hasPages())
        <div class="px-6 py-4 border-t border-outline-variant/20 bg-surface-container-low/20">
            {{ $postEvents->links() }}
        </div>
        @endif
    </div>
</div>

@else
{{-- JIKA BUKAN VVIP --}}
<div class="px-6 py-20 text-center rounded-xl bg-red-500/5 border border-red-500/20 max-w-2xl mx-auto mt-10">
    <div class="w-24 h-24 rounded-full bg-red-500/10 flex items-center justify-center mx-auto mb-6">
        <i class="bi bi-shield-lock-fill text-5xl text-red-500"></i>
    </div>
    <h3 class="font-headline text-2xl text-red-700 font-bold mb-3">Akses Ditolak (Hanya Pimpinan)</h3>
    <p class="font-body text-sm text-on-surface-variant leading-relaxed">
        Laporan finansial, fixed profit, dan buffer budget adalah area khusus Pimpinan Sanggar.<br> 
        Akun Anda tidak memiliki otoritas untuk melihat data sensitif ini.
    </p>
</div>
@endcan

@endsection
