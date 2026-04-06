@extends('layouts.admin')

@section('title', 'Payment & DP – ART-HUB')
@section('page_title', 'Payment & DP Verification')
@section('page_subtitle', 'Verifikasi pembayaran DP dan kunci laba pimpinan.')

@section('content')

{{-- STAT CARDS --}}
@php
    $total    = $bookings->count();
    $pending  = $bookings->where('status','pending')->count();
    $dpPaid   = $bookings->where('status','dp_paid')->count();
    $done     = $bookings->whereIn('status',['confirmed','completed'])->count();
    $canceled = $bookings->where('status','cancelled')->count();
@endphp

<div class="row g-3 mb-4 animate-fade-up">
    <div class="col-6 col-xl-3">
        <div class="arh-card p-3 text-center">
            <i class="bi bi-receipt-cutoff arh-gold fs-3"></i>
            <div class="fw-bold fs-4 mt-1">{{ $total }}</div>
            <small class="text-secondary">Total Booking</small>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="arh-card p-3 text-center" style="border-color: rgba(255,193,7,0.4);">
            <i class="bi bi-hourglass-split text-warning fs-3"></i>
            <div class="fw-bold fs-4 mt-1 text-warning">{{ $pending }}</div>
            <small class="text-secondary">Menunggu DP</small>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="arh-card-gold p-3 text-center">
            <i class="bi bi-lock-fill arh-gold fs-3"></i>
            <div class="fw-bold fs-4 mt-1 arh-gold">{{ $dpPaid }}</div>
            <small class="text-secondary">Laba Terkunci</small>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="arh-card p-3 text-center" style="border-color: rgba(25,135,84,0.4);">
            <i class="bi bi-check-circle-fill text-success fs-3"></i>
            <div class="fw-bold fs-4 mt-1 text-success">{{ $done }}</div>
            <small class="text-secondary">Selesai</small>
        </div>
    </div>
</div>

{{-- TABLE PANEL --}}
<div class="arh-card p-4 animate-fade-up">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-receipt arh-gold"></i> Semua Permintaan Masuk
        </h5>
        <a href="{{ route('admin.bookings.create') }}" class="btn btn-arh-gold btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Manual
        </a>
    </div>

    {{-- Filter Tabs --}}
    <div class="d-flex gap-2 flex-wrap mb-3" id="filter-tabs">
        @php $tabs = ['all'=>"Semua ($total)",'pending'=>"Pending ($pending)",'dp_paid'=>"DP Paid ($dpPaid)",'completed'=>"Selesai ($done)",'cancelled'=>"Batal ($canceled)"] @endphp
        @foreach($tabs as $key => $label)
        <button class="btn btn-sm {{ $key === 'all' ? 'btn-arh-gold' : 'btn-outline-secondary' }}"
                onclick="filterBooking('{{ $key }}', this)">
            {{ $label }}
        </button>
        @endforeach
    </div>

    <div class="table-responsive">
        <table class="table arh-table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>#Booking</th>
                    <th>Klien</th>
                    <th>Event</th>
                    <th>Nilai Kontrak</th>
                    <th>DP</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="booking-tbody">
                @forelse($bookings as $booking)
                @php
                    $statusMap = [
                        'pending'   => ['label'=>'PENDING',   'cls'=>'badge-status-pending'],
                        'dp_paid'   => ['label'=>'DP PAID',   'cls'=>'badge-status-dp_paid'],
                        'confirmed' => ['label'=>'CONFIRMED', 'cls'=>'badge-status-confirmed'],
                        'completed' => ['label'=>'SELESAI',   'cls'=>'badge-status-completed'],
                        'cancelled' => ['label'=>'BATAL',     'cls'=>'badge-status-cancelled'],
                    ];
                    $st = $statusMap[$booking->status] ?? ['label'=>strtoupper($booking->status),'cls'=>'bg-secondary'];
                @endphp
                <tr data-status="{{ $booking->status }}">
                    <td>
                        <span class="badge arh-badge-gold">#{{ str_pad($booking->id, 4,'0',STR_PAD_LEFT) }}</span>
                        <div><small class="text-secondary">{{ $booking->created_at->format('d M Y') }}</small></div>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $booking->client_name ?? ($booking->client->name ?? 'Klien Manual') }}</div>
                        <small class="text-secondary">{{ $booking->client_phone ?? '-' }}</small>
                    </td>
                    <td>
                        <div class="fw-medium text-capitalize">{{ $booking->event_type }}</div>
                        <small class="text-secondary">
                            <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}
                        </small>
                    </td>
                    <td>
                        <div class="fw-bold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                        <small class="arh-gold">Laba: Rp {{ number_format($booking->total_price * 0.30, 0, ',', '.') }}</small>
                    </td>
                    <td>
                        <div class="fw-semibold arh-gold-light">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
                        @if($booking->dp_paid_at)
                            <small class="text-secondary">{{ \Carbon\Carbon::parse($booking->dp_paid_at)->format('d M Y') }}</small>
                        @else
                            <small class="text-warning">Belum bayar</small>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $st['cls'] }}">{{ $st['label'] }}</span>
                        @if($booking->status === 'pending')
                        @php $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($booking->event_date), false); @endphp
                        @if($daysLeft <= 7 && $daysLeft >= 0)
                        <div><small class="text-danger small">⚠️ H-{{ $daysLeft }}</small></div>
                        @endif
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($booking->status === 'pending')
                            <form method="POST" action="{{ route('admin.bookings.confirm', $booking->id) }}"
                                  onsubmit="return confirm('Kunci laba untuk booking #{{ $booking->id }}? Aksi ini TIDAK BISA DIBATALKAN.')">
                                @csrf
                                <button type="submit" class="btn btn-arh-gold btn-sm">
                                    <i class="bi bi-lock-fill"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-secondary">
                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                        Belum ada data booking masuk.
                        <div class="mt-3">
                            <a href="{{ route('admin.bookings.create') }}" class="btn btn-arh-gold btn-sm">
                                <i class="bi bi-plus-circle me-1"></i>Buat Booking Manual
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
function filterBooking(status, btn) {
    document.querySelectorAll('#filter-tabs button').forEach(b => {
        b.classList.remove('btn-arh-gold');
        b.classList.add('btn-outline-secondary');
    });
    btn.classList.remove('btn-outline-secondary');
    btn.classList.add('btn-arh-gold');

    document.querySelectorAll('#booking-tbody tr[data-status]').forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else if (status === 'completed' && (row.dataset.status === 'confirmed' || row.dataset.status === 'completed')) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endsection