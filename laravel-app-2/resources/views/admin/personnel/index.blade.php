@extends('layouts.admin')

@section('title', 'Personel – ART-HUB')
@section('page_title', 'Manajemen Personel')
@section('page_subtitle', 'Kelola kru dan personel Sanggar Cahaya Gumilang.')

@section('content')

@php
    $total       = $personnel->count();
    $active      = $personnel->where('status', 'active')->count();
    $pending     = $personnel->where('status', 'pending_verification')->count();
    $deactivated = $personnel->where('status', 'deactivated')->count();
    $specialtyMap = [
        'penari'      => ['Penari', 'bi-person-arms-up', 'bg-pink-500/10 text-pink-700 border-pink-500/20'],
        'pemusik'     => ['Pemusik', 'bi-music-note-beamed', 'bg-blue-500/10 text-blue-700 border-blue-500/20'],
        'multi_talent'=> ['Multi Talent', 'bi-stars', 'bg-secondary/10 text-secondary border-secondary/20'],
    ];
@endphp

{{-- Temp Password Alert --}}
@if (session('temp_password'))
<div class="bg-yellow-50 border border-yellow-300 text-yellow-900 rounded-lg p-4 mb-4 flex items-start gap-3">
    <svg class="w-5 h-5 mt-0.5 shrink-0 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
    <div>
        <p class="font-semibold">Password Sementara untuk <span class="underline">{{ session('temp_password_name') }}</span></p>
        <p class="mt-1">Berikan password ini kepada personel yang bersangkutan:</p>
        <code class="mt-1 inline-block bg-yellow-100 border border-yellow-400 px-3 py-1 rounded text-base font-mono tracking-widest select-all">
            {{ session('temp_password') }}
        </code>
        <p class="text-xs mt-2 text-yellow-700">⚠ Password ini hanya ditampilkan sekali. Salin sebelum menutup halaman ini.</p>
    </div>
</div>
@endif


{{-- ======== BANNER: Pending Approval ======== --}}
@if($pending > 0)
<div class="mb-6 p-4 rounded-xl border border-orange-500/40 bg-orange-500/5 flex items-center gap-4">
    <div class="w-10 h-10 rounded-full bg-orange-500/10 flex-shrink-0 flex items-center justify-center">
        <i class="bi bi-person-exclamation text-orange-500 text-xl"></i>
    </div>
    <div class="flex-1">
        <div class="font-body font-bold text-orange-700 text-sm">Ada {{ $pending }} Pendaftaran Personel Menunggu Persetujuan!</div>
        <div class="font-body text-xs text-orange-600/80 mt-0.5">Personel baru mendaftar mandiri dan belum bisa mengakses portal mereka. Tinjau dan setujui di bawah.</div>
    </div>
    <a href="#table-section" class="flex-shrink-0 px-4 py-2 bg-orange-500 text-white rounded-lg font-label text-xs font-bold uppercase tracking-wider hover:bg-orange-600 transition-colors">
        Tinjau Sekarang
    </a>
</div>
@endif

