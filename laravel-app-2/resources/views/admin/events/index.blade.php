@extends('layouts.admin')

@section('title', 'Event Management – ART-HUB')
@section('page_title', 'Event Management')
@section('page_subtitle', 'Kelola seluruh event pementasan sanggar.')

@section('content')

{{-- Header --}}
<div class="flex justify-between items-start mb-8">
    <div>
        <h2 class="font-headline text-2xl text-primary font-semibold mb-1">Daftar Event</h2>
        <p class="font-label text-xs uppercase tracking-widest text-outline">{{ $events->count() }} event terdaftar</p>
    </div>
    <a href="{{ route('admin.bookings.create') }}"
       class="bg-gradient-to-br from-primary-container to-primary text-white px-6 py-3 rounded-lg font-label text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-md flex items-center gap-2">
        <i class="bi bi-plus-circle-fill"></i> Booking Baru
    </a>
</div>

{{-- Table Card --}}
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
    <table class="w-full">
        <thead class="bg-surface-container-low">
            <tr>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Kode</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Klien & Jenis</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Tanggal & Waktu</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Venue</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Personel</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Status</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/20">
            @forelse($events as $event)
            @php
                $statusMap = [
                    'planning'  => ['bg-orange-500/10 text-orange-600 border-orange-500/20', 'PLANNING'],
                    'rehearsal' => ['bg-blue-500/10 text-blue-600 border-blue-500/20', 'REHEARSAL'],
                    'ready'     => ['bg-green-500/10 text-green-600 border-green-500/20', 'SIAP'],
                    'ongoing'   => ['bg-secondary/10 text-secondary border-secondary/20', 'BERLANGSUNG'],
                    'completed' => ['bg-surface-container-highest text-on-surface-variant border-outline-variant/30', 'SELESAI'],
                    'cancelled' => ['bg-red-500/10 text-red-600 border-red-500/20', 'BATAL'],
                ];
                [$badgeClass, $badgeLabel] = $statusMap[$event->status] ?? ['bg-surface-container text-outline border-outline-variant/30', strtoupper($event->status)];
                $plotted = $event->personnel->count();
                $needed  = $event->personnel_count;
                $plotPct = $needed > 0 ? round(($plotted / $needed) * 100) : 0;
            @endphp
            <tr class="hover:bg-surface-container-low/50 transition-colors">
                <td class="px-6 py-4">
                    <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">
                        {{ $event->event_code }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="font-body font-semibold text-on-surface text-sm">{{ $event->booking->client_name ?? '—' }}</div>
                    <div class="font-label text-xs text-outline uppercase tracking-wider mt-0.5">{{ $event->booking->event_type ?? '—' }}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="font-body font-semibold text-on-surface text-sm">{{ $event->event_date->format('d M Y') }}</div>
                    <div class="font-label text-xs text-outline mt-0.5">
                        {{ \Carbon\Carbon::parse($event->event_start)->format('H:i') }} – {{ \Carbon\Carbon::parse($event->event_end)->format('H:i') }} WIB
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="font-body text-sm text-on-surface max-w-[180px] truncate" title="{{ $event->venue }}">{{ $event->venue }}</div>
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="font-label text-xs font-bold text-on-surface mb-1">{{ $plotted }}/{{ $needed }}</div>
                    @php $widthStyle = "width: {$plotPct}%"; @endphp
                    <div class="h-1.5 w-16 mx-auto bg-surface-container-high rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $plotPct >= 100 ? 'bg-green-500' : ($plotPct > 50 ? 'bg-secondary' : 'bg-primary/50') }}"
                             style="{{ $widthStyle }}"></div>
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-block px-2.5 py-1 rounded border font-label text-[0.65rem] font-bold uppercase tracking-wider {{ $badgeClass }}">
                        {{ $badgeLabel }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.events.show', $event->id) }}"
                           class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-primary hover:text-white transition-all"
                           title="Detail">
                            <i class="bi bi-eye-fill text-sm"></i>
                        </a>
                        <a href="{{ route('admin.events.plotting', $event->id) }}"
                           class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-secondary hover:text-white transition-all"
                           title="Plotting Kru">
                            <i class="bi bi-diagram-3-fill text-sm"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-20 text-center">
                    <i class="bi bi-calendar-x text-4xl text-outline mb-4 block"></i>
                    <p class="font-headline text-lg text-on-surface font-semibold mb-1">Belum ada event</p>
                    <p class="font-label text-xs uppercase tracking-widest text-outline">Buat booking baru untuk memulai event</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
