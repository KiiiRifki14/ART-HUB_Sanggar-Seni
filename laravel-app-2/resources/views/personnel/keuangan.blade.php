@extends('layouts.personnel')
@section('title', 'Keuangan Saya – Portal Kru ART-HUB')

@section('content')
<style>
    .fin-summary-card {
        border-radius: 18px; padding: 18px 16px;
        display: flex; flex-direction: column; align-items: flex-start; gap: 8px;
        transition: all 0.4s var(--easing-spring);
    }
    .fin-summary-card:hover { transform: translateY(-2px); }
    .fin-summary-icon {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center; font-size: 1rem;
    }
    .fin-table-row {
        display: grid; padding: 13px 16px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        transition: background 0.2s;
    }
    .fin-table-row:last-child { border-bottom: none; }
    .fin-table-row:hover { background: rgba(197,160,40,0.03); }
</style>

{{-- ══ PAGE HEADER ══ --}}
<div class="fu flex items-center gap-3 mb-5">
    <a href="{{ route('personnel.dashboard') }}"
       style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:#F4F2EE;border:1px solid rgba(0,0,0,0.08);color:#4D4946;text-decoration:none;flex-shrink:0;transition:all 0.3s var(--easing-spring)">
        <i class="bi bi-arrow-left" style="font-size:0.9rem"></i>
    </a>
    <div>
        <div style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:700;color:#1A1817;line-height:1">Keuangan Saya</div>
        <div style="font-size:0.75rem;color:#847B78;margin-top:2px">Rekapitulasi honor & denda keterlambatan</div>
    </div>
</div>

{{-- ══ SUMMARY CARDS ══ --}}
<div class="fu1" style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px">

    {{-- Total Diterima --}}
    <div class="fin-summary-card" style="background:rgba(22,163,74,0.07);border:1px solid rgba(22,163,74,0.18)">
        <div class="fin-summary-icon" style="background:rgba(22,163,74,0.12);color:#16a34a">
            <i class="bi bi-arrow-down-circle-fill"></i>
        </div>
        <div style="font-family:'Cormorant Garamond',serif;font-size:1.35rem;font-weight:700;color:#16a34a;line-height:1">
            {{ $totalEarned > 0 ? 'Rp'.number_format($totalEarned/1000000,1).'jt' : '–' }}
        </div>
        <div style="font-size:0.55rem;text-transform:uppercase;letter-spacing:0.1em;color:rgba(22,163,74,0.7);font-weight:700">Total Diterima</div>
    </div>

    {{-- Honor Pending --}}
    <div class="fin-summary-card" style="background:rgba(197,160,40,0.07);border:1px solid rgba(197,160,40,0.2)">
        <div class="fin-summary-icon" style="background:rgba(197,160,40,0.15);color:var(--clr-gold-500)">
            <i class="bi bi-clock-fill"></i>
        </div>
        <div style="font-family:'Cormorant Garamond',serif;font-size:1.35rem;font-weight:700;color:var(--clr-gold-500);line-height:1">
            {{ $totalPending > 0 ? 'Rp'.number_format($totalPending/1000000,1).'jt' : '–' }}
        </div>
        <div style="font-size:0.55rem;text-transform:uppercase;letter-spacing:0.1em;color:rgba(197,160,40,0.7);font-weight:700">Pending</div>
    </div>

    {{-- Denda --}}
    <div class="fin-summary-card" style="background:rgba(220,38,38,0.06);border:1px solid rgba(220,38,38,0.18)">
        <div class="fin-summary-icon" style="background:rgba(220,38,38,0.1);color:#dc2626">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div style="font-family:'Cormorant Garamond',serif;font-size:1.35rem;font-weight:700;color:#dc2626;line-height:1">
            {{ $totalPenalty > 0 ? 'Rp'.number_format($totalPenalty/1000,0).'rb' : '–' }}
        </div>
        <div style="font-size:0.55rem;text-transform:uppercase;letter-spacing:0.1em;color:rgba(220,38,38,0.7);font-weight:700">Total Denda</div>
    </div>
</div>

{{-- ══ RIWAYAT HONOR ══ --}}
<div class="fu2" style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
    <div style="width:3px;height:18px;background:linear-gradient(to bottom,var(--clr-gold-300),rgba(197,160,40,0.15));border-radius:99px;flex-shrink:0"></div>
    <div style="font-family:'Cormorant Garamond',serif;font-weight:700;font-size:1.15rem;color:#1A1817">Riwayat Honor</div>
    <span style="margin-left:auto;padding:3px 10px;border-radius:99px;font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;background:rgba(139,26,42,0.06);color:var(--clr-maroon-500);border:1px solid rgba(139,26,42,0.15)">{{ $eventFinancials->total() }} Event</span>
</div>

@forelse($eventFinancials as $item)
@php
    $ev     = $item['event'];
    $eDate  = \Carbon\Carbon::parse($ev->event_date);
    $isDone = $item['event_status'] === 'completed';
    $isLate = $item['status'] === 'late';
    $net    = $item['fee'] - $item['penalty'];
    $cardClass = $loop->iteration % 2 === 0 ? 'fu' . (min($loop->index+2, 5)) : 'fu' . (min($loop->index+2, 5));
