@extends('layouts.admin')

@section('title', 'Pengaturan Profil Admin – ART-HUB')
@section('page_title', 'Pengaturan Profil')
@section('page_subtitle', 'Kelola informasi dan keamanan akun admin Anda.')

@section('content')

{{-- Alert Messages --}}
@if (session('success'))
    <div class="p-4 mb-6 text-sm text-green-700 bg-green-500/10 border border-green-500/20 rounded-xl flex items-center gap-2 font-bold">
        <i class="bi bi-check-circle-fill text-green-500"></i> {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="p-4 mb-6 text-sm text-red-700 bg-red-500/10 border border-red-500/20 rounded-xl font-bold">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}
    </div>
@endif

<div class="grid md:grid-cols-2 gap-6">
    {{-- Informasi Profil --}}
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm p-6">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-outline-variant/20">
            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                <i class="bi bi-person-fill text-primary text-lg"></i>
            </div>
            <div>
                <h3 class="font-headline text-lg text-primary font-bold">Data Profil</h3>
                <p class="font-label text-xs uppercase tracking-widest text-outline">Informasi akun admin</p>
            </div>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                       placeholder="Nama lengkap Anda">
                @error('name')
                    <p class="text-red-600 font-body text-xs mt-1">{{ $message }}</p>
                @enderror
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
                       placeholder="+62...">
                @error('phone')
                    <p class="text-red-600 font-body text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Role</label>
                <div class="px-4 py-3 bg-surface-container-low border border-outline-variant/50 rounded-xl font-body text-sm text-outline">
                    {{ ucfirst($user->role) }}
                </div>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors">
                <i class="bi bi-save2-fill me-1"></i> Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- Keamanan (Update Password) --}}
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-sm p-6">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-outline-variant/20">
            <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center flex-shrink-0">
                <i class="bi bi-lock-fill text-secondary text-lg"></i>
            </div>
            <div>
                <h3 class="font-headline text-lg text-primary font-bold">Keamanan</h3>
                <p class="font-label text-xs uppercase tracking-widest text-outline">Ubah password akun</p>
            </div>
        </div>

        <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Password Saat Ini</label>
                <input type="password" name="current_password" required
                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                       placeholder="Masukkan password saat ini">
                @error('current_password')
                    <p class="text-red-600 font-body text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Password Baru</label>
                <input type="password" name="password" required
                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                       placeholder="Minimal 8 karakter">
                @error('password')
                    <p class="text-red-600 font-body text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 font-body text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                       placeholder="Ulangi password baru">
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-secondary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-secondary-container transition-colors">
                <i class="bi bi-shield-lock-fill me-1"></i> Perbarui Password
            </button>
        </form>
    </div>
</div>

@endsection
