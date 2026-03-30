@extends('layouts.admin')

@section('title', 'Post-Event Update - ART-HUB')
@section('page_title', 'Post-Event Update')
@section('page_subtitle', 'Audit biaya operasional lapangan ' . ($event->event_code ?? ''))

@section('content')
@php $fr = $event->financialRecord; @endphp

@if($fr)
<div class="grid grid-3 animate-fade-up" style="margin-bottom: 2rem;">
    <div class="glass-panel card-gold" style="text-align: center;">
        <small class="text-muted">Budget Operasional</small>
        <h2 class="title-gold" style="margin: 0.3rem 0 0 0;">Rp {{ number_format($fr->operational_budget, 0, ',', '.') }}</h2>
    </div>
    <div class="glass-panel" style="text-align: center;">
        <small class="text-muted">Realisasi</small>
        @php
            $overBudget = $fr->actual_operational_cost > $fr->operational_budget;
        @endphp
        <h2 style="margin: 0.3rem 0 0 0;" class="{{ $overBudget ? 'text-danger' : '' }}">Rp {{ number_format($fr->actual_operational_cost, 0, ',', '.') }}</h2>
    </div>
    <div class="glass-panel" style="text-align: center;">
        <small class="text-muted">Safety Buffer</small>
        <h2 style="margin: 0.3rem 0 0 0; color: var(--success);">Rp {{ number_format($fr->safety_buffer_amt, 0, ',', '.') }}</h2>
    </div>
</div>

@if($fr->budget_warning)
<div class="glass-panel animate-fade-up" style="border-color: var(--warning); background: var(--warning-glow); margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
    <i class="ph-fill ph-warning" style="color: var(--warning); font-size: 2rem;"></i>
    <div>
        <h4 style="color: var(--warning); margin: 0;">Budget Warning Aktif!</h4>
        <p class="text-muted" style="margin: 0;">{{ $fr->warning_message }}</p>
    </div>
</div>
@endif

<div class="glass-panel animate-fade-up stagger-1">
    <h2 style="margin-bottom: 2rem;"><i class="ph ph-clipboard-text" style="color: var(--gold-primary);"></i> Rincian Biaya Operasional</h2>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Kategori</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Keterangan</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Estimasi</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Realisasi</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Selisih</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fr->operationalCosts as $cost)
                @php $diff = $cost->actual_amount - $cost->estimated_amount; @endphp
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 1rem;"><span class="badge" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); text-transform: capitalize;">{{ str_replace('_', ' ', $cost->category) }}</span></td>
                    <td style="padding: 1rem;">{{ $cost->description }}</td>
                    <td style="padding: 1rem;">Rp {{ number_format($cost->estimated_amount, 0, ',', '.') }}</td>
                    <td style="padding: 1rem; font-weight: 600;">Rp {{ number_format($cost->actual_amount, 0, ',', '.') }}</td>
                    <td style="padding: 1rem;">
                        @if($diff > 0) <span style="color: var(--danger);">+Rp {{ number_format($diff, 0, ',', '.') }}</span>
                        @elseif($diff < 0) <span style="color: var(--success);">-Rp {{ number_format(abs($diff), 0, ',', '.') }}</span>
                        @else <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="padding: 2rem; text-align: center;" class="text-muted">Belum ada data biaya operasional.</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="border-top: 2px solid var(--gold-primary);">
                    <td colspan="2" style="padding: 1rem; font-weight: 700; color: var(--gold-primary);">TOTAL</td>
                    <td style="padding: 1rem; font-weight: 700;">Rp {{ number_format($fr->operationalCosts->sum('estimated_amount'), 0, ',', '.') }}</td>
                    <td style="padding: 1rem; font-weight: 700;">Rp {{ number_format($fr->operationalCosts->sum('actual_amount'), 0, ',', '.') }}</td>
                    <td style="padding: 1rem;">
                        @php $totalDiff = $fr->operationalCosts->sum('actual_amount') - $fr->operationalCosts->sum('estimated_amount'); @endphp
                        @if($totalDiff > 0) <span style="color: var(--danger); font-weight: 700;">+Rp {{ number_format($totalDiff, 0, ',', '.') }}</span>
                        @else <span style="color: var(--success); font-weight: 700;">-Rp {{ number_format(abs($totalDiff), 0, ',', '.') }}</span>
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@else
<div class="glass-panel" style="text-align: center; padding: 3rem;">
    <i class="ph ph-file-dashed" style="font-size: 3rem; color: var(--text-muted);"></i>
    <p class="text-muted" style="margin-top: 1rem;">Belum ada data keuangan untuk event ini. Konfirmasi DP terlebih dahulu.</p>
</div>
@endif

<div style="margin-top: 2rem;">
    <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-outline"><i class="ph ph-arrow-left"></i> Kembali ke Event</a>
    <a href="{{ route('admin.financials.index') }}" class="btn btn-outline" style="margin-left: 0.5rem;"><i class="ph ph-chart-line-up"></i> Laporan Keuangan</a>
</div>
@endsection
