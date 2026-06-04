@extends('layouts.admin')
@section('title', 'Detail Monitoring – ' . ($event->booking->client_name ?? '-'))
@section('page_title', 'Detail Operasional Event')
@section('page_subtitle', $event->event_code . ' · ' . \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y'))

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<style>
    .event-map-container {
        width: 100%;
        height: 300px;
        border-radius: 0.75rem;
        border: 1px solid rgba(106, 90, 84, 0.2);
        z-index: 1;
    }
    .leaflet-container {
        border-radius: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
@endpush

@section('content')
@php
    $booking   = $event->booking;
    $finance   = $event->financialRecord;
    $eventDate = \Carbon\Carbon::parse($event->event_date);
    $daysUntil = now()->startOfDay()->diffInDays($eventDate->startOfDay(), false);
    $isPast    = $daysUntil < 0;
    $isPriority = ($daysUntil >= 0 && $daysUntil <= 3);

    $bStatusMap = [
        'pending'   => ['label' => 'Negosiasi / Pending',  'class' => 'bg-orange-500/10 text-orange-600 border-orange-500/20',              'icon' => 'bi-chat-dots-fill'],
        'dp_paid'   => ['label' => 'Terkunci (DP Diverifikasi)',  'class' => 'bg-secondary/10 text-secondary border-secondary/20',           'icon' => 'bi-lock-fill'],
        'confirmed' => ['label' => 'DP 50% Dikonfirmasi',  'class' => 'bg-blue-500/10 text-blue-600 border-blue-500/20',                    'icon' => 'bi-receipt-cutoff'],
        'paid_full' => ['label' => 'LUNAS',                'class' => 'bg-green-500/10 text-green-600 border-green-500/20',                 'icon' => 'bi-check-circle-fill'],
        'completed' => ['label' => 'Selesai',              'class' => 'bg-surface-container text-on-surface-variant border-outline-variant/30', 'icon' => 'bi-patch-check-fill'],
        'cancelled' => ['label' => 'Dibatalkan',           'class' => 'bg-red-500/10 text-red-600 border-red-500/20',                       'icon' => 'bi-x-circle-fill'],
    ];
    $bStatus    = $booking->status ?? 'pending';
    $statusInfo = $bStatusMap[$bStatus] ?? $bStatusMap['pending'];

    $hadrCount  = $event->personnel->filter(fn($p) => !is_null($p->pivot->checked_in_at))->count();
    $totalCrew  = $event->personnel->count();
    $specialtyMap = [
        'penari'      => ['Penari',        'bi-person-arms-up',  'text-pink-600'],
        'pemusik'     => ['Pemusik',       'bi-music-note-beamed','text-blue-600'],
        'multi_talent'=> ['Multi-Talent',  'bi-stars',           'text-secondary'],
    ];
@endphp

{{-- ── TOP BAR ── --}}
<div class="flex flex-wrap items-center gap-3 mb-6">
    <a href="{{ route('admin.events.monitoring') }}" class="w-10 h-10 rounded-xl bg-surface-container-lowest border border-outline-variant/30 flex items-center justify-center text-on-surface-variant hover:text-primary hover:bg-surface-container-low transition-colors shadow-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    @if($isPriority && !$isPast)
    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-orange-500/30 bg-orange-500/10 font-label text-[0.65rem] font-bold uppercase tracking-widest text-orange-600 shadow-sm">
        <i class="bi bi-fire"></i> Priority H-{{ $daysUntil }}
    </span>
    @elseif($isPast)
    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-outline-variant/30 bg-surface-container font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline shadow-sm">
        <i class="bi bi-clock-history"></i> Sudah Lewat
    </span>
    @else
    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-blue-500/20 bg-blue-500/5 font-label text-[0.65rem] font-bold uppercase tracking-widest text-blue-600 shadow-sm">
        <i class="bi bi-calendar-check"></i> H-{{ $daysUntil }}
    </span>
    @endif
    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border font-label text-[0.65rem] font-bold uppercase tracking-widest {{ $statusInfo['class'] }}">
        <i class="bi {{ $statusInfo['icon'] }}"></i> {{ $statusInfo['label'] }}
    </span>
    <div class="ml-auto flex gap-2">
        <a href="{{ route('admin.events.plotting', $event->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary text-white font-label text-[0.65rem] font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-sm">
            <i class="bi bi-diagram-3"></i> Smart Plotting
        </a>
        <a href="{{ route('admin.events.show', $event->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-outline-variant/30 bg-surface-container-lowest text-on-surface-variant font-label text-[0.65rem] font-bold uppercase tracking-widest hover:bg-surface-container hover:text-primary transition-all shadow-sm">
            <i class="bi bi-gear"></i> Kelola Event
        </a>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- BARIS 1: INFO KLIEN + INFO ACARA + LOKASI                    --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">

    {{-- Kartu Info Klien --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] p-5">
        <h3 class="font-headline text-sm text-primary font-bold mb-4 flex items-center gap-2 pb-3 border-b border-outline-variant/20">
            <i class="bi bi-person-circle text-secondary"></i> Informasi Klien
        </h3>
        <div class="space-y-3.5">
            <div>
                <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-0.5">Nama Klien</span>
                <span class="font-body text-sm font-bold text-on-surface">{{ $booking->client_name ?? '-' }}</span>
            </div>
            <div>
                <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-0.5">No. WhatsApp</span>
                @if($booking->client_phone)
                <a href="https://wa.me/62{{ ltrim($booking->client_phone, '0') }}" target="_blank"
                   class="inline-flex items-center gap-1.5 font-body text-sm font-semibold text-green-600 hover:text-green-700 transition-colors">
                    <i class="bi bi-whatsapp text-base"></i> {{ $booking->client_phone }}
                </a>
                @else
                <span class="font-label text-xs text-outline">—</span>
                @endif
            </div>
            <div>
                <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-0.5">Kode Booking</span>
                <span class="inline-block font-mono text-xs font-bold px-2.5 py-1 rounded bg-secondary-container/30 text-secondary border border-secondary/20">
                    {{ $event->event_code ?? '-' }}
                </span>
            </div>
            <div>
                <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-0.5">Jenis Pementasan</span>
                <span class="font-body text-sm font-semibold text-on-surface capitalize">{{ str_replace('_', ' ', $booking->event_type ?? '-') }}</span>
            </div>
            @if($booking->notes)
            <div>
                <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-0.5">Catatan Klien</span>
                <p class="font-body text-xs text-on-surface-variant bg-surface-container rounded-lg p-2.5 leading-relaxed">{{ $booking->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Kartu Info Acara --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] p-5">
        <h3 class="font-headline text-sm text-primary font-bold mb-4 flex items-center gap-2 pb-3 border-b border-outline-variant/20">
            <i class="bi bi-calendar3 text-secondary"></i> Detail Waktu & Acara
        </h3>
        <div class="space-y-3.5">
            <div>
                <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-0.5">Tanggal Pelaksanaan</span>
                <span class="font-body text-sm font-bold text-on-surface">{{ $eventDate->translatedFormat('l, d F Y') }}</span>
                @if(!$isPast)
                <span class="ml-2 font-label text-[0.6rem] px-1.5 py-0.5 rounded {{ $isPriority ? 'bg-orange-500/10 text-orange-600' : 'bg-blue-500/10 text-blue-600' }} font-bold">H-{{ $daysUntil }}</span>
                @endif
            </div>
            <div>
                <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-0.5">Jam Pelaksanaan</span>
                <span class="font-body text-sm font-bold text-on-surface">
                    {{ \Carbon\Carbon::parse($event->event_start)->format('H:i') }} – {{ \Carbon\Carbon::parse($event->event_end)->format('H:i') }} WIB
                </span>
            </div>
            <div>
                <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-0.5">Status Event</span>
                @php $es = ['planning'=>['Perencanaan','bg-orange-500/10 text-orange-600'],'rehearsal'=>['Latihan','bg-amber-500/10 text-amber-600'],'ready'=>['Siap','bg-blue-500/10 text-blue-600'],'ongoing'=>['Berlangsung','bg-indigo-500/10 text-indigo-600'],'completed'=>['Selesai','bg-green-500/10 text-green-600'],'cancelled'=>['Dibatalkan','bg-red-500/10 text-red-600']]; $esInfo = $es[$event->status] ?? ['Unknown','bg-surface-container text-outline']; @endphp
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[0.65rem] font-label font-bold {{ $esInfo[1] }}">{{ $esInfo[0] }}</span>
            </div>
            <div>
                <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-0.5">Personel Diplot / Kuota</span>
                <div class="flex items-center gap-3 mt-1">
                    <span class="font-headline text-2xl font-bold text-primary">{{ $totalCrew }}</span>
                    <span class="font-body text-sm text-outline">/ {{ $event->personnel_count ?? '∞' }} orang</span>
                    @if($totalCrew > 0)
                    <div class="flex-1 bg-surface-container-high rounded-full h-2 overflow-hidden">
                        @php $pct = $event->personnel_count > 0 ? min(100, round($totalCrew / $event->personnel_count * 100)) : 100; @endphp
                        <div class="h-2 rounded-full {{ $pct >= 100 ? 'bg-green-500' : 'bg-secondary' }} transition-all" style="width: {{ $pct }}%"></div>
                    </div>
                    @endif
                </div>
            </div>
            <div>
                <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-0.5">Kehadiran Kru</span>
                <div class="flex items-center gap-2">
                    <span class="font-headline text-2xl font-bold {{ $hadrCount === $totalCrew && $totalCrew > 0 ? 'text-green-600' : 'text-on-surface' }}">{{ $hadrCount }}</span>
                    <span class="font-body text-sm text-outline">/ {{ $totalCrew }} hadir</span>
                </div>
            </div>
        </div>
    </div>

     {{-- Kartu Lokasi --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] p-5">
        <h3 class="font-headline text-sm text-primary font-bold mb-4 flex items-center gap-2 pb-3 border-b border-outline-variant/20">
            <i class="bi bi-geo-alt-fill text-secondary"></i> Lokasi Pementasan
        </h3>
        <div class="space-y-3.5">
            <div>
                <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-0.5">Nama Venue</span>
                <span class="font-body text-sm font-bold text-on-surface">{{ $event->venue ?? $booking->venue ?? '-' }}</span>
            </div>
            <div>
                <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-0.5">Alamat Lengkap</span>
                <p class="font-body text-sm text-on-surface-variant leading-relaxed">{{ $booking->venue_address ?? $event->venue_address ?? '—' }}</p>
            </div>
            <div>
                <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-1">Koordinat GPS (Geofencing)</span>
                @if($event->latitude && $event->longitude)
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-green-500/10 text-green-600 border border-green-500/20 font-body text-xs font-semibold mb-2">
                        <i class="bi bi-check-circle-fill"></i> Ghosting Guard Aktif
                    </div>
                    <div class="font-mono text-xs text-on-surface-variant bg-surface-container rounded-lg p-2 mb-2">
                        {{ $event->latitude }}, {{ $event->longitude }}
                    </div>
                    <div id="eventMapContainer" class="event-map-container mb-3"></div>
                    <a href="https://maps.google.com/?q={{ $event->latitude }},{{ $event->longitude }}" target="_blank"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-outline-variant/30 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors">
                        <i class="bi bi-map"></i> Buka Google Maps
                    </a>
                @else
                    <div class="flex items-start gap-2 bg-orange-500/5 border border-orange-500/20 rounded-xl p-3">
                        <i class="bi bi-exclamation-triangle-fill text-orange-500 text-base mt-0.5"></i>
                        <div>
                            <p class="font-body text-xs font-bold text-orange-700">Koordinat Belum Diset</p>
                            <p class="font-body text-xs text-orange-600/80 mt-0.5">Kru tidak bisa check-in. Atur di menu Kelola Event.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- BARIS 2: STATUS PEMBAYARAN                                    --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] p-5 mb-5">
    <h3 class="font-headline text-sm text-primary font-bold mb-4 flex items-center gap-2 pb-3 border-b border-outline-variant/20">
        <i class="bi bi-wallet2 text-secondary"></i> Status & Riwayat Pembayaran
    </h3>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        {{-- Nilai Kontrak --}}
        <div class="bg-surface-container rounded-xl p-4 text-center">
            <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold mb-1">Total Nilai Kontrak</div>
            <div class="font-headline text-xl font-bold text-on-surface">Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}</div>
        </div>

        {{-- DP 50% --}}
        <div class="rounded-xl p-4 text-center border {{ in_array($bStatus, ['dp_paid','confirmed','paid_full','completed']) ? 'bg-blue-500/5 border-blue-500/20' : 'bg-surface-container border-outline-variant/20' }}">
            <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold mb-1">DP 50%</div>
            <div class="font-headline text-xl font-bold {{ in_array($bStatus, ['dp_paid','confirmed','paid_full','completed']) ? 'text-blue-600' : 'text-outline' }}">
                Rp {{ number_format($booking->dp_amount ?? 0, 0, ',', '.') }}
            </div>
            @if(in_array($bStatus, ['dp_paid','confirmed','paid_full','completed']))
            <div class="flex items-center justify-center gap-1 mt-1.5">
                <i class="bi bi-check-circle-fill text-blue-500 text-xs"></i>
                <span class="font-label text-[0.6rem] text-blue-600 font-bold">Diterima</span>
            </div>
            @if($booking->dp_paid_at ?? $booking->updated_at)
            <div class="font-label text-[0.55rem] text-outline mt-0.5">{{ \Carbon\Carbon::parse($booking->dp_paid_at ?? $booking->updated_at)->format('d M Y') }}</div>
            @endif
            @else
            <div class="font-label text-[0.6rem] text-outline mt-1.5">Menunggu</div>
            @endif
        </div>

        {{-- Pelunasan --}}
        @php $isLunas = in_array($bStatus, ['paid_full','completed']); @endphp
        <div class="rounded-xl p-4 text-center border {{ $isLunas ? 'bg-green-500/5 border-green-500/20' : 'bg-surface-container border-outline-variant/20' }}">
            <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold mb-1">Pelunasan (Sisa 50%)</div>
            <div class="font-headline text-xl font-bold {{ $isLunas ? 'text-green-600' : 'text-outline' }}">
                Rp {{ number_format(($booking->total_price ?? 0) - ($booking->dp_amount ?? 0), 0, ',', '.') }}
            </div>
            @if($isLunas)
            <div class="flex items-center justify-center gap-1 mt-1.5">
                <i class="bi bi-check-circle-fill text-green-500 text-xs"></i>
                <span class="font-label text-[0.6rem] text-green-600 font-bold">LUNAS</span>
            </div>
            @if($booking->full_paid_at ?? null)
            <div class="font-label text-[0.55rem] text-outline mt-0.5">{{ \Carbon\Carbon::parse($booking->full_paid_at)->format('d M Y') }}</div>
            @endif
            @else
            <div class="font-label text-[0.6rem] text-outline mt-1.5">Belum Dibayar</div>
            @endif
        </div>

        {{-- Estimasi Honor Kru --}}
        <div class="bg-primary/5 border border-primary/15 rounded-xl p-4 text-center">
            <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold mb-1">Estimasi Honor Kru</div>
            <div class="font-headline text-xl font-bold text-primary">Rp {{ number_format($event->estimated_total_honor ?? 0, 0, ',', '.') }}</div>
            @if($finance)
            <div class="font-label text-[0.55rem] text-outline mt-1">
                Ops Budget: Rp {{ number_format($finance->operational_budget, 0, ',', '.') }}
            </div>
            @endif
        </div>
    </div>

    {{-- Progress Bar Pembayaran --}}
    <div class="mt-4 pt-4 border-t border-outline-variant/20">
        <div class="flex justify-between font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-2">
            <span>Progress Pembayaran</span>
            <span>{{ $isLunas ? '100%' : (in_array($bStatus, ['dp_paid','confirmed']) ? '50%' : '0%') }}</span>
        </div>
        <div class="w-full bg-surface-container-high rounded-full h-2.5 overflow-hidden">
            @php $payPct = $isLunas ? 100 : (in_array($bStatus, ['dp_paid','confirmed']) ? 50 : 0); @endphp
            <div class="h-2.5 rounded-full transition-all {{ $isLunas ? 'bg-green-500' : 'bg-blue-500' }}" style="width: {{ $payPct }}%"></div>
        </div>
        <div class="flex justify-between font-label text-[0.55rem] text-outline mt-1">
            <span>Booking Dibuat: {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}</span>
            <span>Status: {{ $statusInfo['label'] }}</span>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- BARIS 3: DATA KEUANGAN POST-EVENT (jika ada)                  --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
@if($finance)
<div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] p-5 mb-5">
    <h3 class="font-headline text-sm text-primary font-bold mb-4 flex items-center gap-2 pb-3 border-b border-outline-variant/20">
        <i class="bi bi-graph-up-arrow text-secondary"></i> Ringkasan Keuangan Post-Event
        <a href="{{ route('admin.financials.post_event', $event->id) }}" class="ml-auto inline-flex items-center gap-1 px-3 py-1 rounded-lg border border-outline-variant/30 font-label text-[0.6rem] font-bold uppercase tracking-widest text-on-surface-variant hover:bg-primary hover:text-white hover:border-primary transition-all">
            <i class="bi bi-pencil"></i> Detail & Edit
        </a>
    </h3>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-green-500/5 border border-green-500/20 rounded-xl p-3 text-center">
            <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold mb-1">Total Pendapatan</div>
            <div class="font-headline text-base font-bold text-green-600">Rp {{ number_format($finance->total_revenue, 0, ',', '.') }}</div>
        </div>
        <div class="bg-primary/5 border border-primary/15 rounded-xl p-3 text-center">
            <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold mb-1">Laba Tetap ({{ $finance->fixed_profit_pct }}%)</div>
            <div class="font-headline text-base font-bold text-primary">Rp {{ number_format($finance->fixed_profit, 0, ',', '.') }}</div>
        </div>
        <div class="bg-surface-container rounded-xl p-3 text-center">
            <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold mb-1">Realisasi Ops</div>
            <div class="font-headline text-base font-bold text-on-surface">Rp {{ number_format($finance->actual_operational_cost, 0, ',', '.') }}</div>
        </div>
        @php $selisih = $finance->operational_budget - $finance->actual_operational_cost; @endphp
        <div class="{{ $selisih >= 0 ? 'bg-blue-500/5 border-blue-500/20' : 'bg-red-500/5 border-red-500/20' }} border rounded-xl p-3 text-center">
            <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold mb-1">Efisiensi Ops</div>
            <div class="font-headline text-base font-bold {{ $selisih >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                {{ $selisih >= 0 ? '+' : '-' }}Rp {{ number_format(abs($selisih), 0, ',', '.') }}
            </div>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- BARIS 4: DAFTAR KRU & STATUS ABSENSI                         --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant/20 bg-surface-container-low/40">
        <h3 class="font-headline text-base text-primary font-bold flex items-center gap-2">
            <i class="bi bi-shield-check text-secondary"></i> Daftar Kru & Status Kehadiran
        </h3>
        <div class="flex items-center gap-3">
            <span class="font-label text-[0.65rem] font-bold text-on-surface-variant">
                <span class="text-green-600">{{ $hadrCount }}</span> / {{ $totalCrew }} Hadir
            </span>
            @if($totalCrew > 0)
            <span class="inline-flex items-center px-3 py-1 rounded-full border border-outline-variant/30 bg-surface-container font-label text-xs font-bold text-on-surface">
                {{ $totalCrew }} Personel
            </span>
            @endif
        </div>
    </div>

    @if($event->personnel->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center px-6">
            <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center text-outline/50 mb-4">
                <i class="bi bi-person-x text-2xl"></i>
            </div>
            <h4 class="font-headline text-base font-bold text-on-surface mb-1">Belum Ada Plotting Kru</h4>
            <p class="font-body text-sm text-outline mb-4">Kru belum ditambahkan ke event ini.</p>
            <a href="{{ route('admin.events.plotting', $event->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white font-label text-[0.7rem] font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-md">
                <i class="bi bi-magic"></i> Buka Smart Plotting
            </a>
        </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full min-w-[600px]">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-3 text-left">Personel</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-4 py-3 text-left">Spesialisasi</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-4 py-3 text-left">Peran di Event</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-4 py-3 text-right">Honor</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-4 py-3 text-center">Check-in</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-4 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/15">
                @foreach($event->personnel as $p)
                @php
                    $pivot   = $p->pivot;
                    $aStatus = $pivot->attendance_status ?? 'not_arrived';
                    $dotColor = match($aStatus) {
                        'on_time' => 'bg-green-500',
                        'late'    => 'bg-orange-500',
                        default   => ($pivot->checked_in_at ? 'bg-green-500' : 'bg-red-400'),
                    };
                    [$specLabel, $specIcon, $specColor] = $specialtyMap[$p->specialty] ?? ['—', 'bi-person', 'text-outline'];
                    $initials = strtoupper(substr($p->user->name ?? 'P', 0, 2));
                @endphp
                <tr class="hover:bg-surface-container-low/40 transition-colors">
                    <td class="px-6 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="relative flex-shrink-0">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-container to-primary text-white flex items-center justify-center font-bold text-xs">{{ $initials }}</div>
                                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full {{ $dotColor }} border-2 border-white"></span>
                            </div>
                            <div>
                                <div class="font-body font-bold text-sm text-on-surface">{{ $p->user->name ?? '–' }}</div>
                                <div class="font-label text-[0.6rem] text-outline">{{ $p->user->phone ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-flex items-center gap-1 font-label text-[0.6rem] font-bold {{ $specColor }}">
                            <i class="bi {{ $specIcon }}"></i> {{ $specLabel }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="font-body text-sm text-on-surface-variant capitalize">{{ str_replace('_', ' ', $pivot->role_in_event ?? '–') }}</span>
                    </td>
                    <td class="px-4 py-3.5 text-right">
                        <span class="font-body text-sm font-semibold text-on-surface">Rp {{ number_format($pivot->fee ?? 0, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        @if($pivot->checked_in_at)
                            <span class="font-body text-sm font-bold text-green-600 flex items-center justify-center gap-1">
                                <i class="bi bi-geo-alt-fill"></i> {{ \Carbon\Carbon::parse($pivot->checked_in_at)->format('H:i') }}
                            </span>
                        @else
                            <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        @if($aStatus === 'on_time')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-green-500/10 text-green-600 border border-green-500/20 font-label text-[0.6rem] font-bold"><i class="bi bi-check-circle-fill"></i> Tepat Waktu</span>
                        @elseif($aStatus === 'late')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-orange-500/10 text-orange-600 border border-orange-500/20 font-label text-[0.6rem] font-bold"><i class="bi bi-clock-fill"></i> Telat {{ $pivot->late_minutes ?? 0 }}mnt</span>
                        @elseif($pivot->checked_in_at)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-blue-500/10 text-blue-600 border border-blue-500/20 font-label text-[0.6rem] font-bold"><i class="bi bi-check-circle"></i> Check-in</span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-red-500/10 text-red-500 border border-red-500/20 font-label text-[0.6rem] font-bold"><i class="bi bi-x-circle"></i> Belum Hadir</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-surface-container-low border-t border-outline-variant/20">
                <tr>
                    <td colspan="3" class="px-6 py-3 font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Total Honor Kru</td>
                    <td class="px-4 py-3 text-right font-headline font-bold text-base text-secondary">
                        Rp {{ number_format($event->personnel->sum(fn($p) => $p->pivot->fee ?? 0), 0, ',', '.') }}
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapContainer = document.getElementById('eventMapContainer');
    if (!mapContainer) return;
    
    const latitude = {{ $event->latitude ?? 'null' }};
    const longitude = {{ $event->longitude ?? 'null' }};
    
    if (!latitude || !longitude) return;
    
    // Initialize map
    const map = L.map(mapContainer).setView([latitude, longitude], 16);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Add marker
    L.marker([latitude, longitude], {
        title: '{{ $event->venue ?? "Event Location" }}'
    }).addTo(map).bindPopup(`
        <strong>{{ $event->venue ?? 'Event Venue' }}</strong><br>
        {{ $event->latitude }}, {{ $event->longitude }}
    `).openPopup();
});
</script>
@endpush

@endsection
