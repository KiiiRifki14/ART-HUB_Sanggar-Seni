@extends('layouts.admin')

@section('title', 'Penanganan Pembatalan – ART-HUB')
@section('page_title', 'Penanganan Pembatalan')
@section('page_subtitle', 'Riwayat pembatalan & pengembalian dana klien.')

@section('content')

{{-- ALERTS --}}
@if(session('success'))
    <div class="p-4 mb-5 text-sm text-green-700 bg-green-500/10 border border-green-500/20 rounded-xl flex items-center gap-2.5 font-bold shadow-sm">
        <div class="w-7 h-7 rounded-lg bg-green-500/20 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-check-circle-fill text-green-500 text-sm"></i>
        </div>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="p-4 mb-5 text-sm text-red-700 bg-red-500/10 border border-red-500/20 rounded-xl flex items-center gap-2.5 font-bold shadow-sm">
        <div class="w-7 h-7 rounded-lg bg-red-500/20 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-x-circle-fill text-red-500 text-sm"></i>
        </div>
        {{ session('error') }}
    </div>
@endif

{{-- STAT STRIP --}}
@php
    $totalCancellations = $cancellations->count();
    $pending    = $cancellations->where('status', 'pending')->count();
    $processed  = $cancellations->where('status', 'processed')->count();
    $refunded   = $cancellations->where('status', 'refunded')->count();
    $totalPenalty = $cancellations->sum('penalty_amount');
    $totalRefund  = $cancellations->sum('refund_amount');
@endphp
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-surface-container-lowest rounded-2xl p-4 border border-outline-variant/30 flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center shrink-0">
            <i class="bi bi-x-circle-fill text-secondary"></i>
        </div>
        <div>
            <div class="font-headline text-2xl font-bold text-primary leading-none">{{ $totalCancellations }}</div>
            <div class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mt-0.5">Total Batal</div>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-2xl p-4 border border-orange-500/20 flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center shrink-0">
            <i class="bi bi-hourglass-split text-orange-500"></i>
        </div>
        <div>
            <div class="font-headline text-2xl font-bold text-orange-600 leading-none">{{ $pending }}</div>
            <div class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mt-0.5">Pending</div>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-2xl p-4 border border-red-500/20 flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center shrink-0">
            <i class="bi bi-cash text-red-500"></i>
        </div>
        <div>
            <div class="font-headline text-lg font-bold text-red-600 leading-none">Rp {{ number_format($totalPenalty/1000000, 1) }}jt</div>
            <div class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mt-0.5">Total Penalti</div>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-2xl p-4 border border-green-500/20 flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center shrink-0">
            <i class="bi bi-arrow-return-left text-green-500"></i>
        </div>
        <div>
            <div class="font-headline text-lg font-bold text-green-600 leading-none">Rp {{ number_format($totalRefund/1000000, 1) }}jt</div>
            <div class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mt-0.5">Total Refund</div>
        </div>
    </div>
</div>

{{-- HEADER --}}
<div class="flex items-center gap-2.5 mb-4">
    <div class="w-9 h-9 rounded-xl bg-red-500/10 flex items-center justify-center shrink-0">
        <i class="bi bi-shield-exclamation text-red-500"></i>
    </div>
    <h2 class="font-headline text-base text-primary font-semibold">Daftar Pembatalan</h2>
</div>

