@extends('layouts.admin')

@section('title', 'DP Verification – ART-HUB')
@section('page_title', 'DP Verification')
@section('page_subtitle', 'Verifikasi pembayaran & kunci laba pimpinan.')

@section('content')
<div class="row g-4 animate-fade-up">

    {{-- KIRI: INFO BOOKING --}}
    <div class="col-12 col-lg-7">
        <div class="arh-card-gold p-4 h-100">
            <h5 class="fw-bold mb-4 d-flex align-items-center gap-2 arh-gold">
                <i class="bi bi-receipt"></i> Detail Booking #{{ $booking->id }}
            </h5>
            
            <div class="row border-bottom border-secondary pb-3 mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <small class="text-secondary d-block mb-1">Klien</small>
                    <div class="fw-semibold">{{ $booking->client_name ?? $booking->client->name ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <small class="text-secondary d-block mb-1">Telepon</small>
                    <div>{{ $booking->client_phone ?? '-' }}</div>
                </div>
            </div>

            <div class="row border-bottom border-secondary pb-3 mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <small class="text-secondary d-block mb-1">Jenis Event</small>
                    <div class="text-capitalize">{{ $booking->event_type }}</div>
                </div>
                <div class="col-md-6 mb-3 mb-md-0">
                    <small class="text-secondary d-block mb-1">Tanggal</small>
                    <div>{{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}</div>
                </div>
            </div>

            <div class="row border-bottom border-secondary pb-3 mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <small class="text-secondary d-block mb-1">Waktu</small>
                    <div>{{ \Carbon\Carbon::parse($booking->event_start)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->event_end)->format('H:i') }}</div>
                </div>
                <div class="col-md-6">
                    <small class="text-secondary d-block mb-1">Venue</small>
                    <div>{{ $booking->venue }}</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <small class="text-secondary d-block mb-1">Sumber Booking</small>
                    <div>
                        <span class="badge {{ $booking->booking_source === 'web' ? 'arh-badge-gold' : 'bg-success' }}">
                            {{ strtoupper($booking->booking_source) }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <small class="text-secondary d-block mb-1">Status</small>
                    <div>
                        @php
                            $statusMap = [
                                'pending'   => ['label'=>'PENDING',   'cls'=>'bg-warning text-dark'],
                                'dp_paid'   => ['label'=>'DP PAID',   'cls'=>'arh-badge-gold'],
                                'confirmed' => ['label'=>'CONFIRMED', 'cls'=>'bg-success'],
                                'completed' => ['label'=>'COMPLETED', 'cls'=>'bg-success'],
                                'cancelled' => ['label'=>'CANCELLED', 'cls'=>'bg-danger'],
                            ];
                            $st = $statusMap[$booking->status] ?? ['label'=>strtoupper($booking->status),'cls'=>'bg-secondary'];
                        @endphp
                        <span class="badge {{ $st['cls'] }}">{{ $st['label'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KANAN: PANEL KEUANGAN --}}
    <div class="col-12 col-lg-5">
        <div class="arh-card p-4 h-100">
            <h5 class="fw-bold mb-4 d-flex align-items-center gap-2 arh-gold">
                <i class="bi bi-safe2-fill"></i> Kalkulasi Laba
            </h5>

            <div class="p-4 rounded-3 mb-4" style="background: rgba(0,0,0,0.25);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-secondary">Total Harga (Kontrak)</span>
                    <span class="fw-bold fs-5">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-secondary">DP Masuk (50%)</span>
                    <span class="fw-bold fs-5 arh-gold">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
                </div>
                
                <hr class="border-secondary my-3 border-dashed">
                
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary"><i class="bi bi-lock-fill me-1"></i>Fixed Profit (30%)</span>
                    <span class="fw-bold arh-gold" style="font-size: 1.1rem;">Rp {{ number_format($booking->total_price * 0.30, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary">Budget Operasional</span>
                    <span>Rp {{ number_format($booking->dp_amount - ($booking->total_price * 0.30), 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-secondary">Safety Buffer (10%)</span>
                    <span class="text-success fw-semibold">Rp {{ number_format(($booking->dp_amount - ($booking->total_price * 0.30)) * 0.10, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($booking->status === 'pending')
            <form method="POST" action="{{ route('admin.bookings.confirm', $booking->id) }}"
                  onsubmit="return confirm('Kunci laba dari DP booking ini?\nAksi ini TIDAK BISA DIBATALKAN dan akan mengalokasikan profit pimpinan.')">
                @csrf
                <button type="submit" class="btn btn-arh-gold w-100 py-3 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-lock-fill fs-5"></i>
                    <span class="fw-semibold">Konfirmasi DP & Kunci Laba</span>
                </button>
            </form>
            @elseif(in_array($booking->status, ['dp_paid','confirmed','completed']))
            <div class="p-3 text-center rounded-3" style="background: rgba(25,135,84,0.1); border: 1px solid rgba(25,135,84,0.3);">
                <i class="bi bi-patch-check-fill text-success fs-1 mb-2 d-inline-block"></i>
                <div class="fw-bold text-success mb-1">Laba Telah Terkunci Aman</div>
                <small class="text-secondary">Waktu Konfirmasi: {{ $booking->dp_paid_at ? \Carbon\Carbon::parse($booking->dp_paid_at)->format('d M Y H:i') : '-' }}</small>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Booking List
    </a>
</div>
@endsection
