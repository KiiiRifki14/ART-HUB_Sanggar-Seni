@extends('layouts.klien')

@section('title', 'Portal Klien – ART-HUB Sanggar Cahaya Gumilang')

@section('content')

{{-- ═══════ HERO GREETING ═══════ --}}
<div class="klien-hero mb-5 animate-fade-up">
    <div class="klien-hero-inner">
        <div class="hero-eyebrow">
            <span class="hero-dot"></span>
            Portal Klien Sanggar Cahaya Gumilang
        </div>
        <h1 class="hero-title">
            Selamat datang,<br>
            <span class="klien-gold-text">{{ Auth::user()->name }}</span> 👋
        </h1>
        <p class="hero-sub">Pantau seluruh pesanan pementasan seni budaya Anda di sini.</p>
    </div>
    <a href="{{ route('klien.bookings.create') }}" class="hero-cta-btn">
        <i class="bi bi-plus-lg me-2"></i>Pesan Pementasan Baru
    </a>
</div>

{{-- ═══════ STAT CARDS ═══════ --}}
@php
    $aktif     = $bookings->whereIn('status', ['pending', 'dp_paid', 'confirmed'])->count();
    $selesai   = $bookings->where('status', 'completed')->count();
    $total     = $bookings->count();
@endphp
<div class="klien-stats-row animate-fade-up" style="animation-delay:0.08s;">
    <div class="kstat-card kstat-active">
        <div class="kstat-icon"><i class="bi bi-clock-history"></i></div>
        <div class="kstat-body">
            <div class="kstat-num">{{ $aktif }}</div>
            <div class="kstat-label">Booking Aktif</div>
        </div>
    </div>
    <div class="kstat-card kstat-done">
        <div class="kstat-icon"><i class="bi bi-check2-all"></i></div>
        <div class="kstat-body">
            <div class="kstat-num">{{ $selesai }}</div>
            <div class="kstat-label">Pementasan Selesai</div>
        </div>
    </div>
    <div class="kstat-card kstat-total">
        <div class="kstat-icon"><i class="bi bi-receipt"></i></div>
        <div class="kstat-body">
            <div class="kstat-num">{{ $total }}</div>
            <div class="kstat-label">Total Pesanan</div>
        </div>
    </div>
</div>

{{-- ═══════ RIWAYAT PESANAN ═══════ --}}
<div class="klien-section-header animate-fade-up" style="animation-delay:0.14s;">
    <h3 class="ksec-title"><i class="bi bi-list-ul me-2"></i>Riwayat Pesanan</h3>
</div>

@forelse($bookings as $booking)
@php
    $statusMap = [
        'pending'   => ['label' => 'Menunggu Konfirmasi', 'icon' => 'bi-hourglass-split',      'cls' => 'status-pending'],
        'dp_paid'   => ['label' => 'DP Terkonfirmasi',    'icon' => 'bi-lock-fill',             'cls' => 'status-dp'],
        'confirmed' => ['label' => 'Jadwal Terkunci',     'icon' => 'bi-calendar2-check-fill',  'cls' => 'status-confirmed'],
        'paid_full' => ['label' => 'Lunas',               'icon' => 'bi-wallet2',               'cls' => 'status-paid'],
        'completed' => ['label' => 'Selesai',             'icon' => 'bi-trophy-fill',           'cls' => 'status-done'],
        'cancelled' => ['label' => 'Dibatalkan',          'icon' => 'bi-x-circle-fill',         'cls' => 'status-cancel'],
    ];
    $st = $statusMap[$booking->status] ?? ['label' => $booking->status, 'icon' => 'bi-circle', 'cls' => ''];
    $delay = number_format(0.18 + $loop->index * 0.05, 2);
