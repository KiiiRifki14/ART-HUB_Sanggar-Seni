@extends('layouts.personnel')
@section('title', 'Jadwal – Portal Kru ART-HUB')

@section('content')

{{-- FLASH --}}
@foreach(['success'=>'green','warning'=>'orange','error'=>'red','info'=>'blue'] as $type => $color)
@if(session($type))
@php
    $alertBgs = ['success'=>'rgba(34,197,94,0.08)','warning'=>'rgba(251,146,60,0.08)','error'=>'rgba(239,68,68,0.08)','info'=>'rgba(96,165,250,0.08)'];
    $alertBorders = ['success'=>'rgba(34,197,94,0.25)','warning'=>'rgba(251,146,60,0.25)','error'=>'rgba(239,68,68,0.25)','info'=>'rgba(96,165,250,0.25)'];
    $alertIcons = ['success'=>'check-circle-fill','warning'=>'exclamation-triangle-fill','error'=>'x-circle-fill','info'=>'info-circle-fill'];
    $alertColors = ['success'=>'#16a34a','warning'=>'#ea580c','error'=>'#dc2626','info'=>'#2563eb'];
    $alertTextColors = ['success'=>'#15803d','warning'=>'#c2410c','error'=>'#b91c1c','info'=>'#1d4ed8'];
@endphp
<div class="fu flex items-start gap-3 p-3.5 rounded-2xl mb-4 border"
     style="background:{{ $alertBgs[$type] }};border-color:{{ $alertBorders[$type] }}">
    <i class="bi bi-{{ $alertIcons[$type] }} text-lg mt-0.5" style="color:{{ $alertColors[$type] }}"></i>
    <span class="text-sm font-semibold" style="color:{{ $alertTextColors[$type] }}">{{ session($type) }}</span>
</div>
@endif
@endforeach

@if(!$personnel)
<div class="text-center py-16 px-6">
    <i class="bi bi-person-x-fill text-5xl block mb-4" style="color:rgba(0,0,0,0.1)"></i>
    <div class="font-bold text-[#1A1817] mb-2">Profil Tidak Ditemukan</div>
    <div class="text-sm" style="color:#847B78">Hubungi Admin untuk mengaktifkan akun Anda.</div>
</div>
@else

{{-- ═══ HERO BANNER ═══ --}}
<div class="fu relative rounded-3xl overflow-hidden mb-4 p-5"
     style="background:linear-gradient(135deg,rgba(139,26,42,0.95) 0%,rgba(92,14,25,1) 50%,rgba(54,31,26,1) 100%);border:1px solid rgba(197,160,40,0.25);box-shadow:0 8px 32px rgba(139,26,42,0.15)">
    {{-- Decorative --}}
    <div class="absolute" style="width:200px;height:200px;border-radius:50%;background:radial-gradient(circle,rgba(197,160,40,0.12),transparent);top:-60px;right:-60px;pointer-events:none"></div>
    <div class="absolute" style="width:100px;height:100px;border-radius:50%;background:radial-gradient(circle,rgba(197,160,40,0.08),transparent);bottom:-20px;left:20px;pointer-events:none"></div>

    <div class="flex items-center gap-4 relative">
        {{-- Avatar --}}
        <div class="relative shrink-0">
            <div style="width:72px;height:72px;border-radius:20px;overflow:hidden;border:2px solid rgba(197,160,40,0.5);box-shadow:0 4px 20px rgba(139,26,42,0.5)">
                @if($personnel->photo)
                    <img src="{{ asset('storage/'.$personnel->photo) }}" style="width:100%;height:100%;object-fit:cover">
                @else
                    <div class="w-full h-full flex items-center justify-center font-head font-bold text-gold" style="background:linear-gradient(135deg,rgba(197,160,40,0.2),rgba(139,26,42,0.4));font-size:1.8rem">
                        {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                    </div>
                @endif
            </div>
            <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-green-500 border-2" style="border-color:#FAF9F6"></div>
        </div>

        {{-- Info --}}
        <div class="flex-1 min-w-0">
            <div class="font-bold text-white truncate mb-0.5" style="font-size:1.1rem">
                {{ $personnel->stage_name ?? Auth::user()->name }}
            </div>
            @if($personnel->stage_name && $personnel->stage_name !== Auth::user()->name)
            <div class="text-xs mb-1.5" style="color:rgba(255,255,255,0.45)">{{ Auth::user()->name }}</div>
            @endif
            <div class="flex flex-wrap gap-1.5">
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.6rem] font-bold uppercase tracking-widest" style="background:rgba(197,160,40,0.15);color:#E8C84A;border:1px solid rgba(197,160,40,0.3)">
                    <i class="bi bi-music-note-list"></i> {{ ucfirst(str_replace('_',' ',$personnel->specialty ?? 'Personel')) }}
                </span>
                @if($personnel->is_backup)
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.6rem] font-bold uppercase tracking-widest" style="background:rgba(255,255,255,0.05);color:rgba(255,255,255,0.4);border:1px solid rgba(255,255,255,0.1)">Cadangan</span>
                @endif
            </div>
        </div>

        {{-- Edit Profil --}}
        <a href="{{ route('personnel.profile.edit') }}" class="shrink-0 flex items-center justify-center w-9 h-9 rounded-xl transition-colors" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.5)">
            <i class="bi bi-pencil-square text-sm"></i>
        </a>
    </div>

    {{-- Stat Row --}}
    <div class="grid grid-cols-3 mt-4 pt-4" style="border-top:1px solid rgba(255,255,255,0.08)">
        <div class="text-center">
            <div class="font-head font-bold text-[#E8C84A]" style="font-size:1.8rem;line-height:1">{{ $upcomingEvents->count() }}</div>
            <div class="text-[0.58rem] uppercase tracking-widest mt-1" style="color:rgba(255,255,255,0.45)">Mendatang</div>
        </div>
        <div class="text-center" style="border-left:1px solid rgba(255,255,255,0.08);border-right:1px solid rgba(255,255,255,0.08)">
            <div class="font-head font-bold text-white" style="font-size:1.8rem;line-height:1">{{ $upcomingEvents->where('pivot.checked_in_at','!=',null)->count() }}</div>
            <div class="text-[0.58rem] uppercase tracking-widest mt-1" style="color:rgba(255,255,255,0.45)">Sudah Absen</div>
        </div>
        <div class="text-center">
            @php $fee = $upcomingEvents->sum('pivot.fee'); @endphp
            <div class="font-head font-bold text-[#E8C84A]" style="font-size:1.8rem;line-height:1">{{ $fee > 0 ? number_format($fee/1000000,1).'jt' : '–' }}</div>
            <div class="text-[0.58rem] uppercase tracking-widest mt-1" style="color:rgba(255,255,255,0.45)">Est. Honor</div>
        </div>
    </div>
