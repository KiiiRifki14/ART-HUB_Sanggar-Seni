@extends('layouts.admin')

@section('title', 'Verifikasi Booking - EVT-2026-045')
@section('page_title', 'Verifikasi DP & Booking')
@section('page_subtitle', 'Tinjau bukti transfer dan kunci Fixed Profit Pimpinan.')

@section('content')

    <div class="grid grid-2 animate-fade-up stagger-1">
        <!-- Rincian Acara -->
        <div class="glass-panel">
            <h3 style="margin-bottom: 1.5rem; color: var(--text-main); display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph ph-calendar-check" style="color: var(--gold-primary);"></i> Rincian Booking
            </h3>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px dashed var(--border-color); padding-bottom: 0.5rem;">
                <span class="text-muted">Nama Klien</span>
                <span style="font-weight: 600;">Sinta Aryanti</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px dashed var(--border-color); padding-bottom: 0.5rem;">
                <span class="text-muted">Jenis Acara</span>
                <span style="font-weight: 600;">Resepsi Pernikahan (Jaipong)</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px dashed var(--border-color); padding-bottom: 0.5rem;">
                <span class="text-muted">Jadwal</span>
                <span style="font-weight: 600; color: var(--gold-light);">Min, 12 Apr 2026 | 19:00</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                <span class="text-muted">Total Tagihan</span>
                <span style="font-weight: 700; font-size: 1.1rem;">Rp 15.000.000</span>
            </div>
        </div>

        <!-- Bukti Transfer & Konfirmasi -->
        <div class="glass-panel" style="border-color: var(--gold-primary);">
            <h3 style="margin-bottom: 1.5rem; color: var(--gold-light); display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph ph-receipt"></i> Verifikasi Transfer DP
            </h3>

            <!-- Simulasi Bukti Transfer -->
            <div style="background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); border-radius: 12px; padding: 2rem; text-align: center; margin-bottom: 1.5rem;">
                <i class="ph ph-image" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                <p class="text-muted" style="margin-bottom: 0;">resi_transfer_bca_sinta.jpg</p>
                <div style="margin-top: 1rem;">
                    <span class="badge badge-success">Dana DP Tercatat Masuk: Rp 5.000.000</span>
                </div>
            </div>

            <!-- Tombol Pemicu Modal -->
            <button type="button" class="btn btn-gold" style="width: 100%; padding: 1.2rem; font-size: 1.05rem;" onclick="openModal('modalLockProfit')">
                <i class="ph ph-shield-check"></i> Konfirmasi Pembayaran & Kunci Laba
            </button>
        </div>
    </div>

    <!-- MENGGUNAKAN KOMPONEN MODAL YANG BARU SAJA DIBUAT (Kritik Bu Sari Passed!) -->
    <x-gold-modal 
        id="modalLockProfit"
        title="Konfirmasi Pembayaran DP"
        amountLabel="Nilai Laba Tetap (Fixed Profit 30%) yang akan dikunci:"
        amountValue="Rp 4.500.000"
        actionUrl="{{ route('admin.bookings.confirm', 1) }}"
        actionText="Kunci Profit & Buat Event"
    />

@endsection
