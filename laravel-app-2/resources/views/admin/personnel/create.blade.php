@extends('layouts.admin')

@section('title', 'Tambah Personel – ART-HUB')
@section('page_title', 'Tambah Personel Baru')
@section('page_subtitle', 'Daftarkan kru baru ke dalam sistem sanggar.')

@section('content')
<div class="row justify-content-center animate-fade-up">
    <div class="col-12 col-lg-8">
        <div class="arh-card p-4">
            <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-person-plus-fill arh-gold"></i> Form Pendaftaran Personel
            </h5>

            <form method="POST" action="{{ route('admin.personnel.store') }}">
                @csrf

                {{-- INFORMASI DASAR --}}
                <h6 class="text-secondary text-uppercase small fw-bold mb-3 mt-2">
                    <i class="bi bi-person-badge me-1"></i> Informasi Dasar
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="Contoh: Siti Nurhaliza" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email (untuk Login) <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="siti@arth-hub.id" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Spesialisasi <span class="text-danger">*</span></label>
                        <select name="specialty" class="form-select @error('specialty') is-invalid @enderror" required>
                            <option value="">— Pilih Spesialisasi —</option>
                            <option value="penari" {{ old('specialty') === 'penari' ? 'selected' : '' }}>Penari (Dancer)</option>
                            <option value="pemusik" {{ old('specialty') === 'pemusik' ? 'selected' : '' }}>Pemusik (Musician)</option>
                            <option value="multi_talent" {{ old('specialty') === 'multi_talent' ? 'selected' : '' }}>Multi-Talent / Crew</option>
                        </select>
                        @error('specialty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor HP</label>
                        <div class="input-group">
                            <span class="input-group-text">+62</span>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone') }}" placeholder="81xxxxxxxxx">
                        </div>
                    </div>
                </div>

                {{-- STATUS --}}
                <h6 class="text-secondary text-uppercase small fw-bold mb-3">
                    <i class="bi bi-toggles me-1"></i> Status Keanggotaan
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_backup"
                                   id="is_backup" value="1" {{ old('is_backup') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_backup">Personel Cadangan?</label>
                        </div>
                        <small class="text-secondary">Tandai jika ini adalah pemain pengganti, bukan inti.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password Default</label>
                        <input type="text" class="form-control bg-dark text-secondary" value="sanggar123" disabled>
                        <small class="text-secondary">Personel bisa ganti setelah login pertama.</small>
                    </div>
                </div>

                {{-- DAY JOB --}}
                <h6 class="text-secondary text-uppercase small fw-bold mb-3">
                    <i class="bi bi-briefcase me-1"></i> Pekerjaan Utama (Day-Job)
                </h6>
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="has_day_job"
                               id="has_day_job" value="1" {{ old('has_day_job') ? 'checked' : '' }}
                               onchange="toggleDayJob(this)">
                        <label class="form-check-label" for="has_day_job">
                            Personel ini punya pekerjaan utama di luar sanggar (PNS, Guru, dll)?
                        </label>
                    </div>
                </div>
                <div id="day-job-section" class="row g-3 mb-4 {{ old('has_day_job') ? '' : 'd-none' }}">
                    <div class="col-12">
                        <label class="form-label">Nama Instansi / Pekerjaan</label>
                        <input type="text" name="day_job_name" class="form-control"
                               value="{{ old('day_job_name') }}" placeholder="Contoh: PNS Kecamatan Subang">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam Masuk Kerja</label>
                        <input type="time" name="day_job_start" class="form-control" value="{{ old('day_job_start', '07:00') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam Pulang Kerja</label>
                        <input type="time" name="day_job_end" class="form-control" value="{{ old('day_job_end', '16:00') }}">
                    </div>
                    <div class="col-12">
                        <div class="alert alert-warning border-0 d-flex gap-2 align-items-start py-2">
                            <i class="bi bi-exclamation-triangle-fill mt-1 flex-shrink-0"></i>
                            <small>Smart Plotting akan otomatis mendeteksi konflik jadwal berdasarkan jam ini.</small>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="d-flex gap-2 justify-content-end pt-3 border-top border-secondary">
                    <a href="{{ route('admin.personnel.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-arh-gold">
                        <i class="bi bi-person-plus-fill me-1"></i>Daftarkan Personel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleDayJob(checkbox) {
    const section = document.getElementById('day-job-section');
    section.classList.toggle('d-none', !checkbox.checked);
}
</script>
@endsection
