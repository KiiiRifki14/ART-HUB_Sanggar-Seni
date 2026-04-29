@extends('layouts.klien')

@section('title', 'Ajukan Pesanan Pementasan – ART-HUB Sanggar Cahaya Gumilang')

@section('content')

<div class="k-breadcrumb animate-fade-up">
    <a href="{{ route('klien.dashboard') }}" class="k-back-link">
        <i class="bi bi-arrow-left me-2"></i>Dashboard
    </a>
    <span class="k-bc-sep">/</span>
    <span class="k-bc-current">Pesan Pementasan Baru</span>
</div>

<div class="k-create-header animate-fade-up" style="animation-delay:0.05s;">
    <h1 class="k-create-title">Ajukan <span class="klien-gold-text">Pementasan Baru</span></h1>
    <p class="k-create-sub">Isi formulir di bawah ini. Tim kami akan menghubungi Anda untuk konfirmasi harga final sebelum Anda mentransfer DP.</p>
</div>

<div class="k-form-layout animate-fade-up" style="animation-delay:0.1s;">

    {{-- KOLOM KIRI: FORM --}}
    <div class="k-form-main">
        <form action="{{ route('klien.bookings.store') }}" method="POST" id="bookingForm">
            @csrf
            <input type="hidden" name="total_price" id="inputTotalPrice" value="0">

            {{-- PILIH PAKET --}}
            <div class="k-form-section">
                <div class="k-form-section-title"><i class="bi bi-grid-3x2-gap me-2"></i>Pilih Paket Pementasan</div>
                <div class="k-pkg-grid">
                    @foreach($packages as $key => $pkg)
                    <div class="k-pkg-item">
                        <input type="radio" class="k-pkg-radio" name="event_type"
                               id="pkg_{{ $key }}" value="{{ $key }}"
                               data-price="{{ $pkg['base_price'] }}" required>
                        <label class="k-pkg-label" for="pkg_{{ $key }}">
                            <div class="k-pkg-name">{{ $pkg['name'] }}</div>
                            <div class="k-pkg-price">Rp {{ number_format($pkg['base_price'], 0, ',', '.') }}</div>
                            <div class="k-pkg-check"><i class="bi bi-check-circle-fill"></i></div>
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('event_type')
                <div class="k-field-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- WAKTU & TANGGAL --}}
            <div class="k-form-section">
                <div class="k-form-section-title"><i class="bi bi-calendar3 me-2"></i>Waktu Pelaksanaan</div>
                <div class="k-input-grid-3">
                    <div class="k-field">
                        <label class="k-label">Tanggal Acara <span class="k-req">*</span></label>
                        <input type="date" name="event_date"
                               class="k-input @error('event_date') k-input-error @enderror"
                               min="{{ now()->addDays(7)->toDateString() }}" required>
                        <div class="k-field-hint">Minimal H-7 dari hari ini</div>
                        @error('event_date')<div class="k-field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="k-field">
                        <label class="k-label">Jam Mulai <span class="k-req">*</span></label>
                        <input type="time" name="event_start"
                               class="k-input @error('event_start') k-input-error @enderror" required>
                        @error('event_start')<div class="k-field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="k-field">
                        <label class="k-label">Jam Selesai <span class="k-req">*</span></label>
                        <input type="time" name="event_end"
                               class="k-input @error('event_end') k-input-error @enderror" required>
                        @error('event_end')<div class="k-field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- LOKASI --}}
            <div class="k-form-section">
                <div class="k-form-section-title"><i class="bi bi-geo-alt me-2"></i>Lokasi Pementasan</div>
                <div class="k-field">
                    <label class="k-label">Nama Gedung / Venue <span class="k-req">*</span></label>
                    <input type="text" name="venue"
                           class="k-input @error('venue') k-input-error @enderror"
                           placeholder="Contoh: Gedung Sate, Bandung" required>
                    @error('venue')<div class="k-field-error">{{ $message }}</div>@enderror
                </div>
                <div class="k-field mt-3">
                    <label class="k-label">Alamat Lengkap</label>
                    <textarea name="venue_address" class="k-input" rows="2"
                        placeholder="Contoh: Jl. Diponegoro No. 22, Bandung"></textarea>
                </div>
            </div>

            {{-- KONTAK --}}
            <div class="k-form-section">
                <div class="k-form-section-title"><i class="bi bi-phone me-2"></i>Kontak WhatsApp</div>
                <div class="k-field">
                    <label class="k-label">Nomor WA Aktif <span class="k-req">*</span></label>
                    <div class="k-phone-wrap">
                        <span class="k-phone-prefix">+62</span>
                        <input type="text" name="client_phone"
                               class="k-input k-input-phone @error('client_phone') k-input-error @enderror"
                               placeholder="81xxxxxxxxx" required>
                    </div>
                    @error('client_phone')<div class="k-field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <button type="submit" class="k-submit-btn d-none d-lg-flex">
                <i class="bi bi-send me-2"></i>Ajukan Pesanan Pementasan
            </button>
        </form>
    </div>

    {{-- KOLOM KANAN: ESTIMASI STICKY --}}
    <div class="k-form-aside">
        <div class="k-estimate-card">
            <div class="k-estimate-header">
                <i class="bi bi-calculator me-2"></i>Estimasi Biaya
            </div>
            <div class="k-estimate-body">
                <div class="k-est-row">
                    <span>Paket dipilih</span>
                    <span id="previewPkgName" class="k-est-pkg">—</span>
                </div>
                <div class="k-est-row">
                    <span>Base Price</span>
                    <span id="previewBasePrice">Rp 0</span>
                </div>
                <div class="k-est-divider"></div>
                <div class="k-est-total-label">Perkiraan Total Kontrak</div>
                <div id="previewTotalPrice" class="k-est-total">Rp 0</div>
                <div class="k-est-note">
                    <i class="bi bi-info-circle me-1"></i>
                    Harga dapat bernegosiasi. Admin sanggar akan mengonfirmasi harga final via WhatsApp.
                </div>
            </div>

            {{-- Mobile Submit --}}
            <div class="k-estimate-footer d-lg-none">
                <button type="submit" form="bookingForm" class="k-submit-btn">
                    <i class="bi bi-send me-2"></i>Ajukan Pesanan
                </button>
            </div>
        </div>
    </div>

