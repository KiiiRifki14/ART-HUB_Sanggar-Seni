@extends('layouts.admin')

@section('title', 'Jadwal Latihan – ART-HUB')
@section('page_title', 'Jadwal Latihan')
@section('page_subtitle', 'Kalender & plotting sesi latihan sanggar.')

@section('content')

@php
    $total    = $rehearsals->count();
    $upcoming = $rehearsals->where('rehearsal_date', '>=', now()->toDateString())->count();
    $past     = $total - $upcoming;
@endphp

{{-- STAT CARDS --}}
<div class="grid grid-cols-3 gap-2 sm:gap-4 mb-6 md:mb-8">
    <div class="bg-surface-container-lowest rounded-xl p-3 sm:p-5 border border-primary/20 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-music-note-list text-lg sm:text-2xl text-primary mb-1 sm:mb-2 block"></i>
        <div class="font-headline text-sm sm:text-3xl font-bold text-primary mb-0.5 sm:mb-1">{{ $upcoming }} <span class="text-[0.6rem] sm:text-lg font-normal">Sesi</span></div>
        <div class="font-label text-[0.5rem] sm:text-[0.65rem] uppercase tracking-widest text-outline font-bold truncate">Mendatang</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-3 sm:p-5 border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-clock-history text-lg sm:text-2xl text-secondary mb-1 sm:mb-2 block"></i>
        <div class="font-headline text-sm sm:text-3xl font-bold text-secondary mb-0.5 sm:mb-1">{{ $past }} <span class="text-[0.6rem] sm:text-lg font-normal">Sesi</span></div>
        <div class="font-label text-[0.5rem] sm:text-[0.65rem] uppercase tracking-widest text-outline font-bold truncate">Berlalu</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-3 sm:p-5 border border-green-500/20 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-check-circle-fill text-lg sm:text-2xl text-green-500 mb-1 sm:mb-2 block"></i>
        <div class="font-headline text-sm sm:text-3xl font-bold text-green-600 mb-0.5 sm:mb-1">{{ $total }}</div>
        <div class="font-label text-[0.5rem] sm:text-[0.65rem] uppercase tracking-widest text-outline font-bold truncate">Total</div>
    </div>
</div>

