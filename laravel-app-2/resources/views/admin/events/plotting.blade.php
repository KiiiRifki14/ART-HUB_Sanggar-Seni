@extends('layouts.admin')

@section('title', 'Smart Plotting — ' . ($event->event_code ?? 'Event') . ' | ART-HUB')
@section('page_title', 'Smart Plotting: ' . ($event->event_code ?? 'Event'))
@section('page_subtitle', 'Assign formasi personel dengan deteksi konflik otomatis dari SQL Stored Procedure.')

@section('content')

{{-- BACK NAV --}}
<div class="flex items-center gap-2 mb-6 font-label text-xs uppercase tracking-widest font-bold">
    <a href="{{ route('admin.events.show', $event->id) }}" class="text-on-surface-variant hover:text-secondary transition-colors flex items-center gap-1.5">
        <i class="bi bi-arrow-left"></i> Kembali ke Event
    </a>
    <span class="text-outline-variant">/</span>
    <span class="text-outline">Plotting Personel</span>
</div>

{{-- ── STATUS BAR: INFO EVENT + RESULT SP ── --}}
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] p-6 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
        <div>
            <h5 class="font-headline font-bold text-lg text-primary flex items-center gap-2 mb-2">
                <i class="bi bi-people-fill text-secondary"></i> Formasi {{ $event->personnel_count ?? 12 }} Personel
            </h5>
            <div class="font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold flex items-center gap-2 flex-wrap">
                <span><i class="bi bi-calendar-event"></i> {{ $event->event_date->format('l, d M Y') }}</span>
                <span class="text-outline-variant">•</span>
                <span><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($event->event_start)->format('H:i') }} – {{ \Carbon\Carbon::parse($event->event_end)->format('H:i') }} WIB</span>
                <span class="text-outline-variant">•</span>
                <span><i class="bi bi-geo-alt"></i> {{ $event->venue }}</span>
            </div>
        </div>

        {{-- Hasil Stored Procedure (jika tersedia) --}}
        <div>
            @if(isset($spData) && $spData)
                @if($spData->collision_count > 0)
                <div class="flex items-center gap-4 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-700">
                    <i class="bi bi-exclamation-octagon-fill text-4xl"></i>
                    <div>
                        <h6 class="font-headline font-bold text-base mb-1">{{ $spData->collision_count }} Personel Konflik Jadwal</h6>
                        <p class="font-body text-[0.7rem] opacity-90 leading-tight">Ada yang sedang di pekerjaan utama/latihan di waktu bersamaan.</p>
                    </div>
                </div>
                @else
                <div class="flex items-center gap-4 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-700">
                    <i class="bi bi-check-circle-fill text-4xl"></i>
                    <div>
                        <h6 class="font-headline font-bold text-base mb-1">Semua Personel Tersedia!</h6>
                        <p class="font-body text-[0.7rem] opacity-90 leading-tight">Tidak ada konflik jadwal ditemukan. Silakan assign formasi.</p>
                    </div>
                </div>
                @endif
            @else
            <div class="flex items-center gap-4 p-4 rounded-xl bg-primary/5 border border-primary/20 text-primary">
                <i class="bi bi-database-fill-check text-4xl text-secondary"></i>
                <div>
                    <h6 class="font-headline font-bold text-base text-secondary mb-1">Deteksi Konflik SQL Siap</h6>
                    <p class="font-body text-[0.7rem] opacity-80 leading-tight">Klik "Validasi & Kunci Plotting" untuk menjalankan Stored Procedure check_personnel_availability.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ── MAIN GRID: FORM PLOTTING + PREVIEW KALKULASI ── --}}
