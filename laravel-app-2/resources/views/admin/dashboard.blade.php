@extends('layouts.admin')

@section('title', 'Dashboard – ART-HUB')
@section('page_title', 'Executive Dashboard')
@section('page_subtitle', 'Ringkasan finansial & penjadwalan Sanggar Cahaya Gumilang.')

@section('styles')
<style>
    .stat-card {
        background: #1a1a1a; border: 1px solid #252525; border-radius: 14px;
        padding: 20px 22px; height: 100%; transition: border-color 0.2s;
    }
    .stat-card:hover { border-color: rgba(197,160,89,0.35); }
    .stat-card.gold { border-color: rgba(197,160,89,0.4); background: rgba(197,160,89,0.06); }
    .stat-icon {
        width: 44px; height: 44px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1.3rem;
    }
    .stat-label { font-size: 0.72rem; color: #888; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .stat-value { font-size: 1.4rem; font-weight: 700; line-height: 1.1; }

    .chart-card {
        background: #1a1a1a; border: 1px solid #252525; border-radius: 14px;
        padding: 22px; height: 100%;
    }
    .chart-title { font-size: 0.88rem; font-weight: 600; color: #eee; margin-bottom: 0; }
    .chart-sub   { font-size: 0.7rem; color: #666; }

    .event-row {
        display: flex; align-items: center; gap: 14px;
        padding: 12px 0; border-bottom: 1px solid #222;
    }
    .event-row:last-child { border-bottom: none; }
    .event-date-box {
        width: 46px; height: 46px; border-radius: 10px; flex-shrink: 0;
        background: rgba(197,160,89,0.12); border: 1px solid rgba(197,160,89,0.25);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
    }
    .event-date-day  { font-size: 1.1rem; font-weight: 700; color: #c5a059; line-height: 1; }
    .event-date-mon  { font-size: 0.6rem; color: #888; text-transform: uppercase; }

    .status-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
</style>
@endsection

@section('content')

{{-- ── ROW 1: STAT CARDS ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card gold">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-icon" style="background:rgba(197,160,89,0.15);">
                    <i class="bi bi-safe2-fill" style="color:#c5a059;"></i>
                </div>
                <span class="badge" style="background:rgba(197,160,89,0.2);color:#c5a059;border:1px solid rgba(197,160,89,0.4);font-size:0.65rem;">
                    <i class="bi bi-lock-fill me-1"></i>Terkunci
                </span>
            </div>
            <div class="stat-label">Fixed Profit Aman</div>
            <div class="stat-value" style="color:#c5a059;">Rp {{ number_format($lockedProfit, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-icon" style="background:rgba(34,197,94,0.12);">
                    <i class="bi bi-shield-check-fill text-success"></i>
                </div>
            </div>
            <div class="stat-label">Safety Buffer Standby</div>
            <div class="stat-value text-success">Rp {{ number_format($safetyBuffer, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card" style="border-color:rgba(239,68,68,0.3);">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-icon" style="background:rgba(239,68,68,0.12);">
                    <i class="bi bi-exclamation-octagon-fill text-danger"></i>
                </div>
                @if($lateCount > 0)
                <span class="badge bg-danger bg-opacity-25 text-danger" style="font-size:0.65rem;">{{ $lateCount }} Insiden</span>
                @endif
            </div>
            <div class="stat-label">Denda Kru Masuk</div>
            <div class="stat-value text-danger">Rp {{ number_format($totalPenalty, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-icon" style="background:rgba(99,102,241,0.12);">
                    <i class="bi bi-calendar-event-fill" style="color:#818cf8;"></i>
                </div>
                @if($needPlotting > 0)
                <span class="badge" style="background:rgba(99,102,241,0.2);color:#818cf8;font-size:0.65rem;">{{ $needPlotting }} Butuh Plot</span>
                @endif
            </div>
            <div class="stat-label">Event Bulan Ini</div>
            <div class="stat-value text-white">{{ $eventCount }} <span style="font-size:0.9rem;color:#666;">event</span></div>
        </div>
    </div>
</div>

{{-- ── ROW 2: CHARTS ── --}}
<div class="row g-4 mb-4">

    {{-- Chart 1: Revenue Line Chart (6 bulan) --}}
    <div class="col-12 col-lg-8">
        <div class="chart-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="chart-title"><i class="bi bi-graph-up me-2" style="color:#c5a059;"></i>Revenue & Profit Bersih</div>
                    <div class="chart-sub">6 Bulan Terakhir (dari data aktual)</div>
                </div>
                <div class="d-flex gap-3" style="font-size:0.72rem;">
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#c5a059;margin-right:5px;"></span>Revenue</span>
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#4ade80;margin-right:5px;"></span>Fixed Profit</span>
                </div>
            </div>
            <canvas id="chartRevenue" height="100"></canvas>
        </div>
    </div>

    {{-- Chart 2: Status Donut --}}
    <div class="col-12 col-lg-4">
        <div class="chart-card d-flex flex-column">
            <div class="mb-3">
                <div class="chart-title"><i class="bi bi-pie-chart-fill me-2" style="color:#c5a059;"></i>Distribusi Status Booking</div>
                <div class="chart-sub">Semua waktu</div>
            </div>
            <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                <canvas id="chartStatus" style="max-height:200px;"></canvas>
            </div>
            {{-- Legend --}}
            <div class="mt-3 d-flex flex-wrap gap-2 justify-content-center" style="font-size:0.72rem;">
                @php
                    $statusLabels = [
                        'pending'   => ['Negotiation', '#fbbf24'],
                        'dp_paid'   => ['Locked',      '#f97316'],
                        'confirmed' => ['DP 50%',      '#60a5fa'],
                        'paid_full' => ['Lunas',       '#4ade80'],
                        'completed' => ['Completed',   '#86efac'],
                        'cancelled' => ['Cancelled',   '#888'],
                    ];
                @endphp
                @foreach($statusChart as $st => $cnt)
                @php
                    $info    = $statusLabels[$st] ?? [$st, '#666'];
                    $dotStyle = "display:inline-block;width:8px;height:8px;border-radius:50%;background:{$info[1]};margin-right:4px;";
                @endphp
                <span>
                    <span @style([$dotStyle])></span>
                    {{ $info[0] }} ({{ $cnt }})
                </span>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ── ROW 3: UPCOMING EVENTS + ALERTS ── --}}
<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="chart-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="chart-title"><i class="bi bi-radar me-2" style="color:#c5a059;"></i>Upcoming Events</div>
                <a href="{{ route('admin.events.monitoring') }}" class="btn btn-sm btn-outline-secondary" style="font-size:0.75rem;">Lihat Semua</a>
            </div>

            @forelse($upcomingEvents as $ev)
            @php
                $date = \Carbon\Carbon::parse($ev->event_date);
                $daysLeft = now()->startOfDay()->diffInDays($date->startOfDay(), false);
                $statusColors = ['ready'=>'#4ade80','planning'=>'#fbbf24','confirmed'=>'#60a5fa'];
                $sc = $statusColors[$ev->status] ?? '#888';
            @endphp
            <div class="event-row">
                <div class="event-date-box">
                    <div class="event-date-day">{{ $date->format('d') }}</div>
                    <div class="event-date-mon">{{ $date->format('M') }}</div>
                </div>
                <div class="flex-grow-1">
                    @php
                        $statusColors = ['ready'=>'#4ade80','planning'=>'#fbbf24','confirmed'=>'#60a5fa'];
                        $sc = $statusColors[$ev->status] ?? '#888';
                        $dotBgStyle   = "background:{$sc};";
                        $statusTxtStyle = "font-size:0.7rem;color:{$sc};";
                    @endphp
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-semibold text-white" style="font-size:0.88rem;">{{ $ev->booking->client_name ?? 'Event Sanggar' }}</span>
                        <span class="status-dot" @style([$dotBgStyle])></span>
                        <span @style([$statusTxtStyle])>{{ strtoupper($ev->status) }}</span>
                    </div>
                    <div class="text-secondary" style="font-size:0.75rem;">
                        <i class="bi bi-geo-alt me-1"></i>{{ $ev->venue }}
                        &nbsp;·&nbsp;
                        <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($ev->event_start)->format('H:i') }} WIB
                    </div>
                </div>
                <div class="text-end flex-shrink-0">
                    @if($daysLeft <= 3 && $daysLeft >= 0)
                        <span class="badge" style="background:rgba(251,191,36,0.2);color:#fbbf24;border:1px solid rgba(251,191,36,0.4);font-size:0.7rem;">★ H-{{ $daysLeft }}</span>
                    @else
                        <span class="text-secondary" style="font-size:0.75rem;">H-{{ $daysLeft }}</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-4 text-secondary">
                <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                Tidak ada event mendatang.
            </div>
            @endforelse

            <div class="mt-3">
                <a href="{{ route('admin.events.monitoring') }}" class="btn btn-sm w-100"
                   style="background:rgba(197,160,89,0.15);color:#c5a059;border:1px solid rgba(197,160,89,0.3);font-size:0.8rem;">
                    <i class="bi bi-binoculars me-1"></i>Buka Event Monitoring
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="chart-card h-100">
            <div class="chart-title mb-3"><i class="bi bi-bell-fill text-warning me-2"></i>Sistem Alert</div>

            @php $hasAlert = false; @endphp

            {{-- DP Verification badge --}}
            @php $dpPending = \App\Models\Booking::where('status','pending')->whereNotNull('payment_proof')->count(); @endphp
            @if($dpPending > 0)
            @php $hasAlert = true; @endphp
            <div class="d-flex gap-3 mb-3 p-3 rounded-3" style="background:rgba(197,160,89,0.07);border-left:3px solid #c5a059;">
                <i class="bi bi-patch-check-fill fs-5 flex-shrink-0" style="color:#c5a059;margin-top:2px;"></i>
                <div>
                    <div class="fw-semibold text-white" style="font-size:0.83rem;">{{ $dpPending }} Bukti DP Menunggu Verifikasi</div>
                    <div class="text-secondary" style="font-size:0.75rem;">Konfirmasi untuk kunci laba pimpinan.</div>
                    <a href="{{ route('admin.bookings.dp_verification') }}" class="text-warning fw-bold mt-1 d-block" style="font-size:0.75rem;">
                        → Buka DP Verification
                    </a>
                </div>
            </div>
            @endif

            {{-- Budget kritis --}}
            @php
                $criticalBudget = \App\Models\FinancialRecord::where('budget_warning', true)->count();
            @endphp
            @if($criticalBudget > 0)
            @php $hasAlert = true; @endphp
            <div class="d-flex gap-3 mb-3 p-3 rounded-3" style="background:rgba(251,191,36,0.07);border-left:3px solid #fbbf24;">
                <i class="bi bi-exclamation-circle-fill text-warning fs-5 flex-shrink-0" style="margin-top:2px;"></i>
                <div>
                    <div class="fw-semibold text-white" style="font-size:0.83rem;">{{ $criticalBudget }} Event Budget Kritis</div>
                    <div class="text-secondary" style="font-size:0.75rem;">Dana operasional hampir habis.</div>
                    <a href="{{ route('admin.financials.post_event_list') }}" class="text-warning fw-bold mt-1 d-block" style="font-size:0.75rem;">
                        → Cek Post-Event Update
                    </a>
                </div>
            </div>
            @endif

            {{-- Kostum overdue --}}
            @php
                $overdueKostum = \App\Models\CostumeRental::where('status','rented')
                    ->where('due_date','<', now()->toDateString())
                    ->whereNull('returned_date')->count();
            @endphp
            @if($overdueKostum > 0)
            @php $hasAlert = true; @endphp
            <div class="d-flex gap-3 mb-3 p-3 rounded-3" style="background:rgba(239,68,68,0.07);border-left:3px solid #ef4444;">
                <i class="bi bi-bag-x-fill text-danger fs-5 flex-shrink-0" style="margin-top:2px;"></i>
                <div>
                    <div class="fw-semibold text-white" style="font-size:0.83rem;">{{ $overdueKostum }} Kostum Telat Dikembalikan</div>
                    <div class="text-secondary" style="font-size:0.75rem;">Vendor menunggu pengembalian.</div>
                    <a href="{{ route('admin.costumes.index') }}" class="text-danger fw-bold mt-1 d-block" style="font-size:0.75rem;">
                        → Buka Costume Manager
                    </a>
                </div>
            </div>
            @endif

            @if(!$hasAlert)
            <div class="text-center py-4 text-secondary">
                <i class="bi bi-check-circle-fill text-success fs-2 d-block mb-2"></i>
                Semua sistem berjalan normal!
            </div>
            @endif

            <div class="mt-auto pt-2">
                <a href="{{ route('admin.events.index') }}" class="btn btn-sm w-100"
                   style="background:rgba(197,160,89,0.15);color:#c5a059;border:1px solid rgba(197,160,89,0.3);font-size:0.8rem;">
                    <i class="bi bi-arrow-right-circle me-1"></i>Buka Event Management
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
{{-- @php logic is in Route now to prevent IDE linting errors --}}
<script type="application/json" id="arhChartData">{{ $chartPayload }}</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    Chart.defaults.color = '#888';
    Chart.defaults.borderColor = '#252525';
    Chart.defaults.font.family = 'Inter, sans-serif';

    // Read data from JSON island (no Blade in <script> block)
    const _d = JSON.parse(document.getElementById('arhChartData').textContent);
    const revenueData = _d.revenue;

    // ── CHART 1: Revenue & Profit Bar
    const revCtx = document.getElementById('chartRevenue').getContext('2d');
    new Chart(revCtx, {
        type: 'bar',
        data: {
            labels: revenueData.map(d => d.label),
            datasets: [
                {
                    label: 'Revenue',
                    data: revenueData.map(d => d.revenue),
                    backgroundColor: 'rgba(197,160,89,0.75)',
                    borderRadius: 6, borderSkipped: false,
                },
                {
                    label: 'Fixed Profit',
                    data: revenueData.map(d => d.profit),
                    backgroundColor: 'rgba(74,222,128,0.6)',
                    borderRadius: 6, borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID') } }
            },
            scales: {
                x: { grid: { color: '#1e1e1e' }, ticks: { font: { size: 11 } } },
                y: {
                    grid: { color: '#1e1e1e' },
                    ticks: { font: { size: 11 }, callback: v => 'Rp ' + (v >= 1000000 ? (v/1000000).toFixed(0) + 'jt' : v) }
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
                backgroundColor: _d.stColors,
                borderColor: '#1a1a1a',
                borderWidth: 3,
                hoverOffset: 6,
            }]
        },
        options: {
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': ' + ctx.parsed + ' booking' } }
            }
        }
    });
</script>
@endsection
