@extends('layouts.admin')

@section('title', 'Pembaruan Pasca-Acara – ART-HUB')
@section('page_title', 'Pembaruan Pasca-Acara')
@section('page_subtitle', 'Audit biaya operasional lapangan ' . ($event->event_code ?? ''))

@section('content')
@php $fr = $event->financialRecord; @endphp

{{-- Back Nav --}}
<div class="flex items-center gap-2 mb-6 subtitle-gold font-bold">
    <a href="{{ route('admin.financials.index') }}" class="text-gray-500 hover:text-yellow-600 transition-colors flex items-center gap-1.5" style="text-transform:none;">
        <i data-lucide="arrow-left" class="w-4 h-4 -mt-0.5"></i> Kembali ke Laporan
    </a>
    <span class="text-gray-300">/</span>
    <span style="color:#8B1A2A;">Detail Audit</span>
</div>

@if($fr)
    {{-- ── SUMMARY CARDS ── --}}
    @php
        // BUG-5 FIX: Budget Ops Bersih = gross budget DIKURANGI safety buffer
        // Safety buffer adalah cadangan yang TIDAK boleh dipakai untuk ops biasa.
        $netOpsBudget = max(0, $fr->operational_budget - $fr->safety_buffer_amt);
        $overBudget   = $fr->actual_operational_cost > $netOpsBudget;
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card-gold text-white p-6 flex items-center justify-between" style="background:linear-gradient(135deg, #8B1A2A, #5C0E19);">
            <div>
                <div class="subtitle-gold mb-1" style="color:rgba(255,255,255,0.8);">Budget Ops Bersih</div>
                <div class="title-gold" style="font-size:1.8rem; color:#fcd400;">Rp {{ number_format($netOpsBudget, 0, ',', '.') }}</div>
                <div class="subtitle-gold mt-1" style="font-size:0.55rem; color:rgba(255,255,255,0.6); text-transform:none; letter-spacing:normal;">(setelah dikurangi cadangan Rp {{ number_format($fr->safety_buffer_amt, 0, ',', '.') }})</div>
            </div>
            <i data-lucide="wallet" class="w-10 h-10 text-white/10"></i>
        </div>
        
        <div class="card-gold p-6 flex items-center justify-between" style="{{ $overBudget ? 'border-color:rgba(239,68,68,0.3); background:rgba(239,68,68,0.02);' : '' }}">
            <div>
                <div class="subtitle-gold mb-1" style="{{ $overBudget ? 'color:#ef4444;' : '' }}">Realisasi Lapangan</div>
                <div class="title-gold" style="font-size:1.8rem; {{ $overBudget ? 'color:#dc2626;' : 'color:#1A1817;' }}">
                    Rp {{ number_format($fr->actual_operational_cost, 0, ',', '.') }}
                </div>
                @if($overBudget)
                <div class="subtitle-gold mt-1 flex items-center gap-1" style="font-size:0.55rem; color:#ef4444; text-transform:none; letter-spacing:normal;">
                    <i data-lucide="alert-triangle" class="w-3 h-3"></i> Melebihi budget bersih!
                </div>
                @endif
            </div>
            <i data-lucide="banknote" class="w-10 h-10" style="{{ $overBudget ? 'color:rgba(239,68,68,0.2);' : 'color:rgba(197,160,40,0.2);' }}"></i>
        </div>
        
        <div class="card-gold p-6 flex items-center justify-between" style="border-color:rgba(22,163,74,0.3); background:rgba(22,163,74,0.02);">
            <div>
                <div class="subtitle-gold mb-1" style="color:#16a34a;">Dana Cadangan (Safety Buffer)</div>
                <div class="title-gold" style="font-size:1.8rem; color:#16a34a;">Rp {{ number_format($fr->safety_buffer_amt, 0, ',', '.') }}</div>
                <div class="subtitle-gold mt-1" style="font-size:0.55rem; color:rgba(22,163,74,0.7); text-transform:none; letter-spacing:normal;">10% dari gross budget — tidak untuk ops biasa</div>
            </div>
            <i data-lucide="shield-check" class="w-10 h-10 text-green-500/20"></i>
        </div>
    </div>

    {{-- ── BUDGET WARNING ── --}}
    @if($fr->budget_warning)
    <div class="card-gold p-5 mb-8 flex items-start gap-4 shadow-sm" style="background:rgba(234,88,12,0.05); border-color:rgba(234,88,12,0.2);">
        <i data-lucide="alert-triangle" class="w-8 h-8 text-orange-500 mt-0.5"></i>
        <div>
            <h6 class="title-gold mb-1" style="font-size:1.1rem; color:#c2410c;">Budget Warning Aktif!</h6>
            <p class="subtitle-gold" style="font-size:0.75rem; color:#ea580c; text-transform:none; letter-spacing:normal;">{{ $fr->warning_message }}</p>
        </div>
    </div>
    @endif

    {{-- ── RINCIAN BIAYA OPS ── --}}
    <div class="card-gold overflow-hidden">
        <div class="px-6 py-5 border-b flex items-center justify-between" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.02);">
            <h3 class="title-gold flex items-center gap-2" style="font-size:1.3rem;">
                <i data-lucide="list-checks" class="w-5 h-5 text-gray-400"></i> Rincian Biaya Operasional
            </h3>
            <button type="button" onclick="document.getElementById('modalAddCost').classList.remove('hidden');document.getElementById('modalAddCost').classList.add('flex');" class="arh-btn-primary py-1.5 px-3 text-xs" style="background:linear-gradient(135deg, #fcd400, #C5A028); color:#1A1817; border:none;">
                <i data-lucide="plus" class="w-4 h-4 mr-1 inline-block -mt-1"></i> Tambah Biaya
            </button>
        </div>
        
        <table class="w-full table-gold">
            <thead>
                <tr>
                    <th class="text-left">Kategori</th>
                    <th class="text-left">Keterangan</th>
                    <th class="text-right">Estimasi</th>
                    <th class="text-right">Realisasi Lapangan</th>
                    <th class="text-right">Selisih</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fr->operationalCosts as $index => $cost)
                @php $diff = $cost->actual_amount - $cost->estimated_amount; @endphp
                <tr>
                    <td>
                        <span class="badge-gold">
                            {{ ucwords(str_replace('_', ' ', $cost->category)) }}
                        </span>
                    </td>
                    <td style="font-family:'Inter',sans-serif; color:#1A1817; font-weight:600; font-size:0.85rem;">{{ $cost->description }}</td>
                    <td class="text-right" style="font-family:'Inter',sans-serif; color:#847B78;">Rp {{ number_format($cost->estimated_amount, 0, ',', '.') }}</td>
                    <td class="text-right">
                        <form action="{{ route('admin.financials.operational_costs.update', $cost->id) }}" method="POST" class="flex items-center justify-end gap-2 group">
                            @csrf
                            <span style="font-weight:700; color:#504442;">Rp</span>
                            <input type="number" name="actual_amount" value="{{ $cost->actual_amount }}" class="input-gold w-32 text-right" style="padding:6px 12px; font-weight:700; color:#8B1A2A;" required>
                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all" style="background:rgba(22,163,74,0.1); color:#16a34a; border:1px solid rgba(22,163,74,0.2);" title="Simpan Perubahan">
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </td>
                    <td class="text-right">
                        @if($diff > 0) 
                            <span class="inline-flex items-center gap-1 rounded border font-bold" style="border-color:rgba(239,68,68,0.2); background:rgba(239,68,68,0.1); color:#dc2626; font-size:0.75rem; padding:2px 6px;"><i data-lucide="arrow-up" class="w-3 h-3"></i> Rp {{ number_format($diff, 0, ',', '.') }}</span>
                        @elseif($diff < 0) 
                            <span class="inline-flex items-center gap-1 rounded border font-bold" style="border-color:rgba(34,197,94,0.2); background:rgba(34,197,94,0.1); color:#16a34a; font-size:0.75rem; padding:2px 6px;"><i data-lucide="arrow-down" class="w-3 h-3"></i> Rp {{ number_format(abs($diff), 0, ',', '.') }}</span>
                        @else 
                            <span style="color:#847B78;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-gray-400" style="font-size:0.85rem;">Belum ada data input biaya operasional tambahan.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background:rgba(197,160,40,0.05); border-top:2px solid rgba(197,160,40,0.2);">
                    <td colspan="2" class="text-right subtitle-gold" style="padding:14px 16px; color:#8B1A2A;">TOTAL KESELURUHAN</td>
                    <td class="text-right" style="font-family:'Inter',sans-serif; color:#847B78; font-weight:700; padding:14px 16px;">Rp {{ number_format($fr->operationalCosts->sum('estimated_amount'), 0, ',', '.') }}</td>
                    <td class="text-right" style="font-family:'Inter',sans-serif; color:#8B1A2A; font-weight:800; font-size:1.1rem; padding:14px 16px;">Rp {{ number_format($fr->operationalCosts->sum('actual_amount'), 0, ',', '.') }}</td>
                    <td class="text-right" style="font-family:'Inter',sans-serif; font-weight:700; padding:14px 16px;">
                        @php $totalDiff = $fr->operationalCosts->sum('actual_amount') - $fr->operationalCosts->sum('estimated_amount'); @endphp
                        @if($totalDiff > 0) 
                            <span style="color:#dc2626;">+Rp {{ number_format($totalDiff, 0, ',', '.') }}</span>
                        @else 
                            <span style="color:#16a34a;">-Rp {{ number_format(abs($totalDiff), 0, ',', '.') }}</span>
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@else
    {{-- NO DATA STATE --}}
    <div class="card-gold border-dashed p-12 text-center max-w-2xl mx-auto">
        <i data-lucide="file-x" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
        <p class="title-gold" style="font-size:1.2rem; margin-bottom:8px;">Belum ada data keuangan</p>
        <p class="subtitle-gold" style="font-size:0.75rem; text-transform:none; letter-spacing:normal;">Data keuangan untuk event ini belum terbentuk.<br>Pastikan DP sudah dikonfirmasi terlebih dahulu dari halaman Booking.</p>
    </div>
