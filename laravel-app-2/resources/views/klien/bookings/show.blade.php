@extends('layouts.klien')

@section('title', 'Detail Pesanan – ART-HUB Sanggar Cahaya Gumilang')

@section('content')

{{-- BACK NAV --}}
<div class="k-breadcrumb animate-fade-up">
    <a href="{{ route('klien.dashboard') }}" class="k-back-link">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
    </a>
    <span class="k-bc-sep">/</span>
    <span class="k-bc-current">Detail Pesanan #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
</div>

@php
    $statusMap = [
        'pending'   => ['label' => 'Menunggu Konfirmasi',  'cls' => 'status-pending',   'step' => 1],
        'dp_paid'   => ['label' => 'DP Terkonfirmasi',     'cls' => 'status-dp',        'step' => 2],
        'confirmed' => ['label' => 'Jadwal Terkunci',      'cls' => 'status-confirmed', 'step' => 3],
        'paid_full' => ['label' => 'Pelunasan Lunas',      'cls' => 'status-paid',      'step' => 4],
        'completed' => ['label' => 'Pementasan Selesai',   'cls' => 'status-done',      'step' => 5],
        'cancelled' => ['label' => 'Dibatalkan',           'cls' => 'status-cancel',    'step' => 0],
    ];
    $st   = $statusMap[$booking->status] ?? ['label' => $booking->status, 'cls' => '', 'step' => 0];
    $step = $st['step'];
@endphp

{{-- ═══════ PROGRESS TRACKER ═══════ --}}
@if($booking->status !== 'cancelled')
<div class="k-tracker animate-fade-up" style="animation-delay:0.06s;">
    @foreach([
        [1, 'bi-file-earmark-text', 'Pengajuan'],
        [2, 'bi-wallet2',           'DP Masuk'],
        [3, 'bi-calendar2-check',   'Terkunci'],
        [4, 'bi-check2-all',        'Lunas'],
        [5, 'bi-trophy',            'Selesai'],
    ] as [$n, $ico, $lbl])
    <div class="k-tracker-step {{ $step >= $n ? 'done' : '' }} {{ $step === $n ? 'active' : '' }}">
        <div class="k-step-circle"><i class="bi {{ $ico }}"></i></div>
        <div class="k-step-label">{{ $lbl }}</div>
    </div>
    @if($n < 5)<div class="k-tracker-line {{ $step > $n ? 'done' : '' }}"></div>@endif
    @endforeach
</div>
@endif

