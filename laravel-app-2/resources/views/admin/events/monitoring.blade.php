@extends('layouts.admin')
@section('title', 'Event Monitoring – ART-HUB')
@section('page_title', 'Event Monitoring')
@section('page_subtitle', 'Pantau status & operasional lapangan seluruh pementasan secara real-time.')

@section('content')
@php
    $statusMap = [
        'pending'   => ['label' => 'Negotiation',   'class' => 'bg-orange-500/10 text-orange-600 border-orange-500/20',     'icon' => 'bi-chat-dots-fill'],
        'dp_paid'   => ['label' => 'Locked',        'class' => 'bg-secondary/10 text-secondary border-secondary/20',        'icon' => 'bi-lock-fill'],
        'confirmed' => ['label' => 'DP 50%',        'class' => 'bg-blue-500/10 text-blue-600 border-blue-500/20',           'icon' => 'bi-receipt-cutoff'],
        'paid_full' => ['label' => 'Lunas',         'class' => 'bg-green-500/10 text-green-600 border-green-500/20',        'icon' => 'bi-check-circle-fill'],
        'completed' => ['label' => 'Completed',     'class' => 'bg-surface-container text-on-surface-variant border-outline-variant/30', 'icon' => 'bi-patch-check-fill'],
        'cancelled' => ['label' => 'Cancelled',     'class' => 'bg-red-500/10 text-red-600 border-red-500/20',              'icon' => 'bi-x-circle-fill'],
    ];
    $filters = [
        'all'       => ['label' => 'Semua',         'icon' => 'bi-grid-3x3-gap-fill'],
        'pending'   => ['label' => 'Nego',          'icon' => 'bi-chat-dots-fill'],
        'dp_paid'   => ['label' => 'Locked',        'icon' => 'bi-lock-fill'],
        'confirmed' => ['label' => 'DP Masuk',      'icon' => 'bi-receipt-cutoff'],
        'paid_full' => ['label' => 'Lunas',         'icon' => 'bi-check-circle-fill'],
        'completed' => ['label' => 'Selesai',       'icon' => 'bi-patch-check-fill'],
    ];
    $currentFilter = $filter ?? 'all';
@endphp

{{-- Header & Filter Tabs --}}
<div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-8">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-container to-primary flex items-center justify-center text-secondary shadow-md flex-shrink-0">
            <i class="bi bi-binoculars-fill text-2xl"></i>
        </div>
        <div>
            <h2 class="font-headline text-xl text-primary font-bold leading-tight">Board Monitoring Lapangan</h2>
            <p class="font-body text-[0.7rem] text-on-surface-variant">Pantau pergerakan acara dan personel secara live.</p>
        </div>
    </div>
    <div class="flex flex-wrap gap-2">
        @foreach($filters as $key => $f)
            <a href="{{ route('admin.events.monitoring', $key !== 'all' ? ['filter' => $key] : []) }}"
               class="px-4 py-2 rounded-lg border font-label text-[0.65rem] font-bold uppercase tracking-widest transition-all flex items-center gap-1.5 shadow-sm {{ $currentFilter === $key ? 'bg-primary text-white border-primary' : 'bg-surface-container-lowest text-on-surface-variant border-outline-variant/30 hover:border-primary/30 hover:text-primary hover:bg-surface-container-low' }}">
                <i class="bi {{ $f['icon'] }}"></i> {{ $f['label'] }}
            </a>
        @endforeach
    </div>
</div>

