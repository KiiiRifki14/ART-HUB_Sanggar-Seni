@extends('layouts.admin')

@section('title', 'New Booking Entry - ART-HUB')
@section('page_title', 'New Booking Entry')
@section('page_subtitle', 'Input booking manual dari klien tanpa akun.')

@section('content')
<div class="glass-panel animate-fade-up" style="max-width: 800px;">
    <h2 style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.8rem;">
        <i class="ph ph-plus-circle" style="color: var(--gold-primary);"></i> Form Booking Manual
    </h2>

    <form method="POST" action="{{ route('admin.bookings.manual.store') }}">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <!-- Nama Klien -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.85rem; color: var(--gold-light);">Nama Klien *</label>
                <input type="text" name="client_name" value="{{ old('client_name') }}" required placeholder="Bpk./Ibu Siapa" style="width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main); font-size: 0.95rem; transition: 0.3s;" onfocus="this.style.borderColor='var(--gold-primary)'" onblur="this.style.borderColor='var(--border-color)'">
                @error('client_name') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>

            <!-- Telepon -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.85rem; color: var(--gold-light);">No. Telepon *</label>
                <input type="text" name="client_phone" value="{{ old('client_phone') }}" required placeholder="08xxxxxxxxxx" style="width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main); font-size: 0.95rem; transition: 0.3s;" onfocus="this.style.borderColor='var(--gold-primary)'" onblur="this.style.borderColor='var(--border-color)'">
                @error('client_phone') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>

            <!-- Jenis Event -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.85rem; color: var(--gold-light);">Jenis Event *</label>
                <select name="event_type" required style="width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main); font-size: 0.95rem;">
                    <option value="">— Pilih Jenis —</option>
                    <option value="jaipong" {{ old('event_type') === 'jaipong' ? 'selected' : '' }}>Jaipong</option>
                    <option value="degung" {{ old('event_type') === 'degung' ? 'selected' : '' }}>Degung</option>
                    <option value="rampak_gendang" {{ old('event_type') === 'rampak_gendang' ? 'selected' : '' }}>Rampak Gendang</option>
                    <option value="wayang_golek" {{ old('event_type') === 'wayang_golek' ? 'selected' : '' }}>Wayang Golek</option>
                    <option value="campuran" {{ old('event_type') === 'campuran' ? 'selected' : '' }}>Campuran</option>
                </select>
                @error('event_type') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>

            <!-- Tanggal Event -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.85rem; color: var(--gold-light);">Tanggal Event *</label>
                <input type="date" name="event_date" value="{{ old('event_date') }}" required style="width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main); font-size: 0.95rem; transition: 0.3s;" onfocus="this.style.borderColor='var(--gold-primary)'" onblur="this.style.borderColor='var(--border-color)'">
                @error('event_date') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>

            <!-- Jam Mulai -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.85rem; color: var(--gold-light);">Jam Mulai *</label>
                <input type="time" name="event_start" value="{{ old('event_start') }}" required style="width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main); font-size: 0.95rem;">
                @error('event_start') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>

            <!-- Jam Selesai -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.85rem; color: var(--gold-light);">Jam Selesai *</label>
                <input type="time" name="event_end" value="{{ old('event_end') }}" required style="width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main); font-size: 0.95rem;">
                @error('event_end') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>

            <!-- Venue -->
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.85rem; color: var(--gold-light);">Venue / Lokasi *</label>
                <input type="text" name="venue" value="{{ old('venue') }}" required placeholder="Gedung / Rumah / Hotel" style="width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main); font-size: 0.95rem; transition: 0.3s;" onfocus="this.style.borderColor='var(--gold-primary)'" onblur="this.style.borderColor='var(--border-color)'">
                @error('venue') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>

            <!-- Total Harga -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.85rem; color: var(--gold-light);">Total Harga (Rp) *</label>
                <input type="number" name="total_price" value="{{ old('total_price') }}" required placeholder="15000000" style="width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main); font-size: 0.95rem;">
                @error('total_price') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>

            <!-- DP -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.85rem; color: var(--gold-light);">Jumlah DP (Rp) *</label>
                <input type="number" name="dp_amount" value="{{ old('dp_amount') }}" required placeholder="7500000" style="width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main); font-size: 0.95rem;">
                @error('dp_amount') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-gold"><i class="ph ph-floppy-disk"></i> Simpan Booking</button>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline"><i class="ph ph-arrow-left"></i> Batal</a>
        </div>
    </form>
</div>
@endsection