@endphp
<div class="kbooking-card animate-fade-up" data-delay="{{ $delay }}">
    <div class="kbooking-left">
        <div class="kbooking-type">{{ ucwords(str_replace('_', ' ', $booking->event_type)) }}</div>
        <div class="kbooking-meta">
            <span><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($booking->event_date)->isoFormat('D MMM Y') }}</span>
            <span class="kbooking-sep">·</span>
            <span><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($booking->venue, 30) }}</span>
        </div>
    </div>
    <div class="kbooking-center">
        <div class="kbooking-status {{ $st['cls'] }}">
            <i class="bi {{ $st['icon'] }} me-1"></i>{{ $st['label'] }}
        </div>
        @if($booking->status === 'pending' && !$booking->payment_proof)
            <div class="kbooking-hint">⏳ Menunggu konfirmasi harga dari Admin</div>
        @elseif($booking->status === 'pending' && $booking->payment_proof)
            <div class="kbooking-hint">🔍 Bukti transfer sedang diverifikasi Admin</div>
        @endif
    </div>
    <div class="kbooking-right">
        <div class="kbooking-price">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
        <div class="kbooking-dp-label">DP: Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
        <a href="{{ route('klien.bookings.show', $booking->id) }}" class="kbooking-detail-btn">
            Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
</div>
@empty
<div class="klien-empty animate-fade-up" style="animation-delay:0.2s;">
    <div class="kempty-icon"><i class="bi bi-calendar-x"></i></div>
    <h5 class="kempty-title">Belum Ada Pesanan</h5>
    <p class="kempty-sub">Wujudkan pementasan seni budaya impian Anda bersama Sanggar Cahaya Gumilang.</p>
    <a href="{{ route('klien.bookings.create') }}" class="klien-btn-gold">
        <i class="bi bi-plus-circle me-2"></i>Mulai Pesan Sekarang
    </a>
</div>
@endforelse

<style>
/* ══════════ KLIEN DASHBOARD ENHANCEMENTS ══════════ */
.klien-hero { display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:20px; padding: 36px 0 20px; }
.hero-eyebrow { font-size:0.75rem; color:rgba(255,255,255,0.7); text-transform:uppercase; letter-spacing:0.1em; display:flex; align-items:center; gap:7px; margin-bottom:12px; }
.hero-dot { width:6px; height:6px; border-radius:50%; background:var(--klien-gold); display:inline-block; }
.hero-title { font-size:2rem; font-weight:800; line-height:1.2; color:#fff; margin:0 0 8px; }
.klien-gold-text { color:var(--klien-gold); }
.hero-sub { color:rgba(255,255,255,0.85); font-size:0.95rem; margin:0; }
.hero-cta-btn {
    background: #FFFFFF;
    color: var(--klien-maroon); font-weight:700; border:none;
    border-radius: 10px; padding: 13px 26px;
    font-size: 0.9rem; white-space: nowrap;
    text-decoration: none; transition: all 0.2s;
    display: inline-flex; align-items: center;
    flex-shrink: 0;
}
.hero-cta-btn:hover { background: var(--klien-body-bg); transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,0,0,0.15); color:var(--klien-maroon); }

