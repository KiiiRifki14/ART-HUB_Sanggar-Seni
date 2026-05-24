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
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-md overflow-hidden relative">
        <table class="w-full hidden md:table text-left border-collapse">
            <thead>
                <tr class="bg-surface-container/20 border-b border-outline-variant/30">
                    <th class="font-label text-[0.7rem] uppercase tracking-widest text-outline font-bold px-6 py-5">Item Aset</th>
                    <th class="font-label text-[0.7rem] uppercase tracking-widest text-outline font-bold px-6 py-5 w-40">Kategori</th>
                    <th class="font-label text-[0.7rem] uppercase tracking-widest text-outline font-bold px-6 py-5 text-center w-32">Kondisi</th>
                    <th class="font-label text-[0.7rem] uppercase tracking-widest text-outline font-bold px-6 py-5 text-center w-32">Jumlah</th>
                    <th class="font-label text-[0.7rem] uppercase tracking-widest text-outline font-bold px-6 py-5 text-right w-36">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10 bg-surface-container-lowest">
                @forelse($sanggarCostumes as $c)
                @php
                    $isDamaged = $c->condition === 'damaged';
                    $isMaintenance = $c->condition === 'maintenance';
                    $rowClass = $isDamaged ? 'bg-red-500/5 hover:bg-red-500/10' : ($isMaintenance ? 'bg-orange-500/5 hover:bg-orange-500/10' : 'hover:bg-surface-container-low/50');
                @endphp
                <tr class="transition-colors duration-200 group {{ $rowClass }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-outline shadow-sm flex-shrink-0 border border-outline-variant/20">
                                <i class="bi bi-box-seam text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-headline font-semibold text-on-surface text-sm sm:text-base group-hover:text-primary transition-colors">{{ $c->name }}</h3>
                                <p class="font-label text-[0.65rem] text-outline tracking-wider mt-0.5">ID: {{ str_pad($c->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-low border border-outline-variant/30 font-label text-xs text-on-surface-variant capitalize tracking-wide shadow-sm">
                            <i class="bi bi-tags-fill text-outline/70"></i> {{ str_replace('_', ' ', $c->category) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($c->condition === 'good')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full border border-green-500/30 bg-green-500/10 font-label text-[0.65rem] font-bold uppercase tracking-widest text-green-700 shadow-sm">
                                <i class="bi bi-check-circle-fill"></i> Baik
                            </span>
                        @elseif($c->condition === 'damaged')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full border border-red-500/30 bg-red-500/10 font-label text-[0.65rem] font-bold uppercase tracking-widest text-red-700 shadow-sm">
                                <i class="bi bi-x-circle-fill"></i> Rusak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full border border-orange-500/30 bg-orange-500/10 font-label text-[0.65rem] font-bold uppercase tracking-widest text-orange-700 shadow-sm">
                                <i class="bi bi-wrench-adjustable"></i> MTC
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 border border-primary/20 font-headline font-bold text-primary text-sm shadow-sm">
                            {{ $c->quantity }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-100 lg:opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <a href="{{ route('admin.costumes.edit-asset', $c->id) }}" class="w-9 h-9 rounded-xl border border-outline-variant/50 bg-surface-container-lowest text-outline hover:text-white hover:bg-primary hover:border-primary flex items-center justify-center transition-all shadow-sm" title="Edit Aset">
                                <i class="bi bi-pencil-fill text-[0.7rem]"></i>
                            </a>
                            <form action="{{ route('admin.costumes.destroy-asset', $c->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-9 h-9 rounded-xl border border-red-200/50 bg-surface-container-lowest text-red-500 hover:text-white hover:bg-red-500 flex items-center justify-center transition-all shadow-sm" title="Hapus Aset">
                                    <i class="bi bi-trash-fill text-[0.7rem]"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center text-on-surface-variant">
                        <div class="w-20 h-20 bg-surface-container rounded-full flex items-center justify-center mx-auto mb-4 border border-outline-variant/30">
                            <i class="bi bi-box-seam text-3xl text-outline opacity-50"></i>
                        </div>
                        <p class="font-headline text-lg font-semibold text-on-surface mb-1">Inventaris Kosong</p>
                        <p class="font-label text-xs tracking-widest uppercase text-outline">Belum ada data aset tersimpan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Mobile Cards View --}}
        <div class="block md:hidden divide-y divide-outline-variant/10 bg-surface-container-lowest">
            @forelse($sanggarCostumes as $c)
            @php
                $isDamaged = $c->condition === 'damaged';
                $isMaintenance = $c->condition === 'maintenance';
                $cardClass = $isDamaged ? 'bg-red-500/5' : ($isMaintenance ? 'bg-orange-500/5' : 'hover:bg-surface-container-low/30');
            @endphp
            <div class="p-4 sm:p-5 {{ $cardClass }} transition-colors">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-surface-container flex flex-col items-center justify-center text-outline shadow-sm flex-shrink-0 border border-outline-variant/20">
                        <i class="bi bi-box-seam text-xl"></i>
                    </div>
                    
                    <div class="flex-grow min-w-0">
                        <div class="flex justify-between items-start mb-1.5">
                            <h3 class="font-headline font-semibold text-on-surface text-sm truncate pr-2" title="{{ $c->name }}">{{ $c->name }}</h3>
                            @if($c->condition === 'good')
                                <span class="flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded border border-green-500/30 bg-green-500/10 font-label text-[0.6rem] font-bold uppercase tracking-widest text-green-700">Baik</span>
                            @elseif($c->condition === 'damaged')
                                <span class="flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded border border-red-500/30 bg-red-500/10 font-label text-[0.6rem] font-bold uppercase tracking-widest text-red-700">Rusak</span>
                            @else
                                <span class="flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded border border-orange-500/30 bg-orange-500/10 font-label text-[0.6rem] font-bold uppercase tracking-widest text-orange-700">MTC</span>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-3 mb-3">
                            <span class="font-label text-[0.6rem] tracking-wider text-outline uppercase border-r border-outline-variant/30 pr-3">ID: {{ str_pad($c->id, 4, '0', STR_PAD_LEFT) }}</span>
                            <span class="font-label text-[0.65rem] text-on-surface-variant capitalize flex items-center gap-1"><i class="bi bi-tag-fill text-outline/50"></i>{{ str_replace('_', ' ', $c->category) }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between pt-3 border-t border-outline-variant/20">
                            <div class="flex items-center gap-2">
                                <span class="font-label text-[0.6rem] uppercase tracking-widest text-outline font-bold">Stok:</span>
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-primary/10 border border-primary/20 font-headline font-bold text-primary text-xs shadow-sm">{{ $c->quantity }}</span>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.costumes.edit-asset', $c->id) }}" class="w-8 h-8 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-outline hover:text-white hover:bg-primary hover:border-primary flex items-center justify-center transition-all shadow-sm">
                                    <i class="bi bi-pencil-fill text-xs"></i>
                                </a>
                                <form action="{{ route('admin.costumes.destroy-asset', $c->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg border border-red-200/50 bg-surface-container-lowest text-red-500 hover:text-white hover:bg-red-500 flex items-center justify-center transition-all shadow-sm">
                                        <i class="bi bi-trash-fill text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-on-surface-variant">
                <div class="w-16 h-16 bg-surface-container rounded-full flex items-center justify-center mx-auto mb-3 border border-outline-variant/30">
                    <i class="bi bi-box-seam text-2xl text-outline opacity-50"></i>
                </div>
                <p class="font-headline text-sm font-semibold text-on-surface mb-1">Inventaris Kosong</p>
                <p class="font-label text-[0.6rem] tracking-widest uppercase text-outline">Belum ada data aset tersimpan.</p>
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
                    <div class="text-right">
                        @if($r->overdue_fine > 0)
                            <div class="font-label text-[0.52rem] uppercase tracking-widest text-outline font-bold mb-0.5">Denda</div>
                            <div class="font-headline font-bold text-red-600 text-xs">Rp {{ number_format($r->overdue_fine, 0, ',', '.') }}</div>
                            <div class="font-label text-[0.5rem] text-outline">{{ $r->overdue_days }} hari &times; Rp50k</div>
                        @else
                            <span class="font-label text-[0.5rem] text-outline">—</span>
                        @endif
                    </div>
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

@endsection
