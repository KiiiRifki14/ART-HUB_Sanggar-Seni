@extends('layouts.admin')

@section('title', 'Cancellation Handler - ART-HUB')
@section('page_title', 'Cancellation Handler')
@section('page_subtitle', 'Riwayat pembatalan & pengembalian dana.')

@section('content')
<div class="glass-panel animate-fade-up">
    <h2 style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.8rem;">
        <i class="ph ph-shield-warning" style="color: var(--danger);"></i> Daftar Pembatalan
    </h2>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Booking</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Klien</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Tgl Batal</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">H- Event</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Penalti</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Refund</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Alasan</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cancellations as $c)
                <tr style="border-bottom: 1px solid var(--border-color);" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1rem;">#{{ $c->booking_id }}</td>
                    <td style="padding: 1rem; font-weight: 600;">{{ $c->booking->client_name ?? '-' }}</td>
                    <td style="padding: 1rem;">{{ \Carbon\Carbon::parse($c->cancellation_date)->format('d M Y') }}</td>
                    <td style="padding: 1rem;">
                        <span class="badge {{ $c->days_before_event <= 3 ? 'badge-danger' : ($c->days_before_event <= 7 ? 'badge-warning' : 'badge-gold') }}">
                            H-{{ $c->days_before_event }}
                        </span>
                    </td>
                    <td style="padding: 1rem;">
                        <div style="color: var(--danger); font-weight: 700;">Rp {{ number_format($c->penalty_amount, 0, ',', '.') }}</div>
                        <small class="text-muted">{{ number_format($c->penalty_percentage, 0) }}% dari total</small>
                    </td>
                    <td style="padding: 1rem;">
                        @if($c->refund_amount > 0)
                            <span style="color: var(--success); font-weight: 700;">Rp {{ number_format($c->refund_amount, 0, ',', '.') }}</span>
                        @else
                            <span class="text-muted">Rp 0 (Hangus)</span>
                        @endif
                    </td>
                    <td style="padding: 1rem; max-width: 200px;">
                        <small>{{ Str::limit($c->reason, 60) }}</small>
                    </td>
                    <td style="padding: 1rem;">
                        @if($c->status === 'pending') <span class="badge badge-warning">PENDING</span>
                        @elseif($c->status === 'processed') <span class="badge badge-gold">DIPROSES</span>
                        @else <span class="badge badge-success">REFUNDED</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="padding: 3rem; text-align: center;" class="text-muted">Belum ada data pembatalan. Semoga tidak ada. 🙏</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- FORMULA PENALTI INFO -->
    <div style="margin-top: 2rem; padding: 1.5rem; background: rgba(0,0,0,0.2); border-radius: 12px; border: 1px solid var(--border-color);">
        <h4 style="margin-bottom: 1rem; color: var(--gold-light);"><i class="ph ph-function" style="color: var(--gold-primary);"></i> Formula Penalti SQL Function</h4>
        <div class="grid grid-4" style="gap: 1rem;">
            <div style="text-align: center; padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 8px;">
                <div class="title-gold" style="font-size: 1.2rem;">10%</div>
                <small class="text-muted">H-14+</small>
            </div>
            <div style="text-align: center; padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 8px;">
                <div style="color: var(--warning); font-size: 1.2rem; font-weight: 700;">30%</div>
                <small class="text-muted">H-7 s/d H-13</small>
            </div>
            <div style="text-align: center; padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 8px;">
                <div style="color: var(--danger); font-size: 1.2rem; font-weight: 700;">50%</div>
                <small class="text-muted">H-3 s/d H-6</small>
            </div>
            <div style="text-align: center; padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 8px; border: 1px solid var(--danger);">
                <div style="color: var(--danger); font-size: 1.2rem; font-weight: 700;">75%</div>
                <small class="text-muted">H-2 atau kurang</small>
            </div>
        </div>
    </div>
</div>
@endsection
