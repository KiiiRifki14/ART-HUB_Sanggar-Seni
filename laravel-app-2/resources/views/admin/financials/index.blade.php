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
    <div class="card-gold text-white p-4 sm:p-6 text-center relative overflow-hidden col-span-2 lg:col-span-1" style="background:linear-gradient(135deg, #8B1A2A, #5C0E19);">
        <div class="absolute -right-6 -bottom-6 text-white/5">
            <i data-lucide="shield-check" class="w-32 h-32"></i>
        </div>
        <div class="relative z-10">
            <i data-lucide="shield-check" class="w-8 h-8 mx-auto mb-3 text-yellow-500"></i>
            <h3 class="title-gold mb-1" style="font-size:2rem; color:#fcd400;">Rp {{ number_format($totalProfit, 0, ',', '.') }}</h3>
            <div class="subtitle-gold" style="font-size:0.65rem; color:rgba(255,255,255,0.7);">Total Laba Tetap</div>
        </div>
    </div>
    <div class="card-gold p-4 sm:p-6 text-center" style="border-color:rgba(22,163,74,0.3);">
        <i data-lucide="shield" class="w-7 h-7 mx-auto mb-2 sm:mb-3 text-green-500"></i>
        <h3 class="title-gold mb-1" style="font-size:1.8rem; color:#16a34a;">Rp {{ number_format($totalBuffer, 0, ',', '.') }}</h3>
        <div class="subtitle-gold" style="font-size:0.65rem;">Dana Cadangan</div>
    </div>
    <div class="card-gold p-4 sm:p-6 text-center">
        <i data-lucide="trending-up" class="w-7 h-7 mx-auto mb-2 sm:mb-3 text-gray-500"></i>
        <h3 class="title-gold mb-1" style="font-size:1.8rem; color:#1A1817;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        <div class="subtitle-gold" style="font-size:0.65rem;">Total Nilai Kontrak</div>
    </div>
    <div class="card-gold p-4 sm:p-6 text-center" style="border-color:rgba(234,88,12,0.3);">
        <i data-lucide="banknote" class="w-7 h-7 mx-auto mb-2 sm:mb-3 text-orange-500"></i>
        <h3 class="title-gold mb-1" style="font-size:1.8rem; color:#ea580c;">Rp {{ number_format($totalOps, 0, ',', '.') }}</h3>
        <div class="subtitle-gold" style="font-size:0.65rem;">Realisasi Operasional</div>
    </div>
</div>

