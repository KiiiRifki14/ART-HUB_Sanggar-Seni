@extends('layouts.admin')

@section('title', 'Dashboard – ART-HUB')
@section('page_title', 'Executive Dashboard')
@section('page_subtitle', 'Ringkasan finansial & penjadwalan Sanggar Cahaya Gumilang.')

@section('content')

{{-- ── ROW 1: STAT CARDS ── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    {{-- Card 1 --}}
    <div class="card-gold flex flex-col justify-between p-5">
        <div class="flex justify-between items-start mb-3">
            <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,rgba(197,160,40,0.2),rgba(139,26,42,0.15));border:1px solid rgba(197,160,40,0.3);display:flex;align-items:center;justify-content:center;color:#C5A028;">
                <i data-lucide="lock" class="w-5 h-5"></i>
            </div>
            <span class="badge-maroon">
                <i data-lucide="lock" class="w-3 h-3"></i> Terkunci
            </span>
        </div>
        <div>
            <div class="subtitle-gold mb-1">Laba Tetap Aman</div>
            <div class="title-gold" style="font-size:1.6rem;">Rp {{ number_format($lockedProfit, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Card 2 --}}
    <div class="card-gold flex flex-col justify-between p-5">
        <div class="flex justify-between items-start mb-3">
            <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,rgba(22,163,74,0.15),rgba(22,163,74,0.05));border:1px solid rgba(22,163,74,0.3);display:flex;align-items:center;justify-content:center;color:#16a34a;">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
            </div>
            <span class="badge-green">Aman</span>
        </div>
        <div>
            <div class="subtitle-gold mb-1">Dana Cadangan Siap</div>
            <div class="title-gold" style="font-size:1.6rem; color:#16a34a;">Rp {{ number_format($safetyBuffer, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Card 3 --}}
    <div class="card-gold flex flex-col justify-between p-5 relative overflow-hidden" style="border-color: rgba(220,38,38,0.3);">
        @if($lateCount > 0)
        <div class="absolute top-0 right-0 bg-red-600 text-white text-[0.65rem] font-bold px-3 py-1 rounded-bl-xl uppercase tracking-wider shadow-sm">
            {{ $lateCount }} Insiden
        </div>
        @endif
        <div class="flex justify-between items-start mb-3">
            <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,rgba(220,38,38,0.15),rgba(220,38,38,0.05));border:1px solid rgba(220,38,38,0.3);display:flex;align-items:center;justify-content:center;color:#dc2626;">
                <i data-lucide="alert-octagon" class="w-5 h-5"></i>
            </div>
            <span style="display:inline-flex;align-items:center;padding:4px 10px;border-radius:6px;font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;background:linear-gradient(135deg,rgba(220,38,38,0.1),rgba(220,38,38,0.02));color:#dc2626;border:1px solid rgba(220,38,38,0.2);">Denda</span>
        </div>
        <div>
            <div class="subtitle-gold mb-1">Denda Kru Masuk</div>
            <div class="title-gold" style="font-size:1.6rem; color:#dc2626;">Rp {{ number_format($totalPenalty, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Card 4 --}}
    <div class="card-gold flex flex-col justify-between p-5">
        <div class="flex justify-between items-start mb-3">
            <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,rgba(197,160,40,0.2),rgba(197,160,40,0.05));border:1px solid rgba(197,160,40,0.3);display:flex;align-items:center;justify-content:center;color:#bfa000;">
                <i data-lucide="calendar-days" class="w-5 h-5"></i>
            </div>
            @if($needPlotting > 0)
            <span class="badge-gold">
                {{ $needPlotting }} Butuh Plot
            </span>
            @endif
        </div>
        <div>
            <div class="subtitle-gold mb-1">Acara Bulan Ini</div>
            <div class="title-gold" style="font-size:1.6rem;">{{ $eventCount }} <span style="font-size:0.9rem; font-family:'Inter',sans-serif; color:#847B78; font-weight:500;">acara</span></div>
        </div>
    </div>
</div>

{{-- ── ROW 2: CHARTS ── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-5">
    {{-- Chart 1: Revenue Line Chart (6 bulan) --}}
    <div class="lg:col-span-2 card-gold p-5">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 gap-3">
            <div>
                <h3 class="title-gold flex items-center gap-2" style="font-size:1.2rem;">
                    <i data-lucide="trending-up" class="w-5 h-5 text-yellow-600"></i>
                    Pendapatan & Laba Bersih
                </h3>
                <p class="subtitle-gold mt-1">Proyeksi 6 Bulan (Termasuk Acara Mendatang)</p>
            </div>
            <div class="flex gap-4 subtitle-gold" style="font-size:0.7rem;">
                <span class="flex items-center gap-1.5"><span style="width:10px;height:10px;border-radius:3px;background:#8B1A2A"></span> Pendapatan</span>
                <span class="flex items-center gap-1.5"><span style="width:10px;height:10px;border-radius:3px;background:#fcd400"></span> Laba Tetap</span>
            </div>
        </div>
        <div class="relative h-[280px] w-full">
            <canvas id="chartRevenue"></canvas>
        </div>
    </div>

    {{-- Chart 2: Status Donut --}}
    <div class="card-gold p-5 flex flex-col">
        <div class="mb-4">
            <h3 class="title-gold flex items-center gap-2" style="font-size:1.2rem;">
                <i data-lucide="pie-chart" class="w-5 h-5 text-yellow-600"></i>
                Distribusi Status
            </h3>
            <p class="subtitle-gold mt-1">Semua waktu booking</p>
        </div>
        <div class="flex-grow flex items-center justify-center relative min-h-[180px]">
            <canvas id="chartStatus"></canvas>
        </div>
    </div>
</div>

{{-- ── ROW 3: UPCOMING EVENTS + ALERTS ── --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
    <div class="lg:col-span-7 card-gold p-5">
        <div class="flex justify-between items-center mb-4">
            <h3 class="title-gold flex items-center gap-2" style="font-size:1.2rem;">
                <i data-lucide="clock" class="w-5 h-5 text-yellow-600"></i>
                Acara Mendatang
            </h3>
            <a href="{{ route('admin.events.monitoring') }}" class="subtitle-gold text-yellow-700 hover:text-yellow-600" style="text-decoration:none;">Lihat Semua &rarr;</a>
        </div>

        <div class="space-y-3">
            @forelse($upcomingEvents as $ev)
            @php
                $date = \Carbon\Carbon::parse($ev->event_date);
                $daysLeft = now()->startOfDay()->diffInDays($date->startOfDay(), false);
                $statusColors = ['ready'=>'text-green-700 bg-green-500/10 border-green-500/30','planning'=>'text-red-700 bg-red-500/10 border-red-500/30','confirmed'=>'text-blue-700 bg-blue-500/10 border-blue-500/30'];
                $sc = $statusColors[$ev->status] ?? 'text-gray-700 bg-gray-500/10 border-gray-500/30';
            @endphp
            <div style="background:linear-gradient(135deg,rgba(0,0,0,0.01),rgba(0,0,0,0.03));border:1px solid rgba(197,160,40,0.2);border-radius:12px;padding:12px;display:flex;align-items:center;gap:14px;transition:all 0.3s;" onmouseover="this.style.background='rgba(197,160,40,0.05)'" onmouseout="this.style.background='linear-gradient(135deg,rgba(0,0,0,0.01),rgba(0,0,0,0.03))'">
                <div style="width:50px;height:50px;background:#fff;border:1px solid rgba(197,160,40,0.3);border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;box-shadow:0 4px 10px rgba(0,0,0,0.04);flex-shrink:0;">
                    <span style="font-family:'Cormorant Garamond',serif;font-size:1.2rem;font-weight:700;color:#8B1A2A;line-height:1;">{{ $date->format('d') }}</span>
                    <span style="font-family:'Inter',sans-serif;font-size:0.6rem;font-weight:700;color:#C5A028;text-transform:uppercase;margin-top:2px;">{{ $date->format('M') }}</span>
                </div>
                <div class="flex-grow min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span style="font-family:'Inter',sans-serif;font-weight:700;font-size:0.95rem;color:#1A1817;" class="truncate">{{ $ev->booking->client_name ?? 'Event Sanggar' }}</span>
                        <span class="inline-block px-2 py-0.5 rounded-md text-[0.6rem] font-bold uppercase tracking-wider border {{ $sc }}">{{ $ev->status }}</span>
                    </div>
                    <div style="font-family:'Inter',sans-serif;font-size:0.75rem;color:#847B78;display:flex;align-items:center;gap:10px;" class="truncate">
                        <span class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#C5A028]"></i>{{ $ev->venue }}</span>
                        <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3.5 h-3.5 text-[#C5A028]"></i>{{ \Carbon\Carbon::parse($ev->event_start)->format('H:i') }}</span>
                    </div>
                </div>
                <div class="flex-shrink-0 text-right">
                    @if($daysLeft <= 3 && $daysLeft >= 0)
                        <span class="badge-maroon" style="padding:4px 8px;font-size:0.65rem;">★ H-{{ $daysLeft }}</span>
                    @else
                        <span class="subtitle-gold" style="font-size:0.65rem;">H-{{ $daysLeft }}</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-8" style="background:rgba(0,0,0,0.02);border-radius:12px;border:1px dashed rgba(197,160,40,0.3);">
                <i data-lucide="calendar-x" class="w-10 h-10 mx-auto mb-3 text-[#C5A028] opacity-50"></i>
                <p style="font-family:'Inter',sans-serif;font-size:0.85rem;color:#847B78;">Tidak ada event mendatang.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-5">
            <a href="{{ route('admin.events.monitoring') }}" style="display:block;width:100%;padding:12px;border-radius:10px;text-align:center;background:rgba(197,160,40,0.08);border:1px solid rgba(197,160,40,0.2);color:#8B1A2A;font-family:'Inter',sans-serif;font-weight:700;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.05em;transition:all 0.3s;" onmouseover="this.style.background='rgba(197,160,40,0.15)'" onmouseout="this.style.background='rgba(197,160,40,0.08)'">
                Buka Event Monitoring
            </a>
        </div>
    </div>

    <div class="lg:col-span-5 card-gold p-5 flex flex-col h-full">
        <h3 class="title-gold flex items-center gap-2 mb-4" style="font-size:1.2rem;">
            <i data-lucide="bell" class="w-5 h-5 text-yellow-600"></i>
            Sistem Alert
        </h3>

        <div class="space-y-3 flex-grow">
            @php $hasAlert = false; @endphp

            {{-- DP Verification alert --}}
            @php $dpPending = \App\Models\Booking::where('status','pending')->whereNotNull('payment_proof')->count(); @endphp
            @if($dpPending > 0)
            @php $hasAlert = true; @endphp
            <div style="background:rgba(139,26,42,0.05);border-left:4px solid #8B1A2A;border-radius:0 12px 12px 0;padding:12px 16px;display:flex;gap:12px;">
                <i data-lucide="check-circle" class="w-5 h-5 text-[#8B1A2A] flex-shrink-0 mt-0.5"></i>
                <div>
                    <h4 style="font-family:'Inter',sans-serif;font-size:0.85rem;font-weight:700;color:#8B1A2A;margin-bottom:4px;">{{ $dpPending }} Bukti DP Menunggu Verifikasi</h4>
                    <p style="font-family:'Inter',sans-serif;font-size:0.75rem;color:#847B78;margin-bottom:8px;">Konfirmasi untuk mengunci jadwal.</p>
                    <a href="{{ route('admin.bookings.dp_verification') }}" class="subtitle-gold text-[#8B1A2A] hover:text-[#5C0E19]" style="font-size:0.65rem;text-decoration:underline;">
                        Verifikasi Sekarang &rarr;
                    </a>
                </div>
            </div>
            @endif

            {{-- Budget kritis --}}
            @php $criticalBudget = \App\Models\FinancialRecord::where('budget_warning', true)->count(); @endphp
            @if($criticalBudget > 0)
            @php $hasAlert = true; @endphp
            <div style="background:rgba(234,88,12,0.05);border-left:4px solid #ea580c;border-radius:0 12px 12px 0;padding:12px 16px;display:flex;gap:12px;">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5"></i>
                <div>
                    <h4 style="font-family:'Inter',sans-serif;font-size:0.85rem;font-weight:700;color:#c2410c;margin-bottom:4px;">{{ $criticalBudget }} Event Budget Kritis</h4>
                    <p style="font-family:'Inter',sans-serif;font-size:0.75rem;color:#847B78;margin-bottom:8px;">Dana operasional hampir habis/minus.</p>
                    <a href="{{ route('admin.financials.post_event_list') }}" class="subtitle-gold text-orange-700 hover:text-orange-800" style="font-size:0.65rem;text-decoration:underline;">
                        Cek Laporan &rarr;
                    </a>
                </div>
            </div>
            @endif

            {{-- Kostum overdue --}}
            @php
                $overdueKostum = \App\Models\CostumeRental::where('status','rented')->where('due_date','<', now()->toDateString())->whereNull('returned_date')->count();
            @endphp
            @if($overdueKostum > 0)
            @php $hasAlert = true; @endphp
            <div style="background:rgba(220,38,38,0.05);border-left:4px solid #dc2626;border-radius:0 12px 12px 0;padding:12px 16px;display:flex;gap:12px;">
                <i data-lucide="archive" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                <div>
                    <h4 style="font-family:'Inter',sans-serif;font-size:0.85rem;font-weight:700;color:#b91c1c;margin-bottom:4px;">{{ $overdueKostum }} Kostum Telat</h4>
                    <p style="font-family:'Inter',sans-serif;font-size:0.75rem;color:#847B78;margin-bottom:8px;">Vendor menunggu pengembalian kostum.</p>
                    <a href="{{ route('admin.costumes.index') }}" class="subtitle-gold text-red-700 hover:text-red-800" style="font-size:0.65rem;text-decoration:underline;">
                        Urus Pengembalian &rarr;
                    </a>
                </div>
            </div>
            @endif

            @if(!$hasAlert)
            <div class="flex flex-col items-center justify-center h-full py-8 text-center">
                <div style="width:54px;height:54px;border-radius:50%;background:rgba(22,163,74,0.1);display:flex;align-items:center;justify-content:center;color:#16a34a;margin-bottom:12px;">
                    <i data-lucide="check" class="w-7 h-7"></i>
                </div>
                <h4 class="title-gold" style="font-size:1.1rem;margin-bottom:4px;">Semua Aman</h4>
                <p class="subtitle-gold">Tidak ada alert sistem aktif</p>
            </div>
            @endif
        </div>

        <div class="mt-5 pt-5 border-t" style="border-color:rgba(197,160,40,0.2);">
            <a href="{{ route('admin.events.index') }}" style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:12px;border-radius:10px;background:linear-gradient(135deg,#8B1A2A,#5C0E19);color:#fcd400;font-family:'Inter',sans-serif;font-weight:700;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.05em;transition:all 0.3s;box-shadow:0 4px 15px rgba(139,26,42,0.3);" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 20px rgba(139,26,42,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(139,26,42,0.3)'">
                <i data-lucide="arrow-right" class="w-4 h-4"></i> Buka Event Management
            </a>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="application/json" id="arhChartData">{!! $chartPayload !!}</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    Chart.defaults.color = '#827471';
    Chart.defaults.borderColor = 'rgba(212,195,191,0.2)';
    Chart.defaults.font.family = 'Manrope, sans-serif';

    const _d = JSON.parse(document.getElementById('arhChartData').textContent);
    const revenueData = _d.revenue;

    // ── CHART 1: Revenue & Profit
    const revCtx = document.getElementById('chartRevenue').getContext('2d');
    new Chart(revCtx, {
        type: 'bar',
        data: {
            labels: revenueData.map(d => d.label),
            datasets: [
                {
                    label: 'Pendapatan',
                    data: revenueData.map(d => d.revenue),
                    backgroundColor: '#8B1A2A', // maroon
                    borderRadius: 4, borderSkipped: false,
                    barPercentage: 0.6,
                },
                {
                    label: 'Laba Tetap',
                    data: revenueData.map(d => d.profit),
                    backgroundColor: '#fcd400', // gold
                    borderRadius: 4, borderSkipped: false,
                    barPercentage: 0.6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 15,
                    bottom: 0,
                    left: -5,
                    right: 0
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(54,31,26,0.95)',
                    titleFont: { family: 'Manrope', size: 12, weight: 'bold' },
                    bodyFont: { family: 'Manrope', size: 12 },
                    padding: 8,
                    cornerRadius: 6,
                    callbacks: { label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID') }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { weight: '600', size: 10 } } },
                y: {
                    border: { display: false },
                    ticks: { font: { size: 10 }, callback: v => 'Rp ' + (v >= 1000000 ? (v/1000000).toFixed(0) + 'jt' : v) }
                }
            }
        }
    });

    // ── CHART 2: Status Donut
    const stCtx = document.getElementById('chartStatus').getContext('2d');
    new Chart(stCtx, {
        type: 'doughnut',
        data: {
            labels: _d.stLabels,
            datasets: [{
                data: _d.stData,
                backgroundColor: ['#fcd400', '#8B1A2A', '#C5A028', '#5C0E19', '#d4c3bf'], // Premium maroon-gold palette
                borderColor: '#ffffff',
                borderWidth: 2,
                hoverOffset: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(54,31,26,0.95)',
                    bodyFont: { family: 'Manrope', size: 12, weight: 'bold' },
                    padding: 8,
                    cornerRadius: 6,
                    callbacks: { label: ctx => ' Booking ' + ctx.label + ': ' + ctx.parsed }
                }
            }
        }
    });
</script>
@endsection
