@extends('layouts.admin')

@section('title', 'Kostum & Logistik – ART-HUB')
@section('page_title', 'Kostum & Logistik')
@section('page_subtitle', 'Inventaris aset sanggar dan status persewaan vendor eksternal.')

@section('content')

{{-- ── ASET KOSTUM SANGGAR ── --}}
<div class="mb-10">
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-headline text-xl text-primary font-semibold flex items-center gap-2">
            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center"><i class="bi bi-tag-fill text-primary"></i></div>
            Inventaris Aset Sanggar
        </h2>
        <a href="{{ route('admin.costumes.create-asset') }}" class="px-4 py-2 rounded-lg bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-sm flex items-center gap-1.5 inline-flex">
    <i class="bi bi-plus-lg"></i> Tambah Aset
</a>
    </div>

    {{-- Table untuk Desktop dan Tablet --}}
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
        <table class="w-full hidden md:table">
            <thead class="bg-surface-container-low border-b border-outline-variant/20">
                <tr>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Nama Aset</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Kategori</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Kondisi</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Jumlah</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($sanggarCostumes as $c)
                @php
                    $isDamaged = $c->condition === 'damaged';
                    $isMaintenance = $c->condition === 'maintenance';
                    $rowClass = $isDamaged ? 'bg-red-500/5 hover:bg-red-500/10' : ($isMaintenance ? 'bg-orange-500/5 hover:bg-orange-500/10' : 'hover:bg-surface-container-low/50');
                @endphp
                <tr class="transition-colors {{ $rowClass }}">
                    <td class="px-6 py-4">
                        <div class="font-body font-semibold text-on-surface text-sm">{{ $c->name }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-body text-xs text-on-surface-variant capitalize">{{ str_replace('_', ' ', $c->category) }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($c->condition === 'good')
                            <span class="inline-block px-2.5 py-0.5 rounded border border-green-500/20 bg-green-500/10 font-label text-[0.65rem] font-bold uppercase tracking-wider text-green-600">Baik</span>
                        @elseif($c->condition === 'damaged')
                            <span class="inline-block px-2.5 py-0.5 rounded border border-red-500/20 bg-red-500/10 font-label text-[0.65rem] font-bold uppercase tracking-wider text-red-600">Rusak</span>
                        @else
                            <span class="inline-block px-2.5 py-0.5 rounded border border-orange-500/20 bg-orange-500/10 font-label text-[0.65rem] font-bold uppercase tracking-wider text-orange-600">MTC</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-surface-container font-headline font-bold text-primary text-sm">
                            {{ $c->quantity }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.costumes.edit-asset', $c->id) }}" class="p-1.5 rounded-lg border border-outline-variant/50 text-outline hover:text-primary hover:border-primary transition-all" title="Edit Aset">
                                <i class="bi bi-pencil-fill text-xs"></i>
                            </a>
                            <form action="{{ route('admin.costumes.destroy-asset', $c->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg border border-red-200/50 text-red-500 hover:bg-red-50 transition-all" title="Hapus Aset">
                                    <i class="bi bi-trash-fill text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-on-surface-variant">
                        <i class="bi bi-tag text-4xl block mb-2 opacity-30"></i>
                        <p class="text-sm">Belum ada data inventaris aset.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Mobile Cards View --}}
        <div class="block md:hidden divide-y divide-outline-variant/20">
            @forelse($sanggarCostumes as $c)
            @php
                $isDamaged = $c->condition === 'damaged';
                $isMaintenance = $c->condition === 'maintenance';
                $cardClass = $isDamaged ? 'bg-red-500/5 border-l-4 border-l-red-500' : ($isMaintenance ? 'bg-orange-500/5 border-l-4 border-l-orange-500' : 'hover:bg-surface-container-low/50 border-l-4 border-l-transparent');
            @endphp
            <div class="p-3 space-y-2 {{ $cardClass }}">
                <div class="flex justify-between items-center">
                    <span class="inline-block px-2 py-0.5 rounded bg-primary/10 text-primary border border-primary/20 font-label text-[0.58rem] font-bold tracking-wider capitalize">
                        {{ str_replace('_', ' ', $c->category) }}
                    </span>
                    <div>
                        @if($c->condition === 'good')
                            <span class="inline-block px-1.5 py-0.5 rounded border border-green-500/20 bg-green-500/10 font-label text-[0.52rem] font-bold uppercase tracking-wider text-green-600">Baik</span>
                        @elseif($c->condition === 'damaged')
                            <span class="inline-block px-1.5 py-0.5 rounded border border-red-500/20 bg-red-500/10 font-label text-[0.52rem] font-bold uppercase tracking-wider text-red-600">Rusak</span>
                        @else
                            <span class="inline-block px-1.5 py-0.5 rounded border border-orange-500/20 bg-orange-500/10 font-label text-[0.52rem] font-bold uppercase tracking-wider text-orange-600">MTC</span>
                        @endif
                    </div>
                </div>
                <div class="flex justify-between items-start gap-4">
                    <div>
                        <div class="font-body font-semibold text-on-surface text-xs">{{ $c->name }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-label text-[0.52rem] uppercase tracking-widest text-outline font-bold mb-0.5">Qty</div>
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-surface-container font-headline font-bold text-primary text-[0.65rem]">
                            {{ $c->quantity }}
                        </span>
                    </div>
                </div>
                <div class="flex justify-end pt-1.5 border-t border-outline-variant/10">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.costumes.edit-asset', $c->id) }}" class="p-1.5 rounded-lg border border-outline-variant/50 text-outline hover:text-primary hover:border-primary transition-all" title="Edit Aset">
                            <i class="bi bi-pencil-fill text-xs"></i>
                        </a>
                        <form action="{{ route('admin.costumes.destroy-asset', $c->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset ini?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg border border-red-200/50 text-red-500 hover:bg-red-50 transition-all" title="Hapus Aset">
                                <i class="bi bi-trash-fill text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-outline">
                <i class="bi bi-tag text-2xl mb-1.5 block opacity-30"></i>
                <p class="font-body text-xs font-semibold text-on-surface mb-1">Belum ada data inventaris aset.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ── SEWA VENDOR (RENTALS) ── --}}
