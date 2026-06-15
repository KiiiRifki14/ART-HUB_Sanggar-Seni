@extends('layouts.admin')

@section('title', 'Daftar Booking – ART-HUB')
@section('page_title', 'Daftar Booking')
@section('page_subtitle', 'Kelola seluruh permintaan pementasan masuk.')

@section('content')
<div>

@php
    $statusMap = [
        'pending'   => ['PENDING',   'bg-orange-500/10 text-orange-600 border-orange-500/20'],
        'dp_paid'   => ['DP PAID',   'bg-secondary/10 text-secondary border-secondary/20'],
        'confirmed' => ['CONFIRMED', 'bg-blue-500/10 text-blue-600 border-blue-500/20'],
        'completed' => ['SELESAI',   'bg-green-500/10 text-green-600 border-green-500/20'],
        'cancelled' => ['BATAL',     'bg-red-500/10 text-red-600 border-red-500/20'],
    ];
@endphp

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card-gold p-5 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-3">
            <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,rgba(197,160,40,0.2),rgba(197,160,40,0.05));border:1px solid rgba(197,160,40,0.3);display:flex;align-items:center;justify-content:center;color:#bfa000;">
                <i data-lucide="file-text" class="w-5 h-5"></i>
            </div>
            <span class="subtitle-gold" style="font-size:0.6rem;">Total</span>
        </div>
        <div>
            <div class="title-gold" style="font-size:1.8rem; line-height:1;">{{ $total }}</div>
            <div class="subtitle-gold mt-1">Total Booking</div>
        </div>
    </div>
    <div class="card-gold p-5 flex flex-col justify-between" style="border-color:rgba(234,88,12,0.2);">
        <div class="flex items-center justify-between mb-3">
            <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,rgba(234,88,12,0.15),rgba(234,88,12,0.05));border:1px solid rgba(234,88,12,0.3);display:flex;align-items:center;justify-content:center;color:#ea580c;">
                <i data-lucide="hourglass" class="w-5 h-5"></i>
            </div>
            <span class="subtitle-gold" style="font-size:0.6rem; color:#ea580c;">Pending</span>
        </div>
        <div>
            <div class="title-gold" style="font-size:1.8rem; line-height:1; color:#ea580c;">{{ $pending }}</div>
            <div class="subtitle-gold mt-1">Menunggu DP</div>
        </div>
    </div>
    <div class="card-gold p-5 flex flex-col justify-between" style="border-color:rgba(197,160,40,0.3);">
        <div class="flex items-center justify-between mb-3">
            <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,rgba(197,160,40,0.2),rgba(197,160,40,0.05));border:1px solid rgba(197,160,40,0.3);display:flex;align-items:center;justify-content:center;color:#bfa000;">
                <i data-lucide="lock" class="w-5 h-5"></i>
            </div>
            <span class="subtitle-gold" style="font-size:0.6rem; color:#bfa000;">Locked</span>
        </div>
        <div>
            <div class="title-gold" style="font-size:1.8rem; line-height:1; color:#bfa000;">{{ $dpPaid }}</div>
            <div class="subtitle-gold mt-1">Laba Terkunci</div>
        </div>
    </div>
    <div class="card-gold p-5 flex flex-col justify-between" style="border-color:rgba(22,163,74,0.3);">
        <div class="flex items-center justify-between mb-3">
            <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,rgba(22,163,74,0.15),rgba(22,163,74,0.05));border:1px solid rgba(22,163,74,0.3);display:flex;align-items:center;justify-content:center;color:#16a34a;">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <span class="subtitle-gold" style="font-size:0.6rem; color:#16a34a;">Done</span>
        </div>
        <div>
            <div class="title-gold" style="font-size:1.8rem; line-height:1; color:#16a34a;">{{ $done }}</div>
            <div class="subtitle-gold mt-1">Selesai</div>
        </div>
    </div>
</div>

{{-- Header + Filter --}}
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-5">
    <h2 class="title-gold" style="font-size:1.6rem;">Semua Permintaan</h2>
    <a href="{{ route('admin.bookings.create') }}" class="arh-btn-primary self-start px-5 py-2.5 flex items-center gap-2 shadow-lg shadow-maroon-900/10">
        <i data-lucide="plus-circle" class="w-5 h-5"></i> Pesanan Baru
    </a>
</div>

