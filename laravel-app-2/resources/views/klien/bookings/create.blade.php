@extends('layouts.klien')

@section('title', 'Ajukan Pesanan Pementasan – ART-HUB Sanggar Cahaya Gumilang')

@section('content')

{{-- BREADCRUMB --}}
<div class="flex items-center gap-2 mb-6 font-label text-xs uppercase tracking-widest font-bold">
    <a href="{{ route('klien.dashboard') }}" class="text-on-surface-variant hover:text-secondary transition-colors flex items-center gap-1.5">
        <i class="bi bi-arrow-left"></i> Dashboard
    </a>
    <span class="text-outline-variant">/</span>
    <span class="text-outline">Pesan Pementasan Baru</span>
</div>

{{-- HEADER --}}
<div class="mb-8">
    <h1 class="font-headline text-3xl sm:text-4xl font-bold text-primary mb-2">Ajukan <span class="text-secondary">Pementasan Baru</span></h1>
    <p class="font-body text-sm text-on-surface-variant max-w-2xl">Isi formulir di bawah ini. Tim kami akan menghubungi Anda untuk konfirmasi harga final sebelum Anda mentransfer DP.</p>
</div>

<div class="flex flex-col lg:flex-row gap-6 items-start">

    {{-- KOLOM KIRI: FORM --}}
    <div class="flex-grow w-full">
        <form action="{{ route('klien.bookings.store') }}" method="POST" id="bookingForm" class="space-y-6">
            @csrf
            <input type="hidden" name="total_price" id="inputTotalPrice" value="0">

            {{-- PILIH PAKET --}}
            <div class="bg-surface-container-lowest rounded-xl p-6 sm:p-8 border border-outline-variant/30 shadow-[0_4px_12px_rgba(54,31,26,0.02)]">
                <div class="font-label text-xs uppercase tracking-widest text-secondary font-bold flex items-center gap-2 mb-6 pb-4 border-b border-outline-variant/30">
                    <i class="bi bi-grid-3x2-gap"></i> Pilih Paket Pementasan
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($packages as $key => $pkg)
                    <div class="relative group">
                        <input type="radio" class="peer absolute opacity-0 w-0 h-0 k-pkg-radio" name="event_type"
                               id="pkg_{{ $key }}" value="{{ $key }}"
                               data-price="{{ $pkg['base_price'] }}" required>
                        <label for="pkg_{{ $key }}" class="block p-5 rounded-xl border-2 border-surface-container-high bg-surface-container-low cursor-pointer transition-all peer-checked:border-secondary peer-checked:bg-secondary/5 hover:border-outline-variant">
                            <div class="font-headline font-bold text-lg text-primary mb-1">{{ $pkg['name'] }}</div>
                            <div class="font-body font-bold text-secondary text-base">Rp {{ number_format($pkg['base_price'], 0, ',', '.') }}</div>
                            <i class="bi bi-check-circle-fill absolute top-5 right-5 text-secondary opacity-0 scale-50 transition-all peer-checked:opacity-100 peer-checked:scale-100 text-xl"></i>
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('event_type')
                <div class="text-red-500 text-xs mt-3 font-body">{{ $message }}</div>
                @enderror
            </div>

            {{-- WAKTU & TANGGAL --}}
            <div class="bg-surface-container-lowest rounded-xl p-6 sm:p-8 border border-outline-variant/30 shadow-[0_4px_12px_rgba(54,31,26,0.02)]">
                <div class="font-label text-xs uppercase tracking-widest text-secondary font-bold flex items-center gap-2 mb-6 pb-4 border-b border-outline-variant/30">
                    <i class="bi bi-calendar3"></i> Waktu Pelaksanaan
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Tanggal Acara <span class="text-red-500">*</span></label>
                        <input type="date" name="event_date"
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('event_date') border-red-500 @enderror"
                               min="{{ now()->addDays(7)->toDateString() }}" required>
                        <div class="font-label text-[0.65rem] text-outline mt-1.5 flex items-center gap-1"><i class="bi bi-info-circle"></i> Minimal H-7 dari hari ini</div>
                        @error('event_date')<div class="text-red-500 text-xs mt-1 font-body">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Jam Mulai <span class="text-red-500">*</span></label>
                        <input type="time" name="event_start"
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('event_start') border-red-500 @enderror" required>
                        @error('event_start')<div class="text-red-500 text-xs mt-1 font-body">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Jam Selesai <span class="text-red-500">*</span></label>
                        <input type="time" name="event_end"
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('event_end') border-red-500 @enderror" required>
                        @error('event_end')<div class="text-red-500 text-xs mt-1 font-body">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- LOKASI --}}
            <div class="bg-surface-container-lowest rounded-xl p-6 sm:p-8 border border-outline-variant/30 shadow-[0_4px_12px_rgba(54,31,26,0.02)]">
                <div class="font-label text-xs uppercase tracking-widest text-secondary font-bold flex items-center gap-2 mb-6 pb-4 border-b border-outline-variant/30">
                    <i class="bi bi-geo-alt"></i> Lokasi Pementasan
                </div>
                <div class="space-y-5">
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Nama Gedung / Venue <span class="text-red-500">*</span></label>
                        <input type="text" name="venue"
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('venue') border-red-500 @enderror"
                               placeholder="Contoh: Gedung Sate, Bandung" required>
                        @error('venue')<div class="text-red-500 text-xs mt-1 font-body">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Alamat Lengkap</label>
                        <textarea name="venue_address" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" rows="2"
                            placeholder="Contoh: Jl. Diponegoro No. 22, Bandung"></textarea>
                    </div>
                </div>
            </div>

            {{-- KONTAK --}}
            <div class="bg-surface-container-lowest rounded-xl p-6 sm:p-8 border border-outline-variant/30 shadow-[0_4px_12px_rgba(54,31,26,0.02)]">
                <div class="font-label text-xs uppercase tracking-widest text-secondary font-bold flex items-center gap-2 mb-6 pb-4 border-b border-outline-variant/30">
                    <i class="bi bi-phone"></i> Kontak WhatsApp
                </div>
                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Nomor WA Aktif <span class="text-red-500">*</span></label>
                    <div class="flex">
                        <span class="inline-flex items-center px-4 rounded-l-lg border border-r-0 border-outline-variant/50 bg-surface-container-highest text-on-surface-variant font-body text-sm font-semibold">
                            +62
                        </span>
                        <input type="text" name="client_phone"
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-r-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('client_phone') border-red-500 @enderror"
                               placeholder="81xxxxxxxxx" required>
                    </div>
                    @error('client_phone')<div class="text-red-500 text-xs mt-1 font-body">{{ $message }}</div>@enderror
                </div>
            </div>

            <button type="submit" class="hidden lg:flex w-full items-center justify-center gap-2 bg-gradient-to-br from-primary-container to-primary text-white px-6 py-4 rounded-xl font-label text-sm font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-xl shadow-primary/20">
                <i class="bi bi-send-fill"></i> Ajukan Pesanan Pementasan
            </button>
        </form>
    </div>

    {{-- KOLOM KANAN: ESTIMASI STICKY --}}
    <div class="w-full lg:w-80 flex-shrink-0">
        <div class="bg-primary text-white rounded-xl shadow-xl overflow-hidden sticky top-24 border border-primary-container">
            <div class="bg-primary-container px-5 py-4 font-label text-xs uppercase tracking-widest font-bold flex items-center gap-2 border-b border-white/10">
                <i class="bi bi-calculator"></i> Estimasi Biaya
            </div>
            <div class="p-5">
                <div class="flex justify-between font-body text-sm text-white/70 mb-3">
                    <span>Paket dipilih</span>
                    <span id="previewPkgName" class="font-semibold text-secondary text-right max-w-[140px] leading-tight">—</span>
                </div>
                <div class="flex justify-between font-body text-sm text-white/70 mb-4">
                    <span>Base Price</span>
                    <span id="previewBasePrice" class="font-semibold">Rp 0</span>
                </div>
                
                <hr class="border-white/10 my-4">
                
                <div class="text-center">
                    <div class="font-label text-[0.65rem] uppercase tracking-widest text-white/50 mb-2">Perkiraan Total Kontrak</div>
                    <div id="previewTotalPrice" class="font-headline text-3xl font-bold text-secondary mb-5">Rp 0</div>
                </div>
                
                <div class="bg-white/5 rounded-lg p-3 font-body text-xs text-white/60 flex items-start gap-2 leading-relaxed">
                    <i class="bi bi-info-circle text-secondary mt-0.5"></i>
                    Harga dapat bernegosiasi. Admin sanggar akan mengonfirmasi harga final via WhatsApp.
                </div>
            </div>

            {{-- Mobile Submit --}}
            <div class="p-5 bg-black/20 border-t border-white/5 lg:hidden">
                <button type="submit" form="bookingForm" class="w-full flex items-center justify-center gap-2 bg-secondary text-primary px-4 py-3 rounded-lg font-label text-xs font-bold uppercase tracking-widest hover:bg-secondary-container transition-all">
                    <i class="bi bi-send-fill"></i> Ajukan Pesanan
                </button>
            </div>
        </div>
    </div>

</div>

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
