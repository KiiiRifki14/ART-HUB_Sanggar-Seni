@extends('layouts.admin')

@section('title', 'Kostum & Logistik – ART-HUB')
@section('page_title', 'Kostum & Logistik')
@section('page_subtitle', 'Inventaris aset sanggar dan status persewaan vendor eksternal.')

@section('content')

{{-- ── ASET KOSTUM SANGGAR ── --}}
<div class="mb-6">
    <div class="flex items-center justify-between mb-3">
        <h2 class="font-headline text-base text-primary font-semibold flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                <i data-lucide="shirt" class="w-4 h-4"></i>
            </div>
            Inventaris Aset Sanggar
        </h2>
        <a href="{{ route('admin.costumes.create-asset') }}" class="px-3.5 py-1.5 rounded-lg bg-primary text-white font-label text-[0.65rem] font-bold uppercase tracking-widest hover:bg-primary-container transition-all shadow-sm flex items-center gap-1.5">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah Aset
        </a>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
        {{-- Table view for larger screens --}}
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[800px]">
                <thead class="bg-surface-container-low border-b border-outline-variant/20">
                    <tr>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-4 py-3 text-left">Nama Aset</th>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-4 py-3 text-left">Kategori</th>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-4 py-3 text-center">Stok</th>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-4 py-3 text-center">Kondisi</th>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/15">
                    @forelse($sanggarCostumes as $c)
                    @php
                        $isDamaged = $c->condition === 'damaged';
                        $isMaintenance = $c->condition === 'maintenance';
                        $conditionBadge = match($c->condition) {
                            'good' => '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-green-500/10 text-green-700 border border-green-500/20 text-[0.55rem] font-bold uppercase"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Baik</span>',
                            'damaged' => '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-red-500/10 text-red-700 border border-red-500/20 text-[0.55rem] font-bold uppercase"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Rusak</span>',
                            default => '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-orange-500/10 text-orange-700 border border-orange-500/20 text-[0.55rem] font-bold uppercase"><span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>Maint.</span>',
                        };
                        $categoryIcons = [
                            'atasan' => 'shirt',
                            'bawahan' => 'layers',
                            'aksesoris' => 'gem',
                            'alat_musik' => 'music',
                            'musik' => 'music',
                            'properti' => 'box',
                            'tradisional' => 'palette',
                            'modern' => 'sparkles',
                        ];
                        $categoryIcon = $categoryIcons[strtolower($c->category)] ?? 'package';
                    @endphp
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-primary/5 text-primary flex items-center justify-center">
                                    <i data-lucide="{{ $categoryIcon }}" class="w-4 h-4"></i>
                                </div>
                                <div class="font-body font-semibold text-on-surface text-xs">{{ $c->name }}</div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2 py-0.5 rounded bg-secondary/10 text-secondary text-[0.55rem] font-bold uppercase tracking-wider">
                                {{ str_replace('_', ' ', $c->category) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full bg-surface-container font-headline font-bold text-primary text-[0.65rem]">
                                {{ $c->quantity }} Pcs
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            {!! $conditionBadge !!}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.costumes.edit-asset', $c->id) }}" class="w-7 h-7 rounded-md border border-outline-variant/40 text-outline hover:text-primary hover:border-primary flex items-center justify-center hover:bg-primary/5 transition-all" title="Edit Aset">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                </a>
                                <form action="{{ route('admin.costumes.destroy-asset', $c->id) }}" method="POST" data-confirm="Apakah Anda yakin ingin menghapus aset ini?" class="inline m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-7 h-7 rounded-md border border-red-200/50 text-red-500 hover:bg-red-50 hover:border-red-500 flex items-center justify-center transition-all" title="Hapus Aset">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-outline">
                            <i data-lucide="package" class="w-12 h-12 mx-auto mb-3 opacity-30 text-primary"></i>
                            <p class="font-headline text-lg font-bold text-on-surface mb-1">Belum Ada Aset Terdaftar</p>
                            <p class="font-body text-sm text-outline mb-4">Tambahkan aset kostum atau properti sanggar untuk memulai.</p>
                            <a href="{{ route('admin.costumes.create-asset') }}" class="px-4 py-2 rounded-lg bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-sm inline-flex items-center gap-1.5">
                                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Aset Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile View for smaller screens --}}
        <div class="block md:hidden divide-y divide-outline-variant/20">
            @forelse($sanggarCostumes as $c)
            @php
                $isDamaged = $c->condition === 'damaged';
                $isMaintenance = $c->condition === 'maintenance';
                $conditionBadge = match($c->condition) {
                    'good' => '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-green-500/10 text-green-700 border border-green-500/20 text-[0.55rem] font-bold uppercase"><span class="w-1 h-1 rounded-full bg-green-500"></span>Baik</span>',
                    'damaged' => '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-red-500/10 text-red-700 border border-red-500/20 text-[0.55rem] font-bold uppercase"><span class="w-1 h-1 rounded-full bg-red-500"></span>Rusak</span>',
                    default => '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-orange-500/10 text-orange-700 border border-orange-500/20 text-[0.55rem] font-bold uppercase"><span class="w-1 h-1 rounded-full bg-orange-500"></span>Maint.</span>',
                };
                $categoryIcons = [
                    'atasan' => 'shirt',
                    'bawahan' => 'layers',
                    'aksesoris' => 'gem',
                    'alat_musik' => 'music',
                    'musik' => 'music',
                    'properti' => 'box',
                    'tradisional' => 'palette',
                    'modern' => 'sparkles',
                ];
                $categoryIcon = $categoryIcons[strtolower($c->category)] ?? 'package';
            @endphp
            <div class="p-3.5 space-y-3 hover:bg-surface-container-low/50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded bg-primary/5 text-primary flex items-center justify-center">
                            <i data-lucide="{{ $categoryIcon }}" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="inline-block px-1.5 py-0.2 rounded bg-secondary/10 text-secondary text-[0.55rem] font-bold uppercase tracking-wider">
                            {{ str_replace('_', ' ', $c->category) }}
                        </span>
                    </div>
                    {!! $conditionBadge !!}
                </div>
                <div class="flex justify-between items-start">
                    <div class="font-body font-bold text-on-surface text-xs max-w-[70%] leading-snug">{{ $c->name }}</div>
                    <div class="text-right">
                        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded bg-surface-container font-headline font-bold text-primary text-[0.58rem]">
                            {{ $c->quantity }} Pcs
                        </span>
                    </div>
                </div>
                <div class="flex justify-end gap-1.5 pt-2 border-t border-outline-variant/10">
                    <a href="{{ route('admin.costumes.edit-asset', $c->id) }}" class="w-7 h-7 rounded-md border border-outline-variant/40 text-outline hover:text-primary hover:border-primary flex items-center justify-center hover:bg-primary/5 transition-all" title="Edit Aset">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                    </a>
                    <form action="{{ route('admin.costumes.destroy-asset', $c->id) }}" method="POST" data-confirm="Apakah Anda yakin ingin menghapus aset ini?" class="inline m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-7 h-7 rounded-md border border-red-200/50 text-red-500 hover:bg-red-50 hover:border-red-500 flex items-center justify-center transition-all" title="Hapus Aset">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-outline">
                <i data-lucide="package" class="w-10 h-10 mx-auto mb-2 opacity-30 text-primary"></i>
                <p class="font-body text-xs font-semibold text-on-surface">Belum ada aset terdaftar</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination Aset --}}
        @if($sanggarCostumes->hasPages())
        <div class="px-6 py-3 border-t border-outline-variant/20 bg-surface-container-low/20">
            {{ $sanggarCostumes->appends(request()->except('page_asset'))->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ── SEWA VENDOR (RENTALS) ── --}}
