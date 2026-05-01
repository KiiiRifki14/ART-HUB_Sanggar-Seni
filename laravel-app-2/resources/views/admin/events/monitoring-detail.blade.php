@extends('layouts.admin')
@section('title', 'Detail Monitoring – ' . $event->booking->client_name)
@section('page_title', 'Detail Operasional')
@section('page_subtitle', $event->event_code . ' · ' . \Carbon\Carbon::parse($event->event_date)->format('d M Y'))

@section('content')
@php
    $booking   = $event->booking;
    $finance   = $event->financialRecord;
    $eventDate = \Carbon\Carbon::parse($event->event_date);
    $daysUntil = now()->startOfDay()->diffInDays($eventDate->startOfDay(), false);
    $isPriority = ($daysUntil >= 0 && $daysUntil <= 3);
    
    $statusMap = [
        'pending'   => ['label' => 'Negotiation',   'class' => 'bg-orange-500/10 text-orange-600 border-orange-500/20',     'icon' => 'bi-chat-dots-fill'],
        'dp_paid'   => ['label' => 'Locked',        'class' => 'bg-secondary/10 text-secondary border-secondary/20',        'icon' => 'bi-lock-fill'],
        'confirmed' => ['label' => 'DP 50%',        'class' => 'bg-blue-500/10 text-blue-600 border-blue-500/20',           'icon' => 'bi-receipt-cutoff'],
        'paid_full' => ['label' => 'PAID Lunas',    'class' => 'bg-green-500/10 text-green-600 border-green-500/20',        'icon' => 'bi-check-circle-fill'],
        'completed' => ['label' => 'Completed',     'class' => 'bg-surface-container text-on-surface-variant border-outline-variant/30', 'icon' => 'bi-patch-check-fill'],
        'cancelled' => ['label' => 'Cancelled',     'class' => 'bg-red-500/10 text-red-600 border-red-500/20',              'icon' => 'bi-x-circle-fill'],
    ];
    $bStatus = $booking->status ?? 'pending';
    $statusInfo = $statusMap[$bStatus] ?? $statusMap['pending'];
@endphp

