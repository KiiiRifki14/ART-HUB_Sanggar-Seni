@extends('layouts.admin')
@section('title', 'Tambah Personel – ART-HUB')
@section('page_title', 'Tambah Personel Baru')
@section('page_subtitle', 'Daftarkan kru baru ke dalam sistem sanggar.')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ hasDayJob: {{ old('has_day_job') ? 'true' : 'false' }} }">
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] p-6 md:p-8">
        
        <div class="flex items-center gap-3 mb-8 pb-4 border-b border-outline-variant/20">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-container to-primary flex items-center justify-center text-secondary flex-shrink-0">
                <i class="bi bi-person-plus-fill text-2xl"></i>
            </div>
            <div>
                <h2 class="font-headline text-xl text-primary font-bold">Pendaftaran Personel</h2>
                <p class="font-body text-xs text-on-surface-variant">Lengkapi data diri kru, status, dan informasi bentrok jadwal.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.personnel.store') }}" class="space-y-8">
            @csrf

            {{-- INFORMASI DASAR --}}
            <section>
                <h3 class="font-headline text-sm text-primary font-bold uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="bi bi-person-vcard text-secondary"></i> Informasi Dasar
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline block mb-1.5 ml-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full bg-surface-container-low border {{ $errors->has('name') ? 'border-red-500' : 'border-outline-variant/50' }} rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" value="{{ old('name') }}" placeholder="Contoh: Siti Nurhaliza" required>
                        @error('name')<p class="text-red-500 font-body text-xs mt-1 ml-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline block mb-1.5 ml-1">Email (Untuk Login) <span class="text-red-500">*</span></label>
                        <input type="email" name="email" class="w-full bg-surface-container-low border {{ $errors->has('email') ? 'border-red-500' : 'border-outline-variant/50' }} rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" value="{{ old('email') }}" placeholder="siti@art-hub.id" required>
                        @error('email')<p class="text-red-500 font-body text-xs mt-1 ml-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline block mb-1.5 ml-1">Spesialisasi Utama <span class="text-red-500">*</span></label>
                        <select name="specialty" class="w-full bg-surface-container-low border {{ $errors->has('specialty') ? 'border-red-500' : 'border-outline-variant/50' }} rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors appearance-none" required>
                            <option value="">— Pilih Spesialisasi —</option>
                            <option value="penari" {{ old('specialty') === 'penari' ? 'selected' : '' }}>Penari (Dancer)</option>
                            <option value="pemusik" {{ old('specialty') === 'pemusik' ? 'selected' : '' }}>Pemusik (Musician)</option>
                            <option value="multi_talent" {{ old('specialty') === 'multi_talent' ? 'selected' : '' }}>Multi-Talent / Crew</option>
                        </select>
                        @error('specialty')<p class="text-red-500 font-body text-xs mt-1 ml-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline block mb-1.5 ml-1">Nomor HP</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-outline-variant/50 bg-surface-container-high text-outline font-body text-sm font-bold">
                                +62
                            </span>
                            <input type="text" name="phone" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-r-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" value="{{ old('phone') }}" placeholder="81xxxxxxxxx">
                        </div>
                    </div>
                </div>
            </section>

            {{-- STATUS KEANGGOTAAN --}}
            <section>
                <h3 class="font-headline text-sm text-primary font-bold uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="bi bi-person-gear text-secondary"></i> Status Keanggotaan
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-surface-container-low border border-outline-variant/50 rounded-xl p-4 flex gap-4">
                        <div class="flex items-center h-5 mt-1">
                            <input type="checkbox" name="is_backup" id="is_backup" value="1" {{ old('is_backup') ? 'checked' : '' }} class="w-4 h-4 text-primary bg-surface-container border-outline-variant rounded focus:ring-primary focus:ring-2">
                        </div>
                        <div>
                            <label for="is_backup" class="font-body text-sm font-bold text-on-surface block cursor-pointer">Jadikan Personel Cadangan?</label>
                            <p class="font-body text-xs text-outline mt-1">Tandai jika ini adalah pemain pengganti, bukan formasi inti.</p>
                        </div>
                    </div>
                    
                    <div class="bg-surface-container-low border border-outline-variant/50 rounded-xl p-4">
                        <label class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline block mb-1">Password Default</label>
                        <div class="font-body text-sm font-mono text-secondary font-bold">sanggar123</div>
                        <p class="font-body text-xs text-outline mt-1">Personel diwajibkan mengganti password setelah login pertama.</p>
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
                            <p class="font-body text-xs text-outline mt-1">Contoh: PNS, Guru, Karyawan Bank yang tidak bisa ikut event pagi/siang.</p>
                        </div>
                    </div>
                    
                    <div x-show="hasDayJob" x-collapse>
                        <div class="pt-4 border-t border-outline-variant/30 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline block mb-1.5 ml-1">Nama Instansi / Pekerjaan</label>
                                <input type="text" name="day_job_name" class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" value="{{ old('day_job_name') }}" placeholder="Contoh: Instansi Dinas Pendidikan Subang">
                            </div>
                            
                            <div>
                                <label class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline block mb-1.5 ml-1">Jam Masuk Kerja (WIB)</label>
                                <input type="time" name="day_job_start" class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors appearance-none" value="{{ old('day_job_start', '07:00') }}">
                            </div>
                            
                            <div>
                                <label class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline block mb-1.5 ml-1">Jam Pulang Kerja (WIB)</label>
                                <input type="time" name="day_job_end" class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors appearance-none" value="{{ old('day_job_end', '16:00') }}">
                            </div>
                            
                            <div class="md:col-span-2 bg-orange-500/10 border border-orange-500/20 rounded-lg p-3 flex gap-3 mt-1">
                                <i class="bi bi-exclamation-triangle-fill text-orange-500 mt-0.5"></i>
                                <p class="font-body text-xs text-orange-700 leading-relaxed">
                                    <b>Perhatian:</b> Fitur Smart Plotting akan secara otomatis menolak/memberi peringatan jika Anda memasukkan personel ini ke dalam event yang jam tayangnya bertabrakan dengan rentang jam kerja di atas.
                                </p>
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
                    <i class="bi bi-person-plus-fill"></i> Daftarkan Personel
                </button>
            </div>
            
        </form>
    </div>
</div>
@endsection
