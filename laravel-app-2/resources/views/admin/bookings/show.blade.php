@extends('layouts.admin')

@section('title', 'DP Verification - ART-HUB')
@section('page_title', 'DP Verification')
@section('page_subtitle', 'Verifikasi pembayaran & kunci laba pimpinan.')

@section('content')
<div class="grid grid-2 animate-fade-up">
    <!-- INFO BOOKING -->
    <div class="glass-panel card-gold">
        <h3 style="color: var(--gold-light); margin-bottom: 1.5rem;">
            <i class="ph ph-receipt" style="margin-right: 0.5rem;"></i> Detail Booking #{{ $booking->id }}
        </h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div><small class="text-muted">Klien</small><div style="font-weight: 600;">{{ $booking->client_name ?? $booking->client->name ?? '-' }}</div></div>
            <div><small class="text-muted">Telepon</small><div>{{ $booking->client_phone ?? '-' }}</div></div>
            <div><small class="text-muted">Jenis Event</small><div style="text-transform: capitalize;">{{ $booking->event_type }}</div></div>
            <div><small class="text-muted">Tanggal</small><div>{{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}</div></div>
            <div><small class="text-muted">Waktu</small><div>{{ \Carbon\Carbon::parse($booking->event_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->event_end)->format('H:i') }}</div></div>
            <div><small class="text-muted">Venue</small><div>{{ $booking->venue }}</div></div>
            <div><small class="text-muted">Sumber Booking</small><div><span class="badge {{ $booking->booking_source === 'web' ? 'badge-gold' : 'badge-success' }}">{{ strtoupper($booking->booking_source) }}</span></div></div>
            <div><small class="text-muted">Status</small><div>
                @if($booking->status === 'pending') <span class="badge badge-warning">PENDING</span>
                @elseif($booking->status === 'dp_paid') <span class="badge badge-gold">DP PAID</span>
                @elseif($booking->status === 'confirmed') <span class="badge badge-success">CONFIRMED</span>
                @elseif($booking->status === 'completed') <span class="badge badge-success">COMPLETED</span>
                @else <span class="badge badge-danger">CANCELLED</span>
                @endif
            </div></div>
        </div>
    </div>

    <!-- PANEL KEUANGAN -->
    <div class="glass-panel">
        <h3 style="margin-bottom: 1.5rem;"><i class="ph ph-lock-key" style="color: var(--gold-primary); margin-right: 0.5rem;"></i> Kalkulasi Laba</h3>

        <div style="background: rgba(0,0,0,0.2); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                <span class="text-muted">Total Harga</span>
                <span style="font-weight: 700; font-size: 1.2rem;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                <span class="text-muted">DP (50%)</span>
                <span style="font-weight: 700; font-size: 1.2rem; color: var(--gold-primary);">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
            </div>
            <hr style="border: none; border-top: 1px dashed var(--border-color); margin: 1rem 0;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span class="text-muted">Fixed Profit (30%)</span>
                <span class="title-gold" style="font-size: 1.1rem;">Rp {{ number_format($booking->total_price * 0.30, 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span class="text-muted">Budget Operasional</span>
                <span>Rp {{ number_format($booking->dp_amount - ($booking->total_price * 0.30), 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span class="text-muted">Safety Buffer (10%)</span>
                <span style="color: var(--success);">Rp {{ number_format(($booking->dp_amount - ($booking->total_price * 0.30)) * 0.10, 0, ',', '.') }}</span>
            </div>
        </div>

        @if($booking->status === 'pending')
        <form method="POST" action="{{ route('admin.bookings.confirm', $booking->id) }}">
            @csrf
            <button type="submit" class="btn btn-gold" style="width: 100%;" onclick="return confirm('Yakin ingin mengonfirmasi DP dan MENGUNCI LABA? Aksi ini TIDAK BISA DIBATALKAN.')">
                <i class="ph ph-lock-key"></i> Konfirmasi DP & Kunci Laba Pimpinan
            </button>
        </form>
        @elseif($booking->status === 'dp_paid' || $booking->status === 'confirmed')
        <div style="padding: 1rem; background: var(--success-glow); border-radius: 12px; border: 1px solid var(--success); text-align: center;">
            <i class="ph-fill ph-check-circle" style="color: var(--success); font-size: 1.5rem;"></i>
            <p style="margin: 0.5rem 0 0 0; font-weight: 600;">Laba Sudah Terkunci</p>
            <small class="text-muted">{{ $booking->dp_paid_at ? \Carbon\Carbon::parse($booking->dp_paid_at)->format('d M Y H:i') : '-' }}</small>
        </div>
        @endif
    </div>
</div>

<div style="margin-top: 2rem;">
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline"><i class="ph ph-arrow-left"></i> Kembali ke Bookings</a>
</div>
@endsection