</div>

<style>
/* ═══ BREADCRUMB ═══ */
.k-breadcrumb { display:flex; align-items:center; gap:8px; margin-bottom:20px; font-size:0.82rem; color:#666; }
.k-back-link { color:#888; text-decoration:none; display:flex; align-items:center; transition:color 0.2s; }
.k-back-link:hover { color:#d4af37; }
.k-bc-sep { color:#3a3a3a; }
.k-bc-current { color:#aaa; }

/* ═══ HEADER ═══ */
.k-create-header { margin-bottom:28px; }
.k-create-title { font-size:1.8rem; font-weight:800; color:#fff; margin-bottom:8px; }
.klien-gold-text { color:var(--klien-gold); }
.k-create-sub { font-size:0.88rem; color:#777; }

/* ═══ LAYOUT ═══ */
.k-form-layout { display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start; }

/* ═══ SECTION ═══ */
.k-form-section {
    background:#0d0d0d; border:1px solid #1e1e1e;
    border-radius:14px; padding:22px; margin-bottom:14px;
}
.k-form-section-title { font-size:0.8rem; font-weight:700; color:#888; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:16px; display:flex; align-items:center; }

/* ═══ PAKET GRID ═══ */
.k-pkg-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.k-pkg-item { position:relative; }
.k-pkg-radio { position:absolute; opacity:0; width:0; height:0; }
.k-pkg-label {
    display:block; background:#111; border:1px solid #2a2a2a;
    border-radius:10px; padding:14px 16px; cursor:pointer;
    transition:border-color 0.2s, background 0.2s; position:relative; overflow:hidden;
}
.k-pkg-label:hover { border-color:#3a3a3a; background:#141414; }
.k-pkg-radio:checked + .k-pkg-label { border-color:#d4af37; background:rgba(212,175,55,0.08); }
.k-pkg-name { font-weight:700; color:#ddd; font-size:0.88rem; margin-bottom:5px; }
.k-pkg-price { font-size:0.92rem; font-weight:800; color:#d4af37; }
.k-pkg-check {
    position:absolute; top:10px; right:10px;
    font-size:1.1rem; color:#d4af37;
    opacity:0; transform:scale(0.7); transition:all 0.2s;
}
.k-pkg-radio:checked + .k-pkg-label .k-pkg-check { opacity:1; transform:scale(1); }

/* ═══ INPUTS ═══ */
.k-input-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; }
.k-label { display:block; font-size:0.78rem; color:#999; margin-bottom:6px; }
.k-req { color:#ef4444; }
.k-input {
    width:100%; background:#080808; border:1px solid #2a2a2a;
    color:#ddd; border-radius:8px; padding:11px 14px;
    font-size:0.85rem; transition:border-color 0.2s, background 0.2s;
    font-family:'Outfit', sans-serif; resize:vertical;
}
.k-input:focus { outline:none; border-color:#d4af37; background:#0d0d0d; color:#fff; }
.k-input-error { border-color:#ef4444 !important; }
.k-field-hint { font-size:0.68rem; color:#555; margin-top:4px; }
.k-field-error { font-size:0.72rem; color:#ef4444; margin-top:4px; }
.k-phone-wrap { display:flex; }
.k-phone-prefix {
    background:#151515; border:1px solid #2a2a2a; border-right:none;
    color:#888; padding:11px 14px; border-radius:8px 0 0 8px; font-size:0.85rem;
}
.k-input-phone { border-radius:0 8px 8px 0; }

/* ═══ SUBMIT ═══ */
.k-submit-btn {
    background:linear-gradient(135deg,#e6c25a,#b48b25);
    color:#000; font-weight:700; border:none;
    border-radius:10px; padding:15px 28px; font-size:0.9rem;
    cursor:pointer; transition:all 0.2s;
    display:flex; align-items:center; justify-content:center; width:100%;
}
.k-submit-btn:hover { background:linear-gradient(135deg,#f7d165,#c59929); transform:translateY(-2px); box-shadow:0 6px 20px rgba(212,175,55,0.3); }

/* ═══ ESTIMASI ASIDE ═══ */
.k-estimate-card { background:#080808; border:1px solid #1e1e1e; border-radius:14px; overflow:hidden; position:sticky; top:90px; }
.k-estimate-header { background:#0d0d0d; border-bottom:1px solid #1e1e1e; padding:14px 18px; font-size:0.8rem; font-weight:700; color:#888; display:flex; align-items:center; text-transform:uppercase; letter-spacing:0.06em; }
.k-estimate-body { padding:18px; }
.k-est-row { display:flex; justify-content:space-between; font-size:0.8rem; color:#666; margin-bottom:10px; }
.k-est-pkg { color:#d4af37; font-weight:600; max-width:140px; text-align:right; }
.k-est-divider { height:1px; background:#1e1e1e; margin:14px 0; }
.k-est-total-label { font-size:0.68rem; color:#555; text-align:center; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px; }
.k-est-total { font-size:1.8rem; font-weight:800; color:#d4af37; text-align:center; margin-bottom:14px; }
.k-est-note { font-size:0.68rem; color:#555; background:#0d0d0d; border-radius:8px; padding:10px 12px; line-height:1.5; display:flex; align-items:flex-start; gap:4px; }
.k-estimate-footer { border-top:1px solid #1e1e1e; padding:14px; }

@media (max-width:900px) {
    .k-form-layout { grid-template-columns:1fr; }
    .k-pkg-grid { grid-template-columns:1fr 1fr; }
    .k-input-grid-3 { grid-template-columns:1fr 1fr; }
}
@media (max-width:500px) {
    .k-pkg-grid { grid-template-columns:1fr; }
    .k-input-grid-3 { grid-template-columns:1fr; }
}
</style>

@push('scripts')
<input type="hidden" id="packagesJson" value='{{ addslashes(json_encode($packages)) }}'>
<script>
var packages = JSON.parse(document.getElementById('packagesJson').value);

document.querySelectorAll('.k-pkg-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
        var price = parseInt(this.getAttribute('data-price'));
        var name  = packages[this.value] ? packages[this.value].name : this.value;

        document.getElementById('previewPkgName').innerText   = name;
        document.getElementById('previewBasePrice').innerText = 'Rp ' + price.toLocaleString('id-ID');
        document.getElementById('previewTotalPrice').innerText = 'Rp ' + price.toLocaleString('id-ID');
        document.getElementById('inputTotalPrice').value = price;
    });
});
</script>
@endpush

@endsection
