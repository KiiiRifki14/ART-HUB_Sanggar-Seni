@extends('layouts.admin')

@section('title', 'New Booking – ART-HUB')
@section('page_title', 'New Booking Entry')
@section('page_subtitle', 'Input booking manual dari klien offline / WhatsApp.')

@section('content')
<div class="row justify-content-center animate-fade-up">
    <div class="col-12 col-lg-8">
        <div class="arh-card p-4">
            <h5 class="fw-bold mb-4 d-flex align-items-center gap-2 arh-gold">
                <i class="bi bi-file-earmark-plus-fill"></i> Form Booking Manual
            </h5>

            <form method="POST" action="{{ route('admin.bookings.manual.store') }}">
                @csrf

                <h6 class="text-secondary small text-uppercase fw-bold mb-3"><i class="bi bi-person-circle me-1"></i> Data Klien</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nama Klien <span class="text-danger">*</span></label>
                        <input type="text" name="client_name" value="{{ old('client_name') }}" required 
                               class="form-control @error('client_name') is-invalid @enderror" placeholder="Bpk./Ibu Siapa">
                        @error('client_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. Telepon / WA <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-secondary border-secondary">+62</span>
                            <input type="text" name="client_phone" value="{{ old('client_phone') }}" required 
                                   class="form-control @error('client_phone') is-invalid @enderror" placeholder="81xxxxxxxxx">
                            @error('client_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <h6 class="text-secondary small text-uppercase fw-bold mb-3"><i class="bi bi-calendar-event me-1"></i> Detail Event</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Jenis Event <span class="text-danger">*</span></label>
                        <select name="event_type" required class="form-select @error('event_type') is-invalid @enderror">
                            <option value="">— Pilih Jenis —</option>
                            @foreach(['jaipong'=>'Jaipong','degung'=>'Degung','rampak_gendang'=>'Rampak Gendang','wayang_golek'=>'Wayang Golek','campuran'=>'Campuran'] as $k => $v)
                                <option value="{{ $k }}" {{ old('event_type') === $k ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                        @error('event_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                        <input type="date" name="event_date" value="{{ old('event_date') }}" required 
                               class="form-control @error('event_date') is-invalid @enderror">
                        @error('event_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" name="event_start" value="{{ old('event_start') }}" required 
                               class="form-control @error('event_start') is-invalid @enderror">
                        @error('event_start') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" name="event_end" value="{{ old('event_end') }}" required 
                               class="form-control @error('event_end') is-invalid @enderror">
                        @error('event_end') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Venue / Lokasi Acara <span class="text-danger">*</span></label>
                        <input type="text" name="venue" value="{{ old('venue') }}" required 
                               class="form-control @error('venue') is-invalid @enderror" placeholder="Gedung / Rumah / Alamat Lengkap">
                        @error('venue') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <h6 class="text-secondary small text-uppercase fw-bold mb-3"><i class="bi bi-wallet2 me-1"></i> Nilai Kontrak</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Total Harga Deal (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="total_price" value="{{ old('total_price') }}" required 
                               class="form-control @error('total_price') is-invalid @enderror" placeholder="Contoh: 15000000">
                        @error('total_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jumlah DP Masuk (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="dp_amount" value="{{ old('dp_amount') }}" required 
                               class="form-control @error('dp_amount') is-invalid @enderror" placeholder="Contoh: 7500000">
                        @error('dp_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex gap-2 pt-3 border-top border-secondary justify-content-end">
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-arh-gold">
                        <i class="bi bi-save me-1"></i>Simpan Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
