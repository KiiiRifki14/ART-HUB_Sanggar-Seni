@extends('layouts.admin')

@section('title', 'Post-Event Update – ART-HUB')
@section('page_title', 'Post-Event Update')
@section('page_subtitle', 'Audit biaya operasional lapangan ' . ($event->event_code ?? ''))

@section('content')
@php $fr = $event->financialRecord; @endphp

@if($fr)
    {{-- ── SUMMARY CARDS ── --}}
    <div class="row g-3 mb-4 animate-fade-up">
        <div class="col-12 col-md-4">
            <div class="arh-card-gold p-4 text-center h-100">
                <small class="text-secondary d-block fw-semibold mb-1">Budget Operasional Awal</small>
                <h3 class="fw-bold arh-gold mb-0 fs-4">Rp {{ number_format($fr->operational_budget, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-12 col-md-4">
            @php $overBudget = $fr->actual_operational_cost > $fr->operational_budget; @endphp
            <div class="arh-card p-4 text-center h-100 {{ $overBudget ? 'border-danger bg-danger bg-opacity-10' : '' }}">
                <small class="text-secondary d-block fw-semibold mb-1">Realisasi Lapangan</small>
                <h3 class="fw-bold mb-0 fs-4 {{ $overBudget ? 'text-danger' : '' }}">
                    Rp {{ number_format($fr->actual_operational_cost, 0, ',', '.') }}
                </h3>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="arh-card p-4 text-center h-100" style="border-color: rgba(25,135,84,0.3);">
                <small class="text-secondary d-block fw-semibold mb-1">Safety Buffer Area</small>
                <h3 class="fw-bold text-success mb-0 fs-4">Rp {{ number_format($fr->safety_buffer_amt, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    {{-- ── BUDGET WARNING ── --}}
    @if($fr->budget_warning)
    <div class="alert bg-warning bg-opacity-10 border border-warning d-flex align-items-center gap-3 mb-4 animate-fade-up p-3">
        <i class="bi bi-exclamation-triangle-fill text-warning fs-1"></i>
        <div>
            <h6 class="fw-bold text-warning mb-1">Budget Warning Aktif!</h6>
            <p class="text-secondary small mb-0">{{ $fr->warning_message }}</p>
        </div>
    </div>
    @endif

    {{-- ── RINCIAN BIAYA OPS ── --}}
    <div class="arh-card p-4 animate-fade-up">
        <h5 class="fw-bold mb-4 d-flex align-items-center gap-2 arh-gold">
            <i class="bi bi-list-check"></i> Rincian Biaya Operasional
        </h5>
        
        <div class="table-responsive">
            <table class="table arh-table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th>Estimasi</th>
                        <th>Realisasi Lapangan</th>
                        <th>Selisih</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fr->operationalCosts as $cost)
                    @php $diff = $cost->actual_amount - $cost->estimated_amount; @endphp
                    <tr>
                        <td>
                            <span class="badge bg-secondary text-capitalize">
                                {{ str_replace('_', ' ', $cost->category) }}
                            </span>
                        </td>
                        <td>{{ $cost->description }}</td>
                        <td>Rp {{ number_format($cost->estimated_amount, 0, ',', '.') }}</td>
                        <td class="fw-bold">Rp {{ number_format($cost->actual_amount, 0, ',', '.') }}</td>
                        <td>
                            @if($diff > 0) 
                                <span class="text-danger fw-semibold">+Rp {{ number_format($diff, 0, ',', '.') }}</span>
                            @elseif($diff < 0) 
                                <span class="text-success fw-semibold">-Rp {{ number_format(abs($diff), 0, ',', '.') }}</span>
                            @else 
                                <span class="text-secondary">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-secondary">Belum ada data input biaya operasional tambahan.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-black bg-opacity-50 border-top border-2" style="border-color: var(--arh-gold) !important;">
                    <tr>
                        <td colspan="2" class="fw-bold arh-gold py-3 text-end">TOTAL KESELURUHAN</td>
                        <td class="fw-bold py-3">Rp {{ number_format($fr->operationalCosts->sum('estimated_amount'), 0, ',', '.') }}</td>
                        <td class="fw-bold py-3 fs-6">Rp {{ number_format($fr->operationalCosts->sum('actual_amount'), 0, ',', '.') }}</td>
                        <td class="py-3">
                            @php $totalDiff = $fr->operationalCosts->sum('actual_amount') - $fr->operationalCosts->sum('estimated_amount'); @endphp
                            @if($totalDiff > 0) 
                                <span class="text-danger fw-bold fs-6">+Rp {{ number_format($totalDiff, 0, ',', '.') }}</span>
                            @else 
                                <span class="text-success fw-bold fs-6">-Rp {{ number_format(abs($totalDiff), 0, ',', '.') }}</span>
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@else
    {{-- NO DATA STATE --}}
    <div class="text-center py-5 border border-secondary rounded-3 bg-black bg-opacity-25 animate-fade-up">
        <i class="bi bi-file-earmark-x text-secondary" style="font-size: 3rem;"></i>
        <p class="text-secondary mt-3 mb-0">Belum ada data keuangan untuk event ini.<br>Konfirmasi DP terlebih dahulu dari halaman Booking.</p>
    </div>
@endif

{{-- ── TOMBOL NAVIGASI ── --}}
<div class="mt-4 d-flex gap-2 animate-fade-up">
    <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Event
    </a>
    <a href="{{ route('admin.financials.index') }}" class="btn btn-outline-info">
        <i class="bi bi-graph-up-arrow me-1"></i> Laporan Keuangan
    </a>
</div>
@endsection