@endif

{{-- ── TOMBOL NAVIGASI & AKSI SELESAI ── --}}
<div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="flex gap-3 w-full sm:w-auto">
        <a href="{{ route('admin.events.show', $event->id) }}" class="arh-btn-secondary px-5 py-2.5" style="border-color:rgba(132,123,120,0.3); color:#504442;">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1 inline-block -mt-1"></i> Kembali ke Event
        </a>
        <a href="{{ route('admin.financials.index') }}" class="arh-btn-secondary px-5 py-2.5">
            <i data-lucide="trending-up" class="w-4 h-4 mr-1 inline-block -mt-1"></i> Laporan Keuangan
        </a>
    </div>

    @if($event->status !== 'completed' && $event->status !== 'cancelled')
    <form action="{{ route('admin.events.mark_completed', $event->id) }}" method="POST" class="m-0 w-full sm:w-auto" onsubmit="return confirm('Tandai event ini sebagai SELESAI? Pastikan semua biaya riil lapangan sudah direkam.');">
        @csrf
        @method('PATCH')
        <button type="submit" class="arh-btn-primary w-full sm:w-auto px-6 py-2.5" style="background:linear-gradient(135deg, #16a34a, #15803d); border:none; color:white;">
            <i data-lucide="check-check" class="w-4 h-4 mr-1 inline-block -mt-1"></i> Tandai Event Selesai
        </button>
    </form>
    @elseif($event->status === 'completed')
    <div class="badge-green px-4 py-2 flex items-center gap-2">
        <i data-lucide="check-circle" class="w-4 h-4"></i> Event Telah Selesai
    </div>
    @endif
