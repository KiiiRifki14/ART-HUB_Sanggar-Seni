@extends('layouts.personnel')
@section('title', 'Dashboard – Portal Kru ART-HUB')

@section('content')
@php
    $personnel     = Auth::user()->personnelProfile;
    $now           = now();
    $upcomingEvents = $personnel
        ? $personnel->events()->where('event_date','>=', $now->toDateString())->orderBy('event_date','asc')->get()
        : collect();

    // Calendar logic
    $thisMonth = $now->month; $thisYear = $now->year;
    $firstDay  = \Carbon\Carbon::create($thisYear, $thisMonth, 1);
    $daysInMonth = $firstDay->daysInMonth;
    $startDow    = $firstDay->dayOfWeek;
    $eventDates  = $upcomingEvents->pluck('event_date')->map(fn($d)=>\Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();
    $urgentDates = $upcomingEvents->filter(function($e) use($now){
        $d = \Carbon\Carbon::parse($e->event_date)->startOfDay()->diffInDays($now->startOfDay(), false);
        return $d >= -3 && $d <= 0;
    })->pluck('event_date')->map(fn($d)=>\Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();
@endphp

@if(!$personnel)
<div style="text-align:center; padding:60px 24px;">
    <i class="bi bi-person-x" style="font-size:3rem; color:rgba(255,255,255,0.15); display:block; margin-bottom:16px;"></i>
    <div style="font-size:1.1rem; font-weight:700; color:#fff; margin-bottom:6px;">Profil Tidak Ditemukan</div>
    <div style="font-size:0.82rem; color:rgba(255,255,255,0.4);">Hubungi Admin untuk mengaktifkan akun Anda.</div>
</div>
@else

{{-- ═══════════════════════════════════════ --}}
{{-- SECTION 1: HERO PROFIL --}}
{{-- ═══════════════════════════════════════ --}}
<div class="fu" style="
    border-radius:20px;
    padding:24px;
    margin-bottom:16px;
    position:relative;
    overflow:hidden;
    background: linear-gradient(135deg, rgba(139,26,42,0.6) 0%, rgba(92,14,25,0.8) 60%, rgba(12,8,6,0.9) 100%);
    border: 1px solid rgba(197,160,40,0.25);
    box-shadow: 0 8px 32px rgba(139,26,42,0.25);
">
    <!-- Decorative ring -->
    <div style="position:absolute;right:-30px;top:-30px;width:120px;height:120px;border-radius:50%;background:radial-gradient(circle,rgba(197,160,40,0.15),transparent);pointer-events:none;"></div>

    <div style="display:flex; align-items:center; gap:16px; position:relative;">
        <!-- Avatar -->
        <div style="position:relative; flex-shrink:0;">
            <div style="width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,rgba(197,160,40,0.3),rgba(197,160,40,0.1));border:1.5px solid rgba(197,160,40,0.4);display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:700;color:#C5A028;">
                {{ strtoupper(substr(Auth::user()->name,0,1)) }}
            </div>
            <div style="position:absolute;bottom:-3px;right:-3px;width:14px;height:14px;border-radius:50%;background:#22c55e;border:2px solid #0C0806;"></div>
        </div>
        <!-- Info -->
        <div style="flex:1; min-width:0;">
            <div style="font-size:1.15rem;font-weight:800;color:#fff;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                {{ $personnel->stage_name ?? Auth::user()->name }}
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:6px;align-items:center;">
                <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;background:rgba(197,160,40,0.15);color:#C5A028;border:1px solid rgba(197,160,40,0.3);">
                    <i class="bi bi-music-note-list"></i> {{ ucfirst($personnel->specialty ?? 'Personel') }}
                </span>
                @if($personnel->is_backup)
                <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.4);border:1px solid rgba(255,255,255,0.1);">
                    Cadangan
                </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0;margin-top:20px;padding-top:16px;border-top:1px solid rgba(255,255,255,0.08);">
        <div style="text-align:center;">
            <div style="font-family:'Cormorant Garamond',serif;font-size:1.6rem;font-weight:700;color:#C5A028;line-height:1;">{{ $upcomingEvents->count() }}</div>
            <div style="font-size:0.6rem;text-transform:uppercase;letter-spacing:0.09em;color:rgba(255,255,255,0.4);margin-top:3px;">Event Mendatang</div>
        </div>
        <div style="text-align:center;border-left:1px solid rgba(255,255,255,0.08);border-right:1px solid rgba(255,255,255,0.08);">
            <div style="font-family:'Cormorant Garamond',serif;font-size:1.6rem;font-weight:700;color:#fff;line-height:1;">{{ $upcomingEvents->where('pivot.checked_in_at','!=',null)->count() }}</div>
            <div style="font-size:0.6rem;text-transform:uppercase;letter-spacing:0.09em;color:rgba(255,255,255,0.4);margin-top:3px;">Sudah Absen</div>
        </div>
        <div style="text-align:center;">
            @php $fee = $upcomingEvents->sum('pivot.fee'); @endphp
            <div style="font-family:'Cormorant Garamond',serif;font-size:1.6rem;font-weight:700;color:#C5A028;line-height:1;">
                {{ $fee > 0 ? number_format($fee/1000000,1).'jt' : '–' }}
            </div>
            <div style="font-size:0.6rem;text-transform:uppercase;letter-spacing:0.09em;color:rgba(255,255,255,0.4);margin-top:3px;">Est. Honor</div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════ --}}
{{-- SECTION 2: KALENDER --}}
{{-- ═══════════════════════════════════════ --}}
<div class="fu1 glass" style="padding:20px; margin-bottom:16px;">
    <!-- Header Kalender -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <div>
            <div style="font-family:'Cormorant Garamond',serif;font-size:1.1rem;font-weight:700;color:#fff;">Kalender Jadwal</div>
            <div style="font-size:0.6rem;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.35);margin-top:1px;">{{ $firstDay->translatedFormat('F Y') }}</div>
        </div>
        <div style="display:flex;gap:12px;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.08em;color:rgba(255,255,255,0.35);">
            <span style="display:flex;align-items:center;gap:5px;"><span style="width:6px;height:6px;border-radius:50%;background:#C5A028;display:inline-block;"></span>Event</span>
            <span style="display:flex;align-items:center;gap:5px;"><span style="width:6px;height:6px;border-radius:50%;background:#ef4444;display:inline-block;"></span>Mepet</span>
        </div>
    </div>

    <!-- Day Headers -->
    <div style="display:grid;grid-template-columns:repeat(7,1fr);margin-bottom:6px;">
        @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $d)
        <div style="text-align:center;font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:rgba(255,255,255,0.25);padding:4px 0;">{{ $d }}</div>
        @endforeach
    </div>

    <!-- Calendar Days -->
    <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:2px;">
        @for($i=0;$i<$startDow;$i++)<div></div>@endfor

        @for($day=1; $day<=$daysInMonth; $day++)
        @php
            $dateStr = \Carbon\Carbon::create($thisYear,$thisMonth,$day)->format('Y-m-d');
            $isToday    = $dateStr === $now->toDateString();
            $hasEvent   = in_array($dateStr,$eventDates);
            $isUrgent   = in_array($dateStr,$urgentDates);
        @endphp
        <div onclick="scrollToEvent('{{ $dateStr }}')"
             {!! 'style="aspect-ratio:1/1;display:flex;flex-direction:column;align-items:center;justify-content:center;border-radius:50%;font-size:0.78rem;font-weight:' . ($isToday || $hasEvent ? '700' : '500') . ';cursor:' . ($hasEvent ? 'pointer' : 'default') . ';position:relative;transition:all 0.15s;color:' . ($isToday ? '#0C0806' : ($hasEvent ? '#fff' : 'rgba(255,255,255,0.4)')) . ';background:' . ($isToday ? '#C5A028' : ($hasEvent && !$isToday ? 'rgba(197,160,40,0.12)' : 'transparent')) . ';"' !!}>
            {{ $day }}
            @if($hasEvent && !$isToday)
            <span {!! 'style="position:absolute;bottom:3px;width:4px;height:4px;border-radius:50%;background:' . ($isUrgent ? '#ef4444' : '#C5A028') . ';"' !!}></span>
            @endif
        </div>
        @endfor
    </div>

    <div style="text-align:center;margin-top:14px;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.09em;color:rgba(255,255,255,0.22);">
        Klik tanggal bertanda untuk melihat detail tugas
    </div>
