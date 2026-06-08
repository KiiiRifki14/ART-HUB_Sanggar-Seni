@extends('layouts.admin')

@section('title', 'Pengaturan Profil Admin – ART-HUB')
@section('page_title', 'Pengaturan Profil')
@section('page_subtitle', 'Kelola informasi dan keamanan akun admin Anda.')

@section('content')

{{-- Alert Messages --}}
@if (session('success'))
    <div class="p-4 mb-5 text-sm text-green-700 bg-green-500/10 border border-green-500/20 rounded-xl flex items-center gap-2.5 font-bold shadow-sm">
        <div class="w-7 h-7 rounded-lg bg-green-500/20 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-check-circle-fill text-green-500 text-sm"></i>
        </div>
        {{ session('success') }}
    </div>
@endif
@if ($errors->any())
    <div class="p-4 mb-5 text-sm text-red-700 bg-red-500/10 border border-red-500/20 rounded-xl flex items-center gap-2.5 font-bold shadow-sm">
        <div class="w-7 h-7 rounded-lg bg-red-500/20 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-exclamation-triangle-fill text-red-500 text-sm"></i>
        </div>
        {{ $errors->first() }}
    </div>
@endif

{{-- PROFILE HERO CARD --}}
<div class="relative bg-gradient-to-br from-primary to-primary-container rounded-2xl p-6 mb-6 overflow-hidden shadow-lg">
    {{-- Decorative circles --}}
    <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full bg-white/5"></div>
    <div class="absolute -bottom-10 -left-6 w-32 h-32 rounded-full bg-white/5"></div>

    <div class="relative flex items-center gap-5">
        {{-- Avatar --}}
        <div class="w-16 h-16 rounded-2xl bg-white/20 border-2 border-white/30 flex items-center justify-center shadow-lg flex-shrink-0 backdrop-blur-sm">
            <span class="font-headline text-2xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
        </div>
        <div>
            <p class="font-body text-white/70 text-xs uppercase tracking-widest font-bold">Selamat datang kembali</p>
            <h2 class="font-headline text-xl font-bold text-white mt-0.5">{{ $user->name }}</h2>
            <div class="flex items-center gap-3 mt-1.5">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/15 rounded-lg font-label text-[0.6rem] font-bold uppercase tracking-widest text-white/90">
                    <i class="bi bi-shield-check text-xs"></i> {{ ucfirst($user->role) }}
                </span>
                <span class="font-body text-xs text-white/70">{{ $user->email }}</span>
            </div>
        </div>
        <div class="ml-auto text-right">
            <p class="font-label text-[0.6rem] uppercase tracking-widest text-white/60">Login Terakhir</p>
            <p class="font-body text-sm font-semibold text-white/90 mt-0.5">Hari ini, {{ now()->format('H:i') }} WIB</p>
        </div>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-5">

    {{-- ══ KIRI: Data Profil & Notifikasi ══ --}}
    <div class="space-y-5">

        {{-- Informasi Profil --}}
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-secondary"></div>

            <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-low/50 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-person-fill text-primary"></i>
                </div>
                <div>
                    <h3 class="font-headline text-sm font-bold text-primary">Data Profil</h3>
                    <p class="font-label text-[0.6rem] uppercase tracking-widest text-outline">Informasi dasar akun</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                {{-- Foto Profil --}}
                <div class="flex items-center gap-4 p-4 bg-surface-container-low rounded-xl border border-outline-variant/20">
                    <div class="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-primary/20 to-secondary/20 border border-primary/20 flex items-center justify-center overflow-hidden group flex-shrink-0 cursor-pointer"
                         onclick="document.getElementById('avatarInput').click()">
                        <span class="font-headline text-2xl text-primary font-bold" id="avatarInitial">{{ substr($user->name, 0, 1) }}</span>
                        <div class="absolute inset-0 bg-primary/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="bi bi-camera-fill text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-body text-sm font-semibold text-on-surface">Foto Profil</p>
                        <p class="font-label text-[0.65rem] text-outline mb-2">JPG, PNG. Maks: 2MB.</p>
                        <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-outline-variant/50 hover:bg-surface-container hover:border-primary transition-colors cursor-pointer font-label text-xs font-bold text-on-surface-variant">
                            <i class="bi bi-upload text-xs"></i> Pilih Foto
                            <input type="file" id="avatarInput" name="avatar" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                        </label>
                        <p id="avatarName" class="font-label text-[0.6rem] text-secondary mt-1 hidden"><i class="bi bi-check-circle-fill"></i> <span></span></p>
                    </div>
                </div>

                {{-- Nama --}}
                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                           placeholder="Nama lengkap Anda">
                    @error('name') <p class="text-red-600 font-body text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Username & Role (readonly) --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Username</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-outline font-bold">@</span>
                            <input type="text" value="{{ explode('@', $user->email)[0] }}" readonly
                                   class="w-full bg-surface-container/40 border border-outline-variant/30 rounded-xl pl-9 pr-4 py-2.5 font-body text-sm text-outline cursor-not-allowed"
                                   title="Username tidak dapat diubah">
                        </div>
                    </div>
                    <div>
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Role</label>
                        <div class="w-full bg-surface-container/40 border border-outline-variant/30 rounded-xl px-4 py-2.5 font-body text-sm text-outline font-semibold flex items-center justify-between cursor-not-allowed">
                            {{ ucfirst($user->role) }}
                            <i class="bi bi-shield-check text-primary text-sm"></i>
                        </div>
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Email</label>
                    <div class="relative">
                        <i class="bi bi-envelope-fill absolute left-4 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none"></i>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl pl-10 pr-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="email@example.com">
                    </div>
                    @error('email') <p class="text-red-600 font-body text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Telepon --}}
                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Nomor Telepon</label>
                    <div class="relative">
                        <i class="bi bi-telephone-fill absolute left-4 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none"></i>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl pl-10 pr-4 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="+62"
                               oninput="this.value = this.value.replace(/[^0-9+]/g, ''); if(this.value.length > 0 && !this.value.startsWith('+62')) this.value = '+62' + this.value.replace('+', '');"
                               pattern="^\+62[0-9]{8,13}$" title="Gunakan format +62 diikuti angka">
                    </div>
                    @error('phone') <p class="text-red-600 font-body text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit"
                        class="w-full py-3 mt-1 rounded-xl bg-gradient-to-r from-primary-container to-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-sm flex items-center justify-center gap-2">
                    <i class="bi bi-save2-fill"></i> Simpan Perubahan Profil
                </button>
            </form>
        </div>

        {{-- Pengaturan Notifikasi --}}
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-low/50 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-yellow-500/10 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-bell-fill text-yellow-600"></i>
                </div>
                <div>
                    <h3 class="font-headline text-sm font-bold text-on-surface">Preferensi Notifikasi</h3>
                    <p class="font-label text-[0.6rem] uppercase tracking-widest text-outline">Atur notifikasi yang Anda terima</p>
                </div>
            </div>
            <div class="p-6 space-y-4">
                {{-- Notif System --}}
                <label class="flex items-center justify-between gap-4 cursor-pointer group p-3 rounded-xl hover:bg-surface-container transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="bi bi-app-indicator text-primary text-sm"></i>
                        </div>
                        <div>
                            <p class="font-body text-sm font-semibold text-on-surface group-hover:text-primary transition-colors">Notifikasi Sistem</p>
                            <p class="font-label text-[0.65rem] text-outline">Pemberitahuan booking masuk, perubahan status, dll.</p>
                        </div>
                    </div>
                    <div class="relative flex items-center justify-center flex-shrink-0">
                        <input type="checkbox" id="notif_system" class="peer sr-only" onchange="saveNotifPref('notif_system')">
                        <div class="w-11 h-6 bg-outline-variant/30 rounded-full peer-checked:bg-primary transition-colors"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </div>
                </label>

                {{-- Notif Email --}}
                <label class="flex items-center justify-between gap-4 cursor-pointer group p-3 rounded-xl hover:bg-surface-container transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-secondary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="bi bi-envelope-fill text-secondary text-sm"></i>
                        </div>
                        <div>
                            <p class="font-body text-sm font-semibold text-on-surface group-hover:text-primary transition-colors">Notifikasi Email</p>
                            <p class="font-label text-[0.65rem] text-outline">Kirim rekap laporan mingguan via email.</p>
                        </div>
                    </div>
                    <div class="relative flex items-center justify-center flex-shrink-0">
                        <input type="checkbox" id="notif_email" class="peer sr-only" onchange="saveNotifPref('notif_email')">
                        <div class="w-11 h-6 bg-outline-variant/30 rounded-full peer-checked:bg-primary transition-colors"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </div>
                </label>
            </div>
        </div>
    </div>

    {{-- ══ KANAN: Keamanan & Log ══ --}}
    <div class="space-y-5">

        {{-- Keamanan --}}
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-low/50 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-secondary/10 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-shield-lock-fill text-secondary"></i>
                </div>
                <div>
                    <h3 class="font-headline text-sm font-bold text-primary">Keamanan Akun</h3>
                    <p class="font-label text-[0.6rem] uppercase tracking-widest text-outline">Ubah password akun</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.password') }}" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                {{-- Password Strength Indicator --}}
                <div id="strengthBar" class="h-1.5 rounded-full bg-surface-container-high overflow-hidden mb-1 transition-all hidden">
                    <div id="strengthFill" class="h-full rounded-full transition-all duration-300 w-0 bg-red-500"></div>
                </div>

                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Password Saat Ini</label>
                    <div class="relative">
                        <i class="bi bi-lock-fill absolute left-4 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none"></i>
                        <input type="password" id="current_password" name="current_password" required
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl pl-10 pr-12 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all"
                               placeholder="Masukkan password saat ini">
                        <button type="button" onclick="togglePassword('current_password', this)"
                                class="absolute inset-y-0 right-0 px-4 flex items-center text-outline hover:text-secondary transition-colors">
                            <i class="bi bi-eye-slash-fill text-sm"></i>
                        </button>
                    </div>
                    @error('current_password') <p class="text-red-600 font-body text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Password Baru</label>
                    <div class="relative">
                        <i class="bi bi-key-fill absolute left-4 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none"></i>
                        <input type="password" id="new_password" name="password" required
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl pl-10 pr-12 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all"
                               placeholder="Min. 8 karakter (A-a-1-@)"
                               oninput="checkStrength(this.value)">
                        <button type="button" onclick="togglePassword('new_password', this)"
                                class="absolute inset-y-0 right-0 px-4 flex items-center text-outline hover:text-secondary transition-colors">
                            <i class="bi bi-eye-slash-fill text-sm"></i>
                        </button>
                    </div>
                    <div id="strengthLabel" class="font-label text-[0.6rem] uppercase tracking-widest mt-1.5 hidden text-outline"></div>
                    @error('password') <p class="text-red-600 font-body text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <i class="bi bi-key-fill absolute left-4 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none"></i>
                        <input type="password" id="confirm_password" name="password_confirmation" required
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl pl-10 pr-12 py-2.5 font-body text-sm text-on-surface focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all"
                               placeholder="Ulangi password baru">
                        <button type="button" onclick="togglePassword('confirm_password', this)"
                                class="absolute inset-y-0 right-0 px-4 flex items-center text-outline hover:text-secondary transition-colors">
                            <i class="bi bi-eye-slash-fill text-sm"></i>
                        </button>
                    </div>
                </div>

                <div class="p-3.5 bg-secondary/5 border border-secondary/20 rounded-xl flex gap-3 items-start">
                    <i class="bi bi-info-circle-fill text-secondary mt-0.5 flex-shrink-0"></i>
                    <p class="font-body text-xs text-on-surface-variant leading-relaxed">
                        Gunakan kombinasi <strong>huruf besar, kecil, angka, dan simbol</strong> minimal 8 karakter untuk keamanan maksimal.
                    </p>
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-xl bg-secondary text-white font-label text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-sm flex items-center justify-center gap-2">
                    <i class="bi bi-shield-lock-fill"></i> Perbarui Password
                </button>
            </form>
        </div>

        {{-- Log Aktivitas --}}
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-low/50 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-500/10 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-clock-history text-blue-600"></i>
                </div>
                <div>
                    <h3 class="font-headline text-sm font-bold text-on-surface">Aktivitas Login</h3>
                    <p class="font-label text-[0.6rem] uppercase tracking-widest text-outline">Riwayat sesi aktif</p>
                </div>
            </div>
            <div class="p-6 space-y-3">
                {{-- Sesi Aktif --}}
                <div class="flex items-center gap-4 p-4 bg-surface-container-low rounded-xl border border-outline-variant/30">
                    <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center border border-outline-variant/50 text-outline flex-shrink-0">
                        <i class="bi bi-laptop text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-body text-sm font-bold text-on-surface">Windows • Chrome</p>
                        <p class="font-label text-xs text-outline mt-0.5">Hari ini, {{ now()->format('H:i') }} WIB</p>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-500/10 text-green-600 border border-green-500/20 rounded-lg font-label text-[0.6rem] font-bold uppercase tracking-wider flex-shrink-0">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Aktif
                    </span>
                </div>

                {{-- Info --}}
                <div class="flex items-start gap-2.5 p-3.5 bg-primary/5 border border-primary/15 rounded-xl">
                    <i class="bi bi-shield-check text-primary flex-shrink-0 mt-0.5 text-sm"></i>
                    <div>
                        <p class="font-body text-xs font-semibold text-primary">Sesi Aman</p>
                        <p class="font-label text-[0.65rem] text-outline mt-0.5">Ini adalah perangkat dan sesi login terakhir Anda. Jika bukan Anda, segera ubah password.</p>
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="grid grid-cols-2 gap-3 pt-1">
                    <div class="p-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-center">
                        <div class="font-headline text-lg font-bold text-primary">{{ $user->created_at->diffForHumans(null, true) }}</div>
                        <div class="font-label text-[0.6rem] uppercase tracking-widest text-outline mt-0.5">Bergabung</div>
                    </div>
                    <div class="p-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-center">
                        <div class="font-headline text-lg font-bold text-secondary">{{ ucfirst($user->role) }}</div>
                        <div class="font-label text-[0.6rem] uppercase tracking-widest text-outline mt-0.5">Level Akses</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="bg-surface-container-lowest border border-red-500/20 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-red-500/15 bg-red-500/5 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-red-500/15 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-exclamation-octagon-fill text-red-500"></i>
                </div>
                <div>
                    <h3 class="font-headline text-sm font-bold text-red-600">Zona Berbahaya</h3>
                    <p class="font-label text-[0.6rem] uppercase tracking-widest text-red-400">Tindakan tidak dapat dibatalkan</p>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-body text-sm font-semibold text-on-surface">Keluar dari Sesi</p>
                        <p class="font-label text-xs text-outline mt-0.5">Akhiri sesi admin Anda sekarang.</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-red-300 text-red-600 font-label text-xs font-bold uppercase tracking-widest hover:bg-red-50 hover:border-red-500 transition-colors">
                            <i class="bi bi-box-arrow-right text-sm"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const notifSys   = document.getElementById('notif_system');
    const notifEmail = document.getElementById('notif_email');
    notifSys.checked   = localStorage.getItem('notif_system')  !== 'false';
    notifEmail.checked = localStorage.getItem('notif_email') !== 'false';
});

