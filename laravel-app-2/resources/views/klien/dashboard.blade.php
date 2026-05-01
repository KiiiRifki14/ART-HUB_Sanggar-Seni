@extends('layouts.klien')

@section('title', 'Portal Klien – ART-HUB Sanggar Cahaya Gumilang')

@section('content')

{{-- ═══════ HERO GREETING ═══════ --}}
<div class="bg-primary text-white rounded-2xl p-8 sm:p-12 mb-10 relative overflow-hidden shadow-xl border-b-4 border-secondary">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-secondary opacity-10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-primary-container opacity-50 rounded-full blur-3xl"></div>
    
    <div class="relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="font-label text-[0.65rem] uppercase tracking-[0.2em] text-secondary font-bold mb-3 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                Portal Klien Sanggar Cahaya Gumilang
            </div>
            <h1 class="font-headline text-4xl sm:text-5xl font-bold leading-tight mb-2">
                Selamat datang,<br>
                <span class="text-secondary">{{ Auth::user()->name }}</span> 👋
            </h1>
            <p class="font-body text-white/80 max-w-xl">Pantau seluruh pesanan pementasan seni budaya Anda dengan mudah dan transparan di sini.</p>
        </div>
        <a href="{{ route('klien.bookings.create') }}" 
           class="inline-flex items-center justify-center gap-2 bg-surface-container-lowest text-primary px-6 py-3.5 rounded-xl font-label text-sm font-bold uppercase tracking-widest hover:bg-secondary hover:text-primary transition-all shadow-lg flex-shrink-0">
            <i class="bi bi-plus-lg"></i> Pesan Pementasan
        </a>
    </div>
</div>

{{-- ═══════ STAT CARDS ═══════ --}}
@php
    $aktif     = $bookings->whereIn('status', ['pending', 'dp_paid', 'confirmed'])->count();
    $selesai   = $bookings->where('status', 'completed')->count();
    $total     = $bookings->count();
@endphp
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] flex items-center gap-4 group hover:-translate-y-1 transition-all">
        <div class="w-12 h-12 rounded-lg bg-orange-500/10 text-orange-600 flex items-center justify-center text-xl flex-shrink-0 group-hover:scale-110 transition-transform">
            <i class="bi bi-clock-history"></i>
        </div>
        <div>
            <div class="font-headline text-2xl font-bold text-on-surface leading-none mb-1">{{ $aktif }}</div>
            <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Booking Aktif</div>
        </div>
    </div>
    
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] flex items-center gap-4 group hover:-translate-y-1 transition-all">
        <div class="w-12 h-12 rounded-lg bg-green-500/10 text-green-600 flex items-center justify-center text-xl flex-shrink-0 group-hover:scale-110 transition-transform">
            <i class="bi bi-check2-all"></i>
        </div>
        <div>
            <div class="font-headline text-2xl font-bold text-on-surface leading-none mb-1">{{ $selesai }}</div>
            <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Pementasan Selesai</div>
        </div>
    </div>
    
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] flex items-center gap-4 group hover:-translate-y-1 transition-all hidden md:flex">
        <div class="w-12 h-12 rounded-lg bg-blue-500/10 text-blue-600 flex items-center justify-center text-xl flex-shrink-0 group-hover:scale-110 transition-transform">
            <i class="bi bi-receipt"></i>
        </div>
        <div>
            <div class="font-headline text-2xl font-bold text-on-surface leading-none mb-1">{{ $total }}</div>
            <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Total Pesanan</div>
        </div>
    </div>
</div>

{{-- ═══════ RIWAYAT PESANAN ═══════ --}}
<div class="mb-6 flex items-center gap-2">
    <h3 class="font-headline text-xl font-bold text-primary flex items-center gap-2">
        <i class="bi bi-list-ul text-secondary"></i> Riwayat Pesanan
    </h3>
</div>

