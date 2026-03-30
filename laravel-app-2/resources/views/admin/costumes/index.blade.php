@extends('layouts.admin')

@section('title', 'Costume Rental - ART-HUB')
@section('page_title', 'Costume Rental Tracker')
@section('page_subtitle', 'Monitor kostum sanggar & sewa vendor.')

@section('content')
<!-- ASET KOSTUM SANGGAR -->
<div class="glass-panel animate-fade-up" style="margin-bottom: 2rem;">
    <h2 style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.8rem;">
        <i class="ph ph-t-shirt" style="color: var(--gold-primary);"></i> Inventaris Kostum Sanggar
    </h2>
    <div class="grid grid-4" style="gap: 1rem;">
        @foreach($sanggarCostumes as $c)
        @php
            $isDamaged = $c->condition === 'damaged';
        @endphp
        <div class="costume-card{{ $isDamaged ? ' costume-damaged' : '' }}" style="padding: 1.5rem; background: rgba(0,0,0,0.2); border-radius: 12px; border-width: 1px; border-style: solid;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.8rem;">
                <h4 style="margin: 0; font-size: 0.95rem;">{{ $c->name }}</h4>
                @if($c->condition === 'good') <span class="badge badge-success" style="font-size: 0.65rem;">BAIK</span>
                @elseif($c->condition === 'damaged') <span class="badge badge-danger" style="font-size: 0.65rem;">RUSAK</span>
                @else <span class="badge badge-warning" style="font-size: 0.65rem;">MAINTENANCE</span>
                @endif
            </div>
            <small class="text-muted">Kategori: {{ $c->category }} • Qty: {{ $c->quantity }}</small>
        </div>
        @endforeach
    </div>
</div>

<!-- SEWA VENDOR -->
<div class="glass-panel animate-fade-up stagger-1">
    <h2 style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.8rem;">
        <i class="ph ph-storefront" style="color: var(--gold-primary);"></i> Sewa Kostum Vendor
    </h2>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Event</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Vendor</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Jenis Kostum</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Qty</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Deadline</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Status</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Denda</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendorRentals as $r)
                <tr style="border-bottom: 1px solid var(--border-color);" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1rem;"><span class="badge badge-gold">{{ $r->event->event_code ?? '-' }}</span></td>
                    <td style="padding: 1rem; font-weight: 600;">{{ $r->vendor->name ?? '-' }}</td>
                    <td style="padding: 1rem;">{{ $r->costume_type }}</td>
                    <td style="padding: 1rem;">{{ $r->quantity }}</td>
                    <td style="padding: 1rem;">
                        <div>{{ \Carbon\Carbon::parse($r->due_date)->format('d M Y') }}</div>
                        @if(!$r->returned_date && \Carbon\Carbon::parse($r->due_date)->isPast())
                            <small style="color: var(--danger); font-weight: 600;">LEWAT DEADLINE!</small>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        @if($r->status === 'rented') <span class="badge badge-warning">AKTIF</span>
                        @elseif($r->status === 'returned') <span class="badge badge-success">KEMBALI</span>
                        @else <span class="badge badge-danger" style="animation: pulse 1.5s infinite;">OVERDUE</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        @if($r->overdue_fine > 0)
                            <span style="color: var(--danger); font-weight: 700;">Rp {{ number_format($r->overdue_fine, 0, ',', '.') }}</span>
                            <br><small class="text-muted">{{ $r->overdue_days }} hari × Rp 50.000</small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<style>
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.5} }
    .costume-card { border-color: var(--border-color); }
    .costume-damaged { border-color: var(--danger) !important; }
</style>
@endsection
