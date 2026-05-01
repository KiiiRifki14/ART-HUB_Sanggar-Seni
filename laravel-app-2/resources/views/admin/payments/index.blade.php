@extends('layouts.admin')

@section('title', 'Payment Tracking – ART-HUB')
@section('page_title', 'Payment Tracking')
@section('page_subtitle', 'Pantau status pelunasan klien pasca-DP & pasca-event.')

@section('content')

@php
    $total    = $bookings->count();
    $unpaid   = $bookings->where('status','completed')->whereNull('pelunasan_at')->count();
    $piutang  = $bookings->where('status','completed')->whereNull('pelunasan_at')->sum(function($b) {
                    return $b->total_price - $b->dp_amount;
                });
    $lunas    = $bookings->whereNotNull('pelunasan_at')->count();
@endphp

{{-- Stat Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-primary-container to-primary text-white rounded-xl p-6 border border-primary/20 shadow-[0_12px_24px_rgba(54,31,26,0.08)] text-center relative overflow-hidden">
        <div class="absolute -right-6 -top-6 text-white/5">
            <i class="bi bi-cash-stack text-9xl"></i>
        </div>
        <div class="relative z-10">
            <i class="bi bi-cash-stack text-3xl text-secondary-container mb-3 block"></i>
            <h3 class="font-headline text-4xl font-bold text-secondary-container mb-1">Rp {{ number_format($piutang, 0, ',', '.') }}</h3>
            <div class="font-label text-xs uppercase tracking-widest text-white/80 font-bold">Total Piutang Berjalan</div>
        </div>
    </div>
    
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-red-500/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-red-500"></div>
        <i class="bi bi-hourglass-top text-3xl text-red-500 mb-3 block"></i>
        <h3 class="font-headline text-4xl font-bold text-red-600 mb-1">{{ $unpaid }} Event</h3>
        <div class="font-label text-xs uppercase tracking-widest text-outline font-bold">Belum Lunas (Selesai Event)</div>
    </div>
    
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-green-500/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-green-500"></div>
        <i class="bi bi-check2-all text-3xl text-green-500 mb-3 block"></i>
        <h3 class="font-headline text-4xl font-bold text-green-600 mb-1">{{ $lunas }} <span class="text-2xl text-outline font-body font-normal">/ {{ $total }}</span></h3>
        <div class="font-label text-xs uppercase tracking-widest text-outline font-bold">Transaksi Lunas</div>
    </div>
</div>

{{-- Header --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="font-headline text-xl text-primary font-semibold flex items-center gap-2">
        <i class="bi bi-journal-check text-secondary"></i> Daftar Tagihan
    </h2>
</div>

{{-- Table --}}
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
    <table class="w-full">
        <thead class="bg-surface-container-low">
            <tr>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">#Booking / Event</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Klien</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Total Kontrak</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">DP Masuk</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Sisa Tagihan</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Status Event</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Status Pembayaran</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/20">
            @forelse($bookings as $booking)
            @php
                $sisa = $booking->total_price - $booking->dp_amount;
                $isLunas = !is_null($booking->pelunasan_at) || ($booking->total_price > 0 && $sisa <= 0);
                $isOverdue = !$isLunas && in_array($booking->status, ['completed']);
                $stName = strtoupper($booking->status);
                
                $rowClass = $isOverdue ? 'bg-red-500/5 hover:bg-red-500/10 border-l-4 border-l-red-500' : 'hover:bg-surface-container-low/50 border-l-4 border-l-transparent';
            @endphp
            <tr class="{{ $rowClass }} transition-colors">
                <td class="px-6 py-4 pl-5">
                    <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">
                        #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                    @if($booking->event)
                        <div class="font-label text-xs text-outline mt-1 font-bold">{{ $booking->event->event_code }}</div>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="font-body font-semibold text-on-surface text-sm">{{ $booking->client_name ?? ($booking->client->name ?? '-') }}</div>
                    <div class="font-label text-xs text-outline">{{ $booking->client_phone ?? '-' }}</div>
                </td>
                <td class="px-6 py-4 text-right font-body text-sm text-on-surface">
                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 text-right font-body text-sm text-green-600 font-medium">
                    Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="font-headline font-bold {{ $isOverdue ? 'text-red-600' : 'text-primary' }}">
                        Rp {{ number_format($sisa, 0, ',', '.') }}
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    @if($booking->status === 'pending') 
                        <span class="inline-block px-2 py-0.5 rounded border border-outline-variant/30 bg-surface-container font-label text-[0.6rem] font-bold uppercase tracking-wider text-outline">PENDING</span>
                    @elseif($booking->status === 'completed') 
                        <span class="inline-block px-2 py-0.5 rounded border border-blue-500/20 bg-blue-500/10 font-label text-[0.6rem] font-bold uppercase tracking-wider text-blue-600">SELESAI</span>
                    @else 
                        <span class="inline-block px-2 py-0.5 rounded border border-outline-variant/30 bg-surface-container font-label text-[0.6rem] font-bold uppercase tracking-wider text-outline">{{ $stName }}</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    @if($isLunas)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-green-500/10 text-green-600 border border-green-500/20 font-label text-[0.65rem] font-bold uppercase tracking-wider">
                            <i class="bi bi-check-circle-fill"></i> LUNAS
                        </span>
                        <div class="font-label text-xs text-outline mt-1">{{ \Carbon\Carbon::parse($booking->pelunasan_at ?? now())->format('d M Y') }}</div>
                    @else
                        @if($booking->status === 'completed')
                            @php $sisaFormatted = number_format($sisa, 0, ',', '.'); @endphp
                            <form method="POST" action="#" class="m-0" onsubmit="return confirm('Tandai pelunasan tagihan ini sebesar Rp {{ $sisaFormatted }}?')">
                                @csrf
                                <button type="button" 
                                        class="w-full py-1.5 rounded border border-green-500 text-green-600 hover:bg-green-500 hover:text-white transition-all font-label text-xs font-bold uppercase tracking-wider" 
                                        onclick="alert('Demo: Fitur proses pelunasan segera diaktifkan')">
                                    <i class="bi bi-check-circle me-1"></i>Tandai Lunas
                                </button>
                            </form>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-orange-500/10 text-orange-600 border border-orange-500/20 font-label text-[0.65rem] font-bold uppercase tracking-wider">
                                <i class="bi bi-hourglass-split"></i> MENUNGGU EVENT
                            </span>
                        @endif
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-20 text-center">
                    <i class="bi bi-inbox text-4xl text-outline mb-4 block"></i>
                    <p class="font-headline text-lg text-on-surface font-semibold mb-1">Belum ada tagihan</p>
                    <p class="font-label text-xs uppercase tracking-widest text-outline">Tagihan akan muncul saat event selesai</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