</div>

{{-- ═══ KALENDER + JADWAL: SIDE BY SIDE ═══ --}}
<div class="fu1 grid gap-4 mb-4" style="grid-template-columns:1fr 1.4fr">

    {{-- Kalender Mini --}}
    <div class="rounded-2xl p-4 bg-white" style="border:1px solid rgba(197,160,40,0.15); box-shadow: 0 4px 20px rgba(54,31,26,0.04)">
        <div class="flex items-center justify-between mb-3">
            <a href="{{ route('personnel.dashboard', array_merge(request()->query(), ['month' => $prevMonth, 'year' => $prevYear])) }}" class="w-6 h-6 flex items-center justify-center rounded-lg hover:bg-gold/10 text-[#847B78] hover:text-[#8B1A2A] transition-all">
                <i class="bi bi-chevron-left text-xs font-bold"></i>
            </a>
            <div class="text-[0.65rem] font-bold uppercase tracking-widest text-[#847B78] text-center flex-1">
                {{ $firstDay->translatedFormat('F Y') }}
            </div>
            <a href="{{ route('personnel.dashboard', array_merge(request()->query(), ['month' => $nextMonth, 'year' => $nextYear])) }}" class="w-6 h-6 flex items-center justify-center rounded-lg hover:bg-gold/10 text-[#847B78] hover:text-[#8B1A2A] transition-all">
                <i class="bi bi-chevron-right text-xs font-bold"></i>
            </a>
        </div>
        <div class="grid grid-cols-7 mb-1">
            @foreach(['M','S','S','R','K','J','S'] as $d)
            <div class="text-center text-[0.55rem] font-bold" style="color:#847B78">{{ $d }}</div>
            @endforeach
        </div>
        <div class="grid grid-cols-7 gap-px">
            @for($i=0;$i<$startDow;$i++)<div></div>@endfor
            @for($day=1; $day<=$daysInMonth; $day++)
            @php
                $ds = \Carbon\Carbon::create($thisYear,$thisMonth,$day)->format('Y-m-d');
                $isTd  = $ds === $now->toDateString();
                $hasEv = in_array($ds,$eventDates);
                $hasReh = in_array($ds,$rehearsalDates);
                $isUrg = in_array($ds,$urgentDates);
                $isUnavail = in_array($ds, $unavailabilityDates);

                $clickAction = ($hasEv || $hasReh) ? "scrollToEvent('$ds')" : "openUnavailModal('$ds')";
                
                $cellColor = '#4D4946';
                if ($isTd) {
                    $cellColor = '#FFF';
                } elseif ($hasEv) {
                    $cellColor = '#8B1A2A';
                } elseif ($hasReh) {
                    $cellColor = '#0d9488';
                } elseif ($isUnavail) {
                    $cellColor = '#dc2626';
                }

                $cellBg = 'transparent';
                if ($isTd) {
                    $cellBg = '#8B1A2A';
                } elseif ($hasEv) {
                    $cellBg = 'rgba(197,160,40,0.18)';
                } elseif ($hasReh) {
                    $cellBg = 'rgba(13,148,136,0.1)';
                } elseif ($isUnavail) {
                    $cellBg = 'rgba(220,38,38,0.08)';
                }
            @endphp
            <div onclick="{{ $clickAction }}"
                 class="aspect-square flex flex-col items-center justify-center rounded-full relative text-[0.7rem] font-{{ $isTd||$hasEv||$hasReh?'bold':'medium' }} {{ $hasEv||$hasReh||!$isUnavail?'cursor-pointer':'' }} transition-all"
                 style="color:{{ $cellColor }};background:{{ $cellBg }}">
                {{ $day }}
                @if($hasEv && !$isTd)
                <span class="absolute w-1 h-1 rounded-full" style="bottom:2px;background:{{ $isUrg?'#ef4444':'#C5A028' }}"></span>
                @elseif($hasReh && !$isTd)
                <span class="absolute w-1 h-1 rounded-full" style="bottom:2px;background:#0d9488"></span>
                @elseif($isUnavail && !$isTd)
                <span class="absolute w-1 h-1 rounded-full" style="bottom:2px;background:rgba(220,38,38,0.4)"></span>
                @endif
            </div>
            @endfor
        </div>
    </div>

    {{-- Tugas Mendatang Compact --}}
    <div class="flex flex-col gap-2">
        <div class="flex items-center justify-between mb-1">
            <div class="font-head font-bold text-[#1A1817] text-lg">Jadwal</div>
            <span class="px-2.5 py-1 rounded-full text-[0.58rem] font-bold uppercase tracking-widest" style="background:rgba(139,26,42,0.06);color:#8B1A2A;border:1px solid rgba(139,26,42,0.15)">{{ $upcomingEvents->count() }}</span>
        </div>

        @forelse($upcomingEvents->take(3) as $event)
        @php
            $eDate   = \Carbon\Carbon::parse($event->event_date);
            $dL      = $now->startOfDay()->diffInDays($eDate->startOfDay(), false);
            $urgent  = ($dL >= 0 && $dL <= 3);
            $isToday = $eDate->isToday();
            $chk     = !empty($event->pivot->checked_in_at);

            $cardBorder = $urgent ? 'rgba(239,68,68,0.3)' : 'rgba(197,160,40,0.15)';
            $cardShadow = $urgent ? '0 4px 16px rgba(239,68,68,0.08)' : '0 4px 20px rgba(54,31,26,0.03)';
            $textColor  = $urgent ? '#dc2626' : '#C5A028';
        @endphp
        <div class="event-card rounded-2xl p-3 bg-white" id="evt-{{ $event->event_date }}"
             style="border:1px solid {{ $cardBorder }};box-shadow:{{ $cardShadow }}">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <div class="font-bold text-[#1A1817] text-sm leading-tight mb-0.5">{{ Str::limit($event->booking->client_name ?? 'Event',20) }}</div>
                    <div class="text-[0.62rem] font-bold flex items-center gap-1" style="color:{{ $textColor }}">
                        <i class="bi bi-{{ $isToday?'fire':'calendar3' }}"></i>
                        @if($isToday) Hari Ini! @elseif($dL==1) Besok! @elseif($dL>0) H-{{ $dL }} @else {{ $eDate->format('d M') }} @endif
                    </div>
                </div>
                @if($chk)
                <span class="shrink-0 w-6 h-6 rounded-full flex items-center justify-center" style="background:rgba(34,197,94,0.12)"><i class="bi bi-check-lg text-xs text-green-600"></i></span>
                @elseif($isToday)
                <span class="shrink-0 px-1.5 py-0.5 rounded-full text-[0.5rem] font-bold uppercase" style="background:rgba(197,160,40,0.15);color:#C5A028;border:1px solid rgba(197,160,40,0.3)">Tap ↓</span>
                @else
                <span class="shrink-0 w-6 h-6 rounded-full flex items-center justify-center" style="background:#F4F2EE"><i class="bi bi-lock-fill" style="font-size:0.6rem;color:#847B78"></i></span>
                @endif
            </div>
            <div class="flex items-center gap-1.5 mt-2 text-[0.58rem]" style="color:#847B78">
                <i class="bi bi-geo-alt-fill text-gold text-xs"></i>
                <span class="truncate">{{ Str::limit($event->venue,22) }}</span>
            </div>
        </div>
        @empty
        <div class="rounded-2xl p-4 text-center bg-white" style="border:1px dashed rgba(197,160,40,0.25)">
            <i class="bi bi-calendar-x text-2xl block mb-2" style="color:#847B78"></i>
            <div class="text-xs" style="color:#847B78">Belum ada jadwal</div>
        </div>
        @endforelse
    </div>