{{-- LIST LATIHAN --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="font-headline text-xl text-primary font-semibold flex items-center gap-2">
        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center"><i class="bi bi-calendar3 text-primary"></i></div>
        Daftar Jadwal Latihan
    </h2>
</div>

<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
    <table class="w-full hidden md:table">
        <thead class="bg-surface-container-low">
            <tr>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Untuk Event / Klien</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Tipe Latihan</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Tanggal</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Waktu (WIB)</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Lokasi</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Catatan</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/20">
            @forelse($rehearsals as $r)
            @php
                $isPast = \Carbon\Carbon::parse($r->rehearsal_date)->isPast() && \Carbon\Carbon::parse($r->rehearsal_date)->toDateString() !== now()->toDateString();
            @endphp
            <tr class="transition-colors hover:bg-surface-container-low/50 {{ $isPast ? 'opacity-50' : '' }}">
                <td class="px-6 py-4">
                    <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider mb-1">
                        {{ $r->event->event_code ?? '-' }}
                    </span>
                    <div class="font-body font-semibold text-on-surface text-sm">{{ $r->event->booking->client_name ?? ($r->event->booking->client->name ?? '-') }}</div>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-block px-2.5 py-1 rounded bg-surface-container text-on-surface border border-outline-variant/50 font-label text-[0.65rem] font-bold uppercase tracking-wider">
                        {{ $r->type }}
                    </span>
                </td>
                <td class="px-6 py-4 font-body text-sm font-semibold text-on-surface">
                    {{ \Carbon\Carbon::parse($r->rehearsal_date)->format('d M Y') }}
                </td>
                <td class="px-6 py-4 font-body text-sm text-on-surface-variant">
                    <i class="bi bi-clock me-1 text-secondary"></i>
                    {{ \Carbon\Carbon::parse($r->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($r->end_time)->format('H:i') }}
                </td>
                <td class="px-6 py-4 font-body text-sm text-on-surface-variant">
                    {{ $r->location }}
                </td>
                <td class="px-6 py-4 max-w-[15rem]">
                    <div class="font-body text-xs text-on-surface-variant truncate" title="{{ $r->notes }}">
                        {{ $r->notes ?? '-' }}
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    <a href="{{ route('admin.events.show', $r->event_id) }}" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg border border-outline-variant/50 font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant hover:border-primary hover:text-primary hover:bg-surface-container transition-colors" title="Lihat Event Terkait">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-16 text-center">
                    <i class="bi bi-calendar-x text-4xl text-outline mb-4 block"></i>
                    <h3 class="font-headline text-lg text-on-surface font-semibold mb-1">Belum ada jadwal latihan</h3>
                    <p class="font-label text-xs uppercase tracking-widest text-outline">
                        Gunakan tombol Plotting di halaman Event untuk menjadwalkan latihan.
                    </p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Mobile Cards View --}}
    <div class="block md:hidden divide-y divide-outline-variant/20">
        @forelse($rehearsals as $r)
        @php
            $isPast = \Carbon\Carbon::parse($r->rehearsal_date)->isPast() && \Carbon\Carbon::parse($r->rehearsal_date)->toDateString() !== now()->toDateString();
            $cDate = \Carbon\Carbon::parse($r->rehearsal_date);
        @endphp
        <div class="p-4 flex gap-4 {{ $isPast ? 'opacity-60' : '' }} hover:bg-surface-container-low/50 transition-colors">
            <!-- Left Column: Mini Calendar Card -->
            <div class="flex-shrink-0 flex flex-col items-center justify-center w-14 h-16 rounded-xl bg-primary/5 border border-primary/10 overflow-hidden shadow-sm">
                <div class="w-full bg-primary text-white text-[0.55rem] font-label font-bold uppercase tracking-widest text-center py-1">
                    {{ $cDate->translatedFormat('M') }}
                </div>
                <div class="flex-grow flex items-center justify-center font-headline text-base font-bold text-primary">
                    {{ $cDate->format('d') }}
                </div>
            </div>

            <!-- Right Column: Rehearsal details -->
            <div class="flex-grow min-w-0 space-y-2">
                <div class="flex justify-between items-start gap-2">
                    <div>
                        <span class="inline-block px-2.5 py-0.5 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider mb-1">
                            {{ $r->event->event_code ?? '-' }}
                        </span>
                        <div class="font-body font-bold text-sm text-on-surface truncate">
                            {{ $r->event->booking->client_name ?? ($r->event->booking->client->name ?? '-') }}
                        </div>
                    </div>
                    <span class="inline-block px-2 py-0.5 rounded bg-surface-container text-on-surface border border-outline-variant/50 font-label text-[0.55rem] font-bold uppercase tracking-wider flex-shrink-0">
                        {{ $r->type }}
                    </span>
                </div>

                <div class="text-xs text-on-surface-variant font-body space-y-1">
                    <div class="flex items-center gap-1.5">
                        <i class="bi bi-clock text-secondary text-[0.8rem] flex-shrink-0"></i>
                        <span>{{ \Carbon\Carbon::parse($r->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($r->end_time)->format('H:i') }} WIB</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i class="bi bi-geo-alt text-primary text-[0.8rem] flex-shrink-0"></i>
                        <span class="truncate">{{ $r->location }}</span>
                    </div>
                    @if($r->notes)
                    <div class="flex items-start gap-1.5 text-[0.7rem] bg-surface-container/40 p-2 rounded border border-outline-variant/10 italic">
                        <span class="text-outline">Note:</span>
                        <span class="text-on-surface-variant">{{ $r->notes }}</span>
                    </div>
                    @endif
                </div>

                <div class="pt-1 flex justify-end">
                    <a href="{{ route('admin.events.show', $r->event_id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded border border-outline-variant/50 font-label text-[0.6rem] font-bold uppercase tracking-widest text-on-surface-variant hover:border-primary hover:text-primary hover:bg-surface-container transition-colors shadow-sm" title="Lihat Event Terkait">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-outline">
            <i class="bi bi-calendar-x text-3xl mb-2 block"></i>
            <p class="font-body text-xs font-semibold text-on-surface mb-1">Belum ada jadwal latihan</p>
            <p class="font-label text-[0.6rem] uppercase tracking-widest text-outline">Gunakan tombol Plotting di halaman Event untuk menjadwalkan latihan.</p>
        </div>
        @endforelse
    </div>
</div>

@endsection


