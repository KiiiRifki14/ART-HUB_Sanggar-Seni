@extends('layouts.admin')

@section('title', 'Daftar Booking – ART-HUB')
@section('page_title', 'Daftar Booking')
@section('page_subtitle', 'Kelola seluruh permintaan pementasan masuk.')

@section('content')
<div x-data="{ showNewBookingModal: {{ $errors->any() ? 'true' : 'false' }} }">

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
    <button @click="showNewBookingModal = true"
       class="bg-gradient-to-br from-primary-container to-primary text-white px-5 py-2.5 rounded-lg font-label text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-md flex items-center gap-2 self-start">
        <i class="bi bi-plus-circle-fill"></i> Pesanan Baru
    </button>
</div>

{{-- Filter Tabs --}}
<div class="flex gap-2 flex-wrap mb-4" id="filter-tabs">
    @php
        $tabs = ['all'=>"Semua ({$total})",'pending'=>"Pending ({$pending})",'dp_paid'=>"DP Dibayar ({$dpPaid})",'completed'=>"Selesai ({$done})",'cancelled'=>"Batal ({$canceled})"];
    @endphp
    @foreach($tabs as $key => $label)
    <button class="px-4 py-2 rounded-lg font-label text-xs font-bold uppercase tracking-widest transition-all border filter-tab {{ $key === 'all' ? 'bg-primary text-white border-primary shadow-sm' : 'bg-surface-container-lowest text-on-surface-variant border-outline-variant/30 hover:border-primary/40 hover:text-primary' }}"
            onclick="filterBooking('{{ $key }}', this)">
        {{ $label }}
    </button>
    @endforeach
</div>