{{-- ═══════ MAIN CONTENT ═══════ --}}
<div class="k-detail-grid animate-fade-up" style="animation-delay:0.12s;">

    {{-- KOLOM KIRI: Rincian Event --}}
    <div class="k-detail-main">

        {{-- Header --}}
        <div class="k-card mb-4">
            <div class="k-card-header">
                <div>
                    <div class="k-card-eyebrow">Kode Booking</div>
                    <div class="k-booking-code">BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div class="kbooking-status {{ $st['cls'] }}">{{ $st['label'] }}</div>
            </div>
        </div>

        {{-- Detail Acara --}}
        <div class="k-card mb-4">
            <div class="k-card-title"><i class="bi bi-calendar-event me-2"></i>Detail Acara</div>
            <div class="k-detail-grid-2">
                <div class="k-detail-item">
                    <div class="k-di-label">Jenis Pementasan</div>
                    <div class="k-di-value">{{ ucwords(str_replace('_', ' ', $booking->event_type)) }}</div>
                </div>
                <div class="k-detail-item">
                    <div class="k-di-label">Tanggal</div>
                    <div class="k-di-value">{{ \Carbon\Carbon::parse($booking->event_date)->isoFormat('dddd, D MMMM Y') }}</div>
                </div>
                <div class="k-detail-item">
                    <div class="k-di-label">Waktu Mulai</div>
                    <div class="k-di-value">{{ \Carbon\Carbon::parse($booking->event_start)->format('H:i') }} WIB</div>
                </div>
                <div class="k-detail-item">
                    <div class="k-di-label">Waktu Selesai</div>
                    <div class="k-di-value">{{ \Carbon\Carbon::parse($booking->event_end)->format('H:i') }} WIB</div>
                </div>
                <div class="k-detail-item k-span-2">
                    <div class="k-di-label">Lokasi Pementasan</div>
                    <div class="k-di-value">{{ $booking->venue }}</div>
                    @if($booking->venue_address)
                    <div class="k-di-sub">{{ $booking->venue_address }}</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Pesan Kontekstual per Status --}}
        @if($booking->status === 'pending')
        <div class="k-alert-banner k-alert-warning">
            <i class="bi bi-info-circle-fill"></i>
            <div>
                <div class="k-alert-title">Pesanan Sedang Ditinjau</div>
                <div class="k-alert-body">Pimpinan sanggar akan menghubungi Anda via WhatsApp untuk negosiasi harga final sebelum Anda mentransfer DP.</div>
            </div>
        </div>
        @elseif($booking->status === 'cancelled')
        <div class="k-alert-banner k-alert-danger">
            <i class="bi bi-x-circle-fill"></i>
            <div>
                <div class="k-alert-title">Pesanan Dibatalkan</div>
                <div class="k-alert-body">Pesanan ini telah dibatalkan. Silakan hubungi sanggar jika ada pertanyaan.</div>
            </div>
        </div>
        @elseif($booking->status === 'completed')
        <div class="k-alert-banner k-alert-success">
            <i class="bi bi-trophy-fill"></i>
            <div>
                <div class="k-alert-title">Pementasan Selesai ✨</div>
                <div class="k-alert-body">Terima kasih telah mempercayakan pementasan seni budaya pada Sanggar Cahaya Gumilang!</div>
            </div>
        </div>
        @elseif(in_array($booking->status, ['dp_paid', 'confirmed']))
        <div class="k-alert-banner k-alert-info">
            <i class="bi bi-calendar2-check-fill"></i>
            <div>
                <div class="k-alert-title">Jadwal Sudah Terkunci! 🎉</div>
                <div class="k-alert-body">Tim penari dan pemusik kami sedang mempersiapkan pementasan terbaik untuk Anda.</div>
            </div>
        </div>
        @endif
    </div>

    {{-- KOLOM KANAN: Pembayaran --}}
    <div class="k-detail-aside">
        <div class="k-payment-card">
            <div class="k-payment-header">
                <i class="bi bi-receipt-cutoff me-2"></i>Ringkasan Pembayaran
            </div>

            <div class="k-payment-row">
                <span>Harga Kontrak</span>
                <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="k-payment-row k-payment-dp">
                <span>DP / Commitment Fee (50%)</span>
                <span class="k-dp-val">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
            </div>
            <div class="k-payment-row k-payment-sisa">
                <span>Sisa Pelunasan</span>
                <span>Rp {{ number_format($booking->total_price - $booking->dp_amount, 0, ',', '.') }}</span>
            </div>

            <div class="k-payment-divider"></div>

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

                <div class="k-choice-section">
                    <div class="k-choice-label">Pilih Langkah Selanjutnya:</div>
                    <div class="k-choice-row">
                        <a href="{{ $waUrl }}" target="_blank" class="k-choice-btn k-choice-wa">
                            <i class="bi bi-whatsapp"></i>
                            <div>
                                <div class="k-cb-title">Negosiasi Harga</div>
                                <div class="k-cb-sub">Chat Admin via WA</div>
                            </div>
                        </a>
                        <button onclick="document.getElementById('payNowSection').classList.toggle('d-none')"
                                class="k-choice-btn k-choice-pay">
                            <i class="bi bi-credit-card-2-front"></i>
                            <div>
                                <div class="k-cb-title">Bayar Langsung</div>
                                <div class="k-cb-sub">Upload Bukti DP</div>
                            </div>
                        </button>
                    </div>
                </div>

                {{-- Form Upload (tersembunyi dulu, muncul klik Bayar Langsung) --}}
                <div id="payNowSection" class="d-none">
                    <div class="k-rekening-info" style="margin-top:14px;">
                        <div class="k-rek-label">Transfer DP ke Rekening:</div>
                        <div class="k-rek-bank">🏦 BCA <span>1234 5678 90</span></div>
                        <div class="k-rek-name">a/n Cahaya Gumilang</div>
                        <div style="margin-top:6px; font-size:0.82rem; color:#d4af37; font-weight:700;">
                            DP: Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}
                        </div>
                    </div>
                    <form action="{{ route('klien.bookings.upload_proof', $booking->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="k-upload-area" id="uploadArea" onclick="document.getElementById('proofInput').click()">
                            <i class="bi bi-cloud-arrow-up k-upload-icon"></i>
                            <div class="k-upload-text">Klik untuk upload bukti transfer</div>
                            <div class="k-upload-sub">JPG, PNG – Maks. 5MB</div>
                        </div>
                        <input type="file" id="proofInput" name="payment_proof" accept="image/*" required
                               onchange="previewFile(this)" style="display:none;">
                        <div id="previewWrap" style="display:none; margin-top:10px;">
                            <img id="previewImg" src="" alt="Preview" style="width:100%; border-radius:8px; border:1px solid #2a2a2a;">
                        </div>
                        <button type="submit" class="k-btn-upload w-100">
                            <i class="bi bi-cloud-arrow-up-fill me-2"></i>Kirim Bukti Pembayaran
                        </button>
                    </form>
                </div>


            @elseif($booking->status === 'pending' && $booking->payment_proof)
                <div class="k-proof-sent">
                    <i class="bi bi-hourglass-split k-ps-icon"></i>
                    <div class="k-ps-title">Bukti Terkirim</div>
                    <div class="k-ps-sub">Admin sedang memverifikasi transfer Anda. Biasanya 1×24 jam.</div>
                </div>

            @elseif(in_array($booking->status, ['dp_paid', 'confirmed', 'paid_full']))
                <div class="k-proof-done">
                    <i class="bi bi-lock-fill k-pd-icon"></i>
                    <div class="k-pd-title">DP Terkonfirmasi ✓</div>
                    <div class="k-pd-sub">Pembayaran DP sudah diterima sanggar. Tanggal Anda terkunci!</div>
                </div>

            @elseif($booking->status === 'completed')
                <div class="k-proof-star">
                    <i class="bi bi-star-fill k-pstar-icon"></i>
                    <div class="k-pstar-title">Pementasan Sukses!</div>
                    <div class="k-pstar-sub">Semua pembayaran lunas. Terima kasih sudah bersama kami.</div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* ═══ BREADCRUMB ═══ */
