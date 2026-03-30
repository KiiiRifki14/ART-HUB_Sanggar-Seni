@extends('layouts.admin')

@section('title', 'Personnel Management - ART-HUB')
@section('page_title', 'Personnel Management')
@section('page_subtitle', 'Profil & status 12 personel sanggar.')

@section('content')
<div class="grid grid-4 animate-fade-up" style="margin-bottom: 2rem;">
    <div class="glass-panel" style="text-align: center;">
        <h1 class="title-gold" style="font-size: 2.5rem; margin-bottom: 0;">{{ $personnel->count() }}</h1>
        <small class="text-muted">Total Personel</small>
    </div>
    <div class="glass-panel" style="text-align: center;">
        <h1 style="font-size: 2.5rem; margin-bottom: 0;">{{ $personnel->where('specialty', 'penari')->count() }}</h1>
        <small class="text-muted">Penari</small>
    </div>
    <div class="glass-panel" style="text-align: center;">
        <h1 style="font-size: 2.5rem; margin-bottom: 0;">{{ $personnel->where('specialty', 'pemusik')->count() }}</h1>
        <small class="text-muted">Pemusik</small>
    </div>
    <div class="glass-panel" style="text-align: center;">
        <h1 style="font-size: 2.5rem; margin-bottom: 0;">{{ $personnel->where('has_day_job', true)->count() }}</h1>
        <small class="text-muted">Punya Kerja Utama</small>
    </div>
</div>

<div class="glass-panel animate-fade-up stagger-1">
    <h2 style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.8rem;">
        <i class="ph ph-users" style="color: var(--gold-primary);"></i> Roster Personel
    </h2>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">No</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Nama</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Spesialisasi</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Pekerjaan Utama</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Jam Kerja</th>
                    <th style="padding: 1rem; text-align: left; color: var(--gold-primary); font-size: 0.75rem; text-transform: uppercase;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($personnel as $i => $p)
                <tr style="border-bottom: 1px solid var(--border-color); transition: 0.2s;" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1rem; color: var(--text-muted);">{{ $i + 1 }}</td>
                    <td style="padding: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.8rem;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--bg-hover); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; border: 1px solid var(--border-color);">{{ strtoupper(substr($p->user->name, 0, 2)) }}</div>
                            <div>
                                <div style="font-weight: 600;">{{ $p->user->name }}</div>
                                <small class="text-muted">{{ $p->user->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1rem; text-transform: capitalize;">
                        @if($p->specialty === 'multi_talent') <span class="badge badge-gold">Multi-Talent</span>
                        @elseif($p->specialty === 'pemusik') <span class="badge" style="background: rgba(255,255,255,0.05); border: 1px solid var(--text-muted);">Pemusik</span>
                        @else <span class="badge" style="background: rgba(255,255,255,0.05); border: 1px solid var(--text-muted);">Penari</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        @if($p->has_day_job)
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="ph-fill ph-warning-circle" style="color: var(--warning);"></i>
                                <span>{{ $p->day_job_desc }}</span>
                            </div>
                        @else
                            <span class="text-muted">— Freelance</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        @if($p->has_day_job)
                            <span class="badge badge-warning" style="font-size: 0.7rem;">{{ $p->day_job_start ? \Carbon\Carbon::parse($p->day_job_start)->format('H:i') : '08:00' }} - {{ $p->day_job_end ? \Carbon\Carbon::parse($p->day_job_end)->format('H:i') : '16:00' }}</span>
                        @else
                            <span class="text-muted">Fleksibel</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        @if($p->is_backup)
                            <span class="badge badge-gold"><i class="ph ph-swap"></i> Cadangan</span>
                        @elseif($p->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Non-Aktif</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