</div>

{{-- ═══ DETAIL TUGAS (Full Width Cards) ═══ --}}
@if($upcomingEvents->count() > 0)
<div class="fu2 mb-2">
    <div class="flex items-center gap-2 mb-3 mt-4">
        <div style="width:3px;height:18px;background:linear-gradient(to bottom,#C5A028,rgba(197,160,40,0.2));border-radius:99px"></div>
        <div class="font-head font-bold text-[#1A1817] text-lg">Detail Tugas (Pementasan)</div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
@foreach($paginatedDetailEvents as $event)
@php
    $eDate     = \Carbon\Carbon::parse($event->event_date);
    $daysLeft  = $now->startOfDay()->diffInDays($eDate->startOfDay(), false);
    $urgent    = ($daysLeft >= 0 && $daysLeft <= 3);
    $isToday   = $eDate->isToday();
    $checkedIn = !empty($event->pivot->checked_in_at);
    $hasCoords = $event->latitude && $event->longitude;
    $canCheckIn = $isToday && !$checkedIn;
    $cardAnim  = ['fu2','fu3','fu4','fu5'][$loop->index % 4];

    $cardBorder = $urgent ? 'rgba(239,68,68,0.3)' : 'rgba(197,160,40,0.15)';
    $cardShadow = $urgent ? '0 4px 24px rgba(239,68,68,0.08)' : '0 4px 20px rgba(54,31,26,0.04)';
    $badgeBg    = $urgent ? 'rgba(239,68,68,0.1)' : 'rgba(139,26,42,0.05)';
    $badgeBorder= $urgent ? 'rgba(239,68,68,0.25)' : 'rgba(139,26,42,0.12)';
    $badgeColor = $urgent ? '#dc2626' : '#8B1A2A';
    $statusColor= $urgent ? '#dc2626' : '#C5A028';
