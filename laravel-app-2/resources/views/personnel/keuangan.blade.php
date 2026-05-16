@extends('layouts.personnel')
@section('title', 'Keuangan – Portal Kru ART-HUB')

@section('content')

{{-- Header --}}
<div class="fu flex items-center gap-3 mb-5">
    <a href="{{ route('personnel.dashboard') }}" class="w-9 h-9 flex items-center justify-center rounded-xl transition-colors" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.5)">
        <i class="bi bi-arrow-left text-sm"></i>
    </a>
    <div>
        <div class="font-head font-bold text-white text-xl">Keuangan Saya</div>
        <div class="text-xs" style="color:rgba(255,255,255,0.35)">Rekapitulasi honor & denda</div>
    </div>
</div>

{{-- Summary Cards --}}
<div class="fu1 grid grid-cols-3 gap-3 mb-5">
    <div class="rounded-2xl p-3.5 text-center" style="background:rgba(74,222,128,0.07);border:1px solid rgba(74,222,128,0.18)">
        <div class="font-head font-bold text-green-400 leading-none mb-1" style="font-size:1.3rem">
            {{ $totalEarned > 0 ? 'Rp'.number_format($totalEarned/1000000,1).'jt' : '–' }}
        </div>
        <div class="text-[0.55rem] uppercase tracking-widest text-green-400/60 font-bold">Diterima</div>
    </div>
    <div class="rounded-2xl p-3.5 text-center" style="background:rgba(197,160,40,0.07);border:1px solid rgba(197,160,40,0.18)">
        <div class="font-head font-bold text-gold leading-none mb-1" style="font-size:1.3rem">
            {{ $totalPending > 0 ? 'Rp'.number_format($totalPending/1000000,1).'jt' : '–' }}
        </div>
        <div class="text-[0.55rem] uppercase tracking-widest font-bold" style="color:rgba(197,160,40,0.6)">Pending</div>
    </div>
    <div class="rounded-2xl p-3.5 text-center" style="background:rgba(239,68,68,0.07);border:1px solid rgba(239,68,68,0.18)">
        <div class="font-head font-bold text-red-400 leading-none mb-1" style="font-size:1.3rem">
            {{ $totalPenalty > 0 ? 'Rp'.number_format($totalPenalty/1000,0).'rb' : '–' }}
        </div>
        <div class="text-[0.55rem] uppercase tracking-widest text-red-400/60 font-bold">Denda</div>
    </div>
</div>

{{-- Event Financial List --}}
<div class="fu2 flex items-center gap-2 mb-3">
    <div style="width:3px;height:18px;background:linear-gradient(to bottom,#C5A028,rgba(197,160,40,0.2));border-radius:99px"></div>
    <div class="font-head font-bold text-white">Riwayat Honor</div>
</div>

@forelse($eventFinancials as $item)
@php
    $ev     = $item['event'];
    $eDate  = \Carbon\Carbon::parse($ev->event_date);
    $isDone = $item['event_status'] === 'completed';
    $isLate = $item['status'] === 'late';
    $net    = $item['fee'] - $item['penalty'];