{{-- Search Bar --}}
<form action="{{ route('admin.bookings.index') }}" method="GET" class="mb-6 flex flex-col sm:flex-row gap-3 items-stretch">
    <input type="hidden" name="status" value="{{ request('status', 'all') }}">
    <div class="relative flex-1">
        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <i data-lucide="search" class="w-5 h-5 text-outline"></i>
        </span>
        <input type="text" name="search" value="{{ request('search') }}" 
               placeholder="Cari booking berdasarkan nama klien..." 
               class="input-gold" style="padding: 12px 14px 12px 44px; height: 48px;">
    </div>
    <div class="flex gap-2">
        <button type="submit" class="arh-btn-primary px-6 h-12 flex items-center gap-2">
            <i data-lucide="search" class="w-4 h-4"></i> Cari
        </button>
        @if(request('search'))
        <a href="{{ route('admin.bookings.index', ['status' => request('status', 'all')]) }}" class="arh-btn-secondary px-5 h-12 flex items-center gap-2">
            <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Reset
        </a>
        @endif
    </div>
</form>

{{-- Filter Tabs --}}
<div class="flex gap-2 overflow-x-auto whitespace-nowrap scrollbar-none pb-2 -mx-4 px-4 md:flex-wrap md:mx-0 md:px-0 mb-6" id="filter-tabs">
    @php
        $statusActive = request('status', 'all');
        $tabs = ['all'=>"Semua ({$total})",'pending'=>"Pending ({$pending})",'dp_paid'=>"DP Dibayar ({$dpPaid})",'completed'=>"Selesai ({$done})",'cancelled'=>"Batal ({$canceled})"];
    @endphp
    @foreach($tabs as $key => $label)
    <a href="{{ route('admin.bookings.index', ['status' => $key, 'search' => request('search')]) }}"
       class="tab-filter {{ $statusActive === $key ? 'active' : '' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- ════ TABLE (Desktop) ════ --}}
