@extends('layouts.klien')

@section('title', 'Detail Pesanan – ART-HUB Sanggar Cahaya Gumilang')

@section('content')

{{-- BACK NAV --}}
<div class="flex items-center gap-2 mb-6 font-label text-xs uppercase tracking-widest font-bold">
    <a href="{{ route('klien.dashboard') }}" class="text-on-surface-variant hover:text-secondary transition-colors flex items-center gap-1.5">
        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
    </a>
    <span class="text-outline-variant">/</span>
    <span class="text-outline">Detail Pesanan #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
</div>

@php
    $statusMap = [
        'pending'   => ['label' => 'Menunggu Konfirmasi',  'cls' => 'bg-orange-500/10 text-orange-600 border-orange-500/20',   'step' => 1],
        'dp_paid'   => ['label' => 'DP Terkonfirmasi',     'cls' => 'bg-blue-500/10 text-blue-600 border-blue-500/20',        'step' => 2],
        'confirmed' => ['label' => 'Jadwal Terkunci',      'cls' => 'bg-green-500/10 text-green-600 border-green-500/20', 'step' => 3],
        'paid_full' => ['label' => 'Pelunasan Lunas',      'cls' => 'bg-green-500/10 text-green-600 border-green-500/20',      'step' => 4],
        'completed' => ['label' => 'Pementasan Selesai',   'cls' => 'bg-surface-container-high text-on-surface-variant border-outline-variant/30',      'step' => 5],
        'cancelled' => ['label' => 'Dibatalkan',           'cls' => 'bg-red-500/10 text-red-600 border-red-500/20',    'step' => 0],
    ];
    $st   = $statusMap[$booking->status] ?? ['label' => strtoupper($booking->status), 'cls' => 'bg-surface-container border-outline-variant/30 text-outline', 'step' => 0];
    $step = $st['step'];
@endphp

{{-- ═══════ PROGRESS TRACKER ═══════ --}}
@if($booking->status !== 'cancelled')
<div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl p-6 sm:p-8 mb-8 shadow-[0_4px_12px_rgba(54,31,26,0.02)] overflow-x-auto">
    <div class="flex items-center min-w-[600px]">
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
            $circleColor = $isActive ? 'bg-secondary/10 border-secondary text-secondary shadow-[0_0_15px_rgba(252,212,0,0.2)] scale-110' : ($isDone ? 'bg-green-500/10 border-green-500 text-green-600' : 'bg-surface-container-low border-outline-variant/50 text-outline-variant');
            $labelColor = $isActive ? 'text-secondary' : ($isDone ? 'text-green-600' : 'text-outline-variant');
        @endphp
        
        <div class="flex flex-col items-center gap-3 w-20 relative z-10">
            <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center transition-all {{ $circleColor }}">
                <i class="bi {{ $ico }} text-lg"></i>
            </div>
            <div class="font-label text-[0.6rem] uppercase tracking-widest font-bold text-center whitespace-nowrap {{ $labelColor }}">{{ $lbl }}</div>
        </div>
        
        @if($n < 5)
            <div class="flex-1 h-0.5 mx-2 rounded-full transition-all {{ $step > $n ? 'bg-green-500' : 'bg-outline-variant/30' }}"></div>
        @endif
        @endforeach
    </div>
</div>
@endif