@endphp
<div class="fu{{ $loop->index < 4 ? $loop->index+2 : 5 }} rounded-3xl p-4 mb-3" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09)">
    <div class="flex items-start justify-between gap-3 mb-3">
        <div class="flex items-center gap-3">
            {{-- Date --}}
            <div class="shrink-0 w-12 h-12 rounded-2xl flex flex-col items-center justify-center" style="background:rgba(197,160,40,0.1);border:1px solid rgba(197,160,40,0.2)">
                <span class="font-head font-bold leading-none text-gold" style="font-size:1.2rem">{{ $eDate->format('d') }}</span>
                <span class="text-[0.48rem] font-bold uppercase text-gold/80">{{ $eDate->format('M') }}</span>
            </div>
            <div>
                <div class="font-bold text-white text-sm leading-tight mb-0.5">{{ $ev->booking->client_name ?? 'Event Sanggar' }}</div>
                <div class="text-xs" style="color:rgba(255,255,255,0.4)">{{ $eDate->translatedFormat('d F Y') }}</div>
                <div class="text-[0.6rem] uppercase tracking-wider mt-0.5 font-bold" style="color:rgba(255,255,255,0.3)">{{ ucfirst(str_replace('_',' ',$item['role'])) }}</div>
            </div>
        </div>
        {{-- Event status badge --}}
        @if($isDone)
        <span class="shrink-0 px-2.5 py-1 rounded-full text-[0.58rem] font-bold uppercase tracking-widest" style="background:rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2)">Selesai</span>
        @else
        <span class="shrink-0 px-2.5 py-1 rounded-full text-[0.58rem] font-bold uppercase tracking-widest" style="background:rgba(197,160,40,0.1);color:#C5A028;border:1px solid rgba(197,160,40,0.2)">Pending</span>
        @endif
    </div>

    {{-- Financial breakdown --}}
    <div class="rounded-2xl overflow-hidden" style="background:rgba(0,0,0,0.2)">
        <div class="flex items-center justify-between px-4 py-2.5" style="border-bottom:1px solid rgba(255,255,255,0.05)">
            <span class="text-xs" style="color:rgba(255,255,255,0.45)">Honor Pokok</span>
            <span class="text-sm font-bold text-gold">Rp {{ number_format($item['fee'],0,',','.') }}</span>
        </div>
        @if($item['penalty'] > 0)
        <div class="flex items-center justify-between px-4 py-2.5" style="border-bottom:1px solid rgba(255,255,255,0.05)">
            <span class="text-xs flex items-center gap-1.5 text-red-400/80"><i class="bi bi-exclamation-triangle-fill text-xs"></i> Denda Keterlambatan ({{ $item['late_minutes'] }} mnt)</span>
            <span class="text-sm font-bold text-red-400">– Rp {{ number_format($item['penalty'],0,',','.') }}</span>
        </div>
        @endif
        <div class="flex items-center justify-between px-4 py-3">
            <span class="text-xs font-bold uppercase tracking-widest" style="color:rgba(255,255,255,0.4)">Total Bersih</span>
            <span class="text-base font-bold" style="color:{{ $isDone?'#4ade80':'#C5A028' }}">Rp {{ number_format($net,0,',','.') }}</span>
        </div>
    </div>

    {{-- Check-in Status --}}
    <div class="flex items-center gap-2 mt-3">
        @if($item['checked_in_at'])
            @if($isLate)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.6rem] font-bold uppercase tracking-widest" style="background:rgba(251,146,60,0.1);color:#fb923c;border:1px solid rgba(251,146,60,0.2)">
                <i class="bi bi-clock-history text-xs"></i> Terlambat {{ $item['late_minutes'] }} mnt
            </span>
            @else
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.6rem] font-bold uppercase tracking-widest" style="background:rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2)">
                <i class="bi bi-check-circle-fill text-xs"></i> Tepat Waktu
            </span>
            @endif
            <span class="text-xs" style="color:rgba(255,255,255,0.3)">{{ \Carbon\Carbon::parse($item['checked_in_at'])->format('H:i') }} WIB</span>
        @else
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.6rem] font-bold uppercase tracking-widest" style="background:rgba(255,255,255,0.04);color:rgba(255,255,255,0.3);border:1px solid rgba(255,255,255,0.08)">
            <i class="bi bi-clock text-xs"></i> Belum Check-in
        </span>
        @endif
    </div>
</div>
@empty
<div class="rounded-3xl p-10 text-center" style="background:rgba(255,255,255,0.025);border:1px dashed rgba(255,255,255,0.08)">
    <i class="bi bi-wallet2 text-4xl block mb-3" style="color:rgba(255,255,255,0.1)"></i>
    <div class="font-bold text-white mb-1">Belum Ada Riwayat Honor</div>
    <div class="text-sm" style="color:rgba(255,255,255,0.35)">Riwayat akan muncul setelah Anda dijadwalkan pada event.</div>
</div>
@endforelse

@endsection
