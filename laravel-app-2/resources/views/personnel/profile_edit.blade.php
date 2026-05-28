@extends('layouts.personnel')
@section('title', 'Edit Profil – Portal Kru ART-HUB')

@section('content')
<style>
    .profile-card {
        background: #FFFFFF;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(54,31,26,0.05);
        border: 1px solid rgba(197,160,40,0.2);
        color: #1A1817;
    }
    .profile-input {
        width: 100%;
        background: #F4F2EE;
        border: none;
        border-bottom: 2px solid #C5A028;
        padding: 12px 14px 10px;
        border-radius: 8px 8px 0 0;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.9rem;
        color: #1A1817;
        outline: none;
        transition: border-color 0.2s, background 0.2s;
    }
    .profile-input:focus {
        border-bottom-color: #8B1A2A;
        background: #FFFDEE;
    }
    .profile-input::placeholder {
        color: #B0A49F;
    }
    .btn-maroon {
        background: linear-gradient(135deg, #8B1A2A, #5C0E19);
        color: #C5A028;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 0.875rem;
        padding: 12px 24px;
        border-radius: 12px;
        border: 1px solid rgba(197,160,40,0.3);
        box-shadow: 0 4px 15px rgba(139,26,42,0.25);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .btn-maroon:hover {
        background: linear-gradient(135deg, #A82335, #70111F);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(139,26,42,0.35);
    }
    .profile-label {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #847B78;
        margin-bottom: 8px;
        display: block;
    }
    .text-muted {
        color: #847B78;
    }
</style>

{{-- Flash --}}
@if(session('success'))
<div class="fu flex items-center gap-2.5 p-3.5 rounded-2xl mb-4 text-green-700 style-success" style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2)">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="fu flex items-center gap-2.5 p-3.5 rounded-2xl mb-4 text-red-700 style-error" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2)">
    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
</div>
@endif
@if($errors->any())
<div class="fu rounded-2xl p-4 mb-4" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25)">
    <div class="flex items-center gap-2 mb-2">
        <i class="bi bi-exclamation-triangle-fill text-red-600"></i>
        <span class="text-sm font-bold text-red-600">Terdapat {{ $errors->count() }} kesalahan:</span>
    </div>
    <ul class="space-y-1">
        @foreach($errors->all() as $error)
        <li class="text-xs text-red-600/80 flex items-start gap-1.5"><i class="bi bi-dot text-red-600 text-base leading-none"></i>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Header --}}
