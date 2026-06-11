@extends('layouts.admin')
@section('title', 'DP Verification – ART-HUB')
@section('page_title', 'DP Verification')
@section('page_subtitle', 'Pastikan keamanan finansial sanggar dengan verifikasi bukti transfer klien.')

@section('content')

{{-- ═══════════════════════════ SUMMARY CARDS ═══════════════════════════ --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
    <div class="bg-surface-container-lowest rounded-xl p-4 md:p-6 border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] flex items-center gap-4 md:gap-5 group hover:-translate-y-1 transition-all">
        <div class="w-11 h-11 md:w-14 md:h-14 rounded-xl bg-orange-500/10 text-orange-600 flex items-center justify-center text-lg md:text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">
            <i class="bi bi-clock-history"></i>
        </div>
        <div>
            <div class="font-label text-[0.55rem] md:text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Menunggu Verifikasi</div>
            <div class="font-headline text-xl md:text-2xl font-bold text-on-surface leading-none">{{ $antreanCount }} <span class="font-body text-xs md:text-sm font-medium text-on-surface-variant">Antrean</span></div>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-4 md:p-6 border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] flex items-center gap-4 md:gap-5 group hover:-translate-y-1 transition-all">
        <div class="w-11 h-11 md:w-14 md:h-14 rounded-xl bg-green-500/10 text-green-600 flex items-center justify-center text-lg md:text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">
            <i class="bi bi-wallet2"></i>
        </div>
        <div>
            <div class="font-label text-[0.55rem] md:text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Total DP Masuk</div>
            <div class="font-headline text-xl md:text-2xl font-bold text-green-600 leading-none">Rp {{ number_format($totalDpMasuk, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="bg-gradient-to-br from-primary-container to-primary rounded-xl p-4 md:p-6 border border-primary/20 shadow-[0_8px_20px_rgba(54,31,26,0.08)] flex items-center gap-4 md:gap-5 group hover:-translate-y-1 transition-all">
        <div class="w-11 h-11 md:w-14 md:h-14 rounded-xl bg-white/10 text-secondary flex items-center justify-center text-lg md:text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">
            <i class="bi bi-lock-fill"></i>
        </div>
        <div>
            <div class="font-label text-[0.55rem] md:text-[0.65rem] uppercase tracking-widest text-white/70 font-bold mb-1">Profit Aman (Locked)</div>
            <div class="font-headline text-xl md:text-2xl font-bold text-secondary leading-none">Rp {{ number_format($totalProfitLocked, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

{{-- ═══════════════════ TABEL ANTREAN VERIFIKASI ═══════════════════ --}}
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-outline-variant/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface-container-low/30">
        <h3 class="font-headline text-lg font-bold text-primary flex items-center gap-2">
            <i class="bi bi-shield-check text-secondary"></i> Antrean Verifikasi
            @if($antreanCount > 0)
                <span class="inline-flex items-center justify-center min-w-6 h-6 px-1.5 rounded-full bg-primary text-secondary font-label text-[0.65rem] font-bold">{{ $antreanCount }}</span>
            @endif
        </h3>
        <div class="relative">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-outline-variant"></i>
            <input type="text" id="searchKlien" placeholder="Cari Klien..." oninput="filterTable()" class="w-full sm:w-64 bg-surface-container-lowest border border-outline-variant/50 rounded-lg pl-9 pr-4 py-2 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
        </div>
    </div>

    <table class="w-full hidden md:table" id="dpvTable">
        <thead class="bg-surface-container-low">
            <tr>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Kode Booking</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Klien & Acara</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Total Deal</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Nominal DP</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Bukti</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/20">
            @forelse($pendingWithProof as $booking)
            <tr data-client="{{ strtolower($booking->client_name) }}" class="hover:bg-surface-container-low/50 transition-colors">
                <td class="px-6 py-4 pl-5">
                    <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">
                        BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="font-body font-bold text-sm text-on-surface mb-1">{{ $booking->client_name }}</div>
                    <span class="inline-block px-2 py-0.5 rounded border border-outline-variant/50 bg-surface-container text-on-surface-variant font-label text-[0.6rem] font-bold uppercase tracking-wider">
                        {{ ucwords(str_replace('_', ' ', $booking->event_type)) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right font-body text-sm font-semibold text-on-surface">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-right">
                    <div class="font-headline font-bold text-green-600 text-sm">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
                    <div class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold">50% FEE</div>
                </td>
                <td class="px-6 py-4 text-center">
                    @if($booking->payment_proof)
                        <button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md border border-outline-variant/50 bg-surface-container-lowest font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant hover:border-primary hover:text-primary hover:bg-surface-container transition-all"
                            onclick="openVerifyModal({{ $booking->id }})">
                            <i class="bi bi-eye"></i> Lihat Bukti
                        </button>
                    @else
                        <span class="text-outline text-xs font-body italic"><i class="bi bi-image me-1"></i>Belum ada</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-end gap-2">
                        <button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-primary text-white font-label text-[0.65rem] font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-sm"
                            onclick="openVerifyModal({{ $booking->id }})">
                            <i class="bi bi-check-lg"></i> Verifikasi
                        </button>
                        @php $rjMsg = "Tolak & hapus bukti transfer dari " . addslashes($booking->client_name) . "? Klien akan diwajibkan upload ulang."; @endphp
                        <form action="{{ route('admin.bookings.reject_proof', $booking->id) }}" method="POST" class="m-0"
                              data-confirm="{{ $rjMsg }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md border border-red-500/50 bg-transparent text-red-500 font-label text-[0.65rem] font-bold uppercase tracking-widest hover:bg-red-500/10 transition-colors">
                                <i class="bi bi-x-lg"></i> Tolak
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-16 text-center">
                    <i class="bi bi-patch-check text-5xl text-green-500/50 mb-4 block"></i>
                    <p class="font-headline text-lg text-on-surface font-semibold mb-1">Tidak ada bukti transfer yang menunggu</p>
                    <p class="font-label text-xs uppercase tracking-widest text-outline">Semua DP sudah diverifikasi! ✅</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Mobile Cards View --}}
    <div class="block md:hidden divide-y divide-outline-variant/20" id="dpvMobileCards">
        @forelse($pendingWithProof as $booking)
        <div data-client="{{ strtolower($booking->client_name) }}" class="p-4 hover:bg-surface-container-low/50 transition-colors space-y-3">
            <div class="flex justify-between items-center">
                <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">
                    BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}
                </span>
                @if($booking->payment_proof)
                    <button type="button" class="inline-flex items-center gap-1 px-2.5 py-1 rounded border border-outline-variant/50 bg-surface-container-lowest font-label text-[0.6rem] font-bold uppercase tracking-widest text-on-surface-variant hover:border-primary hover:text-primary hover:bg-surface-container transition-all"
                        onclick="openVerifyModal({{ $booking->id }})">
                        <i class="bi bi-eye"></i> Detail Bukti
                    </button>
                @else
                    <span class="text-outline text-xs font-body italic"><i class="bi bi-image me-1"></i>Belum ada</span>
                @endif
            </div>
            <div>
                <div class="font-body font-bold text-sm text-on-surface mb-1">{{ $booking->client_name }}</div>
                <div class="flex items-center justify-between gap-2">
                    <span class="inline-block px-2 py-0.5 rounded border border-outline-variant/50 bg-surface-container text-on-surface-variant font-label text-[0.6rem] font-bold uppercase tracking-wider">
                        {{ ucwords(str_replace('_', ' ', $booking->event_type)) }}
                    </span>
                    <span class="font-body text-xs text-outline">
                        Deal: <strong class="text-on-surface">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
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
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
    <div class="px-6 py-5 border-b border-outline-variant/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface-container-low/30">
        <h3 class="font-headline text-base font-bold text-on-surface-variant flex items-center gap-2">
            <i class="bi bi-hourglass-split text-outline"></i> Menunggu Upload Klien
            @if($pendingNoProof->total() > 0)
                <span class="inline-flex items-center justify-center min-w-6 h-6 px-1.5 rounded-full bg-surface-container-highest text-on-surface font-label text-[0.65rem] font-bold">{{ $pendingNoProof->total() }}</span>
            @endif
        </h3>
    </div>

    <div class="divide-y divide-outline-variant/20">
        @forelse($pendingNoProof as $booking)
        <div class="flex flex-col sm:flex-row sm:items-center p-4 sm:p-5 gap-4 sm:gap-5 hover:bg-surface-container-low/50 transition-colors">
            <div class="flex items-center gap-4 flex-grow min-w-0">
                <div class="w-12 h-12 rounded-lg bg-surface-container border border-outline-variant/50 border-dashed flex items-center justify-center text-outline flex-shrink-0">
                    <i class="bi bi-cloud-upload text-xl"></i>
                </div>
                <div class="min-w-0">
                    <div class="font-body font-bold text-sm text-on-surface mb-1 truncate">{{ $booking->client_name }}</div>
                    <div class="font-label text-[0.65rem] text-on-surface-variant flex items-center gap-2 flex-wrap uppercase tracking-widest font-bold">
                        <span><i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}</span>
                        <span class="text-outline-variant">•</span>
                        <span><i class="bi bi-phone"></i> {{ $booking->client_phone }}</span>
                        
                        @php $createdDays = \Carbon\Carbon::parse($booking->created_at)->diffInDays(now()); @endphp
                        @if($createdDays > 3)
                            <span class="px-1.5 py-0.5 bg-red-500/10 border border-red-500/20 text-red-600 rounded text-[0.55rem]">Overdue {{ $createdDays }} hari</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex justify-between items-center sm:text-right sm:block">
                <div class="sm:hidden font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold">Total Kontrak</div>
                <div>
                    <div class="hidden sm:block font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-0.5">Total Kontrak</div>
                    <div class="font-headline font-bold text-primary text-sm">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="flex-shrink-0 flex gap-2 justify-end sm:ml-4">
                <button type="button" onclick="openCashModal({{ $booking->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-600 text-white font-label text-[0.65rem] font-bold uppercase tracking-widest hover:bg-green-700 transition-colors shadow-sm text-xs" title="Terima Pembayaran Tunai (Offline)">
                    <i class="bi bi-cash-stack"></i> <span class="hidden xs:inline">Terima</span> Cash
                </button>
                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-outline-variant/50 bg-surface-container-highest text-on-surface-variant hover:border-primary hover:text-primary hover:bg-surface-container-lowest transition-all" title="Detail & Nego Harga">
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="p-12 text-center text-outline">
            <i class="bi bi-inbox text-4xl mb-3 block"></i>
            <p class="font-body text-sm">Tidak ada booking yang sedang menunggu upload.</p>
        </div>
        @endforelse
    </div>

    @if($pendingNoProof->hasPages())
    <div class="px-6 py-4 border-t border-outline-variant/20 bg-surface-container-low/20">
        {{ $pendingNoProof->appends(request()->except('page_no_proof'))->links() }}
    </div>
    @endif
</div>

{{-- ═══════════════════════════════════════════════════════
     MODAL VERIFIKASI DETAIL (per Booking)
═══════════════════════════════════════════════════════ --}}
@foreach($pendingWithProof as $booking)
@php
    $targetProfit = $booking->total_price * 0.30;
    $dpAmount     = $booking->dp_amount;
    $fixedProfit  = min($dpAmount, $targetProfit);
    $opsBudget    = max(0, $dpAmount - $fixedProfit);
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
                        <label class="block font-label text-[0.65rem] uppercase tracking-widest text-secondary font-bold mb-1.5"><i class="bi bi-lock-fill"></i> Fixed Profit Pimpinan (Rp)</label>
                        <p class="font-body text-[0.65rem] text-white/80 mb-2">Saran: Rp {{ number_format($fixedProfit, 0, ',', '.') }}</p>
                        <input type="number" name="fixed_profit_nominal" min="0" value="{{ $fixedProfit }}" placeholder="Nominal Rp"
                               class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 font-headline font-bold text-white placeholder-white/50 focus:border-secondary outline-none transition-all"
                               required form="formConfirm{{ $booking->id }}">
                    </div>
                    @if($opsBudget > 0)
                    <div class="bg-blue-500/5 border border-blue-500/20 rounded-xl p-3 md:p-4">
                        <div class="font-label text-[0.65rem] uppercase tracking-widest text-blue-700/70 font-bold mb-1">Sisa Budget Operasional</div>
                        <div class="font-headline font-bold text-sm md:text-base text-blue-600">Rp {{ number_format($opsBudget, 0, ',', '.') }}</div>
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
                <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST" class="m-0" id="formConfirm{{ $booking->id }}"
                      data-confirm="Konfirmasi DP & Kunci Laba untuk {{ addslashes($booking->client_name) }}?">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 rounded-lg bg-green-500 text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-green-600 transition-colors flex items-center gap-1.5 shadow-md">
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
</script>

@endsection