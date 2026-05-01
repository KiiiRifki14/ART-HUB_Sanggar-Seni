@extends('layouts.admin')

@section('title', 'Personnel – ART-HUB')
@section('page_title', 'Personnel Management')
@section('page_subtitle', 'Kelola kru dan personel Sanggar Cahaya Gumilang.')

@section('content')

@php
    $total  = $personnel->count();
    $active = $personnel->where('is_active', true)->count();
    $backup = $personnel->where('is_backup', true)->count();
    $dayJob = $personnel->where('has_day_job', true)->count();
    $specialtyMap = [
        'penari'      => ['Penari', 'bi-person-arms-up', 'bg-pink-500/10 text-pink-700 border-pink-500/20'],
        'pemusik'     => ['Pemusik', 'bi-music-note-beamed', 'bg-blue-500/10 text-blue-700 border-blue-500/20'],
        'multi_talent'=> ['Multi Talent', 'bi-stars', 'bg-secondary/10 text-secondary border-secondary/20'],
    ];
@endphp

{{-- Stat bar --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-people-fill text-2xl text-secondary mb-2 block"></i>
        <div class="font-headline text-3xl font-bold text-primary mb-1">{{ $total }}</div>
        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Total Personel</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-5 border border-green-500/20 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-person-check-fill text-2xl text-green-600 mb-2 block"></i>
        <div class="font-headline text-3xl font-bold text-green-600 mb-1">{{ $active }}</div>
        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Aktif</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-person-badge-fill text-2xl text-blue-500 mb-2 block"></i>
        <div class="font-headline text-3xl font-bold text-blue-600 mb-1">{{ $backup }}</div>
        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Cadangan</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-5 border border-orange-500/20 shadow-[0_8px_20px_rgba(54,31,26,0.03)] text-center">
        <i class="bi bi-briefcase-fill text-2xl text-orange-500 mb-2 block"></i>
        <div class="font-headline text-3xl font-bold text-orange-600 mb-1">{{ $dayJob }}</div>
        <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold">Punya Day-Job</div>
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

{{-- Table --}}
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
    <table class="w-full">
        <thead class="bg-surface-container-low">
            <tr>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left w-8">#</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Personel</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Spesialisasi</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Kontak</th>
                <th class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold px-6 py-4 text-left">Day-Job</th>
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
            <tr class="hover:bg-surface-container-low/50 transition-colors">
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
                    @if($p->is_active)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-green-500/10 text-green-600 border border-green-500/20 font-label text-[0.65rem] font-bold uppercase tracking-wider">
                        <i class="bi bi-check-circle-fill"></i> Aktif
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-orange-500/10 text-orange-600 border border-orange-500/20 font-label text-[0.65rem] font-bold uppercase tracking-wider"
                          title="Edit untuk mengaktifkan">
                        <i class="bi bi-hourglass-split"></i> Pending
                    </span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.personnel.edit', $p->id) }}"
                           class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-secondary hover:text-white transition-all"
                           title="Edit">
                            <i class="bi bi-pencil-fill text-sm"></i>
                        </a>
                        @php $delMsg = "Hapus " . addslashes($p->user->name ?? 'personel ini') . "? Data tidak bisa dikembalikan."; @endphp
                        <form method="POST" action="{{ route('admin.personnel.destroy', $p->id) }}" class="m-0"
                              onsubmit="return confirm('{{ $delMsg }}')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-red-500 hover:text-white transition-all"
                                    title="Hapus">
                                <i class="bi bi-trash3-fill text-sm"></i>
                            </button>
                        </form>
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

@endsection
