@extends('layouts.klien')
@section('title', 'Profil Saya – ART-HUB')

@section('content')
<style>
    .profile-input {
        width: 100%;
        background: #F4F2EE;
        border: none;
        border-bottom: 2px solid #D7C4BF;
        padding: 12px 14px 10px;
        border-radius: 8px 8px 0 0;
        font-family: 'Manrope', sans-serif;
        font-size: 0.9rem;
        color: #1A1817;
        outline: none;
        transition: border-color 0.2s, background 0.2s;
    }
    .profile-input:focus {
        border-bottom-color: #FCD400;
        background: #FFFDEE;
    }
    .profile-input::placeholder { color: #B0A49F; }
    .profile-card {
        background: #FFFFFF;
        border-radius: 24px;
        padding: 32px;
        box-shadow: 0 2px 16px rgba(54,31,26,0.07);
        border: 1px solid rgba(215,196,191,0.4);
    }
    .profile-card-label {
        font-family: 'Manrope', sans-serif;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #847B78;
        margin-bottom: 8px;
        display: block;
    }
    .btn-primary-maroon {
        background: #361F1A;
        color: #fff;
        font-family: 'Manrope', sans-serif;
        font-weight: 700;
        font-size: 0.875rem;
        padding: 12px 24px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(54,31,26,0.2);
    }
    .btn-primary-maroon:hover {
        background: #5B3730;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(54,31,26,0.28);
    }
    .avatar-circle {
        width: 80px; height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #361F1A, #5B3730);
        border: 3px solid #FCD400;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Noto Serif', serif;
        font-weight: 700; font-size: 2rem;
        color: #FCD400;
        flex-shrink: 0;
    }
    .badge-pill {
        display: inline-flex; align-items: center; gap: 6px;
        background: #FDF0B2; color: #423700;
        font-family: 'Manrope', sans-serif;
        font-size: 0.6rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.1em;
        padding: 4px 10px; border-radius: 99px;
    }
    .info-pill {
        display: flex; align-items: center; gap-x: 8px;
        background: #F4F2EE; border-radius: 10px;
        padding: 8px 12px;
        font-family: 'Manrope', sans-serif; font-size: 0.8rem;
        color: #4D4946;
    }
    .info-pill i { color: #847B78; }
    .section-divider {
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 24px;
    }
    .section-divider-icon {
        width: 36px; height: 36px;
        background: #FDF0B2;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: #423700; font-size: 1rem;
    }
    .section-divider h2 {
        font-family: 'Noto Serif', serif;
        font-weight: 700; font-size: 1.1rem;
        color: #361F1A; margin: 0;
    }
    .form-group { margin-bottom: 20px; }
    .field-hint {
        margin-top: 5px;
        font-size: 0.7rem;
        color: #847B78;
        display: flex; align-items: center; gap: 4px;
    }
</style>

<div class="max-w-4xl mx-auto">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-5 flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium"
         style="background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.2); color: #15803d;">
        <i class="bi bi-check-circle-fill text-lg"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-5 flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium"
         style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); color: #dc2626;">
        <i class="bi bi-exclamation-triangle-fill text-lg"></i>
        {{ session('error') }}
    </div>
    @endif

    {{-- ─── PROFILE HEADER ─── --}}
    <div class="profile-card mb-6 flex flex-col sm:flex-row items-center sm:items-start gap-6">
        {{-- Avatar --}}
        <div class="avatar-circle">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        {{-- Info --}}
        <div class="flex-1 text-center sm:text-left">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-1">
                <h1 class="font-headline font-bold text-2xl text-primary leading-tight">{{ $user->name }}</h1>
                <span class="badge-pill mx-auto sm:mx-0">
                    <i class="bi bi-star-fill" style="font-size:0.55rem;"></i> Klien Aktif
                </span>
            </div>
            <p class="font-label text-xs text-on-surface-variant uppercase tracking-widest mb-4">Portal Klien &mdash; ART-HUB</p>
            <div class="flex flex-col sm:flex-row gap-2">
                <div class="info-pill flex gap-2">
                    <i class="bi bi-envelope-fill"></i>
                    <span>{{ $user->email }}</span>
                    @if(!$user->email_verified_at)
                        <span class="ml-1 text-orange-500 font-bold text-xs">(Belum Terverifikasi)</span>
                    @endif
                </div>
                <div class="info-pill flex gap-2">
                    <i class="bi bi-telephone-fill"></i>
                    <span>{{ $user->phone ?: 'Belum diisi' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── FORM GRID ─── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- KIRI — Data Diri --}}
        <div class="profile-card">
            <div class="section-divider">
                <div class="section-divider-icon"><i class="bi bi-person-lines-fill"></i></div>
                <h2>Data Diri</h2>
            </div>

            @if($errors->hasAny(['name','email','phone']))
            <div class="mb-4 p-3 rounded-xl text-xs font-medium" style="background:rgba(239,68,68,0.07); border:1px solid rgba(239,68,68,0.2); color:#dc2626;">
                @foreach(['name','email','phone'] as $f)
                    @error($f)<div class="flex items-center gap-1.5"><i class="bi bi-x-circle"></i> {{ $message }}</div>@enderror
                @endforeach
            </div>
            @endif

            <form action="{{ route('klien.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="profile-card-label">Nama Lengkap <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="name" class="profile-input" value="{{ old('name', $user->name) }}" required placeholder="Nama lengkap Anda">
                </div>

                <div class="form-group">
                    <label class="profile-card-label">Alamat Email <span style="color:#dc2626;">*</span></label>
                    <input type="email" name="email" class="profile-input" value="{{ old('email', $user->email) }}" required placeholder="email@contoh.com">
                    <p class="field-hint">
                        <i class="bi bi-info-circle-fill" style="color:#FCD400;"></i>
                        Mengubah email akan memerlukan verifikasi ulang.
                    </p>
                </div>

                <div class="form-group" style="margin-bottom: 28px;">
                    <label class="profile-card-label">Nomor Telepon (WA) <span style="color:#dc2626;">*</span></label>
                    <input type="tel" name="phone" class="profile-input" value="{{ old('phone', $user->phone) }}" required placeholder="08xxxxxxxxxx">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary-maroon">
                        <i class="bi bi-floppy-fill"></i> Simpan Profil
                    </button>
                </div>
            </form>
        </div>

        {{-- KANAN — Ganti Kata Sandi --}}
        <div class="profile-card">
            <div class="section-divider">
                <div class="section-divider-icon"><i class="bi bi-shield-lock-fill"></i></div>
                <h2>Ganti Kata Sandi</h2>
            </div>

            @if($errors->hasAny(['current_password','password']))
            <div class="mb-4 p-3 rounded-xl text-xs font-medium" style="background:rgba(239,68,68,0.07); border:1px solid rgba(239,68,68,0.2); color:#dc2626;">
                @error('current_password')<div class="flex items-center gap-1.5"><i class="bi bi-x-circle"></i> {{ $message }}</div>@enderror
                @error('password')<div class="flex items-center gap-1.5"><i class="bi bi-x-circle"></i> {{ $message }}</div>@enderror
            </div>
            @endif

            <form action="{{ route('klien.profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="profile-card-label">Kata Sandi Saat Ini <span style="color:#dc2626;">*</span></label>
                    <input type="password" name="current_password" class="profile-input" required placeholder="••••••••">
                </div>

                <div class="form-group">
                    <label class="profile-card-label">Kata Sandi Baru <span style="color:#dc2626;">*</span></label>
                    <input type="password" name="password" class="profile-input" required placeholder="••••••••">
                    <p class="field-hint"><i class="bi bi-shield-check"></i> Min. 8 karakter (huruf besar, kecil, angka).</p>
                </div>

                <div class="form-group" style="margin-bottom: 28px;">
                    <label class="profile-card-label">Konfirmasi Kata Sandi Baru <span style="color:#dc2626;">*</span></label>
                    <input type="password" name="password_confirmation" class="profile-input" required placeholder="••••••••">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary-maroon">
                        <i class="bi bi-key-fill"></i> Perbarui Kata Sandi
                    </button>
                </div>
            </form>
        </div>

    </div>{{-- end grid --}}
</div>
@endsection
