@extends('layouts.admin')

@section('title', 'Payment Tracking - ART-HUB')
@section('page_title', 'Payment Tracking')
@section('page_subtitle', 'Monitor status pembayaran semua booking.')

@section('content')
<div class="grid grid-3 animate-fade-up" style="margin-bottom: 2rem;">
    <div class="glass-panel card-gold" style="text-align: center;">
        <h3 class="title-gold" style="margin-bottom: 0.3rem;">Total Revenue</h3>
        <h1 class="title-gold" style="font-size: 2rem; margin-bottom: 0;">Rp {{ number_format($bookings->sum('total_price'), 0, ',', '.') }}</h1>
    </div>
    <div class="glass-panel" style="text-align: center;">
        <h3 style="margin-bottom: 0.3rem;">DP Masuk</h3>
        <h1 style="font-size: 2rem; margin-bottom: 0;">Rp {{ number_format($bookings->where('status', '!=', 'pending')->sum('dp_amount'), 0, ',', '.') }}</h1>
    </div>
    <div class="glass-panel" style="text-align: center;">
        <h3 style="margin-bottom: 0.3rem;">Menunggu DP</h3>
        <h1 style="font-size: 2rem; margin-bottom: 0; color: var(--warning);">{{ $bookings->where('status', 'pending')->count() }}</h1>
    </div>
</div>

<div class="glass-panel animate-fade-up stagger-1">
    <h2 style="margin-bottom: 2rem;"><i class="ph ph-wallet" style="color: var(--gold-primary);"></i> Detail Pembayaran</h2>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">ID</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Klien</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Event</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Total Harga</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">DP (50%)</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Status</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $b)
                <tr style="border-bottom: 1px solid var(--border-color);" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1rem;">#{{ $b->id }}</td>
                    <td style="padding: 1rem; font-weight: 600;">{{ $b->client_name ?? ($b->client->name ?? '-') }}</td>
                    <td style="padding: 1rem; text-transform: capitalize;">{{ $b->event_type }}</td>
                    <td style="padding: 1rem; font-weight: 600;">Rp {{ number_format($b->total_price, 0, ',', '.') }}</td>
                    <td style="padding: 1rem;">Rp {{ number_format($b->dp_amount, 0, ',', '.') }}</td>
                    <td style="padding: 1rem;">
                        @if($b->status === 'pending') <span class="badge badge-warning">PENDING</span>
                        @elseif($b->status === 'dp_paid') <span class="badge badge-gold">DP PAID</span>
                        @elseif($b->status === 'confirmed') <span class="badge badge-success">CONFIRMED</span>
                        @elseif($b->status === 'completed') <span class="badge badge-success">COMPLETE</span>
                        @else <span class="badge badge-danger">CANCELLED</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <a href="{{ route('admin.bookings.show', $b->id) }}" class="btn btn-outline" style="padding: 0.4rem 1rem; font-size: 0.8rem;">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
