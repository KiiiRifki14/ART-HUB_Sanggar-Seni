@extends('layouts.klien')

@section('title', 'Dashboard Klien – ART-HUB')

@section('content')
<div class="mb-5 animate-fade-up">
    <h2 class="fw-bold mb-1">Selamat datang, <span class="klien-gold">{{ Auth::user()->name }}</span></h2>
    <p class="text-secondary">Kelola pesanan pementasan seni Anda di sini.</p>
</div>

<div class="row g-4 mb-5 animate-fade-up" style="animation-delay: 0.1s;">
    <div class="col-12 col-md-6">
        <div class="glass-card p-4 d-flex align-items-center justify-content-between h-100">
            <div>
                <h6 class="text-secondary mb-1">Booking Aktif</h6>
                <h3 class="fw-bold mb-0">{{ $bookings->whereIn('status', ['pending', 'dp_paid', 'confirmed'])->count() }} Pesanan</h3>
            </div>
            <div class="rounded-circle d-flex justify-content-center align-items-center" style="width: 50px; height: 50px; background: rgba(212, 175, 55, 0.2); color: var(--klien-gold);">
                <i class="bi bi-clock-history fs-3"></i>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="glass-card p-4 d-flex align-items-center justify-content-between h-100">
            <div>
                <h6 class="text-secondary mb-1">Pementasan Selesai</h6>
                <h3 class="fw-bold mb-0">{{ $bookings->where('status', 'completed')->count() }} Pementasan</h3>
            </div>
            <div class="rounded-circle d-flex justify-content-center align-items-center" style="width: 50px; height: 50px; background: rgba(40, 167, 69, 0.2); color: #28a745;">
                <i class="bi bi-check2-circle fs-3"></i>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 animate-fade-up" style="animation-delay: 0.2s;">
    <h4 class="fw-bold m-0 text-white"><i class="bi bi-receipt me-2"></i>Riwayat Pesanan Anda</h4>
    <a href="{{ route('klien.bookings.create') }}" class="btn btn-klien-gold btn-sm"><i class="bi bi-plus-lg me-1"></i> Pesan Baru</a>
</div>

<div class="glass-card p-4 animate-fade-up" style="animation-delay: 0.3s;">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0 align-middle" style="background: transparent;">
            <thead>
                <tr class="text-secondary" style="border-bottom: 2px solid rgba(255,255,255,0.1);">
                    <th class="fw-normal pb-3">Tanggal Event</th>
                    <th class="fw-normal pb-3">Jenis Paket</th>
                    <th class="fw-normal pb-3">Status</th>
                    <th class="fw-normal pb-3">Total Harga</th>
                    <th class="fw-normal pb-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td class="py-3">
                        <div class="fw-semibold">{{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}</div>
                        <small class="text-secondary">{{ $booking->venue }}</small>
                    </td>
                    <td class="py-3 text-capitalize">{{ str_replace('_', ' ', $booking->event_type) }}</td>
                    <td class="py-3">
                        @php
                            $colors = [
                                'pending' => 'warning',
                                'dp_paid' => 'info',
                                'confirmed' => 'primary',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                            ];
                            $color = $colors[$booking->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $color }} text-dark rounded-pill px-3">{{ strtoupper($booking->status) }}</span>
                    </td>
                    <td class="py-3 fw-bold klien-gold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td class="py-3">
                        <a href="{{ route('klien.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-light rounded-pill px-3 text-white border-secondary">
                            Detail Kuitansi
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="bi bi-inbox-fill text-secondary fs-1 mb-3 d-inline-block"></i>
                        <p class="text-secondary mb-3">Anda belum pernah melakukan pemesanan.</p>
                        <a href="{{ route('klien.bookings.create') }}" class="btn btn-klien-gold">Pesan Sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