<div class="space-y-4">
    @forelse($bookings as $booking)
    @php
        $statusMap = [
            'pending'   => ['label' => 'Menunggu Konfirmasi', 'icon' => 'bi-hourglass-split',      'cls' => 'bg-orange-500/10 text-orange-600 border-orange-500/20'],
            'dp_paid'   => ['label' => 'DP Terkonfirmasi',    'icon' => 'bi-lock-fill',             'cls' => 'bg-blue-500/10 text-blue-600 border-blue-500/20'],
            'confirmed' => ['label' => 'Jadwal Terkunci',     'icon' => 'bi-calendar2-check-fill',  'cls' => 'bg-green-500/10 text-green-600 border-green-500/20'],
            'paid_full' => ['label' => 'Lunas',               'icon' => 'bi-wallet2',               'cls' => 'bg-green-500/10 text-green-600 border-green-500/20'],
            'completed' => ['label' => 'Selesai',             'icon' => 'bi-trophy-fill',           'cls' => 'bg-surface-container-high text-on-surface-variant border-outline-variant/30'],
            'cancelled' => ['label' => 'Dibatalkan',          'icon' => 'bi-x-circle-fill',         'cls' => 'bg-red-500/10 text-red-600 border-red-500/20'],
        ];
        $st = $statusMap[$booking->status] ?? ['label' => strtoupper($booking->status), 'icon' => 'bi-circle', 'cls' => 'bg-surface-container text-outline border-outline-variant/30'];
    @endphp
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_4px_12px_rgba(54,31,26,0.02)] hover:border-secondary hover:shadow-[0_8px_20px_rgba(252,212,0,0.1)] transition-all flex flex-col md:flex-row md:items-center overflow-hidden">
        
        {{-- Left Info --}}
        <div class="p-5 md:flex-1">
            <h4 class="font-headline font-bold text-lg text-on-surface mb-1 capitalize">{{ str_replace('_', ' ', $booking->event_type) }}</h4>
            <div class="font-label text-xs text-on-surface-variant flex items-center gap-2 flex-wrap">
                <span class="flex items-center gap-1"><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($booking->event_date)->isoFormat('D MMM Y') }}</span>
                <span class="text-outline-variant">•</span>
                <span class="flex items-center gap-1 truncate max-w-[200px] sm:max-w-xs" title="{{ $booking->venue }}"><i class="bi bi-geo-alt"></i> {{ Str::limit($booking->venue, 30) }}</span>
            </div>
        </div>
        
        {{-- Center Status --}}
        <div class="px-5 py-3 md:py-5 border-t md:border-t-0 border-outline-variant/20 md:border-l md:w-56 bg-surface-container-low/30 md:bg-transparent">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded border font-label text-[0.65rem] font-bold uppercase tracking-wider {{ $st['cls'] }}">
                <i class="bi {{ $st['icon'] }}"></i> {{ $st['label'] }}
            </span>
            
            @if($booking->status === 'pending' && !$booking->payment_proof)
                <div class="font-body text-[0.65rem] text-on-surface-variant mt-2 flex items-start gap-1">
                    <i class="bi bi-info-circle text-orange-500"></i> Menunggu konfirmasi harga dari Admin
                </div>
            @elseif($booking->status === 'pending' && $booking->payment_proof)
                <div class="font-body text-[0.65rem] text-on-surface-variant mt-2 flex items-start gap-1">
                    <i class="bi bi-search text-blue-500"></i> Bukti transfer sedang diverifikasi Admin
                </div>
            @endif
        </div>
        
        {{-- Right Price & Action --}}
        <div class="p-5 md:w-56 border-t md:border-t-0 border-outline-variant/20 flex flex-row md:flex-col items-center justify-between md:items-end md:justify-center md:border-l bg-surface-container-low/50 md:bg-transparent text-right">
            <div>
                <div class="font-headline font-bold text-lg text-secondary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                <div class="font-label text-[0.65rem] text-outline uppercase tracking-widest font-bold">DP: Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
            </div>
            
            <a href="{{ route('klien.bookings.show', $booking->id) }}" 
               class="md:mt-3 px-4 py-2 rounded-lg border border-outline-variant/50 font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant hover:border-secondary hover:text-primary hover:bg-secondary/10 transition-colors flex items-center gap-1">
                Detail <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        
    </div>
    @empty
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 border-dashed p-12 text-center shadow-sm">
        <i class="bi bi-calendar-x text-5xl text-outline/50 mb-4 block"></i>
        <h5 class="font-headline font-bold text-xl text-on-surface mb-2">Belum Ada Pesanan</h5>
        <p class="font-body text-sm text-on-surface-variant max-w-sm mx-auto mb-6">Wujudkan pementasan seni budaya impian Anda bersama Sanggar Cahaya Gumilang.</p>
        <a href="{{ route('klien.bookings.create') }}" 
           class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-xl font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-all shadow-md">
            <i class="bi bi-plus-circle"></i> Mulai Pesan Sekarang
        </a>
    </div>
    @endforelse
</div>

@endsection
