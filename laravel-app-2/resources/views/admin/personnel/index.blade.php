@extends('layouts.admin')

@section('title', 'Personnel Management – ART-HUB')
@section('page_title', 'Personnel Management')
@section('page_subtitle', 'Kelola 12 kru dan personel Sanggar Cahaya Gumilang.')

@section('content')

{{-- STAT BAR --}}
@php
    $total   = $personnel->count();
    $active  = $personnel->where('is_active', true)->count();
    $backup  = $personnel->where('is_backup', true)->count();
    $dayJob  = $personnel->where('has_day_job', true)->count();
@endphp
<div class="row g-3 mb-4 animate-fade-up">
    <div class="col-6 col-md-3">
        <div class="arh-card p-3 text-center">
            <i class="bi bi-people-fill arh-gold fs-3"></i>
            <div class="fw-bold fs-4 mt-1">{{ $total }}</div>
            <small class="text-secondary">Total Personel</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="arh-card p-3 text-center" style="border-color: rgba(25,135,84,0.4);">
            <i class="bi bi-person-check-fill text-success fs-3"></i>
            <div class="fw-bold fs-4 mt-1 text-success">{{ $active }}</div>
            <small class="text-secondary">Aktif</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="arh-card p-3 text-center">
            <i class="bi bi-person-badge-fill text-info fs-3"></i>
            <div class="fw-bold fs-4 mt-1 text-info">{{ $backup }}</div>
            <small class="text-secondary">Cadangan</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="arh-card p-3 text-center" style="border-color: rgba(255,193,7,0.4);">
            <i class="bi bi-briefcase-fill text-warning fs-3"></i>
            <div class="fw-bold fs-4 mt-1 text-warning">{{ $dayJob }}</div>
            <small class="text-secondary">Punya Day-Job</small>
        </div>
    </div>
</div>

{{-- TABEL PERSONEL --}}
<div class="arh-card p-4 animate-fade-up">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-person-lines-fill arh-gold"></i> Daftar Personel
        </h5>
        <a href="{{ route('admin.personnel.create') }}" class="btn btn-arh-gold btn-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah Personel
        </a>
    </div>

    <div class="table-responsive">
        <table class="table arh-table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Personel</th>
                    <th>Spesialisasi</th>
                    <th>Kontak</th>
                    <th>Day-Job</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($personnel as $idx => $p)
                <tr>
                    <td><small class="text-secondary">{{ $idx + 1 }}</small></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="arh-avatar">
                                {{ strtoupper(substr($p->user->name ?? 'P', 0, 2)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $p->user->name ?? 'Tanpa Akun' }}</div>
                                <small class="text-secondary">{{ $p->user->email ?? '-' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-secondary bg-opacity-50">{{ $p->specialty }}</span>
                        @if($p->is_backup)
                        <span class="badge bg-info bg-opacity-25 text-info ms-1">Cadangan</span>
                        @endif
                    </td>
                    <td>
                        <small>
                            @if($p->user && $p->user->phone)
                            <a href="tel:{{ $p->user->phone }}" class="text-secondary text-decoration-none">
                                <i class="bi bi-telephone-fill me-1"></i>{{ $p->user->phone }}
                            </a>
                            @else
                            <span class="text-secondary">-</span>
                            @endif
                        </small>
                    </td>
                    <td>
                        @if($p->has_day_job)
                        <div class="d-flex align-items-center gap-1">
                            <i class="bi bi-briefcase-fill text-warning small"></i>
                            <small>{{ $p->day_job_desc ?? 'Ada' }}</small>
                        </div>
                        <small class="text-secondary">
                            {{ \Carbon\Carbon::parse($p->day_job_start)->format('H:i') ?? '' }} –
                            {{ \Carbon\Carbon::parse($p->day_job_end)->format('H:i') ?? '' }}
                        </small>
                        @else
                        <span class="text-secondary small">Tidak ada</span>
                        @endif
                    </td>
                    <td>
                        @if($p->is_active)
                            <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> AKTIF</span>
                        @else
                            <span class="badge bg-warning text-dark border border-warning" title="Klik tombol Edit (Pensil) untuk mengaktifkan akun ini">
                                <i class="bi bi-hourglass-split"></i> MENUNGGU APPROVAL
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-action-group">
                            <a href="{{ route('admin.personnel.edit', $p->id) }}"
                               class="btn-action btn-action-edit" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.personnel.destroy', $p->id) }}" class="m-0 p-0" @php $nameAlert = $p->user->name ?? 'ini'; @endphp
                                  onsubmit="return confirm('Hapus personel {{ $nameAlert }}? Data akan hilang permanen!')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action btn-action-delete" title="Hapus">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-secondary">
                        <i class="bi bi-person-x fs-1 d-block mb-3"></i>
                        Belum ada personel terdaftar.
                        <div class="mt-3">
                            <a href="{{ route('admin.personnel.create') }}" class="btn btn-arh-gold btn-sm">
                                <i class="bi bi-person-plus-fill me-1"></i>Tambah Personel Pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

