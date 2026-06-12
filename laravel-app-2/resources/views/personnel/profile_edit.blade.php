@extends('layouts.personnel')
@section('title', 'Kartu Identitas – Portal Kru ART-HUB')

@section('content')
<style>
    /* ── Section card ── */
    .id-card {
        background: var(--clr-card);
        border-radius: 20px;
        border: 1px solid rgba(197,160,40,0.15);
        box-shadow: 0 4px 24px rgba(54,31,26,0.05);
        overflow: hidden;
        transition: box-shadow 0.4s var(--easing-spring);
    }
    .id-card:hover { box-shadow: 0 8px 32px rgba(54,31,26,0.08); }
    .id-card-header {
        display: flex; align-items: center; gap: 10px;
        padding: 16px 20px;
        border-bottom: 1px solid rgba(197,160,40,0.1);
        background: rgba(197,160,40,0.03);
    }
    .id-card-header-icon {
        width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center;
        justify-content: center; font-size: 0.9rem; flex-shrink: 0;
        background: linear-gradient(135deg, rgba(139,26,42,0.1), rgba(197,160,40,0.1));
        color: var(--clr-maroon-500);
    }
    .id-card-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1rem; font-weight: 700; color: #1A1817;
    }
    .id-card-body { padding: 20px; }

    /* ── Floating label input ── */
    .fl-wrap { position: relative; }
    .fl-label {
        display: block; font-size: 0.6rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.12em; color: #847B78;
        margin-bottom: 6px;
    }
    .fl-input {
        width: 100%; background: #F4F2EE;
        border: none; border-bottom: 2px solid rgba(197,160,40,0.5);
        padding: 11px 14px 9px; border-radius: 8px 8px 0 0;
        font-family: 'Inter', sans-serif; font-size: 0.9rem; color: #1A1817;
        outline: none; transition: all 0.35s var(--easing-spring);
    }
    .fl-input:focus { border-bottom-color: var(--clr-maroon-500); background: #FFFDF0; }
    .fl-input::placeholder { color: #B8B0AD; }
    .fl-input:read-only { opacity: 0.55; cursor: not-allowed; }
    textarea.fl-input { border-radius: 8px; border: 1px solid rgba(197,160,40,0.3); border-bottom: 2px solid rgba(197,160,40,0.5); resize: vertical; min-height: 80px; }
    textarea.fl-input:focus { border-bottom-color: var(--clr-maroon-500); background: #FFFDF0; }

    /* ── Day checkbox pills ── */
    .day-pill-label {
        display: flex; align-items: center; gap: 6px; cursor: pointer;
        padding: 7px 11px; border-radius: 8px;
        border: 1px solid rgba(0,0,0,0.1); background: #fff;
        font-size: 0.78rem; font-weight: 500; color: #4D4946;
        transition: all 0.3s var(--easing-spring);
    }
    .day-pill-label:hover { background: rgba(197,160,40,0.08); border-color: rgba(197,160,40,0.3); }
    .day-pill-label input[type="checkbox"] { accent-color: var(--clr-maroon-500); }
    .day-pill-label:has(input:checked) {
        background: rgba(139,26,42,0.07);
        border-color: rgba(139,26,42,0.3);
        color: var(--clr-maroon-500); font-weight: 600;
    }

    /* ── Save button ── */
    .btn-save-primary {
        width: 100%; padding: 14px; border-radius: 12px; cursor: pointer;
        background: linear-gradient(135deg, var(--clr-maroon-500), var(--clr-maroon-700));
        color: var(--clr-gold-300); font-family: 'Inter', sans-serif;
        font-weight: 700; font-size: 0.9rem;
        border: 1px solid rgba(197,160,40,0.3);
        box-shadow: 0 6px 20px rgba(139,26,42,0.3);
        transition: all 0.4s var(--easing-spring);
        display: flex; align-items: center; justify-content: center; gap: 10px;
    }
    .btn-save-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(139,26,42,0.4);
        background: linear-gradient(135deg, #A82335, #70111F);
    }
    .btn-save-secondary {
        width: 100%; padding: 13px; border-radius: 12px; cursor: pointer;
        background: transparent; border: 1.5px solid rgba(139,26,42,0.3);
        color: var(--clr-maroon-500); font-family: 'Inter', sans-serif;
        font-weight: 700; font-size: 0.88rem;
        transition: all 0.4s var(--easing-spring);
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-save-secondary:hover {
        background: rgba(139,26,42,0.06);
        border-color: var(--clr-maroon-500);
        transform: translateY(-1px);
    }

    /* ── Profile hero ── */
    .profile-hero {
        background: linear-gradient(135deg, var(--clr-maroon-900), var(--clr-maroon-700));
        border-radius: 20px;
        padding: 28px 24px;
        border: 1px solid rgba(197,160,40,0.2);
        box-shadow: 0 8px 32px rgba(30,13,10,0.2);
        position: relative; overflow: hidden;
    }
    .profile-hero::before {
        content: '';
        position: absolute; top: -80px; right: -80px;
        width: 220px; height: 220px; border-radius: 50%;
        background: radial-gradient(circle, rgba(197,160,40,0.12), transparent);
        pointer-events: none;
    }

    /* ── Info readonly row ── */
    .info-row {
        display: flex; align-items: center; gap: 12px;
        padding: 11px 0; border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .info-row:last-child { border-bottom: none; }
    .info-row-icon { font-size: 0.85rem; color: var(--clr-gold-500); width: 18px; text-align: center; flex-shrink: 0; }
    .info-row-label { font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #847B78; }
    .info-row-value { font-size: 0.9rem; font-weight: 600; color: #1A1817; }
</style>

{{-- ══ FLASH MESSAGES ══ --}}
@foreach(['success'=>'#16a34a', 'error'=>'#dc2626', 'warning'=>'#ea580c'] as $type => $clr)
@if(session($type))
<div class="fu rounded-2xl p-3.5 mb-4 flex items-center gap-3 text-sm font-semibold"
     style="background:{{ $clr }}14;border:1px solid {{ $clr }}33;color:{{ $clr }}">
    <i class="bi bi-{{ $type === 'success' ? 'check-circle-fill' : ($type === 'error' ? 'x-circle-fill' : 'exclamation-triangle-fill') }}"></i>
    {{ session($type) }}
</div>
@endif
@endforeach
@if($errors->any())
<div class="fu rounded-2xl p-4 mb-4" style="background:#fef2f2;border:1px solid #fecaca">
    <div class="flex items-center gap-2 mb-2">
        <i class="bi bi-exclamation-triangle-fill text-red-600"></i>
        <span class="text-sm font-bold text-red-600">{{ $errors->count() }} kesalahan ditemukan:</span>
    </div>
    <ul class="space-y-1">
        @foreach($errors->all() as $error)
        <li class="text-xs text-red-600/80 flex items-start gap-1.5"><i class="bi bi-dot text-red-500 text-base leading-none"></i>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- ══ SECTION 1: PROFILE HERO CARD ══ --}}
<div class="fu profile-hero mb-5">
    <div class="flex items-center gap-5 relative">
        {{-- Avatar --}}
        <div style="position:relative;flex-shrink:0">
            <div id="previewWrap"
                 style="width:80px;height:80px;border-radius:18px;overflow:hidden;border:2px solid rgba(197,160,40,0.5);box-shadow:0 6px 20px rgba(0,0,0,0.3)">
                @if($personnel->photo)
                    <img id="previewImg" src="{{ asset('storage/'.$personnel->photo) }}" style="width:100%;height:100%;object-fit:cover">
                @else
                    <div id="previewImg" class="w-full h-full flex items-center justify-center"
                         style="background:linear-gradient(135deg,rgba(197,160,40,0.25),rgba(139,26,42,0.4));font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;color:var(--clr-gold-300)">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            {{-- Online dot --}}
            <div style="position:absolute;bottom:-2px;right:-2px;width:14px;height:14px;background:#22c55e;border-radius:50%;border:2px solid var(--clr-maroon-900)"></div>
        </div>

        {{-- Info --}}
        <div style="flex:1;min-width:0">
            <div style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:700;color:#fff;line-height:1.1;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                {{ $personnel->stage_name ?? Auth::user()->name }}
            </div>
            @if($personnel->stage_name && $personnel->stage_name !== Auth::user()->name)
            <div style="font-size:0.7rem;color:rgba(255,255,255,0.4);margin-bottom:6px">{{ Auth::user()->name }}</div>
            @endif
            <div style="display:flex;flex-wrap:wrap;gap:6px;align-items:center">
                <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:99px;background:rgba(197,160,40,0.15);color:var(--clr-gold-300);font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;border:1px solid rgba(197,160,40,0.3)">
                    <i class="bi bi-music-note-list"></i>
                    {{ ucfirst(str_replace('_', ' ', $personnel->specialty ?? 'Personel')) }}
                </span>
                @if($personnel->is_backup)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:99px;background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.4);font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;border:1px solid rgba(255,255,255,0.1)">
                    Cadangan
                </span>
                @else
                <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:99px;background:rgba(34,197,94,0.12);color:#22c55e;font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;border:1px solid rgba(34,197,94,0.25)">
                    <i class="bi bi-star-fill" style="font-size:0.5rem"></i> Utama
                </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Stats row --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);margin-top:20px;padding-top:16px;border-top:1px solid rgba(255,255,255,0.08)">
        <div style="text-align:center">
            <div style="font-family:'Cormorant Garamond',serif;font-size:1.7rem;font-weight:700;color:var(--clr-gold-300);line-height:1">{{ $personnel->events()->count() }}</div>
            <div style="font-size:0.55rem;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.4);margin-top:3px">Total Event</div>
        </div>
        <div style="text-align:center;border-left:1px solid rgba(255,255,255,0.08);border-right:1px solid rgba(255,255,255,0.08)">
            <div style="font-family:'Cormorant Garamond',serif;font-size:1.7rem;font-weight:700;color:#fff;line-height:1">{{ $personnel->status === 'aktif' ? '✓' : '–' }}</div>
            <div style="font-size:0.55rem;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.4);margin-top:3px">Status</div>
        </div>
        <div style="text-align:center">
            <div style="font-family:'Cormorant Garamond',serif;font-size:1.7rem;font-weight:700;color:var(--clr-gold-300);line-height:1">{{ $personnel->specialty ? '✓' : '–' }}</div>
            <div style="font-size:0.55rem;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.4);margin-top:3px">Spesialisasi</div>
        </div>
    </div>
</div>

{{-- ══ MAIN FORM: DATA DIRI + PEKERJAAN ══ --}}
<form action="{{ route('personnel.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- ── Section 2: Foto Profil ── --}}
    <div class="fu1 id-card mb-4">
        <div class="id-card-header">
            <div class="id-card-header-icon"><i class="bi bi-camera-fill"></i></div>
            <div class="id-card-title">Foto Profil</div>
        </div>
        <div class="id-card-body">
            <div style="display:flex;align-items:center;gap:16px">
                <div id="previewWrap2"
                     style="width:70px;height:70px;border-radius:16px;overflow:hidden;border:2px solid rgba(197,160,40,0.3);flex-shrink:0">
                    @if($personnel->photo)
                        <img src="{{ asset('storage/'.$personnel->photo) }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        <div style="width:100%;height:100%;background:linear-gradient(135deg,rgba(197,160,40,0.15),rgba(139,26,42,0.1));display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:1.6rem;font-weight:700;color:var(--clr-gold-500)">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div style="flex:1">
                    <label for="photo"
                           style="display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:10px;cursor:pointer;font-weight:700;font-size:0.85rem;border:1.5px solid rgba(197,160,40,0.4);color:var(--clr-gold-500);transition:all 0.3s var(--easing-spring)">
                        <i class="bi bi-cloud-arrow-up-fill"></i> Pilih Foto Baru
                    </label>
                    <input id="photo" type="file" name="photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                    <div style="margin-top:6px;font-size:0.68rem;color:#847B78">JPG, PNG, WebP · Maksimal 2MB</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Section 3: Data Diri ── --}}
    <div class="fu2 id-card mb-4">
        <div class="id-card-header">
            <div class="id-card-header-icon"><i class="bi bi-person-lines-fill"></i></div>
            <div class="id-card-title">Data Diri</div>
        </div>
        <div class="id-card-body">
            <div style="display:flex;flex-direction:column;gap:16px">
                <div>
                    <label class="fl-label">Nama Lengkap <span style="color:#ef4444">*</span></label>
                    <input type="text" name="name" class="fl-input"
                           value="{{ old('name', Auth::user()->name) }}" required
                           placeholder="Nama lengkap sesuai identitas">
                </div>
                <div>
                    <label class="fl-label">Nama Panggung / Stage Name <span style="color:#847B78;font-weight:400;text-transform:none;letter-spacing:0">(opsional)</span></label>
                    <input type="text" name="stage_name" class="fl-input"
                           value="{{ old('stage_name', $personnel->stage_name) }}"
                           placeholder="Dikosongkan = gunakan nama lengkap">
                </div>
                <div>
                    <label class="fl-label">No. WhatsApp <span style="color:#847B78;font-weight:400;text-transform:none;letter-spacing:0">(opsional)</span></label>
                    <input type="tel" name="phone" class="fl-input"
                           value="{{ old('phone', Auth::user()->phone) }}"
                           placeholder="08xxxxxxxxxx">
                </div>
                <div>
                    <label class="fl-label">Bio / Deskripsi Singkat <span style="color:#847B78;font-weight:400;text-transform:none;letter-spacing:0">(opsional)</span></label>
                    <textarea name="bio" class="fl-input" rows="3"
                              placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', $personnel->bio) }}</textarea>
                    <div style="margin-top:4px;font-size:0.65rem;color:#847B78">Maks. 500 karakter</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Section 4: Info Sanggar (Read-only) ── --}}
    <div class="fu3 id-card mb-4">
        <div class="id-card-header">
            <div class="id-card-header-icon"><i class="bi bi-building-fill"></i></div>
            <div class="id-card-title">Info Sanggar</div>
            <span style="margin-left:auto;font-size:0.6rem;color:#847B78;font-weight:600;background:#F4F2EE;padding:3px 8px;border-radius:6px">Dikelola Admin</span>
        </div>
        <div class="id-card-body" style="padding:16px 20px">
            <div class="info-row">
                <i class="bi bi-music-note-list info-row-icon"></i>
                <div>
                    <div class="info-row-label">Spesialisasi</div>
                    <div class="info-row-value capitalize">{{ ucfirst(str_replace('_', ' ', $personnel->specialty ?? '–')) }}</div>
                </div>
            </div>
            <div class="info-row">
                <i class="bi bi-patch-check-fill info-row-icon"></i>
                <div>
                    <div class="info-row-label">Status Anggota</div>
                    <div class="info-row-value" style="color:{{ $personnel->is_backup ? '#847B78' : '#16a34a' }}">
                        {{ $personnel->is_backup ? 'Personel Cadangan' : 'Personel Utama' }}
                    </div>
                </div>
            </div>
            <div class="info-row">
                <i class="bi bi-calendar-plus info-row-icon"></i>
                <div>
                    <div class="info-row-label">Bergabung Sejak</div>
                    <div class="info-row-value">{{ $personnel->created_at->translatedFormat('d F Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Section 5: Kehadiran & Ketersediaan ── --}}
    <div class="fu4 id-card mb-5"
         x-data="{ hasDayJob: {{ old('has_day_job', $personnel->has_day_job) ? 'true' : 'false' }} }">
        <div class="id-card-header">
            <div class="id-card-header-icon"><i class="bi bi-briefcase-fill"></i></div>
            <div class="id-card-title">Pekerjaan / Kegiatan Utama</div>
        </div>
        <div class="id-card-body">
            <p style="font-size:0.78rem;color:#847B78;margin:0 0 14px;line-height:1.5">
                Isi agar sistem dapat mendeteksi potensi bentrok jadwal latihan dengan kegiatan harianmu.
            </p>

            {{-- Toggle --}}
            <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:12px 14px;border-radius:12px;background:#F4F2EE;border:1px solid rgba(0,0,0,0.06);margin-bottom:16px">
                <input type="hidden" name="has_day_job" value="0">
                <input type="checkbox" name="has_day_job" value="1" id="has_day_job"
                       style="width:16px;height:16px;border-radius:4px;accent-color:var(--clr-maroon-500);cursor:pointer"
                       x-model="hasDayJob"
                       {{ old('has_day_job', $personnel->has_day_job) ? 'checked' : '' }}>
                <span style="font-size:0.85rem;font-weight:600;color:#1A1817">Saya punya pekerjaan / sekolah / kegiatan rutin di luar sanggar</span>
            </label>

            {{-- Conditional fields --}}
            <div x-show="hasDayJob" x-transition style="display:flex;flex-direction:column;gap:16px">
                <div>
                    <label class="fl-label">Nama Pekerjaan / Institusi <span style="color:#ef4444">*</span></label>
                    <input type="text" name="day_job_name" class="fl-input"
                           value="{{ old('day_job_name', $personnel->day_job_name ?? $personnel->day_job_desc) }}"
                           placeholder="Contoh: Kantor BRI, SMA N 1 Bandung, Kuliah Unpad..."
                           x-bind:required="hasDayJob">
                    <div style="margin-top:4px;font-size:0.65rem;color:#847B78">Nama ini muncul di pesan peringatan bentrok jadwal.</div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div>
                        <label class="fl-label">Jam Mulai <span style="color:#ef4444">*</span></label>
                        <input type="time" name="day_job_start" class="fl-input"
                               value="{{ old('day_job_start', $personnel->day_job_start ? \Carbon\Carbon::parse($personnel->day_job_start)->format('H:i') : '') }}"
                               x-bind:required="hasDayJob">
                    </div>
                    <div>
                        <label class="fl-label">Jam Selesai <span style="color:#ef4444">*</span></label>
                        <input type="time" name="day_job_end" class="fl-input"
                               value="{{ old('day_job_end', $personnel->day_job_end ? \Carbon\Carbon::parse($personnel->day_job_end)->format('H:i') : '') }}"
                               x-bind:required="hasDayJob">
                    </div>
                </div>

                <div>
                    <label class="fl-label">Hari Kegiatan <span style="color:#ef4444">*</span></label>
                    @php $selectedDays = old('day_job_days', $personnel->day_job_days ?? []); @endphp
                    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-top:4px">
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $day)
                        <label class="day-pill-label">
                            <input type="checkbox" name="day_job_days[]" value="{{ $day }}"
                                   {{ in_array($day, (array)$selectedDays) ? 'checked' : '' }}>
                            {{ $day }}
                        </label>
                        @endforeach
                    </div>
                    <div style="margin-top:6px;font-size:0.65rem;color:#847B78">Kosongkan jika berlaku setiap hari.</div>
                </div>

                <div style="padding:10px 14px;border-radius:10px;background:rgba(197,160,40,0.08);border:1px solid rgba(197,160,40,0.2)">
                    <p style="font-size:0.7rem;color:#705d00;margin:0"><i class="bi bi-lightbulb-fill me-1"></i>Contoh: Kuliah 08:00–16:00 · Kerja kantor 07:30–15:30 · Sekolah 07:00–14:00</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── MAIN SAVE BUTTON ── --}}
    <div class="fu5 mb-4">
        <button type="submit" class="btn-save-primary">
            <i class="bi bi-check-circle-fill" style="font-size:1.1rem"></i>
            Simpan Kartu Identitas
        </button>
    </div>
</form>

{{-- ══ FORM GANTI PASSWORD (terpisah, route berbeda) ══ --}}
<form action="{{ route('personnel.profile.password') }}" method="POST">
    @csrf
    <div class="id-card mb-6">
        <div class="id-card-header">
            <div class="id-card-header-icon"><i class="bi bi-shield-lock-fill"></i></div>
            <div class="id-card-title">Keamanan – Ganti Password</div>
        </div>
        <div class="id-card-body">
            <div style="display:flex;flex-direction:column;gap:14px">
                <div>
                    <label class="fl-label">Password Saat Ini <span style="color:#ef4444">*</span></label>
                    <input type="password" name="current_password" class="fl-input" required placeholder="••••••••">
                </div>
                <div>
                    <label class="fl-label">Password Baru <span style="color:#ef4444">*</span></label>
                    <input type="password" name="password" class="fl-input" required placeholder="Min. 8 karakter">
                    <div style="margin-top:4px;font-size:0.65rem;color:#847B78">Min. 8 karakter (huruf besar, kecil, angka)</div>
                </div>
                <div>
                    <label class="fl-label">Konfirmasi Password Baru <span style="color:#ef4444">*</span></label>
                    <input type="password" name="password_confirmation" class="fl-input" required placeholder="Ulangi password baru">
                </div>
            </div>
            <div style="margin-top:18px">
                <button type="submit" class="btn-save-secondary">
                    <i class="bi bi-key-fill"></i> Perbarui Password
                </button>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            // Update both preview wraps (hero + form)
            const hero = document.getElementById('previewWrap');
            const form = document.getElementById('previewWrap2');
            const imgHtml = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover">`;
            if (hero) hero.innerHTML = imgHtml;
            if (form) form.innerHTML = imgHtml;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