</div>

{{-- ═══════════════════════════════════════ --}}
{{-- SECTION 3: BANNER PENGUMUMAN --}}
{{-- ═══════════════════════════════════════ --}}
<div class="fu2" style="
    display:flex;align-items:center;gap:12px;
    padding:14px 16px;
    border-radius:14px;
    background:rgba(197,160,40,0.07);
    border:1px solid rgba(197,160,40,0.2);
    margin-bottom:16px;
">
    <i class="bi bi-megaphone-fill" style="color:#C5A028;font-size:1.1rem;flex-shrink:0;"></i>
    <p style="flex:1;font-size:0.8rem;color:rgba(255,255,255,0.65);margin:0;line-height:1.5;">
        <span style="color:#C5A028;font-weight:700;">Pengumuman:</span>
        Konfirmasi kehadiran sebelum H-3. Absensi GPS wajib dilakukan di lokasi acara.
    </p>
    <a href="https://wa.me/6281234567890?text=Halo+Pak+Yat" target="_blank"
       style="flex-shrink:0;display:inline-flex;align-items:center;gap:5px;padding:7px 12px;border-radius:10px;font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.09em;text-decoration:none;background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);color:#4ade80;white-space:nowrap;">
        <i class="bi bi-whatsapp"></i> Chat
    </a>
