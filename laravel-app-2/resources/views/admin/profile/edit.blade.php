@extends('layouts.admin')

@section('title', 'Pengaturan Profil Admin – ART-HUB')
@section('page_title', 'Pengaturan Profil')
@section('page_subtitle', 'Kelola informasi dan keamanan akun admin Anda.')

@section('content')

{{-- Alert Messages --}}
@if (session('success'))
    <div class="p-4 mb-6 text-sm text-green-700 bg-green-500/10 border border-green-500/20 rounded-xl flex items-center gap-2 font-bold shadow-sm">
        <i class="bi bi-check-circle-fill text-green-500"></i> {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="p-4 mb-6 text-sm text-red-700 bg-red-500/10 border border-red-500/20 rounded-xl font-bold shadow-sm">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}
    </div>
@endif

<div class="grid md:grid-cols-2 gap-6">
    {{-- Sisi Kiri: Data Profil & Notifikasi --}}
    <div class="space-y-6">
        {{-- Informasi Profil --}}
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-secondary"></div>
            
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-outline-variant/20">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-person-fill text-primary text-lg"></i>
                </div>
                <div>
                    <h3 class="font-headline text-lg text-primary font-bold">Data Profil</h3>
                    <p class="font-label text-xs uppercase tracking-widest text-outline">Informasi dasar akun</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Foto Profil --}}
                <div class="flex items-center gap-5 mb-2">
                    <div class="relative w-20 h-20 rounded-2xl bg-surface-container-low border-2 border-dashed border-outline-variant/50 flex items-center justify-center overflow-hidden group">
                        <span class="font-headline text-2xl text-primary font-bold">{{ substr($user->name, 0, 1) }}</span>
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            <i class="bi bi-camera-fill text-white"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-1">Foto Profil</label>
                        <p class="text-[0.65rem] text-outline mb-2">Format: JPG, PNG. Maks: 2MB.</p>
                        <label class="inline-block px-3 py-1.5 rounded-lg border border-outline-variant/50 hover:bg-surface-container hover:border-primary transition-colors cursor-pointer text-xs font-semibold text-on-surface">
                            <input type="file" name="avatar" class="hidden" accept="image/*">
                            Pilih Foto Baru
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                           placeholder="Nama lengkap Anda">
                    @error('name')
                        <p class="text-red-600 font-body text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Username</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-outline">@</span>
                            <input type="text" value="{{ explode('@', $user->email)[0] }}" readonly
                                   class="w-full bg-surface-container/30 border border-outline-variant/30 rounded-xl pl-9 pr-4 py-3 font-body text-sm text-outline cursor-not-allowed"
                                   title="Username tidak dapat diubah">
                        </div>
                    </div>
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Role</label>
                        <div class="w-full bg-surface-container/30 border border-outline-variant/30 rounded-xl px-4 py-3 font-body text-sm text-outline font-semibold flex items-center justify-between cursor-not-allowed">
                            {{ ucfirst($user->role) }}
                            <i class="bi bi-shield-check text-primary"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                           placeholder="email@example.com">
                    @error('email')
                        <p class="text-red-600 font-body text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Nomor Telepon</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                           class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                           placeholder="+62"
                           oninput="this.value = this.value.replace(/[^0-9+]/g, ''); if(this.value.length > 0 && !this.value.startsWith('+62')) this.value = '+62' + this.value.replace('+', '');"
                           pattern="^\+62[0-9]{8,13}$" title="Gunakan format +62 diikuti angka">
                    @error('phone')
                        <p class="text-red-600 font-body text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-3 mt-2 rounded-xl bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-sm">
                    <i class="bi bi-save2-fill me-1"></i> Simpan Perubahan Profil
                </button>
            </form>
        </div>

        {{-- Pengaturan Notifikasi --}}
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-outline-variant/20">
                <div class="w-8 h-8 rounded-lg bg-yellow-500/10 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-bell-fill text-yellow-600 text-sm"></i>
                </div>
                <div>
                    <h3 class="font-headline text-base text-on-surface font-bold">Preferensi Notifikasi</h3>
                </div>
            </div>
            
            <div class="space-y-4">
                <label class="flex items-start gap-3 cursor-pointer group">
                    <div class="relative flex items-center justify-center mt-0.5">
                        <input type="checkbox" id="notif_system" class="peer sr-only" onchange="saveNotifPref('notif_system')">
                        <div class="w-10 h-5 bg-outline-variant/30 rounded-full peer-checked:bg-primary transition-colors"></div>
                        <div class="absolute left-1 top-1 w-3 h-3 bg-white rounded-full transition-transform peer-checked:translate-x-5 shadow-sm"></div>
                    </div>
                    <div>
                        <p class="font-body text-sm font-semibold text-on-surface group-hover:text-primary transition-colors">Notifikasi Sistem</p>
                        <p class="font-label text-[0.65rem] text-outline">Pemberitahuan booking masuk, perubahan status, dll.</p>
                    </div>
                </label>

                <label class="flex items-start gap-3 cursor-pointer group">
                    <div class="relative flex items-center justify-center mt-0.5">
                        <input type="checkbox" id="notif_email" class="peer sr-only" onchange="saveNotifPref('notif_email')">
                        <div class="w-10 h-5 bg-outline-variant/30 rounded-full peer-checked:bg-primary transition-colors"></div>
                        <div class="absolute left-1 top-1 w-3 h-3 bg-white rounded-full transition-transform peer-checked:translate-x-5 shadow-sm"></div>
                    </div>
                    <div>
                        <p class="font-body text-sm font-semibold text-on-surface group-hover:text-primary transition-colors">Notifikasi Email</p>
                        <p class="font-label text-[0.65rem] text-outline">Kirim rekap laporan mingguan via email.</p>
                    </div>
                </label>
            </div>
        </div>
    </div>

    {{-- Sisi Kanan: Keamanan & Log --}}
    <div class="space-y-6">
        {{-- Keamanan (Update Password) --}}
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-secondary to-primary"></div>

            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-outline-variant/20">
                <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-shield-lock-fill text-secondary text-lg"></i>
                </div>
                <div>
                    <h3 class="font-headline text-lg text-primary font-bold">Keamanan</h3>
                    <p class="font-label text-xs uppercase tracking-widest text-outline">Ubah password akun</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Password Saat Ini</label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password" required
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors"
                               placeholder="Masukkan password saat ini">
                        <button type="button" onclick="togglePassword('current_password', this)" class="absolute inset-y-0 right-0 px-4 flex items-center text-outline hover:text-secondary transition-colors" title="Lihat Password">
                            <i class="bi bi-eye-slash-fill"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-red-600 font-body text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Password Baru</label>
                    <div class="relative">
                        <input type="password" id="new_password" name="password" required
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors"
                               placeholder="Min. 8 karakter (A-a-1)">
                        <button type="button" onclick="togglePassword('new_password', this)" class="absolute inset-y-0 right-0 px-4 flex items-center text-outline hover:text-secondary transition-colors" title="Lihat Password">
                            <i class="bi bi-eye-slash-fill"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-600 font-body text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <input type="password" id="confirm_password" name="password_confirmation" required
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors"
                               placeholder="Ulangi password baru">
                        <button type="button" onclick="togglePassword('confirm_password', this)" class="absolute inset-y-0 right-0 px-4 flex items-center text-outline hover:text-secondary transition-colors" title="Lihat Password">
                            <i class="bi bi-eye-slash-fill"></i>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <div class="p-3 bg-secondary/5 border border-secondary/20 rounded-xl mb-4 flex gap-3 items-start">
                        <i class="bi bi-info-circle-fill text-secondary mt-0.5"></i>
                        <p class="font-body text-xs text-on-surface-variant leading-relaxed">
                            Pastikan password baru Anda menggunakan kombinasi huruf, angka, dan simbol untuk keamanan maksimal.
                        </p>
                    </div>
                    <button type="submit" class="w-full py-3 rounded-xl bg-secondary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-secondary-container transition-colors shadow-sm">
                        <i class="bi bi-shield-lock-fill me-1"></i> Perbarui Password
                    </button>
                </div>
            </form>
        </div>

        {{-- Log Aktivitas Terakhir --}}
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-outline-variant/20">
                <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-clock-history text-blue-600 text-sm"></i>
                </div>
                <div>
                    <h3 class="font-headline text-base text-on-surface font-bold">Aktivitas Login</h3>
                </div>
            </div>
            
            <div class="flex items-center gap-4 p-3 bg-surface-container-low rounded-xl border border-outline-variant/30">
                <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center border border-outline-variant/50 text-outline">
                    <i class="bi bi-laptop"></i>
                </div>
                <div>
                    <p class="font-body text-sm font-bold text-on-surface">Windows • Chrome</p>
                    <p class="font-label text-xs text-outline mt-0.5">Hari ini, {{ now()->format('H:i') }} WIB</p>
                </div>
                <div class="ml-auto">
                    <span class="inline-block px-2.5 py-1 bg-green-500/10 text-green-600 border border-green-500/20 rounded-lg font-label text-[0.6rem] font-bold uppercase tracking-wider">
                        Aktif
                    </span>
                </div>
            </div>
            <p class="font-body text-[0.65rem] text-center text-outline mt-3">
                Ini adalah perangkat dan waktu Anda login terakhir kali.
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Load notification preferences
        const notifSys = document.getElementById('notif_system');
        const notifEmail = document.getElementById('notif_email');

        if(localStorage.getItem('notif_system') !== null) {
            notifSys.checked = localStorage.getItem('notif_system') === 'true';
        } else {
            notifSys.checked = true;
        }

        if(localStorage.getItem('notif_email') !== null) {
            notifEmail.checked = localStorage.getItem('notif_email') === 'true';
        } else {
            notifEmail.checked = true;
        }
    });

    function saveNotifPref(id) {
        const el = document.getElementById(id);
        localStorage.setItem(id, el.checked);
    }

    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash-fill');
            icon.classList.add('bi-eye-fill');
            button.classList.add('text-secondary');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-fill');
            icon.classList.add('bi-eye-slash-fill');
            button.classList.remove('text-secondary');
        }
    }
</script>
@endpush

@endsection