.k-breadcrumb { display:flex; align-items:center; gap:8px; margin-bottom:24px; font-size:0.82rem; color:#666; }
.k-back-link { color:#888; text-decoration:none; display:flex; align-items:center; transition:color 0.2s; }
.k-back-link:hover { color:#d4af37; }
.k-bc-sep { color:#3a3a3a; }
.k-bc-current { color:#aaa; }

/* ═══ TRACKER ═══ */
.k-tracker { display:flex; align-items:center; margin-bottom:28px; background:#0d0d0d; border:1px solid #1e1e1e; border-radius:14px; padding:18px 22px; }
.k-tracker-step { display:flex; flex-direction:column; align-items:center; gap:6px; min-width:60px; }
.k-step-circle {
    width:36px; height:36px; border-radius:50%;
    background:#1a1a1a; border:2px solid #2a2a2a;
    display:flex; align-items:center; justify-content:center;
    font-size:0.85rem; color:#444; transition:all 0.3s;
}
.k-tracker-step.done .k-step-circle  { background:#1a3a20; border-color:#2d5a2d; color:#34d399; }
.k-tracker-step.active .k-step-circle{ background:#2a2200; border-color:#d4af37;  color:#d4af37; box-shadow: 0 0 10px rgba(212,175,55,0.3); }
.k-step-label { font-size:0.62rem; color:#555; text-align:center; white-space:nowrap; }
.k-tracker-step.done  .k-step-label { color:#4a7a4a; }
.k-tracker-step.active .k-step-label { color:#c5a059; }
.k-tracker-line { flex:1; height:2px; background:#1e1e1e; margin:0 4px; border-radius:2px; transition:background 0.3s; }
.k-tracker-line.done { background:linear-gradient(90deg, #2d5a2d, #2d5a2d); }

/* ═══ CARD BASE ═══ */
.k-card { background:#0d0d0d; border:1px solid #1e1e1e; border-radius:14px; padding:20px 22px; }
.k-card-header { display:flex; justify-content:space-between; align-items:center; }
.k-card-eyebrow { font-size:0.68rem; color:#555; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px; }
.k-booking-code { font-family:'Courier New', monospace; font-size:1.2rem; font-weight:700; color:#d4af37; }
.k-card-title { font-size:0.85rem; font-weight:700; color:#888; margin-bottom:16px; text-transform:uppercase; letter-spacing:0.06em; }

/* ═══ DETAIL GRID ═══ */
.k-detail-grid { display:grid; grid-template-columns: 1fr 380px; gap:18px; }
.k-detail-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.k-span-2 { grid-column: span 2; }
.k-di-label { font-size:0.68rem; color:#555; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:4px; }
.k-di-value { font-size:0.92rem; font-weight:600; color:#ddd; }
.k-di-sub { font-size:0.75rem; color:#666; margin-top:2px; }

/* ═══ ALERTS ═══ */
.k-alert-banner { display:flex; align-items:flex-start; gap:12px; border-radius:12px; padding:16px 18px; font-size:0.82rem; }
.k-alert-warning { background:#2a1e00; border:1px solid #4a3800; color:#d4a017; }
.k-alert-danger  { background:#2a0a0a; border:1px solid #5a1a1a; color:#ef4444; }
.k-alert-success { background:#0a2a10; border:1px solid #1a5a20; color:#34d399; }
.k-alert-info    { background:#0a1e2a; border:1px solid #1a3a5a; color:#60a5fa; }
.k-alert-banner i { font-size:1.1rem; flex-shrink:0; margin-top:1px; }
.k-alert-title { font-weight:700; margin-bottom:3px; }
.k-alert-body  { opacity:0.85; line-height:1.5; }

/* ═══ STATUS BADGES ═══ */
.kbooking-status { display:inline-flex; align-items:center; font-size:0.75rem; font-weight:600; border-radius:6px; padding:5px 12px; }
.status-pending  { background:#2a2000; color:#fbbf24; }
.status-dp       { background:#0a1e2e; color:#60a5fa; }
.status-confirmed{ background:#0a2e1a; color:#34d399; }
.status-paid     { background:#1a2e0a; color:#86efac; }
.status-done     { background:#1a2200; color:#a3e635; }
.status-cancel   { background:#2a0a0a; color:#f87171; }

/* ═══ PAYMENT ASIDE ═══ */
.k-payment-card { background:#080808; border:1px solid #1e1e1e; border-radius:14px; overflow:hidden; position:sticky; top:90px; }
.k-payment-header { background:#0d0d0d; border-bottom:1px solid #1e1e1e; padding:14px 18px; font-size:0.82rem; font-weight:700; color:#888; display:flex; align-items:center; }
.k-payment-row { display:flex; justify-content:space-between; align-items:center; padding:12px 18px; font-size:0.82rem; color:#888; border-bottom:1px solid #111; }
.k-payment-dp { color:#ccc; background:#0a0a0a; }
.k-dp-val { color:#d4af37; font-weight:700; }
.k-payment-sisa { color:#555; font-size:0.78rem; }
.k-payment-divider { height:1px; background:linear-gradient(90deg, transparent, #2a2a2a, transparent); margin:0; }

/* Choice Section (WA / Pay) */
.k-choice-section { padding:18px 18px 0; }
.k-choice-label { font-size:0.68rem; color:#555; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:10px; text-align:center; }
.k-choice-row { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.k-choice-btn {
    display:flex; flex-direction:column; align-items:center; text-align:center;
    padding:16px 12px; border-radius:12px; text-decoration:none;
    border:1px solid transparent; transition:all 0.2s; cursor:pointer; background:none;
}
.k-choice-btn i { font-size:1.8rem; margin-bottom:8px; }
.k-cb-title { font-size:0.82rem; font-weight:700; margin-bottom:2px; }
.k-cb-sub { font-size:0.65rem; opacity:0.8; }
.k-choice-wa { background:#0d2217; border-color:#1a422d; color:#4ade80; }
.k-choice-wa:hover { background:#143122; border-color:#22c55e; transform:translateY(-2px); color:#4ade80; }
.k-choice-pay { background:#2a2200; border-color:#4a3800; color:#fbbf24; }
.k-choice-pay:hover { background:#3a2f00; border-color:#d4af37; transform:translateY(-2px); color:#fbbf24; }

/* Rekening */
.k-rekening-info { padding:16px 18px; border-bottom:1px solid #111; }
.k-rek-label { font-size:0.68rem; color:#555; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px; }
.k-rek-bank { font-size:1rem; font-weight:700; color:#fff; margin-bottom:2px; }
.k-rek-bank span { color:#d4af37; }
.k-rek-name { font-size:0.78rem; color:#666; }

/* Upload */
.k-upload-area {
    margin:16px 18px 0; border:2px dashed #2a2a2a; border-radius:10px;
    padding:28px 20px; text-align:center; cursor:pointer;
    transition:border-color 0.2s, background 0.2s;
}
.k-upload-area:hover { border-color:#d4af37; background:rgba(212,175,55,0.04); }
.k-upload-icon { font-size:2rem; color:#3a3a3a; display:block; margin-bottom:8px; }
.k-upload-text { font-size:0.82rem; color:#777; font-weight:600; }
.k-upload-sub  { font-size:0.7rem; color:#444; margin-top:3px; }
.k-btn-upload {
    display:block; margin:12px 18px 18px; background:linear-gradient(135deg,#e6c25a,#b48b25);
    color:#000; font-weight:700; border:none; border-radius:10px;
    padding:13px; font-size:0.85rem; cursor:pointer; transition:all 0.2s;
}
.k-btn-upload:hover { background:linear-gradient(135deg,#f7d165,#c59929); transform:translateY(-1px); }

/* Proof States */
.k-proof-sent, .k-proof-done, .k-proof-star { text-align:center; padding:30px 18px; }
.k-ps-icon  { font-size:2.5rem; color:#fbbf24; display:block; margin-bottom:10px; }
.k-pd-icon  { font-size:2.5rem; color:#34d399; display:block; margin-bottom:10px; }
.k-pstar-icon { font-size:2.5rem; color:#d4af37; display:block; margin-bottom:10px; }
.k-ps-title, .k-pd-title, .k-pstar-title { font-weight:700; color:#ddd; margin-bottom:6px; }
.k-ps-sub, .k-pd-sub, .k-pstar-sub { font-size:0.78rem; color:#666; line-height:1.5; }

@media (max-width:900px) {
    .k-detail-grid { grid-template-columns:1fr; }
    .k-tracker { overflow-x:auto; }
    .k-detail-grid-2 { grid-template-columns:1fr 1fr; }
}
@media (max-width:500px) {
    .k-detail-grid-2 { grid-template-columns:1fr; }
    .k-span-2 { grid-column:span 1; }
}
</style>

@push('scripts')
<script>
function previewFile(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('previewWrap').style.display = 'block';
            document.getElementById('uploadArea').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@endsection