<form action="{{ route('admin.events.plotting.store', $event->id) }}" method="POST" id="plotting-form">
    @csrf

    <div class="flex flex-col xl:flex-row gap-6 items-start">

        {{-- ── PANEL KIRI: TABEL ASSIGNMENT PERSONEL ── --}}
        <div class="flex-grow w-full">
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
                <div class="px-6 py-5 border-b border-outline-variant/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface-container-low/30">
                    <h3 class="font-headline text-lg font-bold text-primary flex items-center gap-2">
                        <i class="bi bi-person-lines-fill text-secondary"></i> Pilih & Assign Formasi Personel
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full" id="plotting-table">
                        <thead class="bg-surface-container-low">
                            <tr>
                                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center w-12">#</th>
                                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Personel</th>
                                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Spesialisasi</th>
                                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Role di Event</th>
                                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Fee</th>
                                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/20">
                            @foreach($personnel as $idx => $p)
                            @php
                                $pivotData = $event->personnel->firstWhere('id', $p->id)?->pivot;
                                $alreadyPlotted = !is_null($pivotData);
                                $collidingIds = [];
                                if (isset($spData) && $spData && !empty($spData->collision_details)) {
                                    preg_match_all('/ID:(\d+)/', $spData->collision_details, $m);
                                    $collidingIds = $m[1] ?? [];
                                }
                                $isColliding = in_array((string)$p->id, $collidingIds);
                                $rowClass = $isColliding ? 'bg-red-500/5 hover:bg-red-500/10' : 'hover:bg-surface-container-low/50';
                            @endphp
                            <tr class="transition-colors {{ $rowClass }}">
                                <td class="px-6 py-4 text-center">
                                    <input class="w-4 h-4 rounded border-outline-variant/50 text-secondary focus:ring-secondary cursor-pointer" type="checkbox" name="personnel[{{ $idx }}][selected]"
                                           value="1" id="chk-{{ $p->id }}"
                                           {{ $alreadyPlotted ? 'checked' : '' }}
                                           {{ $isColliding ? 'disabled' : '' }}
                                           onchange="updatePreview()">
                                    <input type="hidden" name="personnel[{{ $idx }}][id]" value="{{ $p->id }}">
                                    <input type="hidden" name="personnel[{{ $idx }}][fee_reference_id]" value="{{ $fees->first()?->id ?? 1 }}">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-surface-container-highest border border-outline-variant/30 text-on-surface-variant font-headline font-bold text-xs flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($p->user->name ?? 'P', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-body font-bold text-sm text-on-surface">{{ $p->user->name ?? 'Personel' }}</div>
                                            @if($p->day_job_name)
                                            <div class="font-body text-[0.65rem] text-orange-500 font-semibold"><i class="bi bi-briefcase-fill me-0.5"></i>{{ $p->day_job_name }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-2 py-0.5 rounded border border-outline-variant/50 bg-surface-container-highest text-on-surface-variant font-label text-[0.6rem] font-bold uppercase tracking-wider">
                                        {{ $p->specialty }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <select class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-1.5 font-body text-xs text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all {{ $isColliding ? 'opacity-50 cursor-not-allowed' : '' }}" name="personnel[{{ $idx }}][role_in_event]" {{ $isColliding ? 'disabled' : '' }}>
                                        <option value="penari_utama" {{ str_contains($p->specialty ?? '', 'Tari') ? 'selected' : '' }}>Penari Utama</option>
                                        <option value="penari_latar">Penari Latar</option>
                                        <option value="pemusik" {{ str_contains($p->specialty ?? '', 'Musik') ? 'selected' : '' }}>Pemusik</option>
                                        <option value="cadangan">Cadangan</option>
                                        <option value="MC">MC / Pembawa Acara</option>
                                    </select>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-headline font-bold text-sm text-secondary">Rp {{ number_format($fees->first()?->base_fee ?? 500000, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($isColliding)
                                        <span class="inline-block px-2 py-0.5 rounded bg-red-500/10 border border-red-500/20 text-red-600 font-label text-[0.6rem] font-bold uppercase tracking-wider shadow-sm">KONFLIK</span>
                                    @elseif($alreadyPlotted)
                                        <span class="inline-block px-2 py-0.5 rounded bg-secondary/10 border border-secondary/20 text-secondary-container font-label text-[0.6rem] font-bold uppercase tracking-wider shadow-sm">ASSIGNED</span>
                                    @else
                                        <span class="inline-block px-2 py-0.5 rounded bg-green-500/10 border border-green-500/20 text-green-600 font-label text-[0.6rem] font-bold uppercase tracking-wider shadow-sm">AVAILABLE</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── PANEL KANAN: PREVIEW HONOR + SUBMIT ── --}}
        <div class="w-full xl:w-80 flex-shrink-0 space-y-6 sticky top-24">
            
            {{-- Summary Card --}}
            <div class="bg-gradient-to-br from-primary-container to-primary rounded-xl overflow-hidden shadow-[0_8px_24px_rgba(54,31,26,0.06)] border border-primary/20">
                <div class="px-5 py-4 font-label text-xs uppercase tracking-widest font-bold flex items-center gap-2 text-white border-b border-white/10">
                    <i class="bi bi-calculator text-secondary"></i> Estimasi Anggaran Honor
                </div>
                
                <div class="p-5">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-label text-[0.65rem] uppercase tracking-widest text-white/70 font-bold">Personel Dipilih</span>
                        <span class="font-headline font-bold text-2xl text-white" id="preview-count">{{ $event->personnel->count() }}</span>
                    </div>
                    
                    <hr class="border-white/10 border-dashed my-4">
                    
                    <div class="mb-4 text-center">
                        <span class="font-label text-[0.65rem] uppercase tracking-widest text-white/70 font-bold block mb-1">Estimasi Total</span>
                        <span class="font-headline font-bold text-3xl text-secondary block" id="preview-total">
                            Rp {{ number_format($event->estimated_total_honor > 0 ? $event->estimated_total_honor : ($fees->first()?->base_fee ?? 500000) * $event->personnel->count(), 0, ',', '.') }}
                        </span>
                    </div>

                    @if($event->financialRecord)
                    <div class="bg-black/20 rounded-lg p-4 font-body text-xs border border-white/5 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-white/60 font-medium">Budget Operasional</span>
                            <span class="text-white font-bold">Rp {{ number_format($event->financialRecord->operational_budget, 0, ',', '.') }}</span>
                        </div>
                        @php $sisa = $event->financialRecord->operational_budget - $event->estimated_total_honor; @endphp
                        <div class="flex justify-between">
                            <span class="text-white/60 font-medium">Sisa Budget Ops</span>
                            <span class="font-bold {{ $sisa >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                {{ $sisa >= 0 ? '+' : '-' }}Rp {{ number_format(abs($sisa), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Info SQL Function --}}
            <div class="bg-surface-container-lowest border-l-4 border-l-secondary border-y border-r border-outline-variant/30 rounded-r-xl p-4 shadow-sm">
                <div class="font-label text-xs uppercase tracking-widest text-primary font-bold mb-2 flex items-center gap-1.5"><i class="bi bi-database-fill text-secondary"></i> Mekanisme SQL</div>
                <div class="font-body text-[0.7rem] text-on-surface-variant leading-relaxed">
                    Saat "Kunci Plotting" diklik, sistem memanggil:<br>
                    <code class="text-blue-600 bg-blue-500/10 border border-blue-500/20 px-1.5 py-0.5 rounded my-1 inline-block text-[0.65rem]">CALL sp_check_personnel_availability()</code><br>
                    Jika lolos, estimasi honor otomatis dihitung SQL.
                </div>
            </div>

            {{-- Tombol Submit --}}
            <button type="submit" class="w-full flex justify-center items-center gap-2 bg-secondary text-primary px-4 py-3.5 rounded-xl font-label text-[0.7rem] font-bold uppercase tracking-widest hover:bg-secondary-container transition-all shadow-md">
                <i class="bi bi-lock-fill"></i> Validasi SQL & Kunci Plotting
            </button>
        </div>

    </div>
</form>

@endsection

@push('scripts')
<script>
    const baseFee = Number("{{ $fees->first()?->base_fee ?? 500000 }}");
    function updatePreview() {
        const checked = document.querySelectorAll('#plotting-table input[type="checkbox"]:checked:not(:disabled)');
        const count = checked.length;
        const total = count * baseFee;
        document.getElementById('preview-count').textContent = count;
        document.getElementById('preview-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>
@endpush
