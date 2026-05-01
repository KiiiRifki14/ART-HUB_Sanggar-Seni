@extends('layouts.admin')

@section('title', 'Detail Booking – ART-HUB')
@section('page_title', 'Detail Booking')
@section('page_subtitle', 'Verifikasi detail pementasan dan kalkulasi laba.')

@section('content')

{{-- BACK NAV --}}
<div class="flex items-center gap-2 mb-6 font-label text-xs uppercase tracking-widest font-bold">
    <a href="{{ route('admin.bookings.index') }}" class="text-on-surface-variant hover:text-secondary transition-colors flex items-center gap-1.5">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
    <span class="text-outline-variant">/</span>
    <span class="text-outline">Booking #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
</div>

<div class="flex flex-col lg:flex-row gap-6 items-start mb-8">

    {{-- KIRI: INFO BOOKING --}}
    <div class="flex-grow w-full space-y-6">
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
            <div class="px-6 py-5 border-b border-outline-variant/20 flex items-center justify-between">
                <h3 class="font-headline text-lg font-bold text-primary flex items-center gap-2">
                    <i class="bi bi-receipt text-secondary"></i> Detail Booking
                </h3>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                <div>
                    <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Klien</div>
                    <div class="font-headline text-lg font-bold text-on-surface">{{ $booking->client_name ?? $booking->client->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Kontak Klien</div>
                    <div class="font-body text-base text-on-surface-variant">{{ $booking->client_phone ?? '-' }}</div>
                </div>
                
                <div class="md:col-span-2"><hr class="border-outline-variant/20 my-0"></div>

                <div>
                    <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Jenis Pementasan</div>
                    <div class="font-body font-bold text-on-surface capitalize">{{ str_replace('_', ' ', $booking->event_type) }}</div>
                </div>
                <div>
                    <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Tanggal</div>
                    <div class="font-body font-bold text-on-surface">{{ \Carbon\Carbon::parse($booking->event_date)->isoFormat('dddd, D MMMM Y') }}</div>
                </div>
                
                <div class="md:col-span-2"><hr class="border-outline-variant/20 my-0"></div>

                <div>
                    <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Waktu</div>
                    <div class="font-body font-bold text-on-surface">{{ \Carbon\Carbon::parse($booking->event_start)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->event_end)->format('H:i') }}</div>
                </div>
                <div>
                    <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Venue</div>
                    <div class="font-body font-bold text-on-surface">{{ $booking->venue }}</div>
                </div>

                <div class="md:col-span-2"><hr class="border-outline-variant/20 my-0"></div>

                <div>
                    <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Sumber Booking</div>
                    <span class="inline-block px-2.5 py-1 rounded bg-surface-container-highest text-on-surface-variant border border-outline-variant/30 font-label text-[0.65rem] font-bold uppercase tracking-wider">
                        {{ strtoupper($booking->booking_source) }}
                    </span>
                </div>
                <div>
                    <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-1">Status</div>
                    @php
                        $statusMap = [
                            'pending'   => ['label'=>'PENDING',   'cls'=>'bg-orange-500/10 text-orange-600 border-orange-500/20'],
                            'dp_paid'   => ['label'=>'DP PAID',   'cls'=>'bg-blue-500/10 text-blue-600 border-blue-500/20'],
                            'confirmed' => ['label'=>'CONFIRMED', 'cls'=>'bg-green-500/10 text-green-600 border-green-500/20'],
                            'completed' => ['label'=>'COMPLETED', 'cls'=>'bg-green-500/10 text-green-600 border-green-500/20'],
                            'cancelled' => ['label'=>'CANCELLED', 'cls'=>'bg-red-500/10 text-red-600 border-red-500/20'],
                        ];
                        $st = $statusMap[$booking->status] ?? ['label'=>strtoupper($booking->status),'cls'=>'bg-surface-container text-outline border-outline-variant/30'];
                    @endphp
                    <span class="inline-block px-2.5 py-1 rounded border font-label text-[0.65rem] font-bold uppercase tracking-wider {{ $st['cls'] }}">
                        {{ $st['label'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- KANAN: PANEL KEUANGAN --}}
    <div class="w-full lg:w-96 flex-shrink-0">
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl overflow-hidden sticky top-24 shadow-[0_8px_24px_rgba(54,31,26,0.04)]">
            <div class="bg-primary px-5 py-4 font-label text-xs uppercase tracking-widest font-bold flex items-center gap-2 text-white border-b border-outline-variant/20">
                <i class="bi bi-safe2-fill text-secondary"></i> Kalkulasi Laba
            </div>

            <div class="p-5 space-y-4 font-body">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-on-surface-variant font-medium">Total Harga (Kontrak)</span>
                    <div class="text-right">
                        <span class="font-headline font-bold text-lg text-primary block">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        @if($booking->status === 'pending')
                            <button type="button" class="mt-1 text-[0.65rem] font-label font-bold uppercase tracking-wider text-secondary hover:text-primary transition-colors flex items-center justify-end gap-1" data-bs-toggle="modal" data-bs-target="#modalUpdateHarga"><i class="bi bi-pencil"></i> Nego</button>
                        @endif
                    </div>
                </div>
                
                <div class="flex justify-between items-center text-sm p-3 rounded-lg bg-secondary/10 border border-secondary/20">
                    <span class="text-secondary-container font-bold">DP Masuk (50%)</span>
                    <span class="font-bold text-secondary text-lg">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
                </div>
                
                <hr class="border-outline-variant/20 border-dashed my-2">
                
                <div class="flex justify-between items-center text-sm">
                    <span class="text-on-surface-variant font-medium flex items-center gap-1.5"><i class="bi bi-lock-fill text-secondary"></i> Fixed Profit (30%)</span>
                    <span class="font-bold text-primary">Rp {{ number_format($booking->total_price * 0.30, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-on-surface-variant font-medium">Budget Operasional</span>
                    <span class="font-bold text-on-surface">Rp {{ number_format($booking->dp_amount - ($booking->total_price * 0.30), 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-on-surface-variant font-medium">Safety Buffer (10%)</span>
                    <span class="font-bold text-green-600">Rp {{ number_format(($booking->dp_amount - ($booking->total_price * 0.30)) * 0.10, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="p-5 bg-surface-container-low border-t border-outline-variant/30">
                @if($booking->status === 'pending')
                @php $delMsg = 'Kunci laba dari DP booking ini?\nAksi ini TIDAK BISA DIBATALKAN dan akan mengalokasikan profit pimpinan.'; @endphp
                <form method="POST" action="{{ route('admin.bookings.confirm', $booking->id) }}" class="m-0"
                      onsubmit="return confirm('{{ $delMsg }}')">
                    @csrf
                    <button type="submit" class="w-full flex justify-center items-center gap-2 bg-gradient-to-br from-primary-container to-primary text-white px-4 py-3 rounded-xl font-label text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-md">
                        <i class="bi bi-lock-fill"></i> Kunci Laba
                    </button>
                </form>
                @elseif(in_array($booking->status, ['dp_paid','confirmed','completed']))
                <div class="p-4 text-center rounded-xl bg-green-500/10 border border-green-500/20 text-green-700">
                    <i class="bi bi-patch-check-fill text-3xl mb-2 block"></i>
                    <div class="font-headline font-bold text-sm mb-1">Laba Telah Terkunci Aman</div>
                    <div class="font-body text-[0.65rem] opacity-80">Waktu: {{ $booking->dp_paid_at ? \Carbon\Carbon::parse($booking->dp_paid_at)->format('d M Y H:i') : '-' }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal Update Harga Nego (Bootstrap modal diubah style-nya) --}}
@if($booking->status === 'pending')
<div class="modal fade" id="modalUpdateHarga" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-2xl overflow-hidden shadow-2xl bg-surface-container-lowest">
            <div class="px-6 py-5 border-b border-outline-variant/20 flex justify-between items-center bg-surface-container-low">
                <h5 class="font-headline font-bold text-lg text-primary flex items-center gap-2">
                    <i class="bi bi-pencil-square text-secondary"></i> Update Harga Nego
                </h5>
                <button type="button" class="text-on-surface-variant hover:text-primary transition-colors" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form action="{{ route('admin.bookings.update_price', $booking->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="p-6">
                    <p class="font-body text-sm text-on-surface-variant leading-relaxed mb-5">Tentukan harga akhir (Deal Price) dengan Klien sebelum DP dikonfirmasi. Ini akan menjadi acuan total tagihan klien di Portal mereka.</p>
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Total Harga Akhir (Rp)</label>
                        <input type="number" name="total_price" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-headline text-lg font-bold text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" value="{{ $booking->total_price }}" required>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant/20 bg-surface-container-low flex justify-end gap-3">
                    <button type="button" class="px-5 py-2.5 rounded-lg border border-outline-variant/50 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-colors" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors">Simpan Harga Nego</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
