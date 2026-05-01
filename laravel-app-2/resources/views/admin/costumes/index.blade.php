@extends('layouts.admin')

@section('title', 'Costume & Logistik – ART-HUB')
@section('page_title', 'Costume & Logistik')
@section('page_subtitle', 'Inventaris aset sanggar dan status persewaan vendor eksternal.')

@section('content')

{{-- ── ASET KOSTUM SANGGAR ── --}}
<div class="mb-10">
    <div class="flex items-center gap-2 mb-6">
        <h2 class="font-headline text-xl text-primary font-semibold">
            <i class="bi bi-tag-fill text-secondary me-1"></i> Inventaris Aset Sanggar
        </h2>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($sanggarCostumes as $c)
        @php
            $isDamaged = $c->condition === 'damaged';
            $isMaintenance = $c->condition === 'maintenance';
            $cardClass = $isDamaged ? 'bg-red-500/5 border-red-500/30' : ($isMaintenance ? 'bg-orange-500/5 border-orange-500/30' : 'bg-surface-container-lowest border-outline-variant/30');
        @endphp
        <div class="rounded-xl p-5 border shadow-[0_4px_12px_rgba(54,31,26,0.02)] {{ $cardClass }} transition-colors hover:shadow-[0_8px_20px_rgba(54,31,26,0.04)]">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-headline font-semibold text-on-surface leading-tight text-lg">{{ $c->name }}</h3>
                @if($c->condition === 'good') 
                    <span class="inline-block px-2 py-0.5 rounded border border-green-500/20 bg-green-500/10 font-label text-[0.6rem] font-bold uppercase tracking-wider text-green-600 flex-shrink-0">Baik</span>
                @elseif($c->condition === 'damaged') 
                    <span class="inline-block px-2 py-0.5 rounded border border-red-500/20 bg-red-500/10 font-label text-[0.6rem] font-bold uppercase tracking-wider text-red-600 flex-shrink-0">Rusak</span>
                @else 
                    <span class="inline-block px-2 py-0.5 rounded border border-orange-500/20 bg-orange-500/10 font-label text-[0.6rem] font-bold uppercase tracking-wider text-orange-600 flex-shrink-0">MTC</span>
                @endif
            </div>
            <div class="flex justify-between items-end">
                <div>
                    <div class="font-label text-xs uppercase tracking-widest text-outline mb-1">Kategori</div>
                    <div class="font-body text-sm text-on-surface-variant capitalize">{{ str_replace('_', ' ', $c->category) }}</div>
                </div>
                <div class="text-right">
                    <div class="font-label text-xs uppercase tracking-widest text-outline mb-1">Tersedia</div>
                    <div class="font-headline text-2xl font-bold text-primary leading-none">{{ $c->quantity }}<span class="text-sm font-normal text-outline">x</span></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ── SEWA VENDOR (RENTALS) ── --}}
<div>
    <div class="flex items-center gap-2 mb-6">
        <h2 class="font-headline text-xl text-primary font-semibold">
            <i class="bi bi-shop text-secondary me-1"></i> Transaksi Sewa Vendor Eksternal
        </h2>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
        <table class="w-full">
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
                            <span class="inline-block px-2.5 py-1 rounded border border-red-500/20 bg-red-500/10 font-label text-[0.65rem] font-bold uppercase tracking-wider text-red-600">OVERDUE</span>
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
    </div>
</div>

@endsection
