@extends('layouts.admin')
@section('title', 'DP Verification – ART-HUB')
@section('page_title', 'DP Verification')
@section('page_subtitle', 'Pastikan keamanan finansial sanggar dengan verifikasi bukti transfer klien.')

@section('content')

{{-- ═══════════════════════════ SUMMARY CARDS ═══════════════════════════ --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
    <div class="card-gold p-4 md:p-6 flex items-center gap-4 md:gap-5 group hover:-translate-y-1 transition-all">
        <div class="w-11 h-11 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-lg md:text-2xl flex-shrink-0 group-hover:scale-110 transition-transform" style="background:rgba(234,88,12,0.1); color:#ea580c;">
            <i data-lucide="clock" class="w-6 h-6"></i>
        </div>
        <div>
            <div class="subtitle-gold mb-1" style="font-size:0.65rem;">Menunggu Verifikasi</div>
            <div class="title-gold" style="font-size:1.5rem; line-height:1;">{{ $antreanCount }} <span class="subtitle-gold" style="font-size:0.8rem; text-transform:none; letter-spacing:normal;">Antrean</span></div>
        </div>
    </div>
    <div class="card-gold p-4 md:p-6 flex items-center gap-4 md:gap-5 group hover:-translate-y-1 transition-all">
        <div class="w-11 h-11 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-lg md:text-2xl flex-shrink-0 group-hover:scale-110 transition-transform" style="background:rgba(22,163,74,0.1); color:#16a34a;">
            <i data-lucide="wallet" class="w-6 h-6"></i>
        </div>
        <div>
            <div class="subtitle-gold mb-1" style="font-size:0.65rem;">Total DP Masuk</div>
            <div class="title-gold" style="font-size:1.5rem; line-height:1; color:#16a34a;">Rp {{ number_format($totalDpMasuk, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="card-gold p-4 md:p-6 flex items-center gap-4 md:gap-5 group hover:-translate-y-1 transition-all" style="background:linear-gradient(135deg, #8B1A2A, #5C0E19);">
        <div class="w-11 h-11 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-lg md:text-2xl flex-shrink-0 group-hover:scale-110 transition-transform" style="background:rgba(255,255,255,0.1); color:#fcd400;">
            <i data-lucide="lock" class="w-6 h-6"></i>
        </div>
        <div>
            <div class="subtitle-gold mb-1" style="font-size:0.65rem; color:rgba(255,255,255,0.7);">Profit Aman (Locked)</div>
            <div class="title-gold" style="font-size:1.5rem; line-height:1; color:#fcd400;">Rp {{ number_format($totalProfitLocked, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

{{-- ═══════════════════ TABEL ANTREAN VERIFIKASI ═══════════════════ --}}
<div class="card-gold overflow-hidden mb-8">
    <div class="px-6 py-4 border-b flex flex-col sm:flex-row sm:items-center justify-between gap-4" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.02);">
        <h3 class="title-gold flex items-center gap-2" style="font-size:1.3rem;">
            <i data-lucide="shield-check" class="w-5 h-5 text-yellow-600"></i> Antrean Verifikasi
            @if($antreanCount > 0)
                <span class="inline-flex items-center justify-center min-w-6 h-6 px-1.5 rounded-full font-bold" style="background:#8B1A2A; color:#fcd400; font-size:0.65rem;">{{ $antreanCount }}</span>
            @endif
        </h3>
        <div class="relative">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
            <input type="text" id="searchKlien" placeholder="Cari Klien..." oninput="filterTable()" class="input-gold w-full sm:w-64" style="padding-left:36px; padding-top:8px; padding-bottom:8px;">
        </div>
    </div>

    <table class="w-full hidden md:table table-gold" id="dpvTable">
        <thead>
            <tr>
                <th class="text-left">Kode Booking</th>
                <th class="text-left">Klien & Acara</th>
                <th class="text-right">Total Deal</th>
                <th class="text-right">Nominal DP</th>
                <th class="text-center">Bukti</th>
                <th class="text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingWithProof as $booking)
            <tr data-client="{{ strtolower($booking->client_name) }}">
                <td class="pl-5">
                    <span class="badge-gold">
                        BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}
                    </span>
                </td>
                <td>
                    <div style="font-weight:700; color:#1A1817; font-size:0.95rem;">{{ $booking->client_name }}</div>
                    <span class="badge-gold mt-1">
                        {{ ucwords(str_replace('_', ' ', $booking->event_type)) }}
                    </span>
                </td>
                <td class="text-right" style="font-family:'Inter',sans-serif; color:#1A1817; font-weight:600;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                <td class="text-right">
                    <div style="font-weight:700; color:#16a34a; font-size:1.1rem;">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
                    <div class="subtitle-gold" style="font-size:0.6rem;">50% FEE</div>
                </td>
                <td class="text-center">
                    @if($booking->payment_proof)
                        <button type="button" class="arh-btn-secondary py-1.5 px-3"
                            onclick="openVerifyModal({{ $booking->id }})">
                            <i data-lucide="eye" class="w-4 h-4 mr-1 inline-block -mt-1"></i> Lihat Bukti
                        </button>
                    @else
                        <span class="subtitle-gold italic" style="text-transform:none; letter-spacing:normal;"><i data-lucide="image" class="w-3 h-3 mr-1 inline-block -mt-0.5"></i>Belum ada</span>
                    @endif
                </td>
                <td>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="arh-btn-primary py-1.5 px-3" style="background:linear-gradient(135deg, #fcd400, #C5A028); color:#1A1817; border:none;"
                            onclick="openVerifyModal({{ $booking->id }})">
                            <i data-lucide="check" class="w-4 h-4 mr-1 inline-block -mt-1"></i> Verifikasi
                        </button>
                        @php $rjMsg = "Tolak & hapus bukti transfer dari " . addslashes($booking->client_name) . "? Klien akan diwajibkan upload ulang."; @endphp
                        <form action="{{ route('admin.bookings.reject_proof', $booking->id) }}" method="POST" class="m-0"
                              data-confirm="{{ $rjMsg }}">
                            @csrf
                            <button type="submit" class="arh-btn-secondary py-1.5 px-3" style="color:#ef4444; border-color:rgba(239,68,68,0.3); background:rgba(239,68,68,0.05);">
                                <i data-lucide="x" class="w-4 h-4 mr-1 inline-block -mt-1"></i> Tolak
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-16 text-center">
                    <i data-lucide="check-circle" class="w-12 h-12 text-green-500 mx-auto mb-3 opacity-50"></i>
                    <p class="title-gold" style="font-size:1.2rem; margin-bottom:4px;">Tidak ada bukti transfer yang menunggu</p>
                    <p class="subtitle-gold" style="font-size:0.7rem;">Semua DP sudah diverifikasi! ✅</p>
                </td>
            </tr>
            @endforelse

    {{-- Mobile Cards View --}}
    <div class="block md:hidden" style="border-top:1px solid rgba(197,160,40,0.2);" id="dpvMobileCards">
        @forelse($pendingWithProof as $booking)
        <div data-client="{{ strtolower($booking->client_name) }}" class="p-4 border-b space-y-3" style="border-color:rgba(197,160,40,0.15);">
            <div class="flex justify-between items-center">
                <span class="badge-gold">
                    BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}
                </span>
                @if($booking->payment_proof)
                    <button type="button" class="arh-btn-secondary py-1 px-2" style="font-size:0.6rem;"
                        onclick="openVerifyModal({{ $booking->id }})">
                        <i data-lucide="eye" class="w-3 h-3 mr-1 inline-block -mt-0.5"></i> Detail Bukti
                    </button>
                @else
                    <span class="subtitle-gold italic" style="text-transform:none; letter-spacing:normal;"><i data-lucide="image" class="w-3 h-3 mr-1 inline-block -mt-0.5"></i>Belum ada</span>
                @endif
            </div>
            <div>
                <div style="font-weight:700; color:#1A1817; font-size:1rem; margin-bottom:4px;">{{ $booking->client_name }}</div>
                <div class="flex items-center justify-between gap-2">
                    <span class="badge-gold" style="background:transparent; border-color:rgba(197,160,40,0.3);">
                        {{ ucwords(str_replace('_', ' ', $booking->event_type)) }}
                    </span>
                    <span class="subtitle-gold" style="text-transform:none; letter-spacing:normal;">
                        Deal: <strong style="color:#1A1817;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
                    </span>
                </div>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-outline-variant/10">
                <div>
                    <div class="font-headline font-bold text-green-600 text-sm">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
                    <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold">DP (50%)</div>
                </div>
                <div class="flex gap-2">
                    <button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded bg-primary text-white font-label text-[0.65rem] font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-sm"
                        onclick="openVerifyModal({{ $booking->id }})">
                        <i class="bi bi-check-lg"></i> Verifikasi
                    </button>
                    @php $rjMsg = "Tolak & hapus bukti transfer dari " . addslashes($booking->client_name) . "? Klien akan diwajibkan upload ulang."; @endphp
                    <form action="{{ route('admin.bookings.reject_proof', $booking->id) }}" method="POST" class="m-0"
                          data-confirm="{{ $rjMsg }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded border border-red-500/50 bg-transparent text-red-500 font-label text-[0.65rem] font-bold uppercase tracking-widest hover:bg-red-500/10 transition-colors">
                            <i class="bi bi-x-lg"></i> Tolak
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-outline">
            <i class="bi bi-patch-check text-4xl text-green-500/50 mb-3 block"></i>
            <p class="font-body text-sm font-semibold text-on-surface">Tidak ada bukti transfer yang menunggu</p>
            <p class="font-label text-[0.6rem] uppercase tracking-widest text-outline mt-1">Semua DP sudah diverifikasi! ✅</p>
        </div>
        @endforelse
    </div>

    @if($pendingWithProof->hasPages())
    <div class="px-6 py-4 border-t border-outline-variant/20 bg-surface-container-low/20">
        {{ $pendingWithProof->appends(request()->except('page_with_proof'))->links() }}
    </div>
    @endif
</div>

{{-- ═══════════════ DAFTAR MENUNGGU UPLOAD ═══════════════ --}}
<div class="card-gold overflow-hidden">
    <div class="px-6 py-4 border-b flex flex-col sm:flex-row sm:items-center justify-between gap-4" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.02);">
        <h3 class="title-gold flex items-center gap-2" style="font-size:1.3rem;">
            <i data-lucide="hourglass" class="w-5 h-5 text-gray-400"></i> Menunggu Upload Klien
            @if($pendingNoProof->total() > 0)
                <span class="inline-flex items-center justify-center min-w-6 h-6 px-1.5 rounded-full font-bold" style="background:#e5e7eb; color:#4b5563; font-size:0.65rem;">{{ $pendingNoProof->total() }}</span>
            @endif
        </h3>
    </div>

    <div class="divide-y" style="border-color:rgba(197,160,40,0.15);">
        @forelse($pendingNoProof as $booking)
        <div class="flex flex-col sm:flex-row sm:items-center p-4 sm:p-5 gap-4 sm:gap-5 transition-colors" style="background:rgba(255,255,255,0.01);">
            <div class="flex items-center gap-4 flex-grow min-w-0">
                <div class="w-12 h-12 rounded-lg border flex items-center justify-center flex-shrink-0" style="border:1px dashed rgba(197,160,40,0.4); background:rgba(197,160,40,0.03);">
                    <i data-lucide="upload-cloud" class="w-5 h-5 text-gray-400"></i>
                </div>
                <div class="min-w-0">
                    <div style="font-weight:700; color:#1A1817; font-size:0.95rem; margin-bottom:4px;" class="truncate">{{ $booking->client_name }}</div>
                    <div class="subtitle-gold flex items-center gap-2 flex-wrap" style="font-size:0.65rem;">
                        <span class="flex items-center"><i data-lucide="calendar" class="w-3 h-3 mr-1 inline-block -mt-0.5"></i> {{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}</span>
                        <span style="color:rgba(132,123,120,0.5);">•</span>
                        <span class="flex items-center"><i data-lucide="smartphone" class="w-3 h-3 mr-1 inline-block -mt-0.5"></i> {{ $booking->client_phone }}</span>
                        
                        @php $createdDays = \Carbon\Carbon::parse($booking->created_at)->diffInDays(now()); @endphp
                        @if($createdDays > 3)
                            <span class="px-2 py-0.5 rounded" style="background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.2); color:#ef4444; text-transform:none; letter-spacing:normal;">Overdue {{ $createdDays }} hari</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex justify-between items-center sm:text-right sm:block">
                <div class="sm:hidden subtitle-gold" style="font-size:0.6rem;">Total Kontrak</div>
                <div>
                    <div class="hidden sm:block subtitle-gold mb-1" style="font-size:0.6rem;">Total Kontrak</div>
                    <div style="font-weight:700; color:#8B1A2A; font-size:1.1rem;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="flex-shrink-0 flex gap-2 justify-end sm:ml-4">
                <button type="button" onclick="openCashModal({{ $booking->id }})" class="arh-btn-primary py-1.5 px-3 text-xs" style="background:linear-gradient(135deg, #16a34a, #15803d); color:white; border:none;" title="Terima Pembayaran Tunai (Offline)">
                    <i data-lucide="banknote" class="w-4 h-4 mr-1 inline-block -mt-1"></i> <span class="hidden xs:inline">Terima</span> Cash
                </button>
                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="arh-btn-secondary py-1.5 px-2.5" title="Detail & Nego Harga">
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
            <p class="subtitle-gold" style="text-transform:none; letter-spacing:normal;">Tidak ada booking yang sedang menunggu upload.</p>
        </div>
        @endforelse
    </div>

    @if($pendingNoProof->hasPages())
    <div class="px-6 py-4 border-t" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.01);">
        {{ $pendingNoProof->appends(request()->except('page_no_proof'))->links() }}
    </div>
    @endif
</div>

{{-- ═══════════════════════════════════════════════════════
     MODAL VERIFIKASI DETAIL (per Booking)
═══════════════════════════════════════════════════════ --}}
@foreach($pendingWithProof as $booking)
@php
    // Hitung saran profit: 30% dari kontrak, dibatasi maksimal DP yang masuk
    $targetProfit = $booking->total_price * 0.30;
    $dpAmount     = $booking->dp_amount;
    $fixedProfit  = min($dpAmount, $targetProfit); // Saran: tidak boleh melebihi DP
    $opsBudget    = max(0, $dpAmount - $fixedProfit);
    $safetyEst    = $opsBudget * 0.10;
    $netOpsEst    = max(0, $opsBudget - $safetyEst);
    $eventDate    = \Carbon\Carbon::parse($booking->event_date)->isoFormat('D MMMM Y');
@endphp

<div id="modalVerify{{ $booking->id }}" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeVerifyModal({{ $booking->id }})"></div>
    <div class="relative w-full max-w-3xl bg-surface-container-lowest rounded-2xl shadow-2xl border border-outline-variant/30 overflow-hidden max-h-[90vh] flex flex-col">

        <div class="px-4 py-4 md:px-6 md:py-5 border-b border-outline-variant/20 bg-surface-container-low flex justify-between items-start flex-shrink-0">
            <div>
                <h5 class="font-headline font-bold text-base md:text-lg text-primary flex items-center gap-2 mb-1">
                    <i class="bi bi-shield-check text-secondary"></i> DP Verification
                </h5>
                <div class="font-label text-[0.65rem] md:text-xs uppercase tracking-widest text-on-surface-variant font-bold">
                    BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }} <span class="mx-1 text-outline-variant">•</span> {{ $eventDate }}
                </div>
            </div>
            <button type="button" class="text-on-surface-variant hover:text-primary transition-colors text-xl" onclick="closeVerifyModal({{ $booking->id }})"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="p-4 md:p-6 overflow-y-auto flex-1">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 md:gap-6">
                <div class="md:col-span-7 space-y-4">
                    <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold border-b border-outline-variant/30 pb-2 mb-2">Deal Summary</div>
                    <div class="bg-surface-container-low border border-outline-variant/50 rounded-xl p-3 md:p-4">
                        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Client Name</div>
                        <div class="font-body font-bold text-sm text-on-surface">{{ $booking->client_name }}</div>
                    </div>
                    <div class="bg-surface-container-low border border-outline-variant/50 rounded-xl p-3 md:p-4">
                        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Total Contract Price</div>
                        <div class="font-headline font-bold text-base md:text-lg text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 md:gap-4">
                        <div class="bg-surface-container-low border border-outline-variant/50 rounded-xl p-3 md:p-4">
                            <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">DP Received</div>
                            <div class="font-headline font-bold text-sm md:text-base text-green-600">Rp {{ number_format($dpAmount, 0, ',', '.') }}</div>
                        </div>
                        <div class="bg-surface-container-low border border-outline-variant/50 rounded-xl p-3 md:p-4">
                            <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-2">Payment Status</div>
                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-orange-500/10 text-orange-600 border border-orange-500/20 font-label text-[0.55rem] md:text-[0.6rem] font-bold uppercase tracking-wider">
                                <i class="bi bi-clock-fill"></i> Pending
                            </span>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-primary-container to-primary rounded-xl p-4 md:p-5 border border-primary/20 shadow-md">
                        <label class="block font-label text-[0.65rem] uppercase tracking-widest text-secondary font-bold mb-1.5"
                               for="profitInput{{ $booking->id }}">
                            <i class="bi bi-lock-fill"></i> Fixed Profit Pimpinan (Rp) <span class="text-red-300">*</span>
                        </label>
                        <p class="font-body text-[0.65rem] text-white/80 mb-1">
                            Saran: <strong class="text-secondary">Rp {{ number_format($fixedProfit, 0, ',', '.') }}</strong>
                            (≈{{ round(($fixedProfit / $booking->total_price) * 100, 1) }}% dari kontrak)
                            — sudah diisi otomatis, bisa diubah.
                        </p>
                        <input
                            type="number"
                            id="profitInput{{ $booking->id }}"
                            name="fixed_profit_nominal"
                            min="0"
                            max="{{ $dpAmount }}"
                            value="{{ $fixedProfit }}"
                            class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 font-headline font-bold text-white placeholder-white/50 focus:border-secondary outline-none transition-all"
                            required
                            form="formConfirm{{ $booking->id }}"
                            oninput="updateProfitPreview({{ $booking->id }}, {{ $dpAmount }})">
                        {{-- Live Preview --}}
                        <div id="profitPreview{{ $booking->id }}" class="mt-2 text-[0.6rem] text-white/70 space-y-0.5">
                            <div>→ Ops Gross: <span id="opsGross{{ $booking->id }}">Rp {{ number_format($opsBudget, 0, ',', '.') }}</span></div>
                            <div>→ Safety 10%: <span id="opsSafety{{ $booking->id }}">Rp {{ number_format($safetyEst, 0, ',', '.') }}</span></div>
                            <div>→ <strong class="text-white/90">Ops Bersih: <span id="opsNet{{ $booking->id }}">Rp {{ number_format($netOpsEst, 0, ',', '.') }}</span></strong></div>
                        </div>
                    </div>
                    @if($opsBudget > 0)
                    <div class="bg-blue-500/5 border border-blue-500/20 rounded-xl p-3 md:p-4">
                        <div class="font-label text-[0.65rem] uppercase tracking-widest text-blue-700/70 font-bold mb-1">Estimasi Sisa Ops (jika ikut saran)</div>
                        <div class="font-headline font-bold text-sm md:text-base text-blue-600">Rp {{ number_format($netOpsEst, 0, ',', '.') }}</div>
                        <div class="font-label text-[0.55rem] text-blue-500/70 mt-0.5">Gross: Rp {{ number_format($opsBudget, 0, ',', '.') }} − Cadangan 10%: Rp {{ number_format($safetyEst, 0, ',', '.') }}</div>
                    </div>
                    @else
                    <div class="bg-orange-500/10 border border-orange-500/20 rounded-xl p-3 md:p-4 text-orange-700 font-body text-xs flex items-start gap-2">
                        <i class="bi bi-exclamation-triangle-fill mt-0.5"></i>
                        <div>DP lebih kecil dari target laba 30% — seluruh DP dikunci sebagai cicilan laba.</div>
                    </div>
                    @endif
                </div>
                <div class="md:col-span-5">
                    <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold border-b border-outline-variant/30 pb-2 mb-4">Bukti Transfer</div>
                    @if($booking->payment_proof)
                        <div class="rounded-xl border border-outline-variant/30 overflow-hidden shadow-sm mb-3 group cursor-pointer relative"
                             onclick="window.open('{{ asset('storage/' . $booking->payment_proof) }}', '_blank')">
                            <img src="{{ asset('storage/' . $booking->payment_proof) }}" class="w-full h-40 md:h-48 object-cover transition-transform duration-500 group-hover:scale-105" alt="Bukti Transfer">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                <i class="bi bi-arrows-fullscreen text-white opacity-0 group-hover:opacity-100 text-2xl md:text-3xl drop-shadow-md transition-opacity"></i>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $booking->payment_proof) }}" download class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-outline-variant/50 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors">
                            <i class="bi bi-download"></i> Unduh Bukti
                        </a>
                    @else
                        <div class="border-2 border-dashed border-outline-variant/50 rounded-xl p-6 md:p-10 text-center text-outline bg-surface-container-low/50">
                            <i class="bi bi-image text-4xl mb-2 block"></i>
                            <div class="font-body text-sm">Belum ada bukti</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="px-4 py-4 md:px-6 md:py-4 border-t border-outline-variant/20 bg-surface-container-low flex justify-between items-center flex-shrink-0">
            <button type="button" class="px-3 py-2 rounded-lg border border-outline-variant/50 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-colors"
                    onclick="closeVerifyModal({{ $booking->id }})">Batal</button>
            <div class="flex gap-2">
                @php $rjMsg2 = "Tolak & hapus bukti transfer dari " . addslashes($booking->client_name) . "?"; @endphp
                <form action="{{ route('admin.bookings.reject_proof', $booking->id) }}" method="POST" class="m-0" data-confirm="{{ $rjMsg2 }}">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded-lg border border-red-500/30 bg-red-500/10 text-red-600 font-label text-xs font-bold uppercase tracking-widest hover:bg-red-500/20 transition-colors flex items-center gap-1.5">
                        <i class="bi bi-x-circle"></i> Tolak
                    </button>
                </form>
                <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST" class="m-0" id="formConfirm{{ $booking->id }}">
                    @csrf
                    <button type="button"
                        onclick="submitWithValidation({{ $booking->id }}, {{ $dpAmount }}, '{{ addslashes($booking->client_name) }}')"
                        class="px-4 py-2.5 rounded-lg bg-green-500 text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-green-600 transition-colors flex items-center gap-1.5 shadow-md">
                        <i class="bi bi-check-circle"></i> Konfirmasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- ═══════════════════════════════════════════════════════
     MODAL KONFIRMASI PEMBAYARAN CASH (Offline)