<div class="mb-6">
    <div class="flex items-center justify-between mb-3">
        <h2 class="font-headline text-base text-primary font-semibold flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary">
                <i data-lucide="shopping-cart" class="w-4 h-4"></i>
            </div>
            Transaksi Sewa Vendor Eksternal
        </h2>
        <a href="{{ route('admin.costumes.create-rental') }}" class="px-3.5 py-1.5 rounded-lg bg-secondary text-white font-label text-[0.65rem] font-bold uppercase tracking-widest hover:bg-secondary-container transition-all shadow-sm flex items-center gap-1.5">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah Sewaan
        </a>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
        {{-- Table view for larger screens --}}
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[900px]">
                <thead class="bg-surface-container-low border-b border-outline-variant/20">
                    <tr>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-3 py-1.5 text-left">Event</th>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-3 py-1.5 text-left">Vendor & Item</th>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-3 py-1.5 text-center">Jumlah</th>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-3 py-1.5 text-left">Tgl Kembali</th>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-3 py-1.5 text-center">Status</th>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-3 py-1.5 text-right">Denda Telat</th>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-3 py-1.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/15">
                    @forelse($vendorRentals as $r)
                    @php
                        $isOverdue = !$r->returned_date && \Carbon\Carbon::parse($r->due_date)->isPast();
                        $rowClass = $isOverdue ? 'bg-red-500/5 hover:bg-red-500/10' : 'hover:bg-surface-container-low/50';
                    @endphp
                    <tr class="{{ $rowClass }} transition-colors">
                        <td class="px-3 py-1.5">
                            <span class="inline-block px-2 py-0.5 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.55rem] font-bold tracking-wider">
                                {{ $r->event->event_code ?? '-' }}
                            </span>
                        </td>
                        <td class="px-3 py-1.5">
                            <div class="font-body font-semibold text-on-surface text-xs">{{ $r->vendor->name ?? '-' }}</div>
                            <div class="font-label text-[0.58rem] text-outline mt-0.5">{{ $r->costume_type }}</div>
                        </td>
                        <td class="px-3 py-1.5 text-center">
                            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-surface-container font-headline font-bold text-primary text-[0.65rem]">
                                {{ $r->quantity }} Pcs
                            </span>
                        </td>
                        <td class="px-3 py-1.5">
                            <div class="font-body text-xs {{ $isOverdue ? 'text-red-600 font-bold' : 'text-on-surface' }}">
                                {{ \Carbon\Carbon::parse($r->due_date)->format('d M Y') }}
                            </div>
                            @if($isOverdue)
                                <div class="font-label text-[0.55rem] text-red-500 font-bold uppercase tracking-widest flex items-center gap-1 mt-0.5">
                                    <i data-lucide="alert-triangle" class="w-3 h-3"></i> Lewat Deadline
                                </div>
                            @endif
                        </td>
                        <td class="px-3 py-1.5 text-center">
                            @if($r->status === 'rented' && $isOverdue)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-red-500/10 text-red-700 border border-red-500/20 text-[0.55rem] font-bold uppercase">TERLAMBAT</span>
                            @elseif($r->status === 'rented')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-orange-500/10 text-orange-700 border border-orange-500/20 text-[0.55rem] font-bold uppercase">DIPINJAM</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-green-500/10 text-green-700 border border-green-500/20 text-[0.55rem] font-bold uppercase">KEMBALI</span>
                            @endif
                        </td>
                        <td class="px-3 py-1.5 text-right">
                            @if($r->overdue_fine > 0)
                                <div class="font-headline font-bold text-red-600 text-xs">Rp {{ number_format($r->overdue_fine, 0, ',', '.') }}</div>
                                <div class="font-label text-[0.55rem] text-outline mt-0.5">{{ $r->overdue_days }} hari &times; Rp50k</div>
                            @else
                                <span class="font-label text-[0.55rem] text-outline">—</span>
                            @endif
                        </td>
                        <td class="px-3 py-1.5">
                            <div class="flex items-center justify-end gap-1.5">
                                @if(!$r->returned_date)
                                <a href="{{ route('admin.costumes.edit-rental', $r->id) }}" class="w-7 h-7 rounded-md border border-outline-variant/40 text-outline hover:text-primary hover:border-primary flex items-center justify-center hover:bg-primary/5 transition-all" title="Edit Sewaan">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                </a>
                                @endif
                                <button type="button" onclick="openReturnModal({{ $r->id }}, '{{ addslashes($r->costume_type) }}')" class="w-7 h-7 rounded-md border border-secondary/40 text-secondary hover:bg-secondary/10 flex items-center justify-center transition-all" title="Tandai Kembali">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-outline">
                            <i data-lucide="shopping-cart" class="w-12 h-12 mx-auto mb-3 opacity-30 text-secondary"></i>
                            <p class="font-headline text-lg font-bold text-on-surface mb-1">Belum Ada Transaksi Sewa</p>
                            <p class="font-body text-sm text-outline">Aset sanggar cukup untuk event saat ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards View --}}
        <div class="block md:hidden divide-y divide-outline-variant/20">
            @forelse($vendorRentals as $r)
            @php
                $isOverdue = !$r->returned_date && \Carbon\Carbon::parse($r->due_date)->isPast();
                $cardClass = $isOverdue ? 'bg-red-500/5 border-l-4 border-l-red-500' : 'hover:bg-surface-container-low/50 border-l-4 border-l-transparent';
            @endphp
            <div class="p-2.5 space-y-2 {{ $cardClass }}">
                <div class="flex justify-between items-center">
                    <span class="inline-block px-2 py-0.5 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.55rem] font-bold tracking-wider">
                        {{ $r->event->event_code ?? '-' }}
                    </span>
                    <div>
                        @if($r->status === 'rented' && $isOverdue)
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[0.55rem] font-semibold bg-red-500/10 text-red-700">TERLAMBAT</span>
                        @elseif($r->status === 'rented')
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[0.55rem] font-semibold bg-orange-500/10 text-orange-700">DIPINJAM</span>
                        @else
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[0.55rem] font-semibold bg-green-500/10 text-green-700">KEMBALI</span>
                        @endif
                    </div>
                </div>
                <div class="flex justify-between items-start gap-2">
                    <div>
                        <div class="font-body font-bold text-on-surface text-xs">{{ $r->vendor->name ?? '-' }}</div>
                        <div class="font-label text-[0.58rem] text-outline mt-0.5">{{ $r->costume_type }}</div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center justify-center px-1.5 py-0.2 rounded bg-surface-container font-headline font-bold text-primary text-[0.58rem]">
                            {{ $r->quantity }} Pcs
                        </span>
                    </div>
                </div>
                <div class="flex justify-between items-end pt-1.5 border-t border-outline-variant/10 gap-2">
                    <div>
                        <div class="font-label text-[0.5rem] uppercase tracking-widest text-outline font-bold">Tgl Kembali</div>
                        <div class="font-body text-xs {{ $isOverdue ? 'text-red-600 font-bold' : 'text-on-surface' }} mt-0.5">
                            {{ \Carbon\Carbon::parse($r->due_date)->format('d M Y') }}
                        </div>
                    </div>
                    <div class="text-right flex flex-col items-end">
                        @if($r->overdue_fine > 0)
                            <div class="font-label text-[0.5rem] uppercase tracking-widest text-outline font-bold">Denda</div>
                            <div class="font-headline font-bold text-red-600 text-xs mt-0.5">Rp {{ number_format($r->overdue_fine, 0, ',', '.') }}</div>
                        @else
                            <span class="font-label text-xs text-outline">—</span>
                        @endif
                    </div>
                </div>
                <div class="flex justify-end gap-1.5 pt-1.5 border-t border-outline-variant/10">
                    @if(!$r->returned_date)
                    <a href="{{ route('admin.costumes.edit-rental', $r->id) }}" class="w-7 h-7 rounded-md border border-outline-variant/40 text-outline hover:text-primary hover:border-primary flex items-center justify-center hover:bg-primary/5 transition-all" title="Edit Sewaan">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                    </a>
                    @endif
                    <button type="button" onclick="openReturnModal({{ $r->id }}, '{{ addslashes($r->costume_type) }}')" class="w-7 h-7 rounded-md border border-secondary/40 text-secondary hover:bg-secondary/10 flex items-center justify-center transition-all" title="Tandai Kembali">
                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-outline">
                <i data-lucide="shopping-cart" class="w-10 h-10 mx-auto mb-2 opacity-30 text-secondary"></i>
                <p class="font-body text-xs font-semibold text-on-surface">Belum ada sewa vendor</p>
            </div>
            @endforelse
        </div>

        @if($vendorRentals->hasPages())
        <div class="px-6 py-3 border-t border-outline-variant/20 bg-surface-container-low/20">
            {{ $vendorRentals->appends(request()->except('page_rental'))->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ── RIWAYAT PENGGUNAAN KOSTUM ── --}}
