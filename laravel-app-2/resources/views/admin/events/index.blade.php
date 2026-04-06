@extends('layouts.admin')

@section('title', 'Event Management – ART-HUB')
@section('page_title', 'Event Management')
@section('page_subtitle', 'Kelola seluruh event pementasan sanggar.')

@section('content')
<div class="arh-card p-4 animate-fade-up">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-calendar-check-fill arh-gold"></i> Daftar Event
        </h5>
        <span class="badge arh-badge-gold">{{ $events->count() }} Total</span>
    </div>

    <div class="table-responsive">
        <table class="table arh-table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Jenis</th>
                    <th>Tanggal & Waktu</th>
                    <th>Venue</th>
                    <th>Personel</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                @php
                    $sc = ['planning'=>'warning','rehearsal'=>'warning','ready'=>'success','ongoing'=>'warning','completed'=>'success','cancelled'=>'danger'];
                    $bc = $sc[$event->status] ?? 'secondary';
                @endphp
                <tr>
                    <td><span class="badge arh-badge-gold">{{ $event->event_code }}</span></td>
                    <td class="text-capitalize">{{ $event->booking->event_type ?? '-' }}</td>
                    <td>
                        <div class="fw-semibold">{{ $event->event_date->format('d M Y') }}</div>
                        <small class="text-secondary">
                            {{ \Carbon\Carbon::parse($event->event_start)->format('H:i') }} –
                            {{ \Carbon\Carbon::parse($event->event_end)->format('H:i') }}
                        </small>
                    </td>
                    <td><small>{{ $event->venue }}</small></td>
                    <td>
                        <span class="badge bg-secondary">
                            {{ $event->personnel->count() }}/{{ $event->personnel_count }}
                        </span>
                    </td>
                    <td><span class="badge bg-{{ $bc }}">{{ strtoupper($event->status) }}</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.events.plotting', $event->id) }}" class="btn btn-arh-gold btn-sm">
                                <i class="bi bi-diagram-3"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-secondary">
                        <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                        Belum ada event terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