</div>

{{-- ═══════════════════════════════════════ --}}
{{-- SECTION 4: TUGAS MENDATANG --}}
{{-- ═══════════════════════════════════════ --}}
<div class="fu3">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <div style="font-family:'Cormorant Garamond',serif;font-size:1.2rem;font-weight:700;color:#fff;">Tugas Mendatang</div>
        <span style="padding:4px 12px;border-radius:99px;font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.09em;background:rgba(197,160,40,0.1);color:#C5A028;border:1px solid rgba(197,160,40,0.2);">
            {{ $upcomingEvents->count() }} Event
        </span>
    </div>

    @forelse($upcomingEvents as $event)
    @php
        $eDate     = \Carbon\Carbon::parse($event->event_date);
        $daysLeft  = $now->startOfDay()->diffInDays($eDate->startOfDay(), false);
        $urgent    = ($daysLeft >= 0 && $daysLeft <= 3);
        $checkedIn = !empty($event->pivot->checked_in_at);
        $hasCoords = $event->latitude && $event->longitude;
    @endphp

    <div id="evt-{{ $event->event_date }}"
         @php echo 'style="border-radius:18px;overflow:hidden;margin-bottom:14px;background:rgba(255,255,255,0.04);border:1px solid ' . ($urgent ? 'rgba(239,68,68,0.3)' : 'rgba(255,255,255,0.08)') . ';transition:all 0.25s;' . ($urgent ? 'box-shadow:0 4px 24px rgba(239,68,68,0.1);' : '') . '"'; @endphp>
        <!-- Card Top Bar -->
        <div style="
            padding:14px 18px;
            display:flex;align-items:center;justify-content:space-between;gap:10px;
            border-bottom:1px solid rgba(255,255,255,0.05);
        ">
            <div style="display:flex;align-items:center;gap:14px;">
                <!-- Date Pill -->
                <div {!! 'style="flex-shrink:0;width:52px;height:52px;border-radius:13px;background:' . ($urgent ? 'rgba(239,68,68,0.12)' : 'rgba(197,160,40,0.1)') . ';border:1px solid ' . ($urgent ? 'rgba(239,68,68,0.3)' : 'rgba(197,160,40,0.25)') . ';display:flex;flex-direction:column;align-items:center;justify-content:center;"' !!}>
                    <span {!! 'style="font-family:Cormorant Garamond,serif;font-size:1.3rem;font-weight:700;line-height:1;color:' . ($urgent ? '#fca5a5' : '#C5A028') . ';"' !!}>{{ $eDate->format('d') }}</span>
                    <span {!! 'style="font-size:0.52rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:' . ($urgent ? '#fca5a5' : '#C5A028') . ';"' !!}>{{ $eDate->format('M') }}</span>
                </div>
                <div>
                    <div style="font-size:1rem;font-weight:800;color:#fff;margin-bottom:4px;">
                        {{ $event->booking->client_name ?? 'Event Sanggar' }}
                    </div>
                    <div {!! 'style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:' . ($urgent ? '#fca5a5' : 'rgba(197,160,40,0.75)') . ';display:flex;align-items:center;gap:5px;"' !!}>
                        @if($urgent)
                            <i class="bi bi-fire"></i>
                            @if($daysLeft==0) Hari Ini! @elseif($daysLeft==1) Besok! @else H-{{ $daysLeft }} @endif
                        @else
                            <i class="bi bi-calendar3"></i>
                            {{ $eDate->translatedFormat('l, d F Y') }}
                        @endif
                    </div>
                </div>
            </div>
            <!-- Status Badge -->
            @if($checkedIn)
            <span style="flex-shrink:0;padding:5px 10px;border-radius:99px;font-size:0.58rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;background:rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2);">
                <i class="bi bi-check-circle-fill"></i> Hadir
            </span>
            @else
            <span style="flex-shrink:0;padding:5px 10px;border-radius:99px;font-size:0.58rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;background:rgba(255,255,255,0.04);color:rgba(255,255,255,0.3);border:1px solid rgba(255,255,255,0.08);">
                <i class="bi bi-clock"></i> Belum
            </span>
            @endif
        </div>

        <!-- Card Details Grid -->
        <div style="padding:16px 18px;display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <!-- Lokasi -->
            <div style="grid-column:1/-1;">
                <div style="font-size:0.58rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.28);margin-bottom:5px;display:flex;align-items:center;gap:4px;">
                    <i class="bi bi-geo-alt-fill" style="color:#C5A028;"></i> Lokasi
                </div>
                <a href="https://maps.google.com/?q={{ urlencode($event->venue) }}" target="_blank"
                   style="font-size:0.85rem;font-weight:600;color:rgba(255,255,255,0.75);text-decoration:none;display:flex;align-items:flex-start;gap:5px;transition:color 0.2s;">
                    <span>{{ $event->venue }}</span>
                    <i class="bi bi-box-arrow-up-right" style="font-size:0.6rem;color:rgba(255,255,255,0.3);margin-top:3px;flex-shrink:0;"></i>
                </a>
            </div>

            <!-- Jam -->
            <div>
                <div style="font-size:0.58rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.28);margin-bottom:5px;display:flex;align-items:center;gap:4px;">
                    <i class="bi bi-clock-fill" style="color:#C5A028;"></i> Jam
                </div>
                <div style="font-size:0.9rem;font-weight:700;color:#fff;">
                    {{ \Carbon\Carbon::parse($event->event_start)->format('H:i') }}<span style="color:rgba(255,255,255,0.3);"> – </span>{{ \Carbon\Carbon::parse($event->event_end)->format('H:i') }}<span style="font-size:0.7rem;font-weight:500;color:rgba(255,255,255,0.4);"> WIB</span>
                </div>
            </div>

            <!-- Jobdesk -->
            <div>
                <div style="font-size:0.58rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.28);margin-bottom:5px;display:flex;align-items:center;gap:4px;">
                    <i class="bi bi-person-badge-fill" style="color:#C5A028;"></i> Jobdesk
                </div>
                <div style="font-size:0.85rem;font-weight:700;color:#fff;text-transform:capitalize;">
                    {{ str_replace('_', ' ', $event->pivot->role_in_event ?? '–') }}
                </div>
            </div>
        </div>

        <!-- Ghosting Guard CTA -->
        <div style="padding:0 16px 16px;">
            @if($checkedIn)
            <div style="
                display:flex;align-items:center;justify-content:center;gap:10px;
                padding:14px;border-radius:13px;
                background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);
            ">
                <i class="bi bi-shield-check" style="font-size:1.2rem;color:#4ade80;"></i>
                <div>
                    <div style="font-size:0.85rem;font-weight:700;color:#4ade80;">Check-in Berhasil</div>
                    <div style="font-size:0.7rem;color:rgba(255,255,255,0.4);">
                        Pukul {{ \Carbon\Carbon::parse($event->pivot->checked_in_at)->format('H:i') }} WIB
                        @if(isset($event->pivot->attendance_status) && $event->pivot->attendance_status === 'late')
                            · <span style="color:#fb923c;">Telat {{ $event->pivot->late_minutes ?? 0 }} mnt</span>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <button type="button"
                    onclick="doCheckIn('{{ $event->id }}', this)"
                    style="
                        width:100%;display:flex;align-items:center;justify-content:center;gap:10px;
                        padding:14px;border-radius:13px;
                        background:linear-gradient(135deg,#8B1A2A,#5C0E19);
                        border:1px solid rgba(197,160,40,0.35);
                        color:#C5A028;font-size:0.85rem;font-weight:700;
                        cursor:pointer;transition:all 0.25s;
                        box-shadow:0 4px 20px rgba(139,26,42,0.3);
                    "
                    onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 8px 28px rgba(139,26,42,0.4)'"
                    onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 20px rgba(139,26,42,0.3)'">
                <i class="bi bi-geo-alt-fill" style="font-size:1.1rem;"></i>
                <div style="text-align:left;">
                    <div>Ghosting Guard – Check-in Lokasi</div>
                    <div style="font-size:0.65rem;font-weight:500;color:rgba(197,160,40,0.6);">{{ $hasCoords ? 'Validasi GPS Aktif · Radius 100m' : 'Lokasi GPS belum diset Admin' }}</div>
                </div>
            </button>
            <form id="cf-{{ $event->id }}" action="{{ route('personnel.attendance.check_in', $event->id) }}" method="POST" style="display:none;">
                @csrf
                <input type="hidden" name="latitude"  id="lat-{{ $event->id }}">
                <input type="hidden" name="longitude" id="lng-{{ $event->id }}">
            </form>
            @endif
        </div>
    </div>
    @empty
    <div style="text-align:center;padding:56px 24px;border-radius:18px;background:rgba(255,255,255,0.025);border:1px dashed rgba(255,255,255,0.08);">
        <i class="bi bi-calendar-x" style="font-size:3rem;color:rgba(255,255,255,0.12);display:block;margin-bottom:14px;"></i>
        <div style="font-size:1.05rem;font-weight:700;color:#fff;margin-bottom:6px;">Belum Ada Tugas</div>
        <div style="font-size:0.8rem;color:rgba(255,255,255,0.35);">Tunggu instruksi dari Admin Sanggar Cahaya Gumilang.</div>
    </div>
    @endforelse
</div>

@endif
@endsection

@push('scripts')
<script>
function doCheckIn(eventId, btn) {
    if (!navigator.geolocation) { alert('GPS tidak didukung browser ini.'); return; }
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.style.opacity = '0.65';
    btn.innerHTML = '<i class="bi bi-arrow-repeat" style="animation:spin 1s linear infinite;font-size:1.1rem;"></i> <div style="text-align:left;"><div>Mendeteksi GPS...</div><div style="font-size:0.65rem;color:rgba(197,160,40,0.6);">Pastikan izin lokasi diizinkan</div></div>';
    navigator.geolocation.getCurrentPosition(
        function(pos) {
            if (!navigator.onLine) {
                let pend = JSON.parse(localStorage.getItem('pendingCheckins')||'[]');
                pend.push({ eventId, latitude:pos.coords.latitude, longitude:pos.coords.longitude, timestamp:new Date().toISOString() });
                localStorage.setItem('pendingCheckins', JSON.stringify(pend));
                btn.innerHTML = '<i class="bi bi-clock-history" style="font-size:1.1rem;"></i><div style="text-align:left;"><div>Tersimpan Offline</div><div style="font-size:0.65rem;color:rgba(197,160,40,0.6);">Akan disinkronkan saat online</div></div>';
                btn.style.opacity = '1';
            } else {
                document.getElementById('lat-'+eventId).value = pos.coords.latitude;
                document.getElementById('lng-'+eventId).value = pos.coords.longitude;
                document.getElementById('cf-'+eventId).submit();
            }
        },
        function(err) {
            btn.innerHTML = orig; btn.disabled = false; btn.style.opacity = '1';
            alert('❌ Gagal mendapatkan lokasi GPS.\nPastikan GPS aktif dan izin lokasi disetujui.');
        },
        { enableHighAccuracy:true, timeout:12000, maximumAge:0 }
    );
}

function scrollToEvent(dateStr) {
    const el = document.getElementById('evt-'+dateStr);
    if (!el) return;
    el.scrollIntoView({ behavior:'smooth', block:'center' });
    el.style.outline = '2px solid rgba(197,160,40,0.6)';
    el.style.outlineOffset = '2px';
    setTimeout(() => { el.style.outline = ''; el.style.outlineOffset = ''; }, 2000);
}
</script>
<style>
@keyframes spin { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }
</style>
@endpush
