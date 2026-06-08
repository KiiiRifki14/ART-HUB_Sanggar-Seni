@extends('layouts.admin')

@section('title', 'Tambah Jadwal Latihan – ART-HUB')
@section('page_title', 'Tambah Jadwal Latihan')
@section('page_subtitle', 'Buat sesi latihan baru untuk event aktif.')

@section('content')

<div x-data="{
    selectedEventId: '{{ old('event_id') }}',
    selectedEventLabel: '',
    forceMode: {{ session('conflict_warning') ? 'true' : 'false' }},
    init() {
        const sel = document.getElementById('event_select');
        if (sel && sel.value) {
            this.selectedEventLabel = sel.options[sel.selectedIndex]?.text ?? '';
        }
    }
}">

{{-- ── BACK BREADCRUMB ── --}}
<div class="flex items-center gap-2 mb-6 text-sm text-on-surface-variant">
    <a href="{{ route('admin.rehearsals.index') }}"
       class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-outline hover:text-primary transition-colors">
        <i class="bi bi-arrow-left-circle-fill text-base"></i> Kembali ke Daftar
    </a>
    <i class="bi bi-chevron-right text-[0.6rem] text-outline/50"></i>
    <span class="text-xs font-bold uppercase tracking-widest text-primary">Plotting Baru</span>
</div>

{{-- ── CONFLICT WARNING BANNER ── --}}
@if (session('conflict_warning'))
<div class="mb-6 p-4 rounded-2xl border border-red-400/60 bg-red-50 flex items-start gap-3 shadow-sm animate-[fadeUp_0.3s_ease]">
    <div class="w-9 h-9 rounded-xl bg-red-500/10 flex items-center justify-center flex-shrink-0">
        <i class="bi bi-exclamation-triangle-fill text-red-600"></i>
    </div>
    <div class="flex-1">
        <p class="font-semibold text-sm text-red-900">⚠️ Peringatan Bentrok Jadwal Personel!</p>
        <p class="text-xs mt-1 text-red-700 leading-relaxed">{{ session('conflict_warning') }}</p>
    </div>
</div>
@endif

{{-- ── VALIDATION ERRORS ── --}}
@if ($errors->any())
<div class="mb-6 p-4 rounded-2xl border border-red-400/60 bg-red-50 shadow-sm">
    <p class="font-bold text-sm text-red-800 mb-2 flex items-center gap-2"><i class="bi bi-x-circle-fill"></i> Ada kesalahan input:</p>
    <ul class="list-disc list-inside space-y-0.5">
        @foreach($errors->all() as $err)
        <li class="text-xs text-red-700">{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- ── FORM CARD ── --}}
