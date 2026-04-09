@extends('layouts.admin')
@section('title', 'Event Monitoring – ART-HUB')
@section('page_title', 'Event Monitoring')
@section('page_subtitle', 'Pantau status & operasional lapangan seluruh pementasan')

@section('content')
@php
    $statusMap = [
        'pending'   => ['label' => 'Negotiation',  'class' => 'arh-badge-nego',     'icon' => 'bi-chat-dots-fill'],
        'dp_paid'   => ['label' => 'Locked',        'class' => 'arh-badge-locked',   'icon' => 'bi-lock-fill'],
        'confirmed' => ['label' => 'DP 50%',        'class' => 'arh-badge-dp',       'icon' => 'bi-receipt-cutoff'],
        'paid_full' => ['label' => 'PAID (Lunas)',  'class' => 'arh-badge-paid',     'icon' => 'bi-check-circle-fill'],
        'completed' => ['label' => '✓✓ Completed', 'class' => 'arh-badge-completed','icon' => 'bi-patch-check-fill'],
        'cancelled' => ['label' => 'Cancelled',     'class' => 'bg-secondary',       'icon' => 'bi-x-circle-fill'],
    ];
    $filters = [
        'all'       => ['label' => 'All Events',    'icon' => 'bi-grid-3x3-gap-fill'],
        'pending'   => ['label' => 'Negotiation',   'icon' => 'bi-chat-dots-fill'],
        'dp_paid'   => ['label' => 'Locked',         'icon' => 'bi-lock-fill'],
        'confirmed' => ['label' => 'DP 50%',         'icon' => 'bi-receipt-cutoff'],
        'paid_full' => ['label' => 'PAID',           'icon' => 'bi-check-circle-fill'],
        'completed' => ['label' => 'Completed',      'icon' => 'bi-patch-check-fill'],
    ];
@endphp