</div>

{{-- MODAL TAMBAH BIAYA OPERASIONAL --}}
@if($fr)
<div id="modalAddCost" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden');this.parentElement.classList.remove('flex');"></div>
    <div class="relative w-full max-w-lg card-gold p-0 overflow-hidden">
        <div class="px-6 py-5 border-b flex justify-between items-center" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.02);">
            <h5 class="title-gold flex items-center gap-2" style="font-size:1.3rem;">
                <i data-lucide="plus-circle" class="w-5 h-5" style="color:#fcd400;"></i> Tambah Biaya Baru
            </h5>
            <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                onclick="document.getElementById('modalAddCost').classList.add('hidden');document.getElementById('modalAddCost').classList.remove('flex');">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('admin.financials.operational_costs.store', $event->id) }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block subtitle-gold mb-1.5" style="font-size:0.65rem;">Kategori <span class="text-red-500">*</span></label>
                    <select name="category" class="input-gold w-full" required>
                        <option value="konsumsi">Konsumsi</option>
                        <option value="transportasi">Transportasi / Bensin</option>
                        <option value="sewa_kostum">Sewa Kostum Luar</option>
                        <option value="honor_kru">Honor Tambahan Kru</option>
                        <option value="logistik">Sewa Alat / Logistik</option>
                        <option value="denda_insiden">Denda Insiden</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block subtitle-gold mb-1.5" style="font-size:0.65rem;">Keterangan / Deskripsi <span class="text-red-500">*</span></label>
                    <input type="text" name="description" placeholder="Contoh: Beli rokok & kopi, Uang tol..." class="input-gold w-full" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block subtitle-gold mb-1.5" style="font-size:0.65rem;">Estimasi Awal (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="estimated_amount" value="0" min="0" class="input-gold w-full font-bold" required>
                    </div>
                    <div>
                        <label class="block subtitle-gold mb-1.5" style="font-size:0.65rem; color:#8B1A2A;">Realisasi Lapangan (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="actual_amount" min="0" placeholder="Nominal Rp" class="input-gold w-full font-bold" style="border-color:rgba(139,26,42,0.4); color:#8B1A2A;" required>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t flex justify-end gap-3" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.02);">
                <button type="button" class="arh-btn-secondary px-5 py-2.5"
                    onclick="document.getElementById('modalAddCost').classList.add('hidden');document.getElementById('modalAddCost').classList.remove('flex');">Batal</button>
                <button type="submit" class="arh-btn-primary px-5 py-2.5">Simpan Biaya</button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection
