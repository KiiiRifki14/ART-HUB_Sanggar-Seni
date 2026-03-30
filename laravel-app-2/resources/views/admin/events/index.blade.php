@extends('layouts.admin')

@section('title', 'Event Management - ART-HUB')
@section('page_title', 'Event Management')
@section('page_subtitle', 'Kelola seluruh event pementasan sanggar.')

@section('content')
<div class="glass-panel animate-fade-up">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="margin: 0; display: flex; align-items: center; gap: 0.8rem;">
            <i class="ph ph-calendar-check" style="color: var(--gold-primary);"></i> Daftar Event
        </h2>
        <span class="badge badge-gold">{{ $events->count() }} Total</span>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Kode</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Jenis</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Tanggal</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Venue</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Status</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                <tr style="border-bottom: 1px solid var(--border-color); transition: 0.2s;" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1rem;"><span class="badge badge-gold">{{ $event->event_code }}</span></td>
                    <td style="padding: 1rem; text-transform: capitalize;">{{ $event->booking->event_type ?? '-' }}</td>
                    <td style="padding: 1rem;">
                        <div style="font-weight: 600;">{{ $event->event_date->format('d M Y') }}</div>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($event->event_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->event_end)->format('H:i') }}</small>
                    </td>
                    <td style="padding: 1rem;">{{ $event->venue }}</td>
                    <td style="padding: 1rem;">
                        @php
                            $statusColors = ['planning' => 'warning', 'rehearsal' => 'warning', 'ready' => 'success', 'ongoing' => 'gold', 'completed' => 'success', 'cancelled' => 'danger'];
                            $badgeClass = 'badge-' . ($statusColors[$event->status] ?? 'gold');
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ strtoupper($event->status) }}</span>
                    </td>
                    <td style="padding: 1rem;">
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-outline" style="padding: 0.4rem 1rem; font-size: 0.8rem;">Detail</a>
                            <a href="{{ route('admin.events.plotting', $event->id) }}" class="btn btn-gold" style="padding: 0.4rem 1rem; font-size: 0.8rem;">Plotting</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="padding: 3rem; text-align: center;" class="text-muted">Belum ada event terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
