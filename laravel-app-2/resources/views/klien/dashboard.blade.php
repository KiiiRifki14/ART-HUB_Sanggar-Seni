@extends('layouts.klien')

@section('title', 'Portal Klien – ART-HUB Sanggar Cahaya Gumilang')

@section('content')

{{-- ═══════ HERO GREETING (Premium Glassmorphism & Micro-animations) ═══════ --}}
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary to-primary-container text-white p-8 sm:p-12 mb-10 shadow-2xl border-b-8 border-secondary transform hover:scale-[1.005] transition-all duration-300">
    {{-- Background Ornaments --}}
    <div class="absolute -top-12 -right-12 w-80 h-80 bg-secondary/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-secondary/5 rounded-full blur-3xl"></div>
    <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-white/5 rounded-full blur-2xl animate-pulse"></div>

    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
        <div class="space-y-4">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-secondary/10 border border-secondary/30 text-secondary font-label text-[0.7rem] font-bold uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-secondary animate-ping"></span>
                Portal Klien Resmi
            </div>
            <h1 class="font-headline text-3xl sm:text-5xl font-bold leading-tight">
                Selamat Datang Kembali,<br>
                <span class="text-secondary tracking-wide">{{ Auth::user()->name }}</span>
            </h1>
            <p class="font-body text-white/70 max-w-xl text-sm sm:text-base leading-relaxed">
                Kelola pesanan pementasan seni budaya Anda, pantau progres persiapan, serta selesaikan administrasi dengan sistem yang transparan dan aman.
            </p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('klien.bookings.create') }}" 
               class="group relative inline-flex items-center justify-center gap-3 bg-secondary text-primary font-label text-xs font-bold uppercase tracking-widest px-8 py-4 rounded-2xl hover:bg-white hover:text-primary transition-all duration-300 shadow-[0_12px_24px_rgba(252,212,0,0.15)] hover:shadow-white/10 active:scale-95">
                <i class="bi bi-calendar-plus text-base group-hover:rotate-12 transition-transform"></i>
                Pesan Pementasan
            </a>
        </div>
    </div>
</div>

