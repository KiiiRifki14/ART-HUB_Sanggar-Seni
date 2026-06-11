@extends('layouts.admin')

@section('title', 'Jadwal Latihan – ART-HUB')
@section('page_title', 'Jadwal Latihan')
@section('page_subtitle', 'Kalender & plotting sesi latihan sanggar.')

@section('content')



<div>

    {{-- ── CONFLICT ALERT ── --}}
    @if (session('conflict_warning'))
    <div class="mb-4 p-4 rounded-xl border border-red-400/60 bg-red-50 flex items-start gap-3 shadow-sm">
        <div class="w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-exclamation-triangle-fill text-red-600 text-sm"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-semibold text-sm text-red-900">Peringatan Bentrok Jadwal Latihan!</p>
            <p class="text-xs mt-0.5 text-red-700 leading-relaxed">{{ session('conflict_warning') }}</p>
        </div>
    </div>
    @endif

    {{-- ── STAT STRIP ── --}}
    <div class="grid grid-cols-3 gap-4 mb-5">
        <div class="bg-surface-container-lowest rounded-2xl p-4 border border-primary/20 flex items-center gap-3.5 shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                <i class="bi bi-music-note-list text-lg text-primary"></i>
            </div>
            <div>
                <div class="font-headline text-2xl font-bold text-primary leading-none">{{ $upcoming }}</div>
                <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mt-1">Mendatang</div>
            </div>
        </div>
        <div class="bg-surface-container-lowest rounded-2xl p-4 border border-outline-variant/30 flex items-center gap-3.5 shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-secondary/10 flex items-center justify-center shrink-0">
                <i class="bi bi-clock-history text-lg text-secondary"></i>
            </div>
            <div>
                <div class="font-headline text-2xl font-bold text-secondary leading-none">{{ $past }}</div>
                <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mt-1">Berlalu</div>
            </div>
        </div>
        <div class="bg-surface-container-lowest rounded-2xl p-4 border border-green-500/20 flex items-center gap-3.5 shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-green-500/10 flex items-center justify-center shrink-0">
                <i class="bi bi-check-circle-fill text-lg text-green-500"></i>
            </div>
            <div>
                <div class="font-headline text-2xl font-bold text-green-600 leading-none">{{ $total }}</div>
                <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mt-1">Total</div>
            </div>
        </div>
    </div>

    {{-- ── HEADER + CTA ── --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="font-headline text-base text-primary font-semibold flex items-center gap-2">
            <i class="bi bi-calendar3 text-secondary"></i>
            Daftar Jadwal Latihan
        </h2>
        <a href="{{ route('admin.rehearsals.create') }}"
           class="bg-gradient-to-br from-primary-container to-primary text-white px-5 py-2.5 rounded-xl font-label text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-md flex items-center gap-2">
            <i class="bi bi-plus-circle-fill"></i> Tambah Latihan
        </a>
    </div>

    {{-- ── TABLE ── --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden overflow-x-auto mb-8">
        <table class="w-full min-w-[760px]">
            <thead class="bg-surface-container-low border-b border-outline-variant/30">
                <tr>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-5 py-3.5 text-left">Event / Klien</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-5 py-3.5 text-left">Tipe</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-5 py-3.5 text-left">Tanggal</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-5 py-3.5 text-left">Waktu</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-5 py-3.5 text-left">Lokasi</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-5 py-3.5 text-left">Catatan</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-5 py-3.5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/15">
                @forelse($rehearsals as $r)
                @php
                    $isPast = \Carbon\Carbon::parse($r->rehearsal_date)->isPast()
                           && \Carbon\Carbon::parse($r->rehearsal_date)->toDateString() !== now()->toDateString();
                    $typeColors = [
                        'musik'    => 'bg-blue-500/10 text-blue-700 border-blue-500/20',
                        'tari'     => 'bg-pink-500/10 text-pink-700 border-pink-500/20',
                        'gabungan' => 'bg-secondary/10 text-secondary border-secondary/20',
                    ];
                    $tc = $typeColors[$r->type] ?? 'bg-surface-container text-outline border-outline-variant/40';
                    $daysLeft = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($r->rehearsal_date)->startOfDay(), false);
                @endphp
                <tr class="transition-colors hover:bg-surface-container-low/40 {{ $isPast ? 'opacity-40' : '' }}">
                    <td class="px-5 py-3.5">
                        <div class="flex flex-col gap-1">
                            <span class="inline-block px-2 py-0.5 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.6rem] font-bold tracking-wider w-fit">
                                {{ $r->event->event_code ?? '-' }}
                            </span>
                            <span class="font-body font-semibold text-on-surface text-sm">{{ $r->event->booking->client_name ?? ($r->event->booking->client->name ?? '-') }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full border font-label text-[0.6rem] font-bold uppercase tracking-wider {{ $tc }}">
                            {{ $r->type }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <span class="font-body text-sm font-semibold text-on-surface">{{ \Carbon\Carbon::parse($r->rehearsal_date)->format('d M Y') }}</span>
                            @if(!$isPast && $daysLeft <= 3 && $daysLeft >= 0)
                            <span class="inline-block px-2 py-0.5 rounded-full bg-red-500/10 text-red-600 font-label text-[0.55rem] font-bold">H-{{ $daysLeft }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="font-body text-sm text-on-surface-variant flex items-center gap-1.5">
                            <i class="bi bi-clock text-secondary text-xs"></i>
                            {{ \Carbon\Carbon::parse($r->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($r->end_time)->format('H:i') }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="font-body text-sm text-on-surface-variant truncate block max-w-[150px]" title="{{ $r->location }}">{{ $r->location }}</span>
                    </td>
                    <td class="px-5 py-3.5 max-w-[160px]">
                        <div class="font-body text-xs text-on-surface-variant truncate" title="{{ $r->notes }}">
                            {{ $r->notes ?? '—' }}
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <a href="{{ route('admin.events.monitoring.show', $r->event_id) }}"
                           class="inline-flex items-center justify-center gap-1.5 h-8 px-3 rounded-lg border border-outline-variant/50 font-label text-[0.65rem] font-bold uppercase text-on-surface-variant hover:border-primary hover:text-primary hover:bg-surface-container transition-colors"
                           title="Lihat Event">
                            <i class="bi bi-eye text-xs"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-surface-container mx-auto flex items-center justify-center mb-4">
                            <i class="bi bi-calendar-x text-3xl text-outline"></i>
                        </div>
                        <h3 class="font-headline text-base text-on-surface font-semibold mb-1">Belum ada jadwal latihan</h3>
                        <p class="font-label text-xs text-outline">Gunakan tombol Tambah Latihan untuk menjadwalkan sesi baru.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination links --}}
    <div class="mt-4 px-2">
        {{ $rehearsals->links() }}
    </div>

</div>

@endsection