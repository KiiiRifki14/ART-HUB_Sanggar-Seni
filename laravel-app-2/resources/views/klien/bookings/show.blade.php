@extends('layouts.klien')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<style>
    /* Fix Leaflet + Tailwind conflict */
    .leaflet-container img {
        max-width: none !important;
        max-height: none !important;
        width: auto !important;
    }
    .leaflet-container img.leaflet-tile {
        width: 256px !important;
        height: 256px !important;
    }
    .leaflet-container * {
        box-sizing: content-box !important;
    }
    .leaflet-container {
        box-sizing: border-box !important;
        border-radius: 1rem;
        font-size: 12px;
    }
    #bookingDetailMap {
        width: 100%;
        height: 240px;
        position: relative;
        display: block;
        border-radius: 1rem;
        border: 1px solid rgba(106, 90, 84, 0.2);
        overflow: hidden;
        z-index: 1;
    }
</style>
@endpush

@section('title', 'Detail Pesanan – ART-HUB Sanggar Cahaya Gumilang')

@section('content')

{{-- BACK NAV --}}
<div class="flex items-center gap-2 mb-6 font-label text-xs uppercase tracking-widest font-bold">
    <a href="{{ route('klien.dashboard') }}" class="text-on-surface-variant hover:text-secondary transition-colors flex items-center gap-1.5">
        <i class="bi bi-arrow-left text-sm"></i> Kembali ke Dashboard
    </a>
    <span class="text-outline-variant">/</span>
    <span class="text-outline">Detail Pesanan #BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
</div>

@php
    $statusMap = [
        'pending'   => ['label' => 'Menunggu Konfirmasi',  'cls' => 'bg-orange-500/10 text-orange-600 border-orange-500/20',   'step' => 1],
        'dp_paid'   => ['label' => 'DP Terkonfirmasi',     'cls' => 'bg-blue-500/10 text-blue-600 border-blue-500/20',        'step' => 3],
        'confirmed' => ['label' => 'Jadwal Terkunci',      'cls' => 'bg-green-500/10 text-green-600 border-green-500/20',     'step' => 3],
        'paid_full' => ['label' => 'Pelunasan Lunas',      'cls' => 'bg-green-500/10 text-green-600 border-green-500/20',      'step' => 4],
        'completed' => ['label' => 'Pementasan Selesai',   'cls' => 'bg-surface-container-high text-on-surface-variant border-outline-variant/30',      'step' => 5],
        'cancelled' => ['label' => 'Dibatalkan',           'cls' => 'bg-red-500/10 text-red-600 border-red-500/20',            'step' => 0],
    ];
    $st   = $statusMap[$booking->status] ?? ['label' => strtoupper($booking->status), 'cls' => 'bg-surface-container border-outline-variant/30 text-outline', 'step' => 0];
    $step = $st['step'];
    
    // Jika pending tapi sudah upload bukti, naikkan ke Step 2 (DP Masuk)
    if ($booking->status === 'pending' && $booking->payment_proof) {
        $step = 2;
        $st['label'] = 'Memverifikasi DP';
    }
@endphp

{{-- ═══════ PROGRESS TRACKER ═══════ --}}
@if($booking->status !== 'cancelled')
<div class="bg-white border border-outline-variant/20 rounded-3xl p-6 sm:p-8 mb-8 shadow-sm overflow-x-auto">
    <div class="flex items-center min-w-[600px] justify-between">
        @foreach([
            [1, 'bi-file-earmark-text', 'Pengajuan'],
            [2, 'bi-wallet2',           'DP Masuk'],
            [3, 'bi-calendar2-check',   'Terkunci'],
            [4, 'bi-check2-all',        'Lunas'],
            [5, 'bi-trophy',            'Selesai'],
        ] as [$n, $ico, $lbl])
        
        @php
            $isDone = $step >= $n;
            $isActive = $step === $n;
            $circleColor = $isActive ? 'bg-secondary/15 border-secondary text-primary shadow-sm scale-110' : ($isDone ? 'bg-green-500/10 border-green-500 text-green-600' : 'bg-surface-container-low border-outline-variant/40 text-outline-variant');
            $labelColor = $isActive ? 'text-primary font-black' : ($isDone ? 'text-green-600 font-bold' : 'text-outline');
        @endphp
        
        <div class="flex flex-col items-center gap-2.5 w-20 relative z-10">
            <div class="w-12 h-12 rounded-full border-2 flex items-center justify-center transition-all duration-300 {{ $circleColor }}">
                <i class="bi {{ $ico }} text-xl"></i>
            </div>
            <div class="font-label text-[0.6rem] uppercase tracking-widest text-center whitespace-nowrap {{ $labelColor }}">{{ $lbl }}</div>
        </div>
        
        @if($n < 5)
            <div class="flex-grow h-0.5 mx-4 rounded-full transition-all duration-300 {{ $step > $n ? 'bg-green-500' : 'bg-outline-variant/20' }}"></div>
        @endif
        @endforeach
    </div>
