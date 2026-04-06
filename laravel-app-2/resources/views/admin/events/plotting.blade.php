@extends('layouts.admin')

@section('title', 'Smart Plotting — ' . ($event->event_code ?? 'Event') . ' | ART-HUB')
@section('page_title', 'Smart Plotting: ' . ($event->event_code ?? 'Event'))
@section('page_subtitle', 'Assign formasi personel dengan deteksi konflik otomatis dari SQL Stored Procedure.')

@section('content')

{{-- ── STATUS BAR: INFO EVENT + RESULT SP ── --}}
<div class="arh-card-gold p-4 mb-4 animate-fade-up">
    <div class="row align-items-center g-3">
        <div class="col-12 col-md-6">
            <h5 class="fw-bold mb-2 d-flex align-items-center gap-2 arh-gold">
                <i class="bi bi-people-fill"></i> Formasi {{ $event->personnel_count ?? 12 }} Personel
            </h5>
            <div class="text-secondary small">
                <i class="bi bi-calendar-event me-1"></i> {{ $event->event_date->format('l, d M Y') }} &nbsp;|&nbsp;
                <i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($event->event_start)->format('H:i') }} – {{ \Carbon\Carbon::parse($event->event_end)->format('H:i') }} WIB &nbsp;|&nbsp;
                <i class="bi bi-geo-alt me-1"></i> {{ $event->venue }}
            </div>
        </div>

        {{-- Hasil Stored Procedure (jika tersedia) --}}
        <div class="col-12 col-md-6">
            @if(isset($spData) && $spData)
                @if($spData->collision_count > 0)
                <div class="p-3 rounded-3 border border-danger d-flex align-items-center gap-3" style="background: rgba(220,53,69,0.15);">
                    <i class="bi bi-exclamation-octagon-fill text-danger fs-1"></i>
                    <div>
                        <h6 class="fw-bold mb-1 text-danger">{{ $spData->collision_count }} Personel Konflik Jadwal</h6>
                        <small class="text-secondary">Ada yang sedang di pekerjaan utama/latihan.</small>
                    </div>
                </div>
                @else
                <div class="p-3 rounded-3 border border-success d-flex align-items-center gap-3" style="background: rgba(25,135,84,0.15);">
                    <i class="bi bi-check-circle-fill text-success fs-1"></i>
                    <div>
                        <h6 class="fw-bold mb-1 text-success">Semua Personel Tersedia!</h6>
                        <small class="text-secondary">Tidak ada konflik jadwal. Silakan assign formasi.</small>
                    </div>
                </div>
                @endif
            @else
            <div class="p-3 rounded-3 border d-flex align-items-center gap-3 bg-black bg-opacity-25" style="border-color: var(--arh-gold);">
                <i class="bi bi-database-fill-check fs-1 arh-gold"></i>
                <div>
                    <h6 class="fw-bold mb-1 col-gold">Deteksi Konflik SQL Siap</h6>
                    <small class="text-secondary">Klik "Validasi & Kunci Plotting" untuk menjalankan Stored Procedure.</small>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ── MAIN GRID: FORM PLOTTING + PREVIEW KALKULASI ── --}}
