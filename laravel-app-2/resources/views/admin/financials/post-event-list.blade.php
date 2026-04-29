@extends('layouts.admin')
@section('title', 'Post-Event Update – ART-HUB')
@section('page_title', 'Post-Event Update')
@section('page_subtitle', 'Input biaya riil lapangan pasca pementasan')

@section('content')
<style>
    .pe-card { background:#1a1a1a; border:1px solid #2a2a2a; border-radius:12px; overflow:hidden; }
    .pe-row { display:flex; align-items:center; padding:16px 20px; border-bottom:1px solid #222; gap:16px; transition:background 0.15s; }
    .pe-row:last-child { border-bottom:none; }
    .pe-row:hover { background:#212121; }
    .budget-bar { height:6px; border-radius:3px; background:#2a2a2a; overflow:hidden; margin-top:4px; }
    .budget-fill { height:100%; border-radius:3px; transition: width 0.5s ease; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-secondary mb-0">Daftar pementasan yang sudah lewat dan butuh pencatatan biaya lapangan (Bensin, Makan, dll).</p>
</div>

<div class="pe-card">
    @forelse($events as $event)
    @php
        $booking  = $event->booking;
        $finance  = $event->financialRecord;
        $costs    = $finance?->operationalCosts;
        $totalCost= $costs?->sum('actual_amount') ?? 0;
        $budget   = $finance?->operational_budget ?? 0;
        $pct      = $budget > 0 ? min(100, round($totalCost / $budget * 100)) : 0;
        $isDone      = $costs && $costs->count() > 0;
        $budgetColor = $pct >= 100 ? '#ef4444' : ($pct >= 80 ? '#fbbf24' : '#4ade80');
        $pctStyle    = "width:{$pct}%; background:{$budgetColor};";
        $pctColorStyle = "color:{$budgetColor};";
    @endphp
    <div class="pe-row">
        {{-- Event Info --}}
        <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                <span class="fw-bold ">{{ $booking->client_name ?? 'Event Sanggar' }}</span>
                <span class="text-secondary" style="font-size:0.78rem;">{{ $event->event_code }}</span>
                @if($isDone)
                    <span class="badge bg-success-subtle text-success border border-success" style="font-size:0.7rem;">
                        <i class="bi bi-check-circle me-1"></i>Biaya Terinput
                    </span>
                @else
                    <span class="badge bg-warning-subtle text-warning border border-warning" style="font-size:0.7rem;">
                        <i class="bi bi-exclamation-circle me-1"></i>Belum Input
                    </span>
                @endif
            </div>
            <div class="text-secondary" style="font-size:0.8rem;">
                <i class="bi bi-calendar me-1"></i>{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }} &nbsp;
                <i class="bi bi-geo-alt me-1"></i>{{ $event->venue }}
            </div>
        </div>

        {{-- Budget progress --}}
        @if($finance)
        <div style="min-width:160px;">
            <div class="d-flex justify-content-between" style="font-size:0.72rem; color:#888;">
                <span>Terpakai</span>
                <span @style([$pctColorStyle])>{{ $pct }}%</span>
            </div>
            <div class="budget-bar">
                <div class="budget-fill" @style([$pctStyle])></div>
            </div>
            <div style="font-size:0.72rem; color:#888; margin-top:2px;">
                Rp {{ number_format($totalCost, 0, ',', '.') }} / {{ number_format($budget, 0, ',', '.') }}
            </div>
        </div>
        @else
        <div style="min-width:160px;" class="text-center">
            <small class="text-secondary">Finansial belum terbentuk</small>
        </div>
        @endif

        {{-- CTA --}}
        <div>
            @if($finance)
            <a href="{{ route('admin.financials.post_event', $event->id) }}"
               class="btn btn-sm {{ $isDone ? 'btn-outline-success' : 'btn-warning text-dark fw-bold' }}">
                @if($isDone)
                    <i class="bi bi-pencil me-1"></i>Edit Biaya
                @else
                    <i class="bi bi-clipboard-plus me-1"></i>Input Biaya
                @endif
            </a>
            @else
            <span class="btn btn-sm btn-secondary disabled">Konfirmasi DP Dulu</span>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-5 text-secondary">
        <i class="bi bi-calendar-check fs-1 d-block mb-2"></i>
        Belum ada pementasan yang selesai dilaksanakan.
    </div>
    @endforelse
</div>
@endsection