{{-- Event Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-6">
    @forelse($events as $booking)
    @php
        $eventModel = $booking->event;
        $bStatus    = $booking->status ?? 'pending';
        $statusInfo = $statusMap[$bStatus] ?? $statusMap['pending'];
        $eventDate  = \Carbon\Carbon::parse($booking->event_date);
        $daysUntil  = now()->startOfDay()->diffInDays($eventDate->startOfDay(), false);
        $isUrgent   = ($daysUntil >= 0 && $daysUntil <= 3 && !in_array($bStatus, ['completed', 'cancelled']));

        // Display Price Handling
        if ($bStatus === 'pending' && ($booking->price_min || $booking->price_max)) {
            $minFmt = $booking->price_min ? number_format($booking->price_min/1000000, 1) . 'jt' : '?';
            $maxFmt = $booking->price_max ? number_format($booking->price_max/1000000, 1) . 'jt' : '?';
            $priceDisplay = 'Rp ' . str_replace('.0jt', 'jt', $minFmt) . ' - ' . str_replace('.0jt', 'jt', $maxFmt);
        } elseif ($booking->total_price) {
            $priceDisplay = 'Rp ' . number_format($booking->total_price, 0, ',', '.');
        } else {
            $priceDisplay = '—';
        }
    @endphp
    
    <div class="group relative bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] hover:shadow-[0_16px_32px_rgba(54,31,26,0.06)] hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden {{ $isUrgent ? 'ring-2 ring-orange-500/30' : '' }}">
        
        {{-- Urgent Badge Overlay --}}
        @if($isUrgent)
        <div class="absolute top-0 right-0 px-3 py-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-label text-[0.6rem] font-bold uppercase tracking-widest rounded-bl-lg shadow-sm z-10 flex items-center gap-1">
            <i class="bi bi-fire"></i> H-{{ $daysUntil }}
        </div>
        @endif

        {{-- Card Header --}}
        <div class="px-5 py-4 border-b border-outline-variant/20 flex items-start gap-4 relative">
            <div class="w-14 h-14 rounded-xl flex flex-col items-center justify-center border flex-shrink-0 {{ $isUrgent ? 'bg-orange-500/10 border-orange-500/20 text-orange-600' : 'bg-surface-container border-outline-variant/30 text-on-surface' }}">
                <span class="font-headline font-bold text-xl leading-none">{{ $eventDate->format('d') }}</span>
                <span class="font-label text-[0.65rem] uppercase tracking-widest font-bold mt-0.5">{{ $eventDate->format('M') }}</span>
            </div>
            <div class="min-w-0 flex-grow pt-0.5">
                <h3 class="font-body font-bold text-base text-on-surface truncate mb-1" title="{{ $booking->client_name }}">{{ $booking->client_name ?? '—' }}</h3>
                <div class="font-label text-[0.65rem] text-on-surface-variant flex items-center gap-1.5 uppercase tracking-widest font-bold">
                    <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($booking->event_start)->format('H:i') }}
                    <span class="text-outline-variant mx-1">•</span>
                    <span class="truncate">{{ str_replace('_', ' ', $booking->event_type ?? '—') }}</span>
                </div>
            </div>
        </div>

        {{-- Card Body --}}
        <div class="p-5 flex-grow space-y-4">
            {{-- Venue --}}
            <div>
                <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-1 flex items-center gap-1"><i class="bi bi-geo-alt-fill"></i> Lokasi Venue</span>
                <p class="font-body text-sm font-medium text-on-surface line-clamp-2" title="{{ $booking->venue }}">{{ $booking->venue }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Deal Price --}}
                <div class="bg-surface-container-low border border-outline-variant/50 rounded-lg p-3">
                    <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-1 block">Deal Price</span>
                    <span class="font-headline font-bold text-sm {{ $bStatus === 'pending' ? 'text-orange-600' : 'text-primary' }}">{{ $priceDisplay }}</span>
                </div>
                {{-- Status --}}
                <div class="bg-surface-container-low border border-outline-variant/50 rounded-lg p-3 flex flex-col justify-center">
                    <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-1.5 block">Status Acara</span>
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded border font-label text-[0.55rem] font-bold uppercase tracking-wider {{ $statusInfo['class'] }}">
                            <i class="bi {{ $statusInfo['icon'] }}"></i> {{ $statusInfo['label'] }}
                        </span>
                    </div>
                </div>
            </div>
            
            {{-- Personnel Plotting Progress (if event created) --}}
            @if($eventModel)
                @php
                    $pCount = $eventModel->personnel->count();
                    $pReq = $eventModel->personnel_count;
                    $pPct = $pReq > 0 ? min(100, round(($pCount / $pReq) * 100)) : 0;
                @endphp
                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold"><i class="bi bi-people-fill me-1"></i> Plotting Personel</span>
                        <span class="font-body text-xs font-bold {{ $pCount >= $pReq ? 'text-green-600' : 'text-primary' }}">{{ $pCount }}/{{ $pReq }} Orang</span>
                    </div>
                    <div class="h-2 w-full bg-surface-container-high rounded-full overflow-hidden border border-outline-variant/30">
                        <div class="h-full transition-all duration-1000 {{ $pPct == 100 ? 'bg-green-500' : 'bg-secondary' }}" {!! 'style="width: '.$pPct.'%;"' !!}></div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Card Footer Actions --}}
        <div class="px-5 py-4 border-t border-outline-variant/20 bg-surface-container-low/50 flex justify-end gap-2">
            @if($eventModel)
                <a href="{{ route('admin.events.monitoring.show', $eventModel->id) }}"
                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-sm group-hover:shadow-md">
                    <i class="bi bi-eye-fill"></i> Detail Monitor
                </a>
            @else
                <a href="{{ route('admin.bookings.show', $booking->id) }}"
                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-secondary/30 bg-secondary/10 font-label text-xs font-bold uppercase tracking-widest text-secondary-container hover:bg-secondary hover:text-white transition-colors">
                    <i class="bi bi-ui-checks"></i> Tindak Lanjut (Nego)
                </a>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 flex flex-col items-center justify-center bg-surface-container-lowest border border-outline-variant/30 border-dashed rounded-2xl">
        <i class="bi bi-calendar-x text-5xl text-outline/50 mb-4"></i>
        <h3 class="font-headline text-lg text-on-surface font-semibold mb-1">Papan Monitor Kosong</h3>
        <p class="font-label text-xs uppercase tracking-widest text-outline">Tidak ada acara yang sesuai filter saat ini.</p>
    </div>
    @endforelse
</div>

@endsection
