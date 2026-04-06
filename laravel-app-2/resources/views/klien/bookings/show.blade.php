@extends('layouts.klien')

@section('title', 'Detail & Negosiasi Pemesanan – ART-HUB')

@section('content')
<div class="mb-4 animate-fade-up d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold mb-1">Negotiation <span class="klien-gold">Hub</span></h2>
        <p class="text-secondary">Pantau status pesanan, harga final kontrak, dan unggah bukti pembayaran di sini.</p>
    </div>
    <a href="{{ route('klien.dashboard') }}" class="btn btn-outline-light rounded-pill"><i class="bi bi-arrow-left me-1"></i> Dashboard</a>
</div>

<div class="row g-4 mb-4">
    {{-- Main Status & Detail Panel --}}
    <div class="col-12 col-lg-7 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="glass-card p-4 p-md-5 h-100">
            <div class="d-flex justify-content-between align-items-center border-bottom border-secondary pb-3 mb-4">
                <h5 class="fw-bold mb-0 text-white"><i class="bi bi-receipt-cutoff me-2 klien-gold"></i>Kuitansi #{{ $booking->id }}</h5>
                @php
                    $colors = [
                        'pending'   => 'warning',
                        'dp_paid'   => 'info',
                        'confirmed' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    ];
                    $cl = $colors[$booking->status] ?? 'secondary';
                @endphp
                <span class="badge bg-{{ $cl }} text-dark fs-6 rounded-pill px-3 py-2">{{ strtoupper($booking->status) }}</span>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-6">
                    <small class="text-secondary d-block mb-1">Acara Pementasan</small>
                    <div class="fw-semibold text-white fs-5 text-capitalize">{{ str_replace('_', ' ', $booking->event_type) }}</div>
                </div>
                <div class="col-6">
                    <small class="text-secondary d-block mb-1">Tanggal & Waktu</small>
                    <div class="fw-semibold text-white">{{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}</div>
                    <div class="text-secondary small">{{ \Carbon\Carbon::parse($booking->event_start)->format('H:i') }} WIB – Selesai</div>
                </div>
            </div>

            <div class="mb-5">
                <small class="text-secondary d-block mb-1">Lokasi Pementasan</small>
                <div class="fw-semibold text-white">{{ $booking->venue }}</div>
            </div>

            @if($booking->status === 'pending')
            <div class="alert alert-warning border-warning rounded-4 bg-transparent text-white" style="border-width: 1px;">
                <h6 class="fw-bold text-warning mb-2"><i class="bi bi-info-circle-fill me-1"></i> Proses Peninjauan Cepat</h6>
                <p class="mb-0 small text-secondary">
                    Pesanan Anda sedang diproses oleh pimpinan sanggar. <br>
                    Harga akhir (Deal Price) dapat berubah sesuai kesepakatan negosiasi via WhatsApp sebelum Anda mentransfer DP.
                </p>
            </div>
            @elseif($booking->status === 'dp_paid' && empty($booking->payment_proof))
            {{-- In case admin manually set DP paid but clients wants to see it, edge case. --}}
            @endif
        </div>
    </div>

    {{-- Billing & Payment Panel --}}
    <div class="col-12 col-lg-5 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="glass-card p-4 p-md-5 position-relative h-100" style="border: 1px solid rgba(212, 175, 55, 0.3);">
            
            <h5 class="fw-bold mb-4 text-center border-bottom border-light pb-3">Ringkasan Biaya Kontrak</h5>
            
            <div class="d-flex justify-content-between mb-3 fs-5">
                <span class="text-secondary">Harga Sepakat</span>
                <span class="fw-bold text-white">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-4 fs-5">
                <span class="text-secondary">Tagihan DP (50%)</span>
                <span class="fw-bold klien-gold">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
            </div>
            
            <hr class="border-secondary my-4">

            {{-- ACTION: Upload Bukti --}}
            @if($booking->status === 'pending' || ($booking->status === 'dp_paid' && !$booking->payment_proof))
                @if($booking->payment_proof)
                    <div class="text-center p-3 rounded-3" style="background: rgba(40,167,69,0.1); border: 1px dashed rgba(40,167,69,0.3);">
                        <i class="bi bi-file-earmark-check-fill text-success fs-1 mb-2 d-inline-block"></i>
                        <h6 class="text-success fw-bold">Bukti Bayar Terkirim</h6>
                        <span class="text-secondary small">Menunggu Admin mengunci jadwal & laba.</span>
                    </div>
                @else
                    <div class="text-center mb-3">
                        <small class="text-secondary">Silakan transfer DP ke:</small>
                        <div class="fs-5 mt-1 fw-bold text-white">BCA 1234567890 a/n Cahaya Gumilang</div>
                    </div>
                    
                    <form action="{{ route('klien.bookings.upload_proof', $booking->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input class="form-control bg-transparent text-info" type="file" name="payment_proof" accept="image/*" required>
                            <small class="text-secondary d-block mt-1">Format: JPG, PNG maksimal 5MB.</small>
                        </div>
                        <button type="submit" class="btn btn-klien-gold w-100 py-3 fw-bold rounded-pill">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Konfirmasi Bayar DP
                        </button>
                    </form>
                @endif
            @elseif($booking->status === 'completed')
                 <div class="text-center p-4 rounded-3" style="background: rgba(212,175,55,0.05);">
                    <i class="bi bi-star-fill klien-gold fs-1 d-block mb-3"></i>
                    <h5 class="klien-gold fw-bold">Pementasan Sukses</h5>
                    <p class="text-secondary small">Terima kasih telah mempercayakan pementasan seni budaya pada kami.</p>
                 </div>
            @else
                <div class="text-center p-4 rounded-3" style="background: rgba(40,167,69,0.1); border: 1px dashed rgba(40,167,69,0.3);">
                    <i class="bi bi-calendar2-check-fill text-success fs-1 mb-2 d-inline-block"></i>
                    <h6 class="text-success fw-bold mb-1">Tanggal Terkunci</h6>
                    <span class="text-secondary small">Tim kami sedang bersiap secara maksimal untuk pementasan Anda.</span>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
