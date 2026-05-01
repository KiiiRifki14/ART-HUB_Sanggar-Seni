@extends('layouts.admin')

@section('title', 'Cancellation Handler – ART-HUB')
@section('page_title', 'Cancellation Handler')
@section('page_subtitle', 'Riwayat pembatalan & pengembalian dana klien.')

@section('content')

{{-- Header --}}
<div class="flex items-center gap-2 mb-6">
    <h2 class="font-headline text-xl text-primary font-semibold">
        <i class="bi bi-shield-exclamation text-red-500 me-1"></i> Daftar Pembatalan
    </h2>
</div>

{{-- Table --}}
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden mb-8">
    <table class="w-full">
        <thead class="bg-surface-container-low">
            <tr>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Booking</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Klien</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Tgl Batal & H- Event</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Penalti</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Refund</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Alasan</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/20">
            @forelse($cancellations as $c)
            @php
                $days = $c->days_before_event;
                $dayClass = $days <= 3 ? 'text-red-600 bg-red-500/10 border-red-500/20' : ($days <= 7 ? 'text-orange-600 bg-orange-500/10 border-orange-500/20' : 'text-on-surface-variant bg-surface-container border-outline-variant/30');
            @endphp
            <tr class="hover:bg-surface-container-low/50 transition-colors">
                <td class="px-6 py-4 pl-5">
                    <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">
                        #{{ str_pad($c->booking_id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                </td>
                <td class="px-6 py-4 font-body font-semibold text-on-surface text-sm">
                    {{ $c->booking->client_name ?? '-' }}
                </td>
                <td class="px-6 py-4">
                    <div class="font-label text-xs text-on-surface-variant mb-1 flex items-center gap-1.5">
                        <i class="bi bi-calendar-x opacity-60"></i> {{ \Carbon\Carbon::parse($c->cancellation_date)->format('d M Y') }}
                    </div>
                    <span class="inline-block px-2 py-0.5 rounded border font-label text-[0.6rem] font-bold uppercase tracking-wider {{ $dayClass }}">
                        H-{{ $days }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="font-headline font-bold text-red-600 text-sm">Rp {{ number_format($c->penalty_amount, 0, ',', '.') }}</div>
                    <div class="font-label text-xs text-outline">{{ number_format($c->penalty_percentage, 0) }}% dari total</div>
                </td>
                <td class="px-6 py-4 text-right">
                    @if($c->refund_amount > 0)
                        <div class="font-headline font-bold text-green-600 text-sm">Rp {{ number_format($c->refund_amount, 0, ',', '.') }}</div>
                    @else
                        <div class="font-label text-xs text-outline italic">Rp 0 (Hangus)</div>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="font-body text-xs text-on-surface-variant max-w-[180px] leading-relaxed">
                        {{ Str::limit($c->reason, 60) }}
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    @if($c->status === 'pending')
                        <span class="inline-block px-2.5 py-1 rounded border border-orange-500/20 bg-orange-500/10 font-label text-[0.65rem] font-bold uppercase tracking-wider text-orange-600">PENDING</span>
                    @elseif($c->status === 'processed')
                        <span class="inline-block px-2.5 py-1 rounded border border-secondary/20 bg-secondary/10 font-label text-[0.65rem] font-bold uppercase tracking-wider text-secondary">DIPROSES</span>
                    @else
                        <span class="inline-block px-2.5 py-1 rounded border border-green-500/20 bg-green-500/10 font-label text-[0.65rem] font-bold uppercase tracking-wider text-green-600">REFUNDED</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-20 text-center">
                    <i class="bi bi-emoji-smile text-4xl text-outline mb-4 block"></i>
                    <p class="font-headline text-lg text-on-surface font-semibold mb-1">Belum ada pembatalan</p>
                    <p class="font-label text-xs uppercase tracking-widest text-outline">Semoga jadwal selalu berjalan lancar! 🙏</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ── FORMULA PENALTI ── --}}
<div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)]">
    <h3 class="font-headline text-lg text-primary font-semibold mb-6 flex items-center gap-2">
        <i class="bi bi-calculator text-secondary"></i> Formula Penalti
    </h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['H-14+', '10%', 'text-on-surface-variant bg-surface-container-low border-outline-variant/50'],
            ['H-7 s/d H-13', '30%', 'text-orange-600 bg-orange-500/5 border-orange-500/20'],
            ['H-3 s/d H-6', '50%', 'text-red-500 bg-red-500/5 border-red-500/20'],
            ['H-2 / Kurang', '75%', 'text-red-700 bg-red-700/5 border-red-700/20']
        ] as [$period, $pct, $classes])
        <div class="text-center p-4 rounded-xl border {{ $classes }}">
            <div class="font-headline text-3xl font-bold mb-1 leading-none">{{ $pct }}</div>
            <div class="font-label text-[0.65rem] uppercase tracking-widest font-bold opacity-80">{{ $period }}</div>
        </div>
        @endforeach
    </div>
</div>

@endsection
