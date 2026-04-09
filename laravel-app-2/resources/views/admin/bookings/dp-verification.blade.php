@extends('layouts.admin')
@section('title', 'DP Verification – ART-HUB')
@section('page_title', 'DP Verification')
@section('page_subtitle', 'Inbox validasi bukti transfer masuk dari Klien')

@section('content')
<style>
    .inbox-card { background:#1a1a1a; border:1px solid #2a2a2a; border-radius:12px; }
    .inbox-row { display:flex; align-items:center; gap:16px; padding:16px 20px; border-bottom:1px solid #222; transition: background 0.15s; }
    .inbox-row:last-child { border-bottom:none; }
    .inbox-row:hover { background:#212121; }
    .proof-thumb {
        width:52px; height:52px; object-fit:cover; border-radius:8px;
        border:2px solid #333; cursor:pointer; transition: border-color 0.2s;
    }
    .proof-thumb:hover { border-color: #c5a059; }
    .proof-placeholder {
        width:52px; height:52px; border-radius:8px;
        background:#252525; border:2px dashed #444;
        display:flex; align-items:center; justify-content:center; color:#555;
    }
    .inbox-empty { padding: 48px; text-align:center; color:#555; }
</style>

{{-- SECTION A: Menunggu Konfirmasi (Ada Bukti) --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="fw-bold mb-0 text-white">
        <i class="bi bi-inbox-fill me-2" style="color:#c5a059;"></i>
        Bukti Diterima – Menunggu Konfirmasi Admin
        @if($pendingWithProof->count() > 0)
            <span class="badge rounded-pill ms-2" style="background:#c5a059; color:#000; font-size:0.78rem;">
                {{ $pendingWithProof->count() }}
            </span>
        @endif
    </h5>
</div>

<div class="inbox-card mb-5">
    @forelse($pendingWithProof as $booking)
    <div class="inbox-row">
        {{-- Proof Thumbnail --}}
        @if($booking->payment_proof)
            <img src="{{ asset('storage/' . $booking->payment_proof) }}"
                 class="proof-thumb"
                 data-bs-toggle="modal" data-bs-target="#modalProof{{ $booking->id }}"
                 alt="Bukti Bayar">
        @else
            <div class="proof-placeholder"><i class="bi bi-image fs-4"></i></div>
        @endif

        {{-- Info --}}
        <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="fw-bold text-white">{{ $booking->client_name }}</span>
                <span class="badge bg-warning text-dark" style="font-size:0.7rem;">Pending</span>
            </div>
            <div class="text-secondary" style="font-size:0.8rem;">
                <i class="bi bi-calendar me-1"></i>{{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }} &nbsp;
                <i class="bi bi-geo-alt me-1"></i>{{ $booking->venue }}
            </div>
        </div>

        {{-- Jumlah DP --}}
        <div class="text-end me-3">
            <div class="text-secondary" style="font-size:0.72rem;">Tagihan DP (50%)</div>
            <div class="fw-bold" style="color:#c5a059;">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
        </div>

        {{-- Aksi --}}
        <div class="d-flex flex-column gap-2" style="min-width:140px;">
            <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST"
                  onsubmit="return confirm('Konfirmasi DP & Kunci Laba untuk booking {{ $booking->client_name }}?')">
                @csrf
                <button type="submit" class="btn btn-sm w-100 fw-bold" style="background:#c5a059; color:#000; font-size:0.78rem;">
                    <i class="bi bi-lock-fill me-1"></i>Kunci Laba & Konfirmasi
                </button>
            </form>
            <a href="{{ route('admin.bookings.show', $booking->id) }}"
               class="btn btn-sm btn-outline-secondary w-100" style="font-size:0.78rem;">
                <i class="bi bi-eye me-1"></i>Detail Booking
            </a>
        </div>
    </div>

    {{-- Modal Preview Bukti --}}
    @if($booking->payment_proof)
    <div class="modal fade" id="modalProof{{ $booking->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark border-secondary text-center">
                <div class="modal-header border-secondary">
                    <h6 class="modal-title text-white">Bukti Transfer – {{ $booking->client_name }}</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <img src="{{ asset('storage/' . $booking->payment_proof) }}"
                         class="img-fluid rounded-3" style="max-height:70vh;">
                </div>
                <div class="modal-footer border-secondary justify-content-center">
                    <a href="{{ asset('storage/' . $booking->payment_proof) }}" download
                       class="btn btn-sm btn-outline-light">
                        <i class="bi bi-download me-1"></i>Unduh Bukti
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @empty
    <div class="inbox-empty">
        <i class="bi bi-patch-check-fill fs-1 d-block mb-2 text-success"></i>
        Tidak ada bukti transfer yang menunggu konfirmasi.<br>
        <small class="text-secondary">Semua DP sudah diverifikasi!</small>
    </div>
    @endforelse
</div>

{{-- SECTION B: Menunggu Klien Upload Bukti --}}
<h5 class="fw-bold mb-3 text-white">
    <i class="bi bi-hourglass-split me-2 text-secondary"></i>
    Menunggu Klien Upload Bukti Transfer
    <span class="badge rounded-pill bg-secondary ms-2" style="font-size:0.78rem;">{{ $pendingNoProof->count() }}</span>
</h5>

<div class="inbox-card">
    @forelse($pendingNoProof as $booking)
    <div class="inbox-row">
        <div class="proof-placeholder"><i class="bi bi-cloud-upload fs-5 text-secondary"></i></div>

        <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="fw-bold text-white">{{ $booking->client_name }}</span>
                <span class="badge bg-secondary" style="font-size:0.7rem;">Belum Upload</span>
                @php
                    $createdDays = \Carbon\Carbon::parse($booking->created_at)->diffInDays(now());
                @endphp
                @if($createdDays > 3)
                    <span class="badge bg-danger" style="font-size:0.7rem;">
                        <i class="bi bi-exclamation-triangle me-1"></i>Overdue {{ $createdDays }} hari
                    </span>
                @endif
            </div>
            <div class="text-secondary" style="font-size:0.8rem;">
                <i class="bi bi-calendar me-1"></i>{{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }} &nbsp;
                <i class="bi bi-phone me-1"></i>{{ $booking->client_phone }}
            </div>
        </div>

        <div class="text-end me-3">
            <div class="text-secondary" style="font-size:0.72rem;">Total Kontrak</div>
            @if($booking->price_min && $booking->price_max)
                <div class="fw-semibold text-warning" style="font-size:0.85rem;">
                    Rp {{ number_format($booking->price_min/1000000, 0, ',', '.') }}jt –
                    {{ number_format($booking->price_max/1000000, 0, ',', '.') }}jt
                </div>
            @else
                <div class="fw-bold" style="color:#c5a059;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
            @endif
        </div>

        <a href="{{ route('admin.bookings.show', $booking->id) }}"
           class="btn btn-sm btn-outline-secondary" style="font-size:0.78rem; min-width:100px;">
            <i class="bi bi-pencil me-1"></i>Negotiate
        </a>
    </div>
    @empty
    <div class="inbox-empty">
        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
        Tidak ada booking yang masih menunggu.
    </div>
    @endforelse
</div>

@endsection
