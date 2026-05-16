@extends('layouts.personnel')
@section('title', 'Edit Profil – Portal Kru ART-HUB')

@section('content')

{{-- Flash --}}
@if(session('success'))
<div class="fu flex items-center gap-2.5 p-3.5 rounded-2xl mb-4 text-green-400 text-sm" style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2)">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="fu flex items-center gap-2.5 p-3.5 rounded-2xl mb-4 text-red-400 text-sm" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2)">
    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
</div>
@endif
@if($errors->any())
<div class="fu rounded-2xl p-4 mb-4" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25)">
    <div class="flex items-center gap-2 mb-2">
        <i class="bi bi-exclamation-triangle-fill text-red-400"></i>
        <span class="text-sm font-bold text-red-400">Terdapat {{ $errors->count() }} kesalahan:</span>
    </div>
    <ul class="space-y-1">
        @foreach($errors->all() as $error)
        <li class="text-xs text-red-400/80 flex items-start gap-1.5"><i class="bi bi-dot text-red-400 text-base leading-none"></i>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Header --}}
<div class="fu flex items-center gap-3 mb-5">
    <a href="{{ route('personnel.dashboard') }}" class="w-9 h-9 flex items-center justify-center rounded-xl transition-colors" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.5)">
        <i class="bi bi-arrow-left text-sm"></i>
    </a>
    <div>
        <div class="font-head font-bold text-white text-xl">Edit Profil</div>
        <div class="text-xs" style="color:rgba(255,255,255,0.35)">Perbarui informasi diri Anda</div>
    </div>
</div>

<form action="{{ route('personnel.profile.update') }}" method="POST" enctype="multipart/form-data">
@csrf

{{-- Avatar Upload --}}
<div class="fu1 rounded-3xl p-5 mb-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09)">
    <div class="text-xs font-bold uppercase tracking-widest mb-4" style="color:rgba(255,255,255,0.35)">Foto Profil</div>
    <div class="flex items-center gap-4">
        <div id="previewWrap" style="width:80px;height:80px;border-radius:20px;overflow:hidden;border:2px solid rgba(197,160,40,0.4);flex-shrink:0">
            @if($personnel->photo)
                <img id="previewImg" src="{{ asset('storage/'.$personnel->photo) }}" style="width:100%;height:100%;object-fit:cover">
            @else
                <div id="previewImg" class="w-full h-full flex items-center justify-center font-head text-3xl font-bold text-gold" style="background:linear-gradient(135deg,rgba(197,160,40,0.2),rgba(139,26,42,0.4))">
                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                </div>
            @endif
        </div>
        <div class="flex-1">
            <label for="photo" class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl cursor-pointer font-bold text-sm transition-colors" style="background:rgba(197,160,40,0.1);border:1px solid rgba(197,160,40,0.25);color:#C5A028">
                <i class="bi bi-camera-fill"></i> Ganti Foto
            </label>
            <input id="photo" type="file" name="photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">
            <div class="mt-2 text-xs" style="color:rgba(255,255,255,0.3)">JPG, PNG, WebP · Maks 2MB</div>
        </div>
    </div>
</div>

{{-- Data Diri --}}
<div class="fu2 rounded-3xl p-5 mb-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09)">
    <div class="text-xs font-bold uppercase tracking-widest mb-4" style="color:rgba(255,255,255,0.35)">Data Diri</div>

    <div class="flex flex-col gap-4">
        {{-- Nama Lengkap --}}
        <div>
            <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:rgba(255,255,255,0.4)">Nama Lengkap <span class="text-red-400">*</span></label>
            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                   class="w-full px-4 py-3 rounded-xl text-sm font-medium text-white outline-none transition-all"
                   style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:#fff"
                   placeholder="Nama lengkap Anda">
            @error('name')<div class="mt-1 text-xs text-red-400">{{ $message }}</div>@enderror
        </div>

        {{-- Nama Panggung --}}
        <div>
            <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:rgba(255,255,255,0.4)">Nama Panggung / Stage Name</label>
            <input type="text" name="stage_name" value="{{ old('stage_name', $personnel->stage_name) }}"
                   class="w-full px-4 py-3 rounded-xl text-sm font-medium text-white outline-none transition-all"
                   style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1)"
                   placeholder="Nama tampilan di sistem">
            <div class="mt-1 text-xs" style="color:rgba(255,255,255,0.25)">Dikosongkan = gunakan nama lengkap</div>
        </div>

        {{-- No HP --}}
        <div>
            <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:rgba(255,255,255,0.4)">No. WhatsApp</label>
            <input type="tel" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                   class="w-full px-4 py-3 rounded-xl text-sm font-medium text-white outline-none transition-all"
                   style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1)"
                   placeholder="08xxxxxxxxxx">
        </div>

        {{-- Bio --}}
        <div>
            <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:rgba(255,255,255,0.4)">Bio / Deskripsi Singkat</label>
            <textarea name="bio" rows="3"
                      class="w-full px-4 py-3 rounded-xl text-sm font-medium text-white outline-none transition-all resize-none"
                      style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1)"
                      placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', $personnel->bio) }}</textarea>
            <div class="mt-1 text-xs" style="color:rgba(255,255,255,0.25)">Maks 500 karakter</div>
        </div>
    </div>
</div>

{{-- Info Read-only --}}
<div class="fu3 rounded-3xl p-5 mb-6" style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.07)">
    <div class="text-xs font-bold uppercase tracking-widest mb-4" style="color:rgba(255,255,255,0.25)">Info Sanggar (Dikelola Admin)</div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <div class="text-xs" style="color:rgba(255,255,255,0.3)">Spesialisasi</div>
            <div class="font-bold text-white capitalize mt-0.5">{{ ucfirst(str_replace('_',' ',$personnel->specialty)) }}</div>
        </div>
        <div>
            <div class="text-xs" style="color:rgba(255,255,255,0.3)">Status</div>
            <div class="font-bold mt-0.5" style="color:{{ $personnel->is_backup?'rgba(255,255,255,0.4)':'#4ade80' }}">
                {{ $personnel->is_backup ? 'Cadangan' : 'Personel Utama' }}
            </div>
        </div>
        @if($personnel->has_day_job && $personnel->day_job_desc)
        <div class="col-span-2">
            <div class="text-xs" style="color:rgba(255,255,255,0.3)">Pekerjaan Utama</div>
            <div class="font-semibold text-white mt-0.5">{{ $personnel->day_job_desc }}</div>
        </div>
        @endif
    </div>
</div>

{{-- Submit --}}
<div class="fu4">
    <button type="submit" class="w-full flex items-center justify-center gap-2.5 p-4 rounded-2xl font-bold text-sm transition-all hover:-translate-y-px"
            style="background:linear-gradient(135deg,#8B1A2A,#5C0E19);border:1px solid rgba(197,160,40,0.35);color:#C5A028;box-shadow:0 4px 20px rgba(139,26,42,0.35)">
        <i class="bi bi-check-lg text-lg"></i> Simpan Perubahan
    </button>
</div>

</form>

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