═══════════════════════════════════════════════════════ --}}
@foreach($pendingNoProof as $booking)
<div id="modalCash{{ $booking->id }}" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCashModal({{ $booking->id }})"></div>
    <div class="relative w-full max-w-md bg-surface-container-lowest rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="px-4 py-4 md:px-6 md:py-5 border-b border-green-500/20 flex justify-between items-center bg-green-500/10 flex-shrink-0">
            <h5 class="font-headline font-bold text-base md:text-lg text-green-700 flex items-center gap-2">
                <i class="bi bi-cash-stack"></i> Terima Pembayaran Tunai
            </h5>
            <button type="button" class="text-green-700/50 hover:text-green-700 transition-colors" onclick="closeCashModal({{ $booking->id }})">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form action="{{ route('admin.bookings.confirm_cash', $booking->id) }}" method="POST" class="flex-1 overflow-y-auto" data-confirm="Proses pembayaran tunai ini?">
            @csrf
            <div class="p-4 md:p-6 space-y-4 md:space-y-5">
                <div class="flex items-center justify-between p-3 rounded-lg border border-primary/30 bg-primary/5">
                    <span class="font-label text-xs uppercase tracking-widest text-primary font-bold">Total Deal</span>
                    <span class="font-headline text-xl md:text-2xl font-bold text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg border border-green-500/30 bg-green-500/5">
                    <span class="font-label text-xs uppercase tracking-widest text-green-600 font-bold">DP Tunai (50%)</span>
                    <span class="font-headline text-base md:text-lg font-bold text-green-600">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
                </div>
                <hr class="border-outline-variant/20 border-dashed">
                <div>
                    <label class="block font-label text-[0.65rem] uppercase tracking-widest text-primary font-bold mb-1.5"><i class="bi bi-lock-fill"></i> Fixed Profit Pimpinan (Rp)</label>
                    <p class="font-body text-[0.7rem] text-on-surface-variant mb-2">Tentukan nominal laba yang ingin dikunci langsung dari uang tunai ini.</p>
                    <input type="number" name="fixed_profit_nominal" min="0" placeholder="Contoh: 2000000"
                           class="w-full bg-surface-container border border-primary/30 rounded-lg px-4 py-2.5 font-headline font-bold text-primary focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary transition-all" required>
                </div>
                <div>
                    <label class="block font-label text-[0.65rem] uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Catatan Pembayaran</label>
                    <input type="text" name="cash_note" placeholder="Diterima oleh..."
                           class="w-full bg-surface-container-lowest border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary transition-all">
                </div>
            </div>
            <div class="px-4 py-4 md:px-6 md:py-4 border-t border-outline-variant/20 bg-surface-container-low flex justify-end gap-3 flex-shrink-0">
                <button type="button" class="px-4 py-2 rounded-lg border border-outline-variant/50 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-colors"
                        onclick="closeCashModal({{ $booking->id }})">Batal</button>
                <button type="submit" class="px-4 py-2.5 rounded-lg bg-green-600 text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-green-700 transition-colors shadow-sm">Verifikasi Cash</button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- ═══════════ SEARCH FILTER SCRIPT ═══════════ --}}
