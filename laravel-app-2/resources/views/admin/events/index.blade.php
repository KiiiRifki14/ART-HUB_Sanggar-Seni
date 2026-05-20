@extends('layouts.admin')

@section('title', 'Manajemen Acara – ART-HUB')
@section('page_title', 'Manajemen Acara')
@section('page_subtitle', 'Kelola seluruh event pementasan sanggar.')

@section('content')

{{-- Header --}}
<div class="flex justify-between items-start mb-8">
    <div>
        <h2 class="font-headline text-2xl text-primary font-semibold mb-1">Daftar Event</h2>
        <p class="font-label text-xs uppercase tracking-widest text-outline">{{ $events->count() }} event terdaftar</p>
    </div>

</div>

{{-- ══ TABLE (Desktop) ══ --}}
<div class="hidden md:block bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden overflow-x-auto">
    <table class="w-full min-w-[1000px]">
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
                    'rehearsal' => ['bg-blue-500/10 text-blue-600 border-blue-500/20', 'LATIHAN'],
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

{{-- ══ MOBILE CARDS (Mobile only) ══ --}}
<div class="md:hidden space-y-3 mb-6">
    @forelse($events as $event)
    @php
        $statusMap = [
            'planning'  => ['bg-orange-500/10 text-orange-600 border-orange-500/20', 'PLANNING'],
            'rehearsal' => ['bg-blue-500/10 text-blue-600 border-blue-500/20', 'LATIHAN'],
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
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 bg-surface-container-low border-b border-outline-variant/20">
            <div>
                <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">{{ $event->event_code }}</span>
                <div class="font-label text-[0.6rem] text-outline mt-0.5 uppercase tracking-wider">{{ $event->booking->event_type ?? '—' }}</div>
            </div>
            <span class="inline-block px-2.5 py-1 rounded border font-label text-[0.6rem] font-bold uppercase tracking-wider {{ $badgeClass }}">
                {{ $badgeLabel }}
            </span>
        </div>
        <div class="px-4 py-3 space-y-3">
            <div class="flex justify-between items-start gap-3">
                <div>
                    <div class="font-body font-bold text-sm text-on-surface">{{ $event->booking->client_name ?? '—' }}</div>
                    <div class="font-label text-[0.65rem] text-outline flex items-center gap-1 mt-0.5"><i class="bi bi-calendar3 opacity-60"></i> {{ $event->event_date->format('d M Y') }}</div>
                    <div class="font-label text-[0.65rem] text-outline flex items-center gap-1 mt-0.5"><i class="bi bi-clock opacity-60"></i> {{ \Carbon\Carbon::parse($event->event_start)->format('H:i') }} – {{ \Carbon\Carbon::parse($event->event_end)->format('H:i') }} WIB</div>
                </div>
            </div>
            
            <div class="bg-surface-container rounded-lg p-2.5 flex items-center gap-2">
                <i class="bi bi-geo-alt-fill text-on-surface-variant text-sm flex-shrink-0"></i>
                <div class="font-body text-xs text-on-surface-variant truncate">{{ $event->venue }}</div>
            </div>

            <div class="flex items-center justify-between gap-3">
                <div class="flex-grow max-w-[120px]">
                    <div class="flex justify-between font-label text-[0.6rem] font-bold text-on-surface mb-1">
                        <span class="text-outline">PERSONEL</span>
                        <span>{{ $plotted }}/{{ $needed }}</span>
                    </div>
                    @php $widthStyle = "width: {$plotPct}%"; @endphp
                    <div class="h-1.5 w-full bg-surface-container-high rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $plotPct >= 100 ? 'bg-green-500' : ($plotPct > 50 ? 'bg-secondary' : 'bg-primary/50') }}" style="{{ $widthStyle }}"></div>
                    </div>
                </div>
                <div class="flex gap-2 flex-shrink-0">
                    <a href="{{ route('admin.events.show', $event->id) }}" class="h-8 px-3 rounded-lg bg-surface-container border border-outline-variant/30 flex items-center justify-center text-on-surface-variant font-label text-[0.65rem] font-bold uppercase hover:bg-primary hover:text-white transition-all"><i class="bi bi-eye-fill"></i></a>
                    <a href="{{ route('admin.events.plotting', $event->id) }}" class="h-8 px-3 rounded-lg bg-surface-container border border-outline-variant/30 flex items-center justify-center text-on-surface-variant font-label text-[0.65rem] font-bold uppercase hover:bg-secondary hover:text-white transition-all"><i class="bi bi-diagram-3-fill"></i></a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="py-14 flex flex-col items-center justify-center bg-surface-container-lowest border border-dashed border-outline-variant/30 rounded-xl text-center">
        <i class="bi bi-calendar-x text-4xl text-outline mb-3"></i>
        <p class="font-headline text-base text-on-surface font-semibold">Belum ada event</p>
    </div>
    @endforelse
</div>

@endsection