<div class="fu flex items-center gap-3 mb-5">
    <a href="{{ route('personnel.dashboard') }}" class="w-9 h-9 flex items-center justify-center rounded-xl transition-colors" style="background:#F4F2EE;border:1px solid rgba(0,0,0,0.1);color:#4D4946">
        <i class="bi bi-arrow-left text-sm"></i>
    </a>
    <div>
        <div class="font-head font-bold text-[#1A1817] text-2xl">Edit Profil</div>
        <div class="text-xs text-muted">Perbarui informasi diri Anda</div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- KIRI — Data Diri & Avatar --}}
    <form action="{{ route('personnel.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        {{-- Avatar Upload --}}
        <div class="fu1 profile-card">
            <div class="profile-label">Foto Profil</div>
            <div class="flex items-center gap-4">
                <div id="previewWrap" style="width:80px;height:80px;border-radius:20px;overflow:hidden;border:2px solid #C5A028;flex-shrink:0">
                    @if($personnel->photo)
                        <img id="previewImg" src="{{ asset('storage/'.$personnel->photo) }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        <div id="previewImg" class="w-full h-full flex items-center justify-center font-head text-3xl font-bold text-[#C5A028]" style="background:linear-gradient(135deg,rgba(197,160,40,0.2),rgba(139,26,42,0.1))">
                            {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <label for="photo" class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl cursor-pointer font-bold text-sm transition-colors border" style="border-color:#C5A028; color:#C5A028; background: transparent;">
                        <i class="bi bi-camera-fill"></i> Ganti Foto
                    </label>
                    <input id="photo" type="file" name="photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                    <div class="mt-2 text-xs text-muted">JPG, PNG, WebP · Maks 2MB</div>
                </div>
            </div>
        </div>

        {{-- Data Diri --}}
        <div class="fu2 profile-card">
            <div class="profile-label">Data Diri</div>

            <div class="flex flex-col gap-4">
                {{-- Nama Lengkap --}}
                <div>
                    <label class="profile-label">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                           class="profile-input"
                           placeholder="Nama lengkap Anda">
                </div>

                {{-- Nama Panggung --}}
                <div>
                    <label class="profile-label">Nama Panggung / Stage Name</label>
                    <input type="text" name="stage_name" value="{{ old('stage_name', $personnel->stage_name) }}"
                           class="profile-input"
                           placeholder="Nama tampilan di sistem">
                    <div class="mt-1 text-xs text-muted">Dikosongkan = gunakan nama lengkap</div>
                </div>

                {{-- No HP --}}
                <div>
                    <label class="profile-label">No. WhatsApp</label>
                    <input type="tel" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                           class="profile-input"
                           placeholder="08xxxxxxxxxx">
                </div>

                {{-- Bio --}}
                <div>
                    <label class="profile-label">Bio / Deskripsi Singkat</label>
                    <textarea name="bio" rows="3"
                              class="profile-input resize-none"
                              style="border-radius: 8px 8px 0 0;"
                              placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', $personnel->bio) }}</textarea>
                    <div class="mt-1 text-xs text-muted">Maks 500 karakter</div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="fu4">
            <button type="submit" class="w-full btn-maroon p-4 justify-center">
                <i class="bi bi-check-lg text-lg"></i> Simpan Perubahan Profil
            </button>
        </div>
    </form>

    {{-- KANAN — Info Sanggar & Ganti Password --}}
    <div class="space-y-6">
        {{-- Info Read-only --}}
        <div class="fu3 profile-card">
            <div class="profile-label">Info Sanggar (Dikelola Admin)</div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-xs text-muted">Spesialisasi</div>
                    <div class="font-bold text-[#1A1817] capitalize mt-0.5">{{ ucfirst(str_replace('_',' ',$personnel->specialty)) }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted">Status</div>
                    <div class="font-bold mt-0.5 text-green-700">
                        {{ $personnel->is_backup ? 'Cadangan' : 'Personel Utama' }}
                    </div>
                </div>
                @if($personnel->has_day_job && $personnel->day_job_desc)
                <div class="col-span-2">
                    <div class="text-xs text-muted">Pekerjaan Utama</div>
                    <div class="font-semibold text-[#1A1817] mt-0.5">{{ $personnel->day_job_desc }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Ganti Password --}}
        <form action="{{ route('personnel.profile.password') }}" method="POST" class="fu5 profile-card">
            @csrf
            <div class="profile-label flex items-center gap-2">
                <i class="bi bi-shield-lock-fill"></i> Keamanan (Ganti Password)
            </div>

            <div class="flex flex-col gap-4 mt-4">
                <div>
                    <label class="profile-label">Password Saat Ini <span class="text-red-500">*</span></label>
                    <input type="password" name="current_password" required
                           class="profile-input">
                </div>
                
                <div>
                    <label class="profile-label">Password Baru <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                           class="profile-input">
                    <div class="mt-1 text-xs text-muted">Min. 8 karakter (huruf besar, kecil, angka)</div>
                </div>

                <div>
                    <label class="profile-label">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required
                           class="profile-input">
                </div>
                
                <button type="submit" class="w-full btn-maroon p-3 justify-center text-xs uppercase tracking-widest">
                    <i class="bi bi-key-fill"></i> Perbarui Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const wrap = document.getElementById('previewWrap');
            wrap.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
