@extends('layouts.admin')

@section('title', 'Dashboard – ART-HUB')
@section('page_title', 'Executive Dashboard')
@section('page_subtitle', 'Ringkasan finansial & penjadwalan Sanggar Cahaya Gumilang.')

@section('content')

{{-- ── ROW 1: STAT CARDS ── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] hover:-translate-y-1 transition-all">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                <i class="bi bi-safe2-fill text-primary text-xl"></i>
            </div>
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-primary/10 text-primary border border-primary/20 text-[0.65rem] font-bold uppercase tracking-wider">
                <i class="bi bi-lock-fill"></i> Terkunci
            </span>
        </div>
        <div class="font-label text-xs uppercase tracking-widest text-outline font-bold mb-1">Fixed Profit Aman</div>
        <div class="font-headline text-2xl text-primary font-semibold">Rp {{ number_format($lockedProfit, 0, ',', '.') }}</div>
    </div>

    <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] hover:-translate-y-1 transition-all">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center">
                <i class="bi bi-shield-check-fill text-green-600 text-xl"></i>
            </div>
        </div>
        <div class="font-label text-xs uppercase tracking-widest text-outline font-bold mb-1">Safety Buffer Standby</div>
        <div class="font-headline text-2xl text-green-600 font-semibold">Rp {{ number_format($safetyBuffer, 0, ',', '.') }}</div>
    </div>

    <div class="bg-surface-container-lowest rounded-xl p-5 border border-red-500/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] hover:-translate-y-1 transition-all relative overflow-hidden">
        @if($lateCount > 0)
        <div class="absolute top-0 right-0 bg-red-500 text-white text-[0.6rem] font-bold px-2 py-1 rounded-bl-lg uppercase tracking-wider">
            {{ $lateCount }} Insiden
        </div>
        @endif
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-lg bg-red-500/10 flex items-center justify-center">
                <i class="bi bi-exclamation-octagon-fill text-red-600 text-xl"></i>
            </div>
        </div>
        <div class="font-label text-xs uppercase tracking-widest text-outline font-bold mb-1">Denda Kru Masuk</div>
        <div class="font-headline text-2xl text-red-600 font-semibold">Rp {{ number_format($totalPenalty, 0, ',', '.') }}</div>
    </div>

    <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] hover:-translate-y-1 transition-all">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center">
                <i class="bi bi-calendar-event-fill text-secondary text-xl"></i>
            </div>
            @if($needPlotting > 0)
            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-secondary/10 text-secondary border border-secondary/20 text-[0.65rem] font-bold uppercase tracking-wider">
                {{ $needPlotting }} Butuh Plot
            </span>
            @endif
        </div>
        <div class="font-label text-xs uppercase tracking-widest text-outline font-bold mb-1">Event Bulan Ini</div>
        <div class="font-headline text-2xl text-on-surface font-semibold">{{ $eventCount }} <span class="text-sm font-body text-outline font-normal">event</span></div>
    </div>
</div>

{{-- ── ROW 2: CHARTS ── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Chart 1: Revenue Line Chart (6 bulan) --}}
    <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)]">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-6 gap-4">
            <div>
                <h3 class="font-headline text-lg text-primary font-semibold mb-1"><i class="bi bi-graph-up me-2 text-secondary"></i>Revenue & Profit Bersih</h3>
                <p class="font-label text-xs uppercase tracking-widest text-outline">Proyeksi 6 Bulan (Termasuk Acara Mendatang)</p>
            </div>
            <div class="flex gap-4 font-label text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                <span class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-primary"></span> Revenue</span>
                <span class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-secondary"></span> Fixed Profit</span>
            </div>
        </div>
        <div class="relative h-[250px] w-full">
            <canvas id="chartRevenue"></canvas>
        </div>
    </div>

    {{-- Chart 2: Status Donut --}}
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] flex flex-col">
        <div class="mb-6">
            <h3 class="font-headline text-lg text-primary font-semibold mb-1"><i class="bi bi-pie-chart-fill me-2 text-secondary"></i>Distribusi Status</h3>
            <p class="font-label text-xs uppercase tracking-widest text-outline">Semua waktu booking</p>
        </div>
        <div class="flex-grow flex items-center justify-center relative min-h-[200px]">
            <canvas id="chartStatus"></canvas>
        </div>
    </div>
</div>