@endphp

<div id="evt-{{ $event->event_date }}" class="{{ $cardAnim }} event-card rounded-3xl overflow-hidden bg-white flex flex-col justify-between"
     style="border:1px solid {{ $cardBorder }};box-shadow:{{ $cardShadow }}">

    <div>
        {{-- Card Header --}}
        <div class="flex items-center gap-3 p-4 pb-3" style="border-bottom:1px solid #F4F2EE">
            {{-- Date badge --}}
            <div class="shrink-0 w-14 h-14 rounded-2xl flex flex-col items-center justify-center" style="background:{{ $badgeBg }};border:1px solid {{ $badgeBorder }}">
                <span class="font-head font-bold leading-none" style="font-size:1.4rem;color:{{ $badgeColor }}">{{ $eDate->format('d') }}</span>
                <span class="text-[0.5rem] font-bold uppercase tracking-widest" style="color:{{ $badgeColor }}">{{ $eDate->format('M') }}</span>
            </div>

            <div class="flex-1 min-w-0">
                <div class="font-bold text-[#1A1817] mb-0.5" style="font-size:0.95rem">{{ $event->booking->client_name ?? 'Event Sanggar' }}</div>
                <div class="flex items-center gap-1.5 text-[0.62rem] font-bold uppercase tracking-wide" style="color:{{ $statusColor }}">
                    <i class="bi bi-{{ $isToday?'fire':($urgent?'exclamation-diamond-fill':'calendar3') }}"></i>
                    @if($isToday) Hari Ini! @elseif($daysLeft==1) Besok! @elseif($daysLeft>0) H-{{ $daysLeft }} @else {{ $eDate->translatedFormat('d F Y') }} @endif
                </div>
            </div>

            {{-- Status pill --}}
            @if($checkedIn)
            <span class="shrink-0 px-2.5 py-1 rounded-full text-[0.58rem] font-bold uppercase tracking-widest" style="background:rgba(34,197,94,0.1);color:#16a34a;border:1px solid rgba(34,197,94,0.2)">
                <i class="bi bi-check-circle-fill"></i> Hadir
            </span>
            @else
            <span class="shrink-0 px-2.5 py-1 rounded-full text-[0.58rem] font-bold uppercase tracking-widest" style="background:#F4F2EE;color:#847B78;border:1px solid rgba(0,0,0,0.05)">
                <i class="bi bi-clock"></i> Belum
            </span>
            @endif
        </div>

        {{-- Detail Grid --}}
        <div class="p-4 grid grid-cols-2 gap-3">
            <div class="col-span-2">
                <div class="text-[0.57rem] font-bold uppercase tracking-widest mb-1.5 flex items-center gap-1" style="color:#847B78"><i class="bi bi-geo-alt-fill text-gold text-xs"></i> Lokasi</div>
                <a href="https://maps.google.com/?q={{ urlencode($event->venue) }}" target="_blank" class="flex items-start gap-1.5 no-underline transition-colors hover:text-maroon-light" style="color:#1A1817;font-size:0.85rem;font-weight:600">
                    <span>{{ $event->venue }}</span><i class="bi bi-box-arrow-up-right shrink-0 mt-1" style="font-size:0.55rem;color:#847B78"></i>
                </a>
            </div>
            <div>
                <div class="text-[0.57rem] font-bold uppercase tracking-widest mb-1.5 flex items-center gap-1" style="color:#847B78"><i class="bi bi-clock-fill text-gold text-xs"></i> Waktu</div>
                <div class="font-bold text-[#1A1817]" style="font-size:0.85rem">{{ \Carbon\Carbon::parse($event->event_start)->format('H:i') }} <span style="color:#847B78">–</span> {{ \Carbon\Carbon::parse($event->event_end)->format('H:i') }} <span class="text-xs font-medium" style="color:#847B78">WIB</span></div>
            </div>
            <div>
                <div class="text-[0.57rem] font-bold uppercase tracking-widest mb-1.5 flex items-center gap-1" style="color:#847B78"><i class="bi bi-person-badge-fill text-gold text-xs"></i> Jobdesk</div>
                <div class="font-bold text-[#1A1817] capitalize" style="font-size:0.85rem">{{ str_replace('_',' ',$event->pivot->role_in_event ?? '–') }}</div>
            </div>
            @if($event->pivot->fee > 0)
            <div class="col-span-2 rounded-xl p-3 flex items-center gap-3" style="background:rgba(139,26,42,0.05);border:1px solid rgba(139,26,42,0.12)">
                <i class="bi bi-cash-stack text-[#8B1A2A] text-lg"></i>
                <div>
                    <div class="text-[0.57rem] font-bold uppercase tracking-widest" style="color:#8B1A2A">Estimasi Honor</div>
                    <div class="font-bold text-[#8B1A2A]" style="font-size:0.95rem">Rp {{ number_format($event->pivot->fee,0,',','.') }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- CTA Check-in --}}
    <div class="px-4 pb-4">
        @if($checkedIn)
        <div class="flex items-center justify-center gap-2.5 p-3.5 rounded-2xl" style="background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.18)">
            <i class="bi bi-shield-check text-green-600 text-xl"></i>
            <div>
                <div class="text-sm font-bold text-green-700">Check-in Berhasil</div>
                <div class="text-xs" style="color:#847B78">
                    Pukul {{ \Carbon\Carbon::parse($event->pivot->checked_in_at)->format('H:i') }} WIB
                    @if(($event->pivot->attendance_status ?? '') === 'late')
                        · <span style="color:#ea580c">Telat {{ $event->pivot->late_minutes ?? 0 }} mnt</span>
                    @endif
                </div>
            </div>
        </div>
        @elseif($canCheckIn)
        <button type="button" onclick="doCheckIn('{{ $event->id }}',this)"
                class="w-full flex items-center justify-center gap-2.5 p-3.5 rounded-2xl font-bold transition-all cursor-pointer hover:-translate-y-px"
                style="background:linear-gradient(135deg,#8B1A2A,#5C0E19);border:1px solid rgba(197,160,40,0.35);color:#C5A028;font-size:0.85rem;box-shadow:0 4px 20px rgba(139,26,42,0.25)">
            <i class="bi bi-geo-alt-fill text-lg"></i>
            <div class="text-left">
                <div>Ghosting Guard – Check-in Lokasi</div>
                <div class="text-[0.62rem] font-medium" style="color:rgba(197,160,40,0.6)">{{ $hasCoords ? 'Validasi GPS · Radius 200m' : 'GPS belum diset Admin' }}</div>
            </div>
        </button>
        <form id="cf-{{ $event->id }}" action="{{ route('personnel.attendance.check_in',$event->id) }}" method="POST" class="hidden">
            @csrf<input type="hidden" name="latitude" id="lat-{{ $event->id }}"><input type="hidden" name="longitude" id="lng-{{ $event->id }}">
        </form>
        @else
        <div class="flex items-center justify-center gap-2.5 p-3.5 rounded-2xl cursor-not-allowed" style="background:#F4F2EE;border:1px dashed rgba(0,0,0,0.1)">
            <i class="bi bi-lock-fill text-lg" style="color:#847B78"></i>
            <div class="text-left">
                <div class="text-sm font-bold" style="color:#847B78">Check-in Terkunci</div>
                <div class="text-xs" style="color:#847B78">Dibuka {{ $eDate->translatedFormat('d F Y') }}</div>
            </div>
        </div>
        @endif
    </div>
