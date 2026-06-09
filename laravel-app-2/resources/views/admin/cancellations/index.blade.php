@extends('layouts.admin')

@section('title', 'Penanganan Pembatalan – ART-HUB')
@section('page_title', 'Penanganan Pembatalan')
@section('page_subtitle', 'Riwayat pembatalan & pengembalian dana klien.')

@section('content')

{{-- STAT SUMMARY CARDS --}}
@php
    $totalCancellations = $totalCancellations ?? ($cancellations instanceof \Illuminate\Pagination\LengthAwarePaginator ? $cancellations->total() : $cancellations->count());
    $pending = $pending ?? ($cancellations instanceof \Illuminate\Pagination\LengthAwarePaginator ? \App\Models\Cancellation::where('status', 'pending')->count() : $cancellations->where('status', 'pending')->count());
    $totalPenalty = $totalPenalty ?? ($cancellations instanceof \Illuminate\Pagination\LengthAwarePaginator ? \App\Models\Cancellation::sum('penalty_amount') : $cancellations->sum('penalty_amount'));
    $totalRefund = $totalRefund ?? ($cancellations instanceof \Illuminate\Pagination\LengthAwarePaginator ? \App\Models\Cancellation::sum('refund_amount') : $cancellations->sum('refund_amount'));
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-5 animate-fade-up">
    <!-- Card 1: Jumlah Antrean Pending -->
    <div class="bg-white border border-outline-variant/35 rounded-xl p-3.5 flex items-center gap-3.5 shadow-sm hover:shadow-md hover:border-orange-400/50 transition-all duration-300 group">
        <div class="w-9 h-9 rounded-lg bg-orange-500/10 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
            <i class="bi bi-hourglass-split text-orange-600 text-base"></i>
        </div>
        <div>
            <div class="font-mono text-xl font-bold text-primary leading-none">{{ $pending }}</div>
            <div class="font-label text-[0.62rem] uppercase tracking-wider text-outline font-bold mt-1">Antrean Pending</div>
        </div>
    </div>

    <!-- Card 2: Total Pinalti Kas -->
    <div class="bg-white border border-outline-variant/35 rounded-xl p-3.5 flex items-center gap-3.5 shadow-sm hover:shadow-md hover:border-[#4A1D13]/40 transition-all duration-300 group">
        <div class="w-9 h-9 rounded-lg bg-[#4A1D13]/10 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
            <i class="bi bi-shield-exclamation text-[#4A1D13] text-base"></i>
        </div>
        <div>
            <div class="font-mono text-xl font-bold text-red-700 leading-none">Rp {{ number_format($totalPenalty, 0, ',', '.') }}</div>
            <div class="font-label text-[0.62rem] uppercase tracking-wider text-outline font-bold mt-1">Pinalti Kas</div>
        </div>
    </div>

    <!-- Card 3: Total Dana Dikembalikan -->
    <div class="bg-white border border-outline-variant/35 rounded-xl p-3.5 flex items-center gap-3.5 shadow-sm hover:shadow-md hover:border-green-400/50 transition-all duration-300 group">
        <div class="w-9 h-9 rounded-lg bg-green-500/10 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
            <i class="bi bi-arrow-return-left text-green-700 text-base"></i>
        </div>
        <div>
            <div class="font-mono text-xl font-bold text-green-700 leading-none">Rp {{ number_format($totalRefund, 0, ',', '.') }}</div>
            <div class="font-label text-[0.62rem] uppercase tracking-wider text-outline font-bold mt-1">Dana Dikembalikan</div>
        </div>
    </div>

    <!-- Card 4: Total Seluruh Pengajuan -->
    <div class="bg-white border border-outline-variant/35 rounded-xl p-3.5 flex items-center gap-3.5 shadow-sm hover:shadow-md hover:border-[#705d00]/40 transition-all duration-300 group">
        <div class="w-9 h-9 rounded-lg bg-[#705d00]/10 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
            <i class="bi bi-archive text-[#705d00] text-base"></i>
        </div>
        <div>
            <div class="font-mono text-xl font-bold text-primary leading-none">{{ $totalCancellations }}</div>
            <div class="font-label text-[0.62rem] uppercase tracking-wider text-outline font-bold mt-1">Seluruh Pengajuan</div>
        </div>
    </div>