function saveNotifPref(id) {
    const el = document.getElementById(id);
    localStorage.setItem(id, el.checked);
}

function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon  = button.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
        button.classList.add('text-secondary');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
        button.classList.remove('text-secondary');
    }
}

function checkStrength(val) {
    const bar    = document.getElementById('strengthBar');
    const fill   = document.getElementById('strengthFill');
    const label  = document.getElementById('strengthLabel');
    bar.classList.remove('hidden');
    label.classList.remove('hidden');
    let score = 0;
    if (val.length >= 8)    score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const levels = [
        { pct: '25%', color: 'bg-red-500',    text: 'Lemah' },
        { pct: '50%', color: 'bg-orange-500', text: 'Cukup' },
        { pct: '75%', color: 'bg-yellow-500', text: 'Kuat' },
        { pct: '100%',color: 'bg-green-500',  text: 'Sangat Kuat' },
    ];
    const lvl = levels[score - 1] || levels[0];
    fill.style.width = lvl.pct;
    fill.className = `h-full rounded-full transition-all duration-300 ${lvl.color}`;
    label.textContent = lvl.text;
    label.className = `font-label text-[0.6rem] uppercase tracking-widest mt-1.5 font-bold ${lvl.color.replace('bg-','text-')}`;
}

function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const nameEl = document.getElementById('avatarName');
        nameEl.classList.remove('hidden');
        nameEl.querySelector('span').textContent = input.files[0].name;
    }
}
</script>
@endpush

@endsection
