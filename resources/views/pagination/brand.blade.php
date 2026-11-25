@if ($paginator->hasPages())
    <nav class="flex flex-col items-center justify-between gap-3 px-4 py-4 text-sm sm:flex-row sm:px-6" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="inline-flex min-h-[44px] items-center text-muted">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex min-h-[44px] items-center font-semibold text-brand">Previous</a>
        @endif
        <div class="flex flex-wrap items-center justify-center gap-1">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex min-h-[44px] min-w-[36px] items-center justify-center px-2 text-muted">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-lg bg-brand px-3 font-semibold text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-lg px-3 font-semibold text-brand hover:bg-brand-soft">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex min-h-[44px] items-center font-semibold text-brand">Next</a>
        @else
            <span class="inline-flex min-h-[44px] items-center text-muted">Next</span>
        @endif
    </nav>
@endif
