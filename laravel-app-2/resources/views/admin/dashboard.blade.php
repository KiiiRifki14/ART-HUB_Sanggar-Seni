@extends('layouts.admin')

@section('title', 'Dashboard – ART-HUB')
@section('page_title', 'Executive Dashboard')
@section('page_subtitle', 'Ringkasan finansial & penjadwalan Sanggar Cahaya Gumilang.')

@section('content')

{{-- ── ROW 1: STAT CARDS ── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    {{-- Card 1 --}}
    <div class="bg-surface-container-lowest rounded-xl p-3.5 border border-outline-variant/30 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
        <div class="flex justify-between items-start mb-2">
            <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                <i data-lucide="lock" class="w-4 h-4"></i>
            </div>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary/10 text-primary border border-primary/20 text-[0.6rem] font-bold uppercase tracking-wider">
                <i data-lucide="lock" class="w-3 h-3"></i> Terkunci
            </span>
        </div>
        <div>
            <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-0.5">Laba Tetap Aman</div>
            <div class="font-headline text-xl text-primary font-bold">Rp {{ number_format($lockedProfit, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Card 2 --}}
    <div class="bg-surface-container-lowest rounded-xl p-3.5 border border-outline-variant/30 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
        <div class="flex justify-between items-start mb-2">
            <div class="w-9 h-9 rounded-lg bg-green-500/10 flex items-center justify-center text-green-600">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
            </div>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-500/10 text-green-700 border border-green-500/20 text-[0.6rem] font-bold uppercase tracking-wider">
                Aman
            </span>
        </div>
        <div>
            <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-0.5">Dana Cadangan Siap</div>
            <div class="font-headline text-xl text-green-600 font-bold">Rp {{ number_format($safetyBuffer, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Card 3 --}}
    <div class="bg-surface-container-lowest rounded-xl p-3.5 border border-red-500/30 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between relative overflow-hidden">
        @if($lateCount > 0)
        <div class="absolute top-0 right-0 bg-red-500 text-white text-[0.55rem] font-bold px-2 py-0.5 rounded-bl-lg uppercase tracking-wider">
            {{ $lateCount }} Insiden
        </div>
        @endif
        <div class="flex justify-between items-start mb-2">
            <div class="w-9 h-9 rounded-lg bg-red-500/10 flex items-center justify-center text-red-600">
                <i data-lucide="alert-octagon" class="w-4 h-4"></i>
            </div>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-red-500/10 text-red-700 border border-red-500/20 text-[0.6rem] font-bold uppercase tracking-wider">
                Denda
            </span>
        </div>
        <div>
            <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-0.5">Denda Kru Masuk</div>
            <div class="font-headline text-xl text-red-600 font-bold">Rp {{ number_format($totalPenalty, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Card 4 --}}
    <div class="bg-surface-container-lowest rounded-xl p-3.5 border border-outline-variant/30 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
        <div class="flex justify-between items-start mb-2">
            <div class="w-9 h-9 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary">
                <i data-lucide="calendar-days" class="w-4 h-4"></i>
            </div>
            @if($needPlotting > 0)
            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-secondary/10 text-secondary border border-secondary/20 text-[0.6rem] font-bold uppercase tracking-wider">
                {{ $needPlotting }} Butuh Plot
            </span>
            @endif
        </div>
        <div>
            <div class="font-label text-[0.65rem] uppercase tracking-widest text-outline font-bold mb-0.5">Acara Bulan Ini</div>
            <div class="font-headline text-xl text-on-surface font-bold">{{ $eventCount }} <span class="text-xs font-body text-outline font-normal">acara</span></div>
        </div>
    </div>
</div>

{{-- ── ROW 2: CHARTS ── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4">
    {{-- Chart 1: Revenue Line Chart (6 bulan) --}}
    <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl p-4 border border-outline-variant/30 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-3 gap-2">
            <div>
                <h3 class="font-headline text-sm text-primary font-bold mb-0.5 flex items-center gap-1.5">
                    <i data-lucide="trending-up" class="w-4 h-4 text-secondary"></i>
                    Pendapatan & Laba Bersih
                </h3>
                <p class="font-label text-[0.6rem] uppercase tracking-widest text-outline">Proyeksi 6 Bulan (Termasuk Acara Mendatang)</p>
            </div>
            <div class="flex gap-3 font-label text-[0.6rem] font-bold uppercase tracking-wider text-on-surface-variant">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-primary"></span> Pendapatan</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-secondary"></span> Laba Tetap</span>
            </div>
        </div>
        <div class="relative h-[210px] w-full">
            <canvas id="chartRevenue"></canvas>
        </div>
    </div>

    {{-- Chart 2: Status Donut --}}
    <div class="bg-surface-container-lowest rounded-xl p-4 border border-outline-variant/30 shadow-sm flex flex-col">
        <div class="mb-3">
            <h3 class="font-headline text-sm text-primary font-bold mb-0.5 flex items-center gap-1.5">
                <i data-lucide="pie-chart" class="w-4 h-4 text-secondary"></i>
                Distribusi Status
            </h3>
            <p class="font-label text-[0.6rem] uppercase tracking-widest text-outline">Semua waktu booking</p>
        </div>
        <div class="flex-grow flex items-center justify-center relative min-h-[160px]">
            <canvas id="chartStatus"></canvas>
        </div>
    </div>
</div>

{{-- ── ROW 3: UPCOMING EVENTS + ALERTS ── --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-3">
    <div class="lg:col-span-7 bg-surface-container-lowest rounded-xl p-4 border border-outline-variant/30 shadow-sm">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-headline text-sm text-primary font-bold flex items-center gap-1.5">
                <i data-lucide="clock" class="w-4 h-4 text-secondary"></i>
                Acara Mendatang
            </h3>
            <a href="{{ route('admin.events.monitoring') }}" class="font-label text-[0.6rem] font-bold uppercase tracking-widest text-secondary hover:text-primary transition-colors underline underline-offset-4 decoration-2 decoration-secondary/30">Lihat Semua</a>
        </div>

        <div class="space-y-2">
            @forelse($upcomingEvents as $ev)
            @php
                $date = \Carbon\Carbon::parse($ev->event_date);
                $daysLeft = now()->startOfDay()->diffInDays($date->startOfDay(), false);
                $statusColors = ['ready'=>'text-green-600 bg-green-500/10 border-green-500/20','planning'=>'text-red-600 bg-red-500/10 border-red-500/20','confirmed'=>'text-blue-600 bg-blue-500/10 border-blue-500/20'];
                $sc = $statusColors[$ev->status] ?? 'text-gray-600 bg-gray-500/10 border-gray-500/20';
            @endphp
            <div class="flex items-center gap-3 p-2 rounded-lg bg-surface-container-low border border-outline-variant/20 hover:bg-surface-container transition-colors duration-200">
                <div class="flex flex-col items-center justify-center w-11 h-11 rounded-lg bg-surface-container-lowest shadow-sm border border-outline-variant/30 flex-shrink-0">
                    <span class="font-headline text-sm font-bold text-primary leading-none">{{ $date->format('d') }}</span>
                    <span class="font-label text-[0.55rem] uppercase tracking-widest text-outline font-bold mt-0.5">{{ $date->format('M') }}</span>
                </div>
                <div class="flex-grow min-w-0">
                    <div class="flex items-center gap-1.5 mb-0.5">
                        <span class="font-body font-bold text-xs text-on-surface truncate">{{ $ev->booking->client_name ?? 'Event Sanggar' }}</span>
                        <span class="inline-block px-1.5 py-0.2 rounded-full text-[0.55rem] font-bold uppercase tracking-wider border {{ $sc }}">{{ $ev->status }}</span>
                    </div>
                    <div class="font-label text-[0.65rem] text-on-surface-variant flex items-center gap-1.5 truncate">
                        <span class="flex items-center gap-0.5"><i data-lucide="map-pin" class="w-3 h-3 opacity-70"></i>{{ $ev->venue }}</span>
                        <span class="text-outline">&bull;</span>
                        <span class="flex items-center gap-0.5"><i data-lucide="clock" class="w-3 h-3 opacity-70"></i>{{ \Carbon\Carbon::parse($ev->event_start)->format('H:i') }}</span>
                    </div>
                </div>
                <div class="flex-shrink-0 text-right">
                    @if($daysLeft <= 3 && $daysLeft >= 0)
                        <span class="inline-block px-1.5 py-0.5 rounded bg-red-500/10 text-red-600 border border-red-500/20 font-label text-[0.55rem] font-bold uppercase tracking-widest">★ H-{{ $daysLeft }}</span>
                    @else
                        <span class="font-label text-[0.55rem] font-bold text-outline uppercase tracking-widest">H-{{ $daysLeft }}</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-6 bg-surface-container-low rounded-lg border border-dashed border-outline-variant/50">
                <i data-lucide="calendar-x" class="w-8 h-8 text-outline mx-auto mb-2 opacity-50"></i>
                <p class="font-body text-xs text-on-surface-variant">Tidak ada event mendatang.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.events.monitoring') }}" class="block w-full py-2 rounded-lg text-center font-label text-[0.65rem] font-bold uppercase tracking-widest bg-surface-container-high text-primary hover:bg-surface-container-highest transition-colors border border-outline-variant/30 flex items-center justify-center gap-1.5">
                <i data-lucide="eye" class="w-3.5 h-3.5"></i> Buka Event Monitoring
            </a>
        </div>
    </div>

    <div class="lg:col-span-5 bg-surface-container-lowest rounded-xl p-4 border border-outline-variant/30 shadow-sm flex flex-col h-full">
        <h3 class="font-headline text-sm text-primary font-bold mb-3 flex items-center gap-1.5">
            <i data-lucide="bell" class="w-4 h-4 text-secondary"></i>
            Sistem Alert
        </h3>

        <div class="space-y-2 flex-grow">
            @php $hasAlert = false; @endphp

            {{-- DP Verification alert --}}
            @php $dpPending = \App\Models\Booking::where('status','pending')->whereNotNull('payment_proof')->count(); @endphp
            @if($dpPending > 0)
            @php $hasAlert = true; @endphp
            <div class="flex gap-3 p-2.5 rounded-lg bg-primary/5 border-l-4 border-primary">
                <i data-lucide="check-circle" class="w-4 h-4 text-primary flex-shrink-0 mt-0.5"></i>
                <div>
                    <h4 class="font-body font-bold text-primary text-xs mb-0.5">{{ $dpPending }} Bukti DP Menunggu Verifikasi</h4>
                    <p class="font-label text-[0.65rem] text-on-surface-variant mb-1.5 leading-relaxed">Konfirmasi untuk mengunci jadwal.</p>
                    <a href="{{ route('admin.bookings.dp_verification') }}" class="font-label text-[0.55rem] font-bold uppercase tracking-widest text-secondary hover:text-primary transition-colors underline underline-offset-2 decoration-2 flex items-center gap-0.5">
                        Verifikasi Sekarang <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>
            @endif

            {{-- Budget kritis --}}
            @php $criticalBudget = \App\Models\FinancialRecord::where('budget_warning', true)->count(); @endphp
            @if($criticalBudget > 0)
            @php $hasAlert = true; @endphp
            <div class="flex gap-3 p-2.5 rounded-lg bg-orange-500/5 border-l-4 border-orange-500">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-orange-500 flex-shrink-0 mt-0.5"></i>
                <div>
                    <h4 class="font-body font-bold text-orange-700 text-xs mb-0.5">{{ $criticalBudget }} Event Budget Kritis</h4>
                    <p class="font-label text-[0.65rem] text-on-surface-variant mb-1.5 leading-relaxed">Dana operasional hampir habis/minus.</p>
                    <a href="{{ route('admin.financials.post_event_list') }}" class="font-label text-[0.55rem] font-bold uppercase tracking-widest text-orange-600 hover:text-orange-800 transition-colors underline underline-offset-2 decoration-2 flex items-center gap-0.5">
                        Cek Laporan <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
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
            <div class="flex gap-3 p-2.5 rounded-lg bg-red-500/5 border-l-4 border-red-500">
                <i data-lucide="archive" class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5"></i>
                <div>
                    <h4 class="font-body font-bold text-red-700 text-xs mb-0.5">{{ $overdueKostum }} Kostum Telat</h4>
                    <p class="font-label text-[0.65rem] text-on-surface-variant mb-1.5 leading-relaxed">Vendor menunggu pengembalian kostum.</p>
                    <a href="{{ route('admin.costumes.index') }}" class="font-label text-[0.55rem] font-bold uppercase tracking-widest text-red-600 hover:text-red-800 transition-colors underline underline-offset-2 decoration-2 flex items-center gap-0.5">
                        Urus Pengembalian <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>
            @endif

            @if(!$hasAlert)
            <div class="flex flex-col items-center justify-center h-full py-8 text-center">
                <div class="w-12 h-12 rounded-full bg-green-500/10 flex items-center justify-center mb-2 text-green-600">
                    <i data-lucide="check" class="w-6 h-6"></i>
                </div>
                <h4 class="font-headline text-sm text-on-surface font-bold mb-0.5">Semua Aman</h4>
                <p class="font-label text-[0.55rem] text-outline uppercase tracking-widest">Tidak ada alert sistem aktif</p>
            </div>
            @endif
        </div>

        <div class="mt-4 pt-4 border-t border-outline-variant/30">
            <a href="{{ route('admin.events.index') }}" class="block w-full py-2.5 rounded-lg text-center font-label text-[0.65rem] font-bold uppercase tracking-widest bg-primary text-white hover:bg-primary-container shadow-sm transition-all flex items-center justify-center gap-1.5">
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i> Buka Event Management
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
                    backgroundColor: '#361f1a', // primary
                    borderRadius: 4, borderSkipped: false,
                    barPercentage: 0.6,
                },
                {
                    label: 'Laba Tetap',
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
                backgroundColor: ['#fcd400', '#361f1a', '#e5beb5', '#827471', '#d4c3bf'], // Heritage palette override
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
                    callbacks: { label: ctx => ' ' + ctx.label + ': ' + ctx.parsed + ' booking' }
                }
            }
        }
    });
</script>
@endsection