{{-- Alpine Component for entire page (to handle modal) --}}
<div x-data="{ showGPSModal: false }">

    {{-- Top Action Bar --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.events.monitoring') }}" class="w-10 h-10 rounded-xl bg-surface-container-lowest border border-outline-variant/30 flex items-center justify-center text-on-surface-variant hover:text-primary hover:bg-surface-container-low transition-colors shadow-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        
        @if($isPriority)
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-orange-500/30 bg-orange-500/10 font-label text-[0.65rem] font-bold uppercase tracking-widest text-orange-600 shadow-sm">
            <i class="bi bi-fire"></i> Priority H-{{ $daysUntil }}
        </span>
        @endif
        
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border font-label text-[0.65rem] font-bold uppercase tracking-widest {{ $statusInfo['class'] }}">
            <i class="bi {{ $statusInfo['icon'] }}"></i> {{ $statusInfo['label'] }}
        </span>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        {{-- Kiri: Info Acara + Koordinat --}}
        <div class="col-span-1 lg:col-span-5 flex flex-col gap-6">
            
            {{-- Info Acara Card --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-5">
                    <i class="bi bi-info-circle-fill text-9xl text-primary"></i>
                </div>
                
                <h3 class="font-headline text-lg text-primary font-bold mb-5 flex items-center gap-2 relative z-10">
                    <i class="bi bi-info-circle-fill text-secondary"></i> Rincian Acara
                </h3>
                
                <div class="grid grid-cols-2 gap-y-5 gap-x-4 relative z-10">
                    <div>
                        <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-1">Klien</span>
                        <span class="font-body text-sm font-semibold text-on-surface">{{ $booking->client_name }}</span>
                    </div>
                    <div>
                        <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-1">Jenis Acara</span>
                        <span class="font-body text-sm font-semibold text-on-surface capitalize">{{ str_replace('_',' ', $booking->event_type) }}</span>
                    </div>
                    <div>
                        <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-1">Tanggal</span>
                        <span class="font-body text-sm font-semibold text-on-surface">{{ $eventDate->format('d M Y') }}</span>
                    </div>
                    <div>
                        <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-1">Waktu (WIB)</span>
                        <span class="font-body text-sm font-semibold text-on-surface">
                            {{ \Carbon\Carbon::parse($event->event_start)->format('H:i') }} – {{ \Carbon\Carbon::parse($event->event_end)->format('H:i') }}
                        </span>
                    </div>
                    <div class="col-span-2">
                        <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold block mb-1">Venue Pelaksanaan</span>
                        <span class="font-body text-sm font-medium text-on-surface">{{ $event->venue }}</span>
                    </div>
                </div>
            </div>

            {{-- Koordinat GPS Card --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] p-6">
                <h3 class="font-headline text-lg text-primary font-bold mb-4 flex items-center gap-2">
                    <i class="bi bi-geo-alt-fill text-secondary"></i> Koordinat Geofencing
                </h3>
                
                @if($event->latitude && $event->longitude)
                    <div class="bg-green-500/5 border border-green-500/20 rounded-xl p-4 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-500/10 text-green-600 rounded border border-green-500/20 font-label text-[0.6rem] uppercase tracking-widest font-bold mb-3">
                            <i class="bi bi-check-circle-fill"></i> Ghosting Guard Aktif
                        </span>
                        <div class="flex items-center gap-4 font-body text-sm">
                            <div class="flex flex-col">
                                <span class="text-[0.65rem] text-outline uppercase font-bold tracking-widest">Latitude</span>
                                <span class="text-on-surface font-semibold">{{ $event->latitude }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[0.65rem] text-outline uppercase font-bold tracking-widest">Longitude</span>
                                <span class="text-on-surface font-semibold">{{ $event->longitude }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <a href="https://maps.google.com/?q={{ $event->latitude }},{{ $event->longitude }}" target="_blank" class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-outline-variant/30 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors">
                            <i class="bi bi-map"></i> Buka Maps
                        </a>
                        <button @click="showGPSModal = true" class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-secondary/30 bg-secondary/5 font-label text-xs font-bold uppercase tracking-widest text-secondary hover:bg-secondary hover:text-white transition-colors">
                            <i class="bi bi-pencil-square"></i> Ubah
                        </button>
                    </div>
                @else
                    <div class="bg-orange-500/5 border border-orange-500/20 rounded-xl p-4 mb-4 flex items-start gap-3">
                        <i class="bi bi-exclamation-triangle-fill text-orange-500 text-lg"></i>
                        <div>
                            <p class="font-body text-sm font-semibold text-orange-700 mb-0.5">Koordinat Belum Diset</p>
                            <p class="font-body text-xs text-orange-600/80">Personel tidak akan bisa melakukan check-in lokasi karena sistem Ghosting Guard belum mengenali area venue.</p>
                        </div>
                    </div>
                    <button @click="showGPSModal = true" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-label text-xs font-bold uppercase tracking-widest transition-colors shadow-sm">
                        <i class="bi bi-geo-alt"></i> Set Koordinat GPS
                    </button>
                @endif
            </div>
        </div>

        {{-- Kanan: Daftar Kru & Absensi --}}
        <div class="col-span-1 lg:col-span-7">
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] p-6 h-full flex flex-col">
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-outline-variant/20">
                    <h3 class="font-headline text-lg text-primary font-bold flex items-center gap-2">
                        <i class="bi bi-shield-check text-secondary"></i> Status Kehadiran Kru
                    </h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-surface-container border border-outline-variant/30 font-label text-xs font-bold text-on-surface">
                        {{ $event->personnel->count() }} / {{ $event->personnel_count ?? '–' }} Personel
                    </span>
                </div>

                @if($event->personnel->isEmpty())
                    <div class="flex-grow flex flex-col items-center justify-center py-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center text-outline/50 mb-4">
                            <i class="bi bi-person-x text-2xl"></i>
                        </div>
                        <h4 class="font-headline text-base font-bold text-on-surface mb-1">Belum Ada Plotting</h4>
                        <p class="font-body text-sm text-outline mb-4">Anda belum memasukkan kru ke dalam daftar tugas untuk acara ini.</p>
                        <a href="{{ route('admin.events.plotting', $event->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white font-label text-[0.7rem] font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-md hover:shadow-lg">
                            <i class="bi bi-magic"></i> Buka Smart Plotting
                        </a>
                    </div>
                @else
                    <div class="flex-grow flex flex-col gap-3">
                        @foreach($event->personnel as $p)
                        @php
                            $pivot  = $p->pivot;
                            $aStatus = $pivot->attendance_status ?? 'not_arrived';
                            
                            $dotColor = match($aStatus) {
                                'on_time' => 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]',
                                'late'    => 'bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.6)]',
                                default   => ($pivot->checked_in_at ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]' : 'bg-red-500'),
                            };
                            $lateText = ($aStatus === 'late' && $pivot->late_minutes > 0)
                                ? '<span class="text-orange-600 ml-1">(Telat ' . $pivot->late_minutes . ' mnt)</span>'
                                : '';
                        @endphp
                        
                        <div class="flex items-center justify-between p-3.5 rounded-xl border border-outline-variant/20 hover:bg-surface-container-low/50 transition-colors group">
                            <div class="flex items-center gap-4">
                                <div class="w-2.5 h-2.5 rounded-full {{ $dotColor }} relative flex-shrink-0">
                                    @if($aStatus === 'on_time' || $aStatus === 'late' || $pivot->checked_in_at)
                                        <div class="absolute inset-0 rounded-full {{ $dotColor }} animate-ping opacity-20"></div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-body font-bold text-sm text-on-surface">{{ $p->user->name ?? '–' }}</div>
                                    <div class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mt-0.5">
                                        {{ str_replace('_',' ', $pivot->role_in_event) }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                @if($pivot->checked_in_at)
                                    <div class="font-body text-sm font-bold text-green-600 flex items-center justify-end gap-1.5">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        {{ \Carbon\Carbon::parse($pivot->checked_in_at)->format('H:i') }}
                                        {!! $lateText !!}
                                    </div>
                                @else
                                    <span class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Belum Hadir</span>
                                @endif
                                <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mt-1">
                                    Honor: <span class="text-on-surface-variant">Rp {{ number_format($pivot->fee, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Legend --}}
                    <div class="mt-6 pt-4 border-t border-outline-variant/20 flex flex-wrap gap-4 font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold justify-center">
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_4px_rgba(34,197,94,0.6)]"></span> Hadir</span>
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-orange-500 shadow-[0_0_4px_rgba(249,115,22,0.6)]"></span> Terlambat</span>
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-500"></span> Belum Absen</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Alpine: Set Koordinat --}}
    <div x-show="showGPSModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         style="display: none;">
        
        <div x-show="showGPSModal"
             @click.away="showGPSModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="bg-surface-container-lowest w-full max-w-md rounded-2xl border border-outline-variant/30 shadow-2xl overflow-hidden flex flex-col">
            
            <div class="px-6 py-4 border-b border-outline-variant/20 flex items-center justify-between bg-surface-container-low/30">
                <h4 class="font-headline text-primary font-bold flex items-center gap-2">
                    <i class="bi bi-geo-fill text-secondary"></i> Set Lokasi Geofencing
                </h4>
                <button @click="showGPSModal = false" class="text-outline hover:text-primary transition-colors">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.events.update_coordinates', $event->id) }}" method="POST">
                @csrf @method('PATCH')
                <div class="p-6 space-y-4">
                    <p class="font-body text-sm text-on-surface-variant leading-relaxed">
                        Masukkan titik koordinat (Latitude & Longitude) agar fitur absensi <b>Ghosting Guard</b> dapat mengunci kehadiran kru berdasarkan radius lokasi.
                    </p>
                    
                    <div>
                        <label class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline block mb-1.5 ml-1">Latitude</label>
                        <input type="number" name="latitude" step="any" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" placeholder="-6.200000" value="{{ $event->latitude }}" required>
                    </div>
                    <div>
                        <label class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline block mb-1.5 ml-1">Longitude</label>
                        <input type="number" name="longitude" step="any" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" placeholder="106.816666" value="{{ $event->longitude }}" required>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-outline-variant/20 flex gap-3 bg-surface-container-low/30">
                    <button type="button" @click="showGPSModal = false" class="flex-1 px-4 py-2.5 rounded-xl border border-outline-variant/50 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-md hover:shadow-lg">
                        Simpan Area
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