{{-- ── ROW 3: UPCOMING EVENTS + ALERTS ── --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <div class="lg:col-span-7 bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)]">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-headline text-lg text-primary font-semibold"><i class="bi bi-radar me-2 text-secondary"></i>Upcoming Events</h3>
            <a href="{{ route('admin.events.monitoring') }}" class="font-label text-xs font-bold uppercase tracking-widest text-secondary hover:text-primary transition-colors underline underline-offset-4 decoration-2 decoration-secondary/30">Lihat Semua</a>
        </div>

        <div class="space-y-4">
            @forelse($upcomingEvents as $ev)
            @php
                $date = \Carbon\Carbon::parse($ev->event_date);
                $daysLeft = now()->startOfDay()->diffInDays($date->startOfDay(), false);
                $statusColors = ['ready'=>'text-green-600 bg-green-500/10 border-green-500/20','planning'=>'text-red-600 bg-red-500/10 border-red-500/20','confirmed'=>'text-blue-600 bg-blue-500/10 border-blue-500/20'];
                $sc = $statusColors[$ev->status] ?? 'text-gray-600 bg-gray-500/10 border-gray-500/20';
            @endphp
            <div class="flex items-center gap-4 p-4 rounded-lg bg-surface-container-low border border-outline-variant/20 hover:bg-surface-container transition-colors">
                <div class="flex flex-col items-center justify-center w-14 h-14 rounded-md bg-surface-container-lowest shadow-sm border border-outline-variant/30 flex-shrink-0">
                    <span class="font-headline text-xl font-bold text-primary leading-none">{{ $date->format('d') }}</span>
                    <span class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mt-1">{{ $date->format('M') }}</span>
                </div>
                <div class="flex-grow min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-body font-semibold text-on-surface truncate">{{ $ev->booking->client_name ?? 'Event Sanggar' }}</span>
                        <span class="inline-block px-2 py-0.5 rounded text-[0.6rem] font-bold uppercase tracking-wider border {{ $sc }}">{{ $ev->status }}</span>
                    </div>
                    <div class="font-label text-xs text-on-surface-variant flex items-center gap-2 truncate">
                        <span><i class="bi bi-geo-alt me-1 opacity-70"></i>{{ $ev->venue }}</span>
                        <span>&bull;</span>
                        <span><i class="bi bi-clock me-1 opacity-70"></i>{{ \Carbon\Carbon::parse($ev->event_start)->format('H:i') }} WIB</span>
                    </div>
                </div>
                <div class="flex-shrink-0 text-right">
                    @if($daysLeft <= 3 && $daysLeft >= 0)
                        <span class="inline-block px-2 py-1 rounded bg-red-500/10 text-red-600 border border-red-500/20 font-label text-[0.65rem] font-bold uppercase tracking-widest">★ H-{{ $daysLeft }}</span>
                    @else
                        <span class="font-label text-xs font-bold text-outline uppercase tracking-widest">H-{{ $daysLeft }}</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-10 bg-surface-container-low rounded-lg border border-dashed border-outline-variant/50">
                <i class="bi bi-calendar-x text-3xl text-outline mb-3 inline-block"></i>
                <p class="font-body text-sm text-on-surface-variant">Tidak ada event mendatang.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.events.monitoring') }}" class="block w-full py-3 rounded-lg text-center font-label text-xs font-bold uppercase tracking-widest bg-surface-container-high text-primary hover:bg-surface-container-highest transition-colors border border-outline-variant/30">
                <i class="bi bi-binoculars me-2"></i>Buka Event Monitoring
            </a>
        </div>
    </div>

    <div class="lg:col-span-5 bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] flex flex-col h-full">
        <h3 class="font-headline text-lg text-primary font-semibold mb-6"><i class="bi bi-bell-fill me-2 text-secondary"></i>Sistem Alert</h3>

        <div class="space-y-4 flex-grow">
            @php $hasAlert = false; @endphp

            {{-- DP Verification badge --}}
            @php $dpPending = \App\Models\Booking::where('status','pending')->whereNotNull('payment_proof')->count(); @endphp
            @if($dpPending > 0)
            @php $hasAlert = true; @endphp
            <div class="flex gap-4 p-4 rounded-lg bg-primary/5 border-l-4 border-primary">
                <i class="bi bi-patch-check-fill text-primary text-xl flex-shrink-0"></i>
                <div>
                    <h4 class="font-body font-semibold text-primary text-sm mb-1">{{ $dpPending }} Bukti DP Menunggu Verifikasi</h4>
                    <p class="font-label text-xs text-on-surface-variant mb-2 leading-relaxed">Konfirmasi untuk mengunci jadwal dan memulai plotting kru.</p>
                    <a href="{{ route('admin.bookings.dp_verification') }}" class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-secondary hover:text-primary transition-colors underline underline-offset-2 decoration-2">
                        Verifikasi Sekarang &rarr;
                    </a>
                </div>
            </div>
            @endif

            {{-- Budget kritis --}}
            @php $criticalBudget = \App\Models\FinancialRecord::where('budget_warning', true)->count(); @endphp
            @if($criticalBudget > 0)
            @php $hasAlert = true; @endphp
            <div class="flex gap-4 p-4 rounded-lg bg-orange-500/5 border-l-4 border-orange-500">
                <i class="bi bi-exclamation-circle-fill text-orange-500 text-xl flex-shrink-0"></i>
                <div>
                    <h4 class="font-body font-semibold text-orange-700 text-sm mb-1">{{ $criticalBudget }} Event Budget Kritis</h4>
                    <p class="font-label text-xs text-on-surface-variant mb-2 leading-relaxed">Dana operasional hampir habis atau minus.</p>
                    <a href="{{ route('admin.financials.post_event_list') }}" class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-orange-600 hover:text-orange-800 transition-colors underline underline-offset-2 decoration-2">
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
            <div class="flex gap-4 p-4 rounded-lg bg-red-500/5 border-l-4 border-red-500">
                <i class="bi bi-bag-x-fill text-red-500 text-xl flex-shrink-0"></i>
                <div>
                    <h4 class="font-body font-semibold text-red-700 text-sm mb-1">{{ $overdueKostum }} Kostum Telat</h4>
                    <p class="font-label text-xs text-on-surface-variant mb-2 leading-relaxed">Vendor menunggu pengembalian kostum/logistik.</p>
                    <a href="{{ route('admin.costumes.index') }}" class="font-label text-[0.65rem] font-bold uppercase tracking-widest text-red-600 hover:text-red-800 transition-colors underline underline-offset-2 decoration-2">
                        Urus Pengembalian &rarr;
                    </a>
                </div>
            </div>
            @endif

            @if(!$hasAlert)
            <div class="flex flex-col items-center justify-center h-full py-8 text-center">
                <div class="w-16 h-16 rounded-full bg-green-500/10 flex items-center justify-center mb-4">
                    <i class="bi bi-check2-all text-green-600 text-3xl"></i>
                </div>
                <h4 class="font-headline text-lg text-on-surface font-semibold mb-1">Semua Aman</h4>
                <p class="font-label text-xs text-outline uppercase tracking-widest">Tidak ada alert sistem aktif</p>
            </div>
            @endif
        </div>

        <div class="mt-6 pt-6 border-t border-outline-variant/30">
            <a href="{{ route('admin.events.index') }}" class="block w-full py-3 rounded-lg text-center font-label text-xs font-bold uppercase tracking-widest bg-primary text-white hover:bg-primary-container shadow-lg transition-all">
                <i class="bi bi-arrow-right-circle me-2"></i>Buka Event Management
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
    Chart.defaults.borderColor = 'rgba(212,195,191,0.3)';
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
                    label: 'Revenue',
                    data: revenueData.map(d => d.revenue),
                    backgroundColor: '#361f1a', // primary
                    borderRadius: 4, borderSkipped: false,
                    barPercentage: 0.6,
                },
                {
                    label: 'Fixed Profit',
                    data: revenueData.map(d => d.profit),
                    backgroundColor: '#fcd400', // secondary container
                    borderRadius: 4, borderSkipped: false,
                    barPercentage: 0.6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(54,31,26,0.9)',
                    titleFont: { family: 'Manrope', size: 13, weight: 'bold' },
                    bodyFont: { family: 'Manrope', size: 13 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: { label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID') }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { weight: '600' } } },
                y: {
                    border: { display: false },
                    ticks: { callback: v => 'Rp ' + (v >= 1000000 ? (v/1000000).toFixed(0) + 'jt' : v) }
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
                backgroundColor: ['#fcd400', '#361f1a', '#e5beb5', '#827471', '#d4c3bf'], // Heritage palette override
                borderColor: '#ffffff',
                borderWidth: 3,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(54,31,26,0.9)',
                    bodyFont: { family: 'Manrope', size: 13, weight: 'bold' },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: { label: ctx => ' ' + ctx.label + ': ' + ctx.parsed + ' booking' }
                }
            }
        }
    });
</script>
@endsection