<form action="{{ route('admin.events.plotting.store', $event->id) }}" method="POST" id="plotting-form">
    @csrf

    <div class="row g-4 animate-fade-up">

        {{-- ── PANEL KIRI: TABEL ASSIGNMENT PERSONEL ── --}}
        <div class="col-12 col-xl-8">
            <div class="arh-card p-4 h-100">
                <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                    <i class="bi bi-person-lines-fill arh-gold"></i> Pilih & Assign Formasi Personel
                </h5>

                <div class="table-responsive">
                    <table class="table arh-table table-hover align-middle mb-0" id="plotting-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Personel</th>
                                <th>Spesialisasi</th>
                                <th>Role di Event</th>
                                <th>Fee</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($personnel as $idx => $p)
                            @php
                                $pivotData = $event->personnel->firstWhere('id', $p->id)?->pivot;
                                $alreadyPlotted = !is_null($pivotData);
                                $collidingIds = [];
                                if (isset($spData) && $spData && !empty($spData->collision_details)) {
                                    preg_match_all('/ID:(\d+)/', $spData->collision_details, $m);
                                    $collidingIds = $m[1] ?? [];
                                }
                                $isColliding = in_array((string)$p->id, $collidingIds);
                            @endphp
                            <tr class="{{ $isColliding ? 'bg-danger bg-opacity-10 border-danger' : '' }}">
                                <td>
                                    <input class="form-check-input border-secondary" type="checkbox" name="personnel[{{ $idx }}][selected]"
                                           value="1" id="chk-{{ $p->id }}"
                                           {{ $alreadyPlotted ? 'checked' : '' }}
                                           {{ $isColliding ? 'disabled' : '' }}
                                           onchange="updatePreview()">
                                    <input type="hidden" name="personnel[{{ $idx }}][id]" value="{{ $p->id }}">
                                    <input type="hidden" name="personnel[{{ $idx }}][fee_reference_id]" value="{{ $fees->first()?->id ?? 1 }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="arh-avatar-sm">{{ strtoupper(substr($p->user->name ?? 'P', 0, 2)) }}</div>
                                        <div>
                                            <div class="fw-semibold">{{ $p->user->name ?? 'Personel' }}</div>
                                            @if($p->day_job_name)
                                            <small class="text-warning"><i class="bi bi-briefcase-fill me-1"></i>{{ $p->day_job_name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-secondary">{{ $p->specialty }}</span></td>
                                <td>
                                    <select class="form-select form-select-sm" name="personnel[{{ $idx }}][role_in_event]" {{ $isColliding ? 'disabled' : '' }}>
                                        <option value="penari_utama" {{ str_contains($p->specialty ?? '', 'Tari') ? 'selected' : '' }}>Penari Utama</option>
                                        <option value="penari_latar">Penari Latar</option>
                                        <option value="pemusik" {{ str_contains($p->specialty ?? '', 'Musik') ? 'selected' : '' }}>Pemusik</option>
                                        <option value="cadangan">Cadangan</option>
                                        <option value="MC">MC / Pembawa Acara</option>
                                    </select>
                                </td>
                                <td>
                                    <span class="fw-bold arh-gold">Rp {{ number_format($fees->first()?->base_fee ?? 500000, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    @if($isColliding)
                                        <span class="badge bg-danger">KONFLIK</span>
                                    @elseif($alreadyPlotted)
                                        <span class="badge arh-badge-gold">ASSIGNED</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-25 text-success">AVAILABLE</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── PANEL KANAN: PREVIEW HONOR + SUBMIT ── --}}
        <div class="col-12 col-xl-4">
            {{-- Summary Card --}}
            <div class="arh-card-gold p-4 mb-4">
                <h5 class="fw-bold mb-4 d-flex align-items-center gap-2 arh-gold">
                    <i class="bi bi-calculator"></i> Estimasi Anggaran Honor
                </h5>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-secondary">Personel Dipilih</span>
                    <span class="fw-bold fs-4" id="preview-count">{{ $event->personnel->count() }}</span>
                </div>
                <hr class="border-secondary border-dashed">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="text-secondary">Estimasi Total</span>
                    <span class="fw-bold fs-4 arh-gold" id="preview-total">
                        Rp {{ number_format($event->estimated_total_honor > 0 ? $event->estimated_total_honor : ($fees->first()?->base_fee ?? 500000) * $event->personnel->count(), 0, ',', '.') }}
                    </span>
                </div>

                @if($event->financialRecord)
                <div class="bg-black bg-opacity-25 p-3 rounded-3 text-sm">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Budget Operasional</span>
                        <span class="fw-semibold">Rp {{ number_format($event->financialRecord->operational_budget, 0, ',', '.') }}</span>
                    </div>
                    @php $sisa = $event->financialRecord->operational_budget - $event->estimated_total_honor; @endphp
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Sisa Budget Ops</span>
                        <span class="fw-bold {{ $sisa >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $sisa >= 0 ? '+' : '-' }}Rp {{ number_format(abs($sisa), 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                @endif
            </div>

            {{-- Info SQL Function --}}
            <div class="arh-card border-start border-3 p-3 mb-4" style="border-color: var(--arh-gold) !important; background: rgba(255,255,255,0.03);">
                <div class="fw-bold mb-2 arh-gold"><i class="bi bi-database-fill me-1"></i> Mekanisme SQL</div>
                <small class="text-secondary d-block lh-base">
                    Saat "Kunci Plotting" diklik, sistem memanggil:<br>
                    <code class="text-info bg-dark px-1 rounded">CALL sp_check_personnel_availability()</code><br>
                    Jika lolos, estimasi honor otomatis dihitung SQL.
                </small>
            </div>

            {{-- Tombol Submit --}}
            <button type="submit" class="btn btn-arh-gold w-100 py-3 fw-bold mb-2">
                <i class="bi bi-lock-fill me-1"></i> Validasi SQL & Kunci Plotting
            </button>
            <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Event
            </a>
        </div>

    </div>
</form>

@endsection

@section('scripts')
<script>
    const baseFee = Number("{{ $fees->first()?->base_fee ?? 500000 }}");
    function updatePreview() {
        const checked = document.querySelectorAll('#plotting-table input[type="checkbox"]:checked:not(:disabled)');
        const count = checked.length;
        const total = count * baseFee;
        document.getElementById('preview-count').textContent = count;
        document.getElementById('preview-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>
@endsection