/* STAT CARDS */
.klien-stats-row { display:grid; grid-template-columns: repeat(3,1fr); gap:14px; margin-bottom:32px; }
.kstat-card { display:flex; align-items:center; gap:14px; padding:18px 20px; border-radius:14px; border:1px solid var(--klien-border); background:var(--klien-card-bg); transition:transform 0.2s, box-shadow 0.2s; box-shadow: 0 1px 6px rgba(128,0,0,0.06); }
.kstat-card:hover { transform: translateY(-3px); box-shadow: 0 4px 15px rgba(128,0,0,0.1); }
.kstat-icon { width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; }
.kstat-active .kstat-icon { background:rgba(217,119,6,0.1); color:#d97706; }
.kstat-done   .kstat-icon { background:rgba(22,163,74,0.1);  color:#16a34a; }
.kstat-total  .kstat-icon { background:rgba(37,99,235,0.1);  color:#2563eb; }
.kstat-num  { font-size:1.6rem; font-weight:800; color:var(--klien-text); line-height:1; }
.kstat-label{ font-size:0.72rem; color:var(--klien-text-muted); margin-top:3px; }

/* SECTION HEADER */
.klien-section-header { margin-bottom:14px; }
.ksec-title { font-size:1rem; font-weight:700; color:var(--klien-text); }

/* BOOKING CARD LIST */
.kbooking-card {
    display:flex; align-items:center; gap:0;
    background:var(--klien-card-bg); border:1px solid var(--klien-border);
    border-radius:14px; padding:0; margin-bottom:10px;
    overflow:hidden; transition:border-color 0.2s, background 0.2s, box-shadow 0.2s;
    box-shadow: 0 1px 6px rgba(128,0,0,0.04);
}
.kbooking-card:hover { border-color:var(--klien-gold); box-shadow: 0 4px 15px rgba(212,175,55,0.15); }
.kbooking-left { padding:18px 20px; flex:1; min-width:0; }
.kbooking-type { font-weight:700; color:var(--klien-text); font-size:0.95rem; margin-bottom:5px; }
.kbooking-meta { font-size:0.75rem; color:var(--klien-text-muted); display:flex; align-items:center; gap:0; flex-wrap:wrap; }
.kbooking-sep { margin:0 6px; }
.kbooking-center { padding:18px 16px; min-width:180px; }
.kbooking-hint { font-size:0.68rem; color:var(--klien-text-muted); margin-top:5px; }
.kbooking-right { padding:18px 20px; text-align:right; display:flex; flex-direction:column; align-items:flex-end; gap:4px; min-width:170px; border-left:1px solid var(--klien-border); }
.kbooking-price { font-size:1rem; font-weight:800; color:var(--klien-gold); }
.kbooking-dp-label { font-size:0.68rem; color:var(--klien-text-muted); }
.kbooking-detail-btn {
    margin-top:6px; font-size:0.75rem; font-weight:600;
    background:transparent; border:1px solid var(--klien-border);
    color:var(--klien-text-muted); border-radius:7px; padding:5px 12px;
    text-decoration:none; transition:all 0.2s; white-space:nowrap;
}
.kbooking-detail-btn:hover { border-color:var(--klien-gold); color:var(--klien-gold); background:rgba(212,175,55,0.05); }

/* STATUS BADGES */
.kbooking-status { display:inline-flex; align-items:center; font-size:0.73rem; font-weight:600; border-radius:6px; padding:4px 10px; }
.status-pending  { background:rgba(217,119,6,0.1); color:#d97706; }
.status-dp       { background:rgba(37,99,235,0.1); color:#2563eb; }
.status-confirmed{ background:rgba(16,185,129,0.1); color:#059669; }
.status-paid     { background:rgba(34,197,94,0.1); color:#16a34a; }
.status-done     { background:rgba(21,128,61,0.1); color:#15803d; }
.status-cancel   { background:rgba(239,68,68,0.1); color:#dc2626; }

/* EMPTY STATE */
.klien-empty { text-align:center; padding:70px 20px; }
.kempty-icon { font-size:4rem; color:var(--klien-text-muted); opacity: 0.5; margin-bottom:16px; }
.kempty-title { color:var(--klien-text); font-weight:700; font-size:1.1rem; margin-bottom:8px; }
.kempty-sub { color:var(--klien-text-muted); font-size:0.85rem; max-width:340px; margin:0 auto 24px; }
.klien-btn-gold {
    background: var(--klien-maroon);
    color: #FFFFFF; font-weight:700; border:none;
    border-radius:10px; padding:12px 28px;
    text-decoration:none; font-size:0.88rem;
    display:inline-flex; align-items:center; transition:all 0.2s;
}
.klien-btn-gold:hover { background: #600000; color:#FFFFFF; transform:translateY(-2px); box-shadow: 0 4px 15px rgba(128,0,0,0.3); }

@media (max-width:768px) {
    .klien-stats-row { grid-template-columns: 1fr 1fr; }
    .kstat-total { display:none; }
    .kbooking-card { flex-direction:column; align-items:stretch; }
    .kbooking-center, .kbooking-right { border-left:none; border-top:1px solid var(--klien-border); text-align:left; align-items:flex-start; }
}
</style>

@push('scripts')
<script>
document.querySelectorAll('.kbooking-card[data-delay]').forEach(function(el) {
    el.style.animationDelay = el.getAttribute('data-delay') + 's';
});
</script>
@endpush
@endsection
