@extends('layouts.admin')
@section('title', 'DP Verification & Payment Tracking – ART-HUB')
@section('page_title', 'DP Verification & Payment Tracking')
@section('page_subtitle', 'Pastikan keamanan finansial sanggar dengan verifikasi bukti transfer klien.')

@section('content')
<style>
    /* ═══════════════════════════════════════════════════════
       SUMMARY CARDS
    ═══════════════════════════════════════════════════════ */
    .dpv-summary-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 28px;
    }
    .dpv-card {
        background: #FFFFFF;
        border: 1px solid #E0D0D2;
        border-radius: 14px;
        padding: 20px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-shadow: 0 1px 6px rgba(139,26,42,0.06);
    }
    .dpv-card:hover { border-color: #8B1A2A; box-shadow: 0 4px 16px rgba(139,26,42,0.1); }
    .dpv-card-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .dpv-card-icon.warn   { background: rgba(251,191,36,0.12); color: #d97706; }
    .dpv-card-icon.success{ background: rgba(22,163,74,0.12);  color: #16a34a; }
    .dpv-card-icon.info   { background: rgba(139,26,42,0.1);   color: #8B1A2A; }
    .dpv-card-label { font-size: 0.72rem; color: #7a5a5e; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em; }
    .dpv-card-value { font-size: 1.35rem; font-weight: 700; line-height: 1.2; color: #1A0808; }
    .dpv-card-value.green { color: #16a34a; }
    .dpv-card-value.blue  { color: #8B1A2A; }

    /* ═══════════════════════════════════════════════════════
       MAIN TABLE PANEL
    ═══════════════════════════════════════════════════════ */
    .dpv-panel {
        background: #FFFFFF;
        border: 1px solid #E0D0D2;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 1px 6px rgba(139,26,42,0.06);
    }
    .dpv-panel-header {
        display: flex; align-items: center;
        justify-content: space-between;
        padding: 16px 22px;
        border-bottom: 1px solid #E0D0D2;
        background: #fdf9f9;
    }
    .dpv-panel-title { font-size: 0.95rem; font-weight: 600; color: #1A0808; display: flex; align-items: center; gap: 10px; }
    .dpv-search-wrap { position: relative; }
    .dpv-search-wrap input {
        background: #FFFFFF;
        border: 1px solid #E0D0D2;
        border-radius: 8px;
        color: #1A0808;
        padding: 7px 14px 7px 36px;
        font-size: 0.8rem;
        width: 220px;
        transition: border-color 0.2s;
    }
    .dpv-search-wrap input:focus { outline: none; border-color: #8B1A2A; box-shadow: 0 0 0 3px rgba(139,26,42,0.1); }
    .dpv-search-wrap input::placeholder { color: #7a5a5e; }
    .dpv-search-wrap .search-icon {
        position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
        color: #7a5a5e; font-size: 0.8rem;
    }

    /* Table */
    .dpv-table { width: 100%; border-collapse: collapse; }
    .dpv-table thead tr { background: #fdf9f9; }
    .dpv-table th {
        padding: 11px 16px; text-align: left;
        font-size: 0.7rem; font-weight: 700;
        color: #8B1A2A; letter-spacing: 0.08em; text-transform: uppercase;
        border-bottom: 1px solid #E0D0D2;
    }
    .dpv-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #F4EEF0;
        vertical-align: middle;
        font-size: 0.83rem;
        color: #1A0808;
    }
    .dpv-table tbody tr:last-child td { border-bottom: none; }
    .dpv-table tbody tr:hover td { background: rgba(139,26,42,0.03); }

    .booking-code { font-family: 'Courier New', monospace; color: #8B1A2A; font-weight: 600; font-size: 0.82rem; }
    .client-name  { font-weight: 600; color: #1A0808; }
    .event-type-badge {
        font-size: 0.68rem; color: #7a5a5e;
        background: #F9F5F5; border: 1px solid #E0D0D2;
        border-radius: 4px; padding: 2px 6px; display: inline-block; margin-top: 3px;
    }
    .nominal-dp { color: #16a34a; font-weight: 700; }
    .dp-label   { font-size: 0.68rem; color: #7a5a5e; }

    /* Tombol Aksi */
    .btn-lihat-bukti {
        background: #F9F5F5; border: 1px solid #E0D0D2;
        border-radius: 7px; padding: 6px 12px; font-size: 0.75rem;
        cursor: pointer; display: inline-flex; align-items: center; gap: 5px;
        transition: border-color 0.2s, color 0.2s; color: #1A0808;
        text-decoration: none;
    }
    .btn-lihat-bukti:hover { border-color: #8B1A2A; color: #8B1A2A; background: rgba(139,26,42,0.05); }
    .btn-verify {
        background: #8B1A2A; color: #fff; border: none;
        border-radius: 7px; padding: 7px 14px; font-size: 0.75rem; font-weight: 700;
        cursor: pointer; transition: background 0.15s;
    }
    .btn-verify:hover { background: #6B1020; }
    .btn-reject {
        background: transparent; color: #ef4444; border: 1px solid rgba(239,68,68,0.4);
        border-radius: 7px; padding: 7px 12px; font-size: 0.75rem; font-weight: 600;
        cursor: pointer; transition: background 0.15s, border-color 0.15s;
    }
    .btn-reject:hover { background: rgba(239,68,68,0.08); border-color: #ef4444; }
    .action-group { display: flex; align-items: center; gap: 8px; }

    /* Empty State */
    .dpv-empty { padding: 50px; text-align: center; color: #7a5a5e; }

    /* ═══════════════════════════════════════════════════════
       WAITING LIST (Belum Upload)
    ═══════════════════════════════════════════════════════ */
    .wait-row {
        display: flex; align-items: center; gap: 14px;
        padding: 14px 22px; border-bottom: 1px solid #F4EEF0;
        transition: background 0.15s;
    }
    .wait-row:last-child { border-bottom: none; }
    .wait-row:hover { background: rgba(139,26,42,0.03); }
    .wait-icon {
        width: 40px; height: 40px; border-radius: 10px;
        background: #F9F5F5; border: 1px dashed #E0D0D2;
        display: flex; align-items: center; justify-content: center;
        color: #7a5a5e; flex-shrink: 0;
    }

    /* ═══════════════════════════════════════════════════════
       MODAL VERIFIKASI
    ═══════════════════════════════════════════════════════ */
    .modal-content.dpv-modal {
        background: #FFFFFF;
        border: 1px solid #E0D0D2;
        border-radius: 16px;
    }
    .dpv-modal .modal-header {
        border-bottom: 1px solid #E0D0D2;
        padding: 18px 22px;
        background: #fdf9f9;
    }
    .dpv-modal .modal-footer {
        border-top: 1px solid #E0D0D2;
        padding: 14px 22px;
        background: #fdf9f9;
    }
    .dpv-deal-card {
        background: #F9F5F5;
        border: 1px solid #E0D0D2;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 14px;
    }
    .dpv-deal-card .label { font-size: 0.7rem; color: #7a5a5e; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 5px; }
    .dpv-deal-card .value { font-size: 0.95rem; color: #1A0808; font-weight: 500; }
    .dpv-deal-card .value.bold { font-size: 1.1rem; font-weight: 700; color: #8B1A2A; }

    .dpv-profit-card {
        background: linear-gradient(135deg, #1a2e1a, #1e3520);
        border: 1px solid #2d5a2d;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 14px;
    }
    .dpv-profit-card .label { font-size: 0.7rem; color: #6b9c6b; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 4px; }
    .dpv-profit-card .value { font-size: 1.4rem; font-weight: 800; color: #34d399; }
    .dpv-profit-card .sub   { font-size: 0.72rem; color: #4a7a4a; margin-top: 3px; }
    .badge-pending-modal {
        display: inline-flex; align-items: center; gap: 5px;
        background: #2a2000; border: 1px solid #5a4000; color: #fbbf24;
        border-radius: 6px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600;
    }
    .proof-preview-img {
        width: 100%; max-height: 180px; object-fit: cover;
        border-radius: 8px; border: 1px solid #2a2a2a; cursor: pointer;
    }
</style>

{{-- Flash messages --}}
@if(session('success'))
    <div class="alert d-flex align-items-center gap-2 mb-4 py-3 px-4 rounded-3"
         style="background: #0f2a1a; border:1px solid #2d5a2d; color:#4ade80; font-size:0.85rem;">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif
@if(session('warning'))
    <div class="alert d-flex align-items-center gap-2 mb-4 py-3 px-4 rounded-3"
         style="background: #2a1a00; border:1px solid #5a3a00; color:#fbbf24; font-size:0.85rem;">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('warning') }}
    </div>
@endif
@if(session('error'))
    <div class="alert d-flex align-items-center gap-2 mb-4 py-3 px-4 rounded-3"
         style="background: #2a0f0f; border:1px solid #5a2d2d; color:#f87171; font-size:0.85rem;">
        <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
    </div>
@endif

{{-- ═══════════════════════════ SUMMARY CARDS ═══════════════════════════ --}}
<div class="dpv-summary-cards">

    {{-- Kartu 1: Antrean --}}
    <div class="dpv-card">
        <div class="dpv-card-icon warn"><i class="bi bi-clock-history"></i></div>
        <div>
            <div class="dpv-card-label">Menunggu Verifikasi</div>
            <div class="dpv-card-value">{{ $antreanCount }} Antrean</div>
        </div>
    </div>

    {{-- Kartu 2: Total DP Masuk --}}
    <div class="dpv-card">
        <div class="dpv-card-icon success"><i class="bi bi-wallet2"></i></div>
        <div>
            <div class="dpv-card-label">Total DP Masuk</div>
            <div class="dpv-card-value green">Rp {{ number_format($totalDpMasuk, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Kartu 3: Profit Locked --}}
    <div class="dpv-card">
        <div class="dpv-card-icon info"><i class="bi bi-lock-fill"></i></div>
        <div>
            <div class="dpv-card-label">Profit Aman (Locked)</div>
            <div class="dpv-card-value blue">Rp {{ number_format($totalProfitLocked, 0, ',', '.') }}</div>
        </div>
    </div>

</div>

{{-- ═══════════════════ TABEL ANTREAN VERIFIKASI ═══════════════════ --}}
<div class="dpv-panel">
    <div class="dpv-panel-header">
        <div class="dpv-panel-title">
            <i class="bi bi-shield-check" style="color:#8B1A2A;"></i>
            Antrean Verifikasi Bukti Bayar
            @if($antreanCount > 0)
                <span class="badge rounded-pill" style="background:#8B1A2A; color:#000; font-size:0.7rem; padding: 3px 8px;">{{ $antreanCount }}</span>
            @endif
        </div>
        <div class="dpv-search-wrap">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchKlien" placeholder="Cari Klien..." oninput="filterTable()">
        </div>
    </div>

    <table class="dpv-table" id="dpvTable">
        <thead>
            <tr>
                <th>Kode Booking</th>
                <th>Klien & Acara</th>
                <th>Total Deal</th>
                <th>Nominal DP</th>
                <th>Bukti</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingWithProof as $booking)
            <tr data-client="{{ strtolower($booking->client_name) }}">
                <td><span class="booking-code">BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</span></td>
                <td>
                    <div class="client-name">{{ $booking->client_name }}</div>
                    <span class="event-type-badge">{{ ucwords(str_replace('_', ' ', $booking->event_type)) }}</span>
                </td>
                <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                <td>
                    <div class="nominal-dp">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
                    <div class="dp-label">50% COMMITMENT FEE</div>
                </td>
                <td>
                    @if($booking->payment_proof)
                        {{-- Buka Modal Verifikasi Detail --}}
                        <button class="btn-lihat-bukti"
                            data-bs-toggle="modal"
                            data-bs-target="#modalVerify{{ $booking->id }}">
                            <i class="bi bi-eye"></i> Lihat Bukti
                        </button>
                    @else
                        <span style="color:#444; font-size:0.75rem;"><i class="bi bi-image me-1"></i>Belum ada</span>
                    @endif
                </td>
                <td>
                    <div class="action-group">
                        {{-- Tombol Verify (submit dari modal) --}}
                        <button class="btn-verify"
                            data-bs-toggle="modal"
                            data-bs-target="#modalVerify{{ $booking->id }}">
                            <i class="bi bi-check-lg me-1"></i>Verify
                        </button>
                        {{-- Tombol Reject --}}
                        <form action="{{ route('admin.bookings.reject_proof', $booking->id) }}" method="POST"
                            onsubmit="return confirm('Tolak & hapus bukti transfer dari {{ $booking->client_name }}? Klien akan diwajibkan upload ulang.')">
                            @csrf
                            <button type="submit" class="btn-reject">
                                <i class="bi bi-x-lg me-1"></i>Reject
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="dpv-empty">
                        <i class="bi bi-patch-check-fill fs-1 d-block mb-2" style="color:#34d399;"></i>
                        Tidak ada bukti transfer yang menunggu konfirmasi.<br>
                        <small style="color:#444;">Semua DP sudah diverifikasi! ✅</small>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ═══════════════ DAFTAR MENUNGGU UPLOAD ═══════════════ --}}
<div class="dpv-panel">
    <div class="dpv-panel-header">
        <div class="dpv-panel-title" style="color:#888;">
            <i class="bi bi-hourglass-split" style="color:#555;"></i>
            Menunggu Klien Upload Bukti Transfer
            <span class="badge rounded-pill bg-secondary" style="font-size:0.7rem; padding: 3px 8px;">{{ $pendingNoProof->count() }}</span>
        </div>
    </div>

    @forelse($pendingNoProof as $booking)
    <div class="wait-row">
        <div class="wait-icon"><i class="bi bi-cloud-upload"></i></div>
        <div style="flex:1;">
            <div style="font-weight:600; color:#bbb; font-size:0.85rem;">{{ $booking->client_name }}</div>
            <div style="font-size:0.75rem; color:#555; margin-top:2px;">
                <i class="bi bi-calendar me-1"></i>{{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}
                <span class="mx-2">·</span>
                <i class="bi bi-phone me-1"></i>{{ $booking->client_phone }}
                @php $createdDays = \Carbon\Carbon::parse($booking->created_at)->diffInDays(now()); @endphp
                @if($createdDays > 3)
                    <span class="ms-2 badge bg-danger" style="font-size:0.65rem;">Overdue {{ $createdDays }} hari</span>
                @endif
            </div>
        </div>
        <div style="text-align:right; margin-right:16px;">
            <div style="font-size:0.68rem; color:#555;">Total Kontrak</div>
            <div style="font-weight:700; color:#8B1A2A; font-size:0.88rem;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
        </div>
        <a href="{{ route('admin.bookings.show', $booking->id) }}"
           style="background:#1a1a1a; border:1px solid #333; color:#aaa; border-radius:7px; padding:7px 14px; font-size:0.75rem; text-decoration:none; white-space:nowrap; transition:all 0.2s;"
           onmouseover="this.style.borderColor='#555'; this.style.color='#fff';"
           onmouseout="this.style.borderColor='#333'; this.style.color='#aaa';">
            <i class="bi bi-pencil me-1"></i>Negotiation
        </a>
    </div>
    @empty
    <div class="dpv-empty">
        <i class="bi bi-inbox fs-1 d-block mb-2" style="color:#333;"></i>
        Tidak ada booking yang sedang menunggu.
    </div>
    @endforelse
</div>


{{-- ═══════════════════════════════════════════════════════
     MODAL VERIFIKASI DETAIL (per Booking)
═══════════════════════════════════════════════════════ --}}
@foreach($pendingWithProof as $booking)
@php
    $targetProfit = $booking->total_price * 0.30;
    $dpAmount     = $booking->dp_amount;
    $fixedProfit  = min($dpAmount, $targetProfit);
    $opsBudget    = max(0, $dpAmount - $fixedProfit);
    $eventDate    = \Carbon\Carbon::parse($booking->event_date)->isoFormat('D MMMM Y');
@endphp

<div class="modal fade" id="modalVerify{{ $booking->id }}" tabindex="-1" aria-labelledby="modalVerifyLabel{{ $booking->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content dpv-modal">

            {{-- Header --}}
            <div class="modal-header">
                <div>
                    <h5 class="modal-title  fw-bold" id="modalVerifyLabel{{ $booking->id }}">
                        <i class="bi bi-shield-check me-2" style="color:#8B1A2A;"></i>
                        Order Management – DP Verification
                    </h5>
                    <div style="font-size:0.75rem; color:#555; margin-top:3px;">
                        <span class="booking-code">BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</span>
                        &nbsp;·&nbsp; {{ $eventDate }}
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-4">
                <div class="row g-3">

                    {{-- Kolom Kiri: Deal Summary --}}
                    <div class="col-md-7">
                        <div style="font-size:0.72rem; color:#666; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:12px; padding-bottom:8px; border-bottom:1px solid #222;">
                            Deal Summary
                        </div>

                        <div class="dpv-deal-card">
                            <div class="label">Client Name</div>
                            <div class="value bold">{{ $booking->client_name }}</div>
                        </div>

                        <div class="dpv-deal-card">
                            <div class="label">Total Contract Price</div>
                            <div class="value bold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <div class="dpv-deal-card">
                                    <div class="label">DP Amount Received (50%)</div>
                                    <div class="value bold" style="color:#34d399;">Rp {{ number_format($dpAmount, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="dpv-deal-card">
                                    <div class="label">Payment Status</div>
                                    <div class="mt-2">
                                        <span class="badge-pending-modal">
                                            <i class="bi bi-clock-fill" style="font-size:0.65rem;"></i>
                                            Pending Verification
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Profit Card --}}
                        <div class="dpv-profit-card">
                            <div class="label">Fixed Profit Secured (30%)</div>
                            <div class="value">Rp {{ number_format($fixedProfit, 0, ',', '.') }}</div>
                            <div class="sub">Fixed profit amount set aside from total contract price</div>
                        </div>

                        @if($opsBudget > 0)
                        <div class="dpv-deal-card" style="border-color:#2a3a2a;">
                            <div class="label">Sisa Budget Operasional</div>
                            <div class="value" style="color:#60a5fa;">Rp {{ number_format($opsBudget, 0, ',', '.') }}</div>
                        </div>
                        @else
                        <div style="background:#2a1a00; border:1px solid #5a3a00; border-radius:10px; padding:12px 16px; font-size:0.78rem; color:#fbbf24;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            DP lebih kecil dari target laba 30% — seluruh DP dikunci sebagai cicilan laba.
                            Budget operasional menunggu pelunasan.
                        </div>
                        @endif
                    </div>

                    {{-- Kolom Kanan: Preview Bukti --}}
                    <div class="col-md-5">
                        <div style="font-size:0.72rem; color:#666; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:12px; padding-bottom:8px; border-bottom:1px solid #222;">
                            Bukti Transfer
                        </div>
                        @if($booking->payment_proof)
                            <img src="{{ asset('storage/' . $booking->payment_proof) }}"
                                 class="proof-preview-img mb-2"
                                 alt="Bukti Transfer {{ $booking->client_name }}"
                                 onclick="window.open(this.src, '_blank')">
                            <div style="font-size:0.7rem; color:#555; text-align:center;">
                                <i class="bi bi-zoom-in me-1"></i>Klik gambar untuk buka full-size
                            </div>
                            <a href="{{ asset('storage/' . $booking->payment_proof) }}" download
                               class="btn-lihat-bukti w-100 justify-content-center mt-2">
                                <i class="bi bi-download"></i> Unduh Bukti
                            </a>
                        @else
                            <div style="background:#1a1a1a; border:2px dashed #2a2a2a; border-radius:10px; padding:40px; text-align:center; color:#444;">
                                <i class="bi bi-image fs-2 d-block mb-2"></i>
                                Belum ada bukti
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            {{-- Footer Aksi --}}
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                    Batal
                </button>
                <div class="d-flex gap-2">
                    {{-- Reject --}}
                    <form action="{{ route('admin.bookings.reject_proof', $booking->id) }}" method="POST"
                          onsubmit="return confirm('Tolak & hapus bukti transfer dari {{ $booking->client_name }}?')">
                        @csrf
                        <button type="submit"
                            style="background: #1a0000; border: 1px solid #7f1d1d; color: #f87171; border-radius: 8px; padding: 8px 18px; font-size:0.82rem; font-weight:600; cursor:pointer;">
                            <i class="bi bi-x-circle me-1"></i>✕ Reject Proof
                        </button>
                    </form>
                    {{-- Confirm --}}
                    @php
                        $confirmMsg = 'Konfirmasi DP & Kunci Laba Rp ' . number_format($fixedProfit, 0, ',', '.') . ' untuk booking ' . $booking->client_name . '?';
                    @endphp
                    <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST"
                          onsubmit="return confirm(this.dataset.msg)"
                          data-msg="{{ $confirmMsg }}">
                        @csrf
                        <button type="submit"
                            style="background: #34d399; color: #000; border: none; border-radius: 8px; padding: 8px 22px; font-size:0.82rem; font-weight:700; cursor:pointer;">
                            <i class="bi bi-check-circle me-1"></i>✓ Confirm Payment
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endforeach

{{-- ═══════════ SEARCH FILTER SCRIPT ═══════════ --}}
<script>
function filterTable() {
    var query = document.getElementById('searchKlien').value.toLowerCase();
    var rows = document.querySelectorAll('#dpvTable tbody tr[data-client]');
    for (var i = 0; i < rows.length; i++) {
        var name = rows[i].getAttribute('data-client') || '';
        rows[i].style.display = name.includes(query) ? '' : 'none';
    }
}
</script>

@endsection


