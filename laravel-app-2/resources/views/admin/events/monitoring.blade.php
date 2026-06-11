@extends('layouts.admin')
@section('title', 'Monitoring Acara – ART-HUB')
@section('page_title', 'Monitoring Acara')
@section('page_subtitle', 'Pantau status & operasional lapangan seluruh pementasan secara real-time.')

@section('content')
@php
    $statusMap = [
        'pending'   => ['label' => 'Negosiasi',    'class' => 'bg-orange-500/10 text-orange-600 border-orange-500/20',     'icon' => 'bi-chat-dots-fill'],
        'dp_paid'   => ['label' => 'Terkunci',      'class' => 'bg-secondary/10 text-secondary border-secondary/20',        'icon' => 'bi-lock-fill'],
        'confirmed' => ['label' => 'DP 50%',        'class' => 'bg-blue-500/10 text-blue-600 border-blue-500/20',           'icon' => 'bi-receipt-cutoff'],
        'paid_full' => ['label' => 'Lunas',         'class' => 'bg-green-500/10 text-green-600 border-green-500/20',        'icon' => 'bi-check-circle-fill'],
        'completed' => ['label' => 'Selesai',       'class' => 'bg-surface-container text-on-surface-variant border-outline-variant/30', 'icon' => 'bi-patch-check-fill'],
        'cancelled' => ['label' => 'Dibatalkan',    'class' => 'bg-red-500/10 text-red-600 border-red-500/20',              'icon' => 'bi-x-circle-fill'],
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
<div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-5">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary-container to-primary flex items-center justify-center text-secondary shadow-md flex-shrink-0">
            <i class="bi bi-binoculars-fill text-lg"></i>
        </div>
        <div>
            <h2 class="font-headline text-base text-primary font-bold leading-tight">Board Monitoring Lapangan</h2>
            <p class="font-body text-[0.65rem] text-on-surface-variant">Pantau pergerakan acara dan personel secara live.</p>
        </div>
    </div>
    <div class="flex overflow-x-auto whitespace-nowrap scrollbar-none pb-2 -mx-4 px-4 md:flex-wrap md:mx-0 md:px-0 gap-1.5">
        @foreach($filters as $key => $f)
            <a href="{{ route('admin.events.monitoring', array_merge($key !== 'all' ? ['filter' => $key] : [], request('search') ? ['search' => request('search')] : [])) }}"
               class="flex-shrink-0 px-2.5 py-1 rounded-lg border font-label text-[0.55rem] sm:text-[0.58rem] md:text-[0.62rem] font-bold uppercase tracking-widest transition-all flex items-center gap-1.5 shadow-sm {{ $currentFilter === $key ? 'bg-primary text-white border-primary' : 'bg-surface-container-lowest text-on-surface-variant border-outline-variant/30 hover:border-primary/30 hover:text-primary hover:bg-surface-container-low' }}">
                <i class="bi {{ $f['icon'] }}"></i> {{ $f['label'] }}
            </a>
        @endforeach
    </div>
</div>

{{-- Search Bar --}}
<form action="{{ route('admin.events.monitoring') }}" method="GET" class="mb-6 flex flex-col sm:flex-row gap-3">
    <input type="hidden" name="filter" value="{{ request('filter', 'all') }}">
    <div class="relative flex-1">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="bi bi-search text-outline text-xs"></i>
        </span>
        <input type="text" name="search" value="{{ request('search') }}" 
               placeholder="Cari monitoring berdasarkan kode event, nama klien, telepon, jenis acara, atau lokasi..." 
               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-outline-variant/30 bg-surface-container-lowest font-body text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
    </div>
    <div class="flex gap-2">
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-all shadow-sm">
            Cari
        </button>
        @if(request('search'))
        <a href="{{ route('admin.events.monitoring', ['filter' => request('filter', 'all')]) }}" class="px-4 py-2.5 rounded-xl border border-outline-variant/30 text-outline hover:text-primary hover:bg-surface-container font-label text-xs font-bold uppercase tracking-widest transition-all flex items-center justify-center">
            Reset
        </a>
        @endif
    </div>
</form>

{{-- Event Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-3 sm:gap-4">
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
    
    <div class="group relative bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_4px_12px_rgba(54,31,26,0.03)] hover:shadow-[0_8px_16px_rgba(54,31,26,0.05)] hover:-translate-y-0.5 transition-all duration-300 flex flex-col h-full overflow-hidden {{ $isUrgent ? 'ring-2 ring-orange-500/30' : '' }}">
        
        {{-- Urgent Badge Overlay --}}
        @if($isUrgent)
        <div class="absolute top-0 right-0 px-2 py-0.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-label text-[0.52rem] sm:text-[0.58rem] font-bold uppercase tracking-widest rounded-bl-lg shadow-sm z-10 flex items-center gap-1">
            <i class="bi bi-fire"></i> H-{{ $daysUntil }}
        </div>
        @endif

        {{-- Card Header --}}
        <div class="px-3.5 py-2.5 sm:px-4 sm:py-3 border-b border-outline-variant/20 flex items-start gap-3 relative">
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-lg flex flex-col items-center justify-center border flex-shrink-0 {{ $isUrgent ? 'bg-orange-500/10 border-orange-500/20 text-orange-600' : 'bg-surface-container border-outline-variant/30 text-on-surface' }}">
                <span class="font-headline font-bold text-xs sm:text-base leading-none">{{ $eventDate->format('d') }}</span>
                <span class="font-label text-[0.45rem] sm:text-[0.55rem] uppercase tracking-widest font-bold mt-0.5">{{ $eventDate->format('M') }}</span>
            </div>
            <div class="min-w-0 flex-grow pt-0.5">
                <h3 class="font-body font-bold text-xs sm:text-sm text-on-surface truncate mb-0.5" title="{{ $booking->client_name }}">{{ $booking->client_name ?? '—' }}</h3>
                <div class="font-label text-[0.52rem] sm:text-[0.6rem] text-on-surface-variant flex items-center gap-1 uppercase tracking-widest font-bold">
                    <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($booking->event_start)->format('H:i') }}
                    <span class="text-outline-variant mx-1">•</span>
                    <span class="truncate">{{ str_replace('_', ' ', $booking->event_type ?? '—') }}</span>
                </div>
            </div>
        </div>

        {{-- Card Body --}}
        <div class="p-3.5 sm:p-4 flex-grow space-y-3">
            {{-- Venue --}}
            <div>
                <span class="font-label text-[0.52rem] sm:text-[0.58rem] uppercase tracking-widest text-outline font-bold mb-0.5 flex items-center gap-1"><i class="bi bi-geo-alt-fill"></i> Lokasi Venue</span>
                <p class="font-body text-xs font-medium text-on-surface line-clamp-2" title="{{ $booking->venue }}">{{ $booking->venue }}</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                {{-- Deal Price --}}
                <div class="bg-surface-container-low border border-outline-variant/50 rounded-lg p-2 sm:p-2.5">
                    <span class="font-label text-[0.52rem] sm:text-[0.58rem] uppercase tracking-widest text-outline font-bold mb-0.5 block">Deal Price</span>
                    <span class="font-headline font-bold text-xs {{ $bStatus === 'pending' ? 'text-orange-600' : 'text-primary' }}">{{ $priceDisplay }}</span>
                </div>
                {{-- Status --}}
                <div class="bg-surface-container-low border border-outline-variant/50 rounded-lg p-2 sm:p-2.5 flex flex-col justify-center">
                    <span class="font-label text-[0.52rem] sm:text-[0.58rem] uppercase tracking-widest text-outline font-bold mb-1 block">Status Acara</span>
                    <div>
                        <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded border font-label text-[0.5rem] sm:text-[0.52rem] font-bold uppercase tracking-wider {{ $statusInfo['class'] }}">
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
                    <div class="flex justify-between items-end mb-1">
                        <span class="font-label text-[0.52rem] sm:text-[0.58rem] uppercase tracking-widest text-outline font-bold"><i class="bi bi-people-fill me-1"></i> Plotting Personel</span>
                        <span class="font-body text-[0.65rem] sm:text-[0.7rem] font-bold {{ $pCount >= $pReq ? 'text-green-600' : 'text-primary' }}">{{ $pCount }}/{{ $pReq }} Orang</span>
                    </div>
                    <div class="h-1.5 w-full bg-surface-container-high rounded-full overflow-hidden border border-outline-variant/30">
                        <div class="h-full transition-all duration-1000 {{ $pPct == 100 ? 'bg-green-500' : 'bg-secondary' }}" {!! 'style="width: '.$pPct.'%;"' !!}></div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Card Footer Actions --}}
        <div class="px-3.5 py-2 sm:px-4 sm:py-2.5 border-t border-outline-variant/20 bg-surface-container-low/50 flex justify-end gap-2">
            @if($eventModel)
                <a href="{{ route('admin.events.monitoring.show', $eventModel->id) }}"
                   class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-1.5 sm:py-2 rounded-lg bg-primary text-white font-label text-[0.65rem] sm:text-[0.7rem] font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-sm group-hover:shadow-md">
                    <i class="bi bi-eye-fill"></i> Detail Monitor
                </a>
            @else
                <a href="{{ route('admin.bookings.show', $booking->id) }}"
                   class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-1.5 sm:py-2 rounded-lg border border-secondary/30 bg-secondary/10 font-label text-[0.65rem] sm:text-[0.7rem] font-bold uppercase tracking-widest text-secondary-container hover:bg-secondary hover:text-white transition-colors">
                    <i class="bi bi-ui-checks"></i> Tindak Lanjut (Nego)
                </a>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 flex flex-col items-center justify-center bg-surface-container-lowest border border-outline-variant/30 border-dashed rounded-xl">
        <i class="bi bi-calendar-x text-5xl text-outline/50 mb-4"></i>
        <h3 class="font-headline text-base text-on-surface font-semibold mb-1">Papan Monitor Kosong</h3>
        <p class="font-label text-xs uppercase tracking-widest text-outline">Tidak ada acara yang sesuai filter saat ini.</p>
    </div>
    @endforelse
</div>

{{-- Pagination Links --}}
<div class="mt-6 flex justify-center">
    {!! $events->links() !!}
</div>

@endsection