</div>
@endif

{{-- ═══════ MAIN CONTENT ═══════ --}}
<div class="flex flex-col lg:flex-row gap-8 items-start">

    {{-- KOLOM KIRI: Rincian Event --}}
    <div class="flex-grow w-full space-y-6">

        {{-- Header & Detail Acara --}}
        <div class="bg-white rounded-3xl border border-outline-variant/20 shadow-sm overflow-hidden">
            {{-- Title Bar --}}
            <div class="p-6 sm:p-8 border-b border-outline-variant/10 bg-surface-container-low/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Kode Transaksi</div>
                    <div class="font-headline font-bold text-2xl text-primary">BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl border font-label text-xs font-bold uppercase tracking-wider {{ $st['cls'] }}">
                        <i class="bi bi-circle-fill text-[0.45rem] animate-pulse"></i> {{ $st['label'] }}
                    </span>
                </div>
            </div>
            
            {{-- Content --}}
            <div class="p-6 sm:p-8">
                <div class="font-label text-xs uppercase tracking-widest text-primary font-bold flex items-center gap-2 mb-6 border-b border-outline-variant/10 pb-2">
                    <i class="bi bi-calendar-event-fill text-secondary"></i> Spesifikasi Acara
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8 font-body">
                    <div>
                        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Jenis Pementasan</div>
                        <div class="font-headline font-bold text-lg text-primary capitalize">{{ ucwords(str_replace('_', ' ', $booking->event_type)) }}</div>
                    </div>
                    <div>
                        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Tanggal Pelaksanaan</div>
                        <div class="text-sm font-bold text-on-surface">{{ \Carbon\Carbon::parse($booking->event_date)->isoFormat('dddd, D MMMM Y') }}</div>
                    </div>
                    <div>
                        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Waktu Mulai</div>
                        <div class="text-sm font-bold text-on-surface">{{ \Carbon\Carbon::parse($booking->event_start)->format('H:i') }} WIB</div>
                    </div>
                    <div>
                        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Waktu Selesai</div>
                        <div class="text-sm font-bold text-on-surface">{{ \Carbon\Carbon::parse($booking->event_end)->format('H:i') }} WIB</div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Lokasi Pementasan</div>
                        <div class="text-sm font-bold text-on-surface mb-1">{{ $booking->venue }}</div>
                        @if($booking->venue_address)
                        <div class="text-xs text-on-surface-variant leading-relaxed mb-3">{{ $booking->venue_address }}</div>
                        @endif
                        @if($booking->latitude && $booking->longitude)
                            <div id="bookingDetailMap" class="w-full mt-3 z-0"></div>
                            <div class="mt-3">
                                <a href="https://maps.google.com/?q={{ $booking->latitude }},{{ $booking->longitude }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 text-xs text-secondary hover:text-primary transition-colors font-label font-bold uppercase tracking-wider">
                                    <i class="bi bi-geo-alt-fill"></i> Petunjuk Arah Google Maps
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Context Banner --}}
        @if($booking->status === 'pending' && !$booking->payment_proof)
        <div class="flex items-start gap-4 p-6 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-900 shadow-sm">
            <i class="bi bi-info-circle-fill text-2xl mt-0.5 text-amber-600"></i>
            <div>
                <div class="font-headline font-bold text-base mb-1">Menunggu Kesepakatan Harga</div>
                <div class="font-body text-xs leading-relaxed opacity-90">
                    Pimpinan sanggar akan menghubungi Anda melalui WhatsApp untuk mendiskusikan harga final berdasarkan kebutuhan pementasan. Setelah disepakati, silakan transfer commitment fee (DP) Anda.
                </div>
            </div>
        </div>
        @elseif($booking->status === 'cancelled')
        <div class="flex items-start gap-4 p-6 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-900 shadow-sm">
            <i class="bi bi-x-circle-fill text-2xl mt-0.5 text-red-600"></i>
            <div>
                <div class="font-headline font-bold text-base mb-1">Jadwal Acara Dibatalkan</div>
                <div class="font-body text-xs leading-relaxed opacity-90">
                    Jadwal pementasan ini resmi dibatalkan dari sistem. Semua plotting personel dan kostum telah dibebaskan. Silakan hubungi admin jika ada kendala administrasi.
                </div>
            </div>
        </div>
        @elseif($booking->status === 'completed')
        <div class="flex items-start gap-4 p-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-900 shadow-sm">
            <i class="bi bi-trophy-fill text-2xl mt-0.5 text-emerald-600"></i>
            <div>
                <div class="font-headline font-bold text-base mb-1">Pementasan Selesai ✨</div>
                <div class="font-body text-xs leading-relaxed opacity-90">
                    Terima kasih telah mempercayakan pelestarian budaya tradisional bersama Sanggar Seni Cahaya Gumilang. Sukses selalu untuk event Anda!
                </div>
            </div>
        </div>
        @endif

        {{-- MODUL PEMBATALAN DINAMIS --}}
        @if(in_array($booking->status, ['pending', 'dp_paid', 'confirmed']))
            @php
                // Hitung sisa hari secara presisi berdasarkan JAM
                $eventDateStr = is_string($booking->event_date) ? $booking->event_date : $booking->event_date->format('Y-m-d');
                $eventStartStr = $booking->event_start ? \Carbon\Carbon::parse($booking->event_start)->format('H:i:s') : '00:00:00';
                $eventDateTime = \Carbon\Carbon::parse($eventDateStr . ' ' . $eventStartStr);
                
                $hoursBefore = \Carbon\Carbon::now()->diffInHours($eventDateTime, false);
                $daysBefore = (int) ceil($hoursBefore / 24);
                if ($daysBefore < 0) {
                    $daysBefore = 0;
                }

                // Ambil penalty tiers dari Database
                $rawTiers = \App\Models\SiteContent::where('key', 'penalty_tiers')->value('value');
                $penaltyTiers = $rawTiers ? json_decode($rawTiers, true) : [
                    ['days_from' => 14, 'percentage' => 10,  'label' => '≥ H-14'],
                    ['days_from' => 7,  'percentage' => 30,  'label' => 'H-7 s/d H-13'],
                    ['days_from' => 3,  'percentage' => 50,  'label' => 'H-3 s/d H-6'],
                    ['days_from' => 0,  'percentage' => 75,  'label' => '< H-3'],
                ];

                // Urutkan dari tertinggi ke terendah
                usort($penaltyTiers, fn($a, $b) => $b['days_from'] <=> $a['days_from']);

                // Tentukan persentase denda aktif
                $estPct = 75;
                $activeKey = null;
                foreach ($penaltyTiers as $key => $tier) {
                    if ($daysBefore >= $tier['days_from']) {
                        $estPct = $tier['percentage'];
                        $activeKey = $key;
                        break;
                    }
                }
                if ($activeKey === null) {
                    $activeKey = array_key_last($penaltyTiers);
                    $estPct = $penaltyTiers[$activeKey]['percentage'];
                }

                $estPenalty = $booking->total_price * ($estPct / 100);
                $estRefund = max(0, $booking->dp_amount - $estPenalty);
                
                // Cek status permohonan pembatalan saat ini
                $cancelReq = $booking->cancellation;
            @endphp
            
            <div class="bg-white border border-red-500/20 rounded-3xl p-6 sm:p-8 shadow-sm">
                <div class="font-label text-sm uppercase tracking-widest font-black text-red-600 flex items-center gap-2 mb-4 border-b border-red-500/10 pb-2">
                    <i class="bi bi-shield-fill-x text-lg"></i> Kebijakan Pembatalan & Pengembalian Dana
                </div>

                @if($cancelReq)
                    {{-- JIKA SUDAH MENGAJUKAN PEMBATALAN --}}
                    <div class="p-6 rounded-2xl border border-red-500/20 bg-red-500/5 mb-6">
                        <div class="flex items-center gap-3 mb-4">
                            @if($cancelReq->status === 'pending')
                                <span class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-700 font-label text-[0.65rem] font-bold uppercase tracking-wider">Menunggu Persetujuan Admin</span>
                            @elseif($cancelReq->status === 'processed')
                                <span class="px-3 py-1 rounded-full bg-red-600 text-white font-label text-[0.65rem] font-bold uppercase tracking-wider">Pembatalan Disetujui</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-gray-500 text-white font-label text-[0.65rem] font-bold uppercase tracking-wider">Pembatalan Ditolak</span>
                            @endif
                            <span class="font-body text-xs text-outline font-medium">Diajukan pada {{ \Carbon\Carbon::parse($cancelReq->created_at)->isoFormat('D MMMM Y') }}</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-body leading-relaxed mb-4">
                            <div>
                                <span class="block text-outline font-semibold mb-0.5">Alasan Pembatalan:</span>
                                <span class="text-on-surface italic">"{{ $cancelReq->reason }}"</span>
                            </div>
                            <div>
                                <span class="block text-outline font-semibold mb-0.5">Waktu Pengajuan (Jam):</span>
                                <span class="text-on-surface font-bold">H-{{ $cancelReq->days_before_event }} Hari (Dihitung Berdasarkan Jam)</span>
                            </div>
                        </div>

                        <div class="p-4 bg-white border border-red-500/10 rounded-xl grid grid-cols-1 sm:grid-cols-3 gap-4 font-headline text-center">
                            <div>
                                <div class="text-[0.6rem] font-label uppercase text-outline font-extrabold mb-1">Persentase Denda</div>
                                <div class="text-xl font-bold text-red-600">{{ number_format($cancelReq->penalty_percentage, 0) }}%</div>
                            </div>
                            <div>
                                <div class="text-[0.6rem] font-label uppercase text-outline font-extrabold mb-1">Nominal Potongan</div>
                                <div class="text-xl font-bold text-red-600">Rp {{ number_format($cancelReq->penalty_amount, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <div class="text-[0.6rem] font-label uppercase text-outline font-extrabold mb-1">Estimasi Refund</div>
                                <div class="text-xl font-bold text-green-600">Rp {{ number_format($cancelReq->refund_amount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- JIKA BELUM MENGAJUKAN PEMBATALAN --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <div class="font-body text-xs sm:text-sm text-on-surface-variant leading-relaxed mb-4">
                                Persentase denda dipotong dari total kontrak berdasarkan selisih waktu pembatalan terhadap jadwal acara (<strong>H-{{ $daysBefore }} Hari</strong>):
                            </div>
                            <ul class="font-body text-xs text-outline space-y-1.5 bg-surface-container-low rounded-xl p-4 border border-outline-variant/30">
                                @foreach($penaltyTiers as $key => $tier)
                                    @php
                                        $isThis = ($key === $activeKey);
                                    @endphp
                                    <li class="flex justify-between {{ $isThis ? 'text-red-700 font-black' : '' }}">
                                        <span>
                                            @if($isThis) <i class="bi bi-caret-right-fill"></i> @endif
                                            {{ !empty($tier['label']) ? $tier['label'] : '≥ H-' . $tier['days_from'] }}:
                                        </span>
                                        <span>Denda {{ number_format($tier['percentage'], 0) }}%</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        <div class="flex flex-col justify-center bg-red-500/5 border border-red-500/10 rounded-2xl p-5">
                            <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-4 text-center">Estimasi Finansial Pengembalian</div>
                            <div class="space-y-3 font-body text-xs">
                                <div class="flex justify-between">
                                    <span class="text-on-surface-variant font-medium">Nilai Kontrak Acara:</span>
                                    <span class="font-bold text-on-surface">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-red-700 font-semibold">
                                    <span>Denda Potongan ({{ number_format($estPct, 0) }}%):</span>
                                    <span>- Rp {{ number_format($estPenalty, 0, ',', '.') }}</span>
                                </div>
                                @if($booking->isDpVerified())
                                    <div class="flex justify-between text-green-700 pt-3 border-t border-red-500/15 font-black text-sm">
                                        <span>Estimasi Refund DP:</span>
                                        <span>Rp {{ number_format($estRefund, 0, ',', '.') }}</span>
                                    </div>
                                @else
                                    <div class="flex justify-between text-outline pt-3 border-t border-red-500/15 text-[0.7rem] italic leading-relaxed">
                                        <span>Commitment fee (DP) belum dikonfirmasi masuk sanggar, refund Rp 0.</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('klien.bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Apakah Anda benar-benar yakin ingin mengirimkan permohonan pembatalan pementasan ini?')">
                        @csrf
                        <div class="mb-4">
                            <label class="block font-label text-xs uppercase tracking-wider text-outline font-bold mb-2">Alasan Pembatalan Acara</label>
                            <textarea name="reason" rows="3" required placeholder="Tuliskan alasan pembatalan secara detail dan jujur..." 
                                      class="w-full bg-surface-container border border-outline-variant/40 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all resize-none"></textarea>
                        </div>
                        
                        <div class="mb-5">
                            <label class="flex items-start gap-2.5 cursor-pointer group">
                                <input type="checkbox" name="digital_acknowledgement" value="1" required class="mt-0.5 rounded border-outline-variant text-red-600 focus:ring-red-500">
                                <span class="font-body text-[0.7rem] text-on-surface-variant leading-relaxed group-hover:text-on-surface transition-colors">
                                    Saya menyetujui dan memahami sepenuhnya ketentuan denda pembatalan di atas. Saya menyatakan tanda tangan digital ini sah sesuai kebijakan administrasi Sanggar Seni Cahaya Gumilang.
                                </span>
                            </label>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="w-full md:w-auto flex justify-center items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-6 py-3.5 rounded-xl font-label text-xs font-bold uppercase tracking-widest transition-all shadow-md active:scale-95">
                                <i class="bi bi-x-circle-fill text-sm"></i> Kirim Pengajuan Pembatalan
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        @endif
    </div>

    {{-- KOLOM KANAN: Pembayaran (Disertai Penataan Agar Tidak Overlap) --}}
    <div class="w-full lg:w-96 shrink-0 relative lg:sticky lg:top-24">
        <div class="bg-white border border-outline-variant/20 rounded-3xl overflow-hidden shadow-sm">
            <div class="bg-primary text-white border-b border-outline-variant/10 p-5 font-label text-xs uppercase tracking-widest font-bold flex items-center gap-2">
                <i class="bi bi-credit-card-fill text-secondary"></i> Ringkasan Pembayaran
            </div>

            <div class="p-5 space-y-4 font-body text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-on-surface-variant font-medium">Harga Kontrak</span>
                    <span class="font-bold text-on-surface">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center p-3.5 rounded-xl bg-secondary/10 border border-secondary/20">
                    <span class="text-secondary font-bold">DP / Commitment Fee</span>
                    <span class="font-black text-secondary text-lg">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-on-surface-variant font-medium">Sisa Pelunasan</span>
                    <span class="font-bold text-on-surface">Rp {{ number_format(max(0, $booking->total_price - $booking->dp_amount), 0, ',', '.') }}</span>
                </div>
            </div>

            <hr class="border-outline-variant/10 m-0">

            {{-- PANEL PEMBAYARAN DAN BUKTI TRANSFER --}}
            @if($booking->status === 'pending' && !$booking->payment_proof)
                @php
                    $siteContents = \Illuminate\Support\Facades\Cache::remember(
                        'site_contents',
                        3600,
                        fn() => \App\Models\SiteContent::pluck('value', 'key')->toArray()
                    );
                    $adminPhone = $siteContents['admin_whatsapp'] ?? '6281234567890';
                    $bankName = $siteContents['bank_name'] ?? 'BCA';
                    $bankAccount = $siteContents['bank_account'] ?? '1234 5678 90 a/n Cahaya Gumilang';

                    $waMsg = urlencode(
                        "Halo kak, saya " . Auth::user()->name .
                        " ingin negosiasi harga untuk booking " .
                        ucwords(str_replace('_', ' ', $booking->event_type)) .
                        " tanggal " . \Carbon\Carbon::parse($booking->event_date)->isoFormat('D MMMM Y') .
                        " (BK-" . str_pad($booking->id, 3, '0', STR_PAD_LEFT) . ")." .
                        " Harga saat ini Rp " . number_format($booking->total_price, 0, ',', '.') . ". Bisa kita diskusikan?"
                    );
                    $waUrl = "https://wa.me/{$adminPhone}?text={$waMsg}";
                @endphp

                <div class="p-5 space-y-4">
                    <div class="font-label text-[0.65rem] text-center text-outline uppercase tracking-widest font-extrabold">Pilih Metode Tindak Lanjut:</div>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ $waUrl }}" target="_blank" 
                           class="flex flex-col items-center justify-center p-4 rounded-xl border border-secondary text-secondary hover:bg-secondary hover:text-primary transition-all duration-200 group text-center">
                            <i class="bi bi-whatsapp text-2xl mb-1.5 group-hover:scale-110 transition-transform"></i>
                            <div class="font-label font-bold text-[0.65rem] uppercase tracking-wider leading-tight">Negosiasi</div>
                        </a>
                        <button onclick="document.getElementById('payNowSection').classList.toggle('hidden')"
                                class="flex flex-col items-center justify-center p-4 rounded-xl border border-primary bg-primary text-white hover:bg-primary-container transition-all duration-200 group text-center">
                            <i class="bi bi-wallet2 text-2xl mb-1.5 group-hover:scale-110 transition-transform"></i>
                            <div class="font-label font-bold text-[0.65rem] uppercase tracking-wider leading-tight">Bayar DP</div>
                        </button>
                    </div>

                    {{-- Form Upload Bukti Transfer --}}
                    <div id="payNowSection" class="hidden animate-fade-up space-y-4 pt-2">
                        <div class="bg-surface-container-low rounded-2xl p-4 text-center border border-outline-variant/30">
                            <div class="font-label text-[0.6rem] text-outline uppercase tracking-widest font-black mb-1">Transfer DP ke Rekening:</div>
                            <div class="font-headline font-bold text-lg text-primary mb-0.5">🏦 {{ $bankName }}</div>
                            <div class="font-body text-xs text-on-surface-variant font-semibold">{{ $bankAccount }}</div>
                        </div>
                        <form action="{{ route('klien.bookings.upload_proof', $booking->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div id="uploadArea" onclick="document.getElementById('proofInput').click()" 
                                 class="border-2 border-dashed border-outline-variant/50 hover:border-primary rounded-2xl p-5 text-center cursor-pointer hover:bg-surface-container-low transition-all">
                                <i class="bi bi-cloud-arrow-up text-3xl text-outline mb-2 block"></i>
                                <div class="font-body font-bold text-xs text-on-surface mb-0.5">Klik untuk upload bukti bayar</div>
                                <div class="font-body text-[0.65rem] text-outline">Format JPG, PNG (Maks 5MB)</div>
                            </div>
                            <input type="file" id="proofInput" name="payment_proof" accept="image/*" required onchange="previewFile(this)" class="hidden">
                            
                            <div id="previewWrap" class="hidden relative">
                                <img id="previewImg" src="" alt="Preview" class="w-full rounded-2xl border border-outline-variant/30 shadow-sm">
                                <button type="button" onclick="document.getElementById('proofInput').click()" class="absolute top-2 right-2 w-8 h-8 rounded-lg bg-black/60 text-white flex items-center justify-center hover:bg-black/80 backdrop-blur-sm"><i class="bi bi-pencil-fill text-xs"></i></button>
                            </div>

                            <button type="submit" class="w-full flex justify-center items-center gap-2 bg-secondary hover:bg-secondary-container text-primary px-4 py-3 rounded-xl font-label text-xs font-bold uppercase tracking-widest transition-all">
                                <i class="bi bi-send-fill text-xs"></i> Kirim Bukti Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            @elseif($booking->status === 'pending' && $booking->payment_proof)
                <div class="p-8 text-center bg-orange-500/5">
                    <i class="bi bi-hourglass-split text-5xl text-orange-500 mb-3 block animate-pulse"></i>
                    <div class="font-headline font-bold text-base text-primary mb-1">Verifikasi Sedang Berjalan</div>
                    <div class="font-body text-xs text-on-surface-variant leading-relaxed">
                        Bukti pembayaran DP Anda telah diterima. Admin kami akan segera melakukan verifikasi mutasi bank untuk mengunci jadwal.
                    </div>
                </div>
            @elseif(in_array($booking->status, ['dp_paid', 'confirmed', 'paid_full']))
                <div class="p-5 border-b border-outline-variant/10 bg-secondary/5">
                    <div class="flex items-center gap-2 font-label text-xs uppercase tracking-widest font-black text-secondary mb-1">
                        <i class="bi bi-lock-fill text-sm"></i> Laba Pimpinan Terkunci
                    </div>
                    <div class="font-body text-[0.7rem] text-on-surface-variant leading-relaxed">
                        Jadwal pementasan, tarif dasar roster, serta profit tetap pimpinan telah di-lock oleh sistem.
                    </div>
                </div>

                @if($booking->status === 'paid_full')
                    <div class="p-8 text-center bg-green-500/10">
                        <i class="bi bi-shield-fill-check text-5xl text-green-600 mb-3 block"></i>
                        <div class="font-headline font-bold text-base text-primary mb-1">Tagihan Lunas 100%</div>
                        <div class="font-body text-xs text-on-surface-variant leading-relaxed">Seluruh tagihan untuk pementasan ini telah lunas sepenuhnya. Terima kasih!</div>
                    </div>
                @else
                    <div class="p-6 text-center bg-green-500/5 mb-2">
                        <i class="bi bi-check-circle-fill text-4xl text-green-500 mb-2 block"></i>
                        <div class="font-headline font-bold text-sm text-primary mb-1">DP Sukses Diterima ✓</div>
                        <div class="font-body text-[0.7rem] text-on-surface-variant leading-relaxed">Uang muka telah masuk pembukuan sanggar. Jadwal Anda dipastikan aman.</div>
                    </div>

                    @if(!$booking->full_payment_proof)
                        {{-- Upload Bukti Pelunasan --}}
                        <div class="p-5 border-t border-outline-variant/10">
                            <div class="font-label text-[0.65rem] text-center text-outline uppercase tracking-widest font-extrabold mb-4">Pelunasan Tagihan (Rp {{ number_format(max(0, $booking->total_price - $booking->dp_amount), 0, ',', '.') }})</div>
                            <form action="{{ route('klien.bookings.upload_full_proof', $booking->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div id="fullUploadArea" onclick="document.getElementById('fullProofInput').click()" 
                                     class="border-2 border-dashed border-outline-variant/50 hover:border-primary rounded-2xl p-5 text-center cursor-pointer hover:bg-surface-container-low transition-all">
                                    <i class="bi bi-cloud-arrow-up text-3xl text-outline mb-2 block"></i>
                                    <div class="font-body font-bold text-xs text-on-surface mb-0.5">Upload Bukti Pelunasan</div>
                                    <div class="font-body text-[0.65rem] text-outline">Format JPG, PNG (Maks 5MB)</div>
                                </div>
                                <input type="file" id="fullProofInput" name="full_payment_proof" accept="image/*" required onchange="previewFullFile(this)" class="hidden">
                                
                                <div id="fullPreviewWrap" class="hidden relative">
                                    <img id="fullPreviewImg" src="" alt="Preview" class="w-full rounded-2xl border border-outline-variant/30 shadow-sm">
                                    <button type="button" onclick="document.getElementById('fullProofInput').click()" class="absolute top-2 right-2 w-8 h-8 rounded-lg bg-black/60 text-white flex items-center justify-center hover:bg-black/80 backdrop-blur-sm"><i class="bi bi-pencil-fill text-xs"></i></button>
                                </div>

                                <button type="submit" class="w-full flex justify-center items-center gap-2 bg-primary text-white px-4 py-3 rounded-xl font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-all">
                                    <i class="bi bi-send-fill text-xs"></i> Kirim Bukti Pelunasan
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="p-6 text-center bg-blue-500/5">
                            <i class="bi bi-hourglass-split text-4xl text-blue-500 mb-3 block animate-pulse"></i>
                            <div class="font-headline font-bold text-sm text-primary mb-1">Verifikasi Pelunasan</div>
                            <div class="font-body text-xs text-on-surface-variant leading-relaxed">Bukti transfer pelunasan Anda sedang diperiksa oleh Pimpinan Sanggar.</div>
                        </div>
                    @endif
                @endif
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
function previewFile(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('previewWrap').classList.remove('hidden');
            document.getElementById('uploadArea').classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function previewFullFile(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('fullPreviewImg').src = e.target.result;
            document.getElementById('fullPreviewWrap').classList.remove('hidden');
            document.getElementById('fullUploadArea').classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const mapContainer = document.getElementById('bookingDetailMap');
    if (!mapContainer) return;

    const lat = parseFloat(mapContainer.getAttribute('data-latitude') || "{{ $booking->latitude }}");
    const lng = parseFloat(mapContainer.getAttribute('data-longitude') || "{{ $booking->longitude }}");
    const venue = "{{ $booking->venue }}" || 'Lokasi Pementasan';

    if (isNaN(lat) || isNaN(lng)) return;

    const map = L.map(mapContainer).setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        noWrap: true,
        maxZoom: 19
    }).addTo(map);

    L.marker([lat, lng]).addTo(map)
        .bindPopup('<strong>' + venue + '</strong><br>' + lat + ', ' + lng)
        .openPopup();
});
</script>
@endpush

@endsection