<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-headline text-xl text-primary font-semibold flex items-center gap-2">
            <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center"><i class="bi bi-shop text-secondary"></i></div>
            Transaksi Sewa Vendor Eksternal
        </h2>
        <a href="{{ route('admin.costumes.create-rental') }}" class="px-4 py-2 rounded-lg bg-secondary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-secondary-container transition-colors shadow-sm flex items-center gap-1.5 inline-flex">
    <i class="bi bi-plus-lg"></i> Nambah Sewaan
</a>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
        <table class="w-full hidden md:table">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Event</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Vendor & Item</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Qty</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Tgl Kembali</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Status</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Denda Telat</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($vendorRentals as $r)
                @php
                    $isOverdue = !$r->returned_date && \Carbon\Carbon::parse($r->due_date)->isPast();
                    $rowClass = $isOverdue ? 'bg-red-500/5 border-l-4 border-l-red-500' : 'hover:bg-surface-container-low/50 border-l-4 border-l-transparent';
                @endphp
                <tr class="{{ $rowClass }} transition-colors">
                    <td class="px-6 py-4 pl-5">
                        <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">
                            {{ $r->event->event_code ?? '-' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-body font-semibold text-on-surface text-sm">{{ $r->vendor->name ?? '-' }}</div>
                        <div class="font-label text-xs text-outline">{{ $r->costume_type }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-surface-container font-headline font-bold text-primary text-sm">
                            {{ $r->quantity }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-body text-sm {{ $isOverdue ? 'text-red-600 font-bold' : 'text-on-surface' }}">
                            {{ \Carbon\Carbon::parse($r->due_date)->format('d M Y') }}
                        </div>
                        @if($isOverdue)
                            <div class="font-label text-[0.6rem] text-red-500 font-bold uppercase tracking-widest flex items-center gap-1 mt-1">
                                <i class="bi bi-exclamation-triangle-fill"></i> Lewat Deadline
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($r->status === 'rented' && $isOverdue)
                            <span class="inline-block px-2.5 py-1 rounded border border-red-500/20 bg-red-500/10 font-label text-[0.65rem] font-bold uppercase tracking-wider text-red-600">TERLAMBAT</span>
                        @elseif($r->status === 'rented')
                            <span class="inline-block px-2.5 py-1 rounded border border-orange-500/20 bg-orange-500/10 font-label text-[0.65rem] font-bold uppercase tracking-wider text-orange-600">DIPINJAM</span>
                        @else
                            <span class="inline-block px-2.5 py-1 rounded border border-green-500/20 bg-green-500/10 font-label text-[0.65rem] font-bold uppercase tracking-wider text-green-600">KEMBALI</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($r->overdue_fine > 0)
                            <div class="font-headline font-bold text-red-600 text-sm">Rp {{ number_format($r->overdue_fine, 0, ',', '.') }}</div>
                            <div class="font-label text-xs text-outline">{{ $r->overdue_days }} hari &times; Rp50k</div>
                        @else
                            <span class="font-label text-xs text-outline">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if(!$r->returned_date)
                            <a href="{{ route('admin.costumes.edit-rental', $r->id) }}" class="p-1.5 rounded-lg border border-outline-variant/50 text-outline hover:text-primary hover:border-primary transition-all" title="Edit Sewaan">
                                <i class="bi bi-pencil-fill text-xs"></i>
                            </a>
                            @endif
                            <button type="button" @click="openReturnModal({{ $r->id }}, '{{ $r->costume_type }}')" class="p-1.5 rounded-lg border border-secondary/50 text-secondary hover:bg-secondary/10 transition-all" title="Tandai Kembali">
                                <i class="bi bi-check-circle-fill text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-20 text-center">
                        <i class="bi bi-shop text-4xl text-outline mb-4 block"></i>
                        <p class="font-headline text-lg text-on-surface font-semibold mb-1">Belum ada sewa vendor</p>
                        <p class="font-label text-xs uppercase tracking-widest text-outline">Aset sanggar cukup untuk event saat ini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Mobile Cards View --}}
        <div class="block md:hidden divide-y divide-outline-variant/20">
            @forelse($vendorRentals as $r)
            @php
                $isOverdue = !$r->returned_date && \Carbon\Carbon::parse($r->due_date)->isPast();
                $cardClass = $isOverdue ? 'bg-red-500/5 border-l-4 border-l-red-500' : 'hover:bg-surface-container-low/50 border-l-4 border-l-transparent';
            @endphp
            <div class="p-3 space-y-2 {{ $cardClass }}">
                <div class="flex justify-between items-center">
                    <span class="inline-block px-2 py-0.5 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.58rem] font-bold tracking-wider">
                        {{ $r->event->event_code ?? '-' }}
                    </span>
                    <div>
                        @if($r->status === 'rented' && $isOverdue)
                            <span class="inline-block px-1.5 py-0.5 rounded border border-red-500/20 bg-red-500/10 font-label text-[0.52rem] font-bold uppercase tracking-wider text-red-600">TERLAMBAT</span>
                        @elseif($r->status === 'rented')
                            <span class="inline-block px-1.5 py-0.5 rounded border border-orange-500/20 bg-orange-500/10 font-label text-[0.52rem] font-bold uppercase tracking-wider text-orange-600">DIPINJAM</span>
                        @else
                            <span class="inline-block px-1.5 py-0.5 rounded border border-green-500/20 bg-green-500/10 font-label text-[0.52rem] font-bold uppercase tracking-wider text-green-600">KEMBALI</span>
                        @endif
                    </div>
                </div>
                <div class="flex justify-between items-start gap-4">
                    <div>
                        <div class="font-body font-semibold text-on-surface text-xs">{{ $r->vendor->name ?? '-' }}</div>
                        <div class="font-label text-[0.65rem] text-outline mt-0.5">{{ $r->costume_type }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-label text-[0.52rem] uppercase tracking-widest text-outline font-bold mb-0.5">Qty</div>
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-surface-container font-headline font-bold text-primary text-[0.65rem]">
                            {{ $r->quantity }}
                        </span>
                    </div>
                </div>
                <div class="flex justify-between items-end pt-1.5 border-t border-outline-variant/10">
                    <div>
                        <div class="font-label text-[0.52rem] uppercase tracking-widest text-outline font-bold mb-0.5">Tgl Kembali</div>
                        <div class="font-body text-xs {{ $isOverdue ? 'text-red-600 font-bold' : 'text-on-surface' }}">
                            {{ \Carbon\Carbon::parse($r->due_date)->format('d M Y') }}
                        </div>
                    </div>
                    <div class="text-right flex flex-col items-end">
                        @if($r->overdue_fine > 0)
                            <div class="font-label text-[0.52rem] uppercase tracking-widest text-outline font-bold mb-0.5">Denda</div>
                            <div class="font-headline font-bold text-red-600 text-xs">Rp {{ number_format($r->overdue_fine, 0, ',', '.') }}</div>
                            <div class="font-label text-[0.5rem] text-outline">{{ $r->overdue_days }} hari &times; Rp50k</div>
                        @else
                            <span class="font-label text-[0.5rem] text-outline">—</span>
                        @endif
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    @if(!$r->returned_date)
                    <a href="{{ route('admin.costumes.edit-rental', $r->id) }}" class="p-1.5 rounded-lg border border-outline-variant/50 text-outline hover:text-primary hover:border-primary transition-all" title="Edit Sewaan">
                        <i class="bi bi-pencil-fill text-xs"></i>
                    </a>
                    @endif
                    <button type="button" @click="openReturnModal({{ $r->id }}, '{{ $r->costume_type }}')" class="p-1.5 rounded-lg border border-secondary/50 text-secondary hover:bg-secondary/10 transition-all" title="Tandai Kembali">
                        <i class="bi bi-check-circle-fill text-xs"></i>
                    </button>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-outline">
                <i class="bi bi-shop text-2xl mb-1.5 block"></i>
                <p class="font-body text-xs font-semibold text-on-surface mb-1">Belum ada sewa vendor</p>
                <p class="font-label text-[0.55rem] uppercase tracking-widest text-outline">Aset sanggar cukup untuk event saat ini</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ── RIWAYAT PENGGUNAAN KOSTUM ── --}}
<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-headline text-xl text-primary font-semibold flex items-center gap-2">
            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center"><i class="bi bi-clock-history text-primary"></i></div>
            Riwayat Penggunaan Aset Sanggar
        </h2>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
        <table class="w-full hidden md:table">
            <thead class="bg-surface-container-low border-b border-outline-variant/20">
                <tr>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Kostum</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Event</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Status</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Tgl Diambil</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Tgl Dikembalikan</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
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
                    <td class="px-6 py-4">
                        <div class="font-body font-semibold text-on-surface text-sm">{{ $usage->costume->name ?? '-' }}</div>
                        <div class="font-label text-xs text-outline">Qty: {{ $usage->quantity ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-2.5 py-1 rounded bg-primary-container/40 text-on-primary-container border border-primary/20 font-label text-[0.65rem] font-bold tracking-wider">
                            {{ $usage->event->event_code ?? '-' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-block px-2.5 py-1 rounded border {{ $statusColor }} font-label text-[0.65rem] font-bold uppercase tracking-wider">
                            {{ $statusLabel[$usage->status] ?? ucfirst($usage->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-body text-sm text-on-surface">{{ \Carbon\Carbon::parse($usage->checked_out_date)->format('d M Y') ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($usage->actual_return_date)
                            <div class="font-body text-sm text-on-surface">{{ \Carbon\Carbon::parse($usage->actual_return_date)->format('d M Y') }}</div>
                        @else
                            <span class="font-label text-xs text-outline italic">Belum dikembalikan</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($usage->status === 'checked_out')
                        <button type="button" @click="openReturnUsageModal({{ $usage->id }}, '{{ $usage->costume->name }}')" class="p-1.5 rounded-lg border border-primary/50 text-primary hover:bg-primary/10 transition-all" title="Tandai Kembali">
                            <i class="bi bi-check-circle-fill text-xs"></i>
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-on-surface-variant">
                        <i class="bi bi-clock-history text-4xl block mb-2 opacity-30"></i>
                        <p class="text-sm">Belum ada riwayat penggunaan kostum.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

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
            <div class="p-3 space-y-2 {{ $cardClass }}">
                <div class="flex justify-between items-center">
                    <span class="inline-block px-2 py-0.5 rounded bg-primary-container/40 text-on-primary-container border border-primary/20 font-label text-[0.58rem] font-bold tracking-wider">
                        {{ $usage->event->event_code ?? '-' }}
                    </span>
                    <span class="inline-block px-1.5 py-0.5 rounded font-label text-[0.52rem] font-bold uppercase tracking-wider text-primary">
                        {{ $statusLabel[$usage->status] ?? ucfirst($usage->status) }}
                    </span>
                </div>
                <div class="flex justify-between items-start gap-4">
                    <div>
                        <div class="font-body font-semibold text-on-surface text-xs">{{ $usage->costume->name ?? '-' }}</div>
                        <div class="font-label text-[0.65rem] text-outline mt-0.5">Qty: {{ $usage->quantity ?? '-' }}</div>
                    </div>
                </div>
                <div class="flex justify-between items-end pt-1.5 border-t border-outline-variant/10 gap-2">
                    <div>
                        <div class="font-label text-[0.52rem] uppercase tracking-widest text-outline font-bold mb-0.5">Diambil</div>
                        <div class="font-body text-xs text-on-surface">{{ \Carbon\Carbon::parse($usage->checked_out_date)->format('d M Y') }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-label text-[0.52rem] uppercase tracking-widest text-outline font-bold mb-0.5">Dikembalikan</div>
                        @if($usage->actual_return_date)
                            <div class="font-body text-xs text-on-surface">{{ \Carbon\Carbon::parse($usage->actual_return_date)->format('d M Y') }}</div>
                        @else
                            <span class="font-label text-xs text-outline italic">Belum</span>
                        @endif
                    </div>
                </div>
                @if($usage->status === 'checked_out')
                <div class="flex justify-end pt-2">
                    <button type="button" @click="openReturnUsageModal({{ $usage->id }}, '{{ $usage->costume->name }}')" class="p-1.5 rounded-lg border border-primary/50 text-primary hover:bg-primary/10 transition-all" title="Tandai Kembali">
                        <i class="bi bi-check-circle-fill text-xs"></i>
                    </button>
                </div>
                @endif
            </div>
            @empty
            <div class="p-6 text-center text-outline">
                <i class="bi bi-clock-history text-2xl mb-1.5 block opacity-30"></i>
                <p class="font-body text-xs font-semibold text-on-surface mb-1">Belum ada riwayat penggunaan</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ── RETURN USAGE MODAL ── --}}
<div x-show="showReturnUsageModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-transition.opacity style="display: none;">
    <div @click.away="closeReturnUsageModal()" class="bg-surface-container-lowest p-6 rounded-2xl w-full max-w-md shadow-2xl border border-outline-variant/30 transform transition-all" x-transition.scale.95>
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-outline-variant/20">
            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                <i class="bi bi-check-circle text-xl"></i>
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

{{-- ── RETURN RENTAL MODAL ── --}}
<div x-data="rentalManager" x-cloak>
    <div x-show="showReturnModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-transition.opacity>
        <div @click.away="closeReturnModal()" class="bg-surface-container-lowest p-6 rounded-2xl w-full max-w-md shadow-2xl border border-outline-variant/30 transform transition-all" x-transition.scale.95>
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-outline-variant/20">
                <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary flex-shrink-0">
                    <i class="bi bi-check-circle text-xl"></i>
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

                // Return Rental
                openReturnModal(rentalId, costumeType) {
                    this.selectedRentalId = rentalId;
                    this.selectedCostumeType = costumeType;
                    this.returnStatus = 'rented';
                    this.returnNotes = '';
                    this.showReturnModal = true;
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
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan: ' + error.message);
                        this.isSubmitting = false;
                    });
                },

                // Return Usage
                showReturnUsageModal: false,
                selectedUsageId: null,
                selectedUsageCostumeType: '',
                returnUsageStatus: 'returned',
                returnUsageNotes: '',

                openReturnUsageModal(usageId, costumeType) {
                    this.selectedUsageId = usageId;
                    this.selectedUsageCostumeType = costumeType;
                    this.returnUsageStatus = 'returned';
                    this.returnUsageNotes = '';
                    this.showReturnUsageModal = true;
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
