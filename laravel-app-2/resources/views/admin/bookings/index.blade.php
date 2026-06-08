@extends('layouts.admin')

@section('title', 'Daftar Booking – ART-HUB')
@section('page_title', 'Daftar Booking')
@section('page_subtitle', 'Kelola seluruh permintaan pementasan masuk.')

@section('content')
<div>

@php
    $total    = $bookings->count();
    $pending  = $bookings->where('status','pending')->count();
    $dpPaid   = $bookings->where('status','dp_paid')->count();
    $done     = $bookings->whereIn('status',['confirmed','completed'])->count();
    $canceled = $bookings->where('status','cancelled')->count();

    $statusMap = [
        'pending'   => ['PENDING',   'bg-orange-500/10 text-orange-600 border-orange-500/20'],
        'dp_paid'   => ['DP PAID',   'bg-secondary/10 text-secondary border-secondary/20'],
        'confirmed' => ['CONFIRMED', 'bg-blue-500/10 text-blue-600 border-blue-500/20'],
        'completed' => ['SELESAI',   'bg-green-500/10 text-green-600 border-green-500/20'],
        'cancelled' => ['BATAL',     'bg-red-500/10 text-red-600 border-red-500/20'],
    ];
@endphp

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/30 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary">
                <i data-lucide="file-text" class="w-5 h-5"></i>
            </div>
            <span class="text-[0.65rem] font-bold text-outline uppercase tracking-widest">Total</span>
        </div>
        <div>
            <div class="font-headline text-2xl font-bold text-primary mb-1">{{ $total }}</div>
            <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Total Booking</div>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-2xl p-6 border border-orange-500/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                <i data-lucide="hourglass" class="w-5 h-5"></i>
            </div>
            <span class="text-[0.65rem] font-bold text-orange-500 uppercase tracking-widest">Pending</span>
        </div>
        <div>
            <div class="font-headline text-2xl font-bold text-orange-600 mb-1">{{ $pending }}</div>
            <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Menunggu DP</div>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-2xl p-6 border border-secondary/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary">
                <i data-lucide="lock" class="w-5 h-5"></i>
            </div>
            <span class="text-[0.65rem] font-bold text-secondary uppercase tracking-widest">Locked</span>
        </div>
        <div>
            <div class="font-headline text-2xl font-bold text-secondary mb-1">{{ $dpPaid }}</div>
            <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Laba Terkunci</div>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-2xl p-6 border border-green-500/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center text-green-500">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <span class="text-[0.65rem] font-bold text-green-500 uppercase tracking-widest">Done</span>
        </div>
        <div>
            <div class="font-headline text-2xl font-bold text-green-600 mb-1">{{ $done }}</div>
            <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Selesai</div>
        </div>
    </div>
</div>

{{-- Header + Filter --}}
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
    <h2 class="font-headline text-xl text-primary font-bold">Semua Permintaan</h2>
    <a href="{{ route('admin.bookings.create') }}"
       class="bg-primary text-white px-5 py-2.5 rounded-xl font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-all shadow-sm flex items-center gap-2 self-start">
        <i data-lucide="plus-circle" class="w-4 h-4"></i> Pesanan Baru
    </a>
</div>

{{-- Filter Tabs --}}
<div class="flex gap-2 overflow-x-auto whitespace-nowrap scrollbar-none pb-2 -mx-4 px-4 md:flex-wrap md:mx-0 md:px-0 mb-6" id="filter-tabs">
    @php
        $tabs = ['all'=>"Semua ({$total})",'pending'=>"Pending ({$pending})",'dp_paid'=>"DP Dibayar ({$dpPaid})",'completed'=>"Selesai ({$done})",'cancelled'=>"Batal ({$canceled})"];
    @endphp
    @foreach($tabs as $key => $label)
    <button class="flex-shrink-0 px-4 py-2 rounded-xl font-label text-xs font-bold uppercase tracking-widest transition-all border filter-tab {{ $key === 'all' ? 'bg-primary text-white border-primary shadow-sm' : 'bg-surface-container-lowest text-on-surface-variant border-outline-variant/30 hover:border-primary/40 hover:text-primary' }}"
            onclick="filterBooking('{{ $key }}', this)">
        {{ $label }}
    </button>
    @endforeach
</div>