<div>
    <div class="flex items-center justify-between mb-3">
        <h2 class="font-headline text-base text-primary font-semibold flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                <i data-lucide="history" class="w-4 h-4"></i>
            </div>
            Riwayat Penggunaan Aset Sanggar
        </h2>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
        {{-- Table view for larger screens --}}
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[900px]">
                <thead class="bg-surface-container-low border-b border-outline-variant/20">
                    <tr>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-3 py-1.5 text-left">Kostum</th>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-3 py-1.5 text-left">Event</th>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-3 py-1.5 text-center">Status</th>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-3 py-1.5 text-left">Tgl Diambil</th>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-3 py-1.5 text-left">Tgl Dikembalikan</th>
                        <th class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold px-3 py-1.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/15">
                    @forelse($costumeUsages as $usage)
                    @php
                        $statusColors = [
                            'checked_out' => 'bg-orange-500/10 border-orange-500/20 text-orange-600',
                            'returned' => 'bg-green-500/10 border-green-500/20 text-green-600',
                            'damaged' => 'bg-red-500/10 border-red-500/20 text-red-600',
                            'lost' => 'bg-red-500/10 border-red-500/20 text-red-600',
                        ];
                        $statusLabel = [
                            'checked_out' => 'Diambil',
                            'returned' => 'Dikembalikan',
                            'damaged' => 'Rusak',
                            'lost' => 'Hilang',
                        ];
                        $statusColor = $statusColors[$usage->status] ?? 'bg-gray-500/10';
                        $rowClass = in_array($usage->status, ['damaged', 'lost']) ? 'bg-red-500/5 hover:bg-red-500/10' : 'hover:bg-surface-container-low/50';
                    @endphp
                    <tr class="transition-colors {{ $rowClass }}">
                        <td class="px-3 py-1.5">
                            <div class="font-body font-semibold text-on-surface text-xs">{{ $usage->costume->name ?? '-' }}</div>
                            <div class="font-label text-[0.58rem] text-outline mt-0.5">Qty: {{ $usage->quantity ?? '-' }} Pcs</div>
                        </td>
                        <td class="px-3 py-1.5">
                            <span class="inline-block px-2 py-0.5 rounded bg-primary-container/40 text-on-primary-container border border-primary/20 font-label text-[0.55rem] font-bold tracking-wider">
                                {{ $usage->event->event_code ?? '-' }}
                            </span>
                        </td>
                        <td class="px-3 py-1.5 text-center">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded border {{ $statusColor }} font-label text-[0.55rem] font-bold uppercase tracking-wider">
                                {{ $statusLabel[$usage->status] ?? ucfirst($usage->status) }}
                            </span>
                        </td>
                        <td class="px-3 py-1.5">
                            <div class="font-body text-xs text-on-surface">{{ \Carbon\Carbon::parse($usage->checked_out_date)->format('d M Y') ?? '-' }}</div>
                        </td>
                        <td class="px-3 py-1.5">
                            @if($usage->actual_return_date)
                                <div class="font-body text-xs text-on-surface">{{ \Carbon\Carbon::parse($usage->actual_return_date)->format('d M Y') }}</div>
                            @else
                                <span class="font-label text-[0.55rem] text-outline italic">Belum dikembalikan</span>
                            @endif
                        </td>
                        <td class="px-3 py-1.5">
                            <div class="flex items-center justify-end">
                                @if($usage->status === 'checked_out')
                                <button type="button" onclick="openReturnUsageModal({{ $usage->id }}, '{{ addslashes($usage->costume->name) }}')" class="w-7 h-7 rounded-md border border-primary/40 text-primary hover:bg-primary/10 flex items-center justify-center transition-all" title="Tandai Kembali">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                </button>
                                @else
                                <span class="font-label text-[0.55rem] text-outline">—</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-outline">
                            <i data-lucide="history" class="w-12 h-12 mx-auto mb-3 opacity-30 text-primary"></i>
                            <p class="font-headline text-lg font-bold text-on-surface mb-1">Belum Ada Riwayat Penggunaan</p>
                            <p class="font-body text-sm text-outline">Penggunaan aset kostum untuk event akan tercatat di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards View --}}
        <div class="block md:hidden divide-y divide-outline-variant/20">
            @forelse($costumeUsages as $usage)
            @php
                $statusColors = [
                    'checked_out' => 'bg-orange-500/5 border-l-4 border-l-orange-500',
                    'returned' => 'bg-green-500/5 border-l-4 border-l-green-500',
                    'damaged' => 'bg-red-500/5 border-l-4 border-l-red-500',
                    'lost' => 'bg-red-500/5 border-l-4 border-l-red-500',
                ];
                $statusLabel = [
                    'checked_out' => 'Diambil',
                    'returned' => 'Dikembalikan',
                    'damaged' => 'Rusak',
                    'lost' => 'Hilang',
                ];
                $cardClass = $statusColors[$usage->status] ?? 'border-l-4 border-l-transparent';
            @endphp
            <div class="p-2.5 space-y-2 {{ $cardClass }}">
                <div class="flex justify-between items-center">
                    <span class="inline-block px-2 py-0.5 rounded bg-primary-container/40 text-on-primary-container border border-primary/20 font-label text-[0.55rem] font-bold tracking-wider">
                        {{ $usage->event->event_code ?? '-' }}
                    </span>
                    <span class="font-label text-[0.55rem] font-bold uppercase tracking-wider text-primary">
                        {{ $statusLabel[$usage->status] ?? ucfirst($usage->status) }}
                    </span>
                </div>
                <div class="flex justify-between items-start gap-2">
                    <div>
                        <div class="font-body font-bold text-on-surface text-xs">{{ $usage->costume->name ?? '-' }}</div>
                        <div class="font-label text-[0.58rem] text-outline mt-0.5 font-semibold">Qty: {{ $usage->quantity ?? '-' }} Pcs</div>
                    </div>
                </div>
                <div class="flex justify-between items-end pt-1.5 border-t border-outline-variant/10 gap-2">
                    <div>
                        <div class="font-label text-[0.5rem] uppercase tracking-widest text-outline font-bold">Diambil</div>
                        <div class="font-body text-xs text-on-surface mt-0.5">{{ \Carbon\Carbon::parse($usage->checked_out_date)->format('d M Y') }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-label text-[0.5rem] uppercase tracking-widest text-outline font-bold">Dikembalikan</div>
                        @if($usage->actual_return_date)
                            <div class="font-body text-xs text-on-surface mt-0.5">{{ \Carbon\Carbon::parse($usage->actual_return_date)->format('d M Y') }}</div>
                        @else
                            <span class="font-label text-[0.58rem] text-outline italic mt-0.5 block">Belum</span>
                        @endif
                    </div>
                </div>
                @if($usage->status === 'checked_out')
                <div class="flex justify-end pt-1.5 border-t border-outline-variant/10">
                    <button type="button" onclick="openReturnUsageModal({{ $usage->id }}, '{{ addslashes($usage->costume->name) }}')" class="w-7 h-7 rounded-md border border-primary/40 text-primary hover:bg-primary/10 flex items-center justify-center transition-all" title="Tandai Kembali">
                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
                @endif
            </div>
            @empty
            <div class="p-6 text-center text-outline">
                <i data-lucide="history" class="w-10 h-10 mx-auto mb-2 opacity-30 text-primary"></i>
                <p class="font-body text-xs font-semibold text-on-surface">Belum ada riwayat penggunaan</p>
            </div>
            @endforelse
        </div>

        @if($costumeUsages->hasPages())
        <div class="px-6 py-3 border-t border-outline-variant/20 bg-surface-container-low/20">
            {{ $costumeUsages->appends(request()->except('page_usage'))->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ── RETURN USAGE MODAL ── --}}
<div x-data="usageManager" x-cloak>
    <div x-show="showReturnUsageModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-transition.opacity>
        <div @click.away="closeReturnUsageModal()" class="bg-surface-container-lowest p-6 rounded-2xl w-full max-w-md shadow-2xl border border-outline-variant/30 transform transition-all" x-transition.scale.95>
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-outline-variant/20">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-headline text-lg font-bold text-on-surface">Tandai Kostum Dikembalikan</h3>
                    <p class="font-body text-xs text-on-surface-variant"><span x-text="selectedUsageCostumeType"></span></p>
                </div>
            </div>

            <form @submit.prevent="submitReturnUsage">
                <div class="mb-5">
                    <label class="font-label text-sm font-bold text-on-surface block mb-2 ml-1">Status Pengembalian</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="relative flex flex-col p-3 rounded-lg border border-outline-variant/50 cursor-pointer hover:bg-surface-container transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                            <input type="radio" name="status" value="returned" x-model="returnUsageStatus" class="absolute top-2 right-2 w-4 h-4">
                            <span class="font-label text-xs font-bold text-green-600 mb-1 uppercase tracking-tighter">Kembali</span>
                            <span class="text-[10px] text-on-surface-variant">Baik</span>
                        </label>
                        <label class="relative flex flex-col p-3 rounded-lg border border-outline-variant/50 cursor-pointer hover:bg-surface-container transition-colors has-[:checked]:border-orange-500 has-[:checked]:bg-orange-500/5">
                            <input type="radio" name="status" value="damaged" x-model="returnUsageStatus" class="absolute top-2 right-2 w-4 h-4">
                            <span class="font-label text-xs font-bold text-orange-600 mb-1 uppercase tracking-tighter">Rusak</span>
                            <span class="text-[10px] text-on-surface-variant">Perbaikan</span>
                        </label>
                        <label class="relative flex flex-col p-3 rounded-lg border border-outline-variant/50 cursor-pointer hover:bg-surface-container transition-colors has-[:checked]:border-red-500 has-[:checked]:bg-red-500/5">
                            <input type="radio" name="status" value="lost" x-model="returnUsageStatus" class="absolute top-2 right-2 w-4 h-4">
                            <span class="font-label text-xs font-bold text-red-600 mb-1 uppercase tracking-tighter">Hilang</span>
                            <span class="text-[10px] text-on-surface-variant">Ganti</span>
                        </label>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="font-label text-sm font-bold text-on-surface block mb-1.5 ml-1">Catatan Pengembalian</label>
                    <textarea name="damage_notes" x-model="returnUsageNotes" placeholder="Tuliskan catatan tentang kondisi atau kejadian saat pengembalian..."
                              class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-3 py-2 font-body text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors resize-none" rows="3"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-outline-variant/20">
                    <button type="button" @click="closeReturnUsageModal()" class="px-4 py-2 rounded-lg font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-colors">
                        Batal
                    </button>
                    <button type="submit" :disabled="isSubmitting" class="px-4 py-2 rounded-lg bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors disabled:opacity-50 flex items-center gap-2">
                        <span x-show="isSubmitting" class="animate-spin inline-block w-3 h-3 border-2 border-white/30 border-t-white rounded-full"></span>
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Tandai Kembali'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script for usage Return --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('usageManager', () => ({
                showReturnUsageModal: false,
                selectedUsageId: null,
                selectedUsageCostumeType: '',
                returnUsageStatus: 'returned',
                returnUsageNotes: '',
                isSubmitting: false,

                init() {
                    window.openReturnUsageModal = (usageId, costumeType) => {
                        this.selectedUsageId = usageId;
                        this.selectedUsageCostumeType = costumeType;
                        this.returnUsageStatus = 'returned';
                        this.returnUsageNotes = '';
                        this.showReturnUsageModal = true;
                    };
                },

                closeReturnUsageModal() {
                    this.showReturnUsageModal = false;
                    this.selectedUsageId = null;
                    this.returnUsageStatus = 'returned';
                    this.returnUsageNotes = '';
                },

                submitReturnUsage() {
                    if (this.isSubmitting) return;

                    this.isSubmitting = true;

                    fetch(`/admin/costume-usages/${this.selectedUsageId}/return`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: this.returnUsageStatus,
                            damage_notes: this.returnUsageNotes
                        })
                    })
                    .then(response => {
                        if (response.redirected) {
                            window.location.href = response.url;
                        } else {
                            return response.json();
                        }
                    })
                    .then(data => {
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan: ' + error.message);
                        this.isSubmitting = false;
                    });
                }
            }));
        });
    </script>
