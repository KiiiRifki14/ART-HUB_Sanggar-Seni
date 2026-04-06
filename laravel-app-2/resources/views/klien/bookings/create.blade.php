@extends('layouts.klien')

@section('title', 'Pesan Pementasan Baru – ART-HUB')

@section('content')
<div class="mb-4 animate-fade-up">
    <h2 class="fw-bold mb-1">Self-Service <span class="klien-gold">Booking</span></h2>
    <p class="text-secondary">Silakan isi formulir di bawah ini. Tim kami akan menghubungi Anda untuk konfirmasi (Negotiation Hub).</p>
</div>

<div class="row g-4 animate-fade-up" style="animation-delay: 0.1s;">
    <div class="col-12 col-lg-8">
        <div class="glass-card p-4 p-md-5">
            <form action="{{ route('klien.bookings.store') }}" method="POST" id="bookingForm">
                @csrf
                
                {{-- Harga yang dikalkulasi akan disubmit via JS --}}
                <input type="hidden" name="total_price" id="inputTotalPrice" value="0">

                <h5 class="fw-bold mb-3 klien-gold border-bottom border-light pb-2">Informasi Acara</h5>
                
                <div class="mb-4">
                    <label class="form-label text-light">Pilih Paket Pementasan</label>
                    <div class="row g-3">
                        @foreach($packages as $key => $pkg)
                        <div class="col-12 col-md-6">
                            <input type="radio" class="btn-check package-radio" name="event_type" id="pkg_{{ $key }}" value="{{ $key }}" data-price="{{ $pkg['base_price'] }}" required>
                            <label class="btn btn-outline-light w-100 text-start p-3 rounded-3" for="pkg_{{ $key }}" style="border-color: rgba(255,255,255,0.1);">
                                <div class="fw-bold mb-1 fs-5 text-white">{{ $pkg['name'] }}</div>
                                <div class="klien-gold fw-semibold">Rp {{ number_format($pkg['base_price'], 0, ',', '.') }}</div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label text-light">Tanggal Acara</label>
                        <input type="date" name="event_date" class="form-control" required min="{{ now()->addDays(7)->toDateString() }}">
                        <div class="form-text text-secondary"><small>*Min H-7</small></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-light">Jam Mulai</label>
                        <input type="time" name="event_start" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-light">Jam Selesai</label>
                        <input type="time" name="event_end" class="form-control" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-light">Lokasi Pementasan (Venue Lengkap)</label>
                    <textarea name="venue" class="form-control" rows="3" placeholder="Contoh: Gedung Sate, Bandung" required></textarea>
                </div>

                <h5 class="fw-bold mb-3 klien-gold border-bottom border-light pb-2 mt-5">Kontak Klien</h5>
                <div class="mb-4">
                    <label class="form-label text-light">Nomor WhatsApp Aktif</label>
                    <input type="text" name="client_phone" class="form-control" placeholder="08xxxx" required>
                </div>

                <div class="d-none d-lg-block">
                    <button type="submit" class="btn btn-klien-gold py-3 px-5 fw-bold fs-5 w-100 mt-4 rounded-pill">
                        Ajukan Pesanan Pementasan <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
        </div>
    </div>

    {{-- Kanan: Estimation Panel (Sticky) --}}
    <div class="col-12 col-lg-4">
        <div class="glass-card p-4 sticky-top" style="top: 100px;">
            <h5 class="fw-bold mb-4 border-bottom border-light pb-2"><i class="bi bi-calculator me-2 klien-gold"></i>Estimasi Biaya</h5>
            
            <div class="d-flex justify-content-between mb-3 text-secondary">
                <span>Base Price (Paket)</span>
                <span id="previewBasePrice" class="text-white">Rp 0</span>
            </div>
            <div class="d-flex justify-content-between mb-3 text-secondary">
                <span>Pajak Platform (0%)</span>
                <span class="text-white">Rp 0</span>
            </div>
            
            <hr class="border-secondary my-4">
            
            <div class="text-center mb-4">
                <div class="text-secondary mb-1">Perkiraan Biaya Kontrak</div>
                <h2 class="fw-bold klien-gold m-0" id="previewTotalPrice">Rp 0</h2>
                <div class="small text-secondary mt-2">Dapat dinegosiasikan setelah pengajuan. Admin sanggar akan menghubungi Anda.</div>
            </div>

            <div class="d-lg-none mt-4">
                <button type="submit" form="bookingForm" class="btn btn-klien-gold w-100 py-3 fw-bold rounded-pill">
                    Ajukan Pesanan <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    </div>
</form>
</div>
@endsection

@push('scripts')
<script>
    // JS Logic untuk merubah style pilihan paket & kalkulasi harga
    const packageRadios = document.querySelectorAll('.package-radio');
    const previewBasePrice = document.getElementById('previewBasePrice');
    const previewTotalPrice = document.getElementById('previewTotalPrice');
    const inputTotalPrice = document.getElementById('inputTotalPrice');

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }

    packageRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Reset styles
            document.querySelectorAll('.btn-outline-light').forEach(lbl => {
                lbl.style.borderColor = 'rgba(255,255,255,0.1)';
                lbl.style.background = 'transparent';
            });
            
            // Apply style to active
            if(this.checked) {
                const label = document.querySelector('label[for="' + this.id + '"]');
                label.style.borderColor = 'var(--klien-gold)';
                label.style.background = 'rgba(212, 175, 55, 0.1)';
                
                const price = parseInt(this.getAttribute('data-price'));
                
                previewBasePrice.innerText = formatRupiah(price);
                previewTotalPrice.innerText = formatRupiah(price);
                inputTotalPrice.value = price;
            }
        });
    });
</script>
@endpush
