@extends('layouts.admin')

@section('title', 'Cahaya Gumilang - Executive Dashboard')
@section('page_title', 'Executive Dashboard')
@section('page_subtitle', 'Ringkasan finansial dan penjadwalan sanggar secara real-time.')

@section('content')

    <!-- STATISTIK KEUANGAN & AUDIT (Baris 1) -->
    <div class="grid grid-3 animate-fade-up">

        <!-- KOTAK 1: Kunci Laba Pimpinan -->
        <div class="glass-panel card-gold" style="position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                <div>
                    <h3 style="color: var(--gold-light); margin-bottom: 0;">Fixed Profit (Bulan Ini)</h3>
                    <p class="text-muted" style="font-size: 0.9rem;">Terkunci via Basis Data 2</p>
                </div>
                <div style="background: var(--gold-glow); padding: 0.8rem; border-radius: 12px;">
                    <i class="ph-fill ph-vault" style="color: var(--gold-primary); font-size: 1.8rem;"></i>
                </div>
            </div>
            <!-- Data Dummy: Nantinya pakai variabel $totalProfit -->
            <h1 class="title-gold" style="font-size: 2.5rem; margin-bottom: 0;">Rp 18.500.000</h1>
            <div style="margin-top: 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem;">
                <span class="badge badge-gold" style="font-size: 0.7rem;"><i class="ph ph-lock-key"></i> Laba Diamankan</span>
                <span class="text-muted">Dari 5 Event Dikonfirmasi</span>
            </div>
        </div>

        <!-- KOTAK 2: Safety Buffer (Dana Darurat) -->
        <div class="glass-panel">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                <div>
                    <h3 style="color: var(--text-main); margin-bottom: 0;">Safety Buffer Standby</h3>
                    <p class="text-muted" style="font-size: 0.9rem;">10% Potongan Operasional</p>
                </div>
                <div style="background: rgba(255,255,255,0.05); padding: 0.8rem; border-radius: 12px; border: 1px solid var(--border-color);">
                    <i class="ph-fill ph-shield-check" style="color: var(--success); font-size: 1.8rem;"></i>
                </div>
            </div>
            <h1 style="font-size: 2.5rem; margin-bottom: 0;">Rp 2.140.000</h1>
            <div style="margin-top: 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem;">
                <span class="badge badge-success" style="font-size: 0.7rem;">Siap Menutup Ghosting</span>
            </div>
        </div>

        <!-- KOTAK 3: Insiden & Denda Lapangan -->
        <div class="glass-panel" style="border-color: var(--danger-glow);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                <div>
                    <h3 style="color: var(--text-main); margin-bottom: 0;">Denda Kru Masuk</h3>
                    <p class="text-muted" style="font-size: 0.9rem;">Dari Event Logs MySQL</p>
                </div>
                <div style="background: var(--danger-glow); padding: 0.8rem; border-radius: 12px;">
                    <i class="ph-fill ph-warning-octagon" style="color: var(--danger); font-size: 1.8rem;"></i>
                </div>
            </div>
            <h1 style="color: var(--danger); font-size: 2.5rem; margin-bottom: 0;">Rp 120.000</h1>
            <div style="margin-top: 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem;">
                <span class="badge badge-danger" style="font-size: 0.7rem;">2 Insiden Terlambat</span>
            </div>
        </div>

    </div>

    <!-- JADWAL EVENT MINGGU INI (Baris 2) -->
    <div class="grid grid-2 animate-fade-up stagger-1" style="margin-top: 2rem;">
        
        <!-- KOLOM KIRI: EVENT AKTIF -->
        <div class="glass-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2 style="margin: 0; display: flex; align-items: center; gap: 0.8rem;">
                    <i class="ph ph-calendar-star" style="color: var(--gold-primary);"></i> Smart Event Radar
                </h2>
                <a href="#" class="btn btn-outline" style="padding: 0.4rem 1rem; font-size: 0.8rem;">Lihat Semua</a>
            </div>

            <!-- List Item Event 1 -->
            <div style="padding: 1.5rem; background: rgba(0,0,0,0.2); border-radius: 12px; border: 1px solid var(--border-color); margin-bottom: 1rem; transition: 0.3s;" onmouseover="this.style.borderColor='var(--gold-primary)'" onmouseout="this.style.borderColor='var(--border-color)'">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <div style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem;">
                            <span class="badge badge-gold">EVT-2026-045</span>
                            <span class="badge badge-success">READY</span>
                        </div>
                        <h3 style="margin-bottom: 0.2rem;">Pernikahan Klien A (Jaipong)</h3>
                        <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 0;"><i class="ph ph-map-pin"></i> Gedung Serbaguna Karawaci</p>
                    </div>
                    <div style="text-align: right;">
                        <div class="text-gold" style="font-weight: 700; color: var(--gold-primary); font-size: 1.1rem;">Min, 12 Apr</div>
                        <div class="text-muted" style="font-size: 0.8rem;">19:00 - 22:00</div>
                    </div>
                </div>
                
                <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px dashed var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; gap: -10px;">
                        <!-- Avatar Personil Dummy -->
                        <div style="width: 30px; height: 30px; border-radius: 50%; background: #333; border: 2px solid var(--bg-card); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: bold; position: relative;">SN</div>
                        <div style="width: 30px; height: 30px; border-radius: 50%; background: #444; border: 2px solid var(--bg-card); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: bold; position: relative; left: -10px;">DA</div>
                        <div style="width: 30px; height: 30px; border-radius: 50%; background: #555; border: 2px solid var(--bg-card); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: bold; position: relative; left: -20px;">+9</div>
                    </div>
                    <div>
                        <a href="{{ url('admin/events/1/plotting') }}" class="btn btn-outline trigger-loader" style="padding: 0.4rem 1rem; font-size: 0.8rem;">Cek Plotting</a>
                    </div>
                </div>
            </div>

            <!-- List Item Event 2 -->
            <div style="padding: 1.5rem; background: rgba(0,0,0,0.2); border-radius: 12px; border: 1px solid var(--border-color); margin-bottom: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <div style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem;">
                            <span class="badge" style="background: rgba(255,255,255,0.1); border: 1px solid var(--text-muted);">EVT-2026-046</span>
                            <span class="badge" style="background: rgba(255,255,255,0.1); border: 1px solid var(--text-muted);">PLANNING</span>
                        </div>
                        <h3 style="margin-bottom: 0.2rem;">Gathering Kantor (Degung)</h3>
                        <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 0;"><i class="ph ph-map-pin"></i> Hotel Aston BSD</p>
                    </div>
                    <div style="text-align: right;">
                        <div class="text-gold" style="font-weight: 700; color: var(--gold-primary); font-size: 1.1rem;">Kam, 16 Apr</div>
                        <div class="text-muted" style="font-size: 0.8rem;">10:00 - 13:00</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN: NOTIFIKASI & WARNING SYSTEM -->
        <div class="glass-panel" style="display: flex; flex-direction: column;">
            <h2 style="margin: 0 0 2rem 0; display: flex; align-items: center; gap: 0.8rem;">
                <i class="ph ph-bell-ringing" style="color: var(--text-main);"></i> Sistem Deteksi MySQL
            </h2>

            <!-- Warning 1 -->
            <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 12px; border-left: 4px solid var(--warning);">
                <div style="background: var(--warning-glow); width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 0 10px var(--warning-glow);">
                    <i class="ph-fill ph-warning-circle" style="color: var(--warning); font-size: 1.3rem;"></i>
                </div>
                <div>
                    <h4 style="margin: 0 0 0.2rem 0; font-size: 1rem; color: var(--text-main);">Dana Operasional Kritis!</h4>
                    <p class="text-muted" style="font-size: 0.85rem; margin: 0;">EVT-2026-045: Sisa biaya bersih setelah potongan Profit & Buffer hanya Rp 1.500.000 (Safety Buffer Active).</p>
                    <small style="color: var(--warning); font-weight: 600; margin-top: 0.5rem; display: block;">Tindakan Diperlukan: Kurangi Biaya Bensin</small>
                </div>
            </div>

            <!-- Warning 2 -->
            <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 12px; border-left: 4px solid var(--gold-primary);">
                <div style="background: rgba(212, 175, 55, 0.2); width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="ph-fill ph-t-shirt" style="color: var(--gold-primary); font-size: 1.3rem;"></i>
                </div>
                <div>
                    <h4 style="margin: 0 0 0.2rem 0; font-size: 1rem; color: var(--gold-light);">Kostum Telat (Overdue)</h4>
                    <p class="text-muted" style="font-size: 0.85rem; margin: 0;">Vendor: Rumah Kostum Bandung.</p>
                    <small style="color: var(--gold-primary); font-weight: 600; margin-top: 0.5rem; display: block;">Denda MySQL Bertambah: Rp 50.000/hari</small>
                </div>
            </div>

            <!-- Warning 3 -->
            <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 12px; border-left: 4px solid var(--border-color);">
                <div style="background: rgba(255,255,255,0.05); width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="ph-fill ph-clock-countdown" style="color: var(--text-muted); font-size: 1.3rem;"></i>
                </div>
                <div>
                    <h4 style="margin: 0 0 0.2rem 0; font-size: 1rem; color: var(--text-main);">Latihan Musik Berlangsung</h4>
                    <p class="text-muted" style="font-size: 0.85rem; margin: 0;">Studio B - Ada 1 pemusik belum check-in.</p>
                </div>
            </div>

        </div>

    </div>

@endsection