<script>
function filterTable() {
    var query = document.getElementById('searchKlien').value.toLowerCase();
    var rows = document.querySelectorAll('#dpvTable tbody tr[data-client]');
    for (var i = 0; i < rows.length; i++) {
        var name = rows[i].getAttribute('data-client') || '';
        rows[i].style.display = name.includes(query) ? '' : 'none';
    }
    var cards = document.querySelectorAll('#dpvMobileCards [data-client]');
    for (var j = 0; j < cards.length; j++) {
        var name = cards[j].getAttribute('data-client') || '';
        cards[j].style.display = name.includes(query) ? '' : 'none';
    }
}

function openVerifyModal(id) {
    var el = document.getElementById('modalVerify' + id);
    if (el) { el.classList.remove('hidden'); el.classList.add('flex'); }
}
function closeVerifyModal(id) {
    var el = document.getElementById('modalVerify' + id);
    if (el) { el.classList.add('hidden'); el.classList.remove('flex'); }
}
function openCashModal(id) {
    var el = document.getElementById('modalCash' + id);
    if (el) { el.classList.remove('hidden'); el.classList.add('flex'); }
}
function closeCashModal(id) {
    var el = document.getElementById('modalCash' + id);
    if (el) { el.classList.add('hidden'); el.classList.remove('flex'); }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id^="modalVerify"], [id^="modalCash"]').forEach(function(m) {
            m.classList.add('hidden'); m.classList.remove('flex');
        });
    }
});

