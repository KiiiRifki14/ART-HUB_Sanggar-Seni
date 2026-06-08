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

<div x-data="{ showModal: {{ session('conflict_warning') ? 'true' : 'false' }}, selectedEventId: '{{ old('event_id') }}' }">

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
        <button @click="showModal = true"
                class="bg-gradient-to-br from-primary-container to-primary text-white px-5 py-2.5 rounded-xl font-label text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-md flex items-center gap-2">
            <i class="bi bi-plus-circle-fill"></i> Tambah Latihan
        </button>
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
                        <a href="{{ route('admin.events.show', $r->event_id) }}"
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

    {{-- ══ MODAL: FORM TAMBAH JADWAL LATIHAN ══ --}}
    <div x-show="showModal"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;"
         x-cloak>

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"
             @click="showModal = false"
             x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>

        {{-- Modal Card --}}
        <div class="relative bg-surface-container-lowest border border-outline-variant/40 rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl z-10 transform transition-all"
             x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95">

            {{-- Accent bar --}}
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-secondary"></div>

            {{-- Modal Header --}}
            <div class="px-6 py-4 border-b border-outline-variant/20 flex justify-between items-center mt-1 bg-surface-container-low/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center shadow-inner">
                        <i class="bi bi-calendar-plus-fill text-primary"></i>
                    </div>
                    <div>
                        <h3 class="font-headline font-bold text-primary text-sm">Plotting Sesi Latihan</h3>
                        <p class="font-label text-[0.6rem] uppercase tracking-widest text-outline">Jadwal sesi latihan baru</p>
                    </div>
                </div>
                <button @click="showModal = false"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-outline hover:text-primary hover:bg-surface-container transition-colors">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>

            {{-- Form --}}
            <form :action="'/admin/events/' + selectedEventId + '/rehearsals'" method="POST" class="p-6 m-0">
                @csrf

                <div class="flex flex-col gap-4">
                    {{-- Pilih Event --}}
                    <div>
                        <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Pilih Event / Acara Klien <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none">
                                <i class="bi bi-collection-play-fill text-primary/70"></i>
                            </span>
                            <select x-model="selectedEventId" name="event_id" required
                                    class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-9 py-2.5 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all appearance-none">
                                <option value="">— Pilih Event Aktif —</option>
                                @foreach($events as $ev)
                                    <option value="{{ $ev->id }}">
                                        [{{ $ev->event_code }}] {{ $ev->booking->client_name ?? ($ev->booking->client->name ?? 'Klien') }} — {{ $ev->booking->event_type ?? 'Pementasan' }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-xs">
                                <i class="bi bi-chevron-down"></i>
                            </span>
                        </div>
                    </div>

                    {{-- Tipe + Tanggal sejajar --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Tipe Latihan <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none">
                                    <i class="bi bi-filter-square-fill text-secondary"></i>
                                </span>
                                <select name="type" required class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-9 py-2.5 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all appearance-none">
                                    <option value="gabungan" {{ old('type') == 'gabungan' ? 'selected' : '' }}>Gabungan</option>
                                    <option value="tari" {{ old('type') == 'tari' ? 'selected' : '' }}>Khusus Tari</option>
                                    <option value="musik" {{ old('type') == 'musik' ? 'selected' : '' }}>Khusus Musik</option>
                                </select>
                                <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-xs">
                                    <i class="bi bi-chevron-down"></i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Tanggal <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none">
                                    <i class="bi bi-calendar-date"></i>
                                </span>
                                <input type="date" name="rehearsal_date" value="{{ old('rehearsal_date') }}" required
                                       class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-4 py-2.5 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- Jam Mulai + Selesai sejajar --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Jam Mulai <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none">
                                    <i class="bi bi-clock"></i>
                                </span>
                                <input type="time" name="start_time" value="{{ old('start_time') }}" required
                                       class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-4 py-2.5 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Jam Selesai <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none">
                                    <i class="bi bi-clock-history"></i>
                                </span>
                                <input type="time" name="end_time" value="{{ old('end_time') }}" required
                                       class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-4 py-2.5 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- Lokasi --}}
                    <div>
                        <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Lokasi <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="bi bi-geo-alt-fill absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none"></i>
                            <input type="text" name="location" value="{{ old('location', 'Sanggar Cahaya Gumilang') }}" required
                                   placeholder="Contoh: Pendopo Utama Sanggar"
                                   class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-4 py-2.5 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Catatan (Opsional)</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3 text-outline text-sm pointer-events-none">
                                <i class="bi bi-chat-left-text"></i>
                            </span>
                            <textarea name="notes" rows="2" placeholder="Contoh: Bawa properti selendang masing-masing..."
                                      class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-4 py-2.5 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all resize-none">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    {{-- Force Save (jika ada conflict warning) --}}
                    @if (session('conflict_warning'))
                    <div class="p-3 bg-yellow-50 border border-yellow-300 rounded-xl flex items-start gap-2.5">
                        <input type="checkbox" name="force_save" value="1" id="force_save" class="mt-0.5 rounded text-primary focus:ring-primary">
                        <label for="force_save" class="text-xs text-yellow-900 font-medium select-none leading-relaxed">
                            Saya sadar ada personel yang bentrok — Paksa Simpan jadwal ini.
                        </label>
                    </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="pt-5 mt-5 flex justify-end gap-3 border-t border-outline-variant/20">
                    <button type="button" @click="showModal = false"
                            class="h-9 px-4 rounded-xl border border-outline-variant text-xs font-bold uppercase tracking-wider text-on-surface-variant hover:bg-surface-container transition-colors">
                        Batal
                    </button>
                    <button type="submit" :disabled="!selectedEventId"
                            class="h-9 px-5 rounded-xl bg-gradient-to-r from-primary-container to-primary text-white text-xs font-bold uppercase tracking-wider hover:opacity-90 transition-all disabled:opacity-40 disabled:cursor-not-allowed shadow-md flex items-center gap-2">
                        <i class="bi bi-calendar-check-fill"></i> Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection