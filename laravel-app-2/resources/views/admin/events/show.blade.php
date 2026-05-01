@extends('layouts.admin')

@section('title', 'Detail Event – ART-HUB')
@section('page_title', $event->event_code)
@section('page_subtitle', 'Detail & Monitoring Event Pementasan.')

@section('content')

{{-- BACK NAV --}}
<div class="flex items-center gap-2 mb-6 font-label text-xs uppercase tracking-widest font-bold">
    <a href="{{ route('admin.events.index') }}" class="text-on-surface-variant hover:text-secondary transition-colors flex items-center gap-1.5">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
    <span class="text-outline-variant">/</span>
    <span class="text-outline">Event {{ $event->event_code }}</span>
</div>

{{-- ── ROW ATAS: PANEL INFORMASI ── --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    {{-- Info Event --}}
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
        <div class="px-5 py-4 border-b border-outline-variant/20 flex items-center justify-between bg-primary">
            <h5 class="font-headline font-bold text-base text-white flex items-center gap-2">
                <i class="bi bi-info-circle text-secondary"></i> Info Event
            </h5>
            <span class="inline-block px-2 py-0.5 rounded border border-white/20 bg-white/10 text-white font-label text-[0.6rem] font-bold uppercase tracking-wider">
                {{ str_replace('_', ' ', $event->booking->event_type ?? '-') }}
            </span>
        </div>
        <div class="p-5 space-y-4">
            <div class="flex flex-col border-b border-outline-variant/20 pb-3">
                <span class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Tanggal</span>
                <span class="font-body font-bold text-on-surface text-sm">{{ $event->event_date->format('d M Y') }}</span>
            </div>
            <div class="flex flex-col border-b border-outline-variant/20 pb-3">
                <span class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Waktu</span>
                <span class="font-body font-bold text-on-surface text-sm">
                    {{ \Carbon\Carbon::parse($event->event_start)->format('H:i') }} – {{ \Carbon\Carbon::parse($event->event_end)->format('H:i') }}
                </span>
            </div>
            <div class="flex flex-col border-b border-outline-variant/20 pb-3">
                <span class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Venue</span>
                <span class="font-body font-bold text-on-surface text-sm">{{ $event->venue }}</span>
            </div>
            
            <div class="flex flex-col pt-1">
                <span class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-2">Koordinat GPS (Geofencing)</span>
                @if($event->latitude && $event->longitude)
                    <div class="font-body font-semibold text-blue-600 text-sm bg-blue-500/10 px-3 py-1.5 rounded-lg border border-blue-500/20 inline-flex w-max items-center gap-1.5"><i class="bi bi-geo-alt"></i> {{ $event->latitude }}, {{ $event->longitude }}</div>
                @else
                    <div class="font-body text-xs font-bold text-red-600 bg-red-500/10 px-3 py-1.5 rounded-lg border border-red-500/20 flex items-start gap-1.5 mb-2"><i class="bi bi-exclamation-triangle-fill mt-0.5"></i> Belum Di-set! (Wajib untuk Absensi)</div>
                @endif
                <button type="button" class="mt-3 inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg border border-outline-variant/50 font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant hover:border-primary hover:text-primary hover:bg-surface-container transition-colors" data-bs-toggle="modal" data-bs-target="#modalKoordinat">
                    <i class="bi bi-pencil"></i> Set Koordinat
                </button>
            </div>
        </div>
    </div>

    {{-- Status & Estimasi Honor --}}
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden flex flex-col">
        <div class="px-5 py-4 border-b border-outline-variant/20 flex items-center justify-between bg-surface-container-low">
            <h5 class="font-headline font-bold text-base text-primary flex items-center gap-2">
                <i class="bi bi-activity text-blue-600"></i> Status Monitor
            </h5>
            @php 
                $sc = ['planning'=>'bg-orange-500/10 text-orange-600 border-orange-500/20',
                       'rehearsal'=>'bg-orange-500/10 text-orange-600 border-orange-500/20',
                       'ready'=>'bg-blue-500/10 text-blue-600 border-blue-500/20',
                       'ongoing'=>'bg-blue-500/10 text-blue-600 border-blue-500/20',
                       'completed'=>'bg-green-500/10 text-green-600 border-green-500/20',
                       'cancelled'=>'bg-red-500/10 text-red-600 border-red-500/20']; 
                $statusClass = $sc[$event->status] ?? 'bg-surface-container text-outline border-outline-variant/30';
            @endphp
            <span class="inline-block px-2.5 py-1 rounded border font-label text-[0.65rem] font-bold uppercase tracking-wider {{ $statusClass }}">
                {{ strtoupper($event->status) }}
            </span>
        </div>
        
        <div class="p-5 flex-grow flex flex-col">
            <div class="bg-surface-container-low border border-outline-variant/50 rounded-xl p-4 mb-4 flex-grow flex flex-col justify-center text-center group hover:border-primary transition-colors cursor-default">
                <span class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Personel Diplot</span>
                <div class="font-headline font-bold text-4xl text-primary flex items-baseline justify-center gap-1">
                    {{ $event->personnel->count() }}<span class="text-xl text-outline-variant">/{{ $event->personnel_count }}</span>
                </div>
            </div>

            <div class="bg-surface-container-low border border-outline-variant/50 rounded-xl p-4">
                <span class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1 block">Estimasi Honor Total</span>
                <div class="font-headline font-bold text-xl text-secondary">Rp {{ number_format($event->estimated_total_honor, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Keuangan --}}
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden flex flex-col">
        <div class="px-5 py-4 border-b border-outline-variant/20 flex items-center justify-between bg-surface-container-low">
            <h5 class="font-headline font-bold text-base text-primary flex items-center gap-2">
                <i class="bi bi-wallet2 text-green-600"></i> Keuangan
            </h5>
        </div>
        
        <div class="p-5 flex-grow flex flex-col justify-center">
            @if($event->financialRecord)
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-outline-variant/20">
                        <span class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Total Revenue</span>
                        <span class="font-body font-bold text-sm text-on-surface">Rp {{ number_format($event->financialRecord->total_revenue, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-outline-variant/20">
                        <span class="font-label text-[0.65rem] uppercase tracking-widest text-secondary font-bold">Fixed Profit (Laba)</span>
                        <span class="font-headline font-bold text-base text-secondary">Rp {{ number_format($event->financialRecord->fixed_profit, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Budget Ops Default</span>
                        <span class="font-body font-bold text-sm text-on-surface-variant">Rp {{ number_format($event->financialRecord->operational_budget, 0, ',', '.') }}</span>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-surface-container border border-outline-variant/50 flex items-center justify-center mx-auto mb-3">
                        <i class="bi bi-safe2 text-2xl text-outline/50"></i>
                    </div>
                    <p class="font-body text-xs text-on-surface-variant">Belum ada data keuangan.<br>Selesaikan DP Verification terlebih dahulu.</p>
                </div>
            @endif
        </div>
    </div>

</div>

{{-- ── ROW BAWAH: TABEL PERSONEL ── --}}
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-outline-variant/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface-container-low/30">
        <h3 class="font-headline text-lg font-bold text-primary flex items-center gap-2">
            <i class="bi bi-people-fill text-secondary"></i> Personel & Check-in Monitor
        </h3>
        <a href="{{ route('admin.events.plotting', $event->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary text-white font-label text-[0.65rem] font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-sm">
            <i class="bi bi-diagram-3"></i> Kelola Plotting
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Nama</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Role</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Fee (Honor)</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Check-in</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Status Absen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($event->personnel as $p)
                <tr class="hover:bg-surface-container-low/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-secondary/10 border border-secondary/20 text-secondary font-headline font-bold text-xs flex items-center justify-center flex-shrink-0">
                                {{ strtoupper(substr($p->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-body font-bold text-sm text-on-surface">{{ $p->user->name }}</div>
                                <div class="font-body text-xs text-on-surface-variant">{{ $p->specialty }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-2.5 py-1 rounded bg-surface-container text-on-surface border border-outline-variant/50 font-label text-[0.65rem] font-bold uppercase tracking-wider">
                            {{ str_replace('_', ' ', $p->pivot->role_in_event) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-body text-sm font-semibold text-on-surface">Rp {{ number_format($p->pivot->fee, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($p->pivot->checked_in_at)
                            <span class="inline-flex items-center gap-1 font-body font-bold text-sm text-green-600"><i class="bi bi-clock-history"></i> {{ \Carbon\Carbon::parse($p->pivot->checked_in_at)->format('H:i') }}</span>
                        @else
                            <span class="text-outline-variant">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php $as = $p->pivot->attendance_status; @endphp
                        @if($as === 'on_time') <span class="inline-block px-2.5 py-1 rounded bg-green-500/10 text-green-600 border border-green-500/20 font-label text-[0.65rem] font-bold uppercase tracking-wider">ON TIME</span>
                        @elseif($as === 'late') <span class="inline-block px-2.5 py-1 rounded bg-red-500/10 text-red-600 border border-red-500/20 font-label text-[0.65rem] font-bold uppercase tracking-wider">TELAT {{ $p->pivot->late_minutes }}m</span>
                        @else <span class="inline-block px-2.5 py-1 rounded bg-surface-container text-outline border border-outline-variant/30 font-label text-[0.65rem] font-bold uppercase tracking-wider">BELUM</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-outline font-body text-sm">Belum ada personel yang diplot ke event ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── TOMBOL AKSI BAWAH ── --}}
<div class="flex flex-wrap gap-3">
    @if($event->financialRecord)
    <a href="{{ route('admin.financials.post_event', $event->id) }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-lg border border-blue-500/30 text-blue-600 font-label text-[0.65rem] font-bold uppercase tracking-widest hover:bg-blue-500/10 transition-colors">
        <i class="bi bi-calculator"></i> Kalkulasi Post-Event (Biaya Riil)
    </a>
    @endif
</div>

{{-- Modal Update Koordinat --}}
<div class="modal fade" id="modalKoordinat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-2xl overflow-hidden shadow-2xl bg-surface-container-lowest">
            <div class="px-6 py-5 border-b border-outline-variant/20 flex justify-between items-center bg-surface-container-low">
                <h5 class="font-headline font-bold text-lg text-primary flex items-center gap-2">
                    <i class="bi bi-geo-alt-fill text-secondary"></i> Set Koordinat Geofencing
                </h5>
                <button type="button" class="text-on-surface-variant hover:text-primary transition-colors" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form action="{{ route('admin.events.update_coordinates', $event->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="p-6">
                    <p class="font-body text-sm text-on-surface-variant leading-relaxed mb-5">Masukkan koordinat acara untuk keperluan absensi Geofencing kru (Radius 100m - 200m).</p>
                    <div class="space-y-4">
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Latitude</label>
                            <input type="text" name="latitude" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" value="{{ $event->latitude }}" placeholder="Contoh: -6.561567" required>
                        </div>
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Longitude</label>
                            <input type="text" name="longitude" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" value="{{ $event->longitude }}" placeholder="Contoh: 107.766724" required>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant/20 bg-surface-container-low flex justify-end gap-3">
                    <button type="button" class="px-5 py-2.5 rounded-lg border border-outline-variant/50 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-colors" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors">Simpan Koordinat</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
