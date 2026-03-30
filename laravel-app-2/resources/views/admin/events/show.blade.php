@extends('layouts.admin')

@section('title', 'Detail Event - ART-HUB')
@section('page_title', $event->event_code)
@section('page_subtitle', 'Detail & Monitoring Event')

@section('content')
<div class="grid grid-3 animate-fade-up">
    <div class="glass-panel card-gold">
        <h3 style="color: var(--gold-light); margin-bottom: 0.3rem;">Info Event</h3>
        <p class="text-muted" style="font-size: 0.85rem;">{{ $event->booking->event_type ?? '-' }}</p>
        <div style="margin-top: 1.5rem;">
            <div style="margin-bottom: 1rem;"><small class="text-muted">Tanggal</small><div style="font-weight: 600;">{{ $event->event_date->format('d M Y') }}</div></div>
            <div style="margin-bottom: 1rem;"><small class="text-muted">Waktu</small><div style="font-weight: 600;">{{ \Carbon\Carbon::parse($event->event_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->event_end)->format('H:i') }}</div></div>
            <div><small class="text-muted">Venue</small><div style="font-weight: 600;">{{ $event->venue }}</div></div>
        </div>
    </div>
    <div class="glass-panel">
        <h3 style="margin-bottom: 0.3rem;">Status</h3>
        @php $sc = ['planning'=>'warning','rehearsal'=>'warning','ready'=>'success','ongoing'=>'gold','completed'=>'success','cancelled'=>'danger']; @endphp
        <span class="badge badge-{{ $sc[$event->status] ?? 'gold' }}" style="font-size: 1rem; padding: 0.5rem 1rem;">{{ strtoupper($event->status) }}</span>
        <div style="margin-top: 1.5rem;">
            <div style="margin-bottom: 1rem;"><small class="text-muted">Personel Diplot</small><div style="font-weight: 600; font-size: 1.5rem;">{{ $event->personnel->count() }}/{{ $event->personnel_count }}</div></div>
            <div><small class="text-muted">Estimasi Honor</small><div class="title-gold" style="font-size: 1.3rem;">Rp {{ number_format($event->estimated_total_honor, 0, ',', '.') }}</div></div>
        </div>
    </div>
    <div class="glass-panel">
        <h3 style="margin-bottom: 0.3rem;">Keuangan</h3>
        @if($event->financialRecord)
            <div style="margin-top: 1rem;">
                <div style="margin-bottom: 1rem;"><small class="text-muted">Revenue</small><div style="font-weight: 600;">Rp {{ number_format($event->financialRecord->total_revenue, 0, ',', '.') }}</div></div>
                <div style="margin-bottom: 1rem;"><small class="text-muted">Fixed Profit</small><div class="title-gold">Rp {{ number_format($event->financialRecord->fixed_profit, 0, ',', '.') }}</div></div>
                <div><small class="text-muted">Budget Ops</small><div>Rp {{ number_format($event->financialRecord->operational_budget, 0, ',', '.') }}</div></div>
            </div>
        @else
            <p class="text-muted" style="margin-top: 1rem;">Belum ada data keuangan.</p>
        @endif
    </div>
</div>

<!-- PERSONEL MONITORING -->
<div class="glass-panel animate-fade-up stagger-1" style="margin-top: 2rem;">
    <h2 style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.8rem;">
        <i class="ph ph-users" style="color: var(--gold-primary);"></i> Personel & Check-in Monitor
    </h2>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 0.8rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Nama</th>
                    <th style="padding: 0.8rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Role</th>
                    <th style="padding: 0.8rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Fee</th>
                    <th style="padding: 0.8rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Check-in</th>
                    <th style="padding: 0.8rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($event->personnel as $p)
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 0.8rem;">
                        <div style="display: flex; align-items: center; gap: 0.8rem;">
                            <div style="width: 35px; height: 35px; border-radius: 50%; background: var(--bg-hover); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; border: 1px solid var(--border-color);">{{ strtoupper(substr($p->user->name, 0, 2)) }}</div>
                            <div>
                                <div style="font-weight: 600;">{{ $p->user->name }}</div>
                                <small class="text-muted">{{ $p->specialty }}</small>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 0.8rem; text-transform: capitalize;">{{ str_replace('_', ' ', $p->pivot->role_in_event) }}</td>
                    <td style="padding: 0.8rem;">Rp {{ number_format($p->pivot->fee, 0, ',', '.') }}</td>
                    <td style="padding: 0.8rem;">{{ $p->pivot->checked_in_at ? \Carbon\Carbon::parse($p->pivot->checked_in_at)->format('H:i') : '-' }}</td>
                    <td style="padding: 0.8rem;">
                        @php $as = $p->pivot->attendance_status; @endphp
                        @if($as === 'on_time') <span class="badge badge-success">ON TIME</span>
                        @elseif($as === 'late') <span class="badge badge-danger">TELAT {{ $p->pivot->late_minutes }}m</span>
                        @else <span class="badge" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color);">BELUM</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 2rem; display: flex; gap: 1rem;">
    <a href="{{ route('admin.events.plotting', $event->id) }}" class="btn btn-gold trigger-loader"><i class="ph ph-users-three"></i> Kelola Plotting</a>
    @if($event->financialRecord)
    <a href="{{ route('admin.financials.post_event', $event->id) }}" class="btn btn-outline"><i class="ph ph-clipboard-text"></i> Post-Event Update</a>
    @endif
    <a href="{{ route('admin.events.index') }}" class="btn btn-outline"><i class="ph ph-arrow-left"></i> Kembali</a>
</div>
@endsection