{{-- ═══════ MAIN CONTENT ═══════ --}}
<div class="flex flex-col lg:flex-row gap-6 items-start">

    {{-- KOLOM KIRI: Rincian Event --}}
    <div class="flex-grow w-full space-y-6">

        {{-- Header & Detail Acara --}}
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_4px_12px_rgba(54,31,26,0.02)] overflow-hidden">
            {{-- Title Bar --}}
            <div class="p-6 border-b border-outline-variant/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Kode Booking</div>
                    <div class="font-headline font-bold text-2xl text-secondary">BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border font-label text-xs font-bold uppercase tracking-wider {{ $st['cls'] }}">
                        <i class="bi bi-circle-fill text-[0.5rem]"></i> {{ $st['label'] }}
                    </span>
                </div>
            </div>
            
            {{-- Content --}}
            <div class="p-6">
                <div class="font-label text-xs uppercase tracking-widest text-primary font-bold flex items-center gap-2 mb-6">
                    <i class="bi bi-calendar-event"></i> Detail Acara
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Jenis Pementasan</div>
                        <div class="font-body font-bold text-on-surface">{{ ucwords(str_replace('_', ' ', $booking->event_type)) }}</div>
                    </div>
                    <div>
                        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Tanggal</div>
                        <div class="font-body font-bold text-on-surface">{{ \Carbon\Carbon::parse($booking->event_date)->isoFormat('dddd, D MMMM Y') }}</div>
                    </div>
                    <div>
                        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Waktu Mulai</div>
                        <div class="font-body font-bold text-on-surface">{{ \Carbon\Carbon::parse($booking->event_start)->format('H:i') }} WIB</div>
                    </div>
                    <div>
                        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Waktu Selesai</div>
                        <div class="font-body font-bold text-on-surface">{{ \Carbon\Carbon::parse($booking->event_end)->format('H:i') }} WIB</div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Lokasi Pementasan</div>
                        <div class="font-body font-bold text-on-surface mb-1">{{ $booking->venue }}</div>
                        @if($booking->venue_address)
                        <div class="font-body text-xs text-on-surface-variant">{{ $booking->venue_address }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Pesan Kontekstual per Status --}}
        @if($booking->status === 'pending')
        <div class="flex items-start gap-4 p-5 rounded-xl bg-orange-500/10 border border-orange-500/20 text-orange-700">
            <i class="bi bi-info-circle-fill text-2xl mt-0.5"></i>
            <div>
                <div class="font-headline font-bold text-lg mb-1">Pesanan Sedang Ditinjau</div>
                <div class="font-body text-sm opacity-90 leading-relaxed">Pimpinan sanggar akan menghubungi Anda via WhatsApp untuk negosiasi harga final sebelum Anda mentransfer DP.</div>
            </div>
        </div>
        @elseif($booking->status === 'cancelled')
        <div class="flex items-start gap-4 p-5 rounded-xl bg-red-500/10 border border-red-500/20 text-red-700">
            <i class="bi bi-x-circle-fill text-2xl mt-0.5"></i>
            <div>
                <div class="font-headline font-bold text-lg mb-1">Pesanan Dibatalkan</div>
                <div class="font-body text-sm opacity-90 leading-relaxed">Pesanan ini telah dibatalkan. Silakan hubungi sanggar jika ada pertanyaan lebih lanjut.</div>
            </div>
        </div>
        @elseif($booking->status === 'completed')
        <div class="flex items-start gap-4 p-5 rounded-xl bg-green-500/10 border border-green-500/20 text-green-700">
            <i class="bi bi-trophy-fill text-2xl mt-0.5"></i>
            <div>
                <div class="font-headline font-bold text-lg mb-1">Pementasan Selesai ✨</div>
                <div class="font-body text-sm opacity-90 leading-relaxed">Terima kasih telah mempercayakan pementasan seni budaya pada Sanggar Cahaya Gumilang!</div>
            </div>
        </div>
        @elseif(in_array($booking->status, ['dp_paid', 'confirmed']))
        <div class="flex items-start gap-4 p-5 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-700">
            <i class="bi bi-calendar2-check-fill text-2xl mt-0.5"></i>
            <div>
                <div class="font-headline font-bold text-lg mb-1">Jadwal Sudah Terkunci! 🎉</div>
                <div class="font-body text-sm opacity-90 leading-relaxed">Tim penari dan pemusik kami sedang mempersiapkan pementasan terbaik untuk Anda.</div>
            </div>
        </div>
        @endif
    </div>

    {{-- KOLOM KANAN: Pembayaran --}}
    <div class="w-full lg:w-96 flex-shrink-0">
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl overflow-hidden sticky top-24 shadow-[0_8px_24px_rgba(54,31,26,0.04)]">
            <div class="bg-surface-container-low border-b border-outline-variant/30 p-5 font-label text-xs uppercase tracking-widest font-bold flex items-center gap-2 text-primary">
                <i class="bi bi-receipt-cutoff"></i> Ringkasan Pembayaran
            </div>

            <div class="p-5 space-y-4 font-body">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-on-surface-variant font-medium">Harga Kontrak</span>
                    <span class="font-bold text-on-surface">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm p-3 rounded-lg bg-secondary/10 border border-secondary/20">
                    <span class="text-secondary-container font-bold">DP / Commitment Fee</span>
                    <span class="font-bold text-secondary text-lg">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-on-surface-variant font-medium">Sisa Pelunasan</span>
                    <span class="font-bold text-on-surface">Rp {{ number_format($booking->total_price - $booking->dp_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <hr class="border-outline-variant/20 m-0">

            {{-- Upload Bukti / Status Panel --}}
            @if($booking->status === 'pending' && !$booking->payment_proof)

                {{-- DUA PILIHAN: Langsung Bayar atau Nego via WA --}}
                @php
                    $adminPhone = '6281234567890'; // Ganti dengan nomor WA admin sanggar
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

                <div class="p-5">
                    <div class="font-label text-[0.65rem] text-center text-outline uppercase tracking-widest font-bold mb-4">Pilih Langkah Selanjutnya:</div>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <a href="{{ $waUrl }}" target="_blank" class="flex flex-col items-center justify-center p-4 rounded-xl border-2 border-secondary text-secondary hover:bg-secondary hover:text-primary transition-all group">
                            <i class="bi bi-whatsapp text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                            <div class="font-body font-bold text-[0.7rem] leading-tight text-center">Negosiasi<br>Harga</div>
                        </a>
                        <button onclick="document.getElementById('payNowSection').classList.toggle('hidden')"
                                class="flex flex-col items-center justify-center p-4 rounded-xl border-2 border-primary bg-primary text-white hover:bg-primary-container transition-all group">
                            <i class="bi bi-credit-card-2-front text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                            <div class="font-body font-bold text-[0.7rem] leading-tight text-center">Bayar<br>Langsung</div>
                        </button>
                    </div>

                    {{-- Form Upload (tersembunyi) --}}
                    <div id="payNowSection" class="hidden animate-fade-up">
                        <div class="bg-surface-container rounded-lg p-4 mb-4 text-center border border-outline-variant/30">
                            <div class="font-label text-[0.65rem] text-outline uppercase tracking-widest font-bold mb-1">Transfer DP ke Rekening:</div>
                            <div class="font-headline font-bold text-xl text-primary mb-0.5">🏦 BCA <span class="text-secondary">1234 5678 90</span></div>
                            <div class="font-body text-xs text-on-surface-variant font-medium">a/n Cahaya Gumilang</div>
                        </div>
                        <form action="{{ route('klien.bookings.upload_proof', $booking->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div id="uploadArea" onclick="document.getElementById('proofInput').click()" class="border-2 border-dashed border-outline-variant/50 rounded-xl p-6 text-center cursor-pointer hover:border-primary hover:bg-surface-container-low transition-all mb-4">
                                <i class="bi bi-cloud-arrow-up text-3xl text-outline mb-2 block"></i>
                                <div class="font-body font-bold text-sm text-on-surface mb-1">Klik untuk upload bukti</div>
                                <div class="font-body text-[0.65rem] text-outline">JPG, PNG – Maks. 5MB</div>
                            </div>
                            <input type="file" id="proofInput" name="payment_proof" accept="image/*" required
                                   onchange="previewFile(this)" class="hidden">
                            
                            <div id="previewWrap" class="hidden mb-4 relative">
                                <img id="previewImg" src="" alt="Preview" class="w-full rounded-xl border border-outline-variant/30 shadow-sm">
                                <button type="button" onclick="document.getElementById('proofInput').click()" class="absolute top-2 right-2 w-8 h-8 rounded-lg bg-black/50 text-white flex items-center justify-center hover:bg-black/70 backdrop-blur-sm"><i class="bi bi-pencil"></i></button>
                            </div>

                            <button type="submit" class="w-full flex justify-center items-center gap-2 bg-secondary text-primary px-4 py-3 rounded-xl font-label text-xs font-bold uppercase tracking-widest hover:bg-secondary-container transition-all">
                                <i class="bi bi-send-fill"></i> Kirim Bukti DP
                            </button>
                        </form>
                    </div>
                </div>

            @elseif($booking->status === 'pending' && $booking->payment_proof)
                <div class="p-8 text-center bg-orange-500/5">
                    <i class="bi bi-hourglass-split text-5xl text-orange-500 mb-4 block"></i>
                    <div class="font-headline font-bold text-lg text-primary mb-2">Bukti Terkirim</div>
                    <div class="font-body text-sm text-on-surface-variant leading-relaxed">Admin sedang memverifikasi transfer Anda. Biasanya diproses dalam 1×24 jam.</div>
                </div>

            @elseif(in_array($booking->status, ['dp_paid', 'confirmed', 'paid_full']))
                <div class="p-5 border-b border-outline-variant/20 bg-secondary/5">
                    <div class="flex items-center gap-2 font-label text-xs uppercase tracking-widest font-bold text-secondary-container mb-2">
                        <i class="bi bi-lock-fill text-sm"></i> Laba Pimpinan Terkunci
                    </div>
                    <div class="font-body text-xs text-on-surface-variant leading-relaxed">
                        Harga dan detail operasional telah ditetapkan. Pimpinan sanggar telah mengunci kesepakatan ini.
                    </div>
                </div>

                <div class="p-8 text-center bg-green-500/5">
                    <i class="bi bi-check-circle-fill text-5xl text-green-500 mb-4 block"></i>
                    <div class="font-headline font-bold text-lg text-primary mb-2">DP Terkonfirmasi ✓</div>
                    <div class="font-body text-sm text-on-surface-variant leading-relaxed">Pembayaran DP sudah diterima sanggar. Tanggal Anda telah terkunci dengan aman!</div>
                </div>

            @elseif($booking->status === 'completed')
                <div class="p-8 text-center bg-secondary/10">
                    <i class="bi bi-star-fill text-5xl text-secondary mb-4 block"></i>
                    <div class="font-headline font-bold text-lg text-primary mb-2">Pementasan Sukses!</div>
                    <div class="font-body text-sm text-on-surface-variant leading-relaxed">Semua pembayaran lunas. Terima kasih sudah mengundang kami.</div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewFile(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('previewWrap').classList.remove('hidden');
            document.getElementById('uploadArea').classList.add('hidden');
            document.getElementById('payNowSection').classList.add('animate-fade-up');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@endsection