</div>
@endforeach
</div>

<div class="mt-4 px-2">
    {{ $paginatedDetailEvents->appends(request()->except('detail_page'))->links() }}
</div>
@endif

{{-- ═══ JADWAL LATIHAN ═══ --}}
@if($upcomingRehearsals->count() > 0)
<div class="fu2 mb-2">
    <div class="flex items-center gap-2 mb-3 mt-6">
        <div style="width:3px;height:18px;background:linear-gradient(to bottom,#0d9488,rgba(13,148,136,0.2));border-radius:99px"></div>
        <div class="font-head font-bold text-[#1A1817] text-lg">Jadwal Latihan</div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
@foreach($upcomingRehearsals as $rehearsal)
@php
    $rDate = \Carbon\Carbon::parse($rehearsal->rehearsal_date);
    $rDaysLeft = $now->startOfDay()->diffInDays($rDate->startOfDay(), false);
    $isTodayR = $rDate->isToday();

    // Cek apakah jam latihan sudah lewat (end_time sudah terlampaui)
    $rehearsalEnded = false;
    if ($isTodayR && $rehearsal->end_time) {
        // Paksa Asia/Jakarta agar konsisten dengan $now dari controller
        $dateOnly    = \Carbon\Carbon::parse($rehearsal->rehearsal_date)->toDateString(); // Y-m-d
        $endTimeOnly = \Carbon\Carbon::parse($rehearsal->end_time)->format('H:i:s');      // H:i:s saja
        $endDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dateOnly . ' ' . $endTimeOnly, 'Asia/Jakarta');
        $rehearsalEnded = $now->gt($endDateTime);
    } elseif ($rDate->isPast() && !$isTodayR) {
        $rehearsalEnded = true;
    }

    // Ambil data pivot absensi latihan
    $rehPivot = $rehearsal->personnel->first()?->pivot;
    $rehCheckedIn = !empty($rehPivot?->checked_in_at);
    $rehHasCoords = $rehearsal->latitude && $rehearsal->longitude;
    // Tombol absen aktif: hari ini, belum absen, dan jam latihan belum selesai
    $canRehCheckIn = $isTodayR && !$rehCheckedIn && !$rehearsalEnded;