<div class="hidden md:block card-gold overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table-gold">
            <thead>
                <tr>
                    <th>#Booking</th>
                    <th>Klien</th>
                    <th>Event</th>
                    <th class="text-right">Kontrak</th>
                    <th class="text-right">DP</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="booking-tbody">
                @forelse($bookings as $booking)
                @php
                    // Adjust status mapping for badges
                    $sm = [
                        'pending'   => ['PENDING',   'badge-gold'],
                        'dp_paid'   => ['DP PAID',   'badge-maroon'],
                        'confirmed' => ['CONFIRMED', 'badge-maroon'],
                        'completed' => ['SELESAI',   'badge-green'],
                        'cancelled' => ['BATAL',     'bg-red-50 text-red-600 border border-red-200 px-2 py-1 rounded-full text-xs font-bold uppercase'],
                    ];
                    [$stLabel, $stClass] = $sm[$booking->status] ?? [strtoupper($booking->status), 'badge-gold'];
                    $daysLeft = (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($booking->event_date)->startOfDay(), false);
                @endphp
                <tr data-status="{{ $booking->status }}">
                    <td>
                        <span class="badge-gold">
                            #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                        <div class="subtitle-gold mt-2" style="font-size:0.65rem;">{{ $booking->created_at->format('d M Y') }}</div>
                    </td>
                    <td>
                        <div style="font-weight:700; color:#1A1817;">{{ $booking->client_name ?? ($booking->client->name ?? 'Klien Manual') }}</div>
                        <div class="font-label text-xs text-outline">{{ $booking->client_phone ?? '—' }}</div>
                    </td>
                    <td>
                        <div style="font-weight:700; color:#8B1A2A; font-size:0.9rem;">{{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}</div>
                        <div class="subtitle-gold mt-1 capitalize" style="font-size:0.7rem;">{{ str_replace('_', ' ', $booking->event_type) }}</div>
                    </td>
                    <td class="text-right">
                        <div style="font-weight:700; color:#1A1817; font-size:0.9rem;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                        <div class="subtitle-gold mt-1" style="color:#C5A028; font-weight:700; font-size:0.65rem;">Laba (est): Rp {{ number_format($booking->total_price * 0.30, 0, ',', '.') }}</div>
                    </td>
                    <td class="text-right">
                        <div style="font-weight:700; color:#1A1817; font-size:0.9rem;">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
                        @if($booking->dp_paid_at)
                        <div class="subtitle-gold mt-1" style="font-size:0.65rem;">{{ \Carbon\Carbon::parse($booking->dp_paid_at)->format('d M Y') }}</div>
                        @elseif(!in_array($booking->status, ['pending', 'cancelled']))
                        <div class="subtitle-gold mt-1" style="font-size:0.65rem; color:#8B1A2A; font-weight:700;">{{ \Carbon\Carbon::parse($booking->updated_at)->format('d M Y') }}</div>
                        @else
                        <div class="subtitle-gold mt-1" style="font-size:0.65rem; color:#ea580c; font-weight:700;">BELUM BAYAR</div>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="{{ $stClass }}">{{ $stLabel }}</span>
                        @if($booking->status === 'pending' && $daysLeft <= 7 && $daysLeft >= 0)
                        <div style="font-size:0.6rem; color:#dc2626; font-weight:700; margin-top:4px;">⚠ H-{{ $daysLeft }}</div>
                        @endif
                    </td>
                    <td>
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn-action btn-action-view" title="Detail"><i data-lucide="eye" class="w-4 h-4"></i></a>
                            @if($booking->status === 'pending')
                                @if(!$booking->is_admin_confirmed)
                                    <button type="button" onclick="openAcceptModal({{ $booking->id }}, '{{ addslashes($booking->client_name) }}', '{{ $booking->smart_warning->class ?? 'info' }}', '{{ addslashes($booking->smart_warning->message ?? '') }}')" class="btn-action btn-action-accept" title="Terima Booking"><i data-lucide="check" class="w-4 h-4"></i></button>
                                    <button type="button" onclick="openRejectModal({{ $booking->id }}, '{{ addslashes($booking->client_name) }}')" class="btn-action btn-action-reject" title="Tolak Booking"><i data-lucide="x" class="w-4 h-4"></i></button>
                                @else
                                    <button type="button" onclick="openKunciModal({{ $booking->id }}, '{{ addslashes($booking->client_name) }}', {{ $booking->total_price }}, {{ $booking->dp_amount }})" class="btn-action btn-action-lock" title="Kunci Laba & Konfirmasi DP"><i data-lucide="lock" class="w-4 h-4"></i></button>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-16 text-center">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-yellow-600 opacity-50"></i>
                    <p class="title-gold" style="font-size:1.2rem; margin-bottom:8px;">Belum ada data booking</p>
                    <a href="{{ route('admin.bookings.create') }}" class="arh-btn-primary" style="display:inline-flex;">+ Buat Pesanan Manual</a>
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
        $sm = [
            'pending'   => ['PENDING',   'badge-gold'],
            'dp_paid'   => ['DP PAID',   'badge-maroon'],
            'confirmed' => ['CONFIRMED', 'badge-maroon'],
            'completed' => ['SELESAI',   'badge-green'],
            'cancelled' => ['BATAL',     'bg-red-50 text-red-600 border border-red-200 px-2 py-1 rounded-full text-xs font-bold uppercase'],
        ];
        [$stLabel, $stClass] = $sm[$booking->status] ?? [strtoupper($booking->status), 'badge-gold'];
        $daysLeft = (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($booking->event_date)->startOfDay(), false);
    @endphp
    <div data-status="{{ $booking->status }}" class="card-gold p-4">
        {{-- Card Header --}}
        <div class="flex items-center justify-between mb-3 border-b pb-3" style="border-color:rgba(197,160,40,0.15);">
            <span class="badge-gold">#{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
            <span class="{{ $stClass }}">{{ $stLabel }}</span>
        </div>
        {{-- Card Body --}}
        <div class="space-y-3">
            <div class="flex justify-between items-start gap-2">
                <div>
                    <div style="font-weight:700; color:#1A1817; font-size:1rem;">{{ $booking->client_name ?? ($booking->client->name ?? 'Klien Manual') }}</div>
                    <div class="subtitle-gold mt-1" style="font-size:0.65rem;">{{ $booking->client_phone ?? '—' }}</div>
                </div>
                <div class="text-right flex-shrink-0">
                    <div class="subtitle-gold" style="font-size:0.6rem;">Tanggal</div>
                    <div style="font-weight:700; font-size:0.85rem; color:#8B1A2A;">{{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}</div>
                </div>
            </div>
            <div class="flex items-center gap-1.5 subtitle-gold" style="font-size:0.7rem; color:#847B78;">
                <i data-lucide="calendar" class="w-4 h-4 text-yellow-600"></i>
                <span class="capitalize" style="font-weight:700;">{{ str_replace('_', ' ', $booking->event_type) }}</span>
                <span>•</span>
                <span>{{ $booking->created_at->format('d M Y') }}</span>
            </div>
            <div class="grid grid-cols-2 gap-3 mt-3">
                <div style="background:rgba(139,26,42,0.03); border:1px solid rgba(139,26,42,0.1); border-radius:10px; padding:10px;">
                    <div class="subtitle-gold mb-1" style="font-size:0.6rem;">Kontrak</div>
                    <div style="font-weight:700; font-size:0.9rem; color:#1A1817;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                </div>
                <div style="background:rgba(197,160,40,0.05); border:1px solid rgba(197,160,40,0.2); border-radius:10px; padding:10px;">
                    <div class="subtitle-gold mb-1" style="font-size:0.6rem;">DP</div>
                    <div style="font-weight:700; font-size:0.9rem; color:#8B1A2A;">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
                    @if($booking->dp_paid_at)
                    <div class="subtitle-gold mt-1" style="font-size:0.6rem;">{{ \Carbon\Carbon::parse($booking->dp_paid_at)->format('d M Y') }}</div>
                    @elseif(!in_array($booking->status, ['pending', 'cancelled']))
                    <div class="subtitle-gold mt-1" style="font-size:0.6rem; color:#8B1A2A;">{{ \Carbon\Carbon::parse($booking->updated_at)->format('d M Y') }}</div>
                    @else
                    <div class="subtitle-gold mt-1" style="font-size:0.6rem; color:#ea580c;">Belum bayar</div>
                    @endif
                </div>
            </div>
            @if($booking->status === 'pending' && $daysLeft <= 7 && $daysLeft >= 0)
            <div style="font-size:0.6rem; color:#dc2626; font-weight:700; margin-top:8px;">⚠ Acara H-{{ $daysLeft }}</div>
            @endif
        </div>
        {{-- Card Footer --}}
        <div class="px-4 py-3 border-t flex gap-2" style="border-color:rgba(197,160,40,0.15); background:rgba(197,160,40,0.02);">
            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="arh-btn-secondary flex-1 py-2 flex items-center justify-center gap-1.5" style="text-align:center;">
                <i data-lucide="eye" class="w-4 h-4"></i> Detail
            </a>
            @if($booking->status === 'pending')
                @if(!$booking->is_admin_confirmed)
                    <button type="button" onclick="openAcceptModal({{ $booking->id }}, '{{ addslashes($booking->client_name) }}', '{{ $booking->smart_warning->class ?? 'info' }}', '{{ addslashes($booking->smart_warning->message ?? '') }}')" class="flex-1 py-2 rounded-xl text-white font-bold text-xs uppercase tracking-widest flex items-center justify-center gap-1.5 transition-all bg-green-600 hover:bg-green-700 active:scale-95 shadow-sm"><i data-lucide="check" class="w-4 h-4"></i> Terima</button>
                    <button type="button" onclick="openRejectModal({{ $booking->id }}, '{{ addslashes($booking->client_name) }}')" class="flex-1 py-2 rounded-xl text-white font-bold text-xs uppercase tracking-widest flex items-center justify-center gap-1.5 transition-all bg-red-600 hover:bg-red-700 active:scale-95 shadow-sm"><i data-lucide="x" class="w-4 h-4"></i> Tolak</button>
                @else
                    <button type="button" onclick="openKunciModal({{ $booking->id }}, '{{ addslashes($booking->client_name) }}', {{ $booking->total_price }}, {{ $booking->dp_amount }})" class="flex-1 py-2 rounded-xl text-primary font-bold text-xs uppercase tracking-widest flex items-center justify-center gap-1.5 transition-all bg-gradient-to-r from-secondary-container to-secondary-fixed-dim hover:brightness-95 active:scale-95 border-none shadow-sm">
                        <i data-lucide="lock" class="w-4 h-4"></i> Kunci DP
                    </button>
                @endif
            @endif
        </div>
    </div>
    @empty
    <div class="py-14 flex flex-col items-center justify-center card-gold" style="border-style:dashed;">
        <i data-lucide="inbox" class="w-12 h-12 text-yellow-600 mb-2 opacity-50"></i>
        <p class="title-gold" style="font-size:1.1rem;">Belum ada data booking</p>
    </div>
    @endforelse
</div>

{{-- Pagination Links --}}
@if($bookings->hasPages())
<div class="mt-4 px-2">
    {{ $bookings->links() }}
</div>
@endif



{{-- ══ MODAL: KUNCI LABA (Konfirmasi DP) ══ --}}
<div id="modalKunciLaba" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-md" onclick="closeKunciModal()"></div>
    <div id="modalKunciContent" class="relative w-full max-w-sm card-gold p-6 transition-all scale-95 opacity-0">

        <div class="flex items-center justify-between mb-4 pb-3 border-b" style="border-color:rgba(197,160,40,0.2);">
            <h5 class="title-gold flex items-center gap-2" style="font-size:1.3rem;">
                <i data-lucide="lock" class="w-5 h-5 text-yellow-600"></i> Kunci Laba & DP
            </h5>
            <button onclick="closeKunciModal()" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>

        <form id="formKunciLaba" method="POST">
            @csrf
            <div class="mb-5 space-y-4">
                <div style="background:rgba(197,160,40,0.05); border:1px solid rgba(197,160,40,0.2); border-radius:10px; padding:12px;">
                    <div class="subtitle-gold mb-1" style="font-size:0.6rem;">Klien</div>
                    <div id="kunci_client" style="font-weight:700; color:#1A1817; font-size:0.95rem;"></div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div style="border:1px solid rgba(139,26,42,0.1); border-radius:10px; padding:10px; text-align:center;">
                        <div class="subtitle-gold mb-1" style="font-size:0.6rem;">Total Kontrak</div>
                        <div id="kunci_total" style="font-weight:700; color:#1A1817; font-size:0.9rem;"></div>
                    </div>
                    <div style="background:rgba(139,26,42,0.05); border:1px solid rgba(139,26,42,0.15); border-radius:10px; padding:10px; text-align:center;">
                        <div class="subtitle-gold mb-1" style="font-size:0.6rem; color:#8B1A2A;">DP Masuk</div>
                        <div id="kunci_dp" style="font-weight:700; color:#8B1A2A; font-size:0.9rem;"></div>
                    </div>
                </div>

                <div>
                    <label class="block subtitle-gold mb-1.5 ml-1">
                        Fixed Profit (Laba Bersih) <span class="text-red-600">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 font-bold" style="color:#8B1A2A; font-family:'Inter',sans-serif;">Rp</span>
                        <input type="number" name="fixed_profit_nominal" id="kunci_profit_input"
                               required min="0"
                               class="input-gold" style="padding-left:40px;"
                               placeholder="Misal: 2000000">
                    </div>
                    <p id="kunci_profit_hint" class="subtitle-gold mt-1.5 ml-1" style="text-transform:none; letter-spacing:normal;"></p>
                </div>
            </div>

            <div class="flex gap-2 pt-4 border-t" style="border-color:rgba(197,160,40,0.2);">
                <button type="button" onclick="closeKunciModal()" class="arh-btn-secondary flex-1 h-12 flex items-center justify-center">Batal</button>
                <button type="submit" class="arh-btn-primary flex-1 h-12 flex items-center justify-center gap-2">
                    <i data-lucide="lock" class="w-4 h-4"></i> Kunci Laba
                </button>
            </div>
        </form>
    </div>
</div>

</div>

{{-- ══ MODAL: TERIMA BOOKING (Smart Warning) ══ --}}
<div id="modalAccept" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-md" onclick="closeAcceptModal()"></div>
    <div id="modalAcceptContent" class="relative w-full max-w-sm card-gold p-6 transition-all scale-95 opacity-0">
        <div class="flex items-center justify-between mb-4 pb-3 border-b" style="border-color:rgba(197,160,40,0.2);">
            <h5 class="title-gold flex items-center gap-2" style="font-size:1.3rem;">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i> Konfirmasi Booking
            </h5>
            <button onclick="closeAcceptModal()" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="formAcceptBooking" method="POST">
            @csrf
            <div class="mb-5 space-y-4">
                <p class="subtitle-gold" style="text-transform:none; letter-spacing:normal;">Apakah Anda yakin ingin menerima booking dari <strong id="accept_client_name" class="text-gray-800"></strong>?</p>
                
                <div id="smart_warning_container" class="p-3 rounded-lg border">
                    <div class="flex gap-2">
                        <i id="smart_warning_icon" data-lucide="info" class="w-5 h-5 flex-shrink-0"></i>
                        <p id="smart_warning_text" class="text-sm font-medium"></p>
                    </div>
                </div>
            </div>
            <div class="flex gap-2 pt-4 border-t" style="border-color:rgba(197,160,40,0.2);">
                <button type="button" onclick="closeAcceptModal()" class="arh-btn-secondary flex-1 h-12 flex items-center justify-center">Batal</button>
                <button type="submit" class="flex-1 h-12 flex items-center justify-center gap-2 rounded-xl text-white font-bold text-xs uppercase tracking-widest transition-all bg-green-600 hover:bg-green-700 active:scale-95 shadow-md shadow-green-600/10 border-none">
                    <i data-lucide="check" class="w-4 h-4"></i> Terima Booking
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ MODAL: TOLAK BOOKING ══ --}}
<div id="modalReject" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-md" onclick="closeRejectModal()"></div>
    <div id="modalRejectContent" class="relative w-full max-w-sm card-gold p-6 transition-all scale-95 opacity-0">
        <div class="flex items-center justify-between mb-4 pb-3 border-b" style="border-color:rgba(197,160,40,0.2);">
            <h5 class="title-gold flex items-center gap-2" style="font-size:1.3rem;">
                <i data-lucide="x-circle" class="w-5 h-5 text-red-600"></i> Tolak Booking
            </h5>
            <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="formRejectBooking" method="POST">
            @csrf
            <div class="mb-5 space-y-4">
                <p class="subtitle-gold" style="text-transform:none; letter-spacing:normal;">Tolak booking dari <strong id="reject_client_name" class="text-gray-800"></strong>?</p>
                <div>
                    <label class="block subtitle-gold mb-1.5 ml-1">Alasan Penolakan <span class="text-red-600">*</span></label>
                    <textarea name="admin_note" required rows="3" class="input-gold" placeholder="Jelaskan alasan penolakan (misal: personel tidak mencukupi)..."></textarea>
                </div>
            </div>
            <div class="flex gap-2 pt-4 border-t" style="border-color:rgba(197,160,40,0.2);">
                <button type="button" onclick="closeRejectModal()" class="arh-btn-secondary flex-1 h-12 flex items-center justify-center">Batal</button>
                <button type="submit" class="flex-1 h-12 flex items-center justify-center gap-2 rounded-xl text-white font-bold text-xs uppercase tracking-widest transition-all bg-red-600 hover:bg-red-700 active:scale-95 shadow-md shadow-red-600/10 border-none">
                    <i data-lucide="x" class="w-4 h-4"></i> Tolak Booking
                </button>
            </div>
        </form>
    </div>