<style>
    .arh-badge-nego     { background: rgba(251,191,36,0.15); color:#fbbf24; border:1px solid rgba(251,191,36,0.35); }
    .arh-badge-locked   { background: rgba(249,115,22,0.15); color:#f97316; border:1px solid rgba(249,115,22,0.35); }
    .arh-badge-dp       { background: rgba(59,130,246,0.15); color:#60a5fa; border:1px solid rgba(59,130,246,0.35); }
    .arh-badge-paid     { background: rgba(34,197,94,0.15);  color:#4ade80; border:1px solid rgba(34,197,94,0.35); }
    .arh-badge-completed{ background: rgba(21,128,61,0.2);   color:#86efac; border:1px solid rgba(21,128,61,0.4); }
    .arh-badge-status { display:inline-flex; align-items:center; gap:5px; padding: 5px 12px; border-radius:20px; font-size:0.78rem; font-weight:600; }

    .mon-card { background:#1a1a1a; border:1px solid #2a2a2a; border-radius:12px; transition: border-color 0.2s; }
    .mon-card:hover { border-color: rgba(197,160,89,0.4); }

    .filter-tabs { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:24px; }
    .filter-tab {
        padding: 7px 16px; border-radius:20px; border: 1px solid #333;
        background: #1a1a1a; color: #aaa; text-decoration:none; font-size:0.82rem;
        font-weight:500; display:flex; align-items:center; gap:6px; transition: all 0.2s;
    }
    .filter-tab:hover { border-color: #c5a059; color: #c5a059; }
    .filter-tab.active { background: rgba(197,160,89,0.2); border-color: #c5a059; color: #c5a059; }

    .star-badge { color: #fbbf24; font-size: 0.9rem; }

    .summary-card { background:#1a1a1a; border:1px solid #2a2a2a; border-radius:10px; padding: 16px 20px; text-align:center; }
    .summary-num { font-size:1.8rem; font-weight:700; line-height:1; }
    .summary-label { font-size:0.72rem; color:#888; margin-top:4px; text-transform:uppercase; letter-spacing:0.5px; }
</style>

{{-- FILTER TABS --}}
<div class="filter-tabs">
    @foreach($filters as $key => $f)
        <a href="{{ route('admin.events.monitoring', $key !== 'all' ? ['filter' => $key] : []) }}"
           class="filter-tab {{ $filter === $key || ($filter === 'all' && $key === 'all') ? 'active' : '' }}">
            <i class="bi {{ $f['icon'] }}"></i> {{ $f['label'] }}
        </a>
    @endforeach
</div>

{{-- EVENT TABLE --}}
<div class="mon-card mb-5">
    <div class="table-responsive">
        <table class="table table-dark mb-0 align-middle" style="background:transparent;">
            <thead style="border-bottom: 1px solid #333;">
                <tr class="text-secondary" style="font-size:0.78rem; text-transform:uppercase; letter-spacing:0.5px;">
                    <th class="px-4 py-3 fw-semibold">Tanggal Event</th>
                    <th class="px-3 py-3 fw-semibold">Nama Klien</th>
                    <th class="px-3 py-3 fw-semibold">Jenis Acara</th>
                    <th class="px-3 py-3 fw-semibold">Deal Price</th>
                    <th class="px-3 py-3 fw-semibold">Status</th>
                    <th class="px-3 py-3 fw-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                @php
                    $booking    = $event->booking;
                    $bStatus    = $booking->status ?? 'pending';
                    $statusInfo = $statusMap[$bStatus] ?? $statusMap['pending'];
                    $eventDate  = \Carbon\Carbon::parse($event->event_date);
                    $daysUntil  = now()->startOfDay()->diffInDays($eventDate->startOfDay(), false);
                    $isPriority = ($daysUntil >= 0 && $daysUntil <= 3);
                    // Deal price display
                    if ($bStatus === 'pending' && $booking && ($booking->price_min || $booking->price_max)) {
                        $minFmt = $booking->price_min ? 'Rp ' . number_format($booking->price_min/1000000, 0) . 'jt' : '?';
                        $maxFmt = $booking->price_max ? number_format($booking->price_max/1000000, 0) . 'jt' : '?';
                        $priceDisplay = $minFmt . ' – ' . $maxFmt;
                    } elseif ($booking && $booking->total_price) {
                        $priceDisplay = 'Rp ' . number_format($booking->total_price, 0, ',', '.');
                    } else {
                        $priceDisplay = '<span class="text-secondary">–</span>';
                    }
                @endphp
                <tr style="border-bottom: 1px solid #222;">
                    <td class="px-4 py-3">
                        <div class="d-flex align-items-center gap-2">
                            <div>
                                <div class="fw-semibold text-white">{{ $eventDate->format('d M Y') }}</div>
                                <div class="text-secondary" style="font-size:0.75rem;">
                                    {{ \Carbon\Carbon::parse($event->event_start)->format('H:i') }} WIB
                                </div>
                            </div>
                            @if($isPriority)
                                <span class="star-badge" title="Upcoming Priority – H-{{ $daysUntil }}">
                                    ★ <span style="font-size:0.7rem; color:#fbbf24;">H-{{ $daysUntil }}</span>
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-3 py-3">
                        <div class="fw-semibold text-white">{{ $booking->client_name ?? '–' }}</div>
                        <div class="text-secondary" style="font-size:0.75rem;">{{ $event->venue }}</div>
                    </td>
                    <td class="px-3 py-3">
                        <span class="text-white text-capitalize">{{ str_replace('_', ' ', $booking->event_type ?? '–') }}</span>
                    </td>
                    <td class="px-3 py-3">
                        <span class="fw-semibold" style="color:#c5a059;">{!! $priceDisplay !!}</span>
                    </td>
                    <td class="px-3 py-3">
                        <span class="arh-badge-status {{ $statusInfo['class'] }}">
                            <i class="bi {{ $statusInfo['icon'] }}"></i>
                            {{ $statusInfo['label'] }}
                        </span>
                    </td>
                    <td class="px-3 py-3 text-center">
                        <a href="{{ route('admin.events.monitoring.show', $event->id) }}"
                           class="btn btn-sm btn-outline-light rounded-pill px-3" style="font-size:0.8rem;">
                            <i class="bi bi-eye me-1"></i>View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-secondary">
                        <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                        Tidak ada event untuk filter ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- SUMMARY BAR --}}
<div class="row g-3">
    <div class="col-6 col-md-2-4">
        <div class="summary-card">
            <div class="summary-num text-white">{{ $summary['total'] }}</div>
            <div class="summary-label">Total Events</div>
        </div>
    </div>
    <div class="col-6 col-md-2-4">
        <div class="summary-card">
            <div class="summary-num" style="color:#fbbf24;">{{ $summary['negotiation'] }}</div>
            <div class="summary-label">Negotiation</div>
        </div>
    </div>
    <div class="col-6 col-md-2-4">
        <div class="summary-card">
            <div class="summary-num" style="color:#60a5fa;">{{ $summary['pending_dp'] }}</div>
            <div class="summary-label">Pending DP</div>
        </div>
    </div>
    <div class="col-6 col-md-2-4">
        <div class="summary-card">
            <div class="summary-num" style="color:#f97316;">{{ $summary['confirmed'] }}</div>
            <div class="summary-label">Confirmed</div>
        </div>
    </div>
    <div class="col-6 col-md-2-4">
        <div class="summary-card">
            <div class="summary-num" style="color:#4ade80;">{{ $summary['completed'] }}</div>
            <div class="summary-label">Completed</div>
        </div>
    </div>
</div>

@endsection
