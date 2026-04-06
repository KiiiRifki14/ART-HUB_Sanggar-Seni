@extends('layouts.admin')

@section('title', 'Dashboard – ART-HUB')
@section('page_title', 'Executive Dashboard')
@section('page_subtitle', 'Ringkasan finansial & penjadwalan Sanggar Cahaya Gumilang.')

@section('content')

{{-- ── STAT CARDS ── --}}
<div class="row g-3 animate-fade-up mb-4">

    {{-- Kunci Laba --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="arh-card-gold p-4 h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="text-secondary small mb-1">Fixed Profit (Bulan Ini)</div>
                    <h3 class="arh-gold fw-bold mb-0 fs-4">Rp 18.500.000</h3>
                </div>
                <div class="arh-stat-icon bg-black bg-opacity-25">
                    <i class="bi bi-safe2-fill arh-gold fs-4"></i>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 mt-2">
                <span class="badge arh-badge-gold fs-xs"><i class="bi bi-lock-fill me-1"></i>Laba Diamankan</span>
                <small class="text-secondary">5 Event Aktif</small>
            </div>
        </div>
    </div>

    {{-- Safety Buffer --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="arh-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="text-secondary small mb-1">Safety Buffer Standby</div>
                    <h3 class="text-success fw-bold mb-0 fs-4">Rp 2.140.000</h3>
                </div>
                <div class="arh-stat-icon" style="background: rgba(25,135,84,0.15);">
                    <i class="bi bi-shield-check-fill text-success fs-4"></i>
                </div>
            </div>
            <span class="badge bg-success bg-opacity-25 text-success small">Siap Menutup Ghosting</span>
        </div>
    </div>

    {{-- Denda Kru --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="arh-card p-4 h-100" style="border-color: rgba(220,53,69,0.4);">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="text-secondary small mb-1">Denda Kru Masuk</div>
                    <h3 class="text-danger fw-bold mb-0 fs-4">Rp 120.000</h3>
                </div>
                <div class="arh-stat-icon" style="background: rgba(220,53,69,0.15);">
                    <i class="bi bi-exclamation-octagon-fill text-danger fs-4"></i>
                </div>
            </div>
            <span class="badge bg-danger bg-opacity-25 text-danger small">2 Insiden Terlambat</span>
        </div>
    </div>

    {{-- Event Bulan Ini --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="arh-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="text-secondary small mb-1">Event Bulan Ini</div>
                    <h3 class="text-white fw-bold mb-0 fs-4">7 Event</h3>
                </div>
                <div class="arh-stat-icon" style="background: rgba(13,110,253,0.15);">
                    <i class="bi bi-calendar-event-fill text-primary fs-4"></i>
                </div>
            </div>
            <span class="badge bg-primary bg-opacity-25 text-primary small">2 Butuh Plotting</span>
        </div>
    </div>
</div>

{{-- ── GRID UTAMA ── --}}
<div class="row g-4 animate-fade-up">

    {{-- KOLOM KIRI: Event Radar --}}
    <div class="col-12 col-lg-7">
        <div class="arh-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-radar arh-gold"></i> Smart Event Radar
                </h5>
                <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary btn-sm">Lihat Semua</a>
            </div>

            {{-- Event Card 1 --}}
            <div class="p-3 rounded-3 mb-3" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="d-flex gap-2 align-items-center mb-2">
                            <span class="badge arh-badge-gold">EVT-2026-045</span>
                            <span class="badge bg-success">READY</span>
                        </div>
                        <h6 class="fw-semibold mb-1">Pernikahan Klien A (Jaipong)</h6>
                        <small class="text-secondary"><i class="bi bi-geo-alt-fill me-1"></i>Gedung Serbaguna Karawaci</small>
                    </div>
                    <div class="text-end">
                        <div class="arh-gold fw-bold">Min, 12 Apr</div>
                        <small class="text-secondary">19:00 – 22:00</small>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-secondary">
                    <div class="d-flex">
                        @foreach(['SN','DA','RH','+9'] as $i => $ava)
                        @php $ml = $i > 0 ? '-8px' : '0'; $zi = 4 - $i; @endphp
                        <div class="arh-avatar-sm border border-dark" @style([
                            "margin-left: {$ml}", 
                            "z-index: {$zi}", 
                            "font-size: 0.65rem"
                        ])>{{ $ava }}</div>
                        @endforeach
                    </div>
                    <a href="{{ url('admin/events/1/plotting') }}" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-diagram-3 me-1"></i>Cek Plotting
                    </a>
                </div>
            </div>

            {{-- Event Card 2 --}}
            <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="d-flex gap-2 align-items-center mb-2">
                            <span class="badge bg-secondary">EVT-2026-046</span>
                            <span class="badge bg-secondary">PLANNING</span>
                        </div>
                        <h6 class="fw-semibold mb-1">Gathering Kantor (Degung)</h6>
                        <small class="text-secondary"><i class="bi bi-geo-alt-fill me-1"></i>Hotel Aston BSD</small>
                    </div>
                    <div class="text-end">
                        <div class="arh-gold fw-bold">Kam, 16 Apr</div>
                        <small class="text-secondary">10:00 – 13:00</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: Sistem Alert --}}
    <div class="col-12 col-lg-5">
        <div class="arh-card p-4 h-100">
            <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-bell-fill text-warning"></i> Sistem Alert MySQL
            </h5>

            {{-- Alert 1 --}}
            <div class="d-flex gap-3 mb-3 p-3 rounded-3 border-start border-warning border-3" style="background: rgba(255,193,7,0.06);">
                <i class="bi bi-exclamation-circle-fill text-warning fs-4 flex-shrink-0 mt-1"></i>
                <div>
                    <div class="fw-semibold small">Dana Operasional Kritis!</div>
                    <div class="text-secondary small">EVT-2026-045: Sisa hanya Rp 1.500.000 setelah potongan Profit & Buffer.</div>
                    <div class="text-warning small fw-bold mt-1">⚡ Kurangi Biaya Bensin</div>
                </div>
            </div>

            {{-- Alert 2 --}}
            <div class="d-flex gap-3 mb-3 p-3 rounded-3 border-start border-3" style="border-color: var(--arh-gold) !important; background: rgba(212,175,55,0.06);">
                <i class="bi bi-bag-x-fill fs-4 flex-shrink-0 mt-1" style="color: var(--arh-gold);"></i>
                <div>
                    <div class="fw-semibold small arh-gold">Kostum Telat (Overdue)</div>
                    <div class="text-secondary small">Vendor: Rumah Kostum Bandung.</div>
                    <div class="small fw-bold mt-1" style="color: var(--arh-gold);">Denda MySQL +Rp 50.000/hari</div>
                </div>
            </div>

            {{-- Alert 3 --}}
            <div class="d-flex gap-3 p-3 rounded-3 border-start border-secondary border-3" style="background: rgba(255,255,255,0.03);">
                <i class="bi bi-clock-history text-secondary fs-4 flex-shrink-0 mt-1"></i>
                <div>
                    <div class="fw-semibold small">Latihan Musik Berlangsung</div>
                    <div class="text-secondary small">Studio B – 1 pemusik belum check-in.</div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.events.index') }}" class="btn btn-arh-gold w-100">
                    <i class="bi bi-arrow-right-circle me-2"></i>Buka Event Management
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
