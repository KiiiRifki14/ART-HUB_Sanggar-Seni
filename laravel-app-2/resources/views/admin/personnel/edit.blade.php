@extends('layouts.admin')
@section('title', 'Edit Personel – ART-HUB')
@section('page_title', 'Edit Data Personel')
@section('page_subtitle', 'Perbarui informasi kru: ' . ($personnel->user->name ?? '-'))

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ hasDayJob: {{ old('has_day_job', $personnel->has_day_job) ? 'true' : 'false' }} }">
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] p-6 md:p-8">
        
        <div class="flex items-center gap-3 mb-8 pb-4 border-b border-outline-variant/20">
            <div class="w-12 h-12 rounded-xl bg-surface-container border border-outline-variant/30 flex items-center justify-center text-on-surface flex-shrink-0">
                <i class="bi bi-pencil-square text-2xl"></i>
            </div>
            <div>
                <h2 class="font-headline text-xl text-primary font-bold">Edit Personel</h2>
                <p class="font-body text-xs text-on-surface-variant">Ubah informasi dasar, status aktif, atau data pekerjaan utama.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.personnel.update', $personnel->id) }}" class="space-y-8">
            @csrf @method('PUT')

            {{-- INFORMASI DASAR --}}
            <section>
                <h3 class="font-headline text-sm text-primary font-bold uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="bi bi-person-vcard text-secondary"></i> Informasi Dasar
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline block mb-1.5 ml-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full bg-surface-container-low border {{ $errors->has('name') ? 'border-red-500' : 'border-outline-variant/50' }} rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" value="{{ old('name', $personnel->user->name) }}" required>
                        @error('name')<p class="text-red-500 font-body text-xs mt-1 ml-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline block mb-1.5 ml-1">Email</label>
                        <input type="email" class="w-full bg-surface-container border border-outline-variant/20 rounded-xl px-4 py-3 font-body text-sm text-outline cursor-not-allowed" value="{{ $personnel->user->email }}" disabled>
                        <p class="font-body text-xs text-outline mt-1 ml-1">Email tidak dapat diubah setelah terdaftar.</p>
                    </div>
                    
                    <div>
                        <label class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline block mb-1.5 ml-1">Spesialisasi Utama <span class="text-red-500">*</span></label>
                        <select name="specialty" class="w-full bg-surface-container-low border {{ $errors->has('specialty') ? 'border-red-500' : 'border-outline-variant/50' }} rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors appearance-none" required>
                            <option value="penari" {{ old('specialty', $personnel->specialty) === 'penari' ? 'selected' : '' }}>Penari (Dancer)</option>
                            <option value="pemusik" {{ old('specialty', $personnel->specialty) === 'pemusik' ? 'selected' : '' }}>Pemusik (Musician)</option>
                            <option value="multi_talent" {{ old('specialty', $personnel->specialty) === 'multi_talent' ? 'selected' : '' }}>Multi-Talent / Crew</option>
                        </select>
                        @error('specialty')<p class="text-red-500 font-body text-xs mt-1 ml-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline block mb-1.5 ml-1">Nomor HP</label>
                        <input type="text" name="phone" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" value="{{ old('phone', $personnel->user->phone ?? '') }}" placeholder="081xxxxxxxxx">
                    </div>
                </div>
            </section>

            {{-- STATUS KEANGGOTAAN --}}
            <section>
                <h3 class="font-headline text-sm text-primary font-bold uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="bi bi-toggles text-secondary"></i> Status Keanggotaan
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-surface-container-low border border-outline-variant/50 rounded-xl p-4 flex gap-4">
                        <div class="flex items-center h-5 mt-1">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $personnel->is_active) ? 'checked' : '' }} class="w-4 h-4 text-green-600 bg-surface-container border-outline-variant rounded focus:ring-green-600 focus:ring-2">
                        </div>
                        <div>
                            <label for="is_active" class="font-body text-sm font-bold text-on-surface block cursor-pointer">Status Personel Aktif?</label>
                            <p class="font-body text-xs text-outline mt-1">Non-aktifkan sementara (cuti/vakum) tanpa menghapus data.</p>
                        </div>
                    </div>
                    
                    <div class="bg-surface-container-low border border-outline-variant/50 rounded-xl p-4 flex gap-4">
                        <div class="flex items-center h-5 mt-1">
                            <input type="checkbox" name="is_backup" id="is_backup" value="1" {{ old('is_backup', $personnel->is_backup) ? 'checked' : '' }} class="w-4 h-4 text-primary bg-surface-container border-outline-variant rounded focus:ring-primary focus:ring-2">
                        </div>
                        <div>
                            <label for="is_backup" class="font-body text-sm font-bold text-on-surface block cursor-pointer">Personel Cadangan?</label>
                            <p class="font-body text-xs text-outline mt-1">Tandai jika ini adalah pemain pengganti, bukan formasi inti.</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- DAY JOB --}}
            <section>
                <h3 class="font-headline text-sm text-primary font-bold uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="bi bi-briefcase-fill text-secondary"></i> Pekerjaan Utama (Day-Job)
                </h3>
                
                <div class="bg-surface-container-low border border-outline-variant/50 rounded-xl p-5">
                    <div class="flex gap-4 mb-4">
                        <div class="flex items-center h-5 mt-0.5">
                            <input type="checkbox" name="has_day_job" id="has_day_job" value="1" x-model="hasDayJob" class="w-4 h-4 text-primary bg-surface-container border-outline-variant rounded focus:ring-primary focus:ring-2">
                        </div>
                        <div>
                            <label for="has_day_job" class="font-body text-sm font-bold text-on-surface block cursor-pointer">Personel memiliki pekerjaan tetap di luar sanggar?</label>
                        </div>
                    </div>
                    
                    <div x-show="hasDayJob" x-collapse>
                        <div class="pt-4 border-t border-outline-variant/30 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline block mb-1.5 ml-1">Nama Instansi / Pekerjaan</label>
                                <input type="text" name="day_job_name" class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" value="{{ old('day_job_name', $personnel->day_job_desc) }}">
                            </div>
                            
                            <div>
                                <label class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline block mb-1.5 ml-1">Jam Masuk (WIB)</label>
                                <input type="time" name="day_job_start" class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors appearance-none" value="{{ old('day_job_start', $personnel->day_job_start ? \Carbon\Carbon::parse($personnel->day_job_start)->format('H:i') : '07:00') }}">
                            </div>
                            
                            <div>
                                <label class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline block mb-1.5 ml-1">Jam Pulang (WIB)</label>
                                <input type="time" name="day_job_end" class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors appearance-none" value="{{ old('day_job_end', $personnel->day_job_end ? \Carbon\Carbon::parse($personnel->day_job_end)->format('H:i') : '16:00') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- TOMBOL AKSI --}}
            <div class="pt-6 border-t border-outline-variant/20 flex items-center justify-end gap-3">
                <a href="{{ route('admin.personnel.index') }}" class="px-5 py-2.5 rounded-xl border border-outline-variant/50 font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-colors">
                    <i class="bi bi-arrow-left me-1"></i> Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-md hover:shadow-lg flex items-center gap-2">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>
            
        </form>
    </div>
</div>
@endsection
