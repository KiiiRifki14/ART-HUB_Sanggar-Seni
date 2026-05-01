@extends('layouts.admin')

@section('title', 'Daftar Booking – ART-HUB')
@section('page_title', 'Daftar Booking')
@section('page_subtitle', 'Kelola seluruh permintaan pementasan masuk.')

@section('content')

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
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-receipt-cutoff text-2xl text-secondary mb-2 block"></i>
        <div class="font-headline text-3xl font-bold text-primary mb-1">{{ $total }}</div>
        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Total Booking</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-5 border border-orange-500/20 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-hourglass-split text-2xl text-orange-500 mb-2 block"></i>
        <div class="font-headline text-3xl font-bold text-orange-600 mb-1">{{ $pending }}</div>
        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Menunggu DP</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-5 border border-secondary/20 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-lock-fill text-2xl text-secondary mb-2 block"></i>
        <div class="font-headline text-3xl font-bold text-secondary mb-1">{{ $dpPaid }}</div>
        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Laba Terkunci</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-5 border border-green-500/20 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-check-circle-fill text-2xl text-green-500 mb-2 block"></i>
        <div class="font-headline text-3xl font-bold text-green-600 mb-1">{{ $done }}</div>
        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Selesai</div>
    </div>
</div>

{{-- Header + Filter --}}
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
    <h2 class="font-headline text-xl text-primary font-semibold">Semua Permintaan</h2>
    <a href="{{ route('admin.bookings.create') }}"
       class="bg-gradient-to-br from-primary-container to-primary text-white px-5 py-2.5 rounded-lg font-label text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-md flex items-center gap-2 self-start">
        <i class="bi bi-plus-circle-fill"></i> Tambah Manual
    </a>
</div>

{{-- Filter Tabs --}}
<div class="flex gap-2 flex-wrap mb-4" id="filter-tabs">
    @php
        $tabs = ['all'=>"Semua ({$total})",'pending'=>"Pending ({$pending})",'dp_paid'=>"DP Paid ({$dpPaid})",'completed'=>"Selesai ({$done})",'cancelled'=>"Batal ({$canceled})"];
    @endphp
    @foreach($tabs as $key => $label)
    <button class="px-4 py-2 rounded-lg font-label text-xs font-bold uppercase tracking-widest transition-all border filter-tab {{ $key === 'all' ? 'bg-primary text-white border-primary shadow-sm' : 'bg-surface-container-lowest text-on-surface-variant border-outline-variant/30 hover:border-primary/40 hover:text-primary' }}"
            onclick="filterBooking('{{ $key }}', this)">
        {{ $label }}
    </button>
    @endforeach
</div>

{{-- Table --}}
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
    <table class="w-full">
        <thead class="bg-surface-container-low">
            <tr>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">#Booking</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Klien</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Event</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">Kontrak</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-right">DP</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Status</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody id="booking-tbody" class="divide-y divide-outline-variant/20">
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
                    <div class="font-body font-semibold text-on-surface text-sm capitalize">{{ $booking->event_type }}</div>
                    <div class="font-label text-xs text-outline flex items-center gap-1 mt-0.5">
                        <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}
                    </div>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="font-body font-semibold text-on-surface text-sm">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                    <div class="font-label text-xs text-secondary">Laba: Rp {{ number_format($booking->total_price * 0.30, 0, ',', '.') }}</div>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="font-body font-semibold text-on-surface text-sm">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
                    @if($booking->dp_paid_at)
                    <div class="font-label text-xs text-outline">{{ \Carbon\Carbon::parse($booking->dp_paid_at)->format('d M Y') }}</div>
                    @else
                    <div class="font-label text-xs text-orange-500 font-bold">Belum bayar</div>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-block px-2.5 py-1 rounded border font-label text-[0.65rem] font-bold uppercase tracking-wider {{ $stClass }}">
                        {{ $stLabel }}
                    </span>
                    @if($booking->status === 'pending' && $daysLeft <= 7 && $daysLeft >= 0)
                    <div class="font-label text-[0.6rem] text-red-500 font-bold mt-1">⚠ H-{{ $daysLeft }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.bookings.show', $booking->id) }}"
                           class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-primary hover:text-white transition-all"
                           title="Detail">
                            <i class="bi bi-eye-fill text-sm"></i>
                        </a>
                        @if($booking->status === 'pending')
                        <form method="POST" action="{{ route('admin.bookings.confirm', $booking->id) }}" class="m-0"
                              onsubmit="return confirm('Kunci laba untuk booking #{{ $booking->id }}? Aksi ini tidak bisa dibatalkan.')">
                            @csrf
                            <button type="submit"
                                    class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-secondary hover:text-white transition-all"
                                    title="Kunci Laba">
                                <i class="bi bi-lock-fill text-sm"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-20 text-center">
                    <i class="bi bi-inbox text-4xl text-outline mb-4 block"></i>
                    <p class="font-headline text-lg text-on-surface font-semibold mb-2">Belum ada data booking</p>
                    <a href="{{ route('admin.bookings.create') }}"
                       class="inline-block mt-2 bg-gradient-to-br from-primary-container to-primary text-white px-5 py-2.5 rounded-lg font-label text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-md">
                        + Buat Booking Manual
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
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

    document.querySelectorAll('#booking-tbody tr[data-status]').forEach(row => {
        const s = row.dataset.status;
        const show = status === 'all'
            || s === status
            || (status === 'completed' && (s === 'confirmed' || s === 'completed'));
        row.style.display = show ? '' : 'none';
    });
}
</script>
@endsection