{{-- ══ TABLE (Desktop) ══ --}}
<div class="hidden md:block bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden mb-8">
    @if($cancellations->isEmpty())
        <div class="py-20 text-center">
            <div class="w-16 h-16 rounded-2xl bg-green-500/10 mx-auto flex items-center justify-center mb-4">
                <i class="bi bi-emoji-smile text-3xl text-green-500"></i>
            </div>
            <p class="font-headline text-lg text-on-surface font-semibold mb-1">Belum ada pembatalan</p>
            <p class="font-label text-xs uppercase tracking-widest text-outline">Semoga jadwal selalu berjalan lancar! 🙏</p>
        </div>
    @else
    <table class="w-full">
        <thead class="bg-surface-container-low border-b border-outline-variant/20">
            <tr>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-3.5 text-left">Booking</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-3.5 text-left">Klien</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-3.5 text-left">Tgl Batal & H- Event</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-3.5 text-right">Penalti</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-3.5 text-right">Refund</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-3.5 text-left">Alasan</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-3.5 text-center">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/15">
            @foreach($cancellations as $c)
            @php
                $days = $c->days_before_event;
                $dayClass = $days <= 3 ? 'text-red-600 bg-red-500/10 border-red-500/20'
                          : ($days <= 7 ? 'text-orange-600 bg-orange-500/10 border-orange-500/20'
                          : 'text-on-surface-variant bg-surface-container border-outline-variant/30');
            @endphp
            <tr class="hover:bg-surface-container-low/40 transition-colors group">
                <td class="px-6 py-4">
                    <span class="inline-block px-2.5 py-1 rounded-lg bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">
                        #{{ str_pad($c->booking_id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                </td>
                <td class="px-6 py-4 font-body font-semibold text-on-surface text-sm">
                    {{ $c->booking->client_name ?? '-' }}
                </td>
                <td class="px-6 py-4">
                    <div class="font-label text-xs text-on-surface-variant mb-1.5 flex items-center gap-1.5">
                        <i class="bi bi-calendar-x opacity-60 text-xs"></i>
                        {{ \Carbon\Carbon::parse($c->cancellation_date)->format('d M Y') }}
                    </div>
                    <span class="inline-block px-2 py-0.5 rounded border font-label text-[0.6rem] font-bold uppercase tracking-wider {{ $dayClass }}">
                        H-{{ $days }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="font-headline font-bold text-red-600 text-sm">Rp {{ number_format($c->penalty_amount, 0, ',', '.') }}</div>
                    <div class="font-label text-xs text-outline">{{ number_format($c->penalty_percentage, 0) }}% dari total</div>
                </td>
                <td class="px-6 py-4 text-right">
                    @if($c->refund_amount > 0)
                        <div class="font-headline font-bold text-green-600 text-sm">Rp {{ number_format($c->refund_amount, 0, ',', '.') }}</div>
                    @else
                        <div class="font-label text-xs text-outline italic">Rp 0 (Hangus)</div>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="font-body text-xs text-on-surface-variant max-w-[180px] leading-relaxed">
                        {{ Str::limit($c->reason, 60) }}
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    @if($c->status === 'pending')
                        <span class="inline-block px-2.5 py-1 rounded-full border border-orange-500/20 bg-orange-500/10 font-label text-[0.6rem] font-bold uppercase tracking-wider text-orange-600">PENDING</span>
                    @elseif($c->status === 'processed')
                        <span class="inline-block px-2.5 py-1 rounded-full border border-secondary/20 bg-secondary/10 font-label text-[0.6rem] font-bold uppercase tracking-wider text-secondary">DIPROSES</span>
                    @else
                        <span class="inline-block px-2.5 py-1 rounded-full border border-green-500/20 bg-green-500/10 font-label text-[0.6rem] font-bold uppercase tracking-wider text-green-600">DIKEMBALIKAN</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

{{-- ══ MOBILE CARDS ══ --}}
<div class="md:hidden space-y-3 mb-8">
    @forelse($cancellations as $c)
    @php
        $days = $c->days_before_event;
        $dayClass = $days <= 3 ? 'text-red-600 bg-red-500/10 border-red-500/20' : ($days <= 7 ? 'text-orange-600 bg-orange-500/10 border-orange-500/20' : 'text-on-surface-variant bg-surface-container border-outline-variant/30');
    @endphp
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 bg-surface-container-low border-b border-outline-variant/20">
            <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">#{{ str_pad($c->booking_id, 4, '0', STR_PAD_LEFT) }}</span>
            @if($c->status === 'pending')
                <span class="inline-block px-2 py-0.5 rounded-full border border-orange-500/20 bg-orange-500/10 font-label text-[0.6rem] font-bold uppercase text-orange-600">PENDING</span>
            @elseif($c->status === 'processed')
                <span class="inline-block px-2 py-0.5 rounded-full border border-secondary/20 bg-secondary/10 font-label text-[0.6rem] font-bold uppercase text-secondary">DIPROSES</span>
            @else
                <span class="inline-block px-2 py-0.5 rounded-full border border-green-500/20 bg-green-500/10 font-label text-[0.6rem] font-bold uppercase text-green-600">DIKEMBALIKAN</span>
            @endif
        </div>
        <div class="px-4 py-3 space-y-2.5">
            <div class="flex justify-between items-start">
                <div>
                    <div class="font-body font-bold text-sm text-on-surface">{{ $c->booking->client_name ?? '-' }}</div>
                    <div class="font-label text-[0.65rem] text-outline flex items-center gap-1 mt-0.5"><i class="bi bi-calendar-x opacity-60"></i> {{ \Carbon\Carbon::parse($c->cancellation_date)->format('d M Y') }}</div>
                </div>
                <span class="inline-block px-2 py-0.5 rounded border font-label text-[0.6rem] font-bold uppercase {{ $dayClass }}">H-{{ $days }}</span>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="bg-red-500/5 border border-red-500/10 rounded-xl p-2.5">
                    <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline mb-0.5">Penalti</div>
                    <div class="font-headline font-bold text-sm text-red-600">Rp {{ number_format($c->penalty_amount, 0, ',', '.') }}</div>
                    <div class="font-label text-[0.55rem] text-outline">{{ number_format($c->penalty_percentage, 0) }}%</div>
                </div>
                <div class="bg-green-500/5 border border-green-500/10 rounded-xl p-2.5">
                    <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline mb-0.5">Refund</div>
                    @if($c->refund_amount > 0)
                        <div class="font-headline font-bold text-sm text-green-600">Rp {{ number_format($c->refund_amount, 0, ',', '.') }}</div>
                    @else
                        <div class="font-label text-xs text-outline italic">Rp 0 (Hangus)</div>
                    @endif
                </div>
            </div>
            @if($c->reason)
            <div class="font-body text-[0.7rem] text-on-surface-variant bg-surface-container rounded-xl px-3 py-2 italic leading-relaxed">"{{ Str::limit($c->reason, 80) }}"</div>
            @endif
        </div>
    </div>
    @empty
    <div class="py-14 flex flex-col items-center justify-center bg-surface-container-lowest border border-dashed border-outline-variant/30 rounded-xl text-center">
        <i class="bi bi-emoji-smile text-4xl text-outline mb-3"></i>
        <p class="font-headline text-base text-on-surface font-semibold">Belum ada pembatalan</p>
    </div>
    @endforelse
</div>

{{-- ══ FORMULA PENALTI (Editable) ══ --}}
<div x-data="{ editing: false }" class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-low/50 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-secondary/10 flex items-center justify-center">
                <i class="bi bi-calculator-fill text-secondary"></i>
            </div>
            <div>
                <div class="font-headline text-sm font-bold text-primary">Formula Penalti Pembatalan</div>
                <p class="font-label text-[0.6rem] uppercase tracking-widest text-outline">Tier denda berdasarkan H-hari sebelum event</p>
            </div>
        </div>
        <button @click="editing = !editing" type="button"
                :class="editing ? 'bg-red-500/10 text-red-600 border-red-500/20' : 'bg-secondary/10 text-secondary border-secondary/20'"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border font-label text-xs font-bold uppercase tracking-widest transition-all">
            <i :class="editing ? 'bi-x-circle-fill' : 'bi-pencil-fill'" class="bi text-sm"></i>
            <span x-text="editing ? 'Batal Edit' : 'Edit Formula'"></span>
        </button>
    </div>

    {{-- DISPLAY VIEW (tidak sedang edit) --}}
    <div x-show="!editing" class="p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($penaltyTiers as $i => $tier)
            @php
                $colors = [
                    'text-on-surface-variant bg-surface-container-low border-outline-variant/40',
                    'text-orange-600 bg-orange-500/5 border-orange-500/20',
                    'text-red-500 bg-red-500/5 border-red-500/20',
                    'text-red-700 bg-red-700/5 border-red-700/20',
                ];
                $colorClass = $colors[min($i, count($colors)-1)];
            @endphp
            <div class="text-center p-5 rounded-2xl border {{ $colorClass }} relative group">
                <div class="font-headline text-3xl font-bold mb-1 leading-none">{{ number_format($tier['percentage'], 0) }}%</div>
                <div class="font-label text-[0.65rem] uppercase tracking-widest font-bold opacity-80 mt-2">
                    @if(!empty($tier['label']))
                        {{ $tier['label'] }}
                    @else
                        ≥ H-{{ $tier['days_from'] }}
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4 flex items-start gap-2 text-xs text-on-surface-variant">
            <i class="bi bi-info-circle-fill text-secondary flex-shrink-0 mt-0.5"></i>
            <p>Formula ini berlaku untuk seluruh pembatalan. Klik <strong>Edit Formula</strong> untuk mengubah persentase dan threshold H-hari sesuai kebijakan sanggar.</p>
        </div>
    </div>

    {{-- EDIT VIEW (sedang edit) --}}
    <div x-show="editing" style="display:none">
        <form action="{{ route('admin.cancellations.penalty_settings') }}" method="POST" id="penaltyForm">
            @csrf

            <div class="p-6">
                {{-- Warning box --}}
                <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-start gap-3 mb-5">
                    <i class="bi bi-exclamation-triangle-fill text-amber-600 flex-shrink-0 mt-0.5"></i>
                    <div class="text-xs text-amber-800 leading-relaxed">
                        <strong>Perhatian:</strong> Perubahan formula hanya berlaku untuk pembatalan <em>berikutnya</em>. Riwayat pembatalan lama tidak akan terpengaruh. Pastikan sudah dikonfirmasi dengan manajemen sanggar.
                    </div>
                </div>

                {{-- Tier rows --}}
                <div id="tierContainer" class="space-y-3 mb-4">
                    @foreach($penaltyTiers as $i => $tier)
                    <div class="tier-row flex items-center gap-3 bg-surface-container-low rounded-xl p-4 border border-outline-variant/30">
                        <div class="w-7 h-7 rounded-lg bg-primary/10 flex items-center justify-center shrink-0 font-label text-xs font-bold text-primary">{{ $i+1 }}</div>

                        <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-1">H-Hari Mulai Dari</label>
                                <div class="relative">
                                    <input type="number" name="tiers[{{ $i }}][days_from]" value="{{ $tier['days_from'] }}" min="0" required
                                           class="w-full bg-white border border-outline-variant/50 rounded-lg px-3 py-2 pr-10 text-sm font-bold text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 font-label text-[0.6rem] text-outline font-bold">hari</span>
                                </div>
                            </div>
                            <div>
                                <label class="block font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-1">Persentase Denda (%)</label>
                                <div class="relative">
                                    <input type="number" name="tiers[{{ $i }}][percentage]" value="{{ $tier['percentage'] }}" min="0" max="100" step="0.5" required
                                           class="w-full bg-white border border-outline-variant/50 rounded-lg px-3 py-2 pr-8 text-sm font-bold text-red-600 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 font-label text-[0.6rem] text-outline font-bold">%</span>
                                </div>
                            </div>
                            <div>
                                <label class="block font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-1">Label Tampilan</label>
                                <input type="text" name="tiers[{{ $i }}][label]" value="{{ $tier['label'] ?? '' }}"
                                       placeholder="Contoh: ≥ H-14"
                                       class="w-full bg-white border border-outline-variant/50 rounded-lg px-3 py-2 text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            </div>
                        </div>

                        <button type="button" onclick="removeTier(this)"
                                class="w-8 h-8 rounded-lg border border-red-200 text-red-400 hover:bg-red-50 hover:text-red-600 hover:border-red-400 transition-colors flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-trash3-fill text-xs"></i>
                        </button>
                    </div>
                    @endforeach
                </div>

                {{-- Add Tier Button --}}
                <button type="button" onclick="addTier()"
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 border-dashed border-primary/30 text-primary font-label text-xs font-bold uppercase tracking-widest hover:border-primary hover:bg-primary/5 transition-all">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Tier Penalti
                </button>
            </div>

            {{-- Form Footer --}}
            <div class="px-6 py-4 border-t border-outline-variant/20 bg-surface-container-low/50 flex items-center justify-between">
                <p class="font-body text-xs text-on-surface-variant">Tier diurutkan otomatis dari H-terbanyak ke H-terkecil saat disimpan.</p>
                <div class="flex gap-3">
                    <button type="button" @click="editing = false"
                            class="h-9 px-4 rounded-xl border border-outline-variant text-xs font-bold uppercase text-on-surface-variant hover:bg-surface-container transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="h-9 px-5 rounded-xl bg-gradient-to-r from-primary-container to-primary text-white text-xs font-bold uppercase tracking-wider hover:opacity-90 transition-all shadow-md flex items-center gap-2">
                        <i class="bi bi-save2-fill"></i> Simpan Formula
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
        <div class="tier-row flex items-center gap-3 bg-surface-container-low rounded-xl p-4 border border-outline-variant/30">
            <div class="w-7 h-7 rounded-lg bg-primary/10 flex items-center justify-center shrink-0 font-label text-xs font-bold text-primary">${idx+1}</div>
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-1">H-Hari Mulai Dari</label>
                    <div class="relative">
                        <input type="number" name="tiers[${idx}][days_from]" value="0" min="0" required
                               class="w-full bg-white border border-outline-variant/50 rounded-lg px-3 py-2 pr-10 text-sm font-bold text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 font-label text-[0.6rem] text-outline font-bold">hari</span>
                    </div>
                </div>
                <div>
                    <label class="block font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-1">Persentase Denda (%)</label>
                    <div class="relative">
                        <input type="number" name="tiers[${idx}][percentage]" value="50" min="0" max="100" step="0.5" required
                               class="w-full bg-white border border-outline-variant/50 rounded-lg px-3 py-2 pr-8 text-sm font-bold text-red-600 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 font-label text-[0.6rem] text-outline font-bold">%</span>
                    </div>
                </div>
                <div>
                    <label class="block font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold mb-1">Label Tampilan</label>
                    <input type="text" name="tiers[${idx}][label]" value=""
                           placeholder="Contoh: H-5 s/d H-9"
                           class="w-full bg-white border border-outline-variant/50 rounded-lg px-3 py-2 text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                </div>
            </div>
            <button type="button" onclick="removeTier(this)"
                    class="w-8 h-8 rounded-lg border border-red-200 text-red-400 hover:bg-red-50 hover:text-red-600 hover:border-red-400 transition-colors flex items-center justify-center flex-shrink-0">
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
        const badge = row.querySelector('.w-7.h-7');
        if (badge) badge.textContent = i + 1;
        // Update name attributes
        row.querySelectorAll('[name]').forEach(input => {
            input.name = input.name.replace(/tiers\[\d+\]/, `tiers[${i}]`);
        });
    });
}
</script>
@endpush

@endsection