{{-- ════ TABLE (Desktop) ════ --}}
<div class="hidden md:block bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[1000px]">
            <thead class="bg-surface-container-low border-b border-outline-variant/20">
                <tr>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">#Booking</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Klien</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Event</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Kontrak</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">DP</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Status</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="booking-tbody" class="divide-y divide-outline-variant/15">
                @forelse($bookings as $booking)
                @php
                    [$stLabel, $stClass] = $statusMap[$booking->status] ?? [strtoupper($booking->status), 'bg-surface-container text-outline border-outline-variant/30'];
                    $daysLeft = (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($booking->event_date)->startOfDay(), false);
                @endphp
                <tr data-status="{{ $booking->status }}" class="hover:bg-surface-container-low/50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">
                            #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                        <div class="font-label text-[0.65rem] text-outline mt-1">{{ $booking->created_at->format('d M Y') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-body font-semibold text-on-surface text-sm">{{ $booking->client_name ?? ($booking->client->name ?? 'Klien Manual') }}</div>
                        <div class="font-label text-xs text-outline">{{ $booking->client_phone ?? '—' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-body font-semibold text-on-surface text-sm capitalize">{{ str_replace('_', ' ', $booking->event_type) }}</div>
                        <div class="font-label text-xs text-outline flex items-center gap-1.5 mt-1">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 opacity-60"></i> {{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="font-body font-semibold text-on-surface text-sm">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                        <div class="font-label text-xs text-secondary font-semibold">Laba (30%): Rp {{ number_format($booking->total_price * 0.30, 0, ',', '.') }}</div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="font-body font-semibold text-on-surface text-sm">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
                        @if($booking->dp_paid_at)
                        <div class="font-label text-xs text-outline mt-1">{{ \Carbon\Carbon::parse($booking->dp_paid_at)->format('d M Y') }}</div>
                        @elseif(!in_array($booking->status, ['pending', 'cancelled']))
                        <div class="font-label text-xs text-secondary font-bold mt-1">{{ \Carbon\Carbon::parse($booking->updated_at)->format('d M Y') }}</div>
                        @else
                        <div class="font-label text-xs text-orange-500 font-bold mt-1">Belum bayar</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border font-label text-[0.65rem] font-bold uppercase tracking-wider {{ $stClass }}">{{ $stLabel }}</span>
                        @if($booking->status === 'pending' && $daysLeft <= 7 && $daysLeft >= 0)
                        <div class="font-label text-[0.6rem] text-red-500 font-bold mt-1">⚠ H-{{ $daysLeft }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="w-9 h-9 rounded-lg border border-outline-variant/40 text-outline hover:text-primary hover:border-primary flex items-center justify-center hover:bg-primary/5 transition-all" title="Detail"><i data-lucide="eye" class="w-4 h-4"></i></a>
                            @if($booking->status === 'pending')
                            <button type="button" onclick="openKunciModal({{ $booking->id }}, '{{ addslashes($booking->client_name) }}', {{ $booking->total_price }}, {{ $booking->dp_amount }})" class="w-9 h-9 rounded-lg border border-secondary/40 text-secondary hover:bg-secondary/10 flex items-center justify-center transition-all" title="Kunci Laba & Konfirmasi DP"><i data-lucide="lock" class="w-4 h-4"></i></button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-16 text-center text-outline">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 opacity-30 text-primary"></i>
                    <p class="font-headline text-lg font-bold text-on-surface mb-2">Belum ada data booking</p>
                    <a href="{{ route('admin.bookings.create') }}" class="inline-block mt-2 bg-primary text-white px-5 py-2.5 rounded-xl font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-all shadow-sm">+ Buat Pesanan Manual</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ══ MOBILE CARDS (Mobile only) ══ --}}
<div class="md:hidden space-y-4" id="booking-tbody-mobile">
    @forelse($bookings as $booking)
    @php
        [$stLabel, $stClass] = $statusMap[$booking->status] ?? [strtoupper($booking->status), 'bg-surface-container text-outline border-outline-variant/30'];
        $daysLeft = (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($booking->event_date)->startOfDay(), false);
    @endphp
    <div data-status="{{ $booking->status }}" class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden">
        {{-- Card Header --}}
        <div class="flex items-center justify-between px-4 py-3 bg-surface-container-low border-b border-outline-variant/20">
            <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">#{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
            <span class="inline-flex px-2 py-0.5 rounded-full border font-label text-[0.6rem] font-bold uppercase tracking-wider {{ $stClass }}">{{ $stLabel }}</span>
        </div>
        {{-- Card Body --}}
        <div class="px-4 py-4 space-y-3">
            <div class="flex justify-between items-start gap-2">
                <div>
                    <div class="font-body font-bold text-sm text-on-surface leading-tight">{{ $booking->client_name ?? ($booking->client->name ?? 'Klien Manual') }}</div>
                    <div class="font-label text-xs text-outline mt-1">{{ $booking->client_phone ?? '—' }}</div>
                </div>
                <div class="text-right flex-shrink-0">
                    <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline">Tanggal</div>
                    <div class="font-body text-xs font-bold text-on-surface mt-0.5">{{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}</div>
                </div>
            </div>
            <div class="flex items-center gap-1.5 font-label text-xs text-on-surface-variant">
                <i data-lucide="calendar" class="w-4 h-4 text-secondary"></i>
                <span class="capitalize font-bold">{{ str_replace('_', ' ', $booking->event_type) }}</span>
                <span class="text-outline">•</span>
                <span>{{ $booking->created_at->format('d M Y') }}</span>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-surface-container rounded-xl p-3">
                    <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline mb-1">Kontrak</div>
                    <div class="font-headline font-bold text-sm text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                </div>
                <div class="bg-surface-container rounded-xl p-3">
                    <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline mb-1">DP</div>
                    <div class="font-headline font-bold text-sm text-secondary">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
                    @if($booking->dp_paid_at)
                    <div class="font-label text-[0.55rem] text-outline font-bold mt-1">{{ \Carbon\Carbon::parse($booking->dp_paid_at)->format('d M Y') }}</div>
                    @elseif(!in_array($booking->status, ['pending', 'cancelled']))
                    <div class="font-label text-[0.55rem] text-secondary font-bold mt-1">{{ \Carbon\Carbon::parse($booking->updated_at)->format('d M Y') }}</div>
                    @else
                    <div class="font-label text-[0.55rem] text-orange-500 font-bold mt-1">Belum bayar</div>
                    @endif
                </div>
            </div>
            @if($booking->status === 'pending' && $daysLeft <= 7 && $daysLeft >= 0)
            <div class="font-label text-xs text-red-500 font-bold flex items-center gap-1.5 mt-2"><i data-lucide="alert-triangle" class="w-4 h-4"></i> Acara H-{{ $daysLeft }}</div>
            @endif
        </div>
        {{-- Card Footer --}}
        <div class="px-4 py-3 border-t border-outline-variant/20 bg-surface-container-low/30 flex gap-2">
            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-xl bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors">
                <i data-lucide="eye" class="w-4 h-4"></i> Detail
            </a>
            @if($booking->status === 'pending')
            <button type="button" onclick="openKunciModal({{ $booking->id }}, '{{ addslashes($booking->client_name) }}', {{ $booking->total_price }}, {{ $booking->dp_amount }})" class="flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-xl bg-secondary/10 border border-secondary/30 text-secondary font-label text-xs font-bold uppercase tracking-widest hover:bg-secondary hover:text-white transition-colors">
                <i data-lucide="lock" class="w-4 h-4"></i> Kunci DP
            </button>
            @endif
        </div>
    </div>
    @empty
    <div class="py-14 flex flex-col items-center justify-center bg-surface-container-lowest border border-outline-variant/30 border-dashed rounded-2xl">
        <i data-lucide="inbox" class="w-12 h-12 text-outline mb-2 opacity-30"></i>
        <p class="font-headline text-base text-on-surface font-bold">Belum ada data booking</p>
    </div>
    @endforelse
</div>



{{-- ══ MODAL: KUNCI LABA (Konfirmasi DP) ══ --}}
<div id="modalKunciLaba" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeKunciModal()"></div>
    <div id="modalKunciContent" class="relative w-full max-w-sm bg-surface-container-lowest rounded-2xl shadow-2xl border border-outline-variant/30 p-6 transition-all scale-95 opacity-0">

        <div class="flex items-center justify-between mb-4 pb-3 border-b border-outline-variant/20">
            <h5 class="font-headline font-bold text-lg text-primary flex items-center gap-2">
                <i data-lucide="lock" class="w-5 h-5 text-secondary"></i> Kunci Laba & Konfirmasi DP
            </h5>
            <button onclick="closeKunciModal()" class="text-on-surface-variant hover:text-primary"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>

        <form id="formKunciLaba" method="POST">
            @csrf
            <div class="mb-5 space-y-4">
                <div class="p-4 bg-surface-container-low rounded-xl border border-outline-variant/25">
                    <div class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline mb-1">Klien</div>
                    <div id="kunci_client" class="font-body font-semibold text-on-surface text-sm"></div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-surface-container-low border border-outline-variant/25 rounded-xl text-center">
                        <div class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline mb-1">Total Kontrak</div>
                        <div id="kunci_total" class="font-headline font-bold text-primary text-sm"></div>
                    </div>
                    <div class="p-3 bg-secondary/5 border border-secondary/20 rounded-xl text-center">
                        <div class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline mb-1">DP Masuk</div>
                        <div id="kunci_dp" class="font-headline font-bold text-secondary text-sm"></div>
                    </div>
                </div>

                <div>
                    <label class="block font-label text-[0.65rem] uppercase tracking-widest text-on-surface-variant font-bold mb-1.5 ml-1">
                        Fixed Profit (Laba Bersih Sanggar) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 font-body text-sm text-outline">Rp</span>
                        <input type="number" name="fixed_profit_nominal" id="kunci_profit_input"
                               required min="0"
                               class="w-full bg-surface-container border border-outline-variant/50 rounded-xl pl-10 pr-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all"
                               placeholder="Misal: 2000000">
                    </div>
                    <p id="kunci_profit_hint" class="text-[0.65rem] text-outline mt-1.5 ml-1 leading-relaxed"></p>
                </div>
            </div>

            <div class="flex gap-2 pt-4 border-t border-outline-variant/20">
                <button type="button" onclick="closeKunciModal()" class="flex-1 py-2.5 rounded-xl border border-outline-variant/50 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-all">Batal</button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-secondary text-primary font-label text-xs font-bold uppercase tracking-widest hover:bg-secondary-container transition-all shadow-sm flex items-center justify-center gap-1.5">
                    <i data-lucide="lock" class="w-4 h-4"></i> Kunci Laba
                </button>
            </div>
        </form>
    </div>
</div>

</div>

@endsection

@section('scripts')
<script>
function filterBooking(status, btn) {
    document.querySelectorAll('.filter-tab').forEach(b => {
        b.classList.remove('bg-primary','text-white','border-primary','shadow-sm');
        b.classList.add('bg-surface-container-lowest','text-on-surface-variant','border-outline-variant/30');
    });
    btn.classList.add('bg-primary','text-white','border-primary','shadow-sm');
    btn.classList.remove('bg-surface-container-lowest','text-on-surface-variant','border-outline-variant/30');

    document.querySelectorAll('#booking-tbody tr[data-status], #booking-tbody-mobile [data-status]').forEach(row => {
        const s = row.dataset.status;
        const show = status === 'all'
            || s === status
            || (status === 'completed' && (s === 'confirmed' || s === 'completed'));
        row.style.display = show ? '' : 'none';
    });
}

function openKunciModal(bookingId, clientName, totalPrice, dpAmount) {
    // Set form action
    document.getElementById('formKunciLaba').action = `/admin/bookings/${bookingId}/confirm`;

    // Fill info
    document.getElementById('kunci_client').textContent = clientName;
    document.getElementById('kunci_total').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
    document.getElementById('kunci_dp').textContent = 'Rp ' + dpAmount.toLocaleString('id-ID');

    // Suggest 30% of total as default profit
    const suggested = Math.round(totalPrice * 0.30);
    document.getElementById('kunci_profit_input').value = suggested;
    document.getElementById('kunci_profit_hint').textContent =
        `Saran otomatis 30%: Rp ${suggested.toLocaleString('id-ID')}. Bisa diubah sesuai negosiasi.`;

    // Show modal
    const modal = document.getElementById('modalKunciLaba');
    const content = document.getElementById('modalKunciContent');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeKunciModal() {
    const modal = document.getElementById('modalKunciLaba');
    const content = document.getElementById('modalKunciContent');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
}
</script>
@endsection
