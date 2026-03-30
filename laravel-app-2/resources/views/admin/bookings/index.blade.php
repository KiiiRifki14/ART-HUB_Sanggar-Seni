@extends('layouts.admin')

@section('title', 'Daftar Booking - ART-HUB')
@section('page_title', 'Daftar Booking & DP Verification')
@section('page_subtitle', 'Kelola semua permintaan pementasan masuk dan status pembayaran DP.')

@section('content')

{{-- FLASH MESSAGES --}}
@if(session('success'))
<div class="glass-panel animate-fade-up" style="border-color: var(--success); background: var(--success-glow); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem; padding: 1rem 1.5rem;">
    <i class="ph-fill ph-check-circle" style="color: var(--success); font-size: 1.5rem; flex-shrink: 0;"></i>
    <span style="font-weight: 500;">{{ session('success') }}</span>
</div>
@endif
@if(session('error'))
<div class="glass-panel animate-fade-up" style="border-color: var(--danger); background: var(--danger-glow); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem; padding: 1rem 1.5rem;">
    <i class="ph-fill ph-warning-octagon" style="color: var(--danger); font-size: 1.5rem; flex-shrink: 0;"></i>
    <span style="font-weight: 500;">{{ session('error') }}</span>
</div>
@endif

{{-- STATS CARDS --}}
@php
    $total    = $bookings->count();
    $pending  = $bookings->where('status', 'pending')->count();
    $dpPaid   = $bookings->where('status', 'dp_paid')->count();
    $done     = $bookings->whereIn('status', ['confirmed', 'completed'])->count();
    $canceled = $bookings->where('status', 'cancelled')->count();
@endphp
<div class="grid grid-4 animate-fade-up" style="margin-bottom: 2rem; gap: 1rem;">
    <div class="glass-panel" style="text-align: center; padding: 1.2rem;">
        <i class="ph-fill ph-notepad" style="color: var(--gold-primary); font-size: 2rem;"></i>
        <h3 style="font-size: 1.8rem; margin: 0.3rem 0 0;">{{ $total }}</h3>
        <small class="text-muted">Total Booking</small>
    </div>
    <div class="glass-panel" style="text-align: center; padding: 1.2rem; border-color: var(--warning);">
        <i class="ph-fill ph-clock-countdown" style="color: var(--warning); font-size: 2rem;"></i>
        <h3 style="font-size: 1.8rem; margin: 0.3rem 0 0; color: var(--warning);">{{ $pending }}</h3>
        <small class="text-muted">Menunggu DP</small>
    </div>
    <div class="glass-panel" style="text-align: center; padding: 1.2rem; border-color: var(--gold-primary);">
        <i class="ph-fill ph-lock-key" style="color: var(--gold-primary); font-size: 2rem;"></i>
        <h3 class="title-gold" style="font-size: 1.8rem; margin: 0.3rem 0 0;">{{ $dpPaid }}</h3>
        <small class="text-muted">Laba Terkunci</small>
    </div>
    <div class="glass-panel" style="text-align: center; padding: 1.2rem; border-color: var(--success);">
        <i class="ph-fill ph-check-circle" style="color: var(--success); font-size: 2rem;"></i>
        <h3 style="font-size: 1.8rem; margin: 0.3rem 0 0; color: var(--success);">{{ $done }}</h3>
        <small class="text-muted">Selesai</small>
    </div>
</div>

