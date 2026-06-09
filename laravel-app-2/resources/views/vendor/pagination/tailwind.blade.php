@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
    <div class="flex justify-between flex-1 sm:hidden">
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-[0.7rem] font-bold uppercase tracking-wider font-body text-outline border border-outline-variant/30 rounded-lg bg-surface-container-low cursor-not-allowed opacity-50">
                <i class="bi bi-chevron-left text-xs"></i> Sebelumnya
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-[0.7rem] font-bold uppercase tracking-wider font-body text-primary border border-primary/25 rounded-lg bg-white hover:bg-primary/5 hover:border-primary/50 transition-all shadow-sm">
                <i class="bi bi-chevron-left text-xs"></i> Sebelumnya
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-[0.7rem] font-bold uppercase tracking-wider font-body text-primary border border-primary/25 rounded-lg bg-white hover:bg-primary/5 hover:border-primary/50 transition-all shadow-sm">
                Berikutnya <i class="bi bi-chevron-right text-xs"></i>
            </a>
        @else
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-[0.7rem] font-bold uppercase tracking-wider font-body text-outline border border-outline-variant/30 rounded-lg bg-surface-container-low cursor-not-allowed opacity-50">
                Berikutnya <i class="bi bi-chevron-right text-xs"></i>
            </span>
        @endif
    </div>

    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
        {{-- Info teks --}}
        <div>
            <p class="font-label text-[0.68rem] text-on-surface-variant font-medium">
                Menampilkan
                <span class="font-bold text-primary">{{ $paginator->firstItem() }}</span>
                –
                <span class="font-bold text-primary">{{ $paginator->lastItem() }}</span>
                dari
                <span class="font-bold text-primary">{{ $paginator->total() }}</span>
                data pembatalan
            </p>
        </div>

        {{-- Tombol halaman --}}
        <div>
            <span class="inline-flex items-center gap-1">
                {{-- Tombol Prev --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-outline-variant/30 bg-surface-container-low text-outline cursor-not-allowed opacity-50" title="Halaman Pertama">
                        <i class="bi bi-chevron-left text-xs"></i>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-primary/25 bg-white text-primary hover:bg-primary/5 hover:border-primary/50 transition-all shadow-sm" title="{{ __('pagination.previous') }}">
                        <i class="bi bi-chevron-left text-xs"></i>
                    </a>
                @endif

                {{-- Nomor halaman --}}
                @foreach ($elements as $element)
                    {{-- Tanda elipsis --}}
                    @if (is_string($element))
                        <span class="inline-flex items-center justify-center w-8 h-8 text-[0.7rem] font-bold text-outline">{{ $element }}</span>
                    @endif

                    {{-- Array nomor halaman --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-[0.7rem] font-bold bg-[#361f1a] text-[#FCD400] border border-[#361f1a] shadow-md">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-[0.7rem] font-bold border border-outline-variant/30 bg-white text-on-surface hover:bg-[#361f1a]/5 hover:border-[#361f1a]/30 hover:text-primary transition-all shadow-sm">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Tombol Next --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-primary/25 bg-white text-primary hover:bg-primary/5 hover:border-primary/50 transition-all shadow-sm" title="{{ __('pagination.next') }}">
                        <i class="bi bi-chevron-right text-xs"></i>
                    </a>
                @else
                    <span aria-disabled="true" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-outline-variant/30 bg-surface-container-low text-outline cursor-not-allowed opacity-50" title="Halaman Terakhir">
                        <i class="bi bi-chevron-right text-xs"></i>
                    </span>
                @endif
            </span>
        </div>
    </div>
</nav>
@endif