@endphp
<div id="evt-{{ $rehearsal->rehearsal_date }}" class="event-card rounded-2xl p-4 bg-white flex flex-col justify-between shadow-sm"
     style="border:1px solid rgba(13,148,136,0.2)">
    
    <div>
        <div class="flex items-start justify-between gap-3 mb-3">
            <div>
                <div class="font-bold text-[#1A1817] text-sm mb-1 leading-tight">{{ Str::limit($rehearsal->event->booking->client_name ?? 'Latihan Gabungan', 22) }}</div>
                <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[0.6rem] font-bold uppercase tracking-widest bg-teal-50 text-teal-600 border border-teal-100">
                    Latihan {{ ucfirst($rehearsal->type) }}
                </div>
            </div>
            <div class="text-right">
                <div class="text-[0.65rem] font-bold" style="color:{{ $isTodayR ? '#0d9488' : '#847B78' }}">
                    @if($isTodayR) Hari Ini! @elseif($rDaysLeft==1) Besok @else {{ $rDate->translatedFormat('d M Y') }} @endif
                </div>
                <div class="text-xs font-bold text-[#1A1817] mt-0.5">
                    {{ \Carbon\Carbon::parse($rehearsal->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($rehearsal->end_time)->format('H:i') }}
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 mt-2 pt-2 border-t border-teal-50 text-xs" style="color:#4D4946">
            <i class="bi bi-geo-alt-fill text-teal-500"></i>
            <span class="truncate block max-w-[200px]" title="{{ $rehearsal->location }}">{{ $rehearsal->location }}</span>
        </div>

        @if($rehearsal->notes)
        <div class="mt-2 text-[0.65rem] italic bg-gray-50 p-2 rounded-lg" style="color:#847B78">
            "{{ $rehearsal->notes }}"
        </div>
        @endif
    </div>

    {{-- CTA Absen Latihan --}}
    <div class="mt-4">
        @if($rehCheckedIn)
        <div class="flex items-center justify-center gap-2 p-2 rounded-xl bg-teal-50 border border-teal-200/50">
            <i class="bi bi-shield-check text-teal-600 text-base"></i>
            <div class="text-left">
                <div class="text-xs font-bold text-teal-700">Absen Latihan Berhasil</div>
                <div class="text-[0.65rem]" style="color:#847B78">
                    Pukul {{ \Carbon\Carbon::parse($rehPivot->checked_in_at)->format('H:i') }} WIB
                    @if(($rehPivot->attendance_status ?? '') === 'late')
                        · <span style="color:#ea580c">Telat {{ $rehPivot->late_minutes ?? 0 }} mnt</span>
                    @endif
                </div>
            </div>
        </div>
        @elseif($canRehCheckIn)
        <button type="button" onclick="doRehearsalCheckIn('{{ $rehearsal->id }}', this)"
                class="w-full flex items-center justify-center gap-2 py-2 rounded-xl font-bold transition-all cursor-pointer bg-gradient-to-r from-teal-600 to-teal-800 text-white border border-teal-500/20 text-xs shadow-sm hover:-translate-y-px">
            <i class="bi bi-geo-alt-fill text-sm"></i>
            <div class="text-left">
                <div>Absen Latihan (GPS)</div>
                <div class="text-[0.55rem] font-medium opacity-80">{{ $rehHasCoords ? 'Radius 200m' : 'GPS belum diset Admin' }}</div>
            </div>
        </button>
        <form id="reh-cf-{{ $rehearsal->id }}" action="{{ route('personnel.rehearsals.check_in', $rehearsal->id) }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="latitude" id="reh-lat-{{ $rehearsal->id }}">
            <input type="hidden" name="longitude" id="reh-lng-{{ $rehearsal->id }}">
            <input type="hidden" name="accuracy" id="reh-acc-{{ $rehearsal->id }}">
        </form>
        @elseif($rehearsalEnded && !$rehCheckedIn)
        {{-- Jam latihan sudah lewat tapi belum absen --}}
        <div class="flex items-center justify-center gap-2 p-2 rounded-xl border border-dashed" style="background:rgba(251,146,60,0.06);border-color:rgba(251,146,60,0.3)">
            <i class="bi bi-clock-history text-orange-400"></i>
            <div class="text-left">
                <div class="text-xs font-bold text-orange-600">Waktu Absen Habis</div>
                <div class="text-[0.6rem]" style="color:#847B78">
                    Latihan selesai {{ \Carbon\Carbon::parse($rehearsal->end_time)->format('H:i') }} WIB
                    · Hubungi admin jika ada kendala
                </div>
            </div>
        </div>
        @else
        <div class="flex items-center justify-center gap-2 p-2 rounded-xl bg-gray-50 border border-dashed border-gray-200">
            <i class="bi bi-lock-fill text-gray-400"></i>
            <div class="text-left">
                <div class="text-xs font-bold text-gray-500">Absen Belum Dibuka</div>
                <div class="text-[0.65rem]" style="color:#847B78">Dibuka {{ $rDate->translatedFormat('d F Y') }}</div>
            </div>
        </div>
        @endif
    </div>
</div>
@endforeach


@endif

{{-- MODAL BERHALANGAN --}}
<div id="unavailModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeUnavailModal()"></div>
    <div class="relative w-full max-w-sm rounded-3xl p-5 border shadow-2xl transition-all scale-95 opacity-0 bg-white border-gold/20" id="unavailModalContent">
        
        <div class="flex items-center justify-between mb-4 pb-3" style="border-bottom:1px solid #F4F2EE">
            <div class="flex items-center gap-2 text-[#1A1817] font-bold font-head text-lg">
                <i class="bi bi-calendar-x text-red-500"></i> Tandai Berhalangan
            </div>
            <button type="button" onclick="closeUnavailModal()" class="text-[#847B78] hover:text-[#1A1817] transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form action="{{ route('personnel.unavailability.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="block text-[0.65rem] font-bold uppercase tracking-widest mb-1.5" style="color:#847B78">Tanggal Mulai</label>
                <input type="date" name="start_date" id="unavail_start_date" required readonly
                       class="w-full px-3 py-2.5 rounded-xl text-sm font-medium text-[#1A1817] outline-none bg-[#F4F2EE] border border-black/5">
            </div>

            <div class="mb-3">
                <label class="block text-[0.65rem] font-bold uppercase tracking-widest mb-1.5" style="color:#847B78">Tanggal Selesai <span class="normal-case text-[#847B78]/60 text-[0.55rem] tracking-normal">(opsional)</span></label>
                <input type="date" name="end_date" id="unavail_end_date"
                       class="w-full px-3 py-2.5 rounded-xl text-sm font-medium text-[#1A1817] outline-none bg-[#F4F2EE] border border-black/5 focus:border-gold/50 transition-colors">
                <div class="mt-1 text-[0.6rem] text-[#847B78]/60">Jika hanya 1 hari, biarkan kosong atau samakan dengan tanggal mulai.</div>
            </div>

            <div class="mb-5">
                <label class="block text-[0.65rem] font-bold uppercase tracking-widest mb-1.5" style="color:#847B78">Alasan <span class="text-red-500">*</span></label>
                <input type="text" name="reason" required placeholder="Contoh: Sakit, Cuti Keluarga, dll."
                       class="w-full px-3 py-2.5 rounded-xl text-sm font-medium text-[#1A1817] outline-none bg-[#F4F2EE] border border-black/5 focus:border-gold/50 transition-colors">
            </div>

            <div class="flex gap-2">
                <button type="button" onclick="closeUnavailModal()" class="flex-1 py-3 rounded-xl font-bold text-sm text-[#4D4946] bg-[#F4F2EE] hover:bg-[#EBE7DF] transition-colors border border-black/5">Batal</button>
                <button type="submit" class="flex-1 py-3 rounded-xl font-bold text-sm transition-all"
                        style="background:linear-gradient(135deg,#8B1A2A,#5C0E19);border:1px solid rgba(197,160,40,0.35);color:#C5A028;box-shadow:0 4px 12px rgba(139,26,42,0.3)">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@endif
@endsection

@push('scripts')
<script>
function doRehearsalCheckIn(rehearsalId, btn) {
    if (!navigator.geolocation) { alert('GPS tidak didukung browser ini.'); return; }
    const orig = btn.innerHTML;
    btn.disabled = true; btn.style.opacity = '0.6';
    btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin text-sm"></i> Mendeteksi GPS...';
    navigator.geolocation.getCurrentPosition(
        function(pos) {
            document.getElementById('reh-lat-'+rehearsalId).value = pos.coords.latitude;
            document.getElementById('reh-lng-'+rehearsalId).value = pos.coords.longitude;
            document.getElementById('reh-acc-'+rehearsalId).value = pos.coords.accuracy;
            document.getElementById('reh-cf-'+rehearsalId).submit();
        },
        function() { btn.innerHTML = orig; btn.disabled = false; btn.style.opacity = '1'; alert('❌ Gagal GPS. Pastikan GPS aktif dan izin lokasi disetujui.'); },
        { enableHighAccuracy:true, timeout:12000, maximumAge:0 }
    );
}

function doCheckIn(eventId, btn) {
    if (!navigator.geolocation) { alert('GPS tidak didukung browser ini.'); return; }
    const orig = btn.innerHTML;
    btn.disabled = true; btn.style.opacity = '0.6';
    btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin text-lg"></i><div class="text-left"><div>Mendeteksi GPS...</div><div class="text-[0.62rem]" style="color:rgba(197,160,40,0.6)">Pastikan izin lokasi disetujui</div></div>';
    navigator.geolocation.getCurrentPosition(
        function(pos) {
            if (!navigator.onLine) {
                let pend = JSON.parse(localStorage.getItem('pendingCheckins')||'[]');
                pend.push({ eventId, latitude:pos.coords.latitude, longitude:pos.coords.longitude, timestamp:new Date().toISOString() });
                localStorage.setItem('pendingCheckins', JSON.stringify(pend));
                btn.innerHTML = '<i class="bi bi-clock-history text-lg"></i><div class="text-left"><div>Tersimpan Offline</div><div class="text-[0.62rem]" style="color:rgba(197,160,40,0.6)">Akan disinkronkan saat online</div></div>';
                btn.style.opacity = '1';
            } else {
                document.getElementById('lat-'+eventId).value = pos.coords.latitude;
                document.getElementById('lng-'+eventId).value = pos.coords.longitude;
                document.getElementById('cf-'+eventId).submit();
            }
        },
        function() { btn.innerHTML = orig; btn.disabled = false; btn.style.opacity = '1'; alert('❌ Gagal GPS. Pastikan GPS aktif dan izin lokasi disetujui.'); },
        { enableHighAccuracy:true, timeout:12000, maximumAge:0 }
    );
}
function scrollToEvent(ds) {
    const el = document.getElementById('evt-'+ds);
    if (!el) return;
    el.scrollIntoView({ behavior:'smooth', block:'center' });
    el.style.outline = '2px solid rgba(197,160,40,0.5)'; el.style.outlineOffset = '2px';
    setTimeout(() => { el.style.outline = ''; el.style.outlineOffset = ''; }, 2000);
}

function openUnavailModal(ds) {
    const today = new Date().toISOString().split('T')[0];
    if (ds < today) {
        alert('Tidak bisa menandai tanggal yang sudah lewat.');
        return;
    }
    
    document.getElementById('unavail_start_date').value = ds;
    document.getElementById('unavail_end_date').value = ds;
    document.getElementById('unavail_end_date').min = ds;
    
    const modal = document.getElementById('unavailModal');
    const content = document.getElementById('unavailModalContent');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Animate in
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeUnavailModal() {
    const modal = document.getElementById('unavailModal');
    const content = document.getElementById('unavailModalContent');
    
    // Animate out
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
}
</script>
@endpush
