@extends('layouts.admin')
@section('title', 'Event Monitoring – ART-HUB')
@section('page_title', 'Event Monitoring')
@section('page_subtitle', 'Pantau status & operasional lapangan seluruh pementasan')

@section('content')
@php
    $statusMap = [
        'pending'   => ['label' => 'Negotiation',   'class' => 'bg-orange-500/10 text-orange-600 border-orange-500/20',     'icon' => 'bi-chat-dots-fill'],
        'dp_paid'   => ['label' => 'Locked',        'class' => 'bg-primary/10 text-primary border-primary/20',   'icon' => 'bi-lock-fill'],
        'confirmed' => ['label' => 'DP 50%',        'class' => 'bg-blue-500/10 text-blue-600 border-blue-500/20',       'icon' => 'bi-receipt-cutoff'],
        'paid_full' => ['label' => 'PAID (Lunas)',  'class' => 'bg-green-500/10 text-green-600 border-green-500/20',     'icon' => 'bi-check-circle-fill'],
        'completed' => ['label' => 'Completed',     'class' => 'bg-surface-container-highest text-on-surface-variant border-outline-variant/30', 'icon' => 'bi-patch-check-fill'],
        'cancelled' => ['label' => 'Cancelled',     'class' => 'bg-red-500/10 text-red-600 border-red-500/20',       'icon' => 'bi-x-circle-fill'],
    ];
    $filters = [
        'all'       => ['label' => 'All Events',    'icon' => 'bi-grid-3x3-gap-fill'],
        'pending'   => ['label' => 'Negotiation',   'icon' => 'bi-chat-dots-fill'],
        'dp_paid'   => ['label' => 'Locked',        'icon' => 'bi-lock-fill'],
        'confirmed' => ['label' => 'DP 50%',        'icon' => 'bi-receipt-cutoff'],
        'paid_full' => ['label' => 'PAID',          'icon' => 'bi-check-circle-fill'],
        'completed' => ['label' => 'Completed',     'icon' => 'bi-patch-check-fill'],
    ];
    $currentFilter = $filter ?? 'all';
@endphp

{{-- Header & Filter Tabs --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <h2 class="font-headline text-xl text-primary font-semibold flex items-center gap-2">
        <i class="bi bi-binoculars text-secondary"></i> Monitoring Lapangan
    </h2>
    <div class="flex flex-wrap gap-2">
        @foreach($filters as $key => $f)
            <a href="{{ route('admin.events.monitoring', $key !== 'all' ? ['filter' => $key] : []) }}"
               class="px-3.5 py-1.5 rounded-lg border font-label text-[0.65rem] font-bold uppercase tracking-widest transition-all flex items-center gap-1.5 {{ $currentFilter === $key ? 'bg-primary text-white border-primary shadow-sm' : 'bg-surface-container-lowest text-on-surface-variant border-outline-variant/30 hover:border-primary/40 hover:text-primary' }}">
                <i class="bi {{ $f['icon'] }}"></i> {{ $f['label'] }}
            </a>
        @endforeach
    </div>
</div>

{{-- Event Table --}}
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
    <table class="w-full">
        <thead class="bg-surface-container-low">
            <tr>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Tanggal Event</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Nama Klien & Venue</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Jenis Acara</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Deal Price</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Status</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/20">
            @forelse($events as $booking)
            @php
                $eventModel = $booking->event;
                $bStatus    = $booking->status ?? 'pending';
                $statusInfo = $statusMap[$bStatus] ?? $statusMap['pending'];
                $eventDate  = \Carbon\Carbon::parse($booking->event_date);
                $daysUntil  = now()->startOfDay()->diffInDays($eventDate->startOfDay(), false);
                $isPriority = ($daysUntil >= 0 && $daysUntil <= 3 && !in_array($bStatus, ['completed', 'cancelled']));

                if ($bStatus === 'pending' && ($booking->price_min || $booking->price_max)) {
                    $minFmt = $booking->price_min ? number_format($booking->price_min/1000000, 0) . 'jt' : '?';
                    $maxFmt = $booking->price_max ? number_format($booking->price_max/1000000, 0) . 'jt' : '?';
                    $priceDisplay = 'Rp ' . $minFmt . ' – ' . $maxFmt;
                } elseif ($booking->total_price) {
                    $priceDisplay = 'Rp ' . number_format($booking->total_price, 0, ',', '.');
                } else {
                    $priceDisplay = '<span class="text-outline">—</span>';
                }
            @endphp
            <tr class="hover:bg-surface-container-low/50 transition-colors {{ $isPriority ? 'bg-orange-500/5' : '' }}">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-surface-container flex flex-col items-center justify-center border border-outline-variant/30 flex-shrink-0">
                            <span class="font-headline font-bold text-primary text-lg leading-none">{{ $eventDate->format('d') }}</span>
                            <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mt-0.5">{{ $eventDate->format('M') }}</span>
                        </div>
                        <div>
                            <div class="font-label text-xs text-outline">{{ \Carbon\Carbon::parse($booking->event_start)->format('H:i') }} WIB</div>
                            @if($isPriority)
                                <div class="font-label text-[0.65rem] font-bold text-orange-600 uppercase tracking-widest mt-1 flex items-center gap-1">
                                    <i class="bi bi-star-fill text-[0.55rem]"></i> H-{{ $daysUntil }}
                                </div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="font-body font-semibold text-on-surface text-sm">{{ $booking->client_name ?? '—' }}</div>
                    <div class="font-label text-xs text-outline max-w-[200px] truncate" title="{{ $booking->venue }}">{{ $booking->venue }}</div>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-block px-2.5 py-1 rounded bg-surface-container-lowest border border-outline-variant/30 font-label text-[0.65rem] font-bold uppercase tracking-wider text-on-surface-variant">
                        {{ str_replace('_', ' ', $booking->event_type ?? '—') }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="font-body font-semibold text-primary text-sm">{!! $priceDisplay !!}</div>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded border font-label text-[0.6rem] font-bold uppercase tracking-wider {{ $statusInfo['class'] }}">
                        <i class="bi {{ $statusInfo['icon'] }}"></i> {{ $statusInfo['label'] }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    @if($eventModel)
                        <a href="{{ route('admin.events.monitoring.show', $eventModel->id) }}"
                           class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-md border border-outline-variant/30 bg-surface-container-lowest font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant hover:bg-primary hover:text-white hover:border-primary transition-all">
                            <i class="bi bi-eye-fill"></i> View
                        </a>
                    @else
                        <a href="{{ route('admin.bookings.show', $booking->id) }}"
                           class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-md border border-secondary/30 bg-secondary/5 font-label text-[0.65rem] font-bold uppercase tracking-widest text-secondary hover:bg-secondary hover:text-white hover:border-secondary transition-all">
                            <i class="bi bi-ui-checks"></i> Nego
                        </a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-20 text-center">
                    <i class="bi bi-calendar-x text-4xl text-outline mb-4 block"></i>
                    <p class="font-headline text-lg text-on-surface font-semibold mb-1">Tidak ada event</p>
                    <p class="font-label text-xs uppercase tracking-widest text-outline">Pilih filter lain atau tunggu booking baru</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
