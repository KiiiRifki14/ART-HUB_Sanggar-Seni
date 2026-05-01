@extends('layouts.admin')

@section('title', 'New Booking – ART-HUB')
@section('page_title', 'New Booking Entry')
@section('page_subtitle', 'Input booking manual dari klien offline / WhatsApp.')

@section('content')
<div class="flex justify-center">
    <div class="w-full lg:w-8/12">
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] p-6 sm:p-8">
            <h2 class="font-headline text-xl text-primary font-semibold mb-6 flex items-center gap-2 border-b border-outline-variant/30 pb-4">
                <i class="bi bi-file-earmark-plus-fill text-secondary"></i> Form Booking Manual
            </h2>

            <form method="POST" action="{{ route('admin.bookings.manual.store') }}" class="space-y-8">
                @csrf

                {{-- Data Klien --}}
                <div class="space-y-4">
                    <h3 class="font-label text-xs uppercase tracking-widest text-secondary font-bold flex items-center gap-2">
                        <i class="bi bi-person-circle"></i> Data Klien
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Nama Klien <span class="text-red-500">*</span></label>
                            <input type="text" name="client_name" value="{{ old('client_name') }}" required 
                                   class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('client_name') border-red-500 @enderror" 
                                   placeholder="Bpk./Ibu Siapa">
                            @error('client_name') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">No. Telepon / WA <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <span class="inline-flex items-center px-4 rounded-l-lg border border-r-0 border-outline-variant/50 bg-surface-container-highest text-on-surface-variant font-body text-sm font-semibold">
                                    +62
                                </span>
                                <input type="text" name="client_phone" value="{{ old('client_phone') }}" required 
                                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-r-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('client_phone') border-red-500 @enderror" 
                                       placeholder="81xxxxxxxxx">
                            </div>
                            @error('client_phone') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Detail Event --}}
                <div class="space-y-4">
                    <h3 class="font-label text-xs uppercase tracking-widest text-secondary font-bold flex items-center gap-2">
                        <i class="bi bi-calendar-event"></i> Detail Event
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Jenis Event <span class="text-red-500">*</span></label>
                            <select name="event_type" required 
                                    class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all appearance-none @error('event_type') border-red-500 @enderror">
                                <option value="">— Pilih Jenis —</option>
                                @foreach(['jaipong'=>'Jaipong','degung'=>'Degung','rampak_gendang'=>'Rampak Gendang','wayang_golek'=>'Wayang Golek','campuran'=>'Campuran'] as $k => $v)
                                    <option value="{{ $k }}" {{ old('event_type') === $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                            @error('event_type') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                            <input type="date" name="event_date" value="{{ old('event_date') }}" required 
                                   class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('event_date') border-red-500 @enderror">
                            @error('event_date') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Jam Mulai <span class="text-red-500">*</span></label>
                            <input type="time" name="event_start" value="{{ old('event_start') }}" required 
                                   class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('event_start') border-red-500 @enderror">
                            @error('event_start') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Jam Selesai <span class="text-red-500">*</span></label>
                            <input type="time" name="event_end" value="{{ old('event_end') }}" required 
                                   class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('event_end') border-red-500 @enderror">
                            @error('event_end') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Venue / Lokasi Acara <span class="text-red-500">*</span></label>
                            <input type="text" name="venue" value="{{ old('venue') }}" required 
                                   class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('venue') border-red-500 @enderror" 
                                   placeholder="Gedung / Rumah / Alamat Lengkap">
                            @error('venue') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Nilai Kontrak --}}
                <div class="space-y-4">
                    <h3 class="font-label text-xs uppercase tracking-widest text-secondary font-bold flex items-center gap-2">
                        <i class="bi bi-wallet2"></i> Nilai Kontrak
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Total Harga Deal (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="total_price" value="{{ old('total_price') }}" required 
                                   class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-on-surface font-semibold focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('total_price') border-red-500 @enderror" 
                                   placeholder="Contoh: 15000000">
                            @error('total_price') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1.5">Jumlah DP Masuk (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="dp_amount" value="{{ old('dp_amount') }}" required 
                                   class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body text-sm text-green-600 font-semibold focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all @error('dp_amount') border-red-500 @enderror" 
                                   placeholder="Contoh: 7500000">
                            @error('dp_amount') <p class="text-red-500 text-xs mt-1 font-body">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-outline-variant/30 mt-8">
                    <a href="{{ route('admin.bookings.index') }}" 
                       class="px-5 py-2.5 rounded-lg border border-outline-variant/50 text-on-surface-variant hover:bg-surface-container-low transition-colors font-label text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                    <button type="submit" 
                            class="bg-gradient-to-br from-primary-container to-primary text-white px-6 py-2.5 rounded-lg font-label text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-md flex items-center gap-2">
                        <i class="bi bi-save"></i> Simpan Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
