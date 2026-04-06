@extends('layouts.admin')

@section('title', 'Cancellation Handler – ART-HUB')
@section('page_title', 'Cancellation Handler')
@section('page_subtitle', 'Riwayat pembatalan & pengembalian dana klien.')

@section('content')
<div class="arh-card p-4 animate-fade-up mb-4">
    <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-shield-exclamation text-danger"></i> Daftar Pembatalan
    </h5>

    <div class="table-responsive">
        <table class="table arh-table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Booking</th>
                    <th>Klien</th>
                    <th>Tgl Batal</th>
                    <th>H- Event</th>
                    <th>Penalti</th>
                    <th>Refund</th>
                    <th>Alasan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cancellations as $c)
                <tr>
                    <td><span class="badge arh-badge-gold">#{{ $c->booking_id }}</span></td>
                    <td class="fw-semibold">{{ $c->booking->client_name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($c->cancellation_date)->format('d M Y') }}</td>
                    <td>
                        @php $days = $c->days_before_event; @endphp
                        <span class="badge bg-{{ $days <= 3 ? 'danger' : ($days <= 7 ? 'warning' : 'secondary') }}">
                            H-{{ $days }}
                        </span>
                    </td>
                    <td>
                        <div class="text-danger fw-bold">Rp {{ number_format($c->penalty_amount, 0, ',', '.') }}</div>
                        <small class="text-secondary">{{ number_format($c->penalty_percentage, 0) }}% dari total</small>
                    </td>
                    <td>
                        @if($c->refund_amount > 0)
                            <span class="text-success fw-bold">Rp {{ number_format($c->refund_amount, 0, ',', '.') }}</span>
                        @else
                            <span class="text-secondary">Rp 0 (Hangus)</span>
                        @endif
                    </td>
                    <td style="max-width: 180px;"><small class="text-secondary">{{ Str::limit($c->reason, 60) }}</small></td>
                    <td>
                        @if($c->status === 'pending')
                            <span class="badge bg-warning text-dark">PENDING</span>
                        @elseif($c->status === 'processed')
                            <span class="badge arh-badge-gold">DIPROSES</span>
                        @else
                            <span class="badge bg-success">REFUNDED</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-secondary">
                        <i class="bi bi-emoji-smile fs-1 d-block mb-3"></i>
                        Belum ada data pembatalan. Semoga tidak ada! 🙏
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── FORMULA PENALTI ── --}}
<div class="arh-card p-4">
    <h6 class="arh-gold fw-bold mb-3">
        <i class="bi bi-calculator me-2"></i>Formula Penalti SQL Function
    </h6>
    <div class="row g-3">
        @foreach([['H-14+','10%','secondary'],['H-7 s/d H-13','30%','warning'],['H-3 s/d H-6','50%','danger'],['H-2 atau kurang','75%','danger']] as [$period, $pct, $color])
        <div class="col-6 col-md-3">
            <div class="text-center p-3 rounded-3 border border-secondary">
                <div class="fs-4 fw-bold text-{{ $color }}">{{ $pct }}</div>
                <small class="text-secondary">{{ $period }}</small>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
