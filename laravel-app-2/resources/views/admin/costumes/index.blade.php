@extends('layouts.admin')

@section('title', 'Costume Rental – ART-HUB')
@section('page_title', 'Costume Rental Tracker')
@section('page_subtitle', 'Monitor kostum sanggar & tracking sewa dari vendor.')

@section('content')

{{-- ── ASET KOSTUM SANGGAR ── --}}
<div class="mb-5 animate-fade-up">
    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2 arh-gold">
        <i class="bi bi-tag-fill"></i> Inventaris Kostum Sanggar
    </h5>
    
    <div class="row g-3">
        @foreach($sanggarCostumes as $c)
        @php
            $isDamaged = $c->condition === 'damaged';
            $bc = $isDamaged ? 'border-danger' : 'border-secondary';
        @endphp
        <div class="col-12 col-md-6 col-xl-3">
            <div class="p-4 rounded-3 h-100 {{ $isDamaged ? 'bg-danger bg-opacity-10 border-danger border' : 'arh-card border border-dark' }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold mb-0 lh-base">{{ $c->name }}</h6>
                    @if($c->condition === 'good') 
                        <span class="badge bg-success">BAIK</span>
                    @elseif($c->condition === 'damaged') 
                        <span class="badge bg-danger">RUSAK</span>
                    @else 
                        <span class="badge bg-warning text-dark">MAINTENANCE</span>
                    @endif
                </div>
                <div class="text-secondary small">
                    <div>Kategori: {{ $c->category }}</div>
                    <div>Qty Sedia: <span class="fw-bold text-white">{{ $c->quantity }}</span></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>


{{-- ── SEWA VENDOR (RENTALS) ── --}}
<div class="arh-card p-4 animate-fade-up">
    <h5 class="fw-bold mb-4 d-flex align-items-center gap-2 arh-gold">
        <i class="bi bi-shop"></i> Transaksi Sewa Vendor Eksternal
    </h5>

    <div class="table-responsive">
        <table class="table arh-table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Vendor</th>
                    <th>Jenis Kostum</th>
                    <th>Qty</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Denda Telat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vendorRentals as $r)
                @php
                    $isOverdue = !$r->returned_date && \Carbon\Carbon::parse($r->due_date)->isPast();
                @endphp
                <tr class="{{ $isOverdue ? 'bg-danger bg-opacity-10' : '' }}">
                    <td><span class="badge arh-badge-gold">{{ $r->event->event_code ?? '-' }}</span></td>
                    <td class="fw-semibold">{{ $r->vendor->name ?? '-' }}</td>
                    <td>{{ $r->costume_type }}</td>
                    <td><span class="badge bg-secondary">{{ $r->quantity }} pcs</span></td>
                    <td>
                        <div>{{ \Carbon\Carbon::parse($r->due_date)->format('d M Y') }}</div>
                        @if($isOverdue)
                            <small class="text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i>LEWAT DEADLINE!</small>
                        @endif
                    </td>
                    <td>
                        @if($r->status === 'rented') 
                            <span class="badge bg-warning text-dark">DIPINJAM</span>
                        @elseif($r->status === 'returned') 
                            <span class="badge bg-success">KEMBALI</span>
                        @else 
                            <span class="badge bg-danger pulse-anim">OVERDUE</span>
                        @endif
                    </td>
                    <td>
                        @if($r->overdue_fine > 0)
                            <div class="text-danger fw-bold">Rp {{ number_format($r->overdue_fine, 0, ',', '.') }}</div>
                            <small class="text-secondary">{{ $r->overdue_days }} hari x Rp50.000</small>
                        @else
                            <span class="text-secondary">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-secondary">
                        Belum ada data persewaan kostum vendor.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