@endphp
<div class="{{ $cardClass }} mb-3"
     style="background:#fff;border-radius:18px;border:1px solid {{ $isDone ? 'rgba(22,163,74,0.15)' : 'rgba(197,160,40,0.18)' }};box-shadow:0 2px 16px rgba(54,31,26,0.04);overflow:hidden;transition:all 0.35s var(--easing-spring)"
     onmouseenter="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 28px rgba(54,31,26,0.08)'"
     onmouseleave="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 16px rgba(54,31,26,0.04)'">

    {{-- Card header --}}
    <div style="display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid rgba(0,0,0,0.05)">
        {{-- Date badge --}}
        <div style="width:48px;height:48px;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0;background:rgba(139,26,42,0.06);border:1px solid rgba(139,26,42,0.15)">
            <span style="font-family:'Cormorant Garamond',serif;font-weight:700;font-size:1.2rem;color:var(--clr-maroon-500);line-height:1">{{ $eDate->format('d') }}</span>
            <span style="font-size:0.48rem;font-weight:700;text-transform:uppercase;color:rgba(139,26,42,0.7)">{{ $eDate->format('M') }}</span>
        </div>
        <div style="flex:1;min-width:0">
            <div style="font-weight:700;color:#1A1817;font-size:0.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:2px">
                {{ $ev->booking->client_name ?? 'Event Sanggar' }}
            </div>
            <div style="font-size:0.65rem;color:#847B78">{{ $eDate->translatedFormat('d F Y') }}</div>
            <div style="font-size:0.58rem;text-transform:uppercase;letter-spacing:0.08em;font-weight:700;color:#847B78;margin-top:1px">{{ ucfirst(str_replace('_', ' ', $item['role'])) }}</div>
        </div>
        @if($isDone)
        <span style="flex-shrink:0;padding:4px 10px;border-radius:99px;font-size:0.58rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;background:rgba(22,163,74,0.1);color:#16a34a;border:1px solid rgba(22,163,74,0.2)">
            <i class="bi bi-check-circle-fill" style="margin-right:3px"></i>Selesai
        </span>
        @else
        <span style="flex-shrink:0;padding:4px 10px;border-radius:99px;font-size:0.58rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;background:rgba(197,160,40,0.1);color:var(--clr-gold-500);border:1px solid rgba(197,160,40,0.2)">
            <i class="bi bi-clock" style="margin-right:3px"></i>Pending
        </span>
        @endif
    </div>

    {{-- Financial breakdown --}}
    <div style="background:#FAFAF8;padding:0 16px">
        <div class="fin-table-row" style="grid-template-columns:1fr auto">
            <span style="font-size:0.8rem;color:#4D4946">Honor Pokok</span>
            <span style="font-size:0.88rem;font-weight:700;color:var(--clr-maroon-500)">Rp {{ number_format($item['fee'], 0, ',', '.') }}</span>
        </div>
        @if($item['penalty'] > 0)
        <div class="fin-table-row" style="grid-template-columns:1fr auto">
            <span style="font-size:0.8rem;color:#dc2626;display:flex;align-items:center;gap:5px"><i class="bi bi-exclamation-circle-fill" style="font-size:0.7rem"></i> Denda Terlambat ({{ $item['late_minutes'] }} mnt)</span>
            <span style="font-size:0.88rem;font-weight:700;color:#dc2626">– Rp {{ number_format($item['penalty'], 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="fin-table-row" style="grid-template-columns:1fr auto;background:rgba(0,0,0,0.02)">
            <span style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#4D4946">Total Bersih</span>
            <span style="font-size:1rem;font-weight:700;color:{{ $isDone ? '#16a34a' : 'var(--clr-maroon-500)' }}">Rp {{ number_format($net, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Check-in status --}}
    <div style="padding:10px 16px;display:flex;align-items:center;gap:8px">
        @if($item['checked_in_at'])
            @if($isLate)
            <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:99px;font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;background:rgba(234,88,12,0.1);color:#ea580c;border:1px solid rgba(234,88,12,0.2)">
                <i class="bi bi-clock-history text-xs"></i> Terlambat {{ $item['late_minutes'] }} mnt
            </span>
            @else
            <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:99px;font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;background:rgba(22,163,74,0.1);color:#16a34a;border:1px solid rgba(22,163,74,0.2)">
                <i class="bi bi-check-circle-fill text-xs"></i> Tepat Waktu
            </span>
            @endif
            <span style="font-size:0.7rem;color:#847B78">{{ \Carbon\Carbon::parse($item['checked_in_at'])->format('H:i') }} WIB</span>
        @else
        <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:99px;font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;background:#F4F2EE;color:#847B78;border:1px solid rgba(0,0,0,0.06)">
            <i class="bi bi-clock text-xs"></i> Belum Check-in
        </span>
        @endif
    </div>
</div>
@empty
<div style="background:#fff;border-radius:20px;border:1px dashed rgba(197,160,40,0.25);padding:48px 24px;text-align:center">
    <i class="bi bi-wallet2" style="font-size:2.5rem;display:block;margin-bottom:12px;color:#C5C0BC"></i>
    <div style="font-family:'Cormorant Garamond',serif;font-weight:700;font-size:1.1rem;color:#1A1817;margin-bottom:6px">Belum Ada Riwayat Honor</div>
    <div style="font-size:0.8rem;color:#847B78">Riwayat akan muncul setelah Anda dijadwalkan pada event.</div>
</div>
@endforelse

{{-- Pagination --}}
<div style="margin-top:16px">{{ $eventFinancials->links() }}</div>

@endsection
