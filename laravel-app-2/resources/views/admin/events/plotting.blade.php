@extends('layouts.admin')

@section('title', 'Smart Plotting - EVT-2026-045')
@section('page_title', 'Smart Plotting: EVT-2026-045')
@section('page_subtitle', 'Sistem Deteksi Konflik Otomatis dari MySQL Basis Data 2.')

@section('content')

    <!-- STATUS BAR EVENT & SP OUT -->
    <div class="glass-panel" style="margin-bottom: 2rem; border-color: var(--gold-primary); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph-fill ph-check-circle" style="color: var(--success);"></i> 
                12 Personnel Dibutuhkan (11 Inti + 1 Cadangan)
            </h3>
            <p class="text-muted" style="margin: 0;">Jadwal: Minggu, 12 Apr 2026 | 19:00 - 22:00 WIB</p>
        </div>
        
        <!-- Peringatan dari MySQL Cursor -->
        @if($spData->collision_count > 0)
        <div style="background: var(--danger-glow); border: 1px solid var(--danger); padding: 0.8rem 1.5rem; border-radius: 12px; display: flex; align-items: center; gap: 1rem;">
            <i class="ph-fill ph-warning-octagon" style="color: var(--danger); font-size: 2rem;"></i>
            <div>
                <h4 style="margin: 0; color: #fff;">Peringatan Tabrakan Jawal ({{ $spData->collision_count }} Orang)</h4>
                <p style="margin: 0; font-size: 0.8rem; color: var(--danger);">Ada personel yang belum pulang kerja day-job/latihan.</p>
            </div>
        </div>
        @else
        <div style="background: var(--success-glow); border: 1px solid var(--success); padding: 0.8rem 1.5rem; border-radius: 12px; display: flex; align-items: center; gap: 1rem;">
            <i class="ph-fill ph-check-circle" style="color: var(--success); font-size: 2rem;"></i>
            <div>
                <h4 style="margin: 0; color: #fff;">Clear! Semua Personel Available.</h4>
                <p style="margin: 0; font-size: 0.8rem; color: var(--success);">Silakan assign formasi tanpa khawatir bentrok.</p>
            </div>
        </div>
        @endif
    </div>

    <form action="{{ route('admin.events.plotting.store', 1) }}" method="POST">
        @csrf

        <div class="grid grid-2 animate-fade-up stagger-1">
            
            <!-- PANEL KIRI: LOGIKA ASSIGNMENT -->
            <div class="glass-panel">
                <h3 style="margin-bottom: 1.5rem;">Pilih 6 Penari Inti + 6 Pemusik/Cadangan</h3>

                <!-- Baris Input 1: Penari Utama -->
                <div style="margin-bottom: 1.5rem; padding: 1rem; background: rgba(0,0,0,0.2); border-radius: 12px; border: 1px solid var(--border-color);">
                    <div style="display: flex; gap: 1rem; align-items: flex-end;">
                        <div style="flex: 2;">
                            <label class="text-muted" style="font-size: 0.8rem; display: block; margin-bottom: 0.5rem;">Siti Nurhaliza (Penari)</label>
                            <select name="personnel[0][role_in_event]" class="form-select" style="width: 100%; padding: 0.8rem; border-radius: 8px; background: var(--bg-dark); color: #fff; border: 1px solid var(--border-color);">
                                <option value="penari_utama" selected>Penari Utama</option>
                                <option value="penari_latar">Penari Latar</option>
                            </select>
                        </div>
                        <div style="flex: 2;">
                            <label class="text-muted" style="font-size: 0.8rem; display: block; margin-bottom: 0.5rem;">Standar Fee (Otomatis)</label>
                            <input type="text" value="Rp 500.000" disabled style="width: 100%; padding: 0.8rem; border-radius: 8px; background: var(--bg-dark); color: var(--gold-light); border: 1px solid var(--gold-dark); text-align: right; font-weight: bold;">
                        </div>
                        <div style="flex: 1; text-align: center;">
                            <span class="badge badge-success"><i class="ph ph-check"></i> Available</span>
                        </div>
                    </div>
                    
                    <input type="hidden" name="personnel[0][id]" value="1">
                    <input type="hidden" name="personnel[0][fee_reference_id]" value="1">
                </div>

                <!-- Baris Input 2: Bapak Ujang (Tertabrak Day-Job) -->
                <div class="has-tooltip" data-tooltip="[SISTEM DETEKSI MYSQL]&#xa;Ada event/pekerjaan (PNS Kecamatan) dari jam 08:00 - 16:00.&#xa;Terdapat risiko waktu perjalanan jika acara dimulai jam 19:00!" style="margin-bottom: 1.5rem; padding: 1rem; background: var(--danger-glow); border-radius: 12px; border: 1px solid var(--danger);">
                    <div style="display: flex; gap: 1rem; align-items: flex-end;">
                        <div style="flex: 2;">
                            <label class="text-muted" style="font-size: 0.8rem; display: block; margin-bottom: 0.5rem; color: #fff;">Bpk Ujang (Pemusik)</label>
                            <select name="personnel[1][role_in_event]" class="form-select" disabled style="width: 100%; padding: 0.8rem; border-radius: 8px; background: var(--bg-dark); color: #fff; border: 1px solid var(--border-color); opacity: 0.5;">
                                <option value="pemusik">Pemusik Gamelan</option>
                            </select>
                        </div>
                        <div style="flex: 2;">
                            <label class="text-muted" style="font-size: 0.8rem; display: block; margin-bottom: 0.5rem; color: #fff;">Status Deteksi MySQL</label>
                            <span style="display: block; font-size: 0.85rem; color: #ff8a8a; line-height: 1.2;">
                                <i class="ph ph-suitcase"></i> PNS Kecamatan <br>
                                (Check-Out: 16:00)
                            </span>
                        </div>
                        <div style="flex: 1; text-align: center;">
                            <span class="badge badge-danger" style="font-size: 0.7rem;">BLOCK_ASSIGN</span>
                        </div>
                    </div>
                </div>

                <!-- Baris Input 3: Indra Gunawan (Cadangan) -->
                <div style="margin-bottom: 1.5rem; padding: 1rem; background: rgba(0,0,0,0.2); border-radius: 12px; border: 1px solid var(--border-color);">
                    <div style="display: flex; gap: 1rem; align-items: flex-end;">
                        <div style="flex: 2;">
                            <label class="text-muted" style="font-size: 0.8rem; display: block; margin-bottom: 0.5rem;">Indra Gunawan (Multi-Talent)</label>
                            <select name="personnel[2][role_in_event]" class="form-select" style="width: 100%; padding: 0.8rem; border-radius: 8px; background: var(--bg-dark); color: #fff; border: 1px solid var(--border-color);">
                                <option value="cadangan" selected>Replaces Bpk Ujang (Pemusik)</option>
                                <option value="penari_latar">Replaces Penari Latar</option>
                            </select>
                        </div>
                        <div style="flex: 2;">
                            <label class="text-muted" style="font-size: 0.8rem; display: block; margin-bottom: 0.5rem;">Fee Cadangan Menyesuaikan</label>
                            <input type="text" value="Rp 400.000" disabled style="width: 100%; padding: 0.8rem; border-radius: 8px; background: var(--bg-dark); color: var(--gold-light); border: 1px solid var(--gold-dark); text-align: right; font-weight: bold;">
                        </div>
                        <div style="flex: 1; text-align: center;">
                            <span class="badge badge-success"><i class="ph ph-check"></i> Available</span>
                        </div>
                    </div>
                    
                    <input type="hidden" name="personnel[2][id]" value="12">
                    <input type="hidden" name="personnel[2][fee_reference_id]" value="3">
                </div>

                <div style="margin-top: 2rem;">
                    <button type="button" class="btn btn-outline" style="width: 100%; border: 1px dashed var(--border-color); color: var(--text-muted);">
                        <i class="ph ph-plus"></i> Tambah Field Personel (+9 Sisa Ruang)
                    </button>
                </div>
            </div>

            <!-- PANEL KANAN: PREVIEW CALCULATOR PROFIT SQL -->
            <div class="glass-panel" style="position: sticky; top: 2rem;">
                <h3 style="margin-bottom: 1.5rem;">Estimasi Tagihan Honor (Estimasi SQL)</h3>
                
                <div style="background: var(--bg-dark); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border-color); margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 0.9rem;">
                        <span class="text-muted">Total Alokasi Honor:</span>
                        <span style="font-weight: 600;">Menggunakan "fn_estimate_total_honor"</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; padding-bottom: 1rem; border-bottom: 1px dashed var(--border-color);">
                        <span class="text-muted">Proyeksi Anggaran (12 Kru):</span>
                        <span style="color: var(--gold-light); font-weight: 700;">Rp 4.700.000</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-top: 1rem; font-size: 0.8rem;">
                        <span class="text-muted">Dana Operasional Sisa:</span>
                        <span style="color: var(--success); font-weight: 700;">Aman (Lebih dari Rp 2 Jt)</span>
                    </div>
                </div>

                <div style="background: rgba(212, 175, 55, 0.1); padding: 1.5rem; border-radius: 12px; border-left: 4px solid var(--gold-primary); margin-bottom: 2rem;">
                    <h4 style="color: var(--gold-light); margin-bottom: 0.5rem; font-size: 1rem;"><i class="ph-fill ph-check-circle"></i> Info Keselarasan Database</h4>
                    <p class="text-muted" style="font-size: 0.85rem; margin: 0;">Jika Anda klik Kunci Plotting di bawah, MySQL akan mendedikasikan anggaran Rp 4.7 Juta ini secara otomatis ke Field 'total_personnel_honor' di tabel Keuangan.</p>
                </div>

                <button type="submit" class="btn btn-gold" style="width: 100%; padding: 1.2rem; font-size: 1rem;">
                    <i class="ph ph-floppy-disk"></i> Validasi Sistem & Kunci Plotting
                </button>
            </div>

        </div>
    </form>
    
@endsection