</div>

{{-- SECTION TITLE --}}
<div class="flex items-center gap-2 mb-3.5 animate-fade-up" style="animation-delay: 0.05s">
    <div class="w-8 h-8 rounded-lg bg-[#4A1D13]/10 flex items-center justify-center shrink-0">
        <i class="bi bi-shield-alert text-[#4A1D13] text-sm"></i>
    </div>
    <h2 class="font-headline text-sm text-primary font-bold">Daftar Antrean & Riwayat Pembatalan</h2>
</div>

{{-- ══ TABLE (Desktop) ══ --}}
<div class="hidden md:block bg-white rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden mb-6 animate-fade-up" style="animation-delay: 0.1s">
    @if($cancellations->isEmpty())
        <div class="py-16 text-center">
            <div class="w-14 h-14 rounded-full bg-emerald-500/10 mx-auto flex items-center justify-center mb-3">
                <i class="bi bi-emoji-smile text-2xl text-emerald-600"></i>
            </div>
            <p class="font-headline text-base text-on-surface font-semibold mb-0.5">Belum ada permohonan pembatalan</p>
            <p class="font-label text-[0.65rem] uppercase tracking-wider text-outline font-bold">Semua jadwal pementasan berjalan lancar! 🙏</p>
        </div>
    @else
    <table class="w-full border-collapse">
        <thead class="bg-surface-container-low border-b border-outline-variant/20">
            <tr>
                <th class="font-label text-[0.65rem] uppercase tracking-wider text-outline font-bold px-4 py-2 text-left">Booking ID</th>
                <th class="font-label text-[0.65rem] uppercase tracking-wider text-outline font-bold px-4 py-2 text-left">Klien</th>
                <th class="font-label text-[0.65rem] uppercase tracking-wider text-outline font-bold px-4 py-2 text-left">Tgl Batal & H- Event</th>
                <th class="font-label text-[0.65rem] uppercase tracking-wider text-outline font-bold px-4 py-2 text-right">Denda Penalti</th>
                <th class="font-label text-[0.65rem] uppercase tracking-wider text-outline font-bold px-4 py-2 text-right">Dana Refund</th>
                <th class="font-label text-[0.65rem] uppercase tracking-wider text-outline font-bold px-4 py-2 text-left">Alasan Pembatalan</th>
                <th class="font-label text-[0.65rem] uppercase tracking-wider text-outline font-bold px-4 py-2 text-center">Status / Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/15">
            @foreach($cancellations as $c)
            @php
                $days = $c->days_before_event;
                $dayClass = $days <= 3 ? 'text-red-700 bg-red-50 border-red-200'
                          : ($days <= 7 ? 'text-amber-700 bg-amber-50 border-amber-200'
                          : 'text-emerald-700 bg-emerald-50 border-emerald-200');
            @endphp
            <tr class="hover:bg-surface-container-low/30 transition-colors group">
                <!-- Booking ID -->
                <td class="px-4 py-2 text-left align-middle">
                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded bg-[#361f1a]/5 text-[#361f1a] border border-[#361f1a]/10 font-mono text-[0.7rem] font-bold">
                        #{{ str_pad($c->booking_id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                </td>
                <!-- Klien -->
                <td class="px-4 py-2 text-left align-middle font-body">
                    <div class="text-xs font-bold text-[#361f1a]">{{ $c->booking->client_name ?? '-' }}</div>
                    <div class="text-[0.68rem] text-outline font-medium">Klien Pemesan</div>
                </td>
                <!-- Tgl Batal & H- Event -->
                <td class="px-4 py-2 text-left align-middle font-body">
                    <div class="text-xs text-on-surface flex items-center gap-1.5 font-medium">
                        <i class="bi bi-calendar-x opacity-65 text-xs"></i>
                        <span>{{ \Carbon\Carbon::parse($c->cancellation_date)->format('d M Y') }}</span>
                    </div>
                    <div class="mt-0.5">
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded border font-mono text-[0.65rem] font-bold {{ $dayClass }}">
                            H-{{ $days }}
                        </span>
                    </div>
                </td>
                <!-- Penalti -->
                <td class="px-4 py-2 text-right align-middle font-body">
                    <div class="text-xs font-bold text-red-700">Rp {{ number_format($c->penalty_amount, 0, ',', '.') }}</div>
                    <div class="text-[0.68rem] text-outline font-medium">{{ number_format($c->penalty_percentage, 0) }}% Denda</div>
                </td>
                <!-- Refund -->
                <td class="px-4 py-2 text-right align-middle font-body">
                    @if($c->refund_amount > 0)
                        <div class="text-xs font-bold text-green-700">Rp {{ number_format($c->refund_amount, 0, ',', '.') }}</div>
                        <div class="text-[0.68rem] text-outline font-medium">Dikembalikan</div>
                    @else
                        <div class="text-xs font-semibold text-outline-variant italic">Rp 0 (Hangus)</div>
                    @endif
                </td>
                <!-- Alasan -->
                <td class="px-4 py-2 text-left align-middle font-body">
                    <div class="text-xs text-on-surface-variant max-w-[180px] truncate" title="{{ $c->reason }}">
                        {{ $c->reason ?? '-' }}
                    </div>
                </td>
                <!-- Status / Aksi -->
                <td class="px-4 py-2 text-center align-middle font-body">
                    @if($c->status === 'pending')
                        <div class="flex items-center justify-center gap-1.5">
                            <form action="{{ route('admin.cancellations.approve', $c->id) }}" method="POST" class="inline m-0" data-confirm="Apakah Anda yakin ingin MENYETUJUI permohonan pembatalan ini? Tindakan ini akan mengembalikan dana ke klien dan mencatat denda pinalti kas.">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center h-7 px-2.5 bg-[#4A1D13] hover:bg-[#3B130E] text-[#FFFDF9] hover:text-white font-body text-[0.65rem] font-bold uppercase tracking-wider rounded transition-all hover:scale-105 active:scale-95 shadow-sm">
                                    Setujui
                                </button>
                            </form>
                            <form action="{{ route('admin.cancellations.reject', $c->id) }}" method="POST" class="inline m-0" data-confirm="Apakah Anda yakin ingin MENOLAK permohonan pembatalan ini? Status booking akan dikembalikan dan pengajuan pembatalan diarsipkan sebagai ditolak.">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center h-7 px-2.5 border border-[#827471]/35 hover:border-[#827471] bg-white hover:bg-surface-container text-[#504442] hover:text-on-surface font-body text-[0.65rem] font-semibold uppercase tracking-wider rounded transition-all hover:scale-105 active:scale-95 shadow-sm">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    @elseif($c->status === 'processed')
                        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full border border-red-500/20 bg-red-550/5 font-body text-[0.65rem] font-bold tracking-wider text-red-800">
                            DISETUJUI
                        </span>
                    @else
                        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full border border-outline-variant/30 bg-surface-container-low font-body text-[0.65rem] font-bold tracking-wider text-outline">
                            DITOLAK
                        </span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @if($cancellations instanceof \Illuminate\Pagination\LengthAwarePaginator && $cancellations->hasPages())
        <div class="px-4 py-3.5 bg-surface-container-low border-t border-outline-variant/20">
            {{ $cancellations->links() }}
        </div>
    @endif
</div>


{{-- ══ MOBILE CARDS ══ --}}
<div class="md:hidden flex flex-col gap-3 mb-6 animate-fade-up" style="animation-delay: 0.1s">
    @forelse($cancellations as $c)
    @php
        $days = $c->days_before_event;
        $dayClass = $days <= 3 ? 'text-red-700 bg-red-50 border-red-200'
                  : ($days <= 7 ? 'text-amber-700 bg-amber-50 border-amber-200'
                  : 'text-emerald-700 bg-emerald-50 border-emerald-200');
    @endphp
    <div class="bg-white rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden hover:shadow-md transition-all duration-300">
        <div class="flex items-center justify-between px-3.5 py-2.5 bg-surface-container-low border-b border-outline-variant/20">
            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded bg-[#361f1a]/5 text-[#361f1a] border border-[#361f1a]/10 font-mono text-[0.7rem] font-bold">
                #{{ str_pad($c->booking_id, 4, '0', STR_PAD_LEFT) }}
            </span>
            @if($c->status === 'pending')
                <div class="flex items-center gap-1.5">
                    <form action="{{ route('admin.cancellations.approve', $c->id) }}" method="POST" class="inline m-0" data-confirm="Apakah Anda yakin ingin MENYETUJUI permohonan pembatalan ini?">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center h-7 px-2.5 bg-[#4A1D13] hover:bg-[#3B130E] text-[#FFFDF9] hover:text-white font-body text-[0.65rem] font-bold uppercase tracking-wider rounded transition-all shadow-sm">
                            Setujui
                        </button>
                    </form>
                    <form action="{{ route('admin.cancellations.reject', $c->id) }}" method="POST" class="inline m-0" data-confirm="Apakah Anda yakin ingin MENOLAK permohonan pembatalan ini?">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center h-7 px-2.5 border border-[#827471]/35 hover:border-[#827471] bg-white hover:bg-surface-container text-[#504442] hover:text-on-surface font-body text-[0.65rem] font-semibold uppercase tracking-wider rounded transition-all shadow-sm">
                            Tolak
                        </button>
                    </form>
                </div>
            @elseif($c->status === 'processed')
                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full border border-red-500/20 bg-red-50/50 font-body text-[0.65rem] font-bold tracking-wider text-red-800">DISETUJUI</span>
            @else
                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full border border-outline-variant/30 bg-surface-container-low font-body text-[0.65rem] font-bold tracking-wider text-outline">DITOLAK</span>
            @endif
        </div>
        <div class="p-3.5 flex flex-col gap-2.5">
            <div class="flex justify-between items-start">
                <div>
                    <div class="font-body font-bold text-xs text-on-surface">{{ $c->booking->client_name ?? '-' }}</div>
                    <div class="font-label text-[0.65rem] text-outline flex items-center gap-1 mt-0.5">
                        <i class="bi bi-calendar-x opacity-65"></i>
                        <span>{{ \Carbon\Carbon::parse($c->cancellation_date)->format('d M Y') }}</span>
                    </div>
                </div>
                <span class="inline-flex items-center px-1.5 py-0.5 rounded border font-mono text-[0.65rem] font-bold {{ $dayClass }}">H-{{ $days }}</span>
            </div>
            
            <div class="grid grid-cols-2 gap-2 font-body">
                <div class="bg-red-500/5 border border-red-500/10 rounded-lg p-2">
                    <div class="font-label text-[0.55rem] uppercase tracking-wider text-outline mb-0.5 font-bold">Penalti Kas</div>
                    <div class="text-xs font-bold text-red-700">Rp {{ number_format($c->penalty_amount, 0, ',', '.') }}</div>
                    <div class="font-label text-[0.55rem] text-outline font-bold mt-0.5">{{ number_format($c->penalty_percentage, 0) }}% Denda</div>
                </div>
                <div class="bg-green-500/5 border border-green-500/10 rounded-lg p-2">
                    <div class="font-label text-[0.55rem] uppercase tracking-wider text-outline mb-0.5 font-bold">Dana Refund</div>
                    @if($c->refund_amount > 0)
                        <div class="text-xs font-bold text-green-700">Rp {{ number_format($c->refund_amount, 0, ',', '.') }}</div>
                        <div class="font-label text-[0.55rem] text-outline font-bold mt-0.5">Dikembalikan</div>
                    @else
                        <div class="text-xs font-semibold text-outline-variant italic">Rp 0 (Hangus)</div>
                    @endif
                </div>
            </div>

            @if($c->reason)
                <div class="text-[0.7rem] text-on-surface-variant bg-surface-container rounded-lg p-2.5 italic leading-relaxed">
                    "{{ $c->reason }}"
                </div>
            @endif
        </div>
    </div>
    @empty
    <div class="py-12 flex flex-col items-center justify-center bg-white border border-outline-variant/30 rounded-xl text-center shadow-sm">
        <i class="bi bi-emoji-smile text-3xl text-outline mb-2"></i>
        <p class="font-headline text-sm text-on-surface font-semibold">Belum ada pembatalan</p>
    </div>
    @endforelse
</div>
@if($cancellations instanceof \Illuminate\Pagination\LengthAwarePaginator && $cancellations->hasPages())
    <div class="mt-2 mb-4">
        {{ $cancellations->links() }}
    </div>
@endif

{{-- ══ FORMULA PENALTI (Editable) ══ --}}
<div x-data="{ editing: false }" class="bg-white rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden animate-fade-up" style="animation-delay: 0.15s">

    {{-- Header --}}
    <div class="px-4 py-3 border-b border-outline-variant/20 bg-surface-container-low/50 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-[#705d00]/10 flex items-center justify-center">
                <i class="bi bi-calculator-fill text-[#705d00] text-sm"></i>
            </div>
            <div>
                <div class="font-headline text-xs font-bold text-primary">Formula Penalti Pembatalan</div>
                <p class="font-label text-[0.6rem] uppercase tracking-wider text-outline font-bold">Atur persentase denda berdasarkan rentang H-hari</p>
            </div>
        </div>
        <button @click="editing = !editing" type="button"
                :class="editing ? 'bg-red-500/10 text-red-600 border-red-500/20' : 'bg-[#705d00]/10 text-[#705d00] border-[#705d00]/20'"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border font-label text-[0.65rem] font-bold uppercase tracking-wider transition-all hover:scale-105 active:scale-95">
            <i :class="editing ? 'bi-x-circle-fill' : 'bi-pencil-fill'" class="bi text-xs"></i>
            <span x-text="editing ? 'Batal Edit' : 'Edit Formula'"></span>
        </button>
    </div>

    {{-- DISPLAY VIEW (tidak sedang edit) --}}
    <div x-show="!editing" class="p-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($penaltyTiers as $i => $tier)
            @php
                $colors = [
                    'text-[#504442] bg-surface-container-low border-outline-variant/40',
                    'text-orange-700 bg-orange-500/5 border-orange-500/20',
                    'text-red-700 bg-[#4A1D13]/5 border-[#4A1D13]/20',
                    'text-[#4A1D13] bg-[#4A1D13]/10 border-[#4A1D13]/30',
                ];
                $colorClass = $colors[min($i, count($colors)-1)];
            @endphp
            <div class="text-center p-3.5 rounded-xl border {{ $colorClass }} relative group">
                <div class="font-mono text-2xl font-bold mb-0.5 leading-none">{{ number_format($tier['percentage'], 0) }}%</div>
                <div class="font-label text-[0.62rem] uppercase tracking-widest font-bold opacity-80 mt-2">
                    @if(!empty($tier['label']))
                        {{ $tier['label'] }}
                    @else
                        ≥ H-{{ $tier['days_from'] }}
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-3.5 flex items-start gap-2 text-[0.7rem] text-on-surface-variant leading-relaxed">
            <i class="bi bi-info-circle-fill text-secondary flex-shrink-0 mt-0.5 text-xs"></i>
            <p>Formula ini berlaku untuk seluruh pembatalan. Klik <strong class="text-primary">Edit Formula</strong> untuk mengubah persentase dan threshold H-hari sesuai kebijakan sanggar.</p>
        </div>
    </div>

    {{-- EDIT VIEW (sedang edit) --}}
    <div x-show="editing" style="display:none">
        <form action="{{ route('admin.cancellations.penalty_settings') }}" method="POST" id="penaltyForm">
            @csrf

            <div class="p-4">
                {{-- Warning box --}}
                <div class="p-3.5 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-start gap-2.5 mb-4">
                    <i class="bi bi-exclamation-triangle-fill text-amber-600 flex-shrink-0 mt-0.5"></i>
                    <div class="text-[0.7rem] text-amber-800 leading-relaxed font-body">
                        <strong>Perhatian:</strong> Perubahan formula hanya berlaku untuk pembatalan <em>berikutnya</em>. Riwayat pembatalan lama tidak akan terpengaruh. Pastikan sudah dikonfirmasi dengan manajemen sanggar.
                    </div>
                </div>

                {{-- Tier rows --}}
                <div id="tierContainer" class="flex flex-col gap-2.5 mb-4">
                    @foreach($penaltyTiers as $i => $tier)
                    <div class="tier-row flex items-center gap-3 bg-surface-container-low rounded-xl p-3.5 border border-outline-variant/30">
                        <div class="w-6 h-6 rounded bg-primary/10 flex items-center justify-center shrink-0 font-mono text-[0.65rem] font-bold text-primary">{{ $i+1 }}</div>

                        <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                            <div>
                                <label class="block font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-1">H-Hari Mulai Dari</label>
                                <div class="relative">
                                    <input type="number" name="tiers[{{ $i }}][days_from]" value="{{ $tier['days_from'] }}" min="0" required
                                           class="w-full bg-white border border-outline-variant/50 rounded-lg px-2.5 py-1.5 pr-10 text-xs font-bold text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                                    <span class="absolute right-2.5 top-1/2 -translate-y-1/2 font-label text-[0.6rem] text-outline font-bold">hari</span>
                                </div>
                            </div>
                            <div>
                                <label class="block font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-1">Persentase Denda (%)</label>
                                <div class="relative">
                                    <input type="number" name="tiers[{{ $i }}][percentage]" value="{{ $tier['percentage'] }}" min="0" max="100" step="0.5" required
                                           class="w-full bg-white border border-outline-variant/50 rounded-lg px-2.5 py-1.5 pr-8 text-xs font-bold text-red-600 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                                    <span class="absolute right-2.5 top-1/2 -translate-y-1/2 font-label text-[0.6rem] text-outline font-bold">%</span>
                                </div>
                            </div>
                            <div>
                                <label class="block font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-1">Label Tampilan</label>
                                <input type="text" name="tiers[{{ $i }}][label]" value="{{ $tier['label'] ?? '' }}"
                                       placeholder="Contoh: ≥ H-14"
                                       class="w-full bg-white border border-outline-variant/50 rounded-lg px-2.5 py-1.5 text-xs text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            </div>
                        </div>

                        <button type="button" onclick="removeTier(this)"
                                class="w-7 h-7 rounded border border-red-200 text-red-400 hover:bg-red-50 hover:text-red-600 hover:border-red-400 transition-colors flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-trash3-fill text-xs"></i>
                        </button>
                    </div>
                    @endforeach
                </div>

                {{-- Add Tier Button --}}
                <button type="button" onclick="addTier()"
                        class="w-full flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg border border-dashed border-primary/30 text-primary font-label text-[0.65rem] font-bold uppercase tracking-wider hover:border-primary hover:bg-primary/5 transition-all">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Tier Penalti
                </button>
            </div>

            {{-- Form Footer --}}
            <div class="px-4 py-3.5 border-t border-outline-variant/20 bg-surface-container-low/50 flex items-center justify-between">
                <p class="font-body text-[0.65rem] text-on-surface-variant font-medium">Tier diurutkan otomatis dari H-terbanyak ke H-terkecil saat disimpan.</p>
                <div class="flex gap-2">
                    <button type="button" @click="editing = false"
                            class="h-8 px-3.5 rounded-lg border border-outline-variant text-[0.65rem] font-bold uppercase text-on-surface-variant hover:bg-surface-container transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="h-8 px-4 rounded-lg bg-[#361f1a] hover:bg-[#361f1a]/90 text-[#FFFDF9] hover:text-white text-[0.65rem] font-bold uppercase tracking-wider transition-all shadow-sm flex items-center gap-1.5">
                        <i class="bi bi-save2-fill text-xs"></i> Simpan Formula
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
let tierCount = {{ count($penaltyTiers) }};
function addTier() {
    const idx = document.querySelectorAll('.tier-row').length;
    const html = `
        <div class="tier-row flex items-center gap-3 bg-surface-container-low rounded-xl p-3.5 border border-outline-variant/30">
            <div class="w-6 h-6 rounded bg-primary/10 flex items-center justify-center shrink-0 font-mono text-[0.65rem] font-bold text-primary">${idx + 1}</div>
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                <div>
                    <label class="block font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-1">H-Hari Mulai Dari</label>
                    <div class="relative">
                        <input type="number" name="tiers[${idx}][days_from]" value="0" min="0" required
                               class="w-full bg-white border border-outline-variant/50 rounded-lg px-2.5 py-1.5 pr-10 text-xs font-bold text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 font-label text-[0.6rem] text-outline font-bold">hari</span>
                    </div>
                </div>
                <div>
                    <label class="block font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-1">Persentase Denda (%)</label>
                    <div class="relative">
                        <input type="number" name="tiers[${idx}][percentage]" value="50" min="0" max="100" step="0.5" required
                               class="w-full bg-white border border-outline-variant/50 rounded-lg px-2.5 py-1.5 pr-8 text-xs font-bold text-red-600 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 font-label text-[0.6rem] text-outline font-bold">%</span>
                    </div>
                </div>
                <div>
                    <label class="block font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-1">Label Tampilan</label>
                    <input type="text" name="tiers[${idx}][label]" value=""
                           placeholder="Contoh: H-5 s/d H-9"
                           class="w-full bg-white border border-[#faf9f6]/50 rounded-lg px-2.5 py-1.5 text-xs text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                </div>
            </div>
            <button type="button" onclick="removeTier(this)"
                    class="w-7 h-7 rounded border border-red-200 text-red-400 hover:bg-red-50 hover:text-red-600 hover:border-red-400 transition-colors flex items-center justify-center flex-shrink-0">
                <i class="bi bi-trash3-fill text-xs"></i>
            </button>
        </div>`;
    document.getElementById('tierContainer').insertAdjacentHTML('beforeend', html);
    reindexTiers();
}

function removeTier(btn) {
    const rows = document.querySelectorAll('.tier-row');
    if (rows.length <= 1) { alert('Minimal harus ada 1 tier penalti.'); return; }
    btn.closest('.tier-row').remove();
    reindexTiers();
}

function reindexTiers() {
    document.querySelectorAll('.tier-row').forEach((row, i) => {
        // Update nomor badge
        const badge = row.querySelector('.w-6.h-6');
        if (badge) badge.textContent = i + 1;
        // Update name attributes
        row.querySelectorAll('[name]').forEach(input => {
            input.name = input.name.replace(/tiers\[\d+\]/, 'tiers[' + i + ']');
        });
    });
}</script>
@endpush

@endsection
