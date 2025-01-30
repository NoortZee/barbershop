@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="pagination-nav">
        <div class="pagination-wrapper">
            <div class="pagination-links">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="btn btn-outline-secondary disabled">
                        {!! __('pagination.previous') !!}
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-outline-secondary">
                        {!! __('pagination.previous') !!}
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="btn btn-outline-secondary disabled">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="btn btn-premium">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="btn btn-outline-secondary">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-outline-secondary">
                        {!! __('pagination.next') !!}
                    </a>
                @else
                    <span class="btn btn-outline-secondary disabled">
                        {!! __('pagination.next') !!}
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif

<style>
.pagination-nav {
    margin-top: 2rem;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
}

.pagination-links {
    display: flex;
    gap: 0.5rem;
}

.pagination-links .btn {
    min-width: 40px;
    height: 40px;
    padding: 0 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.pagination-links .btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-links .btn-premium {
    color: white;
}
</style> 