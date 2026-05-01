@extends('layouts.admin')
@section('title', 'Post-Event Update – ART-HUB')
@section('page_title', 'Post-Event Update')
@section('page_subtitle', 'Input biaya riil lapangan pasca pementasan')

@section('content')

<div class="flex justify-between items-center mb-6">
    <p class="font-label text-xs uppercase tracking-widest text-outline">Daftar pementasan yang sudah lewat dan butuh pencatatan biaya operasional lapangan (Bensin, Makan, dll).</p>
</div>

<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0_12px_24px_rgba(54,31,26,0.03)] overflow-hidden">
    <div class="divide-y divide-outline-variant/20">
        @forelse($events as $event)
        @php
            $booking  = $event->booking;
            $finance  = $event->financialRecord;
            $costs    = $finance?->operationalCosts;
            $totalCost= $costs?->sum('actual_amount') ?? 0;
            $budget   = $finance?->operational_budget ?? 0;
            $pct      = $budget > 0 ? min(100, round($totalCost / $budget * 100)) : 0;
            $isDone      = $costs && $costs->count() > 0;
            $budgetColor = $pct >= 100 ? 'bg-red-500' : ($pct >= 80 ? 'bg-orange-500' : 'bg-green-500');
            $textColor   = $pct >= 100 ? 'text-red-600' : ($pct >= 80 ? 'text-orange-600' : 'text-green-600');
        @endphp
        <div class="flex flex-col sm:flex-row sm:items-center p-6 gap-6 hover:bg-surface-container-low/50 transition-colors">
            {{-- Event Info --}}
            <div class="flex-grow">
                <div class="flex items-center gap-3 mb-2 flex-wrap">
                    <span class="font-headline font-semibold text-primary text-lg">{{ $booking->client_name ?? 'Event Sanggar' }}</span>
                    <span class="inline-block px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container border border-secondary/20 font-label text-[0.65rem] font-bold tracking-wider">
                        {{ $event->event_code }}
                    </span>
                    @if($isDone)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded border border-green-500/20 bg-green-500/10 font-label text-[0.6rem] font-bold uppercase tracking-wider text-green-600">
                            <i class="bi bi-check-circle-fill"></i> Biaya Terinput
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded border border-orange-500/20 bg-orange-500/10 font-label text-[0.6rem] font-bold uppercase tracking-wider text-orange-600">
                            <i class="bi bi-exclamation-circle-fill"></i> Belum Input
                        </span>
                    @endif
                </div>
                <div class="font-label text-xs text-on-surface-variant flex items-center gap-3">
                    <span class="flex items-center gap-1"><i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</span>
                    <span class="text-outline-variant">•</span>
                    <span class="flex items-center gap-1 truncate"><i class="bi bi-geo-alt"></i> {{ $event->venue }}</span>
                </div>
            </div>

            {{-- Budget progress --}}
            @if($finance)
            <div class="min-w-[200px] w-full sm:w-auto">
                <div class="flex justify-between font-label text-[0.65rem] font-bold uppercase tracking-widest text-outline mb-1.5">
                    <span>Terpakai</span>
                    <span class="{{ $textColor }}">{{ $pct }}%</span>
                </div>
                @php $widthStyle = "width: {$pct}%"; @endphp
                <div class="h-2 w-full bg-surface-container-highest rounded-full overflow-hidden mb-1.5">
                    <div class="h-full {{ $budgetColor }} rounded-full transition-all duration-500" style="{{ $widthStyle }}"></div>
                </div>
                <div class="flex justify-between font-body text-xs text-on-surface-variant">
                    <span>Rp {{ number_format($totalCost, 0, ',', '.') }}</span>
                    <span class="text-outline">/ Rp {{ number_format($budget, 0, ',', '.') }}</span>
                </div>
            </div>
            @else
            <div class="min-w-[200px] text-center w-full sm:w-auto">
                <span class="font-label text-xs uppercase tracking-widest text-outline">Finansial belum terbentuk</span>
            </div>
            @endif

            {{-- CTA --}}
            <div class="flex-shrink-0 w-full sm:w-auto mt-4 sm:mt-0">
                @if($finance)
                    @if($isDone)
                    <a href="{{ route('admin.financials.post_event', $event->id) }}"
                       class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-5 py-2.5 rounded-lg border border-green-500/30 text-green-600 hover:bg-green-500/10 transition-colors font-label text-xs font-bold uppercase tracking-widest">
                        <i class="bi bi-pencil-fill"></i> Edit Biaya
                    </a>
                    @else
                    <a href="{{ route('admin.financials.post_event', $event->id) }}"
                       class="w-full sm:w-auto inline-flex justify-center items-center gap-2 bg-gradient-to-br from-primary-container to-primary text-white px-5 py-2.5 rounded-lg hover:opacity-90 transition-all shadow-md font-label text-xs font-bold uppercase tracking-widest">
                        <i class="bi bi-clipboard-plus-fill"></i> Input Biaya
                    </a>
                    @endif
                @else
                <span class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 rounded-lg bg-surface-container text-outline font-label text-xs font-bold uppercase tracking-widest cursor-not-allowed">
                    Konfirmasi DP Dulu
                </span>
                @endif
            </div>
        </div>
        @empty
        <div class="px-6 py-20 text-center">
            <i class="bi bi-calendar-check text-4xl text-outline mb-4 block"></i>
            <p class="font-headline text-lg text-on-surface font-semibold mb-1">Belum ada pementasan</p>
            <p class="font-label text-xs uppercase tracking-widest text-outline">Pementasan yang sudah selesai akan muncul di sini</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
