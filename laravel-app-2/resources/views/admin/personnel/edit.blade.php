@extends('layouts.admin')

@section('title', 'Edit Personel – ART-HUB')
@section('page_title', 'Edit Data Personel')
@section('page_subtitle', 'Perbarui informasi kru: ' . ($personnel->user->name ?? '-'))

@section('content')
<div class="row justify-content-center animate-fade-up">
    <div class="col-12 col-lg-8">
        <div class="arh-card p-4">
            <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square arh-gold"></i> Edit: {{ $personnel->user->name ?? 'Personel' }}
            </h5>

            <form method="POST" action="{{ route('admin.personnel.update', $personnel->id) }}">
                @csrf @method('PUT')

                {{-- INFORMASI DASAR --}}
                <h6 class="text-secondary text-uppercase small fw-bold mb-3">
                    <i class="bi bi-person-badge me-1"></i> Informasi Dasar
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $personnel->user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control bg-dark text-secondary"
                               value="{{ $personnel->user->email }}" disabled>
                        <small class="text-secondary">Email tidak bisa diubah di sini.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Spesialisasi <span class="text-danger">*</span></label>
                        <select name="specialty" class="form-select @error('specialty') is-invalid @enderror" required>
                            @foreach(['Tari Sunda','Tari Jawa','Tari Bali','Pemusik Gamelan','Pemusik Kecapi','Pemusik Kendang','MC / Pembawa Acara','Multi-Talent','Logistik & Tata Panggung'] as $s)
                            <option value="{{ $s }}" {{ old('specialty', $personnel->specialty) === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                        @error('specialty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone', $personnel->phone) }}">
                    </div>
                </div>

                {{-- STATUS --}}
                <h6 class="text-secondary text-uppercase small fw-bold mb-3">
                    <i class="bi bi-toggles me-1"></i> Status Keanggotaan
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   id="is_active" value="1"
                                   {{ old('is_active', $personnel->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Status Aktif</label>
                        </div>
                        <small class="text-secondary">Non-aktifkan sementara tanpa hapus data.</small>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_backup"
                                   id="is_backup" value="1"
                                   {{ old('is_backup', $personnel->is_backup) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_backup">Personel Cadangan</label>
                        </div>
                    </div>
                </div>

                {{-- DAY JOB --}}
                <h6 class="text-secondary text-uppercase small fw-bold mb-3">
                    <i class="bi bi-briefcase me-1"></i> Pekerjaan Utama (Day-Job)
                </h6>
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="has_day_job"
                               id="has_day_job" value="1"
                               {{ old('has_day_job', $personnel->has_day_job) ? 'checked' : '' }}
                               onchange="toggleDayJob(this)">
                        <label class="form-check-label" for="has_day_job">Punya pekerjaan utama di luar sanggar?</label>
                    </div>
                </div>
                <div id="day-job-section" class="row g-3 mb-4 {{ old('has_day_job', $personnel->has_day_job) ? '' : 'd-none' }}">
                    <div class="col-12">
                        <label class="form-label">Nama Instansi / Pekerjaan</label>
                        <input type="text" name="day_job_name" class="form-control"
                               value="{{ old('day_job_name', $personnel->day_job_name) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam Masuk</label>
                        <input type="time" name="day_job_start" class="form-control"
                               value="{{ old('day_job_start', $personnel->day_job_start ? \Carbon\Carbon::parse($personnel->day_job_start)->format('H:i') : '07:00') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam Pulang</label>
                        <input type="time" name="day_job_end" class="form-control"
                               value="{{ old('day_job_end', $personnel->day_job_end ? \Carbon\Carbon::parse($personnel->day_job_end)->format('H:i') : '16:00') }}">
                    </div>
                </div>

                {{-- AKSI --}}
                <div class="d-flex gap-2 justify-content-end pt-3 border-top border-secondary">
                    <a href="{{ route('admin.personnel.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-arh-gold">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
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
    document.getElementById('day-job-section').classList.toggle('d-none', !checkbox.checked);
}
</script>
@endsection