{{-- ════ TABLE (Desktop) ════ --}}
<div class="hidden md:block bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden overflow-x-auto">
    <table class="w-full min-w-[1000px]">
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
                    <span class="inline-block px-2.5 py-1 rounded border font-label text-[0.65rem] font-bold uppercase tracking-wider {{ $stClass }}">{{ $stLabel }}</span>
                    @if($booking->status === 'pending' && $daysLeft <= 7 && $daysLeft >= 0)
                    <div class="font-label text-[0.6rem] text-red-500 font-bold mt-1">⚠ H-{{ $daysLeft }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-primary hover:text-white transition-all" title="Detail"><i class="bi bi-eye-fill text-sm"></i></a>
                        @if($booking->status === 'pending')
                        <button type="button" onclick="openKunciModal({{ $booking->id }}, '{{ addslashes($booking->client_name) }}', {{ $booking->total_price }}, {{ $booking->dp_amount }})" class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-secondary hover:text-white transition-all" title="Kunci Laba & Konfirmasi DP"><i class="bi bi-lock-fill text-sm"></i></button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-6 py-20 text-center">
                <i class="bi bi-inbox text-4xl text-outline mb-4 block"></i>
                <p class="font-headline text-lg text-on-surface font-semibold mb-2">Belum ada data booking</p>
                <a href="{{ route('admin.bookings.create') }}" class="inline-block mt-2 bg-gradient-to-br from-primary-container to-primary text-white px-5 py-2.5 rounded-lg font-label text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-md">+ Buat Pesanan Manual</a>
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ════ MOBILE CARDS (Mobile only) ════ --}}
<div class="md:hidden space-y-3" id="booking-tbody-mobile">
    @forelse($bookings as $booking)
    @php
        [$stLabel, $stClass] = $statusMap[$booking->status] ?? [strtoupper($booking->status), 'bg-surface-container text-outline border-outline-variant/30'];
        $daysLeft = (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($booking->event_date)->startOfDay(), false);
    @endphp
    <div data-status="{{ $booking->status }}" class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden">
        {{-- Card Header --}}
        <div class="flex items-center justify-between px-4 py-3 bg-surface-container-low border-b border-outline-variant/20">
            <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">#{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
            <span class="inline-block px-2.5 py-1 rounded border font-label text-[0.6rem] font-bold uppercase tracking-wider {{ $stClass }}">{{ $stLabel }}</span>
        </div>
        {{-- Card Body --}}
        <div class="px-4 py-3 space-y-2.5">
            <div class="flex justify-between items-start">
                <div>
                    <div class="font-body font-bold text-sm text-on-surface">{{ $booking->client_name ?? ($booking->client->name ?? 'Klien Manual') }}</div>
                    <div class="font-label text-[0.65rem] text-outline">{{ $booking->client_phone ?? '—' }}</div>
                </div>
                <div class="text-right">
                    <div class="font-label text-[0.6rem] uppercase tracking-widest text-outline">Tanggal</div>
                    <div class="font-body text-xs font-bold text-on-surface">{{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}</div>
                </div>
            </div>
            <div class="flex items-center gap-1.5 font-label text-[0.65rem] text-on-surface-variant">
                <i class="bi bi-calendar-event text-secondary"></i>
                <span class="capitalize font-bold">{{ $booking->event_type }}</span>
                <span class="text-outline">•</span>
                <span>{{ $booking->created_at->format('d M Y') }}</span>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="bg-surface-container rounded-lg p-2.5">
                    <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline mb-0.5">Kontrak</div>
                    <div class="font-headline font-bold text-sm text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                </div>
                <div class="bg-surface-container rounded-lg p-2.5">
                    <div class="font-label text-[0.55rem] uppercase tracking-widest text-outline mb-0.5">DP</div>
                    <div class="font-headline font-bold text-sm text-secondary">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
                    @if(!$booking->dp_paid_at)<div class="font-label text-[0.55rem] text-orange-500 font-bold">Belum bayar</div>@endif
                </div>
            </div>
            @if($booking->status === 'pending' && $daysLeft <= 7 && $daysLeft >= 0)
            <div class="font-label text-[0.6rem] text-red-500 font-bold flex items-center gap-1"><i class="bi bi-exclamation-triangle-fill"></i> Acara H-{{ $daysLeft }} — segera tindak lanjuti!</div>
            @endif
        </div>
        {{-- Card Footer --}}
        <div class="px-4 py-3 border-t border-outline-variant/20 bg-surface-container-low/30 flex gap-2">
            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-lg bg-primary text-white font-label text-[0.65rem] font-bold uppercase tracking-widest hover:bg-primary-container transition-colors">
                <i class="bi bi-eye-fill"></i> Detail
            </a>
            @if($booking->status === 'pending')
            <button type="button" onclick="openKunciModal({{ $booking->id }}, '{{ addslashes($booking->client_name) }}', {{ $booking->total_price }}, {{ $booking->dp_amount }})" class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-lg bg-secondary/10 border border-secondary/30 text-secondary font-label text-[0.65rem] font-bold uppercase tracking-widest hover:bg-secondary hover:text-white transition-colors">
                <i class="bi bi-lock-fill"></i> Kunci DP
            </button>
            @endif
        </div>
    </div>
    @empty
    <div class="py-16 flex flex-col items-center justify-center bg-surface-container-lowest border border-outline-variant/30 border-dashed rounded-xl">
        <i class="bi bi-inbox text-4xl text-outline mb-3"></i>
        <p class="font-headline text-base text-on-surface font-semibold">Belum ada data booking</p>
    </div>
    @endforelse
</div>

{{-- ══ MODAL: NEW BOOKING MANUAL ══ --}}
<div x-show="showNewBookingModal"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="display:none;">

    {{-- Backdrop --}}
    <div @click="showNewBookingModal = false"
         class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    {{-- Dialog --}}
    <div class="relative w-full max-w-2xl bg-surface-container-lowest rounded-2xl shadow-2xl overflow-hidden border border-outline-variant/30 max-h-[90vh] flex flex-col">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-outline-variant/20 bg-surface-container-low flex items-center justify-between flex-shrink-0">
            <h3 class="font-headline font-bold text-lg text-primary flex items-center gap-2">
                <i class="bi bi-plus-circle-fill text-secondary"></i> Booking Manual Baru
            </h3>
            <button @click="showNewBookingModal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- Form --}}
        <div class="overflow-y-auto flex-1">
            <form action="{{ route('admin.bookings.manual.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-5">

                    {{-- Info Klien --}}
                    <div>
                        <div class="font-label text-[0.65rem] uppercase tracking-widest text-primary font-bold flex items-center gap-2 mb-4">
                            <i class="bi bi-person-fill"></i> Informasi Klien
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Nama Klien <span class="text-red-500">*</span></label>
                                <input type="text" name="client_name" value="{{ old('client_name') }}" required
                                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('client_name') border-red-400 @enderror"
                                       placeholder="Nama lengkap klien">
                                @error('client_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">No. HP / WA <span class="text-red-500">*</span></label>
                                <input type="text" name="client_phone" value="{{ old('client_phone') }}" required
                                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('client_phone') border-red-400 @enderror"
                                       placeholder="08xxxxxxxxxx">
                                @error('client_phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <hr class="border-outline-variant/20">

                    {{-- Info Event --}}
                    <div>
                        <div class="font-label text-[0.65rem] uppercase tracking-widest text-primary font-bold flex items-center gap-2 mb-4">
                            <i class="bi bi-calendar-event-fill"></i> Detail Pementasan
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Jenis Pementasan <span class="text-red-500">*</span></label>
                                <select name="event_type" required class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach(['jaipong' => 'Tari Jaipong', 'rampak_gendang' => 'Rampak Gendang', 'mapag_panganten' => 'Mapag Panganten', 'kacapi_suling' => 'Kacapi Suling'] as $val => $label)
                                        <option value="{{ $val }}" {{ old('event_type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Tanggal Acara <span class="text-red-500">*</span></label>
                                <input type="date" name="event_date" value="{{ old('event_date') }}" required
                                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            </div>
                            <div>
                                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Waktu Mulai <span class="text-red-500">*</span></label>
                                <input type="time" name="event_start" value="{{ old('event_start') }}" required
                                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            </div>
                            <div>
                                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Waktu Selesai <span class="text-red-500">*</span></label>
                                <input type="time" name="event_end" value="{{ old('event_end') }}" required
                                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Lokasi / Venue <span class="text-red-500">*</span></label>
                                <input type="text" name="venue" value="{{ old('venue') }}" required
                                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                                       placeholder="Nama gedung / tempat acara">
                            </div>
                        </div>
                    </div>

                    <hr class="border-outline-variant/20">

                    {{-- Harga --}}
                    <div>
                        <div class="font-label text-[0.65rem] uppercase tracking-widest text-primary font-bold flex items-center gap-2 mb-4">
                            <i class="bi bi-cash-coin"></i> Harga Kontrak
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Total Harga (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" id="nb_total_price" name="total_price" value="{{ old('total_price') }}" required min="0"
                                       oninput="document.getElementById('nb_dp').value = Math.round(this.value * 0.5)"
                                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                                       placeholder="Contoh: 5000000">
                            </div>
                            <div>
                                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">DP / Uang Muka (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" id="nb_dp" name="dp_amount" value="{{ old('dp_amount') }}" required min="0"
                                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                                       placeholder="Otomatis 50% dari total">
                                <p class="text-[0.65rem] text-outline mt-1">Otomatis terisi 50%, bisa diubah.</p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-outline-variant/20 bg-surface-container-low flex justify-end gap-3 flex-shrink-0">
                    <button type="button" @click="showNewBookingModal = false"
                            class="px-5 py-2.5 rounded-lg border border-outline-variant/50 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-lg bg-gradient-to-br from-primary-container to-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-md flex items-center gap-2">
                        <i class="bi bi-plus-circle-fill"></i> Simpan Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══ MODAL: KUNCI LABA (Konfirmasi DP) ══ --}}
<div id="modalKunciLaba" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeKunciModal()"></div>
    <div id="modalKunciContent" class="relative w-full max-w-sm bg-surface-container-lowest rounded-2xl shadow-2xl border border-outline-variant/30 p-6 transition-all scale-95 opacity-0">

        <div class="flex items-center justify-between mb-4 pb-3 border-b border-outline-variant/20">
            <h5 class="font-headline font-bold text-lg text-primary flex items-center gap-2">
                <i class="bi bi-lock-fill text-secondary"></i> Kunci Laba & Konfirmasi DP
            </h5>
            <button onclick="closeKunciModal()" class="text-on-surface-variant hover:text-primary"><i class="bi bi-x-lg"></i></button>
        </div>

        <form id="formKunciLaba" method="POST">
            @csrf
            <div class="mb-5 space-y-4">
                <div class="p-3 bg-surface-container-low rounded-lg">
                    <div class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline mb-1">Klien</div>
                    <div id="kunci_client" class="font-body font-semibold text-on-surface text-sm"></div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-surface-container-low rounded-lg text-center">
                        <div class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline mb-1">Total Kontrak</div>
                        <div id="kunci_total" class="font-headline font-bold text-primary text-base"></div>
                    </div>
                    <div class="p-3 bg-secondary/5 border border-secondary/20 rounded-lg text-center">
                        <div class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline mb-1">DP Masuk</div>
                        <div id="kunci_dp" class="font-headline font-bold text-secondary text-base"></div>
                    </div>
                </div>

                <div>
                    <label class="block font-label text-[0.65rem] uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">
                        Fixed Profit (Laba Bersih Sanggar) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 font-body text-sm text-outline">Rp</span>
                        <input type="number" name="fixed_profit_nominal" id="kunci_profit_input"
                               required min="0"
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg pl-10 pr-4 py-2.5 font-body text-sm text-on-surface focus:border-primary outline-none transition-all"
                               placeholder="Misal: 2000000">
                    </div>
                    <p id="kunci_profit_hint" class="text-[0.65rem] text-outline mt-1.5"></p>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="button" onclick="closeKunciModal()" class="flex-1 py-2.5 rounded-lg border border-outline-variant/50 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-2.5 rounded-lg bg-secondary text-primary font-label text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-sm flex items-center justify-center gap-1.5">
                    <i class="bi bi-lock-fill"></i> Kunci Laba
                </button>
            </div>
        </form>
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

    document.querySelectorAll('#booking-tbody tr[data-status]').forEach(row => {
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