{{-- TABLE PANEL --}}
<div class="glass-panel animate-fade-up stagger-1">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <h2 style="margin: 0; display: flex; align-items: center; gap: 0.8rem;">
            <i class="ph ph-receipt" style="color: var(--gold-primary);"></i>
            Semua Permintaan Masuk
        </h2>
        <a href="{{ route('admin.bookings.create') }}" class="btn btn-gold" style="padding: 0.7rem 1.5rem;">
            <i class="ph ph-plus-circle"></i> &nbsp;Tambah Manual
        </a>
    </div>

    {{-- FILTER STATUS TABS --}}
    <div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
        @php
            $statuses = [
                'all'       => ['label' => 'Semua',     'count' => $total,    'class' => 'badge-gold'],
                'pending'   => ['label' => 'Pending',   'count' => $pending,  'class' => 'badge-warning'],
                'dp_paid'   => ['label' => 'DP Paid',   'count' => $dpPaid,   'class' => ''],
                'completed' => ['label' => 'Selesai',   'count' => $done,     'class' => 'badge-success'],
                'cancelled' => ['label' => 'Batal',     'count' => $canceled, 'class' => 'badge-danger'],
            ];
        @endphp
        @foreach($statuses as $key => $s)
        <span class="badge {{ $s['class'] }}" style="padding: 0.4rem 0.9rem; font-size: 0.78rem; cursor: pointer;"
              onclick="filterTable('{{ $key }}')" id="tab-{{ $key }}">
            {{ $s['label'] }} ({{ $s['count'] }})
        </span>
        @endforeach
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;" id="booking-table">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase; white-space: nowrap;">#Booking</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase; white-space: nowrap;">Klien</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase; white-space: nowrap;">Jenis & Tanggal Event</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase; white-space: nowrap;">Nilai Kontrak</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase; white-space: nowrap;">DP</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase; white-space: nowrap;">Status</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase; white-space: nowrap;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                @php
                    $statusMap = [
                        'pending'   => ['label' => 'PENDING',   'cls' => 'badge-warning'],
                        'dp_paid'   => ['label' => 'DP PAID',   'cls' => 'badge-gold'],
                        'confirmed' => ['label' => 'CONFIRMED', 'cls' => 'badge-success'],
                        'completed' => ['label' => 'SELESAI',   'cls' => 'badge-success'],
                        'cancelled' => ['label' => 'BATAL',     'cls' => 'badge-danger'],
                    ];
                    $st = $statusMap[$booking->status] ?? ['label' => strtoupper($booking->status), 'cls' => ''];
                @endphp
                <tr class="booking-row" data-status="{{ $booking->status }}"
                    style="border-bottom: 1px solid var(--border-color); transition: background 0.2s;"
                    onmouseover="this.style.background='var(--bg-hover)'"
                    onmouseout="this.style.background='transparent'">

                    {{-- ID --}}
                    <td style="padding: 1rem;">
                        <span class="badge badge-gold" style="font-size: 0.75rem;">#{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
                        <div><small class="text-muted" style="font-size: 0.7rem;">{{ $booking->created_at->format('d M Y') }}</small></div>
                    </td>

                    {{-- KLIEN --}}
                    <td style="padding: 1rem;">
                        <div style="font-weight: 600;">{{ $booking->client_name ?? ($booking->client->name ?? 'Klien Manual') }}</div>
                        <small class="text-muted">{{ $booking->client_phone ?? '-' }}</small>
                    </td>

                    {{-- JENIS & TANGGAL --}}
                    <td style="padding: 1rem;">
                        <div style="text-transform: capitalize; font-weight: 500;">{{ $booking->event_type }}</div>
                        <small class="text-muted">
                            <i class="ph ph-calendar-blank"></i>
                            {{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}
                        </small>
                        <div>
                            <small class="text-muted">
                                <i class="ph ph-clock"></i>
                                {{ \Carbon\Carbon::parse($booking->event_start)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->event_end)->format('H:i') }}
                            </small>
                        </div>
                    </td>

                    {{-- NILAI KONTRAK --}}
                    <td style="padding: 1rem;">
                        <div style="font-weight: 700; font-size: 1rem;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                        <small class="text-muted" style="color: var(--gold-primary);">
                            Laba: Rp {{ number_format($booking->total_price * 0.30, 0, ',', '.') }}
                        </small>
                    </td>

                    {{-- DP --}}
                    <td style="padding: 1rem;">
                        <div style="font-weight: 600; color: var(--gold-light);">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
                        @if($booking->dp_paid_at)
                            <small class="text-muted">{{ \Carbon\Carbon::parse($booking->dp_paid_at)->format('d M Y') }}</small>
                        @else
                            <small style="color: var(--warning);">Belum dibayar</small>
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td style="padding: 1rem;">
                        <span class="badge {{ $st['cls'] }}">{{ $st['label'] }}</span>
                        @if($booking->status === 'dp_paid')
                            <div><small style="color: var(--success); font-size: 0.7rem;"><i class="ph ph-lock-key"></i> Laba Terkunci</small></div>
                        @endif
                        @if($booking->status === 'pending')
                            @php
                                $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($booking->event_date), false);
                            @endphp
                            @if($daysLeft <= 7 && $daysLeft >= 0)
                                <div><small style="color: var(--danger); font-size: 0.7rem; animation: pulse-text 1.5s infinite;">⚠️ H-{{ $daysLeft }} belum bayar!</small></div>
                            @endif
                        @endif
                    </td>

                    {{-- AKSI --}}
                    <td style="padding: 1rem;">
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}"
                               class="btn btn-outline" style="padding: 0.4rem 0.9rem; font-size: 0.78rem; white-space: nowrap;">
                                <i class="ph ph-eye"></i> Detail
                            </a>
                            @if($booking->status === 'pending')
                            <form method="POST" action="{{ route('admin.bookings.confirm', $booking->id) }}"
                                  onsubmit="return confirm('Konfirmasi DP & KUNCI LABA untuk booking #{{ $booking->id }}? Aksi TIDAK BISA DIBATALKAN.')">
                                @csrf
                                <button type="submit" class="btn btn-gold" style="padding: 0.4rem 0.9rem; font-size: 0.78rem; border: none; white-space: nowrap;">
                                    <i class="ph ph-lock-key"></i> Kunci Laba
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 4rem; text-align: center;">
                        <i class="ph ph-notepad" style="font-size: 3.5rem; color: var(--text-muted); display: block; margin-bottom: 1rem;"></i>
                        <p class="text-muted">Belum ada data booking masuk.</p>
                        <a href="{{ route('admin.bookings.create') }}" class="btn btn-gold" style="margin-top: 1rem;">
                            <i class="ph ph-plus-circle"></i> Buat Booking Manual
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    @keyframes pulse-text { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
</style>

<script>
    function filterTable(status) {
        const rows = document.querySelectorAll('.booking-row');
        rows.forEach(row => {
            if (status === 'all') {
                row.style.display = '';
            } else if (status === 'completed') {
                const s = row.dataset.status;
                row.style.display = (s === 'confirmed' || s === 'completed') ? '' : 'none';
            } else {
                row.style.display = row.dataset.status === status ? '' : 'none';
            }
        });
        // Highlight active tab
        document.querySelectorAll('[id^="tab-"]').forEach(t => {
            t.style.opacity = '0.5';
            t.style.transform = 'scale(0.95)';
        });
        const active = document.getElementById('tab-' + status);
        if (active) {
            active.style.opacity = '1';
            active.style.transform = 'scale(1)';
        }
    }
    // Default: semua tab aktif
    filterTable('all');
</script>
@endsection