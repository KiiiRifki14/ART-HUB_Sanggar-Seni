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
                <div class="fp-card m-3 mt-4">
                    <div class="d-flex align-items-center mb-2" style="color: var(--klien-gold); font-weight: 700;">
                        <i class="bi bi-lock-fill fs-5 me-2"></i>Laba Pimpinan Terkunci
                    </div>
                    <div style="font-size: 0.8rem; color: var(--klien-text-muted);">
                        Harga dan detail operasional telah ditetapkan. Pimpinan sanggar telah mengunci kesepakatan ini.
                    </div>
                </div>

                <div class="k-proof-done">
                    <i class="bi bi-check-circle-fill k-pd-icon"></i>
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
.k-tracker { display:flex; align-items:center; margin-bottom:28px; background:var(--klien-card-bg); border:1px solid var(--klien-border); border-radius:14px; padding:18px 22px; box-shadow: 0 1px 6px rgba(128,0,0,0.06); }
.k-tracker-step { display:flex; flex-direction:column; align-items:center; gap:6px; min-width:60px; }
.k-step-circle {
    width:36px; height:36px; border-radius:50%;
    background:#FDFBF7; border:2px solid var(--klien-border);
    display:flex; align-items:center; justify-content:center;
    font-size:0.85rem; color:var(--klien-text-muted); transition:all 0.3s;
}
.k-tracker-step.done .k-step-circle  { background:rgba(22,163,74,0.1); border-color:#16a34a; color:#16a34a; }
.k-tracker-step.active .k-step-circle{ background:rgba(212,175,55,0.1); border-color:var(--klien-gold);  color:var(--klien-gold); box-shadow: 0 0 10px rgba(212,175,55,0.2); }
.k-step-label { font-size:0.62rem; color:var(--klien-text-muted); text-align:center; white-space:nowrap; font-weight:600; }
.k-tracker-step.done  .k-step-label { color:#16a34a; }
.k-tracker-step.active .k-step-label { color:var(--klien-gold); }
.k-tracker-line { flex:1; height:2px; background:var(--klien-border); margin:0 4px; border-radius:2px; transition:background 0.3s; }
.k-tracker-line.done { background:linear-gradient(90deg, #16a34a, #16a34a); }

/* ═══ CARD BASE ═══ */
.k-card { background:var(--klien-card-bg); border:1px solid var(--klien-border); border-radius:14px; padding:20px 22px; box-shadow: 0 1px 6px rgba(128,0,0,0.06); }
.k-card-header { display:flex; justify-content:space-between; align-items:center; }
.k-card-eyebrow { font-size:0.68rem; color:var(--klien-text-muted); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px; font-weight:600; }
.k-booking-code { font-family:'Courier New', monospace; font-size:1.2rem; font-weight:700; color:var(--klien-gold); }
.k-card-title { font-size:0.85rem; font-weight:700; color:var(--klien-text-muted); margin-bottom:16px; text-transform:uppercase; letter-spacing:0.06em; }

/* ═══ DETAIL GRID ═══ */
.k-detail-grid { display:grid; grid-template-columns: 1fr 380px; gap:18px; }
.k-detail-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.k-span-2 { grid-column: span 2; }
.k-di-label { font-size:0.68rem; color:var(--klien-text-muted); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:4px; font-weight:600; }
.k-di-value { font-size:0.92rem; font-weight:700; color:var(--klien-text); }
.k-di-sub { font-size:0.75rem; color:var(--klien-text-muted); margin-top:2px; }

/* ═══ ALERTS ═══ */
.k-alert-banner { display:flex; align-items:flex-start; gap:12px; border-radius:12px; padding:16px 18px; font-size:0.82rem; }
.k-alert-warning { background:rgba(217,119,6,0.1); border:1px solid rgba(217,119,6,0.3); color:#d97706; }
.k-alert-danger  { background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); color:#dc2626; }
.k-alert-success { background:rgba(22,163,74,0.1); border:1px solid rgba(22,163,74,0.3); color:#16a34a; }
.k-alert-info    { background:rgba(37,99,235,0.1); border:1px solid rgba(37,99,235,0.3); color:#2563eb; }
.k-alert-banner i { font-size:1.1rem; flex-shrink:0; margin-top:1px; }
.k-alert-title { font-weight:700; margin-bottom:3px; }
.k-alert-body  { opacity:0.85; line-height:1.5; }

/* ═══ STATUS BADGES ═══ */
.kbooking-status { display:inline-flex; align-items:center; font-size:0.75rem; font-weight:600; border-radius:6px; padding:5px 12px; }
.status-pending  { background:rgba(217,119,6,0.1); color:#d97706; }
.status-dp       { background:rgba(37,99,235,0.1); color:#2563eb; }
.status-confirmed{ background:rgba(16,185,129,0.1); color:#059669; }
.status-paid     { background:rgba(34,197,94,0.1); color:#16a34a; }
.status-done     { background:rgba(21,128,61,0.1); color:#15803d; }
.status-cancel   { background:rgba(239,68,68,0.1); color:#dc2626; }

/* ═══ PAYMENT ASIDE ═══ */
.k-payment-card { background:var(--klien-card-bg); border:1px solid var(--klien-border); border-radius:14px; overflow:hidden; position:sticky; top:90px; box-shadow: 0 1px 6px rgba(128,0,0,0.06); }
.k-payment-header { background:#FDFBF7; border-bottom:1px solid var(--klien-border); padding:14px 18px; font-size:0.85rem; font-weight:700; color:var(--klien-text); display:flex; align-items:center; }
.k-payment-row { display:flex; justify-content:space-between; align-items:center; padding:12px 18px; font-size:0.85rem; color:var(--klien-text); border-bottom:1px solid var(--klien-border); font-weight:500; }
.k-payment-dp { background:rgba(212,175,55,0.05); }
.k-dp-val { color:var(--klien-gold); font-weight:700; }
.k-payment-sisa { font-size:0.8rem; }
.k-payment-divider { height:1px; background:linear-gradient(90deg, transparent, var(--klien-border), transparent); margin:0; }

/* Choice Section (WA / Pay) */
.k-choice-section { padding:18px 18px 0; }
.k-choice-label { font-size:0.68rem; color:var(--klien-text-muted); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:10px; text-align:center; font-weight:700; }
.k-choice-row { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.k-choice-btn {
    display:flex; flex-direction:column; align-items:center; text-align:center;
    padding:16px 12px; border-radius:12px; text-decoration:none;
    border:1px solid transparent; transition:all 0.2s; cursor:pointer; background:none;
}
.k-choice-btn i { font-size:1.8rem; margin-bottom:8px; }
.k-cb-title { font-size:0.85rem; font-weight:700; margin-bottom:2px; }
.k-cb-sub { font-size:0.65rem; opacity:0.8; }
.k-choice-wa { background:transparent; border-color:var(--klien-gold); color:var(--klien-gold); }
.k-choice-wa:hover { background:var(--klien-gold); color:#1a0508; transform:translateY(-2px); box-shadow: 0 4px 15px rgba(212,175,55,0.25); }
.k-choice-pay { background:var(--klien-maroon); border-color:var(--klien-maroon); color:#FFFFFF; }
.k-choice-pay:hover { background:#600000; border-color:#600000; color:#FFFFFF; transform:translateY(-2px); box-shadow: 0 4px 15px rgba(128,0,0,0.25); }

/* Rekening */
.k-rekening-info { padding:16px 18px; border-bottom:1px solid var(--klien-border); }
.k-rek-label { font-size:0.68rem; color:var(--klien-text-muted); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px; font-weight:700; }
.k-rek-bank { font-size:1rem; font-weight:800; color:var(--klien-text); margin-bottom:2px; }
.k-rek-bank span { color:var(--klien-gold); }
.k-rek-name { font-size:0.78rem; color:var(--klien-text-muted); font-weight:600; }

/* Upload */
.k-upload-area {
    margin:16px 18px 0; border:2px dashed var(--klien-border); border-radius:10px;
    padding:28px 20px; text-align:center; cursor:pointer;
    transition:border-color 0.2s, background 0.2s;
}
.k-upload-area:hover { border-color:var(--klien-maroon); background:rgba(128,0,0,0.02); }
.k-upload-icon { font-size:2rem; color:var(--klien-text-muted); display:block; margin-bottom:8px; }
.k-upload-text { font-size:0.85rem; color:var(--klien-text); font-weight:600; }
.k-upload-sub  { font-size:0.7rem; color:var(--klien-text-muted); margin-top:3px; }
.k-btn-upload {
    display:block; margin:12px 18px 18px; background:var(--klien-maroon);
    color:#FFFFFF; font-weight:700; border:none; border-radius:10px;
    padding:13px; font-size:0.85rem; cursor:pointer; transition:all 0.2s;
}
.k-btn-upload:hover { background:#600000; transform:translateY(-1px); box-shadow: 0 4px 15px rgba(128,0,0,0.2); }

/* Proof States */
.k-proof-sent, .k-proof-done, .k-proof-star { text-align:center; padding:30px 18px; }
.k-ps-icon  { font-size:2.5rem; color:#d97706; display:block; margin-bottom:10px; }
.k-pd-icon  { font-size:2.5rem; color:#16a34a; display:block; margin-bottom:10px; }
.k-pstar-icon { font-size:2.5rem; color:var(--klien-gold); display:block; margin-bottom:10px; }
.k-ps-title, .k-pd-title, .k-pstar-title { font-weight:700; color:var(--klien-text); margin-bottom:6px; font-size:1rem; }
.k-ps-sub, .k-pd-sub, .k-pstar-sub { font-size:0.8rem; color:var(--klien-text-muted); line-height:1.5; }

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
