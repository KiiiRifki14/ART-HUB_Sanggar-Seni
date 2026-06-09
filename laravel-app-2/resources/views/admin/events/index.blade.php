@extends('layouts.admin')

@section('title', 'Manajemen Acara – ART-HUB')
@section('page_title', 'Manajemen Acara')
@section('page_subtitle', 'Kelola seluruh event pementasan sanggar.')

@section('content')

{{-- Header --}}
<div class="flex justify-between items-start mb-8">
    <div>
        <h2 class="font-headline text-2xl text-primary font-bold mb-1">Daftar Event</h2>
        <p class="font-label text-xs uppercase tracking-widest text-outline">
            {{ $events instanceof \Illuminate\Pagination\LengthAwarePaginator ? $events->total() : $events->count() }} event terdaftar
        </p>
    </div>
</div>

{{-- ══ TABLE (Desktop) ══ --}}
<div class="hidden md:block bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[1000px]">
            <thead class="bg-surface-container-low border-b border-outline-variant/20">
                <tr>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Kode</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Klien & Jenis</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Tanggal & Waktu</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Venue</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Personel</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Status</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/15">
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
                        <span class="inline-block px-3 py-1 rounded-md bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">
                            {{ $event->event_code }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-body font-semibold text-on-surface text-sm">{{ $event->booking->client_name ?? '—' }}</div>
                        <div class="font-label text-xs text-outline uppercase tracking-wider mt-0.5">{{ str_replace('_', ' ', $event->booking->event_type ?? '—') }}</div>
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
                        <div class="font-label text-xs font-bold text-on-surface mb-1">{{ $plotted }}/{{ $needed }} Plotted</div>
                        @php $widthStyle = "width: {$plotPct}%"; @endphp
                        <div class="h-1.5 w-16 mx-auto bg-surface-container-high rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $plotPct >= 100 ? 'bg-green-500' : ($plotPct > 50 ? 'bg-secondary' : 'bg-primary/50') }}"
                                 style="{{ $widthStyle }}"></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border font-label text-[0.65rem] font-bold uppercase tracking-wider {{ $badgeClass }}">
                            {{ $badgeLabel }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.events.show', $event->id) }}"
                               class="w-9 h-9 rounded-lg border border-outline-variant/40 text-outline hover:text-primary hover:border-primary flex items-center justify-center hover:bg-primary/5 transition-all"
                               title="Detail / Kelola">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('admin.events.plotting', $event->id) }}"
                               class="w-9 h-9 rounded-lg border border-outline-variant/40 text-outline hover:text-secondary hover:border-secondary flex items-center justify-center hover:bg-secondary/5 transition-all"
                               title="Plotting Kru">
                                <i data-lucide="users" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center text-outline">
                        <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-3 opacity-30 text-primary"></i>
                        <p class="font-headline text-lg font-bold text-on-surface mb-1">Belum Ada Event</p>
                        <p class="font-body text-sm text-outline">Buat booking baru untuk memulai event pementasan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ══ MOBILE CARDS (Mobile only) ══ --}}
<div class="md:hidden space-y-4 mb-6">
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
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 bg-surface-container-low border-b border-outline-variant/20">
            <div>
                <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">{{ $event->event_code }}</span>
                <div class="font-label text-[0.6rem] text-outline mt-1 uppercase tracking-wider">{{ str_replace('_', ' ', $event->booking->event_type ?? '—') }}</div>
            </div>
            <span class="inline-block px-2.5 py-1 rounded-full border font-label text-[0.6rem] font-bold uppercase tracking-wider {{ $badgeClass }}">
                {{ $badgeLabel }}
            </span>
        </div>
        <div class="px-4 py-4 space-y-4">
            <div class="flex justify-between items-start gap-3">
                <div>
                    <div class="font-body font-bold text-sm text-on-surface">{{ $event->booking->client_name ?? '—' }}</div>
                    <div class="font-label text-xs text-outline flex items-center gap-1.5 mt-1.5">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 opacity-60"></i> {{ $event->event_date->format('d M Y') }}
                    </div>
                    <div class="font-label text-xs text-outline flex items-center gap-1.5 mt-1">
                        <i data-lucide="clock" class="w-3.5 h-3.5 opacity-60"></i> {{ \Carbon\Carbon::parse($event->event_start)->format('H:i') }} – {{ \Carbon\Carbon::parse($event->event_end)->format('H:i') }} WIB
                    </div>
                </div>
            </div>
            
            <div class="bg-surface-container rounded-xl p-3 flex items-center gap-2">
                <i data-lucide="map-pin" class="text-on-surface-variant w-4 h-4 flex-shrink-0"></i>
                <div class="font-body text-xs text-on-surface-variant truncate">{{ $event->venue }}</div>
            </div>

            <div class="flex items-center justify-between gap-3 pt-2 border-t border-outline-variant/10">
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
                    <a href="{{ route('admin.events.show', $event->id) }}" class="w-9 h-9 rounded-lg bg-surface-container border border-outline-variant/30 flex items-center justify-center text-on-surface-variant hover:bg-primary hover:text-white transition-all"><i data-lucide="eye" class="w-4 h-4"></i></a>
                    <a href="{{ route('admin.events.plotting', $event->id) }}" class="w-9 h-9 rounded-lg bg-surface-container border border-outline-variant/30 flex items-center justify-center text-on-surface-variant hover:bg-secondary hover:text-white transition-all"><i data-lucide="users" class="w-4 h-4"></i></a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="py-14 flex flex-col items-center justify-center bg-surface-container-lowest border border-dashed border-outline-variant/30 rounded-2xl text-center">
        <i data-lucide="calendar-x" class="w-12 h-12 text-outline mb-3 opacity-30"></i>
        <p class="font-headline text-base text-on-surface font-bold">Belum Ada Event</p>
    </div>
    @endforelse
</div>

@if($events instanceof \Illuminate\Pagination\LengthAwarePaginator && $events->hasPages())
    <div class="mt-6 mb-4">
        {{ $events->links() }}
    </div>
@endif

@endsection