</div>

</div>

@endsection

@section('scripts')
<script>

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

function openAcceptModal(bookingId, clientName, warningClass, warningMessage) {
    document.getElementById('formAcceptBooking').action = `/admin/bookings/${bookingId}/accept`;
    document.getElementById('accept_client_name').textContent = clientName;
    
    const container = document.getElementById('smart_warning_container');
    const text = document.getElementById('smart_warning_text');
    const icon = document.getElementById('smart_warning_icon');
    
    text.textContent = warningMessage;
    
    // reset classes
    container.className = 'p-3 rounded-lg border mt-3 ';
    icon.setAttribute('data-lucide', 'info');
    
    if (warningClass === 'danger') {
        container.classList.add('bg-red-50', 'border-red-200', 'text-red-700');
        icon.setAttribute('data-lucide', 'alert-triangle');
    } else if (warningClass === 'warning') {
        container.classList.add('bg-orange-50', 'border-orange-200', 'text-orange-700');
        icon.setAttribute('data-lucide', 'alert-circle');
    } else {
        container.classList.add('bg-green-50', 'border-green-200', 'text-green-700');
        icon.setAttribute('data-lucide', 'check-circle');
    }
    lucide.createIcons();

    const modal = document.getElementById('modalAccept');
    const content = document.getElementById('modalAcceptContent');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeAcceptModal() {
    const modal = document.getElementById('modalAccept');
    const content = document.getElementById('modalAcceptContent');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
}

function openRejectModal(bookingId, clientName) {
    document.getElementById('formRejectBooking').action = `/admin/bookings/${bookingId}/reject`;
    document.getElementById('reject_client_name').textContent = clientName;

    const modal = document.getElementById('modalReject');
    const content = document.getElementById('modalRejectContent');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeRejectModal() {
    const modal = document.getElementById('modalReject');
    const content = document.getElementById('modalRejectContent');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
}
</script>
@endsection
