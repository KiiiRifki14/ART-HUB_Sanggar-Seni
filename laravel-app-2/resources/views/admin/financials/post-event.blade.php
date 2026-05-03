@extends('layouts.admin')

@section('title', 'Post-Event Update – ART-HUB')
@section('page_title', 'Post-Event Update')
@section('page_subtitle', 'Audit biaya operasional lapangan ' . ($event->event_code ?? ''))

@section('content')
@php $fr = $event->financialRecord; @endphp

{{-- Back Nav --}}
<div class="flex items-center gap-2 mb-6 font-label text-xs uppercase tracking-widest font-bold">
    <a href="{{ route('admin.financials.post_event_list') }}" class="text-on-surface-variant hover:text-secondary transition-colors flex items-center gap-1.5">
        <i class="bi bi-arrow-left"></i> Post-Event List
    </a>
    <span class="text-outline-variant">/</span>
    <span class="text-outline">Detail Audit</span>
</div>

@if($fr)
    {{-- ── SUMMARY CARDS ── --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-primary-container to-primary text-white rounded-xl p-6 border border-primary/20 shadow-[0_12px_24px_rgba(54,31,26,0.08)] flex items-center justify-between">
            <div>
                <div class="font-label text-[0.65rem] uppercase tracking-widest text-white/80 font-bold mb-1">Budget Ops Awal</div>
                <div class="font-headline text-3xl font-bold text-secondary">Rp {{ number_format($fr->operational_budget, 0, ',', '.') }}</div>
            </div>
            <i class="bi bi-wallet2 text-4xl text-white/10"></i>
        </div>
        
        @php $overBudget = $fr->actual_operational_cost > $fr->operational_budget; @endphp
        <div class="bg-surface-container-lowest rounded-xl p-6 border {{ $overBudget ? 'border-red-500/30 shadow-[0_8px_20px_rgba(239,68,68,0.1)]' : 'border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)]' }} flex items-center justify-between">
            <div>
                <div class="font-label text-[0.65rem] uppercase tracking-widest font-bold mb-1 {{ $overBudget ? 'text-red-500' : 'text-outline' }}">Realisasi Lapangan</div>
                <div class="font-headline text-3xl font-bold {{ $overBudget ? 'text-red-600' : 'text-on-surface' }}">
                    Rp {{ number_format($fr->actual_operational_cost, 0, ',', '.') }}
                </div>
            </div>
            <i class="bi bi-cash-stack text-4xl {{ $overBudget ? 'text-red-500/10' : 'text-outline-variant/30' }}"></i>
        </div>
        
        <div class="bg-surface-container-lowest rounded-xl p-6 border border-green-500/30 shadow-[0_8px_20px_rgba(34,197,94,0.05)] flex items-center justify-between">
            <div>
                <div class="font-label text-[0.65rem] uppercase tracking-widest font-bold mb-1 text-green-600">Safety Buffer Area</div>
                <div class="font-headline text-3xl font-bold text-green-600">Rp {{ number_format($fr->safety_buffer_amt, 0, ',', '.') }}</div>
            </div>
            <i class="bi bi-shield-check text-4xl text-green-500/10"></i>
        </div>
    </div>

    {{-- ── BUDGET WARNING ── --}}
    @if($fr->budget_warning)
    <div class="bg-orange-500/10 border border-orange-500/20 text-orange-700 rounded-xl p-5 mb-8 flex items-start gap-4 shadow-sm">
        <i class="bi bi-exclamation-triangle-fill text-3xl mt-0.5"></i>
        <div>
            <h6 class="font-headline font-bold text-lg mb-1">Budget Warning Aktif!</h6>
            <p class="font-body text-sm opacity-90">{{ $fr->warning_message }}</p>
        </div>
    </div>
    @endif

    {{-- ── RINCIAN BIAYA OPS ── --}}
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
        <div class="px-6 py-5 border-b border-outline-variant/20 flex items-center justify-between bg-surface-container-low/30">
            <h3 class="font-headline text-lg font-bold text-primary flex items-center gap-2">
                <i class="bi bi-list-check text-secondary"></i> Rincian Biaya Operasional
            </h3>
            <button type="button" data-bs-toggle="modal" data-bs-target="#modalAddCost" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary text-white font-label text-[0.65rem] font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-sm">
                <i class="bi bi-plus-lg"></i> Tambah Biaya
            </button>
        </div>
        
        <table class="w-full">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Kategori</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Keterangan</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Estimasi</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Realisasi Lapangan</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Selisih</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($fr->operationalCosts as $index => $cost)
                @php $diff = $cost->actual_amount - $cost->estimated_amount; @endphp
                <tr class="{{ $index % 2 === 0 ? 'bg-surface-container-lowest' : 'bg-surface-container-low/30' }} hover:bg-surface-container-low transition-colors group">
                    <td class="px-6 py-4">
                        <span class="inline-block px-2.5 py-1 rounded border border-outline-variant/50 bg-surface-container-highest text-on-surface-variant font-label text-[0.65rem] font-bold uppercase tracking-wider">
                            {{ str_replace('_', ' ', $cost->category) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-body text-sm font-semibold text-on-surface">{{ $cost->description }}</td>
                    <td class="px-6 py-4 text-right font-body text-sm text-outline font-medium">Rp {{ number_format($cost->estimated_amount, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.financials.operational_costs.update', $cost->id) }}" method="POST" class="flex items-center justify-end gap-2">
                            @csrf
                            <span class="font-body text-sm text-on-surface font-bold">Rp</span>
                            <input type="number" name="actual_amount" value="{{ $cost->actual_amount }}" class="w-32 bg-surface-container-highest border border-outline-variant/50 rounded-lg px-3 py-1.5 font-headline text-sm font-bold text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors text-right" required>
                            <button type="submit" class="w-8 h-8 rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100 shadow-sm border border-primary/20" title="Simpan Perubahan">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-right font-body text-sm font-bold">
                        @if($diff > 0) 
                            <span class="inline-flex items-center gap-1 text-red-600 bg-red-500/10 px-2 py-0.5 rounded border border-red-500/20"><i class="bi bi-arrow-up-short"></i> Rp {{ number_format($diff, 0, ',', '.') }}</span>
                        @elseif($diff < 0) 
                            <span class="inline-flex items-center gap-1 text-green-600 bg-green-500/10 px-2 py-0.5 rounded border border-green-500/20"><i class="bi bi-arrow-down-short"></i> Rp {{ number_format(abs($diff), 0, ',', '.') }}</span>
                        @else 
                            <span class="text-outline-variant">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-outline">Belum ada data input biaya operasional tambahan.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-primary/5 border-t-2 border-primary/20">
                <tr>
                    <td colspan="2" class="px-6 py-4 text-right font-label text-[0.65rem] uppercase tracking-widest font-bold text-primary">TOTAL KESELURUHAN</td>
                    <td class="px-6 py-4 text-right font-body text-sm font-bold text-on-surface-variant">Rp {{ number_format($fr->operationalCosts->sum('estimated_amount'), 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right font-headline text-base font-bold text-primary">Rp {{ number_format($fr->operationalCosts->sum('actual_amount'), 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right font-body text-sm font-bold">
                        @php $totalDiff = $fr->operationalCosts->sum('actual_amount') - $fr->operationalCosts->sum('estimated_amount'); @endphp
                        @if($totalDiff > 0) 
                            <span class="text-red-600">+Rp {{ number_format($totalDiff, 0, ',', '.') }}</span>
                        @else 
                            <span class="text-green-600">-Rp {{ number_format(abs($totalDiff), 0, ',', '.') }}</span>
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@else
    {{-- NO DATA STATE --}}
    <div class="bg-surface-container border border-outline-variant/30 border-dashed rounded-xl p-12 text-center shadow-sm max-w-2xl mx-auto">
        <i class="bi bi-file-earmark-x text-5xl text-outline/50 mb-4 block"></i>
        <p class="font-headline font-bold text-lg text-on-surface mb-2">Belum ada data keuangan</p>
        <p class="font-body text-sm text-on-surface-variant">Data keuangan untuk event ini belum terbentuk.<br>Pastikan DP sudah dikonfirmasi terlebih dahulu dari halaman Booking.</p>
    </div>
@endif

{{-- ── TOMBOL NAVIGASI & AKSI SELESAI ── --}}
<div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="flex gap-3 w-full sm:w-auto">
        <a href="{{ route('admin.events.show', $event->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-outline-variant/50 font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface hover:bg-surface-container transition-colors">
            <i class="bi bi-arrow-left"></i> Kembali ke Event
        </a>
        <a href="{{ route('admin.financials.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-secondary/30 font-label text-[0.65rem] font-bold uppercase tracking-widest text-secondary hover:bg-secondary/10 transition-colors">
            <i class="bi bi-graph-up-arrow"></i> Laporan Keuangan
        </a>
    </div>

    @if($event->status !== 'completed' && $event->status !== 'cancelled')
    <form action="{{ route('admin.events.mark_completed', $event->id) }}" method="POST" class="m-0 w-full sm:w-auto" onsubmit="return confirm('Tandai event ini sebagai SELESAI?\nPastikan semua biaya riil lapangan sudah direkam.')">
        @csrf
        @method('PATCH')
        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-lg bg-green-600 text-white font-label text-[0.65rem] font-bold uppercase tracking-widest hover:bg-green-700 transition-colors shadow-md">
            <i class="bi bi-check2-all"></i> Tandai Event Selesai
        </button>
    </form>
    @elseif($event->status === 'completed')
    <div class="px-4 py-2 rounded-lg bg-green-500/10 border border-green-500/20 text-green-700 font-label text-[0.65rem] font-bold uppercase tracking-widest flex items-center gap-2">
        <i class="bi bi-patch-check-fill"></i> Event Telah Selesai
    </div>
    @endif
</div>

{{-- MODAL TAMBAH BIAYA OPERASIONAL --}}
@if($fr)
<div class="modal fade" id="modalAddCost" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-2xl overflow-hidden shadow-2xl bg-surface-container-lowest">
            <div class="px-6 py-5 border-b border-outline-variant/20 flex justify-between items-center bg-surface-container-low">
                <h5 class="font-headline font-bold text-lg text-primary flex items-center gap-2">
                    <i class="bi bi-plus-circle-fill text-secondary"></i> Tambah Biaya Baru
                </h5>
                <button type="button" class="text-on-surface-variant hover:text-primary transition-colors" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form action="{{ route('admin.financials.operational_costs.store', $event->id) }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block font-label text-[0.65rem] uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Kategori</label>
                        <select name="category" class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" required>
                            <option value="konsumsi">Konsumsi</option>
                            <option value="transportasi">Transportasi / Bensin</option>
                            <option value="honor_kru">Honor Tambahan Kru</option>
                            <option value="logistik">Sewa Alat / Logistik</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-label text-[0.65rem] uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Keterangan / Deskripsi</label>
                        <input type="text" name="description" placeholder="Contoh: Beli rokok & kopi, Uang tol..." class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-label text-[0.65rem] uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Estimasi Awal (Rp)</label>
                            <input type="number" name="estimated_amount" value="0" min="0" class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-2.5 font-headline font-bold text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" required>
                        </div>
                        <div>
                            <label class="block font-label text-[0.65rem] uppercase tracking-widest text-primary font-bold mb-1.5">Realisasi Lapangan (Rp)</label>
                            <input type="number" name="actual_amount" min="0" placeholder="Nominal Rp" class="w-full bg-primary/5 border border-primary/30 rounded-lg px-4 py-2.5 font-headline font-bold text-primary focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" required>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant/20 bg-surface-container-low flex justify-end gap-3">
                    <button type="button" class="px-5 py-2.5 rounded-lg border border-outline-variant/50 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-colors" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors">Simpan Biaya</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