<div class="max-w-2xl mx-auto">
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden">

        {{-- Accent top bar --}}
        <div class="h-1 w-full bg-gradient-to-r from-primary via-secondary to-primary-container"></div>

        {{-- Header --}}
        <div class="px-7 py-5 border-b border-outline-variant/20 bg-surface-container-low/50 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center shadow-inner">
                <i class="bi bi-calendar-plus-fill text-xl text-primary"></i>
            </div>
            <div>
                <h2 class="font-headline font-bold text-primary text-base">Plotting Sesi Latihan</h2>
                <p class="font-label text-[0.65rem] uppercase tracking-widest text-outline mt-0.5">Isi detail jadwal latihan baru di bawah ini</p>
            </div>
        </div>

        {{-- FORM --}}
        <form :action="'/admin/events/' + selectedEventId + '/rehearsals'" method="POST" class="p-7 space-y-6">
            @csrf

            {{-- ─ SECTION: Event ─ --}}
            <div>
                <h3 class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-3 flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full bg-primary/10 flex items-center justify-center text-primary text-[0.55rem]">1</span>
                    Pilih Event Target
                </h3>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-outline pointer-events-none">
                        <i class="bi bi-collection-play-fill text-primary/70"></i>
                    </span>
                    <select id="event_select"
                            x-model="selectedEventId"
                            @change="selectedEventLabel = $event.target.options[$event.target.selectedIndex].text"
                            name="event_id" required
                            class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-9 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all appearance-none">
                        <option value="">— Pilih Event Aktif —</option>
                        @foreach($events as $ev)
                            <option value="{{ $ev->id }}" {{ old('event_id') == $ev->id ? 'selected' : '' }}>
                                [{{ $ev->event_code }}] {{ $ev->booking->client_name ?? ($ev->booking->client->name ?? 'Klien') }} — {{ $ev->booking->event_type ?? 'Pementasan' }}
                            </option>
                        @endforeach
                    </select>
                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-xs">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </div>

                {{-- Preview pill --}}
                <div x-show="selectedEventId"
                     x-cloak
                     class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-primary/5 border border-primary/20">
                    <i class="bi bi-check-circle-fill text-primary text-xs"></i>
                    <span class="font-label text-xs text-primary font-semibold" x-text="selectedEventLabel"></span>
                </div>
            </div>

            {{-- ─ SECTION: Tipe + Tanggal ─ --}}
            <div>
                <h3 class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-3 flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full bg-secondary/10 flex items-center justify-center text-secondary text-[0.55rem]">2</span>
                    Detail Sesi
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Tipe --}}
                    <div>
                        <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Tipe Latihan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none">
                                <i class="bi bi-filter-square-fill text-secondary"></i>
                            </span>
                            <select name="type" required
                                    class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-9 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all appearance-none">
                                <option value="gabungan" {{ old('type','gabungan') == 'gabungan' ? 'selected' : '' }}>🎭 Gabungan (Tari + Musik)</option>
                                <option value="tari"     {{ old('type') == 'tari'     ? 'selected' : '' }}>💃 Khusus Tari</option>
                                <option value="musik"    {{ old('type') == 'musik'    ? 'selected' : '' }}>🎵 Khusus Musik</option>
                            </select>
                            <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-xs">
                                <i class="bi bi-chevron-down"></i>
                            </span>
                        </div>
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Tanggal Latihan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none">
                                <i class="bi bi-calendar-date text-primary"></i>
                            </span>
                            <input type="date" name="rehearsal_date"
                                   value="{{ old('rehearsal_date') }}" required
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-4 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─ SECTION: Jam ─ --}}
            <div>
                <h3 class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-3 flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-600 text-[0.55rem]">3</span>
                    Waktu Sesi
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    {{-- Jam Mulai --}}
                    <div>
                        <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Jam Mulai <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none">
                                <i class="bi bi-clock-fill text-blue-500"></i>
                            </span>
                            <input type="time" name="start_time"
                                   value="{{ old('start_time', '08:00') }}" required
                                   class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-4 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        </div>
                    </div>

                    {{-- Jam Selesai --}}
                    <div>
                        <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Jam Selesai <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none">
                                <i class="bi bi-clock-history text-outline"></i>
                            </span>
                            <input type="time" name="end_time"
                                   value="{{ old('end_time', '12:00') }}" required
                                   class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-4 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─ SECTION: Lokasi + Catatan ─ --}}
            <div>
                <h3 class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-3 flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full bg-green-500/10 flex items-center justify-center text-green-600 text-[0.55rem]">4</span>
                    Lokasi & Catatan
                </h3>
                <div class="space-y-4">
                    {{-- Lokasi --}}
                    <div>
                        <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Lokasi <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="bi bi-geo-alt-fill absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none text-green-600"></i>
                            <input type="text" name="location"
                                   value="{{ old('location', 'Sanggar Cahaya Gumilang') }}" required
                                   placeholder="Contoh: Pendopo Utama Sanggar"
                                   class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-4 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label class="block font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5 ml-1">Catatan <span class="text-on-surface-variant/50 font-normal normal-case tracking-normal">Opsional</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3.5 text-outline text-sm pointer-events-none">
                                <i class="bi bi-chat-left-text"></i>
                            </span>
                            <textarea name="notes" rows="3"
                                      placeholder="Contoh: Bawa properti selendang masing-masing, hadir 15 menit lebih awal..."
                                      class="w-full rounded-xl border border-outline-variant/50 bg-surface-container-low text-sm pl-10 pr-4 py-2.5 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-none">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─ FORCE SAVE (hanya muncul jika ada conflict warning) ─ --}}
            @if (session('conflict_warning'))
            <div class="p-4 bg-yellow-50 border border-yellow-400/60 rounded-xl flex items-start gap-3">
                <input type="checkbox" name="force_save" value="1" id="force_save"
                       class="mt-0.5 rounded text-primary focus:ring-primary w-4 h-4">
                <label for="force_save" class="text-xs text-yellow-900 font-medium select-none leading-relaxed cursor-pointer">
                    <strong>Saya sadar ada personel yang jadwalnya bentrok</strong> — Tetap simpan jadwal latihan ini secara paksa.
                </label>
            </div>
            @endif

            {{-- ─ ACTION BUTTONS ─ --}}
            <div class="pt-4 mt-2 flex flex-col sm:flex-row justify-end gap-3 border-t border-outline-variant/20">
                <a href="{{ route('admin.rehearsals.index') }}"
                   class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-xl border border-outline-variant text-sm font-bold uppercase tracking-wider text-on-surface-variant hover:bg-surface-container hover:border-primary/30 transition-all">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
                <button type="submit"
                        :disabled="!selectedEventId"
                        class="inline-flex items-center justify-center gap-2 h-11 px-8 rounded-xl bg-gradient-to-r from-primary-container to-primary text-white text-sm font-bold uppercase tracking-wider hover:opacity-90 transition-all disabled:opacity-40 disabled:cursor-not-allowed shadow-md">
                    <i class="bi bi-calendar-check-fill"></i> Simpan Jadwal Latihan
                </button>
            </div>
        </form>
    </div>

    {{-- ─ INFO CARD ─ --}}
    <div class="mt-4 p-4 rounded-xl bg-blue-50 border border-blue-200/60 flex items-start gap-3">
        <i class="bi bi-info-circle-fill text-blue-500 mt-0.5"></i>
        <div class="text-xs text-blue-800 leading-relaxed">
            <strong>Catatan:</strong> Sistem akan otomatis mendeteksi bentrok jadwal personel via stored procedure. Jika ada bentrok, Anda akan diperingatkan dan bisa memilih untuk memaksakan jadwal.
        </div>
    </div>
</div>

</div>
@endsection