{{-- ══ VISUALISASI ALLOKASI FIXED PROFIT FIRST ══ --}}
<div class="card-gold p-6 mb-8">
    <div class="flex items-center gap-2 mb-3">
        <div class="w-1 h-6 rounded-full" style="background:#fcd400;"></div>
        <h3 class="title-gold" style="font-size:1.1rem;">Alokasi Anggaran: Fixed Profit First</h3>
    </div>
    <p class="font-body text-xs mb-4 leading-relaxed" style="color:#504442;">
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

    <div class="w-full rounded-xl h-7 overflow-hidden flex mb-5 border" style="background:rgba(197,160,40,0.1); border-color:rgba(197,160,40,0.2);">
        @if($profitPct > 0)
        <div class="h-full flex items-center justify-center font-bold text-[0.65rem] transition-all" style="background:#8B1A2A; color:#fcd400; width: {{ $profitPct }}%" title="Laba Tetap: {{ $profitPct }}%">
            {{ $profitPct }}% Laba Tetap
        </div>
        @endif
        @if($honorPct > 0)
        <div class="h-full flex items-center justify-center font-bold text-[0.65rem] transition-all" style="background:#fcd400; color:#8B1A2A; width: {{ $honorPct }}%" title="Honor Kru: {{ $honorPct }}%">
            {{ $honorPct }}% Honor Kru
        </div>
        @endif
        @if($budgetPct > 0)
        <div class="h-full flex items-center justify-center font-bold text-[0.65rem] transition-all" style="background:#e5e7eb; color:#4b5563; width: {{ $budgetPct }}%" title="Anggaran Ops: {{ $budgetPct }}%">
            {{ $budgetPct }}% Anggaran Ops
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
        <div class="flex items-start gap-3 p-3 rounded-lg" style="background:rgba(139,26,42,0.05); border:1px solid rgba(139,26,42,0.1);">
            <span class="w-3.5 h-3.5 rounded shrink-0 mt-0.5" style="background:#8B1A2A;"></span>
            <div>
                <div class="font-bold" style="color:#8B1A2A;">1. Laba Tetap Sanggar (Fixed Profit)</div>
                <div class="text-[0.7rem] mt-1 leading-relaxed" style="color:#847B78;">Dana aman sanggar yang langsung dipotong di awal sebelum pembagian honor lapangan.</div>
                <div class="font-bold text-sm mt-2" style="font-family:'Inter',sans-serif; color:#8B1A2A;">Rp {{ number_format($totalProfit, 0, ',', '.') }}</div>
            </div>
        </div>
        
        <div class="flex items-start gap-3 p-3 rounded-lg" style="background:rgba(197,160,40,0.05); border:1px solid rgba(197,160,40,0.2);">
            <span class="w-3.5 h-3.5 rounded shrink-0 mt-0.5" style="background:#fcd400;"></span>
            <div>
                <div class="font-bold" style="color:#8B1A2A;">2. Alokasi Honor Personel</div>
                <div class="text-[0.7rem] mt-1 leading-relaxed" style="color:#847B78;">Total hak bayaran yang dialokasikan untuk kru, penari, dan pemusik yang bertugas.</div>
                <div class="font-bold text-sm mt-2" style="font-family:'Inter',sans-serif; color:#8B1A2A;">Rp {{ number_format($totalHonor, 0, ',', '.') }}</div>
            </div>
        </div>
        
        <div class="flex items-start gap-3 p-3 rounded-lg" style="background:rgba(255,255,255,0.8); border:1px solid rgba(197,160,40,0.3);">
            <span class="w-3.5 h-3.5 rounded shrink-0 mt-0.5" style="background:#e5e7eb;"></span>
            <div>
                <div class="font-bold" style="color:#504442;">3. Anggaran Operasional Lapangan</div>
                <div class="text-[0.7rem] mt-1 leading-relaxed" style="color:#847B78;">Plafon anggaran untuk biaya logistik, konsumsi, sewa kostum vendor luar, dsb.</div>
                <div class="font-bold text-sm mt-2" style="font-family:'Inter',sans-serif; color:#1A1817;">Rp {{ number_format($totalBudgetOps, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Header --}}
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
    <h2 class="title-gold font-semibold flex items-center" style="font-size:1.3rem;">
        <i data-lucide="activity" class="w-5 h-5 mr-2" style="color:#fcd400;"></i> Laporan Keuangan per Event
    </h2>
    <a href="{{ route('admin.financials.export_pdf', request()->only(['search','date_from','date_to'])) }}" class="arh-btn-primary px-4 py-2 text-xs" style="background:#dc2626; border:none; color:white;">
        <i data-lucide="file-text" class="w-4 h-4 mr-1 inline-block -mt-1"></i> Unduh Laporan PDF
    </a>
</div>

{{-- Filter Form --}}
<form method="GET" action="{{ route('admin.financials.index') }}" class="card-gold p-4 mb-6 shadow-sm">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
        <div>
            <label class="block subtitle-gold mb-1.5" style="font-size:0.65rem;">Cari Kode / Jenis Acara</label>
            <div class="relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="input-gold w-full" style="padding-left:36px; padding-top:10px; padding-bottom:10px;"
                       placeholder="Contoh: ARH-001 atau Jaipong">
            </div>
        </div>
        <div>
            <label class="block subtitle-gold mb-1.5" style="font-size:0.65rem;">Tanggal Dari</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="input-gold w-full" style="padding-top:10px; padding-bottom:10px;">
        </div>
        <div>
            <label class="block subtitle-gold mb-1.5" style="font-size:0.65rem;">Tanggal Sampai</label>
            <div class="flex gap-2">
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="input-gold flex-1" style="padding-top:10px; padding-bottom:10px;">
                <button type="submit" class="flex-shrink-0 px-4 py-2.5 arh-btn-primary" style="background:linear-gradient(135deg, #fcd400, #C5A028); color:#1A1817; border:none;">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                </button>
                @if(request()->hasAny(['search','date_from','date_to']))
                <a href="{{ route('admin.financials.index') }}" class="flex-shrink-0 flex items-center px-3 py-2.5 rounded-lg border border-red-500/30 text-red-500 font-bold hover:bg-red-500 hover:text-white transition-all" title="Reset Filter">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </a>
                @endif
            </div>
        </div>
    </div>
    @if(request()->hasAny(['search','date_from','date_to']))
    <div class="mt-3 flex items-center gap-2 subtitle-gold font-bold" style="color:#8B1A2A;">
        <i data-lucide="filter" class="w-3 h-3"></i> Filter aktif — menampilkan {{ $records->total() }} hasil
    </div>
    @endif
</form>

{{-- ══ TABLE (Desktop) ══ --}}
<div class="hidden md:block card-gold overflow-hidden overflow-x-auto w-full">
    <table class="w-full min-w-[900px] table-gold">
        <thead>
            <tr>
                <th class="text-left">Event</th>
                <th class="text-right">Pendapatan</th>
                <th class="text-right">Laba Pimpinan (Fixed)</th>
                <th class="text-right">Honor Kru</th>
                <th class="text-right">Budget Ops</th>
                <th class="text-right">Realisasi Ops</th>
                <th class="text-right">
                    Efisiensi Ops 
                    <i data-lucide="info" class="w-3 h-3 inline-block ml-1 text-gray-400 cursor-help" title="Selisih antara Budget Operasional dengan Biaya Riil (Realisasi Ops). Positif = Efisien/Sisa Budget. Negatif = Melebihi Anggaran."></i>
                </th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $r)
            @php 
                $selisih = $r->operational_budget - $r->actual_operational_cost; 
            @endphp
            <tr>
                <td>
                    <span class="badge-gold mb-1">
                        {{ $r->event->event_code ?? '-' }}
                    </span>
                    <div class="subtitle-gold capitalize" style="font-size:0.65rem; text-transform:none;">{{ str_replace('_', ' ', $r->event->booking->event_type ?? '') }}</div>
                </td>
                <td class="text-right" style="font-family:'Inter',sans-serif; color:#1A1817; font-weight:600;">
                    Rp {{ number_format($r->total_revenue, 0, ',', '.') }}
                </td>
                <td class="text-right">
                    <div style="font-weight:700; color:#8B1A2A; font-size:1.1rem;">Rp {{ number_format($r->fixed_profit, 0, ',', '.') }}</div>
                    <div class="subtitle-gold" style="font-size:0.6rem;">{{ $r->fixed_profit_pct }}% dr Kontrak</div>
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
                <td class="text-center">
                    <a href="{{ route('admin.financials.post_event', $r->event->id ?? 0) }}" 
                       class="arh-btn-secondary py-1.5 px-3">
                        <i data-lucide="eye" class="w-4 h-4 mr-1 inline-block -mt-1"></i> Post-Event
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="py-16 text-center">
                    <i data-lucide="banknote" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                    <p class="title-gold" style="font-size:1.2rem; margin-bottom:4px;">Belum ada data keuangan</p>
                    <p class="subtitle-gold" style="font-size:0.7rem;">Data akan terbentuk setelah DP masuk</p>
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
    <div class="card-gold overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.02);">
            <div>
                <span class="badge-gold">{{ $r->event->event_code ?? '-' }}</span>
                <div class="subtitle-gold mt-1 capitalize" style="font-size:0.6rem; text-transform:none;">{{ str_replace('_', ' ', $r->event->booking->event_type ?? '') }}</div>
            </div>
            <a href="{{ route('admin.financials.post_event', $r->event->id ?? 0) }}" class="arh-btn-primary py-1 px-3 text-xs" style="background:linear-gradient(135deg, #fcd400, #C5A028); color:#1A1817; border:none;">
                <i data-lucide="eye" class="w-3 h-3 mr-1 inline-block -mt-0.5"></i> Post-Event
            </a>
        </div>
        <div class="px-4 py-3 grid grid-cols-2 gap-2">
            <div class="rounded-lg p-2.5" style="background:rgba(255,255,255,0.5); border:1px solid rgba(197,160,40,0.2);">
                <div class="subtitle-gold mb-1" style="font-size:0.55rem;">Pendapatan</div>
                <div style="font-weight:700; color:#1A1817; font-size:0.85rem;">Rp {{ number_format($r->total_revenue, 0, ',', '.') }}</div>
            </div>
            <div class="rounded-lg p-2.5" style="background:rgba(139,26,42,0.05); border:1px solid rgba(139,26,42,0.1);">
                <div class="subtitle-gold mb-1" style="font-size:0.55rem;">Laba Tetap</div>
                <div style="font-weight:700; color:#8B1A2A; font-size:0.85rem;">Rp {{ number_format($r->fixed_profit, 0, ',', '.') }}</div>
                <div class="subtitle-gold" style="font-size:0.5rem; text-transform:none; letter-spacing:normal;">{{ $r->fixed_profit_pct }}% dr kontrak</div>
            </div>
            <div class="rounded-lg p-2.5" style="background:rgba(255,255,255,0.5); border:1px solid rgba(197,160,40,0.2);">
                <div class="subtitle-gold mb-1" style="font-size:0.55rem;">Honor Kru</div>
                <div style="font-weight:700; color:#504442; font-size:0.85rem;">Rp {{ number_format($r->total_personnel_honor, 0, ',', '.') }}</div>
            </div>
            <div class="rounded-lg p-2.5" style="{{ $selisih >= 0 ? 'background:rgba(22,163,74,0.05); border:1px solid rgba(22,163,74,0.1);' : 'background:rgba(239,68,68,0.05); border:1px solid rgba(239,68,68,0.1);' }}">
                <div class="subtitle-gold mb-1" style="font-size:0.55rem;">Efisiensi Ops</div>
                <div style="font-weight:700; font-size:0.85rem; {{ $selisih >= 0 ? 'color:#16a34a;' : 'color:#ef4444;' }}">{{ $selisih >= 0 ? '+' : '-' }}Rp {{ number_format(abs($selisih), 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    @empty
    <div class="py-14 flex flex-col items-center justify-center card-gold border-dashed text-center">
        <i data-lucide="banknote" class="w-10 h-10 text-gray-300 mb-3"></i>
        <p class="title-gold" style="font-size:1.1rem;">Belum ada data keuangan</p>
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
            <h2 class="title-gold font-semibold flex items-center gap-2" style="font-size:1.3rem;">
                <i data-lucide="clipboard-check" class="w-5 h-5" style="color:#fcd400;"></i> Input Biaya Pasca-Event
            </h2>
            @if($pendingCount > 0)
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-white font-bold animate-pulse" style="background:#ea580c; font-size:0.65rem;">
                <i data-lucide="alert-triangle" class="w-3 h-3"></i> {{ $pendingCount }} Belum Diisi
            </span>
            @else
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-bold" style="background:rgba(22,163,74,0.1); color:#16a34a; border:1px solid rgba(22,163,74,0.2); font-size:0.65rem;">
                <i data-lucide="check-circle" class="w-3 h-3"></i> Semua Terisi
            </span>
            @endif
        </div>
        <p class="subtitle-gold" style="font-size:0.65rem;">Catat biaya riil lapangan pasca pementasan (bensin, makan, dll)</p>
    </div>

    <div class="card-gold overflow-hidden">
        <div class="divide-y" style="border-color:rgba(197,160,40,0.15);">
            @forelse($postEvents as $event)
            @php
                $booking  = $event->booking;
                $finance  = $event->financialRecord;
                $costs    = $finance?->operationalCosts;
                $totalCost= $costs?->sum('actual_amount') ?? 0;
                $budget   = $finance?->operational_budget ?? 0;
                $pct      = $budget > 0 ? min(100, round($totalCost / $budget * 100)) : 0;
                $isDone      = $costs && $costs->count() > 0;
                $budgetColor = $pct >= 100 ? 'background:#ef4444;' : ($pct >= 80 ? 'background:#f97316;' : 'background:#22c55e;');
                $textColor   = $pct >= 100 ? 'color:#dc2626;' : ($pct >= 80 ? 'color:#ea580c;' : 'color:#16a34a;');
            @endphp
            <div class="flex flex-col sm:flex-row sm:items-center p-5 gap-5 transition-colors" style="background:rgba(255,255,255,0.01);">
                {{-- Event Info --}}
                <div class="flex-grow">
                    <div class="flex items-center gap-3 mb-1.5 flex-wrap">
                        <span class="title-gold" style="font-size:1.1rem; color:#8B1A2A; text-transform:none;">{{ $booking->client_name ?? 'Event Sanggar' }}</span>
                        <span class="badge-gold">
                            {{ $event->event_code }}
                        </span>
                        @if($isDone)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded border font-bold uppercase tracking-wider" style="border-color:rgba(34,197,94,0.2); background:rgba(34,197,94,0.1); color:#16a34a; font-size:0.6rem;">
                                <i data-lucide="check-circle" class="w-3 h-3"></i> Biaya Terinput
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded border font-bold uppercase tracking-wider" style="border-color:rgba(249,115,22,0.2); background:rgba(249,115,22,0.1); color:#ea580c; font-size:0.6rem;">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i> Belum Input
                            </span>
                        @endif
                    </div>
                    <div class="subtitle-gold flex items-center gap-3 flex-wrap" style="font-size:0.65rem;">
                        <span class="flex items-center gap-1"><i data-lucide="calendar" class="w-3 h-3"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</span>
                        <span style="color:rgba(132,123,120,0.5);">•</span>
                        <span class="flex items-center gap-1 truncate"><i data-lucide="map-pin" class="w-3 h-3"></i> {{ $event->venue }}</span>
                    </div>
                </div>

                {{-- Budget progress --}}
                @if($finance)
                <div class="min-w-[180px] w-full sm:w-auto">
                    <div class="flex justify-between subtitle-gold mb-1.5" style="font-size:0.65rem;">
                        <span>Terpakai</span>
                        <span style="{{ $textColor }}">{{ $pct }}%</span>
                    </div>
                    @php $widthStyle = "width: {$pct}%"; @endphp
                    <div class="h-2 w-full rounded-full overflow-hidden mb-1.5" style="background:rgba(197,160,40,0.1);">
                        <div class="h-full rounded-full transition-all duration-500" style="{{ $budgetColor }} {{ $widthStyle }}"></div>
                    </div>
                    <div class="flex justify-between" style="font-size:0.75rem; color:#847B78;">
                        <span style="font-weight:600; color:#1A1817;">Rp {{ number_format($totalCost, 0, ',', '.') }}</span>
                        <span>/ Rp {{ number_format($budget, 0, ',', '.') }}</span>
                    </div>
                </div>
                @else
                <div class="min-w-[180px] text-center w-full sm:w-auto">
                    <span class="subtitle-gold" style="font-size:0.65rem;">Finansial belum terbentuk</span>
                </div>
                @endif

                {{-- CTA --}}
                <div class="flex-shrink-0 w-full sm:w-auto">
                    @if($finance)
                        <a href="{{ route('admin.financials.post_event', $event->id) }}"
                           class="w-full sm:w-auto inline-flex justify-center items-center gap-2 {{ $isDone ? 'arh-btn-secondary' : 'arh-btn-primary' }} px-5 py-2 transition-all"
                           @if(!$isDone) style="background:linear-gradient(135deg, #fcd400, #C5A028); color:#1A1817; border:none;" @endif>
                            <i data-lucide="{{ $isDone ? 'edit-2' : 'clipboard-edit' }}" class="w-4 h-4 -mt-0.5"></i>
                            {{ $isDone ? 'Edit Biaya' : 'Input Biaya' }}
                        </a>
                    @else
                    <span class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2 rounded-lg font-bold text-xs uppercase tracking-widest cursor-not-allowed" style="background:#f3f4f6; color:#9ca3af;">
                        Konfirmasi DP Dulu
                    </span>
                    @endif
                </div>
            </div>
            @empty
            <div class="px-6 py-14 text-center">
                <i data-lucide="calendar-check" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                <p class="title-gold" style="font-size:1.1rem; margin-bottom:4px;">Belum ada pementasan selesai</p>
                <p class="subtitle-gold" style="font-size:0.65rem;">Pementasan yang sudah selesai akan muncul di sini</p>
            </div>
            @endforelse
        </div>

        @if($postEvents->hasPages())
        <div class="px-6 py-4 border-t" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.01);">
            {{ $postEvents->links() }}
        </div>
        @endif
    </div>
</div>

@else
{{-- JIKA BUKAN VVIP --}}
<div class="px-6 py-20 text-center rounded-xl max-w-2xl mx-auto mt-10" style="background:rgba(239,68,68,0.05); border:1px solid rgba(239,68,68,0.2);">
    <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6" style="background:rgba(239,68,68,0.1);">
        <i data-lucide="shield-alert" class="w-12 h-12 text-red-500"></i>
    </div>
    <h3 class="title-gold mb-3" style="font-size:1.5rem; color:#b91c1c;">Akses Ditolak (Hanya Pimpinan)</h3>
    <p class="font-body text-sm leading-relaxed" style="color:#847B78;">
        Laporan finansial, fixed profit, dan buffer budget adalah area khusus Pimpinan Sanggar.<br> 
        Akun Anda tidak memiliki otoritas untuk melihat data sensitif ini.
    </p>
</div>
@endcan

@endsection