// ── Live Preview Kalkulasi Profit ─────────────────────────────────
function updateProfitPreview(bookingId, dpAmount) {
    var input   = document.getElementById('profitInput' + bookingId);
    var profit  = parseFloat(input.value) || 0;
    var opsGross = Math.max(0, dpAmount - profit);
    var safety   = Math.round(opsGross * 0.10);
    var opsNet   = Math.max(0, opsGross - safety);

    var fmt = function(n) {
        return 'Rp ' + Math.round(n).toLocaleString('id-ID');
    };

    var elGross  = document.getElementById('opsGross'  + bookingId);
    var elSafety = document.getElementById('opsSafety' + bookingId);
    var elNet    = document.getElementById('opsNet'    + bookingId);

    if (elGross)  elGross.textContent  = fmt(opsGross);
    if (elSafety) elSafety.textContent = fmt(safety);
    if (elNet)    elNet.textContent    = fmt(opsNet);

    // Warna merah jika profit > DP (tidak valid)
    if (profit > dpAmount) {
        input.style.borderColor = '#f87171';
    } else {
        input.style.borderColor = '';
    }
}

// ── Validasi & Submit Konfirmasi ──────────────────────────────────
function submitWithValidation(bookingId, dpAmount, clientName) {
    var input  = document.getElementById('profitInput' + bookingId);
    var profit = parseFloat(input ? input.value : 0);

    if (!profit || profit <= 0) {
        alert('⚠️ Nominal Fixed Profit belum diisi!\n\nSilakan isi nominal keuntungan pimpinan sebelum konfirmasi.');
        if (input) input.focus();
        return;
    }

    if (profit > dpAmount) {
        alert('⚠️ Nominal Fixed Profit (' + profit.toLocaleString('id-ID') + ') melebihi DP yang masuk (' + dpAmount.toLocaleString('id-ID') + ').\n\nMaksimum profit adalah sebesar DP yang diterima.');
        if (input) input.focus();
        return;
    }

    var konfirmasi = confirm(
        'Konfirmasi DP & Kunci Laba untuk ' + clientName + '?\n\n' +
        'Fixed Profit: Rp ' + Math.round(profit).toLocaleString('id-ID') + '\n' +
        'Ops Budget: Rp ' + Math.max(0, dpAmount - profit).toLocaleString('id-ID') + '\n\n' +
        'Tindakan ini tidak dapat dibatalkan.'
    );

    if (konfirmasi) {
        var form = document.getElementById('formConfirm' + bookingId);
        if (form) {
            var existingInput = form.querySelector('input[name="fixed_profit_nominal"]');
            if (existingInput) {
                existingInput.remove();
            }
            var hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'fixed_profit_nominal';
            hiddenInput.value = profit;
            form.appendChild(hiddenInput);
            form.submit();
        }
    }
}
</script>

@endsection