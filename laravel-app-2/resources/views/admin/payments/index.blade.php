@extends('layouts.admin')

@section('title', 'Pelacakan Pembayaran – ART-HUB')
@section('page_title', 'Pelacakan Pembayaran')
@section('page_subtitle', 'Pantau status pelunasan klien pasca-DP & pasca-event.')

@section('content')

@php
    $total    = $bookings->count();
    $unpaid   = $bookings->whereIn('status',['completed', 'paid_full'])->whereNull('full_paid_at')->count();
    $piutang  = $bookings->whereIn('status',['completed', 'confirmed', 'dp_paid', 'pending'])->whereNull('full_paid_at')->sum(function($b) {
                    return $b->total_price - $b->dp_amount;
                });
    $lunas    = $bookings->whereNotNull('full_paid_at')->count();
@endphp

{{-- Stat Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-primary-container to-primary text-white rounded-xl p-6 border border-primary/20 shadow-[0_12px_24px_rgba(54,31,26,0.08)] text-center relative overflow-hidden">
        <div class="absolute -right-6 -top-6 text-white/5">
            <i class="bi bi-cash-stack text-9xl"></i>
        </div>
        <div class="relative z-10">
            <i class="bi bi-cash-stack text-3xl text-secondary-container mb-3 block"></i>
            <h3 class="font-headline text-4xl font-bold text-secondary-container mb-1">Rp {{ number_format($piutang, 0, ',', '.') }}</h3>
            <div class="font-label text-xs uppercase tracking-widest text-white/80 font-bold">Total Piutang Berjalan</div>
        </div>
    </div>
    
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-red-500/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-red-500"></div>
        <i class="bi bi-hourglass-top text-3xl text-red-500 mb-3 block"></i>
        <h3 class="font-headline text-4xl font-bold text-red-600 mb-1">{{ $unpaid }} Event</h3>
        <div class="font-label text-xs uppercase tracking-widest text-outline font-bold">Belum Lunas (Selesai Event)</div>
    </div>
    
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-green-500/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-green-500"></div>
        <i class="bi bi-check2-all text-3xl text-green-500 mb-3 block"></i>
        <h3 class="font-headline text-4xl font-bold text-green-600 mb-1">{{ $lunas }} <span class="text-2xl text-outline font-body font-normal">/ {{ $total }}</span></h3>
        <div class="font-label text-xs uppercase tracking-widest text-outline font-bold">Transaksi Lunas</div>
    </div>
</div>

{{-- Header --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="font-headline text-xl text-primary font-semibold flex items-center gap-2">
        <i class="bi bi-journal-check text-secondary"></i> Daftar Tagihan
    </h2>
</div>

{{-- Table --}}
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
    <table class="w-full">
        <thead class="bg-surface-container-low">
            <tr>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">#Booking / Event</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Klien</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Total Kontrak</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">DP Masuk</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Sisa Tagihan</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Status Event</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Status Pembayaran</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/20">
            @forelse($bookings as $booking)
            @php
                $sisa = $booking->total_price - $booking->dp_amount;
                $isLunas = !is_null($booking->full_paid_at) || ($booking->total_price > 0 && $sisa <= 0) || in_array($booking->status, ['paid_full']);
                $isOverdue = !$isLunas && in_array($booking->status, ['completed']);
                $stName = strtoupper($booking->status);
                
                $rowClass = $isOverdue ? 'bg-red-500/5 hover:bg-red-500/10 border-l-4 border-l-red-500' : 'hover:bg-surface-container-low/50 border-l-4 border-l-transparent';
            @endphp
            <tr class="{{ $rowClass }} transition-colors">
                <td class="px-6 py-4 pl-5">
                    <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">
                        #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                    @if($booking->event)
                        <div class="font-label text-xs text-outline mt-1 font-bold">{{ $booking->event->event_code }}</div>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="font-body font-semibold text-on-surface text-sm">{{ $booking->client_name ?? ($booking->client->name ?? '-') }}</div>
                    <div class="font-label text-xs text-outline">{{ $booking->client_phone ?? '-' }}</div>
                </td>
                <td class="px-6 py-4 text-right font-body text-sm text-on-surface">
                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 text-right font-body text-sm text-green-600 font-medium">
                    Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="font-headline font-bold {{ $isOverdue ? 'text-red-600' : 'text-primary' }}">
                        Rp {{ number_format($sisa, 0, ',', '.') }}
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    @if($booking->status === 'pending') 
                        <span class="inline-block px-2 py-0.5 rounded border border-outline-variant/30 bg-surface-container font-label text-[0.6rem] font-bold uppercase tracking-wider text-outline">PENDING</span>
                    @elseif($booking->status === 'completed') 
                        <span class="inline-block px-2 py-0.5 rounded border border-blue-500/20 bg-blue-500/10 font-label text-[0.6rem] font-bold uppercase tracking-wider text-blue-600">SELESAI</span>
                    @else 
                        <span class="inline-block px-2 py-0.5 rounded border border-outline-variant/30 bg-surface-container font-label text-[0.6rem] font-bold uppercase tracking-wider text-outline">{{ $stName }}</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    @if($isLunas)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-green-500/10 text-green-600 border border-green-500/20 font-label text-[0.65rem] font-bold uppercase tracking-wider">
                            <i class="bi bi-check-circle-fill"></i> LUNAS
                        </span>
                        <div class="font-label text-xs text-outline mt-1">{{ \Carbon\Carbon::parse($booking->full_paid_at ?? now())->format('d M Y') }}</div>
                    @else
                        @if($booking->status === 'completed' || $booking->status === 'paid_full')
                            @php $sisaFormatted = number_format($sisa, 0, ',', '.'); @endphp
                            @if($booking->status === 'completed')
                                @if($booking->full_payment_proof)
                                    <div class="flex flex-col gap-1.5">
                                        <a href="{{ asset('storage/' . $booking->full_payment_proof) }}" target="_blank" class="w-full text-center py-1 rounded bg-blue-50 text-blue-600 border border-blue-200 font-label text-[0.6rem] font-bold uppercase tracking-wider hover:bg-blue-100 transition-colors">Lihat Bukti</a>
                                        <div class="flex gap-1.5">
                                            <form action="{{ route('admin.bookings.full_payment', $booking->id) }}" method="POST" class="flex-1 m-0">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-full py-1.5 rounded bg-green-600 text-white hover:bg-green-700 transition-colors font-label text-[0.6rem] font-bold uppercase tracking-wider" onclick="return confirm('Verifikasi bukti sah & lunas?')">Sah</button>
                                            </form>
                                            <form action="{{ route('admin.bookings.reject_full_proof', $booking->id) }}" method="POST" class="flex-1 m-0">
                                                @csrf
                                                <button type="submit" class="w-full py-1.5 rounded bg-red-100 text-red-600 hover:bg-red-200 transition-colors font-label text-[0.6rem] font-bold uppercase tracking-wider" onclick="return confirm('Tolak bukti ini?')">Tolak</button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <button type="button" 
                                            onclick="openLunasModal({{ $booking->id }}, '{{ $sisaFormatted }}')"
                                            class="w-full py-1.5 rounded border border-green-500 text-green-600 hover:bg-green-500 hover:text-white transition-all font-label text-xs font-bold uppercase tracking-wider">
                                        <i class="bi bi-check-circle me-1"></i>Tandai Lunas
                                    </button>
                                @endif
                            @endif
                        @else
                            @if(\Carbon\Carbon::parse($booking->event_date)->isPast() && $booking->event)
                                <a href="{{ route('admin.events.show', $booking->event->id) }}"
                                   class="w-full flex items-center justify-center gap-1.5 py-1.5 rounded border border-blue-500/30 text-blue-600 hover:bg-blue-500/10 transition-all font-label text-xs font-bold uppercase tracking-wider">
                                    <i class="bi bi-box-arrow-up-right"></i> Buka Detail Acara
                                </a>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-orange-500/10 text-orange-600 border border-orange-500/20 font-label text-[0.65rem] font-bold uppercase tracking-wider">
                                    <i class="bi bi-hourglass-split"></i> MENUNGGU EVENT
                                </span>
                            @endif
                        @endif
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-20 text-center">
                    <i class="bi bi-inbox text-4xl text-outline mb-4 block"></i>
                    <p class="font-headline text-lg text-on-surface font-semibold mb-1">Belum ada tagihan</p>
                    <p class="font-label text-xs uppercase tracking-widest text-outline">Tagihan akan muncul saat event selesai</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

{{-- Modal Pelunasan Tunai/Offline --}}
<div id="modalLunasOffline" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeLunasModal()"></div>
    <div class="relative w-full max-w-sm rounded-2xl p-6 border shadow-2xl transition-all scale-95 opacity-0 bg-surface-container-lowest" id="modalLunasContent">
        
        <div class="flex items-center justify-between mb-4 pb-3" style="border-bottom:1px solid rgba(0,0,0,0.06)">
            <h5 class="font-headline font-bold text-lg text-primary flex items-center gap-2">
                <i class="bi bi-cash-stack text-secondary"></i> Pelunasan Offline
            </h5>
            <button type="button" onclick="closeLunasModal()" class="text-on-surface-variant hover:text-primary transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="formLunasOffline" method="POST">
            @csrf
            <div class="mb-5">
                <p class="font-body text-xs text-on-surface-variant leading-relaxed mb-4">Gunakan opsi ini jika Klien membayar sisa tagihan secara langsung (tunai/transfer langsung) di luar portal klien.</p>
                
                <div class="p-3 bg-green-50 border border-green-200 rounded-lg text-center mb-4">
                    <div class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-green-600 mb-1">Sisa Tagihan</div>
                    <div class="font-headline font-bold text-xl text-green-700">Rp <span id="sisaTagihanDisplay"></span></div>
                </div>

                <label class="block font-label text-[0.65rem] uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Catatan Pembayaran (Opsional)</label>
                <input type="text" name="cash_note" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2.5 font-body text-sm text-on-surface focus:border-primary outline-none transition-all" placeholder="Misal: Diterima tunai oleh Bpk X">
            </div>

            <div class="flex gap-2">
                <button type="button" onclick="closeLunasModal()" class="flex-1 py-2.5 rounded-lg border border-outline-variant/50 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-2.5 rounded-lg bg-green-600 text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-green-700 transition-colors">Lunas & Selesai</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    function openLunasModal(bookingId, sisaFormatted) {
        document.getElementById('sisaTagihanDisplay').innerText = sisaFormatted;
        
        // Sesuaikan route form action
        const form = document.getElementById('formLunasOffline');
        form.action = `/admin/bookings/${bookingId}/full-cash-payment`;
        
        const modal = document.getElementById('modalLunasOffline');
        const content = document.getElementById('modalLunasContent');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeLunasModal() {
        const modal = document.getElementById('modalLunasOffline');
        const content = document.getElementById('modalLunasContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }
</script>
@endsection