</div>

{{-- ── RETURN RENTAL MODAL ── --}}
<div x-data="rentalManager" x-cloak>
    <div x-show="showReturnModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-transition.opacity>
        <div @click.away="closeReturnModal()" class="bg-surface-container-lowest p-6 rounded-2xl w-full max-w-md shadow-2xl border border-outline-variant/30 transform transition-all" x-transition.scale.95>
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-outline-variant/20">
                <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary flex-shrink-0">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-headline text-lg font-bold text-on-surface">Tandai Kostum Dikembalikan</h3>
                    <p class="font-body text-xs text-on-surface-variant"><span x-text="selectedCostumeType"></span></p>
                </div>
            </div>

            <form @submit.prevent="submitReturn">
                <div class="mb-5">
                    <label class="font-label text-sm font-bold text-on-surface block mb-2 ml-1">Status Pengembalian</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="relative flex flex-col p-3 rounded-lg border border-outline-variant/50 cursor-pointer hover:bg-surface-container transition-colors has-[:checked]:border-secondary has-[:checked]:bg-secondary/5">
                            <input type="radio" name="status" value="rented" x-model="returnStatus" class="absolute top-2 right-2 w-4 h-4">
                            <span class="font-label text-xs font-bold text-secondary mb-1 uppercase tracking-tighter">Dikembalikan</span>
                            <span class="text-[10px] text-on-surface-variant">Kondisi baik</span>
                        </label>
                        <label class="relative flex flex-col p-3 rounded-lg border border-outline-variant/50 cursor-pointer hover:bg-surface-container transition-colors has-[:checked]:border-red-500 has-[:checked]:bg-red-500/5">
                            <input type="radio" name="status" value="damaged" x-model="returnStatus" class="absolute top-2 right-2 w-4 h-4">
                            <span class="font-label text-xs font-bold text-red-600 mb-1 uppercase tracking-tighter">Rusak</span>
                            <span class="text-[10px] text-on-surface-variant">Perlu ganti</span>
                        </label>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="font-label text-sm font-bold text-on-surface block mb-1.5 ml-1">Catatan (Opsional)</label>
                    <textarea name="notes" x-model="returnNotes" placeholder="Tuliskan catatan tentang kondisi atau kejadian saat pengembalian..."
                              class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-3 py-2 font-body text-xs focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors resize-none" rows="3"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-outline-variant/20">
                    <button type="button" @click="closeReturnModal()" class="px-4 py-2 rounded-lg font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-colors">
                        Batal
                    </button>
                    <button type="submit" :disabled="isSubmitting" class="px-4 py-2 rounded-lg bg-secondary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-secondary-container transition-colors disabled:opacity-50 flex items-center gap-2">
                        <span x-show="isSubmitting" class="animate-spin inline-block w-3 h-3 border-2 border-white/30 border-t-white rounded-full"></span>
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Tandai Kembali'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- HIDDEN FORM untuk POST --}}
    <form id="return-rental-form" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="status" x-model="returnStatus">
        <input type="hidden" name="notes" x-model="returnNotes">
    </form>

    {{-- ALPINE.JS SCRIPT --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('rentalManager', () => ({
                showReturnModal: false,
                selectedRentalId: null,
                selectedCostumeType: '',
                returnStatus: 'rented',
                returnNotes: '',
                isSubmitting: false,

                init() {
                    window.openReturnModal = (rentalId, costumeType) => {
                        this.selectedRentalId = rentalId;
                        this.selectedCostumeType = costumeType;
                        this.returnStatus = 'rented';
                        this.returnNotes = '';
                        this.showReturnModal = true;
                    };
                },

                closeReturnModal() {
                    this.showReturnModal = false;
                    this.selectedRentalId = null;
                    this.returnStatus = 'rented';
                    this.returnNotes = '';
                },

                submitReturn() {
                    if (this.isSubmitting) return;

                    this.isSubmitting = true;

                    const form = document.getElementById('return-rental-form');
                    form.setAttribute('action', `/admin/costume-rentals/${this.selectedRentalId}/return`);

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: this.returnStatus,
                            notes: this.returnNotes
                        })
                    })
                    .then(response => {
                        if (response.redirected) {
                            window.location.href = response.url;
                        } else {
                            return response.json();
                        }
                    })
                    .then(data => {
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan: ' + error.message);
                        this.isSubmitting = false;
                    });
                }
            }));
        });
    </script>
</div>

@endsection