{{-- Stat bar --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-surface-container-lowest rounded-xl p-4 sm:p-5 border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-people-fill text-xl sm:text-2xl text-secondary mb-1.5 sm:mb-2 block"></i>
        <div class="font-headline text-2xl sm:text-3xl font-bold text-primary mb-0.5 sm:mb-1">{{ $total }}</div>
        <div class="font-label text-[0.6rem] sm:text-[0.65rem] uppercase tracking-widest text-outline font-bold">Total Personel</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-4 sm:p-5 border border-green-500/20 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-person-check-fill text-xl sm:text-2xl text-green-600 mb-1.5 sm:mb-2 block"></i>
        <div class="font-headline text-2xl sm:text-3xl font-bold text-green-600 mb-0.5 sm:mb-1">{{ $active }}</div>
        <div class="font-label text-[0.6rem] sm:text-[0.65rem] uppercase tracking-widest text-outline font-bold">Aktif</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-4 sm:p-5 border {{ $pending > 0 ? 'border-orange-500/30' : 'border-outline-variant/30' }} shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-person-fill-exclamation text-xl sm:text-2xl {{ $pending > 0 ? 'text-orange-500' : 'text-outline' }} mb-1.5 sm:mb-2 block"></i>
        <div class="font-headline text-2xl sm:text-3xl font-bold {{ $pending > 0 ? 'text-orange-600' : 'text-outline' }} mb-0.5 sm:mb-1">{{ $pending }}</div>
        <div class="font-label text-[0.6rem] sm:text-[0.65rem] uppercase tracking-widest text-outline font-bold">Menunggu Verifikasi</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-4 sm:p-5 border {{ $deactivated > 0 ? 'border-red-500/20' : 'border-outline-variant/30' }} shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-person-dash-fill text-xl sm:text-2xl {{ $deactivated > 0 ? 'text-red-500' : 'text-outline' }} mb-1.5 sm:mb-2 block"></i>
        <div class="font-headline text-2xl sm:text-3xl font-bold {{ $deactivated > 0 ? 'text-red-600' : 'text-outline' }} mb-0.5 sm:mb-1">{{ $deactivated }}</div>
        <div class="font-label text-[0.6rem] sm:text-[0.65rem] uppercase tracking-widest text-outline font-bold">Dinonaktifkan</div>
    </div>
</div>

{{-- Header --}}
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="font-headline text-xl text-primary font-semibold">Daftar Personel</h2>
    </div>
    <a href="{{ route('admin.personnel.create') }}"
       class="bg-gradient-to-br from-primary-container to-primary text-white px-5 py-2.5 rounded-lg font-label text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-md flex items-center gap-2">
        <i class="bi bi-person-plus-fill"></i> Tambah Personel
    </a>
</div>

{{-- ══ ALPINE CONTAINER UNTUK TABS ══ --}}
<div x-data="{ activeTab: 'semua' }" id="table-section">

    {{-- ══ TABS NAVIGATION ══ --}}
    <div class="flex flex-wrap gap-2 mb-6 p-1.5 bg-surface-container-low/50 rounded-xl border border-outline-variant/30 inline-flex">
        <button @click="activeTab = 'semua'" 
                :class="activeTab === 'semua' ? 'bg-white text-primary shadow-sm ring-1 ring-outline-variant/30 font-bold' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container font-medium'" 
                class="px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2">
            Semua
            <span class="px-2 py-0.5 text-[0.65rem] font-bold rounded-full bg-surface-container text-on-surface">{{ $total }}</span>
        </button>
        <button @click="activeTab = 'active'" 
                :class="activeTab === 'active' ? 'bg-white text-green-700 shadow-sm ring-1 ring-outline-variant/30 font-bold' : 'text-on-surface-variant hover:text-green-700 hover:bg-surface-container font-medium'" 
                class="px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2">
            Aktif
            <span class="px-2 py-0.5 text-[0.65rem] font-bold rounded-full bg-green-500/10 text-green-700">{{ $active }}</span>
        </button>
        <button @click="activeTab = 'pending_verification'" 
                :class="activeTab === 'pending_verification' ? 'bg-white text-orange-700 shadow-sm ring-1 ring-outline-variant/30 font-bold' : 'text-on-surface-variant hover:text-orange-700 hover:bg-surface-container font-medium'" 
                class="px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2">
            Menunggu Verifikasi
            @if($pending > 0)<span class="px-2 py-0.5 text-[0.65rem] font-bold rounded-full bg-orange-500 text-white shadow-sm">{{ $pending }}</span>@endif
        </button>
        <button @click="activeTab = 'deactivated'" 
                :class="activeTab === 'deactivated' ? 'bg-white text-red-700 shadow-sm ring-1 ring-outline-variant/30 font-bold' : 'text-on-surface-variant hover:text-red-700 hover:bg-surface-container font-medium'" 
                class="px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2">
            Dinonaktifkan
            @if($deactivated > 0)<span class="px-2 py-0.5 text-[0.65rem] font-bold rounded-full bg-red-500/10 text-red-700">{{ $deactivated }}</span>@endif
        </button>
    </div>

    {{-- ══ TABLE (Desktop) ══ --}}
    <div class="hidden md:block bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden overflow-x-auto mb-10">
        <table class="w-full min-w-[900px]">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left w-8">#</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Personel</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Spesialisasi</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Kontak</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Pekerjaan Utama</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Status</th>
                    <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($personnel as $idx => $p)
                @php
                    [$specLabel, $specIcon, $specClass] = $specialtyMap[$p->specialty] ?? [$p->specialty, 'bi-person', 'bg-surface-container text-outline border-outline-variant/30'];
                    $initials = strtoupper(substr($p->user->name ?? 'P', 0, 2));
                @endphp
                <tr class="hover:bg-surface-container-low/50 transition-colors" 
                    x-show="activeTab === 'semua' || activeTab === '{{ $p->status }}'" x-transition.opacity>
                    <td class="px-6 py-4 font-label text-xs text-outline">{{ $idx + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center
                                        bg-gradient-to-br from-primary-container to-primary text-white font-bold text-xs">
                                {{ $initials }}
                            </div>
                            <div>
                                <div class="font-body font-semibold text-on-surface text-sm">{{ $p->user->name ?? 'Tanpa Akun' }}</div>
                                <div class="font-label text-xs text-outline">{{ $p->user->email ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded border font-label text-[0.65rem] font-bold uppercase tracking-wider {{ $specClass }}">
                            <i class="bi {{ $specIcon }}"></i> {{ $specLabel }}
                        </span>
                        @if($p->is_backup)
                        <span class="ml-1 inline-block px-2 py-1 rounded bg-blue-500/10 text-blue-600 border border-blue-500/20 font-label text-[0.6rem] font-bold uppercase tracking-wider">Cadangan</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($p->user && $p->user->phone)
                        <a href="tel:{{ $p->user->phone }}" class="font-body text-sm text-on-surface-variant hover:text-primary transition-colors flex items-center gap-1.5">
                            <i class="bi bi-telephone-fill text-xs"></i> {{ $p->user->phone }}
                        </a>
                        @else
                        <span class="font-label text-xs text-outline">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($p->has_day_job)
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <i class="bi bi-briefcase-fill text-orange-500 text-xs"></i>
                            <span class="font-body text-sm text-on-surface">{{ $p->day_job_desc ?? 'Ada' }}</span>
                        </div>
                        <div class="font-label text-xs text-outline">
                            {{ $p->day_job_start ? \Carbon\Carbon::parse($p->day_job_start)->format('H:i') : '' }}
                            {{ $p->day_job_start ? '–' : '' }}
                            {{ $p->day_job_end ? \Carbon\Carbon::parse($p->day_job_end)->format('H:i') : '' }}
                        </div>
                        @else
                        <span class="font-label text-xs text-outline">Tidak ada</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($p->status === 'active')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-green-500/10 text-green-600 border border-green-500/20 font-label text-[0.65rem] font-bold uppercase tracking-wider">
                            <i class="bi bi-check-circle-fill"></i> Aktif
                        </span>
                        @elseif($p->status === 'pending_verification')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-orange-500/10 text-orange-600 border border-orange-500/20 font-label text-[0.65rem] font-bold uppercase tracking-wider">
                            <i class="bi bi-person-fill-exclamation"></i> Menunggu Verifikasi
                        </span>
                        @elseif($p->status === 'deactivated')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-surface-container-high text-outline border border-outline-variant/50 font-label text-[0.65rem] font-bold uppercase tracking-wider">
                            <i class="bi bi-person-dash-fill"></i> Dinonaktifkan
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            
                            @if($p->status === 'pending_verification')
                            {{-- Tombol Setujui / Tolak untuk personel pending --}}
                            <form method="POST" action="{{ route('admin.personnel.approve', $p->id) }}" class="m-0" onsubmit="return confirm('Setujui {{ addslashes($p->user->name ?? '') }} sebagai Personel Aktif?')">
                                @csrf
                                <button type="submit"
                                        class="h-8 px-3 rounded-lg bg-green-500/10 text-green-600 border border-green-500/20 hover:bg-green-500 hover:text-white transition-all font-label text-[0.65rem] font-bold uppercase tracking-wider flex items-center gap-1"
                                        title="Setujui Pendaftaran">
                                    <i class="bi bi-check-circle-fill"></i> Setujui
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.personnel.reject', $p->id) }}" class="m-0" onsubmit="return confirm('Tolak dan hapus akun {{ addslashes($p->user->name ?? '') }}? Data tidak bisa dikembalikan.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="h-8 px-3 rounded-lg bg-red-500/10 text-red-600 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all font-label text-[0.65rem] font-bold uppercase tracking-wider flex items-center gap-1"
                                        title="Tolak & Hapus Akun">
                                    <i class="bi bi-x-circle-fill"></i> Tolak
                                </button>
                            </form>

                            @elseif($p->status === 'active')
                            {{-- Tombol Edit, Nonaktifkan, Hapus untuk personel aktif --}}
                            <a href="{{ route('admin.personnel.edit', $p->id) }}"
                               class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-secondary hover:text-white transition-all"
                               title="Edit"><i class="bi bi-pencil-fill text-sm"></i></a>
                            <form method="POST" action="{{ route('admin.personnel.toggle_status', $p->id) }}" class="m-0" onsubmit="return confirm('Non-aktifkan sementara {{ addslashes($p->user->name ?? '') }}? Personel tidak akan muncul di plotting event.')">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-600 border border-orange-500/20 hover:bg-orange-500 hover:text-white transition-all"
                                        title="Non-aktifkan Sementara">
                                    <i class="bi bi-pause-circle-fill text-sm"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.personnel.destroy', $p->id) }}" class="m-0"
                                  onsubmit="return confirm('Hapus {{ addslashes($p->user->name ?? '') }}? Data tidak bisa dikembalikan.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-red-500 hover:text-white transition-all"
                                        title="Hapus">
                                    <i class="bi bi-trash3-fill text-sm"></i>
                                </button>
                            </form>

                            @elseif($p->status === 'deactivated')
                            {{-- Tombol Edit, Aktifkan Kembali, Hapus untuk personel dinonaktifkan --}}
                            <a href="{{ route('admin.personnel.edit', $p->id) }}"
                               class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-secondary hover:text-white transition-all"
                               title="Edit"><i class="bi bi-pencil-fill text-sm"></i></a>
                            <form method="POST" action="{{ route('admin.personnel.toggle_status', $p->id) }}" class="m-0" onsubmit="return confirm('Aktifkan kembali {{ addslashes($p->user->name ?? '') }}?')">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-green-500/10 text-green-600 border border-green-500/20 hover:bg-green-500 hover:text-white transition-all"
                                        title="Aktifkan Kembali">
                                    <i class="bi bi-play-circle-fill text-sm"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.personnel.destroy', $p->id) }}" class="m-0"
                                  onsubmit="return confirm('Hapus {{ addslashes($p->user->name ?? '') }}? Data tidak bisa dikembalikan.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-red-500 hover:text-white transition-all"
                                        title="Hapus">
                                    <i class="bi bi-trash3-fill text-sm"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center">
                        <i class="bi bi-person-x text-4xl text-outline mb-4 block"></i>
                        <p class="font-headline text-lg text-on-surface font-semibold mb-2">Belum ada personel</p>
                        <a href="{{ route('admin.personnel.create') }}"
                           class="inline-block mt-2 bg-gradient-to-br from-primary-container to-primary text-white px-5 py-2.5 rounded-lg font-label text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-md">
                            + Tambah Personel Pertama
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ══ MOBILE CARDS (Mobile only) ══ --}}
    <div class="md:hidden space-y-3 mb-10">
        @forelse($personnel as $p)
        @php
            [$specLabel, $specIcon, $specClass] = $specialtyMap[$p->specialty] ?? [$p->specialty, 'bi-person', 'bg-surface-container text-outline border-outline-variant/30'];
            $initials = strtoupper(substr($p->user->name ?? 'P', 0, 2));
        @endphp
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden"
             x-show="activeTab === 'semua' || activeTab === '{{ $p->status }}'" x-transition.opacity>
            <div class="flex items-center justify-between px-4 py-3 bg-surface-container-low border-b border-outline-variant/20">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-primary-container to-primary text-white font-bold text-xs">{{ $initials }}</div>
                    <div>
                        <div class="font-body font-bold text-sm text-on-surface">{{ $p->user->name ?? 'Tanpa Akun' }}</div>
                        <div class="font-label text-[0.6rem] text-outline">{{ $p->user->email ?? '-' }}</div>
                    </div>
                </div>
                @if($p->status === 'active')
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-green-500/10 text-green-600 border border-green-500/20 font-label text-[0.6rem] font-bold uppercase tracking-wider"><i class="bi bi-check-circle-fill"></i> Aktif</span>
                @elseif($p->status === 'pending_verification')
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-orange-500/10 text-orange-600 border border-orange-500/20 font-label text-[0.6rem] font-bold uppercase tracking-wider"><i class="bi bi-person-fill-exclamation"></i> Menunggu</span>
                @elseif($p->status === 'deactivated')
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-surface-container-high text-outline border border-outline-variant/50 font-label text-[0.6rem] font-bold uppercase tracking-wider"><i class="bi bi-person-dash-fill"></i> Nonaktif</span>
                @endif
            </div>
            <div class="px-4 py-3 flex flex-col gap-3">
                <div class="flex flex-wrap gap-1.5">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded border font-label text-[0.6rem] font-bold uppercase tracking-wider {{ $specClass }}"><i class="bi {{ $specIcon }}"></i> {{ $specLabel }}</span>
                    @if($p->is_backup)<span class="inline-block px-2 py-0.5 rounded bg-blue-500/10 text-blue-600 border border-blue-500/20 font-label text-[0.6rem] font-bold uppercase tracking-wider">Cadangan</span>@endif
                    @if($p->has_day_job)<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-orange-500/10 text-orange-600 border border-orange-500/20 font-label text-[0.6rem] font-bold uppercase tracking-wider"><i class="bi bi-briefcase-fill"></i> Kerja Utama</span>@endif
                </div>
                <div class="flex gap-2 justify-end mt-1">
                    @if($p->status === 'pending_verification')
                    <form method="POST" action="{{ route('admin.personnel.approve', $p->id) }}" onsubmit="return confirm('Setujui {{ addslashes($p->user->name ?? '') }}?')">@csrf
                        <button type="submit" class="h-8 px-3 rounded-lg bg-green-500/10 text-green-600 border border-green-500/20 hover:bg-green-500 hover:text-white transition-all font-label text-[0.6rem] font-bold uppercase"><i class="bi bi-check-circle-fill"></i> Setujui</button>
                    </form>
                    <form method="POST" action="{{ route('admin.personnel.reject', $p->id) }}" onsubmit="return confirm('Tolak {{ addslashes($p->user->name ?? '') }}?')">@csrf @method('DELETE')
                        <button type="submit" class="h-8 w-8 rounded-lg bg-red-500/10 text-red-600 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center"><i class="bi bi-x-circle-fill"></i></button>
                    </form>

                    @elseif($p->status === 'active')
                    <a href="{{ route('admin.personnel.edit', $p->id) }}" class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-secondary hover:text-white transition-all"><i class="bi bi-pencil-fill text-sm"></i></a>
                    <form method="POST" action="{{ route('admin.personnel.toggle_status', $p->id) }}" onsubmit="return confirm('Non-aktifkan sementara {{ addslashes($p->user->name ?? '') }}?')">@csrf @method('PATCH')
                        <button type="submit" class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-600 border border-orange-500/20 hover:bg-orange-500 hover:text-white transition-all" title="Non-aktifkan Sementara"><i class="bi bi-pause-circle-fill text-sm"></i></button>
                    </form>
                    <form method="POST" action="{{ route('admin.personnel.destroy', $p->id) }}" onsubmit="return confirm('Hapus {{ addslashes($p->user->name ?? '') }}?')">@csrf @method('DELETE')
                        <button type="submit" class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-red-500 hover:text-white transition-all"><i class="bi bi-trash3-fill text-sm"></i></button>
                    </form>

                    @elseif($p->status === 'deactivated')
                    <a href="{{ route('admin.personnel.edit', $p->id) }}" class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-secondary hover:text-white transition-all"><i class="bi bi-pencil-fill text-sm"></i></a>
                    <form method="POST" action="{{ route('admin.personnel.toggle_status', $p->id) }}" onsubmit="return confirm('Aktifkan kembali {{ addslashes($p->user->name ?? '') }}?')">@csrf @method('PATCH')
                        <button type="submit" class="w-8 h-8 rounded-lg bg-green-500/10 text-green-600 border border-green-500/20 hover:bg-green-500 hover:text-white transition-all" title="Aktifkan Kembali"><i class="bi bi-play-circle-fill text-sm"></i></button>
                    </form>
                    <form method="POST" action="{{ route('admin.personnel.destroy', $p->id) }}" onsubmit="return confirm('Hapus {{ addslashes($p->user->name ?? '') }}?')">@csrf @method('DELETE')
                        <button type="submit" class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-red-500 hover:text-white transition-all"><i class="bi bi-trash3-fill text-sm"></i></button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="py-16 flex flex-col items-center justify-center bg-surface-container-lowest border border-dashed border-outline-variant/30 rounded-xl text-center">
            <i class="bi bi-person-x text-4xl text-outline mb-3"></i>
            <p class="font-headline text-base text-on-surface font-semibold">Belum ada personel</p>
        </div>
        @endforelse
    </div>

</div>

@endsection