{{-- ═══════ STAT CARDS (Curated Harmonies) ═══════ --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
    <!-- Active Card -->
    <div class="relative group bg-white border border-outline-variant/20 rounded-2xl p-6 shadow-sm hover:shadow-md hover:border-secondary/40 transition-all duration-300">
        <div class="absolute top-0 right-0 w-24 h-24 bg-orange-500/5 rounded-bl-full group-hover:scale-110 transition-transform duration-300"></div>
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-orange-500/10 text-orange-600 flex items-center justify-center text-2xl shrink-0 group-hover:rotate-6 transition-transform">
                <i class="bi bi-activity"></i>
            </div>
            <div>
                <div class="font-headline text-3xl font-bold text-primary mb-0.5 leading-none">{{ $aktif }}</div>
                <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-extrabold">Pesanan Aktif</div>
            </div>
        </div>
    </div>

    <!-- Completed Card -->
    <div class="relative group bg-white border border-outline-variant/20 rounded-2xl p-6 shadow-sm hover:shadow-md hover:border-secondary/40 transition-all duration-300">
        <div class="absolute top-0 right-0 w-24 h-24 bg-green-500/5 rounded-bl-full group-hover:scale-110 transition-transform duration-300"></div>
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-green-500/10 text-green-600 flex items-center justify-center text-2xl shrink-0 group-hover:rotate-6 transition-transform">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div>
                <div class="font-headline text-3xl font-bold text-primary mb-0.5 leading-none">{{ $selesai }}</div>
                <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-extrabold">Pementasan Selesai</div>
            </div>
        </div>
    </div>

    <!-- Total Card -->
    <div class="relative group bg-white border border-outline-variant/20 rounded-2xl p-6 shadow-sm hover:shadow-md hover:border-secondary/40 transition-all duration-300">
        <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-bl-full group-hover:scale-110 transition-transform duration-300"></div>
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-primary/5 text-primary flex items-center justify-center text-2xl shrink-0 group-hover:rotate-6 transition-transform">
                <i class="bi bi-receipt-cutoff"></i>
            </div>
            <div>
                <div class="font-headline text-3xl font-bold text-primary mb-0.5 leading-none">{{ $total }}</div>
                <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-extrabold">Total Riwayat</div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════ RIWAYAT PESANAN (Modern Table / List Layout) ═══════ --}}
<div class="flex items-center gap-3 mb-6">
    <div class="w-8 h-8 rounded-lg bg-secondary/15 flex items-center justify-center">
        <i class="bi bi-journal-text text-secondary font-bold"></i>
    </div>
    <h3 class="font-headline text-xl font-bold text-primary">Riwayat Pemesanan Anda</h3>
</div>

<div class="space-y-5">
    @forelse($bookings as $booking)
        @php
            $statusMap = [
                'pending'   => [
                    'label' => 'Menunggu Konfirmasi', 
                    'icon' => 'bi-hourglass-split', 
                    'cls' => 'bg-amber-500/10 text-amber-700 border-amber-500/20'
                ],
                'dp_paid'   => [
                    'label' => 'DP Terkonfirmasi', 
                    'icon' => 'bi-lock-fill', 
                    'cls' => 'bg-indigo-500/10 text-indigo-700 border-indigo-500/20'
                ],
                'confirmed' => [
                    'label' => 'Jadwal Terkunci', 
                    'icon' => 'bi-calendar-check-fill', 
                    'cls' => 'bg-blue-500/10 text-blue-700 border-blue-500/20'
                ],
                'paid_full' => [
                    'label' => 'Lunas sepenuhnya', 
                    'icon' => 'bi-shield-check', 
                    'cls' => 'bg-emerald-500/10 text-emerald-700 border-emerald-500/20'
                ],
                'completed' => [
                    'label' => 'Pementasan Sukses', 
                    'icon' => 'bi-trophy-fill', 
                    'cls' => 'bg-surface-container-high text-on-surface-variant border-outline-variant/30'
                ],
                'cancelled' => [
                    'label' => 'Dibatalkan', 
                    'icon' => 'bi-x-circle-fill', 
                    'cls' => 'bg-red-500/10 text-red-700 border-red-500/20'
                ],
            ];
            $st = $statusMap[$booking->status] ?? [
                'label' => strtoupper($booking->status), 
                'icon' => 'bi-info-circle', 
                'cls' => 'bg-surface-container text-outline border-outline-variant/30'
            ];
        @endphp

        <div class="group relative bg-white border border-outline-variant/20 rounded-2xl shadow-sm hover:shadow-md hover:border-secondary/40 transition-all duration-300 overflow-hidden flex flex-col md:flex-row md:items-center">
            {{-- Accent Indicator --}}
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-primary to-primary-container md:block hidden"></div>
            
            {{-- Info Segment --}}
            <div class="p-6 flex-grow">
                <div class="flex items-center gap-3 mb-2 flex-wrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-secondary/10 border border-secondary/30 text-primary font-label text-[0.6rem] font-black uppercase tracking-widest">
                        #BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="font-body text-xs text-outline flex items-center gap-1">
                        <i class="bi bi-clock"></i> {{ $booking->created_at->diffForHumans() }}
                    </span>
                </div>
                
                <h4 class="font-headline font-bold text-lg text-primary capitalize leading-tight mb-2 group-hover:text-secondary-container transition-colors">
                    {{ str_replace('_', ' ', $booking->event_type) }}
                </h4>
                
                <div class="flex items-center gap-x-6 gap-y-2 flex-wrap font-body text-xs text-on-surface-variant">
                    <span class="flex items-center gap-1.5">
                        <i class="bi bi-calendar3 text-secondary"></i>
                        {{ \Carbon\Carbon::parse($booking->event_date)->isoFormat('dddd, D MMMM Y') }}
                    </span>
                    <span class="flex items-center gap-1.5 truncate max-w-[240px] sm:max-w-xs" title="{{ $booking->venue }}">
                        <i class="bi bi-geo-alt-fill text-secondary"></i>
                        {{ Str::limit($booking->venue, 32) }}
                    </span>
                </div>
            </div>

            {{-- Status Check --}}
            <div class="px-6 py-4 md:py-6 border-t md:border-t-0 border-outline-variant/10 md:border-l md:w-60 shrink-0 bg-surface-container-low/20">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl border font-label text-[0.65rem] font-bold uppercase tracking-wider {{ $st['cls'] }}">
                    <i class="bi {{ $st['icon'] }} text-xs"></i>
                    {{ $st['label'] }}
                </span>

                {{-- Custom Context Alerts --}}
                @if($booking->status === 'pending' && !$booking->payment_proof)
                    <p class="font-body text-[0.65rem] text-amber-600 mt-2 leading-relaxed flex items-start gap-1">
                        <i class="bi bi-exclamation-circle-fill mt-0.5"></i>
                        Menunggu kesepakatan harga final dari Pimpinan Sanggar.
                    </p>
                @elseif($booking->status === 'pending' && $booking->payment_proof)
                    <p class="font-body text-[0.65rem] text-blue-600 mt-2 leading-relaxed flex items-start gap-1">
                        <i class="bi bi-info-circle-fill mt-0.5"></i>
                        Bukti DP terunggah. Menunggu konfirmasi verifikasi Admin.
                    </p>
                @elseif($booking->status === 'dp_paid')
                    <p class="font-body text-[0.65rem] text-indigo-600 mt-2 leading-relaxed flex items-start gap-1">
                        <i class="bi bi-info-circle-fill mt-0.5"></i>
                        DP Masuk. Silakan lakukan pelunasan sebelum hari-H.
                    </p>
                @endif
            </div>

            {{-- Financial Summary & Action --}}
            <div class="p-6 md:w-56 border-t md:border-t-0 border-outline-variant/10 md:border-l bg-surface-container-low/40 md:bg-transparent text-left md:text-right flex flex-row md:flex-col justify-between items-center md:justify-center md:items-end gap-3 shrink-0">
                <div>
                    <div class="font-headline font-bold text-xl text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                    <div class="font-label text-[0.6rem] text-outline font-bold uppercase tracking-wider mt-0.5">DP: Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
                </div>
                
                <a href="{{ route('klien.bookings.show', $booking->id) }}" 
                   class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-surface-container-low hover:bg-secondary text-primary font-label text-[0.7rem] font-bold uppercase tracking-widest border border-outline-variant/30 hover:border-secondary rounded-xl transition-all duration-200 shadow-sm active:scale-95">
                    Detail <i class="bi bi-arrow-right-short text-base"></i>
                </a>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-3xl border-2 border-dashed border-outline-variant/40 p-12 text-center shadow-sm max-w-xl mx-auto mt-6">
            <div class="w-16 h-16 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center text-3xl mx-auto mb-4 animate-bounce">
                <i class="bi bi-calendar-x"></i>
            </div>
            <h5 class="font-headline font-bold text-xl text-primary mb-2">Belum Ada Riwayat Pesanan</h5>
            <p class="font-body text-sm text-on-surface-variant mb-6 leading-relaxed">
                Anda belum pernah memesan pementasan tari atau pemusik tradisional. Mari buat pementasan seni yang mengagumkan bersama sanggar kami!
            </p>
            <a href="{{ route('klien.bookings.create') }}" 
               class="inline-flex items-center gap-2 bg-primary text-white font-label text-xs font-bold uppercase tracking-widest px-6 py-3.5 rounded-xl hover:bg-secondary hover:text-primary transition-all duration-300 shadow-lg">
                <i class="bi bi-plus-circle"></i> Mulai Pesan Sekarang
            </a>
        </div>
    @endforelse
</div>

@if($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator && $bookings->hasPages())
    <div class="mt-8">
        {{ $bookings->links() }}
    </div>
@endif

@endsection
