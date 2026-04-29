@extends('layouts.admin')

@section('title', 'Payment Tracking – ART-HUB')
@section('page_title', 'Payment Tracking')
@section('page_subtitle', 'Pantau status pelunasan klien pasca-DP & pasca-event.')

@section('content')

{{-- STAT CARDS --}}
@php
    $total    = $bookings->count();
    $unpaid   = $bookings->where('status','completed')->whereNull('pelunasan_at')->count();
    $piutang  = $bookings->where('status','completed')->whereNull('pelunasan_at')->sum(function($b) {
                    return $b->total_price - $b->dp_amount;
                });
    $lunas    = $bookings->whereNotNull('pelunasan_at')->count();
@endphp

<div class="row g-3 mb-4 animate-fade-up">
    <div class="col-6 col-md-4">
        <div class="arh-card-gold p-4 h-100 text-center">
            <i class="bi bi-cash-stack arh-gold fs-1 d-inline-block mb-2"></i>
            <h3 class="fw-bold fs-3 mb-0 arh-gold">Rp {{ number_format($piutang, 0, ',', '.') }}</h3>
            <small class="text-secondary fw-semibold">Total Piutang Berjalan</small>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="arh-card p-4 h-100 text-center" style="border-color: rgba(220,53,69,0.4);">
            <i class="bi bi-hourglass-top text-danger fs-1 d-inline-block mb-2"></i>
            <h3 class="fw-bold fs-3 mb-0 text-danger">{{ $unpaid }} Event</h3>
            <small class="text-secondary fw-semibold">Belum Lunas (Selesai Event)</small>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="arh-card p-4 h-100 text-center" style="border-color: rgba(25,135,84,0.4);">
            <i class="bi bi-check2-all text-success fs-1 d-inline-block mb-2"></i>
            <h3 class="fw-bold fs-3 mb-0 text-success">{{ $lunas }} / {{ $total }}</h3>
            <small class="text-secondary fw-semibold">Transaksi Lunas</small>
        </div>
    </div>
</div>

{{-- TABLE PANEL --}}
<div class="arh-card p-4 animate-fade-up">
    <h5 class="fw-bold mb-4 d-flex align-items-center gap-2 arh-gold">
        <i class="bi bi-journal-check"></i> Daftar Tagihan
    </h5>

    <div class="table-responsive">
        <table class="table arh-table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>#Booking / Event</th>
                    <th>Klien</th>
                    <th>Total Kontrak</th>
                    <th>DP Masuk</th>
                    <th>Sisa Tagihan</th>
                    <th>Status Event</th>
                    <th>Status Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                @php
                    $sisa = $booking->total_price - $booking->dp_amount;
                    $isLunas = !is_null($booking->pelunasan_at) || ($booking->total_price > 0 && $sisa <= 0);
                    $isOverdue = !$isLunas && in_array($booking->status, ['completed']);
                    $stName = strtoupper($booking->status);
                @endphp
                <tr class="{{ $isOverdue ? 'bg-danger bg-opacity-10 border-danger border' : '' }}">
                    <td>
                        <span class="badge arh-badge-gold">#{{ str_pad($booking->id, 4,'0',STR_PAD_LEFT) }}</span>
                        @if($booking->event)
                            <div><small class="text-secondary">{{ $booking->event->event_code }}</small></div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $booking->client_name ?? ($booking->client->name ?? '-') }}</div>
                        <small class="text-secondary">{{ $booking->client_phone ?? '-' }}</small>
                    </td>
                    <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td class="text-success">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</td>
                    <td>
                        <div class="fw-bold fs-6 {{ $isOverdue ? 'text-danger' : 'arh-gold' }}">
                            Rp {{ number_format($sisa, 0, ',', '.') }}
                        </div>
                    </td>
                    <td>
                        @if($booking->status === 'pending') <span class="badge bg-secondary">PENDING</span>
                        @elseif($booking->status === 'completed') <span class="badge bg-info text-dark">SELESAI</span>
                        @else <span class="badge bg-secondary">{{ $stName }}</span>
                        @endif
                    </td>
                    <td>
                        @if($isLunas)
                            <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>LUNAS</span>
                            <div class="text-secondary small mt-1">{{ \Carbon\Carbon::parse($booking->pelunasan_at ?? now())->format('d M Y') }}</div>
                        @else
                            @if($booking->status === 'completed')
                                @php $sisaFormatted = number_format($sisa, 0, ',', '.'); @endphp
                                <form method="POST" action="#" onsubmit="return confirm('Tandai pelunasan tagihan ini sebesar Rp {{ $sisaFormatted }}?')">
                                    @csrf
                                    <button type="button" class="btn btn-outline-success btn-sm w-100" onclick="alert('Demo: Fitur proses pelunasan segera diaktifkan')">
                                        <i class="bi bi-check-circle me-1"></i>Tandai Lunas
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>MENUNGGU EVENT</span>
                            @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-secondary">
                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                        Belum ada tagihan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
